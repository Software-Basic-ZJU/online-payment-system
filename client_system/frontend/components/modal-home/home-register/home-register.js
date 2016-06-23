(function(){
    angular.module('homeRegister',[
        'ui.router'
    ]).directive('homeRegister',function(){
        return {
            templateUrl:'/frontend/components/modal-home/home-register/home-register.html',
            controller:homeRegister
        }
    })

    function homeRegister($scope,$http,Flash,FormFilter){
        $scope.regForm={
            type:'buyer',
            gender:'M',
            userName:'',
            pswd:'',
            confirmPswd:'',
            email:'',
        };
        $scope.emailVerify=false;
        $scope.isLoaded=true;
        $scope.goLogin=function(){
            $scope.$parent.isLogin = true;//父scope中
            $scope.$parent.isReg=false;
        };
        $scope.goMailVerify=function(address){
            window.open(address);
        };
        $scope.resend=function(){
            $http({
                method:'POST',
                url:'API/pam/resendEmail.php',
                data:{
                    user_name:$scope.regForm.userName,
                    email:$scope.regForm.email
                }
            }).then(function(response){
                var resp=response.data;
                if(resp.code==0){
                    Flash.create('success',resp.msg);
                }
            })
        };

        $scope.register=function(){
            if(!FormFilter.voidFilter($scope.regForm)) return Flash.create('danger','输入项不能为空!');
            if($scope.regForm.userName.length < 8) return Flash.create('danger','用户名至少为8位！');
            if(!FormFilter.sqlFilter($scope.regForm.userName)) return Flash.create('danger','用户名不合法！');
            if($scope.regForm.pswd.length < 6) return Flash.create('danger','密码至少为6位！');
            if(!FormFilter.sqlFilter($scope.regForm.pswd)) return Flash.create('danger','密码不合法！');
            if($scope.regForm.confirmPswd!=$scope.regForm.pswd) return Flash.create('danger','确认密码不相同，请重新输入！');
            if(!FormFilter.emailFilter($scope.regForm.email)) return Flash.create('danger','请输入正确的邮箱！');
            $scope.isLoaded=false;
            $http({
                method:'POST',
                url:'API/pam/register.php',
                data:{
                    user_name:$scope.regForm.userName,
                    password:$scope.regForm.pswd,
                    email:$scope.regForm.email,
                    user_type:$scope.regForm.type,
                    gender:$scope.regForm.gender
                }
            }).then(function success(response){
                var resp=response.data;
                if(resp.code==0){
                    $scope.emailVerify=true;
                    var tmb=$scope.regForm.email.indexOf('@');
                    var emlength=$scope.regForm.email.length;
                    $scope.mailAddress='http://mail.'+$scope.regForm.email.substring(tmb+1,emlength);
                    Flash.create('success',resp.msg);
                }
            }).finally(function(){
                $scope.isLoaded=true;
            });
        }
    }
})();