<?php
if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');
global $_GPC, $_W;
$jid = intval($_GPC['jid']);
if (empty($jid)) {
    message('抱歉，传递的参数错误！', '', 'error');
}
$list = pdo_fetchall("SELECT * FROM " . tablename(CRUD::$table_jgg_user) ." WHERE jid =:jid  ORDER BY createtime DESC", array(':jid'=>$jid));
$header = array('openid'=>'openID','nickname'=>'昵称', 'tel'=>'手机号','uname'=>'用户名','createtime'=>'注册时间');
export2excel($header,$list,'参与用户数据');
exit();
