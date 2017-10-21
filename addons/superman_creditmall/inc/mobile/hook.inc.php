<?php
/**
 * 悟空源 码论 坛bbs . xte c.cc定义
 *
 * @author 微赞
 * @url
 */
defined('IN_IA') or exit('Access Denied');
class Creditmall_doMobileHook extends Superman {
	public function __construct() {
        parent::__construct(true);
	}
    public function exec() {
        global $_W;
        //竞拍商品自动化处理
        //$this->do_auction();
    }

    /*private function do_auction() {
        global $_W;
        $start = 0;
        $pagesize = 3;
        $sql = "SELECT DISTINCT(b.product_id),a.end_time FROM ".tablename('superman_creditmall_product')." AS a,";
        $sql .= tablename('superman_creditmall_product_log')." AS b";
        $sql .= " WHERE a.uniacid=:uniacid AND a.id=b.product_id AND b.isover=0";
        $sql .= " ORDER BY a.end_time ASC LIMIT {$start},{$pagesize}";
        $params = array(
            'uniacid' => $_W['uniacid'],
        );
        $list = pdo_fetchall($sql, $params);
        if ($list) {
            foreach ($list as $item) {

            }
        }
    }*/
}
$obj = new Creditmall_doMobileHook;
$obj->exec();