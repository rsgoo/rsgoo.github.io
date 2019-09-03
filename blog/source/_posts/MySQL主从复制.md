---
title: MySQL主从复制
date: 2019-09-01 21:55:38
tags:
    - MySQL
categories:
    - 数据库
    - MySQL
---

**写在前面**: MySQL主从复制过程通常分为三步。分别是...第一步，第两步，第三步...

<!--more-->

#### 一: MySQL主从复制步骤

- 1: `master` 将改变 记录到二进制文件（binary log）。这些记录的过程叫二进制日志事件（binary log event）

- 2: `slave` 将 `master` 的 `binary log event` 拷贝到它的中继日志（relay log）

- 3: `slave` 重做中继日志中的事件，将改变应用到 `slave` 自己的数据库中。MySQL 复制是 **异步且是串行化** 的


#### 二: MySQL主从的基本原则

- 1: 每个 `slave` 只有一个 `mater`。

- 2: 每个 slave 只能有一个唯一的服务器id。

- 3: 每个 `master` 可以有多个 `slave`。

> MySQL主从复制的最大问题是 网络延迟

#### 三: 一主一从常见配置

- **Master 配置文件**

    ```sql
    -- 1: 配置主服务器唯一id
    server-id = 1

    -- 2: 启用二进制日志
    log_bin = /var/log/mysql/mysql-bin-master.log

    -- 3: 启用错误日志记录【可选】
    log_err = /var/log/mysql/mysql-bin-master-err.log

    -- 4: 配置根目录【可选】
    basedir = /usr/

    -- 5: 配置临时目录【可选】
    tmpdir  = /tmp/

    -- 6: 配置数据目录【可选】
    datadir = /var/lib/mysql

    -- 7: 主机读写都可以【可选】
    read-only = 1

    -- 8: 设置不需要复制的数据库【可选】

    replicate_ignore_db =  dbName

    -- 9: 设置需要复制的数据库【可选】
    replicate_do_db = dbName
    ```

- **Slave 配置文件**

    ```sql
    -- 1: 配置从服务器唯一id，和 master 的 server-id 不能一致。
    server-id = 2

    -- 2: 启用二进制日志【可选】
    log_bin = /var/log/mysql/mysql-bin-master.log

    ```
- **Master 给 Slave 授权**

    ```sql
    --                                    从机数据库ip，如果为% 则是授权给全部ip
    mysql> grant replication slave on *.* to 's1'@'192.168.153.143' identified by '123456';

    -- 刷新
    mysql> flush privileges;

    -- 查询 Master状态
    mysql> show master status;
    +-------------------------+----------+--------------+------------------+
    | File                    | Position | Binlog_Do_DB | Binlog_Ignore_DB |
    +-------------------------+----------+--------------+------------------+
    | mysql-bin-master.000003 |      752 |              |                  |
    +-------------------------+----------+--------------+------------------+
    1 row in set (0.00 sec)


    -- File: mysql-bin-master.000002【二进制日志文件】
    -- Position: 623【日志复制的位置】
    ```

- **slave从机配置需要复制的主机**

    ```sql
    -- 设置需要复制主机的配置
    -- master_log_file 来自 master 机器中执行 show master status 后得到 File 的值
    -- master_log_pos 来自 master 机器中执行 show master status 后得到 Position 的值

    mysql> change master to
    master_host = '192.168.153.160',
    master_user = 's1',
    master_password ='123456',
    master_log_file = 'mysql-bin-master.000005',  
    master_log_pos = 4631;
    -- 启动从机
    mysql> start slave;  

    -- 查询从机状态
    -- 如果出现 Slave_IO_Running: Connecting ，配置master 的配置文件选项  #bind-address = 127.0.0.1。
    MariaDB [(none)]> show slave status\G;
    *************************** 1. row ***************************
                   Slave_IO_State: Waiting for master to send event
                      Master_Host: 192.168.153.160
                      Master_User: s1
                      Master_Port: 3306
                    Connect_Retry: 60
                  Master_Log_File: mysql-bin-master.000005
              Read_Master_Log_Pos: 625
                   Relay_Log_File: mysqld-relay-bin.000002
                    Relay_Log_Pos: 544
            Relay_Master_Log_File: mysql-bin-master.000005
                 Slave_IO_Running: Yes
                Slave_SQL_Running: Yes
                  Replicate_Do_DB:
              Replicate_Ignore_DB:
               Replicate_Do_Table:
           Replicate_Ignore_Table:
          Replicate_Wild_Do_Table:
      Replicate_Wild_Ignore_Table:
                       Last_Errno: 0
                       Last_Error:
                     Skip_Counter: 0
              Exec_Master_Log_Pos: 625
                  Relay_Log_Space: 843
                  Until_Condition: None
                   Until_Log_File:
                    Until_Log_Pos: 0
               Master_SSL_Allowed: No
               Master_SSL_CA_File:
               Master_SSL_CA_Path:
                  Master_SSL_Cert:
                Master_SSL_Cipher:
                   Master_SSL_Key:
            Seconds_Behind_Master: 0
    Master_SSL_Verify_Server_Cert: No
                    Last_IO_Errno: 0
                    Last_IO_Error:
                   Last_SQL_Errno: 0
                   Last_SQL_Error:
      Replicate_Ignore_Server_Ids:
                 Master_Server_Id: 1
                   Master_SSL_Crl:
               Master_SSL_Crlpath:
                       Using_Gtid: No
                      Gtid_IO_Pos:
          Replicate_Do_Domain_Ids:
      Replicate_Ignore_Domain_Ids:
                    Parallel_Mode: conservative
    1 row in set (0.00 sec)
    ```

#### 验证

- 在master 新建一个库及其表并插入一些数据，slave 上也会存在对应的数据😜。

- 切记: 不要在 slave 上执行 增删改 操作。

写在最后: 之前自己一直觉得 **MySQL主从复制** 实验会比较难搞，产生一些畏难心里，不敢去尝试。这周末按照  `B站` 的教程实现了一下。回想，虽然中间遇到了一些问题，但是坚持坚持，问题终究能解决的。很多时候，并不是问题有多难有多无解，更多的是我们对待未知的心态。Go ahead...

---

**😊参考&&感谢😊**

[MySQL高级-主从复制](https://www.bilibili.com/video/av49181542/?p=241)

[MySQL双主（主主）架构方案](https://www.cnblogs.com/ygqygq2/p/6045279.html)
