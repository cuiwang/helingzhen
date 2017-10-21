<?php defined('IN_IA') or exit('Access Denied');?>				<a href="<?php  if($_W['account']['type'] == ACCOUNT_TYPE_APP_NORMAL) { ?><?php  echo url('wxapp/display/home')?><?php  } else { ?><?php  echo url('home/welcome/platform')?><?php  } ?>" class="we7-head-back"><i class="wi wi-back-circle"></i></a>
				<span class="we7-head-account"><a href="<?php  if($_W['account']['type'] == ACCOUNT_TYPE_APP_NORMAL) { ?><?php  echo url('wxapp/display/home')?><?php  } else { ?><?php  echo url('home/welcome/platform')?><?php  } ?>"><?php  echo $_W['account']['name'];?></a></span>
				<?php  if(file_exists(IA_ROOT. "/addons/". $_W['current_module']['name']. "/icon-custom.jpg")) { ?>
				<img src="<?php  echo tomedia("addons/".$_W['current_module']['name']."/icon-custom.jpg")?>" class="head-app-logo" onerror="this.src='./resource/images/gw-wx.gif'">
								<?php  } else { ?>
				<img src="<?php  echo tomedia("addons/".$_W['current_module']['name']."/icon.jpg")?>" class="head-app-logo" onerror="this.src='./resource/images/gw-wx.gif'">
				<?php  } ?>
				<span class="font-lg"><?php  echo $_W['current_module']['title'];?></span>
				<span class="pull-right"><a href="<?php  echo url('profile/module');?>" class="color-default we7-margin-left"><i class="wi wi-cut color-default"></i>切换其他应用</a></span>
				<span class="pull-right"><a href="<?php  echo url('home/welcome/ext', array('m' => 'we7_coupon'))?>" class="color-default we7-margin-left"><i class="wi wi-redact"></i>卡券/门店/收银台设置</a></span>
				<span class="pull-right"><a href="<?php  echo url('website/wenda-show/list', array('cateid' => 1, 'modid' => $_W['current_module']['mid']));?>" class="color-default we7-margin-left"><i class="wi wi-log"></i>使用教程</a></span>
				<div class="pull-right related-info module-related-info">
				</div>
				<script>
					$.post('./index.php?c=module&a=display&do=accounts_dropdown_menu', {'module_name': "<?php  echo $_W['current_module']['name']?>"}, function(data){
						$('.module-related-info').html(data);
					}, 'html');
				</script>
				<!-- 兼容历史性问题：模块内获取不到模块信息$module的问题-start -->
				<?php  if(CRUMBS_NAV == 1) { ?>
				<?php  global $module;?>
				<?php  } ?>
				<!-- end -->
