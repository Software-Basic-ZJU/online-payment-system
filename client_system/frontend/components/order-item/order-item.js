(function(){
    angular.module('orderItem',[
        'ui.router'
    ]).directive('orderItem',function(){
        return {
            templateUrl:'/frontend/components/order-item/order-item.html',
            scope:{
                time:'@',
                orderId:'@',
                content:'@',
                buyer:'@',
                seller:'@',
                price:'@',
                amount:'@',
                state:'@'
            },
            controller:orderItem
        }
    })

    function orderItem($scope,$state,UserInfo,$http,Flash){
        switch($scope.state){
            case '未付款':$scope.isNonPay=true;break;
            case '等待发货':$scope.isWaitShip=true;break;
            case '未确认收货':$scope.isWaitConfirm=true;break;
            case '申请退款中':$scope.refunding=true;break;
            default:break;
        }

        //检查是否成功且是否为航班，只有宾馆才能评价 (不好的方式)
        if($scope.state=='成功' && $scope.content.indexOf('航班')<0){
            $scope.isSuccess=true;
        }

        $scope.isBuyer=UserInfo().isBuyer==1?true:false;    //所存为字符串，必须转换为true&false

        $scope.goModal=function(type,orderId,orderContent){
            var data={
                orderId:orderId,
                orderContent:orderContent
            };
            hideAllExcept(type);
            $scope.$parent.$parent.showModal=true;
            $scope.$emit('emit'+type,data);
        };

        $scope.decideRefund=function(decision,orderId,price){
            if(!confirm('确定同意退款吗?')) return false;
            $http({
                method:'POST',
                url:'API/order/refundPrc.php',
                data:{
                    token:UserInfo().token,
                    decision:decision,
                    order_id:orderId,
                    price:price,
                    buyer:$scope.buyer
                }
            }).then(function(response){
                var resp=response.data;
                if(resp.code==0){
                    Flash.create('success',resp.msg);
                    $state.reload();
                }
            })
        }

        $scope.goDetail=function(orderId){
            $state.go('main.orderDetail',{orderId:orderId})
        }
        function hideAllExcept(type){
            $scope.$parent.$parent.showPay=false;
            $scope.$parent.$parent.showCancel=false;
            $scope.$parent.$parent.showRefund=false;
            $scope.$parent.$parent.showComplain=false;
            $scope.$parent.$parent.showConfirm=false;
            $scope.$parent.$parent.showReply=false;
            $scope.$parent.$parent['show'+type]=true;
        }
    }
})();