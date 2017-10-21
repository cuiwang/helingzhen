$(function(){
    //名片二维码
    showQrcode();
});
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
var showQrcode = function (){
    var ewmlogo = $('#vphoto').attr('src');
    $('.js-ewmtab li').on('click', function () {
        $('.ewm-thumbnail').attr("src","/app/themes/vcard03/static/images/noimage.jpg");
        var _this = $(this);
        var text = location.href;
        if(_this.attr('data-info') == "vaddbook"){
            text = "BEGIN:VCARD\r\nVERSION:3.0";
            if($('#vname').val())
                text += "\r\nFN:"+ $('#vname').val();
            if($('#vposition').html())
                text += "\r\nTITLE:"+ $('#vposition').val();
            if($('#vcompany').val())
                text += "\r\nORG:"+ $('#vcompany').val();
            if($('#vmobphone').val())
                text += "\r\nTEL;CELL,VOICE:"+ $('#vmobphone').val();
            if($('#vtelphone').val())
                text += "\r\nTEL;WORK;VOICE:"+ $('#vtelphone').val();
            if($('#vwebsite').val())
                text += "\r\nURL;WORK:"+ $('#vwebsite').val();
            if($('#vemail').val())
                text += "\r\nEMAIL;INTERNET,HOME:"+ $('#vemail').val();
            if($('#vaddress').val())
                text += "\r\nADR;WORK:;;"+ $('#vaddress').val();
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
    $('#qrcode_icon').on('click', function () {
        var ewmimg = $('.ewm-thumbnail').attr("src");
        if(ewmimg){$('.js-ewmform').show();return;}
        qrcode(ewmlogo, location.href);
        $('.js-ewmform').show();
    })
}