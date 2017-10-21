<?php
defined('IN_IA') or exit('Access Denied');
class Cyl_phoneModuleSite extends WeModuleSite
{
    private $tb_category = 'cyl_phone_category';
    private $tb_business = 'cyl_phone_business';
    private $tb_individual = 'cyl_phone_individual';
    private $tb_paihang = 'cyl_phone_paihang';
    private $tb_message = 'cyl_phone_message';
    private $tb_order = 'cyl_phone_order';
    private $tb_weixin = 'cyl_phone_weixin';
    private $url = '/addons/cyl_phone/template/mobile/';
    private function getStatus($status)
    {
        $status = intval($status);
        if ($status == 1) {
            return '审核通过';
        } else {
            return '待审核';
        }
    }
    private function getAllCategory()
    {
        global $_W;
        $sql        = 'SELECT * FROM ' . tablename($this->tb_category) . ' WHERE uniacid=:uniacid  ORDER BY `orderno` asc ';
        $params     = array(
            ':uniacid' => $_W['uniacid']
        );
        $categories = pdo_fetchall($sql, $params, 'id');
        return $categories;
    }
    public function doMobileIndex()
    {
        global $_W, $_GPC;
        $advs               = pdo_fetchall("SELECT * FROM " . tablename('cyl_phone_adv') . " WHERE uniacid='{$_W['uniacid']}' ORDER BY displayorder DESC");
        $hot                = pdo_fetchall("SELECT * FROM " . tablename($this->tb_business) . " WHERE uniacid='{$_W['uniacid']}' AND status = 1 AND recommended = 1  ORDER BY click DESC LIMIT 12");
        $op                 = $_GPC['op'] ? $_GPC['op'] : 'new';
        $condition          = ' uniacid = :uniacid AND status = 1';
        $params[':uniacid'] = $_W['uniacid'];
        if (!empty($_GPC['keyword'])) {
            $condition .= " AND title LIKE '%{$_GPC['keyword']}%'";
        }
        $cid = $_GPC['cid'];
        if ($cid != 0) {
            $condition .= " AND categoryid={$cid}";
        }
        $pindex   = max(1, intval($_GPC['page']));
        $psize    = 50;
        $total    = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->tb_business) . ' WHERE ' . $condition, $params);
        $business = pdo_fetchall('SELECT * FROM ' . tablename($this->tb_business) . ' WHERE ' . $condition . ' ORDER BY id DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $params);
        if ($op == 'hot') {
            $business = pdo_fetchall('SELECT * FROM ' . tablename($this->tb_business) . ' WHERE ' . $condition . ' ORDER BY click DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $params);
        }
        if ($op == 'new') {
            $business = pdo_fetchall('SELECT * FROM ' . tablename($this->tb_business) . ' WHERE ' . $condition . ' ORDER BY id DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $params);
        }
        $pager          = pagination($total, $pindex, $psize);
        $sql            = 'SELECT * FROM ' . tablename($this->tb_category) . ' WHERE uniacid=:uniacid AND rid = 0  ORDER BY `orderno` asc';
        $params         = array(
            ':uniacid' => $_W['uniacid']
        );
        $categorieshome = pdo_fetchall($sql, $params);
        $categories     = $this->getAllCategory();
        $settings       = $this->module['config'];
        $_share         = array(
            'desc' => $settings['share_desc'],
            'title' => $settings['share_title'],
            'imgUrl' => tomedia($settings['thumb'])
        );
        load()->func('tpl');
        $title = "便民电话";
        include $this->template('index');
    }
    public function doMobilePaihang()
    {
        global $_W, $_GPC;
        $advs               = pdo_fetchall("SELECT * FROM " . tablename('cyl_phone_adv') . " WHERE uniacid='{$_W['uniacid']}' ORDER BY displayorder DESC");
        $hot                = pdo_fetchall("SELECT * FROM " . tablename($this->tb_business) . " WHERE uniacid='{$_W['uniacid']}' AND status = 1  ORDER BY click DESC LIMIT 12");
        $op                 = $_GPC['op'] ? $_GPC['op'] : 'day';
        $condition          = ' uniacid = :uniacid AND status = 1';
        $params[':uniacid'] = $_W['uniacid'];
        if (!empty($_GPC['keyword'])) {
            $condition .= " AND title LIKE '%{$_GPC['keyword']}%'";
        }
        $cid = $_GPC['cid'];
        if ($cid != 0) {
            $condition .= " AND categoryid={$cid}";
        }
        $pindex  = max(1, intval($_GPC['page']));
        $psize   = 50;
        $total   = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->tb_business) . ' WHERE ' . $condition, $params);
        $article = pdo_fetchall('SELECT * FROM ' . tablename($this->tb_business) . ' WHERE ' . $condition, $params, 'id');
        if ($op == 'day') {
            $business = pdo_fetchall("SELECT * FROM " . tablename($this->tb_paihang) . " WHERE uniacid = :uniacid AND date_format(from_UNIXTIME(`time`),'%Y-%m-%d') = date_format(now(),'%Y-%m-%d') ORDER BY click DESC LIMIT 50", array(
                ':uniacid' => $_W['uniacid']
            ));
        }
        if ($op == 'week') {
            $business = pdo_fetchall("SELECT *,SUM(click) as click FROM " . tablename($this->tb_paihang) . " WHERE uniacid = :uniacid AND date_format(from_UNIXTIME(`time`),'%Y-%m-%d') >= date_format(date_sub(date_sub(now(), INTERVAL WEEKDAY(NOW()) DAY), INTERVAL 1 WEEK), '%Y-%m-%d') GROUP BY business_id ORDER BY click DESC LIMIT 50", array(
                ':uniacid' => $_W['uniacid']
            ));
        }
        if ($op == 'month') {
            $business = pdo_fetchall("SELECT *,SUM(click) as click FROM " . tablename($this->tb_paihang) . " WHERE uniacid = :uniacid AND date_format(from_UNIXTIME(`time`),'%Y-%m') = date_format(now(),'%Y-%m') GROUP BY business_id ORDER BY click DESC LIMIT 50", array(
                ':uniacid' => $_W['uniacid']
            ));
        }
        if ($op == 'year') {
            $business = pdo_fetchall("SELECT *,SUM(click) as click FROM " . tablename($this->tb_paihang) . " WHERE uniacid = :uniacid AND date_format(from_UNIXTIME(`time`),'%Y') = date_format(now(),'%Y') GROUP BY business_id ORDER BY click DESC LIMIT 50", array(
                ':uniacid' => $_W['uniacid']
            ));
        }
        $pager          = pagination($total, $pindex, $psize);
        $sql            = 'SELECT * FROM ' . tablename($this->tb_category) . ' WHERE uniacid=:uniacid AND rid = 0  ORDER BY `orderno` asc';
        $params         = array(
            ':uniacid' => $_W['uniacid']
        );
        $categorieshome = pdo_fetchall($sql, $params, 'id');
        $categories     = pdo_getall($this->tb_category, array(
            'uniacid' => $_W['uniacid']
        ), array(), 'id');
        ;
        $settings = $this->module['config'];
        $_share   = array(
            'desc' => $settings['share_desc'],
            'title' => $settings['share_title'],
            'imgUrl' => tomedia($settings['thumb'])
        );
        load()->func('tpl');
        $title = "便民电话";
        include $this->template('paihang');
    }
    public function doMobileContent()
    {
        global $_W, $_GPC;
        load()->func('tpl');
        $id = intval($_GPC['id']);
        load()->model('mc');
        $settings    = $this->module['config'];
        $business    = pdo_fetch('SELECT * FROM ' . tablename($this->tb_business) . ' WHERE uniacid = :uniacid AND id = :id', array(
            ':uniacid' => $_W['uniacid'],
            ':id' => $id
        ));
        $messagelist = pdo_fetchall('SELECT * FROM ' . tablename($this->tb_message) . ' WHERE uniacid = :uniacid AND contentid = :contentid ', array(
            ':uniacid' => $_W['uniacid'],
            ':contentid' => $id
        ));
        $hot         = pdo_fetchall("SELECT * FROM " . tablename($this->tb_business) . " WHERE uniacid='{$_W['uniacid']}' AND status = 1 ORDER BY  RAND() LIMIT 12 ");
        $openid      = $_W['fans']['openid'];
        $nickname    = $_W['fans']['nickname'];
        $categories  = $this->getAllCategory();
        if (checksubmit()) {
            if (empty($_W['fans']['nickname'])) {
                $fans = mc_oauth_userinfo();
            }
            $openid            = $_W['openid'];
            $data              = $_GPC['business'];
            $data['nickname']  = $_W['fans']['tag']['nickname'];
            $data['uniacid']   = $_W['uniacid'];
            $data['contentid'] = $id;
            $data['avatar']    = $_W['fans']['tag']['avatar'];
            $data['openid']    = $openid;
            $data['time']      = TIMESTAMP;
            $ret               = pdo_insert($this->tb_message, $data);
            if (!empty($settings['templateid'])) {
                $kdata = array(
                    'first' => array(
                        'value' => '有人给您的店铺留言了',
                        'color' => '#ff510'
                    ),
                    'keyword1' => array(
                        'value' => $data['nickname'],
                        'color' => '#ff510'
                    ),
                    'keyword2' => array(
                        'value' => $data['content'],
                        'color' => '#ff510'
                    ),
                    'remark' => array(
                        'value' => '请进入店铺进行查看',
                        'color' => '#ff510'
                    )
                );
                $url   = $_W['siteroot'] . 'app' . ltrim(murl('entry', array(
                    'do' => 'content',
                    'm' => 'cyl_phone',
                    'id' => $id
                )), '.');
                $acc   = WeAccount::create();
                $acc->sendTplNotice($business['openid'], $settings['templateid'], $kdata, $url, $topcolor = '#FF683F');
            }
            if (!empty($ret)) {
                message('留言成功', $this->createMobileUrl('content', array(
                    'id' => $id
                )), 'success');
            } else {
                message('留言失败');
            }
        }
        $business['click'] = intval($business['click']) + 1;
        pdo_update($this->tb_business, array(
            'click' => $business['click']
        ), array(
            'uniacid' => $_W['uniacid'],
            'id' => $id
        ));
        $paihang = pdo_fetch("SELECT * FROM " . tablename($this->tb_paihang) . " WHERE uniacid = :uniacid AND business_id = :business_id AND date_format(from_UNIXTIME(`time`),'%Y-%m-%d') = date_format(now(),'%Y-%m-%d')", array(
            ':uniacid' => $_W['uniacid'],
            ':business_id' => $id
        ));
        if ($paihang) {
            pdo_update($this->tb_paihang, array(
                'click' => $paihang['click'] + 1,
                'time' => TIMESTAMP
            ), array(
                'uniacid' => $_W['uniacid'],
                'business_id' => $id,
                'id' => $paihang['id']
            ));
        } else {
            pdo_insert($this->tb_paihang, array(
                'click' => 1,
                'time' => TIMESTAMP,
                'business_id' => $id,
                'uniacid' => $_W['uniacid']
            ));
        }
        $pic    = iunserializer($hot['logo']);
        $_share = array(
            'desc' => $business['desc'],
            'title' => $business['title'],
            'imgUrl' => tomedia($pic['0'])
        );
        $title  = "便民服务";
        include $this->template('content');
    }
    public function doMobileList()
    {
        global $_W, $_GPC;
        $advs               = pdo_fetchall("SELECT * FROM " . tablename('cyl_phone_adv') . " WHERE uniacid='{$_W['uniacid']}' ORDER BY displayorder DESC");
        $hot                = pdo_fetchall("SELECT * FROM " . tablename($this->tb_business) . " WHERE uniacid='{$_W['uniacid']}' AND status = 1  ORDER BY click DESC LIMIT 12");
        $op                 = $_GPC['op'];
        $condition          = ' uniacid = :uniacid AND status = 1';
        $params[':uniacid'] = $_W['uniacid'];
        if (!empty($_GPC['keyword'])) {
            $condition .= " AND title LIKE '%{$_GPC['keyword']}%'";
        }
        $cid = $_GPC['cid'];
        if ($cid != 0) {
            $condition .= " AND categoryid={$cid}";
        }
        $rid = $_GPC['rid'];
        if ($rid != 0) {
            $condition .= " AND categoryid_2={$rid}";
        }
        $pindex         = max(1, intval($_GPC['page']));
        $psize          = 50;
        $total          = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->tb_business) . ' WHERE ' . $condition, $params);
        $business       = pdo_fetchall('SELECT * FROM ' . tablename($this->tb_business) . ' WHERE ' . $condition . ' ORDER BY id DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $params);
        $pager          = pagination($total, $pindex, $psize);
        $sql            = 'SELECT * FROM ' . tablename($this->tb_category) . ' WHERE uniacid=:uniacid AND  rid = :cid ORDER BY `orderno` asc';
        $params         = array(
            ':uniacid' => $_W['uniacid'],
            ':cid' => $cid
        );
        $categorieslist = pdo_fetchall($sql, $params, 'id');
        $categories     = $this->getAllCategory();
        $settings       = $this->module['config'];
        $_share         = array(
            'desc' => $settings['share_desc'],
            'title' => $settings['share_title'],
            'imgUrl' => tomedia($settings['thumb'])
        );
        load()->func('tpl');
        $title = "便民电话";
        include $this->template('list');
    }
    public function doMobileCategory()
    {
        global $_W, $_GPC;
        $pcate    = $_GPC['pcate'];
        $category = pdo_fetchall("SELECT * FROM " . tablename($this->tb_category) . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY rid, orderno DESC, id");
        foreach ($category as $index => $row) {
            if (!empty($row['rid'])) {
                $children[$row['rid']][] = $row;
                unset($category[$index]);
            }
        }
        $settings = $this->module['config'];
        include $this->template('category');
    }
    public function doWebMessage()
    {
        global $_W, $_GPC;
        load()->func('tpl');
        load()->model('mc');
        $id                 = intval($_GPC['id']);
        $condition          = ' uniacid = :uniacid ';
        $params[':uniacid'] = $_W['uniacid'];
        $pindex             = max(1, intval($_GPC['page']));
        $psize              = 20;
        $article            = pdo_fetchall('SELECT id,title FROM ' . tablename($this->tb_business) . ' WHERE ' . $condition, $params, 'id');
        $total              = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->tb_message) . ' WHERE uniacid = :uniacid ', array(
            ':uniacid' => $_W['uniacid']
        ));
        $messagelist        = pdo_fetchall('SELECT * FROM ' . tablename($this->tb_message) . ' WHERE uniacid = :uniacid  ORDER BY id DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, array(
            ':uniacid' => $_W['uniacid']
        ));
        $pager              = pagination($total, $pindex, $psize);
        $title              = "便民电话-留言列表";
        include $this->template('message');
    }
    public function doWebRemmai()
    {
        global $_W, $_GPC;
        load()->func('tpl');
        load()->model('mc');
        $id = intval($_GPC['id']);
        $op = $_GPC['op'] ? $_GPC['op'] : 'display';
        if ($op == 'display') {
            $condition          = ' uniacid = :uniacid ';
            $params[':uniacid'] = $_W['uniacid'];
            $pindex             = max(1, intval($_GPC['page']));
            $psize              = 20;
            $total              = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->tb_weixin) . ' WHERE uniacid = :uniacid ', array(
                ':uniacid' => $_W['uniacid']
            ));
            $messagelist        = pdo_fetchall('SELECT * FROM ' . tablename($this->tb_weixin) . ' WHERE uniacid = :uniacid  ORDER BY id DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, array(
                ':uniacid' => $_W['uniacid']
            ));
            $pager              = pagination($total, $pindex, $psize);
        }
        if ($op == 'weixin') {
            if (!empty($id)) {
                $sql      = 'SELECT * FROM ' . tablename($this->tb_weixin) . ' WHERE id=:id AND uniacid=:uniacid LIMIT 1';
                $params   = array(
                    ':id' => $id,
                    ':uniacid' => $_W['uniacid']
                );
                $business = pdo_fetch($sql, $params);
                $referer  = referer();
                if (empty($business)) {
                    message('未找到指定的人脉.', $this->createWebUrl('display'));
                }
            }
            $referer = referer();
            if (checksubmit()) {
                $data = $_GPC['business'];
                if (empty($business)) {
                    $data['uniacid']  = $_W['uniacid'];
                    $data['time']     = TIMESTAMP;
                    $data['openid']   = $openid;
                    $data['nickname'] = $_W['fans']['tag']['nickname'];
                    $ret              = pdo_insert($this->tb_weixin, $data);
                    if (!empty($ret)) {
                        $id = pdo_insertid();
                    }
                } else {
                    $ret = pdo_update($this->tb_weixin, $data, array(
                        'id' => $id
                    ));
                }
                if (!empty($ret)) {
                    message('信息变更成功', $this->createWebUrl('remmai'), 'success');
                } else {
                    message('信息保存失败');
                }
            }
        }
        if ($op == 'delete') {
            $id = intval($_GPC['id']);
            if (empty($id)) {
                message('未找到指定商家分类');
            }
            $result = pdo_delete($this->tb_weixin, array(
                'id' => $id,
                'uniacid' => $_W['uniacid']
            ));
            if (intval($result) == 1) {
                message('删除成功.', $this->createWebUrl('remmai'), 'success');
            } else {
                message('删除失败.');
            }
        }
        $title = "便民电话";
        include $this->template('renmai');
    }
    public function doMobileUser()
    {
        global $_W, $_GPC;
        load()->func('tpl');
        if (empty($_W['fans']['nickname'])) {
            $fans = mc_oauth_userinfo();
        }
        include $this->template('user');
    }
    public function doMobileRenmai()
    {
        global $_W, $_GPC;
        $op                 = $_GPC['op'] ? $_GPC['op'] : '1';
        $cate               = array(
            '1' => '商家',
            '2' => '美女',
            '3' => '帅哥',
            '4' => '推荐'
        );
        $condition          = ' uniacid = :uniacid ';
        $params[':uniacid'] = $_W['uniacid'];
        if ($op == '1') {
            $business = pdo_fetchall('SELECT * FROM ' . tablename($this->tb_business) . ' WHERE ' . $condition . ' ORDER BY click DESC ', $params);
        }
        if ($op == '2') {
            $business = pdo_fetchall('SELECT * FROM ' . tablename($this->tb_weixin) . ' WHERE sex =  2 AND ' . $condition . ' ORDER BY time DESC ', $params);
        }
        if ($op == '3') {
            $business = pdo_fetchall('SELECT * FROM ' . tablename($this->tb_weixin) . ' WHERE sex =  1 AND ' . $condition . ' ORDER BY time DESC ', $params);
        }
        if ($op == '4') {
            $business = pdo_fetchall('SELECT * FROM ' . tablename($this->tb_weixin) . ' WHERE recommended =  1 AND ' . $condition . ' ORDER BY time DESC ', $params);
        }
        load()->func('tpl');
        if (empty($_W['fans']['nickname'])) {
            $fans = mc_oauth_userinfo();
        }
        include $this->template('renmai');
    }
    public function doMobileMybusiness()
    {
        global $_W, $_GPC;
        load()->func('file');
        load()->func('tpl');
        load()->model('mc');
        $settings = $this->module['config'];
        $ops      = array(
            'display',
            'edit',
            'delete',
            'weixin',
            'wx'
        );
        $op       = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'display';
        if (empty($_W['fans']['nickname'])) {
            $fans = mc_oauth_userinfo();
        }
        $openid   = $_W['openid'];
        $category = pdo_fetchall("SELECT id,rid,name FROM " . tablename('cyl_phone_category') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY rid ASC, id ASC ", array(), 'id');
        $parent   = array();
        $children = array();
        if (!empty($category)) {
            $children = '';
            foreach ($category as $id => $cate) {
                if (!empty($cate['rid'])) {
                    $children[$cate['rid']][] = $cate;
                } else {
                    $parent[$cate['id']] = $cate;
                }
            }
        }
        if ($op == 'weixin') {
            $id = intval($_GPC['id']);
            if (!empty($id)) {
                $sql      = 'SELECT * FROM ' . tablename($this->tb_weixin) . ' WHERE id=:id AND uniacid=:uniacid LIMIT 1';
                $params   = array(
                    ':id' => $id,
                    ':uniacid' => $_W['uniacid']
                );
                $business = pdo_fetch($sql, $params);
                $referer  = referer();
                if (empty($business)) {
                    message('未找到指定的人脉.', $this->createMobileUrl('display'));
                }
            }
            $referer = referer();
            if (checksubmit()) {
                $data = $_GPC['business'];
                if (empty($business)) {
                    $data['uniacid']  = $_W['uniacid'];
                    $data['time']     = TIMESTAMP;
                    $data['openid']   = $openid;
                    $data['nickname'] = $_W['fans']['tag']['nickname'];
                    $ret              = pdo_insert($this->tb_weixin, $data);
                    if (!empty($ret)) {
                        $id = pdo_insertid();
                    }
                } else {
                    $ret = pdo_update($this->tb_weixin, $data, array(
                        'id' => $id
                    ));
                }
                if (!empty($ret)) {
                    message('信息变更成功', $this->createMobileUrl('Mybusiness'), 'success');
                } else {
                    message('信息保存失败');
                }
            }
            include $this->template('add');
        }
        if ($op == 'wx') {
            $condition          = ' uniacid = :uniacid  AND openid=:openid';
            $params[':uniacid'] = $_W['uniacid'];
            $params[':openid']  = $openid;
            $pindex             = max(1, intval($_GPC['page']));
            $psize              = 20;
            $total              = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->tb_weixin) . ' WHERE ' . $condition, $params);
            $business           = pdo_fetchall('SELECT * FROM ' . tablename($this->tb_weixin) . ' WHERE ' . $condition . $strWhere . ' ORDER BY id DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $params);
            $pager              = pagination($total, $pindex, $psize);
            include $this->template('display');
        }
        if ($op == 'display') {
            $condition          = ' uniacid = :uniacid  AND openid=:openid';
            $params[':uniacid'] = $_W['uniacid'];
            $params[':openid']  = $openid;
            if (!empty($_GPC['keyword'])) {
                $condition .= " AND title LIKE '%{$_GPC['keyword']}%'";
            }
            $cid = $_GPC['cid'];
            if ($cid != 0) {
                $strWhere = " AND categoryid={$cid}";
            }
            $pindex   = max(1, intval($_GPC['page']));
            $psize    = 20;
            $total    = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->tb_business) . ' WHERE ' . $condition, $params);
            $business = pdo_fetchall('SELECT * FROM ' . tablename($this->tb_business) . ' WHERE ' . $condition . $strWhere . ' ORDER BY id DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $params);
            $pager    = pagination($total, $pindex, $psize);
            include $this->template('display');
        }
        if ($op == 'edit') {
            $id = intval($_GPC['id']);
            if (!empty($id)) {
                $sql      = 'SELECT * FROM ' . tablename($this->tb_business) . ' WHERE id=:id AND uniacid=:uniacid LIMIT 1';
                $params   = array(
                    ':id' => $id,
                    ':uniacid' => $_W['uniacid']
                );
                $business = pdo_fetch($sql, $params);
                $pcate    = $business['categoryid'];
                $ccate    = $business['categoryid_2'];
                $referer  = referer();
                if (empty($business)) {
                    message('未找到指定的商家.', $this->createMobileUrl('display'));
                }
            }
            $referer = referer();
            if (checksubmit()) {
                $data                 = $_GPC['business'];
                $data['uniacid']      = $_W['uniacid'];
				//$data['yjh']     	  = $_GPC['yjh'];
				$data['dpimg']     	  = $_GPC['dpimg'];
				$data['tx']     	  = $_GPC['tx'];
				//$data['weixin']       = $_GPC['weixin'];
				$data['ewm']     	  = $_GPC['ewm'];
                $data['time']         = TIMESTAMP;
                $data['openid']       = $openid;
                $data['nickname']     = $_W['fans']['tag']['nickname'];
                $data['categoryid']   = intval($_GPC['category']['parentid']);
                $data['categoryid_2'] = intval($_GPC['category']['childid']);
                if ($settings['status'] == 1) {
                    $data['status'] = 2;
                } else {
                    $data['status'] = 1;
                }
                if (!empty($_GPC['image'])) {
                    load()->classs('weixin.account');
                    $accObj       = new WeixinAccount();
                    $access_token = $accObj->fetch_available_token();
                    $images       = explode(",", $_GPC['image']);
                    foreach ($images as $key => $image) {
                        $url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=$access_token&media_id=$image";
                        $ch  = curl_init($url);
                        curl_setopt($ch, CURLOPT_HEADER, 0);
                        curl_setopt($ch, CURLOPT_NOBODY, 0);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        $package  = curl_exec($ch);
                        $httpinfo = curl_getinfo($ch);
                        curl_close($ch);
                        $fileInfo    = array_merge(array(
                            'header' => $httpinfo
                        ), array(
                            'body' => $package
                        ));
                        $rand        = rand(100, 999);
                        $filename    = date("YmdHis") . $rand . ".jpg";
                        $filepath    = ATTACHMENT_ROOT . "/images/cyl_phone/" . $filename;
                        $filecontent = $fileInfo["body"];
                        $dir_name    = ATTACHMENT_ROOT . "/images/cyl_phone";
                        if (!is_dir($dir_name)) {
                            $dir = mkdir($dir_name, 0777, true);
                        }
                        if (false !== $dir) {
                            $local_file = fopen($filepath, 'w');
                            if (false !== $local_file) {
                                if (false !== fwrite($local_file, $filecontent)) {
                                    fclose($local_file);
                                    $info['img'] = "images/cyl_phone/" . $filename;
                                    $thumb .= $info['img'] . ',';
                                }
                            } else {
                                message("图片上传失败，请联系管理员！", "javascript:WeixinJSBridge.call('closeWindow');", "error");
                            }
                        } else {
                            message("目录创建失败！", "javascript:WeixinJSBridge.call('closeWindow');", "error");
                        }
                    }
                }
                $thumb = rtrim($thumb, ",");
                $thumb = explode(",", $thumb);
                if ($_GPC['image']) {
                    $data['logo'] = iserializer($thumb);
                }
                if (empty($id)) {
                    $ret = pdo_insert($this->tb_business, $data);
                    if (!empty($settings['kfid']) && !empty($settings['templateid'])) {
                        $kdata = array(
                            'first' => array(
                                'value' => '便民电话入驻申请通知',
                                'color' => '#ff510'
                            ),
                            'keyword1' => array(
                                'value' => $data['title'],
                                'color' => '#ff510'
                            ),
                            'keyword2' => array(
                                'value' => $data['desc'],
                                'color' => '#ff510'
                            ),
                            'remark' => array(
                                'value' => '请进入后台进行查看',
                                'color' => '#ff510'
                            )
                        );
                        $acc   = WeAccount::create();
                        $acc->sendTplNotice($settings['kfid'], $settings['templateid'], $kdata, $topcolor = '#FF683F');
                    }
                    if (!empty($ret)) {
                        $id = pdo_insertid();
                    }
                } else {
                    $ret = pdo_update($this->tb_business, $data, array(
                        'id' => $id
                    ));
                }
                if (!empty($ret)) {
                    if ($business['pay'] == 1) {
                        message('商家信息变更成功', $this->createMobileUrl('Mybusiness'), 'success');
                    } else {
                        message('商家信息提交成功，正跳转支付页面', $this->createMobileUrl('pay', array(
                            'id' => $id
                        )), 'success');
                    }
                } else {
                    message('商家信息保存失败');
                }
            }
            load()->func('tpl');
            include $this->template('add');
        }
    }
    public function doMobilePay()
    {
        global $_W, $_GPC;
        load()->model('account');
        $settings = $this->module['config'];
        $id       = $_GPC['id'];
        $fee      = $settings['feiyong'];
        $uid      = $_GPC['uid'];
        if (empty($_W['fans']['openid'])) {
            mc_oauth_userinfo();
        }
        if (checksubmit()) {
            $fee = $settings['feiyong'];
            $id  = $_GPC['id'];
            $uid = $_GPC['uid'];
        }
        if ($fee <= 0) {
            message('支付错误, 金额小于0');
        }
        $contents = pdo_fetch("SELECT * FROM " . tablename($this->tb_business) . " WHERE id = :id", array(
            ':id' => $id
        ));
        $title    = $contents['title'];
        $params   = array(
            'module' => 'cyl_wxweizhang',
            'tid' => date('YmdHi') . random(8, 1),
            'ordersn' => date(YmdHis) . $id . $_W['member']['uid'],
            'title' => $title . "赏金",
            'fee' => $fee,
            'user' => $_W['member']['uid']
        );
        $data     = array(
            'uniacid' => $_W['uniacid'],
            'business_id' => $id,
            'tid' => $params['tid'],
            'uid' => $_W['member']['uid'],
            'openid' => $_W['openid'],
            'status' => 0,
            'time' => TIMESTAMP
        );
        pdo_insert($this->tb_order, $data);
        $this->pay($params);
    }
    public function payResult($params)
    {
        global $_W, $_GPC;
        load()->model('mc');
        load()->func('tpl');
        $order    = pdo_fetch("SELECT * FROM " . tablename($this->tb_order) . " WHERE tid = :tid", array(
            ':tid' => $params['tid']
        ));
        $contents = pdo_get($this->tb_business, array(
            'id' => $order['business_id']
        ));
        $data     = array(
            'fee' => $params['fee'],
            'status' => 1
        );
        $settings = $this->module['config'];
        if ($param['result'] == 'success' && ($param['from'] == 'notify' || $param['from'] == 'return')) {
        }
        if (empty($params['result']) || $params['result'] != 'success') {
        }
        if ($params['from'] == 'return') {
            if ($params['result'] == 'success') {
                pdo_update($this->tb_order, $data, array(
                    'tid' => $order['tid']
                ));
                pdo_update($this->tb_business, array(
                    'pay' => '1'
                ), array(
                    'id' => $order['business_id']
                ));
                load()->classs('weixin.account');
                load()->func('communication');
                $acc   = WeAccount::create($acid);
                $kdata = array(
                    'first' => array(
                        'value' => '支付成功',
                        'color' => '#ff510'
                    ),
                    'keyword1' => array(
                        'value' => $_W['uniaccount']['name'],
                        'color' => '#ff510'
                    ),
                    'keyword2' => array(
                        'value' => $contents['title'] . '支付成功',
                        'color' => '#ff510'
                    ),
                    'remark' => array(
                        'value' => '进入后台查看',
                        'color' => '#ff510'
                    )
                );
                $acc->sendTplNotice($settings['kfid'], $settings['templateid'], $kdata, $url, $topcolor = '#FF683F');
                message('支付成功', $this->createMobileUrl('user'), 'success');
            } else {
                message('支付失败！', 'error');
            }
        }
    }
    protected function pay($params = array(), $mine = array())
    {
        global $_W;
        $params['module'] = $this->module['name'];
        $sql              = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `uniacid`=:uniacid AND `module`=:module AND `tid`=:tid';
        $pars             = array();
        $pars[':uniacid'] = $_W['uniacid'];
        $pars[':module']  = $params['module'];
        $pars[':tid']     = $params['tid'];
        $log              = pdo_fetch($sql, $pars);
        if (!empty($log) && $log['status'] == '1') {
            message('这个订单已经支付成功, 不需要重复支付.');
        }
        $setting = uni_setting($_W['uniacid'], array(
            'payment',
            'creditbehaviors'
        ));
        if (!is_array($setting['payment'])) {
            message('没有有效的支付方式, 请联系网站管理员.');
        }
        $log = pdo_get('core_paylog', array(
            'uniacid' => $_W['uniacid'],
            'module' => $params['module'],
            'tid' => $params['tid']
        ));
        if (empty($log)) {
            $log = array(
                'uniacid' => $_W['uniacid'],
                'acid' => $_W['acid'],
                'openid' => $_W['member']['uid'],
                'module' => $this->module['name'],
                'tid' => $params['tid'],
                'fee' => $params['fee'],
                'card_fee' => $params['fee'],
                'status' => '0',
                'is_usecard' => '0'
            );
            pdo_insert('core_paylog', $log);
        }
        $pay                       = $setting['payment'];
        $pay['credit']['switch']   = true;
        $pay['delivery']['switch'] = false;
        $credtis                   = mc_credit_fetch($_W['fans']['uid']);
        include $this->template('common/paycenter');
    }
    public function array_multi2single($array)
    {
        static $result_array = array();
        foreach ($array as $value) {
            if (is_array($value)) {
                $this->array_multi2single($value);
            } else
                $result_array[] = $value;
        }
        return $result_array;
    }
    public function doWebPush()
    {
        global $_W, $_GPC;
        load()->func('tpl');
        $settings = $this->module['config'];
        $sql      = 'SELECT * FROM ' . tablename('cyl_phone_push') . ' WHERE uniacid=:uniacid';
        $params   = array(
            ':uniacid' => $_W['uniacid']
        );
        $pushlist = pdo_fetch($sql, $params);
        $business = pdo_fetchall('SELECT * FROM ' . tablename($this->tb_business) . ' WHERE uniacid=:uniacid GROUP BY openid', $params);
        if (checksubmit()) {
            $push            = $_GPC['data'];
            $push['uniacid'] = $_W['uniacid'];
            if (!empty($pushlist)) {
                pdo_update('cyl_phone_push', $push);
            } else {
                pdo_insert('cyl_phone_push', $push);
                $push = pdo_insertid();
            }
            if (!empty($settings['templateid'])) {
                $kdata = array(
                    'first' => array(
                        'value' => $push['first'],
                        'color' => '#ff510'
                    ),
                    'keyword1' => array(
                        'value' => $push['keyword1'],
                        'color' => '#ff510'
                    ),
                    'keyword2' => array(
                        'value' => $push['keyword2'],
                        'color' => '#ff510'
                    ),
                    'remark' => array(
                        'value' => $push['remark'],
                        'color' => '#ff510'
                    )
                );
                $url   = $push['link'];
                $acc   = WeAccount::create();
                if ($push['push'] == 1) {
                    $acc->sendTplNotice($push['kfid'], $settings['templateid'], $kdata, $url, $topcolor = '#FF683F');
                } else {
                    foreach ($business as $key => $value) {
                        $array = $value['openid'];
                        $acc->sendTplNotice($array, $settings['templateid'], $kdata, $url, $topcolor = '#FF683F');
                    }
                }
            }
            message('发送成功', $this->createWebUrl('push'), 'success');
        }
        include $this->template('push');
    }
    public function doWebBusiness()
    {
        global $_W, $_GPC;
        $ops      = array(
            'display',
            'edit',
            'delete'
        );
        $op       = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'display';
        $category = pdo_fetchall("SELECT id,rid,name FROM " . tablename('cyl_phone_category') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY rid ASC, id ASC ", array(), 'id');
        $parent   = array();
        $children = array();
        if (!empty($category)) {
            $children = '';
            foreach ($category as $id => $cate) {
                if (!empty($cate['rid'])) {
                    $children[$cate['rid']][] = $cate;
                } else {
                    $parent[$cate['id']] = $cate;
                }
            }
        }
        if ($op == 'display') {
            $pageindex = max(intval($_GPC['page']), 1);
            $pagesize  = 20;
            $where     = ' WHERE uniacid=:uniacid';
            $params    = array(
                ':uniacid' => $_W['uniacid']
            );
            if (!empty($_GPC['keyword'])) {
                $where .= ' AND ( (`title` like :keyword) )';
                $params[':keyword'] = "%{$_GPC['keyword']}%";
            }
            if (!empty($_GPC['status'])) {
                $where .= ' AND (status = :status)';
                $params[':status'] = intval($_GPC['status']);
            }
            if (!empty($_GPC['category']['childid'])) {
                $where .= ' AND (categoryid_2 = :categoryid_2)';
                $params[':categoryid_2'] = intval($_GPC['category']['childid']);
            } elseif (!empty($_GPC['category']['parentid'])) {
                $where .= ' AND (categoryid = :categoryid)';
                $params[':categoryid'] = intval($_GPC['category']['parentid']);
            }
            $sql        = 'SELECT COUNT(*) FROM ' . tablename($this->tb_business) . $where;
            $total      = pdo_fetchcolumn($sql, $params);
            $pager      = pagination($total, $pageindex, $pagesize);
            $sql        = 'SELECT * FROM ' . tablename($this->tb_business) . " {$where} ORDER BY id DESC LIMIT " . (($pageindex - 1) * $pagesize) . ',' . $pagesize;
            $business   = pdo_fetchall($sql, $params, 'id');
            $categories = $this->getAllCategory();
            load()->func('tpl');
            include $this->template('business');
        }
        if ($op == 'edit') {
            $id = intval($_GPC['id']);
            if (!empty($id)) {
                $item     = pdo_fetch("SELECT * FROM " . tablename($this->tb_business) . " WHERE id = :id", array(
                    ':id' => $id
                ));
                $pcate    = $item['categoryid'];
                $ccate    = $item['categoryid_2'];
                $sql      = 'SELECT * FROM ' . tablename($this->tb_business) . ' WHERE id=:id AND uniacid=:uniacid LIMIT 1';
                $params   = array(
                    ':id' => $id,
                    ':uniacid' => $_W['uniacid']
                );
                $business = pdo_fetch($sql, $params);
                if (empty($business)) {
                    message('未找到指定的商家.', $this->createWebUrl('business'));
                }
            }
            $categories = $this->getAllCategory();
            if (checksubmit()) {
                $data                 = $_GPC['business'];
                $data['uniacid']      = $_W['uniacid'];
                $data['time']         = TIMESTAMP;
                $data['categoryid']   = intval($_GPC['category']['parentid']);
                $data['categoryid_2'] = intval($_GPC['category']['childid']);
                $data['logo']         = iserializer($_GPC['logo']);
                if (empty($business)) {
                    $ret = pdo_insert($this->tb_business, $data);
                    if (!empty($ret)) {
                        $id = pdo_insertid();
                    }
                } else {
                    $ret = pdo_update($this->tb_business, $data, array(
                        'id' => $id
                    ));
                }
                if (!empty($ret)) {
                    message('商家信息保存成功', $this->createWebUrl('business', array(
                        'op' => 'edit',
                        'id' => $id
                    )), 'success');
                } else {
                    message('商家信息保存失败');
                }
            }
            load()->func('tpl');
            include $this->template('business_edit');
        }
        if ($op == 'delete') {
            $id = intval($_GPC['id']);
            if (empty($id)) {
                message('未找到指定商家分类');
            }
            $result = pdo_delete($this->tb_business, array(
                'id' => $id,
                'uniacid' => $_W['uniacid']
            ));
            pdo_delete('cyl_phone_paihang', array(
                'business_id' => $id
            ));
            if (intval($result) == 1) {
                message('删除商家成功.', $this->createWebUrl('business'), 'success');
            } else {
                message('删除商家失败.');
            }
        }
    }
    public function doWebIndividual()
    {
        global $_W, $_GPC;
        $ops = array(
            'display',
            'edit',
            'delete'
        );
        $op  = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'display';
        if ($op == 'display') {
            $pageindex = max(intval($_GPC['page']), 1);
            $pagesize  = 20;
            $where     = ' WHERE uniacid=:uniacid';
            $params    = array(
                ':uniacid' => $_W['uniacid']
            );
            if (!empty($_GPC['keyword'])) {
                $where .= ' AND ( (`nickname` like :nickname) )';
                $params[':nickname'] = "%{$_GPC['nickname']}%";
            }
            if (!empty($_GPC['phone'])) {
                $where .= ' AND (phone = :phone)';
                $params[':phone'] = intval($_GPC['phone']);
            }
            if (!empty($_GPC['wxh'])) {
                $where .= ' AND (wxh = :wxh)';
                $params[':wxh'] = intval($_GPC['wxh']);
            }
            if (!empty($_GPC['status'])) {
                $where .= ' AND (status = :status)';
                $params[':status'] = intval($_GPC['status']);
            }
            $sql        = 'SELECT COUNT(*) FROM ' . tablename($this->tb_individual) . $where;
            $total      = pdo_fetchcolumn($sql, $params);
            $pager      = pagination($total, $pageindex, $pagesize);
            $sql        = 'SELECT * FROM ' . tablename($this->tb_individual) . " {$where} ORDER BY id asc LIMIT " . (($pageindex - 1) * $pagesize) . ',' . $pagesize;
            $individual = pdo_fetchall($sql, $params, 'id');
            $categories = $this->getAllCategory();
            load()->func('tpl');
            include $this->template('individual');
        }
        if ($op == 'edit') {
            $id = intval($_GPC['id']);
            if (!empty($id)) {
                $item       = pdo_fetch("SELECT * FROM " . tablename($this->tb_individual) . " WHERE id = :id", array(
                    ':id' => $id
                ));
                $sql        = 'SELECT * FROM ' . tablename($this->tb_individual) . ' WHERE id=:id AND uniacid=:uniacid';
                $params     = array(
                    ':id' => $id,
                    ':uniacid' => $_W['uniacid']
                );
                $individual = pdo_fetch($sql, $params);
                if (empty($individual)) {
                    message('未找到指定的会员.', $this->createWebUrl('individual'));
                }
            }
            $categories = $this->getAllCategory();
            if (checksubmit()) {
                $data = $_GPC['individual'];
                if (empty($individual)) {
                    $data['uniacid'] = $_W['uniacid'];
                    $ret             = pdo_insert($this->tb_individual, $data);
                    if (!empty($ret)) {
                        $id = pdo_insertid();
                    }
                } else {
                    $ret = pdo_update($this->tb_individual, $data, array(
                        'id' => $id
                    ));
                    if (!empty($ret)) {
                        message('会员信息保存成功', $this->createWebUrl('individual', array(
                            'op' => 'edit',
                            'id' => $id
                        )), 'success');
                    } else {
                        message('会员信息保存失败');
                    }
                }
            }
            load()->func('tpl');
            include $this->template('individual_edit');
        }
        if ($op == 'delete') {
            $id = intval($_GPC['id']);
            if (empty($id)) {
                message('未找到会员');
            }
            $result = pdo_delete($this->tb_individual, array(
                'id' => $id,
                'uniacid' => $_W['uniacid']
            ));
            if (intval($result) == 1) {
                message('删除会员成功.', $this->createWebUrl('individual'), 'success');
            } else {
                message('删除会员失败.');
            }
        }
    }
    public function doWebCategory()
    {
        global $_W, $_GPC;
        $ops        = array(
            'display',
            'create',
            'delete'
        );
        $op         = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'display';
        $categories = $this->getAllCategory();
        foreach ($categories as $id => $data) {
            if ($data['rid'] != '0') {
                $rcate[$id] = $data;
            }
        }
        if ($op == 'display') {
            if (checksubmit()) {
                $cats = $_GPC['categories'];
                if (empty($cats)) {
                    message('尚未添加任何分类.');
                }
                foreach ($cats as $k => $cat) {
                    empty($cat['name']) && message('有分类名称未添加,无法保存.');
                    $cat['orderno'] = intval($cat['orderno']);
                }
                foreach ($cats as $k => $cat) {
                    pdo_update($this->tb_category, $cat, array(
                        'id' => $k
                    ));
                }
                message('保存成功.', '', 'success');
            }
            load()->func('tpl');
            include $this->template('category');
        }
        if ($op == 'create') {
            $id  = intval($_GPC['id']);
            $rid = intval($_GPC['rid']);
            if (!empty($id)) {
                $sql         = 'SELECT * FROM ' . tablename('cyl_phone_category') . ' WHERE id=:id AND uniacid=:uniacid LIMIT 1';
                $params      = array(
                    ':id' => $id,
                    ':uniacid' => $_W['uniacid']
                );
                $categories  = pdo_fetch($sql, $params);
                $Rparams     = array(
                    ':id' => $rid,
                    ':uniacid' => $_W['uniacid']
                );
                $Rcategories = pdo_fetch($sql, $Rparams);
                if (empty($categories)) {
                    message('未找到指定的分类.', $this->createWebUrl('categories'));
                }
            }
            if (checksubmit()) {
                $category            = $_GPC['category'];
                $category['uniacid'] = $_W['uniacid'];
                $category['orderno'] = intval($cat['orderno']);
                $category['rid']     = intval($rid);
                if (!empty($id)) {
                    pdo_update('cyl_phone_category', $category, array(
                        'id' => $id
                    ));
                } else {
                    pdo_insert('cyl_phone_category', $category);
                    $id = pdo_insertid();
                }
                message('更新分类成功！', $this->createWebUrl('category', array(
                    'op' => 'display'
                )), 'success');
            }
            include $this->template('category');
        }
        if ($op == 'delete') {
            $id = intval($_GPC['id']);
            if (empty($id)) {
                message('未找到指定商家分类');
            }
            $result = pdo_delete($this->tb_category, array(
                'id' => $id,
                'uniacid' => $_W['uniacid']
            ));
            if (intval($result) == 1) {
                message('删除商家分类成功.', $this->createWebUrl('category'), 'success');
            } else {
                message('删除商家分类失败.');
            }
        }
    }
    public function doWebHdp()
    {
        global $_W, $_GPC;
        $op      = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        $uniacid = $_W['uniacid'];
        if ($op == 'display') {
            if (!empty($_GPC['displayorder'])) {
                foreach ($_GPC['displayorder'] as $id => $displayorder) {
                    $update = array(
                        'displayorder' => $displayorder
                    );
                    pdo_update('cyl_phone_adv', $update, array(
                        'id' => $id
                    ));
                }
                message('排序更新成功！', 'refresh', 'success');
            }
            $pindex    = max(1, intval($_GPC['page']));
            $psize     = 10;
            $condition = "WHERE uniacid = '{$uniacid}'";
            $list      = pdo_fetchall("SELECT * FROM " . tablename('cyl_phone_adv') . " $condition ORDER BY displayorder desc  LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
            $total     = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('cyl_phone_adv') . " $condition ");
            $pager     = pagination($total, $pindex, $psize);
        } elseif ($op == 'post') {
            load()->func('tpl');
            $id = intval($_GPC['id']);
            if ($id > 0) {
                $item = pdo_fetch("SELECT * FROM " . tablename('cyl_phone_adv') . " WHERE id = :id", array(
                    ':id' => $id
                ));
                if (empty($item)) {
                    message('抱歉，广告不存在或是已经删除！', '', 'error');
                }
            }
            if (checksubmit('submit')) {
                $data = array(
                    'uniacid' => $_W['uniacid'],
                    'displayorder' => intval($_GPC['displayorder']),
                    'thumb' => $_GPC['thumb'],
                    'followurl' => trim($_GPC['followurl'])
                );
                if (!empty($_FILES['thumb']['tmp_name'])) {
                    file_delete($_GPC['thumb-old']);
                    $upload = file_upload($_FILES['thumb']);
                    if (is_error($upload)) {
                        message($upload['message'], '', 'error');
                    }
                    $data['thumb'] = $upload['path'];
                }
                if (empty($id)) {
                    pdo_insert('cyl_phone_adv', $data);
                } else {
                    pdo_update('cyl_phone_adv', $data, array(
                        'id' => $id
                    ));
                }
                message('广告更新成功！', $this->createWebUrl('hdp', array(
                    'op' => 'display'
                )), 'success');
            }
        } elseif ($op == 'delete') {
            $id  = intval($_GPC['id']);
            $row = pdo_fetch("SELECT id FROM " . tablename('cyl_phone_adv') . " WHERE id = :id", array(
                ':id' => $id
            ));
            if (empty($row)) {
                message('抱歉，广告不存在或是已经被删除！');
            }
            pdo_delete('cyl_phone_adv', array(
                'id' => $id
            ));
            message('删除成功！', referer(), 'success');
        }
        include $this->template('adv');
    }
    public function friendlyDate($sTime, $type = 'normal', $alt = 'false')
    {
        if (!$sTime)
            return '';
        $cTime = time();
        $dTime = $cTime - $sTime;
        $dDay  = intval(date("z", $cTime)) - intval(date("z", $sTime));
        $dYear = intval(date("Y", $cTime)) - intval(date("Y", $sTime));
        if ($type == 'normal') {
            if ($dTime < 60) {
                if ($dTime < 10) {
                    return '刚刚';
                } else {
                    return intval(floor($dTime / 10) * 10) . "秒前";
                }
            } elseif ($dTime < 3600) {
                return intval($dTime / 60) . "分钟前";
            } elseif ($dYear == 0 && $dDay == 0) {
                return '今天' . date('H:i', $sTime);
            } elseif ($dYear == 0) {
                return date("m月d日 H:i", $sTime);
            } else {
                return date("Y-m-d H:i", $sTime);
            }
        } elseif ($type == 'mohu') {
            if ($dTime < 60) {
                return $dTime . "秒前";
            } elseif ($dTime < 3600) {
                return intval($dTime / 60) . "分钟前";
            } elseif ($dTime >= 3600 && $dDay == 0) {
                return intval($dTime / 3600) . "小时前";
            } elseif ($dDay > 0 && $dDay <= 7) {
                return intval($dDay) . "天前";
            } elseif ($dDay > 7 && $dDay <= 30) {
                return intval($dDay / 7) . '周前';
            } elseif ($dDay > 30) {
                return intval($dDay / 30) . '个月前';
            }
        } elseif ($type == 'full') {
            return date("Y-m-d , H:i:s", $sTime);
        } elseif ($type == 'ymd') {
            return date("Y-m-d", $sTime);
        } else {
            if ($dTime < 60) {
                return $dTime . "秒前";
            } elseif ($dTime < 3600) {
                return intval($dTime / 60) . "分钟前";
            } elseif ($dTime >= 3600 && $dDay == 0) {
                return intval($dTime / 3600) . "小时前";
            } elseif ($dYear == 0) {
                return date("Y-m-d H:i:s", $sTime);
            } else {
                return date("Y-m-d H:i:s", $sTime);
            }
        }
    }
}

?>