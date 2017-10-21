<?php
defined('IN_IA') or exit('Access Denied');
class Yg_supergrowthModuleSite extends WeModuleSite
{
    public function doMobileEnter()
    {
        include $this->template('index');
    }
}