(function(){
    angular.module('bookingHotel',[
        'ui.router',
        [
            'hotelItem',
            'hotelForm',
            'hotelDsitem',
        ]
    ]).controller('bookingHotel',bookingHotel);

    function bookingHotel($scope,$state,$timeout,GetToday,$http,FormFilter,UserInfo){
        var begin= 0,end,hotelList;
        $scope.isSearch=false;
        $scope.pageSize=10;
        $scope.currPage=1;
        $scope.total=0;
        end=$scope.pageSize;
        var tabs={
            tabsList:['最受欢迎','评分','价格','星级']
        };
        $scope.hotelTabs=JSON.stringify(tabs);
        //接受filter-tabs组件的事件，模拟点击事件
        $scope.$on('filterHotel',function(event,data){
            if($scope.tab==data) return false;
            $scope.tab=data;
            $scope.currPage=1;
            if($scope.tab=='最受欢迎'){
                hotelList.sort(function(a,b){
                    return b.replyNum - a.replyNum;
                })
            }
            else if($scope.tab=='评分'){
                hotelList.sort(function(a,b){
                    return b.score - a.score;
                })
            }
            else if($scope.tab=='价格'){
                hotelList.sort(function(a,b){
                    return a.lowestPrice - b.lowestPrice;
                })
            }
            else if($scope.tab=='星级'){
                hotelList.sort(function(a,b){
                    return b.star - a.star;
                })
            }
            $scope.hotelList=hotelList.slice(0,$scope.pageSize);
        })

        $scope.changePage=function(page){
            //console.log(page)
            //$scope.isLoaded=false;
            begin=(page-1)*$scope.pageSize;
            end=begin+$scope.pageSize;
            $scope.hotelList=hotelList.slice(begin,end);
        };

        //-----监听URL变化并查询商品
        $scope.$on('$stateChangeSuccess',function(event,toState,toParams){
            if(toParams.des && toParams.it && toParams.ot){
                $scope.isSearch=true;
                $scope.isListLoaded=false;
                $scope.isRecomLoaded=false;
                $scope.hasNoRes=false;
                $scope.recomHasNoRes=false;
            //  执行搜索逻辑
                if(!FormFilter.sqlFilter(toParams.des)) return Flash.create('danger','出发城市名称含有非法字符!');
                if(!FormFilter.sqlFilter(toParams.it)) return Flash.create('danger','到达城市含有非法字符!');
                if(!FormFilter.sqlFilter(toParams.ot)) return Flash.create('danger','出发时间含有非法字符!');

                $http({
                    method:'POST',
                    url:'API/booking/queryHotels.php',
                    data:{
                        token:UserInfo().token,
                        place:toParams.des,
                        star_option:transferStar(toParams.st),
                        price_option:transferPrice(toParams.pr),
                        score_option:transferScore(toParams.sr)
                    }
                }).then(function(response){
                    var resp=response.data;
                    if(resp.code==0){
                        hotelList=resp.res.hotel_list;
                        if(!hotelList.length) {
                            $scope.recomHasNoRes=true;
                            return $scope.hasNoRes=true;
                        }
                        $scope.currPage=1;
                        $scope.total=hotelList.length;
                        $scope.recomHotelList=resp.res.discount_hotel_list;
                        $scope.hotelList=hotelList.slice(0,$scope.pageSize);
                        $scope.recomHotelList=$scope.recomHotelList.slice(0,6);
                        if(!$scope.recomHotelList.length) $scope.recomHasNoRes=true;
                    }
                }).finally(function(){
                    $scope.isListLoaded=true;
                    $scope.isRecomLoaded=true;
                })

            }
        });

        $scope.$on('showRecomHotel',function(event,data){
            $scope.$broadcast('showHotel',data);
        })

        function transferStar(star){
            var tmp=0;
            switch(star){
                case 'all':tmp=0;break;
                case 'two':tmp=1;break;
                case 'three':tmp=2;break;
                case 'four':tmp=3;break;
                case 'five':tmp=4;break;
                default:break;
            }
            return tmp;
        }

        function transferPrice(price){
            var tmp=0;
            switch(price){
                case 'all':tmp=0;break;
                case '150':tmp=1;break;
                case '300':tmp=2;break;
                case '450':tmp=3;break;
                case '600':tmp=4;break;
                case '1000':tmp=5;break;
                case 'max':tmp=6;break;
                default:break;
            }
            return tmp;
        }

        function transferScore(socre){
            var tmp=0;
            switch(socre){
                case 'all':tmp=0;break;
                case '3':tmp=1;break;
                case '3.5':tmp=2;break;
                case '4':tmp=3;break;
                case '4.5':tmp=4;break;
                default:break;
            }
            return tmp;
        }
    }
})();