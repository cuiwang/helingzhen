<?php
/**
 * 音乐盒子
 *
 * 作者:迷失卍国度
 *
 * qq:15595755
 */
defined('IN_IA') or exit('Access Denied');
define('RES', '../addons/weisrc_audio/template/');

class weisrc_audioModuleSite extends WeModuleSite
{
    public $name = 'weisrc_audioModule';
    public $title = '音乐盒子';
    public $modulename = 'weisrc_audio';

    public $_weid = '';
    public $_fromuser = '';
    public $_nickname = '';
    public $_headimgurl = '';

    function __construct()
    {
        global $_W, $_GPC;
        $this->_fromuser = $_W['fans']['from_user'];//debug
        $this->_weid = $_W['uniacid'];
    }

    public function doMobileIndex()
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;

        $bg_base_url = "../addons/weisrc_audio/template/images/bg/";

        $bgname = 'bg0.jpg';
        $setting = pdo_fetch("SELECT * FROM ".tablename('weisrc_audio_setting') . " WHERE weid=:weid", array(':weid' => $_W['weid']));

        if (!empty($setting)) {
            if ($setting['bg_setting'] == 1) { //自定义
                if (!empty($setting['bg_url'])) {

                    $bgname = $_W['attachurl'].$setting['bg_url'];

                }
            } else {
                if ($setting['bg_rand'] == 0) {
                    $bgname = $setting['bg'];
                } else { //随机
                    $bgname = $this->get_cookie('bg');
                    if (empty($bgname)) {
                        $tpl = dir(IA_ROOT.'/addons/weisrc_audio/template/images/bg/');
                        $tpl->handle;
                        $theme_array = array();
                        while ($entry = $tpl->read()) {
                            if(preg_match("/^[a-zA-Z0-9]/", $entry) && $entry != 'common' && $entry != 'photo') {
                                array_push($theme_array, $entry);
                            }
                        }
                        $tpl->close();
                    }
                    $rand_keys = array_rand($theme_array);
                    $bgname = $theme_array[$rand_keys];
                }
                $bgname = $bg_base_url.$bgname;
            }
        }
        include $this->template('audio');
    }

    public function doMobileGetData()
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;

        //所有歌曲列表

        $g_show["error"] = 0;
        if ($_GPC['type'] == "list") {
            //计算总数
            $count = pdo_fetchcolumn("select COUNT(*)  from " . tablename('weisrc_audio_music') . " where status=1 AND weid=:weid", array(':weid' => $weid));
            if ($count) {
                //定义歌曲列表数组
                $music_list = array();
                //每页显示5首歌曲
                $page_num = 5;
                //当前页码
                $page = $_GPC["page"];
                //起始歌曲记录号
                $from_record = ($page - 1) * $page_num;
                //获取歌曲列表

                $music_list = pdo_fetchall("select * from " . tablename('weisrc_audio_music') . " WHERE  status=1 AND weid=:weid ORDER BY  displayorder desc,mid desc limit $from_record,$page_num", array(':weid' => $weid));

                foreach ($music_list as $key => $value) {
                    $music_list[$key]['cover'] = strpos($value['cover'], 'http') === false ? $_W['attachurl'] . $value['cover'] : $value['cover'];
                }

                //检测是否还有下页
                $music_next = 0;
                $real_page = @ceil($count / $page_num);
                if ($real_page > 1) {
                    if ($page >= $real_page) {
                        $music_next = 0;
                    } else {
                        $music_next = $page + 1;
                    }
                }
                $music_prev = $page - 1;

                //返回数组赋值
                $g_show["music_list"] = $music_list;
                $g_show["music_next"] = $music_next;
                $g_show["music_prev"] = $music_prev;
                $g_show["page"] = $page;
                $g_show["real_page"] = $real_page;
            } else {
                $g_show["error"] = 1;
                $g_show["errmsg"] = "歌曲列表获取出错，请返回对话框后重新进入";
                //throw new Exception("歌曲列表获取出错，请返回对话框后重新进入");
            }
        } elseif ($_GPC['type'] == "like_list") { //喜欢歌曲列表
            //判断是否获取到用户Openid
            if ($_GPC['from_user']) {
                $count = pdo_fetchcolumn("select COUNT(*)  from " . tablename('weisrc_audio_music_user') . " where openid='" . $from_user . "' and status=1 AND weid=:weid", array(':weid' => $weid));

                if ($count) {
                    //定义歌曲列表数组
                    $music_list = array();
                    //每页显示5首歌曲
                    $page_num = 5;
                    //当前页码
                    $page = $_GPC["page"];
                    //起始歌曲记录号
                    $from_record = ($page - 1) * $page_num;
                    //获取歌曲列表
                    $music_list = pdo_fetchall("select * from " . tablename('weisrc_audio_music_user') . " where openid='" . $from_user . "' AND status=1 AND weid=:weid ORDER BY dateline desc limit $from_record,$page_num", array(':weid' => $weid));

                    //检测是否还有下页
                    $music_next = 0;
                    $real_page = @ceil($count / $page_num);
                    if ($real_page > 1) {
                        if ($page >= $real_page) {
                            $music_next = 0;
                        } else {
                            $music_next = $page + 1;
                        }
                    }

                    $music_prev = $page - 1;

                    //返回数组赋值
                    $g_show["music_list"] = $music_list;
                    $g_show["music_next"] = $music_next;
                    $g_show["music_prev"] = $music_prev;
                    $g_show["page"] = $page;
                    $g_show["real_page"] = $real_page;

                } else {
                    //没有获取到列表错误提示
                    $g_show["error"] = 1;
                    $g_show["errmsg"] = "你还在播放器里没有喜欢的歌曲列表，请点击爱心选择你钟爱的歌曲先";
                }
            } else {
                //没有获取到openid的提示
                $g_show["error"] = 1;
                $g_show["errmsg"] = "请先关注公众号后再使用此功能！";
            }
        } elseif ($_GPC['type'] == "like") { //用户点击喜欢歌曲
            //监测是否获取openid和歌曲id
            if ($_GPC['from_user'] && $_GPC["song_id"]) {
                //获取用户是否已经对这首歌点击过喜欢
                $like_song = pdo_fetch("select *  from " . tablename('weisrc_audio_music_user') . " where openid='" . $from_user . "' and mid=" . $_GPC["song_id"]);

                //如果已经点击过
                if ($like_song) {
                    //判断当前是喜欢还是不喜欢，如果是喜欢则变为不喜欢
                    $like_flag = ($like_song["status"] == 1) ? 0 : 1;
                    //更新喜欢状态
                    pdo_query("update " . tablename('weisrc_audio_music_user') . " set status=$like_flag where openid='" . $from_user . "' and mid=" . $_GPC["song_id"]);
                } else {
                    //没有表态过则先获取歌曲信息
                    $current_song = pdo_fetch("select * from " . tablename('weisrc_audio_music') . " where status=1 and mid=" . $_GPC["song_id"] . " limit 0,1");
                    $data = array(
                        'weid' => $_W['weid'],
                        'openid' => $_GPC['from_user'],
                        'dateline' => TIMESTAMP,
                        'mid' => $current_song["mid"],
                        'title' => $current_song["title"],
                        'cover' => strpos($current_song['cover'], 'http') === false ? $_W['attachurl'] . $current_song['cover'] : $current_song['cover'],
                        'singer' => $current_song["singer"],
                        'intro' => $current_song["intro"],
                        'status' => 1
                    );

                    pdo_insert('weisrc_audio_music_user', $data);
                    //设定表态标识为喜欢
                    $like_flag = 1;
                }

                $count = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename('weisrc_audio_music_user') . " WHERE mid=:mid AND weid=:weid AND status=1", array(':mid' => $_GPC["song_id"], ':weid' => $weid));
                pdo_query("update " . tablename('weisrc_audio_music') . " set collect=$count where mid=" . $_GPC["song_id"]);

                //返回数组赋值
                $g_show["like_flag"] = $like_flag;
            } else {
                //没有获取到openid的提示
                $g_show["error"] = 1;
                $g_show["errmsg"] = "请先关注公众号后再使用此功能！";
            }
        } else {
            //播放界面
            //如果有歌曲ID则获取歌曲ID
            if ($_GPC["song_id"] > 0) {
                $current_song = pdo_fetch("select * from " . tablename('weisrc_audio_music') . " where status=1  and mid=" . $_GPC["song_id"] . "   AND weid=:weid limit 0,1", array(':weid' => $weid));
            } elseif ($_GPC["song_id"] == "-1") { //如果歌曲播放到最后则循环
                $current_song = pdo_fetch("select * from " . tablename('weisrc_audio_music') . " where status=1  AND weid=:weid order by mid asc limit 0,1", array(':weid' => $weid));
            } else { //如果没有歌曲ID则获取最新歌曲
                $current_song = pdo_fetch("select * from " . tablename('weisrc_audio_music') . " where  status=1  AND weid=:weid order by mid desc limit 0,1", array(':weid' => $weid));
            }
            //获取到当前播放歌曲之后获取上一首和下一首
            if ($current_song) {
                //获取前后歌曲
                $prev = pdo_fetch("select mid from " . tablename('weisrc_audio_music') . " where status=1 and mid<$current_song[mid] AND weid=:weid order by mid desc limit 0,1", array(':weid' => $weid));
                $prev_id = intval($prev['mid']);

                $next = pdo_fetch("select mid from " . tablename('weisrc_audio_music') . " where status=1 and mid>$current_song[mid] AND weid=:weid order by mid asc limit 0,1", array(':weid' => $weid));
                $next_id = intval($next['mid']);
                if (!$prev_id) {
                    $prev_id = 0;
                }
                if (!$next_id) {
                    $next_id = 0;
                }
            } else {
                $g_show["error"] = 1;
                $g_show["errmsg"] = "歌曲获取出错，请返回对话框后重新进入";
            }

            //检查歌曲是否like
            $like_song = 0;
            if ($_GPC['from_user']) {
                $like_song = pdo_fetchcolumn("select COUNT(*)  from " . tablename('weisrc_audio_music_user') . " where openid='" . $from_user . "' and status=1 and mid=$current_song[mid]");
            }

            //返回数组赋值
            $g_show["songtitle"] = $current_song["title"];
            $g_show["auther"] = $current_song["singer"];
            $g_show["songintro"] = $current_song["intro"];
            $g_show["cover"] = strpos($current_song['cover'], 'http') === false ? $_W['attachurl'] . $current_song['cover'] : $current_song['cover'];
            $g_show["url"] = strpos($current_song['url'], 'http') === false ? $_W['attachurl'] . $current_song['url'] : $current_song['url'];
            $g_show["prev_id"] = $prev_id;
            $g_show["next_id"] = $next_id;
            $g_show["current_id"] = $current_song["mid"];
            $g_show["like_song"] = intval($like_song);
        }

        if ($_GPC['backtype'] == "serialize") {
            echo serialize($g_show);
        } elseif ($_GPC['backtype'] == "json") {
            echo json_encode($g_show);
        } elseif ($_GPC['backtype'] == "jsonp") {
            echo $_GPC['callback'] . "(" . json_encode($g_show) . ")";
        } else {
            echo serialize($g_show);
        }
    }

    public function insert_cookie($key, $data)
    {
        global $_W, $_GPC;
        $session = base64_encode(json_encode($data));
        isetcookie($key, $session, 1 * 86400);
    }

    public function get_cookie($key)
    {
        global $_W;
        $key = $_W['config']['cookie']['pre'] . $key;
        return json_decode(base64_decode($_COOKIE[$key]), true);
    }

    public function doMobileVersion()
    {
        echo $this->version;
    }
    private $version = '2878*';

    /**
     * 后台
     */
    //音乐管理
    public function doWebMusic()
    {
        global $_W, $_GPC;
        checklogin();
        $action = 'music';
        $title = '音乐管理';
        $url = $this->createWebUrl($action, array('op' => 'display'));

        load()->func('tpl');

        $where = "WHERE weid = '{$_W['weid']}'";

        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        if ($operation == 'display') {
            if (checksubmit('submit')) { //排序
                if (is_array($_GPC['displayorder'])) {
                    foreach ($_GPC['displayorder'] as $id => $val) {
                        $data = array('displayorder' => intval($_GPC['displayorder'][$id]));
                        pdo_update($this->modulename . '_music', $data, array('mid' => $id, 'weid' => $_W['weid']));
                    }
                }
                message('操作成功!', $url);
            }

            $keyword = trim($_GPC['keyword']);
            if (!empty($keyword)) {
                $where = " WHERE (singer like '%{$keyword}%' OR title like '%{$keyword}%') AND weid=".$_W['weid'];
            }

            $pindex = max(1, intval($_GPC['page']));
            $psize = 15;

            $list = pdo_fetchall("SELECT * FROM " . tablename($this->modulename . '_music') . " {$where} order by displayorder desc,mid desc LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
            if (!empty($list)) {
                $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->modulename . '_music') . " $where");
                $pager = pagination($total, $pindex, $psize);
            }
            include $this->template('music');

        } elseif ($operation == 'post') {
            $id = intval($_GPC['id']);
            $reply = pdo_fetch("select * from " . tablename($this->modulename . '_music') . " where mid=:mid and weid =:weid", array(':mid' => $id, ':weid' => $_W['weid']));

            if (!empty($id)) {
                if (empty($reply)) {
                    message('抱歉，数据不存在或是已经删除！', '', 'error');
                }
            }
            if (!empty($reply)) {
                if (!empty($reply['cover'])) {
                    if (strpos($reply['cover'], 'http') === false) {
                        $cover = $_W['attachurl'] . $reply['cover'];
                    } else {
                        $cover = $reply['cover'];
                    }
                } else {
                    $cover = '';
                }

                if (strpos($reply['url'], 'http') === false) {
                    $music_url = $_W['attachurl'] . $reply['url'];
                } else {
                    $music_url = $reply['url'];
                }
            } else {
                $cover = '';
            }

            if (checksubmit('submit')) {
                $data = array(
                    'weid' => intval($_W['weid']),
                    'url' => trim($_GPC['url']),
                    'title' => trim($_GPC['title']),
                    'singer' => trim($_GPC['singer']),
                    'intro' => trim($_GPC['intro']),
                    'cover' => trim($_GPC['cover']),
                    'status' => 1,
                    'dateline' => TIMESTAMP
                );

                if (istrlen($data['title']) == 0) {
                    message('没有输入标题.', '', 'error');
                }
                if (istrlen($data['title']) > 30) {
                    message('标题不能多于30个字。', '', 'error');
                }
                if (istrlen($data['url']) == 0) {
                    message('没有网址.', '', 'error');
                }
                if (istrlen($data['singer']) == 0) {
                    message('没有输入歌手.', '', 'error');
                }
                if (istrlen($data['intro']) == 0) {
                    message('请输入歌曲介绍.', '', 'error');
                }

                if (!empty($reply)) {
                    unset($data['dateline']);
                    pdo_update($this->modulename . '_music', $data, array('mid' => $id, 'weid' => $_W['weid']));
                } else {
                    pdo_insert($this->modulename . '_music', $data);
                }
                message('操作成功!', $url);
            }
            include $this->template('music');

        } elseif ($operation == 'delete') {
            $id = intval($_GPC['id']);
            $music = pdo_fetch("SELECT mid FROM " . tablename($this->modulename . '_music') . " WHERE mid = '$id'");
            if (empty($music)) {
                message('抱歉，不存在或是已经被删除！', $this->createWebUrl('music', array('op' => 'display')), 'error');
            }
            $row_count = pdo_delete($this->modulename . '_music', array('mid' => $id, 'weid' => $_W['weid']));
            if ($row_count > 0) {
                pdo_delete($this->modulename . '_music_user', array('mid' => $id, 'weid' => $_W['weid']));
            }
            message('删除成功！', $this->createWebUrl('music', array('op' => 'display')), 'success');
        }
    }

    //上传音乐
    public function doWebUploadMusic()
    {
        global $_W, $_GPC;
        checklogin();
        if (!empty($_FILES['attachFile']['name'])) {
            if ($_FILES['attachFile']['error'] != 0) {
                $result['message'] = '上传失败，请重试！';
                exit(json_encode($result));
            }
            $_W['uploadsetting'] = array();
            $_W['uploadsetting']['music']['folder'] = 'music/' . $_W['weid'];
            $_W['uploadsetting']['music']['extentions'] = $_W['config']['upload']['music']['extentions'];
            $_W['uploadsetting']['music']['limit'] = $_W['config']['upload']['music']['limit'];
            $file = file_upload($_FILES['attachFile'], 'music');
            if (is_error($file)) {
                $result['message'] = $file['message'];
                exit(json_encode($result));
            }
            $result['url'] = $file['url'];
            $result['error'] = 0;
            $result['filename'] = $file['path'];
            $result['url'] = $_W['attachurl'].$result['filename'];
            pdo_insert('attachment', array(
                'weid' => $_W['weid'],
                'uid' => $_W['uid'],
                'filename' => $_FILES['attachFile']['name'],
                'attachment' => $result['filename'],
                'type' => 1,
                'createtime' => TIMESTAMP,
            ));
            exit(json_encode($result));
        } else {
            $result['message'] = '请选择要上传的音频文件！';
            exit(json_encode($result));
        }
    }

    public function doWebSetting()
    {
        global $_W, $_GPC;

        load()->func('tpl');

        $tpl = dir(IA_ROOT.'/addons/weisrc_audio/template/images/bg/');
        $tpl->handle;
        $theme_array = array();
        while ($entry = $tpl->read()) {
            if(preg_match("/^[a-zA-Z0-9]/", $entry) && $entry != 'common' && $entry != 'photo') {
                array_push($theme_array, $entry);
            }
        }
        $tpl->close();

        $setting = pdo_fetch("SELECT * FROM ".tablename('weisrc_audio_setting') . " WHERE weid=:weid", array(':weid' => $_W['weid']));

        if (!empty($setting)) {
            if (!empty($setting['bg_url'])) {
                if (strpos($setting['bg_url'], 'http') === false) {
                    $bg_url = $_W['attachurl'] . $setting['bg_url'];
                } else {
                    $bg_url = $setting['bg_url'];
                }
            } else {
                $bg_url = '';
            }
        } else {
            $bg_url = '';
        }

        $data = array(
            'weid' => $_W['weid'],
            'title' => trim($_GPC['title']),
            'bg' => $_GPC['bgname'],
            'bg_rand' => intval($_GPC['bg_rand']),
            'bg_setting' => intval($_GPC['bg_setting']),
            'bg_url' => $_GPC['bg_url'],
        );

        if($_W['ispost'] && $_W['isajax']) { //更新背景
            if(!in_array($_GPC['bgname'], $theme_array)) {
                exit('不存在该模板.');
            }

            if (empty($setting)) {
                $data['title'] = '音乐盒子';
                $data['dateline'] = TIMESTAMP;
                pdo_insert('weisrc_audio_setting', $data);
                exit('success.');
            } else {
                $site = array(
                    'bg' => $_GPC['bgname']
                );

                if (pdo_update('weisrc_audio_setting', $site, array('weid' => $_W['weid']))) {
                    exit('success.');
                } else {
                    exit('bad.');
                }
            }
        } else {
            if (checksubmit()) {
                if (empty($setting)) {
                    pdo_insert('weisrc_audio_setting', $data);
                } else {
                    pdo_update('weisrc_audio_setting', $data, array('weid' => $_W['weid']));
                }
                message('操作成功', 'refresh', 'success');
            }
        }
        include $this->template('setting');
    }

    public function doWebUploadExcel()
    {
        global $_GPC, $_W;

        if($_GPC['leadExcel'] == "true")
        {
            $filename = $_FILES['inputExcel']['name'];
            $tmp_name = $_FILES['inputExcel']['tmp_name'];

            $flag = $this->checkUploadFileMIME($_FILES['inputExcel']);
            if ($flag == 0) {
                message('文件格式不对.');
            }

            if (empty($tmp_name)) {
                message('请选择要导入的Excel文件！');
            }

            $msg = $this->uploadFile($filename, $tmp_name, $_GPC);

            if ($msg == 1) {
                message('导入成功！', referer(), 'success');
            } else {
                message($msg, '', 'error');
            }
        }
    }

    function uploadFile($file, $filetempname, $array)
    {
        //自己设置的上传文件存放路径
        $filePath = '../addons/weisrc_audio/upload/';

        include 'plugin/phpexcelreader/reader.php';

        $data = new Spreadsheet_Excel_Reader();
        $data->setOutputEncoding('utf-8');
        //注意设置时区
        $time = date("y-m-d-H-i-s"); //去当前上传的时间
        $extend = strrchr($file, '.');
        //上传后的文件名
        $name = $time . $extend;
        $uploadfile = $filePath . $name; //上传后的文件名地址

        //$filetype = $_FILES['fileexcel']['type'];

        if (copy($filetempname, $uploadfile)) {
            if (!file_exists($filePath)) {
                echo '文件路径不存在.';
                return;
            }
            if (!is_readable($uploadfile)) {
                echo ("文件为只读,请修改文件相关权限.");
                return;
            }
            $data->read($uploadfile);
            error_reporting(E_ALL ^ E_NOTICE);
            $count = 0;

            for ($i = $data->sheets[0]['numRows']; $i >= 2 ; $i--) { //$=2 第二行开始
                $row = $data->sheets[0]['cells'][$i];
                if ($array['ac'] == "music") {
                    $count = $count + $this->upload_music($row, TIMESTAMP, $array);
                }
            }
        }
        if ($count == 0) {
            $msg = "导入失败！";
        } else {
            $msg = 1;
        }

        return $msg;
    }

    function upload_music($strs, $time, $array)
    {
        global $_W;
        $insert = array(
            'weid' => $_W['weid'],
            'title' => $strs[1],
            'url' => $strs[2],
            'cover' => $strs[3],
            'singer' => $strs[4],
            'intro' => $strs[5],
            'collect' => 0,
            'status' => 1,
            'displayorder' => 0,
            'dateline' => TIMESTAMP,
        );

        $category = pdo_fetch("SELECT * FROM ".tablename('weisrc_audio_music'). " WHERE title=:title AND weid=:weid", array(':title' => $strs[1], ':weid' => $_W['weid']));

        if (empty($category)) {
            return pdo_insert('weisrc_audio_music', $insert);
        } else {
            return 0;
        }
    }

    private function checkUploadFileMIME($file)
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
        // 3.return the flag
        return $flag;
    }
}