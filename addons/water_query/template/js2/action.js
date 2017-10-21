/**
 * 切换DIV层
 * @param div1
 * @param div2
 * @returns
 */
function changeDiv(div1,div2){
	$("#"+div1).hide();
	$("#"+div2).show();
}

/**
 * 给input赋值
 * @param dom
 * @param value
 */
function voluation(inputId,value){
	$("#"+inputId).val(value);
}

//验证码计时器
var checkCodeTimer;
//验证码倒计时
var checkCodeTime = 60;
/**
 * 获取验证码
 * @returns
 */
function getCode(){
	var flag = false;
	var userName = $("#userName").val().trim();
	if(userName == ""){
		mui.toast('请输入姓名');
		return flag;
	}
	var phone = $("#phone").val().trim();
	//判断手机号是否为空
	if(phone == ""){
		mui.toast('请输入手机号');
		//alert("请输入手机号.");
		return flag;
	}
	//判断手机号是否正确
	if(!isMobileNo(phone)){
		mui.toast("请输入正确的手机号");
		return flag;
	}
	if(baseSource == 1){
		var url = getAjaxUrl("cuetomer/getCheckCode.shtml");
	}else if(baseSource == 2){
		var url = getAjaxUrl("v2/cuetomer/appGetCheckCode.shtml");
	}
	showShade(1);
	$.ajax( {
		type : "post",
		dataType : "json",
		data : {"phone" : phone},
		url : url,
		success : function(result) {
			getOpenIdCode(result,function(){
				if(result.success){
					if(result.FHM=="0" || result.FHM=="1"){
						$("#check_code").val(result.CODE);
						mui.toast("验证码已发送至您的手机，请注意查收！");
						changeDiv("get_code_show_a","get_code_hide_a");
						//$("#code-layer").show();
						checkCodeTimer = setInterval(function(){countdown(checkCodeTime,checkCodeTimer,"check_code_count_down_number_span",
								function(){
									
								},
								function(){
							changeDiv("get_code_hide_a","get_code_show_a");
						});}, 1000);
						$("#code_input_div").show();
						flag =  true;
					}else{
						mui.alert('系统异常，请稍后再试！');
					}
				}else{
					mui.alert("系统异常，请稍后再试！");
				}
			});
		},error : function(result){
			mui.alert("系统异常，请稍后再试！");
		}
	});
	
	return flag;
}

/**
 * 倒计时数字
 */
var countDownNumber = -1;

/**
 * 倒计时
 */
function countdown(time,timer,showDiv,ingCallback,endCallback){
	if(countDownNumber == -1){
		countDownNumber = time;
	}
	countDownNumber--
	if(countDownNumber <= 0){
		endCallback();
		clearInterval(timer);
		countDownNumber = -1;
	}
	ingCallback();
	$("#"+showDiv).html(countDownNumber);
	
}

/**
 * 判断是否手机号
 * @param no
 * @returns
 */
function isMobileNo(no){
	var phone = no;
	/*var reg0 = /^13\d{5,9}$/;
	var reg1 = /^15\d{5,9}$/;
	var reg2 = /^15\d{5,9}$/;
	var reg3 = /^0\d{10,11}$/;
	var reg4 = /^17\d{3,9}$/;
	var reg5 = /^18\d{5,9}$/;*/
	var reg0 = /^13\d{9}$/;
	var reg1 = /^15\d{9}$/;
	var reg2 = /^15\d{9}$/;
	var reg3 = /^0\d{10,11}$/;
	var reg4 = /^17\d{9}$/;
	var reg5 = /^18\d{9}$/;
	var my = false;
	if (reg0.test(phone))my=true;
	if (reg1.test(phone))my=true;
	if (reg2.test(phone))my=true;
	if (reg3.test(phone))my=true;   
	if (reg4.test(phone))my=true;
	if (reg5.test(phone))my=true; 
	return my;
}

/**
 * 完善个人信息（绑定手机）
 * @returns
 */
function customerSetContactForBinding(){
	var userName = $("#userName").val();
	var loginName = $("#phone").val();
	var password = $("#password").val();
	showShade(1);
	$.ajax({
		type : "POST" ,
		url : getAjaxUrl("cuetomer/weiXinBindingPhone.shtml") ,
		data : {
			"userName" : userName,
			"loginName" : loginName,
			"password" : password
			},
		dataType : "JSON",
		success : function(result) {
			getOpenIdCode(result,function(){
				if(parseInt(result.FHM)==0 || parseInt(result.FHM)==1 || parseInt(result.FHM)==-4){
					mui.toast("成功");
					changeDiv("next_show_a","next_hide_a");
					/*location_to_receivemode();*/
					location.href=redirect_uri;
				}else if(parseInt(result.FHM)==-1){
					mui.alert("系统原因，请您稍后再试");
				}else if(parseInt(result.FHM)==2){
					mui.alert("验证码超时");
				}else if(parseInt(result.FHM)==3){
					mui.alert("非法操作");
				}else if(parseInt(result.FHM)==4){
					mui.alert("手机号已被其他用户绑定，请联系客服");
				}else if(parseInt(result.FHM)==3){
					mui.alert("验证码错误");
				}
			});
		}
	});
	
}

/**
 * app登录
 * @returns
 */
function appLogin(){
	var loginName = $("#phone").val();
	var password = $("#password").val();
	showShade(1);
	$.ajax({
		type : "POST" ,
		url : getAjaxUrl("v2/cuetomer/appLogin.shtml") ,
		data : {
			"loginName" : loginName,
			"password" : password
			},
		dataType : "JSON",
		success : function(result) {
			getOpenIdCode(result,function(){
				if(parseInt(result.FHM)==0 || parseInt(result.FHM)==1 || parseInt(result.FHM)==-4 || parseInt(result.FHM)==6 || parseInt(result.FHM)==7){
					mui.toast("成功");
					location.href=redirect_uri;
				}else if(parseInt(result.FHM)==-1){
					mui.alert("系统原因，请您稍后再试");
				}else if(parseInt(result.FHM)==2){
					mui.alert("验证码超时");
				}else if(parseInt(result.FHM)==3){
					mui.alert("非法操作");
				}else if(parseInt(result.FHM)==4){
					mui.alert("手机号已被其他用户绑定，请联系客服");
				}else if(parseInt(result.FHM)==3){
					mui.alert("验证码错误");
				}
			});
		}
	});
	
}

/**
 * 洗涤项目加载
 */
function washingProjectsLoad(){
	showShade(1);
	$.ajax( {
		type : "post",
		dataType : "HTML",
		url : getAjaxUrl("v2/WashingProject/getAllWashingProject.shtml"),
		success : function(html) {	
			getOpenIdCode(html,function(){
				$("#productList_div").html(html);
				productTouchend();
			});
		}
	});
	
}

/**
 * 洗衣模式加载
 */
function washingModeLoad(){
	showShade(1);
	$.ajax( {
		type : "post",
		dataType : "html",
		url : getAjaxUrl("v2/order/washingModeSelect.shtml"),
		success : function(html) {
			getOpenIdCode(html,function(){
				$("#washing_mode_li").html(html);
			});
		}
	});
	
}

/**
 * 自助柜子加载
 */
function autocabListLoad(){
	showShade(1);
	$.ajax( {
		type : "post",
		dataType : "html",
		url : getAjaxUrl("v2/order/autocabSelect.shtml"),
		success : function(html) {
			getOpenIdCode(html,function(){
				$("#context").show();
				//$("#select-situs").html(html);
				$("#select-situs").prepend(html);
			});
		}
	});	
	
}

/**
 * 自助柜规格加载
 */
function autocabItemLoad(){
	var autocabId = decodeURIComponent(getParameter("autocabId"));
	var autocabName = decodeURIComponent(getParameter("autocabName"));
	showShade(1);
	$.ajax( {
		type : "post",
		dataType : "html",
		url : getAjaxUrl("v2/order/autocabStatusSelect.shtml"),
		data : {
			"autocabId" : autocabId,
			"autocabName" : autocabName
		},
		success : function(html) {
			getOpenIdCode(html,function(){
				$("#autocab_info_div").html(html);
			});
		}
	});	
	
}


//预约自助柜计时器
var autocabYuYueTimer;
//预约自助柜倒计时时间
var ziZhuGuiYuYueSec = 60;
//自助柜存放计时器
var ziZhuGuiCunFangTimer;
/**
* 自助柜预约
*/
function autocabYuYue(){
	///dataJson  {"boxType":"2","name":"No.146 颐景园自助收送柜","remarks":""}
	//dataJson_boxType 箱格类型   1超大 2大 3中 4小
	var selectedZiZhuGuiName = decodeURIComponent(getParameter("selectedZiZhuGuiName"));//$("#selectedZiZhuGuiName").val();
	var autocabBoxNumber = getParameter("autocabBoxNumber");
	var selectedZiZhuGuiQuanDiZhi = decodeURIComponent(getParameter("selectedZiZhuGuiQuanDiZhi"));
	var remarks = "";
	var orderNo = "";
	showShade(1);
	$.ajax( {
		type : "post",
		dataType : "json",
		url : getAjaxUrl("v2/order/placeOrderConfirm.shtml"),
		data : "collectClothesPatternState=1&boxType="+autocabBoxNumber+"&name="+selectedZiZhuGuiName+"&remarks="+remarks,
		success : function(result) {	
			getOpenIdCode(result,function(){
				if(result.cabcode=="00000" || result.cabcode=="00014"){
					$("#autocab_order_sn_input").val(result.orderno);
					autocabYuYueTimer = setInterval(
							function(){
								countdown(
									ziZhuGuiYuYueSec,
									autocabYuYueTimer,
									"ziZhuGuiDaoJiShi_wait",
									function(){
										checkZiZhuGui(result.orderno);
										$("#ziZhuGuiName_success").html(selectedZiZhuGuiName);
										$("#ziZhuGuiQuanDiZhi_success").html(selectedZiZhuGuiQuanDiZhi);
										$("#ziZhuGuiName_wait").html(selectedZiZhuGuiName);
										$("#ziZhuGuiQuanDiZhi_wait").html(selectedZiZhuGuiQuanDiZhi);
										$("#ziZhuGuiName_error").html(selectedZiZhuGuiName);
										$("#ziZhuGuiQuanDiZhi_error").html(selectedZiZhuGuiQuanDiZhi);
									},
									function(){
										changeDiv("autocab_wait", "autocab_error");
										alert("预约失败");
							});}
						, 1000);
				}else{
					changeDiv("autocab_wait", "autocab_error");
					alert("预约失败，cabcode="+result.cabcode);
				}
			});
			
		}
	});
	
}

/**
* 检查自助柜是否预约成功
* @param checkType
* @param orderId
*/
function checkZiZhuGui(orderId){
	showShade(1);
	$.ajax( {
		type : "post",
		dataType : "json",
		url : getAjaxUrl("v2/order/autoCabOk.shtml"),
		data : "orderId="+orderId+"&remarks="+0,
		success : function(result) {	
			getOpenIdCode(result,function(){
				if(result.success){
					changeDiv("autocab_wait", "autocab_success");
					$("#ziZhuGuiOpenCode").html(result.kxm);
					clearInterval(autocabYuYueTimer);
					//ziZhuGuiCunFangTimer = setInterval(function(){checkCunFang(orderId);}, 1000);
				}
			});
		}
	});
	
}

/**
* 检查自助柜是否存放
* @param checkType
* @param orderId
* @param ziZhuGuiId
*/
function checkCunFang(){
	var orderId=$("#autocab_order_sn_input").val();
	/*ziZhuGuiYuYueSec--;
	if(ziZhuGuiYuYueSec<1){
		ziZhuGuiYuYueSec = 60;
		clearInterval(ziZhuGuiYuYueTimer);
	}*/
	showShade(1);
	$.ajax( {
		type : "post",
		dataType : "json",
		url :getAjaxUrl("v2/order/autoCabOk.shtml"),
		data : "orderId="+orderId+"&remarks="+1,
		success : function(result) {
			getOpenIdCode(result,function(){
				if(result.success){
					//clearInterval(ziZhuGuiCunFangTimer);
					alert("衣服已经存放");
					/*location.href=basePath+"lmxyApp/washing/"+"ordersuccess.html?orderNo="+encodeURIComponent(orderId);*/
					location.href="ordersuccess.html?orderNo="+encodeURIComponent(orderId);
				}
			});
		}
	});
	
}

/**
* 更新自助柜
* @param ziZhuGuiName
*/
function updateBoxStatus(ziZhuGuiName){
	showShade(1);
	$.ajax( {
		type : "post",
		dataType : "json",
		url : getAjaxUrl("autocab/updateBoxStatus.shtml"),
		data : "deviceName="+ziZhuGuiName,
		success : function(result) {	
			getOpenIdCode(result,function(){});
		}
	});
	
}

/**
* 取消自助柜的预约
*/
function cancelZiZhuGuiYuYue(orderNo){
	if(orderNo==null || orderNo == "null" || orderNo.trim() == ""){
		orderNo = $("#autocab_order_sn_input").val();
	}
	showShade(1);
	$.ajax( {
		type : "post",
		dataType : "text",
		url : getAjaxUrl("autocab/youbao_cancelBox.shtml"),
		data : "orderNo="+orderNo,
		success : function(result) {
			getOpenIdCode(result,function(){});
		}
	});	
	
}

/**
 * 上门收衣预约
 */
function shangMengShouYiYuYue(){
	var collectClothesPatternState = 3;
	//String startdata = (String) this.getParameter("startdate");//startdate.value  	上门收衣日期
	//String remarks = (String) this.getParameter("remarks");//remarks.value			留言
	//String starttime = (String) this.getParameter("starttime");//startTime.value	上门收衣时间
	//String checked = (String) this.getParameter("checked");//place1_time.checked	是否选择送衣时间   place1_not_choice_time
	//String backdate = (String) this.getParameter("backdate");//backDate.value		送衣日期
	//String backtime = (String) this.getParameter("backtime");//backTime.value		送衣时间
	//String addrstartid = (String) this.getParameter("addrstartid");//addrStart.value   选择的收衣地址ID
	//String addrstartmc = (String) this.getParameter("addrstartmc");//addrStart.options[addrStart.selectedIndex].text    选择的收衣地址标题
	//String addrendid = (String) this.getParameter("addrendid");//addrEnd.value		送衣地址ID
	//String addrendmc = (String) this.getParameter("addrendmc");//addrEnd.options[addrEnd.selectedIndex].text	送衣地址标题
//	var startdate = decodeURIComponent(getParameter("startdate"));
//	var starttime = decodeURIComponent(getParameter("starttime"));
	//var starttime = decodeURIComponent(getParameter("starttime"));
	var startdate = $("#startdate").val();
	var starttime = $("#starttime").val();
	var checked = false;
	var remarks = "";
	showShade(1);
	$.ajax( {
		type : "post",
		dataType : "json",
		url : getAjaxUrl("v2/order/placeOrderConfirm.shtml"),
		data : {
			collectClothesPatternState : collectClothesPatternState,
			startdate : startdate,
			starttime : starttime,
			remarks : remarks,
			checked : checked,
			
		},
		success : function(result) {
			getOpenIdCode(result,function(){
				if (result.success) {
					var orderno = result.orderno;
					/*location.href= basePath+"lmxyApp/washing/"+"ordersuccess.html?orderno=" + orderno;*/
					location.href="ordersuccess.html?orderno=" + orderno;
				}
			});
		}
	});
	
}

/**
 * 是否绑定
 */
function isBind(){
	var flag = false;
	showShade(1);
	$.ajax( {
		type : "post",
		dataType : "json",
		url : getAjaxUrl("cuetomer/isBind.shtml"),
		success : function(result) {
			getOpenIdCode(result,function(){
				if(result.success=="true"){
					flag = true;
				}
			});
		}
	});
	return flag;
}



/**
 * 用户收货地址刘表
 */
function userAddressList(){
	showShade(1);
	$.ajax({
		type:"post",
		url:getAjaxUrl("v2/order/userAddressList.shtml"),
		dataType:"html",
		success:function(html){
			getOpenIdCode(html,function(){
				$("#address_list_ul").html(html);
		  		if($("#address_list_ul").html().trim() == ""){
		  			$("#address_1_h4").hide();
		  			$("#address_2_h4").hide();
		  			$("#address_3_h4").show();
		  		}
			});
		}
	});
	
}

/**
 * 用户默认收货地址
 */
function userDefaultAddress(){
	showShade(1);
	$.ajax({
		type:"post",
		url:getAjaxUrl("v2/order/userDefaultAddress.shtml"),
		dataType:"html",
		success:function(html){
			getOpenIdCode(html,function(){
				$("#user_default_address_from").prepend(html);
			});
		}
	});
	
}

/**
 * 加载地区
 */
function selectArea(selectId, displayObj){
	if(selectId == "-1") return;
	showShade(1);
	$.ajax( {
		type : "post",
		dataType : "text",
		url : getAjaxUrl("area/getAreaList.shtml?qyId=" + selectId),
		data : {
		},
		success : function(result) {
			getOpenIdCode(result,function(){
				var strOption="";
				var json =  eval('(' + result + ')');
				if(displayObj == 'county'){
					strOption = "<option value=''>请选择区县</option>";
				}
				if(displayObj == 'city'){
					strOption = "<option value=''>请选择城市</option>";
				}
				for(var i=0; i<json.length; i++){
					if(getAddr_district == json[i].QY_MC){
						strOption = strOption +  '<option value="' + json[i].QY_ID + '"  selected="selected" >' + json[i].QY_MC + '</option>';
					}else{
						strOption = strOption +  '<option value="' + json[i].QY_ID + '">' + json[i].QY_MC + '</option>';
					}
				} 
				var obj=document.getElementById(displayObj);
				if(obj)
					obj.innerHTML = strOption;
				if(displayObj == 'city'){
					selectArea(obj.value, 'county');
				}
				if(displayObj == 'province'){
					selectArea(obj.value, 'city');
				}
			});
		},error : function(result)
		{
			 
		}
	});
	
}

function getAreaCity(){
	$.ajax( {
		type : "post",
		dataType : "text",
		url : getAjaxUrl("area/getAreaCity.shtml"),
		data : {
		},
		success : function(result) {
			getOpenIdCode(result,function(){
				var strOption="";
				var json =  eval('(' + result + ')');
				strOption = "<option value=''>请选择城市</option>";
				for(var i=0; i<json.length; i++){
					if(getAddr_city == json[i].QY_MC){
						strOption = strOption +  '<option value="' + json[i].QY_ID + '"  selected="selected" >' + json[i].QY_MC + '</option>';
						selectArea(json[i].QY_ID,'county');
					}else{
						strOption = strOption +  '<option value="' + json[i].QY_ID + '">' + json[i].QY_MC + '</option>';
					}
				} 
				var obj=document.getElementById("city");
				if(obj){
					obj.innerHTML = strOption;
				}
				document.getElementById("county").innerHTML = "<option value=''>请选择区县</option>";
				//selectArea(obj.value, 'county');
				
			});
		}
	});
}

/**
 * 添加联系地址
 */
function addUserDefaultAddress(){
	var addrId = 0;
	var newAddrMc = document.getElementById("address_name");//地址名称  		countyMc
	if(newAddrMc.value.trim() == ""){//mui.toast('欢迎体验Hello MUI');
		mui.toast("请填写简称！");
		return;
	}
	if(newAddrMc.value.trim().length > 8){//mui.toast('欢迎体验Hello MUI');
		mui.toast("简称不要超过八个字呦");
		return;
	}
	var addrDist = document.getElementById("county");//最后的区域ID	county
	if(addrDist.value.trim() == ""){
		mui.toast("请选择省市区！");
		return;
	}
	var newAddr = document.getElementById("address_info");//新的联系地址  		cityMc
	if(newAddr.value.trim() == ""){
		mui.toast("请填写详细地址！");
		return;
	}
	var newContact = document.getElementById("address_userName");//新的联系人	contact
	if(newContact.value.trim() == ""){
		mui.toast("请填写联系人名字！");
		return;
	}
	//var newPhone = document.getElementById("phone");//新的联系电话	phone
	var newPhone = document.getElementById("address_phone");
	if(newPhone.value.trim() == ""){
		mui.toast("请填写手机号！");
		return;
	}
	if(!isMobileNo(newPhone.value.trim())){
		mui.toast("请填写正确的手机号！");
		return;
	}
	showShade(1);
	$.ajax( {
		type : "post",
		dataType : "json",
		url : getAjaxUrl("v2/order/newAddress.shtml"),
		data : {
			deliveryAddressId:addrId,
			countyMc:newAddrMc.value,
			cityMc:newAddr.value,
			county:addrDist.value,
			contact:newContact.value,
			phone:newPhone.value
		},
		success : function(data) {
			getOpenIdCode(data,function(){
				if(data.success){
					/*if(getParameter("type") == "0"){
						location.href=basePath+"lmxyApp/washing/"+"contactway02.html";
					}else{
						location.href=basePath+"lmxyApp/washing/"+"contactway.html";
					}*/
					if(redirect_uri != null){
						/*location.href=basePath+"lmxyApp/washing/"+"contactway02.html?redirect_uri="+redirect_uri;*/
						location.href="contactway02.html?redirect_uri="+redirect_uri;
					}else{
						/*location.href=basePath+"lmxyApp/washing/"+"contactway02.html";*/
						location.href="contactway02.html";
					}
					/*$("#province").val(1);
					$("#city").val(1);
					$("#county").val(1);
					newAddrMc.value = "";
					newAddr.value = "";
					newContact.value = "";
					$("#add_address").hide();
					$("#plase_add_address").hide();
					$("#address_list_ret_button").after(""+
						"<div id='address_item_deliveryAddressItem"+data.result.DA_ID+"' class='address-list list-group'>"+
						    "<div class='list-btn'>"+
							    "<a href='javascript:;' class='glyphicon glyphicon-edit'></a>"+
							    "<a href='javascript:;' class='glyphicon glyphicon-trash' onclick='delete_address("+data.result.DA_ID+")'></a>"+
						    "</div>"+
						    "<p>简称："+data.result.DA_MC+"</p>"+
						    "<p>联系人："+data.result.DA_SHR+"　手机："+data.result.DA_SJH+"</p>"+
						    "<p>地址："+data.result.QY_ONE_MC+data.result.QY_TWO_MC+data.result.QY_MC+data.result.DA_DZ+"</p>"+
					    "</div>");
					$("#address_list").show();*/
				}
			});
		}
	});
	
}

/**
 * 获取用户GPS坐标
 */
function getCustomerGps(){
	$.ajax( {
		type : "post",
		dataType : "json",
		url : getAjaxUrl("v2/cuetomer/getCustomerGps.shtml"),
		success : function(result) {
			getOpenIdCode(result,function(){
				if(result != null){
					$("#lat").val(result.WUL_GPSLAT);
					$("#lng").val(result.WUL_GPSLONG);
					$("#showPois").val(result.WUL_GPSPRE);
				}
				getAddr();
			});
		}
	});
}

/**
 * 选择默认收货地址
 * @param daId
 */
function selectDefaultAddress(daId){
	//default_user_address_name    setDefaultAddress
	if ($("#default_user_qy_mc"+daId).val() == "萧山区" ||
			$("#default_user_qy_mc"+daId).val() == "余杭区" ){
		alert("您所在的区域暂时不提供上门服务，服务恢复后我们会尽快通知您，谢谢您的理解！");
		return;
	}
	$("#select_default_address_id_input").val(daId);
	/*$(".delete_address_div").hide();
	$("#delete_address_div"+daId).show();*/
	/*$("#default_address_item_info_one").html("<p class='mui-table-view-cell fl'>联系人："+$("#default_user_name"+daId).val()+"&nbsp;&nbsp;&nbsp;&nbsp;手机：<b>"+$("#default_user_phone"+daId).val()+"</b></p>");
	$("#default_address_item_info_two").html("<p class='mui-h6 mui-ellipsis mui-table-view-cell'>地址："+$("#default_user_qy_one_mc"+daId).val()+$("#default_user_qy_two_mc"+daId).val()+$("#default_user_qy_mc"+daId).val()+$("#default_user_da_dz"+daId).val()+"</p>");
	$("#phone").val($("#default_user_phone"+daId).val());
	$("#userName").val($("#default_user_name"+daId).val());*/
	//changeDiv("address_list_div","default_address_div");
}

/**
 * 删除地址
 */
function delete_address(daId){
	
	/*confirm('确认要删除吗？','提醒',['是','否'],function(e){
  		if(e.index==0){
  			
  		}else{
  			return;
  		}
  	})*/
  	var confirmValue = confirm("确认要删除吗？")
  	if(confirmValue){
  		showShade(1);
		$.ajax( {
			type : "post",
			dataType : "text",
			url : getAjaxUrl("delivery/address/delete.shtml?address.daId="+daId),
			success : function(data) {
				getOpenIdCode(data,function(){
					if(data=="true"){
						$("#address_list_"+daId).remove();
						if($("#select_default_address_id_input").val().trim() == daId){
							$("#select_default_address_id_input").val("");
						}
						if($("#address_list_ul").html().trim() == ""){
				  			$("#address_1_h4").hide();
				  			$("#address_2_h4").hide();
				  			$("#address_3_h4").show();
				  		}
					}
				});
			}
		});
  	}
}

/**
 * 账户信息加载
 */
function accountInfoLoad(){
	showShade(1);
	$.ajax({
		type : "post",
		url : getAjaxUrl("accountV2/accountInfo.shtml"),
		success : function(html){
			getOpenIdCode(html,function(){
				$("#account_info_div").html(html);
			});
		}
	});
	
}

/**
 * 收入明细
 * @param page
 */
function inputAccountDetailsLoad(page){
	showShade(1);
    $.ajax({
		type : "POST",
		dataType : "html",
		url : getAjaxUrl("accountV2/inputAccountDetails.shtml"),
		success : function(html){
			getOpenIdCode(html,function(){
				$("#input_account_details_div").html(html);
			});
		}
    });
    
}

/**
 * 支出明细
 * @param page
 */
function outputAccountDetailsLoad(page){
	showShade(1);
    $.ajax({
		type : "POST",
		dataType : "html",
		url : getAjaxUrl("accountV2/outputAccountDetails.shtml"),
		success : function(html){
			getOpenIdCode(html,function(){
				$("#output_account_details_div").html(html);
			});
		}
    });
}

/**
 * 取消自助柜的预约
 */
function cancelZiZhuGuiYuYue(orderNo){
	if(orderNo==null || orderNo == "null"){
		orderNo = $("#ziZhuGui_success_YuYue_orderNo").val();
	}
	showShade(1);
	$.ajax( {
		type : "post",
		dataType : "text",
		url :getAjaxUrl("autocab/youbao_cancelBox.shtml"),
		data : "orderNo="+orderNo,
		success : function(result) {
			getOpenIdCode(html,function(){
				alert("已取消预约！");
				//location.href = basePath+"lmxyApp/index.html";
				location.href = "../index.html";
			});
		}
	});
}

/**
 * 添加推荐人
 * @param phone
 */
function addParentUserByCustomer(phone){
	showShade(1);
	$.ajax( {
		type : "post",
		dataType : "json",
		url :getAjaxUrl("v2/cuetomer/addParentUserByCustomer.shtml"),
		data : "phone="+phone,
		success : function(result) {
			getOpenIdCode(result,function(){
				if(result.code == 200){
					alert(result.message);
					location.reload(true);
				}else{
					alert(result.message);
				}
			});
		}
	});
}


/**
 * 充值卡充值
 */
function cardRecharge(){
	var cardNo,cardPwd;
	cardNo = document.getElementById("cardNo");
	cardPwd = document.getElementById("cardPwd");
	if(cardNo != null && cardPwd != null){
		if(cardNo.value.length<5){
			mui.toast("请输入充值卡卡号");
			return;
		}
		if(cardPwd.value.length<5){
			mui.toast("请输入充值卡密码");
			return;
		}
		var strVerifyUrl = getAjaxUrl("cuetomer/center/card/elecTicket/cardRecharge.shtml");
		console.log(strVerifyUrl);
		showShade(1);
		$.ajax({
			type: "POST",
			url: strVerifyUrl, 
			data:{
				rcKh:cardNo.value,
				rcFwm:cardPwd.value
			},  
			dataType: "json",
			success:function(result){
				getOpenIdCode(result,function(){
					if(json.success){
				  		alert("充值成功");
				  		//location.href=basePath+"lmxyApp/myaccount.html";
				  		location.href="myaccount.html";
						 /*cardNo.value = "";
						 cardPwd.value = "";
						 strVerifyUrl = "${basePath}cuetomer/center/account/index.shtml?openid=" + global_openid;
	                 	var i = $.layer({
							area : ['auto','auto'],
							dialog : {
								msg:"充值成功，可至个人中心查看余额!",
								btns : 2, 
								type : 4,
								btn : ["查看余额","继续充值"],
								yes : function(){
									layer.close(i);
									window.location.href= strVerifyUrl;
								},
								no : function(){
									//重置表单信息 
									layer.close(i);
								}
							}
						 });*/
					}else{
						alert("充值失败，请核对充值卡卡号及密码");
					}
				});	 
			},error : function(result){ 
			}
	    }); 
	}
}
function login(){
	$.ajax({
		type: "POST",
		url: getAjaxUrl("v2/cuetomer/binding_save.shtml"), 
		data: "cuetomerInfo.ciYhm="+$("#userName").val()+"&cuetomerInfo.ciMm="+$("#password").val(),  
		dataType: "text",
		success:function(result){
			getOpenIdCode(result,function(){
				if(result == "success"){
					if(getParameter("redirect_uri") == null || getParameter("redirect_uri").trim() == ""){
						//location.href=basePath+"lmxyApp/index.html";
						location.href="index.html";
					}else{
						location.href=getParameter("redirect_uri");
					}
				}
			});
		},error : function(result){ 
		}
    }); 
}