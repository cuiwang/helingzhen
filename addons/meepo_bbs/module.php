<?php
/**
 * 微论坛模块定义
 *
 * @author meepo
 */
defined('IN_IA') or exit('Access Denied');

class Meepo_bbsModule extends WeModule {

	public function __construct()
	{
		$do = $_GPC['do'];
		$doo = $_GPC['doo'];
		$act = $_GPC['act'];
		global $frames;
		$frames = getModuleFrames('meepo_bbs');
		_calc_current_frames2($frames);
	}

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		$setting = $this->module['config'];
		
		$path = IA_ROOT . '/addons/meepo_bbs/template/mobile/';
		if (is_dir($path)) {
			$apis = array();
			if ($handle = opendir($path)) {
				while (false !== ($file = readdir($handle))) {
					if ($file != "." && $file != "..") {
						$stylesResults[] = $file;
					}
				}
			}
		}
		foreach ($stylesResults as $item){
			if(file_exists($path.$item.'/preview.png')){
				$stylesResult[] = $item;
			}
		}
		
		if(!empty($_GPC['name'])){
			$dat = array();
			$dat['name'] = $_GPC['name'];
			$this->saveSettings($dat);
			message('模板设置成功',referer(),'success');
		}
		
		//这里来展示设置项表单
		include $this->template('settings');
	}
}


function getModuleFrames($name){
	global $_W;
	$sql = "SELECT * FROM ".tablename('modules')." WHERE name = :name limit 1";
	$params = array(':name'=>$name);
	$module = pdo_fetch($sql,$params);

	$sql = "SELECT * FROM ".tablename('modules_bindings')." WHERE module = :name ";
	$params = array(':name'=>$name);
	$module_bindings = pdo_fetchall($sql,$params);

	$frames = array();

	$frames['index']['title'] = '首页';
	$frames['index']['active'] = 'active';
	$frames['index']['items'] = array();
	$frames['index']['items']['index']['url'] = url('site/entry/index/',array('doo'=>'index','op'=>'index','m'=>$name));
	$frames['index']['items']['index']['title'] = '管理首页';
	$frames['index']['items']['index']['actions'] = array();
	$frames['index']['items']['index']['active'] = '';
	$frames['index']['items']['threadclass']['url'] = url('site/entry/threadclass/',array('m'=>$name));
	$frames['index']['items']['threadclass']['title'] = '版块管理';
	$frames['index']['items']['threadclass']['actions'] = array();
	$frames['index']['items']['threadclass']['active'] = '';
	$frames['index']['items']['threadclass']['append']['title'] = '最新帖子';
	$frames['index']['items']['threadclass']['append']['url'] = url('site/entry/manage/',array('m'=>$name));
	$frames['index']['items']['custommenu']['url'] = url('site/entry/fast/',array('m'=>$name));
	$frames['index']['items']['custommenu']['title'] = '常用操作';
	$frames['index']['items']['custommenu']['active'] = '';

	$frames['index']['items']['rss']['url'] = url('site/entry/index/',array('doo'=>'index','op'=>'rss','m'=>$name));
	$frames['index']['items']['rss']['title'] = 'RSS抓取';
	$frames['index']['items']['rss']['active'] = '';

	$frames['operate']['title'] = '运营';
	$frames['operate']['items'] = array();
	$frames['operate']['items']['adv']['url'] = url('site/entry/adv/',array('m'=>$name));
	$frames['operate']['items']['adv']['title'] = '站点广告';
	$frames['operate']['items']['adv']['actions'] = array();
	$frames['operate']['items']['adv']['active'] = '';
	$frames['operate']['items']['tasks']['url'] = url('site/entry/task/',array('m'=>$name));
	$frames['operate']['items']['tasks']['title'] = '任务大厅';
	$frames['operate']['items']['tasks']['actions'] = array();
	$frames['operate']['items']['tasks']['active'] = '';
	$frames['operate']['items']['tasks']['append']['title'] = '一键导入';
	$frames['operate']['items']['tasks']['append']['url'] = url('site/entry/',array('do'=>'task','op'=>'one','m'=>$name));

	$frames['operate']['items']['credit']['url'] = url('site/entry/credit/',array('m'=>$name));
	$frames['operate']['items']['credit']['title'] = '积分兑换';
	$frames['operate']['items']['credit']['actions'] = array();
	$frames['operate']['items']['credit']['active'] = '';

	$frames['operate']['items']['rand']['url'] = url('site/entry/rand/',array('m'=>$name));
	$frames['operate']['items']['rand']['title'] = '批刷浏览量';
	$frames['operate']['items']['rand']['actions'] = array();
	$frames['operate']['items']['rand']['active'] = '';

	$frames['operate']['items']['ec']['url'] = url('site/entry/index/',array('doo'=>'ec','op'=>'config','m'=>$name));
	$frames['operate']['items']['ec']['title'] = '电子商务';
	$frames['operate']['items']['ec']['actions'] = array();
	$frames['operate']['items']['ec']['active'] = '';

	$frames['oto']['title'] = 'O2O管理';
	$frames['oto']['items'] = array();

	$frames['oto']['items']['oto_user']['url'] = url('site/entry/oto_user',array('m'=>$name));
	$frames['oto']['items']['oto_user']['title'] = 'o2o核销员管理';
	$frames['oto']['items']['oto_user']['active'] = '';

	$frames['oto']['items']['oto_user_log']['url'] = url('site/entry/oto_user_log',array('m'=>$name));
	$frames['oto']['items']['oto_user_log']['title'] = 'o2o核销记录';
	$frames['oto']['items']['oto_user_log']['active'] = '';

	$frames['begging']['title'] = '微打赏';
	$frames['begging']['items'] = array();

	$frames['begging']['items']['manage']['url'] = url('site/entry/index',array('doo'=>'begging','op'=>'manage','m'=>$name));
	$frames['begging']['items']['manage']['title'] = '打赏管理';
	$frames['begging']['items']['manage']['active'] = '';

	$frames['begging']['items']['list']['url'] = url('site/entry/index',array('doo'=>'begging','op'=>'list','m'=>$name));
	$frames['begging']['items']['list']['title'] = '打赏帖子';
	$frames['begging']['items']['list']['active'] = '';

	$frames['begging']['items']['post']['url'] = url('site/entry/index',array('doo'=>'begging','op'=>'post','m'=>$name));
	$frames['begging']['items']['post']['title'] = '添加打赏帖子';
	$frames['begging']['items']['post']['active'] = '';

	$frames['template']['title'] = '消息及群发';
	$frames['template']['items'] = array();

	$frames['template']['items']['template']['url'] = url('site/entry/index',array('doo'=>'template','op'=>'template','m'=>$name));
	$frames['template']['items']['template']['title'] = '模板库管理';
	$frames['template']['items']['template']['active'] = '';

	$frames['menu']['title'] = '基础设置';
	$frames['menu']['items'] = array();

	$frames['menu']['items']['set']['url'] = url('site/entry/',array('do'=>'set','m'=>$name));
	$frames['menu']['items']['set']['title'] = '系统设置';
	$frames['menu']['items']['set']['active'] = '';

	$frames['menu']['items']['qiniu']['url'] = url('site/entry/',array('do'=>'qiniu','m'=>$name));
	$frames['menu']['items']['qiniu']['title'] = '七牛云设置';
	$frames['menu']['items']['qiniu']['active'] = '';

	$frames['menu']['items']['nav']['url'] = url('site/entry/',array('do'=>'nav','m'=>$name));
	$frames['menu']['items']['nav']['title'] = '首页导航管理';
	$frames['menu']['items']['nav']['active'] = '';
	$frames['menu']['items']['nav']['append'] = array();
	$frames['menu']['items']['nav']['append']['title'] = '一键配置';
	$frames['menu']['items']['nav']['append']['url'] = url('site/entry/',array('do'=>'oneconfig','op'=>nav,'m'=>$name));
	return $frames;
}
//来自易 福 源 码 网
function _calc_current_frames2(&$frames) {
	global $_W,$_GPC,$frames;
	if(!empty($frames) && is_array($frames)) {
		foreach($frames as &$frame) {
			foreach($frame['items'] as &$fr) {
				$query = parse_url($fr['url'], PHP_URL_QUERY);
				parse_str($query, $urls);
				if(defined('ACTIVE_FRAME_URL')) {
					$query = parse_url(ACTIVE_FRAME_URL, PHP_URL_QUERY);
					parse_str($query, $get);
				} else {
					$get = $_GET;
				}
				if(!empty($_GPC['a'])) {
					$get['a'] = $_GPC['a'];
				}
				if(!empty($_GPC['c'])) {
					$get['c'] = $_GPC['c'];
				}
				if(!empty($_GPC['do'])) {
					$get['do'] = $_GPC['do'];
				}
				if(!empty($_GPC['doo'])) {
					$get['doo'] = $_GPC['doo'];
				}
				if(!empty($_GPC['op'])) {
					$get['op'] = $_GPC['op'];
				}
				if(!empty($_GPC['m'])) {
					$get['m'] = $_GPC['m'];
				}
				$diff = array_diff_assoc($urls, $get);

				if(empty($diff)) {
					$fr['active'] = ' active';
					$frame['active'] = ' active';
				}
			}
		}
	}
}
