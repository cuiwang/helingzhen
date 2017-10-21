<?php
defined('IN_IA') or exit('Access Denied');
error_reporting(0);
class Fy_lessonModuleSite extends WeModuleSite
{
    public $_appid = '';
    public $_appsecret = '';
    public $_accountlevel = '';
    public $_uniacid = '';
    public $_fromuser = '';
    public $_nickname = '';
    public $_headimgurl = '';
    public $_auth2_openid = '';
    public $_auth2_nickname = '';
    public $_auth2_headimgurl = '';
    public $table_article = 'fy_lesson_article';
    public $table_blacklist = 'fy_lesson_blacklist';
    public $table_cashlog = 'fy_lesson_cashlog';
    public $table_category = 'fy_lesson_category';
    public $table_evaluate = 'fy_lesson_evaluate';
    public $table_lesson_collect = 'fy_lesson_collect';
    public $table_commission_level = 'fy_lesson_commission_level';
    public $table_commission_log = 'fy_lesson_commission_log';
    public $table_coupon = 'fy_lesson_coupon';
    public $table_lesson_history = 'fy_lesson_history';
    public $table_member = 'fy_lesson_member';
    public $table_member_order = 'fy_lesson_member_order';
    public $table_order = 'fy_lesson_order';
    public $table_lesson_parent = 'fy_lesson_parent';
    public $table_playrecord = 'fy_lesson_playrecord';
    public $table_recommend = 'fy_lesson_recommend';
    public $table_relation = 'fy_lesson_relation';
    public $table_setting = 'fy_lesson_setting';
    public $table_lesson_son = 'fy_lesson_son';
    public $table_syslog = 'fy_lesson_syslog';
    public $table_teacher = 'fy_lesson_teacher';
    public $table_teacher_income = 'fy_lesson_teacher_income';
    public $table_vipcard = 'fy_lesson_vipcard';
    public $table_core_cache = 'core_cache';
    public $table_users = 'users';
    function __construct()
    {
        global $_W, $_GPC;
        $this->_fromuser         = $_W['fans']['from_user'];
        $this->_uniacid          = $_W['uniacid'];
        $account                 = $_W['account'];
        $this->_auth2_openid     = 'auth2_openid_' . $_W['uniacid'];
        $this->_auth2_nickname   = 'auth2_nickname_' . $_W['uniacid'];
        $this->_auth2_headimgurl = 'auth2_headimgurl_' . $_W['uniacid'];
        $this->_appid            = '';
        $this->_appsecret        = '';
        $this->_accountlevel     = $account['level'];
        if ($this->_accountlevel == 4) {
            $this->_appid     = $account['key'];
            $this->_appsecret = $account['secret'];
        }
        $this->checknopay();
    }
    public function doWebCategory()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebRecommend()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebLesson()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebTeacher()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebOrder()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebViporder()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebEvaluate()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebSetting()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebAgent()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebCommission()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebteam()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebComsetting()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebArticle()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebSyslog()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebTeacherclass()
    {
        $this->__web(__FUNCTION__);
    }
    public function doMobileIndex()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileSearch()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileLesson()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileTeacher()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileSelf()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileVip()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileMylesson()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileHistory()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileCollect()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileUpdatecollect()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileConfirm()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileAddtoorder()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileEvaluate()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobilePay()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileCommission()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileQrcode()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileQrcodes()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileteam()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileTeacherlist()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileTeachercenter()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileIncome()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileLessoncash()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileLessoncashlog()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileApplyteacher()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileFollow()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileArticle()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileRecord()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileWritemsg()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileVerify()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileTeacherclass()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function __web($f_name)
    {
        global $_W, $_GPC;
        $uniacid = $_W['uniacid'];
        $op      = $operation = $_GPC['op'] ? $_GPC['op'] : 'display';
        $setting = pdo_fetch("SELECT * FROM " . tablename($this->table_setting) . " WHERE uniacid =:uniacid LIMIT 1", array(
            ':uniacid' => $uniacid
        ));
        include_once 'web/' . strtolower(substr($f_name, 5)) . '.php';
    }
    public function __mobile($f_name)
    {
        global $_W, $_GPC;
        $uniacid = $_W['uniacid'];
        $op      = $operation = $_GPC['op'] ? $_GPC['op'] : 'display';
        include_once 'mobile/' . strtolower(substr($f_name, 8)) . '.php';
    }
    public function payResult($params)
    {
        global $_W, $_GPC;
        $orderid        = $params['tid'];
        $viporder       = pdo_fetch("SELECT * FROM " . tablename($this->table_member_order) . " WHERE id = '{$orderid}'");
        $lessonorder    = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE id = '{$orderid}'");
        $uniacid        = $viporder['uniacid'] ? $viporder['uniacid'] : $lessonorder['uniacid'];
        $customeropenid = $viporder['openid'] ? $viporder['openid'] : $lessonorder['openid'];
        $lessonmember   = pdo_fetch("SELECT * FROM " . tablename($this->table_member) . " WHERE uniacid='{$uniacid}' AND openid='{$customeropenid}'");
        $setting        = pdo_fetch("SELECT istplnotice,cnotice,buysucc,neworder,sale_rank,manageopenid,vip_sale,stock_config FROM " . tablename($this->table_setting) . " WHERE uniacid =:uniacid LIMIT 1", array(
            ':uniacid' => $uniacid
        ));
        $manage         = explode(",", $setting['manageopenid']);
        if (!empty($viporder)) {
            if (($params['result'] == 'success' && $params['from'] == 'notify' || $params['type'] == 'credit') && $viporder['status'] == 0) {
                $data            = array(
                    'status' => $params['result'] == 'success' ? 1 : 0
                );
                $data['paytype'] = $params['type'];
                $data['paytime'] = time();
                if (pdo_update($this->table_member_order, $data, array(
                    'id' => $orderid
                ))) {
                    $updata        = array();
                    $updata['vip'] = 1;
                    if ($lessonmember['vip'] == 0 || time() > $lessonmember['validity']) {
                        $updata['validity'] = time() + $viporder['viptime'] * 86400;
                    } elseif ($lessonmember['vip'] == 1) {
                        $updata['validity'] = $lessonmember['validity'] + $viporder['viptime'] * 86400;
                    }
                    $updata['pastnotice'] = 0;
                    pdo_update($this->table_member, $updata, array(
                        'uniacid' => $uniacid,
                        'openid' => $viporder['openid']
                    ));
                    if ($viporder['member1'] > 0 && $viporder['commission1'] > 0) {
                        $member1   = pdo_fetch("SELECT id,openid,vip FROM " . tablename($this->table_member) . " WHERE uid='{$viporder['member1']}'");
                        $senddata1 = array(
                            'istplnotice' => $setting['istplnotice'],
                            'openid' => $member1['openid'],
                            'cnotice' => $setting['cnotice'],
                            'url' => $_W['siteroot'] . "app/index.php?i={$uniacid}&c=entry&op=commissionlog&do=commission&m=fy_lesson",
                            'first' => "您获得了一笔新的VIP分销佣金。",
                            'keyword1' => $viporder['commission1'] . "元",
                            'keyword2' => date("Y-m-d H:i:s", time()),
                            'remark' => "下级成员：{$lessonmember['nickname']}\r\n消费内容：VIP服务{$viporder['viptime']}天\r\n详情请进入分销中心查看佣金明细。"
                        );
                        if ($setting['sale_rank'] == 2) {
                            if ($member1['vip'] == 1) {
                                $this->changecommisson($viporder['id'], "VIP分销订单", $viporder['member1'], $viporder['commission1'], 1, "一级佣金:订单号" . $viporder['ordersn']);
                                $this->commissionMessage($senddata1, $viporder['acid']);
                            } else {
                                pdo_update($this->table_member_order, array(
                                    'commission1' => 0
                                ), array(
                                    'id' => $orderid
                                ));
                            }
                        } else {
                            $this->changecommisson($viporder['id'], "VIP分销订单", $viporder['member1'], $viporder['commission1'], 1, "一级佣金:订单号" . $viporder['ordersn']);
                            $this->commissionMessage($senddata1, $viporder['acid']);
                        }
                    }
                    if ($viporder['member2'] > 0 && $viporder['commission2'] > 0) {
                        $member2   = pdo_fetch("SELECT id,openid,vip FROM " . tablename($this->table_member) . " WHERE uid='{$viporder['member2']}'");
                        $senddata2 = array(
                            'istplnotice' => $setting['istplnotice'],
                            'openid' => $member2['openid'],
                            'cnotice' => $setting['cnotice'],
                            'url' => $_W['siteroot'] . "app/index.php?i={$uniacid}&c=entry&op=commissionlog&do=commission&m=fy_lesson",
                            'first' => "您获得了一笔新的VIP分销佣金。",
                            'keyword1' => $viporder['commission2'] . "元",
                            'keyword2' => date("Y-m-d H:i:s", time()),
                            'remark' => "下级成员：{$lessonmember['nickname']}\r\n消费内容：VIP服务{$viporder['viptime']}天\r\n详情请进入分销中心查看佣金明细。"
                        );
                        if ($setting['sale_rank'] == 2) {
                            $member2 = pdo_fetch("SELECT id,vip FROM " . tablename($this->table_member) . " WHERE uid='{$viporder['member2']}'");
                            if ($member2['vip'] == 1) {
                                $this->changecommisson($viporder['id'], "VIP分销订单", $viporder['member2'], $viporder['commission2'], 2, "二级佣金:订单号" . $viporder['ordersn']);
                                $this->commissionMessage($senddata2, $viporder['acid']);
                            } else {
                                pdo_update($this->table_member_order, array(
                                    'commission2' => 0
                                ), array(
                                    'id' => $orderid
                                ));
                            }
                        } else {
                            $this->changecommisson($viporder['id'], "VIP分销订单", $viporder['member2'], $viporder['commission2'], 2, "二级佣金:订单号" . $viporder['ordersn']);
                            $this->commissionMessage($senddata2, $viporder['acid']);
                        }
                    }
                    if ($viporder['member3'] > 0 && $viporder['commission3'] > 0) {
                        $member3   = pdo_fetch("SELECT id,openid,vip FROM " . tablename($this->table_member) . " WHERE uid='{$viporder['member3']}'");
                        $senddata3 = array(
                            'istplnotice' => $setting['istplnotice'],
                            'openid' => $member3['openid'],
                            'cnotice' => $setting['cnotice'],
                            'url' => $_W['siteroot'] . "app/index.php?i={$uniacid}&c=entry&op=commissionlog&do=commission&m=fy_lesson",
                            'first' => "您获得了一笔新的VIP分销佣金。",
                            'keyword1' => $viporder['commission3'] . "元",
                            'keyword2' => date("Y-m-d H:i:s", time()),
                            'remark' => "下级成员：{$lessonmember['nickname']}\r\n消费内容：VIP服务{$viporder['viptime']}天\r\n详情请进入分销中心查看佣金明细。"
                        );
                        if ($setting['sale_rank'] == 2) {
                            $member3 = pdo_fetch("SELECT id,vip FROM " . tablename($this->table_member) . " WHERE uid='{$viporder['member3']}'");
                            if ($member3['vip'] == 1) {
                                $this->changecommisson($viporder['id'], "VIP分销订单", $viporder['member3'], $viporder['commission3'], 3, "三级佣金:订单号" . $viporder['ordersn']);
                                $this->commissionMessage($senddata3, $viporder['acid']);
                            } else {
                                pdo_update($this->table_member_order, array(
                                    'commission3' => 0
                                ), array(
                                    'id' => $orderid
                                ));
                            }
                        } else {
                            $this->changecommisson($viporder['id'], "VIP分销订单", $viporder['member3'], $viporder['commission3'], 3, "三级佣金:订单号" . $viporder['ordersn']);
                            $this->commissionMessage($senddata3, $viporder['acid']);
                        }
                    }
                    if ($setting['istplnotice'] == 1) {
                        $sendmessage = array(
                            'touser' => $viporder['openid'],
                            'template_id' => $setting['buysucc'],
                            'url' => $_W['siteroot'] . "app/index.php?i={$uniacid}&c=entry&do=vip&m=fy_lesson",
                            'topcolor' => "#7B68EE",
                            'data' => array(
                                'name' => array(
                                    'value' => "开通/续费VIP服务：[" . urlencode($viporder['viptime']) . "]天",
                                    'color' => "#26b300"
                                ),
                                'remark' => array(
                                    'value' => urlencode("\\n过期时间：" . date('Y-m-d H:i:s', $updata['validity'])),
                                    'color' => "#e40606"
                                )
                            )
                        );
                        $this->send_template_message(urldecode(json_encode($sendmessage)), $viporder['acid']);
                        foreach ($manage as $manageopenid) {
                            $sendneworder = array(
                                'touser' => $manageopenid,
                                'template_id' => $setting['neworder'],
                                'url' => "",
                                'topcolor' => "#7B68EE",
                                'data' => array(
                                    'first' => array(
                                        'value' => urlencode("您有一条新的VIP订单消息"),
                                        'color' => "#428BCA"
                                    ),
                                    'keyword1' => array(
                                        'value' => $viporder['ordersn'],
                                        'color' => "#428BCA"
                                    ),
                                    'keyword2' => array(
                                        'value' => $viporder['vipmoney'],
                                        'color' => "#428BCA"
                                    ),
                                    'keyword3' => array(
                                        'value' => urlencode("VIP会员" . $viporder['viptime'] . "天"),
                                        'color' => "#428BCA"
                                    ),
                                    'remark' => array(
                                        'value' => urlencode("详情请登录网站后台查看！"),
                                        'color' => "#222222"
                                    )
                                )
                            );
                            $this->send_template_message(urldecode(json_encode($sendneworder)), $viporder['acid']);
                        }
                    }
                }
            }
            if ($params['from'] == 'return') {
                if ($lessonmember['vip'] == 0 || time() > $lessonmember['validity']) {
                    $succword = '购买VIP服务成功！';
                } elseif ($lessonmember['vip'] == 1) {
                    $succword = '续费VIP服务成功！';
                }
                message($succword, $this->createMobileUrl('vip'), 'success');
            }
        } elseif (!empty($lessonorder)) {
            if (($params['result'] == 'success' && $params['from'] == 'notify' || $params['type'] == 'credit' || $params['fee'] == 0) && $lessonorder['status'] == 0) {
                $data            = array(
                    'status' => $params['result'] == 'success' ? 1 : 0
                );
                $data['paytype'] = $params['type'];
                $data['paytime'] = time();
                if ($lessonorder['validity'] > 0) {
                    $data['validity'] = time() + 86400 * $lessonorder['validity'];
                }
                if (pdo_update($this->table_order, $data, array(
                    'id' => $orderid
                ))) {
                    $lesson       = pdo_fetch("SELECT stock,buynum FROM " . tablename($this->table_lesson_parent) . " WHERE id='{$lessonorder['lessonid']}'");
                    $lessonupdate = array(
                        'buynum' => $lesson['buynum'] + 1
                    );
                    if ($setting['stock_config'] == 1) {
                        $lessonupdate['stock'] = $lesson['stock'] > 1 ? $lesson['stock'] - 1 : 0;
                    }
                    pdo_update($this->table_lesson_parent, $lessonupdate, array(
                        'id' => $lessonorder['lessonid']
                    ));
                    if ($lessonorder['member1'] > 0 && $lessonorder['commission1'] > 0) {
                        $member1   = pdo_fetch("SELECT id,openid,vip FROM " . tablename($this->table_member) . " WHERE uid='{$lessonorder['member1']}'");
                        $senddata1 = array(
                            'istplnotice' => $setting['istplnotice'],
                            'openid' => $member1['openid'],
                            'cnotice' => $setting['cnotice'],
                            'url' => $_W['siteroot'] . "app/index.php?i={$uniacid}&c=entry&op=commissionlog&do=commission&m=fy_lesson",
                            'first' => "您获得了一笔新的课程分销佣金。",
                            'keyword1' => $lessonorder['commission1'] . "元",
                            'keyword2' => date("Y-m-d H:i:s", time()),
                            'remark' => "下级成员：{$lessonmember['nickname']}\r\n购买课程：{$lessonorder['bookname']}\r\n详情请进入分销中心查看佣金明细。"
                        );
                        if ($setting['sale_rank'] == 2) {
                            if ($member1['vip'] == 1) {
                                $this->changecommisson($lessonorder['id'], $lessonorder['bookname'], $lessonorder['member1'], $lessonorder['commission1'], 1, "一级佣金:订单号" . $lessonorder['ordersn']);
                                $this->commissionMessage($senddata1, $lessonorder['acid']);
                            } else {
                                pdo_update($this->table_order, array(
                                    'commission1' => 0
                                ), array(
                                    'id' => $orderid
                                ));
                            }
                        } else {
                            $this->changecommisson($lessonorder['id'], $lessonorder['bookname'], $lessonorder['member1'], $lessonorder['commission1'], 1, "一级佣金:订单号" . $lessonorder['ordersn']);
                            $this->commissionMessage($senddata1, $lessonorder['acid']);
                        }
                    }
                    if ($lessonorder['member2'] > 0 && $lessonorder['commission2'] > 0) {
                        $member2   = pdo_fetch("SELECT id,openid,vip FROM " . tablename($this->table_member) . " WHERE uid='{$lessonorder['member2']}'");
                        $senddata2 = array(
                            'istplnotice' => $setting['istplnotice'],
                            'openid' => $member2['openid'],
                            'cnotice' => $setting['cnotice'],
                            'url' => $_W['siteroot'] . "app/index.php?i={$uniacid}&c=entry&op=commissionlog&do=commission&m=fy_lesson",
                            'first' => "您获得了一笔新的课程分销佣金。",
                            'keyword1' => $lessonorder['commission2'] . "元",
                            'keyword2' => date("Y-m-d H:i:s", time()),
                            'remark' => "下级成员：{$lessonmember['nickname']}\r\n购买课程：{$lessonorder['bookname']}\r\n详情请进入分销中心查看佣金明细。"
                        );
                        if ($setting['sale_rank'] == 2) {
                            $member2 = pdo_fetch("SELECT id,vip FROM " . tablename($this->table_member) . " WHERE uid='{$lessonorder['member2']}'");
                            if ($member2['vip'] == 1) {
                                $this->changecommisson($lessonorder['id'], $lessonorder['bookname'], $lessonorder['member2'], $lessonorder['commission2'], 2, "二级佣金:订单号" . $lessonorder['ordersn']);
                                $this->commissionMessage($senddata2, $lessonorder['acid']);
                            } else {
                                pdo_update($this->table_order, array(
                                    'commission2' => 0
                                ), array(
                                    'id' => $orderid
                                ));
                            }
                        } else {
                            $this->changecommisson($lessonorder['id'], $lessonorder['bookname'], $lessonorder['member2'], $lessonorder['commission2'], 2, "二级佣金:订单号" . $lessonorder['ordersn']);
                            $this->commissionMessage($senddata2, $lessonorder['acid']);
                        }
                    }
                    if ($lessonorder['member3'] > 0 && $lessonorder['commission3'] > 0) {
                        $member3   = pdo_fetch("SELECT id,openid,vip FROM " . tablename($this->table_member) . " WHERE uid='{$lessonorder['member3']}'");
                        $senddata3 = array(
                            'istplnotice' => $setting['istplnotice'],
                            'openid' => $member3['openid'],
                            'cnotice' => $setting['cnotice'],
                            'url' => $_W['siteroot'] . "app/index.php?i={$uniacid}&c=entry&op=commissionlog&do=commission&m=fy_lesson",
                            'first' => "您获得了一笔新的课程分销佣金。",
                            'keyword1' => $lessonorder['commission3'] . "元",
                            'keyword2' => date("Y-m-d H:i:s", time()),
                            'remark' => "下级成员：{$lessonmember['nickname']}\r\n购买课程：{$lessonorder['bookname']}\r\n详情请进入分销中心查看佣金明细。"
                        );
                        if ($setting['sale_rank'] == 2) {
                            $member3 = pdo_fetch("SELECT id,vip FROM " . tablename($this->table_member) . " WHERE uid='{$lessonorder['member3']}'");
                            if ($member3['vip'] == 1) {
                                $this->changecommisson($lessonorder['id'], $lessonorder['bookname'], $lessonorder['member3'], $lessonorder['commission3'], 3, "三级佣金:订单号" . $lessonorder['ordersn']);
                                $this->commissionMessage($senddata3, $lessonorder['acid']);
                            } else {
                                pdo_update($this->table_order, array(
                                    'commission3' => 0
                                ), array(
                                    'id' => $orderid
                                ));
                            }
                        } else {
                            $this->changecommisson($lessonorder['id'], $lessonorder['bookname'], $lessonorder['member3'], $lessonorder['commission3'], 3, "三级佣金:订单号" . $lessonorder['ordersn']);
                            $this->commissionMessage($senddata3, $lessonorder['acid']);
                        }
                    }
                    if ($lessonorder['price'] > 0 && $lessonorder['teacher_income'] > 0) {
                        $teacher = pdo_fetch("SELECT a.uid,a.openid,a.teacher FROM " . tablename($this->table_teacher) . " a LEFT JOIN " . tablename($this->table_lesson_parent) . " b ON a.id=b.teacherid WHERE b.uniacid='{$uniacid}' AND b.id='{$lessonorder['lessonid']}'");
                        if (!empty($teacher['openid'])) {
                            $teachermember = pdo_fetch("SELECT id,uid,openid,nopay_lesson FROM " . tablename($this->table_member) . " WHERE uniacid='{$_W['uniacid']}' AND openid='{$teacher['openid']}'");
                            $nopay_lesson  = round($lessonorder['price'] * $lessonorder['teacher_income'] * 0.01, 2);
                            pdo_update($this->table_member, array(
                                'nopay_lesson' => $teachermember['nopay_lesson'] + $nopay_lesson
                            ), array(
                                'uniacid' => $uniacid,
                                'openid' => $teacher['openid']
                            ));
                            $incomedata = array(
                                'uniacid' => $uniacid,
                                'uid' => $teacher['uid'],
                                'openid' => $teacher['openid'],
                                'teacher' => $teacher['teacher'],
                                'ordersn' => $lessonorder['ordersn'],
                                'bookname' => $lessonorder['bookname'],
                                'orderprice' => $lessonorder['price'],
                                'teacher_income' => $lessonorder['teacher_income'],
                                'income_amount' => $nopay_lesson,
                                'addtime' => time()
                            );
                            pdo_insert($this->table_teacher_income, $incomedata);
                            $sendteacher = array(
                                'istplnotice' => $setting['istplnotice'],
                                'openid' => $teacher['openid'],
                                'cnotice' => $setting['cnotice'],
                                'url' => $_W['siteroot'] . "app/index.php?i={$uniacid}&c=entry&do=income&m=fy_lesson",
                                'first' => "您获得了一笔新的课程佣金。",
                                'keyword1' => $nopay_lesson . "元",
                                'keyword2' => date("Y-m-d H:i:s", time()),
                                'remark' => "详情请进入讲师中心查看课程收入。"
                            );
                            $this->commissionMessage($sendteacher, $lessonorder['acid']);
                        }
                    }
                    if ($setting['istplnotice'] == 1) {
                        $sendmessage = array(
                            'touser' => $lessonorder['openid'],
                            'template_id' => $setting['buysucc'],
                            'url' => $_W['siteroot'] . "app/index.php?i={$uniacid}&c=entry&status=1&do=mylesson&m=fy_lesson",
                            'topcolor' => "#7B68EE",
                            'data' => array(
                                'name' => array(
                                    'value' => "课程：《" . urlencode($lessonorder['bookname']) . "》",
                                    'color' => "#428BCA"
                                )
                            )
                        );
                        $this->send_template_message(urldecode(json_encode($sendmessage)), $lessonorder['acid']);
                        foreach ($manage as $manageopenid) {
                            $sendneworder = array(
                                'touser' => $manageopenid,
                                'template_id' => $setting['neworder'],
                                'url' => "",
                                'topcolor' => "#7B68EE",
                                'data' => array(
                                    'first' => array(
                                        'value' => urlencode("您有一条新的课程订单消息"),
                                        'color' => "#428BCA"
                                    ),
                                    'keyword1' => array(
                                        'value' => $lessonorder['ordersn'],
                                        'color' => "#428BCA"
                                    ),
                                    'keyword2' => array(
                                        'value' => $lessonorder['price'],
                                        'color' => "#428BCA"
                                    ),
                                    'keyword3' => array(
                                        'value' => urlencode($lessonorder['bookname']),
                                        'color' => "#428BCA"
                                    ),
                                    'remark' => array(
                                        'value' => urlencode("详情请登录网站后台查看！"),
                                        'color' => "#222222"
                                    )
                                )
                            );
                            $this->send_template_message(urldecode(json_encode($sendneworder)), $lessonorder['acid']);
                        }
                    }
                    if ($lessonorder['integral'] > 0) {
                        load()->model('mc');
                        $log = array(
                            '0' => '',
                            '1' => '微课堂订单：' . $lessonorder['ordersn'],
                            '2' => 'fy_lesson',
                            '3' => '',
                            '4' => '',
                            '5' => ''
                        );
                        mc_credit_update($lessonorder['uid'], 'credit1', $lessonorder['integral'], $log);
                    }
                }
            }
            if ($params['from'] == 'return') {
                message("购买课程成功！", $this->createMobileUrl('lesson', array(
                    'id' => $lessonorder['lessonid']
                )), 'success');
            }
        }
    }
    private function commissionMessage($data, $acid)
    {
        if ($data['istplnotice'] == 1) {
            $message = array(
                'touser' => $data['openid'],
                'template_id' => $data['cnotice'],
                'url' => $data['url'],
                'topcolor' => "#e25804",
                'data' => array(
                    'first' => array(
                        'value' => $data['first'],
                        'color' => "#000000"
                    ),
                    'keyword1' => array(
                        'value' => $data['keyword1'],
                        'color' => "#44B549"
                    ),
                    'keyword2' => array(
                        'value' => $data['keyword2'],
                        'color' => "#44B549"
                    ),
                    'remark' => array(
                        'value' => urldecode($data['remark']),
                        'color' => "#000000"
                    )
                )
            );
            $this->send_template_message(urldecode(json_encode($message)), $acid);
        }
    }
    public function oauth2()
    {
        global $_GPC, $_W;
        load()->func('communication');
        $code     = $_GPC['code'];
        $token    = $this->getAuthorizationCode($code);
        $openid   = $token['openid'];
        $userinfo = $this->getUserInfo($openid);
        if ($userinfo['subscribe'] == 0) {
            $userinfo = $this->getUserInfo($openid, $token['access_token']);
        }
        if (empty($userinfo) || !is_array($userinfo) || empty($userinfo['openid']) || empty($userinfo['nickname'])) {
            echo '<h1>获取微信公众号授权失败[无法取得userinfo], 请稍后重试！ 公众平台返回原始数据为: <br />' . $userinfo['meta'] . '<h1>';
            die;
        }
        $this->addfans($userinfo);
        $headimgurl = $userinfo['headimgurl'];
        $nickname   = $userinfo['nickname'];
        setcookie($this->_auth2_headimgurl, $headimgurl);
        setcookie($this->_auth2_nickname, $nickname);
        setcookie($this->_auth2_openid, $openid);
        return $userinfo;
    }
    public function getUserInfo($openid, $ACCESS_TOKEN = '')
    {
        if ($ACCESS_TOKEN == '') {
            load()->classs('weixin.account');
            $accObj       = WeixinAccount::create($_W['account']['acid']);
            $ACCESS_TOKEN = $accObj->fetch_token();
            $url          = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$ACCESS_TOKEN}&openid={$openid}&lang=zh_CN";
        } else {
            $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$ACCESS_TOKEN}&openid={$openid}&lang=zh_CN";
        }
        $json     = ihttp_get($url);
        $userInfo = @json_decode($json['content'], true);
        return $userInfo;
    }
    public function getAuthorizationCode($code)
    {
        $oauth2_code = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->_appid}&secret={$this->_appsecret}&code={$code}&grant_type=authorization_code";
        $content     = ihttp_get($oauth2_code);
        $token       = @json_decode($content['content'], true);
        if (empty($token) || !is_array($token) || empty($token['access_token']) || empty($token['openid'])) {
            echo '<h1>获取微信公众号授权' . $code . '失败[无法取得token以及openid], 请稍后重试！ 公众平台返回原始数据为: <br />' . $content['meta'] . '<h1>';
            die;
        }
        return $token;
    }
    public function getCode($url)
    {
        global $_W;
        $url         = urlencode($url);
        $oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->_appid}&redirect_uri={$url}&response_type=code&scope=snsapi_userinfo&state=0#wechat_redirect";
        header("location:{$oauth2_code}");
    }
    public function addfans($userinfo)
    {
        ignore_user_abort();
        set_time_limit(180);
        global $_W;
        load()->model('mc');
        $setting = uni_setting($_W['uniacid'], array(
            'passport'
        ));
        $fans    = mc_fansinfo($userinfo['openid']);
        if (empty($fans)) {
            $default_group       = pdo_fetchcolumn('SELECT groupid FROM ' . tablename('mc_groups') . ' WHERE uniacid = :uniacid AND isdefault = 1', array(
                ':uniacid' => $_W['uniacid']
            ));
            $rec                 = array();
            $rec['acid']         = $_W['acid'];
            $rec['uniacid']      = $_W['uniacid'];
            $rec['uid']          = 0;
            $rec['openid']       = $userinfo['openid'];
            $rec['nickname']     = $userinfo['nickname'];
            $rec['salt']         = random(8);
            $rec['follow']       = $userinfo['subscribe'] ? $userinfo['subscribe'] : 0;
            $rec['followtime']   = $userinfo['subscribe_time'] ? $userinfo['subscribe_time'] : 0;
            $rec['unfollowtime'] = 0;
            $rec['updatetime']   = TIMESTAMP;
            if (!isset($setting['passport']) || empty($setting['passport']['focusreg'])) {
                $data             = array(
                    'uniacid' => $_W['uniacid'],
                    'email' => md5($userinfo['openid']) . '@012wz.com',
                    'salt' => random(8),
                    'groupid' => $default_group['id'],
                    'nickname' => $userinfo['nickname'],
                    'avatar' => substr($userinfo['headimgurl'], 0, -1) . "132",
                    'gender' => $userinfo['sex'],
                    'createtime' => TIMESTAMP
                );
                $data['password'] = md5($userinfo['openid'] . $data['salt'] . $_W['config']['setting']['authkey']);
                pdo_insert('mc_members', $data);
                $rec['uid'] = pdo_insertid();
            }
            pdo_insert('mc_mapping_fans', $rec);
        }
    }
    public function check_black_list()
    {
        global $_W, $_GPC;
        $uniacid = $this->_uniacid;
        $openid  = $this->_fromuser;
        $item    = pdo_fetch("SELECT * FROM " . tablename($this->table_blacklist) . " WHERE uniacid=:uniacid AND openid=:openid LIMIT 1", array(
            ':uniacid' => $uniacid,
            ':openid' => $openid
        ));
        if (!empty($item)) {
            message('您在黑名单中不能下单，请联系管理员！');
        }
    }
    public function updatelessonmember($openid, $uid)
    {
        ignore_user_abort();
        set_time_limit(180);
        global $_W, $_GPC;
        load()->model('mc');
        $fansinfo     = mc_fansinfo($openid);
        $setting      = pdo_fetch("SELECT id,closespace,istplnotice,pastvip,is_sale,self_sale,level,commission,newjoin,rec_income FROM " . tablename($this->table_setting) . " WHERE uniacid =:uniacid LIMIT 1", array(
            ':uniacid' => $_W['uniacid']
        ));
        $lessonmember = pdo_fetch("SELECT validity,uptime FROM " . tablename($this->table_member) . " WHERE uniacid='{$_W['uniacid']}' AND openid='{$openid}'");
        $referrer     = pdo_fetch("SELECT uid FROM " . tablename($this->table_member) . " WHERE uniacid='{$_W['uniacid']}' AND uid='{$uid}'");
        $uid          = $referrer ? $uid : 0;
        if (empty($lessonmember) && !empty($openid) && $fansinfo['uid'] > 0) {
            $tmpno = '';
            for ($i = 0; $i < 7 - strlen($fansinfo['uid']); $i++) {
                $tmpno .= 0;
            }
            $insertarr = array(
                'uniacid' => $_W['uniacid'],
                'uid' => $fansinfo['uid'],
                'studentno' => $tmpno . $fansinfo['uid'],
                'openid' => $openid,
                'nickname' => $fansinfo['nickname'],
                'parentid' => $uid,
                'status' => 1,
                'uptime' => 0,
                'addtime' => time()
            );
            pdo_insert($this->table_member, $insertarr);
            $id = pdo_insertid();
            if ($setting['is_sale'] == 1 && $setting['istplnotice'] == 1 && $uid > 0) {
                if ($setting['level'] >= 1 && $uid > 0) {
                    $commission = unserialize($setting['commission']);
                    $fans1      = pdo_fetch("SELECT nickname,openid FROM " . tablename('mc_mapping_fans') . "  WHERE uniacid='{$_W['uniacid']}' AND uid='{$uid}'");
                    $recmember1 = pdo_fetch("SELECT openid,nopay_commission,agent_level FROM " . tablename($this->table_member) . " WHERE uniacid='{$_W['uniacid']}' AND uid='{$uid}'");
                    if ($setting['rec_income'] > 0) {
                        pdo_update($this->table_member, array(
                            'nopay_commission' => $recmember1['nopay_commission'] + $setting['rec_income']
                        ), array(
                            'uniacid' => $_W['uniacid'],
                            'uid' => $uid
                        ));
                        $logarr = array(
                            'uniacid' => $_W['uniacid'],
                            'orderid' => $id,
                            'uid' => $uid,
                            'openid' => $fans1['openid'],
                            'nickname' => $fans1['nickname'],
                            'bookname' => "推荐下级成员",
                            'change_num' => $setting['rec_income'],
                            'grade' => 1,
                            'remark' => "直接推荐下级成员加入",
                            'addtime' => time()
                        );
                        pdo_insert($this->table_commission_log, $logarr);
                    }
                    if ($recmember1['agent_level'] > 0) {
                        $level1 = pdo_fetch("SELECT * FROM " . tablename($this->table_commission_level) . " WHERE uniacid='{$_W['uniacid']}' AND id='{$recmember1['agent_level']}'");
                    }
                    if ($setting['self_sale'] == 1) {
                        if (!empty($level1)) {
                            $commission1 = $level1['commission2'];
                        } else {
                            $commission1 = $commission['commission2'];
                        }
                    } else {
                        if (!empty($level1)) {
                            $commission1 = $level1['commission1'];
                        } else {
                            $commission1 = $commission['commission1'];
                        }
                    }
                    $send1 = array(
                        'touser' => $fans1['openid'],
                        'template_id' => $setting['newjoin'],
                        'url' => $_W['siteroot'] . 'app/' . $this->createMobileUrl('team', array(
                            'level' => 1
                        )),
                        'topcolor' => "#e25804",
                        'data' => array(
                            'first' => array(
                                'value' => urldecode("恭喜您有新的下级成员加入"),
                                'color' => "#000000"
                            ),
                            'keyword1' => array(
                                'value' => urldecode($fansinfo['nickname']),
                                'color' => "#44B549"
                            ),
                            'keyword2' => array(
                                'value' => urldecode("一级"),
                                'color' => "#44B549"
                            ),
                            'keyword3' => array(
                                'value' => urldecode("下级购买金额的") . $commission1 . "%",
                                'color' => "#44B549"
                            ),
                            'remark' => array(
                                'value' => urldecode("您的下级成员每次购买课程时，您将获得课程售价{$commission1}%的佣金~"),
                                'color' => "#000000"
                            )
                        )
                    );
                    if ($commission1 > 0) {
                        $this->send_template_message(urldecode(json_encode($send1)));
                    }
                    $commember2 = $this->getParentid($uid);
                    if ($setting['level'] >= 2 && $commember2 > 0) {
                        $fans2      = pdo_fetch("SELECT openid FROM " . tablename('mc_mapping_fans') . "  WHERE uniacid='{$_W['uniacid']}' AND uid='{$commember2}'");
                        $recmember2 = pdo_fetch("SELECT agent_level FROM " . tablename($this->table_member) . " WHERE uniacid='{$_W['uniacid']}' AND uid='{$commember2}'");
                        if ($recmember2['agent_level'] > 0) {
                            $level2 = pdo_fetch("SELECT * FROM " . tablename($this->table_commission_level) . " WHERE uniacid='{$_W['uniacid']}' AND id='{$recmember2['agent_level']}'");
                        }
                        if ($setting['self_sale'] == 1) {
                            if (!empty($level2)) {
                                $commission2 = $level2['commission3'];
                            } else {
                                $commission2 = $commission['commission3'];
                            }
                        } else {
                            if (!empty($level2)) {
                                $commission2 = $level2['commission2'];
                            } else {
                                $commission2 = $commission['commission2'];
                            }
                        }
                        $send2 = array(
                            'touser' => $fans2['openid'],
                            'template_id' => $setting['newjoin'],
                            'url' => $_W['siteroot'] . 'app/' . $this->createMobileUrl('team', array(
                                'level' => 1
                            )),
                            'topcolor' => "#e25804",
                            'data' => array(
                                'first' => array(
                                    'value' => urldecode("恭喜您有新的下级成员加入"),
                                    'color' => "#000000"
                                ),
                                'keyword1' => array(
                                    'value' => urldecode($fansinfo['nickname']),
                                    'color' => "#44B549"
                                ),
                                'keyword2' => array(
                                    'value' => urldecode("二级"),
                                    'color' => "#44B549"
                                ),
                                'keyword3' => array(
                                    'value' => urldecode("下级购买金额的") . $commission2 . "%",
                                    'color' => "#44B549"
                                ),
                                'remark' => array(
                                    'value' => urldecode("您的下级成员每次购买课程时，您将获得课程售价{$commission2}%的佣金~"),
                                    'color' => "#000000"
                                )
                            )
                        );
                        if ($commission2 > 0) {
                            $this->send_template_message(urldecode(json_encode($send2)));
                        }
                        $commember3 = $this->getParentid($commember2);
                        if ($setting['level'] == 3 && $commember3 > 0) {
                            $fans3      = pdo_fetch("SELECT openid FROM " . tablename('mc_mapping_fans') . "  WHERE uniacid='{$_W['uniacid']}' AND uid='{$commember3}'");
                            $recmember3 = pdo_fetch("SELECT agent_level FROM " . tablename($this->table_member) . " WHERE uniacid='{$_W['uniacid']}' AND uid='{$commember3}'");
                            if ($recmember3['agent_level'] > 0) {
                                $level3 = pdo_fetch("SELECT * FROM " . tablename($this->table_commission_level) . " WHERE uniacid='{$_W['uniacid']}' AND id='{$recmember3['agent_level']}'");
                            }
                            if ($setting['self_sale'] == 1) {
                                $commission3 = 0;
                            } else {
                                if (!empty($level3)) {
                                    $commission3 = $level3['commission3'];
                                } else {
                                    $commission3 = $commission['commission3'];
                                }
                            }
                            $send3 = array(
                                'touser' => $fans3['openid'],
                                'template_id' => $setting['newjoin'],
                                'url' => $_W['siteroot'] . 'app/' . $this->createMobileUrl('team', array(
                                    'level' => 1
                                )),
                                'topcolor' => "#e25804",
                                'data' => array(
                                    'first' => array(
                                        'value' => urldecode("恭喜您有新的下级成员加入"),
                                        'color' => "#000000"
                                    ),
                                    'keyword1' => array(
                                        'value' => urldecode($fansinfo['nickname']),
                                        'color' => "#44B549"
                                    ),
                                    'keyword2' => array(
                                        'value' => urldecode("三级"),
                                        'color' => "#44B549"
                                    ),
                                    'keyword3' => array(
                                        'value' => urldecode("下级购买金额的") . $commission3 . "%",
                                        'color' => "#44B549"
                                    ),
                                    'remark' => array(
                                        'value' => urldecode("您的下级成员每次购买课程时，您将获得课程售价{$commission3}%的佣金~"),
                                        'color' => "#000000"
                                    )
                                )
                            );
                            if ($commission3 > 0) {
                                $this->send_template_message(urldecode(json_encode($send3)));
                            }
                        }
                    }
                }
            }
        } elseif (!empty($lessonmember)) {
            if (time() > $lessonmember['validity']) {
                pdo_update($this->table_member, array(
                    'vip' => 0
                ), array(
                    'uniacid' => $_W['uniacid'],
                    'openid' => $openid
                ));
            }
            $lmember = pdo_fetch("SELECT uid,openid,vip,pastnotice,validity FROM " . tablename($this->table_member) . " WHERE uniacid='{$_W['uniacid']}' AND openid='{$openid}'");
            if ($lmember['vip'] == 1 && $lmember['pastnotice'] == 0 && $lmember['validity'] < time() + 7 * 86400) {
                pdo_update($this->table_member, array(
                    'pastnotice' => 1
                ), array(
                    'uid' => $lmember['uid']
                ));
                $sendmessage = array(
                    'touser' => $lmember['openid'],
                    'template_id' => $setting['pastvip'],
                    'url' => $_W['siteroot'] . 'app/' . $this->createMobileUrl('vip'),
                    'topcolor' => "#7B68EE",
                    'data' => array(
                        'first' => array(
                            'value' => "尊敬的会员，您的VIP服务即将到期，为了不影响您的课程学习，请您及时续费。",
                            'color' => "#2392EA"
                        ),
                        'name' => array(
                            'value' => "VIP会员服务",
                            'color' => "#18A15F"
                        ),
                        'expDate' => array(
                            'value' => date('Y-m-d H:i', $lmember['validity']),
                            'color' => "#D24238"
                        )
                    )
                );
                $this->send_template_message(urldecode(json_encode($sendmessage)));
            }
        }
    }
    public function checknopay()
    {
        global $_W, $_GPC;
        $uniacid = $_W['uniacid'];
        $setting = pdo_fetch("SELECT id,closespace,closelast FROM " . tablename($this->table_setting) . " WHERE uniacid=:uniacid LIMIT 1", array(
            ':uniacid' => $uniacid
        ));
        if (time() > $setting['closelast'] + $setting['closespace'] * 60 && $setting['closespace'] != 0) {
            $time        = time() - $setting['closespace'] * 60;
            $nopay_order = pdo_fetchall("SELECT id FROM " . tablename($this->table_order) . " WHERE uniacid='{$uniacid}' AND status=0 AND addtime<'{$time}'");
            foreach ($nopay_order as $item) {
                pdo_update($this->table_order, array(
                    'status' => '-1'
                ), array(
                    'id' => $item['id']
                ));
            }
            pdo_update($this->table_setting, array(
                'closelast' => time()
            ), array(
                'id' => $setting['id']
            ));
        }
    }
    public function getParentid($uid)
    {
        global $_W, $_GPC;
        $uniacid = $_W['uniacid'];
        $parent  = pdo_fetch("SELECT parentid FROM " . tablename($this->table_member) . " WHERE uniacid='{$uniacid}' AND uid='{$uid}'");
        if (!empty($parent)) {
            return $parent['parentid'];
        } else {
            return '0';
        }
    }
    private function changecommisson($orderid, $bookname, $uid, $change_num, $grade, $remark)
    {
        global $_W;
        $uniacid      = $_W['uniacid'];
        $lessonmember = pdo_fetch("SELECT id,uid,openid,nickname,pay_commission,nopay_commission,agent_level,status FROM " . tablename($this->table_member) . " WHERE uniacid='{$_W['uniacid']}' AND uid='{$uid}'");
        if ($lessonmember['status'] == 1) {
            $memupdate        = array();
            $total_commission = $lessonmember['pay_commission'] + $lessonmember['nopay_commission'] + $change_num;
            $levellist        = pdo_fetchall("SELECT * FROM " . tablename($this->table_commission_level) . " WHERE uniacid='{$uniacid}' ORDER BY id ASC ");
            if (!empty($levellist)) {
                if ($lessonmember['agent_level'] == 0) {
                    $setting    = pdo_fetch("SELECT commission FROM " . tablename($this->table_setting) . " WHERE uniacid=:uniacid LIMIT 1", array(
                        ':uniacid' => $uniacid
                    ));
                    $commission = unserialize($setting['commission']);
                    if ($commission['updatemoney'] > 0 && $total_commission >= $commission['updatemoney']) {
                        foreach ($levellist as $key => $value) {
                            if ($value['updatemoney'] > 0 && $total_commission >= $value['updatemoney']) {
                                $memupdate['agent_level'] = intval($levellist[$key + 1]['id']);
                            } else {
                                break;
                            }
                        }
                        if (empty($memupdate['agent_level'])) {
                            $memupdate['agent_level'] = $levellist[0]['id'];
                        }
                    }
                } else {
                    $level = pdo_fetch("SELECT * FROM " . tablename($this->table_commission_level) . " WHERE uniacid='{$uniacid}' AND id='{$lessonmember['agent_level']}'");
                    if ($level['updatemoney'] > 0 && $total_commission >= $level['updatemoney']) {
                        foreach ($levellist as $key => $value) {
                            if ($value['id'] == $level['id']) {
                                $levelkey = $key;
                            }
                            if ($value['updatemoney'] > 0 && $total_commission >= $value['updatemoney']) {
                                $memupdate['agent_level'] = intval($levellist[$key + 1]['id']);
                            } else {
                                break;
                            }
                        }
                        if ($memupdate['agent_level'] == $level['id']) {
                            $memupdate['agent_level'] = $levellist[$levelkey + 1]['id'];
                        }
                    }
                }
            }
            $memupdate['nopay_commission'] = $lessonmember['nopay_commission'] + $change_num;
            pdo_update($this->table_member, $memupdate, array(
                'id' => $lessonmember['id']
            ));
            $member = pdo_fetch("SELECT nickname FROM " . tablename('mc_members') . " WHERE uniacid='{$_W['uniacid']}' AND uid='{$uid}'");
            $logarr = array(
                'uniacid' => $_W['uniacid'],
                'orderid' => $orderid,
                'uid' => $uid,
                'openid' => $lessonmember['openid'],
                'nickname' => $member['nickname'],
                'bookname' => $bookname,
                'change_num' => $change_num,
                'grade' => $grade,
                'remark' => $remark,
                'addtime' => time()
            );
            pdo_insert($this->table_commission_log, $logarr);
        } else {
            if ($grade == 1) {
                $updatearr['commission1'] = 0;
            } elseif ($grade == 2) {
                $updatearr['commission2'] = 0;
            } elseif ($grade == 3) {
                $updatearr['commission3'] = 0;
            }
            pdo_update($this->table_order, $updatearr, array(
                'id' => $orderid
            ));
        }
    }
    private function send_template_message($messageDatas, $acid = null)
    {
        global $_W, $_GPC;
        if (empty($acid)) {
            $acid = $_W['account']['acid'];
        }
        load()->classs('weixin.account');
        $accObj       = WeixinAccount::create($acid);
        $access_token = $accObj->fetch_token();
        $urls         = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $access_token;
        $ress         = $this->http_request($urls, $messageDatas);
        return json_decode($ress, true);
    }
    public function addSysLog($admin_uid, $admin_username, $log_type, $function, $content)
    {
        global $_W;
        if (getenv("HTTP_CLIENT_IP")) {
            $ip = getenv("HTTP_CLIENT_IP");
        } elseif (getenv("HTTP_X_FORWARDED_FOR")) {
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        } else {
            if (getenv("REMOTE_ADDR")) {
                $ip = getenv("REMOTE_ADDR");
            } else {
                $ip = $_SERVER["REMOTE_ADDR"];
            }
        }
        $log_data = array(
            'uniacid' => $_W['uniacid'],
            'admin_uid' => $admin_uid,
            'admin_username' => $admin_username,
            'log_type' => $log_type,
            'function' => $function,
            'content' => $content,
            'ip' => $ip,
            'addtime' => time()
        );
        return pdo_insert($this->table_syslog, $log_data);
    }
    private function http_request($url, $messageDatas = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($messageDatas)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $messageDatas);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
    public function saveImage($path, $file_dir, $image_name)
    {
        if (!preg_match('/\/([^\/]+\.[a-z]{3,4})$/i', $path)) {
            die('获取用户头像失败，请检查系统是否正常获取粉丝头像');
        }
        $ch = curl_init();
        $fp = fopen($file_dir . $image_name, 'wb');
        curl_setopt($ch, CURLOPT_URL, $path);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
    }
    public function downLoadImg($imgPath, $savePath)
    {
        ob_start();
        readfile($imgPath);
        $img = ob_get_contents();
        ob_end_clean();
        $fp2 = @fopen($savePath, 'a');
        fwrite($fp2, $img);
        fclose($fp2);
    }
    private function object_array($array)
    {
        if (is_object($array)) {
            $array = (array) $array;
        }
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $array[$key] = $this->object_array($value);
            }
        }
        return $array;
    }
    private function uploadpic($upfile)
    {
        global $_W, $_GPC;
        $tmppath = "../attachment/images/";
        $u       = $tmppath . $_W['uniacid'] . "/";
        $y       = $u . date("Y", time()) . "/";
        $m       = $y . date("m", time()) . "/";
        $this->checkdir($tmppath);
        $this->checkdir($u);
        $this->checkdir($y);
        $this->checkdir($m);
        $name     = $upfile["name"];
        $type     = $upfile["type"];
        $size     = $upfile["size"];
        $tmp_name = $upfile["tmp_name"];
        if (!empty($name) && in_array($type, array(
            "image/jpeg",
            "image/png",
            "image/gif"
        ))) {
            $error = $upfile["error"];
            $tmp   = explode(".", $name);
            $paths = $m . random(30) . "." . $tmp[1];
            move_uploaded_file($tmp_name, $paths);
            $name = str_replace("../attachment/", "", $paths);
            $this->resize($paths, $paths, "500", "500", "90");
        }
        return $name;
    }
    private function checkdir($path)
    {
        if (!file_exists($path)) {
            mkdir($path, 511);
        }
    }
    public function img_water_mark($srcImg, $waterImg, $savepath = null, $savename = null, $x, $y, $alpha = 100)
    {
        $temp     = pathinfo($srcImg);
        $name     = $temp['basename'];
        $path     = $temp['dirname'];
        $exte     = $temp['extension'];
        $savename = $savename ? $savename : $name;
        $savepath = $savepath ? $savepath : $path;
        $savefile = $savepath . '/' . $savename;
        $srcinfo  = @getimagesize($srcImg);
        if (!$srcinfo) {
            return -1;
        }
        $waterinfo = @getimagesize($waterImg);
        if (!$waterinfo) {
            return -2;
        }
        $srcImgObj = $this->image_create_from_ext($srcImg);
        if (!$srcImgObj) {
            return -3;
        }
        $waterImgObj = $this->image_create_from_ext($waterImg);
        if (!$waterImgObj) {
            return -4;
        }
        imagecopymerge($srcImgObj, $waterImgObj, $x, $y, 0, 0, $waterinfo[0], $waterinfo[1], $alpha);
        switch ($srcinfo[2]) {
            case 1:
                imagegif($srcImgObj, $savefile);
                break;
            case 2:
                imagejpeg($srcImgObj, $savefile);
                break;
            case 3:
                imagepng($srcImgObj, $savefile);
                break;
            default:
                return -5;
        }
        imagedestroy($srcImgObj);
        imagedestroy($waterImgObj);
        return $savefile;
    }
    public function image_create_from_ext($imgfile)
    {
        $info = getimagesize($imgfile);
        $im   = null;
        switch ($info[2]) {
            case 1:
                $im = imagecreatefromgif($imgfile);
                break;
            case 2:
                $im = imagecreatefromjpeg($imgfile);
                break;
            case 3:
                $im = imagecreatefrompng($imgfile);
                break;
        }
        return $im;
    }
    function resize($srcImage, $toFile, $maxWidth = 100, $maxHeight = 100, $imgQuality = 100)
    {
        list($width, $height, $type, $attr) = getimagesize($srcImage);
        if ($width < $maxWidth || $height < $maxHeight) {
            return;
        }
        switch ($type) {
            case 1:
                $img = imagecreatefromgif($srcImage);
                break;
            case 2:
                $img = imagecreatefromjpeg($srcImage);
                break;
            case 3:
                $img = imagecreatefrompng($srcImage);
                break;
        }
        $scale = min($maxWidth / $width, $maxHeight / $height);
        if ($scale < 1) {
            $newWidth  = floor($scale * $width);
            $newHeight = floor($scale * $height);
            $newImg    = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($newImg, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            $newName = "";
            $toFile  = preg_replace("/(.gif|.jpg|.jpeg|.png)/i", "", $toFile);
            switch ($type) {
                case 1:
                    if (imagegif($newImg, "{$toFile}{$newName}.gif", $imgQuality)) {
                        return "{$newName}.gif";
                    }
                    break;
                case 2:
                    if (imagejpeg($newImg, "{$toFile}{$newName}.jpg", $imgQuality)) {
                        return "{$newName}.jpg";
                    }
                    break;
                case 3:
                    if (imagepng($newImg, "{$toFile}{$newName}.png", $imgQuality)) {
                        return "{$newName}.png";
                    }
                    break;
                default:
                    if (imagejpeg($newImg, "{$toFile}{$newName}.jpg", $imgQuality)) {
                        return "{$newName}.jpg";
                    }
                    break;
            }
            imagedestroy($newImg);
        }
        imagedestroy($img);
        return false;
    }
    private function companyPay($post, $fans)
    {
        global $_W, $_GPC;
        $uniacid                  = $_W['uniacid'];
        $account                  = $_W['account'];
        $setting                  = pdo_fetch("SELECT mchid,mchkey,serverIp FROM " . tablename($this->table_setting) . " WHERE uniacid='{$uniacid}'");
        $url                      = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
        $pars                     = array();
        $pars['mch_appid']        = $account['key'];
        $pars['mchid']            = $setting['mchid'];
        $pars['nonce_str']        = random(32);
        $pars['partner_trade_no'] = $setting['mchid'] . date('Ymd') . rand(1000000000, 9999999999.0);
        $pars['openid']           = $fans['openid'];
        $pars['check_name']       = 'NO_CHECK';
        $pars['re_user_name']     = $fans['nickname'];
        $pars['amount']           = $post['total_amount'] * 100;
        $pars['desc']             = $post['desc'];
        $pars['spbill_create_ip'] = $setting['serverIp'] ? $setting['serverIp'] : $_SERVER["SERVER_ADDR"];
        ksort($pars, SORT_STRING);
        $string1 = '';
        foreach ($pars as $k => $v) {
            $string1 .= "{$k}={$v}&";
        }
        $string1 .= "key={$setting['mchkey']}";
        $pars['sign'] = strtoupper(md5($string1));
        $xml          = '<xml>';
        foreach ($pars as $k => $v) {
            $xml .= "<{$k}>{$v}</{$k}>";
        }
        $xml .= '</xml>';
        $extras                    = array();
        $extras['CURLOPT_CAINFO']  = MODULE_ROOT . '/cert/rootca' . $uniacid . '.pem';
        $extras['CURLOPT_SSLCERT'] = MODULE_ROOT . '/cert/apiclient_cert' . $uniacid . '.pem';
        $extras['CURLOPT_SSLKEY']  = MODULE_ROOT . '/cert/apiclient_key' . $uniacid . '.pem';
        load()->func('communication');
        $resp   = ihttp_request($url, $xml, $extras);
        $tmp    = str_replace("<![CDATA[", "", $resp['content']);
        $tmp    = str_replace("]]>", "", $tmp);
        $tmp    = simplexml_load_string($tmp);
        $result = json_decode(json_encode($tmp), TRUE);
        return $result;
    }
    protected function exportexcel($data = array(), $title = array(), $filename = 'report')
    {
        header("Content-type:application/octet-stream");
        header("Accept-Ranges:bytes");
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=" . $filename . "-" . date('Ymd', time()) . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        if (!empty($title)) {
            foreach ($title as $k => $v) {
                $title[$k] = iconv("UTF-8", "GB2312", $v);
            }
            $title = implode("\t", $title);
            echo "{$title}\n";
        }
        if (!empty($data)) {
            $totalprice = 0;
            foreach ($data as $key => $val) {
                foreach ($val as $ck => $cv) {
                    $data[$key][$ck] = iconv("UTF-8", "GB2312", $cv);
                }
                $data[$key] = implode("\t", $data[$key]);
                $totalprice += $val['amount'];
            }
            echo implode("\n", $data);
        }
    }
    public function inputExcel($filename, $tmp_file)
    {
        if (!empty($filename)) {
            $file_types = explode(".", $filename);
            $file_type  = $file_types[count($file_types) - 1];
            if (strtolower($file_type) != "xls") {
                message("请上传后缀是xls的文件", "", "error");
            }
            $savePath = "../attachment/excel/";
            if (!file_exists($savePath)) {
                mkdir($savePath, 511);
            }
            $savePath = $savePath . "fy_lesson/";
            if (!file_exists($savePath)) {
                mkdir($savePath, 511);
            }
            $str         = date('Ymdhis');
            $file_name   = $str . random(8) . "." . $file_type;
            $newfilename = $savePath . $file_name;
            if (!copy($tmp_file, $newfilename)) {
                message("上传文件失败，请稍候重试", "", "error");
            }
            require_once '../framework/library/phpexcel/PHPExcel/IOFactory.php';
            $reader        = PHPExcel_IOFactory::createReader('Excel5');
            $PHPExcel      = $reader->load($newfilename);
            $sheet         = $PHPExcel->getSheet(0);
            $highestRow    = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();
            $arr           = array(
                1 => 'A',
                2 => 'B',
                3 => 'C',
                4 => 'D',
                5 => 'E',
                6 => 'F',
                7 => 'G',
                8 => 'H',
                9 => 'I',
                10 => 'J',
                11 => 'K',
                12 => 'L',
                13 => 'M',
                14 => 'N',
                15 => 'O',
                16 => 'P',
                17 => 'Q',
                18 => 'R',
                19 => 'S',
                20 => 'T',
                21 => 'U',
                22 => 'V',
                23 => 'W',
                24 => 'X',
                25 => 'Y',
                26 => 'Z'
            );
            for ($row = 2; $row <= $highestRow; $row++) {
                for ($column = 0; $arr[$column] != $highestColumn; $column++) {
                    $val          = $sheet->getCellByColumnAndRow($column, $row)->getValue();
                    $list[$row][] = $val;
                }
            }
            return array(
                'list' => $list,
                'newfilename' => $newfilename
            );
        } else {
            message("请选择要上传的文件", "", "error");
        }
    }
    public function upfile($file, $newfile)
    {
        global $_W;
        if (!empty($file['name'])) {
            $file_types = explode(".", $file['name']);
            $file_type  = $file_types[count($file_types) - 1];
            if (strtolower($file_type) != "pem") {
                message("请上传后缀是pem的文件", "", "error");
            }
            $dirpath = dirname(__FILE__) . "/cert/";
            if (!file_exists($dirpath)) {
                mkdir($dirpath, 511);
            }
            $file_name = $dirpath . $newfile . $_W['uniacid'] . "." . $file_type;
            if (!copy($file['tmp_name'], $file_name)) {
                message("上传文件失败，请稍候重试", "", "error");
            }
        }
    }
    private function privateDownloadUrl($accessKey, $secretKey, $baseUrl, $expires = 3600)
    {
        $deadline = time() + $expires;
        $pos      = strpos($baseUrl, '?');
        if ($pos !== false) {
            $baseUrl .= '&e=';
        } else {
            $baseUrl .= '?e=';
        }
        $baseUrl .= $deadline;
        $hmac    = hash_hmac('sha1', $baseUrl, $secretKey, true);
        $find    = array(
            '+',
            '/'
        );
        $replace = array(
            '-',
            '_'
        );
        $hmac    = str_replace($find, $replace, base64_encode($hmac));
        $token   = $accessKey . ':' . $hmac;
        return "{$baseUrl}&token={$token}";
    }
    function two_array_unique($list)
    {
        foreach ($list as $v) {
            $v      = join(',', $v);
            $temp[] = $v;
        }
        $temp = array_unique($temp);
        foreach ($temp as $k => $v) {
            $temp[$k] = explode(',', $v);
        }
        return $temp;
    }
    public function getVipStatusName($vip)
    {
        return $vip == 1 ? 'VIP' : '普通';
    }
    public function getAgentStatusName($status)
    {
        return $status == 1 ? '正常' : '冻结';
    }
    public function getAgentLevelName($levelId)
    {
        global $_W;
        $level = pdo_fetch("SELECT levelname FROM " . tablename($this->table_commission_level) . " WHERE uniacid=:uniacid AND id=:id", array(
            ':uniacid' => $_W['uniacid'],
            ':id' => $levelId
        ));
        return $level ? $level['levelname'] : '默认级别';
    }
    public function getFansCount($uid)
    {
        global $_W;
        return pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_member) . " WHERE uniacid=:uniacid AND parentid=:parentid", array(
            ':uniacid' => $_W['uniacid'],
            ':parentid' => $uid
        ));
    }
    public function getWechatPayNo($tid)
    {
        return pdo_fetch("SELECT uniontid,tag FROM " . tablename('core_paylog') . " WHERE tid=:tid", array(
            ':tid' => $tid
        ));
    }
    public function getVipCardPwd($id)
    {
        $vipCard = pdo_fetch("SELECT password FROM " . tablename($this->table_vipcard) . " WHERE id=:id", array(
            ':id' => $id
        ));
        return $vipCard['password'];
    }
    public function getCouponPwd($id)
    {
        $coupon = pdo_fetch("SELECT password FROM " . tablename($this->table_coupon) . " WHERE card_id=:card_id", array(
            ':card_id' => $id
        ));
        return $coupon['password'];
    }
    public function resultJson($data)
    {
        echo json_encode($data);
        die;
    }
    function mpagination($tcount, $pindex, $psize = 15, $url = '', $context = array('before' => 1, 'after' => 1, 'ajaxcallback' => ''))
    {
        global $_W;
        $pdata           = array(
            'tcount' => 0,
            'tpage' => 0,
            'cindex' => 0,
            'findex' => 0,
            'pindex' => 0,
            'nindex' => 0,
            'lindex' => 0,
            'options' => ''
        );
        $pdata['tcount'] = $tcount;
        $pdata['tpage']  = ceil($tcount / $psize);
        if ($pdata['tpage'] <= 1) {
            return '';
        }
        $cindex          = $pindex;
        $cindex          = min($cindex, $pdata['tpage']);
        $cindex          = max($cindex, 1);
        $pdata['cindex'] = $cindex;
        $pdata['findex'] = 1;
        $pdata['pindex'] = $cindex > 1 ? $cindex - 1 : 1;
        $pdata['nindex'] = $cindex < $pdata['tpage'] ? $cindex + 1 : $pdata['tpage'];
        $pdata['lindex'] = $pdata['tpage'];
        if ($context['isajax']) {
            if (!$url) {
                $url = $_W['script_name'] . '?' . http_build_query($_GET);
            }
            $pdata['faa'] = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['findex'] . '\', ' . $context['ajaxcallback'] . ')"';
            $pdata['paa'] = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['pindex'] . '\', ' . $context['ajaxcallback'] . ')"';
            $pdata['naa'] = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['nindex'] . '\', ' . $context['ajaxcallback'] . ')"';
            $pdata['laa'] = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['lindex'] . '\', ' . $context['ajaxcallback'] . ')"';
        } else {
            if ($url) {
                $pdata['faa'] = 'href="?' . str_replace('*', $pdata['findex'], $url) . '"';
                $pdata['paa'] = 'href="?' . str_replace('*', $pdata['pindex'], $url) . '"';
                $pdata['naa'] = 'href="?' . str_replace('*', $pdata['nindex'], $url) . '"';
                $pdata['laa'] = 'href="?' . str_replace('*', $pdata['lindex'], $url) . '"';
            } else {
                $_GET['page'] = $pdata['findex'];
                $pdata['faa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
                $_GET['page'] = $pdata['pindex'];
                $pdata['paa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
                $_GET['page'] = $pdata['nindex'];
                $pdata['naa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
                $_GET['page'] = $pdata['lindex'];
                $pdata['laa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
            }
        }
        $html = '<div class="c_page">';
        $html .= "<a {$pdata['paa']} class=\"pre\">上一页</a>";
        $html .= "<span class=\"num\">{$cindex}/{$pdata['tpage']}</span>";
        $html .= "<a {$pdata['naa']} class=\"next\">下一页</a>";
        $html .= '</div>';
        return $html;
    }
}
