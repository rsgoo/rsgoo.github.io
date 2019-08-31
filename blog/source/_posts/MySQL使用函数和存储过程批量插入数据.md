---
title: MySQL使用函数和存储过程批量插入数据
date: 2019-08-30 17:38:24
tags:
    - MySQL
categories:
    - 数据库
    - MySQL
---

**写在前面**: 在 `MySQL` 优化学习中，我们通常需要使用到十万量级甚至是百万量级的数据表来去对比索引优化前后的SQL性能表现。那么怎么去获取到拥有这个数量级的数据表呢？答案之一是使用存储过程往表中批量导入数据。下面来实例演示一个批量导入数据表的案例，记录已被日后查阅。

<!--more-->

1：创建数据库及数据表
```sql
-- 创建数据库
create database data_demo default character set utf8mb4;

--创建部门表
create table department(
    id int unsigned primary key auto_increment comment '主键',
    dept_no int unsigned not  null default 0 comment '部门编号',   
    dept_name varchar(20) not null default '' comment '部门名称',
    location varchar(13) not null default '' comment '部门位置'
)engine = innodb default character set utf8mb4 comment '部门信息表';

-- 创建员工信息表
create table employee(
    id int unsigned primary key auto_increment comment '主键',
    employee_no int unsigned not null default 0 comment '员工编号',    
    employee_name varchar(20) not null default '' comment '员工名称',
    job varchar(9) not null default '' comment '工作',
    manager int not null default 0 comment '上级领导',
    hiredate timestamp not null default CURRENT_TIMESTAMP comment '入职时间',
    salary float(7,2) not null comment '薪水',
    dept_no int unsigned not null default 0 comment '部门编号'
)engine = innodb default character set utf8mb4 comment '员工信息表';
```

2: 创建 **随机字符生成** 函数
```SQL
delimiter $$
create function rand_string(n int) returns varchar(255)
begin
    declare chars_str varchar(100) default 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    declare return_str varchar(255) default '';
    declare i int default 0;
    while i < n do
        set return_str = concat(return_str, substring(chars_str, floor(1 + rand() * 52), 1));
        set i = i + 1;
    end while;
    return return_str;
end $$
```

3: 创建 **随机数值生成** 函数
```SQL
delimiter $$
create function rand_num() returns int(5)
begin
    declare i int default 0;
    set i = floor(100 + rand() * 10);
    return i;
end $$
```
> 删除函数命令：drop function funcName;

4: 创建 **员工数据插入** 存储过程
```SQL
delimiter $$
create procedure insert_employee(in start int(10), in max_num int(10))
begin
    declare i int default 0;
    set autocommit=0;
    repeat
        set i = i + 1;
        insert into employee(employee_no, employee_name, job, manager, hiredate, salary, dept_no) values((start+i), rand_string(6), 'saleman', 0001 ,curdate(), 24000,rand_num());
        until i = max_num
    end repeat;
    commit;
end $$
```

4: 创建 **部门数据插入** 存储过程
```SQL
delimiter $$
create procedure insert_department(in start int(10), in max_num int(10))
begin
    declare i int default 0;
    set autocommit=0;
    repeat
        set i = i + 1;
        insert into department(dept_no,dept_name,location) values((start+i),rand_string(10),rand_string(8));
        until i = max_num
    end repeat;
    commit;
end $$
```
> 删除存储过程命令：drop procedure procedureName;

5: 调用存储过程插入数据
```SQL
delimiter ;
-- 部门号从100开始插入10条记录
call insert_department(100,10);

call insert_employee(1,5000000);

```

6: 查看数据

```SQL
-- 部门表
mysql> select * from department;
+----+---------+------------+----------+
| id | dept_no | dept_name  | location |
+----+---------+------------+----------+
|  1 |     101 | VPoajUVSCi | kBdJmjhi |
|  2 |     102 | uylHDUPnUM | aOWpIyqk |
|  3 |     103 | cIjWdCCdKo | pHsPXqOY |
|  4 |     104 | yxRPzeVrVE | lqVFqNSZ |
|  5 |     105 | sPYywNAoUD | ihjzUcAx |
|  6 |     106 | KiKaZTxGOa | IrJwdcen |
|  7 |     107 | anqQeYEasK | vZLGZewV |
|  8 |     108 | pMQSSIMKop | JzuznRuy |
|  9 |     109 | lGBKWEgSXh | TYjaAyUZ |
| 10 |     110 | pDTLXGoyDw | yZFbrEWW |
+----+---------+------------+----------+
10 rows in set (0.02 sec)

--员工表
mysql> select count(*) from  employee;
+----------+
| count(*) |
+----------+
|  5000000 |
+----------+
1 row in set (7.78 sec)

```

7: 有了这些数据后就阔以更快乐的学习SQL相关知识点了😜😜😜
