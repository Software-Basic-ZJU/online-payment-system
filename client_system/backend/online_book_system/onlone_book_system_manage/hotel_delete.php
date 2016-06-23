<?php
	include("functions.php");
	$conn=connectDB();
	$id=$_GET['id'];
	
	$result=mysqli_query($conn,"select room_id from room where hotel_id='$id'");
	$count=@mysqli_num_rows($result);
	for($i=0;$i<$count;$i++){
		$arr=mysqli_fetch_assoc($result);
		$roomid=$arr['room_id'];
		mysqli_query($conn,"delete from commodity where commodity_type='room' and original_id='$roomid'");
	}
	$sql1=mysqli_query($conn,"delete from room where hotel_id=$id");
	$sql2=mysqli_query($conn,"delete from hotel where hotel_id=$id");
	
	if($sql1==true&&$sql2==true){
		echo "<script language=javascript>alert('删除成功！');history.back();location.href='hotel_query.php';</script>";
	}
	else{
		echo "<script language=javascript>alert('删除失败！');history.back();window.opener.location.reload();</script>";
		exit;
	}
?>