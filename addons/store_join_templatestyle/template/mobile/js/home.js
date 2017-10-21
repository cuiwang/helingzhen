/**
 * Created by Administrator on 2016/12/9.
 */
var reg = /^#([0-9a-fA-f]{3}|[0-9a-fA-f]{6})$/;
/*16进制颜色转为RGB格式*/
String.prototype.colorRgb = function () {
    var sColor = this.toLowerCase();
    if (sColor && reg.test(sColor)) {
        if (sColor.length === 4) {
            var sColorNew = "#";
            for (var i = 1; i < 4; i += 1) {
                sColorNew += sColor.slice(i, i + 1).concat(sColor.slice(i, i + 1));
            }
            sColor = sColorNew;
        }
        //处理六位的颜色值
        var sColorChange = [];
        for (var i = 1; i < 7; i += 2) {
            sColorChange.push(parseInt("0x" + sColor.slice(i, i + 2)));
        }
        return "rgba(" + sColorChange.join(",") + ",.8)";
    } else {
        return sColor;
    }
};

////名片二维码
//var qrcode = function (logo, txt) {
//    $('#js-ewmform-summary').empty();
//    //if (logo) txts = "http://qr.liantu.com/api.php?w=246&m=7&logo=" + encodeURIComponent(logo) + "&text=" + encodeURIComponent(txt);
//    //else
//    txts = "http://qr.liantu.com/api.php?w=246&m=7&text=" + encodeURIComponent(txt);
//    $('<img />').on('load', function () {
//        $('.js-ewmform-summary').find('img').attr('src', txts)
//    }).attr("src", txts);
//}
//var showQrcode = function (){
//    var ewmlogo = $('#vphoto').attr('src');
//    $('.js-ewmtab li').on('click', function () {
//        $('.ewm-thumbnail').attr("src","/app/themes/vcard03/static/images/noimage.jpg");
//        var _this = $(this);
//        var text = location.href;
//        if(_this.attr('data-info') == "vaddbook"){
//            text = "BEGIN:VCARD\r\nVERSION:3.0";
//            if($('#vname').val())
//                text += "\r\nFN:"+ $('#vname').val();
//            if($('#vposition').html())
//                text += "\r\nTITLE:"+ $('#vposition').val();
//            if($('#vcompany').val())
//                text += "\r\nORG:"+ $('#vcompany').val();
//            if($('#vmobphone').val())
//                text += "\r\nTEL;CELL,VOICE:"+ $('#vmobphone').val();
//            if($('#vtelphone').val())
//                text += "\r\nTEL;WORK;VOICE:"+ $('#vtelphone').val();
//            if($('#vwebsite').val())
//                text += "\r\nURL;WORK:"+ $('#vwebsite').val();
//            if($('#vemail').val())
//                text += "\r\nEMAIL;INTERNET,HOME:"+ $('#vemail').val();
//            if($('#vaddress').val())
//                text += "\r\nADR;WORK:;;"+ $('#vaddress').val();
//            text += "\r\nEND:VCARD";
//        }
//        var index = _this.index();
//        _this.addClass('cur').siblings('li').removeClass('cur');
//        $('.ewmform-title li').eq(index).show().siblings('li').hide();
//        $('.js-ewmform-des li').eq(index).show().siblings('li').hide();
//        //qrcode(ewmlogo, utf16to8(info));
//        qrcode(ewmlogo, text);
//    })
//    $('.js-ewmClose').on('click', function () {
//        $('.js-ewmform').hide();
//    })
//    $('#qrcode_icon').on('click', function () {
//        var ewmimg = $('.ewm-thumbnail').attr("src");
//        if(ewmimg){$('.js-ewmform').show();return;}
//        qrcode(ewmlogo, location.href);
//        $('.js-ewmform').show();
//    })
//}
/*留言模块*/
var lySubmit = function(){
    $(".lySp").text("");
    $(".okLy").text("");
    var url = $("#lyurl").val();
    var lytel = $(".lphone").val();
    if ($(".lyName").val() == "请输入姓名") {
        $(".lySp:eq(0)").text("姓名不能为空哦！");
        $(".lyName").focus();
        return false;
    } else if (lytel == "请输入联系电话") {
        $(".lySp:eq(1)").text("手机号码不能为空哦！");
        $(".lyTel").focus();
        return false;
    } else if (isNaN(lytel) && !is_HK_telephone(lytel)) {
        $(".lySp:eq(1)").text("手机号码有误哦！");
        $(".lyTel").focus();
        return false;
    } else if (lytel.length != 11 && !is_HK_telephone(lytel)) {
        $(".lySp:eq(1)").text("手机号码有误哦！");
        $(".lyTel").focus();
        return false;
    } else if ($("#lyArea").val() == "请输入留言内容" || $("#lyArea").val() == "") {
        $(".lySp:eq(2)").text("请输入留言内容！");
        $("#lyArea").focus();
        return false;
    }
}

/**地图模块**/
var baidumapurl = $("#baidumap").attr('data-url');
function getQueryString(name){
    var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
    var r = baidumapurl.substr(32).match(reg);
    if(r!=null)return  unescape(r[2]); return null;
}
var getLoc = function(l){
    var loc = getQueryString('location');
    return (l =='lat' ? loc.split(',')[0] : loc.split(',')[1]);
}
var bmap = {
    'option' : {
        'lock' : false,
        'container' : 'baidumap',
        'infoWindow' : {'width' : 250, 'height' : 100, 'title' : getQueryString('title')},
        'point' : {'lng' : getLoc('lng'), 'lat' : getLoc('lat')}
    },
    'init' : function(option) {
        var $this = this;
        $this.option = $.extend({},$this.option,option);
        $this.option.defaultPoint = new BMap.Point($this.option.point.lng, $this.option.point.lat);
        $this.bgeo = new BMap.Geocoder();
        $this.bmap = new BMap.Map($this.option.container);
        $this.bmap.centerAndZoom($this.option.defaultPoint, 15);
        $this.bmap.enableScrollWheelZoom();
        $this.bmap.enableDragging();
        $this.bmap.enableContinuousZoom();
        $this.bmap.addControl(new BMap.NavigationControl());
        $this.bmap.addControl(new BMap.OverviewMapControl());
        //添加标注
        var myicon = new BMap.Icon("../addons/store_business_card/template/mobile/images/ico_marker.png", new BMap.Size(36,36));
        $this.marker = new BMap.Marker($this.option.defaultPoint,{icon:myicon});
        $this.marker.enableDragging();
        $this.bmap.addOverlay($this.marker);
        $this.marker.setAnimation(BMAP_ANIMATION_BOUNCE);//跳动的动画
        //添加文本
        //var opts = {
        //    position : $this.option.defaultPoint,    // 指定文本标注所在的地理位置
        //    offset   : new BMap.Size(-40, -50)    //设置文本偏移量
        //}
        //$this.label = new BMap.Label(getQueryString('title'),opts);
        //$this.label.setStyle({
        //    color : "red",
        //    fontSize : "12px",
        //    height : "20px",
        //    lineHeight : "20px",
        //    fontFamily:"微软雅黑"
        //});
        //$this.bmap.addOverlay($this.label);
        //添加导航
        $this.cr = new BMap.CopyrightControl({anchor: BMAP_ANCHOR_TOP_RIGHT});   //设置版权控件位置
        $this.bmap.addControl($this.cr); //添加版权控件

        var bs = $this.bmap.getBounds();   //返回地图可视区域
        $this.cr.addCopyright({id: 1, content: "<a href='"+ baidumapurl +"' style='font-size:16px;color: #333;background:#FFF;border: 1px solid RGBA(0,0,0,.4);padding: 3px 5px;line-height: 38px;width: 80px;height: 40px;border-radius: 8px;'>导航</a>", bounds: bs});
    },
    'setMarkerCenter' : function() {
        var $this = this;
        var center = $this.bmap.getCenter();
        $this.marker.setPosition(new BMap.Point(center.lng, center.lat));
        $this.showPointValue();
        $this.showAddress();
    }
};
$(function(){
    //关注背景色
    var scolor = $("#js-subscribe").attr('data-color');
    scolor = scolor ? scolor : "#487641";
    $("#js-subscribe").css("background-color", scolor.colorRgb());
    //背景色
    var sHex = "{if empty($_W['styles']['indexbgcolor'])}#01011f{else}{$_W['styles']['indexbgcolor']}{/if}";
    var sRgbColor = sHex.colorRgb();//转为RGB颜色值的方法
    $("#container").css("background-color", sRgbColor);

    var option = {};
    option = {'point' : {'lng' : getLoc('lng'), 'lat' : getLoc('lat')}}
    bmap.init();
    //banner
    //$('#banner, #banner .item').height($(window).height());
    var arr = ["请输入姓名", "请输入联系电话"];
    $(".txt").each(function (index, el) {
        $(this).focus(function () {
            if ($(this).val() == arr[index]) {
                $(this).val("");
            }
            $(this).css({ "color": "#343434" });
        }).blur(function () {
            if ($(this).val() == "") {
                $(this).val(arr[index]);
                $(this).css({ "color": "#B2B2B2" });
            }
        })

    });
    $(".btnLy").click(function () {
        lySubmit();
    });
    //名片二维码
//    showQrcode();
});