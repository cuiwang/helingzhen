<?php
global $_W  ,$_GPC;
    global $_W, $_GPC;
    $weid = $_W['uniacid'];
    checklogin();
//    $total = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('jy_signup_member') . " WHERE weid=" . $weid);
//    $hd = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('jy_signup_hd') . " WHERE weid=" . $weid . " AND enabled=1");
//    $cy = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('jy_signup_hd_cy') . " WHERE weid=" . $weid);
//    $pl = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('jy_signup_hdcomment') . " WHERE weid=" . $weid);
//    $success = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('jy_signup_hd_card') . " WHERE weid=" . $weid);
//    $zan = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('jy_signup_hdcomment_zan') . " WHERE weid=" . $weid);
//    $list = pdo_fetchall("SELECT count(a.id) as num2,b.hdname as title FROM " . tablename('jy_signup_hd_cy') . " as a left join " . tablename('jy_signup_hd') . " as b on a.hdid=b.id WHERE a.weid=" . $weid . " GROUP BY a.hdid ORDER BY num2 DESC LIMIT 10 ");
//    $list2 = pdo_fetchall("SELECT count(a.id) as num2,b.hdname as title FROM " . tablename('jy_signup_hdcomment') . " as a left join " . tablename('jy_signup_hd') . " as b on a.hdid=b.id WHERE a.weid=" . $weid . " GROUP BY a.hdid ORDER BY num2 DESC LIMIT 10 ");
//    $list3 = pdo_fetchall("SELECT count(a.id) as num2,b.description as title FROM " . tablename('jy_signup_hdcomment_zan') . " as a left join " . tablename('jy_signup_hdcomment') . " as b on a.hdcommentid=b.id WHERE a.weid=" . $weid . " GROUP BY a.hdcommentid ORDER BY num2 DESC LIMIT 10 ");
//    $list4 = pdo_fetchall("SELECT count(a.id) as num2,b.nicheng as title,b.wechatid FROM " . tablename('jy_signup_hd_cy') . " as a left join " . tablename('jy_signup_member') . " as b on a.mid=b.id WHERE a.weid=" . $weid . " GROUP BY a.mid ORDER BY num2 DESC LIMIT 10 ");
//    foreach ($list4 as $key => $value) {
//        if (!empty($value['wechatid'])) {
//            $temp = pdo_fetch("SELECT nickname FROM " . tablename('mc_members') . " WHERE uid=" . $value['wechatid']);
//            $list4[$key]['title'] = $temp['nickname'];
//        }
//    }
    include $this->template('stat');
