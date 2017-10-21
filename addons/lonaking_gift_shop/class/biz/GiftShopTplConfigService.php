<?php

/**
 * Created by PhpStorm.
 * User: leon
 * Date: 15/9/29
 * Time: 上午12:34
 */
require_once dirname(__FILE__).'/../../../lonaking_flash/FlashCommonService.php';
require_once dirname(__FILE__).'/../../../lonaking_flash/FlashUserService.php';
class GiftShopTplConfigService extends FlashCommonService
{

    private $userService;
    /**
     * TplConfigService constructor.
     */
    public function __construct()
    {
        $this->plugin_name = 'lonaking_gift_shop';
        $this->table_name = 'lonaking_gift_shop_tpl_template_config';
        $this->columns = 'id,uniacid,get_notice,check_status_access_notice,check_status_refuse_notice,send_notice';
        $this->userService = new FlashUserService();
    }


    public function updateTplConfigByUniacit($config){
        $oldConfig = $this->checkConfigByUniacid($config['uniacid']);
        $config['id'] = $oldConfig['id'];
        $this->updateData($config);
        return $config;
    }

    public function initTplConfig(){
        global $_W;
        $uniacid = $_W['uniacid'];
        $tplConfig = array(
            'uniacid' => $uniacid,
            'get_notice' => '',//礼品兑换成功提醒
            'check_status_access_notice' => '',//审核成功
            'check_status_refuse_notice' => '',//审核失败
            'send_notice' => '',//发货
        );
        $tplConfig = $this->insertData($tplConfig);
        return $tplConfig;
    }
    /**
     * 获取当前唯一公众号的模板通知配置信息,如果没有查到，则插入一条记录
     * @param null $uniacid
     * @return bool
     */
    public function checkConfigByUniacid($uniacid = null){
        global $_W;
        $uniacid = empty($uniacid) ? $_W['uniacid'] : $uniacid;
        $config = pdo_fetch("SELECT ".$this->columns." FROM ".tablename($this->table_name)." WHERE uniacid=:uniacid",array(':uniacid'=>$uniacid));
        if(empty($config)){
            $config = $this->initTplConfig();
        }
        return $config;
    }

    /**
     * 礼品兑换成功的模板消息
     * @param $openid
     */
    public function sendGetGiftSuccessTplNotice($orderOrId){
        $order = null;
        if(is_array($orderOrId)){
            $order = $orderOrId;
        }else{
            //$order = $this->giftOrderService->selectGiftOrdersDetail($orderOrId);
            throw new Exception("订单信息错误",400);
        }
        global $_W;
        $account = $this->createWexinAccount();
        $config = $this->checkConfigByUniacid($_W['uniacid']);
        $score = $this->userService->fetchUserScore($order['openid']);
        $template_id = $config['get_notice'];
        $url = '';
        $postData = array(
            'first' => array(
                'value' => '礼品兑换提交成功，信息如下',
                'color' => '#FF683F'
            ),
            'keyword1' => array(
                'value' => $order['gift_name'],
                'color' => '#FF683F'
            ),
            'keyword2' => array(
                'value' => $order['price'],
                'color' => '#FF683F'
            ),
            'keyword3' => array(
                'value' => $score,
                'color' => '#FF683F'
            ),
            'keyword4' => array(
                'value' => date('m月d日 H:i',$order['createtime']),
                'color' => '#FF683F'
            ),
            'remark' => array(
                'value' => '礼品兑换请求成功,管理员审核通过后将为您发放礼品',
                'color' => '#FF683F'
            )
        );
        $sendArray = array(
            'openid' => $order['openid'],
            'template_id' =>$template_id,
            'postData' => $postData,
            'url' => $url
        );
        $result = $account->sendTplNotice($order['openid'], $template_id, $postData, $url);
    }

    /**
     * 发送礼品兑换订单审核通过的模板消息
     * @param $orderOrId
     */
    public function sendGiftOrderCheckStatusAccessNotice($orderOrId){
        $order = null;
        if(is_array($orderOrId)){
            $order = $orderOrId;
        }else{
            //$order = $this->giftOrderService->selectGiftOrdersDetail($orderOrId);
            throw new Exception("订单信息错误",400);
        }
        $this->log($order);
        global $_W;
        $account = $this->createWexinAccount();
        $config = $this->checkConfigByUniacid($_W['uniacid']);
        $template_id = $config['check_status_access_notice'];
        $url = '';
        $postData = array(
            'first' => array(
                'value' => '您兑换的礼品['.$order['gift_name'].']审核通过了',
                'color' => '#FF683F'
            ),
            'keyword1' => array(
                'value' => '通过',
                'color' => '#FF683F'
            ),
            'keyword2' => array(
                'value' => date('m月d日 H:i',$order['updatetime']),
                'color' => '#FF683F'
            ),
            /*
            'remark' => array(
                'value' => '礼品兑换请求成功,管理员审核通过后将为您发放礼品',
                'color' => '#FF683F'
            )
            */
        );
        $sendArray = array(
            'openid' => $order['openid'],
            'template_id' =>$template_id,
            'postData' => $postData,
            'url' => $url
        );
        $result = $account->sendTplNotice($order['openid'], $template_id, $postData, $url);
    }

    /**
     * 拒绝一个兑换记录
     * @param $orderOrId
     */
    public function sendGiftOrderCheckStatusRefuseNotice($orderOrId){
        $order = null;
        if(is_array($orderOrId)){
            $order = $orderOrId;
        }else{
            //$order = $this->giftOrderService->selectGiftOrdersDetail($orderOrId);
            throw new Exception("订单信息错误",400);
        }
        global $_W;
        $account = $this->createWexinAccount();
        $config = $this->checkConfigByUniacid($_W['uniacid']);
        $template_id = $config['check_status_access_notice'];
        $url = '';
        $postData = array(
            'first' => array(
                'value' => '您兑换的礼品['.$order['gift_name'].']审核没有通过',
                'color' => '#FF683F'
            ),
            'keyword1' => array(
                'value' => '未通过',
                'color' => '#FF683F'
            ),
            'keyword2' => array(
                'value' => date('m月d日 H:i',$order['updatetime']),
                'color' => '#FF683F'
            ),
            'remark' => array(
                'value' => '您的兑换由于'.$order['remark'].'我们无法通过，我们已将礼品积分退回您的账户，为您带来的不便非常抱歉，感谢你对我们的支持',
                'color' => '#FF683F'
            )
        );
        $sendArray = array(
            'openid' => $order['openid'],
            'template_id' =>$template_id,
            'postData' => $postData,
            'url' => $url
        );
        $result = $account->sendTplNotice($order['openid'], $template_id, $postData, $url);
    }
    /**
     * 发货通知
     */
    public function sendGiftSendUpTplNotice($orderOrId){
        $order = null;
        if(is_array($orderOrId)){
            $order = $orderOrId;
        }else{
            //$order = $this->giftOrderService->selectGiftOrdersDetail($orderOrId);
            throw new Exception("订单信息错误",400);

        }
        global $_W;
        $account = $this->createWexinAccount();
        $config = $this->checkConfigByUniacid($_W['uniacid']);
        $template_id = $config['send_notice'];
        $url = '';
        $postData = array(
            'first' => array(
                'value' => '您兑换的礼品['.$order['gift_name'].']已经发货，请关注物流信息',
                'color' => '#FF683F'
            ),
            'keyword1' => array(
                'value' => $order['gift_name'],
                'color' => '#FF683F'
            ),
            'keyword2' => array(
                'value' => '快递公司',
                'color' => '#FF683F'
            ),
            'keyword3' => array(
                'value' => $order['trans_num'],
                'color' => '#FF683F'
            ),
            'keyword4' => array(
                'value' => $order['real_name'].'('.$order['target'].')',
                'color' => '#FF683F'
            ),
            'remark' => array(
                'value' => '您的礼品已经由快递发出，您可以在百度查询运单号关注物流动态',
                'color' => '#FF683F'
            )
        );
        $sendArray = array(
            'openid' => $order['openid'],
            'template_id' =>$template_id,
            'postData' => $postData,
            'url' => $url
        );
        $result = $account->sendTplNotice($order['openid'], $template_id, $postData, $url);
    }

    /**
     * 邀请关注积分奖励通知
     */
    public function sendInviteFollowTplNotice($score,$openid){
        global $_W;
        $account = $this->createWexinAccount();
        $config = $this->checkConfigByUniacid($_W['uniacid']);
        $template_id = $config['invite_score_notice'];
        $url = '';
        $postData = array(
            'first' => array(
                'value' => '有用户通过您的二维码关注,系统已经将奖励积分发放到您的账户，请注意查看',
                'color' => '#FF683F'
            ),
            'keyword1' => array(
                'value' => $score,
                'color' => '#FF683F'
            ),
            'keyword2' => array(
                'value' => date('m月d日 H:i',time()),
                'color' => '#FF683F'
            ),
            'remark' => array(
                'value' => 'nihao',
                'color' => '#FF683F'
            )
        );
        $sendArray = array(
            'openid' => $openid,
            'template_id' =>$template_id,
            'postData' => $postData,
            'url' => $url
        );
        $result = $account->sendTplNotice($openid, $template_id, $postData, $url);
    }

    /**
     * 邀请关注积分奖励通知
     */
    public function sendInviteSecondSharemanTplNotice($score,$openid,$name = ''){
        global $_W;
        $account = $this->createWexinAccount();
        $config = $this->checkConfigByUniacid($_W['uniacid']);
        $template_id = $config['invite_score_notice'];
        $url = '';
        $postData = array(
            'first' => array(
                'value' => '邀请推广人'.$name.'获得积分奖励，感谢您的支持,请再接再厉',
                'color' => '#FF683F'
            ),
            'keyword1' => array(
                'value' => $score,
                'color' => '#FF683F'
            ),
            'keyword2' => array(
                'value' => date('m月d日 H:i',time()),
                'color' => '#FF683F'
            ),
            'remark' => array(
                'value' => '',
                'color' => '#FF683F'
            )
        );
        $sendArray = array(
            'openid' => $openid,
            'template_id' =>$template_id,
            'postData' => $postData,
            'url' => $url
        );
        $result = $account->sendTplNotice($openid, $template_id, $postData, $url);
    }
    /**
     * 根据行业模板库中的id获取长templateId
     * @param $shoreId
     * @return array
     */
    public function fetchTemplateIdByShort($shoreId){
        global $_W;
        $account = $this->createWexinAccount();
        $token = $account->fetch_token();
        $apiUrl = 'https://api.weixin.qq.com/cgi-bin/template/api_add_template?access_token='.$token;
        //发送post请求
        load()->func('communication');
        $response = ihttp_post($apiUrl, array('template_id_short' => $shoreId));
        $result = json_decode($response['content'], true);
        if($result['errcode'] != 0){
            return error($result['errcode'], $result['errmsg']);
        }
        return $result['template_id'];

    }
}