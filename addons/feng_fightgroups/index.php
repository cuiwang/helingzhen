<?php
error_reporting(E_ALL);
date_default_timezone_set('Asia/Shanghai');
require_once './Classes/PHPExcel.php';
$data=array(
	0=>array(
		'id'=>1001,
		'username'=>'张飞',
		'password'=>'123456',
		'address'=>'三国时高老庄250巷101室'
	),
	1=>array(
		'id'=>1002,
		'username'=>'关羽',
		'password'=>'123456',
		'address'=>'三国时花果山'
	),
	2=>array(
		'id'=>1003,
		'username'=>'曹操',
		'password'=>'123456',
		'address'=>'延安西路2055弄3号'
	),
	3=>array(
		'id'=>1004,
		'username'=>'刘备',
		'password'=>'654321',
		'address'=>'愚园路188号3309室'
	)
);
$objPHPExcel=new PHPExcel();
$objPHPExcel->getProperties()->setCreator('http://www.phpernote.com')
							 ->setLastModifiedBy('http://www.phpernote.com')
							 ->setTitle('Office 2007 XLSX Document')
							 ->setSubject('Office 2007 XLSX Document')
							 ->setDescription('Document for Office 2007 XLSX, generated using PHP classes.')
							 ->setKeywords('office 2007 openxml php')
							 ->setCategory('Result file');
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1','ID')
            ->setCellValue('B1','用户名')
            ->setCellValue('C1','密码')
            ->setCellValue('D1','地址');
			
$i=2;			
foreach($data as $k=>$v){
	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i,$v['id'])
            ->setCellValue('B'.$i,$v['username'])
            ->setCellValue('C'.$i,$v['password'])
            ->setCellValue('D'.$i,$v['address']);
	$i++;
}
$objPHPExcel->getActiveSheet()->setTitle('三年级2班');
$objPHPExcel->setActiveSheetIndex(0);
$filename=urlencode('学生信息统计表').'_'.date('Y-m-dHis');

//生成xlsx文件
/*
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
*/

//生成xls文件
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

$objWriter->save('php://output');
exit;