<?php
global $_W,$_GPC;
$item = pdo_fetch('SELECT * FROM ' . tablename('site_multi') . ' WHERE uniacid = :uniacid limit 1', array(':uniacid' => $_W['uniacid']));
$urls = array(
		array(
				'url'=>array(
						array('url'=>$this->createWebUrl('adv'),'title'=>'广告列表','icon'=>'fa fa-bars'),
						array('url'=>$this->createWebUrl('adv',array('op'=>'post')),'title'=>'添加广告','icon'=>'fa fa-plus-square-o'),
						array('url'=>$this->createWebUrl('nav',array('op'=>'display')),'title'=>'首页导航列表','icon'=>'fa fa-bars'),
						array('url'=>$this->createWebUrl('nav',array('op'=>'post')),'title'=>'添加首页导航','icon'=>'fa fa-plus-square-o'),
						
				),
				'head'=>'广告管理',
				'icon'=>'fa fa-plane'
		),

		array(
				'url'=>array(
						array('url'=>$this->createWebUrl('threadclass'),'title'=>'板块管理','icon'=>'fa fa-cog'),
						array('url'=>$this->createWebUrl('threadclass',array('foo'=>'create')),'title'=>'添加板块','icon'=>'fa fa-plus-square-o'),
						array('url'=>$this->createWebUrl('forum_post'),'title'=>'添加帖子','icon'=>'fa fa-plus-square-o'),
						array('url'=>$this->createWebUrl('manage',array('tab'=>'new')),'title'=>'最新帖子','icon'=>'fa fa-cog'),
						array('url'=>$this->createWebUrl('manage',array('tab'=>'wait')),'title'=>'待审核帖子','icon'=>'fa fa-cog'),
						array('url'=>$this->createWebUrl('manage',array('tab'=>'top')),'title'=>'置顶帖子','icon'=>'fa fa-cog'),
						array('url'=>$this->createWebUrl('manage',array('tab'=>'jing')),'title'=>'精品帖子','icon'=>'fa fa-cog'),
						array('url'=>$this->createWebUrl('manage'),'title'=>'所有帖子','icon'=>'fa fa-cog'),
						
				),
				'head'=>' 版块管理',
				'icon'=>'fa fa-plane'
		),
		array(
				'url'=>array(
						array('url'=>$this->createWebUrl('task'),'title'=>'任务管理','icon'=>'fa fa-cog'),
						array('url'=>$this->createWebUrl('task',array('op'=>'one')),'title'=>'一键导入','icon'=>'fa fa-cog'),
						array('url'=>$this->createWebUrl('task',array('op'=>'add')),'title'=>'手工添加任务','icon'=>'fa fa-plus-square-o'),
				),
				'head'=>' 任务大厅',
				'icon'=>'fa fa-plane'
		),
		
		
		
		array(
				'url'=>array(
						array('url'=>$this->createWebUrl('set'),'title'=>'系统设置','icon'=>'fa fa-cog'),
						array('url'=>url('site/multi/post', array('multiid' => $item['id'])),'title'=>'移动端版权设置','icon'=>'fa fa-cog'),
						array('url'=>$this->createWebUrl('help'),'title'=>'帮助手册','icon'=>'fa fa-cog','issys'=>true),
				),
				'head'=>' 系统设置',
				'icon'=>'fa fa-plane'
		),
		
		array(
				'url'=>array(
						array('url'=>$this->createWebUrl('qiniu'),'title'=>'七牛云存储','icon'=>'fa fa-cog'),
				),
				'head'=>' 七牛云存储',
				'icon'=>'fa fa-plane'
		),
		
		

);


include $this->template('credit_cat');