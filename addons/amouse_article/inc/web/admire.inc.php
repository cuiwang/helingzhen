<?php
/**
 * Created by IntelliJ IDEA.
 * User: shizhongying
 * Date: 8/29/15
 * Time: 10:31
 */
global $_W, $_GPC;
$weid=$_W['uniacid'];
$op= $_GPC['op'] ? $_GPC['op'] : 'alist';
$articleid = $_GPC['articleid'];
if($op == 'alist') {
    $pindex= max(1, intval($_GPC['page']));
    $psize= 20; //每页显示
    $condition = "WHERE weid = $weid  AND aid = $articleid ";
    if($articleid){
        $articleid.=" and aid=$articleid ";
        $item = pdo_fetch("SELECT * FROM ".tablename('fineness_article')." WHERE id = :id" , array(':id' => $articleid));
        $price= pdo_fetchcolumn('SELECT sum(price) FROM '.tablename('fineness_admire')." WHERE weid = $weid and aid=$articleid");
    }

    $list= pdo_fetchall('SELECT * FROM '.tablename('fineness_admire')." $condition LIMIT ".($pindex -1) * $psize.','.$psize);
    $total= pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('fineness_admire').$condition);
    $pager= pagination($total, $pindex, $psize);



}elseif($op=='view'){
    $articleid= intval($_GPC['articleid']);
    $id= intval($_GPC['id']);
    $art = pdo_fetch("SELECT * FROM ".tablename('fineness_article')." WHERE id = :id" , array(':id' => $articleid));
    $item = pdo_fetch("SELECT * FROM ".tablename('fineness_admire')." WHERE id = :id" , array(':id' => $id));

}elseif($op == 'delete') { //删除
    if(isset($_GPC['delete'])) {
        $ids= implode(",", $_GPC['delete']);
        $sqls= "delete from  ".tablename('fineness_admire')."  where id in(".$ids.")";
        pdo_query($sqls);
        message('删除成功！', referer(), 'success');
    }
    $id= intval($_GPC['id']);
    $temp= pdo_delete("fineness_admire", array('id' => $id));
    message('删除数据成功！', $this->createWebUrl('comment', array('op' => 'list','articleid'=>$articleid)), 'success');
}
include $this->template('admire');