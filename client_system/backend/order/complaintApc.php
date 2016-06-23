<?php
session_start();
header('Content-type:text/json');
include 'connection.php';
include 'checkStringSafety.php';
include '../pam/verify_token.php';
date_default_timezone_set('Asia/Hong_Kong');

verify_token($_POST['token']);
function url_encode($str){
    if(is_array($str)){
        foreach($str as $key=>$value){
            $str[urlencode($key)]=url_encode($value);
        }
    }
    else{
        $str=urlencode($str);
    }
    return $str;
}
//comlaint application
class complaintApc{
    public static $oside;
    public static function complaintOrder($orderID,$reason)
    {
        $myConn=connection::getConn();
        if($myConn){}
        else{
            $tips=array("code"=>"1","msg"=>"连接失败","res"=>array("token"=>$_SESSION['token']));
            echo urldecode(json_encode(url_encode($tips)));
            exit();
        }
        $flag=0;
        $sql="SELECT * FROM order_records where order_id='".$orderID."'";
        $result=mysqli_query($myConn,$sql);
        if($row=mysqli_fetch_array($result)){
            $buyer=$row['buyer'];
            self::$oside=$row['seller'];
            $state=0;
            $flag=1;
        }
        if($flag==0){
            $tips = array("code"=>"2" ,"msg"=>"不存在该订单记录" ,"res"=>array("token"=>$_SESSION['token']));
            echo urldecode(json_encode(url_encode($tips)));
        }
        else{
            $time=date('Y-m-d H:i:s');
            //change the state of the order
            mysqli_query($myConn,"update order_records set state=7,action_time='$time' where order_id='$orderID'");
            //add it to the complaint table
            mysqli_query($myConn,"insert into complaint (buyer,order_id,reason,state)  values('$buyer','$orderID','$reason','$state')");
            //record the transaction flow
            $sql2="SELECT*FROM complaint where order_id='$orderID'";
            $result2=mysqli_query($myConn,$sql2);
            while($row=mysqli_fetch_array($result2)){
                $eventID=$row['event_id'];
            }
            mysqli_query($myConn,"insert into transact_flow (event_id,`type`,event_time,state) values('$eventID','3','$time','$state')");
            $tips=array("code"=>"0","msg"=>"申请完成，等待审核","res"=>array("token"=>$_SESSION['token']));
            echo urldecode(json_encode(url_encode($tips)));
        }
    }

    public static function getOside()
    {
        return self::$oside;
    }
}

$orderID=$_POST["order_id"];
$reason=$_POST["reason"];
if(checkStringSafety($orderID))
    if(checkStringSafety($reason)){
        complaintApc::complaintOrder($orderID,$reason);
        $title="交易状态改变";
        $body="用户申请申诉";
        $user_id=complaintApc::getOside();
        $post_data="title=".$title."&body=".$body;
        sendOrderCurl($user_id,$post_data);
    }

connection::freeConn();
?>