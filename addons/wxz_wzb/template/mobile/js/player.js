//var LSS_SITE = 'http://192.168.1.62/amytest/aodianplayer';
var LSS_SITE = 'http://cdn.aodianyun.com';
var lssPlayerLoad = false;
var hlsPlayerLoad = false;
var AODIANPLAY_UUID_BASE = 0;
function aodianPlayer(conf){
	var lssFunName,lssFunInterval,hlsFunName,hlsFunInterval,html5FunName,html5FunInterval;
	var conf = conf;
	if (!conf.container || conf.container == "") {

		if (typeof console == "undefined") {}
		else
            console.log("缺少必要的参数：container");
        return;
    }
    var Existcontainer=document.getElementById(conf.container);
    if(!Existcontainer){

    	if(typeof console == "undefined") {}
		else
    	   console.log("container不存在");
        return;
    }
    ++AODIANPLAY_UUID_BASE;
    var html="<div id='aodianplayer_uid_base"+AODIANPLAY_UUID_BASE+"'></div>";
    $('#'+conf.container).append(html);
    conf.mediaid='aodianplayer_uid_base'+AODIANPLAY_UUID_BASE;	
	//判断手机还是pc
    var userAgentInfo = navigator.userAgent;
    var Agents = ["Android","iPhone","SymbianOS","Windows Phone","iPad","iPod"];
    var isPc = true;
    for(var v = 0; v < Agents.length; v++){
        if(userAgentInfo.indexOf(Agents[v]) > 0){
            isPc = false;
            break;
        }
    }
	conf.isPc = isPc;
	if(conf.videoUrl && conf.videoUrl != ''){
		conf.hlsUrl=conf.videoUrl;
	}	
	if(isPc){
		if(conf.rtmpUrl && conf.rtmpUrl != ''){
			var mode = /^rtmp\:\/\/(.*)\/([a-z\_\-A-Z\-0-9]*)\/([a-z\_\-A-Z\-0-9]*)(\?k\=([a-z0-9]*)\&t\=\d{10,11})?$/;
			if(!mode.test(conf.rtmpUrl)){

				if(typeof console == "undefined") {}
		        else
				   console.log("rtmp地址格式错误");
				return;
			}
			var arr = conf.rtmpUrl.match(mode);
			conf.cname = arr[1];
			conf.app = arr[2];
			conf.key = '';
			conf.pk = arr[4] ? arr[4] : '';
			conf.stream = arr[3] + conf.pk;;
			conf.addr = 'rtmp://'+ conf.cname +'/' + conf.app;
			var playername='lssplayer';	
			lssFunName = playername + 'Run';
			var _this=this;	
			lssFunInterval = setInterval(function(){
				if(lssPlayerLoad == true){
					if(lssFunName in window){
						lssplayerRun.call(_this,conf);
						clearInterval(lssFunInterval);
						return;
					}
					return;
				}
				var playerScript = LSS_SITE + '/lss/aodianplay/' + playername + '.js?11212';
				var layoutScript = document.createElement('script');
				layoutScript.type = 'text/javascript';
				layoutScript.charset = 'UTF-8';
				layoutScript.src = playerScript;
				document.getElementsByTagName("body")[0].appendChild(layoutScript);

				lssPlayerLoad = true;
				if(lssFunName && lssFunName in window){
					clearInterval(lssFunInterval);
					lssplayerRun.call(_this,conf);
				}
			},100);		
		}
		else if(conf.hlsUrl && conf.hlsUrl != ''){			
			var playername='hlsplayer';	
			hlsFunName = playername + 'Run';
			var _this=this;	
			hlsFunInterval = setInterval(function(){
				if(hlsPlayerLoad == true){
					if(hlsFunName in window){
						hlsplayerRun.call(_this,conf);
						clearInterval(hlsFunInterval);
						return;
					}
					return;
				}
				var playerScript = LSS_SITE + '/lss/aodianplay/' + playername + '.js';
				var layoutScript = document.createElement('script');
				layoutScript.type = 'text/javascript';
				layoutScript.charset = 'UTF-8';
				layoutScript.src = playerScript;
				document.getElementsByTagName("body")[0].appendChild(layoutScript);

				hlsPlayerLoad = true;
				if(hlsFunName && hlsFunName in window){
					clearInterval(hlsFunInterval);
					hlsplayerRun.call(_this,conf);
				}
			},100);		
		}
	}
	else{
		if(conf.hlsUrl && conf.hlsUrl != ''){
			var playername='html5player';	
			html5FunName = playername + 'Run';
			var _this=this;	
			html5FunInterval = setInterval(function(){
				if(hlsPlayerLoad == true){
					if(html5FunName in window){
						html5playerRun.call(_this,conf);
						clearInterval(html5FunInterval);
						return;
					}
					return;
				}
				var playerScript = LSS_SITE + '/lss/aodianplay/' + playername + '.js';
				var layoutScript = document.createElement('script');
				layoutScript.type = 'text/javascript';
				layoutScript.charset = 'UTF-8';
				layoutScript.src = playerScript;
				document.getElementsByTagName("body")[0].appendChild(layoutScript);

				hlsPlayerLoad = true;
				if(html5FunName && html5FunName in window){
					clearInterval(html5FunInterval);
					html5playerRun.call(_this,conf);
				}
			},100);		
		}
		else{
			document.getElementById(conf.container).innerHTML+="hlsUrl地址未传递";			
		}

	}	
}
