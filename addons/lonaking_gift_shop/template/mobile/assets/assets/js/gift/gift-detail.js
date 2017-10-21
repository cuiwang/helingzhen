/**
 * Created by leon on 8/19/15.
 */
var giftDetail = {
    init : function(){

    },

    event : function () {
        /*确认兑换按钮*/
        $$('div.pages div.page[data-page=gift-detail]').on('click','div.toolbar button', function (e) {
            e.stopPropagation();
            var btn = $$(this);
            var id = btn.attr('data-id');
            var score = parseInt($$(this).attr('data-score'));
            var price = parseInt($$(this).attr('data-price'));
            var send_price = parseFloat($$(this).data('send-price'));
            var giftMode = parseInt($$(this).data('mode'));
            if(score < price || score == null || score == undefined || isNaN(score) || isNaN(price)){
                mainFramework.alert('积分不足');
            	return ;
            }
            var url = '../addons/lonaking_gift_shop/template/mobile/assets/pages/gift-buy.html';
            if(giftMode == 1){
                //微信
                url = "../addons/lonaking_gift_shop/template/mobile/assets/pages/gift-buy-form/gift-buy-wechat-money.html";
            }else if(giftMode == 2){
                //手机话费
                url = "../addons/lonaking_gift_shop/template/mobile/assets/pages/gift-buy-form/gift-buy-mobile-fee.html";
            }else if(giftMode == 3){
                //默认的
                url = "../addons/lonaking_gift_shop/template/mobile/assets/pages/gift-buy-form/gift-buy-default.html";
            }else if(giftMode == 4){
                //自领礼品
                url = "../addons/lonaking_gift_shop/template/mobile/assets/pages/gift-buy-form/gift-buy-ziling.html";
            }
            mainView.router.loadPage({
                url : url,
                context : {
                	id : btn.attr('data-id'),
                	name : btn.attr('data-name'),
                	price : btn.attr('data-price'),
                	score : btn.attr('data-score'),
                	image : btn.attr('data-image'),
                    mode : giftMode,
                    send_price : btn.data('send-price'),
                }
            });
        });

    },
    functions : {

    }
};
mainFramework.onPageBeforeInit('gift-detail',function(e){
    giftDetail.init();
    giftDetail.event();
});
