<?php
/**
 * 家政服务模块处理程序
 *
 */
defined('IN_IA') or exit('Access Denied');

class Zm_housekeepnewModuleProcessor extends WeModuleProcessor {

    //获取昵称
    public function nickName()
    {
        global $_W, $_GPC;
        load()->model('mc');
        $result = mc_fansinfo($_W['member']['uid'], $_W['acid'], $_W['uniacid']);
        if ($result) {
            $nickname = $result['nickname'];
            if (empty($nickname)) {
                $nickname = $_W['fans']['nickname'];
                if (empty($nickname)) {
                    mc_oauth_userinfo($_W['acid']);
                    $nickname = $_W['fans']['tag']['nickname'];
                }
            }
        } else {
            $result = mc_fetch($_W['openid']);
            $nickname = $result['nickname'];
        }
        return $nickname;
    }
    //获取头像
    public function imgAvtar()
    {
        global $_W, $_GPC;
        load()->model('mc');
        $result = mc_fansinfo($_W['member']['uid'], $_W['acid'], $_W['uniacid']);
        if ($result) {
            $avatar = $result['avatar'];
            if (empty($avatar)) {
                $avatar = $_W['fans']['avatar'];
                if (empty($avatar)) {
                    mc_oauth_userinfo($_W['acid']);
                    $avatar = $_W['fans']['tag']['avatar'];
                }
            }
        } else {
            $result = mc_fetch($_W['openid']);
            $avatar = $result['avatar'];
        }
        return $avatar;
    }

	public function respond() {
	    global $_W;
	    $_base = 'xk_housekeepbase';
	    $_muban = 'xk_housekeepmuban';
	    $_staff = 'xk_housekeepstaff';
	    $_user = 'xk_housekeepuser';
		$content = $this->message['content'];
        $_wid = $_W['uniacid'];
		$id =$this->rule;
        $base = pdo_get($_base,array('wid'=>$_wid));
        $muban = pdo_get($_muban,array('wid'=>$_wid));
        $member = pdo_get('xk_housekeepmember',array('wid'=>$_wid,'level'=>0));
		$staff = pdo_get($_staff,array('wid'=>$_wid,'rid'=>$id));
        $user = pdo_get($_user,array('wid'=>$_wid,'openid'=>$this->message['from']));
        if ($user){
            if ($user['tgyopenid'] == null){
                pdo_update($_user,array('tgyopenid'=>$staff['openid'],'tgytime'=>TIMESTAMP),array('openid'=>$this->message['from'],'wid'=>$_wid));
                $wob = WeAccount::create($_W['acid']);
                $data = array(
                    'first'    => array(
                        'value' =>$muban['prompt7'],
                        'color' => '#173177'
                    ),
                    'keyword1' => array(
                        'value' =>$this->nickName().'成为你推广的客户',
                        'color' => '#173177'
                    ),
                    'keyword2' => array(
                        'value'=>date('Y-m-d H:i:s',TIMESTAMP),
                        'color'=>'#173177'
                    ),
                    'remark' => array(
                        'value'=>$muban['remarks7'],
                        'color'=>'#173177'
                    )
                );
                $wob->sendTplNotice($staff['openid'],$muban['messageid7'],$data,$_W['siteroot'].'app'.str_replace('./','/',murl('entry/site/people')).'&m='.$this->module['name'].'&open='.$staff['openid'],'#FF683F');
                $data11['tgynum'] = $staff['tgynum'] + 1;
                pdo_update($_staff,$data11,array('wid'=>$_wid,'id'=>$staff['id']));
                $name = str_replace('{name}',$staff['name'],$base['tjztz']);
                return $this->respText($name);
            }else{
                return $this->respText($base['cfsm']);
            }
        }else{
            $data = array(
                'wid' => $_wid,
                'openid' => $this->message['from'],
                'nickname' =>$this->nickName(),
                'avatar' =>$this->imgAvtar(),
                'member' =>$member['title'],
                'addtime'=>TIMESTAMP,
            );
            pdo_insert($_user,$data);
            pdo_update($_user,array('tgyopenid'=>$staff['openid']),array('openid'=>$this->message['from'],'wid'=>$_wid));
            $wob = WeAccount::create($_W['acid']);
            $data1 = array(
                'first'    => array(
                    'value' =>$muban['prompt7'],
                    'color' => '#173177'
                ),
                'keyword1' => array(
                    'value' =>$this->nickName().'成为你推广的客户',
                    'color' => '#173177'
                ),
                'keyword2' => array(
                    'value'=>date('Y-m-d H:i:s',TIMESTAMP),
                    'color'=>'#173177'
                ),
                'remark' => array(
                    'value'=>$muban['remarks7'],
                    'color'=>'#173177'
                )
            );
            $wob->sendTplNotice($staff['openid'],$muban['messageid7'],$data1,$_W['siteroot'].'app'.str_replace('./','/',murl('entry/site/people')).'&m='.$this->module['name'].'&open='.$staff['openid'],'#FF683F');
            $data11['tgynum'] = $staff['tgynum'] + 1;
            pdo_update($_staff,$data11,array('wid'=>$_wid,'id'=>$staff['id']));
            $name = str_replace('{name}',$staff['name'],$base['tjztz']);
            return $this->respText($name);
//        }


//        $this->message = array(
//            'from' => 'fromUser',  //来源openid
//            'to' => 'toUser', //公众号标识
//            'time' => '1448694116',
//            'type' => 'text', //消息类型
//            'event' => '',
//            'tousername' => 'toUser', //同from
//            'fromusername' => 'fromUser', //同to
//            'createtime' => '1448694116',
//            'msgtype' => 'text',
//            'content' => '官方示例', //关键字
//            'msgid' => '1234567890123456',
//            'redirection' => '',  //是否有消息重定向，例如扫描二维码后触发某一个关键字，这样的消息就属于重定向消息
//            'source' => '', //重定向消息原消息类型
//        );
		//这里定义此模块进行消息处理时的具体过程, 请查看易福源码网文档来编写你的代码 
	    }
    }
}