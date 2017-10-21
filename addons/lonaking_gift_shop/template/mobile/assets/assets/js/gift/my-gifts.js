/**
 * Created by leon on 15/10/14.
 */
var myGifts = {
    init  : function () {

    },
    event : function(){
        /**
         * 点击我的礼品item事件
         */
        $$('div.pages').on('click','#my-gifts a.gift-order-link', function () {
            var btn = $$(this);
            mainFramework.showIndicator();
            myGifts.functions.loadMyGiftOrderDetail(btn.data('id'), function (json,url) {
                mainFramework.hideIndicator();
                mainView.router.loadPage({
                    url : url,
                    context : {
                        'gift_order' : json.data
                    }
                });
            });
        });
    },
    functions : {
        loadMyGiftOrderDetail : function (id,callback) {
            var url = $$('html').data('my-gift-order-detail');
            var postData = {
                'id' : id
            };
            $$.post(url,postData, function (e) {
                var json = JSON.parse(e);
                if(json.status == 200){
                    var url = '../addons/lonaking_gift_shop/template/mobile/assets/pages/gift-order-detail/my-gift-order-detail.html';
                    if(json.data.mode == 1){
                        url = '../addons/lonaking_gift_shop/template/mobile/assets/pages/gift-order-detail/my-gift-order-detail-hongbao.html';
                    }else if(json.data.mode == 2){
                        url = '../addons/lonaking_gift_shop/template/mobile/assets/pages/gift-order-detail/my-gift-order-detail-mobile-fee.html';
                    }else if(json.data.mode == 3){
                        url = '../addons/lonaking_gift_shop/template/mobile/assets/pages/gift-order-detail/my-gift-order-detail.html';
                    }else if(json.data.mode == 4){
                        url = '../addons/lonaking_gift_shop/template/mobile/assets/pages/gift-order-detail/my-gift-order-detail-ziling.html';
                    }
                    callback(json,url);
                }
            });
        }
    }
};
//$(function () {
//    myGifts.init();
//    myGifts.event();
//})
mainFramework.onPageBeforeInit('my-gifts',function(){
    myGifts.init();
    myGifts.event();
});