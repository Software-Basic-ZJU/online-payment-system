<?php
header('Content-type: application/json');
session_start();

//Connect database
global $conn;
include '../order/checkStringSafety.php';
include '_include.php';
connectDB();

//Verify token
verify_token($_POST['token']);

$update_result = mysqli_query($conn,
    "update user set name='".checkStringSafety($_POST['name'])."',
    identity_card='".checkStringSafety($_POST['identity_card'])."', is_name_verified=0
     where user_name='".$_SESSION['user_name']."'");

if($update_result){
    $result = array(
        'code' => 0,
        'msg' => '请耐心等待管理员审核',
        'res' => array(
            'token' => $_SESSION['token']
        )
    );
} else{
    $result = array(
        'code' => -1,
        'msg' => '提交实名认证失败,数据库更新失败',
        'res' => array(
            'errorNo' => mysqli_errno($conn),
            'error' => mysqli_error($conn),
            'token' => $_SESSION['token']
        )
    );
}

echo json_encode($result);

mysqli_close($conn);
?>