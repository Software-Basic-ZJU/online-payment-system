(function(){
    angular.module('orderConfirm',[
        'ui.router'
    ]).directive('orderConfirm',function(){
        return{
            templateUrl:'/frontend/components/modal-order/order-confirm/order-confirm.html',
            controller:orderConfirm
        }
    });

    function orderConfirm($scope,$http,FormFilter,Flash,UserInfo,$state,$timeout){
        $scope.$on('showConfirm',function(event,data){
            $scope.orderId=data.orderId;
            $scope.orderContent=data.orderContent;
        });
        $scope.isLoaded=true;
        $scope.confirm=function(){
            if(!confirm("确认收货吗?")) return false;
            if(!$scope.confirm.payPswd) return Flash.create('danger','密码不能为空!');
            if(!FormFilter.sqlFilter($scope.payPswd)) return Flash.create('danger','密码含有非法字符!');
            $scope.isLoaded=false;
            $http({
                method:'POST',
                url:'API/order/receive.php',
                data:{
                    token:UserInfo().token,
                    order_id:$scope.orderId,
                    password:$scope.confirm.payPswd
                }
            }).then(function(response){
                var resp=response.data;
                if(resp.code==0){
                    Flash.create('success',resp.msg);
                    $timeout(function(){
                        $state.reload();
                    },1200);
                }
            }).finally(function(){
                $scope.isLoaded=true;
            })
        }
    }
})();