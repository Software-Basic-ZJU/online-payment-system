(function(){
    angular.module('hotelDetail',[
        'ui.router',
        [
            'commentItem'
        ]
    ]).controller('hotelDetail',hotelDetail);

    function hotelDetail($scope,$timeout,$http,UserInfo,FormFilter,$stateParams){
        var roomList,rbegin=0,rend,commentList,cbegin=0,cend;
        $scope.isCommentLoaded=false;
        $scope.roomCurrPage=$scope.comCurrPage=1;
        $scope.roomPageSize=10;
        $scope.comPageSize=5;
        $scope.roomTotal=$scope.comTotal=0;
        $scope.roomTabs=JSON.stringify({
            tabsList:['房型','房价']
        });

        $scope.changeRoomPage=function(page){
            //console.log(page)
            //$scope.isLoaded=false;
            rbegin=(page-1)*$scope.roomPageSize;
            rend=rbegin+$scope.roomPageSize;
            $scope.roomList=roomList.slice(rbegin,rend);
        };

        $scope.changeComPage=function(page){
            //console.log(page)
            //$scope.isLoaded=false;
            cbegin=(page-1)*$scope.comPageSize;
            cend=cbegin+$scope.comPageSize;
            $scope.commentList=commentList.slice(cbegin,cend);
        };

        $http({
            method:'POST',
            url:'API/booking/roomDisplay.php',
            data:{
                token:UserInfo().token,
                hotel_id:$stateParams.hid,
                begin_date:$stateParams.it,
                end_date:$stateParams.ot
            }
        }).then(function(response){
            var resp=response.data;
            if(resp.code==0){
                $scope.hotel=resp.res.hotel;
                roomList=resp.res.roomList;
                if(!roomList.length) return $scope.hasNoRes=true;
                $scope.roomTotal=roomList.length;
                $scope.roomCurrPage=1;
                $scope.roomList=roomList.slice(rbegin,$scope.roomPageSize);
            }
        }).finally(function(){
            $scope.isRoomLoaded=true;
        });

        $http({
            method:'POST',
            url:'API/booking/look_comment.php',
            data:{
                token:UserInfo().token,
                hotel_id:$stateParams.hid
            }
        }).then(function(response){
            var resp=response.data;
            if(resp.code==0){
                commentList=resp.res.commentList;
                if(!commentList.length) return $scope.hasNoComRes=true;
                $scope.comTotal=commentList.length;
                $scope.comCurrPage=1;
                $scope.commentList=commentList.slice(cbegin,$scope.comPageSize);
            }
        }).finally(function(){
            $scope.isCommentLoaded=true;
        });

        $scope.hotelBooking=function(roomId,roomName,price){
            $scope.showModal=true;
            $scope.showHotelOrder=true;
            $scope.$broadcast('showHotel',{
                roomId:roomId,
                roomName:roomName,
                price:price
            });
        }
    }
})();