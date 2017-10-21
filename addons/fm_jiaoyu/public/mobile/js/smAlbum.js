/**
 * 相册
 */
//每页数量
var pageSize = 6;
//当前页码
var nowPage = 1;
var classids = $('classids').val();

$(document).ready(function() {
	//getClassList();
	queryAlbumList();
	$('#jumpAdd').click(function(){
		jumpAdd();
	})
});

/**
 * 设置图片高度
 */
function setImageHeight(){
	if($(".albumCover").length!==0){
		var imageWidth = $(".albumCover")[0].offsetWidth;
		var albumBoxWidth = $(".albumBox")[0].offsetWidth;
		$(".albumBox").height(albumBoxWidth);
		$(".albumCover,.bg-dark,.bg-tint").height(imageWidth);
	}
}

/**
 * 获取班级选项列表
 *
function getClassList(){
	var submitData = {
			appid:ApiParamUtil.COMMON_QUERY_CLASS,
			api:ApiParamUtil.COMMON_QUERY_CLASS,
			stuid:basicParameters.stuid,
			usertype:$("#usertype").val()
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
					createBjList(result.data.bjList);
				}else{
					console.log(result.ret.code+":"+result.ret.msg);
				}
			}
		});
}
/**
 * 创建班级下拉选项

function createBjList(bjData){
	var bjList = new Array();
	bjList.push('<div class="checkAll" onclick="isCheckAll(this);">');
	bjList.push('<span name="checkAll" class="le">全选</span>');
	bjList.push('<span class="ri"><img alt="checked" src="'+basicParameters.ctx+'/public/mobile/img/checked.png" /></span>');
	bjList.push('</div>');
	bjList.push('<ul>');
	var bjids = new Array();
	var bjmcs = new Array();
	for(var i=0;i<bjData.length;i++){
		bjList.push('<li onclick="isCheck(this);">');
		bjList.push('<span name="checkName" class="le">'+bjData[i].bj+'</span>');
		bjList.push('<span class="ri"><img alt="check" src="${ctx}/public/mobile/img/check.png" /></span>');
		bjList.push('<input type=hidden name="check" value="'+bjData[i].id+'" /></li>')
		bjids.push(bjData[i].id);
		bjmcs.push(bjData[i].bj);
	}
	bjList.push('</ul>');
	bjList.push('<div class="btnBox">');
	bjList.push('<div class="btn">');
	bjList.push('<div class="box">');
	bjList.push('<span class="ok" onclick="saveChecked(\'classList\');">确认</span>');
	bjList.push('</div>');
	bjList.push('<div class="box">');
	bjList.push('<span onclick="closeBox();">取消</span>');
	bjList.push('</div>');
	bjList.push('</div>');
	bjList.push('</div>');
	if(bjData=='' || bjData == null || bjData.length===0){
		PB.prompt("没有班级！");
		setTimeout(function(){
			window.history.go(-1);
		},1500);
	}else{
		$("#classList").html(bjList.join(''));
		$('#classListShow').html(setMultiselectKey(bjmcs,bjmcs.length));
		$('#classListValue').val(bjids.join(','));
		var defauleSelected = $('#classList').find('input');
		var defauleSelectedImg = $('#classList').find('img');
		defauleSelected.attr("name","checked");
		defauleSelectedImg.attr("src",oss+"/public/mobile/img/checked.png");
		defauleSelectedImg.attr("alt","checked");
	}
}

function setMultiselectKey(bjmcs,top){
	var multiselectKey = new Array();
	var length = top;
	if(top>2)length=2;
		for(var i=0;i<length;i++){
			multiselectKey.push(bjmcs[i]);
		}
		var keyValue = multiselectKey.join('、');
	if(multiselectKey.length===1){
		return keyValue.substring(0,keyValue.length);
	}else{
		return keyValue.substring(0,keyValue.length)+"等"+top+"个班级";
	}
}
 */
/**
 * 设置滑动监听事件
 */
$(document).scroll(function(){
	if(document.body.scrollTop + document.body.clientHeight >= document.body.scrollHeight){
		if(nowPage!==-1&&nowPage!==1){
			createLoadDiv();
			queryAlbumList();
		}
	}
});

/**
 * 创建加载中提示
 */
function createLoadDiv(){
	PB.prompt("正在加载...","forever");
}
/**
 * 加载完成
 */
function createOverDiv(){
//	PB.prompt("没有数据！");
	nowPage=-1;
}
/**
 * 改变页码
 */
function nowPageChange(){
	nowPage = nowPage + 1;
}
/**
 * 移除数据载入中提示
 */
function removeLoadDiv(){
	PB.promptHide();
	PB.hideShade();
}
/**
 * 创建没有数据提示
 */
function noDataDiv(){
	var div = '<div id="noDataDiv" class="noData">还没有任何数据哦</div>';
	if($("#noDataDiv").length == 0){
		$('.albumList').html(div);
	}
}
/**
 * 移除没有数据提示
 */
function removeNoDataDiv(){
	$('#noDataDiv').remove();
}

/**
 * 获取相册列表
 */
function queryAlbumList(){
	
	var submitData = {
			pageSize:pageSize,
			nowPage:nowPage
		};
		$.ajax({
			cache:false,
			type: "POST",
			url: sxclisturl,
			data: submitData,
			success: function(datas){
				
				var result = JSON.parse(datas);
				if(result.ret.code==="200"){
					createAlbumList(result.data.albumList);
					nowPageChange();
					removeLoadDiv();
					if(result.data.albumList.length === 0 && nowPage===1){
						noDataDiv();
					}else if(result.data.albumList.length === 0){
						createOverDiv();
					}else{
						removeNoDataDiv();
					}
				}else{
					console.log(result.ret.code+":"+result.ret.msg);
				}
			}
		});
}

/**
 * 相册列表组装
 */

 
function createAlbumList(dataList){
	var albumBox = new Array();
	for(var i=0;i<dataList.length;i++){
		if((i+1)%2!==0){
			albumBox.push('<div class="albumBox albumBox-left">');
		}else{
			albumBox.push('<div class="albumBox albumBox-right">');
		}
		albumBox.push('<a href="javascript:jumpForm(\''+dataList[i].tag+'\',\''+dataList[i].sid+'\',\''+dataList[i].total+'\',\''+dataList[i].tagid+'\')">');
		if(dataList[i].picurl){
			albumBox.push('<div class="albumCover div-imgMask">');
			albumBox.push('<img class="img-adaptive" title="" src="'+dataList[i].picurl+'" >');
			albumBox.push('</div>');
		}else{
			albumBox.push('<img class="albumCover" title="" style="background-color: #e6e6e6;" >');
		}
		albumBox.push('<div class="bg-dark"></div>');
		albumBox.push('<div class="bg-tint"></div>');
		albumBox.push('<div class="ablumBottom" ><span class="ablumName">'+dataList[i].tag+'</span><span class="ablumTotal">（'+dataList[i].total+'张）</span></div>');
		albumBox.push('</a>');
		albumBox.push('</div>');
	}
	albumBox.push('<div class="cl"></div>');
	$('#albumList').append(albumBox.join(''));
	setImageHeight();
	imagesAdaptive();
}
function imagesAdaptive(){
	$(".img-adaptive").one('load', function() {
		var proportion = $(this).width()/$(this).height();
	 	if($(this).width()>$(this).height()){
	 		var originalWidth = $(this).parent('.div-imgMask').width();
	 		$(this).height(originalWidth);
	 		var changeWidth = originalWidth*proportion;
	 		$(this).width(changeWidth);
	 		$(this).css("margin-left",-Math.round((changeWidth-originalWidth)/2)+"px");
	 		$(this).removeClass('img-adaptive');
	 	}else{
	 		var originalHeight = $(this).parent('.div-imgMask').width();
	 		$(this).width(originalHeight);
	 		var changeHeight = originalHeight/proportion;
	 		$(this).height(changeHeight);
	 		$(this).css("margin-top",-Math.round((changeHeight-originalHeight)/2)+"px");
	 		$(this).removeClass('img-adaptive');
	 	}
	 	$(".div-imgMask").height($(".div-imgMask").width());
	}).each(function() {
		if(this.complete) $(this).load();
	});
}

/**
 * 返回
 */
function cancel(){
	window.history.go(-1);
}
/**
 * 跳转新增页面
 */
function jumpAdd(){
	var url = basicParameters.loadingPage+"&appid="+ApiParamUtil.ALBUM_PIC_ADD_JUMP;
	if($("#usertype").val()){
		url = url + "&usertype="+$("#usertype").val();
	}
	location.href = url;
}

/**
 * 跳转详情页面
*/
function jumpForm(albumName,sid,total,tagid){
	var url = $('#basicParameters').val()+"&sid="+sid+"&type=1";
	
	location.href = url;
	/**var params = {
			albumName:albumName,
			wlzytype:wlzytype,
			albumTotal:total,
			albumClass:$('#classListShow').text(),
			bjids:$('#classListValue').val(),
			tagid:tagid
	};
	var temp = document.createElement("form");      
		temp.action = basicParameters.loadingPage+"&appid="+ApiParamUtil.ALBUM_PIC_LIST_JUMP;      
		temp.method = "post";      
	    temp.style.display = "none";      
	    for (var key in params) {      
	        var opt = document.createElement("textarea");      
	        opt.name = key;      
	        opt.value = params[key];      
	        temp.appendChild(opt);      
	    }      
	    document.body.appendChild(temp);      
	    temp.submit();      
	    return temp;
		**/
}
/**
function showSelectBox(obj){
	if($("#"+obj).find("ul").children().length > 0){
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
							liList.eq(j).find("img").attr("src",oss+"/public/mobile/img/checked.png");
							liList.eq(j).find("input[type=hidden]").attr("name","checked");
							liList.eq(j).find("span[class=le]").attr("name","checkedName");
							break;
						}else{
							liList.eq(j).find("img").attr("alt","check");
							liList.eq(j).find("img").attr("src",oss+"/public/mobile/img/check.png");
							liList.eq(j).find("input[type=hidden]").attr("name","check");
							liList.eq(j).find("span[class=le]").attr("name","checkName");
						}
					}
				}
			}else{
				$("#"+obj).find("img").attr("alt","check");
				$("#"+obj).find("img").attr("src",oss+"/public/mobile/img/check.png");
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

function isSelect(obj){
	$(obj).parent().children().removeAttr("class");
	$(obj).parent().find("span[class=le]").attr("name","selectName");
	$(obj).parent().find("input[type=hidden]").attr("name","select");
	$(obj).attr("class","selected");
	$(obj).find("span[class=le]").attr("name","selectedName");
	$(obj).find("input[type=hidden]").attr("name","selected");
	saveSelected(obj);
	closeBox();
	if($("#select_class").is(":visible")&&$(obj).parents('.single').attr("id")==="gradeList"){
		//getClassList();
	}
}

function isCheckAll(obj){
	var alt = $(obj).find("img").attr("alt");
	if(alt == "check"){
		$(".ri").find("img").attr("alt","checked");
		$(".ri").find("img").attr("src",basicParameters.ctx+"/public/mobile/img/checked.png");
		$("li").find("input").attr("name","checked");
		$("li").find("span[class=le]").attr("name","checkedName");
	}else{
		$(".ri").find("img").attr("alt","check");
		$(".ri").find("img").attr("src",basicParameters.ctx+"/public/mobile/img/check.png");
		$("li").find("input").attr("name","check");
		$("li").find("span[class=le]").attr("name","checkName");
	}
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
		var top = 2;
		if(checkedListName.length<2){
			top = checkedListName.length;
		}
		var multiselectKey = new Array();
		for(var i=0;i<top;i++){
			multiselectKey.push(checkedListName.eq(i).html());
		}
		var keyValue = multiselectKey.join('、');
		if(multiselectKey.length===1){
			$("#"+obj+"Show").html(keyValue.substring(0,keyValue.length));
		}else{
			$("#"+obj+"Show").html(keyValue.substring(0,keyValue.length)+"等"+checkedListName.length+"个班级");
		}
	}
	$("#"+obj+"Value").val(checkedids);
	closeBox();
	$('#albumList').html('');
	nowPage=1;
	queryAlbumList();
}

function isCheck(obj,id){
	var alt = $(obj).find("img").attr("alt");
	if(alt == "check"){
		$(obj).find("img").attr("alt","checked");
		$(obj).find("img").attr("src",basicParameters.ctx+"/public/mobile/img/checked.png");
		$(obj).find("input").attr("name","checked");
		$(obj).find("span[class=le]").attr("name","checkedName");
	}else{
		$(obj).find("img").attr("alt","check");
		$(obj).find("img").attr("src",basicParameters.ctx+"/public/mobile/img/check.png");
		$(obj).find("input").attr("name","check");
		$(obj).find("span[class=le]").attr("name","checkName");
	}
	if($('input[name=checked]').length===$('.selectList').find('li').length){
		$(".checkAll").find("img").attr("alt","checked");
		$(".checkAll").find("img").attr("src",basicParameters.ctx+"/public/mobile/img/checked.png");
	}else{
		$(".checkAll").find("img").attr("alt","check");
		$(".checkAll").find("img").attr("src",basicParameters.ctx+"/public/mobile/img/check.png");
	}
}
*/