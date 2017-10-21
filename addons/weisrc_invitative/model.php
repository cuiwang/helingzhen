<?php

defined('IN_IA') or exit('Access Denied');

//添加通知
function add_announce($announce = array())
{
    $data = array(
        'weid' => $announce['weid'],
        'giftid' => $announce['giftid'],
        'from_user' => $announce['from_user'],
        'type' => $announce['type'],
        'title' => $announce['title'],
        'content' => $announce['content'],
        'levelid' => -1,
        'displayorder' => 0,
        'updatetime' => TIMESTAMP,
        'dateline' => TIMESTAMP,
    );
    pdo_insert('icard_announce', $data);
}

//用户会员卡
function get_user_card($from_user)
{
    global $_W;
    $sql = "SELECT * FROM " . tablename('icard_card') . " WHERE from_user=:from_user AND weid=:weid LIMIT 1";
    return pdo_fetch($sql, array(':from_user' => $from_user, ':weid' => $_W['weid']));
}

//会员卡积分设置
function get_card_score()
{
    global $_W;
    $sql = "SELECT * FROM " . tablename('icard_score') . " WHERE weid=:weid LIMIT 1";
    return pdo_fetch($sql, array(':weid' => $_W['weid']));
}

function get_domain()
{
    $host = $_SERVER['HTTP_HOST'];
    $host = strtolower($host);
    if (strpos($host, '/') !== false) {
        $parse = @parse_url($host);
        $host = $parse['host'];
    }
    $topleveldomaindb = array('com', 'edu', 'gov', 'int', 'mil', 'net', 'org', 'biz', 'info', 'pro', 'name', 'museum', 'coop', 'aero', 'xxx', 'idv', 'mobi', 'cc', 'me');
    $str = '';
    foreach ($topleveldomaindb as $v) {
        $str .= ($str ? '|' : '') . $v;
    }
    $matchstr = "[^\.]+\.(?:(" . $str . ")|\w{2}|((" . $str . ")\.\w{2}))$";
    if (preg_match("/" . $matchstr . "/ies", $host, $matchs)) {
        $domain = $matchs['0'];
    } else {
        $domain = $host;
    }
    return $domain;
}


/*
<p>尊敬的贵宾： <br style="white-space: normal;" />智能营销蓬勃发展的今天，企业如何利用企业云平台进行高效的移动办公、精准营销？企业如何网罗海量的精准客户？企业的客户资源如何系统整合？ <br style="white-space: normal;" />智能手机用户13亿，腾讯QQ用户8亿，微信用户6亿，资源都汇集到移动端，加上4G网络的真正实现&mdash;&mdash;中国移动互联第三次革命真正开始了&mdash;&mdash;这将给中国商业环境带来一场全新的变革。 <br style="white-space: normal;" />今天企业面临这场浪潮，作为企业家的您，准备好了吗？ <br style="white-space: normal;" /><br style="white-space: normal;" />2015年6月21日下午举办《2015年营销革新思维创新讲座》，给您一个完美的答案。 <br style="white-space: normal;" />参与此沙龙&nbsp;您将全面了解： <br style="white-space: normal;" />移动互联时代企业营销革新思维 <br style="white-space: normal;" />【面向对象】<br style="white-space: normal;" />1.价值创造型企业主，企业实际控制人； <br style="white-space: normal;" />2.渴望营销战略突破，并拥抱智能营销、移动营销模式的企业主； <br style="white-space: normal;" />3.期待实践&nbsp;&ldquo;由内到外&rdquo;的产品中心思维向&ldquo;由外到内&rdquo;的客户中心思维的转型的企业主。 <br style="white-space: normal;" />沙龙方式：场地空间有限定向邀请，为保证讲座效果，每家企业限定不超过2人参会，限定20人，以报名先后优先安排座位。<br style="white-space: normal;" />【参会时间】<br style="white-space: normal;" />2015年6月21日（周六）14：30-17：30 <br style="white-space: normal;" />【参会要求】<br style="white-space: normal;" />参与企业家本着相互尊重的态度，须准时入场，全程参与。 <br style="white-space: normal;" />我们诚邀您的到来，携手掌握趋势、赢得未来！ <br style="white-space: normal;" />【沙龙地点】汕头大学<br style="white-space: normal;" />【主办单位】微雨网络科技有限公司<br style="white-space: normal;" /><br style="white-space: normal;" />更多信息请关注网址：<a href="http://www.weisrc.com" target="_blank">www.weisrc.com</a>&nbsp; <br />&nbsp;关注微信服务号：微资源平台微信号：weisrc&nbsp;&nbsp; <br style="white-space: normal;" />咨询QQ：15595755</p>
邀请：颠覆传统方式，让邀请帖更方便快捷


　　微信邀请函的出现是传统请帖的一次大变革，邀请函面向所有行业。直接通过微信送达，免去纸质请帖找人传递的烦恼，展现内容更多，一页请帖包括：图片，导航，内容介绍...。
 * */

