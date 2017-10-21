<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
global $_GPC, $_W;

$weid              = $_W['uniacid'];
$this1             = 'no3';
$action            = 'notice';
$GLOBALS['frames'] = $this->getNaveMenu($_GPC['schoolid'], $action);
$schoolid          = intval($_GPC['schoolid']);
$logo              = pdo_fetch("SELECT logo,title FROM " . tablename($this->table_index) . " WHERE id = '{$schoolid}'");
$bjlist            = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " WHERE weid = :weid AND schoolid = :schoolid And type = :type ORDER BY sid ASC, ssort DESC", array(
    ':weid'     => $weid,
    ':schoolid' => $schoolid,
    ':type'     => 'theclass'
));
$kmlist            = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " WHERE weid = :weid AND schoolid = :schoolid And type = :type ORDER BY sid ASC, ssort DESC", array(
    ':weid'     => $weid,
    ':schoolid' => $schoolid,
    ':type'     => 'subject'
));
$techerlist        = pdo_fetchall("SELECT * FROM " . tablename($this->table_teachers) . " WHERE weid = :weid AND schoolid = :schoolid ORDER BY id ASC", array(
    ':weid'     => $weid,
    ':schoolid' => $schoolid,
));

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if($operation == 'display'){
    $pindex    = max(1, intval($_GPC['page']));
    $psize     = 20;
    $condition = '';
    $params    = array();
    if(!empty($_GPC['keyword'])){
        $condition          .= " AND title LIKE :keyword";
        $params[':keyword'] = "%{$_GPC['keyword']}%";
    }
    if(!empty($_GPC['bj_id'])){
        $condition .= " AND bj_id = '{$_GPC['bj_id']}'";
    }

    $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_notice) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' And type = 1 $condition ORDER BY createtime DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
    foreach($list as $key => $row){
        $bj                   = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " WHERE sid = :sid", array(':sid' => $row['bj_id']));
        $list[$key]['bjname'] = $bj['sname'];
    }
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_notice) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' And type = 1 $condition");
    $pager = pagination($total, $pindex, $psize);
}elseif($operation == 'display1'){
    $pindex    = max(1, intval($_GPC['page']));
    $psize     = 20;
    $condition = '';
    $params    = array();
    if(!empty($_GPC['keyword'])){
        $condition          .= " AND title LIKE :keyword";
        $params[':keyword'] = "%{$_GPC['keyword']}%";
    }
    if(!empty($_GPC['group'])){
        $condition .= " AND groupid = '{$_GPC['group']}'";
    }

    $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_notice) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' And type = 2 $condition ORDER BY createtime DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);

    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_notice) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' And type = 2 $condition ");
    $pager = pagination($total, $pindex, $psize);
}elseif($operation == 'display2'){
    $pindex    = max(1, intval($_GPC['page']));
    $psize     = 20;
    $condition = '';
    $params    = array();
    if(!empty($_GPC['keyword'])){
        $condition          .= " AND title LIKE :keyword";
        $params[':keyword'] = "%{$_GPC['keyword']}%";
    }
    if(!empty($_GPC['bj_id'])){
        $condition .= " AND bj_id = '{$_GPC['bj_id']}'";
    }
    if(!empty($_GPC['km_id'])){
        $condition .= " AND km_id = '{$_GPC['km_id']}'";
    }

    $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_notice) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' And type = 3 $condition ORDER BY createtime DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
    foreach($list as $key => $row){
        $bj                   = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " WHERE sid = :sid", array(':sid' => $row['bj_id']));
        $km                   = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " WHERE sid = :sid", array(':sid' => $row['km_id']));
        $list[$key]['bjname'] = $bj['sname'];
        $list[$key]['kmname'] = $km['sname'];
    }
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_leave) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' And type = 3 $condition");
    $pager = pagination($total, $pindex, $psize);
}elseif($operation == 'display3'){
    $pindex    = max(1, intval($_GPC['page']));
    $psize     = 20;
    $condition = '';
    $params    = array();
    if(!empty($_GPC['keyword'])){
        $condition          .= " AND title LIKE :keyword";
        $params[':keyword'] = "%{$_GPC['keyword']}%";
    }
    if(!empty($_GPC['bj_id'])){
        $condition .= " AND bj_id = '{$_GPC['bj_id']}'";
    }
    if(!empty($_GPC['fenlei'])){
        if($_GPC['fenlei'] == 1){
            $condition .= " AND sid = 0 ";
        }
        if($_GPC['fenlei'] == 2){
            $condition .= " AND tid = 0 ";
        }
    }
    if(!empty($_GPC['leixing'])){
        $condition .= " AND type = '{$_GPC['leixing']}'";
    }

    $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_leave) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' And isliuyan = 0 $condition ORDER BY createtime DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);

    foreach($list as $key => $row){
        $member               = pdo_fetch("SELECT * FROM " . tablename('mc_members') . " where uniacid = :uniacid And uid = :uid ", array(':uniacid' => $_W ['uniacid'], ':uid' => $row['uid']));
        $student              = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where weid = :weid And id = :id ", array(':weid' => $_W ['uniacid'], ':id' => $row['sid']));
        $teacher              = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where weid = :weid And id = :id ", array(':weid' => $_W ['uniacid'], ':id' => $row['tid']));
        $user                 = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where weid = :weid And schoolid = :schoolid And sid = :sid And uid = :uid ORDER BY id DESC", array(':weid' => $_W ['uniacid'], ':schoolid' => $row['schoolid'], ':schoolid' => $schoolid, ':sid' => $row['sid'], ':uid' => $row['uid']));
        $bj                   = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " WHERE sid = :sid", array(':sid' => $row['bj_id']));
        $list[$key]['bjname'] = $bj['sname'];
        $list[$key]['avatar'] = $member['avatar'];
        $list[$key]['s_name'] = $student['s_name'];
        $list[$key]['tname']  = $teacher['tname'];
        $list[$key]['guanxi'] = $user['pard'];

    }
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_leave) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' And isliuyan = 0 $condition ");
    $pager = pagination($total, $pindex, $psize);
}elseif($operation == 'display4'){
    $pindex    = max(1, intval($_GPC['page']));
    $psize     = 8;
    $condition = '';
    mload()->model('user');
    $leave = pdo_fetchall("SELECT * FROM " . tablename($this->table_leave) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' And isliuyan = 2 And isfrist = 1 $condition ORDER BY createtime DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
    foreach($leave as $index => $row){
        $user                   = pdo_fetch("SELECT pard,sid,tid,userinfo FROM " . tablename($this->table_user) . " where weid = :weid AND id = :id", array(':weid' => $weid, ':id' => $row['userid']));
        $touser                 = pdo_fetch("SELECT pard,sid,tid,userinfo FROM " . tablename($this->table_user) . " where weid = :weid AND id = :id", array(':weid' => $weid, ':id' => $row['touserid']));
        $students               = pdo_fetch("SELECT s_name FROM " . tablename($this->table_students) . " where weid = :weid AND id = :id", array(':weid' => $weid, ':id' => $user['sid']));
        $teacher                = pdo_fetch("SELECT tname FROM " . tablename($this->table_teachers) . " where weid = :weid AND id = :id", array(':weid' => $weid, ':id' => $user['tid']));
        $students1              = pdo_fetch("SELECT s_name FROM " . tablename($this->table_students) . " where weid = :weid AND id = :id", array(':weid' => $weid, ':id' => $touser['sid']));
        $teacher1               = pdo_fetch("SELECT tname FROM " . tablename($this->table_teachers) . " where weid = :weid AND id = :id", array(':weid' => $weid, ':id' => $touser['tid']));
        $leave[$index]['huifu'] = pdo_fetchall("SELECT * FROM " . tablename($this->table_leave) . " where weid = :weid AND leaveid = :leaveid ORDER BY createtime DESC LIMIT 0,7", array(':weid' => $weid, ':leaveid' => $row['id']));
        foreach($leave[$index]['huifu'] as $k => $v){

            $leave[$index]['huifu'][$k]['sj']        = sub_day($v['createtime']);
            $leave[$index]['huifu'][$k]['lastconet'] = $v['conet'];
            $leave[$index]['huifu'][$k]['myid']      = $v['userid'];
            $leave[$index]['huifu'][$k]['mytoid']    = $v['touserid'];
            if($v['userid'] == $it['id']){
                $users = pdo_fetch("SELECT pard,sid,tid,userinfo FROM " . tablename($this->table_user) . " where weid = :weid AND id = :id", array(':weid' => $weid, ':id' => $v['touserid']));
            }
            if($v['touserid'] == $it['id']){
                $users = pdo_fetch("SELECT pard,sid,tid,userinfo FROM " . tablename($this->table_user) . " where weid = :weid AND id = :id", array(':weid' => $weid, ':id' => $v['userid']));
            }
            if($users['sid']){
                $leave[$index]['huifu'][$k]['sf'] = 1;
            }
            if($users['tid']){
                $leave[$index]['huifu'][$k]['sf'] = 2;
            }
        }
        $leave[$index]['tname']   = !empty($teacher['tname']) ? "老师" . $teacher['tname'] : '';
        $leave[$index]['s_name']  = !empty($students['s_name']) ? "学生" . $students['s_name'] : '';
        $leave[$index]['tname1']  = !empty($teacher1['tname']) ? "老师" . $teacher1['tname'] : '';
        $leave[$index]['s_name1'] = !empty($students1['s_name']) ? "学生" . $students1['s_name'] : '';
        $leave[$index]['pard']    = $user['pard'];
    }

    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_leave) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' And isliuyan = 2 And isfrist = 1 $condition ");
    $pager = pagination($total, $pindex, $psize);
}elseif($operation == 'display5'){
    $pindex    = max(1, intval($_GPC['page']));
    $notice_id = intval($_GPC['notice_id']);
    $notice    = pdo_fetch("SELECT title FROM " . tablename($this->table_notice) . " where id = :id ", array(':id' => $notice_id));
    $psize     = 100;
    $condition = '';
    if($_GPC['shenfen'] == 1){
        $shenfen   = intval($_GPC['shenfen']);
        $condition .= " AND sid = '0' ";
    }
    if($_GPC['shenfen'] == 2){
        $shenfen   = intval($_GPC['shenfen']);
        $condition .= " AND tid = '0' ";
    }

    $is_pay = isset($_GPC['is_pay']) ? intval($_GPC['is_pay']) : -1;
    if($is_pay >= 0){
        if($is_pay == 2){
            $nowtime   = TIMESTAMP;
            $condition .= " AND readtime > '0' AND readtime < '{$nowtime}'";
        }else if($is_pay == 1){
            $condition         .= " AND readtime = '0' ";
            $params[':is_pay'] = $is_pay;
        }
    }

    $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_record) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' And noticeid = '{$notice_id}' $condition ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);

    foreach($list as $key => $row){
        $student              = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where id = :id ", array(':id' => $row['sid']));
        $notice               = pdo_fetch("SELECT * FROM " . tablename($this->table_notice) . " where id = :id ", array(':id' => $row['noticeid']));
        $user                 = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where id = :id", array(':id' => $row ['userid']));
        $teacher              = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where id = :id ", array(':id' => $row['tid']));
        $list[$key]['title']  = $notice['title'];
        $list[$key]['s_name'] = $student['s_name'];
        $list[$key]['guanxi'] = $user['pard'];
        $list[$key]['tname']  = $teacher['tname'];
    }
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_record) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' And noticeid = '{$notice_id}' $condition ");
    $pager = pagination($total, $pindex, $psize);
}elseif($operation == 'posta'){

    load()->func('tpl');
    $leaveid = intval($_GPC['leaveid']);

    if(!empty($leaveid)){
        $pindex  = max(1, intval($_GPC['page']));
        $psize   = 20;
        $item    = pdo_fetch("SELECT * FROM " . tablename($this->table_leave) . " WHERE id = '{$leaveid}'");
        $user    = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where weid = :weid And schoolid = :schoolid And sid = :sid And uid = :uid ", array(':weid' => $_W ['uniacid'], ':schoolid' => $row['schoolid'], ':schoolid' => $schoolid, ':sid' => $item['sid'], ':uid' => $item['uid']));
        $student = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where id = :id ", array(':id' => $item['sid']));
        $bj      = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " WHERE sid = :sid ", array(':sid' => $item['bj_id']));
        $teacher = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where id = :id ", array(':id' => $bj['tid']));
        $list    = pdo_fetchall("SELECT * FROM " . tablename($this->table_leave) . " where schoolid = '{$schoolid}' And weid = '{$weid}' And isliuyan = 1 And leaveid = '{$leaveid}' ORDER BY createtime DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
        $total   = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_leave) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' And leaveid = '{$leaveid}' ");
        $pager   = pagination($total, $pindex, $psize);
        if(empty($item)){
            message('抱歉，本信息不存在在或是已经删除！', '', 'error');
        }
    }
}elseif($operation == 'postb'){

    load()->func('tpl');
    $id = intval($_GPC['id']);

    if(!empty($id)){

        $item   = pdo_fetch("SELECT * FROM " . tablename($this->table_notice) . " WHERE id = '{$id}'");
        $bj     = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " WHERE sid = :sid ", array(':sid' => $item['bj_id']));
        $picarr = iunserializer($item['picarr']);
        //print_r($picarr);
        //$p = array();
        //foreach($picarr as $key => $row){
        //print_r($key);
        //$pic = tomedia($row);
        //$picarr[$key]['p'] = '<img src="'.$pic.'" alt="image" style="width:100%;">';
        //}
        if(empty($item)){
            message('抱歉，本信息不存在在或是已经删除！', '', 'error');
        }
    }
}elseif($operation == 'post'){

    load()->func('tpl');
    if(checksubmit('submit')){
        if(empty($_GPC['title'])){
            message('请输入标题！');
        }

        if(empty($_GPC['tid'])){
            message('请输入教师姓名！');
        }

        if($_GPC['type'] == 1){
            if(empty($_GPC['bj_id'])){
                message('请选择发送班级！');
            }
        }

        if($_GPC['type'] == 2){
            if(empty($_GPC['groupid'])){
                message('请选择接收对象！');
            }
        }

        if($_GPC['type'] == 3){
            if(empty($_GPC['bj_id'])){
                message('请选择发送班级！');
            }
            if(empty($_GPC['km_id'])){
                message('请选择接该作业所属科目！');
            }
        }
        $teacher = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " WHERE id = '{$_GPC['tid']}'");
        $setting = pdo_fetch("SELECT * FROM " . tablename($this->table_set) . " WHERE :weid = weid", array(':weid' => $weid));
        if(!empty($_GPC['bj_id'])){
            $bj_id = intval($_GPC['bj_id']);
        }else{
            $bj_id = 0;
        }

        if(!empty($_GPC['km_id'])){
            $km_id = intval($_GPC['km_id']);
        }else{
            $km_id = 0;
        }

        if(!empty($_GPC['groupid'])){
            $groupid = intval($_GPC['groupid']);
        }else{
            $groupid = 0;
        }

        $temp = array(
            'weid'       => $weid,
            'schoolid'   => $schoolid,
            'tid'        => $_GPC['tid'],
            'tname'      => $teacher['tname'],
            'title'      => $_GPC['title'],
            'content'    => $_GPC['content'],
            'outurl'     => $_GPC['outurl'],
            'createtime' => time(),
            'bj_id'      => $bj_id,
            'km_id'      => $km_id,
            'type'       => $_GPC['type'],
            'groupid'    => $groupid,
            'ismobile'   => 1,
        );
        if($setting['istplnotice'] == 1){
            pdo_insert($this->table_notice, $temp);
            $notice_id = pdo_insertid();
            if($_GPC['type'] == 1){
                message('开始群发班级通知,请勿执行任何操作！', $this->createWebUrl('BjtzMsg', array('notice_id' => $notice_id, 'schoolid' => $schoolid, 'weid' => $weid, 'tname' => $teacher['tname'], 'bj_id' => $bj_id)), 'success');
            }
            if($_GPC['type'] == 2){
                message('开始群发校园通知,请勿执行任何操作！！', $this->createWebUrl('XytzMsg', array('notice_id' => $notice_id, 'schoolid' => $schoolid, 'weid' => $weid, 'tname' => $teacher['tname'], 'groupid' => $groupid)), 'success');
            }
            if($_GPC['type'] == 3){
                message('开始群发班级作业,请勿执行任何操作！', $this->createWebUrl('ZuoyeMsg', array('notice_id' => $notice_id, 'schoolid' => $schoolid, 'weid' => $weid, 'tname' => $teacher['tname'], 'bj_id' => $bj_id)), 'success');
            }
        }else{
            message('发送失败,请联系管理员开启模板消息！', 'error');
        }
    }

}elseif($operation == 'delete1'){
    $id   = intval($_GPC['id']);
    $item = pdo_fetch("SELECT id FROM " . tablename($this->table_notice) . " WHERE id = '$id'");
    if(empty($item)){
        message('抱歉，不存在或是已经被删除！', 'error');
    }
    pdo_delete($this->table_notice, array('id' => $id));
    pdo_delete($this->table_record, array('noticeid' => $id));
    message('删除成功！', referer(), 'success');
}elseif($operation == 'delete2'){
    $id   = intval($_GPC['id']);
    $item = pdo_fetch("SELECT * FROM " . tablename($this->table_leave) . " WHERE id = '$id'");
    if(empty($item)){
        message('抱歉，不存在或是已经被删除！', $this->createWebUrl('notice', array('op' => 'display', 'schoolid' => $schoolid)), 'success');
    }
    if($item['isfrist'] == 1){
        message('抱歉，您不能单独删除学生发起的首句对话，如需删除，请返回列表删除本学生的全部对话！', referer());
    }
    pdo_delete($this->table_leave, array('id' => $id));

    message('删除成功！', referer(), 'success');
}elseif($operation == 'shenhe'){
    $id      = intval($_GPC['id']);
    $status  = intval($_GPC['status']);
    $fenlei  = intval($_GPC['fenlei']);
    $leave   = pdo_fetch("SELECT * FROM " . tablename($this->table_leave) . " WHERE id = :id ", array(':id' => $id));
    $bj      = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " WHERE sid = :sid ", array(':sid' => $leave['bj_id']));
    $teacher = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where id = :id ", array(':id' => $bj['tid']));

    $temp = array(
        'cltime' => time(),
        'status' => $status
    );
    pdo_update($this->table_leave, $temp, array('id' => $id));
    if($fenlei == 1){
        $this->sendMobileJsqjsh($id, $schoolid, $weid);
    }else{
        $this->sendMobileXsqjsh($id, $schoolid, $weid, $teacher['tname']);
    }
    message('操作成功！', referer(), 'success');
}elseif($operation == 'deleteallrecord'){
    $rowcount    = 0;
    $notrowcount = 0;
    foreach($_GPC['idArr'] as $k => $id){
        $id = intval($id);
        if(!empty($id)){
            $item = pdo_fetch("SELECT * FROM " . tablename($this->table_record) . " WHERE id = :id", array(':id' => $id));
            if(empty($item)){
                $notrowcount++;
                continue;
            }
            pdo_delete($this->table_record, array('id' => $id));
            $rowcount++;
        }
    }
    $message = "操作成功！共删除{$rowcount}条数据,{$notrowcount}条数据不能删除!";

    $data ['result'] = true;

    $data ['msg'] = $message;

    die (json_encode($data));
}elseif($operation == 'deleteall'){
    $id   = intval($_GPC['id']);
    $item = pdo_fetch("SELECT id FROM " . tablename($this->table_leave) . " WHERE id = '$id'");
    if(empty($item)){
        message('抱歉，不存在或是已经被删除！', 'error');
    }
    pdo_delete($this->table_leave, array('leaveid' => $id));

    message('删除成功！', referer(), 'success');
}elseif($operation == 'clear'){

    pdo_delete($this->table_record, array('userid' => 0, 'sid' => 0, 'openid' => '', 'userid' => 0));

    $this->imessage('已经全部清理！', referer(), 'success');
}
include $this->template('web/notice');
?>