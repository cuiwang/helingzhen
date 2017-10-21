<?php
session_start();
/**
 * 查询模块微站定义
 *
 * @author 岳来越好
 * @url http://bbs.we7.cc/
 */
defined ( 'IN_IA' ) or exit ( 'Access Denied' );
class Water_queryModuleSite extends WeModuleSite {
	

	
	/**
	 * 访问首页
	 */
	public function doMobileIndex() {
		global $_GPC, $_W;

		include $this->template ( 'index' );
		
	}

	/**
	 * 查询查询结果
	 */
	public function doMobileQuery() {
		global $_GPC, $_W;
		$keyword = $_GPC ['code'];
		if(empty($keyword)){
			message('参数为空');
		}
		$info = pdo_fetch ( "SELECT * FROM " . tablename ( 'water_query_info' ) . " WHERE  uniacid = '" . $_W ['uniacid'] . "' and keyword= '" . $keyword."'" );
		if(empty($info)){
			$info['keyword'] = $keyword;
			$info['info'] = ' 对不起，没有查到该单号对应的信息，请核实单号后再查询。';
		}
		include $this->template ( 'result' );
	
	}
	

	

	
	
	public function dowebInfo() {
		global $_W, $_GPC;
		$pageNumber = max ( 1, intval ( $_GPC ['page'] ) );
		$pageSize = 10;
		$sql = "SELECT * FROM " . tablename ( 'water_query_info') . " WHERE uniacid = '" . $_W ['uniacid'] . "' ORDER BY id LIMIT " . ($pageNumber - 1) * $pageSize . ',' . $pageSize;
		$list = pdo_fetchall ( $sql );
		$total = pdo_fetchcolumn ( 'SELECT COUNT(*) FROM ' . tablename ( 'water_query_info' ) . " WHERE uniacid = '" . $_W ['uniacid'] . "' ORDER BY id DESC" );
		$pager = pagination ( $total, $pageNumber, $pageSize );
		include $this->template ( 'info' );
	}

	public function doWebAddInfo() {
		global $_W, $_GPC;
		load ()->func ( 'tpl' );
		$infoid = intval ( $_GPC ['infoid'] );
		if ($infoid) {
			$info = pdo_fetch ( "SELECT * FROM " . tablename ( 'water_query_info' ) . " WHERE id= " . $infoid );
		}
		if ($_GPC ['op'] == 'delete') {
			$infoid = intval ( $_GPC ['infoid'] );
			$info = pdo_fetch ( "SELECT id FROM " . tablename ( 'water_query_info' ) . " WHERE id = " . $infoid );
			if (empty ( $info )) {
				message ( '抱歉，信息不存在或是已经被删除！' );
			}
			pdo_delete ( 'water_query_info', array (
					'id' => $infoid 
			) );
			message ( '删除成功！', referer (), 'success' );
		}
		
		if (checksubmit ()) {
			$data = array (
					'keyword' => $_GPC ['keyword'],
					'info' => htmlspecialchars_decode ( $_GPC ['info'] ),
					'infophoto' => $_GPC ['infophoto'],
			);
			
			if (! empty ( $infoid )) {
				pdo_update ( 'water_query_info', $data, array (
						'id' => $infoid 
				) );
			} else {
				$data ['uniacid'] = $_W ['uniacid'];
				pdo_insert ( 'water_query_info', $data );
				$infoid = pdo_insertid ();
			}
			message ( '更新成功！', referer (), 'success' );
		}
		include $this->template ( 'addinfo' );
	}

}