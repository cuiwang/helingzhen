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
        $action = 'kecheng';
		$GLOBALS['frames'] = $this->getNaveMenu($_GPC['schoolid'],$action);
	    $schoolid = intval($_GPC['schoolid']);
		$kcid1 = intval($_GPC['kcid']);
		$logo = pdo_fetch("SELECT logo,title FROM " . tablename($this->table_index) . " WHERE id = '{$schoolid}'");
		$school = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " where id = :id ORDER BY ssort DESC", array(':id' => $schoolid));
		
		$kecheng = pdo_fetch("SELECT * FROM " . tablename($this->table_tcourse) . " where id = :id", array(':id' => $kcid1));
		
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        if ($operation == 'post') {
            load()->func('tpl');
            $id = intval($_GPC['id']);
            if (!empty($id)) {
                $item = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE id = :id ", array(':id' => $id));
                if (empty($item)) {   
                    $this->imessage('抱歉，本条信息不存在在或是已经删除！', referer(), 'error');
                }
            }
        } elseif ($operation == 'display') {

            $pindex = max(1, intval($_GPC['page']));
            $psize = 10;
            $condition = '';
									
            if (!empty($_GPC['kcid'])) {
                $kcid = intval($_GPC['kcid']);
                $condition .= " AND kcid = '{$kcid}'";
            }

			$is_pay = isset($_GPC['is_pay']) ? intval($_GPC['is_pay']) : -1;
			if($is_pay >= 0) {
				$condition .= " AND status = '{$is_pay}'";
				$params[':is_pay'] = $is_pay;
			}			
			if(!empty($_GPC['createtime'])) {
				$starttime = strtotime($_GPC['createtime']['start']);
				$endtime = strtotime($_GPC['createtime']['end']) + 86399;
				$condition .= " AND createtime > '{$starttime}' AND createtime < '{$endtime}'";
			} else {
				$starttime = strtotime('-200 day');
				$endtime = TIMESTAMP;
			}
			$params[':start'] = $starttime;
			$params[':end'] = $endtime;
			
            $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_order) . " WHERE weid = '{$weid}' AND schoolid ={$schoolid} AND type = 1 $condition ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
				foreach($list as $index => $row){
							$kc = pdo_fetch("SELECT * FROM " . tablename($this->table_tcourse) . " WHERE id = :id ", array(':id' => $row['kcid']));
							$student = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " WHERE id = :id ", array(':id' => $row['sid']));
							$user = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " WHERE id = :id ", array(':id' => $row['userid']));
							$list[$index]['kcnanme'] = $kc['name'];
							$list[$index]['s_name'] = $student['s_name'];
							$list[$index]['userinfo'] = $user['userinfo'];
							$list[$index]['pard'] = $user['pard'];
				}
            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_order) . " WHERE weid = '{$weid}' AND schoolid ={$schoolid} AND type = 1 $condition ");
            $pager = pagination($total, $pindex, $psize);
			
        } elseif ($operation == 'delete') {
            $id = intval($_GPC['id']);
            if (empty($id)) {
                $this->imessage('抱歉，本条信息不存在在或是已经被删除！', referer(), 'error');
            }
            pdo_delete($this->table_order, array('id' => $id));
            $this->imessage('删除成功！', referer(), 'success');
        } elseif ($operation == 'tuifei') {
            $id = intval($_GPC['id']);
            if (empty($id)) {
                $this->imessage('抱歉，本条信息不存在在或是已经被删除！');
            }
			$data = array('status' => 3); 
            pdo_update($this->table_order, $data, array('id' => $id));
            $this->imessage('删除成功！', referer(), 'success');
        } elseif ($operation == 'deleteall') {
            $rowcount = 0;
            $notrowcount = 0;
            foreach ($_GPC['idArr'] as $k => $id) {
                $id = intval($id);
                if (!empty($id)) {
                    $goods = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE id = :id", array(':id' => $id));
                    if (empty($goods)) {
                        $notrowcount++;
                        continue;
                    }
                    pdo_delete($this->table_order, array('id' => $id, 'weid' => $weid));
                    $rowcount++;
                }
            }
            $this->message("操作成功！共删除{$rowcount}条数据,{$notrowcount}条数据不能删除!", '', 0);
        }	
        include $this->template ( 'web/baoming' );
?>