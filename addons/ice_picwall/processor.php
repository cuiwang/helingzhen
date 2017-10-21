<?php
defined('IN_IA') or exit('Access Denied');
class Ice_picwallModuleProcessor extends WeModuleProcessor
{
    public function respond()
    {
        $content = $this->message['content'];
        global $_W;
        if (!strcmp($this->message['type'], 'image')) {
            $count = pdo_fetchcolumn("select count(*) from " . tablename("ice_picWallMain") . " where openid = :openid and uniacid = :uniacid", array(
                ":openid" => $_W['openid'],
                ":uniacid" => $_W['uniacid']
            ));
            if ($count > 0) {
                $url     = $this->buildSiteUrl($this->createMobileUrl('Index', array()));
                $url2    = $this->buildSiteUrl($this->createMobileUrl('getInfo', array()));
                $message = sprintf("您已经上传过照片到照片墙，无需重复上传。<a href='%s'>查看照片墙</a>。", $url, $url2);
                return $this->respText($message);
            }
            $message = $this->message;
            $showID  = $this->getShowID();
            load()->func('communication');
            $image    = ihttp_request($this->message['picurl']);
            $partPath = IA_ROOT . '/' . $_W['config']['upload']['attachdir'] . '/';
            do {
                $filename = "images/{$_W['uniacid']}/" . date('Y/m/') . random(30) . '.jpg';
            } while (file_exists($partPath . $filename));
            file_write($filename, $image['content']);
            $data['content']    = $filename;
            $data               = array(
                'uniacid' => $_W['uniacid'],
                'openid' => $_W['openid'],
                'tousername' => $message['to'],
                'showID' => $showID,
                'imgurl' => $filename,
                'time' => $message['time']
            );
            $data['tousername'] = $message['to'];
            $url                = $this->buildSiteUrl($this->createMobileUrl('Index', array()));
            $url2               = $this->buildSiteUrl($this->createMobileUrl('getInfo', array()));
            $ds                 = pdo_insert('ice_picWallMain', $data);
            $message            = sprintf("照片上传成功，请点击<a href='%s'>照片墙</a>,并留下<a href='%s'>您的信息</a>，方便与您取得联系。", $url, $url2);
            if ($ds)
                return $this->respText($message);
            else
                return $this->respText('照片上传失败');
        } elseif (!strcmp($this->message['type'], 'text')) {
            return $this->respText('照片墙更多活动请了解');
        } else {
            return $this->respText('照片上传失败');
        }
    }
    private function getShowID()
    {
        global $_W;
        $sql    = 'select showID from ' . tablename('ice_picWallMain') . ' where uniacid = :uniacid order by showID DESC';
        $result = pdo_fetchcolumn($sql, array(
            'uniacid' => $_W['uniacid']
        ), 0) + 1;
        return $result;
    }
}