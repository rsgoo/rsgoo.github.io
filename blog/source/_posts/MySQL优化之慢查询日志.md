---
title: MySQL优化之慢查询日志
date: 2019-08-28 21:59:46
tags:
    - MySQL
categories:
    - 数据库
    - MySQL
---

**写在前面**： `MySQL` 的慢查询日志是 `MySQL` 提供的一种日志记录，用于记录在 `MySQL` 中响应时间超过阈值的语句。即是指 ☞ 如果运行时间超过配置 `long_query_time` 值的 `SQL` 语句，则会被记录到慢查询日志中。

<!--more-->

----

1：查看 `MySQL` 服务器默认配置的 `long_query_time` 值
```SQL
mysql> show variables like 'long_query_time';
+-----------------+-----------+
| Variable_name   | Value     |
+-----------------+-----------+
| long_query_time | 10.000000 |
+-----------------+-----------+
1 row in set (0.00 sec)

```   

2：查看及开启 **慢查询** 日志信息

> MySQL服务器配置文件详见 [MySQL 5.7.21-1配置文件my.cnf](https://inscode.github.io/2019/08/15/MySQL-5-7-21-1%E9%85%8D%E7%BD%AE%E6%96%87%E4%BB%B6my-cnf/)

```SQL
-- 查看
mysql> show variables like '%slow_query%';
+---------------------+---------------------------------+
| Variable_name       | Value                           |
+---------------------+---------------------------------+
| slow_query_log      | OFF                             |
| slow_query_log_file | /var/lib/mysql/inscode-slow.log |
+---------------------+---------------------------------+
2 rows in set (0.00 sec)

-- 修改 slow_query_log 相关配置，然后重启 MySQL
slow_query_log        = 1
slow_query_log_file   = /var/log/mysql/mysql-slow.log
```

3：捕获慢查询日志
```SQL
root@inscode:/var/log/mysql> tail -f mysql-slow.log

# Time: 2019-08-28T15:08:48.983829Z   --SQL执行时间
# User@Host: root[root] @ localhost []  Id:     2   --执行sql的账户及连接信息
-- sql执行时长           --锁🔐时长           --返回的记录数       --查询检查的行数（为什么这个值会大于该表的总记录数呢？🤔🤔🤔）
# Query_time: 0.288241  Lock_time: 0.000526 Rows_sent: 204846  Rows_examined: 504870
SET timestamp=1567004928;   --时间戳 2019-08-28 23:08:48
-- 超过MySQL服务器 long_query_time 时长的sql语句
select * from employees where  birth_date>'1950' and first_name like '%e%' or last_name like '%a%' and hire_date>'1986' order by birth_date desc, last_name asc, hire_date desc;

# Time: 2019-08-28T15:08:56.678322Z
# User@Host: root[root] @ localhost []  Id:     2
# Query_time: 0.287522  Lock_time: 0.000226 Rows_sent: 204846  Rows_examined: 504870
SET timestamp=1567004936;
select * from employees where  birth_date>'1950' and first_name like '%e%' or last_name like '%a%' and hire_date>'1986' order by birth_date desc, last_name asc;
```

4： `mysqldumpslow` 的使用

```
root@inscode:/etc/mysql/mysql.conf.d> mysqldumpslow --help
Usage: mysqldumpslow [ OPTS... ] [ LOGS... ]

Parse and summarize the MySQL slow query log. Options are

  --verbose    verbose
  --debug      debug
  --help       write this text to standard output

  -v           verbose
  -d           debug
  -s ORDER     what to sort by (al, at, ar, c, l, r, t), 'at' is default
                al: average lock time   //评论锁定时间
                ar: average rows sent   //评论返回时间
                at: average query time  //平均执行时间
                 c: count               //访问次数
                 l: lock time           //锁定时间
                 r: rows sent           //返回的记录行数
                 t: query time          //查询时间
  -r           reverse the sort order (largest last instead of first)
  -t NUM       just show the top n queries
  -a           don't abstract all numbers to N and strings to 'S'
  -n NUM       abstract numbers with at least n digits within names
  -g PATTERN   grep: only consider stmts that include this string
  -h HOSTNAME  hostname of db server for *-slow.log filename (can be wildcard),
               default is '*', i.e. match all
  -i NAME      name of server instance (if using mysql.server startup script)
  -l           don't subtract lock time from total time
```

- 获取 返回记录结果集最多的10个sql语句
```sql
mysqldumpslow -s r -t 10 /var/log/mysql/mysql-slow.log
```

- 获取 访问次数最多的10个sql语句
```sql
mysqldumpslow -s c -t 10 /var/log/mysql/mysql-slow.log
```

- 获取 按照时间排序的前10条中含有 “左连接” 的查询语句
```sql
mysqldumpslow -s t -t 10 -g|"left join" /var/log/mysql/mysql-slow.log
-- 使用more 防止 爆屏
mysqldumpslow -s t -t 10 -g|"left join" /var/log/mysql/mysql-slow.log | more
```
