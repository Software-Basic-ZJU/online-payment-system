<?php 
include("TEMPLATE.php");
?>
<html>
<head>
<meta  http-equiv="Content-Type" content="text/html; charset="utf-8"/>
<title>航班查询</title>
	<style>
		div.class4{
			position: absolute;
			top: 800px;
			left: 10px;
		}
	</style>
</head>

<div class="class0">注：未输入信息的属性默认为对该属性不做限制。</div>
<div class="class1">
<fieldset>
	<legend>查询航班</legend>
<form method="post" action="flight_query.php">
航班编号: <input type="text" name="id"><br>
航班号: <input type="text" name="num"><br>
航空公司:<br><select name="name">
				<option value="" selected="selected"> </option>
                <option value="china">中国航空</option>
                <option value="east">东方航空</option>
                <option value="south">南方航空</option>
                <option value="west">西方航空</option>
                <option value="north">北方航空</option>
            </select><br>
起飞城市: <input type="text" name="bcity"><br>
目的地城市: <input type="text" name="ecity"><br>
起飞机场:<input type="text" name="bair"><br>
目的地机场:<input type="text" name="eair"><br>
中转城市:<input type="text" name="if_stop"><br><br>
<input type="submit" name="sub" value="开始查询">
</form></div>
<div class="class4">
	<table border="1" width=100%>
		<tr><td>航班编号</td><td>航班号</td><td>航空公司</td><td>起飞城市</td><td>目的地城市</td><td>起飞时间</td><td>到达时间</td><td>起飞机场</td><td>目的地机场</td><td>中转城市</td><td>用户折扣</td><td>VIP折扣</td><td>原价</td><td>数量</td><td>操作</td></tr>
		<?php
		$id=$_POST['id'];
		$num=$_POST['num'];
		$name=$_POST['name'];
		$bcity=$_POST['bcity'];
		$ecity=$_POST['ecity'];
		$bair=$_POST['bair'];
		$eair=$_POST['eair'];
		$if_stop=$_POST['if_stop'];

		if($id) $a="and flight_id='".$id."'";
		else $a='';
		if($num) $b="and flight_number='".$num."'";
		else $b='';
		if($name) $c="and airline_name='".$name."'";
		else $c='';
		if($bcity) $d="and begin_city='".$bcity."'";
		else $d='';
		if($ecity) $e="and end_city='".$ecity."'";
		else $e='';
		if($bair) $f="and begin_airport='".$bair."'";
		else $f='';
		if($eair) $g="and end_airport='".$eair."'";
		else $g='';
		if($if_stop) $h="and if_stop='".$if_stop."'";
		else $h='';

		$result=mysqli_query($conn,"select * from flight where 1 $a $b $c $d $e $f $g $h");
		$count=@mysqli_num_rows($result);
		if (!$_POST["sub"]) $count=0;
		for($i=0;$i<$count;$i++){
			$arr=mysqli_fetch_assoc($result);
			$id=$arr['flight_id'];
			$num=$arr['flight_number'];
			$name=$arr['airline_name'];
			$bcity=$arr['begin_city'];
			$ecity=$arr['end_city'];
			$btime=$arr['begin_time'];
			$etime=$arr['end_time'];
			$bair=$arr['begin_airport'];
			$eair=$arr['end_airport'];
			$if_stop=$arr['if_stop'];
			if($if_stop==NULL) $if_stop="无";
			$user=$arr['user_discount'];
			$vip=$arr['vip_discount'];
			$price=$arr['price'];
			$amount=$arr['amount'];

			echo "<tr><td>$id</td><td>$num</td><td>$name</td><td>$bcity</td><td>$ecity</td><td>$btime</td><td>$etime</td><td>$bair</td><td>$eair</td><td>$if_stop</td><td>$user</td><td>$vip</td><td>$price</td><td>$amount</td>";
			?>
			<td><a href="flight_change.php?id=<?php echo $id;?>">修改</a>
				<a href="flight_delete.php?id=<?php echo $id;?>">删除</a></td></tr>
			<?php
		}
		?>
	</table></div>



<body>
  
</body>
</html>

