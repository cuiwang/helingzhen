<?php
class Yike_Common
{
    public function getAccount()
    {
        global $_W;
        load()->model('account');
        if (!empty($_W['acid'])) {
            return WeAccount::create($_W['acid']);
        } else {
            $acid = pdo_fetchcolumn("SELECT acid FROM " . tablename('account_wechats') . " WHERE `uniacid`=:uniacid LIMIT 1", array(
                ':uniacid' => $_W['uniacid']
            ));
            return WeAccount::create($acid);
        }
        return false;
    }

    public function createEweiShopNO($table, $field, $prefix)
    {
        $billno = date('YmdHis') . random(6, true);
        while (1) {
            $count = pdo_fetchcolumn('select count(*) from ' . tablename('ewei_shop_' . $table) . " where {$field}=:billno limit 1", array(
                ':billno' => $billno
            ));
            if ($count <= 0) {
                break;
            }
            $billno = date('YmdHis') . random(6, true);
        }
        return $prefix . $billno;
    }

    public function rechargeEweiShop($uid, $money) {
        global $_W;
        load()->model('mc');
        $result = mc_fansinfo($uid, $_W['acid'], $_W['uniacid']);
        $openid = $result['openid'];
        $logno = $this->createEweiShopNO("member_log", "logno", "RC");
        $data = array("openid" => $openid, "logno" => $logno, "uniacid" => $_W["uniacid"], "type" => '0', "createtime" => TIMESTAMP, "status" => "1", "title" => "会员返利", "money" => $money, "rechargetype" => "system",);
        pdo_insert("ewei_shop_member_log", $data);
        pdo_query('update '. tablename('ewei_shop_member') . ' set money = money + :money where uniacid = :uniacid and uid = :uid', array(':money'=>$money, ':uniacid'=>$_W['uniacid'], ':uid'=>$uid));
    }
}