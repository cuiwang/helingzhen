
var baolock = 1;
var baonum = 1;
var myScroll = null;

function showLoader(msg) {
    $("#loading").show();
    $(".bao_loading").html(msg).show();
}

function hideLoader()
{
    $("#loading").hide();
    $(".bao_loading").hide();
}

function dingwei(page, lat, lng) {
    // page = page.replace('llaatt', lat);
    // page = page.replace('llnngg', lng);
    $.get(page, {lat:lat,lng:lng},function (data) {
    }, 'html');
}
/* 公用 */

$(function () {

    if ($('#search-bar').length > 0)
    {
        $('#search-bar li').width(100 / $('#search-bar li').length + '%');
        $('.page-center-box').css('top', '0.9rem');
    }
    if ($('#tab-bar').length > 0)
    {
        $('.page-center-box').css('top', '1rem');
    }
    if ($('footer').length == 0)
    {
        $('.page-center-box').css('bottom', 0);
    }

});

function loaddata(page, obj, sc) {
    // var link = page.replace('0000', baonum);
    var link = page;
    showLoader('正在加载中....');

    $.get(link, function (data) {
        if (data != 0) {
            obj.append(data);
        }
        baolock = 0;
        hideLoader();
    }, 'html');
    if (sc === true) {
        $(window).scroll(function () {
            if (!baolock && $(window).scrollTop() == $(document).height() - $(window).height()) {
                baolock = 1;
                baonum++;
                // var link = page.replace('0000', baonum);
                var link = page+'&page='+baonum;
                // alert(link);return false;
                showLoader('正在加载中....');
                $.get(link, function (data) {
                    if (data != 0) {
                        obj.append(data);
                    }
                    baolock = 0;
                    hideLoader();
                }, 'html');
            }
        });
    }
	
	
}