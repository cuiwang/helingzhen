<?php
global $_GPC, $_W;
load()->func("tpl");
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$uniacid = $_W['uniacid'];
$basic = pdo_fetch("select * from " . tablename('enjoy_city_reply') . " where uniacid=0");
if ($operation=='display') {
    if (!empty($_GPC['hot'])) {
        foreach ($_GPC['hot'] as $id => $hot) {
            pdo_update('enjoy_city_kind', array(
                'hot' => $hot
            ), array(
                'id' => $id
            ));
        }
        message("分类排序更新成功！", $this->createWebUrl('kind', array(
            'op' => 'display'
        )), 'success');
    }
    $children = array();
    $kind = pdo_fetchall("SELECT * FROM " . tablename('enjoy_city_kind') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY parentid ASC, hot ASC");
    foreach ($kind as $index => $row) {
        if (!empty($row['parentid'])) {
            $children[$row['parentid']][] = $row;
            unset($kind[$index]);
        }
    }
    include $this->template('kind');
} elseif ($operation=='post') {
    $parentid = intval($_GPC['parentid']);
    $id = intval($_GPC['id']);
    if (!empty($id)) {
        $kind = pdo_fetch("SELECT * FROM " . tablename('enjoy_city_kind') . " WHERE id = '$id'");
    } else {
        $kind = array(
            'hot' => 0
        );
    }
    if (!empty($parentid)) {
        $parent = pdo_fetch("SELECT id, name FROM " . tablename('enjoy_city_kind') . " WHERE id = '$parentid'");
        if (empty($parent)) {
            message('抱歉，上级分类不存在或是已经被删除！', $this->createWebUrl('post'), 'error');
        }
    }
    if (checksubmit("submit")) {
        if (empty($_GPC['catename'])) {
            message('抱歉，请输入分类名称！');
        }
        $data = array(
            'uniacid' => $_W['uniacid'],
            'name' => $_GPC['catename'],
            'enabled' => intval($_GPC['enabled']),
            'hot' => intval($_GPC['hot']),
            'parentid' => intval($parentid),
            'thumb' => $_GPC['thumb'],
            'wurl' => trim($_GPC['wurl']),
            'headurl' => trim($_GPC['headurl']),
            'footurl' => trim($_GPC['footurl']),
            'headimg' => trim($_GPC['headimg']),
            'footimg' => trim($_GPC['footimg'])
        );
        if (!empty($id)) {
            unset($data['parentid']);
            pdo_update("enjoy_city_kind", $data, array(
                'id' => $id
            ));
            load()->func("file");
            file_delete($_GPC['thumb_old']);
        } else {
            pdo_insert("enjoy_city_kind", $data);
            $id = pdo_insertid();
        }
        message("更新分类成功！", $this->createWebUrl('kind', array(
            'op' => 'display'
        )), 'success');
    }
    include $this->template('kind');
} elseif ($operation=='delete') {
    $id = intval($_GPC['id']);
    $kind = pdo_fetch("SELECT id, parentid FROM " . tablename('enjoy_city_kind') . " WHERE id = '$id'");
    if (empty($kind)) {
        message('抱歉，分类不存在或是已经被删除！', $this->createWebUrl('kind', array(
            'op' => 'display'
        )), 'error');
    }
    pdo_delete("enjoy_city_kind", array(
        "id" => $id,
        'parentid' => $id
    ), 'OR');
    message("分类删除成功！", $this->createWebUrl('kind', array(
        'op' => 'display'
    )), 'success');
} elseif ($operation=='cshkind') {
    pdo_query("delete from " . tablename('enjoy_city_kind') . " where uniacid=" . $uniacid . "");
    $parentid = $this->insert_default_category('政府机关', 'p5vEx3392G3dxV2U31CF29HH1VX5k9.jpg', 1);
    $this->insert_default_category('县政府', '', 1, $parentid);
    $this->insert_default_category('财政局', '', 2, $parentid);
    $parentid = $this->insert_default_category('美食天地', 'x3n0aDqQJx3B3Q7Xq7J0I6ZMaxQTNn.jpg', 2);
    $this->insert_default_category('饭店餐厅', '', 1, $parentid);
    $this->insert_default_category('快餐外卖', '', 2, $parentid);
    $this->insert_default_category('烧烤麻辣', '', 3, $parentid);
    $this->insert_default_category('夜宵天地', '', 4, $parentid);
    $this->insert_default_category('蛋糕饮品', '', 5, $parentid);
    $this->insert_default_category('火锅香锅', '', 6, $parentid);
    $this->insert_default_category('茶餐西餐', '', 7, $parentid);
    $this->insert_default_category('向东街美食', '', 8, $parentid);
    $this->insert_default_category('土特产', '', 9, $parentid);
    $this->insert_default_category('海鲜水产', '', 10, $parentid);
    $parentid = $this->insert_default_category('休闲娱乐', 'Z3Qq7X43xnN4VAdcV1PndLPH3R9lpa.jpg', 3);
    $this->insert_default_category('KTV', '', 1, $parentid);
    $this->insert_default_category('酒吧', '', 2, $parentid);
    $this->insert_default_category('茶馆', '', 3, $parentid);
    $this->insert_default_category('咖啡厅', '', 4, $parentid);
    $this->insert_default_category('电影院', '', 5, $parentid);
    $this->insert_default_category('足浴洗浴', '', 6, $parentid);
    $this->insert_default_category('按摩推拿', '', 7, $parentid);
    $this->insert_default_category('网吧', '', 8, $parentid);
    $this->insert_default_category('游泳池', '', 9, $parentid);
    $this->insert_default_category('汗蒸养生', '', 10, $parentid);
    $parentid = $this->insert_default_category('宾馆住宿', 'FQ78uQ5Z9pQAqqfUuy0y8745585q5v.jpg', 4);
    $this->insert_default_category('商务宾馆', '', 1, $parentid);
    $this->insert_default_category('家庭宾馆', '', 2, $parentid);
    $parentid = $this->insert_default_category('拼车租车', 'Qsvqxz5BzTBZ6K5ZbeN75wBn165tB7.jpg', 5);
    $parentid = $this->insert_default_category('快递物流', 'GMDYarnLPuxb16PL6zB0ajDCUDDJMd.jpg', 6);
    $this->insert_default_category('快递', '', 1, $parentid);
    $parentid = $this->insert_default_category('时尚女人', 'uHy6wWFQWWHwH8XjARwWy63vzWXGoH.jpg', 7);
    $this->insert_default_category('美发', '', 1, $parentid);
    $this->insert_default_category('美容美体', '', 2, $parentid);
    $this->insert_default_category('美甲', '', 3, $parentid);
    $this->insert_default_category('面膜', '', 4, $parentid);
    $this->insert_default_category('化妆品', '', 5, $parentid);
    $parentid = $this->insert_default_category('便民服务', 'kq6dq8PiFIeZ1Zi62aQM68c2G2iWQW.jpg', 8);
    $this->insert_default_category('桶装水', '', 1, $parentid);
    $this->insert_default_category('同城快跑', '', 2, $parentid);
    $this->insert_default_category('开锁', '', 3, $parentid);
    $this->insert_default_category('疏通', '', 4, $parentid);
    $this->insert_default_category('保姆保洁', '', 5, $parentid);
    $this->insert_default_category('洗衣店', '', 6, $parentid);
    $this->insert_default_category('二手回收', '', 7, $parentid);
    $this->insert_default_category('征婚', '', 8, $parentid);
    $this->insert_default_category('成人用品', '', 9, $parentid);
    $this->insert_default_category('宠物相关', '', 10, $parentid);
    $this->insert_default_category('办公设备', '', 11, $parentid);
    $this->insert_default_category('代购', '', 12, $parentid);
    $this->insert_default_category('煤气', '', 13, $parentid);
    $this->insert_default_category('瓜果蔬粮', '', 14, $parentid);
    $this->insert_default_category('搬家公司', '', 15, $parentid);
    $parentid = $this->insert_default_category('汽车相关', 'WXLoZRx2opobHoBhBB00xBxXrPpp84.jpg', 9);
    $this->insert_default_category('汽车销售', '', 1, $parentid);
    $this->insert_default_category('驾校/教练', '', 2, $parentid);
    $this->insert_default_category('轮胎相关', '', 3, $parentid);
    $this->insert_default_category('二手中介', '', 4, $parentid);
    $this->insert_default_category('洗车内饰', '', 5, $parentid);
    $this->insert_default_category('维修保养', '', 6, $parentid);
    $this->insert_default_category('摩托天地', '', 7, $parentid);
    $parentid = $this->insert_default_category('手机电脑', 'lFc3R8fQm424rTfh8rmMBff0ZCb8Z4.jpg', 10);
    $this->insert_default_category('手机专卖', '', 1, $parentid);
    $this->insert_default_category('二手手机', '', 2, $parentid);
    $this->insert_default_category('电脑专卖', '', 3, $parentid);
    $this->insert_default_category('电脑维修', '', 4, $parentid);
    $parentid = $this->insert_default_category('服装鞋包', 'GQQSFNSQsUT1bjjz1JmlQ15qUmJ31m.jpg', 11);
    $this->insert_default_category('男女服装', '', 1, $parentid);
    $this->insert_default_category('鞋子', '', 2, $parentid);
    $this->insert_default_category('童装', '', 3, $parentid);
    $this->insert_default_category('皮具皮草', '', 4, $parentid);
    $parentid = $this->insert_default_category('婚庆礼仪', 'rYBEKK33G29ZBg339E0bIk3BB3B0Bx.jpg', 12);
    $this->insert_default_category('婚庆公司', '', 1, $parentid);
    $this->insert_default_category('庆典礼仪', '', 2, $parentid);
    $this->insert_default_category('婚车租赁', '', 3, $parentid);
    $this->insert_default_category('喜糖铺子', '', 4, $parentid);
    $this->insert_default_category('主持司仪', '', 5, $parentid);
    $parentid = $this->insert_default_category('摄影摄像', 'dAUd0uUy958Gr8WDWaG95GU99G0rw0.jpg', 13);
    $this->insert_default_category('婚纱摄影', '', 1, $parentid);
    $this->insert_default_category('儿童摄影', '', 2, $parentid);
    $this->insert_default_category('婚庆跟拍', '', 3, $parentid);
    $this->insert_default_category('写真拍摄', '', 4, $parentid);
    $this->insert_default_category('影视制作', '', 5, $parentid);
    $parentid = $this->insert_default_category('医药诊所', 'q89PDCD661eO99Ttneo8CPEzct8rOZ.jpg', 14);
    $this->insert_default_category('医院', '', 1, $parentid);
    $this->insert_default_category('药店', '', 2, $parentid);
    $this->insert_default_category('特色诊所', '', 3, $parentid);
    $this->insert_default_category('家庭医生', '', 4, $parentid);
    $this->insert_default_category('草药郎中', '', 5, $parentid);
    $parentid = $this->insert_default_category('教育培训', 'hvg6IU7OAB5Kvi7QP64Gos44o50IoB.jpg', 15);
    $this->insert_default_category('公办学校', '', 1, $parentid);
    $this->insert_default_category('幼儿园', '', 2, $parentid);
    $this->insert_default_category('艺术培训', '', 3, $parentid);
    $this->insert_default_category('体育培训', '', 4, $parentid);
    $this->insert_default_category('职业培训', '', 5, $parentid);
    $this->insert_default_category('上门家教', '', 6, $parentid);
    $parentid = $this->insert_default_category('房产相关', 'Kv6B7FU4V6BbuInb74jNzGBNR73itU.jpg', 16);
    $this->insert_default_category('楼盘', '', 1, $parentid);
    $this->insert_default_category('中介二手', '', 2, $parentid);
    $this->insert_default_category('房产评估', '', 3, $parentid);
    $parentid = $this->insert_default_category('家居建材', 'l9w4TWnekQQo3O4K4eiE64oM2cnc43.jpg', 17);
    $this->insert_default_category('装饰公司', '', 1, $parentid);
    $this->insert_default_category('五金', '', 2, $parentid);
    $this->insert_default_category('水暖', '', 3, $parentid);
    $this->insert_default_category('背景墙纸', '', 4, $parentid);
    $this->insert_default_category('家饰工艺', '', 5, $parentid);
    $this->insert_default_category('石材', '', 6, $parentid);
    $this->insert_default_category('钢筋水泥', '', 7, $parentid);
    $this->insert_default_category('窗帘家纺', '', 8, $parentid);
    $this->insert_default_category('不锈钢铝材', '', 9, $parentid);
    $this->insert_default_category('门窗炉灶', '', 10, $parentid);
    $this->insert_default_category('不锈钢铝材', '', 11, $parentid);
    $this->insert_default_category('门窗炉灶', '', 12, $parentid);
    $this->insert_default_category('灯饰', '', 13, $parentid);
    $this->insert_default_category('油漆涂料', '', 14, $parentid);
    $this->insert_default_category('整体衣柜', '', 15, $parentid);
    $this->insert_default_category('吊顶', '', 16, $parentid);
    $this->insert_default_category('橱柜', '', 17, $parentid);
    $this->insert_default_category('木线板材', '', 18, $parentid);
    $this->insert_default_category('家私家具', '', 19, $parentid);
    $this->insert_default_category('门业', '', 20, $parentid);
    $this->insert_default_category('木地板', '', 21, $parentid);
    $this->insert_default_category('陶瓷', '', 22, $parentid);
    $this->insert_default_category('卫浴', '', 23, $parentid);
    $this->insert_default_category('楼梯', '', 2, $parentid);
    $parentid = $this->insert_default_category('家用电器', 'LU1gO2uYoju57E2GoOKGggKEGuRYGe.jpg', 18);
    $this->insert_default_category('家居电器', '', 1, $parentid);
    $parentid = $this->insert_default_category('旅游', 'vdXAb87n7b4NuxL3cmxS2HN3Z732aw.jpg', 19);
    $this->insert_default_category('知名景点', '', 1, $parentid);
    $this->insert_default_category('景点宾馆', '', 2, $parentid);
    $this->insert_default_category('旅行社', '', 3, $parentid);
    $this->insert_default_category('农家乐', '', 4, $parentid);
    $this->insert_default_category('旅游用车', '', 5, $parentid);
    $parentid = $this->insert_default_category('茗茶烟酒', 'K5J51jG6UgJ51FbPpp551156UgqGqj.jpg', 20);
    $this->insert_default_category('名烟名酒', '', 1, $parentid);
    $this->insert_default_category('茶叶', '', 2, $parentid);
    $parentid = $this->insert_default_category('母婴周边', 'Ft7G1Cc1PPu9f62hfc4cTS4Zc5f24F.jpg', 21);
    $this->insert_default_category('奶粉', '', 1, $parentid);
    $this->insert_default_category('童车玩具', '', 2, $parentid);
    $this->insert_default_category('综合', '', 3, $parentid);
    $parentid = $this->insert_default_category('商务服务', 'Q61g3gbHh6G3Hh18YHBIi6BIb5OI1O.jpg', 22);
    $this->insert_default_category('广告传媒', '', 1, $parentid);
    $this->insert_default_category('印刷包装', '', 2, $parentid);
    $this->insert_default_category('网站建设', '', 3, $parentid);
    $this->insert_default_category('法律咨询', '', 4, $parentid);
    $this->insert_default_category('工商注册', '', 5, $parentid);
    $this->insert_default_category('财务会计', '', 6, $parentid);
    $this->insert_default_category('400电话', '', 7, $parentid);
    $this->insert_default_category('设计策划', '', 8, $parentid);
    $parentid = $this->insert_default_category('金融服务', 'vSn0FRyAOpR02ogp8Lolnxl2Zr0Z08.jpg', 23);
    $this->insert_default_category('代还养卡', '', 1, $parentid);
    $this->insert_default_category('快速贷款', '', 2, $parentid);
    $this->insert_default_category('典当抵押', '', 3, $parentid);
    $this->insert_default_category('保险公司', '', 4, $parentid);
    $this->insert_default_category('POS机', '', 5, $parentid);
    $this->insert_default_category('投资公司', '', 6, $parentid);
    $this->insert_default_category('股票期货', '', 7, $parentid);
    $this->insert_default_category('综合金融', '', 8, $parentid);
    $parentid = $this->insert_default_category('团体组织', 'gZ8e4a8JjZ7PrVzjt4Tf89h84Oc499.jpg', 24);
    $parentid = $this->insert_default_category('农林牧渔', 'RI8T6BC69JcPsApf66dczL5c4Z9sxP.jpg', 25);
    $this->insert_default_category('农作物', '', 1, $parentid);
    $this->insert_default_category('园林花卉', '', 2, $parentid);
    $this->insert_default_category('畜禽养殖', '', 3, $parentid);
    $parentid = $this->insert_default_category('文娱户外', 'Vok3QfQ3s7Snr7OaEEz73ba3s7keN7.jpg', 26);
    $this->insert_default_category('艺术古玩', '', 1, $parentid);
    $this->insert_default_category('麻将机', '', 2, $parentid);
    $this->insert_default_category('眼镜店', '', 3, $parentid);
    $this->insert_default_category('自行车行', '', 4, $parentid);
    $this->insert_default_category('体育用品', '', 5, $parentid);
    $this->insert_default_category('渔具钓具', '', 6, $parentid);
    $parentid = $this->insert_default_category('珠宝首饰', 'hAO84jCgPZsqPqF3418P8Zgpgj34Ic.jpg', 27);
    $this->insert_default_category('黄金珠宝', '', 1, $parentid);
    $parentid = $this->insert_default_category('鲜花礼品', 'yA613zSSq53DT65SQw63aatsqGqgAG.jpg', 28);
    $this->insert_default_category('鲜花礼品', '', 1, $parentid);
    $parentid = $this->insert_default_category('商行超市', 'nVLS1RVS1zS5q12Lm1xLmV9JLd2QDc.jpg', 29);
    $this->insert_default_category('超市', '', 1, $parentid);
    $this->insert_default_category('进口零食', '', 2, $parentid);
    $this->insert_default_category('批发部', '', 3, $parentid);
    $this->insert_default_category('商行', '', 4, $parentid);
    $parentid = $this->insert_default_category('直销', 'PjWbjl9Uwi9zw3GgXcCxlX93GcjIuI.jpg', 30);
    $this->insert_default_category('直销', '', 1, $parentid);
    $parentid = $this->insert_default_category('其它', 'wq77ppQ8b3R7tS0Rsrk8cF0b7Q101l.jpg', 31);
    $this->insert_default_category('税务', '', 1, $parentid);
    message("初始化分类成功！", $this->createWebUrl('kind', array(
        'op' => 'display'
    )), 'success');
}