/**
 * Created by Administrator on 2015/6/12.
 */
(function(global,factory){
    if(typeof define === 'function' && define.amd){ //AMD
        define(function(){
            return factory(global);
        });
    }else if(typeof module !== 'undefined' && module.exports){ //cmd
        module.exports = factory(global);
    }else {
        global.fixMeta = factory(global);
    }
}(typeof window !== 'undefined' ? window : this,function(window){
    var MetaFix = MetaFix || {};
    window._zoom = 1;
    window._os = window._os || {};
    /**
     * 动态改变viewport
     * @param designWidth
     */
    MetaFix.viewport = function(designWidth){
        checkOS();
        var ratio = getRatio(designWidth);
        var viewport = document.querySelector('meta[name=viewport]');
        if(!viewport){
            var viewportMeta = document.createElement('meta');
            viewportMeta.name = 'viewport';
            viewportMeta.content = 'width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no';
            document.head.appendChild(viewportMeta);
            viewport = document.querySelector('meta[name=viewport]');
        }

        viewport.setAttribute('content','width='+designWidth+', initial-scale='+ ratio +', minimum-scale = '+ ratio +', maximum-scale = '+ ratio +', target-densitydpi=device-dpi,user-scalable=no');
        //if(_os.android){
        //    viewport.setAttribute('content','width='+designWidth+', initial-scale='+ ratio +', minimum-scale = '+ ratio +', maximum-scale = '+ ratio +', target-densitydpi=device-dpi,user-scalable=no');
        //}else if(_os.ios && !_os.android){
        //    //viewport.setAttribute('content','width='+designWidth+',target-densitydpi=device-dpi,user-scalable=no');
        //    viewport.setAttribute('content','width='+designWidth+',target-densitydpi=device-dpi,initial-scale='+ ratio +',minimum-scale = '+ ratio +', maximum-scale = '+ ratio +', user-scalable=no');
        //    if(_os.ios && parseInt(_os.version)<7){
        //        viewport.setAttribute('content','width='+designWidth+',target-densitydpi=device-dpi,initial-scale='+ ratio +',minimum-scale = '+ ratio +', maximum-scale = '+ ratio +', user-scalable=no');
        //    }
        //}
        console.log('viewport初始化完成');
    };
    /**
     * 通过css中的zoom属性改变显示区域
     * @param designWidth
     */
    MetaFix.zoom = function(designWidth){
        var viewport = document.querySelector('meta[name=viewport]');
        if(!viewport){
            var viewportMeta = document.createElement('meta');
            viewportMeta.name = 'viewport';
            viewportMeta.content = 'width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no';
            document.head.appendChild(viewportMeta);
        }else viewport.setAttribute('content','width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no');
        checkOS();
        var zoomStyleDom = document.getElementById('M_zoom');
        if(!zoomStyleDom){
            createStyle();
        }
        setZoom();
        //生成必要的style标签
        function createStyle(){
            var zoomStyle = document.createElement('style');
            zoomStyle.type = 'text/css';
            zoomStyle.id = 'M_zoom';
            document.head.appendChild(zoomStyle);
            zoomStyleDom = document.getElementById('M_zoom');
        }
        function setZoom(){
            _zoom = getRatio(designWidth);
            zoomStyleDom.innerHTML = '.M_zoom{zoom:' + _zoom + ' !important}';
            document.body.className += (document.body.className.indexOf(' M_zoom')>-1 ? '':' M_zoom');
            console.log('zoom初始化完成');
        }
    };

    function getRatio(designWidth){
        var iw = window.innerWidth || designWidth,
            ow = window.outerWidth || iw,
            sw = window.screen.width || iw,
            saw = window.screen.availWidth || iw,
            ih = window.innerHeight || designWidth,
            oh = window.outerHeight || ih,
            sh = window.screen.height || ih,
            sah = window.screen.availHeight || ih,
            w = Math.min(iw,ow,sw,saw,ih,oh,sh,sah),
            ratio = w/designWidth,
            dpr = window.devicePixelRatio,
            ratio = Math.min(ratio,dpr);
        _zoom = ratio;
        return ratio;
    }

    function checkOS(){
        var ua = navigator.userAgent.toLowerCase(),
            android = ua.match(/(android);?[\s\/]+([\d.]+)?/),
            ipad = ua.match(/(ipad).*os\s([\d_]+)/),
            ipod = ua.match(/(ipod)(.*os\s([\d_]+))?/),
            iphone = !ipad && ua.match(/(iphone\sos)\s([\d_]+)/),
            os = {};
        if(android) os.android = true, os.version = android[2];
        if(iphone && !ipod) os.ios = os.iphone = true, os.version = iphone[2].replace(/_/g,'.');
        if(ipad) os.ios = os.ipad = true, os.version = ipad[2].replace(/_/g, '.');
        if(ipod) os.ios = os.ipod = true, os.version = ipod[3] ? ipod[3].replace(/_/g, '.') : null;
        window._os = os;
    }
    MetaFix.viewport(640);
    //window.addEventListener('resize',function(){
    //    MetaFix.viewport(640);
    //},false);
    return MetaFix;
}));