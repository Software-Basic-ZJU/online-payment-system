(function(){
    angular.module('mdfyPswd',[
        'ui.router'
    ]).directive('mdfyPswd',function(){
        return{
            templateUrl:'/frontend/components/modal-account/account-mdfypswd/account-mdfypswd.html',
            controller:mdfyPswd
        }
    });

    function mdfyPswd($scope,$http,UserInfo,Flash,$timeout,$state,FormFilter){
        $scope.mdfyPswdForm={
            oldPswd:'',
            newPswd:'',
            confirmNew:''
        };
        $scope.goMdfyPswd=function(e){
            e.preventDefault();
        }
        $scope.isLoaded=true;
        $scope.goMdfyPswd=function(){
            if(!FormFilter.voidFilter($scope.mdfyPswdForm))
                return Flash.create('danger','密码不能为空!');
            if($scope.mdfyPswdForm.oldPswd==$scope.mdfyPswdForm.newPswd) return Flash.create('danger','新密码不得与旧密码相同!');
            if($scope.mdfyPswdForm.newPswd.length < 6) return Flash.create('danger','密码至少为6位！');
            if(!FormFilter.sqlFilter($scope.mdfyPswdForm.newPswd)) return Flash.create('danger','密码不合法！');
            if($scope.mdfyPswdForm.confirmNew!=$scope.mdfyPswdForm.newPswd) return Flash.create('danger','两次密码输入不一致!')
            //console.log($scope.mdfyPswdForm)
            $scope.isLoaded=false;
            $http({
                method:'POST',
                url:'API/pam/change_password.php',
                data:{
                    token:UserInfo().token,
                    type:'login',
                    old:$scope.mdfyPswdForm.oldPswd,
                    new1:$scope.mdfyPswdForm.newPswd,
                    new2:$scope.mdfyPswdForm.confirmNew
                }
            }).then(function(response){
                var resp=response.data;
                if(resp.code==0){
                    Flash.create('success',resp.msg+"3秒后请重新登录.");
                    angular.element(document.getElementsByClassName('modal')[0]).triggerHandler('click');
                    $timeout(function(){
                        $state.go('home');
                    },3000);
                }
            }).finally(function(){
                $scope.isLoaded=true;
            })
        }
    }
})();