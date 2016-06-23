<?php

$p=$_GET["intq"];

$conn = mysqli_connect("localhost", "root", "064016", "library");
    if(mysqli_connect_errno()){
        printf("Connect failed: %s\n", mysqli_connect_errno());
        exit();
    }
$today_date=date("Y-m-d");
$yeaterday_date=date("Y-m-d",strtotime("-1 day"));


if($p==1){
	$sql="select * from order_records where order_id in (select order_records.order_id from order_records left join payment on (order_records.order_id=payment.order_id) where order_records.state=1 and order_records.state>=payment.state)
	  union  select * from order_records where order_id in (select order_records.order_id from order_records left join payment on (order_records.order_id=payment.order_id) where order_records.state=3 and payment.state=2 )
	  union  select * from order_records where state=0 or state=2 or state>3";
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
			   echo "<td class=\"col1\">" ."success" . "</td>";
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
        echo "no result!";  
    }
       
}

	  
else{
	$sql="select * from order_records where order_id in (select order_records.order_id from order_records left join payment on (order_records.order_id=payment.order_id) where order_records.state=1 and payment.state=2)
	  union select * from order_records where order_id in (select order_records.order_id from order_records left join payment on (order_records.order_id=payment.order_id) where order_records.state=3 and payment.state<2)";
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
			   if($row['state']==0)
			   echo "<td class=\"col1\">" ."has been paid" . "</td>";
		       else if($row['state']==1)
			   echo "<td class=\"col1\">" ."has not been paid" . "</td>";
		        else if($row['state']==2)
			   echo "<td class=\"col1\">" ."has been send" . "</td>";
		        else if($row['state']==3)
			   echo "<td class=\"col1\">" ."has got pay back" . "</td>";
		        else if($row['state']==4)
			   echo "<td class=\"col1\">" ."did not pass" . "</td>";
		        else if($row['state']==5)
			   echo "<td class=\"col1\">" ."still be solved " . "</td>";
		    else if($row['state']==6)
			   echo "<td class=\"col1\">" ."be in cancel" . "</td>";
		    else if($row['state']==7)
			   echo "<td class=\"col1\">" ."wait for respond" . "</td>";
		    else 
			   echo "<td class=\"col1\">" ."no records" . "</td>";
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
        echo "no result!";  
    }
}
    mysqli_close($conn);  
    //mysqli_free_result($result);
    //mysqli_close($conn);

?>