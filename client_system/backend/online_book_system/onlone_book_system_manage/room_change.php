<?php 
include("TEMPLATE.php");
?>
<html>
<head>
<meta  http-equiv="Content-Type" content="text/html; charset="utf-8"/>
<title>酒店房间信息修改</title>
</head>
<?php
	$rid=$_GET['id'];
	$result=mysqli_query($conn,"select * from room where room_id='$rid'");
	$arr=mysqli_fetch_assoc($result);
	$id=$arr['hotel_id'];
	$type=$arr['room_type'];
	$user=$arr['user_discount'];
	$vip=$arr['vip_discount'];
	$price=$arr['price'];
	$amount=$arr['amount'];
?>

<div class="class2">
	<fieldset>
		<legend>修改房间信息</legend>
<form method="post" action="room_changeok.php">
酒店编号:<input type="text" name="id" value="<?php echo "$id"?>" readonly><br>
房间编号: <input type="text" name="rid" value="<?php echo "$rid"?>" readonly><br>
房型: <input type="text" name="type" value="<?php echo "$type"?>"><br>
用户折扣：<input type="text" name="user" value="<?php echo "$user"?>"><br>
VIP折扣：<input type="text" name="vip" value="<?php echo "$vip"?>"><br>
原价：<input type="text" name="price" value="<?php echo "$price"?>"><br>
数量：<input type="text" name="amount" value="<?php echo "$amount"?>"><br><br>
<input type="submit" value="确认修改">
</form>
		</fieldset>
</div>

<body>
  
</body>
</html>




