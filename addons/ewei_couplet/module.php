<?php
/**
 * 对联猜猜
 *
 * @author ewei QQ：22185157
 * @url 
 */
defined('IN_IA') or exit('Access Denied');

class Ewei_coupletModule extends WeModule {
  
    public $weid;
    public $res_url;
    public function __construct() {
        global $_W;
        $this->weid = $_W['uniacid'];
    
    }
  
  public function fieldsFormDisplay($rid = 0) {
        global $_W;

        $couplets="爆竹一声除旧岁 桃符万户换新春
爆竹声声辞旧岁 梅花点点庆新春
爆竹声声辞旧岁 春联户户迎新春
喜鹊登枝盈门喜 春花烂漫大地春
天增岁月人增寿 春满乾坤福满门
发福生财吉祥地 堆金积玉富贵门
春回大地风光好 喜上心头气象新
春回大地风光好 福满人间喜事多
开门迎春春扑面 抬头见喜喜满堂
向阳门第春常在 富贵人家庆有余
向阳庭院花开早 勤劳人家喜事多
春风吹绿门前柳 华灯映红窗上花
全家共饮新春酒 举国同迎富贵年
华夏有天皆丽日 神州无处不春风
欢欢喜喜辞旧岁 高高兴兴过新年
万象更新春光好 一年巨变喜事多
红梅点点风日丽 春意融融人杰灵
鹊送喜报风送爽 莺传佳音梅传春
千门万户瞳瞳日 总把新桃换旧符
人寿年丰家家乐 国泰民安处处春
五谷丰登人人喜 六畜兴旺处处欢
春到山乡处处喜 喜临农家院院春
天地回旋春讯早 乾坤运转喜事多
雨过芳草连天碧 春到寒梅映日红
冬去不忘诗酒会 春来欣入画图中
勤俭自古为美誉 节约至今是佳称
鱼跃碧海赞海阔 鸟飞蓝天颂天高
生意兴隆通四海 财源茂盛达三江
玉海金涛千里秀 绿树红楼万户春
花灯灿烂逢盛世 锣鼓喧天颂华年
桃红柳绿千里秀 张灯结彩万家欢
文坛英才挥彩笔 艺苑新秀唱颂歌
美酒千盅辞旧岁 梅花万树迎新春
校园春光无限好 师生团结讲文明
几行绿柳千门晓 一树红梅万户春
破旧立新创大业 励精图治展宏图
爆竹千声歌盛世 红梅万点报新春
万象更新春光好 一年巨变喜事多
大地回春千山秀 国强民富万户荣
满园春色催桃李 一片丹心育新人
春种满田皆碧玉 秋收遍野尽黄金
屋后树林添新绿 门前田园迎春风
赤心迎来三江客 笑颜送走四海宾
高歌唱出花千树 妙手绘出画万张
百花园林春风暖 万里征途战旗红
美酒红灯歌盛世 银筝玉管迎新春
庆胜利豪情更壮 迈新步信心倍增
国增财富人增寿 春满人间福满门
文艺园地百花艳 教育战线凯歌洪
浩荡东风鼓千帆 英雄儿女闯万关
政策对头家家喜 经济腾飞户户欢
勤俭持家皆称赞 劳动致富最光荣
山向红日四时绿 水随人意八方流
庆丰收全家欢乐 贺新年满屋生辉
同心同德干事业 一心一意为人民
勤俭持家家家富 艰苦创业业业新
风吹杨柳千门绿 雨滋桃杏万户红
山青水秀风光好 人寿年丰喜事多
东风化雨山山翠 政策归心处处春
旗展五星光日月 花开四季丽山川
春风化雨千山秀 红日增辉万木荣
求贤急似渴思饮 治学犹如蝶恋花
八一军旗红天下 万千肝胆壮河山
夹岸晓烟杨柳青 满园春色桃杏红
九天日月吐光辉 十亿神州溢春华
大江南北映红日 长城内外尽朝晖
彩笔传情歌盛世 丹霞达意颂英雄
同心同德创大业 再接再励攀高峰
瑞雪铺下丰收路 春风吹开幸福门
东风送暖家家暖 瑞雪迎春处处春
春满枝头迎佳节 乐在心中接好年
龙腾虎跃人间景 鸟语花香天下春
接天瑞雪千家乐 献岁梅花万朵香
花开富贵家家乐 灯照吉祥岁岁欢
风调雨顺家家乐 粮多财足户户欢
百花吐艳春风暖 万象更新国运昌
天上这光添异彩 人间科技建奇功
九州春好添风采 科学技术改乾坤
无边春色百花艳 有庆年头万木春
前辈创业垂千古 开创未来有后人
克勤克俭奔富路 一心一意夺丰收
知识海洋勤是岸 科技高峰志为梯
工农同饮庆功酒 军民共戴团结花
国强民富逢盛世 日暖花开正阳春
喜看春来花千树 笑饮丰年酒一盅
满腔热情迎旅客 一颗红心为人民
锦绣河山添锦绣 光辉事业益光辉
时雨点红桃千树 春风吹绿柳万枝
春归大地千山秀 日照神州万木春
劳动致富人添喜 勤俭持家春色增
春风早临英雄第 喜报频传光荣家
向阳商店连百姓 春风柜台暖万家
尊师爱生风尚美 文明礼貌气象新
春光明媚百花地 祖国富强万众心
东风送暖花自舞 大地回春鸟能言
新年新春新景色 好人好事好风光
日出神州张正气 春来华夏展宏图
大地春光红艳艳 神州佳节乐陶陶
励精图治千家富 正本清源万木春
国运勃兴花益灿 民情快乐月弥明
一年好景随春到 四化宏图与日新
五湖四海家家乐 万紫千红处处春
雄心开创千秋业 妙手绘成万年春
处处从实际出发 事事为群众着想
大地年年春光好 祖国处处气象新
登奇山风雪为友 宿大地星月伴眠
讲卫生移风易俗 练身体益寿延年
英雄业绩超千古 风流人物看如今
勤饲养六畜兴旺 多积肥五谷丰登
岁月逢春人欢笑 禾苗得雨粮丰收
四面八方传捷报 千家万户浴春晖
处处红花红处处 重重绿树绿重重
煤海滚滚翻波浪 粮山巍巍入云霄
军民情谊深似海 党政团结重如山
军营春色处处好 官兵情谊日日深
一片彩霞迎旭日 万条金线带春光
六出飞花千亩喜 一声爆竹万家春
齐奔小康千秋来 共走富贵万里程
江边柳线迎年绿 门上桃符映眼新
欢欢喜喜辞旧岁 快快乐乐迎新年
笔底纵谈中外事 胸中洞识古今情
大地回春花千树 春风送暖果万枝
岁月逢春春似锦 劳动致富富盈门
年年日历更日历 岁岁长正复长征
兴高彩烈辞旧岁 欢欣鼓舞迎新春
五业并举家家乐 六畜兴旺户户欢
满面春风迎顾客 一腔热情暖人心
春风吹开花遍地 劳动带来福满门
何日同饮长江水 哪时共映日月潭
东风暖吹太行翠 春雨细洒吕梁青
红灯照亮千家户 春风吹拂万人心
春回大地千山秀 福降人间万年春
描天描地描日月 绘江绘海绘山川
一元复始呈兴旺 万象更新起宏图
好园林百花齐放 新中国万木争荣
青松翠柏送寒去 白雪红梅迎春来
丑牛昨夜随冬去 寅虎今朝奔春来
午马昨夜随冬去 未羊今朝奔春来
人尽其才国兴旺 物尽其用民富强
三春花露酿美酒 一腔激情写春秋
伸张正义顺民意 严明法制得人心
珠海生辉千秋业 快马加鞭万里程
龙腾虎跃长征路 莺歌燕舞四化图
献身祖国人人爱 多做好事个个夸
白雪银枝辞旧岁 和风细雨兆丰年
精心巧绣三春景 彩笔妙绘四季图
汾水流长歌千首 春风送暖花万枝
春风催绿荣万物 红梅送香暖千家
长风劲送千帆远 百鸟齐鸣万木新
瑞雪翩翩丰收景 红梅朵朵富裕花
爆竹刚唱丰收曲 春风又开富裕门
芙蓉国内百里雪 红梅枝头十分春
春风吹得校园暖 热血浇出桃李香
喜今朝江山如画 看未来前程似锦
喜爆送去十二月 飞雪迎来又一春
石池春暖人都乐 水阁冬温客皆欢
金鸡啼开千门喜 东风吹入万户春
满园春色关不住 遍地桃李送春来
万千桃李香四海 一派春光明五湖
妙曲吹天百花艳 神姿舞得万马腾
鹤发银丝映日月 丹心热血沃新花
欣看江山万里秀 喜听长征一路歌
龙腾虎跃修水利 人欢马叫闹春耕
人来人往皆笑脸 店内店外都欢迎
万里春风门前过 漫天云霞笔下生
家家桌上丰收酒 户户窗前迎春花
春风吹绿门前树 华灯映红窗上花
拥军欢歌传千代 爱民衷曲唱万年
春来奇花红映日 冬去芳草碧连天
汗水洒尽出高艺 友谊花开香五洲
柳垂千丝三江绿 梅开万树五岳红
碧野青天千里秀 红楼绿树万家春
门外春光万千景 窗前红梅三五枝
门前绿水声声笑 屋后青山步步春
辞旧岁全家欢笑 迎新春满院春光
千洲叠翠春光好 万水扬波气象新
勤俭治家家致富 春风拂柳柳更新
栽花护花花更艳 逢春惜春春越浓
军属门上光荣匾 战士胸前英雄花
英雄功绩昭百世 烈士英名传千秋
劳动门弟春光好 光荣人家景色新
春风双燕曾相识 桃李芳园再度栽
四海高歌丰收曲 五洲遍开幸福花
学习好天天进步 品格高人人夸奖
满园桃李争春暖 遍山松柏耐岁寒
松竹梅岁寒三友 桃李杏春风一家
五讲四美除旧态 文明礼貌树新风
芳草春回依旧绿 梅花时到自然红
万紫千红花竟艳 飞鸣比翼鸟争春
天增岁月人增寿 春满乾坤福满门
东风送暖千家乐 旭日融和万户春
园丁辛勤一堂秀 桃李成荫四海春
春回大地风光好 喜到人间气象新
祖国江山千古秀 中华大地万年春
喜盼神州驰寰宇 笑傲中华骋英豪";
        if (!empty($rid)) {
            $reply = pdo_fetch("SELECT * FROM " . tablename('ewei_couplet_reply') . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
            $rules = unserialize($reply['rules']);
        }
        if (!$reply) {
            $now = time();
            $reply = array(
                "starttime" => $now,
                "endtime" => strtotime(date("Y-m-d H:i", $now + 7 * 24 * 3600)),
                "couplets"=>$couplets,
                "res_img1"=>"../addons/ewei_couplet/style/step2.jpg",
                "res_img2"=>"../addons/ewei_couplet/style/sp.png",
                "bgcolor"=>"#3a0c46",
                "detail"=>"<li>1、活动时间：xx月xx日xx:xx -xx月xx日 xx:xx</li>
<li>2、参与方式：关注 xxx 回复 xxx 进入活动页面，即可参加&ldquo;集下联，赢xxx&rdquo;活动，通过邀请好友为自己集下联，凑齐出一幅正确对联即可领取相应奖品。</li>
<li>3、每个人可以有 xx 个朋友帮他抽奖；帮助好友也有次数限制，每人仅可以帮助一个好友抽一次。</li>
<li>4、活动期间，同一用户只能参与新年集对联活动一次；</li>
<li>5、活动奖品将于活动结束后15天按照提交的收件人姓名、地址和联系方式进行邮寄发放，如未填写有效收件信息，视为放弃奖品；</li>
<li>6、在参与活动过程中，如果出现违规行为（如作弊领取、恶意套取、刷信誉等），将根据自身合理判断取消用户免费领取奖品的资格；</li>"
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
                $path =  "/addons/ewei_couplet";
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
            'joincount'=>intval($_GPC['joincount']),
            'friendcount'=>intval($_GPC['friendcount']),
            'couplets'=>$_GPC['couplets'],
            'starttime'=>strtotime( $_GPC['datelimit']['start']),
            'endtime'=>strtotime( $_GPC['datelimit']['end']),
            'award_name'=>$_GPC['award_name'],
            'award_total'=>intval($_GPC['award_total']),
            'award_last'=>intval($_GPC['award_last']),
            'toptext'=>$_GPC['toptext'],
            'res_img1' => $_GPC['res_img1'],
            'res_img2' => $_GPC['res_img2'],
            'bgcolor' => $_GPC['bgcolor'],
        );
         //规则
        $rule_ids = $_GPC['rule_id'];
        $rule_caches = array();
        if(is_array($rule_ids)){
            foreach($rule_ids as $key =>$value){
                $value = intval($value);
                $d = array(
                    "id"=>$_GPC['rule_id'][$key],
                    "p1"=>$_GPC['rule_p1'][$key],
                    "p2"=>$_GPC['rule_p2'][$key],
                    "p3"=>$_GPC['rule_p3'][$key],
                    "p4"=>$_GPC['rule_p4'][$key],
                    "p5"=>$_GPC['rule_p5'][$key],
                    "p6"=>$_GPC['rule_p6'][$key],
                    "p7"=>$_GPC['rule_p7'][$key],
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
            $id = pdo_insert('ewei_couplet_reply', $insert);
        } else {
            pdo_update('ewei_couplet_reply', $insert, array('id' => $id));
        }
       
        
        //基础数据缓存
        $insert['id'] = $id;
        $d = pdo_fetch("select * from ".tablename('ewei_couplet_reply')." where rid=:rid limit 1",array(":rid"=>$rid));
        $this->write_cache($rid,$d);
        return true;
    }

    public function ruleDeleted($rid) {
        pdo_delete('ewei_couplet_reply', array('rid' => $rid));
        pdo_delete('ewei_couplet_fans', array('rid' => $rid));
        pdo_delete('ewei_couplet_fans_help', array('rid' => $rid));
        pdo_delete('ewei_couplet_fans_record', array('rid' => $rid));
    }
}
