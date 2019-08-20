---
title: MySQL性能优化之Explain使用浅析
date: 2019-08-19 22:18:54
tags:
    - MySQL
categories:
    - 数据库
    - MySQL
---

**写在前面**: 在软件开发中，遵循着 `first finish then perfect` 的原则。前期我们往往会更加关注程序功能的实现，编写的 `SQL` 语句也多为满足业务所需的增删改查。如果运气还不错，业务做起来了，数据量达到一定量级时，我们发现一个请求的响应时间会越来越难以接受，其中某些不再合理的 `SQL` 往往会成为整个请求响应中性能消耗大户，甚至成为性能瓶颈，那么此时对 `SQL` 语句的优化就显得格外重要了。要优化 `SQL` 就需要定位 `SQL` 存在的问题。`MySQL` 提供了 `explain  + sql` 命令来获取 `SQL` 语句执行计划的信息，包括有关如何连接表以及以何种顺序连接表。以下为 `explain` 使用浅析，记录📝以便今后翻阅。

备注: 文中用到的sql数据来自 [employees.sql](https://github.com/inscode/inscode.github.io/blob/master/blog/source/static/myemployees.sql)

---

#### 1: explain 能获取到那些信息？

- 表的读取顺序

- 表数据读取操作（`select`）的类型

- 那些所有阔以被使用

- 那些索引实际被使用

- 表之间的引用

- 每张表有多少行被优化器查询

#### 2: explain 的使用及其字段说明

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
##### 2.1: id 字段

**含义或用途**: select 查询的序列号，包含一组数字，表示查询中执行 select 字句或是操作表的顺序

**id 的三种情形**

- id 相同，执行顺序自上而下

    ```SQL
    -- 查询员工名，部门名
    mysql> explain SELECT employees.employee_id,employees.last_name, departments.department_name
        -> from employees
        -> inner join departments
        -> on employees.department_id = departments.department_id;
    +----+-------------+-------------+------------+------+---------------+------------+---------+---------------------------------------+------+----------+-------+
    | id | select_type | table       | partitions | type | possible_keys | key        | key_len | ref                                   | rows | filtered | Extra |
    +----+-------------+-------------+------------+------+---------------+------------+---------+---------------------------------------+------+----------+-------+
    |  1 | SIMPLE      | departments | NULL       | ALL  | PRIMARY       | NULL       | NULL    | NULL                                  |   27 |   100.00 | NULL  |
    |  1 | SIMPLE      | employees   | NULL       | ref  | dept_id_fk    | dept_id_fk | 5       | myemployees.departments.department_id |    9 |   100.00 | NULL  |
    +----+-------------+-------------+------------+------+---------------+------------+---------+---------------------------------------+------+----------+-------+
    2 rows in set, 1 warning (0.00 sec)
    ```
    说明: 表读取顺序是先 `departments` 后 `departments`。

- id 不同，如果是子查询，id 的序号会递增，id 值越大其优先级越高，就越先被执行。

    ```SQL
    mysql> explain select * from employees where salary = (select max(salary) from employees);
    +----+-------------+-----------+------------+------+---------------+------+---------+------+------+----------+-------------+
    | id | select_type | table     | partitions | type | possible_keys | key  | key_len | ref  | rows | filtered | Extra       |
    +----+-------------+-----------+------------+------+---------------+------+---------+------+------+----------+-------------+
    |  1 | PRIMARY     | employees | NULL       | ALL  | NULL          | NULL | NULL    | NULL |  107 |    10.00 | Using where |
    |  2 | SUBQUERY    | employees | NULL       | ALL  | NULL          | NULL | NULL    | NULL |  107 |   100.00 | NULL        |
    +----+-------------+-----------+------------+------+---------------+------+---------+------+------+----------+-------------+
    2 rows in set, 1 warning (0.00 sec)
    ```
    说明: 表执行顺序是先执行 id 为 2 的子查询后执行 id 为 1 的主查询。

- id 相同不同，同时存在（❓这是什么鬼👻）

    ```SQL
    mysql> 没找到例子，下面就只表示一下了😂😂😂(只关注 id 就行)
    +----+-------------+-----------+------------+------+---------------+------+---------+------+------+----------+-------------+
    | id | select_type | table     | partitions | type | possible_keys | key  | key_len | ref  | rows | filtered | Extra       |
    +----+-------------+-----------+------------+------+---------------+------+---------+------+------+----------+-------------+
    |  1 | PRIMARY     | employees | NULL       | ALL  | NULL          | NULL | NULL    | NULL |  107 |    10.00 | Using where |
    |  1 | PRIMARY     | employees | NULL       | ALL  | NULL          | NULL | NULL    | NULL |  107 |   100.00 | NULL        |
    |  2 | SUBQUERY    | employees | NULL       | ALL  | NULL          | NULL | NULL    | NULL |  107 |   100.00 | NULL        |
    +----+-------------+-----------+------------+------+---------------+------+---------+------+------+----------+-------------+
    2 rows in set, 1 warning (0.00 sec)
    ```
    说明: id 字段中数字大的先执行，即是 `2先执行`，然后存在两个评价的 1，按 `自上而下的先后顺序执行`。


##### 2.2: `select_type` 字段    

**含义或用途**: 表示 `SELECT` 的类型，主要用于区别 普通查询、联合查询、子查询等复杂查询。常见的取值如下

- **1-SIMPLE:** 简单 select 查询，不包含子查询或者连接查询（union）

- **2-PRIMARY:** 主查询，即是外层的查询（查询中包含了子查询），PRIMARY 是最后加载的查询

- **3-SUBQUERY:** 在 SELECT 或 where 查询包含了 子查询

- **4-DERIVED:** 在 From 列表中包含的子查询被标记为 DEVIRED(衍生)。MySQL会递归执行这些子查询，把结果放在临时表中（会增加系统消耗）

- **5-UNION:** 若第二个 SELECT 出现在 UNION 之后，则标记为 UNION; 若 UNION 包含在 FROM 字句的子查询中，外层的 SELECT 被标记 DEVIRED

- **6-UNION RESULT:** 两种 UNION 结果的合并

    ```SQL
    mysql> explain select * from employees where email like '%a%' union select * from employees where department_id>90;
    +----+--------------+------------+------------+-------+---------------+------------+---------+------+------+----------+-----------------------+
    | id  | select_type  | table      | partitions | type  | possible_keys | key        | key_len | ref  | rows | filtered | Extra                 |
    +-----+--------------+------------+------------+-------+---------------+------------+---------+------+------+----------+-----------------------+
    |  1  | PRIMARY      | employees  | NULL       | ALL   | NULL          | NULL       | NULL    | NULL |  107 |    11.11 | Using where           |
    |  2  | UNION        | employees  | NULL       | range | dept_id_fk    | dept_id_fk | 5       | NULL |    8 |   100.00 | Using index condition |
    | NULL| UNION RESULT | <union1,2> | NULL       | ALL   | NULL          | NULL       | NULL    | NULL | NULL |     NULL | Using temporary       |
    +----+--------------+------------+------------+-------+---------------+------------+---------+------+------+----------+-----------------------+
    3 rows in set, 1 warning (0.00 sec)   
    ```

##### 2.3: `table` 字段

**含义或用途**: 输出结果集的表

##### 2.4: `type` 字段

**含义或用途**: 显示查询使用了何种连接类型。结果值从最好到最坏依次如下，通常来说保证 type 值能达到 Range。

**`SYSTEM > CONST > EQ_REF > REF > RANGE > INDEX > ALL`**

- **1-SYSTEM:**  表只有一行（等于系统表），是const连接类型的特例。可以忽略不计

- **2-CONST:**  该表最多只有一个匹配行，在查询开头读取。因为只有一行，所以优化器的其余部分可以将此行中列的值视为常量。 const表非常快，因为它们只读一次。const将a PRIMARY KEY或 UNIQUE索引的所有部分与常量值进行比较时使用。

---

未完待续
