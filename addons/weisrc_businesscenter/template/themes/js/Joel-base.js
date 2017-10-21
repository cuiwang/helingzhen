//For Memory,For Joel//
//Joel-Wap-前端函数 V1.0//
//Auth:蒋金辰 Joel
//Mail:54006600@qq.com
//(c) Copyright 2014 Joel. All Rights Reserved.


	//全局函数
	//入口函数
	function Joel_start(opts){
		Joel_init(opts);
		Joel_initMode(opts);
		Joel_initURL(opts);
		Joel_language();
		console.log("Joel全局初始化成功！")
	}
	//初始化函数
	function Joel_init(opts) {
		console.log("微官网控制台：By Joel")
		console.log("开始执行全局初始化：")
		//国际化
		window.Joel_international = opts['international'];
		//全局缓存国际化判断
		window.Joel_defaultlanguage = opts['defaultlanguage'];
		//全局缓存默认语言
		window.Joel_nowlanguage = opts['nowlanguage'];
		//全局缓存当前语言
		console.log("国际化：" + window.Joel_international + " 默认语言：" + window.Joel_defaultlanguage + " 当前语言：" + window.Joel_nowlanguage);
	}

	function Joel_initMode(opts) {
		console.log("执行动态模式初始化：")
		//访问模式
		window.Joel_wuid = opts['wuid']
		window.Joel_wuserinfo = opts['wuserinfo'];
		window.Joel_wmode = opts['wmode'];
		window.Joel_wclient = opts['wclient'];
		console.log("服务端UID:" + window.Joel_wuid);
		console.log("服务端INFO:" + window.Joel_wuserinfo);
		console.log("客户端模式:" + window.Joel_wmode);
		console.log("客户端标示：" + window.Joel_wclient);

	}

	//通用函数
	function Joel_language() {
		console.log("初始化语言.")
		var lan = window.Joel_nowlanguage;
		if (lan == "J-cn") {
			$('.J-en').hide();
			$('.J-cn').show();
		} else {
			$('.J-cn').hide();
			$('.J-en').show();
		}
	}

	//全局重置URL
	function Joel_initURL(opts) {
		//获取当前模式
		var wmode = opts['wmode'];
		var wclient = opts['wclient'];
		console.log("URL强制初始化.");
		var J_a = $('a.J-initURL');
		//console.log(J_a);
		var pattern = ".html";
		$.each(J_a, function(i,e) {
			var href=$(e).attr('href').replace(new RegExp(pattern), "");
			$(e).attr('href', href+'/uid/'+opts['wuid']);
		});
	}

	//特定操作函数
	//设置语言按钮
	function Joel_setLanguage(uid) {
		var lan = window.Joel_nowlanguage;
		if (lan == "J-cn") {
			//远程设置语言，不成功不影响操作
			$.post('/Wap/Wgw/joelSetlanguage/',{'uid':uid,lan:'J-en'},function(re){
				console.log($.parseJSON(re).msg);				
			});
			window.Joel_nowlanguage = "J-en";
		} else {
			$.post('/Wap/Wgw/joelSetlanguage/',{'uid':uid,lan:'J-cn'},function(re){
				console.log($.parseJSON(re).msg);				
			});
			window.Joel_nowlanguage = "J-cn";
		}
		Joel_language();
		return false;
	}
	
	//快速loading反案
	function J_loading(id){
		$('#'+id).toggle();
	}