<?php
/**
 * Created by PhpStorm.
 * User: achao_zju
 * Date: 6/16/16
 * Time: 6:00 PM
 */
session_start();

header('Content-type: text/json');
require_once '../order/connection.php';
require_once '../order/checkStringSafety.php';
require_once '../order/checkPasswordSafety.php';
include '../pam/verify_token.php';
verify_token($_POST['token']);

//$myConn=connectDB();
$myConn = connection::getConn();
if ($myConn) {
}
else {
   $tips = array("code" => "1", "msg" => "连接失败", "res" => array("token" => $_SESSION['token']));
   echo json_encode($tips);
    exit();
}


$order_id=$_POST['order_id'];
$user_name=$_SESSION['user_name'];



$result=mysqli_query($myConn,"select goods_id from order_records WHERE order_id='$order_id'");
$result_arr=mysqli_fetch_assoc($result);
$commodity_id=$result_arr['goods_id'];

$result=mysqli_query($myConn,"select original_id, hotel_id from commodity,room WHERE commodity_id='$commodity_id' AND original_id=room_id");
$result_arr=mysqli_fetch_assoc($result);
$room_id=$result_arr['original_id'];
$hotel_id=$result_arr['hotel_id'];

$result=mysqli_query($myConn,"select * from comment WHERE room_id='$room_id' and user_name='$user_name'");
$dataCount=mysqli_num_rows($result);
if ($dataCount>0){
    $ifComment=1;
}
else{
    $ifComment=0;
}
$result = array(
    "code" => 0,
    "msg" => "检查成功",
    "res" => array(
        'ifComment'=>$ifComment,
        'token' => $_SESSION['token'],
        'hotel_id'=>$hotel_id,
        'room_id'=>$room_id
    )
);
echo json_encode($result);

