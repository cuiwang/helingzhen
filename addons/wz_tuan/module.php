<?php
/**
 * 新微团购模块定义
 *
 * @author 微赞
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
define('MB_ROOT', IA_ROOT . '/addons/wz_tuan');
class wz_tuanModule extends WeModule {
	public function settingsDisplay($settings) {
		global $_W, $_GPC, $frames;
		require_once MB_ROOT . '/source/backlist.class.php';
        $backlist = new backlist();
		$frames = $backlist->getModuleFrames('wz_tuan');
		$backlist->_calc_current_frames2($frames);
		load()->func('tpl');
		load()->model('account');
		$modules = uni_modules();
		if(checksubmit()) {
			load()->func('file');
            $r = mkdirs(MB_ROOT . '/cert/'.$_W['uniacid']);
			if(!empty($_GPC['cert'])) {
                $ret = file_put_contents(MB_ROOT.'/cert/'.$_W['uniacid'].'/apiclient_cert.pem', trim($_GPC['cert']));
                $r = $r && $ret;
            }
            if(!empty($_GPC['key'])) {
                $ret = file_put_contents(MB_ROOT.'/cert/'.$_W['uniacid'].'/apiclient_key.pem', trim($_GPC['key']));
                $r = $r && $ret;
            }
			if(!$r) {
                message('证书保存失败, 请保证 /addons/wz_tuan/cert/ 目录可写');
            }
			$dat = array(
				'share_type'=>$_GPC['share_type'],
				'userrefund'=>$_GPC['userrefund'],
				'gettime'=>$_GPC['gettime'],
				'goodstip'=>$_GPC['goodstip'],
				'openfirstpay'=>$_GPC['openfirstpay'],
				'firstpay'=>$_GPC['firstpay'],
				'refundpercent' => $_GPC['refundpercent'],
				'status' => $_GPC['status'],
				'sharestatus' => $_GPC['sharestatus'],
				'mode' => $_GPC['mode'],
				'picmode' => $_GPC['picmode'],
				'mchid' => $_GPC['mchid'],
				'apikey' => $_GPC['apikey'],
                'share_title' => $_GPC['share_title'],
                'share_image' => $_GPC['share_image'],
                'share_desc' => $_GPC['share_desc'],
                'share_imagestatus'=>$_GPC['share_imagestatus'],
                'pay_suc'=>$_GPC['pay_suc'],
                'm_pay'=>$_GPC['m_pay'],
                'm_tuan'=>$_GPC['m_tuan'],
                'm_cancle'=>$_GPC['m_cancle'],
                'm_ref'=>$_GPC['m_ref'],
                'm_send'=>$_GPC['m_send'],
                'pay_remark'=>$_GPC['pay_remark'],
                'tuan_remark'=>$_GPC['tuan_remark'],
                'tuan_suc'=>$_GPC['tuan_suc'],
                'cancle_remark'=>$_GPC['cancle_remark'],
                'cancle'=>$_GPC['cancle'],
                'send_remark'=>$_GPC['send_remark'],
                'send'=>$_GPC['send'],
                'ref_remark'=>$_GPC['ref_remark'],
                'ref'=>$_GPC['ref'],
                'sname'=>$_GPC['sname'],
                'slogo'=>$_GPC['slogo'],
                'marketprice1'=>$_GPC['marketprice1'],
                'marketprice2'=>$_GPC['marketprice2'],
                'marketprice3'=>$_GPC['marketprice3'],
                'marketprice4'=>$_GPC['marketprice4'],
                'productprice1'=>$_GPC['productprice1'],
                'productprice2'=>$_GPC['productprice2'],
                'productprice3'=>$_GPC['productprice3'],
                'productprice4'=>$_GPC['productprice4'],
                'copyright'=>$_GPC['copyright'],
                'content' => htmlspecialchars_decode($_GPC['content'])
            );
			if ($this->saveSettings($dat)) {
                message('保存成功', 'refresh');
            }
		}
		//这里来展示设置项表单
		include $this->template('web/setting');
	}
}