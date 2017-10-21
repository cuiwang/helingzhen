<?php
/**
 * By 高贵血迹
 */

	global $_GPC, $_W;

	$weid = $_GPC['i'];
	$schoolid = $_GPC['schoolid'];
	$macid = $_GPC['macid'];
	$cardid = $_GPC['sKH'];
	$pic = trim($_GPC['sImg']);
	$time = $_GPC['sDateYMDHMS'];
	$school = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " WHERE id = {$schoolid} ");
	$ckmac = pdo_fetch("SELECT * FROM " . tablename($this->table_checkmac) . " WHERE macid = {$macid} And weid = {$weid} And schoolid = {$schoolid} ");
	$ckuser = pdo_fetch("SELECT * FROM " . tablename($this->table_idcard) . " WHERE idcard = '{$cardid}' And weid = {$weid} And schoolid = {$schoolid} ");
	if($school['is_recordmac'] == 1){
		if($ckmac['is_on'] ==1){
			if(!empty($ckuser)){
				$times = TIMESTAMP;
				$nowtime = date('H:i',$times);
				if($ckmac['type'] !=0){
					include 'checktime2.php';
				}else{
					include 'checktime.php';
				}
				$bj = pdo_fetch("SELECT bj_id FROM " . tablename($this->table_students) . " WHERE id = {$ckuser['sid']} ");
					$picurl = "";
					if(!empty($ckuser['sid'])){
						if($school['is_cardpay'] == 1){
							if($ckuser['severend'] > $times){
								$data = array(
								'weid' => $weid,
								'schoolid' => $schoolid,
								'macid' => $ckmac['id'],
								'cardid' => $cardid,
								'sid' => $ckuser['sid'],
								'bj_id' => $bj['bj_id'],
								'type' => $type,
								'pic' => $pic,
								'leixing' => $leixing,
								'pard' => $ckuser['pard'],
								'createtime' => time()
								);
								pdo_insert($this->table_checklog, $data);
								$checkid = pdo_insertid();
								$this->sendMobileJxlxtz($schoolid, $weid, $bj['bj_id'], $ckuser['sid'], $type, $leixing, $checkid, $ckuser['pard']);
								$arr = "Check OK!";
							}else{
								$arr = "This card severtime is out!";
							}
						}else{
							$data = array(
							'weid' => $weid,
							'schoolid' => $schoolid,
							'macid' => $ckmac['id'],
							'cardid' => $cardid,
							'sid' => $ckuser['sid'],
							'bj_id' => $bj['bj_id'],
							'type' => $type,
							'pic' => $pic,
							'leixing' => $leixing,
							'pard' => $ckuser['pard'],
							'createtime' => time()
							);
							pdo_insert($this->table_checklog, $data);
							$checkid = pdo_insertid();
							$this->sendMobileJxlxtz($schoolid, $weid, $bj['bj_id'], $ckuser['sid'], $type, $leixing, $checkid, $ckuser['pard']);
							$arr = "Check OK!";
						}
					}
					if(!empty($ckuser['tid'])){
						$data = array(
						'weid' => $weid,
						'schoolid' => $schoolid,
						'macid' => $macid,
						'cardid' => $cardid,
						'pic' => $pic,
						'tid' => $ckuser['tid'],
						'type' => $type,
						'leixing' => $leixing,
						'pard' => 1,
						'createtime' => time()
						);
						pdo_insert($this->table_checklog, $data);
						$arr = "Check OK!";
					}
			}else{
				$arr = "No this card!";
			}
		}else{
			$arr = "No this mac!";
		}
	}else{
		$arr = "This mac is closed!";
	}
	$fmdata = array(
	"Upload" => $arr,
	);
	echo json_encode($fmdata);
	exit();
?>