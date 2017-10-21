<?php

		global $_W,$_GPC;
		load()->func('tpl');
		checklogin();
		$this->checkpay();
		$this->checkmode();
		$weid = $_W['uniacid'];
		include $this->template('web/area');
?>