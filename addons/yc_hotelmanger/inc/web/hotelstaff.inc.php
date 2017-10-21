<?php 
	global $_W,$_GPC;
	$uniacid = $this->_weid;
	 if($_GPC['op'] =='delete'){
		 $data = array(
				 'msg_flag' => 0
		 );
		 $result = pdo_update($this->ycadmin, $data, array('uid' => $_GPC['uid'],'uniacid' => $this->_weid,'hotelid' => $_GPC['hotelid']));
		 if ($result) {
			 echo json_encode(array('success'=>1,'msg'=>"修改酒店管理员保存成功!"));
			 exit;
		 } else {
			 echo json_encode(array('success'=>2,'msg'=>"修改酒店管理员保存失败!"));
			 exit;
		 }
	 }else if($_GPC['op'] =='list'){
		if($_GPC['searchname']){
		 	$lists = pdo_fetchall('SELECT uid,mobile,credit1,credit2,credit3,nickname,avatar,createtime FROM ' . tablename($this->members) . ' WHERE uniacid = ' . $uniacid.' and nickname like  \'%'.$_GPC['searchname'].'%\'');
			echo json_encode($lists);
			exit;
		}
		 $hoteladd = pdo_fetch('SELECT * FROM ' . tablename($this->hotel) . ' WHERE  status = 1 and uniacid =' . $this->_weid.' and id= '.$_GPC['hotelid']);
		 $checkstaffs = pdo_fetchall('SELECT * from ' . tablename($this->ycadmin).' where hotelid='.$_GPC['hotelid'].' and msg_flag=1');
		foreach ($checkstaffs as $keys=>$value) {
			 $list = pdo_fetchall('SELECT uid,mobile,credit1,credit2,credit3,nickname,avatar,createtime FROM ' . tablename($this->members) . ' WHERE uniacid = ' . $uniacid.' and uid='.$value['uid']);
			 $checkstaffs[$keys]['checkstaff'] = $list;
		}
		 $hotelmanager = $checkstaffs;
		  include $this->template('hotelstaffadd');
		 exit;
	 }

	$pindex = max(1, intval($_GPC['page']));

	$psize = $this->psize;

	$uniacid = $this->_weid;

	$hotels = pdo_fetchall('SELECT id,title FROM ' . tablename($this->hotel) . 'WHERE  status = 1 and uniacid =' . $this->_weid.' LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize);
	foreach ($hotels as $key=>$value) {
			$checkstaff = pdo_fetchall('SELECT * from ' . tablename($this->ycadmin).' where hotelid='.$value['id'].' and msg_flag=1');
			foreach ($checkstaff as $keys=>$value) {
					$list = pdo_fetchall('SELECT uid,mobile,credit1,credit2,credit3,nickname,avatar,createtime FROM ' . tablename($this->members) . ' WHERE uniacid = ' . $uniacid.' and uid='.$value['uid']);
				$checkstaff[$keys]['checkstaff'] = $list;
			}
		$hotels[$key]['checkstaffs'] = $checkstaff;
		}

$hotelsview = $hotels;

include $this->template('hotelstaff');