(function(){
    angular.module('orderRefund',[
        'ui.router'
    ]).directive('orderRefund',function(){
        return{
            templateUrl:'/frontend/components/modal-order/order-refund/order-refund.html',
            controller:orderRefund
        }
    });

    function orderRefund($scope,$state,$timeout,FormFilter,Flash,$http,UserInfo){
        $scope.refundForm={
            reasonTip:'default'
        }
        $scope.$on('showRefund',function(event,data){
            $scope.orderId=data.orderId;
            $scope.orderContent=data.orderContent;
        });
        $scope.isLoaded=true;
        $scope.refund=function(){
            if(!confirm("确认提交吗?")) return false;
            if($scope.refundForm.reasonTip=='default') return Flash.create('danger','请选择退款原因!');
            if(!FormFilter.sqlFilter($scope.refundForm.reason)) return Flash.create('danger','原因中含有非法字符串');
            $scope.isLoaded=false;
            $http({
                method:'POST',
                url:'API/order/refundApc.php',
                data:{
                    token:UserInfo().token,
                    order_id:$scope.orderId,
                    reason:$scope.refundForm.reasonTip+' '+$scope.refundForm.reason
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