<?php

class connection{
private static $conn;
private final function _construct()
    {
    $conn=@mysqli_connect("127.0.0.1","root","root");
    mysqli_select_db("online_pay",$conn);
    }
private function _clone(){}
public static function getConn()
     {
     if(!(connection::$conn instanceof self ))
          {
               $conn=@mysqli_connect("127.0.0.1","root","root");
               mysqli_select_db($conn,"online_pay");
          }
     return $conn;
     }

public static function freeConn()
{
if(connection::$conn)
   mysqli_close(connection::$conn);
}

}

function sendOrderCurl($user_id,$post_data){
    $curl = curl_init();
    //设置抓取的url
    curl_setopt($curl, CURLOPT_URL, 'https://localhost:3000/notification/'.$user_id);
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

?>



