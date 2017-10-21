<?php
/**
 * 微课堂模块订阅器
 */
defined('IN_IA') or exit('Access Denied');

class Fy_lessonModuleReceiver extends WeModuleReceiver {
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


	public function receive() {
		ignore_user_abort();
        set_time_limit(180);

		global $_W;

		$type = $this->message['type'];
		$openid = $this->message['from'];
		$eventkey = str_replace("qrscene_", "", $this->message['eventkey']);
		$uniacid = $_W['uniacid'];

		load()->model('mc');
		$fansinfo = mc_fansinfo($openid);

		if(in_array($type, array('subscribe', 'qr'))){
			$setting = pdo_fetch("SELECT id,closespace,istplnotice,pastvip,is_sale,self_sale,level,commission,newjoin,rec_income FROM " . tablename($this->table_setting) . " WHERE uniacid =:uniacid LIMIT 1", array(':uniacid' => $uniacid));

			/* 检查用户是否存在 */
			$lessonmember = pdo_fetch("SELECT validity,uptime FROM " . tablename($this->table_member) . " WHERE uniacid=:uniacid AND openid=:openid", array(':uniacid'=>$uniacid, ':openid'=>$openid));

			if (empty($lessonmember) && !empty($openid) && $fansinfo['uid'] > 0) {
				$tmpno = '';
				for ($i = 0; $i < 7 - strlen($fansinfo['uid']); $i++) {
					$tmpno .= 0;
				}
				$insertarr = array(
					'uniacid' => $uniacid,
					'uid' => $fansinfo['uid'],
					'studentno' => $tmpno . $fansinfo['uid'],
					'openid' => $openid,
					'nickname' => $fansinfo['nickname'],
					'parentid' => $eventkey,
					'status' => 1,
					'uptime' => 0,
					'addtime' => time(),
				);
				pdo_insert($this->table_member, $insertarr);
				$id = pdo_insertid();

				if ($setting['is_sale'] == 1 && $setting['istplnotice'] == 1 && $eventkey > 0) {

					if ($setting['level'] >= 1 && $eventkey > 0) {/* 一级推荐人 */
						$commission = unserialize($setting['commission']);
						$fans1 = pdo_fetch("SELECT nickname,openid FROM " . tablename('mc_mapping_fans') . "  WHERE uniacid=:uniacid AND uid=:uid", array(':uniacid'=>$uniacid, ':uid'=>$eventkey));

						$recmember1 = pdo_fetch("SELECT openid,nopay_commission,agent_level FROM " . tablename($this->table_member) . " WHERE uniacid=:uniacid AND uid=:uid", array(':uniacid'=>$uniacid, ':uid'=>$eventkey));
						if ($setting['rec_income'] > 0) {
							pdo_update($this->table_member, array('nopay_commission' => $recmember1['nopay_commission'] + $setting['rec_income']), array('uniacid' => $uniacid, 'uid' => $eventkey));
							$logarr = array(
								'uniacid' => $uniacid,
								'orderid' => $id,
								'uid' => $eventkey,
								'openid' => $fans1['openid'],
								'nickname' => $fans1['nickname'],
								'bookname' => "推荐下级成员",
								'change_num' => $setting['rec_income'],
								'grade' => 1,
								'remark' => "直接推荐下级成员加入",
								'addtime' => time(),
							);
							pdo_insert($this->table_commission_log, $logarr);
						}

						if ($recmember1['agent_level'] > 0) {
							$level1 = pdo_fetch("SELECT * FROM " . tablename($this->table_commission_level) . " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid, ':id'=>$recmember1['agent_level']));
						}
						if ($setting['self_sale'] == 1) { /* 开启分销内购，一级分销人拿二级佣金 */
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
							'url' => $_W['siteroot'] . 'app/' . $this->createMobileUrl('team', array('level' => 1)),
							'topcolor' => "#e25804",
							'data' => array(
								'first' => array(
									'value' => urldecode("恭喜您有新的下级成员加入"),
									'color' => "#000000",
								),
								'keyword1' => array(
									'value' => urldecode($fansinfo['nickname']),
									'color' => "#44B549",
								),
								'keyword2' => array(
									'value' => urldecode("一级"),
									'color' => "#44B549",
								),
								'keyword3' => array(
									'value' => urldecode("下级购买金额的") . $commission1 . "%",
									'color' => "#44B549",
								),
								'remark' => array(
									'value' => urldecode("您的下级成员每次购买课程时，您将获得课程售价{$commission1}%的佣金~"),
									'color' => "#000000",
								),
							)
						);
						if ($commission1 > 0) {
							$this->send_template_message(urldecode(json_encode($send1)));
						}

						$commember2 = $this->getParentid($eventkey);
						if ($setting['level'] >= 2 && $commember2 > 0) {/* 二级推荐人 */
							$fans2 = pdo_fetch("SELECT openid FROM " . tablename('mc_mapping_fans') . "  WHERE uniacid=:uniacid AND uid=:uid", array(':uniacid'=>$uniacid, ':uid'=>$commember2));

							$recmember2 = pdo_fetch("SELECT agent_level FROM " . tablename($this->table_member) . " WHERE uniacid=:uniacid AND uid=:uid", array(':uniacid'=>$uniacid, ':uid'=>$commember2));
							if ($recmember2['agent_level'] > 0) {
								$level2 = pdo_fetch("SELECT * FROM " . tablename($this->table_commission_level) . " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid,':id'=>$recmember2['agent_level']));
							}


							if ($setting['self_sale'] == 1) { /* 开启分销内购，二级级分销人拿三级佣金 */
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
								'url' => $_W['siteroot'] . 'app/' . $this->createMobileUrl('team', array('level' => 1)),
								'topcolor' => "#e25804",
								'data' => array(
									'first' => array(
										'value' => urldecode("恭喜您有新的下级成员加入"),
										'color' => "#000000",
									),
									'keyword1' => array(
										'value' => urldecode($fansinfo['nickname']),
										'color' => "#44B549",
									),
									'keyword2' => array(
										'value' => urldecode("二级"),
										'color' => "#44B549",
									),
									'keyword3' => array(
										'value' => urldecode("下级购买金额的") . $commission2 . "%",
										'color' => "#44B549",
									),
									'remark' => array(
										'value' => urldecode("您的下级成员每次购买课程时，您将获得课程售价{$commission2}%的佣金~"),
										'color' => "#000000",
									),
								)
							);
							if ($commission2 > 0) {
								$this->send_template_message(urldecode(json_encode($send2)));
							}

							$commember3 = $this->getParentid($commember2);
							if ($setting['level'] == 3 && $commember3 > 0) {/* 三级推荐人 */
								$fans3 = pdo_fetch("SELECT openid FROM " . tablename('mc_mapping_fans') . "  WHERE uniacid=:uniacid AND uid=:uid", array(':uniacid'=>$uniacid, ':uid'=>$commember3));

								$recmember3 = pdo_fetch("SELECT agent_level FROM " . tablename($this->table_member) . " WHERE uniacid=:uniacid AND uid=:uid", array(':uniacid'=>$uniacid, ':uid'=>$commember3));
								if ($recmember3['agent_level'] > 0) {
									$level3 = pdo_fetch("SELECT * FROM " . tablename($this->table_commission_level) . " WHERE uniacid=:uniacid AND id=:id", array(':uniacid'=>$uniacid, ':id'=>$recmember3['agent_level']));
								}

								if ($setting['self_sale'] == 1) { /* 开启分销内购，三级级分销人没有佣金 */
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
									'url' => $_W['siteroot'] . 'app/' . $this->createMobileUrl('team', array('level' => 1)),
									'topcolor' => "#e25804",
									'data' => array(
										'first' => array(
											'value' => urldecode("恭喜您有新的下级成员加入"),
											'color' => "#000000",
										),
										'keyword1' => array(
											'value' => urldecode($fansinfo['nickname']),
											'color' => "#44B549",
										),
										'keyword2' => array(
											'value' => urldecode("三级"),
											'color' => "#44B549",
										),
										'keyword3' => array(
											'value' => urldecode("下级购买金额的") . $commission3 . "%",
											'color' => "#44B549",
										),
										'remark' => array(
											'value' => urldecode("您的下级成员每次购买课程时，您将获得课程售价{$commission3}%的佣金~"),
											'color' => "#000000",
										),
									)
								);
								if ($commission3 > 0) {
									$this->send_template_message(urldecode(json_encode($send3)));
								}
							}
						}
					}
				}
			}
		}
	}

	/* 发送模版消息 */
    private function send_template_message($messageDatas, $acid = null) {
        global $_W, $_GPC;
        if (empty($acid)) {
            $acid = $_W['account']['acid'];
        }

        load()->classs('weixin.account');
        $accObj = WeixinAccount::create($acid);
        $access_token = $accObj->fetch_token();

        $urls = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $access_token;
        $ress = $this->http_request($urls, $messageDatas);

        return json_decode($ress, true);
    }

	/* https请求（支持GET和POST） */
    private function http_request($url, $messageDatas = null) {
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

	/* 查询上级推荐人 */
    public function getParentid($uid) {
        global $_W, $_GPC;
        $uniacid = $_W['uniacid'];

        $parent = pdo_fetch("SELECT parentid FROM " . tablename($this->table_member) . " WHERE uniacid='{$uniacid}' AND uid='{$uid}'");

        if (!empty($parent)) {
            return $parent['parentid'];
        } else {
            return '0';
        }
    }


}
