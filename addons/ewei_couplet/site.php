<?php

/**
 * 对联猜猜
 *
 * @author ewei QQ：22185157
 * @url 
 */
defined('IN_IA') or exit('Access Denied');
require_once "jssdk.php";
 
class Ewei_coupletModuleSite extends WeModuleSite {

    function write_cache($filename, $data) {
        global $_W;
        $path = "/addons/ewei_couplet";
        $filename = IA_ROOT . $path . "/data/" . $filename . ".txt";
        load()->func('file');
        mkdirs(dirname($filename));
        file_put_contents($filename, base64_encode(json_encode($data)));
        @chmod($filename, $_W['config']['setting']['filemode']);
        return is_file($filename);
    }

      public function doWebTpl() {
        global $_GPC, $_W;
        load()->func('tpl');
        $rule['id'] = random(32);
        include $this->template($_GPC['t']);
    }
        public function doWebManage() {

        global $_GPC, $_W;

        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $sql = "uniacid = :uniacid AND `module` = :module";
        $params = array();
        $params[':uniacid'] = $_W['uniacid'];
        $params[':module'] = 'ewei_couplet';
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
                $couplet = pdo_fetch("SELECT viewnum,starttime,endtime,isshow FROM " . tablename('ewei_couplet_reply') . " WHERE rid = :rid ", array(':rid' => $item['id']));
                $item['fansnum'] = pdo_fetchcolumn("select count(*) from " . tablename('ewei_couplet_fans') . " where rid=:rid and sim=0 ", array(":rid" => $item['id']));
                $item['awardnum'] = pdo_fetchcolumn("select count(*) from " . tablename('ewei_couplet_fans') . " where rid=:rid and sim=0 and status>=1 ", array(":rid" => $item['id']));
                $item['viewnum'] = $couplet['viewnum'];
                $item['starttime'] = date('Y-m-d H:i', $couplet['starttime']);
                $endtime = $couplet['endtime'] + 86399;
                $item['endtime'] = date('Y-m-d H:i', $endtime);
                $nowtime = time();
                if ($couplet['starttime'] > $nowtime) {
                    $item['status'] = "<span class=\"label label-warning\">未开始</span>";
                    $item['show'] = 1;
                } elseif ($endtime < $nowtime) {
                    $item['status'] = "<span class=\"label label-default\">已结束</span>";
                    $item['show'] = 0;
                } else {
                    if ($couplet['isshow'] == 1) {
                        $item['status'] = "<span class=\"label label-success\">已开始</span>";
                        $item['show'] = 2;
                    } else {
                        $item['status'] = "<span class=\"label \">已暂停</span>";
                        $item['show'] = 1;
                    }
                }
                $item['isshow'] = $couplet['isshow'];
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
         if(checksubmit('simulate')){
            $str="的是在和有大这主中人上为们地个用工时要动国产以我到他会作来分生对于学下级就年阶义发成部民可出能方进同行面说种过命度革而多子后自社加小机也经力线本电高量长党得实家定深法表着水理化争现所二起政三好十战无农使性前等反体合斗路图把结第里正新开论之物从当两些还天资事队批如应形想制心样干都向变关点育重其思与间内去因件日利相由压员气业代全组数果期导平各基或月毛然问比展那它最及外没看治提五解系林者米群头意只明四道马认次文通但条较克又公孔领军流入接席位情运器并飞原油放立题质指建区验活众很教决特此常石强极土少已根共直团统式转别造切九你取西持总料连任志观调七么山程百报更见必真保热委手改管处己将修支识病象几先老光专什六型具示复安带每东增则完风回南广劳轮科北打积车计给节做务被整联步类集号列温装即毫知轴研单色坚据速防史拉世设达尔场织历花受求传口断况采精金界品判参层止边清至万确究书术状厂须离再目海交权且儿青才证低越际八试规斯近注办布门铁需走议县兵固除般引齿千胜细影济白格效置推空配刀叶率述今选养德话查差半敌始片施响收华觉备名红续均药标记难存测士身紧液派准斤角降维板许破述技消底床田势端感往神便贺村构照容非搞亚磨族火段算适讲按值美态黄易彪服早班麦削信排台声该击素张密害侯草何树肥继右属市严径螺检左页抗苏显苦英快称坏移约巴材省黑武培著河帝仅针怎植京助升王眼她抓含苗副杂普谈围食射源例致酸旧却充足短划剂宣环落首尺波承粉践府鱼随考刻靠够满夫失包住促枝局菌杆周护岩师举曲春元超负砂封换太模贫减阳扬江析亩木言球朝医校古呢稻宋听唯输滑站另卫字鼓刚写刘微略范供阿块某功套友限项余倒卷创律雨让骨远帮初皮播优占死毒圈伟季训控激找叫云互跟裂粮粒母练塞钢顶策双留误础吸阻故寸盾晚丝女散焊功株亲院冷彻弹错散商视艺灭版烈零室轻血倍缺厘泵察绝富城冲喷壤简否柱李望盘磁雄似困巩益洲脱投送奴侧润盖挥距触星松送获兴独官混纪依未突架宽冬章湿偏纹吃执阀矿寨责熟稳夺硬价努翻奇甲预职评读背协损棉侵灰虽矛厚罗泥辟告卵箱掌氧恩爱停曾溶营终纲孟钱待尽俄缩沙退陈讨奋械载胞幼哪剥迫旋征槽倒握担仍呀鲜吧卡粗介钻逐弱脚怕盐末阴丰编印蜂急拿扩伤飞露核缘游振操央伍域甚迅辉异序免纸夜乡久隶缸夹念兰映沟乙吗儒杀汽磷艰晶插埃燃欢铁补咱芽永瓦倾阵碳演威附牙芽永瓦斜灌欧献顺猪洋腐请透司危括脉宜笑若尾束壮暴企菜穗楚汉愈绿拖牛份染既秋遍锻玉夏疗尖殖井费州访吹荣铜沿替滚客召旱悟刺脑措贯藏敢令隙炉壳硫煤迎铸粘探临薄旬善福纵择礼愿伏残雷延烟句纯渐耕跑泽慢栽鲁赤繁境潮横掉锥希池败船假亮谓托伙哲怀割摆贡呈劲财仪沉炼麻罪祖息车穿货销齐鼠抽画饲龙库守筑房歌寒喜哥洗蚀废纳腹乎录镜妇恶脂庄擦险赞钟摇典柄辩竹谷卖乱虚桥奥伯赶垂途额壁网截野遗静谋弄挂课镇妄盛耐援扎虑键归符庆聚绕摩忙舞遇索顾胶羊湖钉仁音迹碎伸灯避泛亡答勇频皇柳哈揭甘诺概宪浓岛袭谁洪谢炮浇斑讯懂灵蛋闭孩释乳巨徒私银伊景坦累匀霉杜乐勒隔弯绩招绍胡呼痛峰零柴簧午跳居尚丁秦稍追梁折耗碱殊岗挖氏刃剧堆赫荷胸衡勤膜篇登驻案刊秧缓凸役剪川雪链渔啦脸户洛孢勃盟买杨宗焦赛旗滤硅炭股坐蒸凝竟陷枪黎救冒暗洞犯筒您宋弧爆谬涂味津臂障褐陆啊健尊豆拔莫抵桑坡缝警挑污冰柬嘴啥饭塑寄赵喊垫康遵牧遭幅园腔订香肉弟屋敏恢忘衣孙龄岭骗休借丹渡耳刨虎笔稀昆浪萨茶滴浅拥穴覆伦娘吨浸袖珠雌妈紫戏塔锤震岁貌洁剖牢锋疑霸闪埔猛诉刷狠忽灾闹乔唐漏闻沈熔氯荒茎男凡抢像浆旁玻亦忠唱蒙予纷捕锁尤乘乌智淡允叛畜俘摸锈扫毕璃宝芯爷鉴秘净蒋钙肩腾枯抛轨堂拌爸循诱祝励肯酒绳穷塘燥泡袋朗喂铝软渠颗惯贸粪综墙趋彼届墨碍启逆卸航雾冠丙街莱贝辐肠付吉渗瑞惊顿挤秒悬姆烂森糖圣凹陶词迟蚕亿矩";  
            $len = mb_strlen($str,'utf-8');
            $reply = pdo_fetch("SELECT * FROM " . tablename('ewei_couplet_reply') . " WHERE rid = :rid limit 1", array(':rid' => $rid));
            $num = intval($_GPC['simulatenum']);
            for($i=1;$i<=$num;$i++){
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
                        "nickname"=> $nickname,
                        "createtime"=>time(),
                        "status"=>1,
                        "sim"=>1,
                        "log"=>1
                    );
                    pdo_insert("ewei_couplet_fans",$d);
            }
            
            message('模拟数据成功!',$this->createWebUrl('fanslist',array('rid'=>$rid)));
        }
        
        $where = '';
        $params = array(':rid' => $rid);
        if ($_GPC['status'] != '') {
            $where.=' and status=:status';
            $params[':status'] = intval($_GPC['status']);
        }
        if (!empty($_GPC['keywords'])) {
            $where.=' and mobile like :mobile';
            $params[':mobile'] = "%{$_GPC['keywords']}%";
        }  


        $total = pdo_fetchcolumn("SELECT count(*) FROM " . tablename('ewei_couplet_fans') . " WHERE rid = :rid and sim=0 " . $where . "", $params);
      
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $pager = pagination($total, $pindex, $psize);
        $start = ($pindex - 1) * $psize;
        $limit .= " LIMIT {$start},{$psize}";
        $list = pdo_fetchall("SELECT * FROM " . tablename('ewei_couplet_fans') . " WHERE rid = :rid and sim=0  " . $where . " ORDER BY createtime DESC " . $limit, $params);
        include $this->template('fanslist');
    }
 
    public function doWebdownload() {
        require_once 'download.php';
    }
 
    public function doWebsetshow() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $isshow = intval($_GPC['isshow']);
        if (empty($rid)) {
            message('抱歉，传递的参数错误！', '', 'error');
        }
        $temp = pdo_update('ewei_couplet_reply', array('isshow' => $isshow), array('rid' => $rid));
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
                'appsecret_share' => $_GPC['appsecret_share']
            );
            if (!empty($set)) {
                pdo_update('ewei_couplet_sysset', $data, array('id' => $set['id']));
            } else {
                pdo_insert('ewei_couplet_sysset', $data);
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
  
    
    /**
     * 获取回复
     * @return boolean
     */
    public function get_reply($rid) {
        $path = "/addons/ewei_couplet";
        $filename = IA_ROOT . $path . "/data/" . $rid . ".txt";
        if (is_file($filename)) {
            $content = file_get_contents($filename);
            if (empty($content)) {
                return false;
            }
            return json_decode(base64_decode($content), true);
        }
        return pdo_fetch("SELECT * FROM " . tablename('ewei_couplet_reply') . " WHERE rid = :rid limit 1", array(':rid' => $rid));
    }

    /**
     * 获取设置
     * @return boolean
     */
    public function get_sysset() {

        global $_W;
        $path = "/addons/ewei_couplet";
        $filename = IA_ROOT . $path . "/data/sysset_" . $_W['uniacid'] . ".txt";
        if (is_file($filename)) {
            $content = file_get_contents($filename);
            if (empty($content)) {
                return false;
            }
            return json_decode(base64_decode($content), true);
        }
        return pdo_fetch("SELECT * FROM " . tablename('ewei_couplet_sysset') . " WHERE uniacid = :uniacid limit 1", array(':uniacid' => $_W['uniacid']));
    }
    private function getCookie($id){
       global $_W;
       $cookieid = "__cookie_ewei_couplet_20150210101__{$id}_{$_W['uniacid']}";
       return json_decode(base64_decode($_COOKIE[$cookieid]), true);
  }
    private function setCookie($id, $cookie){
      global $_W;
      $cookieid = "__cookie_ewei_couplet_20150210101__{$id}_{$_W['uniacid']}";
      setcookie($cookieid, base64_encode(json_encode($cookie)), time() + 3600 * 24 * 365);
  }
  private function check_weixin(){
       if (empty($_SERVER['HTTP_USER_AGENT']) || strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false && strpos($_SERVER['HTTP_USER_AGENT'], 'Windows Phone') === false) {
         die(json_encode(array('result' => 0, "message" => "您只能在微信客户端参加活动哦!")));
      }
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
            pdo_insert("ewei_couplet_sysset", $set);
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
            $jssdk = new JSSDK($appIdShare, $appSecretShare);
            $package = $jssdk->getSignPackage();
        }
   
        
        $cookie =$this->getCookie($id);
        $openid = is_array($cookie)?$cookie['openid']:"";
        $nickname= is_array($cookie)?$cookie['nickname']:"";
        $headurl = is_array($cookie)?$cookie['headurl']:"";
        $area =   is_array($cookie)?$cookie['area']:"";
        $access_token="";
     
        load()->func('communication');
        
        //获取openid
        
        if(empty($openid)){
                $code = $_GPC['code'];
                $access_token = "";
                if (empty($code)) {
                    $url = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . "&c=entry&m=ewei_couplet&do=index&id={$id}&fromopenid=".$_GPC['fromopenid'];
      
                    $authurl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $appId . "&redirect_uri=" . urlencode($url) . "&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect";
                    header("location: " . $authurl);
                    exit();
                } else {
                    $tokenurl = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $appId . "&secret=" . $appSecret . "&code=" . $code . "&grant_type=authorization_code";
                    $resp = ihttp_get($tokenurl);
                    $token = @json_decode($resp['content'], true);
                    if (!empty($token) && is_array($token) && $token['errmsg'] == 'invalid code') {
                        $url = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . "&c=entry&m=ewei_couplet&do=index&id={$id}&fromopenid=".$_GPC['fromopenid'];
                        $authurl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $appId . "&redirect_uri=" . urlencode($url) . "&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect";
                        header("location: " . $authurl);
                        exit();
                    }
                    if (empty($token) || !is_array($token) || empty($token['access_token']) || empty($token['openid'])) {
                    } else {
                        $access_token = $token['access_token'];
                        $openid = $token['openid'];
                    }
                }
            }
          //获取用户资料
        
            if(empty($nickname)){
              
                //如果未获取过用户信息，则获取粉丝信息
                $infourl = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $access_token . "&openid=" . $openid . "&lang=zh_CN";
                $resp = ihttp_get($infourl);
                $fans_info = @json_decode($resp['content'], true);
                
                if (isset($fans_info['nickname'])) {
                    $nickname = $f['nickname'] = $fans_info['nickname'];
                    $headurl = $f['headurl'] = $fans_info['headimgurl'];
                    $area =  $f['area'] = $fans_info['province']." ".$fans_info['city'];
                    $f = array(
                        "uniacid" => $_W['uniacid'],
                        "openid" => $openid,
                        "nickname"=>$nickname,
                        "headurl"=>$headurl,
                        "area"=>$area,
                        "rid" => $id,
                        "appid" => $appId,
                        "appsecret" => $appSecret
                    );
                     
                     $this->setCookie($id,$f);
                }
       }
    
        $fromopenid=$_GPC['fromopenid'];
        $hasOther = false;
        $needHelpers = false; //是否获取帮助列表
        if(!empty($fromopenid) && $fromopenid!=$openid){
            $fromfans = pdo_fetch("select * from " . tablename('ewei_couplet_fans') . " where rid=:rid and openid=:openid limit 1", array(":rid" => $id, ":openid" => $fromopenid));
            if(!empty($fromfans)){
                $hasOther = true;
                $uptext = unserialize($fromfans['uptext']);
                $downtext = unserialize($fromfans['downtext']);
                //抽中的个数
                $num = 0;
                foreach($downtext as $dt){
                    if(!empty($dt['bingo'])){
                        $num++;
                    }
                }
                $needHelpers = true;
            }
        }
        else{
            $fans = pdo_fetch("select * from " . tablename('ewei_couplet_fans') . " where rid=:rid and openid=:openid limit 1", array(":rid" => $id, ":openid" => $openid));
            if(!empty($fans)){
                $uptext = unserialize($fans['uptext']);
                $downtext = unserialize($fans['downtext']);
                  //抽中的个数
                  $num = 0;
                  foreach($downtext as $dt){
                      if(!empty($dt['bingo'])){
                          $num++;
                      }
                 }
                 $needHelpers = true;
            }
        }
     
        //参与人数
        $joincount = pdo_fetchcolumn("select count(*) from ".tablename('ewei_couplet_fans')." where rid=:rid ",array(":rid"=>$id));
        $joincount+=$reply['joincount'];
        $joincount = number_format($joincount,0);
        //公告
        $awards = pdo_fetchall("select nickname from ".tablename('ewei_couplet_fans')." where rid=:rid and status>=1 order by createtime desc limit 10",array(':rid'=>$id));
        $gonggao = "";
        foreach($awards as $a){
            $gonggao.= "恭喜 ".$this->cut_str($a['nickname'], 1,0)."*** 获得了".$reply['award_name']."! &nbsp;&nbsp;";
        }
       
    
        //浏览次数
        pdo_query("update " . tablename('ewei_couplet_reply') . " set viewnum=viewnum+1 where rid=:rid", array(":rid" => $id));
       
        if(!empty($fromfans)){
             //分享信息
            $sharetitle = "新年集对联，免费领大奖!";
            if($fans['status']>=1){
                 $sharedesc = "{$fromfans['nickname']}已经领取了集对联免费领{$reply['award_name']}，你还不赶紧来参加!";
                 $sharelink = $_W['siteroot'] . 'app/' . $this->createMobileUrl('index', array('id' => $id));
            }else{
                $sharedesc = "{$fromfans['nickname']}正在参与集对联免费领{$reply['award_name']}活动，快来帮{$fromfans['nickname']}抽取下联吧！";
                $sharelink = $_W['siteroot'] . 'app/' . $this->createMobileUrl('index', array('id' => $id, 'fromopenid' => $fromopenid));
            }
           $help = pdo_fetch("select * from " . tablename('ewei_couplet_fans_help') . " where rid=:rid and fansopenid=:fansopenid and openid=:openid limit 1", array(":rid" => $id,":fansopenid"=>$fromopenid, ":openid" => $openid));   
           $helped = !empty($help);
        
            
        }else if(!empty($fans)){
             $sharetitle = "新年集对联，免费领大奖!";
             if($fans['status']>=1){
             //分享信息
                 $sharedesc = "哈哈, 我已经领取了集对联免费领{$reply['award_name']}，你还不赶紧来参加!";
                 $sharelink = $_W['siteroot'] . 'app/' . $this->createMobileUrl('index', array('id' => $id));
             }
             else{
                 $sharedesc = "我正在参与集对联免费领{$reply['award_name']}活动，快来帮我抽取下联吧！";
                 $sharelink = $_W['siteroot'] . 'app/' . $this->createMobileUrl('index', array('id' => $id,"fromopenid"=>$openid));
             }
             
        }
        include $this->template('index');
    }
    public function doMobileGetCouplet(){
        
        global $_W,$_GPC;
    
        $this->check_weixin();
        
        $id = intval($_GPC['id']);
        $openid = $_GPC['openid'];
        $reply = $this->get_reply($id);
        if(empty($reply)){
            die(json_encode(array("result"=>0,"message"=>"未找到活动!")));
        }
        if(empty($reply['isshow'])){
            die(json_encode(array("result"=>0,"message"=>"活动暂停，请等待活动开启!")));
        }
              
        $cookie =$this->getCookie($id);
        //是否关注
        $followed = !empty($_W['openid']);
        if ($followed) {
            $mf = pdo_fetch("select follow from " . tablename('mc_mapping_fans') . " where openid=:openid limit 1", array(":openid" => $_W['openid']));
            $followed = $mf['follow'] == 1;
        }
    
        if($followed){
            if($reply['award_last']<=0){
                 die(json_encode(array("result"=>0,"message"=>"哈哈，奖品已经被疯狂的领取一空啦，请等待下次活动吧!")));
             }
             $fans = pdo_fetch("select * from " . tablename('ewei_couplet_fans') . " where rid=:rid and openid=:openid limit 1", array(":rid" => $id, ":openid" => $openid));
             if(empty($fans)){
                 //获取随机对联
                 $couplets = explode("\r\n",$reply['couplets']);
                 $rand = rand(0, count($couplets)-1);
                 $d = $couplets[$rand];
                 $ds = explode(" ",$d);
                 $uptext = array();
                 for($i=0;$i<7;$i++){
                     $uptext[] = array("char"=>  $this->cut_str($ds[0], 1,$i));
                 }
                 $downtext = array();
                 for($i=0;$i<7;$i++){
                     $downtext[] = array("char"=> $this->cut_str($ds[1], 1,$i),"bingo"=>0);
                 }
                 //随机获取规则
                 $rules = unserialize($reply['rules']);
                 $rule =$rules[ array_rand($rules, 1) ];
                 
                 $fans = array(
                     "rid"=>$id,
                     "nickname"=>$cookie['nickname'],
                     "headurl"=>$cookie['headurl'],
                     "area"=>$cookie['area'],
                     "openid"=>$openid,
                     "uptext"=>  serialize($uptext),
                     "downtext"=>  serialize($downtext),
                     "rule"=>  $rule['id'],
                     "createtime"=>time()
                 );
                 pdo_insert("ewei_couplet_fans",$fans);
                 die(json_encode(array("result"=>1,"uptext"=>$ds[0])));
             }
             else{
                 die(json_encode(array("result"=>2,"url"=>$_W['siteroot'].'app/'.$this->createMobileUrl('index',array('id'=>$id)))));
             }
        }
        else{
            die(json_encode(array("result"=>2,"url"=>$reply['followurl'])));
        }
    }
    public function  doMobileHelp(){
        global $_W,$_GPC;
        
        $this->check_weixin(); 
        $succList = array("与{nick}迸出爱的火花，抽中了<strong>{value}</strong>字","与{nick}手拿菜刀砍电线，抽中了<strong>{value}</strong>字","与{nick}花前月下，抽中了<strong>{value}</strong>字","与{nick}是好基友，抽中了<strong>{value}</strong>字","与{nick}一贱钟情，抽中了<strong>{value}</strong>字","{nick}的手撸啊撸，抽中了<strong>{value}</strong>字","{nick}用他的滑板鞋为你摩擦，抽中了<strong>{value}</strong>字","{nick}看到前任素颜倒垃圾，抽中了<strong>{value}</strong>字","{nick}看到前任素颜倒垃圾，抽中了<strong>{value}</strong>字","{nick}今天抽到了上上签，抽中了<strong>{value}</strong>字","{nick}就是你的小苹果，请深爱，抽中了<strong>{value}</strong>字");
        $failList  =  array("{nick}素颜倒垃圾被前任撞见，抽中了无效字<strong>{value}</strong>字","{nick}不是杰伦的新娘，抽中了无效字<strong>{value}</strong>字","{nick}被霸道总裁拒绝，抽中了无效字<strong>{value}</strong>字","{nick}被ta的世界删除，抽中了无效字<strong>{value}</strong>字","与{nick}YP的当晚来大姨妈！抽中了无效字<strong>{value}</strong>字","与{nick}是走失的兄妹，抽中了无效字<strong>{value}</strong>字","与{nick}表白被拒绝，抽中了无效字<strong>{value}</strong>字","{nick}扭动身体跳起广场舞，抽中了无效字<strong>{value}</strong>字","{nick}为ta删除了整个世界，抽中了无效字<strong>{value}</strong>字","{nick}看不到媚娘的G CUP，抽中了无效字<strong>{value}</strong>字");

        $id = intval($_GPC['id']);
        $openid = $_GPC['openid'];        
        $fromopenid =$_GPC['fromopenid'];        
        $reply = $this->get_reply($id);
        if(empty($reply)){
            die(json_encode(array("result"=>0, "message"=>"未找到活动!")));
        }
        if(empty($reply['isshow'])){
            die(json_encode(array("result"=>0,"message"=>"活动暂停，请等待活动开启!")));
        }
        
        $fromfans = pdo_fetch("select * from " . tablename('ewei_couplet_fans') . " where rid=:rid and openid=:openid limit 1", array(":rid" => $id, ":openid" => $fromopenid));   
        if(empty($fromfans)){
            die(json_encode(array("result"=>0, "message"=>"未找到该用户!")));
        }  
        $help = pdo_fetch("select * from " . tablename('ewei_couplet_fans_help') . " where rid=:rid and fansopenid=:fansopenid and openid=:openid limit 1", array(":rid" => $id,":fansopenid"=>$fromopenid, ":openid" => $openid));   
        if(!empty($help)){
            die(json_encode(array("result"=>0, "message"=>"您已经帮助过 TA 了!")));
        }
        if($reply['friendcount']>0){
            //判断帮忙次数
            $helpcount = pdo_fetchcolumn("select count(*) from " . tablename('ewei_couplet_fans_help') . " where rid=:rid and fansopenid=:fansopenid", array(":rid" => $id,":fansopenid"=>$fromopenid));   
            if($helpcount>=$reply['friendcount']){
                die(json_encode(array("result"=>0, "message"=>"哈哈，TA 已经被 {$reply['friendcount']} 个好友帮忙达到帮忙次数上限了，您就不能帮 TA 了!")));
            }
        }
        
        //找出规则设置
        $rules = unserialize($reply['rules']);
        foreach($rules as $r){
            if($r['id']==$fromfans['rule']){
                  $rule = $r;
                  break;
            }
        }
        $downtext =unserialize($fromfans['downtext']);
        
        $carr = array();
        foreach($downtext as $key => $dt){
            if(empty($dt['bingo'])){
                $carr[] = $key;
            }
        }     
        if(empty($carr)){
              die(json_encode(array("result"=>0, "message"=>"哈哈，TA 已经凑齐对联了，赶紧告诉 TA 来领取大奖吧!~")));
        }
        $rand = rand(0,count($carr)-1);
       
        $pos = $carr[$rand] ;
  
        $p = $rule["p".($pos+1)];
        $get_rand = rand(0,100); 
        $chars = $downtext[$pos]['char'];
        $success = false; //是否抽中
        $desc = "";
        $cookie = $this->getCookie($id);
        if(floatval($p)>=floatval($get_rand)){
             $success = true;
            //抽中,判断
            foreach($downtext as $key => &$dt){
                if($key==$pos)    {
                    $dt['bingo']=1;
                    $chars = $dt['char'];
                    break;
                }
            }     
            unset($dt);
            $desc = str_replace("{nick}", $cookie['nickname'],$succList[rand(0, count($succList)-1)]) ;
            $desc = str_replace("{value}", $chars,$desc) ;
       
        }
        else{
            //未抽中
             $success = false;

             //随即给一个字
             $rand_chars= "";
             $str="的是在和有大这主中人上为们地个用工时要动国产以我到他会作来分生对于学下级就年阶义发成部民可出能方进同行面说种过命度革而多子后自社加小机也经力线本电高量长党得实家定深法表着水理化争现所二起政三好十战无农使性前等反体合斗路图把结第里正新开论之物从当两些还天资事队批如应形想制心样干都向变关点育重其思与间内去因件日利相由压员气业代全组数果期导平各基或月毛然问比展那它最及外没看治提五解系林者米群头意只明四道马认次文通但条较克又公孔领军流入接席位情运器并飞原油放立题质指建区验活众很教决特此常石强极土少已根共直团统式转别造切九你取西持总料连任志观调七么山程百报更见必真保热委手改管处己将修支识病象几先老光专什六型具示复安带每东增则完风回南广劳轮科北打积车计给节做务被整联步类集号列温装即毫知轴研单色坚据速防史拉世设达尔场织历花受求传口断况采精金界品判参层止边清至万确究书术状厂须离再目海交权且儿青才证低越际八试规斯近注办布门铁需走议县兵固除般引齿千胜细影济白格效置推空配刀叶率述今选养德话查差半敌始片施响收华觉备名红续均药标记难存测士身紧液派准斤角降维板许破述技消底床田势端感往神便贺村构照容非搞亚磨族火段算适讲按值美态黄易彪服早班麦削信排台声该击素张密害侯草何树肥继右属市严径螺检左页抗苏显苦英快称坏移约巴材省黑武培著河帝仅针怎植京助升王眼她抓含苗副杂普谈围食射源例致酸旧却充足短划剂宣环落首尺波承粉践府鱼随考刻靠够满夫失包住促枝局菌杆周护岩师举曲春元超负砂封换太模贫减阳扬江析亩木言球朝医校古呢稻宋听唯输滑站另卫字鼓刚写刘微略范供阿块某功套友限项余倒卷创律雨让骨远帮初皮播优占死毒圈伟季训控激找叫云互跟裂粮粒母练塞钢顶策双留误础吸阻故寸盾晚丝女散焊功株亲院冷彻弹错散商视艺灭版烈零室轻血倍缺厘泵察绝富城冲喷壤简否柱李望盘磁雄似困巩益洲脱投送奴侧润盖挥距触星松送获兴独官混纪依未突架宽冬章湿偏纹吃执阀矿寨责熟稳夺硬价努翻奇甲预职评读背协损棉侵灰虽矛厚罗泥辟告卵箱掌氧恩爱停曾溶营终纲孟钱待尽俄缩沙退陈讨奋械载胞幼哪剥迫旋征槽倒握担仍呀鲜吧卡粗介钻逐弱脚怕盐末阴丰编印蜂急拿扩伤飞露核缘游振操央伍域甚迅辉异序免纸夜乡久隶缸夹念兰映沟乙吗儒杀汽磷艰晶插埃燃欢铁补咱芽永瓦倾阵碳演威附牙芽永瓦斜灌欧献顺猪洋腐请透司危括脉宜笑若尾束壮暴企菜穗楚汉愈绿拖牛份染既秋遍锻玉夏疗尖殖井费州访吹荣铜沿替滚客召旱悟刺脑措贯藏敢令隙炉壳硫煤迎铸粘探临薄旬善福纵择礼愿伏残雷延烟句纯渐耕跑泽慢栽鲁赤繁境潮横掉锥希池败船假亮谓托伙哲怀割摆贡呈劲财仪沉炼麻罪祖息车穿货销齐鼠抽画饲龙库守筑房歌寒喜哥洗蚀废纳腹乎录镜妇恶脂庄擦险赞钟摇典柄辩竹谷卖乱虚桥奥伯赶垂途额壁网截野遗静谋弄挂课镇妄盛耐援扎虑键归符庆聚绕摩忙舞遇索顾胶羊湖钉仁音迹碎伸灯避泛亡答勇频皇柳哈揭甘诺概宪浓岛袭谁洪谢炮浇斑讯懂灵蛋闭孩释乳巨徒私银伊景坦累匀霉杜乐勒隔弯绩招绍胡呼痛峰零柴簧午跳居尚丁秦稍追梁折耗碱殊岗挖氏刃剧堆赫荷胸衡勤膜篇登驻案刊秧缓凸役剪川雪链渔啦脸户洛孢勃盟买杨宗焦赛旗滤硅炭股坐蒸凝竟陷枪黎救冒暗洞犯筒您宋弧爆谬涂味津臂障褐陆啊健尊豆拔莫抵桑坡缝警挑污冰柬嘴啥饭塑寄赵喊垫康遵牧遭幅园腔订香肉弟屋敏恢忘衣孙龄岭骗休借丹渡耳刨虎笔稀昆浪萨茶滴浅拥穴覆伦娘吨浸袖珠雌妈紫戏塔锤震岁貌洁剖牢锋疑霸闪埔猛诉刷狠忽灾闹乔唐漏闻沈熔氯荒茎男凡抢像浆旁玻亦忠唱蒙予纷捕锁尤乘乌智淡允叛畜俘摸锈扫毕璃宝芯爷鉴秘净蒋钙肩腾枯抛轨堂拌爸循诱祝励肯酒绳穷塘燥泡袋朗喂铝软渠颗惯贸粪综墙趋彼届墨碍启逆卸航雾冠丙街莱贝辐肠付吉渗瑞惊顿挤秒悬姆烂森糖圣凹陶词迟蚕亿矩";  
             $len  = mb_strlen($str,'utf-8');
             while(1){
                  $rand_chars =  $this->cut_str($str,1,rand(0,$len));
                  if($rand_chars!=$chars && !empty($rand_chars)){
                      break;
                  }
             }
             $chars = $rand_chars;
             $desc = str_replace("{nick}", $cookie['nickname'],$failList[rand(0, count($failList)-1)]) ;
             $desc = str_replace("{value}", $chars,$desc) ;
        }
        //判断抽中几个
        $num  = 0;
        foreach($downtext as $key => $dt){
                if(!empty($dt['bingo']))    {
                    $num++;
                }
        } 
 
      
        $help  = array(
            "rid"=>$id,
            "fansopenid"=>$fromopenid,
            "openid"=>$openid,
            "nickname"=>$cookie['nickname'],
            "headurl"=>$cookie['headurl'],
            "desc"=>$desc,
            "status"=>$success?1:0,
            "createtime"=>time()
        );
        pdo_insert("ewei_couplet_fans_help",$help);
        $helpid = pdo_insertid();
       
        if($success) {
            $status = $fromfans['status'];
            if($num>=7){
                $status = 1;
              
               //减少奖品数
               $award_last = pdo_fetchcolumn("select award_last from ".tablename('ewei_couplet_reply')." where rid=:rid limit 1",array(":rid"=>$id));
               pdo_update("ewei_couplet_reply",array("award_last"=> $award_last-1),array("rid"=>$id));
            
            }
            pdo_update("ewei_couplet_fans",array("downtext"=>  serialize($downtext),'num'=>$num,'status'=>$status,'helps'=>$fromfans['helps']+1),array("openid"=>$fromopenid));
            
          
            
        }
        
       die(json_encode(array(
                "result"=>!$success?1:2,
                "helpid"=>$helpid,
                "char"=> $downtext[$pos]['char'],
                "pos"=>$pos,
                "desc"=>$desc,
                "num"=>$num
        )));
  }
 public function doMobileGetHelpers(){
     global $_W,$_GPC;
     $id = intval($_GPC['id']);
     $openid = $_GPC['openid'];        
     $pindex = max(1, intval($_GPC['page']));
     $psize = 5;
     $params = array(":rid"=>$id,":fansopenid"=>$openid);
     $list = pdo_fetchall("select id,headurl, `desc` from ".tablename('ewei_couplet_fans_help')." where rid=:rid and fansopenid=:fansopenid order by createtime desc  LIMIT ".($pindex - 1) * $psize.",".$psize,$params);
     include $this->template("friendlist");
  }
  
public function cut_str($string, $sublen, $start = 0, $code = 'UTF-8')
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
    public function doMobileJubao(){
        include $this->template('jubao');
    }
    public function doMobileInfo(){
        global $_W,$_GPC;
        $mobile = $_GPC['mobile'];
        $realname = $_GPC['realname'];
        if(empty($mobile) || empty($realname)){
            die(json_encode(array("result"=>0, "message"=>"信息登记不全，无法领奖!")));
        }  
        
        $id = intval($_GPC['id']);
        $openid = $_GPC['openid'];
        $reply = $this->get_reply($id);
        if(empty($reply)){
            die(json_encode(array("result"=>0, "message"=>"未找到活动!")));
        }
        if(empty($reply['isshow'])){
            die(json_encode(array("result"=>0,"message"=>"活动暂停，请等待活动开启!")));
        }
        $fans = pdo_fetch("select * from " . tablename('ewei_couplet_fans') . " where rid=:rid and openid=:openid limit 1", array(":rid" => $id, ":openid" => $openid));   
        if(empty($fans)){
            die(json_encode(array("result"=>0, "message"=>"未找到该用户!")));
        }  
        if($fans['num']<7){
            die(json_encode(array("result"=>0, "message"=>"未凑够 7 个字，无法登记领奖哦!~")));
        }  
        pdo_update("ewei_couplet_fans",array("realname"=>$realname,"mobile"=>$mobile,"log"=>1),array("openid"=>$openid,"rid"=>$id));
        die(json_encode(array("result"=>1)));
        
    }
    public function doWebgetaward() {
        global $_W, $_GPC;


        $id = $_GPC['id'];
        $rid = $_GPC['rid'];
        $reply = pdo_fetch("SELECT * FROM " . tablename('ewei_couplet_reply') . " WHERE rid = :rid limit 1", array(':rid' => $rid));
         if (empty($reply)) {
            message('未找到活动!', '', 'error');
        }
        
        $fans = pdo_fetch("select * from " . tablename('ewei_couplet_fans') . " where rid=:rid and id=:id limit 1", array(":rid" => $rid, ":id" => $id));
        if (empty($fans)) {
            message('未找到用户!', '', 'error');
        }
        pdo_update("ewei_couplet_fans", array("status"=>2,"consumetime"=>time()), array("id" => $fans['id']));
        message('操作成功!', referer(), "success");
    }

}
