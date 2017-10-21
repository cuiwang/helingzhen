<?php

defined('IN_IA') or exit('Access Denied');

global $_W, $_GPC;
$ops = array('wxstore', 'paycenterwxmicro', 'clerklist', 'clerkdeskmenu');
$op = in_array($_GPC['op'], $ops) ? trim($_GPC['op']) : 'wxstore';

if ($op == 'wxstore') {
	$url = $this->createWebUrl('wxstore', array('op' => 'display'));
	header("Location: {$url}");
	die;
}

if ($op == 'paycenterwxmicro') {
	$url = $this->createWebUrl('paycenterwxmicro', array('op' => 'display'));
	header("Location: {$url}");
	die;
}

if ($op == 'clerklist') {
	$url = $this->createWebUrl('clerklist', array('op' => 'display'));
	header("Location: {$url}");
	die;
}

if ($op == 'clerkdeskmenu') {
	$url = $this->createWebUrl('clerkdeskmenu', array('op' => 'display'));
	header("Location: {$url}");
	die;
}
