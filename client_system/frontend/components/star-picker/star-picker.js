(function(){
    angular.module('starPicker',[
        'ui.router'
    ]).directive('starPicker',function(){
        return {
            templateUrl:'/frontend/components/star-picker/star-picker.html',
            scope:{
                starNum:'='
            },
            controller:starPicker
        }
    });

    function starPicker($scope){
        $scope.stars=[];
        for(var i=0;i<5;i++){
            $scope.stars.push({
                selected:false
            })
        }

        //重绘选中星星数量
        $scope.starSel=function(num){
            var i;
            for(i=0;i<5;i++){
                $scope.stars[i].selected=false;
            }
            for(i=0;i<num+1;i++){
                $scope.stars[i].selected=true;
            }
            $scope.starNum=num+1;
        }
    }
})();