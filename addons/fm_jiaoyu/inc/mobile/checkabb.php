<?php
/**
 * By 高贵血迹
 */

	global $_GPC, $_W;
	

	$operation = in_array ( $_GPC ['op'], array ('default', 'login', 'classinfo', 'check', 'gps', 'banner', 'video', 'start') ) ? $_GPC ['op'] : 'default';
	$weid = $_GPC['i'];
	$schoolid = $_GPC['schoolid'];
	$macid = $_GPC['macid'];
	$ckmac = pdo_fetch("SELECT * FROM " . tablename($this->table_checkmac) . " WHERE macid = '{$macid}' And weid = {$weid} And schoolid = {$schoolid} ");
	$school = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " WHERE id = {$schoolid} ");
	if ($operation == 'default') {
		echo("你是SB吗");
		exit();
    }
	if(empty($school)){
		$result['error'] = true;
		$result['errormsg'] = "找不到本校，设备未关联学校";
		echo json_encode($result);			
	}
	if(empty($ckmac)){
		$result['error'] = true;
		$result['errormsg'] = "没找到设备,请添加设备";
		echo json_encode($result);		
	}	
	if($school['is_recordmac'] == 2){
		$result['error'] = true;
		$result['errormsg'] = "本校无权使用设备,请联系管理员";
		echo json_encode($result);		
	}	
	if ($ckmac['is_on'] == 2){
		$result['error'] = true;
		$result['errormsg'] = "本设备已关闭,请在管理后台打开";
		echo json_encode($result);
	}
	if (empty($_W['setting']['remote']['type'])) { 
		$urls = $_W['SITEROOT'].$_W['config']['upload']['attachdir'].'/'; 
	} else {
		$urls = $_W['attachurl'];
	}
	if ($operation == 'start') {
		if(!empty($ckmac)){
			$result['data'] = array(
				array(
					'school_id' => '',
					'school_name' => $school['title'],
					'province' => '',
					'city' => '',
					'district' => '',
					'weather_id' => '',
					'apikey' => '',
				)			
			);
			echo json_encode($result);
		}	
    }
	
	if ($operation == 'info') {
		if($school['gonggao']){
			$result['data']['position4']['content'] = $school['gonggao'];
		}else{
			$result['error'] = true;
			$result['errormsg'] = "无法校园公告,请先设置";			
		}
		echo json_encode($result);		
    }	

	if ($operation == 'banner') {
		if($ckmac['banner']){
			$banner = unserialize($ckmac['banner']);
			$pictures = [];
			$pictures = '"'.$urls.$banner['pic1'].'"'.'"'.$urls.$banner['pic2'].'"'.'"'.$urls.$banner['pic3'].'"'.'"'.$urls.$banner['pic4'].'"'.'"'.$urls.$banner['pic5'].'"'.;			
			$result['data'] = array(
				'position1' => $urls.$banner['header'],
				'position2' => $urls.$banner['pop'],
				'position3' => array(
					'picture' => $pictures
				)
			);
			$temp = array(
				'isflow' => 2,
				'pop' => $banner['pop'],
				'header' => $banner['header'],
				'pic1' => $banner['pic1'],
				'pic2' => $banner['pic2'],
				'pic3' => $banner['pic3'],
				'pic4' => $banner['pic4'],
				'pic5' => $banner['pic5'],
				);
			$temp1['banner'] = serialize($temp);
			pdo_update($this->table_checkmac, $temp1, array('id' => $ckmac['id']));			
		}else{
			$result['error'] = true;
			$result['errormsg'] = "无法获取幻灯片,请先设置";
		}
		echo json_encode($result);
    }
	
	if ($operation == 'shexiangtou') {
		if(!empty($ckmac['shexiangtou'])){
			$sxt = unserialize($ckmac['shexiangtou']);
			$result['data'] = array(
				array(
					'device_id' => $sxt['device_id'],
					'user_id' => $sxt['user_id'],
					'school_id' => $school['id'],
					'school_name' => $school['title'],
					'pwd' =>  $sxt['pwd'],
				)			
			);
			
		}else{
			$result['error'] = true;
			$result['errormsg'] = "无法获取外接云摄像头,请先设置";			
		}	
		echo json_encode($result);
    }
	
	if ($operation == 'classinfo') {
		$classid = $_GPC['classId'];
		if(!empty($ckmac)){
			$times = TIMESTAMP;		                  
			$users = pdo_fetchall("SELECT idcard as card_id, sid as baby_id, bj_id as CLASS_ID, usertype as USERTYPE, sid as SID, tid as TID, pard as PARD FROM " . tablename($this->table_idcard) . " WHERE weid = '{$weid}' And schoolid = {$school['id']} And is_on = 1 And severend > $times ORDER BY id DESC");
			if($users){
				foreach($users as $key =>$row) {
					if($row['USERTYPE'] == 1){
						$teacher = pdo_fetch("SELECT tname,thumb,sex,birthdate  FROM " . tablename($this->table_teachers) . " WHERE id = '{$row['TID']}' ");
						$users[$key]['baby_id'] = "02" .$row['TID'];
						$parter[$key]['card_name'] = "老师卡";
						$parter[$key]['avatar'] = $school['tpic'];
						$users[$key]['owner_name'] = $teacher['tname']."老师";
						$users[$key]['update_date'] = '';
						$users[$key]['baby_name'] = '';
						$users[$key]['baby_name_for_read'] = '';
						$users[$key]['baby_avatar'] = $teacher['thumb'];
						$users[$key]['baby_birthday'] = date('Y-m-d',$teacher['birthdate']);
						if($row['sex'] ==1){
							$users[$key]['baby_sex'] = 'male';
						}else{
							$users[$key]['baby_sex'] = 'female';
						}
						$users[$key]['baby_class'] = "老师";
					}else{
						$student = pdo_fetch("SELECT s_name,icon,sex,birthdate  FROM " . tablename($this->table_students) . " WHERE id = '{$row['SID']}' ");
						$bjinfo = pdo_fetch("SELECT sname  FROM " . tablename($this->table_classify) . " WHERE sid = '{$row['CLASS_ID']}' ");
						$users[$key]['baby_id'] = $row['baby_id'];
						$parter[$key]['card_name'] = getpardforkqj($row['PARD'])."卡";
						$parter[$key]['avatar'] = $school['spic'];
						if($row['PARD'] == 1){
							$users[$key]['owner_name'] = $student['s_name'];
						}else{
							$users[$key]['owner_name'] = $student['s_name'].getpardforkqj($row['PARD']);
						}
						$users[$key]['update_date'] = '';
						$users[$key]['baby_name'] = $student['s_name'];
						$users[$key]['baby_name_for_read'] = '';
						$users[$key]['baby_avatar'] = $student['icon'];
						$users[$key]['baby_birthday'] = date('Y-m-d',$student['birthdate']);
						if($row['sex'] ==1){
							$users[$key]['baby_sex'] = 'male';
						}else{
							$users[$key]['baby_sex'] = 'female';
						}
						$users[$key]['baby_class'] = $bjinfo['sname'];
					}			    
					$parter = pdo_fetchall("SELECT idcard,pname,pard,spic FROM " . tablename($this->table_idcard) . " WHERE weid = '{$weid}' And schoolid = {$school['id']} And sid = {$row['SID']} And is_on = 1 ORDER BY id DESC");
						foreach($parter as $k =>$r) {
							if($row['idcard'] !=$r['card_id']){
								$parter[$k]['name'] = $r['pname'].getpardforkqj($r['pard']);
								$parter[$k]['avatar'] = $r['spic'];
							}
							
						}
					$users[$key]['family'] = $parter;
					$result['data']['insertorupdate'] = $users;
				}
			}else{
				$result['error'] = true;
				$result['errormsg'] = "无法同步卡号信息,请先设置绑定考勤卡";				
			}
			echo json_encode($result);
		}
    }
	
	if ($operation == 'login') {
		if(!empty($ckmac)){
			$banner = unserialize($ckmac['banner']);
			$result['returnCode'] = "000";
			$result['getBasic'] = array(
				array(
					'INPRE' => "尊敬的家长您好,您的孩子#name#于#datatime#执卡[#cardId#]进入[设备(#devId#)]区域",
					'VOICEPRE' => $banner['VOICEPRE'],
					'NOTICE' => $banner['pop'],
					'TENANT_ID' => '',
					'ORG_ID' => '',						
					'ORG_NAME' => $school['title'],				
					'ST1' => $school['jxstart'],
					'ST2' => $school['jxend'],
					'ET1' => $school['lxstart'],
					'ET2' => $school['lxend'],
					'SBTIME' => $school['jxend'],
					'XBTIME' => $school['lxstart'],
				)
			);
			$p1 = explode('/',$banner['pic1']);
			$p2 = explode('/',$banner['pic2']);
			$p3 = explode('/',$banner['pic3']);
			$p4 = explode('/',$banner['pic4']);
			$p5 = explode('/',$school['logo']);
			if(!empty($banner['video'])){
				$result['getVideoAndImages'] = array(	
						array(
							'FILE_NAME' => $banner['video'],
							'FILE_PATH' => $banner['video'],
						),	
						array(
							'FILE_NAME' => $p1[4],
							'FILE_PATH' => $banner['pic1'],
						),
						array(
							'FILE_NAME' => $p2[4],
							'FILE_PATH' => $banner['pic2'],
						),
						array(					
							'FILE_NAME' => $p3[4],
							'FILE_PATH' => $banner['pic3'],
						),
						array(					
							'FILE_NAME' => $p4[4],
							'FILE_PATH' => $banner['pic4'],
						),
						array(					
							'FILE_NAME' => $p5[4],
							'FILE_PATH' => $school['logo'],
						),						
				);
			}else{
				$result['getVideoAndImages'] = array(	
						array(					
							'FILE_NAME' => $p1[4],
							'FILE_PATH' => $banner['pic1'],
						),
						array(					
							'FILE_NAME' => $p2[4],
							'FILE_PATH' => $banner['pic2'],
						),
						array(					
							'FILE_NAME' => $p3[4],
							'FILE_PATH' => $banner['pic3'],
						),
						array(					
							'FILE_NAME' => $p4[4],
							'FILE_PATH' => $banner['pic4'],
						),
						array(					
							'FILE_NAME' => $p5[4],
							'FILE_PATH' => $school['logo'],
						),						
				);				
			}
			$temp = array(
				'isflow' => 2,
				'pop' => $banner['pop'],
				'video' => $banner['video'],
				'pic1' => $banner['pic1'],
				'pic1' => $banner['pic1'],
				'pic2' => $banner['pic2'],
				'pic3' => $banner['pic3'],
				'pic4' => $banner['pic4'],
				'VOICEPRE' => $banner['VOICEPRE'],
			);
			$temp1['banner'] = serialize($temp);
			pdo_update($this->table_checkmac, $temp1, array('id' => $ckmac['id']));				
			echo json_encode($result);
		}
    }

	if ($operation == 'check') {
		$fstype = false;
		$ckuser = pdo_fetch("SELECT * FROM " . tablename($this->table_idcard) . " WHERE idcard = '{$_GPC['signId']}' And weid = '{$weid}' And schoolid = '{$schoolid}' ");
		$bj = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " WHERE id = '{$ckuser['sid']}' ");
		if(!empty($ckuser)){
			$times = TIMESTAMP;
			$pic = $_GPC['picurl'];
			$nowtime = date('H:i',$times);
			if($ckmac['type'] !=0){
				include 'checktime2.php';	
			}else{
				include 'checktime.php';	
			}
			$signTime = strtotime($_GPC['signTime']);
			if(!empty($ckuser['sid'])){
				if($school['is_cardpay'] == 1){					
					if($ckuser['severend'] > $times){
						$data = array(
						'weid' => $weid,
						'schoolid' => $schoolid,
						'macid' => $ckmac['id'],
						'cardid' => $_GPC ['signId'],
						'sid' => $ckuser['sid'],
						'bj_id' => $bj['bj_id'],
						'type' => $type,
						'pic' => $pic,
						'temperature' => $_GPC ['signTemp'],
						'leixing' => $leixing,
						'pard' => $ckuser['pard'],
						'createtime' => $signTime
						);
						pdo_insert($this->table_checklog, $data);
						$checkid = pdo_insertid();
						$this->sendMobileJxlxtz($schoolid, $weid, $bj['bj_id'], $ckuser['sid'], $type, $leixing, $checkid, $ckuser['pard']);
						$fstype = true;
					}					
				}else{
					$data = array(
					'weid' => $weid,
					'schoolid' => $schoolid,
					'macid' => $ckmac['id'],
					'cardid' => $_GPC ['signId'],
					'sid' => $ckuser['sid'],
					'bj_id' => $bj['bj_id'],
					'type' => $type,
					'pic' => $pic,
					'temperature' => $_GPC ['signTemp'],
					'leixing' => $leixing,
					'pard' => $ckuser['pard'],
					'createtime' => $signTime
					);
					pdo_insert($this->table_checklog, $data);
					$checkid = pdo_insertid();
					$this->sendMobileJxlxtz($schoolid, $weid, $bj['bj_id'], $ckuser['sid'], $type, $leixing, $checkid, $ckuser['pard']);
					$fstype = true;
				}
			}
			if(!empty($ckuser['tid'])){
				$data = array(
				'weid' => $weid,
				'schoolid' => $schoolid,
				'macid' => $ckmac['id'],
				'cardid' => $_GPC ['signId'],
				'tid' => $ckuser['tid'],
				'type' => $type,
				'leixing' => $leixing,
				'pic' => $pic,
				'pard' => 1,
				'createtime' => $signTime
				);
				pdo_insert($this->table_checklog, $data);
				$fstype = true;		
			}	
		}		
		if ($fstype !=false){
			$result['returnCode'] = "000";
			$result['insertKqInfo'] = array(
				array(
					'COLNUM' => "1"
				)
			);
			echo json_encode($result);
        }else{
			$result['returnCode'] = "222";
			$result['insertKqInfo'] = array(
				array(
					'COLNUM' => "2"
				)
			);
			echo json_encode($result);
		}	
	}
?>