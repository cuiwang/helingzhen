<?php

/**
 * 合体红包
 *
 * @author ewei QQ：22185157
 * @url 
 */
defined('IN_IA') or exit('Access Denied');
require_once "jssdk.php";
function cmp($a, $b) {
	    if ($a["point"] == $b["point"]) {
 	        return 0;
	    }
	    return ($a["point"] < $b["point"]) ? 1 : -1;
	}
class Ewei_bonusModuleSite extends WeModuleSite {

    function write_cache($filename, $data) {
        global $_W;
        $path = "/addons/ewei_bonus";
        $filename = IA_ROOT . $path . "/data/" . $filename . ".txt";
        load()->func('file');
        mkdirs(dirname($filename));
        file_put_contents($filename, base64_encode(json_encode($data)));
        @chmod($filename, $_W['config']['setting']['filemode']);
        return is_file($filename);
    }

    /**
     * 获取回复
     * @return boolean
     */
    public function get_reply($rid) {
        $path = "/addons/ewei_bonus";
        $filename = IA_ROOT . $path . "/data/" . $rid . ".txt";
        if (is_file($filename)) {
            $content = file_get_contents($filename);
            if (empty($content)) {
                return false;
            }
            return json_decode(base64_decode($content), true);
        }
        return pdo_fetch("SELECT * FROM " . tablename('ewei_bonus_reply') . " WHERE rid = :rid limit 1", array(':rid' => $rid));
    }

    /**
     * 获取设置
     * @return boolean
     */
    public function get_sysset() {

        global $_W;
        $path = "/addons/ewei_bonus";
        $filename = IA_ROOT . $path . "/data/sysset_" . $_W['uniacid'] . ".txt";
        if (is_file($filename)) {
            $content = file_get_contents($filename);
            if (empty($content)) {
                return false;
            }
            return json_decode(base64_decode($content), true);
        }
        return pdo_fetch("SELECT * FROM " . tablename('ewei_bonus_sysset') . " WHERE uniacid = :uniacid limit 1", array(':uniacid' => $_W['uniacid']));
    }

    public function doWebManage() {

        global $_GPC, $_W;

        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $sql = "uniacid = :uniacid AND `module` = :module";
        $params = array();
        $params[':uniacid'] = $_W['uniacid'];
        $params[':module'] = 'ewei_bonus';
        load()->model('reply');
        load()->func('tpl');
        if (isset($_GPC['keywords'])) {
            $sql .= ' AND `name` LIKE :keywords';
            $params[':keywords'] = "%{$_GPC['keywords']}%";
        }
        $list = reply_search($sql, $params, $pindex, $psize, $total);
        $pager = pagination($total, $pindex, $psize);

        if (!empty($list)) {
            foreach ($list as &$item) {
                $condition = "`rid`={$item['id']}";
                $item['keywords'] = reply_keywords_search($condition);
                $bonus = pdo_fetch("SELECT viewnum,starttime,endtime,isshow FROM " . tablename('ewei_bonus_reply') . " WHERE rid = :rid ", array(':rid' => $item['id']));
                $item['fansnum'] = pdo_fetchcolumn("select count(*) from " . tablename('ewei_bonus_fans') . " where rid=:rid ", array(":rid" => $item['id']));
                $item['viewnum'] = $bonus['viewnum'];
                $item['starttime'] = date('Y-m-d H:i', $bonus['starttime']);
                $endtime = $bonus['endtime'] + 86399;
                $item['endtime'] = date('Y-m-d H:i', $endtime);
                $nowtime = time();
                if ($bonus['starttime'] > $nowtime) {
                    $item['status'] = "<span class=\"label label-warning\">未开始</span>";
                    $item['show'] = 1;
                } elseif ($endtime < $nowtime) {
                    $item['status'] = "<span class=\"label label-default\">已结束</span>";
                    $item['show'] = 0;
                } else {
                    if ($bonus['isshow'] == 1) {
                        $item['status'] = "<span class=\"label label-success\">已开始</span>";
                        $item['show'] = 2;
                    } else {
                        $item['status'] = "<span class=\"label \">已暂停</span>";
                        $item['show'] = 1;
                    }
                }
                $item['isshow'] = $bonus['isshow'];
            }
            unset($item);
        }

        include $this->template('manage');
    }

    public function doWebdelete() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $rule = pdo_fetch("SELECT id, module FROM " . tablename('rule') . " WHERE id = :id and uniacid=:uniacid", array(':id' => $rid, ':uniacid' => $_W['uniacid']));
        if (empty($rule)) {
            message('抱歉，要修改的规则不存在或是已经被删除！');
        }
        if (pdo_delete('rule', array('id' => $rid))) {
            pdo_delete('rule_keyword', array('rid' => $rid));
            //删除统计相关数据
            pdo_delete('stat_rule', array('rid' => $rid));
            pdo_delete('stat_keyword', array('rid' => $rid));
            //调用模块中的删除
            $module = WeUtility::createModule($rule['module']);
            if (method_exists($module, 'ruleDeleted')) {
                $module->ruleDeleted($rid);
            }
        }
        message('规则操作成功！', $this->createWebUrl('manage'), 'success');
    }

    public function doWebdeleteAll() {
        global $_GPC, $_W;

        foreach ($_GPC['idArr'] as $k => $rid) {
            $rid = intval($rid);
            if ($rid == 0)
                continue;
            $rule = pdo_fetch("SELECT id, module FROM " . tablename('rule') . " WHERE id = :id and uniacid=:uniacid", array(':id' => $rid, ':uniacid' => $_W['uniacid']));
            if (empty($rule)) {
                $this->webmessage('抱歉，要修改的规则不存在或是已经被删除！');
            }
            if (pdo_delete('rule', array('id' => $rid))) {
                pdo_delete('rule_keyword', array('rid' => $rid));
                //删除统计相关数据
                pdo_delete('stat_rule', array('rid' => $rid));
                pdo_delete('stat_keyword', array('rid' => $rid));
                //调用模块中的删除
                $module = WeUtility::createModule($rule['module']);
                if (method_exists($module, 'ruleDeleted')) {
                    $module->ruleDeleted($rid);
                }
            }
        }
        $this->webmessage('规则操作成功！', '', 0);
    }

    public function doWebfanslist() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        if (empty($rid)) {
            message('抱歉，传递的参数错误！', '', 'error');
        }
        $where = '';
        $params = array(':rid' => $rid);
        if ($_GPC['status'] != '') {
            $where.=' and status=:status';
            $params[':status'] = intval($_GPC['status']);
        }
        if (!empty($_GPC['keywords'])) {
            $where.=' and mobile<>\'\' and mobile like :mobile';
            $params[':mobile'] = "%{$_GPC['keywords']}%";
        } else {
            $where.=" and mobile<>:mobile";
            $params[':mobile'] = "";
        }


        $total = pdo_fetchcolumn("SELECT count(*) FROM " . tablename('ewei_bonus_fans') . " WHERE rid = :rid " . $where . "", $params);
        $pindex = max(1, intval($_GPC['page']));
        $psize = 12;
        $pager = pagination($total, $pindex, $psize);
        $start = ($pindex - 1) * $psize;
        $limit .= " LIMIT {$start},{$psize}";
        $list = pdo_fetchall("SELECT * FROM " . tablename('ewei_bonus_fans') . " WHERE rid = :rid " . $where . " ORDER BY createtime DESC " . $limit, $params);
        include $this->template('fanslist');
    }

    public function doWebRecordlist() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        if (empty($rid)) {
            message('抱歉，传递的参数错误！', '', 'error');
        }
        if(checksubmit('simulate')){
            $str="abcdefghijklmnopqrestuvwxyz的是在和有大这主中人上为们地个用工时要动国产以我到他会作来分生对于学下级就年阶义发成部民可出能方进同行面说种过命度革而多子后自社加小机也经力线本电高量长党得实家定深法表着水理化争现所二起政三好十战无农使性前等反体合斗路图把结第里正新开论之物从当两些还天资事队批如应形想制心样干都向变关点育重其思与间内去因件日利相由压员气业代全组数果期导平各基或月毛然问比展那它最及外没看治提五解系林者米群头意只明四道马认次文通但条较克又公孔领军流入接席位情运器并飞原油放立题质指建区验活众很教决特此常石强极土少已根共直团统式转别造切九你取西持总料连任志观调七么山程百报更见必真保热委手改管处己将修支识病象几先老光专什六型具示复安带每东增则完风回南广劳轮科北打积车计给节做务被整联步类集号列温装即毫知轴研单色坚据速防史拉世设达尔场织历花受求传口断况采精金界品判参层止边清至万确究书术状厂须离再目海交权且儿青才证低越际八试规斯近注办布门铁需走议县兵固除般引齿千胜细影济白格效置推空配刀叶率述今选养德话查差半敌始片施响收华觉备名红续均药标记难存测士身紧液派准斤角降维板许破述技消底床田势端感往神便贺村构照容非搞亚磨族火段算适讲按值美态黄易彪服早班麦削信排台声该击素张密害侯草何树肥继右属市严径螺检左页抗苏显苦英快称坏移约巴材省黑武培著河帝仅针怎植京助升王眼她抓含苗副杂普谈围食射源例致酸旧却充足短划剂宣环落首尺波承粉践府鱼随考刻靠够满夫失包住促枝局菌杆周护岩师举曲春元超负砂封换太模贫减阳扬江析亩木言球朝医校古呢稻宋听唯输滑站另卫字鼓刚写刘微略范供阿块某功套友限项余倒卷创律雨让骨远帮初皮播优占死毒圈伟季训控激找叫云互跟裂粮粒母练塞钢顶策双留误础吸阻故寸盾晚丝女散焊功株亲院冷彻弹错散商视艺灭版烈零室轻血倍缺厘泵察绝富城冲喷壤简否柱李望盘磁雄似困巩益洲脱投送奴侧润盖挥距触星松送获兴独官混纪依未突架宽冬章湿偏纹吃执阀矿寨责熟稳夺硬价努翻奇甲预职评读背协损棉侵灰虽矛厚罗泥辟告卵箱掌氧恩爱停曾溶营终纲孟钱待尽俄缩沙退陈讨奋械载胞幼哪剥迫旋征槽倒握担仍呀鲜吧卡粗介钻逐弱脚怕盐末阴丰编印蜂急拿扩伤飞露核缘游振操央伍域甚迅辉异序免纸夜乡久隶缸夹念兰映沟乙吗儒杀汽磷艰晶插埃燃欢铁补咱芽永瓦倾阵碳演威附牙芽永瓦斜灌欧献顺猪洋腐请透司危括脉宜笑若尾束壮暴企菜穗楚汉愈绿拖牛份染既秋遍锻玉夏疗尖殖井费州访吹荣铜沿替滚客召旱悟刺脑措贯藏敢令隙炉壳硫煤迎铸粘探临薄旬善福纵择礼愿伏残雷延烟句纯渐耕跑泽慢栽鲁赤繁境潮横掉锥希池败船假亮谓托伙哲怀割摆贡呈劲财仪沉炼麻罪祖息车穿货销齐鼠抽画饲龙库守筑房歌寒喜哥洗蚀废纳腹乎录镜妇恶脂庄擦险赞钟摇典柄辩竹谷卖乱虚桥奥伯赶垂途额壁网截野遗静谋弄挂课镇妄盛耐援扎虑键归符庆聚绕摩忙舞遇索顾胶羊湖钉仁音迹碎伸灯避泛亡答勇频皇柳哈揭甘诺概宪浓岛袭谁洪谢炮浇斑讯懂灵蛋闭孩释乳巨徒私银伊景坦累匀霉杜乐勒隔弯绩招绍胡呼痛峰零柴簧午跳居尚丁秦稍追梁折耗碱殊岗挖氏刃剧堆赫荷胸衡勤膜篇登驻案刊秧缓凸役剪川雪链渔啦脸户洛孢勃盟买杨宗焦赛旗滤硅炭股坐蒸凝竟陷枪黎救冒暗洞犯筒您宋弧爆谬涂味津臂障褐陆啊健尊豆拔莫抵桑坡缝警挑污冰柬嘴啥饭塑寄赵喊垫康遵牧遭幅园腔订香肉弟屋敏恢忘衣孙龄岭骗休借丹渡耳刨虎笔稀昆浪萨茶滴浅拥穴覆伦娘吨浸袖珠雌妈紫戏塔锤震岁貌洁剖牢锋疑霸闪埔猛诉刷狠忽灾闹乔唐漏闻沈熔氯荒茎男凡抢像浆旁玻亦忠唱蒙予纷捕锁尤乘乌智淡允叛畜俘摸锈扫毕璃宝芯爷鉴秘净蒋钙肩腾枯抛轨堂拌爸循诱祝励肯酒绳穷塘燥泡袋朗喂铝软渠颗惯贸粪综墙趋彼届墨碍启逆卸航雾冠丙街莱贝辐肠付吉渗瑞惊顿挤秒悬姆烂森糖圣凹陶词迟蚕亿矩";  
            $len = mb_strlen($str,'utf-8');
            
            $reply = pdo_fetch("SELECT * FROM " . tablename('ewei_bonus_reply') . " WHERE rid = :rid limit 1", array(':rid' => $rid));
            
            $num = intval($_GPC['simulatenum']);
            for($i=1;$i<=$num;$i++){
                   
                    $points = rand($reply['points'] * 100,$reply['points'] * 2 * 100) /100;
                    $nickname = "";
                    while(true)
                    {
                        
                        $rand1=rand(0,$len-1);
                        $rand2=rand(0,$len-1);
                        $nickname = mb_substr($str, $rand1,2,'utf-8').mb_substr($str, $rand2,2,'utf-8');
                        if(!empty($nickname)){
                            break;
                        }
                    }
                   //提交申请
                    $d = array(
                        "rid"=>$rid,
                        "points"=>$points,
                        "nickname"=> $nickname,
                        "createtime"=>time(),
                        "status"=>1,
                        "sim"=>1
                    );
                    pdo_insert("ewei_bonus_fans_record",$d);
            }
            
            message('模拟数据成功!',$this->createWebUrl('recordlist',array('rid'=>$rid)));
        }
        $where = ' and r.sim=0';
        $params = array(':rid' => $rid);
        if ($_GPC['status'] != '') {
            $where.=' and r.status=:status';
            $params[':status'] = intval($_GPC['status']);
        }
        if (!empty($_GPC['keywords'])) {
            $where.=' and f.mobile<>\'\' and f.mobile like :mobile';
            $params[':mobile'] = "%{$_GPC['keywords']}%";
        }
        $total = pdo_fetchcolumn("SELECT count(*) FROM " . tablename('ewei_bonus_fans_record') . "r left join " . tablename('ewei_bonus_fans') . " f on f.openid = r.openid WHERE  r.rid = :rid " . $where . "", $params);
        $pindex = max(1, intval($_GPC['page']));
        $psize = 12;
        $pager = pagination($total, $pindex, $psize);
        $start = ($pindex - 1) * $psize;
        $limit .= " LIMIT {$start},{$psize}";
        $list = pdo_fetchall("SELECT r.*,f.account,f.bank,f.paytype,f.mobile,f.realname FROM " . tablename('ewei_bonus_fans_record') . " r left join " . tablename('ewei_bonus_fans') . " f on f.openid = r.openid WHERE r.rid = :rid " . $where . " ORDER BY r.createtime DESC " . $limit, $params);
        include $this->template('recordlist');
    }

    public function doWebdownload() {
        require_once 'download.php';
    }
    public function doWebdownloadr() {
        require_once 'downloadr.php';
    }
    public function doWebsetshow() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $isshow = intval($_GPC['isshow']);

        if (empty($rid)) {
            message('抱歉，传递的参数错误！', '', 'error');
        }
        $temp = pdo_update('ewei_bonus_reply', array('isshow' => $isshow), array('rid' => $rid));
        message('状态设置成功！', $this->createWebUrl('manage'), 'success');
    }

    public function doWebSysset() {
        global $_W, $_GPC;
        $set = $this->get_sysset();
        if (checksubmit('submit')) {
            $data = array(
                'uniacid' => $_W['uniacid'],
                'appid' => $_GPC['appid'],
                'appsecret' => $_GPC['appsecret'],
                'appid_share' => $_GPC['appid_share'],
                'appsecret_share' => $_GPC['appsecret_share'],
                'resroot' => $_GPC['resroot'],
            );
            if (!empty($set)) {
                pdo_update('ewei_bonus_sysset', $data, array('id' => $set['id']));
            } else {
                pdo_insert('ewei_bonus_sysset', $data);
            }
            $this->write_cache("sysset_" . $_W['uniacid'], $data);
            message('更新授权接口成功！', 'refresh');
        }

        include $this->template('sysset');
    }

    public function webmessage($error, $url = '', $errno = -1) {
        $data = array();
        $data['errno'] = $errno;
        if (!empty($url)) {
            $data['url'] = $url;
        }
        $data['error'] = $error;
        echo json_encode($data);
        exit;
    }
  
    public function doMobileIndex() {
        global $_GPC, $_W;
        $id = intval($_GPC['id']);
        if (empty($id)) {
            message('抱歉，参数错误！', '', 'error');
        }
        $reply = $this->get_reply($id);
        if ($reply == false) {
            message('抱歉，活动已经结束，下次再来吧！', '', 'error');
        }
        if (empty($reply['isshow'])) {
            message('抱歉，活动已暂停，请稍后进入!', '', 'error');
        }
        //是否关注
        $followed = !empty($_W['openid']);
        if ($followed) {
            $mf = pdo_fetch("select follow from " . tablename('mc_mapping_fans') . " where openid=:openid limit 1", array(":openid" => $_W['openid']));
            $followed = $mf['follow'] == 1;
        }
        $set = $this->get_sysset();
        if (empty($set)) {
            $set['uniacid'] = $_W['uniacid'];
            pdo_insert("ewei_bonus_sysset", $set);
        }

        //读取分享借用的信息
        load()->model('account');
        $_W['account'] = account_fetch($_W['uniacid']);
        $appId = $appIdShare = $_W['account']['key'];
        $appSecret = $appSecretShare = $_W['account']['secret'];
       
            if($_W['account']['level']!=4){
                 //不是认证服务号
                 if (!empty($set['appid']) && !empty($set['appsecret'])) {
                   $appId = $appIdShare = $set['appid'];
                   $appSecret = $appSecretShare = $set['appsecret'];
                 }
                 else{
                    //如果没有借用，判断是否认证服务号
                    message('请使用认证服务号进行活动，或借用其他认证服务号权限!');
                 }
            }
        if (empty($appId) || empty($appSecret)) {
              message('请到管理后台设置完整的 AppID 和AppSecret !');
        }
    
        if (!empty($set['appid_share']) && !empty($set['appsecret_share'])) {
            $appIdShare = $set['appid_share'];
            $appSecretShare = $set['appsecret_share'];
        }
        if (!empty($appId) && !empty($appSecret)) {
            $jssdk = new JSSDK($appIdShare, $appIdShare);
            $package = $jssdk->getSignPackage();
        }
   
        $cookieid = "__cookie_ewei_bonus_20150204100__{$id}_{$_W['uniacid']}";
        $cookie = json_decode(base64_decode($_COOKIE[$cookieid]), true);
        $openid = is_array($cookie)?$cookie['openid']:"";
        $nickname= is_array($cookie)?$cookie['nickname']:"";
        $headurl = is_array($cookie)?$cookie['headurl']:"";
        $area =   is_array($cookie)?$cookie['area']:"";
        $access_token="";
        $snsapi_type = "snsapi_base";
        if($followed){
            $snsapi_type = "snsapi_userinfo";
        }
        load()->func('communication');
        
        //获取openid
        
        if(empty($openid)){
                $code = $_GPC['code'];
                $access_token = "";
                if (empty($code)) {
                    $url = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . "&c=entry&m=ewei_bonus&do=index&id={$id}&fromuser=".$_GPC['fromuser'];
      
                    $authurl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $appId . "&redirect_uri=" . urlencode($url) . "&response_type=code&scope={$snsapi_type}&state=123#wechat_redirect";
                    header("location: " . $authurl);
                    exit();
                } else {
                    $tokenurl = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $appId . "&secret=" . $appSecret . "&code=" . $code . "&grant_type=authorization_code";
                    $resp = ihttp_get($tokenurl);
                    $token = @json_decode($resp['content'], true);
                    if (!empty($token) && is_array($token) && $token['errmsg'] == 'invalid code') {
                        $url = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . "&c=entry&m=ewei_bonus&do=index&id={$id}&fromuser=".$_GPC['fromuser'];
                        $authurl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $appId . "&redirect_uri=" . urlencode($url) . "&response_type=code&scope={$snsapi_type}&state=123#wechat_redirect";
                        header("location: " . $authurl);
                        exit();
                    }
                    if (empty($token) || !is_array($token) || empty($token['access_token']) || empty($token['openid'])) {
                    } else {
                        $access_token = $token['access_token'];
                        $openid = $token['openid'];
                         $f = array(
                            "uniacid" => $_W['uniacid'],
                            "openid" => $openid,
                            "rid" => $id,
                            "appid" => $appId,
                            "appsecret" => $appSecret
                        );
                        setcookie($cookieid, base64_encode(json_encode($f)), time() + 3600 * 24 * 365);
                    }
                }
            }
        
        //获取用户资料
        if($followed) {
            if(empty($nickname)){
               $f = array(
                    "uniacid" => $_W['uniacid'],
                    "openid" => $openid,
                    "rid" => $id,
                    "appid" => $appId,
                    "appsecret" => $appSecret
                );
                //如果未获取过用户信息，则获取粉丝信息
                $infourl = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $access_token . "&openid=" . $openid . "&lang=zh_CN";
                $resp = ihttp_get($infourl);
                $fans_info = @json_decode($resp['content'], true);
                
                if (isset($fans_info['nickname'])) {
                    $nickname = $f['nickname'] = $fans_info['nickname'];
                    $headurl = $f['headurl'] = $fans_info['headimgurl'];
                    $area =  $f['area'] = $fans_info['province']." ".$fans_info['city'];
                    setcookie($cookieid, base64_encode(json_encode($f)), time() + 3600 * 24 * 365);
                }
            }
        }
 
        $newfans = false;
        $fans = pdo_fetch("select * from " . tablename('ewei_bonus_fans') . " where rid=:rid and openid=:openid limit 1", array(":rid" => $id, ":openid" => $openid));
        if (!empty($fans)) {
            $points = number_format($fans['points_current'],2);
            if(!empty($nickname)){
                pdo_update("ewei_bonus_fans",array("nickname"=>$nickname,"headurl"=>$headurl,"area"=>$area),array("openid"=>$openid));    
            }
            
        } else {
            $fans = array(
                "rid"=>$id,
                "openid"=>$openid,
                "nickname"=>$nickname,
                "headurl"=>$headurl,
                "area"=>$area,
                "createtime"=>time()
            );
            pdo_insert("ewei_bonus_fans",$fans);
            $fans['id'] = pdo_insertid();
            $points = $this->get_points($reply, $fans, true);
            pdo_update("ewei_bonus_fans",array("points_start"=>$points,"points_total"=>$points,"points_current"=>$points),array("id"=>$fans['id']));
            $newfans = true;
        }
     
        //合体
        $from =$_GPC['fromuser'];
       
        if(!empty($from) && $from!=$openid){
            
            $fromfans = pdo_fetch("select * from  ".tablename('ewei_bonus_fans')." where rid=:rid and openid=:openid limit 1",array(":rid"=>$id,":openid"=>$from));
            if(!empty($fromfans)){
                $help = pdo_fetch("select * from  ".tablename('ewei_bonus_fans_help')." where rid=:rid and fansopenid=:fansopenid and openid=:openid limit 1",array(":rid"=>$id,":fansopenid"=>$from,":openid"=>$openid));
                if(empty($help)){
                    //如果没有合体过
                    $helppoints = $this->get_points($reply, $fromfans, false);
                    $help = array(
                         "rid"=>$id,
                         "fansopenid"=>$from ,
                         "openid"=>$openid,
                         "nickname"=>$nickname,
                         "headurl" =>$headurl,
                         "points"=>$helppoints,
                         "createtime"=>time()
                    );
                    pdo_insert("ewei_bonus_fans_help",$help);
                    //增加帮助别人次数
                    pdo_update("ewei_bonus_fans",array("helpothers"=>$fans['helpothers'] +1),array("rid"=>$id, "openid"=>$openid));
                    //增加from被帮助人数据
                    pdo_update("ewei_bonus_fans",
                            array(
                                "helps"=>$fromfans['helps'] +1,
                                "points_current"=>$fromfans['points_current'] + $helppoints ,
                                "points_total"=>$fromfans['points_total'] + $helppoints ,
                                "points_help"=>$fromfans['points_help'] + $helppoints ,
                            ),array("rid"=>$id, "openid"=>$from));
                    
                }
            }
        }
        
        //已经提现
        $users = pdo_fetchall("select nickname,points from " . tablename('ewei_bonus_fans_record') . " where rid=:rid and status=1 order by createtime desc limit 20 ", array(":rid" => $id));
        foreach ($users as &$u) {
            $u['nickname'] = $this->cut_str($u['nickname'], 1, 0) . "**";
        }
        unset($u);
        //参与人数
        $joincount = pdo_fetchcolumn("select count(*) from ".tablename('ewei_bonus_fans')." where rid=:rid ",array(":rid"=>$id));
        $joincount+=$reply['joincount'];
        $joincount = number_format($joincount,0);
       
        //浏览次数
        pdo_query("update " . tablename('ewei_bonus_reply') . " set viewnum=viewnum+1 where rid=:rid", array(":rid" => $id));

        //分享信息
        $sharelink = $_W['siteroot'] . 'app/' . $this->createMobileUrl('index', array('id' => $id, 'fromuser' => $openid));
 
        $resroot = $this->get_resroot();
        include $this->template('index2');
    }
function cut_str($string, $sublen, $start = 0, $code = 'UTF-8')
{
    if($code == 'UTF-8')
    {
        $pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
        preg_match_all($pa, $string, $t_string);
        if(count($t_string[0]) - $start > $sublen) return join('', array_slice($t_string[0], $start, $sublen));
        return join('', array_slice($t_string[0], $start, $sublen));
    }
    else
    {
        $start = $start*2;
        $sublen = $sublen*2;
        $strlen = strlen($string);
        $tmpstr = '';
        for($i=0; $i< $strlen; $i++)
        {
            if($i>=$start && $i< ($start+$sublen))
            {
                if(ord(substr($string, $i, 1))>129)
                {
                    $tmpstr.= substr($string, $i, 2);
                }
                else
                {
                    $tmpstr.= substr($string, $i, 1);
                }
            }
            if(ord(substr($string, $i, 1))>129) $i++;
        }
        //if(strlen($tmpstr)< $strlen ) $tmpstr.= "...";
        return $tmpstr;
    }
}
    private function get_points($reply, $fans, $first = false) {
        $points = 0;
        $fans_points = $fans['points'];

        //默认的
        $start = $reply['start'];
        $end = $reply['end'];
       $points = rand(intval($start * 100), intval($end * 100)) / 100;
        if (!$first) {
            $rules = unserialize($reply['rules']);
            usort($rules, "cmp");
            foreach ($rules as $rule) {
                if ($fans_points >= $rule['point']) {
                    $start = $rule['start'];
                    $end = $rule['end'];
                    $points = rand(intval($start * 100), intval($end * 100)) / 100;
                    break;
                }
            }
        }
        return $points;
    }

    public function doMobileShareList() {
 

        global $_W, $_GPC;
        $id = intval($_GPC['id']);
        $openid = $_GPC['openid'];
        if(empty($id) || empty($openid)){
            die(json_encode(array("success"=>false)));
        }
        $reply = $this->get_reply($id);
        if ($reply == false) {
             die(json_encode(array("success"=>false)));
        }
        if (empty($reply['isshow'])) {
             die(json_encode(array("success"=>false)));
        }
        $fans = pdo_fetch("select * from " . tablename('ewei_bonus_fans') . " where rid=:rid and openid=:openid limit 1", array(":rid" => $id, ":openid" => $openid));
         
        $list = pdo_fetchall("select * from ".tablename('ewei_bonus_fans_help')." where rid=:rid and fansopenid=:openid order by createtime desc limit 20",array(":rid"=>$id,"openid"=>$openid));
        
        //如果获取了资料
        if(!empty($fans['nickname'])){
	        $list[] = array(
	            "nickname"=>$fans['nickname'],
	            "headurl"=>$fans['headurl'],
	            "points"=>$fans['points_start'],
	            "createtime"=>$fans['createtime']
	        );
        }
        foreach($list as &$l){
            $l['createtime'] = date('Y-m-d H:i',$l['createtime']);
        }
        unset($l);
        
        die(json_encode(array('success' => true,'list'=>$list)));
    }
 
    public function doMobileJubao(){
        include $this->template('jubao');
    }
    public function doMobileTixian(){
        global $_W,$_GPC;
        $id = intval($_GPC['id']);
     
        $openid = $_GPC['openid'];
        $fans = pdo_fetch("select * from " . tablename('ewei_bonus_fans') . " where rid=:rid and openid=:openid limit 1", array(":rid" => $id, ":openid" => $openid));
        $reply = pdo_fetch("select * from " . tablename('ewei_bonus_reply') . " where rid=:rid limit 1", array(":rid" => $id));
        if($_W['ispost'] && $_W['isajax']){
            //判断是否已经有申请
            $a = pdo_fetch("select * from " . tablename('ewei_bonus_fans_record') . " where rid=:rid and openid=:openid and status=0 limit 1", array(":rid" => $id, ":openid" => $openid));
            if(!empty($a)){
                die(json_encode(array("success"=>false,"message"=>"您已经有提现申请，请等待处理!")));
            }
            //提交申请
            $d = array(
                "rid"=>$id,
                "openid"=>$openid,
                "points"=>$_GPC['points'],
                "nickname"=>$fans['nickname'],
                "createtime"=>time()
            );
            pdo_insert("ewei_bonus_fans_record",$d);
            $ac = array(
                "paytype"=>$_GPC['paytype'],                 
                "realname"=>$_GPC['realname'],
                "mobile"=>$_GPC['mobile'],
                "bank"=>$_GPC['bank'],
                "account"=>$_GPC['account']
            );
            pdo_update("ewei_bonus_fans",$ac,array("id"=>$fans['id']));
                    
            die(json_encode(array("success"=>true)));
        }
        $resroot = $this->get_resroot();
        include $this->template('tixian');
    }
    
    public function doMobileMyList(){
        global $_W,$_GPC;
        $id = intval($_GPC['id']);
        $openid = $_GPC['openid'];
        $reply = pdo_fetch("SELECT * FROM " . tablename('ewei_bonus_reply') . " WHERE rid = :rid limit 1", array(':rid' => $id));
        $fans = pdo_fetch("select * from " . tablename('ewei_bonus_fans') . " where rid=:rid and openid=:openid limit 1", array(":rid" => $id, ":openid" => $openid));
        if($_GPC['op']=='list'){
         
            $where = '';
            $params = array(':rid' => $id,':openid'=>$openid);
            $total = pdo_fetchcolumn("SELECT count(*) FROM " . tablename('ewei_bonus_fans_record') . " WHERE  rid = :rid and openid=:openid " . $where . "", $params);
            $pindex = max(1, intval($_GPC['pageIndex']));
            $psize = intval($_GPC['pageSize']);
            $start = ($pindex - 1) * $psize;
            $limit .= " LIMIT {$start},{$psize}";
            $list = pdo_fetchall("SELECT points,createtime,status FROM " . tablename('ewei_bonus_fans_record') . " WHERE rid = :rid and openid=:openid " . $where . " ORDER BY createtime DESC " . $limit, $params);
            foreach($list as &$row){
                $row['name'] = "红包";
                $row['createtimestr'] = date('m-d',$row['createtime']);
                $row['endtimestr'] =  date('m-d',$reply['endtime']);
            }
            unset($row);
         
            die(json_encode(array(
                "success"=>true,
                "list"=>$list
            )));
        }
        $resroot = $this->get_resroot();
         include $this->template('mylist');
    }
    private function get_resroot(){
        global $_W;
        $set = $this->get_sysset();
        $resroot = $set['resroot'];
        if(empty($resroot)){
            $resroot = "../addons/ewei_bonus/style/";
        }
        else{
            if(substr($resroot, strlen($resroot)-1)!=='/'){
              $resroot.="/";
            }
        }
        
        return $resroot;
    }
    public function doWebgetaward() {
        global $_W, $_GPC;


        $id = $_GPC['id'];
        $rid = $_GPC['rid'];
        $reply = pdo_fetch("SELECT * FROM " . tablename('ewei_bonus_reply') . " WHERE rid = :rid limit 1", array(':rid' => $rid));
         if (empty($reply)) {
            message('未找到活动!', '', 'error');
        }
        $record = pdo_fetch("SELECT * FROM " . tablename('ewei_bonus_fans_record') . " WHERE id = :id and rid=:rid limit 1", array(':id' => $id,':rid' => $rid));
        if (empty($record)) {
            message('未找到提现申请!', '', 'error');
        }
        if(!empty($record['status'])){
            message('已经提现过了，无法重复提现!', '', 'error');
        }
        
        $fans = pdo_fetch("select * from " . tablename('ewei_bonus_fans') . " where rid=:rid and openid=:openid limit 1", array(":rid" => $rid, ":openid" => $record['openid']));
        if (empty($fans)) {
            message('未找到用户!', '', 'error');
        }
        $points = $record['points'];
        if($points>$fans['points_current']){
            message("用户红包金额 ({$fans['points_current']}) 小于要提现的金额 ({$record['points']}) !", '', 'error');
        }
        
        $fans['points_current']-=$points;
        $fans['points_withdraw']+=$points;
        $fans['status'] = 1;
        pdo_update("ewei_bonus_fans", $fans, array("id" => $fans['id']));
        
 
        pdo_update("ewei_bonus_fans_record", array("status" => 1,"consumetime"=>time()), array("rid" => $rid, "id" => $id));
        message('提现成功!', referer(), "success");
    }

}
