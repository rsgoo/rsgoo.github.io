---
title: Golang tips
date: 2019-09-07 00:59:07
tags:
    - Golang
categories:
    - Golang
---

**写在前面**: 常用但易忘的 Golang 知识点整理。

<!--more-->

1: 小数保留指定位数

> 由于 Go 的 Math.rand() 函数四舍五入只能保留到整数位，当需要保留指定小数位时需要使用 sprintf() 帮忙

```go
var number1 float64 = 27.234567
s := fmt.Sprintf("%.5f", number1)

//这时 27.23457 的类型是 string
fmt.Printf("这时 %v 的类型是 %T\n", s, s)

val, _ := strconv.ParseFloat(s, 64)
//此时 27.23457 的类型是 float64
fmt.Printf("此时 %v 的类型是 %T\n", val, val)    
```
