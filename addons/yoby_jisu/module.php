<?php
/**
 * 极速龙舟模块定义
 * 易福源码网 www.efwww.com
 */
defined('IN_IA') or exit('Access Denied');

class Yoby_jisuModule extends WeModule {
	public $mod_name = "yoby_jisu";
	public $title = "极速龙舟";
	public $playdesc = "游戏开始后,操作龙舟收集粽子,撞上别人龙舟游戏结束,相同分数先参与活动者优先排名.";
	public function fieldsFormDisplay($rid = 0) {
		global $_W;
		load()->func('tpl');
		if(!empty($rid)){
			$reply = pdo_fetch("SELECT * FROM ".tablename($this->mod_name."_reply")." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
			$reply['data'] = unserialize($reply['data']);
			
		}else{
			$reply = array(
			'hd_title'=>$this->title,
			'hd_desc'=>'邀请您的朋友一起来玩'.$this->title.'游戏吧!',
			'hd_img'=>$_W['siteroot']."addons/".$this->mod_name."/template/mobile/images/avg.jpg",
			'desc'=>"<p><strong>游戏玩法</strong></p>".$this->playdesc."<br><p><strong>奖品设置</strong></p>
<p>第1名 Macbook 128GB一台</p>
<p>第2名 IPhone6s 32GB一部</p><p>第3名 IPhone SE一部</p><p>第4-10名 小米5一部</p><p>第10-20名 PSP3000游戏机一部</p>",
				'max_num' => 100,
				'day_num' => 30,
				'share_title'=>$this->title,
				'share_img'=>$_W['siteroot']."addons/".$this->mod_name."/template/mobile/images/icon.jpg",
				'share_desc'=>'邀请您的朋友一起来玩'.$this->title.'吧,一起来拿奖吧',
				'ad_img'=>$_W['siteroot']."addons/".$this->mod_name."/template/mobile/images/avg.jpg",
				'share_url'=>'http://mp.weixin.qq.com/',
				'copyright'=>'',
					'start_time' => TIMESTAMP,
				'end_time' => TIMESTAMP+3600*24*30,
				'game_time'=>30,
				'game_title'=>$this->title.'-首页',
				'pagenum'=>20,
				'isok'=>1,
				'isreg'=>0,
				'c_num'=>0,
				'sharenum'=>1,
				'data'=>array(
					'mp3'=>$_W['siteroot']."addons/".$this->mod_name."/template/mobile/images/1.mp3",
						't1'=>$_W['siteroot']."addons/".$this->mod_name."/template/mobile/wh_wsjzhuagui/Public/Images/shaobing/home.png",
					't2'=>$_W['siteroot']."addons/".$this->mod_name."/template/mobile/wh_wsjzhuagui/Public/Images/shaobing/home1.png",
					
				)
			);
			
		}
		
		include $this->template('form');	
	
	}

	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		return '';
	}

	public function fieldsFormSubmit($rid) {
	global $_GPC, $_W;
	load()->func('file');
	$id = intval($_GPC['reply_id']);
	if(intval($_GPC['day_num'])>intval($_GPC['max_num'])){message('每天次数必须小于等于总次数！', '', 'error');}
	$data  = array(
		'mp3'=>$_GPC['mp3'],
		't1'=>$_GPC['t1'],'t2'=>$_GPC['t2'],
	);
			$insert = array(
			'rid' => $rid,
			'weid'=>$_W['uniacid'],
			'hd_img' => $_GPC['hd_img'],
			'hd_title' => $_GPC['hd_title'],
			'hd_desc' => $_GPC['hd_desc'],
			'max_num' => intval($_GPC['max_num']),
			'day_num' => intval($_GPC['day_num']),		
			'desc' => htmlspecialchars_decode($_GPC['desc']),
			'share_img' => $_GPC['share_img'],
			'share_title' => $_GPC['share_title'],
			'share_desc' => $_GPC['share_desc'],		
			'share_url' =>$_GPC['share_url'],
			'ad_img' =>$_GPC['ad_img'],
			'copyright' =>$_GPC['copyright'],
			'game_title' =>$_GPC['game_title'],
			'isok' =>intval($_GPC['isok']),
			'sharenum' =>intval($_GPC['sharenum']),
			'pagenum' =>intval($_GPC['pagenum']),
			'isreg' =>intval($_GPC['isreg']),
			'c_num' =>intval($_GPC['c_num']),
			'c_url' =>$_GPC['c_url'],
			'start_time'=>strtotime($_GPC['htime']['start']),
			'end_time'=>strtotime($_GPC['htime']['end']),
			'game_time'=>intval($_GPC['game_time']),
			'data'=>serialize($data),
		);
		if (empty($id)) {
			pdo_insert($this->mod_name."_reply", $insert);
		} else {
			pdo_update($this->mod_name."_reply", $insert, array('id' => $id));
		}
	}

	public function ruleDeleted($rid) {
		$row = pdo_fetchall("SELECT id  FROM ".tablename($this->mod_name."_reply")." WHERE rid = '$rid'");
		$deleteid = array();
		if (!empty($row)) {
			foreach ($row as $k => $v) {
				$deleteid[] = $v['id'];
			}
		}
		pdo_delete($this->mod_name."_reply", "id IN ('".implode("','", $deleteid)."')");
		pdo_delete($this->mod_name.'_fans',array('rid'=>$rid));
		pdo_delete($this->mod_name.'_num',array('rid'=>$rid));
		pdo_delete($this->mod_name.'_top',array('rid'=>$rid));
		
	}


}