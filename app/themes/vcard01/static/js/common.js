//PC端跳转
function IsPC() {
    var userAgentInfo = navigator.userAgent;
    var Agents = new Array("Android", "iPhone", "SymbianOS", "Windows Phone", "iPad", "iPod");
    var flag = true;
    for (var v = 0; v < Agents.length; v++) {
        if (userAgentInfo.indexOf(Agents[v]) > 0) {
            flag = false;
            break;
        }
    }
    return flag;
}
// 图片延迟替换函数
function lazy_img() {
    var lazy = $('.lazy-bk');
    lazy.each(function () {
        var self = $(this),
            srcImg = self.attr('data-bk');
        $('<img />').on('load', function () {
            if (self.is('img')) {
                self.attr('src', srcImg)
            } else {
                self.css({
                    'background-image': 'url(' + srcImg + ')',
                    'background-size': 'cover'
                })
            }
        }).attr("src", srcImg);
        self.removeClass('lazy-bk');
    })
}
function utf16to8(str) {
    var out, i, len, c;
    out = "";
    len = str.length;
    for (i = 0; i < len; i++) {
        c = str.charCodeAt(i);
        if ((c >= 0x0001) && (c <= 0x007F)) {
            out += str.charAt(i);
        } else if (c > 0x07FF) {
            out += String.fromCharCode(0xE0 | ((c >> 12) & 0x0F));
            out += String.fromCharCode(0x80 | ((c >> 6) & 0x3F));
            out += String.fromCharCode(0x80 | ((c >> 0) & 0x3F));
        } else {
            out += String.fromCharCode(0xC0 | ((c >> 6) & 0x1F));
            out += String.fromCharCode(0x80 | ((c >> 0) & 0x3F));
        }
    }
    return out;
}
//名片二维码
var qrcode = function (logo, txt) {
    $('#js-ewmform-summary').empty();
    //if (logo) txts = "http://qr.liantu.com/api.php?w=246&m=7&logo=" + encodeURIComponent(logo) + "&text=" + encodeURIComponent(txt);
    //else
        txts = "http://qr.liantu.com/api.php?w=246&m=7&text=" + encodeURIComponent(txt);
    $('<img />').on('load', function () {
        $('.js-ewmform-summary').find('img').attr('src', txts)
    }).attr("src", txts);
}
function showQrcode(){
    var ewmlogo = $('#vphoto').attr('src');
    $('.js-ewmtab li').on('click', function () {
        $('.ewm-thumbnail').attr("src","/app/themes/vcard01/static/images/noimage.jpg");
        var _this = $(this);
        var text = location.href;
		if(_this.attr('data-info') == "vaddbook"){
            text = "BEGIN:VCARD\r\nVERSION:3.0";
            if($('#vname').html())
                text += "\r\nFN:"+ $('#vname').html();
            if($('#vposition').html())
                text += "\r\nTITLE:"+ $('#vposition').html();
            if($('#vcompany').html())
                text += "\r\nORG:"+ $('#vcompany').html();
            if($('#vmobphone').html())
                text += "\r\nTEL;CELL,VOICE:"+ $('#vmobphone').html();
            if($('#vtelphone').html())
                text += "\r\nTEL;WORK;VOICE:"+ $('#vtelphone').html();
            if($('#vwebsite').html())
                text += "\r\nURL;WORK:"+ $('#vwebsite').html();
            if($('#vemail').html())
                text += "\r\nEMAIL;INTERNET,HOME:"+ $('#vemail').html();
            if($('#vaddress').html())
                text += "\r\nADR;WORK:;;"+ $('#vaddress').html();
            text += "\r\nEND:VCARD";
		}
        var index = _this.index();
        _this.addClass('cur').siblings('li').removeClass('cur');
        $('.ewmform-title li').eq(index).show().siblings('li').hide();
        $('.js-ewmform-des li').eq(index).show().siblings('li').hide();
        //qrcode(ewmlogo, utf16to8(info));
        qrcode(ewmlogo, text);
    })
    $('.js-ewmClose').on('click', function () {
        $('.js-ewmform').hide();
    })
    $('.qrcode_icon').on('click', function () {
        var ewmimg = $('.ewm-thumbnail').attr("src");
        if(ewmimg){$('.js-ewmform').show();return;}
        qrcode(ewmlogo, location.href);
        $('.js-ewmform').show();
    })
}
//个人微信二维码
function showWxQrcode() {
    var $showWxQrcode = $(".js_showWxqrcode");
    var $wxewm = $(".js-wxewm");
    $showWxQrcode.on('click', function () {
        $wxewm.show();
    });
    $(".js-wxewmClose").click(function () {
        $wxewm.hide();
    });
}
//留言模块
function pub_message() {
    $("#btn_submit").on("click", function () {
        var url = $("#form1").attr('action');
        var name = $("#username").val();
        var mobile = $("#mobile").val();
        var content = $("#content").val();
        _this = $(this);
        if (name == '') {
            $.tipMessage('请输入您的姓名！', 2, 2000);
            return false;
        }
        if (mobile == '') {
            $.tipMessage('请输入您的手机！', 2, 2000);
            return false;
        }
        if (content == '') {
            $.tipMessage('请输入内容！', 2, 2000);
            return false;
        }
        var data = {
            uname: name,
            tel: mobile,
            msg: content
        };
        $(this).val('正在提交....').attr('disabled', 'disabled');
        $.post(url, data,
            function (data) {
                if (data.status == 1) {
                    $.tipMessage(data.info, 3, 3000);
                    $("#username").val('');
                    $("#mobile").val('');
                    $("#content").val('');
                } else {
                    $.tipMessage(data.info, 2, 2000);
                }
                _this.val('提交').removeAttr('disabled');
            }, "json");
    });
}
$(function () {
    if (IsPC()) {
        //window.location.href = "/home.html";
    }
    //名片二维码
    showQrcode();
    //微信二维码
    showWxQrcode();
    //留言功能
    pub_message();
})

	