<?php
	include("functions.php");
	$conn=connectDB();
	$rid=$_GET['rid'];
	
	$result=mysqli_query($conn,"select hotel_id from room where room_id='$rid'");
	$arr=mysqli_fetch_assoc($result);
	$id=$arr['hotel_id'];
	$sql1=mysqli_query($conn,"delete from commodity where commodity_type='room' and original_id='$rid'");
	$sql2=mysqli_query($conn,"delete from room where room_id=$rid");
	
	if($sql1==true&&$sql2==true){
		$res=mysqli_query($conn,"select * from room where hotel_id='$id'");
		$count=@mysqli_num_rows($res);
		$lprice=32768;
		for($i=0;$i<$count;$i++){
			$a=mysqli_fetch_assoc($res);
			$price=$a['price'];
			if($price<$lprice) $lprice=$price;
		}
		mysqli_query($conn,"update hotel set lowest_price='$lprice' where hotel_id='$id'");
		echo "<script language=javascript>alert('删除成功！');history.back();location.href='room_query.php';</script>";
	}
	else{
		echo "<script language=javascript>alert('删除失败！');history.back();window.opener.location.reload();</script>";
		exit;
	}
?>