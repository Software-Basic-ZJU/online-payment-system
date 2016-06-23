<?php
include '../order/checkStringSafety.php';
header('Content-type: application/json');
session_start();

//Connect database
global $conn;
include('_include.php');
connectDB();

//Verify token
verify_token($_POST['token']);

$update_result = mysqli_query($conn,
    "update user set gender='".checkStringSafety($_POST['gender'])."',
    phone_number='".checkStringSafety($_POST['phone_number'])."'
    where user_name='".checkStringSafety($_SESSION['user_name'])."'");

if($update_result){
    $result = array(
        'code' => 0,
        'msg' => '修改成功',
        'res' => array(
            'token' => $_SESSION['token']
        )
    );
    echo json_encode($result);
} else{
    $result = array(
        'code' => -1,
        'msg' => '修改失败,数据库更新失败',
        'res' => array(
            'errorNo' => mysqli_errno($conn),
            'error' => mysqli_error($conn),
            'token' => $_SESSION['token']
        )
    );
    echo json_encode($result);
}

mysqli_close($conn);
?>