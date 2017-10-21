<?php

/**
 * 微教育模块
 *
 * @author 高贵血迹
 */

global $_GPC, $_W;
$weid              = $_W['uniacid'];
$action1           = 'permiss';
$this1             = 'no1';
$action            = 'semester';
$GLOBALS['frames'] = $this->getNaveMenu($_GPC['schoolid'], $action);
$schoolid          = intval($_GPC['schoolid']);
$logo              = pdo_fetch("SELECT logo,title FROM " . tablename($this->table_index) . " WHERE id = '{$schoolid}'");

$uniacid = intval($_W['uniacid']);

$state = uni_permission($_W['uid'], $uniacid);
if($state != 'founder' && $state != 'manager'){
    // 暂时先放着
    message('没有该公众号操作权限！', url('accound/display'), 'error');
}
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if($operation == 'display'){
    $_W['page']['title'] = '账号操作员列表';
    $account             = pdo_fetch("SELECT * FROM " . tablename('uni_account') . " WHERE uniacid = :uniacid", array(':uniacid' => $uniacid));
    if(empty($account)){
        $this->imessage('抱歉，您操作的公众号不存在或是已经被删除！');
    }
    $permission = pdo_fetchall("SELECT id, uid, role FROM " . tablename('uni_account_users') . " WHERE uniacid = '$uniacid' and role != :role  ORDER BY uid ASC, role DESC", array(':role' => 'clerk'), 'uid');
    if(!empty($permission)){
        $member = pdo_fetchall("SELECT username, uid, tid FROM " . tablename('users') . " WHERE schoolid = '$schoolid' And uid IN (" . implode(',', array_keys($permission)) . ")");
        foreach($member as $key => $r){
            $teacher                = pdo_fetch("SELECT fz_id,tname FROM " . tablename($this->table_teachers) . " WHERE weid = '{$weid}' And id = '{$r['tid']}' And schoolid = {$schoolid} ");
            $thisfz                 = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE weid = '{$weid}' And sid = '{$teacher['fz_id']}' And schoolid = {$schoolid} ");
            $member[$key]['fzname'] = $thisfz['sname'];
            $member[$key]['tname']  = $teacher['tname'];

        }
    }
    $uids = array();
    foreach($permission as $v){
        $uids[] = $v['uid'];
    }
    $founders = explode(',', $_W['config']['setting']['founder']);
    //template('account/permission');
    include $this->template('web/permiss');
}elseif($operation == 'auth'){
    if(!$_W['isfounder']){
        exit('您没有进行该操作的权限');
    }
    $uids = $_GPC['uid'];
    if(empty($uids) || !is_array($uids) || empty($uniacid)){
        exit('error');
    }
    foreach($uids as $v){
        $tmpuid = intval($v);
        $data   = array(
            'uniacid' => $uniacid,
            'uid'     => $tmpuid,
        );
        $exists = pdo_fetch("SELECT * FROM " . tablename('uni_account_users') . " WHERE uid = :uid AND uniacid = :uniacid", array(':uniacid' => $uniacid, ':uid' => $tmpuid));
        if(empty($exists)){
            $data['role'] = 'operator';
            pdo_insert('uni_account_users', $data);
        }
    }
    exit('success');
}elseif($operation == 'revo'){
    $uid     = $_GPC['uid'];
    $uniacid = $_GPC['uniacid'];
    if(empty($uniacid) || empty($uid)){
        exit('error');
    }
    $data = array(
        'uniacid' => $uniacid,
        'uid'     => $uid,
    );

    $exists = pdo_fetch("SELECT * FROM " . tablename('uni_account_users') . " WHERE uid = :uid AND uniacid = :uniacid", array(':uniacid' => $uniacid, ':uid' => $uid));
    if(!empty($exists)){
        pdo_delete('uni_account_users', $data);
        pdo_delete('users', $data);
        $this->imessage('删除成功！', referer(), 'success');
    }

}elseif($operation == 'revos'){
    $ids = $_GPC['ids'];
    $ms  = array();
    foreach($ids as $v){
        $id = intval($v);
        if($id){
            $exists = pdo_fetch("SELECT uid FROM " . tablename('uni_account_users') . " WHERE id = :id AND uniacid = :uniacid", array(':uniacid' => $uniacid, ':id' => $id));
            pdo_delete('users', array('uid' => $exists['uid']));
            pdo_delete('uni_account_users', array('id' => $id));
            array_push($ms, $id);
        }
    }
    exit('success');
}elseif($operation == 'select'){
    $condition = '';
    $params    = array();
    if(!empty($_GPC['keyword'])){
        $condition           = '`username` LIKE :username';
        $params[':username'] = "%{$_GPC['keyword']}%";
    }
    $pindex = max(1, intval($_GPC['page']));
    $psize  = 10;
    $total  = 0;

    $list  = pdo_fetchall("SELECT * FROM " . tablename('users') . " WHERE status = '0' " . (!empty($_W['config']['setting']['founder']) ? " AND uid NOT IN ({$_W['config']['setting']['founder']})" : '') . " LIMIT " . (($pindex - 1) * $psize) . ",{$psize}");
    $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('users') . " WHERE status = '0' " . (!empty($_W['config']['setting']['founder']) ? " AND uid NOT IN ({$_W['config']['setting']['founder']})" : '') . "");
    $pager = pagination($total, $pindex, $psize, '', array('ajaxcallback' => 'null'));

    $permission = pdo_fetchall("SELECT uid FROM " . tablename('uni_account_users') . " WHERE uniacid = '$uniacid'", array(), 'uid');
    template('account/select');
    exit;
}elseif($operation == 'role'){
    $uid     = intval($_GPC['uid']);
    $uniacid = intval($_GPC['uniacid']);
    $role    = !empty($_GPC['role']) && in_array($_GPC['role'], array('operator', 'manager')) ? $_GPC['role'] : 'operator';
    $state   = pdo_update('uni_account_users', array('role' => $role), array('uid' => $uid, 'uniacid' => $uniacid));
    if($state === false) exit('error');else exit('success');
}elseif($operation == 'user'){
    load()->model('user');
    $post             = array();
    $post['username'] = trim($_GPC['username']);
    $user             = user_single($post);
    if(!empty($user)){
        $data   = array(
            'uniacid' => $uniacid,
            'uid'     => $user['uid'],
        );
        $exists = pdo_fetch("SELECT * FROM " . tablename('uni_account_users') . " WHERE uid = :uid AND uniacid = :uniacid", array(':uniacid' => $uniacid, ':uid' => $user['uid']));
        if(empty($exists)){
            $data['role'] = 'operator';
            pdo_insert('uni_account_users', $data);
        }else{
            exit("{$post['username']} 已经是该公众号的操作员或管理员，请勿重复添加");
        }
        exit('success');
    }
    exit('用户不存在或已被删除！');
}