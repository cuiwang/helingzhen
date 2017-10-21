/**
 * 打开分享遮罩层1:分享首页
 */
function openShareMask1() {
	shareType=1;
	$('#maskEdit').text('就可以分享到朋友圈，成功后立即多5次抽奖呢。不勇敢点，永远不知道好运什么时候临幸自己。');
	wxImgUrl = imgUrl1;
	wxLink = lineLink1;
	wxTitle = descContent1;
	document.getElementById('shareMask').style.display = "block";
}
/**
 * 打开分享遮罩层2：分享战绩
 */
function openShareMask2() {
	shareType=2;
	$('#maskEdit').text('就可以分享到朋友圈，告诉大家您今天的手气吧！');
	wxImgUrl = imgUrl2;
	wxLink = lineLink2;
	wxTitle = descContent2;
	document.getElementById('shareMask').style.display = "block";
}
/**
 * 关闭分享遮罩层
 */
function closeShareMask() {
	document.getElementById('shareMask').style.display = "none";
}
/**
 * 编辑电话号码
 */
function editPhone(){
	$('#phonePopup').show();
	var phone = $('#phone').text();
	$('input[name="phone"]').val(phone);
}
/**
 * 设置电话号码
 */
function setPhone() {
	var phone = $('input[name="phone"]').val();
	if (!/^1\d{10}$/.test(phone)) {
		alert("请输入正确的电话号码");
		return false;
	}
	$.post(_webApp + '/activity/rxhk/setPhone', {
		phone : phone
	}, function(data) {
		if (data.result == 'success') {
			$('#phone').text(phone);
			alert("设置成功");
		} else {
			alert(data.message);
		}
		$('#phonePopup').hide();
	}, 'json');
}