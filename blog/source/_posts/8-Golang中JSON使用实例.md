---
title: Golang中JSON使用实例
date: 2019-09-22 12:22:28
tags:
    - Golang
categories:
    - Golang
---

**写在前面**：Golang中对于JSON操作的几个实例。

<!--more-->

### 一：Golang数据类型转为Json

#### 1-0: 结构体 struct 转Json
```go
package main

import (
	"encoding/json"
	"fmt"
)

type Person struct {
	Name   string
	Age    int
	Rmb    float64
	Gender string
	Hobby  []string
}

func main() {
	var Tom = Person{
		Name:   "Tom",
		Age:    2,
		Rmb:    268000,
		Gender: "Male",
		Hobby:  []string{"eat fish", "play with tom"},
	}

	bytes, err := json.Marshal(Tom)
	if err != nil {
		fmt.Println("err is ", nil)
	}
	fmt.Println(string(bytes))
}

```

#### 1-1: Json 转 结构体 struct
```go
package main

import (
	"encoding/json"
	"fmt"
)

type Cat struct {
	Name   string
	Age    int
	Rmb    float64
	Gender string
	Hobby  []string
}

func main() {

	var Tom = Cat{}
	var jsonStr = `{"Name":"Tom","Age":2,"Rmb":268000,"Gender":"Male","Hobby":["eat fish","play with tom"]}`

	jsonStrBytes := []byte(jsonStr)

	err := json.Unmarshal(jsonStrBytes, &Tom)
	if err != nil {
		fmt.Println("err is ", err)
	}
	fmt.Println(Tom)
}

```
----

#### 2-0: Map 转 json
```go
package main

import (
	"encoding/json"
	"fmt"
)

func main() {
	var Person map[string]interface{}
	Person = make(map[string]interface{})
	Person["name"] = "Jerry"
	Person["age"] = 2
	Person["rmb"] = 268000
	Person["gender"] = "Male"
	Person["hobby"] = []string{"watch tv", "play mobile", "chat with tom"}

	bytes, err := json.Marshal(Person)
	if err != nil {
		fmt.Println("err is ", nil)
	}
	fmt.Println(string(bytes))

}

```

#### 2-1: Json 转 Map
```go
package main

import (
	"encoding/json"
	"fmt"
)

func main() {

	dataMap := make(map[string]interface{})

	var jsonStr = `{"age":2,"gender":"Male","hobby":["watch tv","play mobile","chat with tom"],"name":"Jerry","rmb":268000}`
	jsonStrBytes := []byte(jsonStr)
	//注意，这里是使用地址
	err := json.Unmarshal(jsonStrBytes, &dataMap)
	if err != nil {
		fmt.Println("err is ", err)
	}
	fmt.Println(dataMap)
}

```

---

##### 3-1: 切片 slice 转 json
```go
package main

import (
	"encoding/json"
	"fmt"
)

func main() {
	var slices = make([]map[string]interface{}, 0)

	//var Jerry map[string]interface{}
	Jerry := make(map[string]interface{})
	Jerry["name"] = "Jerry"
	Jerry["age"] = 2
	Jerry["rmb"] = 268000
	Jerry["gender"] = "Male"
	Jerry["hobby"] = []string{"watch tv", "play mobile", "chat with tom"}

	slices = append(slices, Jerry)

	Tom := make(map[string]interface{})
	Tom["name"] = "Tom"
	Tom["age"] = 3
	Tom["rmb"] = 288000
	Tom["gender"] = "Male"
	Tom["hobby"] = []string{"watch tv", "play mobile", "chat with jerry"}

	slices = append(slices, Tom)

	bytes, err := json.Marshal(slices)
	if err != nil {
		fmt.Println("err is ", err)
	}

	fmt.Println(string(bytes))
}

```

##### 3-2: json 转  切片 slice

```go
package main

import (
	"encoding/json"
	"fmt"
)

func main() {
	var jsonStr = `[{"age":2,"gender":"Male","hobby":["watch tv","play mobile","chat with tom"],"name":"Jerry","rmb":268000},{"age":3,"gender":"Male","hobby":["watch tv","play mobile","chat with jerry"],"name":"Tom","rmb":288000}]`

	var jsonStrBytes = []byte(jsonStr)

	var slices = make([]map[string]interface{}, 0)

	err := json.Unmarshal(jsonStrBytes, &slices)
	if err != nil {
		fmt.Println("ers is ", err)
	}
	fmt.Println(slices)
}

```

---

#### 4-0: 结构体切片 与 json 互转

```go
package main

import (
	"encoding/json"
	"fmt"
)

type Player struct {
	Name    string
	Age     int
	Team    string
	Country string
}

func main() {

	var White = Player{
		Name:    "White",
		Age:     23,
		Team:    "Spurs",
		Country: "USA",
	}

	var Mills = Player{
		Name:    "Mills",
		Age:     30,
		Team:    "Spurs",
		Country: "Australia",
	}

	Players := make([]Player, 0)

	Players = append(Players, White)
	Players = append(Players, Mills)

	//结构体切片转json字符串
	playersBytes, err := json.Marshal(Players)
	if err != nil {
		fmt.Println("err is ", err)
	}
	playJsonStr := string(playersBytes)
	fmt.Println(playJsonStr)

	//json字符串转结构体切片
	decodePlayers := make([]Player, 0)
	playStrBytes := []byte(playJsonStr)

	err1 := json.Unmarshal(playStrBytes, &decodePlayers)
	if err1 != nil {
		fmt.Println("err1 is ", err1)
	}

	fmt.Println(decodePlayers)

}

```

---

#### 使用JSON 编码器编码Golang数据为JSON文件

1: map 数据结构写入到json文件

```go
package main

import (
	"encoding/json"
	"fmt"
	"os"
)

func main() {
	Person := make(map[string]interface{})
	Person["name"] = "Jerry"
	Person["age"] = 2
	Person["rmb"] = 268000
	Person["gender"] = "Male"
	Person["hobby"] = []string{"watch tv", "play mobile", "chat with tom"}

	jsonFile, err := os.OpenFile("jerry.json", os.O_WRONLY|os.O_CREATE|os.O_APPEND, 0666)
	if err != nil {
		fmt.Println("file err is ", err)
	}

	//创建json编码器
	encoder := json.NewEncoder(jsonFile)

	//数据编码
	err1 := encoder.Encode(Person)
	if err1 != nil {
		fmt.Println("err is ", err1)
		return
	}
}

```

2: 切片结构体数据结构写入到json文件

```go
package main

import (
	"os"
	"fmt"
	"encoding/json"
)

func main() {

	type Animal struct {
		Name   string
		Age    int
		Rmb    float64
		Gender string
		Hobby  []string
	}

	var slices = make([]Animal, 0)

	Tom := Animal{
		Name:   "TOM",
		Age:    2,
		Rmb:    26000,
		Gender: "male",
		Hobby:  []string{"eat fish", "play with jerry"},
	}

	Jerry := Animal{
		Name:   "TOM",
		Age:    2,
		Rmb:    26000,
		Gender: "male",
		Hobby:  []string{"eat fish", "play with tom"},
	}

	slices = append(slices, Tom)
	slices = append(slices, Jerry)

	jsonFile, err := os.OpenFile("animals.json", os.O_WRONLY|os.O_CREATE|os.O_TRUNC, 0666)
	defer jsonFile.Close()

	if err != nil {
		fmt.Println("err is ", err)
		return
	}

	jsonEncoder := json.NewEncoder(jsonFile)
	err1 := jsonEncoder.Encode(slices)
	if err1 != nil {
		fmt.Println("err1 is ", err1)
		return

	}
}

```

3: 读取JSON文件 到 map
```go
package main

import (
	"os"
	"fmt"
	"encoding/json"
)

func main() {

	Tom := make(map[string]interface{})

	srcJsonFile, err := os.OpenFile("jerry.json", os.O_RDONLY, 0666)
	defer srcJsonFile.Close()
	if err != nil {
		fmt.Println("err is ", nil)
		return
	}

	jsonDecoder := json.NewDecoder(srcJsonFile)
	err1 := jsonDecoder.Decode(&Tom)
	if err1 != nil {
		fmt.Println("err1 is ", err1)
		return
	}

	fmt.Println(Tom)
}

```

4: 读取JSON文件 到 结构体切片
```go
package main

import (
	"os"
	"fmt"
	"encoding/json"
)

func main() {

	type Animal struct {
		Name   string
		Age    int
		Rmb    float64
		Gender string
		Hobby  []string
	}

	var slices = make([]Animal, 0)

	srcJsonFile, err := os.OpenFile("animals.json", os.O_RDONLY, 0666)
	defer srcJsonFile.Close()

	if err != nil {
		fmt.Println("err is ", nil)
		return
	}

	jsonDecoder := json.NewDecoder(srcJsonFile)
	err1 := jsonDecoder.Decode(&slices)
	if err1 != nil {
		fmt.Println("err1 is ", err1)
		return
	}

	for _, value := range slices {
		fmt.Println(value)
	}
}

```

5：json 解码 Unmarshal

```go
package main

import (
	"encoding/json"
	"fmt"
)

func main() {
	type Animal struct {
		Name   string
		Age    int
		Rmb    float64
		Gender string
		Hobby  []string
	}
	var jsonStr = `{"age":2,"gender":"Male","hobby":["watch tv","play mobile","chat with tom"],"name":"Jerry","rmb":268000}`

	jsonBytes := []byte(jsonStr)

	var animal = &Animal{}

	err := json.Unmarshal(jsonBytes, animal)
	if err != nil {
		fmt.Println("err is ", err)
		return
	}

	fmt.Println(*animal)
}

```
