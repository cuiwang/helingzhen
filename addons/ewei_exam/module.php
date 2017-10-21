<?php

/**
 * 微考试模块定义
 *
 */
defined('IN_IA') or exit('Access Denied');
include "../addons/ewei_exam/model.php";
class Ewei_examModule extends WeModule
{
    public $_weid = '';
    public $_types_config = '';
    public $_set_info = array();
    public $_answer_array = array();

    function __construct()
    {
        global $_W;
        $this->_weid = $_W['uniacid'];

        $this->_set_info =  get_ewei_exam_sysset();
        $init_param =  get_init_param();
        $this->_types_config = $init_param['types_config'];
        $this->_answer_array = $init_param['answer_array'];

        //print_r($init_param);exit;
    }


    public function fieldsFormDisplay($rid = 0)
    {
        //要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
        global $_W, $_GPC;
        if (!empty($rid)) {
            $reply = pdo_fetchall("SELECT * FROM " . tablename('ewei_exam_reply') . " WHERE rid = :rid", array(':rid' => $rid));
            if (!empty($reply)) {
                foreach ($reply as $row) {
                    $paperids[$row['paperid']] = $row['paperid'];
                }
                $album = pdo_fetchall("SELECT id, title, thumb, description FROM " . tablename('ewei_exam_paper') . " WHERE id IN (" . implode(',', $paperids) . ")", array(), 'id');
            }
        }
        include $this->template('rule');
    }

    public function fieldsFormValidate($rid = 0)
    {
        //规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
        return '';
    }

    public function fieldsFormSubmit($rid)
    {
        //规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
        global $_W, $_GPC;
        if (!empty($_GPC['paperid'])) {
            foreach ($_GPC['paperid'] as $aid) {
                pdo_insert('ewei_exam_reply', array(
                    'rid' => $rid,
                    'paperid' => $aid,
                    'weid' => $_W['uniacid'],
                ));
            }
        }
    }

    public function ruleDeleted($rid)
    {
        //删除规则时调用，这里 $rid 为对应的规则编号
    }

}

