(function() {
  if ("undefined" == typeof app || !app) var app = {
    default: {
      client: null,
      token: null,
      openID: null,
      ua: null,
      id: null,
      progress: 0,
      startShake: false,
      shakeTime: 0,
      totalTime: 10,
      socket: null,
      audio: null,
	  audio2:null,
	  gamestatus:0,
      msg:{
        notready: "请等待主持人在大屏幕开启摇一摇活动后再加入",
        join: "加入摇一摇游戏成功，等待主持人开始游戏",
        start: "游戏开始了，小伙伴们疯狂摇起来！",
        over: "游戏结束, ",
        noresult: "您没有参与本轮游戏"
      }
    },
    init: function() {
	  app.default.totalTime = max_point;
      app.setFullScreen(320);//全屏显示
      app.scan();//
      app.initSocket();//建立socket链接
      app.initAudio();//初始化音乐
      app.bindEvent();//绑定事件
      //app.reg();
    },
    initAudio: function(){
        app.default.audio = document.getElementById("Audio_CutdownPlayer2"); 
		app.default.audio2 = document.getElementById("Audio_CutdownPlayer"); 
    },
    initSocket: function(){
	  socket = new WebSocket("ws://"+socket_url);
	  socket.onopen = function(){
				socket.send('{"type":"login","client_name":"'+app_user.nickname+'","client_avatar":"'+app_user.avatar+'","room_id":"'+app_user.rotate_id+'","openid":"'+app_user.openid+'","shakeTime":0}');
				$(".tips").html(app.default.msg.join);
				
	  };
	  socket.onclose = function(){
		window.location.reload();
	  };
	  socket.onmessage = function(e){
		  
				var data =  $.parseJSON(e.data);
				
				switch(data.type){
					// 服务端ping客户端
					case 'ping':
						socket.send('{"type":"pong"}');
						break;
					// 登录 更新用户列表
					case 'login':
						
					break; 
					case 'refresh':
					   window.location.reload();
					break;
					case 'start':
						
						$(".progress .val").css('-webkit-transform', 'translateX(-100%)');
						app.default.shakeTime = 0;
						app.default.startShake = false;
						app.startShake();
					  break;
					case 'game_over':
						app.stopShake();
						setTimeout(function(){
							$.alert("本轮结束啦，进入下一轮", function() {
								window.location.reload();
							});
						},1000);
					break;
				}
	  };

    },
    bindEvent: function() {
      var myShakeEvent = new Shake({threshold: 10});
		myShakeEvent.start();
		window.addEventListener('shake', app.shakeEvent, false);
    },
    timecount: function() {

    },
    progress: function() {
      var progress = Math.round(app.default.shakeTime / app.default.totalTime * 100);
      if (progress <= 100) {
        $(".progress .val").css('-webkit-transform', 'translateX(-' + (100 - progress) + '%)');
      } else {
		app.onsuccess();
      }
	  
    },
    onsuccess: function() {
	  app.stopShake();
      alert("游戏完成，请关注大屏幕显示比赛结果");
    },
    startShake: function() {
	  app.djstime();
    },
	djstime:function (){
				//app.default.audio2.play();
				$('.overlay').addClass('show');
				interval = window.setInterval(function(){
					//$("#Audio_CutdownPlayer").paused();
					auio_play(app.default.audio2);
					$(".count").html('<span class="count_nums" style="display: inline;">'+ready_time+'<b>s</b></span>');
					ready_time--;
					if(ready_time<0){
						$('.overlay').removeClass('show');
						$(".tips").html(app.default.msg.start);
						$(".hand").addClass("shake");
						$(".progress").removeClass("hide");
						app.default.startShake = true;
						window.clearInterval(interval);
					}
				},1000);	
	},
    stopShake: function() {
	 // $(".overlay").removeClass("show");
      $(".hand").removeClass("shake");
      $(".progress").addClass("hide");
      app.default.startShake = false;
      app.default.shakeTime = 0;
	   //app.resetShake();
    },
    resetShake: function() {
      $(".progress").addClass("hide");
      $(".progress .val").css('-webkit-transform', 'translateX(-100%)');
    },
    shakeEvent: function() {
      if (app.default.startShake) {
		app.default.shakeTime++;
		var shake_data = '{"type":"say","ttype":"shake","shakeTime":"'+app.default.shakeTime+'"}';
		socket.send(shake_data);
        app.progress();
        auio_play(app.default.audio);
      }
	  
    },
    scan: function() {
      app.default.ua = function(ua, appVersion, platform) {
        return {
          // win系列
          win32: platform === "Win32",
          ie: /MSIE ([^;]+)/.test(ua),
          ieMobile: window.navigator.msPointerEnabled,
          ieVersion: Math.floor((/MSIE ([^;]+)/.exec(ua) || [0, "0"])[1]),

          // ios系列
          ios: (/iphone|ipad/gi).test(appVersion),
          iphone: (/iphone/gi).test(appVersion),
          ipad: (/ipad/gi).test(appVersion),
          iosVersion: parseFloat(('' + (/CPU.*OS ([0-9_]{1,5})|(CPU like).*AppleWebKit.*Mobile/i.exec(ua) || [0, ''])[1])
            .replace('undefined', '3_2').replace('_', '.').replace('_', '')) || false,
          safari: /Version\//gi.test(appVersion) && /Safari/gi.test(appVersion),
          uiWebView: /(iPhone|iPod|iPad).*AppleWebKit(?!.*Safari)/i.test(ua),

          // 安卓系列
          android: (/android/gi).test(appVersion),
          androidVersion: parseFloat("" + (/android ([0-9\.]*)/i.exec(ua) || [0, ''])[1]),

          // chrome
          chrome: /Chrome/gi.test(ua),
          chromeVersion: parseInt((/Chrome\/([0-9]*)/gi.exec(ua) || [0, 0])[1], 10),

          // 内核
          webkit: /AppleWebKit/.test(appVersion),

          // 其他浏览器
          uc: appVersion.indexOf("UCBrowser") !== -1,
          Browser: / Browser/gi.test(appVersion),
          MiuiBrowser: /MiuiBrowser/gi.test(appVersion),

          // 微信
          MicroMessenger: ua.toLowerCase().match(/MicroMessenger/i) == "micromessenger",

          // 其他
          canTouch: "ontouchstart" in document
        };
      }(navigator.userAgent, navigator.appVersion, navigator.platform);
    },
    getParam: function(name, url) {
      var r = new RegExp("(\\?|#|&)" + name + "=(.*?)(#|&|$)")
      var m = (url || location.href).match(r);
      return decodeURIComponent(m ? m[2] : '');
    },
    cookie: function(name, value, options) {
      if (typeof value != 'undefined') { // name and value given, set cookie
        options = options || {};
        if (value === null) {
          value = '';
          options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
          var date;
          if (typeof options.expires == 'number') {
            date = new Date();
            date.setTime(date.getTime() + (options.expires * 1000));
          } else {
            date = options.expires;
          }
          expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
        }
        var path = options.path ? '; path=' + options.path : '';
        var domain = options.domain ? '; domain=' + options.domain : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
      } else { // only name given, get cookie
        var cookieValue = null;
        if (document.cookie && document.cookie != '') {
          var cookies = document.cookie.split(';');
          for (var i = 0; i < cookies.length; i++) {
            var cookie = cookies[i].replace(/^\s*(.*?)\s*$/, "$1"); //this.trim(cookies[i]);
            // Does this cookie string begin with the name we want?
            if (cookie.substring(0, name.length + 1) == (name + '=')) {
              cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
              break;
            }
          }
        }
        return cookieValue;
      }
    },
    setFullScreen: function(width) {
      document.body.style.width = width + 'px';
      //为了防止取不到clientWidth，这里补充一个默认值
      var clientWidth = document.documentElement.clientWidth ? document.documentElement.clientWidth : 320;
      var scale = clientWidth / width;
      //缩放页面，这里一般是放大页面
      document.body.style['-webkit-transform'] = 'scale(' + scale + ',' + scale + ')';
      document.body.style['-webkit-transform-origin'] = '0px 0px';
      //缩回放大高度，避免出现一些兼容性问题
      var height = document.body.clientHeight / scale;
      document.body.style.height = height + 'px';
    }
  }
  app.init();

//使用方法 
　　
}());
var e = function(e) {
return function(t, n) {
	var r, i;
	if (typeof t == "object" && typeof n == "object" && t && n) return r = t[e], i = n[e], r === i ? 0 : r < i ? -1 : 1;
	throw "error"
}
};
