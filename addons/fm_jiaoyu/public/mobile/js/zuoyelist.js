/**
 * 公共删除页面
 */
//每页数量
var pageSize = 8;
//当前页码
var nowPage = 0;

$(document).ready(function() {
	queryDataList();
	createLoadDiv();
	$("#canBtn").click(function(){
		cancelBtn();
	});
	$("#delBtn").click(function(){
		deleteBtn();
	});
});

$(document).scroll(function(){
	if(document.body.scrollTop + document.documentElement.clientHeight >= document.body.scrollHeight){
		if(nowPage!==-1){
			createLoadDiv();
			queryDataList();
		}
	}
});

function createLoadDiv(){
	PB.prompt("数据加载中。。。","forever");
}
function createOverDiv(){
	PB.prompt("没有更多数据了！");
	nowPage=-1;
}
function nowPageChange(){
	nowPage = nowPage + 1;
}
function removeLoadDiv(){
	PB.promptHide();
	PB.hideShade();
}

function noDataDiv(){
	var div = '<div id="noDataDiv" class="noData">还没有任何数据哦</div>';
	$("#dataList").html(div);
}

/**
 * 查询列表数据
 */
function queryDataList(){
	nowPageChange();
	var submitData = {
		pagesize:pageSize,
		nowpage:nowPage,
		getalist:1,
	};
	$.ajax({
		cache:false,
		type: "POST",
		posturl: listurl,
		data: submitData,
		success: function(datas){
			var result = typeof datas === "object" ? datas : JSON.parse(datas);
			//var result = eval(datas);
			if(result.ret.code==="200"){
				createDataList(result.data.dataList);
				removeLoadDiv();
				if(result.data.dataList.length === 0 && nowPage === 1){
					noDataDiv();
					//$('#headerMenu').hide();
				}else if(result.data.dataList.length === 0){
					createOverDiv();
				}
			}else{
				console.log(result.ret.code+":"+result.ret.msg);
			}
		}
	});
}

/**
 * 创建列表数据
 */
function createDataList(dataList){
	var myStudentData = new Array();
	var url = xqurl;
	myStudentData.push('<ul>');
	for(var i = 0;i<dataList.length;i++){
		myStudentData.push('<li>');
		myStudentData.push('<a href="'+url+'&id='+dataList[i].id+'">');
		myStudentData.push('<div class="box-left">');
		myStudentData.push('<div class="title">'+dataList[i].title+'</div>');
		myStudentData.push('<div class="ortherInf">');
		myStudentData.push('<span class="time">'+dataList[i].time+'</span>');
		if(dataList[i].banji!=="" && dataList[i].banji!== null && dataList[i].banji!== undefined){
			myStudentData.push('<span class="read">'+dataList[i].banji+'</span>');//班级
		}
		if(dataList[i].name!==""){
			myStudentData.push('<span class="choice">'+dataList[i].name+'</span>');
		}
		if(dataList[i].kemu!=="" && dataList[i].kemu!== undefined){
			myStudentData.push('<span class="choice">'+dataList[i].kemu+'</span>');
		}		
		if(dataList[i].ydrs!==0 && dataList[i].ydrs!== undefined){
			myStudentData.push('<span class="comment" style="color: #F7F4F4;position: relative;border-radius: 5px;font-size: 5px; margin-right: 5px; background-color: #E61717;">'+dataList[i].ydrs+'</span>');
		}
		myStudentData.push('</div></div>');
		myStudentData.push('</a>');
		myStudentData.push('<div class="box-right">');
		myStudentData.push('<div class="checkbox checkboxChange"><input type="hidden" value="'+dataList[i].id+'" /></div>');
		myStudentData.push('</div>');
		myStudentData.push('<div class="cl"></div>');
		myStudentData.push('</li>');
	}
	myStudentData.push('</ul>');
	$("#dataList").append(myStudentData.join(''));
}

/**
 * 显示选项
 */
function showCheckBox(){
	$('.checkbox').show();
	$('.checkDiv').show();
	$(".checkboxChange").click(function(){
		changeCheckBox(this);
	});
	$("#dataList").css("marginBottom","80px");
	$(".allCheck").click(function(){
		changeAllCheckBox(this);
	});
}

/**
 * 改变全部状态
 * @param node
 */
function changeAllCheckBox(node){
	if($(node).hasClass('checked')){
		$('.checkbox').removeClass('checked');
	}else{
		$('.checkbox').addClass('checked');
	}
}

/**
 * 判断是否全选
 */
function judgeAllCheck(){
	if($('.checkboxChange').length===$('.checkboxChange.checked').length){
		$(".allCheck").addClass('checked');
	}else{
		$('.allCheck').removeClass('checked');
	}
}

/**
 * 改变单个选项状态
 * @param node
 */
function changeCheckBox(node){
	if($(node).hasClass('checked')){
		$(node).removeClass('checked');
	}else{
		$(node).addClass('checked');
	}
	judgeAllCheck();
}

/**
 * 取消
 */
function cancelBtn(){
	$('.checkbox').hide();
	$('.checkDiv').hide();
	$(".checkbox").click(function(){
		changeCheckBox(this);
	});
	$("#dataList").css("marginBottom","0");
	$(".allCheck").click(function(){
		changeAllCheckBox(this);
	});
}

/**
 * 删除
 */
function deleteBtn(){
	var chekedArray = new Array();
	$('.checked').find('input').each(function(){
		chekedArray.push($(this).val())
	});
	if(confirm("确定要删除吗？")){
	var submitData = {
			api:api,
			id:chekedArray.toString()
		};
	$.ajax({
		cache:false,
		type: "POST",
		url: basicParameters.loadingData,
		data: submitData,
		success: function(datas){
			var result = typeof datas === "object" ? datas : JSON.parse(datas);
			if(result.ret.code==="200"){
				$('.checked').parents('li').each(function(){
					$(this).remove();
				});
				if($("#dataList").find("li").length===0){
					window.location.reload();
				}
			}else{
				console.log(result.ret.code+":"+result.ret.msg);
			}
		}
	});
	}
}