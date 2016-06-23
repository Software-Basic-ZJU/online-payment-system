<?php
session_start();

header('Content-type: application/json');
require_once '../order/connection.php';
require_once '../order/checkStringSafety.php';
require_once '../order/checkPasswordSafety.php';
include '../pam/verify_token.php';

verify_token($_POST['token']);

$hotel_id=$_POST['hotel_id'];
$myConn = connection::getConn();
if ($myConn) {
}
else {
    $tips = array("code" => "1", "msg" => "连接失败", "res" => array("token" => $_SESSION['token']));
    echo json_encode($tips);
    exit();
}

$result=mysqli_query($myConn,"select room_id  from room WHERE hotel_id=$hotel_id");
$dataCount=mysqli_num_rows($result);
if ($dataCount==0){
    $result = array(
        "code" => 0,
        "msg" => "该酒店目前还没有评价信息",
        "res" => array(
            'token' => $_SESSION['token'],
            'commentList'=>array()
        )
    );
    echo json_encode($result);
    exit;
}
$k=0;
for($i=0;$i<$dataCount;$i++){
    $result_arr=mysqli_fetch_assoc($result);
    $room_id=$result_arr['room_id'];
    $result2=mysqli_query($myConn,"select user_name,score,comment from comment WHERE room_id=$room_id");
    $dataCount2=mysqli_num_rows($result2);

    for ($j=0;$j<$dataCount2;$j++){
        $result_arr2=mysqli_fetch_assoc($result2);
        $user_name=$result_arr2['user_name'];
        $comment=$result_arr2['comment'];
        $score=$result_arr2['score'];

        $result3=mysqli_query($myConn,"select room_type from room WHERE room_id=$room_id");
        $result_arr3=mysqli_fetch_assoc($result3);
        $room_type=$result_arr3['room_type'];

        $res2[$k++]=array(
            "userName"=>$user_name,
            "comment"=>$comment,
            "roomType"=>$room_type,
            "score"=>$score);


    }

}
if ($k==0){
    $res = array(
        "code" => 0,
        "msg" => "该酒店目前还没有评价信息",
        "res" => array(
            'token' => $_SESSION['token'],
            'commentList'=>array()
        )
    );
    echo json_encode($res);
    exit;

}
else {
    $res = array(
        "code" => 0,
        "msg" => "成功查看评价列表",
        "res" => array(
            'commentList'=>$res2,
           'token' => $_SESSION['token']

        )
    );
    echo json_encode($res);
    exit;
}
?>