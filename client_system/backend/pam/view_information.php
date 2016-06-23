<?php

header('Content-type: application/json');
session_start();

// Connect database
global $conn;
include "_include.php";

connectDB();

//Verify token & Get user's information
$info = verify_token($_POST['token']);
$user_name=$_SESSION['user_name'];
// Get user's cards
$cards_query = mysqli_query($conn,
    "select card_id from card where user_name='$user_name'");
$cards_info = array();
$i = 0;
if($cards_query){
    while($card_num = mysqli_fetch_array($cards_query)){
        $cards_info[$i] = $card_num["card_id"];
        $i ++;
    }
}

//The state of verifying user
if($info['name'] != null && $info['identity_card'] != null){
    if($info['is_name_verified']){
        $identity_state = 1; //已经验证
    } else{
        $identity_state = 0; //正在等待验证
    }
} else{
    $identity_state = -1; //未提交认证或者认证失败
}

// Echo information
$result = array(
    "code" => 0,
    "msg" => "获取信息成功",
    "res" => array(
        'userName'=>$info['user_name'],
        "gender" => $info["gender"],
        "is_buyer" => $info["is_buyer"],
        "email" => $info["email"],
        "phone_number" => $info["phone_number"],
        "mail_verified" => $info["is_mail_verified"],
        "balance" => $info["balance"],
        "vip_exp" => $info["vip_exp"],
        "cards_info" => $cards_info,
        'token' => $_SESSION['token'],
        'name'=>$info['name'],
        'identity_state' => $identity_state,
        'isSetPay'=>$info['transaction_password']
    )
);
echo json_encode($result);

mysqli_close($conn);

?>