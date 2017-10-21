<?php
global $_W, $_GPC;
load()->func("tpl");
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$pindex = max(1, intval($_GPC['page']));
$psize = 9;
$uniacid = $_W['uniacid'];
if ($op=='display') {
    if ($_GPC['title']) {
        $where = "and a.title LIKE '%" . $_GPC['title'] . "%'";
    } else {
        $where = "";
    }
    if ($_GPC['unusual']) {
        $where1 = "and a.ischeck=0";
    } else {
        $where1 = "";
    }
    if (checksubmit("submit")) {
        if (!empty($_GPC['hot'])) {
            foreach ($_GPC['hot'] as $id => $hot) {
                pdo_update('enjoy_city_firm', array(
                    'hot' => $hot
                ), array(
                    'id' => $id
                ));
            }
            message("排序更新成功！", $this->createWebUrl('firm', array(
                'op' => 'display'
            )), 'success');
        }
    }
    $list = pdo_fetchall("SELECT a.*,b.name FROM " . tablename('enjoy_city_firm') . " as a left join " . tablename('enjoy_city_kind') . " as b on
	    a.childid=b.id WHERE a.uniacid = '{$_W['uniacid']}' " . $where . $where1 . " and a.ispay>-1 ORDER BY a.createtime desc LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
    $countadd = pdo_fetchcolumn("select count(*) FROM " . tablename('enjoy_city_firm') . " as a left join " . tablename('enjoy_city_kind') . " as b on
	    a.childid=b.id WHERE a.uniacid = '{$_W['uniacid']}' " . $where . $where1 . " and a.ispay>-1");
    $sumfirm = pdo_fetchcolumn("select count(*) from " . tablename('enjoy_city_firm') . " where ispay>-1 and
	    uniacid=" . $uniacid . "");
    $checkfirm = pdo_fetchcolumn("select count(*) from " . tablename('enjoy_city_firm') . " where ispay>-1 and ischeck>0 and
	    uniacid=" . $uniacid . "");
    $payfirm = pdo_fetchcolumn("select count(*) from " . tablename('enjoy_city_firm') . " where ispay>-1 and ismoney>0 and
	    uniacid=" . $uniacid . "");
    $paymoney = pdo_fetchcolumn("select sum(paymoney) from " . tablename('enjoy_city_firm') . " where ispay>-1 and ismoney>0 and
	    uniacid=" . $uniacid . "");
    $pager = pagination($countadd, $pindex, $psize);
    for ($i = 0; $i < count($list); $i++) {
        $list[$i]['img'] = unserialize($list[$i]['img']);
    }
} elseif ($op=='post') {
    $sellers = pdo_fetchall("select * from " . tablename('enjoy_city_seller') . " where uniacid=" . $uniacid . "");
    $sql = 'SELECT * FROM ' . tablename('enjoy_city_kind') . ' WHERE `uniacid` = :uniacid ORDER BY `parentid`, `hot` asc';
    $category = pdo_fetchall($sql, array(
        ':uniacid' => $_W['uniacid']
    ), 'id');
    $parent = $children = array();
    if (!empty($category)) {
        foreach ($category as $cid => $cate) {
            if (!empty($cate['parentid'])) {
                $children[$cate['parentid']][] = $cate;
            } else {
                $parent[$cate['id']] = $cate;
            }
        }
    }
    if (!empty($_GPC['category']['childid'])) {
        $params[':ccate'] = intval($_GPC['category']['childid']);
    }
    if (!empty($_GPC['category']['parentid'])) {
        $params[':pcate'] = intval($_GPC['category']['parentid']);
    }
    $id = intval($_GPC['id']);
    if (checksubmit("submit")) {
        $data = array(
            'uniacid' => $_W['uniacid'],
            'title' => $_GPC['title'],
            'hot' => $_GPC['hot'],
            'province' => $_GPC['reside']['province'],
            'city' => $_GPC['reside']['city'],
            'district' => $_GPC['reside']['district'],
            'address' => $_GPC['address'],
            'location_x' => $_GPC['baidumap']['lng'],
            'location_y' => $_GPC['baidumap']['lat'],
            'intro' => $_GPC['intro'],
            'tel' => $_GPC['tel'],
            'icon' => $_GPC['icon'],
            'img' => $_GPC['img'],
            'browse' => $_GPC['browse'],
            'forward' => $_GPC['forward'],
            'ischeck' => $_GPC['ischeck'],
            'ismoney' => $_GPC['ismoney'],
            'sid' => $_GPC['sid'],
            'parentid' => $_GPC['category']['parentid'],
            'childid' => $_GPC['category']['childid'],
            'wei_num' => trim($_GPC['wei_num']),
            'wei_name' => trim($_GPC['wei_name']),
            'wei_sex' => intval($_GPC['wei_sex']),
            'wei_intro' => trim($_GPC['wei_intro']),
            'wei_avatar' => trim($_GPC['wei_avatar']),
            'wei_ewm' => trim($_GPC['wei_ewm']),
            's_name' => trim($_GPC['s_name']),
            'breaks' => trim($_GPC['breaks']),
            'uid' => intval($_GPC['uid']),
            'firmurl' => trim($_GPC['firmurl']),
            'custom' => trim($_GPC['custom']),
            'video1' => trim($_GPC['video1']),
            'video2' => trim($_GPC['video2']),
            'fax' => trim($_GPC['fax']),
            'stime' => $_GPC['stime'],
            'etime' => $_GPC['etime']
        );
        if (!empty($id)) {
            pdo_update('enjoy_city_firm', $data, array(
                'id' => $id
            ));
            $message = "更新商户成功！";
        } else {
            $data['createtime'] = TIMESTAMP;
            pdo_insert("enjoy_city_firm", $data);
            $id = pdo_insertid();
            $message = "新增商户成功！";
        }
        message($message, $this->createWebUrl('firm', array(
            'op' => 'display'
        )), 'success');
    }
    $firm = pdo_fetch("SELECT * FROM " . tablename('enjoy_city_firm') . " WHERE id = '$id' and uniacid = '{$_W['uniacid']}'");
    $payment = pdo_fetch("select uniontid,fee,status from " . tablename('core_paylog') . " where tid=" . $id . "
	    and module='enjoy_city' and uniacid=" . $uniacid . "");
    if (empty($firm['etime'])) {
        $firm['etime'] = empty($firm['etime']) ? (date("Y-m-d H:i:s", TIMESTAMP + 365 * 24 * 60 * 60)) : $firm['etime'];
    }
    $firm['baidumap'] = array(
        'lng' => $firm['location_x'],
        'lat' => $firm['location_y']
    );
    $act = pdo_fetch("select * from " . tablename('enjoy_city_reply') . " where uniacid=" . $uniacid . "");
    $firm['province'] = empty($firm['province']) ? $act['province'] : $firm['province'];
    $firm['city'] = empty($firm['city']) ? $act['city'] : $firm['city'];
    $firm['district'] = empty($firm['district']) ? $act['district'] : $firm['district'];
} elseif ($op=='delete') {
    $id = intval($_GPC['id']);
    $firm = pdo_fetch("SELECT id FROM " . tablename('enjoy_city_firm') . " WHERE id = '$id' AND uniacid=" . $_W['uniacid'] . "");
    if (empty($firm)) {
        message('抱歉，商户不存在或是已经被删除！', $this->createWebUrl('firm', array(
            'op' => 'display'
        )), 'error');
    }
    pdo_delete("enjoy_city_firm", array(
        "id" => $id
    ));
    message("商户删除成功！", $this->createWebUrl('firm', array(
        'op' => 'display'
    )), 'success');
} else if ($op=='ischeck') {
    $ischeck = $_GPC['ischeck'];
    $id = $_GPC['id'];
    if ($ischeck==0) {
        $ischeck = 1;
        $firm = pdo_fetch("SELECT * FROM " . tablename('enjoy_city_firm') . " WHERE id = '$id' AND uniacid=" . $_W['uniacid'] . "");
        $fopenid = pdo_fetchcolumn("select openid from " . tablename('enjoy_city_fans') . " where uid=" . $firm[uid] . " and
uniacid=" . $uniacid . "");
        if (!empty($fopenid)) {
            $message = "恭喜,您的店铺通过审核了，请尽快完善店铺信息，以获取更多的展示效果";
            require_once MB_ROOT . "/controller/weixin.class.php";
            $url = $this->str_murl($this->createMobileUrl("firm", array(
                'fid' => $firm['id']
            )));
            $config = $this->module['config']['api'];
            $now = date('Y-m-d', TIMESTAMP);
            $template = array(
                'touser' => $fopenid,
                'template_id' => $config['mid2'],
                'url' => $url,
                'topcolor' => '#743a3a',
                'data' => array(
                    'first' => array(
                        'value' => urlencode('通过审核，请尽快完善店铺信息'),
                        'color' => '#007aff'
                    ),
                    'keyword1' => array(
                        'value' => urlencode($firm['title']),
                        'color' => '#007aff'
                    ),
                    'keyword2' => array(
                        'value' => urlencode('通过'),
                        'color' => '#007aff'
                    ),
                    'keyword3' => array(
                        'value' => urlencode($now),
                        'color' => '#007aff'
                    ),
                    'remark' => array(
                        'value' => urlencode($message),
                        'color' => '#007aff'
                    )
                )
            );
            $api = $this->module['config']['api'];
            $weixin = new class_weixin($_W['account']['key'], $_W['account']['secret']);
            $weixin->send_template_message(urldecode(json_encode($template)));
        }
        $cdata = array(
            'etime' => date('Y-m-d H:i:s', (TIMESTAMP + 365 * 24 * 60 * 60)),
            'ischeck' => 1
        );
    } else {
        $ischeck = 0;
        $cdata = array(
            'ischeck' => 0
        );
    }
    $resr = pdo_update('enjoy_city_firm', $cdata, array(
        'uniacid' => $uniacid,
        'id' => $id
    ));
    message("操作成功", $this->createWebUrl('firm'), 'success');
} else if ($op=='rule') {
    $id = intval($_GPC['id']);
    $rule = 'F' . $id;
    $rcount = pdo_fetchcolumn("select count(*) from " . tablename('rule_keyword') . " where uniacid=" . $uniacid . " and 
        content='" . $rule . "'");
    if ($rcount > 0) {
        message('店铺规则已存在', $this->createWebUrl('firm'), 'error');
    } else {
        $data1 = array(
            'uniacid' => $uniacid,
            'name' => $rule,
            'module' => 'enjoy_city',
            'status' => 1
        );
        pdo_insert("rule", $data1);
        $rid = pdo_insertid();
        $data2 = array(
            'rid' => $rid,
            'uniacid' => $uniacid,
            'module' => 'enjoy_city',
            'content' => $rule,
            'type' => 1,
            'status' => 1
        );
        $ekey = pdo_insert('rule_keyword', $data2);
        if ($ekey > 0) {
            pdo_update('enjoy_city_firm', array(
                'rid' => $rid
            ), array(
                'id' => $id,
                'uniacid' => $uniacid
            ));
            message("增加店铺规则成功", $this->createWebUrl('firm'), 'success');
        }
    }
} else if ($op=='UploadExcel') {
    if ($_GPC['leadExcel']=="true") {
        $filename = $_FILES['inputExcel']['name'];
        $tmp_name = $_FILES['inputExcel']['tmp_name'];
        $flag = $this->checkUploadFileMIME($_FILES['inputExcel']);
        if ($flag==0) {
            message('文件格式不对.');
        }
        if (empty($tmp_name)) {
            message('请选择要导入的Excel文件！');
        }
        $msg = $this->uploadFile($filename, $tmp_name, $_GPC);
        if ($msg==1) {
            message('导入成功！', referer(), 'success');
        } else {
            message($msg, '', 'error');
        }
    }
} else if ($_GPC['op']=='excel') {
    $list = pdo_fetchall("SELECT a.*,b.name FROM " . tablename('enjoy_city_firm') . " as a left join " . tablename('enjoy_city_kind') . " as b on
    a.childid=b.id WHERE a.uniacid = '{$_W['uniacid']}' and a.ispay>-1 ORDER BY a.createtime desc");
    $title = array(
        '排序',
        '商户名',
        '起始时间',
        '终止时间',
        '一级分类',
        '二级分类',
        '省',
        '市',
        '区',
        '详细地址',
        '经度',
        '纬度',
        '电话',
        '简介',
        '优惠信息',
        '自定义按钮名称',
        '商品外链',
        'logo图片',
        '横幅图片',
        '浏览次数',
        '转发次数',
        '老板微信号',
        '老板微信昵称',
        '老板性别',
        '老板头像',
        '老板二维码',
        '申请人姓名',
        '申请人UID',
        '老板个人简介',
        '通过审核1(是)0(否)',
        '在线支付1(是)0(否)',
        '在线支付1金额'
    );
    $arraydata[] = iconv("UTF-8", "GB2312//IGNORE", implode("\t", $title));
    foreach ($list as &$value) {
        $cash = $value['total'] - $value['cashed'];
        $tmp_value = array(
            $value['hot'],
            $value['title'],
            $value['stime'],
            $value['etime'],
            $value['parentid'],
            $value['childid'],
            $value['province'],
            $value['city'],
            $value['district'],
            $value['address'],
            $value['location_x'],
            $value['location_y'],
            $value['tel'],
            $value['intro'],
            $value['custom'],
            $value['breaks'],
            $value['firmurl'],
            $value['icon'],
            $value['img'],
            $value['browse'],
            $value['forward'],
            $value['wei_num'],
            $value['wei_name'],
            $value['wei_sex'],
            $value['wei_avatar'],
            $value['wei_ewm'],
            $value['s_name'],
            $value['uid'],
            $value['wei_intro'],
            $value['ischeck'],
            $value['ispay'],
            $value['paymoney']
        );
        $arraydata[] = iconv("UTF-8", "GB2312//IGNORE", implode("\t", $tmp_value));
    }
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Type: application/vnd.ms-execl");
    header("Content-Type: application/force-download");
    header("Content-Type: application/download");
    header("Content-Disposition: attachment; filename=" . date("Ymd") . ".xls");
    header("Content-Transfer-Encoding: binary");
    header("Pragma: no-cache");
    header("Expires: 0");
    echo implode("	
", $arraydata);
    exit();
} else if ($op=='gather') {
    if ($_GPC['ops']==1) {
        $file_contents = file_get_contents('http://api.map.baidu.com/?qt=s&c=1&wd=南京市%20下关区%20学校&rn=100&pn=2&ie=utf-8&oue=1&fromproduct=jsapi&res=api&callback=BMap._rd._cbk68355&ak=lQ9Lw03f6Y9eARzgQZNyaolD8vQqtOv4');
        $filearr = json_decode('{"place_info":{"d_activity_gwj_section":"0-+","d_brand_id_section":"0-+","d_business_id":"","d_business_type":"","d_cater_book_pc_section":"0-+","d_cater_book_wap_section":"0-+","d_cater_rating_section":"0-+","d_data_type":"","d_discount2_section":"0-+","d_discount_section":"0-+","d_discount_tm2_section":"0-+","d_discount_tm_section":"0-+","d_dist":"0-+","d_filt_type_section":"0-+","d_free_section":"0-+","d_groupon_section":"0-+","d_groupon_type_section":"0-+","d_health_score_section":"0-+","d_hotel_book_pc_section":"0-+","d_hotel_book_wap_section":"0-+","d_hourly_day1_bookable_section":"0-+","d_hourly_day1_fullroom_section":"0-+","d_hourly_day1_price_section":"0-+","d_hourly_day2_bookable_section":"0-+","d_hourly_day2_fullroom_section":"0-+","d_hourly_day2_price_section":"0-+","d_hourly_day3_bookable_section":"0-+","d_hourly_day3_fullroom_section":"0-+","d_hourly_day3_price_section":"0-+","d_hourly_day4_bookable_section":"0-+","d_hourly_day4_fullroom_section":"0-+","d_hourly_day4_price_section":"0-+","d_hourly_day5_bookable_section":"0-+","d_hourly_day5_fullroom_section":"0-+","d_hourly_day5_price_section":"0-+","d_level_section":"0-+","d_lowprice_flag_section":"0-+","d_meishipaihao_section":"0-+","d_movie_book_section":"0-+","d_overall_rating_section":"0-+","d_price_section":"0-+","d_query_attr_type":"2","d_rebate_section":"0-+","d_sort_rule":"0","d_sort_type":"","d_spothot_section":"0-+","d_sub_type":"","d_support_imax_section":"0-+","d_tag_filter":"0","d_tag_info_list":"\u6559\u80b2,\u5b66\u6821","d_tag_section":"0-+","d_ticket_book_flag_section":"0-+","d_tonight_sale_flag_section":"0-+","d_total_score_section":"0-+","d_wise_price_section":"0-+","search_ext":[{"title":"","wd":""}]},"content":[{"acc_flag":0,"addr":"\u5e55\u5e9c\u4e1c\u8def96\u53f7\u76db\u4e16\u82b1\u56ed\u5185","address_norm":"[\u6c5f\u82cf\u7701(18)|PROV|0|NONE][\u5357\u4eac\u5e02(315)|CITY|0|NONE][\u4e0b\u5173\u533a(1759)|AREA|0|NONE][\u5e55\u5e9c\u4e1c\u8def()|ROAD|1|NONE]96\u53f7\u76db\u4e16\u82b1\u56ed\u5185","aoi":"\u5e55\u5e9c\u5c71","area":1759,"area_name":"\u5357\u4eac\u5e02\u4e0b\u5173\u533a","biz_type":0,"catalogID":476,"cla":[[19,"\u6559\u80b2"],[189,"\u5b66\u524d\u6559\u80b2"],[476,"\u5e7c\u513f\u56ed\/\u6258\u513f\u6240"]],"click_flag":0,"detail":1,"diPointX":1322489139,"diPointY":375605622,"di_tag":"\u5e7c\u513f\u56ed \u5b66\u6821 \u6559\u80b2","dis":-1,"dist2route":0,"dist2start":0,"ext":{"detail_info":{"app_cross_rcmd":{"is_rcmd":1,"src_type":"wanneng_touchuan","sub_src":"app","transfer":1},"areaid":"1759","comment_num":"0","description_flag":"","display_info_comment_label":{"hotel":"","life":""},"display_info_redu":"14","from_pds":"1","image":"http:\/\/hiphotos.baidu.com\/lbsugc\/c%3Dmap%2C90%2C90\/sign=996d271ed900baa1ae2c1fea373c8022\/c995d143ad4bd11375a05dc052afa40f4afb05d6.jpg","image_from":"ruitudetail","image_num":"1","link":[],"mbc":{"markv":"3"},"name":"\u76db\u4e16\u53cc\u8bed\u5e7c\u513f\u56ed","overall_rating":"","phone":"(025)83018839","poi_address":"\u5e55\u5e9c\u4e1c\u8def96\u53f7\u76db\u4e16\u82b1\u56ed\u5185","point":{"x":13224891.39,"y":3756056.22},"price":"","price_rating":"0","service_rating":"0","shop_hours":"","shop_hours_flag":"","std_tag":"\u6559\u80b2\u57f9\u8bad;\u5e7c\u513f\u56ed","tag":"\u5e7c\u513f\u56ed","technology_rating":"0","validate":0,"weighted_tag":"\u5e7c\u513f\u56ed:10 \u5b66\u6821:10 \u6559\u80b2:10"},"src_name":"education"},"ext_display":{"display_info":{"impression_tag":{"hotel":"","life":""},"redu":"14","source_map":[],"src_name":"display_info"}},"ext_type":4,"f_flag":8,"father_son":0,"flag_type":"0","geo":".=zU50OB2l0YWA;","geo_type":2,"indoor_pano":"","ismodified":0,"name":"\u76db\u4e16\u53cc\u8bed\u5e7c\u513f\u56ed","navi_x":"13224852.37","navi_y":"3756067.55","new_catalog_id":"0d0101","pano":1,"poiType":0,"poi_click_num":0,"poi_profile":0,"primary_uid":"8481585940451040212","prio_flag":32,"route_flag":0,"show_tag":[],"status":1,"std_tag":"\u6559\u80b2\u57f9\u8bad;\u5e7c\u513f\u56ed","storage_src":"api","street_id":"941c8d3a64ba265dc8213de2","tag":"\u5e7c\u513f\u56ed \u5b66\u6821<\/font> \u6559\u80b2","tel":"(025)83018839","ty":2,"uid":"941c8d3a64ba265dc8213de2","view_type":0,"x":1322489139,"y":375605622},{"acc_flag":0,"addr":"\u5357\u4eac\u5e02\u9f13\u697c\u533a\u6811\u4eba\u8def","area":1759,"area_name":"\u5357\u4eac\u5e02\u4e0b\u5173\u533a","biz_type":0,"catalogID":192,"cla":[[19,"\u6559\u80b2"]],"click_flag":0,"detail":1,"diPointX":1321806086,"diPointY":375061311,"di_tag":"\u5b66\u6821 \u6559\u80b2","dis":-1,"dist2route":0,"dist2start":0,"ext":{"detail_info":{"app_cross_rcmd":{"is_rcmd":1,"src_type":"wanneng_touchuan","sub_src":"app","transfer":1},"areaid":"1759","description_flag":1,"display_info_comment_label":{"hotel":"","life":""},"display_info_redu":"3","from_pds":"1","image":"http:\/\/hiphotos.baidu.com\/space\/pic\/item\/0dd7912397dda144520a3ca5bab7d0a20cf486a2.jpg","image_from":"baidumap","image_num":"1","link":[],"mbc":{"markv":"3"},"name":"\u5357\u4eac\u5e08\u8303\u5927\u5b66\u9644\u5c5e\u4e2d\u5b66\u6811\u4eba\u5b66\u6821-4\u53f7\u697c","overall_rating":"0","phone":"","poi_address":"\u5357\u4eac\u5e02\u9f13\u697c\u533a\u6811\u4eba\u8def","point":{"x":13218060.86,"y":3750613.11},"price":"","price_rating":"0","service_rating":"0","shop_hours":"","shop_hours_flag":"","std_tag":"\u6559\u80b2\u57f9\u8bad;\u5176\u4ed6","tag":"\u5b66\u6821","technology_rating":"0","validate":0,"weighted_tag":"\u5b66\u6821:10 \u6559\u80b2:10"},"src_name":"education"},"ext_display":{"display_info":{"impression_tag":{"hotel":"","life":""},"redu":"3","source_map":[],"src_name":"display_info"}},"ext_type":4,"f_flag":9,"father_son":0,"flag_type":"1","geo":".=GkSyOB\/svWWA;","geo_type":2,"indoor_pano":"","ismodified":0,"name":"\u5357\u4eac\u5e08\u8303\u5927\u5b66\u9644\u5c5e\u4e2d\u5b66\u6811\u4eba\u5b66\u6821-4\u53f7\u697c","navi_x":"13218105.06","navi_y":"3750619.04","new_catalog_id":"0d0100","pano":1,"poiType":0,"poi_click_num":0,"poi_profile":0,"primary_uid":"6289829624547040303","prio_flag":32,"route_flag":0,"show_tag":[],"status":1,"std_tag":"\u6559\u80b2\u57f9\u8bad;\u5176\u4ed6","storage_src":"api","street_id":"aac966bc6188da1b7d29bdd2","tag":"\u5b66\u6821<\/font> \u6559\u80b2","ty":2,"uid":"aac966bc6188da1b7d29bdd2","view_type":0,"x":1321806086,"y":375061311},{"acc_flag":0,"addr":"\u4e1c\u4e95\u6751\u71d5\u4ead\u8def2\u53f78\u680b102\u5546\u94fa\uff0c\u827a\u5f69\u7a7a\u95f4\u5185","aoi":"\u548c\u71d5\u8def","area":1759,"area_name":"\u5357\u4eac\u5e02\u4e0b\u5173\u533a","biz_type":0,"catalogID":0,"cla":[],"click_flag":0,"detail":1,"diPointX":1322552992,"diPointY":375443508,"di_tag":"\u5b66\u6821 \u6559\u80b2","dis":-1,"dist2route":0,"dist2start":0,"ext":{"detail_info":{"app_cross_rcmd":{"is_rcmd":1,"src_type":"wanneng_touchuan","sub_src":"app","transfer":1},"areaid":"1759","description_flag":1,"display_info_comment_label":{"hotel":"","life":""},"display_info_redu":"0","from_pds":"1","groupon":[{"catg_list":"2047,2045,316","cn_name":"\u3010\u63a8\u8350\u3011\u56f4\u68cb\u8bfe1\u8282\uff0c\u9700\u9884\u7ea6\uff01","groupon_brandtag":null,"groupon_end":"2017-03-30","groupon_id":"11931754","groupon_image":"http:\/\/e.hiphotos.baidu.com\/bainuo\/crop%3D47%2C0%2C1187%2C719%3Bw%3D719%3Bq%3D99\/sign=4a594fe352fbb2fb20640252727e1788\/e61190ef76c6a7efddacef98fafaaf51f2de6681.jpg","groupon_num":"21","groupon_price":"9.9","groupon_rebate":"2","groupon_short_title":"\u3010\u63a8\u8350\u3011\u56f4\u68cb\u8bfe1\u8282\uff0c\u9700\u9884\u7ea6\uff01","groupon_site":"http:\/\/www.nuomi.com","groupon_start":"2016-04-01","groupon_tags":{"activity":null,"appointment":"1","labelid":[],"multi_option":"0","tp":"0","voucher":"0"},"groupon_title":"\u4ec5\u552e9.9\u5143\uff0c\u4ef7\u503c50\u5143\u56f4\u68cb\u8bfe1\u8282\uff0c\u9700\u9884\u7ea6\uff01","groupon_type":"1","groupon_url_mobile":"http:\/\/m.nuomi.com\/deal\/view?tinyurl=emvve8ch","groupon_url_pc":"http:\/\/www.nuomi.com\/deal\/emvve8ch.html?cid=map_pc_bubble","groupon_webapp_url":"http:\/\/m.nuomi.com\/deal\/view?tinyurl=emvve8ch","regular_price":"50","subcatg_list":"2114,2107,375","thirdcatg_list":""},{"catg_list":"2047,2045,316","cn_name":"\u3010\u63a8\u8350\u3011\u56f4\u68cb\u542f\u8499\u73ed16\u6b21\u8bfe\u5957\u99101\u4efd\uff0c\u9700\u9884\u7ea6\uff01","groupon_brandtag":null,"groupon_end":"2017-04-01","groupon_id":"12101798","groupon_image":"http:\/\/e.hiphotos.baidu.com\/bainuo\/crop%3D0%2C93%2C1280%2C775%3Bw%3D720%3Bq%3D79\/sign=16cd48695566d0166a56c468aa1bf83e\/503d269759ee3d6d7aed4c6f44166d224f4ade6f.jpg","groupon_num":"2","groupon_price":"499","groupon_rebate":"6.2","groupon_short_title":"\u3010\u63a8\u8350\u3011\u56f4\u68cb\u542f\u8499\u73ed16\u6b21\u8bfe\u5957\u99101\u4efd\uff0c\u9700\u9884\u7ea6\uff01","groupon_site":"http:\/\/www.nuomi.com","groupon_start":"2016-04-06","groupon_tags":{"activity":null,"appointment":"1","labelid":[],"multi_option":"0","tp":"0","voucher":"0"},"groupon_title":"\u4ec5\u552e499\u5143\uff0c\u4ef7\u503c800\u5143\u56f4\u68cb\u542f\u8499\u73ed16\u6b21\u8bfe\u5957\u99101\u4efd\uff0c\u9700\u9884\u7ea6\uff01","groupon_type":"1","groupon_url_mobile":"http:\/\/m.nuomi.com\/deal\/view?tinyurl=wu973qfh","groupon_url_pc":"http:\/\/www.nuomi.com\/deal\/wu973qfh.html?cid=map_pc_bubble","groupon_webapp_url":"http:\/\/m.nuomi.com\/deal\/view?tinyurl=wu973qfh","regular_price":"800","subcatg_list":"2114,2107,375","thirdcatg_list":""}],"groupon_end":"2017-03-30","groupon_largest_num":"21","groupon_num":"2","groupon_price":"9.9","image":"http:\/\/e.hiphotos.baidu.com\/bainuo\/crop%3D0%2C0%2C960%2C1280%3Bw%3D690%3Bq%3D99%3Bc%3Dnuomi%2C95%2C95\/sign=e920d6814cfbfbedc8166c3f45c0db06\/a8ec8a13632762d0448ce301a7ec08fa503dc6da.jpg","image_from":"nuomi_detail","image_num":"10","link":[],"mbc":{"markv":"3"},"name":"\u4e2d\u5c71\u56f4\u68cb\u5b66\u6821(\u8fc8\u768b\u68652\u5e97)","overall_rating":"","phone":"(025)85339928,13913908128","poi_address":"\u4e1c\u4e95\u6751\u71d5\u4ead\u8def2\u53f78\u680b102\u5546\u94fa\uff0c\u827a\u5f69\u7a7a\u95f4\u5185","point":{"x":13225500,"y":3754440},"price":"","price_rating":"0","rec_reason":"\u8d85\u4f4e\u4ef79.9\u5143, 2<\/font>\u6298","service_rating":"0","shop_hours":"","shop_hours_flag":"","std_tag":"\u6559\u80b2\u57f9\u8bad;\u5176\u4ed6","tag":"\u5b66\u6821","technology_rating":"0","validate":0,"weighted_tag":"\u5b66\u6821:10 \u6559\u80b2:10"},"src_name":"education"},"ext_display":{"display_info":{"impression_tag":{"hotel":"","life":""},"redu":"0","source_map":[],"src_name":"display_info"}},"ext_type":4,"f_flag":9,"father_son":0,"flag_type":"1","geo":".=g6I1OB0ANYWA;","geo_type":2,"indoor_pano":"","ismodified":0,"name":"\u4e2d\u5c71\u56f4\u68cb\u5b66\u6821(\u8fc8\u768b\u68652\u5e97)","navi_x":"0","navi_y":"0","new_catalog_id":"0d0100","origin_id":{"lbc_uid":"4870861463901124672"},"pano":1,"poiType":0,"poi_click_num":0,"poi_profile":0,"primary_uid":"3893581504175356238","prio_flag":32,"route_flag":0,"show_tag":[],"status":1,"std_tag":"\u6559\u80b2\u57f9\u8bad;\u5176\u4ed6","storage_src":"api","street_id":"239acd4c54c9828e5a3ac8e6","tag":"\u5b66\u6821<\/font> \u6559\u80b2","tel":"(025)85339928,13913908128","ty":2,"uid":"239acd4c54c9828e5a3ac8e6","view_type":0,"x":1322552992,"y":375443508},{"acc_flag":0,"addr":"\u4e09\u6c4a\u6cb3\u8fce\u6c5f\u56ed\u5c0f\u533a\u5185","alias":["\u5b9d\u5584\u5e7c\u513f\u56ed"],"area":1759,"area_name":"\u5357\u4eac\u5e02\u4e0b\u5173\u533a","biz_type":0,"catalogID":0,"cla":[],"click_flag":0,"detail":1,"diPointX":1321824766,"diPointY":375058578,"di_tag":"\u5e7c\u513f\u56ed \u5b66\u6821 \u6559\u80b2","dis":-1,"dist2route":0,"dist2start":0,"ext":{"detail_info":{"app_cross_rcmd":{"is_rcmd":1,"src_type":"wanneng_touchuan","sub_src":"app","transfer":1},"areaid":"1759","description_flag":1,"display_info_comment_label":{"hotel":"","life":""},"display_info_redu":"2","from_pds":"1","image":"http:\/\/hiphotos.baidu.com\/space\/pic\/item\/d52a2834349b033bb330accd1dce36d3d539bd69.jpg","image_from":"baidumap","image_num":"1","link":[],"mbc":{"markv":"3"},"name":"\u5357\u4eac\u5e02\u5b9d\u5584\u5e7c\u513f\u56ed","overall_rating":"5.0","phone":"","poi_address":"\u4e09\u6c4a\u6cb3\u8fce\u6c5f\u56ed\u5c0f\u533a\u5185","point":{"x":13218247.66,"y":3750585.78},"price":"","price_rating":"0","service_rating":"0","shop_hours":"","shop_hours_flag":"","std_tag":"\u6559\u80b2\u57f9\u8bad;\u5e7c\u513f\u56ed","tag":"\u5e7c\u513f\u56ed","technology_rating":"0","validate":0,"weighted_tag":"\u5e7c\u513f\u56ed:10 \u5b66\u6821:10 \u6559\u80b2:10"},"src_name":"education"},"ext_display":{"display_info":{"impression_tag":{"hotel":"","life":""},"redu":"2","source_map":[],"src_name":"display_info"}},"ext_type":4,"f_flag":8,"father_son":0,"flag_type":"1","geo":".=+HXyOBSCvWWA;","geo_type":2,"indoor_pano":"","ismodified":0,"name":"\u5357\u4eac\u5e02\u5b9d\u5584\u5e7c\u513f\u56ed","navi_x":"0","navi_y":"0","new_catalog_id":"0d0101","pano":1,"poiType":0,"poi_click_num":0,"poi_profile":0,"primary_uid":"11366087294972002631","prio_flag":32,"route_flag":0,"show_tag":[],"status":1,"std_tag":"\u6559\u80b2\u57f9\u8bad;\u5e7c\u513f\u56ed","storage_src":"api","street_id":"b53dc46472137fc975e5b6b9","tag":"\u5e7c\u513f\u56ed \u5b66\u6821<\/font> \u6559\u80b2","ty":2,"uid":"b53dc46472137fc975e5b6b9","view_type":0,"x":1321824766,"y":375058578},{"acc_flag":0,"addr":"\u5357\u4eac\u5e02\u9f13\u697c\u533a","aoi":"\u9605\u6c5f\u697c;\u4e2d\u5c71\u7801\u5934;\u897f\u7ad9","area":1759,"area_name":"\u5357\u4eac\u5e02\u4e0b\u5173\u533a","biz_type":0,"catalogID":0,"cla":[],"click_flag":0,"detail":1,"diPointX":1322029206,"diPointY":375299475,"di_tag":"\u5e7c\u513f\u56ed \u5b66\u6821 \u6559\u80b2","dis":-1,"dist2route":0,"dist2start":0,"ext":{"detail_info":{"app_cross_rcmd":{"is_rcmd":1,"src_type":"wanneng_touchuan","sub_src":"app","transfer":1},"areaid":"1759","description_flag":"","display_info_comment_label":{"hotel":"","life":""},"display_info_redu":"1","from_pds":"1","image":"http:\/\/hiphotos.baidu.com\/space\/pic\/item\/d52a2834349b033bb330accd1dce36d3d539bd69.jpg","image_from":"baidumap","image_num":"1","link":[],"mbc":{"markv":"3"},"name":"\u5e7c\u513f\u56ed","overall_rating":"","phone":"","poi_address":"\u5357\u4eac\u5e02\u9f13\u697c\u533a","point":{"x":13220292.06,"y":3752994.75},"price":"","price_rating":"0","service_rating":"0","shop_hours":"","shop_hours_flag":"","std_tag":"\u6559\u80b2\u57f9\u8bad;\u5e7c\u513f\u56ed","tag":"\u5e7c\u513f\u56ed","technology_rating":"0","validate":0,"weighted_tag":"\u5e7c\u513f\u56ed:10 \u5b66\u6821:10 \u6559\u80b2:10"},"src_name":"education"},"ext_display":{"display_info":{"impression_tag":{"hotel":"","life":""},"redu":"1","source_map":[],"src_name":"display_info"}},"ext_type":4,"f_flag":8,"father_son":0,"flag_type":"1","geo":".=WCJzOBT2pXWA;","geo_type":2,"indoor_pano":"","ismodified":0,"name":"\u5e7c\u513f\u56ed","navi_x":"0","navi_y":"0","new_catalog_id":"0d0101","pano":1,"poiType":0,"poi_click_num":0,"poi_profile":0,"primary_uid":"643754416703841763","prio_flag":32,"route_flag":0,"show_tag":[],"status":1,"std_tag":"\u6559\u80b2\u57f9\u8bad;\u5e7c\u513f\u56ed","storage_src":"api","street_id":"1979668c8b51580cb13c81e7","tag":"\u5e7c\u513f\u56ed \u5b66\u6821<\/font> \u6559\u80b2","ty":2,"uid":"1979668c8b51580cb13c81e7","view_type":0,"x":1322029206,"y":375299475},{"acc_flag":0,"addr":"\u71d5\u6c5f\u56ed7\u5e62-1\u5ba4","address_norm":"[\u6c5f\u82cf\u7701(18)|PROV|0|NONE][\u5357\u4eac\u5e02(315)|CITY|0|NONE][\u4e0b\u5173\u533a(1759)|AREA|1|NONE]\u71d5\u6c5f\u56ed7\u5e62-1\u53f7[\u91d1\u71d5\u8def()|ROAD|0|NONE]","area":1759,"area_name":"\u5357\u4eac\u5e02\u4e0b\u5173\u533a","biz_type":0,"catalogID":476,"cla":[[19,"\u6559\u80b2"],[189,"\u5b66\u524d\u6559\u80b2"],[476,"\u5e7c\u513f\u56ed\/\u6258\u513f\u6240"]],"click_flag":0,"detail":1,"diPointX":1322146190,"diPointY":375505208,"di_tag":"\u5e7c\u513f\u56ed \u5b66\u6821 \u6559\u80b2","dis":-1,"dist2route":0,"dist2start":0,"ext":{"detail_info":{"app_cross_rcmd":{"is_rcmd":1,"src_type":"wanneng_touchuan","sub_src":"app","transfer":1},"areaid":"1759","comment_num":"0","description_flag":"","display_info_comment_label":{"hotel":"","life":""},"display_info_redu":"16","from_pds":"1","image":"http:\/\/hiphotos.baidu.com\/lbsugc\/pic\/item\/2e2eb9389b504fc2d862f3eae7dde71190ef6d1b.jpg","image_from":"baidumap","image_num":"6","link":[],"mbc":{"markv":"3"},"name":"\u6768\u71d5\u5e7c\u513f\u56ed","overall_rating":"","phone":"13851606730","poi_address":"\u71d5\u6c5f\u56ed7\u5e62-1\u5ba4","point":{"x":13221461.9,"y":3755052.08},"price":"","price_rating":"0","service_rating":"0","shop_hours":"","shop_hours_flag":"","std_tag":"\u6559\u80b2\u57f9\u8bad;\u5e7c\u513f\u56ed","tag":"\u5e7c\u513f\u56ed","technology_rating":"0","validate":0,"weighted_tag":"\u5e7c\u513f\u56ed:10 \u5b66\u6821:10 \u6559\u80b2:10"},"src_name":"education"},"ext_display":{"display_info":{"impression_tag":{"hotel":"","life":""},"redu":"16","source_map":[],"src_name":"display_info"}},"ext_type":4,"f_flag":8,"father_son":0,"flag_type":"0","geo":".=OmlzOB4EcYWA;","geo_type":2,"indoor_pano":"","ismodified":1,"name":"\u6768\u71d5\u5e7c\u513f\u56ed","navi_x":"13221438.3917","navi_y":"3755068.03504","new_catalog_id":"0d0101","pano":1,"poiType":0,"poi_click_num":0,"poi_profile":0,"primary_uid":"13616585586067184518","prio_flag":32,"route_flag":0,"show_tag":[],"status":1,"std_tag":"\u6559\u80b2\u57f9\u8bad;\u5e7c\u513f\u56ed","storage_src":"api","street_id":"2c1eeeab1be616681fe999ff","tag":"\u5e7c\u513f\u56ed \u5b66\u6821<\/font> \u6559\u80b2","tel":"13851606730","ty":2,"uid":"2c1eeeab1be616681fe999ff","view_type":0,"x":1322146190,"y":375505208},{"acc_flag":0,"addr":"\u5357\u4eac\u5e02\u4e0b\u5173\u533a","aoi":"\u5e55\u5e9c\u5c71","area":1759,"area_name":"\u5357\u4eac\u5e02\u4e0b\u5173\u533a","biz_type":0,"catalogID":0,"cla":[],"click_flag":0,"detail":1,"diPointX":1322372809,"diPointY":375589155,"di_tag":"\u5b66\u6821 \u6559\u80b2 \u5b66\u9662","dis":-1,"dist2route":0,"dist2start":0,"ext":{"detail_info":{"areaid":"1759","description_flag":1,"display_info_comment_label":{"hotel":"","life":""},"display_info_redu":"21","from_pds":"1","image":"http:\/\/hiphotos.baidu.com\/space\/pic\/item\/7dd98d1001e939012e51dd2973ec54e736d19651.jpg","image_from":"baidumap","image_num":"1","link":[],"mbc":{"markv":"3"},"name":"\u5546\u4e1a\u5b66\u9662\u5317\u81ea\u884c\u8f66\u79df\u8d41\u70b9","overall_rating":"","phone":"","poi_address":"\u5357\u4eac\u5e02\u4e0b\u5173\u533a","point":{"x":13223728.09,"y":3755891.55},"price":"","price_rating":"0","service_rating":"0","shop_hours":"","shop_hours_flag":"","std_tag":"\u6559\u80b2\u57f9\u8bad;\u9ad8\u7b49\u9662\u6821","tag":"\u5b66\u6821","technology_rating":"0","validate":0,"weighted_tag":"\u5b66\u6821:10 \u6559\u80b2:10 \u5b66\u9662:10"},"src_name":"education"},"ext_display":{"display_info":{"impression_tag":{"hotel":"","life":""},"redu":"21","source_map":[],"src_name":"display_info"}},"ext_type":4,"f_flag":8,"father_son":0,"flag_type":"1","geo":".=J7c0OBjkwYWA;","geo_type":2,"indoor_pano":"","ismodified":0,"name":"\u5546\u4e1a\u5b66\u9662\u5317\u81ea\u884c\u8f66\u79df\u8d41\u70b9","navi_x":"0","navi_y":"0","new_catalog_id":"0d0100","pano":1,"poiType":0,"poi_click_num":0,"poi_profile":0,"primary_uid":"6041363013500106589","prio_flag":32,"route_flag":0,"show_tag":[],"status":1,"std_tag":"\u6559\u80b2\u57f9\u8bad;\u9ad8\u7b49\u9662\u6821","storage_src":"api","street_id":"955e2e4346b3e5be1e1d11f0","tag":"\u5b66\u6821<\/font> \u6559\u80b2 \u5b66\u9662","ty":2,"uid":"955e2e4346b3e5be1e1d11f0","view_type":11,"x":1322372809,"y":375589155},{"acc_flag":0,"addr":"\u6c5f\u82cf\u7701\u5357\u4eac\u5e02\u9f13\u697c\u533a\u91d1\u9675\u65b0\u516d\u675118\u53f7","alias":["\u5357\u4eac\u5e02\u9f13\u697c\u533a\u5c0f\u535a\u58eb\u5e7c\u513f\u56ed"],"area":1759,"area_name":"\u5357\u4eac\u5e02\u4e0b\u5173\u533a","biz_type":0,"catalogID":0,"cla":[],"click_flag":0,"detail":1,"diPointX":1322175237,"diPointY":375569719,"di_tag":"\u5e7c\u513f\u56ed \u5b66\u6821 \u6559\u80b2","dis":-1,"dist2route":0,"dist2start":0,"ext":{"detail_info":{"app_cross_rcmd":{"is_rcmd":1,"src_type":"wanneng_touchuan","sub_src":"app","transfer":1},"areaid":"1759","description_flag":1,"display_info_comment_label":{"hotel":"","life":""},"display_info_redu":"8","from_pds":"1","image":"http:\/\/hiphotos.baidu.com\/space\/pic\/item\/d52a2834349b033bb330accd1dce36d3d539bd69.jpg","image_from":"baidumap","image_num":"1","link":[],"mbc":{"markv":"3"},"name":"\u5357\u4eac\u5e02\u9f13\u697c\u533a\u5c0f\u535a\u58eb\u5e7c\u513f\u56ed(\u91d1\u71d5\u8def)","overall_rating":"0","phone":"(025)58705536","poi_address":"\u6c5f\u82cf\u7701\u5357\u4eac\u5e02\u9f13\u697c\u533a\u91d1\u9675\u65b0\u516d\u675118\u53f7","point":{"x":13221752.37,"y":3755697.19},"price":"","price_rating":"0","service_rating":"0","shop_hours":"","shop_hours_flag":"","std_tag":"\u6559\u80b2\u57f9\u8bad;\u5e7c\u513f\u56ed","tag":"\u5e7c\u513f\u56ed","technology_rating":"0","validate":0,"weighted_tag":"\u5e7c\u513f\u56ed:10 \u5b66\u6821:10 \u6559\u80b2:10"},"src_name":"education"},"ext_display":{"display_info":{"impression_tag":{"hotel":"","life":""},"redu":"8","source_map":[],"src_name":"display_info"}},"ext_type":4,"f_flag":8,"father_son":0,"flag_type":"1","geo":".=FsszOB30rYWA;","geo_type":2,"indoor_pano":"","ismodified":0,"name":"\u5357\u4eac\u5e02\u9f13\u697c\u533a\u5c0f\u535a\u58eb\u5e7c\u513f\u56ed(\u91d1\u71d5\u8def)","navi_x":"13221760.53","navi_y":"3755687.28","new_catalog_id":"0d0101","pano":1,"poiType":0,"poi_click_num":0,"poi_profile":0,"primary_uid":"9034672293442158543","prio_flag":32,"route_flag":0,"show_tag":[],"status":1,"std_tag":"\u6559\u80b2\u57f9\u8bad;\u5e7c\u513f\u56ed","storage_src":"api","street_id":"073ca6e04db34aeb4354d713","tag":"\u5e7c\u513f\u56ed \u5b66\u6821<\/font> \u6559\u80b2","tel":"(025)58705536","ty":2,"uid":"073ca6e04db34aeb4354d713","view_type":0,"x":1322175237,"y":375569719},{"acc_flag":0,"addr":"\u5357\u4eac\u5e02\u9f13\u697c\u533a","area":1759,"area_name":"\u5357\u4eac\u5e02\u4e0b\u5173\u533a","biz_type":0,"catalogID":192,"cla":[[19,"\u6559\u80b2"]],"click_flag":0,"detail":1,"diPointX":1321805183,"diPointY":375056613,"di_tag":"\u5b66\u6821 \u6559\u80b2","dis":-1,"dist2route":0,"dist2start":0,"ext":{"detail_info":{"app_cross_rcmd":{"is_rcmd":1,"src_type":"wanneng_touchuan","sub_src":"app","transfer":1},"areaid":"1759","description_flag":1,"display_info_comment_label":{"hotel":"","life":""},"display_info_redu":"19","from_pds":"1","image":"http:\/\/hiphotos.baidu.com\/space\/pic\/item\/0dd7912397dda144520a3ca5bab7d0a20cf486a2.jpg","image_from":"baidumap","image_num":"1","link":[],"mbc":{"markv":"3"},"name":"\u5357\u4eac\u5e08\u8303\u5927\u5b66\u9644\u5c5e\u4e2d\u5b66\u6811\u4eba\u5b66\u6821-3\u53f7\u697c","overall_rating":"0","phone":"","poi_address":"\u5357\u4eac\u5e02\u9f13\u697c\u533a","point":{"x":13218051.83,"y":3750566.13},"price":"","price_rating":"0","service_rating":"0","shop_hours":"","shop_hours_flag":"","std_tag":"\u6559\u80b2\u57f9\u8bad;\u5176\u4ed6","tag":"\u5b66\u6821","technology_rating":"0","validate":0,"weighted_tag":"\u5b66\u6821:10 \u6559\u80b2:10"},"src_name":"education"},"ext_display":{"display_info":{"impression_tag":{"hotel":"","life":""},"redu":"19","source_map":[],"src_name":"display_info"}},"ext_type":4,"f_flag":9,"father_son":0,"flag_type":"1","geo":".=\/VSyOBljuWWA;","geo_type":2,"indoor_pano":"","ismodified":0,"name":"\u5357\u4eac\u5e08\u8303\u5927\u5b66\u9644\u5c5e\u4e2d\u5b66\u6811\u4eba\u5b66\u6821-3\u53f7\u697c","navi_x":"13218055.27","navi_y":"3750542.56","new_catalog_id":"0d0100","pano":1,"poiType":0,"poi_click_num":0,"poi_profile":0,"primary_uid":"12548796520184728844","prio_flag":32,"route_flag":0,"show_tag":[],"status":1,"std_tag":"\u6559\u80b2\u57f9\u8bad;\u5176\u4ed6","storage_src":"api","street_id":"9cef6ce264638ccb3517dc56","tag":"\u5b66\u6821<\/font> \u6559\u80b2","ty":2,"uid":"9cef6ce264638ccb3517dc56","view_type":0,"x":1321805183,"y":375056613},{"acc_flag":0,"addr":"\u6c5f\u82cf\u7701\u5357\u4eac\u5e02\u9f13\u697c\u533a\u4e94\u4f70\u6751\u8def61\u53f7","address_norm":"[\u6c5f\u82cf\u7701(18)|PROV|0|NONE][\u5357\u4eac\u5e02(315)|CITY|0|NONE][\u4e0b\u5173\u533a(1759)|AREA|0|NONE]\u4f0d\u4f70\u675163\u53f7[\u4e94\u4f70\u6751\u8def()|ROAD|0|NONE]","alias":["\u82cf\u7ea2\u5e7c\u513f\u56ed"],"aoi":"\u5e55\u5e9c\u5c71","area":1759,"area_name":"\u5357\u4eac\u5e02\u4e0b\u5173\u533a","biz_type":0,"catalogID":476,"cla":[[19,"\u6559\u80b2"],[189,"\u5b66\u524d\u6559\u80b2"],[476,"\u5e7c\u513f\u56ed\/\u6258\u513f\u6240"]],"click_flag":0,"detail":1,"diPointX":1322414287,"diPointY":375575683,"di_tag":"\u5e7c\u513f\u56ed \u5b66\u6821 \u6559\u80b2","dis":-1,"dist2route":0,"dist2start":0,"ext":{"detail_info":{"app_cross_rcmd":{"is_rcmd":1,"src_type":"wanneng_touchuan","sub_src":"app","transfer":1},"areaid":"1759","comment_num":"0","description_flag":"","display_info_comment_label":{"hotel":"","life":""},"display_info_redu":"15","from_pds":"1","image":"http:\/\/hiphotos.baidu.com\/lbsugc\/pic\/item\/c83d70cf3bc79f3d7143e5c9bea1cd11738b29ac.jpg","image_from":"baidumap","image_num":"6","link":[],"mbc":{"markv":"3"},"name":"\u4e94\u4f70\u6751\u82cf\u7ea2\u53cc\u8bed\u5e7c\u513f\u56ed","overall_rating":"","phone":"","poi_address":"\u6c5f\u82cf\u7701\u5357\u4eac\u5e02\u9f13\u697c\u533a\u4e94\u4f70\u6751\u8def61\u53f7","point":{"x":13224142.87,"y":3755756.83},"price":"","price_rating":"0","service_rating":"0","shop_hours":"","shop_hours_flag":"","std_tag":"\u6559\u80b2\u57f9\u8bad;\u5e7c\u513f\u56ed","tag":"\u5e7c\u513f\u56ed","technology_rating":"0","validate":0,"weighted_tag":"\u5e7c\u513f\u56ed:10 \u5b66\u6821:10 \u6559\u80b2:10"},"src_name":"education"},"ext_display":{"display_info":{"impression_tag":{"hotel":"","life":""},"redu":"15","source_map":[],"src_name":"display_info"}},"ext_type":4,"f_flag":8,"father_son":0,"flag_type":"0","geo":".=PDn0OBDStYWA;","geo_type":2,"indoor_pano":"","ismodified":0,"name":"\u4e94\u4f70\u6751\u82cf\u7ea2\u53cc\u8bed\u5e7c\u513f\u56ed","navi_x":"13224119.61","navi_y":"3755777.77","new_catalog_id":"0d0101","pano":1,"poiType":0,"poi_click_num":0,"poi_profile":0,"primary_uid":"16210548306482622591","prio_flag":32,"route_flag":0,"show_tag":[],"status":16,"std_tag":"\u6559\u80b2\u57f9\u8bad;\u5e7c\u513f\u56ed","storage_src":"api","street_id":"3ea41025a56648f17369d362","tag":"\u5e7c\u513f\u56ed \u5b66\u6821<\/font> \u6559\u80b2","ty":2,"uid":"3ea41025a56648f17369d362","view_type":0,"x":1322414287,"y":375575683},{"acc_flag":0,"addr":"\u6c5f\u82cf\u7701\u5357\u4eac\u5e02\u9f13\u697c\u533a\u4e94\u767e\u6751\u8def\u9644\u8fd1","aoi":"\u5e55\u5e9c\u5c71","area":1759,"area_name":"\u5357\u4eac\u5e02\u4e0b\u5173\u533a","biz_type":0,"catalogID":0,"cla":[],"click_flag":0,"detail":1,"diPointX":1322415517,"diPointY":375585372,"di_tag":"\u5e7c\u513f\u56ed \u5b66\u6821 \u6559\u80b2","dis":-1,"dist2route":0,"dist2start":0,"ext":{"detail_info":{"app_cross_rcmd":{"is_rcmd":1,"src_type":"wanneng_touchuan","sub_src":"app","transfer":1},"areaid":"1759","description_flag":1,"display_info_comment_label":{"hotel":"","life":""},"display_info_redu":"10","from_pds":"1","image":"http:\/\/hiphotos.baidu.com\/space\/pic\/item\/d52a2834349b033bb330accd1dce36d3d539bd69.jpg","image_from":"baidumap","image_num":"1","link":[],"mbc":{"markv":"3"},"name":"\u4e94\u5858\u5e7c\u513f\u56ed\u5206\u56ed","overall_rating":"5.0","phone":"","poi_address":"\u6c5f\u82cf\u7701\u5357\u4eac\u5e02\u9f13\u697c\u533a\u4e94\u767e\u6751\u8def\u9644\u8fd1","point":{"x":13224155.17,"y":3755853.72},"price":"","price_rating":"0","service_rating":"0","shop_hours":"","shop_hours_flag":"","std_tag":"\u6559\u80b2\u57f9\u8bad;\u5e7c\u513f\u56ed","tag":"\u5e7c\u513f\u56ed","technology_rating":"0","validate":0,"weighted_tag":"\u5e7c\u513f\u56ed:10 \u5b66\u6821:10 \u6559\u80b2:10"},"src_name":"education"},"ext_display":{"display_info":{"impression_tag":{"hotel":"","life":""},"redu":"10","source_map":[],"src_name":"display_info"}},"ext_type":4,"f_flag":8,"father_son":0,"flag_type":"1","geo":".=dWn0OBcpvYWA;","geo_type":2,"indoor_pano":"","ismodified":0,"name":"\u4e94\u5858\u5e7c\u513f\u56ed\u5206\u56ed","navi_x":"0","navi_y":"0","new_catalog_id":"0d0101","pano":1,"poiType":0,"poi_click_num":0,"poi_profile":0,"primary_uid":"1319216706989541217","prio_flag":32,"route_flag":0,"show_tag":[],"status":1,"std_tag":"\u6559\u80b2\u57f9\u8bad;\u5e7c\u513f\u56ed","storage_src":"api","street_id":"a76c1f7ccbf8709eab059ddb","tag":"\u5e7c\u513f\u56ed \u5b66\u6821<\/font> \u6559\u80b2","ty":2,"uid":"a76c1f7ccbf8709eab059ddb","view_type":0,"x":1322415517,"y":375585372},{"acc_flag":0,"addr":"\u6c5f\u82cf\u7701\u5357\u4eac\u5e02\u9f13\u697c\u533a\u5b89\u6000\u8def","address_norm":"[\u6c5f\u82cf\u7701(18)|PROV|1|NONE][\u5357\u4eac\u5e02(315)|CITY|1|NONE][\u4e0b\u5173\u533a(1759)|AREA|1|NONE][\u5b89\u6000\u6751\u8def()|ROAD|0|NONE]","alias":["\u5357\u4eac\u5e02\u673a\u68b0\u4e2d\u7b49\u4e13\u4e1a\u5b66\u6821","\u5357\u4eac\u5e02\u673a\u68b0\u4e2d\u4e13"],"aoi":"\u548c\u71d5\u8def","area":1759,"area_name":"\u5357\u4eac\u5e02\u4e0b\u5173\u533a","biz_type":0,"catalogID":474,"cla":[[19,"\u6559\u80b2"],[187,"\u4e2d\u7b49\u6559\u80b2"]],"click_flag":0,"detail":1,"diPointX":1322454620,"diPointY":375414970,"di_tag":"\u4e2d\u5b66 \u5b66\u6821 \u9ad8\u4e2d \u6559\u80b2 \u804c\u4e1a\u9ad8\u4e2d","dis":-1,"dist2route":0,"dist2start":0,"ext":{"detail_info":{"app_cross_rcmd":{"is_rcmd":1,"src_type":"wanneng_touchuan","sub_src":"app","transfer":1},"areaid":"1759","description_flag":1,"display_info_comment_label":{"hotel":"","life":""},"display_info_redu":"20","from_pds":"1","image":"http:\/\/hiphotos.baidu.com\/lbsugc\/c%3Dmap%2C90%2C90\/sign=7605c0f2e124b899ca3c21691e2a24a5\/7acb0a46f21fbe09355e29e663600c338644adc4.jpg","image_from":"ruitudetail","image_num":"2","link":[],"mbc":{"markv":"3"},"name":"\u5357\u4eac\u5e02\u673a\u68b0\u4e2d\u7b49\u4e13\u4e1a\u5b66\u6821","overall_rating":"5.0","phone":"025-85080515","poi_address":"\u6c5f\u82cf\u7701\u5357\u4eac\u5e02\u9f13\u697c\u533a\u5b89\u6000\u8def","point":{"x":13224546.2,"y":3754149.7},"price":"","price_rating":"0","service_rating":"0","shop_hours":"","shop_hours_flag":"","std_tag":"\u6559\u80b2\u57f9\u8bad;\u4e2d\u5b66","tag":"\u804c\u4e1a\u9ad8\u4e2d","technology_rating":"0","validate":0,"weighted_tag":"\u4e2d\u5b66:10 \u5b66\u6821:10 \u9ad8\u4e2d:10 \u6559\u80b2:10 \u804c\u4e1a\u9ad8\u4e2d:10"},"src_name":"education"},"ext_display":{"display_info":{"impression_tag":{"hotel":"","life":""},"redu":"20","source_map":[],"src_name":"display_info"}},"ext_type":4,"f_flag":25,"father_son":0,"flag_type":"0","geo":".=c5w0OB6CGYWA;","geo_type":2,"indoor_pano":"","ismodified":0,"name":"\u5357\u4eac\u5e02\u673a\u68b0\u4e2d\u7b49\u4e13\u4e1a\u5b66\u6821","navi_x":"13224633.3252","navi_y":"3754141.32622","new_catalog_id":"0d0104","pano":1,"poiType":0,"poi_click_num":0,"poi_profile":0,"primary_uid":"17629016475605925887","prio_flag":32,"route_flag":0,"show_tag":[],"status":1,"std_tag":"\u6559\u80b2\u57f9\u8bad;\u4e2d\u5b66","storage_src":"api","street_id":"e3e2cd9d2ec25d9dc6f50dbc","tag":"\u4e2d\u5b66 \u5b66\u6821<\/font> \u9ad8\u4e2d \u6559\u80b2 \u804c\u4e1a\u9ad8\u4e2d","tel":"025-85080515","ty":2,"uid":"e3e2cd9d2ec25d9dc6f50dbc","view_type":0,"x":1322454620,"y":375414970},{"acc_flag":0,"addr":"\u4e1c\u4e95\u67513\u53f7","aoi":"\u548c\u71d5\u8def","area":1759,"area_name":"\u5357\u4eac\u5e02\u4e0b\u5173\u533a","biz_type":0,"catalogID":0,"cla":[],"click_flag":0,"detail":1,"diPointX":1322578353,"diPointY":375420577,"di_tag":"\u5e7c\u513f\u56ed \u5b66\u6821 \u6559\u80b2","dis":-1,"dist2route":0,"dist2start":0,"ext":{"detail_info":{"areaid":"1759","description_flag":1,"from_pds":"1","image":"http:\/\/hiphotos.baidu.com\/space\/pic\/item\/d52a2834349b033bb330accd1dce36d3d539bd69.jpg","image_from":"baidumap","image_num":"1","link":[],"mbc":{"markv":"3"},"name":"\u5357\u4eac\u5e02\u4e0b\u5173\u533a\u5b9e\u9a8c\u5e7c\u513f\u56ed","overall_rating":"","phone":"","poi_address":"\u4e1c\u4e95\u67513\u53f7","point":{"x":13225783.53,"y":3754205.77},"price":"","price_rating":"0","service_rating":"0","shop_hours":"","shop_hours_flag":"","std_tag":"\u6559\u80b2\u57f9\u8bad;\u5e7c\u513f\u56ed","tag":"\u5e7c\u513f\u56ed","technology_rating":"0","validate":0,"weighted_tag":"\u5e7c\u513f\u56ed:10 \u5b66\u6821:10 \u6559\u80b2:10"},"src_name":"education"},"ext_type":4,"f_flag":8,"father_son":0,"flag_type":"1","geo":".=xGP1OBhaHYWA;","geo_type":2,"name":"\u5357\u4eac\u5e02\u4e0b\u5173\u533a\u5b9e\u9a8c\u5e7c\u513f\u56ed","navi_x":"0","navi_y":"0","new_catalog_id":"0d0101","poiType":0,"poi_click_num":0,"poi_profile":0,"primary_uid":"11388100803968612101","prio_flag":32,"route_flag":0,"show_tag":[],"status":1,"std_tag":"\u6559\u80b2\u57f9\u8bad;\u5e7c\u513f\u56ed","storage_src":"api","tag":"\u5e7c\u513f\u56ed \u5b66\u6821<\/font> \u6559\u80b2","ty":2,"uid":"ec521203e25b7bf45cd4fedc","view_type":0,"x":1322578353,"y":375420577},{"acc_flag":0,"addr":"\u6c5f\u82cf\u7701\u5357\u4eac\u5e02\u9f13\u697c\u533a\u4f0d\u4f70\u675163\u53f7","aoi":"\u5e55\u5e9c\u5c71","area":1759,"area_name":"\u5357\u4eac\u5e02\u4e0b\u5173\u533a","biz_type":0,"catalogID":0,"cla":[],"click_flag":0,"detail":1,"diPointX":1322422180,"diPointY":375579317,"di_tag":"\u5e7c\u513f\u56ed \u5b66\u6821 \u6559\u80b2","dis":-1,"dist2route":0,"dist2start":0,"ext":{"detail_info":{"app_cross_rcmd":{"is_rcmd":1,"src_type":"wanneng_touchuan","sub_src":"app","transfer":1},"areaid":"1759","description_flag":1,"display_info_comment_label":{"hotel":"","life":""},"display_info_redu":"29","from_pds":"1","image":"http:\/\/hiphotos.baidu.com\/space\/pic\/item\/d52a2834349b033bb330accd1dce36d3d539bd69.jpg","image_from":"baidumap","image_num":"1","link":[],"mbc":{"markv":"3"},"name":"\u82cf\u7ea2\u8bed\u5e7c\u513f\u56ed","overall_rating":"5.0","phone":"","poi_address":"\u6c5f\u82cf\u7701\u5357\u4eac\u5e02\u9f13\u697c\u533a\u4f0d\u4f70\u675163\u53f7","point":{"x":13224221.8,"y":3755793.17},"price":"","price_rating":"0","service_rating":"0","shop_hours":"","shop_hours_flag":"","std_tag":"\u6559\u80b2\u57f9\u8bad;\u5e7c\u513f\u56ed","tag":"\u5e7c\u513f\u56ed","technology_rating":"0","validate":0,"weighted_tag":"\u5e7c\u513f\u56ed:10 \u5b66\u6821:10 \u6559\u80b2:10"},"src_name":"education"},"ext_display":{"display_info":{"impression_tag":{"hotel":"","life":""},"redu":"29","source_map":[],"src_name":"display_info"}},"ext_type":4,"f_flag":8,"father_son":0,"flag_type":"1","geo":".=k+o0OB1KuYWA;","geo_type":2,"name":"\u82cf\u7ea2\u8bed\u5e7c\u513f\u56ed","navi_x":"0","navi_y":"0","new_catalog_id":"0d0101","poiType":0,"poi_click_num":0,"poi_profile":0,"primary_uid":"1184868010645238334","prio_flag":32,"route_flag":0,"show_tag":[],"status":1,"std_tag":"\u6559\u80b2\u57f9\u8bad;\u5e7c\u513f\u56ed","storage_src":"api","tag":"\u5e7c\u513f\u56ed \u5b66\u6821<\/font> \u6559\u80b2","ty":2,"uid":"e9aac49ed482511b095cb381","view_type":0,"x":1322422180,"y":375579317},{"acc_flag":0,"addr":"\u6c5f\u82cf\u7701\u5357\u4eac\u5e02\u9f13\u697c\u533a\u4e94\u767e\u6751\u8def","area":1759,"area_name":"\u5357\u4eac\u5e02\u4e0b\u5173\u533a","biz_type":0,"catalogID":0,"cla":[],"click_flag":0,"detail":1,"diPointX":1322372475,"diPointY":375555527,"di_tag":"\u5e7c\u513f\u56ed \u5b66\u6821 \u6559\u80b2","dis":-1,"dist2route":0,"dist2start":0,"ext":{"detail_info":{"app_cross_rcmd":{"is_rcmd":1,"src_type":"wanneng_touchuan","sub_src":"app","transfer":1},"areaid":"1759","description_flag":"","display_info_comment_label":{"hotel":"","life":""},"display_info_redu":"26","from_pds":"1","image":"http:\/\/hiphotos.baidu.com\/space\/pic\/item\/d52a2834349b033bb330accd1dce36d3d539bd69.jpg","image_from":"baidumap","image_num":"1","link":[],"mbc":{"markv":"3"},"name":"\u5357\u4eac\u5e02\u7389\u5858\u5e7c\u513f\u56ed","overall_rating":"5.0","phone":"","poi_address":"\u6c5f\u82cf\u7701\u5357\u4eac\u5e02\u9f13\u697c\u533a\u4e94\u767e\u6751\u8def","point":{"x":13223724.75,"y":3755555.27},"price":"","price_rating":"0","service_rating":"0","shop_hours":"","shop_hours_flag":"","std_tag":"\u6559\u80b2\u57f9\u8bad;\u5e7c\u513f\u56ed","tag":"\u5e7c\u513f\u56ed","technology_rating":"0","validate":0,"weighted_tag":"\u5e7c\u513f\u56ed:10 \u5b66\u6821:10 \u6559\u80b2:10"},"src_name":"education"},"ext_display":{"display_info":{"impression_tag":{"hotel":"","life":""},"redu":"26","source_map":[],"src_name":"display_info"}},"ext_type":4,"f_flag":8,"father_son":0,"flag_type":"1","geo":".=71c0OBHXoYWA;","geo_type":2,"indoor_pano":"","ismodified":0,"name":"\u5357\u4eac\u5e02\u7389\u5858\u5e7c\u513f\u56ed","navi_x":"0","navi_y":"0","new_catalog_id":"0d0101","pano":1,"poiType":0,"poi_click_num":0,"poi_profile":0,"primary_uid":"17104010375902178382","prio_flag":32,"route_flag":0,"show_tag":[],"status":1,"std_tag":"\u6559\u80b2\u57f9\u8bad;\u5e7c\u513f\u56ed","storage_src":"api","street_id":"7ccec4c0120e53c385420ed1","tag":"\u5e7c\u513f\u56ed \u5b66\u6821<\/font> \u6559\u80b2","ty":2,"uid":"7ccec4c0120e53c385420ed1","view_type":0,"x":1322372475,"y":375555527},{"acc_flag":0,"addr":"\u6c5f\u82cf\u7701\u5357\u4eac\u5e02\u9f13\u697c\u533a\u65b9\u5bb6\u8425573\u53f7","area":1759,"area_name":"\u5357\u4eac\u5e02\u4e0b\u5173\u533a","biz_type":0,"catalogID":0,"cla":[],"click_flag":0,"detail":1,"diPointX":1321997981,"diPointY":375462655,"di_tag":"\u5e7c\u513f\u56ed \u5b66\u6821 \u6559\u80b2","dis":-1,"dist2route":0,"dist2start":0,"ext":{"detail_info":{"app_cross_rcmd":{"is_rcmd":1,"src_type":"wanneng_touchuan","sub_src":"app","transfer":1},"areaid":"1759","description_flag":"","display_info_comment_label":{"hotel":"","life":""},"display_info_redu":"28","from_pds":"1","image":"http:\/\/hiphotos.baidu.com\/space\/pic\/item\/d52a2834349b033bb330accd1dce36d3d539bd69.jpg","image_from":"baidumap","image_num":"1","link":[],"mbc":{"markv":"3"},"name":"\u95fd\u6c5f\u5e7c\u513f\u56ed","overall_rating":"","phone":"","poi_address":"\u6c5f\u82cf\u7701\u5357\u4eac\u5e02\u9f13\u697c\u533a\u65b9\u5bb6\u8425573\u53f7","point":{"x":13219979.81,"y":3754626.55},"price":"","price_rating":"0","service_rating":"0","shop_hours":"","shop_hours_flag":"","std_tag":"\u6559\u80b2\u57f9\u8bad;\u5e7c\u513f\u56ed","tag":"\u5e7c\u513f\u56ed","technology_rating":"0","validate":0,"weighted_tag":"\u5e7c\u513f\u56ed:10 \u5b66\u6821:10 \u6559\u80b2:10"},"src_name":"education"},"ext_display":{"display_info":{"impression_tag":{"hotel":"","life":""},"redu":"28","source_map":[],"src_name":"display_info"}},"ext_type":4,"f_flag":8,"father_son":0,"flag_type":"1","geo":".=daBzOB\/rRYWA;","geo_type":2,"indoor_pano":"","ismodified":0,"name":"\u95fd\u6c5f\u5e7c\u513f\u56ed","navi_x":"0","navi_y":"0","new_catalog_id":"0d0101","pano":1,"poiType":0,"poi_click_num":0,"poi_profile":0,"primary_uid":"401576626206060435","prio_flag":32,"route_flag":0,"show_tag":[],"status":1,"std_tag":"\u6559\u80b2\u57f9\u8bad;\u5e7c\u513f\u56ed","storage_src":"api","street_id":"ff8fee67a8655226882fb5b3","tag":"\u5e7c\u513f\u56ed \u5b66\u6821<\/font> \u6559\u80b2","ty":2,"uid":"ff8fee67a8655226882fb5b3","view_type":0,"x":1321997981,"y":375462655},{"acc_flag":0,"addr":"\u6c5f\u82cf\u7701\u5357\u4eac\u5e02\u9f13\u697c\u533a\u91d1\u71d5\u8def95\u53f7","area":1759,"area_name":"\u5357\u4eac\u5e02\u4e0b\u5173\u533a","biz_type":0,"catalogID":0,"cla":[],"click_flag":0,"detail":1,"diPointX":1322155598,"diPointY":375516777,"di_tag":"\u5e7c\u513f\u56ed \u5b66\u6821 \u6559\u80b2","dis":-1,"dist2route":0,"dist2start":0,"ext":{"detail_info":{"app_cross_rcmd":{"is_rcmd":1,"src_type":"wanneng_touchuan","sub_src":"app","transfer":1},"areaid":"1759","description_flag":"","display_info_comment_label":{"hotel":"","life":""},"display_info_redu":"18","from_pds":"1","image":"http:\/\/hiphotos.baidu.com\/space\/pic\/item\/d52a2834349b033bb330accd1dce36d3d539bd69.jpg","image_from":"baidumap","image_num":"1","link":[],"mbc":{"markv":"3"},"name":"\u751c\u5b9d\u8d1d\u5e7c\u513f\u56ed","overall_rating":"5.0","phone":"13776644672","poi_address":"\u6c5f\u82cf\u7701\u5357\u4eac\u5e02\u9f13\u697c\u533a\u91d1\u71d5\u8def95\u53f7","point":{"x":13221555.98,"y":3755167.77},"price":"","price_rating":"0","service_rating":"0","shop_hours":"","shop_hours_flag":"","std_tag":"\u6559\u80b2\u57f9\u8bad;\u5e7c\u513f\u56ed","tag":"\u5e7c\u513f\u56ed","technology_rating":"0","validate":0,"weighted_tag":"\u5e7c\u513f\u56ed:10 \u5b66\u6821:10 \u6559\u80b2:10"},"src_name":"education"},"ext_display":{"display_info":{"impression_tag":{"hotel":"","life":""},"redu":"18","source_map":[],"src_name":"display_info"}},"ext_type":4,"f_flag":8,"father_son":0,"flag_type":"1","geo":".=O5nzOBp5eYWA;","geo_type":2,"indoor_pano":"","ismodified":0,"name":"\u751c\u5b9d\u8d1d\u5e7c\u513f\u56ed","navi_x":"0","navi_y":"0","new_catalog_id":"0d0101","pano":1,"poiType":0,"poi_click_num":0,"poi_profile":0,"primary_uid":"8013678660733285336","prio_flag":32,"route_flag":0,"show_tag":[],"status":1,"std_tag":"\u6559\u80b2\u57f9\u8bad;\u5e7c\u513f\u56ed","storage_src":"api","street_id":"fcb80b8c7f8d1dd16fa3ccfd","tag":"\u5e7c\u513f\u56ed \u5b66\u6821<\/font> \u6559\u80b2","tel":"13776644672","ty":2,"uid":"fcb80b8c7f8d1dd16fa3ccfd","view_type":0,"x":1322155598,"y":375516777},{"acc_flag":0,"addr":"\u6c5f\u82cf\u7701\u5357\u4eac\u5e02\u9f13\u697c\u533a\u70ed\u6cb3\u5357\u8def92-1\u53f7","alias":["\u5357\u4eac\u5e02\u9f13\u697c\u533a\u7279\u6b8a\u6559\u80b2\u5b66\u6821\u4e8c\u677f\u6865\u6821\u533a"],"area":1759,"area_name":"\u5357\u4eac\u5e02\u4e0b\u5173\u533a","biz_type":0,"catalogID":0,"cla":[],"click_flag":0,"detail":1,"diPointX":1321894608,"diPointY":375156021,"di_tag":"\u5b66\u6821 \u6559\u80b2 \u7279\u6b8a\u6559\u80b2\u5b66\u6821","dis":-1,"dist2route":0,"dist2start":0,"ext":{"detail_info":{"app_cross_rcmd":{"is_rcmd":1,"src_type":"wanneng_touchuan","sub_src":"app","transfer":1},"areaid":"1759","description_flag":1,"display_info_comment_label":{"hotel":"","life":""},"display_info_redu":"2","from_pds":"1","image":"http:\/\/hiphotos.baidu.com\/space\/pic\/item\/0e2442a7d933c895aca3c86cd91373f0830200c3.jpg","image_from":"baidumap","image_num":"1","link":[],"mbc":{"markv":"3"},"name":"\u5357\u4eac\u5e02\u9f13\u697c\u533a\u7279\u6b8a\u6559\u80b2\u5b66\u6821(\u4e8c\u677f\u6865\u6821\u533a)","overall_rating":"0","phone":"","poi_address":"\u6c5f\u82cf\u7701\u5357\u4eac\u5e02\u9f13\u697c\u533a\u70ed\u6cb3\u5357\u8def92-1\u53f7","point":{"x":13218946.08,"y":3751560.21},"price":"","price_rating":"0","service_rating":"0","shop_hours":"","shop_hours_flag":"","std_tag":"\u6559\u80b2\u57f9\u8bad;\u7279\u6b8a\u6559\u80b2\u5b66\u6821","tag":"\u7279\u6b8a\u6559\u80b2\u5b66\u6821","technology_rating":"0","validate":0,"weighted_tag":"\u5b66\u6821:10 \u6559\u80b2:10 \u7279\u6b8a\u6559\u80b2\u5b66\u6821:10"},"src_name":"education"},"ext_display":{"display_info":{"impression_tag":{"hotel":"","life":""},"redu":"2","source_map":[],"src_name":"display_info"}},"ext_type":4,"f_flag":25,"father_son":0,"flag_type":"1","geo":".=QLoyOB10GXWA;","geo_type":2,"indoor_pano":"","ismodified":1,"name":"\u5357\u4eac\u5e02\u9f13\u697c\u533a\u7279\u6b8a\u6559\u80b2\u5b66\u6821(\u4e8c\u677f\u6865\u6821\u533a)","navi_x":"13218948.56","navi_y":"3751575.56","new_catalog_id":"0d0107","pano":1,"poiType":0,"poi_click_num":0,"poi_profile":0,"primary_uid":"13137125749058710915","prio_flag":32,"route_flag":0,"show_tag":[],"status":1,"std_tag":"\u6559\u80b2\u57f9\u8bad;\u7279\u6b8a\u6559\u80b2\u5b66\u6821","storage_src":"api","street_id":"081b84ee07062ae29e82ea33","tag":"\u5b66\u6821<\/font> \u6559\u80b2 \u7279\u6b8a\u6559\u80b2\u5b66\u6821<\/font>","ty":2,"uid":"081b84ee07062ae29e82ea33","view_type":0,"x":1321894608,"y":375156021}],"current_city":{"code":315,"geo":".=OYB1OBNVGWWA;","level":12,"name":"\u5357\u4eac\u5e02","sup":1,"sup_bus":1,"sup_business_area":1,"sup_lukuang":1,"sup_subway":1,"type":2,"up_province_name":"\u6c5f\u82cf\u7701"},"hot_city":["\u5317\u4eac\u5e02|131","\u4e0a\u6d77\u5e02|289","\u5e7f\u5dde\u5e02|257","\u6df1\u5733\u5e02|340","\u6210\u90fd\u5e02|75","\u5929\u6d25\u5e02|332","\u5357\u4eac\u5e02|315","\u676d\u5dde\u5e02|179","\u6b66\u6c49\u5e02|218","\u91cd\u5e86\u5e02|132"],"result":{"ad_display_type":0,"aladdin_res_num":118,"aladin_query_type":0,"area_profile":0,"business_bound":"","catalogID":0,"cmd_no":1,"count":18,"current_null":0,"data_security_filt_res":0,"db":0,"debug":0,"jump_back":1,"loc_attr":0,"op_gel":1,"page_num":2,"pattern_sign":0,"profile_uid":"2bfcb14cf5cb2cfcc23644f2","qid":"5214662275973558666","requery":"","res_bound":"(13217665,3750566;13226170,3756056)","res_bound_acc":"(0,0;0,0)","res_l":0,"res_x":"0.000000","res_y":"0.000000","result_show":0,"return_query":"\u5357\u4eac\u5e02 \u4e0b\u5173\u533a \u5b66\u6821","rp_strategy":0,"spec_dispnum":0,"spothot":false,"sug_index":-1,"time":0,"total":118,"total_busline_num":0,"tp":0,"type":11,"wd":"\u5357\u4eac\u5e02 \u4e0b\u5173\u533a \u5b66\u6821","wd2":"","what":"\u5b66\u6821","where":"\u5357\u4eac\u5e02\u4e0b\u5173\u533a","error":0,"qt":"s","c":"1","cb_wd":"\u5357\u4eac\u5e02 \u4e0b\u5173\u533a \u5b66\u6821","rn":"100","pn":"2","ie":"utf-8","oue":"1","fromproduct":"jsapi","res":"api","callback":"BMap._rd._cbk68355","ak":"lQ9Lw03f6Y9eARzgQZNyaolD8vQqtOv4"}}');
        var_dump($filearr);
        print_r($file_contents);
        exit();
    }
} else {
    message("请求方式不存在");
}
$basic = pdo_fetch("select * from " . tablename('enjoy_city_reply') . " where uniacid=0");
include $this->template('firm');