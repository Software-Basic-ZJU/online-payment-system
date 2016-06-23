(function(){
    angular.module('flightDsitem',[
        'ui.router'
    ]).directive('flightDsitem',function(){
        return {
            templateUrl:'/frontend/components/flight-dsitem/flight-dsitem.html',
            scope:{
                company:'@',
                leaveTime:'@',
                reachTime:'@',
                discRate:'@',
                discPrice:'@',
                goodsId:'@',
                flightId:'@'
            },
            controller:flightDsitem
        }
    });

    function flightDsitem($scope,$state,UserInfo,$stateParams){
        $scope.flightDetail=function(goodsId){
            var flight={
                company:$scope.company,
                leaveTime:$scope.leaveTime,
                reachTime:$scope.reachTime,
                price:$scope.discPrice,
                goodsId:goodsId,
                fromPort:$stateParams.from,
                toPort:$stateParams.to,
                flightId:$scope.flightId
            };
            UserInfo('flight_info',flight);
            $state.go('booking.flightOrder',{gid:goodsId});
        }
    }
})();