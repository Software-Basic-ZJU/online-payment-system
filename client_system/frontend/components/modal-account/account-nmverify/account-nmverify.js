(function(){
    angular.module('accountNmverify',[
        'ui.router'
    ]).directive('accountNmverify',function(){
        return{
            templateUrl:'/frontend/components/modal-account/account-nmverify/account-nmverify.html',
            controller:accountNmverify
        }
    });

    function accountNmverify($scope,$http,UserInfo,FormFilter,Flash,$state){
        $scope.verifyForm={};
        $scope.isLoaded=true;
        $scope.goVerify=function() {
            if(!FormFilter.voidFilter($scope.verifyForm)) return Flash.create('danger','输入项不得为空');
            if(!FormFilter.sqlFilter($scope.verifyForm.certiId)) return Flash.create('danger','充值卡号含有非法字符!');
            if(!FormFilter.sqlFilter($scope.verifyForm.name)) return Flash.create('danger','真实姓名含有非法字符!');
            $scope.isLoaded=false;
            $http({
                method: 'POST',
                url: 'API/pam/verify_user.php',
                data: {
                    token:UserInfo().token,
                    name:$scope.verifyForm.name,
                    identity_card:$scope.verifyForm.certiId
                }
            }).then(function(response){
                var resp=response.data;
                if(resp.code==0){
                    $scope.$parent.personInfo.idenState=1;
                    Flash.create('success',resp.msg);
                    $state.reload();
                }
            }).finally(function(){
                $scope.isLoaded=true;
            })
        }
    };
})();