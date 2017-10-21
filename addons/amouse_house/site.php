<?php
defined('IN_IA') or exit('Access Denied');
define("AMOUSE_HOUSE_RES", "../addons/amouse_house/ui/");
class Amouse_HouseModuleSite extends WeModuleSite {

    public function doMobileNew(){
        global $_W,$_GPC;
        $a= !empty($_GPC['a']) ? $_GPC['a'] : 'rent';
        load()->func('file');
        load()->func('tpl');
        $setting= $this->get_sysset($weid);
        $wxid= !empty($_GPC['wxid']) ? $_GPC['wxid'] : $_W['fans']['from_user'];


        $data = array(
            'weid'=> $_W['uniacid'],
            'title'=> trim($_GPC['title']),
            'price'=> trim($_GPC['price']),
            'square_price'=> trim($_GPC['square_price']),
            'area'=>trim($_GPC['area']),
            'house_type'=> $_GPC['house_type'],
            'floor'=> trim($_GPC['floor']),
            'orientation'=> $_GPC['orientation'],
            'createtime'=> TIMESTAMP,
            'type'=> trim($_GPC['type']),
            'recommed'=>0,
            'contacts'=> trim($_GPC['contacts']),
            'phone'=> trim($_GPC['phone']),
            'introduction'=> trim($_GPC['introduction']),
            'openid'=> $wxid,
            'thumb1' => $_GPC['thumb1'],
            'thumb2' => $_GPC['thumb2'],
            'thumb3' => $_GPC['thumb3'],
            'thumb4' => $_GPC['thumb4'],
            'brokerage'=>$_GPC['brokerage'],
            'location_p' => trim($_GPC['location_p']),
            'location_c' => trim($_GPC['location_c']),
            'location_a' => trim($_GPC['location_a']),
            'place' => trim($_GPC['place']),
            'lng' => trim($_GPC['lng']),
            'lat' => trim($_GPC['lat']),
        );
        if($setting && $setting['isadjuest']==0){
            $data['status'] =1;
        }else{
            $data['status'] =0;
        }
        load()->classs('weixin.account');
        $accObj= WeixinAccount::create($_W['acid']);
        if($a == 'rent') {

            if ($_W['ispost']) {
                pdo_insert('amouse_house',$data);

                if($setting && !empty($setting['openid'])) {
                    $orderinfo .= '--------------------\n';
                    $orderinfo .= "您有一条新的租售房产提交信息\n";
                    $orderinfo .= "房产名称：{$data['title']}\n";
                    $orderinfo .= "房产地址：{$data['place']}\n";
                    $orderinfo .= "联系电话：{$data['phone']}\n";
                    $send['msgtype'] = 'text';
                    $send['text'] = array('content' => urlencode($orderinfo));

                    $send['touser'] = trim($setting['openid']);
                    $accObj->sendCustomNotice($send);
                }

                message('提交房产信息成功!',$this->createMobileUrl('index',array('type'=>$_GPC['type'])),'success');
            }
            include $this->template('house/rent_new');
        }elseif($a='house'){
            if ($_W['ispost']) {
                pdo_insert('amouse_house',$data);

                if($setting && !empty($setting['openid'])) {
                    $orderinfo .= '--------------------\n';
                    $orderinfo .= "您有一条新的出售，求购房产提交信息\n";
                    $orderinfo .= "房产名称：{$data['title']}\n";
                    $orderinfo .= "房产地址：{$data['place']}\n";
                    $orderinfo .= "联系电话：{$data['phone']}\n";
                    $send['msgtype'] = 'text';
                    $send['text'] = array('content' => urlencode($orderinfo));

                    $send['touser'] = trim($setting['openid']);
                    $accObj->sendCustomNotice($send);
                }

                message('提交房产信息成功!',$this->createMobileUrl('index',array('type'=>$_GPC['type'])),'success');
            }
            include $this->template('house/house_new');
        }
    }
    public function doMobilePosition(){
        global $_W,$_GPC;
		$weid=$_W['uniacid'];
		$setting= $this->get_sysset($weid);
        $op= !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        if($op=='display'){
            $location_ps = pdo_fetchall("select distinct location_p from ".tablename('amouse_house')." where  status=1 and weid = ".$weid);
            $cities = pdo_fetchall("select distinct location_p,location_c from ".tablename('amouse_house')." where status = 1 and weid = ".$weid);
        }
        if($op=='positionsort'){
            $op = 'sort';
            $location_c = trim($_GPC['location_c']);
            $slides = pdo_fetchall("select * from ".tablename('amouse_house_slide')." where weid = ".$weid." and isshow = 1 order by listorder desc");
            $pindex= max(1, intval($_GPC['page']));
            $psize= 20;
            $type = $_GPC['type'];
            $list = pdo_fetchall("SELECT * FROM ".tablename('amouse_house')." WHERE weid ='{$weid}' AND location_c = '".$location_c."' AND status=1 ORDER BY createtime DESC LIMIT ".($pindex -1) * $psize.','.$psize); //分页
            $total= pdo_fetchcolumn('SELECT COUNT(id) FROM '.tablename('amouse_house')." WHERE weid ='{$weid}' AND location_c = '".$location_c."' AND status=1 ORDER BY createtime DESC");
            $pager = pagination($total, $pindex, $psize);
            include $this->template('house/index');
            exit;
        }

        include $this->template('house/position');
    }
    protected function fileUpload($file, $type) {
        global $_W;
        set_time_limit(0);
        $_W['uploadsetting'] = array();
        $_W['uploadsetting']['images']['folder'] = 'image';
        $_W['uploadsetting']['images']['extentions'] = array('jpg', 'png', 'gif');
        $_W['uploadsetting']['images']['limit'] = 50000;
        $result = array();
        $upload = file_upload($file, 'image');
        if (is_error($upload)) {
            message($upload['message'], '', 'ajax');
        }
        $result['url'] = $upload['url'];
        $result['error'] = 0;
        $result['filename'] = $upload['path'];
        return $result;
    }
    public function doMobileUploadImage() {
        global $_W;
        load()->func('file');
        if (empty($_FILES['file']['name'])) {
            $result['message'] = '请选择要上传的文件！';
            exit(json_encode($result));
        }

        if ($file = $this->fileUpload($_FILES['file'], 'image')) {
            if ($file['error']) {
                exit('0');
                //exit(json_encode($file));
            }
            $result['url'] = $_W['config']['upload']['attachdir'] . $file['filename'];
            $result['error'] = 0;
            $result['filename'] = $file['filename'];
            exit(json_encode($result));
        }
    }


    public function doMobileUpdateCount(){
        global $_GPC, $_W;
        $id = intval($_GPC['id']);
        $op = !empty($_GPC['op']) ? $_GPC['op'] : 'read';
        $result['state'] = 0 ;
        $result['msg'] = ' ';
        $detail = pdo_fetch("SELECT * FROM " . tablename('amouse_newflats') . " WHERE `id`=:id", array(':id'=>$id));
        if($detail){
            if($op=="read"){
                $data=array('readcount'=>$detail['readcount']+1);
                pdo_update('amouse_newflats', $data, array('id' => $id));
            }elseif($op=="like"){
                $data2=array('like'=>$detail['like']+1);
                pdo_update('amouse_newflats',$data2, array('id' => $id));
            }
            $result['msg'] = ' ';
            $result['state'] = 1 ;
        }
        message($result, '', 'ajax');
    }

    private function checkIsWeixin(){
        $user_agent= $_SERVER['HTTP_USER_AGENT'];
        if(strpos($user_agent, 'MicroMessenger') === false) {
            echo "本页面仅支持微信访问!非微信浏览器禁止浏览!";
            exit;
        }
    }
    
    public function get_sysset($weid=0) {
        global $_GPC, $_W;
        return pdo_fetch("SELECT * FROM ".tablename('amouse_sysset')." WHERE weid=:weid limit 1", array(':weid' => $weid));
    }

    //参数设置
    public function doWebSysset() {
        global $_W, $_GPC;
        $weid= $_W['uniacid'];
        $set= $this->get_sysset($weid);
        load()->func('tpl');
        if(checksubmit('submit')) {
            $data= array(
                'weid' => $weid,
                'guanzhuUrl'=>trim($_GPC['guanzhuUrl']),
                'copyright'=>trim($_GPC['copyright']),
                'broker'=>trim($_GPC['broker']),
                'jjrmobile'=>trim($_GPC['jjrmobile']),
                'newflat_images'=>trim($_GPC['newflat_images']),
                'appid_share'=>trim($_GPC['appid_share']),
                'appsecret_share'=>trim($_GPC['appsecret_share']),
                'isoauth' => trim($_GPC['isoauth']),
                'defcity' => trim($_GPC['defcity']),
                'nickname'=>trim($_GPC['nickname']),
                'openid' => trim($_GPC['openid']),
                'isshow' => trim($_GPC['isshow']),
                'isadjuest' => trim($_GPC['isadjuest'])
            );
            if($_GPC['isoauth']==0){
                $data['appid']=trim($_GPC['appid']) ;
                $data['appsecret']=trim($_GPC['appsecret']);
            }else{
                $data['appid']='' ;
                $data['appsecret']='';
            }
            if(!empty($set)) {
                pdo_update('amouse_sysset', $data, array('id' => $set['id']));
            } else {
                pdo_insert('amouse_sysset', $data);
            }
            message('更新参数设置成功！', 'refresh');
        }

        if(!isset($set['isoauth'])) {
            $set['isoauth']= 1;
            $set['isshow']= 1;
        }
        include $this->template('web/sysset');
    }
}