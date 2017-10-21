function getSerializeValue(formId){
	var form = $("#"+formId);
	 x=form.serialize();
	 return x;
}
/**其他数据
 * @param data 原数据
 * @param otherData 新增数据 格式 key=value
 * @returns
 */
function getOtherData(data,otherData){
	if(otherData != null && otherData != ''){
		if(data == ''){
			data += otherData;
		}else{
			data += "&"+otherData;
		}
	}
	return data;
}
/**替换全部
 * @author hxq
 *
 */
String.prototype.replaceAll = function (str1,str2){
	  var str    = this;     
	  var result   = str.replace(eval("/"+str1+"/gi"),str2);
	  return result;
}
/**把英文逗号转换为中文逗号以防止数据混乱
 * @param id this obj
 */
function replaceDouhao(id){
	 id.value=id.value.replaceAll(",",'，'); 
}
/**
 * @param formId	表单id	
 * @param callBack 回调函数
 * @param url	地址 如果form表单没有地址或没有form表单
 * @param otherData	其他数据
 */
function postObj(formId,callBack,url,otherData){
	if(url == null || url == ''){
		url = $("#"+formId).attr("action");
	}
	var data = getSerializeValue(formId);
	data = getOtherData(data,otherData);
	postAjax(url,data,"post",callBack)
}

/** ajax 请求
 * @param url	地址
 * @param data	数据
 * @param type	post or get
 */
function postAjax(url,data,type,callBack){
	$.ajax({
        type: type,
        url: url,
        data: data,
//        dataType: "json",
        contentType:"application/x-www-form-urlencoded; charset=utf-8",
        async:true,
        success: function(data){
        	var jsonData = eval("(" + data + ")");
        	callBack(jsonData);
        }
    })
}

/**
 * @param formId	表单id	
 * @param callBack 回调函数
 * @param url	地址 如果form表单没有地址或没有form表单
 * @param otherData	其他数据
 */
function queryObj(formId,callBack,url,otherData){
	if(url == null || url == ''){
		url = $("#"+formId).attr("action");
	}
	var data = getSerializeValue(formId);
	data = getOtherData(data,otherData);
	getAjax(url,data,"get",callBack)
}
/** ajax 请求
 * @param url	地址
 * @param data	数据
 * @param type	post or get
 */
function getAjax(url,data,type,callBack){
	$.ajax({
        type: type,
        url: url,
        data: data,
//        dataType: "json",
        contentType:"application/x-www-form-urlencoded; charset=utf-8",
        async:true,
        success: function(data){
        	callBack(data);
        }
    })
}
