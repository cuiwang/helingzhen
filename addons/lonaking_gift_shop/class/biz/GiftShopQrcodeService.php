<?php

/**
 * Created by PhpStorm.
 * User: leon
 * Date: 15/9/4
 * Time: 上午12:23
 * Exception 7开头
 */
class GiftShopQrcodeService
{

    /**
     * 生成一个二维码
     * @return array
     * @throws Exception 当声称失败或者公众号无权限则抛出异常
     */
    public function generateQrcode(){
        global $_W;

        $result = array();
        load()->classs('weixin.account');
        $account = WeiXinAccount::create($_W['account']['acid']);
        /*生成二维码*/
        $scene_id = $this->generate_scene_id($_W['account']['acid']);
        $barcode = array(
            'expire_seconds' => 604800, // 二维码的有效时间, 单位 秒.
            'action_name' => 'QR_SCENE',
            'action_info' => array(
                'scene' => array(
                    'scene_id' => $scene_id
                )
            )
        );
        $qrcode = $account->barCodeCreateDisposable($barcode);
        if($qrcode['errno'] != -1 && !empty($qrcode['ticket']) ){//有权限生成二维码
            $qrcode_url = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".urlencode($qrcode['ticket']);
            $result['qrcode'] = $qrcode_url;
            $result['qrcode_updatetime'] = time();
            $result['scene_id'] = $scene_id;
        }else{
            throw new Exception("微信号无权限生成二维码",7400);
        }
        $result['scene_id'] = $barcode['action_info']['scene']['scene_id'];
        return $result;
    }

    /**
     * 生成一个场景值
     * @param $uniacid
     * @return int
     */
    private function generate_scene_id($uniacid){
        $max = pdo_fetch("SELECT uniacid,max_scene_id FROM ".tablename('lonaking_supertask_max_scene')." WHERE uniacid =:uniacid",array(':uniacid'=>$uniacid));
        if(empty($max)){
            $data = array(
                'uniacid' => $uniacid,
                'max_scene_id' => 1
            );
            pdo_insert("lonaking_supertask_max_scene",$data);
            return 1;
        }else{
            $scene_id = $max['max_scene_id'] + 1;
            $data = array(
                'uniacid' => $uniacid,
                'max_scene_id' => $scene_id
            );
            pdo_update("lonaking_supertask_max_scene",$data, array('uniacid'=>$uniacid));
            return $scene_id;
        }
    }
}