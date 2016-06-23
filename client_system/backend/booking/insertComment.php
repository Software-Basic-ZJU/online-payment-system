<?php
    
    session_start();
    
    header('Content-type: text/json');
    require_once '../order/connection.php';
    require_once '../order/checkStringSafety.php';
    require_once '../order/checkPasswordSafety.php';
    require_once 'insertComment.php';
    require_once '../pam/verify_token.php';
    
    verify_token($_POST['token']);
    
    class insert{
        public static function insertComment($user_name,$room_id,$score,$comment,$hotel_id)
        {
            $comment=checkStringSafety($comment);
            $myConn = connection::getConn();
            if ($myConn) {
            } else {
                $tips = array("code" => "1", "msg" => "连接失败", "res" => array("token" => $_SESSION['token']));
                echo json_encode($tips);
                exit();
            }
            
            $sql="INSERT INTO comment ( user_name, room_id,score,comment)
            VALUES
            ('$user_name','$room_id','$score','$comment')";
            
            
            
            if (!$myConn->query($sql))
            {
                $tips = array("code" => "1", "msg" => "插入评论失败", "res" => array("token" => $_SESSION['token']));
                echo json_encode($tips);
                exit();
            }
            $myConn->query("update hotel set hot=hot+1 where hotel_id='$hotel_id'");
            $tips = array(
                          "code" => "0",
                          "msg" => "插入评论完成 ",
                          "res" => array(
                              "token" =>$_SESSION['token'])
                );
            echo json_encode($tips);
            connection::freeConn();
        }
        
    }
    
    $user_name = $_SESSION['user_name'];
    $hotel_id=$_POST['hotel_id'];
    $room_id = $_POST['room_id'];
    $score = $_POST['score'];
    $comment = $_POST['comment'];
    checkStringSafety($user_name);
    checkStringSafety($comment);
    insert::insertComment($user_name,$room_id,$score,$comment,$hotel_id);
    
    ?>