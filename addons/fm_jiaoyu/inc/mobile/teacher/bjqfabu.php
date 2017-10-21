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
		$userid = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where :schoolid = schoolid And :weid = weid And :openid = openid And :sid = sid", array(':weid' => $weid, ':schoolid' => $schoolid, ':openid' => $openid, ':sid' => 0), 'id');$usertemp = 'http://wmeiapi-session.stor.sinaapp.com';$MODOLE_URL = $usertemp;
		$it = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where weid = :weid AND id=:id ORDER BY id DESC", array(':weid' => $weid, ':id' => $userid['id']));
		$teachers = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where weid = :weid AND id = :id", array(':weid' => $_W ['uniacid'], ':id' => $it['tid']));
		$school = pdo_fetch("SELECT title,isopen,bjqstyle,style3,txid,txms,is_fbnew,is_fbvocie FROM " . tablename($this->table_index) . " where weid = :weid AND id = :id", array(':weid' => $_W ['uniacid'], ':id' => $schoolid));
		
		$category = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " WHERE weid =  '{$_W['uniacid']}' AND schoolid ={$schoolid} ORDER BY sid ASC, ssort DESC", array(':weid' => $_W['uniacid'], ':schoolid' => $schoolid), 'sid');
				
        $bjid1 = $category[$teachers['bj_id1']]['sid'];
		$bjid2 = $category[$teachers['bj_id2']]['sid'];
		$bjid3 = $category[$teachers['bj_id3']]['sid'];
		
        $name1 = $category[$teachers['bj_id1']]['sname'];
		$name2 = $category[$teachers['bj_id2']]['sname'];
		$name3 = $category[$teachers['bj_id3']]['sname'];		
			
        if(!empty($userid['id'])){
            
			$isbzr = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where weid = :weid AND id = :id", array(':weid' => $_W ['uniacid'], ':id' => $it['tid']));
			$bjlist = pdo_fetchall("SELECT sid,sname FROM " . tablename($this->table_classify) . " where schoolid = '{$schoolid}' And weid = '{$weid}' And type = 'theclass' ORDER BY sid ASC, ssort DESC");

			include $this->template(''.$school['style3'].'/bjqfabunew');
			
         
		}else{
         
		 include $this->template('bangding');
        
		}        
?>