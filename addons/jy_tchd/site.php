<?php
defined('IN_IA') or exit('Access Denied');
define('MD', 'jy_tchd');
define('MD_ROOT', '../addons/jy_tchd');

include_once IA_ROOT . '/addons/' . MD . '/' . 'class/common.php';
@include_once IA_ROOT . '/addons/' . MD . '/' . 'class/frames.php';

class jy_tchdModuleSite extends Michael
{
    public function __call($name, $args=null)
    {
        $name=strtolower($name);
        if(preg_match('/^doweb(\w+)/',$name,$matches)){
            global $_W, $_GPC;
            $weid = $_W['uniacid'];
            checklogin();
            if( empty($_W['isajax']))
            {
                $user_perm=$this->perm_fetchall();

                if( file_exists(IA_ROOT . '/addons/' . MD . '/' . 'class/frames.php'))
                {
                    $frames_class = new frames(MD);
                    $frames = $frames_class->frames(MD,$user_perm);
                    $frames_class->_calc_current_frames($frames);
                }
            }
            $c_name=substr($name,5);
            $c_array=explode('.',$c_name);
            $c=$c_array[0];
            $a=empty($c_array[1])?'index':$c_array[1];

            if(file_exists(IA_ROOT . '/addons/' . MD . '/plugin/' . $c . '/web/' . $a . '.php')) {
                if(file_exists(IA_ROOT . '/addons/' . MD . '/plugin/' . $c . '/config.php')) {
                    $config = include IA_ROOT . '/addons/' . MD . '/plugin/' . $c . '/config.php';
                }
                @include_once IA_ROOT . '/addons/' . MD . '/plugin/' . $c . '/web/' . $a . '.php';
            }
            else if(file_exists(IA_ROOT . '/addons/' . MD . '/core/' . $c . '/web/' . $a . '.php'))
            {
                if(file_exists(IA_ROOT . '/addons/' . MD . '/core/' . $c . '/config.php')) {
                    $config = include IA_ROOT . '/addons/' . MD . '/core/' . $c . '/config.php';
                }
                @include_once IA_ROOT . '/addons/' . MD . '/core/' . $c . '/web/' . $a . '.php';
            }
            else
            {
                if(file_exists(IA_ROOT . '/addons/' . MD . '/inc/web/' . $c . '.php')) {
                    include IA_ROOT . '/addons/' . MD . '/inc/web/' . $c . '.php';
                }
                else{
                   // message("您访问的地址有误1！");
                }

            }

        }elseif(preg_match('/^domobile(\w+)/',$name,$matches)){
            global $_W, $_GPC;
            $weid = $_W['uniacid'];

            $c_name=substr($name,8);
            $c_array=explode('.',$c_name);
            $c=$c_array[0];
            $a=empty($c_array[1])?'index':$c_array[1];

            if(file_exists(IA_ROOT . '/addons/' . MD . '/plugin/' . $c . '/mobile/' . $a . '.php')) {
                if(file_exists(IA_ROOT . '/addons/' . MD . '/plugin/' . $c . '/config.php')) {
                    $config = include IA_ROOT . '/addons/' . MD . '/plugin/' . $c . '/config.php';
                }
                @include_once IA_ROOT . '/addons/' . MD . '/plugin/' . $c . '/mobile/' . $a . '.php';
            }
            else if(file_exists(IA_ROOT . '/addons/' . MD . '/core/' . $c . '/mobile/' . $a . '.php'))
            {
                if(file_exists(IA_ROOT . '/addons/' . MD . '/core/' . $c . '/config.php')) {
                    $config = include IA_ROOT . '/addons/' . MD . '/core/' . $c . '/config.php';
                }
                @include_once IA_ROOT . '/addons/' . MD . '/core/' . $c . '/mobile/' . $a . '.php';
            }
            else
            {
                if(!empty($c_array[1]))
                {
                    if(file_exists(IA_ROOT . '/addons/' . MD . '/inc/mobile/' . $c .'/'.$a. '.php')) {
                        include IA_ROOT . '/addons/' . MD . '/inc/mobile/' . $c .'/'.$a. '.php';
                    }
                    else{
                        message("您访问的地址有误！");
                    }
                }
                else{
                    if(file_exists(IA_ROOT . '/addons/' . MD . '/inc/mobile/' . $c . '.php')) {
                        include IA_ROOT . '/addons/' . MD . '/inc/mobile/' . $c . '.php';
                    }
                    else{
                        message("您访问的地址有误！");
                    }
                }

            }
        }
        else{
        }
    }
    public function payResult($params) {
        global $_W, $_GPC;

        $uniacid=$_W['uniacid'];
        $from_user=$params['user'];

        $priceid=substr($params['tid'], 10);

        $params['module'] = $this->module['name'];
        $pars = array();
        $pars[':uniacid'] = $_W['uniacid'];
        $pars[':module'] = $params['module'];
        $pars[':tid'] = $params['tid'];
        $sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `uniacid`=:uniacid AND `module`=:module AND `tid`=:tid';
        $log = pdo_fetch($sql, $pars);

        if($log['fee']!=$params['fee'])
        {
            echo '非法操作！发布失败!';
            exit;
        }

        $member = pdo_fetch("SELECT id FROM " . tablename(MD . '_member') . "  WHERE weid=" . $uniacid . " AND from_user='" . $from_user."'");
        $mid=$member['id'];
        if(!empty($mid)){
            $temp=pdo_fetch("SELECT id FROM ".tablename(MD.'_hd_cy')." WHERE weid=".$uniacid." AND priceid=".$priceid." AND mid=".$mid);
        }

        if ($params['result'] == 'success' && $params['from'] == 'notify') {

            if(empty($temp))
            {
                $hd=pdo_fetch("SELECT a.*,b.num,b.hdname FROM ".tablename(MD.'_hd_price')." as a left join ".tablename(MD.'_hd')." as b on a.hdid=b.id WHERE a.id=".$priceid);
                $hdid=$hd['hdid'];
                $data=array(
                    'weid'=>$uniacid,
                    'hdid'=>$hdid,
                    'priceid'=>$hd['id'],
                    'mid'=>$mid,
                    'status'=>1,
                    'price'=>$log['fee'],
                    'createtime'=>TIMESTAMP,
                );
                $data['plid']=$log['plid'];

                pdo_insert(MD."_hd_cy",$data);
                pdo_update(MD.'_hd_price',array('renshu'=>$hd['renshu']+1),array('id'=>$hd['id']));
                pdo_update(MD."_hd",array('num'=>($hd['num']+1)),array('id'=>$hdid));


                $text="<a href='".$_W['siteroot'].'app/'.substr($this->createMobileUrl('detail',array('id'=>$this->pencode($hdid))),2)."'>恭喜您！活动【".$hd['hdname']."】报名成功，点击查看报名详情！</a>";
                $text=urlencode($text);
                $access_token = WeAccount::token();
                $data = array(
                    "touser"=>$from_user,
                    "msgtype"=>"text",
                    "text"=>array("content"=>$text)
                );
                $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
                load()->func('communication');
                $response = ihttp_request($url, urldecode(json_encode($data)));

            }else{
                echo "<script>
					window.location.href = '".$_W['siteroot'].'app/' .substr($this->createMobileUrl('myplan'),2)."';
				</script>";
            }
        }

        if ($params['from'] == 'return') {

            if ($params['result'] == 'success') {
                echo "<script>

					window.location.href = '".$_W['siteroot'].'app/' .substr($this->createMobileUrl('myplan'),2)."';

				</script>";
            }
            else
            {
                message('支付失败！', $this->createMobileUrl('index'), 'success');
            }
        }
    }

}