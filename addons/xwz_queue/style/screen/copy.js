/**
 * 复制文字
 * 突然觉得不知道用空间是对还是错...
 * @todo 要做显示,隐藏的接口
 * @example
 *     1, 单个节点复制
 *         copy({
 *            id:"#id",
 *            str:"要复制的内容"
 *        });
 *     
 *     2, 多个节点复制
 *         copy({
 *             id:"#id .copy",
 *             copy:"111"
 *         });
 *     3, 动态获取值
 *         copy({
 *             id:"#id",
 *             copy:function(){
 *                 return this.getAttribute("data-value");//this为当前的dom
 *             }
 *         });
 *     4, 外部设置内容
 *         copy({
 *             id:"#text"
 *         });
 *
 *         copy("#text", "我要复制") === copy({id:"#test", str:"我要复制"})
 *     5, 移除实例
 *         copy.remove(str||ele||jquery);//支持移除数组对象
 *     6, 配置参数
 *         config.id 选择器, 支持多个, dom,jquery,str
 *         config.str 要复制的内容, 支持fn, this指向当前的dom
 *         config.path swf路径
 *         config.success 成功回调, 回调参数为复制的内容, this指向当前的dom
 *         config.mouseover, mouseout, mousedown, mouseup 四个事件, this指向当前的dom
 *
 * 
 */
(function($, nameSpace){
    var count = 0,
        isIe6 = !-[1, ] && !window.XMLHttpRequest,
        _expando = 'xl_copy_'+ $.now(),
    copy = window.copy = function( config, str ){
        if("string" === typeof (str)){
            config = {
                id: config,
                str: str
            }
        }

        //合并默认
        config = $.extend({}, copy.defaults, config || {});

        //$包裹下
        config.id = $(config.id)

        if(!config.id.length){
            return copy;
        }

        $.each(config.id, function(){
            var that = this;
            //如果已经加载过
            if(! that._copy){

                //打上标识,并实例
                that._copy = _expando + (count++);
                copy._list[that._copy] = new Class(that._copy, that, config);
            } else {
                copy._get(that._copy) && copy._get(that._copy).setText(config.str);
            }
        });

        return copy;
    }

    copy.defaults = {
        id: "",
        str: "谢亮",
        path: '/assets/js/copy.swf',

        //5种事件
        success: $.noop,
        mouseover:$.noop,
        mouseout:$.noop,
        mousedown:$.noop,
        mouseup:$.noop
    }

    /**
     * 缓存列表
     */
    copy._list = {};

    /**
     * 根据标识获取实例
     */
    copy._get = function(id){
        return !!id && this._list[id];
    }

    /**
     * 移除id上的flash元素
     */
    copy.remove = function(id){
        id = $(id);
        if(!id.length){
            return copy;
        }

        $.each(id, function(){
            //如果装载过flash才算
            if(this._copy){
                copy._get(this._copy) && copy._get(this._copy).remove();
            }
        });

        return copy;
    }

    /**
     * flash触发回调
     * @param  {string}   id        实例id
     * @param  {string}   eventName 事件名称
     * @param  {(string|null)}   param     参数或者空
     */
    copy.FLASHcallback = function(id, eventName, param){
        // console.log(id, eventName, param)
        var api = copy._get(id);

        //如果不存在
        if(!api){
            return copy;
        }

        //执行config里的回调
        if("function" === typeof (api.config[eventName])){
            api.config[eventName].call(api._dom.$ele[0], param);//总是拿dom 作call
        }
        api = null;
    }

    /**
     * 获取HTML代码
     * @param  {number} width  swf宽
     * @param  {number} height swf宽
     * @return {string}        HTML代码
     */
    copy._getHTML = function(id, width, height, path) {
            var html = '',
                flashvars = 'id=' + id + '&width='+ width +'&height='+ height;
            if(isIe6){
                path += '?v='+ (+new Date);
            }

            if (navigator.userAgent.match(/MSIE/)) {
                // IE gets an OBJECT tag
                html += '<object id="'+ id +'" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" style="width:100%;height:100%;vertical-align:top;"><param name="allowScriptAccess" value="always" /><param name="allowFullScreen" value="false" /><param name="movie" value="'+ path +'" /><param name="loop" value="false" /><param name="menu" value="false" /><param name="quality" value="best" /><param name="bgcolor" value="#ffffff" /><param name="flashvars" value="' + flashvars + '"/><param name="wmode" value="transparent"/></object>';
            } else {
                // all other browsers get an EMBED tag
                html += '<embed id="'+ id +'" name="'+ id +'" style="width:100%;height:100%;vertical-align:top;" src="'+ path +'" loop="false" menu="false" quality="best" bgcolor="#ffffff" allowScriptAccess="always" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" flashvars="' + flashvars + '" wmode="transparent" />';
            }
            return html;
        }

    /**
     * 给flash提供获取最新的复制文本
     * @param  {string} id 实例id
     */
    copy.FLASHgetStr = function(id){
        // alert("更新中"+ str)
        // console.log(copy._list[id]._dom.flashEle.setText)
        copy._get(id) && copy._get(id).setText();
    }








    // 构架类
    function Class(id, ele, config){
        this.config = config;
        this.id = id;//唯一标识
        this._dom = {
            $ele: $(ele)
        }
        return this._init();
    }

    Class.prototype = {
        _init: function(){
            this._append();
            this._resize();
            this._bind();
            return this;
        },

        /**
         * 删除实例
         */
        remove: function(){
            var that = this,
                id = that.id,
                i;

            //移除事件
            
            $(window).off("resize."+ id);

            //删除HTML
            that._dom.$wrap.remove();

            //从HTMLElement上删除标识以便于可以二次使用
            that._dom.$ele[0]['_copy'] = undefined;
            // delete that._dom.$ele[0]['_copy'];

            //删除缓存
            delete copy._list[id];

            //删除实例
            for(i in that){
                delete that[i];
            }

            
        },

        /**
         * 设置文本
         * @param {[type]} str [description]
         */
        setText: function(str){
            var that = this;
            //没有的情况下是从flash回调过来的
            if(str){
                that.config.str = str;//如果有则更新到config里,因为可能是外部设置的
            } else {
                str = that.config.str;//没有str则直接拿config里的
            }
            if("function" === typeof (str)){
                str = str.call(that._dom.$ele[0]);
            }

            //如果swf元素上有方法才执行
            if(that._dom.flashEle && that._dom.flashEle.setText){
                that._dom.flashEle.setText(str);
            }
            return that;
        },

        /**
         * 绑定事件
         */
        _bind: function(){
            var that = this,
                dom = this._dom;
                
            $(window).on("resize."+ this.id, $.proxy(that._resize, that));
        },
        _resize: function(){
            var offset = this._dom.$ele.offset();
            this._dom.$wrap.css({
                left: offset.left,
                top: offset.top
            });
            offset = null;
        },
        _append: function(){
            var dom = this._dom,
                div = document.createElement("div"),
                width = dom.$ele.outerWidth(true),
                height = dom.$ele.outerHeight(true);

            div.style.position = 'absolute';
            div.style.zIndex = 1000;
            div.style.width = width +"px";
            div.style.height = height +"px";


            // div.style.backgroundColor = '#f00';//debug

            

            dom.$wrap = $(div);


            document.body.appendChild(div);

            //必须在插入到dom后再操作innerHTML否则ie有bug
            div.innerHTML = copy._getHTML(this.id, width, height, this.config.path);

           
            dom.flashEle = document.getElementById(this.id);

            div = null;
        }

    };
}(jQuery, 'copy'));