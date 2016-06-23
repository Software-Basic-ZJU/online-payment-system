<?php

include '_include.php';

$msg=sendVerifyEmail($_POST['user_name'],$_POST['email']);
if(!strcmp($msg,'激活邮件发送失败，请确认你的邮箱地址！')){
    $code=1;
}
else{
    $code=0;
}
$res=array(
    'code'=>$code,
    'msg'=>$msg,
    'res'=>array()
);

echo json_encode($res);