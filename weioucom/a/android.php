<?php
/**
* @Description:(入口文件)
* @Author:jiangyuchao
* @E-mail:jiangyc0125@163.com
* @Date 2014-11-12 下午4:35:28
* @Version V1.0
*/
error_reporting(1);
header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('Asia/Shanghai');
define("DIR",dirname(dirname(dirname(__FILE__))));

ini_set("include_path", ini_get("include_path").PATH_SEPARATOR.DIR."/core");//设置框架所在目录为目录为include_path

require_once DIR.'/core/log4php/Logger.php';
Logger::configure(DIR.'/setting/log4php.properties');
//$logger = Logger::getLogger("android.php");

include_once 'App.class.php';
include_once "Mysqldatabase.php";
include_once 'Mongodatabase.php';
include_once 'View.class.php';

$app = App::getInstance();
$app->setConfPath(DIR."/setting");

$mysql = new Mysqldatabase($app->loadConf("mysql_setting"));
$app->setMysql($mysql);

$sql = "SELECT `version_download_url` from `t_version` WHERE `code_platform`= :code_platform ORDER BY `versionNum` desc limit 1";

$selectArr = array(':code_platform'=>7);
$row = $mysql->fetchRow($sql,$selectArr);

$url=$row["version_download_url"];
Header("Location: ".${url}); 


