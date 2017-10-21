<?php
    require_once IA_ROOT . "/addons/cgc_gzredbag/WxPay/WxPayPubHelper.php";

load()->func('logging');
	global $_W, $_GPC;
  	load()->func('tpl');
  	$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
  	$uniacid=$_W["uniacid"];
  	$id=$_GPC['id'];
  	if ($op=='display') {
  		$pindex = max(1, intval($_GPC['page']));	
		$psize= 20;
		$list = pdo_fetchall("SELECT *  from ".tablename('gzredbag_wxpay')."  where uniacid=$uniacid order by id desc LIMIT ". ($pindex -1) * $psize . ',' .$psize );
		$total = pdo_fetchcolumn("SELECT COUNT(*)  from ".tablename('gzredbag_wxpay')."  where uniacid=$uniacid ");
		$pager = pagination($total, $pindex, $psize);
  	}
  	if ($op=='post') {
  		if (!empty($id)) {
  		$item = pdo_fetch("SELECT *  from ".tablename('gzredbag_wxpay')."  where uniacid=$uniacid and id=$id");
  		if (empty($item)) {
				message('抱歉，项目不存在或是已经删除！', '', 'error');
			}
		}
		if (checksubmit('submit')) {
			$data = array(
			    'uniacid' => $_W['uniacid'],
			    'title'=> $_GPC['title'],
				'money' => $_GPC['money'],
				/*'max_money' => $_GPC['max_money'],
				'min_money' => $_GPC['min_money'],*/
				'remark' => $_GPC['remark'],
				'createtime' => time()
			);
         
			if (!empty($id)) {
			  $ret=native_pay_one(array('uniacid'=>$_W['uniacid'],'product_id'=>$id));
			  if ($ret['code']!=1){
                  message($ret['msg'], referer(), 'error');
              }
			  $data['qr_url']=$ret['qr_url'];
			  pdo_update('gzredbag_wxpay', $data, array('id' => $id));
			  message('更新成功！', $this->createWebUrl('wxpay'), 'success');
			} else {
			   pdo_insert('gzredbag_wxpay', $data);
			   $id = pdo_insertid();
			   $ret=native_pay_one(array('uniacid'=>$_W['uniacid'],'product_id'=>$id));	
			   if ($ret['code']!=1){
                  message($ret['msg'], referer(), 'error');
                }	
               $qr_url=$ret['qr_url'];			
			   pdo_update('gzredbag_wxpay', array('qr_url'=>$qr_url), array('id' => $id));
               message('增加成功！', $this->createWebUrl('wxpay'), 'success');
			}
		  }
		} 
		
		if ($op=='delete') {
			pdo_delete('gzredbag_wxpay', array('id' => $id));
            message('删除成功！', $this->createWebUrl('wxpay', array('op' => 'display')), 'success');
		}
		
		if ($op=='show_url') {
		   $item = pdo_fetch("SELECT *  from ".tablename('gzredbag_wxpay')."  where uniacid=$uniacid and id=$id");
		  
	       exit($item['qr_url']);
		}

  	include $this->template('wxpay');