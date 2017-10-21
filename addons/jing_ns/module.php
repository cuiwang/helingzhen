<?php

defined('IN_IA') or exit('Access Denied');

class Jing_nsModule extends WeModule {
	public $table_reply = 'jing_ns_reply';
	public function fieldsFormDisplay($rid = 0) {
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
		global $_W, $_GPC;
		load()->func('tpl');
		if($rid==0){
			$reply = array(
				'title'=> '测测你的女神是谁!',
				'description' => '我才不会告诉你,我是这样约到我的女神的呢 ≥﹏≤',
				'loading'	=>  '../addons/jing_ns/template/style/img/loading_img.png',
				'audio'=>  '../addons/jing_ns/template/style/media/musicbg.mp3',
				'logo'	=>	'../addons/jing_ns/template/style/img/logo.png',
				'share_title'=> '小黄人抢衣服活动开始了',
				'share_title2'=> '我在小黄人抢衣服活动中抢到了[score]件衣服！你也来试试吧！',
				'share_content'=> '亲，欢迎参加小黄人抢衣服活动，祝您好运哦！！',
			);
		}else{
			$reply = pdo_fetch("SELECT * FROM ".tablename($this->table_reply)." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
		}
		include $this->template('form');
	}

	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		return '';
	}

	public function fieldsFormSubmit($rid) {
		//规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
		global $_W,$_GPC;
		$id = intval($_GPC['reply_id']);
		$insert = array(
				'rid' => $rid,
				'uniacid' => $_W['uniacid'],
				'title' => $_GPC['title'],
				'thumb' => $_GPC['thumb'],
				'description' => $_GPC['description'],
				'loading' => $_GPC['loading'],
				'logo' => $_GPC['logo'],
				'audio' => $_GPC['audio'],
				'link1' => $_GPC['link1'],
				'link2' => $_GPC['link2'],
				'createtime' => time(),		
			);
		if (empty($id)) {
			pdo_insert($this->table_reply, $insert);
		} else {
			unset($insert['createtime']);
			pdo_update($this->table_reply, $insert, array('id' => $id));
		}
	}

	public function ruleDeleted($rid) {
		//删除规则时调用，这里 $rid 为对应的规则编号
		$replies = pdo_fetchall("SELECT id  FROM ".tablename($this->table_reply)." WHERE rid = '$rid'");
		$deleteid = array();
		if (!empty($replies)) {
			foreach ($replies as $index => $row) {
				$deleteid[] = $row['id'];
			}
		}
		pdo_delete($this->table_reply, "id IN ('".implode("','", $deleteid)."')");
	}

}