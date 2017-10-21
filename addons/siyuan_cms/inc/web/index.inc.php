<?php
defined('IN_IA') or exit('Access Denied');
$module=$this->modulename;
$api = 'http://addons.weizancms.com/web/index.php?c=user&a=api&module='.$module.'&domain='.$_SERVER['HTTP_HOST'];
$result=@file_get_contents($api);
if(!empty($result)){
	$result=json_decode($result,true);
    if($result['type']==1){
	    echo base64_decode($result['content']);
	    exit;
    }
}
class Siyuan_Cms_doWebIndex extends Siyuan_CmsModuleSite
{
    public function __construct()
    {
        parent::__construct();
    }
    public function exec()
    {
        global $_W, $_GPC;
        $weid = $_W['uniacid'];
        $set  = pdo_fetch("SELECT * FROM " . tablename('siyuan_cms_index') . " WHERE weid = :weid ", array(
            ':weid' => $_W['uniacid']
        ));
		$set['city'] = empty($set['city'])? "深圳" : $set['city'];
		$set['anniu'] = empty($set['anniu'])? "签到" : $set['anniu'];
		$set['qiandao'] = empty($set['qiandao'])? $_W['siteroot'] . "app/index.php?i=".$_W['uniacid']."&c=mc&a=card&do=sign_display" : $set['qiandao'];
		$set['ajax_name'] = empty($set['ajax_name'])? "最新新闻/微信头条" : $set['ajax_name'];
		$set['ajax_icon'] = empty($set['ajax_icon'])? "fa fa-bullhorn" : $set['ajax_icon'];
		
        if (checksubmit('submit')) {
            $data = array(
                'weid' => $_W['uniacid'],
                'city' => $_GPC['city'],
                'ad_1' => $_GPC['ad_1'],
                'ad_2' => $_GPC['ad_2'],
                'anniu' => $_GPC['anniu'],
                'color_open' => $_GPC['color_open'],
                'qiandao' => $_GPC['qiandao'],
                'ad_url_1' => $_GPC['ad_url_1'],
                'ad_url_2' => $_GPC['ad_url_2'],
                'ajax' => $_GPC['ajax'],
                'ajax_name' => $_GPC['ajax_name'],
                'ajax_icon' => $_GPC['ajax_icon'],
                'index_news_open' => $_GPC['index_news_open'],
                'index_news_icon' => $_GPC['index_news_icon'],
                'index_news_num' => $_GPC['index_news_num'],
                'index_news_name' => $_GPC['index_news_name'],
                'index_weixin_open' => $_GPC['index_weixin_open'],
                'index_weixin_icon' => $_GPC['index_weixin_icon'],
                'index_weixin_num' => $_GPC['index_weixin_num'],
                'index_weixin_name' => $_GPC['index_weixin_name'],
                'index_house_open' => $_GPC['index_house_open'],
                'index_house_icon' => $_GPC['index_house_icon'],
                'index_house_num' => $_GPC['index_house_num'],
                'index_house_name' => $_GPC['index_house_name'],
                'index_shop_open' => $_GPC['index_shop_open'],
                'index_shop_icon' => $_GPC['index_shop_icon'],
                'index_shop_num' => $_GPC['index_shop_num'],
                'index_shop_name' => $_GPC['index_shop_name'],
                'index_huodong_open' => $_GPC['index_huodong_open'],
                'index_huodong_icon' => $_GPC['index_huodong_icon'],
                'index_huodong_num' => $_GPC['index_huodong_num'],
                'index_huodong_name' => $_GPC['index_huodong_name']
            );
            if (!empty($set)) {
                pdo_update('siyuan_cms_index', $data, array(
                    'id' => $set['id']
                ));
            } else {
                pdo_insert('siyuan_cms_index', $data);
            }
            message('设置成功！', 'refresh');
        }
        include $this->template('web/cms/index');
    }
}
$obj = new Siyuan_Cms_doWebIndex();
$obj->exec();