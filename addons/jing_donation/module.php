<?php
/**
 * 微募捐模块定义
 *
 * @author 刘靜
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Jing_donationModule extends WeModule {
	public $t_reply = 'jing_donation_reply';
	public $t_donation = 'jing_donation';

	public function fieldsFormDisplay($rid = 0) {
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
		if (!empty($rid)) {
            $reply = pdo_fetchall("SELECT * FROM " . tablename($this->t_reply) . " WHERE rid = :rid ORDER BY ID ASC", array(':rid' => $rid));
            if (!empty($reply)) {
                foreach ($reply as $row) {
                    $donation[$row['donationid']] = $row['donationid'];
                }
                $donation = pdo_fetchall("SELECT id, title, thumb, description FROM " . tablename($this->t_donation) . " WHERE id IN (" . implode(',', $donation) . ")", array(), 'id');
            }
        }
        include $this->template('rule');
	}

	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		return '';
	}

	public function fieldsFormSubmit($rid) {
		//规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
		global $_W, $_GPC;
        
        //删除旧的
        pdo_query("delete from ".tablename($this->t_reply)." where rid=:rid",array(':rid'=>$rid));

        if (!empty($_GPC['donationid'])) {
            foreach ($_GPC['donationid'] as $aid) {
                pdo_insert($this->t_reply, array(
                    'rid' => $rid,
                    'donationid' => $aid,
                ));
            }
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
		pdo_delete($this->t_reply, "id IN ('".implode("','", $deleteid)."')");
	}

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		load()->func('tpl');
        if (checksubmit()) {
            $cfg = array(
                'sitename' => $_GPC['sitename'],
                'sitelogo' => $_GPC['sitelogo'],
                'sitedescription' => $_GPC['sitedescription'],
                'tpl1' => $_GPC['tpl1'],
                'tpl2' => $_GPC['tpl2'],
                'admin_openid' => $_GPC['admin_openid']
            );
            if ($this->saveSettings($cfg)) {
                message('保存成功', 'refresh');
            }
        }
		include $this->template('setting');
	}

}