<?php
defined('IN_IA') or exit('Access Denied');
class Msyou_meituzoneModule extends WeModule
{
    function tables_check($tablestr = '')
    {
        global $_W;
        require 'create_tables.php';
    }
    public function fieldsFormDisplay($rid = 0)
    {
        $this->tables_check('');
        global $_W;
        if (!empty($rid)) {
            $reply = pdo_fetch("SELECT * FROM " . tablename('msyou_meituzone_reply') . " WHERE rid = :rid ORDER BY `id` DESC", array(
                ':rid' => $rid
            ));
            $rules = unserialize($reply['rules']);
        }
        if (!$reply) {
            $now   = time();
            $reply = array(
                "starttime" => $now,
                "endtime" => strtotime(date("Y-m-d H:i", $now + 7 * 24 * 3600)),
                "content" => '<p style="font-size: 16px;"><strong>活动规则：</strong></p>
<p style="font-size: 16px;">1.参与本次活动请使用微信客户端参与。</p>
<p style="font-size: 16px;">2.活动排名以<span style="color: #ff0000;">XX(逗值和分享数)XX</span>为准。</p>
<p style="font-size: 16px;">3.奖励领取：关注 xxxx 后,回复 xxxx, 即可申请领奖。</p>
<p style="font-size: 16px;">4.金额兑现时间：活动结束后5个工作日内发放完毕。</p>
<p style="font-size: 16px;">5.在获取和使用过程中，如果出现违规行为，本平台将根据自身合理判断取消用户资格。</p>
<p style="font-size: 16px;">6.本次活动最终解释权归：<span style="color: #ff0000; text-decoration: none;"><strong><span style="color: #ff0000;"><strong>xxxxxx</strong></span></strong></span><span style="line-height: 1.5; font-size: 16px;">官方所有。</span></p>',
                'title' => 'XXXXX',
                'detail' => '<p style="font-size: 16px;">xxxx全民逗比秀场，秀出逗比样！！！</p>',
                'thumburl' => MODULE_URL . "style/images/thumb.png",
                'loadimgurl' => MODULE_URL . "style/images/loading.gif",
                'bgurl' => MODULE_URL . "style/images/bg.png",
                'topurl' => MODULE_URL . "style/images/top.png",
                'crinfo' => '技术支持：<a target="_blank" href="http://sighttp.qq.com/authd?IDKEY=0f914dd5867cdfefe7105e854d44e4b6e530fc501a8b4ba2">QQ1801764826</a>',
                'zanx' => 3,
                'sharex' => 2,
                'viewx' => 1,
                'indexshownick' => 1,
                'indexshowcontent' => 0,
                'maxaddimg' => 3,
                'pagesize' => 5,
                'gzjoin' => 0,
                'gzzan' => 0,
                'justpai' => 0,
                'justpinglun' => 0
            );
        }
        load()->func('tpl');
        include $this->template('form');
    }
    public function fieldsFormValidate($rid = 0)
    {
        return '';
    }
    public function fieldsFormSubmit($rid)
    {
        global $_GPC, $_W;
        $id     = intval($_GPC['reply_id']);
        $insert = array(
            'rid' => $rid,
            'uniacid' => $_W['uniacid'],
            'title' => $_GPC['title'],
            'detail' => htmlspecialchars_decode($_GPC['detail']),
            'thumburl' => $_GPC['thumburl'],
            'loadimgurl' => $_GPC['loadimgurl'],
            'bgurl' => $_GPC['bgurl'],
            'topurl' => $_GPC['topurl'],
            'content' => htmlspecialchars_decode($_GPC['content']),
            'starttime' => strtotime($_GPC['datelimit']['start']),
            'endtime' => strtotime($_GPC['datelimit']['end']),
            'crinfo' => htmlspecialchars_decode($_GPC['crinfo']),
            'zanx' => $_GPC['zanx'],
            'sharex' => $_GPC['sharex'],
            'viewx' => $_GPC['viewx'],
            'indexshownick' => intval($_GPC['indexshownick']),
            'indexshowcontent' => intval($_GPC['indexshowcontent']),
            'followurl' => $_GPC['followurl'],
            'indexshowzan' => intval($_GPC['indexshowzan']),
            'maxaddimg' => $_GPC['maxaddimg'],
            'pagesize' => $_GPC['pagesize'],
            'gzjoin' => intval($_GPC['gzjoin']),
            'gzzan' => intval($_GPC['gzzan']),
            'musicurl' => json_encode($_GPC['musicurl']),
            'coverurl' => $_GPC['coverurl'],
            'justpai' => intval($_GPC['justpai']),
            'justpinglun' => intval($_GPC['justpinglun'])
        );
        if (empty($id)) {
            if ($insert['starttime'] <= time()) {
                $insert['status'] = 1;
            } else {
                $insert['status'] = 0;
            }
            $insert['creater']    = $_W['username'];
            $insert['createtime'] = time();
            $id                   = pdo_insert('msyou_meituzone_reply', $insert);
        } else {
            $insert['editer']   = $_W['username'];
            $insert['edittime'] = time();
            pdo_update('msyou_meituzone_reply', $insert, array(
                'id' => $id
            ));
        }
        return true;
    }
    public function ruleDeleted($rid)
    {
        pdo_delete('msyou_meituzone_reply', array(
            'rid' => $rid
        ));
        pdo_delete('msyou_meituzone_join', array(
            'rid' => $rid
        ));
    }
}