(function(){
    angular.module('setPay',[
        'ui.router'
    ]).directive('setPay',function(){
        return{
            templateUrl:'/frontend/components/modal-account/account-setpay/account-setpay.html',
            controller:setPay
        }
    })

    function setPay($scope,$http,Flash,FormFilter,UserInfo,$state){
        $scope.setPayForm={
            loginPswd:'',
            newPswd:'',
            confirmNew:''
        };
        $scope.isLoaded=true;
        $scope.goSetPay=function(){
            if(!FormFilter.voidFilter($scope.setPayForm))
                return Flash.create('danger','密码不能为空!');
            if(!FormFilter.sqlFilter($scope.setPayForm.newPswd)) return Flash.create('danger','密码含有非法字符!');
            $scope.isLoaded=false;
            $http({
                method:'POST',
                url:'API/pam/set_transaction_psw.php',
                data:{
                    token:UserInfo().token,
                    login_password:$scope.setPayForm.loginPswd,
                    new1:$scope.setPayForm.newPswd,
                    new2:$scope.setPayForm.confirmNew
                }
            }).then(function(response){
                var resp=response.data;
                if(resp.code==0){
                    UserInfo('token',resp.res.token);
                    $scope.$parent.isSetPay=true;
                    Flash.create('success',resp.msg);
                    $state.reload();
                }
            }).finally(function(){
                $scope.isLoaded=true;
            })
        }
    }
})();