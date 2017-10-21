<?php

defined('IN_IA') or exit('Access Denied');

global $_W, $_GPC;
$ops = array('statcredit1', 'statcredit2', 'statcash', 'statcard', 'statpaycenter');
$op = in_array($_GPC['op'], $ops) ? trim($_GPC['op']) : 'statcredit1';

if ($op == 'statcredit1') {
	$url = $this->createWebUrl('statcredit1', array('op' => 'display'));
	header("Location: {$url}");
	die;
}

if ($op == 'statcredit2') {
	$url = $this->createWebUrl('statcredit2', array('op' => 'display'));
	header("Location: {$url}");
	die;
}

if ($op == 'statcash') {
	$url = $this->createWebUrl('statcash', array('op' => 'display'));
	header("Location: {$url}");
	die;
}

if ($op == 'statcard') {
	$url = $this->createWebUrl('statcard', array('op' => 'display'));
	header("Location: {$url}");
	die;
}

if ($op == 'statpaycenter') {
	$url = $this->createWebUrl('statpaycenter', array('op' => 'display'));
	header("Location: {$url}");
	die;
}