<?php
	include("functions.php");
	$conn=connectDB();
	$id=$_POST['id'];
	$num=$_POST['num'];  
	$name=$_POST['name'];  
	$bcity=$_POST['bcity'];  
	$ecity=$_POST['ecity'];  
	$btime=$_POST['btime'];  
	$etime=$_POST['etime'];  
	$bair=$_POST['bair'];  
	$eair=$_POST['eair'];  
	$if_stop=$_POST['if_stop'];  
	$user=$_POST['user'];  
	$vip=$_POST['vip'];  
	$price=$_POST['price'];  
	$amount=$_POST['amount']; 
	if(!($num&&$name&&$bcity&&$ecity&&$btime&&$etime&&$bair&&$eair&&$user&&$vip&&$price&&$amount)){
		echo "<script language=javascript>alert('信息不完整或有错误，请重填！');history.back();window.opener.location.reload();</script>";
		exit;
	}
	$sql=mysqli_query($conn,"update flight set flight_number='$num',airline_name='$name',begin_city='$bcity',end_city='$ecity',begin_time='$btime',end_time='$etime',begin_airport='$bair',end_airport='$eair',if_stop='$if_stop',user_discount='$user',vip_discount='$vip',price='$price',amount='$amount' where flight_id='$id'");
	if($sql==true){
		echo "<script language=javascript>alert('修改成功！');history.back();location.href='flight_query.php';</script>";
	}
	else{
		echo "<script language=javascript>alert('修改失败！');history.back();window.opener.location.reload();</script>";
		exit;
	}
?>