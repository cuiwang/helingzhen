<?php
/**
 * Created by PhpStorm.
 * 竞猜列表
 * User: yike
 * Date: 2016/6/6
 * Time: 10:31
 */
if (!defined('IN_IA')) {
    exit('Access Denied');
}
global $_W, $_GPC;
$callback = $_GPC['callback'];
if($_GPC['op'] == 'classify'){
    if($_GPC['classify_id']){
        $classify = pdo_fetchall('select * from '.tablename('yike_guess_classify').' where uniacid = :uniacid  and parents_id = :parents_id', array(
                ':uniacid' => $_W['uniacid'],
                ':parents_id' => $_GPC['classify_id']
            ));
    }else{
        $classify = pdo_fetchall('select * from '.tablename('yike_guess_classify').' where uniacid = :uniacid  and parents_id != 0', array(
                ':uniacid' => $_W['uniacid'],
                ':parents_id' => $_GPC['classify_id']
            ));
        // var_dump($classify);
        $classify_main = pdo_fetchall('select * from '.tablename('yike_guess_classify').' where uniacid = :uniacid and parents_id = 0', array(
                ':uniacid' => $_W['uniacid'],
            ));
    }
    show_jsonp(1,array('classify_main' => $classify_main, 'classify' => $classify),$callback);
}elseif($_GPC['op'] == 'guess'){
    if($_GPC['classify_id']){
        $classify = pdo_fetch('select * from '.tablename('yike_guess_classify').' where uniacid = :uniacid and id = :id', array(':uniacid' => $_W['uniacid'], ':id' => $_GPC['classify_id']));
        if($classify['parents_id'] == 0){
            $classify_ids = pdo_fetchall('select id from '.tablename('yike_guess_classify').' where uniacid = :uniacid and parents_id = :parents_id', array(':uniacid' => $_W['uniacid'], ':parents_id' => $_GPC['classify_id']));
            $i = 0;
            foreach($classify_ids as $c){
                if($i == 0){
                    $ids = $c['id'];
                }else{
                    $ids .=','.$c['id'];
                }
                $i ++;
            }
            $guess = pdo_fetchall('select * from '.tablename('yike_guess_guess').' where uniacid = :uniacid and classify_id in ('.$ids.') and sold_out = 0 order by id desc', array(':uniacid' => $_W['uniacid']));
        }else{
            $guess = pdo_fetchall('select * from '.tablename('yike_guess_guess').' where uniacid = :uniacid and classify_id = :classify_id and sold_out = 0 order by id desc', array(':uniacid' => $_W['uniacid'], ':classify_id' => $_GPC['classify_id']));
        }
    }else{
        $guess = pdo_fetchall('select * from '.tablename('yike_guess_guess').' where uniacid = :uniacid and sold_out = 0 order by id desc', array(':uniacid' => $_W['uniacid']));
    }
    foreach($guess as $k => $v){
        $guess[$k]['home_image'] = tomedia($v['home_image']);
        $guess[$k]['guest_iamge'] = tomedia($v['guest_iamge']);
        $guess[$k]['image'] = tomedia($v['image']);
    }
    if($guess){
        show_jsonp(1,array('guess' => $guess),$callback);
    }else{
        show_jsonp(0,array('result' => '暂无竞猜'),$callback);
    }
}
