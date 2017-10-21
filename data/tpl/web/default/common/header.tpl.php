<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/header-base', TEMPLATE_INCLUDEPATH)) : (include template('common/header-base', TEMPLATE_INCLUDEPATH));?>
<?php  if(FRAME == 'system') { ?>
<?php  cache_build_frame_menu();?>
<?php  } ?>
<div data-skin="default" class="skin-default <?php  if($_GPC['main-lg']) { ?> main-lg-body <?php  } ?>">
<?php  $frames = buildframes(FRAME);_calc_current_frames($frames);?>
<div class="head">
	<nav class="navbar navbar-default" role="navigation">
		<div class="container <?php  if(!empty($frames['section']['platform_module_menu']['plugin_menu'])) { ?>plugin-head<?php  } ?>">
			<div class="navbar-header">
				<a class="navbar-brand" href="<?php  echo $_W['siteroot'];?>">
					<img src="<?php  if(!empty($_W['setting']['copyright']['blogo'])) { ?><?php  echo tomedia($_W['setting']['copyright']['blogo'])?><?php  } else { ?>./resource/images/logo/logo.png<?php  } ?>" class="pull-left" width="110px" height="35px">
					<span class="version hidden"><?php echo IMS_VERSION;?></span>
				</a>
			</div>
			<?php  if(!empty($_W['uid'])) { ?>
			<div class="collapse navbar-collapse">
				<ul class="nav navbar-nav navbar-left">
					<?php  global $top_nav?>
					<?php  if(is_array($top_nav)) { foreach($top_nav as $nav) { ?>
					<li<?php  if(FRAME == $nav['name']) { ?> class="active"<?php  } ?>><a href="<?php  if(empty($nav['url'])) { ?><?php  echo url('home/welcome/' . $nav['name']);?><?php  } else { ?><?php  echo $nav['url'];?><?php  } ?>" <?php  if(!empty($nav['blank'])) { ?>target="_blank"<?php  } ?>><?php  echo $nav['title'];?></a></li>
					<?php  } } ?>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"><i class="wi wi-user color-gray"></i><?php  echo $_W['user']['username'];?> <span class="caret"></span></a>
						<ul class="dropdown-menu color-gray" role="menu">
							<li>
								<a href="<?php  echo url('user/profile');?>" target="_blank"><i class="wi wi-account color-gray"></i> 我的账号</a>
							</li>
							<?php  if($_W['isfounder']) { ?>
							<li class="divider"></li>
							<?php  if(uni_user_see_more_info(ACCOUNT_MANAGE_NAME_VICE_FOUNDER, false)) { ?>
							<li><a href="<?php  echo url('cloud/upgrade');?>" target="_blank"><i class="wi wi-update color-gray"></i> 自动更新</a></li>
							<?php  } ?>
							<li><a href="<?php  echo url('system/updatecache');?>" target="_blank"><i class="wi wi-cache color-gray"></i> 更新缓存</a></li>
							<li class="divider"></li>
							<?php  } ?>
							<li>
								<a href="<?php  echo url('user/logout');?>"><i class="fa fa-sign-out color-gray"></i> 退出系统</a>
							</li>
						</ul>
					</li>
				</ul>
			</div>
			<?php  } else { ?>
			<div class="collapse navbar-collapse">
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown"><a href="<?php  echo url('user/register');?>">注册</a></li>
					<li class="dropdown"><a href="<?php  echo url('user/login');?>">登录</a></li>
				</ul>
			</div>
			<?php  } ?>
		</div>
	</nav>
</div>
<?php  if(empty($_COOKIE['check_setmeal']) && !empty($_W['account']['endtime']) && ($_W['account']['endtime'] - TIMESTAMP < (6*86400))) { ?> 
<div class="system-tips we7-body-alert" id="setmeal-tips">
	<div class="container text-right">
		<div class="alert-info">
			<a href="<?php  if($_W['isfounder']) { ?><?php  echo url('user/edit', array('uid' => $_W['account']['uid']));?><?php  } else { ?>javascript:void(0);<?php  } ?>">
				该公众号管理员服务有效期：<?php  echo date('Y-m-d', $_W['account']['starttime']);?> ~ <?php  echo date('Y-m-d', $_W['account']['endtime']);?>.
				<?php  if($_W['account']['endtime'] < TIMESTAMP) { ?>
				目前已到期，请联系管理员续费
				<?php  } else { ?>
				将在<?php  echo floor(($_W['account']['endtime'] - strtotime(date('Y-m-d')))/86400);?>天后到期，请及时付费
				<?php  } ?>
			</a>
			<span class="tips-close" onclick="check_setmeal_hide();"><i class="wi wi-error-sign"></i></span>
		</div>
	</div>
</div>
<script>
	function check_setmeal_hide() {
		util.cookie.set('check_setmeal', 1, 1800);
		$('#setmeal-tips').hide();
		return false;
	}
</script>
<?php  } ?> 
<div class="main">
<?php  if(!defined('IN_MESSAGE')) { ?>
<div class="container">
	<a href="javascript:;" class="js-big-main button-to-big color-gray" title="加宽"><?php  if($_GPC['main-lg']) { ?>正常<?php  } else { ?>宽屏<?php  } ?></a>
	<?php  if(in_array(FRAME, array('account', 'system',  'wxapp', 'site')) && !in_array($_GPC['a'], array('news-show', 'notice-show'))) { ?>
	<div class="panel panel-content main-panel-content <?php  if(!empty($frames['section']['platform_module_menu']['plugin_menu'])) { ?>panel-content-plugin<?php  } ?>">
		<div class="content-head panel-heading main-panel-heading">
			<?php  if(($_GPC['c'] != 'cloud' && !empty($_GPC['m']) && !in_array($_GPC['m'], array('keyword', 'special', 'welcome', 'default', 'userapi', 'service'))) || defined('IN_MODULE')) { ?>
				<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/header-module', TEMPLATE_INCLUDEPATH)) : (include template('common/header-module', TEMPLATE_INCLUDEPATH));?>
			<?php  } else { ?>
				<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/header-' . FRAME, TEMPLATE_INCLUDEPATH)) : (include template('common/header-' . FRAME, TEMPLATE_INCLUDEPATH));?>
			<?php  } ?>
		</div>
	<div class="panel-body clearfix main-panel-body <?php  if(!empty($_W['setting']['copyright']['leftmenufixed'])) { ?>menu-fixed<?php  } ?>">
		<?php  if(($_GPC['a'] != 'zhuanti')) { ?>
		<div class="left-menu">
			<?php  if(empty($frames['section']['platform_module_menu']['plugin_menu'])) { ?>
			<div class="left-menu-content">
				<?php  if(is_array($frames['section'])) { foreach($frames['section'] as $frame_section_id => $frame_section) { ?>
				<?php  if(!isset($frame_section['is_display']) || !empty($frame_section['is_display'])) { ?>
				<div class="panel panel-menu">
					<?php  if($frame_section['title']) { ?>
					<div class="panel-heading">
						<span class="no-collapse"><?php  echo $frame_section['title'];?><i class="wi wi-appsetting pull-right setting"></i></span>
					</div>
					<?php  } ?>
					<ul class="list-group">
						<?php  if(is_array($frame_section['menu'])) { foreach($frame_section['menu'] as $menu_id => $menu) { ?>
						<?php  if(!empty($menu['is_display'])) { ?>
								<?php  if($menu_id == 'platform_module_more') { ?>
									<li class="list-group-item list-group-more">
										<a href="<?php  echo url('module/manage-account');?>"><span class="label label-more">更多应用</span></a>
									</li>
									<?php  } else { ?>
									<?php  if((in_array($_W['role'], array(ACCOUNT_MANAGE_NAME_OWNER, ACCOUNT_MANAGE_NAME_FOUNDER)) && $menu_id == 'front_download' || $menu_id != 'front_download') && !($menu_id == 'platform_menu' && $_W['account']['level'] == ACCOUNT_SUBSCRIPTION)) { ?>
									<li class="list-group-item <?php  if($menu['active']) { ?>active<?php  } ?>">
										<a href="<?php  echo $menu['url'];?>" class="text-over" <?php  if($frame_section_id == 'platform_module') { ?>target="_blank"<?php  } ?>>
										<?php  if($menu['icon']) { ?>
											<?php  if($frame_section_id == 'platform_module') { ?>
												<img src="<?php  echo $menu['icon'];?>"/>
											<?php  } else { ?>
												<i class="<?php  echo $menu['icon'];?>"></i>
											<?php  } ?>
										<?php  } ?>
										<?php  echo $menu['title'];?>
										</a>
									</li>
									<?php  } ?>
								<?php  } ?>
							<?php  } ?>
						<?php  } } ?>
					</ul>
				</div>
				<?php  } ?>
				<?php  } } ?>
			</div>
			<?php  } else { ?>
				<div class="plugin-menu clearfix">
					<div class="plugin-menu-main pull-left">
						<ul class="list-group">
							<li class="list-group-item<?php  if($_W['current_module']['name'] == $frames['section']['platform_module_menu']['plugin_menu']['main_module']) { ?> active<?php  } ?>">
								<a href="<?php  echo url('home/welcome/ext', array('m' => $frames['section']['platform_module_menu']['plugin_menu']['main_module']))?>">
									<i class="wi wi-main-apply"></i>
									<div>主应用</div>
								</a>
							</li>
							<li class="list-group-item">
								<div>插件</div>
							</li>
							<?php  if(is_array($frames['section']['platform_module_menu']['plugin_menu']['menu'])) { foreach($frames['section']['platform_module_menu']['plugin_menu']['menu'] as $plugin_name => $plugin) { ?>
							<li class="list-group-item<?php  if($_W['current_module']['name'] == $plugin_name) { ?> active<?php  } ?>">
								<a href="<?php  echo url('home/welcome/ext', array('m' => $plugin_name))?>">
									<img src="<?php  echo $plugin['icon'];?>" alt="" class="img-icon" />
									<div><?php  echo $plugin['title'];?></div>
								</a>
							</li>
							<?php  } } ?>
						</ul>
						<?php  unset($plugin_name);?>
						<?php  unset($plugin);?>
					</div>
					<div class="plugin-menu-sub pull-left">
						<?php  if(is_array($frames['section'])) { foreach($frames['section'] as $frame_section_id => $frame_section) { ?>
						<?php  if(!isset($frame_section['is_display']) || !empty($frame_section['is_display'])) { ?>
							<div class="panel panel-menu">
								<?php  if($frame_section['title']) { ?>
								<div class="panel-heading">
									<span class="no-collapse"><?php  echo $frame_section['title'];?><i class="wi wi-appsetting pull-right setting"></i></span>
								</div>
								<?php  } ?>
								<ul class="list-group panel-collapse">
									<?php  if(is_array($frame_section['menu'])) { foreach($frame_section['menu'] as $menu_id => $menu) { ?>
									<?php  if(!empty($menu['is_display'])) { ?>
									<?php  if($menu_id == 'platform_module_more') { ?>
									<li class="list-group-item list-group-more">
										<a href="<?php  echo url('module/manage-account');?>"><span class="label label-more">更多应用</span></a>
									</li>
									<?php  } else { ?>
									<li class="list-group-item <?php  if($menu['active']) { ?>active<?php  } ?>">
										<a href="<?php  echo $menu['url'];?>" class="text-over" <?php  if($frame_section_id == 'platform_module') { ?>target="_blank"<?php  } ?>>
										<?php  if($menu['icon']) { ?>
											<?php  if($frame_section_id == 'platform_module') { ?>
											<img src="<?php  echo $menu['icon'];?>"/>
											<?php  } else { ?>
											<i class="<?php  echo $menu['icon'];?>"></i>
											<?php  } ?>
										<?php  } ?>
										<?php  echo $menu['title'];?>
										</a>
									</li>
									<?php  } ?>
									<?php  } ?>
									<?php  } } ?>
								</ul>
							</div>
						<?php  } ?>
						<?php  } } ?>
					</div>
				</div>
			<?php  } ?>
		</div>
		<?php  } ?>
		<div class="right-content">
	<?php  } ?>
<?php  } ?>
