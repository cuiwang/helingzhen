/**
 * Created by leon on 8/19/15.
 */
var giftBuy = {
    init  : function () {

    },
    event : function(){
    	/**
    	 * 提交兑换信息
    	 */
        $$('div.pages').on('click','div.page[data-page=gift-buy] a.submit-order-info', function (e) {
            e.stopPropagation();
            var btn = $$(this);
            btn.removeClass('submit-order-info');
            mainFramework.showPreloader('提交中...');
            var giftOrderApiUrl = $("html").attr("data-url-giftorderapi");
            var giftMode = btn.data('mode');
            var postData = {
        		gift_id : $("input[name='order_gift_id']").val(),
            }
            if(giftMode == 1){
                //微信红包

            }else if( giftMode == 2){
                //话费充值
                postData.mobile = $("input[name='order_mobile']").val();
            }else if( giftMode == 3){
                //实物礼品
                postData.name = $("input[name='order_name']").val();
                postData.mobile = $("input[name='order_mobile']").val();
                postData.target = $("input[name='order_target']").val();
                postData.pay_method = $$('select[name=pay_method]').val();
            }else if( giftMode == 4){
                //自领礼品
                postData.name = $("input[name='order_name']").val();
                postData.mobile = $("input[name='order_mobile']").val();
            }

            //请求发送数据
            $$.post(giftOrderApiUrl, postData, function (result) {
                mainFramework.hidePreloader();
                var json = JSON.parse(result);
                if(json.status == 200){
                    mainFramework.alert(json.message, function () {
                        var giftOrderUrl = $$('html').data('my-gift-order-detail');
                        var giftOrderPostData = {
                            'id' : json.data.order.id
                        };
                        if(json.data.pay_redirect != '' && json.data.pay_redirect != null){
                            //alert(json.data.pay_redirect);
                            window.location.href = json.data.pay_redirect;
                            return false;
                        }else{
                            $$.post(giftOrderUrl,giftOrderPostData, function (result2) {
                                var giftOrderJson = JSON.parse(result2);
                                if(giftOrderJson.status == 200){
                                    var redirectUrl = '../addons/lonaking_gift_shop/template/mobile/assets/pages/gift-order-detail/my-gift-order-detail.html';
                                    if(giftOrderJson.data.mode == 1){
                                        redirectUrl = '../addons/lonaking_gift_shop/template/mobile/assets/pages/gift-order-detail/my-gift-order-detail-hongbao.html';
                                    }else if(giftOrderJson.data.mode == 2){
                                        redirectUrl = '../addons/lonaking_gift_shop/template/mobile/assets/pages/gift-order-detail/my-gift-order-detail-mobile-fee.html';
                                    }else if(giftOrderJson.data.mode == 3){
                                        redirectUrl = '../addons/lonaking_gift_shop/template/mobile/assets/pages/gift-order-detail/my-gift-order-detail.html';
                                    }else if(giftOrderJson.data.mode == 4){
                                        redirectUrl = '../addons/lonaking_gift_shop/template/mobile/assets/pages/gift-order-detail/my-gift-order-detail-ziling.html';
                                    }
                                    mainView.router.loadPage({
                                        url : redirectUrl,
                                        context : {
                                            'gift_order' : giftOrderJson.data
                                        }
                                    });
                                    return ;
                                }
                            });
                        }
                    });
                }else{
                    mainFramework.alert(json.message);
                    return ;
                }
            });
        });
    },
    functions : {

    }
};
mainFramework.onPageBeforeInit('gift-buy',function(e){
    giftBuy.init();
    giftBuy.event();
});