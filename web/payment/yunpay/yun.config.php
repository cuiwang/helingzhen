<?php
 //↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
require '../../../../framework/bootstrap.inc.php';
 $yunpay = pdo_fetch("SELECT * FROM ".tablename('buymod_payset')." where weid=:weid",array(':weid' => 0));
//合作身份者id
$yun_config['partner']		= $yunpay['yunpid'];
//安全检验码
$yun_config['key']			= $yunpay['yunkey'];
//云会员账户（邮箱）
$seller_email = $yunpay['yunaccount'];

//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑

?>