<?php

session_start();

header('Content-type: text/json');
require_once '../order/connection.php';
require_once '../order/checkStringSafety.php';
require_once '../order/checkPasswordSafety.php';
include '../pam/verify_token.php';
date_default_timezone_set('Asia/Hong_Kong');

verify_token($_POST['token']);

$myConn = connection::getConn();
if ($myConn) {
} else {
    $tips = array("code" => "1", "msg" => "连接失败", "res" => array("token" => $_SESSION['token']));
    echo json_encode($tips);
    exit();
}

$room_id=$_POST['room_id'];
$room_book_number=$_POST['ticket_number'];// 要订购的房间数目
$user_name=$_SESSION['user_name'];
$seller=10000;
$begin_date=$_POST['begin_date'];
$end_date=$_POST['end_date'];


$result=mysqli_query($myConn,"select vip_exp,user_id from user WHERE user_name='$user_name' ");
$result_arr=mysqli_fetch_array($result);
$vip_exp=$result_arr['vip_exp'];
$user_id=$result_arr['user_id'];


$result=mysqli_query($myConn,"select user_discount,vip_discount,amount,price from room WHERE room_id=$room_id");
$result_arr=mysqli_fetch_array($result);
$amount=$result_arr['amount'];
$user_discount=$result_arr['user_discount'];
$vip_discount=$result_arr['vip_discount'];
$price=$result_arr['price'];

$sql2="select count(room_id) as count_room_id from room_time WHERE room_id=$room_id and (('$begin_date'>= begin_date and '$begin_date' < end_date) OR ('$end_date'<= end_date and '$end_date'>begin_date))";
$result2=mysqli_query($myConn,$sql2);
$row2=mysqli_fetch_array($result2);
$count_room_id=$row2['count_room_id'];

if ($vip_exp<100&&$room_book_number>3){
    $res=array(
        "code"=>"1",
        "msg"=>"您订购房间数量超过3间的限制,升级为VIP用户可最多订购5间房间!",
        "res"=>array(
            'token' => $_SESSION['token']
        )
    );
    echo json_encode($res);
    exit();
}
else if($vip_exp>=100&&$room_book_number>5){
    $res=array(
        "code"=>"1",
        "msg"=>"VIP用户最多能订购5间房间!",
        "res"=>array(
            'token' => $_SESSION['token']
        )
    );
    echo json_encode($res);
    exit;
}
else if($amount-$count_room_id<$room_book_number){
    $res=array(
        "code"=>"1",
        "msg"=>"该房型的房间余量不足,请减少房间数量!",
        "res"=>array(
            "amount"=>$amount,
            'token' => $_SESSION['token']
        )
    );
    echo json_encode($res);
    exit;
}
else{
    for ($i=0;$i<$room_book_number;$i++) {
        mysqli_query($myConn, "insert into room_time(room_id,begin_date,end_date) VALUES ($room_id,'$begin_date','$end_date')");
    }

    $result=mysqli_query($myConn,"select hotel_id from room WHERE room_id=$room_id");
    $result_arr=mysqli_fetch_array($result);
    $hotel_id=$result_arr['hotel_id'];

    $vip_exp=$vip_exp+3;
    mysqli_query($myConn,"update user set vip_exp=$vip_exp WHERE user_name='$user_name'");
    $now_single_price=$vip_discount/100*$user_discount/100*$price;
    $total_price=$now_single_price*$room_book_number;

    $state='0';
    $start_time=date('Y-m-d H:i:s');

    $result=mysqli_query($myConn,"select commodity_id from commodity WHERE commodity_type='room' and original_id=$room_id");
    $result_arr=mysqli_fetch_array($result);
    $commodity_id=$result_arr['commodity_id'];


    mysqli_query($myConn, "INSERT INTO order_records(goods_id,amount,price,buyer,seller,state,start_time,action_time) VALUES ($commodity_id,$room_book_number,$total_price,$user_id,$seller,'$state','$start_time','$start_time')");

    $res=array(
         "code"=>"0",
         "msg"=>"成功订购房间!",
         "res"=>array(
             "originalPrice"=>$price,
             "userDiscount"=>$user_discount,
             "vipDiscount"=>$vip_discount,
             "totalPrice"=>$total_price,
            'token' => $_SESSION['token']
         )
     );
     echo json_encode($res);
    exit;
}
