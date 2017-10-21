<?php
/**
 */
defined('IN_IA') or exit('Access Denied');

load()->model('app');
app_update_today_visit($_GPC['m']);