<?php
defined('IN_IA') or exit('Access Denied');
session_start();
checkagentlogin();
$dos = array('url', 'site', 'functions');
$do = in_array($do, $dos) ? $do : 'url';

if ($do == 'url') {
	template('agent/agent_site');
}

if ($do == 'functions') {
	template('agent/agent_site');
}
if ($do == 'site') {
	template('agent/agent_site');
}
