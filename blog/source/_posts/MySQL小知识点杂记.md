---
title: MySQL小知识点杂记
date: 2019-09-04 18:23:54
tags:
    - MySQL
categories:
    - 数据库
    - MySQL
---

**写在前面**: 在使用 MySQL 过程中，总是会用到一些比较不常用知识点，这些知识点通常不会刻意学习，而是当成工具类随用随查。这篇笔记 📒 便是将自己在平时工作学习过程中遇到这些知识点做此记录，便于以后查阅，以提升效率。

<!--more-->

1: 快速🔜一个表存在多少个数据字段
```sql
-- TABLE_SCHEMA 对于数据表所在的数据库名
-- table_name 对于数据表名称
select count(*) from information_schema.COLUMNS
where TABLE_SCHEMA = 'database_name' and table_name = 'table_name';
```
