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

2: 不定长参数使用

> 不定长类型只能放在参数列表最后

```go
func main() {
	LoveManySkills(1.11, "Golang", "Rust", "Redis", "MySQL", "C")
}

// 不定长类型只能放在参数列表最后
func LoveManySkills(version float32, skills ...interface{}) {
	fmt.Println("current version is: ", version)
	for key, skill := range skills {
		fmt.Printf("你喜欢的第 %d 门技能是 %v\n", key+1, skill)
	}
}

```

3: 获取随机数字
```go
myRand := rand.New(rand.NewSource(time.Now().UnixNano()))
//返回【0，N)之间的数字
answer := myRand.Intn(N)
```

4: 匿名函数使用
```go
//方式一
func(a, b int) {
    fmt.Println(int64(a + b))
}(110100, 11019)

//方式二
var product = func(x, y float64) float64 {
    return x * y
}
fmt.Println(product(1101, 2))

//方式三
whatAmI := func(i interface{}) {
	switch t := i.(type) {
	case bool:
		fmt.Println("I'm a bool")
	case int:
		fmt.Println("I'm an int")
	default:
		fmt.Printf("Don't know type %T\n", t)
	}
}
whatAmI(true)
whatAmI(1)
whatAmI("hey")
```
