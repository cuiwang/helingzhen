<?php
/**
 * 微城市模块微站定义
 *
 * @author 小义
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
define(EARTH_RADIUS, 6371); //地球半径，平均半径为6371km
define('MB_ROOT', IA_ROOT . '/addons/enjoy_city');
class Enjoy_cityModuleSite extends WeModuleSite {

// 	public function doMobileEntry() {
// 		//这个操作被定义用来呈现 功能封面
// 	}
// 	public function doWebBasic() {
// 		//这个操作被定义用来呈现 管理中心导航菜单
// 	}
// 	public function doWebAd() {
// 		//这个操作被定义用来呈现 管理中心导航菜单
// 	}
// 	public function doWebKind() {
// 		//这个操作被定义用来呈现 管理中心导航菜单
// 	}
// 	public function doWebFirm() {
// 		//这个操作被定义用来呈现 管理中心导航菜单
// 	}
// 	public function doWebContact() {
// 		//这个操作被定义用来呈现 管理中心导航菜单
// 	}
// 	public function doWebFans() {
// 		//这个操作被定义用来呈现 管理中心导航菜单
// 	}

    /**
     *计算某个经纬度的周围某段距离的正方形的四个点
     *
     * @param lng float 经度
     * @param lat float 纬度
     * @param distance float 该点所在圆的半径，该圆与此正方形内切，默认值为0.5千米
     * @return array 正方形的四个点的经纬度坐标
     */
    public $curversion = '';

    public function auth(){
        global $_W;
        $uniacid=$_W['uniacid'];

        $userinfo = mc_oauth_userinfo();
//         if($_W['fans']['uid']==0){
//             //查询此粉丝是否存在
//             $exist=pdo_fetchcolumn("select count(*) from ".tablename('mc_mapping_fans')." where uniacid=".$uniacid."
// 		        and openid='".$userinfo['openid']."'");
//             if($exist<1){
//                 $msg=array(
//                     'from'=>$userinfo['openid'],
//                     'time'=>0,
//                     'event'=>'unsubscribe',
//                     'nickname'=>$userinfo['nickname'],

//                 );
//                 $this->book($msg);
//             }

//         }
        $userinfo1 = $_W['fans'];

        $userlist=pdo_fetch("select * from ".tablename('enjoy_city_fans')." where uniacid=".$uniacid." and openid='".$userinfo['openid']."'");

        if(empty($userlist)){

            $data=array(
                'uniacid'=>$uniacid,
                'subscribe'=>$userinfo1['follow'],
                'openid'=>$userinfo['openid'],
                'nickname'=>$userinfo['nickname'],
                'gender'=>$userinfo['sex'],
                'city'=>$userinfo['city'],
                'state'=>$userinfo['province'],
                'country'=>$userinfo['country'],
                'subscribe_time'=>$userinfo1['followtime'],
                'avatar'=>$userinfo['avatar'],
                'huid'=>$userinfo1['uid'],
                'ip'=>CLIENT_IP
            );
            pdo_insert('enjoy_city_fans',$data);

            $userlist=pdo_fetch("select * from ".tablename('enjoy_city_fans')." where uniacid=".$uniacid." and openid='".$userinfo['openid']."'");

        }else {
            if ($userlist['huid']==0){
                $huid=$_W['fans']['uid'];
                pdo_update("enjoy_city_fans",array('huid'=>$huid),array('uniacid'=>$uniacid,'openid'=>$userinfo['openid']));
            }
            //判断关注状态是否改变
            if($userinfo1['follow']!=$userlist['subscribe']){
                pdo_update("enjoy_city_fans",array('subscribe'=>$userinfo1['follow']),array('uniacid'=>$uniacid,'openid'=>$userinfo['openid']));

            }
        }
        if($userlist['black']==1){
            message("抱歉，您没有参与资格",'','error');
            exit();
        }
        return $userlist;
    }
    //是否注册登录

    function islogin($username,$password,$uniacid){
        global $_W,$_GPC;
        session_start();
        //查询用户名密码是都合法
        $fans=pdo_fetch("select * from ".tablename('enjoy_city_fans')." where uniacid=".$uniacid." and (username='".$username."' or
    mobile='".$username."')");
// if(empty($fans['openid'])){
//  //获取openid
//     $userinfo = mc_oauth_userinfo();
//     //更新openid
//     pdo_update('enjoy_city_fans',array('openid'=>$userinfo['openid']),array('uid'=>$fans['uid']));
// }
        if($fans['password']==$password){
            //密码相同 说明登录成功
            //保存用户名 密码
            $_SESSION['city']['username']=empty($fans['username'])?$fans['mobile']:$fans['username'];
            $_SESSION['city']['password']=$fans['password'];
            $_SESSION['city']['uid']=$fans['uid'];

            return $fans['uid'];
            //跳转个人中心
           // header("location:".$this->createMobileUrl('personal',array('uid'=>$fans['uid']))."");
        }else {
          //  message('用户名或密码出错',referer(),error);
        return 0;
        }
        
        
        
    }
    
    //登录
//     function doMobilelogined(){
//         global $_W,$_GPC;
//         $uniacid=$_W['uniacid'];
//         session_start();
//         $username=trim($_GPC['username']);
//         $password=trim($_GPC['password']);
//         $uid=$this->islogin($username,$password,$uniacid);
        
//         if($uid>0){
//             header("location:".$this->createMobileUrl('personal',array('uid'=>$uid))."");
//         }else{
//             message('用户名或密码出错',referer(),error);
//         }
        
//     }
    function getServerIP(){
        return gethostbyname($_SERVER["SERVER_NAME"]);
    }
    //退出登录
    function doMobileExit(){
        global $_W,$_GPC;
        session_start();
        //清除session
        unset($_SESSION['city']);
        header("location:".$this->createMobileUrl('login'));
        
    }
    
    function doMobileimgload(){
        global $_W,$_GPC;
        $filename=TIMESTAMP.rand(0, 100).".jpg";///要生成的图片名字
        $type=$_GPC['type'];
        $upload=$_GPC['upload'];
        load()->func('communication');
        load()->func('file');
        $setting = $_W['setting']['upload']['image'];
        $uniacid=$_W['uniacid'];
        $setting['folder'] = "images/{$uniacid}";
        $setting['folder'] .= '/'.date('Y/m/');
        //生成图片
       $imgDir =$setting['folder'];
       $filePath = $imgDir.$filename;
//        $imgDir = '../addons/enjoy_city/public/upload/';
//       echo $imgDir;
//       exit();

        
        //处理base64文本，用正则把第一个base64,之前的部分砍掉
        preg_match('/(?<=base64,)[\S|\s]+/',$upload,$streamForW);
       // if (file_put_contents($streamFilename,base64_decode($streamForW[0]))===false) Common::exitWithError("文件写入失败!","");//这是我自己的一个静态类，输出错误信息的，你可以换成你的程序
        $jpg = $streamForW[0];//得到post过来的二进制原始数据
        if(empty($jpg))
        {
            echo 'nostream';
            exit();
        }
        $fullname = ATTACHMENT_ROOT . '/' . $filePath;
        $jpg  = base64_decode($jpg);
        //$file = fopen("./".$imgDir.$filename,"w");//打开文件准备写入
        $file = fopen($fullname,"w");//打开文件准备写入
        fwrite($file,$jpg);//写入
        fclose($file);//关闭
        
        
        if (!empty($_W['setting']['remote']['type'])) {
            $remotestatus = file_remote_upload($filePath);
            if (is_error($remotestatus)) {
                $info['error'] = 1;
                file_delete($filePath);
                // die(json_encode($result));
            } else {
                file_delete($filePath);
                $info['url'] = tomedia($filePath);
                $info['error'] = 0;
            }
        }else {
            $info['url'] = $_W['siteroot'].'attachment/'.$filePath;
            $info['error'] = 0;
        }
//         //图片是否存在
//         if(!file_exists($fullname))
//         {
//             echo 'createFail';
//             exit();
//         }

        
       $res=array(
           'error'=>0,
           'data'=>'',
           'extend'=>array('type'=>$type,'url'=>tomedia($filePath)),
            
       );
     echo  json_encode($res);
       exit();
    }
    
    
    function doWebshopimg(){
        global $_W,$_GPC;
        $type=$_GPC['type'];
        $fid=$_GPC['fid'];
        $field=$_GPC['field'];
        
        $title=$_GPC['text'];
        $uniacid=$_W['uniacid'];
       // var_dump($_GPC);
        if(!empty($_FILES['file']['name'])){
        
                    //$resimg=$this->ImgUpload($uniacid,$type,$fid,$title);
            $info=$this->newimgload($_FILES);
	
            if($info[error]=='1'){
                $error=1;
            }else{
                $data=array(
                    'uniacid'=>$uniacid,
                    'fid'=>$fid,
                    'imgurl'=>$info['url'],
                    'title'=>$title,
                    'createtime'=>TIMESTAMP
                );
                $res=pdo_insert('enjoy_city_fimg',$data);
                if($res>0){
                $error=0;
                $url=$info['url'];
                }else{
                    $error=1;
                }
            }
        }
        
        
        $res=array(
            'error'=>0,
            'data'=>$field,
            'extend'=>array('type'=>$type,'url'=>$url),
        
        );
        echo  '<html><body>'.json_encode($res).'</body></html>';
        exit();
        
    }

    function newimgload($FILES){
        global $_GPC,$_W;
        //接收传来的参数
        load()->func('communication');
        load()->func('file');
        $setting = $_W['setting']['upload']['image'];
        $uniacid=$_W['uniacid'];
        $setting['folder'] = "images/{$uniacid}";
        $setting['folder'] .= '/'.date('Y/m/');



        $ext = pathinfo($FILES['file']['name'], PATHINFO_EXTENSION);
        $ext = strtolower($ext);
        $size = intval($FILES['file']['size']);
        $originname = $FILES['file']['name'];
        $filename = file_random_name(ATTACHMENT_ROOT .'/'. $setting['folder'], $ext);
        $fullname = ATTACHMENT_ROOT . '/'.$setting['folder']. $filename;
        require_once MB_ROOT . '/controller/image.php';

        $img = new Image();
        $img->load($FILES['file']['tmp_name'])
        ->width(300)	//设置生成图片的宽度，高度将按照宽度等比例缩放
        ->fixed_given_size(true)	//生成的图片是否以给定的宽度和高度为准
        ->keep_ratio(true)		//是否保持原图片的原比例
        ->quality(100)	//设置生成图片的质量 0-100，如果生成的图片格式为png格式，数字越大，压缩越大，如果是其他格式，如jpg，gif，数组越小，压缩越大
        ->save($fullname);	//保存生成图片的路径
        //return $fullname;
        $pathname=$setting['folder'] . $filename;
       // $file = file_upload($FILES['file'], 'image', $setting['folder'] . $filename);
       // $pathname = $file['path'];
        $fullname = ATTACHMENT_ROOT . '/' . $pathname;

        if (!empty($_W['setting']['remote']['type'])) {
            $remotestatus = file_remote_upload($pathname);
            if (is_error($remotestatus)) {
                $info['error'] = 1;
                file_delete($pathname);
               // die(json_encode($result));
            } else {
                file_delete($pathname);
                $info['url'] = tomedia($pathname);
                $info['error'] = 0;
            }
        }else {
            $info['url'] = $_W['siteroot'].'attachment/'.$pathname;
            $info['error'] = 0;
        }
            return $info;
    }
   
    function doMobileshopimg(){
        global $_W,$_GPC;
        $type=$_GPC['type'];
        $fid=$_GPC['fid'];
        $title=$_GPC['text'];
        $uniacid=$_W['uniacid'];
       // var_dump($_GPC);

        if(!empty($_FILES['file']['name'])){

            //$resimg=$this->ImgUpload($uniacid,$type,$fid,$title);
        $info=$this->newimgload($_FILES);
// 	var_dump($info);
// 	exit();
            if($info[error]=='1'){
                $error=1;
            }else{
                $data=array(
                    'uniacid'=>$uniacid,
                    'fid'=>$fid,
                    'imgurl'=>$info['url'],
                    'title'=>$title,
                    'createtime'=>TIMESTAMP
                );
                $res=pdo_insert('enjoy_city_fimg',$data);
                if($res>0){
                $error=0;
                $url=$info['url'];
                }else{
                    $error=1;
                }
            }
        }


        $res=array(
            'error'=>0,
            'data'=>$_FILES,
            'extend'=>array('type'=>$type,'url'=>$url),

        );
        echo  '<html><body>'.json_encode($res).'</body></html>';
        exit();

    }
    
    
    
    //删除图片

    function doWebdelimg(){
        global $_GPC,$_W;
        //接收传来的参数
        $id=intval($_GPC['id']);
        $uniacid=$_W['uniacid'];
        if($id>0){
            //图片存在可删除
            pdo_delete('enjoy_city_fimg', array('id' => $id,'uniacid'=>$uniacid));
          
           $res=array(
               'error'=>0,
               'data'=>'',
               'extend'=>array(),
           
           );
        }else{
            //图片不存在
             $res=array(
               'error'=>1,
               'data'=>'',
               'extend'=>array(),
           
           );
        }
        echo json_encode($res);
       
        exit();
    }
    
    //删除图片
    function doMobiledelimg(){
        global $_GPC,$_W;
        //接收传来的参数
        $id=intval($_GPC['id']);
        $uniacid=$_W['uniacid'];
        if($id>0){
            //图片存在可删除
            pdo_delete('enjoy_city_fimg', array('id' => $id,'uniacid'=>$uniacid));
          
           $res=array(
               'error'=>0,
               'data'=>'',
               'extend'=>array(),
           
           );
        }else{
            //图片不存在
             $res=array(
               'error'=>1,
               'data'=>'',
               'extend'=>array(),
           
           );
        }
        echo json_encode($res);
       
        exit();
    }
    
    
    public function ImgUpload($uniacid,$type,$fid,$title){
        global $_W,$_GPC;
    //    require_once MB_ROOT . '/controller/image.php';
        
        if(empty($_FILES['file'])){
            //message('上传的图片不能为空哦...');
            return -1;
        }else{
            if ($_FILES['file']['error'] != 0) {
               // message('上传失败,请稍后再试..');
                return -1;
            }
            $size = $_FILES['file']['size'];
//             if($size>2097152){
//               //  message('上传的图片大小不能超过2M..');
//                 return -1;
//             }
            $_W['uploadsetting'] = array();
            $_W['uploadsetting']['image']['folder'] = '/images/' . $_W['uniacid'];
            $_W['uploadsetting']['image']['extentions'] = $_W['config']['upload']['image']['extentions'];
            $_W['uploadsetting']['image']['limit'] = $_W['config']['upload']['image']['limit'];
         
            load()->func('file');
            $file = $this->file_uploadcity($_FILES['file'], 'image');
    
            if (is_error($file)) {
                message($file['message']);
            }
            $result['url'] = $file['url'];
            $result['error'] = 0;
            $result['filename'] = $file['path'];
            $result['url'] = $_W['attachurl'].$result['filename'];
            
//             $img = new Image();
//             $img->load($result['url'])
//             ->width(640)	//设置生成图片的宽度，高度将按照宽度等比例缩放
//             ->fixed_given_size(true)	//生成的图片是否以给定的宽度和高度为准
//             ->keep_ratio(true)		//是否保持原图片的原比例
//             ->quality(50)	//设置生成图片的质量 0-100，如果生成的图片格式为png格式，数字越大，压缩越大，如果是其他格式，如jpg，gif，数组越小，压缩越大
//             ->save('../attachment/'.$result['filename']);	//保存生成图片的路径

          //  $res=pdo_query("update ".tablename('djrc_basic')." set avatar='".$result['filename']."' where openid='".$_W['openid']."' and uniacid=".$_W['uniacid']."");
           $data=array(
             'uniacid'=>$uniacid,
               'fid'=>$fid,
               'imgurl'=>$result['url'],
               'title'=>$title,
               'createtime'=>TIMESTAMP
           );
           $res=pdo_insert('enjoy_city_fimg',$data);
            if($res==0){
               // message('系统正忙..请喝杯茶等一等好吗..');
                return -1;
            }else{
                return $result['url'];
            }
        }
    }
    
    function file_uploadcity($file, $type = 'image', $name = '') {
        $harmtype = array('asp', 'php', 'jsp', 'js', 'css', 'php3', 'php4', 'php5', 'ashx', 'aspx', 'exe', 'cgi');
        if (empty($file)) {
            return error(-1, '没有上传内容');
        }
        if (!in_array($type, array('image', 'thumb', 'voice', 'video', 'audio'))) {
            return error(-2, '未知的上传类型');
        }

        global $_W;
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $ext = strtolower($ext);
        $setting = $_W['setting']['upload'][$type];
        if (!in_array(strtolower($ext), $setting['extentions']) || in_array(strtolower($ext), $harmtype)) {
            return error(-3, '不允许上传此类文件');
        }
        if (!empty($setting['limit']) && $setting['limit'] * 1024 < filesize($file['tmp_name'])) {
            return error(-4, "上传的文件超过大小限制，请上传小于 {$setting['limit']}k 的文件");
        }
        $result = array();
        if (empty($name) || $name == 'auto') {
            $uniacid = intval($_W['uniacid']);
            $path = "{$type}s/{$uniacid}/" . date('Y/m/');
            mkdirs(ATTACHMENT_ROOT . '/' . $path);
            $filename = file_random_name(ATTACHMENT_ROOT . '/' . $path, $ext);
    
            $result['path'] = $path . $filename;
        } else {
            mkdirs(dirname(ATTACHMENT_ROOT . '/' . $name));
            if (!strexists($name, $ext)) {
                $name .= '.' . $ext;
                
            }
            $result['path'] = $name;
        }


//         if (!file_move($file['tmp_name'], ATTACHMENT_ROOT . '/' . $result['path'])) {
//             return error(-1, '保存上传文件失败');
//         }
        require_once MB_ROOT . '/controller/image.php';
        
        $img = new Image();
        
        $img->load($file['tmp_name'])
        ->width(640)	//设置生成图片的宽度，高度将按照宽度等比例缩放
        ->fixed_given_size(true)	//生成的图片是否以给定的宽度和高度为准
        ->keep_ratio(true)		//是否保持原图片的原比例
        ->quality(50)	//设置生成图片的质量 0-100，如果生成的图片格式为png格式，数字越大，压缩越大，如果是其他格式，如jpg，gif，数组越小，压缩越大
        ->save('../attachment/'.$result['path']);	//保存生成图片的路径

        $result['success'] = true;
        return $result;
    }
    
    function doMobilebd(){
        global $_W,$_GPC;
        $uniacid=$_W['uniacid'];
       $fid=intval($_GPC['fid']);
      $firm= pdo_fetch("select title,location_x,location_y,address,district from ".tablename('enjoy_city_firm')." where 
           uniacid=".$uniacid." and id=".$fid);
       
        
        
        
        
        include $this->template('bd');
    }
    
    function shareth($str,$r)//删除空格
    {
        $qian=array("#firm#");$hou=array($r);
    
        return str_replace($qian,$hou,$str);
    }
    function shareth1($str,$r)//删除空格
    {
        $qian=array("#firmlogo#");$hou=array($r);
    
        return str_replace($qian,$hou,$str);
    }

    function doMobilemycontact(){
        global $_W,$_GPC;

        session_start();
        $uid=$_SESSION['city']['uid'];
        if(empty($uid)){
            //清除session
            unset($_SESSION['city']);
            //返回登录页面
            header("location:".$this->createMobileUrl('login'));
        }

        $uniacid=$_W['uniacid'];
        $active='user';
        //先取出活动详情
        $act=pdo_fetch("select * from ".tablename('enjoy_city_reply')." where uniacid=".$uniacid);
        $cid=intval($_GPC['cid']);
      //  $fid=intval($_GPC['fid']);
        if(empty($uid)){
            header("location:".$this->createMobileUrl('login'));
        }
        if(checksubmit('submit')){
//         var_dump($_GPC);
//         exit();
        //插入数据库
            $data = array(
                'uniacid' => $_W['uniacid'],
                'kind'=>0,
                'ischeck'=>0,
                'cweixin'=>$_GPC['wei_num'],
                'cgender'=>$_GPC['wei_sex'],
                'cnickname'=>$_GPC['wei_name'],
                'descript'=>$_GPC['wei_intro'],
                'cavatar'=>$_GPC['wei_avatar'],
                'cewm'=>$_GPC['wei_ewm'],
                'uid'=>$uid,
                'addtime'=>TIMESTAMP

            );

            if($cid>0){
                //更新
                pdo_update('enjoy_city_contact', $data,array('id'=>$cid));
                header("location:".$this->createMobileUrl('clist'));
            }else {
                $res=pdo_insert('enjoy_city_contact', $data);
            }

            if($res>0){
                header("location:".$this->createMobileUrl('clist'));
            }


        }

        //编辑

        if($cid>0){
            $firm=pdo_fetch("select * from ".tablename('enjoy_city_contact')." where uniacid=".$uniacid." and
        id=".$cid);
        }

        //分享
        $sharelink =  $_W['siteroot'] . "app/".$this->createMobileUrl('entry');
        $sharetitle=$act['title']."无所不查，邀您入驻";
        $sharecontent="一个神奇的网站";

        include $this->template('mycontact');


    }
    
    //登记人脉

    function doMobileclist(){
        global $_W,$_GPC;

        session_start();
        $uid=$_SESSION['city']['uid'];
        if(empty($uid)){
            //清除session
            unset($_SESSION['city']);
            //返回登录页面
            header("location:".$this->createMobileUrl('login'));
        }

        $uniacid=$_W['uniacid'];
        $active='user';
        //先取出活动详情
        $act=pdo_fetch("select * from ".tablename('enjoy_city_reply')." where uniacid=".$uniacid);

        //查出我的人脉
        $mycontact=pdo_fetch("select * from ".tablename('enjoy_city_contact')." where uniacid=".$uniacid."
            and uid=".$uid);

        //查出我创建的老板人脉
        $contacts=pdo_fetchall("select * from ".tablename('enjoy_city_firm')." where uniacid=".$uniacid."
            and uid=".$uid);


        //分享
        $sharelink =  $_W['siteroot'] . "app/".$this->createMobileUrl('entry');
        $sharetitle=$act['title']."无所不查，邀您入驻";
        $sharecontent="一个神奇的网站";


        include $this->template('clist');

    }
    
    function doMobilecdetail(){
        global $_W,$_GPC;

        session_start();
        $uid=$_SESSION['city']['uid'];
        if(empty($uid)){
            //清除session
            unset($_SESSION['city']);
            //返回登录页面
            header("location:".$this->createMobileUrl('login'));
        }

        $uniacid=$_W['uniacid'];
        $active='user';
        //先取出活动详情
        $act=pdo_fetch("select * from ".tablename('enjoy_city_reply')." where uniacid=".$uniacid);
        $fid=intval($_GPC['fid']);
        //  $fid=intval($_GPC['fid']);
        if(empty($uid)){
            header("location:".$this->createMobileUrl('login'));
        }
        if(checksubmit('submit')){
            //         var_dump($_GPC);
            //         exit();
            //插入数据库
            $data = array(
                'uniacid' => $_W['uniacid'],
                'ischeck'=>0,
                'wei_num'=>$_GPC['wei_num'],
                'wei_sex'=>$_GPC['wei_sex'],
                'wei_name'=>$_GPC['wei_name'],
                'wei_intro'=>$_GPC['wei_intro'],
                'wei_avatar'=>$_GPC['wei_avatar'],
                'wei_ewm'=>$_GPC['wei_ewm'],
                'uid'=>$uid

            );

            if($fid>0){
                //更新
                pdo_update('enjoy_city_firm', $data,array('id'=>$fid));
            }


                header("location:".$this->createMobileUrl('clist'));



        }

        //编辑

        if($fid>0){
            $firm=pdo_fetch("select * from ".tablename('enjoy_city_firm')." where uniacid=".$uniacid." and
        id=".$fid);
        }

        //分享
        $sharelink =  $_W['siteroot'] . "app/".$this->createMobileUrl('entry');
        $sharetitle=$act['title']."无所不查，邀您入驻";
        $sharecontent="一个神奇的网站";

        include $this->template('cdetail');


    }
    
    
    //登记人脉

    function doMobileflist(){
        global $_W,$_GPC;

//         session_start();
//         $uid=$_SESSION['city']['uid'];

        $uniacid=$_W['uniacid'];
        $active='friend';
        $op=empty($_GPC['op'])?'sflist':trim($_GPC['op']);
        //先取出活动详情
        $act=pdo_fetch("select * from ".tablename('enjoy_city_reply')." where uniacid=".$uniacid);
        //老板
        $sflist=pdo_fetchall("select * from ".tablename('enjoy_city_firm')." where uniacid=".$uniacid."
            and ischeck=1 and wei_ewm!=''");
        if($op=='gflist'){
            //个人
            $flist=pdo_fetchall("select * from ".tablename('enjoy_city_contact')." where uniacid=".$uniacid."
            and ischeck=1 and cewm!=''");
        }elseif ($op=='mflist'){
            //男的
            $flist=pdo_fetchall("select * from (select cgender,cavatar,cewm,cweixin,cnickname,
            descript,ischeck,addtime,uid,uniacid from ".tablename('enjoy_city_contact')." union all select
            wei_sex,wei_avatar,wei_ewm,wei_num,wei_name,wei_intro,ischeck,createtime,uid,uniacid from
            ".tablename('enjoy_city_firm').") tb where uniacid=".$uniacid." and ischeck=1 and
            cgender=1 and cewm!='' order by addtime desc");
        }elseif ($op=='wflist'){

            //女的
            $flist=pdo_fetchall("select * from (select cgender,cavatar,cewm,cweixin,cnickname,
            descript,ischeck,addtime,uid,uniacid from ".tablename('enjoy_city_contact')." union all select
            wei_sex,wei_avatar,wei_ewm,wei_num,wei_name,wei_intro,ischeck,createtime,uid,uniacid from
            ".tablename('enjoy_city_firm').") tb where uniacid=".$uniacid." and ischeck=1 and
            cgender=0 and cewm!='' order by addtime desc");
        }elseif ($op=='rflist'){

            //排行
            $flist=pdo_fetchall("select * from (select hot,cgender,cavatar,cewm,cweixin,cnickname,
            descript,ischeck,addtime,uid,uniacid from ".tablename('enjoy_city_contact')." union all select hot,
            wei_sex,wei_avatar,wei_ewm,wei_num,wei_name,wei_intro,ischeck,createtime,uid,uniacid from
            ".tablename('enjoy_city_firm').") tb where uniacid=".$uniacid." and ischeck=1 and cewm!='' order by hot desc");

        }
        session_start();
        $uid=$_SESSION['city']['uid'];
        if(!empty($uid)){
            //搜索cid
           $cid= pdo_fetchcolumn("select id from ".tablename('enjoy_city_contact')." where uniacid=".$uniacid." and uid=".$uid);
            $arr="&cid=".$cid;
        }else{
            $arr="";
        }


        //分享
        $sharelink =  $_W['siteroot'] . "app/".$this->createMobileUrl('flist');
        $sharetitle=$act['mshare_title'];
        $sharecontent=$act['mshare_content'];
        $shareicon=$act['mshare_icon'];



        include $this->template('flist');
    }
    
    //微友圈

    function doMobilesearch(){
        global $_W,$_GPC;

        $uniacid=$_W['uniacid'];
        $active='entry';
        $search=trim($_GPC['search']);
        //先取出活动详情
        $act=pdo_fetch("select * from ".tablename('enjoy_city_reply')." where uniacid=".$uniacid);
        $jointel=$this->jointh($act['jointel'],$act['tel']);
        $op=empty($_GPC['op'])?'firm':trim($_GPC['op']);
        if($op=='firm'){
            //查询关键字
            $firm=pdo_fetchall("select * from ".tablename('enjoy_city_firm')." where uniacid=".$uniacid."
            and concat(title,intro,address,fax) like '%".$search."%' and ischeck=1");
//             echo "select * from ".tablename('enjoy_city_firm')." where uniacid=".$uniacid."
//             and concat('title','intro','address') like '%".$search."%' and ischeck=1";
//             exit();
        }elseif ($op=='flist'){
//             $flist=pdo_fetchall("select * from ".tablename('enjoy_city_firm')." where uniacid=".$uniacid."
//             and title like '%".$search."%'");
            $flist=pdo_fetchall("select * from (select hot,cgender,cavatar,cewm,cweixin,cnickname,
            descript,ischeck,addtime,uid,uniacid from ".tablename('enjoy_city_contact')." union all select hot,
            wei_sex,wei_avatar,wei_ewm,wei_num,wei_name,wei_intro,ischeck,createtime,uid,uniacid from
            ".tablename('enjoy_city_firm').") tb where uniacid=".$uniacid." and ischeck=1
                and concat(cnickname,descript) like '%".$search."%' order by hot desc");

        }


//         var_dump($flist);
//         exit();



        include $this->template('search');






    }
    
    function jointh($str,$r)//删除空格
    {
        $qian=array("#tel#");$hou=array($r);

        return str_replace($qian,$hou,$str);
    }
    
    //忘记密码

    function doMobileforget(){
        global $_W,$_GPC;
        $active='user';
        $uniacid=$_W['uniacid'];
        //先取出活动详情
        $act=pdo_fetch("select * from ".tablename('enjoy_city_reply')." where uniacid=".$uniacid);
        
        if(!empty($_GPC['forget'])){
            $username=trim($_GPC['username']);
            $mobile=trim($_GPC['mobile']);

                        $fans=pdo_fetch("select * from ".tablename('enjoy_city_fans')." where uniacid=".$uniacid." and mobile='".$mobile."'
        and username='".$username."'");
                        if(!empty($fans)){
                      //跳转重置密码
                        $_SESSION['city']['username']=$username;
                        $_SESSION['city']['password']=$fans['password'];
                        $_SESSION['city']['uid']=$fans['uid'];
                        header("location:".$this->createMobileUrl('rpwd'));
                    }else{
                      $this->newmessage("用户名或手机号码错误",$this->createMobileUrl('forget'));
//                         message("用户名或手机号码错误",$this->createMobileUrl('forget'),'error');
                    }        
        }

        //分享
        $sharelink =  $_W['siteroot'] . "app/".$this->createMobileUrl('entry');
        $sharetitle=$act['mshare_title'];
        $sharecontent=$act['mshare_content'];
        $shareicon=$act['mshare_icon'];
        
        include $this->template('forget');
        
        
        
    }
    
public function newmessage($info,$url_teb){
    $error_info=$info;
    $url=$url_teb;
    include $this->template('cue');
    exit();
}
    
//支付参数

    function doMobilerpwd(){
        global $_W,$_GPC;
        $active='user';
        $uniacid=$_W['uniacid'];
        //先取出活动详情
        $act=pdo_fetch("select * from ".tablename('enjoy_city_reply')." where uniacid=".$uniacid);

        if(!empty($_GPC['rpwd'])){
            session_start();
            $uid=$_SESSION['city']['uid'];
            $password=trim($_GPC['password']);
            $password2=trim($_GPC['password2']);
            //先判断旧密码是否正确
            $fans=pdo_fetch("select * from ".tablename('enjoy_city_fans')." where uniacid=".$uniacid." and uid=".$uid);
            if($password==$password2){
                //检查此手机号码是否已注册
//                 $resm= pdo_fetchcolumn("select count(*) from ".tablename('enjoy_city_fans')." where uniacid=".$uniacid."
//         and mobile='".$mobile."'");
//                 if($resm>0){
//                     message("该手机号码已被占用",$this->createMobileUrl('personal'),'error');
//                 }else{
                    //设置成功
                    $res=pdo_update('enjoy_city_fans',array('password'=>$password),array('uniacid'=>$uniacid,'uid'=>$uid));
                    if($res>0){
                        $_SESSION['city']['password']=$password;
                        //修改成功
                        header("location:".$this->createMobileUrl('personal'));
                    }
             //   }
            }else{
               $this->newmessage("两次输入密码不一致",$this->createMobileUrl('rpwd'));
//                 message("两次输入密码不一致",$this->createMobileUrl('rpwd'),'error');
            }




        }








        //分享
        $sharelink =  $_W['siteroot'] . "app/".$this->createMobileUrl('entry');
        $sharetitle=$act['mshare_title'];
        $sharecontent=$act['mshare_content'];
        $shareicon=$act['mshare_icon'];



        include $this->template('rpwd');
    }
    
public function doMobilepaylog(){
	global $_W,$_GPC;
	$uniacid=$_W['uniacid'];

	//生成订单
	$uid=$_GPC['uid'];
	$fid=intval($_GPC['fid']);
	$title=$_GPC['uid'];
	$province=$_GPC['province'];
	$city=$_GPC['city'];
	$district=$_GPC['district'];
	$address=$_GPC['address'];
	$location_x=$_GPC['location_x'];
	$location_y=$_GPC['location_y'];
	$intro=$_GPC['intro'];
	$tel=$_GPC['tel'];
	$sid=$_GPC['sid'];
	$parentid=$_GPC['parentid'];
	$childid=$_GPC['childid'];
	$wei_num=$_GPC['wei_num'];
	$wei_sex=$_GPC['wei_sex'];
	$wei_name=$_GPC['wei_name'];
	$wei_intro=$_GPC['wei_intro'];
	$icon=$_GPC['icon'];
	$img=$_GPC['img'];
	$wei_avatar=$_GPC['wei_avatar'];
	$wei_ewm=$_GPC['wei_ewm'];
	$s_name=$_GPC['s_name'];
	$firmurl=$_GPC['firmurl'];
	$custom=$_GPC['custom'];
	$s_name=$_GPC['s_name'];



	//先取出活动详情
	$act=pdo_fetch("select * from ".tablename('enjoy_city_reply')." where uniacid=".$uniacid);

	if($fid!=0){
		//说明是老商户

	}else{
		//说明是新商户

		//创建新商户
    $data = array(
        'uniacid' => $_W['uniacid'],
        'title'=>$_GPC['title'],
        'stime'=>date('Y-m-d H:i:s',TIMESTAMP),
        'etime'=>date('Y-m-d H:i:s',(TIMESTAMP+365*24*60*60)),
        'province'=>$_GPC['province'],
        'city'=>$_GPC['city'],
        'district'=>$district,
        'address'=>$_GPC['address'],
        'location_x'=>$location_x,
        'location_y'=>$location_y,
        'intro'=>$_GPC['intro'],
        'tel'=>$_GPC['tel'],
        'ischeck'=>$_GPC['ischeck'],
        'ismoney'=>$_GPC['ismoney'],
        'ispay'=>0,
        'paymoney'=>$act['fee'],
        'sid'=>$_GPC['sid'],
        'parentid'=>$parentid,
        'childid'=>$childid,
        'wei_num'=>$_GPC['wei_num'],
        'wei_sex'=>$_GPC['wei_sex'],
        'wei_name'=>$_GPC['wei_name'],
        'wei_intro'=>$_GPC['wei_intro'],
        'icon'=>$icon,
        'img'=>$img,
        'wei_avatar'=>$wei_avatar,
        'wei_ewm'=>$wei_ewm,
        'custom'=>$custom,
        'firmurl'=>$firmurl,
        's_name'=>$_GPC['s_name'],
        'uid'=>$uid,
        'ispay'=>-1,
        'cflag'=>0,
        'createtime'=>TIMESTAMP
    );
		pdo_insert('enjoy_city_firm', $data);
		$fid = pdo_insertid();

	}
	//$openid=$user['openid'];
	//先查询是否存在订单
	$tid=pdo_fetchcolumn("select id from ".tablename('enjoy_city_firm')." where uniacid=".$uniacid." and uid=".$uid." and id=".$fid);
	//查询支付的钱
	$fee=$act['fee'];


	// 		echo "select id from ".tablename('enjoy_guess_paylog')." where uniacid=".$uniacid." and uid=".$uid." and rid=".$rid;
	// 		exit();
    $fans=pdo_fetch("select * from ".tablename('enjoy_city_fans')." where uniacid=".$uniacid." and 
        uid=".$uid);
	$params=array();
	$params['tid'] = $tid;
	$params['user'] = $fans['openid'];
	$params['fee'] = $fee;
	$params['title'] = '支付'.$fee.'元给'.$act[title].'平台';
	$params['ordersn'] = '12345789';
	$params['virtual'] = 1;

	$params['module'] = $this->module['name'];
	$pars = array();
	$pars[':uniacid'] = $_W['uniacid'];
	$pars[':module'] = $params['module'];
	$pars[':tid'] = $params['tid'];

	if($params['fee'] <= 0) {
		$pars['from'] = 'return';
		$pars['result'] = 'success';
		$pars['type'] = 'alipay';
		$pars['tid'] = $params['tid'];
		$site = WeUtility::createModuleSite($pars[':module']);
		$method = 'payResult';
		if (method_exists($site, $method)) {
			exit($site->$method($pars));
		}
	}

	$log = pdo_get('core_paylog', array('uniacid' => $_W['uniacid'], 'module' => $params['module'], 'tid' => $params['tid']));
	//在pay方法中，要检测是否已经生成了paylog订单记录，如果没有需要插入一条订单数据
	//未调用系统pay方法的，可以将此代码放至自己的pay方法中，进行漏洞修复
	if (empty($log)) {
		$log = array(
				'uniacid' => $_W['uniacid'],
				'acid' => $_W['acid'],
				'openid' => $fans['openid'],
// 				'openid' => $_W['member']['uid'],
				'module' => $this->module['name'], //模块名称，请保证$this可用
				'tid' => $params['tid'],
				'fee' => $params['fee'],
				'card_fee' => $params['fee'],
				'status' => '0',
				'is_usecard' => '0',
		);
		pdo_insert('core_paylog', $log);
	}

	if(empty($fans['openid'])){
	    $message='未获取您的openid请使用微信登录';
	}
	$sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `uniacid`=:uniacid AND `module`=:module AND `tid`=:tid';
	$log = pdo_fetch($sql, $pars);
	if(!empty($log) && $log['status'] == '1') {
		$message='这个订单已经支付成功, 不需要重复支付.';
	}

	$setting = uni_setting($_W['uniacid'], array('payment', 'creditbehaviors'));
	if(!is_array($setting['payment'])) {
		$message='没有有效的支付方式, 请联系网站管理员.';
	}
	$pay = $setting['payment'];
	if (!empty($pay['credit']['switch'])) {
		$credtis = mc_credit_fetch($_W['member']['uid']);
	}
	$you = 0;

	$res['error']=$message;
	$res['params']=base64_encode(json_encode($params));

	echo json_encode($res);
}

public function payResult($params) {
    global $_W;

    //$fee = intval($params['fee']);
    $data = array('status' => $params['result'] == 'success' ? 1 : 0);
    $paytype = array('credit' => '1', 'wechat' => '2', 'alipay' => '2', 'delivery' => '3');


$firm=pdo_fetch("select a.*,b.openid from ".tablename('enjoy_city_firm')." as a left join ".tablename('enjoy_city_seller')."
    as b on a.sid=b.id where a.uniacid=".$params['uniacid']." and a.id=".$params['tid']);
    if ($params['result'] == 'success' && $params['from'] == 'notify') {
        if ($params['type'] == 'alipay') {
            if (empty($params['transaction_id'])) {
                return false;
            }
        }
        if ($params['type'] == 'wechat') {
            if (empty($params['tag']['transaction_id'])) {
                return false;
            }
        }
        //先取出活动详情
        $act=pdo_fetch("select * from ".tablename('enjoy_city_reply')." where uniacid=".$params['uniacid']);
        if($act['fee']==$params['card_fee']){
            $paymessage="已支付";
            $message="审核通过";
        //更新ismoney=1,ischeck=1
        pdo_update('enjoy_city_firm',array('ismoney'=>1,'ischeck'=>1,'ispay'=>0),array('uniacid'=>$params['uniacid'],'id'=>$params['tid']));
        //支付成功，发模板消息通知

        }

        require_once MB_ROOT . '/controller/weixin.class.php';
        $url=$this->str_murl($this->createMobileUrl("firm",array('fid'=>$params['tid'])));
        $config = $this->module['config']['api'];
        $now=date('Y-m-d H:i:s',TIMESTAMP);
        //echo $xxquan;
        $template = array(
            'touser'      => $firm['openid'],
            'template_id' => $config['mid'],
            'url'         => $url,
            'topcolor'    => '#743a3a',
            'data' 		  => array('first'=>array('value'=>urlencode('有新商户入驻啦'),'color'=>'#007aff'),
                'keyword1'=>array('value'=>urlencode($firm['title']),'color'=>'#007aff'),
                'keyword2'=>array('value'=>urlencode($firm['wei_name']),'color'=>'#007aff'),
                'keyword3'=>array('value'=>urlencode($params['card_fee'].'元'),'color'=>'#007aff'),
                'keyword4'=>array('value'=>urlencode('已支付'),'color'=>'#007aff'),
                'keyword5'=>array('value'=>urlencode($now),'color'=>'#007aff'),
                'remark'=>array('value'=>urlencode($message),'color'=>'#007aff'),
            )
        );


        $api = $this->module['config']['api'];
        $weixin = new class_weixin($_W['account']['key'],$_W['account']['secret']);
        $weixin->send_template_message(urldecode(json_encode($template)));
        $fopenid=pdo_fetchcolumn("select openid from ".tablename('enjoy_city_fans')." where uid=".$firm[uid]." and
uniacid=".$params['uniacid']);
        if(!empty($fopenid)){
            //通过审核，发模板消息给商户
            $message="恭喜,您的店铺通过审核了，请尽快完善店铺信息，以获取更多的展示效果";
            //插入成功后通知管理员审核
            require_once MB_ROOT . '/controller/weixin.class.php';
            $url=$this->str_murl($this->createMobileUrl("firm",array('fid'=>$firm['id'])));
            $config = $this->module['config']['api'];
            $now=date('Y-m-d',TIMESTAMP);
            //echo $xxquan;
            $template = array(
                'touser'      => $fopenid,
                'template_id' => $config['mid2'],
                'url'         => $url,
                'topcolor'    => '#743a3a',
                'data' 		  => array('first'=>array('value'=>urlencode('通过审核，请尽快完善店铺信息'),'color'=>'#007aff'),
                    'keyword1'=>array('value'=>urlencode($firm['title']),'color'=>'#007aff'),
                    'keyword2'=>array('value'=>urlencode('通过'),'color'=>'#007aff'),
                    'keyword3'=>array('value'=>urlencode($now),'color'=>'#007aff'),
                    'remark'=>array('value'=>urlencode($message),'color'=>'#007aff'),
                )
            );
            $api = $this->module['config']['api'];
            $weixin = new class_weixin($_W['account']['key'],$_W['account']['secret']);
            $weixin->send_template_message(urldecode(json_encode($template)));

        }



    }
//     else{
//         //支付失败，发模板消息通知
//         $paymessage="支付失败";
//         $message="后台审核中";
//     }
    //支付失败了再检查次
    if (empty($params['result']) || $params['result'] != 'success') {
                //支付失败，发模板消息通知
                $paymessage="支付失败";
                $message="后台审核中";
    }


    $data['paytype'] = $paytype[$params['type']];
    if ($params['type'] == 'wechat') {
        $data['transid'] = $params['tag']['transaction_id'];
    }
    if ($params['type'] == 'delivery') {
        $data['status'] = 1;

    }
    if ($params['from'] == 'return') {




        header("location:../../app/" .$this->createMobileUrl('bdetail'));
        //添加模板

    }
}

public static function str_murl($url)
{
    global $_W;

    return $_W['siteroot'] . 'app' . str_replace('./', '/', $url);

}

public function doMobilepaybang(){
    global $_W,$_GPC;
    $uniacid=$_W['uniacid'];
    $userinfo1 = $_W['fans'];
    $userinfo = mc_oauth_userinfo();
    $uid=$_GPC['uid'];
    $data=array(
        'uniacid'=>$uniacid,
        'subscribe'=>$userinfo1['follow'],
        'openid'=>$userinfo['openid'],
        'nickname'=>$userinfo['nickname'],
        'gender'=>$userinfo['sex'],
        'city'=>$userinfo['city'],
        'state'=>$userinfo['province'],
        'country'=>$userinfo['country'],
        'subscribe_time'=>$userinfo1['followtime'],
        'avatar'=>$userinfo['avatar'],
        'huid'=>$userinfo1['uid'],
        'ip'=>CLIENT_IP
    );
    $res=pdo_update('enjoy_city_fans',$data,array('uid'=>$uid));
    if($res==1){
        //绑定成功
        $this->newmessage('恭喜您，绑定微信号成功',$this->createMobileUrl('addb'));
    }
}
    
public function insert_default_category($name, $logo,$hot, $parentid = 0, $isfirst = 0)
{
    global $_GPC, $_W;
    $path = '../addons/enjoy_city/public/images/icons/';
    $path = $path. $logo;

//     $category_parent = pdo_fetch("SELECT * FROM " . tablename('enjoy_city_kind') . " WHERE name = :name AND uniacid=:uniacid AND parentid=0", array(':name' => $parent_name, ':uniacid' => $_W['uniacid']));

//     $parentid = intval($category_parent['id']);

    $data = array(
        'uniacid' => $_W['uniacid'],
        'hot'=>$hot,
        'name' => $name,
        'thumb' => $path,
        'parentid' => $parentid,
        'enabled' => 0,
        'wurl' => '',
    );

    $category = pdo_fetch("SELECT * FROM " . tablename('enjoy_city_kind') . " WHERE name = :name AND uniacid=:uniacid", array(':name' => $name, ':uniacid' => $_W['uniacid']));

    if (empty($category)) {
        pdo_insert('enjoy_city_kind', $data);
    }
    return pdo_insertid();
}

function doMobilefirmfans(){
    global $_W,$_GPC;
    $uniacid=$_W['uniacid'];
    $fid=intval($_GPC['fid']);
    $uid=intval($_GPC['uid']);
    $openid=trim($_GPC['openid']);
    $follow=intval($_GPC['follow']);
    $rid=intval($_GPC['rid']);
    $fnickname=trim($_GPC['fnickname']);
    $favatar=trim($_GPC['favatar']);
    
    $time=TIMESTAMP+2*60*60;
    $date=date('m-d H:i',$time);
    if(!empty($openid)){
        //先查询是否已经是粉丝了
        $count=pdo_fetchcolumn("select count(*) from ".tablename('enjoy_city_firmfans')." where openid='".$openid."' and rid=".$rid." and uniacid=".$uniacid."
            and flag=1");
        if($count>0){
            //说明已经是本店的粉丝了
            $error=2;
        }else{
           
        //记录openid和avatar
        $data=array(
            'uniacid'=>$uniacid,
            'openid'=>$openid,
            'flag'=>$follow,
            'fnickname'=>$fnickname,
            'favatar'=>$favatar,
            'fid'=>$fid,
            'rid'=>$rid,
            'createtime'=>TIMESTAMP
        );
        $insert=pdo_insert('enjoy_city_firmfans',$data);
        if($insert>0){
            if($follow==1){
                $error=1;
            }else{
                $error=0;
            }
            
        }else{
            $error=0;
        }
        }
    }else{
        $error=0;
    }
   
    $res=array(
        'error'=>$error,
        'data'=>'',
        'extend'=>array('id'=>'F'.$fid,'expiretime'=>$time,'expire'=>$date,'uid'=>$uid),
    
    );
    echo  json_encode($res);
    exit();
    
    
}   
//粉丝评价
public function doMobilefirmstar(){
    global $_W,$_GPC;
    $uniacid=$_W['uniacid'];
    //评星
    $fid=intval($_GPC['shop_id']);
    $star=intval($_GPC['star']);
    $openid=trim($_GPC['openid']);
    $isstar=pdo_fetchcolumn("select starscore from ".tablename('enjoy_city_firmfans')." where
        uniacid=".$uniacid." and fid=".$fid." and openid='".$openid."' and flag=1");
    if($isstar>0){
        //说明已经评过星了
        $error=1;
        $data="您已评价过".$isstar."星，请不要重复评价";
    }else{
        //评价星星
        $update=array(
          'uniacid'=>$uniacid,
           'starscore'=>$star
        );
        $update=pdo_update('enjoy_city_firmfans',$update,array('uniacid'=>$uniacid,'fid'=>$fid,'openid'=>$openid,'flag'=>1));
        if ($update>0){
            //店铺评分人数++，店铺评分++
              pdo_query("update ".tablename('enjoy_city_firm')." set starnums=starnums+1,
                  starscores=starscores+".$star." where uniacid=".$uniacid." and id=".$fid);
           
            $error=0;
            $data="感谢您的评价，我们真诚为您服务";
        }else{
            $error=2;
            $data="评分失败,请重新评价";
        }
        
      
        
        
    }
    
    $res=array(
        'error'=>$error,
        'data'=>$data,
        'extend'=>array('perstar'=>$star),
    
    );
    echo  json_encode($res);
    exit();
}

//粉丝标签
public function doMobilefirmtag(){
    global $_W,$_GPC;
    $uniacid=$_W['uniacid'];
    $flag=trim($_GPC['flag']);
    $tflag=intval($_GPC['tflag']);
    $openid=trim($_GPC['openid']);
    $fid=intval($_GPC['shop_id']);
    $tag=trim($_GPC['tag']);
    $tagid=intval($_GPC['tag_id']);
    //查询这个店的标签
    $taged=pdo_fetchcolumn("select count(*) from ".tablename('enjoy_city_firmlabel')." where uniacid=".$uniacid."
        and openid='".$openid."' and fid=".$fid." and checked=0");
   
        if($flag=='1'){
            if($taged<1){
            //说明是新标签
            $insert=array(
                'uniacid'=>$uniacid,
                'openid'=>$openid,
                'label'=>$tag,
                'times'=>1,
                'fid'=>$fid,
                'createtime'=>TIMESTAMP
            );
           
            $r=pdo_insert('enjoy_city_firmlabel',$insert);
            $tagid=pdo_insertid();
            $insert1=array(
              'uniacid'=>$uniacid,
                'openid'=>$openid,
                'tagid'=>$tagid,
                'fid'=>$fid,
                'flag'=>1,
                'createtime'=>TIMESTAMP
            );
          pdo_insert('enjoy_city_taglap',$insert1);
            if($r>0){
                //新增成功
                $error=0;
                $data='操作成功，管理员审核可显示';
                $status=1;
            }else{
                $error=1;
                $data='系统忙，请稍后重新添加';
                $status=0;
            }
            }else{
                $error=1;
                $data='您有标签正在审核，通过后可以继续添加';
                $status=0;
            
            }
        
        } else{
            //先查询是否存在
            $istag=pdo_fetchcolumn("select count(*) from ".tablename('enjoy_city_taglap')." where 
                uniacid=".$uniacid." and openid='".$openid."' and tagid=".$tagid);
            if($istag>0){
                if($tflag==1){
                    $tflag=0;
                    //标签--
                    pdo_query("update ".tablename('enjoy_city_firmlabel')." set times=times-1
                       where uniacid=".$uniacid." and id=".$tagid);
                }else{
                    $tflag=1;
                    //标签++
                    pdo_query("update ".tablename('enjoy_city_firmlabel')." set times=times+1
                       where uniacid=".$uniacid." and id=".$tagid);
                }
                $t=pdo_update('enjoy_city_taglap',array('flag'=>$tflag),array('openid'=>$openid,'uniacid'=>$uniacid,'tagid'=>$tagid));
                if($t>0){
                    $error=0;
                    $data="操作成功";
                 
                }else{
                    $error=1;
                    $data="重新操作";
                    $status=0;
                }
                
            }else{
                //标签++
                $insertn=array(
                    'uniacid'=>$uniacid,
                    'openid'=>$openid,
                    'tagid'=>$tagid,
                    'fid'=>$fid,
                    'flag'=>1,
                    'createtime'=>TIMESTAMP
                );
                $s=pdo_insert('enjoy_city_taglap',$insertn);
                if($s>0){
                    //标签++
                    pdo_query("update ".tablename('enjoy_city_firmlabel')." set times=times+1
                       where uniacid=".$uniacid." and id=".$tagid);
                     
                    $error=0;
                    $data="操作成功";
                    $status=1;
                }else{
                    $error=1;
                    $data="重新操作";
                    $status=0;
                }  
            }

            $tagnum=pdo_fetchcolumn("select times from ".tablename('enjoy_city_firmlabel')." where
                uniacid=".$uniacid." and id=".$tagid);
        }
 

    
    $res=array(
        'error'=>$error,
        'data'=>$data,
        'extend'=>array('status'=>$status,'tflag'=>$tflag,'tagnum'=>$tagnum),
    
    );
    echo  json_encode($res);
    exit();
    
    
}

 //客服二维码
 public function doMobilekfewm(){
     global $_W,$_GPC;
     $uniacid=$_W['uniacid'];
     $active='user';
     //先取出活动详情
     $act=pdo_fetch("select * from ".tablename('enjoy_city_reply')." where uniacid=".$uniacid);
     
     
     
     
     include $this->template('kfewm');
 }
    //粉丝消息
    public function doMobilefansxx(){
        global $_W,$_GPC;
        $uniacid=$_W['uniacid'];
        $active='user';
        $fid=intval($_GPC['fid']);
        $firm=pdo_fetchcolumn("select title from ".tablename('enjoy_city_firm')." where id=".$fid." and 
            uniacid=".$uniacid);
        $config = $this->module['config']['api'];
        $config['jgday']=empty($config['jgday'])?7:$config['jgday'];
        //先取出活动详情
        $act=pdo_fetch("select * from ".tablename('enjoy_city_reply')." where uniacid=".$uniacid);
         $yhday=intval($_GPC['yhday']);
         $intro=trim($_GPC['intro']);
         $title=trim($_GPC['title']);
         $overtime=TIMESTAMP+24*$yhday*60*60;
         if($yhday){
            $data=array(
               'uniacid'=>$uniacid,
                'fid'=>$fid,
                'title'=>$title,
                'intro'=>$intro,
                'createtime'=>TIMESTAMP,
                'overtime'=>$overtime
            ) ;
          $res=pdo_insert('enjoy_city_fansxx',$data);
          if($res>0){
              //取出这个fid粉丝的个数
              require_once MB_ROOT . '/controller/weixin.class.php';
              $url=$this->str_murl($this->createMobileUrl("firm",array('fid'=>$fid)));
              $config = $this->module['config']['api'];
              $now=date('Y-m-d',TIMESTAMP);
              $over=date('Y-m-d',$overtime);
              $firmfans=pdo_fetchall("select * from ".tablename('enjoy_city_firmfans')." where uniacid=".$uniacid."
                  and fid=".$fid." and flag=1");
              $api = $this->module['config']['api'];
              $weixin = new class_weixin($_W['account']['key'],$_W['account']['secret']);
              for ($i=0;$i<count($firmfans);$i++){
              //echo $xxquan;
              $template = array(
                  'touser'      => $firmfans[$i]['openid'],
                  'template_id' => $config['mid1'],
                  'url'         => $url,
                  'topcolor'    => '#743a3a',
                  'data' 		  => array('first'=>array('value'=>urlencode('您收到来自'.$firm.'的信息'),'color'=>'#007aff'),
                      'toName'=>array('value'=>urlencode($firmfans[$i]['fnickname']),'color'=>'#007aff'),
                      'gift'=>array('value'=>urlencode($title),'color'=>'#007aff'),
                      'time'=>array('value'=>urlencode($now.'到'.$over),'color'=>'#007aff'),
                      'remark'=>array('value'=>urlencode($intro),'color'=>'#007aff'),
                  )
              );
              $res=$weixin->send_template_message(urldecode(json_encode($template)));
              }
           
            
              
              
              $this->newmessage('发送模板消息成功，距离下次发送还有'.$config['jgday'].'天',$this->createMobileUrl('bdetail'));
          }
            
             exit();
         }
         
         
        include $this->template('fansxx');
        
        
        
        
        
    }
    
    public function fm_qrcode($value = 'http://www.we7.cc', $filename = '', $pathname = '', $logo = '', $scqrcode = array('errorCorrectionLevel' => 'H', 'matrixPointSize' => '4', 'margin' => '5'))
    {
        global $_W;
        $uniacid = !empty($_W['uniacid']) ? $_W['uniacid'] : $_W['acid'];
        require_once '../framework/library/qrcode/phpqrcode.php';
        load()->func('file');
    
        //        $filename = empty($filename) ? date("YmdHis") . '' . random(10) : date("YmdHis") . '' . random(istrlen($filename));
        $filename = empty($filename) ? date("YmdHis") . '' . random(10) : $filename;
    
        if (!empty($pathname)) {
            $dfileurl = 'attachment/images/' . $uniacid . '/qrcode/cache/' . date("Ymd") . '/' . $pathname;
            $fileurl = '../' . $dfileurl;
        } else {
            $dfileurl = 'attachment/images/' . $uniacid . '/qrcode/cache/' . date("Ymd");
            $fileurl = '../' . $dfileurl;
        }
        mkdirs($fileurl);
        $fileurl = empty($pathname) ? $fileurl . '/' . $filename . '.png' : $fileurl . '/' . $filename . '.png';
    
        QRcode::png($value, $fileurl, $scqrcode['errorCorrectionLevel'], $scqrcode['matrixPointSize'], $scqrcode['margin']);
    
        return $fileurl;
    
        $dlogo = $_W['attachurl'] . 'headimg_' . $uniacid . '.jpg?uniacid=' . $uniacid;
    
        if (!$logo) {
            $logo = toimage($dlogo);
        }
    
        $QR = $_W['siteroot'] . $dfileurl . '/' . $filename . '.png';
        if ($logo !== FALSE) {
            $QR = imagecreatefromstring(file_get_contents($QR));
            $logo = imagecreatefromstring(file_get_contents($logo));
            $QR_width = imagesx($QR);
            $QR_height = imagesy($QR);
            $logo_width = imagesx($logo);
            $logo_height = imagesy($logo);
            $logo_qr_width = $QR_width / 5;
            $scale = $logo_width / $logo_qr_width;
            $logo_qr_height = $logo_height / $scale;
            $from_width = ($QR_width - $logo_qr_width) / 2;
            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
        }
        if (!empty($pathname)) {
            $dfileurllogo = 'attachment/images/' . $uniacid . '/qrcode/fm_qrcode/' . date("Ymd") . '/' . $pathname;
            $fileurllogo = '../' . $dfileurllogo;
        } else {
            $dfileurllogo = 'attachment/images/' . $uniacid . '/qrcode/fm_qrcode';
            $fileurllogo = '../' . $dfileurllogo;
        }
        mkdirs($fileurllogo);
        $fileurllogo = empty($pathname) ? $fileurllogo . '/' . $filename . '_logo.png' : $fileurllogo . '/' . $filename . '_logo.png';;
    
        imagepng($QR, $fileurllogo);
        return $fileurllogo;
    }
    
    public function doMobilejoblist(){
        global $_W,$_GPC;
        $uniacid=$_W['uniacid'];
        $fid=$_GPC['fid'];
        $act=pdo_fetch("select * from ".tablename('enjoy_city_reply')." where uniacid=".$uniacid);
        //查询职位信息
        $joblist=pdo_fetchall("select * from ".tablename('enjoy_city_job')." where uniacid=".$uniacid."
            and fid=".$fid);
        
        include $this->template('joblist');
        
    }
    public function doMobileaddjob(){
        global $_W,$_GPC;
        $uniacid=$_W['uniacid'];
        $fid=$_GPC['fid'];
        $firm=pdo_fetch("select * from ".tablename('enjoy_city_firm')." where uniacid=".$uniacid."
            and id=".$fid);
        $jid=$_GPC['jid'];
        if(!empty($jid)){
            $joblist=pdo_fetch("select * from ".tablename('enjoy_city_job')." where uniacid=".$uniacid."
            and fid=".$fid." and id=".$jid);
        }
       
        if($_GPC['addjob']==1){
            $data=array(
              'uniacid'=>$uniacid,
              'fid'=>$fid,
                'ptitle'=>trim($_GPC['ptitle']),
                'wages'=>trim($_GPC['wages']),
                'pnum'=>trim($_GPC['pnum']),
                'pmobile'=>trim($_GPC['pmobile']),
                'paddress'=>trim($_GPC['paddress']),
                'pdetail'=>trim($_GPC['pdetail']),
                'isfull'=>intval($_GPC['isfull']),
                'updatetime'=>TIMESTAMP,
                'createtime'=>TIMESTAMP
            
            );
            if($jid){
                $res=pdo_update('enjoy_city_job',$data,array('uniacid'=>$uniacid,'fid'=>$fid,'id'=>$jid));
            }else{
                $res=pdo_insert('enjoy_city_job',$data);
            }
            
            if($res>0){
                //跳转
                header("location:".$this->createMobileUrl('joblist',array('fid'=>$fid)));
            }
            
//             var_dump($data);
//             exit();
        }
      
        
        include $this->template('addjob');
        
        
    }
    
    public function doMobilebulid(){
        global $_W,$_GPC;
        $uniacid=$_W['uniacid'];
        $id=$_GPC['id'];
        $op=$_GPC['op'];
        if($op==1){
        $end=$_GPC['end'];
        
        if($end==0){
            $end=1;
        }else{
            $end=0;
        }
        $res['flag']=pdo_update('enjoy_city_job',array('isend'=>$end),array('uniacid'=>$uniacid,'id'=>$id));
        }elseif($op==2){
            
            $res['flag']=pdo_update('enjoy_city_job',array('updatetime'=>TIMESTAMP),array('uniacid'=>$uniacid,'id'=>$id));  
        }elseif($op==3){
            
            $res['flag']=pdo_delete('enjoy_city_job',array('uniacid'=>$uniacid,'id'=>$id));  
        }
        
        echo json_encode($res);
        exit();
        
    }

    function uploadFile($file, $filetempname, $array)
    {
        //自己设置的上传文件存放路径
        $filePath = '../addons/enjoy_city/upload/';
        include '../addons/enjoy_city/controller/reader.php';

        $data = new Spreadsheet_Excel_Reader();
        $data->setOutputEncoding('utf-8');

        //注意设置时区
        $time = date("y-m-d-H-i-s"); //去当前上传的时间
        $extend = strrchr($file, '.');
        //上传后的文件名
        $name = $time . $extend;
        $uploadfile = $filePath . $name; //上传后的文件名地址

        if (copy($filetempname, $uploadfile)) {
            if (!file_exists($filePath)) {
                echo '文件路径不存在.';
                return;
            }
            if (!is_readable($uploadfile)) {
                echo("文件为只读,请修改文件相关权限.");
                return;
            }
            $data->read($uploadfile);
            error_reporting(E_ALL ^ E_NOTICE);
            $count = 0;
            for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) { //$=2 第二行开始
                //以下注释的for循环打印excel表数据
                for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
                    //echo "\"".$data->sheets[0]['cells'][$i][$j]."\",";
                }

                $row = $data->sheets[0]['cells'][$i];
                //message($data->sheets[0]['cells'][$i][1]);

                if ($array['ac'] == "category") {
                    $count = $count + $this->upload_category($row, TIMESTAMP, $array);
                }
            }
        }
        if ($count == 0) {
            $msg = "导入失败,数据已经存在！";
        } else {
            $msg = 1;
        }
        return $msg;
    }
    
    //程序

    function upload_category($strs, $time, $array)
    {
        global $_W;

        if (empty($strs[1])) {
            return 0;
        }
        $insert = array();
//         $insert['title'] = $strs[1];
//         $insert['parentid'] = $strs[2];
//         $insert['childid'] = $strs[3];
//         $insert['s_name'] = $strs[4];
//         $insert['tel'] = $strs[5];
//         $insert['province'] = $strs[6];
//         $insert['city'] = $strs[7];
//         $insert['district'] = $strs[8];
//         $insert['address'] = $strs[9];
//         $insert['intro'] = $strs[10];
//         $insert['uniacid'] = $_W['uniacid'];
//         $insert['createtime'] = TIMESTAMP;
//         $insert['ischeck'] = 1;
//         $insert['stime'] = date('Y-m-d H:i:s',TIMESTAMP);
//         $insert['etime'] = date('Y-m-d H:i:s',TIMESTAMP+60*60*24*365);

        $insert['hot']=$strs[1];
        $insert['title']=$strs[2];
        $insert['stime'] =empty($strs[3])?date('Y-m-d H:i:s',TIMESTAMP):$strs[3];
        $insert['etime'] =empty($strs[4])?date('Y-m-d H:i:s',TIMESTAMP+60*60*24*365):$strs[4];
        $insert['parentid']=$strs[5];
        $insert['childid']=$strs[6];
        $insert['province']=$strs[7];
        $insert['city']=$strs[8];
        $insert['district']=$strs[9];
        $insert['address']=$strs[10];
        $insert['location_x']=$strs[11];
        $insert['location_y']=$strs[12];
        $insert['tel']=$strs[13];
        $insert['intro']=$strs[14];
        $insert['custom']=$strs[15];
        $insert['breaks']=$strs[16];
        $insert['firmurl']=$strs[17];
        $insert['icon']=$strs[18];
        $insert['img']=$strs[19];
        $insert['browse']=$strs[20];
        $insert['forward']=$strs[21];
        $insert['wei_num']=$strs[22];
        $insert['wei_name']=$strs[23];
        $insert['wei_sex']=$strs[24];
        $insert['wei_avatar']=$strs[25];
        $insert['wei_ewm']=$strs[26];
        $insert['s_name']=$strs[27];
        $insert['uid']=$strs[28];
        $insert['wei_intro']=$strs[29];
        $insert['uniacid'] = $_W['uniacid'];
        $insert['createtime'] = TIMESTAMP;
        $insert['ischeck'] = $strs[30];
        $insert['ispay'] = $strs[31];
        $insert['paymoney'] = $strs[32];


        $category = pdo_fetch("SELECT * FROM " . tablename('enjoy_city_firm') . " WHERE title=:title AND uniacid=:uniacid", array(':title' => $strs[1], ':uniacid' => $_W['uniacid']));

        if (empty($category)) {
            return pdo_insert('enjoy_city_firm', $insert);
        } else {
            return 0;
        }
    }
    
    public function checkUploadFileMIME($file)
    {
        // 1.through the file extension judgement 03 or 07
        $flag = 0;
        $file_array = explode(".", $file ["name"]);
        $file_extension = strtolower(array_pop($file_array));

        // 2.through the binary content to detect the file
        switch ($file_extension) {
            case "xls" :
                // 2003 excel
                $fh = fopen($file ["tmp_name"], "rb");
                $bin = fread($fh, 8);
                fclose($fh);
                $strinfo = @unpack("C8chars", $bin);
                $typecode = "";
                foreach ($strinfo as $num) {
                    $typecode .= dechex($num);
                }
                if ($typecode == "d0cf11e0a1b11ae1") {
                    $flag = 1;
                }
                break;
            case "xlsx" :
                // 2007 excel
                $fh = fopen($file ["tmp_name"], "rb");
                $bin = fread($fh, 4);
                fclose($fh);
                $strinfo = @unpack("C4chars", $bin);
                $typecode = "";
                foreach ($strinfo as $num) {
                    $typecode .= dechex($num);
                }
                echo $typecode . 'test';
                if ($typecode == "504b34") {
                    $flag = 1;
                }
                break;
        }
        return $flag;
    }
    
    public function doMobilesetcookie(){
        global $_W,$_GPC;
        $lat=trim($_GPC['lat']);
        $lng=trim($_GPC['lng']);
        $exist=pdo_tableexists('modules_newmean');
        if(!$exist){
        }else{
            setcookie('lat', $lat, time() + 60 * 20);
            setcookie('lng', $lng, time() + 60 * 20);
        }
        $res['flag']=1;
        $res['lat']=$lat;
        $res['lng']=$lng;
       echo json_encode($res);
        exit();



    }
    
    public function squarePoint($lng, $lat, $distance = 0.5)
    {

        $dlng = 2 * asin(sin($distance / (2 * EARTH_RADIUS)) / cos(deg2rad($lat)));
        $dlng = rad2deg($dlng);
        $dlat = $distance / EARTH_RADIUS;
        $dlat = rad2deg($dlat);

        return array(
            'left-top' => array('lat' => $lat + $dlat, 'lng' => $lng - $dlng),
            'right-top' => array('lat' => $lat + $dlat, 'lng' => $lng + $dlng),
            'left-bottom' => array('lat' => $lat - $dlat, 'lng' => $lng - $dlng),
            'right-bottom' => array('lat' => $lat - $dlat, 'lng' => $lng + $dlng)
        );
    }
    
    function getDistance($lat1, $lng1, $lat2, $lng2, $len_type = 1, $decimal = 2)
    {
        $radLat1 = $lat1 * M_PI / 180;
        $radLat2 = $lat2 * M_PI / 180;
        $a = $lat1 * M_PI / 180 - $lat2 * M_PI / 180;
        $b = $lng1 * M_PI / 180 - $lng2 * M_PI / 180;

        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)));
        $s = $s * EARTH_RADIUS;
        $s = round($s * 1000);
        if ($len_type > 1) {
            $s /= 1000;
        }
        $s /= 1000;
        return round($s, $decimal);
    }
    
    function tranTime($time)
    {
        $rtime = date("m-d H:i",$time);
        $htime = date("H:i",$time);

        $time = time() - $time;

        if ($time < 60)
        {
            $str = '刚刚';
        }
        elseif ($time < 60 * 60)
        {
            $min = floor($time/60);
            $str = $min.'分钟前';
        }
        elseif ($time < 60 * 60 * 24)
        {
            $h = floor($time/(60*60));
            $str = $h.'小时前 '.$htime;
        }
        elseif ($time < 60 * 60 * 24 * 3)
        {
            $d = floor($time/(60*60*24));
            if($d==1)
                $str = '昨天 '.$rtime;
            else
                $str = '前天 '.$rtime;
        }
        else
        {
            $str = $rtime;
        }
        return $str;
    }
    
    protected function exportexcel($data = array(), $title = array(), $filename = 'report')
    {
        header("Content-type:application/octet-stream");
        header("Accept-Ranges:bytes");
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=" . $filename . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        //导出xls 开始
        if (!empty($title)) {
            foreach ($title as $k => $v) {
                $title[$k] = iconv("UTF-8", "GB2312", $v);
            }
            $title = implode("\t", $title);
            echo "$title\n";
        }
        if (!empty($data)) {
            foreach ($data as $key => $val) {
                foreach ($val as $ck => $cv) {
                    $data[$key][$ck] = iconv("UTF-8", "GB2312", $cv);
                }
                $data[$key] = implode("\t", $data[$key]);
            }
            echo implode("\n", $data);
        }
    }
    
    private function book($message) {
        global $_W;
        $setting = uni_setting($_W['uniacid'], array('passport'));

        load()->model('mc');
        $fans = mc_fansinfo($message['from']);
        $default_groupid = cache_load("defaultgroupid:{$_W['uniacid']}");
        if (empty($default_groupid)) {
            $default_groupid = pdo_fetchcolumn('SELECT groupid FROM ' .tablename('mc_groups') . ' WHERE uniacid = :uniacid AND isdefault = 1', array(':uniacid' => $_W['uniacid']));
            cache_write("defaultgroupid:{$_W['uniacid']}", $default_groupid);
        }
        $rec = array();
        $rec['acid'] = $_W['acid'];
        $rec['uniacid'] = $_W['uniacid'];
        $rec['uid'] = 0;
        $rec['openid'] = $message['from'];
        $rec['nickname'] = $message['nickname'];
        $rec['salt'] = random(8);
        if ($message['event'] == 'unsubscribe') {
            $rec['follow'] = 0;
            $rec['followtime'] = 0;
            $rec['unfollowtime'] = $message['time'];
        } else {
            $rec['follow'] = 1;
            $rec['followtime'] = $message['time'];
            $rec['unfollowtime'] = 0;
        }
        if (!isset($setting['passport']) || empty($setting['passport']['focusreg'])) {
            $data = array(
                'uniacid' => $_W['uniacid'],
                'email' => md5($message['from']).'@we7.cc',
                'salt' => random(8),
                'groupid' => $default_groupid,
                'createtime' => TIMESTAMP,
            );
            $data['password'] = md5($message['from'] . $data['salt'] . $_W['config']['setting']['authkey']);
            pdo_insert('mc_members', $data);
            $rec['uid'] = pdo_insertid();
        }
        pdo_insert('mc_mapping_fans', $rec);
        //}
    }
    
    
    
    
    

}