<?php
defined('IN_IA') or exit('Access Denied');
class weisrc_quickadModule extends WeModule
{
    public $name = 'weisrc_quickadModule';
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
        $url = $this->createWebUrl('index', array(
            'op' => 'display'
        ));
        Header('Location: ' . $url);
    }
}