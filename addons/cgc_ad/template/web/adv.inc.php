<?php
global $_W, $_GPC;
$weid = $_W['uniacid'];
checklogin();
load()->func('tpl');
$op = empty ($_GPC['op']) ? 'display' : $_GPC['op'];

$config = $this->settings;
if ($op == 'display') {

	$quan = pdo_fetchall("SELECT id,aname FROM " . tablename('cgc_ad_quan') . " WHERE weid=" . $weid . " AND del=0");
	$condition = "";
	if (!empty ($_GPC['quan_id'])) {
		$condition .= " AND a.quan_id= " . intval($_GPC['quan_id']);
	}

	if (!empty ($_GPC['keyword'])) {
		$condition .= " AND a.content LIKE '%{$_GPC['keyword']}%' ";
	}

	if (!empty ($_GPC['type'])) {

		if ($_GPC['type'] == 1) {

			$condition .= " AND a.rob_start_time>" . time();

		}

		if ($_GPC['type'] == 2) {

			$condition .= " AND a.rob_start_time<" . time() . " AND a.rob_end_time is NULL ";

		}

		if ($_GPC['type'] == 3) {

			$condition .= " AND a.rob_end_time >0 ";

		}

	}

	if (!empty ($_GPC['status'])) {
		$condition .= " AND a.status = " . $_GPC['status'] . " ";
	}

	if (!empty ($_GPC['nickname'])) {

		$condition .= " AND a.nickname like '%{$_GPC['nickname']}%'";

	}

	$pindex = max(1, intval($_GPC['page']));

	$psize = 20;

	$list = pdo_fetchall("SELECT a.*,b.type FROM " . tablename('cgc_ad_adv') . " as a left join " . tablename('cgc_ad_member') . " as b on a.mid=b.id  WHERE a.weid=" . $weid . " $condition  AND a.del=0 ORDER BY a.create_time DESC LIMIT " . ($pindex -1) * $psize . ",{$psize}");

	$total = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('cgc_ad_adv') . " a WHERE weid=" . $weid . $condition . " AND del=0 ");

	$pager = pagination($total, $pindex, $psize);

} else
	if ($op == 'delete_all') {
		if (empty ($_GPC['quan_id'])) {
			message("请选择圈子以后在删除", referer(), "success");
		}
		pdo_query("delete FROM " . tablename('cgc_ad_adv') . "   WHERE weid=" . $weid . " and quan_id={$_GPC['quan_id']}");
		message("删除成功", referer(), "success");
	} else
		if ($op == 'fabu') {

			$mid = $_GPC['mid'];

			$pay = $_GPC['pay'];

			$num = $_GPC['num'];

			if (empty ($mid)) {

				echo json_encode(array (
					'status' => 2,
					'log' => '请选择发布者'
				));

				exit;

			} else {

				$fabu_xinxi = pdo_fetch("SELECT a.*,b.percent,b.total_min,b.total_max,b.avg_min,b.top_line,b.begin_time,b.over_time FROM " . tablename('cgc_ad_member') . " as a left join " . tablename('cgc_ad_quan') . " as b on a.quan_id=b.id WHERE a.weid=" . $weid . " AND a.id=" . $mid . " AND a.status=1 ");

				if (empty ($fabu_xinxi)) {

					echo json_encode(array (
						'status' => 2,
						'log' => '该发布者不存在或者已经被禁用，请重新发布！'
					));

					exit;

				}

				if ($_GPC['model'] == 2) {
					$fabu_xinxi['avg_min'] = $fabu_xinxi['avg_min2'];
					$fabu_xinxi['total_min'] = $fabu_xinxi['total_min2'];
					$fabu_xinxi['total_max'] = $fabu_xinxi['total_max2'];
				}

				if ($_GPC['model'] == 4) {
				  $fabu_xinxi['avg_min'] = $fabu_xinxi['avg_min4'];
				  $fabu_xinxi['total_min'] = $fabu_xinxi['total_min4'];
				  $fabu_xinxi['total_max'] = $fabu_xinxi['total_max4'];
				}

				if ($fabu_xinxi['type'] != 3 && $fabu_xinxi['type'] != 4) {
					echo json_encode(array (
						'status' => 2,
						'log' => '发布者不是商家用户或官方用户，无法发布广告！'
					));

					exit;

				}

				if (empty ($pay)) {

					echo json_encode(array (
						'status' => 2,
						'log' => '广告金额不能为空!'
					));

					exit;

				}

				if (empty ($num) || $num < 1) {

					echo json_encode(array (
						'status' => 2,
						'log' => '广告份数不能为空!'
					));

					exit;

				}

				if ($pay < $fabu_xinxi['total_min']) {

					echo json_encode(array (
						'status' => 2,
						'log' => "广告金额不能低于" . $fabu_xinxi['total_min'] . '元'
					));

					exit;

				}

				if ($pay > $fabu_xinxi['total_max']) {
					echo json_encode(array (
						'status' => 2,
						'log' => '广告金额不能超过' . $fabu_xinxi['total_max'] . '元'
					));
					exit;

				}

				if ($num > intval($pay / $fabu_xinxi['avg_min'])) {

					echo json_encode(array (
						'status' => 2,
						'log' => $pay . '元最多可分' . intval($pay / $fabu_xinxi['avg_min']) . '份'
					));

					exit;

				}

				$least_amount = max($fabu_xinxi['total_min'], $num * $fabu_xinxi['avg_min']);

				if ($pay < $least_amount) {

					echo json_encode(array (
						'status' => 2,
						'log' => '红包总金额不能低于' . $least_amount . '元'
					));

					exit;

				}

				$fee = intval($pay * $fabu_xinxi['percent']) / 100;

				$the_pay = intval(($pay + $fee) * 100) / 100;

				echo json_encode(array (
					'status' => 1,
					'log' => '确认收到商家' . $the_pay . '元(其中' . $fee . '元为服务费)的广告费了吗？'
				));

				exit;

			}

		} else
			if ($op == 'fabu2') {

				$id = $_GPC['id'];

				$mid = $_GPC['mid'];

				$pay = $_GPC['pay'];

				$num = $_GPC['num'];

				if (empty ($id)) {

					echo json_encode(array (
						'status' => 2,
						'log' => '操作非法，请刷新页面重试一次！'
					));

					exit;

				}

				if (empty ($mid)) {

					echo json_encode(array (
						'status' => 2,
						'log' => '请选择发布者'
					));

					exit;

				} else {

					$adv = pdo_fetch("SELECT a.*,b.type,b.nicheng,b.thumb,a.headimgurl,a.nickname,d.aname FROM " . tablename('cgc_ad_adv') . " as a left join " . tablename('cgc_ad_member') . " as b on a.mid=b.id  left join " . tablename('cgc_ad_quan') . " as d on a.quan_id=d.id WHERE a.weid=" . $weid . " AND a.id=" . $id);

					if (empty ($adv) && empty ($adv['status'])) {

						echo json_encode(array (
							'status' => 2,
							'log' => '该广告已经不存在或者仍未付款！'
						));

						exit;

					}

					if ($adv['mid'] != $mid) {

						echo json_encode(array (
							'status' => 2,
							'log' => '操作非法，请刷新页面重试一次！'
						));

						exit;

					}

					if ($adv['total_amount'] == $pay && $adv['total_num'] == $num) {

						echo json_encode(array (
							'status' => 1,
							'log' => ''
						));

						exit;

					} else {

						if ($adv['rob_users'] > 0) {

							echo json_encode(array (
								'status' => 3,
								'log' => '该广告已有用户领取过，已经不可以修改广告金额和广告份数！'
							));

							exit;

						}

						$fabu_xinxi = pdo_fetch("SELECT a.*,b.percent,b.total_min,b.total_max,b.avg_min,b.top_line,b.begin_time,b.over_time FROM " . tablename('cgc_ad_member') . " as a left join " . tablename('cgc_ad_quan') . " as b on a.quan_id=b.id WHERE a.weid=" . $weid . " AND a.id=" . $mid . " AND a.status=1 ");

						if (empty ($fabu_xinxi)) {

							echo json_encode(array (
								'status' => 2,
								'log' => '该发布者不存在或者已经被禁用，请重新发布！'
							));

							exit;

						}

						if ($fabu_xinxi['type'] != 3 && $fabu_xinxi['type'] != 4) {

							echo json_encode(array (
								'status' => 2,
								'log' => '发布者不是商家用户或官方用户，无法发布广告！'
							));

							exit;

						}

						if (empty ($pay)) {

							echo json_encode(array (
								'status' => 2,
								'log' => '广告金额不能为空!'
							));

							exit;

						}

						if (empty ($num) || $num < 1) {

							echo json_encode(array (
								'status' => 2,
								'log' => '广告份数不能为空!'
							));

							exit;

						}

						if ($pay < $fabu_xinxi['total_min']) {

							echo json_encode(array (
								'status' => 2,
								'log' => "广告金额不能低于" . $fabu_xinxi['total_min'] . '元'
							));

							exit;

						}

						if ($pay > $fabu_xinxi['total_max']) {

							echo json_encode(array (
								'status' => 2,
								'log' => '广告金额不能超过' . $fabu_xinxi['total_max'] . '元'
							));

							exit;

						}

						if ($num > intval($pay / $fabu_xinxi['avg_min'])) {

							echo json_encode(array (
								'status' => 2,
								'log' => $pay . '元最多可分' . intval($pay / $fabu_xinxi['avg_min']) . '份'
							));

							exit;

						}

						$least_amount = max($fabu_xinxi['total_min'], $num * $fabu_xinxi['avg_min']);

						if ($pay < $least_amount) {

							echo json_encode(array (
								'status' => 2,
								'log' => '红包总金额不能低于' . $least_amount . '元'
							));

							exit;

						}

						$fee = intval($pay * $fabu_xinxi['percent']) / 100;

						$the_pay = intval(($pay + $fee) * 100) / 100;

						if ($adv['total_amount'] != $pay) {

							echo json_encode(array (
								'status' => 1,
								'log' => '确认将商家' . $adv['total_amount'] . '元的广告费改为' . $pay . '元了吗？'
							));

							exit;

						}

						if ($adv['total_num'] != $num) {

							echo json_encode(array (
								'status' => 1,
								'log' => '确认将商家' . $adv['total_num'] . '份的广告份额改为' . $num . '份了吗？'
							));

							exit;

						}

					}

				}

			} else
				if ($op == 'jiqi') {

					$id = $_GPC['id'];

					if (empty ($id)) {

						echo json_encode(array (
							'status' => 2,
							'log' => '操作非法，请刷新页面重试一次！'
						));

						exit;

					}

					$adv = pdo_fetch("SELECT a.*,b.type,b.nicheng,b.thumb,a.headimgurl,a.nickname,d.aname FROM " . tablename('cgc_ad_adv') . " as a left join " . tablename('cgc_ad_member') . " as b on a.mid=b.id left join " . tablename('cgc_ad_quan') . " as d on a.quan_id=d.id WHERE a.weid=" . $weid . " AND a.id=" . $id);

					if (empty ($adv) && empty ($adv['status'])) {

						echo json_encode(array (
							'status' => 2,
							'log' => '该广告已经不存在或者仍未付款！'
						));

						exit;

					}

					if ($adv['model'] == 4) {
						echo json_encode(array (
							'status' => 2,
							'log' => '任务模式无法生成机器人！'
						));

						exit;
					}

					if ($adv['status'] == 3) {

						echo json_encode(array (
							'status' => 2,
							'log' => '该广告仍处于待审核！'
						));

						exit;

					}

					if ($adv['status'] == 4 || $adv['status'] == 5) {

						echo json_encode(array (
							'status' => 2,
							'log' => '该广告审核不通过！'
						));

						exit;

					}

					if ($adv['rob_start_time'] > time()) {

						echo json_encode(array (
							'status' => 2,
							'log' => '还没有到可以开抢的时间'
						));

						exit;

					}

					$jiqi = pdo_fetchall("SELECT b.id FROM " . tablename('cgc_ad_red') . " as a left join " . tablename('cgc_ad_member') . " as b on a.mid=b.id WHERE a.weid=" . $weid . " AND a.advid=" . $id . " AND b.type=2 ");

					$condition = '';

					if (!empty ($jiqi)) {

						$condition .= " AND id NOT IN ( ";

						foreach ($jiqi as $key => $value) {

							$condition .= $value['id'] . ",";

						}

						$condition = substr($condition, 0, -1);

						$condition .= ")";

					}

					$num = intval($_GPC['num']);

					$jiqi_z = pdo_fetchall("SELECT * FROM " . tablename('cgc_ad_member') . " WHERE weid=" . $weid . " AND quan_id=" . $adv['quan_id'] . " AND status=1 AND type=2" . $condition . " order by rand() LIMIT " . $num);

					if (empty ($jiqi_z)) {

						echo json_encode(array (
							'status' => 2,
							'log' => '没有可用的机器人或者所有机器人已经抢过该广告'
						));

						exit;

					} else {

						$rob_all = 0;

						foreach ($jiqi_z as $key => $value) {

							if ($adv['rob_users'] >= $adv['total_num']) {

								echo json_encode(array (
									'status' => 2,
									'log' => '手慢了，钱被抢光啦！'
								));

								exit;

							} else {

								$rob_index = $adv['rob_users'];

								$rob_plan = explode(',', $adv['rob_plan']);

								$rob_money = $rob_plan[$rob_index];

								$rob_money = $rob_money / 100;

								if ($rob_money == 0) {

									$rob_money = '0.01';

								}

								if (empty ($rob_money) || $rob_money <= 0 || $rob_money > $adv['total_amount']) {

									echo json_encode(array (
										'status' => 2,
										'log' => '哎呀~没抢到'
									));

									exit;

								} else {

									$data = array (

										'weid' => $weid,

										'quan_id' => $adv['quan_id'],

										'advid' => $id,

										'mid' => $value['id'],

										'money' => $rob_money,

										'create_time' => TIMESTAMP,

										
									);

									pdo_insert("cgc_ad_red", $data);

									pdo_update("cgc_ad_member", array (
										'rob' => $value['rob'] + $rob_money,
										'credit' => $value['credit'] + $rob_money
									), array (
										'id' => $value['id']
									));

									$data2 = array (

										'weid' => $weid,

										'quan_id' => $adv['quan_id'],

										'advid' => $id,

										'mid' => $value['id'],

										'createtime' => TIMESTAMP,

										
									);

									pdo_insert("cgc_ad_pv", $data2);

									if (($adv['rob_users'] + 1) == $adv['total_num']) {

										pdo_update("cgc_ad_adv", array (
											'rob_amount' => ($adv['rob_amount'] + $rob_money),
											'rob_users' => ($adv['rob_users'] + 1),
											'views' => ($adv['views'] + 1),
											'rob_end_time' => TIMESTAMP,
											'rob_status' => 1
										), array (
											'id' => $adv['id']
										));

										$max_id = pdo_fetchcolumn("SELECT max(money) FROM " . tablename('cgc_ad_red') . " WHERE weid=" . $weid . " AND quan_id=" . $adv['quan_id'] . " AND advid=" . $id);

										pdo_update("cgc_ad_red", array (
											'is_luck' => 1
										), array (
											'money' => $max_id
										));

										$min_id = pdo_fetchcolumn("SELECT min(money) FROM " . tablename('cgc_ad_red') . " WHERE weid=" . $weid . " AND quan_id=" . $adv['quan_id'] . " AND advid=" . $id);

										pdo_update("cgc_ad_red", array (
											'is_shit' => 1
										), array (
											'money' => $min_id
										));

									} else {

										pdo_update("cgc_ad_adv", array (
											'rob_amount' => ($adv['rob_amount'] + $rob_money),
											'rob_users' => ($adv['rob_users'] + 1),
											'views' => ($adv['views'] + 1)
										), array (
											'id' => $adv['id']
										));

									}

									$adv['rob_users'] += 1;

									$adv['rob_amount'] += $rob_money;

									$rob_all += $rob_money;

								}

							}

						}

						echo json_encode(array (
							'status' => 1,
							'log' => '一共抢了' . $num . "次，共抢了" . $rob_all . "元!"
						));

						exit;

					}

				} else
					if ($op == 'post') {
                   
						$id = $_GPC['id'];

						if (!empty ($id)) {

							$adv = pdo_fetch("SELECT a.*,b.type,b.nicheng,b.thumb,b.headimgurl,b.nickname,d.aname FROM " . tablename('cgc_ad_adv') . " as a left join " . tablename('cgc_ad_member') . " as b on a.mid=b.id left join " . tablename('cgc_ad_quan') . " as d on a.quan_id=d.id WHERE a.weid=" . $weid . " AND a.id=" . $id);

							if (empty ($adv) && empty ($adv['status'])) {

								message("该广告已经不存在或者仍未付款！");

							}

							$jiqi_z = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('cgc_ad_member') . " WHERE weid=" . $weid . " AND quan_id=" . $adv['quan_id'] . " AND status=1 AND type=2");

							$jiqi = pdo_fetchcolumn("SELECT count(a.id) FROM " . tablename('cgc_ad_red') . " as a left join " . tablename('cgc_ad_member') . " as b on a.mid=b.id WHERE a.weid=" . $weid . " AND a.advid=" . $id . " AND b.type=2 ");

							//兼容旧数据
							if (empty ($adv['model'])) {
								$adv['model'] = '1';
							}
						}
   
						$quan = pdo_fetchall("SELECT * FROM " . tablename('cgc_ad_quan') . " WHERE weid=" . $weid . " AND status=1 AND del=0 ");

						$sj = pdo_fetchall("SELECT a.*,b.aname FROM " . tablename('cgc_ad_member') . " as a left join " . tablename('cgc_ad_quan') . " as b on a.quan_id=b.id WHERE a.weid=" . $weid . " AND ( a.type=3 OR a.type=4 ) AND a.status=1 ");

						if (checksubmit()) {
							$total_num=$_GPC['total_num'];
							$total_amount=$_GPC['total_amount'];
							//微信
							if ($adv['type'] == 1) {
								$mid = $_GPC['mid'];
								$fabu_xinxi = pdo_fetch("SELECT a.*,b.percent,b.total_min4,b.total_max4,b.avg_min4,b.total_min,b.total_max,b.avg_min,b.total_min2,b.total_max2,b.avg_min2,b.top_line,b.begin_time,b.over_time FROM " . tablename('cgc_ad_member') . " as a left join " . tablename('cgc_ad_quan') . " as b on a.quan_id=b.id WHERE a.weid=" . $weid . " AND a.id=" . $mid . " AND a.status=1 ");

								if (empty ($fabu_xinxi)) {
									message("该发布者不存在或者已经被禁用，请重新发布！");
								}

								if ($_GPC['model'] == 2) {
									$fabu_xinxi['avg_min'] = $fabu_xinxi['avg_min2'];
									$fabu_xinxi['total_min'] = $fabu_xinxi['total_min2'];
									$fabu_xinxi['total_max'] = $fabu_xinxi['total_max2'];
								}

								if ($_GPC['model'] == 4) {
									$fabu_xinxi['avg_min'] = $fabu_xinxi['avg_min4'];
									$fabu_xinxi['total_min'] = $fabu_xinxi['total_min4'];
									$fabu_xinxi['total_max'] = $fabu_xinxi['total_max4'];
								}
								
								
								// 处理图片

								$images = $_GPC['thumb'];

								// 从微信服务器下载用户上传的图片

								if (!empty ($images) && count($images) > 0) {

									load()->func('file');

									$down_images = array ();

									foreach ($images as $k => $row) {

										if (!preg_match("/^(http):/", $row) && $config['is_qniu'] == 1 && empty($_W['setting']['remote']['type'])) {
                                          
											$ret = $this->VP_IMAGE_SAVE($row);

										} else {

											$ret['image'] = $row;

										}

										if (!empty ($ret['error'])) {

											message('上传图片失败:' . $ret['error']);

										}

										$down_images[] = $ret['image'];

									}

									$images = iserializer($down_images);

								}
								



								if($_GPC['allocation_way']=='1'){
							      $plan = $this->red_average_plan($total_amount, $total_num);
								}
								else{
								  $plan = $this->red_plan($total_amount, $total_num, $fabu_xinxi['avg_min']);
								}
								
								
								
							
								$plan = implode(',', $plan);

								$data = array (
									'status' => $_GPC['status'],
									'views' => $_GPC['views'],
									'content' => $_GPC['content'],
									'rob_amount' => $_GPC['rob_amount'],
									'rob_users' => $_GPC['rob_users'],
									'top_level' => $_GPC['top_level'],
									'rob_plan' => $plan,
									'model' => $_GPC['model'],
                                    'allocation_way' => $_GPC['allocation_way'],
                                    'job_submission_time'=>$_GPC['job_submission_time'],
									'kouling' => $_GPC['kouling'],
									'total_amount' => $_GPC['total_amount'],
									'total_num' => $_GPC['total_num'],
									'is_kouling' => $_GPC['is_kouling'],
									'time_limit' => $_GPC['time_limit'],
									'time_limit_start' => $_GPC['time_limit_start'],
									'time_limit_end' => $_GPC['time_limit_end'],
									'hx_status' => $_GPC['hx_status'],
									'hx_pass' => $_GPC['hx_pass'],
								     'wx_cardid' => $_GPC['wx_cardid'],
								     'images' => $images,	
								     'link' => $_GPC['link'],
								     'qr_code' => $_GPC['qr_code'],
								     'telphone' => $_GPC['telphone'],		
								);

								pdo_update("cgc_ad_adv", $data, array (
									'id' => $adv['id']
								));
								$fabu_xinxi['quan_id'] = $adv['quan_id'];

							} else {
                           
								$mid = $_GPC['mid'];

								if (empty ($mid)) {

									message("请选择发布者！");

								}

							
								$_GPC['job_submission_time'] = '';
								
							

								$fabu_xinxi = pdo_fetch("SELECT a.*,b.percent,b.total_min4,b.total_max4,b.avg_min4,b.total_min,b.total_max,b.avg_min,b.total_min2,b.total_max2,b.avg_min2,b.top_line,b.begin_time,b.over_time FROM " . tablename('cgc_ad_member') . " as a left join " . tablename('cgc_ad_quan') . " as b on a.quan_id=b.id WHERE a.weid=" . $weid . " AND a.id=" . $mid . " AND a.status=1 ");

								if (empty ($fabu_xinxi)) {
									message("该发布者不存在或者已经被禁用，请重新发布！");
								}

								if ($_GPC['model'] == 2) {
									$fabu_xinxi['avg_min'] = $fabu_xinxi['avg_min2'];
									$fabu_xinxi['total_min'] = $fabu_xinxi['total_min2'];
									$fabu_xinxi['total_max'] = $fabu_xinxi['total_max2'];
								}

								if ($_GPC['model'] == 4) {
									$fabu_xinxi['avg_min'] = $fabu_xinxi['avg_min4'];
									$fabu_xinxi['total_min'] = $fabu_xinxi['total_min4'];
									$fabu_xinxi['total_max'] = $fabu_xinxi['total_max4'];
								}

								if ($fabu_xinxi['type'] != 3 && $fabu_xinxi['type'] != 4) {

									message("发布者不是商家用户或官方用户，无法发布广告！");

								}

								if (empty ($_GPC['title'])) {

									message("广告标题不能同时为空！");

								}

								if (empty ($_GPC['total_amount'])) {

									message("广告金额不能为空!");

								}

								if (empty ($_GPC['total_num']) || $_GPC['total_num'] < 1) {

									message("广告份数不能为空!");

								}

								// link格式化

								$link = $_GPC['link'];

								if (!empty ($link)) {

									if (!preg_match("/^(http|ftp):/", $link)) {

										$link = 'http://' . $link;

									}

								}

								if ($this->text_len($link) > 500) {

									message("链接内容超长啦！");

								}

								$total_amount = intval($_GPC['total_amount'] * 100) / 100;

								if ($total_amount < $fabu_xinxi['total_min']) {

									message("广告金额不能低于" . $fabu_xinxi['total_min'] . '元');

								}

								if ($total_amount > $fabu_xinxi['total_max']) {

									message('广告金额不能超过' . $fabu_xinxi['total_max'] . '元');

								}

								if ($_GPC['total_num'] > intval($total_amount / $fabu_xinxi['avg_min'])) {

									message($total_amount . '元最多可分' . intval($total_amount / $fabu_xinxi['avg_min']) . '份');

								}

								$least_amount = max($fabu_xinxi['total_min'], $_GPC['total_num'] * $fabu_xinxi['avg_min']);

								if ($total_amount < $least_amount) {

									message('红包总金额不能低于' . $least_amount . '元');

								}

								$fee = intval($total_amount * $fabu_xinxi['percent']) / 100;

								$the_pay = intval(($total_amount + $fee) * 100) / 100;

								$total_num = intval($_GPC['total_num']);
                             
                             
                       
								// 生成分配方案

								if (!empty ($id) && ($adv['rob_users'] > 0 || ($total_amount == $adv['total_amount'] && $total_num == $adv['total_num']))) {

									//$plan = $adv['rob_plan'];
									
									$top_level = $adv['top_level'];

									$hot_time = $adv['hot_time'];

									$rob_start_time = $adv['rob_start_time'];

								} else {
									
									
								

									//如果广告总额大于等于置顶线，则需要设置置顶级别

									$top_level = 0;

									if ($fabu_xinxi['top_line'] > 0 && ($total_amount) >= $fabu_xinxi['top_line']) {

										$top_level = $total_amount * 100;

									}

									$yure = pdo_fetchall("SELECT * FROM " . tablename('cgc_ad_yure') . " WHERE weid=" . $weid . " AND quan_id=" . $fabu_xinxi['quan_id'] . " ORDER BY fee ASC ");

									$hot_time = 0;

									if (!empty ($yure)) {

										foreach ($yure as $key => $value) {

											if ($total_amount < $value['fee']) {

												$hot_time = $total_amount * $value['time'];

												break;

											}

										}

										if (empty ($hot_time)) {

											$hot_time = $total_amount * $value['time'];

										}

									}

									if (!empty ($id)) {

										$now = $adv['publish_time'];

									} else {

										$now = time();

									}

									$begin_time = strtotime(date('Y-m-d')) + $fabu_xinxi['begin_time'] * 3600;

									$over_time = strtotime(date('Y-m-d')) + $fabu_xinxi['over_time'] * 3600;

									$next_begin_time = strtotime(date('Y-m-d', strtotime('+1 day'))) + $fabu_xinxi['begin_time'] * 3600;

									$rob_start_time = 0;

									if ($now < $begin_time) {

										$rob_start_time = $begin_time + $hot_time;

									} else
										if ($now < $over_time) {

											$rob_start_time = $now + $hot_time;

										} else {

											$rob_start_time = $next_begin_time + $hot_time;

										}

								
								
								
								}
								
								
						

								// 处理图片

								$images = $_GPC['thumb'];

								// 从微信服务器下载用户上传的图片

								if (!empty ($images) && count($images) > 0) {

									load()->func('file');

									$down_images = array ();

									foreach ($images as $k => $row) {

										if (!preg_match("/^(http):/", $row) && $config['is_qniu'] == 1 && empty($_W['setting']['remote']['type'])) {
                                            	
											$ret = $this->VP_IMAGE_SAVE($row);

										} else {

											$ret['image'] = $row;

										}

										if (!empty ($ret['error'])) {

											message('上传图片失败:' . $ret['error']);

										}

										$down_images[] = $ret['image'];

									}

									$images = iserializer($down_images);

								}

 
								
      	                        if($_GPC['allocation_way']=='1'){
							         $plan = $this->red_average_plan($total_amount, $total_num);
								}
								else{
								  $plan = $this->red_plan($total_amount, $total_num, $fabu_xinxi['avg_min']);
								}
								
                                	$plan = implode(',', $plan);
								$data = array (
									'weid' => $weid,
									'quan_id' => $fabu_xinxi['quan_id'],
									'mid' => $mid,						
	                                'title' => $_GPC['title'],
									'content' => $_GPC['content'],
									'images' => $images,
									'link' => $link,
									'total_num' => $total_num,
									'total_amount' => $total_amount,
									'hot_time' => $hot_time,
									'top_level' => $top_level,
									'fee' => $fee,
									'total_pay' => $the_pay,
									'pay' => $the_pay,
									'status' => $_GPC['status'],
									'views' => $_GPC['views'],									
									'rob_start_time' => $rob_start_time,
									'is_kouling' => $_GPC['is_kouling'],
									'kouling' => $_GPC['kouling'],
									'model' => $_GPC['model'],
									'top_level' => $_GPC['top_level'],
									'group_size' => $_GPC['group_size'],
									'nickname' => $fabu_xinxi['nickname'],
									'headimgurl' => $fabu_xinxi['headimgurl'],
									'kouling' => $_GPC['kouling'],
									'type' => $fabu_xinxi['type'],
									'telphone' => $_GPC['telphone'],
									'qr_code' => $_GPC['qr_code'],
									'time_limit' => $_GPC['time_limit'],
									'time_limit_start' => $_GPC['time_limit_start'],
									'time_limit_end' => $_GPC['time_limit_end'],
									'allocation_way' => $_GPC['allocation_way'],
									'job_submission_time' => $_GPC['job_submission_time'],
									'hx_status' => $_GPC['hx_status'],
									'hx_pass' => $_GPC['hx_pass'],
									'wx_cardid' => $_GPC['wx_cardid'],
									'rob_plan' => $plan,							
									
									
								);
                             
                          							
								if (!empty ($id)) {
									pdo_update('cgc_ad_adv', $data, array (
										'id' => $id
									));

									if ($total_amount != $adv['total_amount']) {

										pdo_update('cgc_ad_member', array (
											'fabu' => $fabu_xinxi['fabu'] - $adv['total_amount'] + $total_amount
										), array (
											'id' => $mid
										));

									}

								} else {

									$now = time();

									$data['links'] = 0;

									$data['rob_amount'] = 0;

									$data['rob_users'] = 0;

									$data['publish_time'] = $now;

									$data['create_time'] = $now;

									pdo_insert('cgc_ad_adv', $data);

									pdo_update('cgc_ad_member', array (
										'fabu' => $fabu_xinxi['fabu'] + $total_amount
									), array (
										'id' => $mid
									));

								}

							}

							$data['content'] = $_GPC['content'];

							$data['views'] = $_GPC['views'];

							$_userlist = pdo_fetchall('SELECT from_user FROM ' . tablename('cgc_ad_member') . " where weid=" . $weid . " AND message_notify =1 and type=1 AND quan_id=" . $fabu_xinxi['quan_id']);

							$_url = $_W['siteroot'] . "app/" . substr($this->createMobileUrl('index', array (
								'quan_id' => $fabu_xinxi['quan_id']
							)), 2);
							$htt = str_replace('"', "'", htmlspecialchars_decode($config['tuisong']));
							$_tdata = array (
								'first' => array (
									'value' => '系统通知',
									'color' => '#173177'
								),
								'keyword1' => array (
									'value' => $config['tuisong'],
									'color' => '#173177'
								),
								'keyword2' => array (
									'value' => date('Y-m-d H:i:s', time()),
									'color' => '#173177'
								),
								'keyword3' => array (
									'value' => '抢钱通知',
									'color' => '#173177'
								),
								'remark' => array (
									'value' => '点击详情进入',
									'color' => '#173177'
								),

								
							);

							if (empty ($id) && !empty ($config['tuisong'])) {
								if ($config['is_type'] == 1) {
									foreach ($_userlist as $key => $r) {
										sendTemplate_common($r['from_user'], $config['template_id'], $_url, $_tdata);
										sleep(1);
									}
								} else {

									foreach ($_userlist as $key => $r) {
										$a = post_send_text($r['from_user'], $htt);

									}

								}

							}

							message("设置成功!", $this->createWebUrl('adv'), 'success');

						}
					}

elseif ($op == 'del') {
	$id = $_GPC['id'];
	$temp = pdo_fetch("SELECT * FROM " . tablename('cgc_ad_adv') . " WHERE weid=" . $weid . " AND id=" . $id);
	if (empty ($temp)) {
		echo 2;
		exit;
	} else {
		pdo_update("cgc_ad_adv", array (
			'del' => 1
		), array (
			'id' => $id
		));

		echo 1;
		exit;
	}
} else
	if ($op == 'sendmsg') {
		$id = $_GPC['id'];

		$temp = pdo_fetch("SELECT * FROM " . tablename('cgc_ad_adv') . " WHERE weid=" . $weid . " AND id=" . $id);
		$_userlist = pdo_fetchall('SELECT openid FROM ' . tablename('cgc_ad_member') . " where weid=" . $weid . "  AND quan_id=" . $temp['quan_id'] . " and type=1 and status=0");

		$_url = $_W['siteroot'] . "app/" . substr($this->createMobileUrl('index', array (
			'quan_id' => $temp['quan_id']
		)), 2);
		$htt = str_replace('"', "'", htmlspecialchars_decode($config['tuisong']));
		$_tdata = array (
			'first' => array (
				'value' => '系统通知',
				'color' => '#173177'
			),
			'keyword1' => array (
				'value' => $config['tuisong'],
				'color' => '#173177'
			),
			'keyword2' => array (
				'value' => date('Y-m-d H:i:s', time()),
				'color' => '#173177'
			),
			'keyword3' => array (
				'value' => '抢钱通知',
				'color' => '#173177'
			),
			'remark' => array (
				'value' => '点击详情进入',
				'color' => '#173177'
			),
			
		);

		if ($config['is_type'] == 1) {
			foreach ($_userlist as $key => $r) {
				$a = sendTemplate_common($r['openid'], $config['template_id'], $_url, $_tdata);
			}
		} else {
			foreach ($_userlist as $key => $r) {
				$a = post_send_text($r['openid'], $htt);
			}
		}
		message("发送通知成功!", referer(), 'success');
	}

include $this->template('web/adv');