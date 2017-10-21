<?php
defined('IN_IA') or exit('Access Denied');
define('MODULE_ROOT', IA_ROOT . '/addons/czt_wechat_visitor/');
define('RESOURCE_URL', '../addons/czt_wechat_visitor/static/');
define('CSS_URL', RESOURCE_URL . 'css/');
define('JS_URL', RESOURCE_URL . 'js/');
define('IMAGES_URL', RESOURCE_URL . 'images/');
load()->model('mc');
require MODULE_ROOT . 'global.php';
class Czt_wechat_visitorModuleSite extends WeModuleSite
{
    public function doWebRecords()
    {
        global $_W, $_GPC;
        $pindex = max(1, intval($_GPC['page']));
        $psize  = 30;
        $fans   = pdo_fetchall("SELECT * FROM " . tablename('czt_wechat_visitor_fans') . " WHERE  uniacid =:uniacid ORDER BY  fanid DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(
            ':uniacid' => $_W['uniacid']
        ));
        $total  = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('czt_wechat_visitor_fans') . ' WHERE  uniacid =:uniacid', array(
            ':uniacid' => $_W['uniacid']
        ));
        $pager  = pagination($total, $pindex, $psize);
        include $this->template('records');
    }
}
function get_fan_info()
{
    global $_W;
    $fan = mc_oauth_userinfo();
    if (empty($fan)) {
        message('必须在微信浏览器打开, 或者必须设置oAuth参数');
    }
    $sql              = 'SELECT * FROM ' . tablename('czt_wechat_visitor_fans') . ' WHERE `uniacid`=:uniacid and `openid`=:openid';
    $pars             = array();
    $pars[':openid']  = $fan['openid'];
    $pars[':uniacid'] = $_W['uniacid'];
    $ret              = pdo_fetch($sql, $pars);
    if (empty($ret)) {
        $data                = array();
        $data['uniacid']     = $_W['uniacid'];
        $data['create_time'] = TIMESTAMP;
        $data['openid']      = $fan['openid'];
        $data['nickname']    = $fan['nickname'];
        $data['headimgurl']  = $fan['headimgurl'];
        $data['sex']         = $fan['sex'];
        $data['city']        = $fan['city'];
        $data['country']     = $fan['country'];
        $data['province']    = $fan['province'];
        $ret                 = pdo_insert('czt_wechat_visitor_fans', $data);
        if (!empty($ret)) {
            $fan['fanid'] = pdo_insertid();
        } else {
            exit('pdo_insert error!');
        }
    } else {
        $fan['fanid'] = $ret['fanid'];
    }
    return $fan;
}