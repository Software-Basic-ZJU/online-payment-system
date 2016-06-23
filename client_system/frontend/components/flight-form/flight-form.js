(function(){
    angular.module('flightForm',[
        'ui.router'
    ]).directive('flightForm',function(){
        return{
            templateUrl:'/frontend/components/flight-form/flight-form.html',
            controller:flightForm
        }
    });

    function flightForm($scope,$state,GetToday,$stateParams,Flash){
        $scope.today = GetToday();
        if($stateParams.from){
            $scope.isAble=true;
            $scope.flightForm={
                from:$stateParams.from,
                to:$stateParams.to,
                leaveTime:$stateParams.lvt,
                reachTime:$stateParams.rct,
            };
        }
        else {
            $scope.isAble = false;
            $scope.canChoiceBack = function () {
                $scope.isAble = true;
                $scope.flightForm.leaveTime = $scope.today;
            };
            $scope.flightForm = {
                leaveTime: $scope.today
            };
        }
        $scope.flightSearch=function(){
            if(!$scope.flightForm.from) return Flash.create('danger','出发地不能为空！');
            if(!$scope.flightForm.to) return Flash.create('danger','目的地不能为空！');
            $state.go('booking.flight',{
                from:$scope.flightForm.from,
                to:$scope.flightForm.to,
                lvt:$scope.flightForm.leaveTime,
                rct:$scope.flightForm.reachTime
            });
        };
    }
})();