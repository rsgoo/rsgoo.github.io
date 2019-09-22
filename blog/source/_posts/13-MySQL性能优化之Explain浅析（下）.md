---
title: MySQL性能优化之Explain浅析（下）
date: 2019-08-20 21:53:16
tags:
    - MySQL
categories:
    - 数据库
    - MySQL
---

**写在前面**: 接上篇 [MySQL性能优化之Explain浅析（上）](https://inscode.github.io/2019/08/19/MySQL%E6%80%A7%E8%83%BD%E4%BC%98%E5%8C%96%E4%B9%8BExplain%E6%B5%85%E6%9E%90%EF%BC%88%E4%B8%8A%EF%BC%89/)。

<!--more-->
---

#### 1: explain 的使用及其字段说明

```SQL
-- 得到的执行计划如下表
mysql> explain select * from employees;
+----+-------------+-----------+------------+------+---------------+------+---------+------+------+----------+-------+
| id | select_type | table     | partitions | type | possible_keys | key  | key_len | ref  | rows | filtered | Extra |
+----+-------------+-----------+------------+------+---------------+------+---------+------+------+----------+-------+
|  1 | SIMPLE      | employees | NULL       | ALL  | NULL          | NULL | NULL    | NULL |  107 |   100.00 | NULL  |
+----+-------------+-----------+------------+------+---------------+------+---------+------+------+----------+-------+
1 row in set, 1 warning (0.01 sec)
```

##### 1.1: possible_keys 字段

**含义或用途**: 显示可能应用到这张表中的一个或多个索引。查询涉及到的字段上若存在索引，则该索引列被列出，但是该索引列不一定被实际使用。

##### 1.2: keys 字段

**含义或用途**: 实际使用的索引。如果为Null，则没有使用索引。若查询中使用了覆盖索引，则该索引仅出现在 `key` 列中。

```SQL
mysql> explain select department_id,location_id from departments;
+----+-------------+-------------+------------+-------+---------------+-----------+---------+------+------+----------+-------------+
| id | select_type | table       | partitions | type  | possible_keys | key       | key_len | ref  | rows | filtered | Extra       |
+----+-------------+-------------+------------+-------+---------------+-----------+---------+------+------+----------+-------------+
|  1 | SIMPLE      | departments | NULL       | index | NULL          | loc_id_fk | 5       | NULL |   27 |   100.00 | Using index |
+----+-------------+-------------+------------+-------+---------------+-----------+---------+------+------+----------+-------------+
```

##### 1.3: key_len 字段

**含义或用途**: 表示索引中实际用到的字节数，可通过该列计算查询中使用的索引长度。在不损失精度的情况下，长度越短越好。`key_len` 显示的值为索引字段的最大可能长度，**并非实际使用长度**，即 `key_len` 是根据表定义计算而得，不是通过表内检索而得。

```sql
-- 一个查询条件
mysql> explain select * from employees where job_id="IT_PROG";
+----+-------------+-----------+------------+------+---------------+-----------+---------+-------+------+----------+-------+
| id | select_type | table     | partitions | type | possible_keys | key       | key_len | ref   | rows | filtered | Extra |
+----+-------------+-----------+------------+------+---------------+-----------+---------+-------+------+----------+-------+
|  1 | SIMPLE      | employees | NULL       | ref  | job_id_fk     | job_id_fk | 23      | const |    5 |   100.00 | NULL  |
+----+-------------+-----------+------------+------+---------------+-----------+---------+-------+------+----------+-------+
1 row in set, 1 warning (0.02 sec)

-- 两个查询条件
mysql> explain select * from employees where job_id="IT_PROG" and employee_id>100;
+----+-------------+-----------+------------+-------+-------------------+-----------+---------+------+------+----------+-----------------------+
| id | select_type | table     | partitions | type  | possible_keys     | key       | key_len | ref  | rows | filtered | Extra                 |
+----+-------------+-----------+------------+-------+-------------------+-----------+---------+------+------+----------+-----------------------+
|  1 | SIMPLE      | employees | NULL       | range | PRIMARY,job_id_fk | job_id_fk | 27      | NULL |    5 |   100.00 | Using index condition |
+----+-------------+-----------+------------+-------+-------------------+-----------+---------+------+------+----------+-----------------------+
1 row in set, 1 warning (0.01 sec)
```

##### 1.4: ref 字段

**含义或用途**: 显示索引的那一列被使用了，如果可能的话，是一个常数，

```sql
-- employees.job_id='AC_MGR'解析为一个常量const
mysql> explain select * from employees,departments where employees.department_id = departments.department_id and employees.job_id='AC_MGR';
+----+-------------+-------------+------------+--------+----------------------+-----------+---------+-------------------------------------+------+----------+-------------+
| id | select_type | table       | partitions | type   | possible_keys        | key       | key_len | ref                                 | rows | filtered | Extra       |
+----+-------------+-------------+------------+--------+----------------------+-----------+---------+-------------------------------------+------+----------+-------------+
|  1 | SIMPLE      | employees   | NULL       | ref    | dept_id_fk,job_id_fk | job_id_fk | 23      | const                               |    1 |   100.00 | Using where |
|  1 | SIMPLE      | departments | NULL       | eq_ref | PRIMARY              | PRIMARY   | 4       | myemployees.employees.department_id |    1 |   100.00 | NULL        |
+----+-------------+-------------+------------+--------+----------------------+-----------+---------+-------------------------------------+------+----------+-------------+
```

##### 1.5: rows 字段

**含义或用途**: 每张表有多少行被优化器查询（值越少越好）。此数字是估算值，可能并不总是准确的

##### 1.6: Extra 字段

- `Using filesort`: 说明 `MySQL` 会对数据使用一个 **外部的索引排序**，而不是按照表内的索引顺序进行读取。`MySQL` 中无法利用索引完成的排序称之为 **文件排序**。出现时表示需要优化了

    ```SQL
    mysql> show index from apps;
    +-------+------------+--------------------------+--------------+-------------+-----------+-------------+----------+--------+------+------------+---------+---------------+
    | Table | Non_unique | Key_name                 | Seq_in_index | Column_name | Collation | Cardinality | Sub_part | Packed | Null | Index_type | Comment | Index_comment |
    +-------+------------+--------------------------+--------------+-------------+-----------+-------------+----------+--------+------+------------+---------+---------------+
    | apps  |          0 | PRIMARY                  |            1 | id          | A         |           5 |     NULL | NULL   |      | BTREE      |         |               |
    | apps  |          1 | idx_url_country_language |            1 | url         | A         |           5 |     NULL | NULL   |      | BTREE      |         |               |
    | apps  |          1 | idx_url_country_language |            2 | country     | A         |           5 |     NULL | NULL   |      | BTREE      |         |               |
    | apps  |          1 | idx_url_country_language |            3 | language    | A         |           5 |     NULL | NULL   |      | BTREE      |         |               |
    +-------+------------+--------------------------+--------------+-------------+-----------+-------------+----------+--------+------+------------+---------+---------------+
    4 rows in set (0.00 sec)

    -- 组合索引是 idx_url_country_language， 下面的SQL 中间隔了组合索引的 country 字段
    mysql> explain select * from apps where url="https://golang.org" order by language;
    +----+-------------+-------+------------+------+--------------------------+--------------------------+---------+-------+------+----------+---------------------------------------+
    | id | select_type | table | partitions | type | possible_keys            | key                      | key_len | ref   | rows | filtered | Extra                                 |
    +----+-------------+-------+------------+------+--------------------------+--------------------------+---------+-------+------+----------+---------------------------------------+
    |  1 | SIMPLE      | apps  | NULL       | ref  | idx_url_country_language | idx_url_country_language | 767     | const |    1 |   100.00 | Using index condition; Using filesort |
    +----+-------------+-------+------------+------+--------------------------+--------------------------+---------+-------+------+----------+---------------------------------------+

    mysql> explain select * from apps where url="https://golang.org" order by country,language;
    +----+-------------+-------+------------+------+--------------------------+--------------------------+---------+-------+------+----------+-----------------------+
    | id | select_type | table | partitions | type | possible_keys            | key                      | key_len | ref   | rows | filtered | Extra                 |
    +----+-------------+-------+------------+------+--------------------------+--------------------------+---------+-------+------+----------+-----------------------+
    |  1 | SIMPLE      | apps  | NULL       | ref  | idx_url_country_language | idx_url_country_language | 767     | const |    1 |   100.00 | Using index condition |
    +----+-------------+-------+------------+------+--------------------------+--------------------------+---------+-------+------+----------+-----------------------+
    ```

- `Using temporary`: 使用临时表保存中间查询结果，MySQL 在对结果排序时使用临时表，常见于排序 `order by` 和 分组查询 `group by`。出现时表示 **急需优化** 了

    ```SQL
    mysql> explain select country from apps group by country;
    +----+-------------+-------+------------+-------+--------------------------+--------------------------+---------+------+------+----------+----------------------------------------------+
    | id | select_type | table | partitions | type  | possible_keys            | key                      | key_len | ref  | rows | filtered | Extra                                        |
    +----+-------------+-------+------------+-------+--------------------------+--------------------------+---------+------+------+----------+----------------------------------------------+
    |  1 | SIMPLE      | apps  | NULL       | index | idx_url_country_language | idx_url_country_language | 895     | NULL |    5 |   100.00 | Using index; Using temporary; Using filesort |
    +----+-------------+-------+------------+-------+--------------------------+--------------------------+---------+------+------+----------+----------------------------------------------+
    ```

- `Using index`: 表示相应的 select 操作使用了覆盖索引（`Covering index`）,避免访问表的数据行，效率还阔以。如果还同时出现了 `using where`，表明索引还被用来执行索引值的查找。如果没有同时出现 `using where`，表明索引只是用来读取数据而非执行查询操作。

    ```SQL
    mysql> explain select employee_id from employees where employee_id order by  employee_id;
    +----+-------------+-----------+------------+-------+---------------+---------+---------+------+------+----------+--------------------------+
    | id | select_type | table     | partitions | type  | possible_keys | key     | key_len | ref  | rows | filtered | Extra                    |
    +----+-------------+-----------+------------+-------+---------------+---------+---------+------+------+----------+--------------------------+
    |  1 | SIMPLE      | employees | NULL       | index | NULL          | PRIMARY | 4       | NULL |  107 |    90.00 | Using where; Using index |
    +----+-------------+-----------+------------+-------+---------------+---------+---------+------+------+----------+--------------------------+
    ```

        覆盖索引：MySQL 可以利用索引返回 `select` 查询的字段，而不必根据索引再次去读取数据文件。也就是说查询的列是索引的一部分，那么查询就只在索引上进行。

- `using join buffer`: 使用了连接缓存

- `impossible where`: where 字句的值总是 false，不能用来获取任何元祖

    ```sql
    mysql> explain select * from employees where employee_id= 1 and employee_id=2;
    +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+------------------+
    | id | select_type | table | partitions | type | possible_keys | key  | key_len | ref  | rows | filtered | Extra            |
    +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+------------------+
    |  1 | SIMPLE      | NULL  | NULL       | NULL | NULL          | NULL | NULL    | NULL | NULL |     NULL | Impossible WHERE |
    +----+-------------+-------+------------+------+---------------+------+---------+------+------+----------+------------------+
    ```
