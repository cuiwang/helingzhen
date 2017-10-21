<?php
/**
 * 合体红包
 *
 */
defined('IN_IA') or exit('Access Denied');

class Ewei_bonusModule extends WeModule {
  
    public $weid;
    public $res_url;
    public function __construct() {
        global $_W;
        $this->weid = $_W['uniacid'];
    
    }
  
  public function fieldsFormDisplay($rid = 0) {
        global $_W;

        if (!empty($rid)) {
            $reply = pdo_fetch("SELECT * FROM " . tablename('ewei_bonus_reply') . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
            $rules = unserialize($reply['rules']);
        }
        if (!$reply) {
            $now = time();
            $reply = array(
                
                "starttime" => $now,
                "endtime" => strtotime(date("Y-m-d H:i", $now + 7 * 24 * 3600)),
                "start"=>"10",
                "end"=>"30",
                	"detail"=>"<p style='font-size: 16px;'><strong>活动时间：</strong></p>
<p style='font-size: 16px;'>xxxx-xx-xx xx:xx 至 <span style='color: rgb(255, 0, 0);'>xxxx-xx-xx xx:xx 截止</span></p>
<p style='font-size: 16px;'><strong>活动规则：</strong></p>
<p style='font-size: 16px;'>1.参与本次红包活动请使用微信客户端参与。</p>
<p style='font-size: 16px;'>2.领取红包后分享给好友合体，可让自己红包随机增大。</p>
<p style='font-size: 16px;'>3.红包金额必须达到xxx元以上，即能申请提现。</p>
<p>4.奖励领取：关注 xxxx 后,回复 xxxx, 即可按红包金额申请提现。</p>
<p style='font-size: 16px;'>5.红包金额有限，领完即止。</p>
<p style='font-size: 16px;'>6.金额兑现时间：活动结束后5个工作日内发放完毕。</p>
<p style='font-size: 16px;'>7.在获取和使用过程中，如果出现违规行为（如作弊领取、恶意套现、刷信誉等）本平台将根据自身合理判断取消用户获取红包的资格。</p>
<p style='font-size: 16px;'>8.本次活动最终解释权归：<span style='color: rgb(255, 0, 0); text-decoration: none;'><strong><span style='color: rgb(255, 0, 0);'><strong>xxxxxx</strong></span></strong></span><span style='line-height: 1.5; font-size: 16px;'>官方所有。<span style='color: rgb(34, 34, 34); font-family: &#39;Helvetica Neue&#39;, &#39;Hiragino Sans GB&#39;, &#39;Microsoft YaHei&#39;, 黑体, Arial, sans-serif; font-size: 14.44444465637207px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: 24.44444465637207px; orphans: auto; text-align: left; text-indent: 0px; text-transform: none; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); display: inline !important; float: none;'></span></span> </p>
<p style='font-size: 16px; text-align: center;'><span style='color: rgb(34, 34, 34); font-family: &#39;Helvetica Neue&#39;, &#39;Hiragino Sans GB&#39;, &#39;Microsoft YaHei&#39;, 黑体, Arial, sans-serif; font-size: 14.44444465637207px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: 24.44444465637207px; orphans: auto; text-align: left; text-indent: 0px; text-transform: none; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); display: inline !important; float: none;'>&nbsp;</span></p>"
            );
            $rules =array(
                    array("point"=>40,"start"=>0.1,"end"=>5),
                    array("point"=>55,"start"=>0.1,"end"=>3),
                    array("point"=>70,"start"=>0.1,"end"=>0.5)
                );
        }
        load()->func('tpl');
        include $this->template('form');
    }

    public function fieldsFormValidate($rid = 0) {
        //规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
        return '';
    }

    
function write_cache($filename, $data) {
	global $_W;
                  $path =  "/addons/ewei_bonus";
	$filename = IA_ROOT . $path."/data/".$filename.".txt";
                  load()->func('file');
	mkdirs(dirname($filename));
	file_put_contents($filename, base64_encode( json_encode($data) ));
	@chmod($filename, $_W['config']['setting']['filemode']);
	return is_file($filename);
}

    public function fieldsFormSubmit($rid) {
        global $_GPC, $_W;
        $id = intval($_GPC['reply_id']);
        
        $insert = array(
            'rid' => $rid,
            'uniacid' =>$_W['uniacid'],
            'title' => $_GPC['title'],
            'thumb' => $_GPC['thumb'],
            'description' => $_GPC['description'],
            'detail' => htmlspecialchars_decode($_GPC['detail']),
            'followurl' => $_GPC['followurl'],
            'createtime' => time(),
            'copyright' => $_GPC['copyright'],
            'start' => $_GPC['start'],
            'end' => $_GPC['end'],
            'joincount'=>intval($_GPC['joincount']),
            'points'=>intval($_GPC['points']),
            'starttime'=>strtotime( $_GPC['datelimit']['start']),
            'endtime'=>strtotime( $_GPC['datelimit']['end']),
        );
         //规则
        $rule_ids = $_GPC['rule_id'];
        $rule_points = $_GPC['rule_point'];
        $rule_starts = $_GPC['rule_start'];
        $rule_ends = $_GPC['rule_end'];
        $rule_caches = array();
        if(is_array($rule_ids)){
            foreach($rule_ids as $key =>$value){
                $value = intval($value);
                $d = array(
                    "point"=>$rule_points[$key],
                    "start"=>$rule_starts[$key],
                    "end"=>$rule_ends[$key],
                );
                $rule_caches[] = $d;
            }
        }
        $insert['rules'] = serialize($rule_caches);
        
        if (empty($id)) {
            if ($insert['starttime'] <= time()) {
                $insert['isshow'] = 1;
            } else {
                $insert['isshow'] = 0;
            }
            $id = pdo_insert('ewei_bonus_reply', $insert);
        } else {
            pdo_update('ewei_bonus_reply', $insert, array('id' => $id));
        }
       
        
        //基础数据缓存
        $insert['id'] = $id;
        $d = pdo_fetch("select * from ".tablename('ewei_bonus_reply')." where rid=:rid limit 1",array(":rid"=>$rid));
        $this->write_cache($rid,$d);
        return true;
    }

    public function ruleDeleted($rid) {
        pdo_delete('ewei_bonus_reply', array('rid' => $rid));
        pdo_delete('ewei_bonus_fans', array('rid' => $rid));
        pdo_delete('ewei_bonus_fans_help', array('rid' => $rid));
        pdo_delete('ewei_bonus_fans_record', array('rid' => $rid));
    }
}
