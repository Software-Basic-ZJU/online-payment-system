<?php
	include("functions.php");
	$conn=connectDB();
	$id=$_GET['id'];
	
	$sql1=mysqli_query($conn,"delete from commodity where commodity_type='flight' and original_id=$id");
	$sql2=mysqli_query($conn,"delete from flight where flight_id='$id'");
	
	if($sql1==true&&$sql2==true){
		echo "<script language=javascript>alert('删除成功！');history.back();location.href='flight_query.php';</script>";
	}
	else{
		echo "<script language=javascript>alert('删除失败！');history.back();window.opener.location.reload();</script>";
		exit;
	}
?>