<?php
defined('IN_IA') or die('Access Denied');
class Junsion_netcollectModuleProcessor extends WeModuleProcessor
{
    public function respond()
    {
        $content            = $this->message['content'];
        $rid                = $this->rule;
        $_SESSION['openid'] = $this->message['from'];
        $rule               = pdo_fetch('select * from ' . tablename($this->modulename . "_rule") . " where rid='{$rid}'");
        if (!empty($rule['hword']) && $content == $rule['hword']) {
            return $this->respNews(array(
                array(
                    'title' => $rule['title2'],
                    'description' => $rule['description2'],
                    'picurl' => toimage($rule['thumb2']),
                    'url' => $this->createMobileUrl('help', array(
                        'rid' => $rid
                    ))
                )
            ));
        }
        return $this->respNews(array(
            array(
                'title' => $rule['title'],
                'description' => $rule['description'],
                'picurl' => toimage($rule['thumb']),
                'url' => $this->createMobileUrl('index', array(
                    'rid' => $rid
                ))
            )
        ));
    }
}