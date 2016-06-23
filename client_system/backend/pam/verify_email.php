<?php

include "_include.php";

$token = explode("-", decrypt($_GET['token']));

if($token[1] > time()+7*24*3600){
    echo "该邮件已过期,请重新申请";
} else{
    global $conn;
    connectDB();
    $update_result = mysqli_query($conn,
        "update user set is_mail_verified=1 where user_name='".$token[0]."'");
    if(mysqli_affected_rows($conn)){
        echo "验证成功";
    } else{
        echo "验证失败,token有误!";
    }
}

?>
