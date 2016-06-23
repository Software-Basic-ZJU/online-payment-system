<?php 
include("TEMPLATE.php");
?>
<html>
<head>
<meta  http-equiv="Content-Type" content="text/html; charset="utf-8"/>
<title>酒店查询</title>
    <style>
        div.class4{
            position: absolute;
            top: 580px;
            left: 220px;
        }
    </style>
</head>
<div class="class0">注：未输入信息的属性默认为对该属性不做限制。</div>
<div class="class1">
 
<form method="post" action="hotel_query.php">
	<fieldset>
		<legend>查询酒店</legend>
酒店编号: <input type="text" name="id"><br>
酒店名: <input type="text" name="name"><br>
地点: <input type="text" name="place"><br>
星级:<br> <select name="star">
				<option value="" selected="selected"> </option>
                <option value="5">五星级/豪华</option>
                <option value="4">四星级/高档</option>
                <option value="3">三星级/舒适</option>
                <option value="2">二星级/经济</option>
                <option value="1">一星级/经济</option>
            </select><br><br>
<input type="submit" name="sub1" value="开始查询">
 	</fieldset>
</form></div>
<div class="class4">
<table border="1" width=100%>
	<tr><td>酒店编号</td><td>酒店名</td><td>地点</td><td>星级</td><td>人气</td><td>评分</td><td>最低价</td><td>操作</td></tr>
	<?php
	$id=$_POST['id'];
	$name=$_POST['name'];
	$place=$_POST['place'];
	$star=$_POST['star'];

	if($id) $a="and hotel_id='".$id."'";
	else $a='';
	if($name) $b="and hotel_name='".$name."'";
	else $b='';
	if($place) $c="and place='".$place."'";
	else $c='';
	if($star) $d="and star='".$star."'";
	else $d='';

	$result=mysqli_query($conn,"select * from hotel where 1 $a $b $c $d");
	$count=@mysqli_num_rows($result);
    if (!$_POST["sub1"]) $count=0;
	for($i=0;$i<$count;$i++){
		$arr=mysqli_fetch_assoc($result);
		$id=$arr['hotel_id'];
		$name=$arr['hotel_name'];
		$place=$arr['place'];
		$star=$arr['star'];
		$hot=$arr['hot'];
		$score=$arr['score'];
		$lprice=$arr['lowest_price'];
		echo "<tr><td>$id</td><td>$name</td><td>$place</td><td>$star</td><td>$hot</td><td>$score</td><td>$lprice</td>";
		?>
		<td><a href="hotel_change.php?id=<?php echo $id;?>">修改</a>
			<a href="hotel_delete.php?id=<?php echo $id;?>">删除</a></td></tr>
		<?php
	}
	?>
</table></div>



<body>
  
</body>
</html>

