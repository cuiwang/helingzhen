<?php
$replyid = intval ( $_GPC ['replyid'] );
$reply = pdo_fetch ( "SELECT * FROM " . tablename ( $this->table_reply ) . " WHERE id = :replyid ORDER BY `id` DESC", array (
		':replyid' => $replyid
) );
if (empty ( $reply )) {
	message ( '活动不存在' );
} else {
	if ($reply ['status'] == 1) {
		pdo_update ( $this->table_reply, array (
				'status' => 2
		), array (
				'id' => $replyid
		) );
	} elseif ($reply ['status'] == 2) {
		pdo_update ( $this->table_reply, array (
				'status' => 1
		), array (
				'id' => $replyid
		) );
	} else {
		message('出错了');
	}
	header ( "location:" . $this->createWebUrl ( 'activity' ) );
}
?>