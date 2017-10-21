<?php
/**
 * 微夜店
 *
 * 作者:悟空源码网
 *
 * qq : 63779278
 */
defined('IN_IA') or exit('Access Denied');
define('RES', '../addons/weisrc_nightclub/template/');
//{RES}images/nopic.jpeg
//{RES}images/default-headimg.jpg
class weisrc_nightclubModule extends WeModule {
	public $name = 'weisrc_nightclubModule';
	public $title = '微夜店';
	public $ability = '';
	public $tablename = 'weisrc_nightclub_reply';
    public $action = 'detail';//方法
    public $modulename = 'weisrc_nightclub';//模块标识

    public function fieldsFormDisplay($rid = 0) {
        global $_W;
    }

    public function fieldsFormSubmit($rid = 0) {
        global $_GPC, $_W;
    }
}