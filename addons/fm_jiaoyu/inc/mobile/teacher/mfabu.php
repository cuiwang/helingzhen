<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
        global $_W, $_GPC;
        $weid = $_W ['uniacid'];
        $from_user = $this->_fromuser;
		$schoolid = intval($_GPC['schoolid']);
		$openid = $_W['openid'];
        
        //查询是否用户登录		
		$userid = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where :schoolid = schoolid And :weid = weid And :openid = openid And :sid = sid", array(':weid' => $weid, ':schoolid' => $schoolid, ':openid' => $openid, ':sid' => 0), 'id');
		$it = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where weid = :weid AND id=:id ", array(':weid' => $weid, ':id' => $userid['id']));
		$teacher = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where weid = :weid AND id = :id", array(':weid' => $weid, ':id' => $it['tid']));
		$school = pdo_fetch("SELECT title,style3,is_fbnew,txid,txms,is_fbnew,is_fbvocie FROM " . tablename($this->table_index) . " where weid = :weid AND id=:id", array(':weid' => $weid, ':id' => $schoolid));

        if(!empty($userid['id'])){		
			include $this->template(''.$school['style3'].'/mfabunew');
		}else{
         
		 include $this->template('bangding');
        
		}        
?>