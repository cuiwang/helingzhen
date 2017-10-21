<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
        global $_GPC, $_W;
       
        $weid = $_W['uniacid'];
        $action = 'payall';
		$this1 = 'no4';
		$GLOBALS['frames'] = $this->getNaveMenu($_GPC['schoolid'],$action);
	    $schoolid = intval($_GPC['schoolid']);
		$logo = pdo_fetch("SELECT logo,title FROM " . tablename($this->table_index) . " WHERE id = '{$schoolid}'");
		$school = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " where id = :id ", array(':id' => $schoolid));
						
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        if ($operation == 'post') {
            load()->func('tpl');
            $id = intval($_GPC['id']);
            if (!empty($id)) {
                $item = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE id = :id ", array(':id' => $id));
                if (empty($item)) {   
                    $this->imessage('抱歉，本条信息不存在在或是已经删除！', '', 'error');
                }
            }
        } elseif ($operation == 'display') {
			$allkc = pdo_fetchall("SELECT * FROM " . tablename($this->table_tcourse) . " WHERE weid = :weid And schoolid = :schoolid ", array(
					':weid' => $weid,
					':schoolid' => $schoolid
					));
			$allob = pdo_fetchall("SELECT * FROM " . tablename($this->table_cost) . " WHERE weid = :weid And schoolid = :schoolid ", array(
					':weid' => $weid,
					':schoolid' => $schoolid
					));
            $pindex = max(1, intval($_GPC['page']));
            $psize = 15;
            $condition = '';
            if (!empty($_GPC['number'])) {
                $condition .= " AND id = '{$_GPC['number']}'";
            }			
			if ($_GPC['type'] ==1) {
				$type = intval($_GPC['type']);
				$condition .= " AND type = '{$type}' ";
			}
			if ($_GPC['type'] ==2) {
				$type = intval($_GPC['type']);
				$condition .= " AND type = '{$type}' ";
			}
			if ($_GPC['type'] ==3) {
				$type = intval($_GPC['type']);
				$condition .= " AND type = '{$type}' ";
			}
			if ($_GPC['type'] ==4) {
				$type = intval($_GPC['type']);
				$condition .= " AND type = '{$type}' ";
			}
			if ($_GPC['type'] ==5) {
				$type = intval($_GPC['type']);
				$condition .= " AND type = '{$type}' ";
			}			
			if ($_GPC['paytype'] ==1) {
				$paytype = intval($_GPC['paytype']);
				$condition .= " AND paytype = '{$paytype}' ";
			}
			if ($_GPC['paytype'] ==2) {
				$paytype = intval($_GPC['paytype']);
				$condition .= " AND paytype = '{$paytype}' ";
			}									
            if (!empty($_GPC['obid'])) {
                $obid = intval($_GPC['obid']);
                $condition .= " AND costid = '{$obid}'";
            }
            if (!empty($_GPC['kcid'])) {
                $condition .= " AND kcid = '{$_GPC['kcid']}'";
            }
            if (!empty($_GPC['costid'])) {
                $condition .= " AND costid = '{$_GPC['costid']}'";
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
            if (!empty($_GPC['keyword'])) {
				$students = pdo_fetch("SELECT id FROM " . tablename($this->table_students) . " WHERE schoolid = :schoolid And s_name = :s_name ", array(':schoolid' => $schoolid,':s_name' => $_GPC['keyword']));
                $condition .= " AND sid = '{$students['id']}'";
            }			
			$params[':start'] = $starttime;
			$params[':end'] = $endtime;
            $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_order) . " WHERE weid = '{$weid}' AND schoolid ={$schoolid} $condition ORDER BY paytime DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
				foreach($list as $index => $row){
							$kc = pdo_fetch("SELECT * FROM " . tablename($this->table_tcourse) . " WHERE id = :id ", array(':id' => $row['kcid']));
							$student = pdo_fetch("SELECT s_name,bj_id FROM " . tablename($this->table_students) . " WHERE id = :id ", array(':id' => $row['sid']));
							$bjinfo = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = :sid ", array(':sid' => $student['bj_id']));
							$user = pdo_fetch("SELECT userinfo,pard FROM " . tablename($this->table_user) . " WHERE id = :id ", array(':id' => $row['userid']));
							$ob = pdo_fetch("SELECT name FROM " . tablename($this->table_cost) . " WHERE id = :id ", array(':id' => $row['costid']));
							$signup = pdo_fetch("SELECT * FROM " . tablename($this->table_signup) . " WHERE orderid = :orderid ", array(':orderid' => $row['id']));
							$payweid = pdo_fetch("SELECT name FROM " . tablename('account_wechats') . " where uniacid = :uniacid ", array(':uniacid' => $row['payweid']));
							$list[$index]['kcname'] = $kc['name'];
							$list[$index]['s_name'] = $student['s_name'];
							$list[$index]['userinfo'] = $user['userinfo'];
							$list[$index]['pard'] = $user['pard'];
							$list[$index]['obname'] = $ob['name'];
							$list[$index]['signname'] = $signup['name'];
							$list[$index]['signpard'] = $signup['pard'];
							$list[$index]['signmob'] = $signup['mobile'];
							$list[$index]['bjname'] = $bjinfo['sname'];
							$list[$index]['payweidname'] = $payweid['name'];
				}
            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_order) . " WHERE weid = '{$weid}' AND schoolid ={$schoolid} $condition");

            $pager = pagination($total, $pindex, $psize);			
			//////////导出数据/////////////////
			if ($_GPC['out_put'] == 'output') {
				$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_order) . " WHERE weid = '{$weid}' AND schoolid ={$schoolid} $condition ORDER BY id DESC");
                $ii = 0;
               foreach($list as $index => $row){
							$kc = pdo_fetch("SELECT * FROM " . tablename($this->table_tcourse) . " WHERE id = :id ", array(':id' => $row['kcid']));
							$student = pdo_fetch("SELECT s_name,bj_id,numberid FROM " . tablename($this->table_students) . " WHERE id = :id ", array(':id' => $row['sid']));
							$bjinfo = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = :sid ", array(':sid' => $student['bj_id']));
							$user = pdo_fetch("SELECT userinfo,pard FROM " . tablename($this->table_user) . " WHERE id = :id ", array(':id' => $row['userid']));
							$ob = pdo_fetch("SELECT name FROM " . tablename($this->table_cost) . " WHERE id = :id ", array(':id' => $row['costid']));
							$signup = pdo_fetch("SELECT * FROM " . tablename($this->table_signup) . " WHERE orderid = :orderid ", array(':orderid' => $row['id']));
							$arr[$ii]['id'] = $row['id'];
							if($row['type']==4){
								$kcname = '报名费';
							}else if($row['type']==5){
								$kcname = '考勤卡费';
							}else{
								$kcname = $ob['name'].$kc['name'];
							}
							$arr[$ii]['project'] = $kcname;
							$arr[$ii]['student'] = ($row['type']!=4)?$student['s_name']:$signup['name'];
							$arr[$ii]['xuehao'] = !empty($student['numberid'])? $student['numberid']:'未填写';
							$arr[$ii]['classname'] = $bjinfo['sname'];
							$myuser = iunserializer($user['userinfo']);
							if(is_array($myuser)){
								$arr[$ii]['signpard'] = !empty($myuser['name'])? $myuser['name']:'未填写';
								$arr[$ii]['mobile'] = !empty($myuser['mobile'])? $myuser['mobile']:'未填写';
							}else{
								$arr[$ii]['signpard'] = !empty($myuser['name'])? $myuser['name']:'未填写';
								$arr[$ii]['mobile'] = !empty($myuser['mobile'])? $myuser['mobile']:'未填写';								
							}
							$arr[$ii]['paytime'] = date('Y年m月d日 h:i:sa',$row['paytime']);
							$arr[$ii]['cose'] = $row['cose'];
							if($row['status']=='1'){
								$status = '未支付';
							}
							else if($row['status']=='2'){
								$status = '已支付';
							}
							else if($row['status']=='3'){
								$status = '已退款';
							}
							$arr[$ii]['status'] = $status;
							if($row['pay_type']=='wechat'){
								$pay_type = '微信支付';
							}
							else if($row['pay_type']=='alipay'){
								$pay_type = '支付宝';
							}
							else if($row['pay_type']=='baifubao'){
								$pay_type = '百付宝';
							}
							else if($row['pay_type']=='unionpay'){
								$pay_type = '银联';
							}
							else if($row['pay_type']=='cash'){
								$pay_type = '现金支付';
							}
							else if($row['pay_type']=='credit'){
								$pay_type = '余额支付';
							}
							$arr[$ii]['pay_type'] = $pay_type;
							if($row['paytype']=='1'){
								$paytype = '在线支付';
							}
							else if($row['paytype']=='2'){
								$paytype = '现金支付';
							}
							$arr[$ii]['paytype'] = $paytype;
							if ($_W['isfounder'] || $_W['role'] == 'owner'){
								$payweid = pdo_fetch("SELECT name FROM " . tablename('account_wechats') . " where uniacid = :uniacid ", array(':uniacid' => $row['payweid']));
								$arr[$ii]['payacuont'] = $payweid['name'];
							}
							$ii++;
				}
				//echo "<pre>";print_r($arr);exit;
				if ($_W['isfounder'] || $_W['role'] == 'owner'){
					$this->exportexcel($arr, array('订单号','项目名','学生','学号','班级','缴费人','手机号','付费时间','金额','支付状态','支付方式','支付类型','收款公众号'), time());
				}else{
					$this->exportexcel($arr, array('订单号','项目名','学生','学号','班级','缴费人','手机号','付费时间','金额','支付状态','支付方式','支付类型'), time());
				}
                exit();
            }
			////////////////////////////////			

        } elseif ($operation == 'tuifei') {
            $id = intval($_GPC['id']);
            if (empty($id)) {
                $this->imessage('抱歉，本条信息不存在在或是已经被删除！');
            }
			$data = array('status' => 3); 
            pdo_update($this->table_order, $data, array('id' => $id));
            $this->imessage('操作成功！', referer(), 'success');
        } elseif ($operation == 'pay') {
            $id = intval($_GPC['id']);
            if (empty($id)) {
                $this->imessage('抱歉，本条信息不存在在或是已经被删除！');
            }
			$data = array('status' => 2,'paytime' => time(),'paytype' => 2,'pay_type' => 'cash'); 
            pdo_update($this->table_order, $data, array('id' => $id));
            $this->imessage('操作成功！', referer(), 'success');
        } elseif ($operation == 'unpay') {
            $id = intval($_GPC['id']);
            if (empty($id)) {
                $this->imessage('抱歉，本条信息不存在在或是已经被删除！');
            }
			$data = array('status' => 1,'paytime' => '','paytype' => 3,'pay_type' => 'no'); 
            pdo_update($this->table_order, $data, array('id' => $id));
            $this->imessage('操作成功！', referer(), 'success');
        } elseif ($operation == 'delete') {
            $id = intval($_GPC['id']);
            if (empty($id)) {
                $this->imessage('抱歉，本条信息不存在在或是已经被删除！');
            }
            pdo_delete($this->table_order, array('id' => $id));
            $this->imessage('操作成功！', referer(), 'success');
        } elseif ($operation == 'payallorder') {
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
					$data = array('status' => 2,'paytime' => time(),'paytype' => 2,'pay_type' => 'cash');
                    pdo_update($this->table_order, $data, array('id' => $id));
                    $rowcount++;
                }
            }
            message("操作成功！");
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
            message("操作成功！");
        }	
        include $this->template ( 'web/payall' );
?>