(function(){
    angular.module('mainBooking',[
        'ui.router',
        [
            'booking',
            'bookingFlight'
        ]
    ]).controller('mainBooking',mainBooking);

    function mainBooking($scope,$state,localStorageService){
        $scope.goPages=function(substate){
            $state.go('booking.'+substate,{userId:localStorageService.get('user')});
        }
    }
})();