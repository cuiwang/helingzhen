<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
        global $_GPC, $_W;
       
        $weid = $_W['uniacid'];
        $action = 'kecheng';
		$this1 = 'no2';
		$GLOBALS['frames'] = $this->getNaveMenu($_GPC['schoolid'],$action);
	    $schoolid = intval($_GPC['schoolid']);
		$logo = pdo_fetch("SELECT logo,title FROM " . tablename($this->table_index) . " WHERE id = '{$schoolid}'");
		$school = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " where id = :id ", array(':id' => $schoolid));

		$it = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " WHERE sid = :sid", array(':sid' => $sid));
		$xueqi = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$weid}' AND schoolid ={$schoolid} And type = 'semester' ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'semester', ':schoolid' => $schoolid));		
		$km = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$weid}' AND schoolid ={$schoolid} And type = 'subject' ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'subject', ':schoolid' => $schoolid));
		$bj = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$weid}' AND schoolid ={$schoolid} And type = 'theclass' ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'theclass', ':schoolid' => $schoolid));
		$xq = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$weid}' AND schoolid ={$schoolid} And type = 'week' ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'week', ':schoolid' => $schoolid));
		$sd = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$weid}' AND schoolid ={$schoolid} And type = 'timeframe' ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'timeframe', ':schoolid' => $schoolid));
		$qh = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$weid}' AND schoolid ={$schoolid} And type = 'score' ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'score', ':schoolid' => $schoolid));

        $category = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " WHERE weid =  '{$weid}' AND schoolid ={$schoolid} ORDER BY sid ASC, ssort DESC", array(':weid' => $weid, ':schoolid' => $schoolid), 'sid');

        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        if ($operation == 'post') {
            load()->func('tpl');
            $id = intval($_GPC['id']);
			$payweid = pdo_fetchall("SELECT * FROM " . tablename('account_wechats') . " where level = 4 ORDER BY acid ASC");
            if (!empty($id)) {
                $item = pdo_fetch("SELECT * FROM " . tablename($this->table_tcourse) . " WHERE id = :id ", array(':id' => $id));
				$teachers = pdo_fetchall("SELECT * FROM " . tablename ($this->table_teachers) . " where weid = :weid And schoolid = :schoolid ", array(
					':weid' => $weid,
					':schoolid' => $schoolid
				));
                if (empty($item)) {   
                    message('抱歉，本条信息不存在在或是已经删除！', '', 'error');
                }
            }
            if (checksubmit('submit')) {
                $data = array(
				    'weid' => $weid,
					'schoolid' => $schoolid,
					'tid' => intval($_GPC['tid']),
					'xq_id' => trim($_GPC['xq']),
					'km_id' => trim($_GPC['km']),
					'bj_id' => trim($_GPC['bj']),
					'name' => trim($_GPC['name']),
					'minge' => trim($_GPC['minge']),
					'yibao' => trim($_GPC['yibao']),
					'cose' => trim($_GPC['cose']),
					'dagang' => trim($_GPC['dagang']),
					'adrr' => trim($_GPC['adrr']),
					'is_hot' => intval($_GPC['is_hot']),
					'is_show' => intval($_GPC['is_show']),
					'ssort' => intval($_GPC['ssort']),
					'start' => strtotime($_GPC['start']),
					'end' => strtotime($_GPC['end']),
					'payweid' => empty($_GPC['payweid']) ? $weid : intval($_GPC['payweid']),
                );
			
                if (empty($id)) {
                    message('抱歉，本条信息不存在在或是已经删除！', '', 'error');
                } else {
                    pdo_update($this->table_tcourse, $data, array('id' => $id));
                }
                message('修改成功！', $this->createWebUrl('kecheng', array('op' => 'display', 'schoolid' => $schoolid)), 'success');
            }
        } elseif ($operation == 'display') {
            if (checksubmit('submit')) { //排序
                if (is_array($_GPC['ssort'])) {
                    foreach ($_GPC['ssort'] as $id => $val) {
                        $data = array('ssort' => intval($_GPC['ssort'][$id]));
                        pdo_update($this->table_tcourse, $data, array('id' => $id));
                    }
                }
                message('批量修排序成功!', $url);
            }			

            $pindex = max(1, intval($_GPC['page']));
            $psize = 10;
            $condition = '';
			
		    if (!empty($_GPC['name'])) {
                $condition .= " AND name LIKE '%{$_GPC['name']}%' ";
            }
						
            if (!empty($_GPC['bj_id'])) {
                $cid = intval($_GPC['bj_id']);
                $condition .= " AND bj_id = '{$cid}'";
            }	
						
            if (!empty($_GPC['km_id'])) {
                $cid = intval($_GPC['km_id']);
                $condition .= " AND km_id = '{$cid}'";
            }		

            $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_tcourse) . " WHERE weid = '{$weid}' AND schoolid ={$schoolid} $condition ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
				foreach($list as $key => $row){
					$teacher = pdo_fetch("SELECT * FROM " . tablename ($this->table_teachers) . " where id = :id ", array(':id' => $row['tid']));
					$yb = pdo_fetchcolumn("select count(*) FROM ".tablename('wx_school_order')." WHERE kcid = '".$row['id']."' And status = 2 ");
					$allks = pdo_fetchcolumn("select count(*) FROM ".tablename('wx_school_kcbiao')." WHERE kcid = '".$row['id']."'");
					$list[$key]['allks'] = $allks;
					$list[$key]['yib'] = $yb +$row['yibao'];
					$list[$key]['tname'] = $teacher['tname'];
					$list[$key]['shengyu'] = $row['minge'] - $yb;
				}
            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_tcourse) . " WHERE weid = '{$weid}' AND schoolid ={$schoolid} $condition");

            $pager = pagination($total, $pindex, $psize);
        } elseif ($operation == 'delete') {
            $id = intval($_GPC['id']);
            if (empty($id)) {
                message('抱歉，本条信息不存在在或是已经被删除！');
            }
            pdo_delete($this->table_tcourse, array('id' => $id));
            message('删除成功！', referer(), 'success');
        } elseif ($operation == 'deleteall') {
            $rowcount = 0;
            $notrowcount = 0;
            foreach ($_GPC['idArr'] as $k => $id) {
                $id = intval($id);
                if (!empty($id)) {
                    $goods = pdo_fetch("SELECT * FROM " . tablename($this->table_tcourse) . " WHERE id = :id", array(':id' => $id));
                    if (empty($goods)) {
                        $notrowcount++;
                        continue;
                    }
                    pdo_delete($this->table_tcourse, array('id' => $id, 'weid' => $weid));
                    $rowcount++;
                }
            }
            message("操作成功！共删除{$rowcount}条数据,{$notrowcount}条数据不能删除!", '', 0);
        } elseif ($operation == 'add') {
			load()->func('tpl');
            $id = intval($_GPC['id']);
           // $row = pdo_fetch("SELECT id, thumb FROM " . tablename($this->table_tcourse) . " WHERE id = :id", array(':id' => $id));
            if (!empty($id)) {
                $item = pdo_fetch("SELECT * FROM " . tablename($this->table_tcourse) . " WHERE id = :id", array(':id' => $id));	
				$teachers = pdo_fetch("SELECT * FROM " . tablename ($this->table_teachers) . " where id = :id ", array(':id' => $item['tid']));				
                if (empty($item)) {
                    message('抱歉，教师不存在或是已经删除！', '', 'error');
                }
            }
			if (checksubmit('submit')) {
                $data = array(
				    'weid' => $weid,
					'schoolid' => $schoolid,
					'tid' => intval($_GPC['tid']),
					'kcid' => trim($_GPC['kcid']),
					'bj_id' => trim($_GPC['bj_id']),
					'km_id' => trim($_GPC['km_id']),					
					'sd_id' => trim($_GPC['sd']),
					'xq_id' => trim($_GPC['xq']),					
					'nub' => trim($_GPC['nub']),
					'isxiangqing' => trim($_GPC['isxiangqing']),
					'content' => trim($_GPC['content']),
					'date' => strtotime($_GPC['date']),
                );

                if (istrlen($data['nub']) == 0) {
                    message('没有输入编号.', '', 'error');
                }	
										
				pdo_insert($this->table_kcbiao, $data);
					message('操作成功', $this->createWebUrl('kecheng', array('op' => 'display', 'schoolid' => $schoolid)), 'success');    
            }
		}	
        include $this->template ( 'web/kecheng' );
?>