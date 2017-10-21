/**
 * Created by Will on 2014/10/16.
 */

var MyLoaderScene = cc.Scene.extend({
    _interval : null,
    _length : 0,
    _count : 0,
    _label : null,
    _className:"MyLoaderScene",
    loadingBarWidth:0,
    loadingBarHeight:0,
    img_fu:null,
    actfor:null,
    actRo:null,
    init : function() {
        var self = this;

        // bg
        var bgLayer = self._bgLayer = cc.LayerColor.create(cc.color(r = 255, b = 255, g = 255, a = 255), cc.winSize.width, cc.winSize.height);
        bgLayer.setPosition(cc.visibleRect.bottomLeft);
        self.addChild(bgLayer, 0);

        var img = self._img = new cc.Sprite(res.loaderSprite_png);
        img.attr({
            x: cc.winSize.width / 2,
            y: cc.winSize.height / 2
        });
        bgLayer.addChild(this._img, 1);

//        var ProcessBarDown = self.ProcessBarDown = new cc.Sprite(res.processDown_png);
//        ProcessBarDown.attr({
//            x: cc.winSize.width / 2,
//            y: cc.winSize.height * 0.4,
//            anchorX: 0.5,
//            anchorY: 0.5
//        });
//        bgLayer.addChild(this.ProcessBarDown, 2);

//        this.img_fu = self.img_fu = new cc.Sprite(res.img_loading);
//        this. img_fu.attr({
//            x: cc.winSize.width *0.18+1,
//            y: cc.winSize.height * 0.32,
//            anchorX: 0.5,
//            anchorY: 0.5
//        });
//        this.addChild(this.img_fu, 3);
//        this.actRo = new cc.rotateBy(6,360);//img转动
//        this.actfor = this.actRo.repeatForever();
//        this.img_fu.runAction(this.actfor);

        //加载进度条
        var resLoadingBar = [];
        resLoadingBar.push(res.processUp_png);
        cc.loader.load(resLoadingBar,
            function (result, count, loadedCount) {
            }, function () {
                var ProcessBarUp = self.ProcessBarUp = new cc.Sprite(res.processUp_png);
                ProcessBarUp.attr({
                    x:cc.winSize.width/6-14,
                    y:cc.winSize.height*0.17+34,
                    anchorX:0,
                    anchorY:0,
                    scaleX:1,
                    scaleY:1
                });
                var size_loadingBar = self.ProcessBarUp.getContentSize()
                self.loadingBarWidth = size_loadingBar.width;
                self.loadingBarHeight = size_loadingBar.height;
                self.ProcessBarUp.setTextureRect(cc.rect(0,0,0,self.loadingBarHeight));
                self.addChild(self.ProcessBarUp, 2);
                self.schedule(self._startLoading, 0.3);//开始处 延迟 0.5
                return true;
            });
    },
    onEnter: function () {
        var self = this;
        cc.Node.prototype.onEnter.call(self);
    },
    onExit: function () {
        cc.Node.prototype.onExit.call(this);
    },
    initWithResources: function (resources, cb) {
        if(typeof resources == "string") resources = [resources];
        this.resources = resources || [];
        this.cb = cb;
    },
    _startLoading: function () {
        var self = this;
        self.unschedule(self._startLoading);
        var res = self.resources;
        cc.loader.load(res,function (result, count, loadedCount) {//res加载资源  成功跳转
                var loadedCount1 = 1+loadedCount;
                var percent = loadedCount1 / count;
                self.ProcessBarUp.setTextureRect(cc.rect(0,0,self.loadingBarWidth*percent,self.loadingBarHeight));
                //     self.ProcessBarUp.setScale(percent,1);
//              self.img_fu.setPosition(cc.winSize.width *0.18+loadedCount1*11.8+5, cc.winSize.height * 0.32+2);

            }, function () {
                if (self.cb)
                //加载完后停滞一会在进入游戏
                    self.schedule(self.cb,0,0,0.7);
            });
    }
});
MyLoaderScene.preload = function(resources, cb){
    var _myLoaderScene = null;
    if(!_myLoaderScene) {
        _myLoaderScene = new MyLoaderScene();
        _myLoaderScene.init();
    }
    _myLoaderScene.initWithResources(resources, cb);

    cc.director.runScene(_myLoaderScene);
    return _myLoaderScene;
};
