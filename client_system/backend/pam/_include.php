<?php

include 'verify_token.php';

function sendCurl($post_data){
    //初始化
    $curl = curl_init();
    //设置抓取的url
    curl_setopt($curl, CURLOPT_URL, 'https://localhost:3000/notification');
    //设置头文件的信息作为数据流输出
    curl_setopt($curl, CURLOPT_HEADER, 0);
    //设置获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
    //设置post方式提交
    curl_setopt($curl, CURLOPT_POST, 1);
    //设置post数据
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
    //执行命令
    $data = curl_exec($curl);
    //关闭URL请求
    curl_close($curl);
    //返回获得的数据
    return $data;
}

function sendVerifyEmail($user_name, $email){
    /**
     * 注：本邮件类都是经过我测试成功了的，如果大家发送邮件的时候遇到了失败的问题，请从以下几点排查：
     * 1. 用户名和密码是否正确；
     * 2. 检查邮箱设置是否启用了smtp服务；
     */
    require_once "email.class.php";

//******************** 配置变量 ********************************
    $smtpserver = "smtp.126.com";//SMTP服务器
    $smtpserverport =25;//SMTP服务器端口
    $smtpusermail = "j98355@126.com";//SMTP服务器的用户邮箱
    $smtpemailto = $email;//发送给谁
    $fromName = '亿颗赛艇网';
    $smtpuser = "j98355@126.com";//SMTP服务器的用户帐号
    $smtppass = "wen201";//密码，或者授权码

    $mailtitle = "【亿颗赛艇】请认证您的注册邮箱";//邮件主题
    $token = encrypt($user_name."-".time());
    $url = "https://tx.zhelishi.cn:8080/backend/pam/verify_email.php?token=".urlencode($token);
    $mailcontent = "尊敬的用户".$user_name.":<br/>"."您好，点击<a href='".$url."'>链接</a>即可认证邮箱<br/>";//邮件内容

    $mailtype = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件

//************************ 创建对象 ****************************
    $smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);
//这里面的一个true是表示使用身份验证,否则不使用身份验证.
    $smtp->debug = false;//是否显示发送的调试信息，默认不发送
    $state = $smtp->sendmail($smtpemailto, $smtpusermail, $fromName,
        $mailtitle, $mailcontent, $mailtype);
    if($state=="")
        $error = "激活邮件发送失败，请确认你的邮箱地址！";
    else
        $error = "成功发送邮件！";
    return $error;
}

function sendCodesEmail($email, $codes){
    /**
     * 注：发送邮件的时候遇到了失败的问题，请从以下几点排查：
     * 1. 用户名和密码是否正确；
     * 2. 检查邮箱设置是否启用了smtp服务；
     */
    require_once "email.class.php";

//******************** 配置变量 ********************************
    $smtpserver = "smtp.126.com";//SMTP服务器
    $smtpserverport =25;//SMTP服务器端口
    $smtpusermail = "j98355@126.com";//SMTP服务器的用户邮箱

    //Connect database
    global $conn;
    connectDB();

    $query = mysqli_query($conn,
        "select user_name from user where email='$email' limit 1");
    $user_name = mysqli_fetch_array($query)['user_name'];

    if(!$user_name){
        return 1;   //邮件发送失败,邮箱地址不在用户列表
    }

    $smtpemailto = $email;//发送给谁

    $fromName = '亿颗赛艇网';
    $smtpuser = "j98355@126.com";//SMTP服务器的用户帐号
    $smtppass = "wen201";//密码，或者授权码

    $mailtitle = "【亿颗赛艇】忘记密码";//邮件主题
    $mailcontent = "尊敬的用户 ".$user_name.":<br/>"."您正在申请重置密码，您的验证码为：<br/>".$codes;//邮件内容

    $mailtype = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件

//************************ 创建对象 ****************************
    $smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);
    //这里面的一个true是表示使用身份验证,否则不使用身份验证.
    $smtp->debug = false;//是否显示发送的调试信息，默认不发送
    $state = $smtp->sendmail($smtpemailto, $smtpusermail, $fromName,
        $mailtitle, $mailcontent, $mailtype);
    if($state=="")
        $error = -1;
    else
        $error = 0;

    return $error;
}

?>