<?php
/**
 * 悟空源 码论 坛bbs . xte c.cc微站定义
 *
 * @author 微赞
 * @url http://bbs.012wz.com/thread-13060-1-1.html
 */
defined('IN_IA') or exit('Access Denied');
class table_superman_creditmall_kv extends SupermanTable {
	public function __construct() {
		$this->_table = 'superman_creditmall_kv';
	}
    public function fetch_value($skey, $params = null, $unserialize = true) {
        global $_W;
        $filter = array(
            'uniacid' => $_W['uniacid'],
            'skey' => SupermanUtil::get_skey($skey, $params),
        );
        $row = $this->fetch($filter);
        if ($row) {
            return $unserialize?iunserializer($row['svalue']):$row['svalue'];
        }
    }
}
