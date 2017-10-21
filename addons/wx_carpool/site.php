
<?php
/**
 * 微信拼车模块微站定义
 *
 * @author DoubleWei
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
require_once(dirname(__FILE__) . "/model/common.php");

class Wx_carpoolModuleSite extends WeModuleSite {


    public function __construct(){
        error_reporting(E_ALL ^ E_NOTICE );

        /*调用公共入口*/
        commonModel::entry();
    }
    
    
    public function doMobileIndex() {
        //手机端首页
                require_once(dirname(__FILE__)."/inc/mobile/doMobileIndex.php");
    }

    public function doMobileOrderPage(){
        /*订单分页接口*/
        require_once(dirname(__FILE__)."/inc/mobile/doMobileOrderPage.php");
    }

    public function doMobileComment(){
        /*评论接口*/
        require_once(dirname(__FILE__)."/inc/mobile/doMobileComment.php");
    }

    public function doMobileCommentList(){
        /*评论列表*/
        require_once(dirname(__FILE__)."/inc/mobile/doMobileCommentList.php");
    }


    public function doMobileForm() {
        //手机端表单页面
        require_once(dirname(__FILE__)."/inc/mobile/doMobileForm.php");
    }
    public function doMobilePrompt() {
    //手机端提示页面
     require_once(dirname(__FILE__)."/inc/mobile/doMobilePrompt.php");
    }
    public function doMobileAddOrder() {
        //手机端新增订单控制器
        require_once(dirname(__FILE__)."/inc/mobile/doMobileAddOrder.php");
    }
    public function doMobileDetails() {
        //手机端订单详情页面
        require_once(dirname(__FILE__)."/inc/mobile/doMobileDetails.php");
    }
    public function doMobilePersonal() {
        //手机端个人中心界面
        require_once(dirname(__FILE__)."/inc/mobile/doMobilePersonal.php");
    }
    public function doMobileRecharge() {
        //手机端充值页面
        require_once(dirname(__FILE__)."/inc/mobile/doMobileRecharge.php");
    }
    /*APP端付款返回结果结果*/
    public function payResult($params){
        require_once  (dirname(__FILE__)."/inc/mobile/doMobilePayResult.php" );
    }

	public function doMobileCarpool() {
		//这个操作被定义用来呈现
	}
	public function doWebCarousel() {
		//轮播设置
        require_once(dirname(__FILE__)."/inc/web/doWebCarousel.php");

	}
    public function doWebAddIndex() {
        //轮播设置
        require_once(dirname(__FILE__)."/inc/web/doWebAddIndex.php");

    }


    public function doWebAddCarousel() {
        //添加修改轮播图片
        require_once(dirname(__FILE__)."/inc/web/doWebAddCarousel.php");

    }

    public function doWebDelCarousel() {
        //删除轮播图片
        require_once(dirname(__FILE__)."/inc/web/doWebDelCarousel.php");

    }

	public function doWebManager2() {
         //参数配置
        require_once(dirname(__FILE__)."/inc/web/doWebParConf.php");
	}
	public function doWebManager3() {
        //用户管理
        require_once(dirname(__FILE__)."/inc/web/doWebUser.php");
    }
	public function doWebManager4() {
        //信息管理页面
        require_once(dirname(__FILE__)."/inc/web/doWebInfo.php");

	}
    public function doWebOpOrder() {
        //信息管理操作控制器
        require_once(dirname(__FILE__)."/inc/web/doWebOpOrder.php");

    }

	public function doWebManager5() {
		//支付管理界面
        require_once(dirname(__FILE__)."/inc/web/doWebPayment.php");
	}
    public function doWebManager6() {
        //订单详情页
        require_once(dirname(__FILE__)."/inc/web/doWebOrderDetails.php");
    }

	public function doWebManager7() {
        require_once(dirname(__FILE__)."/inc/web/doWebCommentList.php");
        //这个操作被定义用来呈现 管理中心导航菜单
	}
	public function doWebManager8() {
        require_once(dirname(__FILE__)."/inc/web/doWebDelComment.php");

        //这个操作被定义用来呈现 管理中心导航菜单
	}
	public function doWebManage9() {
        require_once(dirname(__FILE__)."/inc/web/doWebShowTplSet.php");

        //这个操作被定义用来呈现 管理中心导航菜单
	}
	public function doWebManage10() {
		//这个操作被定义用来呈现 管理中心导航菜单
	}


    //模板消息通知提醒
    public function sendtpl($openid,$url,$template_id,$content){
        global $_GPC,$_W;
        load()->classs('weixin.account');
        load()->func('communication');
        $obj = new WeiXinAccount();
        $access_token = $obj->fetch_available_token();
        $data = array(
            'touser' => $openid,
            'template_id' => $template_id,
            'url' => $url,
            'topcolor' => "#FF0000",
            'data' => $content,
        );
        $json = json_encode($data);
        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$access_token;
        $ret = ihttp_post($url,$json);
        $resp = @json_decode($ret['content'], true);
        return $resp;
    }



}