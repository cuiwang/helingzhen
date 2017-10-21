<?php
/**
 * 积分导入模块微站定义
 *
 * @author han：865077369
 * @url http://www.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class hx_integralModuleSite extends WeModuleSite {

	public function doWebIntegral() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_GPC, $_W;

		$uniacid = $_W['uniacid'];

		if (checksubmit('submit')) {

			$force = $_GPC['force'];

			$file = $_FILES['file'];

			if( $file['name'] && $file['error'] == 0){

				$type = @end( explode('.', $file['name']));

				$type = strtolower($type);

				if( !in_array($type, array('xls','xlsx')) ){

					message('文件类型错误！',  '', 'error');

				}

				//开始导入

				set_time_limit(0);

				include_once IA_ROOT . '/framework/library/phpexcel/PHPExcel.php';

				/** PHPExcel_IOFactory */

				include_once IA_ROOT . '/framework/library/phpexcel/PHPExcel/IOFactory.php';

				if( $type == 'xls' ){

					$inputFileType = 'Excel5';    //这个是读 xls的

				}else{

					$inputFileType = 'Excel2007';//这个是计xlsx的

				}

				$objReader = PHPExcel_IOFactory::createReader($inputFileType);

				$objPHPExcel = $objReader->load($file['tmp_name']);



				$objWorksheet = $objPHPExcel->getActiveSheet();//取得总行数

				$highestRow = $objWorksheet->getHighestRow();//取得总列数

				for ($row = 2;$row <= $highestRow;$row++){

					$mobile = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
					
					$credit = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();

					$mobile = trim($mobile);

					$credit = floatval( $credit );
					
					$createtime=$_W['timestamp'];
					
					
					$member= pdo_fetch("SELECT * FROM " . tablename('mc_members') . " WHERE  uniacid = :uniacid and  mobile = :mobile", array(':uniacid' => $_W['uniacid'],':mobile' =>$mobile));

					if(empty($member)){
											
						$data= array('telphone'=>$mobile,'credit'=>$credit,'is_show'=>0,'uniacid' => $_W['uniacid'],'createtime'=>$createtime);
						
						pdo_insert('hx_integral_jf', $data);
						

					}else{
						
						load()->model('mc');
						
						mc_credit_update($member['uid'], 'credit1', $credit);
						
						$data= array('telphone'=>$mobile,'credit'=>$credit,'is_show'=>1,'uniacid' => $_W['uniacid'],'createtime'=>$createtime);
						
						pdo_insert('hx_integral_jf', $data);
						
						}	

				}

				message('数据导入成功',  $this->createWebUrl('Integral'));

			}

			message('文件上传失败！',  '', 'error');

		}

		include $this->template('integral');
	}
	public function doWebList() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_GPC, $_W;
		
        $op= $_GPC['op'] ? $_GPC['op'] : 'list';
		
		if(!empty($_GPC['keyword'])) {
        $condition .= " and telphone LIKE '%".$_GPC['keyword']."%'";
    }
	  if($op=='list'){
		
		$list=pdo_fetchall("SELECT * FROM " . tablename('hx_integral_jf') . " WHERE uniacid = :uniacid and is_show = :is_show".$condition, array(':is_show'=>1,':uniacid' => $_W['uniacid']));
	  }elseif($op=='slist'){
		  
		  $list=pdo_fetchall("SELECT * FROM " . tablename('hx_integral_jf') . " WHERE uniacid = :uniacid ".$condition, array(':uniacid' => $_W['uniacid']));
		  
		  }else{
		  $list=pdo_fetchall("SELECT * FROM " . tablename('hx_integral_jf') . " WHERE uniacid = :uniacid and is_show = :is_show".$condition, array(':is_show'=>0,':uniacid' => $_W['uniacid']));
		  }
		include $this->template('list');
	}

}