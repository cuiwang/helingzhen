var ctx = $("#ctx").val();
var PB = new PromptBox();
var images = {
		localId: [],
	    serverId: []
};

$(document).ready(function() {
	initCss();
	loading_teacher_info();
	loading_usertype_list();
	loading_ktdt_stats();
	loading_bjq_stats();
	loading_log_stats();
	if ($("#bbzp_total").length > 0) {
		loading_bbzp_stats();
	}
	if ($("#zthd_total").length > 0) {
		loading_zthd_stats();
	}
	loading_yezs_stats();
	if ($("#ysgg_total").length > 0) {
		loading_ysgg_stats();
	}
	if ($("#ysxw_total").length > 0) {
		loading_ysxw_stats();
	}
	if ($("#xszy_total").length > 0) {
		loading_xszy_stats();
	}
	
	loading_campus_info();
	
	$("#unbound").click(function(){
		unBoundTeacher();
	});
	$("#askForLeave").click(function(){
		jumpTeaPaxy();
	});
	$(".icon_edit").click(function(){
		showTextBox();
	});
	$("#exchange").click(function(){
		exchangeScore();
	});
	
});

function exchangeScore(){
	var score = $("#jf_total").html();
	var submitData = {
			appid:ApiParamUtil.COMMON_DUIBA_URL,
			jf_total:score
	};
	$.ajax({
		cache:false,
		type: "POST",
		url: basicParameters.loadingData,
		data: submitData,
		success: function(datas){
			var result = typeof datas === "object" ? datas : JSON.parse(datas);
			if(result.ret.code==="200"){
				var url = result.data.duibaurl;
				window.location.href = url;
			}else{
				console.log(result.ret.code+":"+result.ret.msg);
			}
		}
	});
}

function showTextBox(){
	$("#content").val($("#signatureValue").val());
	$("#discussText").show();
}
function closeTextBox(){
	$("#discussText").hide();
	$("#discussBg").hide();
	$("#content").val("");
}

function editSignature(){
	if($("#content").val().length>80){
		PB.prompt("个性签名超过80字符！");
		return;
	}
	var submitData = {
			appid:ApiParamUtil.USER_CENTER_SAVE_TEACHER_SIGNATURE,
			api:ApiParamUtil.USER_CENTER_SAVE_TEACHER_SIGNATURE,
			signature:$("#content").val()
	};
	$.ajax({
		cache:false,
		type: "POST",
		url: basicParameters.loadingData,
		data: submitData,
		success: function(datas){
			var result = typeof datas === "object" ? datas : JSON.parse(datas);
			if(result.ret.code==="200"){
				PB.prompt("个性签名修改成功！");
				var signatureValue = $("#content").val();
				if(datas.signature != null && datas.signature != ''){
					signatureValue = "未设置个性签名";
				}
				$("#signature").html(signatureValue);
				$("#signature").each(function(){
					var maxwidth = 7;
					if($(this).text().length>maxwidth){
						$(this).text($(this).text().substring(0,maxwidth));
						$(this).html($(this).html()+'…');
					}
				});
				$("#signatureValue").val(signatureValue);
				closeTextBox();
			}else{
				console.log(result.ret.code+":"+result.ret.msg);
			}
		}
	});
}

function initCss(){
	$(".linkDiv .centerBox").css({ 
        position: "absolute", 
        left: ($(window).width() - $(".linkDiv .centerBox").outerWidth())/2
    });
	var w = ($(window).width()-20)*0.7/3;
	$("#imgpath").attr("height", w);
	$("#imgpath").attr("height", w);
	
	var statsBoxs = $(".statsBox");
	var total = statsBoxs.length;
	var max = total - total % 3;
	for (i = 0; i < total; i++) {
		if (i % 3 != 0) {
			$(statsBoxs[i]).addClass("rightBox");
		}
		if (i >= max) {
			$(statsBoxs[i]).css("border-bottom", "none");
		}
	}
}

function loading_teacher_info() {
	var submitData = {
		appid : ApiParamUtil.USER_CENTER_TEACHER_INFO
	};
	$.post(commonUrl_loadingData, submitData, function(data) {
		var json = typeof data === "object" ? data : JSON.parse(data);
		if (json.ret.code == "200") {
			var datas = json.data;
			$("#stuHeader").find("img").attr("src",datas.iconpath);
			$("#teacher_name").html(datas.name);
			$("#position").html(datas.position);
			$("#teacher_mobile").html(datas.mobile);
			$("#jf_total").html(datas.jf_total);
			$("#balances").html(datas.balances+"元");
			if(datas.signature != null && datas.signature != ''){
				$("#signature").html(datas.signature);
				$("#signatureValue").val(datas.signature);
				$("#signature").each(function(){
					var maxwidth = 7;
					if($(this).text().length>maxwidth){
						$(this).text($(this).text().substring(0,maxwidth));
						$(this).html($(this).html()+'…');
					}
				});
			}
		} else {
			PromptBox.alert(json.ret.msg);
		}
		
	});
}

function loading_usertype_list(){
	var submitData = {
		appid : ApiParamUtil.USER_CENTER_QUERY_USERTYPE_LIST
	};
	$.post(commonUrl_loadingData, submitData, function(json) {
		var json = typeof json == "object" ? json : JSON.parse(json);
		if(json.ret.code == "200"){
			var datas = json.data;
			if(datas.length > 1) {
				$("#status").html(createSelectList(datas));
			} else {
				$("#status").html("");
			}
		}
	});
}

function createSelectList(data){
	
	var str = "";
	var option = "";
	for ( var i = 0; i < data.length; i++) {
		var selected = "";
		if(basicParameters.campusid == data[i].campusid && basicParameters.usertype == data[i].usertype){
			selected = "selected = selected";
		}
		option += '<option value="jumpInfo_'+data[i].campusid+'_'+data[i].usertype+'" '+selected+'>'+data[i].campus+'-'+data[i].rolename+'</option>';
	}
	str += '<select onchange="updateUserCampus(this)">'
		+  option
		+  '</select>'
		+  '<span>切换班级</span>';
	return str;
}

function goToGrzx(jumpInfoArr,jumpAppid) {
	var url = ctx + "/mobile/loading_page?" +
			"campusid="+jumpInfoArr[1]+"&orgcode="+basicParameters.orgcode+
			"&appid=" + jumpAppid + 
			"&usertype="+jumpInfoArr[2]+"&userid="+basicParameters.userid+"&fromusername="+basicParameters.fromusername;
	location.href = url;
}

function updateUserCampus(obj){
	var jumpInfoArr = $(obj).val().split('_');
	var jumpAppid = ApiParamUtil.APPID_PERSONAL_INFORMATION;
	if(jumpInfoArr[2] == "1"){
		jumpAppid = ApiParamUtil.APPID_PERSONAL_INFORMATION_TEACHER;
	}
	updateUserType(jumpInfoArr[2]);
	
	var submitData = {
		appid : ApiParamUtil.UPDATE_USER_CAMPUS_FOR_APP,
		currentcampusid : jumpInfoArr[1]
	};
	$.post(commonUrl_loadingData, submitData, function(json) {
		var json = typeof json == "object" ? json : JSON.parse(json);
		if(json.ret.code == "200"){
			updateCurrentStu("");
			goToGrzx(jumpInfoArr,jumpAppid);
		}
	});
}

function updateUserType(_usertype){
	var submitData = {
		appid : ApiParamUtil.USER_UPDATE_USERTYPE,
		current_usertype : _usertype
	};
	$.post(commonUrl_loadingData, submitData, function(json) {
//		var json = typeof json == "object" ? json : JSON.parse(json);
//		if(json.ret.code == "200"){
//			goToGrzx(jumpInfoArr,jumpAppid);
//		}
	});
}

function updateCurrentStu(current_stuid){
	var submitData = {
		appid : ApiParamUtil.USER_UPDATE_XSJB,
		current_stuid : current_stuid
	};
	$.post(commonUrl_loadingData, submitData, function(json) {
		
	});
}

function loading_ktdt_stats() {
	var submitData = {
		systemtype : 'weixin',
		appid : ApiParamUtil.MY_CLASS_CLASSROOM_PUBLISH_TOTAL
	};
	$.post(commonUrl_loadingData, submitData, function(data) {
		var json = typeof data === "object" ? data : JSON.parse(data);
		if (json.ret.code == "200") {
			var datas = json.data;
			$("#ktdt_total").html(datas.ktdt_total);
		} else {
			PromptBox.alert(json.ret.msg);
		}
	});
}

function loading_bjq_stats() {
	var submitData = {
		systemtype : 'weixin',
		appid : ApiParamUtil.MY_CLASS_CLASS_CIRCLE_PUBLISH_TOTAL
	};
	$.post(commonUrl_loadingData, submitData, function(data) {
		var json = typeof data === "object" ? data : JSON.parse(data);
		if (json.ret.code == "200") {
			var datas = json.data;
			$("#bjq_total").html(datas.bjq_total);
		} else {
			PromptBox.alert(json.ret.msg);
		}
	});
}

function loading_bbzp_stats() {
	var submitData = {
		systemtype : 'weixin',
		appid : ApiParamUtil.MY_CLASS_BABY_ARTICLE_PUBLISH_TOTAL
	};
	$.post(commonUrl_loadingData, submitData, function(data) {
		var json = typeof data === "object" ? data : JSON.parse(data);
		if (json.ret.code == "200") {
			var datas = json.data;
			$("#bbzp_total").html(datas.bbzp_total);
		} else {
			PromptBox.alert(json.ret.msg);
		}
	});
}

function loading_zthd_stats() {
	var submitData = {
		systemtype : 'weixin',
		appid : ApiParamUtil.MY_CLASS_THEME_PUBLISH_TOTAL
	};
	$.post(commonUrl_loadingData, submitData, function(data) {
		var json = typeof data === "object" ? data : JSON.parse(data);
		if (json.ret.code == "200") {
			var datas = json.data;
			$("#zthd_total").html(datas.zthd_total);
		} else {
			PromptBox.alert(json.ret.msg);
		}
	});
}

function loading_yezs_stats() {
	var submitData = {
		systemtype : 'weixin',
		appid : ApiParamUtil.DAILY_MANAGE_KNOWLEDGE_PUBLISH_TOTAL
	};
	$.post(commonUrl_loadingData, submitData, function(data) {
		var json = typeof data === "object" ? data : JSON.parse(data);
		if (json.ret.code == "200") {
			var datas = json.data;
			$("#yezs_total").html(datas.yezs_total);
		} else {
			PromptBox.alert(json.ret.msg);
		}
	});
}

function loading_campus_info() {
	var submitData = {
		appid : ApiParamUtil.SYS_MANAGE_SCHOOL_CAMPUS_INFO
	};
	$.post(commonUrl_loadingData, submitData, function(data) {
		var json = typeof data === "object" ? data : JSON.parse(data);
		if (json.ret.code == "200") {
			var datas = json.data;
			$("#address").html(datas.address);
			$("#campus").html(datas.campus);
			$("#telephone").html(datas.telephone);
			$("#imgpath").attr("src", datas.imgpath);
			$("#qrcodeurl").attr("src", datas.qrcodeurl);
		} else {
			PromptBox.alert(json.ret.msg);
		}
	});
}

function loading_ysgg_stats() {
	var submitData = {
		systemtype : 'weixin',
		appid : ApiParamUtil.PERSONAL_NEWS_NOTICE_TOTAL,
		type:2
	};
	$.post(commonUrl_loadingData, submitData, function(data) {
		var json = typeof data === "object" ? data : JSON.parse(data);
		if (json.ret.code == "200") {
			var datas = json.data;
			$("#ysgg_total").html(datas.total);
		} else {
			PromptBox.alert(json.ret.msg);
		}
	});
}

function loading_ysxw_stats() {
	var submitData = {
		systemtype : 'weixin',
		appid : ApiParamUtil.PERSONAL_NEWS_NOTICE_TOTAL,
		type:3
	};
	$.post(commonUrl_loadingData, submitData, function(data) {
		var json = typeof data === "object" ? data : JSON.parse(data);
		if (json.ret.code == "200") {
			var datas = json.data;
			$("#ysxw_total").html(datas.total);
		} else {
			PromptBox.alert(json.ret.msg);
		}
	});
}

function loading_xszy_stats() {
	var submitData = {
		systemtype : 'weixin',
		appid : ApiParamUtil.PERSONAL_HOMEWORK_TOTAL
	};
	$.post(commonUrl_loadingData, submitData, function(data) {
		var json = typeof data === "object" ? data : JSON.parse(data);
		if (json.ret.code == "200") {
			var datas = json.data;
			$("#xszy_total").html(datas.total);
		} else {
			PromptBox.alert(json.ret.msg);
		}
	});
}

/**
 * 获取考勤信息
 */
function loading_log_stats(){
	var today = new Date().Format("yyyy-MM-dd");
	var submitData = {
			appid:ApiParamUtil.TEACHER_PAXY_QUERY,
			api:ApiParamUtil.TEACHER_PAXY_QUERY,
			beginDate:today,
			endDate:today
		};
		$.ajax({
			cache:false,
			type: "POST",
			url: basicParameters.loadingData,
			async:false,
			data: submitData,
			success: function(datas){
				var result = typeof datas === "object" ? datas : JSON.parse(datas);
				if(result.ret.code==="200"){
					var punchnum ="未打卡";
					if(result.data.punchnum){
						punchnum = result.data.punchnum+"次打卡";
					}
					$("#logTime").text(punchnum);
				}else{
					console.log(result.ret.code+":"+result.ret.msg);
				}
			}
		});
}

//function createPaxyDate(dataList){
//	var amtime="未打卡",pmtime="未打卡";
//	if(dataList.length>0){
//		if(dataList[0].amhour&&dataList[0].ammin){
//			amtime = dataList[0].amhour+':'+dataList[0].ammin;
//		}
//		if(dataList[0].pmhour&&dataList[0].pmmin){
//			pmtime = dataList[0].pmhour+':'+dataList[0].pmmin
//		}
//		$("#logTime").text(amtime+"-"+pmtime);
//	}
//}

function jumpTeaPaxy(){
	window.location.href = basicParameters.loadingPage + "&appid="+ApiParamUtil.TEACHER_PAXY_JUMP;
}

function unBoundTeacher(){
	if(confirm("确认解除绑定？")){
		var submitData = {
			appid : ApiParamUtil.USER_CENTER_UNBOUND_TEACHER
		};
		$.post(commonUrl_loadingData, submitData, function(json) {
			var result = typeof json == "object" ? json : JSON.parse(json);
			if(result.ret.code == 200){
				window.location.href = commonUrl_loadingPage + "&appid="+ApiParamUtil.APPID_BINDING_PATRIARCH_AND_TEACHER;
			}
		});
	}
}

function initImgCss(){
	var imgDivs = $(".img");
	for (var i = 0; i < imgDivs.length; i++) {
		//外面边框的的高宽比例
		var H_W = imgDivs.eq(i).height() / imgDivs.eq(i).width();
		//图片的高宽比例
		var h_w = imgDivs.eq(i).find("img").height() / imgDivs.eq(i).find("img").width();
		if(H_W > 1 && H_W > h_w){
			maxHeight(imgDivs.eq(i));
		}else if(H_W > 1 && H_W < h_w){
			maxWidth(imgDivs.eq(i));
		}else if(H_W < 1 && H_W > h_w){
			maxHeight(imgDivs.eq(i));
		}else if(H_W < 1 && H_W < h_w){
			maxWidth(imgDivs.eq(i));
		}else if(H_W = 1 && h_w > 1){
			maxWidth(imgDivs.eq(i));
		}else if(H_W = 1 && h_w < 1){
			maxHeight(imgDivs.eq(i));
		}else{
			maxHeightAndWidth(imgDivs.eq(i));
		}
	}
}

function maxWidth(obj){
	obj.find("img").css("width","100%");
	obj.find("img").css("top","50%");
	obj.find("img").css("margin-top",-obj.find("img").height()/2);
}

function maxHeight(obj){
	obj.find("img").css("height","100%");
	obj.find("img").css("left","50%");
	obj.find("img").css("margin-left",-obj.find("img").width()/2);
}

function maxHeightAndWidth(obj){
	obj.find("img").css("top","0");
	obj.find("img").css("left","0");
	obj.find("img").css("height","100%");
	obj.find("img").css("width","100%");
}



/**
 * 微信选择图片
 */
function wxChooseImage(){
	if (typeof WeixinJSBridge == "undefined"){
		YixinJSBridge.invoke("pickImage",
                {type: "album", //从相册
//                 width:"图片宽度",
//                 height:"图片高度",
                 quality:"100"}, // 1~100 
                 function(result) {
                	images.localId.push(result.id);
                	var base64= "data:"+result.mime+";base64,"+result.data;
                	var obj=new Image();
     				obj.src=base64;
     				obj.onload=function(){
     					var dh = document.body.clientHeight;
     					var dw = document.body.clientWidth;
     					var maxHeight = dh-150;
     					var maxWidth = dw-20;
     					var scale = obj.width / obj.height;
     					if(obj.height > obj.width){
    						obj.height = maxHeight;
    						obj.width = maxHeight*scale;
    					}else{
    						obj.height = maxWidth/scale;
    						obj.width = maxWidth;
    					}
     					document.querySelector('.bigImg').innerHTML="<img id='srcImg' src='"+this.src+"' height='"+obj.height+"' width='"+obj.width+"' />";
    					document.querySelector('.previewImg').innerHTML = "<img id='previewImg' src='" + base64 + "' width='80px' height='80px'>";
    					$("#bigImage").val(base64);
    		        	popupDiv('pop-div');
    		        	$('.header').hide();
    		        	cutImage();
    		        	imagesUploadYiXin();
    				}
                 });
		
	}else{
		wx.chooseImage({
			success: function (res) {
				images.localId = res.localIds;
//				if(/android/i.test(navigator.userAgent)){
//					saveImageAndroid();
//				}else{
					var obj=new Image();
					obj.src=res.localIds[0];
					obj.onload=function(){
						var dh = document.body.clientHeight;
						var dw = document.body.clientWidth;
						var maxHeight = dh-200;
						var maxWidth = dw-100;
						var scale = obj.width / obj.height;
						if(obj.height > obj.width){
							obj.height = maxHeight;
							obj.width = maxHeight*scale;
						}else{
							obj.height = maxWidth/scale;
							obj.width = maxWidth;
						}
						document.querySelector('.bigImg').innerHTML="<img id='srcImg' src='"+this.src+"' height='"+obj.height+"' width='"+obj.width+"' />";
						document.querySelector('.previewImg').innerHTML = "<img id='previewImg' src='" + res.localIds[0] + "' width='80px' height='80px'>";
						popupDiv('pop-div');
						$('.header').hide();
						cutImage();
						imagesUploadWx();
//				}
				}
			}
		});
	}
};

function saveImageAndroid() {
	PB.prompt("头像更新中...","forever");
	 wx.uploadImage({
	      localId: images.localId[0],
	      isShowProgressTips:0,//// 默认为1，显示进度提示
	      success: function (res) {
	      	var url = ctx + "/mobile/upload_image";
	    	var submitData = {
	    			orgcode:$("#orgcode").val(),
	    			bigImage:res.serverId,
	    			appid : ApiParamUtil.APPID_SERVICE_HEADSAVE,
	    			userid:$("#userid").val(),
	    			cutting:false
	    		};
	    		$.post(url, submitData, function(data) {
	    			PB.loadMsg("头像更改成功！");
	    			location.reload();
	    		});
	      },
	      fail: function (res) {
	        alert(JSON.stringify(res));
	      }
	    });
}

function imagesUploadYiXin() {
	//alert(images.localId);
	var i = 0, length = images.localId.length;
	
	YixinJSBridge.invoke("uploadImage",
	        {ids: images.localId}, 
	        function(result) {
	        	images.serverId.push(result.urls);
	        	images.localId=[];
	 });
	
};

function imagesUploadWx() {
	      wx.uploadImage({
	        localId: images.localId[0],
	        isShowProgressTips:0,//// 默认为1，显示进度提示
	        success: function (res) {
	        	$("#bigImage").val(res.serverId);
	        },
	        fail: function (res) {
	          alert(JSON.stringify(res));
	        }
	      });
};


function popupDiv(div_id) {
    var div_obj = $("#" + div_id);
    var windowWidth = document.body.scrollWidth;
    var windowHeight = document.body.scrollHeight;
    var popupHeight = div_obj.height();
    var popupWidth = div_obj.width();

    //添加并显示遮罩层
    $("<div id='mask'></div>").addClass("mask").width(windowWidth).height(windowHeight).click(function () {
        //hideDiv(div_id);
    }).appendTo("body").fadeIn(200);

    div_obj.css({ "position": "absolute" }).animate({
        left: windowWidth / 2 - popupWidth / 2,
        top: 0, opacity: "show" }, "slow");
}

function hideDiv(div_id) {
	$('.header').show();
    $("#mask").remove();
    $("#" + div_id).animate({ left: 0, top: 0, opacity: "hide" }, "slow");
}

//裁剪图像
function cutImage() {
    $("#srcImg").Jcrop({
        aspectRatio: 1,
        onChange: showCoords,
        onSelect: showCoords,
        setSelect: [0, 0, 400, 400]
    });

    //简单的事件处理程序，响应自onChange,onSelect事件，按照上面的Jcrop调用
    function showCoords(obj) {
        $("#x").val(obj.x);
        $("#y").val(obj.y);
        $("#w").val(obj.w);
        $("#h").val(obj.h);
        if (parseInt(obj.w) > 0) {
            //计算预览区域图片缩放的比例，通过计算显示区域的宽度(与高度)与剪裁的宽度(与高度)之比得到
            var rx = 80 / obj.w;
            var ry = 80 / obj.h;

            //通过比例值控制图片的样式与显示
            $("#previewImg").css({
                width: Math.round(rx * $("#srcImg").width()) + "px",
                height: Math.round(rx * $("#srcImg").height()) + "px",
                marginLeft: "-" + Math.round(rx * obj.x) + "px",
                marginTop: "-" + Math.round(ry * obj.y) + "px"
            });
        }
    }
}

function closePopUpBoxBg(){
	$(".popUpBox,.popUpBoxBg").hide();
}

function saveImage() {
	var sw = $("#srcImg").width();
	var sh = $("#srcImg").height();
	var url = ctx + "/mobile/upload_image";
	var submitData = {
			orgcode:$("#orgcode").val(),
			bigImage:$("#bigImage").val(),
			x:$("#x").val(),
			y:$("#y").val(),
			w:$("#w").val(),
			h:$("#h").val(),
			sw:sw,
			sh:sh,
			appid : ApiParamUtil.APPID_SERVICE_HEADSAVE,
			userid:$("#userid").val(),
			cutting:true
		};
		$.post(url, submitData, function(data) {
			hideDiv('pop-div');
			PB.loadMsg("头像更改成功！");
			location.reload();
		});
}

/**
 * 查看打赏明细
 * 
 */
function viewRewardList() {
	var url = ctx + "/mobile/oauthUrl/" + basicParameters.orgcode + "/" + ApiParamUtil.REWARD_INFO_PAGE_JUMP;
	window.location.href = url;
}