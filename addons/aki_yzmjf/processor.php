<?php


include(IA_ROOT . '/addons/aki_yzmjf/util/emoji.php');
defined('IN_IA') or exit('Access Denied');
class Aki_yzmjfModuleProcessor extends WeModuleProcessor
{
    public function respond()
    {
        $content = $this->message['content'];
        global $_W, $_GPC;
        $openid = $_W['openid'];
        if (strlen($content) <= 2) {
            return $this->respText("对不起,验证码错误！");
        }
        $count = pdo_fetchcolumn("select count(*) from " . tablename("aki_yzmjf_user") . " where uniacid = :uniacid and openid = :openid", array(
            ":uniacid" => $_W['uniacid'],
            ":openid" => $openid
        ));
        if ($count == 0) {
            $dat = array(
                'uniacid' => $_W['uniacid'],
                'openid' => $openid
            );
            pdo_insert('aki_yzmjf_user', $dat);
        }
        $code    = substr($content, 6);
        $res     = pdo_fetch("select count(*) as count,id,code,yzmjfid,piciid,jifen  from " . tablename("aki_yzmjf_code") . " where uniacid = :uniacid and  code = :code and status = 1  and yzmjfid = 0", array(
            ':code' => $code,
            ':uniacid' => $_W['uniacid']
        ));
        $count   = $res['count'];
        $cid     = $res['id'];
        $piciid  = $res['piciid'];
        $jifen   = $res['jifen'];
        $yzmjfid = $res['yzmjfid'];
        $msg     = "";
        if ($count == 0) {
            $msg = "对不起,验证码错误！或者已经被使用!";
        } else {
            $memberuid = $_W['member']['uid'];
            load()->model('mc');
            $jifen1  = 0;
            $jifen2  = 0;
            $result1 = mc_credit_fetch($memberuid);
            if (!empty($result1)) {
                $jifen1 = $result1['credit1'];
            }
            $result  = mc_credit_update($memberuid, 'credit1', $jifen);
            $result2 = mc_credit_fetch($memberuid);
            if (!empty($result2)) {
                $jifen2 = $result2['credit1'];
            }
            if ($result) {
                pdo_update('aki_yzmjf_code', array(
                    'status' => '2'
                ), array(
                    'id' => $cid
                ));
                pdo_query('update ' . tablename('aki_yzmjf_codenum') . ' set usedcount = usedcount + 1 where id = :id', array(
                    'id' => $piciid
                ));
                $data = array(
                    'uid' => $memberuid,
                    'uniacid' => $_W['uniacid'],
                    'codeid' => $cid,
                    'piciid' => $piciid,
                    'openid' => $openid,
                    'yzmjfid' => 0,
                    'jifen' => $jifen,
                    'status' => '1',
                    'time' => time()
                );
                pdo_insert('aki_yzmjf_sendlist', $data);
                $msg = "恭喜您获得" . $jifen . "积分，当前积分由" . $jifen1 . "变为" . $jifen2;
            } else {
                $msg = "积分兑换失败！请稍后再试！";
            }
        }
        return $this->respText($msg);
    }
}
