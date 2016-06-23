(function(){
    angular.module('flightOrder',[
        'ui.router',
        [
            'flightItem'
        ]
    ]).controller('flightOrder',flightOrder);

    function flightOrder($scope,$timeout,Flash,$http,UserInfo,FormFilter) {
        $scope.flight=UserInfo().flight_info;
        $scope.isLoaded=true;
        $scope.psgList=[{
            idenType:'certificate'
        }];
        
        $scope.addPsg=function(){
            if($scope.psgList.length>=5){
                return Flash.create('warning','一次最多购买五张票!');
            }
            $scope.psgList.push({
                idenType:'certificate'
            });
        };
        $scope.removePsg=function(index){
            if($scope.psgList.length==1) return;
            $scope.psgList.splice(index,1);
        }

        $scope.submitOrder=function(goodsId){
            if(!confirm("确认要预订吗？")) return false;
            var reg=/^[1-9A-Za-z]+?/;
            for(var i=0;i<$scope.psgList.length;i++){
                if(!$scope.psgList[i].name || !$scope.psgList[i].type || !$scope.psgList[i].idenText){
                    $scope.isLoaded=true;
                    return Flash.create('danger','乘客信息不能为空!');
                }
                for(var j in $scope.psgList[i]){
                    if(!FormFilter.sqlFilter($scope.psgList[i][j])) {
                        $scope.isLoaded=true;
                        return Flash.create('danger', '输入项含有非法字符!');
                    }
                    if(!reg.test($scope.psgList[i].idenText)) {
                        $scope.isLoaded=true;
                        return Flash.create('danger','证件格式不正确!');
                    }
                }
            }
            $scope.isLoaded=false;
            $http({
                method:'POST',
                url:'API/booking/flight_book.php',
                data:{
                    token:UserInfo().token,
                    ticket_number:$scope.psgList.length,
                    flight_id:goodsId
                }
            }).then(function(response){
                var resp=response.data;
                if(resp.code==0){
                    Flash.create('success',resp.msg);
                    $timeout(function(){
                        history.back();
                    },1200);
                }
            }).finally(function(){
                $scope.isLoaded=true;
            })
        }

    }
})();