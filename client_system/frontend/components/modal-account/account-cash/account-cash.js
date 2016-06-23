(function(){
    angular.module('accountCash',[
        'ui.router'
    ]).directive('accountCash',function(){
        return{
            templateUrl:'/frontend/components/modal-account/account-cash/account-cash.html',
            controller:accountCash
        }
    });

    function accountCash($scope){
        $scope.cashForm={
            cardId:'123456123456',
        };
        $scope.cards=['123456123456','654323232321'];

        $scope.goGetCash=function(){

        }
    }
})();