<?php
/*
 __________________________________________________
|  Encode by BajieTeam on we7                      |
|__________________________________________________|
*/

goto r3FzR;
qJxnn:
define("\120\103\x4c\132\111\x50\137\x45\122\122\137\102\101\104\137\x46\x4f\122\115\101\124", -10);
goto Q3JLb;
X3PPM:
define("\120\x43\114\x5a\111\120\x5f\x4f\x50\124\x5f\120\101\124\110", 77001);
goto zguS3;
loQDZ:
function PclZipUtilCopyBlock($p_src, $p_dest, $p_size, $p_mode = 0)
{
    goto PtJeO;
    HCV4a:
    m0RW5:
    goto f23Dn;
    mE2Im:
    $v_read_size = $p_size < PCLZIP_READ_BLOCK_SIZE ? $p_size : PCLZIP_READ_BLOCK_SIZE;
    goto NbotW;
    hm7ZV:
    S800B:
    goto f8ut_;
    rWSxr:
    if ($p_mode == 2) {
        goto XYgtO;
    }
    goto g4zL5;
    PgbUp:
    XYgtO:
    goto LKZXz;
    iVrq1:
    lSgD5:
    goto gRGYa;
    PtJeO:
    $v_result = 1;
    goto uTYT7;
    f23Dn:
    WdPZ5:
    goto UqGqY;
    bmMkS:
    @fwrite($p_dest, $v_buffer, $v_read_size);
    goto gZEnb;
    UqGqY:
    if (!($p_size != 0)) {
        goto LqPj7;
    }
    goto BWDNE;
    vzkPL:
    @fwrite($p_dest, $v_buffer, $v_read_size);
    goto OhFI2;
    bEkiv:
    $v_buffer = @gzread($p_src, $v_read_size);
    goto vzkPL;
    BWDNE:
    $v_read_size = $p_size < PCLZIP_READ_BLOCK_SIZE ? $p_size : PCLZIP_READ_BLOCK_SIZE;
    goto bEkiv;
    LKZXz:
    NZjxW:
    goto xaE9H;
    gRGYa:
    goto sr4IP;
    goto TnbTQ;
    OhFI2:
    $p_size -= $v_read_size;
    goto wWS4L;
    f8ut_:
    goto sr4IP;
    goto HCV4a;
    LqpAT:
    N6pvv:
    goto e3MvE;
    xNHUL:
    Sm3bP:
    goto FLmJT;
    NrfSM:
    goto QRSKx;
    goto LqpAT;
    m0Qp_:
    LqPj7:
    goto Kum2o;
    e3MvE:
    sr4IP:
    goto ZhnUV;
    xaE9H:
    if (!($p_size != 0)) {
        goto lSgD5;
    }
    goto QbEP5;
    gZEnb:
    $p_size -= $v_read_size;
    goto WJRVl;
    uTYT7:
    if ($p_mode == 0) {
        goto glml2;
    }
    goto silMM;
    wWS4L:
    goto WdPZ5;
    goto m0Qp_;
    Kum2o:
    goto sr4IP;
    goto PgbUp;
    TnbTQ:
    V0Ezg:
    goto JUlwp;
    ARnOo:
    $p_size -= $v_read_size;
    goto NrfSM;
    qOP8J:
    @gzwrite($p_dest, $v_buffer, $v_read_size);
    goto UMv7U;
    FLmJT:
    if (!($p_size != 0)) {
        goto S800B;
    }
    goto mE2Im;
    g4zL5:
    if ($p_mode == 3) {
        goto V0Ezg;
    }
    goto etiHe;
    BiuBD:
    glml2:
    goto xNHUL;
    UMv7U:
    $p_size -= $v_read_size;
    goto T7fmO;
    bPwFi:
    $v_buffer = @fread($p_src, $v_read_size);
    goto qOP8J;
    T7fmO:
    goto NZjxW;
    goto iVrq1;
    n5mae:
    if (!($p_size != 0)) {
        goto N6pvv;
    }
    goto L4v_h;
    ZhnUV:
    return $v_result;
    goto E_tQV;
    L4v_h:
    $v_read_size = $p_size < PCLZIP_READ_BLOCK_SIZE ? $p_size : PCLZIP_READ_BLOCK_SIZE;
    goto cfBDM;
    PcMoO:
    @gzwrite($p_dest, $v_buffer, $v_read_size);
    goto ARnOo;
    WJRVl:
    goto Sm3bP;
    goto hm7ZV;
    silMM:
    if ($p_mode == 1) {
        goto m0RW5;
    }
    goto rWSxr;
    QbEP5:
    $v_read_size = $p_size < PCLZIP_READ_BLOCK_SIZE ? $p_size : PCLZIP_READ_BLOCK_SIZE;
    goto bPwFi;
    etiHe:
    goto sr4IP;
    goto BiuBD;
    NbotW:
    $v_buffer = @fread($p_src, $v_read_size);
    goto bmMkS;
    JUlwp:
    QRSKx:
    goto n5mae;
    cfBDM:
    $v_buffer = @gzread($p_src, $v_read_size);
    goto PcMoO;
    E_tQV:
}
goto Il3Bz;
j6vVB:
define("\x50\103\114\x5a\111\x50\137\x45\x52\122\x5f\x41\x4c\122\105\101\x44\x59\x5f\x41\x5f\104\111\x52\x45\103\x54\117\122\131", -17);
goto vfTeS;
JeUoU:
define("\x50\103\x4c\132\x49\x50\x5f\x4f\x50\x54\137\122\x45\115\117\x56\105\137\x50\101\x54\x48", 77003);
goto OfFWR;
YSwNP:
define("\120\x43\x4c\x5a\111\x50\x5f\x45\x52\122\x5f\125\123\105\x52\x5f\101\102\117\122\x54\x45\x44", 2);
goto qPaG5;
EHXcT:
define("\x50\x43\114\x5a\x49\120\137\103\x42\x5f\120\117\x53\x54\x5f\101\x44\104", 78004);
goto etISF;
JmGFt:
define("\120\103\x4c\132\x49\120\137\x4f\x50\x54\x5f\x45\x58\x54\122\x41\x43\x54\137\x49\x4e\137\117\125\x54\x50\125\124", 77015);
goto HUyy6;
O9cpN:
define("\x50\x43\x4c\x5a\111\x50\137\101\124\x54\x5f\106\x49\114\x45\137\x4e\x41\x4d\105", 79001);
goto nsxA8;
r3FzR:
if (defined("\x50\x43\114\132\x49\120\137\x52\105\x41\104\x5f\102\x4c\x4f\103\113\137\x53\x49\x5a\105")) {
    goto ocESn;
}
goto Gkmlw;
Il0BN:
define("\x50\x43\x4c\x5a\111\120\137\117\120\124\x5f\x45\130\x54\122\101\x43\x54\x5f\x41\x53\137\123\124\122\111\x4e\x47", 77006);
goto dhypG;
AQJol:
Ns9gp:
goto H9k16;
QLi8U:
define("\120\103\x4c\x5a\111\120\x5f\x45\122\122\x5f\x55\x4e\x53\125\x50\120\117\x52\124\x45\x44\137\x45\x4e\x43\x52\x59\120\124\x49\117\116", -19);
goto Og4s4;
A2Ys_:
define("\x50\x43\x4c\x5a\x49\120\137\123\105\120\x41\122\x41\124\x4f\122", "\54");
goto GP2FB;
tV_wv:
define("\120\103\114\132\x49\x50\137\101\124\x54\x5f\x46\x49\x4c\105\x5f\115\124\x49\x4d\105", 79004);
goto hB122;
dBGiD:
define("\120\x43\x4c\x5a\111\120\x5f\x4f\120\124\137\105\x58\x54\x52\101\103\124\137\x44\x49\x52\137\122\105\123\124\x52\111\x43\x54\111\117\x4e", 77019);
goto nzhFG;
oOZGc:
define("\x50\103\114\x5a\111\120\x5f\x41\x54\x54\137\x46\111\114\x45\137\103\117\x4d\x4d\x45\x4e\124", 79006);
goto eAPdp;
MoWCw:
define("\x50\103\x4c\x5a\x49\x50\x5f\105\x52\x52\x5f\111\x4e\x56\x41\x4c\111\104\x5f\x50\x41\x52\101\x4d\x45\x54\x45\122", -3);
goto Vhb25;
SxduU:
define("\x50\103\x4c\132\x49\120\x5f\105\122\122\x5f\x44\111\x52\x45\103\x54\x4f\122\x59\x5f\x52\105\x53\x54\x52\x49\103\124\x49\x4f\x4e", -21);
goto X3PPM;
OTpH6:
define("\x50\103\114\132\x49\x50\137\101\124\124\x5f\x46\x49\114\105\x5f\x4e\x45\x57\x5f\106\x55\114\x4c\x5f\116\101\x4d\x45", 79003);
goto tV_wv;
bRTX0:
define("\x50\103\114\132\x49\120\137\x54\x45\x4d\x50\x4f\x52\x41\x52\131\137\x44\x49\122", '');
goto LBk56;
s6aW1:
define("\x50\103\114\132\x49\x50\x5f\103\102\x5f\x50\122\105\x5f\101\x44\x44", 78003);
goto EHXcT;
i9eoj:
define("\120\103\x4c\132\111\120\x5f\105\122\122\x5f\127\x52\x49\x54\x45\x5f\x4f\x50\105\116\x5f\106\101\111\x4c", -1);
goto TSsz5;
TzfrW:
define("\120\103\114\132\x49\x50\137\x4f\x50\124\x5f\124\105\115\120\x5f\x46\111\114\x45\x5f\117\x4e", 77021);
goto ESS8k;
LLK4O:
define("\120\103\114\x5a\111\x50\137\x45\x52\x52\137\122\105\116\101\115\105\137\106\111\x4c\x45\137\x46\101\x49\114", -12);
goto pU__N;
zJu3V:
define("\x50\103\114\132\111\x50\137\x45\x52\122\137\x49\x4e\126\x41\114\x49\x44\x5f\x5a\x49\120", -6);
goto Q73WL;
XEUNW:
if (defined("\120\103\x4c\x5a\111\120\137\105\x52\x52\117\122\137\105\130\x54\105\x52\116\101\114")) {
    goto Ns9gp;
}
goto AlR0m;
T0oIr:
define("\x50\103\x4c\x5a\x49\x50\x5f\103\x42\137\x50\x4f\x53\x54\x5f\x45\130\x54\x52\101\103\x54", 78002);
goto s6aW1;
faQT3:
define("\120\103\x4c\132\111\120\137\117\x50\x54\x5f\120\x52\x45\x50\105\x4e\x44\137\x43\x4f\115\115\x45\116\124", 77014);
goto JmGFt;
LBk56:
rjHO0:
goto yGwkR;
GoUxr:
define("\x50\103\x4c\x5a\111\x50\x5f\117\x50\x54\137\x41\x44\104\137\124\x45\x4d\120\137\x46\111\x4c\x45\137\117\x46\106", 77022);
goto O9cpN;
IlTHs:
define("\120\103\x4c\x5a\x49\120\x5f\x45\122\x52\x5f\111\x4e\x56\x41\114\111\x44\137\x4f\120\x54\x49\x4f\116\137\x56\x41\114\x55\x45", -16);
goto j6vVB;
OS0Ur:
define("\x50\103\x4c\x5a\111\x50\137\x4f\120\124\x5f\x53\x54\x4f\x50\137\x4f\116\x5f\105\x52\x52\x4f\x52", 77017);
goto dBGiD;
pU__N:
define("\120\x43\114\132\111\120\x5f\105\122\122\137\x42\x41\x44\137\x43\x48\x45\103\113\123\125\x4d", -13);
goto uvcnN;
hB122:
define("\120\103\114\x5a\111\120\x5f\101\124\x54\x5f\x46\x49\x4c\105\137\x43\117\x4e\124\105\x4e\124", 79005);
goto oOZGc;
Vhb25:
define("\120\103\x4c\132\111\120\137\x45\122\122\137\115\111\x53\x53\x49\116\107\137\106\x49\114\x45", -4);
goto aqag4;
GP2FB:
VHoR8:
goto XEUNW;
ESS8k:
define("\120\103\114\132\111\120\137\117\120\124\x5f\101\104\104\137\124\x45\115\120\137\x46\111\x4c\105\137\117\x4e", 77021);
goto dB1Ud;
Q3JLb:
define("\120\103\114\x5a\x49\x50\137\x45\122\122\x5f\x44\x45\114\x45\x54\105\137\106\x49\114\105\x5f\x46\101\111\114", -11);
goto LLK4O;
TSsz5:
define("\120\103\114\x5a\111\120\x5f\x45\122\x52\137\122\105\101\104\137\117\120\x45\116\x5f\x46\x41\111\x4c", -2);
goto MoWCw;
lMAi1:
define("\120\x43\x4c\132\x49\x50\x5f\x4f\120\x54\x5f\103\x4f\x4d\x4d\x45\x4e\124", 77012);
goto LgIey;
GKilr:
$g_pclzip_version = "\62\56\x38\x2e\62";
goto YSwNP;
etISF:
class PclZip
{
    public $zipname = '';
    public $zip_fd = 0;
    public $error_code = 1;
    public $error_string = '';
    public $magic_quotes_status;
    public function __construct($p_zipname)
    {
        goto it7IA;
        it7IA:
        if (function_exists("\x67\x7a\157\x70\145\156")) {
            goto w1q84;
        }
        goto XCuKP;
        a12Sc:
        w1q84:
        goto XmVM3;
        Hdnnu:
        $this->zip_fd = 0;
        goto nEyTi;
        nEyTi:
        $this->magic_quotes_status = -1;
        goto suspI;
        XmVM3:
        $this->zipname = $p_zipname;
        goto Hdnnu;
        suspI:
        return;
        goto Y218j;
        XCuKP:
        die("\x41\142\x6f\x72\x74\x20" . basename(__FILE__) . "\x20\72\40\x4d\x69\163\163\151\x6e\x67\40\x7a\x6c\151\x62\x20\x65\170\164\145\156\163\x69\x6f\156\x73");
        goto a12Sc;
        Y218j:
    }
    public function create($p_filelist)
    {
        goto ZIt_u;
        rF8Se:
        return 0;
        goto WgSZf;
        rCvJT:
        tu1co:
        goto wsYCw;
        nWkcw:
        goto tu1co;
        goto ql2Cn;
        roLgH:
        g7okg:
        goto Ylrw0;
        JNDdq:
        K8drs:
        goto C7XBP;
        KDdfe:
        $v_result = $this->privFileDescrExpand($v_filedescr_list, $v_options);
        goto oedb2;
        WOsLA:
        if ($v_size == 2) {
            goto g7okg;
        }
        goto kOJpg;
        zx_4Y:
        return 0;
        goto KOXDB;
        Q_nPi:
        $v_string_list = array();
        goto p3Bma;
        BDOoj:
        $v_string_list = $p_filelist;
        goto yX_Ux;
        dXrMn:
        if (is_string($p_filelist)) {
            goto PX4oI;
        }
        goto l_uN9;
        euPnw:
        muCmy:
        goto kpS0X;
        kOJpg:
        if ($v_size > 2) {
            goto kVij8;
        }
        goto eepE3;
        o3fyR:
        $v_string_list = explode(PCLZIP_SEPARATOR, $p_filelist);
        goto JNDdq;
        pdADX:
        sALWt:
        goto wQSmV;
        paGmK:
        if (!($v_result != 1)) {
            goto STZJG;
        }
        goto xU_9q;
        ZIt_u:
        $v_result = 1;
        goto zK1P5;
        iYe6Y:
        foreach ($v_string_list as $v_string) {
            goto IhXF5;
            TFS5R:
            k2QHM:
            goto J9jvr;
            qjvYx:
            goto k2QHM;
            goto H9Ee0;
            H9Ee0:
            CfJZj:
            goto wQysk;
            IhXF5:
            if ($v_string != '') {
                goto CfJZj;
            }
            goto qjvYx;
            J9jvr:
            DbgM_:
            goto XpCge;
            wQysk:
            $v_att_list[][PCLZIP_ATT_FILE_NAME] = $v_string;
            goto TFS5R;
            XpCge:
        }
        goto oal6F;
        kpS0X:
        if (isset($p_filelist[0]) && is_array($p_filelist[0])) {
            goto Akg7M;
        }
        goto BDOoj;
        uKkL0:
        $v_supported_attributes = array(PCLZIP_ATT_FILE_NAME => "\x6d\141\x6e\144\x61\x74\x6f\x72\171", PCLZIP_ATT_FILE_NEW_SHORT_NAME => "\157\160\164\x69\x6f\x6e\141\x6c", PCLZIP_ATT_FILE_NEW_FULL_NAME => "\x6f\160\164\151\x6f\156\x61\x6c", PCLZIP_ATT_FILE_MTIME => "\x6f\160\164\x69\157\156\141\x6c", PCLZIP_ATT_FILE_CONTENT => "\157\x70\x74\151\x6f\156\141\154", PCLZIP_ATT_FILE_COMMENT => "\157\x70\x74\151\157\x6e\x61\154");
        goto shCds;
        IsPI5:
        PX4oI:
        goto o3fyR;
        ZMGGW:
        $v_result = $this->privParseOptions($v_arg_list, $v_size, $v_options, array(PCLZIP_OPT_REMOVE_PATH => "\x6f\160\164\151\x6f\x6e\x61\x6c", PCLZIP_OPT_REMOVE_ALL_PATH => "\x6f\x70\x74\x69\x6f\156\141\154", PCLZIP_OPT_ADD_PATH => "\157\160\x74\151\157\x6e\x61\x6c", PCLZIP_CB_PRE_ADD => "\157\160\164\151\x6f\156\141\x6c", PCLZIP_CB_POST_ADD => "\x6f\160\x74\x69\157\156\141\154", PCLZIP_OPT_NO_COMPRESSION => "\157\x70\x74\x69\157\156\x61\154", PCLZIP_OPT_COMMENT => "\157\x70\164\151\157\x6e\141\154", PCLZIP_OPT_TEMP_FILE_THRESHOLD => "\157\x70\164\x69\x6f\156\141\x6c", PCLZIP_OPT_TEMP_FILE_ON => "\157\x70\164\151\157\156\141\x6c", PCLZIP_OPT_TEMP_FILE_OFF => "\x6f\160\164\151\157\x6e\x61\x6c"));
        goto Uk4lB;
        nsvjs:
        $v_size = func_num_args();
        goto vxlk7;
        C7XBP:
        if (!(sizeof($v_string_list) != 0)) {
            goto dBKaV;
        }
        goto iYe6Y;
        WgSZf:
        ymPtU:
        goto rCvJT;
        oedb2:
        if (!($v_result != 1)) {
            goto oNTW3;
        }
        goto EO60e;
        rnTWq:
        dBKaV:
        goto uKkL0;
        Ylrw0:
        $v_options[PCLZIP_OPT_REMOVE_PATH] = $v_arg_list[1];
        goto WvIYw;
        wQSmV:
        goto K8drs;
        goto IsPI5;
        WvIYw:
        goto q5U7N;
        goto Pekd8;
        EO60e:
        return 0;
        goto TwB72;
        mz3qU:
        return 0;
        goto cUZ32;
        wEFu5:
        $v_arg_list = func_get_args();
        goto uFgWm;
        l_uN9:
        PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "\111\x6e\166\141\x6c\151\144\40\166\x61\x72\151\x61\142\x6c\x65\40\164\171\x70\x65\x20\160\x5f\146\151\154\145\154\x69\x73\164");
        goto mz3qU;
        idf3a:
        $v_att_list = $p_filelist;
        goto pdADX;
        wsYCw:
        yRw2A:
        goto v1aVX;
        cUZ32:
        goto K8drs;
        goto euPnw;
        iGatY:
        $v_result = $this->privCreate($v_filedescr_list, $p_result_list, $v_options);
        goto paGmK;
        zK1P5:
        $this->privErrorReset();
        goto F1Zgn;
        eepE3:
        goto q5U7N;
        goto roLgH;
        O2J0W:
        Akg7M:
        goto idf3a;
        BDD8j:
        if (is_array($p_filelist)) {
            goto muCmy;
        }
        goto dXrMn;
        NbK3Q:
        $v_filedescr_list = array();
        goto r0Xs8;
        TwB72:
        oNTW3:
        goto iGatY;
        vxlk7:
        if (!($v_size > 1)) {
            goto yRw2A;
        }
        goto wEFu5;
        yX_Ux:
        goto sALWt;
        goto O2J0W;
        A30Uh:
        $v_options[PCLZIP_OPT_ADD_PATH] = $v_arg_list[0];
        goto WOsLA;
        wSsWT:
        if (is_integer($v_arg_list[0]) && $v_arg_list[0] > 77000) {
            goto orBze;
        }
        goto A30Uh;
        F1Zgn:
        $v_options = array();
        goto Zj2SH;
        q6v1C:
        $v_size--;
        goto wSsWT;
        p3Bma:
        $v_att_list = array();
        goto NbK3Q;
        oal6F:
        PWR_W:
        goto rnTWq;
        KOXDB:
        q5U7N:
        goto nWkcw;
        v1aVX:
        $this->privOptionDefaultThreshold($v_options);
        goto Q_nPi;
        xU_9q:
        return 0;
        goto W8poU;
        uFgWm:
        array_shift($v_arg_list);
        goto q6v1C;
        pmJ7P:
        return $p_result_list;
        goto CdtIQ;
        Zj2SH:
        $v_options[PCLZIP_OPT_NO_COMPRESSION] = false;
        goto nsvjs;
        dYqdY:
        vkyID:
        goto KDdfe;
        iRVsC:
        PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "\x49\156\166\141\x6c\151\x64\40\156\x75\155\x62\145\x72\40\x2f\x20\164\x79\x70\x65\40\157\146\40\x61\x72\x67\x75\x6d\x65\x6e\x74\x73");
        goto zx_4Y;
        ql2Cn:
        orBze:
        goto ZMGGW;
        r0Xs8:
        $p_result_list = array();
        goto BDD8j;
        shCds:
        foreach ($v_att_list as $v_entry) {
            goto AN3Dh;
            woiLS:
            return 0;
            goto VZAlF;
            AN3Dh:
            $v_result = $this->privFileDescrParseAtt($v_entry, $v_filedescr_list[], $v_options, $v_supported_attributes);
            goto ufgeu;
            xhGZg:
            h_waF:
            goto nKYwh;
            ufgeu:
            if (!($v_result != 1)) {
                goto eeja8;
            }
            goto woiLS;
            VZAlF:
            eeja8:
            goto xhGZg;
            nKYwh:
        }
        goto dYqdY;
        Uk4lB:
        if (!($v_result != 1)) {
            goto ymPtU;
        }
        goto rF8Se;
        W8poU:
        STZJG:
        goto pmJ7P;
        Pekd8:
        kVij8:
        goto iRVsC;
        CdtIQ:
    }
    public function add($p_filelist)
    {
        goto lXFh8;
        F4AS_:
        goto hK21H;
        goto YzD59;
        Mdf3Z:
        $v_result = $this->privAdd($v_filedescr_list, $p_result_list, $v_options);
        goto pnyLT;
        JP38Z:
        $v_string_list = explode(PCLZIP_SEPARATOR, $p_filelist);
        goto p07qa;
        Cgh5l:
        return 0;
        goto Kasup;
        SyevT:
        return 0;
        goto sFRre;
        Wdg8q:
        if (!(sizeof($v_string_list) != 0)) {
            goto DNW8E;
        }
        goto ZlQe7;
        YzD59:
        qV3vZ:
        goto qubIN;
        POOFo:
        if (is_array($p_filelist)) {
            goto g0mPi;
        }
        goto BIyeF;
        dtJuV:
        s1gKw:
        goto jBa3Y;
        lHwX4:
        PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "\x49\x6e\166\x61\x6c\151\144\40\166\x61\162\x69\x61\x62\x6c\x65\40\x74\x79\x70\x65\40\47" . gettype($p_filelist) . "\47\x20\146\157\x72\x20\x70\x5f\x66\x69\154\145\x6c\x69\x73\164");
        goto SyevT;
        nlReu:
        JuN05:
        goto rDzUl;
        j6Pb4:
        if (isset($p_filelist[0]) && is_array($p_filelist[0])) {
            goto JuN05;
        }
        goto KzSCS;
        gjwAl:
        if (is_integer($v_arg_list[0]) && $v_arg_list[0] > 77000) {
            goto S0efx;
        }
        goto hJPnu;
        rDzUl:
        $v_att_list = $p_filelist;
        goto ljOv2;
        nNRww:
        return 0;
        goto dtJuV;
        sWXtZ:
        if (!($v_size > 1)) {
            goto UClVx;
        }
        goto TTtzA;
        dUpJ1:
        UClVx:
        goto DuE2E;
        pJGX6:
        SeInn:
        goto cuh1k;
        cuh1k:
        iBf33:
        goto dUpJ1;
        ec6QA:
        WW2Xm:
        goto ZMcex;
        zJeU9:
        if (!($v_result != 1)) {
            goto SeInn;
        }
        goto QMFoU;
        BIyeF:
        if (is_string($p_filelist)) {
            goto eQkwF;
        }
        goto lHwX4;
        jBa3Y:
        return $p_result_list;
        goto dKw2S;
        u1mmL:
        $v_options[PCLZIP_OPT_NO_COMPRESSION] = false;
        goto YQubZ;
        ypKhL:
        goto iBf33;
        goto lzgAr;
        dzJo_:
        eQkwF:
        goto JP38Z;
        KzSCS:
        $v_string_list = $p_filelist;
        goto xf5T0;
        Owd1a:
        array_shift($v_arg_list);
        goto C0gLv;
        DuE2E:
        $this->privOptionDefaultThreshold($v_options);
        goto CCHjm;
        QMFoU:
        return 0;
        goto pJGX6;
        hJPnu:
        $v_options[PCLZIP_OPT_ADD_PATH] = $v_add_path = $v_arg_list[0];
        goto Xqpo1;
        TTtzA:
        $v_arg_list = func_get_args();
        goto Owd1a;
        BT2JJ:
        $v_att_list = array();
        goto hKU8V;
        xf5T0:
        goto taOjq;
        goto nlReu;
        A_tTn:
        goto UX_6i;
        goto dzJo_;
        iu1Ij:
        GPw6y:
        goto Mdf3Z;
        p07qa:
        UX_6i:
        goto Wdg8q;
        sFRre:
        goto UX_6i;
        goto YIzoe;
        ljOv2:
        taOjq:
        goto A_tTn;
        qKxaC:
        $v_result = $this->privParseOptions($v_arg_list, $v_size, $v_options, array(PCLZIP_OPT_REMOVE_PATH => "\157\x70\164\x69\x6f\156\x61\x6c", PCLZIP_OPT_REMOVE_ALL_PATH => "\157\x70\164\x69\157\156\x61\154", PCLZIP_OPT_ADD_PATH => "\157\160\164\x69\157\156\x61\x6c", PCLZIP_CB_PRE_ADD => "\157\160\x74\x69\157\156\141\x6c", PCLZIP_CB_POST_ADD => "\x6f\160\x74\x69\157\x6e\x61\154", PCLZIP_OPT_NO_COMPRESSION => "\x6f\x70\164\x69\x6f\x6e\141\154", PCLZIP_OPT_COMMENT => "\157\x70\164\151\157\156\x61\x6c", PCLZIP_OPT_ADD_COMMENT => "\157\x70\164\x69\x6f\x6e\x61\154", PCLZIP_OPT_PREPEND_COMMENT => "\x6f\x70\164\x69\x6f\156\141\154", PCLZIP_OPT_TEMP_FILE_THRESHOLD => "\x6f\160\x74\151\x6f\156\141\154", PCLZIP_OPT_TEMP_FILE_ON => "\x6f\x70\x74\x69\157\156\141\x6c", PCLZIP_OPT_TEMP_FILE_OFF => "\x6f\x70\x74\x69\x6f\x6e\x61\154"));
        goto zJeU9;
        ZlQe7:
        foreach ($v_string_list as $v_string) {
            $v_att_list[][PCLZIP_ATT_FILE_NAME] = $v_string;
            X0uKR:
        }
        goto KB3K8;
        VcVkT:
        DNW8E:
        goto A5eDX;
        YQubZ:
        $v_size = func_num_args();
        goto sWXtZ;
        ZMcex:
        $v_options[PCLZIP_OPT_REMOVE_PATH] = $v_arg_list[1];
        goto F4AS_;
        A5eDX:
        $v_supported_attributes = array(PCLZIP_ATT_FILE_NAME => "\155\141\156\144\141\x74\x6f\162\x79", PCLZIP_ATT_FILE_NEW_SHORT_NAME => "\157\160\x74\x69\x6f\156\141\x6c", PCLZIP_ATT_FILE_NEW_FULL_NAME => "\157\x70\x74\x69\157\x6e\x61\154", PCLZIP_ATT_FILE_MTIME => "\x6f\160\164\x69\x6f\x6e\141\154", PCLZIP_ATT_FILE_CONTENT => "\x6f\160\164\151\x6f\x6e\x61\154", PCLZIP_ATT_FILE_COMMENT => "\157\160\164\151\x6f\156\141\154");
        goto d2vs5;
        Kasup:
        hK21H:
        goto ypKhL;
        lXFh8:
        $v_result = 1;
        goto x20s6;
        hKU8V:
        $v_filedescr_list = array();
        goto qMYaJ;
        bkaK_:
        ouYkX:
        goto F3IBa;
        C0gLv:
        $v_size--;
        goto gjwAl;
        pnyLT:
        if (!($v_result != 1)) {
            goto s1gKw;
        }
        goto nNRww;
        C987I:
        $v_options = array();
        goto u1mmL;
        qubIN:
        PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "\x49\x6e\x76\x61\154\151\x64\40\x6e\165\x6d\x62\145\162\x20\x2f\x20\x74\x79\160\x65\x20\157\146\40\x61\162\147\165\x6d\145\x6e\164\163");
        goto Cgh5l;
        Xqpo1:
        if ($v_size == 2) {
            goto WW2Xm;
        }
        goto bhcD2;
        ZxhSc:
        return 0;
        goto iu1Ij;
        F3IBa:
        $v_result = $this->privFileDescrExpand($v_filedescr_list, $v_options);
        goto zSl1U;
        lzgAr:
        S0efx:
        goto qKxaC;
        qMYaJ:
        $p_result_list = array();
        goto POOFo;
        KB3K8:
        ZcniJ:
        goto VcVkT;
        YIzoe:
        g0mPi:
        goto j6Pb4;
        d2vs5:
        foreach ($v_att_list as $v_entry) {
            goto XPkjz;
            JqycQ:
            MZ9FG:
            goto FfSV2;
            FfSV2:
            ITcJS:
            goto Pczo7;
            XPkjz:
            $v_result = $this->privFileDescrParseAtt($v_entry, $v_filedescr_list[], $v_options, $v_supported_attributes);
            goto qN8At;
            RNHd0:
            return 0;
            goto JqycQ;
            qN8At:
            if (!($v_result != 1)) {
                goto MZ9FG;
            }
            goto RNHd0;
            Pczo7:
        }
        goto bkaK_;
        w3gn3:
        goto hK21H;
        goto ec6QA;
        bhcD2:
        if ($v_size > 2) {
            goto qV3vZ;
        }
        goto w3gn3;
        x20s6:
        $this->privErrorReset();
        goto C987I;
        zSl1U:
        if (!($v_result != 1)) {
            goto GPw6y;
        }
        goto ZxhSc;
        CCHjm:
        $v_string_list = array();
        goto BT2JJ;
        dKw2S:
    }
    public function listContent()
    {
        goto Cz9hC;
        Lo_DV:
        return 0;
        goto qeHGI;
        vlOAA:
        if ($this->privCheckFormat()) {
            goto JaCN4;
        }
        goto Lo_DV;
        Cz9hC:
        $v_result = 1;
        goto n2c3T;
        qeHGI:
        JaCN4:
        goto m4gNm;
        rD4fu:
        return 0;
        goto k2XrM;
        Bedzm:
        return $p_list;
        goto brHhV;
        n2c3T:
        $this->privErrorReset();
        goto vlOAA;
        vWIp3:
        if (!(($v_result = $this->privList($p_list)) != 1)) {
            goto uffkI;
        }
        goto Ve04x;
        k2XrM:
        uffkI:
        goto Bedzm;
        Ve04x:
        unset($p_list);
        goto rD4fu;
        m4gNm:
        $p_list = array();
        goto vWIp3;
        brHhV:
    }
    public function extract()
    {
        goto PrFkU;
        ecgrr:
        if (!isset($v_options[PCLZIP_OPT_ADD_PATH])) {
            goto f7nQQ;
        }
        goto RyBFZ;
        GVHGE:
        return 0;
        goto bqfMV;
        WoFsQ:
        $p_list = array();
        goto z5CCx;
        c0s17:
        goto dx58R;
        goto YYx80;
        OJ8iZ:
        J1Fbr:
        goto ecgrr;
        QQMlk:
        HuSQD:
        goto H7Flj;
        tPNJl:
        if (!($v_result != 1)) {
            goto UWvF3;
        }
        goto sRNCp;
        z77i2:
        $v_options[PCLZIP_OPT_EXTRACT_AS_STRING] = false;
        goto iVrj8;
        wUe1L:
        prHV1:
        goto uDdiS;
        SrAIC:
        $v_arg_list = func_get_args();
        goto o37VY;
        TLMIr:
        PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "\x49\x6e\166\141\x6c\x69\144\x20\156\x75\155\142\x65\162\40\57\40\164\171\160\x65\x20\157\146\x20\x61\x72\147\165\x6d\x65\156\164\163");
        goto GVHGE;
        YqF0l:
        $v_remove_all_path = $v_options[PCLZIP_OPT_REMOVE_ALL_PATH];
        goto OJ8iZ;
        iVrj8:
        if (!($v_size > 0)) {
            goto r9uMZ;
        }
        goto SrAIC;
        DSCIv:
        BGRlZ:
        goto dz_GD;
        bqfMV:
        dx58R:
        goto txDmG;
        b_voq:
        WY_8E:
        goto nq0DB;
        sYj7D:
        if (!isset($v_options[PCLZIP_OPT_REMOVE_PATH])) {
            goto RC45z;
        }
        goto W3ya2;
        QqMNJ:
        $v_size = func_num_args();
        goto z77i2;
        zgB0u:
        $v_path = $v_arg_list[0];
        goto HMZEw;
        dqLfW:
        AMwAE:
        goto sYj7D;
        dz_GD:
        $v_remove_path = $v_arg_list[1];
        goto c0s17;
        z5CCx:
        $v_result = $this->privExtractByRule($p_list, $v_path, $v_remove_path, $v_remove_all_path, $v_options);
        goto ssiUH;
        HMZEw:
        if ($v_size == 2) {
            goto BGRlZ;
        }
        goto M37MN;
        nq0DB:
        return $p_list;
        goto GanBb;
        H7Flj:
        $v_options = array();
        goto KjooG;
        SfACn:
        VHZ5x:
        goto wylpm;
        hDmeM:
        f7nQQ:
        goto HSkpu;
        OEPXu:
        $this->privErrorReset();
        goto IGo2p;
        szwBv:
        unset($p_list);
        goto YtI0j;
        txDmG:
        goto ZCCnX;
        goto SfACn;
        oxoPu:
        if (!isset($v_options[PCLZIP_OPT_REMOVE_ALL_PATH])) {
            goto J1Fbr;
        }
        goto YqF0l;
        tjeRB:
        $v_path = $v_options[PCLZIP_OPT_PATH];
        goto dqLfW;
        ssiUH:
        if (!($v_result < 1)) {
            goto WY_8E;
        }
        goto szwBv;
        YtI0j:
        return 0;
        goto b_voq;
        KjooG:
        $v_path = '';
        goto nDQF0;
        YYx80:
        DgjWP:
        goto TLMIr;
        Hp0wp:
        r9uMZ:
        goto hmH2h;
        RyBFZ:
        if (!(strlen($v_path) > 0 && substr($v_path, -1) != "\57")) {
            goto prHV1;
        }
        goto gdUQQ;
        nDQF0:
        $v_remove_path = '';
        goto qHzS5;
        wylpm:
        $v_result = $this->privParseOptions($v_arg_list, $v_size, $v_options, array(PCLZIP_OPT_PATH => "\157\160\164\151\x6f\156\141\154", PCLZIP_OPT_REMOVE_PATH => "\157\x70\164\x69\x6f\x6e\x61\x6c", PCLZIP_OPT_REMOVE_ALL_PATH => "\x6f\x70\164\x69\157\156\x61\154", PCLZIP_OPT_ADD_PATH => "\157\160\x74\151\157\x6e\x61\154", PCLZIP_CB_PRE_EXTRACT => "\x6f\160\164\151\157\156\141\x6c", PCLZIP_CB_POST_EXTRACT => "\157\160\x74\x69\157\156\141\x6c", PCLZIP_OPT_SET_CHMOD => "\157\160\164\x69\157\156\141\154", PCLZIP_OPT_BY_NAME => "\157\160\164\x69\157\156\141\x6c", PCLZIP_OPT_BY_EREG => "\157\x70\164\151\x6f\156\141\x6c", PCLZIP_OPT_BY_PREG => "\x6f\160\164\151\x6f\156\141\154", PCLZIP_OPT_BY_INDEX => "\157\160\164\x69\x6f\156\x61\154", PCLZIP_OPT_EXTRACT_AS_STRING => "\x6f\x70\164\151\157\156\x61\154", PCLZIP_OPT_EXTRACT_IN_OUTPUT => "\157\x70\x74\151\x6f\156\x61\x6c", PCLZIP_OPT_REPLACE_NEWER => "\157\x70\x74\x69\157\156\x61\154", PCLZIP_OPT_STOP_ON_ERROR => "\157\x70\164\151\157\x6e\x61\154", PCLZIP_OPT_EXTRACT_DIR_RESTRICTION => "\x6f\160\x74\151\x6f\156\x61\x6c", PCLZIP_OPT_TEMP_FILE_THRESHOLD => "\157\160\x74\151\x6f\156\141\x6c", PCLZIP_OPT_TEMP_FILE_ON => "\x6f\160\x74\x69\x6f\x6e\141\x6c", PCLZIP_OPT_TEMP_FILE_OFF => "\157\160\164\151\157\x6e\x61\154"));
        goto tPNJl;
        W3ya2:
        $v_remove_path = $v_options[PCLZIP_OPT_REMOVE_PATH];
        goto D8vqs;
        uDdiS:
        $v_path .= $v_options[PCLZIP_OPT_ADD_PATH];
        goto hDmeM;
        o37VY:
        if (is_integer($v_arg_list[0]) && $v_arg_list[0] > 77000) {
            goto VHZ5x;
        }
        goto zgB0u;
        Ol7GP:
        return 0;
        goto QQMlk;
        HSkpu:
        ZCCnX:
        goto Hp0wp;
        D8vqs:
        RC45z:
        goto oxoPu;
        IGo2p:
        if ($this->privCheckFormat()) {
            goto HuSQD;
        }
        goto Ol7GP;
        M37MN:
        if ($v_size > 2) {
            goto DgjWP;
        }
        goto nrRlU;
        scs5k:
        UWvF3:
        goto fsqZY;
        hmH2h:
        $this->privOptionDefaultThreshold($v_options);
        goto WoFsQ;
        nrRlU:
        goto dx58R;
        goto DSCIv;
        sRNCp:
        return 0;
        goto scs5k;
        fsqZY:
        if (!isset($v_options[PCLZIP_OPT_PATH])) {
            goto AMwAE;
        }
        goto tjeRB;
        PrFkU:
        $v_result = 1;
        goto OEPXu;
        gdUQQ:
        $v_path .= "\x2f";
        goto wUe1L;
        qHzS5:
        $v_remove_all_path = false;
        goto QqMNJ;
        GanBb:
    }
    public function extractByIndex($p_index)
    {
        goto LqOFz;
        FiVk5:
        $v_size = func_num_args();
        goto ns4Xh;
        yPlFs:
        if (!($v_result != 1)) {
            goto M3ns0;
        }
        goto av_P3;
        U73LG:
        $this->privErrorReset();
        goto tLbX2;
        kYuHg:
        array_shift($v_arg_list);
        goto D01kT;
        WAwOx:
        AzhYI:
        goto Yvgup;
        bJotb:
        EgtU1:
        goto w32U7;
        abkWq:
        return $p_list;
        goto FVLn5;
        mOec8:
        $v_options_trick = array();
        goto SWK13;
        tBrW1:
        $v_remove_path = $v_arg_list[1];
        goto aLc8J;
        jpc5x:
        M3ns0:
        goto TAgpR;
        xv6lg:
        q8WxM:
        goto qzU_k;
        ggr8N:
        $v_remove_all_path = false;
        goto FiVk5;
        zL07w:
        $v_options[PCLZIP_OPT_EXTRACT_AS_STRING] = false;
        goto NK6qn;
        av_P3:
        return 0;
        goto jpc5x;
        P5Ajf:
        if (!isset($v_options[PCLZIP_OPT_EXTRACT_AS_STRING])) {
            goto nf_VN;
        }
        goto SYSYU;
        oL3DB:
        y_jvy:
        goto s2AaP;
        u2SXb:
        $v_path = $v_options[PCLZIP_OPT_PATH];
        goto O_24X;
        faqz4:
        if (!($v_result != 1)) {
            goto EgtU1;
        }
        goto ea01Q;
        QjcY1:
        bpv4A:
        goto pcAjP;
        pnbaU:
        if (!isset($v_options[PCLZIP_OPT_REMOVE_PATH])) {
            goto q8WxM;
        }
        goto bg6Xk;
        Qn0im:
        nf_VN:
        goto zL07w;
        qzU_k:
        if (!isset($v_options[PCLZIP_OPT_REMOVE_ALL_PATH])) {
            goto Fn4mD;
        }
        goto bJRAg;
        yH7mE:
        reAfp:
        goto tBrW1;
        gLNvI:
        goto y_jvy;
        goto QjcY1;
        gbExp:
        if (!isset($v_options[PCLZIP_OPT_ADD_PATH])) {
            goto she4B;
        }
        goto XKVZ8;
        Yvgup:
        $v_options = array();
        goto uH0Ya;
        X0fev:
        NmJYY:
        goto abkWq;
        wa28f:
        $v_remove_path = '';
        goto ggr8N;
        SYSYU:
        goto Ef2Yx;
        goto Qn0im;
        lXqai:
        return 0;
        goto X0fev;
        ns4Xh:
        $v_options[PCLZIP_OPT_EXTRACT_AS_STRING] = false;
        goto lgJRI;
        aLc8J:
        goto HqdID;
        goto HwT7q;
        XKVZ8:
        if (!(strlen($v_path) > 0 && substr($v_path, -1) != "\57")) {
            goto EFp52;
        }
        goto lSl3T;
        ea01Q:
        return 0;
        goto bJotb;
        lSl3T:
        $v_path .= "\57";
        goto YLxBe;
        BFfzn:
        if (is_integer($v_arg_list[0]) && $v_arg_list[0] > 77000) {
            goto bpv4A;
        }
        goto WrhfT;
        gSPp1:
        goto HqdID;
        goto yH7mE;
        w32U7:
        if (!isset($v_options[PCLZIP_OPT_PATH])) {
            goto CqDOr;
        }
        goto u2SXb;
        chTbN:
        return 0;
        goto WAwOx;
        lgJRI:
        if (!($v_size > 1)) {
            goto F1_Ao;
        }
        goto TzEuE;
        TAgpR:
        $v_options[PCLZIP_OPT_BY_INDEX] = $v_options_trick[PCLZIP_OPT_BY_INDEX];
        goto sp5mr;
        vPnjn:
        $v_arg_trick = array(PCLZIP_OPT_BY_INDEX, $p_index);
        goto mOec8;
        QiTdn:
        $v_path .= $v_options[PCLZIP_OPT_ADD_PATH];
        goto BuG4r;
        tLbX2:
        if ($this->privCheckFormat()) {
            goto AzhYI;
        }
        goto chTbN;
        UlmOF:
        if (!(($v_result = $this->privExtractByRule($p_list, $v_path, $v_remove_path, $v_remove_all_path, $v_options)) < 1)) {
            goto NmJYY;
        }
        goto lXqai;
        hyCL0:
        if ($v_size > 2) {
            goto z7bP8;
        }
        goto gSPp1;
        pcAjP:
        $v_result = $this->privParseOptions($v_arg_list, $v_size, $v_options, array(PCLZIP_OPT_PATH => "\x6f\x70\164\151\x6f\x6e\141\154", PCLZIP_OPT_REMOVE_PATH => "\x6f\x70\164\151\157\x6e\141\154", PCLZIP_OPT_REMOVE_ALL_PATH => "\157\160\164\151\x6f\156\141\x6c", PCLZIP_OPT_EXTRACT_AS_STRING => "\x6f\x70\x74\x69\x6f\x6e\141\154", PCLZIP_OPT_ADD_PATH => "\157\160\164\x69\157\x6e\141\x6c", PCLZIP_CB_PRE_EXTRACT => "\157\x70\164\x69\x6f\x6e\141\154", PCLZIP_CB_POST_EXTRACT => "\x6f\x70\164\151\x6f\x6e\141\x6c", PCLZIP_OPT_SET_CHMOD => "\157\x70\x74\x69\x6f\x6e\141\154", PCLZIP_OPT_REPLACE_NEWER => "\x6f\x70\x74\x69\x6f\156\x61\x6c", PCLZIP_OPT_STOP_ON_ERROR => "\157\160\164\151\x6f\156\x61\x6c", PCLZIP_OPT_EXTRACT_DIR_RESTRICTION => "\x6f\x70\x74\x69\x6f\x6e\x61\154", PCLZIP_OPT_TEMP_FILE_THRESHOLD => "\157\x70\x74\x69\x6f\156\141\x6c", PCLZIP_OPT_TEMP_FILE_ON => "\x6f\x70\x74\151\x6f\156\141\154", PCLZIP_OPT_TEMP_FILE_OFF => "\157\160\x74\x69\x6f\x6e\141\154"));
        goto faqz4;
        BuG4r:
        she4B:
        goto P5Ajf;
        sp5mr:
        $this->privOptionDefaultThreshold($v_options);
        goto UlmOF;
        bJRAg:
        $v_remove_all_path = $v_options[PCLZIP_OPT_REMOVE_ALL_PATH];
        goto xeEuT;
        uH0Ya:
        $v_path = '';
        goto wa28f;
        SWK13:
        $v_result = $this->privParseOptions($v_arg_trick, sizeof($v_arg_trick), $v_options_trick, array(PCLZIP_OPT_BY_INDEX => "\x6f\x70\x74\151\x6f\156\x61\154"));
        goto yPlFs;
        WrhfT:
        $v_path = $v_arg_list[0];
        goto K1fUA;
        O_24X:
        CqDOr:
        goto pnbaU;
        qILEK:
        PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "\x49\x6e\x76\x61\x6c\x69\x64\40\156\165\x6d\x62\x65\162\40\57\40\x74\x79\160\x65\x20\x6f\146\x20\141\x72\x67\165\155\145\x6e\164\x73");
        goto JXENS;
        NK6qn:
        Ef2Yx:
        goto oL3DB;
        K1fUA:
        if ($v_size == 2) {
            goto reAfp;
        }
        goto hyCL0;
        LqOFz:
        $v_result = 1;
        goto U73LG;
        s2AaP:
        F1_Ao:
        goto vPnjn;
        TzEuE:
        $v_arg_list = func_get_args();
        goto kYuHg;
        YLxBe:
        EFp52:
        goto QiTdn;
        U1u7q:
        HqdID:
        goto gLNvI;
        D01kT:
        $v_size--;
        goto BFfzn;
        xeEuT:
        Fn4mD:
        goto gbExp;
        JXENS:
        return 0;
        goto U1u7q;
        bg6Xk:
        $v_remove_path = $v_options[PCLZIP_OPT_REMOVE_PATH];
        goto xv6lg;
        HwT7q:
        z7bP8:
        goto qILEK;
        FVLn5:
    }
    public function delete()
    {
        goto X4AuT;
        EUaWn:
        $this->privDisableMagicQuotes();
        goto OnO0K;
        v5ULz:
        if (!($v_result != 1)) {
            goto Srfpc;
        }
        goto bCv3q;
        OnO0K:
        $v_list = array();
        goto O3awC;
        Q0mqH:
        return 0;
        goto kDMGT;
        NXFxf:
        $this->privSwapBackMagicQuotes();
        goto gQKeq;
        Q80Zl:
        return 0;
        goto TIELZ;
        LZld5:
        if (!($v_size > 0)) {
            goto GDZTk;
        }
        goto IIE7Z;
        X4AuT:
        $v_result = 1;
        goto VIaSh;
        AesXG:
        $this->privSwapBackMagicQuotes();
        goto Ke8in;
        VIaSh:
        $this->privErrorReset();
        goto s1RXt;
        s1RXt:
        if ($this->privCheckFormat()) {
            goto HqwM7;
        }
        goto Q0mqH;
        pYdYe:
        GDZTk:
        goto EUaWn;
        kDMGT:
        HqwM7:
        goto gJrCr;
        O3awC:
        if (!(($v_result = $this->privDeleteByRule($v_list, $v_options)) != 1)) {
            goto G1Xwf;
        }
        goto NXFxf;
        TIELZ:
        G1Xwf:
        goto AesXG;
        Ke8in:
        return $v_list;
        goto fzO85;
        gQKeq:
        unset($v_list);
        goto Q80Zl;
        IIE7Z:
        $v_arg_list = func_get_args();
        goto wn2p3;
        GMUHh:
        Srfpc:
        goto pYdYe;
        qqyKa:
        $v_size = func_num_args();
        goto LZld5;
        gJrCr:
        $v_options = array();
        goto qqyKa;
        bCv3q:
        return 0;
        goto GMUHh;
        wn2p3:
        $v_result = $this->privParseOptions($v_arg_list, $v_size, $v_options, array(PCLZIP_OPT_BY_NAME => "\x6f\x70\164\151\x6f\x6e\x61\154", PCLZIP_OPT_BY_EREG => "\x6f\160\x74\x69\x6f\156\141\x6c", PCLZIP_OPT_BY_PREG => "\x6f\x70\164\151\x6f\x6e\x61\154", PCLZIP_OPT_BY_INDEX => "\x6f\x70\164\151\157\x6e\x61\154"));
        goto v5ULz;
        fzO85:
    }
    public function deleteByIndex($p_index)
    {
        $p_list = $this->delete(PCLZIP_OPT_BY_INDEX, $p_index);
        return $p_list;
    }
    public function properties()
    {
        goto blYue;
        c8KLt:
        $this->privSwapBackMagicQuotes();
        goto c4akF;
        ZT9ic:
        $v_prop["\163\x74\x61\164\x75\163"] = "\x6e\x6f\x74\x5f\145\x78\151\163\164";
        goto pANNb;
        ocEH1:
        if (!(($this->zip_fd = @fopen($this->zipname, "\162\x62")) == 0)) {
            goto j6Huu;
        }
        goto PH14r;
        XOjyx:
        PclZip::privErrorLog(PCLZIP_ERR_READ_OPEN_FAIL, "\x55\x6e\141\x62\x6c\x65\40\164\x6f\x20\157\x70\x65\x6e\x20\x61\x72\x63\x68\151\166\x65\40\x27" . $this->zipname . "\x27\x20\151\156\40\142\151\x6e\141\x72\171\x20\162\x65\x61\x64\x20\155\x6f\144\x65");
        goto isIDT;
        Q1SaE:
        $this->privSwapBackMagicQuotes();
        goto KXoo1;
        KXoo1:
        return 0;
        goto ipiqt;
        isIDT:
        return 0;
        goto KH1pJ;
        ONY9k:
        jbNXH:
        goto qLhIz;
        ipiqt:
        dRL3j:
        goto yLzMw;
        xeXwm:
        $v_prop["\156\x62"] = $v_central_dir["\x65\156\x74\x72\151\x65\163"];
        goto q7ERW;
        c4akF:
        return 0;
        goto ONY9k;
        fMMFq:
        $this->privSwapBackMagicQuotes();
        goto NZgq8;
        FM3rP:
        $v_prop["\143\157\x6d\x6d\145\156\x74"] = $v_central_dir["\x63\157\x6d\155\145\156\164"];
        goto xeXwm;
        EMf_J:
        if ($this->privCheckFormat()) {
            goto dRL3j;
        }
        goto Q1SaE;
        pANNb:
        if (!@is_file($this->zipname)) {
            goto TDxjG;
        }
        goto ocEH1;
        blYue:
        $this->privErrorReset();
        goto BechC;
        TjheF:
        $v_prop["\143\x6f\155\155\145\156\x74"] = '';
        goto AcMxi;
        BechC:
        $this->privDisableMagicQuotes();
        goto EMf_J;
        NZgq8:
        return $v_prop;
        goto iqqWT;
        cnSSI:
        $v_central_dir = array();
        goto iA8uz;
        q7ERW:
        $v_prop["\x73\164\141\x74\x75\x73"] = "\157\153";
        goto SIuzk;
        SIuzk:
        TDxjG:
        goto fMMFq;
        iA8uz:
        if (!(($v_result = $this->privReadEndCentralDir($v_central_dir)) != 1)) {
            goto jbNXH;
        }
        goto c8KLt;
        PH14r:
        $this->privSwapBackMagicQuotes();
        goto XOjyx;
        yLzMw:
        $v_prop = array();
        goto TjheF;
        AcMxi:
        $v_prop["\156\142"] = 0;
        goto ZT9ic;
        KH1pJ:
        j6Huu:
        goto cnSSI;
        qLhIz:
        $this->privCloseFd();
        goto FM3rP;
        iqqWT:
    }
    public function duplicate($p_archive)
    {
        goto obgsp;
        obgsp:
        $v_result = 1;
        goto i8jhV;
        qRbS7:
        QTrTp:
        goto t55Hz;
        DD7sl:
        goto OxdUz;
        goto qw6L9;
        t55Hz:
        OxdUz:
        goto SCpgK;
        w082g:
        $v_result = PCLZIP_ERR_INVALID_PARAMETER;
        goto DD7sl;
        i8jhV:
        $this->privErrorReset();
        goto A4cWd;
        qw6L9:
        XxWy0:
        goto QidMl;
        LTW2h:
        ytKwV:
        goto tfHuy;
        Uko5b:
        goto QTrTp;
        goto Trtyd;
        tfHuy:
        if (!is_file($p_archive)) {
            goto V3J3J;
        }
        goto x2MyM;
        cWNHF:
        if (is_string($p_archive)) {
            goto ytKwV;
        }
        goto GexiR;
        GexiR:
        PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "\111\156\166\x61\x6c\x69\144\x20\166\x61\x72\x69\141\x62\154\x65\x20\x74\171\x70\145\x20\160\137\141\162\x63\150\x69\166\x65\x5f\x74\x6f\137\x61\144\144");
        goto w082g;
        wYJgq:
        PclZip::privErrorLog(PCLZIP_ERR_MISSING_FILE, "\116\x6f\x20\146\x69\154\145\40\167\151\164\150\40\x66\151\154\x65\x6e\x61\x6d\x65\40\x27" . $p_archive . "\x27");
        goto gX6xZ;
        x2MyM:
        $v_result = $this->privDuplicate($p_archive);
        goto Uko5b;
        RvURy:
        goto OxdUz;
        goto LTW2h;
        SCpgK:
        return $v_result;
        goto e4iah;
        QidMl:
        $v_result = $this->privDuplicate($p_archive->zipname);
        goto RvURy;
        gX6xZ:
        $v_result = PCLZIP_ERR_MISSING_FILE;
        goto qRbS7;
        A4cWd:
        if (is_object($p_archive) && get_class($p_archive) == "\x70\x63\x6c\x7a\x69\160") {
            goto XxWy0;
        }
        goto cWNHF;
        Trtyd:
        V3J3J:
        goto wYJgq;
        e4iah:
    }
    public function merge($p_archive_to_add)
    {
        goto ojy3O;
        USl_8:
        dCIlm:
        goto XJk1s;
        XJk1s:
        return $v_result;
        goto ZGEK9;
        V2cPF:
        AQzaW:
        goto jmQM9;
        gOUVM:
        goto dCIlm;
        goto lY8Z3;
        HRPTd:
        sXHWG:
        goto q3HEH;
        tRu04:
        if ($this->privCheckFormat()) {
            goto AQzaW;
        }
        goto ZpNYl;
        ZpNYl:
        return 0;
        goto V2cPF;
        yQdip:
        $v_result = PCLZIP_ERR_INVALID_PARAMETER;
        goto adxV6;
        adxV6:
        goto dCIlm;
        goto HRPTd;
        q3HEH:
        $v_result = $this->privMerge($p_archive_to_add);
        goto gOUVM;
        tOlQ1:
        $this->privErrorReset();
        goto tRu04;
        dZEg2:
        $v_result = $this->privMerge($v_object_archive);
        goto USl_8;
        jmQM9:
        if (is_object($p_archive_to_add) && get_class($p_archive_to_add) == "\x70\143\154\172\x69\160") {
            goto sXHWG;
        }
        goto MnYpi;
        MnYpi:
        if (is_string($p_archive_to_add)) {
            goto iYNKK;
        }
        goto XN1J5;
        lY8Z3:
        iYNKK:
        goto JOfIN;
        XN1J5:
        PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "\x49\156\x76\x61\x6c\x69\x64\40\166\141\162\151\x61\x62\154\x65\40\164\x79\160\145\40\x70\137\141\162\143\x68\151\x76\x65\x5f\164\157\x5f\141\x64\144");
        goto yQdip;
        ojy3O:
        $v_result = 1;
        goto tOlQ1;
        JOfIN:
        $v_object_archive = new PclZip($p_archive_to_add);
        goto dZEg2;
        ZGEK9:
    }
    public function errorCode()
    {
        goto HK6p2;
        XUX66:
        N3g1P:
        goto CdeGF;
        WG4yk:
        return $this->error_code;
        goto ZOnNS;
        ZOnNS:
        goto N3g1P;
        goto oMTkj;
        oMTkj:
        SalRE:
        goto EwQD8;
        EwQD8:
        return PclErrorCode();
        goto XUX66;
        HK6p2:
        if (PCLZIP_ERROR_EXTERNAL == 1) {
            goto SalRE;
        }
        goto WG4yk;
        CdeGF:
    }
    public function errorName($p_with_code = false)
    {
        goto GwXYP;
        Vykks:
        $v_value = "\116\157\116\141\155\x65";
        goto G08U5;
        o8YKR:
        $v_value = $v_name[$this->error_code];
        goto mV5Uv;
        YrlhP:
        RPb93:
        goto vQ22R;
        G08U5:
        goto Q6BOr;
        goto jjgF8;
        GwXYP:
        $v_name = array(PCLZIP_ERR_NO_ERROR => "\120\x43\x4c\x5a\x49\120\x5f\105\x52\122\137\x4e\117\137\105\x52\x52\x4f\x52", PCLZIP_ERR_WRITE_OPEN_FAIL => "\x50\x43\x4c\132\x49\x50\137\x45\x52\122\x5f\x57\x52\111\x54\x45\137\x4f\120\105\x4e\137\x46\x41\111\x4c", PCLZIP_ERR_READ_OPEN_FAIL => "\120\103\x4c\132\x49\x50\x5f\105\122\x52\137\122\x45\x41\x44\137\x4f\120\x45\116\137\106\101\x49\114", PCLZIP_ERR_INVALID_PARAMETER => "\x50\103\114\132\x49\x50\x5f\105\122\122\137\x49\x4e\126\101\x4c\x49\104\x5f\120\101\x52\x41\x4d\105\x54\105\x52", PCLZIP_ERR_MISSING_FILE => "\x50\x43\x4c\132\111\x50\137\105\x52\122\x5f\115\x49\x53\123\111\116\x47\x5f\106\111\114\105", PCLZIP_ERR_FILENAME_TOO_LONG => "\x50\103\114\132\x49\x50\x5f\x45\122\x52\x5f\x46\x49\114\105\x4e\x41\x4d\x45\x5f\x54\x4f\117\137\114\x4f\x4e\107", PCLZIP_ERR_INVALID_ZIP => "\120\x43\x4c\x5a\x49\120\137\x45\122\122\137\x49\x4e\x56\x41\x4c\x49\x44\137\x5a\x49\120", PCLZIP_ERR_BAD_EXTRACTED_FILE => "\x50\103\114\x5a\x49\120\x5f\105\x52\122\137\x42\x41\104\137\105\x58\124\x52\101\103\x54\x45\x44\137\x46\111\x4c\105", PCLZIP_ERR_DIR_CREATE_FAIL => "\120\103\x4c\132\111\x50\x5f\105\x52\x52\x5f\x44\x49\122\137\103\x52\105\101\124\105\137\x46\101\111\x4c", PCLZIP_ERR_BAD_EXTENSION => "\120\x43\114\x5a\x49\120\137\105\x52\122\x5f\x42\101\104\x5f\x45\x58\124\105\116\x53\x49\x4f\116", PCLZIP_ERR_BAD_FORMAT => "\x50\x43\x4c\132\111\x50\137\105\122\x52\137\x42\101\x44\137\x46\x4f\x52\115\x41\x54", PCLZIP_ERR_DELETE_FILE_FAIL => "\120\103\114\132\x49\120\137\105\x52\122\137\x44\x45\114\x45\124\105\137\106\111\114\x45\x5f\106\101\x49\x4c", PCLZIP_ERR_RENAME_FILE_FAIL => "\x50\103\114\132\x49\120\137\x45\122\x52\x5f\x52\105\116\101\x4d\105\x5f\x46\x49\x4c\105\x5f\106\x41\111\x4c", PCLZIP_ERR_BAD_CHECKSUM => "\x50\103\114\x5a\x49\120\x5f\x45\122\x52\x5f\x42\101\x44\x5f\103\x48\105\103\x4b\123\x55\115", PCLZIP_ERR_INVALID_ARCHIVE_ZIP => "\x50\103\114\132\111\120\137\105\x52\122\137\111\116\x56\101\x4c\111\x44\x5f\x41\122\x43\110\111\126\x45\137\132\x49\120", PCLZIP_ERR_MISSING_OPTION_VALUE => "\120\x43\x4c\132\x49\x50\x5f\105\122\122\137\x4d\111\123\123\x49\x4e\x47\x5f\117\x50\x54\111\x4f\x4e\137\x56\101\x4c\x55\105", PCLZIP_ERR_INVALID_OPTION_VALUE => "\x50\103\114\132\x49\x50\x5f\105\122\122\x5f\x49\x4e\x56\x41\114\x49\x44\x5f\117\x50\x54\111\x4f\x4e\x5f\x56\101\x4c\125\x45", PCLZIP_ERR_UNSUPPORTED_COMPRESSION => "\120\x43\114\132\x49\x50\x5f\105\x52\122\137\x55\116\123\x55\x50\120\117\122\124\105\x44\137\103\117\115\x50\x52\x45\123\x53\x49\117\x4e", PCLZIP_ERR_UNSUPPORTED_ENCRYPTION => "\x50\x43\x4c\x5a\111\120\x5f\105\122\x52\x5f\x55\116\x53\x55\120\120\x4f\122\124\105\x44\x5f\105\x4e\103\122\x59\x50\124\x49\117\116", PCLZIP_ERR_INVALID_ATTRIBUTE_VALUE => "\x50\x43\114\x5a\111\x50\x5f\x45\x52\122\137\111\x4e\x56\x41\x4c\x49\x44\x5f\101\x54\124\x52\x49\102\125\x54\105\137\x56\101\114\x55\105", PCLZIP_ERR_DIRECTORY_RESTRICTION => "\x50\103\114\132\x49\x50\x5f\x45\122\122\137\x44\x49\122\105\x43\124\x4f\122\131\x5f\122\105\x53\x54\x52\x49\x43\124\111\x4f\116");
        goto KJLRR;
        jFTYE:
        if ($p_with_code) {
            goto eBlCQ;
        }
        goto Aiq7y;
        mV5Uv:
        Q6BOr:
        goto jFTYE;
        Aiq7y:
        return $v_value;
        goto QFus4;
        Zog9k:
        eBlCQ:
        goto dIdDA;
        dIdDA:
        return $v_value . "\40\50" . $this->error_code . "\51";
        goto YrlhP;
        jjgF8:
        jknLP:
        goto o8YKR;
        KJLRR:
        if (isset($v_name[$this->error_code])) {
            goto jknLP;
        }
        goto Vykks;
        QFus4:
        goto RPb93;
        goto Zog9k;
        vQ22R:
    }
    public function errorInfo($p_full = false)
    {
        goto CxEqz;
        CRjZr:
        return PclErrorString();
        goto KLL_Z;
        Lpzqi:
        s7hnZ:
        goto GMlkz;
        cCXAp:
        if ($p_full) {
            goto s7hnZ;
        }
        goto rX4f0;
        GMlkz:
        return $this->errorName(true) . "\x20\72\40" . $this->error_string;
        goto DIuwa;
        KLL_Z:
        dxAD_:
        goto itq89;
        zr9or:
        goto dxAD_;
        goto zrf2N;
        CxEqz:
        if (PCLZIP_ERROR_EXTERNAL == 1) {
            goto KTRr1;
        }
        goto cCXAp;
        rX4f0:
        return $this->error_string . "\40\x5b\x63\157\144\145\40" . $this->error_code . "\135";
        goto f_WWa;
        f_WWa:
        goto GElsj;
        goto Lpzqi;
        DIuwa:
        GElsj:
        goto zr9or;
        zrf2N:
        KTRr1:
        goto CRjZr;
        itq89:
    }
    public function privCheckFormat($p_level = 0)
    {
        goto XB3hK;
        Wpqi_:
        return false;
        goto YZCbo;
        XB3hK:
        $v_result = true;
        goto sfKA_;
        YZCbo:
        yM500:
        goto vmznn;
        cW03q:
        if (is_file($this->zipname)) {
            goto yM500;
        }
        goto H3gzY;
        pyhy9:
        l5n68:
        goto fzZYI;
        fzZYI:
        return $v_result;
        goto tPKkH;
        u1jYU:
        return false;
        goto pyhy9;
        sfKA_:
        clearstatcache();
        goto wld3Y;
        H3gzY:
        PclZip::privErrorLog(PCLZIP_ERR_MISSING_FILE, "\115\151\163\163\x69\x6e\147\x20\x61\162\143\150\151\x76\x65\x20\146\x69\x6c\145\x20\x27" . $this->zipname . "\47");
        goto Wpqi_;
        kcRjW:
        PclZip::privErrorLog(PCLZIP_ERR_READ_OPEN_FAIL, "\125\x6e\141\x62\154\x65\x20\x74\x6f\40\162\145\141\x64\40\x61\x72\143\150\151\166\x65\x20\x27" . $this->zipname . "\47");
        goto u1jYU;
        wld3Y:
        $this->privErrorReset();
        goto cW03q;
        vmznn:
        if (is_readable($this->zipname)) {
            goto l5n68;
        }
        goto kcRjW;
        tPKkH:
    }
    public function privParseOptions(&$p_options_list, $p_size, &$v_result_list, $v_requested_options = false)
    {
        goto gbjJI;
        BdIRK:
        Jjdhh:
        goto A6tu6;
        z7SqJ:
        if (!($v_requested_options !== false)) {
            goto W0j7S;
        }
        goto EMU_r;
        DNc0Y:
        glorj:
        goto i_tB1;
        ozwx3:
        switch ($p_options_list[$i]) {
            case PCLZIP_OPT_PATH:
            case PCLZIP_OPT_REMOVE_PATH:
            case PCLZIP_OPT_ADD_PATH:
                goto H3pYZ;
                wWpAC:
                $i++;
                goto g20v_;
                o584u:
                EJRjs:
                goto ZSPcN;
                H3pYZ:
                if (!($i + 1 >= $p_size)) {
                    goto EJRjs;
                }
                goto AcwVP;
                itT4j:
                return PclZip::errorCode();
                goto o584u;
                AcwVP:
                PclZip::privErrorLog(PCLZIP_ERR_MISSING_OPTION_VALUE, "\x4d\151\163\x73\x69\x6e\x67\40\x70\141\162\141\x6d\x65\x74\x65\x72\x20\166\x61\x6c\x75\145\x20\x66\157\162\40\157\160\x74\x69\x6f\x6e\40\x27" . PclZipUtilOptionText($p_options_list[$i]) . "\x27");
                goto itT4j;
                ZSPcN:
                $v_result_list[$p_options_list[$i]] = PclZipUtilTranslateWinPath($p_options_list[$i + 1], false);
                goto wWpAC;
                g20v_:
                goto G1efg;
                goto h1ZDM;
                h1ZDM:
            case PCLZIP_OPT_TEMP_FILE_THRESHOLD:
                goto EoY5G;
                EoY5G:
                if (!($i + 1 >= $p_size)) {
                    goto M3G8P;
                }
                goto kPSJe;
                kPSJe:
                PclZip::privErrorLog(PCLZIP_ERR_MISSING_OPTION_VALUE, "\x4d\151\163\x73\151\156\x67\40\160\x61\x72\141\x6d\145\164\145\x72\x20\x76\x61\154\x75\x65\x20\x66\x6f\162\x20\x6f\160\164\x69\x6f\156\x20\x27" . PclZipUtilOptionText($p_options_list[$i]) . "\x27");
                goto FodS4;
                EdPFJ:
                wTBKw:
                goto w0_yW;
                FodS4:
                return PclZip::errorCode();
                goto Vg3NC;
                fQwxP:
                if (!(!is_integer($v_value) || $v_value < 0)) {
                    goto XUDX5;
                }
                goto Rq4Nj;
                IcEPB:
                if (!isset($v_result_list[PCLZIP_OPT_TEMP_FILE_OFF])) {
                    goto wTBKw;
                }
                goto f3FjD;
                Vg3NC:
                M3G8P:
                goto IcEPB;
                Rq4Nj:
                PclZip::privErrorLog(PCLZIP_ERR_INVALID_OPTION_VALUE, "\111\156\x74\x65\147\145\162\40\x65\x78\160\145\x63\164\x65\144\40\146\x6f\162\40\157\160\x74\151\157\x6e\x20\x27" . PclZipUtilOptionText($p_options_list[$i]) . "\47");
                goto iz2Ip;
                UsTRE:
                $v_result_list[$p_options_list[$i]] = $v_value * 1048576;
                goto Jti3S;
                iz2Ip:
                return PclZip::errorCode();
                goto mFle5;
                XmWqf:
                goto G1efg;
                goto QUZmX;
                Jti3S:
                $i++;
                goto XmWqf;
                mFle5:
                XUDX5:
                goto UsTRE;
                X1GxF:
                return PclZip::errorCode();
                goto EdPFJ;
                f3FjD:
                PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "\117\x70\x74\151\x6f\156\40\x27" . PclZipUtilOptionText($p_options_list[$i]) . "\47\x20\143\x61\156\x20\156\157\164\x20\142\145\40\x75\163\145\144\x20\x77\151\x74\x68\40\157\x70\164\151\x6f\156\x20\x27\120\x43\x4c\132\111\120\137\x4f\120\x54\137\124\x45\x4d\120\137\106\x49\114\x45\x5f\117\106\x46\x27");
                goto X1GxF;
                w0_yW:
                $v_value = $p_options_list[$i + 1];
                goto fQwxP;
                QUZmX:
            case PCLZIP_OPT_TEMP_FILE_ON:
                goto aPA8b;
                KWADM:
                $v_result_list[$p_options_list[$i]] = true;
                goto L50bG;
                qCp9p:
                PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "\x4f\x70\164\151\x6f\156\x20\47" . PclZipUtilOptionText($p_options_list[$i]) . "\47\x20\143\x61\x6e\40\156\x6f\x74\40\x62\145\40\165\x73\x65\144\x20\167\151\x74\150\x20\x6f\160\x74\x69\157\x6e\40\x27\x50\x43\x4c\x5a\111\120\137\117\x50\x54\137\x54\x45\115\x50\x5f\x46\x49\x4c\x45\x5f\x4f\x46\x46\47");
                goto vDsCb;
                aPA8b:
                if (!isset($v_result_list[PCLZIP_OPT_TEMP_FILE_OFF])) {
                    goto ove6f;
                }
                goto qCp9p;
                L50bG:
                goto G1efg;
                goto iBEEY;
                vDsCb:
                return PclZip::errorCode();
                goto CujeA;
                CujeA:
                ove6f:
                goto KWADM;
                iBEEY:
            case PCLZIP_OPT_TEMP_FILE_OFF:
                goto cBbC3;
                CDCJb:
                goto G1efg;
                goto hHE7j;
                cBbC3:
                if (!isset($v_result_list[PCLZIP_OPT_TEMP_FILE_ON])) {
                    goto AuMFT;
                }
                goto Z0wAf;
                vZZ9i:
                m9DEM:
                goto C31ru;
                HjtGQ:
                return PclZip::errorCode();
                goto X3le1;
                Z0wAf:
                PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "\x4f\160\x74\151\157\x6e\x20\x27" . PclZipUtilOptionText($p_options_list[$i]) . "\x27\x20\143\141\156\40\x6e\x6f\164\40\142\x65\x20\165\x73\x65\144\40\x77\x69\x74\x68\x20\x6f\x70\x74\x69\x6f\x6e\x20\47\120\x43\114\x5a\x49\120\137\x4f\x50\x54\x5f\124\105\x4d\x50\x5f\106\111\x4c\105\x5f\117\116\x27");
                goto HjtGQ;
                X3le1:
                AuMFT:
                goto VrwC8;
                cihtL:
                PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "\117\x70\164\x69\157\x6e\40\47" . PclZipUtilOptionText($p_options_list[$i]) . "\47\x20\x63\x61\x6e\40\156\157\x74\x20\x62\145\x20\165\x73\145\x64\40\x77\x69\164\x68\x20\x6f\160\164\x69\157\156\40\x27\x50\x43\x4c\x5a\x49\x50\x5f\117\x50\124\x5f\x54\105\115\x50\x5f\106\111\114\x45\137\x54\x48\x52\x45\x53\110\x4f\x4c\x44\x27");
                goto C92gm;
                VrwC8:
                if (!isset($v_result_list[PCLZIP_OPT_TEMP_FILE_THRESHOLD])) {
                    goto m9DEM;
                }
                goto cihtL;
                C31ru:
                $v_result_list[$p_options_list[$i]] = true;
                goto CDCJb;
                C92gm:
                return PclZip::errorCode();
                goto vZZ9i;
                hHE7j:
            case PCLZIP_OPT_EXTRACT_DIR_RESTRICTION:
                goto EO94W;
                c2eG5:
                so549:
                goto dVptC;
                dVptC:
                $v_result_list[$p_options_list[$i]] = PclZipUtilTranslateWinPath($p_options_list[$i + 1], false);
                goto oP38W;
                zKNrY:
                dSlga:
                goto tVanN;
                mJ934:
                if (is_string($p_options_list[$i + 1]) && $p_options_list[$i + 1] != '') {
                    goto so549;
                }
                goto jGZjl;
                y0gf1:
                PclZip::privErrorLog(PCLZIP_ERR_MISSING_OPTION_VALUE, "\x4d\151\163\163\x69\x6e\147\40\160\x61\162\x61\155\145\164\x65\162\40\166\x61\x6c\x75\145\40\x66\157\162\x20\157\160\x74\x69\157\x6e\x20\x27" . PclZipUtilOptionText($p_options_list[$i]) . "\47");
                goto J5hOa;
                EO94W:
                if (!($i + 1 >= $p_size)) {
                    goto GZzGe;
                }
                goto y0gf1;
                tVanN:
                goto G1efg;
                goto jtgiQ;
                xnx8Z:
                GZzGe:
                goto mJ934;
                jGZjl:
                goto dSlga;
                goto c2eG5;
                oP38W:
                $i++;
                goto zKNrY;
                J5hOa:
                return PclZip::errorCode();
                goto xnx8Z;
                jtgiQ:
            case PCLZIP_OPT_BY_NAME:
                goto gm03n;
                xTbfR:
                PclZip::privErrorLog(PCLZIP_ERR_MISSING_OPTION_VALUE, "\115\151\163\163\x69\x6e\147\40\160\x61\x72\141\155\145\164\145\x72\40\166\x61\x6c\165\x65\40\x66\x6f\x72\x20\157\x70\164\151\x6f\156\40\x27" . PclZipUtilOptionText($p_options_list[$i]) . "\47");
                goto qxQ6_;
                aJ2fu:
                goto Ao3oT;
                goto XKc4x;
                dAyCI:
                if (is_string($p_options_list[$i + 1])) {
                    goto nXf5N;
                }
                goto JatJW;
                A49KP:
                nXf5N:
                goto c6Q9E;
                NTpo6:
                PclZip::privErrorLog(PCLZIP_ERR_INVALID_OPTION_VALUE, "\x57\162\157\156\x67\x20\160\141\162\141\x6d\x65\x74\145\162\x20\166\x61\154\x75\145\x20\146\x6f\162\40\x6f\x70\164\151\x6f\x6e\40\x27" . PclZipUtilOptionText($p_options_list[$i]) . "\47");
                goto iqAf7;
                D0qig:
                goto G1efg;
                goto cDRC6;
                ET0uh:
                $v_result_list[$p_options_list[$i]] = $p_options_list[$i + 1];
                goto rszCT;
                gm03n:
                if (!($i + 1 >= $p_size)) {
                    goto PcA3P;
                }
                goto xTbfR;
                rszCT:
                Ao3oT:
                goto J5Jrw;
                XKc4x:
                JP0ma:
                goto ET0uh;
                JatJW:
                if (is_array($p_options_list[$i + 1])) {
                    goto JP0ma;
                }
                goto NTpo6;
                qxQ6_:
                return PclZip::errorCode();
                goto cxyR5;
                J5Jrw:
                $i++;
                goto D0qig;
                Q_8ME:
                goto Ao3oT;
                goto A49KP;
                iqAf7:
                return PclZip::errorCode();
                goto Q_8ME;
                c6Q9E:
                $v_result_list[$p_options_list[$i]][0] = $p_options_list[$i + 1];
                goto aJ2fu;
                cxyR5:
                PcA3P:
                goto dAyCI;
                cDRC6:
            case PCLZIP_OPT_BY_EREG:
                $p_options_list[$i] = PCLZIP_OPT_BY_PREG;
            case PCLZIP_OPT_BY_PREG:
                goto AD_SQ;
                iBhJ7:
                PclZip::privErrorLog(PCLZIP_ERR_INVALID_OPTION_VALUE, "\127\162\157\156\147\40\160\x61\x72\x61\x6d\145\164\145\162\x20\x76\141\x6c\x75\145\x20\x66\157\x72\x20\x6f\160\164\x69\157\x6e\40\47" . PclZipUtilOptionText($p_options_list[$i]) . "\47");
                goto p9eYi;
                kqNaa:
                goto h5YL4;
                goto sqh0v;
                sqh0v:
                r244A:
                goto VeSS3;
                XdD0F:
                PclZip::privErrorLog(PCLZIP_ERR_MISSING_OPTION_VALUE, "\115\x69\163\163\151\x6e\147\x20\x70\141\162\x61\155\x65\164\145\162\40\166\x61\x6c\165\145\40\x66\x6f\x72\40\157\x70\164\x69\x6f\x6e\x20\x27" . PclZipUtilOptionText($p_options_list[$i]) . "\x27");
                goto rZ1Ds;
                PE1VM:
                Lxklx:
                goto pzuiN;
                IBf6U:
                h5YL4:
                goto lYZvO;
                VeSS3:
                $v_result_list[$p_options_list[$i]] = $p_options_list[$i + 1];
                goto IBf6U;
                RWoLl:
                goto G1efg;
                goto QN8zw;
                lYZvO:
                $i++;
                goto RWoLl;
                rZ1Ds:
                return PclZip::errorCode();
                goto PE1VM;
                pzuiN:
                if (is_string($p_options_list[$i + 1])) {
                    goto r244A;
                }
                goto iBhJ7;
                p9eYi:
                return PclZip::errorCode();
                goto kqNaa;
                AD_SQ:
                if (!($i + 1 >= $p_size)) {
                    goto Lxklx;
                }
                goto XdD0F;
                QN8zw:
            case PCLZIP_OPT_COMMENT:
            case PCLZIP_OPT_ADD_COMMENT:
            case PCLZIP_OPT_PREPEND_COMMENT:
                goto dN5sa;
                jtAz0:
                PclZip::privErrorLog(PCLZIP_ERR_MISSING_OPTION_VALUE, "\115\151\x73\x73\151\156\x67\40\160\141\162\x61\x6d\x65\164\x65\162\x20\166\x61\x6c\x75\x65\x20\146\157\162\x20\157\x70\164\151\157\x6e\x20\47" . PclZipUtilOptionText($p_options_list[$i]) . "\x27");
                goto X9jM9;
                g2ol0:
                return PclZip::errorCode();
                goto Dx4WF;
                meVMB:
                PclZip::privErrorLog(PCLZIP_ERR_INVALID_OPTION_VALUE, "\127\162\x6f\156\x67\x20\160\141\x72\x61\155\x65\x74\145\162\40\x76\141\154\165\145\x20\146\x6f\162\40\157\160\x74\151\x6f\x6e\40\x27" . PclZipUtilOptionText($p_options_list[$i]) . "\x27");
                goto g2ol0;
                EjR9d:
                $v_result_list[$p_options_list[$i]] = $p_options_list[$i + 1];
                goto E5aS6;
                DS37m:
                AGZa5:
                goto Tz9jl;
                E5aS6:
                a0P3u:
                goto aswd1;
                X9jM9:
                return PclZip::errorCode();
                goto DS37m;
                dN5sa:
                if (!($i + 1 >= $p_size)) {
                    goto AGZa5;
                }
                goto jtAz0;
                aswd1:
                $i++;
                goto u1ohj;
                achpg:
                Q5BOF:
                goto EjR9d;
                Tz9jl:
                if (is_string($p_options_list[$i + 1])) {
                    goto Q5BOF;
                }
                goto meVMB;
                u1ohj:
                goto G1efg;
                goto BMsRo;
                Dx4WF:
                goto a0P3u;
                goto achpg;
                BMsRo:
            case PCLZIP_OPT_BY_INDEX:
                goto TJDAG;
                VIR8n:
                $i++;
                goto ShFeq;
                dfIHg:
                if (!($v_result_list[$p_options_list[$i]][$j]["\163\x74\x61\x72\164"] < $v_sort_value)) {
                    goto EvTx1;
                }
                goto HScU6;
                duEii:
                TiwPY:
                goto IMAY0;
                ShFeq:
                goto G1efg;
                goto mlOUk;
                cIqI2:
                goto ZrnA4;
                goto kDjqK;
                zF4tH:
                dwiGa:
                goto wHM80;
                EHgVg:
                PclZip::privErrorLog(PCLZIP_ERR_INVALID_OPTION_VALUE, "\x54\x6f\157\x20\x6d\x61\x6e\171\40\166\141\x6c\165\x65\163\40\151\x6e\x20\x69\156\x64\145\170\x20\162\x61\156\147\145\40\x66\157\x72\x20\157\x70\164\x69\x6f\x6e\40\47" . PclZipUtilOptionText($p_options_list[$i]) . "\47");
                goto n0ai6;
                u8CBk:
                if (!($j < sizeof($v_work_list))) {
                    goto RfumS;
                }
                goto eKaAo;
                Kc6Ws:
                goto U0PKz;
                goto hmgSI;
                B5iaK:
                EvTx1:
                goto PzVa_;
                LAsUU:
                $v_work_list = explode("\x2c", $p_options_list[$i + 1]);
                goto cIqI2;
                vWaFp:
                $v_work_list[0] = $p_options_list[$i + 1] . "\55" . $p_options_list[$i + 1];
                goto ez1xM;
                TJDAG:
                if (!($i + 1 >= $p_size)) {
                    goto Kr3O2;
                }
                goto WJB1F;
                PzVa_:
                $v_sort_value = $v_result_list[$p_options_list[$i]][$j]["\x73\164\x61\x72\x74"];
                goto NO3vO;
                QaIG7:
                $j = 0;
                goto EmuAP;
                kDjqK:
                aBWGA:
                goto vWaFp;
                MVq7R:
                $v_result_list[$p_options_list[$i]][$j]["\145\156\144"] = $v_item_list[0];
                goto Kc6Ws;
                HScU6:
                $v_sort_flag = true;
                goto RCK_t;
                u5HEQ:
                return PclZip::errorCode();
                goto c9fKo;
                NO3vO:
                g2tKI:
                goto ZOnRg;
                IMAY0:
                $p_options_list[$i + 1] = strtr($p_options_list[$i + 1], "\x20", '');
                goto LAsUU;
                imlY8:
                if (is_integer($p_options_list[$i + 1])) {
                    goto aBWGA;
                }
                goto vxhyY;
                WzEHk:
                if (is_string($p_options_list[$i + 1])) {
                    goto TiwPY;
                }
                goto imlY8;
                ZOnRg:
                $j++;
                goto W2p1a;
                rrafm:
                KDXHq:
                goto VIR8n;
                RCK_t:
                PclZip::privErrorLog(PCLZIP_ERR_INVALID_OPTION_VALUE, "\x49\x6e\x76\x61\154\x69\144\40\x6f\162\144\145\x72\x20\157\x66\40\151\x6e\144\145\x78\40\x72\x61\156\x67\x65\x20\146\157\x72\40\157\160\x74\x69\x6f\x6e\40\47" . PclZipUtilOptionText($p_options_list[$i]) . "\x27");
                goto QXFDI;
                QXFDI:
                return PclZip::errorCode();
                goto B5iaK;
                D7LaY:
                if ($v_size_item_list == 2) {
                    goto s7DxM;
                }
                goto EHgVg;
                G16KX:
                RfumS:
                goto hOGNI;
                sEIuz:
                $v_result_list[$p_options_list[$i]][$j]["\x73\164\141\x72\x74"] = $v_item_list[0];
                goto MVq7R;
                hmgSI:
                s7DxM:
                goto cKM3i;
                cKM3i:
                $v_result_list[$p_options_list[$i]][$j]["\163\x74\x61\x72\x74"] = $v_item_list[0];
                goto wfThB;
                c9fKo:
                goto ZrnA4;
                goto duEii;
                L7MT6:
                ZrnA4:
                goto IZh4C;
                WJB1F:
                PclZip::privErrorLog(PCLZIP_ERR_MISSING_OPTION_VALUE, "\115\x69\x73\163\151\156\x67\x20\x70\141\x72\x61\x6d\145\164\x65\x72\x20\x76\x61\154\x75\x65\40\x66\157\x72\40\157\x70\x74\x69\157\x6e\x20\47" . PclZipUtilOptionText($p_options_list[$i]) . "\47");
                goto HATQl;
                EmuAP:
                UrYfp:
                goto u8CBk;
                HATQl:
                return PclZip::errorCode();
                goto lepTT;
                hOGNI:
                if (!$v_sort_flag) {
                    goto KDXHq;
                }
                goto rrafm;
                n0ai6:
                return PclZip::errorCode();
                goto Ci00K;
                Ci00K:
                goto U0PKz;
                goto J056c;
                eKaAo:
                $v_item_list = explode("\x2d", $v_work_list[$j]);
                goto IDNWJ;
                RJyY6:
                $v_work_list = array();
                goto WzEHk;
                HWSGG:
                if ($v_size_item_list == 1) {
                    goto L2C9B;
                }
                goto D7LaY;
                lepTT:
                Kr3O2:
                goto RJyY6;
                W2p1a:
                goto UrYfp;
                goto G16KX;
                wfThB:
                $v_result_list[$p_options_list[$i]][$j]["\x65\x6e\144"] = $v_item_list[1];
                goto MFg8A;
                IZh4C:
                $v_sort_flag = false;
                goto aIN3B;
                J056c:
                L2C9B:
                goto sEIuz;
                wHM80:
                $v_work_list = $p_options_list[$i + 1];
                goto L7MT6;
                br04d:
                PclZip::privErrorLog(PCLZIP_ERR_INVALID_OPTION_VALUE, "\x56\141\x6c\x75\x65\x20\155\165\x73\x74\40\142\145\40\x69\x6e\x74\x65\x67\x65\162\x2c\x20\x73\164\162\151\x6e\147\40\157\x72\x20\x61\162\162\x61\171\x20\146\x6f\162\40\x6f\x70\164\151\x6f\156\x20\x27" . PclZipUtilOptionText($p_options_list[$i]) . "\47");
                goto u5HEQ;
                ez1xM:
                goto ZrnA4;
                goto zF4tH;
                MFg8A:
                U0PKz:
                goto dfIHg;
                aIN3B:
                $v_sort_value = 0;
                goto QaIG7;
                vxhyY:
                if (is_array($p_options_list[$i + 1])) {
                    goto dwiGa;
                }
                goto br04d;
                IDNWJ:
                $v_size_item_list = sizeof($v_item_list);
                goto HWSGG;
                mlOUk:
            case PCLZIP_OPT_REMOVE_ALL_PATH:
            case PCLZIP_OPT_EXTRACT_AS_STRING:
            case PCLZIP_OPT_NO_COMPRESSION:
            case PCLZIP_OPT_EXTRACT_IN_OUTPUT:
            case PCLZIP_OPT_REPLACE_NEWER:
            case PCLZIP_OPT_STOP_ON_ERROR:
                $v_result_list[$p_options_list[$i]] = true;
                goto G1efg;
            case PCLZIP_OPT_SET_CHMOD:
                goto qFBNm;
                qFBNm:
                if (!($i + 1 >= $p_size)) {
                    goto AO8Gi;
                }
                goto SxLoW;
                J5Ukt:
                $i++;
                goto fE6FZ;
                bqohY:
                AO8Gi:
                goto mvwWX;
                SxLoW:
                PclZip::privErrorLog(PCLZIP_ERR_MISSING_OPTION_VALUE, "\115\x69\x73\x73\x69\156\147\40\x70\141\x72\141\155\x65\x74\145\162\40\x76\x61\x6c\165\x65\40\146\157\x72\x20\x6f\160\164\151\157\x6e\40\47" . PclZipUtilOptionText($p_options_list[$i]) . "\47");
                goto I_tPb;
                I_tPb:
                return PclZip::errorCode();
                goto bqohY;
                mvwWX:
                $v_result_list[$p_options_list[$i]] = $p_options_list[$i + 1];
                goto J5Ukt;
                fE6FZ:
                goto G1efg;
                goto q3h6j;
                q3h6j:
            case PCLZIP_CB_PRE_EXTRACT:
            case PCLZIP_CB_POST_EXTRACT:
            case PCLZIP_CB_PRE_ADD:
            case PCLZIP_CB_POST_ADD:
                goto gkjjW;
                OjMNE:
                lZUQS:
                goto LDnjy;
                ZtbYP:
                PclZip::privErrorLog(PCLZIP_ERR_INVALID_OPTION_VALUE, "\x46\165\156\x63\164\151\x6f\156\x20\x27" . $v_function_name . "\x28\51\47\40\x69\163\40\156\x6f\164\40\141\x6e\x20\x65\170\x69\x73\x74\x69\x6e\x67\x20\146\x75\156\143\x74\x69\x6f\156\x20\146\157\x72\40\157\160\164\x69\157\156\40\47" . PclZipUtilOptionText($p_options_list[$i]) . "\47");
                goto YxoT1;
                YxoT1:
                return PclZip::errorCode();
                goto WAic1;
                Jh2Ho:
                PclZip::privErrorLog(PCLZIP_ERR_MISSING_OPTION_VALUE, "\x4d\151\163\163\151\156\147\40\160\x61\x72\x61\155\145\x74\145\162\40\x76\x61\154\x75\145\40\146\x6f\162\x20\x6f\160\164\151\x6f\156\40\47" . PclZipUtilOptionText($p_options_list[$i]) . "\x27");
                goto JfRRB;
                gkjjW:
                if (!($i + 1 >= $p_size)) {
                    goto lZUQS;
                }
                goto Jh2Ho;
                jw8J7:
                $v_result_list[$p_options_list[$i]] = $v_function_name;
                goto QPVkP;
                LDnjy:
                $v_function_name = $p_options_list[$i + 1];
                goto eGrHJ;
                WAic1:
                Ztc92:
                goto jw8J7;
                ywrp1:
                goto G1efg;
                goto NNJwW;
                eGrHJ:
                if (function_exists($v_function_name)) {
                    goto Ztc92;
                }
                goto ZtbYP;
                QPVkP:
                $i++;
                goto ywrp1;
                JfRRB:
                return PclZip::errorCode();
                goto OjMNE;
                NNJwW:
            default:
                PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "\125\156\153\x6e\157\167\x6e\40\160\141\162\x61\x6d\145\x74\145\162\x20\47" . $p_options_list[$i] . "\x27");
                return PclZip::errorCode();
        }
        goto BdIRK;
        QGoLK:
        CY8y4:
        goto IkIKl;
        A6tu6:
        G1efg:
        goto duKIW;
        XP_r_:
        FS9sT:
        goto z7SqJ;
        WKNAB:
        return PclZip::errorCode();
        goto xTaUI;
        HIpCc:
        return PclZip::errorCode();
        goto SNESr;
        i_tB1:
        W0j7S:
        goto vhUb1;
        aD4Th:
        AiWBg:
        goto vhw6c;
        BW2EV:
        goto AiWBg;
        goto DNc0Y;
        xTaUI:
        xRNo_:
        goto c4CYn;
        z1Z1i:
        rtkxQ:
        goto m7DDo;
        K5mMD:
        goto rtkxQ;
        goto XP_r_;
        EMU_r:
        $key = reset($v_requested_options);
        goto aD4Th;
        gbjJI:
        $v_result = 1;
        goto uLHOp;
        tI0R3:
        if (isset($v_result_list[$key])) {
            goto xRNo_;
        }
        goto EsiRT;
        IkIKl:
        return $v_result;
        goto oBC22;
        PMKWW:
        PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "\111\x6e\166\x61\154\151\x64\40\157\160\164\151\x6f\156\141\154\x20\x70\x61\x72\x61\x6d\145\x74\x65\162\x20\47" . $p_options_list[$i] . "\47\40\x66\x6f\x72\40\x74\150\x69\x73\x20\155\145\164\150\157\x64");
        goto HIpCc;
        SNESr:
        KRhrD:
        goto ozwx3;
        mepDS:
        if (isset($v_requested_options[$p_options_list[$i]])) {
            goto KRhrD;
        }
        goto PMKWW;
        ow6Lu:
        $key = next($v_requested_options);
        goto BW2EV;
        duKIW:
        $i++;
        goto K5mMD;
        joq9q:
        if (!($v_requested_options[$key] == "\155\141\156\144\141\x74\x6f\x72\x79")) {
            goto f9aR1;
        }
        goto tI0R3;
        EsiRT:
        PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "\x4d\151\x73\x73\x69\x6e\x67\40\155\x61\x6e\x64\141\164\x6f\162\171\40\160\141\x72\141\x6d\x65\164\x65\162\40" . PclZipUtilOptionText($key) . "\50" . $key . "\51");
        goto WKNAB;
        m7DDo:
        if (!($i < $p_size)) {
            goto FS9sT;
        }
        goto mepDS;
        vhw6c:
        if (!($key = key($v_requested_options))) {
            goto glorj;
        }
        goto joq9q;
        c4CYn:
        f9aR1:
        goto qx0az;
        vhUb1:
        if (isset($v_result_list[PCLZIP_OPT_TEMP_FILE_THRESHOLD])) {
            goto CY8y4;
        }
        goto QGoLK;
        qx0az:
        iFHHy:
        goto ow6Lu;
        uLHOp:
        $i = 0;
        goto z1Z1i;
        oBC22:
    }
    public function privOptionDefaultThreshold(&$p_options)
    {
        goto ZZins;
        UWrAr:
        $last = strtolower(substr($v_memory_limit, -1));
        goto b9VTE;
        g8A6c:
        $v_memory_limit = $v_memory_limit * 1048576;
        goto NMEBc;
        G1yd9:
        if (!($last == "\153")) {
            goto IlJzA;
        }
        goto sPgJj;
        ZZins:
        $v_result = 1;
        goto Ntzyz;
        ZhdCV:
        if (!($last == "\x6d")) {
            goto KeLYd;
        }
        goto g8A6c;
        NMEBc:
        KeLYd:
        goto G1yd9;
        XHH5o:
        return $v_result;
        goto iTCoX;
        iTCoX:
        c0INN:
        goto aZCEv;
        b9VTE:
        if (!($last == "\147")) {
            goto oFbdl;
        }
        goto L6C4Z;
        gSv8z:
        $p_options[PCLZIP_OPT_TEMP_FILE_THRESHOLD] = floor($v_memory_limit * PCLZIP_TEMPORARY_FILE_RATIO);
        goto Rr2KO;
        Ntzyz:
        if (!(isset($p_options[PCLZIP_OPT_TEMP_FILE_THRESHOLD]) || isset($p_options[PCLZIP_OPT_TEMP_FILE_OFF]))) {
            goto c0INN;
        }
        goto XHH5o;
        Rr2KO:
        if (!($p_options[PCLZIP_OPT_TEMP_FILE_THRESHOLD] < 1048576)) {
            goto xXoeR;
        }
        goto JfIri;
        L6C4Z:
        $v_memory_limit = $v_memory_limit * 1073741824;
        goto tePQ6;
        tePQ6:
        oFbdl:
        goto ZhdCV;
        sPgJj:
        $v_memory_limit = $v_memory_limit * 1024;
        goto gdDBt;
        v2GBb:
        return $v_result;
        goto MEfpI;
        gdDBt:
        IlJzA:
        goto gSv8z;
        ls6mE:
        $v_memory_limit = trim($v_memory_limit);
        goto UWrAr;
        JfIri:
        unset($p_options[PCLZIP_OPT_TEMP_FILE_THRESHOLD]);
        goto xVRFt;
        aZCEv:
        $v_memory_limit = ini_get("\155\x65\x6d\157\162\171\137\x6c\x69\x6d\x69\x74");
        goto ls6mE;
        xVRFt:
        xXoeR:
        goto v2GBb;
        MEfpI:
    }
    public function privFileDescrParseAtt(&$p_file_list, &$p_filedescr, $v_options, $v_requested_options = false)
    {
        goto XvVLC;
        K7jwJ:
        foreach ($p_file_list as $v_key => $v_value) {
            goto Fivrs;
            Vgll5:
            if (!($v_requested_options[$key] == "\155\141\156\144\141\164\x6f\162\x79")) {
                goto ayrVX;
            }
            goto S8SLW;
            NeiRW:
            KJ5ZF:
            goto IxTld;
            ptxAO:
            if (!($key = key($v_requested_options))) {
                goto lEppR;
            }
            goto Vgll5;
            YbFfH:
            if (!($v_requested_options !== false)) {
                goto dZzyn;
            }
            goto Vqc9C;
            r6Zr2:
            GMoWo:
            goto po09g;
            Fivrs:
            if (isset($v_requested_options[$v_key])) {
                goto KJ5ZF;
            }
            goto VyV_p;
            po09g:
            SMgb4:
            goto YbFfH;
            CcsNN:
            goto ysQk3;
            goto GvkJL;
            wSv7y:
            DHQA_:
            goto MachB;
            VyV_p:
            PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "\x49\156\166\x61\x6c\151\144\x20\x66\151\x6c\x65\x20\x61\164\x74\x72\x69\x62\165\164\x65\40\x27" . $v_key . "\x27\40\x66\157\x72\x20\x74\150\x69\163\40\x66\151\154\x65");
            goto NuyOV;
            utPlz:
            PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "\x4d\151\x73\x73\151\156\147\x20\155\141\156\x64\x61\x74\157\x72\171\x20\160\141\162\141\x6d\145\164\x65\x72\x20" . PclZipUtilOptionText($key) . "\x28" . $key . "\51");
            goto HQBJt;
            NTBd7:
            WGdZ9:
            goto d85Vi;
            yX0q9:
            ysQk3:
            goto ptxAO;
            GvkJL:
            lEppR:
            goto P5ng6;
            xXB42:
            $key = next($v_requested_options);
            goto CcsNN;
            Vqc9C:
            $key = reset($v_requested_options);
            goto yX0q9;
            IxTld:
            switch ($v_key) {
                case PCLZIP_ATT_FILE_NAME:
                    goto Dt8zq;
                    YNACH:
                    return PclZip::errorCode();
                    goto IIXeB;
                    y2OjC:
                    if (!($p_filedescr["\x66\151\154\x65\x6e\x61\x6d\145"] == '')) {
                        goto DATKe;
                    }
                    goto q5Bha;
                    MldZI:
                    PclZip::privErrorLog(PCLZIP_ERR_INVALID_ATTRIBUTE_VALUE, "\111\x6e\166\x61\x6c\151\144\40\164\171\160\x65\x20" . gettype($v_value) . "\x2e\x20\x53\x74\162\x69\x6e\147\40\x65\170\x70\145\x63\x74\145\144\x20\x66\157\162\40\x61\x74\164\x72\151\142\x75\x74\145\x20\x27" . PclZipUtilOptionText($v_key) . "\x27");
                    goto z502g;
                    BIzWv:
                    AxCRC:
                    goto nRujp;
                    Dt8zq:
                    if (is_string($v_value)) {
                        goto AxCRC;
                    }
                    goto MldZI;
                    IIXeB:
                    DATKe:
                    goto ijrzK;
                    q5Bha:
                    PclZip::privErrorLog(PCLZIP_ERR_INVALID_ATTRIBUTE_VALUE, "\x49\x6e\166\x61\154\x69\144\x20\145\155\160\164\x79\x20\146\151\154\x65\156\141\x6d\x65\40\x66\x6f\162\x20\x61\x74\x74\x72\x69\x62\165\x74\145\x20\47" . PclZipUtilOptionText($v_key) . "\47");
                    goto YNACH;
                    ijrzK:
                    goto SMgb4;
                    goto cp1oI;
                    nRujp:
                    $p_filedescr["\146\x69\x6c\145\x6e\141\155\x65"] = PclZipUtilPathReduction($v_value);
                    goto y2OjC;
                    z502g:
                    return PclZip::errorCode();
                    goto BIzWv;
                    cp1oI:
                case PCLZIP_ATT_FILE_NEW_SHORT_NAME:
                    goto P0c17;
                    I7CXX:
                    OVUfq:
                    goto C_r_Q;
                    YOSBG:
                    goto SMgb4;
                    goto B3Uwn;
                    P0c17:
                    if (is_string($v_value)) {
                        goto OVUfq;
                    }
                    goto n5JnY;
                    Ki80h:
                    return PclZip::errorCode();
                    goto I7CXX;
                    C_r_Q:
                    $p_filedescr["\x6e\x65\167\x5f\x73\150\x6f\162\x74\x5f\156\141\x6d\145"] = PclZipUtilPathReduction($v_value);
                    goto RYkjH;
                    n5JnY:
                    PclZip::privErrorLog(PCLZIP_ERR_INVALID_ATTRIBUTE_VALUE, "\111\156\166\x61\154\151\x64\40\164\171\160\x65\x20" . gettype($v_value) . "\x2e\40\123\x74\x72\151\156\x67\40\x65\x78\160\145\143\164\x65\144\40\x66\157\162\x20\141\164\x74\x72\151\142\x75\164\145\40\47" . PclZipUtilOptionText($v_key) . "\x27");
                    goto Ki80h;
                    tMwpj:
                    m9ym5:
                    goto YOSBG;
                    RYkjH:
                    if (!($p_filedescr["\156\x65\x77\x5f\163\150\157\x72\x74\x5f\x6e\141\155\x65"] == '')) {
                        goto m9ym5;
                    }
                    goto kalfK;
                    THz8a:
                    return PclZip::errorCode();
                    goto tMwpj;
                    kalfK:
                    PclZip::privErrorLog(PCLZIP_ERR_INVALID_ATTRIBUTE_VALUE, "\111\156\166\x61\154\151\x64\x20\x65\155\x70\164\x79\40\x73\x68\157\162\x74\x20\x66\151\154\x65\156\x61\x6d\x65\x20\146\157\162\40\141\164\x74\162\151\x62\165\164\x65\40\x27" . PclZipUtilOptionText($v_key) . "\47");
                    goto THz8a;
                    B3Uwn:
                case PCLZIP_ATT_FILE_NEW_FULL_NAME:
                    goto l3WKj;
                    Drpb6:
                    return PclZip::errorCode();
                    goto hB0_0;
                    qy1jl:
                    if (!($p_filedescr["\156\x65\x77\x5f\146\165\154\x6c\x5f\156\141\x6d\145"] == '')) {
                        goto qTZBX;
                    }
                    goto t6eKk;
                    m0ETK:
                    goto SMgb4;
                    goto NuJ6X;
                    l3WKj:
                    if (is_string($v_value)) {
                        goto sYPUU;
                    }
                    goto RTw7n;
                    fzHe0:
                    $p_filedescr["\156\x65\x77\137\x66\x75\x6c\x6c\137\156\x61\x6d\x65"] = PclZipUtilPathReduction($v_value);
                    goto qy1jl;
                    RTw7n:
                    PclZip::privErrorLog(PCLZIP_ERR_INVALID_ATTRIBUTE_VALUE, "\111\156\x76\141\154\151\x64\40\x74\171\x70\x65\x20" . gettype($v_value) . "\x2e\x20\123\164\x72\x69\x6e\x67\40\x65\170\x70\x65\x63\x74\x65\x64\40\146\157\162\40\x61\164\x74\162\x69\142\x75\164\x65\x20\47" . PclZipUtilOptionText($v_key) . "\x27");
                    goto pBLzM;
                    pBLzM:
                    return PclZip::errorCode();
                    goto hXqsr;
                    hXqsr:
                    sYPUU:
                    goto fzHe0;
                    hB0_0:
                    qTZBX:
                    goto m0ETK;
                    t6eKk:
                    PclZip::privErrorLog(PCLZIP_ERR_INVALID_ATTRIBUTE_VALUE, "\x49\x6e\x76\x61\154\151\x64\40\145\155\160\x74\171\40\x66\165\154\x6c\x20\x66\151\154\145\x6e\141\155\145\x20\146\x6f\162\40\x61\164\164\162\x69\142\165\x74\x65\x20\x27" . PclZipUtilOptionText($v_key) . "\47");
                    goto Drpb6;
                    NuJ6X:
                case PCLZIP_ATT_FILE_COMMENT:
                    goto eBxCc;
                    L9Qoc:
                    return PclZip::errorCode();
                    goto QAVyy;
                    QAVyy:
                    onjt_:
                    goto R9WvZ;
                    eBxCc:
                    if (is_string($v_value)) {
                        goto onjt_;
                    }
                    goto W7Xf3;
                    W7Xf3:
                    PclZip::privErrorLog(PCLZIP_ERR_INVALID_ATTRIBUTE_VALUE, "\111\156\166\x61\154\151\144\40\164\x79\160\x65\40" . gettype($v_value) . "\x2e\40\x53\164\162\x69\x6e\147\40\x65\x78\x70\145\143\x74\x65\x64\x20\146\157\x72\x20\x61\x74\x74\x72\x69\x62\x75\x74\x65\x20\47" . PclZipUtilOptionText($v_key) . "\x27");
                    goto L9Qoc;
                    R9WvZ:
                    $p_filedescr["\x63\x6f\x6d\x6d\145\x6e\164"] = $v_value;
                    goto N74y8;
                    N74y8:
                    goto SMgb4;
                    goto aH19w;
                    aH19w:
                case PCLZIP_ATT_FILE_MTIME:
                    goto seqFV;
                    vudWc:
                    hxYV3:
                    goto G5FXw;
                    zh5b5:
                    goto SMgb4;
                    goto HGKTl;
                    G5FXw:
                    $p_filedescr["\x6d\164\x69\x6d\x65"] = $v_value;
                    goto zh5b5;
                    seqFV:
                    if (is_integer($v_value)) {
                        goto hxYV3;
                    }
                    goto jahEd;
                    jahEd:
                    PclZip::privErrorLog(PCLZIP_ERR_INVALID_ATTRIBUTE_VALUE, "\111\x6e\x76\x61\154\151\x64\x20\164\x79\160\x65\x20" . gettype($v_value) . "\x2e\40\x49\156\x74\x65\147\x65\x72\x20\145\x78\x70\x65\x63\x74\145\x64\x20\x66\x6f\x72\x20\x61\x74\164\x72\151\x62\165\164\145\40\x27" . PclZipUtilOptionText($v_key) . "\x27");
                    goto Vdps8;
                    Vdps8:
                    return PclZip::errorCode();
                    goto vudWc;
                    HGKTl:
                case PCLZIP_ATT_FILE_CONTENT:
                    $p_filedescr["\143\157\x6e\164\x65\156\x74"] = $v_value;
                    goto SMgb4;
                default:
                    PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "\x55\x6e\153\156\157\x77\x6e\40\x70\141\162\x61\155\145\164\x65\x72\x20\x27" . $v_key . "\x27");
                    return PclZip::errorCode();
            }
            goto r6Zr2;
            HQBJt:
            return PclZip::errorCode();
            goto NTBd7;
            d85Vi:
            ayrVX:
            goto lXmRR;
            NuyOV:
            return PclZip::errorCode();
            goto NeiRW;
            lXmRR:
            UBMIQ:
            goto xXB42;
            S8SLW:
            if (isset($p_file_list[$key])) {
                goto WGdZ9;
            }
            goto utPlz;
            P5ng6:
            dZzyn:
            goto wSv7y;
            MachB:
        }
        goto aCeRe;
        XvVLC:
        $v_result = 1;
        goto K7jwJ;
        tqz6B:
        return $v_result;
        goto mPZaK;
        aCeRe:
        rmH4H:
        goto tqz6B;
        mPZaK:
    }
    public function privFileDescrExpand(&$p_filedescr_list, &$p_options)
    {
        goto wL37U;
        BuTn6:
        eeUcU:
        goto sKsNn;
        c_YfA:
        goto ihxfm;
        goto JLRRN;
        CJ3gm:
        return $v_result;
        goto i5g3e;
        hKmdp:
        $v_dirlist_nb++;
        goto xe1Id;
        uZ_UC:
        LV9yo:
        goto gaCWx;
        qYotP:
        return $v_result;
        goto ZsuIF;
        IgYqN:
        $v_result_list[sizeof($v_result_list)] = $v_descr;
        goto P4Cm9;
        AgoY0:
        $v_descr = $p_filedescr_list[$i];
        goto sb_J6;
        E6Tr6:
        if ($v_dirlist_nb != 0) {
            goto CCiYv;
        }
        goto r9uZi;
        yMyRv:
        if ($v_descr["\163\x74\x6f\162\x65\144\x5f\x66\x69\x6c\145\x6e\141\x6d\x65"] != '') {
            goto LV9yo;
        }
        goto GGrWk;
        r9uZi:
        goto jHFZC;
        goto vi1Z6;
        DQo2B:
        $v_descr["\164\x79\x70\x65"] = "\146\151\x6c\145";
        goto WMr54;
        wc6E2:
        if (file_exists($v_descr["\146\151\154\x65\x6e\x61\x6d\x65"])) {
            goto eeUcU;
        }
        goto GNRb8;
        UwodE:
        return PclZip::errorCode();
        goto QKGSF;
        Gscjz:
        if (!($v_descr["\x73\164\x6f\162\x65\x64\x5f\146\x69\x6c\145\156\x61\155\145"] != $v_descr["\146\x69\x6c\x65\156\x61\155\145"] && !isset($p_options[PCLZIP_OPT_REMOVE_ALL_PATH]))) {
            goto Kw5OR;
        }
        goto yMyRv;
        YrL6K:
        $v_result_list = array();
        goto b3ahy;
        L0po2:
        UguuM:
        goto sG1GG;
        PIaA8:
        iI0YT:
        goto PLR55;
        TyEHD:
        $this->privCalculateStoredFilename($v_descr, $p_options);
        goto IgYqN;
        OAssO:
        $v_dirlist_nb = 0;
        goto vQDLh;
        P4Cm9:
        if (!($v_descr["\x74\x79\160\145"] == "\146\157\x6c\x64\145\162")) {
            goto UUcx1;
        }
        goto jHr8s;
        XSdFT:
        if (@is_dir($v_descr["\x66\x69\154\x65\156\141\x6d\x65"])) {
            goto iI0YT;
        }
        goto a0SJc;
        MXI2U:
        unset($v_dirlist_descr);
        goto E3BQx;
        JCgnA:
        mujup:
        goto y5gA6;
        PWxFy:
        if (!(($v_result = $this->privFileDescrExpand($v_dirlist_descr, $p_options)) != 1)) {
            goto FIe_2;
        }
        goto CJ3gm;
        i5g3e:
        FIe_2:
        goto P9lLM;
        QIMA6:
        L77fZ:
        goto DQo2B;
        bbCgh:
        PclZip::privErrorLog(PCLZIP_ERR_MISSING_FILE, "\x46\x69\x6c\145\40\x27" . $v_descr["\x66\x69\154\x65\x6e\x61\x6d\x65"] . "\47\x20\x64\157\x65\x73\x20\x6e\157\x74\40\x65\x78\x69\163\x74");
        goto UwodE;
        IR4W8:
        $v_descr["\164\x79\x70\x65"] = "\x76\x69\162\x74\x75\x61\154\x5f\x66\x69\154\145";
        goto YqCZz;
        b3ahy:
        $i = 0;
        goto EPDmz;
        y5gA6:
        $v_dirlist_descr[$v_dirlist_nb]["\x66\151\x6c\x65\x6e\141\x6d\145"] = $v_descr["\x66\x69\154\145\x6e\141\155\x65"] . "\57" . $v_item_handler;
        goto Gscjz;
        M7cDJ:
        if (!(($v_item_handler = @readdir($v_folder_handler)) !== false)) {
            goto UguuM;
        }
        goto SULs0;
        QKGSF:
        goto RaTpQ;
        goto BuTn6;
        tbnDw:
        goto qFDyS;
        goto uZ_UC;
        teMLM:
        goto mvr52;
        goto QIMA6;
        G5SKd:
        if (!($i < sizeof($p_filedescr_list))) {
            goto OpGUj;
        }
        goto AgoY0;
        vi1Z6:
        CCiYv:
        goto PWxFy;
        geafz:
        goto bdsH7;
        goto M1Q1D;
        E_9ie:
        qFDyS:
        goto AMz1P;
        IQYxJ:
        goto a3GZV;
        goto JCgnA;
        P9lLM:
        $v_result_list = array_merge($v_result_list, $v_dirlist_descr);
        goto XOpys;
        wL37U:
        $v_result = 1;
        goto YrL6K;
        P6fUj:
        goto yyGxP;
        goto hfKVG;
        rczII:
        goto mvr52;
        goto Wtdag;
        GNRb8:
        if (isset($v_descr["\143\x6f\156\164\x65\156\x74"])) {
            goto C6opB;
        }
        goto bbCgh;
        AtvWu:
        $i++;
        goto geafz;
        XOpys:
        jHFZC:
        goto MXI2U;
        sb_J6:
        $v_descr["\x66\x69\x6c\145\x6e\141\155\x65"] = PclZipUtilTranslateWinPath($v_descr["\146\151\x6c\x65\156\x61\x6d\145"], false);
        goto Igvbe;
        sKsNn:
        if (@is_file($v_descr["\146\151\x6c\145\x6e\x61\x6d\145"])) {
            goto L77fZ;
        }
        goto XSdFT;
        GGrWk:
        $v_dirlist_descr[$v_dirlist_nb]["\156\145\167\x5f\146\165\154\x6c\137\x6e\141\155\145"] = $v_item_handler;
        goto tbnDw;
        I11Ia:
        goto yyGxP;
        goto teMLM;
        hfKVG:
        mvr52:
        goto y0utb;
        M1Q1D:
        OpGUj:
        goto OQi7f;
        JLRRN:
        NV4vD:
        goto tdCtx;
        jHr8s:
        $v_dirlist_descr = array();
        goto OAssO;
        tdCtx:
        a3GZV:
        goto M7cDJ;
        y0utb:
        goto RaTpQ;
        goto CBrjJ;
        vQDLh:
        if ($v_folder_handler = @opendir($v_descr["\x66\x69\154\145\x6e\141\155\145"])) {
            goto NV4vD;
        }
        goto c_YfA;
        YqCZz:
        RaTpQ:
        goto TyEHD;
        OQi7f:
        $p_filedescr_list = $v_result_list;
        goto qYotP;
        SULs0:
        if (!($v_item_handler == "\x2e" || $v_item_handler == "\x2e\x2e")) {
            goto mujup;
        }
        goto IQYxJ;
        PLR55:
        $v_descr["\x74\x79\x70\145"] = "\x66\x6f\154\x64\145\162";
        goto rczII;
        CBrjJ:
        C6opB:
        goto IR4W8;
        a0SJc:
        if (@is_link($v_descr["\146\151\x6c\x65\156\x61\x6d\145"])) {
            goto WZYiL;
        }
        goto I11Ia;
        E3BQx:
        UUcx1:
        goto p837l;
        sG1GG:
        @closedir($v_folder_handler);
        goto TSQbt;
        WMr54:
        goto mvr52;
        goto PIaA8;
        EPDmz:
        bdsH7:
        goto G5SKd;
        gaCWx:
        $v_dirlist_descr[$v_dirlist_nb]["\x6e\145\x77\137\x66\x75\x6c\154\x5f\x6e\x61\x6d\145"] = $v_descr["\163\164\x6f\x72\145\144\137\x66\x69\x6c\x65\156\x61\155\145"] . "\x2f" . $v_item_handler;
        goto E_9ie;
        xe1Id:
        goto a3GZV;
        goto L0po2;
        p837l:
        yyGxP:
        goto AtvWu;
        Wtdag:
        WZYiL:
        goto P6fUj;
        TSQbt:
        ihxfm:
        goto E6Tr6;
        AMz1P:
        Kw5OR:
        goto hKmdp;
        Igvbe:
        $v_descr["\146\151\x6c\x65\156\x61\155\x65"] = PclZipUtilPathReduction($v_descr["\146\151\154\x65\x6e\x61\x6d\x65"]);
        goto wc6E2;
        ZsuIF:
    }
    public function privCreate($p_filedescr_list, &$p_result_list, &$p_options)
    {
        goto ezWvW;
        LQ4l0:
        SB92n:
        goto d0C9L;
        rTUbs:
        return $v_result;
        goto FzBky;
        bOEJJ:
        $this->privCloseFd();
        goto F5lEo;
        ezWvW:
        $v_result = 1;
        goto ch_Za;
        DHd57:
        $this->privDisableMagicQuotes();
        goto IKB3M;
        F5lEo:
        $this->privSwapBackMagicQuotes();
        goto rTUbs;
        bgsaL:
        return $v_result;
        goto LQ4l0;
        IKB3M:
        if (!(($v_result = $this->privOpenFd("\167\142")) != 1)) {
            goto SB92n;
        }
        goto bgsaL;
        d0C9L:
        $v_result = $this->privAddList($p_filedescr_list, $p_result_list, $p_options);
        goto bOEJJ;
        ch_Za:
        $v_list_detail = array();
        goto DHd57;
        FzBky:
    }
    public function privAdd($p_filedescr_list, &$p_result_list, &$p_options)
    {
        goto bW_FR;
        daJxU:
        $this->zip_fd = $v_zip_temp_fd;
        goto t6UvA;
        fyPgg:
        XTDFZ:
        goto yXTJl;
        yXTJl:
        $i++;
        goto hvJH0;
        BWPaS:
        $this->privDisableMagicQuotes();
        goto NGcj1;
        hvJH0:
        goto IY3ln;
        goto BSKMj;
        c7IbG:
        $this->privSwapBackMagicQuotes();
        goto KbIWC;
        XOj1K:
        $v_read_size = $v_size < PCLZIP_READ_BLOCK_SIZE ? $v_size : PCLZIP_READ_BLOCK_SIZE;
        goto fW8y9;
        ymzK9:
        eeIX7:
        goto m37MC;
        bLUNM:
        $v_read_size = $v_size < PCLZIP_READ_BLOCK_SIZE ? $v_size : PCLZIP_READ_BLOCK_SIZE;
        goto B3z5v;
        KbIWC:
        return $v_result;
        goto UgSDp;
        Y61iS:
        @rewind($this->zip_fd);
        goto BwTcl;
        Srolp:
        goto JUrnz;
        goto WD0uK;
        SWT6_:
        $v_size = $v_central_dir["\x73\151\x7a\x65"];
        goto WbJXF;
        qYI6t:
        @unlink($v_zip_temp_name);
        goto bgBW6;
        LVMBk:
        if (!($v_size != 0)) {
            goto zUI9M;
        }
        goto bLUNM;
        BwTcl:
        $v_zip_temp_name = PCLZIP_TEMPORARY_DIR . uniqid("\160\143\x6c\x7a\151\160\x2d") . "\56\164\155\160";
        goto MKg31;
        oODn9:
        if (!(($v_result = $this->privReadEndCentralDir($v_central_dir)) != 1)) {
            goto WKVyv;
        }
        goto mYnRa;
        xAUU1:
        $this->privSwapBackMagicQuotes();
        goto eCN1N;
        u2Uio:
        $this->privConvertHeader2FileInfo($v_header_list[$i], $p_result_list[$i]);
        goto fyPgg;
        u612q:
        $v_size -= $v_read_size;
        goto Srolp;
        NGcj1:
        if (!(($v_result = $this->privOpenFd("\162\x62")) != 1)) {
            goto T7eWF;
        }
        goto BQPR4;
        qY13Q:
        IY3ln:
        goto Y_HJj;
        aiGtn:
        if (!(($v_result = $this->privAddFileList($p_filedescr_list, $v_header_list, $p_options)) != 1)) {
            goto napSq;
        }
        goto VQr1a;
        qK2r4:
        $v_result = $this->privCreate($p_filedescr_list, $p_result_list, $p_options);
        goto FB22p;
        jsFrF:
        $v_comment = $p_options[PCLZIP_OPT_COMMENT];
        goto GN_S6;
        xY1LV:
        JUrnz:
        goto LVMBk;
        gCxU1:
        WKVyv:
        goto Y61iS;
        quBmc:
        if (!isset($p_options[PCLZIP_OPT_COMMENT])) {
            goto TyNZA;
        }
        goto jsFrF;
        tGgQb:
        PclZipUtilRename($v_zip_temp_name, $this->zipname);
        goto U_ZFe;
        hmaBs:
        $this->privSwapBackMagicQuotes();
        goto jy6R0;
        ctf7A:
        if (!(($v_result = $this->privWriteCentralFileHeader($v_header_list[$i])) != 1)) {
            goto jZPXi;
        }
        goto iQCxs;
        PswOZ:
        $this->zip_fd = $v_zip_temp_fd;
        goto OELNt;
        XOLRk:
        unset($v_header_list);
        goto c7IbG;
        m37MC:
        if (!isset($p_options[PCLZIP_OPT_PREPEND_COMMENT])) {
            goto cd8Cr;
        }
        goto RdPa2;
        lgyIg:
        $v_count++;
        goto LKHvG;
        U_ZFe:
        return $v_result;
        goto nmXho;
        MKg31:
        if (!(($v_zip_temp_fd = @fopen($v_zip_temp_name, "\167\142")) == 0)) {
            goto Chy9a;
        }
        goto XLlHo;
        qijpt:
        cd8Cr:
        goto Afdby;
        NmTh8:
        if (!isset($p_options[PCLZIP_OPT_ADD_COMMENT])) {
            goto eeIX7;
        }
        goto qSAFS;
        Y_HJj:
        if (!($i < sizeof($v_header_list))) {
            goto XN2Hd;
        }
        goto cJ9Y6;
        iQCxs:
        fclose($v_zip_temp_fd);
        goto lhoJO;
        Afdby:
        $v_size = @ftell($this->zip_fd) - $v_offset;
        goto z9Cc6;
        i3k33:
        Chy9a:
        goto Y0jks;
        WbJXF:
        agUs5:
        goto dmgR8;
        FoZkq:
        $v_swap = $this->zip_fd;
        goto PswOZ;
        XLlHo:
        $this->privCloseFd();
        goto DqJgK;
        ZSC7s:
        ZgW79:
        goto BWPaS;
        bgBW6:
        $this->privSwapBackMagicQuotes();
        goto FErNz;
        GN_S6:
        TyNZA:
        goto NmTh8;
        pMJDA:
        $this->privCloseFd();
        goto YwzqN;
        cJ9Y6:
        if (!($v_header_list[$i]["\x73\x74\141\164\165\163"] == "\157\153")) {
            goto Sy2jE;
        }
        goto ctf7A;
        X0Izo:
        @fclose($v_zip_temp_fd);
        goto hmaBs;
        HMv_5:
        $this->privSwapBackMagicQuotes();
        goto X2U8G;
        DaiBC:
        jZPXi:
        goto lgyIg;
        B3z5v:
        $v_buffer = fread($this->zip_fd, $v_read_size);
        goto GESDj;
        QxEEm:
        $v_count = 0;
        goto qY13Q;
        X2U8G:
        return $v_result;
        goto V6MYG;
        FErNz:
        return $v_result;
        goto DaiBC;
        GESDj:
        @fwrite($v_zip_temp_fd, $v_buffer, $v_read_size);
        goto u612q;
        HUHKS:
        @fwrite($this->zip_fd, $v_buffer, $v_read_size);
        goto bZwII;
        YwzqN:
        @unlink($v_zip_temp_name);
        goto HMv_5;
        cJw3B:
        $v_header_list = array();
        goto aiGtn;
        U7byS:
        $this->privCloseFd();
        goto X0Izo;
        Y3neN:
        $v_offset = @ftell($this->zip_fd);
        goto SWT6_;
        WD0uK:
        zUI9M:
        goto FoZkq;
        eCN1N:
        return $v_result;
        goto gCxU1;
        LKHvG:
        Sy2jE:
        goto u2Uio;
        fW8y9:
        $v_buffer = @fread($v_zip_temp_fd, $v_read_size);
        goto HUHKS;
        t2u4a:
        T7eWF:
        goto OHgge;
        lhoJO:
        $this->privCloseFd();
        goto qYI6t;
        lekpE:
        $v_swap = $this->zip_fd;
        goto daJxU;
        OHgge:
        $v_central_dir = array();
        goto oODn9;
        t6UvA:
        $v_zip_temp_fd = $v_swap;
        goto U7byS;
        yQhx7:
        return $v_result;
        goto t2u4a;
        dmgR8:
        if (!($v_size != 0)) {
            goto CAF2i;
        }
        goto XOj1K;
        FB22p:
        return $v_result;
        goto ZSC7s;
        AFb0M:
        $v_comment = $v_central_dir["\x63\157\155\155\145\156\x74"];
        goto quBmc;
        z9Cc6:
        if (!(($v_result = $this->privWriteCentralHeader($v_count + $v_central_dir["\145\156\x74\162\151\145\163"], $v_size, $v_offset, $v_comment)) != 1)) {
            goto qZCzI;
        }
        goto XOLRk;
        VQr1a:
        fclose($v_zip_temp_fd);
        goto pMJDA;
        gjKT7:
        PclZip::privErrorLog(PCLZIP_ERR_READ_OPEN_FAIL, "\125\x6e\141\142\x6c\145\40\164\x6f\x20\157\x70\145\x6e\40\164\145\x6d\160\157\x72\141\162\x79\x20\x66\151\x6c\x65\40\x27" . $v_zip_temp_name . "\x27\x20\151\156\x20\142\151\x6e\x61\x72\x79\x20\167\162\151\164\x65\40\155\157\144\145");
        goto kqJ2N;
        RJ3t1:
        $v_list_detail = array();
        goto kvYS0;
        bW_FR:
        $v_result = 1;
        goto RJ3t1;
        NMXEm:
        CAF2i:
        goto hDpWh;
        kvYS0:
        if (!(!is_file($this->zipname) || filesize($this->zipname) == 0)) {
            goto ZgW79;
        }
        goto qK2r4;
        OELNt:
        $v_zip_temp_fd = $v_swap;
        goto cJw3B;
        kqJ2N:
        return PclZip::errorCode();
        goto i3k33;
        DqJgK:
        $this->privSwapBackMagicQuotes();
        goto gjKT7;
        qSAFS:
        $v_comment = $v_comment . $p_options[PCLZIP_OPT_ADD_COMMENT];
        goto ymzK9;
        bZwII:
        $v_size -= $v_read_size;
        goto ojmch;
        RdPa2:
        $v_comment = $p_options[PCLZIP_OPT_PREPEND_COMMENT] . $v_comment;
        goto qijpt;
        mYnRa:
        $this->privCloseFd();
        goto xAUU1;
        UgSDp:
        qZCzI:
        goto lekpE;
        jy6R0:
        @unlink($this->zipname);
        goto tGgQb;
        V6MYG:
        napSq:
        goto Y3neN;
        ojmch:
        goto agUs5;
        goto NMXEm;
        BSKMj:
        XN2Hd:
        goto AFb0M;
        BQPR4:
        $this->privSwapBackMagicQuotes();
        goto yQhx7;
        Y0jks:
        $v_size = $v_central_dir["\x6f\146\146\x73\145\164"];
        goto xY1LV;
        hDpWh:
        $i = 0;
        goto QxEEm;
        nmXho:
    }
    public function privOpenFd($p_mode)
    {
        goto c6xN0;
        gdgXi:
        return PclZip::errorCode();
        goto PJdyZ;
        c6xN0:
        $v_result = 1;
        goto Nz1V7;
        Nz1V7:
        if (!($this->zip_fd != 0)) {
            goto dbS2e;
        }
        goto nGMGA;
        VhOB7:
        PclZip::privErrorLog(PCLZIP_ERR_READ_OPEN_FAIL, "\125\156\x61\142\154\145\x20\x74\157\x20\x6f\x70\145\x6e\x20\x61\162\x63\150\x69\x76\145\40\47" . $this->zipname . "\x27\40\x69\156\x20" . $p_mode . "\40\x6d\157\x64\145");
        goto k2POZ;
        scEiP:
        H7Vbr:
        goto t1EpC;
        PJdyZ:
        dbS2e:
        goto glqnc;
        nGMGA:
        PclZip::privErrorLog(PCLZIP_ERR_READ_OPEN_FAIL, "\132\x69\160\40\x66\x69\154\145\x20\47" . $this->zipname . "\47\x20\x61\x6c\x72\145\x61\x64\171\x20\157\160\145\x6e");
        goto gdgXi;
        k2POZ:
        return PclZip::errorCode();
        goto scEiP;
        glqnc:
        if (!(($this->zip_fd = @fopen($this->zipname, $p_mode)) == 0)) {
            goto H7Vbr;
        }
        goto VhOB7;
        t1EpC:
        return $v_result;
        goto LJ7Rj;
        LJ7Rj:
    }
    public function privCloseFd()
    {
        goto cj0CK;
        Wc2wD:
        if (!($this->zip_fd != 0)) {
            goto ugOqb;
        }
        goto k_sVP;
        zdogT:
        return $v_result;
        goto k67XR;
        k_sVP:
        @fclose($this->zip_fd);
        goto zrUsK;
        B1qRz:
        $this->zip_fd = 0;
        goto zdogT;
        zrUsK:
        ugOqb:
        goto B1qRz;
        cj0CK:
        $v_result = 1;
        goto Wc2wD;
        k67XR:
    }
    public function privAddList($p_filedescr_list, &$p_result_list, &$p_options)
    {
        goto gfp2b;
        HgQRj:
        $v_offset = @ftell($this->zip_fd);
        goto V3mXG;
        UWGvE:
        if (!($v_header_list[$i]["\163\x74\141\164\165\163"] == "\x6f\153")) {
            goto Mv3hu;
        }
        goto JKdNZ;
        qD7VI:
        $this->privConvertHeader2FileInfo($v_header_list[$i], $p_result_list[$i]);
        goto dNYKO;
        Zz8s2:
        op5XS:
        goto hCt0d;
        FFpVR:
        $v_comment = '';
        goto YHYES;
        dNYKO:
        VeZGo:
        goto DidHC;
        ZNoHV:
        return $v_result;
        goto M5RLz;
        Tcxuu:
        if (!($i < sizeof($v_header_list))) {
            goto Oz0wc;
        }
        goto UWGvE;
        gUDkJ:
        RCDyT:
        goto HgQRj;
        Xwmb_:
        r7GjC:
        goto lTIFb;
        ikCzv:
        if (!(($v_result = $this->privAddFileList($p_filedescr_list, $v_header_list, $p_options)) != 1)) {
            goto RCDyT;
        }
        goto C530r;
        vCgva:
        if (!(($v_result = $this->privWriteCentralHeader($v_count, $v_size, $v_offset, $v_comment)) != 1)) {
            goto f4Dc3;
        }
        goto Rk0aG;
        gfp2b:
        $v_result = 1;
        goto uJ_sB;
        hCt0d:
        $v_size = @ftell($this->zip_fd) - $v_offset;
        goto vCgva;
        lTIFb:
        $v_count++;
        goto VpsWy;
        Rk0aG:
        unset($v_header_list);
        goto YZnSc;
        DidHC:
        $i++;
        goto bNJY3;
        Wsh0n:
        Oz0wc:
        goto FFpVR;
        l41sZ:
        f4Dc3:
        goto ZNoHV;
        JnEBc:
        $v_comment = $p_options[PCLZIP_OPT_COMMENT];
        goto Zz8s2;
        uJ_sB:
        $v_header_list = array();
        goto ikCzv;
        JKdNZ:
        if (!(($v_result = $this->privWriteCentralFileHeader($v_header_list[$i])) != 1)) {
            goto r7GjC;
        }
        goto HDqO_;
        bNJY3:
        goto aYvVV;
        goto Wsh0n;
        YZnSc:
        return $v_result;
        goto l41sZ;
        VpsWy:
        Mv3hu:
        goto qD7VI;
        YHYES:
        if (!isset($p_options[PCLZIP_OPT_COMMENT])) {
            goto op5XS;
        }
        goto JnEBc;
        HDqO_:
        return $v_result;
        goto Xwmb_;
        ZSRTD:
        aYvVV:
        goto Tcxuu;
        V3mXG:
        $i = 0;
        goto YubJX;
        C530r:
        return $v_result;
        goto gUDkJ;
        YubJX:
        $v_count = 0;
        goto ZSRTD;
        M5RLz:
    }
    public function privAddFileList($p_filedescr_list, &$p_result_list, &$p_options)
    {
        goto ldG0y;
        r2yMe:
        goto Xv_2r;
        goto CUil9;
        xxQcM:
        return PclZip::errorCode();
        goto UyjZu;
        dJHkU:
        if (!($v_result != 1)) {
            goto LpNmR;
        }
        goto dPbua;
        CDaD2:
        $v_header = array();
        goto WC371;
        l2WcW:
        $j++;
        goto RFBtk;
        H0qUV:
        $p_result_list[$v_nb++] = $v_header;
        goto fomQ0;
        fWUzX:
        hQiuW:
        goto lH0Ml;
        NaPfy:
        $v_result = $this->privAddFile($p_filedescr_list[$j], $v_header, $p_options);
        goto dJHkU;
        iFwjQ:
        if (!($p_filedescr_list[$j]["\164\x79\160\x65"] != "\x76\x69\162\164\x75\x61\154\137\x66\x69\154\x65" && !file_exists($p_filedescr_list[$j]["\x66\151\x6c\x65\156\141\155\x65"]))) {
            goto AFQdn;
        }
        goto NfDay;
        NfDay:
        PclZip::privErrorLog(PCLZIP_ERR_MISSING_FILE, "\x46\151\x6c\145\40\47" . $p_filedescr_list[$j]["\146\x69\154\145\x6e\x61\155\145"] . "\x27\x20\x64\x6f\x65\163\40\x6e\x6f\164\40\x65\x78\151\x73\164");
        goto xxQcM;
        Wh2AB:
        ideif:
        goto g99Sf;
        UyjZu:
        AFQdn:
        goto yrXRH;
        g99Sf:
        if (!($j < sizeof($p_filedescr_list) && $v_result == 1)) {
            goto hQiuW;
        }
        goto F3GJm;
        WC371:
        $v_nb = sizeof($p_result_list);
        goto THPK8;
        BzuTC:
        LpNmR:
        goto H0qUV;
        lH0Ml:
        return $v_result;
        goto XQ2ne;
        CUil9:
        Xik97:
        goto iFwjQ;
        dPbua:
        return $v_result;
        goto BzuTC;
        yrXRH:
        if (!($p_filedescr_list[$j]["\164\x79\160\145"] == "\146\151\x6c\x65" || $p_filedescr_list[$j]["\164\171\160\145"] == "\x76\151\x72\164\x75\141\154\x5f\146\x69\x6c\145" || $p_filedescr_list[$j]["\x74\171\160\x65"] == "\146\157\154\144\145\x72" && (!isset($p_options[PCLZIP_OPT_REMOVE_ALL_PATH]) || !$p_options[PCLZIP_OPT_REMOVE_ALL_PATH]))) {
            goto U65a4;
        }
        goto NaPfy;
        ldG0y:
        $v_result = 1;
        goto CDaD2;
        fomQ0:
        U65a4:
        goto Tanwl;
        THPK8:
        $j = 0;
        goto Wh2AB;
        IBO4h:
        if (!($p_filedescr_list[$j]["\146\x69\154\x65\x6e\141\155\x65"] == '')) {
            goto Xik97;
        }
        goto r2yMe;
        RFBtk:
        goto ideif;
        goto fWUzX;
        F3GJm:
        $p_filedescr_list[$j]["\146\151\x6c\145\x6e\x61\x6d\x65"] = PclZipUtilTranslateWinPath($p_filedescr_list[$j]["\146\x69\154\x65\x6e\x61\x6d\145"], false);
        goto IBO4h;
        Tanwl:
        Xv_2r:
        goto l2WcW;
        XQ2ne:
    }
    public function privAddFile($p_filedescr, &$p_header, &$p_options)
    {
        goto gPbXb;
        fQedF:
        PclZip::privErrorLog(PCLZIP_ERR_READ_OPEN_FAIL, "\x55\x6e\x61\x62\x6c\145\40\164\157\x20\157\160\x65\x6e\40\146\151\154\145\40\47{$p_filename}\x27\x20\x69\x6e\40\x62\x69\x6e\141\x72\x79\40\x72\145\x61\144\40\x6d\x6f\x64\x65");
        goto Y04NE;
        HdNoG:
        $v_content = $p_filedescr["\x63\157\156\x74\145\156\x74"];
        goto ewIgS;
        qL5qn:
        lqAFw:
        goto peZo1;
        vimpn:
        $this->privConvertHeader2FileInfo($p_header, $v_local_header);
        goto N2zwN;
        uIZ_f:
        wS00o:
        goto sL6JH;
        y3Aaj:
        $v_result = 1;
        goto zhCnU;
        mCzEs:
        $p_header["\x63\157\155\160\162\x65\163\x73\145\x64\137\163\x69\x7a\145"] = $p_header["\163\x69\x7a\145"];
        goto xMycw;
        sCbLy:
        INovr:
        goto tlFz9;
        gyM05:
        $p_header["\163\164\141\164\165\163"] = "\x73\153\x69\x70\160\x65\x64";
        goto qyEQw;
        d7nbv:
        $p_header["\144\x69\x73\153"] = 0;
        goto tEhXY;
        zhCnU:
        ttHQh:
        goto gQiSz;
        gQiSz:
        bRp2j:
        goto w_qC4;
        tEhXY:
        $p_header["\151\x6e\164\145\x72\x6e\x61\154"] = 0;
        goto SKIne;
        UAXVg:
        goto YyhmV;
        goto wmc52;
        uoreu:
        yElHi:
        goto k0llu;
        LIQar:
        Rgw9b:
        goto pwZ3x;
        O1DJq:
        @fclose($v_file);
        goto fIUXR;
        RKuUm:
        hrlDX:
        goto FnmGR;
        agXRF:
        if (isset($p_filedescr["\x6d\x74\151\x6d\145"])) {
            goto QGYdF;
        }
        goto iIPwM;
        MVPv9:
        $p_header["\163\151\x7a\x65"] = strlen($p_filedescr["\143\x6f\x6e\x74\145\x6e\x74"]);
        goto oQNPe;
        FnmGR:
        if (!($p_header["\163\164\x6f\162\x65\x64\137\146\x69\154\x65\156\x61\155\x65"] != $v_local_header["\163\164\x6f\162\145\x64\137\x66\151\x6c\145\156\141\155\x65"])) {
            goto LxZas;
        }
        goto PHdXj;
        bSEzb:
        $p_header["\x63\x72\143"] = 0;
        goto pP5uP;
        pP5uP:
        $p_header["\143\157\x6d\x70\x72\x65\163\163\x65\x64\137\x73\x69\172\x65"] = 0;
        goto rVpZT;
        LAogN:
        m_abQ:
        goto mCzEs;
        SKIne:
        $p_header["\x6f\x66\x66\x73\145\164"] = 0;
        goto ZUj2S;
        R8JoI:
        OzaEp:
        goto Fk7qB;
        aRFYA:
        if (!($v_result == 0)) {
            goto hrlDX;
        }
        goto gyM05;
        mB3t7:
        $p_header["\x66\154\x61\x67"] = 0;
        goto k3Ly2;
        cg3b_:
        if ($p_filedescr["\x74\x79\x70\145"] == "\x66\157\x6c\144\145\x72") {
            goto y8vTe;
        }
        goto Le072;
        j3dNb:
        HNYDc:
        goto Za_tn;
        tlFz9:
        $p_header["\145\170\164\145\162\156\x61\154"] = 0;
        goto MVPv9;
        vfRBy:
        goto U248S;
        goto p_R_r;
        UdE0M:
        if ($p_options[PCLZIP_OPT_NO_COMPRESSION]) {
            goto m_abQ;
        }
        goto NtPBg;
        w_qC4:
        return $v_result;
        goto x65i5;
        DpEIl:
        if ($p_options[PCLZIP_OPT_NO_COMPRESSION]) {
            goto M81jp;
        }
        goto HhRYy;
        xMycw:
        $p_header["\143\157\155\160\162\145\163\x73\151\157\x6e"] = 0;
        goto gDTK1;
        qyEQw:
        $v_result = 1;
        goto RKuUm;
        nLR65:
        goto lfoJt;
        goto sCbLy;
        Clubi:
        $p_header["\x69\x6e\144\145\170"] = -1;
        goto qccSM;
        mMWLU:
        goto lfoJt;
        goto ehlWy;
        Xx5EZ:
        $p_header["\x76\x65\x72\x73\151\x6f\156"] = 20;
        goto JI51t;
        RWUR1:
        goto b7VoT;
        goto c2wN0;
        hLa9b:
        $v_local_header = array();
        goto d_WFO;
        JYWQR:
        goto BAEon;
        goto LAogN;
        p_R_r:
        onJV8:
        goto HdNoG;
        fJOfF:
        Ojto6:
        goto RCyRl;
        rHtNu:
        $p_header["\155\164\x69\155\145"] = $p_filedescr["\x6d\x74\151\155\145"];
        goto RWUR1;
        gByvO:
        LRhPk:
        goto KGou7;
        mYN7T:
        $p_header["\x73\164\157\162\145\144\x5f\146\151\154\145\156\x61\x6d\145"] .= "\x2f";
        goto gByvO;
        PjEP4:
        goto OzaEp;
        goto RJNAT;
        k0llu:
        $v_content = @fread($v_file, $p_header["\x73\x69\x7a\145"]);
        goto VrN2I;
        vEVG7:
        $p_header["\163\164\141\x74\165\163"] = "\146\151\x6c\145\156\x61\155\x65\x5f\x74\x6f\157\x5f\154\x6f\156\147";
        goto j3dNb;
        wmc52:
        hq2Qj:
        goto fFUSN;
        YyqFR:
        QGYdF:
        goto rHtNu;
        WboBm:
        $p_header["\163\164\x61\164\x75\163"] = "\x6f\153";
        goto Clubi;
        CXsEH:
        Umtdg:
        goto pz8Qd;
        XjvNx:
        U248S:
        goto S7kNf;
        HOZM7:
        goto lfoJt;
        goto P1xp3;
        AnQvZ:
        if (!(($v_file = @fopen($p_filename, "\162\x62")) == 0)) {
            goto yElHi;
        }
        goto fQedF;
        k3Ly2:
        $p_header["\143\x6f\155\160\x72\x65\x73\x73\x69\157\156"] = 0;
        goto bSEzb;
        Le072:
        if ($p_filedescr["\x74\x79\160\x65"] == "\166\x69\x72\164\x75\141\154\x5f\x66\151\154\145") {
            goto INovr;
        }
        goto HOZM7;
        PWF2y:
        wR30P:
        goto zQCQc;
        OCQIZ:
        ad6qe:
        goto qaMTE;
        bQMCr:
        @fwrite($this->zip_fd, $v_content, $p_header["\143\157\155\x70\162\145\163\x73\x65\x64\137\x73\x69\x7a\x65"]);
        goto uUC9f;
        uGagh:
        GyngO:
        goto XjvNx;
        t5HiH:
        M81jp:
        goto tySCQ;
        qccSM:
        if ($p_filedescr["\164\x79\x70\145"] == "\146\x69\x6c\x65") {
            goto WndvS;
        }
        goto cg3b_;
        M2qud:
        $p_header["\x63\x6f\155\160\162\x65\163\163\145\x64\x5f\163\151\172\145"] = strlen($v_content);
        goto roII7;
        pz8Qd:
        if (!isset($p_options[PCLZIP_OPT_TEMP_FILE_OFF]) && (isset($p_options[PCLZIP_OPT_TEMP_FILE_ON]) || isset($p_options[PCLZIP_OPT_TEMP_FILE_THRESHOLD]) && $p_options[PCLZIP_OPT_TEMP_FILE_THRESHOLD] <= $p_header["\x73\x69\172\x65"])) {
            goto hq2Qj;
        }
        goto AnQvZ;
        RJNAT:
        f7cGY:
        goto efCrP;
        EE6a1:
        $p_header["\143\157\155\x6d\x65\x6e\164\137\x6c\x65\156"] = 0;
        goto LA2Gj;
        Qz1S6:
        $p_header["\145\170\x74\162\141\x5f\x6c\145\x6e"] = 0;
        goto d7nbv;
        S94s4:
        PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "\x49\x6e\x76\141\154\151\x64\40\146\x69\x6c\x65\x20\x6c\151\x73\164\40\160\141\x72\141\x6d\145\x74\145\162\40\50\x69\x6e\166\x61\154\x69\x64\40\x6f\162\40\x65\155\160\164\x79\40\x6c\151\163\x74\51");
        goto P2OQK;
        spg1L:
        if ($p_filedescr["\x74\x79\x70\145"] == "\x66\x69\154\145") {
            goto Umtdg;
        }
        goto kZZ8h;
        S7kNf:
        Rcup9:
        goto DQShH;
        DQShH:
        if (!isset($p_options[PCLZIP_CB_POST_ADD])) {
            goto bRp2j;
        }
        goto hLa9b;
        VEG2p:
        $p_header["\163\164\141\x74\165\x73"] = "\146\151\x6c\164\145\x72\145\x64";
        goto PWF2y;
        VrN2I:
        @fclose($v_file);
        goto h6PY2;
        ewIgS:
        $p_header["\143\162\143"] = @crc32($v_content);
        goto UdE0M;
        gPbXb:
        $v_result = 1;
        goto jjyJ8;
        W4Rxk:
        if (!(($v_result = $this->privWriteFileHeader($p_header)) != 1)) {
            goto tUf23;
        }
        goto hl09m;
        bDSYu:
        $p_header["\163\x74\x6f\x72\x65\x64\x5f\146\x69\154\145\156\x61\155\145"] = $p_filedescr["\163\164\157\x72\145\x64\x5f\x66\x69\154\145\x6e\141\155\x65"];
        goto M43nO;
        gJ9u5:
        if (!(($v_result = $this->privWriteFileHeader($p_header)) != 1)) {
            goto GyngO;
        }
        goto JncbV;
        oQNPe:
        lfoJt:
        goto agXRF;
        sZ7Qz:
        return $v_result;
        goto O89QB;
        LU7_M:
        goto U248S;
        goto CXsEH;
        Fk7qB:
        if (!isset($p_options[PCLZIP_CB_PRE_ADD])) {
            goto Thg0Q;
        }
        goto Lm0zi;
        N2zwN:
        $v_result = $p_options[PCLZIP_CB_PRE_ADD](PCLZIP_CB_PRE_ADD, $v_local_header);
        goto aRFYA;
        P2OQK:
        return PclZip::errorCode();
        goto LIQar;
        JfW8H:
        goto ad6qe;
        goto t5HiH;
        P1xp3:
        WndvS:
        goto uf1ui;
        dge7E:
        goto b7VoT;
        goto YyqFR;
        kZZ8h:
        if ($p_filedescr["\164\171\160\x65"] == "\166\x69\x72\x74\x75\141\x6c\137\x66\x69\154\145") {
            goto onJV8;
        }
        goto TZJfE;
        hl09m:
        @fclose($v_file);
        goto sZ7Qz;
        NtPBg:
        $v_content = @gzdeflate($v_content);
        goto g7mvg;
        UQTq_:
        $p_header["\x63\157\x6d\155\x65\156\x74"] = $p_filedescr["\x63\157\155\155\145\x6e\164"];
        goto R8JoI;
        d_WFO:
        $this->privConvertHeader2FileInfo($p_header, $v_local_header);
        goto b70nG;
        COnvx:
        if (!($v_result == 0)) {
            goto ttHQh;
        }
        goto y3Aaj;
        h6PY2:
        $p_header["\x63\162\143"] = @crc32($v_content);
        goto DpEIl;
        efCrP:
        $p_header["\x63\x6f\x6d\155\145\156\x74\x5f\154\145\x6e"] = strlen($p_filedescr["\x63\x6f\x6d\x6d\x65\156\164"]);
        goto UQTq_;
        zQCQc:
        if (!(strlen($p_header["\x73\x74\x6f\162\145\x64\137\x66\151\x6c\x65\x6e\141\x6d\145"]) > 255)) {
            goto HNYDc;
        }
        goto vEVG7;
        PHdXj:
        $p_header["\163\x74\157\162\x65\x64\x5f\x66\151\154\145\x6e\x61\155\x65"] = PclZipUtilPathReduction($v_local_header["\x73\164\157\x72\x65\x64\137\x66\x69\154\145\156\141\x6d\145"]);
        goto fpivK;
        ZUj2S:
        $p_header["\x66\x69\x6c\x65\x6e\x61\155\145"] = $p_filename;
        goto bDSYu;
        kO3R9:
        $p_header["\143\x6f\x6d\x70\162\145\163\x73\x69\157\156"] = 0;
        goto OCQIZ;
        rVpZT:
        $p_header["\x66\151\x6c\x65\156\141\x6d\x65\x5f\154\145\x6e"] = strlen($p_filename);
        goto Qz1S6;
        fIUXR:
        return $v_result;
        goto qL5qn;
        Lm0zi:
        $v_local_header = array();
        goto vimpn;
        qaMTE:
        if (!(($v_result = $this->privWriteFileHeader($p_header)) != 1)) {
            goto lqAFw;
        }
        goto O1DJq;
        fFUSN:
        $v_result = $this->privAddFileUsingTempFile($p_filedescr, $p_header, $p_options);
        goto Z7hLd;
        j3Ew4:
        $p_header["\163\151\x7a\145"] = filesize($p_filename);
        goto nLR65;
        iIPwM:
        if ($p_filedescr["\x74\171\x70\x65"] == "\166\151\162\x74\165\x61\154\137\146\151\x6c\x65") {
            goto HX17I;
        }
        goto vOzTD;
        BnQ_1:
        Thg0Q:
        goto USHGV;
        JncbV:
        return $v_result;
        goto uGagh;
        TZJfE:
        if ($p_filedescr["\164\x79\160\x65"] == "\146\157\x6c\x64\x65\x72") {
            goto Ojto6;
        }
        goto LU7_M;
        HhRYy:
        $v_content = @gzdeflate($v_content);
        goto M2qud;
        EJWG7:
        $p_header["\145\170\x74\x65\x72\x6e\x61\x6c"] = 16;
        goto gJ9u5;
        RCyRl:
        if (!(@substr($p_header["\163\164\157\162\x65\144\137\146\x69\x6c\145\x6e\x61\x6d\145"], -1) != "\x2f")) {
            goto LRhPk;
        }
        goto mYN7T;
        pwZ3x:
        clearstatcache();
        goto Xx5EZ;
        HEOdu:
        $p_header["\x6d\x74\x69\155\x65"] = time();
        goto lUE_q;
        O89QB:
        tUf23:
        goto bQMCr;
        roII7:
        $p_header["\x63\x6f\x6d\160\x72\145\x73\163\x69\157\156"] = 8;
        goto JfW8H;
        snZwH:
        $p_header["\x65\170\x74\x65\162\x6e\141\x6c"] = 16;
        goto xvPKF;
        sL6JH:
        YyhmV:
        goto vfRBy;
        uUC9f:
        goto U248S;
        goto fJOfF;
        b70nG:
        $v_result = $p_options[PCLZIP_CB_POST_ADD](PCLZIP_CB_POST_ADD, $v_local_header);
        goto COnvx;
        pYVTO:
        if (!($p_filename == '')) {
            goto Rgw9b;
        }
        goto S94s4;
        Y04NE:
        return PclZip::errorCode();
        goto uoreu;
        fpivK:
        LxZas:
        goto BnQ_1;
        nF3cJ:
        return $v_result;
        goto uIZ_f;
        ehlWy:
        y8vTe:
        goto snZwH;
        lUE_q:
        b7VoT:
        goto U06bj;
        peZo1:
        @fwrite($this->zip_fd, $v_content, $p_header["\143\157\155\160\162\x65\163\x73\145\144\x5f\x73\x69\x7a\145"]);
        goto UAXVg;
        USHGV:
        if (!($p_header["\163\x74\157\x72\145\144\x5f\146\x69\154\145\156\141\x6d\x65"] == '')) {
            goto wR30P;
        }
        goto VEG2p;
        QqSrt:
        $p_header["\x73\x69\x7a\x65"] = filesize($p_filename);
        goto mMWLU;
        LA2Gj:
        $p_header["\x63\x6f\155\155\x65\x6e\x74"] = '';
        goto PjEP4;
        uf1ui:
        $p_header["\145\x78\164\x65\162\x6e\x61\x6c"] = 0;
        goto QqSrt;
        KGou7:
        $p_header["\x73\x69\x7a\x65"] = 0;
        goto EJWG7;
        xvPKF:
        $p_header["\x6d\164\151\155\145"] = filemtime($p_filename);
        goto j3Ew4;
        Za_tn:
        if (!($p_header["\163\164\141\164\165\x73"] == "\157\x6b")) {
            goto Rcup9;
        }
        goto spg1L;
        U06bj:
        if (isset($p_filedescr["\143\157\155\155\145\x6e\164"])) {
            goto f7cGY;
        }
        goto EE6a1;
        jjyJ8:
        $p_filename = $p_filedescr["\146\151\x6c\x65\156\x61\155\145"];
        goto pYVTO;
        tySCQ:
        $p_header["\x63\157\155\x70\x72\x65\x73\x73\x65\144\137\163\x69\172\x65"] = $p_header["\163\151\x7a\145"];
        goto kO3R9;
        FBILr:
        $p_header["\x63\x6f\155\x70\162\145\163\163\151\x6f\x6e"] = 8;
        goto JYWQR;
        JI51t:
        $p_header["\x76\145\x72\x73\x69\157\156\137\145\x78\x74\162\141\143\x74\145\144"] = 10;
        goto mB3t7;
        g7mvg:
        $p_header["\143\157\155\x70\x72\145\x73\163\145\144\x5f\163\151\x7a\145"] = strlen($v_content);
        goto FBILr;
        Z7hLd:
        if (!($v_result < PCLZIP_ERR_NO_ERROR)) {
            goto wS00o;
        }
        goto nF3cJ;
        gDTK1:
        BAEon:
        goto W4Rxk;
        c2wN0:
        HX17I:
        goto HEOdu;
        vOzTD:
        $p_header["\x6d\x74\151\155\145"] = filemtime($p_filename);
        goto dge7E;
        M43nO:
        $p_header["\x65\x78\x74\162\141"] = '';
        goto WboBm;
        x65i5:
    }
    public function privAddFileUsingTempFile($p_filedescr, &$p_header, &$p_options)
    {
        goto tEe1I;
        dBQbQ:
        $v_size = filesize($p_filename);
        goto IVq1v;
        jF7cO:
        @fclose($v_file_compressed);
        goto cgC8z;
        CMomi:
        return PclZip::errorCode();
        goto BUeP6;
        wpNoS:
        return $v_result;
        goto d3yFj;
        Xh9bh:
        $v_data_header["\x6f\x73"] = bin2hex($v_data_header["\157\163"]);
        goto tZCpp;
        wP2ai:
        $p_filename = $p_filedescr["\x66\x69\154\145\x6e\141\155\145"];
        goto jCgSA;
        eXLTV:
        if (!(filesize($v_gzip_temp_name) < 18)) {
            goto cwyek;
        }
        goto lk6m5;
        RhbXo:
        if (!($v_size != 0)) {
            goto mqqps;
        }
        goto gwmqH;
        L_PL7:
        $v_size = $p_header["\x63\x6f\155\x70\162\x65\x73\x73\x65\144\x5f\163\151\172\x65"];
        goto KvYVx;
        OWKmH:
        @fwrite($this->zip_fd, $v_buffer, $v_read_size);
        goto fGLOo;
        t56pZ:
        $p_header["\143\x6f\155\x70\x72\145\163\x73\x69\157\156"] = ord($v_data_header["\143\155"]);
        goto moC4y;
        tEe1I:
        $v_result = PCLZIP_ERR_NO_ERROR;
        goto wP2ai;
        wXYgf:
        goto pFxZ8;
        goto iuqko;
        lPIF9:
        $v_binary_data = @fread($v_file_compressed, 10);
        goto hKrgT;
        KvYVx:
        FWpis:
        goto RhbXo;
        wQQZa:
        $v_size -= $v_read_size;
        goto wXYgf;
        NS0UB:
        $v_binary_data = @fread($v_file_compressed, 8);
        goto hlea3;
        tzhkj:
        @gzputs($v_file_compressed, $v_buffer, $v_read_size);
        goto wQQZa;
        KU5KJ:
        if (!($v_size != 0)) {
            goto k37zk;
        }
        goto t5s5T;
        H_huN:
        if (!(($v_file_compressed = @gzopen($v_gzip_temp_name, "\x77\x62")) == 0)) {
            goto f3QoZ;
        }
        goto fP8Ac;
        moC4y:
        $p_header["\x63\x72\x63"] = $v_data_footer["\x63\162\x63"];
        goto ysJoI;
        W_8d6:
        return PclZip::errorCode();
        goto Ufett;
        tZCpp:
        @fseek($v_file_compressed, filesize($v_gzip_temp_name) - 8);
        goto NS0UB;
        iuqko:
        k37zk:
        goto klCiR;
        ktmWG:
        PclZip::privErrorLog(PCLZIP_ERR_READ_OPEN_FAIL, "\x55\x6e\141\142\x6c\145\40\164\157\x20\x6f\160\x65\x6e\40\146\x69\154\x65\40\47{$p_filename}\47\40\x69\156\x20\x62\x69\x6e\141\x72\171\40\x72\x65\x61\x64\40\x6d\157\x64\x65");
        goto u10jS;
        tU_Z8:
        if (!(($v_result = $this->privWriteFileHeader($p_header)) != 1)) {
            goto V__Y_;
        }
        goto wpNoS;
        fJtfH:
        PclZip::privErrorLog(PCLZIP_ERR_READ_OPEN_FAIL, "\x55\156\141\x62\x6c\145\40\x74\x6f\40\x6f\160\145\x6e\x20\x74\145\x6d\x70\x6f\x72\141\162\x79\x20\x66\151\x6c\x65\x20\x27" . $v_gzip_temp_name . "\x27\40\151\156\x20\142\x69\156\141\x72\x79\40\162\x65\x61\x64\x20\x6d\x6f\x64\145");
        goto WQcaW;
        nTYqt:
        PclZip::privErrorLog(PCLZIP_ERR_WRITE_OPEN_FAIL, "\x55\x6e\x61\x62\154\145\40\x74\157\40\157\160\x65\156\x20\x74\145\155\160\x6f\x72\141\162\171\40\x66\151\x6c\145\x20\x27" . $v_gzip_temp_name . "\47\x20\x69\156\40\x62\x69\x6e\x61\x72\x79\x20\x77\162\x69\164\145\40\155\157\x64\x65");
        goto W_8d6;
        u10jS:
        return PclZip::errorCode();
        goto FWF3u;
        bj9WP:
        bXzwr:
        goto iT710;
        iT710:
        fseek($v_file_compressed, 10);
        goto L_PL7;
        Sfts5:
        return $v_result;
        goto qw6sD;
        a2px4:
        @fclose($v_file_compressed);
        goto tU_Z8;
        jCgSA:
        if (!(($v_file = @fopen($p_filename, "\162\142")) == 0)) {
            goto E_qd5;
        }
        goto ktmWG;
        hlea3:
        $v_data_footer = unpack("\x56\x63\x72\143\x2f\126\143\157\x6d\160\x72\x65\163\163\145\144\137\163\151\172\x65", $v_binary_data);
        goto t56pZ;
        FWF3u:
        E_qd5:
        goto uvzJz;
        klCiR:
        @fclose($v_file);
        goto KWs_M;
        fGLOo:
        $v_size -= $v_read_size;
        goto Ho0Nx;
        d3yFj:
        V__Y_:
        goto ZuqRd;
        lk6m5:
        PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, "\147\x7a\151\160\40\x74\145\155\x70\157\162\141\162\x79\x20\x66\x69\x6c\145\x20\47" . $v_gzip_temp_name . "\47\x20\150\141\163\40\151\156\x76\141\x6c\x69\144\40\146\x69\x6c\145\x73\151\172\x65\x20\x2d\x20\163\150\157\165\154\x64\x20\142\145\40\155\x69\x6e\x69\155\x75\155\x20\61\70\x20\142\x79\x74\x65\x73");
        goto CMomi;
        iyARu:
        mqqps:
        goto jF7cO;
        Ufett:
        f3QoZ:
        goto dBQbQ;
        Ho0Nx:
        goto FWpis;
        goto iyARu;
        QJGgZ:
        $v_buffer = @fread($v_file_compressed, $v_read_size);
        goto OWKmH;
        uvzJz:
        $v_gzip_temp_name = PCLZIP_TEMPORARY_DIR . uniqid("\160\x63\154\x7a\x69\x70\55") . "\56\x67\x7a";
        goto H_huN;
        hKrgT:
        $v_data_header = unpack("\141\61\151\x64\61\57\141\61\151\x64\x32\x2f\x61\x31\143\155\x2f\141\x31\146\154\x61\147\x2f\126\155\x74\x69\155\x65\57\141\61\170\x66\x6c\x2f\141\x31\157\x73", $v_binary_data);
        goto Xh9bh;
        fP8Ac:
        fclose($v_file);
        goto nTYqt;
        ZuqRd:
        if (!(($v_file_compressed = @fopen($v_gzip_temp_name, "\162\142")) == 0)) {
            goto bXzwr;
        }
        goto fJtfH;
        BUeP6:
        cwyek:
        goto DYGsz;
        IVq1v:
        pFxZ8:
        goto KU5KJ;
        KWs_M:
        @gzclose($v_file_compressed);
        goto eXLTV;
        yGGv8:
        xhXDy:
        goto lPIF9;
        Zi8Vo:
        $v_buffer = @fread($v_file, $v_read_size);
        goto tzhkj;
        DYGsz:
        if (!(($v_file_compressed = @fopen($v_gzip_temp_name, "\162\x62")) == 0)) {
            goto xhXDy;
        }
        goto rbnIk;
        gwmqH:
        $v_read_size = $v_size < PCLZIP_READ_BLOCK_SIZE ? $v_size : PCLZIP_READ_BLOCK_SIZE;
        goto QJGgZ;
        WQcaW:
        return PclZip::errorCode();
        goto bj9WP;
        cgC8z:
        @unlink($v_gzip_temp_name);
        goto Sfts5;
        ysJoI:
        $p_header["\x63\x6f\155\x70\162\x65\x73\163\145\x64\137\x73\151\x7a\x65"] = filesize($v_gzip_temp_name) - 18;
        goto a2px4;
        rbnIk:
        PclZip::privErrorLog(PCLZIP_ERR_READ_OPEN_FAIL, "\125\x6e\141\x62\x6c\145\40\x74\157\40\157\160\x65\x6e\x20\164\145\155\160\x6f\162\x61\x72\171\40\x66\151\x6c\x65\40\47" . $v_gzip_temp_name . "\47\x20\151\x6e\x20\142\151\156\141\x72\x79\40\162\x65\141\x64\x20\155\157\144\145");
        goto oAyRt;
        t5s5T:
        $v_read_size = $v_size < PCLZIP_READ_BLOCK_SIZE ? $v_size : PCLZIP_READ_BLOCK_SIZE;
        goto Zi8Vo;
        oAyRt:
        return PclZip::errorCode();
        goto yGGv8;
        qw6sD:
    }
    public function privCalculateStoredFilename(&$p_filedescr, &$p_options)
    {
        goto dE5su;
        Ku1ry:
        tXh1b:
        goto rb_vf;
        hxvw0:
        goto xvdXF;
        goto CT2Fx;
        HLUDT:
        goto NakkA;
        goto u_gKW;
        rb_vf:
        Mlqfh:
        goto RHH5O;
        BNwWM:
        NakkA:
        goto fznGj;
        loCiA:
        ewtNu:
        goto L7_js;
        slkEN:
        if (isset($p_options[PCLZIP_OPT_REMOVE_PATH])) {
            goto NRisU;
        }
        goto iSs0E;
        MnThd:
        if (!(substr($p_filename, 0, 2) == "\x2e\57" || substr($p_remove_dir, 0, 2) == "\56\57")) {
            goto Mlqfh;
        }
        goto xY5uT;
        NmG1S:
        $v_stored_filename = $p_filename;
        goto hxvw0;
        r2F7w:
        ClI23:
        goto BNwWM;
        iSs0E:
        $p_remove_dir = '';
        goto y03fY;
        h5xCv:
        $p_remove_all_dir = 0;
        goto mAQWu;
        LwwRU:
        if (!($v_compare > 0)) {
            goto ClI23;
        }
        goto pXU43;
        b9LrV:
        $v_stored_filename = $p_add_dir . "\57" . $v_stored_filename;
        goto ZmpP8;
        CT2Fx:
        fbCYg:
        goto EYiBF;
        y03fY:
        goto qWB8i;
        goto TlqZS;
        X2yX3:
        W0LRs:
        goto mLDox;
        NAuMW:
        goto NakkA;
        goto shk42;
        he2mo:
        if (substr($p_add_dir, -1) == "\57") {
            goto cK4za;
        }
        goto b9LrV;
        mAQWu:
        goto d12YR;
        goto X2yX3;
        kmUUD:
        $v_stored_filename = '';
        goto wF7BV;
        vgyg0:
        if (!($p_add_dir != '')) {
            goto tW4O8;
        }
        goto he2mo;
        VYhCy:
        tW4O8:
        goto aQ3JM;
        HbFup:
        cK4za:
        goto cb_Ja;
        RHH5O:
        $v_compare = PclZipUtilPathInclusion($p_remove_dir, $v_stored_filename);
        goto LwwRU;
        fzYrL:
        $p_remove_dir = "\56\x2f" . $p_remove_dir;
        goto loCiA;
        shk42:
        Lr8kN:
        goto x70Tn;
        u_gKW:
        PzxcY:
        goto z2iwY;
        mLDox:
        $p_remove_all_dir = $p_options[PCLZIP_OPT_REMOVE_ALL_PATH];
        goto ACgZl;
        ZmpP8:
        goto nK_Os;
        goto HbFup;
        x70Tn:
        if (!(substr($p_remove_dir, -1) != "\x2f")) {
            goto C_VVI;
        }
        goto vFDE1;
        vFDE1:
        $p_remove_dir .= "\57";
        goto pfsYf;
        CBv52:
        goto EpuBa;
        goto FXN1Q;
        TOgEs:
        if (isset($p_filedescr["\x6e\145\x77\137\163\150\x6f\x72\x74\x5f\x6e\x61\x6d\x65"])) {
            goto fbCYg;
        }
        goto NmG1S;
        caX9d:
        sjB3s:
        goto aYa2B;
        VfWuy:
        if ($p_remove_dir != '') {
            goto Lr8kN;
        }
        goto HLUDT;
        q7Bj3:
        $v_dir = '';
        goto OgRo1;
        FQg3z:
        return $v_result;
        goto FBaEs;
        GUnZI:
        if ($p_remove_all_dir) {
            goto PzxcY;
        }
        goto VfWuy;
        z2iwY:
        $v_stored_filename = basename($p_filename);
        goto NAuMW;
        irHhO:
        $v_stored_filename = PclZipUtilTranslateWinPath($p_filedescr["\x6e\x65\167\x5f\146\165\x6c\x6c\137\x6e\x61\x6d\145"]);
        goto caX9d;
        dE5su:
        $v_result = 1;
        goto uRHsB;
        loyzH:
        if (isset($p_filedescr["\x6e\x65\x77\137\x66\x75\154\x6c\137\156\141\x6d\x65"])) {
            goto Pv905;
        }
        goto TOgEs;
        mqZ0H:
        qWB8i:
        goto i6yrS;
        L7_js:
        if (!(substr($p_filename, 0, 2) != "\56\57" && substr($p_remove_dir, 0, 2) == "\56\57")) {
            goto tXh1b;
        }
        goto kV38G;
        tDK39:
        $p_add_dir = '';
        goto CBv52;
        ACgZl:
        d12YR:
        goto loyzH;
        OgRo1:
        if (!($v_path_info["\144\x69\x72\156\x61\155\x65"] != '')) {
            goto wmELu;
        }
        goto YxuEB;
        rR6Vm:
        if (isset($p_options[PCLZIP_OPT_ADD_PATH])) {
            goto CDsJA;
        }
        goto tDK39;
        aYa2B:
        $v_stored_filename = PclZipUtilPathReduction($v_stored_filename);
        goto x6yQW;
        EYiBF:
        $v_path_info = pathinfo($p_filename);
        goto q7Bj3;
        hu0A_:
        $p_remove_dir = $p_options[PCLZIP_OPT_REMOVE_PATH];
        goto mqZ0H;
        x6yQW:
        $p_filedescr["\x73\164\x6f\x72\x65\x64\137\146\x69\154\x65\x6e\141\x6d\145"] = $v_stored_filename;
        goto FQg3z;
        b1dGI:
        $v_stored_filename = $v_dir . $p_filedescr["\156\145\167\137\x73\x68\x6f\162\x74\137\x6e\141\x6d\x65"];
        goto gFpDV;
        kV38G:
        $p_remove_dir = substr($p_remove_dir, 2);
        goto Ku1ry;
        uRHsB:
        $p_filename = $p_filedescr["\146\151\154\145\x6e\x61\x6d\145"];
        goto rR6Vm;
        FXN1Q:
        CDsJA:
        goto nUhYi;
        PshHm:
        $v_stored_filename = substr($v_stored_filename, strlen($p_remove_dir));
        goto d8gJN;
        pXU43:
        if ($v_compare == 2) {
            goto CDnK0;
        }
        goto PshHm;
        xY5uT:
        if (!(substr($p_filename, 0, 2) == "\56\x2f" && substr($p_remove_dir, 0, 2) != "\x2e\57")) {
            goto ewtNu;
        }
        goto fzYrL;
        G3W0z:
        EpuBa:
        goto slkEN;
        i6yrS:
        if (isset($p_options[PCLZIP_OPT_REMOVE_ALL_PATH])) {
            goto W0LRs;
        }
        goto h5xCv;
        aQ3JM:
        goto sjB3s;
        goto iLf_c;
        TlqZS:
        NRisU:
        goto hu0A_;
        fznGj:
        $v_stored_filename = PclZipUtilTranslateWinPath($v_stored_filename);
        goto vgyg0;
        nLSOc:
        CDnK0:
        goto kmUUD;
        nUhYi:
        $p_add_dir = $p_options[PCLZIP_OPT_ADD_PATH];
        goto G3W0z;
        d8gJN:
        goto wOm8F;
        goto nLSOc;
        iLf_c:
        Pv905:
        goto irHhO;
        wF7BV:
        wOm8F:
        goto r2F7w;
        gFpDV:
        xvdXF:
        goto GUnZI;
        Ri_UT:
        wmELu:
        goto b1dGI;
        pfsYf:
        C_VVI:
        goto MnThd;
        YxuEB:
        $v_dir = $v_path_info["\x64\x69\162\x6e\141\155\145"] . "\57";
        goto Ri_UT;
        MJakN:
        nK_Os:
        goto VYhCy;
        cb_Ja:
        $v_stored_filename = $p_add_dir . $v_stored_filename;
        goto MJakN;
        FBaEs:
    }
    public function privWriteFileHeader(&$p_header)
    {
        goto KHYqF;
        hbfab:
        JUyff:
        goto g7NXl;
        d9I6s:
        fputs($this->zip_fd, $v_binary_data, 30);
        goto t40PY;
        wl3Z5:
        $v_mdate = ($v_date["\171\x65\141\162"] - 1980 << 9) + ($v_date["\155\x6f\156"] << 5) + $v_date["\155\x64\x61\x79"];
        goto lEI_V;
        MMRUY:
        $p_header["\157\x66\x66\163\145\164"] = ftell($this->zip_fd);
        goto KT1Yj;
        lEI_V:
        $v_binary_data = pack("\x56\166\x76\166\x76\x76\x56\x56\x56\166\x76", 67324752, $p_header["\x76\145\162\163\151\157\x6e\137\145\x78\x74\x72\141\143\164\145\144"], $p_header["\146\x6c\141\147"], $p_header["\x63\x6f\x6d\160\x72\145\x73\163\x69\157\x6e"], $v_mtime, $v_mdate, $p_header["\143\x72\x63"], $p_header["\143\x6f\x6d\160\162\145\x73\x73\x65\x64\137\163\151\x7a\145"], $p_header["\x73\151\172\145"], strlen($p_header["\x73\164\x6f\162\145\x64\x5f\x66\x69\154\x65\x6e\141\155\145"]), $p_header["\145\170\x74\x72\141\137\154\x65\156"]);
        goto d9I6s;
        FWAyb:
        fputs($this->zip_fd, $p_header["\x65\170\164\162\x61"], $p_header["\x65\x78\x74\x72\141\x5f\154\x65\x6e"]);
        goto hbfab;
        K1IzB:
        if (!($p_header["\145\x78\x74\162\141\x5f\154\145\156"] != 0)) {
            goto JUyff;
        }
        goto FWAyb;
        ybvun:
        D1J9e:
        goto K1IzB;
        t40PY:
        if (!(strlen($p_header["\163\x74\157\x72\x65\144\x5f\146\151\x6c\145\156\x61\155\x65"]) != 0)) {
            goto D1J9e;
        }
        goto QNhH2;
        UTfQQ:
        $v_mtime = ($v_date["\x68\x6f\165\x72\163"] << 11) + ($v_date["\155\151\156\165\164\145\163"] << 5) + $v_date["\x73\x65\x63\157\156\x64\163"] / 2;
        goto wl3Z5;
        g7NXl:
        return $v_result;
        goto yujdY;
        KHYqF:
        $v_result = 1;
        goto MMRUY;
        QNhH2:
        fputs($this->zip_fd, $p_header["\163\164\157\162\145\144\137\x66\151\x6c\145\x6e\x61\x6d\x65"], strlen($p_header["\163\x74\157\162\145\x64\x5f\146\151\154\x65\x6e\141\x6d\x65"]));
        goto ybvun;
        KT1Yj:
        $v_date = getdate($p_header["\x6d\x74\x69\x6d\x65"]);
        goto UTfQQ;
        yujdY:
    }
    public function privWriteCentralFileHeader(&$p_header)
    {
        goto f7MWl;
        Dg3Cu:
        $v_mtime = ($v_date["\x68\x6f\165\x72\x73"] << 11) + ($v_date["\x6d\151\x6e\x75\x74\145\x73"] << 5) + $v_date["\x73\145\143\157\156\x64\163"] / 2;
        goto d9FKx;
        HcM2U:
        fputs($this->zip_fd, $p_header["\145\x78\164\162\141"], $p_header["\x65\x78\164\x72\x61\x5f\x6c\145\156"]);
        goto QtKJs;
        QtKJs:
        IhES6:
        goto ZWawC;
        d9FKx:
        $v_mdate = ($v_date["\x79\x65\x61\x72"] - 1980 << 9) + ($v_date["\155\157\x6e"] << 5) + $v_date["\x6d\144\x61\x79"];
        goto arD7q;
        ZCiMT:
        fputs($this->zip_fd, $p_header["\x63\157\x6d\x6d\x65\x6e\x74"], $p_header["\143\157\x6d\155\145\x6e\x74\137\x6c\x65\x6e"]);
        goto IGcAG;
        arD7q:
        $v_binary_data = pack("\126\x76\x76\x76\166\x76\166\x56\126\126\x76\166\166\x76\166\126\x56", 33639248, $p_header["\x76\x65\x72\163\x69\157\156"], $p_header["\x76\x65\162\x73\151\x6f\x6e\137\145\x78\x74\162\141\143\x74\145\144"], $p_header["\146\x6c\141\147"], $p_header["\x63\157\x6d\160\x72\145\x73\163\x69\157\x6e"], $v_mtime, $v_mdate, $p_header["\x63\x72\143"], $p_header["\143\157\155\x70\x72\145\163\163\x65\144\x5f\x73\x69\x7a\x65"], $p_header["\163\x69\x7a\x65"], strlen($p_header["\x73\164\157\162\x65\144\137\146\151\154\x65\x6e\x61\x6d\145"]), $p_header["\145\170\x74\162\141\x5f\154\145\156"], $p_header["\143\157\x6d\x6d\x65\x6e\x74\137\x6c\x65\156"], $p_header["\x64\x69\x73\153"], $p_header["\151\156\164\x65\162\x6e\x61\154"], $p_header["\x65\x78\164\145\162\156\x61\154"], $p_header["\157\x66\x66\163\x65\164"]);
        goto Tp0Oc;
        kVZZK:
        fputs($this->zip_fd, $p_header["\x73\164\157\x72\145\x64\x5f\146\151\x6c\145\156\x61\x6d\x65"], strlen($p_header["\163\164\157\x72\145\144\137\x66\151\x6c\x65\156\x61\155\x65"]));
        goto S7zQN;
        Tp0Oc:
        fputs($this->zip_fd, $v_binary_data, 46);
        goto seuSX;
        seuSX:
        if (!(strlen($p_header["\163\x74\x6f\162\145\144\137\x66\151\x6c\145\x6e\141\155\145"]) != 0)) {
            goto p4y09;
        }
        goto kVZZK;
        f7MWl:
        $v_result = 1;
        goto oLAC9;
        IGcAG:
        vSAJZ:
        goto yHsus;
        oLAC9:
        $v_date = getdate($p_header["\155\x74\151\x6d\145"]);
        goto Dg3Cu;
        ZWawC:
        if (!($p_header["\x63\x6f\x6d\x6d\145\156\x74\137\154\145\156"] != 0)) {
            goto vSAJZ;
        }
        goto ZCiMT;
        S7zQN:
        p4y09:
        goto vx4md;
        vx4md:
        if (!($p_header["\145\x78\x74\162\x61\x5f\x6c\145\156"] != 0)) {
            goto IhES6;
        }
        goto HcM2U;
        yHsus:
        return $v_result;
        goto rhVPc;
        rhVPc:
    }
    public function privWriteCentralHeader($p_nb_entries, $p_size, $p_offset, $p_comment)
    {
        goto b9rwe;
        zCp6U:
        fputs($this->zip_fd, $v_binary_data, 22);
        goto duCfV;
        rcF6_:
        hd1pC:
        goto l_gKB;
        duCfV:
        if (!(strlen($p_comment) != 0)) {
            goto hd1pC;
        }
        goto oMgeh;
        l_gKB:
        return $v_result;
        goto Z7Pr7;
        b9rwe:
        $v_result = 1;
        goto haq6M;
        oMgeh:
        fputs($this->zip_fd, $p_comment, strlen($p_comment));
        goto rcF6_;
        haq6M:
        $v_binary_data = pack("\x56\166\x76\x76\166\126\x56\x76", 101010256, 0, 0, $p_nb_entries, $p_nb_entries, $p_size, $p_offset, strlen($p_comment));
        goto zCp6U;
        Z7Pr7:
    }
    public function privList(&$p_list)
    {
        goto N07W2;
        IEszu:
        if (!(($v_result = $this->privReadCentralFileHeader($v_header)) != 1)) {
            goto Q0eWW;
        }
        goto HWp3o;
        Jsizn:
        $this->privSwapBackMagicQuotes();
        goto rlsxG;
        Uj0ha:
        $this->privDisableMagicQuotes();
        goto Qp7eX;
        FB71v:
        o7rH5:
        goto u12Ld;
        Z3po0:
        PclZip::privErrorLog(PCLZIP_ERR_READ_OPEN_FAIL, "\125\156\141\142\x6c\x65\40\164\x6f\40\x6f\160\145\x6e\x20\x61\162\x63\x68\151\166\x65\40\x27" . $this->zipname . "\47\x20\x69\156\x20\142\151\x6e\x61\x72\x79\x20\162\x65\141\144\x20\155\x6f\x64\145");
        goto vwuXu;
        XQByK:
        if (!(($v_result = $this->privReadEndCentralDir($v_central_dir)) != 1)) {
            goto rJXRs;
        }
        goto mZdre;
        b0dP0:
        wZycO:
        goto y9nQ_;
        yaLio:
        return $v_result;
        goto DkBiF;
        Icv9G:
        if (!($i < $v_central_dir["\145\156\164\x72\x69\145\163"])) {
            goto G5Knh;
        }
        goto IEszu;
        wafmU:
        rJXRs:
        goto txBRu;
        WgTq2:
        goto UrkkZ;
        goto Rna0u;
        e9Ryp:
        $this->privSwapBackMagicQuotes();
        goto yaLio;
        ro2hF:
        $v_central_dir = array();
        goto XQByK;
        kzhw1:
        return $v_result;
        goto wafmU;
        MQcHY:
        $this->privCloseFd();
        goto e9Ryp;
        YRm4D:
        sSjsQ:
        goto ro2hF;
        txBRu:
        @rewind($this->zip_fd);
        goto falQj;
        falQj:
        if (!@fseek($this->zip_fd, $v_central_dir["\157\x66\x66\163\x65\164"])) {
            goto o7rH5;
        }
        goto Jsizn;
        mZdre:
        $this->privSwapBackMagicQuotes();
        goto kzhw1;
        MetSe:
        $v_header["\151\156\x64\145\x78"] = $i;
        goto lWFZG;
        UnwNo:
        return $v_result;
        goto g98KP;
        vwuXu:
        return PclZip::errorCode();
        goto YRm4D;
        kMwue:
        return PclZip::errorCode();
        goto FB71v;
        HWp3o:
        $this->privSwapBackMagicQuotes();
        goto UnwNo;
        g98KP:
        Q0eWW:
        goto MetSe;
        y9nQ_:
        $i++;
        goto WgTq2;
        u12Ld:
        $i = 0;
        goto jjDfB;
        Qp7eX:
        if (!(($this->zip_fd = @fopen($this->zipname, "\162\142")) == 0)) {
            goto sSjsQ;
        }
        goto a6tMa;
        jjDfB:
        UrkkZ:
        goto Icv9G;
        rlsxG:
        PclZip::privErrorLog(PCLZIP_ERR_INVALID_ARCHIVE_ZIP, "\x49\156\x76\x61\x6c\151\144\40\141\162\143\x68\151\x76\145\x20\163\x69\172\x65");
        goto kMwue;
        Rna0u:
        G5Knh:
        goto MQcHY;
        N07W2:
        $v_result = 1;
        goto Uj0ha;
        lWFZG:
        $this->privConvertHeader2FileInfo($v_header, $p_list[$i]);
        goto mBmPd;
        mBmPd:
        unset($v_header);
        goto b0dP0;
        a6tMa:
        $this->privSwapBackMagicQuotes();
        goto Z3po0;
        DkBiF:
    }
    public function privConvertHeader2FileInfo($p_header, &$p_info)
    {
        goto DP4TO;
        DP4TO:
        $v_result = 1;
        goto v8f7o;
        yh0nh:
        $p_info["\x73\x74\157\x72\x65\144\x5f\146\x69\x6c\x65\156\141\155\x65"] = $v_temp_path;
        goto bwbne;
        PW63m:
        $p_info["\x69\156\x64\x65\x78"] = $p_header["\151\x6e\144\x65\x78"];
        goto Vp9Nu;
        WV0ls:
        $v_temp_path = PclZipUtilPathReduction($p_header["\163\164\x6f\x72\145\144\x5f\146\151\x6c\x65\156\x61\155\x65"]);
        goto yh0nh;
        f5GDR:
        $p_info["\143\x6f\155\x70\162\x65\163\163\x65\x64\x5f\163\151\x7a\145"] = $p_header["\143\x6f\155\x70\162\x65\x73\x73\145\x64\x5f\163\x69\x7a\x65"];
        goto R5S4j;
        bwbne:
        $p_info["\x73\151\172\x65"] = $p_header["\x73\x69\172\145"];
        goto f5GDR;
        Qk53R:
        $p_info["\x66\x69\x6c\x65\156\x61\155\x65"] = $v_temp_path;
        goto WV0ls;
        e4GPK:
        return $v_result;
        goto klJdh;
        zxBei:
        $p_info["\x66\157\x6c\x64\x65\162"] = ($p_header["\x65\x78\x74\x65\x72\x6e\x61\x6c"] & 16) == 16;
        goto PW63m;
        tlNhe:
        $p_info["\143\157\x6d\x6d\145\156\x74"] = $p_header["\x63\157\155\155\x65\x6e\x74"];
        goto zxBei;
        R5S4j:
        $p_info["\155\x74\x69\155\145"] = $p_header["\x6d\x74\x69\155\145"];
        goto tlNhe;
        Y6hSR:
        $p_info["\143\x72\143"] = $p_header["\143\162\143"];
        goto e4GPK;
        Vp9Nu:
        $p_info["\163\164\141\164\165\x73"] = $p_header["\x73\x74\141\164\x75\x73"];
        goto Y6hSR;
        v8f7o:
        $v_temp_path = PclZipUtilPathReduction($p_header["\146\151\154\145\x6e\141\155\145"]);
        goto Qk53R;
        klJdh:
    }
    public function privExtractByRule(&$p_file_list, $p_path, $p_remove_path, $p_remove_all_path, &$p_options)
    {
        goto tbJlS;
        OfnIb:
        if (!($v_extract && ($v_header["\146\154\141\147"] & 1) == 1)) {
            goto O84Q4;
        }
        goto sGtLJ;
        ldHwz:
        if (isset($p_options[PCLZIP_OPT_EXTRACT_IN_OUTPUT]) && $p_options[PCLZIP_OPT_EXTRACT_IN_OUTPUT]) {
            goto rOoPr;
        }
        goto nX7wC;
        KeGCD:
        if (!($v_result1 == 2)) {
            goto ncCmE;
        }
        goto nYw6P;
        xklgZ:
        if (!(($v_result = $this->privConvertHeader2FileInfo($v_header, $p_file_list[$v_nb_extracted++])) != 1)) {
            goto WUHIC;
        }
        goto N9XGU;
        TqsVX:
        Vyr5t:
        goto SIK8O;
        bdfN0:
        wwhj3:
        goto FTTDl;
        zIl3M:
        wy5OW:
        goto fTsuy;
        cCk55:
        $v_result1 = $this->privExtractFileInOutput($v_header, $p_options);
        goto ffS2t;
        SlR1_:
        if ($p_options[PCLZIP_OPT_EXTRACT_AS_STRING]) {
            goto RvaQV;
        }
        goto ldHwz;
        DPSeA:
        pYvYV:
        goto MRU2x;
        aimaF:
        a4aHv:
        goto MxKUr;
        ZRYJe:
        $this->privSwapBackMagicQuotes();
        goto txFQP;
        tbJlS:
        $v_result = 1;
        goto XGiph;
        fqDqT:
        ojwQS:
        goto jVga1;
        coVzP:
        $v_nb_extracted++;
        goto jTgYJ;
        LREo2:
        fcsqZ:
        goto GL8_z;
        NDExK:
        if (!($j < sizeof($p_options[PCLZIP_OPT_BY_NAME]) && !$v_extract)) {
            goto Vyr5t;
        }
        goto EZitS;
        Jmphc:
        $v_extract = true;
        goto zbLyD;
        ppnQB:
        goto b44oo;
        goto sgLK2;
        I6DwW:
        goto a4aHv;
        goto A5NpK;
        wFdCA:
        $this->privCloseFd();
        goto ZRYJe;
        jVga1:
        $p_remove_path_size = strlen($p_remove_path);
        goto ZQrjS;
        EzvHk:
        $j_start = 0;
        goto lfhOh;
        wWkiH:
        JGXB7:
        goto xklgZ;
        gs2_E:
        CU81B:
        goto WjvWG;
        fcND5:
        uxBqL:
        goto Tp9ma;
        zbLyD:
        p8Gir:
        goto fbrtF;
        xphRn:
        IMtZ2:
        goto Ft1ge;
        AvqHw:
        $p_path = "\x2e\x2f" . $p_path;
        goto UsPcI;
        SS2IT:
        goto dqVXr;
        goto R82Tl;
        nYw6P:
        goto aRl9d;
        goto bqZOP;
        tj7sX:
        return $v_result;
        goto L3XTI;
        wBCed:
        DXNz0:
        goto F5a3o;
        sgLK2:
        Uf5BJ:
        goto IXxvB;
        zHoVV:
        if (!(isset($p_options[PCLZIP_OPT_STOP_ON_ERROR]) && $p_options[PCLZIP_OPT_STOP_ON_ERROR] === true)) {
            goto E6FbF;
        }
        goto YcUl3;
        UVUiM:
        goto dqVXr;
        goto gBxZw;
        Tp9ma:
        $j++;
        goto UkGG5;
        ZQz8v:
        PclZip::privErrorLog(PCLZIP_ERR_UNSUPPORTED_COMPRESSION, "\106\151\x6c\x65\x6e\141\x6d\145\40\47" . $v_header["\163\164\157\x72\145\144\x5f\x66\x69\154\x65\156\x61\x6d\145"] . "\47\x20\x69\x73\40" . "\x63\x6f\x6d\x70\162\x65\x73\x73\145\144\40\x62\x79\40\x61\156\x20\x75\x6e\x73\165\160\160\157\162\164\x65\x64\x20\143\x6f\x6d\160\x72\145\x73\x73\x69\x6f\x6e\40" . "\155\145\x74\x68\x6f\x64\x20\50" . $v_header["\x63\157\x6d\160\162\x65\x73\163\151\157\156"] . "\x29\40");
        goto pnhpF;
        MRU2x:
        $p_file_list[$v_nb_extracted]["\143\x6f\x6e\164\145\x6e\x74"] = $v_string;
        goto coVzP;
        MPeMb:
        uuyrU:
        goto bJOUa;
        B6PXQ:
        $this->privCloseFd();
        goto L224p;
        jTgYJ:
        if (!($v_result1 == 2)) {
            goto vt51j;
        }
        goto XzLPx;
        yn2iC:
        BSDFH:
        goto aaJaj;
        SIoaI:
        $v_result1 = $this->privExtractFileAsString($v_header, $v_string, $p_options);
        goto WSN0y;
        junsD:
        $v_central_dir = array();
        goto NUFZv;
        F0uCW:
        if (!($v_result != 1)) {
            goto gd8ta;
        }
        goto yLzie;
        zj8TF:
        $this->privSwapBackMagicQuotes();
        goto IAhfO;
        GL8_z:
        if (!$v_extract) {
            goto uuyrU;
        }
        goto PUdu2;
        go9uM:
        $v_extract = false;
        goto LREo2;
        NSU8L:
        return PclZip::errorCode();
        goto ahYxc;
        I2KGp:
        $this->privCloseFd();
        goto ojVfe;
        aaJaj:
        $v_header = array();
        goto Brzf6;
        egTWM:
        JHlzY:
        goto e301r;
        WSN0y:
        if (!($v_result1 < 1)) {
            goto DXNz0;
        }
        goto OzLUZ;
        NcT9y:
        goto XHnr5;
        goto ncYEu;
        YcUl3:
        $this->privSwapBackMagicQuotes();
        goto ZQz8v;
        EivYE:
        PclZip::privErrorLog(PCLZIP_ERR_INVALID_ARCHIVE_ZIP, "\111\156\166\x61\x6c\151\144\x20\x61\162\x63\150\151\x76\145\40\163\x69\x7a\x65");
        goto PxS6s;
        ncYEu:
        gzWmr:
        goto fcND5;
        C0eLp:
        $this->privCloseFd();
        goto TpkVR;
        Efr7J:
        O84Q4:
        goto FuNge;
        pObcP:
        if (!@fseek($this->zip_fd, $v_pos_entry)) {
            goto BSDFH;
        }
        goto Wn86W;
        PpcQf:
        $p_path = substr($p_path, 0, strlen($p_path) - 1);
        goto I6DwW;
        qgUCy:
        $this->privSwapBackMagicQuotes();
        goto aof3d;
        voa05:
        $this->privCloseFd();
        goto x2sEh;
        FVg1c:
        alANX:
        goto zyKJQ;
        SIK8O:
        goto b44oo;
        goto ovGei;
        BdPhI:
        bTQUK:
        goto KYZin;
        gBxZw:
        rOoPr:
        goto cCk55;
        Zn44r:
        return $v_result;
        goto rWi6A;
        cJ_vB:
        $this->privSwapBackMagicQuotes();
        goto ZngQm;
        yDKXi:
        gd8ta:
        goto go9uM;
        SXFsM:
        b44oo:
        goto hSEPk;
        LONQ2:
        $p_remove_path .= "\x2f";
        goto fqDqT;
        Kxh6n:
        iKAma:
        goto uyUDS;
        BmQT6:
        YNwEf:
        goto aCoXC;
        jvU2a:
        $v_nb_extracted = 0;
        goto FVg1c;
        WjvWG:
        $j = $j_start;
        goto BmQT6;
        dqtZ3:
        JP3yA:
        goto NDExK;
        Ek0nb:
        z5n6v:
        goto gnqZL;
        Ts95F:
        $v_extract = false;
        goto XFbuY;
        N9XGU:
        $this->privCloseFd();
        goto zj8TF;
        A5NpK:
        xYdRX:
        goto Ek0nb;
        RcGcO:
        $this->privSwapBackMagicQuotes();
        goto rrcAc;
        Ft1ge:
        if (!($i >= $p_options[PCLZIP_OPT_BY_INDEX][$j]["\x65\x6e\144"])) {
            goto wwhj3;
        }
        goto xZAnu;
        rrcAc:
        PclZip::privErrorLog(PCLZIP_ERR_UNSUPPORTED_ENCRYPTION, "\x55\156\163\x75\x70\x70\157\x72\x74\145\x64\40\145\156\x63\162\171\160\x74\151\157\x6e\x20\146\157\x72\x20" . "\40\x66\151\154\145\x6e\x61\155\x65\x20\x27" . $v_header["\163\x74\x6f\162\x65\144\137\x66\151\154\x65\x6e\x61\x6d\x65"] . "\x27");
        goto NSU8L;
        VEEFR:
        goto alANX;
        goto PY_A4;
        iM7WD:
        dqVXr:
        goto MPeMb;
        YXjTC:
        if (!($v_result1 == 2)) {
            goto F7GJW;
        }
        goto cuj3M;
        ojVfe:
        $this->privSwapBackMagicQuotes();
        goto rk7Vt;
        Brzf6:
        if (!(($v_result = $this->privReadCentralFileHeader($v_header)) != 1)) {
            goto bTQUK;
        }
        goto CE0eT;
        lfhOh:
        $i = 0;
        goto jvU2a;
        AJcxa:
        $this->privSwapBackMagicQuotes();
        goto SC4NF;
        hIuRy:
        F7GJW:
        goto iM7WD;
        aof3d:
        return $v_result;
        goto BdPhI;
        rk7Vt:
        return $v_result;
        goto DPSeA;
        pnhpF:
        return PclZip::errorCode();
        goto eaLk3;
        KYZin:
        $v_header["\151\156\144\145\170"] = $i;
        goto dV_us;
        PY_A4:
        aRl9d:
        goto voa05;
        TkuBD:
        vt51j:
        goto UVUiM;
        XzLPx:
        goto aRl9d;
        goto TkuBD;
        aCoXC:
        if (!($j < sizeof($p_options[PCLZIP_OPT_BY_INDEX]) && !$v_extract)) {
            goto XHnr5;
        }
        goto gcP_7;
        bqZOP:
        ncCmE:
        goto SS2IT;
        sGtLJ:
        $v_header["\163\x74\x61\164\165\163"] = "\165\x6e\163\x75\160\x70\157\162\164\x65\144\137\145\x6e\x63\x72\x79\x70\164\151\157\156";
        goto EnZdJ;
        gcP_7:
        if (!($i >= $p_options[PCLZIP_OPT_BY_INDEX][$j]["\163\164\x61\162\x74"] && $i <= $p_options[PCLZIP_OPT_BY_INDEX][$j]["\x65\x6e\144"])) {
            goto IMtZ2;
        }
        goto NQdeE;
        zyKJQ:
        if (!($i < $v_central_dir["\x65\x6e\x74\x72\x69\x65\x73"])) {
            goto aRl9d;
        }
        goto F6jVJ;
        gnqZL:
        if (!($p_remove_path != '' && substr($p_remove_path, -1) != "\x2f")) {
            goto ojwQS;
        }
        goto LONQ2;
        x6N4l:
        $v_pos_entry = $v_central_dir["\x6f\x66\x66\163\x65\x74"];
        goto EzvHk;
        ZngQm:
        return $v_result1;
        goto wBCed;
        eaLk3:
        E6FbF:
        goto VBAR9;
        XGiph:
        $this->privDisableMagicQuotes();
        goto PrptF;
        F5a3o:
        if (!(($v_result = $this->privConvertHeader2FileInfo($v_header, $p_file_list[$v_nb_extracted])) != 1)) {
            goto pYvYV;
        }
        goto I2KGp;
        NOtW4:
        $this->privSwapBackMagicQuotes();
        goto tj7sX;
        ahYxc:
        HF6X3:
        goto Efr7J;
        PUdu2:
        @rewind($this->zip_fd);
        goto tiVae;
        hSEPk:
        if (!($v_extract && ($v_header["\143\157\x6d\x70\162\145\163\163\151\x6f\x6e"] != 8 && $v_header["\143\x6f\x6d\160\x72\145\163\163\151\x6f\x6e"] != 0))) {
            goto DjpOw;
        }
        goto cjYoH;
        EZitS:
        if (substr($p_options[PCLZIP_OPT_BY_NAME][$j], -1) == "\x2f") {
            goto wy5OW;
        }
        goto n2Bkf;
        FKiBN:
        $this->privSwapBackMagicQuotes();
        goto EivYE;
        KBj2U:
        $v_result = $this->privConvertHeader2FileInfo($v_header, $p_file_list[$v_nb_extracted++]);
        goto F0uCW;
        CE0eT:
        $this->privCloseFd();
        goto qgUCy;
        Hy9t1:
        $this->privSwapBackMagicQuotes();
        goto ltcLO;
        T1WI_:
        XHnr5:
        goto SXFsM;
        B_NVP:
        $i++;
        goto VEEFR;
        ffS2t:
        if (!($v_result1 < 1)) {
            goto JGXB7;
        }
        goto C0eLp;
        YlWLz:
        return $v_result;
        goto yDKXi;
        tiVae:
        if (!@fseek($this->zip_fd, $v_header["\x6f\x66\146\163\x65\x74"])) {
            goto PkTpl;
        }
        goto B6PXQ;
        cuj3M:
        goto aRl9d;
        goto hIuRy;
        Uc2VH:
        $j++;
        goto r_nRC;
        FTTDl:
        if (!($p_options[PCLZIP_OPT_BY_INDEX][$j]["\163\164\141\x72\x74"] > $i)) {
            goto gzWmr;
        }
        goto NcT9y;
        L3XTI:
        m6D6i:
        goto KeGCD;
        OzLUZ:
        $this->privCloseFd();
        goto cJ_vB;
        xZAnu:
        $j_start = $j + 1;
        goto bdfN0;
        PrptF:
        if (!($p_path == '' || substr($p_path, 0, 1) != "\57" && substr($p_path, 0, 3) != "\56\x2e\x2f" && substr($p_path, 1, 2) != "\x3a\x2f")) {
            goto Fv43w;
        }
        goto AvqHw;
        IwHNE:
        if (isset($p_options[PCLZIP_OPT_BY_INDEX]) && $p_options[PCLZIP_OPT_BY_INDEX] != 0) {
            goto CU81B;
        }
        goto RkkLj;
        A3lg2:
        $this->privCloseFd();
        goto AJcxa;
        ovGei:
        TFh1v:
        goto QFLp8;
        PxS6s:
        return PclZip::errorCode();
        goto yn2iC;
        QJ_5m:
        $v_string = '';
        goto SIoaI;
        F6jVJ:
        @rewind($this->zip_fd);
        goto pObcP;
        oBfrq:
        $v_extract = true;
        goto kswJC;
        n2Bkf:
        if ($v_header["\163\x74\157\162\x65\x64\x5f\146\x69\x6c\145\x6e\141\x6d\x65"] == $p_options[PCLZIP_OPT_BY_NAME][$j]) {
            goto JHlzY;
        }
        goto cKC1j;
        UsPcI:
        Fv43w:
        goto f3yIr;
        TpkVR:
        $this->privSwapBackMagicQuotes();
        goto xLnnl;
        cjYoH:
        $v_header["\163\164\141\164\x75\x73"] = "\165\x6e\x73\x75\160\x70\x6f\162\x74\x65\x64\x5f\143\157\155\x70\162\145\x73\163\151\x6f\x6e";
        goto zHoVV;
        r_nRC:
        goto JP3yA;
        goto TqsVX;
        uyUDS:
        oto27:
        goto Uc2VH;
        OMhzl:
        PkTpl:
        goto SlR1_;
        NUFZv:
        if (!(($v_result = $this->privReadEndCentralDir($v_central_dir)) != 1)) {
            goto FKh9M;
        }
        goto wFdCA;
        txFQP:
        return $v_result;
        goto o_qko;
        o_qko:
        FKh9M:
        goto x6N4l;
        QFLp8:
        if (!preg_match($p_options[PCLZIP_OPT_BY_PREG], $v_header["\x73\164\x6f\x72\145\144\x5f\x66\x69\x6c\145\x6e\141\155\145"])) {
            goto p8Gir;
        }
        goto Jmphc;
        bio8G:
        goto iKAma;
        goto egTWM;
        xLnnl:
        return $v_result1;
        goto wWkiH;
        nX7wC:
        $v_result1 = $this->privExtractFile($v_header, $p_path, $p_remove_path, $p_remove_all_path, $p_options);
        goto w33Ru;
        lFHzq:
        if (isset($p_options[PCLZIP_OPT_BY_PREG]) && $p_options[PCLZIP_OPT_BY_PREG] != '') {
            goto TFh1v;
        }
        goto IwHNE;
        kswJC:
        dABUu:
        goto bio8G;
        dV_us:
        $v_pos_entry = ftell($this->zip_fd);
        goto Ts95F;
        NQdeE:
        $v_extract = true;
        goto xphRn;
        MxKUr:
        if (!(substr($p_path, -1) == "\x2f")) {
            goto xYdRX;
        }
        goto PpcQf;
        f3yIr:
        if (!($p_path != "\x2e\57" && $p_path != "\x2f")) {
            goto z5n6v;
        }
        goto aimaF;
        Fi4jr:
        u6TlD:
        goto pgsDS;
        ltcLO:
        return $v_result;
        goto sBy4h;
        EnZdJ:
        if (!(isset($p_options[PCLZIP_OPT_STOP_ON_ERROR]) && $p_options[PCLZIP_OPT_STOP_ON_ERROR] === true)) {
            goto HF6X3;
        }
        goto RcGcO;
        bJOUa:
        KyRfg:
        goto B_NVP;
        ZQrjS:
        if (!(($v_result = $this->privOpenFd("\x72\x62")) != 1)) {
            goto oBa9G;
        }
        goto Hy9t1;
        SC4NF:
        return $v_result1;
        goto Fi4jr;
        RkkLj:
        $v_extract = true;
        goto ppnQB;
        pgsDS:
        if (!(($v_result = $this->privConvertHeader2FileInfo($v_header, $p_file_list[$v_nb_extracted++])) != 1)) {
            goto m6D6i;
        }
        goto TRlKH;
        x2sEh:
        $this->privSwapBackMagicQuotes();
        goto Zn44r;
        Wn86W:
        $this->privCloseFd();
        goto FKiBN;
        FuNge:
        if (!($v_extract && $v_header["\163\x74\x61\164\165\x73"] != "\x6f\x6b")) {
            goto fcsqZ;
        }
        goto KBj2U;
        ZIdu_:
        PclZip::privErrorLog(PCLZIP_ERR_INVALID_ARCHIVE_ZIP, "\x49\x6e\x76\x61\154\151\x64\x20\141\x72\x63\150\151\x76\x65\40\163\151\x7a\x65");
        goto ANvGv;
        UkGG5:
        goto YNwEf;
        goto T1WI_;
        IAhfO:
        return $v_result;
        goto ZMwLL;
        TRlKH:
        $this->privCloseFd();
        goto NOtW4;
        cKC1j:
        goto iKAma;
        goto zIl3M;
        IXxvB:
        $j = 0;
        goto dqtZ3;
        L224p:
        $this->privSwapBackMagicQuotes();
        goto ZIdu_;
        yLzie:
        $this->privCloseFd();
        goto IEi_9;
        w33Ru:
        if (!($v_result1 < 1)) {
            goto u6TlD;
        }
        goto A3lg2;
        fTsuy:
        if (!(strlen($v_header["\x73\x74\x6f\x72\145\144\137\146\x69\154\145\x6e\141\155\145"]) > strlen($p_options[PCLZIP_OPT_BY_NAME][$j]) && substr($v_header["\x73\x74\157\162\145\x64\137\x66\151\154\x65\x6e\x61\155\x65"], 0, strlen($p_options[PCLZIP_OPT_BY_NAME][$j])) == $p_options[PCLZIP_OPT_BY_NAME][$j])) {
            goto dABUu;
        }
        goto oBfrq;
        IEi_9:
        $this->privSwapBackMagicQuotes();
        goto YlWLz;
        sBy4h:
        oBa9G:
        goto junsD;
        fbrtF:
        goto b44oo;
        goto gs2_E;
        VBAR9:
        DjpOw:
        goto OfnIb;
        R82Tl:
        RvaQV:
        goto QJ_5m;
        e301r:
        $v_extract = true;
        goto Kxh6n;
        XFbuY:
        if (isset($p_options[PCLZIP_OPT_BY_NAME]) && $p_options[PCLZIP_OPT_BY_NAME] != 0) {
            goto Uf5BJ;
        }
        goto lFHzq;
        ANvGv:
        return PclZip::errorCode();
        goto OMhzl;
        ZMwLL:
        WUHIC:
        goto YXjTC;
        rWi6A:
    }
    public function privExtractFile(&$p_entry, $p_path, $p_remove_path, $p_remove_all_path, &$p_options)
    {
        goto cF9tC;
        GNivq:
        oZw4S:
        goto vNTER;
        pth3J:
        $v_local_header = array();
        goto TMJL_;
        vWME3:
        goto B3N7X;
        goto arwa0;
        XYmmq:
        $v_inclusion = PclZipUtilPathInclusion($p_options[PCLZIP_OPT_EXTRACT_DIR_RESTRICTION], $p_entry["\x66\151\x6c\145\x6e\141\155\145"]);
        goto urXzu;
        SJ2kT:
        if (filemtime($p_entry["\x66\151\x6c\x65\x6e\141\x6d\145"]) > $p_entry["\x6d\164\151\x6d\x65"]) {
            goto IASMd;
        }
        goto bo0Gw;
        cdhw2:
        $v_size -= $v_read_size;
        goto VeOeA;
        n4IxC:
        return $v_result;
        goto cBeoF;
        F_cmx:
        $v_size = $p_entry["\143\157\x6d\160\x72\145\x73\163\145\144\137\x73\x69\172\145"];
        goto rnoAE;
        AighZ:
        return $v_result;
        goto DBYzA;
        uzaVv:
        Doa6k:
        goto VJ538;
        bo0Gw:
        goto Z2gPB;
        goto xcEh_;
        arwa0:
        QeTgA:
        goto cYUHx;
        u3XVP:
        $p_entry["\146\x69\x6c\x65\156\141\155\x65"] = $p_path . "\57" . $p_entry["\x66\151\154\145\x6e\x61\x6d\145"];
        goto tkKmm;
        DM_Jk:
        if (!strstr($p_entry["\146\151\x6c\145\156\x61\x6d\x65"], "\57")) {
            goto Doa6k;
        }
        goto TIvty;
        DnTIT:
        OLHBw:
        goto JCnMf;
        S1WPV:
        if (!(($v_result = $this->privDirCheck($v_dir_to_check, ($p_entry["\x65\x78\x74\145\x72\x6e\141\x6c"] & 16) == 16)) != 1)) {
            goto oeIGO;
        }
        goto mWJOU;
        FZdw3:
        if (!(($p_entry["\146\154\x61\147"] & 1) == 1)) {
            goto hXaGp;
        }
        goto HCDWQ;
        Rt7E6:
        $p_entry["\163\x74\x61\164\165\x73"] = "\x77\162\x69\164\145\x5f\145\162\x72\157\x72";
        goto n4IxC;
        WRpFE:
        touch($p_entry["\x66\151\x6c\145\156\x61\155\x65"], $p_entry["\x6d\x74\151\155\x65"]);
        goto FOC_3;
        TMJL_:
        $this->privConvertHeader2FileInfo($p_entry, $v_local_header);
        goto Nodny;
        xMheK:
        if ($p_remove_path != '') {
            goto V3qsb;
        }
        goto gu338;
        rhxGK:
        $v_local_header = array();
        goto wtGjB;
        rLTov:
        PclZip::privErrorLog(PCLZIP_ERR_WRITE_OPEN_FAIL, "\106\x69\154\145\156\141\155\x65\x20\x27" . $p_entry["\x66\151\x6c\145\x6e\141\x6d\145"] . "\47\40\x65\x78\151\163\x74\163\40" . "\141\x6e\144\x20\x69\163\40\x77\162\x69\x74\145\x20\160\x72\157\164\145\x63\x74\145\144");
        goto n5ZQO;
        i2CFd:
        $p_entry["\163\x74\141\x74\165\163"] = "\146\151\154\164\145\x72\145\x64";
        goto DsppD;
        mGgJA:
        nfvuF:
        goto DnTIT;
        WmVjt:
        @fwrite($v_dest_file, $v_buffer, $v_read_size);
        goto cdhw2;
        A9Reo:
        goto Z2gPB;
        goto JqHQ5;
        VJ538:
        $v_dir_to_check = '';
        goto rPKxK;
        BzymB:
        lzy9m:
        goto X7Fpt;
        DQMXJ:
        $v_file_content = @gzinflate($v_buffer);
        goto uHICO;
        zygPI:
        $p_remove_path_size = strlen($p_remove_path);
        goto ibWbd;
        ZOXEu:
        if ($p_entry["\x63\x6f\x6d\x70\x72\x65\x73\x73\x69\157\x6e"] == 0) {
            goto tDMw0;
        }
        goto FZdw3;
        XWJme:
        BWXF8:
        goto aU4fA;
        n2hu2:
        $v_result = PCLZIP_ERR_USER_ABORTED;
        goto q4jG9;
        q4jG9:
        p52M5:
        goto tVtI2;
        LX54b:
        MLSNK:
        goto GNivq;
        Z7P74:
        $p_entry["\x73\x74\x61\x74\x75\x73"] = "\x73\153\x69\160\160\x65\144";
        goto nbJd2;
        ZHReq:
        if (!isset($p_options[PCLZIP_OPT_SET_CHMOD])) {
            goto ySTMb;
        }
        goto mrrTF;
        J2io8:
        oi3aW:
        goto A9Reo;
        Nodny:
        $v_result = $p_options[PCLZIP_CB_POST_EXTRACT](PCLZIP_CB_POST_EXTRACT, $v_local_header);
        goto fXxd0;
        cBeoF:
        Elq21:
        goto oUMpb;
        e13lR:
        kvCFS:
        goto HuBZY;
        Hg_AI:
        WV5mk:
        goto x8ivD;
        MfZYQ:
        VCFzv:
        goto N0No_;
        X7Fpt:
        UQD56:
        goto czw3B;
        EYp1o:
        PclZip::privErrorLog(PCLZIP_ERR_ALREADY_A_DIRECTORY, "\106\x69\x6c\145\x6e\x61\155\145\40\47" . $p_entry["\146\151\x6c\145\156\x61\155\145"] . "\x27\40\151\x73\40" . "\x61\154\162\145\x61\144\171\x20\165\163\145\x64\x20\142\171\40\x61\156\x20\145\170\x69\163\x74\x69\156\x67\40\x64\151\162\145\143\164\157\x72\x79");
        goto i2_h6;
        ooSqi:
        if (!(($v_dest_file = @fopen($p_entry["\x66\x69\x6c\145\156\141\x6d\145"], "\167\142")) == 0)) {
            goto Elq21;
        }
        goto Rt7E6;
        n6IxG:
        PclZip::privErrorLog(PCLZIP_ERR_WRITE_OPEN_FAIL, "\x4e\x65\x77\x65\162\x20\166\145\x72\163\151\157\156\40\157\146\x20\47" . $p_entry["\146\x69\x6c\145\x6e\x61\155\145"] . "\47\x20\145\x78\x69\x73\x74\x73\x20" . "\141\156\x64\40\x6f\x70\164\x69\157\156\x20\120\103\x4c\132\111\x50\137\117\x50\x54\x5f\x52\x45\120\x4c\x41\103\105\x5f\x4e\x45\x57\105\x52\40\151\163\x20\x6e\157\164\40\x73\145\154\x65\143\164\x65\x64");
        goto SFPFg;
        cT5l0:
        mYTz0:
        goto Bu3zx;
        aWBgS:
        return $v_result;
        goto stKqZ;
        FMnN7:
        if (!(($v_result = $this->privReadFileHeader($v_header)) != 1)) {
            goto EpFC6;
        }
        goto oI8RK;
        Z2zk6:
        iymuH:
        goto vecQb;
        mrrTF:
        @chmod($p_entry["\146\151\x6c\145\156\141\x6d\x65"], $p_options[PCLZIP_OPT_SET_CHMOD]);
        goto hunNg;
        x7xyU:
        if ($p_remove_all_path == true) {
            goto qgJld;
        }
        goto xMheK;
        czw3B:
        return $v_result;
        goto D6V8I;
        OAWUR:
        $p_entry["\146\151\154\x65\x6e\x61\x6d\x65"] = basename($p_entry["\146\151\154\145\x6e\141\x6d\x65"]);
        goto mRgys;
        rPKxK:
        B3N7X:
        goto S1WPV;
        qUgOn:
        oeIGO:
        goto rIGar;
        jSH7_:
        $p_entry["\163\164\x61\164\165\x73"] = "\167\x72\151\164\x65\x5f\145\162\162\x6f\x72";
        goto oXvvn;
        yPNlq:
        goto UQD56;
        goto bWXjH;
        xcEh_:
        G0u8g:
        goto twlSJ;
        dyTyw:
        Z2gPB:
        goto LX54b;
        urXzu:
        if (!($v_inclusion == 0)) {
            goto nfvuF;
        }
        goto TPZ0V;
        hs0Vl:
        return PclZip::errorCode();
        goto zPZE9;
        pZxss:
        if (!is_writeable($p_entry["\x66\x69\x6c\x65\x6e\141\x6d\145"])) {
            goto V1NbM;
        }
        goto SJ2kT;
        mWJOU:
        $p_entry["\x73\x74\x61\x74\165\x73"] = "\x70\141\164\150\x5f\x63\x72\145\141\164\151\x6f\x6e\x5f\x66\x61\151\154";
        goto If8QN;
        NFxoA:
        uJ9e4:
        goto F_cmx;
        SFPFg:
        return PclZip::errorCode();
        goto cT5l0;
        i0bpp:
        if (file_exists($p_entry["\x66\151\x6c\x65\156\141\x6d\145"])) {
            goto WV5mk;
        }
        goto JZLOo;
        kYQeq:
        Z1X2z:
        goto KBaGC;
        KcjRL:
        axa58:
        goto gVg4i;
        hunNg:
        ySTMb:
        goto OLfuQ;
        LcWxZ:
        if (isset($p_options[PCLZIP_OPT_REPLACE_NEWER]) && $p_options[PCLZIP_OPT_REPLACE_NEWER] === true) {
            goto Z1X2z;
        }
        goto dAhJV;
        tkKmm:
        ac4hk:
        goto OrMMj;
        i2_h6:
        return PclZip::errorCode();
        goto J2io8;
        DsppD:
        return $v_result;
        goto ImeLk;
        cJt45:
        if (!($v_size != 0)) {
            goto D9OCv;
        }
        goto YkQEf;
        gu338:
        goto vx1xP;
        goto jtrBX;
        oI8RK:
        return $v_result;
        goto TwXSc;
        bnOzp:
        D9OCv:
        goto acceB;
        oUMpb:
        @fwrite($v_dest_file, $v_file_content, $p_entry["\163\151\172\x65"]);
        goto gcRyO;
        hIPiF:
        if (!(isset($p_options[PCLZIP_OPT_STOP_ON_ERROR]) && $p_options[PCLZIP_OPT_STOP_ON_ERROR] === true)) {
            goto oi3aW;
        }
        goto EYp1o;
        JCnMf:
        if (!isset($p_options[PCLZIP_CB_PRE_EXTRACT])) {
            goto VCFzv;
        }
        goto rhxGK;
        oXvvn:
        return $v_result;
        goto NFxoA;
        FH8_W:
        goto Z2gPB;
        goto W3IZr;
        FOC_3:
        Fx1oO:
        goto ZHReq;
        TwXSc:
        EpFC6:
        goto zemoY;
        aU4fA:
        @touch($p_entry["\x66\151\x6c\x65\x6e\x61\155\x65"], $p_entry["\155\x74\151\x6d\145"]);
        goto hHS9v;
        ibWbd:
        if (!(substr($p_entry["\146\x69\x6c\145\x6e\x61\x6d\145"], 0, $p_remove_path_size) == $p_remove_path)) {
            goto kvCFS;
        }
        goto OzREZ;
        wSqWa:
        $p_entry["\x73\164\141\x74\165\x73"] = "\x77\x72\151\164\x65\x5f\x70\162\157\x74\145\143\x74\x65\144";
        goto BlYBV;
        bWXjH:
        CjqYY:
        goto pth3J;
        twlSJ:
        $p_entry["\163\x74\x61\164\165\x73"] = "\x61\154\x72\145\141\x64\171\137\x61\137\144\151\x72\x65\x63\164\157\162\x79";
        goto hIPiF;
        jTbSy:
        goto B3N7X;
        goto uzaVv;
        N0No_:
        if (!($p_entry["\x73\164\x61\164\x75\x73"] == "\157\153")) {
            goto oZw4S;
        }
        goto i0bpp;
        a6qgz:
        $p_entry["\x73\164\141\x74\165\163"] = "\141\142\157\162\164\145\144";
        goto n2hu2;
        bDWG2:
        if (!($p_path != '')) {
            goto ac4hk;
        }
        goto u3XVP;
        HCDWQ:
        PclZip::privErrorLog(PCLZIP_ERR_UNSUPPORTED_ENCRYPTION, "\x46\x69\154\145\40\x27" . $p_entry["\146\x69\x6c\145\156\x61\x6d\145"] . "\x27\40\151\x73\40\145\x6e\x63\162\171\160\164\145\x64\x2e\40\x45\x6e\x63\x72\171\160\x74\x65\x64\x20\x66\x69\x6c\x65\163\x20\x61\x72\145\40\x6e\x6f\x74\40\x73\165\160\x70\157\162\x74\145\x64\56");
        goto hs0Vl;
        tVtI2:
        $p_entry["\146\151\154\x65\156\x61\155\x65"] = $v_local_header["\146\151\154\x65\156\x61\x6d\x65"];
        goto MfZYQ;
        n5ZQO:
        return PclZip::errorCode();
        goto m2RSd;
        G20KA:
        tDMw0:
        goto XEvRs;
        Hbxvy:
        goto UQD56;
        goto XgGQ0;
        FDCNZ:
        $p_entry["\163\x74\141\164\165\x73"] = "\x66\x69\154\x74\x65\162\145\144";
        goto SC95k;
        nbJd2:
        $v_result = 1;
        goto mJpbO;
        JfD47:
        lKPAM:
        goto zygPI;
        VlnrD:
        $v_result = $p_options[PCLZIP_CB_PRE_EXTRACT](PCLZIP_CB_PRE_EXTRACT, $v_local_header);
        goto X7GOL;
        KBaGC:
        EnAtX:
        goto dyTyw;
        vecQb:
        if ($p_entry["\x73\164\141\x74\165\163"] == "\141\x62\157\x72\x74\x65\144") {
            goto MyKF3;
        }
        goto xv6NE;
        If8QN:
        $v_result = 1;
        goto qUgOn;
        c3p55:
        return PclZip::errorCode();
        goto mGgJA;
        XEvRs:
        if (!(($v_dest_file = @fopen($p_entry["\146\x69\x6c\x65\156\x61\155\145"], "\167\x62")) == 0)) {
            goto uJ9e4;
        }
        goto jSH7_;
        dqs0p:
        $v_buffer = @fread($this->zip_fd, $p_entry["\x63\157\155\x70\x72\x65\x73\163\145\144\137\x73\x69\172\145"]);
        goto DQMXJ;
        xKn49:
        if (!($v_file_content === false)) {
            goto OHBiq;
        }
        goto ST_1m;
        kkGIR:
        if (!(PclZipUtilPathInclusion($p_remove_path, $p_entry["\146\151\154\145\156\x61\x6d\145"]) == 2)) {
            goto lKPAM;
        }
        goto FDCNZ;
        JqHQ5:
        V1NbM:
        goto wSqWa;
        zsWOh:
        if (!(isset($p_options[PCLZIP_OPT_STOP_ON_ERROR]) && $p_options[PCLZIP_OPT_STOP_ON_ERROR] === true)) {
            goto mYTz0;
        }
        goto n6IxG;
        gcRyO:
        unset($v_file_content);
        goto Adm6W;
        cYUHx:
        $v_dir_to_check = $p_entry["\146\x69\x6c\145\156\x61\x6d\x65"];
        goto jTbSy;
        RG6Ai:
        V3qsb:
        goto kkGIR;
        m2RSd:
        KFSr_:
        goto FH8_W;
        stKqZ:
        s5FYR:
        goto XWJme;
        TPZ0V:
        PclZip::privErrorLog(PCLZIP_ERR_DIRECTORY_RESTRICTION, "\x46\x69\x6c\x65\156\x61\155\145\x20\x27" . $p_entry["\x66\x69\x6c\x65\x6e\x61\x6d\145"] . "\47\x20\151\163\x20" . "\x6f\x75\x74\x73\x69\144\x65\40\120\103\114\132\111\x50\x5f\x4f\120\x54\x5f\x45\130\x54\x52\x41\103\124\x5f\104\111\x52\x5f\122\x45\123\124\122\x49\x43\124\x49\x4f\116");
        goto c3p55;
        Wc7iA:
        $v_result = PCLZIP_ERR_USER_ABORTED;
        goto BzymB;
        k5zcm:
        if (!($v_result < PCLZIP_ERR_NO_ERROR)) {
            goto s5FYR;
        }
        goto aWBgS;
        mJpbO:
        r4Gl1:
        goto Xu8JJ;
        X7GOL:
        if (!($v_result == 0)) {
            goto r4Gl1;
        }
        goto Z7P74;
        IvDIX:
        $p_entry["\x73\x74\x61\164\165\x73"] = "\163\x6b\x69\160\160\145\x64";
        goto yPNlq;
        TIvty:
        $v_dir_to_check = dirname($p_entry["\146\x69\154\x65\x6e\141\155\x65"]);
        goto vWME3;
        DBYzA:
        OHBiq:
        goto ooSqi;
        HuBZY:
        vx1xP:
        goto bDWG2;
        W3IZr:
        IASMd:
        goto LcWxZ;
        Adm6W:
        @fclose($v_dest_file);
        goto aY5tk;
        ImeLk:
        fKwNe:
        goto OAWUR;
        HbJO4:
        if (!isset($p_options[PCLZIP_OPT_TEMP_FILE_OFF]) && (isset($p_options[PCLZIP_OPT_TEMP_FILE_ON]) || isset($p_options[PCLZIP_OPT_TEMP_FILE_THRESHOLD]) && $p_options[PCLZIP_OPT_TEMP_FILE_THRESHOLD] <= $p_entry["\x73\x69\x7a\145"])) {
            goto axa58;
        }
        goto dqs0p;
        XgGQ0:
        MyKF3:
        goto IvDIX;
        jtrBX:
        qgJld:
        goto b2bP3;
        zPZE9:
        hXaGp:
        goto HbJO4;
        BlYBV:
        if (!(isset($p_options[PCLZIP_OPT_STOP_ON_ERROR]) && $p_options[PCLZIP_OPT_STOP_ON_ERROR] === true)) {
            goto KFSr_;
        }
        goto rLTov;
        cF9tC:
        $v_result = 1;
        goto FMnN7;
        dAhJV:
        $p_entry["\163\x74\x61\x74\x75\163"] = "\156\145\167\145\162\x5f\145\170\x69\x73\164";
        goto zsWOh;
        Xu8JJ:
        if (!($v_result == 2)) {
            goto p52M5;
        }
        goto a6qgz;
        rnoAE:
        KoUnu:
        goto cJt45;
        wtGjB:
        $this->privConvertHeader2FileInfo($p_entry, $v_local_header);
        goto VlnrD;
        acceB:
        fclose($v_dest_file);
        goto WRpFE;
        vNTER:
        if (!($p_entry["\x73\x74\141\x74\x75\163"] == "\157\153")) {
            goto iymuH;
        }
        goto EMKe5;
        OrMMj:
        if (!isset($p_options[PCLZIP_OPT_EXTRACT_DIR_RESTRICTION])) {
            goto OLHBw;
        }
        goto XYmmq;
        Bu3zx:
        goto EnAtX;
        goto kYQeq;
        ST_1m:
        $p_entry["\x73\x74\141\x74\x75\x73"] = "\145\x72\x72\157\x72";
        goto AighZ;
        fXxd0:
        if (!($v_result == 2)) {
            goto lzy9m;
        }
        goto Wc7iA;
        hHS9v:
        goto Fx1oO;
        goto G20KA;
        EMKe5:
        if (($p_entry["\145\170\164\x65\x72\x6e\141\x6c"] & 16) == 16) {
            goto LZ0QU;
        }
        goto ZOXEu;
        SC95k:
        return $v_result;
        goto JfD47;
        tzXh6:
        $v_buffer = @fread($this->zip_fd, $v_read_size);
        goto WmVjt;
        uHICO:
        unset($v_buffer);
        goto xKn49;
        JZLOo:
        if (($p_entry["\145\x78\164\145\162\156\x61\x6c"] & 16) == 16 || substr($p_entry["\146\151\x6c\x65\x6e\x61\x6d\x65"], -1) == "\x2f") {
            goto QeTgA;
        }
        goto DM_Jk;
        VeOeA:
        goto KoUnu;
        goto bnOzp;
        x8ivD:
        if (is_dir($p_entry["\x66\x69\154\145\156\x61\155\x65"])) {
            goto G0u8g;
        }
        goto pZxss;
        gVg4i:
        $v_result = $this->privExtractFileUsingTempFile($p_entry, $p_options);
        goto k5zcm;
        aY5tk:
        goto BWXF8;
        goto KcjRL;
        xv6NE:
        if (isset($p_options[PCLZIP_CB_POST_EXTRACT])) {
            goto CjqYY;
        }
        goto Hbxvy;
        mRgys:
        goto vx1xP;
        goto RG6Ai;
        YkQEf:
        $v_read_size = $v_size < PCLZIP_READ_BLOCK_SIZE ? $v_size : PCLZIP_READ_BLOCK_SIZE;
        goto tzXh6;
        rIGar:
        goto MLSNK;
        goto Hg_AI;
        mqhwX:
        KBtru:
        goto x7xyU;
        zemoY:
        if (!($this->privCheckFileHeaders($v_header, $p_entry) != 1)) {
            goto KBtru;
        }
        goto mqhwX;
        OLfuQ:
        LZ0QU:
        goto Z2zk6;
        b2bP3:
        if (!(($p_entry["\145\170\164\x65\x72\x6e\x61\x6c"] & 16) == 16)) {
            goto fKwNe;
        }
        goto i2CFd;
        OzREZ:
        $p_entry["\146\x69\x6c\145\156\141\x6d\145"] = substr($p_entry["\146\151\x6c\145\156\141\155\x65"], $p_remove_path_size);
        goto e13lR;
        D6V8I:
    }
    public function privExtractFileUsingTempFile(&$p_entry, &$p_options)
    {
        goto UsInp;
        FXxIX:
        @fwrite($v_dest_file, $v_buffer, $v_read_size);
        goto vIGSJ;
        HZ5kA:
        PclZip::privErrorLog(PCLZIP_ERR_READ_OPEN_FAIL, "\x55\x6e\x61\142\x6c\145\40\164\157\x20\x6f\160\x65\156\x20\x74\145\155\x70\157\x72\x61\x72\171\x20\146\x69\x6c\x65\40\47" . $v_gzip_temp_name . "\x27\x20\x69\x6e\x20\142\151\156\x61\x72\x79\x20\162\x65\x61\x64\40\155\x6f\144\145");
        goto MdGdI;
        FG3q2:
        if (!(($v_src_file = @gzopen($v_gzip_temp_name, "\162\142")) == 0)) {
            goto toxps;
        }
        goto cBhFu;
        UsInp:
        $v_result = 1;
        goto AUmSn;
        MdGdI:
        return PclZip::errorCode();
        goto KO_Ut;
        NGdrP:
        return $v_result;
        goto UNs_z;
        U6x2_:
        PclZip::privErrorLog(PCLZIP_ERR_WRITE_OPEN_FAIL, "\125\156\x61\x62\154\x65\40\164\157\40\157\x70\x65\x6e\40\x74\x65\x6d\x70\x6f\162\x61\162\171\40\146\151\154\x65\x20\47" . $v_gzip_temp_name . "\x27\40\151\x6e\40\x62\x69\x6e\141\x72\x79\40\167\x72\151\x74\145\40\155\157\144\145");
        goto nLjMy;
        tdRB0:
        @fwrite($v_dest_file, $v_binary_data, 10);
        goto CGrVY;
        AUmSn:
        $v_gzip_temp_name = PCLZIP_TEMPORARY_DIR . uniqid("\160\x63\154\172\151\x70\55") . "\x2e\x67\172";
        goto blD78;
        gtDik:
        $v_read_size = $v_size < PCLZIP_READ_BLOCK_SIZE ? $v_size : PCLZIP_READ_BLOCK_SIZE;
        goto Uxeii;
        dkBuX:
        $v_buffer = @fread($this->zip_fd, $v_read_size);
        goto FXxIX;
        cps_1:
        dCUZs:
        goto j20gL;
        g1u0d:
        @fclose($v_dest_file);
        goto IvIwo;
        Z7XKg:
        $p_entry["\x73\x74\x61\164\x75\163"] = "\x77\x72\151\164\145\137\145\x72\x72\157\x72";
        goto FtjVC;
        LmsFp:
        @fclose($v_dest_file);
        goto BG7Gc;
        FtjVC:
        return $v_result;
        goto Zr4uM;
        MLDKd:
        @fwrite($v_dest_file, $v_binary_data, 8);
        goto g1u0d;
        fLTCH:
        $v_binary_data = pack("\x56\x56", $p_entry["\x63\162\x63"], $p_entry["\x73\x69\172\145"]);
        goto MLDKd;
        NIv3J:
        if (!($v_size != 0)) {
            goto WknCK;
        }
        goto dVBHu;
        dVBHu:
        $v_read_size = $v_size < PCLZIP_READ_BLOCK_SIZE ? $v_size : PCLZIP_READ_BLOCK_SIZE;
        goto dkBuX;
        XCbRI:
        $v_size -= $v_read_size;
        goto qAnFW;
        U3ya6:
        $v_binary_data = pack("\x76\141\61\141\x31\x56\x61\61\x61\x31", 35615, Chr($p_entry["\143\157\155\x70\162\145\163\163\151\x6f\156"]), Chr(0), time(), Chr(0), Chr(3));
        goto tdRB0;
        km66o:
        WknCK:
        goto fLTCH;
        kbl_I:
        ASzIZ:
        goto NIv3J;
        j20gL:
        if (!($v_size != 0)) {
            goto QdDyH;
        }
        goto gtDik;
        BG7Gc:
        @gzclose($v_src_file);
        goto kPDkE;
        cBhFu:
        @fclose($v_dest_file);
        goto r5X_3;
        im9x9:
        $v_size = $p_entry["\x73\151\172\145"];
        goto cps_1;
        KO_Ut:
        toxps:
        goto im9x9;
        Zr4uM:
        f_MuD:
        goto FG3q2;
        vIGSJ:
        $v_size -= $v_read_size;
        goto aLly2;
        blD78:
        if (!(($v_dest_file = @fopen($v_gzip_temp_name, "\x77\142")) == 0)) {
            goto OwGHD;
        }
        goto Q7eDd;
        Q7eDd:
        fclose($v_file);
        goto U6x2_;
        CGrVY:
        $v_size = $p_entry["\143\157\x6d\x70\162\x65\x73\x73\145\144\x5f\163\151\172\145"];
        goto kbl_I;
        aLly2:
        goto ASzIZ;
        goto km66o;
        r5X_3:
        $p_entry["\x73\x74\141\164\165\x73"] = "\x72\x65\x61\144\137\145\162\x72\157\x72";
        goto HZ5kA;
        oBg_N:
        OwGHD:
        goto U3ya6;
        kPDkE:
        @unlink($v_gzip_temp_name);
        goto NGdrP;
        Uxeii:
        $v_buffer = @gzread($v_src_file, $v_read_size);
        goto Mpweb;
        Mpweb:
        @fwrite($v_dest_file, $v_buffer, $v_read_size);
        goto XCbRI;
        nLjMy:
        return PclZip::errorCode();
        goto oBg_N;
        IvIwo:
        if (!(($v_dest_file = @fopen($p_entry["\146\151\x6c\x65\x6e\x61\155\145"], "\167\142")) == 0)) {
            goto f_MuD;
        }
        goto Z7XKg;
        VgI1E:
        QdDyH:
        goto LmsFp;
        qAnFW:
        goto dCUZs;
        goto VgI1E;
        UNs_z:
    }
    public function privExtractFileInOutput(&$p_entry, &$p_options)
    {
        goto qisuI;
        V55e_:
        okS7q:
        goto AyLYf;
        UOavk:
        $p_entry["\x73\x74\x61\x74\165\163"] = "\x73\x6b\151\x70\x70\x65\144";
        goto UKN0E;
        si7jW:
        $p_entry["\163\x74\141\x74\165\163"] = "\x61\x62\x6f\162\164\x65\x64";
        goto pcfJw;
        liAyP:
        $v_buffer = @fread($this->zip_fd, $p_entry["\143\x6f\x6d\x70\162\x65\x73\x73\x65\x64\x5f\163\151\x7a\x65"]);
        goto oQ2dC;
        w7Qin:
        if ($p_entry["\x73\x74\141\164\165\x73"] == "\141\x62\157\x72\164\x65\x64") {
            goto Jeoo8;
        }
        goto x13sJ;
        D8kwK:
        echo $v_file_content;
        goto ZosEW;
        for33:
        $this->privConvertHeader2FileInfo($p_entry, $v_local_header);
        goto KLG_D;
        ERaI5:
        return $v_result;
        goto gpT25;
        FkGN8:
        goto dutY8;
        goto Sq9_u;
        vRsiN:
        dutY8:
        goto nfIAv;
        uufO6:
        $this->privConvertHeader2FileInfo($p_entry, $v_local_header);
        goto oOUCz;
        qisuI:
        $v_result = 1;
        goto yzGLs;
        bjm7Q:
        if (($p_entry["\x65\170\x74\145\x72\156\141\x6c"] & 16) == 16) {
            goto wGICy;
        }
        goto ltn5k;
        afIkW:
        $p_entry["\146\151\x6c\x65\x6e\x61\x6d\145"] = $v_local_header["\146\x69\154\145\156\141\x6d\x65"];
        goto w2MPZ;
        y1AsJ:
        Dpb3O:
        goto w7Qin;
        vDw8P:
        $p_entry["\163\164\141\x74\165\163"] = "\x73\153\151\160\x70\x65\x64";
        goto WVsbe;
        D4ujK:
        if (!($p_entry["\x73\164\x61\164\x75\163"] == "\x6f\x6b")) {
            goto Dpb3O;
        }
        goto bjm7Q;
        yzGLs:
        if (!(($v_result = $this->privReadFileHeader($v_header)) != 1)) {
            goto shvti;
        }
        goto ERaI5;
        NlNYX:
        if (!($v_result == 2)) {
            goto HYzTT;
        }
        goto si7jW;
        QfHZO:
        if (!($this->privCheckFileHeaders($v_header, $p_entry) != 1)) {
            goto okS7q;
        }
        goto V55e_;
        RfUz1:
        unset($v_buffer);
        goto D8kwK;
        nfIAv:
        return $v_result;
        goto vt1hE;
        KLG_D:
        $v_result = $p_options[PCLZIP_CB_PRE_EXTRACT](PCLZIP_CB_PRE_EXTRACT, $v_local_header);
        goto k7q5H;
        qCG_Y:
        echo $v_buffer;
        goto ZqctI;
        Sq9_u:
        Jeoo8:
        goto UOavk;
        oQ2dC:
        $v_file_content = gzinflate($v_buffer);
        goto RfUz1;
        ZqctI:
        unset($v_buffer);
        goto UoDe4;
        weJX2:
        ZnmFm:
        goto NlNYX;
        w2MPZ:
        Q2Uco:
        goto D4ujK;
        IoALG:
        $v_buffer = @fread($this->zip_fd, $p_entry["\x63\x6f\155\x70\162\x65\x73\x73\x65\144\x5f\163\x69\x7a\145"]);
        goto qCG_Y;
        HMS0X:
        if (!($v_result == 2)) {
            goto JmFo6;
        }
        goto bTizW;
        iWOaR:
        wGICy:
        goto y1AsJ;
        RsBdu:
        goto u0mHX;
        goto bk81x;
        anSy_:
        $v_local_header = array();
        goto for33;
        pcfJw:
        $v_result = PCLZIP_ERR_USER_ABORTED;
        goto nj7ea;
        k7q5H:
        if (!($v_result == 0)) {
            goto ZnmFm;
        }
        goto vDw8P;
        gpT25:
        shvti:
        goto QfHZO;
        x13sJ:
        if (isset($p_options[PCLZIP_CB_POST_EXTRACT])) {
            goto EwGAe;
        }
        goto FkGN8;
        AyLYf:
        if (!isset($p_options[PCLZIP_CB_PRE_EXTRACT])) {
            goto Q2Uco;
        }
        goto anSy_;
        bTizW:
        $v_result = PCLZIP_ERR_USER_ABORTED;
        goto n3gwS;
        UoDe4:
        u0mHX:
        goto iWOaR;
        E8GgN:
        $v_local_header = array();
        goto uufO6;
        WVsbe:
        $v_result = 1;
        goto weJX2;
        nj7ea:
        HYzTT:
        goto afIkW;
        edfyB:
        EwGAe:
        goto E8GgN;
        bk81x:
        ZZ4g8:
        goto IoALG;
        oOUCz:
        $v_result = $p_options[PCLZIP_CB_POST_EXTRACT](PCLZIP_CB_POST_EXTRACT, $v_local_header);
        goto HMS0X;
        ZosEW:
        unset($v_file_content);
        goto RsBdu;
        ltn5k:
        if ($p_entry["\143\157\x6d\x70\162\145\x73\163\x65\144\137\x73\151\172\x65"] == $p_entry["\x73\151\x7a\145"]) {
            goto ZZ4g8;
        }
        goto liAyP;
        n3gwS:
        JmFo6:
        goto vRsiN;
        UKN0E:
        goto dutY8;
        goto edfyB;
        vt1hE:
    }
    public function privExtractFileAsString(&$p_entry, &$p_string, &$p_options)
    {
        goto YAR4V;
        p8CTe:
        if ($p_entry["\143\x6f\155\x70\162\x65\163\163\x69\157\156"] == 0) {
            goto KC5u1;
        }
        goto KmOb5;
        yqhYP:
        goto zch0J;
        goto KBwCL;
        aYBrd:
        $v_local_header = array();
        goto u3pvk;
        CaMwf:
        if (!(($p_entry["\x65\x78\x74\x65\162\x6e\x61\154"] & 16) == 16)) {
            goto THY1F;
        }
        goto yqhYP;
        K3Cx6:
        $p_string = $v_local_header["\143\x6f\x6e\164\x65\x6e\164"];
        goto i1uFk;
        sdUou:
        ioOFX:
        goto aYBrd;
        i1uFk:
        unset($v_local_header["\x63\157\x6e\164\x65\156\164"]);
        goto a46ad;
        ArnNr:
        $p_entry["\x73\x74\x61\x74\x75\x73"] = "\x73\153\151\x70\x70\x65\x64";
        goto JLF8l;
        Wpf2K:
        KSj7C:
        goto X_oa8;
        GizpG:
        if (!isset($p_options[PCLZIP_CB_PRE_EXTRACT])) {
            goto djnq9;
        }
        goto PYZ3Z;
        isV9t:
        VpRJ0:
        goto Wpf2K;
        MC7j1:
        $v_result = $p_options[PCLZIP_CB_PRE_EXTRACT](PCLZIP_CB_PRE_EXTRACT, $v_local_header);
        goto zj0Gj;
        gQRIF:
        $v_header = array();
        goto mEpg1;
        sqjMJ:
        $v_result = 1;
        goto O76Q2;
        KmOb5:
        $v_data = @fread($this->zip_fd, $p_entry["\143\x6f\x6d\x70\162\x65\163\x73\x65\x64\x5f\163\x69\x7a\145"]);
        goto UxZil;
        sQIrH:
        goto L9f8H;
        goto E4IqU;
        Ci2Hr:
        $v_result = PCLZIP_ERR_USER_ABORTED;
        goto isV9t;
        PYZ3Z:
        $v_local_header = array();
        goto FK7du;
        L6Nqo:
        $v_result = $p_options[PCLZIP_CB_POST_EXTRACT](PCLZIP_CB_POST_EXTRACT, $v_local_header);
        goto K3Cx6;
        mEpg1:
        if (!(($v_result = $this->privReadFileHeader($v_header)) != 1)) {
            goto vsX_O;
        }
        goto weyTP;
        zj0Gj:
        if (!($v_result == 0)) {
            goto t9rmi;
        }
        goto NKNyl;
        X0NwZ:
        $v_result = PCLZIP_ERR_USER_ABORTED;
        goto nNYmB;
        hVcH4:
        if (!($v_result == 2)) {
            goto rdKwb;
        }
        goto Ub5YI;
        eELJb:
        KlCFM:
        goto ywwqr;
        E4IqU:
        KC5u1:
        goto q0gYr;
        UrU0C:
        $v_local_header["\143\x6f\156\x74\x65\x6e\x74"] = $p_string;
        goto DiIyt;
        EUOdj:
        Bhq0G:
        goto sQIrH;
        BCAO1:
        if (isset($p_options[PCLZIP_CB_POST_EXTRACT])) {
            goto ioOFX;
        }
        goto ZEFPG;
        FJnOM:
        vsX_O:
        goto yKU5F;
        a46ad:
        if (!($v_result == 2)) {
            goto VpRJ0;
        }
        goto Ci2Hr;
        Ub5YI:
        $p_entry["\x73\x74\x61\x74\x75\x73"] = "\141\x62\x6f\x72\164\145\144";
        goto X0NwZ;
        yKU5F:
        if (!($this->privCheckFileHeaders($v_header, $p_entry) != 1)) {
            goto wMidr;
        }
        goto GqtFx;
        FK7du:
        $this->privConvertHeader2FileInfo($p_entry, $v_local_header);
        goto MC7j1;
        O76Q2:
        t9rmi:
        goto hVcH4;
        NKNyl:
        $p_entry["\x73\x74\x61\164\x75\x73"] = "\x73\153\151\160\x70\x65\x64";
        goto sqjMJ;
        RXC1b:
        djnq9:
        goto E3dE6;
        q0gYr:
        $p_string = @fread($this->zip_fd, $p_entry["\x63\x6f\155\160\162\x65\163\163\145\144\x5f\163\151\x7a\145"]);
        goto WP6RM;
        u3pvk:
        $this->privConvertHeader2FileInfo($p_entry, $v_local_header);
        goto UrU0C;
        ZEFPG:
        goto KSj7C;
        goto KVFNu;
        WP6RM:
        L9f8H:
        goto SNHTI;
        UxZil:
        if (!(($p_string = @gzinflate($v_data)) === false)) {
            goto Bhq0G;
        }
        goto EUOdj;
        weyTP:
        return $v_result;
        goto FJnOM;
        KBwCL:
        THY1F:
        goto p8CTe;
        SNHTI:
        zch0J:
        goto eELJb;
        nNYmB:
        rdKwb:
        goto xYiXq;
        KVFNu:
        uPacp:
        goto ArnNr;
        ywwqr:
        if ($p_entry["\x73\164\x61\x74\x75\163"] == "\141\x62\x6f\x72\164\145\x64") {
            goto uPacp;
        }
        goto BCAO1;
        xYiXq:
        $p_entry["\146\151\154\145\x6e\141\155\145"] = $v_local_header["\146\151\x6c\145\156\x61\155\145"];
        goto RXC1b;
        DiIyt:
        $p_string = '';
        goto L6Nqo;
        JLF8l:
        goto KSj7C;
        goto sdUou;
        E3dE6:
        if (!($p_entry["\163\164\141\x74\x75\163"] == "\x6f\x6b")) {
            goto KlCFM;
        }
        goto CaMwf;
        YAR4V:
        $v_result = 1;
        goto gQRIF;
        GqtFx:
        wMidr:
        goto GizpG;
        X_oa8:
        return $v_result;
        goto FKiQu;
        FKiQu:
    }
    public function privReadFileHeader(&$p_header)
    {
        goto yV6Dd;
        qenlg:
        $v_day = $p_header["\155\144\x61\x74\145"] & 31;
        goto FaQpa;
        I6TEK:
        bml4T:
        goto Oymun;
        gkUWl:
        $p_header["\x63\x6f\x6d\160\x72\x65\x73\163\x65\144\137\163\x69\x7a\145"] = $v_data["\x63\157\155\160\x72\145\163\163\145\x64\137\163\151\172\x65"];
        goto GROL1;
        esMFi:
        goto mauU7;
        goto I6TEK;
        GuVkj:
        $p_header["\166\x65\x72\163\x69\x6f\156\137\145\x78\x74\162\x61\143\x74\145\x64"] = $v_data["\x76\145\x72\x73\x69\x6f\156"];
        goto X4VbI;
        FaQpa:
        $p_header["\155\x74\x69\x6d\x65"] = @mktime($v_hour, $v_minute, $v_seconde, $v_month, $v_day, $v_year);
        goto WozA_;
        S8T6s:
        if ($v_data["\x65\170\164\x72\x61\137\x6c\x65\156"] != 0) {
            goto q_Fx4;
        }
        goto ZQLF1;
        X5j3d:
        $v_minute = ($p_header["\x6d\x74\151\x6d\x65"] & 2016) >> 5;
        goto bFYRY;
        A8oH2:
        goto erCv5;
        goto nDfU6;
        VbAUo:
        return $v_result;
        goto mw_m9;
        zzJ8Z:
        $p_header["\x65\170\x74\162\x61"] = fread($this->zip_fd, $v_data["\145\x78\x74\162\x61\137\154\x65\x6e"]);
        goto HZJKn;
        MQ9Vj:
        OX_cp:
        goto fnvaZ;
        uzOvN:
        TYV7Z:
        goto M1JCx;
        dW1Lx:
        $p_header["\146\151\154\145\156\141\x6d\x65"] = '';
        goto YVwzx;
        X4VbI:
        $p_header["\143\x6f\x6d\x70\x72\x65\163\x73\151\x6f\156"] = $v_data["\143\157\x6d\x70\162\x65\x73\163\x69\157\x6e"];
        goto d_9W_;
        kZjCZ:
        $p_header["\155\144\141\164\x65"] = $v_data["\155\144\x61\164\x65"];
        goto zfo4N;
        d_9W_:
        $p_header["\x73\x69\172\145"] = $v_data["\163\x69\x7a\x65"];
        goto gkUWl;
        XC6nq:
        PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, "\x49\156\x76\x61\x6c\x69\144\40\142\154\x6f\x63\x6b\40\x73\x69\x7a\145\40\72\x20" . strlen($v_binary_data));
        goto v3yEw;
        HZJKn:
        erCv5:
        goto GuVkj;
        OFQVQ:
        $p_header["\146\x69\154\x65\156\141\x6d\145\137\x6c\x65\156"] = $v_data["\146\x69\154\x65\156\x61\155\x65\137\x6c\x65\x6e"];
        goto kZjCZ;
        M1JCx:
        $v_data = unpack("\x76\166\145\162\163\151\x6f\156\57\x76\x66\x6c\141\147\x2f\166\x63\x6f\x6d\x70\162\145\x73\163\151\x6f\x6e\57\166\155\x74\x69\x6d\x65\x2f\x76\155\144\141\x74\x65\57\126\x63\162\x63\57\126\x63\157\155\160\162\145\163\x73\x65\x64\x5f\163\151\x7a\x65\57\x56\x73\x69\x7a\x65\57\166\x66\x69\154\x65\156\141\155\145\137\154\145\x6e\x2f\166\145\170\x74\162\141\137\x6c\145\x6e", $v_binary_data);
        goto LkkS8;
        Oymun:
        $v_hour = ($p_header["\x6d\164\151\x6d\145"] & 63488) >> 11;
        goto X5j3d;
        v3yEw:
        return PclZip::errorCode();
        goto uzOvN;
        GROL1:
        $p_header["\143\x72\143"] = $v_data["\143\162\x63"];
        goto UwjaW;
        Y7crE:
        return PclZip::errorCode();
        goto MQ9Vj;
        UwjaW:
        $p_header["\146\x6c\141\147"] = $v_data["\x66\x6c\141\x67"];
        goto OFQVQ;
        zfo4N:
        $p_header["\x6d\164\x69\x6d\145"] = $v_data["\x6d\164\151\155\145"];
        goto Pjc36;
        bFYRY:
        $v_seconde = ($p_header["\155\164\151\155\x65"] & 31) * 2;
        goto qBkRF;
        nDfU6:
        q_Fx4:
        goto zzJ8Z;
        b7Izp:
        $p_header["\155\x74\x69\x6d\145"] = time();
        goto esMFi;
        LkkS8:
        $p_header["\x66\151\154\145\156\x61\x6d\145"] = fread($this->zip_fd, $v_data["\x66\x69\x6c\145\156\x61\155\x65\x5f\154\145\x6e"]);
        goto S8T6s;
        MCrNr:
        $v_month = ($p_header["\x6d\x64\141\164\145"] & 480) >> 5;
        goto qenlg;
        t9E6I:
        if (!(strlen($v_binary_data) != 26)) {
            goto TYV7Z;
        }
        goto dW1Lx;
        yV6Dd:
        $v_result = 1;
        goto AONmj;
        YVwzx:
        $p_header["\163\x74\x61\164\x75\x73"] = "\x69\x6e\x76\141\x6c\x69\144\x5f\150\x65\x61\144\x65\x72";
        goto XC6nq;
        eNEBi:
        if (!($v_data["\x69\x64"] != 67324752)) {
            goto OX_cp;
        }
        goto w9gJm;
        QIQz0:
        $p_header["\x73\x74\x61\x74\165\x73"] = "\157\153";
        goto VbAUo;
        qBkRF:
        $v_year = (($p_header["\x6d\144\141\164\145"] & 65024) >> 9) + 1980;
        goto MCrNr;
        lioaP:
        $v_data = unpack("\126\x69\144", $v_binary_data);
        goto eNEBi;
        Pjc36:
        if ($p_header["\155\x64\x61\x74\145"] && $p_header["\155\164\151\155\145"]) {
            goto bml4T;
        }
        goto b7Izp;
        AONmj:
        $v_binary_data = @fread($this->zip_fd, 4);
        goto lioaP;
        w9gJm:
        PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, "\111\156\x76\x61\154\x69\144\40\141\x72\x63\x68\x69\x76\145\x20\163\x74\162\165\143\164\165\x72\x65");
        goto Y7crE;
        WozA_:
        mauU7:
        goto IzbvX;
        fnvaZ:
        $v_binary_data = fread($this->zip_fd, 26);
        goto t9E6I;
        ZQLF1:
        $p_header["\x65\x78\x74\x72\x61"] = '';
        goto A8oH2;
        IzbvX:
        $p_header["\163\164\x6f\x72\x65\144\x5f\x66\151\154\145\156\141\155\145"] = $p_header["\146\151\154\x65\156\141\155\x65"];
        goto QIQz0;
        mw_m9:
    }
    public function privReadCentralFileHeader(&$p_header)
    {
        goto I9Qyx;
        lKPqn:
        $p_header["\x63\x6f\155\155\145\x6e\164"] = '';
        goto zayqP;
        F3tPA:
        $p_header["\x65\x78\x74\162\x61"] = fread($this->zip_fd, $p_header["\145\x78\164\162\x61\x5f\x6c\145\x6e"]);
        goto R3rxL;
        c79mI:
        $v_data = unpack("\126\x69\x64", $v_binary_data);
        goto vg0Yv;
        D9M4Y:
        $v_year = (($p_header["\155\144\x61\x74\145"] & 65024) >> 9) + 1980;
        goto WaTXn;
        S9gfe:
        $v_minute = ($p_header["\155\x74\x69\x6d\145"] & 2016) >> 5;
        goto Q9vbx;
        OUq1L:
        ExlGv:
        goto F3tPA;
        bGOc5:
        if ($p_header["\143\157\x6d\155\145\x6e\164\x5f\154\145\x6e"] != 0) {
            goto pFavE;
        }
        goto lKPqn;
        GSBzJ:
        goto nokdG;
        goto OUq1L;
        hFQtw:
        q7lET:
        goto LVHvy;
        DwuyL:
        if ($p_header["\145\170\x74\162\x61\x5f\x6c\x65\x6e"] != 0) {
            goto ExlGv;
        }
        goto lbct3;
        f9R3u:
        NywS4:
        goto t7zKX;
        WV1O2:
        $p_header = unpack("\x76\166\x65\162\x73\x69\157\x6e\57\166\166\145\x72\x73\x69\x6f\156\137\145\x78\x74\x72\x61\143\x74\x65\x64\57\x76\x66\x6c\141\x67\x2f\x76\143\157\x6d\160\162\x65\163\x73\x69\157\x6e\57\x76\155\x74\x69\x6d\145\x2f\x76\155\x64\x61\164\x65\57\x56\143\162\143\57\x56\x63\x6f\x6d\x70\x72\x65\163\x73\145\144\137\163\151\x7a\145\x2f\x56\163\151\172\x65\x2f\166\x66\x69\154\145\x6e\x61\155\x65\137\x6c\x65\156\57\166\145\x78\164\x72\x61\137\x6c\145\156\x2f\x76\143\157\155\155\x65\156\x74\x5f\x6c\x65\156\57\x76\x64\151\163\153\x2f\166\x69\156\164\x65\x72\156\x61\154\57\126\x65\x78\x74\x65\x72\x6e\141\x6c\x2f\126\157\146\x66\163\145\164", $v_binary_data);
        goto PeNXG;
        yKM9q:
        return $v_result;
        goto Uahk1;
        t8428:
        $p_header["\146\x69\x6c\145\x6e\x61\x6d\x65"] = '';
        goto H6Dvd;
        y30wg:
        $p_header["\x73\x74\141\164\x75\163"] = "\x6f\153";
        goto L6BUa;
        PeNXG:
        if ($p_header["\146\151\154\x65\x6e\x61\155\x65\137\154\145\156"] != 0) {
            goto NywS4;
        }
        goto t8428;
        tT3J8:
        $p_header["\163\x74\x61\x74\x75\x73"] = "\x69\156\x76\141\154\x69\144\x5f\x68\x65\x61\144\x65\x72";
        goto gfMdA;
        I9Qyx:
        $v_result = 1;
        goto tnhAG;
        FWt76:
        PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, "\111\x6e\166\141\x6c\x69\x64\40\141\162\x63\x68\x69\166\x65\40\x73\164\x72\165\143\x74\x75\162\145");
        goto ex26G;
        H6Dvd:
        goto FA7tn;
        goto f9R3u;
        t7zKX:
        $p_header["\146\x69\x6c\x65\156\x61\x6d\x65"] = fread($this->zip_fd, $p_header["\146\151\154\x65\156\x61\x6d\145\137\154\x65\156"]);
        goto IK9eN;
        hWovZ:
        ojrYP:
        goto DuGjZ;
        lbct3:
        $p_header["\145\x78\164\x72\x61"] = '';
        goto GSBzJ;
        qSUhj:
        jgZCI:
        goto WV1O2;
        DWMA5:
        if (!(strlen($v_binary_data) != 42)) {
            goto jgZCI;
        }
        goto y_bsa;
        DuGjZ:
        $p_header["\x73\164\157\162\x65\144\x5f\146\x69\154\x65\156\141\x6d\x65"] = $p_header["\x66\x69\x6c\x65\x6e\141\155\x65"];
        goto y30wg;
        y_bsa:
        $p_header["\146\x69\154\145\156\x61\155\145"] = '';
        goto tT3J8;
        OoA2B:
        return PclZip::errorCode();
        goto qSUhj;
        xo6m7:
        goto ojrYP;
        goto r254u;
        EFzVE:
        $p_header["\143\157\155\155\x65\156\164"] = fread($this->zip_fd, $p_header["\x63\x6f\x6d\x6d\145\x6e\164\x5f\154\x65\156"]);
        goto buhBi;
        wa9wo:
        $v_hour = ($p_header["\x6d\164\151\155\145"] & 63488) >> 11;
        goto S9gfe;
        r254u:
        No9pp:
        goto wa9wo;
        Q9vbx:
        $v_seconde = ($p_header["\x6d\164\x69\x6d\145"] & 31) * 2;
        goto D9M4Y;
        Lok8c:
        pFavE:
        goto EFzVE;
        buhBi:
        HrRJm:
        goto ogd_O;
        LVHvy:
        $v_binary_data = fread($this->zip_fd, 42);
        goto DWMA5;
        gfMdA:
        PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, "\x49\156\x76\x61\x6c\151\144\40\142\154\x6f\x63\x6b\40\163\x69\172\145\40\x3a\40" . strlen($v_binary_data));
        goto OoA2B;
        IK9eN:
        FA7tn:
        goto DwuyL;
        vg0Yv:
        if (!($v_data["\x69\144"] != 33639248)) {
            goto q7lET;
        }
        goto FWt76;
        qTLcu:
        $v_day = $p_header["\155\144\x61\x74\145"] & 31;
        goto fd9QI;
        R3rxL:
        nokdG:
        goto bGOc5;
        zayqP:
        goto HrRJm;
        goto Lok8c;
        fd9QI:
        $p_header["\x6d\164\151\x6d\x65"] = @mktime($v_hour, $v_minute, $v_seconde, $v_month, $v_day, $v_year);
        goto hWovZ;
        WaTXn:
        $v_month = ($p_header["\155\x64\141\164\145"] & 480) >> 5;
        goto qTLcu;
        dwW20:
        $p_header["\x65\170\x74\x65\x72\x6e\x61\x6c"] = 16;
        goto kZ7QE;
        tnhAG:
        $v_binary_data = @fread($this->zip_fd, 4);
        goto c79mI;
        ogd_O:
        if (1) {
            goto No9pp;
        }
        goto yVjbt;
        yVjbt:
        $p_header["\x6d\164\x69\x6d\145"] = time();
        goto xo6m7;
        ex26G:
        return PclZip::errorCode();
        goto hFQtw;
        kZ7QE:
        S_zvV:
        goto yKM9q;
        L6BUa:
        if (!(substr($p_header["\x66\x69\154\145\x6e\141\x6d\x65"], -1) == "\x2f")) {
            goto S_zvV;
        }
        goto dwW20;
        Uahk1:
    }
    public function privCheckFileHeaders(&$p_local_header, &$p_central_header)
    {
        goto U_Kbm;
        hQbux:
        $p_local_header["\163\151\x7a\x65"] = $p_central_header["\163\151\x7a\145"];
        goto wllyE;
        ih7rA:
        if (!($p_local_header["\x76\145\162\x73\x69\x6f\156\137\x65\x78\x74\162\141\x63\164\x65\x64"] != $p_central_header["\166\145\162\163\x69\157\x6e\x5f\x65\170\x74\x72\x61\143\x74\145\144"])) {
            goto m2OH3;
        }
        goto rGTAY;
        wllyE:
        $p_local_header["\143\x6f\x6d\160\162\x65\163\163\x65\x64\137\163\x69\x7a\145"] = $p_central_header["\x63\157\155\160\162\x65\163\x73\145\x64\137\163\151\172\145"];
        goto oetNT;
        AmAe1:
        dSkk8:
        goto q7egy;
        NmijB:
        if (!($p_local_header["\x66\154\141\147"] != $p_central_header["\146\154\x61\147"])) {
            goto VE0rE;
        }
        goto H6K7j;
        Tal1F:
        VZcyJ:
        goto HGqt4;
        oetNT:
        $p_local_header["\x63\x72\143"] = $p_central_header["\143\x72\x63"];
        goto Tal1F;
        AwXsu:
        if (!($p_local_header["\x66\x69\154\145\156\141\x6d\145"] != $p_central_header["\146\x69\154\145\156\x61\x6d\145"])) {
            goto dAkx9;
        }
        goto KndPe;
        JV7ic:
        if (!(($p_local_header["\146\x6c\141\x67"] & 8) == 8)) {
            goto VZcyJ;
        }
        goto hQbux;
        H6K7j:
        VE0rE:
        goto NTWtX;
        NTWtX:
        if (!($p_local_header["\x63\157\155\160\x72\x65\163\163\x69\x6f\156"] != $p_central_header["\143\157\155\160\162\x65\163\163\151\x6f\156"])) {
            goto nm41Y;
        }
        goto r_Kio;
        hjr2s:
        if (!($p_local_header["\155\164\151\155\145"] != $p_central_header["\155\164\151\155\145"])) {
            goto dSkk8;
        }
        goto AmAe1;
        q7egy:
        if (!($p_local_header["\x66\x69\154\x65\x6e\141\x6d\x65\137\154\145\x6e"] != $p_central_header["\x66\x69\x6c\145\156\x61\x6d\x65\x5f\x6c\x65\156"])) {
            goto bdBkE;
        }
        goto R2g7Q;
        HGqt4:
        return $v_result;
        goto FbhDq;
        rGTAY:
        m2OH3:
        goto NmijB;
        r_Kio:
        nm41Y:
        goto hjr2s;
        R2g7Q:
        bdBkE:
        goto JV7ic;
        U_Kbm:
        $v_result = 1;
        goto AwXsu;
        KndPe:
        dAkx9:
        goto ih7rA;
        FbhDq:
    }
    public function privReadEndCentralDir(&$p_central_dir)
    {
        goto WbHqD;
        hp81n:
        $v_found = 0;
        goto oxpIv;
        symTm:
        $v_byte = @fread($this->zip_fd, 1);
        goto NW2yc;
        h13E_:
        if (!(@ftell($this->zip_fd) != $v_size - $v_maximum_size)) {
            goto sQwp1;
        }
        goto fKX2i;
        HlDO0:
        return PclZip::errorCode();
        goto ETqnC;
        AMgn3:
        if (!(@ftell($this->zip_fd) != $v_size)) {
            goto M02b3;
        }
        goto KwZqV;
        oxpIv:
        if (!($v_size > 26)) {
            goto SMpMw;
        }
        goto Le7yT;
        xzDeY:
        $v_maximum_size = 65557;
        goto QWh7l;
        ETqnC:
        i9d77:
        goto ElHFF;
        DMefi:
        if (!($v_pos == $v_size)) {
            goto DbG_I;
        }
        goto jPDDM;
        gdLuu:
        $v_pos = ftell($this->zip_fd);
        goto mOwye;
        BprDR:
        if (!($v_pos + $v_data["\x63\x6f\x6d\x6d\x65\x6e\164\137\x73\151\172\x65"] + 18 != $v_size)) {
            goto JvtAq;
        }
        goto ddf3l;
        qerEl:
        sF9g1:
        goto Dwq7o;
        uD7OC:
        goto vKlSl;
        goto TlJJk;
        PuV6J:
        $p_central_dir["\145\156\x74\x72\x69\145\163"] = $v_data["\145\156\x74\x72\x69\x65\x73"];
        goto kk1ep;
        uhDjv:
        z1asO:
        goto xwO02;
        reyPq:
        @fseek($this->zip_fd, $v_size);
        goto AMgn3;
        KwZqV:
        PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, "\125\156\141\x62\154\x65\x20\164\157\x20\147\157\x20\164\x6f\x20\x74\x68\x65\40\x65\156\x64\x20\x6f\x66\40\x74\x68\145\40\x61\x72\x63\150\x69\166\x65\40\x27" . $this->zipname . "\47");
        goto Jb_k7;
        fKX2i:
        PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, "\x55\x6e\x61\x62\154\145\x20\x74\157\x20\x73\x65\x65\x6b\40\142\x61\143\x6b\40\164\157\x20\x74\x68\x65\40\x6d\x69\144\x64\x6c\x65\40\157\x66\40\164\x68\145\40\x61\x72\143\150\x69\x76\x65\x20\x27" . $this->zipname . "\x27");
        goto rpikN;
        r5V6d:
        WWkhc:
        goto yFPM0;
        QWh7l:
        if (!($v_maximum_size > $v_size)) {
            goto z1asO;
        }
        goto YKZm0;
        PLrnz:
        hRijM:
        goto Qh9UD;
        jc1cV:
        $v_pos++;
        goto uD7OC;
        r0zYV:
        $v_size = filesize($this->zipname);
        goto reyPq;
        aIQ9c:
        return PclZip::errorCode();
        goto JGzU1;
        LwHEs:
        PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, "\x55\156\141\x62\x6c\x65\x20\x74\x6f\x20\x73\x65\145\x6b\40\x62\141\x63\x6b\x20\164\157\40\164\x68\x65\40\155\151\x64\x64\154\145\40\x6f\x66\x20\x74\150\x65\x20\141\162\143\x68\151\166\145\40\47" . $this->zipname . "\47");
        goto aIQ9c;
        AOaMB:
        if (!(strlen($v_binary_data) != 18)) {
            goto hRijM;
        }
        goto ppGby;
        Jtc9q:
        goto meIs6;
        goto qerEl;
        mOwye:
        $v_bytes = 0;
        goto r5V6d;
        Qb5Nj:
        $p_central_dir["\163\x69\172\145"] = $v_data["\163\x69\x7a\x65"];
        goto kLMx0;
        Mmr4G:
        M02b3:
        goto hp81n;
        bTu_k:
        $v_binary_data = @fread($this->zip_fd, 4);
        goto J_Mt4;
        Jb_k7:
        return PclZip::errorCode();
        goto Mmr4G;
        yTclH:
        FzhM3:
        goto Ndev0;
        J_Mt4:
        $v_data = @unpack("\126\x69\x64", $v_binary_data);
        goto p_iQU;
        mzKXp:
        $v_found = 1;
        goto yTclH;
        UbOQQ:
        if ($v_data["\x63\157\x6d\x6d\x65\x6e\x74\137\x73\x69\172\145"] != 0) {
            goto sF9g1;
        }
        goto jmDz2;
        Fp0MK:
        $v_pos++;
        goto uZAUi;
        xwO02:
        @fseek($this->zip_fd, $v_size - $v_maximum_size);
        goto h13E_;
        Dwq7o:
        $p_central_dir["\x63\157\x6d\155\145\x6e\x74"] = fread($this->zip_fd, $v_data["\x63\x6f\x6d\155\x65\x6e\164\137\163\151\172\x65"]);
        goto f41sw;
        Gq6Cx:
        $p_central_dir["\x64\151\x73\x6b\x5f\163\164\141\x72\x74"] = $v_data["\144\151\x73\153\x5f\x73\x74\141\162\x74"];
        goto CabZk;
        NW2yc:
        $v_bytes = ($v_bytes & 16777215) << 8 | Ord($v_byte);
        goto T6DgY;
        pKw5T:
        if ($v_found) {
            goto vGZFb;
        }
        goto xzDeY;
        p_iQU:
        if (!($v_data["\151\144"] == 101010256)) {
            goto FzhM3;
        }
        goto mzKXp;
        vIX2a:
        PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, "\x54\x68\145\x20\143\x65\156\x74\x72\x61\x6c\40\144\151\x72\x20\151\163\40\x6e\x6f\x74\40\x61\164\40\x74\150\x65\40\x65\156\144\40\157\x66\x20\x74\x68\145\40\141\162\x63\x68\151\166\x65\56" . "\40\123\x6f\x6d\145\40\x74\162\x61\x69\x6c\x69\156\x67\x20\142\x79\164\145\x73\40\145\x78\x69\x73\x74\x73\x20\x61\x66\164\x65\162\x20\164\x68\145\40\141\x72\x63\150\x69\x76\x65\x2e");
        goto HlDO0;
        xJUq2:
        DbG_I:
        goto gUyBk;
        Uz_d2:
        $v_binary_data = fread($this->zip_fd, 18);
        goto AOaMB;
        s3GHU:
        return PclZip::errorCode();
        goto PLrnz;
        RqcNf:
        sQwp1:
        goto gdLuu;
        Le7yT:
        @fseek($this->zip_fd, $v_size - 22);
        goto bWZtz;
        Pwlgn:
        SMpMw:
        goto pKw5T;
        rpikN:
        return PclZip::errorCode();
        goto RqcNf;
        JGzU1:
        v_04j:
        goto bTu_k;
        f41sw:
        meIs6:
        goto PuV6J;
        jmDz2:
        $p_central_dir["\x63\x6f\155\x6d\x65\156\164"] = '';
        goto Jtc9q;
        gUyBk:
        vGZFb:
        goto Uz_d2;
        jPDDM:
        PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, "\x55\x6e\141\x62\154\x65\x20\164\157\40\x66\x69\156\x64\x20\105\x6e\x64\x20\x6f\146\40\103\145\x6e\164\162\x61\154\40\x44\151\x72\40\x52\x65\x63\x6f\x72\x64\40\163\x69\147\x6e\141\x74\x75\162\145");
        goto qa95N;
        Ndev0:
        $v_pos = ftell($this->zip_fd);
        goto Pwlgn;
        kLMx0:
        $p_central_dir["\144\x69\x73\x6b"] = $v_data["\x64\x69\163\153"];
        goto Gq6Cx;
        bWZtz:
        if (!(($v_pos = @ftell($this->zip_fd)) != $v_size - 22)) {
            goto v_04j;
        }
        goto LwHEs;
        uCMQn:
        vKlSl:
        goto DMefi;
        TlJJk:
        jwaHn:
        goto Fp0MK;
        WbHqD:
        $v_result = 1;
        goto r0zYV;
        uZAUi:
        goto WWkhc;
        goto uCMQn;
        yFPM0:
        if (!($v_pos < $v_size)) {
            goto vKlSl;
        }
        goto symTm;
        ddf3l:
        if (!0) {
            goto i9d77;
        }
        goto vIX2a;
        kk1ep:
        $p_central_dir["\144\x69\163\153\x5f\x65\156\x74\x72\151\x65\163"] = $v_data["\144\151\x73\x6b\x5f\x65\156\x74\x72\151\145\163"];
        goto TwPxI;
        CabZk:
        return $v_result;
        goto R2Fux;
        qa95N:
        return PclZip::errorCode();
        goto xJUq2;
        Qh9UD:
        $v_data = unpack("\166\x64\x69\163\x6b\57\166\144\x69\163\x6b\x5f\x73\x74\141\x72\164\57\166\144\x69\163\153\137\x65\x6e\x74\x72\151\x65\163\57\x76\x65\x6e\164\162\x69\x65\163\57\126\163\151\172\x65\x2f\x56\157\146\146\x73\x65\164\x2f\166\143\x6f\x6d\x6d\145\156\164\x5f\x73\x69\x7a\145", $v_binary_data);
        goto BprDR;
        ppGby:
        PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, "\111\x6e\x76\141\x6c\x69\144\x20\105\x6e\x64\x20\157\146\x20\x43\145\156\x74\162\141\x6c\x20\104\151\x72\x20\x52\145\143\x6f\162\144\x20\163\151\x7a\x65\x20\72\x20" . strlen($v_binary_data));
        goto s3GHU;
        T6DgY:
        if (!($v_bytes == 1347093766)) {
            goto jwaHn;
        }
        goto jc1cV;
        YKZm0:
        $v_maximum_size = $v_size;
        goto uhDjv;
        ElHFF:
        JvtAq:
        goto UbOQQ;
        TwPxI:
        $p_central_dir["\157\146\146\163\145\x74"] = $v_data["\157\x66\146\x73\145\164"];
        goto Qb5Nj;
        R2Fux:
    }
    public function privDeleteByRule(&$p_result_list, &$p_options)
    {
        goto fRKys;
        hY3Mn:
        @rewind($this->zip_fd);
        goto xikTx;
        sZtAZ:
        ng_wH:
        goto coMxx;
        LbtDp:
        $v_found = true;
        goto Mwu0T;
        bE440:
        @unlink($v_zip_temp_name);
        goto xNSpc;
        lvPd0:
        $v_header_list = array();
        goto wSv3j;
        lWGxf:
        return PclZip::errorCode();
        goto qTbau;
        wdHDq:
        H05Wz:
        goto MFhRU;
        uQ0Gj:
        EeUXL:
        goto t2A3N;
        fUkxs:
        @unlink($v_zip_temp_name);
        goto mekF0;
        UnDE9:
        $v_temp_zip->privCloseFd();
        goto bE440;
        F8K9Z:
        goto G4QvN;
        goto mPTOq;
        W2PUA:
        Cruhx:
        goto P3yTk;
        mKTjA:
        IrBn6:
        goto XizzB;
        tNpj5:
        $v_found = true;
        goto i3NkI;
        WoZ6w:
        if ($v_header_list[$v_nb_extracted]["\x73\x74\157\162\145\x64\137\x66\151\x6c\x65\x6e\141\155\145"] == $p_options[PCLZIP_OPT_BY_NAME][$j]) {
            goto Y6ReC;
        }
        goto BVWMF;
        VtK43:
        cqTzT:
        goto zEdoy;
        dSULc:
        $this->privCloseFd();
        goto Gq1hz;
        p7bkO:
        return PclZip::errorCode();
        goto QAJTw;
        E0H9d:
        $j_start = $j + 1;
        goto WPMDx;
        oKfNw:
        gTJZo:
        goto QTwTJ;
        wOtGS:
        $v_header_list[$v_nb_extracted] = array();
        goto dJLXP;
        mPTOq:
        LIDKQ:
        goto U6Txd;
        QTwTJ:
        $this->privCloseFd();
        goto rjd6a;
        MOYcW:
        unset($v_local_header);
        goto fToxp;
        ajY1Z:
        return $v_result;
        goto mKTjA;
        s0vIT:
        $v_comment = '';
        goto RtVyF;
        jwCvJ:
        if (!(($v_result = $v_temp_zip->privWriteCentralFileHeader($v_header_list[$i])) != 1)) {
            goto hZNEH;
        }
        goto H7oZK;
        xikTx:
        if (!@fseek($this->zip_fd, $v_header_list[$i]["\x6f\x66\x66\163\x65\164"])) {
            goto CGJEI;
        }
        goto Qih0N;
        x_Sfv:
        $v_comment = $p_options[PCLZIP_OPT_COMMENT];
        goto NZ3hY;
        ldiWS:
        cV9nz:
        goto SrNe5;
        wiVVz:
        RbKMz:
        goto ikhC3;
        u5G9K:
        $i++;
        goto o0_RD;
        JF1mE:
        unset($v_temp_zip);
        goto Qd2_v;
        Sx9rO:
        bf2BS:
        goto Af1yv;
        Gq1hz:
        @unlink($v_zip_temp_name);
        goto ajY1Z;
        fToxp:
        if (!(($v_result = $v_temp_zip->privWriteFileHeader($v_header_list[$i])) != 1)) {
            goto fgeL4;
        }
        goto N120p;
        MFhRU:
        if (!($i < sizeof($v_header_list))) {
            goto AaUcW;
        }
        goto jwCvJ;
        c8jP4:
        if (!(($v_result = PclZipUtilCopyBlock($this->zip_fd, $v_temp_zip->zip_fd, $v_header_list[$i]["\143\x6f\155\x70\x72\145\x73\x73\x65\144\x5f\163\151\x7a\x65"])) != 1)) {
            goto YgI4f;
        }
        goto rOq4Z;
        hYmoo:
        ZxHgL:
        goto uQ0Gj;
        rwtSm:
        goto ZiCdm;
        goto i64Wp;
        zq2Mu:
        Y6ReC:
        goto aJI_P;
        olfRU:
        JwW8X:
        goto naocW;
        ftgNM:
        $this->privCloseFd();
        goto Pv3o7;
        fRKys:
        $v_result = 1;
        goto cbCAk;
        ix_1F:
        YgI4f:
        goto Yhr2w;
        XizzB:
        $v_temp_zip->privCloseFd();
        goto GFm1G;
        Rbwbk:
        $this->privCloseFd();
        goto dot0g;
        H7oZK:
        $v_temp_zip->privCloseFd();
        goto inbH2;
        Qrmu1:
        if (!($i < $v_central_dir["\145\156\x74\162\151\x65\163"])) {
            goto bf2BS;
        }
        goto wOtGS;
        J6cCz:
        unset($v_header_list);
        goto lbIiH;
        KrAM9:
        $v_found = false;
        goto zIE6B;
        mekF0:
        PclZip::privErrorLog(PCLZIP_ERR_INVALID_ARCHIVE_ZIP, "\111\156\x76\141\154\x69\x64\x20\x61\162\143\150\x69\x76\145\40\163\x69\172\145");
        goto lWGxf;
        uYXEs:
        qRyQ7:
        goto Vawo0;
        Qih0N:
        $this->privCloseFd();
        goto iv5mh;
        dJLXP:
        if (!(($v_result = $this->privReadCentralFileHeader($v_header_list[$v_nb_extracted])) != 1)) {
            goto MWQo2;
        }
        goto Rbwbk;
        SCF85:
        if (isset($p_options[PCLZIP_OPT_BY_INDEX]) && $p_options[PCLZIP_OPT_BY_INDEX] != 0) {
            goto o2RAZ;
        }
        goto ZCd9x;
        UswS7:
        return $v_result;
        goto ovw_L;
        h7076:
        goto VvZ8D;
        goto wwHoX;
        Mwu0T:
        KHHHT:
        goto rwtSm;
        a1Hps:
        return $v_result;
        goto Strjm;
        j3GYp:
        if (!@fseek($this->zip_fd, $v_pos_entry)) {
            goto k5LlJ;
        }
        goto ftgNM;
        LRqLC:
        if (!($j < sizeof($p_options[PCLZIP_OPT_BY_NAME]) && !$v_found)) {
            goto RbKMz;
        }
        goto N938U;
        t2A3N:
        $j++;
        goto Of0XY;
        SkQss:
        $v_temp_zip->privCloseFd();
        goto zIjOo;
        jTOlX:
        if (!($i < sizeof($v_header_list))) {
            goto LIDKQ;
        }
        goto hY3Mn;
        U741x:
        $i = 0;
        goto rjIo0;
        CGPU9:
        $i = 0;
        goto wdHDq;
        u0xLq:
        @unlink($v_zip_temp_name);
        goto g_trS;
        FCRbC:
        $this->privCloseFd();
        goto mTQWp;
        IsK1s:
        sScIV:
        goto LRqLC;
        LS03p:
        unset($v_header_list[$v_nb_extracted]);
        goto VtK43;
        sbjZR:
        return $v_result;
        goto vBb0Y;
        N938U:
        if (substr($p_options[PCLZIP_OPT_BY_NAME][$j], -1) == "\x2f") {
            goto WD06O;
        }
        goto WoZ6w;
        V_JsV:
        @rewind($this->zip_fd);
        goto j3GYp;
        IFeZ7:
        return $v_result;
        goto ix_1F;
        iv5mh:
        $v_temp_zip->privCloseFd();
        goto fUkxs;
        SITVt:
        oxhxj:
        goto MOYcW;
        qXHaU:
        return $v_result;
        goto tKiJt;
        pJ8gU:
        $v_header_list[$v_nb_extracted]["\x69\156\x64\x65\x78"] = $i;
        goto KrAM9;
        YBYLL:
        $i++;
        goto F8K9Z;
        XJxG3:
        $j = $j_start;
        goto UrPt8;
        NqzH4:
        zSrWO:
        goto LS03p;
        yAK7y:
        QGwHZ:
        goto Qrmu1;
        rOq4Z:
        $this->privCloseFd();
        goto SkQss;
        ZIUQS:
        $v_pos_entry = $v_central_dir["\157\146\x66\x73\x65\x74"];
        goto V_JsV;
        vBb0Y:
        voT8o:
        goto kn3rW;
        rjIo0:
        $v_nb_extracted = 0;
        goto yAK7y;
        ikhC3:
        goto ZiCdm;
        goto c28x2;
        SrNe5:
        $v_found = true;
        goto j3BxB;
        EQeCa:
        if (!(($v_result = $this->privReadFileHeader($v_local_header)) != 1)) {
            goto Cruhx;
        }
        goto d9ti9;
        IZ2tB:
        MWQo2:
        goto pJ8gU;
        zIE6B:
        if (isset($p_options[PCLZIP_OPT_BY_NAME]) && $p_options[PCLZIP_OPT_BY_NAME] != 0) {
            goto qRyQ7;
        }
        goto rx3Bd;
        zEdoy:
        Et3zV:
        goto u5G9K;
        wwHoX:
        rminp:
        goto tNpj5;
        zIjOo:
        @unlink($v_zip_temp_name);
        goto IFeZ7;
        J18tP:
        if (!(($v_result = $v_temp_zip->privOpenFd("\167\142")) != 1)) {
            goto JwW8X;
        }
        goto FCRbC;
        jY89T:
        $this->privCloseFd();
        goto a1Hps;
        O_leq:
        $j++;
        goto o0BCE;
        cbCAk:
        $v_list_detail = array();
        goto Z3s9b;
        c28x2:
        dI7yr:
        goto lKCvC;
        zhKkn:
        $v_temp_zip = new PclZip($v_zip_temp_name);
        goto J18tP;
        RtVyF:
        if (!isset($p_options[PCLZIP_OPT_COMMENT])) {
            goto rSbwJ;
        }
        goto x_Sfv;
        Pv3o7:
        PclZip::privErrorLog(PCLZIP_ERR_INVALID_ARCHIVE_ZIP, "\111\156\166\141\x6c\151\x64\x20\x61\162\x63\150\151\166\145\x20\163\x69\x7a\x65");
        goto p7bkO;
        BVWMF:
        goto W0NC9;
        goto lkpmZ;
        qTbau:
        CGJEI:
        goto fpnnd;
        d9ti9:
        $this->privCloseFd();
        goto HnzBf;
        YfQtn:
        oB7bW:
        goto GBYsg;
        mTQWp:
        return $v_result;
        goto olfRU;
        wSv3j:
        $j_start = 0;
        goto U741x;
        rjd6a:
        if (!(($v_result = $this->privOpenFd("\x77\142")) != 1)) {
            goto voT8o;
        }
        goto sbjZR;
        j3BxB:
        VvZ8D:
        goto qtlAN;
        UxWr3:
        AaUcW:
        goto s0vIT;
        aJI_P:
        $v_found = true;
        goto L7VnY;
        UrPt8:
        maVGj:
        goto FCyHt;
        UcoLT:
        goto Wjr0_;
        goto udnld;
        i3NkI:
        goto VvZ8D;
        goto ldiWS;
        OTjqj:
        @rewind($this->zip_fd);
        goto ZIUQS;
        U6Txd:
        $v_offset = @ftell($v_temp_zip->zip_fd);
        goto CGPU9;
        vo6JS:
        if (($v_header_list[$v_nb_extracted]["\145\170\x74\x65\162\156\141\x6c"] & 16) == 16 && $v_header_list[$v_nb_extracted]["\x73\x74\x6f\162\145\144\137\146\x69\154\145\x6e\141\x6d\145"] . "\57" == $p_options[PCLZIP_OPT_BY_NAME][$j]) {
            goto cV9nz;
        }
        goto h7076;
        A__b9:
        $v_size = @ftell($v_temp_zip->zip_fd) - $v_offset;
        goto AD79B;
        NjcV6:
        @unlink($v_zip_temp_name);
        goto UswS7;
        udnld:
        cEMsW:
        goto VPC2V;
        QY7ls:
        if (!($i >= $p_options[PCLZIP_OPT_BY_INDEX][$j]["\145\156\x64"])) {
            goto MnDJA;
        }
        goto E0H9d;
        S4K2W:
        return $v_result;
        goto OXXTs;
        qtlAN:
        goto W0NC9;
        goto zq2Mu;
        QAJTw:
        k5LlJ:
        goto lvPd0;
        Af1yv:
        if ($v_nb_extracted > 0) {
            goto cEMsW;
        }
        goto iYCeN;
        AD79B:
        if (!(($v_result = $v_temp_zip->privWriteCentralHeader(sizeof($v_header_list), $v_size, $v_offset, $v_comment)) != 1)) {
            goto IrBn6;
        }
        goto J6cCz;
        dMVpJ:
        goto oB7bW;
        goto hYmoo;
        kn3rW:
        if (!(($v_result = $this->privWriteCentralHeader(0, 0, 0, '')) != 1)) {
            goto GxN6r;
        }
        goto S4K2W;
        lKCvC:
        if (!preg_match($p_options[PCLZIP_OPT_BY_PREG], $v_header_list[$v_nb_extracted]["\x73\164\x6f\x72\145\144\137\146\151\x6c\145\x6e\x61\x6d\x65"])) {
            goto KHHHT;
        }
        goto LbtDp;
        lbIiH:
        $v_temp_zip->privCloseFd();
        goto dSULc;
        cvWdj:
        Wjr0_:
        goto qXHaU;
        iYCeN:
        if ($v_central_dir["\145\x6e\164\x72\151\x65\163"] != 0) {
            goto gTJZo;
        }
        goto UcoLT;
        z90t1:
        if ($v_found) {
            goto zSrWO;
        }
        goto IFgLU;
        FCyHt:
        if (!($j < sizeof($p_options[PCLZIP_OPT_BY_INDEX]) && !$v_found)) {
            goto oB7bW;
        }
        goto KUXOp;
        ZCd9x:
        $v_found = true;
        goto eTNHO;
        OXXTs:
        GxN6r:
        goto gruNU;
        i64Wp:
        o2RAZ:
        goto XJxG3;
        o0BCE:
        goto sScIV;
        goto wiVVz;
        eTNHO:
        goto ZiCdm;
        goto uYXEs;
        inbH2:
        $this->privCloseFd();
        goto NjcV6;
        xNSpc:
        return $v_result;
        goto dQbuQ;
        Strjm:
        Wiiuq:
        goto OTjqj;
        ovw_L:
        hZNEH:
        goto h7cF9;
        mnuno:
        if (strlen($v_header_list[$v_nb_extracted]["\163\164\x6f\x72\145\144\x5f\x66\x69\154\145\x6e\x61\155\x65"]) > strlen($p_options[PCLZIP_OPT_BY_NAME][$j]) && substr($v_header_list[$v_nb_extracted]["\163\x74\157\x72\x65\144\x5f\146\x69\154\145\156\141\x6d\145"], 0, strlen($p_options[PCLZIP_OPT_BY_NAME][$j])) == $p_options[PCLZIP_OPT_BY_NAME][$j]) {
            goto rminp;
        }
        goto vo6JS;
        NZ3hY:
        rSbwJ:
        goto A__b9;
        N120p:
        $this->privCloseFd();
        goto UnDE9;
        g_trS:
        return $v_result;
        goto W2PUA;
        GFm1G:
        $this->privCloseFd();
        goto hUZuw;
        o0_RD:
        goto QGwHZ;
        goto Sx9rO;
        WPMDx:
        MnDJA:
        goto ALts2;
        KojwT:
        PclZipUtilRename($v_zip_temp_name, $this->zipname);
        goto JF1mE;
        KUXOp:
        if (!($i >= $p_options[PCLZIP_OPT_BY_INDEX][$j]["\x73\164\x61\x72\x74"] && $i <= $p_options[PCLZIP_OPT_BY_INDEX][$j]["\x65\156\x64"])) {
            goto a_TKI;
        }
        goto DceLa;
        mGlpA:
        a_TKI:
        goto QY7ls;
        h7cF9:
        $v_temp_zip->privConvertHeader2FileInfo($v_header_list[$i], $p_result_list[$i]);
        goto sZtAZ;
        GBYsg:
        ZiCdm:
        goto z90t1;
        gruNU:
        $this->privCloseFd();
        goto cvWdj;
        coMxx:
        $i++;
        goto OxhuD;
        Qd2_v:
        goto Wjr0_;
        goto oKfNw;
        qL1FL:
        if (!(($v_result = $this->privReadEndCentralDir($v_central_dir)) != 1)) {
            goto Wiiuq;
        }
        goto jY89T;
        P3yTk:
        if (!($this->privCheckFileHeaders($v_local_header, $v_header_list[$i]) != 1)) {
            goto oxhxj;
        }
        goto SITVt;
        OxhuD:
        goto H05Wz;
        goto UxWr3;
        HnzBf:
        $v_temp_zip->privCloseFd();
        goto u0xLq;
        ALts2:
        if (!($p_options[PCLZIP_OPT_BY_INDEX][$j]["\163\x74\x61\162\164"] > $i)) {
            goto ZxHgL;
        }
        goto dMVpJ;
        VPC2V:
        $v_zip_temp_name = PCLZIP_TEMPORARY_DIR . uniqid("\160\143\x6c\x7a\151\160\x2d") . "\x2e\x74\x6d\x70";
        goto zhKkn;
        BLXSd:
        $v_central_dir = array();
        goto qL1FL;
        Yhr2w:
        NI2bi:
        goto YBYLL;
        dQbuQ:
        fgeL4:
        goto c8jP4;
        KNyGO:
        G4QvN:
        goto jTOlX;
        QzC34:
        goto cqTzT;
        goto NqzH4;
        rx3Bd:
        if (isset($p_options[PCLZIP_OPT_BY_PREG]) && $p_options[PCLZIP_OPT_BY_PREG] != '') {
            goto dI7yr;
        }
        goto SCF85;
        zdpRj:
        CWK3b:
        goto O_leq;
        naocW:
        $i = 0;
        goto KNyGO;
        tPUMh:
        return $v_result;
        goto efcp_;
        fpnnd:
        $v_local_header = array();
        goto EQeCa;
        IFgLU:
        $v_nb_extracted++;
        goto QzC34;
        Of0XY:
        goto maVGj;
        goto YfQtn;
        lkpmZ:
        WD06O:
        goto mnuno;
        L7VnY:
        W0NC9:
        goto zdpRj;
        Vawo0:
        $j = 0;
        goto IsK1s;
        dot0g:
        return $v_result;
        goto IZ2tB;
        efcp_:
        FZ0ec:
        goto BLXSd;
        DceLa:
        $v_found = true;
        goto mGlpA;
        hUZuw:
        @unlink($this->zipname);
        goto KojwT;
        Z3s9b:
        if (!(($v_result = $this->privOpenFd("\162\x62")) != 1)) {
            goto FZ0ec;
        }
        goto tPUMh;
        tKiJt:
    }
    public function privDirCheck($p_dir, $p_is_dir = false)
    {
        goto vuCz2;
        yrnN7:
        if (!($p_parent_dir != $p_dir)) {
            goto sxlHt;
        }
        goto Oio2R;
        vvgJS:
        PclZip::privErrorLog(PCLZIP_ERR_DIR_CREATE_FAIL, "\125\x6e\x61\142\x6c\145\40\164\157\x20\x63\x72\145\x61\x74\145\40\x64\151\x72\145\143\x74\x6f\162\171\x20\47{$p_dir}\47");
        goto BTbDi;
        VTKl7:
        IWQlt:
        goto zz8wx;
        QZ7u7:
        KTJML:
        goto KqTHh;
        e6Ef_:
        if (@mkdir($p_dir, 511)) {
            goto jlRpw;
        }
        goto vvgJS;
        UaGHD:
        xRl4z:
        goto xF0Ny;
        mhUZr:
        $p_dir = substr($p_dir, 0, strlen($p_dir) - 1);
        goto VTKl7;
        KqTHh:
        $p_parent_dir = dirname($p_dir);
        goto yrnN7;
        T0eWP:
        WqpT4:
        goto UaGHD;
        Oio2R:
        if (!($p_parent_dir != '')) {
            goto xRl4z;
        }
        goto cCGfN;
        yrc4l:
        if (!($p_is_dir && substr($p_dir, -1) == "\57")) {
            goto IWQlt;
        }
        goto mhUZr;
        mukCd:
        return $v_result;
        goto apJEC;
        zz8wx:
        if (!(is_dir($p_dir) || $p_dir == '')) {
            goto KTJML;
        }
        goto nIj2l;
        vuCz2:
        $v_result = 1;
        goto yrc4l;
        cCGfN:
        if (!(($v_result = $this->privDirCheck($p_parent_dir)) != 1)) {
            goto WqpT4;
        }
        goto Gsh3g;
        nIj2l:
        return 1;
        goto QZ7u7;
        Gsh3g:
        return $v_result;
        goto T0eWP;
        xF0Ny:
        sxlHt:
        goto e6Ef_;
        xXMbq:
        jlRpw:
        goto mukCd;
        BTbDi:
        return PclZip::errorCode();
        goto xXMbq;
        apJEC:
    }
    public function privMerge(&$p_archive_to_add)
    {
        goto wruzo;
        ocxrm:
        return $v_result;
        goto Tid4s;
        hUUFp:
        HFiBp:
        goto xNaNO;
        UDgR5:
        return $v_result;
        goto GD9Di;
        MiXwV:
        Zi3Bz:
        goto m6P65;
        GD9Di:
        CgEJC:
        goto uOaNw;
        yYAe5:
        $v_zip_temp_name = PCLZIP_TEMPORARY_DIR . uniqid("\x70\143\x6c\172\151\x70\x2d") . "\x2e\x74\155\160";
        goto n76tZ;
        PAkhB:
        $v_comment = $v_central_dir["\x63\x6f\155\x6d\x65\156\164"] . "\x20" . $v_central_dir_to_add["\x63\x6f\155\155\x65\x6e\x74"];
        goto CnCE6;
        kY0Gn:
        goto dGj2a;
        goto V8MFJ;
        PLN__:
        if (!(($v_result = $this->privWriteCentralHeader($v_central_dir["\x65\x6e\164\162\x69\145\163"] + $v_central_dir_to_add["\x65\156\164\162\x69\145\163"], $v_size, $v_offset, $v_comment)) != 1)) {
            goto xmmLZ;
        }
        goto KYJ3b;
        IoGFn:
        goto ocsmf;
        goto OvcjM;
        SzJlX:
        $v_result = 1;
        goto UDgR5;
        Cq6PW:
        KtTs8:
        goto gi1DA;
        H6PNG:
        $v_central_dir = array();
        goto M0GIP;
        iuIqx:
        if (!(($v_result = $p_archive_to_add->privReadEndCentralDir($v_central_dir_to_add)) != 1)) {
            goto mSZyU;
        }
        goto K9n4D;
        UWGOX:
        $v_read_size = $v_size < PCLZIP_READ_BLOCK_SIZE ? $v_size : PCLZIP_READ_BLOCK_SIZE;
        goto A8TSF;
        o5rLq:
        $v_buffer = fread($this->zip_fd, $v_read_size);
        goto HmE6Y;
        wruzo:
        $v_result = 1;
        goto Sef_X;
        DBFKq:
        $v_buffer = fread($p_archive_to_add->zip_fd, $v_read_size);
        goto B_W0w;
        xNaNO:
        $v_central_dir_to_add = array();
        goto iuIqx;
        B_W0w:
        @fwrite($v_zip_temp_fd, $v_buffer, $v_read_size);
        goto U2j64;
        Mmwg_:
        @rewind($this->zip_fd);
        goto p0Shf;
        YnZub:
        $p_archive_to_add->privCloseFd();
        goto yWg5J;
        U2j64:
        $v_size -= $v_read_size;
        goto PZdw2;
        MomJF:
        $this->privCloseFd();
        goto KiZb9;
        kAn66:
        $v_size = $v_central_dir_to_add["\163\x69\172\x65"];
        goto ZWia1;
        zM5Ng:
        @unlink($this->zipname);
        goto t71uK;
        xFgdF:
        xmmLZ:
        goto Jdi4b;
        Z7vcf:
        $p_archive_to_add->privCloseFd();
        goto V6yiK;
        yUVAF:
        return $v_result;
        goto hUUFp;
        K9n4D:
        $this->privCloseFd();
        goto LfjFV;
        Zr0Ew:
        $v_result = $this->privDuplicate($p_archive_to_add->zipname);
        goto JtIu3;
        zO0cI:
        td4Zx:
        goto Cb0Tq;
        IMTD8:
        $v_size -= $v_read_size;
        goto kY0Gn;
        WINZx:
        $this->zip_fd = null;
        goto l8AK7;
        tchq1:
        goto td4Zx;
        goto MiXwV;
        zVcIs:
        return $v_result;
        goto fxQNT;
        OvcjM:
        ltHGZ:
        goto kAn66;
        CnCE6:
        $v_size = @ftell($v_zip_temp_fd) - $v_offset;
        goto cyhGm;
        No8wu:
        return PclZip::errorCode();
        goto eFC8A;
        DY12W:
        $v_read_size = $v_size < PCLZIP_READ_BLOCK_SIZE ? $v_size : PCLZIP_READ_BLOCK_SIZE;
        goto xGkkI;
        vOmzt:
        @rewind($p_archive_to_add->zip_fd);
        goto yYAe5;
        m6P65:
        $v_size = $v_central_dir_to_add["\x6f\x66\x66\x73\145\164"];
        goto Cq6PW;
        n76tZ:
        if (!(($v_zip_temp_fd = @fopen($v_zip_temp_name, "\167\x62")) == 0)) {
            goto BQTJk;
        }
        goto DEkpe;
        gECZf:
        $v_size = $v_central_dir["\163\x69\172\x65"];
        goto bdCJ5;
        EPsN2:
        @fwrite($v_zip_temp_fd, $v_buffer, $v_read_size);
        goto IMTD8;
        KiZb9:
        return $v_result;
        goto zyoef;
        pN5sA:
        $v_size -= $v_read_size;
        goto tchq1;
        RCNsf:
        return $v_result;
        goto xFgdF;
        k8SG8:
        @fwrite($v_zip_temp_fd, $v_buffer, $v_read_size);
        goto q0vxa;
        ZWia1:
        dGj2a:
        goto t7Fn8;
        zyoef:
        jjYOp:
        goto Mmwg_;
        t71uK:
        PclZipUtilRename($v_zip_temp_name, $this->zipname);
        goto ocxrm;
        LB2TL:
        if (!(($v_result = $this->privOpenFd("\162\142")) != 1)) {
            goto myJ0V;
        }
        goto RmC8Z;
        q0vxa:
        $v_size -= $v_read_size;
        goto IoGFn;
        sH39l:
        myJ0V:
        goto H6PNG;
        p0Shf:
        if (!(($v_result = $p_archive_to_add->privOpenFd("\x72\142")) != 1)) {
            goto HFiBp;
        }
        goto sZTdq;
        ZcI2_:
        if (!($v_size != 0)) {
            goto ltHGZ;
        }
        goto DY12W;
        KYJ3b:
        $this->privCloseFd();
        goto V2tNy;
        l8AK7:
        unset($v_header_list);
        goto RCNsf;
        M0GIP:
        if (!(($v_result = $this->privReadEndCentralDir($v_central_dir)) != 1)) {
            goto jjYOp;
        }
        goto MomJF;
        ZkYlw:
        $this->zip_fd = $v_zip_temp_fd;
        goto traXG;
        avMPt:
        @fclose($v_zip_temp_fd);
        goto WINZx;
        xGkkI:
        $v_buffer = @fread($this->zip_fd, $v_read_size);
        goto k8SG8;
        eFC8A:
        BQTJk:
        goto IBuAA;
        V6yiK:
        @fclose($v_zip_temp_fd);
        goto zM5Ng;
        WsgA5:
        qoTtK:
        goto LB2TL;
        EPhJQ:
        $v_zip_temp_fd = $v_swap;
        goto PLN__;
        Cb0Tq:
        if (!($v_size != 0)) {
            goto Zi3Bz;
        }
        goto yANh4;
        YxgKM:
        $v_read_size = $v_size < PCLZIP_READ_BLOCK_SIZE ? $v_size : PCLZIP_READ_BLOCK_SIZE;
        goto DBFKq;
        IBuAA:
        $v_size = $v_central_dir["\157\x66\146\163\x65\x74"];
        goto zO0cI;
        cyhGm:
        $v_swap = $this->zip_fd;
        goto JUbNE;
        sZTdq:
        $this->privCloseFd();
        goto yUVAF;
        HmE6Y:
        @fwrite($v_zip_temp_fd, $v_buffer, $v_read_size);
        goto pN5sA;
        fxQNT:
        mSZyU:
        goto vOmzt;
        yANh4:
        $v_read_size = $v_size < PCLZIP_READ_BLOCK_SIZE ? $v_size : PCLZIP_READ_BLOCK_SIZE;
        goto o5rLq;
        juR6R:
        $this->privCloseFd();
        goto Z7vcf;
        Jdi4b:
        $v_swap = $this->zip_fd;
        goto ZkYlw;
        bdCJ5:
        ocsmf:
        goto ZcI2_;
        A8TSF:
        $v_buffer = @fread($p_archive_to_add->zip_fd, $v_read_size);
        goto EPsN2;
        yWg5J:
        PclZip::privErrorLog(PCLZIP_ERR_READ_OPEN_FAIL, "\125\156\141\x62\154\145\40\x74\157\x20\x6f\x70\145\x6e\40\164\145\x6d\x70\x6f\x72\141\x72\x79\40\x66\x69\154\145\40\x27" . $v_zip_temp_name . "\x27\40\x69\156\40\x62\151\x6e\141\162\171\40\167\x72\151\x74\x65\40\155\x6f\x64\145");
        goto No8wu;
        V2tNy:
        $p_archive_to_add->privCloseFd();
        goto avMPt;
        RmC8Z:
        return $v_result;
        goto sH39l;
        Sef_X:
        if (is_file($p_archive_to_add->zipname)) {
            goto CgEJC;
        }
        goto SzJlX;
        V8MFJ:
        YyG8M:
        goto PAkhB;
        PZdw2:
        goto KtTs8;
        goto cvORY;
        cvORY:
        i0DXb:
        goto YArjj;
        YArjj:
        $v_offset = @ftell($v_zip_temp_fd);
        goto gECZf;
        DEkpe:
        $this->privCloseFd();
        goto YnZub;
        uOaNw:
        if (is_file($this->zipname)) {
            goto qoTtK;
        }
        goto Zr0Ew;
        gi1DA:
        if (!($v_size != 0)) {
            goto i0DXb;
        }
        goto YxgKM;
        LfjFV:
        $p_archive_to_add->privCloseFd();
        goto zVcIs;
        traXG:
        $v_zip_temp_fd = $v_swap;
        goto juR6R;
        JtIu3:
        return $v_result;
        goto WsgA5;
        JUbNE:
        $this->zip_fd = $v_zip_temp_fd;
        goto EPhJQ;
        t7Fn8:
        if (!($v_size != 0)) {
            goto YyG8M;
        }
        goto UWGOX;
        Tid4s:
    }
    public function privDuplicate($p_archive_filename)
    {
        goto EZr_U;
        ddspf:
        pzHl2:
        goto i92d1;
        EZr_U:
        $v_result = 1;
        goto kTA4y;
        Fl3NZ:
        return PclZip::errorCode();
        goto Mt68R;
        b1DFM:
        if (!(($v_zip_temp_fd = @fopen($p_archive_filename, "\x72\142")) == 0)) {
            goto KdMZr;
        }
        goto iD93f;
        Z9ekV:
        PclZip::privErrorLog(PCLZIP_ERR_READ_OPEN_FAIL, "\125\x6e\141\142\x6c\x65\40\x74\x6f\40\157\160\x65\156\40\x61\x72\143\x68\151\166\145\x20\146\x69\154\145\x20\x27" . $p_archive_filename . "\x27\40\151\x6e\x20\142\x69\x6e\x61\x72\x79\x20\x77\162\x69\x74\145\40\x6d\x6f\144\x65");
        goto Fl3NZ;
        UPr1l:
        $v_result = 1;
        goto vnxzC;
        R30jg:
        $v_buffer = fread($v_zip_temp_fd, $v_read_size);
        goto cfv28;
        iD93f:
        $this->privCloseFd();
        goto Z9ekV;
        NRSk6:
        $v_read_size = $v_size < PCLZIP_READ_BLOCK_SIZE ? $v_size : PCLZIP_READ_BLOCK_SIZE;
        goto R30jg;
        YVz3p:
        if (!(($v_result = $this->privOpenFd("\x77\142")) != 1)) {
            goto CG34u;
        }
        goto nsWXx;
        oBKEx:
        return $v_result;
        goto C0o7V;
        OOFMn:
        @fclose($v_zip_temp_fd);
        goto oBKEx;
        P3nMO:
        $v_size -= $v_read_size;
        goto bArI2;
        hkyTL:
        pwz3m:
        goto mM5qK;
        NOui_:
        abAAo:
        goto YVz3p;
        Mt68R:
        KdMZr:
        goto bnizo;
        vnxzC:
        return $v_result;
        goto NOui_;
        ijIfw:
        CG34u:
        goto b1DFM;
        mM5qK:
        $this->privCloseFd();
        goto OOFMn;
        nsWXx:
        return $v_result;
        goto ijIfw;
        kTA4y:
        if (is_file($p_archive_filename)) {
            goto abAAo;
        }
        goto UPr1l;
        bArI2:
        goto pzHl2;
        goto hkyTL;
        cfv28:
        @fwrite($this->zip_fd, $v_buffer, $v_read_size);
        goto P3nMO;
        bnizo:
        $v_size = filesize($p_archive_filename);
        goto ddspf;
        i92d1:
        if (!($v_size != 0)) {
            goto pwz3m;
        }
        goto NRSk6;
        C0o7V:
    }
    public function privErrorLog($p_error_code = 0, $p_error_string = '')
    {
        goto ec4o2;
        Bvbe5:
        $this->error_code = $p_error_code;
        goto RKg_B;
        m6LSs:
        Pzk79:
        goto N0uJM;
        RKg_B:
        $this->error_string = $p_error_string;
        goto eY08t;
        ec4o2:
        if (PCLZIP_ERROR_EXTERNAL == 1) {
            goto Pzk79;
        }
        goto Bvbe5;
        N0uJM:
        PclError($p_error_code, $p_error_string);
        goto L5z3P;
        L5z3P:
        uotJ2:
        goto tlq3_;
        eY08t:
        goto uotJ2;
        goto m6LSs;
        tlq3_:
    }
    public function privErrorReset()
    {
        goto h0SQA;
        j2NOC:
        $this->error_code = 0;
        goto zj7w0;
        aPgcR:
        SZ3CK:
        goto VpYbu;
        y18wF:
        goto SZ3CK;
        goto yLJt9;
        zj7w0:
        $this->error_string = '';
        goto y18wF;
        h0SQA:
        if (PCLZIP_ERROR_EXTERNAL == 1) {
            goto fG8Qo;
        }
        goto j2NOC;
        TM_Rb:
        PclErrorReset();
        goto aPgcR;
        yLJt9:
        fG8Qo:
        goto TM_Rb;
        VpYbu:
    }
    public function privDisableMagicQuotes()
    {
        goto xiXZ2;
        vo1ET:
        VZnde:
        goto uPzpS;
        gI9VS:
        @set_magic_quotes_runtime(0);
        goto c7K5A;
        c7K5A:
        juhto:
        goto N3BFC;
        N3BFC:
        return $v_result;
        goto c1aFL;
        Z7sBj:
        if (!($this->magic_quotes_status == 1)) {
            goto juhto;
        }
        goto gI9VS;
        xiXZ2:
        $v_result = 1;
        goto FhkCK;
        n4o65:
        rFztc:
        goto TRd6O;
        cSlU9:
        return $v_result;
        goto vo1ET;
        FkCng:
        return $v_result;
        goto n4o65;
        FhkCK:
        if (!(!function_exists("\147\145\x74\137\155\x61\147\x69\x63\137\x71\165\x6f\x74\x65\163\x5f\x72\165\x6e\164\x69\x6d\x65") || !function_exists("\x73\x65\164\x5f\x6d\141\147\151\143\x5f\161\x75\157\x74\x65\x73\137\162\x75\x6e\164\151\155\x65"))) {
            goto VZnde;
        }
        goto cSlU9;
        uPzpS:
        if (!($this->magic_quotes_status != -1)) {
            goto rFztc;
        }
        goto FkCng;
        TRd6O:
        $this->magic_quotes_status = @get_magic_quotes_runtime();
        goto Z7sBj;
        c1aFL:
    }
    public function privSwapBackMagicQuotes()
    {
        goto J6bI3;
        mc8Hp:
        qs962:
        goto E99x3;
        xYhJd:
        return $v_result;
        goto sIQ3L;
        LTKht:
        return $v_result;
        goto WEnPN;
        VJrTW:
        return $v_result;
        goto mc8Hp;
        P0bCJ:
        rlvNG:
        goto LTKht;
        LL3Bl:
        @set_magic_quotes_runtime($this->magic_quotes_status);
        goto P0bCJ;
        E99x3:
        if (!($this->magic_quotes_status != -1)) {
            goto F0GNf;
        }
        goto xYhJd;
        sgPzL:
        if (!($this->magic_quotes_status == 1)) {
            goto rlvNG;
        }
        goto LL3Bl;
        J6bI3:
        $v_result = 1;
        goto GQdPm;
        sIQ3L:
        F0GNf:
        goto sgPzL;
        GQdPm:
        if (!(!function_exists("\x67\145\x74\137\155\141\147\151\x63\x5f\x71\x75\157\x74\145\x73\137\162\x75\156\x74\x69\x6d\145") || !function_exists("\163\x65\x74\x5f\155\x61\147\x69\x63\137\161\x75\x6f\164\145\x73\137\162\x75\156\164\151\155\x65"))) {
            goto qs962;
        }
        goto VJrTW;
        WEnPN:
    }
}
goto WxoE4;
Il3Bz:
function PclZipUtilRename($p_src, $p_dest)
{
    goto JvygH;
    JvygH:
    $v_result = 1;
    goto n0fYm;
    yBW2m:
    goto l41QI;
    goto GL3oE;
    xSmO2:
    g0rBv:
    goto opRxG;
    DHbQd:
    return $v_result;
    goto thWYu;
    n0fYm:
    if (@rename($p_src, $p_dest)) {
        goto N6Bin;
    }
    goto Arirs;
    opRxG:
    $v_result = 0;
    goto yBW2m;
    GL3oE:
    T0toL:
    goto oU3fU;
    oU3fU:
    $v_result = 0;
    goto obO53;
    LofsT:
    goto l41QI;
    goto xSmO2;
    wOus1:
    if (!@unlink($p_src)) {
        goto T0toL;
    }
    goto LofsT;
    Otp9v:
    N6Bin:
    goto DHbQd;
    obO53:
    l41QI:
    goto Otp9v;
    Arirs:
    if (!@copy($p_src, $p_dest)) {
        goto g0rBv;
    }
    goto wOus1;
    thWYu:
}
goto JmZun;
AlR0m:
define("\120\x43\x4c\132\111\x50\x5f\105\x52\122\117\122\x5f\x45\130\124\105\x52\116\101\114", 0);
goto AQJol;
HUyy6:
define("\x50\103\x4c\132\x49\120\137\x4f\120\x54\x5f\122\105\120\114\x41\x43\x45\x5f\x4e\x45\x57\x45\x52", 77016);
goto OS0Ur;
WxoE4:
function PclZipUtilPathReduction($p_dir)
{
    goto f9ZAB;
    cG3hl:
    zgYKZ:
    goto DMtTZ;
    asO2i:
    $v_skip = 0;
    goto nBgNc;
    D04WO:
    if ($v_list[$i] == "\56\56") {
        goto NSVCX;
    }
    goto awSRn;
    Lku4f:
    Nfb7e:
    goto o85iq;
    wdkNO:
    $v_result = $p_dir;
    goto qMfmo;
    vrtur:
    if ($v_list[$i] == "\x2e") {
        goto WPe73;
    }
    goto D04WO;
    f9ZAB:
    $v_result = '';
    goto gEIZy;
    cPyTX:
    $v_skip--;
    goto dJvR9;
    tBTtb:
    hr04R:
    goto YNcOo;
    YE5Ad:
    NSVCX:
    goto c52wN;
    xjd40:
    j1yBw:
    goto E0g2E;
    yg2Jv:
    $v_result = "\x2e\x2e\x2f" . $v_result;
    goto RgEuQ;
    awSRn:
    if ($v_list[$i] == '') {
        goto hr04R;
    }
    goto N5DmQ;
    rcYol:
    AwNmt:
    goto DAeep;
    c52wN:
    $v_skip++;
    goto ILtL5;
    B1kn5:
    if (!($v_skip > 0)) {
        goto BMsag;
    }
    goto RPhxF;
    ILtL5:
    goto eGvOg;
    goto tBTtb;
    nBgNc:
    $i = sizeof($v_list) - 1;
    goto Lku4f;
    VbKed:
    $v_result = $v_list[$i] . ($i != sizeof($v_list) - 1 ? "\x2f" . $v_result : '');
    goto FGYm1;
    JEts2:
    JB8Af:
    goto y0HhL;
    DMtTZ:
    $i--;
    goto pSaVM;
    N5DmQ:
    if ($v_skip > 0) {
        goto GOaHn;
    }
    goto VbKed;
    bt9Vh:
    if (!($v_skip > 0)) {
        goto AwNmt;
    }
    goto wdkNO;
    bUQ1T:
    if (!($v_skip > 0)) {
        goto YKWDG;
    }
    goto yg2Jv;
    E0g2E:
    $v_result = $v_list[$i];
    goto temwz;
    gEIZy:
    if (!($p_dir != '')) {
        goto JB8Af;
    }
    goto WeKmZ;
    RgEuQ:
    $v_skip--;
    goto E_bj6;
    FGYm1:
    goto cD54Z;
    goto ya0NF;
    NTLE3:
    goto eGvOg;
    goto YE5Ad;
    qMfmo:
    $v_skip = 0;
    goto rcYol;
    DAeep:
    goto aStb5;
    goto xjd40;
    E_bj6:
    goto utP0l;
    goto bpaa4;
    pSaVM:
    goto Nfb7e;
    goto HnrTU;
    kVjbp:
    if ($i == sizeof($v_list) - 1) {
        goto j1yBw;
    }
    goto shssT;
    XzBAZ:
    goto eGvOg;
    goto iSjIY;
    HnrTU:
    T7_Q3:
    goto B1kn5;
    YNcOo:
    if ($i == 0) {
        goto Pp1xr;
    }
    goto kVjbp;
    iSjIY:
    WPe73:
    goto NTLE3;
    dJvR9:
    cD54Z:
    goto XzBAZ;
    aTbxn:
    eGvOg:
    goto cG3hl;
    temwz:
    aStb5:
    goto aTbxn;
    WeKmZ:
    $v_list = explode("\57", $p_dir);
    goto asO2i;
    Hnd9H:
    $v_result = "\x2f" . $v_result;
    goto bt9Vh;
    gVkhX:
    BMsag:
    goto JEts2;
    bpaa4:
    YKWDG:
    goto gVkhX;
    RPhxF:
    utP0l:
    goto bUQ1T;
    y0HhL:
    return $v_result;
    goto weIEg;
    ya0NF:
    GOaHn:
    goto cPyTX;
    shssT:
    goto aStb5;
    goto P1bZw;
    o85iq:
    if (!($i >= 0)) {
        goto T7_Q3;
    }
    goto vrtur;
    P1bZw:
    Pp1xr:
    goto Hnd9H;
    weIEg:
}
goto mt_KH;
zguS3:
define("\120\103\114\x5a\111\x50\x5f\x4f\x50\x54\137\x41\x44\104\x5f\x50\x41\124\110", 77002);
goto JeUoU;
GYW3l:
if (defined("\x50\x43\114\132\111\x50\x5f\123\x45\x50\x41\122\101\124\x4f\x52")) {
    goto VHoR8;
}
goto A2Ys_;
LgIey:
define("\x50\x43\x4c\132\111\120\137\x4f\120\x54\137\101\x44\104\x5f\x43\117\115\115\x45\116\x54", 77013);
goto faQT3;
MVmQE:
define("\x50\103\x4c\132\111\120\x5f\105\122\x52\137\102\101\x44\x5f\x45\130\x54\x45\116\x53\111\117\x4e", -9);
goto qJxnn;
Gkmlw:
define("\120\103\114\132\x49\120\137\x52\105\x41\104\137\102\114\117\x43\x4b\x5f\123\x49\132\x45", 2048);
goto UuOLv;
W9S9U:
define("\120\103\114\x5a\111\120\x5f\x45\x52\122\137\115\x49\x53\x53\111\x4e\x47\137\x4f\120\x54\x49\x4f\x4e\x5f\x56\101\x4c\125\x45", -15);
goto IlTHs;
dB1Ud:
define("\x50\x43\x4c\132\111\x50\137\x4f\120\x54\x5f\124\105\115\x50\x5f\106\x49\114\x45\x5f\117\106\x46", 77022);
goto GoUxr;
CpXbH:
define("\x50\x43\114\x5a\111\x50\137\124\105\115\x50\x4f\x52\x41\122\131\x5f\106\111\x4c\x45\x5f\x52\x41\x54\111\117", 0.47);
goto IWwH1;
uvcnN:
define("\120\103\114\132\111\120\x5f\105\x52\x52\x5f\x49\116\126\x41\x4c\x49\104\137\101\122\103\x48\x49\x56\x45\x5f\132\111\120", -14);
goto W9S9U;
H9k16:
if (defined("\120\x43\x4c\x5a\x49\120\137\124\105\x4d\120\117\x52\101\122\x59\137\104\111\122")) {
    goto rjHO0;
}
goto bRTX0;
nsxA8:
define("\120\x43\x4c\x5a\111\120\x5f\x41\x54\x54\x5f\106\111\114\105\137\116\105\x57\137\x53\x48\117\122\x54\137\116\101\x4d\x45", 79002);
goto OTpH6;
yGwkR:
if (defined("\x50\103\114\x5a\x49\120\137\124\x45\x4d\120\x4f\122\101\x52\x59\137\106\x49\114\105\137\x52\101\124\111\117")) {
    goto Pz3v1;
}
goto CpXbH;
lzOo1:
define("\120\103\x4c\132\x49\120\137\117\x50\124\137\x42\131\x5f\x4e\101\x4d\105", 77008);
goto paWBR;
eAPdp:
define("\x50\103\x4c\132\x49\x50\137\103\x42\137\x50\x52\x45\137\x45\130\124\122\x41\103\x54", 78001);
goto T0oIr;
CFJ24:
define("\120\x43\114\132\111\120\x5f\x4f\120\124\x5f\102\131\137\105\x52\105\107", 77010);
goto QLqDv;
vfTeS:
define("\x50\x43\114\132\x49\x50\x5f\x45\122\x52\x5f\125\x4e\x53\x55\x50\120\117\x52\x54\x45\x44\x5f\x43\x4f\x4d\x50\122\x45\123\123\111\117\x4e", -18);
goto QLi8U;
aqag4:
define("\x50\x43\x4c\132\x49\x50\x5f\x45\x52\122\x5f\106\111\x4c\105\116\x41\115\105\137\x54\117\x4f\137\114\117\116\x47", -5);
goto zJu3V;
QLqDv:
define("\120\x43\114\132\x49\x50\x5f\117\120\x54\x5f\102\x59\x5f\x50\122\105\107", 77011);
goto lMAi1;
paWBR:
define("\120\x43\x4c\x5a\x49\x50\137\117\120\124\137\x42\x59\137\111\x4e\104\x45\x58", 77009);
goto CFJ24;
hkfje:
define("\x50\x43\114\x5a\111\120\x5f\117\x50\x54\x5f\101\104\104\137\x54\105\x4d\120\x5f\x46\x49\114\105\137\x54\110\122\x45\123\110\x4f\114\104", 77020);
goto TzfrW;
dhypG:
define("\x50\103\x4c\132\111\120\x5f\117\x50\124\x5f\116\x4f\137\103\x4f\115\x50\122\x45\x53\123\111\117\116", 77007);
goto lzOo1;
UuOLv:
ocESn:
goto GYW3l;
mt_KH:
function PclZipUtilPathInclusion($p_dir, $p_path)
{
    goto Yg2rO;
    Bk1DM:
    if (!($p_path == "\x2e" || strlen($p_path) >= 2 && substr($p_path, 0, 2) == "\x2e\x2f")) {
        goto DI8vT;
    }
    goto Gmtin;
    vfQBe:
    QxLZy:
    goto f10T_;
    oCwIJ:
    dVcIi:
    goto vbUsR;
    aWljn:
    $j++;
    goto Q1ZGx;
    I9XhE:
    if (!$v_result) {
        goto Zm_2t;
    }
    goto qRHfC;
    Mr0yX:
    if (!($v_list_path[$j] == '')) {
        goto P7bZY;
    }
    goto GOBcv;
    bvt8v:
    $i++;
    goto h8wxl;
    Yg2rO:
    $v_result = 1;
    goto LunKt;
    LunKt:
    if (!($p_dir == "\x2e" || strlen($p_dir) >= 2 && substr($p_dir, 0, 2) == "\x2e\x2f")) {
        goto GMT5r;
    }
    goto bSM8O;
    DHuSg:
    cenB5:
    goto Ys8vK;
    oOGK1:
    if (!($v_list_dir[$i] == '')) {
        goto eADGZ;
    }
    goto V0hV1;
    Tlmxq:
    $j = 0;
    goto DHuSg;
    Dani5:
    $v_list_dir = explode("\x2f", $p_dir);
    goto NbaFh;
    HAZve:
    $v_result = 0;
    goto XJeFJ;
    dsvVH:
    goto HpSR9;
    goto Uw2Ld;
    UOgWU:
    $i++;
    goto hG_a3;
    h8wxl:
    goto AT31q;
    goto CQl2o;
    hG_a3:
    $j++;
    goto cFOr3;
    DafiF:
    if (!($i < $v_list_dir_size && $v_list_dir[$i] == '')) {
        goto EC2pw;
    }
    goto bvt8v;
    uMs2J:
    eADGZ:
    goto Mr0yX;
    zI6g3:
    P7bZY:
    goto njhUr;
    iMS3w:
    if ($i < $v_list_dir_size) {
        goto dVcIi;
    }
    goto dsvVH;
    GOBcv:
    $j++;
    goto p3L9B;
    FJ1zt:
    GMT5r:
    goto Bk1DM;
    CQl2o:
    EC2pw:
    goto pzcGZ;
    pzcGZ:
    if ($i >= $v_list_dir_size && $j >= $v_list_path_size) {
        goto ffhz8;
    }
    goto iMS3w;
    jarF_:
    return $v_result;
    goto R7NHE;
    lFah3:
    $i = 0;
    goto Tlmxq;
    G5Fn2:
    $v_result = 2;
    goto fd1jh;
    NbaFh:
    $v_list_dir_size = sizeof($v_list_dir);
    goto IROwf;
    cRDYv:
    $v_list_path_size = sizeof($v_list_path);
    goto lFah3;
    n6Oki:
    DI8vT:
    goto Dani5;
    njhUr:
    if (!($v_list_dir[$i] != $v_list_path[$j] && $v_list_dir[$i] != '' && $v_list_path[$j] != '')) {
        goto UhEuz;
    }
    goto HAZve;
    V0hV1:
    $i++;
    goto e9Tnq;
    f10T_:
    AT31q:
    goto DafiF;
    VgFXf:
    Zm_2t:
    goto jarF_;
    fd1jh:
    goto HpSR9;
    goto oCwIJ;
    XJeFJ:
    UhEuz:
    goto UOgWU;
    pNEjL:
    HpSR9:
    goto VgFXf;
    p3L9B:
    goto cenB5;
    goto zI6g3;
    vMyqa:
    if (!($j < $v_list_path_size && $v_list_path[$j] == '')) {
        goto QxLZy;
    }
    goto aWljn;
    Ys8vK:
    if (!($i < $v_list_dir_size && $j < $v_list_path_size && $v_result)) {
        goto vm3yL;
    }
    goto oOGK1;
    IROwf:
    $v_list_path = explode("\57", $p_path);
    goto cRDYv;
    Gmtin:
    $p_path = PclZipUtilTranslateWinPath(getcwd(), false) . "\57" . substr($p_path, 1);
    goto n6Oki;
    vbUsR:
    $v_result = 0;
    goto pNEjL;
    Q1ZGx:
    goto vt6GZ;
    goto vfQBe;
    SeHRA:
    vm3yL:
    goto I9XhE;
    cFOr3:
    goto cenB5;
    goto SeHRA;
    qRHfC:
    vt6GZ:
    goto vMyqa;
    e9Tnq:
    goto cenB5;
    goto uMs2J;
    bSM8O:
    $p_dir = PclZipUtilTranslateWinPath(getcwd(), false) . "\x2f" . substr($p_dir, 1);
    goto FJ1zt;
    Uw2Ld:
    ffhz8:
    goto G5Fn2;
    R7NHE:
}
goto loQDZ;
OfFWR:
define("\x50\103\114\132\111\x50\x5f\x4f\120\x54\137\x52\x45\x4d\x4f\x56\x45\x5f\101\114\x4c\x5f\x50\x41\124\x48", 77004);
goto qwS2i;
nzhFG:
define("\120\103\114\132\x49\x50\x5f\117\120\x54\x5f\x54\x45\115\120\x5f\x46\111\114\x45\137\x54\x48\122\x45\123\x48\x4f\x4c\x44", 77020);
goto hkfje;
IWwH1:
Pz3v1:
goto GKilr;
qwS2i:
define("\x50\x43\x4c\132\111\x50\x5f\117\x50\x54\137\x53\x45\124\x5f\103\x48\115\x4f\x44", 77005);
goto Il0BN;
Q73WL:
define("\x50\103\114\132\111\120\137\105\x52\122\137\102\x41\x44\x5f\x45\130\124\122\101\x43\124\x45\x44\x5f\106\111\114\105", -7);
goto epA7v;
JmZun:
function PclZipUtilOptionText($p_option)
{
    goto aXtid;
    ZG17Z:
    $v_result = "\125\x6e\153\156\x6f\x77\156";
    goto Aby21;
    vzzMn:
    Xd4G6:
    goto AuvM4;
    D9vIy:
    reset($v_list);
    goto wUqIb;
    ydpBa:
    a9m1B:
    goto vzzMn;
    aXtid:
    $v_list = get_defined_constants();
    goto D9vIy;
    wUqIb:
    uLbWQ:
    goto WMQ5I;
    dx9RM:
    if (!(($v_prefix == "\120\x43\x4c\132\111\x50\x5f\x4f\120\124" || $v_prefix == "\x50\103\114\132\111\120\x5f\x43\102\x5f" || $v_prefix == "\x50\103\x4c\x5a\111\x50\137\x41\x54\124") && $v_list[$v_key] == $p_option)) {
        goto a9m1B;
    }
    goto aSCKt;
    WMQ5I:
    if (!($v_key = key($v_list))) {
        goto Q1O_a;
    }
    goto ph9kX;
    ph9kX:
    $v_prefix = substr($v_key, 0, 10);
    goto dx9RM;
    aSCKt:
    return $v_key;
    goto ydpBa;
    AuvM4:
    next($v_list);
    goto zbC6l;
    dIfdJ:
    Q1O_a:
    goto ZG17Z;
    zbC6l:
    goto uLbWQ;
    goto dIfdJ;
    Aby21:
    return $v_result;
    goto U7YLI;
    U7YLI:
}
goto MkFdr;
qPaG5:
define("\120\x43\x4c\132\111\x50\137\105\122\122\137\116\117\137\x45\122\x52\x4f\122", 0);
goto i9eoj;
Og4s4:
define("\x50\103\x4c\132\x49\x50\x5f\x45\x52\x52\137\111\x4e\x56\101\x4c\x49\104\137\101\x54\124\122\111\x42\125\124\105\x5f\126\101\114\125\105", -20);
goto SxduU;
epA7v:
define("\120\x43\114\132\111\x50\137\105\x52\122\137\x44\x49\122\x5f\x43\122\105\x41\x54\x45\137\106\x41\x49\114", -8);
goto MVmQE;
MkFdr:
function PclZipUtilTranslateWinPath($p_path, $p_remove_disk_letter = true)
{
    goto gqD5d;
    LGfSq:
    Z8mdn:
    goto PrWlq;
    vA0ed:
    if (!($p_remove_disk_letter && ($v_position = strpos($p_path, "\72")) != false)) {
        goto Fwn6M;
    }
    goto VFgCT;
    bM8iK:
    Fwn6M:
    goto rA73e;
    PrWlq:
    DHN78:
    goto SQD1_;
    SQD1_:
    return $p_path;
    goto ZEcK8;
    q81Am:
    $p_path = strtr($p_path, "\x5c", "\57");
    goto LGfSq;
    gqD5d:
    if (!stristr(php_uname(), "\x77\x69\156\x64\157\x77\x73")) {
        goto DHN78;
    }
    goto vA0ed;
    rA73e:
    if (!(strpos($p_path, "\x5c") > 0 || substr($p_path, 0, 1) == "\134")) {
        goto Z8mdn;
    }
    goto q81Am;
    VFgCT:
    $p_path = substr($p_path, $v_position + 1);
    goto bM8iK;
    ZEcK8:
}
