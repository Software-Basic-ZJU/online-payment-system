(function(){
    angular.module('orderReply',[
        'ui.router',
        [
            'starPicker'
        ]
    ]).directive('orderReply',function(){
        return {
            templateUrl:'/frontend/components/modal-order/order-reply/order-reply.html',
            controller:orderReply
        }
    });

    function orderReply($scope,$http,UserInfo,Flash,$timeout,$state,FormFilter){
        $scope.reply={
            content:'',
            score:0
        };
        $scope.$on('showReply',function(event,data){
            $scope.isCheckLoaded=false;
            $scope.noReplied=false;
            $scope.hasReplied=false;
            $scope.orderId=data.orderId;
            $scope.orderContent=data.orderContent;
            $http({
                method:'POST',
                url:'API/order/checkComment.php',
                data:{
                    token:UserInfo().token,
                    order_id:$scope.orderId
                }
            }).then(function(response){
                var resp=response.data;
                if(resp.code==0){
                    if(resp.res.ifComment) $scope.hasReplied=true;
                    else $scope.noReplied=true;
                    $scope.hotelId=resp.res.hotel_id;
                    $scope.roomId=resp.res.room_id;
                }
            }).finally(function(){
                $scope.isCheckLoaded=true;
            });

        });

        $scope.submitReply=function(){
            if(!confirm('确认提交评价吗?')) return false;
            if(!$scope.reply.content) return Flash.create('danger','您的评价还没有内容呢!');
            if(!FormFilter.sqlFilter($scope.reply.content)) return Flash.create('danger','内容含有非法字符!');
            $scope.isLoaded=false;
            $http({
                method:'POST',
                url:'API/booking/insertComment.php',
                data:{
                    token:UserInfo().token,
                    hotel_id:$scope.hotelId,
                    room_id:$scope.roomId,
                    score:$scope.reply.score,
                    comment:$scope.reply.content
                }
            }).then(function(response){
                var resp=response.data;
                if(resp.code==0){
                    Flash.create('success',resp.msg);
                    $timeout(function(){
                        $state.reload();
                    },800);
                }
            }).finally(function(){
                $scope.isLoaded=true;
            })
        }
    }
})();