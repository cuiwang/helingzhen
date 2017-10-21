<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
global $_GPC, $_W;

$weid              = $_W['uniacid'];
$action            = 'signup';
$this1             = 'no3';
$GLOBALS['frames'] = $this->getNaveMenu($_GPC['schoolid'], $action);
$schoolid          = intval($_GPC['schoolid']);
$logo              = pdo_fetch("SELECT logo,title FROM " . tablename($this->table_index) . " WHERE id = '{$schoolid}'");

$school    = pdo_fetch("SELECT signset FROM " . tablename($this->table_index) . " where id = :id ", array(':id' => $schoolid));
$bjlist    = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$weid}' AND schoolid ={$schoolid} And type = 'theclass' ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'theclass', ':schoolid' => $schoolid));
$njlist    = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$weid}' AND schoolid ={$schoolid} And type = 'semester' ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'semester', ':schoolid' => $schoolid));
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if($operation == 'display'){

    $pindex    = max(1, intval($_GPC['page']));
    $psize     = 10;
    $condition = '';

    if(!empty($_GPC['bj_id'])){
        $bj_id     = intval($_GPC['bj_id']);
        $condition .= " And bj_id = '{$bj_id}' ";
    }

    if(!empty($_GPC['nj_id'])){
        $nj_id     = intval($_GPC['nj_id']);
        $condition .= " And nj_id = '{$nj_id}' ";
    }

    if(!empty($_GPC['status'])){
        $status    = intval($_GPC['status']);
        $condition .= " And status = '{$status}' ";
    }

    $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_signup) . " where schoolid = '{$schoolid}' And weid = '{$weid}' $condition ORDER BY createtime DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);

    foreach($list as $key => $row){
        $bj                     = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " WHERE sid = :sid", array(':sid' => $row['bj_id']));
        $nj                     = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " WHERE sid = :sid", array(':sid' => $row['nj_id']));
        $order                  = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE id = :id", array(':id' => $row['orderid']));
        $member                 = pdo_fetch("SELECT * FROM " . tablename('mc_members') . " where uniacid = :uniacid And uid = :uid ", array(':uniacid' => $_W ['uniacid'], ':uid' => $row['uid']));
        $list[$key]['avatar']   = $member['avatar'];
        $list[$key]['nickname'] = $member['nickname'];
        $list[$key]['bjname']   = $bj['sname'];
        $list[$key]['njname']   = $nj['sname'];
        $list[$key]['order']    = $order['status'];
    }
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_signup) . " WHERE weid = '{$weid}' AND schoolid = {$schoolid} $condition");

    $pager = pagination($total, $pindex, $psize);
}elseif($operation == 'pass'){

    $id = intval($_GPC['id']);

    $item = pdo_fetch("SELECT * FROM " . tablename($this->table_signup) . " WHERE :id = id", array(':id' => $id));

    $temp = array(
        'weid'           => $item['weid'],
        'schoolid'       => $item['schoolid'],
        's_name'         => $item['name'],
        'sex'            => $item['sex'],
        'numberid'       => $item['numberid'],
        'mobile'         => $item['mobile'],
        'xq_id'          => $item['nj_id'],
        'bj_id'          => $item['bj_id'],
        'note'           => $item['idcard'],
        'birthdate'      => $item['birthday'],
        'seffectivetime' => time(),
        'createdate'     => time()
    );

    pdo_insert($this->table_students, $temp);
    $studentid = pdo_insertid();
    $user      = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where schoolid = '{$item['schoolid']}' And weid = '{$item['weid']}' And (own = '{$item['openid']}' or dad = '{$item['openid']}' or mom = '{$item['openid']}') ");
    $singset   = iunserializer($school['signset']);
    if($singset['is_bd'] == 1){
        if(empty($user)){
            if(!empty($item['pard'])){
                if($item['pard'] == 2){
                    $data = array(
                        'numberid' => $item['numberid'],
                        'mom'      => $item['openid'],
                        'muid'     => $item['uid']
                    );
                    $info = array('name' => '', 'mobile' => $item ['mobile']);
                }
                if($item['pard'] == 3){
                    $data = array(
                        'numberid' => $item['numberid'],
                        'dad'      => $item['openid'],
                        'duid'     => $item['uid']
                    );
                    $info = array('name' => '', 'mobile' => $item ['mobile']);
                }
                if($item['pard'] == 4){
                    $data = array(
                        'numberid' => $item['numberid'],
                        'own'      => $item['openid'],
                        'ouid'     => $item['uid']
                    );
                    $info = array('name' => $item ['name'], 'mobile' => $item ['mobile']);
                }
                pdo_update($this->table_students, $data, array('id' => $studentid));
                $temp2             = array(
                    'sid'      => $studentid,
                    'weid'     => $item ['weid'],
                    'schoolid' => $item ['schoolid'],
                    'openid'   => $item ['openid'],
                    'pard'     => $item['pard'],
                    'uid'      => $item['uid']
                );
                $temp2['userinfo'] = iserializer($info);
                pdo_insert($this->table_user, $temp2);
            }
        }
    }
    $temp1 = array(
        'status'   => 2,
        'passtime' => time()
    );

    pdo_update($this->table_signup, $temp1, array('id' => $id));
    $this->sendMobileBmshjgtz($id, $item['schoolid'], $item['weid'], $item['openid'], $item['name']);
    $this->imessage('审核成功！', referer(), 'success');

}elseif($operation == 'defid'){
    $id = intval($_GPC['id']);

    $item = pdo_fetch("SELECT * FROM " . tablename($this->table_signup) . " WHERE :id = id", array(':id' => $id));

    $temp = array(
        'status'   => 3,
        'passtime' => time()
    );

    pdo_update($this->table_signup, $temp, array('id' => $id));
    $this->sendMobileBmshjgtz($_GPC['id'], $item['schoolid'], $item['weid'], $item['openid'], $item['name']);

    $this->imessage('已拒绝该申请！', referer(), 'success');
}elseif($operation == 'delete'){
    $id   = intval($_GPC['id']);
    $item = pdo_fetch("SELECT id FROM " . tablename($this->table_signup) . " WHERE id = '$id'");
    if(empty($item)){
        $this->imessage('抱歉，不存在或是已经被删除！', 'error');
    }
    pdo_delete($this->table_signup, array('id' => $id), 'OR');

    $this->imessage('删除成功！', referer(), 'success');
}
include $this->template('web/signup');
?>