<?php
/**
 * 一键导航模块微站定义
 *
 * @author yhctech
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Yhc_onenaviModuleSite extends WeModuleSite {


    public function doMobileRedirect() {
        $this->__mobile(__FUNCTION__);
    }

	public function doWebList() {
		//这个操作被定义用来呈现 管理中心导航菜单
        $this->__web(__FUNCTION__);
	}


    public function doWebForm() {
        //这个操作被定义用来呈现 管理中心导航菜单
        $this->__web(__FUNCTION__);
    }

    public function doWebDel() {
        //这个操作被定义用来呈现 管理中心导航菜单
        $this->__web(__FUNCTION__);
    }

    //后台管理程序 web文件夹下
    public function __web($f_name) {
        global $_W, $_GPC;
        checklogin();
        //每个页面都要用的公共信息，今后可以考虑是否要运用到缓存
        include_once 'web/' . strtolower(substr($f_name, 5)) . '.php';
    }

    public function __mobile($f_name){
        global $_W,$_GPC;
        $openid=$_W['fans']['from_user'] ;
        $weid=$_W['uniacid'];
        $setting= $this->get_sysset($weid);
       // $this->checkIsWeixin();

        include_once 'mobile/'.strtolower(substr($f_name,8)).'.php';
    }

}