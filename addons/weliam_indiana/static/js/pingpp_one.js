(function(){
    var one_url='http://'+window.location.host+"/addons/weliam_indiana/static/js/index.js";
    var sea_url='http://'+window.location.host+"/addons/weliam_indiana/static/js/sea.js";
    var pingpp_one=function(){};

    window.pingpp_one=new pingpp_one();

    var e=document.createEvent('Event');
    e.initEvent('pingpp_one_ready',true,true);

    var use=function(){
        seajs.use(one_url,function(index){
            var t=index('./init');
            var s=index('./success');

            pingpp_one.prototype.init=function(opt,callback){
                t.init(opt,callback);
            };

            pingpp_one.prototype.success=function(callback,continueCallback){
                s.init(callback,continueCallback);
            };

            pingpp_one.prototype.resume=function(){
                t.resume();
            };

            document.dispatchEvent(e);
        });
    };
    if(!window.seajs){
        var script=document.createElement('script');
        script.type='text/javascript';
        script.src=sea_url;
        document.body.appendChild(script);
        script.onload=function(){
            use();
        };
    }
    else{
        use();
    }
})();