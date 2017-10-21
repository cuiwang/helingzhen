<?php
/**
 * 一键导航模块处理程序
 *
 * @author yhctech
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Yhc_onenaviModuleProcessor extends WeModuleProcessor {
	public function respond() {

        //这里定义此模块进行消息处理时的具体过程, 请查看微赞文档来编写你的代码

        global $_W;
        $rid = $this->rule;

        $item = pdo_fetch("SELECT * FROM ".tablename('yhc_onenavi')." WHERE rid = :rid limit 0 ,1", array(':rid' => $rid));

        $url = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . "&c=entry&m=yhc_onenavi&do=redirect&rid=".$rid;

        return $this->respNews(array(
            'Title' => $item['title'],
            'Description' => $item['title'],
            'PicUrl' => "http://api.map.baidu.com/staticimage?center=".$item["lng"].",".$item["lat"]."&width=360&height=200&zoom=18&copyright=1&markers=".$item["lng"].",".$item["lat"],
            'Url' => $url
        ));
	}
    private function httpGet($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_URL, $url);

        $res = curl_exec($curl);
        curl_close($curl);

        return $res;
    }
}