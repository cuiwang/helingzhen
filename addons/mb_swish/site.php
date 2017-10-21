<?php
 defined('IN_IA')or exit('Access Denied');
define('MB_ROOT', IA_ROOT . '/addons/mb_swish');
require MB_ROOT . '/source/util.php';
class mb_swishModuleSite extends WeModuleSite{
    public function doWebEntry(){
        global $_W;
        $url = $_W['siteroot'] . 'app/' . substr($this -> createMobileUrl('get'), 2);
        include $this -> template('entry');
    }
    public function doWebRecords(){
        global $_W, $_GPC;
        check_license();
        $r = new Record();
        $total = 0;
        $psize = 15;
        $pindex = intval($_GPC['page']);
        $pindex = max($pindex, 1);
        $filters = array();
        $ds = $r -> getAll($filters, $pindex, $psize, $total);
        $pager = pagination($total, $pindex, $psize);
        define('RESOURCE_URL', '../addons/mb_swish');
        include $this -> template('records');
    }
    public function doWebStorage(){
        global $_W, $_GPC;
        check_license();
        if(checksubmit()){
            $input = coll_elements(array('key', 'secret', 'bucket', 'host', 'resource'), $_GPC);
            $input = coll_walk($input, 'trim');
            $setting = $this -> module['config'];
            $setting['storage'] = $input;
            $this -> saveSettings($setting);
            message('保存参数成功', referer());
        }
        $storage = $this -> module['config']['storage'];
        include $this -> template("storage");
    }
}
