<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<style>
	.js-test .item:hover{background-color:#f5f5f5;}
</style>
	<ol class="breadcrumb">
		<li><a href="./?refresh"><i class="fa fa-home"></i></a></li>
		<li><a href="<?php  echo url('system/welcome');?>">系统</a></li>
		<li class="active">订阅管理</li>
	</ol>
<div class="alert alert-info">
如果模块测试订阅消息失败，为了不影响系统整体通知，请禁用这些通知失败的模块
</div>
<?php  if(is_array($module_subscribes)) { foreach($module_subscribes as $module_name => $module_value) { ?>
	<div class="panel panel-default js-test" modulename="<?php  echo $module_name;?>">
		<div class="panel-heading clearfix">
			<div class="pull-right">
				<input class="js-flag" type="checkbox" modulename="<?php  echo $module_name;?>" <?php  if(!in_array($module_name, $module_ban)) { ?> checked="checked" <?php  } ?>/>
			</div>
			<?php  echo $modules[$module_name]['title'];?> (<?php  echo $module_name;?>)
		</div>
		<div class="panel-body clearfix">
			<?php  if(is_array($module_value)) { foreach($module_value as $v) { ?>
			<?php  if($v != 'text' && $v != 'enter') { ?>
			<div class="col-md-3 col-sm-4 col-xs-6 item" style="line-height: 30px; cursor:pointer;">
				<?php  echo $mtypes[$v];?>
				<p class="pull-right"></p>
			</div>
			<?php  } ?>
			<?php  } } ?>
		</div>
	</div>
<?php  } } ?>

<script type="text/javascript">

require(['bootstrap.switch'],function($){
	$('.js-flag:checkbox').bootstrapSwitch({onText: '启用', offText: '禁用'});
	$('.js-flag:checkbox').on('switchChange.bootstrapSwitch', function(event, state) {
		var modulename = $(this).attr('modulename');
		var ban = state ? 1 : 0;
		$.getJSON("<?php  echo url('system/subscribe/ban')?>", {modulename:modulename, ban:ban}, function(data) {
			var data = eval(data.message);
		});
	});
});

$(function() {
	$('.js-test').each(function() {
		var modulename = $(this).attr('modulename');
		var result = $(this).find('p');
		var module_subscribe = $(this).children('.item');
		$.post("<?php  echo url('system/subscribe/check')?>", {modulename:modulename}, function(data){
			if(data != 'success') {
				result.html('<span class="label label-danger"> 失败</span>');
			} else {
				result.html('<span class="label label-success"> 正常</span');
			}
		});
	});
});
</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>