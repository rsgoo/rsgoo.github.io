---
title: MySQL性能优化之Explain浅析实例
date: 2019-08-24 10:59:14
tags:
    - MySQL
categories:
    - 数据库
    - MySQL
---

**写在前面**: 前面了解 `Explain` 的基础知识，接下来我们通过好一些案列来实操一下，通过实践加深对前面两篇笔记的了解。

<!--more-->

---

#### 案例一

```SQL
exlain select d1.name, (select id from t3) d2
from (select id, name from t1 where other_colume = '') d1
union
(select name, id from t2);
```

依据 explain 中 `id`值可知执行先后顺序分别是 **4 > 3 > 2 > 1 > NULL**。说明如下

![](/images/blog/201908/7-explain执行案例一.png)

> 执行顺序1（id为4）: select_type为`UNION`，执行的SQL为`select name, id from t2`

> 执行顺序2（id为3）: select_type为`DERIVED`，由于SQL查询解析时是从`from`开始，后面紧接着的sql查询语句又包含在`from`中，因此select_type为`DERIVED`。执行SQL为`select id, name from t1 where other_colume = ''`

> 执行顺序3（id为2）: select_type为`SUBQUERY`，执行的SQL为`select id from t3`

> 执行顺序4（id为1）: select_type列值为`PRIMARY`表示该查询为外层查询，table列标识为`<derived3>`表示查询的结果来自于一个衍生表，`<derived3>`中的3表示该查询衍生自第`id为3`的查询。

> 执行顺序4（id为1）: select_type列值为`UNION RESULT`表示从临时查询结果集中读取行，table列值为`<union 1,4>` 表示对id为1和4的两个select结果进行`UNION`操作。

[参考来源：bilibili.MySQL高级_explain之热身Case](https://www.bilibili.com/video/av49181542/?p=208)
