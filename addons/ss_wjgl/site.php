<?php
/**
 * 入口文件
 */
defined ( 'IN_IA' ) or exit ( 'Access Denied' );
require IA_ROOT . '/addons/ss_wjgl/defines.php';
class ss_wjglModuleSite extends WeModuleSite {
	
	/**
	 * 资源管理
	 */
	public function doWebManager() {
		error_reporting ( 0 );
		set_time_limit ( 0 );
		global $_W, $_GPC;
		$op = $_GPC ['op'];
		$name = $_GPC ['name'];
		if ($op == "save") {
			$id = $_GPC['id'];
			
			$file = $_FILES ["file"];
			$filetype = $file ["type"];
			
			$filepath = XSY_RESOURCE_ATTACHMENT . "/" . $_W ['uniacid'] . "/" . date ( 'Ymd' ) . "/";
			$this->mkFolder ( IA_ROOT . "/" . $filepath );
			$filepath = $filepath . $this->uuid () . $file ['name'];
			if (move_uploaded_file ( $file ['tmp_name'], IA_ROOT . "/" . $filepath )) {
				
				if(empty($id))
				{
					$data = array (
							"uniacid" => $_W ['uniacid'],
							"name" => $name,
							"path" => $filepath,
							"createtime" => time ()
					);
					pdo_insert ( "ss_wjgl_file", $data );
				}
				else
				{
					$data = array (
							"name" => $name,
							"path" => $filepath
					);
					pdo_update("ss_wjgl_file",$data,array("id"=>intval($id)));
				}
				
				message ( '文件上传成功', $this->createWebUrl("manager"), 'success' );
			} else {
				message ( '文件上传失败', $this->createWebUrl("manager"), 'error' );
			}
		} else if ($op == 'del') {
			pdo_delete ( "ss_wjgl_file", array (
					"id" => intval ( $_GPC ['id'] ) 
			) );
			message ( '文件删除成功', $this->createWebUrl("manager"), 'success' );
		}
		if (! empty ( $name )) {
			$condition = " and name like '%" . $name . "%'";
		}
		$sql = "select * from " . tablename ( 'ss_wjgl_file' ) . " where uniacid={$_W['uniacid']} {$condition} ";
		
		$count_sql = "select count(*) from " . tablename ( "ss_wjgl_file" ) . " where uniacid={$_W['uniacid']} {$condition} ";
		
		$params = array ();
		$total = 0;
		$pager;
		$total_page;
		$list = array ();
		$this->get_page_data ( $sql, $count_sql, $params, $total, $pager, $list, $total_page );
		
		$resourcepath = $_W ['siteroot'] . "app/index.php?i={$_W['uniacid']}&c=entry&do=resource&m=ss_wjgl";
		
		include $this->template ( 'web/manager' );
	}
	public function uuid($prefix = '') {
		$chars = md5 ( uniqid ( mt_rand (), true ) );
		$uuid = substr ( $chars, 0, 8 );
		$uuid .= substr ( $chars, 8, 4 );
		$uuid .= substr ( $chars, 12, 4 );
		$uuid .= substr ( $chars, 16, 4 );
		$uuid .= substr ( $chars, 20, 12 );
		return $prefix . $uuid;
	}
	function get_page_data($sql, $count_sql, $params, &$total, &$pager, &$list, &$total_page = 0) {
		try {
			global $_GPC;
			$psize = 5;
			$pindex = max ( 1, intval ( $_GPC ['page'] ) );
			$total = pdo_fetchcolumn ( $count_sql, $params );
			$total_page = ($total % $psize == 0) ? $total / $psize : intval ( $total / $psize ) + 1;
			if (($pindex - 1) * $psize > $total)
				$pindex = 1;
			
			$list = pdo_fetchall ( "$sql limit " . (($pindex - 1) * $psize) . ",$psize", $params );
			$pager = pagination ( $total, $pindex, $psize );
			return false;
		} catch ( Exception $e ) {
			return $e->getMessage ();
		}
	}
	function mkFolder($path) {
		if (! is_readable ( $path )) {
			is_file ( $path ) or mkdir ( $path, 0700, true );
		}
	}
	
	/**
	 * 资源下载
	 */
	public function doMobileResource() {
		global $_W, $_GPC;
		$isweixin = $this->is_weixin ();
		$fileid = intval ( $_GPC ['id'] );
		$file = pdo_get ( "ss_wjgl_file", array (
				"id" => $fileid 
		) );
		include $this->template ( 'default/resource' );
	}
	
	/**
	 * 资源列表 
	 */
	public function doMobileIndex() {
		global $_W, $_GPC;
		$files = pdo_fetchall ("select * from ".tablename( "ss_wjgl_file"));
		include $this->template ( 'default/index' );
	}
	public function is_weixin() {
		if (empty ( $_SERVER ['HTTP_USER_AGENT'] ) || strpos ( $_SERVER ['HTTP_USER_AGENT'], 'MicroMessenger' ) === false && strpos ( $_SERVER ['HTTP_USER_AGENT'], 'Windows Phone' ) === false) {
			return false;
		}
		return true;
	}
}