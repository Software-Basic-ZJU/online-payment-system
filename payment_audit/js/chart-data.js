var request= new XMLHttpRequest();
request.open("GET","php/charts.php",true);
request.send();
request.onreadystatechange=function(){
	if(request.readyState===4){
		if(request.status===200){
			var data = JSON.parse(request.responseText);
			var lineChartData = {
			labels : ["未付款","已支付待发货","未确认收货","退款","成功","失败","申请退款","申诉中"],
			datasets : [
				{
					label: "前一日",
					fillColor : "rgba(220,220,220,0.2)",
					strokeColor : "rgba(220,220,220,1)",
					pointColor : "rgba(220,220,220,1)",
					pointStrokeColor : "#fff",
					pointHighlightFill : "#fff",
					pointHighlightStroke : "rgba(220,220,220,1)",
					data : [data.unpaid,data.wait_to_ship,data.wait_to_confirm,data.refunded,data.success,data.failed,data.refunding,data.complain]
				},
				{
					label: "My Second dataset",
					fillColor : "rgba(48, 164, 255, 0.2)",
					strokeColor : "rgba(48, 164, 255, 1)",
					pointColor : "rgba(48, 164, 255, 1)",
					pointStrokeColor : "#fff",
					pointHighlightFill : "#fff",
					pointHighlightStroke : "rgba(48, 164, 255, 1)",
					data : [data.unpaid1,data.wait_to_ship1,data.wait_to_confirm1,data.refunded1,data.success1,data.failed1,data.refunding1,data.complain1]
				}
			]
			}
		
			var barChartData = {
			labels : ["未付款","已支付待发货","未确认收货","退款","成功","失败","申请退款","申诉中"],
			datasets : [
				{
					fillColor : "rgba(220,220,220,0.5)",
					strokeColor : "rgba(220,220,220,0.8)",
					highlightFill: "rgba(220,220,220,0.75)",
					highlightStroke: "rgba(220,220,220,1)",
					data : [data.unpaid2,data.wait_to_ship2,data.wait_to_confirm2,data.refunded2,data.success2,data.failed2,data.refunding2,data.complain2]
				},
				{
					fillColor : "rgba(48, 164, 255, 0.2)",
					strokeColor : "rgba(48, 164, 255, 0.8)",
					highlightFill : "rgba(48, 164, 255, 0.75)",
					highlightStroke : "rgba(48, 164, 255, 1)",
					data : [data.unpaid3,data.wait_to_ship3,data.wait_to_confirm3,data.refunded3,data.success3,data.failed3,data.refunding3,data.complain3]
				}
			]
			}
			
			var pieData = [
				{
					value: data.unpaid4,
					color:"#e60000",
					highlight: "#ff0000",
					label: "未付款"
				},
				{
					value: data.wait_to_ship4,
					color: "#ffb53e",
					highlight: "#fac878",
					label: "已支付代发货"
				},
				{
					value: data.wait_to_confirm4,
					color: "#1ebfae",
					highlight: "#3cdfce",
					label: "未确认收货"
				},
				{
					value: data.refunded4,
					color: "#99243f",
					highlight: "#f6495f",
					label: "退款"
				},
				{
					value: data.success4,
					color: "#00ff00",
					highlight: "#00ff88",
					label: "成功"
				},
				{
					value: data.failed4,
					color: "#ffff00",
					highlight: "#ffd700",
					label: "失败"
				},
				{
					value: data.refunding4,
					color: "#9400D3",
					highlight: "#9400b0",
					label: "申请退款"
				},
				{
					value: data.complain4,
					color: "#FF00FF",
					highlight: "#FF77FF",
					label: "申诉中"
				}

			];
			
			var doughnutData = [
					{
						value: data.cost,
						color:"#30a5ff",
						highlight: "#62b9fb",
						label: "0-50"
					},
					{
						value: data.cost1,
						color: "#ffff00",
						highlight: "#d9d919",
						label: "50-150"
					},
					{
						value: data.cost2,
						color: "#ff0000",
						highlight: "#ff7f00",
						label: "150-300"
					},
					{
						value: data.cost3,
						color: "#00ff00",
						highlight: "#32cd32",
						label: "300-500"
					},
					{
						value: data.cost4,
						color: "#6ebfae",
						highlight: "#3cdfce",
						label: "500-1000"
					},
					{
						value: data.cost5,
						color: "#ff00ff",
						highlight: "#ff6ec7",
						label: "1000-3000"
					},
					{
						value: data.cost6,
						color: "#7d7dff",
						highlight: "#0000e3",
						label: ">3000"
					}	
				];
	
				var chart1 = document.getElementById("line-chart").getContext("2d");
				window.myLine = new Chart(chart1).Line(lineChartData, {
				responsive: true});
				var chart2 = document.getElementById("bar-chart").getContext("2d");
				window.myBar = new Chart(chart2).Bar(barChartData, {
				responsive : true});
				var chart3 = document.getElementById("doughnut-chart").getContext("2d");
				window.myDoughnut = new Chart(chart3).Doughnut(doughnutData, {responsive : true});
				var chart4 = document.getElementById("pie-chart").getContext("2d");
				window.myPie = new Chart(chart4).Pie(pieData, {responsive : true});
}
}
}