---
title: Golang爬虫系列三：下载去哪儿游记图片到本地
date: 2019-08-14 19:21:39
tags:
    - Golang
    - 爬虫
categories:
    - Golang
---

###### 声明：以下内容仅供技术学习

简述： 获取[去哪儿网](http://www.qunar.com/) 中的的游记（eg: [美在“十二背后"](https://travel.qunar.com/youji/6961284) ）页面中的图片地址并将图片保存到本地

<!--more-->

##### 步骤如下

1: 使用正则获取图片的链接并存储到一个 `slice` 中

2: 遍历 `slice` 中的 图片地址，读取数据保存到本地

**代码如下**
```golang
package main

import (
    "fmt"
    "strconv"
    "net/http"
    "io"
    "regexp"
    "os"
)

func main() {
    var articleId int
    fmt.Print("请输入游记id: ")
    fmt.Scan(&articleId)

    spiderArticleImageUrls(articleId)
}

func spiderArticleImageUrls(articleId int) {
    //https://travel.qunar.com/youji/5908435
    articleUrl := "https://travel.qunar.com/youji/" + strconv.Itoa(articleId)
    resp, err1 := http.Get(articleUrl)

    //fmt.Println(resp.StatusCode)
    //os.Exit(1)
    if err1 != nil {
        fmt.Println("http get err:", nil)
        return
    }

    if resp.StatusCode != 200 {
        fmt.Println("404 not found")
        return
    }

    defer resp.Body.Close()

    var pageContent string
    buffer := make([]byte, 8192)
    for {
        n, err2 := resp.Body.Read(buffer)
        if n == 0 {
            fmt.Println("读取网页完成")
            break
        }

        if err2 != nil && err2 != io.EOF {
            fmt.Println("err2:", err2)
        }

        pageContent += string(buffer[:n])
    }

    osDir, _ := os.Getwd()
    dir := osDir + "/qunar/"
    os.Mkdir(dir, os.ModePerm)
    fileName := dir + strconv.Itoa(articleId) + ".txt"

    file, err3 := os.Create(fileName)
    if err3 != nil {
        fmt.Println("文件创建失败: ", err3)
    }

    /* 写入内容到文件*/
    image_pat := regexp.MustCompile(`data-retina="(.*?)" class="box_img js_box_img js_lazyimg ajaxloading"`)
    image_urls := image_pat.FindAllStringSubmatch(pageContent, -1)
    //var saveImages []string
    imgDir := osDir + "/qunar/" + strconv.Itoa(articleId) + "/"
    os.Mkdir(imgDir, os.ModePerm)
    fileSaveChan := make(chan int)
    for key, image := range image_urls {
        key++
        go saveImageUrlToFile(key, image[1], imgDir, fileSaveChan)
        file.WriteString(string(key) + "--" + image[1] + "\n")
        //saveImages = append(saveImages, image[1])
    }

    imgCount := (len(image_urls))
    for i := 0; i < imgCount; i++ {
        fmt.Printf("图片%d.jpg 保存成功\n", <-fileSaveChan)
    }

}

//保存文件件
func saveImageUrlToFile(i int, imgUrl, dir string, fileSaveChan chan int) {
    fileName := dir + strconv.Itoa(i) + ".jpg"
    file, _ := os.Create(fileName)
    resp, err1 := http.Get(imgUrl)
    if err1 != nil {
        fmt.Println("saveImageUrlToFile err:", err1)
        return
    }

    defer resp.Body.Close()

    buffer := make([]byte, 8192)
    for {
        n, err2 := resp.Body.Read(buffer)
        if n == 0 {
            //fmt.Println("saveImageUrlToFile 读取网页完成:", i)
            break
        }

        if err2 != nil && err2 != io.EOF {
            fmt.Println("err2:", err2)
        }

        //数据写入文件
        file.Write(buffer[:n])
    }

    fileSaveChan <- i

}

```
