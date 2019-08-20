---
title: MySQL性能优化之Explain浅析（中）
date: 2019-08-20 21:53:16
tags:
categories:
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

**含义或用途**: 表示MySQL认为必须检查以执行查询的行数（值越少越好）。此数字是估算值，可能并不总是准确的



```
explain SELECT employees.employee_id,employees.last_name, departments.department_name
from employees
inner join departments
on employees.department_id = departments.department_id;
```
