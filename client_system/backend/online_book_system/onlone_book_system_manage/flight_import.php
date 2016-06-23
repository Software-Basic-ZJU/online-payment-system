<?php include("TEMPLATE.php");?>
<!DOCTYPE html>
<html>
<head>
    <meta  http-equiv="Content-Type" content="text/html; charset="utf-8"/>

    <title>添加航班信息</title>
    
</head>
<body>

<?php  //after add a hotel
$add="fail";
if($_POST["sub1"]){
    if(empty($_POST["flight_number"]))  $flight_numberError = "未输入航班号";
    else  $flight_number = $_POST['flight_number'];
    $airline_name = $_POST['airline_name'];

    if(empty($_POST["begin_city"]))  $begin_cityError = "未输入起飞城市";
    else  $begin_city = $_POST['begin_city'];
    if(empty($_POST["end_city"]))  $end_cityError = "未输入目的地城市";
    else  $end_city = $_POST['end_city'];

    if(empty($_POST["begin_time"]))  $begin_timeError = "未输入起飞时间";
    else  $begin_time = $_POST['begin_time'];
    if(empty($_POST["end_time"]))  $end_timeError = "未输入到达时间";
    else  $end_time = $_POST['end_time'];
    if(empty($_POST["begin_airport"]))  $begin_airportError = "未输入起飞机场";
    else  $begin_airport = $_POST['begin_airport'];
    if(empty($_POST["end_airport"]))  $end_airportError = "未输入目的地机场";
    else  $end_airport = $_POST['end_airport'];

    if(empty($_POST["end_city"]))  $if_stopError = "未输入有效信息";
    else  $end_city = $_POST['end_city'];

    if(empty($_POST["user_discount"]))  $user_discountError = "未输入用户折扣";
    else  $user_discount = $_POST['user_discount'];
    if(empty($_POST["vip_discount"]))  $vip_diScountError = "未输入VIP折扣";
    else  $vip_discount = $_POST['vip_discount'];

    if(empty($_POST["price"]))  $priceError = "未输入原价";
    else  $price = $_POST['price'];
    if(empty($_POST["amount"]))  $amountError = "未输入机票余量";
    else  $amount = $_POST['amount'];

    if(!empty($_POST["flight_number"]) && !empty($_POST['begin_city'])&& !empty($_POST['end_city'])&& !empty($_POST['begin_time'])&& !empty($_POST['end_time'])&& !empty($_POST['begin_airport'])&& !empty($_POST['end_airport'])&&!empty($_POST['user_discount'])&&!empty($_POST['vip_discount'])&&!empty($_POST['price'])&&!empty($_POST['amount'])) {
        mysqli_query($conn,"INSERT INTO flight(flight_number,airline_name,begin_city,end_city,begin_time,end_time,begin_airport,end_airport,if_stop,user_discount,vip_discount,price,amount) VALUES ('$flight_number','$airline_name','$begin_city','$end_city','$begin_time','$end_time','$begin_airport','$end_airport','$if_stop',$user_discount,$vip_discount,$price,$amount)");
        $result=mysqli_query($conn,"select MAX(flight_id) as flight_id from flight");
        $result_arr=mysqli_fetch_assoc($result);
        $flight_id=$result_arr['flight_id'];
        $commodity_type='flight';
        mysqli_query($conn,"INSERT INTO commodity(commodity_type,original_id) VALUES ('$commodity_type',$flight_id)");
        $add="success";
    }

}?>


<div class="right"> <!--before add a hotel-->
    <h4>添加单个航班信息</h4>
    <form method="post" action="flight_import.php">
        <fieldset>
            <legend>输入航班信息:</legend>
            <div>
            航班号:<br>
            <input type="text" name="flight_number" maxlength="6" placeholder="格式:CX1001"><br>
            <span class="error">* <?php echo $flight_numberError; ?></span><br><br>
            航空公司:<br>
            <select name="airline_name" required>
                <option value="china" selected="selected">中国航空</option> <!-- 中文不行-->
                <option value="east">东方航空</option>
                <option value="south">南方航空</option>
                <option value="west">西方航空</option>
                <option value="north">北方航空</option>
            </select><br><br>
             起飞城市:<br>
            <input type="text" name="begin_city" maxlength="10"><br>
            <span class="error">* <?php echo $begin_cityError; ?></span><br><br>
            目的地城市:<br>
            <input type="text" name="end_city" maxlength="10"><br>
            <span class="error">* <?php echo $end_cityError; ?></span><br><br>
            起飞机场:<br>
            <input type="text" name="begin_airport"><br>
            <span class="error">* <?php echo $begin_airportError; ?></span><br><br>
            目的地机场:<br>
            <input type="text" name="end_airport"><br>
            <span class="error">* <?php echo $end_airportError; ?></span><br><br>
            起飞时间:<br>
            <input type="datetime" name="begin_time" placeholder="1999-12-31 23:59:59"><br>
            <span class="error">* <?php echo $begin_timeError; ?></span><br><br>
            到达时间:<br>
            <input type="datetime" name="end_time" placeholder="2000-12-31 23:59:59"><br>
            <span class="error">* <?php echo $end_timeError; ?></span><br><br>
            中转城市:<br>
            <input type="text" name="if_stop" placeholder="不中转则输入NULL" maxlength="10"><br>
            <span class="error">* <?php echo $if_stopError; ?></span><br><br>
            用户折扣:<br>
            <input type="text" name="user_discount" maxlength="3"><br>
            <span class="error">* <?php echo $user_discountError; ?></span><br><br>
            VIP折扣:<!--(如果折扣是80%,则输入80即可,如果不打折,输入100)--><br>
            <input type="text" name="vip_discount" maxlength="3"><br>
            <span class="error">* <?php echo $vip_discountError; ?></span><br><br>
            原价:<br>
            <input type="text" name="price" maxlength="4" placeholder="价格单位是¥"><br>
            <span class="error">* <?php echo $priceError; ?></span><br><br>
            机票余量:<br>
            <input type="text" name="amount" maxlength="4"><br>
            <span class="error">* <?php echo $amountError; ?></span><br><br>

            <input type="submit" name="sub1" value="提交">
            <?php if($add=="success")
                echo  "<br><span class=\"error\">成功添加航班信息</span>";
            ?>
        </fieldset>
    </form>
    <span class="error"><?php echo $errorInfo; ?></span>
</div>

<div class="right2"> <!-- add hotels -->
    <h2 align="center">批量添加航班信息</h2>
    <fieldset>
        <legend>确认信息</legend>
        <h3>从文件"flights_import"中读取到以下内容, 请确认是否提交:</h3>

    <textarea rows="11" cols="110" readonly>
        <?php  $file = fopen("flights_import", "r") or die("Unable to open file!");
        while (!feof($file)){echo  fgets($file);
        }
        ?>
   </textarea>
        <form method="post" action="flight_import.php">
            <input type="submit" name="sub2" value="提交">
        </form><br>
    <textarea rows="11" cols="110" readonly>
    <?php
    echo "\n";
    if($_POST["sub2"]){
        echo "正在提交数据...\n";
        $file = fopen("flights_import", "r") or die("Unable to open file!");
        $sample = "INSERT INTO flight(flight_number,airline_name,begin_city,end_city,begin_time,end_time,begin_airport,end_airport,if_stop,user_discount,vip_discount,price,amount) VALUES";
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

            mysqli_query($conn,"INSERT INTO flight(flight_number,airline_name,begin_city,end_city,begin_time,end_time,begin_airport,end_airport,if_stop,user_discount,vip_discount,price,amount) VALUES ('$flight_number','$airline_name','$begin_city','$end_city','$begin_time','$end_time','$begin_airport','$end_airport','$if_stop',$user_discount,$vip_discount,$price,$amount)");
            $result=mysqli_query($conn,"select MAX(flight_id) as flight_id from flight");
            $result_arr=mysqli_fetch_assoc($result);
            $flight_id=$result_arr['flight_id'];
            $commodity_type='flight';
            mysqli_query($conn,"INSERT INTO commodity(commodity_type,original_id) VALUES ('$commodity_type',$flight_id)");
        }
        fclose($file);
    }
    ?>
    </textarea>
    </fieldset>
</div>

</body>
</html>

