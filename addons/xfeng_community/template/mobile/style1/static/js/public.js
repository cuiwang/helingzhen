/**
 * Created by zhoufeng on 16/9/17.
 */
var lock = 1;
var page = 1;
var myScroll = null;
function showLoader(msg) {
    $("#loading").show();
    $(".xq_loading").html(msg).show();
}
function hideLoader()
{
    $("#loading").hide();
    $(".xq_loading").hide();
}
function loaddata(url, obj,object, sc) {
    showLoader('正在加载中....');
    $.get(url, function (data) {
        if (data.list.length > 0) {
            var gettpl = document.getElementById(object).innerHTML;
            laytpl(gettpl).render(data, function(html){
                //document.getElementById(obj).innerHTML = html;
                obj.append(html);
            });
        }
        lock = 0;
        hideLoader();
    }, 'json');
    if (sc === true) {
        $(window).scroll(function () {
            if (!lock && $(window).scrollTop() == $(document).height() - $(window).height()) {
                lock = 1;
                page++;
                var link = url+'&page='+page;
                // alert(link);return false;
                showLoader('正在加载中....');
                $.get(link, function (data) {
                    if (data.list.length > 0) {
                        var gettpl = document.getElementById(object).innerHTML;
                        laytpl(gettpl).render(data, function(html){
                            //document.getElementById(obj).innerHTML = html;
                            obj.append(html);
                        });
                    }else{
                        showLoader('全部数据加载完毕');
                    }
                    lock = 0;
                    hideLoader();
                }, 'json');
            }
        });
    }
}