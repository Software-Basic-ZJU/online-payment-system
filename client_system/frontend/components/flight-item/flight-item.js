(function(){
    angular.module('flightItem',[
        'ui.router'
    ]).directive('flightItem',function(){
        return {
            templateUrl:'/frontend/components/flight-item/flight-item.html',
            scope:{
                goodsId:'@',
                date:'@',
                fromCity:'@',
                toCity:'@',
                fromPort:'@',
                toPort:'@',
                leaveTime:'@',
                reachTime:'@',
                company:'@',
                flightId:'@',
                price:'@'
            },
            controller:flightItem
        }
    });

    function flightItem($scope,$state,UserInfo){
        $scope.flightDetail=function(goodsId){
            var flight={
                goodsId:$scope.goodsId,
                date:$scope.date,
                fromCity:$scope.fromCity,
                toCity:$scope.toCity,
                fromPort:$scope.fromPort,
                toPort:$scope.toPort,
                leaveTime:$scope.leaveTime,
                reachTime:$scope.reachTime,
                company:$scope.company,
                flightId:$scope.flightId,
                price:$scope.price
            }
            UserInfo('flight_info',flight);
            $state.go('booking.flightOrder',{gid:goodsId});
        }
    }
})();