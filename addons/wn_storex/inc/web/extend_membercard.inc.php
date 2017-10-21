<?php

defined('IN_IA') or exit('Access Denied');

global $_W, $_GPC;
$ops = array('card_setting', 'member_list', 'signmanage', 'noticemanage', 'cardmanage');
$op = in_array($_GPC['op'], $ops) ? trim($_GPC['op']) : 'card_setting';

if ($op == 'signmanage') {
	$url = $this->createWebUrl('signmanage', array('op' => 'sign_set'));
	header("Location: {$url}");
	die;
}

if ($op == 'noticemanage') {
	$url = $this->createWebUrl('noticemanage', array('op' => 'notice_list'));
	header("Location: {$url}");
	die;
}

if ($op == 'card_setting') {
	$url = $this->createWebUrl('membercard', array('op' => 'display'));
	header("Location: {$url}");
	die;
}

if ($op == 'cardmanage') {
	$url = $this->createWebUrl('cardmanage', array('op' => 'display'));
	header("Location: {$url}");
	die;
}