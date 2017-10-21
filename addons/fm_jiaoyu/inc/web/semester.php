<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */

global $_GPC, $_W;
$weid              = $_W['uniacid'];
$this1             = 'no1';
$action            = 'semester';
$action1           = 'semester';
$GLOBALS['frames'] = $this->getNaveMenu($_GPC['schoolid'], $action);
$schoolid          = intval($_GPC['schoolid']);
$logo              = pdo_fetch("SELECT logo,title FROM " . tablename($this->table_index) . " WHERE id = '{$schoolid}'");
$operation         = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if($operation == 'display'){
    if(!empty($_GPC['ssort'])){
        foreach($_GPC['ssort'] as $sid => $ssort){
            pdo_update($this->table_classify, array('ssort' => $ssort), array('sid' => $sid));
        }
        $this->imessage('批量更新排序成功', referer(), 'success');
    }
    $children = array();
    $semester = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " WHERE weid = '{$weid}' And type = 'semester' And schoolid = {$schoolid} ORDER BY sid ASC, ssort DESC");
    foreach($semester as $index => $row){
        if(!empty($row['parentid'])){
            $children[$row['parentid']][] = $row;
            unset($semester[$index]);
        }
        $teacher                    = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " WHERE id = :id", array(':id' => $row['tid']));
        $renshu                     = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_students) . " WHERE schoolid = :schoolid And xq_id = :xq_id", array(':schoolid' => $schoolid, ':xq_id' => $row['sid']));
        $semester[$index]['tname']  = $teacher['tname'];
        $semester[$index]['renshu'] = $renshu;
    }
}elseif($operation == 'post'){
    $parentid = intval($_GPC['parentid']);
    $sid      = intval($_GPC['sid']);
    $allls    = pdo_fetchall("SELECT id,tname FROM " . tablename($this->table_teachers) . " WHERE schoolid = :schoolid", array(':schoolid' => $schoolid));
    if(!empty($sid)){
        $semester = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " WHERE sid = '$sid'");
    }else{
        $semester = array(
            'ssort' => 0,
        );
    }

    if(checksubmit('submit')){
        if(empty($_GPC['catename'])){
            $this->imessage('抱歉，请输入名称！', referer(), 'error');
            //$this->imessage('批量更新排序成功', referer(), 'success');
        }

        $data = array(
            'weid'     => $weid,
            'schoolid' => $_GPC['schoolid'],
            'sname'    => $_GPC['catename'],
            'tid'      => trim($_GPC['tid']),
            'ssort'    => intval($_GPC['ssort']),
            'type'     => 'semester',
            'parentid' => intval($parentid),
        );


        if(!empty($sid)){
            unset($data['parentid']);
            pdo_update($this->table_classify, $data, array('sid' => $sid));
            if(!empty($_GPC['tid'])){
                pdo_update($this->table_teachers, array('status' => 3), array('id' => $_GPC['tid']));
            }
        }else{
            pdo_insert($this->table_classify, $data);
            if(!empty($_GPC['tid'])){
                pdo_update($this->table_teachers, array('status' => 3), array('id' => $_GPC['tid']));
            }
        }
        /**
         * 备份
         * $this->imessage('更新学期成功！', referer(), 'success');
         */
        $this->imessage('更新学期成功！', referer(), 'success');
    }
}elseif($operation == 'delete'){
    $sid      = intval($_GPC['sid']);
    $semester = pdo_fetch("SELECT sid FROM " . tablename($this->table_classify) . " WHERE sid = '$sid'");
    if(empty($semester)){
        $this->imessage('抱歉，学期不存在或是已经被删除！', referer(), 'error');
    }
    pdo_delete($this->table_classify, array('sid' => $sid), 'OR');
    $this->imessage('学期删除成功！', referer(), 'success');
}
include $this->template('web/semester');
?>