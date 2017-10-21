
 <?php 
 	global $_W,$_GPC;
	$id = $_GPC['id'];
	$comments = $_GPC['comments'];
	$result = pdo_update("weliam_indiana_period",array('comment'=>$comments),array('id'=>$id));
	if($result == 0){
		echo json_encode('false') ;
		exit;
	}else{
		echo json_encode('true') ;
		exit;
	}
 	?>
