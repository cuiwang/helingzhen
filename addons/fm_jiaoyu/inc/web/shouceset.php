<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */

global $_GPC, $_W;
$weid              = $_W['uniacid'];
$action            = 'shoucelist';
$this1             = 'no2';
$GLOBALS['frames'] = $this->getNaveMenu($_GPC['schoolid'], $action);
$schoolid          = intval($_GPC['schoolid']);
$logo              = pdo_fetch("SELECT logo,title,shoucename FROM " . tablename($this->table_index) . " WHERE id = '{$schoolid}'");
$operation         = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$it                = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " WHERE sid = :sid", array(':sid' => $sid));
$xueqi             = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$weid}' AND schoolid ={$schoolid} And type = 'semester' ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'semester', ':schoolid' => $schoolid));
if($operation == 'display'){
    if(!empty($_GPC['ssort'])){
        foreach($_GPC['ssort'] as $sid => $ssort){
            pdo_update($this->table_scset, array('ssort' => $ssort), array('id' => $sid));
        }
        message('批量更新排序成功', $this->createWebUrl('shouceset', array('op' => 'display', 'schoolid' => $schoolid)), 'success');
    }
    $pindex    = max(1, intval($_GPC['page']));
    $psize     = 10;
    $condition = '';
    $sclist    = pdo_fetchall("SELECT * FROM " . tablename($this->table_scset) . " WHERE weid = '{$weid}' And schoolid = {$schoolid} ORDER BY id ASC, ssort DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
    foreach($sclist as $key => $value){
        $gz = pdo_fetch("SELECT id FROM " . tablename($this->table_scicon) . " WHERE schoolid = :schoolid And setid = :setid", array(':schoolid' => $schoolid, ':setid' => $value['id']));
    }
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_scset) . " WHERE weid = '{$weid}' AND schoolid ={$schoolid} $condition");
    $pager = pagination($total, $pindex, $psize);
}elseif($operation == 'pylist'){
    if(!empty($_GPC['ssort'])){
        foreach($_GPC['ssort'] as $sid => $ssort){
            pdo_update($this->table_scpy, array('ssort' => $ssort), array('id' => $sid));
        }
        message('批量更新排序成功', $this->createWebUrl('shouceset', array('op' => 'pylist', 'schoolid' => $schoolid)), 'success');
    }
    $pindex    = max(1, intval($_GPC['page']));
    $psize     = 10;
    $condition = '';
    $sclist    = pdo_fetchall("SELECT * FROM " . tablename($this->table_scpy) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' ORDER BY ssort ASC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
    foreach($sclist as $key => $value){
        $teacher               = pdo_fetch("SELECT tname FROM " . tablename($this->table_teachers) . " WHERE schoolid = :schoolid And id = :id", array(':schoolid' => $schoolid, ':id' => $value['tid']));
        $sclist[$key]['tname'] = $teacher['tname'];
    }
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_scpy) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' $condition");
    $pager = pagination($total, $pindex, $psize);
}elseif($operation == 'setiocn'){
    load()->func('tpl');
    $setid = intval($_GPC['setid']);
    if(!empty($setid)){
        $item = pdo_fetchall("SELECT * FROM " . tablename($this->table_scicon) . " WHERE setid = '{$setid}' And schoolid = '{$schoolid}' And weid = '{$weid}' And type = 1 ORDER BY ssort ASC");
        if(checksubmit('submit')){
            if(!empty($_GPC['thisid'])){
                if(!empty($_GPC['old'])){
                    foreach($_GPC['old'] as $key => $thisid){
                        if(!empty($thisid)){
                            $thisid = trim($_GPC['thisid'][$key]);
                            $title  = trim($_GPC['custom_title'][$key]);
                            $ssort  = trim($_GPC['custom_ssort'][$key]);
                            $data   = array(
                                'weid'       => $weid,
                                'schoolid'   => $schoolid,
                                'setid'      => $setid,
                                'title'      => $title,
                                'icon1title' => trim($_GPC['custom_title1'][$key]),
                                'icon2title' => trim($_GPC['custom_title2'][$key]),
                                'icon3title' => trim($_GPC['custom_title3'][$key]),
                                'icon4title' => trim($_GPC['custom_title4'][$key]),
                                'icon5title' => trim($_GPC['custom_title5'][$key]),
                                'icon1'      => trim($_GPC['custom_icon1'][$key]),
                                'icon2'      => trim($_GPC['custom_icon2'][$key]),
                                'icon3'      => trim($_GPC['custom_icon3'][$key]),
                                'icon4'      => trim($_GPC['custom_icon4'][$key]),
                                'icon5'      => trim($_GPC['custom_icon5'][$key]),
                                'ssort'      => $ssort,
                                'type'       => 1
                            );
                            pdo_update($this->table_scicon, $data, array('id' => $thisid));
                        }
                    }
                }
                if(!empty($_GPC['new'])){
                    foreach($_GPC['new'] as $key => $title){
                        $title = trim($_GPC['custom_title-new'][$key]);
                        $data  = array(
                            'weid'       => $weid,
                            'schoolid'   => $schoolid,
                            'setid'      => $setid,
                            'title'      => $title,
                            'icon1title' => trim($_GPC['custom_title1-new'][$key]),
                            'icon2title' => trim($_GPC['custom_title2-new'][$key]),
                            'icon3title' => trim($_GPC['custom_title3-new'][$key]),
                            'icon4title' => trim($_GPC['custom_title4-new'][$key]),
                            'icon5title' => trim($_GPC['custom_title5-new'][$key]),
                            'icon1'      => trim($_GPC['custom_icon1-new'][$key]),
                            'icon2'      => trim($_GPC['custom_icon2-new'][$key]),
                            'icon3'      => trim($_GPC['custom_icon3-new'][$key]),
                            'icon4'      => trim($_GPC['custom_icon4-new'][$key]),
                            'icon5'      => trim($_GPC['custom_icon5-new'][$key]),
                            'ssort'      => trim($_GPC['custom_ssort-new'][$key]),
                            'type'       => 1
                        );
                        pdo_insert($this->table_scicon, $data);
                    }
                }
            }else{
                foreach($_GPC['custom_title-new'] as $key => $title){
                    $title = trim($title);
                    $data  = array(
                        'weid'       => $weid,
                        'schoolid'   => $schoolid,
                        'setid'      => $setid,
                        'title'      => $title,
                        'icon1title' => trim($_GPC['custom_title1-new'][$key]),
                        'icon2title' => trim($_GPC['custom_title2-new'][$key]),
                        'icon3title' => trim($_GPC['custom_title3-new'][$key]),
                        'icon4title' => trim($_GPC['custom_title4-new'][$key]),
                        'icon5title' => trim($_GPC['custom_title5-new'][$key]),
                        'icon1'      => trim($_GPC['custom_icon1-new'][$key]),
                        'icon2'      => trim($_GPC['custom_icon2-new'][$key]),
                        'icon3'      => trim($_GPC['custom_icon3-new'][$key]),
                        'icon4'      => trim($_GPC['custom_icon4-new'][$key]),
                        'icon5'      => trim($_GPC['custom_icon5-new'][$key]),
                        'ssort'      => trim($_GPC['custom_ssort-new'][$key]),
                        'type'       => 1
                    );
                    pdo_insert($this->table_scicon, $data);
                }
            }
            $this->imessage('操作成功!', $this->createWebUrl('shouceset', array('op' => 'display', 'schoolid' => $schoolid)), 'success');
        }
    }
}elseif($operation == 'setiocns'){
    load()->func('tpl');
    $setid = intval($_GPC['setid']);
    if(!empty($setid)){
        $item = pdo_fetchall("SELECT * FROM " . tablename($this->table_scicon) . " WHERE setid = '{$setid}' And schoolid = '{$schoolid}' And weid = '{$weid}' And type = 2 ORDER BY ssort ASC");
        if(checksubmit('submit')){
            if(!empty($_GPC['thisid'])){
                if(!empty($_GPC['old'])){
                    foreach($_GPC['old'] as $key => $thisid){
                        if(!empty($thisid)){
                            $thisid = trim($_GPC['thisid'][$key]);
                            $title  = trim($_GPC['custom_title'][$key]);
                            $ssort  = trim($_GPC['custom_ssort'][$key]);
                            $data   = array(
                                'weid'       => $weid,
                                'schoolid'   => $schoolid,
                                'setid'      => $setid,
                                'title'      => $title,
                                'icon1title' => trim($_GPC['custom_title1'][$key]),
                                'icon2title' => trim($_GPC['custom_title2'][$key]),
                                'icon3title' => trim($_GPC['custom_title3'][$key]),
                                'icon4title' => trim($_GPC['custom_title4'][$key]),
                                'icon5title' => trim($_GPC['custom_title5'][$key]),
                                'icon1'      => trim($_GPC['custom_icon1'][$key]),
                                'icon2'      => trim($_GPC['custom_icon2'][$key]),
                                'icon3'      => trim($_GPC['custom_icon3'][$key]),
                                'icon4'      => trim($_GPC['custom_icon4'][$key]),
                                'icon5'      => trim($_GPC['custom_icon5'][$key]),
                                'ssort'      => $ssort,
                                'type'       => 2
                            );
                            pdo_update($this->table_scicon, $data, array('id' => $thisid));
                        }
                    }
                }
                if(!empty($_GPC['new'])){
                    foreach($_GPC['new'] as $key => $title){
                        $title = trim($_GPC['custom_title-new'][$key]);
                        $data  = array(
                            'weid'       => $weid,
                            'schoolid'   => $schoolid,
                            'setid'      => $setid,
                            'title'      => $title,
                            'icon1title' => trim($_GPC['custom_title1-new'][$key]),
                            'icon2title' => trim($_GPC['custom_title2-new'][$key]),
                            'icon3title' => trim($_GPC['custom_title3-new'][$key]),
                            'icon4title' => trim($_GPC['custom_title4-new'][$key]),
                            'icon5title' => trim($_GPC['custom_title5-new'][$key]),
                            'icon1'      => trim($_GPC['custom_icon1-new'][$key]),
                            'icon2'      => trim($_GPC['custom_icon2-new'][$key]),
                            'icon3'      => trim($_GPC['custom_icon3-new'][$key]),
                            'icon4'      => trim($_GPC['custom_icon4-new'][$key]),
                            'icon5'      => trim($_GPC['custom_icon5-new'][$key]),
                            'ssort'      => trim($_GPC['custom_ssort-new'][$key]),
                            'type'       => 2
                        );
                        pdo_insert($this->table_scicon, $data);
                    }
                }
            }else{
                foreach($_GPC['custom_title-new'] as $key => $title){
                    $title = trim($title);
                    $data  = array(
                        'weid'       => $weid,
                        'schoolid'   => $schoolid,
                        'setid'      => $setid,
                        'title'      => $title,
                        'icon1title' => trim($_GPC['custom_title1-new'][$key]),
                        'icon2title' => trim($_GPC['custom_title2-new'][$key]),
                        'icon3title' => trim($_GPC['custom_title3-new'][$key]),
                        'icon4title' => trim($_GPC['custom_title4-new'][$key]),
                        'icon5title' => trim($_GPC['custom_title5-new'][$key]),
                        'icon1'      => trim($_GPC['custom_icon1-new'][$key]),
                        'icon2'      => trim($_GPC['custom_icon2-new'][$key]),
                        'icon3'      => trim($_GPC['custom_icon3-new'][$key]),
                        'icon4'      => trim($_GPC['custom_icon4-new'][$key]),
                        'icon5'      => trim($_GPC['custom_icon5-new'][$key]),
                        'ssort'      => trim($_GPC['custom_ssort-new'][$key]),
                        'type'       => 2
                    );
                    pdo_insert($this->table_scicon, $data);
                }
            }
            $this->imessage('操作成功！', $this->createWebUrl('shouceset', array('op' => 'display', 'schoolid' => $schoolid)), 'success');
        }
    }
}elseif($operation == 'delitemiconset'){
    $id = intval($_GPC['id']);
    pdo_delete($this->table_scicon, array('id' => $id));
    $data ['result'] = true;
    $data ['msg']    = '删除成功！';
    die (json_encode($data));
}elseif($operation == 'post1'){
    load()->func('tpl');
    $id = intval($_GPC['id']);
    if(!empty($id)){
        $item = pdo_fetch("SELECT * FROM " . tablename($this->table_scpy) . " WHERE id = '$id'");
    }else{
        $item = array(
            'ssort' => 0,
        );
    }
    if(checksubmit('submit')){
        if(empty($_GPC['ssort'])){
            $this->imessage('抱歉，请输入排序！');
        }
        if(empty($_GPC['title'])){
            $this->imessage('请输入评语内容');
        }
        $data = array(
            'weid'       => $weid,
            'schoolid'   => $_GPC['schoolid'],
            'title'      => trim($_GPC['title']),
            'ssort'      => intval($_GPC['ssort']),
            'createtime' => time()
        );
        if(!empty($id)){
            pdo_update($this->table_scpy, $data, array('id' => $id));
        }else{
            pdo_insert($this->table_scpy, $data);
        }
        $this->imessage('操作成功！@', $this->createWebUrl('shouceset', array('op' => 'pylist', 'schoolid' => $schoolid)), 'success');
    }
}elseif($operation == 'post'){
    load()->func('tpl');
    $id = intval($_GPC['id']);
    if(!empty($id)){
        $item = pdo_fetch("SELECT * FROM " . tablename($this->table_scset) . " WHERE id = '$id'");
    }else{
        $item = array(
            'ssort' => 0,
        );
    }
    if(checksubmit('submit')){
        if(empty($_GPC['title'])){
            $this->imessage('抱歉，请输入名称！');
        }
        if(empty($_GPC['icon'])){
            $this->imessage('请上传小图标');
        }
        if($_GPC['allowshare'] == 1){
            if(empty($_GPC['bg1'])){
                $this->imessage('请上传分享背景图1');
            }
            if(empty($_GPC['bg2'])){
                $this->imessage('请上传分享背景图2');
            }
            if(empty($_GPC['bg3'])){
                $this->imessage('请上传分享背景图3');
            }
            if(empty($_GPC['bg4'])){
                $this->imessage('请上传分享背景图4');
            }
            if(empty($_GPC['bg5'])){
                $this->imessage('请上传分享背景图5');
            }
            if(empty($_GPC['bg6'])){
                $this->imessage('请上传分享背景图6');
            }
            if(empty($_GPC['top3'])){
                $this->imessage('请上传第四页顶部图');
            }
            if(empty($_GPC['top4'])){
                $this->imessage('请上传第五页顶部图');
            }
            if(empty($_GPC['top5'])){
                $this->imessage('请上传尾页顶部图');
            }
            if(empty($_GPC['bottext'])){
                $this->imessage('请输入尾页按钮文字');
            }
            if(empty($_GPC['bottext'])){
                $this->imessage('请输入尾页引导文字');
            }
        }
        if(empty($_GPC['top1'])){
            $this->imessage('请上传第二页顶部图');
        }
        if(empty($_GPC['top2'])){
            $this->imessage('请上传第三页顶部图');
        }
        $data = array(
            'weid'       => $weid,
            'schoolid'   => $_GPC['schoolid'],
            'title'      => $_GPC['title'],
            'icon'       => trim($_GPC['icon']),
            'bg1'        => trim($_GPC['bg1']),
            'bg2'        => trim($_GPC['bg2']),
            'bg3'        => trim($_GPC['bg3']),
            'bg4'        => trim($_GPC['bg4']),
            'bg5'        => trim($_GPC['bg5']),
            'bg6'        => trim($_GPC['bg6']),
            'top1'       => trim($_GPC['top1']),
            'top2'       => trim($_GPC['top2']),
            'top3'       => trim($_GPC['top3']),
            'top4'       => trim($_GPC['top4']),
            'top5'       => trim($_GPC['top5']),
            'bottext'    => trim($_GPC['bottext']),
            'boturl'     => trim($_GPC['boturl']),
            'lasttxet'   => trim($_GPC['lasttxet']),
            'guidword1'  => trim($_GPC['guidword1']),
            'guidword2'  => trim($_GPC['guidword2']),
            'guidurl'    => trim($_GPC['guidurl']),
            'bgm'        => trim($_GPC['bgm']),
            'nj_id'      => trim($_GPC['nj_id']),
            'ssort'      => intval($_GPC['ssort']),
            'allowshare' => intval($_GPC['allowshare']),
            'createtime' => time()
        );
        if(!empty($id)){
            pdo_update($this->table_scset, $data, array('id' => $id));
        }else{
            pdo_insert($this->table_scset, $data);
        }
        $this->imessage('操作成功！!!', $this->createWebUrl('shouceset', array('op' => 'display', 'schoolid' => $schoolid)), 'success');
    }
}elseif($operation == 'delete1'){
    $id       = intval($_GPC['id']);
    $theclass = pdo_fetch("SELECT id FROM " . tablename($this->table_scpy) . " WHERE id = '$id'");
    if(empty($theclass['id'])){
        message('抱歉，本条不存在或是已经被删除！', $this->createWebUrl('shouceset', array('op' => 'pylist', 'schoolid' => $schoolid)), 'error');
    }
    pdo_delete($this->table_scpy, array('id' => $id));
    $this->imessage('删除成功！', $this->createWebUrl('shouceset', array('op' => 'pylist', 'schoolid' => $schoolid)), 'success');
}elseif($operation == 'delete'){
    $id       = intval($_GPC['id']);
    $theclass = pdo_fetch("SELECT id FROM " . tablename($this->table_scset) . " WHERE id = '$id'");
    if(empty($theclass['id'])){
        message('抱歉，本规则不存在或是已经被删除！', $this->createWebUrl('shouceset', array('op' => 'display', 'schoolid' => $schoolid)), 'error');
    }
    pdo_delete($this->table_scset, array('id' => $id));
    $this->imessage('删除成功！', $this->createWebUrl('shouceset', array('op' => 'display', 'schoolid' => $schoolid)), 'success');
}
include $this->template('web/shouceset');
?>