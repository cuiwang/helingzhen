<?php
/**
 * 集阅读模块
 *
 * @author Toddy
 */
defined ( 'IN_IA' ) or exit ( 'Access Denied' );
class Jy_readsModuleProcessor extends WeModuleProcessor {
	public $table_reply = 'jy_reads_reply';
	public function respond() {
		global $_W;
		$sql = "SELECT * FROM " . tablename ( $this->table_reply ) . " WHERE rid = :rid ";
		$reply = pdo_fetch ( $sql, array (
				':rid' => $this->rule 
		) );
		if ($reply) {
			if ($reply ['starttime'] > time ()) {
				return $this->respText ( "活动尚未开始,敬请期待！" );
			} elseif ($reply ['endtime'] < time () || $reply ['endtime'] == 0) {
				return $this->respText ( "活动已经结束，请关注我们后续的活动！" );
			} elseif ($reply ['status'] == 2) {
				return $this->respText ( "活动暂停中" );
			} else {
				$news = array ();
				$news [] = array (
						'title' => $reply ['share_title'],
						'description' => $reply ['share_description'],
						'picurl' => tomedia ( $reply ['share_thumb'] ),
						'url' => $_W ['siteroot'] .'app/'. substr ( $this->createMobileUrl ( 'index', array (
								'id' => $reply ['id'],
								'issub' => 1
						) ), 2 ) 
				);
				return $this->respNews ( $news );
			}
		} else {
			return $this->respText ( '网络繁忙' );
		}
	}
}