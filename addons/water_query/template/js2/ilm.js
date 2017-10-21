// JavaScript Document
//设置背景遮罩层
function showShade(isShow){
	if(jQuery("#layoutBg").length > 0){return;}
	var winHeight = $(document).height();//findDimensions();
	var e = document.createElement("div");e.id="layoutBg";e.style.cssText="height:"+winHeight+"px;";
	//isShow = 1 需要等待效果
	if(isShow == 1){
		e.innerHTML="<span class='mui-icon mui-icon-spinner mui-spin'></span>";
	}
	document.body.appendChild(e);
};
//关闭背景遮罩层
function closeShade(){
	if($("#layoutBg").length > 0){$("#layoutBg").remove()}
}
var InterValObj; //timer变量，控制时间
var curCount;//当前剩余秒数行

function times(){
	if(document.getElementById("code-time").getAttribute("data-code") == "yes"){
		curCount = 5;	//倒计时
		document.getElementById("code-time").setAttribute("data-code","no");
		document.getElementById("code-time").innerHTML = curCount;
		InterValObj = window.setInterval(SetRemainTime, 1000); //启动计时器，1秒执行一次
	}
}
function SetRemainTime() {
	if (curCount == 0) {                
		window.clearInterval(InterValObj);//停止计时器
		document.getElementById("code-time").setAttribute("data-code","yes");
		document.getElementById("code-time").innerHTML = "请重新获取证码";
	} else {
		curCount--;
		document.getElementById("code-time").innerHTML = curCount;
	}
	
}

function productTouchend(){
	var Numbers = 0,Price = 0;
	//增加商品数量
	$(".input-plus").on('touchend',function(e){
		var inputnum = $(this).parent().children("input").val();
		inputnum = parseInt(inputnum.substring(0,inputnum.indexOf("件")));
		inputnum++;
		inputnum = inputnum+"件";
		$(this).parent().children("input").val(inputnum);
		var datas = $(this).attr("data-type");
		Numbers = parseInt(Numbers) + 1;
		Price = parseInt(Price) + parseInt(datas);
		$("#total").html("共<i style='color: #007aff;'>" + Numbers + "</i>件　" + "共<i style='color: #007aff;'>" + Price + "</i>元");
	});
	$(".input-minus").on('touchend',function(e){
		var inputnum = $(this).parent().children("input").val();
		inputnum = parseInt(inputnum.substring(0,inputnum.indexOf("件")));
		if(inputnum > 0){
			var datas = $(this).attr("data-type");
			Numbers = parseInt(Numbers) - 1;
			Price = parseInt(Price) - parseInt(datas);
			$("#total").html("共<i style='color: #007aff;'>" + Numbers + "</i>件　" + "共<i style='color: #007aff;'>" + Price + "</i>元");
		}
		inputnum--;
		if(inputnum<=0){
			inputnum=0;
		}
		inputnum = inputnum+"件";
		$(this).parent().children("input").val(inputnum);
	});
}

$(function(){
	//支付中心
	$("#payment input").on('touchend',function(e){
		if($(this).is(":checked")){
			$(this).siblings("label").removeClass("cur");
		}else{
			$(this).siblings("label").addClass("cur");
		}
	});
	//评论
	$(".star span b").on('tap',function(){
		var index = $(this).parent().children().index(this);
		var $id = $(this).parents("div").attr("data-c");
		if($id != undefined){
			var $oBj = $(".star" + $id +" span b");
			if(index == 2){
				$oBj.addClass("starm");
				$(this).parent().siblings("strong").html("非常满意");
			}else{
				if(index == 1){
					$(this).parent().siblings("strong").html("基本满意");
				}else{
					$(this).parent().siblings("strong").html("不满意");
				}
				$oBj.removeClass("starm");
				$oBj.addClass("starl");
				$(this).nextAll(index).removeClass("starl");
			}
		}
	});
})

//关闭弹层
function closelayer(){
	closeShade();
	document.getElementById("code-layer").style.display = "none";
}
//获取验证码
function code(){
	times();
	document.getElementById("code-layer").style.display = "block";
}
//校验验证码
function concode(obj){
	if(obj.value.length >= 6){
		document.getElementById("error").innerHTML = "验证码有误";
	}else{
		document.getElementById("error").innerHTML = "";
	}
}
//选择收衣地址
function selectAdd(){
	$(".contact-box").toggle();
	$(".addresslist-box").toggle();
}
//去支付
function payfor(){
	document.getElementById("payment-layer").style.display = "block";
	showShade(2);
}
/**
 * unixtime时间戳转换
 */
function js_date_time(unixtime) {
	var timestr = new Date(parseInt(unixtime) * 1000);
	return formatDateTime(timestr);
}

/**
 * 时间格式化
 */
function formatDateTime(myDate) {
	var year = myDate.getFullYear(); //获取完整的年份(4位,1970-????)
	var month = myDate.getMonth() + 1; //获取当前月份(0-11,0代表1月)
	var day = myDate.getDate(); //获取当前日(1-31)
	var hours = myDate.getHours();
	var minutes = myDate.getMinutes();
	var seconds = myDate.getSeconds();
	
	if (month < 10) {
		month = "0" + month;
	}
	
	if (day < 10) {
		day = "0" + day;
	}
	
	if (hours < 10) {
		hours = "0" + hours;
	}
	
	if (minutes < 10) {
		minutes = "0" + minutes;
	}
	
	if( seconds < 10) {
		seconds = "0" + seconds;
	}
	
	return year + "-" + month + "-" + day + " " + hours+":" +minutes+":"+seconds;
}