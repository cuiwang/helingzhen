<?php
/**
 * 方言听力版模块微站定义
 *
 * @author 华轩科技
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Hx_dialectModuleSite extends WeModuleSite {

	public function doMobileDetail() {
		global $_W, $_GPC;
		$dnum = isset($this->module['config']['num']) ? $this->module['config']['num'] : '182031';
		$this->saveSettings(array('num'=>$dnum+1));
		$uniacid=$_W['uniacid'];
		//这个操作被定义用来呈现 微站首页导航图标
		$id = intval($_GPC['id']);
		if ($id) {
			$reply = pdo_fetch("SELECT * FROM " . tablename('hx_dialect_reply') . " WHERE id = :id", array(':id' => $id));
			$num = $reply['num'];
			$questions = pdo_fetchall("SELECT *  from ".tablename('hx_dialect_questions')." where uniacid='{$uniacid}' order by rand() LIMIT ".$num );
			$answer = '';
			foreach ($questions as $key => $value) {
				$answer .= '"'.strtoupper($value['answer']).'",';
				$mark .= '"'.strtoupper($value['mark']).'",';
				$marks = $marks + $value['mark'];
			}
			$answer = substr($answer,0,strlen($answer)-1);
			$mark = substr($mark,0,strlen($mark)-1);
			$m1 = $marks*0.9;
			$m2 = $marks*0.7;
			$m3 = $marks*0.5;
			$m4 = $marks*0.3;
			$s_title = str_replace("#score#", "' + arr[0] + '", $reply['s_title']);
			$s_title = str_replace("#dialect#", $reply['r_name'], $s_title);
			$s_des = str_replace("#score#", "' + arr[0] + '", $reply['s_des']);
			$s_des = str_replace("#dialect#", $reply['r_name'], $s_des);	
		}else{
			message('参数错误');
		}
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false) {
			//message('请在微信中打开链接',$reply['s_sucai'],'error');
		}
		include $this->template('detail_1212');
	}

	public function doWebList(){
		global $_GPC,  $_W;
		$uniacid=$_W["uniacid"];
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$where ="";
		$mark = $_GPC['mark'];
		$keyword = $_GPC['keyword'];
		if(!empty($mark)){
			$where .= " AND mark = '$mark'";
		}
		if(!empty($keyword)){
			$where .= " AND title like '%$keyword%'";
		}
		if (!empty($_GPC['Deleteall']) && !empty($_GPC['select'])) {
			foreach ($_GPC['select'] as $k => $v) {
				pdo_delete('hx_dialect_questions', array('id' => $v,'uniacid'=> $_W['uniacid']));	
			}
			message('成功删除选中的防伪码！', referer(), 'success');
		}
		if (!empty($_GPC['Frozenall']) && !empty($_GPC['select'])) {
			foreach ($_GPC['select'] as $k => $v) {
				pdo_update('securitys_data', array('status' => 0), array('id' => $v,'uniacid'=>$_W['uniacid']));
			}
			message('成功冻结选中的防伪码！', referer(), 'success');
		}
		$list = pdo_fetchall("SELECT *  from ".tablename('hx_dialect_questions')." where uniacid='{$uniacid}' $where order by id asc LIMIT ". ($pindex -1) * $psize . ',' .$psize );
		$total = pdo_fetchcolumn("SELECT COUNT(*)  from ".tablename('hx_dialect_questions')." where uniacid='{$uniacid}' $where order by id asc");
		$pager = pagination($total, $pindex, $psize);
		load()->func('tpl');
		include $this->template('list');
	}

	public function doWebAdd() {
		global $_GPC,  $_W;
		load()->func('tpl');
		$id = intval($_GPC['id']);
		if (!empty($id)) {
			$item = pdo_fetch("SELECT * FROM ".tablename('hx_dialect_questions')." WHERE id = :id" , array(':id' => $id));
			if (empty($item)) {
				message('抱歉，题目不存在或是已经删除！', '', 'error');
			}
		}
		if(checksubmit('submit')) {
			$data = array(
				'uniacid'	=>	$_W['uniacid'],
				'title'	=>	$_GPC['title'],
				'audio'	=>	$_GPC['audio'],
				'a'		=>	$_GPC['x_a'],
				'b'		=>	$_GPC['x_b'],
				'c'		=>	$_GPC['x_c'],
				'd'		=>	$_GPC['x_d'],
				'answer'=>	$_GPC['answer'],
				'mark'	=>	$_GPC['mark'],
				'hard'	=>	$_GPC['hard'],
				'remark'=>	$_GPC['remark'],
				'status'=>	'1',				
				);
			if (empty($id)) {
				pdo_insert('hx_dialect_questions', $data);
				message('题目添加成功！', referer(), 'success');
			}else{
				pdo_update('hx_dialect_questions', $data, array('id' => $id));
				message('题目编辑成功！', referer(), 'success');
			}
		}
		include $this->template('add');
	}
	//冻结题目 将题目状态设置为不可用
	public function doWebFrozen(){
		global $_GPC, $_W;
		pdo_update('hx_dialect_questions', array('status' => 0), array('id' => $_GPC['id'],'uniacid'=>$_W['uniacid']));	
		message('成功冻结本道题目！', referer(), 'success');	
	}
	public function doWebUnFrozen(){
		global $_GPC, $_W;
		pdo_update('hx_dialect_questions', array('status' => 1), array('id' => $_GPC['id'],'uniacid'=>$_W['uniacid']));	
		message('成功启用本道题目！', referer(), 'success');	
	}
	//删除题目 彻底删除数据
	public function doWebDelete(){
		global $_GPC, $_W;
		if(!empty($id)){
			$set = pdo_delete('hx_dialect_questions', array('id' => $_GPC['id'],'uniacid'=> $_W['uniacid']));	
			message('成功删除本道题目！', referer(), 'success');	
		}
	}

}