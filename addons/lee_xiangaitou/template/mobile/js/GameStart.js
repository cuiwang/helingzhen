/**
 * Created by hasee on 2015/2/6.
 */
var GameStart = cc.Scene.extend({
    onEnter:function () {
        this._super();
        //添加层节点
        var layer = new GameLayer();
        layer.init();
        this.addChild(layer);
    }
});

var GameLayer = cc.Layer.extend({
    share:null,
    ctor: function () {
        this._super();
        this.init();
    },
    init: function () {
        this._super();
        var winsize=cc.director.getWinSize();
        var Sprite_bg=cc.Sprite.create(res.bg_Start);
        Sprite_bg.setPosition(cc.p(winsize.width/2,winsize.height/2));
        this.addChild(Sprite_bg);



        //创建一个游戏开始菜单
        var menu = new cc.Menu;
        menu.setPosition(cc.p(0,0));

        //开始
        var MenuItem=cc.MenuItemImage.create(res.btn_play,null,this.OnStart,this);
        MenuItem.setPosition(cc.p(winsize.width/2,winsize.height*0.15));
        menu.addChild(MenuItem);

        //奖品
        var award=cc.MenuItemImage.create(res.btn_award,null,onAward,this);//online
        award.setPosition(cc.p(winsize.width/2,winsize.height*0.26));
        menu.addChild(award);

        //说明
        var RuleItem=cc.MenuItemImage.create(res.btn_rule,null,this.OnRule,this);
        RuleItem.setPosition(cc.p(winsize.width/2,winsize.height *0.35));
        menu.addChild(RuleItem);

        this.addChild(menu);
    },
    //开始游戏的事件响应
    OnStart:function(){
        this.rule = new cc.Sprite(res.bg_guide);
        this.rule.attr({
            x:cc.winSize.width/2,
            y:cc.winSize.height/2
        });
        this.handAction();
        this.addChild(this.rule,10,"start");
        var self = this;
        var listener = cc.EventListener.create({
            event: cc.EventListener.TOUCH_ONE_BY_ONE,
            swallowTouches: true,
            onTouchBegan: function (touch, event) {     //实现 onTouchBegan 事件处理回调函数
                self.onPlay();
                return false;
            }
        });
        cc.eventManager.addListener(listener,this.rule);


    },

    OnRule:function(){		
		window.location.href=ruleurl;		
    },
    onPlay: function () {
        //g_time=0;
        //g_num=0;		
		$.post(playurl,{"xgtid":saveInfoArray[0],"uid":saveInfoArray[1],"from_user":saveInfoArray[2]},function(data){
			if(data.code == 1){
				cc.director.runScene(new GameMain());
			}else{
				alert(data.msg);					
			}		
		},"json");
    },
    handAction: function () {
        var hand = new cc.Sprite(res.img_hand);
        hand.attr({x:cc.winSize.width*0.82,y:cc.winSize.height*0.36});
        this.rule.addChild(hand,5);
        hand.setOpacity(0);
        var arrowAction = cc.repeatForever(cc.sequence(cc.spawn(cc.moveTo(0.8, cc.p(hand.x, hand.y + 80)).easing(cc.easeIn(0.5)), cc.fadeIn(1)), cc.fadeOut(0.8), cc.callFunc(function () {
            hand.y = hand.y - 100;
            hand.opacity = 255;
        }, this)));
        hand.runAction(arrowAction);
    }


});