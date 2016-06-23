(function(){
    angular.module('orderCancel',[
        'ui.router'
    ]).directive('orderCancel',function(){
        return{
            templateUrl:'/frontend/components/modal-order/order-cancel/order-cancel.html',
            controller:orderCancel
        }
    });

    function orderCancel($scope,$timeout,$state,FormFilter,$http,UserInfo,Flash){
        $scope.$on('showCancel',function(event,data){
            $scope.orderId=data.orderId;
            $scope.orderContent=data.orderContent;
        });
        $scope.cancelForm={
            reasonTip:'default'
        }
        $scope.isLoaded=true;
        $scope.cancel=function(){
            if(!confirm("确认取消吗?")) return false;
            if($scope.cancelForm.reasonTip=='default') return Flash.create('danger','请选择取消订单的原因!');
            if(!FormFilter.sqlFilter($scope.cancelForm.reason)) return Flash.create('danger','原因中含有非法字符串');
            $scope.isLoaded=false;
            $http({
                method:'POST',
                url:'API/order/cancel.php',
                data:{
                    token:UserInfo().token,
                    order_id:$scope.orderId,
                    reason:$scope.cancelForm.reasonTip+' '+$scope.cancelForm.reason
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