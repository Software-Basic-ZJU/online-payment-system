(function(){
    angular.module('filterTabs',[
        'ui.router'
    ]).directive('filterTabs',function(){
        return{
            templateUrl:'/frontend/components/filter-tabs/filter-tabs.html',
            transclude:true,
            controller:filterTabs,
            scope:{
                parent:'@',
                tabs:'@',
                icon:'@',
                clickFunc:'@'
            }
        }
    });

    function filterTabs($scope){
        $scope.tabsList=JSON.parse($scope.tabs).tabsList;

        //增加复用性，采用事件发送广播机制
        $scope.filter=function(tab){
            $scope.filtType=tab;
            $scope.$emit('filter'+$scope.parent,tab)
        }
    }
})();