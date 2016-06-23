<?php
	//header("Content-Type:text/plain;charset=utf-8");
	header("Content-Type:application/json;charset=utf-8");
	global $conn;
    $conn = mysqli_connect("127.0.0.1", "library", "1234", "payment");
	//$conn = mysqli_connect("localhost", "root", "064016", "library");
    if(mysqli_connect_errno()){
        printf("Connect failed: %s\n", mysqli_connect_errno());
        exit();
    }
	$today_date=date("Y-m-d");
	$yeaterday_date=date("Y-m-d",strtotime("-1 day"));
	$twodays_ago_date=date("Y-m-d",strtotime("-2 day"));
	$one_month_ago=date("Y-m-d",strtotime("-1 month"));
	$two_month_ago=date("Y-m-d",strtotime("-2 month"));
	
	$unpaid=mysqli_query($conn,"select count(*) from order_records where state = '0' and start_time between '".$yeaterday_date."' and '".$today_date."'");
	$wait_to_ship=mysqli_query($conn,"select count(*) from order_records where state = '1' and start_time between '".$yeaterday_date."' and '".$today_date."'");
	$wait_to_confirm=mysqli_query($conn,"select count(*) from order_records where state = '2' and start_time between '".$yeaterday_date."' and '".$today_date."'");
	$refunded=mysqli_query($conn,"select count(*) from order_records where state = '3' and start_time between '".$yeaterday_date."' and '".$today_date."'");
	$success=mysqli_query($conn,"select count(*) from order_records where state = '4' and start_time between '".$yeaterday_date."' and '".$today_date."'");
	$failed=mysqli_query($conn,"select count(*) from order_records where state = '5' and start_time between '".$yeaterday_date."' and '".$today_date."'");
	$refunding=mysqli_query($conn,"select count(*) from order_records where state = '6' and start_time between '".$yeaterday_date."' and '".$today_date."'");
	$complain=mysqli_query($conn,"select count(*) from order_records where state = '7' and start_time between '".$yeaterday_date."' and '".$today_date."'");
	
	$unpaid1=mysqli_query($conn,"select count(*) from order_records where state = '0' and start_time between '".$twodays_ago_date."' and '".$yeaterday_date."'");
	$wait_to_ship1=mysqli_query($conn,"select count(*) from order_records where state = '1' and start_time between '".$twodays_ago_date."' and '".$yeaterday_date."'");
	$wait_to_confirm1=mysqli_query($conn,"select count(*) from order_records where state = '2' and start_time between '".$twodays_ago_date."' and '".$yeaterday_date."'");
	$refunded1=mysqli_query($conn,"select count(*) from order_records where state = '3' and start_time between '".$twodays_ago_date."' and '".$yeaterday_date."'");
	$success1=mysqli_query($conn,"select count(*) from order_records where state = '4' and start_time between '".$twodays_ago_date."' and '".$yeaterday_date."'");
	$failed1=mysqli_query($conn,"select count(*) from order_records where state = '5' and start_time between '".$twodays_ago_date."' and '".$yeaterday_date."'");
	$refunding1=mysqli_query($conn,"select count(*) from order_records where state = '6' and start_time between '".$twodays_ago_date."' and '".$yeaterday_date."'");
	$complain1=mysqli_query($conn,"select count(*) from order_records where state = '7' and start_time between '".$twodays_ago_date."' and '".$yeaterday_date."'");
	
	$unpaid2=mysqli_query($conn,"select count(*) from order_records where state = '0' and start_time between '".$one_month_ago."' and '".$today_date."'");
	$wait_to_ship2=mysqli_query($conn,"select count(*) from order_records where state = '1' and start_time between '".$one_month_ago."' and '".$today_date."'");
	$wait_to_confirm2=mysqli_query($conn,"select count(*) from order_records where state = '2' and start_time between '".$one_month_ago."' and '".$today_date."'");
	$refunded2=mysqli_query($conn,"select count(*) from order_records where state = '3' and start_time between '".$one_month_ago."' and '".$today_date."'");
	$success2=mysqli_query($conn,"select count(*) from order_records where state = '4' and start_time between '".$one_month_ago."' and '".$today_date."'");
	$failed2=mysqli_query($conn,"select count(*) from order_records where state = '5' and start_time between '".$one_month_ago."' and '".$today_date."'");
	$refunding2=mysqli_query($conn,"select count(*) from order_records where state = '6' and start_time between '".$one_month_ago."' and '".$today_date."'");
	$complain2=mysqli_query($conn,"select count(*) from order_records where state = '7' and start_time between '".$one_month_ago."' and '".$today_date."'");
	
	$unpaid3=mysqli_query($conn,"select count(*) from order_records where state = '0' and start_time between '".$two_month_ago."' and '".$today_date."'");
	$wait_to_ship3=mysqli_query($conn,"select count(*) from order_records where state = '1' and start_time between '".$two_month_ago."' and '".$today_date."'");
	$wait_to_confirm3=mysqli_query($conn,"select count(*) from order_records where state = '2' and start_time between '".$two_month_ago."' and '".$today_date."'");
	$refunded3=mysqli_query($conn,"select count(*) from order_records where state = '3' and start_time between '".$two_month_ago."' and '".$today_date."'");
	$success3=mysqli_query($conn,"select count(*) from order_records where state = '4' and start_time between '".$two_month_ago."' and '".$today_date."'");
	$failed3=mysqli_query($conn,"select count(*) from order_records where state = '5' and start_time between '".$two_month_ago."' and '".$today_date."'");
	$refunding3=mysqli_query($conn,"select count(*) from order_records where state = '6' and start_time between '".$two_month_ago."' and '".$today_date."'");
	$complain3=mysqli_query($conn,"select count(*) from order_records where state = '7' and start_time between '".$two_month_ago."' and '".$today_date."'");
	
	$unpaid4=mysqli_query($conn,"select count(*) from order_records where state = '0'");
	$wait_to_ship4=mysqli_query($conn,"select count(*) from order_records where state = '1'");
	$wait_to_confirm4=mysqli_query($conn,"select count(*) from order_records where state = '2'");
	$refunded4=mysqli_query($conn,"select count(*) from order_records where state = '3'");
	$success4=mysqli_query($conn,"select count(*) from order_records where state = '4'");
	$failed4=mysqli_query($conn,"select count(*) from order_records where state = '5'");
	$refunding4=mysqli_query($conn,"select count(*) from order_records where state = '6'");
	$complain4=mysqli_query($conn,"select count(*) from order_records where state = '7'");
	
	$cost=mysqli_query($conn,"select count(*) from order_records where price*amount <= 50");
	$cost1=mysqli_query($conn,"select count(*) from order_records where price*amount between 50 and 150");
	$cost2=mysqli_query($conn,"select count(*) from order_records where price*amount between 150 and 300");
	$cost3=mysqli_query($conn,"select count(*) from order_records where price*amount between 300 and 500");
	$cost4=mysqli_query($conn,"select count(*) from order_records where price*amount between 500 and 1000");
	$cost5=mysqli_query($conn,"select count(*) from order_records where price*amount between 1000 and 3000");
	$cost6=mysqli_query($conn,"select count(*) from order_records where price*amount > 3000");

	$unpaid_row = mysqli_fetch_array($unpaid);
	$wait_to_ship_row = mysqli_fetch_array($wait_to_ship);
	$wait_to_confirm_row = mysqli_fetch_array($wait_to_confirm);
	$refunded_row = mysqli_fetch_array($refunded);
	$success_row = mysqli_fetch_array($success);
	$failed_row = mysqli_fetch_array($failed);
	$refunding_row = mysqli_fetch_array($refunding);
	$complain_row = mysqli_fetch_array($complain);
	
	$unpaid_row1 = mysqli_fetch_array($unpaid1);
	$wait_to_ship_row1 = mysqli_fetch_array($wait_to_ship1);
	$wait_to_confirm_row1 = mysqli_fetch_array($wait_to_confirm1);
	$refunded_row1 = mysqli_fetch_array($refunded1);
	$success_row1 = mysqli_fetch_array($success1);
	$failed_row1 = mysqli_fetch_array($failed1);
	$refunding_row1 = mysqli_fetch_array($refunding1);
	$complain_row1 = mysqli_fetch_array($complain1);
	
	$unpaid_row2 = mysqli_fetch_array($unpaid2);
	$wait_to_ship_row2 = mysqli_fetch_array($wait_to_ship2);
	$wait_to_confirm_row2 = mysqli_fetch_array($wait_to_confirm2);
	$refunded_row2 = mysqli_fetch_array($refunded2);
	$success_row2 = mysqli_fetch_array($success2);
	$failed_row2 = mysqli_fetch_array($failed2);
	$refunding_row2 = mysqli_fetch_array($refunding2);
	$complain_row2 = mysqli_fetch_array($complain2);
	
	$unpaid_row3 = mysqli_fetch_array($unpaid3);
	$wait_to_ship_row3 = mysqli_fetch_array($wait_to_ship3);
	$wait_to_confirm_row3 = mysqli_fetch_array($wait_to_confirm3);
	$refunded_row3 = mysqli_fetch_array($refunded3);
	$success_row3 = mysqli_fetch_array($success3);
	$failed_row3 = mysqli_fetch_array($failed3);
	$refunding_row3 = mysqli_fetch_array($refunding3);
	$complain_row3 = mysqli_fetch_array($complain3);
	
	$unpaid_row4 = mysqli_fetch_array($unpaid4);
	$wait_to_ship_row4 = mysqli_fetch_array($wait_to_ship4);
	$wait_to_confirm_row4 = mysqli_fetch_array($wait_to_confirm4);
	$refunded_row4 = mysqli_fetch_array($refunded4);
	$success_row4 = mysqli_fetch_array($success4);
	$failed_row4 = mysqli_fetch_array($failed4);
	$refunding_row4 = mysqli_fetch_array($refunding4);
	$complain_row4 = mysqli_fetch_array($complain4);
	
	$cost_row = mysqli_fetch_array($cost);
	$cost1_row = mysqli_fetch_array($cost1);
	$cost2_row = mysqli_fetch_array($cost2);
	$cost3_row = mysqli_fetch_array($cost3);
	$cost4_row = mysqli_fetch_array($cost4);
	$cost5_row = mysqli_fetch_array($cost5);
	$cost6_row = mysqli_fetch_array($cost6);

	$unpaid_num = $unpaid_row[0];
	$wait_to_ship_num = $wait_to_ship_row[0];
	$wait_to_confirm_num = $wait_to_confirm_row[0];
	$refunded_num = $refunded_row[0];
	$success_num = $success_row[0];
	$failed_num = $failed_row[0];
	$refunding_num = $refunding_row[0];
	$complain_num = $complain_row[0];
	
	$unpaid_num1 = $unpaid_row1[0];
	$wait_to_ship_num1 = $wait_to_ship_row1[0];
	$wait_to_confirm_num1 = $wait_to_confirm_row1[0];
	$refunded_num1 = $refunded_row1[0];
	$success_num1 = $success_row1[0];
	$failed_num1 = $failed_row1[0];
	$refunding_num1 = $refunding_row1[0];
	$complain_num1 = $complain_row1[0];
	
	$unpaid_num2 = $unpaid_row2[0];
	$wait_to_ship_num2 = $wait_to_ship_row2[0];
	$wait_to_confirm_num2 = $wait_to_confirm_row2[0];
	$refunded_num2 = $refunded_row2[0];
	$success_num2 = $success_row2[0];
	$failed_num2 = $failed_row2[0];
	$refunding_num2 = $refunding_row2[0];
	$complain_num2 = $complain_row2[0];
	
	$unpaid_num3 = $unpaid_row3[0];
	$wait_to_ship_num3 = $wait_to_ship_row3[0];
	$wait_to_confirm_num3 = $wait_to_confirm_row3[0];
	$refunded_num3 = $refunded_row3[0];
	$success_num3 = $success_row3[0];
	$failed_num3 = $failed_row3[0];
	$refunding_num3 = $refunding_row3[0];
	$complain_num3 = $complain_row3[0];
	
	$unpaid_num4 = $unpaid_row4[0];
	$wait_to_ship_num4 = $wait_to_ship_row4[0];
	$wait_to_confirm_num4 = $wait_to_confirm_row4[0];
	$refunded_num4 = $refunded_row4[0];
	$success_num4 = $success_row4[0];
	$failed_num4 = $failed_row4[0];
	$refunding_num4 = $refunding_row4[0];
	$complain_num4 = $complain_row4[0];
	
	$cost_num=$cost_row[0];
	$cost1_num=$cost1_row[0];
	$cost2_num=$cost2_row[0];
	$cost3_num=$cost3_row[0];
	$cost4_num=$cost4_row[0];
	$cost5_num=$cost5_row[0];
	$cost6_num=$cost6_row[0];
	
	$result_num=array();
	$result_num[0]=$unpaid_num;
	$result_num[1]=$wait_to_ship_num;
	$result_num[2]=$wait_to_confirm_num;
	$result_num[3]=$refunded_num;
	$result_num[4]=$success_num;
	$result_num[5]=$failed_num;
	$result_num[6]=$refunding_num;
	$result_num[7]=$complain_num;
	
	$result_num1=array();
	$result_num1[0]=$unpaid_num1;
	$result_num1[1]=$wait_to_ship_num1;
	$result_num1[2]=$wait_to_confirm_num1;
	$result_num1[3]=$refunded_num1;
	$result_num1[4]=$success_num1;
	$result_num1[5]=$failed_num1;
	$result_num1[6]=$refunding_num1;
	$result_num1[7]=$complain_num1;
	
	$result_num2=array();
	$result_num2[0]=$unpaid_num2;
	$result_num2[1]=$wait_to_ship_num2;
	$result_num2[2]=$wait_to_confirm_num2;
	$result_num2[3]=$refunded_num2;
	$result_num2[4]=$success_num2;
	$result_num2[5]=$failed_num2;
	$result_num2[6]=$refunding_num2;
	$result_num2[7]=$complain_num2;
	$result_num2[8]=$unpaid_num3;
	$result_num2[9]=$wait_to_ship_num3;
	$result_num2[10]=$wait_to_confirm_num3;
	$result_num2[11]=$refunded_num3;
	$result_num2[12]=$success_num3;
	$result_num2[13]=$failed_num3;
	$result_num2[14]=$refunding_num3;
	$result_num2[15]=$complain_num3;
	
	$result_num3=array();
	$result_num3[0]=$unpaid_num4;
	$result_num3[1]=$wait_to_ship_num4;
	$result_num3[2]=$wait_to_confirm_num4;
	$result_num3[3]=$refunded_num4;
	$result_num3[4]=$success_num4;
	$result_num3[5]=$failed_num4;
	$result_num3[6]=$refunding_num4;
	$result_num3[7]=$complain_num4;
	
	$result_cost=array();
	$result_cost[0]=$cost_num;
	$result_cost[1]=$cost1_num;
	$result_cost[2]=$cost2_num;
	$result_cost[3]=$cost3_num;
	$result_cost[4]=$cost4_num;
	$result_cost[5]=$cost5_num;
	$result_cost[6]=$cost6_num;
	
	echo '{"unpaid":'.$result_num[0].',"wait_to_ship":'.$result_num[1].',"wait_to_confirm":'.$result_num[2].',"refunded":'.$result_num[3].',"success":'.$result_num[4].',"failed":'.$result_num[5].',"refunding":'.$result_num[6].',"complain":'.$result_num[7].',"unpaid1":'.$result_num1[0].',"wait_to_ship1":'.$result_num1[1].',"wait_to_confirm1":'.$result_num1[2].',"refunded1":'.$result_num1[3].',"success1":'.$result_num1[4].',"failed1":'.$result_num1[5].',"refunding1":'.$result_num1[6].',"complain1":'.$result_num1[7].',"unpaid2":'.$result_num2[0].',"wait_to_ship2":'.$result_num2[1].',"wait_to_confirm2":'.$result_num2[2].',"refunded2":'.$result_num2[3].',"success2":'.$result_num2[4].',"failed2":'.$result_num2[5].',"refunding2":'.$result_num2[6].',"complain2":'.$result_num2[7].',"unpaid3":'.$result_num2[8].',"wait_to_ship3":'.$result_num2[9].',"wait_to_confirm3":'.$result_num2[10].',"refunded3":'.$result_num2[11].',"success3":'.$result_num2[12].',"failed3":'.$result_num2[13].',"refunding3":'.$result_num2[14].',"complain3":'.$result_num2[15].',"unpaid4":'.$result_num3[0].',"wait_to_ship4":'.$result_num3[1].',"wait_to_confirm4":'.$result_num3[2].',"refunded4":'.$result_num3[3].',"success4":'.$result_num3[4].',"failed4":'.$result_num3[5].',"refunding4":'.$result_num3[6].',"complain4":'.$result_num3[7].',"cost":'.$result_cost[0].',"cost1":'.$result_cost[1].',"cost2":'.$result_cost[2].',"cost3":'.$result_cost[3].',"cost4":'.$result_cost[4].',"cost5":'.$result_cost[5].',"cost6":'.$result_cost[6].'}';
	mysqli_close($conn);
?>