<?php
defined('IN_IA') or exit('Access Denied');
class Yg_parkourModule extends WeModule
{
    public $table_reply = 'yg_parkour_reply';
    public $table_oauth = 'yg_parkour_oauth';
    public function fieldsFormDisplay($rid = 0)
    {
        global $_W;
        if ($rid == 0) {
            $reply = array(
                'starttime' => time(),
                'endtime' => time() + 10 * 84400,
                'status' => 1,
                "indexbg" => MODULE_URL . "template/mobile/js/main_bg_2014091101.jpg",
                "gamebg" => MODULE_URL . "template/mobile/js/main_png8_2014091601.png",
                "phbbg" => MODULE_URL . "template/mobile/js/ph.png",
                "resultbg" => MODULE_URL . "template/mobile/js/result_bg_2014091001.jpg"
            );
        } else {
            $reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE rid = :rid ORDER BY `id` DESC", array(
                ':rid' => $rid
            ));
        }
        include $this->template('form');
    }
    public function fieldsFormValidate($rid = 0)
    {
        return '';
    }
    public function fieldsFormSubmit($rid)
    {
        global $_W, $_GPC;
        $id     = intval($_GPC['reply_id']);
        $i      = 1;
        $insert = array(
            'rid' => $rid,
            'uniacid' => $_W['uniacid'],
            'title' => $_GPC['title'],
            'thumb' => $_GPC['thumb'],
            'description' => $_GPC['description'],
            'starttime' => strtotime($_GPC['time'][start]),
            'endtime' => strtotime($_GPC['time'][end]),
            'status' => intval($_GPC['status']),
            'indexbg' => $_GPC['indexbg'],
            'rule' => $_GPC['rule'],
            'follelink' => $_GPC['follelink'],
            'pnum' => $_GPC['pnum'],
            'addnum' => $_GPC['addnum'],
            'gametime' => $_GPC['gametime'],
            'gamebg' => $_GPC['gamebg'],
            'phbbg' => $_GPC['phbbg'],
            'resultbg' => $_GPC['resultbg'],
            'turng' => $_GPC['turng'],
            'sharepic' => $_GPC['sharepic'],
            'sharedesc' => $_GPC['sharedesc'],
            'sharetitle' => $_GPC['sharetitle'],
            'forward' => $_GPC['forward'],
            'isfolle' => $_GPC['isfolle'],
            'createtime' => TIMESTAMP
        );
        if (empty($id)) {
            pdo_insert($this->table_reply, $insert);
        } else {
            unset($insert['createtime']);
            pdo_update($this->table_reply, $insert, array(
                'id' => $id
            ));
        }
    }
    public function ruleDeleted($rid)
    {
        $replies  = pdo_fetchall("SELECT id  FROM " . tablename($this->table_reply) . " WHERE rid = '$rid'");
        $deleteid = array();
        if (!empty($replies)) {
            foreach ($replies as $index => $row) {
                $deleteid[] = $row['id'];
            }
        }
        pdo_delete($this->table_reply, "id IN ('" . implode("','", $deleteid) . "')");
    }
    public function settingsDisplay($settings)
    {
        global $_GPC, $_W;
        if (checksubmit()) {
            $cfg           = array();
            $cfg['appid']  = $_GPC['appid'];
            $cfg['secret'] = $_GPC['secret'];
            if ($this->saveSettings($cfg)) {
                $insert = array(
                    'weid' => $_W['weid'],
                    'appid' => $cfg['appid'],
                    'secret' => $cfg['secret']
                );
                $result = pdo_fetch("select * from " . tablename($this->table_oauth) . " where 1=1 and weid={$_W['weid']}");
                if (empty($result)) {
                    pdo_insert($this->table_oauth, $insert);
                } else {
                    pdo_update($this->table_oauth, $insert, array(
                        'id' => $result['id']
                    ));
                }
                message('保存成功', 'refresh');
            }
        }
        $config = '已授权';
        include $this->template('setting');
    }
}