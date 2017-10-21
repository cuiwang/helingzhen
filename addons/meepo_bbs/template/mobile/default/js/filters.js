angular.module('starter.filters', ['starter.factories'])

	.filter('TabFilter', function(){
		var text = {
			ask: '问答',
			share: '分享',
			job: '招聘',
			none: '自由',
			top: '置顶'
		};
		return function(tab, top){
			return top ? text.top : (text[tab] || text.none);
		}
	})

	.filter('fromNow', function($window){
		return function(date){
			$window.moment.lang('zh-cn');
			return $window.moment(date,'YYYY-MM-DD HH:mm:ss').fromNow();
		}
	})

	.filter('avatar', function(SettingFactory){
		var avatar = SettingFactory.get('avatar');
		return function(url){
			if ( !avatar.enabled ) {
				return '../addons/meepo_bbs/template/mobile/img/avatar.png';
			}
			return (url);
		}
	});