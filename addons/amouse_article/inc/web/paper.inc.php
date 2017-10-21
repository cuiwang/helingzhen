<?php
/**
 * Created by IntelliJ IDEA.
 * User: user
 * Date: 15-3-21
 * Time: 下午12:59
 * To change this template use File | Settings | File Templates.
 */

global $_W, $_GPC;
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$weid= $_W['uniacid'] ;
load()->func('tpl');
$category = pdo_fetchall("SELECT * FROM ".tablename('fineness_article_category')." WHERE uniacid =$weid ORDER BY parentid ASC, displayorder ASC, id ASC ", array(), 'id');
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
if ($op == 'display') {
    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {
            pdo_update('fineness_article', array('displayorder' => $displayorder), array('id' => $id));
        }
        message('文章排序更新成功！', $this->createWebUrl('paper', array('op' => 'display')), 'success');
    }

    load()->func('file');
    if (!empty($_GPC['delete'])) {
        $ids= implode(",", $_GPC['delete']);
        foreach ($_GPC['delete'] as $id => $delete) {
            $row = pdo_fetch("SELECT id,rid, thumb FROM ".tablename('fineness_article')." WHERE id = :id", array(':id' => $id));
            if (empty($row)) {
                message('抱歉，文章不存在或是已经被删除！');
            }
            if (!empty($row['thumb'])) {
                file_delete($row['thumb']);
            }
            if(!empty($row['rid'])) {
                pdo_delete('rule', array('id' => $row['rid'], 'uniacid' => $_W['uniacid']));
                pdo_delete('rule_keyword', array('rid' => $row['rid'], 'uniacid' => $_W['uniacid']));
                pdo_delete('news_reply', array('rid' => $row['rid']));
            }

            pdo_delete("fineness_comment",array('aid'=>$id));

        }
        $sqls= "delete from  ".tablename('fineness_article')."  where id in(".$ids.")";
        pdo_query($sqls);
        message('删除成功！', referer(), 'success');
    }

    $pindex = max(1, intval($_GPC['page']));
    $psize = 20;
    $condition = " WHERE weid =$weid ";
    $params = array();
    if (!empty($_GPC['keyword'])) {
        $condition .= " AND title LIKE :keyword";
        $params[':keyword'] = "%{$_GPC['keyword']}%";
    }

    if (!empty($_GPC['category']['childid'])) {
        $cid = intval($_GPC['category']['childid']);
        $condition .= " AND ccate = '{$cid}'";
    } elseif (!empty($_GPC['category']['parentid'])) {
        $cid = intval($_GPC['category']['parentid']);
        $condition .= " AND pcate = '{$cid}'";
    }
    $sql ="SELECT * FROM ".tablename('fineness_article')." $condition ORDER BY createtime DESC LIMIT ".($pindex - 1) * $psize.','.$psize;

    $list = pdo_fetchall($sql, $params);
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fineness_article') .$condition );
    $pager = pagination($total, $pindex, $psize);

} elseif ($op == 'post') {
    $id = intval($_GPC['id']);
    load()->func('file');
    $pcate = $_GPC['pcate'];
    $ccate = $_GPC['ccate'];
    $pindex = max(1, intval($_GPC['page']));
    if ($id>0) {
        $item = pdo_fetch("SELECT * FROM ".tablename('fineness_article')." WHERE id = :id" , array(':id' => $id));
        
        if (empty($item)) {
            message('抱歉，文章不存在或是已经删除！', '', 'error');
        }
        $pcate = $item['pcate'];
        $ccate = $item['ccate'];
         $keywords = pdo_fetchcolumn('SELECT content FROM ' . tablename('rule_keyword') . ' WHERE id = :id AND uniacid = :uniacid ', array(':id' => $item['kid'], ':uniacid' => $_W['uniacid']));
        $item['type'] = explode(',', $item['type']);
        $item['credit'] = iunserializer($item['credit']) ? iunserializer($item['credit']) : array();
        if(!empty($item['credit']['limit'])) {
            $credit_num = pdo_fetchcolumn('SELECT SUM(credit_value) FROM ' . tablename('mc_handsel') . ' WHERE uniacid = :uniacid AND module = :module AND sign = :sign', array(':uniacid' => $_W['uniacid'], ':module' => 'article', ':sign' => md5(iserializer(array('id' => $id)))));
            if(is_null($credit_num)) $credit_num = 0;
            $credit_yu = (($item['credit']['limit'] - $credit_num) < 0) ? 0 : $item['credit']['limit'] - $credit_num;
        }
    } else{
        $item['bg_music_switch'] ='1';
        $item['credit'] = array();
        $item['template']==5;
        $item['iscomment']==0;
        $item['isadmire']==0;
    }

    if (checksubmit('submit')) {
        empty($_GPC['title']) ? message('亲,标题不能为空') : $title= $_GPC['title'];
        $data = array(
            'weid' => $_W['uniacid'],
            'pcate' => intval($_GPC['category']['parentid']),
            'ccate' => intval($_GPC['category']['childid']),
            'template' => $_GPC['template'],
            'templatefile' => "themes/detail".$_GPC['template'],
            'title' => $title,
            'content' =>htmlspecialchars_decode($_GPC['content'], ENT_QUOTES),
            'description' => $_GPC['description'],
            'bg_music_switch'=>$_GPC['bg_music_switch'],
            'displayorder' => intval($_GPC['displayorder']),
            'outLink' => $_GPC['outLink'],
            'tel' => $_GPC['tel'],
            'clickNum'=> $_GPC['clickNum'],
            'zanNum'=>intval($_GPC['zanNum']),
            'author'=>$_GPC['author'],
            'isadmire'=>$_GPC['isadmire'],
			'iscomment'=>$_GPC['iscomment'],
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
			$reply['url'] =	$_W['siteroot'].'app/'.substr($this->createMobileUrl('detail',array('id'=>$item[id]),true),2);
           
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

} elseif ($op == 'delete') {
    load()->func('file');
    $id = intval($_GPC['id']);
    $row = pdo_fetch("SELECT id,rid, thumb FROM ".tablename('fineness_article')." WHERE id = :id", array(':id' => $id));
    if (empty($row)) {
        message('抱歉，文章不存在或是已经被删除！');
    }
    if (!empty($row['thumb'])) {
        file_delete($row['thumb']);
    }
    if(!empty($row['rid'])) {
        pdo_delete('rule', array('id' => $row['rid'], 'uniacid' => $_W['uniacid']));
        pdo_delete('rule_keyword', array('rid' => $row['rid'], 'uniacid' => $_W['uniacid']));
        pdo_delete('news_reply', array('rid' => $row['rid']));
    }
    pdo_delete("fineness_comment",array('aid'=>$id));
    pdo_delete('fineness_article', array('id' => $id));

    message('删除成功！', referer(), 'success');
} elseif ($op == 'setstatus') {

    $id   = intval($_GPC['id']);
    $data = intval($_GPC['data']);
    $type = $_GPC['type'];
    $data = ($data == 1 ? '0' : '1');
    pdo_update('fineness_article', array($type=> $data), array( "id" => $id,"weid" => $_W['uniacid']));
    die(json_encode(array(
        'result' => 1,
        'data' => $data
    )));

}elseif($op=='setadmire'){//设置赞赏
    $articleid     = intval($_GPC['articleid']);
    $item = pdo_fetch("SELECT * FROM ".tablename('fineness_article')." WHERE id = :id" , array(':id' => $articleid));
    $adsets= pdo_fetchall("SELECT * FROM ".tablename('fineness_admire_set')." WHERE aid = :aid ORDER BY displayorder ASC ",array(':aid'=>$articleid));

    if (checksubmit('submit')) {
        for ($i = 0; $i < count($_GPC['price']); $i++) {
            $ids = $_GPC['ids'];
            $id = trim(implode(',', $ids), ',');
            $insert = array(
                'price' => $_GPC['price'][$i],
                'displayorder'=>$_GPC['displayorder'][$i],
                'aid'=>$articleid,
                'weid' => $_W['uniacid'],
                'createtime' => TIMESTAMP,
            );
            if ($ids[$i] != NULL) {
                pdo_update("fineness_admire_set", $insert, array('id' => $ids[$i]));
            } else {
                pdo_insert("fineness_admire_set", $insert);
            }
        }
        message('更新信息成功', referer(), 'success');
    }
}elseif ($op == 'deletead') {
    $id = intval($_GPC['id']);
    pdo_delete("fineness_admire_set",array('id'=>$id));
    message('删除成功！', referer(), 'success');
}

include $this->template('article');