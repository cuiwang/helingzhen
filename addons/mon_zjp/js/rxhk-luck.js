$(function() {
    // 初始化页面元素
    $('#topbg').height($(document).height());
    $('#armArea').height($(window).height() - 300);
    $('.popup-bg').height($(window).height());
    // 显示我的奖品
    $('.my-prize').click(function() {
        $('.popup-prize').css('display', 'block');
    })
    // 点击事件：切换手臂样式
    $('.switch-btn').click(function() {
        switchArm();
    })
    // 初始化手臂事件
    initArmEvent();
});

/**
 * 分享成功
 */
function sharefinish() {
    $.post(_webApp + '/activity/rxhk/shareRecord', {}, function(data) {
        closeShareMask();
        if (data.result == 'success') {
            isShare = 1;
            surplusCount = data.surplusCount;
            playCount = data.playCount;
            shareSuccess();
        } else {
            alert(data.message);
        }
    }, 'json');
}

/**
 * 初始化手臂事件
 */
function initArmEvent() {
    // 手臂
    var arm = document.getElementById('arm');
    // 手指
    var finger = document.getElementById('finger');
    // 抽奖结果弹出框
    var luckPopup = document.getElementById('luckPopup');
    // 抽奖箱
    var luckBox = document.getElementById('luckBox');
    // 抽奖标语提示
    var luckTips = document.getElementById('luckTips');
    // 手臂最大下拉高度
    var maxDragH = document.body.clientHeight - 500;

    arm.addEventListener('touchstart', function(event) {
        event.preventDefault();// 阻止其他事件
        // 如果这个元素的位置内只有一个手指的话
        if (event.targetTouches.length == 1) {
            var touch = event.targetTouches[0]; // 把元素放在手指所在的位置
            startY = touch.pageY;
        }
    }, false);

    arm.addEventListener('touchmove', function(event) {
        event.preventDefault();// 阻止其他事件
        if (event.targetTouches.length == 1) {
            var touch = event.targetTouches[0]; // 把元素放在手指所在的位置
            // 手臂被拖拉高度
            armDragH = touch.pageY - startY;
            if (armDragH > maxDragH) {
                arm.className = "arm arm-shake";
            } else {
                arm.style.webkitTransform = 'translate(' + 0 + 'px, '
                + armDragH + 'px)';
            }
            luckBox.className = "box-animation";
            finger.className = "";
            $('#luckTips').hide();
        }
    }, false);

    arm.addEventListener('touchend', function(event) {
        event.preventDefault();// 阻止其他事件
        if (!arm)
            return;
        if (armDragH > maxDragH) {
            // 延迟执行抽奖
            setTimeout('lottery()', 1000);
        } else {
            resetArm();
        }

    }, false);
}
/**
 * 复位手臂状态
 */
function resetArm() {
    arm.style.webkitTransform = 'translate(0px, 0px)';
    arm.className = "arm";
    luckBox.className = "box-front";
    finger.className = "finger";
    $('#luckTips').show();
}
/**
 * 切换手臂样式
 */
function switchArm() {


    weixinShare(descContent, imgUrl);
//	var armSize = 5;
//	var i = Math.floor(Math.random() * armSize);
//	var cIndex = $('#arm').attr('data-index');
//	if (i == cIndex) {
//		switchArm();
//	} else {
//		$('#arm').attr('data-index', i).attr(
//				'style',
//				'background-image:url(' + _webApp
//						+ '/images/page/activity/rxhk/arm-bg-' + i + '.png);')
//		$('#armImg').attr(
//				'style',
//				'background-image:url(' + _webApp
//						+ '/images/page/activity/rxhk/arm-' + i + '.png);')
//	}
}
/**
 * 抽奖
 */
function lottery() {
    if (parseInt(surplusCount)) {


        var datastr='{"result":"success","hasPrize":false,"playCount":2,"surplusCount":0,"name":"","isShare":0}';

       var data= eval('(' + datastr + ')');
        cj(data);


          /*
        $.post(_webApp + '/activity/rxhk/lottery', {},
            function(data) {
                if (data.result == 'success') {
                    isShare = data.isShare;
                    surplusCount = data.surplusCount;
                    playCount = data.playCount;
                    phone = data.phone;
                    if (data.hasPrize) {
                        // 有奖品
                        $('#prizeName').html(data.name);
                        $('#prizeImg').attr("src", data.image);
                        $('#luckText').html(
                            '你还有<span>' + data.surplusCount
                            + '</span>次抽奖机会，继续加油！')
                        if (surplusCount > 0) {
                            $('#luckPopupBtn').html("继续抽奖").attr(
                                'href',
                                'javascript:closeLuckPopup();');
                        } else {
                            if (parseInt(isShare)) {
                                $('#luckText').html(
                                    '您的抽奖次数已经用完，谢谢参与！')
                                $('#luckPopupBtn')
                                    .html("我的奖品")
                                    .attr(
                                    'href',
                                    _webApp
                                    + '/activity/rxhk/myprize?adfrom='
                                    + adfrom);
                            } else {
                                $('#luckText')
                                    .html(
                                    '您的抽奖次数已经用完，分享到朋友圈可以再获得<span>5</span>次抽奖机会！')
                                $('#luckPopupBtn')
                                    .html("再获得5次机会")
                                    .attr('href',
                                    'javascript:openShareMask();');
                                weixinShare(descContent, imgUrl);
                            }
                        }
                        openLuckPopup();
                    } else {
                        // 无奖品
                        $('#promptHead')
                            .html(
                            '<img src="'
                            + _webApp
                            + '/images/page/activity/rxhk/noprize.png" style="margin: 0 auto;">啥也没抽中');
                        if (surplusCount > 0) {
                            $('#promptWord')
                                .html(
                                '<p>你还有<span id="surplusCount">'
                                + data.surplusCount
                                + '</span>次抽奖机会，继续加油！</p>');
                            $('#prompBtn')
                                .html("继续抽奖")
                                .attr('href',
                                'javascript:closePromptPopup();');
                        } else {
                            if (parseInt(isShare)) {
                                $('#promptWord').html(
                                    "您的抽奖次数已经用完，谢谢参与");
                                $('#prompBtn')
                                    .html("我的奖品")
                                    .attr(
                                    'href',
                                    _webApp
                                    + '/activity/rxhk/myprize?adfrom='
                                    + adfrom);
                            } else {
                                $('#promptWord')
                                    .html(
                                    "抽奖次数已用完，分享可获取<span>5</span>次抽奖机会");
                                $('#prompBtn')
                                    .html("分享")
                                    .attr('href',
                                    'javascript:openShareMask();');
                                weixinShare(descContent, imgUrl);
                            }
                        }
                        openPromptPopup();
                    }
                } else if (data.result == 'nocount') {
                    isShare = data.isShare;
                    surplusCount = data.surplusCount;
                    playCount = data.playCount;
                    noCountPopup();
                } else {
                    alert(data.message);
                }
            }, 'json');*/
    } else {

        noCountPopup();
    }


}


function cj(data){


    if (data.result == 'success') {
        isShare = data.isShare;
        surplusCount = data.surplusCount;
        playCount = data.playCount;
        phone = data.phone;
        if (data.hasPrize) {
            // 有奖品
            $('#prizeName').html(data.name);
            $('#prizeImg').attr("src", data.image);
            $('#luckText').html(
                '你还有<span>' + data.surplusCount
                + '</span>次抽奖机会，继续加油！')
            if (surplusCount > 0) {
                $('#luckPopupBtn').html("继续抽奖").attr(
                    'href',
                    'javascript:closeLuckPopup();');
            } else {
                if (parseInt(isShare)) {
                    $('#luckText').html(
                        '您的抽奖次数已经用完，谢谢参与！')
                    $('#luckPopupBtn')
                        .html("我的奖品")
                        .attr(
                        'href',
                        _webApp
                        + '/activity/rxhk/myprize?adfrom='
                        + adfrom);
                } else {
                    $('#luckText')
                        .html(
                        '您的抽奖次数已经用完，分享到朋友圈可以再获得<span>5</span>次抽奖机会！')
                    $('#luckPopupBtn')
                        .html("再获得5次机会")
                        .attr('href',
                        'javascript:openShareMask();');
                   // weixinShare(descContent, imgUrl);
                }
            }
            openLuckPopup();
        } else {
            // 无奖品
            $('#promptHead')
                .html(
                '<img src="'
                + _webApp
                + '/images/noprize.png" style="margin: 0 auto;">啥也没抽中');
				
            if (surplusCount > 0) {
                $('#promptWord')
                    .html(
                    '<p>你还有<span id="surplusCount">'
                    + data.surplusCount
                    + '</span>次抽奖机会，继续加油！</p>');
                $('#prompBtn')
                    .html("继续抽奖")
                    .attr('href',
                    'javascript:closePromptPopup();');
            } else {
				
                if (parseInt(isShare)) {
					alert("1");
					return 
                    $('#promptWord').html(
                        "您的抽奖次数已经用完，谢谢参与");
                    $('#prompBtn')
                        .html("我的奖品")
                        .attr(
                        'href',
                        _webApp
                        + '/activity/rxhk/myprize?adfrom='
                        + adfrom);
                } else {
				
				
                    $('#promptWord')
                        .html(
                        "抽奖次数已用完，分享可获取<span>5</span>次抽奖机会");
					
						
                    $('#prompBtn')
                        .html("分享")
                        .attr('href',
                        'javascript:openShareMask();');
						
                    //weixinShare(descContent, imgUrl);
                }
            }
            openPromptPopup();
        }
    } else if (data.result == 'nocount') {
        isShare = data.isShare;
        surplusCount = data.surplusCount;
        playCount = data.playCount;
        noCountPopup();
    } else {
        alert(data.message);
    }






}


/**
 * 弹出无次数的提示
 */
function noCountPopup() {
    if (parseInt(isShare)) {
        $('#promptHead').html("谢谢参与");
        $('#promptWord').html("您的抽奖次数已经用完");
        $('#prompBtn').html("我的奖品").attr('href',
            _webApp + '/activity/rxhk/myprize?adfrom=' + adfrom);
        openPromptPopup();
    } else {
        $('#promptHead').html("分享可获取<span>5</span>次抽奖机会");
        $('#promptWord').html("您的抽奖次数已经用完,分享可获取<span>5</span>次抽奖机会");
        $('#prompBtn').html("分享").attr('href', 'javascript:openShareMask();');
        weixinShare(descContent, imgUrl);
        openPromptPopup();
    }
}
// 随机产品宣导提示
var tipsArr = [];
tipsArr.push('好慷宅洁士居家保洁都是4小时标准服务时间');
tipsArr.push('每天只需要花费19元就可购买星级家家务包年A6套餐');
tipsArr.push('关注微信公众号“好慷家庭服务中心”预订更优惠呢');
tipsArr.push('宅洁士居家保洁4小时-139元即可“焕”个新家');
tipsArr.push('需要固定服务人员建议购买星级家·家务包年产品');
tipsArr.push('七色保洁布从概念到落地，首创是我们好慷哦');
tipsArr.push('针对服务的安全性，好慷的家政服务全程都购买了保险哦');
tipsArr.push('星级家”由经过培训的专职员工、使用专业工具，提供有计划、有标准的家务服务');
tipsArr.push('好慷专用的日式保洁工具组，应用多项专利技术，实现保洁服务0死角');
tipsArr.push('好慷的七色保洁布：分区域的保洁，不用颜色、材质的，干湿分离、避免交叉污染');
tipsArr.push('好慷每一位服务人员都经过公安系统备案，都经过专业体检，有健康证');
tipsArr.push('全国统一的呼叫中心平台，提供主动式服务，一站式服务为您搞定所有售后问题');
tipsArr.push('补时保障：家政服务人员末按约定服务时间上门服务，迟到1小时全赔付');
tipsArr.push('幸运抽到保洁券的您，要抓紧使用哦！');
tipsArr.push('年底是订保洁旺季，提前预订有备无患呢。');


function randomTips() {
    if (tipText.length) {
        $('.tips').attr('data-index', 0).text(tipText);
    } else {
        var n = tipsArr.length;
        var i = Math.floor(Math.random() * n);
        var cIndex = $('.tips').attr('data-index');
        if (i == cIndex) {
            randomTips();
        } else {
            $('.tips').attr('data-index', i).text(tipsArr[i]);
        }
    }
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
            alert("设置成功");
        } else {
            alert(data.message);
        }
        closePhonePopup();
    }, 'json');
}
/**
 * 分享成功
 */
function shareSuccess() {
    $('#promptHead').html("分享成功");
    $('#promptWord').html("恭喜你获得<span>5</span>次抽奖机会，继续加油！");
    $('#prompBtn').html("继续抽奖").attr('href', 'javascript:closePromptPopup();');
    openPromptPopup();
}
/**
 * 打开抽奖结果弹出框
 */
function openLuckPopup() {
    randomTips();
    $('#luckPopup').show();
    resetArm();
    // 随机切换手臂样式
    switchArm();
}
/**
 * 关闭抽奖结果弹出框
 */
function closeLuckPopup() {
    $('#luckPopup').hide();
    if (!phone && playCount >= 4) {
        openPhonePopup();
    }
}
/**
 * 打开提示弹出框
 */
function openPromptPopup() {
    randomTips();
    $('#promptPopup').show();
    resetArm();
    // 随机切换手臂样式
    switchArm();
}
/**
 * 关闭提示弹出框
 */
function closePromptPopup() {
    $('#promptPopup').hide();
    if (!phone && playCount >= 4) {
        openPhonePopup();
    }
}
/**
 * 打开电话弹出框
 */
function openPhonePopup() {
    randomTips();
    $('#phonePopup').show();
}
/**
 * 关闭电话弹出框
 */
function closePhonePopup() {
    $('#phonePopup').hide();
}
/**
 * 打开分享遮罩层
 */
function openShareMask() {
    weixinShare(descContent, imgUrl);
    $('.popup').hide();
    $('#shareMask').show();
}
/**
 * 关闭分享遮罩层
 */
function closeShareMask() {
    sharefinish();
    $('#shareMask').hide();
}
/**
 * 分享
 */
function weixinShare(text, pic) {
    if (text != undefined) {
        document.title = text;
    }
    if (pic != undefined) {
        var shareImg = document.createElement("img")
        shareImg.src = pic;
        shareImg.style.position = "absolute";
        shareImg.style.top = "-1000px";
        document.body.insertBefore(shareImg, document.body.childNodes[0])
    }
}