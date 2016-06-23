<?php
    error_reporting(0);
	
	$p=$_GET["p"];
    $q=$_GET["q"];
    $r=$_GET["r"];
    $s=$_GET["s"];
    //echo $p;echo "-";
    //echo $q;echo "-";
    //echo $r;echo "-";
    //echo $s;
	$conn = mysqli_connect("127.0.0.1", "root", "root","online_pay");
    //$conn = mysql_connect('localhost', 'root', '064016');
    if (!$conn){
        die('Could not connect: ' . mysql_error());}
    //mysql_select_db("library", $conn);
    if($q == "null")
    {       
        if($r == "递增"){
            $sql="SELECT * FROM order_records ORDER BY $s";}
        else if($r == "递减"){
            $sql="SELECT * FROM order_records ORDER BY $s DESC";}
        else{
            $sql="SELECT * FROM order_records";}
    }
    else
    {
        if($r == "递增"){
            if($p == "null"){
                $sql="SELECT * FROM order_records WHERE order_id like '%$q%' or goods_id like '%$q%' or 
                amount like '%$q%' or price like '%$q%' or price like '%$q%' or seller like '%$q%' or state like '%$q%' or 
                start_time like '%$q%' or close_time like '%$q%' ORDER BY $s";}
            else{
                $sql="SELECT * FROM order_records WHERE $p like '%$q%' ORDER BY $s";}}
        else if($r == "递减"){
            if($p == "null"){
                $sql="SELECT * FROM order_records WHERE order_id like '%$q%' or goods_id like '%$q%' or 
                amount like '%$q%' or price like '%$q%' or price like '%$q%' or seller like '%$q%' or state like '%$q%' or 
                start_time like '%$q%' or close_time like '%$q%' ORDER BY $s DESC";}
            else{
                $sql="SELECT * FROM order_records WHERE $p like '%$q%' ORDER BY $s DESC";}}
        else{
            if($p == "null"){
                $sql="SELECT * FROM order_records WHERE order_id like '%$q%' or goods_id like '%$q%' or 
                amount like '%$q%' or price like '%$q%' or price like '%$q%' or seller like '%$q%' or state like '%$q%' or 
                start_time like '%$q%' or close_time like '%$q%'";}
            else{
                $sql="SELECT * FROM order_records WHERE $p like '%$q%'";}}
    }

    $result = mysqli_query($conn,$sql);

    if($result){
        if($row=mysqli_num_rows($result)){
           echo "<table border=\"1\">";
           while($row = mysqli_fetch_array($result)){
               echo "<tr>";
               echo "<td class=\"col1\">" . $row['order_id'] . "</td>";
               echo "<td class=\"col1\">" . $row['goods_id'] . "</td>";
               echo "<td class=\"col1\">" . $row['amount'] . "</td>";
               echo "<td class=\"col1\">" . $row['price'] . "</td>";
               echo "<td class=\"col1\">" . $row['buyer'] . "</td>";
               echo "<td class=\"col1\">" . $row['seller'] . "</td>";
               echo "<td class=\"col1\">" . $row['state'] . "</td>";
               echo "<td class=\"col2\">" . $row['start_time'] . "</td>";
               echo "<td class=\"col2\">" . $row['close_time'] . "</td>";
               echo "</tr>";}
           echo "</table>";}
        else{
            echo "没有符合的结果!";}}
    else{
        echo "数据查询出错!";  }
       
    mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>search.php</title>
    </head>
    <body>
        
    </body>
</html>
