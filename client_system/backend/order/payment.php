<?php
session_start();
header('Content-type: text/json');
include_once 'connection.php';
include_once 'checkStringSafety.php';
include_once 'checkPasswordSafety.php';
include '../pam/verify_token.php';
date_default_timezone_set('Asia/Hong_Kong');
verify_token($_POST['token']);

class payment{
    public static $oside;
    public static function paymentOrder($orderID, $password)
    {
        $myConn=connection::getConn();
        if($myConn){}
        else{
            $tips=array("code"=>"1","msg"=>"连接失败","res"=>array("token"=>$_SESSION['token']));
            echo urldecode(json_encode(url_encode($tips)));
            exit();
        }

        if(checkPasswordSafety($password)) {
            $flag = 0;
            $sql = "SELECT * FROM order_records where order_id='".$orderID."'";
            $result = mysqli_query($myConn, $sql);
            if ($row = mysqli_fetch_array($result)) {
                    $buyer = $row['buyer'];
                    self::$oside=$row['seller'];
                    $price = $row['price'];
                    $flag = 1;
            }
            if ($flag == 0) {
                $tips = array("code" => "2", "msg" => "不存在该订单记录","res"=>array("token"=>$_SESSION['token']));
                echo urldecode(json_encode(url_encode($tips)));
            } else {

                $flag = 0;
                $sql = "SELECT * FROM user where user_id = '$buyer'";
                $result = mysqli_query($myConn, $sql);
                while ($row = mysqli_fetch_array($result)) {
                    if ($row['balance'] < $price) {
                        $flag = -1;
                        break;
                    }
                    if ($row['transaction_password'] == $password) {
                        $balance=$row['balance'];
                        $flag = 1;
                        break;
                    }
                }
                if ($flag == -1) {
                    $tips = array("code" => "3", "msg" => "余额不足","res"=>array("token"=>$_SESSION['token']));
                    echo urldecode(json_encode(url_encode($tips)));
                    exit();
                }
                if ($flag == 0) {
                    $tips = array("code" => "5", "msg" => "密码错误","res"=>array("token"=>$_SESSION['token']));
                    echo urldecode(json_encode(url_encode($tips)));
                    exit();
                }

                $time = date('Y-m-d H:i:s');
                $balance-=$price;
                mysqli_query($myConn, "update order_records set state = 2,action_time='$time' where order_id = '$orderID'");
                mysqli_query($myConn,"update user set balance='$balance' where user_id='$buyer'");
                mysqli_query($myConn, "insert into payment values(NULL, '$buyer', '$orderID', '0')");
                mysqli_query($myConn, "insert into logistics values(NULL, '$buyer', '$orderID', '1')");
                $sql2 = "SELECT * FROM payment where order_id = '$orderID'";
                $result2 = mysqli_query($myConn, $sql2);
                while ($row = mysqli_fetch_array($result2)) {
                    $eventid = $row['event_id'];
                }
                mysqli_query($myConn, "insert into transact_flow (event_id,`type`,event_time,state) values('$eventid', '0', '$time','1')");
                $tips = array("code" => "0", "msg" => "支付完成","res"=>array("token"=>$_SESSION['token']));
                echo urldecode(json_encode(url_encode($tips)));
            }
        }
        connection::freeConn();
    }

    public static function getOside()
    {
        return self::$oside;
    }
}

$orderID=$_POST["order_id"];
$password=md5($_POST["password"]);
if(checkStringSafety($orderID))
    if(checkPasswordSafety($password)) {
        payment::paymentOrder($orderID,$password);
        $title = "交易状态改变";
        $body = "用户付款成功";
        $user_id = payment::getOside();
        $post_data = "title=" . $title . "&body=" . $body;
        sendOrderCurl($user_id,$post_data);
    }

?>