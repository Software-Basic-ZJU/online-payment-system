<?php

header('Content-type: application/json');
session_start();

// Connect database
include "_include.php";

global $conn;
connectDB();

// Get id and password
$user_name = mysqli_escape_string($conn, $_POST['user_name']);
$password = md5($_POST['password']);

// Check id and password
$query_result = mysqli_query($conn, "select * from user 
    where user_name='$user_name' and 
    login_password='$password' limit 1");

if($fetched = mysqli_fetch_array($query_result)){
    $token = $user_name."-".time();
    $token = encrypt($token);
    if($fetched['is_mail_verified'] == null){
        $result = array(
            "code" => 2,
            "msg" => "登录失败,邮箱未认证.已重新发送认证邮件!",
            "res" => array()
        );
        sendVerifyEmail($user_name,$fetched['email']);
        echo json_encode($result);
        exit;
    }
    if($fetched['transaction_password'] == null){
        $hasTranPsw = 0;
    } else{
        $hasTranPsw = 1;
    }
    $result = array(
        "code" => 0,
        "msg" => "登陆成功",
        "res" => array(
            "token" => $token,
            "hasTranPsw" => $hasTranPsw,
            'userName'=>$user_name,
			'userId'=>$fetched['user_id'],
            'is_buyer'=>$fetched['is_buyer']
        )
    );
    $post_data='token='.$token.'&user_id='.$fetched['user_id'];
    sendCurl($post_data);
    echo json_encode($result);
} else {
    $result = array(
        "code" => 1,
        "msg" => "登录失败,用户名或密码错误",
        "res" => array()
    );
    echo json_encode($result);
}

mysqli_close($conn);

?>
