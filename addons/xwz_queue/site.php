<?php

/**
 * 微信排号
 * 
 * @author 小丸子  3066560445
 */
defined('IN_IA') or exit('Access Denied');

class Xwz_queueModuleSite extends WeModuleSite {

    public function get_openid() {

        global $_W;
        return $_W['openid'];
    }

    public function doWebTypeTpl() {
        global $_GPC, $_W;
        load()->func('tpl');
        include $this->template('type_item');
    }

    public function doWebManage() {
        global $_GPC, $_W;

        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $sql = "uniacid = :uniacid AND `module` = :module";
        $params = array();
        $params[':uniacid'] = $_W['uniacid'];
        $params[':module'] = 'xwz_queue';

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
                $reply = pdo_fetch("SELECT rid,title,status FROM " . tablename('xwz_queue_reply') . " WHERE rid = :rid ", array(':rid' => $item['id']));
                $item['rid'] = $reply['rid'];
                $item['title'] = $reply['title'];
                $types = pdo_fetchall('SELECT * FROM ' . tablename('xwz_queue_type') . ' WHERE rid = :rid', array(':rid' => $reply['rid']));


                foreach ($types as &$type) {
                    $type['num'] = pdo_fetchcolumn('select count(*) from ' . tablename('xwz_queue_data') . ' where rid=:rid and typeid=:typeid and status=0', array(':rid' => $reply['rid'], ':typeid' => $type['id']));
                }
                unset($type);
                $item['types'] = $types;
                if ($reply['status'] == 1) {
                    $item['statusstr'] = "<span class=\"label label-success\">已开始</span>";
                } else {
                    $item['statusstr'] = "<span class=\"label label-default\">已暂停</span>";
                }
                $item['status'] = $reply['status'];
            }
            unset($item);
        }
        include $this->template('manage');
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
        message('规则操作成功！', $this->createWebUrl('manage'), 'success');
    }

    public function doWebStatus() {

        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $status = intval($_GPC['status']);
        pdo_update("xwz_queue_reply", array("status" => $status), array("rid" => $rid));
        message('操作成功！', $this->createWebUrl('manage'), 'success');
    }
/****************/
    public function doWebUsed() {
        global $_W, $_GPC;
        $rid = intval($_GPC['rid']);
        $id = intval($_GPC['id']);
        $typeid = intval($_GPC['typeid']);
        $reply = pdo_fetch('select * from ' . tablename('xwz_queue_reply') . ' where uniacid=:uniacid and rid=:rid limit 1', array(':uniacid' => $_W['uniacid'], ':rid' => $rid));
        if (empty($reply)) {
            message('未找到排号系统!', '', 'error');
        }
        $data = pdo_fetch('select * from ' . tablename('xwz_queue_data') . ' where uniacid=:uniacid and rid=:rid and id=:id limit 1', array(':uniacid' => $_W['uniacid'], ':rid' => $rid, ':id' => $id));
        if (empty($data)) {
            message('未找到排号记录!', '', 'error');
        }
        $fans = pdo_fetch('select * from ' . tablename('xwz_queue_fans') . ' where uniacid=:uniacid and rid=:rid and openid=:openid limit 1', array(':uniacid' => $_W['uniacid'], ':rid' => $rid, ':openid' => $data['openid']));
        if (empty($fans)) {
            message('未找到用户记录!', '', 'error');
        }

        pdo_update('xwz_queue_data', array('status' => 1), array('id' => $id, 'uniacid' => $_W['uniacid'], 'rid' => $rid));
        //成功次数
        pdo_update('xwz_queue_fans', array('suc' => $fans['suc'] + 1), array('id' => $id, 'uniacid' => $_W['uniacid'], 'id' => $fans['id']));

        //发送通知
        $this->sendNotice($rid, $typeid);

        message('操作成功!', $this->createWebUrl('queue', array('rid' => $rid, 'typeid' => $typeid)), 'success');
    }

    public function doWebFaild() {
        global $_W, $_GPC;
        $rid = intval($_GPC['rid']);
        $id = intval($_GPC['id']);
        $typeid = intval($_GPC['typeid']);
        $reply = pdo_fetch('select * from ' . tablename('xwz_queue_reply') . ' where uniacid=:uniacid and rid=:rid limit 1', array(':uniacid' => $_W['uniacid'], ':rid' => $rid));
        if (empty($reply)) {
            message('未找到排号系统!', '', 'error');
        }
        $data = pdo_fetch('select * from ' . tablename('xwz_queue_data') . ' where uniacid=:uniacid and rid=:rid and id=:id limit 1', array(':uniacid' => $_W['uniacid'], ':rid' => $rid, ':id' => $id));
        if (empty($data)) {
            message('未找到排号记录!', '', 'error');
        }
        $fans = pdo_fetch('select * from ' . tablename('xwz_queue_fans') . ' where uniacid=:uniacid and rid=:rid and openid=:openid limit 1', array(':uniacid' => $_W['uniacid'], ':rid' => $rid, ':openid' => $data['openid']));
        if (empty($fans)) {
            message('未找到用户记录!', '', 'error');
        }

        pdo_update('xwz_queue_data', array('status' => -1), array('id' => $id, 'uniacid' => $_W['uniacid'], 'rid' => $rid));
        //发送通知
        $this->sendNotice($rid, $typeid);

        message('操作成功!', $this->createWebUrl('queue', array('rid' => $rid, 'typeid' => $typeid)), 'success');
    }

    public function doWebClearQueue() {
        global $_W, $_GPC;
        $rid = intval($_GPC['rid']);
        pdo_delete('xwz_queue_data', array('rid' => $rid));
        message('操作成功!', $this->createWebUrl('queue', array('rid' => $rid)), 'success');
    }

    public function doWebManager() {
        global $_GPC, $_W;
        load()->func('tpl');
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        $rid = intval($_GPC['rid']);
        if ($operation == 'display') {
            $manager = pdo_fetchall('select * from '.tablename('xwz_queue_manager')." where uniacid=:uniacid and rid=:rid order by id desc",array(':uniacid'=>$_W['uniacid'],':rid'=>$rid));
       
        } elseif ($operation == 'post') {
            $id = intval($_GPC['id']);
            if (checksubmit('submit')) {
                if (empty($_GPC['username'])) {
                    message('抱歉，请输入管理员名称！');
                }
                $data = array(
                    'uniacid' => $_W['uniacid'],
                    'rid' => $_GPC['rid'],
                    'username' => $_GPC['username'],
                    'pwd' => $_GPC['pwd'],
                    'status' => intval($_GPC['status'])
                );
                if (!empty($id)) {
                    pdo_update('xwz_queue_manager', $data, array('id' => $id));
                } else {
                    pdo_insert('xwz_queue_manager', $data);
                }
                message('保存管理员成功！', $this->createWebUrl('manager', array('op' => 'display','rid'=>$rid)), 'success');
            }
            $manager =  pdo_fetch('select * from '.tablename('xwz_queue_manager')." where uniacid=:uniacid and id=:id and rid=:rid limit 1",array(':uniacid'=>$_W['uniacid'],':id'=>$id,':rid'=>$rid));
           
        } elseif ($operation == 'delete') {
            $id = intval($_GPC['id']);
            $manager = pdo_fetch("SELECT id FROM " . tablename('xwz_queue_manager') . " WHERE id = '$id' and uniacid={$_W['uniacid']}");
            if (empty($manager)) {
                message('抱歉，管理员不存在或是已经被删除！', $this->createWebUrl('manager', array('op' => 'display','rid'=>$rid)), 'error');
            }
            pdo_delete('xwz_queue_manager', array('id' => $id, 'parentid' => $id), 'OR');
            message('管理员删除成功！', $this->createWebUrl('manager', array('op' => 'display','rid'=>$rid)), 'success');
        }
         include $this->template('manager');
    }

    public function doMobileIndex() {
        global $_W, $_GPC;
        $rid = intval($_GPC['rid']);
        $reply = pdo_fetch('select * from ' . tablename('xwz_queue_reply') . ' where uniacid=:uniacid and rid=:rid limit 1', array(':uniacid' => $_W['uniacid'], ':rid' => $rid));

        $openid = $_W['openid'];
        $fans = pdo_fetch('select follow from ' . tablename('mc_mapping_fans') . ' where openid=:openid limit 1', array(':openid' => $openid));
        $isfollow = true;
        if (!empty($openid)) {
            $isfollow = !empty($fans['follow']);
        }
        if (empty($openid) || !$isfollow) {
            header('location: ' . $reply['followurl']);
            exit;
        }
        include $this->template('index');
    }

    public function doMobileNumber() {

        global $_W, $_GPC;
        $rid = intval($_GPC['rid']);

        $openid = $this->get_openid();

        if (empty($rid)) {
            exit(json_encode(array('status' => true, 'ret' => 7001)));
        }

        $reply = pdo_fetch('select * from ' . tablename('xwz_queue_reply') . ' where uniacid=:uniacid and rid=:rid limit 1', array(':uniacid' => $_W['uniacid'], ':rid' => $rid));

        $ret = array(
            'status' => true,
            'ret' => 7101,
            'shop_name' => $reply['heading'],
            'tel' => $reply['tel'],
            'config' => array(
                'num' => $reply['num'],
                'intro' => $reply['intro']
            )
        );

        if (empty($reply)) {
            $ret['ret'] = 7002;
            exit(json_encode($ret));
        }
        if (empty($reply['status'])) {
            $ret['ret'] = 7003;
            exit(json_encode($ret));
        }

        $fans = pdo_fetch('select * from ' . tablename('xwz_queue_fans') . ' where uniacid=:uniacid and  rid=:rid and openid=:openid limit 1', array(':uniacid' => $_W['uniacid'], ':rid' => $rid, ':openid' => $openid));
        if (!empty($fans) && $fans['status'] == 1) {
            $ret['ret'] = 403;
            exit(json_encode($ret));
        }

        $typedata = pdo_fetch('select * from ' . tablename('xwz_queue_data') . ' where uniacid=:uniacid and  rid=:rid and openid=:openid and status=0 order by id desc limit 1', array(':uniacid' => $_W['uniacid'], ':rid' => $rid, ':openid' => $openid));

        $ret['msg'] = '您还没有领取号牌!';
        if (!empty($typedata)) {

            $type = pdo_fetch('select * from ' . tablename('xwz_queue_type') . ' where uniacid=:uniacid and  rid=:rid and id=:id limit 1', array(':uniacid' => $_W['uniacid'], ':rid' => $rid, ':id' => $typedata['typeid']));

            if (empty($type)) {
                //如果没有类型且用户排在上面则，取消排位
                pdo_delete('xwz_queue_data', array('uniacid' => $_W['uniacid'], 'rid' => $rid, 'typeid' => $type['id']));
            } else {
                unset($ret['msg']);

                //前面的人 
                $before_count = pdo_fetchcolumn('select count(*) from ' . tablename('xwz_queue_data') . ' where uniacid=:uniacid and rid=:rid and typeid=:typeid and status=0 and id<' . $typedata['id'], array(':uniacid' => $_W['uniacid'], ':rid' => $reply['rid'], ':typeid' => $typedata['typeid']));

                //最后入座的人
                $last = pdo_fetch('select id from ' . tablename('xwz_queue_data') . ' where uniacid=:uniacid and rid=:rid and typeid=:typeid and status=1 order by id desc limit 1', array(':uniacid' => $_W['uniacid'], ':rid' => $reply['rid'], ':typeid' => $typedata['typeid']));
                $after_count = 0;
                if (!empty($last)) {
                    //过号几个
                    $after_count = pdo_fetchcolumn('select count(*) from ' . tablename('xwz_queue_data') . ' where uniacid=:uniacid and rid=:rid and typeid=:typeid and status<>-1 and id>' . $typedata['id'] . ' and id<=' . $last['id'], array(':uniacid' => $_W['uniacid'], ':rid' => $reply['rid'], ':typeid' => $typedata['typeid']));
                }

                $ret['ret'] = 0;
                $ret['config']['intro'] =$reply['intro'];
                $ret['data'] = array(
                    'typeid' => $type['id'],
                    'type' => $type['tag'],
                    'type_name' => $type['title'],
                    'number' => $typedata['number'],
                    'dt_add' => date('Y-m-d H:i', $fans['createtime']),
                    'waiting_time' => $this->DateDiff(time(), $typedata['createtime']),
                    'before_count' => $before_count,
                    'after_count' => $after_count,
                    'status' => $after_count > 0 ? 2 : 0
                );

                //判断过号次数
                if (!empty($fans)) {
                    if ($after_count > $reply['num'] && $reply['num'] > 0) {
                        //号码失效
                        pdo_update('xwz_queue_data', array('status' => -1), array('rid' => $rid, 'uniacid' => $_W['uniacid'], 'openid' => $openid));
                        //过号次数
                        pdo_update('xwz_queue_fans', array('past' => $fans['past'] + 1), array('rid' => $rid, 'uniacid' => $_W['uniacid'], 'id' => $fans['id']));
                    }
                }
            }
        }

        exit(json_encode($ret));
        // case 7105:
        //  t = "抱歉，您号码已失效，请重新扫码！";
        //    break
        //  }
    }

    function DateDiff($time1, $time2) { //时间比较函数，返回两个日期相差几秒、几分钟、几小时或几天 
        $dividend = 60;

        if ($time1 && $time2)
            return round((float) ($time1 - $time2) / $dividend);
        return false;
    }

    public function doMobileType() {

        global $_W, $_GPC;

        $rid = intval($_GPC['rid']);
        if (empty($rid)) {
            exit(json_encode(array('status' => true, 'ret' => 7001)));
        }
        $reply = pdo_fetch('select * from ' . tablename('xwz_queue_reply') . ' where uniacid=:uniacid and rid=:rid limit 1', array(':uniacid' => $_W['uniacid'], ':rid' => $rid));
        if (empty($reply)) {
            exit(json_encode(array('status' => true, 'ret' => 7002)));
        }
        if (empty($reply['status'])) {
            exit(json_encode(array('status' => true, 'ret' => 7003)));
        }

        $types = pdo_fetchall('select * from ' . tablename('xwz_queue_type') . ' where uniacid=:uniacid and  rid=:rid order by id asc', array(':uniacid' => $_W['uniacid'], ':rid' => $rid));
        $ret = array(
            'status' => true,
            'ret' => 0,
            'data' => array()
        );

        foreach ($types as $t) {
            $current = pdo_fetch('select id,number from ' . tablename('xwz_queue_data') . ' where uniacid=:uniacid and rid=:rid and typeid=:typeid and status=0 order by id asc ', array(':uniacid' => $_W['uniacid'], ':rid' => $reply['rid'], ':typeid' => $t['id']));
            $ret['data'][] = array(
                'type' => $t['tag'],
                'type_name' => $t['title'],
                'count' => pdo_fetchcolumn('select count(*) from ' . tablename('xwz_queue_data') . ' where uniacid=:uniacid and rid=:rid and typeid=:typeid and status=0 ', array(':uniacid' => $_W['uniacid'], ':rid' => $reply['rid'], ':typeid' => $t['id'])),
                'typeid' => $t['id'],
                'id' => intval($current['id']),
                'current' => intval($current['number'])
            );
        }
        exit(json_encode($ret));
    }
/*手机端排队*/
    public function doMobileLineup() {

        global $_W, $_GPC;

        $rid = intval($_GPC['rid']);
        $openid = $this->get_openid();
        if (empty($rid)) {
            exit(json_encode(array('status' => true, 'ret' => 7003)));
        }
        $reply = pdo_fetch('select * from ' . tablename('xwz_queue_reply') . ' where uniacid=:uniacid and rid=:rid limit 1', array(':uniacid' => $_W['uniacid'], ':rid' => $rid));
        if (empty($reply)) {
            exit(json_encode(array('status' => true, 'ret' => 7003)));
        }
        if (empty($reply['status'])) {
            exit(json_encode(array('status' => true, 'ret' => 7003)));
        }
        $typeid = intval($_GPC['type']);
        $type = pdo_fetch('select * from ' . tablename('xwz_queue_type') . ' where uniacid=:uniacid and  rid=:rid and id=:id limit 1', array(':uniacid' => $_W['uniacid'], ':rid' => $rid, ':id' => $typeid));
        $fans = pdo_fetch('select * from ' . tablename('xwz_queue_fans') . ' where uniacid=:uniacid and  rid=:rid and openid=:openid limit 1', array(':uniacid' => $_W['uniacid'], ':rid' => $rid, ':openid' => $openid));
        if (empty($fans)) {
            //获取用户信息
            $nickname = $openid;
            $headimgurl = "";
            load()->classs('weixin.account');
            $accObj = WeAccount::create($_W['acid']);
            $access_token = $accObj->fetch_token();
            if (!empty($access_token)) {
                load()->func('communication');
                $res = ihttp_request('https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $access_token . '&openid=' . $openid . '&lang=zh_CN');
                $userinfo = @json_decode($res['content'], true);
                if (is_array($userinfo) && !empty($userinfo['nickname'])) {
                    $nickname = $userinfo['nickname'];
                    $headimgurl = $userinfo['headimgurl'];
                }
            }

            $fans = array(
                'uniacid' => $_W['uniacid'],
                'rid' => $rid,
                'openid' => $openid,
                'nickname' => $nickname,
                'headimgurl' => $headimgurl,
                'createtime' => time(),
                'status' => 0,
            );
            pdo_insert('xwz_queue_fans', $fans);
            $fans['id'] = pdo_insertid();
        }
        $time = time();



        //前面的人
        $number = pdo_fetchcolumn('select count(*) from ' . tablename('xwz_queue_data') . ' where uniacid=:uniacid and  rid=:rid and typeid=:typeid ', array(':uniacid' => $_W['uniacid'], ':rid' => $reply['rid'], ':typeid' => $typeid));
        //前面等待的人
        $number_wait = pdo_fetchcolumn('select count(*) from ' . tablename('xwz_queue_data') . ' where uniacid=:uniacid and  rid=:rid and typeid=:typeid and status=0 ', array(':uniacid' => $_W['uniacid'], ':rid' => $reply['rid'], ':typeid' => $typeid));


        //取消以前等待
        pdo_update('xwz_queue_data', array('status' => -1), array('uniacid' => $_W['uniacid'], 'rid' => $reply['rid'], 'openid' => $openid));

        //增加现有等待
        $data = array(
            'uniacid' => $_W['uniacid'],
            'rid' => $rid,
            'number' => $number + 1,
            'openid' => $openid,
            'typeid' => $typeid,
            'createtime' => $time,
            'status' => 0,
        );
        pdo_insert('xwz_queue_data', $data);
        $data['id'] = pdo_insertid();

        $ret = array(
            'status' => true,
            'ret' => 0,
            'data' => array(
                'typeid' => $type['id'],
                'type' => $type['tag'],
                'type_name' => $type['title'],
                'number' => $number + 1,
                'dt_add' => date('Y-m-d H:i', $time),
                'waiting_time' => 0,
                'before_count' => $number_wait,
                'status' => 0,
            ),
            'config' => array(
                'num' => $reply['num'],
                'intro' => $reply['intro']
            )
        );
 
        //发送通知
        $this->sendMessage($data);

        exit(json_encode($ret));
    }

    public function sendMessage($data, $notice = false) {

        global $_W;
        $reply = pdo_fetch('select * from ' . tablename('xwz_queue_reply') . ' where uniacid=:uniacid and rid=:rid limit 1', array(':uniacid' => $_W['uniacid'], ':rid' => $data['rid']));
        $type = pdo_fetch('select * from ' . tablename('xwz_queue_type') . ' where uniacid=:uniacid and  rid=:rid and id=:id limit 1', array(':uniacid' => $_W['uniacid'], ':rid' => $data['rid'], ':id' => $data['typeid']));
        $number = pdo_fetchcolumn('select count(*) from ' . tablename('xwz_queue_data') . ' where uniacid=:uniacid and  rid=:rid and typeid=:typeid and openid<>:openid  and status=0 and id<:id  ', array(':uniacid' => $_W['uniacid'], ':rid' => $reply['rid'], ':typeid' => $data['typeid'], ':openid' => $data['openid'], ':id' => $data['id']));
	$acid = pdo_fetch('select * from ' . tablename('account') . ' where uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));
	$_W['acid'] = $acid['acid'];
        //发送通知
        $sendtype = 0; // 0不发送 1 模板消息 2 客服消息
        //如果是认证服务号模板消息，如果认证号订阅号，客服消息
        load()->model('account');
        $account = account_fetch($_W['acid']);
        if ($account['level'] == 4) {
            //认证服务号
            $template_id = $reply['templateid'];
            if (!empty($template_id)) {
                $sendtype = 1;
            } else {
                $sendtype = 2;
            }
        } else if ($account['level'] == 3) {
            //认证订阅号
            $sendtype = 2;
        }
        $url = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&m=xwz_queue&do=index&rid=' . $data['rid'];
        if ($sendtype == 1) {
        	$openid = $data['openid'];
			/*{{first.DATA}}
			{{first.DATA}}
			商家名称：{{keyword1.DATA}}
			排队号码：{{keyword2.DATA}}
			前面等待：{{keyword3.DATA}}
			预计等待：{{keyword4.DATA}}
			排队状态：{{keyword5.DATA}}
			{{remark.DATA}}*/
			if($number>0){
				$state = '排队中...';
			}elseif($number==0){
				$state = '等待就坐（到您了）';
			}
            $data = json_encode(array(
                'keyword1' => array('value' => $reply['heading'].'（'.$type['title'].'）'),
                'keyword2' => array('value' => $type['tag'] . $data['number']),
                'keyword3' => array('value' => $number . '位'),
                'keyword4' => array('value' => ' - -'),
                'keyword5' => array('value' =>$state),
	       'remark' => array('value' => '感谢使用微信排号，欢迎您的到来！')
            ));
            load()->classs('weixin.account');
            $accObj = WeAccount::create($_W['acid']);
            $access_token = $accObj->fetch_token();
            if (!empty($access_token)) {
                load()->func('communication');
                $postarr = '{"touser":"' . $openid . '","template_id":"' . $template_id . '","url":"' . $url . '","data":' . $data . '}';
                $res = ihttp_post('https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $access_token, $postarr);
            }
        } else if ($sendtype == 2) {

            $content = $notice ? "排号进度通知" : "取号成功通知\n\n";
            $content.="号码：" . $type['tag'] . $data['number'] . "\n";
            $content.="类型：" . $type['title'] . "\n";
            $content.="前面还有：" . $number . "位\n";
            $content.="商家：" . $reply['heading'] . "\n";
            $content.="取号时间：" . date('Y-m-d H:i', $data['createtime']) . "\n\n";
            $content.="<a href='{$url}'>点击查看详情</a>";
            $data = array(
                "touser" => $data['openid'],
                "msgtype" => "text",
                "text" => array('content' => urlencode($content))
            );
            load()->classs('weixin.account');
            $accObj = WeAccount::create($_W['acid']);
            $access_token = $accObj->fetch_token();
            if (!empty($access_token)) {
                $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
                load()->func('communication');
                $res = ihttp_request($url, urldecode(json_encode($data)));
            }
        }
    }

    public function doMobileGiveup() {

        global $_W, $_GPC;
        $rid = intval($_GPC['rid']);
        $typeid = intval($_GPC['type']);
        $openid = $this->get_openid();

        //取消以前等待
        pdo_update('xwz_queue_data', array('giveuptime' => time(), 'status' => -1), array('uniacid' => $_W['uniacid'], 'rid' => $rid, 'openid' => $openid, 'typeid' => $typeid));

        $fans = pdo_fetch('select id,cancel from ' . tablename('xwz_queue_fans') . ' where uniacid=:uniacid and rid=:rid and openid=:openid limit 1', array(':uniacid' => $_W['uniacid'], ':rid' => $rid, ':openid' => $openid));
        if (!empty($fans)) {
            //取消次数
            pdo_update('xwz_queue_fans', array('cancel' => $fans['cancel'] + 1), array('rid' => $rid, 'uniacid' => $_W['uniacid'], 'id' => $fans['id']));
        }

        //发送通知
        $this->sendNotice($rid, $typeid);

        exit(json_encode(array('status' => true, 'ret' => 0)));
    }

    //发送等待通知
    public function sendNotice($rid, $typeid) {
        global $_W;
        $reply = pdo_fetch('select * from ' . tablename('xwz_queue_reply') . ' where uniacid=:uniacid and rid=:rid limit 1', array(':uniacid' => $_W['uniacid'], ':rid' => $rid));
        $type = pdo_fetch('select * from ' . tablename('xwz_queue_type') . ' where uniacid=:uniacid and  rid=:rid and id=:id limit 1', array(':uniacid' => $_W['uniacid'], ':rid' => $rid, ':id' => $typeid));
        $beforenum = intval($reply['beforenum']);
        if ($beforenum > 0) {
            //找到接近 beforenum 位的等待用户
            $datas = pdo_fetchall("select  * from " . tablename('xwz_queue_data') . " where uniacid=:uniacid and  rid=:rid and typeid=:typeid  and status=0 order by id asc limit {$beforenum} ", array(':uniacid' => $_W['uniacid'], ':rid' => $reply['rid'], ':typeid' => $typeid));
            foreach ($datas as $d) {
                $this->sendMessage($d);
            }
        }
    }

    public function doMobileLogin() {

        global $_W, $_GPC;
        $rid = intval($_GPC['rid']);

        if ($_W['ispost']) {

            //先判断管理员
            $manager = pdo_fetch('select * from '.tablename('xwz_queue_manager').' '
                    . ' where uniacid=:uniacid and rid=:rid and username=:username and pwd=:pwd limit  1'
                    ,array(':uniacid'=>$_W['uniacid'],':rid'=>$rid,':username'=>$_GPC['username'],':pwd'=>$_GPC['password']));
           if(!empty($manager)){
                isetcookie('__xwz_session_' . $_W['uniacid'] . '_' . $rid, $manager['id'], 0);
                exit(json_encode(array('status' => true)));
           }
           
           //再判断系统管理员
            $member = array(
                'username' => $_GPC['username'],
                'password' => $_GPC['password']
            );
            load()->model('user');
            $record = user_single($member);
            if (!empty($record)) {
                if ($record['status'] == 1) {
                    exit(json_encode(array('status' => false)));
                }
                isetcookie('__xwz_session_' . $_W['uniacid'] . '_' . $rid, $record['uid'], 0);
                exit(json_encode(array('status' => true)));
            }
            exit(json_encode(array('status' => false)));
        }

        include $this->template('login');
    }

    public function doMobileLoginout() {

        global $_W, $_GPC;
        $rid = intval($_GPC['rid']);
        isetcookie('__xwz_session_' . $_W['uniacid'] . '_' . $rid, null);
        header("location: " . $_W['siteroot'] . 'app/' . substr($this->createMobileUrl('login', array('rid' => $rid)), 2));
    }

    public function check_manage() {

        global $_W, $_GPC;
        $rid = intval($_GPC['rid']);
        if (empty($_GPC['__xwz_session_' . $_W['uniacid'] . '_' . $rid])) {
            header("location: " . $_W['siteroot'] . 'app/' . substr($this->createMobileUrl('login', array('rid' => $rid)), 2));
            exit;
        }
    }

    public function doMobileManage() {
        global $_W, $_GPC;
        $rid = intval($_GPC['rid']);
        $this->check_manage();
        include $this->template('manage');
    }

    public function doMobileSetting() {
        global $_W, $_GPC;
        $rid = intval($_GPC['rid']);
        $reply = pdo_fetch('select * from ' . tablename('xwz_queue_reply') . ' where uniacid=:uniacid and rid=:rid limit 1', array(':uniacid' => $_W['uniacid'], ':rid' => $rid));
        $ret = array(
            'status' => true,
            'ret' => 0
        );
        if (empty($reply)) {
            $ret['ret'] = 7002;
            exit(json_encode($ret));
        }
        $ret['data'] = array(
            'id' => $rid,
            'wid' => $_W['uniacid'],
            'status' => $reply['status']
        );
        exit(json_encode($ret));
    }

    public function doMobileSwitch() {
        global $_W, $_GPC;
        $rid = intval($_GPC['rid']);
        $reply = pdo_fetch('select * from ' . tablename('xwz_queue_reply') . ' where uniacid=:uniacid and rid=:rid limit 1', array(':uniacid' => $_W['uniacid'], ':rid' => $rid));
        if (empty($reply)) {
            exit(json_encode(array(
                'status' => false,
                'msg' => '未找到排号系统',
            )));
        }

        pdo_update('xwz_queue_reply', array('status' => empty($reply['status']) ? 1 : 0), array('rid' => $rid));
        exit(json_encode(array(
            'status' => true
        )));
    }

    public function doMobileReset() {
        global $_W, $_GPC;
        $rid = intval($_GPC['rid']);
        $reply = pdo_fetch('select * from ' . tablename('xwz_queue_reply') . ' where uniacid=:uniacid and rid=:rid limit 1', array(':uniacid' => $_W['uniacid'], ':rid' => $rid));
        if (empty($reply)) {
            exit(json_encode(array(
                'status' => false,
                'msg' => '未找到排号系统',
            )));
        }
        pdo_update('xwz_queue_data', array('status' => -1), array('rid' => $rid, 'uniacid' => $_W['uniacid']));
        exit(json_encode(array(
            'status' => true
        )));
    }

    public function doMobileUsed() {
        global $_W, $_GPC;
        $rid = intval($_GPC['rid']);
        $id = intval($_GPC['id']);
        $reply = pdo_fetch('select * from ' . tablename('xwz_queue_reply') . ' where uniacid=:uniacid and rid=:rid limit 1', array(':uniacid' => $_W['uniacid'], ':rid' => $rid));
        if (empty($reply)) {
            exit(json_encode(array(
                'status' => false,
                'msg' => '未找到排号系统',
            )));
        }
        $data = pdo_fetch('select * from ' . tablename('xwz_queue_data') . ' where uniacid=:uniacid and rid=:rid and id=:id limit 1', array(':uniacid' => $_W['uniacid'], ':rid' => $rid, ':id' => $id));
        if (empty($data)) {
            exit(json_encode(array(
                'status' => false,
                'msg' => '未找到排号记录',
            )));
        }
        $fans = pdo_fetch('select * from ' . tablename('xwz_queue_fans') . ' where uniacid=:uniacid and rid=:rid and openid=:openid limit 1', array(':uniacid' => $_W['uniacid'], ':rid' => $rid, ':openid' => $data['openid']));
        if (empty($fans)) {
            exit(json_encode(array(
                'status' => false,
                'msg' => '未找到用户记录',
            )));
        }

        pdo_update('xwz_queue_data', array('status' => 1), array('id' => $id, 'uniacid' => $_W['uniacid'], 'rid' => $rid));
        //成功次数
        pdo_update('xwz_queue_fans', array('suc' => $fans['suc'] + 1), array('id' => $id, 'uniacid' => $_W['uniacid'], 'id' => $fans['id']));

        //发送通知
        $this->sendNotice($rid, $data['typeid']);

        exit(json_encode(array(
            'status' => true
        )));
    }

    public function doMobileFaild() {
        global $_W, $_GPC;
        $rid = intval($_GPC['rid']);
        $id = intval($_GPC['id']);
        $reply = pdo_fetch('select * from ' . tablename('xwz_queue_reply') . ' where uniacid=:uniacid and rid=:rid limit 1', array(':uniacid' => $_W['uniacid'], ':rid' => $rid));
        if (empty($reply)) {
            exit(json_encode(array(
                'status' => false,
                'msg' => '未找到排号系统',
            )));
        }
        $data = pdo_fetch('select * from ' . tablename('xwz_queue_data') . ' where uniacid=:uniacid and rid=:rid and id=:id limit 1', array(':uniacid' => $_W['uniacid'], ':rid' => $rid, ':id' => $id));
        if (empty($data)) {
            exit(json_encode(array(
                'status' => false,
                'msg' => '未找到排号记录',
            )));
        }

        pdo_update('xwz_queue_data', array('status' => -1), array('id' => $id, 'uniacid' => $_W['uniacid'], 'rid' => $rid));

        //发送通知
        $this->sendNotice($rid, $data['typeid']);

        exit(json_encode(array(
            'status' => true
        )));
    }

    public function doMobileLineups() {
        global $_W, $_GPC;
        $rid = intval($_GPC['rid']);
        $typeid = intval($_GPC['type']);
        $type = pdo_fetch('select * from ' . tablename('xwz_queue_type') . ' where uniacid=:uniacid and  rid=:rid and id=:id limit 1', array(':uniacid' => $_W['uniacid'], ':rid' => $rid, ':id' => $typeid));

        $datas = pdo_fetchall('select id,number,createtime from ' . tablename('xwz_queue_data') . ' where uniacid=:uniacid and rid=:rid and  typeid=:typeid  and status=0 order by id asc limit 10', array(':uniacid' => $_W['uniacid'], ':rid' => $rid, ':typeid' => $typeid));
        foreach ($datas as &$d) {
            $d['is_waiting'] = $this->DateDiff(time(), $d['createtime']);
            $d['type'] = $type['tag'];
        }
        unset($d);

        $ret = array(
            '_count' => count($datas),
            'type' => $type['tag'],
            'type_name' => $type['title'],
            'list' => $datas,
            'member_count' => pdo_fetchcolumn('select count(*) from ' . tablename('xwz_queue_data') . ' where uniacid=:uniacid and rid=:rid and typeid=:typeid and status=0 ', array(':uniacid' => $_W['uniacid'], ':rid' => $rid, ':typeid' => $typeid))
        );
        exit(json_encode($ret));
    }

    public function doWebQueue() {

        global $_W, $_GPC;
        $rid = intval($_GPC['rid']);
        $typeid = intval($_GPC['typeid']);
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $types = pdo_fetchall('select * from ' . tablename('xwz_queue_type') . ' where uniacid=:uniacid and  rid=:rid order by id asc', array(':uniacid' => $_W['uniacid'], ':rid' => $rid));
        foreach ($types as &$t) {
            $t['num'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('xwz_queue_data') . " where uniacid=:uniacid and rid=:rid and typeid=:typeid and status=0 ", array(':uniacid' => $_W['uniacid'], ':rid' => $rid, ':typeid' => $t['id']));
        }
        unset($t);

        $condition = ' and d.status=0 ';
        if (empty($typeid)) {
            $typeid = $types[0]['id'];
        }
        $condition .= " AND d.typeid = '" . $typeid . "'";

        $list = pdo_fetchall("SELECT d.id,d.rid,t.tag,d.typeid,d.status,d.number,d.openid,f.nickname,f.headimgurl FROM " . tablename('xwz_queue_data')
                . " d left join " . tablename('xwz_queue_type') . " t on d.typeid = t.id "
                . " left join " . tablename('xwz_queue_fans') . " f on d.openid = f.openid "
                . " WHERE d.uniacid = '{$_W['uniacid']}'  $condition ORDER BY id asc LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('xwz_queue_data') . " d left join " . tablename('xwz_queue_type') . " t on d.typeid = t.id where d.uniacid = '{$_W['uniacid']}' $condition");
        $pager = pagination($total, $pindex, $psize);

        include $this->template('queue');
    }

    public function doWebFans() {

        global $_W, $_GPC;
        $rid = intval($_GPC['rid']);

        $condition = '';
        if (!empty($_GPC['keyword'])) {
            $condition .= " AND nickname LIKE '%{$_GPC['keyword']}%'";
        }

        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $list = pdo_fetchall("SELECT * FROM " . tablename('xwz_queue_fans') . " WHERE uniacid ={$_W['uniacid']} and rid={$rid} $condition ORDER BY id desc LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('xwz_queue_fans') . " where uniacid ={$_W['uniacid']} and rid={$rid} $condition ");
        $pager = pagination($total, $pindex, $psize);
        include $this->template('fans');
    }

    public function doWebBlacklist() {

        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $id = intval($_GPC['id']);
        $status = intval($_GPC['status']);
        pdo_update("xwz_queue_fans", array("status" => $status), array("rid" => $rid, "id" => $id));
        message('操作成功！', $this->createWebUrl('fans', array('rid' => $rid)), 'success');
    }

    public function doWebScreen() {

        global $_W, $_GPC;
        $rid = intval($_GPC['rid']);
        $wid = intval($_GPC['wid']);
        $reply = pdo_fetch('select * from ' . tablename('xwz_queue_reply') . ' where uniacid=:uniacid and rid=:rid limit 1', array(':uniacid' => $wid, ':rid' => $rid));
        load()->model('reply');
        $keywords = reply_keywords_search('rid=' . $rid);
        $keyword = $keywords[0]['content'];
        include $this->template('screen');
    }

    public function doWebRefreshScreen() {
        global $_W, $_GPC;
        $rid = intval($_GPC['rid']);
        $wid = intval($_GPC['wid']);

        $types = pdo_fetchall('select * from ' . tablename('xwz_queue_type') . ' where uniacid=:uniacid and  rid=:rid order by id asc', array(':uniacid' => $wid, ':rid' => $rid));
        $ret = array(
            'list' => array()
        );

        foreach ($types as $t) {
            $current = pdo_fetchcolumn('select number from ' . tablename('xwz_queue_data') . ' where uniacid=:uniacid and rid=:rid and typeid=:typeid and status=0 order by id asc limit 1  ', array(':uniacid' => $wid, ':rid' => $rid, ':typeid' => $t['id']));
            if (intval($current) == 0) {
                $before = pdo_fetchcolumn('select number from ' . tablename('xwz_queue_data') . ' where uniacid=:uniacid and rid=:rid and typeid=:typeid and status=1 order by id asc limit 1   ', array(':uniacid' => $wid, ':rid' => $rid, ':typeid' => $t['id']));
            } else {
                $before = 0;
            }
            //$before = pdo_fetchcolumn('select number from ' . tablename('xwz_queue_data') . ' where uniacid=:uniacid and rid=:rid and typeid=:typeid and status=0 ', array(':uniacid' => $wid, ':rid' => $rid, ':typeid' => $t['id']));
            $ret['list'][] = array(
                'type' => $t['tag'],
                'type_name' => $t['title'],
                'before' => intval($before),
                'current' => intval($current)
            );
        }

        exit(json_encode($ret));
    }

}
