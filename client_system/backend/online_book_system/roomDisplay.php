
<?php

    session_start();
    
    header('Content-type: text/json');

    require_once '../order/connection.php';
    require_once '../checkStringSafety.php';
    require_once '../checkPasswordSafety.php';
    require_once '../pam/verify_token.php';
    verify_token($_POST['token']);

    class query
    {
        public static function roomDisplay($hotel_id,$begin_date,$end_date){
            $myConn = connection::getConn();
            if ($myConn) {
            }
            else {
                $tips = array("code" => "1", "msg" => "连接失败", "res" => array("token" => $_SESSION['token']));
                echo json_encode($tips);
                exit();
            }


            //输出酒店介绍
            $sql="select * from hotel where hotel_id=$hotel_id ";
            $result=mysqli_query($myConn,$sql);
            $row = mysqli_fetch_assoc($result);

            $res1 = array("hotel_name" => $row['hotel_name'],
                "place" => $row['place'],
                "star" => $row['star'],
                "score" => $row['score']);

            //输出各种房型
            //echo"床型" ."房价";

            $sql="select room_id,room_type,price,hotel_id,amount from room where amount>0 and hotel_id=$hotel_id ";
            $result=mysqli_query($myConn,$sql);

            $i=0;
            while ($row = mysqli_fetch_array($result)) {
                $room_id=$row['room_id'];
                $amount=$row['amount'];

                $sql2="select count(room_id) as count_room_id from room_time WHERE room_id=$room_id and (('$begin_date'>= begin_date and '$begin_date' < end_date) OR ('$end_date'<= end_date and '$end_date'>begin_date))";
                $result2=mysqli_query($myConn,$sql2);
                $row2=mysqli_fetch_assoc($result2);
                $count_room_id=$row2['count_room_id'];


                if ($amount>$count_room_id) {
                    $res2[$i++] = array("roomType" => $row['room_type'],
                        "price" => $row['price']);
                }
            }
            if ($i==0){
                $res2="该酒店没有满足条件的房间可以提供!";
            }

            $tips = array(
                "code" => "0",
                "msg" => "查询完成 ",
                "res" =>array(
                    "token" => $_SESSION['token'],
                    "res1" => $res1,
                    "res2" => $res2
                )
            );
            echo json_encode($tips);
            connection::freeConn();


        }
    }




    $begin_date=$_POST['begin_date'];
    $end_date=$_POST['end_date'];
    $hotel_id=$_POST['hotel_id'];
    roomDisplay($hotel_id,$begin_date,$end_date)

?>
