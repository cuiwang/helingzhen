<?php
/**
 * 女神来了模块定义
 *
 * @author 微赞科技
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
  $rule = pdo_fetch("select id, module from " . tablename('rule') . " where id = :id", array(':id' => $rid));
        if (empty($rule)) {
            message('抱歉，要修改的规则不存在或是已经被删除！');
        }
        if (pdo_delete('rule', array('id' => $rid))) {
            pdo_delete('rule_keyword', array('rid' => $rid));
            //删除统计相关数据
            pdo_delete('stat_rule', array('rid' => $rid));
            pdo_delete('stat_keyword', array('rid' => $rid));
            //调用模块中的删除
            $module = WeUtility::createModule($rule['module']);
            if (method_exists($module, 'ruleDeleted')) {
               pdo_delete($this->table_reply, array('rid' => $rid));
                pdo_delete($this->table_reply_share, array('rid' => $rid));
                pdo_delete($this->table_reply_huihua, array('rid' => $rid));
                pdo_delete($this->table_reply_display, array('rid' => $rid));
                pdo_delete($this->table_reply_vote, array('rid' => $rid));
                pdo_delete($this->table_reply_body, array('rid' => $rid));
                pdo_delete($this->table_users, array('rid' => $rid));
                pdo_delete($this->table_log, array('rid' => $rid));
                pdo_delete($this->table_bbsreply, array('rid' => $rid));
                pdo_delete($this->table_banners, array('rid' => $rid));
                pdo_delete($this->table_advs, array('rid' => $rid));
                pdo_delete($this->table_data, array('rid' => $rid));
                pdo_delete($this->table_announce, array('rid' => $rid));
                pdo_delete($this->table_iplist, array('rid' => $rid));
                pdo_delete($this->table_iplistlog, array('rid' => $rid));
                pdo_delete($this->table_users_name, array('rid' => $rid));
                pdo_delete($this->table_users_voice, array('rid' => $rid));
                pdo_delete($this->table_order, array('rid' => $rid));
				pdo_delete($this -> table_counter, array('rid' => $rid));
				pdo_delete($this -> table_jifen, array('rid' => $rid));
				pdo_delete($this -> table_jifen_gift, array('rid' => $rid));
				pdo_delete($this -> table_user_gift, array('rid' => $rid));
				pdo_delete($this -> table_user_zsgift, array('rid' => $rid));
				pdo_delete($this -> table_msg, array('rid' => $rid));
				pdo_delete($this -> table_orderlog, array('rid' => $rid));
				pdo_delete($this -> table_qrcode, array('rid' => $rid));
				pdo_delete($this -> table_answer, array('rid' => $rid));
            }
        }
        message('活动删除成功！', referer(), 'success');
    