---
title: Golang爬虫系列二：爬取捧腹网段子及其笑话详情
date: 2019-08-12 21:55:04
tags:
    - Go
    - 爬虫
categories:
    - Golang
---

###### 声明：以下内容仅供技术学习

简述：爬取 [捧腹网 ](https://www.pengfu.com) 中的 [段子](https://www.pengfu.com/xiaohua_1.html)页面数据及每一条数据所链接的 [笑话详情页](https://www.pengfu.com/content_1857807_1.html)。

<!--more-->

##### 步骤如下

以第一页（https://www.pengfu.com/xiaohua_1.html） 举例

1: 爬取第一页的内容，获取里面每一个笑话的链接;

2: 爬取步骤1获取到的笑话链接的内容，解析笑话标题和内容

3: 将每一页爬取到的标题和内容写入到文件中

`代买示例如下`

```golang
package main

import (
    "fmt"
    "strconv"
    "net/http"
    "io"
    "regexp"
    "strings"
    "os"
)

func main() {
    var start int
    var end int
    fmt.Print("请输入爬取的起始页: ")
    fmt.Scan(&start)
    fmt.Print("请输入爬取的结束页: ")
    fmt.Scan(&end)

    //爬取业务逻辑
    pengfuWorking(start, end)
}

func pengfuWorking(start, end int) {
    fmt.Println(start, ":", end)

    pageChan := make(chan int)
    for i := start; i <= end; i++ {
        //开启协程
        go pengfuSpider(i, pageChan)
    }

    for i := start; i <= end; i++ {
        //读取管道标识
        fmt.Printf("第 %d 页 爬取完成\n", <-pageChan)
    }
}

//抓取带有10个段子的url
func pengfuSpider(i int, pageChan chan int) {
    //https://www.pengfu.com/xiaohua_1.html
    url := "https://www.pengfu.com/xiaohua_" + strconv.Itoa(i) + ".html"

    //抓取url中的内容
    result, err := spiderUrlContent(url)
    if err != nil {
        fmt.Println("err message：", err)
        return
    }

    //爬取每一页的段子
    urlPat := regexp.MustCompile(`<h1 class="dp-b"><a href="(?s:(.*?))"`)
    urls := urlPat.FindAllStringSubmatch(result, -1)

    //创建存储title,content 的切片
    titleSlice := make([]string, 0)
    contentSlice := make([]string, 0)
    for _, value := range urls {
        url := value[1]
        title, content, err := spiderDetailContent(url)
        if err != nil {
            fmt.Println("spiderDetailContent error:", err)
            continue
        }
        titleSlice = append(titleSlice, title)
        contentSlice = append(contentSlice, content)
    }
    saveJokeToFile(i, titleSlice, contentSlice)
    pageChan <- i

}

func spiderUrlContent(url string) (result string, err error) {
    resp, err1 := http.Get(url)
    if err1 != nil {
        err = err1
        return
    }

    defer resp.Body.Close()

    buffer := make([]byte, 8196)
    for {
        n, err2 := resp.Body.Read(buffer)
        if n == 0 {
            fmt.Println("读取网页完成:", url)
            break
        }

        if err2 != nil && err2 != io.EOF {
            err = err2
            return
        }
        result += string(buffer[:n])
    }
    return
}

//爬取一个笑话详情页的标题和内容
func spiderDetailContent(url string) (title, content string, err error) {
    result, err1 := spiderUrlContent(url)
    if err1 != nil {
        err = err1
        return
    }

    //匹配标题
    titlePat := regexp.MustCompile(`<h1>(?s:(.*?))\t*</h1>`)
    titles := titlePat.FindAllStringSubmatch(result, 1)
    title = titles[0][1]
    title = strings.Trim(title, "")
    title = strings.Replace(title, "&nbsp; ", "", -1)

    //匹配内容
    contentPat := regexp.MustCompile(`<div class="content-txt pt10">\n*\t*(?s:(.*?))<a`)
    contents := contentPat.FindAllStringSubmatch(result, 1)
    content = contents[0][1]
    content = strings.Trim(content, "\t")
    content = strings.Replace(content, "\t", "", -1)
    return
}

func saveJokeToFile(idx int, titleSlice, contentSlice []string) {
    //将读取到的数据存储存储为文件
    dir, _ := os.Getwd()
    filePath := dir + "/pengfu_" + strconv.Itoa(idx) + ".txt"

    file, err := os.Create(filePath)
    if err != err {
        fmt.Println("os Create err：", err.Error())
        return
    }

    defer file.Close() //保存好一个文件就关闭一个文件

    for i := 0; i < len(titleSlice); i++ {
        file.WriteString(titleSlice[i] + ":\t\t" + contentSlice[i] + "\n")
    }
}

```

`爬取到的文本文件示例`
```
会放坏:		女：“我购物车里的那些水果牛奶饮料零食你赶紧给我买！” 男：“着什么急呀？” 女：“天越来越热了，会放坏的。”
肉多舒服:		楼主大四女生，这几天和同一宿舍的女生一起去找工作，在火车站，碰到一个大姐，带着一个四五岁的小男孩侯车，小男孩总是粘着我，要坐我腿上，同宿舍的女生很是羡慕我有人缘，我得意的问小男孩：“你为什么老是喜欢坐姐姐腿上？” 小男孩用稚嫩的口气回答：“姐姐腿上肉多，坐着舒服” 我：“尼玛，这是谁家小哔崽子，快点领走！”
卸妆:		一女人问大师：大师，在这么复杂险恶的世界，我一个弱女子如何保护自己？大师说：你把妆卸了！
刺十字绣:		家长对孩子的教育真的非常重要。有些男孩，小时候调皮的很，偷了邻居家一根针，家长也不管，一根针嘛，没事。结果孩子长大了，不好好学习每天呆在家里刺十字绣。
约会:		网上聊了一妹子，今天见面。问老妈要了二百块钱，老妈问我干嘛用，我说约会，她高高兴兴的就给了。刚出家门，老爸就把我拽到一边说“小子，二百块钱给我一百，半个月不知道烟的滋味了。”我说“爸，这是我和女朋友的约会钱，不能给你。”老爸说了。“你约什么会，那是我刚建的小号，为了抽颗烟我和你聊了半个月了，快拿来。‘’
改配方了:		70年代时，有一长辈练铁沙掌，功夫成了之后，可以掌断五砖，凌空碎砖，威风的不得了。 到80年代，只能掌断三砖。 到90年代只能一砖一砖的断了。 他说，一直以为功力退步了，后来才知道烧砖的配方改了。。。
报恩:		为什么古装剧里总是有女人会对恩人说：小女子无以为报，唯有以身相许，古代真的存在这种现象吗？ 扯淡，那是因为她喜欢他，要是不喜欢，她就会说：小女子无以为报，唯有来生再报了。
问路:		春节回老家时，看到一个老外在向一个农民伯伯问路。只见那个老外一边说着生硬的中文，一边用手笔划，但那个农民伯伯却还是不明白。最后，那个农民伯伯说了一句让我至今难忘的话：“Can you speak English?”
老王:		闺蜜是个韩剧迷，她老公是个球迷。某天晚上有球赛，他俩因为抢电视机遥控器打了起来。她一气之下找我哭诉，我问她：“电视遥控器最后归谁了?”她边哭边说：“老王。”我疑惑道：“这个老王是谁呀?我怎么没听说过。”她哭的声音更大了：“一个修电视机的……”
丑的先说:		两个女人在大街上吵架，那个骂的吐沫横飞啊。一大妈看不下去了，可谁也不听，都说自己对，对方错。大妈淡淡地来了一句：“那么这样，丑的先说!”瞬间，整个世界都安静了。。。
```
