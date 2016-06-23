<?php

$intq=$_GET["intq"];
$conn = mysqli_connect("127.0.0.1", "library", "1234", "payment");
//$conn = mysqli_connect("localhost", "root", "064016", "library");
    if(mysqli_connect_errno()){
        printf("Connect failed: %s\n", mysqli_connect_errno());
        exit();
    }

$today_date=date("Y-m-d");
$yeaterday_date=date("Y-m-d",strtotime("-1 day"));

$sql="select * from order_records where state=".$intq;
$result = mysqli_query($conn,$sql);

  if($result)
    {
        if($row=mysqli_num_rows($result))
        //if($row=mysqli_fetch_row($result))
        {
          
           while($row = mysqli_fetch_array($result))
           //while($row = mysqli_fetch_array($result))
           {
               echo "<tr>";
               echo "<td class=\"col1\">" . $row['order_id'] . "</td>";
               echo "<td class=\"col1\">" . $row['goods_id'] . "</td>";
               echo "<td class=\"col1\">" . $row['amount'] . "</td>";
               echo "<td class=\"col1\">" . $row['price'] . "</td>";
               echo "<td class=\"col1\">" . $row['buyer'] . "</td>";
               echo "<td class=\"col1\">" . $row['seller'] . "</td>";
               echo "<td class=\"col1\">" . $row['state'] . "</td>";
               echo "<td class=\"col1\">" . $row['start_time'] . "</td>";
               echo "<td class=\"col1\">" . $row['close_time'] . "</td>";
			   
               echo "</tr>";
           }
           
        }
        else
        {
            echo "no result!";
        }
    }

 
    else
    {
        echo "error";  
    }
       

	  

    mysqli_close($conn);  
    //mysqli_free_result($result);
    //mysqli_close($conn);

?>
