
```sql
create database bigdata default character set utf8mb4;

create table dept(
    id int unsigned primary key auto_increment,
    dept_no int unsigned not  null default 0,
    dept_name varchar(20) not null default '',
    location varchar(13) not null default ''
)engine = innodb default character set utf8mb4;

create table employee(
    id int unsigned primary key auto_increment,
    employee_no int unsigned not null default 0 comment '员工编号',    
    employee_name varchar(20) not null default '' comment '员工名称',
    job varchar(9) not null default '' comment '工作',
    manager int not null default 0 comment '上级领导',
    hiredate timestamp not null default CURRENT_TIMESTAMP comment '入职时间',
    salary float(7,2) not null comment '薪水',
    dept_no int unsigned not null default 0 comment '部门编号'
)engine = innodb default character set utf8mb4;


-- 产生随机字符
delimiter $$
create function rand_string(n int) returns varchar(255)
begin
    declare chars_str varchar(100) default 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    declare return_str varchar(255) default '';
    declare i int default 0;
    while i<n do
    set return_str=concat(return_str,substring(chars_str,floor(1+rand()*52),1));
    set i = i+1;
    end while;
    return return_str;
end $$

-- 产生随机部门编号
delimiter $$
create function rand_num() returns int(5)
begin
    declare i int default 0;
    set i=floor(100+rand()*10);
    return i;
end $$

-- 删除函数
drop function rand_num;

-- 创建存储过程-插入员工表
delimiter $$
create procedure insert_emp(in start int(10), in max_num int(10))
begin
    declare i int default 0;
    set autocommit=0;
    repeat
    set i=i+1;
    insert into employee(employee_no,employee_name,job,manager,hiredate,salary,dept_no) values((start+i),rand_string(6),'saleman',0001,curdate(),24000,rand_num());
    until i = max_num
    end repeat;
    commit;
end $$

-- 创建存储过程-插入部门表
delimiter $$
create procedure insert_dept(in start int(10), in max_num int(10))
begin
    declare i int default 0;
    set autocommit=0;
    repeat
    set i=i+1;
    insert into dept(dept_no,dept_name,location) values((start+i),rand_string(10),rand_string(8));
    until i = max_num
    end repeat;
    commit;
end $$

-- 调用存储过程
delimiter ;
-- 部门号从100开始插入10条记录
call insert_dept(100,10);
```
