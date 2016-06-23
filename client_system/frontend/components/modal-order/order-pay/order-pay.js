(function(){
    angular.module('orderPay',[
        'ui.router'
    ]).directive('orderPay',function(){
        return{
            templateUrl:'/frontend/components/modal-order/order-pay/order-pay.html',
            controller:orderPay
        }
    });

    function orderPay($scope,$http,FormFilter,UserInfo,Flash,$state,$timeout){
        $scope.payForm={
            payWay:'balance',
            payPswd:''
        };
        $scope.$on('showPay',function(event,data){
            $scope.orderId=data.orderId;
            $scope.orderContent=data.orderContent;
        });
        $scope.isLoaded=true;
        $scope.confirmPay=function(){
            if(!$scope.payForm.payPswd) return Flash.create('danger','密码不能为空');
            if(!FormFilter.voidFilter($scope.payForm.payPswd)) return Flash.create('danger','密码含有非法字符!');
            if(!confirm("确认支付吗?")) return false;
            $scope.isLoaded=false;
            $http({
                method:'POST',
                url:'API/order/payment.php',
                data:{
                    token:UserInfo().token,
                    order_id:$scope.orderId,
                    password:$scope.payForm.payPswd
                }
            }).then(function(response){
                var resp=response.data;
                if(resp.code==0){
                    Flash.create('success',resp.msg);
                    $timeout(function(){
                        $state.reload();
                    },800);
                }
            }).finally(function(){
                $scope.isLoaded=true;
            })

        }
    }
})();