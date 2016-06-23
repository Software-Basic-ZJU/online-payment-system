<?php
    session_start();
    
    header('Content-type: text/json');
    require_once '../order/connection.php';
    require_once '../order/checkStringSafety.php';
    require_once '../order/checkPasswordSafety.php';
    include '../pam/verify_token.php';

    verify_token($_POST['token']);
    
    class query{
        public static function queryHotels($place,$star_option,$price_option,$score_option)
        {
            $myConn = connection::getConn();
            if ($myConn) {
            } else {
                $tips = array("code" => "1", "msg" => "连接失败", "res" => array("token" => $_SESSION['token']));
                echo json_encode($tips);
                exit();
            }
            switch ($star_option) {
                case 0:
                    $star1='0';
                    $star2='5';
                    break;
                case 1:
                    $star1='0';
                    $star2='2';
                    break;
                case 2:
                    $star1='2';
                    $star2='3';
                    break;
                case 3:
                    $star1='3';
                    $star2='4';
                    break;
                case 4:
                    $star1='4';
                    $star2='5';
                    break;
            }
            
            switch($price_option){
                case 0:
                    $price1='-1';
                    $price2='32768';
                    break;
                case 1:
                    $price1='-1';
                    $price2='150';
                    break;
                case 2:
                    $price1='150';
                    $price2='300';
                    break;
                case 3:
                    $price1='300';
                    $price2='450';
                    break;
                case 4:
                    $price1='450';
                    $price2='600';
                    break;
                case 5:
                    $price1='600';
                    $price2='1000';
                    break;
                case 6:
                    $price1='1000';
                    $price2='9999';
                    break;
            }
            
            switch($score_option){
                case 0:
                    $score='0.0';
                    break;
                case 1:
                    $score='3.0';
                    break;
                case 2:
                     $score='3.5';
                    break;
                case 3:
                     $score='4.0';
                    break;
                case 4:
                     $score='4.5';
                    break;
                
            }
            
            
            $sql="SELECT * FROM hotel
            where place like '%$place%'
            AND (star >= $star1)=1
            AND (star <= $star2)=1
            AND (lowest_price >= $price1)=1
            AND (lowest_price <= $price2)=1
            AND (score >= $score)=1
            ";
            
            
            $result = mysqli_query($myConn, $sql);
            $res1=array();
            $res2=array();
            $i=0;$j=0;
            while ($row = mysqli_fetch_array($result)) {
                
                $res1[$i++] = array("hotelName" => $row['hotel_name'],
                                   "address" => $row['place'],
                                   "star" => $row['star'],
                                   "score" => $row['score'],
                                   "lowestPrice" => $row['lowest_price'],
                                   "replyNum" => $row['hot'],
                                    "hotelId"=>$row['hotel_id']);

                $hotel_id=$row['hotel_id'];
                $result2=mysqli_query($myConn,"select * from room WHERE hotel_id='$hotel_id' and user_discount<100 and amount >0");
                while ($row2=mysqli_fetch_array($result2)){
                    $price=$row2['price'];$user_discount=$row2['user_discount'];
                    $nowPrice=$price*$user_discount/100;
                    $res2[$j++]=array(
                        "roomId"=>$row2['room_id'],
                        "hotelName" => $row['hotel_name'],
                        "address" => $row['place'],
                        "star" => $row['star'],
                        "score" => $row['score'],
                        "roomType"=>$row2['room_type'],
                        "userDiscount"=>$user_discount,
                        "nowPrice"=>$nowPrice
                    );
                }


            }
            
            $tips = array(
                          "code" => "0",
                          "msg" => "查询完成 ",
                          "res" =>array(
                                "token" => $_SESSION['token'],
                                "hotel_list" => $res1,
                                "discount_hotel_list"=>$res2
                            )
                          );
            echo json_encode($tips);
            connection::freeConn();
        }
        
    }
    
    $place = $_POST['place'];
    $star_option = $_POST['star_option'];
    $price_option = $_POST['price_option'];
    $score_option = $_POST['score_option'];
    checkStringSafety($place);
  
    query::queryHotels($place,$star_option,$price_option,$score_option);
    
    ?>