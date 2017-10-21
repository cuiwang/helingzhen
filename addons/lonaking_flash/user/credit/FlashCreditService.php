<?php
/*
 __________________________________________________
|  Encode by BajieTeam on we7                      |
|__________________________________________________|
*/

goto DnoMU;
DnoMU:
/**
 * Created by PhpStorm.
 * User: leon
 * Date: 15/11/16
 * Time: 下午12:57
 */
require_once dirname(__FILE__) . "\57\56\56\x2f\56\x2e\57\x46\x6c\141\163\150\x43\x6f\155\155\157\x6e\123\x65\x72\166\151\x63\x65\x2e\160\x68\x70";
goto LZm_q;
LZm_q:
require_once dirname(__FILE__) . "\x2f\x2e\x2e\x2f\56\56\x2f\106\x6c\141\x73\x68\125\163\x65\162\x53\x65\x72\166\151\143\145\56\160\x68\160";
goto Tp0L9;
Tp0L9:
class FlashCreditService extends FlashCommonService
{
    private $flashUserService;
    /**
     * CreditUtils constructor.
     */
    public function __construct()
    {
        goto xsV2f;
        ywQAM:
        $this->columns = "\x2a";
        goto KUeo6;
        xsV2f:
        $this->table_name = "\x6d\143\137\x63\162\145\x64\151\x74\163\x5f\162\145\x63\x6f\x72\x64";
        goto ywQAM;
        KUeo6:
        $this->plugin_name = "\x6c\x6f\x6e\141\x6b\151\156\147\x5f\x66\154\141\x73\150";
        goto hVljg;
        hVljg:
        $this->flashUserService = new FlashUserService();
        goto yagLP;
        yagLP:
    }
    public function fetchUserCreditRecordPage($openid, $pageIndex = '', $pageSize = '', $creditType = "\143\x72\x65\144\151\x74\61")
    {
        goto Cb9HG;
        CeRqM:
        $page = $this->selectPageOrderBy("\101\116\104\40\x75\151\144\x3d{$uid}\x20\x41\116\104\40\143\x72\x65\x64\x69\164\x74\x79\160\x65\x3d\x27{$creditType}\x27", "\143\x72\x65\141\x74\145\164\x69\x6d\x65\x20\104\x45\x53\x43\54", $pageIndex, $pageSize);
        goto g9lly;
        Cb9HG:
        $pageIndex = max(1, $pageIndex);
        goto YqiE0;
        g9lly:
        return $page;
        goto gCIPR;
        ketRI:
        $uid = $this->flashUserService->fetchUid($openid);
        goto CeRqM;
        YqiE0:
        $pageSize = $pageSize >= 10 ? $pageSize : 10;
        goto ketRI;
        gCIPR:
    }
}
