<?php
defined('IN_IA') or exit('Access Denied');
session_start();
checkagentlogin();
$dos = array('main');
$do = in_array($do, $dos) ? $do : 'main';

if ($do == 'main') {
	template('agent/agent_main');
}
