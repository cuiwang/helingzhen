var KK = window.KK = {
	_ucurl:'http://passport.chuanke.com',
	_kkurl:'http://old.www.chuanke.com',
	_resurl:'http://res.ckimg.com',
	_answerUrl : 'http://wenda.chuanke.com',
	_kkdownload:'http://download.chuanke.com/client/KK.exe',
	_kkipad:'http://kk.chuanke.com/ipad',
	_kkiphone:'http://kk.chuanke.com/iphone',
	_kkandroid:'http://kk.chuanke.com/android',
	_kkdesktop:'http://dl.baofeng.com/BFVCenter/kb-chuankewang-0321.exe',
	_uploadImgUrl : 'http://upload.img.chuanke.com',
	_uploadCourseware : 'http://web.v.chuanke.com/video.upload',
	//站点名称:index,uc,bbs
	_site:'index',
	_isHome:false,
	//第三方登录
	_sinaLogin:"http://passport.chuanke.com/login/auth?from=sina",
	_qqLogin:"http://passport.chuanke.com/login/auth?from=qq",
	_rrLogin:"http://passport.chuanke.com/login/auth?from=renren",
	_baiduLogin:"http://passport.chuanke.com/login/auth?from=baidu",
	
	user:{
		userid:0,
		username:'',
		nickname:'',
		cart:0
	},

	init:function() {
		var userinfo = '';
		var authinfo = this.readCookie("AuthInfo");
		if(typeof authinfo == "string"){
			try{
				userinfo = eval('('+this.base64_decode(authinfo)+')'); 
				this.user.userid	= userinfo.userid;
				this.user.username	= userinfo.username;
				this.user.nickname	= userinfo.nickname;
			}catch(e){}
		}
		
		var cartinfo = this.readCookie("CartInfo");
		if(typeof cartinfo == "string"){
			try{
				cart = eval('('+this.base64_decode(cartinfo)+')'); 
				this.user.cart	= cart;
			}catch(e){}
		}		
		
		this.isHome();
		
		this.buildHeaderBar();
		
		if (this.checkLogin()) {
			$("#kk_header_message").parent('div.messages').show();
			this.buildMessage();
			this.buildIdentifyMenu();
		}
		
		//显示头部下拉菜单信息
		this.showHeaderMenu();
		
		if (this.checkLogin()) {
			this.updateCartCount();

			//显示购买成功Tip
			var buy_tip = this.readCookie("ck_buy_tips");
			var ck_buy_mobile_tip = this.readCookie("ck_buy_mobile_tips");
			if (buy_tip && buy_tip == this.user.userid && ck_buy_mobile_tip && ck_buy_mobile_tip == this.user.userid) {
				//buyCourseTip('nomobile');
				$.cookie.set('ck_buy_tips', '0', 0, '/', 'chuanke.com');
				$.cookie.set('ck_buy_mobile_tips', '0', 0, '/', 'chuanke.com');
			} else if (buy_tip && buy_tip == this.user.userid){
				//buyCourseTip('mobile');
				$.cookie.set('ck_buy_tips', '0', 0, '/', 'chuanke.com');
				$.cookie.set('ck_buy_mobile_tips', '0', 0, '/', 'chuanke.com');			
			}

			//显示今日直播课Tip
			var today = new Date();
			var dateStr = today.getFullYear()+"-"+(today.getMonth()+1)+"-"+today.getDate();
			var first_login = this.readCookie("ck_first_login");
			if (first_login == 1) {
				$.ajax({
					url : this._kkurl+"/?mod=student&act=course&do=today&r="+Math.random(),
					type : "GET",
					async : false,
					dataType : 'script',
					success : function(){
						if(total_live_course > 0){
							todayLiveCourseTip(total_live_course);
						}
					}
				});
				$.cookie.set('ck_first_login', dateStr, 86400, '/', 'chuanke.com');
			}
		}
	},
	
	isHome:function(){
		var pageUrl = document.URL;
		pageUrl = pageUrl.substring(0, pageUrl.length-1);
		if(pageUrl == this._kkurl){
			this._isHome = true;
		}
	},
	
	//消息
	buildMessage : function(){
		$.ajax({
			url : this._kkurl+"/?mod=message&r="+Math.random(),
			type : "GET",
			async : false,
			dataType : 'script',
			success : function(){
				var theSum = parseInt(Sum);
				if(theSum > 0){
					$("#kk_header_message").append('(<span id="header_noticeNum" class="red">'+Sum+'</span>)');
				}
				var items = '';
				for(var i=0; i<NoticeData.length; i++){
					if(NoticeData[i][1] > 0 || i == 0){
						if(i == 0 && NoticeData[i][1] <= 0){
							items += "<li><a href="+NoticeData[i][2]+">"+NoticeData[i][0]+"</a></li>";
						}else{
							items += "<li><a href="+NoticeData[i][2]+">"+NoticeData[i][0]+"("+NoticeData[i][1]+")</a></li>";
						}
					}
				}
				$("#header_messageDetail").html(items);
			}
		})
	},

	buildIdentifyMenu:function() {
		$.getScript(this._kkurl+"/?do=identify&r="+Math.random(), function(){
			//校长菜单
			var monitor_menu = '';
			if (is_monitor == 1) {
				monitor_menu += '<li><a href="'+KK._kkurl+'/?mod=seller&act=order">销售订单</a></li>';
				monitor_menu += '<li><a href="'+KK._kkurl+'/?mod=seller&act=course&do=list">课程管理</a></li>';
				monitor_menu += '<li><a href="'+KK._kkurl+'/?mod=course&act=create&do=cate">发布课程</a></li>';
				monitor_menu += '<li><a href="'+KK._kkurl+'/?mod=school&act=show&sid='+KK.user.userid+'">我的学校</a></li>';
			} else {
				monitor_menu += '<li><a href="'+KK._kkurl+'/?mod=school&act=create">创建学校</a></li>';
			}
			$("#monitorMenu").html(monitor_menu);

			//老师菜单
			var teacher_menu = '';
			if (is_teacher == 1) {
				teacher_menu += '<li><a href="'+KK._kkurl+'/?mod=teacher&act=course&do=appointment">课程任命</a></li>';
				teacher_menu += '<li><a href="'+KK._kkurl+'/?mod=teacher&act=school">签约学校</a></li>';
			} else {
				teacher_menu += '<li><a href="'+KK._kkurl+'/?mod=teacher">申请老师</a></li>';
			}
			$("#teacherMenu").html(teacher_menu);
		});
	},
	
	updateCartCount:function(){
		$.ajax({
			url:this._kkurl+"/?mod=cart&act=show&do=count&r="+Math.random(),
			type:"GET",
			async:false,
			dataType:"script",
			success:function(){
				if(CartCnt > 0){
					$("#kk_header_cart").text("购物车("+CartCnt+")");
				}else{
					$("#kk_header_cart").text("购物车");
				}
			}
		});
	},

	buildHeaderBar:function() {
		var str = '<div class="sn_bd">';

		/*头部导航左侧*/
		str += '<div class="sn_bd_left">';
		str += '<p class="sn_item"><a href="'+this._kkurl+'">传课首页</a></p>';
		str += '<p class="sn_item"><a href="'+this._kkurl+'/help">传课帮助</a></p>';
		str += '<div class="sn_item hasChild"><a href="javascript:;">客户端下载</a><i class="arrow"></i><ul class="hasChild_sub" style="width:135px;"><li><a href="http://kk.chuanke.com/" target="_blank">传课KK 电脑版</a></li><li><a href="'+this._kkipad+'" target="_blank">传课KK iPad版</a></li><li><a href="'+this._kkiphone+'" target="_blank">传课KK iPhone版</a></li><li><a href="'+this._kkandroid+'" target="_blank">传课KK Android版</a></li></ul></div>';
		str += '</div>';

		/*头部导航右侧*/
		str += '<div class="sn_bd_right">';
		if(this.user.cart > 0){
			str += '<p class="sn_item"><a href="'+this._kkurl+'/?mod=cart&act=show" id="kk_header_cart">购物车('+this.user.cart+')</a></p>';
		}else{
			str += '<p class="sn_item"><a href="'+this._kkurl+'/?mod=cart&act=show" id="kk_header_cart">购物车</a></p>';
		}
		str += '<span class="c_999 fl">|</span>';
		//登陆信息
		var url = location.href;
		url = this.base64_encode(url);
		url = url.replace(/\//, '_');
		if (this.user.userid > 0) {
			str += '<div class="sn_item username hasChild">';
			str += '<span class="login_username">'+this.user.nickname+'</span>';
			str += '<i class="arrow"></i>';
			str += '<ul class="hasChild_sub" style="width:70px;">';
			str += '<li><a href="'+this._ucurl+'/info/detail">设置中心</a></li>';
			//str += '<li><a href="'+this._ucurl+'/login/logout/returl/1">退出</a></li>';
			str += '<li><a href="'+this._ucurl+'/login/logout/">退出</a></li>';
			str += '</ul>';
			str += '</div>';
			str += '<span class="c_999 fl">|</span>';
			//消息
			str += '<div class="sn_item messages hasChild" style="display:none">';
			str += '<a href="'+this._kkurl+'?mod=message&act=notice&do=list" id="kk_header_message">消息</a>';
			str += '<i class="arrow"></i>';
			str += '<ul class="hasChild_sub" style="width:120px;" id="header_messageDetail"></ul>';
			str += '</div>';
			str += '<span class="c_999 fl">|</span>';
			//我是学生
			str += '<div class="sn_item ch_student hasChild">';
			str += '<a href="'+this._kkurl+'/?mod=student&act=course">我的课程</a>';
			str += '<i class="arrow"></i>';
			str += '<ul class="hasChild_sub" style="width:90px;">';
			str += '<li><a href="'+this._kkurl+'/?mod=student&act=course&do=timelist">直播日历</a></li>';
			str += '<li><a href="'+this._kkurl+'/?mod=student&act=order">我的订单</a></li>';
			str += '<li><a href="'+this._kkurl+'/?mod=student&act=collect">收藏的课程</a></li>';
			str += '<li><a href="'+this._kkurl+'/?mod=student&act=school">收藏的学校</a></li>';
			str += '</ul>';
			str += '</div>';
			str += '<span class="c_999 fl">|</span>';
	
			//我是校长
			str += '<div class="sn_item ch_school hasChild">';
			str += '<a href="'+this._kkurl+'/?mod=seller">我是校长</a>';
			str += '<i class="arrow"></i>';
			str += '<ul class="hasChild_sub" style="width:85px;" id="monitorMenu"></ul>';
			str += '</div>';
			str += '<span class="c_999 fl">|</span>';
			//我是老师
			str += '<div class="sn_item ch_teacher hasChild">';
			str += '<a href="'+this._kkurl+'/?mod=teacher">我是老师</a>';
			str += '<i class="arrow"></i>';
			str += '<ul class="hasChild_sub" style="width:78px;" id="teacherMenu"></ul>';
			str += '</div>';
		} else{
			str += '<div class="sn_item login">';
			str += '<a class="a_f60 mr10" href="'+this._ucurl+'/login/index/ret/'+url+'">[登录]</a>';
			str += '<a class="a_f60" href="'+this._ucurl+'/reg/index/ret/'+url+'">[免费注册]</a>';
			str += '</div>';
			//第三方登录
			str += '<div class="sn_item"><a href="'+this._baiduLogin+'"><span class="login_bybd"></span><span class="ml5">百度登录</span></a></div>';
			str += '<div class="sn_item"><a href="'+this._sinaLogin+'"><span class="login_bysina"></span><span class="ml5">新浪登录</span></a></div>';
			str += '<div class="sn_item"><a href="'+this._qqLogin+'"><span class="login_byqq"></span><span class="ml5">QQ登录</span>	</a></div>';
			str += '<div class="sn_item"><a href="'+this._rrLogin+'"><span class="login_byrr"></span><span class="ml5">人人登录</span></a></div>';
		}
		
		str += '</div>';
		str += '</div>';
		$('#header-bar').html(str);
	},

	checkLogin:function() {
		if (this.user.userid > 0) {
			return true;
		} else {
			return false;
		}
	},

	showHeaderMenu:function() {
		$('.hasChild').hover(function(){
			var cobj = $(this);
			if (cobj.hasClass("username")) {
				var width = parseInt(cobj.width() + 30);
				width = parseInt(width);
				width = width > 93 ? width : 93;
				cobj.find('.hasChild_sub').width(width);
			}
			cobj.addClass('hasHover');
		},function(){
			$(this).removeClass('hasHover');
		});
	},

	readCookie:function(name) {
		var cookie = '';
		var search = name + '=';
		if (document.cookie.length > 0) {
			var offset = document.cookie.indexOf(search);
			if (offset != -1) {
				offset += search.length;
				var end = document.cookie.indexOf(";", offset);
				if (end == -1) {
					end = document.cookie.length;
				}
				cookie = document.cookie.substring(offset, end);
			}
		}
		return decodeURIComponent(cookie);
	},
	
	base64_decode:function(input) {
		var keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
		var output = "";
		var chr1, chr2, chr3 = "";
		var enc1, enc2, enc3, enc4 = "";
		var i = 0;
		if (input.length % 4 != 0) {
			return "";
		}
		var base64test = /[^A-Za-z0-9\+\/\=]/g;
		if (base64test.exec(input)) {
			return "";
		}
		do {
			enc1 = keyStr.indexOf(input.charAt(i++));
			enc2 = keyStr.indexOf(input.charAt(i++));
			enc3 = keyStr.indexOf(input.charAt(i++));
			enc4 = keyStr.indexOf(input.charAt(i++));
			chr1 = (enc1 << 2) | (enc2 >> 4);
			chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
			chr3 = ((enc3 & 3) << 6) | enc4;
			output = output + String.fromCharCode(chr1);
			if (enc3 != 64) {
				output+=String.fromCharCode(chr2);
			}
			if (enc4 != 64) {
				output+=String.fromCharCode(chr3);
			}
			chr1 = chr2 = chr3 = "";
			enc1 = enc2 = enc3 = enc4 = "";
		} while (i < input.length);
		return output;
	},

	base64_encode:function(str) {
		var base64EncodeChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
		var out, i, len;
		var c1, c2, c3;

		len = str.length;
		i = 0;
		out = "";
		while(i < len) {
			c1 = str.charCodeAt(i++) & 0xff;
			if(i == len) {
				out += base64EncodeChars.charAt(c1 >> 2);
				out += base64EncodeChars.charAt((c1 & 0x3) << 4);
				out += "==";
				break;
			}
			c2 = str.charCodeAt(i++);
			if(i == len) {
				out += base64EncodeChars.charAt(c1 >> 2);
				out += base64EncodeChars.charAt(((c1 & 0x3)<< 4) | ((c2 & 0xF0) >> 4));
				out += base64EncodeChars.charAt((c2 & 0xF) << 2);
				out += "=";
				break;
			}
			c3 = str.charCodeAt(i++);
			out += base64EncodeChars.charAt(c1 >> 2);
			out += base64EncodeChars.charAt(((c1 & 0x3)<< 4) | ((c2 & 0xF0) >> 4));
			out += base64EncodeChars.charAt(((c2 & 0xF) << 2) | ((c3 & 0xC0) >>6));
			out += base64EncodeChars.charAt(c3 & 0x3F);
		}
		return out;
	}
};

var _hmt = _hmt || [];
(function() {
   var hm = document.createElement("script");
   hm.src = "//hm.baidu.com/hm.js?2be0d6083ea4207036d33a4d8be519db";
   var s = document.getElementsByTagName("script")[0]; 
       s.parentNode.insertBefore(hm, s);
})();