<?php
global $_GPC, $_W;
$id = intval($_GPC['id']);
$pici = intval($_GPC['pici']);
$rule = pdo_fetch("select * from " . tablename('haoman_dpm_pw') . " where id = :id ", array(':id' => $id));
$codenum = pdo_fetch("select * from " . tablename('haoman_dpm_pici') . " where pici = :pici ", array(':pici' => $pici));
if (empty($rule)) {
	message('抱歉，参数错误！');
}
pdo_delete('haoman_dpm_pw', array('id' => $id));
if($rule['pici']!=0){
	pdo_update('haoman_dpm_pici', array('codenum' => $codenum['codenum'] - 1), array('pici' => $codenum['pici']));

}
message('口令删除成功！', referer(), 'success');