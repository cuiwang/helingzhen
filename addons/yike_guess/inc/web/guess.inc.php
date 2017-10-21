<?php
/**
 * Created by PhpStorm.
 * User: yike
 * Date: 2016/6/3
 * Time: 10:38
 */
global $_W, $_GPC;
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'guess_list';
if($operation == 'guess_list'){
    $size = 10;
    $page = max(1, intval($_GPC['page']));
    
    
    // 检索
    if ($_GPC['play_id']==3) {
        $play_id = '0';
    }else{
        $play_id = $_GPC['play_id'];
    }
    if (!empty($_GPC['name'])||!empty($play_id)) {
        $name = $_GPC['name'];
        if ($play_id != 0) {
            $total = pdo_fetchcolumn("SELECT count(*) from ".tablename('yike_guess_guess')." g left join ".tablename('yike_guess_classify')." c on c.id = g.classify_id where g.play_id =:play_id and g.uniacid = :uniacid and g.name like '%".$name."%'", array(
                ':uniacid' => $_W['uniacid'],
                ':play_id' => $play_id
            ));
            $sql="SELECT g.*, c.name as classify_name from ".tablename('yike_guess_guess')." g left join ".tablename('yike_guess_classify')." c on c.id = g.classify_id where g.play_id =:play_id and g.uniacid = :uniacid and g.name like '%".$name."%' ORDER BY g.id DESC"; 
            $sql .= " limit " . ($page - 1) * $size . ',' . $size;
            $data5 = array(
                ':uniacid' => $_W['uniacid'],
                ':play_id' => $play_id
            );
            $list = pdo_fetchall($sql,$data5);
            $pager = pagination($total, $page, $size);
        }else{
            $total = pdo_fetchcolumn("SELECT count(*) from ".tablename('yike_guess_guess')." g left join ".tablename('yike_guess_classify')." c on c.id = g.classify_id where g.uniacid = :uniacid and g.name like '%".$name."%'", array(
                ':uniacid' => $_W['uniacid']
            ));
            $sql="SELECT g.*, c.name as classify_name from ".tablename('yike_guess_guess')." g left join ".tablename('yike_guess_classify')." c on c.id = g.classify_id where g.uniacid = :uniacid and g.name like '%".$name."%' ORDER BY g.id DESC"; 
            $sql .= " limit " . ($page - 1) * $size . ',' . $size;
            $data5 = array(
                ':uniacid' => $_W['uniacid']
            );
            $list = pdo_fetchall($sql,$data5);
            $pager = pagination($total, $page, $size);
        }
    }else{
        $total = pdo_fetchcolumn("select count(*) from ".tablename('yike_guess_guess')." g left join ".tablename('yike_guess_classify')." c on c.id = g.classify_id where g.uniacid = :uniacid  ORDER BY g.id DESC", array(
            ':uniacid' => $_W['uniacid']
        ));
        $sql='select g.*, c.name as classify_name from '.tablename('yike_guess_guess').' g left join '.tablename('yike_guess_classify').' c on c.id = g.classify_id where g.uniacid = :uniacid  ORDER BY g.id DESC';
        $sql .= " limit " . ($page - 1) * $size . ',' . $size;
        $list = pdo_fetchall($sql,array(':uniacid' => $_W['uniacid']));
        // var_dump($list);
        $pager = pagination($total, $page, $size);
    }
    include $this->template('web/guess');
}elseif($operation == 'add_guess'){
    $guess_id = $_GPC['id'];
    $classify = pdo_fetchall('select * from '.tablename('yike_guess_classify').' where uniacid = :uniacid and parents_id != 0', array(':uniacid' => $_W['uniacid']));
    if($guess_id){
        $guess = pdo_fetch(' SELECT * FROM '.tablename('yike_guess_guess').' WHERE id = :id',array(':id' => $guess_id));
        if($guess['play_id'] == 2){
            $guess['contest'] = unserialize($guess['contest']);
        }
    }
    if(checksubmit('submit')){
        if(empty($_GPC['name'])){
            message('项目名不能为空！', $this->createWebUrl('guess', array('op' => 'add_guess')), 'error');
        }elseif(empty($_GPC['nature'])){
            message('比赛性质不能为空！', $this->createWebUrl('guess', array('op' => 'add_guess')), 'error');
        }elseif(empty($_GPC['classify_id'])){
            message('所属分类不能为空！', $this->createWebUrl('guess', array('op' => 'add_guess')), 'error');
        }elseif(empty($_GPC['upper'])){
            $_GPC['upper'] = 0;
        }elseif(empty($_GPC['upper'])){
            $_GPC['lower'] = 0;
        }
        if($_GPC['play_id'] == 1){
            if(empty($_GPC['home_team'])){
                message('主队名不能为空！', $this->createWebUrl('guess', array('op' => 'add_guess')), 'error');
            }elseif(empty($_GPC['guest_team'])){
                message('客队名不能为空！', $this->createWebUrl('guess', array('op' => 'add_guess')), 'error');
            }elseif(empty($_GPC['home_image'])){
                message('主队图标不能为空！', $this->createWebUrl('guess', array('op' => 'add_guess')), 'error');
            }elseif(empty($_GPC['guest_iamge'])){
                message('客队图标不能为空！', $this->createWebUrl('guess', array('op' => 'add_guess')), 'error');
            }elseif(empty($_GPC['win'])){
                message('胜赔率不能为空！', $this->createWebUrl('guess', array('op' => 'add_guess')), 'error');
            }elseif(empty($_GPC['transport'])){
                message('负赔率不能为空！', $this->createWebUrl('guess', array('op' => 'add_guess')), 'error');
            }elseif(empty($_GPC['image'])){
                message('封面图不能为空！', $this->createWebUrl('guess', array('op' => 'add_guess')), 'error');
            }
            if(empty($_GPC['flat'])){
                $flat = 0;
            }else{
                $flat = $_GPC['flat'];
            }
            $data = array(
                'sort' => $_GPC['sort'],
                'name' => $_GPC['name'],
                'nature' => $_GPC['nature'],
                'classify_id' => $_GPC['classify_id'],
                'type_id' => $_GPC['type_id'],
                'start_time' => strtotime($_GPC['start_time']),
                'end_time' => strtotime($_GPC['end_time']),
                'home_team' => $_GPC['home_team'],
                'guest_team' => $_GPC['guest_team'],
                'home_image' => $_GPC['home_image'],
                'guest_iamge' => $_GPC['guest_iamge'],
                'match_time' => strtotime($_GPC['match_time']),
                'win' => $_GPC['win'],
                'flat' => $flat,
                'transport' => $_GPC['transport'],
                'describe' => $_GPC['describe'],
                'concede_num' => $_GPC['concede_num'],
                'concede' => $_GPC['concede'],
                'image' => $_GPC['image'],
                'is_show' => $_GPC['is_show'],
                'play_id' => $_GPC['play_id'],
                'upper' => $_GPC['upper'],
                'lower' => $_GPC['lower']
            );
            if($guess_id){
                $result = pdo_update('yike_guess_guess', $data, array(
                    'id' => $guess_id
                ));
            }else{
                $data['uniacid'] = $_W['uniacid'];
                $result = pdo_insert('yike_guess_guess', $data);
            }
            if($result){
                message('竞猜项目添加成功！', $this->createWebUrl('guess', array('op' => 'guess_list')), 'success');
            }else{
                message('竞猜项目添加失败！', $this->createWebUrl('guess', array('op' => 'guess_list')), 'error');
            }
        }else{
            if(empty($_GPC['contest'])){
                message('请至少添加一个竞赛者！', $this->createWebUrl('guess', array('op' => 'add_guess')), 'error');
            }
            foreach($_GPC['contest']['name'] as $k => $v){
                if(empty($v)){
                    message('参赛者名不能为空！', $this->createWebUrl('guess', array('op' => 'add_guess')), 'error');
                }else{
                    $contest[$k]['name'] = $v;
                }
            }
            foreach($_GPC['contest']['image'] as $k => $v){
                if(empty($v)){
                    message('参赛者图标不能为空！', $this->createWebUrl('guess', array('op' => 'add_guess')), 'error');
                }else{
                    $contest[$k]['image'] = $v;
                }
            }foreach($_GPC['contest']['odds'] as $k => $v){
                if(empty($v)){
                    message('参赛者赔率不能为空！', $this->createWebUrl('guess', array('op' => 'add_guess')), 'error');
                }else{
                    $contest[$k]['odds'] = $v;
                }
            }
            $data = array(
                'contest'=>serialize($contest),
                'name' => $_GPC['name'],
                'nature' => $_GPC['nature'],
                'classify_id' => $_GPC['classify_id'],
                'start_time' => strtotime($_GPC['start_time']),
                'end_time' => strtotime($_GPC['end_time']),
                'match_time' => strtotime($_GPC['match_time']),
                'describe' => $_GPC['describe'],
                'is_show' => $_GPC['is_show'],
                'image' => $_GPC['image'],
                'play_id' => $_GPC['play_id'],
                'upper' => $_GPC['upper'],
                'lower' => $_GPC['lower']
            );
            if($guess_id){
                $result = pdo_update('yike_guess_guess', $data, array(
                    'id' => $guess_id
                ));
            }else{
                $data['uniacid'] = $_W['uniacid'];
                $result = pdo_insert('yike_guess_guess', $data);
            }
            if($result){
                message('竞猜项目添加成功！', $this->createWebUrl('guess', array('op' => 'guess_list')), 'success');
            }else{
                message('竞猜项目添加失败！', $this->createWebUrl('guess', array('op' => 'guess_list')), 'error');
            }
        }
    }
    include $this->template('web/guess');
}elseif($operation == 'guess_delete'){
    $id = intval($_GPC['id']);
    $row = pdo_fetch("SELECT id FROM " . tablename('yike_guess_guess') . " WHERE id = :id", array(':id' => $id));
    if (empty($row)) {
        message('抱歉，项目不存在或是已经被删除！',$this->createWebUrl('guess', array('op' => 'guess_list')),'error');
    }
    pdo_delete('yike_guess_guess', array('id' => $id));
    message('删除成功！', $this->createWebUrl('guess', array('op' => 'guess_list')), 'success');
}elseif($operation == 'classify_list'){
    $classify = pdo_fetchall('select * from '.tablename('yike_guess_classify').' where uniacid = :uniacid and parents_id = 0', array(':uniacid' => $_W['uniacid']));
    $i = 0;
    foreach($classify as $c){
        $classify[$i]['son'] = pdo_fetchall('select * from '.tablename('yike_guess_classify').' where uniacid = :uniacid and parents_id = :parents_id', array(':uniacid' => $_W['uniacid'], ':parents_id' => $c['id']));
        $i ++;
    }
    include $this->template('web/classify');
}elseif($operation == 'add_classify'){
    $classify_id = $_GPC['id'];
    $classify = pdo_fetchall('select * from '.tablename('yike_guess_classify').' where uniacid = :uniacid and parents_id = 0', array(':uniacid' => $_W['uniacid']));
    if($classify_id){
        $classify_one = pdo_fetch(' SELECT * FROM '.tablename('yike_guess_classify').' WHERE id = :id',array(':id' => $classify_id));
    }
    if(checksubmit('submit')){
        $data = array(
            'sort' => $_GPC['sort'],
            'name' => $_GPC['name'],
            'parents_id' => $_GPC['parents_id'],
            'link' => $_GPC['link'],
            'image' => $_GPC['image'],
            'is_show' => $_GPC['is_show'],
        );
        if($classify_id){
            $result = pdo_update('yike_guess_classify', $data, array(
                'id' => $classify_id
            ));
        }else{
            $data['uniacid'] = $_W['uniacid'];
            $result = pdo_insert('yike_guess_classify', $data);
        }
        if($result){
            message('分类添加成功！', $this->createWebUrl('guess', array('op' => 'classify_list')), 'success');
        }else{
            message('分类添加失败！', $this->createWebUrl('guess', array('op' => 'classify_list')), 'error');
        }
    }
    include $this->template('web/classify');
}elseif($operation == 'classify_delete'){
    $id = intval($_GPC['id']);
    $row = pdo_fetch("SELECT * FROM " . tablename('yike_guess_classify') . " WHERE id = :id", array(':id' => $id));
    if (empty($row)) {
        message('抱歉，分类不存在或是已经被删除！',$this->createWebUrl('guess', array('op' => 'classify_list')),'error');
    }
    if($row['parents_id']){
        pdo_delete('yike_guess_classify', array('parents_id' => $id));
    }
    pdo_delete('yike_guess_classify', array('id' => $id));
    message('删除成功！', $this->createWebUrl('guess', array('op' => 'classify_list')), 'success');
}elseif($operation == 'open'){
    $guess = pdo_fetch(' SELECT * FROM '.tablename('yike_guess_guess').' WHERE id = :id',array(':id' => $_GPC['id']));
    if($guess['play_id'] == 2){
        $guess['contest'] = unserialize($guess['contest']);
    }
    if(checksubmit('submit')){
        if($guess['play_id'] == 1 || empty($guess['play_id'])){
            /*if($guess['type_id'] == 1){
            if($guess['concede'] == 1){
                $home_num = $_GPC['home_num'];
                $guest_num = $_GPC['guest_num'] + $guess['concede_num'];
            }elseif($guess['concede'] == 2){
                $home_num = $_GPC['home_num'] + $guess['concede_num'];
                $guest_num = $_GPC['guest_num'];
            }
        }else{*/
            $home_num = $_GPC['home_num'];
            $guest_num = $_GPC['guest_num'];
            /*}*/
            if($home_num > $guest_num){
                $result = 1;
                $win = $guess['win'];
            }elseif($home_num == $guest_num){
                $result = 2;
                $win = $guess['flat'];
            }elseif($home_num < $guest_num){
                $result = 3;
                $win = $guess['transport'];
            }
            $lottery = $_GPC['home_num'].':'.$_GPC['guest_num'];
            $data2 = array(
                'result' => $result,
                'is_open' => 1,
                'lottery' => $lottery
            );
            pdo_update('yike_guess_guess', $data2, array(
                'id' => $_GPC['id']
            ));
            $order = pdo_fetchall(' SELECT * FROM '.tablename('yike_guess_order').' WHERE uniacid = :uniacid and guess_id = :guess_id',array(':uniacid' => $_W['uniacid'], ':guess_id' => $_GPC['id']));
            foreach($order as $o){
                if($o['bet'] == $result){
                    $bonus = $o['money'] * $win;
                    $is_win = 1;
                }else{
                    $bonus = 0;
                    $is_win = 0;
                }
                $data = array(
                    'bonus' => $bonus,
                    'is_win' => $is_win,
                    'lottery' => $lottery
                );
                pdo_update('yike_guess_order', $data, array(
                    'id' => $o['id']
                ));
                $user = pdo_fetch(' SELECT * FROM '.tablename('mc_members').' WHERE uid = :uid',array(':uid' => $o['user_id']));
                $data1 = array(
                    'credit1' => $user['credit1'] + $bonus
                );
                pdo_update('mc_members', $data1, array(
                    'uid' => $o['user_id']
                ));
                if($is_win == 1){
                    $data3 = array(
                        'uid' => $user['uid'],
                        'uniacid' => $_W['uniacid'],
                        'money' => $bonus,
                        'type' => 1,
                        'balance' => $user['credit1'] + $bonus,
                        'create_time' => time(),
                        'name' => '中奖'
                    );
                    pdo_insert('yike_guess_balance', $data3);
                }
                if(date('Y-m-d', time()) == ((date('Y-m', time())).'-01')){
                    $data4 = array(
                        'add_money' => $bonus
                    );
                }else{
                    $user1 = pdo_fetch(' SELECT * FROM '.tablename('yike_members').' WHERE uid = :uid',array(':uid' => $o['user_id']));
                    $data4 = array(
                        'add_money' => $user1['add_money'] + $bonus
                    );
                }
                pdo_update('yike_members', $data4, array(
                    'uid' => $o['user_id']
                ));
                $setdata = pdo_fetch("select * from " . tablename('yike_guess_sysset') . ' where uniacid=:uniacid limit 1', array(
                    ':uniacid' => $_W['uniacid']
                ));
                $set = unserialize($setdata['sets']);
                $openid = pdo_fetch('SELECT openid FROM '.tablename('mc_mapping_fans'). ' where uid = :uid',array(':uid' => $o['user_id']));
                if(isset($set['notice']['submit'])){
                    if($is_win == 1){
                        $send = m('notice')->sendTplNotice($openid['openid'], $set['notice']['submit'], array('title' => array('value' => '尊敬的客户：'), 'headinfo' => array('value' => '恭喜您中奖了'), 'program' => array('value' => $guess['name']), 'result' => array('value' => '获得奖金'.$bonus), 'remark' => array('value' => '谢谢您的支持')));
                    }else{
                        $send = m('notice')->sendTplNotice($openid['openid'], $set['notice']['submit'], array('title' => array('value' => '尊敬的客户：'), 'headinfo' => array('value' => '很遗憾您没中奖'), 'program' => array('value' => $guess['name']), 'result' => array('value' => '未中奖'), 'remark' => array('value' => '谢谢您的支持')));
                    }
                }
            }
        }elseif($guess['play_id'] == 2){
            $lottery = $_GPC['champion'];
            $data2 = array(
                'result' => $lottery,
                'is_open' => 1,
                'lottery' => $guess['contest'][$lottery]['name']
            );
            pdo_update('yike_guess_guess', $data2, array(
                'id' => $_GPC['id']
            ));
            $order = pdo_fetchall(' SELECT * FROM '.tablename('yike_guess_order').' WHERE uniacid = :uniacid and guess_id = :guess_id',array(':uniacid' => $_W['uniacid'], ':guess_id' => $_GPC['id']));
            foreach($order as $o){
                if($o['bet'] == $lottery){
                    $bonus = $o['money'] * $guess['contest'][$lottery]['odds'];
                    $is_win = 1;
                }else{
                    $bonus = 0;
                    $is_win = 0;
                }
                $data = array(
                    'bonus' => $bonus,
                    'is_win' => $is_win,
                    'lottery' => $lottery
                );
                pdo_update('yike_guess_order', $data, array(
                    'id' => $o['id']
                ));
                $user = pdo_fetch(' SELECT * FROM '.tablename('mc_members').' WHERE uid = :uid',array(':uid' => $o['user_id']));
                $data1 = array(
                    'credit1' => $user['credit1'] + $bonus
                );
                pdo_update('mc_members', $data1, array(
                    'uid' => $o['user_id']
                ));
                if($is_win == 1){
                    $data3 = array(
                        'uid' => $user['uid'],
                        'uniacid' => $_W['uniacid'],
                        'money' => $bonus,
                        'type' => 1,
                        'balance' => $user['credit1'] + $bonus,
                        'create_time' => time(),
                        'name' => '中奖'
                    );
                    pdo_insert('yike_guess_balance', $data3);
                }
                if(date('Y-m-d', time()) == ((date('Y-m', time())).'-01')){
                    $data4 = array(
                        'add_money' => $bonus
                    );
                }else{
                    $user1 = pdo_fetch(' SELECT * FROM '.tablename('yike_members').' WHERE uid = :uid',array(':uid' => $o['user_id']));
                    $data4 = array(
                        'add_money' => $user1['add_money'] + $bonus
                    );
                }
                pdo_update('yike_members', $data4, array(
                    'uid' => $o['user_id']
                ));
                $setdata = pdo_fetch("select * from " . tablename('yike_guess_sysset') . ' where uniacid=:uniacid limit 1', array(
                    ':uniacid' => $_W['uniacid']
                ));
                $set = unserialize($setdata['sets']);
                $openid = pdo_fetch('SELECT openid FROM '.tablename('mc_mapping_fans'). ' where uid = :uid',array(':uid' => $o['user_id']));
                if(isset($set['notice']['submit'])){
                    if($is_win == 1){
                        $send = m('notice')->sendTplNotice($openid['openid'], $set['notice']['submit'], array('title' => array('value' => '尊敬的客户：'), 'headinfo' => array('value' => '恭喜您中奖了'), 'program' => array('value' => $guess['name']), 'result' => array('value' => '获得奖金'.$bonus), 'remark' => array('value' => '谢谢您的支持')));
                    }else{
                        $send = m('notice')->sendTplNotice($openid['openid'], $set['notice']['submit'], array('title' => array('value' => '尊敬的客户：'), 'headinfo' => array('value' => '很遗憾您没中奖'), 'program' => array('value' => $guess['name']), 'result' => array('value' => '未中奖'), 'remark' => array('value' => '谢谢您的支持')));
                    }
                }
            }
        }
        message('开奖完成！', $this->createWebUrl('guess', array('op' => 'guess_list')), 'success');
    }
    include $this->template('web/open');
}else if($operation == 'sold_out'){
    $id = intval($_GPC['id']);
    // var_dump($_GPC['id']);die;
    $alter = pdo_fetch("SELECT * FROM".tablename('yike_guess_guess')."WHERE id = :id limit 1",array(
        ':id' => $id
        ));
    // var_dump($alter);
    $sold_out = intval($alter['sold_out']);
    // var_dump($sold_out);
    if (empty($alter)) {
        message('项目不存在', $this->createWebUrl('guess', array('op' => 'guess_list')), 'success');
    }else if($sold_out == '0'){
        $sold_out = array('sold_out' => 1 );
        $result = pdo_update('yike_guess_guess', $sold_out, array(
            'id' => $_GPC['id']
        ));
        if (!empty($result)) {
            message('下架成功', $this->createWebUrl('guess', array('op' => 'guess_list')), 'success');
        }
    }
}