<?php
/**
 * 一键导航模块定义
 *
 * @author yhctech
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Yhc_onenaviModule extends WeModule {
	public function fieldsFormDisplay($rid = 0) {
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
		global $_W;
		$uniacid = $_W['uniacid'];
		if (!empty($rid)) {

			$item = pdo_fetch("SELECT * FROM ".tablename('yhc_onenavi')." WHERE rid = :rid and uniacid=:uniacid ORDER BY `id` DESC", array(':rid' => $rid,':uniacid'=> $uniacid));

			$url = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . "&c=entry&m=yhc_onenavi&do=redirect&rid=".$item["rid"];
		}
		include $this->template('form');
	}

	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		global $_GPC;
		if (empty($_GPC['title'])) {
            return '请输入坐标名称！';
        }
        if (empty($_GPC['baidumap']['lng']) || empty($_GPC['baidumap']['lat'])) {
        	return '请选择坐标！';
        }
		return '';
	}

	public function fieldsFormSubmit($rid) {
		//规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
		global $_GPC, $_W;
		$uniacid=$_W['uniacid'];
		$id = intval($_GPC['reply_id']);
		$insert = array(
			'rid' => $rid,
			'uniacid' => $uniacid,
			'title' => $_GPC['title'],
			'lng' => $_GPC['baidumap']['lng'],				
			'lat' => $_GPC['baidumap']['lat'],
		);
		if (empty($id)) {
			pdo_insert('yhc_onenavi', $insert);
		} else {
			pdo_update('yhc_onenavi', $insert, array('id' => $id));
		}
	}

	public function ruleDeleted($rid) {
		//删除规则时调用，这里 $rid 为对应的规则编号
		global $_GPC;
	    pdo_delete('yhc_onenavi', array('rid' => $rid));
	    return true;
	}


}