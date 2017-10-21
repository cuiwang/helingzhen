var ajax_callback = 0;
jQuery(function($){
    $.ajaxSetup({
		beforeSend:function(xhr,self){
			if(self.url.indexOf("?") == -1){
				self.url += "?fhash=";
			}else{
				self.url += "&fhash=";
			}
		}
	});
});
$(function(){
	bind_user_loginout();
	bind_ajax_form();
	navScroll(".index_nav");

	// 显示筛选框
    $("#screen").bind('click',function(e){
		e.stopPropagation();
		if($("#selectbox1").is(":hidden")){
			$("#selectbox1").show();
		}
		else{
			$("#selectbox1").hide();
		}
        $("#screen").toggleClass("screen1");
    });
	// 阻止冒泡
	$("#selectbj").bind('click',function(e){
		e.stopPropagation();
	});
	
    $(".mybtn").bind('click',function(){
        $(".mybtn").toggleClass("screen1");        
    });

    // 查看更多回报
    $(".moreProject").bind('click',function(){
        $(".displayReturn").slideToggle("fast");
        $(".closemore").slideToggle("fast");
        $(".openmore").slideToggle("fast");      
    });
   
	//初始化头部搜索
	$(document).click(function(e){
		e.stopPropagation();
		$("#selectbox1").hide();
		$("#screen").removeClass("screen1"); 
	});
});

// 放大镜
function magicZoom(obj){
	var v=$(obj).attr("src");
    var h=$(document).height();
 	$(".outerdiv").slideToggle("fast");
    $("#bigimg").attr("src",v);
    $(".innerdiv").css("height",h+"px");
}
function close_magicZoom(obj){
	$(obj).slideToggle("fast");
}

//用于未来扩展的提示正确错误的JS
$.showErr = function(str,func)
{
	$.weeboxs.open(str, {boxid:'fanwe_error_box',contentType:'text',showButton:true, showCancel:false, showOk:true,title:'提示',width:300,type:'wee',onclose:func});
};

$.showSuccess = function(str,func)
{
	$.weeboxs.open(str, {boxid:'fanwe_success_box',contentType:'text',showButton:true, showCancel:false, showOk:true,title:'提示',width:300,type:'wee',onclose:func});
};
$.showConfirm = function(str,func,funcls)
{
	$.weeboxs.open(str, {boxid:'fanwe_confirm_box',contentType:'text',showButton:true, showCancel:true, showOk:true,title:'警告',width:300,type:'wee',onok:func,onclose:funcls});
};

/*验证*/
$.minLength = function(value, length , isByte) {
	var strLength = $.trim(value).length;
	if(isByte)
		strLength = $.getStringLength(value);
		
	return strLength >= length;
};

$.maxLength = function(value, length , isByte) {
	var strLength = $.trim(value).length;
	if(isByte)
		strLength = $.getStringLength(value);
		
	return strLength <= length;
};
$.getStringLength=function(str)
{
	str = $.trim(str);
	if(str=="")
		return 0;
	var length=0; 
	for(var i=0;i <str.length;i++) 
	{ 
		if(str.charCodeAt(i)>255)
			length+=2; 
		else
			length++; 
	}
	return length;
};

$.checkMobilePhone = function(value){
	if($.trim(value)!='')
		return /^\d{6,}$/i.test($.trim(value));
	else
		return true;
};
$.checkEmail = function(val){
	var reg = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/; 
	return reg.test(val);
};

function close_pop(){
	$(".dialog-close").click();
}

function bind_user_login()
{
	$("#user_login_form").find("input[name='submit_form']").bind("click",function(){

		do_login_user();
	});
	$("#user_login_form").find("input[name='user_pwd']").bind("keydown",function(e){
		if(e.keyCode==13)
		{
			do_login_user();
		}
	});
	$("#user_login_form").find("input[name='email']").bind("keydown",function(e){
		if(e.keyCode==9||e.keyCode==13)
		{
			$("#user_login_form").find("input[name='user_pwd']").val("");
			$("#user_login_form").find("input[name='user_pwd']").focus();
			return false;
		}
	});
	/*$("#user_login_form").find("input[name='email']").bind("focus",function(){
		if($.trim($(this).val())=="邮箱或者用户名")
		{
			$(this).val("");
		}
	});
	$("#user_login_form").find("input[name='email']").bind("blur",function(){
		if($.trim($(this).val())=="")
		{
			$(this).val("邮箱或者用户名");
		}

	});*/
	$("#user_login_form").bind("submit",function(){
		return false;
	});
}
function bind_user_loginout()
{
	$("#user_login_out").bind("click",function(){
		do_loginout($(this).attr("href"));
		return false;
	});
}
function do_login_user(){
	
	if($.trim($("#user_login_form").find("input[name='email']").val())=="")
	{
		$.show_tip("请输入邮箱或者用户名");	
		$("#user_login_form").find("input[name='email']").val("");
		$("#user_login_form").find("input[name='email']").focus();
		return false;
	}
	if($.trim($("#user_login_form").find("input[name='user_pwd']").val())=="")
	{
		$.show_tip("请输入密码");
		$("#user_login_form").find("input[name='user_pwd']").val("");
		$("#user_login_form").find("input[name='user_pwd']").focus();
		return false;
	}
	var ajaxurl = $("form[name='user_login_form']").attr("action");
	var query = $("form[name='user_login_form']").serialize() ;

	$.ajax({ 
		url: ajaxurl,
		dataType: "json",
		data:query,
		type: "POST",
		success: function(ajaxobj){
			if(ajaxobj.status==1)
			{
				//alert(ajaxobj.data);
				var integrate = $("<span id='integrate'>"+ajaxobj.data+"</span>");
				$("body").append(integrate);				
				$("#integrate").remove();
				close_pop();
				location.href = ajaxobj.jump;
				
			}
			else
			{
				if(ajaxobj.status==2){
					$.showConfirm("本站需绑定资金托管账户，是否马上去绑定",function(){
						location.href = ajaxobj.jump;
					},function(){
						location.reload();
					});
				}else{
					if(ajaxobj.status==0){
						$.showErr(ajaxobj.info);
					}
				}						
			}
		},
		error:function(ajaxobj)
		{
//			if(ajaxobj.responseText!='')
//			alert(ajaxobj.responseText);
		}
	});
}

function do_loginout(ajaxurl)
{	
	var query = new Object();
	query.ajax = 1;
	$.ajax({ 
		url: ajaxurl,
		dataType: "json",
		data:query,
		type: "POST",
		success: function(ajaxobj){
			if(ajaxobj.status==1)
			{
				//alert(ajaxobj.data);
				var integrate = $("<span id='integrate'>"+ajaxobj.data+"</span>");
				$("body").append(integrate);				
				$("#integrate").remove();
				location.href = ajaxobj.jump;
				
			}
			else
			{
				location.href = ajaxobj.jump;							
			}
		},
		error:function(ajaxobj)
		{
//			if(ajaxobj.responseText!='')
//			alert(ajaxobj.responseText);
		}
	});
}

function bind_ajax_form(){
	$(".ajax_form").find(".ui-button").bind("click",function(){
		$(".ajax_form").submit();
	});
	$(".ajax_form").bind("submit",function(){
		var ajaxurl = $(this).attr("action");
		var query = $(this).serialize() ;
		$.ajax({ 
			url: ajaxurl,
			dataType: "json",
			data:query,
			type: "POST",
			success: function(ajaxobj){
				if(ajaxobj.status==1)
				{
					if(ajaxobj.info!="")
					{
						$.showSuccess(ajaxobj.info,function(){
							if(ajaxobj.jump!="")
							{
								location.href = ajaxobj.jump;
							}
						});	
					}
					else
					{
						if(ajaxobj.jump!="")
						{
							location.href = ajaxobj.jump;
						}
					}
				}
				else
				{
					if(ajaxobj.info!="")
					{
						$.showErr(ajaxobj.info,function(){
							if(ajaxobj.jump!="")
							{
								location.href = ajaxobj.jump;
							}
						});	
					}
					else
					{
						if(ajaxobj.jump!="")
						{
							location.href = ajaxobj.jump;
						}
					}							
				}
			},
			error:function(ajaxobj)
			{
				if(ajaxobj.responseText!='')
				alert(ajaxobj.responseText);
			}
		});
		return false;
	});
}

function show_pop_login(){
	$.weeboxs.open(APP_ROOT+"/index.php?ctl=ajax&act=login", {boxid:'pop_user_login',contentType:'ajax',showButton:false, showCancel:false, showOk:false,title:'会员登录',width:300,type:'wee'});
}

function send_message(user_id){
	var ajaxurl = APP_ROOT+"/index.php?ctl=ajax&act=usermessage&id="+user_id;
	$.ajax({ 
		url: ajaxurl,
		dataType: "json",
		type: "POST",
		success: function(ajaxobj){
			if(ajaxobj.status==1)
			{
				$.weeboxs.open(ajaxobj.html, {boxid:'send_message',contentType:'text',showButton:false, showCancel:false, showOk:false,title:'发送私信',width:300,type:'wee'});				
				$("#user_message_form").find("textarea[name='message']").focus();
				bind_usermessage_form();
			}
			else if(ajaxobj.status==2)
			{
				show_pop_login();
			}
			else
			{
				$.showErr(ajaxobj.info);							
			}
		},
		error:function(ajaxobj)
		{
//			if(ajaxobj.responseText!='')
//			alert(ajaxobj.responseText);
		}
	});
}

function bind_usermessage_form(){
	$("#user_message_form").find(".ui-button").bind("click",function(){
		$("#user_message_form").submit();
	});
	$("#user_message_form").bind("submit",function(){
		if($.trim($("#user_message_form").find("textarea[name='message']").val())==""){
			$("#user_message_form").find("textarea[name='message']").focus();
			return false;
		}
		var ajaxurl = $(this).attr("action");
		var query = $(this).serialize();
		$.ajax({ 
			url: ajaxurl,
			dataType: "json",
			data:query,
			type: "POST",
			success: function(ajaxobj){
				close_pop();
				if(ajaxobj.status==1)
				{
					if(ajaxobj.info!="")
					{
						$.showSuccess(ajaxobj.info,function(){
							if(ajaxobj.jump!="")
							{
								location.href = ajaxobj.jump;
							}
						});	
					}
					else
					{
						if(ajaxobj.jump!="")
						{
							location.href = ajaxobj.jump;
						}
					}
				}
				else
				{
					if(ajaxobj.info!="")
					{
						$.showErr(ajaxobj.info,function(){
							if(ajaxobj.jump!="")
							{
								location.href = ajaxobj.jump;
							}
						});	
					}
					else
					{
						if(ajaxobj.jump!="")
						{
							location.href = ajaxobj.jump;
						}
					}							
				}
			},
			error:function(ajaxobj)
			{
				if(ajaxobj.responseText!='')
				alert(ajaxobj.responseText);
			}
		});
		return false;
	});
}

// 首页导航屏幕顶部固定
function navScroll(oComment_nav) {
    var oComment_nav = oComment_nav,
    oComment_height = $(".oComment_height").outerHeight(),
    bodyTop;
    window.onscroll = function(){
        bodyTop = document.body.scrollTop;
        changeclass(bodyTop);
    };
    function changeclass(top) {
        if (top >= oComment_height) {
            $(oComment_nav).addClass('fixednav');
        } else {
            $(oComment_nav).removeClass('fixednav');
        }
    }
}

// 编辑地址页点击选中
function selectadd(obj){
    $(obj).find(".edit_select").attr("checked","checked");
}

// 返回上一页
function return_prepage()  
{  
	if(window.document.referrer==""||window.document.referrer==window.location.href)  
	{  
		window.location.href="{dede:type}[field:typelink /]{/dede:type}";  
	}else  
	{  
		window.location.href=window.document.referrer;  
	}  
} 

function bind_del_consignee(consignee_id,del_url){
	$("#remove_but").bind("click",function(){
		id=consignee_id;
		var obj=new Object();
		obj.id=id;
		$.ajax({
			url:del_url,
			data:obj,
			type:"POST",
			dataType:"json",
			success:function(ajaxobj){
				if(ajaxobj.status==1){
 					$.showSuccess(ajaxobj.info,function(){
				   		if(ajaxobj.jump){
					   		window.location.href=ajaxobj.jump;
					   	}	
					});	
				}else{
					$.showSuccess(ajaxobj.info,function(){
					 	window.location.reload();
					});	
				}
			}
		});
	});
}

function bind_ajax_form_custom(str)
{
	$(str).find(".ui-button").bind("click",function(){
		$(str).submit();
	});
	$(str).bind("submit",function(){
		 
		var ajaxurl = $(this).attr("action");
		var query = $(this).serialize() ;
		$.ajax({ 
			url: ajaxurl,
			dataType: "json",
			data:query,
			type: "POST",
			success: function(ajaxobj){
				if(ajaxobj.status==1)
				{
					if(ajaxobj.info!="")
					{
						$.showSuccess(ajaxobj.info,function(){
							if(ajaxobj.jump!="")
							{
								location.href = ajaxobj.jump;
							}
						});	
					}
					else
					{
						if(ajaxobj.jump!="")
						{
							location.href = ajaxobj.jump;
						}
					}
				}
				else
				{
					if(ajaxobj.info!="")
					{
						$.showErr(ajaxobj.info,function(){
							if(ajaxobj.jump!="")
							{
								location.href = ajaxobj.jump;
							}
						});	
					}
					else
					{
						if(ajaxobj.jump!="")
						{
							location.href = ajaxobj.jump;
						}
					}							
				}
			},
			error:function(ajaxobj)
			{
				if(ajaxobj.responseText!='')
				alert(ajaxobj.responseText);
			}
		});
		return false;
	});
}

// 发送手机验证码
function send_mobile_verify_sms_custom(type,mobile,verify_name){
	var sajaxurl = APP_ROOT+"/index.php?ctl=ajax&act=send_change_mobile_verify_code";
	var squery = new Object();
	if(type!=2){
		if($.trim(mobile).length == 0)
		{			
 			$.show_tip("手机号码不能为空");
			return false;
		}
 		if(!$.checkMobilePhone(mobile))
		{
 			$.show_tip("手机号码格式错误");
			return false;
		}
			if(!$.maxLength(mobile,11,true))
		{
			$.show_tip("长度不能超过11位");
			return false;
		}
		squery.mobile = $.trim(mobile);
	}
	squery.step =type;
	$.ajax({ 
		url: sajaxurl,
		data:squery,
		type: "POST",
		dataType: "json",
		success: function(sdata){
			if(sdata.status==1)
			{
				code_lefttime = 60;
				code_lefttime_func_custom(type,mobile,verify_name,'mobile');
				$.showSuccess(sdata.info);
				return false;
			}
			else
			{
				$.showErr(sdata.info);
				return false;
			}
		}
	});	
}

// 发送邮箱验证码
function send_email_verify(type,email,verify_name){
	var sajaxurl = APP_ROOT+"/index.php?ctl=ajax&act=send_email_verify_code";
	var squery = new Object();
	if(type!=2){
		if($.trim(email).length == 0){			
			$.showErr("邮箱不能为空");
			return false;
		}
		if(!$.checkEmail(email)){
			$.showErr("邮箱格式错误");
			return false;
		} 
 	}
	squery.email = email;
	squery.step =type;
	$.ajax({ 
		url: sajaxurl,
		data:squery,
		type: "POST",
		dataType: "json",
		success: function(sdata){
			if(sdata.status==1)
			{
					code_lefttime = 60;
				code_lefttime_func_custom(type,email,verify_name,'email');
				$.showSuccess(sdata.info);
				return false;
			}
			else
			{
 					$.showErr(sdata.info);
				return false;
			}
		}
	});		
}

// 重新发送验证码
function code_lefttime_func_custom(type,mobile,verify_name,fun_name){
	var code_timeer=null;
	clearTimeout(code_timeer);
	$(verify_name).val(code_lefttime+"秒后重新发送");
	$(verify_name).css("color","#999");
	$(verify_name).addClass("bg_eee").removeClass("bg_red");
	code_lefttime--;
	if(code_lefttime >0){
		$(verify_name).attr("disabled","disabled");
		code_timeer = setTimeout(function(){code_lefttime_func_custom(type,mobile,verify_name);},1000);
	}
	else{
		code_lefttime = 60;
		$(verify_name).removeAttr("disabled");
		$(verify_name).val("发送验证码");
		$(verify_name).css("color","#fff");
		$(verify_name).addClass("bg_red").removeClass("bg_eee");
		$(verify_name).bind("click",function(){
			if(fun_name=='mobile'){
				send_mobile_verify_sms_custom(type,mobile,verify_name);
			}else{
				if(fun_name=='email'){
					send_email_verify(type,mobile,verify_name);
				}
			}
		});
	}
	
}

// 限制只能输入金额
function amount(th){
    var regStrs = [
        ['^0(\\d+)$', '$1'], //禁止录入整数部分两位以上，但首位为0
        ['[^\\d\\.]+$', ''], //禁止录入任何非数字和点
        ['\\.(\\d?)\\.+', '.$1'], //禁止录入两个以上的点
        ['^(\\d+\\.\\d{2}).+', '$1'] //禁止录入小数点后两位以上
    ];
    for(i=0; i<regStrs.length; i++){
        var reg = new RegExp(regStrs[i][0]);
        th.value = th.value.replace(reg, regStrs[i][1]);
    }
}
//先使用round函数四舍五入成整数，然后再保留指定小数位  
function round2(number,fractionDigits){     
    with(Math){     
        return round(number*pow(10,fractionDigits))/pow(10,fractionDigits);     
    }     
}
function ajax_form(ajax_form){
	var ajaxurl = $(ajax_form).attr("action");
	var query = $(ajax_form).serialize() ;
	$.ajax({ 
		url: ajaxurl,
		dataType: "json",
		data:query,
		type: "POST",
		success: function(ajaxobj){
			if(ajaxobj.status==1)
			{
				if(ajaxobj.info!="")
				{
					$.showSuccess(ajaxobj.info,function(){
						if(ajaxobj.jump!="")
						{
							location.href = ajaxobj.jump;
						}
					});	
				}
				else
				{
					if(ajaxobj.jump!="")
					{
						location.href = ajaxobj.jump;
					}
				}
			}
			else
			{
				if(ajaxobj.info!="")
				{
					$.showErr(ajaxobj.info,function(){
						if(ajaxobj.jump!="")
						{
							location.href = ajaxobj.jump;
						}
					});	
				}
				else
				{
					if(ajaxobj.jump!="")
					{
						location.href = ajaxobj.jump;
					}
				}							
			}
		},
		error:function(ajaxobj)
		{
			if(ajaxobj.responseText!='')
			alert(ajaxobj.responseText);
		}
	});
	return false;
}
function checkIpsBalance(type,user_id,func){
 	var query = new Object();
	query.ctl="collocation";
	query.act="QueryForAccBalance";
	query.user_type = type;
	query.user_id = user_id;
	query.is_ajax = 1;
	$.ajax({
		url:APP_ROOT + "/index.php",
		data:query,
		type:"post",
		dataType:"json",
		success:function(result){
			if(func!=null)
				func.call(this,result);
		}
	});
}

/**
 * 格式化数字
 * @param {Object} num
 */
function formatNum(num) {
	num = String(num.toFixed(2));
	var re = /(\d+)(\d{3})/;
	while (re.test(num)) {
		num = num.replace(re, "$1,$2");
	}
	return num;
}

// 返回顶部
function init_gotop() {
	if($("body").height() <= document.documentElement.clientHeight*1.8){
		$("#jumphelper").remove();
	}
	$("#gotop").click(function(){
		$("html,body").animate({scrollTop:0},"fast","swing");		
	});
}