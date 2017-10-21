<?php
/**
 * 悟空源 码论 坛bbs . xte c.cc微站定义
 *
 * @author 微赞
 * @url http://bbs.012wz.com/thread-13060-1-1.html
 */
defined('IN_IA') or exit('Access Denied');
class table_core_paylog extends SupermanTable {
    public function __construct() {
        $this->_table = 'core_paylog';
        $this->_pk = 'plid';
    }
}