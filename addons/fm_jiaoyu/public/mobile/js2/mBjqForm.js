var oss = '{OSSURL}/';
// var commonUrl_loadingPage = $("#commonUrl_loadingPage").val();
// var commonUrl_loadingData = $("#commonUrl_loadingData").val();
// var stuid = $("#stuid").val();
var vedioUrl=$("#vedioUrl").val();
var thumbnailUrl="";
var weixinMediaId=$("#weixinMediaId").val();
//var COMMON_WEIXIN_MEDIAGET=ApiParamUtil.COMMON_WEIXIN_MEDIAGET;
//活动那个
var isGhActivity=$("#isGhActivity").val();
var ghActivityThemeParentid=$("#ghActivityThemeParentid").val();
var PB = new PromptBox();

$(window).load(function() {
	var hideimg = $(".hideimg");
	if(weixinMediaId!=null && weixinMediaId!='' && weixinMediaId!=undefined){
		callWeiXinGetMediaApi();
	}else{
		showBox('image');
	}
	if(hideimg.length!=0){
		hideimg.each(function() {
			loadImages(this.name,this.value);
		});
	}
});

$(document).ready(function(){
	
});

function isCheck(obj,id){
	var alt = $(obj).find("img").attr("alt");
	if(alt == "check"){
		$(obj).find("img").attr("alt","checked");
		$(obj).find("img").attr("src",oss+"public/mobile/img/checked.png");
		$(obj).find("input").attr("name","checked");
		$(obj).find("span[class=le]").attr("name","checkedName");
	}else{
		$(obj).find("img").attr("alt","check");
		$(obj).find("img").attr("src",oss+"public/mobile/img/check.png");
		$(obj).find("input").attr("name","check");
		$(obj).find("span[class=le]").attr("name","checkName");
	}
	if($('input[name=checked]').length===$('.selectList').find('li').length){
		$(".checkAll").find("img").attr("alt","checked");
		$(".checkAll").find("img").attr("src",basicParameters.oss+"public/mobile/img/checked.png");
	}else{
		$(".checkAll").find("img").attr("alt","check");
		$(".checkAll").find("img").attr("src",basicParameters.oss+"public/mobile/img/check.png");
	}
}

function isSelect(obj){
	$(obj).parent().children().removeAttr("class");
	$(obj).parent().find("span[class=le]").attr("name","selectName");
	$(obj).parent().find("input[type=hidden]").attr("name","select");
	$(obj).attr("class","selected");
	$(obj).find("span[class=le]").attr("name","selectedName");
	$(obj).find("input[type=hidden]").attr("name","selected");
	saveSelected(obj);
	closeBox();
}

function saveChecked(obj){
	var checkedListValue = $("#"+obj).find("input[type=hidden][name=checked]");
	var checkedListName = $("#"+obj).find("span[class=le][name=checkedName]");
	var checkedids = "";
	for (var i = 0; i < checkedListValue.length; i++) {
		if(i == checkedListValue.length -1){
			checkedids += checkedListValue.eq(i).val();
		}else{
			checkedids += checkedListValue.eq(i).val() + ",";
		}
	}
	if(checkedListName.length == 0){
		PB.prompt("请选择一个班级！");
		return;
	}else if(checkedListName.length == 1){
		$("#"+obj+"Show").html(checkedListName.eq(0).html());
	}else{
		$("#"+obj+"Show").html(checkedListName.eq(0).html() + "等" + checkedListName.length + "个班级");
	}
	$("#"+obj+"Value").val(checkedids);
	closeBox();
}

function saveSelected(obj){
	var boxName = $(obj).parent().parent().attr("id");
	var selectedName = $(obj).find("span[class=le][name=selectedName]");
	var selectedValue = $(obj).find("input[type=hidden][name=selected]");
	$("#"+boxName+"Show").html(selectedName.html());
	$("#"+boxName+"Value").val(selectedValue.val());
}

function showSelectBox(obj){
	if($("#"+obj).find("ul").children().length > 0){
//		if(obj=="stuList")$(".checkAll").css("display","none");
		$(".selectList").css("display","block");
		$(".blackBg").css("display","block");
		$("#"+obj).css("display","block");
		var height = 0;
		if($("#"+obj).attr("class") == "double"){
			$("#"+obj).css("height",$(".selectList").height());
			$("#"+obj).find("ul").css("height",$(".selectList").height()-100);
			var objList;
			if($("#"+obj+"Value").val() != ""){
				objList = $("#"+obj+"Value").val().split(",");
				var liList = $("#"+obj).find("li");
				for (var j = 0; j < liList.length; j++) {
					for (var i = 0; i < objList.length; i++) {
						if(objList[i] == liList.eq(j).find("input[type=hidden]").val()){
							liList.eq(j).find("img").attr("alt","checked");
							liList.eq(j).find("img").attr("src",oss+"public/mobile/img/checked.png");
							liList.eq(j).find("input[type=hidden]").attr("name","checked");
							liList.eq(j).find("span[class=le]").attr("name","checkedName");
							break;
						}else{
							liList.eq(j).find("img").attr("alt","check");
							liList.eq(j).find("img").attr("src",oss+"public/mobile/img/check.png");
							liList.eq(j).find("input[type=hidden]").attr("name","check");
							liList.eq(j).find("span[class=le]").attr("name","checkName");
						}
					}
				}
			}else{
				$("#"+obj).find("img").attr("alt","check");
				$("#"+obj).find("img").attr("src",oss+"public/mobile/img/check.png");
				$("#"+obj).find("input[type=hidden]").attr("name","check");
				$("#"+obj).find("span[class=le]").attr("name","checkName");
			}
		}else{
			$("#"+obj).css("height",$(".selectList").height());
			$("#"+obj).find("ul").css("height",$(".selectList").height());
		}
		$(".selectList").css("margin-top",-$("#"+obj).parent().height()/2);	
	}
}

function closeBox(){
	$(".selectList").css("display","none");
	$(".blackBg").css("display","none");
	$(".single").css("display","none");
	$(".double").css("display","none");
	$(".double").css("height","auto");
	$(".double").find("ul").css("height","auto");
}
/**
function sendPhoto(){
	var sendtype = $("#sendtype").val();
	var content = $("#content").val();
	var photoUrlArray = new Array();
	var photoidlArray = new Array();
	$(".boxImages").each(function() {
		if($(this).hasClass("baseUploadImg")){
			photoUrlArray.push($(this).attr("src"));
		}else if($(this).hasClass("wxUploadImg")){
			photoidlArray.push(this.id.substring(this.id.indexOf("photoid_")+8));
		}
	});
	var photoids = photoidlArray.join(',');
	var bjids = "";
	var stuids = "";
	var isopenValue = 1;
	if(photoUrlArray.length==0&& photoids=='' && content==''){
		PB.prompt("请填写简介或者上传图片！");
		return;
	}
	if(content.length>200){
		PB.prompt("简介内容请少于200字！");
		return;
	}
	if(sendtype == 1 || sendtype == 2){
		bjids =  $("#bjListValue").val();
		if(bjids == undefined || bjids == null || bjids == "" ){
			PB.prompt("请选择班级！");
			return;
		}
	}
	if(sendtype == 1 || sendtype==3){
		stuids =  $("#stuListValue").val();
		//isopenValue =  $("#isopenValue").val();
		if(stuids == undefined || stuids == null || stuids == "" ){
			PB.prompt("请选择学生！");
			return;
		}
		var stuidsAndBjidsArray = stuids.split(',');
		var bjidsArray = new Array();
		var stuidsArray = new Array();
		for(var i =0;i<stuidsAndBjidsArray.length;i++){
			var bjidAndStuid = stuidsAndBjidsArray[i].split('_');
			bjidsArray.push(bjidAndStuid[0]);
			stuidsArray.push(bjidAndStuid[1]);
		}
		
		bjids = bjidsArray.join(',');
		stuids = stuidsArray.join(',');
	}
	var photoUrls="";
	if(vedioUrl!=null && vedioUrl!=''){
		//视频
		photoUrls=vedioUrl;
	}else{
		photoUrls=images.serverId.join(',');
	}
	if (confirm("确定保存?")) {
		showLoadImgMask();
		PB.prompt("信息发布中，请稍等~","forever");
		var url = commonUrl_loadingData;
		var submitData = {
			appid:ApiParamUtil.MY_CLASS_CLASS_SAVE,
			api:ApiParamUtil.MY_CLASS_CLASS_SAVE,
			content : content,
			bjids : bjids,
			stuids : stuids,
			publisherid : $("#userid").val(),
			wlzytype:5,
			isopen : 1,
			photoUrls : photoUrls,
			sendtype : sendtype,
			photoids:photoids,
			thumbnailUrl:thumbnailUrl
			
		};
		$.post(url, submitData, function(datas) {
			var result = typeof datas === "object" ? datas : JSON.parse(datas);
			if(result.ret.code==="200"){
				if(result.data.result=="success"){
					PB.prompt("信息发布成功！");
					hideLoadImgMask();
					//光海活动
					if(isGhActivity=="true" && ghActivityThemeParentid!=null){
						isghhdActivityJump(stuids,images.serverId);
						return;
					}
					if(isopenValue==1){
						location.href = commonUrl_loadingPage+"&appid=2071014&type=1&stuid="+stuids;
					}else{
						location.href = commonUrl_loadingPage+"&appid=2071014&type=2&stuid="+stuids;
					}
				}else if(result.data.result == "apply"){
					WeixinJSBridge.invoke('closeWindow',{},function(res){
					});
				}
			}else{
				console.log(result.ret.code+":"+result.ret.msg);
				PB.prompt("照片发布失败！");
				hideLoadImgMask();
			}			
		});
	}
}**/
/**
 * 班级圈发布成功之后跳转
 */
// function isghhdActivityJump(stuids,serverId){
	// if(serverId==null || serverId.length==0){
		// PB.prompt("请填写简介并上传图片！");
		// return;
	// }
	// location.href = commonUrl_loadingPage+"&appid=6070007&appdataid="+ghActivityThemeParentid+"&stuid="+stuids;

// }

// function cancel(){
	// location.href = commonUrl_loadingPage+"&appid=2071014&type=1&stuid="+$("#stuListValue").val();
// }

function isCheckAll(obj){
	var alt = $(obj).find("img").attr("alt");
	if(alt == "check"){
		$(".ri").find("img").attr("alt","checked");
		$(".ri").find("img").attr("src",oss+"public/mobile/img/checked.png");
		$("li").find("input").attr("name","checked");
		$("li").find("span[class=le]").attr("name","checkedName");
	}else{
		$(".ri").find("img").attr("alt","check");
		$(".ri").find("img").attr("src",oss+"public/mobile/img/check.png");
		$("li").find("input").attr("name","check");
		$("li").find("span[class=le]").attr("name","checkName");
	}
}

/**
 * 显示遮罩
 */
function showLoadImgMask(){
	var bh = $(window).height(); 
	var bw = $(window).width();
	document.documentElement.style.overflow='hidden';
	document.body.style.cssText = 'overflow:hidden;+overflow:none;_overflow:hidden;';
	document.body.parentNode.style.overflow="hidden";
	document.documentElement.style.overflow='hidden';
	$("#fullbg").css({ 
		height:bh, 
		width:bw,
		display:"block"
	});
}

/**
 * 隐藏
 */
function hideLoadImgMask(){
	$("#fullbg").hide();
	document.documentElement.style.overflow='auto';
	document.body.style.cssText = 'overflow:auto;+overflow:auto;_overflow:auto;';
	document.body.parentNode.style.overflow="auto";
	document.documentElement.style.overflow='auto';
}
