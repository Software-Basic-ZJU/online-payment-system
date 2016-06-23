<?php
require_once 'functions.php';
$conn=connectDB();?>
<html>
<head>
    <meta  http-equiv="Content-Type" content="text/html; charset="utf-8"/>


    <style>
        .error {color: #FF0000;}
        div.class0{
            position: absolute;
            left:220px;
            top: 160px;
        }
        div.class1{
            position: absolute;
            left: 220px;
            width: 220px;
            top: 210px;
        }
        div.class2{
            position: absolute;
            left: 220px;
            width: 220px;
            top: 150px;
        }
        div.class3{
            position: relative;
            top: 90px;
            left: 220px;
        }
        div.right{
            position: absolute;
            top: 100px;
            left: 190px;
            padding: 4px;
        }
        div.right2{
            position: absolute;
            top: 85px;
            left: 460px;
            padding: 4px;
        }
        fieldset{
            border: 2px solid #4CAF50;
            padding-left: 10px;
            padding-right: 10px;
        }
        table, th, td{
            border: 1px solid black;
            border-collapse: collapse;
        }
        th, td{
            text-align: left;
            padding: 4px;
        }
        tr:nth-child(even){
            background-color: #e6e6e6;
        }
        tr:nth-child(odd){
            background-color: #ffffff;
        }
        th{
            background-color: #4CAF50;
            color: white;
        }
    </style>
    <style type="text/css">

        a:link {
            text-decoration: none;
            color: gray;
        }

        a:visited {
            text-decoration: none;
            color: gray;
        }

        a:hover {
            text-decoration: none;
            color: black;
        }

        a:active {
            text-decoration: none;
            color: gray;
        }

        body {
            margin-left: 0;
            margin-top: 0;
        }

        div {
            font-size: 24px;
            font-family: "微软雅黑";
            font-weight: bold;
        }

        #zero {
            text-align: center;
            color: white;
            height: 80px;
            width:100.65%;
            background: grey;
            font-size: 40px;
            line-height: 80px;
        }
        #from_import {
                        
        }

        #one {
            float: top;
            width: 12.5%;
            font-size: 24px;
            background: #FFFFDF;
            line-height: 40px;
        }

        #one li ul {
            display: none;
            width: 100px;
        }

        #one li:hover ul {
            display: block;
        }

    </style>
</head>
<script>
    function show(){
        var date = new Date(); //日期对象
        var now = "";
        now = date.getFullYear()+"年"; //读英文就行了
        now = now + (date.getMonth()+1)+"月"; //取月的时候取的是当前月-1如果想取当前月+1就可以了
        now = now + date.getDate()+"日";
        now = now + date.getHours()+"时";
        now = now + date.getMinutes()+"分";
        now = now + date.getSeconds()+"秒";
        document.getElementById("nowDiv").innerHTML = now; //div的html是now这个字符串
        setTimeout("show()",1000); //设置过1000毫秒就是1秒，调用show方法
    }
</script>

<body onload="show()"> <!-- 网页加载时调用一次 以后就自动调用了-->
<div id="nowDiv" align="right"></div>

<div id="zero">在线订购子系统</div>
<br>

<div id="one">
    <ul>
        <li><a href="hotel_query.php">酒店查询</a></li>
        <li><a href="hotel_import.php">添加酒店</a>
        </li>
        <li><a href="room_query.php">房间查询</a> </li>
        <li><a href="room_import.php">添加房间</a>
        <ul>
        </ul>
        </li>
        <li><a href="flight_query.php">航班查询</a></li>
        <li><a href="flight_import.php">添加航班</a>
        </li>
    </ul>
</div>



</body>
</html>
