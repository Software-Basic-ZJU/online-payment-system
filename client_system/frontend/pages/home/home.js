(function(){
    angular.module('home',[
        'ui.router',
        [
            'modal',
            'homeLogin',
            'homeRegister',
            'homeFindpswd',
            'main',
        ]
    ]).controller('home',home);

    function home($scope){
        $scope.showModal=false;
        $scope.showLogin=function(){
            $scope.showModal=true;
            $scope.isLogin=true;
            $scope.isReg=false;
        };
        $scope.showRegister=function(){
            $scope.showModal=true;
            $scope.isLogin=false;
            $scope.isReg=true;
        };
        
    }
})();