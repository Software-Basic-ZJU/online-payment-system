<?php

header('Content-type: application/json');

include "_include.php";

$email = $_POST['email'];

if(!preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/', $email)){
    $result = array(
        "code" => 2,
        "msg" => "电子邮箱格式错误",
        "res" => array()
    );
    echo json_encode($result);
    exit;
}

$codes = rand(1000, 9999);
$error = sendCodesEmail($email, $codes);

switch($error){
    case -1:{
        $result = array(
            'code' => $error,
            'msg' => "邮件发送失败，请检查你的邮箱地址",
            'res' => array()
        );
        break;
    }
    case 0:{
        $result = array(
            'code' => $error,
            'msg' => "成功发送邮件",
            'res' => array(
                'codes' => encrypt($codes)
            )
        );
        break;
    }
    default:{
        $result = array(
            'code' => $error,
            'msg' => "邮件发送失败,邮件不在用户列表",
            'res' => array()
        );
        break;
    }
}

echo json_encode($result);

?>