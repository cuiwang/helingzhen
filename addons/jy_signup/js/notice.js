function showNoticeFunc(str){
    var newNode = document.createElement("div");
    newNode.className = "alertContent";
    var objStr = '<div class="alertContent-con"><p>'+str+'</p><span class="latecolorbg" id="noticeSure" onclick="hideNoticeFunc(this)">确定</span></div>';
    newNode.innerHTML = objStr;
    document.body.appendChild(newNode);
}
function hideNoticeFunc(obj){
    var objPar = document.getElementById("noticeSure").parentNode.parentNode;
    document.body.removeChild(objPar);
}

//判断手机格式
function validatemobile(mobile) {
    var myreg = /^1[345789]\d{9}$/;
    if(mobile.length==0) {
        showNoticeFunc('请输入手机号码！');
        return false;
    }
    if(mobile.length!=11 || !myreg.test(mobile)) {
        showNoticeFunc('请输入有效的手机号码！');
        return false;
    }
    return true;
}
//判断邮箱格式
function IsEmail(email) {
    var str = email.trim();
    if(str.length == 0) {
        showNoticeFunc('请输入邮箱！');
        return false;
    }
    reg=/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
    if(!reg.test(str)){
        showNoticeFunc("对不起，您输入的邮箱格式不正确!");
        return false;
    }
    return true;
}
//判断两次密码输入是否一致
function check_repsw(val1,val2){
	 if(val1 != val2){
	 	return false;
	 }
	 return true;
}