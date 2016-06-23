(function(){
    angular.module('hotelDsitem',[
        'ui.router'
    ]).directive('hotelDsitem',function(){
        return {
            templateUrl:'/frontend/components/hotel-dsitem/hotel-dsitem.html',
            scope:{
                roomId:'@',
                hotelName:'@',
                roomType:'@',
                star:'@',
                score:'@',
                address:'@',
                discRate:'@',
                discPrice:'@'
            },
            controller:hotelDsitem
        }
    });

    function hotelDsitem($scope,$state,$timeout) {
        $scope.dRoomBook = function (roomId,roomName,price) {
            $timeout(function(){
                $scope.$parent.$parent.showModal = true;
                $scope.$parent.$parent.showHotelOrder = true;
                $scope.$emit('showRecomHotel', {
                    roomId: roomId,
                    roomName: roomName,
                    price: price
                });
            },0)
        }
    }
})();