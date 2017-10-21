<?php
/**
 * 关注有礼模块微站定义
 *
 * @author meepo
 *  http://www.5kym.com 悟空源码网
 */
defined('IN_IA') or exit('Access Denied');

if(!function_exists('M')){
    function M($name){
        static $model = array();
        if(empty($model[$name])) {
            include IA_ROOT.'/addons/meepo_fen/model/'.$name.'.php';
            $model[$name] = new $name();
        }
        return $model[$name];
    }
}

class Meepo_fenModuleSite extends WeModuleSite {
	public $system_setting = array();
	public function __construct()
	{
		global $_W,$_GPC;
		if(!empty($_W['openid'])){
			M('member')->update($_W['openid']);
		}
	}

	public function doMobileindex(){
	    global $_W,$_GPC;
	    $_GPC['do'] = 'index';
	    
	    include $this->template('default/index');
	}
	public function doWebmember(){
	    global $_W,$_GPC;
	    $_GPC['do'] = 'member';
	    if ($_GPC['act'] == 'edit') {
	        $id = intval($_GPC['id']);
	        if($_W['ispost']){
	            $data = array();
	            $data['uniacid'] = $_W['uniacid'];
	            $data['create_time'] = time();
	            if(!empty($id)){
	                $data['id'] = $id;
	                unset($data['create_time']);
	            }
	            M('member')->update($data);
	            message('保存成功',$this->createWebUrl('member'),'success');
	        }
	        $item = M('member')->getInfo($id);
	        include $this->template('member_edit');
	        exit();
	    }
	    if ($_GPC['act'] == 'delete') {
	        $id = intval($_GPC['id']);
	        if(empty($id)){
	            if($_W['ispost']){
	                $data = array();
	                $data['status'] = 1;
	                $data['message'] = '参数错误';
	                die(json_encode($data));
	            }else{
	                message('参数错误',referer(),'error');
	            }
	        }
	        M('member')->delete($id);
	        if($_W['ispost']){
	            $data = array();
	            $data['status'] = 1;
	            $data['message'] = '操作成功';
	            die(json_encode($data));
	        }else{
	            message('删除成功',referer(),'success');
	        }
	    }
	    $page = !empty($_GPC['page'])?intval($_GPC['page']):1;
	    $where = "";
		$params = array();
		$list = M('member')->getList($page,$where,$params);
	    include $this->template('member');
	}
	public function doWebsetting(){
	    global $_W,$_GPC;
	    $_GPC['do'] = 'setting';
	    $code = $_GPC['code'];

	    if(empty($code)){
	        $code = 'system';
	    }
	    if(empty($code)){
	        message('参数错误',referer(),'error');
	    }
	    if($_W['ispost']){
	        $data = array();
	        $data['codename'] = $code;
	        $data['value'] = serialize($_POST);
	        M('setting')->update($data);
	        message('保存成功',referer(),'success');
	    }
	    $item = M('setting')->getSetting($code);
	    include $this->template($code.'_setting');
	}
}