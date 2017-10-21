<?php
global $_GPC, $_W;
	  $weid = $_W['uniacid'];
		$cfg = $this->module['config'];
		$rid = intval($_GPC['rid']);
		$ridwall = pdo_fetch("SELECT * FROM ".tablename('weixin_wall_reply')." WHERE weid=:weid AND rid = :rid LIMIT 1", array(':weid'=>$weid,':rid'=>$rid));
        $data['luckMap']['map']=  array(
		       'buttonurl'=>'',
			   'id'=>$rid,
			   'imgurl'=>!empty($ridwall['cjimgurl']) ? $_W['attachurl'].$ridwall['cjimgurl'] : '',
			  'name'=>$ridwall['cjname'],
			  'num_exclude'=>intval($ridwall['cjnum_exclude']),
			  'num_tag'=>intval($ridwall['cjnum_tag']),
			  'tag_exclude'=>intval($ridwall['cjnum_exclude']),
		);
		
        $tag = pdo_fetchall("SELECT * FROM ".tablename('weixin_awardlist')." WHERE weid=:weid AND luckid=:luckid ORDER BY displayid ASC",array(':weid'=>$weid,':luckid'=>$rid));
				$data['luckMap']['tagList']=  $tag;


        die(json_encode($data));