---
title: Golang爬虫系列零：爬取百度贴吧内容
date: 2019-08-04 12:26:26
tags:
    - 爬虫
    - Go
categories:
    - Golang
---

###### 声明：以下内容仅供技术学习

##### 爬虫实现步骤

1：获取需要爬取URL的地址（以 [`JavaScript吧`](http://tieba.baidu.com/f?kw=javascript&ie=utf-8) 举例）

<!--more-->

    http://tieba.baidu.com/f?kw=javascript&ie=utf-8&pn=0

    http://tieba.baidu.com/f?kw=javascript&ie=utf-8&pn=50

    http://tieba.baidu.com/f?kw=javascript&ie=utf-8&pn=150

2：使用 http.Get(url) 获取到每一个URL的内容

3：将或得到URL的内容写入文件

**注意文件命名**

```golang
package main

import (
    "fmt"
    "net/http"
    "io"
    "strconv"
    "os"
    "time"
)

//贴吧的URL
//http://tieba.baidu.com/f?kw=javascript&ie=utf-8&pn=0
//http://tieba.baidu.com/f?kw=javascript&ie=utf-8&pn=50
func main() {
    start := time.Now()
    //确认起始页和终止页
    var startPage int
    var endPage int
    fmt.Print("请输入起始页:")
    fmt.Scanln(&startPage)
    if startPage <= 1 {
        startPage = 1
    }

    fmt.Print("请输入终止页:")
    fmt.Scanln(&endPage)
    if endPage <= 1 {
        endPage = 1
    }

    working(startPage, endPage)
    cost := time.Since(start)
    fmt.Printf("耗时 = [%s] ", cost)
}

func working(startPage, endPage int) {
    fmt.Printf("正在爬取第%d页到%d页的信息...\n", startPage, endPage)

    pageChan := make(chan int)
    //循环爬取每一页
    for i := startPage; i <= endPage; i++ {
        //开启协程
        go SpiderPage(i, pageChan)
        //SpiderPage(i, pageChan)
    }

    for i := startPage; i <= endPage; i++ {
        //开启协程
        fmt.Printf("第 %d 页 爬取完成\n", <-pageChan)
    }

}

//爬取单个页面的函数
func SpiderPage(i int, pageChan chan int) {
    baseUrl := "http://tieba.baidu.com/f?kw=javascript&ie=utf-8&pn=" + strconv.Itoa((i-1)*50)
    fmt.Println("正在获取第", i, "页的内容")
    result, err := httpGetUrl(baseUrl)
    if err != nil {
        fmt.Println("http get error, Msg", err.Error())
        return
    }
    //将读取到的数据存储存储为文件
    file, err := os.Create("JavaScript_" + strconv.Itoa(i) + ".html")
    if err != err {
        fmt.Println("os Create err：", err.Error())
        return
    }
    file.WriteString(result)
    file.Close() //保存好一个文件就关闭一个文件
    pageChan <- i
}

//爬取内容
func httpGetUrl(url string) (result string, err error) {
    resp, err1 := http.Get(url)
    if err1 != nil {
        err = err1
        return
    }
    //关闭
    defer resp.Body.Close()

    //读取数据
    buffer := make([]byte, 8196)
    for {
        //读取的内容至于buffer容器
        n, err2 := resp.Body.Read(buffer)
        if n == 0 {
            fmt.Println("读取网页完成")
            break
        }
        if err2 != nil && err2 != io.EOF {
            err = err2
            return
        }
        //累次相加每一次读到的Buffer数据，存入result, 一次性返回
        result += string(buffer[:n])
    }
    return
````
