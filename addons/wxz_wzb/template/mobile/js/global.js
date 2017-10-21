
function resize(){
	$('#tabs-h li').width($('#tabs-h').width()/$('#tabs-h li').length);
	//视频比例
	if($(window).height()>=600){
		player_height = player_height + 90;
	}
	$('#wrap,#player').height(player_height);
	if($("#wrap iframe").length>0){
		$('#wrap iframe').height(player_height);
	}
	if($("#play").length>0){
		$('#play').height(player_height);
	}
	if($("#playVideo").length>0){
		$('#playVideo').height(player_height);
	}
	if($("#no_start_advs_box").length>0){
		$('#no_start_advs_box,#no_start_advs_box img').height(player_height);
	}
	if($("#live_advs_box").length>0){
		$('#live_advs_box,#live_advs_box img').height(player_height);
	}
	if($("#end_advs_box").length>0){
		$('#end_advs_box,#end_advs_box img').height(player_height);
	}
	var coment_box = WinH - $("#header").outerHeight() - $("#wrap").height() - $("#tabs-h").outerHeight() - $(".live-info").outerHeight()- $("#advs_box").height();
	var talk_box = coment_box -67;
	$("#comment_box").height(coment_box);
	//$("#danmu").css({"top":player_height+$("#header").outerHeight()+$("#advs_box").height()+$(".live-info").outerHeight()});
	$("#talk").height(talk_box);
}
function contains(str, obj) {
	var arr=new Array(); //定义一数组 
		arr=str.split(','); //字符分割
    var i = arr.length;  
    while (i--) {  
        if (arr[i] == obj) {  
            return true;  
        }  
    }  
    return false;  
}

function praise(){
		var lid=$("#live_id").val();
		var i = _afbooajax._querystring('i');
		var j = _afbooajax._querystring('j');
		$.ajax({
			type:'POST',
			url:'./index.php?i='+i+'&j='+j+'&c=entry&do=praise&m=afboo_zhbobo',
			data:{'listid':lid,'uc_openid':$("#wx_openid").val()},
			dataType:"json",	
			success:function(json){
							if(json.errno=='1'){
									$.tipsBox({
										obj: $(".head-user img"),
										toobj: $("#praise-num"),
										str: '<img src="'+$('.head-user img').attr('src')+'" style="width:28px;height:28px;border-radius:100%">',
										callback: function() {
										  $("#praise-num").html(json.num);
										}
									});
							}else if(json.errno=='-1'){
									_loading_toast._show('直播ID有误');
							}else if(json.errno=='-2'){
									_loading_toast._show('你已被禁言');	
							}else if(json.errno=='-3'){
									_loading_toast._show('您的资料不存在、亲重新进入！');
							}else if(json.errno=='-4'){
									_loading_toast._show('主播已开启全员禁言');
							}else if(json.errno=='-5'){
									_loading_toast._show('赞过了、不可以再赞哦！');
							}else{
								_loading_toast._show(json.message);
							}
			}
		})
}
//发送新消息
function send_message(){
			var content = $("#dt_review_form_content").val(),category_id=$("#category_id").val(),lid=$("#live_id").val();
			if(content==''){
				_loading_toast._show('评论内容不能为空！');
				return false;
			}else if(content.length > 100){
				_loading_toast._show('评论内容超出限制！');
				return false;
			}
			var i = _afbooajax._querystring('i');
			var j = _afbooajax._querystring('j');
			var post_url = './index.php?i='+i+'&j='+j+'&c=entry&do=comment_reply&m=afboo_zhbobo';
				var pagenum=$('#PageNum'),pageval=pagenum.val();
				$.ajax({
					type:'post',
					url:post_url,
					data:{'type':'comment','content':content,'category_id':category_id,'listid':lid,'uc_openid':$("#wx_openid").val()},
					dataType:"json",
					success:function(json){
						if(json.errno=='1'){
							_loading_toast._show2('提交成功');		
					    }else if(json.errno=='-2'){
							_loading_toast._show('直播id有误');
						}else if(json.errno=='-3'){
							_loading_toast._show('请先输入评论内容！')
						}else if(json.errno=='-4'){
							_loading_toast._show('粉丝资料不存在')
						}else if(json.errno=='-5'){
							_loading_toast._show('回复失败、您回复的评论已经被删除了')
						}else if(json.errno=='-6'){
							_loading_toast._show('你已经被禁言');
						}else if(json.errno=='-7'){
							_loading_toast._show('主播已开启禁言');
						}else{
								_loading_toast._show(json.message);
					     }
						$("#talk").animate({scrollTop:0},600);
						$("#dt_review_box_emo").hide();
						$("#dt_review_form_content").val('');
					}
				})			
	
}
//get more new message
function AutoLoad(){
	var pageReset=$('#PageNum').attr('date-reset'),html='',comtNum=0;
	var i = _afbooajax._querystring('i');
	var j = _afbooajax._querystring('j');
	var post_url = './index.php?i='+i+'&j='+j+'&c=entry&do=get_comment_newjoin&m=afboo_zhbobo';
	$.ajax({
		type:'get',
		url:post_url,
		data:{'type':'new_join','listid':$("#live_id").val(),'max':pageReset},
		dataType:"json",
		success:function(json){			
			switch(json.errno){
				case '1':{
					comtNum=Number($('#comment-num').text());
					$('#PageNum').attr('date-reset',json.num);
					$('#comment-num,#banner_pingluns').html(json.allnum);
					var people = Number(live_persons)+Number(json.zhbobo_user);
					$('#banner_persons').html(people);
					$('#praise-num').html(json.zan);
					$("#comment").prepend(json.html);
				}
			}
		}
	})
}
//get more news
function loadMore(){
	var page=Number($('#PageNum').val())+1;
	var i = _afbooajax._querystring('i');
	var j = _afbooajax._querystring('j');
	var post_url = './index.php?i='+i+'&j='+j+'&c=entry&do=get_comment_reply&m=afboo_zhbobo';
	$.ajax({
		type:'get',
		url:post_url,
		data:{'type':'comment','listid':$("#live_id").val(),'page':page,'update_time':$('#page_time').val()},
		dataType:"json",
		success:function(json){		
			switch(json.errno){
				case '1':{
					$('#PageNum').val(page);
					$('#comment-num').html(Number(json.num));//floor num			
					if(json.length==0){
						
						$(".tt-msg-load-desc a").text('没有更多了');
						$('.tt-msg-loading').hide();
						$('.tt-msg-load-desc').show();
						
					}else{
						
						$("#comment").append(json.html);
						load_status = 1;
						$('.tt-msg-loading').hide();
						$('.tt-msg-load-desc').show();
					}	
					break;
				}
				case '-1':{
					_loading_toast._show('ID有误');
					break;
				}
				case '-2':{
					if(json.num==0){
						$(".tt-msg-load-desc a").text('没有更多了');
						$('.tt-msg-loading').hide();
						$('.tt-msg-load-desc').show();
					}else{
						$(".tt-msg-load-desc a").text('没有更多了');
						$('.tt-msg-loading').hide();
						$('.tt-msg-load-desc').show();
					}
					break;
				}
			}
		}
	})
}
function AutoLoad2(){
	var pageReset=$('#NewsNum').attr('date-reset'),html='',comtNum=0;
	var i = _afbooajax._querystring('i');
	var j = _afbooajax._querystring('j');
	var post_url = './index.php?i='+i+'&j='+j+'&c=entry&do=get_news_new&m=afboo_zhbobo';
	$.ajax({
		type:'get',
		url:post_url,
		data:{'type':'new_join','listid':$("#live_id").val(),'max':pageReset},
		dataType:"json",
		success:function(json){			
			if(json.error==0){
					$('#NewsNum').attr('date-reset',json.num);
					$('#comment-num,#banner_pingluns').html(json.allnum);
					var people = Number(live_persons)+Number(json.zhbobo_user);
					$('#banner_persons').html(people);
					$('#praise-num').html(json.zan);
					$("#cmntList").prepend(json.html);
			}
		}
	})
}
function loadMore2(){
	var page=Number($('#NewsNum').val())+1;
	var i = _afbooajax._querystring('i');
	var j = _afbooajax._querystring('j');
	var post_url = './index.php?i='+i+'&j='+j+'&c=entry&do=get_comment_news&m=afboo_zhbobo';
	$.ajax({
		type:'get',
		url:post_url,
		data:{'type':'comment','listid':$("#live_id").val(),'page':page,'update_time':$('#page_time').val()},
		dataType:"json",
		success:function(json){	
			$(".news-msg-loading").hide();
			$(".loading_more_news").show();
			if(json.errno==0){
				load_news_status = 1;
				$('#NewsNum').val(page);
				$("#cmntList").append(json.message);
			}else if (json.errno==1){
				$(".loading_more_news").text('没有更多了');
			}else{
				_loading_toast._show(json.message);
			}
		}
	})
}

function gave(){
//SOCKET.emit('dashang',{'type':'redpack','gift_number':1,'money':'111','openid':$('#wx_openid').val()});

	var liveID=$("#live_id"),
		nowTime = new Date().getTime(),
		clickTime=liveID.attr("ctime")?liveID.attr("ctime"):0,
		alsoTime=nowTime-clickTime,
		money=$('#gave-money'),
		say=$('#gave-say'),
		isJson=false;
	if(isNaN(Number(money.val()))){
			_loading_toast._show('请输入正常的打赏金额');
			return false;
	}
	if(Number(money.val())=='' || Number(money.val())<=0){
		_loading_toast._show('请输入正常的打赏金额');
		return false;
	}else if(Number(money.val())<dashang_limit){
		_loading_toast._show('打赏金额不能小于'+dashang_limit+'元');
		return false;
	}else if(Number(money.val())>200){
		_loading_toast._show('打赏金额不能大于200元');
		return false;
	}
	if(say.val()==''){
		_loading_toast._show('祝福语没有填写！');
		return false;
	}
	if(clickTime && alsoTime < 60000){
		_loading_toast._show('操作过于频繁，稍后再试');
		return false;
	}else{
		liveID.attr("ctime",nowTime);
		if(liveID.attr("ctime")){
			var i = _afbooajax._querystring('i');
			var j = _afbooajax._querystring('j');
			var post_url = './index.php?i='+i+'&j='+j+'&c=entry&do=wechatpay&m=afboo_zhbobo';
			$.ajax({
				type:'post',
				data:{'gift_number':'1','money':money.val(),'type':'redpack','content':say.val(),'listid':$("#live_id").val(),'category_id':$("#category_id").val()},
				url:post_url,
				dataType:"json",
				//async:false,
				success: function(json){
					if(json.errno=='1'){
						var e = json.data;
						WeixinJSBridge.invoke(
						   'getBrandWCPayRequest', {
							   "appId":e.appId,         
							   "timeStamp": ""+e.timeStamp ,             
							   "nonceStr": e.nonceStr,   
							   "package":e.package,     
							   "signType":e.signType,             
							   "paySign":e.paySign
						   },
						   function(res){  
							   if(res.err_msg == "get_brand_wcpay_request:ok") {
								    close_give();
									liveID.attr("ctime",0);
									SOCKET.emit('dashang',{'type':'redpack','gift_number':1,'money':$('#gave-money').val(),'openid':$('#wx_openid').val()});
								}else{
									if(res.err_msg == "get_brand_wcpay_request:cancel"){
										var tips = '你取消了支付';
									}else{
										var tips = '支付失败';
									}
									liveID.attr("ctime",0);
									close_give();
									_loading_toast._show(tips);	
								}
						   }
					   );   
					}else{
						_loading_toast._show(json.message);
					}
				}	
			})
		}
	}
}

//摇一摇
function shakeEventDidOccur(){
	if(mobile_type==0){
		$("#guanzhu_pop").show();
		return;
	}
	var shake=$('#shake'),limit=$('#shake-limit font');
	if(shake.is(':hidden')==false && shake.attr('data-shake')=='on'){
		//是否需要填写信息
		if($("#shake_status").val()=='0'){
			if($('#afboo_pop').css('display')=='none'){
				$("#afboo_pop").show();
			}
			return;
		}
		//摇了只能等结束再摇	
		shake.attr('data-shake','off');
		//获取MP3播放对象并播放
		var shakeYao=$('#shake-yao')[0],shakeKai=$('#shake-kai')[0],liveID=$("#live_id");
		shakeYao.pause();
		shakeYao.play();			
		//播完音乐再执行	
		setTimeout(function(){
			var i = _afbooajax._querystring('i');
			var j = _afbooajax._querystring('j');
			var post_url = './index.php?i='+i+'&j='+j+'&c=entry&do=shake&m=afboo_zhbobo';
			$.ajax({
				type:'post',
				data:{'listid':liveID.val()},
				url:post_url,
				dataType:"json",
				async:false,
				success: function(json){
					//shakeKai.pause();
					//shakeKai.play();	
					if(json.errno=='1'){
							limit.text(json.have_chance);
							$("#jpimg").attr("src",json.img);
							$("#jpname").text(json.name);
							  if(json.get_url!=''){
								 $("#zjlbtn .button_yes").html("<a class=\"get_btn\" href="+json.get_url+">领奖</a>");
							  }else{
								 $("#zjlbtn .button_yes").html("<a class=\"get_btn\" href=\"javascript:;\" onclick=\"get_luck_award()\">领奖</a>");
							  }
							$("#zjlBox").show();

					}else{	
							if(json.errno=='-5'){
								limit.text(json.have_chance);
								$("#mzjBox").show();
								return;
							}
							if(json.errno=='-3'){
								$('#shake-limit').html('<font color=red>你摇一摇机会已经用完啦！</font>');
							}else{
							    _loading_toast._show(json.message);
								shake.attr('data-shake','on');
							} 
							
							
							
					}
				}	
			})
		},1100);		
	}
}
function close_zj(){
	$("#zjlBox").hide();
	$('#shake').attr('data-shake','on');
}
function no_zj(){
	$("#mzjBox").hide();
	$('#shake').attr('data-shake','on');
}
function get_luck_award(){
	$("#zjlBox").hide();
	alert('直播过后、工作人员会直接联系你！');
	$('#shake').attr('data-shake','on');
}
var send_btn = false;
function send_rm(){
	var realname = $("#realname").val();
	var mobile = $("#mobile").val();
	var address = $("#address").val();
	if(realname == '') {
		_loading_toast._show('请填入真实的姓名');
	return;
	}
	if(mobile == '') {
		_loading_toast._show('请输入手机号码，以便领取奖品');
		return;
	}
	if(address == '') {
		_loading_toast._show('请输入收货地址，以便领取奖品');
		return;
	}
	var submitData = {
	"realname" : realname,
	"mobile" : mobile,
	"address":address,
	'listid':$("#live_id").val()
	};
	if(send_btn) return;
	send_btn = true;
	var i = _afbooajax._querystring('i');
	var j = _afbooajax._querystring('j');
	var post_url = './index.php?i='+i+'&j='+j+'&c=entry&do=update_fans&m=afboo_zhbobo';
	$.ajax({
	type : "POST",
	url : post_url,
	data : submitData,
	dataType : "json",
	success : function(data){
		send_btn = false;
		if(data.errno == '1') {
			$("#error_tip").text('');
			$("#afboo_pop").hide();
			$("#shake_status").val('1');
		} else {
			_loading_toast._show(data.message);
		}
	}
	})
}
function change_gift(gift_id,listid){
	if(gift_id != '' && listid!=''){
		$(".reward-img-wrap").removeClass('selected');
		if($("#gift_"+gift_id).length>0){
			$("#gift_"+gift_id).addClass('selected');
			var gift_number = parseInt($("#gift_number").val());
			if(gift_number > 0){
				$("#total_price").text(parseFloat($("#gift_"+gift_id).attr("data-one")*gift_number).toFixed(2));
			}
			
		}
	}
}
function number_change(){
	var gift_number = parseInt($("#gift_number").val());
	if(gift_number > 0){
		var gift_id = $(".selected").attr("data-type");
		$('#total_price').text(parseFloat($("#gift_"+gift_id).attr("data-one")*gift_number).toFixed(2));
	}
}
function gift_pay(){
		var money  = $('#total_price').text();
		if(Number(money)=='' || Number(money)<=0){
			_loading_toast._show('请输入正常的礼物数量');
			return false;
		}else if(Number(money)<0.01){
			_loading_toast._show('礼物金额不能小于0.01元');
			return false;
		}
		if(isNaN(Number($("#gift_number").val()))){
			_loading_toast._show('礼物数量必须是大于0的数字');
			return false;
		}
		var i = _afbooajax._querystring('i');
		var j = _afbooajax._querystring('j');
		var post_url = './index.php?i='+i+'&j='+j+'&c=entry&do=wechatpay&m=afboo_zhbobo';
		$.ajax({
				type:'post',
				data:{'gift_number':$("#gift_number").val(),'money':money,'type':$(".selected").attr("data-type"),'content':$(".selected").next().text(),'listid':$("#live_id").val(),'category_id':$("#category_id").val()},
				url:post_url,
				dataType:"json",
				//async:false,
				success: function(json){
					if(json.errno=='1'){
						var e = json.data;
						WeixinJSBridge.invoke(
						   'getBrandWCPayRequest', {
							   "appId":e.appId,         
							   "timeStamp": ""+e.timeStamp ,             
							   "nonceStr": e.nonceStr,   
							   "package":e.package,     
							   "signType":e.signType,             
							   "paySign":e.paySign
						   },
						   function(res){  
							   $(".gift_list").hide();	
							   if(res.err_msg == "get_brand_wcpay_request:ok") {
								   var select_type = $(".selected").attr("data-type");
								   if($("#send_"+select_type).length>0){
										var now_num = Number($("#send_"+select_type).text())+Number($("#gift_number").val());
										$("#send_"+select_type).text(now_num);
								   }
								   close_gift_list();
								   SOCKET.emit('gift',{'type':select_type,'gift_number':$("#gift_number").val(),'money':$('#total_price').text(),'openid':$('#wx_openid').val(),'content':$(".selected").next().text()});	
								}else{
									if(res.err_msg == "get_brand_wcpay_request:cancel"){
										var tips = '你取消了支付';
									}else{
										var tips = '支付失败';
									}
										
									_loading_toast._show(tips);
								}
						   }
					   );   
					}else{
						_loading_toast._show(json.message);
					}
				}	
			})
		
	}
function close_give(){
	//talk_can_scroll();
	$("body").animate({scrollTop:0},600);
	$("#talk").animate({scrollTop:0},600);
	$("#dashang_box").hide();
	$("#review_box").show();
}
function close_gift_list(){
	//talk_can_scroll();
	$("body").animate({scrollTop:0},600);
	$("#talk").animate({scrollTop:0},600);
	$("#reward-layer-box").hide();
	$("#review_box").show();
}

var _emo= {
    _text: ["[笑脸]", "[感冒]", "[流泪]", "[发怒]", "[爱慕]", "[吐舌]", "[发呆]", "[可爱]", "[调皮]", "[寒冷]", "[呲牙]", "[闭嘴]", "[害羞]", "[苦闷]", "[难过]", "[流汗]", "[犯困]", "[惊恐]", "[咖啡]", "[炸弹]", "[西瓜]", "[爱心]", "[心碎]"],
    _indexOf: function(text) {
        if (_emo._text.indexOf) {
            return _emo._text.indexOf(text);
        }
        for (var i = 0, _len = _emo._text.length; i < _len; i++) {
            if (_emo._text[i] == text) {
                return i;
            }
        }
        return -1;
    },
    _insertFun: null,
    _show: function(id, fun) {
        _emo._insertFun = fun;
        if ($("#" + id).children().length == 0) {
            var _html = "<ul>";
            for (var i = 0; i < 23; i++) {
                _html += "<li class='emo' ontouchstart='' onclick='_emo._insert(" + i + ")'><img src='" + '../addons/afboo_zhbobo/template/mobile/emo/' + (i + 1) + ".png'></li>";
            }
            _html += "</ul>";
            $("#" + id).html(_html);
        }
		
			$("#" + id).slideToggle();
		
    },
    _hide: function(id) {
        $("#" + id).hide();
    },
    _insert: function(index) {
        (_emo._insertFun)(index);
    },
    _toCode: function(content) {
        return content.replace(/\[[\u4e00-\u9fa5]{1,2}\]/g, function(a) {
            var _code = _emo._indexOf(a) + 1;
            return _code == 0 ? a : "[/" + _code + "]";
        });
    }
};
var _afbooajax = {
	_querystring : function(name){ 
		var result = location.search.match(new RegExp("[\?\&]" + name+ "=([^\&]+)","i")); 
		if (result == null || result.length < 1){ 
			return "";
		}
		return result[1]; 
	}
};

var _loading_toast= {
    _center: function() {
		var _left = ($(window).width() - $("#toast").outerWidth()) / 2 + "px";
        $("#toast").css({
            "top": "50%",
            "left": _left
        });
    },
    _show: function(text, fun) {
        $("#toast").html(text);
        _loading_toast._center();
        $("#toast").show();
        $("#toast").bind("resize", _loading_toast._center);
        setTimeout(function() {
            _loading_toast._hide(fun);
        }, 3 * 1000);
    },
	_show2: function(text, fun) {
        $("#toast").html(text);
        _loading_toast._center();
        $("#toast").show();
        $("#toast").bind("resize", _loading_toast._center);
        setTimeout(function() {
            _loading_toast._hide(fun);
        }, 1000);
    },
    _hide: function(fun) {
        $("#toast").hide();
        $("#toast").unbind("resize");
        if (fun) {
            (fun)();
        }
    }
};

function talk_not_scroll(){
		$('#talk').bind('touchmove',function(e){
                e.preventDefault();
				e.stopPropagation();
		})
}
function talk_can_scroll(){
		$('#talk').unbind('touchmove');
}
function number_focus(){
	$("body").animate({scrollTop: document.body.clientHeight},600);
}
function code_look(){
	var look_code = $("#look_code").val();
	console.log(look_code);return false;
	if('undefined'==look_code || ''==look_code){
		_loading_toast._show('请先输入观看密码');
		return;
	}
	var i = _afbooajax._querystring('i');
	var j = _afbooajax._querystring('j');
	var post_url = './index.php?i='+i+'&j='+j+'&c=entry&do=look_code&m=afboo_zhbobo';
	$.ajax({
		type : "POST",
		url : post_url,
		data : {'listid':$("#live_id").val(),'look_code':look_code,'uc_openid':$("#wx_openid").val()},
		dataType : "json",
		success : function(data){
			_loading_toast._show(data.message);
			if(data.errno == '0' || data.errno == '-10') {
				$("#code_pop").hide();
			}
		}
	})
}
function pay_look(){
	var i = _afbooajax._querystring('i');
	var j = _afbooajax._querystring('j');
	var post_url = './index.php?i='+i+'&j='+j+'&c=entry&do=look_pay&m=afboo_zhbobo';
	$.ajax({
		type : "POST",
		url : post_url,
		data : {'listid':$("#live_id").val()},
		dataType : "json",
		success : function(json){
				if(json.errno=='1'){
						var e = json.data;
						WeixinJSBridge.invoke(
						   'getBrandWCPayRequest', {
							   "appId":e.appId,         
							   "timeStamp": ""+e.timeStamp ,             
							   "nonceStr": e.nonceStr,   
							   "package":e.package,     
							   "signType":e.signType,             
							   "paySign":e.paySign
						   },
						   function(res){  
							  
							   if(res.err_msg == "get_brand_wcpay_request:ok") {
								   _loading_toast._show('支付成功');
								   $("#pay_pop").hide();
								}else{
									if(res.err_msg == "get_brand_wcpay_request:cancel"){
										var tips = '你取消了支付';
									}else{
										var tips = '支付失败';
									}
										
									_loading_toast._show(tips);
								}
						   }
					   );   
					}else{
					  if(json.errno!='-10'){
						_loading_toast._show(json.message);
					  }else{
						 _loading_toast._show('支付成功');
					     $("#pay_pop").hide();
					  }
					}
		}
	})
}
