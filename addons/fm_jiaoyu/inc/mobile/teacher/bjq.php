<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
        global $_W, $_GPC;
        $weid = $_W ['uniacid'];
        $from_user = $_W['openid'];
		$schoolid = intval($_GPC['schoolid']);
		$openid = $_W['openid'];
		$act = "bjq";
        
        //查询是否用户登录		
		$userid = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where :schoolid = schoolid And :weid = weid And :openid = openid And :sid = sid", array(':weid' => $weid, ':schoolid' => $schoolid, ':openid' => $openid, ':sid' => 0), 'id');
		$it = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where weid = :weid AND id = :id And :schoolid = schoolid", array(':weid' => $weid, ':schoolid' => $schoolid, ':id' => $userid['id']));	
		$school = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " where weid = :weid AND id=:id", array(':weid' => $weid, ':id' => $schoolid));
		$teachers = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where weid = :weid And schoolid = :schoolid AND id = :id", array(':weid' => $weid, ':schoolid' => $schoolid, ':id' => $it['tid']));		
		if(empty($_GPC['bj_id'])){
			$bj_id = $teachers['bj_id1'];		
		}else{
			$bj_id = intval($_GPC['bj_id']);	
		}		
		$category = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " WHERE weid =  '{$weid}' AND schoolid = '{$schoolid}' ORDER BY sid ASC, ssort DESC", array(':weid' => $weid, ':schoolid' => $schoolid), 'sid');
        
        $bjid1 = $category[$teachers['bj_id1']]['sid'];
		$bjid2 = $category[$teachers['bj_id2']]['sid'];
		$bjid3 = $category[$teachers['bj_id3']]['sid'];
		
        $name1 = $category[$teachers['bj_id1']]['sname'];
		$name2 = $category[$teachers['bj_id2']]['sname'];
		$name3 = $category[$teachers['bj_id3']]['sname'];
		$nowname = $category[$bj_id]['sname'];
		$nownj = $category[$bj_id]['parentid'];

		$bzj = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " where weid = :weid And schoolid = :schoolid And tid = :tid And type = 'theclass' And sid = :sid", array(':weid' => $weid, ':schoolid' => $schoolid, ':tid' => $it['tid'], ':sid' => $bj_id));
		$bnjzr = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " where weid = :weid And schoolid = :schoolid And tid = :tid And sid = :sid", array(':weid' => $weid, ':schoolid' => $schoolid, ':tid' => $it['tid'], ':sid' => $nownj));
		$bjlist = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where schoolid = '{$schoolid}' And weid = '{$weid}' And type = 'theclass' ORDER BY sid ASC, ssort DESC");
		$thistime = strtotime($_GPC['limit']);
		if($thistime){
			$condition = " AND createtime < '{$thistime}'";
			$list1 = pdo_fetchall("SELECT * FROM " . tablename($this->table_bjq) . " where schoolid = '{$schoolid}' And weid = '{$weid}' And type = 0 And ( bj_id1 = '{$bj_id}' Or bj_id2 = '{$bj_id}' Or bj_id3 = '{$bj_id}' ) $condition ORDER BY createtime DESC LIMIT 0,10");
			foreach ($list1 as $index => $v) {
				if (!empty($v['sherid'])) {
					$list1[$index]['picurl'] = pdo_fetchall("SELECT * FROM " . tablename($this->table_media) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' AND sherid =:sherid  ORDER BY id ASC", array(':sherid'=>$v['sherid']));
					$list1[$index]['znames'] = pdo_fetchall("SELECT * FROM " . tablename($this->table_dianzan) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' AND sherid =:sherid  ORDER BY createtime ASC LIMIT 0,4", array(':sherid'=>$v['sherid']));
					$num = count($list1[$index]['zname']);
					$list1[$index]['contents'] = pdo_fetchall("SELECT * FROM " . tablename($this->table_bjq) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' AND type=1 AND sherid =:sherid  ORDER BY createtime ASC", array(':sherid'=>$v['sherid']));
					$list1[$index]['isdianz'] = pdo_fetch("SELECT id FROM " . tablename($this->table_dianzan) . " where :schoolid = schoolid And :weid = weid  And :uid = uid And :sherid = sherid", array(
			          ':weid' => $weid,
                      ':schoolid' => $schoolid,
					  ':uid' => $it['uid'],
					  ':sherid' => $v['id']
					   ));
				} 
				$members = pdo_fetch("SELECT avatar FROM " . tablename ( 'mc_members' ) . " where uniacid = :uniacid And uid = :uid ORDER BY uid ASC", array(':uniacid' => $weid, ':uid' => $v['uid']));
				$list1[$index]['avatar'] = $members['avatar'];
				$list1[$index]['time'] = sub_day($v['createtime']);
			}
			include $this->template('bjqlist');
		}else{
			if(!empty($userid['id'])){
				if($bj_id != 0){
						if($school['bjqstyle'] =='old'){
							$tj = " ORDER BY createtime DESC";
						}
						if($school['bjqstyle'] =='new'){
							$tj = " ORDER BY createtime DESC LIMIT 0,10";
						}					
						$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_bjq) . " where schoolid = '{$schoolid}' And weid = '{$weid}' And type = 0 And ( bj_id1 = '{$bj_id}' Or bj_id2 = '{$bj_id}' Or bj_id3 = '{$bj_id}' ) $tj ");
					   // $children = array();
						foreach ($list as $index => $row) {
							if (!empty($row['sherid'])) {
								$list[$index]['picurl'] = pdo_fetchall("SELECT * FROM " . tablename($this->table_media) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' AND sherid =:sherid  ORDER BY id ASC", array(':sherid'=>$row['sherid']));
								$list[$index]['zname'] = pdo_fetchall("SELECT * FROM " . tablename($this->table_dianzan) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' AND sherid =:sherid  ORDER BY createtime ASC LIMIT 0,4", array(':sherid'=>$row['sherid']));
								$list[$index]['contents'] = pdo_fetchall("SELECT * FROM " . tablename($this->table_bjq) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' AND type=1 AND sherid =:sherid  ORDER BY createtime ASC", array(':sherid'=>$row['sherid']));
								$list[$index]['isdianz'] = pdo_fetch("SELECT id FROM " . tablename($this->table_dianzan) . " where :schoolid = schoolid And :weid = weid  And :uid = uid And :sherid = sherid", array(
								  ':weid' => $weid,
								  ':schoolid' => $schoolid,
								  ':uid' => $it['uid'],
								  ':sherid' => $row['id']
								   ));								
							} 
							$member = pdo_fetch("SELECT * FROM " . tablename ( 'mc_members' ) . " where uniacid = :uniacid And uid = :uid ORDER BY uid ASC", array(':uniacid' => $weid, ':uid' => $row['uid']));
							$list[$index]['avatar'] = $member['avatar'];
							$list[$index]['time'] = sub_day($row['createtime']);
						}					
					if($school['bjqstyle'] =='old'){
						include $this->template(''.$school['style3'].'/bjq');
					}
					if($school['bjqstyle'] =='new'){
						include $this->template(''.$school['style3'].'/bjqnew');
					}
				}
			}else{
				include $this->template('bangding');
			}
		}        
?>