<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
        global $_W, $_GPC;
        $weid = $_W['uniacid'];
		$schoolid = intval($_GPC['schoolid']);
        
		//教师列表按教师入职时间先后顺序排列，先入职再前
		$it = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where id = :id ", array(':id' => $_SESSION['user']));
		if($it){
			$student = pdo_fetch("SELECT xq_id,bj_id FROM " . tablename($this->table_students) . " WHERE schoolid = :schoolid And id = :id", array(':schoolid' => $schoolid,':id' => $it['sid']));
			$bj_id = $student['bj_id'];
			$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_teachers) . " WHERE weid = :weid AND schoolid =:schoolid AND ( bj_id1 = :bj_id1 or bj_id2 = :bj_id2 or bj_id3 = :bj_id3 ) ORDER BY id ASC", array(
					':weid' => $_W['uniacid'],
					':schoolid' => $schoolid,
					':bj_id1' =>$bj_id,
					':bj_id2' =>$bj_id,
					':bj_id3' =>$bj_id,
					));
			
			$bjname = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " where sid = :sid ", array(':sid' => $student['bj_id']));
			$xqname = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " where sid = :sid ", array(':sid' => $student['xq_id']));
			foreach($list as $key => $row){
				 
				 //$list[$key]['sname'] = $category['sname'];
				 if ($bj_id = $row['bj_id1']){
					$category = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " where sid = :sid ", array(':sid' => $row['km_id1']));
				 }else if($bj_id = $row['bj_id2']){
					$category = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " where sid = :sid ", array(':sid' => $row['km_id2'])); 
				 }else if($bj_id = $row['bj_id3']){
					$category = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " where sid = :sid ", array(':sid' => $row['km_id3'])); 
				 }
				 $list[$key]['sname'] = $category['sname'];
			}		
			
			$school = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " where weid = :weid AND id=:id", array(':weid' => $weid, ':id' => $schoolid));
		
			include $this->template(''.$school['style2'].'/mytecher');
		}else{
			include $this->template('bangding');
		}
?>