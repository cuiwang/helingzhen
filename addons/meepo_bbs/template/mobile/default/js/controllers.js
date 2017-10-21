angular.module('starter.controllers', ['starter.factories', 'starter.services', 'starter.filters' ])

	
	.controller('AppCtrl', function($scope, $location, $ionicModal, $ionicPopup, CacheFactory, SettingFactory, HttpFactory){

		$scope.accessToken = MEEPO.OPENID;
		$scope.title = MEEPO.SETTING.title;
		$scope.setting = SettingFactory.get();

		// 保存头像设置
		$scope.settingAvatar = function(){
			SettingFactory.save('avatar', $scope.setting.avatar);
		};

		// 暂且复制上面的
		$scope.settingImage = function(){
			SettingFactory.save('image', $scope.setting.image);
		};

		// 修改小尾巴
		$scope.settingTail = function(){
			$ionicPopup.prompt({
				title: '新的小尾巴：',
				inputType: 'text',
				inputPlaceholder: $scope.setting.tail,
				cancelText: '取消',
				okText: '保存',
				okType: 'button-balanced'
			}).then(function(tail){
				if ( tail == undefined ) return;
				// 补全markdown格式小尾巴
				tail = tail.indexOf('\n') != 0 ? '\n----------\n' + tail : '';
				// 新建话题的内容尾巴直接改掉算了。
				SettingFactory.save('tail', $scope.topicParams.content = $scope.setting.tail = tail);
			});
		};

		/**
		 * 登录
		 */
		$scope.loginParams = {};

		$ionicModal.fromTemplateUrl('../addons/meepo_bbs/template/mobile/'+MEEPO.TEMPLATE+'/templates/login.html', {
			scope: $scope
		}).then(function(modal){
			$scope.loginModal = modal;
		});

		$scope.saveToken = function(){
			if (MEEPO.OPENID != null) {
				CacheFactory.save('accessToken', $scope.accessToken = MEEPO.OPENID);
				$scope.loginModal.hide();
				$scope.loginParams = {};
			} else {
				$scope.loginParams.message = '请用微信浏览器打开浏览！';
			}
		};

		// 退出
		$scope.quit = function(){
			$scope.accessToken = null;
			CacheFactory.remove('accessToken');
		};



	})

	// 主题首页
	.controller('TopicsCtrl', function($scope,$ionicModal,$location, $stateParams,HttpFactory, CacheFactory){

		$scope.id = $stateParams.id;

		
		
		$scope.refresh = function(){

			HttpFactory.send({
				url: MEEPO.TOPICS,
				params: {
					id : $scope.id
				},
				scope: $scope
			}).success(function(response){
				if ( !!response.data ) {
					CacheFactory.save('topics', {
						data: $scope.topics = response.data,
						tab: $scope.tab
					});
					$scope.$broadcast('scroll.refreshComplete');
				}
			});
		};
		$scope.refresh();

		/**
		 * 发布话题 modal
		 */
		$ionicModal.fromTemplateUrl('../addons/meepo_bbs/template/mobile/'+MEEPO.TEMPLATE+'/templates/new_topic.html', {
			scope: $scope
		}).then(function(modal){
			$scope.newTopicModal = modal;
		});

		$scope.topicParams = {
			title: '',
			tab: 'share',
			typeid : $scope.id,
			content: '' + $scope.setting.tail
		};

		$scope.newTopic = function(){
			HttpFactory.send({
				url: MEEPO.NEW_TOPICS,
				data: $scope.topicParams,
				method: 'post',
				mask: true
			}).success(function(response){
				if ( response.success ) {
					$scope.newTopicModal.hide();
					$location.url('/app/topic/' + response.topic_id);
				}
				$scope.topicParams = {tab: 'share'};
			});
		};

	})

	.controller('ClassCtrl',function($scope,HttpFactory,CacheFactory){
		
		
		$scope.refreshclass = function(){

			HttpFactory.send({
				url: MEEPO.CLASS,
				params: {
					tab: $scope.tab
				},
				scope: $scope
			}).success(function(response){
				if ( !!response.data ) {
					$scope.threadclass = response.data;
					$scope.tab;
					$scope.$broadcast('scroll.refreshComplete');
				}
			});
		};

		$scope.refreshclass();
	})

	// 主题详情
	.controller('TopicCtrl', function($scope, $stateParams, $ionicModal, $ionicPopup, HttpFactory, ImageService){

		$scope.id = $stateParams.id;

		var updateTopic = function(all){
			HttpFactory.send({
				url: MEEPO.TOPIC_DETAIL,
				params: {
					id: $scope.id
				},
				scope: $scope
			}).success(function(response){
				if ( !!response.data ) {
					if ( all ) {
						response.data.content = ImageService.replaceUrlString(response.data.content);
						$scope.topic = response.data;
					} else {
						$scope.topic.replies = response.data.replies;
					}
				} else {
					$scope.topic = {
						isNotExists: true
					}
				}
			});
		};

		updateTopic(true);

		$scope.updateHeart = function(replie){
			HttpFactory.send({
				url: MEEPO.UPS.formatParam(replie.id),
				method: 'post',
				data: replie,
				mask: true
			}).success(function(response){
				if ( response.success ) {
					replie.ups = response.action == 'up' ? [1] : replie.ups = [];
				} else if ( response.error_msg == ERROR.HEHE_YOU_CANNOT ) {
					$ionicPopup.alert({
						title: ERROR.HEHE_YOU_CANNOT,
						template: '上面的标题，是原话...',
						buttons: [
							{ text: '靠', type: 'button-balanced' }
						]
					});
				}
			});
		};

		/**
		 * 评论
		 */
		$ionicModal.fromTemplateUrl('../addons/meepo_bbs/template/mobile/'+MEEPO.TEMPLATE+'/templates/new_replie.html', {
			scope: $scope
		}).then(function(modal){
			$scope.newReplieModal = modal;
		});

		$scope.at = '';

		$scope.preReplie = function(id, name){
			$scope.at = name || name;
			$scope.replieParams = {
				reply_id: id || '',
				content: '@' + $scope.at + '：'
			};
			$scope.newReplieModal.show();
		};

		$scope.newReplie = function(){
			HttpFactory.send({
				url: MEEPO.REPLIES.formatParam($scope.id),
				data: $scope.replieParams,
				method: 'post',
				mask: true
			}).success(function(response){
				if ( response.success ) {
					$scope.newReplieModal.hide();
					updateTopic();
				}else{

				}
			});
		};

	});