<?php
    //调用配置文件
    include'config.php';
    //链接数据库
    $con=mysqli_connect($url,$user,$password);
    if (!$con) {die('Could not connect: ' . mysqli_error());}
    mysqli_query($con,"set names utf8");
    $str1="CREATE DATABASE IF NOT EXISTS ".$dataBase." DEFAULT CHARSET utf8 COLLATE utf8_general_ci;";
    $result=mysqli_query($con,$str1);
    if (!$result) {die('创建数据库失败！:'.mysqli_error($con));}
    mysqli_select_db($con,$dataBase);
    $str2='CREATE TABLE IF NOT EXISTS '.$table.'('.
            'id     INT UNSIGNED AUTO_INCREMENT ,'.
            'user   VARCHAR(20) NOT NULL ,'.
            'pass   VARCHAR(255) NOT NULL ,'.
            'email  VARCHAR(20) NOT NULL ,'.
            'tel    VARCHAR(15) NOT NULL ,'.
            'PRIMARY KEY(id))ENGINE=InnoDB DEFAULT CHARSET=utf8;';
    $result=mysqli_query($con,$str2);
    if(!$result) {die('数据表创建失败!: ' . mysqli_error($con));}

    mysqli_close($con);
?>