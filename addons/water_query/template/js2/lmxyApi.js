//Ajax提交的Url
//var basePath="http://test.ilanmao.cn/jsLmxy/";
var basePath="http://ilanmao.cn/jsLmxy/";
//var basePath="http://192.168.3.20:8080/jsLmxy/";
//Ajax提交的来源，1表示是微信，2表示是App
var baseSource="1";

/**
 * 获取参数
 */
function getParameter(name){
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return unescape(r[2]); return null;
}

/**
 * 生成最终提交的URL
 * @param {url} 每个界面要提交的url,不含basePath,如v2/WashingProject/getAllWashingProject.shtml
 * @example 
	 * $.ajax( {
			type : "post",
			async: false,
			dataType : "HTML",
			url : getAjaxUrl("v2/WashingProject/getAllWashingProject.shtml"),
			success : function(html) {	
				 
			}
		});
 */
function getAjaxUrl(url){
	var openid = getParameter("openid");
	var extParams;
	var code = getParameter("code");
	if(openid == null || openid.trim() == "") {
		extParams= "baseSource=" + baseSource;
	}else{
		extParams= "openid="+openid + "&baseSource=" + baseSource;
	}
	if(code != null && code.trim() != ""){
		extParams = extParams+"&code="+code;
	}
	 
	var idx = url.indexOf("?");
	url = basePath + url;
	if(idx > 0)
		url = url + "&";
	else
		url = url + "?";
	url = url + extParams;
	return url;
}

function getOpenIdCode(data,normalCallBack) {
	try{
		var isJson = typeof(data) == "object" && Object.prototype.toString.call(data).toLowerCase() == "[object object]" && !data.length;  
		if(!isJson){
			data = jQuery.parseJSON(data);
		}
		if(data!=null && data.message=="findOpenid"){
			var url = window.location.href;
			//测试
			//var wxUrl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxa4c56bff4a1588b0&redirect_uri=REDIRECT_URL&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect";
			//正式
			var wxUrl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx60940ef9a0493429&redirect_uri=REDIRECT_URL&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect";
			url = encodeURIComponent(url);
			var requestUrl = wxUrl.replace(/REDIRECT_URL/, url);
			window.location.href=requestUrl;
			return;
		}else if(data!=null && data.message=="userIsNotBind"){
			var url = window.location.href;
			var wxUrl = basePath+"lmxyApp/washing/contactway.html?redirect_uri=REDIRECT_URL";
			url = encodeURIComponent(url);
			var requestUrl = wxUrl.replace(/REDIRECT_URL/, url);
			window.location.href=requestUrl;
			return;
		}else if(data!= null && data.message=="response"){
			showShade();
			window.location.href=basePath+data.url;
		}else if(data!= null && data.message=="toLogin"){
			var url = get_location_href();
			var wxUrl = "login.html?redirect_uri=REDIRECT_URL";
			url = encodeURIComponent(url);
			var requestUrl = wxUrl.replace(/REDIRECT_URL/, url);
			window.location.href=requestUrl;
			return;
		}else if(data!= null && data.message=="gotoPayment"){
			alert("您有未支付的订单，请支付后再下新单！");
			window.location.href=data.url;
			return;
		}else{
			normalCallBack();
		}
		//
	}catch(e){
		normalCallBack();
	}finally{
		closeShade();
	}
}

function get_location_href(){
	if(baseSource == 1){
		return window.location.href;
	}else{
		var diskPath = window.location.href;
		var relativePath = diskPath.substring(diskPath.indexOf("www/")+4);
		return relativePath;
	}
}
var HtmlUtil = {
		/*1.用浏览器内部转换器实现html转码*/
		htmlEncode:function (html){
			//1.首先动态创建一个容器标签元素，如DIV
			var temp = document.createElement ("div");
			//2.然后将要转换的字符串设置为这个元素的innerText(ie支持)或者textContent(火狐，google支持)
			(temp.textContent != undefined ) ? (temp.textContent = html) : (temp.innerText = html);
			//3.最后返回这个元素的innerHTML，即得到经过HTML编码转换的字符串了
			var output = temp.innerHTML;
			temp = null;
			return output;
		},
		/*2.用浏览器内部转换器实现html解码*/
		htmlDecode:function (text){
			//1.首先动态创建一个容器标签元素，如DIV
			var temp = document.createElement("div");
			//2.然后将要转换的字符串设置为这个元素的innerHTML(ie，火狐，google都支持)
			temp.innerHTML = text;
			//3.最后返回这个元素的innerText(ie支持)或者textContent(火狐，google支持)，即得到经过HTML解码的字符串了。
			var output = temp.innerText || temp.textContent;
			temp = null;
			return output;
		}
	};


Date.prototype.pattern=function(fmt) { 
	var o = { 
		"M+" : this.getMonth()+1, //月份 
		"d+" : this.getDate(), //日 
		"h+" : this.getHours()%12 == 0 ? 12 : this.getHours()%12, //小时 
		"H+" : this.getHours(), //小时 
		"m+" : this.getMinutes(), //分 
		"s+" : this.getSeconds(), //秒 
		"q+" : Math.floor((this.getMonth()+3)/3), //季度 
		"S" : this.getMilliseconds() //毫秒 
	}; 
	var week = { 
		"0" : "/u65e5", 
		"1" : "/u4e00", 
		"2" : "/u4e8c", 
		"3" : "/u4e09", 
		"4" : "/u56db", 
		"5" : "/u4e94", 
		"6" : "/u516d" 
	}; 
	if(/(y+)/.test(fmt)){ 
		fmt=fmt.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length)); 
	} 
	if(/(E+)/.test(fmt)){ 
		fmt=fmt.replace(RegExp.$1, ((RegExp.$1.length>1) ? (RegExp.$1.length>2 ? "/u661f/u671f" : "/u5468") : "")+week[this.getDay()+""]); 
	} 
	for(var k in o){ 
		if(new RegExp("("+ k +")").test(fmt)){ 
			fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length))); 
		} 
	} 
	return fmt; 
} 


function revertUTF8(szInput){
	 var x,wch,wch1,wch2,uch="",szRet="";
	 for (x=0; x<szInput.length; x++){
	  if (szInput.charAt(x)=="%") {
	   wch =parseInt(szInput.charAt(++x) + szInput.charAt(++x),16);
	   if (!wch) {
	    break;
	   }
	   if (!(wch & 0x80)) {
	    wch = wch;
	   } else if (!(wch & 0x20)){
	    x++;
	    wch1 = parseInt(szInput.charAt(++x) + szInput.charAt(++x),16);
	    wch  = (wch & 0x1F)<< 6;
	    wch1 = wch1 & 0x3F;
	     wch  = wch + wch1;
	   }  else {
	    x++;
	    wch1 = parseInt(szInput.charAt(++x) + szInput.charAt(++x),16);
	    x++;
	    wch2 = parseInt(szInput.charAt(++x) + szInput.charAt(++x),16);
	    wch  = (wch & 0x0F)<< 12;
	    wch1 = (wch1 & 0x3F)<< 6;
	    wch2 = (wch2 & 0x3F);
	    wch  = wch + wch1 + wch2;
	   }
	   szRet += String.fromCharCode(wch);
	  } else {
	   szRet += szInput.charAt(x);
	  }
	 }
	 return(szRet);
	}


function toUTF8(szInput){
	 var wch,x,uch="",szRet="";

	 for (x=0; x<szInput.length; x++){
	  wch=szInput.charCodeAt(x);
	  if (!(wch & 0xFF80)){
	   szRet += szInput.charAt(x);
	  }else if (!(wch & 0xF000)){
	   uch = "%" + (wch>>6 | 0xC0).toString(16) + 
	      "%" + (wch & 0x3F | 0x80).toString(16);
	   szRet += uch; 
	  }else{
	   uch = "%" + (wch >> 12 | 0xE0).toString(16) + 
	      "%" + (((wch >> 6) & 0x3F) | 0x80).toString(16) +
	      "%" + (wch & 0x3F | 0x80).toString(16);
	   szRet += uch; 
	  }
	 }
	 return(szRet);
	}


function Utf8ToUnicode(strUtf8)
{
        var bstr = "";
        var nTotalChars = strUtf8.length;        // total chars to be processed.
        var nOffset = 0;                                        // processing point on strUtf8
        var nRemainingBytes = nTotalChars;        // how many bytes left to be converted
        var nOutputPosition = 0;
        var iCode, iCode1, iCode2;                        // the value of the unicode.

        while (nOffset < nTotalChars)
        {
                iCode = strUtf8.charCodeAt(nOffset);
                if ((iCode & 0x80) == 0)                        // 1 byte.
                {
                        if ( nRemainingBytes < 1 )                // not enough data
                                break;

                        bstr += String.fromCharCode(iCode & 0x7F);
                        nOffset ++;
                        nRemainingBytes -= 1;
                }
                else if ((iCode & 0xE0) == 0xC0)        // 2 bytes
                {
                        iCode1 =  strUtf8.charCodeAt(nOffset + 1);
                        if ( nRemainingBytes < 2 ||                        // not enough data
                                 (iCode1 & 0xC0) != 0x80 )                // invalid pattern
                        {
                                break;
                        }

                        bstr += String.fromCharCode(((iCode & 0x3F) << 6) | (         iCode1 & 0x3F));
                        nOffset += 2;
                        nRemainingBytes -= 2;
                }
                else if ((iCode & 0xF0) == 0xE0)        // 3 bytes
                {
                        iCode1 =  strUtf8.charCodeAt(nOffset + 1);
                        iCode2 =  strUtf8.charCodeAt(nOffset + 2);
                        if ( nRemainingBytes < 3 ||                        // not enough data
                                 (iCode1 & 0xC0) != 0x80 ||                // invalid pattern
                                 (iCode2 & 0xC0) != 0x80 )
                        {
                                break;
                        }

                        bstr += String.fromCharCode(((iCode & 0x0F) << 12) |
                                        ((iCode1 & 0x3F) <<  6) |
                                        (iCode2 & 0x3F));
                        nOffset += 3;
                        nRemainingBytes -= 3;
                }
                else                                                                // 4 or more bytes -- unsupported
                        break;
        }

        if (nRemainingBytes != 0)
        {
                // bad UTF8 string.
                return "";
        }

        return bstr;
}