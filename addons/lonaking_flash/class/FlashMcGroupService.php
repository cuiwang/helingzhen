<?php
/*
 __________________________________________________
|  Encode by BajieTeam on we7                      |
|__________________________________________________|
*/

/**
 * Created by PhpStorm.
 * User: leon
 * Date: 16/4/24
 * Time: 下午2:02
 */
require_once dirname(__FILE__) . "\x2f\x2e\x2e\x2f\x46\154\141\x73\x68\103\x6f\155\x6d\x6f\156\123\x65\x72\166\x69\x63\145\56\x70\x68\160";
class FlashMcGroupService extends FlashCommonService
{
    public function __construct()
    {
        goto WIAmE;
        WIAmE:
        $this->table_name = "\x6d\143\137\147\x72\x6f\x75\160\x73";
        goto zBvAa;
        zBvAa:
        $this->columns = "\52";
        goto gpUGl;
        gpUGl:
        $this->plugin_name = "\x6c\x6f\156\141\x6b\x69\156\x67\x5f\146\154\141\x73\150";
        goto C6Kgw;
        C6Kgw:
    }
    public function selectByIds($groupIds)
    {
        goto EJfew;
        N79Jq:
        throw new Exception("\346\237\xa5\350\257\xa2\xe5\217\202\xe6\225\xb0\xe5\xbc\x82\345\xb8\270", 404);
        goto oVYBQ;
        BxWn1:
        $idsStr = implode("\54", $groupIds);
        goto tdeok;
        kSmQY:
        $ids = array_unique($groupIds);
        goto BxWn1;
        oVYBQ:
        lnrhM:
        goto NP4zq;
        zVdQ5:
        DLQmX:
        goto kSmQY;
        tdeok:
        $in = "\50" . $idsStr . "\x29";
        goto mtk55;
        EJfew:
        if (is_array($groupIds)) {
            goto lnrhM;
        }
        goto N79Jq;
        NP4zq:
        if (!(sizeof($groupIds) <= 0)) {
            goto DLQmX;
        }
        goto zsRg_;
        mtk55:
        $data_list = pdo_fetchall("\x53\x45\114\105\103\124\40" . $this->columns . "\40\x46\x52\117\x4d\x20" . tablename($this->table_name) . "\40\x57\x48\x45\x52\x45\40\x67\x72\x6f\x75\160\x69\144\x20\x69\x6e\40{$in}");
        goto A_4s3;
        A_4s3:
        return $data_list;
        goto xvvM_;
        zsRg_:
        throw new Exception("\345\217\x82\346\x95\xb0\xe4\xb8\272\347\xa9\272", 404);
        goto zVdQ5;
        xvvM_:
    }
    public function getUserMcGroupByUid($uid)
    {
        goto Fwb2j;
        VzrxO:
        $groupID = $member["\147\x72\157\x75\160\151\144"];
        goto OPzhH;
        Fwb2j:
        $member = pdo_fetch("\163\x65\x6c\x65\x63\x74\x20\x2a\40\x66\x72\x6f\155\40" . tablename("\x6d\143\137\155\x65\155\142\x65\162\163") . "\x20\x77\x68\145\162\145\x20\165\151\144\75\x27{$uid}\x27");
        goto VzrxO;
        Z3c4l:
        return $data;
        goto WW66b;
        OPzhH:
        $data = pdo_fetch("\x53\x45\114\x45\x43\124\40" . $this->columns . "\x20\x46\122\x4f\115\40" . tablename($this->table_name) . "\40\x57\x48\105\x52\105\40\147\x72\x6f\x75\160\151\x64\75\47{$groupID}\47");
        goto Z3c4l;
        WW66b:
    }
}
