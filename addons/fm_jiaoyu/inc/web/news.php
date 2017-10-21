<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */

global $_GPC, $_W;

$action            = 'article';
$action1           = 'news';
$this1             = 'no3';
$GLOBALS['frames'] = $this->getNaveMenu($_GPC['schoolid'], $action);
$schoolid          = intval($_GPC['schoolid']);
$logo              = pdo_fetch("SELECT logo,title FROM " . tablename($this->table_index) . " WHERE id = '{$schoolid}'");
$weid              = $_W['uniacid'];
$operation         = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if($operation == 'display'){
    $pindex    = max(1, intval($_GPC['page']));
    $psize     = 20;
    $condition = '';
    $params    = array();
    if(!empty($_GPC['keyword'])){
        $condition          .= " AND title LIKE :keyword";
        $params[':keyword'] = "%{$_GPC['keyword']}%";
    }

    $list  = pdo_fetchall("SELECT * FROM " . tablename($this->table_news) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' And type = 'news' $condition ORDER BY displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_news) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' And type = 'news'");
    $pager = pagination($total, $pindex, $psize);
}elseif($operation == 'post'){
    load()->func('tpl');
    $id = intval($_GPC['id']);
    if(!empty($id)){
        $item1 = pdo_fetch("SELECT * FROM " . tablename($this->table_news) . " WHERE id = '{$id}'");
        if(empty($item1)){
            $this->imessage('抱歉，本条信息不存在在或是已经删除！', '', 'error');
        }
    }
    if(checksubmit('submit')){

        $data = array(
            'weid'         => $weid,
            'schoolid'     => $_GPC['schoolid'],
            'title'        => trim($_GPC['title']),
            'content'      => trim($_GPC['content']),
            'thumb'        => trim($_GPC['thumb']),
            'description'  => trim($_GPC['description']),
            'author'       => trim($_GPC['author']),
            'is_display'   => 1,
            'is_show_home' => 1,
            'click'        => intval($_GPC['click']),
            'dianzan'      => intval($_GPC['dianzan']),
            'displayorder' => intval($_GPC['displayorder']),
            'type'         => 'news',
            'createtime'   => time(),
        );

        if(!empty($id)){
            pdo_update($this->table_news, $data, array('id' => $id));
        }else{
            pdo_insert($this->table_news, $data);
        }
        $this->imessage('发布成功！', $this->createWebUrl('news', array('op' => 'display', 'schoolid' => $schoolid)), 'success');
    }
}elseif($operation == 'delete'){
    $id   = intval($_GPC['id']);
    $news = pdo_fetch("SELECT id FROM " . tablename($this->table_news) . " WHERE id = '$id'");
    if(empty($news)){
        $this->imessage('抱歉，文章不存在或是已经被删除！', $this->createWebUrl('news', array('op' => 'display', 'schoolid' => $schoolid)), 'error');
    }
    pdo_delete($this->table_news, array('id' => $id), 'OR');
    $this->imessage('文章删除成功！', $this->createWebUrl('news', array('op' => 'display', 'schoolid' => $schoolid)), 'success');
}
include $this->template('web/news');
?>