/**
 * Created by zhoufeng on 16/8/31.
 */
/**
 * Created by Administrator on 2016/5/26.
 */

$(function () {
    bottomBar.bindQuickEvent();
    bottomBar.changeSelect();
});
/*气球快捷菜单*/
var bottomBar = {
    changeSelect: function () {
        var bottomBarCheckedIndex = 0;
        var request_uri = location.href;
        if (request_uri.indexOf("/members/myprofile.do") > -1)
            bottomBarCheckedIndex = 4;
        else if (request_uri.indexOf("/forum/news/index.do") > -1)
            bottomBarCheckedIndex = 3;
        else if (request_uri.indexOf("/propertyIndex") > -1)
            bottomBarCheckedIndex = 1;

        var $tmpImg = $(".weui_tabbar_item:eq(" + bottomBarCheckedIndex + ") img");
        var tmpSrc = $tmpImg.attr("src");
        var replacedSrc = tmpSrc.substring(0, tmpSrc.lastIndexOf(".")) + "_on" + tmpSrc.substring(tmpSrc.lastIndexOf("."));

        $tmpImg.attr("src", replacedSrc);

        $(".weui_tabbar_item:eq(" + bottomBarCheckedIndex + ")").addClass("weui_bar_item_on");
    },
    showBalloon: function (dom) {
        $(".p-shade").show();
        $(".balloon").animate({bottom:'60px'});;
        $(".nav-query-off").addClass("nav-query-on");
        $(".nav-query-off").removeClass("nav-query-off");
        $(dom).unbind("click").click(function(){bottomBar.hideBalloon(dom);})
    },
    hideBalloon: function (dom) {
        $(".p-shade").hide();
        $(".nav-query-on").addClass("nav-query-off");
        $(".nav-query-on").removeClass("nav-query-on");
        $(".balloon").animate({bottom:'-100%'});;
        $(dom).unbind("click").click(function(){bottomBar.showBalloon(dom);})
    },
    bindQuickEvent: function () {
        document.body.addEventListener('touchmove', function (event) {
            if($(".p-shade").is(":visible"))
                event.preventDefault();
        }, false);

        $(".nav-query-off").click(function(){
            bottomBar.showBalloon(this);
        });

        $(".p-shade,.balloon").click(function(){
            bottomBar.hideBalloon(this);
        });
    }
}


