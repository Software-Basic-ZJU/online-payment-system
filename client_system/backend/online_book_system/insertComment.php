<?php
    
    session_start();
    
    header('Content-type: text/json');
    require_once 'connection.php';
    require_once 'checkStringSafety.php';
    require_once 'checkPasswordSafety.php';
    require_once '../pam/insertComment.php';
    
    verify_token($_POST['token']);
    
    class insert{
        public static function insertComment($user_name,$room_id,$score,$comment)
        {
            $myConn = connection::getConn();
            if ($myConn) {
            } else {
                $tips = array("code" => "1", "msg" => "连接失败", "res" => array("token" => $_SESSION['token']));
                echo json_encode($tips);
                exit();
            }
           
           
            
            $sql="INSERT INTO comment ( user_name, room_id,score,comment)
            VALUES
            ('$_POST[user_name]','$_POST[room_id]','$_POST[score]','$_POST[comment]')";
            
            
            
            if (!$mysqli->query($sql))
            {
                $tips = array("code" => "1", "msg" => "插入评论失败", "res" => array("token" => $_SESSION['token']));
                echo json_encode($tips);
                exit();
            }
           
            $tips = array(
                          "code" => "0",
                          "msg" => "插入评论完成 ",
                          "res" => array("token =>$token")
                                        );
            echo json_encode($tips);
            connection::freeConn();
        }
        
    }
    
    $user_name = $_POST['user_name'];
    $room_id = $_POST['room_id'];
    $score = $_POST['score'];
    $comment = $_POST['comment'];
    checkStringSafety($user_name);
    checkStringSafety($comment);
    insert::insertComment($user_name,$room_id,$score,$comment);
    
    ?>