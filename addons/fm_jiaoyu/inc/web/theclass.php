<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */

global $_GPC, $_W;
$weid              = $_W['uniacid'];
$action1           = 'theclass';
$this1             = 'no1';
$action            = 'semester';
$GLOBALS['frames'] = $this->getNaveMenu($_GPC['schoolid'], $action);
$schoolid          = intval($_GPC['schoolid']);
$logo              = pdo_fetch("SELECT logo,title FROM " . tablename($this->table_index) . " WHERE id = '{$schoolid}'");
$operation         = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$it                = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " WHERE sid = :sid", array(':sid' => $sid));
$xueqi             = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$weid}' AND schoolid ={$schoolid} And type = 'semester' ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'semester', ':schoolid' => $schoolid));
if($operation == 'display'){
    if(!empty($_GPC['ssort'])){
        foreach($_GPC['ssort'] as $sid => $ssort){
            pdo_update($this->table_classify, array('ssort' => $ssort), array('sid' => $sid));
        }
        $this->imessage('批量更新排序成功', referer(), 'success');
    }
    $children = array();

    $theclass = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " WHERE weid = '{$weid}' And type = 'theclass' And schoolid = {$schoolid} ORDER BY sid ASC, ssort DESC");
    foreach($theclass as $key => $row){
        $teacher                  = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " WHERE id = :id", array(':id' => $row['tid']));
        $xueqi                    = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " WHERE sid = :sid", array(':sid' => $row['parentid']));
        $renshu                   = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_students) . " WHERE schoolid = :schoolid And bj_id = :bj_id", array(':schoolid' => $schoolid, ':bj_id' => $row['sid']));
        $theclass[$key]['name']   = $teacher['tname'];
        $theclass[$key]['xueqi']  = $xueqi['sname'];
        $theclass[$key]['renshu'] = $renshu;
    }
}elseif($operation == 'post'){
    load()->func('tpl');
    $parentid = intval($_GPC['parentid']);
    $sid      = intval($_GPC['sid']);
    $allls    = pdo_fetchall("SELECT id,tname FROM " . tablename($this->table_teachers) . " WHERE schoolid = :schoolid", array(':schoolid' => $schoolid));
    if(!empty($sid)){
        $theclass = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " WHERE sid = '$sid'");
    }else{
        $theclass = array(
            'ssort' => 0,
        );
    }
    if(checksubmit('submit')){
        if(empty($_GPC['catename'])){
            $this->imessage('抱歉，请输入名称！', referer(), 'error');
        }

        $data = array(
            'weid'       => $weid,
            'schoolid'   => $_GPC['schoolid'],
            'sname'      => $_GPC['catename'],
            'ssort'      => intval($_GPC['ssort']),
            'type'       => 'theclass',
            'erwei'      => trim($_GPC['erwei']),
            'qun'        => trim($_GPC['qun']),
            'cost'       => trim($_GPC['cost']),
            'video'      => trim($_GPC['video']),
            'video1'     => trim($_GPC['video1']),
            'videostart' => trim($_GPC['videostart']),
            'videoend'   => trim($_GPC['videoend']),
            'allowpy'    => trim($_GPC['allowpy']),
            'tid'        => trim($_GPC['tid']),
            'parentid'   => intval($parentid),
        );
        if(!empty($sid)){
            pdo_update($this->table_classify, $data, array('sid' => $sid));
        }else{
            pdo_insert($this->table_classify, $data);
            $sid = pdo_insertid();
        }
        $this->imessage('更新班级成功！', referer(), 'success');
    }
}elseif($operation == 'delete'){
    $sid      = intval($_GPC['sid']);
    $theclass = pdo_fetch("SELECT sid FROM " . tablename($this->table_classify) . " WHERE sid = '$sid'");
    if(empty($theclass)){
        $this->imessage('抱歉，班级不存在或是已经被删除！', referer(), 'error');
    }
    pdo_delete($this->table_classify, array('sid' => $sid), 'OR');
    $this->imessage('班级删除成功！', referer(), 'success');
}
include $this->template('web/theclass');
?>