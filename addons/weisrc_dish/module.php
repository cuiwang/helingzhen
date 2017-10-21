<?php
/**
 * 微点餐
 *
 * 作者:微赞科技
 *
 */
defined('IN_IA') or exit('Access Denied');

class weisrc_dishModule extends WeModule
{
    public $name = 'weisrc_dishModule';

    public function fieldsFormDisplay($rid = 0)
    {
        global $_W;
    }
    public function fieldsFormSubmit($rid = 0)
    {
        global $_GPC, $_W;
    }
    public function welcomeDisplay()
    {
        $url = $this->createWebUrl('stores2', array('op' => 'display'));
        Header("Location: " . $url);
    }
}