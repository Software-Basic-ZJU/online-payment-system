<?php
header('Content-type: application/json');

//Connect database
global $conn;
include('_include.php');
connectDB();

//Verify token
verify_token($_POST['token']);

//Check new password
if($_POST['new1'] != $_POST['new2']){
    $result = array(
        'code' => 1,
        'msg' => '修改密码失败,两次新密码不一致',
        'res' => array(
            'token' => $_SESSION['token']
        )
    );
    echo json_encode($result);
    mysqli_close($conn);
    exit;
}
if(strlen($_POST['new1']) < 6){
    $result = array(
        "code" => 2,
        "msg" => "密码长度不符合规定",
        "res" => array(
            'token' => $_SESSION['token']
        )
    );
    echo json_encode($result);
    mysqli_close($conn);
    exit;
}

//Encode passwords
$login_password = md5($_POST['login_password']);
$new = md5($_POST['new1']);
$user_name = $_SESSION['user_name'];

//Query
$query_result = mysqli_query($conn,
    "select * from user where user_name='$user_name' and login_password='$login_password'");

if(mysqli_fetch_row($query_result)){
    //Change password
    $change_result = mysqli_query($conn,
        "update user set transaction_password='$new' where user_name='$user_name'");
    if($change_result){
        $result = array(
            'code' => 0,
            'msg' => '设置支付密码成功',
            'res' => array(
                'token' => $_SESSION['token']
            )
        );
        echo json_encode($result);
    } else{
        $result = array(
            'code' => -1,
            'msg' => '设置密码失败,更新数据库失败',
            'res' => array(
                'errorNo' => mysqli_errno($conn),
                'error' => mysqli_error($conn),
                'token' => $_SESSION['token']
            )
        );
        echo json_encode($result);
    }
} else{
    $result = array(
        'code' => 3,
        'msg' => '设置密码失败,登陆密码错误',
        'res' => array(
            'token' => $_SESSION['token']
        )
    );
    echo json_encode($result);
}

mysqli_close($conn);
?>