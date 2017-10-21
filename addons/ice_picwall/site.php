<?php
defined('IN_IA') or exit('Access Denied');
class Ice_picwallModuleSite extends WeModuleSite
{
    public function doMobileIndex()
    {
        global $_GPC, $_W;
        $openid    = $_W['openid'];
        $uniacid   = $_W['uniacid'];
        $sql       = 'SELECT count(*) FROM ' . tablename('ice_picWallMain') . 'where uniacid = :uniacid';
        $param     = array(
            ':uniacid' => $uniacid
        );
        $total     = pdo_fetchcolumn($sql, $param);
        $psize     = 6;
        $totalSize = 1;
        if ($total % $psize == 0) {
            $totalSize = $total / $psize;
        } else {
            $totalSize = $total / $psize + 1;
        }
        $account = pdo_fetch("SELECT * FROM " . tablename('account_wechats') . " WHERE uniacid=" . $uniacid);
        include $this->template('picwall');
    }
    public function doMobilegetInfo()
    {
        global $_W, $_GPC;
        $openid = $_W['openid'];
        $info   = pdo_fetch("SELECT * FROM " . tablename('ice_picWallUserinfo') . " WHERE openid = :openid ", array(
            ':openid' => $_W['openid']
        ));
        include $this->template('PersonalInfo');
    }
    public function doMobiledogetInfo()
    {
        global $_W, $_GPC;
        $data = array(
            'uniacid' => $_W['uniacid'],
            'uname' => $_GPC['uName'],
            'openid' => $_W['openid'],
            'phone' => $_GPC['phoneNum']
        );
        $ds   = pdo_insert('ice_picWallUserinfo', $data);
        if ($ds) {
            echo 'success';
        } else {
            echo 'fail';
        }
    }
    public function doMobilegetPage()
    {
        global $_GPC, $_W;
        $likeLink = $_GPC['likeLink'];
        $uniacid  = $_W['uniacid'];
        $openid   = $_W['openid'];
        $sql      = 'SELECT count(*) FROM ' . tablename('ice_picWallMain') . 'where uniacid = :uniacid and status = :status';
        $param    = array(
            ':uniacid' => $uniacid,
            ':status' => '1'
        );
        $total    = pdo_fetchcolumn($sql, $param);
        $sql      = 'SELECT * FROM ' . tablename('ice_picWallMain') . 'where uniacid = :uniacid and status = :status order by showID DESC';
        $pindex   = max(1, intval($_GPC['pageIndex']));
        $psize    = 6;
        $dataList = $this->reply_searchPic($sql, $param, $pindex, $psize, $total);
        echo $this->re_data($dataList, $likeLink);
    }
    public function doMobilelikePage()
    {
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $openid  = $_W['openid'];
        load()->func('logging');
        logging_run($openid, '', 'openid');
        if (empty($openid)) {
            echo "error";
            exit();
        }
        $acid = intval($_W['account']['uniacid']);
        $acc  = WeAccount::create($acid);
        $fan  = $acc->fansQueryInfo($_W['openid'], true);
//        if (intval($fan['errno']) == -1) {
 //           echo "noway";
  //          exit();
  //      }
   //     if ($fan['subscribe'] != '1') {
    //        echo "unsub";
      //      exit();
      //  }
        $type   = 1;
        $result = $this->pic_limit($type);
        if ($result) {
            echo 'liked';
        } else {
            $param = array(
                'uniacid' => $uniacid,
                'openid' => $openid,
                'tousername' => $tousername,
                'time' => date("Y-m-d H:i:s", time()),
                'picid' => $_GPC['personalPicWallId']
            );
            pdo_insert('ice_picWallLikelist', $param);
            $param2 = array(
                ':uniacid' => $uniacid,
                'picid' => $_GPC['personalPicWallId']
            );
            $sql    = 'SELECT count(openid) FROM ' . tablename('ice_picWallLikelist') . ' WHERE uniacid = :uniacid and picid = :picid';
            $column = pdo_fetchcolumn($sql, $param2);
            load()->func('logging');
            logging_run('点赞数据更新：' . $column, '', 'column');
            if (intval($column) > 0) {
                pdo_update('ice_picWallMain', array(
                    'likenum' => $column
                ), array(
                    'id' => $_GPC['personalPicWallId']
                ));
            }
            echo $column;
        }
    }
    public function doWebManage()
    {
        global $_GPC, $_W;
        $uniacid  = $_W['uniacid'];
        $sql      = "SELECT count(*) FROM " . tablename('ice_picWallMain') . " WHERE uniacid = :uniacid ";
        $total    = pdo_fetchcolumn($sql, array(
            ':uniacid' => $uniacid
        ));
        $pindex   = max(1, intval($_GPC['page']));
        $psize    = 10;
        $pager    = pagination($total, $pindex, $psize);
        $str      = 'select a.*,b.uname,b.phone from ' . tablename('ice_picWallMain') . ' a LEFT JOIN ' . tablename('ice_picWallUserinfo') . ' b on a.openid = b.openid  where a.uniacid =:uniacid group by a.id order by a.likenum DESC';
        $param    = array(
            ':uniacid' => $uniacid
        );
        $ds       = $this->reply_searchPic($str, $param, $pindex, $psize, $total);
        $i        = 0;
        $j        = 1;
        $listdata = array();
        foreach ($ds as &$row) {
            $list['sort']    = ($pindex - 1) * $psize + $j++;
            $list['id']      = $row['id'];
            $list['showID']  = $row['showID'];
            $list['imgurl']  = $_W['attachurl'] . $row['imgurl'];
            $list['uname']   = $row['uname'];
            $list['phone']   = $row['phone'];
            $list['status']  = $row['status'];
            $list['likeNum'] = $row['likeNum'];
            $list['time']    = Date('Y-m-d H:i:s', $row['time']);
            $listdata[$i++]  = $list;
        }
        include $this->template('managelist');
    }
    public function doWebdelete()
    {
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        if ($_GPC['op'] == 'delete') {
            pdo_update('ice_picWallMain', array(
                'status' => '0'
            ), array(
                'id' => $_GPC['id']
            ));
            message('隐藏成功！', $this->createWebUrl('Manage', array(
                'foo' => 'managelist'
            )), 'success');
        } else if ($_GPC['op'] == 'edit') {
            pdo_update('ice_picWallMain', array(
                'status' => '1'
            ), array(
                'id' => $_GPC['id']
            ));
            message('显示成功！', $this->createWebUrl('Manage', array(
                'foo' => 'managelist'
            )), 'success');
        } else if ($_GPC['op'] == 'reldelete') {
            pdo_delete('ice_picWallMain', array(
                'id' => $_GPC['id']
            ));
            message('删除成功！', $this->createWebUrl('Manage', array(
                'foo' => 'managelist'
            )), 'success');
        }
    }
    private function reply_searchPic($sql, $param, $pindex = 0, $psize = 6, &$total = 0)
    {
        if ($pindex > 0) {
            $start = ($pindex - 1) * $psize;
            $sql .= " LIMIT {$start},{$psize}";
        }
        return pdo_fetchall($sql, $param);
    }
    private function re_data($array, $likeLink)
    {
        global $_W;
        $i    = 0;
        $html = '
		<li>
			<div class="pic">
				<div style="POSITION: absolute; TOP: 10px; left: 10px;" class="imgNo">%s</div>
				<a onclick="popUp(this)"; name="img_0">
					<img src="%s" width="127" height=auto></img>
				</a>
			</div>
			<H2>
				<span class="hot">
					<a style="COLOR: red" onclick="del2(%s,this)">
						<img  align="absMiddle" src="../addons/ice_picwall/img/xin.png"></img>
					</a>&nbsp;&nbsp;
					<span id="likeNum">%s</span>
				</span>
				<span class="right">%s</span>
			</H2>
			<div class="photo"></div>
		</li>';
        load()->func('logging');
        $strResultleft  = '';
        $strResultright = '';
        foreach ($array as $key => $value) {
            $str = sprintf($html, $value['showID'], $_W['attachurl'] . $value['imgurl'], $value['id'], $value['likeNum'], $value['time']);
            if (!($i % 2)) {
                $strResultleft .= $str;
            } else {
                $strResultright .= $str;
            }
            $i++;
        }
        $strResult = $strResultleft . '~' . $strResultright;
        return $strResult;
    }
    private function pic_limit($type, $num = 1)
    {
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $openid  = $_W['openid'];
        load()->func('logging');
        $result = FALSE;
        if ($type == '1') {
            $sql    = 'SELECT count(openid) FROM ' . tablename('ice_picWallLikelist') . ' WHERE to_days(time) = to_days(now()) and openid =:openid and uniacid =:uniacid and picid =:picid';
            $param  = array(
                ':openid' => $openid,
                ':uniacid' => $uniacid,
                ':picid' => $_GPC['personalPicWallId']
            );
            $column = pdo_fetchcolumn($sql, $param);
            logging_run('数据是：' . json_encode($column), '', 'limit1');
            if ($column >= 1) {
                $result = TRUE;
            }
        } elseif ($type == '2') {
            $sql    = 'SELECT count(openid) FROM ' . tablename('ice_picWallLikelist') . ' WHERE openid =:openid';
            $param  = array(
                ':openid' => $openid
            );
            $column = pdo_fetchcolumn($sql, $param);
            logging_run('数据是：' . json_encode($column), '', 'limit2');
            if ($column >= 1) {
                $result = TRUE;
            }
        }
        return $result;
    }
}