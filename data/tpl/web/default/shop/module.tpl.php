<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<ol class="breadcrumb">
	<li><a href="./?refresh"><i class="fa fa-home"></i></a></li>
	<li><a href="<?php  echo url('system/welcome');?>">系统</a></li>
	<li class="active">模块列表</li>
</ol>
<style type="text/css">
    .panel-body > ul{list-style:none;margin: 0px;padding: 0px}
    .panel-body > ul li{display: inline-block}
</style>
<div class="main">
<ul class="nav nav-tabs">
	<li <?php  if($do == 'list') { ?>class="active"<?php  } ?>><a href="<?php  echo url('shop/module/list');?>">模块列表</a></li>
	<?php  if($do == 'post') { ?><li class="active"><a href="">编辑模块</a></li><?php  } ?>
</ul>
<?php  if($do=='post') { ?>
<form class="form-horizontal form" id="form1" action="" method="post" enctype="multipart/form-data">
		<input type="hidden" name="sid" value="<?php  echo $sid;?>">
		<div class="main">
			<div class="panel panel-default">
				<div class="panel-heading">模块基本信息</div>
				<div class="panel-body">
                    <div class="form-group">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="require">* </span>模块名称</label>
						<div class="col-sm-9 col-xs-12">	
							<input type="text" name="title" value="<?php  echo $modules['title'];?>" class="form-control" style="width:50%;">
						</div>
					</div>
					<div class="form-group">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="require">* </span>模块分类</label>
						<div class="col-sm-9 col-xs-12">	
							<input type="text" name="type" value="<?php  echo $modules['type'];?>" class="form-control" style="width:50%;">
							<span class="help-block">模块分类：biz 表示行业解决方案；business 表示行业功能；activity 表示营销活动；wdlgame 表示游戏功能；wdlshow 表示展示功能；customer 表示常用服务；other 表示其他功能</span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="require">* </span>模块标识</label>
						<div class="col-sm-9 col-xs-12"> 
							<input type="hidden" name="module" value="<?php  echo $modules['name'];?>" class="form-control">
                            <label class="col-xs-12 col-sm-3 col-md-2 "><?php  echo $modules['name'];?></label>
						</div>
					</div>
                    <div class="form-group">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="require">* </span>模块价格</label>
						<div class="col-sm-9 col-xs-12">
							<input type="text" name="price" value="<?php  echo $modules['price'];?>" class="form-control" style="width:50%;">
						</div>
					</div>
                    <div class="form-group">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label">功能简介</label>
						<div class="col-sm-9 col-xs-12">
							
                                <textarea class="form-control" name="description" rows="6"><?php  echo $modules['description'];?></textarea>
							
						</div>
					</div>
					
				</div>
			</div>		
			<div class="form-group">
				<div class="col-sm-9 col-xs-9 col-md-9">
					<input type="hidden" name="token" value="<?php  echo $_W['token'];?>">
					<input name="submit" id="submit" type="submit" value="提交" class="btn btn-primary col-lg-1">
				</div>
			</div>
		</div>
	</form>
	<script type="text/javascript">
		require(['util'], function(u){
			
			$('.field_del').click(function(){
				var id = $(this).attr('data-id');
				if(!confirm('确定删除吗')) return flase;
				$.post("<?php  echo url('shop/manage/field_del');?>", {'id':id}, function(data){
					if(data != 'success') {
						u.message(data, '', 'error');
						return false;
					}
					location.reload();
				});
			});
		});
</script>
<?php  } else if($op='list') { ?>

 <div class="panel panel-info">
			<div class="panel-heading">筛选</div>
			<div class="panel-body">
				<form action="./index.php" method="get" class="form-horizontal" role="form">
					<input type="hidden" name="c" value="shop">
					<input type="hidden" name="a" value="module">
					
					<div class="form-group clearfix">
						<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">关键字</label>
						
						<div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
							<input class="form-control" name="keyword" id="" type="text" value="<?php  echo $_GPC['keyword'];?>" placeholder="输入 模块名称 可快速查找">
						</div>
						<div class="col-xs-12 col-sm-3 col-md-2 col-lg-1">
							<button class="btn btn-default"><i class="fa fa-search"></i> 搜索</button>
						</div>
					</div>
				</form>
			</div>
		</div>

<div class="clearfix">
	<div class="panel panel-default">
		<div class="panel-body table-responsive">
			<table class="table">
				<thead>
				<tr>
					<th class="row-first" width="100px">模块名称</th>
					<th width="100px">价格</th>
					<th>功能简介</th>
					<th>操作</th>
				</tr>
				</thead>
				<tbody>
				<?php  if(is_array($modules)) { foreach($modules as $module) { ?>
                <?php  if(!$module['issystem']) { ?>
				<tr>
					<td class="row-first"><?php  echo $module['title'];?><input class="modules" type="hidden" value="<?php  echo $module['name'];?>" name="modules[]" /></td>                                       
					<td><span style="color:#F00;"><?php  echo $module['price'];?></span>元/年</td>
					<td><?php  echo $module['ability'];?></td>
					<td><?php  if($_W['isfounder']) { ?><span><a class="btn btn-default btn-sm" href="<?php  echo url('shop/module/post', array('mid' => $module['mid']));?>"><i class="fa fa-edit">编辑</i></a></span><?php  } ?>
                   <?php  if(!empty($module['outLink'])) { ?> <span><a class="btn btn-default btn-sm" href="<?php  echo $module['outLink'];?>"><i class="fa fa-eye">查看详情</i></a></span></td><?php  } ?>
				</tr>
                <?php  } ?>
				<?php  } } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
</form>
</div>
<?php  } ?>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>