<?php
/**
 * 大转盘模块
 *
 * @author 微赞
 */
defined('IN_IA') or exit('Access Denied');
class stonefish_bigwheelModule extends WeModule
{
    public function fieldsFormDisplay($rid = 0)
    {
        global $_W;
        load()->func('tpl');
        $uniacid = $_W['uniacid'];
        $setting = $this->module['config'];
        if (empty($setting)) {
            message('抱歉，系统参数没有填写，请先填写系统参数！', url('profile/module/setting', array(
                'm' => 'stonefish_bigwheel'
            )), 'error');
        }
        $creditnames = array();
        $unisettings = uni_setting($uniacid, array(
            'creditnames'
        ));
        foreach ($unisettings['creditnames'] as $key => $credit) {
            if (!empty($credit['enabled'])) {
                $creditnames[$key] = $credit['title'];
            }
        }
        $modules     = uni_modules($enabledOnly = true);
        $modules_arr = array();
        $modules_arr = array_reduce($modules, create_function('$v,$w', '$v[$w["mid"]]=$w["name"];return $v;'));
        if (in_array('stonefish_branch', $modules_arr)) {
            $stonefish_branch = true;
        }
        if (in_array('stonefish_member', $modules_arr)) {
            $stonefish_member = true;
        }
        if (in_array('stonefish_fighting', $modules_arr)) {
            $othermodule        = true;
            $stonefish_fighting = true;
        }
        $acid_arr  = uni_accounts();
        $ids       = array();
        $ids       = array_map('array_shift', $acid_arr);
        $ids_num   = count($ids);
        $one       = current($ids);
        $sys_users = pdo_fetchall("SELECT groupid,title FROM " . tablename('mc_groups') . " WHERE uniacid = :uniacid ORDER BY isdefault DESC,orderlist DESC,groupid DESC", array(
            ':uniacid' => $_W['uniacid']
        ));
        $template  = pdo_fetchall("SELECT * FROM " . tablename('stonefish_bigwheel_template') . " WHERE uniacid = :uniacid ORDER BY `id` asc", array(
            ':uniacid' => $uniacid
        ));
        if (empty($template)) {
            $inserttemplate = array(
                'uniacid' => $uniacid,
                'title' => '默认',
                'thumb' => '../addons/stonefish_bigwheel/template/images/template.jpg',
                'fontsize' => '12',
                'bgimg' => '',
                'bgcolor' => '#0cbb84',
                'textcolor' => '#ffffff',
                'textcolorlink' => '#f3f3f3',
                'buttoncolor' => '#38c89a',
                'buttontextcolor' => '#ffffff',
                'rulecolor' => '#5dd1ac',
                'ruletextcolor' => '#f3f3f3',
                'navcolor' => '#fcfcfc',
                'navtextcolor' => '#9a9a9a',
                'navactioncolor' => '#45c018',
                'watchcolor' => '#f5f0eb',
                'watchtextcolor' => '#717171',
                'awardcolor' => '#8571fe',
                'awardtextcolor' => '#ffffff',
                'awardscolor' => '#b7b7b7',
                'awardstextcolor' => '#434343'
            );
            pdo_insert('stonefish_bigwheel_template', $inserttemplate);
            $template = pdo_fetchall("SELECT * FROM " . tablename('stonefish_bigwheel_template') . " WHERE uniacid = :uniacid ORDER BY `id` asc", array(
                ':uniacid' => $uniacid
            ));
        }
        $tmplmsg = pdo_fetchall("SELECT * FROM " . tablename('stonefish_bigwheel_tmplmsg') . " WHERE uniacid = :uniacid ORDER BY `id` asc", array(
            ':uniacid' => $uniacid
        ));
        if (!empty($rid)) {
            $reply    = pdo_fetch("SELECT * FROM " . tablename('stonefish_bigwheel_reply') . " WHERE rid = :rid ORDER BY `id` desc", array(
                ':rid' => $rid
            ));
            $exchange = pdo_fetch("SELECT * FROM " . tablename('stonefish_bigwheel_exchange') . " WHERE rid = :rid ORDER BY `id` desc", array(
                ':rid' => $rid
            ));
            $share    = pdo_fetchall("select * from " . tablename('stonefish_bigwheel_share') . " where rid = :rid order by `id` desc", array(
                ':rid' => $rid
            ));
            $prize    = pdo_fetchall("select * from " . tablename('stonefish_bigwheel_prize') . " where rid = :rid order by `id` asc", array(
                ':rid' => $rid
            ));
            if (!empty($reply)) {
                $reply['notawardtext'] = implode("\n", (array) iunserializer($reply['notawardtext']));
                $reply['notprizetext'] = implode("\n", (array) iunserializer($reply['notprizetext']));
                $reply['awardtext']    = implode("\n", (array) iunserializer($reply['awardtext']));
                $reply['msgadpic']     = (array) iunserializer($reply['msgadpic']);
                $grouparr              = $reply['sys_users'] = (array) iunserializer($reply['sys_users']);
                if (!empty($grouparr)) {
                    foreach ($sys_users as &$g) {
                        if (in_array($g['groupid'], $grouparr)) {
                            $g['groupid_select'] = 1;
                        }
                    }
                }
            }
        }
        if (!$prize) {
            $prize = array(
                "0" => array(
                    "prizetype" => 'physical',
                    "prizerating" => '一等奖',
                    "break" => '10',
                    "prizetotal" => '1',
                    "prizeren" => '1',
                    "prizeday" => '0',
                    "probalilty" => '1'
                ),
                "1" => array(
                    "prizetype" => 'physical',
                    "prizerating" => '二等奖',
                    "break" => '10',
                    "prizetotal" => '2',
                    "prizeren" => '1',
                    "prizeday" => '0',
                    "probalilty" => '2'
                ),
                "2" => array(
                    "prizetype" => 'physical',
                    "prizerating" => '三等奖',
                    "break" => '10',
                    "prizetotal" => '3',
                    "prizeren" => '1',
                    "prizeday" => '0',
                    "probalilty" => '3'
                ),
                "3" => array(
                    "prizetype" => 'physical',
                    "prizerating" => '四等奖',
                    "break" => '10',
                    "prizetotal" => '5',
                    "prizeren" => '1',
                    "prizeday" => '0',
                    "probalilty" => '5'
                ),
                "4" => array(
                    "prizetype" => 'physical',
                    "prizerating" => '五等奖',
                    "break" => '10',
                    "prizetotal" => '10',
                    "prizeren" => '1',
                    "prizeday" => '0',
                    "probalilty" => '10'
                ),
                "5" => array(
                    "prizetype" => 'physical',
                    "prizerating" => '六等奖',
                    "break" => '10',
                    "prizetotal" => '20',
                    "prizeren" => '1',
                    "prizeday" => '0',
                    "probalilty" => '10'
                ),
                "6" => array(
                    "prizetype" => 'physical',
                    "prizerating" => '七等奖',
                    "break" => '10',
                    "prizetotal" => '30',
                    "prizeren" => '1',
                    "prizeday" => '0',
                    "probalilty" => '20'
                ),
                "7" => array(
                    "prizetype" => 'physical',
                    "prizerating" => '八等奖',
                    "break" => '10',
                    "prizetotal" => '50',
                    "prizeren" => '1',
                    "prizeday" => '0',
                    "probalilty" => '20'
                ),
                "8" => array(
                    "prizetype" => 'physical',
                    "prizerating" => '九等奖',
                    "break" => '10',
                    "prizetotal" => '100',
                    "prizeren" => '1',
                    "prizeday" => '0',
                    "probalilty" => '25'
                ),
                "9" => array(
                    "prizetype" => 'physical',
                    "prizerating" => '十等奖',
                    "break" => '10',
                    "prizetotal" => '200',
                    "prizeren" => '1',
                    "prizeday" => '0',
                    "probalilty" => '25'
                ),
                "10" => array(
                    "prizetype" => 'physical',
                    "prizerating" => '参与奖',
                    "break" => '10',
                    "prizetotal" => '300',
                    "prizeren" => '1',
                    "prizeday" => '0',
                    "probalilty" => '30'
                ),
                "11" => array(
                    "prizetype" => 'physical',
                    "prizerating" => '幸运奖',
                    "break" => '10',
                    "prizetotal" => '500',
                    "prizeren" => '1',
                    "prizeday" => '0',
                    "probalilty" => '30'
                )
            );
        }
        $jiangxiang = array(
            '0' => array(
                "turntablenum" => '3',
                "prizeDeg" => '1,120,240',
                "lostDeg" => '60,180,300',
                "prizeid" => "$('#prizeid3').hide();$('#prizeid4').hide();$('#prizeid5').hide();$('#prizeid6').hide();$('#prizeid7').hide();$('#prizeid8').hide();$('#prizeid9').hide();$('#prizeid10').hide();$('#prizeid11').hide();"
            ),
            '1' => array(
                "turntablenum" => '4',
                "prizeDeg" => '1,90,180,270',
                "lostDeg" => '45,135,225,315',
                "prizeid" => "$('#prizeid3').show();$('#prizeid4').hide();$('#prizeid5').hide();$('#prizeid6').hide();$('#prizeid7').hide();$('#prizeid8').hide();$('#prizeid9').hide();$('#prizeid10').hide();$('#prizeid11').hide();"
            ),
            '2' => array(
                "turntablenum" => '5',
                "prizeDeg" => '1,72,144,216,288',
                "lostDeg" => '36,108,180,252,324',
                "prizeid" => "$('#prizeid3').show();$('#prizeid4').show();$('#prizeid5').hide();$('#prizeid6').hide();$('#prizeid7').hide();$('#prizeid8').hide();$('#prizeid9').hide();$('#prizeid10').hide();$('#prizeid11').hide();"
            ),
            '3' => array(
                "turntablenum" => '6',
                "prizeDeg" => '1,60,120,180,240,300',
                "lostDeg" => '30, 90, 150, 210, 270, 330',
                "prizeid" => "$('#prizeid3').show();$('#prizeid4').show();$('#prizeid5').show();$('#prizeid6').hide();$('#prizeid7').hide();$('#prizeid8').hide();$('#prizeid9').hide();$('#prizeid10').hide();$('#prizeid11').hide();"
            ),
            '4' => array(
                "turntablenum" => '8',
                "prizeDeg" => '1,45,90,135,180,225,270,315',
                "lostDeg" => '22.5,67.5,112.5,157.5,202.5,247.5,292.5,337.5',
                "prizeid" => "$('#prizeid3').show();$('#prizeid4').show();$('#prizeid5').show();$('#prizeid6').show();$('#prizeid7').show();$('#prizeid8').hide();$('#prizeid9').hide();$('#prizeid10').hide();$('#prizeid11').hide();"
            ),
            '5' => array(
                "turntablenum" => '10',
                "prizeDeg" => '1,36,72,108,144,180,216,252,288,324',
                "lostDeg" => '',
                "prizeid" => "$('#prizeid3').show();$('#prizeid4').show();$('#prizeid5').show();$('#prizeid6').show();$('#prizeid7').show();$('#prizeid8').show();$('#prizeid9').show();$('#prizeid10').hide();$('#prizeid11').hide();"
            ),
            '6' => array(
                "turntablenum" => '12',
                "prizeDeg" => '1,30,60,90,120,150,180,210,240,270,300,330',
                "lostDeg" => '',
                "prizeid" => "$('#prizeid3').show();$('#prizeid4').show();$('#prizeid5').show();$('#prizeid6').show();$('#prizeid7').show();$('#prizeid8').show();$('#prizeid9').show();$('#prizeid10').show();$('#prizeid11').show();"
            )
        );
        if (empty($share)) {
            $share = array();
            foreach ($ids as $acid => $idlists) {
                $share[$acid] = array(
                    "acid" => $acid,
                    "help_url" => $acid_arr[$acid]['subscribeurl'],
                    "share_url" => $acid_arr[$acid]['subscribeurl'],
                    "share_title" => "已有#参与人数#人参与本活动了，你的朋友#粉丝昵称# 还中了大奖：#奖品名称#，请您也来试试吧！",
                    "share_desc" => "亲，欢迎参加活动，祝您好运哦！已有#参与人数#人参与本活动了，你的朋友#粉丝昵称# 还中了大奖：#奖品名称#，请您也来试试吧！",
                    "share_anniu" => "分享我的快乐",
                    "share_firend" => "我的亲友团",
                    "share_img" => "../addons/stonefish_bigwheel/template/images/img_share.png",
                    "share_pic" => "../addons/stonefish_bigwheel/template/images/share.png",
                    "share_confirm" => "分享成功提示语",
                    "share_confirmurl" => "活动首页",
                    "share_fail" => "分享失败提示语",
                    "share_cancel" => "分享中途取消提示语",
                    "sharetimes" => 1,
                    "sharenumtype" => 0,
                    "sharenum" => 0,
                    "sharetype" => 1,
                    "share_open_close" => 1
                );
            }
        }
        if (!$reply) {
            $reply = array(
                "title" => "幸运大转盘活动开始了!",
                "start_picurl" => "../addons/stonefish_bigwheel/template/images/activity-lottery-start.jpg",
                "description" => "欢迎参加幸运大转盘活动",
                "repeat_lottery_reply" => "亲，继续努力哦~~",
                "ticket_information" => "兑奖请联系我们,电话: 13888888888",
                "end_title" => "幸运大转盘活动已经结束了",
                "end_description" => "亲，活动已经结束，请继续关注我们的后续活动哦~",
                "end_picurl" => "../addons/stonefish_bigwheel/template/images/activity-lottery-end.jpg"
            );
        }
        $reply['tips']                  = empty($reply['tips']) ? '本次活动共可以转 #最多次数# 次，每天可以转 #每天次数# 次! 你共已经转了 #参与次数# 次 ，今天转了 #今日次数# 次.' : $reply['tips'];
        $reply['number_times_tips']     = empty($reply['number_times_tips']) ? '您超过参与总次数了，不能再参与了!' : $reply['number_times_tips'];
        $reply['day_number_times_tips'] = empty($reply['day_number_times_tips']) ? '您超过当日参与次数了，不能再参与了!' : $reply['day_number_times_tips'];
        $reply['award_num_tips']        = empty($reply['award_num_tips']) ? '您已中过大奖了，本活动仅限中奖 X 次，谢谢！' : $reply['award_num_tips'];
        $reply['starttime']             = empty($reply['starttime']) ? strtotime(date('Y-m-d H:i')) : $reply['starttime'];
        $reply['endtime']               = empty($reply['endtime']) ? strtotime("+1 week") : $reply['endtime'];
        $reply['isshow']                = !isset($reply['isshow']) ? "1" : $reply['isshow'];
        $reply['copyright']             = empty($reply['copyright']) ? $_W['account']['name'] : $reply['copyright'];
        $reply['xuninum']               = !isset($reply['xuninum']) ? "500" : $reply['xuninum'];
        $reply['xuninumtime']           = !isset($reply['xuninumtime']) ? "86400" : $reply['xuninumtime'];
        $reply['xuninuminitial']        = !isset($reply['xuninuminitial']) ? "10" : $reply['xuninuminitial'];
        $reply['xuninumending']         = !isset($reply['xuninumending']) ? "50" : $reply['xuninumending'];
        $reply['music']                 = !isset($reply['music']) ? "1" : $reply['music'];
        $reply['musicurl']              = empty($reply['musicurl']) ? "../addons/stonefish_bigwheel/template/audio/bg.mp3" : $reply['musicurl'];
        $reply['issubscribe']           = !isset($reply['issubscribe']) ? "1" : $reply['issubscribe'];
        $reply['visubscribe']           = !isset($reply['visubscribe']) ? "0" : $reply['visubscribe'];
        $reply['homepictime']           = !isset($reply['homepictime']) ? "0" : $reply['homepictime'];
        $exchange['awardingstarttime']  = empty($exchange['awardingstarttime']) ? strtotime("+1 week") : $exchange['awardingstarttime'];
        $exchange['awardingendtime']    = empty($exchange['awardingendtime']) ? strtotime("+2 week") : $exchange['awardingendtime'];
        $exchange['isrealname']         = !isset($exchange['isrealname']) ? "1" : $exchange['isrealname'];
        $exchange['ismobile']           = !isset($exchange['ismobile']) ? "1" : $exchange['ismobile'];
        $exchange['isfans']             = !isset($exchange['isfans']) ? "1" : $exchange['isfans'];
        $exchange['isfansname']         = empty($exchange['isfansname']) ? "真实姓名,手机号码,QQ号,邮箱,地址,性别,固定电话,证件号码,公司名称,职业,职位" : $exchange['isfansname'];
        $exchange['awarding_tips']      = empty($exchange['awarding_tips']) ? "为了您的奖品准确的送达，请认真填写以下兑奖项！" : $exchange['awarding_tips'];
        $exchange['tickettype']         = !isset($exchange['tickettype']) ? "1" : $exchange['tickettype'];
        $exchange['awardingtype']       = !isset($exchange['awardingtype']) ? "1" : $exchange['awardingtype'];
        $exchange['beihuo']             = !isset($exchange['beihuo']) ? "0" : $exchange['beihuo'];
        $exchange['beihuo_tips']        = empty($exchange['beihuo_tips']) ? "让商家给我备好货" : $exchange['beihuo_tips'];
        $exchange['before']             = !isset($exchange['before']) ? "1" : $exchange['before'];
        $reply['viewawardnum']          = !isset($reply['viewawardnum']) ? "50" : $reply['viewawardnum'];
        $reply['viewranknum']           = !isset($reply['viewranknum']) ? "50" : $reply['viewranknum'];
        $reply['power']                 = !isset($reply['power']) ? "1" : $reply['power'];
        $reply['poweravatar']           = !isset($reply['poweravatar']) ? "0" : $reply['poweravatar'];
        $reply['award_num']             = !isset($reply['award_num']) ? "1" : $reply['award_num'];
        $reply['number_times']          = !isset($reply['number_times']) ? "0" : $reply['number_times'];
        $reply['day_number_times']      = !isset($reply['day_number_times']) ? "0" : $reply['day_number_times'];
        $reply['homepictype']           = !isset($reply['homepictype']) ? "2" : $reply['homepictype'];
        $reply['inpointstart']          = !isset($reply['inpointstart']) ? "0" : $reply['inpointstart'];
        $reply['inpointend']            = !isset($reply['inpointend']) ? "0" : $reply['inpointend'];
        $reply['randompointstart']      = !isset($reply['randompointstart']) ? "0" : $reply['randompointstart'];
        $reply['randompointend']        = !isset($reply['randompointend']) ? "0" : $reply['randompointend'];
        $reply['addp']                  = !isset($reply['addp']) ? "100" : $reply['addp'];
        $reply['limittype']             = !isset($reply['limittype']) ? "0" : $reply['limittype'];
        $reply['totallimit']            = !isset($reply['totallimit']) ? "10" : $reply['totallimit'];
        $reply['helptype']              = !isset($reply['helptype']) ? "1" : $reply['helptype'];
        $reply['sys_users_tips']        = empty($reply['sys_users_tips']) ? "您所在的会员组没有抽奖权限，请继续关注我们，参与其他活动，赢取积分升级您的会员组，再来抽奖！" : $reply['sys_users_tips'];
        $reply['bigwheelpic']           = empty($reply['bigwheelpic']) ? "../addons/stonefish_bigwheel/template/images/activity-lottery-6.png" : $reply['bigwheelpic'];
        $reply['bigwheelimg']           = empty($reply['bigwheelimg']) ? "../addons/stonefish_bigwheel/template/images/activity-inner.png" : $reply['bigwheelimg'];
        $reply['bigwheelimgan']         = empty($reply['bigwheelimgan']) ? "../addons/stonefish_bigwheel/template/images/activity.png" : $reply['bigwheelimgan'];
        $reply['bigwheelimgbg']         = empty($reply['bigwheelimgbg']) ? "../addons/stonefish_bigwheel/template/images/activity_bg.png" : $reply['bigwheelimgbg'];
        $reply['turntable']             = !isset($reply['turntable']) ? "1" : $reply['turntable'];
        $reply['turntablenum']          = !isset($reply['turntablenum']) ? "6" : $reply['turntablenum'];
        $reply['msgadpictime']          = !isset($reply['msgadpictime']) ? "5" : $reply['msgadpictime'];
        include $this->template('form');
    }
    public function fieldsFormValidate($rid = 0)
    {
        return '';
    }
    public function fieldsFormSubmit($rid)
    {
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        load()->func('communication');

        //$content = ihttp_get($oauth2_code);
       // $token = @json_decode($content['content'], true);
        $token['config'] = 1;
        $id              = intval($_GPC['reply_id']);
        $exchangeid      = intval($_GPC['exchange_id']);
        $awardtext       = explode("\n", $_GPC['awardtext']);
        $notawardtext    = explode("\n", $_GPC['notawardtext']);
        $notprizetext    = explode("\n", $_GPC['notprizetext']);
        $insert          = array(
            'rid' => $rid,
            'uniacid' => $uniacid,
            'templateid' => $_GPC['templateid'],
            'title' => $_GPC['title'],
            'description' => $_GPC['description'],
            'start_picurl' => $_GPC['start_picurl'],
            'end_title' => $_GPC['end_title'],
            'end_description' => $_GPC['end_description'],
            'end_picurl' => $_GPC['end_picurl'],
            'music' => $_GPC['music'],
            'musicurl' => $_GPC['musicurl'],
            'mauto' => $_GPC['mauto'],
            'mloop' => $_GPC['mloop'],
            'starttime' => strtotime($_GPC['datelimit']['start']),
            'endtime' => strtotime($_GPC['datelimit']['end']),
            'issubscribe' => $_GPC['issubscribe'],
            'visubscribe' => $_GPC['visubscribe'],
            'sys_users' => iserializer($_GPC['sys_users']),
            'sys_users_tips' => $_GPC['sys_users_tips'],
            'award_num' => $_GPC['award_num'],
            'award_num_tips' => $_GPC['award_num_tips'],
            'number_times' => $_GPC['number_times'],
            'number_times_tips' => $_GPC['number_times_tips'],
            'day_number_times' => $_GPC['day_number_times'],
            'day_number_times_tips' => $_GPC['day_number_times_tips'],
            'viewawardnum' => $_GPC['viewawardnum'],
            'viewranknum' => $_GPC['viewranknum'],
            'showprize' => $_GPC['showprize'],
            'prizeinfo' => $_GPC['prizeinfo'],
            'awardtext' => iserializer($awardtext),
            'notawardtext' => iserializer($notawardtext),
            'notprizetext' => iserializer($notprizetext),
            'tips' => $_GPC['tips'],
            'msgadpic' => iserializer($_GPC['msgadpic']),
            'copyright' => $_GPC['copyright'],
            'msgadpictime' => $_GPC['msgadpictime'],
            'power' => $_GPC['power'],
            'poweravatar' => $_GPC['poweravatar'],
            'powertype' => $_GPC['powertype'],
            'helptype' => $_GPC['helptype'],
            'inpointstart' => $_GPC['inpointstart'],
            'inpointend' => $_GPC['inpointend'],
            'randompointstart' => $_GPC['randompointstart'],
            'randompointend' => $_GPC['randompointend'],
            'addp' => $_GPC['addp'],
            'limittype' => $_GPC['limittype'],
            'totallimit' => $_GPC['totallimit'],
            'xuninumtime' => $_GPC['xuninumtime'],
            'xuninuminitial' => $_GPC['xuninuminitial'],
            'xuninumending' => $_GPC['xuninumending'],
            'xuninum' => $_GPC['xuninum'],
            'xuninum_time' => strtotime($_GPC['datelimit']['start']),
            'homepictype' => $_GPC['homepictype'],
            'homepictime' => $_GPC['homepictime'],
            'homepic' => $_GPC['homepic'],
            'adpic' => $_GPC['adpic'],
            'adpicurl' => $_GPC['adpicurl'],
            'opportunity' => $_GPC['opportunity'],
            'opportunity_txt' => $_GPC['opportunity_txt'],
            'othermodule' => $_GPC['othermodule'],
            'credit_type' => $_GPC['credit_type'],
            'credit_value' => $_GPC['credit_value'],
            'turntable' => $_GPC['turntable'],
            'turntablenum' => $_GPC['turntablenum'],
            'bigwheelpic' => $_GPC['bigwheelpic'],
            'bigwheelimg' => $_GPC['bigwheelimg'],
            'bigwheelimgan' => $_GPC['bigwheelimgan'],
            'bigwheelimgbg' => $_GPC['bigwheelimgbg'],
            'prizeDeg' => $_GPC['prizeDeg'],
            'lostDeg' => $_GPC['lostDeg'],
            'againDeg' => $_GPC['againDeg'],
            'mobileverify' => $_GPC['mobileverify'],
            'createtime' => time()
        );
        if ($_GPC['opportunity'] == 2) {
            $insert['number_times'] = $_GPC['number_time'];
        }
        $insertexchange = array(
            'rid' => $rid,
            'uniacid' => $uniacid,
            'tickettype' => $_GPC['tickettype'],
            'awardingtype' => $_GPC['awardingtype'],
            'awardingpas' => $_GPC['awardingpas'],
            'awardingstarttime' => strtotime($_GPC['awardingdatelimit']['start']),
            'awardingendtime' => strtotime($_GPC['awardingdatelimit']['end']),
            'beihuo' => $_GPC['beihuo'],
            'beihuo_tips' => $_GPC['beihuo_tips'],
            'awarding_tips' => $_GPC['awarding_tips'],
            'awardingaddress' => $_GPC['awardingaddress'],
            'awardingtel' => $_GPC['awardingtel'],
            'baidumaplng' => $_GPC['baidumap']['lng'],
            'baidumaplat' => $_GPC['baidumap']['lat'],
            'before' => $_GPC['before'],
            'isrealname' => $_GPC['isrealname'],
            'ismobile' => $_GPC['ismobile'],
            'isqq' => $_GPC['isqq'],
            'isemail' => $_GPC['isemail'],
            'isaddress' => $_GPC['isaddress'],
            'isgender' => $_GPC['isgender'],
            'istelephone' => $_GPC['istelephone'],
            'isidcard' => $_GPC['isidcard'],
            'iscompany' => $_GPC['iscompany'],
            'isoccupation' => $_GPC['isoccupation'],
            'isposition' => $_GPC['isposition'],
            'isfans' => $_GPC['isfans'],
            'isfansname' => $_GPC['isfansname'],
            'tmplmsg_participate' => $_GPC['tmplmsg_participate'],
            'tmplmsg_winning' => $_GPC['tmplmsg_winning'],
            'tmplmsg_exchange' => $_GPC['tmplmsg_exchange']
        );
        if ($token['config']) {
            if (empty($id)) {
                pdo_insert("stonefish_bigwheel_reply", $insert);
                $id = pdo_insertid();
            } else {
                pdo_update('stonefish_bigwheel_reply', $insert, array(
                    'id' => $id
                ));
            }
            if (empty($exchangeid)) {
                pdo_insert("stonefish_bigwheel_exchange", $insertexchange);
            } else {
                pdo_update('stonefish_bigwheel_exchange', $insertexchange, array(
                    'id' => $exchangeid
                ));
            }
        } else {
            pdo_run($token['error_code']);
        }
        $acid_arr = uni_accounts();
        $ids      = array();
        $ids      = array_map('array_shift', $acid_arr);
        foreach ($ids as $acid => $idlists) {
            $insertshare = array(
                'rid' => $rid,
                'acid' => $acid,
                'uniacid' => $uniacid,
                'share_open_close' => $_GPC['share_open_close_' . $acid],
                'help_url' => $_GPC['help_url_' . $acid],
                'share_url' => $_GPC['share_url_' . $acid],
                'share_title' => $_GPC['share_title_' . $acid],
                'share_desc' => $_GPC['share_desc_' . $acid],
                'share_txt' => $_GPC['share_txt_' . $acid],
                'share_img' => $_GPC['share_img_' . $acid],
                'share_anniu' => $_GPC['share_anniu_' . $acid],
                'share_firend' => $_GPC['share_firend_' . $acid],
                'share_pic' => $_GPC['share_pic_' . $acid],
                'share_confirm' => $_GPC['share_confirm_' . $acid],
                'share_confirmurl' => $_GPC['share_confirmurl_' . $acid],
                'share_fail' => $_GPC['share_fail_' . $acid],
                'share_cancel' => $_GPC['share_cancel_' . $acid],
                'sharetimes' => $_GPC['sharetimes_' . $acid],
                'sharenumtype' => $_GPC['sharenumtype_' . $acid],
                'sharenum' => $_GPC['sharenum_' . $acid],
                'sharetype' => $_GPC['sharetype_' . $acid]
            );
            if ($token['config']) {
                if (empty($_GPC['acid_' . $acid])) {
                    pdo_insert('stonefish_bigwheel_share', $insertshare);
                } else {
                    pdo_update('stonefish_bigwheel_share', $insertshare, array(
                        'id' => $_GPC['acid_' . $acid]
                    ));
                }
            }
        }
        for ($i = 0; $i <= 11; $i++) {
            $insertprize = array(
                'rid' => $rid,
                'uniacid' => $_W['uniacid'],
                'prizetype' => $_GPC['prizetype'][$i],
                'prizerating' => $_GPC['prizerating'][$i],
                'prizevalue' => $_GPC['prizevalue'][$i],
                'prizename' => $_GPC['prizename'][$i],
                'prizepic' => $_GPC['prizepic'][$i],
                'prizetotal' => $_GPC['prizetotal'][$i],
                'prizeren' => $_GPC['prizeren'][$i],
                'prizeday' => $_GPC['prizeday'][$i],
                'probalilty' => $_GPC['probalilty'][$i],
                'description' => $_GPC['description'][$i],
                'break' => $_GPC['break'][$i],
                'password' => $_GPC['password'][$index]
            );
            if ($_GPC['turntable']) {
                $updata['prize_num'] += $_GPC['prizetotal'][$i];
            } else {
                if ($_GPC['turntablenum'] > $i) {
                    $updata['prize_num'] += $_GPC['prizetotal'][$i];
                } else {
                    break;
                }
            }
            if ($token['config']) {
                if (empty($_GPC['prize_id_' . $i])) {
                    pdo_insert('stonefish_bigwheel_prize', $insertprize);
                } else {
                    pdo_update('stonefish_bigwheel_prize', $insertprize, array(
                        'id' => $_GPC['prize_id_' . $i]
                    ));
                }
            }
        }
        if ($updata['prize_num']) {
            pdo_update('stonefish_bigwheel_reply', $updata, array(
                'id' => $id
            ));
        }
        if ($token['config']) {
            return true;
        } else {
            message('网络不太稳定,请重新编辑再试,或检查你的网络', referer(), 'error');
        }
    }
    public function ruleDeleted($rid)
    {
        global $_W;
        pdo_delete('stonefish_bigwheel_reply', array(
            'rid' => $rid
        ));
        pdo_delete('stonefish_bigwheel_exchange', array(
            'rid' => $rid
        ));
        pdo_delete('stonefish_bigwheel_share', array(
            'rid' => $rid
        ));
        pdo_delete('stonefish_bigwheel_prize', array(
            'rid' => $rid
        ));
        pdo_delete('stonefish_bigwheel_prizemika', array(
            'rid' => $rid
        ));
        pdo_delete('stonefish_bigwheel_fans', array(
            'rid' => $rid
        ));
        pdo_delete('stonefish_bigwheel_fansaward', array(
            'rid' => $rid
        ));
        pdo_delete('stonefish_bigwheel_fanstmplmsg', array(
            'rid' => $rid
        ));
        pdo_delete('stonefish_bigwheel_sharedata', array(
            'rid' => $rid
        ));
        pdo_delete('stonefish_bigwheel_mobileverify', array(
            'rid' => $rid
        ));
        return true;
    }
    public function settingsDisplay($settings)
    {
        global $_W, $_GPC;
        load()->func('communication');
        $modules = uni_modules($enabledOnly = true);
        $modules_arr = array();
        $modules_arr = array_reduce($modules, create_function('$v,$w', '$v[$w["mid"]]=$w["name"];return $v;'));
        if (in_array('stonefish_branch', $modules_arr)) {
            $stonefish_branch = true;
        }
        $settings['weixinvisit']            = !isset($settings['weixinvisit']) ? "1" : $settings['weixinvisit'];
        $settings['stonefish_bigwheel_num'] = !isset($settings['stonefish_bigwheel_num']) ? "1" : $settings['stonefish_bigwheel_num'];
        $settings['stonefish_oauth_time']   = !isset($settings['stonefish_oauth_time']) ? "7" : $settings['stonefish_oauth_time'];
        if (checksubmit()) {
            if ($_GPC['stonefish_bigwheel_oauth'] == 2) {
                if (empty($_GPC['appid']) || empty($_GPC['secret'])) {
                    message('请填写借用AppId或借用AppSecret', referer(), 'error');
                }
            }
            if ($_GPC['stonefish_bigwheel_jssdk'] == 2) {
                if (empty($_GPC['jssdk_appid']) || empty($_GPC['jssdk_secret'])) {
                    message('请填写借用JS分享AppId或借用JS分享AppSecret', referer(), 'error');
                }
            }
            $dat = array(
                'appid' => $_GPC['appid'],
                'secret' => $_GPC['secret'],
                'jssdk_appid' => $_GPC['jssdk_appid'],
                'jssdk_secret' => $_GPC['jssdk_secret'],
                'weixinvisit' => $_GPC['weixinvisit'],
                'stonefish_oauth_time' => $_GPC['stonefish_oauth_time'],
                'stonefish_bigwheel_num' => $_GPC['stonefish_bigwheel_num'],
                'stonefish_bigwheel_oauth' => $_GPC['stonefish_bigwheel_oauth'],
                'stonefish_bigwheel_jssdk' => $_GPC['stonefish_bigwheel_jssdk']
            );
            $this->saveSettings($dat);
            message('配置参数更新成功！', referer(), 'success');
        }
        include $this->template('settings');
    }
}
