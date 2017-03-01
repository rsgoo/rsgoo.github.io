<?php

$is_girl = $_GET['sex'] == 0 ? true : false;

if ($is_girl){
    require('class\class1.php');
    echo "this is a girl";
    $class1 = new Class1();
} else {
    require('class\class2.php');
    echo "not a girl";
    $class2 = new Class2();
}

?>