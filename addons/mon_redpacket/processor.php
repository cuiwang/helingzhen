<?php
/**
 * @url 
 */
defined('IN_IA') or exit('Access Denied');

class Mon_redpacketModuleProcessor extends WeModuleProcessor
{



 

    public $table_redpacket = "redpacket";

    public $table_redpacket_reply = "redpacket_reply";

    public function respond()
    {
        global $_W;
        $rid = $this->rule;

        $fromuser = $this->message['from'];

        if ($rid) {
            $reply = pdo_fetch("SELECT * FROM " . tablename($this->table_redpacket_reply) . " WHERE rid = :rid", array(
                ':rid' => $rid
            ));
            
            if ($reply) {
                
                $news = array();
                $news[] = array(
                    'title' => $reply['new_title'],
                    'description' => $reply['new_desc'],
                    'picurl' => $this->getpicurl($reply['new_pic']),
                    'url' => $this->createMobileUrl('auth', array(
                        'pid' => $reply['pid'],'au'=>'msg','at'=>1
                    ))
                );

                
                return $this->respNews($news);
            }
        }
        return null;
    }

    private function getpicurl($url)
    {
        global $_W;
        
       
        return $_W['attachurl'] . $url;
    }
}

