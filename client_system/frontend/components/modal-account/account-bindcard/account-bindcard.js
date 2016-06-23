(function(){
    angular.module('accountBindcard',[
        'ui.router'
    ]).directive('accountBindcard',function(){
        return{
            templateUrl:'/frontend/components/modal-account/account-bindcard/account-bindcard.html',
            controller:accountBindcard
        }
    });

    function accountBindcard($scope,$http,FormFilter,UserInfo,Flash,$state){
        $scope.bindForm={
            bank:'china',
            cardId:'',
            pswd:''
        };
        $scope.isLoaded=true;
        $scope.goBindCard=function(){
            if(!FormFilter.voidFilter($scope.bindForm)) return Flash.create('danger','输入项不得为空!');
            if($scope.bindForm.cardId.length!=20) return Flash.create('danger','银行卡号必须为20位!');
            if($scope.bindForm.pswd.length!=6) return Flash.create('danger','银行卡密码必须为6位!');
            if(!FormFilter.sqlFilter($scope.bindForm.cardId)) return Flash.create('danger','卡号含有非法字符!');
            if(!FormFilter.sqlFilter($scope.bindForm.pswd)) return Flash.create('danger','卡密码含有非法字符!');
            $scope.isLoaded=false;
            $http({
                method:'POST',
                url:'API/pam/add_card.php',
                data:{
                    token:UserInfo().token,
                    card_id:$scope.bindForm.cardId,
                    password:$scope.bindForm.pswd
                }
            }).then(function(response){
                var resp=response.data;
                if(resp.code==0){
                    Flash.create('success',resp.msg);
                    $scope.$parent.personInfo.cardList.push($scope.bindForm.cardId);
                    $state.reload();
                }
                $scope.bindForm={};
            }).finally(function(){
                $scope.isLoaded=true;
            })
        }
    }
})();