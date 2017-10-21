<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
//defined('IN_IA') or exit('Access Denied');
$_W['page']['title'] = '添加用户1 - 用户管理 - 用户管理';
global $_GPC, $_W;
$weid              = $_W['uniacid'];
$action1           = 'permiss';
$this1             = 'no1';
$action            = 'semester';
$GLOBALS['frames'] = $this->getNaveMenu($_GPC['schoolid'], $action);
$schoolid          = intval($_GPC['schoolid']);
$logo              = pdo_fetch("SELECT logo,title,wqgroupid FROM " . tablename($this->table_index) . " WHERE id = '{$schoolid}'");
$uid               = intval($_GPC['uid']);
$item              = pdo_fetch("SELECT * FROM " . tablename('users') . " WHERE uid = '{$uid}'");
$grid              = pdo_fetch("SELECT id FROM " . tablename('users_group') . " WHERE id = '{$item['groupid']}'");
if(checksubmit()){
    load()->model('user');
    $tuid             = intval($_GPC['uid']);
    $user             = array();
    $user['username'] = trim($_GPC['username']);
    $user['schoolid'] = $schoolid;
    $user['uniacid']  = $weid;
    if(!preg_match(REGULAR_USERNAME, $user['username'])){
        $this->imessage('必须输入用户名，格式为 3-15 位字符，可以包括汉字、字母（不区分大小写）、数字、下划线和句点。', referer(), 'warning');
    }
    if(!$tuid){
        if(user_check(array('username' => $user['username']))){
            $this->imessage('非常抱歉，此用户名已经被注册，你需要更换注册名称！', referer(), 'error');
        }
    }
    $user['password'] = trim($_GPC['password']);
    if(istrlen($user['password']) < 8){
        $this->imessage('必须输入密码，且密码长度不得低于8位。', referer(), 'error');
    }
    $user['remark']  = $_GPC['remark'];
    $user['tid']     = intval($_GPC['jsid']);
    $user['groupid'] = intval($_GPC['groupid']) ? intval($_GPC['groupid']) : 1;
    $group           = pdo_fetch("SELECT id,timelimit FROM " . tablename('users_group') . " WHERE id = :id", array(':id' => $user['groupid']));
    if(empty($group)){
        $this->imessage('会员组不存在', referer(), 'error');
    }
    if(!$tuid){
        $cheisbd = pdo_fetch("SELECT * FROM " . tablename('users') . " WHERE tid = :tid", array(':tid' => $user['tid']));
        if(!empty($cheisbd)){
            $this->imessage('抱歉,该教师已绑定其他帐号', referer(), 'error');
        }
    }
    $timelimit = intval($group['timelimit']);
    $timeadd   = 0;
    if($timelimit > 0){
        $timeadd = strtotime($timelimit . ' days');
    }
    $user['starttime'] = TIMESTAMP;
    $user['endtime']   = $timeadd;
    if(!$tuid){
        $restuid = user_register($user);
        if($restuid > 0){
            unset($user['password']);
            $exists = pdo_fetch("SELECT * FROM " . tablename('uni_account_users') . " WHERE uid = :uid AND uniacid = :uniacid", array(':uniacid' => $weid, ':uid' => $restuid));
            if(empty($exists)){
                $data['role']    = 'operator';
                $data['uid']     = $restuid;
                $data['uniacid'] = $weid;
                pdo_insert('uni_account_users', $data);
                $insert = array(
                    'uniacid'    => $weid,
                    'uid'        => $restuid,
                    'type'       => 'fm_jiaoyu',
                    'permission' => 'fm_jiaoyu_menu_school',
                );
                pdo_insert('users_permission', $insert);
            }
            $this->imessage('用户增加成功！', referer(), 'success');
        }
    }else{
        $user['uid'] = $tuid;
        $isup        = user_updates($user);
        if($isup != 0){
            unset($user['password']);
            $exists = pdo_fetch("SELECT * FROM " . tablename('uni_account_users') . " WHERE uid = :uid AND uniacid = :uniacid", array(':uniacid' => $weid, ':uid' => $tuid));
            if(empty($exists)){
                $data['role']    = 'operator';
                $data['uid']     = $tuid;
                $data['uniacid'] = $weid;
                pdo_insert('uni_account_users', $data);
            }
            $this->imessage('修改成功！', referer(), 'success');
        }else{
            $this->imessage('修改失败，请联系管理员！', referer(), 'error');
        }
    }
    $this->imessage('增加用户失败，请稍候重试或联系网站管理员解决！', referer(), 'error');
}
$groups = pdo_fetchall("SELECT id, name FROM " . tablename('users_group') . " ORDER BY id ASC");
$alljs  = pdo_fetchall("SELECT id,tname FROM " . tablename($this->table_teachers) . " WHERE weid = '{$weid}' And schoolid = {$schoolid} ORDER BY id ASC");

function user_updates($user) {
    if(empty($user) || !is_array($user)){
        return 0;
    }
    if(!$user['uid']){
        return 0;
    }
    $user['salt']      = random(8);
    $user['password']  = user_hash($user['password'], $user['salt']);
    $user['joinip']    = CLIENT_IP;
    $user['joindate']  = TIMESTAMP;
    $user['lastip']    = CLIENT_IP;
    $user['lastvisit'] = TIMESTAMP;
    if(empty($user['status'])){
        $user['status'] = 2;
    }
    pdo_update('users', $user, array('uid' => intval($user['uid'])));

    return 1;
}

function user_hashs($passwordinput, $salt) {
    global $_W;
    $passwordinput = "{$passwordinput}-{$salt}-{$_W['config']['setting']['authkey']}";

    return sha1($passwordinput);
}

include $this->template('web/creates');

