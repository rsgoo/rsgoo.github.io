---
title: test article
date: 2019-08-02 19:10:54
type: archives
tags:
    - PHP
    - Hexo
categories:
    - 测试
---
#### Hexo 部署测试

##### 1：通过日志方式统计

<!--more-->

```php
file_put_contents("loc.log", microtime(true).'_end'."-----$lat@2".PHP_EOL, FILE_APPEND);

file_put_contents('exelog/'.time().'.log', var_export(Yii::$app->request->post(), true));


$postdata = file_get_contents("php://input");
$postdata = json_decode($postdata,true);


```

##### 2：二维数组指定字段排序

```php
array_multisort (array_column($citiesMddBaseInfo, ‘hot’), SORT_DESC, $citiesMddBaseInfo);

array_multisort(array_column($hotelList, 'price'), SORT_ASC, $hotelList);

array_multisort($hotelList,SORT_ASC, array_column($hotelList, 'price'));
```


<!--![](./post-test-deploy-hexo/smell.jpg)-->

{% asset_img smell.jpg 这是一张测试图片 %}

