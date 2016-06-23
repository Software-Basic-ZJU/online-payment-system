<?php
	include("functions.php");
	$conn=connectDB();
	$rid=$_POST['rid'];
	$id=$_POST['id'];
	$type=$_POST['type'];
	$user=$_POST['user'];
	$vip=$_POST['vip'];
	$price=$_POST['price'];
	$amount=$_POST['amount'];

	if(!($type&&$user&&$vip&&$price&&$amount)){
		echo "<script language=javascript>alert('信息不完整或有错误，请重填！');history.back();window.opener.location.reload();</script>";
		exit;
	}
	$sql=mysqli_query($conn,"update room set room_type='$type',user_discount='$user',vip_discount='$vip',price='$price',amount='$amount' where room_id='$rid'");
	if($sql==true){

		$result=mysqli_query($conn,"select lowest_price from hotel where hotel_id='$id'");
        $arr=mysqli_fetch_assoc($result);
        $lowest_price=$arr['lowest_price'];
        if($price<$lowest_price){
            mysqli_query($conn,"update hotel set lowest_price=$price where hotel_id='$id'");
        }
        echo "<script language=javascript>alert('修改成功！');history.back();location.href='room_query.php';</script>";
	}
	else{
		echo "<script language=javascript>alert('修改失败！');history.back();window.opener.location.reload();</script>";
		exit;
	}
?>