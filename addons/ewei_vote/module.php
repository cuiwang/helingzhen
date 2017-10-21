<?php

/**
 * 投票系统
 *
 * [WeiZan System] Copyright (c) 2013 012wz.com
 */
defined('IN_IA') or exit('Access Denied');

class Ewei_voteModule extends WeModule
{

    public $name = 'Vote';
    public $title = '投票系统';
    public $ability = '';
    public $tablename = 'vote_reply';

    public function fieldsFormDisplay($rid = 0) {
        global $_W;
        if (!empty($rid)) {
            $reply = pdo_fetch("SELECT * FROM " . tablename($this->tablename) . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
            $options = pdo_fetchall("select * from " . tablename('vote_option') . " where rid=:rid order by id asc", array(':rid' => $rid));
            foreach ($options as &$o) {
                $o['type'] = $reply['isimg'] == '1' ? 'image' : 'text';
            }
            unset($o);
        }
        if (!$reply) {
            $now = time();
            $reply = array(
                "starttime" => $now,
                "endtime" => strtotime(date("Y-m-d H:i", $now + 7 * 24 * 3600)),
                "share_title" => "欢迎参加投票活动",
                "share_desc" => "亲，欢迎参加投票活动！ 亲，需要绑定账号才可以参加哦",
                "share_txt" => "&lt;p&gt;1. 关注微信公众账号\"()\"&lt;/p&gt;&lt;p&gt;2. 发送消息\"投票\", 点击返回的消息即可参加&lt;/p&gt;",
            );
        }
        include $this->template('form');
    }

    public function fieldsFormValidate($rid = 0) {
        return true;
    }

    public function fieldsFormSubmit($rid = 0) {
        global $_GPC, $_W;
        $id = intval($_GPC['reply_id']);
        $insert = array(
            'rid' => $rid,
            'weid' => $_W['uniacid'],
            'title' => $_GPC['title'],
            'description' => $_GPC['description'],
            'votetype' => $_GPC['votetype'],
            'votelimit' => $_GPC['votelimit'],
            'votetimes' => $_GPC['votetimes'],
            'votetotal' => $_GPC['votetotal'],
            'isimg' => $_GPC['isimg'],
            'share_title' => $_GPC['share_title'],
            'share_desc' => preg_replace('/\s/i', '', str_replace('	', '', cutstr(str_replace('&nbsp;', '', ihtmlspecialchars(strip_tags($_GPC['share_desc']))), 60))),
            'share_url' => $_GPC['share_url'],
            'share_txt' => $_GPC['share_txt'],
            'starttime' => strtotime($_GPC['datelimit']['start']),
            'endtime' => strtotime($_GPC['datelimit']['end'])
        );
        if (!empty($_GPC['thumb'])) {
            $insert['thumb'] = $_GPC['thumb'];
            load()->func('file');
            file_delete($_GPC['thumb-old']);
        }

        if (empty($id)) {
            if ($insert['starttime'] <= TIMESTAMP) {
                $insert['isshow'] = 1;
            } else {
                $insert['isshow'] = 0;
            }
            $id = pdo_insert($this->tablename, $insert);
        } else {
            pdo_update($this->tablename, $insert, array('id' => $id));
        }
        $options = array();
        $option_ids = $_POST['option_id'];
        $option_titles = $_POST['option_title'];
        $option_thumb_olds = $_POST['option_thumb_old'];
        $files = $_FILES;
        $len = count($option_ids);
        $ids = array();
        for ($i = 0; $i < $len; $i++) {
            $item_id = $option_ids[$i];
            $a = array(
                "title" => $option_titles[$i],
                "rid" => $rid,
                "thumb" => $_GPC['option_thumb_' . $item_id]
            );
            if ((int)$item_id == 0) {
                pdo_insert("vote_option", $a);
                $item_id = pdo_insertid();
            } else {
                pdo_update("vote_option", $a, array('id' => $item_id));
            }
            $ids[] = $item_id;
        }
        if (!empty($ids)) {
            pdo_query("delete from " . tablename('vote_option') . " where  rid = $rid and  id not in ( " . implode(',', $ids) . ")");
        }
        return true;
    }

    public function ruleDeleted($rid = 0) {
        pdo_delete('vote_reply', array('rid' => $rid));
        pdo_delete('vote_fans', array('rid' => $rid));
        pdo_delete('vote_option', array('rid' => $rid));
        return true;
    }

}
