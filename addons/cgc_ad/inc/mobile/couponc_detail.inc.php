<?php

global $_W, $_GPC;
$weid = $_W['uniacid'];
$quan_id = intval($_GPC['quan_id']);
$id = intval($_GPC['id']);
$member = $this->get_member();
$from_user = $member['openid'];
$subscribe = $member['follow'];
$quan = $this->get_quan();
$adv = $this->get_adv();
$config = $this->settings;
$rob_next_time = $member['rob_next_time'];
$mid = $member['id'];
$op = empty($_GPC['op']) ? "display" : $_GPC['op'];
if ($op == 'is_open') {
    if ($adv['openid'] != $member['openid'] && empty($member['is_kf'])) {
        $this->returnError("没权限");
    }
    $id = $_GPC['id'];
    pdo_update('cgc_ad_adv', array(
        'is_open' => $_GPC['status']
    ) , array(
        'id' => $_GPC['id'],
        'weid' => $weid,
        'quan_id' => $_GPC['quan_id']
    ));
    $this->returnSuccess("操作口令成功", referer() , 'success');
}
$quan['city'] = str_replace("|", "或", $quan['city']);
if ($op == 'display') {
    $my = pdo_fetch("SELECT * FROM " . tablename('cgc_ad_red') . " WHERE weid=" . $weid . " AND quan_id=" . $quan_id . " AND advid=" . $id . " AND mid=" . $mid);
    // 抢钱令牌，避免重复提交
    $_SESSION['rob_token'] = md5(microtime(true));
    $adv['views'] = $this->get_view($member, $adv);
    $pagesize = 50;
    $red = pdo_fetchall("SELECT a.*,b.type,b.headimgurl,b.nickname FROM " . tablename('cgc_ad_red') . " as a " . "  left join " . tablename('cgc_ad_member') . " as b on a.mid=b.id " . " WHERE a.weid=" . $weid . " AND a.quan_id=" . $adv['quan_id'] . " AND a.advid=" . $id . " ORDER BY a.create_time DESC limit 0,$pagesize", array() , "mid");
    $_red = pdo_fetchcolumn('SELECT count(id) FROM ' . tablename('cgc_ad_red') . " WHERE weid=" . $weid . " AND quan_id=" . $adv['quan_id'] . " AND advid=" . $id);
    $_msglist = pdo_fetchall("SELECT a.*,b.headimgurl,b.nickname FROM " . tablename('cgc_ad_message') . " a
	      left join " . tablename('cgc_ad_member') . " b on a.mid=b.id
		  WHERE a.weid=" . $weid . " AND a.advid=" . $id . " and a.status=1 order by upbdate desc limit 0,5");
    $_msgtotal = pdo_fetchcolumn('SELECT count(id) FROM ' . tablename('cgc_ad_message') . " WHERE weid=" . $weid . " AND status=1  and advid=" . $_GPC['id']);
    include $this->template('couponc_detail');
    exit();
}
if ($op == 'rob') {
    if ($adv['rob_users'] >= $adv['total_num']) {
        $this->returnError('手慢了，钱被抢光啦！');
    }
    if ($adv['is_kouling'] == 1 && $_GPC['kouling'] != $adv['kouling']) {
        $this->returnError('口令错误');
    }
    
    $ret = cal_red($member, $quan, $adv, $config);
    if ($ret['code'] == "0") {
        $this->returnError($ret['msg'], $ret['data']);
    } else {
    	pdo_insert('cgc_ad_couponc', array(
			'weid'=>$_W['uniacid'],
			'quan_id'=>$quan_id,
			'advid'=>$id,
			'mid'=>$mid,
			'openid'=> $member['openid'],
			'nickname'=> $member['nickname'],
			'avatar'=> $member['headimgurl'],
			'company_name'=> $adv['company_name'] ,
			'couponc_discount'=> $adv['couponc_discount'] ,			
			'couponc_money'=> $adv['couponc_money'] ,			
			'couponc_gift'=> $adv['couponc_gift'] ,					
			'couponc_shoper'=> $adv['couponc_shoper'] ,	
			'couponc_add'=> $adv['couponc_add'] ,	
			'couponc_tel'=> $adv['couponc_tel'] ,	
			'couponc_detail'=> $adv['couponc_detail'] ,	
			'couponc_rule'=> $adv['couponc_rule'] ,				
			'couponc_type'=> $adv['couponc_type'],
			'couponc_valid_date' => $adv['create_time'] + ($adv['couponc_valid_date']* 24 * 60 * 60),
			'couponc_miaosha'=> $adv['couponc_miaosha'],
			'couponc_name'=> $adv['couponc_name'],
			'ip' => getip(),
			'create_time'=>time()
		));
        $this->returnSuccess($ret['msg'], $ret['data']);
    }
}
if ($_GPC['op'] == 'get_morered') {
    $pagesize = 50;
    $__pages = intval($_GPC['page']) * $pagesize;
    $red = pdo_fetchall("SELECT a.*,b.headimgurl,b.nickname FROM " . tablename('cgc_ad_red') . " as a 
	          left join " . tablename('cgc_ad_member') . " as b on a.mid=b.id
			  WHERE a.weid=" . $weid . " AND a.quan_id=" . $adv['quan_id'] . " AND a.advid=" . $id . " ORDER BY a.create_time DESC limit " . $__pages . ",$pagesize");
    $ht = '';
    foreach ($red as $key => $r) {
        if ($r['is_luck']) {
            $is_luck = '<font style="color:#337AB7;">(最佳)</font>';
        }
        $is_luck = "";
        $ht.= '<div class="weui_cell" style="width:87%">
	           <div class="weui_cell_hd"><img src="' . $r['headimgurl'] . '" style="width:20px;margin-right:5px;display:block"></div>
	           <div class="weui_cell_bd weui_cell_primary">
	           <p>' . $r['nickname'] . '</p>
	           </div>
		       <div class="weui_cell_ft">' . $r['money'] . $config['unit_text'] . $is_luck . '</div>';
        $ht.= "</div>";
    }
    if (!empty($ht)) {
        exit(json_encode(array(
            'status' => 1,
            'log' => $ht
        )));
    } else {
        exit(json_encode(array(
            'status' => 0
        )));
    }
}

