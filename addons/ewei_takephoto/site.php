<?php

/**
 * 拍大白
 * @author ewei qq:22185157
 */
defined('IN_IA') or exit('Access Denied');

class Ewei_takephotoModuleSite extends WeModuleSite {
  
    public function doWebManage() {
        global $_GPC, $_W;

        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $sql = "uniacid = :uniacid AND `module` = :module";
        $params = array();
        $params[':uniacid'] = $_W['uniacid'];
        $params[':module'] = 'ewei_takephoto';

        if (isset($_GPC['keywords'])) {
            $sql .= ' AND `name` LIKE :keywords';
            $params[':keywords'] = "%{$_GPC['keywords']}%";
        }
        load()->model('reply');
        $list = array();
        $list = reply_search($sql, $params, $pindex, $psize, $total);
        $pager = pagination($total, $pindex, $psize);

        if (!empty($list)) {
            foreach ($list as &$item) {
                $condition = "`rid`={$item['id']}";
                $item['keywords'] = reply_keywords_search($condition);
                $reply = pdo_fetch("SELECT rid,title, viewnum,starttime,endtime,status FROM " . tablename('ewei_takephoto_reply') . " WHERE rid = :rid ", array(':rid' => $item['id']));
                $item['rid'] = $reply['rid'];
                $item['title'] = $reply['title'];
                $item['viewnum'] = $reply['viewnum'];
                $item['starttime'] = date('Y-m-d H:i', $reply['starttime']);
                $endtime = $reply['endtime'];
                $item['endtime'] = date('Y-m-d H:i', $endtime);
                $nowtime = time();
                if ($reply['starttime'] > $nowtime) {
                    $item['statusstr'] = "<span class=\"label label-warning\">未开始</span>";
                } elseif ($endtime < $nowtime) {
                    $item['statusstr'] = "<span class=\"label label-default\">已结束</span>";
                } else {
                    if ($reply['status'] == 1) {
                        $item['statusstr'] = "<span class=\"label label-success\">已开始</span>";
                    } else {
                        $item['statusstr'] = "<span class=\"label \">已暂停</span>";
                    }
                }
                $item['status'] = $reply['status'];
            }
            unset($item);
        }
        include $this->template('manage');
    }
    public function doWebTpl() {
        global $_GPC, $_W;
        load()->func('tpl');
		
        include $this->template($_GPC['t']);
    }
    public function doWebSysset() {
        global $_W, $_GPC;
        $set = pdo_fetch("select * from ".tablename('ewei_takephoto_sysset')." where uniacid=:uniacid limit  1",array(':uniacid'=>$_W['uniacid']));
        if (checksubmit('submit')) {

            $data = array(
                'uniacid' => $_W['uniacid'],
                'oauth2' => intval($_GPC['oauth2']),
                'appid' => $_GPC['appid'],
                'appsecret' => $_GPC['appsecret']
            );
            if (!empty($set)) {
                pdo_update('ewei_share_sysset', $data, array('id' => $set['id']));
            } else {
                pdo_insert('ewei_share_sysset', $data);
            }
            message('更新授权接口成功！', 'refresh');
        }
        include $this->template('sysset');
    }
    public function doWebDelete() {
   
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $rule = pdo_fetch("SELECT id, module FROM " . tablename('rule') . " WHERE id = :id and uniacid=:uniacid", array(':id' => $rid, ':uniacid' => $_W['uniacid']));
        if (empty($rule)) {
            message('抱歉，要修改的规则不存在或是已经被删除！');
        }
        if (pdo_delete('rule', array('id' => $rid))) {
            pdo_delete('rule_keyword', array('rid' => $rid));
            //删除统计相关数据
            pdo_delete('stat_rule', array('rid' => $rid));
            pdo_delete('stat_keyword', array('rid' => $rid));
            //调用模块中的删除
            $module = WeUtility::createModule($rule['module']);
            if (method_exists($module, 'ruleDeleted')) {
                $module->ruleDeleted($rid);
            }
        }


        message('规则操作成功！', $this->createWebUrl('manage',array('name'=>'ewei_takephoto')), 'success');
    
    }
    public function doWebStatus() {
   
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
       $status = intval($_GPC['status']);
        pdo_update("ewei_takephoto_reply",array("status"=>$status),array("rid"=>$rid));
        message('操作成功！', $this->createWebUrl('manage',array('name'=>'ewei_takephoto')), 'success');
    }
     
    
    public function webmessage($error, $url = '', $errno = -1) {
        $data = array();
        $data['errno'] = $errno;
        if (!empty($url)) {
            $data['url'] = $url;
        }
        $data['error'] = $error;
        echo json_encode($data);
        exit;
    }
 
    
    public function doMobileIndex(){
        global $_W,$_GPC;
        $rid = intval($_GPC['rid']);
        $reply = pdo_fetch("select * from ".tablename('ewei_takephoto_reply')." where rid=:rid limit 1",array(':rid'=>$rid));
        if(empty($reply)){
            message('活动未找到!');
        }
        if ($reply['status'] == 0) {
            message('活动暂停 ,请稍后再来哦!');
        }
        if ($reply['starttime'] > time()) {
            message('活动还未开始，还不能参加哦!');
        }
        if ($reply['endtime'] < time()) {
            message('活动已经结束，不能参加啦，请等待下次活动哦!');
        }
        $reply['share_desc'] = str_replace("[SCORE]", "' + score + '", $reply['share_desc']);
        $reply['share_title'] = str_replace("[SCORE]", "' + score + '", $reply['share_title']);
        $items = iunserializer($reply['items']);
        //访问量
        pdo_update("ewei_takephoto_reply",array("viewnum"=>$reply['viewnum']+1),array("rid"=>$rid));
        include $this->template('index');
    }
}
