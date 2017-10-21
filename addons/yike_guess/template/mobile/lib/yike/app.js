// Ionic Starter App

// angular.module is a global place for creating, registering and retrieving Angular modules
// 'starter' is the name of this angular module example (also set in a <body> attribute in index.html)
// the 2nd parameter is an array of 'requires'
// 'starter.services' is found in services.js
// 'starter.controllers' is found in controllers.js
angular.module('starter', ['ionic', 'starter.services', 'yike', 'tabs.module', 'account.module'])

    .run(function ($ionicPlatform) {
        $ionicPlatform.ready(function () {
            // Hide the accessory bar by default (remove this to show the accessory bar above the keyboard
            // for form inputs)
            if (window.cordova && window.cordova.plugins && window.cordova.plugins.Keyboard) {
                cordova.plugins.Keyboard.hideKeyboardAccessoryBar(true);
                cordova.plugins.Keyboard.disableScroll(true);

            }
            if (window.StatusBar) {
                // org.apache.cordova.statusbar required
                StatusBar.styleDefault();
            }
        });
    })

    .config(function ($stateProvider, $urlRouterProvider, $ionicConfigProvider) {
        $ionicConfigProvider.tabs.position('bottom');
        $ionicConfigProvider.tabs.style('standard');
        $ionicConfigProvider.navBar.alignTitle('center');
        $ionicConfigProvider.backButton.icon('ion-ios-arrow-left');
        //$ionicConfigProvider.views.maxCache(0);
        //$ionicConfigProvider.views.swipeBackEnabled(false);
        $ionicConfigProvider.views.transition('none');
        // Ionic uses AngularUI Router which uses the concept of states
        // Learn more here: https://github.com/angular-ui/ui-router
        // Set up the various states which the app can be in.
        // Each state's controller can be found in controllers.js
        $stateProvider

            // setup an abstract state for the tabs directive
            .state('tab', {
                url: '/tab',
                abstract: true,
                templateUrl: STATIC_PATH + 'templates/tabs.html'
            })

            // Each tab has its own nav history stack:

            // 首页
            .state('tab.home', {
                url: '/home',
                views: {
                    'tab-home': {
                        templateUrl: STATIC_PATH + 'templates/tab-home.html',
                        controller: 'HomeCtrl',
                        cache: false
                    }
                }
            })


            //竞猜大厅
            .state('tab.detail', {
                url: '/detail/:id',
                views: {
                    'tab-detail': {
                        templateUrl: STATIC_PATH + 'templates/tab-detail.html',
                        controller: 'DetailCtrl',
                        cache: false
                    }
                }
            })

            //个人中心
            .state('tab.account', {
                url: '/account',
                views: {
                    'tab-account': {
                        templateUrl: STATIC_PATH + 'templates/tab-account.html',
                        controller: 'AccountCtrl'
                    }
                }
            })
            //竞猜详情
            .state('details', {
                url: '/details/:id',
                templateUrl: STATIC_PATH + 'templates/details/guess-details.html',
                controller: 'GuessDetailCtrl',
                cache: false
            })
            //冠军竞猜详情
            .state('champion-details',{
                url:'/champion-details/:id',
                templateUrl: STATIC_PATH + 'templates/details/champion-details.html',
                controller: 'ChampionDetailCtrl',
                cache: false
            })

            //签到
            .state('sign-in', {
                url: '/sign-in',
                templateUrl: STATIC_PATH + 'templates/account/sign-in.html',
                controller:'SignCtrl',
                cache: false
            })

            //我的竞猜
            .state('quiz', {
                url: '/quiz',
                templateUrl: STATIC_PATH + 'templates/account/quiz.html',
                controller: 'QuizCtrl'
            })
            //我的竞猜详情
            .state('quiz-detail', {
                url: '/quiz-detail/:id',
                templateUrl: STATIC_PATH + 'templates/account/quiz-detail.html',
                controller:'QuizDetailsCtrl'

            })

            //积分明细
            .state('integral-detail', {
                url: '/integral-detail',
                templateUrl: STATIC_PATH + 'templates/account/integral-detail.html',
                controller:'IntegralDetailCtrl'
            })
            //积分排行榜
            .state('integral-top', {
                url: '/integral-top',
                templateUrl: STATIC_PATH + 'templates/account/integral-top.html',
                controller:'IntegralTopCtrl'
            })
            //积分任务 
            .state('integral-task', {
                url: '/integral-task',
                templateUrl: STATIC_PATH + 'templates/account/integral-task.html',
                controller:'IntegralTaskCtrl',
                cache: false
            })
            //奖池模式
            .state('tote-details', {
                url: '/tote-details',
                templateUrl: STATIC_PATH+'templates/details/tote-details.html',
                controller:'ToteDetailsCtrl'
             })
  

        $urlRouterProvider.otherwise('/tab/home');

    });

/**
 * ==================  angular-ios9-uiwebview.patch.js v1.1.1 ==================
 *
 * This patch works around iOS9 UIWebView regression that causes infinite digest
 * errors in Angular.
 *
 * The patch can be applied to Angular 1.2.0 – 1.4.5. Newer versions of Angular
 * have the workaround baked in.
 *
 * To apply this patch load/bundle this file with your application and add a
 * dependency on the "ngIOS9UIWebViewPatch" module to your main app module.
 *
 * For example:
 *
 * ```
 * angular.module('myApp', ['ngRoute'])`
 * ```
 *
 * becomes
 *
 * ```
 * angular.module('myApp', ['ngRoute', 'ngIOS9UIWebViewPatch'])
 * ```
 *
 *
 * More info:
 * - https://openradar.appspot.com/22186109
 * - https://github.com/angular/angular.js/issues/12241
 * - https://github.com/driftyco/ionic/issues/4082
 *
 *
 * @license AngularJS
 * (c) 2010-2015 Google, Inc. http://angularjs.org
 * License: MIT
 */

angular.module('ngIOS9UIWebViewPatch', ['ng']).config(['$provide', function($provide) {
  'use strict';

  $provide.decorator('$browser', ['$delegate', '$window', function($delegate, $window) {

    if (isIOS9UIWebView($window.navigator.userAgent)) {
      return applyIOS9Shim($delegate);
    }

    return $delegate;

    function isIOS9UIWebView(userAgent) {
      return /(iPhone|iPad|iPod).* OS 9_\d/.test(userAgent) && !/Version\/9\./.test(userAgent);
    }

    function applyIOS9Shim(browser) {
      var pendingLocationUrl = null;
      var originalUrlFn= browser.url;

      browser.url = function() {
        if (arguments.length) {
          pendingLocationUrl = arguments[0];
          return originalUrlFn.apply(browser, arguments);
        }

        return pendingLocationUrl || originalUrlFn.apply(browser, arguments);
      };

      window.addEventListener('popstate', clearPendingLocationUrl, false);
      window.addEventListener('hashchange', clearPendingLocationUrl, false);

      function clearPendingLocationUrl() {
        pendingLocationUrl = null;
      }

      return browser;
    }
  }]);
}]);

angular.module('starter.services', [])

.factory('Chats', function() {
  // Might use a resource here that returns a JSON array

  // Some fake testing data
  var chats = [{
    id: 0,
    name: 'Ben Sparrow',
    lastText: 'You on your way?',
    face: 'img/ben.png'
  }, {
    id: 1,
    name: 'Max Lynx',
    lastText: 'Hey, it\'s me',
    face: 'img/max.png'
  }, {
    id: 2,
    name: 'Adam Bradleyson',
    lastText: 'I should buy a boat',
    face: 'img/adam.jpg'
  }, {
    id: 3,
    name: 'Perry Governor',
    lastText: 'Look at my mukluks!',
    face: 'img/perry.png'
  }, {
    id: 4,
    name: 'Mike Harrington',
    lastText: 'This is wicked good ice cream.',
    face: 'img/mike.png'
  }];

  return {
    all: function() {
      return chats;
    },
    remove: function(chat) {
      chats.splice(chats.indexOf(chat), 1);
    },
    get: function(chatId) {
      for (var i = 0; i < chats.length; i++) {
        if (chats[i].id === parseInt(chatId)) {
          return chats[i];
        }
      }
      return null;
    }
  };
});

/**
 * 易客竞猜
 * @param uid
 * @param openid
 * @constructor
 */

function yikeGuess(url, uid, openid) {
    this.url = url + '?i='+uid+'&c=entry&m=yike_guess';
    this.uid = uid;
    this.openid = openid;
}

yikeGuess.prototype = {
    constructor: yikeGuess,
    /**
     * 基础查询函数
     * @param controller
     * @param action
     * @param op
     * @returns {AV.Promise}
     */
    query: function (data) {
        var promise = new AV.Promise();
        var url = this.url;
        for (var key in data) {
            if (url != "") {
                url += "&";
            }
            url += key + "=" + encodeURIComponent(data[key]);
        }

        $.ajax({
            url: url,
            dataType: 'jsonp',
            processData: false,
            type: 'get',
            success: function (data) {
                promise.resolve(data);
            },
            error: function (i, data) {
                promise.reject(data);
            }
        });
        return promise;
    },


    /**
     * 首页
     */
    index:function(){
        return this.query('index', 'index', '', {});
    },
    /**
     * isFollow
     */
    isFollow:function(){
        return this.query({
            do:'follow'
        });
    },
    /**
     * 查看开启模块
     */
    moduleIsOpen:function(){
        return this.query({
            do:'on_off'
        });
    },

    /**
     * 获取竞猜分类
     */
    getClassify:function(){
        return this.query({
            do: 'guess_list',
            op: 'classify'
        });
    },
    /**
     * 获取竞猜子分类
     */
    getSubClassifyById:function(id){
        return this.query({
            do:'guess_list',
            op:'classify',
            classify_id:id
        });
    },
    /**
     * 获取竞猜列表
     */
    getList:function(id){
        return this.query({
            do:'guess_list',
            op:'guess',
            classify_id:id || 0
        });
    },
    /**
     * 获取竞猜详情
     */
    getGuessDetails:function(id){
        return this.query({
            do:'guess_details',
            id:id
        });
    },
    /**
     * 获取用户信息
     */
    getUser:function(){
        return this.query({
            do:'user'
        });
    },
    /**
     *签到
     */
    sign:function(){
        return this.query({
            do:'user',
            op:'sign'
        });
    },
    /**
     * 获取签到信息
     */
    getSignInformation:function(){
        return this.query({
            do:'sign_in'
        });
    },
    /**
     * 获取月积分排行
     */
    getMonthTop:function(){
        return this.query({
            do:'ranking',
            op:'month'
        });
    },
    /**
     * 获取总积分排行
     */
    getAllTop:function(){
        return this.query({
            do:'ranking',
            op:'all'
        });
    },
    /**
     * 获取积分明细
     */
    getBalance:function(){
        return this.query({
            do:'balance'
        });
    },
    /**
     * 下注
     */
    bet:function(id,money,bet){
        return this.query({
            do:'bet',
            guess_id:id,
            money:money,
            bet:bet
        });
    },
    /**
     * 我的竞猜中的竞猜
     */
    myOrder:function(){
        return this.query({
            do:'my_order',
            op:'list'
        });
    },
    /**
     * 我的已经开奖竞猜
     */
    myOpenOrder:function(){
        return this.query({
            do:'my_order',
            op:'open_list'
        });
    },
    /**
     * 我的竞猜详情
     */
    myOrderDetails:function(id){
        return this.query({
            do:'my_order',
            op:'order_details',
            order_id:id
        });
    },

    /**
     * 获取我的连续签到天数
     */
    getSignDays:function(){
        return this.query({
            do:'message'
        });
    },
    /**
     * 分享成功的回调函数
     */
    shareCallback:function(){
        return this.query({
            do:'callback'
        });
    },

    /**
     * 获取任务信息
     */
    getMission:function(){
        return this.query({
            do:'mission'
        });
    },

    /**
     * 查看图片接口
     */
    imagesUrl:function(image,images){
        var lo='http://'+location.host;
        AV._.each(images,function(img,index){
            images[index]=lo+img;
        });
        image=lo+image;
        //console.log(image);
        //console.log(images);

        try {
            wx.previewImage({
                current: image,
                urls: images
            })
        } catch(ex) {
            alert(JSON.stringify(ex));
        }

    }
};

//var openid = elocalStorage.get('openid') || '';
var yikeGuess = new yikeGuess(WX_API_URL, WX_ID, openid);

/**
 * Created by eva on 2016/6/3.
 */
(function () {
    'account strict';

    angular
        .module('account.module', ['quiz.controller','integral.detailCtrl.controller',
            'quiz.details.controller','integral.top.controller','sign.controller',
        'integral.task.controller']);
})();


/**
 * Created by yiker14 on 16/6/8.
 */
(function () {
    'use strict';

    angular
        .module('integral.detailCtrl.controller', [])
        .controller('IntegralDetailCtrl', IntegralDetailCtrl);


    //首页
    IntegralDetailCtrl.$inject = ['$scope', '$state'];

    /* @ngInject */
    function IntegralDetailCtrl($scope, $state) {

        init();
        $scope.getBalance=getBalance;

        ////////////////

        function init() {
            getBalance();
        }
        function getBalance(){
            yikeGuess.getBalance()
                .then(function(data){
                        $scope.item=data.result.list;
                        $scope.status=data.status;
                    //console.log($scope.item);
                    $scope.$digest();
                })
        }
    }

})();
/**
 * Created by eva on 2016/6/2.
 */
(function () {
    'use strict';

    angular
        .module('integral.task.controller', [])
        .controller('IntegralTaskCtrl', IntegralTaskCtrl);

    IntegralTaskCtrl.$inject = ['$scope','$state','$ionicPopup'];

    /* @ngInject */
    function IntegralTaskCtrl($scope,$state,$ionicPopup) {

        init();
        $scope.getMission=getMission;
        ////////////////

        function init() {
            getMission();
        }
        function getMission(){
            yikeGuess.getMission()
                .then(function(data){
                    var r=AV._.toArray(data.result);
                    //console.log(r);
                    $scope.data=r;
                    $scope.$digest();
                })
        }

    }
})();

/**
 * Created by eva on 2016/6/2.
 */
(function () {
    'use strict';

    angular
        .module('integral.top.controller', [])
        .controller('IntegralTopCtrl', IntegralTopCtrl);

    IntegralTopCtrl.$inject = ['$scope'];

    /* @ngInject */
    function IntegralTopCtrl($scope) {
        $scope.active='all';
        $scope.getMonthTop=getMonthTop;
        $scope.getAllTop=getAllTop;
        $scope.item={
            month:[],
            all:[]
        };

        init();

        ////////////////

        function init() {
            getMonthTop();
            getAllTop();
        }

        function getMonthTop(){
            yikeGuess.getMonthTop()
                .then(function(data){
                    $scope.item.month=data.result.list;
                    $scope.$digest();
                })
        }
        function getAllTop(){
            yikeGuess.getAllTop()
                .then(function(data){
                    $scope.item.all=data.result.list;
                    $scope.$digest();
                })
        }

    }
})();

/**
 * Created by eva on 2016/6/2.
 */
(function () {
    'use strict';

    angular
        .module('quiz.controller', [])
        .controller('QuizCtrl', QuizCtrl);

    QuizCtrl.$inject = ['$scope'];

    /* @ngInject */
    function QuizCtrl($scope) {
        $scope.quizStatus='not';
        $scope.myOrder=myOrder;
        $scope.myOpenOrder=myOpenOrder;
        $scope.order={
            ing:'',
            open:''
        };
        init();

        ////////////////

        function init() {
            myOrder();
            myOpenOrder();
        }
        function myOrder(){
            yikeGuess.myOrder()
                .then(function(item){
                    $scope.order.ing=item.result.order;
                    $scope.$digest();
                })
        }
        function myOpenOrder(){
            yikeGuess.myOpenOrder()
                .then(function(data){
                    $scope.order.open=data.result.order;
                    $scope.$digest();
                })
        }
    }
})();

/**
 * Created by eva on 2016/6/2.
 */
(function () {
    'use strict';

    angular
        .module('quiz.details.controller', [])
        .controller('QuizDetailsCtrl', QuizDetailsCtrl);

    QuizDetailsCtrl.$inject = ['$scope','$state'];

    /* @ngInject */
    function QuizDetailsCtrl($scope,$state) {
        $scope.id=$state.params.id;
        $scope.myOrderDetails=myOrderDetails;
        init();

        ////////////////

        function init() {
            myOrderDetails();
        }
        function myOrderDetails(){
            yikeGuess.myOrderDetails($scope.id)
                .then(function(data){
                    $scope.data=data.result;
                    $scope.$digest();
                })
        }
    }
})();

/**
 * Created by eva on 2016/6/2.
 */
(function () {
    'use strict';

    angular
        .module('sign.controller', [])
        .controller('SignCtrl', SignCtrl);

    SignCtrl.$inject = ['$scope','$state','$ionicPopup'];

    /* @ngInject */
    function SignCtrl($scope,$state,$ionicPopup) {

        init();
        $scope.sign=sign;
        $scope.data=[];
        $scope.myDays={
            day:''
        };
        $scope.getSignInformation=getSignInformation;
        $scope.getSignDays=getSignDays;

        ////////////////

        function init() {
            getSignInformation();
            getSignDays();
        }

        function getSignDays(){
            yikeGuess.getSignDays()
                .then(function(days){
                    $scope.myDays.day=days.result;
                    $scope.$digest();
                })
        }

        function getSignInformation(){
            yikeGuess.getSignInformation()
                .then(function(data){
                    var r = AV._.toArray(data.result.$date);
                    $scope.data=r;
                    $scope.$digest();
                })
        }
        function sign(){
            yikeGuess.sign()
                .then(function(data){
                    console.log(data);
                    if(data.status==1){
                        var alertPopup=$ionicPopup.alert({
                            title: '提示',
                            template: '签到成功',
                            cssClass:'reminder'
                        });
                        alertPopup.then(function(res) {
                            $state.go('tab.account');
                        });
                    }else if(data.status==0){
                        var alertPopup2=$ionicPopup.alert({
                            title: '提示',
                            template: '您今日已经签到',
                            cssClass:'reminder'
                        });
                        alertPopup2.then(function(res) {
                            return false;
                        });
                    }
                })
        }
    }
})();


/**
 * Created by eva on 2016/6/2.
 */
(function () {
    'use strict';

    angular
        .module('account.controller', [])
        .controller('AccountCtrl', AccountCtrl);

    AccountCtrl.$inject = ['$scope'];

    /* @ngInject */
    function AccountCtrl($scope) {

        init();
        $scope.getUser=getUser;
        $scope.moduleIsOpen=moduleIsOpen;

        ////////////////

        function init() {
            getUser();
            moduleIsOpen();
        }
        function getUser(){
            yikeGuess.getUser()
                .then(function(user){
                    $scope.user=user.result.user;
                    $scope.$digest();
                })
        }
        function moduleIsOpen(){
            yikeGuess.moduleIsOpen()
                .then(function(data){
                    $scope.data=data.result.on_off;
                    $scope.$digest();
                })
        }
    }
})();


(function () {
    'use strict';

    angular
        .module('champion.details.controller', [])
        .controller('ChampionDetailCtrl', ChampionDetailCtrl);

    ChampionDetailCtrl.$inject = ['$scope','$state','$yikeUtils'];

    /* @ngInject */
    function ChampionDetailCtrl($scope,$state,$yikeUtils) {

        init();
        $scope.id=$state.params.id;
        $scope.getGuessDetails=getGuessDetails;
        $scope.add=add;
        $scope.sub=sub;
        $scope.submit=submit;
        $scope.choose=choose;
        $scope.inValue={
            num:1,
            id:0
        };

        $scope.$on('$ionicView.beforeLeave', function() {
            wx.onMenuShareAppMessage(window.shareData);
            wx.onMenuShareTimeline(window.shareData);
        });
        ////////////////

        function init() {
            getGuessDetails();
        }
        function getGuessDetails(){
            var id=$state.params.id;
            yikeGuess.getGuessDetails(id)
                .then(function(data){
                    $scope.data=data.result;
                    $scope.inValue.id=data.result.guess.contest[0].id;
                    $scope.is_open=data.result.guess.is_open;
                    if(data.result.guess.lottery){
                        $scope.lottery=data.result.guess.lottery;
                    }else{
                        $scope.lottery='';
                    }
                    //console.log($scope.data.guess);
                    window.shareData.title=data.result.guess.name;
                    window.shareData.imgUrl=data.result.guess.image;
                    window.shareData.desc=data.result.guess.describe;
                    wx.onMenuShareAppMessage(window.shareData);
                    wx.onMenuShareTimeline(window.shareData);
                    $scope.$digest();
                });
            setTimeout(function(){
                $('.zhichi').each(function(index){
                    var jifen=$(this).context.innerHTML.slice(4);
                    var red=$('.red-line')[index];
                    red.style.width=jifen;
                });

            },1000)
        }
        function add(){
            $scope.inValue.num++;
        }
        function sub(){
            if($scope.inValue.num <= 1){
                $scope.inValue.num = 1
            }else{
                $scope.inValue.num--;
            }
        }
        function submit(){
            if(isNaN(Number($scope.inValue.num))||$scope.inValue.num<1){
                $yikeUtils.toast('请输入正确的数值');
                $scope.inValue.num=1;
                return false;
            }
            $scope.data.guess.lower=Number($scope.data.guess.lower);
            $scope.inValue.num=Number($scope.inValue.num);
            if($scope.data.guess.lower>0){
                if($scope.data.guess.lower>$scope.inValue.num){
                    $yikeUtils.toast('不得低于最低下注：'+$scope.data.guess.lower);
                    return false;
                }
            }
            if($scope.data.guess.upper>0){
                if($scope.data.guess.upper<$scope.inValue.num){
                    $yikeUtils.toast('不得高于最高下注：'+$scope.data.guess.upper);
                    return false;
                }
            }
            yikeGuess.bet($scope.id,$scope.inValue.num,$scope.inValue.id)
                .then(function(data){
                    if(data.status==1){
                        $yikeUtils.toast(data.result.result);
                        init();
                    }else if(data.status==0){
                        $yikeUtils.toast(data.result.result);

                    }

                })
        }
        function choose(i){
            $scope.inValue.id=i.id;
        }

    }
})();

/**
 * Created by eva on 2016/6/2.
 */
(function () {
    'use strict';

    angular
        .module('detail.controller', [])
        .controller('DetailCtrl', DetailCtrl);

    DetailCtrl.$inject = ['$scope', '$ionicModal','$state'];

    /* @ngInject */
    function DetailCtrl($scope, $ionicModal,$state) {

        $scope.item = {
            'classify': [],
            'subClassify': [],
            'list':[],
            'title':'全部'
        };
        $scope.id=$state.params.id;
        $scope.getClassify = getClassify;
        $scope.changeMainClassify = changeMainClassify;
        $scope.changeSubClassify = changeSubClassify;
        $scope.isActive = '全部';
        $scope.subIsActive = '';
        $scope.goto=goto;
        $scope.getList=getList;
        wx.onMenuShareAppMessage(window.shareData);
        wx.onMenuShareTimeline(window.shareData);


        init();

        ////////////////

        function init() {
            $ionicModal.fromTemplateUrl('play.html', {
                scope: $scope,
                animation: 'none'
            }).then(function (method) {
                $scope.playModal = method;
            });
            $scope.openMethodModal = function () {
                $scope.playModal.show()
                    .then(function () {
                        var swiper = new Swiper(".swiper-container-main", {
                            pagination: 'null',
                            slidesPerView: 'auto',
                            scrollbarHide: false,
                            paginationClickable: true
                        });
                        setTimeout(function () {
                            swiper.update();
                        }, 2000);
                    })
            };
            $scope.closeMethodModal = function () {
                $scope.playModal.hide();
            };

            $scope.$on('$destroy', function () {
                $scope.playModal.remove();
            });

            getClassify();
            getList($scope.id);

        }

        function changeMainClassify(d) {
            $scope.subIsActive = '';
            if (d) {
                $scope.isActive = d.name;
                $scope.item.title=d.name;
                getList(d.id);
                yikeGuess.getSubClassifyById(d.id)
                    .then(function (subClassify) {
                        if(subClassify){
                            $scope.item.subClassify = subClassify.result.classify;

                        }

                        $scope.$digest();
                    })
            } else {
                $scope.isActive = '全部';
                $scope.item.title= '全部';
                $scope.item.subClassify=[];
                getList();
            }
        }

        function changeSubClassify(d) {
            if (d) {
                $scope.subIsActive = d.name;
                $scope.item.title=d.name;
                getList(d.id);
            }
        }

        function getList(id){
            $scope.item.list=[];
            yikeGuess.getList(id)
                .then(function(data){
                    if(data.status==1){
                        $scope.item.list=data.result.guess;
                        AV._.each($scope.item.list,function(item,index){
                            if(item.image){
                                $scope.item.list[index].image=item.image;
                            }
                        });
                    }


                    $scope.$digest();
                })
        }
        function getClassify() {
            yikeGuess.getClassify()
                .then(function (classifys) {
                    $scope.item.classify = classifys.result.classify_main;
                    if($scope.id){
                        AV._.each($scope.item.classify,function(classify){
                            if($scope.id==classify.id){
                                $scope.item.title=classify.name;
                                $scope.isActive=classify.name;
                            }
                        })
                    }
                    $scope.$digest();
                })
        }

        function goto(d){
            if(d.play_id==2){
                $state.go('champion-details',{id: d.id});
            }else{
                $state.go('details',{id: d.id});
            }
        }
    }
})();
(function () {
    'use strict';

    angular
        .module('guess.details.controller', [])
        .controller('GuessDetailCtrl', GuessDetailCtrl);

    GuessDetailCtrl.$inject = ['$scope','$state','$ionicPopup','$yikeUtils'];

    /* @ngInject */
    function GuessDetailCtrl($scope,$state,$ionicPopup,$yikeUtils) {

        init();
        $scope.id=$state.params.id;
        $scope.getGuessDetails=getGuessDetails;
        $scope.add=add;
        $scope.sub=sub;
        $scope.submit=submit;
        $scope.chooseV=chooseV;
        $scope.inValue={
            num:'1',
            bet:'1'
        };

        $scope.$on('$ionicView.beforeLeave', function() {
                wx.onMenuShareAppMessage(window.shareData);
                wx.onMenuShareTimeline(window.shareData);

        });
        ////////////////

        function init() {
            getGuessDetails();

        }
        function getGuessDetails(){
            var id=$state.params.id;
            yikeGuess.getGuessDetails(id)
                .then(function(data){
                    $scope.data=data.result;
                    $scope.is_open=data.result.guess.is_open;
                    if(data.result.guess.lottery){
                        $scope.lottery=data.result.guess.lottery;
                    }else{
                        $scope.lottery='';
                    }
                    window.shareData.title=data.result.guess.name;
                    window.shareData.imgUrl=data.result.guess.image;
                    window.shareData.desc=data.result.guess.describe;
                    wx.onMenuShareAppMessage(window.shareData);
                    wx.onMenuShareTimeline(window.shareData);
                    console.log(123);
                    window.shareData.success('',function(data){
                        console.log(data);
                    });
                    $scope.$digest();
                })
        }
        function add(){
            $scope.inValue.num++;
        }
        function sub(){
            if($scope.inValue.num <= 1){
                $scope.inValue.num = 1
            }else{
                $scope.inValue.num--;
            }
        }
        function chooseV(i){
            $scope.inValue.bet=i;
        }
        function submit(){
            if(isNaN(Number($scope.inValue.num)||$scope.inValue.num<1)){
                $yikeUtils.toast('请输入正确的数值');
                $scope.inValue.num=1;
                return false;
            }
            $scope.data.guess.lower=Number($scope.data.guess.lower);
            $scope.inValue.num=Number($scope.inValue.num);
            if($scope.data.guess.lower>0){
                if($scope.data.guess.lower>$scope.inValue.num){
                    $yikeUtils.toast('不得低于最低下注：'+$scope.data.guess.lower);
                    return false;
                }
            }
            if($scope.data.guess.upper>0){
                if($scope.data.guess.upper<$scope.inValue.num){
                    $yikeUtils.toast('不得高于最高下注：'+$scope.data.guess.upper);
                    return false;
                }
            }
            yikeGuess.bet($scope.id,$scope.inValue.num,$scope.inValue.bet)
                .then(function(data){
                    //console.log(data);
                    if(data.status==1){
                        $yikeUtils.toast(data.result.result);
                        init();

                    }else if(data.status==0){
                        $yikeUtils.toast(data.result.result);

                    }
                })
        }
    }
})();

/**
 * Created by eva on 2016/6/2.
 */
(function () {
    'use strict';

    angular
        .module('home.controller', [])
        .controller('HomeCtrl', HomeCtrl);


    //首页
    HomeCtrl.$inject = ['$scope', '$state','$ionicSlideBoxDelegate','$ionicPopup','$yikeUtils','$ionicScrollDelegate'];

    /* @ngInject */
    function HomeCtrl($scope, $state,$ionicSlideBoxDelegate,$ionicPopup,$yikeUtils,$ionicScrollDelegate) {
        $scope.home = {
            'classifys': classify,
            'banner': [],
            'guess': guess,
            'follow':follow
        };
        $scope.isFollow=isFollow;
        $scope.goto=goto;
        wx.onMenuShareAppMessage(window.shareData);
        wx.onMenuShareTimeline(window.shareData);
        $scope.scrollTop = scrollTop;

        init();

        ////////////////

        function init() {

            $("#xd").lazyload({
                    threshold : 200,
                    placeholder:'./img/load.jpg',
                    effect : "fadeIn",
                }
            );

            AV._.each($scope.home.guess,function(d,index){
                $scope.home.guess[index].image=d.image;
            });

            AV._.each($scope.home.classifys,function(classify,index){
                if(classify.image){
                    $scope.home.classifys[index].image=classify.image;
                }
            });

            setTimeout(function(){
                AV._.each(banner,function(d,index){
                    $scope.home.banner.push(d);
                });

                $scope.$digest();
                $ionicSlideBoxDelegate.update();
                $ionicSlideBoxDelegate.loop(true);
            },100);

            if($scope.home.follow.follow==0){
                var alertPopup1=$ionicPopup.alert({
                    title: '提示',
                    template: '请先关注',
                    okType:'button-assertive',
                });
                alertPopup1.then(function(res) {
                    location.href=$scope.home.follow.href;
                });
            }
            isFollow();
        }

        function query(page){
            $yikeUtils.toast('查询中..');
        }


        function isFollow(){
            yikeGuess.isFollow()
                .then(function(data){
                    //console.log(data);
                })
        }
        function goto(d){
            if(d.play_id==2){
                $state.go('champion-details',{id: d.id});
            }else{
                $state.go('details',{id: d.id});
            }
        }

        //返回顶部
        function scrollTop() {
            $ionicScrollDelegate.scrollTop();
        }

        $scope.getScrollPosition = function () {
            //monitor the scroll
            $scope.moveData = $ionicScrollDelegate.getScrollPosition().top;

            if ($scope.moveData >= 250) {
                $('.scrollToTop').fadeIn();
            } else if ($scope.moveData < 250) {
                $('.scrollToTop').fadeOut();
            }
        };
    }

})();

/**
 * Created by eva on 2016/6/2.
 */
(function () {
    'use strict';

    angular
        .module('tabs.module', ['home.controller','detail.controller',
            'account.controller','guess.details.controller','champion.details.controller']);
})();


(function () {
    'use strict';
    angular
        .module('tote.details.controller', [])
        .controller('ToteDetailsCtrl',ToteDetailCtrl);

    ToteDetailCtrl.$inject = ['$scope','$state','$yikeUtils'];

    /* @ngInject */
    function ToteDetailCtrl($scope,$state,$yikeUtils) {
        
       

    }
})();
"use strict";
angular.module("ionic-toast", ["ionic"]).run(["$templateCache", function (t) {
    var o = '<div class="ionic_toast" ng-class="ionicToast.toastClass" ng-style="ionicToast.toastStyle"><span class="ionic_toast_close" ng-click="hide()"><i class="ion-close-round toast_close_icon"></i></span><span ng-bind-html="ionicToast.toastMessage"></span></div>';
    t.put("ionic-toast/templates/ionic-toast.html", o)
}]).provider("ionicToast", function () {
    this.$get = ["$compile", "$document", "$interval", "$rootScope", "$templateCache", "$timeout", function (t, o, i, n, s, a) {
        var c, e = {
            toastClass: "",
            toastMessage: "",
            toastStyle: {display: "none", opacity: 0}
        }, l = {
            top: "ionic_toast_top",
            middle: "ionic_toast_middle",
            bottom: "ionic_toast_bottom"
        }, d = n.$new(), p = t(s.get("ionic-toast/templates/ionic-toast.html"))(d);
        d.ionicToast = e, o.find("body").append(p);
        var u = function (t, o, i) {
            d.ionicToast.toastStyle = {display: t, opacity: o}, d.ionicToast.toastStyle.opacity = o, i()
        };
        return d.hide = function () {
            u("none", 0, function () {
                console.log("toast hidden")
            })
        }, {
            show: function (t, o, i, n) {
                t && o && n && (a.cancel(c), n > 5e3 && (n = 5e3), angular.extend(d.ionicToast, {
                    toastClass: l[o] + " " + (i ? "ionic_toast_sticky" : ""),
                    toastMessage: t
                }), u("block", 1, function () {
                    i || (c = a(function () {
                        d.hide()
                    }, n))
                }))
            }, hide: function () {
                d.hide()
            }
        }
    }]
});

(function () {
  'use strict';

  angular
    .module('yike.back', [])
    .directive('yikeBack', YikeBack);

  YikeBack.$inject = ['$ionicHistory'];

  function YikeBack($ionicHistory) {
    var directive = {
      template: ' <button class="button button-clear ion-chevron-left white"></button>',
      link: link,
      replace: true,
      restrict: 'AE'
    };
    return directive;

    function link(scope, element, attrs) {
      element.bind('click', function(e) {
        $ionicHistory.goBack();
      })
    }
  }
})();

(function () {
    'use strict';

    angular
        .module('yike', ['yike.subMenu', 'yike.utils', 'ionic-toast', 'yike.back']);

})();

(function () {
  'use strict';

  angular
    .module('yike.subMenu', [])
    .directive('yikeSubMenu', yikeSubMenu);

  yikeSubMenu.$inject = [];
  function yikeSubMenu() {
    return {
      replace: false,
      restrict: 'AE',
      link: function (scope, elem, attrs) {
        scope.clickCategory = function (key) {
          scope.current.menu = key == scope.current.menu ? '' : key;
          scope.current.subMenu = [];
        };

        scope.clickMenu = function (menu) {
          if (menu.sub.length > 0) {
            scope.current.subMenu = menu.sub;
          } else {
            scope.condition[scope.current.menu] = menu;
            scope.current.menu = null;
            scope.page = 1;
            scope.query();
          }
          $('.sub').scrollTop(0);
        };

        scope.clickSubMenu = function (subMenu) {
          scope.condition[scope.current.menu] = subMenu;
          scope.current.menu = null;
          scope.page = 1;
          scope.query();
        }
      },
      templateUrl: STATIC_PATH +  'templates/utils/sub-menu.html'
    };
  }
})();

(function () {
  'use strict';

  angular
    .module('yike.utils', ['ionic'])
    .factory('$yikeUtils', $yikeUtils);

  $yikeUtils.$inject = ['$rootScope', '$state', '$ionicPopup', '$ionicModal', '$location', '$timeout', 'ionicToast', '$ionicLoading'];

  /* @ngInject */
  function $yikeUtils($rootScope, $state, $ionicPopup, $ionicModal, $location, $timeout, ionicToast, $ionicLoading) {
    return {
      go: go,
      alert: alert,
      confirm: confirm,
      show: show,
      toast: toast
    };

    ////////////////

    function go(target, params, options) {
      $state.go(target, params, options);
    }

    function toast(message, position, stick, time) {
      //position = position || 'middle';
      //stick = stick || false;
      //time = time || 3000;
      //ionicToast.show(message, position, stick, time);
      $ionicLoading.show({ template: message, noBackdrop: true, duration: 1000 });
    }

    function alert(title, template) {
      var _alert = $ionicPopup.alert({
        title: title,
        template: template,
        'okType': 'button-assertive'
      });

      $timeout(function() {
        _alert.close(); //close the popup after 3 seconds for some reason
      }, 1500);

      return _alert;
    }

    function confirm(title, template) {
      var _alert = $ionicPopup.confirm({
        'title': title,
        'template': template,
        'okType': 'button-assertive',
        'cancelText': '取消',
        'okText': '确认',
        cssClass:'red-confirm'
      });

      $timeout(function() {
        _alert.close(); //close the popup after 3 seconds for some reason
      }, 3000);

      return _alert;
    }

    function show(title, template, scope, buttons) {
      var _alert = $ionicPopup.show({
        title: title,
        template: template,
        scope: scope,
        buttons: buttons
      });
      $timeout(function() {
        _alert.close(); //close the popup after 3 seconds for some reason
      }, 3000);

      return _alert;
    }
  }
})();
