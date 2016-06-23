(function(){
    angular.module('modal',[
        'ui.router'
    ]).directive('modal',function(){
        return {
            templateUrl:'/frontend/components/modal/modal.html',
            transclude:true,
            controller:modal
        }
    });

    function modal($scope,$timeout){
    //    isLogin,isShow 继承父scope
        angular.element(document.getElementsByClassName('shelter')[0]).on('click',function(){
            $timeout(function() {
                $scope.showModal = false;
                $scope.showFindPswd=false;
            },0);
        });
        angular.element(document.getElementsByClassName('modal')[0]).on('click',function(e){e.preventDefault()});

    }
})();