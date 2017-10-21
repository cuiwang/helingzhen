<?php
/**
 * @author Zombieszy
 * qq:214983937
 */
defined('IN_IA') or exit('Access Denied');
define('APP_PUBLIC', './source/modules/activity/');

class We7_ActivityModuleSite extends WeModuleSite
{

    public $activitytalbe = "activity";

    public $activityday = "activity_day";

    public $activityguest = "activity_guest";

    public $activityuser = "activity_user";

    public $activitymail = "activity_mail";

    public $note = "activity_note";

    public $table_reply = 'activity_reply';

    /**
     * 活动管理
     */
    public function doWebActivityManger()
    {
        global $_W, $_GPC;
        $operation = ! empty($_GPC['op']) ? $_GPC['op'] : 'display';
        
        if ($operation == 'post') { // 添加
            $id = intval($_GPC['id']);
            
            if (! empty($id)) {
                $item = pdo_fetch("SELECT * FROM " . tablename($this->activitytalbe) . " WHERE id = :id", array(
                    ':id' => $id
                ));
                if (empty($item)) {
                    message('抱歉，活动删除或不存在！', '', 'error');
                }
                
                $item['begintime'] = date("Y-m-d  H:i", $item['begintime']);
                $item['endtime'] = date("Y-m-d  H:i", $item['endtime']);
            }
            if (checksubmit('submit')) {
                if (empty($_GPC['acname'])) {
                    message('请输入活动名称!');
                }
                
                if (empty($_GPC['begintime'])) {
                    message('请输入活动开始时间!');
                }
                
                if (empty($_GPC['endtime'])) {
                    message('请输入活动结束时间!');
                }
                
                if (empty($_GPC['countlimit'])) {
                    message('请输入活动人数限制');
                }
                
                if (empty($_GPC['ac_pic'])) {
                    message('请选择活动背景！');
                }
                
                if (empty($_GPC['address'])) {
                    message('请输入活动地址！');
                }
                if (empty($_GPC['acdes'])) {
                    message('请输入活动内容！');
                }
                
                $data = array(
                    'weid' => $_W['uniacid'],
                    'name' => $_GPC['acname'],
                    'ac_pic' => $_GPC['ac_pic'],
                    'ppt1' => $_GPC['ppt1'],
                    'ppt2' => $_GPC['ppt2'],
                    'ppt3' => $_GPC['ppt3'],
                    'address' => $_GPC['address'],
                    'location_p' => trim($_GPC['location_p']),
                    'location_c' => trim($_GPC['location_c']),
                    'location_a' => trim($_GPC['location_a']),
                    'lng' => trim($_GPC['lng']),
                    'lat' => trim($_GPC['lat']),
                    'countlimit' => $_GPC['countlimit'],
                    'tel' => $_GPC['tel'],
                    'email' => $_GPC['email'],
                    'zb' => $_GPC['zb'],
                    'cb' => $_GPC['cb'],
                    'xb' => $_GPC['xb'],
                    'cjdx' => $_GPC['cjdx'],
                    'acdes' => htmlspecialchars_decode($_GPC['acdes']),
                    'countvirtual' => $_GPC['countvirtual'],
                    'begintime' => strtotime($_GPC['begintime']),
                    'endtime' => strtotime($_GPC['endtime']),
                    'createtime' => TIMESTAMP,
                    'isrepeat' => intval($_GPC['isrepeat']),
                    'istip' => intval($_GPC['istip'])
                );
                if (! empty($id)) {
                    pdo_update($this->activitytalbe, $data, array(
                        'id' => $id
                    ));
                } else {
                    pdo_insert($this->activitytalbe, $data);
                }
                message('更新微活动成功！', $this->createWebUrl("activityManger", array(
                    "op" => "display"
                )), 'success');
            }
        } elseif ($operation == 'display') {
            $pindex = max(1, intval($_GPC['page']));
            
            $psize = 20;
            
            $list = pdo_fetchall("SELECT * FROM " . tablename($this->activitytalbe) . " WHERE weid = '{$_W['uniacid']}'  ORDER BY createtime DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->activitytalbe) . " WHERE weid = '{$_W['uniacid']}'");
            $pager = pagination($total, $pindex, $psize);
        } elseif ($operation == 'delete') {
            $id = intval($_GPC['id']);
            
            pdo_delete($this->activityday, array(
                'aid' => $id
            )); // 删除日程表
            pdo_delete($this->activityguest, array(
                'aid' => $id
            )); // 删除嘉宾表
                // 报名用户
            pdo_delete($this->activityuser, array(
                'aid' => $id
            ));
            
            // 报名说明项
            pdo_delete($this->note, array(
                'aid' => $id
            ));
            
            // 删除活动
            pdo_delete($this->activitytalbe, array(
                'id' => $id
            ));
            
            message('删除成功！', referer(), 'success');
        }
        
        load()->func('tpl');
        include $this->template('activity');
    }

    public function geturl($type = 0)
    {
        switch ($type) {
            case 0:
                $img_url = './source/modules/we7_activity/images/ac_pic.jpg';
                break;
            case 1:
                $img_url = './source/modules/we7_activity/images/ppt1.jpg';
                break;
            case 2:
                $img_url = './source/modules/we7_activity/images/ppt2.jpg';
                break;
            case 3:
                $img_url = './source/modules/we7_activity/images/ppt3.jpg';
                break;
        }
        return $img_url;
    }

    /**
     * 日程管理
     */
    public function doWebDayManger()
    {
        global $_W, $_GPC;
        $operation = ! empty($_GPC['op']) ? $_GPC['op'] : 'display';
        $aid = $_GPC['aid'];
        $id = $_GPC['id'];
        if ($operation == 'display') { // 日程显示
            $list = pdo_fetchall("SELECT * FROM " . tablename($this->activityday) . " WHERE aid = '$aid'  ORDER BY daytime DESC");
        } elseif ($operation == 'add') { // 日程添加
            
            if (! empty($id)) {
                $item = pdo_fetch("SELECT * FROM " . tablename($this->activityday) . " WHERE id = :id", array(
                    ':id' => $id
                ));
                if (empty($item)) {
                    message('抱歉，日程删除或不存在！', '', 'error');
                }
                
                $item['daytime'] = date("Y-m-d  H:i", $item['daytime']);
            }
            if (checksubmit('submit')) {
                if (empty($_GPC['dname'])) {
                    message('请输入日程名称!');
                }
                if (empty($_GPC['daytime'])) {
                    message('请选择日程时间！');
                }
                
                $daytime = strtotime($_GPC['daytime']);
                
                $data = array(
                    
                    'dname' => $_GPC['dname'],
                    'daytime' => $daytime,
                    'aid' => $aid,
                    'ddes' => $_GPC['ddes']
                );
                if (! empty($id)) {
                    pdo_update($this->activityday, $data, array(
                        'id' => $id
                    ));
                } else {
                    pdo_insert($this->activityday, $data);
                }
                message('更新活动日程成功！', $this->createWebUrl('dayManger', array(
                    'op' => 'display',
                    'aid' => $aid
                )), 'success');
            }
        } elseif ($operation == 'delete') {
            
            pdo_delete($this->activityday, array(
                'id' => $id
            )); // 删除日程表
            
            message('删除成功！', referer(), 'success');
        }
        
        load()->func('tpl');
        include $this->template("day");
    }

    /**
     * 说明项管理
     */
    public function doWebnoteManger()
    {
        global $_W, $_GPC;
        $operation = ! empty($_GPC['op']) ? $_GPC['op'] : 'display';
        $aid = $_GPC['aid'];
        $id = $_GPC['id'];
        if ($operation == 'display') { // 日程显示
            $list = pdo_fetchall("SELECT * FROM " . tablename($this->note) . " WHERE aid = '$aid'");
        } elseif ($operation == 'add') { // 日程添加
            
            if (! empty($id)) {
                $item = pdo_fetch("SELECT * FROM " . tablename($this->note) . " WHERE id = :id", array(
                    ':id' => $id
                ));
                if (empty($item)) {
                    message('抱歉，日程删除或不存在！', '', 'error');
                }
            }
            if (checksubmit('submit')) {
                if (empty($_GPC['title'])) {
                    message('请输入说明项名称!');
                }
                if (empty($_GPC['ndesc'])) {
                    message('请输入说说明项内容！');
                }
                
                $data = array(
                    
                    'title' => $_GPC['title'],
                    'ndesc' => htmlspecialchars_decode($_GPC['ndesc']),
                    'aid' => $aid
                );
                if (! empty($id)) {
                    pdo_update($this->note, $data, array(
                        'id' => $id
                    ));
                } else {
                    pdo_insert($this->note, $data);
                }
                message('更新活动说明项成功！', $this->createWebUrl('noteManger', array(
                    'name' => 'we7_activity',
                    'op' => 'display',
                    'aid' => $aid
                )), 'success');
            }
        } elseif ($operation == 'delete') {
            
            pdo_delete($this->note, array(
                'id' => $id
            )); // 删除日程表
            
            message('删除成功！', referer(), 'success');
        }
        
        include $this->template("note");
    }

    /**
     * 报名管理
     */
    public function doWebApplyManger()
    {
        global $_W, $_GPC;
        $operation = ! empty($_GPC['op']) ? $_GPC['op'] : 'display';
        $aid = $_GPC['aid'];
        $id = $_GPC['id'];
        if ($operation == 'display') {
            $activity = pdo_fetch("SELECT * FROM " . tablename($this->activitytalbe) . " WHERE id = :id", array(
                ':id' => $aid
            ));
            $pindex = max(1, intval($_GPC['page']));
            $psize = 20;
            $list = pdo_fetchall("SELECT * FROM " . tablename($this->activityuser) . " WHERE aid = '{$aid}'  ORDER BY createtime DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->activityuser) . " WHERE aid = '{$aid}' ");
            $pager = pagination($total, $pindex, $psize);
        } elseif ($operation == 'delete') {
            
            pdo_delete($this->activityuser, array(
                'id' => $id
            )); // 删除日程表
            
            message('删除成功！', referer(), 'success');
        }
        
        include $this->template("user");
    }

    /**
     * 提醒设置
     */
    public function doWebTip()
    {
        global $_W, $_GPC;
        $operation = ! empty($_GPC['op']) ? $_GPC['op'] : 'display';
        $weid = $_W['weid'];
        
        $id = $_GPC['id'];
        if ($operation == 'display') { // 日程显示
            $list = pdo_fetchall("SELECT * FROM " . tablename($this->activitymail) . " WHERE weid = '$weid'");
        } elseif ($operation == 'add') { // 日程添加
            
            if (! empty($id)) {
                $item = pdo_fetch("SELECT * FROM " . tablename($this->activitymail) . " WHERE id = :id", array(
                    ':id' => $id
                ));
                if (empty($item)) {
                    message('抱歉，日程删除或不存在！', '', 'error');
                }
            }
            if (checksubmit('submit')) {
                if (empty($_GPC['email'])) {
                    message('请填写邮件！');
                }
                
                $data = array(
                    
                    'email' => $_GPC['email'],
                    'weid' => $weid
                );
                if (! empty($id)) {
                    pdo_update($this->activitymail, $data, array(
                        'id' => $id
                    ));
                } else {
                    pdo_insert($this->activitymail, $data);
                }
                message('更新邮件项成功！', $this->createWebUrl('tip', array(
                    
                    'op' => 'display'
                )), 'success');
            }
        } elseif ($operation == 'delete') {
            
            pdo_delete($this->activitymail, array(
                'id' => $id
            )); // 删除日程表
            
            message('删除成功！', referer(), 'success');
        }
        
        include $this->template("mail");
    }

    /**
     * 数据下载
     */
    public function doWebuserDownload()
    {
        global $_W, $_GPC;
        
        $aid = $_GPC['aid'];
        
        $activity = pdo_fetch("SELECT * FROM " . tablename($this->activitytalbe) . " WHERE id = :id", array(
            ':id' => $aid
        ));
        
        $tx = $activity['name'] . "活动报名数据";
        $tx = iconv("UTF-8", "GB2312", $tx);
        
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=" . $activity['name']);
        
        echo $tx . "\n";
        
        // 输出内容如下：
        
        echo iconv("UTF-8", "GB2312", "姓名" . "\t");
        echo iconv("UTF-8", "GB2312", "性别" . "\t");
        echo iconv("UTF-8", "GB2312", "电话" . "\t");
        echo iconv("UTF-8", "GB2312", "邮箱" . "\t");
        echo iconv("UTF-8", "GB2312", "公司" . "\t");
        echo iconv("UTF-8", "GB2312", "职位" . "\t");
        echo iconv("UTF-8", "GB2312", "报名时间" . "\t");
        
        echo "\n";
        
        $list = pdo_fetchall("SELECT * FROM " . tablename($this->activityuser) . " WHERE aid = '{$aid}'  ORDER BY createtime DESC");
        
        foreach ($list as $item) {
            echo iconv("UTF-8", "GB2312", $item['uname'] . "\t");
            echo iconv("UTF-8", "GB2312", $item['sex'] . "\t");
            
            echo iconv("UTF-8", "GB2312", $item['tel'] . "\t");
            echo iconv("UTF-8", "GB2312", $item['email'] . "\t");
            echo iconv("UTF-8", "GB2312", $item['company'] . "\t");
            echo iconv("UTF-8", "GB2312", $item['jobtitle'] . "\t");
            echo iconv("UTF-8", "GB2312", date('Y-m-d H:i:s', $item['createtime']) . "\t");
            
            echo "\n";
        }
    }

    /**
     * 嘉宾管理
     */
    public function doWebGuestManger()
    {
        global $_W, $_GPC;
        $operation = ! empty($_GPC['op']) ? $_GPC['op'] : 'display';
        $aid = $_GPC['aid'];
        $id = $_GPC['id'];
        if ($operation == 'display') { // 日程显示
            $list = pdo_fetchall("SELECT * FROM " . tablename($this->activityguest) . " WHERE aid = '$aid' ");
        } elseif ($operation == 'add') { // 日程添加
            
            if (! empty($id)) {
                $item = pdo_fetch("SELECT * FROM " . tablename($this->activityguest) . " WHERE id = :id", array(
                    ':id' => $id
                ));
                if (empty($item)) {
                    message('抱歉，日程删除或不存在！', '', 'error');
                }
            }
            if (checksubmit('submit')) {
                if (empty($_GPC['gname'])) {
                    message('请输入嘉宾名称');
                }
                
                if (empty($_GPC['headimage'])) {
                    message('请选择嘉宾头像！');
                }
                
                if (empty($_GPC['jobtitle'])) {
                    message('请输入嘉宾职位！');
                }
                
                if (empty($_GPC['sig'])) {
                    message('请输入嘉宾签名！');
                }
                
                if (empty($_GPC['gdesc'])) {
                    message('请输入嘉宾说明！');
                }
                
                $data = array(
                    'gname' => $_GPC['gname'],
                    'headimage' => $_GPC['headimage'],
                    'sig' => $_GPC['sig'],
                    'jobtitle' => $_GPC['jobtitle'],
                    'gdesc' => htmlspecialchars_decode($_GPC['gdesc']),
                    
                    'aid' => $aid
                );
                if (! empty($id)) {
                    pdo_update($this->activityguest, $data, array(
                        'id' => $id
                    ));
                } else {
                    pdo_insert($this->activityguest, $data);
                }
                message('更新嘉宾成功！', $this->createWebUrl('guestManger', array(
                    'name' => 'we7_activity',
                    'op' => 'display',
                    'aid' => $aid
                )), 'success');
            }
        } elseif ($operation == 'delete') {
            
            pdo_delete($this->activityguest, array(
                'id' => $id
            )); // 删除日程表
            
            message('删除成功！', referer(), 'success');
        }
        
        load()->func('tpl');
        include $this->template("guest");
    }

    /**
     * 更新浏览次数
     */
    public function updateVisitsCount($id)
    {
        $activity = pdo_fetch("SELECT * FROM " . tablename($this->activitytalbe) . " WHERE id = :id", array(
            ':id' => $id
        ));
        
        $vCount = intval($activity['visitsCount']) + 1;
        $data = array(
            'visitsCount' => $vCount
        );
        
        if (! empty($id)) {
            pdo_update($this->activitytalbe, $data, array(
                'id' => $id
            ));
        }
    }

    /**
     * 手机活动页面
     */
    public function doMobileActivity()
    {
        global $_W, $_GPC;
        
        $id = intval($_GPC['id']);
        
        $rid = intval($_GPC['rid']);
       
   
        if (! empty($id)) {
           
            $activity = pdo_fetch("SELECT * FROM " . tablename($this->activitytalbe) . " WHERE id = :id and weid=:weid", array(
                ':id' => $id,
                ':weid' => $_W['uniacid']
            ));
            
            if (empty($activity)) {
                message('抱歉，活动删除或不存在！', '', 'error');
            } else {
                
                $sharepic = $activity['ac_pic'];
                $shareContent = $activity['name'];
                if (! empty($rid)) {
                    
                    $reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE id = :rid", array(
                        ':rid' => $rid
                    ));
                    $sharepic = $reply['new_pic'];
                    $shareContent = $reply['news_content'];
                }
                
                $allowApply = 'true';
                $applyBtnText = "报名";
                
                if (TIMESTAMP > $activity['endtime']) {
                    $applyBtnText = "活动已结束！";
                    $allowApply = 'false';
                } else {
                    if (! $this->isAllowApply($id)) {
                        $applyBtnText = "报名人数已满！";
                        $allowApply = 'false';
                    }
                }
                
                $openid = $_W['fans']['from_user'];
                $user = pdo_fetch("SELECT * FROM " . tablename($this->activityuser) . " WHERE openid = :openid AND aid=:aid", array(
                    ':openid' => $openid,
                    ':aid' => $id
                ));
                
                if ($user) {
                   // $applyBtnText = "已报名！";
                  //  $allowApply = 'true';
                }
                
                $applyCount = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->activityuser) . " WHERE  aid=:aid", array(
                    
                    ':aid' => $id
                ));
                
                $applyCount = $applyCount + intval($activity['countvirtual']); // 已报名人数+虚拟报名人数
                $applyLimit = intval($activity['countvirtual']) + intval($activity['countlimit']); // 虚拟人数+人数限制
                
                $guest = pdo_fetchall("SELECT * FROM " . tablename($this->activityguest) . " WHERE aid = :aid", array(
                    ':aid' => $activity['id']
                )); // 所有嘉宾
                
                $addguest = pdo_fetchall("SELECT * FROM " . tablename($this->activityguest) . " WHERE aid = :aid and id%2=1", array(
                    ':aid' => $activity['id']
                )); // 奇数
                
                $evenguest = pdo_fetchall("SELECT * FROM " . tablename($this->activityguest) . " WHERE aid = :aid and id%2=0", array(
                    ':aid' => $activity['id']
                )); // 偶数
                
                $daylist = pdo_fetchall("SELECT * FROM " . tablename($this->activityday) . " WHERE aid = :aid order by daytime asc", array(
                    ':aid' => $activity['id']
                ));
                
                $notelist = pdo_fetchall("SELECT * FROM " . tablename($this->note) . " WHERE aid = :aid", array(
                    ':aid' => $activity['id']
                ));
                
                $addressDetail = '{"detal":"{\"address\":\"' . $activity['address'] . '\"}","latitude":"' . $activity['lat'] . '","longitude":"' . $activity['lng'] . '"}';
            }
        }
        $this->updateVisitsCount($id);
        
      
        include $this->template("activity");
    }

    public function doWebQuery()
    {
        global $_W, $_GPC;
        $kwd = $_GPC['keyword'];
        $sql = 'SELECT * FROM ' . tablename($this->activitytalbe) . ' WHERE `weid`=:weid AND `name` LIKE :name';
        $params = array();
        $params[':weid'] = $_W['weid'];
        $params[':name'] = "%{$kwd}%";
        $ds = pdo_fetchall($sql, $params);
        foreach ($ds as &$row) {
            $r = array();
            $r['name'] = $row['name'];
            $r['acdes'] = $row['acdes'];
            $r['ac_pic'] = $row['ac_pic'];
            $r['id'] = $row['id'];
            $row['entry'] = $r;
        }
        include $this->template('query');
    }
    
    // 报名
    public function doMobileapply()
    {
        global $_W, $_GPC;
        
        $openid = $_W['fans']['from_user'];
        $aid = $_GPC['aid'];
        $data = array(
            'uname' => $_GPC['uname'],
            'acname' => $_GPC['acname'],
            'sex' => $_GPC['radio'],
            'tel' => $_GPC['tel'],
            'email' => $_GPC['email'],
            'company' => $_GPC['company'],
            'aid' => $_GPC['aid'],
            'jobtitle' => $_GPC['job'],
            'openid' => $openid,
            'aid' => $aid,
            'createtime' => TIMESTAMP
        );
        
        $item = pdo_fetch("SELECT * FROM " . tablename($this->activityuser) . " WHERE openid = :openid AND aid=:aid", array(
            ':openid' => $openid,
            ':aid' => $aid
        ));
        
        if (! $this->isAllowApply($aid)) {
            $arr = array(
                'code' => 402,
                'msg' => '报名人数已达上线!'
            );
            echo json_encode($arr);
            exit();
        }
        
        $activity = pdo_fetch("SELECT * FROM " . tablename($this->activitytalbe) . " WHERE id = :id", array(
            ':id' => $aid
        ));
        
        $isRepeat = intval($activity['isrepeat']);
        
        if ($isRepeat == 0 && ! empty($item)) {
            $arr = array(
                'code' => 201,
                'msg' => "已经报过名了，不可以重复报名!"
            );
            echo json_encode($arr);
            exit();
        }
        
        pdo_insert($this->activityuser, $data);
        
        $id = pdo_insertid();
        if ($id) {
            $url = $this->createMobileUrl('detail');
            $arr = array(
                'code' => 200,
                'msg' => "报名成功"
            );
            
            $istip = intval($activity['istip']);
            
            if ($istip == 1) { // 发送邮件
                $this->sendMail($activity['name'], $data);
            }
            
            echo json_encode($arr);
            exit();
        }
        
        /*
         * if (empty($item)) {
         *
         * pdo_insert($this->activityuser, $data);
         *
         * $id = pdo_insertid();
         * if ($id) {
         * $url = $this->createMobileUrl('detail');
         * $arr = array(
         * 'code' => 200,
         * 'msg' => "报名成功"
         * );
         * echo json_encode($arr);
         * exit();
         * }
         * }
         */
    }

    /**
     * 发送邮件提醒
     */
    public function sendMail($acname, $applyUser)
    {
        global $_W, $_GPC;
        
        $weid = $_W['weid'];
        
        $list = pdo_fetchall("SELECT * FROM " . tablename($this->activitymail) . " WHERE weid = '$weid'");
        
        $data = array(
            'uname' => $_GPC['uname'],
            'acname' => $_GPC['acname'],
            'sex' => $_GPC['radio'],
            'tel' => $_GPC['tel'],
            'email' => $_GPC['email'],
            'company' => $_GPC['company'],
            'aid' => $_GPC['aid'],
            'jobtitle' => $_GPC['job'],
            'openid' => $openid,
            'aid' => $aid,
            'createtime' => TIMESTAMP
        );
        
        $body = "用户名:" . $applyUser['uname'] . "\t性别:" . $applyUser['sex'] . "电话:" . $applyUser['tel'] . "公司:" . $applyUser['company'] . "职位:" . $applyUser['jobtitle'];
       
	   load()->func('communication');
		
        foreach ($list as $mail) {
            
            ihttp_email($mail['email'], $acname . "报名用户提醒！", $body . "\n已报名");
        }
    }

    /**
     * 检查是否可以继续申请
     * 
     * @param unknown $aid            
     * @return boolean
     */
    public function isAllowApply($aid)
    {
        $activity = pdo_fetch("SELECT * FROM " . tablename($this->activitytalbe) . " WHERE id = :id", array(
            ':id' => $aid
        ));
        
        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->activityuser) . " WHERE  aid=:aid", array(
            
            ':aid' => $aid
        ));
        
        $appCountLimit = intval($activity['countlimit']);
        
        return $total < $appCountLimit;
    }

    public function getSubstrCut($str_cut, $length)
    {
        if (strlen($str_cut) > $length) {
            for ($i = 0; $i < $length; $i ++)
                if (ord($str_cut[$i]) > 128)
                    $i ++;
            $str_cut = mb_strcut($str_cut, 0, $i, 'utf-8') . "..";
        }
        return $str_cut;
    }
}