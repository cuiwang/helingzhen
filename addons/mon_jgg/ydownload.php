<?php
if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');
global $_GPC, $_W;
$jid = intval($_GPC['jid']);
if (empty($jid)) {
    message('抱歉，传递的参数错误！', '', 'error');
}
        $where = '';
        $params = array(
            ':jid' => $jid
        );
        if ($_GPC['uid'] != '') {
            $where .= " and r.uid=:uid";
            $params[':uid'] = $_GPC['uid'];
        }


    $list = pdo_fetchall("SELECT r.*,u.nickname,u.tel,u.uname FROM " . tablename(CRUD::$table_jgg_sn) . "  r left join ".tablename(CRUD::$table_jgg_user)." u on r.uid=u.id WHERE r.jid =:jid  ".$where." ORDER BY  id ASC ",$params);


    $tableheader = array('id', '验证码', '使用者','使用情况');


$html = "\xEF\xBB\xBF";
foreach ($tableheader as $value) {
    $html .= $value . "\t ,";
}
$html .= "\n";
foreach ($list as $value) {
    $html .= $value['id'] . "\t ,";    $html .= $value['sn'] . "\t ,";
    $html .= $value['nickname'] . "\t ,";
    if(!empty($value['uid'])){
        $html .= "已使用\n ";

    }else{
        $html .= "未使用\n ";

    }
}


header("Content-type:text/csv");
header("Content-Disposition:attachment; filename=验证码记录数据.csv");

echo $html;
exit();
