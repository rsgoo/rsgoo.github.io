---
title: MySQL之使用show-profiling进行sql分析
date: 2019-08-31 11:21:56
tags:
    - MySQL
categories:
    - 数据库
    - MySQL
---

**写在前面**: `show profiling` 是MySQL提供的可以用来分析 **当前会话中sql语句** 执行消耗资源情况的工具。

<!--more-->

1: 查看 MySQL 服务器 profiling 配置信息并开启

```SQL
-- 查看
mysql> show variables like '%profiling%';
+------------------------+-------+
| Variable_name          | Value |
+------------------------+-------+
| have_profiling         | YES   |
| profiling              | OFF   |
| profiling_history_size | 15    |
+------------------------+-------+
3 rows in set (0.00 sec)

-- 开启
mysql> set profiling = on;
```

2: 使用 `show profiles` 查看最近执行SQL语句的耗时情况

```sql

mysql> show profiles;
+----------+------------+------------------------------------------------------------+
| Query_ID | Duration   | Query                                                      |
+----------+------------+------------------------------------------------------------+
|       22 | 1.33266500 | select * from employee  where employee_name like 'axla%'   |
|       23 | 1.34147675 | select * from employee  where employee_name like 'axlx%'   |
|       24 | 1.33172000 | select * from employee  where employee_name like 'axl;%'   |
|       25 | 1.33968575 | select * from employee  where employee_name like 'axll%'   |
|       26 | 1.30903325 | select * from employee  where employee_name like 'axllz%'  |
|       27 | 1.33119175 | select * from employee  where employee_name like 'axllz0%' |
|       28 | 1.37719575 | select * from employee  where employee_name like 'axllzo%' |
|       29 | 0.00008650 | show profiles for query 22                                 |
|       30 | 0.00017425 | show profiles cpu.block for query 22                       |
|       31 | 0.00006300 | show profiles cpu,block io for query 22                    |
|       32 | 0.00007200 | show profiles cpu for query 22                             |
|       33 | 0.00006825 | show profiling cpu for query 22                            |
|       34 | 0.00006525 | show profiles cpu for query 22                             |
|       35 | 0.00007450 | show profiling cpu for query 22                            |
|       36 | 6.57003650 | select * from employee                                     |
+----------+------------+------------------------------------------------------------+
15 rows in set, 1 warning (0.05 sec)

-- 查看某一条SQL语句具体执行耗时情况
mysql> show profile cpu,block io for query 36;
+----------------------+----------+----------+------------+--------------+---------------+
| Status               | Duration | CPU_user | CPU_system | Block_ops_in | Block_ops_out |
+----------------------+----------+----------+------------+--------------+---------------+
| starting             | 0.000090 | 0.000083 |   0.000007 |            0 |             0 |
| checking permissions | 0.000013 | 0.000010 |   0.000001 |            0 |             0 |
| Opening tables       | 0.000019 | 0.000018 |   0.000002 |            0 |             0 |
| init                 | 0.000023 | 0.000020 |   0.000002 |            0 |             0 |
| System lock          | 0.000010 | 0.000010 |   0.000001 |            0 |             0 |
| optimizing           | 0.000005 | 0.000004 |   0.000000 |            0 |             0 |
| statistics           | 0.000016 | 0.000015 |   0.000001 |            0 |             0 |
| preparing            | 0.000046 | 0.000042 |   0.000004 |            0 |             0 |
| executing            | 0.000005 | 0.000004 |   0.000001 |            0 |             0 |
| Sending data         | 6.471989 | 5.454445 |   0.405826 |       132832 |             0 |
| end                  | 0.020849 | 0.000872 |   0.000079 |          984 |             0 |
| query end            | 0.013094 | 0.000000 |   0.000795 |         1136 |             0 |
| closing tables       | 0.001540 | 0.000000 |   0.000364 |          632 |             0 |
| freeing items        | 0.002086 | 0.000000 |   0.000471 |         1008 |             0 |
| logging slow query   | 0.056165 | 0.000000 |   0.005052 |         1568 |             8 |
| cleaning up          | 0.004088 | 0.000000 |   0.000771 |          992 |             0 |
+----------------------+----------+----------+------------+--------------+---------------+
16 rows in set, 1 warning (0.07 sec)

-- 如果上面的status 中出现以下四种情况表明需要优化❌，
- 1：converting HEAP to MyISAM（查询结果太大，内存不够用，需要往磁盘上搬运）

- 2：create tmp table (创建临时表【先拷贝数据到临时表，用完临时表数据后删除】)

- 3：Copying to tmp table to dis (把内存中临时表复制到磁盘，危险❌)

- 4：locked (锁定了🔐)

```

3: show profile 后可跟参数类型

| 类型            |   说明         |
| :------------- | :------------- |
| all            | 显示所有开销信息  |
| block io       | 显示块IO开销相关  |
| context switch | 上下午切换相关开销 |
| cpu            | 显示cpu开销信息   |
| ipc            | 显示发送和接收相关开销信息|
| memory         | 显示内存相关开销信息  |
| page fault     | 显示页面错误❎相关开销信息  |
| source         | 显示source相关开销信息  |
| swaps          | 显示交换次数相关开销信息  |


4: 全局查询日志,

❌注意❌：**（不可在生产环境使用）**

```SQL
mysql> show variables like '%general%';
+------------------+----------------------------+
| Variable_name    | Value                      |
+------------------+----------------------------+
| general_log      | OFF                        |
| general_log_file | /var/lib/mysql/inscode.log |
+------------------+----------------------------+
2 rows in set (0.07 sec)
```
- 在 my.cnf 中开启全局日志

```SQL
general_log = 1
general_log_file = /var/log/mysql/mysql-general-log.log
```

---

笔记记录来源 [MySQL高级_2用Show Profile进行sql分析](https://www.bilibili.com/video/av49181542/?p=229)
