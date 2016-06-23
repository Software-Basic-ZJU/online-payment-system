(function(){
    angular.module('mainAccount',[
        'ui.router',
        [
            'modal',
            'accountCash',
            'accountRecharge',
            'accountBindcard',
            'accountNmverify',
            'homeFindpswd',
            'mdfyPswd',
            'mdfyPay',
            'setPay'
        ]
    ]).controller('mainAccount',mainAccount);

    function mainAccount($scope,$timeout,$http,UserInfo,Flash,FormFilter){
        $scope.isEdit=false;
        $scope.isLoaded=false;
        $http({
            method:'POST',
            url:'API/pam/view_information.php',
            data:{
                token:UserInfo().token
            }
        }).then(function(response){
            var resp=response.data;
            if(resp.code==0){
                UserInfo('token',resp.res.token);
                UserInfo('userType',resp.res.is_buyer);
                $scope.personInfo={
                    email:resp.res.email,
                    gender:resp.res.gender,
                    userType:resp.res.is_buyer,
                    phone:resp.res.phone_number || '暂无',
                    balance:resp.res.balance,
                    userName:resp.res.userName,
                    //realName:resp.res.name || '请实名认证',
                    idenState:resp.res.identity_state,
                    exp:resp.res.vip_exp,
                    level:getLevel(resp.res.vip_exp),
                    cardList:resp.res.cards_info
                };
                if($scope.personInfo.idenState==1) $scope.personInfo.realName=resp.res.name;
                else if($scope.personInfo.idenState==0) $scope.personInfo.realName='等待审核中...';
                else {
                    $scope.personInfo.notIden=true;
                    $scope.personInfo.idenState=false;
                    $scope.personInfo.realName='请实名认证';
                }

                $scope.isSetPay=resp.res.isSetPay?true:false;
            }
        }).finally(function(){
            $scope.isLoaded=true;
        });


        $scope.edit=function(){
            $scope.isEdit=true;
        };
        $scope.editSubmit=function(){
            var reg=/^[0-9]+?/;
            if(!reg.test($scope.personInfo.phone)) return Flash.create('danger','电话号码格式不正确!');
            if(!FormFilter.sqlFilter($scope.personInfo.phone)) return Flash.create('danger','电话号码中含有非法字符');
            $http({
                method:'POST',
                url:'API/pam/change_info.php',
                data:{
                    token:UserInfo().token,
                    gender:$scope.personInfo.gender,
                    phone_number:$scope.personInfo.phone
                }
            }).then(function success(response){
                var resp=response.data;
                if(resp.code==0){
                    UserInfo('token',resp.res.token);
                    Flash.create('success',resp.msg);
                    $scope.isEdit=false;
                }
            })
        };
        $scope.goModal=function(type){
            $scope.showModal=true;
            modalShow(type)
        };

        function modalShow(type){
            $scope.showCash=false;
            $scope.showRecharge=false;
            $scope.showBindCard=false;
            $scope.showSetPay=false;
            $scope.showMdfyPay=false;
            $scope.showMdfyPswd=false;
            $scope.showNameVerify=false;
            $scope.showFindPswd=false;
            $scope['show'+type]=true;
        }

        function getLevel(exp){
            var level=0;
            if(exp>=300 && exp<500) level=1;
            else if(exp>=500 && exp<800) level=2;
            else if(exp>=800&& exp<1200) level=3;
            else if(exp>=1200 && exp<1700) level=4;
            else if(exp>=1700 && exp<2300) level=5;
            else if(exp>=2300 && exp<3000) level=6;
            else if(exp>=3000 && exp<3800) level=7;
            else if(exp>=3800 && exp<4700) level=8;
            else if(exp>=4700) level=9;

            return level;
        }
    }
})();