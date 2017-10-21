<?php
/**
 * 微商城模块微站定义
 *
 */
defined('IN_IA') or exit('Access Denied');
define('ROOT_PATH', str_replace('site.php', '', str_replace('\\', '/', __FILE__)));
session_start();
//require_once '../framework/library/qrcode/phpqrcode.php';
require_once "phpqrcode.php";/*引入PHP QR库文件*/
include 'model.php';
include 'code.php';
require_once "jssdk.php";
class haoman_virtuamallModuleSite extends WeModuleSite {

	public $settings;


	//生成口令
	public function doWebcode(){
		global $_W  ,$_GPC;

		$_GPC['do'] = 'post';
		checklogin();
		$uniacid = $_W['uniacid'];
		$rid = $_GPC['rid'];
		load()->model('reply');
		load()->func('tpl');
		$sql = "uniacid = :uniacid and `module` = :module";
		$params = array();
		$params[':uniacid'] = $_W['uniacid'];
		$params[':module'] = 'haoman_virtuamall';

		$rowlist = pdo_fetchall("SELECT id,name FROM ".tablename('haoman_virtuamall_category')." WHERE weid = :weid", array(':weid' => $_W['uniacid']));




		$now = time();
		$addcard1 = array(
			"starttime" => $now,
			"endtime" => strtotime(date("Y-m-d H:i", $now + 7 * 24 * 3600)),
		);

		if (checksubmit()) {
			$count = $_GPC['num'];
			$id = $_GPC['name'];

			if(empty($id)){
				message('参数错误', $this->createWebUrl("code"), 'error');
			}

			$name = pdo_fetch("SELECT * FROM ".tablename('haoman_virtuamall_category')." WHERE weid = :weid and id =:id", array(':weid' => $_W['uniacid'],':id'=>$id));

			if(empty($count)||$count > 20000){
				message('参数错误', $this->createWebUrl("code"), 'error');
			}else{

				$picid = pdo_fetch("SELECT max(pici) FROM ".tablename('haoman_virtuamall_pw')." WHERE uniacid = :uniacid", array(':uniacid' => $_W['uniacid']));
				$picid = $picid['max(pici)'];

				$pici = !empty($picid) ? ($picid+1) : 1;
				// print_r($picid);exit();

				$data = array('uniacid' => $_W['uniacid'], 'createtime' => time('Ymd'), 'codenum' => $count,'is_qrcode'=>0,'rulename'=>$name['name'], 'pici' => $pici,'status'=>$_GPC['status']);
				pdo_insert('haoman_virtuamall_pici', $data);
				for($i = 0; $i < $count; $i++){
					$randcode = $this->genkeyword(8);

					$code = new Code();
					$card_no = $code->encodeID($randcode,5);
					$card_pre = $this->GetRandStr(4);
					$card_vc = substr(md5($card_pre.$card_no),0,2);
					$card_vc = strtoupper($card_vc);
					$title = $card_pre.$card_no.$card_vc;
					$pwid = 'pwid'.date('Ymd') . sprintf('%d', time()).$i;

					$used_cardid = $this->foo($id);

					$updata = array(
						'rid'=>0,
						'pici' => $pici,
						'category'=>$id,
						'rulename' => $name['name'],
						'uniacid' => $_W['uniacid'],
						'pwid' => $pwid,
						'title' => $title,
						'used_times' => $_GPC['used_times']*24 * 3600,
						'num' => 1,
						'iscqr' => 1,
						'isused' => 0,
						'endtime' => 0,
						'activation_time' => 0,
						'used_cardid' => $used_cardid,
						'status' => 1,
						'createtime' =>time(),
					);

					$temp = pdo_insert('haoman_virtuamall_pw', $updata);
				}


				message('卡密生成成功', $this->createWebUrl('code'), 'success');
			}
		}



		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$sql = 'select * from ' . tablename('haoman_virtuamall_pici') . 'where  uniacid = :uniacid and status!=0 LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
		$prarm = array(':uniacid' => $_W['uniacid']);
		$list = pdo_fetchall($sql, $prarm);

		foreach($list as &$k){
			$k['name'] =  pdo_fetchcolumn('select title from ' . tablename('haoman_virtuamall_goods') . 'where weid = :weid and goodssn = :goodssn', array(':weid'=>$_W['uniacid'],':goodssn'=>$k['is_qrcode']));

		}
		unset($k);
		$count = pdo_fetchcolumn('select count(*) from ' . tablename('haoman_virtuamall_pici') . 'where uniacid = :uniacid', $prarm);
		$pager = pagination($count, $pindex, $psize);

		include $this->template('code');
	}


	function isExist($randcode){
		global $_W;
		$sql = 'select * from ' . tablename('haoman_virtuamall_code') . 'where uniacid = :uniacid and code = :code';
		$prarm = array(':uniacid' => $_W['uniacid'], ':code' => $randcode);
		if(pdo_fetch($sql,$prarm)){
			return 1;
		}else{
			return 0;
		}

	}

	function GetRandStr($len)
	{
		$chars = array(
			"a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
			"l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
			"w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
			"H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
			"S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
			"3", "4", "5", "6", "7", "8", "9"
		);
		$charsLen = count($chars) - 1;
		shuffle($chars);
		$output = "";
		for ($i=0; $i<$len; $i++)
		{
			$output .= $chars[mt_rand(0, $charsLen)];
		}
		return $output;
	}

	function genkeyword($length)
	{
		$chars = array('0','1', '2', '3', '4', '5', '6', '7', '8', '9');
		$password = rand(1, 9);
		for ($i = 0; $i < $length - 1; $i++) {
			$keys = array_rand($chars, 1);
			$password .= $chars[$keys];
		}
		return $password;
	}
    //生成12个数字和字母随机数
	function foo($id) {
		$o = $last = '';
		do {
			$last = $o;
			usleep(10);
			$t = explode(' ', microtime());
			$o = substr(base_convert(strtr($t[0].$t[1].$t[1], '.', ''), 10, 36), 0, 12);
		}while($o == $last);
		return $id.$o;
	}



	//查看口令
	public function doWebcodeshow(){
		global $_W  ,$_GPC;

		$pici = $_GPC['pici'];

		$params = array(':uniacid' => $_W['uniacid'],':pici' => $pici);
		$where = '';
		if (!empty($_GPC['used_cardid'])) {
			$where.=' and used_cardid=:used_cardid';
			$params[':used_cardid'] = $_GPC['used_cardid'];
		}
		if (!empty($_GPC['title'])) {
			$where.=' and title=:title';
			$params[':title'] = $_GPC['title'];
		}


		$t = time();

		$total = pdo_fetchcolumn("select count(id) from " . tablename('haoman_virtuamall_pw') . "where  uniacid=:uniacid and pici = :pici " . $where . "", $params);

			$pindex = max(1, intval($_GPC['page']));
		$psize = 12;
		$pager = pagination($total, $pindex, $psize);
		$start = ($pindex - 1) * $psize;
		$limit .= " LIMIT {$start},{$psize}";
		$list = pdo_fetchall("select * from " . tablename('haoman_virtuamall_pw') . " where uniacid=:uniacid and pici = :pici" . $where . "" . $limit, $params);

		foreach($list as &$k){
			$k['shopname'] = pdo_fetchcolumn('select title from ' . tablename('haoman_virtuamall_goods') . 'where weid = :weid and goodssn = :goodssn', array(':weid'=>$_W['uniacid'],':goodssn'=>$k['pwsn']));

		}
		unset($k);
		include $this->template('codeshow');
	}

    //口令导入
	public function doWebImport()
	{
		require_once ROOT_PATH."function.php";
		global $_GPC, $_W;
		$uniacid = $_W['uniacid'];
		$filename = $_GPC['csv'];
		if($_W['ispost'])
		{
			$filename = $_FILES['csv']['tmp_name'];
			if(empty($filename))
			{
				//echo '请选择要导入的CSV文件！';
				message('请选择要导入的CSV文件！','','');
				exit;
			}
			$handle = fopen($filename, 'r');
			$result = input_csv($handle); //解析csv
			$len_result = count($result);
			if($len_result==0){
				message('导入的CSV文件没有数据！','','error');
				exit;
			}

			$colname1 = trim(iconv('gb2312','utf-8//IGNORE', $result[0][0]));  //需要增加字段就这边复制一行
			$colname2 = trim(iconv('gb2312','utf-8//IGNORE', $result[0][1]));
			$colname3 = trim(iconv('gb2312','utf-8//IGNORE', $result[0][2]));
			$colname4 = trim(iconv('gb2312','utf-8//IGNORE', $result[0][3]));
			$colname5 = trim(iconv('gb2312','utf-8//IGNORE', $result[0][4]));

			if($colname1!='卡号'||$colname2!='卡密'||$colname5!='商品编码')//需要增加字段就这边加个判断
			{
				message('请检查导入的数据表是否正确！','','error');
			}

			$picid = pdo_fetch("SELECT max(pici) FROM ".tablename('haoman_virtuamall_pw')." WHERE uniacid = :uniacid", array(':uniacid' => $_W['uniacid']));

			$picid = $picid['max(pici)'];

			$pici = !empty($picid) ? ($picid+1) : 1;

            $codenum = $len_result;
            $tims = time('Ymd');
			for ($i = 1; $i < $len_result; $i++) { //循环获取各字段值
				$used_cardid = replacestr($result[$i][0]); //中文转码
				$title = iconv('gb2312', 'utf-8//IGNORE', $result[$i][1]);//中文转码
				$used_times = replacestr($result[$i][2]); //中文转码
				$category = replacestr($result[$i][3]); //中文转码
				$pwsn = replacestr($result[$i][4]); //中文转码
				//需要增加字段就这边复制一行
				$pwid = 'pwid'.date('Ymd') . sprintf('%d', time()).$i;

			//	$data_values .= "('$uniacid','$used_times','$category','$pwsn','$pwid','$used_cardid','$title'),";
			}



		//	$data_values = substr($data_values,0,-1); //去掉最后一个逗号
			fclose($handle); //关闭指针
			$datas = array('uniacid' => $_W['uniacid'], 'createtime' => time('Ymd'), 'codenum' => count($result)-1, 'pici' => $pici,'status'=>1,'is_qrcode'=>$result[1][4]);
			pdo_insert('haoman_virtuamall_pici', $datas);
			$results = array_splice($result,1);
			if (!empty($results)){
				foreach ($results as $v) {
					$data = array(
						'title'=>$v[1], //卡密
						'used_cardid'=>$v[0],//卡号
						'bianhao'=>$v[3],//编号
						'pwsn'=>$v[4],//产品编码
						'uniacid' => $_W['uniacid'], //公众号
						'pici' => $pici,//批次
						'pwid' => $pwid,
						'num' => 1,
						'isused' => 0,
						'status' => 1,
						'createtime' =>time(),
						'used_times' => $v[2]*24 * 3600,
						'iscqr' => 1,
						'endtime' => 0,
						'activation_time' => 0,
					);
					$category = pdo_fetch("SELECT * FROM " . tablename('haoman_virtuamall_category') . " WHERE weid = :weid and bianhao =:bianhao", array(':weid' => $_W['uniacid'],':bianhao'=>$v[3]));
					if($category){
						$data['category']=$category['id'];
					}
					$query = pdo_insert('haoman_virtuamall_pw',$data);
				}
			}


		//	$query = pdo_query("insert ignore into ".tablename('haoman_virtuamall_pw')." (uniacid,orderid,tbname) values $data_values");   //需要增加字段就这边数据库字段增加下
			if($query){
				message('导入成功！',referer(),'success');
				//echo '导入成功！';
			}else{
				message('导入失败！',referer(),'error');
				//echo '导入失败！';
			}
		}

		include $this->template('import');
	}

	//口令导入
	public function doWebImports()
	{
		global $_W, $_GPC;
		load()->func('logging');
		//$pici = $_GPC['pici'];
		$_GPC['do'] = 'postt';
		if (!empty($_GPC['foo'])) {
			include 'excel/oleread.php';
			include 'excel/excel.php';
			$tmp = $_FILES['file']['tmp_name'];
			if (!empty ($tmp)) {
				$file_name = date('Ymdhis') . ".xls"; //上传后的文件保存路径和名称
				if (copy($tmp, $file_name)) {
					$xls = new Spreadsheet_Excel_Reader();
					$xls->setOutputEncoding('utf-8');  //设置编码
					$xls->read($file_name);  //解析文件
					for ($i=2; $i<=$xls->sheets[0]['numRows']; $i++) {
						$data_values[] = $xls->sheets[0]['cells'][$i];
						$pwid = 'pwid'.date('Ymd') . sprintf('%d', time()).$i;
					}
				}
				$picid = pdo_fetch("SELECT max(pici) FROM ".tablename('haoman_virtuamall_pw')." WHERE uniacid = :uniacid", array(':uniacid' => $_W['uniacid']));

				$picid = $picid['max(pici)'];

				$pici = !empty($picid) ? ($picid+1) : 1;

				$datas = array('uniacid' => $_W['uniacid'], 'createtime' => time('Ymd'), 'codenum' => count($data_values),'is_qrcode'=>0, 'pici' => $pici,'status'=>1,'is_qrcode'=>$data_values[1][5]);
				pdo_insert('haoman_virtuamall_pici', $datas);
				if (!empty($data_values)){
					foreach ($data_values as $v) {
						$data = array(
							'title'=>$v[1], //卡密
							'used_cardid'=>$v[2],//卡号
							'bianhao'=>$v[4],//编号
							'pwsn'=>$v[5],//产品编码
							'uniacid' => $_W['uniacid'], //公众号
							'pici' => $pici,//批次
							'pwid' => $pwid,
							'num' => 1,
							'isused' => 0,
							'status' => 1,
							'ishexiao' => $v[6],
							'createtime' =>time(),
							'used_times' => $v[3]*24 * 3600,
							'iscqr' => 1,
							'endtime' => 0,
							'activation_time' => 0,
						);
						$category = pdo_fetch("SELECT * FROM " . tablename('haoman_virtuamall_category') . " WHERE weid = :weid and bianhao =:bianhao", array(':weid' => $_W['uniacid'],':bianhao'=>$v[4]));
						if($category){
							$data['category']=$category['id'];
						}
						pdo_insert('haoman_virtuamall_pw',$data);
					}
				}
				@unlink($file_name);
				message('导入成功!',referer());
			}
			message('请选择文件');


		} else {
			include $this->template('import');
		}
	}

	//网页端激活卡密
	public function doWebActivation() {
		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		$pici = intval($_GPC['pici']);
		$pw = pdo_fetch("select * from " . tablename('haoman_virtuamall_pw') . " where id = :id and pici = :pici ", array(':id' => $id,':pici' => $pici));

		if (empty($pw)) {
			message('抱歉，参数错误！');
		}elseif($pw['status']==2){
			message('抱歉，该卡密已经激活过！');
		}else{
			pdo_update('haoman_virtuamall_pw', array('status' => 2,'num'=>0,'activation_time'=>time(),'endtime'=>time()+$pw['used_times']),  array('id' => $pw['id']));
		}

		message('卡密激活成功！', referer(), 'success');
	}

//网页端激活卡密
	public function doMobileActivation() {
		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		$pici = intval($_GPC['pici']);
		$pw = pdo_fetch("select * from " . tablename('haoman_virtuamall_pw') . " where id = :id and pici = :pici ", array(':id' => $id,':pici' => $pici));

		if (empty($pw)) {
			message('抱歉，参数错误！');
		}elseif($pw['status']==2){
			message('抱歉，该卡密已经激活过！');
		}else{
			pdo_update('haoman_virtuamall_pw', array('status' => 2,'num'=>0,'activation_time'=>time(),'endtime'=>time()+$pw['used_times']),  array('id' => $pw['id']));
		}

		message('卡密激活成功！', referer(), 'success');
	}

	//失效口令删除
	public function doWebMiss() {
		global $_GPC, $_W;
		checklogin();
		$res = pdo_fetch('select * from ' . tablename('haoman_virtuamall_pw') . ' where uniacid = :uniacid and status = 2', array(':uniacid' => $_W['uniacid']));
		if($res){
			pdo_delete('haoman_virtuamall_pw',array('uniacid' => $_W['uniacid'] ,'status' =>'2'));
			message('删除成功',$this->createWebUrl("code"),'success');
		}else{
			message('暂无已失效口令',$this->createWebUrl("code"),'error');
		}
	}

	public function doWebDeletepw() {
		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		$pici = intval($_GPC['pici']);
		$rule = pdo_fetch("select * from " . tablename('haoman_virtuamall_pw') . " where id = :id ", array(':id' => $id));

		$codenum = pdo_fetch("select * from " . tablename('haoman_virtuamall_pici') . " where pici = :pici ", array(':pici' => $pici));
		$goods = pdo_fetch("select * from " . tablename('haoman_virtuamall_goods') . " where weid = :weid and goodssn = :goodssn ", array(':goodssn' => $rule['pwsn'],':weid'=>$_W['uniacid']));

		if (empty($rule)) {
			message('抱歉，参数错误！');
		}
		if($rule['iscqr']==2&&$rule['isused']==1){
			message('改卡密已经被购买，无法删除！', referer(), 'error');
		}
		pdo_delete('haoman_virtuamall_pw', array('id' => $id));
		if($rule['pici']!=0){
			pdo_update('haoman_virtuamall_pici', array('codenum' => $codenum['codenum'] - 1), array('pici' => $codenum['pici']));
			pdo_update('haoman_virtuamall_goods', array('total' => $goods['total'] - 1), array('id' => $goods['id']));
		}
		message('卡密删除成功！', referer(), 'success');
	}

	public function doMobileDeletepw() {
		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		$pici = intval($_GPC['pici']);
		$rule = pdo_fetch("select * from " . tablename('haoman_virtuamall_pw') . " where id = :id ", array(':id' => $id));
		$codenum = pdo_fetch("select * from " . tablename('haoman_virtuamall_pici') . " where pici = :pici ", array(':pici' => $pici));
		if (empty($rule)) {
			message('抱歉，参数错误！');
		}
		pdo_delete('haoman_virtuamall_pw', array('id' => $id));
		if($rule['pici']!=0){
			pdo_update('haoman_virtuamall_pici', array('codenum' => $codenum['codenum'] - 1), array('pici' => $codenum['pici']));

		}
		message('卡密删除成功！', referer(), 'success');
	}

	//每批次卡密删除
	public function doWebCodedie() {
		global $_GPC, $_W;
		checklogin();
		$pici = $_GPC['pici'];
		$res = pdo_fetch('select * from ' . tablename('haoman_virtuamall_pici') . ' where uniacid = :uniacid and pici = :pici', array(':uniacid' => $_W['uniacid'] ,':pici' => $pici));
		$ress = pdo_fetchall('select * from ' . tablename('haoman_virtuamall_pw') . ' where uniacid = :uniacid and pici = :pici and pwsn = :pwsn and iscqr =2 and isused =1', array(':uniacid' => $_W['uniacid'] ,':pici' => $res['pcic'],':pwsn'=>$res['is_qrcode']));
		if($ress){
			message('删除失败,该批次下有卡密已售出',$this->createWebUrl("code"),'error');
		}
		if($res){
			pdo_delete('haoman_virtuamall_pici', array('uniacid' => $_W['uniacid'],'pici' => $pici));
			pdo_delete('haoman_virtuamall_pw', array('uniacid' => $_W['uniacid'],'pici' => $pici));
			//  pdo_delete('haoman_virtuamall_prize', array('uniacid' => $_W['uniacid'],'pici' => $pici));
			message('删除成功',$this->createWebUrl("code"),'success');
		}else{
			message('暂无口令',$this->createWebUrl("code"),'error');
		}
	}

//    口令下载
	public function  doWebUDownload2()
	{
		global $_GPC,$_W;
		checklogin();
		$list = pdo_fetchall('select * from ' . tablename('haoman_virtuamall_pw') . ' where uniacid = :uniacid and status !=0 ORDER BY id ', array(':uniacid' => $_W['uniacid']));

		foreach($list as &$v){
			$v['shopname'] =  pdo_fetchcolumn('select title from ' . tablename('haoman_virtuamall_goods') . ' where weid = :weid and goodssn = :goodssn ORDER BY id ', array(':weid' => $_W['uniacid'],'goodssn'=>$v['pwsn']));

		}

		$tableheader = array('ID','批次','卡密','卡号','归属产品','商品编码','有效期','激活时间','数量','过期时间');
		$html = "\xEF\xBB\xBF";
		foreach ($tableheader as $value) {
			$html .= $value . "\t ,";
		}
		$html .= "\n";
		foreach ($list as $value) {
			$html .= $value['id'] . "\t ,";
			$html .= $value['pici'] . "\t ,";
			$html .=  $value['title'] . "\t ,";
			$html .=  $value['used_cardid'] . "\t ,";
			$html .=  $value['shopname'] . "\t ,";
			$html .=  $value['pwsn'] . "\t ,";
			if($value['used_times']==0){
				$html .=  '长期有限' . "\t ,";
			}else{
				$html .=  $value['used_times']/(24*3600) . "\t ,";
			}

			if($value['activation_time']){
				$html .=  date('Y-m-d H:i:s', $value['activation_time']) . "\t ,";
			}else{
				$html .=  '' . "\t ,";
			}

			$html .=  $value['num'] . "\t ,";
			if($value['endtime']){
				$html .= date('Y-m-d H:i:s', $value['endtime']) . "\n";
			}
			else{
				$html .= '' . "\n";
			}

		}


		header("Content-type:text/csv");

		header("Content-Disposition:attachment;filename=全部卡密.csv");

		$html = mb_convert_encoding($html, 'gb2312', 'UTF-8');

		echo $html;
		exit();
	}

	//核销记录下载
	public function  doWebUDownload3()
	{
		global $_GPC,$_W;
		checklogin();
		$list = pdo_fetchall('select * from ' . tablename('haoman_virtuamall_hexiaoaward') . ' where weid = :weid and status !=0 ORDER BY id ', array(':weid' => $_W['uniacid']));

		foreach($list as &$k){
			$k['category'] = pdo_fetchcolumn('SELECT `name` FROM ' . tablename('haoman_virtuamall_category') . ' WHERE `weid` = :weid and id = :id',array(":weid" => $_W['uniacid'],':id'=>$k['category']));
		}
		unset($k);

		$tableheader = array('ID','核销员','卡密','卡号','电话','商品编码','分类','核销时间');
		$html = "\xEF\xBB\xBF";
		foreach ($tableheader as $value) {
			$html .= $value . "\t ,";
		}
		$html .= "\n";
		foreach ($list as $value) {
			$html .= $value['id'] . "\t ,";
			$html .= $value['realname'] . "\t ,";
			$html .=  $value['title'] . "\t ,";
			$html .=  $value['used_cardid'] . "\t ,";
			$html .=  $value['mobile'] . "\t ,";
			$html .=  $value['pwsn'] . "\t ,";
			$html .=  $value['category'] . "\t ,";
			$html .= date('Y-m-d H:i:s', $value['createtime']) . "\n";


		}


		header("Content-type:text/csv");

		header("Content-Disposition:attachment;filename=全部核销记录.csv");

		$html = mb_convert_encoding($html, 'gb2312', 'UTF-8');

		echo $html;
		exit();
	}


	public function  doWebDownload2()
	{
		global $_GPC,$_W;
		checklogin();

		$list = pdo_fetchall("SELECT g.*, o.orderid,o.total,g.type,o.optionname,o.optionid,o.price as orderprice FROM " . tablename('haoman_virtuamall_order_goods') . " o left join " . tablename('haoman_virtuamall_goods') . " g on o.goodsid=g.id " . " WHERE o.weid='{$_W['weid']}'");

		foreach($list as &$v){
			$v['username'] =  pdo_fetchcolumn('select realname from ' . tablename('haoman_virtuamall_order') . ' where weid = :weid and tandid = :tandid ORDER BY id ', array(':weid' => $_W['uniacid'],'tandid'=>$v['orderid']));
			$v['mobile'] =  pdo_fetchcolumn('select mobile from ' . tablename('haoman_virtuamall_order') . ' where weid = :weid and tandid = :tandid ORDER BY id ', array(':weid' => $_W['uniacid'],'tandid'=>$v['orderid']));
			$v['paytype'] =  pdo_fetchcolumn('select paytype from ' . tablename('haoman_virtuamall_order') . ' where weid = :weid and tandid = :tandid ORDER BY id ', array(':weid' => $_W['uniacid'],'tandid'=>$v['orderid']));
			$v['transid'] =  pdo_fetchcolumn('select transid from ' . tablename('haoman_virtuamall_order') . ' where weid = :weid and tandid = :tandid ORDER BY id ', array(':weid' => $_W['uniacid'],'tandid'=>$v['orderid']));
			$v['status'] =  pdo_fetchcolumn('select status from ' . tablename('haoman_virtuamall_order') . ' where weid = :weid and tandid = :tandid ORDER BY id ', array(':weid' => $_W['uniacid'],'tandid'=>$v['orderid']));
			if($v['paytype']==1){
				$v['paytype'] = '余额';
			}else{
				if($v['transid']){
					$v['paytype']= '微信支付';
				}elseif(empty($v['transid'])&&$v['status']!=0){
					$v['paytype']= '线上支付';
				}else{
					$v['paytype']= '';
				}

			}

			if($v['status']==0){
				$v['status'] = '未付款';
			}elseif($v['status']==1){
				$v['status']='已付款';
			}elseif($v['status']==2){
				$v['status'] = '待收货';
			}elseif($v['status']==3){
				$v['status'] = '已收货';
			}else{
				$v['status']= '订单取消';
			}
		}

		$tableheader = array('ID','订单号','姓名','电话','支付方式','销售价','成本价','数量','状态','时间');
		$html = "\xEF\xBB\xBF";
		foreach ($tableheader as $value) {
			$html .= $value . "\t ,";
		}
		$html .= "\n";
		foreach ($list as $value) {
			$html .= $value['id'] . "\t ,";
			$html .= $value['orderid'] . "\t ,";
			$html .=  $value['username'] . "\t ,";
			$html .=  $value['mobile'] . "\t ,";
			$html .=  $value['paytype'] . "\t ,";
			$html .=  $value['marketprice'] . "\t ,";
			$html .=  $value['originalprice'] . "\t ,";
			$html .=  $value['total'] . "\t ,";
			$html .=  $value['status'] . "\t ,";
			$html .= date('Y-m-d H:i:s', $value['createtime']) . "\n";
		}


		header("Content-type:text/csv");

		header("Content-Disposition:attachment;filename=全部订单.csv");

		$html = mb_convert_encoding($html, 'gb2312', 'UTF-8');

		echo $html;
		exit();
	}

	public function __construct() {
		global $_W;
		$sql = 'SELECT `settings` FROM ' . tablename('uni_account_modules') . ' WHERE `uniacid` = :uniacid AND `module` = :module';
		$settings = pdo_fetchcolumn($sql, array(':uniacid' => $_W['uniacid'], ':module' => 'haoman_virtuamall'));
		$this->settings = iunserializer($settings);
	}

	public function doMobileGrant() {
		$this->Grant(array('haoman_virtuamall'));
	}
//分类管理
	public function doWebCategory() {
		global $_GPC, $_W;
		load()->func('tpl');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			if (!empty($_GPC['bianhao'])) {
				foreach ($_GPC['bianhao'] as $id => $bianhao) {
					pdo_update('haoman_virtuamall_category', array('bianhao' => $bianhao), array('id' => $id));
				}
				message('分类排序更新成功！', $this->createWebUrl('category', array('op' => 'display')), 'success');
			}
			$children = array();
			$category = pdo_fetchall("SELECT * FROM " . tablename('haoman_virtuamall_category') . " WHERE weid = '{$_W['uniacid']}' ORDER BY parentid ASC, bianhao DESC");

			foreach ($category as $index => $row) {
				if (!empty($row['parentid'])) {
					$children[$row['parentid']][] = $row;
					unset($category[$index]);
				}
			}
			include $this->template('category');
		} elseif ($operation == 'post') {
			$parentid = intval($_GPC['parentid']);
			$id = intval($_GPC['id']);
			if (!empty($id)) {
				$category = pdo_fetch("SELECT * FROM " . tablename('haoman_virtuamall_category') . " WHERE id = '$id'");
			} else {
				$category = array(
					'displayorder' => 0,
				);
			}
			if (!empty($parentid)) {
				$parent = pdo_fetch("SELECT id, name FROM " . tablename('haoman_virtuamall_category') . " WHERE id = '$parentid'");
				if (empty($parent)) {
					message('抱歉，上级分类不存在或是已经被删除！', $this->createWebUrl('post'), 'error');
				}
			}
			if (checksubmit('submit')) {
				if (empty($_GPC['catename'])) {
					message('抱歉，请输入分类名称！');
				}
				$data = array(
					'weid' => $_W['uniacid'],
					'name' => $_GPC['catename'],
					'enabled' => 1,
					'bianhao' => intval($_GPC['bianhao']),
					'isrecommand' => 1,
					'description' => $_GPC['description'],
					'parentid' => intval($parentid),
					'thumb' => $_GPC['thumb']
				);
				if (!empty($id)) {
					unset($data['parentid']);
					pdo_update('haoman_virtuamall_category', $data, array('id' => $id));
					load()->func('file');
					file_delete($_GPC['thumb_old']);
				} else {
					pdo_insert('haoman_virtuamall_category', $data);
					$id = pdo_insertid();
				}
				message('更新分类成功！', $this->createWebUrl('category', array('op' => 'display')), 'success');
			}
			include $this->template('category');
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$category = pdo_fetch("SELECT id, parentid FROM " . tablename('haoman_virtuamall_category') . " WHERE id = '$id'");
			if (empty($category)) {
				message('抱歉，分类不存在或是已经被删除！', $this->createWebUrl('category', array('op' => 'display')), 'error');
			}
			pdo_delete('haoman_virtuamall_category', array('id' => $id, 'parentid' => $id), 'OR');
			message('分类删除成功！', $this->createWebUrl('category', array('op' => 'display')), 'success');
		}
	}

	public function doWebSetGoodsProperty() {
		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		$type = $_GPC['type'];
		$data = intval($_GPC['data']);
		if (in_array($type, array('new', 'hot', 'recommand', 'discount'))) {
			$data = ($data==1?'0':'1');
			pdo_update("haoman_virtuamall_goods", array("is" . $type => $data), array("id" => $id, "weid" => $_W['uniacid']));
			die(json_encode(array("result" => 1, "data" => $data)));
		}
		if (in_array($type, array('status'))) {
			$data = ($data==1?'0':'1');
			pdo_update("haoman_virtuamall_goods", array($type => $data), array("id" => $id, "weid" => $_W['uniacid']));
			die(json_encode(array("result" => 1, "data" => $data)));
		}
		if (in_array($type, array('type'))) {
			$data = ($data==1?'2':'1');
			pdo_update("haoman_virtuamall_goods", array($type => $data), array("id" => $id, "weid" => $_W['uniacid']));
			die(json_encode(array("result" => 1, "data" => $data)));
		}
		die(json_encode(array("result" => 0)));
	}


	public function doWebGoods() {
		global $_GPC, $_W;
		load()->func('tpl');

		$sql = 'SELECT * FROM ' . tablename('haoman_virtuamall_category') . ' WHERE `weid` = :weid ORDER BY `parentid`, `displayorder` DESC';

		$category = pdo_fetchall($sql, array(':weid' => $_W['uniacid']), 'id');

		$pw = pdo_fetch("SELECT * FROM " . tablename('haoman_virtuamall_pw') . " WHERE uniacid = :uniacid", array(':uniacid' => $_W['uniacid']));


		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

		if ($operation == 'post') {
			$id = intval($_GPC['id']);


              //修改进入
			if (!empty($id)) {
				$item = pdo_fetch("SELECT * FROM " . tablename('haoman_virtuamall_goods') . " WHERE id = :id", array(':id' => $id));
				if (empty($item)) {
					message('抱歉，商品不存在或是已经删除！', '', 'error');
				}
				$categoryss = pdo_fetch("SELECT * FROM " . tablename('haoman_virtuamall_category') . " WHERE weid = :weid and id = :id", array(':weid' => $_W['uniacid'],':id'=>$item['pcate']));
				$pws = pdo_fetch("SELECT used_times,ishexiao FROM " . tablename('haoman_virtuamall_pw') . " WHERE uniacid = :uniacid and pwsn= :pwsn", array(':uniacid' => $_W['uniacid'],'pwsn'=>$item['goodssn']));
				 if($pws){
					 $days = $pws['used_times']/86400;
                     $status = $pws['ishexiao'];
				 } else{
					$days = 0;
				}

			}

			if (empty($category)) {
				message('抱歉，请您先添加商品分类！', $this->createWebUrl('category', array('op' => 'post')), 'error');
			}
//			if(empty($pw)){
//				message('抱歉，请您先添加卡密！', $this->createWebUrl('code', array('op' => 'post')), 'error');
//
//			}
			if (checksubmit('submit')) {
                ini_set('max_execution_time', '0');
				if (empty($_GPC['goodsname'])) {
					message('请输入商品名称！');
				}
//				if (empty($_GPC['category']['parentid'])) {
//					message('请选择商品分类！');
//				}
				if(empty($_GPC['thumbs'])){
					$_GPC['thumbs'] = array();
				}

					if (empty($_GPC['num'])&&!$_GPC['id']) {
						message('请输入卡密生成数量！');
					}


				$goodssn =empty($_GPC['goodssn'])?$_W['uniacid'].sprintf('%d', time()).$_GPC['category']:$_GPC['goodssn'];


				$count = $_GPC['num'];

				if($_GPC['id']&&empty($_GPC['num'])){
					$count = 1;
				}

				if(($count > 20000||$count<1)){

						message('请输入正确的卡密数量');

				}else{

                 if($_GPC['id']&&empty($_GPC['num'])){

				 }else{

					$picid = pdo_fetch("SELECT max(pici) FROM ".tablename('haoman_virtuamall_pw')." WHERE uniacid = :uniacid", array(':uniacid' => $_W['uniacid']));
					$picid = $picid['max(pici)'];

					$pici = !empty($picid) ? ($picid+1) : 1;
					// print_r($picid);exit();

					$data = array('uniacid' => $_W['uniacid'], 'createtime' => time('Ymd'), 'codenum' => $count,'is_qrcode'=>$goodssn,'pici' => $pici,'status'=>1);
					$ss = pdo_insert('haoman_virtuamall_pici', $data);
					if($ss){
						$counts = pdo_fetchcolumn("SELECT sum(codenum) FROM " . tablename('haoman_virtuamall_pici') . " WHERE uniacid = :uniacid AND status = 1 and is_qrcode = :is_qrcode", array(':uniacid' => $_W['uniacid'],':is_qrcode'=>$goodssn));
					}
					for($i = 0; $i < $count; $i++){
						$randcode = $this->genkeyword(8);

						$code = new Code();
						$card_no = $code->encodeID($randcode,5);
						$card_pre = $this->GetRandStr(4);
						$card_vc = substr(md5($card_pre.$card_no),0,2);
						$card_vc = strtoupper($card_vc);
						$title = $card_pre.$card_no.$card_vc;
						$pwid = 'pwid'.date('Ymd') . sprintf('%d', time()).$i;

						$used_cardid = $this->foo($id);

						$updata = array(
							'pwsn'=>$goodssn,
							'pici' => $pici,
							'category'=>$_GPC['category'],
							'uniacid' => $_W['uniacid'],
							'pwid' => $pwid,
							'title' => $title,
							'used_times' => $_GPC['used_times']*24 * 3600,
							'num' => 1,
							'iscqr' => 1,
							'isused' => 0,
							'endtime' => 0,
							'activation_time' => 0,
							'used_cardid' => $used_cardid,
							'status' => 1,
							'ishexiao' => $_GPC['ishexiao'],
							'createtime' =>time(),
						);

						$temp = pdo_insert('haoman_virtuamall_pw', $updata);
					}

				}
				}

				$data = array(
					'weid' => intval($_W['uniacid']),
					'displayorder' => intval($_GPC['displayorder']),
					'title' => $_GPC['goodsname'],
					'pcate' => intval($_GPC['category']),
					'ccate' => 0,
					'thumb'=>$_GPC['thumb'],
					'type' => 2,
					'isrecommand' => 1,
					'ishot' => intval($_GPC['ishot']),
					'isnew' => intval($_GPC['isnew']),
					'isdiscount' => intval($_GPC['isdiscount']),
					'istime' => intval($_GPC['istime']),
					'timestart' => strtotime($_GPC['timestart']),
					'timeend' => strtotime($_GPC['timeend']),
					'description' => $_GPC['description'],
					'content' => htmlspecialchars_decode($_GPC['content']),
					'goodssn' => $goodssn,
					'unit' => $_GPC['unit'],
					'createtime' => TIMESTAMP,
					//'total' => intval($counts),
					'totalcnf' => intval($_GPC['totalcnf']),
					'marketprice' => $_GPC['marketprice'],
					'weight' => $_GPC['weight'],
					'costprice' => $_GPC['costprice'],
					'originalprice' => $_GPC['originalprice'],
					'productprice' => $_GPC['productprice'],
					'productsn' => $_GPC['productsn'],
					'credit' => sprintf('%.2f', $_GPC['credit']),
					'maxbuy' => intval($_GPC['maxbuy']),
					'usermaxbuy' => intval($_GPC['usermaxbuy']),
					'hasoption' => intval($_GPC['hasoption']),
					'sales' => intval($_GPC['sales']),
					'status' => 1,
				);

				if($counts){
					$data['total']=$counts-$item['sales_num'];
				}
				if ($data['total'] === -1) {
					$data['total'] = 0;
					$data['totalcnf'] = 2;
				}

				if(is_array($_GPC['thumbs'])){
					$data['thumb_url'] = serialize($_GPC['thumbs']);
				}
				if (empty($id)) {
					pdo_insert('haoman_virtuamall_goods', $data);
					$id = pdo_insertid();
				} else {
					unset($data['createtime']);

					pdo_update('haoman_virtuamall_goods', $data, array('id' => $id));
				}

				message('商品更新成功！', $this->createWebUrl('goods', array('op' => 'display', 'id' => $id)), 'success');
			}
		} elseif ($operation == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 15;
			$condition = ' WHERE `weid` = :weid AND `deleted` = :deleted';
			$params = array(':weid' => $_W['uniacid'], ':deleted' => '0');
			if (!empty($_GPC['keyword'])) {
				$condition .= ' AND `title` LIKE :title';
				$params[':title'] = '%' . trim($_GPC['keyword']) . '%';
			}

			if (!empty($_GPC['category'])) {
				$condition .= ' AND `pcate` = :pcate';
				$params[':pcate'] = intval($_GPC['category']);
			}
			if (isset($_GPC['status'])) {
				$condition .= ' AND `status` = :status';
				$params[':status'] = intval($_GPC['status']);
			}

			$sql = 'SELECT COUNT(*) FROM ' . tablename('haoman_virtuamall_goods') . $condition;
			$total = pdo_fetchcolumn($sql, $params);
			if (!empty($total)) {
				$sql = 'SELECT * FROM ' . tablename('haoman_virtuamall_goods') . $condition . ' ORDER BY `status` DESC, `displayorder` DESC,
						`id` DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
				$list = pdo_fetchall($sql, $params);
				$pager = pagination($total, $pindex, $psize);
			}

		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);

			$row = pdo_fetch("SELECT id, goodssn FROM " . tablename('haoman_virtuamall_goods') . " WHERE id = :id", array(':id' => $id));
			if (empty($row)) {
				message('抱歉，商品不存在或是已经被删除！');
			}

//			if (!empty($row['thumb'])) {
//				file_delete($row['thumb']);
//			}
//			pdo_delete('haoman_virtuamall_goods', array('id' => $id));
			//修改成不直接删除，而设置deleted=1
			pdo_update("haoman_virtuamall_pw", array("status" => 0), array('pwsn' => $row['goodssn']));
			pdo_update("haoman_virtuamall_pici", array("status" => 0), array('is_qrcode' => $row['goodssn']));
			pdo_update("haoman_virtuamall_goods", array("deleted" => 1), array('id' => $id));
			message('删除成功！', referer(), 'success');
		} elseif ($operation == 'productdelete') {
			$id = intval($_GPC['id']);
			pdo_delete('haoman_virtuamall_product', array('id' => $id));
			message('删除成功！', '', 'success');
		}
		include $this->template('goods');
	}

	public function doWebOrder() {
		global $_W, $_GPC;
		load()->func('tpl');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$status = $_GPC['status'];
			$sendtype = !isset($_GPC['sendtype']) ? 0 : $_GPC['sendtype'];
			$condition = "weid = :weid";
			$paras = array(':weid' => $_W['uniacid']);
			if (empty($starttime) || empty($endtime)) {
				$starttime = strtotime('-1 month');
				$endtime = time();
			}
			if (!empty($_GPC['time'])) {
				$starttime = strtotime($_GPC['time']['start']);
				$endtime = strtotime($_GPC['time']['end']) + 86399;
				$condition .= " AND createtime >= :starttime AND createtime <= :endtime ";
				$paras[':starttime'] = $starttime;
				$paras[':endtime'] = $endtime;
			}
			if (!empty($_GPC['paytype'])) {
				$condition .= " AND paytype = '{$_GPC['paytype']}'";
			} elseif ($_GPC['paytype'] === '0') {
				$condition .= " AND paytype = '{$_GPC['paytype']}'";
			}
			if (!empty($_GPC['keyword'])) {
				$condition .= " AND ordersn LIKE '%{$_GPC['keyword']}%'";
			}
			if (!empty($_GPC['member'])) {
				$condition .= " AND (realname LIKE '%{$_GPC['member']}%' or mobile LIKE '%{$_GPC['member']}%')";
			}
			if ($status != '') {
				$condition .= " AND status = '" . intval($status) . "'";
			}
			if (!empty($sendtype)) {
				$condition .= " AND sendtype = '" . intval($sendtype) . "' AND status != '3'";
			}

			$sql = 'SELECT * FROM ' . tablename('haoman_virtuamall_order') .   ' WHERE ' . $condition . ' ORDER BY status  DESC, createtime DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
			$list = pdo_fetchall($sql,$paras);

			$paytype = array (
					'0' => array('css' => 'default', 'name' => '未支付'),
					'1' => array('css' => 'danger','name' => '余额支付'),
					'2' => array('css' => 'info', 'name' => '在线支付'),
			);
			$orderstatus = array (
					'-1' => array('css' => 'default', 'name' => '已取消'),
					'0' => array('css' => 'danger', 'name' => '待付款'),
					'1' => array('css' => 'info', 'name' => '待发货'),
					'2' => array('css' => 'warning', 'name' => '待收货'),
					'3' => array('css' => 'success', 'name' => '已完成')
			);
			foreach ($list as &$value) {
				$s = $value['status'];
				$value['statuscss'] = $orderstatus[$value['status']]['css'];
				$value['status'] = $orderstatus[$value['status']]['name'];
				if ($s < 1) {
					$value['css'] = $paytype[$s]['css'];
					$value['paytype'] = $paytype[$s]['name'];
					continue;
				}
				$value['css'] = $paytype[$value['paytype']]['css'];
				if ($value['paytype'] == 2) {
					if (empty($value['transid'])) {
						$value['paytype'] = '支付宝支付';
					} else {
						$value['paytype'] = '微信支付';
					}
				} else {
					$value['paytype'] = $paytype[$value['paytype']]['name'];
				}
			}
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('haoman_virtuamall_order') . " o " ." left join ".tablename('mc_member_address')." a on o.addressid = a.id " ." WHERE $condition", $paras);
			$pager = pagination($total, $pindex, $psize);
			if (!empty($list)) {
				foreach ($list as &$row) {
					$row['dispatch'] = pdo_fetch("SELECT * FROM " . tablename('haoman_virtuamall_dispatch') . " WHERE id = :id", array(':id' => $row['dispatch']));
				}
				unset($row);
			}


		} elseif ($operation == 'detail') {


			$id = intval($_GPC['id']);
			$item = pdo_fetch("SELECT * FROM " . tablename('haoman_virtuamall_order') . " WHERE id = :id", array(':id' => $id));
			$ids = $item['tandid'];
			if (empty($item)) {
				message("抱歉，订单不存在!", referer(), "error");
			}
			$dispatch = pdo_fetch("SELECT * FROM " . tablename('haoman_virtuamall_dispatch') . " WHERE id = :id", array(':id' => $item['dispatch']));
			if (!empty($dispatch) && !empty($dispatch['express'])) {
				$express = pdo_fetch("select * from " . tablename('haoman_virtuamall_express') . " WHERE id=:id limit 1", array(":id" => $dispatch['express']));
			}
			$item['user'] = pdo_fetch("SELECT * FROM " . tablename('mc_member_address') . " WHERE id = {$item['addressid']}");
			$goods = pdo_fetchall("SELECT g.*, o.total,g.type,o.optionname,o.optionid,o.price as orderprice FROM " . tablename('haoman_virtuamall_order_goods') . " o left join " . tablename('haoman_virtuamall_goods') . " g on o.goodsid=g.id " . " WHERE o.orderid='{$ids}'");
			$item['goods'] = $goods;

		} elseif ($operation == 'delete') {
			/*订单删除*/
			$orderid = intval($_GPC['id']);
			if (pdo_delete('haoman_virtuamall_order', array('id' => $orderid))) {
				message('订单删除成功', $this->createWebUrl('order', array('op' => 'display')), 'success');
			} else {
				message('订单不存在或已被删除', $this->createWebUrl('order', array('op' => 'display')), 'error');
			}
		}

		include $this->template('order');
	}

	//设置订单商品的库存 minus  true 减少  false 增加
	private function setOrderStock($id = '', $minus = true) {
		$goods = pdo_fetchall("SELECT g.id, g.title,g.sales_num, g.thumb, g.unit, g.marketprice,g.total as goodstotal,o.total,o.optionid,g.sales FROM " . tablename('haoman_virtuamall_order_goods') . " o left join " . tablename('haoman_virtuamall_goods') . " g on o.goodsid=g.id " . " WHERE o.orderid='{$id}'");
		foreach ($goods as $item) {
			if ($minus) {
				//属性
//				if (!empty($item['optionid'])) {
//					pdo_query("update " . tablename('haoman_virtuamall_goods_option') . " set stock=stock-:stock where id=:id", array(":stock" => $item['total'], ":id" => $item['optionid']));
//				}
				$data = array();
				if (!empty($item['goodstotal']) && $item['goodstotal'] != -1) {
					$data['total'] = $item['goodstotal'] - $item['total'];
				   $data['sales_num'] = $item['sales_num']+$item['total'];
				}

				$data['sales'] = $item['sales'] + $item['total'];
				pdo_update('haoman_virtuamall_goods', $data, array('id' => $item['id']));
			} else {
				//属性
				if (!empty($item['optionid'])) {
					pdo_query("update " . tablename('haoman_virtuamall_goods_option') . " set stock=stock+:stock where id=:id", array(":stock" => $item['total'], ":id" => $item['optionid']));
				}
				$data = array();
				if (!empty($item['goodstotal']) && $item['goodstotal'] != -1) {
					$data['total'] = $item['goodstotal'] + $item['total'];
				}
				$data['sales'] = $item['sales'] - $item['total'];
				pdo_update('haoman_virtuamall_goods', $data, array('id' => $item['id']));
			}
		}
	}

	public function doWebNotice() {
		global $_GPC, $_W;
		load()->func('tpl');
		$operation = empty($_GPC['op']) ? 'display' : $_GPC['op'];
		$operation = in_array($operation, array('display')) ? $operation : 'display';
		$pindex = max(1, intval($_GPC['page']));
		$psize = 50;
		if (!empty($_GPC['date'])) {
			$starttime = strtotime($_GPC['date']['start']);
			$endtime = strtotime($_GPC['date']['end']) + 86399;
		} else {
			$starttime = strtotime('-1 month');
			$endtime = time();
		}
		$where = " WHERE `weid` = :weid AND `createtime` >= :starttime AND `createtime` < :endtime";
		$paras = array(
			':weid' => $_W['uniacid'],
			':starttime' => $starttime,
			':endtime' => $endtime
		);
		$keyword = $_GPC['keyword'];
		if (!empty($keyword)) {
			$where .= " AND `feedbackid`=:feedbackid";
			$paras[':feedbackid'] = $keyword;
		}
		$type = empty($_GPC['type']) ? 0 : $_GPC['type'];
		$type = intval($type);
		if ($type != 0) {
			$where .= " AND `type`=:type";
			$paras[':type'] = $type;
		}
		$status = empty($_GPC['status']) ? 0 : intval($_GPC['status']);
		$status = intval($status);
		if ($status != -1) {
			$where .= " AND `status` = :status";
			$paras[':status'] = $status;
		}
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('haoman_virtuamall_feedback') . $where, $paras);
		$list = pdo_fetchall("SELECT * FROM " . tablename('haoman_virtuamall_feedback') . $where . " ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $paras);
		$pager = pagination($total, $pindex, $psize);
		$transids = array();
		foreach ($list as $row) {
			$transids[] = $row['transid'];
		}
		if (!empty($transids)) {
			$sql = "SELECT * FROM " . tablename('haoman_virtuamall_order') . " WHERE weid='{$_W['uniacid']}' AND transid IN ( '" . implode("','", $transids) . "' )";
			$orders = pdo_fetchall($sql, array(), 'transid');
		}
		$addressids = array();
		if(is_array($orders)){
			foreach ($orders as $transid => $order) {
				$addressids[] = $order['addressid'];
			}
		}
		$addresses = array();
		if (!empty($addressids)) {
			$sql = "SELECT * FROM " . tablename('mc_member_address') . " WHERE uniacid='{$_W['uniacid']}' AND id IN ( '" . implode("','", $addressids) . "' )";
			$addresses = pdo_fetchall($sql, array(), 'id');
		}
		foreach ($list as &$feedback) {
			$transid = $feedback['transid'];
			$order = $orders[$transid];
			$feedback['order'] = $order;
			$addressid = $order['addressid'];
			$feedback['address'] = $addresses[$addressid];
		}
		include $this->template('notice');
	}

	public function getCartTotal() {
		global $_W;
		$cartotal = pdo_fetchcolumn("select sum(total) from " . tablename('haoman_virtuamall_cart') . " where weid = '{$_W['uniacid']}' and from_user='{$_W['fans']['from_user']}'");
		return empty($cartotal) ? 0 : $cartotal;
	}

	private function getFeedbackType($type) {
		$types = array(1 => '维权', 2 => '告警');
		return $types[intval($type)];
	}

	private function getFeedbackStatus($status) {
		$statuses = array('未解决', '用户同意', '用户拒绝');
		return $statuses[intval($status)];
	}

	public function doMobilelist() {
		global $_GPC, $_W;
		$pindex = max(1, intval($_GPC['page']));
		$psize = 4;
		$condition = '';



		if (!empty($_GPC['ccate'])) {
			$cid = intval($_GPC['ccate']);
			$condition .= " AND ccate = '{$cid}'";
			$_GPC['pcate'] = pdo_fetchcolumn("SELECT parentid FROM " . tablename('haoman_virtuamall_category') . " WHERE id = :id", array(':id' => intval($_GPC['ccate'])));
		} elseif (!empty($_GPC['pcate'])) {
			$cid = intval($_GPC['pcate']);
			$condition .= " AND pcate = '{$cid}'";
		}
		if (!empty($_GPC['keyword'])) {
			$condition .= " AND title LIKE '%{$_GPC['keyword']}%'";
		}
		$children = array();
		$category = pdo_fetchall("SELECT * FROM " . tablename('haoman_virtuamall_category') . " WHERE weid = '{$_W['uniacid']}' and enabled=1 ORDER BY parentid ASC, displayorder DESC", array(), 'id');

		foreach ($category as $index => $row) {
			if (!empty($row['parentid'])) {
				$children[$row['parentid']][$row['id']] = $row;
				unset($category[$index]);
			}
		}


		$recommandcategory = array();
		$rlist = pdo_fetchall("SELECT * FROM " . tablename('haoman_virtuamall_goods') . " WHERE weid = '{$_W['uniacid']}' and deleted=0 AND status = '1'  ORDER BY displayorder DESC, sales DESC  ");

		foreach ($category as &$c) {
			if ($c['isrecommand'] == 1) {
				$c['list'] = pdo_fetchall("SELECT * FROM " . tablename('haoman_virtuamall_goods') . " WHERE weid = '{$_W['uniacid']}' and deleted=0 AND status = '1'  and pcate='{$c['id']}'  ORDER BY displayorder DESC, sales DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
				$c['total'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('haoman_virtuamall_goods') . " WHERE weid = '{$_W['uniacid']}'  and deleted=0  AND status = '1' and pcate='{$c['id']}'");
				$c['pager'] = pagination($c['total'], $pindex, $psize, $url = '', $context = array('before' => 0, 'after' => 0, 'ajaxcallback' => ''));
				$recommandcategory[] = $c;
			}
			if (!empty($children[$c['id']])) {
				foreach ($children[$c['id']] as &$child) {
					if ($child['isrecommand'] == 1) {
						$child['list'] = pdo_fetchall("SELECT * FROM " . tablename('haoman_virtuamall_goods') . " WHERE weid = '{$_W['uniacid']}'  and deleted=0 AND status = '1'  and pcate='{$c['id']}' and ccate='{$child['id']}'  ORDER BY displayorder DESC, sales DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
						$child['total'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('haoman_virtuamall_goods') . " WHERE weid = '{$_W['uniacid']}'  and deleted=0  AND status = '1' and pcate='{$c['id']}' and ccate='{$child['id']}' ");
						$child['pager'] = pagination($child['total'], $pindex, $psize, $url = '', $context = array('before' => 0, 'after' => 0, 'ajaxcallback' => ''));
						$recommandcategory[] = $child;
					}
				}
				unset($child);
			}
		}

		unset($c);
		$carttotal = $this->getCartTotal();
		//幻灯片
		$advs = pdo_fetchall("select * from " . tablename('haoman_virtuamall_adv') . " where enabled=1 and weid= '{$_W['uniacid']}'");
        $picture =  pdo_fetch("select thumb from " . tablename('haoman_virtuamall_adv') . " where enabled=1 and weid= '{$_W['uniacid']}'");

        foreach ($advs as &$adv) {
			if (substr($adv['link'], 0, 5) != 'http:') {
				$adv['link'] = "http://" . $adv['link'];
			}
		}
		unset($adv);
		//首页推荐
		$rpindex = max(1, intval($_GPC['rpage']));
		$rpsize = 4;
		$condition = ' and isrecommand=1';
	//	$rlist = pdo_fetchall("SELECT * FROM " . tablename('haoman_virtuamall_goods') . " WHERE weid = '{$_W['uniacid']}'  and deleted=0 AND status = '1' $condition ORDER BY displayorder DESC, sales DESC LIMIT " . ($rpindex - 1) * $rpsize . ',' . $rpsize);

        $settings = $this->module['config'];
        //分享信息
        $sharetitle = empty($settings['share_title']) ? '这里的商品好便宜哦!' : $settings['share_title'];
        $sharedesc = empty($settings['share_desc']) ? '亲，这里的商品好便宜哦！！' : str_replace("\r\n", " ", $settings['share_desc']);
        if (!empty($settings['share_imgurl'])) {
            $shareimg = $settings['share_imgurl'];
        } else {
            $shareimg = $picture['thumb'];
        }

        $jssdk = new JSSDK();
        $package = $jssdk->GetSignPackage();
		include $this->template('list');
	}
	
	public function doMobilelistmore_rec() {
		global $_GPC, $_W;
		$pindex = max(1, intval($_GPC['page']));
		$psize = 4;
		$condition = ' and isrecommand=1 ';
		$list = pdo_fetchall("SELECT * FROM " . tablename('haoman_virtuamall_goods') . " WHERE weid = '{$_W['uniacid']}'  and deleted=0 AND status = '1' $condition ORDER BY displayorder DESC, sales DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
		include $this->template('list_more');
	}


	public function doMobilelistmore() {
		global $_GPC, $_W;
		$pindex = max(1, intval($_GPC['page']));
		$psize = 4;
		$condition = '';
		$params = array(':weid' => $_W['uniacid']);
		$cid = intval($_GPC['ccate']);
		if (empty($cid)) {
			return NULL;
		}

		$catePid = $_GPC['pcate'];
		if (empty($catePid)) {
			$condition .= ' AND `pcate` = :pcate';
			$params[':pcate'] = $cid;
		} else {
			$condition .= ' AND `ccate` = :ccate';
			$params[':ccate'] = $cid;
		}


		$sql = 'SELECT * FROM ' . tablename('haoman_virtuamall_goods') . ' WHERE `weid` = :weid AND `deleted` = :deleted AND `status` = :status ' . $condition .
				' ORDER BY `displayorder` DESC, `sales` DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
		$params[':deleted'] = 0;
		$params[':status'] = 1;
		$list = pdo_fetchall($sql, $params);

		include $this->template('list_more');

	}

	public function doMobilelist2() {
		global $_GPC, $_W;
		$pindex = max(1, intval($_GPC["page"]));
		$psize = 10;
		$condition = '';
		if (!empty($_GPC['ccate'])) {
			$cid = intval($_GPC['ccate']);
			$condition .= " AND ccate = '{$cid}'";
			$_GPC['pcate'] = pdo_fetchcolumn("SELECT parentid FROM " . tablename('haoman_virtuamall_category') . " WHERE id = :id", array(':id' => intval($_GPC['ccate'])));
		} elseif (!empty($_GPC['pcate'])) {
			$cid = intval($_GPC['pcate']);
			$condition .= " AND pcate = '{$cid}'";
		}
		if (!empty($_GPC['keyword'])) {
			$condition .= " AND title LIKE '%{$_GPC['keyword']}%'";
		}
		$sort = empty($_GPC['sort']) ? 0 : $_GPC['sort'];
		$sortfield = "displayorder asc";
		$sortb0 = empty($_GPC['sortb0']) ? "desc" : $_GPC['sortb0'];
		$sortb1 = empty($_GPC['sortb1']) ? "desc" : $_GPC['sortb1'];
		$sortb2 = empty($_GPC['sortb2']) ? "desc" : $_GPC['sortb2'];
		$sortb3 = empty($_GPC['sortb3']) ? "asc" : $_GPC['sortb3'];
		if ($sort == 0) {
			$sortb00 = $sortb0 == "desc" ? "asc" : "desc";
			$sortfield = "createtime " . $sortb0;
			$sortb11 = "desc";
			$sortb22 = "desc";
			$sortb33 = "asc";
		} else if ($sort == 1) {
			$sortb11 = $sortb1 == "desc" ? "asc" : "desc";
			$sortfield = "sales " . $sortb1;
			$sortb00 = "desc";
			$sortb22 = "desc";
			$sortb33 = "asc";
		} else if ($sort == 2) {
			$sortb22 = $sortb2 == "desc" ? "asc" : "desc";
			$sortfield = "viewcount " . $sortb2;
			$sortb00 = "desc";
			$sortb11 = "desc";
			$sortb33 = "asc";
		} else if ($sort == 3) {
			$sortb33 = $sortb3 == "asc" ? "desc" : "asc";
			$sortfield = "marketprice " . $sortb3;
			$sortb00 = "desc";
			$sortb11 = "desc";
			$sortb22 = "desc";
		}
		$sorturl = $this->createMobileUrl('list2', array("keyword" => $_GPC['keyword'], "pcate" => $_GPC['pcate'], "ccate" => $_GPC['ccate']), true);
		if (!empty($_GPC['isnew'])) {
			$condition .= " AND isnew = 1";
			$sorturl.="&isnew=1";
		}
		if (!empty($_GPC['ishot'])) {
			$condition .= " AND ishot = 1";
			$sorturl.="&ishot=1";
		}
		if (!empty($_GPC['isdiscount'])) {
			$condition .= " AND isdiscount = 1";
			$sorturl.="&isdiscount=1";
		}
		if (!empty($_GPC['istime'])) {
			$condition .= " AND istime = 1 and " . time() . ">=timestart and " . time() . "<=timeend";
			$sorturl.="&istime=1";
		}
		$children = array();
		$category = pdo_fetchall("SELECT * FROM " . tablename('haoman_virtuamall_category') . " WHERE weid = '{$_W['uniacid']}' and enabled=1 ORDER BY parentid ASC, displayorder DESC", array(), 'id');
		foreach ($category as $index => $row) {
			if (!empty($row['parentid'])) {
				$children[$row['parentid']][$row['id']] = $row;
				unset($category[$index]);
			}
		}
		$list = pdo_fetchall("SELECT * FROM " . tablename('haoman_virtuamall_goods') . " WHERE weid = '{$_W['uniacid']}'  and deleted=0 AND status = '1' $condition ORDER BY $sortfield LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
		foreach ($list as &$r) {
			if ($r['istime'] == 1) {
				$arr = $this->time_tran($r['timeend']);
				$r['timelaststr'] = $arr[0];
				$r['timelast'] = $arr[1];
			}
		}
		unset($r);
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('haoman_virtuamall_goods') . " WHERE weid = '{$_W['uniacid']}'  and deleted=0  AND status = '1' $condition");
		$pager = pagination($total, $pindex, $psize, $url = '', $context = array('before' => 0, 'after' => 0, 'ajaxcallback' => ''));
		$carttotal = $this->getCartTotal();
		include $this->template('list2');
	}

	function time_tran($the_time) {
		$timediff = $the_time - time();
		$days = intval($timediff / 86400);
		if (strlen($days) <= 1) {
			$days = "0" . $days;
		}
		$remain = $timediff % 86400;
		$hours = intval($remain / 3600);
		;
		if (strlen($hours) <= 1) {
			$hours = "0" . $hours;
		}
		$remain = $remain % 3600;
		$mins = intval($remain / 60);
		if (strlen($mins) <= 1) {
			$mins = "0" . $mins;
		}
		$secs = $remain % 60;
		if (strlen($secs) <= 1) {
			$secs = "0" . $secs;
		}
		$ret = "";
		if ($days > 0) {
			$ret.=$days . " 天 ";
		}
		if ($hours > 0) {
			$ret.=$hours . ":";
		}
		if ($mins > 0) {
			$ret.=$mins . ":";
		}
		$ret.=$secs;
		return array("倒计时 " . $ret, $timediff);
	}

	public function doMobileMyCart() {
		global $_W, $_GPC;
		$this->checkAuth();
		$op = $_GPC['op'];
		if ($op == 'add') {
			$goodsid = intval($_GPC['id']);
			$total = intval($_GPC['total']);
			$total = empty($total) ? 1 : $total;
			$optionid = intval($_GPC['optionid']);
			$goods = pdo_fetch("SELECT id, type, total,marketprice,maxbuy FROM " . tablename('haoman_virtuamall_goods') . " WHERE id = :id", array(':id' => $goodsid));
			if (empty($goods)) {
				$result['message'] = '抱歉，该商品不存在或是已经被删除！';
				message($result, '', 'ajax');
			}
			$marketprice = $goods['marketprice'];
			if (!empty($optionid)) {
				$option = pdo_fetch("select marketprice from " . tablename('haoman_virtuamall_goods_option') . " where id=:id limit 1", array(":id" => $optionid));
				if (!empty($option)) {
					$marketprice = $option['marketprice'];
				}
			}
			$row = pdo_fetch("SELECT id, total FROM " . tablename('haoman_virtuamall_cart') . " WHERE from_user = :from_user AND weid = '{$_W['uniacid']}' AND goodsid = :goodsid  and optionid=:optionid", array(':from_user' => $_W['fans']['from_user'], ':goodsid' => $goodsid,':optionid'=>$optionid));
			if ($row == false) {
				//不存在
				$data = array(
					'weid' => $_W['uniacid'],
					'goodsid' => $goodsid,
					'goodstype' => $goods['type'],
					'marketprice' => $marketprice,
					'from_user' => $_W['fans']['from_user'],
					'total' => $total,
					'optionid' => $optionid
				);
				pdo_insert('haoman_virtuamall_cart', $data);
			} else {
				//累加最多限制购买数量
				$t = $total + $row['total'];
				if (!empty($goods['maxbuy'])) {
					if ($t > $goods['maxbuy']) {
						$t = $goods['maxbuy'];
					}
				}
				//存在
				$data = array(
					'marketprice' => $marketprice,
					'total' => $t,
					'optionid' => $optionid
				);
				pdo_update('haoman_virtuamall_cart', $data, array('id' => $row['id']));
			}
			//返回数据
			$carttotal = $this->getCartTotal();
			$result = array(
				'result' => 1,
				'total' => $carttotal
			);
			die(json_encode($result));
		} else if ($op == 'clear') {
			pdo_delete('haoman_virtuamall_cart', array('from_user' => $_W['fans']['from_user'], 'weid' => $_W['uniacid']));
			die(json_encode(array("result" => 1)));
		} else if ($op == 'remove') {
			$id = intval($_GPC['id']);
			pdo_delete('haoman_virtuamall_cart', array('from_user' => $_W['fans']['from_user'], 'weid' => $_W['uniacid'], 'id' => $id));
			die(json_encode(array("result" => 1, "cartid" => $id)));
		} else if ($op == 'update') {
			$id = intval($_GPC['id']);
			$num = intval($_GPC['num']);
			$sql = "update " . tablename('haoman_virtuamall_cart') . " set total=$num where id=:id";
			pdo_query($sql, array(":id" => $id));
			die(json_encode(array("result" => 1)));
		} else {
			$list = pdo_fetchall("SELECT * FROM " . tablename('haoman_virtuamall_cart') . " WHERE  weid = '{$_W['uniacid']}' AND from_user = '{$_W['fans']['from_user']}'");
			$totalprice = 0;
			if (!empty($list)) {
				foreach ($list as &$item) {
					$goods = pdo_fetch("SELECT  title, thumb, marketprice, unit, total,maxbuy FROM " . tablename('haoman_virtuamall_goods') . " WHERE id=:id limit 1", array(":id" => $item['goodsid']));
					//属性
					$option = pdo_fetch("select title,marketprice,stock from " . tablename("haoman_virtuamall_goods_option") . " where id=:id limit 1", array(":id" => $item['optionid']));
					if ($option) {
						$goods['title'] = $goods['title'];
						$goods['optionname'] = $option['title'];
						$goods['marketprice'] = $option['marketprice'];
						$goods['total'] = $option['stock'];
					}
					$item['goods'] = $goods;
					$item['totalprice'] = (floatval($goods['marketprice']) * intval($item['total']));
					$totalprice += $item['totalprice'];
				}
				unset($item);
			}
			include $this->template('cart');
		}
	}

	public function doMobileConfirm() {
		global $_W, $_GPC;
		checkauth();
		$totalprice = 0;
		$allgoods = array();
		$id = intval($_GPC['id']);
		$optionid = intval($_GPC['optionid']);

		$total = intval($_GPC['total']);
		if ( (empty($total)) || ($total < 1) ) {
			$total = 1;
		}
		$direct = false; //是否是直接购买
		$returnUrl = ''; //当前连接
		if (!empty($id)) {
			$sql = 'SELECT `id`,`pcate`, `goodssn`,`thumb`, `title`, `weight`, `marketprice`, `total`, `type`, `totalcnf`, `sales`, `unit`, `istime`, `timeend`, `usermaxbuy`FROM ' .tablename('haoman_virtuamall_goods') . ' WHERE `id` = :id';
			$item = pdo_fetch($sql, array(':id' => $id));

			if (empty($item)) {
				message('商品不存在或已经下架', $this->createMobileUrl('detail', array('id' => $id)), 'error');
			}
			if ($item['total'] - $total < 0) {
				message('抱歉，[' . $item['title'] . ']库存不足！', $this->createMobileUrl('confirm'), 'error');
			}

			if (!empty($optionid)) {
				$option = pdo_fetch("select title,marketprice,weight,stock from " . tablename("haoman_virtuamall_goods_option") . " where id=:id limit 1", array(":id" => $optionid));
				if ($option) {
					$item['optionid'] = $optionid;
					$item['title'] = $item['title'];
					$item['pcate'] = $item['pcate'];
					$item['optionname'] = $option['title'];
					$item['marketprice'] = $option['marketprice'];
					$item['weight'] = $option['weight'];
				}
			}

			$item['stock'] = $item['total'];
			$item['total'] = $total;
			$item['totalprice'] = $total * $item['marketprice'];
			$allgoods[] = $item;
			$totalprice += $item['totalprice'];
			if ($item['type'] == 1) {
				$needdispatch = true;
			}
			$direct = true;

			// 检查用户最多购买数量
			$sql = 'SELECT SUM(`og`.`total`) AS `orderTotal` FROM ' . tablename('haoman_virtuamall_order_goods') . ' AS `og` JOIN ' . tablename('haoman_virtuamall_order') . ' AS `o` ON `og`.`orderid` = `o`.`id` WHERE `og`.`goodsid` = :goodsid AND `o`.`from_user` = :from_user';
			$params = array(':goodsid' => $id, ':from_user' => $_W['fans']['from_user']);
			$orderTotal = pdo_fetchcolumn($sql, $params);
			if ( (($orderTotal + $item['total']) > $item['usermaxbuy']) && (!empty($item['usermaxbuy']))) {
				message('您已经超过购买数量了', $this->createMobileUrl('detail', array('id' => $id)), 'error');
			}

			$returnUrl = urlencode($_W['siteurl']);
		}
		if (!$direct) {
			//（从购物车购买）
			$list = pdo_fetchall("SELECT * FROM " . tablename('haoman_virtuamall_cart') . " WHERE  weid = '{$_W['uniacid']}' AND from_user = '{$_W['fans']['from_user']}'");
			if (!empty($list)) {
				foreach ($list as &$g) {
					$item = pdo_fetch("select id,thumb,goodssn,pcate,title,weight,marketprice,total,type,totalcnf,sales,unit from " . tablename("haoman_virtuamall_goods") . " where id=:id limit 1", array(":id" => $g['goodsid']));
					//属性
					$option = pdo_fetch("select title,marketprice,weight,stock from " . tablename("haoman_virtuamall_goods_option") . " where id=:id limit 1", array(":id" => $g['optionid']));
					if ($option) {
						$item['optionid'] = $g['optionid'];
						$item['pcate'] = $item['pcate'];
						$item['title'] = $item['title'];
						$item['optionname'] = $option['title'];
						$item['marketprice'] = $option['marketprice'];
						$item['weight'] = $option['weight'];
					}
					$item['stock'] = $item['total'];
					$item['total'] = $g['total'];
					$item['totalprice'] = $g['total'] * $item['marketprice'];
					$allgoods[] = $item;
					$totalprice += $item['totalprice'];
					if ($item['type'] == 1) {
						$needdispatch = true;
					}
				}
				unset($g);
			}
			$returnurl = $this->createMobileUrl("confirm");
		}

		if (count($allgoods) <= 0) {
			header("location: " . $this->createMobileUrl('myorder'));
			exit();
		}

		if (checksubmit('submit')) {
			// 是否自提
			$sendtype = 1;
			$address = pdo_fetch("SELECT * FROM " . tablename('mc_member_address') . " WHERE id = :id", array(':id' => intval($_GPC['address'])));

				if (empty($address)) {
					message('抱歉，请您填写姓名和电话！');
				}


			// 商品价格
			$goodsprice = 0;
			foreach ($allgoods as $row) {
				$goodsprice += $row['totalprice'];
			}
			// 运费
			$dispatchid = intval($_GPC['dispatch']);
			$dispatchprice = 0;
			foreach ($dispatch as $d) {
				if ($d['id'] == $dispatchid) {
					$dispatchprice = $d['price'];
					$sendtype = $d['dispatchtype'];
				}
			}

			$data = array(
				'weid' => $_W['uniacid'],
				'from_user' => $_W['fans']['from_user'],
				'ordersn' => date('md') . random(4, 1),
				'price' => $goodsprice + $dispatchprice,
				'dispatchprice' => $dispatchprice,
				'goodsprice' => $goodsprice,
				'status' => 0,
				'statuss' => 0,
				'tandid' => date('YmdHi').random(8, 1),
				'sendtype' => intval($sendtype),
				'dispatch' => $dispatchid,
				'goodstype' => intval($item['type']),
				'remark' => $_GPC['remark'],
				'addressid' => $address['id'],
				'realname' => $address['username'],
				'mobile' => $address['mobile'],
				'address' => $address['province'].$address['city'].$address['district'].$address['address'],
				'createtime' => TIMESTAMP
			);

			pdo_insert('haoman_virtuamall_order', $data);

			$orderids = pdo_insertid();

			$tandid = pdo_fetch("SELECT tandid FROM " . tablename('haoman_virtuamall_order') . " WHERE id = :id", array(':id' => $orderids));

			$orderid = $tandid['tandid'];

			//插入订单商品
			foreach ($allgoods as $row) {
				if (empty($row)) {
					continue;
				}
				$d = array(
					'weid' => $_W['uniacid'],
					'goodsid' => $row['id'],
					'orderid' => $orderid,
					'total' => $row['total'],
					'category' => $row['pcate'],
					'price' => $row['marketprice'],
					'createtime' => TIMESTAMP,
					'optionid' => $row['optionid'],
					'pwid' => $row['goodssn']
				);
				$o = pdo_fetch("select title from " . tablename('haoman_virtuamall_goods_option') . " where id=:id limit 1", array(":id" => $row['optionid']));
				if (!empty($o)) {
					$d['optionname'] = $o['title'];
				}
				pdo_insert('haoman_virtuamall_order_goods', $d);
//				pdo_update('haoman_virtuamall_pici', array('is_qrcode2'=>1), array('is_qrcode' => $row['goodssn']));

			}
			// 清空购物车
			if (!$direct) {
				pdo_delete("haoman_virtuamall_cart", array("weid" => $_W['uniacid'], "from_user" => $_W['fans']['from_user']));
			}
			// 变更商品库存
			if (empty($item['totalcnf'])) {

                //拍下减少库存
				$this->setOrderStock($orderid);
			}
			message('提交订单成功,现在跳转到付款页面...', $this->createMobileUrl('pay', array('orderid' => $orderid)), 'success');
		}
		$carttotal = $this->getCartTotal();
		$profile = fans_search($_W['fans']['from_user'], array('resideprovince', 'residecity', 'residedist', 'address', 'realname', 'mobile'));
		$row = pdo_fetch("SELECT * FROM " . tablename('mc_member_address') . " WHERE isdefault = 1 and uid = :uid limit 1", array(':uid' => $_W['fans']['uid']));
		include $this->template('confirm');
	}

	//设置订单积分
	public function setOrderCredit($orderid, $add = true) {
		global $_W;
		$order = pdo_fetch("SELECT * FROM " . tablename('haoman_virtuamall_order') . " WHERE tandid = :tandid limit 1", array(':tandid' => $orderid));
		if (empty($order)) {
			return false;
		}
		$sql = 'SELECT `goodsid`, `total` FROM ' . tablename('haoman_virtuamall_order_goods') . ' WHERE `orderid` = :orderid';
		$orderGoods = pdo_fetchall($sql, array(':orderid' => $orderid));
		if (!empty($orderGoods)) {
			$credit = 0.00;
			$sql = 'SELECT `credit` FROM ' . tablename('haoman_virtuamall_goods') . ' WHERE `id` = :id';
			foreach ($orderGoods as $goods) {
				$goodsCredit = pdo_fetchcolumn($sql, array(':id' => $goods['goodsid']));
				$credit += $goodsCredit * floatval($goods['total']);
			}
		}
		//增加积分
		if (!empty($credit)) {
			load()->model('mc');
			load()->func('compat.biz');
			$uid = mc_openid2uid($order['from_user']);
			$fans = fans_search($uid, array("credit1"));
			if (!empty($fans)) {
				if (!empty($add)) {
					mc_credit_update($_W['member']['uid'], 'credit1', $credit, array('0' => $_W['member']['uid'], '购买商品赠送'));
				} else {
					mc_credit_update($_W['member']['uid'], 'credit1', 0 - $credit, array('0' => $_W['member']['uid'], '微商城操作'));
				}
			}
		}
	}

	public function doMobilePay() {
		global $_W, $_GPC;
		$this->checkAuth();
		$orderid = $_GPC['orderid'];

		$order = pdo_fetch("SELECT * FROM " . tablename('haoman_virtuamall_order') . " WHERE tandid = :tandid", array(':tandid' => $orderid));

			if ($order['status'] != '0') {
			message('抱歉，您的订单已经付款或是被关闭，请重新进入付款！', $this->createMobileUrl('myorder'), 'error');
		}
		if (checksubmit('codsubmit')) {
			$ordergoods = pdo_fetchall("SELECT goodsid, total,optionid FROM " . tablename('haoman_virtuamall_order_goods') . " WHERE orderid = '{$orderid}'", array(), 'goodsid');
			if (!empty($ordergoods)) {
				$goods = pdo_fetchall("SELECT id, title, thumb, marketprice, unit, total,credit FROM " . tablename('haoman_virtuamall_goods') . " WHERE id IN ('" . implode("','", array_keys($ordergoods)) . "')");
			}

			pdo_update('haoman_virtuamall_order', array('status' => '1', 'paytype' => '3'), array('id' => $orderid));
			message('订单提交成功，请您收到货时付款！', $this->createMobileUrl('myorder'), 'success');
		}
		if (checksubmit()) {
			if ($order['paytype'] == 1 && $_W['fans']['credit2'] < $order['price']) {
				message('抱歉，您帐户的余额不够支付该订单，请充值！', create_url('mobile/module/charge', array('name' => 'member', 'weid' => $_W['uniacid'])), 'error');
			}
			if ($order['price'] == '0') {
				$this->payResult(array('tid' => $orderid, 'from' => 'return', 'type' => 'credit2'));
				exit;
			}
		}
		// 商品编号
		$sql = 'SELECT `goodsid` FROM ' . tablename('haoman_virtuamall_order_goods') . " WHERE `orderid` = :orderid";
		$goodsId = pdo_fetchcolumn($sql, array(':orderid' => $orderid));
		// 商品名称
		$sql = 'SELECT `title` FROM ' . tablename('haoman_virtuamall_goods') . " WHERE `id` = :id";
		$goodsTitle = pdo_fetchcolumn($sql, array(':id' => $goodsId));

		$params['tid'] = $orderid;
		$params['user'] = $_W['fans']['from_user'];
		$params['fee'] = $order['price'];
		$params['title'] = $goodsTitle;
		$params['ordersn'] = $order['ordersn'];
		$params['virtual'] = $order['goodstype'] == 2 ? true : false;

		include $this->template('pay');
	}

	public function payResult($params) {
		global $_W;

		$fee = intval($params['fee']);

		if($params['result'] == 'success'&& $params['from'] == 'notify'){

			$data = array('status' => $params['result'] == 'success' ? 2 : 0,'statuss'=> $params['result'] == 'success' ? 2 : 0);
			$paytype = array('credit' => '1', 'wechat' => '2', 'alipay' => '2', 'delivery' => '3');


			// 卡券代金券备注
			if (!empty($params['is_usecard'])) {
				$cardType = array('1' => '微信卡券', '2' => '系统代金券');
				$data['paydetail'] = '使用' . $cardType[$params['card_type']] . '支付了' . ($params['fee'] - $params['card_fee']);
				$data['paydetail'] .= '元，实际支付了' . $params['card_fee'] . '元。';
			}

			$data['paytype'] = $paytype[$params['type']];
			if ($params['type'] == 'wechat') {
				$data['transid'] = $params['tag']['transaction_id'];
			}
			if ($params['type'] == 'delivery') {
				$data['status'] = 1;
			}
			$data['paytime']= time();

            $order = pdo_fetch("SELECT * FROM " . tablename('haoman_virtuamall_order') . " WHERE tandid = :tandid", array(':tandid' => $params['tid']));

            if($order['status']!=3){
              $item =  pdo_update('haoman_virtuamall_order', $data, array('tandid' => $params['tid'],'status'=>0));
            }

            if($item){
                //会多执行一次
                $sql = 'SELECT `goodsid`,`total` FROM ' . tablename('haoman_virtuamall_order_goods') . ' WHERE `orderid` = :orderid';
                $goodsId = pdo_fetchall($sql, array(':orderid' => $params['tid']));
                foreach ($goodsId as $v){
                    $sql = 'SELECT `total`, `totalcnf`,`sales_num` FROM ' . tablename('haoman_virtuamall_goods') . ' WHERE `id` = :id';
                    $goodsInfo = pdo_fetch($sql, array(':id' => $v['goodsid']));
                    // 更改库存
                    if ($goodsInfo['totalcnf'] == '1' && !empty($goodsInfo['total'])) {
                        pdo_update('haoman_virtuamall_goods', array('total' => $goodsInfo['total'] - $v['total'],'sales_num'=>$goodsInfo['sales_num']+$v['total']), array('id' => $v['goodsid']));
                    }
                }
               $settings = $this->module['config'];

                $openid_arr =  explode("丨",$settings['openid']);
                  for ($i=0;$i<count($openid_arr);$i++){
                      $v = $openid_arr[$i];
                      $template =array(

                          "touser"=>$v,//$from_user,

                          "template_id"=>$settings['noticeemail'],

                          "url"=>"",

                          "topcolor"=>"#FF0000",

                          "data"=>array('first'=>array('value'=>"有新订单了，赶紧查收。",

                              'color'=>"#743A3A",

                          ),

                              'keyword1'=>array('value'=>"[".$params['tid']."]",

                                  'color'=>'#000000',

                              ),

                              'keyword2'=>array('value'=>"[".$order['price']."]",

                                  'color'=>'#000000',

                              ),

                              'keyword3'=>array('value'=>date("Y-m-d H:i:s",time()),

                                  'color'=>'#00ff00',

                              ),

                              'keyword4'=>array('value'=>"[".$order['realname']."]",

                                  'color'=>"#000000",

                              ),
                              'keyword5'=>array('value'=>"[".$order['mobile']."]",

                                  'color'=>"#000000",

                              ),

                              'remark'=>array('value'=>"恭喜恭喜！！"),

                          )

                      );

                      $rest = $this->send_template_message(json_encode($template));

                  }
            }




		}
		if (empty($params['result']) || $params['result'] != 'success'&& $params['from'] == 'notify') {
			message('支付失败！', '', 'error');
		}
		if ($params['from'] == 'return') {

			//积分变更
			$this->setOrderCredit($params['tid']);

//			if (!empty($this->module['config']['noticeemail']) || !empty($this->module['config']['mobile'])) {
//				$order = pdo_fetch("SELECT `price`, `paytype`, `from_user`, `addressid` FROM " . tablename('haoman_virtuamall_order') . " WHERE id = '{$params['tid']}'");
//				$ordergoods = pdo_fetchall("SELECT goodsid, total FROM " . tablename('haoman_virtuamall_order_goods') . " WHERE orderid = '{$params['tid']}'", array(), 'goodsid');
//				$goods = pdo_fetchall("SELECT id, title, thumb, marketprice, unit, total FROM " . tablename('haoman_virtuamall_goods') . " WHERE id IN ('" . implode("','", array_keys($ordergoods)) . "')");
//				$address = pdo_fetch("SELECT * FROM " . tablename('mc_member_address') . " WHERE id = :id", array(':id' => $order['addressid']));
//
//			}

			$setting = uni_setting($_W['uniacid'], array('creditbehaviors'));
			$credit = $setting['creditbehaviors']['currency'];
			if ($params['type'] == $credit) {
				message('支付成功！', $this->createMobileUrl('myorder'), 'success');
			} else {
				message('支付成功！', '../../app/' . $this->createMobileUrl('myorder'), 'success');
			}
		}
	}

	public function doMobileContactUs() {
		global $_W;

		$item = pdo_fetchall("SELECT * FROM " . tablename('haoman_virtuamall_order') . " WHERE weid = '{$_W['uniacid']}' AND from_user = '{$_W['fans']['from_user']}' order by `id` desc");

//		if (empty($item)) {
//			message('抱歉，您的订单不存或是已经被取消！', $this->createMobileUrl('myorder'), 'error');
//		}

     $time = time();

		foreach($item as &$k){

			$k['goods'] = pdo_fetchall("SELECT * FROM " . tablename('haoman_virtuamall_order_goods') . " WHERE orderid='{$k['tandid']}'");

			$aaa = array();
			 foreach($k['goods'] as &$a){

				$aaa  = pdo_fetch("SELECT cardid FROM " . tablename('haoman_virtuamall_order_goods') . " WHERE id = '{$a['id']}'");

				$newaaa =rtrim($aaa['cardid'],",");

				if($newaaa){
					$a['pw'] = pdo_fetchall("SELECT * FROM " . tablename('haoman_virtuamall_pw') . " WHERE category=:category  and isused =1 and id IN({$newaaa}) order by `id` desc ", array(":category" => $a['category']));
						if($a['pw']){
							$style = 1;
							foreach($a['pw'] as &$n){
								if($n['status']==2&&$n['used_times']!=0){
									$n['daytime'] = round(($n['endtime']-$time)/86400,2);
									$n['daytime'] = $n['daytime']<= 0 ? "已到期":$n['daytime'];
								}
							}
							$k['styles'] = 1;
						}
						else{
							$k['styles'] = 2;
						}
				}

			}
		}


		include $this->template('contactus');
	}


	private function sendText($openid,$txt){
		global $_W;
		$acid=pdo_fetchcolumn("SELECT acid FROM ".tablename('account')." WHERE uniacid=:uniacid ",array(':uniacid'=>$_W['uniacid']));
		$acc = WeAccount::create($acid);
		$data = $acc->sendCustomNotice(array('touser'=>$openid,'msgtype'=>'text','text'=>array('content'=>urlencode($txt))));
		return $data;


	}

	public function doMobileMyOrder() {
		global $_W, $_GPC;
		$this->checkAuth();
		$op = $_GPC['op'];
		if ($op == 'confirm') {
			$orderid = intval($_GPC['orderid']);
			$order = pdo_fetch("SELECT * FROM " . tablename('haoman_virtuamall_order') . " WHERE id = :id AND from_user = :from_user", array(':id' => $orderid, ':from_user' => $_W['fans']['from_user']));

			if (empty($order)) {
				message('抱歉，您的订单不存或是已经被取消！', $this->createMobileUrl('myorder'), 'error');
			}


		$category = pdo_fetchall("SELECT * FROM " . tablename('haoman_virtuamall_order_goods') . " WHERE orderid=:orderid ", array(":orderid" => $order['tandid']));


		$num = '';

			$settings = $this->module['config'];

			foreach($category as $k){

			$num = $k['total'];
			$pw=array();
			$pw = pdo_fetchall("SELECT id FROM " . tablename('haoman_virtuamall_pw') . " WHERE category=:category and status = 1 and isused !=1 and num > 0 and pwsn = :pwsn order by `id` desc limit ".$num."", array(":category" => $k['category'],'pwsn'=>$k['pwid']));

			$goodstoal = pdo_fetch("SELECT title FROM " . tablename('haoman_virtuamall_goods') . " WHERE goodssn=:goodssn order by `id` desc ", array(":goodssn" => $k['pwid']));

			$nums = count($pw);

			if($k['total']>$nums){

				$actions = "亲爱的管理员，商品为".$goodstoal['title']."的卡密用完了，赶紧更新下";

				$this->sendText($settings['password'],$actions);

				message('确认收货失败，请稍等再试！', $this->createMobileUrl('myorder'), 'error');
			}



			$data=array();

			foreach($pw as $v){

				$data['cardid'].=$v['id'].",";

				$res = pdo_update('haoman_virtuamall_pw', array('isused'=>1,'rid'=>$k['id'],'iscqr'=>2), array('id' => $v['id']));
			}

			pdo_update('haoman_virtuamall_order_goods', $data, array('id' => $k['id']));




		}

			pdo_update('haoman_virtuamall_order', array('status' => 3,'statuss' => 3), array('id' => $orderid, 'from_user' => $_W['fans']['from_user']));



			message('确认收货完成！', $this->createMobileUrl('myorder'), 'success');
		} else if ($op == 'detail') {
			$orderid = intval($_GPC['orderid']);
			$item = pdo_fetch("SELECT * FROM " . tablename('haoman_virtuamall_order') . " WHERE weid = '{$_W['uniacid']}' AND from_user = '{$_W['fans']['from_user']}' and id='{$orderid}' limit 1");

				if (empty($item)) {
				message('抱歉，您的订单不存或是已经被取消！', $this->createMobileUrl('myorder'), 'error');
			}

			$goodsid = pdo_fetch("SELECT goodsid,total FROM " . tablename('haoman_virtuamall_order_goods') . " WHERE orderid = '{$item['tandid']}'", array(), 'goodsid');
			$goods = pdo_fetchall("SELECT g.id, g.title, g.thumb, g.unit, g.marketprice, o.total,o.optionid,o.id AS abc,o.category FROM " . tablename('haoman_virtuamall_order_goods') . " o left join " . tablename('haoman_virtuamall_goods') . " g on o.goodsid=g.id " . " WHERE o.orderid='{$item['tandid']}'");

				$aaa = array();
			foreach($goods as &$a){
				$aaa  = pdo_fetch("SELECT cardid FROM " . tablename('haoman_virtuamall_order_goods') . " WHERE id = '{$a['abc']}'");

				$newaaa =rtrim($aaa['cardid'],",");

				if($newaaa){
					$a['pw'] = pdo_fetchall("SELECT * FROM " . tablename('haoman_virtuamall_pw') . " WHERE category=:category  and isused =1 and id IN({$newaaa}) order by `id` desc ", array(":category" => $a['category']));

				}

			}
			foreach ($goods as &$g) {
				//属性
				$option = pdo_fetch("select title,marketprice,weight,stock from " . tablename("haoman_virtuamall_goods_option") . " where id=:id limit 1", array(":id" => $g['optionid']));
				if ($option) {
					$g['title'] = "[" . $option['title'] . "]" . $g['title'];
					$g['marketprice'] = $option['marketprice'];
				}
			}


			unset($g);
			$dispatch = pdo_fetch("select id,dispatchname from " . tablename('haoman_virtuamall_dispatch') . " where id=:id limit 1", array(":id" => $item['dispatch']));
			include $this->template('order_detail');
		} else {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$status = intval($_GPC['status']);
			$where = " weid = '{$_W['uniacid']}' AND from_user = '{$_W['fans']['from_user']}'";
			if ($status == 2) {
				$where.=" and ( status=1 or status=2 )";
			} else {
				$where.=" and status=$status";
			}
			$list = pdo_fetchall("SELECT * FROM " . tablename('haoman_virtuamall_order') . " WHERE $where ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(), 'id');

			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('haoman_virtuamall_order') . " WHERE weid = '{$_W['uniacid']}' AND from_user = '{$_W['fans']['from_user']}'");
			$pager = pagination($total, $pindex, $psize);
			if (!empty($list)) {
				foreach ($list as &$row) {
					$goodsid = pdo_fetchall("SELECT goodsid,total FROM " . tablename('haoman_virtuamall_order_goods') . " WHERE orderid = '{$row['tandid']}'", array(), 'goodsid');
					$goods = pdo_fetchall("SELECT g.id, g.title, g.thumb, g.unit, g.marketprice,o.total,o.optionid FROM " . tablename('haoman_virtuamall_order_goods') . " o left join " . tablename('haoman_virtuamall_goods') . " g on o.goodsid=g.id " . " WHERE o.orderid='{$row['tandid']}'");
					foreach ($goods as &$item) {
						//属性
						$option = pdo_fetch("select title,marketprice,weight,stock from " . tablename("haoman_virtuamall_goods_option") . " where id=:id limit 1", array(":id" => $item['optionid']));
						if ($option) {
							$item['title'] = "[" . $option['title'] . "]" . $item['title'];
							$item['marketprice'] = $option['marketprice'];
						}
					}
					unset($item);
					$row['goods'] = $goods;
					$row['total'] = $goodsid;
					$row['dispatch'] = pdo_fetch("select id,dispatchname from " . tablename('haoman_virtuamall_dispatch') . " where id=:id limit 1", array(":id" => $row['dispatch']));
				}
			}

			include $this->template('order');
		}
	}



	public function doMobileDetail() {
		global $_W, $_GPC;
		$goodsid = intval($_GPC['id']);
		$goods = pdo_fetch("SELECT * FROM " . tablename('haoman_virtuamall_goods') . " WHERE id = :id", array(':id' => $goodsid));
		if (empty($goods)) {
			message('抱歉，商品不存在或是已经被删除！');
		}
		if ($goods['istime'] == 1) {
			$backUrl = $this->createMobileUrl('list');
			$backUrl = $_W['siteroot'] . 'app' . ltrim($backUrl, '.');
			if (time() < $goods['timestart']) {
				message('抱歉，还未到购买时间, 暂时无法购物哦~', $backUrl, "error");
			}
			if (time() > $goods['timeend']) {
				message('抱歉，商品限购时间已到，不能购买了哦~', $backUrl, "error");
			}
		}
		$title = $goods['title'];
		//浏览量
		pdo_query("update " . tablename('haoman_virtuamall_goods') . " set viewcount=viewcount+1 where id=:id and weid='{$_W['uniacid']}' ", array(":id" => $goodsid));
		$piclist1 = array(array("attachment" => $goods['thumb']));
		$piclist = array();
		if (is_array($piclist1)) {
			foreach($piclist1 as $p){
				$piclist[] = is_array($p)?$p['attachment']:$p;
			}
		}
		if ($goods['thumb_url'] != 'N;') {
			$urls = unserialize($goods['thumb_url']);
			if (is_array($urls)) {
				foreach($urls as $p){
					$piclist[] = is_array($p)?$p['attachment']:$p;
				}
			}
		}
		$marketprice = $goods['marketprice'];
		$productprice= $goods['productprice'];
		$originalprice = $goods['originalprice'];
		$stock = $goods['total'];
		//规格及规格项
		$allspecs = pdo_fetchall("select * from " . tablename('haoman_virtuamall_spec') . " where goodsid=:id order by displayorder asc", array(':id' => $goodsid));
		foreach ($allspecs as &$s) {
			$s['items'] = pdo_fetchall("select * from " . tablename('haoman_virtuamall_spec_item') . " where  `show`=1 and specid=:specid order by displayorder asc", array(":specid" => $s['id']));
		}
		unset($s);
		//处理规格项
		$options = pdo_fetchall("select id,title,thumb,marketprice,productprice,costprice, stock,weight,specs from " . tablename('haoman_virtuamall_goods_option') . " where goodsid=:id order by id asc", array(':id' => $goodsid));
		//排序好的specs
		$specs = array();
		//找出数据库存储的排列顺序
		if (count($options) > 0) {
			$specitemids = explode("_", $options[0]['specs'] );
			foreach($specitemids as $itemid){
				foreach($allspecs as $ss){
					$items = $ss['items'];
					foreach($items as $it){
						if($it['id']==$itemid){
							$specs[] = $ss;
							break;
						}
					}
				}
			}
		}
		$params = pdo_fetchall("SELECT * FROM " . tablename('haoman_virtuamall_goods_param') . " WHERE goodsid=:goodsid order by displayorder asc", array(":goodsid" => $goods['id']));
		$carttotal = $this->getCartTotal();

        $settings = $this->module['config'];

        $jssdk = new JSSDK();
        $package = $jssdk->GetSignPackage();
		include $this->template('detail');
	}

	public function doMobileAddress() {
		global $_W, $_GPC;
		$this->checkAuth();
		$operation = $_GPC['op'];
		$item = pdo_fetchall("SELECT * FROM " . tablename('haoman_virtuamall_goods') . " WHERE weid = :weid", array(':weid' => $_W['uniacid']));

		foreach($item as $k){
			if($k['type']==1){
				$style = 1;
			}

			break;
		}

		if ($operation == 'post') {
			$id = intval($_GPC['id']);
			$data = array(
				'uniacid' => $_W['uniacid'],
				'uid' => $_W['fans']['uid'],
				'username' => $_GPC['realname'],
				'mobile' => $_GPC['mobile'],
				'province' => $_GPC['province'],
				'city' => $_GPC['city'],
				'district' => $_GPC['area'],
				'address' => $_GPC['address'],
			);
			if (empty($_GPC['realname']) || empty($_GPC['mobile'])) {
				message('请输完善您的资料！');
			}
			if (!empty($id)) {
				unset($data['uniacid']);
				unset($data['uid']);
				pdo_update('mc_member_address', $data, array('id' => $id));
				message($id, '', 'ajax');
			} else {
				pdo_update('mc_member_address', array('isdefault' => 0), array('uniacid' => $_W['uniacid'], 'uid' => $_W['fans']['uid']));
				$data['isdefault'] = 1;
				pdo_insert('mc_member_address', $data);
				$id = pdo_insertid();
				if (!empty($id)) {
					message($id, '', 'ajax');
				} else {
					message(0, '', 'ajax');
				}
			}
		} elseif ($operation == 'default') {
			$id = intval($_GPC['id']);
			$sql = 'SELECT `isdefault` FROM ' . tablename('mc_member_address') . ' WHERE `id` = :id AND `uniacid` = :uniacid
					 AND `uid` = :uid';
			$params = array(':id' => $id, ':uniacid' => $_W['uniacid'], ':uid' => $_W['fans']['uid']);
			$address = pdo_fetch($sql, $params);

			if (!empty($address) && empty($address['isdefault'])) {
				pdo_update('mc_member_address', array('isdefault' => 0), array('uniacid' => $_W['uniacid'], 'uid' => $_W['fans']['uid']));
				pdo_update('mc_member_address', array('isdefault' => 1), array('uniacid' => $_W['uniacid'], 'uid' => $_W['fans']['uid'], 'id' => $id));
			}
			message(1, '', 'ajax');
		} elseif ($operation == 'detail') {
			$id = intval($_GPC['id']);
			$sql = 'SELECT * FROM ' . tablename('mc_member_address') . ' WHERE `id` = :id';
			$row = pdo_fetch($sql, array(':id' => $id));
			message($row, '', 'ajax');
		} elseif ($operation == 'remove') {
			$id = intval($_GPC['id']);
			if (!empty($id)) {
				$where = ' AND `uniacid` = :uniacid AND `uid` = :uid';
				$sql = 'SELECT `isdefault` FROM ' . tablename('mc_member_address') . ' WHERE `id` = :id' . $where;
				$params = array(':id' => $id, ':uniacid' => $_W['uniacid'], ':uid' => $_W['fans']['uid']);
				$address = pdo_fetch($sql, $params);

				if (!empty($address)) {
					pdo_delete('mc_member_address', array('id' => $id));
					// 如果删除的是默认地址，则设置是新的为默认地址
					if ($address['isdefault'] > 0) {
						$sql = 'SELECT MAX(id) FROM ' . tablename('mc_member_address') . ' WHERE 1 ' . $where;
						unset($params[':id']);
						$maxId = pdo_fetchcolumn($sql, $params);
						if (!empty($maxId)) {
							pdo_update('mc_member_address', array('isdefault' => 1), array('id' => $maxId));
							die(json_encode(array("result" => 1, "maxid" => $maxId)));
						}
					}
				}
			}
			die(json_encode(array("result" => 1, "maxid" => 0)));
		} else {
			$sql = 'SELECT * FROM ' . tablename('mc_member_address') . ' WHERE `uniacid` = :uniacid AND `uid` = :uid';
			$params = array(':uniacid' => $_W['uniacid']);
			if (empty($_W['member']['uid'])) {
				$params[':uid'] = $_W['fans']['openid'];
			} else {
				$params[':uid'] = $_W['member']['uid'];
			}
			$addresses = pdo_fetchall($sql, $params);
			$carttotal = $this->getCartTotal();
			include $this->template('address');
		}
	}

	private function checkAuth() {
		global $_W;
		checkauth();
	}
	private function changeWechatSend($id, $status, $msg = '') {
		global $_W;
		$paylog = pdo_fetch("SELECT plid, openid, tag FROM " . tablename('core_paylog') . " WHERE tid = '{$id}' AND status = 1 AND type = 'wechat'");
		if (!empty($paylog['openid'])) {
			$paylog['tag'] = iunserializer($paylog['tag']);
			$acid = $paylog['tag']['acid'];
			$account = account_fetch($acid);
			$payment = uni_setting($account['uniacid'], 'payment');
			if ($payment['payment']['wechat']['version'] == '2') {
				return true;
			}
			$send = array(
					'appid' => $account['key'],
					'openid' => $paylog['openid'],
					'transid' => $paylog['tag']['transaction_id'],
					'out_trade_no' => $paylog['plid'],
					'deliver_timestamp' => TIMESTAMP,
					'deliver_status' => $status,
					'deliver_msg' => $msg,
			);
			$sign = $send;
			$sign['appkey'] = $payment['payment']['wechat']['signkey'];
			ksort($sign);
			$string = '';
			foreach ($sign as $key => $v) {
				$key = strtolower($key);
				$string .= "{$key}={$v}&";
			}
			$send['app_signature'] = sha1(rtrim($string, '&'));
			$send['sign_method'] = 'sha1';
			$account = WeAccount::create($acid);
			$response = $account->changeOrderStatus($send);
			if (is_error($response)) {
				message($response['message']);
			}
		}
	}



	public function send_template_message($data)

	{
//模版消息
		global $_W, $_GPC;

		$atype = 'weixin';

		$account_code = "account_weixin_code";

		load()->classs('weixin.account');

		$access_token = WeAccount::token();

		$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $access_token;

		$response = ihttp_request($url, $data);

		if (is_error($response)) {

			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");

		}

		$result = @json_decode($response['content'], true);

		if (empty($result)) {

			return error(-1, "接口调用失败, 元数据: {$response['meta']}");

		} elseif (!empty($result['errcode'])) {

			return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},信息详情：");

		}

		return true;

	}

	public function doWebOption() {
		$tag = random(32);
		global $_GPC;
		include $this->template('option');
	}

	public function doWebSpec() {
		global $_GPC;
		$spec = array(
			"id" => random(32),
			"title" => $_GPC['title']
		);
		include $this->template('spec');
	}

	public function doWebSpecItem() {
		global $_GPC;
		load()->func('tpl');
		$spec = array(
			"id" => $_GPC['specid']
		);
		$specitem = array(
			"id" => random(32),
			"title" => $_GPC['title'],
			"show" => 1
		);
		include $this->template('spec_item');
	}

	public function doWebParam() {
		$tag = random(32);
		global $_GPC;
		include $this->template('param');
	}


	public function doWebAdv() {
		global $_W, $_GPC;
			load()->func('tpl');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$list = pdo_fetchall("SELECT * FROM " . tablename('haoman_virtuamall_adv') . " WHERE weid = '{$_W['uniacid']}' ORDER BY displayorder DESC");
		} elseif ($operation == 'post') {
			$id = intval($_GPC['id']);
			if (checksubmit('submit')) {
				$data = array(
					'weid' => $_W['uniacid'],
					'advname' => $_GPC['advname'],
					'link' => $_GPC['link'],
					'enabled' => intval($_GPC['enabled']),
					'displayorder' => intval($_GPC['displayorder']),
					'thumb'=>$_GPC['thumb']
				);
				if (!empty($id)) {
					pdo_update('haoman_virtuamall_adv', $data, array('id' => $id));
				} else {
					pdo_insert('haoman_virtuamall_adv', $data);
					$id = pdo_insertid();
				}
				message('更新幻灯片成功！', $this->createWebUrl('adv', array('op' => 'display')), 'success');
			}
			$adv = pdo_fetch("select * from " . tablename('haoman_virtuamall_adv') . " where id=:id and weid=:weid limit 1", array(":id" => $id, ":weid" => $_W['uniacid']));
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$adv = pdo_fetch("SELECT id FROM " . tablename('haoman_virtuamall_adv') . " WHERE id = '$id' AND weid=" . $_W['uniacid'] . "");
			if (empty($adv)) {
				message('抱歉，幻灯片不存在或是已经被删除！', $this->createWebUrl('adv', array('op' => 'display')), 'error');
			}
			pdo_delete('haoman_virtuamall_adv', array('id' => $id));
			message('幻灯片删除成功！', $this->createWebUrl('adv', array('op' => 'display')), 'success');
		} else {
			message('请求方式不存在');
		}
		include $this->template('adv', TEMPLATE_INCLUDEPATH, true);
	}

//	public function doMobileAjaxdelete() {
//		global $_GPC;
//		$delurl = $_GPC['pic'];
//		if (file_delete($delurl)) {
//			echo 1;
//		} else {
//			echo 0;
//		}
//	}

	public function doMobileOrder() {
		global $_W, $_GPC;

		$orderId = intval($_GPC['orderid']);
		$status = intval($_GPC['status']);
		$tandid = $_GPC['tandid'];
		$referStatus = intval($_GPC['curtstatus']);
		$sql = 'SELECT `id` FROM ' . tablename('haoman_virtuamall_order') . ' WHERE `id` = :id AND `weid` = :weid AND `from_user`= :from_user';
		$params = array(':id' => $orderId, ':weid' => $_W['uniacid'], ':from_user' => $_W['fans']['from_user']);
		$orderId = pdo_fetchcolumn($sql, $params);
		$redirect = $this->createMobileUrl('myorder', array('status' => $referStatus));
		if (empty($orderId)) {
			message('订单不存在或已经被删除', $redirect , 'error');
		}

		if ($_GPC['op'] == 'delete') {
			pdo_delete('haoman_virtuamall_order', array('id' => $orderId));
			pdo_delete('haoman_virtuamall_order_goods', array('orderid' => $tandid));
			message('订单已经成功删除！', $redirect, 'success');
		} else {
			pdo_update('haoman_virtuamall_order', array('status' => $status), array('id' => $orderId));
			message('订单已经成功取消！', $redirect, 'success');
		}
	}

	public function doWebDeletecard() {
		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		$rule = pdo_fetch("select id from " . tablename('haoman_virtuamall_addcard') . " where id = :id ", array(':id' => $id));
		if (empty($rule)) {
			message('抱歉，参数错误！');
		}else{
			pdo_delete('haoman_virtuamall_addcard', array('id' => $id));

			message('卡券删除成功！', referer(), 'success');
		}

	}

	public function doWebDeletehexiao() {
		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		$rule = pdo_fetch("select id from " . tablename('haoman_virtuamall_hexiaopeople') . " where id = :id ", array(':id' => $id));
		if (empty($rule)) {
			message('抱歉，参数错误！');
		}else{
			pdo_delete('haoman_virtuamall_hexiaopeople', array('id' => $id));

			message('核销员删除成功！', referer(), 'success');
		}

	}

	public function doWebDeletehexiao_jilu() {
		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		$rule = pdo_fetch("select id from " . tablename('haoman_virtuamall_hexiaoaward') . " where id = :id ", array(':id' => $id));
		if (empty($rule)) {
			message('抱歉，参数错误！');
		}else{
			pdo_delete('haoman_virtuamall_hexiaoaward', array('id' => $id));

			message('核销记录删除成功！', referer(), 'success');
		}

	}

	private function httpGet($url) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 500);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_URL, $url);

		$res = curl_exec($curl);
		curl_close($curl);

		return $res;
	}
	//获取api_ticket
	public function getCardTicket($openid,$type){
		global $_W,$_GPC;
		if($type==1){
			$card = pdo_fetchall("select cardid from " . tablename('haoman_ds_addcard') . " where  uniacid = :uniacid ", array(':uniacid' =>$_W['uniacid']));
			$card_id = $card[array_rand($card)][cardid];

		}

		if($type==2){
			$card_idarr = pdo_fetchall("select id,couponid,awardspro from " . tablename('haoman_ds_prize') . " where  uniacid = :uniacid and awardspro > 0 and awardstotal-prizedraw>0 and couponid <> '' ORDER BY Rand() ASC",array(':uniacid' =>$_W['uniacid']));

			$card_rowid=-1;
			if($card_idarr) {
				$card_temparr = array();
				foreach ($card_idarr as $index => $row) {
					$item = array(
						'id' => $row['id'],
						'couponid' => $row['couponid'],
						'v' => $row['awardspro'],
					);
					$card_temparr[] = $item;

				}

				foreach ($card_temparr as $key => $val) {
					$randarr[$val['id']] = $val['v'];
				}

				$card_rowid = $this->Get_rand($randarr); //根据概率获取奖项id
				$card_new = pdo_fetch("select * from " . tablename('haoman_ds_prize') . " where  id=" . $card_rowid . "");
				$card_id = $card_new['couponid'];
			}
		}

		//获取access_token
		$data = pdo_fetch( " SELECT * FROM ".tablename('haoman_ds_cardticket')." WHERE weid='".$_W['uniacid']."' " );
		$appid = $_W['account']['key'];
		$appSecret = $_W['account']['secret'];
		load()->func('communication');
		//检测ticket是否过期
		if ($data['createtime'] < time()) {
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appSecret."";
			$res = json_decode($this->httpGet($url));
			$tokens = $res->access_token;
			if(empty($tokens))
			{
				return;
			}

			$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".$tokens."&type=wx_card";
			$res = json_decode($this->httpGet($url));
			$now = TIMESTAMP;
			$now = intval($now) + 7200;
			$ticket = $res->ticket;
			$insert = array(
				'weid' => $_W['uniacid'],
				'createtime' => $now,
				'ticket' => $ticket,
			);
			if(empty($data)){
				pdo_insert('haoman_ds_cardticket',$insert);
			}else{
				pdo_update('haoman_ds_cardticket',$insert,array('id'=>$data['id']));
			}

		}else{
			$ticket = $data['ticket'];
		}

		// 注意 URL 一定要动态获取，不能 hardcode.
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

		//获得ticket后将参数拼成字符串进行sha1加密
		$now = time();
		$timestamp = $now;



		//随机字符串
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$str = "";
		for ($i = 0; $i < 16; $i++) {
			$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}
		//随机字符串



		$nonceStr = $str;
		$card_id = $card_id;
		$openid = $openid;
		$string = "card_id=$card_id&jsapi_ticket=$ticket&noncestr=$nonceStr$openid=$openid&timestamp=$timestamp";

		$arr = array($card_id,$ticket,$nonceStr,$openid,$timestamp);//组装参数
		asort($arr, SORT_STRING);
		$sortString = "";
		foreach($arr as $temp){
			$sortString = $sortString.$temp;
		}
		$signature = sha1($sortString);
		$cardArry = array(
			'code' =>"",
			'openid' => $openid,
			'timestamp' => $now,
			'signature' => $signature,
			'cardId' => $card_id,
			'ticket' => $ticket,
			'nonceStr' => $nonceStr,
			'card_rowid' => $card_rowid,
		);
		return $cardArry;


	}

	public function doWebCardmanage() { //微信卡券管理
		global $_GPC, $_W;
		$uniacid = $_W['uniacid'];
		load()->model('reply');

		$settings = $this->module['config'];

		$addcard = pdo_fetchall("select * from " . tablename('haoman_virtuamall_addcard') . " where uniacid = :uniacid order by id desc", array(":uniacid" => $uniacid));

		$now = time();
		$addcard1 = array(
			"getstarttime" => $now,
			"getendtime" => strtotime(date("Y-m-d H:i", $now + 7 * 24 * 3600)),
		);

		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$sql = "uniacid = :uniacid and `module` = :module";
		$params = array();
		$params[':uniacid'] = $_W['uniacid'];
		$params[':module'] = 'haoman_virtuamall';

		$list = reply_search($sql, $params);
		$pager = pagination($total, $pindex, $psize);

		include $this->template('cardmanage');
	}

	public function doWebnewcard() { //添加、修改微信卡券
		global $_GPC, $_W;
// $rid = intval($_GPC['rid']);
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		$uniacid = $_W['uniacid'];
		load()->model('reply');
		load()->func('tpl');
		$sql = "uniacid = :uniacid and `module` = :module";
		$params = array();
		$params[':uniacid'] = $_W['uniacid'];
		$params[':module'] = 'haoman_virtuamall';

//$rowlist = reply_search($sql, $params);


// message($rid);

		if($operation == 'updatacard'){

			$id = $_GPC['listid'];


			$updata = array(
				'buynum' => $_GPC['buynum'],
				'uniacid' => $_W['uniacid'],
				'cardname' => $_GPC['cardname'],
				'cardprize' =>  $_GPC['cardprize'],
				'cardid' => $_GPC['cardid'],
				'cardnum' => $_GPC['cardnum'],
				'createtime' =>time(),
				'isstartusing' => $_GPC['isstartusing'],
				'most_num_times' => $_GPC['most_num_times'],
				'getstarttime' => strtotime($_GPC['datelimit']['start']),
				'getendtime' => strtotime($_GPC['datelimit']['end']),
			);


			$temp =  pdo_update('haoman_virtuamall_addcard',$updata,array('id'=>$id));

			message("更新卡券成功",$this->createWebUrl('cardmanage'),"success");


		}elseif($operation == 'addcard'){





			$updata = array(
				'buynum' => $_GPC['buynum'],
				'uniacid' => $_W['uniacid'],
				'cardprize' =>  $_GPC['cardprize'],
				'cardname' => $_GPC['cardname'],
				'cardid' => $_GPC['cardid'],
				'cardnum' => $_GPC['cardnum'],
				'createtime' =>time(),
				'isstartusing' => $_GPC['isstartusing'],
				'most_num_times' => $_GPC['most_num_times'],
				'getstarttime' => strtotime($_GPC['datelimit']['start']),
				'getendtime' => strtotime($_GPC['datelimit']['end']),
			);


			// message($keywords['name']);

			$temp = pdo_insert('haoman_virtuamall_addcard', $updata);

			message("添加卡券成功",$this->createWebUrl('cardmanage'),"success");

		}elseif($operation == 'up'){

			$uid = intval($_GPC['uid']);
			$list = pdo_fetch("select * from " . tablename('haoman_virtuamall_addcard') . "  where id=:uid ", array(':uid' => $uid));

			include $this->template('updatacard');

		}else{

			$now = time();
			$addcard1 = array(
				"getstarttime" => $now,
				"getendtime" => strtotime(date("Y-m-d H:i", $now + 7 * 24 * 3600)),
			);

			include $this->template('newcard');

		}


	}

	public function doWebVerification() { //核销员管理
		global $_GPC, $_W;
		$uniacid = $_W['uniacid'];
		load()->model('reply');

		$settings = $this->module['config'];

		$addcard = pdo_fetchall("select * from " . tablename('haoman_virtuamall_hexiaopeople') . " where weid = :weid order by id desc", array(":weid" => $uniacid));

		foreach($addcard as &$k){
			$k['category'] = pdo_fetchcolumn('SELECT `name` FROM ' . tablename('haoman_virtuamall_category') . ' WHERE `weid` = :weid and id = :id',array(":weid" => $uniacid,':id'=>$k['category']));
		}
		unset($k);

		$now = time();
		$addcard1 = array(
			"getstarttime" => $now,
			"getendtime" => strtotime(date("Y-m-d H:i", $now + 7 * 24 * 3600)),
		);

		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$sql = "uniacid = :uniacid and `module` = :module";
		$params = array();
		$params[':uniacid'] = $_W['uniacid'];
		$params[':module'] = 'haoman_virtuamall';

		$list = reply_search($sql, $params);

		$pager = pagination($total, $pindex, $psize);

		include $this->template('verification');
	}

	public function doWebUserinfo() {
		global $_GPC, $_W;
		if ($_W['isajax']) {
			$fansid = intval($_GPC['fansid']);
			$uniacid = $_W['uniacid'];

			$fans = pdo_fetch("SELECT * FROM " . tablename('haoman_virtuamall_hexiaopeople') . " WHERE weid = :weid AND id = :id", array(':weid' => $uniacid, ':id' => $fansid));

			$dsdata = pdo_fetchall("SELECT * FROM " . tablename('haoman_virtuamall_hexiaoaward') . " WHERE weid = :weid AND openid = :openid ORDER BY `id` LIMIT 50", array(':weid' => $uniacid, ':openid' => $fans['openid']));


			foreach($dsdata as &$k){
				$k['category'] = pdo_fetchcolumn('SELECT `name` FROM ' . tablename('haoman_virtuamall_category') . ' WHERE `weid` = :weid and id = :id',array(":weid" => $uniacid,':id'=>$k['category']));

                $k['name']	= pdo_fetchcolumn('SELECT `realname` FROM ' . tablename('haoman_virtuamall_order') . ' WHERE `weid` = :weid and tandid = :tandid',array(":weid" => $uniacid,':tandid'=>$k['orderid']));
                $k['buy_mobile']	= pdo_fetchcolumn('SELECT `mobile` FROM ' . tablename('haoman_virtuamall_order') . ' WHERE `weid` = :weid and tandid = :tandid',array(":weid" => $uniacid,':tandid'=>$k['orderid']));

            }
			unset($k);

			include $this->template('userinfo');
			exit();
		}
	}

	public function doWebnewhexiao() { //添加、修改核销员
		global $_GPC, $_W;

		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		$uniacid = $_W['uniacid'];
		load()->model('reply');
		load()->func('tpl');
		$sql = "uniacid = :uniacid and `module` = :module";
		$params = array();
		$params[':uniacid'] = $_W['uniacid'];
		$params[':module'] = 'haoman_virtuamall';

		$sqls = 'SELECT * FROM ' . tablename('haoman_virtuamall_category') . ' WHERE `weid` = :weid ORDER BY `parentid`, `displayorder` DESC';

		$category = pdo_fetchall($sqls, array(':weid' => $_W['uniacid']), 'id');

		if($operation == 'updatacard'){

			$id = $_GPC['listid'];


			$updata = array(
				'weid' => $_W['uniacid'],
				'openid' =>  $_GPC['openid'],
				'realname' => $_GPC['realname'],
				'avatar' => $avatar = $_W['fans']['tag']['avatar'],
				'mobile' => $_GPC['mobile'],
				'category' => $_GPC['category'],
				'status' => $_GPC['status'],
				'starttime' => strtotime($_GPC['datelimit']['start']),
				'endtime' => strtotime($_GPC['datelimit']['end']),
			);


			$temp =  pdo_update('haoman_virtuamall_hexiaopeople',$updata,array('id'=>$id));

			message("更新核销员成功",$this->createWebUrl('verification'),"success");


		}elseif($operation == 'addhexiao'){

			$updata = array(
				'weid' => $_W['uniacid'],
				'openid' =>  $_GPC['openid'],
				'realname' => $_GPC['realname'],
				'mobile' => $_GPC['mobile'],
				'category' => $_GPC['category'],
				'status' => $_GPC['status'],
				'starttime' => strtotime($_GPC['datelimit']['start']),
				'endtime' => strtotime($_GPC['datelimit']['end']),
			);


			// message($keywords['name']);

			$temp = pdo_insert('haoman_virtuamall_hexiaopeople', $updata);

			message("添加核销员成功",$this->createWebUrl('verification'),"success");

		}elseif($operation == 'up'){

			$uid = intval($_GPC['uid']);

			$list = pdo_fetch("select * from " . tablename('haoman_virtuamall_hexiaopeople') . "  where id=:uid ", array(':uid' => $uid));

			$categorys = pdo_fetch('SELECT `name` FROM ' . tablename('haoman_virtuamall_category') . ' WHERE `weid` = :weid and id = :id',array(":weid" => $_W['uniacid'],':id'=>$list['category']));

			include $this->template('updatahexiao');

		}else{

			$now = time();
			$addcard1 = array(
				"getstarttime" => $now,
				"getendtime" => strtotime(date("Y-m-d H:i", $now + 7 * 24 * 3600)),
			);

			include $this->template('newhexiao');

		}


	}

	public function doMobilegetShareImgUrl() {

		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		$num = intval($_GPC['num']);
		$djtitle = $_GPC['djtitle'];

		$imgName = "haomanvirtuamall".$_W['uniacid'].$id;
		$linkUrl = $_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&m=haoman_virtuamall&do=hexiao&id=".$id;
		$imgUrl = "../addons/haoman_virtuamall/qrcode/".$imgName.".png";

		load()->func('file');
		mkdirs(ROOT_PATH . '/qrcode');
		$dir = $imgUrl;
		$flag = file_exists($dir);

		if($flag == false){
			//生成二维码图片
			$errorCorrectionLevel = "L";
			$matrixPointSize = "4";
			QRcode::png($linkUrl,$imgUrl,$errorCorrectionLevel,$matrixPointSize);
			//生成二维码图片
		}

		$data = array(
			'success' => 1,
			'msg' => $imgUrl,
			'djtitle' => $djtitle,
		);

		echo json_encode($data);
	}

	public function doMobileHexiao()
	{
		global $_GPC, $_W;


		$uniacid = $_W['uniacid'];
		$display = empty($_GPC['act']) ? 'display' : $_GPC['act'];

		$awardid = intval($_GPC['id']);

		$from_user = $_W['fans']['from_user'];

		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		if (strpos($user_agent, 'MicroMessenger') === false) {
			message('本页面仅支持微信访问!非微信浏览器禁止浏览!', '', 'error');
		}


		if($display == "display"){
			$award = pdo_fetch("select * from " . tablename('haoman_virtuamall_pw') . " where uniacid = :uniacid and id = :id and status = 1 and ishexiao = 1",array(':uniacid'=>$uniacid,':id'=>$awardid));
			if($award == false){
				message('卡密不存在或者已经核销！',$this->createMobileUrl('hexiao'),'error');
			}
			$replys = pdo_fetch("select * from " . tablename('haoman_virtuamall_hexiaopeople') . " where weid = :weid AND openid = :from_user and status =1 AND category IN(0,{$award['category']})", array(':weid' => $uniacid,':from_user' => $from_user));

			if($replys == false){
				message('您没有兑奖员权限,请先后台绑定下兑奖员OpenID','','error');
			}

			if($replys['starttime']>time()||$replys['endtime']<time()){
				message('请确定您的核销时间段','','error');
			}

			include $this->template('hexiao');
		}else{

			$award = pdo_fetch("select * from " . tablename('haoman_virtuamall_pw') . " where uniacid = :uniacid and id = :id and status = 1 and ishexiao = 1 and num >0",array(':uniacid'=>$uniacid,':id'=>$awardid));

           $ordergoodid = pdo_fetch("select orderid from " . tablename('haoman_virtuamall_order_goods') . " where weid = :weid and find_in_set('$awardid',cardid) ",array(':weid'=>$uniacid));

			$replys = pdo_fetch("select * from " . tablename('haoman_virtuamall_hexiaopeople') . " where weid = :weid AND openid = :from_user and status = 1 AND category IN(0,{$award['category']})", array(':weid' => $uniacid,':from_user' => $from_user));

			if($replys == false){

				message('请确定您的权限','','error');
			}
			if($award == false){

				message('不存在此奖品！',$this->createMobileUrl('hexiao'),'error');
			}

		//	if(intval($_GPC['rid']) == intval($award['rid'])){
				$temp = pdo_update('haoman_virtuamall_pw', array('status' => 2,'num'=>0,'activation_time'=>time(),'endtime'=>time()+$award['used_times']), array('id' => $award['id']));
//			}else{
//				message('您没有兑奖权限,请确认该奖品为此活动规则的奖品！',$this->createMobileUrl('hexiao'),'error');
//			}
			$updata = array(
				'weid'=>$_W['uniacid'],
				'openid' => $from_user,
				'category'=>$replys['category'],
				'realname' => $replys['realname'],
				'mobile' => $replys['mobile'],
				'starttime' => $replys['starttime'],
				'endtime' => $replys['endtime'],
				'pwid' => $award['pwid'],
				'pwsn' => $award['pwsn'],
				'orderid' => $ordergoodid['orderid'],
				'title' => $award['title'],
				'used_cardid' => $award['used_cardid'],
				'status' => 1,
				'createtime' =>time(),
			);

			pdo_insert('haoman_virtuamall_hexiaoaward', $updata);

			if ($temp === false) {
				message('卡密核销不成功，请联系商家',$this->createMobileUrl('hexiao'),'error');
			} else {
				message('恭喜，已经成功核销卡密！',$this->createMobileUrl('hexiao'),'success');
			}
		}


	}
}
