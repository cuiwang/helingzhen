<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<div class="we7-page-search">
	<form method="get" class="form-inline">
		<input type="hidden" name="a" value="manage-account">
		<input type="hidden" name="c" value="module">
		<div class="input-group we7-margin-bottom col-sm-4">
			<input class="form-control " name="keyword" value="<?php  echo $_GPC['keyword'];?>" type="text" placeholder="名称" >
			<span class="input-group-btn"><button class="btn btn-default"><i class="fa fa-search"></i></button></span>
		</div>
	</form>
</div>
<div class="js-module-display" ng-controller="ModuleMoreCtrl" ng-cloak>
	<div we7-initial-searchbar we7-search-callback="searchModule(letter)"></div>
	<div class="ext-apply-list clearfix">
		<?php  if(is_array($modules)) { foreach($modules as $row) { ?>
		<div class="ext-apply-item <?php  if($row['shortcut']) { ?>apply-show<?php  } ?>" >
			<img src="<?php  echo $row['logo'];?>" class="apply-img <?php  if(!$row['enabled']) { ?>gray<?php  } ?>" />
			<h2 class="apply-title"><?php  echo $row['title'];?></h2>
			<span class="color-green">
				<?php  if($row['enabled']) { ?>
					启用
				<?php  } else { ?>
					禁用
				<?php  } ?>
			</span>
			<div class="mask">

				<a href="<?php  echo url('home/welcome/ext', array('m' => $row['name']));?>" class="entry">
					进入应用 <i class="wi wi-angle-right"></i>
				</a>
				<?php  if($row['shortcut']) { ?>
				<a href="<?php  echo url('module/manage-account/shortcut', array('modulename' => $row['name'], 'shortcut' => STATUS_OFF))?>" onclick="return ajaxopen(this.href);" class="cut-btn" title="移出菜单">
					<i class="wi wi-remove"></i>
				</a>
				<?php  } else { ?>
				<a href="<?php  echo url('module/manage-account/shortcut', array('modulename' => $row['name'], 'shortcut' => STATUS_ON))?>" onclick="return ajaxopen(this.href);" class="cut-btn" title="显示到菜单">
					<i class="wi wi-eye"></i>
				</a>
				<?php  } ?>
				<a href="<?php  echo url('module/manage-account/top', array('modulename' => $row['name']))?>" class="stick">
					<i class="wi wi-stick-sign" title="置顶"></i>
				</a>

			</div>
		</div>
		<?php  } } ?>
	</div>
	<div class="text-right"><?php  echo $pager;?></div>
</div>
<script type="text/javascript">
	angular.module('moduleApp').value('config', {
		searchurl : "<?php  echo url('module/manage-account')?>"
	});
	angular.bootstrap($('.js-module-display'), ['moduleApp']);
</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
