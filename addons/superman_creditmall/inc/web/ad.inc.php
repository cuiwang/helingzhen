<?php
/**
 * 悟空源 码论 坛bbs . xte c.cc定义
 *
 * @author 微赞
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
class Creditmall_doWebAd extends Superman_creditmallModuleSite {
    public function __construct() {
        parent::__construct(true);
    }

    public function exec() {
        global $_W, $_GPC;
        $title = '广告管理';
        $act = in_array($_GPC['act'], array('display', 'post', 'delete', 'setattr', 'content'))?$_GPC['act']:'display';
        $ad_positions = superman_ad_position();
        if ($act == 'display') {
            if ($_GPC['_method'] == 'switch') {
                $id = $_GPC['id'];
                $value = $_GPC['value'];
                $field = $_GPC['field'];
                $data = array(
                    $field => $value,
                );
                $condition = array(
                    'id' => $id,
                );
                superman_ad_update($data, $condition);
                echo 'success';
                exit;
            }
            if(checksubmit('submit')) {
                $displayorder = $_GPC['displayorder'];
                if ($displayorder) {
                    foreach ($displayorder as $id=>$val) {
                        $data = array(
                            'displayorder' => $val,
                        );
                        $condition = array(
                            'id' => $id,
                        );
                        superman_ad_update($data, $condition);
                    }
                    message('更新成功', referer(), 'success');
                }
            }
            $pindex = max(1, intval($_GPC['page']));
            $pagesize = 20;
            $start = ($pindex - 1) * $pagesize;
            $filter = array();
            $filter['uniacid'] = $_W['uniacid'];
            if ($_GPC['position_id'] > 0) {
                $filter['position_id'] = intval($_GPC['position_id']);
            }
            $total = superman_ad_count($filter);
            if ($total) {
                $list = superman_ad_fetchall($filter, $start, $pagesize);
                if ($list) {
                    foreach($list as &$p){
                        superman_ad_set($p);
                    }
                    unset($p);
                }
                $pager = pagination($total, $pindex, $pagesize);
            }
            //print_r($list);
        } else if ($act == 'post') {
            $id = intval($_GPC['id']);
            if ($id) {
                $item = superman_ad_fetch($id);
                if ($item) {
                    superman_ad_set($item);
                }
            } else {
                $item = array(
                    'ad_time' => array(
                        'start' => date('Y-m-d H:i'),
                        'end' => date('Y-m-d H:i', strtotime('+1 month')),
                    ),
                    'isshow' => 1,
                );
            }
            if (checksubmit('submit')) {
                $start_time = strtotime($_GPC['ad_time']['start']);
                $end_time = strtotime($_GPC['ad_time']['end']);
                $content = array();
                if (isset($_GPC['content']['title']) && is_array($_GPC['content']['title'])) {
                    foreach ($_GPC['content']['title'] as $k=>$v) {
                        $content[$k] = array(
                            'title' => $_GPC['content']['title'][$k],
                            'thumb' => $_GPC['content']['thumb'][$k],
                            'url' => $_GPC['content']['url'][$k],
                        );
                    }
                    ksort($content);
                }
                $data = array(
                    'title' => trim($_GPC['title']),
                    'start_time' => !isset($_GPC['ad_time_limit'])?$start_time:0,
                    'end_time' => !isset($_GPC['ad_time_limit'])?$end_time:0,
                    'isshow' => $_GPC['isshow']?1:0,
                    'displayorder' => intval($_GPC['displayorder']),
                    'content' => $content?iserializer($content):array(),
                );

                if ($id) {
                    superman_ad_position_delete_adid($id);
                    $condition = array(
                        'id' => $id,
                    );
                    superman_ad_update($data, $condition);
                } else {
                    $data['uniacid'] = $_W['uniacid'];
                    $data['dateline'] = TIMESTAMP;
                    $id = superman_ad_insert($data);
                }
                if ($_GPC['position_ids'] && is_array($_GPC['position_ids'])) {
                    foreach ($_GPC['position_ids'] as $posid) {
                        $data = array(
                            'ad_id' => $id,
                            'position_id' => $posid,
                        );
                        superman_ad_position_insert($data);
                    }
                }
                message('操作成功！', $this->createWebUrl('ad'), 'success');
            }
        } else if ($act == 'delete') {
            $id = intval($_GPC['id']);
            $item = superman_ad_fetch($id);
            if (empty($item)) {
                message('广告不存在或已删除！', referer(), 'error');
            }
            superman_ad_delete($id);
            message('删除成功！', referer(), 'success');
        } else if ($act == 'content') {
            include $this->template('web/ad-content');
            die;
        }
        include $this->template('web/ad');
    }
}

$obj = new Creditmall_doWebAd;
$obj->exec();
