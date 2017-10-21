<?php
/**
 *
 * 昵称头像
 *
 *
 */
defined('IN_IA') or exit('Access Denied');

class NickImg extends MysqlConn
{
    //获取昵称
    public function nickName()
    {
        global $_W, $_GPC;
        load()->model('mc');
        $result = mc_fansinfo($_W['member']['uid'], $_W['acid'], $_W['uniacid']);
        if ($result) {
            $nickname = $result['nickname'];
            if (empty($nickname)) {
                $nickname = $_W['fans']['nickname'];
                if (empty($nickname)) {
                    mc_oauth_userinfo($_W['acid']);
                    $nickname = $_W['fans']['tag']['nickname'];
                }
            }
        } else {
            $result = mc_fetch($_W['openid']);
            $nickname = $result['nickname'];
        }
        return $nickname;
    }
    //获取头像
    public function imgAvtar()
    {
        global $_W, $_GPC;
        load()->model('mc');
        $result = mc_fansinfo($_W['member']['uid'], $_W['acid'], $_W['uniacid']);
        if ($result) {
            $avatar = $result['avatar'];
            if (empty($avatar)) {
                $avatar = $_W['fans']['avatar'];
                if (empty($avatar)) {
                    mc_oauth_userinfo($_W['acid']);
                    $avatar = $_W['fans']['tag']['avatar'];
                }
            }
        } else {
            $result = mc_fetch($_W['openid']);
            $avatar = $result['avatar'];
        }
        return $avatar;
    }
    //生成永久二维码
    public function qrAdd()
    {
        global $_W, $_GPC;
        //创建公众号号对象
        $wob = WeAccount::create($_W['acid']);
        //查询是否存在二维码场景ID且为永久
        $qrcid = pdo_fetchcolumn("SELECT qrcid FROM " . tablename('qrcode') . " WHERE acid = :acid AND model= 2 AND qrcid > 90000 AND qrcid < 100000 ORDER BY qrcid DESC LIMIT 1", array(":acid" => $_W['acid']));
        //设置永久二维码参数
        if (empty($qrcid)) {
            $barcode['action_info']['scene']['scene_id'] = 90001;
        } else {
            $barcode['action_info']['scene']['scene_id'] = $qrcid + 1;
        }
        $barcode['action_name'] = 'QR_LIMIT_SCENE';
        //生成永久二维码
        $sult = $wob->barCodeCreateFixed($barcode);
        //二维码图片获取
        $imgs = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . $sult['ticket'];

        $url = $sult['url'];

        $scene_id = $barcode['action_info']['scene']['scene_id'];

        $_ruledata = array(
            'uniacid' => $_W['uniacid'],
            'name' => "家政服务",
            'module' => "zm_housekeepnew",
            'status' => 1,
            'displayorder' => 254,
        );

        $_rulekey = array(
            'uniacid' => $_W['uniacid'],
            'module' => 'zm_housekeepnew',
            'content' => 'new' . $scene_id,
            'type' => 1,
            'displayorder' => 254,
            'status' => 1
        );
        //插入规则列表
        pdo_insert('rule', $_ruledata);
        //查询上次插入的id;
        $qr['rid'] = pdo_insertid();

        $_rulekey['rid'] = $qr['rid'];
        //插入规则关键字
        pdo_insert('rule_keyword', $_rulekey);

        $qrcode = array(
            'uniacid' => $_W['uniacid'],
            'acid' => $_W['acid'],
            'qrcid' => $scene_id,
            'name' => '家政服务',
            'keyword' => "new" . $scene_id,
            'model' => 2,
            'ticket' => $sult['ticket'],
            'createtime' => time(),
            'status' => 1,
            'url' => $url
        );
        pdo_insert('qrcode', $qrcode);
        $qr['qrcode'] = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=" . $sult['ticket'];
        return $qr;
    }
    //男女
    public function nanNv($data){
            if ($data == 1) {
                return '男';
            } else if ($data == 2) {
                return '女';
            }

    }
    //生成随机数
    public function numRand($a)
    {
        $str = '1234567890abcdefghijklmnopqrstuvwxyz';
        $t1 = "";
        for ($i = 0; $i < $a; $i++) {
            $j = rand(0, 35);
            $t1 = $t1 . $str[$j];
        }
        return $t1;
    }
    //修改,删除,添加返回的信息
    public function succError($data, $url, $update = '')
    {
        global $_W;
        if ($data && empty($update)) {
            return message('添加成功', $url, 'success');
        } else {
            if ($data && ($update == '1')) {
                return message('修改成功', $url, 'success');
            } else {
                if ($data && ($update == '2')) {
                    return message('删除成功', $url, 'success');
                } else {
                    return message('删除失败', $url, 'error');
                }
                return message('修改失败', $url, 'error');
            }
            return message('添加失败', $url, 'error');
        }

    }
    //支付状态
    public function getState($data)
    {
        if ($data === 0) {
            return '未支付';
        } else if ($data == 1) {
            return '未派单';
        } else if ($data == 2) {
            return '服务中';
        } else if ($data == 3) {
            return '已完成';
        } else if ($data == 4) {
            return '已评价';
        }
    }


    //检测是否关注
    public function follow(){
        global $_W;
        if(checkauth()&&$_W['fans']['follow'] !=1){
            message('请您先关注',$this->createMobileUrl('isfollow'),'info');
        }else{
            return true;
        }
    }
    public function doMobileIsfollow()
    {
        header('Location:https://open.weixin.qq.com/qr/code/?username='.$this->base['lead']);
    }
    //分享数据
    public function shareDate(){
        return array(
            'title'   => $this->base['share_title'],
            'link'    => $this->base['share_link'],
            'imgUrl'  => tomedia($this->base['share_icon']),
            'content' => $this->base['share_content']
        );
    }
    //支付
    public function payMent($tid,$ordersn,$title,$fee){
        $params = array(
                'tid' => $tid,      //充值模块中的订单号，此号码用于业务模块中区分订单，交易的识别码
                'ordersn' => $ordersn,  //收银台中显示的订单号
                'title' => $title,          //收银台中显示的标题
                'fee' => $fee,      //收银台中显示需要支付的金额,只能大于 0
                'user' => $this->_uid,     //付款用户, 付款的用户名(选填项)
            );
        return    $this->pay($params);
    }

    public function payResult($ret) {
        global $_W;
        if($ret['result'] == 'success') {
            $list = $this->myGet('xk_housekeepmoneyrc',array('wid'=>$_W['uniacid'],'tid'=>$ret['tid']));
            if ($list){
                $type = $this->myGet('core_paylog',array('uniacid'=>$_W['uniacid'],'tid'=>$ret['tid']));
                if ($type['type'] == 'credit'){
                    header('Location:'.$_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&do=moneycz&jf=1&fee=".$ret['fee']."&m=".$this->module['name']);
                    message('不可以用余额充值,余额退回您的账户',$_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&do=moneycz&jf=1&fee=".$ret['fee']."&m=".$this->module['name'],'info');
                }else{
                    header('Location:'.$_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&do=moneycz&fee=".$ret['fee']."&tid=".$ret['tid']."&m=".$this->module['name']);
                    message('充值成功',$_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&do=moneycz&fee=".$ret['fee']."&tid=".$ret['tid']."&m=".$this->module['name'],'success');
                }
            }else{
                header("Location:".$_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&do=paym&sta=1&tid=".$ret['tid']."&m=".$this->module['name']);
                exit();
                message('已经成功支付',$_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&do=paym&sta=1&tid=".$ret['tid']."&m=".$this->module['name'],'success');
            }
        }
    }
    public function payFshi($data){
        if ($data == 'alipay') {
            return '支付宝';
        } else if ($data == 'wechat') {
            return '微信支付';
        } else if ($data == 'credit') {
            return '余额支付';
        }
    }
    //模板消息
    public function wob($openid,$mbid,$data,$url='',$topcolor = '#FF683F'){
        global $_GPC,$_W;
        $wob = WeAccount::create($_W['acid']);
        return $wob->sendTplNotice($openid,$mbid,$data,$url,$topcolor);
    }

    //企业支付到用户
    public function paypeople($from,$money){
        global $_W,$_GPC;

        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';

        $_data = $this->myGet('xk_housekeepsetting',array('wid'=>$_W['uniacid']));

        function unicode() {
            $str = uniqid(mt_rand(),1);
            $str=sha1($str);
            return md5($str);
        }

        $pars = array();

        $pars['mch_appid'] =$_W['account']['key']; //商户的应用appid

        $pars['mchid'] = $this->module['config']['mchid']; //商户ID

        $pars['nonce_str'] = unicode() ;//这个据说是唯一的字符串下面有方法 unicode();

        $pars['partner_trade_no'] =$this->module['config']['mchid'].TIMESTAMP.rand(1000000, 9999999); //.time();//这个是订单号。

        $pars['openid'] = $from; //授权用户的openid。这个必须得是用户授权才能用

        $pars['check_name'] = 'NO_CHECK'; //这个是设置是否检测用户真实姓名的

        $pars['amount'] = $money*100;//提现金额

        $pars['desc'] = '提成'; //订单描述

        $pars['spbill_create_ip'] = $_SERVER['SERVER_ADDR'];//获取服务器的ip

        $pars=array_filter($pars);

        ksort($pars);

        function arraytoxml($data){
            $str='<xml>';
            foreach($data as $k=>$v) {
                $str.='<'.$k.'>'.$v.'</'.$k.'>';
            }
            $str.='</xml>';
            return $str;
        }
        function xmltoarray($xml) {
            //禁止引用外部xml实体
            libxml_disable_entity_loader(true);

            $xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);

            $val = json_decode(json_encode($xmlstring),true);

            return $val;
        }
        function curl($param="",$url,$dat) {
            $postUrl = $url;
            $curlPost = $param;
            $ch = curl_init();                                      //初始化curl
            curl_setopt($ch, CURLOPT_URL,$postUrl);                 //抓取指定网页
            curl_setopt($ch, CURLOPT_HEADER, 0);                    //设置header
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);            //要求结果为字符串且输出到屏幕上
            curl_setopt($ch, CURLOPT_POST, 1);                      //post提交方式
            curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);           // 增加 HTTP Header（头）里的字段
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);        // 终止从服务端进行验证
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch,CURLOPT_SSLCERT,$dat['apiclient_cert']); //这个是证书的位置
            curl_setopt($ch,CURLOPT_SSLKEY,$dat['apiclient_key']); //这个也是证书的位置
            curl_setopt($ch,CURLOPT_CAINFO,$dat['rootca']);
            $data = curl_exec($ch);                                 //运行curl
            curl_close($ch);
            return $data;
        }

        $str='';
        foreach($pars as $k=>$v) {
            $str.=$k.'='.$v.'&';
        }

        $secrect_key= $this->module['config']['password'];///这个就是个API密码。32位的。。随便MD5一下就可以了

        $str.='key='.$secrect_key;
        $pars['sign']=md5($str);
        $xml=arraytoxml($pars);
        $res = curl($xml,$url,$_data);
        $ret = xmltoarray($res);

        return $ret;
    }
    public function cvsDo($filter,$list){
        /* 输入到CSV文件 */
        $html = "\xEF\xBB\xBF";
        /* 输出表头 */
        foreach ($filter as $key => $title) {
            $html .= $title . "\t,";
        }
        $html .= "\n";
        foreach ($list as $k => $v) {
            foreach ($filter as $key => $title) {
                if ($key == 'state') {
                    $html .= $this->getState(intval($v['state'])) . "\t, ";
                }else {
                    $html .= $v[$key] . "\t, ";
                }
            }
            $html .= "\n";
        }
        /* 输出CSV文件 */
        header("Content-type:text/csv");
        header("Content-Disposition:attachment; filename=导出数据.csv");
        echo $html;
        exit();
    }
    public function xinCvs($filter,$list){
        /* 输入到CSV文件 */
        $html = "\xEF\xBB\xBF";
        /* 输出表头 */
        foreach ($filter as $key => $title) {
            $html .= $title . "\t,";
        }
        $html .= "\n";
        foreach ($list as $k => $v) {
            foreach ($filter as $key => $title) {
                if ($key == 'fwstgy') {
                    if ($v['fwstgy'] == 1 ){
                        $html .= '推广员'. "\t, ";
                    }else{
                        $html .= '服务师'. "\t, ";
                    }
                }elseif($key == 'sex') {
                    if ($v['sex'] == 1 ){
                        $html .= '男'. "\t, ";
                    }else{
                        $html .= '女'. "\t, ";
                    }
                }else{
                    $html .= $v[$key] . "\t, ";
                }
            }
            $html .= "\n";
        }
        /* 输出CSV文件 */
        header("Content-type:text/csv");
        header("Content-Disposition:attachment; filename=导出数据.csv");
        echo $html;
        exit();
    }
    public function cvsXin($filter,$list){
        /* 输入到CSV文件 */
        $html = "\xEF\xBB\xBF";
        /* 输出表头 */
        foreach ($filter as $key => $title) {
            $html .= $title . "\t,";
        }
        $html .= "\n";
        foreach ($list as $k => $v) {
            foreach ($filter as $key => $title) {
                if ($key == 'addtime') {
                    $html .= date('Y-m-d H:i:s',$v['addtime']). "\t, ";
                }else{
                    $html .= $v[$key] . "\t, ";
                }
            }
            $html .= "\n";
        }
        /* 输出CSV文件 */
        header("Content-type:text/csv");
        header("Content-Disposition:attachment; filename=导出数据.csv");
        echo $html;
        exit();
    }
}
