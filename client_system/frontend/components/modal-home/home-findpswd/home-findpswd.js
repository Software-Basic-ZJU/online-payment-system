(function(){
    angular.module('homeFindpswd',[
        'ui.router'
    ]).directive('homeFindpswd',function(){
        return {
            templateUrl:'/frontend/components/modal-home/home-findpswd/home-findpswd.html',
            controller:accountFindpswd
        }
    });

    function accountFindpswd($scope,$http,Flash,UserInfo,FormFilter,$state,$timeout){
        $scope.sendTime=0;
        $scope.findForm={
            email:'',
            code:'',
            pswd:'',
            confirmPswd:'',
            type:'login'
        };
        $scope.stateName='发送邮件';
        $scope.firstStep=true;
        $scope.sendEmail=function(){
            var time=Math.floor(new Date().getTime()/1000);
            if(time-$scope.sendTime<60){
                var delTime=60-time+$scope.sendTime;
                return Flash.create('danger','请在'+delTime+'秒后再点击发送!');
            }
            if(!$scope.findForm.email) return Flash.create('danger','邮箱不能为空');
            if(!FormFilter.emailFilter($scope.findForm.email)) return Flash.create('danger','邮箱中含有非法字符!');
            $scope.stateName="正在发送";
            $http({
                method:'POST',
                url:'API/pam/codes.php',
                data:{
                    email:$scope.findForm.email
                }
            }).then(function(response){
                var resp=response.data;
                if(resp.code==0){
                    UserInfo('emailCode',resp.res.codes);
                    Flash.create('success','邮件已发送.请填写邮件中的验证码!');
                    $scope.sendTime=Math.floor(new Date().getTime()/1000);
                }
            }).finally(function(){
                $scope.stateName='发送邮件';
            })
        };

        $scope.goFindPswd=function(){
            if(!FormFilter.voidFilter($scope.findForm)) return Flash.create('danger','输入项不能为空!');
            if(!FormFilter.sqlFilter($scope.findForm.pswd)) return Flash.create('danger','密码中含有非法字符!');
            if(!FormFilter.sqlFilter($scope.findForm.code)) return Flash.create('danger','验证码含有非法字符!');
            if($scope.findForm.pswd!=$scope.findForm.confirmPswd) return Flash.create('danger','确认密码不一致!');
            $scope.isLoaded=false;
            $http({
                method:'POST',
                url:'API/pam/verify_codes.php',
                data:{
                    email:$scope.findForm.email,
                    codes:$scope.findForm.code,
                    encrypt_codes:UserInfo().emailCode,
                    type:$scope.findForm.type,
                    new1:$scope.findForm.pswd,
                    new2:$scope.findForm.confirmPswd
                }
            }).then(function(response){
                var resp=response.data;
                if(resp.code==0){
                    Flash.create('success','修改密码成功!请返回登录!');
                    $timeout(function(){
                        $state.reload();
                    },1500);
                }
            }).finally(function(){
                $scope.isLoaded=true;
            })
        }
    }
})();