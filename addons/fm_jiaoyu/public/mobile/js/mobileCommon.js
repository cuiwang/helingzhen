/*上拉加载代码*/
var pageScroll;
var onLoading=false;
function pageScrollLoad(){
	setTimeout(function(){
		if(pageScroll){
			pageScroll.destroy();
			pageScroll=null;
		}
		var load_more=$("#load_more");
		if(load_more.length>0){
			if($("#pageMore").length>0){
				pageScroll = new IScroll('#page-wrapper',{probeType: 2,momentum:false,click:true});
				$("#load_more").html("上拉加载更多");
				pageScroll.on('scrollEnd', updateOnScrollEnd);
				pageScroll.on('scroll',updateOnScroll);
			}else{
				$("#load_more").hide();			
				pageScroll=new IScroll('#page-wrapper',{click:true,momentum:false});
			}
		}else{
			pageScroll=new IScroll('#page-wrapper',{click:true,momentum:false});
		}
	},300);
}

function refreshScrollLoad(){	
	setTimeout(function(){		
		if($("#pageMore").length>0){
			$("#load_more").html("上拉加载更多");
			pageScroll.refresh();
		}else{
			$("#load_more").hide();
			pageScroll.destroy();
			pageScroll=null;
			pageScroll=new IScroll('#page-wrapper',{click:true,momentum:false});
		}
		onLoading=false;
	},200)
}

function updateOnScroll(){
	if(pageScroll.y<=pageScroll.maxScrollY){
		$("#load_more").html("正在为您加载");
	}
}

function updateOnScrollEnd(){	
	if(pageScroll.y<=pageScroll.maxScrollY){
		setTimeout(function(){
			if(!onLoading){
				onLoading=true;
				loadMore('listContanier',refreshScrollLoad);
			}
		},200);		
	}else{
		$("#load_more").html("上拉加载更多");
	}
}

function simpleScrollLoad(){
	setTimeout(function(){
		pageScroll=new IScroll('#page-wrapper',{click:true,momentum:false});	
	},300);
}


/*加载横向菜单*/
var navScroll;
function navScrollLoad(){
	setTimeout(function(){
		navScroll = new IScroll('#header-wrapper',{eventPassthrough:true, scrollX: true, scrollY: false, click:true});
	},300);
}

//===============================弹窗相关开始===============================

var loadingLayer;


/*提醒框*/
function showInfo(msg,timer) {
	layer.close(loadingLayer);
	if(timer==undefined){
		timer=2.5;
	}
	layer.open({content:msg,style:'text-align:center;border:none;background-color:rgba(0,0,0,0.6);color:#fff;',time:timer});
}

/*提醒框*/
function showInfo2(title,msg,timer) {
	layer.close(loadingLayer);
	if(timer==undefined){
		timer=2.5;
	}
	layer.open({title:title,content:msg,style:'text-align:center;border:none;background-color:rgba(0,0,0,0.6);color:#fff;',time:timer});
}
/*提醒框*/
function showInfo3(title,msg,timer) {
	layer.close(loadingLayer);
	if(timer==undefined){
		timer=2.5;
	}
	layer.open({title:title,content:msg,shade: [0.8, '#000000'],shadeClose: true,style:'text-align:center;border:none;background-color:#fff;color:#000000;',time:timer});
}
/*提醒框4*/
function showInfo4(msg,timer) {
	layer.close(loadingLayer);
	if(timer==undefined){
		timer=2.5;
	}
	layer.open({content:msg,style:'text-align:center;border:none;background-color:#fff;color:#000;',time:timer});
}

var cowebLoading=
'<div style="position:relative;width:100px;height:100px;text-align:center;">'
+'<div id="canvasFlash"></div>'
+'<div style="position:absolute;top:40%;font-size:1.4em;font-family:Helvetica;width:100%;text-align:center;color:#01B3E7;">CO</div>'
+'</div>';

var cowebloaders = 
{
	width: 100,
	height: 100,	
	stepsPerFrame: 1,
	trailLength: 1,
	pointDistance: .02,
	fps: 30,
	fillColor: '#01B3E7',
	step: function(point, index) {
		this._.beginPath();
		this._.moveTo(point.x, point.y);
		this._.arc(point.x, point.y, index * 7, 0, Math.PI*2, false);
		this._.closePath();
		this._.fill();	
	},	
	path: [
		['arc', 50, 50, 30, 0, 360]
	]
};

/*加载提醒框*/
function showLoading(msg,timer) {
	if(timer==undefined){
		timer=2.5;
	}
	if("coweb"==style){
		loadingLayer=layer.open({type: 1,content:cowebLoading,style:"background-color:rgba(0,0,0,0);box-shadow:none",time:timer});
		loadingTip();
	}else{
		loadingLayer=layer.open({type: 2,content:msg,className:'noticeInfo',time:timer});
	}
}

function loadingTip(){	
	if($('#canvasFlash').html()==''){		
		var d, a, container = document.getElementById('canvasFlash');
		d = document.createElement('div');
		a = new Sonic(cowebloaders);
		d.appendChild(a.canvas);
		container.appendChild(d);
		a.play();
	}
}
//===============================弹窗相关结束===============================

/**
 * 初始化界面的raido和check样式
 */
function initRadioGreen(){
	$('input').iCheck( {
		checkboxClass : 'icheckbox_square-green',
		radioClass : 'iradio_square-green',
		increaseArea : '10%'
	});
}
function initRadioOrange(){
	$('input').iCheck( {
		checkboxClass : 'icheckbox_square-yellow',
		radioClass : 'iradio_square-yellow',
		increaseArea : '10%'
	});
}


//======================UlSelect插件开始==========================

/*根据界面的设置获取值，并生成hidden的字段放于容器*/
function generateUlSelectResult(containerId){	
	$(".ulSelectItem").remove();
	$(".ulSelect").each(function(){
		var parentObject=$(this);		
		var key=parentObject.data("key");		
		var result=getUlSelectValue(parentObject);			
		if(result){
			$("#"+containerId).append("<input class='ulSelectItem' type='hidden' id='"+key.replace(".","_")+"' name='"+key+"' value='"+result+"'/>");
		}
	});
}

function getUlSelectValue($obj){
	var parentObject=$obj;	
	var selectedObjs=parentObject.children(".selected");//选中的值
	var value;//结果
	
	var type=parentObject.data("type");
	
	if('multiSelected'==type){//复选		
		if(selectedObjs){
			value="";
			for(var i=0;i<selectedObjs.length;i++){
				if(i==selectedObjs.length-1){
					value+=$(selectedObjs[i]).data("option");
				}else{
					value+=$(selectedObjs[i]).data("option")+", ";
				}
			}
			return value;
		}		
	}else{//单选	
		if(selectedObjs){	
			return selectedObjs.data("option");
		}
	}
	
	return "";
}

/*设置UlSelecte值和绑定事件*/
function initUlSelect(){
	
	$(".ulSelect").each(function(){
		
		var parentObject=$(this);
		var type=parentObject.data("type");		
		var value=parentObject.data("value");
		
		if('multiSelected'==type){//复选			
			if(value){
				parentObject.children("li").removeClass("selected");
			}			
			parentObject.children("li").each(function(){				
				$(this).click(function(){
					var selectedObj=$(this);
					selectedObj.toggleClass("selected");
					var callback=selectedObj.data("callback");
					if(callback){
						eval(callback);
					}
				});				
				if(value){//赋值
					var values=value.split(", ");
					if(values){
						for(var i=0;i<values.length;i++){
							var option=$(this).data("option");
							if(option){
								if(values[i]==option){
									$(this).click();
								}
							}
						}
					}
				}
			})
		}else{//单选			
			parentObject.children("li").each(function(){
				$(this).click(function(){//单击事件
					var selectedObj=$(this);
					selectedObj.parent().children("li").removeClass("selected");
					selectedObj.addClass("selected");					
					var callback=selectedObj.data("callback");
					if(callback){
						eval(callback);
					}
				});
				if(value){//赋值
					var option=$(this).data("option");
					if(option){
						if(value==option){
							$(this).click();
						}
					}
				}
			})
		}
	})
}
//======================UlSelect插件结束==========================

//智凡优家使用
function refreshCommunity(select){
	var newId = select.value;
	if(newId && newId.length > 0){
		communityId = newId;
		var form = { "cid" : newId };		
		formSubmit(form, url+"/wechat/HappyHome!refreshCommunity.action", null, null, null);
	}
}
//===========================以上为Eric整理部分===============================

/**
 * 分页代码
 */
function loadMore(container,callback) {
	var parent;
	var form; 
	if(container==undefined){
		form = $('#listForm');
		parent = $("#load_more").parent();
	}else{
		form = $('#listForm'+container);
		parent = $("#"+container);
	}
	
	$.ajax( {
		type : "post",
		url : form.attr("action"),
		data : form.serialize(),
		success : function(morehtml) {			
			if(container==undefined){
				parent.html(morehtml);
			}else{
				$('#load_more_div'+container).remove();				
				parent.append(morehtml);
				$("img").lazyload({
					effect : "fadeIn",
					threshold :600
				});
			}
			if(callback){
				callback();
			}
		}
	});
}

function scrollToTop(top) {
	$("html, body").animate({scrollTop : top}, 120);
}
/**
 * 返回顶部代码。
 */
function backToTop() {
	//scrollToTop(0);
	$("html, body").animate( {
		scrollTop : 0
	}, 120);
}

function isMobile(phone) {
	if (phone.length != 11)
		return false;
	else if (phone.substring(0, 1) != "1")
		return false;
	else if (isNaN(phone))
		return false;
	return true;
}

function isEmail(email) {
	var reg = new RegExp("\\w+([-+.]\\w+)*@\\w+([-.]\\w+)*\\.\\w+([-.]\\w+)*");
	return reg.test(email);
}

function isAge(age) {
	var reg = new RegExp("^\\d*[1-9]\\d*$");
	return reg.test(age);
}
function isURL(str_url) {
	var strRegex = "^((https|http|ftp|rtsp|mms)?://)";
	var re = new RegExp(strRegex);
	//re.test()
	if (re.test(str_url)) {
		return (true);
	} else {
		return (false);
	}
}
function imgLazyLoad(){
	$("img").lazyload({
		effect : "fadeIn",
		threshold :600
	});
}
function isEmpty(s){ return /^\s*$/.test(s); }

function setChk(chkObjName, objName) {
	var tmpObj = jQuery("input[name='" + objName + "']").val();
	if (tmpObj != undefined) {
		var chk = tmpObj.split("_");
		for (i = 0; i < chk.length; i++) {
			jQuery("input[name='" + chkObjName + "'][value=" + chk[i] + "]")
					.attr('checked', true);
		}
	}
}

function isApple(){
	var browser = {
		versions: function () {
		var u = navigator.userAgent, app = navigator.appVersion;
		return { //移动终端浏览器版本信息
			ios: !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/), //ios终端
			android: u.indexOf('Android') > -1 || u.indexOf('Linux') > -1, //android终端或uc浏览器
			iPhone: u.indexOf('iPhone') > -1, //是否为iPhone或者QQHD浏览器
			iPad: u.indexOf('iPad') > -1, //是否iPad
		};
		}(),
	}
	if (browser.versions.iPhone || browser.versions.iPad || browser.versions.ios) {
		return true;
	}else{
		return false;
	}	
}
function isWeiXin(){ 
	var ua = window.navigator.userAgent.toLowerCase(); 
	if(ua.match(/MicroMessenger/i) == 'micromessenger'){ 
		return true; 
	}else{ 
		return false; 
	} 
} 
// 对Date的扩展，将 Date 转化为指定格式的String
// 月(M)、日(d)、小时(h)、分(m)、秒(s)、季度(q) 可以用 1-2 个占位符， 
// 年(y)可以用 1-4 个占位符，毫秒(S)只能用 1 个占位符(是 1-3 位的数字) 
// 例子： 
// (new Date()).Format("yyyy-MM-dd hh:mm:ss.S") ==> 2006-07-02 08:09:04.423 
// (new Date()).Format("yyyy-M-d h:m:s.S")      ==> 2006-7-2 8:9:4.18 
Date.prototype.Format = function (fmt) { //author: meizz 
    var o = {
        "M+": this.getMonth() + 1, //月份 
        "d+": this.getDate(), //日 
        "h+": this.getHours(), //小时 
        "m+": this.getMinutes(), //分 
        "s+": this.getSeconds(), //秒 
        "q+": Math.floor((this.getMonth() + 3) / 3), //季度 
        "S": this.getMilliseconds() //毫秒 
    };
    if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    for (var k in o)
    if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
    return fmt;
}

/*保留小数点后length位*/
function formatFloat(data, length) {
	if (length == undefined) {
		length = 2;//默认是2位
	}
	var tmpData = parseFloat(data);
	if (isNaN(tmpData)) {
		return false;
	}
	var tmp = Math.pow(10, length);
	tmpData = Math.round(tmpData * tmp) / tmp;
	return tmpData;
}

//是否存在指定函数 
function isExitsFunction(functionName) {
    try {
		if (typeof(eval(functionName))==='function')
		{
			return true;
		}
	} catch(e) {}
	return false;
}