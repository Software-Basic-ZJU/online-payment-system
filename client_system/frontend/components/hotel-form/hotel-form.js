(function(){
    angular.module('hotelForm',[
        'ui.router'
    ]).directive('hotelForm',function(){
        return {
            templateUrl:'/frontend/components/hotel-form/hotel-form.html',
            controller:hotelForm
        }
    });

    function hotelForm($scope,$state,GetToday,$stateParams,Flash){
        $scope.today = GetToday();
        if($stateParams.des){
            $scope.hotelForm={
                destination:$stateParams.des,
                checkInTime:$stateParams.it,
                checkOutTime:$stateParams.ot,
                star:$stateParams.st,
                price:$stateParams.pr,
                score:$stateParams.sr
            };
        }
        else {
            $scope.hotelForm = {
                star: 'all',
                price: 'all',
                score: 'all',
                checkInTime: $scope.today,
                checkOutTime: $scope.today
            };
        }
        $scope.hotelSearch=function(){
            if(!$scope.hotelForm.destination) return Flash.create('danger','目的地不能为空！');
            $state.go('booking.hotel',{
                des:$scope.hotelForm.destination,
                it:$scope.hotelForm.checkInTime,
                ot:$scope.hotelForm.checkOutTime,
                st:$scope.hotelForm.star,
                pr:$scope.hotelForm.price,
                sr:$scope.hotelForm.score
            })
        };
    }
})();