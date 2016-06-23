(function(){
    angular.module('homeLogin',[
        'ui.router',
    ]).directive('homeLogin',function(){
        return {
            templateUrl:'/frontend/components/modal-home/home-login/home-login.html',
            controller:homeLogin
        }
    });

    function homeLogin($scope,$http,$timeout,$state,FormFilter,Flash,UserInfo,GenerateCode,socket){
        $scope.logForm={
            userName:'',
            pswd:'',
            idenCode:''
        };
        $scope.isLoaded=true;
        $scope.isJumping=false;
        $scope.idenCode=GenerateCode();
        $scope.changeCode=function(){
            $scope.idenCode=GenerateCode();
        };
        $scope.goRegister=function(){
            $scope.$parent.isLogin=false;//父scope中
            $scope.$parent.isReg=true;
        };

        $scope.findPswd=function(){
            $scope.$parent.showFindPswd=true;
            $scope.$parent.isLogin=false;
            $scope.$parent.isReg=false;
        };
        $scope.login=function(){
            if(!FormFilter.voidFilter($scope.logForm)) return Flash.create('danger','输入项不能为空!');
            if(!FormFilter.sqlFilter($scope.logForm.userName)) return Flash.create('danger','用户名非法！');
            if(!FormFilter.sqlFilter($scope.logForm.pswd)) return Flash.create('danger','密码非法！');
            if($scope.idenCode.toLowerCase()!=$scope.logForm.idenCode.toLowerCase()){
                $scope.changeCode();
                return Flash.create('danger','验证码错误!');
            }
            $scope.isLoaded=false;
            $http({
                method:'POST',
                url:'API/pam/login.php',
                data:{
                    user_name:$scope.logForm.userName,
                    password:$scope.logForm.pswd
                }
            }).then(function success(response){
                var resp=response.data;
                if(resp.code==0){
                    UserInfo({
                        token:resp.res.token,
                        userName:resp.res.userName,
						userId:resp.res.userId,
                        isBuyer:resp.res.is_buyer
                    });
                    $scope.isJumping=true;
                    Flash.create('success',resp.msg);
                    $state.go('main',{userName:resp.res.userName});
                    // socket.emit('signin',resp.res.token,function(res){
                    //     if(res.code==0){
                    //         Flash.create('success',resp.msg);
                    //         $state.go('main',{userName:resp.res.userName});
                    //     }
                    //     else if(res.code==1){
                    //         Flash.create('danger','小伙子请不要绕过登录哦~');
                    //     }
                    //     else{
                    //         Flash.create('danger','不知道错哪了!');
                    //     }
                    // });
                }
            }).finally(function(){
                $scope.isLoaded=true;
                $scope.changeCode();
            })
        }
    }
})();