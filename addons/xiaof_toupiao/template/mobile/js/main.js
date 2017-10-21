var phonereg = /^1([38]\d|4[57]|5[0-35-9]|7[06-8]|8[89])\d{8}$/;
var smslock = true;

function xfdialog(content, isclose) {

	if (isclose) {
		$("#popup-close").show();
	} else {
		$("#popup-close").hide();
	}
	$(".popup-container").html(content);
	$("#dialog").addClass("is-visible");
}

var wait = 60;

function smstime(This) {
	if (wait == 0) {
		This.html("获取验证码");
		smslock = true;
		wait = 60;
	} else {
		This.html("重新发送(" + wait + ")");
		wait--;
		setTimeout(function() {
			smstime(This)
		}, 1000);
	}
}

function getLocation(id, This) {
	wx.ready(function() {
		wx.getLocation({
			success: function(res) {
				$.post(window.sysinfo.siteroot + "app/index.php?c=entry&do=verifyLocation&m=xiaof_toupiao&i=" + window.sysinfo.uniacid, {
					latitude: res.latitude,
					longitude: res.longitude
				}, function(data) {
					var result = new Function('return' + data)();
					if (result.errno == 0) {
						vote(id, This);
					} else {
						xfdialog(result.message, true);
					}
				});
			},
			fail: function() {
				xfdialog('地理位置获取失败', true);
			},
			cancel: function() {
				xfdialog('放弃定位', true);
			}
		});
	});
}

function vote(id, This, parameter) {
	if(!arguments[2]){
		parameter = "";
	}
	var voteurl = window.sysinfo.siteroot + "app/index.php?c=entry&do=vote&m=xiaof_toupiao&i=" + window.sysinfo.uniacid + "&type=good&id=" + id + parameter;
	$.get(voteurl, function(data) {
		var result = new Function('return' + data)();
		if (result.errno == 0) {
			var num = result.message.match(/投了([0-9])票/i);
			var addnum = parseInt(num[1]);
			This.html(parseInt(This.html()) + addnum);
		}
		if (result.errno == 104) {
			xfdialog("活动仅限本地区参与投票", true);
			return;
		}
		if (result.errno == 115) {
			var gpshtml = '<div class="gpsmsg" style="text-align: center;">未进行GPS定位，定位后点确定继续<br/><span class="complete-button complete button-bg-color getlocation">点击定位</span></div>';
			xfdialog(gpshtml, true);
			$(".getlocation").click(function() {
				getLocation(id, This);
			});
			return;
		}
		if (result.errno == 111) {
			var xphone;
			var lock = false;
			var smshtml = '<h3>手机号验证</h3><div class="verifycode"><p>手机号：<input type="tel" id="xphone" name="phone"></p><p>验证码：<input type="number" id="xverifycode" name="v"><span class="getsms">获取验证码</span></p></div><div class="verifycode-button"><span class="complete-button complete button-bg-color">确定</span><span class="complete" onclick=\'$("#dialog").removeClass("is-visible");\'>取消</span></div>';
			xfdialog(smshtml, true);
			$(".getsms").click(function() {
				xphone = $("#xphone").val();
				if (!phonereg.test(xphone)) {
					alert("不是正确手机号");
				} else {
					if (smslock == true) {
						smslock = false;
						smstime($(this));
						$.get(window.sysinfo.siteroot + "app/index.php?c=entry&do=getsms&m=xiaof_toupiao&i=" + window.sysinfo.uniacid + "&phone=" + xphone, function(data) {
							var result = new Function('return' + data)();
							if (result.errno != 0) {
								alert(result.message);
							} else {
								lock = true;
							}
						});
					}
				}
			});
			$(".complete-button").click(function() {
				if (lock) {
					var xverifycode = $("#xverifycode").val();
					if (xverifycode.length != 4) {
						alert("验证码格式错误");
					} else {
						vote(id, This, "&verifycode=" + xverifycode + "&phone=" + xphone);
					}
				} else {
					alert("请先获取验证码");
				}
			});
			return;
		}
		xfdialog(result.message, true);
		if (result.message.indexOf("acid-lists")) {
			new Swiper('.acid-lists', {
				scrollbar: '.swiper-scrollbar',
				autoplay: 3000,
				scrollbarHide: true,
				slidesPerView: 1
			});
		}
	});
}