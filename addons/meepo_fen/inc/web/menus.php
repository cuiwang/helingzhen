<?php
$menus = array();
$menus[] = array(
	'title'=>'基本设置',
	'url'=>$this->createwebUrl('set'),
	'do'=>'set'
);
$menus[] = array(
	'title'=>'首次关注',
	'url'=>$this->createwebUrl('new'),
	'do'=>'new'
);
$menus[] = array(
	'title'=>'非首次关注',
	'url'=>$this->createwebUrl('old'),
	'do'=>'old'
);