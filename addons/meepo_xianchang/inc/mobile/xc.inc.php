<?php
/**
 * MEEPO 米波现场
 *
 * 官网 http://meepo.com.cn 作者QQ 284099857
 */
include MODULE_ROOT.'/inc/mobile/pc_init.php';
$xcs = pdo_fetchall("SELECT img FROM ".tablename($this->xc2_table)." WHERE weid=:weid AND rid=:rid ORDER BY displayid ASC",array(':weid'=>$weid,':rid'=>$rid));
include $this->template('xc');