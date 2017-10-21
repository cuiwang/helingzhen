// Ionic Starter App

// angular.module is a global place for creating, registering and retrieving Angular modules
// 'starter' is the name of this angular module example (also set in a <body> attribute in index.html)
// the 2nd parameter is an array of 'requires'
// 'starter.controllers' is found in controllers.js
angular.module('starter', ['ionic', 'starter.controllers', 'starter.factories'])

	.run(function($ionicPlatform, SettingFactory, $http){
		$ionicPlatform.ready(function(){
			
		});
		// 初始化 设置
		if ( !SettingFactory.get() || window.SETTING.version != SettingFactory.get('version') || window.SETTING.reset ) {
			SettingFactory.save(window.SETTING);
		}
	})

	.config(function($stateProvider, $urlRouterProvider){
		$stateProvider

			.state('app', {
				url: "/app",
				abstract: true,
				templateUrl: "../addons/meepo_bbs/template/mobile/"+MEEPO.TEMPLATE+"/templates/menu.html",
				controller: 'AppCtrl'
			})

			.state('app.topics', {
				url: "/topics/:id",
				views: {
					'content': {
						templateUrl: "../addons/meepo_bbs/template/mobile/"+MEEPO.TEMPLATE+"/templates/topics.html",
						controller: 'TopicsCtrl'
					}
				}
			})

			.state('app.topic', {
				url: "/topic/:id",
				views: {
					'content': {
						templateUrl: "../addons/meepo_bbs/template/mobile/"+MEEPO.TEMPLATE+"/templates/forum/topic.html",
						controller: 'TopicCtrl'
					}
				}
			})

			.state('app.class', {
				url: "/class",
				views: {
					'content': {
						templateUrl: "../addons/meepo_bbs/template/mobile/"+MEEPO.TEMPLATE+"/templates/threadclass.html",
						controller: 'ClassCtrl'
					}
				}
			});
		// if none of the above states are matched, use this as the fallback
		$urlRouterProvider.otherwise('/app/class');
	});

