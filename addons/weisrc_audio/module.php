<?php
/**
 * 音乐盒子
 *
 * 作者:迷失卍国度
 *
 * qq:15595755
 */
defined('IN_IA') or exit('Access Denied');
include 'plugin/phpexcelreader/reader.php';
class weisrc_audioModule extends WeModule
{
    public $name = 'weisrc_audioModule';
    public $tablename = 'weisrc_audio_reply';
    public $modulename = 'weisrc_audio'; //模块标识
    public $actions_titles = array();

    public function fieldsFormSubmit($rid = 0)
    {
        global $_GPC, $_W;
        $id = intval($_GPC['reply_id']);
        $data = array(
            'rid' => $rid,
            'title' => $_GPC['title'],
            'picture' => $_GPC['picture'],
            'description' => $_GPC['description'],
            'dateline' => TIMESTAMP
        );
        if (empty($id)) {
            pdo_insert($this->tablename, $data);
        } else {
            if (!empty($_GPC['picture'])) {
                file_delete($_GPC['picture-old']);
            } else {
                unset($data['picture']);
            }
            unset($data['dateline']);
            pdo_update($this->tablename, $data, array('id' => $id));
        }
    }

    public function fieldsFormDisplay($rid = 0)
    {
        global $_W;
        if (!empty($rid)) {
            $reply = pdo_fetch("SELECT * FROM " . tablename($this->tablename) . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
        }
        include $this->template('form');
    }

    public function fieldsFormValidate($rid = 0)
    {
        return true;
    }

    //删除规则
    public function ruleDeleted($rid = 0)
    {
        global $_W;
        $replies = pdo_fetchall("SELECT id FROM " . tablename($this->tablename) . " WHERE rid = '$rid'");
        $deleteid = array();
        if (!empty($replies)) {
            foreach ($replies as $index => $row) {
                $deleteid[] = $row['id'];
            }
        }
        pdo_delete($this->tablename, "id IN ('" . implode("','", $deleteid) . "')");
        return true;
    }


    //97
}