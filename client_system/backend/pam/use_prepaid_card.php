<?php
header('Content-type: application/json');
session_start();

//Connect database
global $conn;
include('_include.php');
connectDB();

//Verify token
verify_token($_POST['token']);

$card_id = $_POST['card_id'];
$password = $_POST['password'];
$user_name = $_SESSION['user_name'];

$query = mysqli_query($conn, "select * from prepaid_card where card_id='$card_id' 
    and password='$password' and is_used=0");
$query_result = mysqli_fetch_array($query);

if(!$query_result){
    $result = array(
        'code' => 1,
        'msg' => '充值失败,卡号或者密码错误,或者充值卡已过期',
        'res' => array(
            'token' => $_SESSION['token']
        )
    );
} else{
    $update_result = mysqli_query($conn,
        "update user set balance=balance+'".$query_result['amount']."' 
        where user_name='$user_name' ");
    if($update_result){
        $use_card = mysqli_query($conn,
            "update prepaid_card set is_used=1 where card_id='$card_id'");
        $result = array(
            'code' => 0,
            'msg' => '充值成功',
            'res' => array(
                'amount' => $query_result['amount'],
                'token' => $_SESSION['token']
            )
        );
    } else{
        $result = array(
            "code" => -1,
            "msg" => "充值失败,添加数据失败",
            "res" => array(
                'errorNo' => mysqli_errno($conn),
                'error' => mysqli_error($conn),
                'token' => $_SESSION['token']
            )
        );
    }
}

echo json_encode($result);

mysqli_close($conn);
?>