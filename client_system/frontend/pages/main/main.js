(function(){
    angular.module('main',[
        'ui.router',
        [
            'header',
            'mainAside',
            'modal',
            'homeFindpswd',
            'mainOrder'
        ]
    ]).controller('main',main);

    function main($scope,$stateParams,$state,UserInfo){
        var userName=$stateParams.userName;
        if(!userName || userName!=UserInfo().userName){
            $state.go('home');
        }

        watchData('Pay');
        watchData('Cancel');
        watchData('Complain');
        watchData('Refund');
        watchData('Confirm');
        watchData('Reply');

        function watchData(type){
            $scope.$on('emit'+type,function(event,data){
                $scope.$broadcast('show'+type,data);
            })
        }
    }
})();