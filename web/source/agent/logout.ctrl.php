<?php
/**
 * [WECHAT 2017]
 
 */
defined('IN_IA') or exit('Access Denied');
session_start();
session_destroy();

header('Location:' . url('account/welcome'));
