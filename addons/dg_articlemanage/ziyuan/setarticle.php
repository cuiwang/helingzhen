<?php
/**
 * Created by PhpStorm.
 * User: chengbin
 * Date: 2016/9/28
 * Time: 18:34
 */

require '../framework/bootstrap.inc.php';
require IA_ROOT . '/web/common/bootstrap.sys.inc.php';
load()->web('common');
load()->web('template');
global $_W,$_GPC;
load()->func('tpl');
if(empty($_GPC['key'])){
    echo "你的访问无效";
    exit();
}
$key=$_GPC['key'];
$sql="select * from ".tablename('dg_article')." where `key`='$key'";
$ainfo=pdo_fetch($sql);
$uniacid=$ainfo['uniacid'];
$category = pdo_fetchall("SELECT * FROM ".tablename('dg_article_category')." WHERE uniacid =:uniacid ORDER BY parentid ASC,displayorder,id ", array(":uniacid"=>$uniacid));
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
$id = intval($ainfo['id']);
load()->func('file');
$pcate = $_GPC['pcate'];
$ccate = $_GPC['ccate'];
if ($id>0) {
    $item = pdo_fetch("SELECT * FROM ".tablename('dg_article')." WHERE id = :id" , array(':id' => $id));

    if (empty($item)) {
        message('抱歉，文章不存在或是已经删除！', '', 'error');
    }
    $pcate = $item['pcate'];
    $ccate = $item['ccate'];
    $akeywords = pdo_fetchcolumn('SELECT content FROM ' . tablename('rule_keyword') . ' WHERE id = :id AND uniacid = :uniacid ', array(':id' => $item['kid'], ':uniacid' => $_W['uniacid']));
    $item['type'] = explode(',', $item['type']);
    $item['credit'] = iunserializer($item['credit']) ? iunserializer($item['credit']) : array();
    if(!empty($item['credit']['limit'])) {
        $credit_num = pdo_fetchcolumn('SELECT SUM(credit_value) FROM ' . tablename('mc_handsel') . ' WHERE uniacid = :uniacid AND module = :module AND sign = :sign', array(':uniacid' => $_W['uniacid'], ':module' => 'dg_article', ':sign' => md5(iserializer(array('id' => $id)))));
        if(is_null($credit_num)) $credit_num = 0;
        $credit_yu = (($item['credit']['limit'] - $credit_num) < 0) ? 0 : $item['credit']['limit'] - $credit_num;
    }
    $ausql="select * from ".tablename('dg_article_author')." where id=:aid and uniacid=:uniacid";
    $parms=array(":aid"=>$item['author_id'],":uniacid"=>$uniacid);
    $saler=pdo_fetch($ausql,$parms);
} else{
    $item['bg_music_switch'] ='1';
    $item['credit'] = array();
}


if ($_GPC['submit']=="提交") {
    empty($_GPC['title']) ? message('亲,标题不能为空') : $title= $_GPC['title'];
    empty($_GPC['category']['parentid']) ? message('亲,必选选择一级分类') : $_GPC['category']['parentid'];
    empty($_GPC['category']['childid']) ? message('亲,必选选择二级分类') : $_GPC['category']['childid'];

    $data = array(
        'uniacid' => $uniacid,
        'pcate' => intval($_GPC['category']['parentid']),
        'ccate' => intval($_GPC['category']['childid']),
        'title' => $title,
        'pay_money'=>$_GPC['pay_money'],
        'content' => htmlspecialchars_decode($_GPC['content']),
        'description' => $_GPC['description'],
        'displayorder' => intval($_GPC['displayorder']),
        'tel' => $_GPC['tel'],
        'clickNum'=> $_GPC['clickNum'],
        'zanNum'=>intval($_GPC['zanNum']),
        'author'=>$_GPC['author'],
        'createtime' => TIMESTAMP,
        'author_id'=>$ainfo['author_id'],
        'pay_num'=>$_GPC['pay_num'],
        'bg_music'=>$_GPC['bg_music'],
        'bg_music_set'=>$_GPC['bg_music_set']
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
    if(!empty($_GPC['akeyword'])) {
        $rule['uniacid'] = $_W['uniacid'];
        $rule['name'] = '文章：' . $_GPC['title'] . ' 触发规则';
        $rule['module'] = 'news';
        $rule['status'] = 1;

        $keyword = array('uniacid' => $_W['uniacid']);
        $keyword['module'] = 'news';
        $keyword['content'] = $_GPC['akeyword'];
        $keyword['status'] = 1;
        $keyword['type'] = 1;
        $keyword['displayorder'] = 1;
        $reply['title'] = $_GPC['title'];
        $reply['description'] = $_GPC['description'];
        $reply['thumb'] = $data['thumb'];
        $reply['url'] = murl('entry/module/detail', array('m'=>'dg_articlemanage','id' => $id));

    }
//				if(!empty($_GPC['credit']['status'])) {
//					$credit['status'] = intval($_GPC['credit']['status']);
//					$credit['limit'] = intval($_GPC['credit']['limit']) ? intval($_GPC['credit']['limit']) : message('请设置积分上限');
//					$credit['share'] = intval($_GPC['credit']['share']) ? intval($_GPC['credit']['share']) : message('请设置分享时赠送积分多少');
//					$credit['click'] = intval($_GPC['credit']['click']) ? intval($_GPC['credit']['click']) : message('请设置阅读时赠送积分多少');
//					$data['credit'] = iserializer($credit);
//				} else {
//					$data['credit'] = iserializer(array('status' => 0, 'limit' => 0, 'share' => 0, 'click' => 0));
//				}

    if (!empty($_FILES['thumb']['tmp_name'])) {
        file_delete($_GPC['thumb_old']);
        $upload = file_upload($_FILES['thumb']);
        if (is_error($upload)) {
            message($upload['message'], '', 'error');
        }
        $data['thumb'] = $upload['path'];
    }
/*    if (empty($id)) {*/
        if(!empty($_GPC['akeyword'])) {
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
        pdo_update('dg_article', $data,array('id'=>$id));
        /*$aid = pdo_insertid();*/
        pdo_update('news_reply', array('url' => murl('entry/module/detail', array('m'=>'dg_articlemanage', 'id' => $id))), array('rid' => $rid));

   /* }else {
        unset($data['createtime']);
        pdo_delete('rule', array('id' => $item['rid'], 'uniacid' => $_W['uniacid']));
        pdo_delete('rule_keyword', array('rid' => $item['rid'], 'uniacid' => $_W['uniacid']));
        pdo_delete('news_reply', array('rid' => $item['rid']));

        if(!empty($_GPC['akeyword'])) {
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
        pdo_update('dg_article', $data, array('id' => $id));
    }*/
    message('文章更新成功！','', 'success');
}
template('setarticle');
/*include IA_ROOT.'/web/setarticle.html';*/


