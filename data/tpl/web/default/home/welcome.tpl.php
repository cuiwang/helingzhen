<?php defined('IN_IA') or exit('Access Denied');?>﻿<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<div class="welcome-container" id="js-home-welcome" ng-controller="WelcomeCtrl" ng-cloak>
	<div class="welcome-container">
		<div class="panel we7-panel account-stat">
			<div class="panel-heading">
				今日关键指标/昨日关键指标
			</div>
			<div class="panel-body we7-padding-vertical">
				<div class="col-sm-3 text-center">
					<div class="title">新关注</div>
					<div>
						<span class="today" ng-init="0" ng-bind="fans_kpi.today.new"></span>
						<span class="pipe">/</span>
						<span class="yesterday" ng-init="0" ng-bind="fans_kpi.yesterday.new"></span>
					</div>
				</div>
				<div class="col-sm-3 text-center">
					<div class="title">取消关注</div>
					<div>
						<span class="today" ng-init="0" ng-bind="fans_kpi.today.cancel"></span>
						<span class="pipe">/</span>
						<span class="yesterday" ng-init="0" ng-bind="fans_kpi.yesterday.cancel"></span>
					</div>
				</div>
				<div class="col-sm-3 text-center">
					<div class="title">净增关注</div>
					<div>
						<span class="today" ng-init="0" ng-bind="fans_kpi.today.jing_num"></span>
						<span class="pipe">/</span>
						<span class="yesterday" ng-init="0" ng-bind="fans_kpi.yesterday.jing_num"></span>
					</div>
				</div>
				<div class="col-sm-3 text-center">
					<div class="title">累计关注</div>
					<div>
						<span class="today" ng-init="0" ng-bind="fans_kpi.today.cumulate"></span>
					</div>
				</div>
			</div>
		</div>
		<div class="panel we7-panel notice notice-tab">
			<div class="panel-heading">
				<a href="./index.php?c=article&a=notice-show" class="color-default pull-right">更多</a>
			</div>
			<ul class="list-group active">
				<li class="list-group-item" ng-repeat="notice in notices" ng-if="notices">
					<a ng-href="{{notice.url}}" class="text-over" target="_blank" ng-bind="notice.title"></a>
					<span class="pull-right color-gray" ng-bind="notice.createtime"></span>
				</li>
				<li class="list-group-item text-center" ng-if="!notices">
					<span>暂无数据</span>
				</li>
			</ul>
		</div>	
	</div>
</div>
<script>
	angular.module('homeApp').value('config', {
		notices: <?php echo !empty($notices) ? json_encode($notices) : 'null'?>,
	});
	angular.bootstrap($('#js-home-welcome'), ['homeApp']);
</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
