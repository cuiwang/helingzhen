<?php

defined('IN_IA') or exit('Access Denied');

class Fy_carModuleSite extends WeModuleSite {
	/* 微洗车数据表 */
	public $table_blacklist          = 'cash_car_blacklist';
	public $table_cart               = 'cash_car_cart';
	public $table_category           = 'cash_car_category';
    public $table_goods              = 'cash_car_goods';
	public $table_goods_evaluate     = 'cash_car_goods_evaluate';
	public $table_member_onecard     = 'cash_car_member_onecard';
	public $table_member_onecard_log = 'cash_car_member_onecard_log';
    public $table_nave               = 'cash_car_nave';
    public $table_onecard            = 'cash_car_onecard';
	public $table_onecard_order      = 'cash_car_onecard_order';
    public $table_order              = 'cash_car_order';
    public $table_order_goods        = 'cash_car_order_goods';	
    public $table_setting            = 'cash_car_setting';
    public $table_store              = 'cash_car_stores';
    public $table_worker             = 'cash_car_stores_worker';
	public $table_store_time         = 'cash_car_store_time';
	public $table_onecard_record     = 'cash_car_member_onecard_record';

	/* 微洗车核销数据表 */
	public $table_car_account        = 'fy_car_account';
	public $table_car_setting        = 'fy_car_setting';
	public $table_car_settle         = 'fy_car_settle';
	public $table_car_worker         = 'fy_car_worker';

/************************************* pc端（后台管理） ****************************************/
	
	/* 服务点帐号 */
	public function doWebAccount() {
		$this->__web(__FUNCTION__);
	}

	/* Web结算管理 */
	public function doWebSettle() {
		$this->__web(__FUNCTION__);
	}

	/* 基本设置 */
	public function doWebSetting() {
		$this->__web(__FUNCTION__);
	}


/************************************* mobile端（前台显示） *************************************/

	/* 服务点管理 */
	public function doMobileStore() {
		$this->__mobile(__FUNCTION__);
	}

	/* 工作人员管理 */
	public function doMobileLower() {
		$this->__mobile(__FUNCTION__);
	}

	/* Mobile结算管理 */
	public function doMobileSettle() {
		$this->__mobile(__FUNCTION__);
	}

	/*系统结算账单*/
	public function doMobileSystem() {
		$this->__mobile(__FUNCTION__);
	}

    /* 帐号登陆 */
    public function doMobilelogin() {
		global $_W,$_GPC;
		$weid = $_W['uniacid'];

		/* 已登录则跳转到主页 标记 */
		if(!empty($_SESSION['accountid'])){
			header("Location:{$this->createMobileUrl('store')}");
		}

		if(checksubmit('submit')){
			$account  = trim($_GPC['account']);
			$password = trim($_GPC['password']);
			if(empty($account) || empty($password)){
				message('请输入登陆账号和密码！', '', 'error');
			}

			/* 查询用户信息 */
			$user = pdo_fetch("SELECT * FROM " . tablename($this->table_car_account) . " WHERE weid='{$weid}' AND account='{$account}'");
			if(empty($user)){
				message('登录帐号不存在！', '', 'error');
			}

			/*验证用户密码*/
			if(md5($password) != $user['password']){
				message('登陆密码错误！', '', 'error');
			}

			/* 检查用户账号状态 */
			if($user['status'] != 1){
				message('抱歉，您的帐号已被冻结！', '', 'error');
			}

			/*设置登录SESSION*/
			session_start();
			$_SESSION['accountid'] = $user['id'];
			$_SESSION['account']   = $user['account'];

			/*标记*/
			message("登陆成功！", $this->createMobileUrl('store'), "success");
		}

		include $this->template('login');        
    }

	/* 注销登录 */
    public function doMobilelogout() {
		global $_W,$_GPC;
		session_start();
		unset($_SESSION['accountid']);
		unset($_SESSION['account']);

		message("退出成功！", $this->createMobileUrl('login'), "success");
	}

/************************************************ 公共函数 *************************************/

	public function __web($f_name){
		global $_W,$_GPC;
		$weid = $_W['uniacid'];
		$op = $operation = $_GPC['op']?$_GPC['op']:'display';
		include_once  'web/'.strtolower(substr($f_name,5)).'.php';
	}
	
	public function __mobile($f_name){
		global $_W,$_GPC;
		$weid = $_W['uniacid'];
		$op = $operation = $_GPC['op']?$_GPC['op']:'display';

		include_once  'mobile/'.strtolower(substr($f_name,8)).'.php';
	}

	private function checklogin(){
		global $_W,$_GPC;

		if(empty($_SESSION['accountid'])){
			header("Location:{$this->createMobileUrl('login')}");
		}
	}

	private function object_array($array){
		if(is_object($array)){
			$array = (array)$array;
		}
		if(is_array($array)){
			foreach($array as $key=>$value){
				$array[$key] = $this->object_array($value);
			}
		}
		return $array;
	}

	//导出excel文件
	protected function exportexcel($data = array(), $title = array(), $filename = 'report'){
        header("Content-type:application/octet-stream");
        header("Accept-Ranges:bytes");
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=" . $filename . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        //导出xls 开始
        if (!empty($title)) {
            foreach ($title as $k => $v) {
                $title[$k] = iconv("UTF-8", "GB2312", $v);
            }
            $title = implode("\t", $title);
            echo "$title\n";
        }
        if (!empty($data)) {
			$totalprice = 0;
            foreach ($data as $key => $val) {
                foreach ($val as $ck => $cv) {
                    $data[$key][$ck] = iconv("UTF-8", "GB2312", $cv);
                }
                $data[$key] = implode("\t", $data[$key]);
				$totalprice += $val['amount'];
            }
            echo implode("\n", $data);
        }
    }

	/**
	 * 生成分页数据
	 * @param int $currentPage 当前页码
	 * @param int $totalCount 总记录数
	 * @param string $url 要生成的 url 格式，页码占位符请使用 *，如果未写占位符，系统将自动生成
	 * @param int $pageSize 分页大小
	 * @return string 分页HTML
	 */
	 function pagination($total, $pageIndex, $pageSize = 15, $url = '', $context = array('before' => 5, 'after' => 4, 'ajaxcallback' => '')) {
		global $_W;
		$pdata = array(
			'tcount' => 0,
			'tpage' => 0,
			'cindex' => 0,
			'findex' => 0,
			'pindex' => 0,
			'nindex' => 0,
			'lindex' => 0,
			'options' => ''
		);
		if($context['ajaxcallback']) {
			$context['isajax'] = true;
		}

		$pdata['tcount'] = $total;
		$pdata['tpage'] = ceil($total / $pageSize);
		$cindex = $pageIndex;
		$cindex = min($cindex, $pdata['tpage']);
		$cindex = max($cindex, 1);
		$pdata['cindex'] = $cindex;
		$pdata['findex'] = 1;
		$pdata['pindex'] = $cindex > 1 ? $cindex - 1 : 1;
		$pdata['nindex'] = $cindex < $pdata['tpage'] ? $cindex + 1 : $pdata['tpage'];
		$pdata['lindex'] = $pdata['tpage'];

		if($context['isajax']) {
			if(!$url) {
				$url = $_W['script_name'] . '?' . http_build_query($_GET);
			}
			$pdata['faa'] = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['findex'] . '\', ' . $context['ajaxcallback'] . ')"';
			$pdata['paa'] = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['pindex'] . '\', ' . $context['ajaxcallback'] . ')"';
			$pdata['naa'] = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['nindex'] . '\', ' . $context['ajaxcallback'] . ')"';
			$pdata['laa'] = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['lindex'] . '\', ' . $context['ajaxcallback'] . ')"';
		} else {
			if($url) {
				$pdata['faa'] = 'href="?' . str_replace('*', $pdata['findex'], $url) . '"';
				$pdata['paa'] = 'href="?' . str_replace('*', $pdata['pindex'], $url) . '"';
				$pdata['naa'] = 'href="?' . str_replace('*', $pdata['nindex'], $url) . '"';
				$pdata['laa'] = 'href="?' . str_replace('*', $pdata['lindex'], $url) . '"';
			} else {
				$_GET['page'] = $pdata['findex'];
				$pdata['faa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
				$_GET['page'] = $pdata['pindex'];
				$pdata['paa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
				$_GET['page'] = $pdata['nindex'];
				$pdata['naa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
				$_GET['page'] = $pdata['lindex'];
				$pdata['laa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
			}
		}

		$html = '<div><ul>';
		if($pdata['cindex'] < $pdata['tpage'] || $pdata['tpage']==1) {
			$html .= "<li><span>首页</span></li>";
			$html .= "<li><span>上一页</span></li>";
		}else{
			$html .= "<li><a {$pdata['faa']} class=\"pager-nav\"><span>首页</span></a></li>";
			$html .= "<li><a {$pdata['paa']} class=\"pager-nav\"><span>上一页</span></a></li>";
		}

			if(!$context['before'] && $context['before'] != 0) {
			$context['before'] = 5;
		}
		if(!$context['after'] && $context['after'] != 0) {
			$context['after'] = 4;
		}

		if($context['after'] != 0 && $context['before'] != 0) {
			$range = array();
			$range['start'] = max(1, $pdata['cindex'] - $context['before']);
			$range['end'] = min($pdata['tpage'], $pdata['cindex'] + $context['after']);
			if ($range['end'] - $range['start'] < $context['before'] + $context['after']) {
				$range['end'] = min($pdata['tpage'], $range['start'] + $context['before'] + $context['after']);
				$range['start'] = max(1, $range['end'] - $context['before'] - $context['after']);
			}
			for ($i = $range['start']; $i <= $range['end']; $i++) {
				if($context['isajax']) {
					$aa = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $i . '\', ' . $context['ajaxcallback'] . ')"';
				} else {
					if($url) {
						$aa = 'href="?' . str_replace('*', $i, $url) . '"';
					} else {
						$_GET['page'] = $i;
						$aa = 'href="?' . http_build_query($_GET) . '"';
					}
				}
				$html .= ($i == $pdata['cindex'] ? '<li class="demo"><span class="currentpage">' . $i . '</span></li>' : "<li><a {$aa}><span>" . $i . '</span></a></li>');
			}
		}

		if($pdata['cindex'] >= $pdata['tpage']) {
			$html .= "<li><span>下一页</span></li>";
			$html .= "<li><span>尾页</span></li>";
		}else{
			$html .= "<li><a {$pdata['naa']} class=\"pager-nav\"><span>下一页</span></a></li>";
			$html .= "<li><a {$pdata['laa']} class=\"pager-nav\"><span>尾页</span></a></li>";
		}
		$html .= '</ul></div>';
		return $html;
	}

}
