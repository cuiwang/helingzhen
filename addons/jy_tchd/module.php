<?php
/**
 * 同城互动模块定义
 *
 */
defined('IN_IA') or exit('Access Denied');

class jy_tchdModule extends WeModule {
    public function welcomeDisplay() {
        header('location: '.$this->createWebUrl('webindex'));
        exit;
    }
}