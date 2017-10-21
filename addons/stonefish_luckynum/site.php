<?php
/**
 * 幸运数字活动模块
 *
 * @author 微赞
 * @url http://www.00393.com/
 */
defined('IN_IA') or exit('Access Denied');
include 'common.inc.php';
class stonefish_luckynumModuleSite extends WeModuleSite {
    public function doWebDeleteaward(){
        checklogin();
        global $_W,$_GPC;
        $id = intval($_GPC['id']);
        $rid = intval($_GPC['rid']);
        $sql = "SELECT id FROM " . tablename('stonefish_luckynum_award') . " WHERE `id`=:id";
        $row = pdo_fetch($sql, array(':id'=>$id));
        if (empty($row)) {
            message('抱歉，奖品不存在或是已经被删除！', '', 'error');
        }
        if (pdo_delete('stonefish_luckynum_award', array('id' => $id))) {
            message('奖品删除成功', murl('platform/reply/post', array(
                'm' => 'stonefish_luckynum',
                'rid' => $rid,
            )), 'success');
        }
    }
	public function doWebFanslist() {
        global $_GPC, $_W;
		$rid = $_GPC['rid'];
		$params = array(':rid' => $rid, ':uniacid' => $_W['uniacid']);
		if (!empty($_GPC['realname'])) {     
            $where.=' and realname=:realname';
            $params[':realname'] = $_GPC['realname'];
        }
		if (!empty($_GPC['mobile'])) {     
            $where.=' and mobile=:mobile';
            $params[':mobile'] = $_GPC['mobile'];
        }
		//导出标题以及参数设置
		if($_GPC['status']==''){
		    $statustitle = '全部';
		}
		if($_GPC['status']==1){
		    $statustitle = '已中奖';
			$where.=' and zhongjiang>=1';
		}
		if($_GPC['status']==2){
		     $statustitle = '已提交';
			$where.=' and zhongjiang=2';
		}
		if($_GPC['status']==3){
		     $statustitle = '未兑换';
			 $where.=' and zhongjiang<=2 and zhongjiang>=1';
		}
		if($_GPC['status']==4){
		     $statustitle = '未中奖';
			 $where.=' and zhongjiang=0';
		}
		if($_GPC['status']==5){
		     $statustitle = '虚拟奖';
			 $where.=' and xuni=1';
		}
		if($_GPC['status']==6){
		     $statustitle = '已兑换';
			$where.=' and zhongjiang=3';
		}
		//导出标题以及参数设置				
		$total = pdo_fetchcolumn("select count(id) from " . tablename('stonefish_luckynum_fans') . "  where rid = :rid and uniacid=:uniacid " . $where . "", $params);
        $pindex = max(1, intval($_GPC['page']));
        $psize = 12;
        $pager = pagination($total, $pindex, $psize);
        $start = ($pindex - 1) * $psize;
        $limit .= " LIMIT {$start},{$psize}";
        $list = pdo_fetchall("select * from " . tablename('stonefish_luckynum_fans') . " where rid = :rid and uniacid=:uniacid " . $where . " order by id desc " . $limit, $params);
		//中奖情况
		foreach ($list as &$lists) {
			$lists['awardinfo'] = pdo_fetchcolumn("select count(id) from " . tablename('stonefish_luckynum_fans') . "  where rid = :rid and from_user=:from_user and zhongjiang>=1", array(':rid' => $rid,':from_user' => $lists['from_user']));			
		}
		//中奖情况
		//一些参数的显示
        $num1 = pdo_fetchcolumn("select count(distinct(from_user)) as total from " . tablename('stonefish_luckynum_fans') . "  where rid = :rid and uniacid=:uniacid", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
        $num2 = pdo_fetchcolumn("select count(id) from " . tablename('stonefish_luckynum_fans') . "  where rid = :rid and uniacid=:uniacid and zhongjiang>0", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
        $num3 = pdo_fetchcolumn("select count(id) from " . tablename('stonefish_luckynum_fans') . "  where rid = :rid and uniacid=:uniacid and zhongjiang=1", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
		$num4 = pdo_fetchcolumn("select count(id) from " . tablename('stonefish_luckynum_fans') . "  where rid = :rid and uniacid=:uniacid and zhongjiang=2", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
		$num5 = pdo_fetchcolumn("select count(id) from " . tablename('stonefish_luckynum_fans') . "  where rid = :rid and uniacid=:uniacid and zhongjiang=0", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
		$num6 = pdo_fetchcolumn("select count(id) from " . tablename('stonefish_luckynum_fans') . "  where rid = :rid and uniacid=:uniacid and xuni=1", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
		$num7 = pdo_fetchcolumn("select count(id) from " . tablename('stonefish_luckynum_fans') . "  where rid = :rid and uniacid=:uniacid and zhongjiang=3", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
		//一些参数的显示
        include $this->template('fanslist');
    }
	public function doWebAwardlist(){
		global $_GPC, $_W;        
        if (!empty($_GPC['id'])) {
                pdo_update('stonefish_luckynum_fans', array('zhongjiang' => intval($_GPC['zhongjiang']),'consumetime' => time()), array('id' => $_GPC['id']));
                message('操作成功', referer(), 'success');
        }else{
			message('非法请求！');
		}
	}
	public function doWebAddaward() {
        global $_GPC, $_W;
		if($_W['isajax']) {
				$uid = intval($_GPC['uid']);
				$rid = intval($_GPC['rid']);
				//规则
				$reply = pdo_fetch("select * from " . tablename('stonefish_scratch_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
				//粉丝数据
				$data = pdo_fetch("select * from " . tablename('stonefish_luckynum_fans') . ' where id = :id and uniacid = :uniacid', array(':uniacid' => $_W['uniacid'], ':id' => $uid));
				$fansID = pdo_fetchcolumn("select uid as fansID from ".tablename('mc_mapping_fans')." where openid='".$data['from_user']."'");
				if($fansID){
					load()->model('mc');
					$profile = mc_fetch($fansID, array('realname','mobile'));
				}
				//奖品数据
				$awardlist = pdo_fetchall("select * from " . tablename('stonefish_luckynum_award') . ' where rid = :rid and uniacid = :uniacid order by id ASC', array(':uniacid' => $_W['uniacid'], ':rid' => $rid));
				$awards = pdo_fetchall("select number from " . tablename('stonefish_luckynum_fans') . ' where rid = :rid and uniacid = :uniacid and zhongjiang>=1 order by id desc', array(':uniacid' => $_W['uniacid'], ':rid' => $rid));
				foreach ($awardlist as &$val) {
                    $val['number'] = explode(',',$val['numbers']);
					foreach ($awards as &$awardsnum) {
						$key = array_search($awardsnum['number'], $val['number']);
						if ($key !== false)
						array_splice($val['number'], $key, 1);							
					}
                }
				include $this->template('xuniaward');
				exit();
		}
       
    }
	public function doWebAddawardsave() {
        global $_GPC, $_W;
		$uid = intval($_GPC['uid']);
		$rid = intval($_GPC['rid']);
		$number = intval($_GPC['number']);
		if(!$number){
		    message('必需选择奖品才能生效', url('site/entry/fanslist',array('rid' => $rid, 'm' => 'stonefish_luckynum')), 'error');
		}
		if(!$rid){
		    message('系统出错', url('site/entry/fanslist',array('rid' => $rid, 'm' => 'stonefish_luckynum')), 'error');
		}
		if($uid) {
			//查询奖品ID
			$awardid = pdo_fetchcolumn("select id from ".tablename('stonefish_luckynum_award')." where find_in_set($number, numbers)");
			//添加中奖记录
            pdo_update('stonefish_luckynum_fans', array('number' => $number,'award_id' => $awardid,'zhongjiang' => 1,'xuni' => 1), array('id' => $uid));
			message('添加虚拟中奖成功', url('site/entry/fanslist',array('rid' => $rid, 'm' => 'stonefish_luckynum')));
		} else {
			message('未找到指定用户', url('site/entry/fanslist',array('rid' => $rid, 'm' => 'stonefish_luckynum')), 'error');
		}       
    }
	public function doWebAwardfrom() {
        global $_GPC, $_W;
		if($_W['isajax']) {
				$uid = intval($_GPC['uid']);
				$rid = intval($_GPC['rid']);
				//粉丝数据
				$data = pdo_fetch("select * from " . tablename('stonefish_luckynum_fans') . ' where id = :id and uniacid = :uniacid', array(':uniacid' => $_W['uniacid'], ':id' => $uid));
				$fansID = pdo_fetchcolumn("select uid as fansID from ".tablename('mc_mapping_fans')." where openid='".$data['from_user']."'");
				if($fansID){
					load()->model('mc');
					$profile = mc_fetch($fansID, array('realname','mobile'));
				}
				$list = pdo_fetchall("select * from " . tablename('stonefish_luckynum_fans') . "  where rid = :rid and uniacid=:uniacid and from_user=:from_user and zhongjiang>=1 order by id desc ", array(':uniacid' => $_W['uniacid'], ':rid' => $rid, ':from_user' => $data['from_user']));
				if ($list) {
                    foreach ($list as &$val) {
                        $val['award'] = pdo_fetch("SELECT * FROM ".tablename('stonefish_luckynum_award')." WHERE id={$val['award_id']}");
                    }
                }
				include $this->template('awardfrom');
				exit();
		}
    }
	public function doWebUserinfo() {
        global $_GPC, $_W;
		if($_W['isajax']) {
				$uid = intval($_GPC['uid']);
				$rid = intval($_GPC['rid']);
				//兑奖资料
				$reply = pdo_fetch("select * from " . tablename('stonefish_luckynum') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
				$isfansname = explode(',',$reply['isfansname']);
				//粉丝数据
				$data = pdo_fetch("select * from " . tablename('stonefish_luckynum_fans') . ' where id = :id', array(':id' => $uid));
				$fansID = pdo_fetchcolumn("select uid as fansID from ".tablename('mc_mapping_fans')." where openid='".$data['from_user']."'");
				if($fansID){
					load()->model('mc');
					$profile = mc_fetch($fansID, array('realname','mobile'));
				}				
				include $this->template('userinfo');
				exit();
		}
    }

	public function doMobileInfosubmit() {
		global $_W, $_GPC;
        list($rid, $from_user) = explode('|', superman_authcode(trim($_GPC['_x']), 'DECODE'));
        if (empty($rid) || empty($from_user)) {
            message('非法请求！');
        }
        $reply = pdo_fetch("select * from " . tablename('stonefish_luckynum') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
		$isfansname = explode(',',$reply['isfansname']);
		$fansID = pdo_fetchcolumn("select uid as fansID from ".tablename('mc_mapping_fans')." where openid='".$from_user."'");
		$profile = mc_fetch($fansID, array('avatar','nickname','realname','mobile','qq','email','address','gender','telephone','idcard','company','occupation','position'));
		$success = false;
        if (checksubmit()) {
            //查询规则保存哪些数据
			$updata = array();
			if($reply['isrealname']){
			    $updata['realname'] = $_GPC['realname'];
			}
			if($reply['ismobile']){
			    $updata['mobile'] = $_GPC['mobile'];
			}
			if($reply['isqq']){
			    $updata['qq'] = $_GPC['qq'];
			}
			if($reply['isemail']){
			    $updata['email'] = $_GPC['email'];
			}
			if($reply['isaddress']){
			    $updata['address'] = $_GPC['address'];
			}
			if($reply['isgender']){
			    $updata['gender'] = $_GPC['gender'];
			}
			if($reply['istelephone']){
			    $updata['telephone'] = $_GPC['telephone'];
			}
			if($reply['isidcard']){
			    $updata['idcard'] = $_GPC['idcard'];
			}
			if($reply['iscompany']){
			    $updata['company'] = $_GPC['company'];
			}
			if($reply['isoccupation']){
			    $updata['occupation'] = $_GPC['occupation'];
			}
			if($reply['isposition']){
			    $updata['position'] = $_GPC['position'];
			}
			pdo_update('stonefish_luckynum_fans', array('zhongjiang' => 2), array('rid' => $rid, 'from_user' => $from_user, 'zhongjiang' => 1));
			$temp = pdo_update('stonefish_luckynum_fans', $updata, array('rid' => $rid, 'from_user' => $from_user));
            if ($temp === false) {
				$success = false;
			}else{
				if($reply['isfans']){
				    load()->model('mc');
                    mc_update($fans_ID, $updata);
				}
				$success = true;
			}
        }
        include $this->template('infosubmit');
	}
	public function doMobileShare() {
		global $_W, $_GPC;
        $rid = $_GPC['rid'];
        $reply = pdo_fetch("select * from " . tablename('stonefish_luckynum') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
        //分享信息
        $sharelink = $_W['siteroot'] .'app/'.$this->createMobileUrl('share', array('rid' => $rid));
        $sharetitle = $reply['sharetitle'];
        $sharedesc = $reply['sharedesc'];		
		$shareimg = toimage($reply['shareimg']);
		include $this->template('share');
	}
	
	public function doWebDeletefans() {
        global $_GPC, $_W;
		$rid = intval($_GPC['rid']);
		$reply = pdo_fetch("select * from ".tablename('stonefish_luckynum')." where rid = :rid and uniacid=:uniacid", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
        if (empty($reply)) {
            $this->webmessage('抱歉，要修改的活动不存在或是已经被删除！');
        }		
        foreach ($_GPC['idArr'] as $k => $id) {
            $id = intval($id);
            if ($id == 0)
                continue;
			$fans = pdo_fetch("select * from ".tablename('stonefish_luckynum_fans')." where id = :id", array(':id' => $id));
            if (empty($fans)) {
                $this->webmessage('抱歉，选中的粉丝数据不存在！');
            }
			pdo_delete('stonefish_luckynum_fans', array('id' => $id));
			//删除粉丝参与记录
        }
        $this->webmessage('粉丝记录删除成功！', '', 0);
    }
	
	public function doWebList() {
        global $_GPC, $_W;
        load()->model('reply');
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $sql = "uniacid = :uniacid and `module` = :module";
        $params = array();
        $params[':uniacid'] = $_W['uniacid'];
        $params[':module'] = 'stonefish_luckynum';

        if (!empty($_GPC['keyword'])) {
            $sql .= ' and `name` LIKE :keyword';
            $params[':keyword'] = "%{$_GPC['keyword']}%";
        }
        $list = reply_search($sql, $params, $pindex, $psize, $total);
        $pager = pagination($total, $pindex, $psize);

        if (!empty($list)) {
            foreach ($list as &$item) {
                $condition = "`rid`={$item['id']}";
                $item['keyword'] = reply_keywords_search($condition);
                $luckynum = pdo_fetch("select starttime,endtime,isshow from " . tablename('stonefish_luckynum') . " where rid = :rid", array(':rid' => $item['id']));
                $item['fansview'] = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('stonefish_luckynum_fans')." where rid=:rid", array(':rid' => $item['id']));
				$item['fansnum'] = pdo_fetchcolumn("SELECT count(distinct(from_user)) as total FROM ".tablename('stonefish_luckynum_fans')." where rid=:rid", array(':rid' => $item['id']));
                $item['starttime'] = date('Y-m-d H:i', $luckynum['starttime']);
                $endtime = $luckynum['endtime'] + 86399;
                $item['endtime'] = date('Y-m-d H:i', $endtime);
                $nowtime = time();
                if ($luckynum['starttime'] > $nowtime) {
                    $item['status'] = '<span class="label label-warning">未开始</span>';
                    $item['show'] = 1;
                } elseif ($endtime < $nowtime) {
                    $item['status'] = '<span class="label label-default ">已结束</span>';
                    $item['show'] = 0;
                } else {
                    if ($luckynum['isshow'] == 1) {
                        $item['status'] = '<span class="label label-success">已开始</span>';
                        $item['show'] = 2;
                    } else {
                        $item['status'] = '<span class="label label-default ">已暂停</span>';
                        $item['show'] = 1;
                    }
                }
                $item['isshow'] = $luckynum['isshow'];
            }
        }
        include $this->template('list');
    }
	
	public function doWebSetshow() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $isshow = intval($_GPC['isshow']);

        if (empty($rid)) {
            message('抱歉，传递的参数错误！', '', 'error');
        }
        $temp = pdo_update('stonefish_luckynum', array('isshow' => $isshow), array('rid' => $rid));
        message('状态设置成功！', referer(), 'success');
    }
	public function doWebDelete() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $rule = pdo_fetch("select id, module from " . tablename('rule') . " where id = :id and uniacid=:uniacid", array(':id' => $rid, ':uniacid' => $_W['uniacid']));
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
        message('活动删除成功！', referer(), 'success');
    }

    public function doWebDeleteAll() {
        global $_GPC, $_W;
        foreach ($_GPC['idArr'] as $k => $rid) {
            $rid = intval($rid);
            if ($rid == 0)
                continue;
            $rule = pdo_fetch("select id, module from " . tablename('rule') . " where id = :id and uniacid=:uniacid", array(':id' => $rid, ':uniacid' => $_W['uniacid']));
            if (empty($rule)) {
                $this->webmessage('抱歉，要修改的规则不存在或是已经被删除！');
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
        $this->webmessage('选择中的活动删除成功！', '', 0);
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
	public function doWebDownload() {
        require_once 'download.php';
    }
}