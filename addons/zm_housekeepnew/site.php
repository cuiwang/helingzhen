<?php
/**
 * 家政服务模块微站定义
 *
 */
defined('IN_IA') or exit('Access Denied');
include_once 'class/MysqlConn.class.php';
include_once 'class/NickImg.class.php';
class Zm_housekeepnewModuleSite extends NickImg {


    protected $_staff = 'xk_housekeepstaff';

    protected $_serverg = 'xk_housekeepserverg';

    protected $_project = 'xk_housekeepservergproject';

    protected $_base = 'xk_housekeepbase';

    protected $_slide = 'xk_housekeepslide';

    protected $_slidecc = 'xk_housekeepslidecc';

    protected $_orderg = 'xk_housekeeporderg';

    protected $_muban = 'xk_housekeepmuban';

    protected $_member = 'xk_housekeepmember';

    protected $_assess = 'xk_housekeepassess';

    protected $_user = 'xk_housekeepuser';

    protected $_qdlist = 'xk_housekeepqdlist';

    protected $_usrads = 'xk_housekeepuseraddress';

    protected $_moneycz = 'xk_housekeepmoneycz';

    protected $_moneyrc = 'xk_housekeepmoneyrc';

    protected $_fwstgy = 'xk_housekeepfwstgy';

    protected $_barrage = 'xk_housekeepbarrage';

    protected $_core = 'core_paylog';

    public function __construct(){
        global $_W, $_GPC;
        $this->_wid = $_W['uniacid'];
        $this->_uid = $_W['member']['uid'];
        $this->_acid = $_W['acid'];
        $this->_openid = $_W['openid'];
        $this->base = $this->myGet($this->_base,array('wid'=>$this->_wid));
        $this->muban = $this->myGet($this->_muban,array('wid'=>$this->_wid));
        $this->memberid = $this->myGet($this->_member,array('wid'=>$this->_wid,'level'=>0));
        $_W['page']['title'] = $this->base['header'];
        $_W['page']['footer'] = $this->base['footer'];
    }
    //手机端首页
    public function doMobileMfront() {
        global $_W,$_GPC;
        
        $_share = $this->shareDate();
        $mp3 = $this->myGet('xk_housekeepsetting',array('wid'=>$this->_wid));
        $dm = $this->myGetall($this->_barrage,array('wid'=>$this->_wid));
        $dat = $this->myGet($this->_user,array('wid'=>$this->_wid,'openid'=>$this->_openid));
        $cc = $this->myGet($this->_slidecc,array('wid'=>$this->_wid));
        if ($_GPC['cha'] != null){
            $list = $this->myGettop($this->_project,'*','','top limit '.$_GPC['cha']);
            foreach ($list as $k=>$v){
                $list[$k]['icon'] = tomedia($list[$k]['icon']);
                $a = $this->myGet($this->_serverg,array('wid'=>$this->_wid,'id'=>$v['serverg_id']),array('name'));
                $list[$k]['serverg_name'] = $a['name'];
            }
            $succ = json_encode($list);
            echo $succ;exit;
        }
        if ($dat){
            $use = $this->myGettop($this->_member,'*','','level DESC');
            foreach ($use as $k=>$v){
                $data = $this->myGet($this->_user,array('openid'=>$this->_openid));
                $m = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename($this->_orderg)." WHERE wid = '".$this->_wid."' AND openid= '".$this->_openid."' AND state=3 OR wid = '".$this->_wid."' AND openid= '".$this->_openid."' AND state=4");
                if (($data['money'] >= $v['money']) or ($m >= $v['number'])){
                    $this->myUpdate($this->_user,array('member'=>$v['title']),array('wid'=>$this->_wid,'openid'=>$this->_openid),'AND');
                    break;
                }
            }
            $slide = $this->myGettop($this->_slide,'*','','top');
            $serverg = $this->myGettop($this->_serverg,'*','','top limit 8');
            $project = $this->myGettop($this->_project,'*','','top limit 5');
            require $this->template('mfront');
        }else{
            $data = array(
                'wid' => $this->_wid,
                'openid' => $this->_openid,
                'nickname' =>$this->nickName(),
                'avatar' =>$this->imgAvtar(),
                'member' => $this->memberid['title'],
                'addtime'=>TIMESTAMP,
            );
            $this->myInsert($this->_user,$data);
            $slide = $this->myGettop($this->_slide,'*','','top');
            $serverg = $this->myGettop($this->_serverg,'*','','top limit 8');
            $project = $this->myGettop($this->_project,'*','','top limit 5');
            require $this->template('mfront');
        }
    }
    //查询项目
    public function doMobileFindproject(){
        global $_W,$_GPC;
        
        $_share = $this->shareDate();
        if ($_GPC['name'] != null){
            $list = pdo_fetchall("SELECT * FROM ".tablename($this->_project)." WHERE wid='".$this->_wid."' AND name like '%".$_GPC['name']."%'");
            include $this->template('findproject');
        }

    }
    //所有服务
    public function doMobileAllserverg(){
        global $_W,$_GPC;
        
        $_share = $this->shareDate();
        $serverg = $this->myGettop($this->_serverg,'*','','top');
        require $this->template('allserverg');
    }
    //服务下的项目
    public function doMobileProject(){
        global $_W,$_GPC;
        
        $_share = $this->shareDate();
        $serverg = $this->myGet($this->_serverg,array('wid'=>$this->_wid,'id'=>intval($_GPC['id'])),array('name'));
        $project = $this->myGetall($this->_project,array('wid'=>$this->_wid,'serverg_id'=>intval($_GPC['id'])));
        require $this->template('project');
    }
    //订单提交
    public function doMobileProjectdetail(){
        global $_W,$_GPC;
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        
        $_share = $this->shareDate();
        load()->func('tpl');
        if($_GPC['state']!=null){
            $this->myUpdate($this->_usrads,array('moren'=>1),array('wid'=>$this->_wid,'id'=>intval($_GPC['state'])),'AND');
        }
        $na = $this->myGet('mc_members',array('uniacid'=>$this->_wid,'uid'=>$this->_uid));
        $id = intval($_GPC['id']);
        $project = $this->myGet($this->_project,array('wid'=>$this->_wid,'id'=>$id));
        $address = $this->myGet($this->_usrads,array('wid'=>$this->_wid,'openid'=>$this->_openid,'moren'=>1));
        $user = $this->myGet($this->_user,array('wid'=>$this->_wid,'openid'=>$this->_openid));
        $member = $this->myGet($this->_member,array('wid'=>$this->_wid,'title'=>$user['member']));
        if ($user['mobile'] == null){
            $this->myUpdate($this->_user,array('mobile'=>$_GPC['orderg']['mobile']),array('wid'=>$this->_wid,'openid'=>$this->_openid),'AND');
        }
        if(checksubmit()){
            $data = $_GPC['orderg'];
            $data['address'] = str_replace(',','',$data['address']);
            $data['addtime'] = date('Y-m-d H:i:s',strtotime($_GPC['orderg']['addtime']));
            $data['wid'] = $this->_wid;
            $data['nickname'] = $this->nickName();
            $data['atime'] = TIMESTAMP;
            $data['openid'] = $this->_openid;
            $data['project_name'] = $project['name'];
            $data['unit'] = $project['price'];
            $sum = $_GPC['orderg']['number'] * $project['price'];
            $data['summoney'] = $sum - ($sum * ($member['agio']/100));
            $data['summoney'] = substr(sprintf("%.3f",$data['summoney']),0,-1);
            $data['state'] = 0;
            $data['tid'] = TIMESTAMP.$this->_uid;
            $succ = $this->myInsert($this->_orderg,$data);
            $data11 = array(
                'first'    => array(
                    'value' =>'未支付提醒',
                    'color' => '#173177'
                ),
                'keyword1' => array(
                    'value' =>$data['project_name'],
                    'color' => '#173177'
                ),
                'keyword2' => array(
                    'value'=>'未支付',
                    'color'=>'#173177'
                ),
                'remark' => array(
                    'value'=>'您的订单未付款,点击查看详情',
                    'color'=>'#173177'
                )
            );
            $this->wob($this->_openid,$this->muban['messageid4'],$data11,$_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&do=dingdandetail&id=".$succ."&m=".$this->module['name']);
            $this->succError($succ,$this->createMobileUrl('Paym',array('id'=>$succ)));
        }
        require $this->template('projectdetail');
    }
    //订单列表
    public function doMobileOrderlist(){
        global $_W,$_GPC;
//        echo $this->module['name'];exit;
        
        $_share = $this->shareDate();
        if($_GPC['name']=='dai') {
            $list_d = $this->myGetall($this->_orderg, array('wid' => $this->_wid, 'openid' => $this->_openid, 'state' => 0));
            include $this->template('orderlist');
        }elseif($_GPC['name']=='wei'){
            $list_w = $this->myGetall($this->_orderg,array('wid'=>$this->_wid,'openid'=>$this->_openid,'state'=>1));
            include $this->template('orderlist');
        }elseif($_GPC['name']=='fu'){
            $list_f = $this->myGetall($this->_orderg,array('wid'=>$this->_wid,'openid'=>$this->_openid,'state'=>2));
            include $this->template('orderlist');
        }elseif($_GPC['name']=='yi'){
            if ($_GPC['id'] != null){
                $list_y = pdo_fetchall("SELECT * FROM ".tablename($this->_orderg)." WHERE wid = '".$this->_wid."' AND openid= '".$this->_openid."' AND state=3 AND id='".intval($_GPC['id'])."'");
                include $this->template('orderlist');exit();
            }
            $list_y = pdo_fetchall("SELECT * FROM ".tablename($this->_orderg)." WHERE wid = '".$this->_wid."' AND openid= '".$this->_openid."' AND state=3 OR wid = '".$this->_wid."' AND openid= '".$this->_openid."' AND  state=4");
            include $this->template('orderlist');
        }else{
            $list_d = $this->myGetall($this->_orderg,array('wid'=>$this->_wid,'openid'=>$this->_openid,'state'=>0));
            $list_w = $this->myGetall($this->_orderg,array('wid'=>$this->_wid,'openid'=>$this->_openid,'state'=>1));
            $list_f = $this->myGetall($this->_orderg,array('wid'=>$this->_wid,'openid'=>$this->_openid,'state'=>2));
            $list_y = pdo_fetchall("SELECT * FROM ".tablename($this->_orderg)." WHERE wid = '".$this->_wid."' AND openid= '".$this->_openid."' AND state=3 OR  wid = '".$this->_wid."' AND openid= '".$this->_openid."' AND state=4");
            include $this->template('orderlist');
        }
    }
    //订单完成
    public function doMobileOrderwc(){
        global $_GPC,$_W;
        
        $_share = $this->shareDate();
        $order = $this->myGet($this->_orderg,array('wid'=>$this->_wid,'id'=>intval($_GPC['id'])));
        $succ = $this->myUpdate($this->_orderg,array('state'=>3),array('wid'=>$this->_wid,'id'=>intval($_GPC['id'])),'AND');
        $user = $this->myGet($this->_user,array('wid'=>$this->_wid,'openid'=>$this->_openid));
        $this->myUpdate($this->_user,array('money'=>($order['summoney']+$user['money'])),array('wid'=>$this->_wid,'openid'=>$this->_openid),'AND');
        $data = array(
            'first'    => array(
                'value' =>$this->muban['prompt4'],
                'color' => '#173177'
            ),
            'keyword1' => array(
                'value' =>$order['project_name'],
                'color' => '#173177'
            ),
            'keyword2' => array(
                'value'=>'已完成',
                'color'=>'#173177'
            ),
            'remark' => array(
                'value'=>$this->muban['remarks4'],
                'color'=>'#173177'
            )
        );
        $this->wob($order['openid'],$this->muban['messageid4'],$data);
//        $data1 = array(
//            'first'    => array(
//                'value' =>$this->muban['prompt8'],
//                'color' => '#173177'
//            ),
//            'keyword1' => array(
//                'value' =>$order['project_name'],
//                'color' => '#173177'
//            ),
//            'keyword2' => array(
//                'value'=>date('Y-m-d H:i:s',TIMESTAMP),
//                'color'=>'#173177'
//            ),
//            'remark' => array(
//                'value'=>$this->muban['remarks8'],
//                'color'=>'#173177'
//            )
//        );
//        $this->wob($order['seropenid'],$this->muban['messageid8'],$data1,$_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&do=ordersc&id=".$order['id']."&m=".$this->module['name']);
        message('请您对服务做出评价',$this->createMobileUrl('orderlist',array('name'=>'yi','id'=>intval($_GPC['id']))),'success');
    }
    //管理员派单
    public function doMobileDispatch(){
        global $_W,$_GPC;
        if  (checksubmit()) {
            $list = $this->myGet($this->_orderg, array('id' => intval($_GPC['orderid']), 'wid' => $this->_wid));
            $data = array(
                'first' => array(
                    'value' => $this->muban['prompt2'],
                    'color' => '#173177'
                ),
                'keyword1' => array(
                    'value' => $list['name'],
                    'color' => '#173177'
                ),
                'keyword2' => array(
                    'value' => $list['project_name'],
                    'color' => '#173177'
                ),
                'keyword3' => array(
                    'value' => $list['addtime'] . ',地址:' . $list['address'],
                    'color' => '#173177'
                ),
                'keyword4' => array(
                    'value' => $list['mobile'],
                    'color' => '#173177'
                ),
                'remark' => array(
                    'value' => $this->muban['remarks2'],
                    'color' => '#173177'
                )
            );
            if ($list['state'] == 1) {
                $ord = $this->myGet($this->_staff, array('id' => $_GPC['staffid'], 'wid' => $this->_wid));
                $succ = $this->myUpdate($this->_orderg, array('state' => 2, 'sername' => $ord['name'], 'seropenid' => $ord['openid']), array('wid' => $this->_wid, 'id' => $list['id']), 'AND');
                if ($succ) {
                    $datagk = array(
                        'first' => array(
                            'value' => '您的订单状态已更新',
                            'color' => '#173177'
                        ),
                        'keyword1' => array(
                            'value' => $list['project_name'],
                            'color' => '#173177'
                        ),
                        'keyword2' => array(
                            'value' => '管理员已派单',
                            'color' => '#173177'
                        ),
                        'remark' => array(
                            'value' => '服务师姓名:' . $ord['name'] . ',手机号:' . $ord['mobile'],
                            'color' => '#173177'
                        )
                    );
                    $this->wob($ord['openid'], $this->muban['messageid2'], $data);
                    $this->wob($list['openid'], $this->muban['messageid4'], $datagk, $_W['siteroot'] . 'app' . str_replace('./', '/', murl('entry/site/dingdandetail')) . '&m=' . $_W['current_module']['name'] . '&id=' . intval($_GPC['id']));
                    message('派单成功', $this->createMobileUrl('Dispatch'), 'success');
                }
            }
        }
        $peo = $this->myGetall($this->_staff,array('wid'=>$this->_wid,'backadmin'=>1),array('name','id'));
        $all =  $this->myGetall($this->_orderg,array('wid'=>$this->_wid,'state'=>1));
        include $this->template('dispatch');
    }

    public function doMobileOrdersc(){
        global $_GPC,$_W;
        
        $_share = $this->shareDate();
        load()->func('tpl');
        if (checksubmit()){
            $list = $this->myGet($this->_orderg,array('wid'=>$this->_wid,'id'=>$_GPC['id']));
            if ($list['img'] == null){
                load()->func('file');
                $a = file_move($_FILES['imgfile']['tmp_name'],ATTACHMENT_ROOT.'/images/'.$_W['uniacid'].'/2017/01/'.$list['tid'].'.jpg');
                $data['img'] = 'images/'.$_W['uniacid'].'/2017/01/'.$list['tid'].'.jpg';
                $data['con'] = $_GPC['con'];
                $succ = $this->myUpdate($this->_orderg,$data,array('wid'=>$this->_wid,'id'=>$list['id']),'AND');
                if ($succ){
                    $this->myUpdate($this->_qdlist,array('state'=>2),array('wid'=>$this->_wid,'id'=>intval($_GPC['idx'])),'AND');
                    message('提交成功',$this->createMobileUrl('mstaffcore'),'success');
                }
            }else{
                message('已经提交,请勿重新提交',$this->createMobileUrl('mstaffcore'),'info');
            }
        }
        include $this->template('ordersc');
    }
    //取消订单
    public function doMobileDelorder(){
        global $_W,$_GPC;
        $this->myDelete($this->_orderg,array('id'=>intval($_GPC['id']),'openid'=>$this->_openid,'wid'=>$this->_wid));
        message('取消成功',$this->createMobileUrl('orderlist'),'success');
    }
    //催单
    public function doMobileCuidan(){
        global $_GPC,$_W;
        $data = $this->myGet($this->_orderg,array('id'=>intval($_GPC['id']),'wid'=>$this->_wid));
        $data1 = array(
            'first'    => array(
                'value' =>$this->muban['prompt1'],
                'color' => '#173177'
            ),
            'keyword1' => array(
                'value' =>'新订单待派单,顾客催单',
                'color' => '#173177'
            ),
            'keyword2' => array(
                'value'=>'项目:'.$data['project_name'].',时间:'.$data['addtime'],
                'color'=>'#173177'
            ),
            'remark' => array(
                'value'=>$this->muban['remarks1'],
                'color'=>'#173177'
            )
        );
        $all = $this->myGetall($this->_staff,array('wid'=>$this->_wid,'booking'=>1));
        foreach($all as $v){
            $this->wob($v['openid'],$this->muban['messageid1'],$data1,$_W['siteroot'] . 'app' . str_replace('./', '/', murl('entry/site/dispatch')) . '&m=' . $_W['current_module']['name']);
        }

        message('催单成功,管理员已收到你的信息,请稍等',$this->createMobileUrl('orderlist'),'success');
    }
    //支付订单
    public function doMobilePaym(){
        global $_W,$_GPC;
        
        $data = $this->myGet($this->_orderg,array('id'=>intval($_GPC['id']),'wid'=>$this->_wid));
        if ($_GPC['sta'] == 1){
            $data11 = $this->myGet($this->_orderg,array('tid'=>$_GPC['tid'],'wid'=>$this->_wid));
            $core = $this->myGet($this->_core,array('uniacid'=>$this->_wid,'tid'=>$_GPC['tid']));
            if($core['status']==1){
                $this->myUpdate($this->_orderg,array('state'=>1),array('wid'=>$this->_wid,'tid'=>$_GPC['tid']),'AND');
                $data1 = array(
                    'first'    => array(
                        'value' =>$this->muban['prompt1'],
                        'color' => '#173177'
                    ),
                    'keyword1' => array(
                        'value' =>'新订单待派单',
                        'color' => '#173177'
                    ),
                    'keyword2' => array(
                        'value'=>'项目:'.$data11['project_name'].',时间:'.$data11['addtime'],
                        'color'=>'#173177'
                    ),
                    'remark' => array(
                        'value'=>$this->muban['remarks1'],
                        'color'=>'#173177'
                    )
                );
                $all = $this->myGetall($this->_staff,array('wid'=>$this->_wid,'booking'=>1));
                foreach($all as $v) {
                    $this->wob($v['openid'], $this->muban['messageid1'], $data1,$_W['siteroot'] . 'app' . str_replace('./', '/', murl('entry/site/dispatch')) . '&m=' . $_W['current_module']['name']);
                }
                $da = array(
                    'first'    => array(
                        'value' =>'您的订单状态更新',
                        'color' => '#173177'
                    ),
                    'keyword1' => array(
                        'value' =>$data11['project_name'],
                        'color' => '#173177'
                    ),
                    'keyword2' => array(
                        'value'=>'已付款',
                        'color'=>'#173177'
                    ),
                    'remark' => array(
                        'value'=>'您的订单已支付,请等待管理员派单',
                        'color'=>'#173177'
                    )
                );
                $this->wob($data11['openid'], $this->muban['messageid3'], $da);
                message('已经成功支付',$this->createMobileUrl('orderlist'),'success');
//                header('Location:'.$_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=orderlist&m='.$this->module['name']);
            }
        }
        if (((int)($data['summoney']) === 0) && ($_GPC['tid']!= null)){
            $data11 = $this->myGet($this->_orderg,array('tid'=>$_GPC['tid'],'wid'=>$this->_wid));
            $this->myUpdate($this->_orderg,array('state'=>1),array('wid'=>$this->_wid,'tid'=>$_GPC['tid']),'AND');
            $data1 = array(
                'first'    => array(
                    'value' =>$this->muban['prompt1'],
                    'color' => '#173177'
                ),
                'keyword1' => array(
                    'value' =>'新订单待派单',
                    'color' => '#173177'
                ),
                'keyword2' => array(
                    'value'=>'项目:'.$data11['project_name'].',时间:'.$data11['addtime'],
                    'color'=>'#173177'
                ),
                'remark' => array(
                    'value'=>$this->muban['remarks1'],
                    'color'=>'#173177'
                )
            );
            $all = $this->myGetall($this->_staff,array('wid'=>$this->_wid,'booking'=>1));
            foreach($all as $v) {
                $this->wob($v['openid'], $this->muban['messageid1'], $data1,$_W['siteroot'] . 'app' . str_replace('./', '/', murl('entry/site/dispatch')) . '&m=' . $_W['current_module']['name']);
            }
            message('已经成功支付',$this->createMobileUrl('orderlist'),'success');
//            header('Location:'.$_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=orderlist&m='.$this->module['name']);
        }
        $this->payMent($data['tid'],$data['tid'],$data['project_name'],$data['summoney']);
    }
    //会员充值
    public function doMobileMoneycz(){
        global $_GPC,$_W;
        
        $_share = $this->shareDate();
//        if ($_GPC['jf'] != null){
//            load()->model('mc');
//            mc_credit_update($_W['member']['uid'], 'credit2',$_GPC['fee'], array($_W['member']['uid'], '余额充值返回'));
//        }
        if ($_GPC['fee'] != null){
            load()->model('mc');
            $data['tid'] = TIMESTAMP.$this->_uid;
            $data['title'] = '会员充值';
            $data['money'] = $_GPC['fee'];
            $data['openid'] = $this->_openid;
            $data['nickname'] = $this->nickName();
            $data['wid'] = $this->_wid;
            $data['addtime'] = TIMESTAMP;
            $data['state'] = 0;
            $this->myInsert($this->_moneyrc,$data);
            $cz = $this->myGet($this->_moneycz,array('wid'=>$this->_wid,'czmoney'=>intval($_GPC['fee'])));
            $this->myUpdate($this->_moneyrc,array('state'=>1,'zsmoney'=>$cz['zsmoney']),array('wid'=>$this->_wid,'tid'=> $data['tid']),'AND');
            $ye = $cz['czmoney'] + $cz['zsmoney'];
            mc_credit_update($this->_uid, 'credit2', $ye, array($this->_uid, '余额充值赠送'));
            $ti = date('Y-m-d H:i:s',time());
            $aaaa =str_replace('{nickname}',$this->nickName(),$this->muban['remarks6']);
            $aaaa =str_replace('{sum}',$_W['member']['credit2']+$ye,$aaaa);
            $aaaa =str_replace('{jifen}',$ye,$aaaa);

            $da = array(
                'first'    => array(
                    'value' =>$this->muban['prompt6'],
                    'color' => '#173177'
                ),
                'keyword1' => array(
                    'value' =>$ti,
                    'color' => '#173177'
                ),
                'keyword2' => array(
                    'value'=>'充值',
                    'color'=>'#173177'
                ),
                'keyword3' => array(
                    'value'=>'增加'.$ye.'余额',
                    'color'=>'#173177'
                ),
                'keyword4' => array(
                    'value'=>$_W['member']['credit2']+$ye,
                    'color'=>'#173177'
                ),
                'remark' => array(
                    'value'=>$aaaa,
                    'color'=>'#173177'
                )
            );
            $this->wob($this->_openid,$this->muban['messageid6'],$da,$_W['siteroot'].'app'.str_replace('./','/',murl('entry/site/moneyczlist')).'&m='.$this->module['name']);
            message('充值成功',$this->createMobileUrl('member'),'success');
        }
        if ($_GPC['money']!=null){
            require_once "pay/WxPay.JsApiPay.php";
            require_once "pay/WxPay.Exception.php";
            require_once "pay/WxPay.Config.php";
            require_once "pay/WxPay.Data.php";
            $tools = new JsApiPay();
            //支付参数
            $input = new WxPayUnifiedOrder();
            $input->SetBody("充值");
            $input->SetAttach("充值");
            $input->SetAppid($_W['account']['key']);//公众账号ID
            if ($this->module['config']['mchid'] == null){
                $input->SetMch_id(WxPayConfig::MCHID);//商户号
                $input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
            }else{
                $input->SetMch_id($this->module['config']['mchid']);//商户号
                $input->SetOut_trade_no($this->module['config']['mchid'].date("YmdHis"));
            }
            $input->SetTotal_fee($_GPC['money']*100
            );
            $input->SetTime_start(date("YmdHis"));
            $input->SetTime_expire(date("YmdHis", time() + 600));
            $input->SetGoods_tag("充值");
            $input->SetNotify_url("http://paysdk.weixin.qq.com/example/notify.php");
            $input->SetTrade_type("JSAPI");
            $input->SetOpenid($this->_openid);
            $order = WxPayApi::unifiedOrder($input,$this->module['config']['mchid'],$this->module['config']['password']);
            $jsApiParameters = $tools->GetJsApiParameters($order,$this->module['config']['password']);
            echo $jsApiParameters;exit;
//            $this->payMent($data['tid'],$data['tid'],$data['title'],$data['money']);exit;
        }
        $list = $this->myGetall($this->_moneycz,array('wid'=>$this->_wid));
        include $this->template('moneycz');
    }

    public function doMobileMoneyczlist(){
        global $_GPC,$_W;
        
        $_share = $this->shareDate();
        $list = $this->myGetall($this->_moneyrc,array('wid'=>$this->_wid,'openid'=>$this->_openid,'state'=>1));
        include $this->template('moneyczlist');
    }
    //订单详情
    public function doMobileProjectsingle(){
        global $_W,$_GPC;
        
        $_share = $this->shareDate();
        $project = $this->myGet($this->_project,array('wid'=>$this->_wid,'id'=>intval($_GPC['id'])));
        $member = $this->myGetall($this->_member,array('wid'=>$this->_wid));
        $user = $this->myGet($this->_user,array('wid'=>$this->_wid,'openid'=>$this->_openid));
        include $this->template('projectsingle');
    }

    public function doMobileDingdandetail(){
        global $_W,$_GPC;
        
        $_share = $this->shareDate();
        $list = $this->myGet($this->_orderg,array('id'=>intval($_GPC['id']),'wid'=>$this->_wid));
        $pro = $this->myGet($this->_project,array('name'=>$list['project_name'],'wid'=>$this->_wid));
        $core = $this->myGet($this->_core,array('uniacid'=>$this->_wid,'tid'=>$list['tid']));
        include $this->template('dingdandetail');
    }
    //抢单页面
    public function doMobileQiangdan(){
        global $_W,$_GPC;
        
        $_share = $this->shareDate();
        $list = $this->myGet($this->_staff,array('wid'=>$this->_wid,'openid'=>$this->_openid,'state'=>1));
        if($list){
            if ($_GPC['jwd'] != null){
                $list =$this->myGetall($this->_orderg,array('wid'=>$this->_wid,'state'=>1),array('jwd','id'),'AND');
                $succ = json_encode($list);
                echo $succ;exit;
            }
            $ti = TIMESTAMP - ($this->module['config']['tt']*60);
            $list =pdo_fetchall("SELECT * FROM ".tablename($this->_orderg)." WHERE wid='".$this->_wid."' AND state=1 AND atime<'".$ti."'");
            if ($list){
                $a = count($list);
            }
            include $this->template('qiangdan');
        }else{
            message('请您先申请服务师,如申请请等待审核',$this->createMobileUrl('mstaff'),'success');
        }
    }
    //抢单服务师说明
    public function doMobileSerspeak(){
        global $_GPC,$_W;
        
        $_share = $this->shareDate();
        include $this->template('serspeak');
    }

    public function doMobileQiangdanone(){
        global $_W,$_GPC;
        
        $_share = $this->shareDate();
        $list = $this->myGet($this->_orderg,array('id'=>intval($_GPC['id']),'wid'=>$this->_wid));
        $ord = $this->myGet($this->_staff,array('openid'=>$this->_openid,'wid'=>$this->_wid));
        $succ = $this->myUpdate($this->_orderg,array('sername'=>$ord['name'],'seropenid'=>$ord['openid']),array('wid'=>$this->_wid,'id'=>$list['id']),'AND');
        if($succ) {
            $dat = array(
                'wid' => $this->_wid,
                'openid' => $this->_openid,
                'sername' => $list['sername'],
                'project_name' => $list['project_name'],
                'address' => $list['address'],
                'addtime' => $list['addtime'],
                'time' => TIMESTAMP,
                'summoney' => $list['summoney'],
                'state' => 0,
                'orderid' => $list['id']
            );
            $this->myInsert($this->_qdlist, $dat);
            message("抢单成功",$this->createMobileUrl('qdlist'),'success');
        }
    }
    //抢单派单
    public function doMobilePaidan(){
        global $_GPC,$_W;
        $list = $this->myGet($this->_orderg,array('id'=>intval($_GPC['id']),'wid'=>$this->_wid));
        $data = array(
            'first'    => array(
                'value' =>$this->muban['prompt2'],
                'color' => '#173177'
            ),
            'keyword1' => array(
                'value' =>$list['name'],
                'color' => '#173177'
            ),
            'keyword2' => array(
                'value'=>$list['project_name'],
                'color'=>'#173177'
            ),
            'keyword3' => array(
                'value'=>$list['addtime'].',地址:'.$list['address'],
                'color'=>'#173177'
            ),
            'keyword4' => array(
                'value'=>$list['mobile'],
                'color'=>'#173177'
            ),
            'remark' => array(
                'value'=>$this->muban['remarks2'],
                'color'=>'#173177'
            )
        );
        if($list['state']==1){
            $ord = $this->myGet($this->_staff,array('openid'=>$this->_openid,'wid'=>$this->_wid));
            $succ = $this->myUpdate($this->_orderg,array('state'=>2,'sername'=>$ord['name'],'seropenid'=>$ord['openid']),array('wid'=>$this->_wid,'id'=>$list['id']),'AND');
            if($succ){
                $datagk = array(
                    'first' => array(
                        'value' =>'您的订单状态已更新',
                        'color' => '#173177'
                    ),
                    'keyword1' => array(
                        'value'=>$list['project_name'],
                        'color'=>'#173177'
                    ),
                    'keyword2' => array(
                        'value'=>'管理员已派单',
                        'color'=>'#173177'
                    ),
                    'remark' => array(
                        'value'=>'服务师姓名:'.$ord['name'].',手机号:'.$ord['mobile'],
                        'color'=>'#173177'
                    )
                );
//                $dat = array(
//                    'wid'=>$this->_wid,
//                    'openid'=>$this->_openid,
//                    'sername' => $list['sername'],
//                    'project_name' =>$list['project_name'],
//                    'address' =>$list['address'],
//                    'addtime' => $list['addtime'],
//                    'time' => TIMESTAMP,
//                    'summoney' =>$list['summoney'],
//                    'state' => 1,
//                    'orderid' => $list['id']
//                );
                $suc = $this->myUpdate($this->_qdlist,array('state'=>1),array('wid'=>$this->_wid,'orderid'=>$list['id']),'AND');
                $this->wob($ord['openid'],$this->muban['messageid2'],$data);
                $this->wob($list['openid'],$this->muban['messageid4'],$datagk,$_W['siteroot'].'app'.str_replace('./','/',murl('entry/site/dingdandetail')).'&m='.$this->module['name'].'&id='.intval($_GPC['id']));
                if($suc) message('请及时服务顾客',$this->createMobileUrl('qdlist'),'success');
//                if ($_GPC['fan'] !=null){
//                    message('请及时服务顾客',$this->createMobileUrl('qdlist'),'success');
//                }
//                $json = json_encode($succ);
//                echo $json;exit;
            }
        }
    }
    //抢单后的页面
    public function doMobileQdlist(){
        global $_W,$_GPC;
        
        $_share = $this->shareDate();
        if ($_GPC['state']=='fw'){
            $list = $this->myGetall($this->_qdlist,array('state'=>1,'wid'=>$this->_wid,'openid'=>$this->_openid));
            include $this->template('qdlist');exit;
        }
        if ($_GPC['state']=='wc'){
            $list = $this->myGetall($this->_qdlist,array('state'=>2,'wid'=>$this->_wid,'openid'=>$this->_openid));
            include $this->template('qdlist');exit;
        }
        $list = $this->myGetall($this->_qdlist,array('wid'=>$this->_wid,'openid'=>$this->_openid));
        include $this->template('qdlist');
    }
    //抢单详情
    public function doMobileQddetail(){
        global $_W,$_GPC;
        
        $_share = $this->shareDate();
        $list = $this->myGet($this->_qdlist,array('wid'=>$this->_wid,'id'=>intval($_GPC['id'])));
        $aa = $this->myGet($this->_orderg,array('wid'=>$this->_wid,'id'=>$list['orderid']));
        include $this->template('qddetail');
    }
    //抢单删除
    public function doMobileQddel(){
        global $_W,$_GPC;
        $list = $this->myGet($this->_qdlist,array('wid'=>$this->_wid,'id'=>intval($_GPC['id'])));
        $aa = $this->myGet($this->_orderg,array('id'=>$list['orderid'],'seropenid'=>$this->_openid,'wid'=>$this->_wid));
//        $data = array(
//            'first' => array(
//                'value' => $this->muban['prompt5'],
//                'color' => '#173177'
//            ),
//            'keyword1' => array(
//                'value' => $aa['project_name'],
//                'color' => '#173177'
//            ),
//            'keyword2' => array(
//                'value' => $aa['addtime'],
//                'color' => '#173177'
//            ),
//            'keyword3' => array(
//                'value' => '服务师取消订单',
//                'color' => '#173177'
//            ),
//            'remark' => array(
//                'value' => $this->muban['remarks5'],
//                'color' => '#173177'
//            )
//        );
//        $this->wob($aa['openid'],$this->muban['messageid5'],$data);
        $this->myUpdate($this->_orderg,array('state'=>1,'sername'=>null,'seropenid'=>null),array('wid'=>$this->_wid,'id'=>$list['orderid']),'AND');
        $this->myDelete($this->_qdlist,array('wid'=>$this->_wid,'id'=>intval($_GPC['id'])));
        message('取消成功',$this->createMobileUrl('qdlist'),'success');
    }
    //服务人员验证
    public function doMobileMstaff() {
        global $_W,$_GPC;
        
        $_share = $this->shareDate();
        if ($_GPC['qian'] ){
            require_once "pay/WxPay.JsApiPay.php";
            require_once "pay/WxPay.Exception.php";
            require_once "pay/WxPay.Config.php";
            require_once "pay/WxPay.Data.php";
            $tools = new JsApiPay();
//②、统一下单
            $input = new WxPayUnifiedOrder();
            $input->SetBody("支付押金");
            $input->SetAttach("支付押金");

            $input->SetAppid($_W['account']['key']);//公众账号ID
            if ($this->module['config']['mchid'] == null){
                $input->SetMch_id(WxPayConfig::MCHID);//商户号
                $input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
            }else{
                $input->SetMch_id($this->module['config']['mchid']);//商户号
                $input->SetOut_trade_no($this->module['config']['mchid'].date("YmdHis"));
            }
            $input->SetTotal_fee("1");
            $input->SetTime_start(date("YmdHis"));
            $input->SetTime_expire(date("YmdHis", time() + 600));
            $input->SetGoods_tag("支付押金");
            $input->SetNotify_url("http://paysdk.weixin.qq.com/example/notify.php");
            $input->SetTrade_type("JSAPI");
            $input->SetOpenid($this->_openid);
            $order = WxPayApi::unifiedOrder($input,$this->module['config']['mchid'],$this->module['config']['password']);
            $jsApiParameters = $tools->GetJsApiParameters($order,$this->module['config']['password']);
        }



        $list = $this->myGet($this->_staff,array('wid'=>$this->_wid,'openid'=>$this->_openid));
        $serg = $this->myGetall($this->_serverg,array('wid'=>$this->_wid),array('name','id'));
        if(((int)($list['state']) === 0)&&((int)($list['tgystate']) === 0)&&((int)($list['front'])==2)){
            message('请等待管理员审核',$this->createMobileUrl('member'),'success');
        }
        if (checksubmit()){
            $de = $this->myGet($this->_staff,array('wid'=>$this->_wid,'name'=>$_GPC['ba']['name'],'mobile'=>$_GPC['ba']['mobile'],'front'=>3));
            $xin = $this->myGet($this->_staff,array('wid'=>$this->_wid,'name'=>$_GPC['ba']['name'],'mobile'=>$_GPC['ba']['mobile'],'front'=>2));
            if($de||$xin){
                message('手机号已注册或正在审核中,请重新填写手机号',$this->createMobileUrl('Mstaff'),'info');
            }
            if($this->module['config']['fws'] == 1){
                $li = $this->myGet($this->_staff,array('wid'=>$this->_wid,'name'=>$_GPC['ba']['name'],'mobile'=>$_GPC['ba']['mobile'],'front'=>1));
                $data = $_GPC['ba'];
                $data['project'] = substr_replace($_GPC['ba']['project'],'',0,1);
                if ($_GPC['state'] != null){
                    $front = $this->myGet($this->_staff,array('wid'=>$this->_wid,'openid'=>$this->_openid,'front'=>2));
                    if($front){
                        message('已报名,请等待管理员审核',$this->createMobileUrl('member'),'info');
                    }else{
                        $this->myUpdate($this->_staff,array('state'=>0,'tgystate'=>0,'front'=>2),array('wid'=>$this->_wid,'openid'=>$this->_openid),'AND');
                        $data['updatetime'] = TIMESTAMP;
                        $succ = $this->myUpdate($this->_staff,$data,array('openid'=>$this->_openid,'wid'=>$this->_wid),'AND');
                        if ($succ){
                            message('修改成功,等待管理员审核',$this->createMobileUrl('member'),'success');
                        }else{
                            message('修改失败',$this->createMobileUrl('member'),'error');
                        }
                    }
                }
                $data['openid'] = $this->_openid;
                $data['avatar'] = $this->imgAvtar();
                if ($li){
                    $data['updatetime'] = TIMESTAMP;
                    $data['state'] = 1;
                    $data['tgystate'] = 1;
                    $data['front'] = 3;
                    $succ =$this->myUpdate($this->_staff,$data,array('wid'=>$this->_wid,'name'=>$_GPC['ba']['name'],'mobile'=>$_GPC['ba']['mobile']),'AND');
                    if ($succ){
                        message('验证成功',$this->createMobileUrl('member'),'success');
                    }else{
                        message('验证失败',$this->createMobileUrl('mstaff'),'error');
                    }
                }else{
                    $front = $this->myGet($this->_staff,array('wid'=>$this->_wid,'openid'=>$this->_openid,'front'=>2));
                    if($front){
                        message('已报名,请等待管理员审核',$this->createMobileUrl('member'),'info');
                    }else{
                        $data['wid'] = $this->_wid;
                        $data['addtime'] = TIMESTAMP;
                        $data['tgy'] = 1;
                        $data['front'] = 2;
                        $succ = $this->myInsert($this->_staff,$data);
                        if ($succ){
                            message('报名成功,等待管理员审核',$this->createMobileUrl('member'),'success');
                        }else{
                            message('报名失败',$this->createMobileUrl('mstaff'),'error');
                        }
                    }
                }
            }else{
                $li = $this->myGet($this->_staff,array('wid'=>$this->_wid,'name'=>$_GPC['ba']['name'],'mobile'=>$_GPC['ba']['mobile'],'front'=>1));
                if ($_GPC['state'] != null){
                    $data = $_GPC['ba'];
                    $data['project'] = substr_replace($_GPC['ba']['project'],'',0,1);
                    $data['updatetime'] = TIMESTAMP;
                    $succ = $this->myUpdate($this->_staff,$data,array('openid'=>$this->_openid,'wid'=>$this->_wid),'AND');
                    if ($succ){
                        message('修改成功',$this->createMobileUrl('member'),'success');
                    }else{
                        message('修改失败',$this->createMobileUrl('member'),'error');
                    }
                }
                if ($li){
                    $data = $_GPC['ba'];
                    $data['project'] = substr_replace($_GPC['ba']['project'],'',0,1);
                    $data['openid'] = $this->_openid;
                    $data['avatar'] = $this->imgAvtar();
                    $data['updatetime'] = TIMESTAMP;
                    $data['state'] = 1;
                    $data['tgy'] = 1;
                    $data['tgystate'] = 1;
                    $data['front'] = 3;
                    $succ =$this->myUpdate($this->_staff,$data,array('wid'=>$this->_wid,'name'=>$_GPC['ba']['name'],'mobile'=>$_GPC['ba']['mobile']),'AND');
                }else{
                    $aa = $this->myGet($this->_staff,array('wid'=>$this->_wid,'openid'=>$this->_openid));
                    $data = $_GPC['ba'];
                    $data['project'] = substr_replace($_GPC['ba']['project'],'',0,1);
                    $data['openid'] = $this->_openid;
                    $data['avatar'] = $this->imgAvtar();
                    $data['addtime'] = TIMESTAMP;
                    $data['state'] = 1;
                    $data['tgy'] = 1;
                    $data['wid'] = $this->_wid;
                    $data['tgystate'] = 1;
                    $data['front'] = 3;
                    if ($aa['qrcode'] == null){
                        $qr = $this->qrAdd();
                        $data = array_merge($data,$qr);
                    }
                    $succ = $this->myInsert($this->_staff,$data);
                }
                if ($succ){
                    message('报名成功',$this->createMobileUrl('member'),'success');
                }else{
                    message('报名失败',$this->createMobileUrl('mstaff'),'error');
                }
            }
        }

        include $this->template('mstaff');
    }
    //个人中心
    public function doMobileMember(){
        global $_GPC,$_W;
        
        $_share = $this->shareDate();
        $data = $this->myGet($this->_user,array('openid'=>$this->_openid));
        $list = $this->myGet($this->_staff,array('wid'=>$this->_wid,'openid'=>$this->_openid,'front'=>3));
        $tgy = $this->myGet($this->_staff,array('wid'=>$this->_wid,'openid'=>$this->_openid,'tgystate'=>1,'front'=>0));
        $use = $this->myGettop($this->_member,'*','','level DESC');
        foreach ($use as $k=>$v){
            $m = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename($this->_orderg)." WHERE wid = '".$this->_wid."' AND openid= '".$this->_openid."' AND state=3 OR wid = '".$this->_wid."' AND openid= '".$this->_openid."' AND state=4");
            if (($data['money'] >= $v['money']) or ($m >= $v['number'])){
                $this->myUpdate($this->_user,array('member'=>$v['title']),array('wid'=>$this->_wid,'openid'=>$this->_openid),'AND');
                break;
            }
        }
        $data = $this->myGet($this->_user,array('openid'=>$this->_openid));
        require $this->template('member');
    }
    //个人资料
    public function doMobileMemberdate(){
        global $_GPC,$_W;
        
        $_share = $this->shareDate();
        $list = $this->myGet($this->_user,array('wid'=>$this->_wid,'openid'=>$this->_openid));
        $moren = $this->myGet($this->_usrads,array('wid'=>$this->_wid,'openid'=>$this->_openid,'moren'=>1));
        include $this->template('memberdate');
    }
    //添加地址
    public function doMobileMemAddress(){
        global $_GPC,$_W;
        
        $_share = $this->shareDate();
        if (checksubmit()){
            $data = $_GPC['a'];
            $data['wid'] = $this->_wid;
            $data['address'] = str_replace(',','',$data['address']);
            $data['openid'] = $this->_openid;
            $data['addtime'] = TIMESTAMP;
//            if ($_GPC['a']['moren'] == null){
//                $data['moren'] = 0;
//            }
            $succ = $this->myInsert($this->_usrads,$data);
            if ($succ){
//                $da = $this->myGet($this->_usrads,array('wid'=>$this->_wid,'id'=>$succ));
//                if ($da['moren'] == 1){
//                    $this->myUpdate($this->_usrads,array('moren'=>0),array('openid'=>$this->_openid,'wid'=>$this->_wid),'AND');
//                    $succ = $this->myUpdate($this->_usrads,array('moren'=>1),array('wid'=>$this->_wid,'openid'=>$this->_openid,'id'=>$da['id']),'AND');
                if ($_GPC['id'] != null){
                    $this->succError($succ,$this->createMobileUrl('memberadssel',array('id'=>$_GPC['id'])));
                }
                $this->succError($succ,$this->createMobileUrl('memberadssel'));
//                }else{
//                    $this->succError($succ,$this->createMobileUrl('memberadsel'));
//                }
            }
        }
        include $this->template('memaddress');
    }
    //修改手机号
    public function doMobileMembermobile(){
        global $_GPC,$_W;
        
        $_share = $this->shareDate();
        if (checksubmit()){
            $data['mobile'] = $_GPC['mobile'];
            $succ = $this->myUpdate($this->_user,$data,array('wid'=>$this->_wid,'openid'=>$this->_openid),'AND');
            $this->succError($succ,$this->createMobileUrl('Memberdate'),1);
        }
        include $this->template('membermobile');
    }
    //选择默认地址
    public function doMobileMemberadssel(){
        global $_GPC,$_W;
        
        $_share = $this->shareDate();
        $list = $this->myGetall($this->_usrads,array('wid'=>$this->_wid,'openid'=>$this->_openid));
        if ($_GPC['moren']!=null){
            $this->myUpdate($this->_usrads,array('moren'=>0),array('wid'=>$this->_wid,'openid'=>$this->_openid),'AND');
            $succ = $this->myUpdate($this->_usrads,array('moren'=>1),array('wid'=>$this->_wid,'id'=>intval($_GPC['moren'])),'AND');
            echo $succ;exit;
        }
//        if (checksubmit()){
//            if($_GPC['id']!=null){
//                $this->myUpdate($this->_usrads,array('moren'=>0),array('openid'=>$this->_openid,'wid'=>$this->_wid),'AND');
//                $succ = $this->myUpdate($this->_usrads,array('moren'=>1),array('wid'=>$this->_wid,'openid'=>$this->_openid,'id'=>intval($_GPC['moren'])),'AND');
//                if ($succ) message('修改地址成功',$this->createMobileUrl('projectdetail',array('id'=>intval($_GPC['id']))),'success');
//            }
//            $this->myUpdate($this->_usrads,array('moren'=>0),array('openid'=>$this->_openid,'wid'=>$this->_wid),'AND');
//            $succ = $this->myUpdate($this->_usrads,array('moren'=>1),array('wid'=>$this->_wid,'openid'=>$this->_openid,'id'=>intval($_GPC['moren'])),'AND');
//            if ($succ) message('修改默认地址成功',$this->createMobileUrl('memberdate'),'success');
//        }
        include $this->template('memberadssel');
    }
    //编辑地址
    public function doMobileMemberadedit(){
        global $_GPC, $_W;
        
        $_share = $this->shareDate();
        $list = $this->myGet($this->_usrads, array('wid' => $this->_wid, 'id' => intval($_GPC['id'])));
        if (checksubmit()) {
            $data = $_GPC['a'];
            $data['address'] = str_replace(',','',$data['address']);
            $data['wid'] = $this->_wid;
            $data['openid'] = $this->_openid;
            $data['addtime'] = TIMESTAMP;
//            if ($_GPC['a']['moren'] == null) {
//                $data['moren'] = 0;
//            }
            $succ = $this->myUpdate($this->_usrads, $data, array('wid' => $this->_wid, 'id' => intval($_GPC['id'])), 'AND');
            if ($succ) {
//                $da = $this->myGet($this->_usrads, array('wid' => $this->_wid, 'id' => intval($_GPC['id'])));
//                if ($da['moren'] == 1) {
//                    $this->myUpdate($this->_usrads, array('moren' => 0), array('openid' => $this->_openid, 'wid' => $this->_wid), 'AND');
//                    $succ = $this->myUpdate($this->_usrads, array('moren' => 1), array('wid' => $this->_wid, 'openid' => $this->_openid, 'id' => $da['id']), 'AND');
//                    $this->succError($succ, $this->createMobileUrl('memberdate'), 1);
//                } else {
//                    $this->succError($succ, $this->createMobileUrl('memberdate'), 1);
//                }
                $this->succError($succ, $this->createMobileUrl('memberadssel'), 1);
            }
        }
        include $this->template('memberadedit');
    }
    //删除地址
    public function doMobileMemberaddel(){
        global $_GPC,$_W;
        $succ = $this->myDelete($this->_usrads,array('wid'=>$this->_wid,'id'=>intval($_GPC['id'])));
        if ($succ) message('删除成功',$this->createMobileUrl('memberadssel'),'success');
    }
    //服务师个人中心
    public function doMobileMstaffcore(){
        global $_W,$_GPC;
        
        $_share = $this->shareDate();
        $fwsk = pdo_fetchall("SELECT id,summoney FROM ".tablename($this->_orderg)." WHERE wid = '".$this->_wid."' AND seropenid= '".$this->_openid."' AND fws=0 AND state=3 OR  wid = '".$this->_wid."' AND seropenid= '".$this->_openid."' AND fws=0 AND state=4");
        $a = 0;
        if ($fwsk) {
            foreach ($fwsk as $k => $v) {
                $a = $a + ($v['summoney']-($v['summoney']*($this->module['config']['ptcc']/100)));
                $this->myUpdate($this->_orderg, array('fws' => 1), array('wid' => $this->_wid, 'id' => $v['id']), 'AND');
            }
            $ll = $this->myGet($this->_fwstgy,array('openid'=>$this->_openid,'wid'=>$this->_wid,'fwstgy'=>0,'state'=>0));
            if ($ll){
                $this->myUpdate($this->_fwstgy,array('money'=>($ll['money']+$a)),array('fwstgy'=>0,'wid'=>$this->_wid,'state'=>0,'openid'=>$this->_openid),'AND');
            }else{
                $dat['wid'] = $this->_wid;
                $dat['openid'] = $this->_openid;
                $dat['money'] = $a;
                $dat['addtime'] = TIMESTAMP;
                $this->myInsert($this->_fwstgy,$dat);
            }
        }
        $qian = $this->myGet($this->_fwstgy,array('openid'=>$this->_openid,'wid'=>$this->_wid,'fwstgy'=>0,'state'=>0));

        $yq = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename($this->_qdlist)." WHERE openid = :openid AND wid=:wid AND state =0",
            array(':openid'=>$this->_openid,':wid'=>$this->_wid));
        $wc = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename($this->_qdlist)." WHERE openid = :openid AND wid=:wid AND state =2",
            array(':openid'=>$this->_openid,':wid'=>$this->_wid));
        $or = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename($this->_orderg)." WHERE wid=:wid AND state =1",
            array(':wid'=>$this->_wid));
        $list =$this->myGet($this->_staff,array('wid'=>$this->_wid,'openid'=>$this->_openid,'front'=>3));
        $yj = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename($this->_orderg)." WHERE wid=:wid AND seropenid = :openid AND state = 3 OR wid=:wid AND seropenid = :openid AND state = 4",
            array(':wid'=>$this->_wid,':openid'=>$this->_openid));
        $tx = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename($this->_fwstgy).' WHERE state = 1 AND openid = :openid AND fwstgy=0 OR state=2 AND openid = :openid AND fwstgy=0',
            array(':wid'=>$this->_wid,':openid'=>$this->_openid));
        include $this->template('mstaffcore');
    }
    //提现
    public function doMobileTixian(){
        global $_W,$_GPC;
        
        $_share = $this->shareDate();
        if (($_GPC['state']== 'fws')&&($_GPC['money'] < $this->module['config']['txje'])){
            message('提现金额小于设定金额',$this->createMobileUrl('Mstaffcore'),'info');
        }else{
            if ($_GPC['state']== 'fws'){
                $aa = ($_GPC['money'] *($this->module['config']['fwssxf']/100));
                $data['shmoney'] =$_GPC['money'] - (substr(sprintf("%.3f",$aa),0,-1));
                $data['state'] = 1;
                $succ = $this->myUpdate($this->_fwstgy,$data,array('wid'=>$this->_wid,'state'=>0,'openid'=>$this->_openid,'fwstgy'=>0),'AND');
                if ($succ)message('提交成功,等待管理员审核',$this->createMobileUrl('Mstaffcore'),'success');
            }
        }
        if(($_GPC['state']== 'tgy')&&($_GPC['money'] < $this->module['config']['tgytxje'])) {
            message('提现金额小于设定金额', $this->createMobileUrl('extend'), 'info');
        }else{
            if($_GPC['state']== 'tgy'){
                $aa = ($_GPC['money'] *($this->module['config']['tgysxf']/100));
                $data['shmoney'] =$_GPC['money'] - (substr(sprintf("%.3f",$aa),0,-1));
                $data['state'] = 1;
                $succ = $this->myUpdate($this->_fwstgy,$data,array('wid'=>$this->_wid,'state'=>0,'openid'=>$this->_openid,'fwstgy'=>1),'AND');
                if ($succ)message('提交成功,等待管理员审核',$this->createMobileUrl('extend'),'success');
            }
        }
    }
    //提现查看
    public function doMobileTixianmx(){
        global $_GPC,$_W;
        
        $_share = $this->shareDate();
        if ($_GPC['state']!=null){
            if($_GPC['state'] == 'yjsh'){
                $list = pdo_fetchall('SELECT * FROM  '.tablename($this->_fwstgy).' WHERE wid=:wid AND openid = :openid AND state = 1 AND fwstgy=1', array(':wid'=>$this->_wid,':openid'=>$this->_openid));
                include $this->template('tixianmx');
            }else if($_GPC['state'] == 'yjwc'){
                $list = pdo_fetchall('SELECT * FROM  '.tablename($this->_fwstgy).' WHERE wid=:wid AND openid = :openid AND state = 2 AND fwstgy=1', array(':wid'=>$this->_wid,':openid'=>$this->_openid));
                include $this->template('tixianmx');
            }
        }else{
            $list = pdo_fetchall('SELECT * FROM  '.tablename($this->_fwstgy).' WHERE wid=:wid AND openid = :openid AND state = 1 AND fwstgy=0 OR wid=:wid AND openid = :openid AND state = 2 AND fwstgy=0', array(':wid'=>$this->_wid,':openid'=>$this->_openid));
            include $this->template('tixianmx');
        }
    }
    //提成中心
    public function doMobileFwsmoney(){
        global $_GPC,$_W;
        
        $_share = $this->shareDate();
        $nick = $this->nickName();
        $img = $this->imgAvtar();
        $list = pdo_fetchall('SELECT * FROM  '.tablename($this->_orderg).' WHERE wid=:wid AND seropenid = :openid AND state = 3 OR wid=:wid AND seropenid = :openid AND state = 4', array(':wid'=>$this->_wid,':openid'=>$this->_openid));
        require $this->template('fwsmoney');
    }

    public function doMobileTgymoney(){
        global $_GPC,$_W;
        
        $_share = $this->shareDate();
        $nick = $this->nickName();
        $img = $this->imgAvtar();
        $arr =array();
        $www = $this->myGetall($this->_user,array('wid'=>$this->_wid,'tgyopenid'=>$this->_openid));
        foreach ($www as $vv){
            $tgy = pdo_fetchall("SELECT * FROM ".tablename($this->_orderg)." WHERE wid = '".$this->_wid."' AND openid= '".$vv['openid']."' AND tgy=1 AND state=3 OR wid = '".$this->_wid."' AND openid= '".$vv['openid']."' AND tgy=1 AND state=4");
            array_push($arr,$tgy);
        }
        require $this->template('tgymoney');
    }
    //评价
    public function doMobileAccess(){
        global $_GPC,$_W;
        
        $_share = $this->shareDate();
        $list = $this->myGet($this->_orderg,array('id'=>intval($_GPC['id']),'wid'=>$this->_wid));
        $name = $this->myGet($this->_staff,array('wid'=>$this->_wid,'openid'=>$list['seropenid']));
        if (checksubmit()){
            $data = $_GPC['pj'];
            $data['wid'] = $this->_wid;
            $data['project_name'] = $list['project_name'];
            $data['name'] = $list['name'];
            $data['sername'] = $name['name'];
            $data['addtime'] = TIMESTAMP;
            if($data['content'] == ''){
                $data['content'] == $this->base['comment'];
            }
            $succ = $this->myInsert($this->_assess,$data);
            $this->myUpdate($this->_orderg,array('state'=>4),array('wid'=>$this->_wid,'id'=>$list['id']),'AND');
            if($succ){
                message('评价成功',$this->createMobileUrl('orderlist'),'success');
            }
        }
        require $this->template('access');
    }
    //推广员中心
    public function doMobileExtend(){
        global $_W,$_GPC;
        
        $_share = $this->shareDate();
        $arr =array();
        $www = $this->myGetall($this->_user,array('wid'=>$this->_wid,'tgyopenid'=>$this->_openid));
        foreach ($www as $vv){
            $fwsk = pdo_fetchall("SELECT id,summoney FROM ".tablename($this->_orderg)." WHERE wid = '".$this->_wid."' AND openid= '".$vv['openid']."' AND tgy=0 AND state=3 OR wid = '".$this->_wid."' AND openid= '".$vv['openid']."' AND tgy=0 AND state=4");
            array_push($arr,$fwsk);
        }
        $a = 0;
        if ($arr[0]) {
            foreach ($arr[0] as $k => $v) {
                $a = $a + (($v['summoney']*$this->module['config']['tgytc'])/100);
                $this->myUpdate($this->_orderg, array('tgy' => 1), array('wid' => $this->_wid, 'id' => $v['id']), 'AND');
            }
            $a = (substr(sprintf("%.3f",$a),0,-1));
            $ll = $this->myGet($this->_fwstgy,array('openid'=>$this->_openid,'wid'=>$this->_wid,'fwstgy'=>1,'state'=>0));
            if ($ll){
                $this->myUpdate($this->_fwstgy,array('money'=>($ll['money']+$a)),array('fwstgy'=>1,'state'=>0,'wid'=>$this->_wid,'openid'=>$this->_openid),'AND');
            }else{
                $dat['wid'] = $this->_wid;
                $dat['openid'] = $this->_openid;
                $dat['money'] = $a;
                $dat['addtime'] = TIMESTAMP;
                $dat['fwstgy'] = 1;
                $this->myInsert($this->_fwstgy,$dat);
            }
        }
        $qian = $this->myGet($this->_fwstgy,array('openid'=>$this->_openid,'wid'=>$this->_wid,'fwstgy'=>1,'state'=>0));
        $qrcode = $this->myGet($this->_staff,array('openid'=>$this->_openid,'wid'=>$this->_wid),array('qrcode','name','mobile'));
        include $this->template('extend');
    }

    public function doMobilePeople(){
        global $_GPC,$_W;
        
        $_share = $this->shareDate();
        if ($_GPC['open'] != null){
            $list = $this->myGetall($this->_user,array('tgyopenid'=>$this->_openid,'wid'=>$this->_wid));
            include $this->template('people');exit;
        }
        $list = $this->myGetall($this->_user,array('tgyopenid'=>$this->_openid,'wid'=>$this->_wid));
        include $this->template('people');
    }

    public function doMobileExtendsq(){
        global $_GPC,$_W;
        
        $_share = $this->shareDate();
        $list = $this->myGet($this->_staff,array('wid'=>$this->_wid,'openid'=>$this->_openid,'tgystate'=>0));
        if ($list&&(intval($list['tgystate']) === 0)){
            message('如已申请服务师或已提交申请,请等待管理员审核',$this->createMobileUrl('member'),'success');
        }
        if(checksubmit()){
            if ($this->module['config']['tgy'] == 1){
                if ($list) message('您已经提交申请,请等待管理员审核',$this->createMobileUrl('member'),'info');
                $data = $_GPC['tgy'];
                $data['avatar'] = $this->imgAvtar();
                $data['addtime'] = TIMESTAMP;
                $data['wid'] = $this->_wid;
                $data['openid'] =$this->_openid;
                $data['tgy'] = 1;
                $data['tgystate'] = 0;
                $succ = $this->myInsert($this->_staff,$data);
                if ($succ) message('提交成功,请等待管理员审核',$this->createMobileUrl('memberdate'),'success');
            }else{
                if ($list){
                    $aa = $this->myGet($this->_staff,array('wid'=>$this->_wid,'openid'=>$this->_openid));
                    $data = $_GPC['tgy'];
                    $data['avatar'] = $this->imgAvtar();
                    $data['updatetime'] = TIMESTAMP;
                    $data['wid'] = $this->_wid;
                    $data['openid'] =$this->_openid;
                    $data['tgy'] = 1;
                    $data['tgystate'] = 1;
                    if ($aa['qrcode'] == null){
                        $qr = $this->qrAdd();
                        $data = array_merge($data,$qr);
                    }
                    $succ = $this->myUpdate($this->_staff,$data,array('wid'=>$this->_wid,'openid'=>$this->_openid),'AND');
                    if ($succ) message('报名成功',$this->createMobileUrl('memberdate'),'success');
                }else{
                    $aa = $this->myGet($this->_staff,array('wid'=>$this->_wid,'openid'=>$this->_openid));
                    $data = $_GPC['tgy'];
                    $data['avatar'] = $this->imgAvtar();
                    $data['addtime'] = TIMESTAMP;
                    $data['wid'] = $this->_wid;
                    $data['openid'] =$this->_openid;
                    $data['tgy'] = 1;
                    $data['tgystate'] = 1;
                    if ($aa['qrcode'] == null){
                        $qr = $this->qrAdd();
                        $data = array_merge($data,$qr);
                    }
                    $succ = $this->myInsert($this->_staff,$data);
                    if ($succ) message('报名成功',$this->createMobileUrl('memberdate'),'success');
                }
            }

        }
        include $this->template('extendsq');
    }
    //常见问题
    public function doMobileQuestion(){
        global $_W,$_GPC;
        
        $_share = $this->shareDate();
        include $this->template('question');
    }



    //后台幻灯片
    public function doWebSlide(){
        global $_W,$_GPC;
        load()->func('tpl');
        $all = $this->myPager($this->_slide,$_GPC['page']);
        $list = $all['all'];
        $pager = $all['pager'];
        require $this->template('slide');
    }

    public function doWebSlidecc(){
        global $_W,$_GPC;
        load()->func('tpl');
        $list = $this->myGet($this->_slidecc,array('wid'=>$this->_wid));
        if(checksubmit()){
            if ($list){
                $data = $_GPC['cc'];
                $data['addtime'] = TIMESTAMP;
                $succ = $this->myUpdate($this->_slidecc,$data,array('wid'=>$this->_wid));
                $this->succError($succ,$this->createWebUrl('slidecc'),1);
            }else{
                $data = $_GPC['cc'];
                $data['wid'] = $this->_wid;
                $data['addtime'] = TIMESTAMP;
                $succ = $this->myInsert($this->_slidecc,$data);
                $this->succError($succ,$this->createWebUrl('slidecc'));
            }
        }
        require $this->template('slidecc');
    }

    public function doWebSlideadd(){
        global $_GPC,$_W;
        if(checksubmit()){
            $data = $_GPC['slide'];
            $data['wid'] = $this->_wid;
            $data['addtime'] = TIMESTAMP;
            $succ = $this->myInsert($this->_slide,$data);
            $this->succError($succ,$this->createWebUrl('slide'));
        }
        require $this->template('slideadd');
    }

    public function doWebSlideedit(){
        global $_GPC,$_W;
        $id = intval($_GPC['id']);
        $item = $this->myGet($this->_slide,array('id'=>$id,'wid'=>$this->_wid));
        if(checksubmit()){
            $data = $_GPC['slide'];
            $data['addtime'] = TIMESTAMP;
            $succ = $this->myUpdate($this->_slide,$data,array('wid'=>$this->_wid,'id'=>$id),'AND');
            $this->succError($succ,$this->createWebUrl('slide'),1);
        }
        require $this->template('slideedit');
    }

    public function doWebSlidedelete(){
        global $_GPC,$_W;
        $id = intval($_GPC['id']);
        $succ = $this->myDelete($this->_slide,array('id'=>$id,'wid'=>$this->_wid));
        $this->succError($succ,$this->createWebUrl('slide'),2);
    }
    //弹幕设置
    public function doWebBarrage(){
        global $_W,$_GPC;
        load()->func('tpl');
        if(checksubmit()){
            $data = $_GPC['dm'];
            $data['wid'] = $this->_wid;
            $data['addtime'] = TIMESTAMP;
            $succ = $this->myInsert($this->_barrage,$data);
            $this->succError($succ,$this->createWebUrl('barragelist'));
        }
        include $this->template('barrage');
    }

    public function doWebBarragelist(){
        global $_W,$_GPC;
        $list = $this->myGetall($this->_barrage,array('wid'=>$this->_wid));
        include $this->template('barragelist');
    }

    public function doWebBarrageedit(){
        global $_W,$_GPC;
        load()->func('tpl');
        $item = $this->myGet($this->_barrage,array('wid'=>$this->_wid,'id'=>intval($_GPC['id'])));
        if(checksubmit()){
            $data = $_GPC['dm'];
            $data['addtime'] = TIMESTAMP;
            $succ = $this->myUpdate($this->_barrage,$data,array('wid'=>$this->_wid,'id'=>intval($_GPC['id'])),'AND');
            $this->succError($succ,$this->createWebUrl('barragelist'),1);
        }
        include $this->template('barrageedit');
    }

    public function doWebBarragedel(){
        global $_W,$_GPC;
        $succ = $this->myDelete($this->_barrage,array('wid'=>$this->_wid,'id'=>intval($_GPC['id'])));
        $this->succError($succ,$this->createWebUrl('barragelist'),2);
    }

    public function doWebAudio(){
        global $_W,$_GPC;
        $mp3 = $this->myGet('xk_housekeepsetting',array('wid'=>$this->_wid));
        if(checksubmit()){
            load()->func('file');
            if (!empty($_FILES['mp']['name'])) {
                $mp = file_move($_FILES['mp']['tmp_name'],MODULE_ROOT.'/images/mp3'.$_W['uniacid'].'.mp3');
            }
            if ($mp3 == null){
                $data['mp3'] = MODULE_ROOT.'/images/mp3'.$_W['uniacid'].'.mp3';
                $data['wid'] = $this->_wid;
                $data['addtime'] = TIMESTAMP;
                $this->myInsert('xk_housekeepsetting',$data);
                message('上传成功',$this->createWebUrl('audio'),'success');
            }
            $data['mp3'] = MODULE_ROOT.'/images/mp3'.$_W['uniacid'].'.mp3';
            $data['addtime'] = TIMESTAMP;
            $this->myUpdate('xk_housekeepsetting',$data,array('wid'=>$this->_wid));
        }
        include $this->template('audio');
    }
    //后台服务师管理
    public function doWebWstaff() {
        global $_W,$_GPC;
        $filter = array(
            'name' => '姓名',
            'sex' => '性别',
            'mobile' => '手机号',
            'wei' => '未提佣金',
            'yi' => '已提佣金',
            'fwsmoney' => '实获佣金',
            'addtime' => '添加时间',
            'booking' => '是否为管理员',
            'state' => '是否验证',
            'backadmin' => '是否后台派单显示'
        );
        if ((!empty($_GPC['mobile']))&&(!empty($_GPC['name']))){
            $all =  $this->myPager($this->_staff,$_GPC['page'],"AND name= '".$_GPC['name']."'  AND mobile= '".$_GPC['mobile']."'",'id DESC','*'," WHERE wid='".$this->_wid."'  AND name= '".$_GPC['name']."' AND mobile= '".$_GPC['mobile']."'");
            $list = $all['all'];
            $pager = $all['pager'];
            $total = $all['total'];
            $sum = pdo_fetchcolumn("SELECT sum(tgymoney) FROM ".tablename($this->_staff)." WHERE wid='".$this->_wid."' AND name= '".$_GPC['name']."' AND mobile= '".$_GPC['mobile']."'");
            if ($_GPC['export'] != '') {
                $list = pdo_fetchall("SELECT * FROM ".tablename($this->_staff)." WHERE wid = '".$this->_wid."' AND name= '".$_GPC['name']."' AND mobile= '".$_GPC['mobile']."'");
                foreach($list as $k=>$v){
                    $a = $this->myGet($this->_fwstgy,array('openid'=>$v[openid],'state'=>0,'fwstgy'=>0));
                    $b = pdo_fetchcolumn("SELECT SUM(money) FROM ".tablename($this->_fwstgy)." WHERE state=2 AND openid='".$v[openid]."' AND fwstgy=0");
                    if ($a['money'] == null){
                        $list[$k]['wei'] = '0.00';
                    }else{
                        $list[$k]['wei'] = $a['money'];
                    }
                    if($b){
                        $list[$k]['yi'] = $b;
                    }else{
                        $list[$k]['yi'] = '0.00';
                    }
                }
                $this->cvsXin($filter,$list);exit;
            }
            require $this->template('wstaff');exit;
        }elseif(!empty($_GPC['mobile'])){
            $all =  $this->myPager($this->_staff,$_GPC['page']," AND  mobile= '".$_GPC['mobile']."'",'id DESC','*'," WHERE wid='".$this->_wid."'  AND mobile= '".$_GPC['mobile']."'");
            $list = $all['all'];
            $pager = $all['pager'];
            $total = $all['total'];
            $sum = pdo_fetchcolumn("SELECT sum(tgymoney) FROM ".tablename($this->_staff)." WHERE wid='".$this->_wid."'  AND mobile= '".$_GPC['mobile']."'");
            if ($_GPC['export'] != '') {
                $list = pdo_fetchall("SELECT * FROM ".tablename($this->_staff)." WHERE wid = '".$this->_wid."' AND mobile= '".$_GPC['mobile']."'");
                foreach($list as $k=>$v){
                    $a = $this->myGet($this->_fwstgy,array('openid'=>$v[openid],'state'=>0,'fwstgy'=>0));
                    $b = pdo_fetchcolumn("SELECT SUM(money) FROM ".tablename($this->_fwstgy)." WHERE state=2 AND openid='".$v[openid]."' AND fwstgy=0");
                    if ($a['money'] == null){
                        $list[$k]['wei'] = '0.00';
                    }else{
                        $list[$k]['wei'] = $a['money'];
                    }
                    if($b){
                        $list[$k]['yi'] = $b;
                    }else{
                        $list[$k]['yi'] = '0.00';
                    }
                }
                $this->cvsXin($filter,$list);exit;
            }
            require $this->template('wstaff');exit;
        }elseif(!empty($_GPC['name'])){
            $all =  $this->myPager($this->_staff,$_GPC['page']," AND  name= '".$_GPC['name']."'",'id DESC','*'," WHERE   wid='".$this->_wid."' AND name= '".$_GPC['name']."'");
            $list = $all['all'];
            $pager = $all['pager'];
            $total = $all['total'];
            $sum = pdo_fetchcolumn("SELECT sum(tgymoney) FROM ".tablename($this->_staff)." WHERE wid='".$this->_wid."'   AND name= '".$_GPC['name']."'");
            if ($_GPC['export'] != '') {
                $list = pdo_fetchall("SELECT * FROM ".tablename($this->_staff)." WHERE wid = '".$this->_wid."' AND name= '".$_GPC['name']."'");
                foreach($list as $k=>$v){
                    $a = $this->myGet($this->_fwstgy,array('openid'=>$v[openid],'state'=>0,'fwstgy'=>0));
                    $b = pdo_fetchcolumn("SELECT SUM(money) FROM ".tablename($this->_fwstgy)." WHERE state=2 AND openid='".$v[openid]."' AND fwstgy=0");
                    if ($a['money'] == null){
                        $list[$k]['wei'] = '0.00';
                    }else{
                        $list[$k]['wei'] = $a['money'];
                    }
                    if($b){
                        $list[$k]['yi'] = $b;
                    }else{
                        $list[$k]['yi'] = '0.00';
                    }
                }
                $this->cvsXin($filter,$list);exit;
            }
            require $this->template('wstaff');exit;
        }
        if ($_GPC['export'] != '') {
            $list = pdo_fetchall("SELECT * FROM ".tablename($this->_staff)." WHERE wid = '".$this->_wid."'");
            foreach($list as $k=>$v){
                $a = $this->myGet($this->_fwstgy,array('openid'=>$v[openid],'state'=>0,'fwstgy'=>0));
                $b = pdo_fetchcolumn("SELECT SUM(money) FROM ".tablename($this->_fwstgy)." WHERE state=2 AND openid='".$v[openid]."' AND fwstgy=0");
                if ($a['money'] == null){
                    $list[$k]['wei'] = '0.00';
                }else{
                    $list[$k]['wei'] = $a['money'];
                }
                if($b){
                    $list[$k]['yi'] = $b;
                }else{
                    $list[$k]['yi'] = '0.00';
                }
            }
            $this->cvsXin($filter,$list);exit;
        }
        $all = $this->myPager($this->_staff,$_GPC['page']);
        $list = $all['all'];
        $pager = $all['pager'];
        $total = $all['total'];
        $sum = pdo_fetchcolumn("SELECT sum(fwsmoney) FROM ".tablename($this->_staff)." WHERE wid='".$this->_wid."' AND state=1");
        include $this->template('wstaff');
    }

    public function doWebWstaffadd(){
        global $_W,$_GPC;
        load()->func('tpl');
        if(checksubmit()){
            $not = $this->myGet($this->_staff,array('wid'=>$this->_wid,'name'=>$_GPC['staff']['name']));
            if($not) message('姓名已经存在',$this->createWebUrl('wstaffadd'),'info');
            $not = $this->myGet($this->_staff,array('wid'=>$this->_wid,'mobile'=>$_GPC['staff']['mobile']));
            if($not) message('手机号已经存在',$this->createWebUrl('wstaffadd'),'info');
            $qr = $this->qrAdd();
            $data = $_GPC['staff'];
            $data['addtime'] = TIMESTAMP;
            $data['wid'] = $this->_wid;
            $data = array_merge($data,$qr);
            $data['tgy'] = 1;
            $data['front'] = 1;
            $data['backadmin'] =1;
            $succ = $this->myInsert($this->_staff,$data);
            $this->succError($succ,$this->createWebUrl('wstaff'));
        }
        include $this->template('wstaff-add');
    }

    public function doWebWstaffedit(){
        global $_W,$_GPC;
        $id = intval($_GPC['id']);
        $staff =  $this->myGet($this->_staff,array('id'=>$id,'wid'=>$this->_wid),array('name','mobile','sex','avatar','booking'));
        if(checksubmit()){
//            $not = $this->myGet($this->_staff,array('wid'=>$this->_wid,'name'=>$_GPC['staff']['name']));
//            if($not) message('姓名已经存在',$this->createWebUrl('wstaff'),'info');
            $data = $_GPC['staff'];
            $data['updatetime'] = TIMESTAMP;
            $succ = $this->myUpdate($this->_staff,$data,array('wid'=>$this->_wid,'id'=>$id),'AND');
            $this->succError($succ,$this->createWebUrl('wstaff'),1);
        }
        include $this->template('wstaff-edit');
    }

    public function doWebWstaffdelete(){
        global $_W,$_GPC;
        $id = intval($_GPC['id']);
        $sel = $this->myGet($this->_staff,array('id'=>$id,'wid'=>$this->_wid));
        $succ = $this->myDelete($this->_staff,array('id'=>$id,'wid'=>$this->_wid));
        $this->succError($succ,$this->createWebUrl('wstaff'),2);
    }

    public function doWebWstaffm(){
        global  $_W,$_GPC;
        $list = $this->myGetall($this->_staff,array('wid'=>$this->_wid,'booking'=>1));
        require $this->template('wstaffm');
    }

    public function doWebWstaffgl(){
        global  $_W,$_GPC;
        if($_GPC['id']!=null){
            $succ = $this->myUpdate($this->_staff,array('booking'=>1),array('wid'=>$this->_wid,'id'=>intval($_GPC['id'])),'AND');
            if ($succ) message('设置成功',$this->createWebUrl('wstaff'),'success');
        }
    }

    public function doWebWstaffgldel(){
        global  $_W,$_GPC;
        $succ = $this->myUpdate($this->_staff,array('booking'=>0),array('wid'=>$this->_wid,'id'=>intval($_GPC['id'])),'AND');
        if ($succ) message('取消成功',$this->createWebUrl('wstaff'),'success');
    }

    public function doWebStaffsh(){
        global $_GPC,$_W;
        $list =pdo_fetchall("SELECT * FROM ".tablename($this->_staff)." WHERE wid=:wid AND wechat!='' AND front=2",array(':wid'=>$this->_wid));
        include $this->template('staffsh');
    }
    //服务师报名审核
    public function doWebStaffshtg(){
        global $_GPC,$_W;
        $aa = $this->myGet($this->_staff,array('id'=>intval($_GPC['id']),'wid'=>$this->_wid));
        $dat['state'] = 1;
        $dat['updatetime'] = TIMESTAMP;
        $dat['tgy'] = 1;
        $dat['tgystate'] = 1;
        $dat['front'] =3;
        if ($aa['qrcode'] == null){
            $qr = $this->qrAdd();
            $dat = array_merge($dat,$qr);
        }
        $succ = $this->myUpdate($this->_staff,$dat,array('wid'=>$this->_wid,'id'=>intval($_GPC['id'])),'AND');
        if($succ){
            message('审核通过',$this->createWebUrl('staffsh'),'success');
        }else{
            message('审核失败',$this->createWebUrl('staffsh'),'info');
        }
    }
    //服务师推广员提现审核
    public function doWebStafftxsh(){
        global $_GPC,$_W;
        $pindex = max(1, $_GPC['page']);
        $psize = 15;
        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename($this->_fwstgy).' LEFT JOIN '.tablename($this->_staff).' ON '.tablename($this->_fwstgy)
            .'.openid='.tablename($this->_staff).'.openid WHERE '.tablename($this->_fwstgy).'.wid='.$this->_wid.' AND '.tablename($this->_fwstgy).'.state=1');
        $field = tablename($this->_staff).'.name,'.tablename($this->_staff).'.sex,'.tablename($this->_staff).'.mobile,'.tablename($this->_staff).'.wechat,'.tablename($this->_fwstgy).'.*';
        $sh = pdo_fetchall("SELECT ".$field." FROM ".tablename($this->_fwstgy).' LEFT JOIN '.tablename($this->_staff).' ON '.tablename($this->_fwstgy)
            .'.openid='.tablename($this->_staff).'.openid WHERE '.tablename($this->_fwstgy).'.wid='.$this->_wid.' AND '.tablename($this->_fwstgy).'.state=1 ORDER BY '
            .tablename($this->_fwstgy).'.id LIMIT '.($pindex - 1) * $psize . ",{$psize}");
        $pager = pagination($total, $pindex, $psize);
//        var_dump($sh);exit();
        include $this->template('stafftxsh');
    }

    public function doWebStafftxtj()
    {
        global $_GPC, $_W;
        $money = $this->myGet($this->_fwstgy, array('wid' => $this->_wid, 'id' => intval($_GPC['id'])));
        $sta = $this->myGet($this->_staff, array('openid' => $money['openid'], 'wid' => $this->_wid));
        if ($money['fwstgy'] == 1) {
            $succ = $this->paypeople($money['openid'], $money['shmoney']);
            if ($succ['return_code'] == 'SUCCESS') {
                if ($succ['err_code'] == 'NOTENOUGH'){
                    message('审核失败,帐号余额不足', $this->createWebUrl('stafftxsh'), 'error');
                }
                $this->myUpdate($this->_fwstgy, array('state' => 2,'updatetime'=>TIMESTAMP), array('id' => intval($_GPC['id']), 'wid' => $this->_wid), 'AND');
                $sum = $sta['tgymoney'] + $money['shmoney'];
                $su = $this->myUpdate($this->_staff, array('tgymoney' => $sum), array('wid' => $this->_wid, 'openid' => $money['openid']),'AND');
                if ($su) {
                    message('审核成功', $this->createWebUrl('stafftxsh'), 'success');
                }
            }else {
                message('审核失败,请查看参数设置中设置是否正确', $this->createWebUrl('stafftxsh'), 'error');
            }
        } else {
            $succ = $this->paypeople($money['openid'], $money['shmoney']);
            if ($succ['return_code'] == 'SUCCESS') {
                if ($succ['err_code'] == 'NOTENOUGH'){
                    message('审核失败,帐号余额不足', $this->createWebUrl('stafftxsh'), 'error');
                }
                $this->myUpdate($this->_fwstgy, array('state' => 2,'updatetime'=>TIMESTAMP), array('id' => intval($_GPC['id']), 'wid' => $this->_wid), 'AND');
                $sum = $sta['fwsmoney'] + $money['shmoney'];
                $su = $this->myUpdate($this->_staff, array('fwsmoney' => $sum), array('wid' => $this->_wid, 'openid' => $money['openid']),'AND');
                if ($su) {
                    message('审核成功', $this->createWebUrl('stafftxsh'), 'success');
                }
            }else {
                message('审核失败,请查看参数设置中设置是否正确', $this->createWebUrl('stafftxsh'), 'error');
            }
        }
    }
    //提现通过列表
    public function doWebStafftxshlist(){
        global $_W,$_GPC;
        $filter = array(
            'name' => '姓名',
            'sex' => '性别',
            'mobile' => '手机号',
            'wechat' => '微信号',
            'updatetime' => '审核通过时间',
            'fwstgy' => '岗位',
            'shmoney' => '提现金额',
        );
        if (!empty($_GPC['time'])) {
            $starttime = strtotime($_GPC['time']['start']);
            $endtime = strtotime($_GPC['time']['end']) + 86399;
        }
        if (intval(strtotime($_GPC['time']['end'])) === -28800){
            load()->func('tpl');
            if ((!empty($_GPC['mobile']))&&(!empty($_GPC['station']))){
                $staff = $this->myGet($this->_staff,array('wid'=>$this->_wid,'mobile'=>$_GPC['mobile']));
                if (trim($_GPC['station']) == '服务师'){
                    $all =  $this->myPager($this->_fwstgy,$_GPC['page']," AND openid= '".$staff['openid']."' AND state=2 AND fwstgy=0",'id DESC','*'," WHERE wid='".$this->_wid."' AND openid= '".$staff['openid']."' AND state=2 AND fwstgy=0");
                    $sh = $all['all'];
                    foreach( $sh as $k=>$v){
                        $sh[$k]['name'] = $staff['name'];
                        $sh[$k]['mobile'] = $staff['mobile'];
                        $sh[$k]['sex'] = $staff['sex'];
                        $sh[$k]['wechat'] = $staff['wechat'];
                    }
                    $sum = pdo_fetchcolumn("SELECT sum(shmoney) FROM ".tablename($this->_fwstgy)." WHERE wid='".$this->_wid."' AND openid= '".$staff['openid']."' AND state=2 AND fwstgy=0");
                    $pager = $all['pager'];
                    $total = $all['total'];
                }elseif(trim($_GPC['station']) == '推广员'){
                    $all =  $this->myPager($this->_fwstgy,$_GPC['page']," AND openid= '".$staff['openid']."' AND state=2 AND fwstgy=1",'id DESC','*'," WHERE wid='".$this->_wid."' AND openid= '".$staff['openid']."' AND state=2 AND fwstgy=1");
                    $sh = $all['all'];
                    foreach( $sh as $k=>$v){
                        $sh[$k]['name'] = $staff['name'];
                        $sh[$k]['mobile'] = $staff['mobile'];
                        $sh[$k]['sex'] = $staff['sex'];
                        $sh[$k]['wechat'] = $staff['wechat'];
                    }
                    $sum = pdo_fetchcolumn("SELECT sum(shmoney) FROM ".tablename($this->_fwstgy)." WHERE wid='".$this->_wid."' AND openid= '".$staff['openid']."' AND state=2 AND fwstgy=1");
                    $pager = $all['pager'];
                    $total = $all['total'];
                }
                if ($_GPC['export'] != '') {
                    $this->xinCvs($filter,$sh);exit;
                }
                require $this->template('stafftxshlist');exit;
            }elseif(!empty($_GPC['mobile'])){
                $staff = $this->myGet($this->_staff, array('wid' => $this->_wid, 'mobile' => $_GPC['mobile']));
                $all = $this->myPager($this->_fwstgy, $_GPC['page'], " AND openid= '" . $staff['openid'] . "' AND state=2", 'id DESC', '*', " WHERE wid='" . $this->_wid . "' AND openid= '" . $staff['openid'] . "' AND state=2");
                $sh = $all['all'];
                foreach ($sh as $k => $v) {
                    $sh[$k]['name'] = $staff['name'];
                    $sh[$k]['mobile'] = $staff['mobile'];
                    $sh[$k]['sex'] = $staff['sex'];
                    $sh[$k]['wechat'] = $staff['wechat'];
                }
                $sum = pdo_fetchcolumn("SELECT sum(shmoney) FROM " . tablename($this->_fwstgy) . " WHERE wid='" . $this->_wid . "' AND openid= '" . $staff['openid'] . "' AND state=2");
                $pager = $all['pager'];
                $total = $all['total'];
                if ($_GPC['export'] != '') {
                    $this->xinCvs($filter, $sh);
                    exit;
                }
                require $this->template('stafftxshlist');exit;
            }elseif(!empty($_GPC['station'])){
                if (trim($_GPC['station']) == '服务师'){
                    $all =  $this->myPager($this->_fwstgy,$_GPC['page']," AND state=2 AND fwstgy=0",'id DESC','*'," WHERE wid='".$this->_wid."' AND state=2 AND fwstgy=0");
                    $sh = $all['all'];
                    foreach($sh as $k=>$v){
                        $staff = $this->myGet($this->_staff, array('wid' => $this->_wid, 'openid' => $v['openid']));
                        $sh[$k]['name'] = $staff['name'];
                        $sh[$k]['mobile'] = $staff['mobile'];
                        $sh[$k]['sex'] = $staff['sex'];
                        $sh[$k]['wechat'] = $staff['wechat'];
                    }
                    $sum = pdo_fetchcolumn("SELECT sum(shmoney) FROM ".tablename($this->_fwstgy)." WHERE wid='".$this->_wid."' AND  state=2 AND fwstgy=0");
                    $pager = $all['pager'];
                    $total = $all['total'];
                    if ($_GPC['export'] != '') {
                        $this->xinCvs($filter,$sh);exit;
                    }
                }elseif(trim($_GPC['station']) == '推广员') {
                    $all =  $this->myPager($this->_fwstgy,$_GPC['page']," AND state=2 AND fwstgy=1",'id DESC','*'," WHERE wid='".$this->_wid."' AND state=2 AND fwstgy=1");
                    $sh = $all['all'];
                    foreach($sh as $k=>$v){
                        $staff = $this->myGet($this->_staff, array('wid' => $this->_wid, 'openid' => $v['openid']));
                        $sh[$k]['name'] = $staff['name'];
                        $sh[$k]['mobile'] = $staff['mobile'];
                        $sh[$k]['sex'] = $staff['sex'];
                        $sh[$k]['wechat'] = $staff['wechat'];
                    }
                    $sum = pdo_fetchcolumn("SELECT sum(shmoney) FROM ".tablename($this->_fwstgy)." WHERE wid='".$this->_wid."' AND  state=2 AND fwstgy=1");
                    $pager = $all['pager'];
                    $total = $all['total'];
                    if ($_GPC['export'] != '') {
                        $this->xinCvs($filter,$sh);exit;
                    }
                }
                require $this->template('stafftxshlist');exit;
            }
        }elseif(intval(strtotime($_GPC['time']['end'])) > 0){
            load()->func('tpl');
            if((!empty($_GPC['mobile']))&&(!empty($_GPC['station']))){
                $staff = $this->myGet($this->_staff,array('wid'=>$this->_wid,'mobile'=>$_GPC['mobile']));
                if (trim($_GPC['station']) == '服务师'){
                    $all =  $this->myPager($this->_fwstgy,$_GPC['page']," AND openid= '".$staff['openid']."' AND updatetime >= '".$starttime."' AND updatetime <= '".$endtime."' AND fwstgy=0 AND state=2",'id DESC','*'," WHERE wid='".$this->_wid."' AND openid= '".$staff['openid']."' AND updatetime >= '".$starttime."' AND updatetime <= '".$endtime."' AND fwstgy=0 AND state=2");
                    $sh = $all['all'];
                    foreach( $sh as $k=>$v){
                        $sh[$k]['name'] = $staff['name'];
                        $sh[$k]['mobile'] = $staff['mobile'];
                        $sh[$k]['sex'] = $staff['sex'];
                        $sh[$k]['wechat'] = $staff['wechat'];
                    }
                    $sum = pdo_fetchcolumn("SELECT sum(shmoney) FROM ".tablename($this->_fwstgy)." WHERE wid='".$this->_wid."'  AND openid= '".$staff['openid']."' AND updatetime >= '".$starttime."' AND updatetime <= '".$endtime."' AND fwstgy=0 AND state=2");
                    $pager = $all['pager'];
                    $total = $all['total'];
                }elseif(trim($_GPC['station']) == '推广员'){
                    $all =  $this->myPager($this->_fwstgy,$_GPC['page']," AND openid= '".$staff['openid']."' AND updatetime >= '".$starttime."' AND updatetime <= '".$endtime."' AND fwstgy=1 AND state=2",'id DESC','*'," WHERE wid='".$this->_wid."' AND openid= '".$staff['openid']."' AND updatetime >= '".$starttime."' AND updatetime <= '".$endtime."' AND fwstgy=1 AND state=2");
                    $sh = $all['all'];
                    foreach( $sh as $k=>$v){
                        $sh[$k]['name'] = $staff['name'];
                        $sh[$k]['mobile'] = $staff['mobile'];
                        $sh[$k]['sex'] = $staff['sex'];
                        $sh[$k]['wechat'] = $staff['wechat'];
                    }
                    $sum = pdo_fetchcolumn("SELECT sum(shmoney) FROM ".tablename($this->_fwstgy)." WHERE wid='".$this->_wid."'  AND openid= '".$staff['openid']."' AND updatetime >= '".$starttime."' AND updatetime <= '".$endtime."' AND fwstgy=1 AND state=2");
                    $pager = $all['pager'];
                    $total = $all['total'];
                }
                if ($_GPC['export'] != '') {
                    $this->xinCvs($filter,$sh);exit;
                }
                require $this->template('stafftxshlist');exit;
            }elseif(!empty($_GPC['mobile'])){
                $staff = $this->myGet($this->_staff,array('wid'=>$this->_wid,'mobile'=>$_GPC['mobile']));
                $all =  $this->myPager($this->_fwstgy,$_GPC['page']," AND openid= '".$staff['openid']."' AND updatetime >= '".$starttime."' AND updatetime <= '".$endtime."' AND  state=2",'id DESC','*'," WHERE wid='".$this->_wid."' AND openid= '".$staff['openid']."' AND updatetime >= '".$starttime."' AND updatetime <= '".$endtime."' AND state=2");
                $sh = $all['all'];
                foreach( $sh as $k=>$v){
                    $sh[$k]['name'] = $staff['name'];
                    $sh[$k]['mobile'] = $staff['mobile'];
                    $sh[$k]['sex'] = $staff['sex'];
                    $sh[$k]['wechat'] = $staff['wechat'];
                }
                $sum = pdo_fetchcolumn("SELECT sum(shmoney) FROM ".tablename($this->_fwstgy)." WHERE wid='".$this->_wid."'  AND openid= '".$staff['openid']."' AND updatetime >= '".$starttime."' AND updatetime <= '".$endtime."' AND  state=2");
                $pager = $all['pager'];
                $total = $all['total'];
                if ($_GPC['export'] != '') {
                    $this->xinCvs($filter,$sh);exit;
                }
                require $this->template('stafftxshlist');exit;
            }elseif(!empty($_GPC['station'])) {
                if (trim($_GPC['station']) == '服务师'){
                    $all =  $this->myPager($this->_fwstgy,$_GPC['page']," AND updatetime >= '".$starttime."' AND updatetime <= '".$endtime."' AND fwstgy=0 AND state=2",'id DESC','*'," WHERE wid='".$this->_wid."' AND updatetime >= '".$starttime."' AND updatetime <= '".$endtime."' AND fwstgy=0 AND state=2");
                    $sh = $all['all'];
                    foreach($sh as $k=>$v){
                        $staff = $this->myGet($this->_staff, array('wid' => $this->_wid, 'openid' => $v['openid']));
                        $sh[$k]['name'] = $staff['name'];
                        $sh[$k]['mobile'] = $staff['mobile'];
                        $sh[$k]['sex'] = $staff['sex'];
                        $sh[$k]['wechat'] = $staff['wechat'];
                    }
                    $sum = pdo_fetchcolumn("SELECT sum(shmoney) FROM ".tablename($this->_fwstgy)." WHERE wid='".$this->_wid."' AND updatetime >= '".$starttime."' AND updatetime <= '".$endtime."' AND fwstgy=0 AND state=2");
                    $pager = $all['pager'];
                    $total = $all['total'];
                }elseif(trim($_GPC['station']) == '推广员'){
                    $all =  $this->myPager($this->_fwstgy,$_GPC['page']," AND updatetime >= '".$starttime."' AND updatetime <= '".$endtime."' AND fwstgy=1 AND state=2",'id DESC','*'," WHERE wid='".$this->_wid."' AND updatetime >= '".$starttime."' AND updatetime <= '".$endtime."' AND fwstgy=1 AND state=2");
                    $sh = $all['all'];
                    foreach($sh as $k=>$v){
                        $staff = $this->myGet($this->_staff, array('wid' => $this->_wid, 'openid' => $v['openid']));
                        $sh[$k]['name'] = $staff['name'];
                        $sh[$k]['mobile'] = $staff['mobile'];
                        $sh[$k]['sex'] = $staff['sex'];
                        $sh[$k]['wechat'] = $staff['wechat'];
                    }
                    $sum = pdo_fetchcolumn("SELECT sum(shmoney) FROM ".tablename($this->_fwstgy)." WHERE wid='".$this->_wid."' AND updatetime >= '".$starttime."' AND updatetime <= '".$endtime."' AND fwstgy=1 AND state=2");
                    $pager = $all['pager'];
                    $total = $all['total'];
                }
                if ($_GPC['export'] != '') {
                    $this->xinCvs($filter,$sh);exit;
                }
                require $this->template('stafftxshlist');exit;
            }else{
                $all =  $this->myPager($this->_fwstgy,$_GPC['page']," AND updatetime >= '".$starttime."' AND updatetime <= '".$endtime."' AND  state=2",'id DESC','*'," WHERE wid='".$this->_wid."' AND updatetime >= '".$starttime."' AND updatetime <= '".$endtime."' AND state=2");
                $sh = $all['all'];
                foreach($sh as $k=>$v){
                    $staff = $this->myGet($this->_staff, array('wid' => $this->_wid, 'openid' => $v['openid']));
                    $sh[$k]['name'] = $staff['name'];
                    $sh[$k]['mobile'] = $staff['mobile'];
                    $sh[$k]['sex'] = $staff['sex'];
                    $sh[$k]['wechat'] = $staff['wechat'];
                }
                $sum = pdo_fetchcolumn("SELECT sum(shmoney) FROM ".tablename($this->_fwstgy)." WHERE wid='".$this->_wid."' AND updatetime >= '".$starttime."' AND updatetime <= '".$endtime."' AND state=2");
                $pager = $all['pager'];
                $total = $all['total'];
                if ($_GPC['export'] != '') {
                    $this->xinCvs($filter,$sh);exit;
                }
                require $this->template('stafftxshlist');exit;
            }
        }
        load()->func('tpl');
        $pindex = max(1, $_GPC['page']);
        $psize = 15;
        $sum = pdo_fetchcolumn("SELECT sum(shmoney) FROM ".tablename($this->_fwstgy)." WHERE wid='".$this->_wid."' AND state = 2");
        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename($this->_fwstgy).' LEFT JOIN '.tablename($this->_staff).' ON '.tablename($this->_fwstgy)
            .'.openid='.tablename($this->_staff).'.openid WHERE '.tablename($this->_fwstgy).'.wid='.$this->_wid.' AND '.tablename($this->_fwstgy).'.state=2');
        $field = tablename($this->_staff).'.name,'.tablename($this->_staff).'.sex,'.tablename($this->_staff).'.mobile,'.tablename($this->_staff).'.wechat,'.tablename($this->_fwstgy).'.*';
        $sh = pdo_fetchall("SELECT ".$field." FROM ".tablename($this->_fwstgy).' LEFT JOIN '.tablename($this->_staff).' ON '.tablename($this->_fwstgy)
            .'.openid='.tablename($this->_staff).'.openid WHERE '.tablename($this->_fwstgy).'.wid='.$this->_wid.' AND '.tablename($this->_fwstgy).'.state=2 ORDER BY '
            .tablename($this->_fwstgy).'.id LIMIT '.($pindex - 1) * $psize . ",{$psize}");
        $pager = pagination($total, $pindex, $psize);
        if ($_GPC['export'] != '') {
            $this->xinCvs($filter,$sh);exit;
        }
        include $this->template('stafftxshlist');
    }
    //后台服务管理
    public function doWebServerg() {
        global $_W,$_GPC;
        $all = $this->myPager($this->_serverg,$_GPC['page']);
        $serverg = $all['all'];
        $pager = $all['pager'];
        require $this->template('serverg');
    }
    //服务添加
    public function doWebServergadd(){
        global $_W,$_GPC;
        if(checksubmit()){
            $not = $this->myGet($this->_serverg,array('wid'=>$this->_wid,'name'=>$_GPC['serv']['name']));
            if($not) message('分类名称已经存在',$this->createWebUrl('servergadd'),'info');
            $data = $_GPC['serv'];
            $data['wid'] = $this->_wid;
            $data['addtime'] = TIMESTAMP;
            $succ = $this->myInsert($this->_serverg,$data);
            $this->succError($succ,$this->createWebUrl('serverg'));
        }
        require $this->template('servergadd');
    }
    //服务编辑
    public function doWebServergedit(){
        global $_W,$_GPC;
        $sel =  $this->myGet($this->_serverg,array('id'=>intval($_GPC['id']),'wid'=>$this->_wid),array('name','icon','top','content'));
        if(checksubmit()){
//            $not = $this->myGet($this->_serverg,array('wid'=>$this->_wid,'name'=>$_GPC['serv']['name']));
//            if($not) message('分类名称已经存在',$this->createWebUrl('serverg'),'info');
            $data =  $_GPC['serv'];
            $data['updatetime'] = TIMESTAMP;
            $succ = $this->myUpdate($this->_serverg,$data,array('wid'=>$this->_wid,'id'=>intval($_GPC['id'])),'AND');
            $this->succError($succ,$this->createWebUrl('serverg'),1);
        }
        require $this->template('servergedit');
    }
    //服务删除
    public function doWebServergdelete(){
        global $_GPC,$_W;
        $id = intval($_GPC['id']);
        $suc  = $this->myDelete($this->_project,array('serverg_id'=>$id,'wid'=>$this->_wid));
        $succ  = $this->myDelete($this->_serverg,array('id'=>$id,'wid'=>$this->_wid));
        $this->succError($succ,$this->createWebUrl('serverg'),2);
    }
    //项目
    public function doWebServergproject(){
        global $_W,$_GPC;
        if($_GPC['id']!=null){
            $id = intval($_GPC['id']);
            $pindex = max(1,intval($_GPC['page']));
            $psize = 15;
            $total = $this->myGetnum($this->_project," WHERE wid = :wid AND serverg_id = ".$id,array(':wid'=>$_W['uniacid']));
            $allpro = $this->myGetsel($this->_project,'*','id',$pindex,$psize,' AND serverg_id ='.$id);
            $pager = pagination($total,$pindex,$psize);
            foreach ($allpro as $k => $v){
                $data = tablename($this->_serverg).".id = ".tablename($this->_project).".serverg_id WHERE ".tablename($this->_serverg).".id = ".
                    $allpro[$k]['serverg_id']." AND ".tablename($this->_project).".wid = ".$this->_wid;
                $val = $this->myGetlian(tablename($this->_serverg).'.name',$this->_serverg,$this->_project,$data);
                $allpro[$k]['sername'] = $val['name'];
            }
            require  $this->template('servergproject');
        }
        $all = $this->myPager($this->_project,$_GPC['page']);
        $allpro = $all['all'];
        $pager = $all['pager'];
        foreach ($allpro as $k => $v){
            $data = tablename($this->_serverg).".id = ".tablename($this->_project).".serverg_id WHERE ".tablename($this->_serverg).".id = ".
                $allpro[$k]['serverg_id']." AND ".tablename($this->_project).".wid = ".$this->_wid;
            $val = $this->myGetlian(tablename($this->_serverg).'.name',$this->_serverg,$this->_project,$data);
            $allpro[$k]['sername'] = $val['name'];
        }
        require  $this->template('servergproject');
    }
    //项目添加
    public function doWebServergprojectadd(){
        global $_W,$_GPC;
        $list = $this->myGetall($this->_serverg,array('wid'=>$this->_wid));
        if(checksubmit()){
            $not = $this->myGet($this->_project,array('wid'=>$this->_wid,'name'=>$_GPC['pro']['name']));
            if($not) message('项目名称已经存在',$this->createWebUrl('project'),'info');
            $data = $_GPC['pro'];
            $data['wid'] = $this->_wid;
            $data['addtime'] = TIMESTAMP;
            $succ = $this->myInsert($this->_project,$data);
            $this->succError($succ,$this->createWebUrl('servergproject'));
        }
        require $this->template('servergprojectadd');
    }
    //项目编辑
    public function doWebServergprojectedit(){
        global $_W,$_GPC;
        $sel =  $this->myGet($this->_project,array('id'=>intval($_GPC['id']),'wid'=>$this->_wid));
        $list = $this->myGetall($this->_serverg,array('wid'=>$this->_wid),array('id','name'));
        if(checksubmit()){
//            $not = $this->myGet($this->_project,array('wid'=>$this->_wid,'name'=>$_GPC['pro']['name']));
//            if($not) message('项目名称已经存在',$this->createWebUrl('servergproject'),'info');
            $data = $_GPC['pro'];
            $data['updatetime'] = TIMESTAMP;
            $succ = $this->myUpdate($this->_project,$data,array('wid'=>$this->_wid,'id'=>intval($_GPC['id'])),'AND');
            $this->succError($succ,$this->createWebUrl('servergproject'),1);
        }
        require $this->template('servergprojectedit');
    }
    //项目删除
    public function doWebServergprojectdelete(){
        global $_W,$_GPC;
        $id = intval($_GPC['id']);
        $succ = $this->myDelete($this->_project,array('id'=>$id,'wid'=>$this->_wid));
        $this->succError($succ,$this->createWebUrl('servergproject'),2);
    }
    //后台订单列表
    public function doWebOrderg(){
        global $_GPC,$_W;
        if (($_GPC['qian'] != null)&&($_GPC['list']=='wfk'))  {
            $user = $this->myUpdate($this->_orderg,array('summoney'=>$_GPC['qian'],'wid'=>$this->_wid),array('id'=>$_GPC['idid'],'wid'=>$this->_wid),'AND');
            $json = json_encode($user);
            echo $json;exit;
        }
        $filter = array(
            'name' => '姓名',
            'mobile' => '手机号',
            'project_name' => '服务项目',
            'addtime' => '服务时间',
            'address' => '服务地址',
            'content' => '备注',
            'summoney' => '服务价格',
            'state' => '订单状态',
            'sername' => '服务人员',
        );
        $peo = $this->myGetall($this->_staff,array('wid'=>$this->_wid,'backadmin'=>1,'state'=>1));
        if (!empty($_GPC['time'])) {
            $starttime = strtotime($_GPC['time']['start']);
            $endtime = strtotime($_GPC['time']['end']) + 86399;
        }

        if ($_GPC['list']=='wfk'){
            if (intval(strtotime($_GPC['time']['end'])) === -28800){
                if ((!empty($_GPC['mobile']))&&(!empty($_GPC['name']))){
                    $all =  $this->myPager($this->_orderg,$_GPC['page'],"AND name= '".$_GPC['name']."' AND state=0 AND mobile= '".$_GPC['mobile']."'",'id DESC','name,mobile,project_name,addtime,address,content,summoney,state,sername'," WHERE wid=:wid AND state=0 AND name= '".$_GPC['name']."' AND mobile= '".$_GPC['mobile']."'");
                    $list = $all['all'];
                    $pager = $all['pager'];
                    $total = $all['total'];
                    if ($_GPC['export'] != '') {
                        $list = pdo_fetchall("SELECT name,mobile,project_name,addtime,address,content,summoney,state,sername FROM ".tablename($this->_orderg)." WHERE wid = '".$this->_wid."' AND name= '".$_GPC['name']."' AND state=0 AND  mobile= '".$_GPC['mobile']."'");
                        $this->cvsDo($filter,$list);exit;
                    }
                    require $this->template('orderg');exit;
                }elseif(!empty($_GPC['mobile'])){
                    $all =  $this->myPager($this->_orderg,$_GPC['page']," AND state=0 AND mobile= '".$_GPC['mobile']."'",'id DESC','name,mobile,project_name,addtime,address,content,summoney,state,sername'," WHERE wid=:wid AND state=0  AND mobile= '".$_GPC['mobile']."'");
                    $list = $all['all'];
                    $pager = $all['pager'];
                    $total = $all['total'];
                    if ($_GPC['export'] != '') {
                        $list = pdo_fetchall("SELECT name,mobile,project_name,addtime,address,content,summoney,state,sername FROM ".tablename($this->_orderg)." WHERE  wid = '".$this->_wid."' AND state=0  AND  mobile= '".$_GPC['mobile']."'");
                        $this->cvsDo($filter,$list);exit;
                    }
                    require $this->template('orderg');exit;
                }elseif(!empty($_GPC['name'])){
                    $all =  $this->myPager($this->_orderg,$_GPC['page']," AND state=0 AND name= '".$_GPC['name']."'",'id DESC','name,mobile,project_name,addtime,address,content,summoney,state,sername'," WHERE  state=0 AND wid=:wid AND name= '".$_GPC['name']."'");
                    $list = $all['all'];
                    $pager = $all['pager'];
                    $total = $all['total'];
                    if ($_GPC['export'] != '') {
                        $list = pdo_fetchall("SELECT name,mobile,project_name,addtime,address,content,summoney,state,sername FROM ".tablename($this->_orderg)." WHERE  wid = '".$this->_wid."' AND state=0  AND name= '".$_GPC['name']."'");
                        $this->cvsDo($filter,$list);exit;
                    }
                    require $this->template('orderg');exit;
                }
            }elseif(intval(strtotime($_GPC['time']['end'])) > 0){
                if((!empty($_GPC['mobile']))&&(!empty($_GPC['name']))){
                    $all =  $this->myPager($this->_orderg,$_GPC['page']," AND state=0 AND name= '".$_GPC['name']."' AND mobile= '".$_GPC['mobile']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."'",'id DESC','name,mobile,project_name,addtime,address,content,summoney,state,sername'," WHERE wid=:wid AND name= '".$_GPC['name']."' AND mobile= '".$_GPC['mobile']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."' AND state=0 ");
                    $list = $all['all'];
                    $pager = $all['pager'];
                    $total = $all['total'];
                    if ($_GPC['export'] != '') {
                        $list = pdo_fetchall("SELECT name,mobile,project_name,addtime,address,content,summoney,state,sername FROM ".tablename($this->_orderg)." WHERE wid = '".$this->_wid."' AND name= '".$_GPC['name']."' AND mobile= '".$_GPC['mobile']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."' AND state=0 ");
                        $this->cvsDo($filter,$list);exit;
                    }
                    require $this->template('orderg');exit;
                }elseif(!empty($_GPC['mobile'])){
                    $all =  $this->myPager($this->_orderg,$_GPC['page']," AND state=0  AND mobile= '".$_GPC['mobile']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."'",'id DESC','name,mobile,project_name,addtime,address,content,summoney,state,sername'," WHERE wid=:wid AND mobile= '".$_GPC['mobile']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."'  AND state=0 ");
                    $list = $all['all'];
                    $pager = $all['pager'];
                    $total = $all['total'];
                    if ($_GPC['export'] != '') {
                        $list = pdo_fetchall("SELECT name,mobile,project_name,addtime,address,content,summoney,state,sername FROM ".tablename($this->_orderg)." WHERE wid = '".$this->_wid."' AND mobile= '".$_GPC['mobile']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."' AND state=0 ");
                        $this->cvsDo($filter,$list);exit;
                    }
                    require $this->template('orderg');exit;
                }elseif(!empty($_GPC['name'])) {
                    $all = $this->myPager($this->_orderg, $_GPC['page'], " AND state=0  AND name= '".$_GPC['name']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."'", 'id DESC', 'name,mobile,project_name,addtime,address,content,summoney,state,sername'," WHERE wid=:wid AND name= '".$_GPC['name']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."' AND state=0 ");
                    $list = $all['all'];
                    $pager = $all['pager'];
                    $total = $all['total'];
                    if ($_GPC['export'] != '') {
                        $list = pdo_fetchall("SELECT name,mobile,project_name,addtime,address,content,summoney,state,sername FROM " . tablename($this->_orderg) . " WHERE wid = '" . $this->_wid . "' AND name= '".$_GPC['name']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."' AND state=0 ");
                        $this->cvsDo($filter, $list);
                        exit;
                    }
                    require $this->template('orderg');
                    exit;
                }else{
                    $all = $this->myPager($this->_orderg, $_GPC['page'], " AND state=0  AND  atime >= '".$starttime."' AND atime <= '".$endtime."'", 'id DESC', 'name,mobile,project_name,addtime,address,content,summoney,state,sername'," WHERE wid=:wid AND  atime >= '".$starttime."' AND atime <= '".$endtime."' AND state=0 ");
                    $list = $all['all'];
                    $pager = $all['pager'];
                    $total = $all['total'];
                    if ($_GPC['export'] != '') {
                        $list = pdo_fetchall("SELECT name,mobile,project_name,addtime,address,content,summoney,state,sername FROM " . tablename($this->_orderg) . " WHERE wid = '" . $this->_wid . "' AND  atime >= '".$starttime."' AND atime <= '".$endtime."' AND state=0 ");
                        $this->cvsDo($filter, $list);
                        exit;
                    }
                    require $this->template('orderg');
                    exit;
                }
            }
            $all =  $this->myPager($this->_orderg,$_GPC['page'],' AND state = 0','id','*',' WHERE wid=:wid AND state = 0');
            $list = $all['all'];
            $pager = $all['pager'];
            $total = $all['total'];
            require $this->template('orderg');exit;
        }
        if ($_GPC['list']=='wpd'){
            if (intval(strtotime($_GPC['time']['end'])) === -28800){
                if ((!empty($_GPC['mobile']))&&(!empty($_GPC['name']))){
                    $all =  $this->myPager($this->_orderg,$_GPC['page'],"AND name= '".$_GPC['name']."' AND state=1 AND mobile= '".$_GPC['mobile']."'",'id DESC','id,name,mobile,project_name,addtime,address,content,summoney,state,sername'," WHERE wid='".$this->_wid."' AND state=1 AND name= '".$_GPC['name']."' AND mobile= '".$_GPC['mobile']."'");
                    $list = $all['all'];
                    $pager = $all['pager'];
                    $total = $all['total'];
                    if ($_GPC['export'] != '') {
                        $list = pdo_fetchall("SELECT name,mobile,project_name,addtime,address,content,summoney,state,sername FROM ".tablename($this->_orderg)." WHERE wid = '".$this->_wid."' AND name= '".$_GPC['name']."' AND state=1 AND  mobile= '".$_GPC['mobile']."'");
                        $this->cvsDo($filter,$list);exit;
                    }
                    require $this->template('orderg');exit;
                }elseif(!empty($_GPC['mobile'])){
                    $all =  $this->myPager($this->_orderg,$_GPC['page']," AND state=1 AND mobile= '".$_GPC['mobile']."'",'id DESC','id,name,mobile,project_name,addtime,address,content,summoney,state,sername'," WHERE wid='".$this->_wid."' AND state=1  AND mobile= '".$_GPC['mobile']."'");
                    $list = $all['all'];
                    $pager = $all['pager'];
                    $total = $all['total'];
                    if ($_GPC['export'] != '') {
                        $list = pdo_fetchall("SELECT name,mobile,project_name,addtime,address,content,summoney,state,sername FROM ".tablename($this->_orderg)." WHERE  wid = '".$this->_wid."' AND state=1  AND  mobile= '".$_GPC['mobile']."'");
                        $this->cvsDo($filter,$list);exit;
                    }
                    require $this->template('orderg');exit;
                }elseif(!empty($_GPC['name'])){
                    $all =  $this->myPager($this->_orderg,$_GPC['page']," AND state=1 AND name= '".$_GPC['name']."'",'id DESC','id,name,mobile,project_name,addtime,address,content,summoney,state,sername'," WHERE state=1  AND  wid='".$this->_wid."' AND name= '".$_GPC['name']."'");
                    $list = $all['all'];
                    $pager = $all['pager'];
                    $total = $all['total'];
                    if ($_GPC['export'] != '') {
                        $list = pdo_fetchall("SELECT name,mobile,project_name,addtime,address,content,summoney,state,sername FROM ".tablename($this->_orderg)." WHERE  wid = '".$this->_wid."' AND state=1  AND name= '".$_GPC['name']."'");
                        $this->cvsDo($filter,$list);exit;
                    }
                    require $this->template('orderg');exit;
                }
            }elseif(intval(strtotime($_GPC['time']['end'])) > 0){
                if((!empty($_GPC['mobile']))&&(!empty($_GPC['name']))){
                    $all =  $this->myPager($this->_orderg,$_GPC['page']," AND state=1 AND name= '".$_GPC['name']."' AND mobile= '".$_GPC['mobile']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."'",'id DESC','id,name,mobile,project_name,addtime,address,content,summoney,state,sername'," WHERE wid='".$this->_wid."' AND name= '".$_GPC['name']."' AND mobile= '".$_GPC['mobile']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."' AND state=1 ");
                    $list = $all['all'];
                    $pager = $all['pager'];
                    $total = $all['total'];
                    if ($_GPC['export'] != '') {
                        $list = pdo_fetchall("SELECT name,mobile,project_name,addtime,address,content,summoney,state,sername FROM ".tablename($this->_orderg)." WHERE wid = '".$this->_wid."' AND name= '".$_GPC['name']."' AND mobile= '".$_GPC['mobile']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."' AND state=1 ");
                        $this->cvsDo($filter,$list);exit;
                    }
                    require $this->template('orderg');exit;
                }elseif(!empty($_GPC['mobile'])){
                    $all =  $this->myPager($this->_orderg,$_GPC['page']," AND state=1  AND mobile= '".$_GPC['mobile']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."'",'id DESC','id,name,mobile,project_name,addtime,address,content,summoney,state,sername'," WHERE wid='".$this->_wid."' AND mobile= '".$_GPC['mobile']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."'  AND state=1 ");
                    $list = $all['all'];
                    $pager = $all['pager'];
                    $total = $all['total'];
                    if ($_GPC['export'] != '') {
                        $list = pdo_fetchall("SELECT name,mobile,project_name,addtime,address,content,summoney,state,sername FROM ".tablename($this->_orderg)." WHERE wid = '".$this->_wid."' AND mobile= '".$_GPC['mobile']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."' AND state=1 ");
                        $this->cvsDo($filter,$list);exit;
                    }
                    require $this->template('orderg');exit;
                }elseif(!empty($_GPC['name'])) {
                    $all = $this->myPager($this->_orderg, $_GPC['page'], " AND state=1  AND name= '".$_GPC['name']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."'", 'id DESC', 'id,name,mobile,project_name,addtime,address,content,summoney,state,sername'," WHERE wid='".$this->_wid."' AND name= '".$_GPC['name']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."' AND state=1 ");
                    $list = $all['all'];
                    $pager = $all['pager'];
                    $total = $all['total'];
                    if ($_GPC['export'] != '') {
                        $list = pdo_fetchall("SELECT name,mobile,project_name,addtime,address,content,summoney,state,sername FROM " . tablename($this->_orderg) . " WHERE wid = '" . $this->_wid . "' AND name= '".$_GPC['name']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."' AND state=1 ");
                        $this->cvsDo($filter, $list);
                        exit;
                    }
                    require $this->template('orderg');
                    exit;
                }else{
                    $all = $this->myPager($this->_orderg, $_GPC['page'], " AND state=1  AND  atime >= '".$starttime."' AND atime <= '".$endtime."'", 'id DESC', 'id,name,mobile,project_name,addtime,address,content,summoney,state,sername'," WHERE wid='".$this->_wid."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."' AND state=1 ");
                    $list = $all['all'];
                    $pager = $all['pager'];
                    $total = $all['total'];
                    if ($_GPC['export'] != '') {
                        $list = pdo_fetchall("SELECT name,mobile,project_name,addtime,address,content,summoney,state,sername FROM " . tablename($this->_orderg) . " WHERE wid = '" . $this->_wid . "' AND  atime >= '".$starttime."' AND atime <= '".$endtime."' AND state=1 ");
                        $this->cvsDo($filter, $list);
                        exit;
                    }
                    require $this->template('orderg');
                    exit;
                }
            }
            $all =  $this->myPager($this->_orderg,$_GPC['page'],' AND state = 1','id','*'," WHERE wid='" . $this->_wid . "' AND state = 1");
            $list = $all['all'];
            $pager = $all['pager'];
            $total = $all['total'];
            require $this->template('orderg');exit;
        }
        if ($_GPC['list']=='fwz'){
            if (intval(strtotime($_GPC['time']['end'])) === -28800){
                if ((!empty($_GPC['mobile']))&&(!empty($_GPC['name']))){
                    $all =  $this->myPager($this->_orderg,$_GPC['page'],"AND name= '".$_GPC['name']."' AND state=2 AND mobile= '".$_GPC['mobile']."'",'id DESC','name,mobile,project_name,addtime,address,content,summoney,state,sername'," WHERE wid=:wid AND state=2 AND name= '".$_GPC['name']."' AND mobile= '".$_GPC['mobile']."'");
                    $list = $all['all'];
                    $pager = $all['pager'];
                    $total = $all['total'];
                    if ($_GPC['export'] != '') {
                        $list = pdo_fetchall("SELECT name,mobile,project_name,addtime,address,content,summoney,state,sername FROM ".tablename($this->_orderg)." WHERE wid = '".$this->_wid."' AND name= '".$_GPC['name']."' AND state=2 AND  mobile= '".$_GPC['mobile']."'");
                        $this->cvsDo($filter,$list);exit;
                    }
                    require $this->template('orderg');exit;
                }elseif(!empty($_GPC['mobile'])){
                    $all =  $this->myPager($this->_orderg,$_GPC['page']," AND state=2 AND mobile= '".$_GPC['mobile']."'",'id DESC','name,mobile,project_name,addtime,address,content,summoney,state,sername'," WHERE wid=:wid AND state=2  AND mobile= '".$_GPC['mobile']."'");
                    $list = $all['all'];
                    $pager = $all['pager'];
                    $total = $all['total'];
                    if ($_GPC['export'] != '') {
                        $list = pdo_fetchall("SELECT name,mobile,project_name,addtime,address,content,summoney,state,sername FROM ".tablename($this->_orderg)." WHERE  wid = '".$this->_wid."' AND state=2  AND  mobile= '".$_GPC['mobile']."'");
                        $this->cvsDo($filter,$list);exit;
                    }
                    require $this->template('orderg');exit;
                }elseif(!empty($_GPC['name'])){
                    $all =  $this->myPager($this->_orderg,$_GPC['page']," AND state=2 AND name= '".$_GPC['name']."'",'id DESC','name,mobile,project_name,addtime,address,content,summoney,state,sername'," WHERE   state=2 AND wid=:wid AND name= '".$_GPC['name']."'");
                    $list = $all['all'];
                    $pager = $all['pager'];
                    $total = $all['total'];
                    if ($_GPC['export'] != '') {
                        $list = pdo_fetchall("SELECT name,mobile,project_name,addtime,address,content,summoney,state,sername FROM ".tablename($this->_orderg)." WHERE  wid = '".$this->_wid."' AND state=2  AND name= '".$_GPC['name']."'");
                        $this->cvsDo($filter,$list);exit;
                    }
                    require $this->template('orderg');exit;
                }
            }elseif(intval(strtotime($_GPC['time']['end'])) > 0){
                if((!empty($_GPC['mobile']))&&(!empty($_GPC['name']))){
                    $all =  $this->myPager($this->_orderg,$_GPC['page']," AND state=2 AND name= '".$_GPC['name']."' AND mobile= '".$_GPC['mobile']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."'",'id DESC','name,mobile,project_name,addtime,address,content,summoney,state,sername'," WHERE wid=:wid AND name= '".$_GPC['name']."' AND mobile= '".$_GPC['mobile']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."' AND state=2 ");
                    $list = $all['all'];
                    $pager = $all['pager'];
                    $total = $all['total'];
                    if ($_GPC['export'] != '') {
                        $list = pdo_fetchall("SELECT name,mobile,project_name,addtime,address,content,summoney,state,sername FROM ".tablename($this->_orderg)." WHERE wid = '".$this->_wid."' AND name= '".$_GPC['name']."' AND mobile= '".$_GPC['mobile']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."' AND state=2 ");
                        $this->cvsDo($filter,$list);exit;
                    }
                    require $this->template('orderg');exit;
                }elseif(!empty($_GPC['mobile'])){
                    $all =  $this->myPager($this->_orderg,$_GPC['page']," AND state=2  AND mobile= '".$_GPC['mobile']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."'",'id DESC','name,mobile,project_name,addtime,address,content,summoney,state,sername'," WHERE wid=:wid AND mobile= '".$_GPC['mobile']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."'  AND state=2 ");
                    $list = $all['all'];
                    $pager = $all['pager'];
                    $total = $all['total'];
                    if ($_GPC['export'] != '') {
                        $list = pdo_fetchall("SELECT name,mobile,project_name,addtime,address,content,summoney,state,sername FROM ".tablename($this->_orderg)." WHERE wid = '".$this->_wid."' AND mobile= '".$_GPC['mobile']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."' AND state=2 ");
                        $this->cvsDo($filter,$list);exit;
                    }
                    require $this->template('orderg');exit;
                }elseif(!empty($_GPC['name'])) {
                    $all = $this->myPager($this->_orderg, $_GPC['page'], " AND state=2  AND name= '".$_GPC['name']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."'", 'id DESC', 'name,mobile,project_name,addtime,address,content,summoney,state,sername'," WHERE wid=:wid AND name= '".$_GPC['name']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."' AND state=2 ");
                    $list = $all['all'];
                    $pager = $all['pager'];
                    $total = $all['total'];
                    if ($_GPC['export'] != '') {
                        $list = pdo_fetchall("SELECT name,mobile,project_name,addtime,address,content,summoney,state,sername FROM " . tablename($this->_orderg) . " WHERE wid = '" . $this->_wid . "' AND name= '".$_GPC['name']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."' AND state=2 ");
                        $this->cvsDo($filter, $list);
                        exit;
                    }
                    require $this->template('orderg');
                    exit;
                }else{
                    $all = $this->myPager($this->_orderg, $_GPC['page'], " AND state=2  AND  atime >= '".$starttime."' AND atime <= '".$endtime."'", 'id DESC', 'name,mobile,project_name,addtime,address,content,summoney,state,sername'," WHERE wid=:wid AND  atime >= '".$starttime."' AND atime <= '".$endtime."' AND state=2 ");
                    $list = $all['all'];
                    $pager = $all['pager'];
                    $total = $all['total'];
                    if ($_GPC['export'] != '') {
                        $list = pdo_fetchall("SELECT name,mobile,project_name,addtime,address,content,summoney,state,sername FROM " . tablename($this->_orderg) . " WHERE wid = '" . $this->_wid . "' AND  atime >= '".$starttime."' AND atime <= '".$endtime."' AND state=2 ");
                        $this->cvsDo($filter, $list);
                        exit;
                    }
                    require $this->template('orderg');
                    exit;
                }
            }
            $all =  $this->myPager($this->_orderg,$_GPC['page'],' AND state = 2','id','*',' WHERE wid=:wid AND state = 2');
            $list = $all['all'];
            $pager = $all['pager'];
            $total = $all['total'];
            require $this->template('orderg');exit;
        }
        if ($_GPC['list']=='ywc'){
            if (intval(strtotime($_GPC['time']['end'])) === -28800){
                if ((!empty($_GPC['mobile']))&&(!empty($_GPC['name']))){
                    $all =  $this->myPager($this->_orderg,$_GPC['page'],"AND name= '".$_GPC['name']."' AND state=3 AND mobile= '".$_GPC['mobile']."' OR wid='".$this->_wid."' AND name= '".$_GPC['name']."' AND state=4 AND mobile= '".$_GPC['mobile']."'",'id DESC','name,mobile,project_name,addtime,address,content,summoney,state,sername'," WHERE wid=:wid AND state=3 AND name= '".$_GPC['name']."' AND mobile= '".$_GPC['mobile']."' OR wid='".$this->_wid."' AND name= '".$_GPC['name']."' AND state=4 AND mobile= '".$_GPC['mobile']."'");
                    $list = $all['all'];
                    $pager = $all['pager'];
                    $total = $all['total'];
                    if ($_GPC['export'] != '') {
                        $list = pdo_fetchall("SELECT name,mobile,project_name,addtime,address,content,summoney,state,sername FROM ".tablename($this->_orderg)." WHERE wid = '".$this->_wid."' AND name= '".$_GPC['name']."' AND state=3 AND  mobile= '".$_GPC['mobile']."' OR wid='".$this->_wid."' AND name= '".$_GPC['name']."' AND state=4 AND mobile= '".$_GPC['mobile']."'");
                        $this->cvsDo($filter,$list);exit;
                    }
                    require $this->template('orderg');exit;
                }elseif(!empty($_GPC['mobile'])){
                    $all =  $this->myPager($this->_orderg,$_GPC['page']," AND state=3 AND mobile= '".$_GPC['mobile']."' OR wid='".$this->_wid."' AND state=4 AND mobile= '".$_GPC['mobile']."'",'id DESC','name,mobile,project_name,addtime,address,content,summoney,state,sername'," WHERE wid=:wid AND state=3  AND mobile= '".$_GPC['mobile']."' OR wid='".$this->_wid."' AND state=4 AND mobile= '".$_GPC['mobile']."'");
                    $list = $all['all'];
                    $pager = $all['pager'];
                    $total = $all['total'];
                    if ($_GPC['export'] != '') {
                        $list = pdo_fetchall("SELECT name,mobile,project_name,addtime,address,content,summoney,state,sername FROM ".tablename($this->_orderg)." WHERE  wid = '".$this->_wid."' AND state=3  AND  mobile= '".$_GPC['mobile']."' OR wid='".$this->_wid."' AND state=4 AND mobile= '".$_GPC['mobile']."'");
                        $this->cvsDo($filter,$list);exit;
                    }
                    require $this->template('orderg');exit;
                }elseif(!empty($_GPC['name'])){
                    $all =  $this->myPager($this->_orderg,$_GPC['page']," AND state=3 AND name= '".$_GPC['name']."'  OR wid='".$this->_wid."' AND state=4 AND name= '".$_GPC['name']."'",'id DESC','name,mobile,project_name,addtime,address,content,summoney,state,sername'," WHERE state=3 AND wid=:wid AND name= '".$_GPC['name']."'  OR wid='".$this->_wid."' AND state=4 AND name= '".$_GPC['name']."'");
                    $list = $all['all'];
                    $pager = $all['pager'];
                    $total = $all['total'];
                    if ($_GPC['export'] != '') {
                        $list = pdo_fetchall("SELECT name,mobile,project_name,addtime,address,content,summoney,state,sername FROM ".tablename($this->_orderg)." WHERE  wid = '".$this->_wid."' AND state=3  AND name= '".$_GPC['name']."' OR wid='".$this->_wid."' AND state=4 AND name= '".$_GPC['name']."'");
                        $this->cvsDo($filter,$list);exit;
                    }
                    require $this->template('orderg');exit;
                }
            }elseif(intval(strtotime($_GPC['time']['end'])) > 0){
                if((!empty($_GPC['mobile']))&&(!empty($_GPC['name']))){
                    $all =  $this->myPager($this->_orderg,$_GPC['page']," AND state=3 AND name= '".$_GPC['name']."' AND mobile= '".$_GPC['mobile']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."' OR wid='".$this->_wid."' AND state=4 AND name= '".$_GPC['name']."' AND mobile= '".$_GPC['mobile']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."'",'id DESC','name,mobile,project_name,addtime,address,content,summoney,state,sername'," WHERE wid=:wid AND name= '".$_GPC['name']."' AND mobile= '".$_GPC['mobile']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."' AND state=3  OR wid='".$this->_wid."' AND state=4 AND name= '".$_GPC['name']."' AND mobile= '".$_GPC['mobile']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."'");
                    $list = $all['all'];
                    $pager = $all['pager'];
                    $total = $all['total'];
                    if ($_GPC['export'] != '') {
                        $list = pdo_fetchall("SELECT name,mobile,project_name,addtime,address,content,summoney,state,sername FROM ".tablename($this->_orderg)." WHERE wid = '".$this->_wid."' AND name= '".$_GPC['name']."' AND mobile= '".$_GPC['mobile']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."' AND state=3  OR wid='".$this->_wid."' AND state=4 AND name= '".$_GPC['name']."' AND mobile= '".$_GPC['mobile']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."'");
                        $this->cvsDo($filter,$list);exit;
                    }
                    require $this->template('orderg');exit;
                }elseif(!empty($_GPC['mobile'])){
                    $all =  $this->myPager($this->_orderg,$_GPC['page']," AND state=3  AND mobile= '".$_GPC['mobile']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."' OR wid='".$this->_wid."' AND state=4 AND mobile= '".$_GPC['mobile']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."'",'id DESC','name,mobile,project_name,addtime,address,content,summoney,state,sername'," WHERE wid=:wid AND mobile= '".$_GPC['mobile']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."'  AND state=3  OR wid='".$this->_wid."' AND state=4 AND mobile= '".$_GPC['mobile']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."'");
                    $list = $all['all'];
                    $pager = $all['pager'];
                    $total = $all['total'];
                    if ($_GPC['export'] != '') {
                        $list = pdo_fetchall("SELECT name,mobile,project_name,addtime,address,content,summoney,state,sername FROM ".tablename($this->_orderg)." WHERE wid = '".$this->_wid."' AND mobile= '".$_GPC['mobile']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."' AND state=3  OR wid='".$this->_wid."' AND state=4 AND mobile= '".$_GPC['mobile']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."'");
                        $this->cvsDo($filter,$list);exit;
                    }
                    require $this->template('orderg');exit;
                }elseif(!empty($_GPC['name'])) {
                    $all = $this->myPager($this->_orderg, $_GPC['page'], " AND state=3  AND name= '".$_GPC['name']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."'  OR wid='".$this->_wid."' AND state=4 AND name= '".$_GPC['name']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."'", 'id DESC', 'name,mobile,project_name,addtime,address,content,summoney,state,sername'," WHERE wid=:wid AND name= '".$_GPC['name']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."' AND state=3  OR wid='".$this->_wid."' AND state=4 AND name= '".$_GPC['name']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."'");
                    $list = $all['all'];
                    $pager = $all['pager'];
                    $total = $all['total'];
                    if ($_GPC['export'] != '') {
                        $list = pdo_fetchall("SELECT name,mobile,project_name,addtime,address,content,summoney,state,sername FROM " . tablename($this->_orderg) . " WHERE wid = '" . $this->_wid . "' AND name= '".$_GPC['name']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."' AND state=3  OR wid='".$this->_wid."' AND state=4 AND name= '".$_GPC['name']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."'");
                        $this->cvsDo($filter, $list);
                        exit;
                    }
                    require $this->template('orderg');
                    exit;
                }else{
                    $all = $this->myPager($this->_orderg, $_GPC['page'], " AND state=3  AND  atime >= '".$starttime."' AND atime <= '".$endtime."'  OR wid='".$this->_wid."' AND state=4 AND  atime >= '".$starttime."' AND atime <= '".$endtime."'", 'id DESC', 'name,mobile,project_name,addtime,address,content,summoney,state,sername'," WHERE wid=:wid AND  atime >= '".$starttime."' AND atime <= '".$endtime."' AND state=3  OR wid='".$this->_wid."' AND state=4 AND  atime >= '".$starttime."' AND atime <= '".$endtime."'");
                    $list = $all['all'];
                    $pager = $all['pager'];
                    $total = $all['total'];
                    if ($_GPC['export'] != '') {
                        $list = pdo_fetchall("SELECT name,mobile,project_name,addtime,address,content,summoney,state,sername FROM " . tablename($this->_orderg) . " WHERE wid = '" . $this->_wid . "' AND  atime >= '".$starttime."' AND atime <= '".$endtime."' AND state=3  OR wid='".$this->_wid."' AND state=4 AND  atime >= '".$starttime."' AND atime <= '".$endtime."'");
                        $this->cvsDo($filter, $list);
                        exit;
                    }
                    require $this->template('orderg');
                    exit;
                }
            }
            $all =  $this->myPager($this->_orderg,$_GPC['page']," AND state = 3 OR wid = '" . $_W['uniacid']."' AND state = 4",'id','*',' WHERE wid=:wid AND state = 3 OR wid =:wid AND state = 4');
            $list = $all['all'];
            $pager = $all['pager'];
            $total = $all['total'];
            require $this->template('orderg');exit;
        }
        $all =  $this->myPager($this->_orderg,$_GPC['page'],'','id DESC');
        $list = $all['all'];
        $pager = $all['pager'];
        $total = $all['total'];
        if (intval(strtotime($_GPC['time']['end'])) === -28800){
            if ((!empty($_GPC['mobile']))&&(!empty($_GPC['name']))){
                $all =  $this->myPager($this->_orderg,$_GPC['page'],"AND name= '".$_GPC['name']."' AND mobile= '".$_GPC['mobile']."'",'id DESC','id,name,mobile,project_name,addtime,address,content,summoney,state,sername'," WHERE wid='".$this->_wid."' AND name= '".$_GPC['name']."' AND mobile= '".$_GPC['mobile']."'");
                $list = $all['all'];
                $pager = $all['pager'];
                $total = $all['total'];
                if ($_GPC['export'] != '') {
                    $list = pdo_fetchall("SELECT name,mobile,project_name,addtime,address,content,summoney,state,sername FROM ".tablename($this->_orderg)." WHERE wid = '".$this->_wid."' AND name= '".$_GPC['name']."' AND mobile= '".$_GPC['mobile']."'");
                    $this->cvsDo($filter,$list);exit;
                }
                require $this->template('orderg');exit;
            }elseif(!empty($_GPC['mobile'])){
                $all =  $this->myPager($this->_orderg,$_GPC['page'],"AND mobile= '".$_GPC['mobile']."'",'id DESC','id,name,mobile,project_name,addtime,address,content,summoney,state,sername'," WHERE wid='".$this->_wid."' AND mobile= '".$_GPC['mobile']."'");
                $list = $all['all'];
                $pager = $all['pager'];
                $total = $all['total'];
                if ($_GPC['export'] != '') {
                    $list = pdo_fetchall("SELECT name,mobile,project_name,addtime,address,content,summoney,state,sername FROM ".tablename($this->_orderg)." WHERE wid = '".$this->_wid."' AND mobile= '".$_GPC['mobile']."'");
                    $this->cvsDo($filter,$list);exit;
                }
                require $this->template('orderg');exit;
            }elseif(!empty($_GPC['name'])){
                $all =  $this->myPager($this->_orderg,$_GPC['page'],"AND name= '".$_GPC['name']."'",'id DESC','id,name,mobile,project_name,addtime,address,content,summoney,state,sername'," WHERE wid='" . $this->_wid . "' AND name= '".$_GPC['name']."'");
                $list = $all['all'];
                $pager = $all['pager'];
                $total = $all['total'];
                if ($_GPC['export'] != '') {
                    $list = pdo_fetchall("SELECT name,mobile,project_name,addtime,address,content,summoney,state,sername FROM ".tablename($this->_orderg)." WHERE wid = '".$this->_wid."' AND name= '".$_GPC['name']."'");
                    $this->cvsDo($filter,$list);exit;
                }
                require $this->template('orderg');exit;
            }
        }elseif(intval(strtotime($_GPC['time']['end'])) > 0){
            if((!empty($_GPC['mobile']))&&(!empty($_GPC['name']))){
                $all =  $this->myPager($this->_orderg,$_GPC['page'],"AND name= '".$_GPC['name']."' AND mobile= '".$_GPC['mobile']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."'",'id DESC','id,name,mobile,project_name,addtime,address,content,summoney,state,sername'," WHERE wid='" . $this->_wid . "' AND name= '".$_GPC['name']."' AND mobile= '".$_GPC['mobile']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."'");
                $list = $all['all'];
                $pager = $all['pager'];
                $total = $all['total'];
                if ($_GPC['export'] != '') {
                    $list = pdo_fetchall("SELECT name,mobile,project_name,addtime,address,content,summoney,state,sername FROM ".tablename($this->_orderg)." WHERE wid = '".$this->_wid."' AND name= '".$_GPC['name']."' AND mobile= '".$_GPC['mobile']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."'");
                    $this->cvsDo($filter,$list);exit;
                }
                require $this->template('orderg');exit;
            }elseif(!empty($_GPC['mobile'])){
                $all =  $this->myPager($this->_orderg,$_GPC['page']," AND mobile= '".$_GPC['mobile']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."'",'id DESC','id,name,mobile,project_name,addtime,address,content,summoney,state,sername'," WHERE wid='" . $this->_wid . "' AND mobile= '".$_GPC['mobile']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."'");
                $list = $all['all'];
                $pager = $all['pager'];
                $total = $all['total'];
                if ($_GPC['export'] != '') {
                    $list = pdo_fetchall("SELECT name,mobile,project_name,addtime,address,content,summoney,state,sername FROM ".tablename($this->_orderg)." WHERE wid = '".$this->_wid."' AND mobile= '".$_GPC['mobile']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."'");
                    $this->cvsDo($filter,$list);exit;
                }
                require $this->template('orderg');exit;
            }elseif(!empty($_GPC['name'])) {
                $all = $this->myPager($this->_orderg, $_GPC['page'], " AND name= '".$_GPC['name']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."'", 'id DESC', 'id,name,mobile,project_name,addtime,address,content,summoney,state,sername'," WHERE wid='" . $this->_wid . "' AND name= '".$_GPC['name']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."'");
                $list = $all['all'];
                $pager = $all['pager'];
                $total = $all['total'];
                if ($_GPC['export'] != '') {
                    $list = pdo_fetchall("SELECT name,mobile,project_name,addtime,address,content,summoney,state,sername FROM " . tablename($this->_orderg) . " WHERE wid = '" . $this->_wid . "' AND name= '".$_GPC['name']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."'");
                    $this->cvsDo($filter, $list);
                    exit;
                }
                require $this->template('orderg');
                exit;
            }else{
                $all = $this->myPager($this->_orderg, $_GPC['page'], " AND  atime >= '".$starttime."' AND atime <= '".$endtime."'", 'id DESC', 'id,name,mobile,project_name,addtime,address,content,summoney,state,sername'," WHERE wid=:wid AND  atime >= '".$starttime."' AND atime <= '".$endtime."'");
                $list = $all['all'];
                $pager = $all['pager'];
                $total = $all['total'];
                if ($_GPC['export'] != '') {
                    $list = pdo_fetchall("SELECT name,mobile,project_name,addtime,address,content,summoney,state,sername FROM " . tablename($this->_orderg) . " WHERE wid = '" . $this->_wid . "' AND  atime >= '".$starttime."' AND atime <= '".$endtime."'");
                    $this->cvsDo($filter, $list);
                    exit;
                }
                require $this->template('orderg');
                exit;
            }
        }
        if ($_GPC['export'] != '') {
            $list = $this->myGetall($this->_orderg,array('wid'=>$this->_wid));
            $this->cvsDo($filter,$list);exit;
        }
        require $this->template('orderg');
    }
    //显示订单详情
    public function doWebShowlist(){
        global  $_GPC,$_W;
        if  ($_GPC['idid'] != null){
            $order = $this->myGet($this->_orderg,array('wid'=>$this->_wid,'id'=>intval($_GPC['idid'])));
            $succ = $this->myUpdate($this->_orderg,array('state'=>3),array('wid'=>$this->_wid,'id'=>intval($_GPC['idid'])),'AND');
            $user = $this->myGet($this->_user,array('wid'=>$this->_wid,'openid'=>$order['openid']));
            $UU = $this->myUpdate($this->_user,array('money'=>($order['summoney']+$user['money'])),array('wid'=>$this->_wid,'openid'=>$order['openid']),'AND');
            $data = array(
                'first'    => array(
                    'value' =>$this->muban['prompt4'],
                    'color' => '#173177'
                ),
                'keyword1' => array(
                    'value' =>$order['project_name'],
                    'color' => '#173177'
                ),
                'keyword2' => array(
                    'value'=>'已完成',
                    'color'=>'#173177'
                ),
                'remark' => array(
                    'value'=>'请您对我们的服务进行评价.',
                    'color'=>'#173177'
                )
            );
            $this->wob($order['openid'],$this->muban['messageid4'],$data,$_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&do=orderlist&name=yi&id=".$_GPC['idid']."&m=".$this->module['name']);
            $json = json_encode($UU);
            echo $json;exit;
        }
        $list = $this->myGet($this->_orderg,array('id'=>intval($_GPC['id']),'wid'=>$this->_wid));
        include $this->template('showlist');
    }
    //后台派单
    public function doWebPaidan(){
        global $_GPC,$_W;
        if (empty($_GPC['people'])){
          message('请选择服务师,如为空请先添加服务师',$this->createWebUrl('Orderg',array('list'=>'wpd')),'info');
        }
//        var_dump($_W['current_module']);exit;
        $list = $this->myGet($this->_orderg,array('id'=>intval($_GPC['id']),'wid'=>$this->_wid));
        $data = array(
            'first'    => array(
                'value' =>$this->muban['prompt2'],
                'color' => '#173177'
            ),
            'keyword1' => array(
                'value' =>$list['name'],
                'color' => '#173177'
            ),
            'keyword2' => array(
                'value'=>$list['project_name'],
                'color'=>'#173177'
            ),
            'keyword3' => array(
                'value'=>$list['addtime'].',地址:'.$list['address'],
                'color'=>'#173177'
            ),
            'keyword4' => array(
                'value'=>$list['mobile'],
                'color'=>'#173177'
            ),
            'remark' => array(
                'value'=>$this->muban['remarks2'],
                'color'=>'#173177'
            )
        );
        if($list['state']==1){
            $ord = $this->myGet($this->_staff,array('name'=>$_GPC['people'],'wid'=>$this->_wid));
            $succ = $this->myUpdate($this->_orderg,array('state'=>2,'sername'=>$_GPC['people'],'seropenid'=>$ord['openid']),array('wid'=>$this->_wid,'id'=>$list['id']),'AND');
            if($succ){
                $datagk = array(
                    'first' => array(
                        'value' =>'您的订单状态已更新',
                        'color' => '#173177'
                    ),
                    'keyword1' => array(
                        'value'=>$list['project_name'],
                        'color'=>'#173177'
                    ),
                    'keyword2' => array(
                        'value'=>'管理员已派单',
                        'color'=>'#173177'
                    ),
                    'remark' => array(
                        'value'=>'服务师姓名:'.$ord['name'].',手机号:'.$ord['mobile'],
                        'color'=>'#173177'
                    )
                );
                $this->wob($ord['openid'],$this->muban['messageid2'],$data);
                $this->wob($list['openid'],$this->muban['messageid4'],$datagk,$_W['siteroot'].'app'.str_replace('./','/',murl('entry/site/dingdandetail')).'&m='.$_W['current_module']['name'].'&id='.intval($_GPC['id']));
                message('派单成功-折-翼-天-使-资-源-社-区-提-供',$this->createWebUrl('orderg'),'success');
            }
        }
    }
    //后台基础设置
    public function doWebBase(){
        global $_W,$_GPC;
//        $list = $this->myGetall($this->_staff,array('wid'=>$this->_wid));
        $item = $this->myGet($this->_base,array('wid'=>$this->_wid));
        load()->func('tpl');
        if(checksubmit()){
            if($item){
//                $mopenid = $_GPC['base']['mopenid'];
//                $this->myUpdate($this->_staff,array('booking'=>0),array('wid'=>$this->_wid));
//                $this->myUpdate($this->_staff,array('booking'=>1),array('wid'=>$this->_wid,'openid'=>$mopenid),'AND');
                $data = $_GPC['base'];
                $data['updatetime'] = TIMESTAMP;
                $succ = $this->myUpdate($this->_base,$data,array('wid'=>$this->_wid));
                $this->succError($succ,$this->createWebUrl('base'),1);
            }else{
//                $mopenid = $_GPC['base']['mopenid'];
//                $this->myUpdate($this->_staff,array('booking'=>0),array('wid'=>$this->_wid));
//                $this->myUpdate($this->_staff,array('booking'=>1),array('wid'=>$this->_wid,'openid'=>$mopenid),'AND');
                $data = $_GPC['base'];
                $data['wid'] = $this->_wid;
                $data['addtime'] = TIMESTAMP;
                $succ = $this->myInsert($this->_base,$data);
                $this->succError($succ,$this->createWebUrl('base'));
            }
        }
        require $this->template('base');
    }
    //后台模板消息设置
    public function doWebMuban(){
        global $_W,$_GPC;
        $item = $this->myGet($this->_muban,array('wid'=>$this->_wid));
        if(checksubmit()){
            if($item){
                $data = $_GPC['muban'];
                $data['addtime'] = TIMESTAMP;
                $succ = $this->myUpdate($this->_muban,$data,array('wid'=>$this->_wid));
                $this->succError($succ,$this->createWebUrl('muban'),1);
            }else{
                $data = $_GPC['muban'];
                $data['wid'] = $this->_wid;
                $data['addtime'] = TIMESTAMP;
                $succ = $this->myInsert($this->_muban,$data);
                $this->succError($succ,$this->createWebUrl('muban'));
            }
        }
        require $this->template('muban');
    }
    //后台充值赠送设置
    public function doWebMoneyrc(){
        global $_W,$_GPC;
        if (checksubmit()){
            $data = $_GPC['cz'];
            $data['wid'] = $this->_wid;
            $data['addtime'] = TIMESTAMP;
            $succ = $this->myInsert($this->_moneycz,$data);
            if ($succ) message('设置成功',$this->createWebUrl('moneyrclist'),'success');
        }
        include $this->template('moneyrc');
    }
    //后台充值赠送列表
    public function doWebMoneyrclist(){
        global $_W,$_GPC;
        $list = $this->myGetall($this->_moneycz,array('wid'=>$this->_wid));
        include $this->template('moneyrclist');
    }

    public function doWebMoneyrcdel(){
        global $_W,$_GPC;
        $succ = $this->myDelete($this->_moneycz,array('id'=>intval($_GPC['id']),'wid'=>$this->_wid));
        $this->succError($succ,$this->createWebUrl('moneyrclist'),2);
    }
    //后台会员
    public function doWebMemberdata(){
            global $_W,$_GPC;
            $all = pdo_fetchall("SELECT * FROM ".tablename('mc_groups')." WHERE uniacid='".$this->_wid."' ORDER BY groupid");
            $memall = pdo_fetchall("SELECT * FROM ".tablename($this->_member)." WHERE wid='".$this->_wid."' ORDER BY id");
            $num = pdo_fetchcolumn("SELECT count(*) FROM ".tablename($this->_member)." WHERE wid='".$this->_wid."'");
            for ($i=0;$i<$num;$i++){
                if($all[$i]['groupid'] == $memall[$i]['groupid']){
                    if ($all[$i]['title']!=$memall[$i]['title']){
                        $this->myUpdate($this->_member,array('title'=>$all[$i]['title']),array('wid'=>$this->_wid,'groupid'=>$memall[$i]['groupid']),'AND');
                    }
                }else{
                    if ($all[$i]['isdefault'] == 1){
                        $data['wid'] = $this->_wid;
                        $data['title'] = $all[$i]['title'];
                        $data['content'] = '不享受折扣';
                        $data['addtime'] = TIMESTAMP;
                        $data['agio'] = 0;
                        $data['groupid'] = $all[$i]['groupid'];
                        $succ = $this->myInsert($this->_member,$data);
                    }else{
                        $nnn = pdo_fetch("SELECT level,agio,money,number FROM ".tablename($this->_member)." WHERE wid='".$this->_wid."' ORDER BY level DESC LIMIT 1");
                        $data[$i]['wid'] = $this->_wid;
                        $data[$i]['title'] = $all[$i]['title'];
                        $data[$i]['addtime'] = TIMESTAMP;
                        $data[$i]['agio'] = $nnn['agio']+10;
                        $data[$i]['money'] = $nnn['money']+200;
                        $data[$i]['number'] = $nnn['number']+50;
                        $data[$i]['level'] = $nnn['level']+1;
                        $data[$i]['groupid'] = $all[$i]['groupid'];
                        $succ = $this->myInsert($this->_member,$data[$i]);
                    }
                }
            }
            for ($i=0;$i<$num;$i++){
                if ($memall[$i]['groupid'] != $all[$i]['groupid']){
                    $this->myDelete($this->_member,array('wid'=>$this->_wid,'groupid'=>$memall[$i]['groupid']));
                }
            }
            $list = $this->myGettop($this->_member,'*','','id');
            foreach ($list as $k=>$v){
                $list[$k]['number'] = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename($this->_user)." WHERE member=:member AND
                wid=:wid",array(':wid'=>$this->_wid,':member'=>$v['title']));
            }
            require $this->template('memberdata');exit;
    }
    //添加会员等级
//    public function doWebmemberadd(){
//        global $_W,$_GPC;
//        if(checksubmit()){
//            $data = $_GPC['serv'];
//            $dat =$this->myGet($this->_member,array('wid'=>$this->_wid,'title'=>$data['title']));
//            if($dat){
//                message('会员名称已存在',$this->createWebUrl('memberdata'),'info');
//            }else{
//                $data['wid'] = $this->_wid;
//                $data['addtime'] = TIMESTAMP;
//                $succ = $this->myInsert($this->_member,$data);
//                $this->succError($succ,$this->createWebUrl('Memberdata'));
//            }
//        }
//        require $this->template('memberadd');
//    }
    //会员等级列表
    public function doWebMemberlist(){
        global $_W,$_GPC;
        $filter = array(
            'nickname' => '昵称',
            'addtime' => '成为时间',
            'member' => '所属等级',
            'mobile' => '手机号',
            'money' => '消费金额',
        );
        if (!empty($_GPC['time'])) {
            $starttime = strtotime($_GPC['time']['start']);
            $endtime = strtotime($_GPC['time']['end']) + 86399;
        }

        if (intval(strtotime($_GPC['time']['end'])) === -28800) {
            if (!empty($_GPC['mobile'])) {
                $all = $this->myPager($this->_user, $_GPC['page'], " AND mobile= '" . $_GPC['mobile'] . "'", 'id DESC', '*', " WHERE wid='".$this->_wid."' AND  mobile= '" . $_GPC['mobile'] . "'");
                $list = $all['all'];
                $pager = $all['pager'];
                $total = $all['total'];
                if ($_GPC['export'] != '') {
                    $this->cvsXin($filter, $list);
                    exit;
                }
                require $this->template('memberlist');
                exit;
            }
        }elseif (intval(strtotime($_GPC['time']['end'])) > 0) {
            if (!empty($_GPC['mobile'])) {
                $all = $this->myPager($this->_user, $_GPC['page'], " AND mobile= '" . $_GPC['mobile'] . "' AND  addtime >= '" . $starttime . "' AND addtime <= '" . $endtime . "'", 'id DESC', '*', " WHERE wid='".$this->_wid."' AND mobile= '" . $_GPC['mobile'] . "' AND  addtime >= '" . $starttime . "' AND addtime <= '" . $endtime . "'");
                $list = $all['all'];
                $pager = $all['pager'];
                $total = $all['total'];
                if ($_GPC['export'] != '') {
                    $this->cvsXin($filter, $list);
                    exit;
                }
                require $this->template('memberlist');
                exit;
            } else {
                $all = $this->myPager($this->_user, $_GPC['page'], " AND  addtime >= '" . $starttime . "' AND addtime <= '" . $endtime . "'", 'id DESC', '*', " WHERE wid='".$this->_wid."' AND  addtime >= '" . $starttime . "' AND addtime <= '" . $endtime . "'");
                $list = $all['all'];
                $pager = $all['pager'];
                $total = $all['total'];
                if ($_GPC['export'] != '') {
                    $list = pdo_fetchall("SELECT * FROM ".tablename($this->_user)."  WHERE wid='".$this->_wid."' AND  addtime >= '" . $starttime . "' AND addtime <= '" . $endtime . "'");
                    $this->cvsXin($filter, $list);
                    exit;
                }
                require $this->template('memberlist');
                exit;
            }

        }
        if ($_GPC['member'] != null) {
            $all = $this->myPager($this->_user, $_GPC['page'], "AND member ='" . $_GPC['member'] . "'", 'id DESC', '*', " WHERE wid='".$this->_wid."' AND member ='" . $_GPC['member'] . "'");
            $list = $all['all'];
            $pager = $all['pager'];
            $total = $all['total'];
            if ($_GPC['export'] != '') {
                $list = $this->myGetall($this->_user,array('wid'=>$this->_wid,'member'=>$_GPC['member']));
                $this->cvsXin($filter, $list);
                exit;
            }
            require $this->template('memberlist');
        }else{
            $all = $this->myPager($this->_user, $_GPC['page'],'','id DESC','*');
            $list = $all['all'];
            $pager = $all['pager'];
            $total = $all['total'];
            if ($_GPC['export'] != '') {
                $list = $this->myGetall($this->_user,array('wid'=>$this->_wid));
                $this->cvsXin($filter, $list);
                exit;
            }
            require $this->template('memberlist');
        }
    }
    //会员等级编辑
    public function doWebMemberedit(){
        global $_W,$_GPC;
        $list =$this->myGet($this->_member,array('wid'=>$this->_wid,'id'=>intval($_GPC['id'])));
        if (checksubmit()){
            $data = $_GPC['serv'];
//            $dat =$this->myGet($this->_member,array('wid'=>$this->_wid,'title'=>$data['title']));
//            if($dat){
//                message('会员名称已存在',$this->createWebUrl('memberdata'),'info');
//            }else{
            $data['addtime'] = TIMESTAMP;
//            $user = $this->myUpdate($this->_user,array('member'=>$_GPC['serv']['title']),array('wid'=>$this->_wid,'member'=>$list['title']),'AND');
            $succ = $this->myUpdate($this->_member,$data,array('wid'=>$this->_wid,'id'=>$_GPC['id']),'AND');
            $this->succError($succ,$this->createWebUrl('Memberdata'),1);
//            }
        }
        require $this->template('memberedit');
    }
    //会员等级删除
//    public function doWebMemberdel(){
//        global $_W,$_GPC;
//        $list =$this->myGet($this->_member,array('wid'=>$this->_wid,'id'=>intval($_GPC['id'])));
//        $zd = $this->myGet($this->_member,array('wid'=>$this->_wid,'content'=>'不享受折扣'));
//        $user = $this->myUpdate($this->_user,array('member'=>$zd['title']),array('wid'=>$this->_wid));
//        $del = $this->myDelete($this->_member,array('wid'=>$this->_wid,'id'=>intval($_GPC['id'])));
//        $this->succError($del,$this->createWebUrl('Memberdata'),2);
//        require $this->template('membertrem');
//    }
    //顾客充值列表
    public function doWebMemberczlist(){
        global $_W,$_GPC;
        $filter = array(
            'nickname' => '昵称',
            'money' => '充值金额',
            'zsmoney' => '赠送金额',
            'addtime' => '时间',
        );
        if (!empty($_GPC['time'])) {
            $starttime = strtotime($_GPC['time']['start']);
            $endtime = strtotime($_GPC['time']['end']) + 86399;
        }
        if (intval(strtotime($_GPC['time']['end'])) === -28800) {
            if (!empty($_GPC['nickname'])) {
                $all = $this->myPager($this->_moneyrc, $_GPC['page'], "AND nickname= '" . $_GPC['nickname'] . "' AND state=1", 'id DESC', '*', " WHERE wid='" . $this->_wid . "' AND nickname= '" . $_GPC['nickname'] . "' AND state=1");
                $list = $all['all'];
                $pager = $all['pager'];
                $total = $all['total'];
                $sum = pdo_fetchcolumn("SELECT sum(money) FROM ". tablename($this->_moneyrc) . " WHERE wid = '" . $this->_wid . "' AND state=1 AND nickname= '" . $_GPC['nickname'] . "'");
                $sum1 = pdo_fetchcolumn("SELECT sum(zsmoney) FROM ". tablename($this->_moneyrc) . " WHERE wid = '" . $this->_wid . "' AND state=1 AND nickname= '" . $_GPC['nickname'] . "'");
                if ($_GPC['export'] != '') {
                    $list = pdo_fetchall("SELECT * FROM " . tablename($this->_moneyrc) . " WHERE wid = '" . $this->_wid . "' AND nickname= '" . $_GPC['nickname'] . "' AND state=1");
                    $this->cvsXin($filter, $list);
                    exit;
                }
                require $this->template('memberczlist');
                exit;
            }
        }elseif(intval(strtotime($_GPC['time']['end'])) > 0){
            if(!empty($_GPC['nickname'])){
                $all =  $this->myPager($this->_moneyrc,$_GPC['page'],"AND nickname= '".$_GPC['nickname']."' AND  addtime >= '".$starttime."' AND addtime <= '".$endtime."' AND state=1",'id DESC','*'," WHERE wid='" . $this->_wid . "' AND nickname= '".$_GPC['nickname']."' AND  atime >= '".$starttime."' AND atime <= '".$endtime."' AND state=1");
                $list = $all['all'];
                $pager = $all['pager'];
                $total = $all['total'];
                $sum = pdo_fetchcolumn("SELECT sum(money) FROM ". tablename($this->_moneyrc) . " WHERE wid = '" . $this->_wid . "' AND state=1 AND nickname= '".$_GPC['nickname']."' AND  addtime >= '".$starttime."' AND addtime <= '".$endtime."'");
                $sum1 = pdo_fetchcolumn("SELECT sum(zsmoney) FROM ". tablename($this->_moneyrc) . " WHERE wid = '" . $this->_wid . "' AND state=1 AND nickname= '".$_GPC['nickname']."' AND  addtime >= '".$starttime."' AND addtime <= '".$endtime."'");
                if ($_GPC['export'] != '') {
                    $list = pdo_fetchall("SELECT * FROM ".tablename($this->_moneyrc)." WHERE wid = '".$this->_wid."' AND nickname= '".$_GPC['nickname']."' AND  addtime >= '".$starttime."' AND addtime <= '".$endtime."' AND state=1");
                    $this->cvsXin($filter,$list);exit;
                }
                require $this->template('memberczlist');exit;
            }else{
                $all = $this->myPager($this->_moneyrc, $_GPC['page'], " AND  addtime >= '".$starttime."' AND addtime <= '".$endtime."' AND state=1", 'id DESC', '*'," WHERE wid=:wid AND  addtime >= '".$starttime."' AND addtime <= '".$endtime."' AND state=1");
                $list = $all['all'];
                $pager = $all['pager'];
                $total = $all['total'];
                $sum = pdo_fetchcolumn("SELECT sum(money) FROM ". tablename($this->_moneyrc) . " WHERE wid = '" . $this->_wid . "' AND state=1 AND  addtime >= '".$starttime."' AND addtime <= '".$endtime."'");
                $sum1 = pdo_fetchcolumn("SELECT sum(zsmoney) FROM ". tablename($this->_moneyrc) . " WHERE wid = '" . $this->_wid . "' AND state=1 AND  addtime >= '".$starttime."' AND addtime <= '".$endtime."'");
                if ($_GPC['export'] != '') {
                    $list = pdo_fetchall("SELECT * FROM " . tablename($this->_moneyrc) . " WHERE wid = '" . $this->_wid . "' AND  addtime >= '".$starttime."' AND addtime <= '".$endtime."' AND state=1");
                    $this->cvsXin($filter, $list);
                    exit;
                }
                require $this->template('memberczlist');
                exit;
            }
        }
        if ($_GPC['export'] != '') {
            $list = $this->myGetall($this->_moneyrc,array('wid'=>$this->_wid,'state'=>1));
            $this->cvsXin($filter,$list);exit;
        }
        $all = $this->myPager($this->_moneyrc,$_GPC['page'],' AND state=1 ',' addtime DESC ','*'," WHERE wid='".$this->_wid."' AND state=1 ");
        $list = $all['all'];
        $pager = $all['pager'];
        $total = $all['total'];
        $sum = pdo_fetchcolumn("SELECT sum(money) FROM ". tablename($this->_moneyrc) . " WHERE wid = '" . $this->_wid . "' AND state=1");
        $sum1 = pdo_fetchcolumn("SELECT sum(zsmoney) FROM ". tablename($this->_moneyrc) . " WHERE wid = '" . $this->_wid . "' AND state=1");
        include $this->template('memberczlist');
    }
    //后台推广员
    public function doWebExtend(){
        global $_W,$_GPC;
        $filter = array(
            'name' => '姓名',
            'mobile' => '手机号',
            'wei' => '未提佣金',
            'yi' => '已提佣金',
            'tgymoney' => '消费金额',
            'tgynum' => '推广数量'
        );
        $all = $this->myPager($this->_staff,$_GPC['page'],'AND tgystate=1','id','*'," WHERE wid='".$this->_wid."' AND  tgystate=1");
        $list = $all['all'];
        $pager = $all['pager'];
        $total = $all['total'];
        $sum = pdo_fetchcolumn("SELECT sum(tgymoney) FROM ".tablename($this->_staff)." WHERE wid='".$this->_wid."' AND tgystate=1");
        if ((!empty($_GPC['mobile']))&&(!empty($_GPC['name']))){
            $all =  $this->myPager($this->_staff,$_GPC['page'],"AND name= '".$_GPC['name']."' AND tgystate=1 AND mobile= '".$_GPC['mobile']."'",'id DESC','*'," WHERE wid='".$this->_wid."' AND tgystate=1 AND name= '".$_GPC['name']."' AND mobile= '".$_GPC['mobile']."'");
            $list = $all['all'];
            $pager = $all['pager'];
            $total = $all['total'];
            $sum = pdo_fetchcolumn("SELECT sum(tgymoney) FROM ".tablename($this->_staff)." WHERE wid='".$this->_wid."' AND tgystate=1 AND name= '".$_GPC['name']."' AND mobile= '".$_GPC['mobile']."'");
            if ($_GPC['export'] != '') {
                $list = pdo_fetchall("SELECT * FROM ".tablename($this->_staff)." WHERE wid = '".$this->_wid."' AND tgystate=1 AND name= '".$_GPC['name']."' AND mobile= '".$_GPC['mobile']."'");
                foreach($list as $k=>$v){
                    $a = $this->myGet($this->_fwstgy,array('openid'=>$v[openid],'state'=>0,'fwstgy'=>1));
                    $b = pdo_fetchcolumn("SELECT SUM(money) FROM ".tablename($this->_fwstgy)." WHERE state=2 AND openid='".$v[openid]."' AND fwstgy=1");
                    if ($a['money'] == null){
                        $list[$k]['wei'] = '0.00';
                    }else{
                        $list[$k]['wei'] = $a['money'];
                    }
                    if($b){
                        $list[$k]['yi'] = $b;
                    }else{
                        $list[$k]['yi'] = '0.00';
                    }
                }
                $this->cvsDo($filter,$list);exit;
            }
            require $this->template('extend');exit;
        }elseif(!empty($_GPC['mobile'])){
            $all =  $this->myPager($this->_staff,$_GPC['page']," AND tgystate=1 AND mobile= '".$_GPC['mobile']."'",'id DESC','*'," WHERE wid='".$this->_wid."' AND tgystate=1  AND mobile= '".$_GPC['mobile']."'");
            $list = $all['all'];
            $pager = $all['pager'];
            $total = $all['total'];
            $sum = pdo_fetchcolumn("SELECT sum(tgymoney) FROM ".tablename($this->_staff)." WHERE wid='".$this->_wid."' AND tgystate=1  AND mobile= '".$_GPC['mobile']."'");
            if ($_GPC['export'] != '') {
                $list = pdo_fetchall("SELECT * FROM ".tablename($this->_staff)." WHERE wid = '".$this->_wid."' AND tgystate=1 AND mobile= '".$_GPC['mobile']."'");
                foreach($list as $k=>$v){
                    $a = $this->myGet($this->_fwstgy,array('openid'=>$v[openid],'state'=>0,'fwstgy'=>1));
                    $b = pdo_fetchcolumn("SELECT SUM(money) FROM ".tablename($this->_fwstgy)." WHERE state=2 AND openid='".$v[openid]."' AND fwstgy=1");
                    if ($a['money'] == null){
                        $list[$k]['wei'] = '0.00';
                    }else{
                        $list[$k]['wei'] = $a['money'];
                    }
                    if($b){
                        $list[$k]['yi'] = $b;
                    }else{
                        $list[$k]['yi'] = '0.00';
                    }
                }
                $this->cvsDo($filter,$list);exit;
            }
            require $this->template('extend');exit;
        }elseif(!empty($_GPC['name'])){
            $all =  $this->myPager($this->_staff,$_GPC['page']," AND tgystate=1 AND name= '".$_GPC['name']."'",'id DESC','*'," WHERE tgystate=1  AND  wid='".$this->_wid."' AND name= '".$_GPC['name']."'");
            $list = $all['all'];
            $pager = $all['pager'];
            $total = $all['total'];
            $sum = pdo_fetchcolumn("SELECT sum(tgymoney) FROM ".tablename($this->_staff)." WHERE wid='".$this->_wid."' AND tgystate=1  AND name= '".$_GPC['name']."'");
            if ($_GPC['export'] != '') {
                $list = pdo_fetchall("SELECT * FROM ".tablename($this->_staff)." WHERE wid = '".$this->_wid."' AND tgystate=1 AND name= '".$_GPC['name']."'");
                foreach($list as $k=>$v){
                    $a = $this->myGet($this->_fwstgy,array('openid'=>$v[openid],'state'=>0,'fwstgy'=>1));
                    $b = pdo_fetchcolumn("SELECT SUM(money) FROM ".tablename($this->_fwstgy)." WHERE state=2 AND openid='".$v[openid]."' AND fwstgy=1");
                    if ($a['money'] == null){
                        $list[$k]['wei'] = '0.00';
                    }else{
                        $list[$k]['wei'] = $a['money'];
                    }
                    if($b){
                        $list[$k]['yi'] = $b;
                    }else{
                        $list[$k]['yi'] = '0.00';
                    }
                }
                $this->cvsDo($filter,$list);exit;
            }
            require $this->template('extend');exit;
        }
        if ($_GPC['export'] != '') {
            $list = pdo_fetchall("SELECT * FROM ".tablename($this->_staff)." WHERE wid = '".$this->_wid."' AND tgystate=1");
            foreach($list as $k=>$v){
                $a = $this->myGet($this->_fwstgy,array('openid'=>$v[openid],'state'=>0,'fwstgy'=>1));
                $b = pdo_fetchcolumn("SELECT SUM(money) FROM ".tablename($this->_fwstgy)." WHERE state=2 AND openid='".$v[openid]."' AND fwstgy=1");
                if ($a['money'] == null){
                    $list[$k]['wei'] = '0.00';
                }else{
                    $list[$k]['wei'] = $a['money'];
                }
                if($b){
                    $list[$k]['yi'] = $b;
                }else{
                    $list[$k]['yi'] = '0.00';
                }
            }
            $this->cvsDo($filter,$list);exit;
        }
        require $this->template('extend');
    }
    //推广员审核
    public function doWebExtendsh(){
        global $_W,$_GPC;
        $list = $this->myGetall($this->_staff,array('wid'=>$this->_wid,'tgy'=>1,'tgystate'=>0,'backadmin'=>0));
        if ($_GPC['id'] != null){
            $data['tgystate'] = 1;
            $li = $this->myGet($this->_staff,array('id'=>intval($_GPC['id']),'wid'=>$this->_wid));
            if ($li['qrcode']==null){
                $qr = $this->qrAdd();
                $data = array_merge($data,$qr);
            }
            $succ = $this->myUpdate($this->_staff,$data,array('wid'=>$this->_wid,'openid'=>$li['openid']),'AND');
            if ($succ) message('审核通过',$this->createWebUrl('extendsh'),'success');
        }
        include $this->template('extendsh');
    }

//    public function doWebExtenddel(){
//        global $_W,$_GPC;
//        $succ = $this->myUpdate($this->_staff,array('tgystate'=>0),array('wid'=>$this->_wid,'id'=>intval($_GPC['id'])),'AND');
//        $this->succError($succ,$this->createWebUrl('extend'),2);
//    }

    //推广提成订单
    public function doWebExtendtcdd(){
        global $_W,$_GPC;
        $arr =array();
        $www = $this->myGetall($this->_user,array('wid'=>$this->_wid,'tgyopenid'=>$_GPC['id']));
        foreach ($www as $vv){
            $fwsk = pdo_fetchall("SELECT * FROM ".tablename($this->_orderg)." WHERE wid = '".$this->_wid."' AND openid= '".$vv['openid']."' AND state=3 OR wid = '".$this->_wid."' AND openid= '".$vv['openid']."' AND state=4");
            array_push($arr,$fwsk);
        }
        $list = $arr[0];
        include $this->template('orderg');
    }
    //推广客户
    public function doWebExtendtgr(){
        global $_W,$_GPC;
        $all =$this->myPager($this->_user,$_GPC['page']," AND tgyopenid ='". $_GPC['id']."'");
        $list = $all['all'];
        $pager = $all['pager'];
        require $this->template('memberlist');
    }
    //后台评价
    public function doWebAssess(){
        global $_W,$_GPC;
        if ($_GPC['sername'] != null){
            $all = $this->myPager($this->_assess,$_GPC['page']," AND sername='".$_GPC['sername']."'");
            $list = $all['all'];
            $pager = $all['pager'];
            require $this->template('assess');
        }
        $all = $this->myPager($this->_assess,$_GPC['page']);
        $list = $all['all'];
        $pager = $all['pager'];
        require $this->template('assess');
    }
    //评分计算 efwww_com
    public function doWebAssessNum(){
        global $_W,$_GPC;
        $all = $this->myPager($this->_staff,$_GPC['page']);
        $list = $all['all'];
        $pager = $all['pager'];
        $num1 = array();
        foreach($list as $k => $v){
            $num = $this->myGetall($this->_assess,array('wid'=>$this->_wid,'sername'=>$list[$k]['name']),array('pjnum'));
            if($num != null){
                foreach($num as $key => $val){
                    array_push($num1,$val['pjnum']);
                }
                $list[$k]['num'] = array_sum($num1);
            }
        }
        require $this->template('assessnum');
    }


    public function doMobilePaywechat()
    {
    }
}
