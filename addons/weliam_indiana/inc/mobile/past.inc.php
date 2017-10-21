<?php
global $_W,$_GPC;
	if (empty($_GPC['id'])) {
        message('抱歉，参数错误！', '', 'error');
    }
	$id = intval($_GPC['id']);
	$uniacid=$_W['uniacid'];
	$openid = m('user') -> getOpenid();
	$goods = m('goods')->getGoods($id);
	$periods = pdo_fetchall("SELECT id,nickname,avatar,goodsid,periods,openid,code,endtime,zong_codes,shengyu_codes,canyurenshu,status,period_number,partakes FROM " . tablename('weliam_indiana_period') . " WHERE uniacid = '{$_W['uniacid']}' and goodsid ='{$id}' and status >= 3 order by id desc");
	foreach($periods as $key=>$value){
		if(empty($value['openid'])){
			unset($periods[$key]);
		}else{
			$member = m('member')->getInfoByOpenid($value['openid']);
			$periods[$key]['avatar'] = $member['avatar'];
		}
		
	}
	$periodss = pdo_fetchall("SELECT id,nickname,avatar,goodsid,periods,openid,code,endtime,zong_codes,shengyu_codes,canyurenshu,status,period_number,partakes FROM " . tablename('weliam_indiana_period') . " WHERE uniacid = '{$_W['uniacid']}' and goodsid ='{$id}' and status = 2 order by id desc");
//	echo "<pre>";print_r($periods);exit;
	include $this->template('past');
?>