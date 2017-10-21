<?php

defined('IN_IA') or exit('Access Denied');

global $_W, $_GPC;
$ops = array('couponmanage', 'couponconsume', 'couponexchange', 'couponmarket', 'wxcardreply');
$op = in_array($_GPC['op'], $ops) ? trim($_GPC['op']) : 'couponmanage';

if ($op == 'couponmanage') {
	$url = $this->createWebUrl('couponmanage', array('op' => 'display'));
	header("Location: {$url}");
	die;
}

if ($op == 'couponconsume') {
	$url = $this->createWebUrl('noticemanage', array('op' => 'display'));
	header("Location: {$url}");
	die;
}

if ($op == 'couponexchange') {
	$url = $this->createWebUrl('couponexchange', array('op' => 'display'));
	header("Location: {$url}");
	die;
}

if ($op == 'couponmarket') {
	$url = $this->createWebUrl('couponmarket', array('op' => 'display'));
	header("Location: {$url}");
	die;
}

if ($op == 'wxcardreply') {
	$url = $this->createWebUrl('wxcardreply', array('op' => 'display'));
	header("Location: {$url}");
	die;
}