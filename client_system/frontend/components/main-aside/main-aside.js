(function(){
    angular.module('mainAside',[
        'ui.router'
    ]).directive('mainAside',function(){
        return{
            templateUrl:'/frontend/components/main-aside/main-aside.html',
            controller:mainAside
        }
    });

    function mainAside($rootScope,$scope,$state,$http,UserInfo){
        $scope.state=$state.current.name.split(".")[1];
        $rootScope.$on('$stateChangeSuccess',function(){
            $scope.state=$state.current.name.split(".")[1];
        })

        $scope.logout=function(){
            UserInfo({});
            $state.go('home');
        }
    }
})();