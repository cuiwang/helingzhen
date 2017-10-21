<?php
		global $_GPC, $_W;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'remove';
		if ($operation=='add') {
			if (empty($_GPC['goodsid'])) {
				echo 0;
				exit;
			}else{
				$data=array(
	            'openid' => $_W['openid'],
	            'uniacid'=>$_W['uniacid'],
	            'sid'=>$_GPC['goodsid']
	            );
	            if (pdo_insert('wz_tuan_collect', $data)) {
	            	echo 1;
	            }else{
	            	echo 0;
	            }
			}
		}
		if ($operation=='remove') {
			if (empty($_GPC['goodsid'])) {
				echo 0;
				exit;
			}else{
				if (pdo_delete('wz_tuan_collect', array('uniacid' =>$_W['uniacid'], 'sid' => $_GPC['goodsid']))) {
					echo 1;
				}else{
					echo 0;
				}
			}
		}
?>