<?php 
include("TEMPLATE.php");
?>
<html>
<head>
<meta  http-equiv="Content-Type" content="text/html; charset="utf-8"/>
<title>房间查询</title>
</head>

<div class="class0">注：未输入信息的属性默认为对该属性不做限制。</div>
<div class="class1">
<fieldset>
	<legend>查询酒店房型</legend>
<form method="post" action="room_query.php">
酒店编号: <input type="text" name="id"><br>
房间编号: <input type="text" name="rid"><br>
房型: <input type="text" name="type"><br><br>
<input type="submit" name="sub" value="开始查询">
</form>
</fieldset></div>
<div class="class3">
<table border="1" width=60%>
	<tr><td>房间编号</td><td>酒店编号</td><td>房型</td><td>用户折扣</td><td>VIP折扣</td><td>原价</td><td>数量</td><td>操作</td></tr>
	<?php
	$id=$_POST['id'];
	$rid=$_POST['rid'];
	$type=$_POST['type'];

	if($id) $a="and hotel_id='".$id."'";
	else $a='';
	if($rid) $b="and hotel_name='".$rid."'";
	else $b='';
	if($type) $c="and place='".$type."'";
	else $c='';

	$result=mysqli_query($conn,"select * from room where 1 $a $b $c");
	$count=@mysqli_num_rows($result);
	if (!$_POST["sub"]) $count=0;
	for($i=0;$i<$count;$i++){
		$arr=mysqli_fetch_assoc($result);
		$rid=$arr['room_id'];
		$id=$arr['hotel_id'];
		$type=$arr['room_type'];
		$user=$arr['user_discount'];
		$vip=$arr['vip_discount'];
		$price=$arr['price'];
		$amount=$arr['amount'];
		echo "<tr><td>$rid</td><td>$id</td><td>$type</td><td>$user</td><td>$vip</td><td>$price</td><td>$amount</td>";
		?>
		<td><a href="room_change.php?id=<?php echo $rid;?>">修改</a>
			<a href="room_delete.php?id=<?php echo $rid;?>">删除</a></td></tr>
		<?php
	}
	?>
</table>
</div>
<body>
  
</body>
</html>

