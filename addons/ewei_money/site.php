<?php

/**
 * 疯狂划算
 *
 * @author ewei 012wz.com
 * @url
 */
defined('IN_IA') or exit('Access Denied');
require 'jssdk.php';
class Ewei_moneyModuleSite extends WeModuleSite {

    public $tablename = 'ewei_money_reply';
    public $tablefans = 'ewei_money_fans';

    public function doWebdd() {
    	$rid = intval($_GPC['rid']);
        require_once 'dd.php';
    }

    public function doWebdda() {
    	$rid = intval($_GPC['rid']);
        require_once 'dda.php';
    }

    public function doWebSetshow() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $isshow = intval($_GPC['isshow']);

        if (empty($rid)) {
            message('抱歉，传递的参数错误！', '', 'error');
        }
        $temp = pdo_update('ewei_money_reply', array('isshow' => $isshow), array('rid' => $rid));
        message('状态设置成功！', $this->createWebUrl('manage'), 'success');
    }

    public function doWebSetstatus() {
        global $_GPC, $_W;
        $id = intval($_GPC['id']);
        $status = intval($_GPC['status']);
        if (empty($id)) {
            message('抱歉，传递的参数错误！', '', 'error');
        }
        $p = array('status' => $status);
        if ($status == 1) {
            $p['consumetime'] = TIMESTAMP;
        } else {
            $p['consumetime'] = 0;
        }
        $temp = pdo_update('ewei_money_award', $p, array('id' => $id));
        if ($temp == false) {
            message('请不要重复操作！', '', 'error');
        } else {
            message('状态设置成功！', $this->createWebUrl('awardlist', array('rid' => $_GPC['rid'])), 'success');
        }
    }

    public function doWebDelete() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $rule = pdo_fetch("SELECT id, module FROM " . tablename('rule') . " WHERE id = :id and uniacid=:weid", array(':id' => $rid, ':weid' => $_W['uniacid']));
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
            $module->ruleDeleted($rid);
        }
        message('规则操作成功！',$this->createWebUrl('manage'), 'success');
    }

    public function doWebDeleteAll() {
        global $_GPC, $_W;
        
        foreach ($_GPC['idArr'] as $k => $rid) {
            $rid = intval($rid);
            if ($rid == 0)
                continue;
            $rule = pdo_fetch("SELECT id, module FROM " . tablename('rule') . " WHERE id = :id and uniacid=:weid", array(':id' => $rid, ':weid' => $_W['uniacid']));
            if (empty($rule)) {
                $this->message('抱歉，要修改的规则不存在或是已经被删除！');
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
        }
        $this->message('规则操作成功！', '', 0);
    }

    public function doWebManage() {
        global $_GPC, $_W;
       
        load()->model('reply');
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $sql = "uniacid = :weid AND `module` = :module";
        $params = array();
        $params[':weid'] = $_W['uniacid'];
        $params[':module'] = 'ewei_money';

        if (isset($_GPC['keywords'])) {
            $sql .= ' AND `name` LIKE :keywords';
            $params[':keywords'] = "%{$_GPC['keywords']}%";
        }
        $list = reply_search($sql, $params, $pindex, $psize, $total);
        $pager = pagination($total, $pindex, $psize);
        if (!empty($list)) {
            foreach ($list as &$item) {
                $condition = "`rid`={$item['id']}";
                $item['keywords'] = reply_keywords_search($condition);
                $money = pdo_fetch("SELECT * FROM " . tablename('ewei_money_reply') . " WHERE rid = :rid ", array(':rid' => $item['id']));
                $item['fansnum'] = $money['play_times'];
                $item['viewnum'] = $money['view_times'];
                $item['starttime'] = date('Y-m-d H:i', $money['starttime']);
                $endtime = $money['endtime'] + 86399;
                $item['endtime'] = date('Y-m-d H:i', $endtime);
                $nowtime = time();
                if ($money['starttime'] > $nowtime) {
                    $item['status'] = '<span class="label label-default">未开始</span>';
                    $item['show'] = 1;
                } elseif ($endtime < $nowtime) {
                    $item['status'] = '<span class="label label-warning">已结束</span>';
                    $item['show'] = 0;
                } else {
                    if ($money['isshow'] == 1) {
                        $item['status'] = '<span class="label label-success">已开始</span>';
                        $item['show'] = 2;
                    } else {
                        $item['status'] = '<span class="label label-default">已暂停</span>';
                        $item['show'] = 1;
                    }
                }
                $item['isshow'] = $money['isshow'];
            }
        }
        include $this->template('manage');
    }
    
  public function doWebSysset() {
        global $_W, $_GPC;
        $set = pdo_fetch("select * from ".tablename('ewei_money_sysset')." where weid=:weid limit 1",array(":weid"=>$_W['uniacid']));
        if (checksubmit('submit')) {
            $data = array(
                'weid' => $_W['uniacid'],
                'appid' => $_GPC['appid'],
                'appsecret' => $_GPC['appsecret'],
                'appid_share' => $_GPC['appid_share'],
                'appsecret_share' => $_GPC['appsecret_share']
            );
            if (!empty($set)) {
                pdo_update('ewei_money_sysset', $data, array('id' => $set['id']));
            } else {
                pdo_insert('ewei_money_sysset', $data);
            }
            message('更新授权接口成功！', 'refresh');
        }

        include $this->template('sysset');
    }
    
      /**
     * 获取设置
     * @return boolean
     */
    public function get_sysset() {
        global $_W;
        return pdo_fetch("SELECT * FROM " . tablename('ewei_money_sysset') . " WHERE weid = :weid limit 1", array(':weid' =>$_W['uniacid']));
    }
  
    private function get_code($id, $appid) {

        global $_W;
        $url = $_W['siteroot'] . "app/index.php?i=" . $_W['uniacid'] . "&c=entry&m=ewei_money&do=index&id={$id}";
        $oauth2_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $appid . "&redirect_uri=" . urlencode($url) . "&response_type=code&scope=snsapi_base&state=123#wechat_redirect";
        header("location: $oauth2_url");
        exit();
    }

    public function get_openid($id, $code, $appid, $appsecret) {
        global $_GPC, $_W;
        load()->func('communication');
        $oauth2_code = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $appid . "&secret=" . $appsecret . "&code=" . $code . "&grant_type=authorization_code";
        $content = ihttp_get($oauth2_code);

        $token = @json_decode($content['content'], true);
        if (!empty($token) && is_array($token) && $token['errmsg'] == 'invalid code') {
            $this->get_code($id, $appid);
        }
        if (empty($token) || !is_array($token) || empty($token['access_token']) || empty($token['openid'])) {
            message('未获取到 openid , 请刷新重试!');
        }
        return $token['openid'];
    }
    
    public function doMobileIndex() {
        global $_GPC, $_W;
        $rid = intval($_GPC['id']);
        if (empty($rid)) {
            message('参数错误!');
        }
        $reply = pdo_fetch("SELECT * FROM " . tablename($this->tablename) . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
        if(empty($reply)){
            message('未找到游戏!');
        } 
        $openid =$_W['openid'];
        
        //是否关注
        $followed = !empty($openid);
        if ($followed) {
            $f = pdo_fetch("select follow from " . tablename('mc_mapping_fans') . " where openid=:openid limit 1", array(":openid" => $openid));
            $followed = !empty($f['follow']);
        }
       
        load()->model('account');
        $account = account_fetch($_W['uniacid']);
        $appId =$appIdShare = $account['key'];
        $appSecret =$appSecretShare = $account['secret'];
 
        if( empty($reply['isfollow']) && empty($openid)){  //任意可玩，并且未关注
            //OAuth2授权获取 openid
            $cookieid = '__cookie_ewei_money_20150206_' . $rid;
            if ($_W['account']['level'] != 4) {
                //不是认证服务号
                $set = $this->get_sysset();
                if (!empty($set['appid']) && !empty($set['appsecret'])) {
                    $appId = $set['appid'];
                    $appSecret = $set['appsecret'];
                }  else{
                    //如果没有借用，判断是否认证服务号
                    message('请使用认证服务号进行活动，或借用其他认证服务号权限!');
                 }
                 if (!empty($set['appid_share']) && !empty($set['appsecret_share'])) {
                    $appIdShare = $set['appid_share'];
                    $appSecretShare = $set['appsecret_share'];
                }
            }
           if (empty($appId) || empty($appSecret)) {
               message('请到管理后台设置完整的 AppID 和AppSecret !');
           }
 
            $cookie = json_decode(base64_decode($_COOKIE[$cookieid]));
            if (!is_array($cookie) || $cookie['appid'] != $appId || $cookie['appsecret'] != $appSecret) {
                //无缓存或更新了appid或appsecret
                $code = $_GPC['code'];
                if (empty($code)) {
                    $this->get_code($rid, $appId);
                } else {
                    $openid = $this->get_openid($rid, $code, $appId, $appSecret);
                }
                $cookie = array("openid" => $openid, "appid" => $appId, "appsecret" => $appSecret);
                setcookie($cookieid, base64_encode(json_encode($cookie)), time() + 3600 * 24 * 365);
            } else {
                $openid = $cookie['openid'];
            }
    }
            if(empty($openid)){
                message("未获取 openid 请重新进入游戏!");
            }
            
            $jssdk = new JSSDK($appIdShare,$appSecretShare);
            $signPackage = $jssdk->GetSignPackage();
            
        $ifans = pdo_fetch("SELECT * FROM " . tablename($this->tablefans) . " WHERE rid = :rid and from_user = :from_user ", array(':from_user' => $openid, ':rid' => $rid));
 
        $reply['daytimes'] = !empty($ifans)? $ifans['daytimes'] : $reply['daytimes'];
        $reply['alltimes'] = !empty($ifans)?$ifans['alltimes'] : $reply['alltimes'];
        pdo_query("update " . tablename($this->tablename) . " set view_times=view_times+1 where rid=" . $rid . "");
        include $this->template('index');
    }

    public function doMobileRule() {
        global $_GPC, $_W;
        $rid = intval($_GPC['id']);
        if (empty($rid)) {
            message('参数错误!');
        }
        $uid =intval($_GPC['uid']);
        $reply = pdo_fetch("SELECT * FROM " . tablename($this->tablename) . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
        $ifans = pdo_fetch("SELECT * FROM " . tablename($this->tablefans) . " WHERE rid = :rid and id = :id ", array(':id' => $uid, ':rid' => $rid));
        $data = array();
        if ($ifans != false) {
            if ($ifans['alltimes'] < $ifans['daytimes']) {
                $ifans['daytimes'] = $ifans['alltimes'];
            }
            $data = array(
                "success" => true,
                "usedAllTimes" => $ifans['alltimes'],
                "usedDayTimes" => $ifans['daytimes'],
                "allTimes" => $reply['alltimes'],
                "dayTimes" => $reply['daytimes'],
                "expires" => date("Y-m-d H:i", $reply['starttime']) . " ～ " . date("Y-m-d H:i", $reply['endtime']) ,
                'exchange' => $reply['description'],
                "rule" => $reply['rule'],
            );
        } else {
            $data = array(
                "success" => true,
                "usedAllTimes" => $reply['alltimes'],
                "usedDayTimes" => $reply['daytimes'],
                "allTimes" => $reply['alltimes'],
                "dayTimes" => $reply['daytimes'],
               "expires" => date("Y年m月d日  H:i", $reply['starttime']) . " ～ " . date("Y年m月d日  H:i", $reply['endtime']) ,
                'exchange' => $reply['description'],
                "rule" => $reply['rule'],
            );
        }
        echo json_encode($data);
    }

    public function doMobileTicket() {
        global $_GPC, $_W;
        $rid = intval($_GPC['id']);
        $uid =intval($_GPC['uid']);
        $return = array();
        $reply = pdo_fetch("SELECT * FROM " . tablename($this->tablename) . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
        $ifans = pdo_fetch("select * from " . tablename('ewei_money_fans') . " where rid=:rid AND id=:id ORDER BY max_score DESC", array(':rid' => $rid, ':id' => $uid));
        if (!empty($uid)) {
            $list = pdo_fetchall("SELECT * FROM " . tablename("ewei_money_award") . " WHERE rid = :rid AND uid=:uid ORDER BY `id` DESC", array(':rid' => $rid, ':uid' => $uid));
            if ($list != false) {
                foreach ($list as $row) {
                    $return['result'][] = array(
                        "id" => $row['id'],
                        'sum' => $row['sum'],
                        "type" => $row['name'],
                        "sn" => $row['award_sn'],
                        "rule" => $reply['remain_rule'],
                        "time" => $reply['valid_time'],
                        "status" => $row['status'] == 0 ? '未使用' : '已兑换',
                        "exchange" => $row['status'] == 0 ? true : false,
                        "useable" => false,
                        "shopUrl" => '#',
                    );
                }
            }
            $return['page'] = 1;
            $return['remain'] = $ifans['remain'];
            $return['total_remain'] = $reply['total_remain'];
            $return['success'] = true;
        }
        echo json_encode($return);
    }

    public function doMobileRank() {
        global $_GPC, $_W;
        $return = array();
        $rid = intval($_GPC['id']);
        $pindex = max(1, intval($_GPC['page']));
        $uid  = intval($_GPC['uid']);
        $psize = 10;
        $fans = pdo_fetch("select max_score from " . tablename('ewei_money_fans') . " where rid=:rid AND id=:id", array(':rid' => $rid, ':id' => $uid));
        //if($fans['max_score']>0){
        if ($fans) {
            if ($fans['max_score'] > 0) {
                $return['uid'] = $fans['id'];
                $max_score = $fans['max_score'] + 0.01;
                $rank = pdo_fetchcolumn("select count(id) from " . tablename('ewei_money_fans') . " where rid=:rid AND max_score >" . $max_score . "", array(':rid' => $rid));
                $return['max'] = $fans['max_score'];
                $return['rank'] = $rank + 1;
            } else {
                $return['max'] = 'null';
                $return['rank'] = 'null';
            }
        } else {
            $return['uid'] = 0;
        }
        $list = pdo_fetchall("SELECT max_score,sum,nickname  FROM " . tablename('ewei_money_fans') . " WHERE rid=:rid AND  max_score > 0 ORDER BY max_score DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(':rid' => $rid));
        $count = 1;
        foreach ($list as $row) {
            if ($row['nickname'] != '') {
                $return['result'][] = array(
                    "rank" => ($pindex - 1) * $psize + $count,
                    'name' => $row['nickname'],
                    'sum' => $row['max_score'],
                );
                $count+=1;
            }
        }
        $return['page'] = $pindex;
        $return['success'] = true;
        echo json_encode($return);
    }

    public function doMobileUser() {
        global $_GPC, $_W;
        $rid = intval($_GPC['id']);
        $return = array();
        $openid = $_GPC['openid'];
        $uid = intval($_GPC['uid']);
        if(!empty($uid)){
            $fans = pdo_fetch("SELECT * FROM " . tablename($this->tablefans) . " WHERE rid = :rid and id = :id ", array(':id' => $uid, ':rid' => $rid));    
        }
        
        if(empty($uid) || empty($fans)){
             $reply = pdo_fetch("SELECT * FROM " . tablename($this->tablename) . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
              
             $insert = array(
                'rid' => $rid,
                'weid'=>$_W['uniacid'],
                'daytimes' => $reply['daytimes'],
                'alltimes' => $reply['alltimes'],
                'remain' => $reply['remain'],
                'from_user' => $openid,
                'nickname' => $_GPC['nick'],
                'mobile' => $_GPC['mobile'],
            );
             pdo_insert('ewei_money_fans', $insert);
             $uid = pdo_insertid();
            
            //游戏次数
            pdo_query("update " . tablename($this->tablename) . " set play_times=play_times+1 where rid=" . $rid . "");
            
             echo json_encode(array(
            "success"=>'true',
            "uid"=>$uid
           ));
        }
        else{
             echo json_encode(array(
              "success"=>'true',
               "uid"=>0
             ));
        }
     
       
       
    }

    public function doMobileExchange() {
        global $_GPC, $_W;
        $rid = intval($_GPC['id']);
        $data = array();
        $reply = pdo_fetch("SELECT * FROM " . tablename($this->tablename) . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));

        //判断密码是否正确 
        if (trim($_GPC['sn']) == trim($reply['remain_sm'])) {
            //改变现金卷状态
            $res = pdo_query("update " . tablename("ewei_money_award") . " set status=1 where rid=" . $rid . " AND id = " . intval($_GPC['snid']) . "");
            $data['success'] = true;
            $data['status'] = '已兑换';
        } else {
            $data['success'] = false;
            $data['status'] = '未使用';
        }

        $return = array(
            'success' => $data['success'],
            'status' => $data['status'],
        );
        echo json_encode($return);
    }

    public function doMobileScore() {
     
        global $_GPC, $_W;
        //会员是否存在
        $rid = intval($_GPC['id']);
        $score = floatval($_GPC['score']);
        $uid = intval($_GPC['uid']);
        $return = array();
        $reply = pdo_fetch("SELECT * FROM " . tablename($this->tablename) . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
        if (empty($reply)) {
            message('参数错误');
        }
        
        $ifans = pdo_fetch("select * from " . tablename('ewei_money_fans') . " where rid=:rid AND id=:id ORDER BY max_score DESC", array(':rid' => $rid, ':id' =>$uid));
        $today = mktime(0, 0, 0);
        if ($ifans['lasttime'] < $today) {
               $sql = "update " . tablename($this->tablefans) . " set daytimes = '" . $reply['daytimes'] . "' where rid='" . $rid . "' and from_user = '" . $_W['fans']['from_user'] . "'";
               pdo_query($sql);
               $ifans['daytimes'] = $reply['daytimes'];
         }
         
            
                if ($_GPC['_scoreFirst'] == 'true' || ($_GPC['_scoreFirst'] == 'false' && $ifans['max_score'] == 0)) {
                    //记录分数
                    if ($score > $ifans['max_score']) {
                        $ifans['max_score'] = $score;
                    }
                    $today = mktime(0, 0, 0);
                    if ($score > 0) {
                        if ($ifans['daytimes'] > 0 && $ifans['alltimes'] > 0) {
                            $ifans['alltimes'] = $ifans['alltimes'] - 1;
                            if ($ifans['lasttime'] < $today) {
                                $ifans['daytimes'] = $reply['daytimes'] - 1;
                            } else {
                                $ifans['daytimes'] = $ifans['daytimes'] - 1;
                            }
                        }
                        $ifans['sum']+=$score;
                        $ifans['lasttime'] = time();
                        //保存积分
                        pdo_update('ewei_money_fans', $ifans, array('id' => $ifans['id']));
                    }
                }
                $m = $ifans['max_score'] + 0.01;
                $ranking = pdo_fetchcolumn("select count(id) from " . tablename('ewei_money_fans') . " where rid=:rid AND max_score >" . $m . "", array(':rid' => $rid));
                $ifans['ranking'] = $ranking + 1;
                //兑奖
                if ($_GPC['_scoreFirst'] == 'true' || ($_GPC['_scoreFirst'] == 'false' && $ifans['max_score'] == 0)) {
                    //现金劵
                    if ($reply['min_sum'] < $score) {
                        //查询fans的现金劵数是否大于0
                        if ($ifans['remain'] > 0) {
                            //用户获得现金劵
                            $sn = random(16);
                            if ($score > $reply['max_sum']) {
                                $ifans['max_sum'] = $reply['max_sum'];
                            } else {
                                $ifans['max_sum'] = $score;
                            }
                            $data = array(
                                'sum' => $ifans['max_sum'],
                                "name" => $reply['remain_name'],
                                "award_sn" => $sn,
                                "createtime" => time(),
                                "status" => 0,
                                "rid" => $ifans['rid'],
                                "uid" => $ifans['id'],
                                "from_user" => $ifans['from_user'],
                            );
                            pdo_insert('ewei_money_award', $data);
                            $data1['remain'] = $ifans['remain'] - 1;
                            pdo_update('ewei_money_fans', $data1, array('id' => $ifans['id']));
                        }
                    }
                }
                $total_remain = pdo_fetchcolumn("select count(id) from " . tablename('ewei_money_award') . "", array());
                $ifans['total_remain'] = $reply['total_remain'] - $total_remain;
                $ifans['min_sum'] = $reply['min_sum'];
    
            //total_remain现金卷总数,个人现金卷remain,max最佳成绩
            $return = array(
                'success' => 'true',
                'rank' => $ifans['ranking'],
                'max' => $ifans['max_score'],
                'total_remain' => $ifans['total_remain'],
                'remain' => $ifans['remain'],
                'remainAllTimes' => $ifans['alltimes'],
                'remainDayTimes' => $ifans['daytimes'],
                'max_sum' => $reply['max_sum'],
                'min_sum' => $ifans['min_sum'],
                'uid' => intval($_GPC['uid']),
                'timeout' => empty($reply['game_time']) ? 10 : $reply['game_time'],
                'customer' => $ifans['nickname'],
                '_t' => time(),
                'end' => ($reply['endtime'] > time() ? false : true),
            );
            $return['result'] = array(
                'exchange' => 'false',
                'useable' => 'false',
            );
           
        echo json_encode($return);
    }

    public function doWebRank() {
        //这个操作被定义用来呈现 规则列表
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $pindex = max(1, intval($_GPC['page']));
        $psize = 10;
        if ($rid) {
            $where = 'WHERE rid=:rid and nickname != ""';
            $arr = array(':rid' => $rid);
        } else {
            $arr = array();
        }
        $list = pdo_fetchall("SELECT * FROM " . tablename('ewei_money_fans') . $where . " ORDER BY max_score DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $arr);
        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('ewei_money_fans') . $where . " ORDER BY max_score DESC", $arr);
        $pager = pagination($total, $pindex, $psize);
        include $this->template('rank');
    }

    public function doWebawardlist() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        if (empty($rid)) {
            message('抱歉，传递的参数错误！', '', 'error');
        }
        $where = '';
        $params = array(':rid' => $rid);
        if (!empty($_GPC['status'])) {
            $where.=' and a.status=:status';
            $params[':status'] = intval($_GPC['status']);
            if ($params[':status'] == 2) {
                $params[':status'] = 0;
            }
        }
        if (!empty($_GPC['keywords'])) {
            $where.=' and (a.award_sn like :award_sn or f.mobile like :mobile)';
            $params[':award_sn'] = "%{$_GPC['keywords']}%";
            $params[':mobile'] = "%{$_GPC['keywords']}%";
        }

        $total = pdo_fetchcolumn("SELECT count(a.id) FROM " . tablename('ewei_money_award') . " a left join " . tablename('ewei_money_fans') . " f on a.from_user=f.from_user WHERE a.rid = :rid" . $where . "", $params);
        $pindex = max(1, intval($_GPC['page']));
        $psize = 10;
        $pager = pagination($total, $pindex, $psize);
        $start = ($pindex - 1) * $psize;
        $limit .= " LIMIT {$start},{$psize}";
        $list = pdo_fetchall("SELECT a.*,f.nickname,f.mobile FROM " . tablename('ewei_money_award') . " a left join " . tablename('ewei_money_fans') . " f on a.from_user=f.from_user WHERE a.rid = :rid " . $where . " ORDER BY a.id ASC " . $limit, $params);
        /*        //一些参数的显示
          $num1 = pdo_fetchcolumn("SELECT total_num FROM " . tablename($this->tablename) . " WHERE rid = :rid", array(':rid' => $rid));
          $num2 = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('ewei_money_award') . " WHERE rid = :rid and status=1", array(':rid' => $rid));
          $num3 = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('ewei_money_award') . " WHERE rid = :rid and status=2", array(':rid' => $rid)); */
        include $this->template('awardlist');
    }

    public function web_message($error, $url = '', $errno = -1) {
        $data = array();
        $data['errno'] = $errno;
        if (!empty($url)) {
            $data['url'] = $url;
        }
        $data['error'] = $error;
        echo json_encode($data);
        exit;
    }

}
