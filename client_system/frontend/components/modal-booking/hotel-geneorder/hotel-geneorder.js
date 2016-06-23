(function(){
    angular.module('hotelGeneorder',[
        'ui.router'
    ]).directive('hotelGeneorder',function(){
        return {
            templateUrl:'/frontend/components/modal-booking/hotel-geneorder/hotel-geneorder.html',
            controller:hotelGeneorder
        }
    });

    function hotelGeneorder($scope,$http,UserInfo,FormFilter,Flash,$state,$stateParams,$timeout){
        $scope.$on('showHotel',function(event,data){
            $scope.roomId=data.roomId;
            $scope.roomName=data.roomName;
            $scope.price=data.price
        });

        $scope.hotelOrder={
            roomNum:'1',
            name:[],
        };

        $scope.nameList=[{}];
        $scope.$watch('hotelOrder.roomNum',function(newValue,oldValue){
            var delta=newValue-oldValue;
            if(delta>=0){
                for(var i=0;i<delta;i++){
                    $scope.nameList.push({});
                }
            }
            else{
                delta=-delta;
                for(var i=0;i<delta;i++){
                    $scope.nameList.pop();
                }
            }
        });
        $scope.isLoaded=true;
        $scope.submitOrder=function(roomId){
            if(!confirm("确定要预定吗？")) return false;
            if($scope.hotelOrder.name.length<$scope.hotelOrder.roomNum) return Flash.create('danger','入住人姓名不能为空!');
            for(var i=0;i<$scope.hotelOrder.name.length;i++){
                if(!FormFilter.sqlFilter($scope.hotelOrder.name[i])){
                    return Flash.create('danger','入住人姓名含有非法字符!');
                }
            }
            if(!$scope.hotelOrder.phone) return Flash.create('danger','手机号不能为空!');
            if(!FormFilter.sqlFilter($scope.hotelOrder.phone)) return Flash.create('danger','手机号含有非法字符!');
            $scope.isLoaded=false;
            $http({
                method:'POST',
                url:'API/booking/room_book.php',
                data:{
                    token:UserInfo().token,
                    room_id:roomId,
                    ticket_number:$scope.hotelOrder.roomNum,
                    begin_date:$stateParams.it,
                    end_date:$stateParams.ot
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