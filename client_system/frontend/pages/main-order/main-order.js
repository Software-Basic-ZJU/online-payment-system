(function(){
    angular.module('mainOrder',[
        'ui.router',
        [
            'modal',
            'orderItem',
            'orderPay',
            'orderRefund',
            'orderCancel',
            'orderComplain',
            'orderConfirm',
            'orderReply',
            'paging',
            'bw.paging'
        ]
    ]).controller('mainOrder',mainOrder);

    function mainOrder($scope,$timeout,$http,FormFilter,UserInfo,CompTime){
        var orderList,begin=0,end;
        $scope.isLoaded=false;
        $scope.hasNoRes=false;
        $scope.choiceTime=0;
        $scope.choiceState=0;
        $scope.pageSize=5;
        end=$scope.pageSize;
        $scope.changeTime=function(time){
            $scope.choiceTime=time;
            getOrderList();
        };
        $scope.changeState=function(state){
            $scope.choiceState=state;
            getOrderList();
        };
        $scope.orders=[];
        getOrderList();
        //翻页
        $scope.changePage=function(page){
            //console.log(page)
            //$scope.isLoaded=false;
            begin=(page-1)*5;
            end=begin+5;
            $scope.orders=orderList.slice(begin,end);

        };

        function getOrderList(){
            $scope.isLoaded = false;
            $scope.hasNoRes = false;
            $http({
                method: 'POST',
                url: 'API/order/orderQuery.php',
                data: {
                    token: UserInfo().token,
                    span: $scope.choiceTime,
                    state: $scope.choiceState,
                    type: UserInfo().userType
                }
            }).then(function (response) {
                var resp = response.data;
                if (resp.code == 0) {
                    orderList = resp.res.order_records;
                    //修饰订单数组内容
                    if (!orderList.length) $scope.hasNoRes = true;
                    for (var i = 0; i < orderList.length; i++) {
                        orderList[i].stateName = transferState(orderList[i].state);
                        if (orderList[i].type == 'flight') {
                            orderList[i].orderContent = '航班 ' + orderList[i].goods.flight_number
                                + ' ' + orderList[i].goods.begin_city + '-' + orderList[i].goods.end_city
                                + ' [' + orderList[i].goods.begin_time + ']起飞';
                        }
                        else {
                            orderList[i].orderContent = '酒店 ' + orderList[i].goods.hotel_name
                                + ' ' + orderList[i].goods.room_type + ' [' + orderList[i].goods.begin_date
                                + ']入住';
                        }
                    }
                    begin=0;end=$scope.pageSize;
                    $scope.currPage = 1;
                    $scope.total = resp.res.order_records.length;
                    $scope.isLoaded = true;
                    orderList.sort(function (a, b) {
                        if ((a.state < 3 || a.state > 5) && (b.state >= 3 && b.state <= 5)) return -1;
                        else if ((a.state >= 3 && a.state <= 5) && (b.state < 3 || b.state > 5)) return 1;
                        else return CompTime(a.action_time, b.action_time);
                    });
                    $scope.orders = orderList.slice(begin, end);
                }
            });

        }

        function transferState(state){
            var stateName;
            switch(state){
                case '0':stateName='未付款';break;
                case '1':stateName='等待发货';break;
                case '2':stateName='未确认收货';break;
                case '3':stateName='已退款';break;
                case '4':stateName='成功';break;
                case '5':stateName='失败';break;
                case '6':stateName='申请退款中';break;
                case '7':stateName='申诉中';break;
                default:stateName='异常状态';break;
            }
            return stateName;
        }

    }
})();