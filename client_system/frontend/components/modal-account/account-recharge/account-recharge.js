(function(){
    angular.module('accountRecharge',[
        'ui.router'
    ]).directive('accountRecharge',function(){
        return {
            templateUrl:'/frontend/components/modal-account/account-recharge/account-recharge.html',
            controller:accountRecharge
        }
    })

    function accountRecharge($scope,GenerateCode,$http,UserInfo,FormFilter,Flash,$state){
        $scope.recForm={
            cardId:'',
            pswd:'',
            idenCode:''
        };
        $scope.idenCode=GenerateCode();
        $scope.changeCode=function(){
            $scope.idenCode=GenerateCode();
        };
        $scope.isLoaded=true;
        $scope.goRecharge=function(){
            if(!FormFilter.voidFilter($scope.recForm)) return Flash.create('danger','输入项不能为空');
            if(!FormFilter.sqlFilter($scope.recForm.cardId)) return Flash.create('danger','充值卡号含有非法字符!');
            if(!FormFilter.sqlFilter($scope.recForm.pswd)) return Flash.create('danger','充值卡密码含有非法字符!');
            if($scope.idenCode.toLowerCase()!=$scope.recForm.idenCode.toLowerCase()){
                $scope.changeCode();
                return Flash.create('danger','验证码错误!');
            }
            $scope.isLoaded=false;
            $http({
                method:'POST',
                url:'API/pam/use_prepaid_card.php',
                data:{
                    token:UserInfo().token,
                    card_id:$scope.recForm.cardId,
                    password:$scope.recForm.pswd
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