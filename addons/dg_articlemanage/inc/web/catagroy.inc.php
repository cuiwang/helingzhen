<?php
/**
 * Created by PhpStorm.
 * User: chengbin
 * Date: 2016/8/18
 * Time: 10:47
 */
global $_W, $_GPC;
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$uniacid= $_W['uniacid'];

if ($op == 'display') {
    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {
            $update = array('displayorder' => $displayorder);
            pdo_update('dg_article_category', $update, array('id' => $id));
        }
        message('分类排序更新成功！', 'refresh', 'success');
    }
    $children = array();
    $category = pdo_fetchall("SELECT * FROM ".tablename('dg_article_category')." WHERE uniacid =".$uniacid." ORDER BY parentid, displayorder DESC, id");
    foreach ($category as $index => $row) {
        if (!empty($row['parentid'])){
            $children[$row['parentid']][] = $row;
            unset($category[$index]);
        }
    }

} elseif ($op == 'post') {
    load()->func('tpl');
    $parentid = intval($_GPC['parentid']);
    $id = intval($_GPC['id']);
    if(!empty($id)) {
        $category = pdo_fetch("SELECT * FROM ".tablename('dg_article_category')." WHERE id = '$id' AND uniacid =$uniacid ");
        if(empty($category)) {
            message('分类不存在或已删除', '', 'error');
        }
        $cateurl=$_W['siteroot'].'app/'.substr($this->createMobileUrl('payred_index',array('pid'=>$category['id']),true),2);
    }
    if (!empty($parentid)) {
        $parent = pdo_fetch("SELECT id, name FROM ".tablename('dg_article_category')." WHERE id = '$parentid'");
        if (empty($parent)) {
            message('抱歉，上级分类不存在或是已经被删除！', $this->createWebUrl('catagroy', array('do' => 'display')), 'error');
        }
        $cateurl=$_W['siteroot'].'app/'.substr($this->createMobileUrl('payred_index',array('cid'=>$category['id'],'pid'=>$parentid),true),2);
    }

    $category['type'] = explode(',', $category['type']);
    $keywords = pdo_fetchcolumn('SELECT content FROM ' . tablename('rule_keyword') . ' WHERE id = :id AND uniacid = :uniacid ', array(':id' => $category['kid'], ':uniacid' => $uniacid));


    if (checksubmit('submit')) {
        if (empty($_GPC['cname'])) {
            message('抱歉，请输入分类名称！');
        }
        $data = array(
            'uniacid' => $uniacid,
            'name' => $_GPC['cname'],
            'displayorder' => intval($_GPC['displayorder']),//排序ID
            'parentid' => intval($parentid),//父类ID
            'description' => $_GPC['description'],//描述
            'thumb' => $_GPC['thumb'],
            'createtime' => TIMESTAMP
        );
        if(!empty($_GPC['keyword'])) {
            $rule['uniacid'] = $uniacid;
            $rule['name'] = '文章分类：' . $_GPC['cname'] . ' 触发规则';
            $rule['module'] = 'news';
            $rule['status'] = 1;

            $keyword = array('uniacid' => $uniacid);
            $keyword['module'] = 'news';
            $keyword['content'] = $_GPC['keyword'];
            $keyword['status'] = 1;
            $keyword['type'] = 1;
            $keyword['displayorder'] = 1;

            $reply['title'] = $_GPC['cname'];
            $reply['description'] = $_GPC['description'];
            $reply['thumb'] = $_GPC['thumb'];
            $reply['displayorder'] = $_GPC['displayorder'];
            $reply['url'] = murl('entry/module/index', array('m'=>'dg_articlemanage','cid' => $id));
        }

        if (!empty($id)) {
            unset($data['parentid']);
            pdo_delete('rule', array('id' => $category['rid'], 'uniacid' => $uniacid));
            pdo_delete('rule_keyword', array('rid' => $category['rid'], 'uniacid' => $uniacid));
            pdo_delete('news_reply', array('rid' => $category['rid']));

            if(!empty($_GPC['keyword'])) {
                pdo_insert('rule', $rule);
                $rid = pdo_insertid();

                $keyword['rid'] = $rid;
                pdo_insert('rule_keyword', $keyword);
                $kid = pdo_insertid();
                $reply['rid'] = $rid;
                $alist = pdo_fetchall("SELECT * FROM ".tablename('dg_article')." WHERE uniacid= $uniacid AND ccate=$id  ORDER BY displayorder ASC limit 8 ") ;
                if($alist){
                    foreach($alist as $par) {
                        $reply2['title'] = $par['title'];
                        $reply2['description'] = $par['description'];
                        $reply2['thumb'] = $par['thumb'];
                        $reply2['url'] = murl('entry/module/detail', array('m'=>'dg_articlemanage','id' => $par['id']));
                        $reply2['rid'] = $rid;
                        pdo_insert('news_reply', $reply2);
                    }
                }else{
                    pdo_insert('news_reply', $reply);
                    $data['rid'] = $rid;
                    $data['kid'] = $kid;
                }
            } else {
                $data['rid'] = 0;
                $data['kid'] = 0;
            }
            pdo_update('dg_article_category', $data, array('id' => $id));
        } else {

            if(!empty($_GPC['keyword'])) {
                pdo_insert('rule', $rule);
                $rid = pdo_insertid();

                $keyword['rid'] = $rid;
                pdo_insert('rule_keyword', $keyword);
                $kid = pdo_insertid();

                $reply['rid'] = $rid;
                $data['rid'] = $rid;
                $data['kid'] = $kid;
            }
            pdo_insert('dg_article_category', $data);
            $aid = pdo_insertid();
            if(!empty($_GPC['keyword'])) {//关键字
                $alist = pdo_fetchall("SELECT * FROM ".tablename('dg_article')." WHERE uniacid={$uniacid} AND ccate={$aid}  ORDER BY displayorder ASC limit 8 ") ;
                if($alist){
                    foreach($alist as $par) {
                        $reply2['title'] = $par['title'];
                        $reply2['description'] = $par['description'];
                        $reply2['thumb'] = $par['thumb'];
                        $reply2['url'] = murl('entry/module/detail', array('m'=>'dg_articlemanage','id' => $par[id]));
                        $reply2['rid'] = $rid;
                        pdo_insert('news_reply', $reply2);
                    }
                }else{
                    $reply['title'] = $_GPC['cname'];
                    $reply['description'] = $_GPC['description'];
                    $reply['thumb'] = $_GPC['thumb'];
                    $reply['displayorder'] = $_GPC['displayorder'];
                    $reply['url'] = murl('entry/module/index', array('m'=>'dg_articlemanage','cid' => $aid));
                    pdo_insert('news_reply', $reply);
                }
            }
        }
        message('更新分类成功！', $this->createWebUrl('catagroy', array('do' => 'display')), 'success');
    }
} elseif ($op == 'fetch') {
    $category = pdo_fetchall("SELECT id, name FROM ".tablename('dg_article_category')." WHERE parentid = '".intval($_GPC['parentid'])."' ORDER BY id ASC, displayorder ASC, id ASC ");
    message($category, '', 'ajax');
} elseif ($op == 'delete') {
    load()->func('file');
    $id = intval($_GPC['id']);
    pdo_delete('dg_article_category', array('id' => $id));
    message('分类删除成功！', $this->createWebUrl('catagroy', array('do' => 'display')), 'success');
}

include $this->template('catagroy');