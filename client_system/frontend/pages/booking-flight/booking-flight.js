(function(){
    angular.module('bookingFlight',[
        'ui.router',
        [
            'flightForm',
            'flightItem',
            'flightDsitem'
        ]
    ]).controller('bookingFlight',bookingFlight);

    function bookingFlight($scope,$state,$timeout,$http,FormFilter,Flash,UserInfo,CompTime){
        var flightList,begin= 0,end;
        var tabs={
            tabsList:['价格','出发时间']
        };
        $scope.flightTabs=JSON.stringify(tabs);
        $scope.pageSize=10;
        $scope.total=0;
        end=$scope.pageSize;
        $scope.hasNoRes=false;
        $scope.transfer='all';
        //接受filter-tabs组件的事件，模拟点击事件
        $scope.$on('filterFlight',function(event,data){
            if($scope.tab==data) return false;
            $scope.tab=data;
            if($scope.tab=='价格'){
                flightList.sort(function(a,b){
                    return a.price - b.price;
                })
            }
            else if($scope.tab=='出发时间'){
                flightList.sort(function(a,b){
                    var atime= a.leaveTime.split(':');
                    var btime= b.leaveTime.split(':');
                    if(atime[0]==btime[0]){
                        if(atime[1]==btime[1]) return atime[2]-btime[2];
                        else return atime[1]-btime[1];
                    }
                    else return atime[0]-btime[0];
                })
            }
            $scope.currPage = 1;
            $scope.flightList = flightList.slice(begin, end);
        });
        $scope.changePage=function(page){
            //console.log(page)
            //$scope.isLoaded=false;
            begin=(page-1)*$scope.pageSize;
            end=begin+$scope.pageSize;
            $scope.flightList=flightList.slice(begin,end);
        };

        $scope.changeStop=function(transfer){
            if(transfer=='all') return $scope.flightList=flightList;
            var filterList=[];
            for(var i=0;i<flightList.length;i++){
                if(!flightList[i].ifStop || flightList[i].ifStop==0){
                    filterList.push(flightList[i])
                }
            }
            $scope.flightList=filterList;
        }

        $scope.$on('$stateChangeSuccess',function(event,toState,toParams){
            var bgtime,edtime;
            if(toParams.from && toParams.to && toParams.lvt){
                $scope.isSearch=true;
                $scope.isListLoaded=false;
                $scope.isRecomLoaded=false;
                $scope.hasNoRes=false;
                $scope.hasRecomNoRes=false;
                if(!FormFilter.sqlFilter(toParams.from)) return Flash.create('danger','出发城市名称含有非法字符!');
                if(!FormFilter.sqlFilter(toParams.to)) return Flash.create('danger','到达城市含有非法字符!');
                if(!FormFilter.sqlFilter(toParams.lvt)) return Flash.create('danger','出发时间含有非法字符!');
                if(!FormFilter.sqlFilter(toParams.rct)) return Flash.create('danger','含有非法字符!');

                $http({
                    method:'POST',
                    url:'API/booking/queryFlights.php',
                    data:{
                        token:UserInfo().token,
                        begin_city:toParams.from,
                        end_city:toParams.to,
                        begin_time:toParams.lvt,
                        end_time:toParams.rct || ''
                    }
                }).then(function(response){
                    var resp=response.data;
                    if(resp.code==0){
                        if(!resp.res.flight_list.length) $scope.hasNoRes=true;
                        else {
                            flightList = resp.res.flight_list;
                            for (var i = 0; i < flightList.length; i++) {
                                bgtime = flightList[i].beginTime.split(' ');
                                edtime = flightList[i].endTime.split(' ');
                                flightList[i].date = bgtime[0];
                                flightList[i].leaveTime = bgtime[1];
                                flightList[i].reachTime = edtime[1];
                            }
                        }
                        if(!$scope.hasNoRes) {
                            $scope.currPage = 1;
                            $scope.total = flightList.length;
                            $scope.flightList = flightList.slice(begin, end);
                        }
                        if(!resp.res.recom_flight.length) $scope.hasRecomNoRes=true;
                        else{
                            $scope.recomFlightList=resp.res.recom_flight.slice(0,8);
                            for (var i = 0; i < $scope.recomFlightList.length; i++) {
                                bgtime = $scope.recomFlightList[i].beginTime.split(' ');
                                edtime = $scope.recomFlightList[i].endTime.split(' ');
                                $scope.recomFlightList[i].leaveTime = bgtime[1];
                                $scope.recomFlightList[i].reachTime = edtime[1];
                            }
                        }

                    }
                }).finally(function(){
                    $scope.isListLoaded=true;
                    $scope.isRecomLoaded=true;
                });

            }
        });

    }
})();