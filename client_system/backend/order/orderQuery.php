<?php

session_start();

header('Content-type: text/json');
require_once 'connection.php';
require_once 'checkStringSafety.php';
require_once 'checkPasswordSafety.php';
require_once '../pam/verify_token.php';
date_default_timezone_set('Asia/Hong_Kong');
verify_token($_POST['token']);

class query{
    public static function queryOrders($userID,$span,$state,$buy)
    {
        $myConn = connection::getConn();
        if ($myConn) {
        } else {
            $tips = array("code" => "1", "msg" => "连接失败", "res" => array("token" => $_SESSION['token']));
            echo json_encode($tips);
            exit();
        }
        switch ($span) {
            case 0:
                $timeStart = date('Y-m-d H:i:s', 0);
                $timeEnd = date('Y-m-d H:i:s');
                break;
            case 1:
                $timeStart = date('Y-m-d H:i:s',strtotime(date('Y-m-d')));
                $timeEnd = date('Y-m-d H:i:s');
                break;
            case 2:
                $timeStart = date('Y-m-d H:i:s', strtotime('-7day'));
                $timeEnd = date('Y-m-d H:i:s');
                break;
            case 3:
                $timeStart = date('Y-m-d H:i:s', strtotime('-30day'));
                $timeEnd = date('Y-m-d H:i:s');
                break;
            case 4:
                $timeStart = date('Y-m-d H:i:s', strtotime('-90day'));
                $timeEnd = date('Y-m-d H:i:s');
                break;
            case 5:
                $timeStart = date('Y-m-d H:i:s', strtotime('-365day'));
                $timeEnd = date('Y-m-d H:i:s');
                break;
            case 6:
                $timeStart = date('Y-m-d H:i:s', 0);
                $timeEnd = date('Y-m-d H:i:s', strtotime('-365day'));
                break;
            default:
                $timeStart = date('Y-m-d H:i:s', 0);
                $timeEnd = date('Y-m-d H:i:s');
                break;
        }
        if ($buy)
            $sql = "SELECT * FROM `order_records`,`commodity` WHERE `action_time` <= '$timeEnd' && action_time >= '$timeStart' && `buyer` = '$userID' && `goods_id` = `commodity_id`";
        else $sql = "SELECT * FROM `order_records`,`commodity` WHERE `action_time` <= '$timeEnd' && action_time >= '$timeStart' && `seller` = '$userID' && `goods_id` = `commodity_id`";
        switch ($state) {
            case 0:
                break;
            case 1:
                $sql = $sql . " && state <> 3 && state <> 4 && state <> 5";
                break;
            case 2:
                $sql = $sql . " && `state` = '0'";
                break;
            case 3:
                $sql = $sql . " && `state` = '1'";
                break;
            case 4:
                $sql = $sql . " && `state` = '2'";
                break;
            case 5:
                $sql = $sql . " && `state` = '3'";
                break;
            case 6:
                $sql = $sql . " && `state` = '4'";
                break;
            case 7:
                $sql = $sql . " && `state` = '5'";
                break;
            default:
                break;
        }
        $result = mysqli_query($myConn, $sql);
        $goods = NULL;
        $res = array();
        while ($row = mysqli_fetch_array($result)) {
            $type = $row['commodity_type'];
            $id = $row['original_id'];
            switch ($type) {
                case 'flight':
                    $result2 = mysqli_query($myConn, "SELECT * FROM `flight` WHERE `flight_id` = '$id'");
                    if ($row2 = mysqli_fetch_array($result2)) {
                        $goods = array("flight_number" => $row2['flight_number'],
                            "begin_city" => $row2['begin_city'],
                            "end_city" => $row2['end_city'],
                            "begin_time" => $row2['begin_time'],
                            "end_time" => $row2['end_time']);
                    }
                    break;
                case 'room':
                    $result2 = mysqli_query($myConn, "SELECT * FROM `hotel` NATURAL JOIN `room` NATURAL JOIN `room_time` WHERE `room_id` = '$id'");
                    if ($row2 = mysqli_fetch_array($result2)) {
                        $goods = array(
                            "hotel_name" => $row2['hotel_name'],
                            "room_type" => $row2['room_type'],
                            "begin_date" => $row2['begin_date'],
                            "end_date"=>$row2['end_date']
                        );
                    }
                    break;
            }
            array_push($res, array("order_id" => $row['order_id'],
                "amount" => $row['amount'],
                "price" => $row['price'],
                "state" => $row['state'],
                "action_time" => $row['action_time'],
                "buyer"=>$row["buyer"],
                "seller"=>$row["seller"],
                "type" => $row['commodity_type'],
                "goods" => $goods));
        }
        $res2 = array("token" => $_SESSION['token'],"order_records" => $res);
        $tips = array("code" => "0", "msg" => "查询完成 ", "res" => $res2);
        echo json_encode($tips);
        connection::freeConn();
    }
}

$userID = $_SESSION['user_id'];
$span = $_POST['span'];
$state = $_POST['state'];
$buy = $_POST['type'];
checkStringSafety($userID);
checkStringSafety($span);
checkStringSafety($state);
query::queryOrders($userID, $span, $state,$buy);

?>