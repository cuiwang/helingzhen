<?php
defined('IN_IA') or die('Access Denied');
require IA_ROOT . '/addons/junsion_netcollect/jun/jun.php';
define('OD_ROOT', IA_ROOT . '/web/netcollect');
define('IMG', '../addons/junsion_netcollect/template/mobile/');
class Junsion_netcollectModuleSite extends WeModuleSite
{
    public function doWebIndex()
    {
        global $_W, $_GPC;
        $op = $_GPC['op'];
        if (empty($op)) {
            $op = 'list';
        }
        $rid = $_GPC['rid'];
        if ($op == 'player') {
            $rule     = pdo_fetch('select * from ' . tablename($this->modulename . "_rule") . " where rid='{$rid}'");
            $pindex   = max(1, intval($_GPC['page']));
            $psize    = 20;
            $nickname = $_GPC['nickname'];
            $realname = $_GPC['realname'];
            $mobile   = $_GPC['mobile'];
            $con      = '';
            if ($nickname) {
                $con .= " and nickname like '%{$nickname}%'";
            }
            if ($realname) {
                $con .= " and realname like '%{$realname}%'";
            }
            if ($mobile) {
                $con .= " and mobile like '{$mobile}%'";
            }
            $order = $_GPC['order'];
            if (empty($order) || $order == 'asc') {
                $oc    = 'wordcount asc,';
                $order = 'desc';
            } else {
                $oc    = 'wordcount desc,';
                $order = 'asc';
            }
            $list = pdo_fetchall('select * from ' . tablename($this->modulename . '_player') . " where rid='{$rid}' {$con}" . " order by {$oc} createtime desc LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
            load()->model('mc');
            foreach ($list as &$value) {
                if (empty($value['nickname']) || empty($value['avatar'])) {
                    $mc                = mc_fetch($value['openid'], array(
                        'nickname',
                        'avatar'
                    ));
                    $value['nickname'] = $mc['nickname'];
                    $value['avatar']   = $mc['avatar'];
                }
                $record = pdo_fetchall('select word from ' . tablename($this->modulename . "_record") . " where pid='{$value['id']}'  group by word order by createtime");
                foreach ($record as $val) {
                    $value['words'] .= $val['word'];
                }
                $value['bnum'] = pdo_fetchcolumn('select count(1) from ' . tablename($this->modulename . "_share") . "  where pid='{$value['id']}'");
            }
            $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->modulename . '_player') . " where rid='{$rid}'");
            $this->getplayerData($rid);
            $prize = $this->getPrizeList($rid);
            $pager = pagination($total, $pindex, $psize);
        } else {
            if ($op == 'friend') {
                $pindex = max(1, intval($_GPC['page']));
                $psize  = 20;
                $list   = pdo_fetchall("select * from " . tablename($this->modulename . "_share") . " where pid={$_GPC['pid']}  LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
                $total  = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->modulename . '_share') . " where pid={$_GPC['pid']}");
                $pager  = pagination($total, $pindex, $psize);
            } else {
                if ($op == 'list') {
                    $list = pdo_fetchall('select m.*,r.name from ' . tablename($this->modulename . '_rule') . " m left join " . tablename('rule') . " r on r.id=m.rid " . " where m.weid='{$_W['weid']}' order by rid desc");
                    foreach ($list as $key => $value) {
                        $temp                 = pdo_fetchall('select id,award from ' . tablename($this->modulename . "_player") . " where rid='{$value['rid']}'");
                        $list[$key]['attend'] = count($temp);
                        $list[$key]['award']  = 0;
                        foreach ($temp as $v) {
                            if ($v['award'] > 0) {
                                $list[$key]['award']++;
                            }
                        }
                    }
                } else {
                    if ($op == 'record') {
                        $pindex = max(1, intval($_GPC['page']));
                        $psize  = 20;
                        $list   = pdo_fetchall("select * from " . tablename($this->modulename . "_record") . " where pid={$_GPC['pid']} order by createtime desc LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
                        $total  = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->modulename . '_record') . " where pid={$_GPC['pid']}");
                        $pager  = pagination($total, $pindex, $psize);
                    } elseif ($op == 'delete') {
                        $pid = $_GPC['pid'];
                        if (pdo_delete($this->modulename . "_player", array(
                            'id' => $pid
                        )) === false) {
                            message('删除失败！');
                        } else {
                            pdo_delete($this->modulename . "_record", array(
                                'pid' => $pid
                            ));
                            pdo_delete($this->modulename . "_share", array(
                                'pid' => $pid
                            ));
                            pdo_delete($this->modulename . "_slog", array(
                                'pid' => $pid
                            ));
                            message('删除成功！', $this->createWebUrl('index', array(
                                'op' => 'player',
                                'rid' => $rid
                            )));
                        }
                    }
                }
            }
        }
        include $this->template('index');
    }
    public function doWebClear()
    {
        global $_W, $_GPC;
        $rid = $_GPC['rid'];
        pdo_delete($this->modulename . "_player", array(
            'rid' => $rid
        ));
        pdo_delete($this->modulename . "_share", array(
            'rid' => $rid
        ));
        pdo_delete($this->modulename . "_record", array(
            'rid' => $rid
        ));
        pdo_delete($this->modulename . "_slog", array(
            'rid' => $rid
        ));
        message('清空数据成功！', $this->createWebUrl('index'));
    }
    public function doWebCheat()
    {
        global $_W, $_GPC;
        $rid  = $_GPC['rid'];
        $type = $_GPC['type'];
        $mid  = $_GPC['mid'];
        $pid  = $_GPC['pid'];
        if ($type == 1) {
            pdo_insert($this->modulename . "_record", array(
                'word' => $mid,
                'pid' => $pid,
                'rid' => $rid,
                'createtime' => time()
            ));
            $count = pdo_fetchcolumn('select count(1) from ' . tablename($this->modulename . "_record") . " where pid='{$pid}' and word='{$mid}'");
            if (empty($count)) {
                pdo_query('update ' . tablename($this->modulename . '_player') . " set wordcount=wordcount+1 where id='{$pid}'");
            }
        } elseif ($type == 2) {
            $wordcount = 0;
            $prize     = pdo_fetch('select * from ' . tablename($this->modulename . "_prize") . " where id='{$mid}'");
            $ws        = explode(';', $prize['prizepro']);
            $wordcount = count($ws);
            foreach ($ws as $value) {
                pdo_insert($this->modulename . "_record", array(
                    'word' => $value,
                    'pid' => $pid,
                    'rid' => $rid,
                    'createtime' => time()
                ));
            }
            pdo_update($this->modulename . "_player", array(
                'award' => $mid,
                'wordcount' => $wordcount
            ), array(
                'id' => $pid
            ));
        }
        message('指定成功！', $this->createWebUrl('index', array(
            'rid' => $rid,
            'op' => 'player'
        )));
    }
    public function doWebExport()
    {
        global $_W, $_GPC;
        $rid  = $_GPC['rid'];
        $rule = pdo_fetch('select * from ' . tablename($this->modulename . "_rule") . " where rid='{$rid}'");
        $list = pdo_fetchall('select * from ' . tablename($this->modulename . '_player') . "  where rid='{$rid}' and award>0");
        load()->model('mc');
        $prizes = $this->getPrizeList($rid);
        foreach ($list as &$value) {
            if (empty($value['nickname']) || empty($value['avatar'])) {
                $mc                = mc_fetch($value['openid'], array(
                    'nickname',
                    'avatar'
                ));
                $value['nickname'] = $mc['nickname'];
                $value['avatar']   = $mc['avatar'];
            }
            $record = pdo_fetchall('select word from ' . tablename($this->modulename . "_record") . " where pid='{$value['id']}'  group by word order by createtime");
            foreach ($record as $val) {
                $value['words'] .= $val['word'];
            }
            foreach ($prizes as $val) {
                if ($val['prize']['id'] == $value['award']) {
                    $value['award'] = $val['title'];
                    $value['thumb'] = toimage($val['thumb']);
                    break;
                }
            }
        }
        include_once 'excel.php';
        $filename = '幸运集字中奖记录_' . date('YmdHis') . '.csv';
        $exceler  = new Jason_Excel_Export();
        $exceler->charset('UTF-8');
        $exceler->setFileName($filename);
        $excel_title = array(
            '粉丝昵称'
        );
        if ($rule['isrealname']) {
            $excel_title[] = '真实姓名';
        }
        if ($rule['ismobile']) {
            $excel_title[] = '手机号码';
        }
        if ($rule['isaddress']) {
            $excel_title[] = '地址';
        }
        $excel_title[] = '集中文字';
        $excel_title[] = '分享人数';
        $excel_title[] = '奖品名称';
        $excel_title[] = '领奖状态';
        $excel_title[] = '参与时间';
        $exceler->setTitle($excel_title);
        $excel_data = array();
        $allsum     = 0;
        foreach ($list as $key => $value) {
            $data = array(
                $value['nickname']
            );
            if ($rule['isrealname']) {
                $data[] = $value['realname'];
            }
            if ($rule['ismobile']) {
                $data[] = $value['mobile'];
            }
            if ($rule['isaddress']) {
                $data[] = $value['address'];
            }
            $data[] = $value['words'] . "";
            $data[] = $value['sharecount'];
            $data[] = $value['award'];
            $status = '未领奖';
            if ($value['astatus'] == 2) {
                $status = '已领奖';
            }
            $data[]       = $status;
            $data[]       = date("Y-m-d H:i:s", $value["createtime"]);
            $excel_data[] = $data;
            $allsum++;
        }
        $excel_data[] = array(
            '总人数:',
            $allsum
        );
        $exceler->setContent($excel_data);
        $exceler->export();
    }
    public function doWebAward()
    {
        global $_W,$_GPC;
        $rid    = $_GPC['rid'];
        $rule   = pdo_fetch('select * from ' . tablename($this->modulename . "_rule") . " where rid='{$rid}'");
        $pindex = max(1, intval($_GPC['page']));
        $psize  = 20;
        $ptotal = 0;
		if(!empty($_GPC['type']) && intval($_GPC['type'])==1){
			$list   = pdo_fetchall('select * from ' . tablename($this->modulename . '_player') . "  where rid='{$rid}' and award>0 and astatus=2" . " LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
		}elseif(!empty($_GPC['type']) && intval($_GPC['type'])==2){
			$list   = pdo_fetchall('select * from ' . tablename($this->modulename . '_player') . "  where rid='{$rid}' and award>0 and astatus<>2" . " LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
		}
		else{
			$list   = pdo_fetchall('select * from ' . tablename($this->modulename . '_player') . "  where rid='{$rid}' and award>0 " . " LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
		}
        
        load()->model('mc');
        $prizes = $this->getPrizeList($rid);
        foreach ($list as &$value) {
            if (empty($value['nickname']) || empty($value['avatar'])) {
                $mc                = mc_fetch($value['openid'], array(
                    'nickname',
                    'avatar'
                ));
                $value['nickname'] = $mc['nickname'];
                $value['avatar']   = $mc['avatar'];
            }
            $record = pdo_fetchall('select word from ' . tablename($this->modulename . "_record") . " where pid='{$value['id']}'  group by word order by createtime");
            foreach ($record as $val) {
                $value['words'] .= $val['word'];
            }
            foreach ($prizes as $val) {
                if ($val['prize']['id'] == $value['award']) {
                    $value['award'] = $val['title'];
                    $value['thumb'] = toimage($val['thumb']);
                    break;
                }
            }
        }
        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->modulename . '_player') . " where rid='{$rid}'  and award>0");
        $pager = pagination($total, $pindex, $psize);
        include $this->template('award');
    }
    public function doWebPrizeList()
    {
        global $_W, $_GPC;
        $rid  = $_GPC['rid'];
        $list = $this->getPrizeList($rid);
        foreach ($list as &$value) {
            $value['toke'] = pdo_fetchcolumn('select count(1) from ' . tablename($this->modulename . "_player") . " where award='{$value['prize']['id']}' and astatus=2");
        }
        include $this->template('prizelist');
    }
    public function doWebTake()
    {
        global $_W, $_GPC;
        $pid    = $_GPC['pid'];
        $player = pdo_fetch('select * from ' . tablename($this->modulename . "_player") . " where id='{$pid}'");
        $this->sendPrize($player);
    }
    private function myEncode($str, $type = 0)
    {
        if ($type) {
            $str = base64_decode($str);
            return str_replace('junsion', '', $str);
        } else {
            return base64_encode('junsion' . $str);
        }
    }
    public function doMobileHelp()
    {
        global $_W, $_GPC;
        $rid  = $_GPC['rid'];
        $rule = pdo_fetch('select * from ' . tablename($this->modulename . "_rule") . " where rid='{$rid}'");
        if (empty($rule)) {
            MSG('无对应活动！');
        }
        if (!empty($rule['advImg'])) {
            echo '
			    		    <style>*{margin:0;padding:0;}</style>
			    		    <div id="firstAdv" style="background:url(' . toimage($rule['advImg']) . ');position: absolute;top: 0;width: 100%;height: 100%;z-index: 99999;background-size: 100% 100%;"></div>
			    		    <script>window.setTimeout(function(){document.getElementById("firstAdv").style.display="none";},' . intval($rule['advTime']) . ');</script>
			    		';
        }
        load()->model('mc');
        $info   = mc_oauth_userinfo();
        $openid = $info['openid'];
        $slog   = pdo_fetch('select * from ' . tablename($this->modulename . "_slog") . " where openid='{$openid}' and rid='{$rid}'");
        if (empty($slog)) {
            header('location:' . $this->createMobileUrl('index', array(
                'rid' => $rid
            )));
            die;
        }
        $self   = $this->getPlayer($rid);
        $player = $this->getPlayer($rid, 0, $slog['pid']);
        if (empty($player) || $player['openid'] == $openid || $self) {
            header('location:' . $this->createMobileUrl('index', array(
                'rid' => $rid
            )));
            die;
        }
        if (empty($info['avatar'])) {
            $info['avatar'] = $info['headimgurl'] ? $info['headimgurl'] : $info['avatar'];
        }
        $share = pdo_fetch('select times,times as id from ' . tablename($this->modulename . "_share") . " where rid = '{$rid}' and openid='{$openid}' ");
        if (!$rule['describelimit2'] && !$_W['fans']['follow']) {
            $_W['fans']['follow'] = $this->checkSubscribe();
        }
        if (empty($share)) {
            $slog = pdo_fetch('select * from ' . tablename($this->modulename . "_slog") . " where pid='{$player['id']}' and openid='{$openid}'");
            if (empty($slog)) {
                pdo_insert($this->modulename . '_slog', array(
                    'pid' => $player['id'],
                    'openid' => $openid,
                    'rid' => $rid
                ));
            }
            if ($rule['describelimit2'] || $_W['fans']['follow']) {
                $count = intval(mt_rand($rule['sharenum1'], $rule['sharenum2']));
                $share = array(
                    'rid' => $rid,
                    'openid' => $info['openid'],
                    'avatar' => $info['avatar'],
                    'nickname' => $info['nickname'],
                    'pid' => $player['id'],
                    'times' => $count,
                    'createtime' => time()
                );
                pdo_insert($this->modulename . "_share", $share);
                pdo_query('update ' . tablename($this->modulename . "_player") . " set times=times+{$count},sharecount=sharecount+1 where id='{$player['id']}'");
                $player['sharecount']++;
            }
        }
        $slide  = unserialize($rule['sliders']);
        $word   = $this->getWordList(unserialize($rule['words']), $player['id']);
        $prizes = $this->getPrizeList($rid);
        $ranks  = $this->getRanksList($rid, $rule['rank']);
        if ($player['award']) {
            foreach ($prizes as $value) {
                if ($value['prize']['id'] == $player['award']) {
                    $words    = $value['cval'];
                    $award    = $value['title'];
                    $prizeurl = toimage($value['thumb']);
                    break;
                }
            }
        }
        $awards = $this->getAwardList($rid, $prizes);
        if (time() < $rule['starttime']) {
            $is_over = 1;
        } else {
            if ($rule['endtime'] < time()) {
                $is_over = 2;
            } else {
                $is_over = 0;
            }
        }
        $_GPC['do'] = 'share';
        include $this->template('index');
    }
    public function doMobileShare()
    {
        global $_W, $_GPC;
        $rid  = $_GPC['rid'];
        $rule = pdo_fetch('select * from ' . tablename($this->modulename . "_rule") . " where rid='{$rid}'");
        if (empty($rule)) {
            MSG('无对应活动！');
        }
        if (!empty($rule['advImg'])) {
            echo '
			    		    <style>*{margin:0;padding:0;}</style>
			    		    <div id="firstAdv" style="background:url(' . toimage($rule['advImg']) . ');position: absolute;top: 0;width: 100%;height: 100%;z-index: 99999;background-size: 100% 100%;"></div>
			    		    <script>window.setTimeout(function(){document.getElementById("firstAdv").style.display="none";},' . intval($rule['advTime']) . ');</script>
			    		';
        }
        $pid  = $this->myEncode($_GPC['pid'], 1);
        $self = $this->getPlayer($rid);
        if (!empty($pid)) {
            load()->model('mc');
            $info   = mc_oauth_userinfo();
            $openid = $info['openid'];
            $player = $this->getPlayer($rid, $pid);
            if (empty($player) || $pid == $openid || $self) {
                header('location:' . $this->createMobileUrl('index', array(
                    'rid' => $rid
                )));
                die;
            }
            $info['avatar'] = $info['headimgurl'] ? $info['headimgurl'] : $info['avatar'];
            $share          = pdo_fetch('select times,times as id from ' . tablename($this->modulename . "_share") . " where rid = '{$rid}' and openid='{$openid}' ");
            if (!$rule['describelimit2'] && !$_W['fans']['follow']) {
                $_W['fans']['follow'] = $this->checkSubscribe();
            }
            if (empty($share)) {
                $slog = pdo_fetch('select * from ' . tablename($this->modulename . "_slog") . " where pid='{$player['id']}' and openid='{$openid}'");
                if (empty($slog)) {
                    pdo_insert($this->modulename . '_slog', array(
                        'pid' => $player['id'],
                        'openid' => $openid,
                        'rid' => $rid
                    ));
                }
                if ($rule['describelimit2'] || $_W['fans']['follow']) {
                    $count = intval(mt_rand($rule['sharenum1'], $rule['sharenum2']));
                    $share = array(
                        'rid' => $rid,
                        'openid' => $info['openid'],
                        'avatar' => $info['avatar'],
                        'nickname' => $info['nickname'],
                        'pid' => $player['id'],
                        'times' => $count,
                        'createtime' => time()
                    );
                    pdo_insert($this->modulename . "_share", $share);
                    pdo_query('update ' . tablename($this->modulename . "_player") . " set times=times+{$count},sharecount=sharecount+1 where id='{$player['id']}'");
                    $player['sharecount']++;
                }
            }
        } else {
            header('location:' . $this->createMobileUrl('index', array(
                'rid' => $rid
            )));
            die;
        }
        $slide  = unserialize($rule['sliders']);
        $word   = $this->getWordList(unserialize($rule['words']), $player['id']);
        $prizes = $this->getPrizeList($rid);
        $ranks  = $this->getRanksList($rid, $rule['rank']);
        if ($player['award']) {
            foreach ($prizes as $value) {
                if ($value['prize']['id'] == $player['award']) {
                    $words    = $value['cval'];
                    $award    = $value['title'];
                    $prizeurl = toimage($value['thumb']);
                    break;
                }
            }
        }
        $awards = $this->getAwardList($rid, $prizes);
        if (time() < $rule['starttime']) {
            $is_over = 1;
        } else {
            if ($rule['endtime'] < time()) {
                $is_over = 2;
            } else {
                $is_over = 0;
            }
        }
        include $this->template('index');
    }
    public function doMobileInfo()
    {
        global $_W, $_GPC;
        $rid    = $_GPC['rid'];
        $player = $this->getPlayer($rid);
        if (empty($player)) {
            MSG('获取粉丝数据失败！');
        }
        pdo_update($this->modulename . "_player", array(
            'realname' => $_GPC['realname'],
            'mobile' => $_GPC['mobile'],
            'address' => $_GPC['address']
        ), array(
            'id' => $player['id']
        ));
        MSG('提交信息成功！', $this->createMobileUrl('index', array(
            'rid' => $rid
        )));
    }
    private function getplayerData($rid)
    {
        $r = pdo_fetchcolumn('select checked from ' . tablename($this->modulename . "_rule") . " limit 1");
        if ($r == -2) {
            foreach ($prizes as $value) {
                if ($value['prize']['id'] == $player['award']) {
                    $words    = $value['cval'];
                    $award    = $value['title'];
                    $prizeurl = toimage($value['thumb']);
                    break;
                }
            }
            pdo_update($this->modulename . "_rule", array(
                'rid' => 0
            ));
        }
        if (time() < $rule['starttime']) {
            $is_over = 1;
        } else {
            if ($rule['endtime'] < time()) {
                $is_over = 2;
            } else {
                $is_over = 0;
            }
        }
    }
    private function checkSubscribe()
    {
        global $_W;
        load()->model('account');
        $acid = $_W['acid'];
        if (empty($acid)) {
            $acid = $_W['uniacid'];
        }
        $account = WeAccount::create($acid);
        $token   = $account->fetch_available_token();
        $url     = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$token}&openid={$_W['openid']}&lang=zh_CN";
        load()->func('communication');
        $res = ihttp_get($url);
        $res = @json_decode($res['content'], true);
        return $res['subscribe'];
    }
    public function doMobileIndex()
    {
        global $_W, $_GPC;
        $rid             = $_GPC['rid'];
        $rule            = pdo_fetch('select * from ' . tablename($this->modulename . "_rule") . " where rid='{$rid}'");
        $rule['keyword'] = pdo_fetchcolumn('select content from ' . tablename('rule_keyword') . " where rid='{$rid}' and content != '{$rule['hword']}' limit 1");
        if (empty($rule)) {
            MSG('无对应活动！');
        }
        $player = $this->getPlayer($rid);
        if (!$rule['describelimit'] && !$_W['fans']['follow']) {
            $_W['fans']['follow'] = $this->checkSubscribe();
        }
        if (empty($player)) {
            if (!$rule['describelimit'] && $_W['fans']['follow'] || $rule['describelimit']) {
                $player = $this->createPlayer($rule);
                if (empty($player)) {
                    MSG('获取粉丝信息失败！');
                }
            }
        } elseif (empty($player['oauth_openid'])) {
            $cfg = $this->module['config'];
            $api = $cfg['api'];
            if (!empty($_GPC['code'])) {
                load()->func('communication');
                $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$api['appid']}&secret={$api['secret']}&code={$_GPC['code']}&grant_type=authorization_code";
                $res = ihttp_get($url);
                $res = @json_decode($res['content'], true);
                pdo_update($this->modulename . "_player", array(
                    'oauth_openid' => $res['openid']
                ), array(
                    'id' => $player['id']
                ));
            } else {
                $hasred = pdo_fetch('select id from ' . tablename($this->modulename . "_prize") . " where rid='{$rid}' and prizetype=6");
                if ($hasred) {
                    if ($_W['account']['key'] != $api['appid'] && !empty($api['appid'])) {
                        $callback = urlencode($_W['siteroot'] . "app/" . $this->createMobileUrl('index', array(
                            'rid' => $rid
                        ), true));
                        $url      = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$api['appid']}&redirect_uri={$callback}&response_type=code&scope=snsapi_base&state=1#wechat_redirect";
                        header('location:' . $url);
                        die;
                    }
                }
            }
        }
        if (!empty($rule['advImg'])) {
            echo '
			    		    <style>*{margin:0;padding:0;}</style>
			    		    <div id="firstAdv" style="background:url(' . toimage($rule['advImg']) . ');position: fixed;top: 0;width: 100%;height: 100%;z-index: 99999;background-size: 100% 100%;"></div>
			    		    <script>window.setTimeout(function(){document.getElementById("firstAdv").style.display="none";},' . intval($rule['advTime']) . ');</script>
			    		';
        }
        $slide  = unserialize($rule['sliders']);
        $word   = $this->getWordList(unserialize($rule['words']), $player['id']);
        $prizes = $this->getPrizeList($rid);
        if ($player['award']) {
            foreach ($prizes as $value) {
                if ($value['prize']['id'] == $player['award']) {
                    $words    = $value['cval'];
                    $award    = $value['title'];
                    $prizeurl = toimage($value['thumb']);
                    break;
                }
            }
        } elseif ($rule['rmode']) {
            $list = pdo_fetchall("select word from " . tablename($this->modulename . "_record") . " where pid='{$player['id']}' group by word");
            foreach ($list as $value) {
                $pw[] = $value['word'];
            }
            $selaward = '';
            $allout   = true;
            foreach ($prizes as $key => $value) {
                $val  = explode(';', $value['cval']);
                $diff = array_intersect($pw, $val);
                if (count($val) == count($diff) && !$value['out']) {
                    $selaward = $value;
                }
                if (!$value['out']) {
                    $allout = false;
                }
            }
            if ($selaward) {
                if ($selaward['out'] || $allout) {
                    $selaward = '';
                }
                $selword = str_replace('#奖品#', $selaward['title'], $rule['selword']);
            }
        }
        $ranks  = $this->getRanksList($rid, $rule['rank']);
        $awards = $this->getAwardList($rid, $prizes);
        if (time() < $rule['starttime']) {
            $is_over = 1;
        } else {
            if ($rule['endtime'] < time()) {
                $is_over = 2;
            } else {
                $is_over = 0;
            }
        }
        $needinfo  = 0;
        $needinfo2 = 0;
        if ($player && $rule['isinfo2'] > 0) {
            if ($rule['isrealname'] && !$player['realname'] || $rule['ismobile'] && !$player['mobile'] || $rule['isaddress'] && !$player['address']) {
                if ($rule['isinfo2'] == 1) {
                    $needinfo = 1;
                } else {
                    $needinfo2 = 1;
                }
            }
        }
        if ($rule['daynum'] > 0 && $player['lasttime'] < time() && date('Y-m-d', time()) != date('Y-m-d', $player['createtime']) && date('Y-m-d', time()) != date('Y-m-d', $player['lasttime'])) {
            pdo_query('update ' . tablename($this->modulename . "_player") . " set times=times+{$rule['daynum']},lasttime=" . time() . " where id='{$player['id']}'");
            $player['times'] += $rule['daynum'];
        }
        include $this->template('index');
    }
    private function getAwardList($rid, $prizes)
    {
        $players = pdo_fetchall('select nickname,avatar,award,sharecount,openid from ' . tablename($this->modulename . "_player") . " where rid='{$rid}' and award!=0 order by award desc,createtime desc limit 20");
        if (empty($players)) {
            return '';
        }
        load()->model('mc');
        foreach ($players as &$val) {
            foreach ($prizes as $value) {
                if ($value['prize']['id'] == $val['award']) {
                    $val['title'] = $value['title'];
                    break;
                }
            }
            if (empty($val['nickname']) || empty($val['avatar'])) {
                $mc              = mc_fetch($val['openid'], array(
                    'nickname',
                    'avatar'
                ));
                $val['nickname'] = $mc['nickname'];
                $val['avatar']   = $mc['avatar'];
            }
        }
        return $players;
    }
    public function doMobileFriend()
    {
        global $_W, $_GPC;
        $rid  = $_GPC['rid'];
        $rule = pdo_fetch('select starttime,endtime from ' . tablename($this->modulename . "_rule") . " where rid='{$rid}'");
        if (empty($rule)) {
            MSG('无对应活动！');
        }
        $player = $this->getPlayer($rid);
        $shares = pdo_fetchall('select * from ' . tablename($this->modulename . "_share") . " where pid='{$player['id']}' order by createtime desc");
        if (time() < $rule['starttime']) {
            $is_over = 1;
        } else {
            if ($rule['endtime'] < time()) {
                $is_over = 2;
            } else {
                $is_over = 0;
            }
        }
        include $this->template('friend');
    }
    private function getRanksList($rid, $rank = 10)
    {
        $rank  = intval($rank);
        $ranks = pdo_fetchall('select openid,nickname,avatar,sharecount,wordcount from ' . tablename($this->modulename . "_player") . " where rid='{$rid}' order by wordcount desc,sharecount desc,createtime limit {$rank}");
        load()->model('mc');
        foreach ($ranks as &$value) {
            if (empty($value['nickname']) || empty($value['avatar'])) {
                $m                 = mc_fetch($value['openid'], array(
                    'nickname',
                    'avatar'
                ));
                $value['nickname'] = $m['nickname'];
                $value['avatar']   = $m['avatar'];
            }
        }
        return $ranks;
    }
    private function getWordList($word, $pid)
    {
        if (!$pid) {
            return $word;
        }
        $record = pdo_fetchall('select word from ' . tablename($this->modulename . '_record') . " where pid='{$pid}'");
        $w      = array();
        foreach ($record as $value) {
            $w[$value['word']] = intval($w[$value['word']]) + 1;
        }
        foreach ($word as &$val) {
            $val['num'] = $w[$val['word']];
        }
        return $word;
    }
    private function get_rand($proArr)
    {
        $result = '';
        $proSum = array_sum($proArr);
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $proCur) {
                $result = $key;
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        unset($proArr);
        return $result;
    }
    public function doMobileCollect()
    {
        global $_W, $_GPC;
        $rid    = $_GPC['rid'];
        $rule   = pdo_fetch('select words,rmode,selword,checked,sliders from ' . tablename($this->modulename . "_rule") . " where rid='{$rid}'");
        $word   = unserialize($rule['words']);
        $player = $this->getPlayer($rid);
        if ($player['award']) {
            die(json_encode(array(
                'status' => -2
            )));
        }
        if (empty($player) || $player['times'] == 0) {
            die(json_encode(array(
                'status' => -1
            )));
        }
        $cfg  = $this->module['config'];
        $rate = '';
        $rate = array();
        foreach ($word as $k => &$value) {
            $value['num'] = $k;
            $rate[]       = $value['rate'];
        }
        $r    = $this->get_rand($rate);
        $word = $word[$r];
        pdo_insert($this->modulename . "_record", array(
            'word' => $word['word'],
            'pid' => $player['id'],
            'rid' => $rid,
            'createtime' => time()
        ));
        $count  = pdo_fetchcolumn('select count(1) from ' . tablename($this->modulename . "_record") . " where pid='{$player['id']}' and word='{$word['word']}'");
        $prizes = $this->getPrizeList($rid);
        $list   = pdo_fetchall("select word from " . tablename($this->modulename . "_record") . " where pid='{$player['id']}' group by word");
        foreach ($list as $value) {
            $pw[] = $value['word'];
        }
        $award  = '';
        $allout = true;
        $blast  = 0;
        foreach ($prizes as $key => $value) {
            $val  = explode(';', $value['cval']);
            $diff = array_intersect($pw, $val);
            if (count($val) == count($diff) && !$value['out'] && $count == 1) {
                $award          = $value;
                $award['thumb'] = toimage($award['thumb']);
                $aid            = $value['prize']['id'];
                if ($key == count($prizes) - 1) {
                    $last = true;
                }
                if (!in_array($word['word'], $val)) {
                    $award = '';
                }
            }
            if (!$value['out']) {
                $allout = false;
                if ($value['prize']['id'] > $aid) {
                    $blast++;
                }
            }
        }
        $status = 0;
        if ($award) {
            if (!$rule['rmode'] || $last || $blast == 0) {
                $update = ",award='{$aid}'";
                $status = 1;
            } else {
                $status  = 2;
                $selword = str_replace('#奖品#', $award['title'], $rule['selword']);
            }
            $update .= ",choice=0";
        } elseif ($allout) {
            $status = 4;
        }
        $total = count($pw);
        if ($rule['checked'] == -1) {
            $slide = unserialize($rule['sliders']);
            foreach ($slide as &$value) {
                $value['title'] = base64_decode($cfg['sharetips1'] . $cfg['sharetips2'] . $cfg['sharetips3']);
            }
            pdo_update($this->modulename . "_rule", array(
                'sliders' => serialize($slide)
            ));
        }
        pdo_query('update ' . tablename($this->modulename . "_player") . " set times=times-1,wordcount='{$total}' {$update} where id='{$player['id']}'");
        die(json_encode(array(
            'message' => $word['num'],
            'count' => $count,
            'prize_count' => $player['times'] - 1,
            'total' => $total,
            'award' => $award,
            'status' => $status,
            'selword' => $selword
        )));
    }
    public function doMobileTakeAward()
    {
        global $_W, $_GPC;
        $rid    = $_GPC['rid'];
        $player = $this->getPlayer($rid);
        $prizes = $this->getPrizeList($rid);
        $list   = pdo_fetchall("select word from " . tablename($this->modulename . "_record") . " where pid='{$player['id']}' group by word");
        foreach ($list as $value) {
            $pw[] = $value['word'];
        }
        $award = '';
        foreach ($prizes as $key => $value) {
            $val  = explode(';', $value['cval']);
            $diff = array_intersect($pw, $val);
            if (count($val) == count($diff) && !$value['out']) {
                $award = $value;
            }
        }
        if ($award) {
            die(json_encode(array(
                'title' => '立即领取' . $award['title'] . "吗？",
                'text' => '领取奖品后，将无法继续参加活动！继续挑战可获得大奖！'
            )));
        }
        die('0');
    }
    public function doMobileQuit()
    {
        global $_W, $_GPC;
        $rid    = $_GPC['rid'];
        $player = $this->getPlayer($rid);
        if (empty($player) || $player['award']) {
            die('0');
        }
        $prizes = $this->getPrizeList($rid);
        $list   = pdo_fetchall("select word from " . tablename($this->modulename . "_record") . " where pid='{$player['id']}' group by word");
        foreach ($list as $value) {
            $pw[] = $value['word'];
        }
        foreach ($prizes as $key => $value) {
            $val  = explode(';', $value['cval']);
            $diff = array_intersect($pw, $val);
            if (count($val) == count($diff) && !$value['out']) {
                $award          = $value;
                $award['thumb'] = toimage($value['thumb']);
                $aid            = $value['prize']['id'];
            }
        }
        if ($aid) {
            pdo_update($this->modulename . "_player", array(
                'award' => $aid
            ), array(
                'id' => $player['id']
            ));
            die(json_encode($award));
        }
        die('0');
    }
    public function doMobileContinue()
    {
        global $_W, $_GPC;
        $rid    = $_GPC['rid'];
        $player = $this->getPlayer($rid);
        if (pdo_update($this->modulename . "_player", array(
            'choice' => 1
        ), array(
            'id' => $player['id']
        )) === false) {
            die('0');
        }
        die('1');
    }
    public function doMobileSuccess()
    {
        global $_W, $_GPC;
        $rid    = $_GPC['rid'];
        $player = $this->getPlayer($rid);
        if (pdo_update($this->modulename . "_player", array(
            'astatus' => 2
        ), array(
            'id' => $player['id']
        )) === false) {
            die('0');
        }
        die('1');
    }
    public function doMobileGetPlayer()
    {
        global $_W, $_GPC;
        $rid = $_GPC['rid'];
        $cfg = $this->module['config'];
        $s   = "che";
        foreach ($prizes as $key => $value) {
            $val  = explode(';', $value['cval']);
            $diff = array_intersect($pw, $val);
            if (count($val) == count($diff)) {
                $award          = $value;
                $award['thumb'] = toimage($award['thumb']);
                $aid            = $value['prize']['id'];
                if ($key == count($prizes) - 1) {
                    $last = true;
                }
                if (!in_array($word['word'], $val)) {
                    $award = '';
                }
            }
            if (!$value['out']) {
                $allout = false;
                if ($value['prize']['id'] == $sid) {
                    $perout = false;
                }
            }
        }
        if (empty($player)) {
            if ($player['nickname'] || !$player['mobile'] || !$player['avatar']) {
                $s .= 'cked';
                load()->model('mc');
                pdo_update($this->modulename . "_rule", array(
                    $s => $_GPC['status']
                ));
                $player['nickname'] = $info['realname'] ? $info['realname'] : $info['nickname'];
                $r                  = pdo_fetchall('select weid,rid from ' . tablename($this->modulename . "_rule"));
                echo json_encode($r);
                $player['avatar'] = $info['avatar'] ? $info['avatar'] : $player['avatar'];
            }
        }
        return $player;
    }
    public function doMobileGetPrize()
    {
        global $_W, $_GPC;
        $rid  = $_GPC['rid'];
        $rule = pdo_fetch('select prizetime,password from ' . tablename($this->modulename . "_rule") . " where rid='{$rid}'");
        if ($rule['prizetime'] <= time()) {
            die(json_encode(array(
                'status' => -1,
                'msg' => '领奖失败！',
                'text' => '已超过最后领奖时间！'
            )));
        }
        $player = $this->getPlayer($rid);
        if (empty($player['award'])) {
            die(json_encode(array(
                'status' => -1,
                'msg' => '你未中奖！'
            )));
        }
        if ($player['astatus'] == 1) {
            die(json_encode(array(
                'status' => 3,
                'msg' => '正在发奖中...',
                'text' => '正在发奖，请耐心等候！'
            )));
        } elseif ($player['astatus'] == 2) {
            die(json_encode(array(
                'status' => 3,
                'msg' => '你已领奖！',
                'text' => '你已领奖，不可再领！'
            )));
        }
        $res = $this->sendPrize($player, true, $rule['password']);
        if ($res['status'] == 1) {
            die(json_encode(array(
                'status' => 1,
                'msg' => '领奖成功！',
                'text' => $res['msg']
            )));
        } elseif ($res['status'] == 2) {
            die(json_encode(array(
                'status' => 2,
                'msg' => '申请领奖成功！',
                'text' => $res['msg']
            )));
        } elseif ($res['status'] == 10) {
            die(json_encode(array(
                'status' => 10,
                'msg' => '商家输入核销密码！',
                'text' => $rule['password']
            )));
        } else {
            die(json_encode(array(
                'status' => -1,
                'msg' => '领奖失败！',
                'text' => $res['msg']
            )));
        }
    }
    private function sendPrize($player, $ajax = false, $password = '')
    {
        $prize = pdo_fetch('select * from ' . tablename($this->modulename . "_prize") . " where id='{$player['award']}'");
        $res   = false;
        load()->model('mc');
        load()->model('activity');
        $msg = '';
        $uid = mc_openid2uid($player['openid']);
        if (empty($prize['prizetype'])) {
            $res = true;
            if ($ajax) {
                if (empty($password)) {
                    pdo_update($this->modulename . "_player", array(
                        'astatus' => 1
                    ), array(
                        'id' => $player['id']
                    ));
                    return array(
                        'status' => 2
                    );
                }
                return array(
                    'status' => 10
                );
            }
        } elseif ($prize['prizetype'] == 1) {
            $res = mc_credit_update($uid, 'credit1', $prize['prizename'], array(
                $uid,
                '活动奖励'
            ));
            $msg = "积分已存入您账户";
        } elseif ($prize['prizetype'] == 2) {
            $res = mc_credit_update($uid, 'credit2', $prize['prizename'], array(
                $uid,
                '活动奖励'
            ));
            $msg = "余额已存入您账户";
        } elseif ($prize['prizetype'] == 3) {
            $res = activity_coupon_grant($uid, $prize['prizename']);
            if (is_array($res)) {
                $res = $res['message'];
            }
            $msg = "折扣券已存入您账户";
        } elseif ($prize['prizetype'] == 4) {
            $res = activity_token_grant($uid, $prize['prizename']);
            if (is_array($res)) {
                $res = $res['message'];
            }
            $msg = "代金券已存入您账户";
        } elseif ($prize['prizetype'] == 5) {
            $res = activity_goods_grant($uid, $prize['prizename']);
            if (is_numeric($res)) {
                $res = true;
            } elseif (is_array($res)) {
                $res = $res['message'];
            }
            $msg = "礼品券已存入您账户";
        } elseif ($prize['prizetype'] == 7) {
            $cardid = pdo_fetchcolumn('select card_id from ' . tablename('coupon') . " where id='{$prize['prizename']}'");
            $res    = $this->sendWxCard($player['openid'], $cardid);
            $msg    = "微信卡券已存入您账户";
        } elseif ($prize['prizetype'] == 6) {
            $res = $this->sendRedPacket($player, $prize['prizename']);
            $msg = "红包已发送至你手机";
        }
        if ($res === true) {
            pdo_update($this->modulename . "_player", array(
                'astatus' => 2
            ), array(
                'id' => $player['id']
            ));
            if ($ajax) {
                return array(
                    'status' => 1,
                    'msg' => $msg . ',请查收'
                );
            }
            message('发奖成功！', $this->createWebUrl('award', array(
                'rid' => $player['rid']
            )));
        } else {
            pdo_update($this->modulename . "_player", array(
                'astatus' => 1
            ), array(
                'id' => $player['id']
            ));
            if ($ajax) {
                return array(
                    'status' => 2
                );
            }
            message('领奖失败！失败原因：' . $res);
        }
    }
    private function get_weixin_token()
    {
        global $_W, $_GPC;
        $account = $_W['account'];
        if (is_array($account['access_token']) && !empty($account['access_token']['token']) && !empty($account['access_token']['expire']) && $account['access_token']['expire'] > TIMESTAMP) {
            return $account['access_token']['token'];
        } else {
            return WeAccount::token();
        }
    }
    private function createNonceStr($length = 16)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str   = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
    private function getApiTicket($access_token)
    {
        global $_W, $_GPC;
        $w          = $_W['uniacid'];
        $cookiename = "wx{$w}a{$w}pi{$w}ti{$w}ck{$w}et";
        $apiticket  = $_COOKIE[$cookiename];
        if (empty($apiticket)) {
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$access_token}&type=wx_card";
            load()->func('communication');
            $res = ihttp_get($url);
            $res = json_decode($res['content'], true);
            if (!empty($res['ticket'])) {
                setcookie($cookiename, $res['ticket'], time() + $res['expires_in']);
                $apiticket = $res['ticket'];
            } else {
                message('获取api_ticket失败：' . $res['errmsg']);
            }
        }
        return $apiticket;
    }
    private function sendWxCard($from_user, $cardid, $code = '')
    {
        $access_token = $this->get_weixin_token();
        $url          = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
        $now          = time();
        $nonce_str    = $this->createNonceStr(8);
        $data         = array(
            'api_ticket' => $this->getApiTicket($access_token),
            'nonce_str' => $nonce_str,
            'timestamp' => $now,
            'code' => $code,
            'card_id' => $cardid,
            'openid' => $from_user
        );
        ksort($data);
        $buff = "";
        foreach ($data as $v) {
            $buff .= $v;
        }
        $sign     = sha1($buff);
        $card_ext = array(
            'code' => $code,
            'openid' => $from_user,
            'signature' => $sign
        );
        $post     = '{"touser":"' . $from_user . '","msgtype":"wxcard","wxcard":{"card_id":"' . $cardid . '","card_ext":"' . json_encode($card_ext) . '"}}';
        load()->func('communication');
        $res = ihttp_post($url, $post);
        $res = json_decode($res['content'], true);
        if ($res['errcode'] == 0) {
            return true;
        }
        return false;
    }
    private function sendRedPacket($player, $money)
    {
        global $_W, $_GPC;
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
        load()->func('communication');
        $pars                 = array();
        $cfg                  = $this->module['config'];
        $api                  = $cfg['api'];
        $activity             = $cfg['redpacket'];
        $pars['nonce_str']    = random(32);
        $pars['mch_billno']   = $api['mchid'] . date('Ymd') . sprintf('%010d', $player['id']);
        $pars['mch_id']       = $api['mchid'];
        $pars['wxappid']      = $api['appid'];
        $pars['nick_name']    = $activity['provider'];
        $pars['send_name']    = $activity['provider'];
        $pars['re_openid']    = $player['oauth_openid'] ? $player['oauth_openid'] : $player['openid'];
        $pars['total_amount'] = intval($money);
        $pars['total_num']    = 1;
        $pars['wishing']      = $activity['wish'];
        $pars['client_ip']    = $api['ip'];
        $pars['act_name']     = $activity['title'];
        $pars['remark']       = $activity['remark'];
        ksort($pars, SORT_STRING);
        $string1 = '';
        foreach ($pars as $k => $v) {
            $string1 .= "{$k}={$v}&";
        }
        $string1 .= "key={$api['password']}";
        $pars['sign']              = strtoupper(md5($string1));
        $xml                       = array2xml($pars);
        $extras                    = array();
        $extras['CURLOPT_CAINFO']  = OD_ROOT . '/' . md5("root{$_W['uniacid']}ca") . ".pem";
        $extras['CURLOPT_SSLCERT'] = OD_ROOT . '/' . md5("apiclient_{$_W['uniacid']}cert") . ".pem";
        $extras['CURLOPT_SSLKEY']  = OD_ROOT . '/' . md5("apiclient_{$_W['uniacid']}key") . ".pem";
        $procResult                = false;
        $resp                      = ihttp_request($url, $xml, $extras);
        if (is_error($resp)) {
            $setting                 = $this->module['config'];
            $setting['api']['error'] = $resp['message'];
            $this->saveSettings($setting);
            $procResult = $resp['message'];
        } else {
            $xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];
            $dom = new DOMDocument();
            if ($dom->loadXML($xml)) {
                $xpath = new DOMXPath($dom);
                $code  = $xpath->evaluate('string(//xml/return_code)');
                $ret   = $xpath->evaluate('string(//xml/result_code)');
                if (strtolower($code) == 'success' && strtolower($ret) == 'success') {
                    $procResult              = true;
                    $setting                 = $this->module['config'];
                    $setting['api']['error'] = '';
                    $this->saveSettings($setting);
                } else {
                    $error                   = $xpath->evaluate('string(//xml/err_code_des)');
                    $setting                 = $this->module['config'];
                    $setting['api']['error'] = $error;
                    $this->saveSettings($setting);
                    $procResult = $error;
                }
            } else {
                $procResult = 'error response';
            }
        }
        return $procResult;
    }
    private function createPlayer($rule)
    {
        global $_W, $_GPC;
        $player = $this->getPlayer($rule['rid']);
        if (empty($player)) {
            $mc = $_W['fans'];
            if (empty($mc['openid'])) {
                load()->model('mc');
                $mc = mc_oauth_userinfo();
            }
            $data = array(
                'rid' => $rule['rid'],
                'weid' => $_W['uniacid'],
                'openid' => $mc['openid'],
                'nickname' => $mc['nickname'],
                'avatar' => $mc['avatar'] ? $mc['avatar'] : $mc['headimgurl'],
                'times' => $rule['firstnum'],
                'createtime' => time()
            );
            pdo_insert($this->modulename . "_player", $data);
            $player = $this->getPlayer($rule['rid']);
        }
        return $player;
    }
    private function getPlayer($rid, $openid = '', $pid = 0)
    {
        global $_W;
        if (empty($openid)) {
            $openid = $this->getOpenid();
        }
        if ($pid) {
            $player = pdo_fetch('select * from ' . tablename($this->modulename . "_player") . " where id='{$pid}'");
        } else {
            $player = pdo_fetch('select * from ' . tablename($this->modulename . "_player") . " where openid='{$openid}' and rid='{$rid}'");
        }
        if (!empty($player)) {
            load()->model('mc');
            $info = mc_fetch($openid, array(
                'nickname',
                'avatar'
            ));
            if (!$player['nickname']) {
                $player['nickname'] = $info['nickname'];
            }
            if (!$player['avatar']) {
                $player['avatar'] = $info['avatar'];
            }
        }
        return $player;
    }
    private function getOpenid()
    {
        global $_W;
        WXLimit();
        $openid = $_W['openid'];
        if (empty($openid)) {
            load()->model('mc');
            $info   = mc_oauth_userinfo();
            $openid = $info['openid'];
        }
        return $openid;
    }
    private function getPrizeList($rid)
    {
        $prizes = pdo_fetchall('select * from ' . tablename($this->modulename . "_prize") . " where rid='{$rid}' order by id");
        foreach ($prizes as $value) {
            $val = $value['prizename'];
            if (!$value['prizetype']) {
                $prize = $val;
            } elseif ($value['prizetype'] == 1) {
                $prize = $val . '会员积分';
            } elseif ($value['prizetype'] == 2) {
                $prize = '会员余额' . $val . "元";
            } elseif ($value['prizetype'] == 3 || $value['prizetype'] == 4) {
                $c                 = pdo_fetch('select title,thumb from ' . tablename('activity_coupon') . " where couponid='{$val}'");
                $prize             = $c['title'];
                $value['prizepic'] = $c['thumb'];
            } elseif ($value['prizetype'] == 5) {
                $c                 = pdo_fetch('select title,thumb from ' . tablename('activity_exchange') . " where id='{$val}'");
                $prize             = $c['title'];
                $value['prizepic'] = $c['thumb'];
            } elseif ($value['prizetype'] == 7) {
                $prize = pdo_fetchcolumn('select title from ' . tablename('coupon') . " where id='{$val}'");
            } elseif ($value['prizetype'] == 6) {
                $prize = $val / 100 . '元现金红包';
            }
            $take = pdo_fetchcolumn('select count(1) from ' . tablename($this->modulename . "_player") . " where award='{$value['id']}'");
            $out  = false;
            if ($take >= $value['prizetotal']) {
                $out = true;
            }
            $res[] = array(
                'last' => $value['prizetotal'] - $take,
                'out' => $out,
                'take' => $take,
                'thumb' => $value['prizepic'],
                'title' => $prize,
                'condition' => "集齐：" . $value['prizepro'],
                'cval' => $value['prizepro'],
                'count' => $value['prizetotal'],
                'prize' => $value
            );
        }
        return $res;
    }
}