<?php
    
    session_start();
    
    header('Content-type: text/json');
    require_once '../order/connection.php';
    require_once '../order/checkStringSafety.php';
    require_once '../order/checkPasswordSafety.php';
    include '../pam/verify_token.php';
    
    verify_token($_POST['token']);
    
    class query{
        public static function queryFLights($begin_city,$end_city,$begin_time,$end_time)
        {
            $myConn = connection::getConn();
            if ($myConn) {
            } else {
                $tips = array("code" => "1", "msg" => "连接失败", "res" => array("token" => $_SESSION['token']));
                echo json_encode($tips);
                exit();
            }
           
            $sql="select * from flight
            where begin_city like '%$begin_city%'
            AND end_city like '%$end_city%'
            AND begin_time like '%$begin_time%'
            AND end_time like '%$end_time%'
            ";

            $result = mysqli_query($myConn, $sql);
            $i=0;
            while ($row = mysqli_fetch_array($result)) {
                
                $res1[$i++] = array("airlineName" => $row['airline_name'],
                                    "flightNumber"=>$row['flight_number'],
                                    "flightId" => $row['flight_id'],
                                    "beginTime" => $row['begin_time'],
                                    "fromPort" => $row['begin_airport'],
                                    "endTime" => $row['end_time'],
                                    "toPort" => $row['end_airport'],
                                    "fromCity"=>$row['begin_city'],
                                    "toCity"=>$row['end_city'],
                                    "price" => $row['price'],
                                    "ifStop" => $row['if_stop'],
                                    "vipDiscount" => $row['vip_discount'],
                                    "userDiscount" => $row['user_discount']);
            }

            $sql2="select * from flight
            where begin_city like '%$begin_city%'
            AND end_city like '%$end_city%'
            AND begin_time like '%$begin_time%'
            AND end_time like '%$end_time%'
            AND ( (vip_discount < 100 )=1 OR (user_discount < 100 )=1 )
            ";

            $result2 = mysqli_query($myConn, $sql2);
            $j=0;
            while ($row2 = mysqli_fetch_array($result2)) {

                $res2[$j++] = array("airlineName" => $row['airline_name'],
                    "flightNumber"=>$row['flight_number'],
                    "flightId" => $row['flight_id'],
                    "beginTime" => $row['begin_time'],
                    "fromPort" => $row['begin_airport'],
                    "endTime" => $row['end_time'],
                    "toPort" => $row['end_airport'],
                    "fromCity"=>$row['begin_city'],
                    "toCity"=>$row['end_city'],
                    "price" => $row['price'],
                    "ifStop" => $row['if_stop'],
                    "vipDiscount" => $row['vip_discount'],
                    "userDiscount" => $row['user_discount']);
            }

            $tips = array(
                          "code" => "0",
                          "msg" => "查询完成 ",
                          "res" =>array(
                                        "token" => $_SESSION['token'],
                                        "flight_list" => $res1
                                        )
                                        );
            echo json_encode($tips);
            connection::freeConn();
        }
        
    }
    
    $begin_city = $_POST['begin_city'];
    $end_city = $_POST['end_city'];
    $begin_month = $_POST['begin_time'];
    $begin_day = $_POST['end_time'];
    checkStringSafety($begin_city);
    checkStringSafety($end_city);
    query::queryFlights($begin_city, $end_city, $begin_month,$begin_day);
    
    ?>