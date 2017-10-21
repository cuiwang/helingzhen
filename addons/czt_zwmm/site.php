<?php
/**
 * 指纹蜜码模块微站定义
 *
 * @author 
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Czt_zwmmModuleSite extends WeModuleSite {

	public function doMobileIndex() {
		global $_W, $_GPC;
		$settings=$this->module['config'];
		include $this->template('index');
		
	}
	public function doMobileResult() {
		header('Content-type: application/json');
		$json=<<<eof
{"furtuneResult":
   [
     {"matchRadio":"65%",
     "matchType":"蜻蜓点水",
     "matchContent":"人与人的相遇真是难言的缘分，命运将Ta的选择权送到你手中，却抠门的只给你一晃眼的时间。也许你们还各自深陷在上一段的爱情泥潭中，but！也请深入挖掘眼前的Ta，切莫“此情可待成追忆，只是当时已惘然”呐。"
     }, 
 	{"matchRadio":"70%",
     "matchType":"欢喜冤家",
     "matchContent":"你们就像是冤家路窄一样，明明一开始就难以苟同对方，但偏偏却有时常会遇到。你们因为想要征服对方而变得越来越亲近。所谓见时羞，别是愁，百转千回不自由，形容你们再恰当不过！"
    },
 	{"matchRadio":"75%",
     "matchType":"爱意朦胧",
     "matchContent":"人一生会遇到约2920万人，两个人相爱的概率是0.000049。亲爱的，你们一路走来不易，心路历程让人颇为感慨，一路坎坎坷坷，坚强的面对困难，相濡以沫，相互扶持，这种感情尤为感人，珍惜彼此吧。"
    },
  	{"matchRadio":"80%",
     "matchType":"暧昧浪漫",
     "matchContent":"Ta于你而言就像一块硕大的磁石，你就已经深深的被Ta俘虏。Ta的一颦一笑、举手投足在你眼中恍若天神，Ta说的每一个字、每一句话，你都奉若圣经。但缘分这种东西也讲究男女搭配，干活不累，如果只是“落花有意流水无情”，赶紧解毒吧。"
    },
   	{"matchRadio":"85%",
     "matchType":"深深吸引",
     "matchContent":"亲爱的，恭喜你找到了与你契合的一半，你们都拥有至死不渝的爱情观念，忠于爱情。对于你们来讲，忠于对方是一种习惯，不需要特别时时提醒，特别强调，忠诚的程度与质量达到了令人羡慕嫉妒恨的高度。"
    },
    {"matchRadio":"90%",
     "matchType":"如胶似漆",
     "matchContent":"在你们看来，彼此是最特别的，无关乎萝莉正太，无关乎可爱萌叔，亦无关乎长腿欧巴。只因爱上了，在我眼中你就是最独一无二的那一个，这样的缘分遇到不容易，且行且珍惜，亲爱的。两情若是长久时，又岂在朝朝暮暮~"
    },
    {"matchRadio":"95%",
     "matchType":"一生挚爱",
     "matchContent":"相处时间长了，你们惊奇的发现缘来你与Ta竟是那么的相似。你们有着相似的表情、相似的习惯、你爱着Ta的喜好，感受着彼此的喜悦，分享你们如出一辙的价值观。人生的道路上，能遇上这样一段缘分，老天爷你太偏心了吧。"
    },
	{"matchRadio":"100%",
     "matchType":"金玉良缘",
     "matchContent":"亲爱的，天长地久，没什么可以破坏，是不是高富帅、白富美不要紧，最重要的是我们在一起，共同生活，享受着这个美好的世界。最大的空间是宇宙，最远的时间是永远，空间和时间的交织点是你我不变的情缘。执子之手幸福终老。"
    }
   ]
}
eof;
	echo($json);
	}
}