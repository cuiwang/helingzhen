		<?php
		global $_W,$_GPC;
		$weid = $_W['uniacid'];
		$rid = intval($_GPC['rid']);
		$rotate_id = intval($_GPC['rotate_id']);
 		$code = $_GPC['code'];
		if ($_GPC['code']=="authdeny" || empty($_GPC['code'])){
            message("授权失败");
        }
		load()->func('communication');
		if(!empty($code)) {
			$reply=pdo_fetch('SELECT `appid`,`secret` FROM '.tablename($this->redpack_config_table).' WHERE weid=:weid AND rid=:rid',array(':weid'=>$weid,':rid'=>$rid));
			$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$reply['appid']."&secret=".$reply['secret']."&code=".$code."&grant_type=authorization_code";
			$ret = ihttp_get($url);
			if(!is_error($ret)) {
				$auth = @json_decode($ret['content'], true);
				if(is_array($auth) && !empty($auth['openid'])) {
					isetcookie('Meepo'.$weid, $auth['openid'], 3600);
					$forward =$_W['siteroot']."app/".$this->createMobileurl('app_redpack',array('rid'=>$rid));
                    $forward = str_replace('./','', $forward);
					header('location: ' .$forward);
					exit;
				}else{
					message('微信授权失败');
				}
			}else{
				message('微信授权失败');
			}
		}else{
			message('微信授权失败');
		}