(function(){
    angular.module('commentItem',[
        'ui.router'
    ]).directive('commentItem',function(){
        return{
            templateUrl:'/frontend/components/comment-item/comment-item.html',
            scope:{
                userName:'@',
                roomName:'@',
                score:'@',
                comment:'@'
            },
            controller:commentItem
        }
    });

    function commentItem($scope){
        $scope.scoreNum=[];
        for(var i=0;i<parseInt($scope.score);i++){
            $scope.scoreNum.push({});
        }
    }
})();