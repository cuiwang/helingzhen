<?php
/**
 * 易客竞猜模块处理程序
 *
 */
define('YIKE_MODULE', IA_ROOT . '/addons/yike_guess/yike/');

function m($name = '') {
    static $_modules = array();
    if (isset($_modules[$name])) {
        return $_modules[$name];
    }
    $module = YIKE_MODULE . strtolower($name) . '.php';
    if (!is_file($module)) {
        die(' Module ' . $name . ' Not Found!');
    }
    require $module;
    $class_name = 'Yike_' . ucfirst($name);
    $_modules[$name] = new $class_name();
    return $_modules[$name];
}

class Yike_guessModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W;
        if ($this->message['event'] == 'SCAN') {

            $openid = $this->message['from'];
            $sceneid = $this->message['eventkey'];
            $qr = m('qrcode')->getQRBySceneid($sceneid);
            if (empty($qr)) {
                return $this->respText('无效的二维码');
            }

            $qrmember = pdo_get('yike_members', array('uniacid' => $_W['uniacid'], 'openid' => $qr['openid']));
            $uid = $_W['member']['uid'];
            $old_user = pdo_get('yike_members', array('uniacid' => $_W['uniacid'], 'uid' => $uid));
            if (empty($old_user)) {
                //如果没有注册过
                $agent = pdo_get(
                	'yike_members', array(
                	'uniacid' => $_W['uniacid'],
                	'uid' => $qrmember['uid']
                ));
                $user = array(
                	'uniacid' => $_W['uniacid'],
                	'uid' => $uid,
                	'level1' => $qrmember['uid'],
                	'level2' => $agent['level1'],
                	'level3' => $agent['level2'],
                    'is_inviter' => 0,
                    'openid' => $_W['openid']
                );
                $user_result = pdo_insert('yike_members', $user);
                if (empty($user_result)) {
                    message('出错了, 用户数据创建失败');
                }
            }elseif(!empty($old_user)){
            	$openid = array(
            		'openid' => $_W['openid']
            	);
            	$openid = pdo_update('yike_members',$openid,array(
            		'uniacid' => $_W['uniacid'],
            		'uid' => $uid
            	));
            }
        }elseif ($this->message['event'] == 'subscribe') {
            $openid = $this->message['from'];
            $sceneid = $this->message['eventkey'];
            preg_match("/_(.*)/", $sceneid ,$id);
            $qr = m('qrcode')->getQRBySceneid($id[1]);
            if (empty($qr)) {
                return $this->respText('无效的二维码');
            }
            $qrmember = pdo_get('yike_members', array('uniacid' => $_W['uniacid'], 'openid' => $qr['openid']));
            $uid = $_W['member']['uid'];
            $old_user = pdo_get('yike_members', array('uniacid' => $_W['uniacid'], 'uid' => $uid));
            $setdata = pdo_fetch("select * from " . tablename('yike_guess_sysset') . ' where uniacid=:uniacid limit 1', array(
                ':uniacid' => $_W['uniacid']
            ));
            $set = unserialize($setdata['sets']);
            if (!$old_user) {
                //如果没有注册过
                $agent = pdo_get('yike_members', array('uniacid' => $_W['uniacid'], 'uid' => $qrmember['uid']));
                $user = array(
                	'uniacid' => $_W['uniacid'],
                	'uid' => $uid,
                	'level1' => $qrmember['uid'],
                	'level2' => $agent['level1'],
                	'level3' => $agent['level2'],
                	'is_inviter' => 0,
                    'openid' => $_W['openid']
                );
                $user_result = pdo_insert('yike_members', $user);
                if (empty($user_result)) {
                    message('出错了, 用户数据创建失败');
                }
            }elseif(!empty($old_user)){
            	$openid = array(
            		'openid' => $_W['openid']
            	);
            	$openid = pdo_update('yike_members',$openid,array(
            		'uniacid' => $_W['uniacid'],
            		'uid' => $uid
            	));
            }
            if (!empty($id[1])) {
                return $this->respText($set['hint']);
            }
        } else if ($this->message['content'] == '竞猜二维码') {
            $old_user = pdo_get('yike_members', array('uniacid' => $_W['uniacid'], 'uid' => $_W['member']['uid']));
            $openid = $this->message['from'];
            if (empty($old_user)) {
                $waittext = "您暂无海报生成权限,请先进入竞猜";
                m("notice")->sendCustomNotice($openid, $waittext);
                return false;
            }

            $waittext = "您的专属海报正在拼命生成中，请等待片刻...";
            m("notice")->sendCustomNotice($openid, $waittext);

            load()->model('mc');
            $uid = mc_openid2uid($openid);
            $user = pdo_get('mc_members', array('uniacid'=>$_W['uniacid'], 'uid'=>$uid));
            $user['openid'] = $openid;
            //用户的二维码
            $setdata = pdo_fetch("select * from " . tablename('yike_guess_sysset') . ' where uniacid=:uniacid limit 1', array(
                ':uniacid' => $_W['uniacid']
            ));
            $set = unserialize($setdata['sets']);
            if (empty($set)) {
                $set = array('poster' => '/addons/yike_guess/static/img/poster.png');
            } else if (empty($set['poster'])) {
                $set['poster'] = '/addons/yike_guess/static/img/poster.png';
            }
            if($set['qr_code'] == 1){
                $poster = array('data' => '[{"left":"130px","top":"15px","type":"qr","width":"84px","height":"84px","size":""},{"left":"120px","top":"108px","type":"nickname","width":"80px","height":"40px","size":"16px","color":"#fff"},{"left":"120px","top":"148px","type":"word","width":"80px","height":"40px","size":"16px","color":"#fff"}]',
                    'bg' => $set['poster'], 'poster_text' => $set['poster_text']);
            }elseif($set['qr_code'] == 2){
                $poster = array('data' => '[{"left":"130px","top":"165px","type":"qr","width":"84px","height":"84px","size":""},{"left":"120px","top":"258px","type":"nickname","width":"80px","height":"40px","size":"16px","color":"#fff"},{"left":"120px","top":"298px","type":"word","width":"80px","height":"40px","size":"16px","color":"#fff"}]',
                    'bg' => $set['poster'], 'poster_text' => $set['poster_text']);
            }elseif($set['qr_code'] == 3){
                $poster = array('data' => '[{"left":"130px","top":"315px","type":"qr","width":"84px","height":"84px","size":""},{"left":"120px","top":"408px","type":"nickname","width":"80px","height":"40px","size":"16px","color":"#fff"},{"left":"120px","top":"448px","type":"word","width":"80px","height":"40px","size":"16px","color":"#fff"}]',
                    'bg' => $set['poster'], 'poster_text' => $set['poster_text']);
            }
            $media = m('qrcode')->getPoster($poster, $user, false);
            $result = pdo_get('yike_guess_qr', array('acid' => $_W['acid'], 'openid' => $openid));
            $mediaid = $result['mediaid'];
            m('notice')->sendImage($openid, $mediaid);
        }
	}
}