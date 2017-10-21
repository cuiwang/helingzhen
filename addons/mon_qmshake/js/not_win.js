/**
 * 
 * @authors jRx (you@example.org)
 * @date    2015-01-27 19:25:51
 * @version $Id$
 */
 window.citysData =  {
	1: "上海",
	2: "北京",
	3: "广州",
	4: "重庆",
	5: "南京",
	6: "杭州",
	7: "苏州",
	8: "天津",
	9: "深圳",
	10: "武汉",
	11: "成都",
	//12: "廊坊",
	13: "西安",
	48: "贵阳",
	51: "徐州",
	24: "太原",
	45: "中山",
	23: "长沙",
	29: "合肥",
	31: "南宁",
	30: "南昌",
	52: "芜湖",
	18: "济南",
	32: "海口",
	27: "无锡",
	37: "呼和浩特",
	21: "厦门",
	25: "宁波",
	15: "沈阳",
	53: "昆山",
	26: "佛山",
	16: "大连",
	14: "石家庄",
	44: "珠海",
	22: "福州",
	17: "郑州 ",
	33: "哈尔滨",
	19: "青岛 ",
	28: "昆明",
	34: "长春",
	49: "常州"
};
 ;(function($){
 	var from_channels = null;
 	var appHref = 'http://a.app.qq.com/o/simple.jsp?pkgname=com.lanshan.weimicommunity';
 	var Download = {}; 
 	Download.setHref = function(url) {
 		if(window.parent && window.parent.window) {
 			window.parent.window.location.href = url;
 		}
 		else {
 			window.location.href = url;
 		}
 	}
 	Download.viewPort = function(){
 		var u = navigator.userAgent.toLowerCase();
 		return{
 			isWeixin: u.indexOf('micromessenger') > -1,
 			trident: u.indexOf('trident') > -1, //IE内核
			presto: u.indexOf('presto') > -1, //opera内核
			webKit: u.indexOf('applewebkit') > -1, //苹果、谷歌内核
			gecko: u.indexOf('gecko') > -1 && u.indexOf('khtml') == -1, //火狐内核
			mobile: !!u.match(/applewebkit.*mobile.*/) || !!u.match(/applewebkit/), //是否为移动终端
			ios: !!u.match(/\(i[^;]+;( u;)? cpu.+mac os x/), //ios终端
			android: u.indexOf('android') > -1 || u.indexOf('linux') > -1, //android终端或者uc浏览器
			iPhone: u.indexOf('iphone') > -1 || u.indexOf('mac') > -1, //是否为iPhone或者QQHD浏览器
			iPad: u.indexOf('ipad') > -1, //是否iPad
			webApp: u.indexOf('safari') == -1 //是否web应该程序，没有头部与底部
 		}
 	};
 	Download.action = function(){ 		
 		var _this = this;
 		if($( ".share") ){
 			$(".share").bind("click",function(){
 				_callback_share(); 		
	 		})
 		} 		
 		if($( "body#downLoad").length>0 ){
 			$("body").bind("click",function(){
	 			_callback_down(); 
	 			// return false;		
		 	})
 		}
 		if($( "#downLoad").length>0 ){
 			$("#downLoad").bind("click",function(){
	 			_callback_down(); 
		 	})
 		}
 		if( $(".had_win #time") ){ 			
 			$(".had_win #time").text( decodeURI( Download.getUrlParam("time") )  );
 			$(".had_win #prize").text( decodeURI( Download.getUrlParam("name") ) );
 		}	 	
	 	if($( "#more_welfare") ){
	 		$("#more_welfare").attr("href","/pageapp/html/java/slot_welfare/welfare_list.html?c="+Download.getUrlParam("c")+"&u="+Download.getUrlParam("u")+"&city_name="+ decodeURI(Download.getUrlParam("city_name"))+"&title="+ decodeURI(Download.getUrlParam("title"))+"&from="+Download.getUrlParam("from")+"&from_channels="+from_channels);
	 	}
	 	if($("#btn_shaken")){
	 		$("#btn_shaken").bind("click",function(){
	 			var url = "/pageapp/html/java/slot_welfare/index.html?c="+Download.getUrlParam("c")+"&u="+Download.getUrlParam("u")+"&title="+ decodeURI(Download.getUrlParam("title"))+"&from="+Download.getUrlParam("from")+"&from_channels="+from_channels;
	 			_this.setHref(url);
	 		})
	 	}
	 	if( $("#receive_success #phone_num") ){
	 		$("#receive_success #phone_num").text(Download.getUrlParam("p"))
	 	}
	 	if($("#android_marker,#ios_marker")){
	 		$("#android_marker,#ios_marker").bind("click", function(){
	 			event.stopPropagation()
	 			$("#android_marker,#ios_marker").hide();
	 			//this.style.display = "none !important";
	 		})
	 	}
	 	function _callback_down(){
	 		_wm.push(['_trackevent', 'shihui_had_win', 'downLoad', 'cityload', Download.getUrlParam("c")]);
	 		
 			_this.setHref(appHref);	
	 	}
	 	function _callback_share(){
	 		var viewPort = Download.viewPort();
	 		if(viewPort.ios){	 			
	 			$("#ios_marker").show();
	 		}
	 		if(viewPort.android){
	 			$("#android_marker").show();
	 		}
	 	}
 	}; 	

 	Download.getUrlParam = function (name, top) {
	            var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
	            var r;
	            if(top) {
	            	r = top.location.search.substr(1).match(reg);
	            } else {
	            	r = window.location.search.substr(1).match(reg);
	            }
	            if (r != null) return r[2]; return null;
	};
	Download.setDocTitle = function(){
		from_channels = Download.getUrlParam("from_channels");
		var city_name =  Download.getUrlParam("city_name") || Download.getUrlParam("city");
		if(Download.getUrlParam("from")=="wgy" ){
			document.title = "上实惠，摇免费";
		}else if(Download.getUrlParam("title")){
			document.title = decodeURI(Download.getUrlParam("title"));
		}else{
			document.title = "快来实惠"+  window.citysData[Download.getUrlParam("c")] +"抢福利吧！";
		}
		
	};
	Download.setCity = function(){
		var _this = this;
		var c = Download.getUrlParam("c"),
		loader = $("#loader");
		if( loader ) {
			/*
			loader.load("/pageapp/html/java/slot_welfare/city_middle/default.html",function(){
				_callback();
			});
			*/
		}
		function _callback(){
			if($("#btn_shaken")){
		 		$("#btn_shaken").bind("click",function(){
		 			var url = "/pageapp/html/java/slot_welfare/index.html?c="+Download.getUrlParam("c")+"&u="+Download.getUrlParam("u")+"&from="+Download.getUrlParam("from")+"&from_channels="+from_channels;
		 			_this.setHref(url);
		 		})
		 	}
		}
	}
	Download.setDocTitle();
 	Download.action();
 	Download.setCity();
	window.download = Download;
 })(Zepto);


