<?php
/**
 * 小智-微直播（传播版）模块定义
 *
 * @author wxz
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
require_once '../framework/library/qrcode/phpqrcode.php';

class Wxz_wzbModule extends WeModule {
	public function fieldsFormDisplay($rid = 0) {
		
		global $_W;
        load()->func('tpl');
        if (!empty($rid)) {
            $live_setting = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_live_setting') . ' WHERE `uniacid` = :uniacid and `rid` = :rid', array(':uniacid' => $_W['uniacid'],':rid' => $rid));
			$setting = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_setting') . ' WHERE `uniacid` = :uniacid and `rid` = :rid', array(':uniacid' => $_W['uniacid'],':rid' => $rid));
		}

		$imgName = "wxz_wzb".$_W['uniacid'].$rid;
		
	   	$linkUrl = $_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&m=wxz_wzb&do=index2&rid=".$rid;
		$imgUrl = "../addons/wxz_wzb/qrcode/".$imgName.".png";
        load()->func('file');
        mkdirs(MODULE_ROOT . '/qrcode');
		$dir = $imgUrl;
        $flag = file_exists($dir);
		if($flag == false){
		   	//生成二维码图片
	        $errorCorrectionLevel = "L";
	        $matrixPointSize = "4";
	        QRcode::png($linkUrl,$imgUrl,$errorCorrectionLevel,$matrixPointSize);
		    //生成二维码图片
    	}
		
        include $this->template('form');
	}


	public function fieldsFormValidate($rid = 0) {
		
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		return '';
	}

	public function fieldsFormSubmit($rid) {
		
		global $_GPC, $_W;
		$aid = intval($_GPC['aid']);
		$lid = intval($_GPC['lid']);
		if($_GPC['livesetting']['title']==''){
			message('直播间标题不能为空',referer(),'error');
		}
		$data=array(
			'uniacid'=>$_W['uniacid'],
			'logo'=>$_GPC['setting']['logo'],
			'title'=>$_GPC['setting']['title'],
			'sub_title'=>$_GPC['setting']['sub_title'],
			'yc'=>$_GPC['setting']['yc'],
			'yc_url'=>$_GPC['setting']['yc_url'],
			'gz_must'=>$_GPC['setting']['gz_must'],
			'no_avatar'=>$_GPC['setting']['no_avatar'],
			'attention_code'=>$_GPC['setting']['attention_code'],
			'attention_url'=>$_GPC['setting']['attention_url']
		);

		$liveset=array(
			'uniacid'=>$_W['uniacid'],
			'title'=>$_GPC['livesetting']['title'],
			'dateline'=>time()
		);
		
		if($aid){
			pdo_update('wxz_wzb_setting', $data, array('rid' => $rid));
		}else{
			$data['rid']=$rid;
			pdo_insert('wxz_wzb_setting', $data);
		}

		if($lid){
			pdo_update('wxz_wzb_live_setting', $liveset, array('rid' => $rid));
		}else{
			$liveset['rid']=$rid;
			pdo_insert('wxz_wzb_live_setting', $liveset);
		}
	}

	public function ruleDeleted($rid) {
		
		pdo_delete('wxz_wzb_live_setting', array('rid' => $rid));
		pdo_delete('wxz_wzb_live_setting', array('rid' => $rid));
	}
}