<?php

header('Content-type: application/json');
session_start();

include "_include.php";

//Verify token
verify_token($_POST['token']);

// Echo information
$result = array(
    "code" => 0,
    "msg" => "token 可以销毁",
    "res" => array()
);
echo json_encode($result);

?>


