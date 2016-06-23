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

//do the procession after complaint application
class complaintPrc{
    public static $oside;
    //$acptOrNot=0 未处理 /=1 已处理
    public static function prcComplaintOrder($orderID,$acptOrNot)
    {
        $myConn=connection::getConn();
        if($myConn){}
        else{
            $tips=array("code"=>"1","msg"=>"连接失败","res"=>array("token"=>$_SESSION['token']));
            echo urldecode(json_encode(url_encode($tips)));
            exit();
        }
        $flag=0;
        $sql="SELECT * FROM complaint natural join order_records where complaint.order_id='$orderID'";
        $result=mysqli_query($myConn,$sql);
        if($row=mysqli_fetch_array($result)){
            self::$oside=$row['buyer'];
            $flag=1;
        }
        if($flag==0){
            $tips = array("code"=>"2" ,"msg"=>"不存在该订单记录","res"=>array("token"=>$_SESSION['token']) );
            echo urldecode(json_encode(url_encode($tips)));
        }
        else{
            $time=date('Y-m-d H:i:s');
            //change the state of the order and fund table
            if($acptOrNot==1){
                mysqli_query($myConn,"update complaint set state=1 where order_id='$orderID'");
                //record the transaction flow
                $sql2="SELECT*FROM complaint where order_id='$orderID'";
                $result2=mysqli_query($myConn,$sql2);
                while($row=mysqli_fetch_array($result2)){
                    $eventID=$row['event_id'];
                }
                mysqli_query($myConn,"insert into transact_flow values('$eventID','3','$acptOrNot','$time')");
                $tips=array("code"=>"0","msg"=>"处理成功","res"=>array("token"=>$_SESSION['token']));
                echo urldecode(json_encode(url_encode($tips)));

            }
            else if($acptOrNot==0){}
            else{
                $tips=array("code"=>"5","msg"=>"含有非法字符","res"=>array("token"=>$_SESSION['token']));
                echo urldecode(json_encode(url_encode($tips)));
            }

        }
    }

    public static function getOside()
    {
        return self::$oside;
    }
}

$orderID=$_POST["order_id"];
$acptOrNot=$_POST["arbitration"];
if(checkStringSafety($orderID))
    if(checkStringSafety($acptOrNot)){
        complaintPrc::prcComplaintOrder($orderID,$acptOrNot);
        if($acptOrNot==1){
            $title="仲裁结果";
            $body="仲裁已处理";
            $user_id=complaintPrc::getOside();
            $post_data="title=".$title."&body=".$body;
            sendOrderCurl($user_id,$post_data);
        }
    }

connection::freeConn();
?>