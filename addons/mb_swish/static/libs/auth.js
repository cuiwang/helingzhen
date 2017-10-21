/**
 * 
 */

define(['MHJ'],function(MHJ){
	var KEY_API_TOKEN = window.KEY_API_TOKEN = 'lppzapitoken';
	var KEY_API_OPENID = window.KEY_API_OPENID = 'lppzauthid';
	var KEY_SERVER_REDIRECT = "m_redirect";
	var OAUTH_URL = window.OAUTH_URL;
//	var baseUrl = 'http://mkt.wx.lppz.com/letui/';
	var baseUrl = 'http://lppz.letwx.com/';
	
	var KEY_CHECK_TIMES = "CHECK_TOKEN_";
	var MAX_CHECK_TIMES = 10;
	
	var checktimes;
	
	return {
		cfg:function(gameid,isdebug,scope) {
			var data = new Date();
			var scope = scope || "snsapi_base"; //snsapi_base,snsapi_userinfo
			KEY_API_TOKEN = KEY_API_TOKEN+"_"+gameid+"_"+[data.getYear(),data.getMonth(),data.getDate()].join("");
			KEY_API_OPENID = KEY_API_OPENID +"_"+gameid+"_"+[data.getYear(),data.getMonth(),data.getDate()].join("");
			
			if(isdebug) {
				OAUTH_URL = window.OAUTH_URL = baseUrl + 'oauth/lppztest?scope='+scope+'&gameid='+ gameid +'&redirect=';
			}
			else {
				OAUTH_URL = window.OAUTH_URL = baseUrl + 'oauth/lppz?scope='+scope+'&gameid='+ gameid +'&redirect=';
			}
			KEY_CHECK_TIMES = 'CHECK_TOKEN_'+gameid;
			checktimes = localStorage[KEY_CHECK_TIMES] || 0;
		},
		
		checkToken:function(cb,errcb) {	
            cb('x', 'xxx');
            return;
			var oAuthURLTMP = OAUTH_URL + encodeURIComponent(window.location.href);
			MHJ.mlog(oAuthURLTMP);
			var obj = MHJ.getUrlParam();
			var apitoken;
			var apiopenid;
			var redirect = window.location.href.split("&apiopenid")[0];
	    	redirect = redirect.split("&apitoken")[0]; //去掉参数中的openid,token后面的部分
	    	
		    if(!obj.from){	//在无from的情况下，才会认为当前url中的apitoken和apiopenid有效
		        apitoken = obj.apitoken;
		        apiopenid = obj.apiopenid;
		    }else{
		    	redirect = redirect.split("&from")[0];
				redirect = redirect.split("?from")[0];
		    	oAuthURLTMP = OAUTH_URL + encodeURIComponent(redirect);
		    }
		    
			if (!apitoken || !apiopenid) {
				if (!MHJ.getCookie(KEY_API_TOKEN) || !MHJ.getCookie(KEY_API_OPENID)){
					MHJ.setCookie(KEY_SERVER_REDIRECT,encodeURIComponent(redirect));
					checktimes ++;
					localStorage[KEY_CHECK_TIMES] = checktimes;
					
					if(checktimes < MAX_CHECK_TIMES) {
						setTimeout(function(){
							window.location.href = oAuthURLTMP;
						},500);
					}
					else {
						delete localStorage[KEY_CHECK_TIMES];
						MHJ.mlog(obj);
						errcb && errcb();
					}
					
				}else{
					cb && cb(MHJ.getCookie(KEY_API_OPENID),MHJ.getCookie(KEY_API_TOKEN));
				}
			} else {
				MHJ.setCookie(KEY_API_TOKEN, apitoken);
				MHJ.setCookie(KEY_API_OPENID, apiopenid);
				cb && cb(apiopenid,apitoken);
			}
		},
		
		getAuthId:function() {
			return MHJ.getCookie(KEY_API_OPENID);
		},
		
		getAuthToken:function() {
			return MHJ.getCookie(KEY_API_TOKEN);
		},
		
		clear:function() {
			MHJ.delCookie(KEY_API_TOKEN);
			MHJ.delCookie(KEY_API_OPENID);	
		}
	};
});
