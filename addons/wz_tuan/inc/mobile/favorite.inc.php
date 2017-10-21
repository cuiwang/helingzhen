<?php
		global $_W,$_GPC;
		$this->getuserinfo();
		$weid = $_W['uniacid'];
		$share_data = $this->module['config'];
		$favorite = pdo_fetchall("SELECT * FROM " . tablename('wz_tuan_collect') . " WHERE uniacid = '{$_W['uniacid']}' AND openid = '{$_W['openid']}'");
		if (!empty($favorite)) {
			foreach ($favorite as $key => $value) {
				$goods = pdo_fetch("SELECT * FROM " . tablename('wz_tuan_goods') . " WHERE uniacid = '{$_W['uniacid']}' AND id = '{$value['sid']}'");
				$favorite[$key]['goods'] = $goods;
			}
		}
		include $this->template('favorite');
?>