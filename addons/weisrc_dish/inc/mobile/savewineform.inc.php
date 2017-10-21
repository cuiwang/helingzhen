<?php
global $_W, $_GPC;
$weid = $this->_weid;
$from_user = $this->_fromuser;
$storeid = intval($_GPC['storeid']);

if ($storeid == 0) {
    message('门店不存在！');
}

include $this->template($this->cur_tpl . '/savewine_form');