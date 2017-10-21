/**
 * 判断当前运行环境是否为PC
 */
function lmxyInWindow(){
	return !(mui.os.android || mui.os.ios || mui.os.ipad || mui.os.iphone);
}
/**
 * 初始化界面事件，针对PC端
 */
function lmxyInitEvent(w){
// 空函数 
	function navMenuClickAndTap(e){
		var ips = location.href.indexOf("www/");
		var targetTab = this.getAttribute('href'); 
		if(ips>0){
			targetTab = location.href.substr(0, ips + 4) + targetTab;
		}
		//alert(targetTab); 
		if(baseSource == 2){
			targetTab = targetTab.substring(targetTab.indexOf("www/")+4);
		}
		//alert(targetTab); 
		location.replace(targetTab);
		/*if( location.href.indexOf(targetTab) ==-1){
//			mui.openWindow({
//			    url: targetTab, 
//			    show:{
//			      autoShow:false
//			    }
//			});
			
		}*/
		
	}
	mui('.mui-bar-tab').on('tap', 'a', navMenuClickAndTap);
	if(lmxyInWindow()){
		mui('.mui-bar-tab').on('click', 'a', navMenuClickAndTap); 
		mui('.mui-card').on('click','div', function(e){
			var obj = document.getElementsByClassName("mui-expand"); 
			
			if(obj){
				for(var i=0; i<obj.length; i++)
				{
					obj[i].className = "mui-table-view-cell mui-collapse"; 
				} 
			} 
			this.className = "mui-table-view-cell mui-expand";
			var targetTab = this.getAttribute('href'); 
			 //alert(this.classList + "," + targetTab);
		});
	}
	
	
	
};

var getPosTimer = null;
/**
 * 初始化底部导航菜单
 * http://test.ilanmao.cn/jsLmxy/lmxyApp/index.html
 */
function lmxyInitMenus(activeCaption,level){
	setTimeout(function(){getPos();},1000);
	//getPosTimer = setInterval(function(){getPos();},1000);
	var tabMenus = "";
	if(baseSource == 2){
		if(level == 0){
			tabMenus=[{"url":"index.html","caption":"我要洗衣","icon":"mui-icon-download","enabled":1},{"url":"myorder.html","caption":"我的订单","icon":"mui-icon-email","enabled":1},{"url":"myaccount.html","caption":"我的帐户","icon":"mui-icon-contact","enabled":1},{"url":"msgcenter.html","caption":"消息中心","icon":"mui-icon-chat","enabled":0}];
		}else if(level == 1){
			tabMenus=[{"url":"../index.html","caption":"我要洗衣","icon":"mui-icon-download","enabled":1},{"url":"../myorder.html","caption":"我的订单","icon":"mui-icon-email","enabled":1},{"url":"../myaccount.html","caption":"我的帐户","icon":"mui-icon-contact","enabled":1},{"url":"../msgcenter.html","caption":"消息中心","icon":"mui-icon-chat","enabled":0}];
		}
	}else{
		//tabMenus=[{"url":"/jsLmxy/lmxyApp/index.html","caption":"我要洗衣","icon":"mui-icon-download","enabled":1},{"url":"/jsLmxy/lmxyApp/myorder.html","caption":"我的订单","icon":"mui-icon-email","enabled":1},{"url":"/jsLmxy/lmxyApp/myaccount.html","caption":"我的帐户","icon":"mui-icon-contact","enabled":1},{"url":"/jsLmxy/lmxyApp/msgcenter.html","caption":"消息中心","icon":"mui-icon-chat","enabled":0}];
		tabMenus=[{"url":basePath+"lmxyApp/index.html","caption":"我要洗衣","icon":"mui-icon-download","enabled":1},{"url":basePath+"lmxyApp/myorder.html","caption":"我的订单","icon":"mui-icon-email","enabled":1},{"url":basePath+"lmxyApp/myaccount.html","caption":"我的帐户","icon":"mui-icon-contact","enabled":1},{"url":basePath+"lmxyApp/msgcenter.html","caption":"消息中心","icon":"mui-icon-chat","enabled":0}];
	}
	//tabMenus=[{"url":basePath+"lmxyApp/index.html","caption":"我要洗衣","icon":"mui-icon-download","enabled":1},{"url":basePath+"lmxyApp/myorder.html","caption":"我的订单","icon":"mui-icon-email","enabled":1},{"url":basePath+"lmxyApp/myaccount.html","caption":"我的帐户","icon":"mui-icon-contact","enabled":1},{"url":basePath+"lmxyApp/msgcenter.html","caption":"消息中心","icon":"mui-icon-chat","enabled":0}];
	var s='';
	for(var i=0; i< tabMenus.length; i++){
		var item = tabMenus[i];
		if(item.enabled){ 
			s = s + '<a class="mui-tab-item';
			if(item.caption == activeCaption){
				s = s + ' mui-active';
			}
			s = s + '" href="' + item.url + '" ><span class="mui-icon ' +
				item.icon + '"></span><span class="mui-tab-label">' +
				item.caption + '</span></a>';
		} 
	}	 
	var obj = document.getElementById("tabNav");
	if(obj){
		obj.innerHTML = s;
	} 
	lmxyInitEvent(window);	
}

function geoInf( position ) {
	var str = "";
	var timeflag = position.timestamp;//获取到地理位置信息的时间戳；一个毫秒数；
	str += "时间戳："+timeflag+"<br/>";
	var codns = position.coords;//获取地理坐标信息；
	var lat = codns.latitude;//获取到当前位置的经度；
	str += "经度："+lat+"<br/>";
	var longt = codns.longitude;//获取到当前位置的纬度
	str += "纬度："+longt+"<br/>";
	var alt = codns.altitude;//获取到当前位置的海拔信息；
	str += "海拔："+alt+"<br/>";
	var accu = codns.accuracy;//地理坐标信息精确度信息；
	str += "精确度："+accu+"<br/>";
	var altAcc = codns.altitudeAccuracy;//获取海拔信息的精确度；
	str += "海拔精确度："+altAcc+"<br/>";
	var head = codns.heading;//获取设备的移动方向；
	str += "移动方向："+head+"<br/>";
	var sped = codns.speed;//获取设备的移动速度；
	str += "移动速度："+sped;
	//alert( str );
	//提交数据
	var url1 = basePath + "geographical/position/saveUserPosition.shtml";
	//?openid=test1234&gpslat=30.289236&gpslong=120.107170&gpspre=40.000000
	url1 = url1+ "?gpslat="+lat+"&gpslong="+longt+"&gpspre="+accu;
	$.ajax({
		type: "POST", 
		data:{},
		url: url1,
		success:function(result){
			setTimeout(function(){getPos();},1000);
		},
		error : function(result){
			setTimeout(function(){getPos();},1000);
		}
	});
}
function getPos() {
	plus.geolocation.getCurrentPosition( geoInf, function ( e ) {
		//alert( "获取位置信息失败："+e.message );
	} );
}

