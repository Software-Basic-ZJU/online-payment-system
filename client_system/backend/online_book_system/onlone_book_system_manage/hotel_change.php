<?php 
include("TEMPLATE.php");
?>
<html>
<head>
<meta  http-equiv="Content-Type" content="text/html; charset="utf-8"/>
<title>酒店信息修改</title>
</head>

<?php
	$id=$_GET['id'];
	$result=mysqli_query($conn,"select * from hotel where hotel_id='$id'");
	$arr=mysqli_fetch_assoc($result);
	$name=$arr['hotel_name'];
	$place=$arr['place'];
?>

<div class="class2">
	<fieldset>
		<legend>修改酒店信息</legend>
<form method="post" action="hotel_changeok.php">
酒店编号:<input type="text" name="id" value="<?php echo "$id"?>" readonly><br>
酒店名: <input type="text" name="name" value="<?php echo "$name"?>"><br>
地点: <input type="text" name="place" value="<?php echo "$place"?>"><br>
星级:<br> <select name="star">
				<option value="5" selected="selected">五星级/豪华</option>
                <option value="4">四星级/高档</option>
                <option value="3">三星级/舒适</option>
                <option value="2">二星级/经济</option>
                <option value="1">一星级/经济</option>
            </select><br><br>
<input type="submit" value="确认修改">
</form>
		</fieldset>
</div>
<body>
  
</body>
</html>

