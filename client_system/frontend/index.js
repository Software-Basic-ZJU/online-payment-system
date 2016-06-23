(function(){
    var app=angular.module("onlinePayment",[
        'ui.router',
        'oc.lazyLoad',
        'ngAnimate',
        'LocalStorageModule',
        'ngFlash'
    ]);

    // 以datetime格式返回今日日期
    app.factory('GetToday',function(){
        return function(){
            var time=new Date();
            var obj={
                year:time.getFullYear(),
                month:time.getMonth()+1,
                day:time.getDate(),
            };
            if (obj.month<10) obj.month="0"+obj.month;
            if (obj.day<10) obj.day="0"+obj.day;
            var datetime=obj.year+"-"+obj.month+"-"+obj.day;
            return datetime;
        }
    });

    app.factory('FormFilter',function(){
        return {
            emailFilter:function(email){
                var emailReg=/^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
                return emailReg.test(email);
            },
            sqlFilter:function(text){
                var re=/select|update|delete|truncate|join|union|exec|insert|drop|count|’|"|;|>|<|%/i;
                return !re.test(text);
            },
            voidFilter:function(form){
                for(var i in form){
                    if(!form[i]) return false;
                }
                return true;
            }
        }
    });

    app.factory('CompTime',function(){
        return function compTime(beginTime,endTime){
            var beginTimes = beginTime.substring(0, 10).split('-');
            var endTimes = endTime.substring(0, 10).split('-');
            beginTime = beginTimes[1] + '-' + beginTimes[2] + '-' + beginTimes[0] + ' ' + beginTime.substring(10, 19);
            endTime = endTimes[1] + '-' + endTimes[2] + '-' + endTimes[0] + ' ' + endTime.substring(10, 19);
            var res = (Date.parse(endTime) - Date.parse(beginTime)) / 3600 / 1000;
            return res;
        }
    });

    app.factory('GenerateCode',function(){
        return function createCode() {
            var idenCode = "";
            var CodeLength = 6;//验证码的长度
            var selectChar = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 'A', 'B',
                'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O',
                'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'a', 'b', 'c',
                'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's',
                't', 'u', 'v', 'w', 'x', 'y', 'z'];//所有候选组成验证码的字符，当然也可以用中文的
            for (var i = 0; i < CodeLength; i++) {
                var charIndex = Math.floor(Math.random() * 36);
                idenCode += selectChar[charIndex];
            }
            return idenCode;
        }
    });
    app.factory('UserInfo',function(localStorageService){
        var userCache=localStorageService.get('user') || {};
        return function(userInfo,value){
            //getter
            if(userInfo==undefined && value==undefined) return userCache;
            //setter
            else if(userInfo!=undefined && value!=undefined){
                var key=userInfo;
                userCache[key]=value;
            }
            else if(userInfo!=undefined && value==undefined) {
                userCache = userInfo || {};
            }
            localStorageService.set('user', userCache);
            return userCache;
        }
    });

    //基于websocket 报警系统
    app.factory('socket',function(){
        var socket=io.connect(':3000');
        return socket;
    });

    app.config(function($httpProvider){
        $httpProvider.defaults.headers.post['Content-Type']='application/x-www-form-urlencoded;charset=UTF-8';
        //console.log($httpProvider.defaults.headers);
        function param(obj) {
            var query = '', name, value, fullSubName, subName, subValue, innerObj, i;

            for (name in obj) {
                value = obj[name];
                if (value instanceof Array) {
                    for (i = 0; i < value.length; ++i) {
                        subValue = value[i];
                        fullSubName = name + '[' + i + ']';
                        innerObj = {};
                        innerObj[fullSubName] = subValue;
                        query += param(innerObj) + '&';
                    }
                }
                else if (value instanceof Object) {
                    for (subName in value) {
                        subValue = value[subName];
                        fullSubName = name + '[' + subName + ']';
                        innerObj = {};
                        innerObj[fullSubName] = subValue;
                        query += param(innerObj) + '&';
                    }
                }
                else if (value !== undefined && value !== null)
                    query += encodeURIComponent(name) + '=' + encodeURIComponent(value) + '&';
            }
            return query.length ? query.substr(0, query.length - 1) : query;
        };
        $httpProvider.defaults.transformRequest = [function(data) {
            return angular.isObject(data) && String(data) !== '[object File]' ? param(data) : data;
        }];
        $httpProvider.interceptors.push(function($q,Flash,UserInfo){
            return{
                'request':function(config){
                    if(config.url.match(/^API/)){
                        config.api=true;
                        config.url=config.url.replace(/^API/,'backend');
                    }
                    //console.log($q.when(config));
                    return config||$q.when(config);
                },
                'response':function(response){
                    if(response.config.api){
                        if(response.data.code!=0){
                            Flash.create('danger',response.data.msg);
                            if(response.data.code==233 || response.data.code==-233){
                                UserInfo({}); // logout
                                window.location.href='/';
                            }
                            return $q.reject(response)
                        }
                        if(response.data.res.token){
                            UserInfo('token',response.data.res.token); //更改token
                        }
                    }
                    return response||$q.when(response);
                },
                'responseError':function(rejection){
                    if(rejection.config.api){
                        Flash.create('danger','网络错误,请重试！');
                    }
                    return $q.reject(rejection);
                }
            }
        })
    })


    app.config(function($ocLazyLoadProvider){
        $ocLazyLoadProvider.config({
            debug:false,
            modules:[
                {
                    name:'home',
                    files:[
                        '/frontend/pages/home/home.css',
                        '/frontend/pages/home/home.js'
                    ]
                },
                {
                    name:'main',
                    files:[
                        '/frontend/pages/main/main.css',
                        '/frontend/pages/main/main.js'
                    ]
                },
                {
                    name:'mainAside',
                    files:[
                        '/frontend/components/main-aside/main-aside.css',
                        '/frontend/components/main-aside/main-aside.js'
                    ]
                },
                {
                    name:'mainAccount',
                    files:[
                        '/frontend/pages/main-account/main-account.css',
                        '/frontend/pages/main-account/main-account.js'
                    ]
                },
                {
                    name:'mainOrder',
                    files:[
                        '/frontend/pages/main-order/main-order.css',
                        '/frontend/pages/main-order/main-order.js'
                    ]
                },
                {
                    name:'mainBooking',
                    files:[
                        '/frontend/pages/main-booking/main-booking.css',
                        '/frontend/pages/main-booking/main-booking.js'
                    ]
                },
                {
                    name:'modal',
                    files:[
                        '/frontend/components/modal/modal.css',
                        '/frontend/components/modal/modal.js'
                    ]
                },
                {
                    name:'homeLogin',
                    files:[
                        '/frontend/components/modal-home/home-login/home-login.css',
                        '/frontend/components/modal-home/home-login/home-login.js'
                    ]
                },
                {
                    name:'homeRegister',
                    files:[
                        '/frontend/components/modal-home/home-register/home-register.css',
                        '/frontend/components/modal-home/home-register/home-register.js'
                    ]
                },
                {
                    name:'homeFindpswd',
                    files:[
                        '/frontend/components/modal-home/home-findpswd/home-findpswd.css',
                        '/frontend/components/modal-home/home-findpswd/home-findpswd.js',
                    ]
                },
                {
                    name:'orderItem',
                    files:[
                        '/frontend/components/order-item/order-item.css',
                        '/frontend/components/order-item/order-item.js'
                    ]
                },
                {
                    name:'orderDetail',
                    files:[
                        '/frontend/pages/order-detail/order-detail.css',
                        '/frontend/pages/order-detail/order-detail.js'
                    ]
                },
                {
                    name:'orderPay',
                    files:[
                        '/frontend/components/modal-order/order-pay/order-pay.css',
                        '/frontend/components/modal-order/order-pay/order-pay.js'
                    ]
                },
                {
                    name:'orderCancel',
                    files:[
                        '/frontend/components/modal-order/order-cancel/order-cancel.css',
                        '/frontend/components/modal-order/order-cancel/order-cancel.js'
                    ]
                },
                {
                    name:'orderRefund',
                    files:[
                        '/frontend/components/modal-order/order-refund/order-refund.css',
                        '/frontend/components/modal-order/order-refund/order-refund.js'
                    ]
                },
                {
                    name:'orderComplain',
                    files:[
                        '/frontend/components/modal-order/order-complain/order-complain.css',
                        '/frontend/components/modal-order/order-complain/order-complain.js'
                    ]
                },
                {
                    name:'orderConfirm',
                    files:[
                        '/frontend/components/modal-order/order-confirm/order-confirm.css',
                        '/frontend/components/modal-order/order-confirm/order-confirm.js'
                    ]
                },
                {
                    name:'orderReply',
                    files:[
                        '/frontend/components/modal-order/order-reply/order-reply.css',
                        '/frontend/components/modal-order/order-reply/order-reply.js'
                    ]
                },
                {
                    name:'accountCash',
                    files:[
                        '/frontend/components/modal-account/account-cash/account-cash.css',
                        '/frontend/components/modal-account/account-cash/account-cash.js',
                    ]
                },
                {
                    name:'accountRecharge',
                    files:[
                        '/frontend/components/modal-account/account-recharge/account-recharge.css',
                        '/frontend/components/modal-account/account-recharge/account-recharge.js',
                    ]
                },
                {
                    name:'accountBindcard',
                    files:[
                        '/frontend/components/modal-account/account-bindcard/account-bindcard.css',
                        '/frontend/components/modal-account/account-bindcard/account-bindcard.js',
                    ]
                },
                {
                    name:'accountNmverify',
                    files:[
                        '/frontend/components/modal-account/account-nmverify/account-nmverify.css',
                        '/frontend/components/modal-account/account-nmverify/account-nmverify.js',
                    ]
                },
                {
                    name:'booking',
                    files:[
                        '/frontend/pages/booking/booking.css',
                        '/frontend/pages/booking/booking.js'
                    ]
                },
                {
                    name:'bookingFlight',
                    files:[
                        '/frontend/pages/booking-flight/booking-flight.css',
                        '/frontend/pages/booking-flight/booking-flight.js'
                    ]
                },
                {
                    name:'bookingHotel',
                    files:[
                        '/frontend/pages/booking-hotel/booking-hotel.css',
                        '/frontend/pages/booking-hotel/booking-hotel.js'
                    ]
                },
                {
                    name:'datePicker',
                    files:[
                        '/frontend/bower_components/angularjs-datepicker/dist/angular-datepicker.min.css',
                        '/frontend/bower_components/angularjs-datepicker/dist/angular-datepicker.min.js'
                    ]
                },
                {
                    name:'hotelForm',
                    files:[
                        '/frontend/components/hotel-form/hotel-form-a.css',
                        '/frontend/components/hotel-form/hotel-form-b.css',
                        '/frontend/components/hotel-form/hotel-form.js'
                    ]
                },
                {
                    name:'filterTabs',
                    files:[
                        '/frontend/components/filter-tabs/filter-tabs.css',
                        '/frontend/components/filter-tabs/filter-tabs.js'
                    ]
                },
                {
                    name:'hotelItem',
                    files:[
                        '/frontend/components/hotel-item/hotel-item.css',
                        '/frontend/components/hotel-item/hotel-item.js'
                    ]
                },
                {
                    name:'hotelDsitem',
                    files:[
                        '/frontend/components/hotel-dsitem/hotel-dsitem.css',
                        '/frontend/components/hotel-dsitem/hotel-dsitem.js'
                    ]
                },
                {
                    name:'flightForm',
                    files:[
                        '/frontend/components/flight-form/flight-form-a.css',
                        '/frontend/components/flight-form/flight-form-b.css',
                        '/frontend/components/flight-form/flight-form.js',
                    ]
                },
                {
                    name:'flightItem',
                    files:[
                        '/frontend/components/flight-item/flight-item.css',
                        '/frontend/components/flight-item/flight-item.js',
                    ]
                },
                {
                    name:'flightDsitem',
                    files:[
                        '/frontend/components/flight-dsitem/flight-dsitem.css',
                        '/frontend/components/flight-dsitem/flight-dsitem.js'
                    ]
                },
                {
                    name:'hotelDetail',
                    files:[
                        '/frontend/pages/hotel-detail/hotel-detail.css',
                        '/frontend/pages/hotel-detail/hotel-detail.js'
                    ]
                },
                {
                    name:'flightOrder',
                    files:[
                        '/frontend/pages/flight-order/flight-order.css',
                        '/frontend/pages/flight-order/flight-order.js'
                    ]
                },
                {
                    name:'hotelGeneorder',
                    files:[
                        '/frontend/components/modal-booking/hotel-geneorder/hotel-geneorder.css',
                        '/frontend/components/modal-booking/hotel-geneorder/hotel-geneorder.js',
                    ]
                },
                {
                    name:'mdfyPswd',
                    files:[
                        '/frontend/components/modal-account/account-mdfypswd/account-mdfypswd.css',
                        '/frontend/components/modal-account/account-mdfypswd/account-mdfypswd.js',
                    ]
                },
                {
                    name:'mdfyPay',
                    files:[
                        '/frontend/components/modal-account/account-mdfypay/account-mdfypay.css',
                        '/frontend/components/modal-account/account-mdfypay/account-mdfypay.js',
                    ]
                },
                {
                    name:'setPay',
                    files:[
                        '/frontend/components/modal-account/account-setpay/account-setpay.css',
                        '/frontend/components/modal-account/account-setpay/account-setpay.js',
                    ]
                },
                {
                    name:'paging',
                    files:[
                        '/frontend/bower_components/angular-paging/dist/paging.min.js'
                    ]
                },
                {
                    name:'starPicker',
                    files:[
                        '/frontend/components/star-picker/star-picker.css',
                        '/frontend/components/star-picker/star-picker.js'
                    ]
                },
                {
                    name:'commentItem',
                    files:[
                        '/frontend/components/comment-item/comment-item.css',
                        '/frontend/components/comment-item/comment-item.js',
                    ]
                }
            ]
        })
    });

    app.config(function($stateProvider,$urlRouterProvider){
        $urlRouterProvider.when('','/');

        $stateProvider
            .state('home',{
                url:'/',
                pageTitle:'亿颗赛艇-在线支付系统',
                templateUrl:'/frontend/pages/home/home.html',
                controller:'home',
                resolve:{
                    home:function($ocLazyLoad){
                        return $ocLazyLoad.load(['home']);
                    }
                }
            })
            .state('main',{
                url:'/main/{userName}',
                templateUrl:'/frontend/pages/main/main.html',
                controller:'main',
                resolve:{
                    main:function($ocLazyLoad){
                        return $ocLazyLoad.load(['main']);
                    }
                },
                redirectTo:'main.account'   //default substate
            })
            .state('main.account',{
                url:'/account{path:/?}',
                pageTitle:'个人账户信息-控制台',
                templateUrl:'/frontend/pages/main-account/main-account.html',
                controller:'mainAccount',
                resolve:{
                    mainAccount:function($ocLazyLoad){
                        return $ocLazyLoad.load(['mainAccount']);
                    }
                }
            })
            .state('main.order',{
                url:'/order{path:/?}',
                pageTitle:'订单管理-控制台',
                templateUrl:'/frontend/pages/main-order/main-order.html',
                controller:'mainOrder',
                resolve:{
                    mainOrder:function($ocLazyLoad){
                        return $ocLazyLoad.load(['mainOrder']);
                    }
                }
            })
            .state('main.booking',{
                url:'/booking{path:/?}',
                pageTitle:'应用入口-控制台',
                templateUrl:'/frontend/pages/main-booking/main-booking.html',
                controller:'mainBooking',
                resolve:{
                    mainBooking:function($ocLazyLoad){
                        return $ocLazyLoad.load(['mainBooking']);
                    }
                }
            })
            .state('main.orderDetail',{
                url:'/order/detail?orderId',
                pageTitle:'订单详情-控制台',
                templateUrl:'/frontend/pages/order-detail/order-detail.html',
                controller:'orderDetail',
                resolve:{
                    orderDetail:function($ocLazyLoad){
                        return $ocLazyLoad.load(['orderDetail']);
                    }
                }
            })
            .state('booking',{
                url:'/booking/{userId}',
                templateUrl:'/frontend/pages/booking/booking.html',
                controller:'booking',
                resolve:{
                    booking:function($ocLazyLoad){
                        return $ocLazyLoad.load(['booking'])
                    }
                },
                redirect:'booking.flight'
            })
            .state('booking.flight',{
                url:'/flight?from&to&lvt&rct',
                pageTitle:'机票预订-亿颗赛艇',
                templateUrl:'/frontend/pages/booking-flight/booking-flight.html',
                controller:'bookingFlight',
                resolve:{
                    bookingFlight:function($ocLazyLoad){
                        return $ocLazyLoad.load(['bookingFlight'])
                    }
                }
            })
            .state('booking.hotel',{
                url:'/hotel?des&it&ot&st&pr&sr',
                pageTitle:'酒店预订-亿颗赛艇',
                templateUrl:'/frontend/pages/booking-hotel/booking-hotel.html',
                controller:'bookingHotel',
                resolve:{
                    bookingHotel:function($ocLazyLoad){
                        return $ocLazyLoad.load(['bookingHotel'])
                    }
                }
            })
            .state('booking.hotelDetail',{
                url:'/hotel/detail?hid&it&ot',
                pageTitle:'房型选择-酒店预订',
                templateUrl:'/frontend/pages/hotel-detail/hotel-detail.html',
                controller:'hotelDetail',
                resolve:{
                    hotelDetail:function($ocLazyLoad){
                        return $ocLazyLoad.load(['hotelDetail'])
                    }
                }
            })
            .state('booking.flightOrder',{
                url:'/flight/order?gid',
                pageTitle:'提交订单-机票预订',
                templateUrl:'/frontend/pages/flight-order/flight-order.html',
                controller:'flightOrder',
                resolve:{
                    flightOrder:function($ocLazyLoad){
                        return $ocLazyLoad.load(['flightOrder'])
                    }
                }
            })

    });

    //redirect a state to a default substate
    app.run(function($rootScope,$state,socket,Flash,UserInfo,$timeout){
		socket.on('message',function(notification){
            //console.log(notification.user_id)
			//console.log(UserInfo().userId);
			if(notification.user_id==UserInfo().userId){
				var time=(new Date(notification.timestamp)).toLocaleString();
				$timeout(function(){
					Flash.create('info','实时消息: 用户'+notification.user_id+'于['+time+']'+notification.body);
				});
				
			}
        });
        $rootScope.$on('$stateChangeStart',function(event,toState,params){
            if(toState.redirectTo){
                event.preventDefault();
                $state.go(toState.redirectTo,params);
            }
            if(toState.pageTitle){
                $rootScope.pageTitle=toState.pageTitle;
            }
        })
    })
})();