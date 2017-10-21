<?php

defined('IN_IA') or exit('Access Denied');
class Netbuffer_followtipnewsModule extends WeModule
{
    public function settingsDisplay($settings)
    {
        global $_GPC, $_W;
        load()->func('file');
        if (checksubmit()) {
            $cfg = array(
                'nbf_followtipnews_usr' => $_GPC['nbf_followtipnews_usr'],
                'nbf_followtipnews_usr_startcount' => $_GPC['nbf_followtipnews_usr_startcount'],
                'nbf_followtipnews_imgurl' => $_GPC['nbf_followtipnews_imgurl'],
                'nbf_followtipnews_url' => $_GPC['nbf_followtipnews_url'],
                'nbf_followtipnews_usr_subtitle' => $_GPC['nbf_followtipnews_usr_subtitle'],
                'nbf_followtipnews_usr_subtitle_url' => $_GPC['nbf_followtipnews_usr_subtitle_url'],
                'nbf_followtipnews_usr_subtitle2' => $_GPC['nbf_followtipnews_usr_subtitle2'],
                'nbf_followtipnews_usr_subtitle_url2' => $_GPC['nbf_followtipnews_usr_subtitle_url2']
            );
            if ($this->saveSettings($cfg)) {
                message('保存成功', 'refresh');
            }
        }
        include $this->template('settings');
    }
}

?>