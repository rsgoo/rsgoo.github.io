---
title: MySQL性能优化之通过Explain进行索引优化
date: 2019-08-24 10:59:14
tags:
    - MySQL
categories:
    - 数据库
    - MySQL
---

**写在前面**: 前面了解 `Explain` 的基础知识，接下来我们通过好一些案列来实操一下，通过实践加深对前面两篇笔记的了解。

<!--more-->

---

#### 案例一

```SQL
exlain select d1.name, (select id from t3) d2
from (select id, name from t1 where other_colume = '') d1
union
(select name, id from t2);
```

依据 explain 中 `id`值可知执行先后顺序分别是 **4 > 3 > 2 > 1 > NULL**。说明如下

![](/images/blog/201908/7-explain执行案例一.png)

> 执行顺序1（id为4）: select_type为`UNION`，执行的SQL为`select name, id from t2`

> 执行顺序2（id为3）: select_type为`DERIVED`，由于SQL查询解析时是从`from`开始，后面紧接着的sql查询语句又包含在`from`中，因此select_type为`DERIVED`。执行SQL为`select id, name from t1 where other_colume = ''`

> 执行顺序3（id为2）: select_type为`SUBQUERY`，执行的SQL为`select id from t3`

> 执行顺序4（id为1）: select_type列值为`PRIMARY`表示该查询为外层查询，table列标识为`<derived3>`表示查询的结果来自于一个衍生表，`<derived3>`中的3表示该查询衍生自第`id为3`的查询。

> 执行顺序4（id为1）: select_type列值为`UNION RESULT`表示从临时查询结果集中读取行，table列值为`<union 1,4>` 表示对id为1和4的两个select结果进行`UNION`操作。

---

#### 案例二 之 单表优化

数据准备

```SQL
-- 建表语句
create table article(
    id int(10) unsigned not null PRIMARY key auto_increment,
    author_id int(10) unsigned not null comment '作者id',
    category_id int(10) unsigned not null comment '分类id',
    views int(10) unsigned not null comment '查看次数',
    comments int(10) unsigned not null comment '评论次数',
    title varchar(255) not null comment '文章标题',
    content text not null comment '内容'
)default character set utf8mb4;

-- 数据插入
insert into article(author_id, category_id, views, comments, title, content)
values
(1,1,1,1,'标题1','内容1'),
(2,2,2,2,'标题2','内容2'),
(3,3,3,3,'标题3','内容3'),
(4,4,4,4,'标题4','内容4'),
(5,5,5,5,'标题5','内容5'),
(6,6,6,6,'标题6','内容6');
```

- 查询 category_id 为1 且 comments 大于1情况下, views 最多的article_id

    ```SQL
    -- 查询sql
    mysql> select id,author_id from article where category_id=1 and comments>1 order by views desc limit 1;
    +----+-----------+
    | id | author_id |
    +----+-----------+
    |  3 |         1 |
    +----+-----------+
    1 row in set (0.00 sec)

    --查看执行计划，得到如下可优化点
    ---- 1：type 为 ALL 全表扫描；
    ---- 2：Extra 出现了 Using filesort
    mysql> explain select id,author_id from article where category_id=1 and comments>1 order by views desc limit 1;
    +----+-------------+---------+------------+------+---------------+------+---------+------+------+----------+-----------------------------+
    | id | select_type | table   | partitions | type | possible_keys | key  | key_len | ref  | rows | filtered | Extra                       |
    +----+-------------+---------+------------+------+---------------+------+---------+------+------+----------+-----------------------------+
    |  1 | SIMPLE      | article | NULL       | ALL  | NULL          | NULL | NULL    | NULL |    6 |    16.67 | Using where; Using filesort |
    +----+-------------+---------+------------+------+---------------+------+---------+------+------+----------+-----------------------------+
    1 row in set, 1 warning (0.00 sec)

    -- 尝试优化
    ---- 根据查询条件建立组合索引：
    mysql> create index idx_article_ccv on article(category_id,comments,views);

    -- 查看刚建的索引
    mysql> show index from article;
    +---------+------------+-----------------+--------------+-------------+-----------+-------------+----------+--------+------+------------+---------+---------------+
    | Table   | Non_unique | Key_name        | Seq_in_index | Column_name | Collation | Cardinality | Sub_part | Packed | Null | Index_type | Comment | Index_comment |
    +---------+------------+-----------------+--------------+-------------+-----------+-------------+----------+--------+------+------------+---------+---------------+
    | article |          0 | PRIMARY         |            1 | id          | A         |           6 |     NULL | NULL   |      | BTREE      |         |               |
    | article |          1 | idx_article_ccv |            1 | category_id | A         |           5 |     NULL | NULL   |      | BTREE      |         |               |
    | article |          1 | idx_article_ccv |            2 | comments    | A         |           6 |     NULL | NULL   |      | BTREE      |         |               |
    | article |          1 | idx_article_ccv |            3 | views       | A         |           6 |     NULL | NULL   |      | BTREE      |         |               |
    +---------+------------+-----------------+--------------+-------------+-----------+-------------+----------+--------+------+------------+---------+---------------+
    4 rows in set (0.02 sec)

    -- 再次执行刚才的查询SQL
    ---- 1: type 由 ALL 变成了 range
    ---- 2: Extra 还是出现了 Using filesort, //看来还得优化
    ---- 3: MySQL 无法再对后面 view 部分进行检索，即是range 类型查询字段后面的字段无效
    mysql> explain select id,author_id from article where category_id=1 and comments>1 order by views desc limit 1;
    +----+-------------+---------+------------+-------+-----------------+-----------------+---------+------+------+----------+---------------------------------------+
    | id | select_type | table   | partitions | type  | possible_keys   | key             | key_len | ref  | rows | filtered | Extra                                 |
    +----+-------------+---------+------------+-------+-----------------+-----------------+---------+------+------+----------+---------------------------------------+
    |  1 | SIMPLE      | article | NULL       | range | idx_article_ccv | idx_article_ccv | 8       | NULL |    1 |   100.00 | Using index condition; Using filesort |
    +----+-------------+---------+------------+-------+-----------------+-----------------+---------+------+------+----------+---------------------------------------+
    1 row in set, 1 warning (0.00 sec)

    -- 删除之前所建立的索引
    mysql> drop index idx_article_ccv on article;

    -- 再次尝试建立索引
    ---- 根据查询条件建立组合索引，这次去掉 comments
    mysql> create index idx_article_cv on article(category_id,views);

    -- 再次查询执行计划
    ---- type 变为了ref
    ---- ref  变为le const
    ---- Extra 没有 Using filesort
    mysql> explain select id,author_id from article where category_id=1 and comments>1 order by views desc limit 1;
    +----+-------------+---------+------------+------+----------------+----------------+---------+-------+------+----------+-------------+
    | id | select_type | table   | partitions | type | possible_keys  | key            | key_len | ref   | rows | filtered | Extra       |
    +----+-------------+---------+------------+------+----------------+----------------+---------+-------+------+----------+-------------+
    |  1 | SIMPLE      | article | NULL       | ref  | idx_article_cv | idx_article_cv | 4       | const |    2 |    33.33 | Using where |
    +----+-------------+---------+------------+------+----------------+----------------+---------+-------+------+----------+-------------+
    1 row in set, 1 warning (0.00 sec)

    ```

---

#### 案例三 之 双表优化

数据准备

```SQL
-- 建表语句
create table class (
    id int(10) unsigned not null PRIMARY key auto_increment,
    card int(10) unsigned not null comment '分类id'
)default character set utf8mb4;

create table book (
    bookid int(10) unsigned not null PRIMARY key auto_increment,
    card int(10) unsigned not null comment '分类id'
)default character set utf8mb4;

-- 数据插入
insert into class(id,card)
values
(1,10),(2,7),(3,3),(4,13),(5,17),
(6,4),(7,9),(8,13),(9,19),(10,16),
(11,20),(12,13),(13,3),(14,15),(15,5),
(16,20),(17,6),(18,9),(19,6),(20,4);

-- 数据插入
insert into book(bookid,card)
values
(1,2),(2,18),(3,3),(4,2),(5,20),
(6,15),(7,11),(8,13),(9,8),(10,4),
(11,13),(12,14),(13,10),(14,7),(15,3),
(16,16),(17,10),(18,3),(19,5),(20,14);

mysql> explain select * from class left join book on class.card=book.card;
+----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+----------------------------------------------------+
| id | select_type | table | partitions | type | possible_keys | key  | key_len | ref  | rows | filtered | Extra                                              |
+----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+----------------------------------------------------+
|  1 | SIMPLE      | class | NULL       | ALL  | NULL          | NULL | NULL    | NULL |   20 |   100.00 | NULL                                               |
|  1 | SIMPLE      | book  | NULL       | ALL  | NULL          | NULL | NULL    | NULL |   20 |   100.00 | Using where; Using join buffer (Block Nested Loop) |
+----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+----------------------------------------------------+
2 rows in set, 1 warning (0.00 sec)

-- 先假设给class 的card加索引

mysql> create index idx_card on class(card);

mysql> explain select * from class left join book on class.card=book.card and class.id>0;
+----+-------------+-------+------------+-------+---------------+----------+---------+------+------+----------+----------------------------------------------------+
| id | select_type | table | partitions | type  | possible_keys | key      | key_len | ref  | rows | filtered | Extra                                              |
+----+-------------+-------+------------+-------+---------------+----------+---------+------+------+----------+----------------------------------------------------+
|  1 | SIMPLE      | class | NULL       | index | NULL          | idx_card | 4       | NULL |   20 |   100.00 | Using index                                        |
|  1 | SIMPLE      | book  | NULL       | ALL   | NULL          | NULL     | NULL    | NULL |   20 |   100.00 | Using where; Using join buffer (Block Nested Loop) |
+----+-------------+-------+------------+-------+---------------+----------+---------+------+------+----------+----------------------------------------------------+
2 rows in set, 1 warning (0.00 sec)

-- 删除 idx_card 然后在 book表上加索引试试
mysql> alter table class drop index idx_card;
mysql> create index idx_card on book(card);

-- 查看一下执行计划
-- 对比上一个执行计划，type 由 ALL 变为了 ref，ref列值指向runoob.class.card，rows 由 20 + 20变为 20+1
mysql> explain select * from class left join book on book.card=class.card;
+----+-------------+-------+------------+------+---------------+----------+---------+-------------------+------+----------+-------------+
| id | select_type | table | partitions | type | possible_keys | key      | key_len | ref               | rows | filtered | Extra       |
+----+-------------+-------+------------+------+---------------+----------+---------+-------------------+------+----------+-------------+
|  1 | SIMPLE      | class | NULL       | ALL  | NULL          | NULL     | NULL    | NULL              |   20 |   100.00 | NULL        |
|  1 | SIMPLE      | book  | NULL       | ref  | idx_card      | idx_card | 4       | runoob.class.card |    1 |   100.00 | Using index |
+----+-------------+-------+------------+------+---------------+----------+---------+-------------------+------+----------+-------------+
2 rows in set, 1 warning (0.00 sec)

-- 使用右链接查询时
mysql> create index idx_card on book(card);

mysql> explain select * from book right join class on book.card=class.card;
+----+-------------+-------+------------+------+---------------+----------+---------+-------------------+------+----------+-------------+
| id | select_type | table | partitions | type | possible_keys | key      | key_len | ref               | rows | filtered | Extra       |
+----+-------------+-------+------------+------+---------------+----------+---------+-------------------+------+----------+-------------+
|  1 | SIMPLE      | class | NULL       | ALL  | NULL          | NULL     | NULL    | NULL              |   20 |   100.00 | NULL        |
|  1 | SIMPLE      | book  | NULL       | ref  | idx_card      | idx_card | 4       | runoob.class.card |    1 |   100.00 | Using index |
+----+-------------+-------+------------+------+---------------+----------+---------+-------------------+------+----------+-------------+
2 rows in set, 1 warning (0.00 sec)
```

结论：Left join 条件用于确定如何从 **右表** 搜索行（因为左表都是匹配的），所以右表一定要建立索引。换句话说 “左连接因为主表必定全部扫描，子表不一定全部对应，所以加在子表好一些”。

![](https://www.runoob.com/wp-content/uploads/2019/01/sql-join.png)

---

#### 案例三 之 三表优化

```SQL
-- 建表语句
create table phone (
    phoneid int(10) unsigned not null PRIMARY key auto_increment,
    card int(10) unsigned not null comment '分类id'
)default character set utf8mb4;

-- 数据插入
insert into phone(phoneid,card)
values
(1,16),(2,17),(3,17),(4,14),(5,16),
(6,20),(7,11),(8,15),(9,3),(10,7),
(11,5),(12,5),(13,7),(14,3),(15,11),
(16,4),(17,9),(18,12),(19,13),(20,8);

---------------------------------------
-- 查看原始执行计划
mysql> explain select * from class left join book on class.card = book.card left join phone on class.card = phone.card;
+----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+----------------------------------------------------+
| id | select_type | table | partitions | type | possible_keys | key  | key_len | ref  | rows | filtered | Extra                                              |
+----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+----------------------------------------------------+
|  1 | SIMPLE      | class | NULL       | ALL  | NULL          | NULL | NULL    | NULL |   20 |   100.00 | NULL                                               |
|  1 | SIMPLE      | book  | NULL       | ALL  | NULL          | NULL | NULL    | NULL |   20 |   100.00 | Using where; Using join buffer (Block Nested Loop) |
|  1 | SIMPLE      | phone | NULL       | ALL  | NULL          | NULL | NULL    | NULL |   20 |   100.00 | Using where; Using join buffer (Block Nested Loop) |
+----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+----------------------------------------------------+

-- 根据案例二的经验增加如下索引

mysql> create index idx_b_card on book(card);

mysql> create index idx_p_card on phone(card);

-- 查询增加索引后的执行计划

---- 最后两个type 均是 ref, rows = 20 + 1 + 1

mysql> explain select * from class left join book on class.card = book.card left join phone on class.card = phone.card;
+----+-------------+-------+------------+------+---------------+------------+---------+-------------------+------+----------+-------------+
| id | select_type | table | partitions | type | possible_keys | key        | key_len | ref               | rows | filtered | Extra       |
+----+-------------+-------+------------+------+---------------+------------+---------+-------------------+------+----------+-------------+
|  1 | SIMPLE      | class | NULL       | ALL  | NULL          | NULL       | NULL    | NULL              |   20 |   100.00 | NULL        |
|  1 | SIMPLE      | book  | NULL       | ref  | idx_b_card    | idx_b_card | 4       | runoob.class.card |    1 |   100.00 | Using index |
|  1 | SIMPLE      | phone | NULL       | ref  | idx_p_card    | idx_p_card | 4       | runoob.class.card |    1 |   100.00 | Using index |
+----+-------------+-------+------------+------+---------------+------------+---------+-------------------+------+----------+-------------+
3 rows in set, 1 warning (0.00 sec)
```
---

【SQL 优化结论】

- 尽可能减少 Join 语句中的 `Nested Loop` (嵌套循环)。

- 优先 `Nested Loop` 中的内层循

- 永远用小结果集驱动大的结果集。

- 当无法保证被驱动表的 Join 条件字段被索引且内存资源充足的前提下，不要太吝啬 `JoinBuffer` 设置

---

[参考来源：bilibili.MySQL高级_explain之热身Case](https://www.bilibili.com/video/av49181542/?p=208)
