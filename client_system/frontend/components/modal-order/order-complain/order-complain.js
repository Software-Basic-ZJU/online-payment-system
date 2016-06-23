(function(){
    angular.module('orderComplain',[
        'ui.router'
    ]).directive('orderComplain',function(){
        return {
            templateUrl:'/frontend/components/modal-order/order-complain/order-complain.html',
            controller:orderComplain
        }
    })

    function orderComplain($scope,$timeout,$http,$state,FormFilter,UserInfo,Flash){
        $scope.cmlpForm={
            reasonTip:'default'
        };
        $scope.$on('showComplain',function(event,data){
            $scope.orderId=data.orderId;
            $scope.orderContent=data.orderContent;
        });
        $scope.isLoaded=true;

        $scope.complain=function(){
            if(!confirm("确认提交吗?")) return false;
            if($scope.cmlpForm.reasonTip=='default') return Flash.create('danger','请选择申诉原因');
            if(!FormFilter.sqlFilter($scope.cmlpForm.reason)) return Flash.create('danger','原因中含有非法字符串');
            $scope.isLoaded=false;
            $http({
                method:'POST',
                url:'API/order/complaintApc.php',
                data:{
                    token:UserInfo().token,
                    order_id:$scope.orderId,
                    reason:$scope.cmlpForm.reasonTip+' '+$scope.cmlpForm.reason
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