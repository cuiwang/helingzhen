/**
 * 易客竞猜
 * @param uid
 * @param openid
 * @constructor
 */

function yikeGuess(url, uid, openid) {
    this.url = url + '?i='+uid+'&c=entry&m=yike_guess';
    this.uid = uid;
    this.openid = openid;
}

yikeGuess.prototype = {
    constructor: yikeGuess,
    /**
     * 基础查询函数
     * @param controller
     * @param action
     * @param op
     * @returns {AV.Promise}
     */
    query: function (data) {
        var promise = new AV.Promise();
        var url = this.url;
        for (var key in data) {
            if (url != "") {
                url += "&";
            }
            url += key + "=" + encodeURIComponent(data[key]);
        }

        $.ajax({
            url: url,
            dataType: 'jsonp',
            processData: false,
            type: 'get',
            success: function (data) {
                promise.resolve(data);
            },
            error: function (i, data) {
                promise.reject(data);
            }
        });
        return promise;
    },


    /**
     * 首页
     */
    index:function(){
        return this.query('index', 'index', '', {});
    },
    /**
     * isFollow
     */
    isFollow:function(){
        return this.query({
            do:'follow'
        });
    },
    /**
     * 查看开启模块
     */
    moduleIsOpen:function(){
        return this.query({
            do:'on_off'
        });
    },

    /**
     * 获取竞猜分类
     */
    getClassify:function(){
        return this.query({
            do: 'guess_list',
            op: 'classify'
        });
    },
    /**
     * 获取竞猜子分类
     */
    getSubClassifyById:function(id){
        return this.query({
            do:'guess_list',
            op:'classify',
            classify_id:id
        });
    },
    /**
     * 获取竞猜列表
     */
    getList:function(id){
        return this.query({
            do:'guess_list',
            op:'guess',
            classify_id:id || 0
        });
    },
    /**
     * 获取竞猜详情
     */
    getGuessDetails:function(id){
        return this.query({
            do:'guess_details',
            id:id
        });
    },
    /**
     * 获取用户信息
     */
    getUser:function(){
        return this.query({
            do:'user'
        });
    },
    /**
     *签到
     */
    sign:function(){
        return this.query({
            do:'user',
            op:'sign'
        });
    },
    /**
     * 获取签到信息
     */
    getSignInformation:function(){
        return this.query({
            do:'sign_in'
        });
    },
    /**
     * 获取月积分排行
     */
    getMonthTop:function(){
        return this.query({
            do:'ranking',
            op:'month'
        });
    },
    /**
     * 获取总积分排行
     */
    getAllTop:function(){
        return this.query({
            do:'ranking',
            op:'all'
        });
    },
    /**
     * 获取积分明细
     */
    getBalance:function(){
        return this.query({
            do:'balance'
        });
    },
    /**
     * 下注
     */
    bet:function(id,money,bet){
        return this.query({
            do:'bet',
            guess_id:id,
            money:money,
            bet:bet
        });
    },
    /**
     * 我的竞猜中的竞猜
     */
    myOrder:function(){
        return this.query({
            do:'my_order',
            op:'list'
        });
    },
    /**
     * 我的已经开奖竞猜
     */
    myOpenOrder:function(){
        return this.query({
            do:'my_order',
            op:'open_list'
        });
    },
    /**
     * 我的竞猜详情
     */
    myOrderDetails:function(id){
        return this.query({
            do:'my_order',
            op:'order_details',
            order_id:id
        });
    },

    /**
     * 获取我的连续签到天数
     */
    getSignDays:function(){
        return this.query({
            do:'message'
        });
    },
    /**
     * 分享成功的回调函数
     */
    shareCallback:function(){
        return this.query({
            do:'callback'
        });
    },

    /**
     * 获取任务信息
     */
    getMission:function(){
        return this.query({
            do:'mission'
        });
    },

    /**
     * 查看图片接口
     */
    imagesUrl:function(image,images){
        var lo='http://'+location.host;
        AV._.each(images,function(img,index){
            images[index]=lo+img;
        });
        image=lo+image;
        console.log(image);
        console.log(images);

        try {
            wx.previewImage({
                current: image,
                urls: images
            })
        } catch(ex) {
            alert(JSON.stringify(ex));
        }

    }
};

//var openid = elocalStorage.get('openid') || '';
var yikeGuess = new yikeGuess(WX_API_URL, WX_ID, openid);
