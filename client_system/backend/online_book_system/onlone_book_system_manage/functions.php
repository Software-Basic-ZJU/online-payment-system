<?php
/**
 * Created by PhpStorm.
 * User: achao_zju
 * Date: 6/4/16
 * Time: 8:10 PM
 */
require_once  'config.php';
function connectDB(){
    $conn=mysqli_connect(MYSQL_HOST,MYSOL_USER,MYSQL_PW);
    if (!$conn){
        die('can not connect');
    }
    else{
     //   echo "yeah";
    }
    mysqli_select_db($conn,"online_book_system");
    return  $conn;
}
?>