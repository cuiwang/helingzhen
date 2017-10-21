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
            });

        $urlRouterProvider.otherwise('/tab/home');

    });
