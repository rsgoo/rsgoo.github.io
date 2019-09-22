---
title: MySQL优化之orderBy优化
date: 2019-08-27 21:33:03
tags:
    - MySQL
categories:
    - 数据库
    - MySQL
---

**写在前面**：`order by` 字句应该尽量使用 `Index` 方式排序，避免使用 `Filesort` 方式排序。

<!--more-->

1： MySQL支持两种方式的排序: `Filesort` 和 `Index`。Index 效率高，因为MySQL扫描本身就能完成排序。`Filesort` 方式效率较低，因为它需要的索引的基础上再次排序。

2： order by 满足两种情况时会使用 `Index` 方式排序

- 使用 `ORDER BY` 语句使用 **索引最左前列**

- 使用 `Where` 字句和 `Order by` 字句的条件列组合满足  **索引最左前列**

    ```sql
    explain select * from tableA where age>20 order by birth;
    ```

3：如果 `order by` 后的字段没有在索引列上，会产生 `Filesort`。`Filesort` 有两种算法：**单路排序** 和 **双路排序**。

- 双路排序：扫描磁盘两次，最终得到数据。【两次I/O】

    > 从磁盘取出排序字段，在buffer中进行排序，再从磁盘取其他字段。

- 单路排序：

    > 从磁盘读取查询所需要的 **所有列**，按照 order by 指定的列在 buffer 对它们进行排序，然后扫描排序后的列进行输出。单路排序的效率更快一些，它避免了第二次读取数据。并且把 `随机IO` 变成了 `顺序IO`。但是会带来使用更多的空间，因为他把每一行都保存在内存中。

    > 单路排序算法失效：取出的所有列的大小超过了 **sort_buffer_size** 的容量，导致实际上每次只能按照 **sort_buffer_size** 的大小取数据进行排序，拍完序后再取 **sort_buffer_size** 容量大小的数据，在排序....如此往复，从而多次 I/O

    > 优化策略：【调整 sort_buffer_size 参数的值】【调整max_length_for_sort_data 参数的值】

4：使用 **order by** 的一些优化策略

- 1：order by 时 `select *`是不太好的工程实践。应该只查询需要的字段。

    - 原因1：当查询的子弹大小总和小于 `max_length_for_sort_data` 并没排序的字段不是 `text || bolb` 类型时，会使用单路排序算法，否则会使用多路排序算法

    - 原因2：两种排序算法的数据都有可能超过 `sort_buffer_size` 的容量，超出之后，会创建 `tmp` 文件进行合并，从而导致多次 I/0。而使用单路排序算法出现的概率更多一些，所以要增加 `sort_buffer_size` 值大小。

- 2：尝试提高 `sort_buffer_size` 参数的值

- 3：尝试提高 `max_length_for_sort_data` 参数的值

![order-by是否使用索引排序分析](/images/blog/201908/8-order-by是否使用索引排序分析.jpeg)

---

数据准备

```sql
-- 建表语句
create table tableA(
    id int primary key not null auto_increment,
    age int,
    birth timestamp not null
)default  character set utf8mb4;

-- 数据插入
insert into tableA(age, birth) values(22, NOW());
insert into tableA(age, birth) values(23, NOW());
insert into tableA(age, birth) values(24, NOW());
insert into tableA(age, birth) values(25, NOW());
insert into tableA(age, birth) values(26, NOW());
insert into tableA(age, birth) values(27, NOW());

-- 建立组合索引
create index idx_ageBirth on tableA(age,birth);

-- order by排序字段顺序和索引字段顺序一致，不会产生文件排序
mysql> explain select * from tableA where age>20 order by age,birth;
+----+-------------+--------+------------+-------+---------------+--------------+---------+------+------+----------+--------------------------+
| id | select_type | table  | partitions | type  | possible_keys | key          | key_len | ref  | rows | filtered | Extra                    |
+----+-------------+--------+------------+-------+---------------+--------------+---------+------+------+----------+--------------------------+
|  1 | SIMPLE      | tableA | NULL       | index | idx_ageBirth  | idx_ageBirth | 9       | NULL |    6 |   100.00 | Using where; Using index |
+----+-------------+--------+------------+-------+---------------+--------------+---------+------+------+----------+--------------------------+

-- order by排序字段顺序和索引的顺序不一致，因此会产生文件排序。
mysql> explain select * from tableA where age>20 order by birth;
+----+-------------+--------+------------+-------+---------------+--------------+---------+------+------+----------+------------------------------------------+
| id | select_type | table  | partitions | type  | possible_keys | key          | key_len | ref  | rows | filtered | Extra                                    |
+----+-------------+--------+------------+-------+---------------+--------------+---------+------+------+----------+------------------------------------------+
|  1 | SIMPLE      | tableA | NULL       | index | idx_ageBirth  | idx_ageBirth | 9       | NULL |    6 |   100.00 | Using where; Using index; Using filesort |
+----+-------------+--------+------------+-------+---------------+--------------+---------+------+------+----------+------------------------------------------+

-- order by排序字段顺序和索引的顺序不一致，因此会产生文件排序。
mysql> explain select * from tableA where age>20 order by birth,age;
+----+-------------+--------+------------+-------+---------------+--------------+---------+------+------+----------+------------------------------------------+
| id | select_type | table  | partitions | type  | possible_keys | key          | key_len | ref  | rows | filtered | Extra                                    |
+----+-------------+--------+------------+-------+---------------+--------------+---------+------+------+----------+------------------------------------------+
|  1 | SIMPLE      | tableA | NULL       | index | idx_ageBirth  | idx_ageBirth | 9       | NULL |    6 |   100.00 | Using where; Using index; Using filesort |
+----+-------------+--------+------------+-------+---------------+--------------+---------+------+------+----------+------------------------------------------+

-- order by后不是同升同降都会使用到文件排序 Filesort
-- 索引是排好序的快速查找数据结构，不按照已经排序的规则去获取数据会产生文件内排序
mysql> explain select age,birth from tableA where birth>"2019-08-27 21:52:08" order by age desc,birth asc;
+----+-------------+--------+------------+-------+---------------+--------------+---------+------+------+----------+------------------------------------------+
| id | select_type | table  | partitions | type  | possible_keys | key          | key_len | ref  | rows | filtered | Extra                                    |
+----+-------------+--------+------------+-------+---------------+--------------+---------+------+------+----------+------------------------------------------+
|  1 | SIMPLE      | tableA | NULL       | index | NULL          | idx_ageBirth | 9       | NULL |    6 |    33.33 | Using where; Using index; Using filesort |
+----+-------------+--------+------------+-------+---------------+--------------+---------+------+------+----------+------------------------------------------+

-- order by后不是同升同降都会使用到文件排序 Filesort
mysql> explain select age,birth from tableA where birth>"2019-08-27 21:52:08" order by age asc,birth desc;
+----+-------------+--------+------------+-------+---------------+--------------+---------+------+------+----------+------------------------------------------+
| id | select_type | table  | partitions | type  | possible_keys | key          | key_len | ref  | rows | filtered | Extra                                    |
+----+-------------+--------+------------+-------+---------------+--------------+---------+------+------+----------+------------------------------------------+
|  1 | SIMPLE      | tableA | NULL       | index | NULL          | idx_ageBirth | 9       | NULL |    6 |    33.33 | Using where; Using index; Using filesort |
+----+-------------+--------+------------+-------+---------------+--------------+---------+------+------+----------+------------------------------------------+

```
---

`Group by` 的优化策略和原理和 `Order by` 趋同，唯一的一点是 `where` 条件优先级高于 `having`，能写在 `where` 限定的条件就不要去 `having` 限定。

---
笔记来自 [MySQL高级_为排序使用索引OrderBy](https://www.bilibili.com/video/av49181542/?p=226)
