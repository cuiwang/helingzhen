/**
 * 获取网页的宽度和高度
 * @param  {string} get    需要的宽（w）或高（h）
 * @return 如果参数get存在，则返回相应宽或高，如果get没有写则返回数组
 */
function getBodySize(get) {
    var bodySize = [];
    bodySize['w']=($(document.body).width()>$(window).width())? $(document.body).width():$(window).width();
    bodySize['h']=($(document.body).height()>$(window).height())? $(document.body).height():$(window).height();
    return get?bodySize[get]:bodySize;
}

/**
 * 通用AJAX提交
 * @param  {string} url    表单提交地址
 * @param  {string} formObj    待提交的表单对象或ID
 */
function commonAjaxSubmit(url,formObj){
    if(!formObj||formObj==''){
        var formObj="form";
    }
    if(!url||url==''){
        var url=document.URL;
    }
    $(formObj).ajaxSubmit({
        url:url,
        type:"POST",
        dataType:"json", //数据类型  
        success:function(data, st) {
            if(data.status==1){
				/**
                popup.success(data.info);
                setTimeout(function(){
                    popup.close("asyncbox_success");
                },2000);
				**/
				
				fhalert(data.info);
            }else{
				/**
                popup.error(data.info);
                setTimeout(function(){
                    popup.close("asyncbox_error");
                },2000);
				**/
				fhalert(data.info);
				
            }
           // return ;
			if(data.url&&data.url!=''&&data.type!=''){
				setTimeout(function(){
                	window.location.href=data.url;
                },2000);
				return false;
			}
		  
            if(data.url&&data.url!=''){
                setTimeout(function(){
                	top.window.location.href=data.url;
                },2000);
            }
            if(data.url==''){
                setTimeout(function(){
                	top.window.location.reload();
                },1000);
            }
        }
    });
    return false;
}

function fhalert_nfida(msg){
	 popup.success(msg);
     setTimeout(function(){
     popup.close("asyncbox_success");
     },2000);
}


function fhalert(msg){
	var dd = layer.load(msg);
	setTimeout(function(){layer.close(dd);},2000); 
}

/**
 * 检测字符串是否是电子邮件地址格式
 * @param  {string} value    待检测字符串
 */
function isEmail(value){
    var Reg =/^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
    return Reg.test(value);
}

function delByLink(link,obj){
    $.get(link,function(data){
        if(data.status==1){
			obj.parent('td').parent('tr').hide(300);
		 }else{
            popup.error(data.info);
        }
    });
}


function changeStatus(url,v){
			$.get(url,function(data){
				if(data.status==1){
					$(v).html(data.info);
				}
			});
	}