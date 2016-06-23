(function(){
    angular.module('booking',[
        'ui.router',
        [
            'datePicker',
            '720kb.datepicker',
            'filterTabs',
            'paging',
            'bw.paging',
            'modal',
            'hotelGeneorder'
        ]
    ]).controller('booking',booking);

    function booking($scope,$state,UserInfo){
        $scope.goMain=function(){
            $state.go('main',{userName:UserInfo().userName});
        }
    }
})();