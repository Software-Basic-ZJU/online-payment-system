<?php

header('Content-type: application/json');
session_start();

//    Get information
$user_name = $_POST['user_name'];
$password = $_POST['password'];
$email = $_POST['email'];
$gender = $_POST['gender'];
if($_POST['user_type'] == "buyer"){
    $is_buyer = 1;
} else{
    $is_buyer = 0;
}

//    Check information format
if(!preg_match('/^\w{3,40}$/', $user_name)){
    $result = array(
        "code" => 1,
        "msg" => "用户名不符合规定",
        "res" => array()
    );
    echo json_encode($result);
    exit;
}
if(strlen($password) < 6){
    $result = array(
        "code" => 2,
        "msg" => "密码长度不符合规定",
        "res" => array()
    );
    echo json_encode($result);
    exit;
}
if(!preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/', $email)){
    $result = array(
        "code" => 3,
        "msg" => "电子邮箱格式错误",
        "res" => array()
    );
    echo json_encode($result);
    exit;
}

//    Connect database
global $conn;
include('_include.php');

connectDB();

//    Check user_name
$id_query = "select user_name from user where user_name='$user_name' limit 1";
$id_check = mysqli_query($conn, $id_query);
if(mysqli_num_rows($id_check) >= 1){
    $result = array(
        "code" => 4,
        "msg" => "用户名已存在",
        "res" => array()
    );
    echo json_encode($result);
    mysqli_close($conn);
    exit;
}

//Check email
$email_query = "select user_name from user where email='$email' limit 1";
$email_check = mysqli_query($conn, $email_query);
if(mysqli_num_rows($email_check) >= 1){
    $result = array(
        "code" => 5,
        "msg" => "邮箱已注册",
        "res" => array()
    );
    echo json_encode($result);
    mysqli_close($conn);
    exit;
}

//    Insert tuple
$password = md5($password);
$insert_result = mysqli_query($conn,
    "insert into user(user_name, login_password, gender, is_buyer, 
email, balance, vip_exp)
values('$user_name', '$password', '$gender', '$is_buyer', '$email', 0, 0)");

if($insert_result){
    $result = array(
        "code" => 0,
        "msg" => "恭喜您,注册成功",
        "res" => array()
    );
    sendVerifyEmail($user_name, $email);
    echo json_encode($result);

} else {
    $result = array(
        "code" => -1,
        "msg" => "添加数据失败",
        "res" => array(
            'errorNo' => mysqli_errno($conn),
            'error' => mysqli_error($conn)
        )
    );
    echo json_encode($result);
}

mysqli_close($conn);

?>
