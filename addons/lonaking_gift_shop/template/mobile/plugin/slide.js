/**
 * Created by leon on 15/9/6.
 */
var slide = {
    init : function(){
        var slide_html = $$('#slide').html().trim();
        var imgs = $$('#slide .swiper-wrapper').html().trim();
        if(slide_html.length!=0 && imgs.length != 0){
            var modal = mainFramework.modal({
                title :"广告",
                afterText:  $$('#slide').html(),
                buttons: [
                    {
                        text: '关闭广告',
                        bold: true,
                        onClick: function () {
                            //mainFramework.alert('关闭广告');
                            slide.functions.remove_framework();
                        }
                    },
                    {
                        text: '查看详情',
                        bold: true,
                        onClick: function () {
                            var url = $$('body div.swiper-slide-active img').data('url');
                            window.location.href = url;
                        }
                    },
                ]
            })
            mainFramework.swiper($$(modal).find('.swiper-container'), {pagination: '.swiper-pagination'});
        }else{
            slide.functions.remove_framework();
        }
    },
    event : function () {

    },
    functions : {
        remove_framework : function(){
            $$("link[name=framework]").remove();
            $$("link[name=framework-js]").remove();
        }
    }
};
$(function () {
    slide.init();
    slide.event();
});