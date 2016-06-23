<?php
	include("functions.php");
	$conn=connectDB();
	$id=$_POST['id'];
	$name=$_POST['name'];
	$place=$_POST['place'];
	$star=$_POST['star'];
	if(!($name&&$place)){
		echo "<script language=javascript>alert('信息不完整或有错误，请重填！');history.back();window.opener.location.reload();</script>";
		exit;
	}
	$sql=mysqli_query($conn,"update hotel set hotel_name='$name',place='$place',star='$star' where hotel_id='$id'");
	if($sql==true){
		echo "<script language=javascript>alert('修改成功！');history.back();location.href='hotel_query.php';</script>";
	}
	else{
		echo "<script language=javascript>alert('修改失败！');history.back();window.opener.location.reload();</script>";
		exit;
	}
?>