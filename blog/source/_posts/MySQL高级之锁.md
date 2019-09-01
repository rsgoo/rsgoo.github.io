---
title: MySQL高级之锁
date: 2019-08-31 17:27:53
tags:
    - MySQL
categories:
    - 数据库
    - MySQL
---

**写在前面**: 锁🔐是计算机协调多个进程或线程 **并发** 访问某一资源的机制。

<!--more-->

在数据库中，除了传统的计算资源（CPU、RAM、I/O...）的争用外，数据也是一种多用户共享的资源。如何保证数据在并发条件下访问的一致性，有效性是数据库系统必须解决的一个问题。锁🔐冲突也是影响数据库并发访问性能的一个重要因素。

#### 一: 锁定分类

- 按照操作: 读锁（共享锁） 和 写锁（排他锁）

    >共享锁: 简称S锁，顾名思义，共享锁就是多个事务对于同一数据可以共享一把锁，都能访问到数据，但是只能读不能修改。

    >排他锁: 简称X锁，顾名思义，排他锁就是不能与其他所并存，如一个事务获取了一个数据行的排他锁，其他事务就不能再获取该行的其他锁，包括共享锁和排他锁，但是获取排他锁的事务是可以对数据就行读取和修改。

- 按照粒度: 行锁 | 表锁 | 页锁

> 表锁偏向于 `MyISAM` 存储引擎，开销小，加锁块；无死锁；锁定粒度大，发生锁🔐冲突的概率最高，并发度最低。

> 行锁倾向于 `innoDB` 存储引擎，开销大，加锁慢，会出现死锁，锁定粒度小，发生锁冲突概率最低，并发度最高。

#### 二: 读锁（read lock）

数据准备
```SQL
-- 建表
create table mylock(
    id int PRIMARY key auto_increment,
    name varchar(20)
)Engine = MyISAM default character set utf8mb4;

-- 插入数据
insert into mylock(name)
values
("a"),("b"),("c"),("d"),("e"),("f"),("g");    
```

- 查看表上加过的锁

    ```SQL
    mysql> show open tables;
    ```

- 手动给表加锁和解锁

    ```sql
    -- 加锁
    mysql> lock table tableName1 read(or write), tableName2 read(or write);

    -- 解锁
    mysql> unlock tables;

    ```

- session1 给 mylock 表增加读锁

    ```sql
    -- 加锁
    mysql> lock table mylock read;
    Query OK, 0 rows affected (0.00 sec)

    -- 查看表的锁状态
    mysql> show open tables;
    +--------------------+------------------------------------------------------+--------+-------------+
    | Database           | Table                                                | In_use | Name_locked |
    +--------------------+------------------------------------------------------+--------+-------------+
    | runoob             | mylock                                               |      1 |           0 |
    +--------------------+------------------------------------------------------+--------+-------------+

    -- 查询锁定的表
    mysql> select * from mylock;
    +----+------+
    | id | name |
    +----+------+
    |  1 | a1   |
    |  2 | b    |
    |  3 | c    |
    |  4 | d    |
    |  5 | e    |
    |  6 | f    |
    |  7 | g    |
    +----+------+
    7 rows in set (0.00 sec)

    -- 查询别的表
    mysql> select * from apps;
    -- ERROR 1100 (HY000): Table 'apps' was not locked with LOCK TABLES

    -- 尝试去更新锁定的 mylock 表
    mysql> update mylock set name = 'a11' where id=1;
    -- ERROR 1099 (HY000): Table 'mylock' was locked with a READ lock and can't be updated;
    ```     

- 新开 **session2**

    ```sql
    mysql> select * from mylock;
    +----+------+
    | id | name |
    +----+------+
    |  1 | a1   |
    |  2 | b    |
    |  3 | c    |
    |  4 | d    |
    |  5 | e    |
    |  6 | f    |
    |  7 | g    |
    +----+------+
    7 rows in set (0.00 sec)

    mysql> select * from apps;
    +----+------------+-------------------------+---------+
    | id | app_name   | url                     | country |
    +----+------------+-------------------------+---------+
    |  1 | QQ APP     | http://im.qq.com/       | CN      |
    |  2 | 微博 APP   | http://weibo.com/       | CN      |
    |  3 | 淘宝 APP   | https://www.taobao.com/ | CN      |
    +----+------------+-------------------------+---------+
    3 rows in set (0.01 sec)

    -- 更新表mylock2
    -- 此时 更新操作处于阻塞状态。需要 session1 释放读锁
    mysql> update mylock set name='b2' where id=2;
    ···
    -- 1 min 11.73 sec 表明更新操作等待时间
    mysql> update mylock set name='b2' where id=2;
    -- Query OK, 1 row affected (1 min 11.73 sec)
    -- Rows matched: 1  Changed: 1  Warnings: 0

    -- 查询
    mysql> select * from mylock;
    +----+------+
    | id | name |
    +----+------+
    |  1 | a1   |
    |  2 | b2   |
    |  3 | c    |
    |  4 | d    |
    |  5 | e    |
    |  6 | f    |
    |  7 | g    |
    +----+------+
    7 rows in set (0.01 sec)
    ```

#### 三: 写锁（write lock）

- session1 给表 mylock 加写锁

    ```SQL
    -- 加写锁
    mysql> lock table mylock write;

    -- 读取 mylock
    mysql> select * from mylock;
    +----+------+
    | id | name |
    +----+------+
    |  1 | a1   |
    |  2 | b2   |
    |  3 | c    |
    |  4 | d    |
    |  5 | e    |
    |  6 | f    |
    |  7 | g    |
    +----+------+
    7 rows in set (0.00 sec)

    -- 修改表 mylock
    mysql> update mylock set name='c3' where id=3;
    -- Query OK, 1 row affected (0.00 sec)
    -- Rows matched: 1  Changed: 1  Warnings: 0

    -- 读取别的数据表
    mysql> select * from apps;
    -- ERROR 1100 (HY000): Table 'apps' was not locked with LOCK TABLES
    ```

- session2

    ```SQL
    -- 查询别的数据表
    mysql> select * from apps;
    +----+------------+-------------------------+---------+
    | id | app_name   | url                     | country |
    +----+------------+-------------------------+---------+
    |  1 | QQ APP     | http://im.qq.com/       | CN      |
    |  2 | weibo app  | http://weibo.com/       | CN      |
    |  3 | 淘宝 APP   | https://www.taobao.com/ | CN      |
    +----+------------+-------------------------+---------+
    3 rows in set (0.00 sec)

    -- 查询加了读锁的 mylock 表
    ----处于阻塞状态
    mysql> select * from mylock;
    ...

    -- session1 释放锁
    mysql> select * from mylock;
    +----+------+
    | id | name |
    +----+------+
    |  1 | a1   |
    |  2 | b2   |
    |  3 | c3   |
    |  4 | d    |
    |  5 | e    |
    |  6 | f    |
    |  7 | g    |
    +----+------+
    7 rows in set (3 min 14.11 sec)
    ```

#### 四: MyISAM 对表操作的两中情况

- 1: 对 `MyISAM` 表的 **读** 操作（加读锁），**不会阻塞** 其他进程对同一表的 **读请求**，**但会阻塞** 对同一表的 **写请求**。只有当读锁释放后，才会执行其他进程的操作。

- 2: 对 `MyISAM` 表的 **写** 操作（加写锁），**会阻塞** 其他进程对同一表的 **读和写请求**，、只有当写锁释放后，才会执行其他进程的读写操作。

- 3: `MyISAM` 的读写锁调度是 **写优先**，不适合做 **写为主** 的表引擎。因为加写锁后，其他线程无法在进行任何操作，大量的更新和插入会使得很难获得到锁，从而造成阻塞。

#### 五: 表锁定的分析

可以通过检查 `table_locks_waited` 和 `tables_locks_immediate` 状态变量来分析系统上的表锁定

```SQL
mysql> show status like '%table_locks%';
+-----------------------+-------+
| Variable_name         | Value |
+-----------------------+-------+
| Table_locks_immediate | 127   |
| Table_locks_waited    | 0     |
+-----------------------+-------+
2 rows in set (0.00 sec)
```

> Table_locks_immediate: 产生表级锁定的次数，表示可以立即获取锁的查询次数，每立即获取锁值加 1

> Table_locks_waited: 出现表级锁定争用而发生等待的次数（不能立即获取到锁的次数，没等待一次锁值加一），此值高则说明存在较为严重的表级锁争用的情况。

---

#### 六: 行锁

数据准备

```SQL
-- 建表
create table innodblock(
    a int,
    b varchar(16)
)engine=innoDB default character set utf8mb4;

-- 数据插入
insert into innodblock values
(1,"b2"),
(3,'3'),
(4,'4000'),
(5,'5000'),
(6,'6000'),
(7,'7000'),
(8,'8000'),
(9,'9000'),
(10,'10000');

-- 创建索引
create index idx_innodblock_a on innodblock(a);
create index idx_innodblock_b on innodblock(b);
```

- 索引失效会导致行锁变表锁🔐

```SQL
-- session1 b字段为varchar类型，此时会索引失效
mysql> update innodblock set a=41 where b=4004;
-- Query OK, 0 rows affected (0.00 sec)
-- Rows matched: 1  Changed: 0  Warnings: 0

-- session2
-- 此时由于session1的更新预计导致了索引失效，行锁转换为表锁，如session1不commit, 则session2会提示锁超时
mysql> update innodblock set b="9111" where a=9;
ERROR 1205 (HY000): Lock wait timeout exceeded; try restarting transaction

-- session1 执行commit
mysql> update innodblock set b="9111" where a=9;
Query OK, 1 row affected (3.70 sec)
Rows matched: 1  Changed: 1  Warnings: 0
```

#### 七: 间隙锁🔐

当使用范围条件而不是相等条件检索数据，并请求共享锁或排它锁时，InnoDB会给符合条件的已有数据记录的索引项加锁；对于键值在范围内但是并不存在的记录，叫做 "间隙"

>间隙锁的危害: 因为Query在执行过程中通过范围查找的话，会锁定整个范围内的索引值，即是其中的某些键值并不存在。间隙锁有一个比较致命的弱点，就是当锁定一个范围值后，即使某些不存在的键值记录也会被无辜锁定，从而造成在锁定的时候无法 **插入** 键值范围内的任何数据。

```SQL
-- 在session2 中查询表的记录。
mysql> select * from innodblock;
+------+-------+
| a    | b     |
+------+-------+
|    1 | b2    |
|    3 | 3     |
|    4 | 4004  |
|    5 | 5000  |
|    6 | 6000  |
|    7 | 7000  |
|    8 | 8000  |
|    9 | 9112  |
|   10 | 10000 |
+------+-------+
9 rows in set (0.00 sec)

-- 新增一条 a=2 的记录，同时在seession1 执行更新 a>1 and a<6 的操作
mysql> insert into innodblock values(2,"2000");
Query OK, 1 row affected (0.00 sec)

-- session1 更新操作，此时如果session2的插入操作没有执行commit操作，session1 的更新就会处于阻塞状态，知道session1 commit。
mysql> update innodblock set b='20190901' where a>1 and a<6;
Query OK, 4 rows affected (20.04 sec)
Rows matched: 4  Changed: 4  Warnings: 0
```

#### 八: 如何锁定一行
```SQL
mysql> begin;

mysql> select * from innodblock where a = 8 for update;

mysql> 其他SQL操作

mysq> commit;

```

#### 九: 查询行锁🔐状态

```SQL
mysql> show status like '%row_lock%';
+-------------------------------+--------+
| Variable_name                 | Value  |
+-------------------------------+--------+
| Innodb_row_lock_current_waits | 0      |
| Innodb_row_lock_time          | 134849 |
| Innodb_row_lock_time_avg      | 26969  |
| Innodb_row_lock_time_max      | 51044  |
| Innodb_row_lock_waits         | 5      |
+-------------------------------+--------+
5 rows in set (0.00 sec)
```

- Innodb_row_lock_current_waits: 当前正在等待锁定的数量。

- Innodb_row_lock_time: 从系统启动到现在锁定等待的总时长。

- Innodb_row_lock_time_avg: 等待的平均时长。

- Innodb_row_lock_waits: 系统启动后到现在 **等待的总次数。**


#### 10: 锁的优化建议

- 竟可能让所有数据检索都通过索引来完成，避免行锁升级为表锁

- 合理设计索引，尽量缩小锁的范围（间隙锁）。

- 尽可能减少检索条件，避免间隙锁。

- 控制事务大小，减少锁定的资源量和时间长度。
