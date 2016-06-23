<?php

header('Content-type: application/json');
session_start();

function CheckCard($card_id, $password){
    if(strlen($password) == 6 && strlen($card_id) == 20){
        return true;
    } else{
        return false;
    }
}

//Connect database
global $conn;
include('_include.php');
connectDB();

//Verify token
verify_token($_POST['token']);

//Get information
$user_name = $_SESSION['user_name'];
$card_id = $_POST['card_id'];
$password = $_POST['password'];

//Check card
if(CheckCard($card_id, $password) == false){
    $result = array(
        'code' => 1,
        'msg' => '添加失败,银行卡账户或密码不合规范',
        'res' => array(
            'token' => $_SESSION['token']
        )
    );
    echo json_encode($result);
    mysqli_close($conn);
    exit;
}

//Add card
$add_result = mysqli_query($conn,
    "insert into card (user_name, card_id) values('$user_name', '$card_id')");
if($add_result){
    $result = array(
        'code' => 0,
        'msg' => '添加成功',
        'res' => array(
            'token' => $_SESSION['token']
        )
    );
    echo json_encode($result);
} else{
    if(mysqli_errno($conn) == 1062){
        $result = array(
            'code' => 2,
            'msg' => '添加失败,已经添加过了这张卡',
            'res' => array(
                'token' => $_SESSION['token']
            )
        );
        echo json_encode($result);
    } else{
        $result = array(
            'code' => -1,
            'msg' => '添加失败,数据库错误',
            'res' => array(
                'token' => $_SESSION['token'],
                'errorNo' => mysqli_errno($conn),
                'error' => mysqli_error($conn)
            )
        );
        echo json_encode($result);
    }
}

mysqli_close($conn);
?>