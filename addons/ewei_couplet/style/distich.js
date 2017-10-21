/**
 *春节对联
 * @author p_jdspguo
 * @version v2015/1/28
 */
define('wg.market.coupletIndex', function(require, exports, module) {
    var $ = require("zepto");
	var login = require("login");
	var url = require("url");
	var cookie = require("cookie");
	var xss = require("wd.tool.xss");
	var _loadscript = require("loadscript");
	var tplparse = require("wg.market.tplparser");
	var autoLoadImages = require("lazyLoad");
	var clickEvent = "click";
	var marketData = [];//微店数据源
	var shopids = [];
	var myuin = "",mypicurl="",myopenid="",mynick="";
	var dataObject = {
		"1": {'1': '取','2': '九','3': '州','4': '四','5': '海','6': '财','7': '宝'},
		"2": {'1': '接','2': '鸿','3': '福','4': '步','5': '步','6': '高','7': '升'},
		"3": {'1': '心','2': '想','3': '事','4': '成','5': '兴','6': '伟','7': '业'},
		"4": {'1': '脚','2': '踩','3': '天','4': '下','5': '高','6': '富','7': '帅'},
		"5": {'1': '春','2': '满','3': '乾','4': '坤','5': '猪','6': '满','7': '门'},
		"6": {'1': '夜','2': '夜','3': '失','4': '眠','5': '为','6': '女','7': '神'},
		"7": {'1': '有','2': '情','3': '有','4': '缘','5': '要','6': '时','7': '间'},
		"8": {'1': '铁','2': '棒','3': '磨','4': '成','5': '绣','6': '花','7': '针'},
		"9": {'1': '爱','2': '我','3': '的','4': '惨','5': '不','6': '忍','7': '睹'},
		"10": {'1': '输','2': '完','3': '立','4': '刻','5': '滚','6': '回','7': '家'},
		"11": {'1': '微','2': '店','3': '就','4': '是','5': '我','6': '的','7': '天'},
		"12": {'1': '在','2': '地','3': '沦','4': '为','5': '心','6': '机','7': '婊'}
	};
	var succList = ["与{nick}迸出爱的火花，抽中了<strong>{value}</strong>字","与{nick}手拿菜刀砍电线，抽中了<strong>{value}</strong>字","与{nick}花前月下，抽中了<strong>{value}</strong>字","与{nick}是好基友，抽中了<strong>{value}</strong>字","与{nick}一贱钟情，抽中了<strong>{value}</strong>字","{nick}的手撸啊撸，抽中了<strong>{value}</strong>字","{nick}用他的滑板鞋为你摩擦，抽中了<strong>{value}</strong>字","{nick}看到前任素颜倒垃圾，抽中了<strong>{value}</strong>字","{nick}看到前任素颜倒垃圾，抽中了<strong>{value}</strong>字","{nick}今天抽到了上上签，抽中了<strong>{value}</strong>字","{nick}就是你的小苹果，请深爱，抽中了<strong>{value}</strong>字"];
	var failList = ["{nick}素颜倒垃圾被前任撞见，抽中了无效字<strong>{value}</strong>字","{nick}不是杰伦的新娘，抽中了无效字<strong>{value}</strong>字","{nick}被霸道总裁拒绝，抽中了无效字<strong>{value}</strong>字","{nick}被ta的世界删除，抽中了无效字<strong>{value}</strong>字","与{nick}YP的当晚来大姨妈！抽中了无效字<strong>{value}</strong>字","与{nick}是走失的兄妹，抽中了无效字<strong>{value}</strong>字","与{nick}表白被拒绝，抽中了无效字<strong>{value}</strong>字","{nick}扭动身体跳起广场舞，抽中了无效字<strong>{value}</strong>字","{nick}为ta删除了整个世界，抽中了无效字<strong>{value}</strong>字","{nick}看不到媚娘的G CUP，抽中了无效字<strong>{value}</strong>字"];
	var useless = ['占','地','利','人','和','迎','新','春','万','蓉','如','意','展','宏','图','怀','拥','世','上','白','富','美','增','岁','月','增','肥','日','机','撸','啊','很','好','后','宫','佳','丽','三','千','人','名','主','东','微','店','开','年','大','吉','利'];

	exports.init = function() {
		loadedPercent(50);
        //测试
        
		if(getEnv() != "weixin") {
			showDialog("请在微信环境中参与活动！", "确定");
			return ;
		}
		
		//微信授权
		if (getEnv() == "weixin" && (!cookie.get("wx_nickname") || cookie.get("wx_nickname") == "京东用户")) {
			location.href = "http://wq.jd.com/mlogin/wxv3/Login_BJ?appid=1&rurl=" + encodeURIComponent(location.href) + "&scope=snsapi_userinfo,snsapi_event_v2";
			return false;
		}
		
		if(!login.isLogin()) {//没登入，强制登入
			login.login();
		}else {
            loadedPercent(60);
            queryMimeInfo(function(data) {//查询是否创建过id
                loadedPercent(90);
                if(!data.id) {//没创建，则显示微店列表
                    queryMarket();
                    $(".step1").show();
                    $("#J_loading").hide();
                }else {//已经创建
                    xuanStep2(data);//渲染个人页面
                    $("#J_loading").hide();
                }
            })
			
			queryAds();//拉取广告位
            
            xuanLight();//渲染跑马灯
            
            bindEvent();
		}
	}
	
	function bindEvent() {
		$("#nextpage").on(clickEvent, function() {//换一批微店显示
			var arrIndex = [];
			var data = [];
			for (var i=0; i<9; i++) {
				arrIndex.push(rd(0, marketData.length-1));
			}
			for (var j=0; j<arrIndex.length; j++) {
				data.push(marketData[arrIndex[j]]);
			}
			xuanMarket(data);
		})

		$("[tag=attend]").live(clickEvent, function() {//微店点击
			var shopid = $(this).attr("data");
            var classname =  $(this).attr("class");
            if(classname == "on") {
                $(this).removeClass("on");
            }else {
                $(this).addClass("on");
            }
			if (shopids.indexOf(shopid) == -1) {//在数组中不存在该店铺id，则添加
				shopids.push(shopid);
			}
		})

		$("#toAttent").on(clickEvent, function() {//关注店铺
			if (shopids.length == 0) {
				showDialog("至少选择1个品牌LOGO完成关注，<br/>才能获得下联哦~", "确定");
			}else {
				showLoading();
				batchAttent();
			}
		})

		$("#toMimePage").on(clickEvent, function() {//进入自己的个人页面
			showLoading();
			queryMimeInfo(function(data) {
                $(".step1").hide()
				xuanStep2(data);
			})
		})
		
		$("[tag=help]").on(clickEvent, function() {//点击邀请好友
			$("#shareTips").addClass("active");
		})
		
		$("#shareTips").on(clickEvent, function() {//隐藏分享浮层
			$(this).removeClass("active");
		})
		
		$("[tag=acheieve]").on(clickEvent, function() {//进入领取大奖页面
			hideAll();
			$(".step1").hide();
			$(".step2").hide();
			$(".brand").hide();
			$(".step3").show();
		})
		
		$("#submitInfo").on(clickEvent, function() {//提交信息
			var name = $("#name").val();
			var tel = $("#tel").val();
			var address = $("#address").val();
			if(!name || !tel || !address) {
				showDialog("信息不完整，请重新填写！", "确定");
			}else {
				submitInfo(name, tel, address);
			}
		})

		$("[tag=toRule]").on(clickEvent, function() {//去规则页
			hideAll();
			$("#ruleDialog").show();
			$(".dialog_mask").show();
			$("#ruleDialog [tag=close]").off().on(clickEvent, function() {
				$("#ruleDialog").hide();
				$(".dialog_mask").hide();
			})
		})
	}

	/**
	 *创建活动id
	 */
	function createActiveId(seqno) {
		showLoading();
		window.createback = function(data) {
			hideLoading();
			if (data.ret == 0) {//创建成功
				var couclass = "t" + seqno;
				$("#coupletDialog .couplet").addClass(couclass);
				$("#coupletDialog").show();
				$(".dialog_mask").show();
			} else if (data.ret == 2) {//未登入
				login.login({bj:1});
			} else if (data.ret == 19) {//已经创建过ID
				showDialog("您已经创建过ID！", "确定");
			} else {
				showDialog("系统繁忙，请稍后再试！", "确定");
			}
		}
		_loadscript.loadScript("http://wq.jd.com/activetmp/helpdraw/getcoupletsid?seqno=" + seqno + "&call=createback" + "&_t=" + Math.random());
	}
	
	/**
	 *渲染个人页面 
	 */
	function xuanStep2(data) {
		myid = data.id;
		mynick = data.nick;
        mynick = xss.xss(mynick.replace(/(�\d)+/g, "[img]"), "html");
		myopenid = data.openid;
		mypicurl = data.picurl;
		myuin = data.uin;
		var seqno = data.seqno;//对联号
		var couclass = "t" + seqno;
        var bingoflag = data.bingoflag;//中大奖标志
		$("#mycouplet").addClass(couclass);
		var num = caculate(data);//已经筹齐的字数
        var count = data.count;//刮奖次数
        $("#totalnum").html(data.usernum);
		if(num == 0) {//没有一个字
            $("[tag=haved]").html(num);
  	    $("[tag=rest]").html(7 - num);
	    $("#firststep").show();
            xuanRight(data.data);//渲染又联
            
		}else if(num > 0 && num < 7) {//有字但是还没筹齐
			$("[tag=haved]").html(num);
			$("[tag=rest]").html(7 - num);
            $("#secondstep").show();
			xuanRight(data.data);//渲染又联
		}else if(bingoflag == 1 && num == 7){//筹齐，中大奖了
			$("#laststep").show();
			xuanRight(data.data);//渲染又联
		}else if(count == 68) {//刮奖达到上限
            $("#largeststep").show();
        }else {
            $("[tag=haved]").html(num);
	$("[tag=rest]").html(7 - num);
                $("#secondstep").show();
	xuanRight(data.data);//渲染又联
        }
		xuanFriends(data);//渲染好友列表
		hideLoading();
		$(".step2").show();//显示个人页面
		$(".brand").show();//显示广告页面
                                setShareConfig(data, num);//设置分享
	}

	/**
	 *查询自己的信息
	 */
	function queryMimeInfo(callback) {
		window.queryback = function(data) {
			if (data.ret == 0) {//查询成功
				callback && callback(data);
			} else if (data.ret == 2) {//未登入
				login.login();
			} else {
				showDialog("系统繁忙，请稍后再试！", "确定");
			}
		}
		_loadscript.loadScript("http://wq.jd.com/activetmp/helpdraw/querycouplets?call=queryback&_t=" + Math.random());
	}

	/**
	 *批量关注店铺
	 */
	var index = 0;
	function batchAttent() {
		if (index < shopids.length) {
			attentMarket(shopids[index], function() {
				index = index + 1;
				batchAttent();
			})
		}else {
			hideLoading();
			var seqno = rd(1, 12);
			createActiveId(seqno);
		}
	}

	/**
	 *关注店铺
	 * shopid 店铺id
	 * callback 回调方法
	 */
	function attentMarket(shopid, callback) {
		window.addFavCkb = function(data) {
			callback && callback();
		}
		_loadscript.loadScript("http://wq.jd.com/fav/shop/AddShopFav?shopId=" + shopid + '&callback=addFavCkb' + '&_t=' + Math.random());
	}
 
	
	/**
	 * 查询
	 */
	function queryAds() {
		window.queryAdsback = function(data) {
			var result = [];
			if (data.retCode == 0) {
				var list = data.list;
				if (list && list.length > 0) {
					var groupid = list[0]["groupid"];
					if (groupid == 2310) {
						var locations = list[0]["locations"];
						if (locations && locations.length > 0) {
							for (var i=0; i<locations.length; i++) {
								var location = locations[i];
                                if(location.locationid == "6407") {
                                    var plans = location.plans;
                                    for (var j=0; j<plans.length; j++) {
                                        var item = plans[j];
                                        var materialdesc = item.materialdesc;
                                        var desc = materialdesc.split("|")[0] || "";
                                        var attend = materialdesc.split("|")[1] || "";
                                        result.push({name:item.materialname, pic:item.material, link:item.materiallink, desc: desc, attend: attend});
                                    }
                                }
							}
							xuanAds(result);
						}
					}
				}
			}
		}
		_loadscript.loadScript("http://bases.wanggou.com/mcoss/focusbi/show?gids=2310&pc=100&callback=queryAdsback" + "&_t" + Math.round(new Date().getTime()/60000));
	}
	
	/**
	 *渲染广告位 
	 */
	function xuanAds(data) {
		var tpl = tplparse.Template({
			tpl : $('#adsttpl').html()
		});
		var html = tpl.render({
			ads : data
		});
		$("#adslist").html(html);

		autoLoadImages.autoLoadImage();
	}

	/**
	 *展示对话框
	 * @param {Object} msg 内容
	 * @param {Object} btnTxt 按钮文本
	 * @param {Object} callback 回调方法
	 */
	function showDialog(msg, btnTxt, callback) {
		$("#dialog h3").html(msg);
		$("#dialog [tag=sure]").html(btnTxt);
		$("#dialog").show();
		$(".dialog_mask").show();
		$("#dialog [tag=close]").off().on(clickEvent, function() {
			$("#dialog").hide();
            $(".dialog_mask").hide();
			callback && callback();
		})
		$("#dialog [tag=sure]").off().on(clickEvent, function() {
			$("#dialog").hide();
            $(".dialog_mask").hide();
			callback && callback();
		})
	}
	
	/**
	 *渲染右联 
	 */
	function xuanRight(data) {
		var arr = [];
		for (var i=0; i<data.length; i++) {
			var item = data[i];
			if(item.bingo == 0) {//没中的留个坑位
				arr.push("<i class='f0'></i>");
			}else {
				var couclass = "f" + (i + 1);
				arr.push("<i class='" + couclass + "'></i>");
			}
		}
		
		$(".right").html(arr.join(""));
	}
	
	/**
	 *渲染好友帮忙列表 
	 */
	function xuanFriends(data) {
		var result = [];
		var seqno = data.seqno + "";
		var helper = data.helper;
        if(helper && helper.length > 0) {
            for (var i=0; i<helper.length; i++) {
                var item = helper[i];
                var level = item.level + "";
                var time = item.time + "";
                var name = item.nick;
                var value = dataObject[seqno][level];
                if(level >=1 && level <= 7) {
                    result.push({pic:item.picurl, text: succList[Math.floor(time.slice(-1))].replace("{nick}", xss.xss(name.replace(/(�\d)+/g, "[img]"), "html")).replace("{value}", value)});
                }else {
                    var index = Math.floor(time.slice(-2) + "");
                    if(index >= 49 && index < 99) {
                        index = index - 49;
                    }
					else if(index == 99){
                        index = 49;
                    }
                    var _value = useless[index];
                    result.push({pic:item.picurl, text: failList[Math.floor(time.slice(-1))].replace("{nick}", xss.xss(name.replace(/(�\d)+/g, "[img]"), "html")).replace("{value}", _value)});
                }
            }
		
            var tpl = tplparse.Template({
                tpl : $('#friendtpl').html()
            });
            var html = tpl.render({
                ads : result
            });
            $("#friendList").html(html);
            
            autoLoadImages.autoLoadImage();
        }
	}
	
	/**
	 *设置分享 
	 */
	function setShareConfig(data, num) {
        var paths = ["CH04", "CH05", "CH06"];
        var index = rd(0, 2);
        var path = paths[index];
		window.shareConfig.link = "http://wqs.jd.com/promote/" + path + "/2015/couplet/invent.html?hbid=" + myid + "&suin=" + myuin + "&ptag=17018.24.3";
		window.shareConfig.desc = mynick + "正在参与京东微店集对联赢iPad mini3活动，快来帮我抽取下联吧！";
		window.shareConfig.shareCallback = function() {//分享之后的回调
			$("#shareTips").removeClass("active");
			draw("wddlhd2", 1);
		}
	}
	
	/**
	 *抽奖接口调用
	 */
	function draw(active, level) {
		window.ActiveLotteryCallBack = function(data) {
			if(data.ret == 0) {//成功
				showDialog("您已经成功分享，并获得28元优惠券！", "去使用优惠券", function() {//待改善
					location.href = "wallet.html";
				});
                $("[tag=help]").html("邀请好友对下联 赢iPad mini3");
			}else if(data.ret == 2) {
				login.login();
			}else if(data.ret == 11) {
                showDialog("您已成功分享！<br/>不好意思优惠券已发完，您可以去逛逛满减专场", "去逛逛", function(){
                    
                });
                $("[tag=help]").html("邀请好友对下联 赢iPad mini3");
            }
		}
		_loadscript.loadScript("http://wq.jd.com/active/active_draw?active=" + active + "&level=" + level + "&ext=hj:" + getEnv().charAt(0) + (getEnv() == "qq" ? ("&sid=" + cookie.get("sid")) : "") + "&_t=" + Math.random());
	}
	
	/*
	 * 计算已经筹齐字数
	 */
	function caculate(data) {
		var result = 0;
		var _data = data.data;
		for (var i=0; i<_data.length; i++) {
			var _item = _data[i];
			if(_item["bingo"] == 1) {
				result ++;
			}
		}
		return result;
	}
	
	/**
	 *提交信息
	 */
	function submitInfo(name, tel, address) {
		window.ycallback = function(data) {
			showDialog("信息提交成功！", "确定", function() {
                location.href = "wallet.html";
            });
		}
		var msgcontent = decodeURIComponent(name) + "|" + decodeURIComponent(tel) + "|" + decodeURIComponent(address);
		_loadscript.loadScript("http://wq.jd.com/appointment/CommonAppointSubmit?biztype=coupletinfo&msgcontent=" + msgcontent + "&platform=" + (getEnv() == "weixin" ? 2 : 1) + "&callback=ycallback&_t=" + Math.random());
	}
	
	/**
	 *渲染跑马灯 
	 */
	function xuanLight() {
		if(_DATA) {
			var result = "公告：";
			for (var i=0; i<_DATA.length; i++) {
				result += _DATA[i]["name"] + "&nbsp;&nbsp;&nbsp;&nbsp;";
			}
			$(".gg p").html(result);
		}
	}
	
	/**
	 *展示loading状态 
	 */
	function showLoading() {
		hideAll();
		$("#loading").show();
		$(".dialog_mask").show();
	}
	
	function hideLoading() {
		$("#loading").hide();
		$(".dialog_mask").hide();
	}
	
	//返回n至m的任意区间的随机数
	function rd(n, m) {
		var c = m - n + 1;
		return Math.floor(Math.random() * c + n);
	}

	/**
	 *隐藏所有弹出框
	 */
	function hideAll() {
		$(".dialog_mask").hide();
		$("#dialog").hide();
		$("#coupletDialog").hide();
		$("#ruleDialog").hide();
		$("#loading").hide();
	}
    
    /**
	 *查询预约
	 */
	function searchInfo(biztype, callback) {
		window.queryInfoCallback = function(data) {
			if (data.iRet == 9999) {
				login.login({
					"bj" : 1
				});
			} else {
				if (data.sMsgContent != "") {
					callback(data.sMsgContent, data.iRank, data.iTotalNum);
				} else {
					callback(-1, data.iRank, data.iTotalNum);
				}
			}
		}
		_loadscript.loadScript("http://wq.jd.com/appointment/CommonAppointQuery?biztype=" + biztype + "&callback=queryInfoCallback" + "&_t=" + Math.random());
	}

	/**
	 *上报预约
	 */
	function reportInfo(biztype, value, callback) {
		window.reportback = function(data) {
			if (data.iRet == 9999) {
				login.login({
					"bj" : 1
				});
			} else if (data.iRet == 0) {
				callback && callback();
			} else {
				callback && callback(-1);
			}
		}
		_loadscript.loadScript("http://wq.jd.com/appointment/CommonAppointSubmit?biztype=" + biztype + "&msgcontent=" + encodeURIComponent(value) + "&callback=reportback&_t=" + Math.random());
	}
	
	/**
	 *查询环境
	 */
	function getEnv() {
		var ua = navigator.userAgent.toLowerCase();
		if (/micromessenger(\/[\d\.]+)*/.test(ua)) {
			return "weixin"
		} else if (/qq\/(\/[\d\.]+)*/.test(ua) || /qzone\//.test(ua)) {
			return "qq";
		} else {
			return "h5";
		}
	}

});

