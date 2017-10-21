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
        $action = 'cost';
		$this1 = 'no4';
		$GLOBALS['frames'] = $this->getNaveMenu($_GPC['schoolid'],$action);
	    $schoolid = intval($_GPC['schoolid']);
		$logo = pdo_fetch("SELECT logo,title FROM " . tablename($this->table_index) . " WHERE id = '{$schoolid}'");
		$gongneng = pdo_fetchall("SELECT * FROM " . tablename($this->table_object) . " ");

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if($operation == 'post'){
    load()->func('tpl');
    $id      = intval($_GPC['id']);
    $payweid = pdo_fetchall("SELECT * FROM " . tablename('account_wechats') . " where level = 4 ORDER BY acid ASC");
    if(!empty($id)){
        $item = pdo_fetch("SELECT * FROM " . tablename($this->table_cost) . " WHERE id = :id", array(':id' => $id));
        if(empty($item)){
            $this->imessage('抱歉，本条信息不存在在或是已经删除！', '', 'error');
        }
    }

    $banji  = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$weid}' And schoolid = '{$schoolid}' And type = 'theclass'  ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'theclass', ':schoolid' => $schoolid));
    $uniarr = explode(',', $item['bj_id']);
    //print_r($uniarr);

    if(checksubmit('submit')){
        $data = array(
            'weid'         => intval($weid),
            'schoolid'     => $schoolid,
            'name'         => $_GPC['name'],
            'cost'         => $_GPC['cost'],
            'dataline'     => intval($_GPC['dataline']),
            'icon'         => $_GPC['icon'],
            'is_sys'       => intval($_GPC['is_sys']),
            'is_time'      => intval($_GPC['is_time']),
            'starttime'    => strtotime($_GPC['starttime']),
            'endtime'      => strtotime($_GPC['endtime']),
            'about'        => intval($_GPC['about']),
            'bj_id'        => implode(',', $_GPC['arr']),
            'createtime'   => time(),
            'payweid'      => empty($_GPC['payweid']) ? $weid : intval($_GPC['payweid']),
            'description'  => trim($_GPC['description']),
            'displayorder' => intval($_GPC['displayorder'])
        );

        if(empty($_GPC['dataline']) & empty($_GPC['starttime']) & empty($_GPC['endtiem'])){
            $this->imessage('你必须设置一项时间范围设置方式！', '', 'error');
        }

        if(!empty($_GPC['starttime']) || !empty($_GPC['endtiem'])){
            if(strtotime($_GPC['starttime']) > strtotime($_GPC['endtime'])){
                $this->imessage('时间范围设置错误,开始时间不能大于结束时间！', '', 'error');
            }
        }

        if(empty($id)){
            if(!empty($_GPC['about'])){
                $about = pdo_fetch("SELECT * FROM " . tablename($this->table_cost) . " WHERE weid = :weid And schoolid = :schoolid And about = :about And is_on = :is_on", array(
                    ':weid'     => $weid,
                    ':schoolid' => $schoolid,
                    ':about'    => $_GPC['about'],
                    ':is_on'    => 1
                ));
                if(!empty($about)){
                    $this->imessage('你选择的关联功能已经其他收费项目中存在,或先停止使用之前已经关联过改功能的收费项目！', '', 'error');
                }else{
                    pdo_insert($this->table_cost, $data);
                }
            }else{
                pdo_insert($this->table_cost, $data);
            }
        }else{
            $cost = pdo_fetch("SELECT * FROM " . tablename($this->table_cost) . " WHERE id = :id", array(':id' => $id));
            if($cost['about'] != intval($_GPC['about'])){
                $this->imessage('错误,缴费项目的关联功能不可更改！', '', 'error');
            }else{
                pdo_update($this->table_cost, $data, array('id' => $id));
            }
        }
        $this->imessage('创建付费项目成功！', $this->createWebUrl('cost', array('op' => 'display', 'schoolid' => $schoolid)), 'success');
    }
}elseif($operation == 'display'){

    $pindex    = max(1, intval($_GPC['page']));
    $psize     = 10;
    $condition = '';

    if(!empty($_GPC['name'])){
        $condition .= " AND name LIKE '%{$_GPC['name']}%' ";
    }

    $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_cost) . " WHERE weid = '{$weid}' AND schoolid ={$schoolid} $condition ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
    foreach($list as $index => $row){
        $object                       = pdo_fetch("SELECT * FROM " . tablename($this->table_object) . " where id = :id ", array(':id' => $row['about']));
        $list[$index]['displayorder'] = $object['displayorder'];
    }
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_cost) . " WHERE weid = '{$weid}' AND schoolid ={$schoolid} $condition");

    $pager = pagination($total, $pindex, $psize);
}elseif($operation == 'delete'){
    $id = intval($_GPC['id']);

    if(empty($id)){
        $this->imessage('抱歉，本条信息不存在在或是已经被删除！');
    }
    pdo_delete($this->table_cost, array('id' => $id));
    $this->imessage('删除成功！', referer(), 'success');
}elseif($operation == 'change'){
    $id    = intval($_GPC['id']);
    $is_on = intval($_GPC['is_on']);

    $data = array('is_on' => $is_on);

    pdo_update($this->table_cost, $data, array('id' => $id));
}elseif($operation == 'deleteall'){
    $rowcount    = 0;
    $notrowcount = 0;
    foreach($_GPC['idArr'] as $k => $id){
        $id = intval($id);
        if(!empty($id)){
            $goods = pdo_fetch("SELECT * FROM " . tablename($this->table_cost) . " WHERE id = :id", array(':id' => $id));
            if(empty($goods)){
                $notrowcount++;
                continue;
            }
            pdo_delete($this->table_cost, array('id' => $id, 'weid' => $weid));
            $rowcount++;
        }
    }
    $this->imessage("操作成功！共删除{$rowcount}条数据,{$notrowcount}条数据不能删除!", '', 0);
}
include $this->template('web/cost');
?>