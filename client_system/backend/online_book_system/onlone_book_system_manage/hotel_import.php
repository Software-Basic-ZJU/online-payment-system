<?php include("TEMPLATE.php");?>
<!DOCTYPE html>
<html>
<head>
    <meta  http-equiv="Content-Type" content="text/html; charset="utf-8"/>

    <title>添加酒店信息</title>
</head>
<body>


<?php  //after add a hotel


$add="fail";
if($_POST["sub1"]){
    if(empty($_POST["hotel_name"]))
        $hotel_nameError = "未输入酒店名";
    else
        $hotel_name = $_POST['hotel_name'];
    if(empty($_POST["place"]))
        $placeError = "未输入酒店地点";
    else
        $place= $_POST["place"];
    $star= $_POST["star"];
    if(!empty($_POST["hotel_name"]) && !empty($_POST['place'])) {
        switch ($star) {
            case 'five':
                $star_number = 5;
                break;
            case 'four':
                $star_number = 4;
                break;
            case 'three':
                $star_number = 3;
                break;
            case 'two':
                $star_number = 2;
                break;
            case 'one':
                $star_number = 1;
                break;
        }
        mysqli_query($conn, "INSERT INTO hotel(hotel_name,place,star) VALUES ('$hotel_name','$place',$star_number)");
        $add="success";
    }

}?>

    <div class="right"> <!--before add a hotel-->
    <h4>添加单个酒店信息</h4>
    <form method="post" action="hotel_import.php">
        <fieldset>
            <legend>输入酒店信息:</legend>
            酒店名:<br>
            <input type="text" name="hotel_name" maxlength="50"><br>
            <span class="error">* <?php echo $hotel_nameError; ?></span><br><br>
            酒店地点:<br>
            <input type="text" name="place" maxlength="100"  ><br>
            <span class="error">* <?php echo $placeError; ?></span><br><br>
            酒店星级:<br>
            <select name="star" required>
                <option value="five" selected="selected">五星级/豪华</option>
                <option value="four">四星级/高档</option>
                <option value="three">三星级/舒适</option>
                <option value="two">二星级/经济</option>
                <option value="one">一星级/经济</option>
            </select><br><br>
            <input type="submit" name="sub1" value="提交">
                <?php if($add=="success")
                echo  "<br><span class=\"error\">成功添加酒店信息</span>";
                ?>
        </fieldset>
    </form>
    <span class="error"><?php echo $errorInfo; ?></span>
    </div>

    <div class="right2"> <!-- add hotels -->
    <h2 align="center">批量添加酒店信息</h2>
    <fieldset>
        <legend>确认信息</legend>
        <h3>从文件"hotels_import"中读取到以下内容, 请确认是否提交:</h3>

    <textarea rows="11" cols="110" readonly>
        <?php  $file = fopen("hotels_import", "r") or die("Unable to open file!");
        while (!feof($file)){echo  fgets($file);
        }
        ?>
   </textarea>
        <form method="post" action="hotel_import.php">
            <input type="submit" name="sub2" value="提交">
        </form><br>
    <textarea rows="11" cols="110" readonly>
    <?php
    echo "\n";
    if($_POST["sub2"]){
        echo "正在提交数据...\n";
        $file = fopen("hotels_import", "r") or die("Unable to open file!");
        $sample = "insert into hotel(hotel_name,place,star) values";
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
        }
        fclose($file);
    }
    ?>
    </textarea>
    </fieldset>
    </div>

</body>
</html>

