(function(){
    angular.module('orderDetail',[
        'ui.router'
    ]).controller('orderDetail',orderDetail);

    function orderDetail($scope,$state,$timeout){
        //$scope.goBack 函数在父controller中声明
        $scope.state="交易成功";
        $scope.isCompleted=true;
        $scope.isNonPay=false;
        $scope.price=10000;
        $scope.orderId=1234;
        $scope.orderContent="飞机大炮";
        $timeout(function(){    //test
            $scope.isLoaded=true;
        },1000);

        $scope.goModal=function(type,orderId,orderContent){
            var data={
                orderId:orderId,
                orderContent:orderContent
            };
            hideAllExcept(type);
            $scope.showModal=true;
            $scope.$emit('emit'+type,data);
        };

        function hideAllExcept(type){
            $scope.showPay=false;
            $scope.showCancel=false;
            $scope.showRefund=false;
            $scope.showComplain=false;
            $scope.showConfirm=false;
            $scope['show'+type]=true;
        }
    }
})();