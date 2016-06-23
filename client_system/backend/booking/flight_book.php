<?php
/**
 * Created by PhpStorm.
 * User: achao_zju
 * Date: 6/13/16
 * Time: 9:25 PM
 */
session_start();

header('Content-type: application/json');
require_once '../order/connection.php';
require_once '../order/checkStringSafety.php';
require_once '../order/checkPasswordSafety.php';
include '../pam/verify_token.php';
verify_token($_POST['token']);
date_default_timezone_set('Asia/Hong_Kong');

$myConn = connection::getConn();
if (!$myConn) {
    $tips = array("code" => "1", "msg" => "连接失败", "res" => array("token" => $_SESSION['token']));
    echo json_encode($tips);
    exit();
}


$flight_id=$_POST['flight_id'];
$ticket_number=$_POST['ticket_number'];// 要订购的机票数目
$user_name=$_SESSION['user_name'];
$seller=10000;

$result=mysqli_query($myConn,"select vip_exp,user_id from user WHERE user_name='$user_name' ");
$result_arr=mysqli_fetch_array($result);
$vip_exp=$result_arr['vip_exp'];
$user_id=$result_arr['user_id'];

$result=mysqli_query($myConn,"select user_discount,vip_discount,amount,price from flight WHERE  flight_id='$flight_id'");
$result_arr=mysqli_fetch_array($result);
$amount=$result_arr['amount'];
$user_discount=$result_arr['user_discount'];
$vip_discount=$result_arr['vip_discount'];
$price=$result_arr['price'];




if($vip_exp<100&&$ticket_number>3){
    $res=array(
        "code"=>"1",
        "msg"=>"您订购机票数量超过3张的限制,升级为VIP用户可最多订购5张机票!",
        "res"=>array(
            'token' => $_SESSION['token']
        )
    );
    echo json_encode($res);
    exit();
}
else if($vip_exp>=100&&$ticket_number>5){
    $res=array(
        "code"=>"2",
        "msg"=>"VIP用户最多能订购5张机票!",
        "res"=>array(
            'token' => $_SESSION['token']
        )
    );
    echo json_encode($res);
    exit;
}
else if($amount<$ticket_number){
    $res=array(
        "code"=>"3",
        "msg"=>"机票余量不足,请减少订票数量!",
        "res"=>array(
            "amount"=>$amount ,//返回机票余量
            'token' => $_SESSION['token']
        )
    );
    echo json_encode($res);
    exit;
}
else{
    $amount=$amount-$ticket_number;
    mysqli_query($myConn,"update flight set amount='$amount'WHERE flight_id='$flight_id'");
    $vip_exp=$vip_exp+10;
    mysqli_query($myConn,"update user set vip_exp='$vip_exp'WHERE user_name='$user_name'");
    $now_single_price=$vip_discount/100*$user_discount/100*$price;
    $total_price=$now_single_price*$ticket_number;

    $state='0';
    $start_time=date('Y-m-d H:i:s');
    $result=mysqli_query($myConn,"select commodity_id from commodity WHERE commodity_type='flight' and original_id='$flight_id'");
    if($result) {
        $result_arr = mysqli_fetch_array($result);
        $commodity_id = $result_arr['commodity_id'];

        mysqli_query($myConn, "INSERT INTO order_records (goods_id,amount,price,buyer,seller,state,start_time,action_time) VALUES ($commodity_id,$ticket_number,$total_price,'$user_id','$seller','$state','$start_time','$start_time')");


        $res = array(
            "code" => "0",
            "msg" => "成功订购机票！",
            "res" => array(
                "originalPrice" => $price,
                "userDiscount" => $user_discount,
                "vipDiscount" => $vip_discount,
                "totalPirce" => $total_price,
                'token' => $_SESSION['token']

            )
        );
        echo json_encode($res);
    }
}




