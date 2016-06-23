<?php 
include("TEMPLATE.php");
?>
<html>
<head>
<meta  http-equiv="Content-Type" content="text/html; charset="utf-8"/>
<title>航班信息修改</title>
</head>

<?php
	$id=$_GET['id'];
	$result=mysqli_query($conn,"select * from flight where flight_id='$id'");
	$arr=mysqli_fetch_assoc($result);
	$num=$arr['flight_number'];  
	$name=$arr['airline_name'];  
	$bcity=$arr['begin_city'];  
	$ecity=$arr['end_city'];  
	$btime=$arr['begin_time'];  
	$etime=$arr['end_time'];  
	$bair=$arr['begin_airport'];  
	$eair=$arr['end_airport'];  
	$if_stop=$arr['if_stop'];  
	$user=$arr['user_discount'];  
	$vip=$arr['vip_discount'];  
	$price=$arr['price'];  
	$amount=$arr['amount']; 
?>

<div class="class2">
	<fieldset>
		<legend>修改航班信息</legend>
<form method="post" action="flight_changeok.php">
航班编号: <input type="text" name="id" value="<?php echo "$id"?>" readonly><br>
航班号: <input type="text" name="num" value="<?php echo "$num"?>"><br>
航空公司:<select name="name">
                <option value="china" selected="selected">中国航空</option>
                <option value="east">东方航空</option>
                <option value="south">南方航空</option>
                <option value="west">西方航空</option>
                <option value="north">北方航空</option>
            </select><br>
起飞城市: <input type="text" name="bcity" value="<?php echo "$bcity"?>"><br>
目的地城市: <input type="text" name="ecity" value="<?php echo "$ecity"?>"><br>
起飞时间: <input type="text" name="btime" value="<?php echo "$btime"?>"><br>
到达时间: <input type="text" name="etime" value="<?php echo "$etime"?>"><br>
起飞机场:<input type="text" name="bair" value="<?php echo "$bair"?>"><br>
目的地机场:<input type="text" name="eair" value="<?php echo "$eair"?>"><br>
中转城市:<input type="text" name="if_stop" value="<?php echo "$if_stop"?>"><br>
用户折扣:<input type="text" name="user" value="<?php echo "$user"?>"><br>
VIP折扣:<input type="text" name="vip" value="<?php echo "$vip"?>"><br>
原价:<input type="text" name="price" value="<?php echo "$price"?>"><br>
数量:<input type="text" name="amount" value="<?php echo "$amount"?>"><br>
<input type="submit" value="确认修改">
</form>
		</fieldset>
</div>
<body>
  
</body>
</html>

