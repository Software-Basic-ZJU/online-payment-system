<?php
session_start();
header('Content-type: text/json');
include 'connection.php';
include 'checkStringSafety.php';
include '../pam/verify_token.php';
date_default_timezone_set('Asia/Hong_Kong');
verify_token($_POST['token']);

function url_encode($str) {  
   if(is_array($str))
  {  
        foreach($str as $key=>$value)
         {  
           $str[urlencode($key)] = url_encode($value);  
         }  
  } 
  else
   {  
       $str = urlencode($str);  
   }       
    return $str;  
}  

class cancel
{
    public static $oside;
    public static function cancelOrder($orderid, $reason)
    {
        $myConn = connection::getConn();
        if ($myConn) {
            //echo '连接成功</br>';
            //$tips = array("code"=>"0","msg"=>"connection succeeded");
            //echo  json_encode($tips);
        } else {
            $tips = array("code" => "1", "msg" => "连接失败", "res" => array("token" => $_SESSION['token']));
            echo urldecode(json_encode(url_encode($tips)));
            exit();
        }
        $flag = 0;
        $sql = "SELECT * FROM order_records where order_id='$orderid'";
        $result = mysqli_query($myConn, $sql);
        if($row = mysqli_fetch_array($result)) {
            $buyer = $row['buyer'];
            self::$oside=$row['seller'];
            $flag = 1;
        }
        if ($flag == 0) {
            //echo "不存在该订单记录";
            $tips = array("code" => "2", "msg" => "不存在该订单记录", "res" => array("token" => $_SESSION['token']));
            echo urldecode(json_encode(url_encode($tips)));
        } else {
            $time = date('Y-m-d H:i:s');
            mysqli_query($myConn, "update order_records set state = 5,action_time='$time' where order_id = '$orderid'");
            mysqli_query($myConn, "update order_records set close_time = '$time' where order_id = '$orderid'");
            //echo "记录order_records更改成功</br>";
            mysqli_query($myConn, "insert into cancel (buyer,order_id,reason) values ('$buyer', '$orderid', '$reason')");
            //echo "记录cancel添加成功</br>";
            $sql2 = "SELECT * FROM cancel where order_id = '$orderid'";
            $result2 = mysqli_query($myConn, $sql2);
            while ($row = mysqli_fetch_array($result2)) {
                $eventid = $row['event_id'];
            }
            mysqli_query($myConn, "insert into transact_flow (event_id,type,event_time,state) values('$eventid', '2', '$time','1')");
            //echo "记录transact_flow添加成功</br>";
            $tips = array("code" => "0", "msg" => "取消订单成功!", "res" => array("token" => $_SESSION['token']));
            echo urldecode(json_encode(url_encode($tips)));
        }
    }

    public static function getOside()
    {
        return self::$oside;
    }
}

$orderid= $_POST["order_id"];
$reason= $_POST["reason"];
if(CheckStringSafety($orderid))
   if(CheckStringSafety($reason)) {
       cancel::cancelOrder($orderid, $reason);

       $title = "交易状态改变";
       $body = "用户取消订单，交易失败";
       $user_id = cancel::getOside();
       $post_data = "title=" . $title . "&body=" . $body;
       sendOrderCurl($user_id,$post_data);
   }
connection::freeConn();
?>