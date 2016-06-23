<?php
session_start();
header('Content-type: text/json');
include_once 'connection.php';
include_once 'checkStringSafety.php';
include_once 'checkPasswordSafety.php';
include '../pam/verify_token.php';
date_default_timezone_set('Asia/Hong_Kong');
verify_token($_POST['token']);

class receive
{
    public static $oside;
    public static function receiveOrder($orderID, $password)
    {
        $myConn = connection::getConn();
        if ($myConn) {
        } else {
            $tips = array("code" => "1", "msg" => "连接失败","res"=>array("token"=>$_SESSION['token']));
            echo urldecode(json_encode(url_encode($tips)));
            exit();
        }

        if (checkPasswordSafety($password)) {
            $flag = 0;
            $sql="SELECT * FROM order_records where order_id='$orderID'";
            $result=mysqli_query($myConn,$sql);
            if($row=mysqli_fetch_array($result)){
                $buyer=$row['buyer'];
                self::$oside=$row['seller'];
                $flag=1;
            }
            if ($flag == 0) {
                $tips = array("code" => "2", "msg" => "不存在该订单记录","res"=>array("token"=>$_SESSION['token']));
                echo urldecode(json_encode(url_encode($tips)));
            } else {

                $flag = 0;
                $sql = "SELECT * FROM user where user_id = '$buyer'";
                $result = mysqli_query($myConn, $sql);
                while ($row = mysqli_fetch_array($result)) {
                    if ($row['transaction_password'] == $password) {
                        $exp = $row['vip_exp'];
                        $flag = 1;
                        break;
                    }
                }
                if ($flag == 0) {
                    $tips = array("code" => "3", "msg" => "密码错误","res"=>array("token"=>$_SESSION['token']));
                    echo urldecode(json_encode(url_encode($tips)));
                    exit();
                }

                $time = date('Y-m-d H:i:s');
                mysqli_query($myConn, "update payment set state = 1 where order_id = '$orderID'");
                mysqli_query($myConn, "update order_records set state = 4,action_time='$time' where order_id = '$orderID'");
                mysqli_query($myConn, "update order_records set close_time = '$time' where order_id = '$orderID'");
                mysqli_query($myConn, "update logistics set state = 2 where order_id = '$orderID'");
                $sql2 = "SELECT * FROM logistics where order_id = '$orderID'";
                $result2 = mysqli_query($myConn, $sql2);
                if($row = mysqli_fetch_array($result2)) {
                    $eventid = $row['event_id'];
                }

                mysqli_query($myConn, "insert into transact_flow (event_id,`type`,event_time,state) values('$eventid','4','$time','1')");
                $tips = array("code" => "0", "msg" => "收货成功",
                    'res'=> array(
                        'token'=>$_SESSION['token']
                    ));

                $sql2 = "SELECT * FROM order_records where order_id = '$orderID'";
                $result2 = mysqli_query($myConn, $sql2);
                while ($row = mysqli_fetch_array($result2)) {
                    $cost = round($row['price']);
                    break;
                }
                $exp = $exp + $cost;
                mysqli_query($myConn, "update user set vip_exp = '$exp' where user_id = '$buyer'");
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
    receive::receiveOrder($orderID, $password);
    $title = "交易状态改变";
    $body = "用户收货成功";
    $user_id = receive::getOside();
    $post_data = "title=".$title."&body=".$body;
    sendOrderCurl($user_id,$post_data);
}

?>