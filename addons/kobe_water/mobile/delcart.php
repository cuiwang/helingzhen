<?php
  
    $id = $_POST['id'];
    $openid = $_W['fans']['openid'];
    $uniacid = $_W['uniacid'];
    $result = pdo_query("DELETE FROM ".tablename('hao_water_cart')." WHERE id = :id AND openid = :openid AND uniacid = :uniacid " , array(':id' => $id,':openid'=>$openid,':uniacid'=>$uniacid));
	if (!empty($result)) {
		//message('删除成功');
		$result = 'success';
		echo json_encode($result);
	}else{
		$result = 'fail';
		echo json_encode($result);
	}

?>