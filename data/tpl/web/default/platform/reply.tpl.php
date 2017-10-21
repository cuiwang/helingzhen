<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<?php  if(in_array($m, $sysmods)) { ?>
	<div class="we7-page-title">
		自动回复
	</div>
	<ul class="we7-page-tab">
		<li <?php  if($m == 'keyword' || $m == '') { ?>class="active" <?php  } ?>><a href="<?php  echo url('platform/reply', array('m' => 'keyword'));?>">关键字自动回复 </a></li>
		<li <?php  if($m == 'special') { ?>class="active"<?php  } ?>><a href="<?php  echo url('platform/reply', array('m' => 'special'));?>">非文字自动回复</a></li>
		<li <?php  if($m == 'welcome') { ?>class="active"<?php  } ?>><a href="<?php  echo url('platform/reply', array('m' => 'welcome'));?>">首次访问自动回复</a></li>
		<li <?php  if($m == 'default') { ?>class="active"<?php  } ?>><a href="<?php  echo url('platform/reply', array('m' => 'default'));?>">默认回复</a></li>
		<li <?php  if($m == 'service') { ?>class="active"<?php  } ?>><a href="<?php  echo url('platform/reply', array('m' => 'service'));?>">常用服务</a></li>
		<li <?php  if($m == 'userapi') { ?>class="active"<?php  } ?>><a href="<?php  echo url('platform/reply', array('m' => 'userapi'));?>">自定义接口回复</a></li>
	</ul>
<?php  } else { ?>
	<div class="we7-page-title">
		入口设置
	</div>
	<ul class="we7-page-tab">
		<li class="active"><a href="<?php  echo url('platform/reply', array('m' => $m));?>">关键字链接入口 </a></li>
		<?php  if(!empty($frames['section']['platform_module_common']['menu']['platform_module_cover'])) { ?>
		<li><a href="<?php  echo url('platform/cover', array('m' => $m));?>">封面链接入口</a></li>
		<?php  } ?>
	</ul>	
<?php  } ?>
<?php  if($m == 'keyword' || $m == 'userapi' || !in_array($m, $sysmods)) { ?>
	<div id="js-keyword-display" ng-controller="KeywordDisplay" ng-cloak>
		<div class="keyword-list-head">
			<form action="./index.php" method="get" class="form-horizontal" role="form">
				<input type="hidden" name="c" value="platform">
				<input type="hidden" name="a" value="reply">
				<input type="hidden" name="m" value="<?php  echo $_GPC['m'];?>" />
				<input type="hidden" name="status" value="<?php  echo $status;?>" />
				<div class="keyword-list-head clearfix">
					<div class="pull-right">
						<a href="<?php  echo url('platform/reply/post', array('m' => $m));?>" class="btn btn-primary">+添加<?php  if($m == 'userapi') { ?>自定义接口<?php  } else { ?>关键字<?php  } ?>回复</a>
						<?php  if(in_array($m, $sysmods) && $m != 'userapi') { ?><a href="<?php  echo url('platform/reply/post', array('m' => 'apply'));?>" class="btn btn-danger  we7-margin-left">+添加应用关键字</a><?php  } ?>
					</div>
					<div class="input-group we7-margin-bottom" style="width:465px;">
						<select class="we7-select pull-left" name="search_type">
							<option value="keyword" <?php  if($_GPC['search_type'] == 'keyword') { ?>selected<?php  } ?>>关键字</option>
							<option value="rule" <?php  if($_GPC['search_type'] == 'rule') { ?>selected<?php  } ?>>规则名</option>
						</select>
						<input name="type" type="hidden" value="<?php  echo $_GPC['type'];?>">
						<input class="form-control" name="keyword" type="text" value="<?php  echo $_GPC['keyword'];?>" placeholder="输入规则名称或关键字名称" style="width: 330px;">
						<span class="input-group-btn"><button class="btn btn-default"><i class="fa fa-search"></i></button></span>
					</div>
				</div>
			</form>
		</div>
		<?php  if(in_array($m, $sysmods) && $m != 'userapi') { ?>
		<div class="btn-group we7-btn-group we7-margin-bottom btn-group-justified">
			<a href="<?php  echo url('platform/reply/display', array('m' => 'keyword'));?>" class="btn <?php  if(!$_GPC['type']) { ?>active<?php  } ?>">全部</a>
			<a href="<?php  echo url('platform/reply/display', array('m' => 'keyword', 'type' => 'news'));?>" class="btn <?php  if($_GPC['type'] == 'news') { ?>active<?php  } ?>">回复图文</a>
			<a href="<?php  echo url('platform/reply/display', array('m' => 'keyword', 'type' => 'apply'));?>" class="btn <?php  if($_GPC['type'] == 'apply') { ?>active<?php  } ?>">回复模块</a>
			<a href="<?php  echo url('platform/reply/display', array('m' => 'keyword', 'type' => 'voice'));?>" class="btn <?php  if($_GPC['type'] == 'voice') { ?>active<?php  } ?>">回复语音</a>
			<a href="<?php  echo url('platform/reply/display', array('m' => 'keyword', 'type' => 'basic'));?>" class="btn <?php  if($_GPC['type'] == 'basic') { ?>active<?php  } ?>">回复文字</a>
			<a href="<?php  echo url('platform/reply/display', array('m' => 'keyword', 'type' => 'music'));?>" class="btn <?php  if($_GPC['type'] == 'music') { ?>active<?php  } ?>">回复音乐</a>
			<a href="<?php  echo url('platform/reply/display', array('m' => 'keyword', 'type' => 'images'));?>" class="btn <?php  if($_GPC['type'] == 'images') { ?>active<?php  } ?>">回复图片</a>
			<a href="<?php  echo url('platform/reply/display', array('m' => 'keyword', 'type' => 'video'));?>" class="btn <?php  if($_GPC['type'] == 'video') { ?>active<?php  } ?>">回复视频</a>
		</div>
		<?php  } ?>
		<div class="clearfix"></div>
		<div class="table we7-tables <?php  if(!in_array($m, $sysmods)) { ?> we7-padding-bottom <?php  } ?>">
			<form action="<?php  echo url('platform/reply/delete');?>" method="post" role="form" class="form we7-form" id="form1">
				<input type="hidden" name="m" value="<?php  echo $m;?>">
				<?php  if(!empty($replies)) { ?>
					<?php  if(is_array($replies)) { foreach($replies as $row) { ?>
					<table class="table we7-table table-hover">
						<col width="80px"/>
						<col width=""/>
						<col width="120px"/>
						<col width="230px"/>
						<tr>
							<th class="text-left"  colspan="2">
								<div class="pull-left">
									<input id='rid-<?php  echo $row['id'];?>' type="checkbox" name='rid[]' we7-check-all="we7-check-all" value="<?php  echo $row['id'];?>"/>
									<label class="text-over reply-item-name" for="rid-<?php  echo $row['id'];?>">&nbsp;</label>
									<label class="text-over reply-item-name"><?php  if(!empty($row['name'])) { ?>规则名：<?php  echo $row['name'];?><?php  } ?></label>
								</div>
								<span class="pull-right">
									<?php  if($row['displayorder'] > 0) { ?>
										<?php  if($row['displayorder'] == '255') { ?>
											<span class="label label-primary">置顶</span>
										<?php  } else { ?>
											<span class="label label-info">优先级 <?php  echo $row['displayorder'];?></span>
										<?php  } ?>
									<?php  } ?>
								</span>
							</th>
							<th>是否开启</th>
							<th class="text-right">操作</th>
						</tr>
						<tr>
							<?php  if(!in_array($row['module'], $sysmods)) { ?>
								
							<td class="vertical-middle">
								<img src="<?php  echo $row['module_info']['logo'];?>" alt="" class="keyword-img"/>
							</td>
							<td class="text-left">
							<?php  } else { ?>
							<td class="text-left" colspan="2">
							<?php  } ?>
								<div class="we7-form reply-item-keyword">
									<div class="form-inline">
										<label for="" class="control-label">关&nbsp;键&nbsp;字</label>
										<div class="form-controls form-control-static">
											<?php  if(is_array($row['keywords'])) { foreach($row['keywords'] as $kw) { ?>
											<span class="keyword-tag" data-toggle="tooltip" data-placement="bottom" title="<?php  if($kw['type']==1) { ?>此关键字为精准触发<?php  } else if($kw['type']==2) { ?>此关键字为包含触发<?php  } else if($kw['type']==3) { ?>此关键字为正则匹配触发<?php  } ?>"><?php  echo $kw['content'];?></span>
											<?php  if($kw['type'] == 4) { ?><span class="form-control-static keyword-tag" data-toggle="tooltip" data-placement="bottom" title="托管">优先级在<?php  echo $row['displayorder'];?>之下直接生效</span><?php  } ?>
											<?php  } } ?>
										</div>
										<div class="form-inline">
										<label for="" class="control-label col-sm-2">回复内容</label>
										<div class="form-controls form-control-static">
											<span class="">
											<?php  if($m == 'userapi') { ?>
												自定义
											<?php  } else if(in_array($row['module'], $sysmods)) { ?>
												共<?php  echo $row['allreply']['sum'];?>条（<?php  if($row['allreply']['basic'] > 0) { ?><?php  echo $row['allreply']['basic'];?>条文字 <?php  } ?><?php  if($row['allreply']['images'] > 0) { ?><?php  echo $row['allreply']['images'];?>条图片 <?php  } ?><?php  if($row['allreply']['news'] > 0) { ?><?php  echo $row['allreply']['news'];?>条图文 <?php  } ?><?php  if($row['allreply']['music'] > 0) { ?><?php  echo $row['allreply']['music'];?>条音乐 <?php  } ?><?php  if($row['allreply']['voice'] > 0) { ?><?php  echo $row['allreply']['voice'];?>条语音 <?php  } ?><?php  if($row['allreply']['video'] > 0) { ?><?php  echo $row['allreply']['video'];?>条视频 <?php  } ?><?php  if($row['allreply']['wxcard'] > 0) { ?><?php  echo $row['allreply']['wxcard'];?>条卡券<?php  } ?>）
											<?php  } else { ?>
												<?php  echo cutstr($row['module_info']['title'], 10);?>应用
											<?php  } ?>
											</span>
										</div>
									</div>
								</div>
							</td>
							<td class="vertical-middle">
								<label>
									<div class="switch <?php  if($row['status']) { ?> switchOn<?php  } ?>" id="key-<?php  echo $row['id'];?>" ng-click="changeStatus(<?php  echo $row['id'];?>)"></div>
								</label>
							</td>
							<td class="vertical-middle text-right">
								<div class="link-group">
									<?php  if(in_array($row['module'], $sysmods)) { ?>
									<a href="<?php  echo url('platform/reply/post', array('m' => $m, 'rid' => $row['id']))?>">编辑</a>
									<?php  } else { ?>
									<a href="<?php  echo url('platform/reply/post', array('m' => $row['module'], 'rid' => $row['id']))?>">编辑</a>
									<?php  } ?>
									<a href="<?php  echo url('platform/reply/delete', array('m' => $m, 'rid' => $row['id']))?>" class="del" onclick="return confirm('删除规则将同时删除关键字与回复，确认吗？');return false;">删除</a>
									<?php  if(is_array($entries['rule'])) { foreach($entries['rule'] as $ext_menu) { ?>
									<a href="<?php  echo $ext_menu['url'];?>&id=<?php  echo $row['id'];?>&rid=<?php  echo $row['id'];?>"><?php  echo $ext_menu['title'];?></a>
									<?php  } } ?>
								</div>
							</td>
						</tr>
					</table>
					<?php  } } ?>
					<div>
						<input type="checkbox" name="rid[]" we7-check-all="we7-check-all" id="select_all"  class="we7-margin-left" value="1" />
						<label for="select_all">&nbsp;</label>
						<input type="submit" class="btn btn-danger" value="删除" onclick="if(!confirm('确定删除选中的规则吗？')) return false;"/>
						<input type="hidden" name="token" value="<?php  echo $_W['token'];?>"/>
						<div class="text-right">
							<?php  echo $pager;?>
						</div>
					</div>
				<?php  } else { ?>
					<p class="text-center we7-margin-top">暂无数据</p>
				<?php  } ?>
			</form>
		</div>
	</div>
	<script>
		$(function () {
			$('[data-toggle="tooltip"]').tooltip();
			$('#select_all').click(function(){
				$('#form1 :checkbox').prop('checked', $(this).prop('checked'));
			});
			$('#form1 :checkbox').click(function(){
				if(!$(this).prop('checked')) {
					$('#select_all').prop('checked', false);
				} else {
					var flag = 0;
					$('#form1 :checkbox[name="rid[]"]').each(function(){
						if(!$(this).prop('checked') && !flag) {
							flag = 1;
						}
					});
					if(flag) {
						$('#select_all').prop('checked', false);
					} else {
						$('#select_all').prop('checked', true);
					}
				}
			});
		});
		angular.bootstrap($('#js-keyword-display'), ['replyFormApp']);
	</script>
<?php  } else if($m == 'special') { ?>
	<div class="NoKeyword-list" id="js-special-display" ng-controller="SpecialDisplay" ng-cloak>
		<div class="table we7-tables">
			<table class="table we7-table table-hover vertical-middle">
				<col width="160px"/>
				<col />
				<col width="120px"/>
				<col width="180px"/>
				<tr>
					<th class="text-left">类型</th>
					<th class="text-left">关键字/模块</th>
					<th>状态</th>
					<th class="text-left">操作</th>
				</tr>
				<?php  if(is_array($mtypes)) { foreach($mtypes as $name => $title) { ?>
				<tr>
					<td class="text-left">
						<?php  echo $title;?>
					</td>
					<td class="text-left">
						<?php  if(!empty($setting[$name]['type'])) { ?>
						<?php  if($setting[$name]['type'] == 'keyword') { ?><?php  echo $setting[$name]['keyword'];?><?php  } else { ?><?php  echo $module[$setting[$name]['module']]['title'];?><?php  } ?>
						<?php  } else { ?>
						<?php  if(!empty($setting[$name]['keyword'])) { ?><?php  echo $setting[$name]['keyword'];?><?php  } else { ?><?php  echo $module[$setting[$name]['module']]['title'];?><?php  } ?>
						<?php  } ?>
					</td>
					<td>
						<label>
							<div ng-class="switch_class['<?php  echo $name;?>']" ng-click="changestatus('<?php  echo $name;?>')"></div>
						</label>
					</td>
					<td>
						<div class="link-group text-left">
							<a href="<?php  echo url('platform/reply/post', array('m' => 'special', 'type' => $name))?>" class="keyword-link">编辑</a>
						</div>
					</td>
				</tr>
				<?php  } } ?>
			</table>
		</div>
	</div>
	<script>
		$(function() {
			angular.module('replyFormApp').value('config', {
				<?php  if(is_array($mtypes)) { foreach($mtypes as $name => $title) { ?>
					'<?php  echo $name;?>' : '<?php  echo $setting[$name]['type'];?>',
				<?php  } } ?>
				'url' : '<?php  echo url('platform/reply/change_status')?>'
			});
			angular.bootstrap($('#js-special-display'), ['replyFormApp']);
		});
	</script>
<?php  } else if($m == 'welcome') { ?>
	<div class="alert we7-page-alert">
		<i class="wi wi-info-sign"></i>用户关注公众号时，发送的欢迎信息。
	</div>
	<div class="new-keyword" id="welcome" ng-cloak>
		<div class="we7-form" ng-controller="WelcomeDisplay">
			<form id="reply-form" class="form-horizontal form" action="<?php  echo url('platform/reply/post', array('m' => $m, 'rid' => $rule_keyword_id))?>" method="post" enctype="multipart/form-data">
				<div>
					<?php  echo module_build_form('core', $rule_keyword_id, array('keyword' => false))?>
				</div>
				<input type="submit" name="submit"  value="保存" class="btn btn-primary" style="padding: 6px 50px;"/>
				<input type="hidden" name="token" value="<?php  echo $_W['token'];?>">
				<input type="hidden" name="m" value="<?php  echo $m;?>">
				<input type="hidden" name="type" value="<?php  echo $type;?>">
			</form>
		</div>
	</div>
	<script>
		require(['underscore'], function() {
			angular.bootstrap($('#welcome'), ['replyFormApp']);
		});
	</script>
<?php  } else if($m == 'default') { ?>
	<div class="alert we7-page-alert">
		<i class="wi wi-info-sign"></i>当系统不知道该如何回复粉丝的消息时，默认发送的内容。
	</div>
	<div class="new-keyword" id="default">
		<div id="a" class="we7-form" ng-controller="DefaultDisplay">
			<form id="reply-form" class="form-horizontal form" action="<?php  echo url('platform/reply/post', array('m' => $m, 'rid' => $rule_keyword_id))?>" method="post" enctype="multipart/form-data">
				<div>
					<?php  echo module_build_form('core', $rule_keyword_id, array('keyword' => false))?>
				</div>
				<input type="submit" name="submit"  value="保存" class="btn btn-primary" style="padding: 6px 50px;"/>
				<input type="hidden" name="token" value="<?php  echo $_W['token'];?>">
				<input type="hidden" name="m" value="<?php  echo $m;?>">
				<input type="hidden" name="type" value="<?php  echo $type;?>">
			</form>
		</div>
	</div>
	<script>
		require(['underscore'], function() {
			angular.bootstrap($('#default'), ['replyFormApp']);
		});
	</script>
<?php  } else if($m == 'service') { ?>
	<div class="NoKeyword-list" id="js-service-display" ng-controller="serviceDisplay" ng-cloak>
		<div class="table we7-tables">
			<table class="table we7-table table-hover">
				<col width="160px"/>
				<col />
				<col width="120px"/>
				<tr>
					<th class="text-left">服务名称</th>
					<th class="text-left">功能说明</th>
					<th>状态</th>
				</tr>
				<tr ng-repeat="(id, api) in service track by id" ng-if="service">
					<td class="text-left">
						{{ api.name }}
					</td>
					<td class="text-left" ng-bind-html="api.description">
					</td>
					<td class="vertical-middle">
						<label>
							<div ng-class="api.switch == 'checked' ? 'switch switchOn' : 'switch'" ng-click="changeStatus(id)"></div>
						</label>
					</td>
				</tr>
				<tr ng-if="!service">
					<td colspan="3" class="text-center">
						暂无数据
					</td>
				</tr>
			</table>
		</div>
	</div>
	<script>
		$(function() {
			angular.module('replyFormApp').value('config', {
				'url' : "<?php  echo url('platform/reply/change_status')?>",
				'service' : <?php echo !empty($service_list) ? json_encode($service_list) : 'null'?>
			});
			angular.bootstrap($('#js-service-display'), ['replyFormApp']);
		});
	</script>
<?php  } ?>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>