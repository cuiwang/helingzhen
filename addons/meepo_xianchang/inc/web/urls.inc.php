<?php
/**
 * MEEPO 米波现场
 *
 * 官网 http://meepo.com.cn 赞木 作者QQ 284099857
 */
global $_W,$_GPC;
$weid = $_W['uniacid'];
$id  = $rid = $_GPC['id'];
if(!empty($rid)){
	    $xianchang = pdo_fetch("SELECT * FROM ".tablename($this->xc_table)." WHERE weid = :weid AND rid = :rid",array(':weid'=>$weid,':rid'=>$rid));
		if(empty($xianchang)){
			message('规则不存在或是已经被删除！');
		}
}else{
	message('规则不存在或是已经被删除！');
}
$xianchang['controls'] = iunserializer($xianchang['controls']);
if(empty($xianchang['controls'] )){
	message('请先在规则中功能管理选择本次活动需要开启的活动功能！');
}
include $this->template('urls');