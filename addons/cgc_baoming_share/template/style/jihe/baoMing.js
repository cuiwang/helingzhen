$(document).ready(function() {
	
    $(".erweimaBtn").click(function(event) {
       $(this).parent().parent().css('display', 'none');
    });
    $(".mask").click(function(event) {
       $(this).css('display', 'none');
    });
    return;
 //手机号
 //获取焦点
$(".phone").focus(function (event) {
	if ($(this).val() == "请输入手机号参与报名") {
    	$(this).val("").css({"color": "#000","font-size":"1.2rem"});
    }
});
  //失去焦点
$(".phone").blur(function (event) {
    if ($(this).val() == "") {
       $(this).val("请输入手机号参与报名").css({"color":"#d0d0d0","font-size":"1rem"});
   		return;
	}
    if (!$(".phone").val().match(/^[1][3,4,5,7,8][0-9]{9}$/)) {
        $(this).val("请输入手机号参与报名").css({"color":"#d0d0d0","font-size":"1rem"});
        return;
    }
});

})



