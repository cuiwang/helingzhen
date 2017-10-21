<?php
/**
 * 用户洗车卡明细
 * ============================================================================
 * 版权所有 2015-2016 米粒源码，并保留所有权利。
 * 网站地址: http://www.webmili.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！已购买用户允许对程序代码进行修改和使用，但是不允许对
 * 程序代码以任何形式任何目的的再发布，作者将保留追究法律责任的权力和最终解
 * 释权。
 * ============================================================================
 */
  
$weid = $this->_weid;
$from_user = $_W['fans']['from_user'];
$uid = $_W['fans']['uid'];
$this->_fromuser = $from_user;
$title = '洗车卡明细';

$pindex = max(1, intval($_GPC['page']));
$psize = 20;

$recordlist = pdo_fetchall("SELECT * FROM " . tablename($this->table_onecard_record) . " WHERE weid='{$weid}' AND openid='{$from_user}' ORDER BY add_time DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);

$total = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename($this->table_onecard_record) . " WHERE weid='{$weid}' AND openid='{$from_user}'");

$pager = $this->mpagination($total, $pindex, $psize);

include $this->template('onecardrecord');