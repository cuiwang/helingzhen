<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
        global $_W, $_GPC;
        $weid = $this->weid;
        $from_user = $this->_fromuser;
        $id = intval($_GPC['id']);
		$openid = $_W['openid'];
		$schoolid = intval($_GPC['schoolid']);
		
		$userid = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where :schoolid = schoolid And :weid = weid And :openid = openid And :tid = tid", array(':weid' => $weid, ':schoolid' => $schoolid, ':openid' => $openid, ':tid' => 0), 'id');
		$its = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where weid = :weid AND id=:id ORDER BY id DESC", array(':weid' => $weid, ':id' => $userid['id']));		
		$userinfo = iunserializer($its['userinfo']);
		$student = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where weid = :weid AND schoolid=:schoolid AND id=:id", array(':weid' => $weid, ':schoolid' => $schoolid, ':id' => $its['sid']));
		$school = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " where weid = :weid AND id=:id ORDER BY ssort DESC", array(':weid' => $weid, ':id' => $schoolid));
        $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_kcbiao) . " WHERE weid = '{$_W['uniacid']}' AND schoolid ={$schoolid} AND kcid = {$id}  ORDER BY date ASC", array(':weid' => $_W['uniacid'], ':schoolid' => $schoolid, ':kcid' => $id));
        $item1 = pdo_fetch("SELECT * FROM " . tablename($this->table_kcbiao) . " WHERE id = :id ", array(':id' => $id));
		$item = pdo_fetch("SELECT * FROM " . tablename($this->table_tcourse) . " WHERE id = :id ", array(':id' => $id));
        $teacher = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where weid = :weid AND schoolid=:schoolid AND id=:id", array(':weid' => $weid, ':schoolid' => $schoolid, ':id' => $item['tid']));
		$title = $item['title'];
        $category = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " WHERE weid =  '{$_W['uniacid']}' AND schoolid ={$schoolid} ORDER BY sid ASC, ssort DESC", array(':weid' => $_W['uniacid'], ':schoolid' => $schoolid), 'sid');
        if (!empty($category)) {
            $children = '';
            foreach ($category as $cid => $cate) {
                if (!empty($cate['parentid'])) {
                    $children[$cate['parentid']][$cate['id']] = array($cate['id'], $cate['name']);
                }
            }
        }
		
		$rest = $item['minge'] - $item['yibao'];
		
		$isfull =false;
		
		if ($item['yibao'] >= $item['minge']){
			
		$isfull =true;
		
		}
		
		$km = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$_W['uniacid']}' AND schoolid =$schoolid And type = 'subject' ORDER BY ssort DESC", array(':weid' => $_W['uniacid'], ':type' => 'subject', ':schoolid' => $schoolid));
		$it = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " WHERE sid = :sid", array(':sid' => $sid));
   		
        include $this->template(''.$school['style1'].'/kcinfo');
?>