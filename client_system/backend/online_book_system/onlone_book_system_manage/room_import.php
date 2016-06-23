<?php include("TEMPLATE.php");?>
<!DOCTYPE html>
<html>
<head>
    <meta  http-equiv="Content-Type" content="text/html; charset="utf-8"/>

    <title>添加酒店房型信息</title>
</head>
<body>

<?php  //after add a hotel
$add="fail";
if($_POST["sub1"]){
    if(empty($_POST["hotel_id"]))
        $hotel_idError = "未输入酒店ID";
    else
        $hotel_id = $_POST['hotel_id'];
    if(empty($_POST["room_type"]))
        $room_typeError = "未输入房间类型";
    else
        $room_type= $_POST["room_type"];
    if(empty($_POST["user_discount"]))
        $user_discountError = "未输入用户折扣";
    else
        $user_discount = $_POST['user_discount'];
    if(empty($_POST["vip_discount"]))
        $vip_discountError = "未输入VIP折扣";
    else
        $vip_discount= $_POST["vip_discount"];
    if(empty($_POST["price"]))
        $priceError = "未输入原价";
    else
        $price= $_POST["price"];
    if(empty($_POST["amount"]))
        $amountError = "未输入余量";
    else
        $amount= $_POST["amount"];

    if(!empty($_POST["hotel_id"]) && !empty($_POST['room_type'])&&!empty($_POST['user_discount'])&&!empty($_POST['vip_discount'])&&!empty($_POST['price'])&&!empty($_POST['amount'])) {
        mysqli_query($conn,"INSERT INTO room(hotel_id,room_type,user_discount,vip_discount,price,amount) VALUES ($hotel_id,'$room_type',$user_discount,$vip_discount,$price,$amount)");
        $result=mysqli_query($conn,"select lowest_price from hotel WHERE  hotel_id=$hotel_id");
        $result_arr=mysqli_fetch_assoc($result);
        $lowest_price=$result_arr['lowest_price'];
        if ($price<$lowest_price){
            mysqli_query($conn,"update hotel set lowest_price=$price WHERE hotel_id=$hotel_id");
        }
        $result=mysqli_query($conn,"select MAX(room_id) as room_id from room");
        $result_arr=mysqli_fetch_assoc($result);
        $room_id=$result_arr['room_id'];
        $commodity_type='room';
        mysqli_query($conn,"INSERT INTO commodity(commodity_type,original_id) VALUES ('$commodity_type',$room_id)");
        $add="success";

    }

}?>

<div class="right"> <!--before add a hotel-->
    <h4>添加单个房间信息</h4>
    <form method="post" action="room_import.php">
        <fieldset>
            <legend>输入房间信息:</legend>
            酒店ID:<br>
            <input type="text" name="hotel_id" maxlength="20"><br>
            <span class="error">* <?php echo $hotel_idError; ?></span><br><br>
            房间类型:<br>
            <input type="text" name="room_type" maxlength="20"  ><br>
            <span class="error">* <?php echo $room_typeError; ?></span><br><br>
             用户折扣:<br>
            <input type="text" name="user_discount" maxlength="3"  ><br>
            <span class="error">* <?php echo $user_discountError; ?></span><br><br>
            VIP折扣:<br>
            <input type="text" name="vip_discount" maxlength="3"  ><br>
            <span class="error">* <?php echo $vip_discountError; ?></span><br><br>
            原价:<br>
            <input type="text" name="price" maxlength="4"  ><br>
            <span class="error">* <?php echo $priceError; ?></span><br><br>
            余量:<br>
            <input type="text" name="amount" maxlength="4"  ><br>
            <span class="error">* <?php echo $amountError; ?></span><br><br>
            <input type="submit" name="sub1" value="提交">
            <?php if($add=="success")
                echo  "<br><span class=\"error\">成功添加房间信息</span>";
            ?>
        </fieldset>
    </form>
    <span class="error"><?php echo $errorInfo; ?></span>
</div>

<div class="right2"> <!-- add hotels -->
    <h2 align="center">批量添加房间信息</h2>
    <fieldset>
        <legend>确认信息</legend>
        <h3>从文件"rooms_import"中读取到以下内容, 请确认是否提交:</h3>

    <textarea rows="11" cols="110" readonly>
        <?php  $file = fopen("rooms_import", "r") or die("Unable to open file!");
        while (!feof($file)){echo  fgets($file);
        }
        ?>
   </textarea>
        <form method="post" action="room_import.php">
            <input type="submit" name="sub2" value="提交">
        </form><br>
    <textarea rows="11" cols="110" readonly>
    <?php
    echo "\n";
    if($_POST["sub2"]){
        echo "正在提交数据...\n";
        $file = fopen("rooms_import", "r") or die("Unable to open file!");
        $sample = "insert into room(hotel_id,room_type,user_discount,vip_discount,price,amount) values";
        $i = 0;
        while(!feof($file)){
            $query = $sample . fgets($file);
            if($query == $sample)
                continue;
            echo $query;
            $i++;
            $result=mysqli_query($conn,$query);
            if(!$result){
                echo "插入第 $i 条数据时遇到错误: " . $connect->error . "\n";
                continue;
            }
            else
                echo "插入第 $i 条数据成功!\n";
            $result=mysqli_query($conn,"select * from room WHERE  room_id = (SELECT  max(room_id) FROM room)");
            $result_arr=mysqli_fetch_assoc($result);
            $room_id=$result_arr['room_id'];
            $hotel_id=$result_arr['hotel_id'];
            $price=$result_arr['price'];
            $result=mysqli_query($conn,"select lowest_price from hotel WHERE  hotel_id=$hotel_id");
            $result_arr=mysqli_fetch_assoc($result);
            $lowest_price=$result_arr['lowest_price'];
            if ($price<$lowest_price){
                mysqli_query($conn,"update hotel set lowest_price=$price WHERE hotel_id=$hotel_id");
            }
            $commodity_type='room';
            mysqli_query($conn,"INSERT INTO commodity(commodity_type,original_id) VALUES ('$commodity_type',$room_id)");
        }
        fclose($file);
    }
    ?>
    </textarea>
    </fieldset>
</div>

</body>
</html>

