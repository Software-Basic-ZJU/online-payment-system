<?php
session_start();
header('Content-type:application/json');
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

//seller do the procession after refund application
class refundPrc{
    public static $oside;
    //$acptOrNot=1 同意退款 /=2 不同意
    public static function prcRefundOrder($orderID,$acptOrNot,$price,$buyer)
    {
        $myConn=connection::getConn();
        if($myConn){}
        else{
            $tips=array("code"=>"1","msg"=>"连接失败","res"=>array("token"=>$_SESSION['token']));
            echo json_encode($tips);
            exit();
        }
        $flag=0;
        $sql="SELECT * FROM refund JOIN order_records ON refund.order_id=order_records.order_id WHERE refund.order_id='$orderID'";
        $result=mysqli_query($myConn,$sql);
        if($row=mysqli_fetch_array($result)){
            self::$oside=$row['buyer'];
            $flag=1;
        }
        if($flag==0){
            $tips = array("code"=>"2" ,"msg"=>"不存在该订单记录" ,"res"=>array("token"=>$_SESSION['token']));
            echo json_encode($tips);
        }
        else{
            $time=date('Y-m-d H:i:s');
            //change the state of the order and fund table
            if($acptOrNot=='1'){
                mysqli_query($myConn,"update order_records set state=3, action_time='$time' ,close_time = '$time' where order_id='$orderID'");
                $res=mysqli_query($myConn,"update refund set state=1 where order_id='$orderID'");
                if($res){
                    $result=mysqli_query($myConn,"select * from user where user_id='$buyer'");
                    if($row=mysqli_fetch_array($result)){
                        $balance=$row['balance'];
                        $balance+=$price;
                        mysqli_query($myConn,"update user set balance='$balance' where user_id='$buyer'");
                    }
                }
            }
            else if($acptOrNot=='2'){
                mysqli_query($myConn,"update order_records set state=2, action_time='$time' where order_id='$orderID'");
                mysqli_query($myConn,"update refund set state=2 where order_id='$orderID'");
            }
            else{
                $tips=array("code"=>"5","msg"=>"含有非法字符","res"=>array("token"=>$_SESSION['token']));
                echo json_encode($tips);
            }
            //record the transaction flow
            $sql2="SELECT * FROM refund where order_id='$orderID'";
            $result2=mysqli_query($myConn,$sql2);
            if($row=mysqli_fetch_array($result2)){
                $eventID=$row['event_id'];
            }
            mysqli_query($myConn,"insert into transact_flow (event_id,type,event_time,state) values('$eventID','1','$time','$acptOrNot')");
            $tips=array("code"=>"0","msg"=>"处理成功","res"=>array("token"=>$_SESSION['token']));
            echo json_encode($tips);
        }
    }

    public static function getOside()
    {
        return self::$oside;
    }
}

$orderID=$_POST["order_id"];
$acptOrNot=$_POST["decision"];
$price=$_POST["price"];
$buyer=$_POST["buyer"];
if(checkStringSafety($orderID))
    if(checkStringSafety($acptOrNot)) {
        refundPrc::prcRefundOrder($orderID, $acptOrNot,$price,$buyer);
        if ($acptOrNot == 1) {
            $title = "交易状态改变";
            $body = "退款成功";
        } else if ($acptOrNot == 2) {
            $title = "交易状态改变";
            $body = "退款失败";
        }
        $user_id = refundPrc::getOside();
        $post_data = "title=" . $title . "&body=" . $body;
        sendOrderCurl($user_id,$post_data);
    }

connection::freeConn();
?>