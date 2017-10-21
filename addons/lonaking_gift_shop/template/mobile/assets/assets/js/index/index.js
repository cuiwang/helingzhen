/**
 * Created by leon on 8/19/15.
 */
var index = {
    init: function () {
        // 初始化首页的轮播图片
        index.functions.swiperInit();
        
        index.functions.checkFollow();

		index.functions.checkLoadGo();

    },
    event: function () {
		$$('.click-location').on('click',function(e){
			window.location.href = $$(this).attr('data-location-url');
		});

    	//点击广告
    	$$('div.pages').on('click','div.page[data-page=index] div.swiper-container div.task-item img',function(e){
			window.location.href = $$(this).attr('data-url');
		});

        // 点击礼物
        $$('div.pages').on('click','div.page[data-page=index] #gift-shop .row div.gift', function (e) {
            e.stopPropagation();
            var btn = $$(this);
            mainView.router.loadPage({
                url : '../addons/lonaking_gift_shop/template/mobile/assets/pages/gift-detail.html',
                context : {
                	id : btn.attr('data-id'),
                	name : btn.attr('data-name'),
                	price : btn.attr('data-price'),
                	score : btn.attr('data-score'),
                	image : btn.find('div.gift-img img').attr('src'),
					mode : btn.data('mode'),
					send_price : btn.data('send-price'),
					description : btn.find('.description').html(),
					num : btn.data('num'),
					sold : btn.data('sold'),
					limit_num : btn.data('limit-num')
                }
            });
        });

		//点击我的礼品
		$$('div.pages').on('click','div.page[data-page=index] .toolbar-tab .right', function (e) {
			e.stopPropagation();
			mainFramework.showIndicator();
			var btn = $$(this);
			var url = $$('html').data('mygifts-api-url');
			$.post(url, function(result){
				mainFramework.hideIndicator();
				var json = JSON.parse(result);
				if(json.status!=200){
					mainFramework.alert(json.message);
					return ;
				}
				if(json.status==200){
					//家在新页面
					mainView.router.loadPage({
						url : '../addons/lonaking_gift_shop/template/mobile/assets/pages/my-gifts.html',
						context : {
							'new_gifts' : json.data.new_gifts,
							'success_gifts' : json.data.success_gifts,
							'attachurl' : $$('html').data('attachurl')
						}
					});
				}
			});
		});
    },
    functions: {
        // 初始化首页广告图片，这里应该请求的
        swiperInit : function () {
            var indexSwiper = mainFramework.swiper('div.pages div.page[data-page=index] div.swiper-container', {
            	preloadImages: false,
            	pagination: '.swiper-pagination',
                lazyLoading: true,
            });
        },
		checkFollow : function () {
			var follow_status = $$('html').data('follow-status');
			if(follow_status == 0){
				mainFramework.confirm("您还没有关注本微信公众平台,点击确定进入引导关注页面", function () {
					var follow_url = $$('html').data('follow-url');
					window.location.href = follow_url;
					return ;
				}, function () {

				});
			}else{

			}
		},

        loadGiftDetail : function(id,callback){
            // 请求id为id的url
            mainView.router.loadPage({
                url : 'pages/gift-detail.html'
            });
            callback(this);
        },
		//检测是否礼品信息
		checkLoadGo : function(){
			var goParam = getQueryString("go");
			if(goParam == 'gift_detail'){
				mainFramework.showIndicator();
				myGifts.functions.loadMyGiftOrderDetail(getQueryString('id'), function (json,url) {
					mainFramework.hideIndicator();
					mainView.router.loadPage({
						url : url,
						context : {
							'gift_order' : json.data
						}
					});
				});
			}else if(goParam == "mygifts"){
				mainFramework.showIndicator();
				var btn = $$(this);
				var url = $$('html').data('mygifts-api-url');
				$.post(url, function(result){
					mainFramework.hideIndicator();
					var json = JSON.parse(result);
					if(json.status!=200){
						mainFramework.alert(json.message);
						return ;
					}
					if(json.status==200){
						//家在新页面
						mainView.router.loadPage({
							url : '../addons/lonaking_gift_shop/template/mobile/assets/pages/my-gifts.html',
							context : {
								'new_gifts' : json.data.new_gifts,
								'success_gifts' : json.data.success_gifts,
								'attachurl' : $$('html').data('attachurl')
							}
						});
					}
				});
			}
		}
    }
}
/* 初始化页面 */
$(function(e){
    index.init();
    index.event();
})
