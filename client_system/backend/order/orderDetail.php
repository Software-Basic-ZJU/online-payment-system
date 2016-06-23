<?php

session_start();

header('Content-type: application/json');
require_once 'connection.php';
require_once 'checkStringSafety.php';
require_once 'checkPasswordSafety.php';
require_once '../pam/verify_token.php';
date_default_timezone_set('Asia/Hong_Kong');
verify_token($_POST['token']);

class query{
    public static function queryOneOrder($order_id)
    {
        $myConn = connection::getConn();
        if ($myConn) {
        } else {
            $tips = array("code" => "1", "msg" => "连接失败", "res" => array("token" => $_SESSION['token']));
            echo json_encode($tips);
            exit();
        }
        $sql= "SELECT state FROM order_records WHERE order_id='$order_id'";
        $result = mysqli_query($myConn, $sql);
        if($row=mysqli_fetch_array($result)){
            $state=$row['state'];
        }
        $sql = "SELECT * FROM `order_records`,`commodity` WHERE `goods_id` = `commodity_id` and `order_id`='$order_id'";
        $result = mysqli_query($myConn, $sql);
        $goods = NULL;
        $res = array();
        while ($row = mysqli_fetch_array($result)) {
            $type = $row['commodity_type'];
            $id = $row['original_id'];
            switch ($type) {
                case 'flight':
                    $result2 = mysqli_query($myConn, "SELECT * FROM `flight` WHERE `flight_id` = '$id'");
                    while ($row2 = mysqli_fetch_array($result2)) {
                        $goods = array("flight_number" => $row2['flight_number'],
                            "begin_city" => $row2['begin_city'],
                            "end_city" => $row2['end_city'],
                            "begin_time" => $row2['begin_time'],
                            "end_time" => $row2['end_time']);
                    }
                    break;
                case 'room':
                    $result2 = mysqli_query($myConn, "SELECT * FROM `hotel` NATURAL JOIN `room` NATURAL JOIN `room_time` WHERE `room_id` = '$id'");
                    while ($row2 = mysqli_fetch_array($result2)) {
                        $goods = array("hotel_name" => $row2['hotel_name'], "room_type" => $row2['room_type'], "begin_date" => $row2['begin_date']);
                    }
                    break;
            }

            $paysql="SELECT * FROM payment as a,transact_flow as b WHERE a.event_id=b.event_id and order_id='$order_id' and b.type='0' and b.state='1'";
            $result=mysqli_query($myConn,$paysql);
            if($row2=mysqli_fetch_array($result)){
                $pay_time=$row2['event_time'];
            }
            $res = array("order_id" => $row['order_id'],
                "amount" => $row['amount'],
                "price" => $row['price'],
                "state" => $state,
                "start_time" => $row['start_time'],
                "close_time" => $row['close_time'],
                "pay_time" => $pay_time,
                "type" => $row['commodity_type'],
                "goods_id" => $row["goods_id"],
                "buyer"=>$row["buyer"],
                "goods" => $goods);
            $seller_id = $row['seller'];
            break;
        }
        $result = mysqli_query($myConn, "SELECT * FROM user WHERE user_id = '$seller_id'");

        if ($row = mysqli_fetch_array($result)) {
            $seller = array("seller_name" => $row['user_name'],
                "email" => $row['email'],
                "phone" => $row['phone_number'],
                "seller_id" => $seller_id,
                "name" => $row['name']);
        }
        $res2 = array("token" => $_SESSION['token'],"order_records" => $res,"seller" => $seller);
        $tips = array("code" => "0", "msg" => "查询完成 ", "res" => $res2);
        echo json_encode($tips);
        connection::freeConn();
    }
}

$orderID = $_POST['order_id'];
checkStringSafety($orderID);
query::queryOneOrder($orderID);

?>