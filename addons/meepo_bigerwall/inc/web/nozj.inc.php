<?php
global $_W, $_GPC;
		$id = intval($_GPC['id']);
		$weid = $_W['weid'];
		
		if (!empty($_GPC['openid'])) {
			$award_id = $_GPC['award_id'];
			pdo_update('weixin_flag', array('award_id' =>$award_id), array('openid' => $_GPC['openid'], 'rid'=>$id,'weid'=>$weid));
				message('操作成功！', $this->createWebUrl('manage', array('id' => $id, 'op' =>'list')));
			
		}