上传音乐
修改php.ini
upload_max_filesize 大小
不让是默认的设置

修改 data/config.php
$config['upload']['image']['extentions'] = array('gif', 'jpg', 'jpeg', 'png','mp3','mp4');

该模块包含客户定制的一键关注模块。后台可以很方便的管理很多公众号。


ALTER TABLE ims_fineness_article  MODIFY COLUMN content mediumtext ;


public function doMobileAjaxPay() {
        global $_W, $_GPC ;
        $price =  $_GPC['price'];
        if($price==0){
            $price=0.01;
        }
        $uniacid=$_W['uniacid'];
        $set=  pdo_fetch("SELECT * FROM ".tablename('fineness_sysset')." WHERE weid=:weid limit 1", array(':weid' => $uniacid));
        if (empty($set)) {

            $res['code']=501;
            $res['msg']="抱歉，基本参数没设置";
            return json_encode($res);
        }
        load()->model('mc');
        $userInfo = mc_oauth_userinfo();

        $jsApi = new JsApi_pub($set);

        $jsApi->setOpenId($userInfo['openid']);

        $unifiedOrder = new UnifiedOrder_pub($set);
        $unifiedOrder->setParameter("openid",$userInfo['openid']);//商品描述
        $unifiedOrder->setParameter("body", "赞赏");//商品描述

        $timeStamp = time();
        $out_trade_no = $set['appid']."$timeStamp";
        $unifiedOrder->setParameter("out_trade_no", $out_trade_no);//商户订单号
        $unifiedOrder->setParameter("total_fee", $price*100);//总金额
        $notifyUrl = $_W['siteroot'] . "addons/" . AMOUSE_ARTICLE . "/notify.php";
        $unifiedOrder->setParameter("notify_url", $notifyUrl);//通知地址
        $unifiedOrder->setParameter("trade_type", "JSAPI");//交易类型
        $prepay_id = $unifiedOrder->getPrepayId();
        $jsApi->setPrepayId($prepay_id);
        $jsApiParameters = $jsApi->getParameters();
        $res['code']=200;
        $res['msg']=$jsApiParameters;
        return json_encode($res);
    }

    public function doMobilePaySuccess() {
        global $_W,$_GPC;
        $uniacid=$_W['uniacid'];
        $price=$_GPC['price'];
        $aid=$_GPC['aid'];
        $article=pdo_fetch('select * from '.tablename('fineness_article').' where weid=:weid AND id=:id',array(':weid'=>$uniacid,':id'=>$aid));
        load()->model('mc');
        $userInfo = mc_oauth_userinfo();
        if (empty($userInfo) && empty($userInfo['nickname'])) {//已关注过
            $res['code']=202;
            $res['msg']="您还没有关注，请关注后参与。";
            return json_encode($res);
        }
        load()->func('logging');

        if(!empty($article)) {
            $data = array(
                'weid' => $uniacid,
                'price' =>$price,
                'aid' => $aid,
                'author' => $userInfo['nickname'],
                'thumb' => $userInfo['avatar'],
                'openid' => $userInfo['openid'],
                'createtime' => time()
            );
            pdo_insert('fineness_admire', $data);
            // $this->sendOrderSuccessTplMsg($oid,$meal['title']);
        }
        $res['code']=200;
        $res['msg']='sucess';
        return json_encode($res);
    }