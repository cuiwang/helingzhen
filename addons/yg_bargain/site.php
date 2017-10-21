<?php
defined('IN_IA') or exit('Access Denied');
load()->func('communication');
class Yg_bargainModuleSite extends WeModuleSite
{
    public $table_reply = 'yg_bargain_reply';
    public $table_oauth = 'yg_bargain_oauth';
    public $table_shop = 'yg_bargain_shop';
    public $table_user = 'yg_bargain_user';
    public $table_usershop = 'yg_bargain_usershop';
    public $table_shoptype = 'yg_bargain_shoptype';
    public $table_help = 'yg_bargain_help';
    public $table_info = 'yg_bargain_info';
    public $userinfo;
    function __construct()
    {
        global $_W, $_GPC;
        $string = $_SERVER['REQUEST_URI'];
        if (strpos($string, 'app') == true) {
            $this->userinfo = $this->jboauth();
        }
    }
    public function doMobilecheckshop()
    {
        global $_GPC, $_W;
        $userinfo = $this->userinfo;
        $openid   = $userinfo['mk_openid'];
        $uniacid  = $_W['uniacid'];
        $shopid   = $_GPC['shopid'];
        $ushop    = pdo_fetch("SELECT * FROM " . tablename($this->table_usershop) . " where uniacid={$uniacid} and openid='{$openid}' and shopid={$shopid}");
        if (!empty($ushop)) {
            $shop = pdo_fetch("SELECT * FROM " . tablename($this->table_shop) . " WHERE id = '{$ushop['shopid']}'");
            $msg  = array(
                'status' => 200,
                'sname' => $shop['sname']
            );
            echo json_encode($msg);
        } else {
            $usershop = pdo_fetch("SELECT * FROM " . tablename($this->table_usershop) . " where uniacid={$uniacid} and openid='{$openid}' and shopid={$shopid} ");
            if (!empty($usershop)) {
                $shop = pdo_fetch("SELECT * FROM " . tablename($this->table_shop) . " WHERE id = '{$usershop['shopid']}'");
                $msg  = array(
                    'status' => 100,
                    'sname' => $shop['sname']
                );
                echo json_encode($msg);
            } else {
                $msg = array(
                    'status' => 200
                );
                echo json_encode($msg);
            }
        }
    }
    public function doMobileduihuanjp()
    {
        global $_GPC, $_W;
        $userinfo = $this->userinfo;
        $openid   = $userinfo['mk_openid'];
        $shopid   = $_GPC['shopid'];
        $pwd      = $_GPC['dh_pwd'];
        $uniacid  = $_W['uniacid'];
        $shop     = pdo_fetch("SELECT * FROM " . tablename($this->table_shop) . " WHERE id = '{$shopid}'");
        if ($shop['pwd'] == $pwd) {
            $ushop = pdo_fetch("SELECT * FROM " . tablename($this->table_usershop) . " where uniacid={$uniacid} and openid='{$openid}' and shopid={$shopid}");
            if (!empty($ushop)) {
                $faly = pdo_update($this->table_usershop, array(
                    'isduihuan' => 1
                ), array(
                    'id' => $ushop['id']
                ));
                if ($faly) {
                    $msg = array(
                        'status' => 200
                    );
                    echo json_encode($msg);
                } else {
                    $msg = array(
                        'status' => 500
                    );
                    echo json_encode($msg);
                }
            } else {
                $msg = array(
                    'status' => 500
                );
                echo json_encode($msg);
            }
        } else {
            $msg = array(
                'status' => 100
            );
            echo json_encode($msg);
        }
    }
    public function doMobilehelp()
    {
        global $_GPC, $_W;
        $this->checkact();
        $id         = $_GPC['id'];
        $helpopenid = $_GPC['helpopenid'];
        $uniacid    = $_W['uniacid'];
        $userinfo   = $this->userinfo;
        $openid     = $userinfo['mk_openid'];
        $shopid     = $_GPC['shopid'];
        $reply      = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE id = {$id}");
        $user       = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " WHERE uniacid ={$uniacid} and openid  = '{$helpopenid}'");
        $shop       = pdo_fetch("SELECT * FROM " . tablename($this->table_shop) . " WHERE id = '{$shopid}'");
        $countdown  = $shop['setime'] - time();
        if (!empty($user)) {
            $usershop = array(
                'uniacid' => $uniacid,
                'openid' => $helpopenid,
                'userid' => $user['id'],
                'shopid' => $shopid,
                'bgprie' => $shop['price'],
                'addtime' => TIMESTAMP
            );
            $ushop    = pdo_fetch("SELECT * FROM " . tablename($this->table_usershop) . " where uniacid={$uniacid} and openid='{$helpopenid}' and shopid={$shopid}");
            if (empty($ushop)) {
                $faly = pdo_insert($this->table_usershop, $usershop);
            }
        }
        $helpnum  = pdo_fetchcolumn("SELECT count(*) FROM " . tablename($this->table_help) . " where uniacid={$uniacid} and behelped='{$helpopenid}' and shopid={$shopid}");
        $usershop = pdo_fetch("SELECT * FROM " . tablename($this->table_usershop) . " where uniacid={$uniacid} and openid='{$helpopenid}' and shopid={$shopid}");
        if (empty($usershop)) {
            $usershop['bgprie'] = $shop['price'];
        }
        $helpuser    = pdo_fetchall("SELECT a.price,a.cutprice,b.username FROM " . tablename($this->table_help) . " as a LEFT JOIN " . tablename($this->table_user) . " as b on a.helpopenid=b.openid where a.uniacid ={$uniacid} and a.shopid={$shopid} and a.behelped='{$helpopenid}'");
        $rankinglist = pdo_fetchall("SELECT a.username,b.bgprie from " . tablename($this->table_user) . " a LEFT JOIN " . tablename($this->table_usershop) . " b on a.id = b.userid where a.uniacid ={$uniacid} and b.shopid={$shopid} ORDER BY b.bgprie ASC");
        $num_a       = rand(1, 9);
        $num_b       = rand(1, 9);
        $num_count   = $num_a + $num_b;
        $sturt       = 1;
        $member      = pdo_fetch("SELECT * FROM " . tablename($this->table_help) . " where uniacid={$uniacid} and behelped ='{$helpopenid}' and helpopenid='{$openid}' and shopid={$shopid}");
        if (!empty($member)) {
            $sturt = 4;
        }
        include $this->template('help');
    }
    public function doMobilesaveuser()
    {
        global $_GPC, $_W;
        $userinfo = $this->userinfo;
        $openid   = $userinfo['mk_openid'];
        $uniacid  = $_W['uniacid'];
        $data     = array(
            'uniacid' => $uniacid,
            'openid' => $openid,
            'nickname' => $userinfo['mk_nickname'],
            'username' => $_GPC['bmname'],
            'usertel' => $_GPC['bmtel'],
            'headimgurl' => $userinfo['mk_headimgurl'],
            'time' => TIMESTAMP
        );
        $member   = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where uniacid={$uniacid} and openid='{$openid}'");
        if (empty($member)) {
            $faly = pdo_insert($this->table_user, $data);
            if ($faly) {
                echo 2;
            } else {
                echo 1;
            }
        }
    }
    public function doMobilesvaehelp()
    {
        global $_GPC, $_W;
        $helpopenid = $_GPC['helpopenid'];
        $userinfo   = $this->userinfo;
        $openid     = $userinfo['mk_openid'];
        $shopid     = $_GPC['shopid'];
        $uniacid    = $_W['uniacid'];
        $data       = array(
            'uniacid' => $uniacid,
            'openid' => $openid,
            'nickname' => $userinfo['mk_nickname'],
            'username' => $_GPC['bmname'],
            'usertel' => $_GPC['bmtel'],
            'headimgurl' => $userinfo['mk_headimgurl'],
            'time' => TIMESTAMP
        );
        $member     = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where uniacid={$uniacid} and openid='{$openid}'");
        if (empty($member)) {
            $faly = pdo_insert($this->table_user, $data);
        }
        $shop     = pdo_fetch("SELECT * FROM " . tablename($this->table_shop) . " WHERE id = '{$shopid}'");
        $price    = rand($shop['min'] * 100, $shop['max'] * 100) / 100;
        $usershop = pdo_fetch("SELECT * FROM " . tablename($this->table_usershop) . " where uniacid={$uniacid} and openid='{$helpopenid}' and shopid={$shopid}");
        $member   = pdo_fetch("SELECT * FROM " . tablename($this->table_help) . " where uniacid={$uniacid} and helpopenid='{$openid}' and behelped ='{$helpopenid}' and shopid={$shopid}");
        if ($usershop['bgprie']  > $shop['cutdi']) {
            $sum = $usershop['bgprie'] - $price;			
            if ($sum < $shop['cutdi']) {
                $price = $usershop['bgprie'] - $shop['cutdi'];
            }
			$bgprie   = $usershop['bgprie'] - $price;
            $data = array(
                'uniacid' => $uniacid,
                'behelped' => $helpopenid,
                'shopid' => $shopid,
                'helpopenid' => $openid,
                'price' => $price,
                'cutprice' => $bgprie,
                'time' => TIMESTAMP
            );
            if (empty($member)) {
                $faly = pdo_insert($this->table_help, $data);
                if ($faly) {
                    $msg = array(
                        'status' => 200,
                        'price' => $price
                    );
                    if (!empty($usershop)) {
                        pdo_update($this->table_usershop, array(
                            'bgprie' => $bgprie,
                            'uptime' => time()
                        ), array(
                            'id' => $usershop['id']
                        ));
                    }
                    echo json_encode($msg);
                } else {
                    $msg = array(
                        'status' => 500,
                        'price' => $price
                    );
                    echo json_encode($msg);
                }
            } else {
                $msg = array(
                    'status' => 201,
                    'price' => $price
                );
                echo json_encode($msg);
            }
        } else {
            $msg = array(
                'status' => 100,
                'price' => $price
            );
            echo json_encode($msg);
        }
    }
    public function doMobilebghelp()
    {
        global $_GPC, $_W;
        $userinfo = $this->userinfo;
        $openid   = $userinfo['mk_openid'];
        $shopid   = $_GPC['shopid'];
        $uniacid  = $_W['uniacid'];
        $shop     = pdo_fetch("SELECT * FROM " . tablename($this->table_shop) . " WHERE id = '{$shopid}'");
        $price    = rand($shop['smin'] * 100, $shop['smax'] * 100) / 100;
        $usershop = pdo_fetch("SELECT * FROM " . tablename($this->table_usershop) . " where uniacid={$uniacid} and openid='{$openid}' and shopid={$shopid}");
        $bgprie   = $usershop['bgprie'] - $price;
		if($bgprie<0){
			$bgprie=$shop['cutdi'];
			$price=$shop['price']-$shop['cutdi'];
			}
        $data     = array(
            'uniacid' => $uniacid,
            'behelped' => $openid,
            'shopid' => $shopid,
            'helpopenid' => $openid,
            'price' => $price,
            'cutprice' => $bgprie,
            'time' => TIMESTAMP
        );
        $member   = pdo_fetch("SELECT * FROM " . tablename($this->table_help) . " where uniacid={$uniacid} and behelped ='{$openid}' and helpopenid='{$openid}' and shopid={$shopid}");
        if (empty($member)) {
            $faly = pdo_insert($this->table_help, $data);
            if ($faly) {
                $msg = array(
                    'status' => 200,
                    'price' => $price
                );
                if (!empty($usershop)) {
                    pdo_update($this->table_usershop, array(
                        'bgprie' => $bgprie,
                        'uptime' => time()
                    ), array(
                        'id' => $usershop['id']
                    ));
                }
                echo json_encode($msg);
            } else {
                $msg = array(
                    'status' => 500,
                    'price' => $price
                );
                echo json_encode($msg);
            }
        } else {
            $msg = array(
                'status' => 100,
                'price' => $price
            );
            echo json_encode($msg);
        }
    }
    public function doMobilebargain()
    {
        global $_GPC, $_W;
        $this->checkact();
        $id        = $_GPC['id'];
        $uniacid   = $_W['uniacid'];
        $userinfo  = $this->userinfo;
        $openid    = $userinfo['mk_openid'];
        $shopid    = $_GPC['shopid'];
        $reply     = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE id = {$id}");
        $user      = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " WHERE uniacid ={$uniacid} and openid  = '{$openid}'");
        $shop      = pdo_fetch("SELECT * FROM " . tablename($this->table_shop) . " WHERE id = '{$shopid}'");
        $countdown = $shop['setime'] - time();
        if (empty($user)) {
            include $this->template('reg');
        } else {
            $usershop = array(
                'uniacid' => $uniacid,
                'openid' => $openid,
                'userid' => $user['id'],
                'shopid' => $shopid,
                'bgprie' => $shop['price'],
                'addtime' => TIMESTAMP
            );
            $ushop    = pdo_fetch("SELECT * FROM " . tablename($this->table_usershop) . " where uniacid={$uniacid} and openid='{$openid}' and shopid={$shopid}");
            if (empty($ushop)) {
                $faly = pdo_insert($this->table_usershop, $usershop);
            }
            $helpnum  = pdo_fetchcolumn("SELECT count(*) FROM " . tablename($this->table_help) . " where uniacid={$uniacid} and behelped='{$openid}' and shopid={$shopid}");
            $usershop = pdo_fetch("SELECT * FROM " . tablename($this->table_usershop) . " where uniacid={$uniacid} and openid='{$openid}' and shopid={$shopid}");
            if (empty($usershop)) {
                $usershop['bgprie'] = $shop['price'];
            }
            $helpuser    = pdo_fetchall("SELECT a.price,a.cutprice,b.username FROM " . tablename($this->table_help) . " as a LEFT JOIN " . tablename($this->table_user) . " as b on a.helpopenid=b.openid where a.uniacid ={$uniacid} and a.shopid={$shopid} and a.behelped='{$openid}'");
            $rankinglist = pdo_fetchall("SELECT a.username,a.usertel,a.openid,b.bgprie from " . tablename($this->table_user) . " a LEFT JOIN " . tablename($this->table_usershop) . " b on a.id = b.userid where a.uniacid ={$uniacid} and b.shopid={$shopid} ORDER BY b.bgprie ASC");
            $ming        = 0;
            foreach ($rankinglist as $row => $value) {
                $ming++;
                if ($value['openid'] == $openid) {
                    break;
                }
            }
            $sturt  = 1;
            $member = pdo_fetch("SELECT * FROM " . tablename($this->table_help) . " where uniacid={$uniacid} and behelped ='{$openid}' and helpopenid='{$openid}' and shopid={$shopid}");
            if (!empty($member)) {
               $sturt = 4;
			   if (!empty($ushop["bgprie"])) {
                    if ($ushop["bgprie"] <= $shop['cutdi']) {
                        if ($usershop['isduihuan'] == 0) {
                            $sturt = 2;
                        } else {
                            $sturt = 3;
                        }
                    }
                }
            }
            include $this->template('bargain');
        }
    }
    public function doMobileIndex()
    {
        global $_GPC, $_W;
        $userinfo = $this->userinfo;
        $openid   = $userinfo['mk_openid'];
        $op       = $_GPC['op'];
        if (empty($op)) {
            $op = "virtual";
        }
        $uniacid = $_W['uniacid'];
        $id      = $_GPC['id'];
        $this->checkact();
        $reply        = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE id = :id", array(
            ':id' => $_GPC['id']
        ));
        $shoptypelist = pdo_fetchall("SELECT * FROM " . tablename($this->table_shoptype) . " WHERE uniacid = {$uniacid}");
        if (count($shoptypelist) < 2) {
            print_r("请添加两个商品类型");
            exit;
        } else {
            if ($op == "virtual") {
                $shoplist = pdo_fetchall("SELECT * FROM " . tablename($this->table_shop) . " WHERE uniacid = {$uniacid} and shoptype={$shoptypelist[0]['id']}");
            } else {
                $shoplist = pdo_fetchall("SELECT * FROM " . tablename($this->table_shop) . " WHERE uniacid = {$uniacid} and shoptype={$shoptypelist[1]['id']}");
            }
        }
        foreach ($shoplist as $key => $value) {
            if ($value['sstime'] > time()) {
                $shoplist[$key]['state'] = "尚未开始";
            } elseif ($value['setime'] < time()) {
                $shoplist[$key]['state'] = "已经结束";
            } else {
                $shoplist[$key]['state'] = "进行中";
            }
            $pnum                   = pdo_fetchcolumn("SELECT count(*) FROM " . tablename($this->table_usershop) . " where uniacid={$uniacid} and shopid={$value['id']}");
            $shoplist[$key]['pnum'] = $pnum;
            $ushop                  = pdo_fetch("SELECT * FROM " . tablename($this->table_usershop) . " where uniacid={$uniacid} and openid='{$openid}' and shopid={$value['id']}");
            if (empty($ushop)) {
                $shoplist[$key]['bgprie'] = $value['price'];
            } else {
                $shoplist[$key]['bgprie'] = $ushop['bgprie'];
            }
        }
        include $this->template('index');
    }
    public function doMobilereg()
    {
        global $_GPC, $_W;
        include $this->template('reg');
    }
    public function doWebmodfy()
    {
        global $_GPC, $_W;
        $id    = $_GPC['id'];
        $ushop = pdo_fetch("SELECT * FROM " . tablename($this->table_usershop) . " where id={$id} ");
        if (!empty($ushop)) {
            $faly = pdo_update($this->table_usershop, array(
                'isduihuan' => 1
            ), array(
                'id' => $ushop['id']
            ));
            message('商品保存成功!', $this->createWebUrl('ranking', array(
                'shopid' => $ushop['shopid']
            )), "success");
        }
    }
    public function doWebranking()
    {
        global $_GPC, $_W;
        $uniacid     = $_W['uniacid'];
        $shopid      = $_GPC['shopid'];
        $pindex      = max(1, intval($_GPC['page']));
        $psize       = 20;
        $shop        = pdo_fetch("select * from " . tablename($this->table_shop) . " where id=:id limit 1", array(
            ":id" => $shopid
        ));
        $rankinglist = pdo_fetchall("SELECT a.username,a.nickname,a.usertel,b.bgprie,b.isduihuan,b.id from " . tablename($this->table_user) . " a LEFT JOIN " . tablename($this->table_usershop) . " b on a.id = b.userid where a.uniacid ={$uniacid} and b.shopid={$shopid} ORDER BY b.bgprie ASC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
        foreach ($rankinglist as $key => $value) {
            if ($shop['cutdi'] >= $value['bgprie']) {
                if ($value['isduihuan'] == 1) {
                    $rankinglist[$key]['stuat'] = 1;
                } else {
                    $rankinglist[$key]['stuat'] = 2;
                }
            } else {
                $rankinglist[$key]['stuat'] = 3;
            }
        }
        $total = pdo_fetchcolumn("SELECT a.* from" . tablename($this->table_user) . " a LEFT JOIN " . tablename($this->table_usershop) . " b on a.id = b.userid where a.uniacid ={$uniacid} and b.shopid={$shopid}");
        $pager = pagination($total, $pindex, $psize);
        include $this->template("ranking");
    }
    public function doWebShopmge()
    {
        global $_GPC, $_W;
        $op      = $_GPC['op'];
        $uniacid = $_W['uniacid'];
        if ($op == 'edit') {
            $id = intval($_GPC['id']);
            if (checksubmit()) {
                $insert = array(
                    'uniacid' => $uniacid,
                    'sname' => $_GPC['sname'],
                    'spic' => $_GPC['spic'],
                    'listpic' => $_GPC['listpic'],
                    'price' => $_GPC['price'],
                    'sstime' => strtotime($_GPC['ztime']['start']),
                    'setime' => strtotime($_GPC['ztime']['end']),
                    'min' => $_GPC['min'],
                    'max' => $_GPC['max'],
                    'smin' => $_GPC['smin'],
                    'smax' => $_GPC['smax'],
                    'cutdi' => $_GPC['cutdi'],
                    'stime' => time(),
                    'rule' => $_GPC['rule'],
                    'shoptype' => $_GPC['shoptype'],
                    'shopdetail' => $_GPC['shopdetail'],
                    'bgmsg' => $_GPC['bgmsg'],
                    'bglink' => $_GPC['bglink'],
                    'pwd' => $_GPC['pwd']
                );
                if (empty($id)) {
                    pdo_insert($this->table_shop, $insert);
                } else {
                    pdo_update($this->table_shop, $insert, array(
                        'id' => $id
                    ));
                }
                message('商品保存成功!', $this->createWebUrl('Shopmge'), "success");
            }
            $item     = pdo_fetch("select * from " . tablename($this->table_shop) . " where id=:id limit 1", array(
                ":id" => $id
            ));
            $itemtype = pdo_fetchall("select * from " . tablename($this->table_shoptype) . " where uniacid={$uniacid}");
            if (empty($item)) {
                $item['sstime'] = time();
                $item['setime'] = time() + 10 * 84400;
            }
            include $this->template('shop_form');
        } else if ($op == 'delete') {
            $id = intval($_GPC['id']);
            if (empty($id)) {
                message('抱歉，参数错误！', '', 'error');
            }
            pdo_delete($this->table_shop, array(
                "id" => $id
            ));
            message('商品类型删除成功!', referer(), 'success');
        } else {
            $pindex = max(1, intval($_GPC['page']));
            $psize  = 20;
            $list   = pdo_fetchall("SELECT * FROM " . tablename($this->table_shop) . " WHERE uniacid = {$uniacid} ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
            foreach ($list as $key => $value) {
                $shop                   = pdo_fetch("select * from " . tablename($this->table_shoptype) . " where id=:id limit 1", array(
                    ":id" => $value['shoptype']
                ));
                $list[$key]['typename'] = $shop['typename'];
            }
            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_shop) . " WHERE uniacid = {$uniacid}");
            $pager = pagination($total, $pindex, $psize);
            include $this->template("shop");
        }
    }
    public function doWebshoptype()
    {
        global $_GPC, $_W;
        $op      = $_GPC['op'];
        $uniacid = $_W['uniacid'];
        if ($op == 'edit') {
            $id = intval($_GPC['id']);
            if (checksubmit()) {
                $insert       = array(
                    'uniacid' => $uniacid,
                    'typename' => $_GPC['typename'],
                    'ttime' => time()
                );
                $shoptypelist = pdo_fetchall("SELECT * FROM " . tablename($this->table_shoptype) . " WHERE uniacid = {$uniacid}");
                if (empty($id)) {
                    if (count($shoptypelist) < 2) {
                        pdo_insert($this->table_shoptype, $insert);
                    } else {
                        message('商品类型最多添加两个', $this->createWebUrl('shoptype'), "success");
                    }
                } else {
                    pdo_update($this->table_shoptype, $insert, array(
                        'id' => $id
                    ));
                }
                message('商品类型保存成功!', $this->createWebUrl('shoptype'), "success");
            }
            $item = pdo_fetch("select * from " . tablename($this->table_shoptype) . " where id=:id limit 1", array(
                ":id" => $id
            ));
            include $this->template('type_form');
        } else if ($op == 'delete') {
            $id = intval($_GPC['id']);
            if (empty($id)) {
                message('抱歉，参数错误！', '', 'error');
            }
            pdo_delete($this->table_shoptype, array(
                "id" => $id
            ));
            message('商品类型删除成功!', referer(), 'success');
        } else {
            $pindex = max(1, intval($_GPC['page']));
            $psize  = 20;
            $list   = pdo_fetchall("SELECT * FROM " . tablename($this->table_shoptype) . " WHERE uniacid = {$uniacid} ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
            $total  = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_shoptype) . " WHERE uniacid = {$uniacid}");
            $pager  = pagination($total, $pindex, $psize);
            include $this->template("type");
        }
    }
    public function checkact()
    {
        global $_W, $_GPC;
        $reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE id = :id", array(
            ':id' => $_GPC['id']
        ));
        if ($reply) {
            if ($reply['starttime'] > time()) {
                echo "本次活动尚未开始,敬请期待！";
                exit;
            } elseif ($reply['endtime'] < time() || $reply['status'] == 0) {
                echo "本次活动已经结束，请关注我们后续的活动！";
                exit;
            } elseif ($reply['status'] == 2) {
                echo "本次活动暂停中";
                exit;
            }
        }
    }
    public function getOauthCode($data, $key)
    {
        global $_GPC, $_W;
        $forward = urlencode($data);
        $url     = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $key . '&redirect_uri=' . $forward . '&response_type=code&scope=snsapi_userinfo&wxref=mp.weixin.qq.com#wechat_redirect';
        header('location:' . $url);
    }
    public function jboauth()
    {
        global $_GPC, $_W;
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'MicroMessenger') === false) {
            die("本页面仅支持微信访问!非微信浏览器禁止浏览!");
        }
        $serverapp = $_W['account']['level'];
        if ($serverapp == 4) {
            $appid  = $_W['account']['key'];
            $secret = $_W['account']['secret'];
        } else {
            $cfg    = pdo_fetch("select * from " . tablename($this->table_oauth) . " where 1=1 and weid={$_W['weid']}");
            $appid  = $cfg['appid'];
            $secret = $cfg['secret'];
        }
        $info = pdo_fetch("select * from " . tablename($this->table_info) . " where logoopenid = :logoopenid and uniacid =:uniacid", array(
            ':logoopenid' => $_W['openid'],
            ':uniacid' => $_W['uniacid']
        ));
        if (!empty($info)) {
            $user['mk_nickname']   = $info['nickname'];
            $user['mk_openid']     = $info['openid'];
            $user['mk_headimgurl'] = $info['headimgurl'];
        } else {
            $code = $_GPC['code'];
            if (empty($code)) {
                $url = $_W['siteroot'] . $_SERVER['REQUEST_URI'];
                $url = str_replace("//app", "/app", $url);
                $this->getOauthCode($url, $appid);
            } else {
                if (empty($code)) {
                    $url = $_W['siteroot'] . $_SERVER['REQUEST_URI'];
                    $this->getOauthCode($url);
                } else {
                    $key    = $appid;
                    $secret = $secret;
                    $url    = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $key . '&secret=' . $secret . '&code=' . $code . '&grant_type=authorization_code';
                    $data   = ihttp_get($url);
                    if ($data['code'] != 200) {
                        message('诶呦,网络异常..请稍后再试..');
                    }
                    $temp         = @json_decode($data['content'], true);
                    $access_token = $temp['access_token'];
                    $openid       = $temp['openid'];
                    $user_url     = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $access_token . '&openid=' . $openid;
                    $user_temp    = ihttp_get($user_url);
                    if ($user_temp['code'] != 200) {
                        message('诶呦,网络异常..请稍后再试..');
                    }
                    $user = @json_decode($user_temp['content'], true);
                    if (!empty($user['errocde']) || $user['errocde'] != 0) {
                        message(account_weixin_code($user['errcode']), '', 'error');
                    }
                    if (empty($fromuser)) {
                        $from_user = $openid;
                    }
                }
                $datainfo = array(
                    'uniacid' => $_W['uniacid'],
                    'logoopenid' => $_W['openid'],
                    'openid' => $user['openid'],
                    'nickname' => $user['nickname'],
                    'headimgurl' => $user['headimgurl']
                );
                if (empty($info)) {
                    pdo_insert($this->table_info, $datainfo);
                } else {
                    $wheredata = array(
                        'id' => $info['id']
                    );
                    pdo_update($this->table_info, $datainfo, $wheredata);
                }
            }
        }
        return $user;
    }
}