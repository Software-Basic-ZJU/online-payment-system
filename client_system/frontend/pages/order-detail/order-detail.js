(function(){
    angular.module('orderDetail',[
        'ui.router',
        [
            'orderPay',
            'orderRefund',
            'orderCancel',
            'orderComplain',
            'orderConfirm',
            'orderReply'
        ]
    ]).controller('orderDetail',orderDetail);

    function orderDetail($scope,$state,$stateParams,$http,UserInfo,Flash){
        //$scope.goBack 函数在父controller中声明
        $scope.isSuccess=false;
        var orderId=$stateParams.orderId;
        if(!orderId) return $state.go('main');
        $scope.goBack=function(){
            history.back();
        };
        $scope.goModal=function(type,orderId,orderContent){
            var data={
                orderId:orderId,
                orderContent:orderContent
            };
            hideAllExcept(type);
            $scope.showModal=true;
            $scope.$emit('emit'+type,data);
        };
        $http({
            method:'POST',
            url:'API/order/orderDetail.php',
            data:{
                token:UserInfo().token,
                order_id:orderId
            }
        }).then(function(response){
            var resp=response.data;
            if(resp.code==0){
                var order=resp.res.order_records;
                var seller=resp.res.seller;
                if(!order){
                    Flash.create('danger','该订单不存在!');
                    return $state.go('main.order');
                }
                $scope.orderInfo={
                    orderId:orderId,
                    goodsId:order.goods_id,
                    startTime:order.start_time,
                    payTime:order.pay_time,
                    closeTime:order.close_time,
                    price:order.price,
                    stateName:transferState(order.state),
                    type:order.type
                };
                if(order.goods!=null) {
                    if ($scope.orderInfo.type == 'flight') {
                        $scope.orderInfo.orderContent = '航班 ' + order.goods.flight_number
                            + ' ' + order.goods.begin_city + '-' + order.goods.end_city
                            + ' [' + order.goods.begin_time + ']起飞';
                    }
                    else {
                        $scope.orderInfo.orderContent = '酒店 ' + order.goods.hotel_name
                            + ' ' + order.goods.room_type + ' [' + order.goods.begin_date
                            + ']入住';
                    }
                }

                //卖家不能进行任何对订单的买方操作
                if(UserInfo().isBuyer==0) {
                    $scope.isCompleted=true;
                    $scope.isSuccess=false;
                }
                if(order.state>=3 && order.state<=5 && $scope.orderInfo.type!='flight') {
                    if(order.state==4 && UserInfo().isBuyer==1) $scope.isSuccess=true;
                    $scope.isCompleted=true;
                }
                else if(order.state==0) $scope.isNonPay=true;

                if(seller) {
                    $scope.sellInfo = {
                        userName: seller.seller_name,
                        name: seller.name ? seller.name : '未实名认证',
                        email: seller.email,
                        phone: seller.phone
                    };
                }
                $scope.isLoaded=true;
            }
        });

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
        function hideAllExcept(type){
            $scope.showPay=false;
            $scope.showCancel=false;
            $scope.showRefund=false;
            $scope.showComplain=false;
            $scope.showConfirm=false;
            $scope.showReply=false;
            $scope['show'+type]=true;
        }
    }
})();