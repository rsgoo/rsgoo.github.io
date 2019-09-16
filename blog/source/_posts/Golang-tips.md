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

5: 将 `slice` 作为变参函数的参数
> 如果你有一个含有多个值的 slice，想把它们作为参数 使用，你要这样调用 func(slice...)。

```go
package main

import "fmt"

func main() {

	nums := []int{1, 2, 3, 4, 5}
	sum, pro := plusNumbs(nums...)
	fmt.Println(sum)
	fmt.Println(pro)
}

func plusNumbs(nums ...int) (sum, product int) {
	product = 1
	for _, value := range nums {
		sum += value
		product *= value
	}
	return sum, product
}
```

6: time相关
```go
//24个小时后
duration, _ := time.ParseDuration("24h")
fmt.Println(time.Now().Add(duration))
//7天后
fmt.Println(time.Now().Add(duration*7))

tar1 := time.Now().Add(duration)
tar2 := time.Now().Add(duration * 7)
//计算时间的间隔
fmt.Println(tar2.Sub(tar1))
```

7: 使用自定义函数删除指定头尾字符
```go
s := "ohello worldl"
ts := strings.TrimFunc(s, func(r rune) bool {
	return r == 'o' || r == 'l'
})
fmt.Println(ts)
```


8: 获取命令行指定的参数值

> cmd> go run params.go  -language c -uname dongdong


```go
package main

import (
	"flag"
	"fmt"
)

func main() {
	var name string
	var lang = flag.String("language", "编程语言", "")
	flag.StringVar(&name, "uname", "请输入姓名", "")
	flag.Parse()
	fmt.Println("usernem:", name)
	fmt.Println("langauge", *lang)
}
```
