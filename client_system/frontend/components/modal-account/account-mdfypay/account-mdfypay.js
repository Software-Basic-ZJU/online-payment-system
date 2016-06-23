(function(){
    angular.module('mdfyPay',[
        'ui.router'
    ]).directive('mdfyPay',function(){
        return {
            templateUrl:'/frontend/components/modal-account/account-mdfypay/account-mdfypay.html',
            controller:mdfyPay
        }
    });

    function mdfyPay($scope,$http,UserInfo,FormFilter,Flash,$state){
        $scope.mdfyPayForm={
            oldPswd:'',
            newPswd:'',
            confirmNew:''
        };
        $scope.isLoaded=true;
        $scope.goMdfyPay=function(){
            $scope.isLoaded=false;
            if(!FormFilter.voidFilter($scope.mdfyPayForm))
                return Flash.create('danger','密码不能为空!');
            if($scope.mdfyPayForm.oldPswd==$scope.mdfyPayForm.newPswd) return Flash.create('danger','新旧密码不能相同!');
            if(!FormFilter.sqlFilter($scope.mdfyPayForm.oldPswd)) return Flash.create('danger','旧密码含有非法字符!');
            if(!FormFilter.sqlFilter($scope.mdfyPayForm.newPswd)) return Flash.create('danger','新密码含有非法字符!');
            if($scope.mdfyPayForm.newPswd!=$scope.mdfyPayForm.confirmNew) return Flash.create('danger','确认密码输入不正确');
            $scope.isLoaded=false;
            $http({
                method:'POST',
                url:'API/pam/change_password.php',
                data:{
                    token:UserInfo().token,
                    type:'transaction',
                    old:$scope.mdfyPayForm.oldPswd,
                    new1:$scope.mdfyPayForm.newPswd,
                    new2:$scope.mdfyPayForm.confirmNew
                }
            }).then(function(response){
                var resp=response.data;
                if(resp.code==0){
                    Flash.create('success',resp.msg);
                    $state.reload();
                }
            }).finally(function(){
                $scope.isLoaded=true;
            })
        }
    }
})();