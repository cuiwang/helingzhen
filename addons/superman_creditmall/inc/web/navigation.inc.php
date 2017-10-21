<?php
/**
 * 悟空源 码论 坛bbs . xte c.cc定义
 *
 * @author 微赞
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
class Creditmall_doWebNavigation extends Superman_creditmallModuleSite {
    public function __construct() {
        parent::__construct(true);
    }
    public function exec() {
        global $_W, $_GPC;
        $title = '导航管理';
        $act = in_array($_GPC['act'], array('display'))?$_GPC['act']:'display';
        if ($act == 'display') {
            if (isset($_GPC['setattr']) && $_GPC['setattr'] == 1) {
                $id = intval($_GPC['id']);
                $field = in_array($_GPC['field'], array('isshow'))?$_GPC['field']:'';
                if (!$id || !$field) {
                    exit('非法请求');
                }
                $value = $_GPC['value'];
                $data = array(
                    $field => $value,
                );
                M::t('superman_creditmall_navigation')->update($data, array('id' => $id));
                exit('success');
            }
            $filter = array(
                'uniacid' => $_W['uniacid']
            );
            $orderby = " ORDER BY displayorder DESC";
            $list = M::t('superman_creditmall_navigation')->fetchall($filter, $orderby, 0, -1);
            if (checksubmit()) {
                foreach ($_GPC['title'] as $key=>$val) {
                    $id = $_GPC['id'][$key];
                    $icon = trim($_GPC['icon'][$key]);
                    $title = trim($_GPC['title'][$key]);
                    $url = trim($_GPC['url'][$key]);
                    $displayorder = intval($_GPC['displayorder'][$key]);
                    $isshow = $_GPC['isshow'][$key]?1:0;
                    $data = array(
                        'uniacid' => $_W['uniacid'],
                        'icon' => $icon,
                        'title' => cutstr($title, 6),
                        'url' => $url,
                        'displayorder' => $displayorder,
                        'isshow' => $isshow,
                    );
                    if ($id) {
                        M::t('superman_creditmall_navigation')->update($data, array('id' => $id));
                    } else {
                        M::t('superman_creditmall_navigation')->insert($data);
                    }
                }
                message('操作成功', referer(), 'success');
            }
        }
        include $this->template('web/navigation');
    }
}
$obj = new Creditmall_doWebNavigation;
$obj->exec();
