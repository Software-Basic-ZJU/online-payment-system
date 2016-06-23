<?php

header('Content-type: application/json');

//Connect database
global $conn;
include('_include.php');
connectDB();

//Verify codes
if(decrypt($_POST['encrypt_codes']) != $_POST['codes']){
    $result = array(
        'code' => 3,
        'msg' => '验证码错误',
        'res' => array()
    );
    echo json_encode($result);
    mysqli_close($conn);
    exit;
}

//Check new password
if($_POST['new1'] != $_POST['new2']){
    $result = array(
        'code' => 1,
        'msg' => '修改密码失败,两次新密码不一致',
        'res' => array()
    );
    echo json_encode($result);
    mysqli_close($conn);
    exit;
}
if(strlen($_POST['new1']) < 6){
    $result = array(
        "code" => 2,
        "msg" => "密码长度不符合规定",
        "res" => array()
    );
    echo json_encode($result);
    mysqli_close($conn);
    exit;
}

//Get information
$email = $_POST['email'];
$new = md5($_POST['new1']);

//Get the type of password
if($_POST['type'] == 'login'){
    $type = "login_password";
} else{
    $type = "transaction_password";
}

//Change password
$change_result = mysqli_query($conn,
    "update user set $type='$new' where email='$email'");
if($change_result){
    $result = array(
        'code' => 0,
        'msg' => '修改密码成功',
        'res' => array()
    );
    echo json_encode($result);
} else{
    $result = array(
        'code' => -1,
        'msg' => '修改密码失败,更新数据库失败',
        'res' => array(
            'errorNo' => mysqli_errno($conn),
            'error' => mysqli_error($conn)
        )
    );
    echo json_encode($result);
}

mysqli_close($conn);
?>