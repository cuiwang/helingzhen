<?php

	global $_W,$_GPC;
    $weid=$_W['uniacid'];
    $userinfo=getFromUser($this->settings,$this->modulename);
    $userinfo=json_decode($userinfo,true); 
    $from_user=$userinfo['openid'];
    $quan_id=intval($_GPC['quan_id']);


		$quan_id=intval($_GPC['quan_id']);

		if(empty($quan_id))

		{

			$msg="你访问的网站找不到了";

			$err_title="温馨提示";

			$label='warn';

			include $this->template('error');

		}

		else

		{

			$adv=pdo_fetch("SELECT * FROM ".tablename('cgc_ad_quan')." WHERE weid=".$weid." AND id=".$quan_id);

			if(empty($adv) || $adv['del']==1 || $adv['status']==0)

			{

				$msg=$adv['aname']."正在维护，请稍后再来吧~";

				$err_title="温馨提示";

				$label='warn';

				include $this->template('error');

			}

			else

			{

				$op=$_GPC['op'];

				if($op=='pay')

				{

					$msg="发布失败";

					$err_title=$adv['aname'];

					$label='success';

					$redirect=$_W['siteroot'] . 'app/' . substr($this->createMobileUrl('index', array('quan_id' => $quan_id)), 2);

					include $this->template('error');

				}else if($op == 'vip_recharge'){
					
						$msg="充值vip失败";
					
						$err_title=$adv['aname'];
					
						$label='error';
					
						$redirect=$_W['siteroot'] . 'app/' . substr($this->createMobileUrl('vip_recharge', array('quan_id' => $quan_id)), 2);
					
						include $this->template('error');
					
				}

			}

		}