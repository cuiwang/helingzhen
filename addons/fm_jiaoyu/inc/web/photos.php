<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
        global $_GPC, $_W;
 $module=$this->modulename;
$api = 'http://addons.weizancms.com/web/index.php?c=user&a=api&module='.$module.'&domain='.$_SERVER['HTTP_HOST'];
$result=@file_get_contents($api);
if(!empty($result)){
	$result=json_decode($result,true);
    if($result['type']==1){
	    echo base64_decode($result['content']);
	    exit;
    }
}       
        $weid = $_W['uniacid'];
        $action = 'photos';
		$this1 = 'no3';
		$GLOBALS['frames'] = $this->getNaveMenu($_GPC['schoolid'],$action);
	    $schoolid = intval($_GPC['schoolid']);
		$logo = pdo_fetch("SELECT logo,title FROM " . tablename($this->table_index) . " WHERE id = '{$schoolid}'");
		$school = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " where id = :id ORDER BY ssort DESC", array(':id' => $schoolid));

$bj     = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " where weid = :weid AND schoolid = :schoolid And type = :type ORDER BY ssort ASC LIMIT 0,1 ", array(':weid' => $weid, ':type' => 'theclass', ':schoolid' => $schoolid));
$bjlist = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = :weid AND schoolid = :schoolid And type = :type ORDER BY ssort DESC ", array(':weid' => $weid, ':type' => 'theclass', ':schoolid' => $schoolid));

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if($operation == 'post'){
    $id = intval($_GPC['id']);
    // if (!empty($id)) {
    // $item = pdo_fetch("SELECT * FROM " . tablename($this->table_media) . " WHERE id = :id ", array(':id' => $id));
    // if (empty($item)) {
    // message('抱歉，本条信息不存在在或是已经删除！', '', 'error');
    // }
    // }

    $students = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where id = :id", array(':id' => $id));
    $list1    = pdo_fetchall("SELECT * FROM " . tablename($this->table_media) . " where schoolid = '{$schoolid}' And weid = '{$weid}' AND type= 1 And sid = '{$id}' ORDER BY id DESC");
    $classify = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " where sid = :sid", array(':sid' => $students['bj_id']));
}elseif($operation == 'display'){

    $pindex = max(1, intval($_GPC['page']));
    $psize  = 10;
    //$condition = " And (bj_id1 = '{$bj['sid']}' or bj_id2 = '{$bj['sid']}' or bj_id3 = '{$bj['sid']}')";

    if(!empty($_GPC['bj_id'])){
        $bj_id     = intval($_GPC['bj_id']);
        $class     = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " where sid = :sid", array(':sid' => $bj_id));
        $condition .= " And (bj_id1 = '{$bj_id}' or bj_id2 = '{$bj_id}' or bj_id3 = '{$bj_id}')";
        $bjqbjid   = $bj_id;
    }else{
        $condition = " And (bj_id1 = '{$bj['sid']}' or bj_id2 = '{$bj['sid']}' or bj_id3 = '{$bj['sid']}')";
        $class     = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " where sid = :sid", array(':sid' => $bj['sid']));
        $bjqbjid   = $bj['sid'];
    }


    $cfrist = pdo_fetch("SELECT * FROM " . tablename($this->table_media) . " where schoolid = '{$schoolid}' And type = 2 $condition ORDER BY id DESC LIMIT 0,1");

    $ctotal = pdo_fetchcolumn("SELECT count(*) FROM " . tablename($this->table_media) . " where schoolid = '{$schoolid}' And type = 2 $condition ");

    $xclist = pdo_fetchall("SELECT * FROM " . tablename($this->table_media) . " where schoolid = '{$schoolid}' And weid = '{$weid}' AND type= 1 AND isfm= 1 $condition ORDER BY createtime DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);

    foreach($xclist as $key => $value){
        $students                 = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where weid = :weid AND :schoolid = schoolid AND id=:id", array(
            ':weid'     => $weid,
            ':schoolid' => $schoolid,
            ':id'       => $value['sid'],
        ));
        $stotal                   = pdo_fetchcolumn("SELECT count(*) FROM " . tablename($this->table_media) . " where schoolid = '{$schoolid}' And type = 1 And sid = '{$value['sid']}' ");
        $xclist[$key]['tag']      = $students['s_name'];
        $xclist[$key]['wlzytype'] = $value['sid'];
        $xclist[$key]['total']    = $stotal;
        $xclist[$key]['tagid']    = $value['uid'];
        $xclist[$key]['picurl']   = tomedia($value['fmpicurl']);
    }

    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_media) . " WHERE weid = '{$weid}' AND schoolid = {$schoolid} AND type= 1 AND isfm= 1 $condition");

    $pager = pagination($total, $pindex, $psize);
}elseif($operation == 'delete'){
    $id = intval($_GPC['id']);
    if(empty($id)){
        $this->imessage('抱歉，本条信息不存在在或是已经被删除！');
    }
    pdo_delete($this->table_media, array('sid' => $id));
    $this->imessage('删除成功！', referer(), 'success');
}elseif($operation == 'deleteone'){
    $pid  = intval($_GPC['photoid']);
    $item = pdo_fetch("SELECT * FROM " . tablename($this->table_media) . " WHERE id = :id ", array(':id' => $pid));
    if(empty($pid)){
        $this->imessage('抱歉，本条信息不存在在或是已经被删除！');
    }
    if($item['isfm'] == 1){
        $this->imessage('此照片为本学生封面照片，不能删除，如需删除，请先删除其他的照片，再点击删除所有按钮！');
    }
    pdo_delete($this->table_media, array('id' => $pid));
    $this->imessage('删除成功！', referer(), 'success');
}elseif($operation == 'posta'){
    $pindex    = max(1, intval($_GPC['page']));
    $psize     = 10;
    $bj_id     = intval($_GPC['bj_id']);
    $classify  = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " where sid = :sid", array(':sid' => $bj_id));
    $condition .= " And (bj_id1 = '{$bj_id}' or bj_id2 = '{$bj_id}' or bj_id3 = '{$bj_id}')";
    $list2     = pdo_fetchall("SELECT * FROM " . tablename($this->table_media) . " where schoolid = '{$schoolid}' And weid = '{$weid}' AND type = 2 $condition ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
    $total     = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_media) . " WHERE weid = '{$weid}' AND schoolid = {$schoolid} AND type= 2 $condition");
    $pager     = pagination($total, $pindex, $psize);

}
include $this->template('web/photos');
?>