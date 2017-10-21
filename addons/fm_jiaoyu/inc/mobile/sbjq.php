<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
        global $_W, $_GPC;
        $weid = $this->weid;
        $from_user = $this->_fromuser;
		$schoolid = intval($_GPC['schoolid']);
		$openid = $_W['openid'];
        
        //查询是否用户登录		
		$userid = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where :schoolid = schoolid And :weid = weid And :openid = openid And :tid = tid", array(':weid' => $weid, ':schoolid' => $schoolid, ':openid' => $openid, ':tid' => 0), 'id');
		$it = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where weid = :weid AND id=:id ORDER BY id DESC", array(':weid' => $weid, ':id' => $userid['id']));	
		$school = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " where weid = :weid AND id=:id", array(':weid' => $weid, ':id' => $schoolid));
		$students = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where weid = :weid AND id = :id", array(':weid' => $_W ['uniacid'], ':id' => $it['sid']));		

        $shenfen = "";
		
		$isopen = 0;
		if ($it['pard'] == 2){
			$shenfen = "母亲";
		}else if($it['pard'] == 3){	
		    $shenfen = "父亲";
		}else if($it['pard'] == 4){	
		    $shenfen = "本人";			
		}		
		
		$category = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " WHERE weid =  '{$_W['uniacid']}' AND schoolid = '{$schoolid}' ORDER BY sid ASC, ssort DESC", array(':weid' => $_W['uniacid'], ':schoolid' => $schoolid), 'sid');
        
        $bj_id = $category[$students['bj_id']]['sid'];
		
        $bjidname = $category[$teachers['bj_id']]['sname'];
		
		// if(empty($bj_id)){ 
		// $bj_id = $teachers['bj_id1'];		
		// }
		
        if(!empty($userid['id'])){

			$teacher = pdo_fetchall("SELECT * FROM " . tablename ($this->table_teachers) . " where weid = :weid AND schoolid = :schoolid", array(':weid' => $weid, ':schoolid' => $schoolid), 'id');	

			$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_bjq) . " where schoolid = '{$schoolid}' And weid = '{$weid}' And type = 0 And bj_id1 = '{$bj_id}' Or bj_id2 = '{$bj_id}' Or bj_id3 = '{$bj_id}' ORDER BY createtime DESC");	
        
            foreach ($list as $index => $row) {
            	 if (!empty($row['sherid'])) {
					$list[$index]['picurl'] = pdo_fetchall("SELECT * FROM " . tablename($this->table_media) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' AND sherid =:sherid  ORDER BY id ASC", array(':sherid'=>$row['sherid']));
					$list[$index]['zname'] = pdo_fetchall("SELECT * FROM " . tablename($this->table_dianzan) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' AND sherid =:sherid  ORDER BY createtime ASC", array(':sherid'=>$row['sherid']));
					$list[$index]['contents'] = pdo_fetchall("SELECT * FROM " . tablename($this->table_bjq) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' AND type=1 AND sherid =:sherid  ORDER BY createtime ASC", array(':sherid'=>$row['sherid']));
                }
				$member = pdo_fetch("SELECT * FROM " . tablename ( 'mc_members' ) . " where uniacid = :uniacid And uid = :uid ORDER BY uid ASC", array(':uniacid' => $_W ['uniacid'], ':uid' => $row['uid']));
				$list[$index]['avatar'] = $member['avatar'];				
            }
    	 
            
			$item = pdo_fetch("SELECT * FROM " . tablename($this->table_bjq) . " WHERE id = :id ", array(':id' => $id));	
			
		 include $this->template(''.$school['style2'].'/sbjq');
          }else{
         //include $this->template('bjq');
		 		    $stopurl = $_W['siteroot'] .'app/'.$this->createMobileUrl('bjq', array('schoolid' => $schoolid));
			header("location:$stopurl");
			exit;
          }        
?>