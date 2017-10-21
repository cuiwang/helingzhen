/**
 * @file 所有UI组件的基类，通过它可以简单的快速的创建新的组件。
 * @name zepto.ui
 * @short zepto.ui
 * @desc 所有UI组件的基类，通过它可以简单的快速的创建新的组件。
 * @import core/zepto.js, core/zepto.extend.js
 */
(function($, undefined) {
    var id = 1,
        _blankFn = function(){},
        tpl = '<%=name%>-<%=id%>',
        record = (function(){
            var data = {},
                id = 0,
                iKey = "GMUWidget"+(+ new Date()); //internal key.

            return function( obj, key, val){
                var dkey = obj[ iKey ] || ( obj[ iKey ] = ++id ),
                    store = data[dkey] || (data[dkey] = {});

                !$.isUndefined(val) && (store[key] = val);
                $.isNull(val) && delete store[key];

                return store[ key ];
            }
        })();
        
    $.ui = $.ui || {
        version: '2.0.5',

        guid: _guid,

       
        define: function(name, data, superClass) {
            if(superClass) data.inherit = superClass;
            var Class = $.ui[name] = _createClass(function(el, options) {
                var obj = _createObject(Class.prototype, {
                    _id: $.parseTpl(tpl, {
                        name: name,
                        id: _guid()
                    })
                });

                obj._createWidget.call(obj, el, options,Class.plugins);
                return obj;
            }, data);
            return _zeptoLize(name, Class);
        },

        
        isWidget: function(obj, name){
            return obj instanceof (name===undefined ? _widget: $.ui[name] || _blankFn);
        }
    };
        
    /**
     * generate guid
     */
    function _guid() {
        return id++;
    };

    function _createObject(proto, data) {
        var obj = {};
        Object.create ? obj = Object.create(proto) : obj.__proto__ = proto;
        return $.extend(obj, data || {});
    }

    function _createClass(Class, data) {
        if (data) {
            _process(Class, data);
            $.extend(Class.prototype, data);
        }
        return $.extend(Class, {
            plugins: [],
            register: function(fn) {
                if ($.isObject(fn)) {
                    $.extend(this.prototype,fn);
                    return;
                }
                this.plugins.push(fn);
            }
        });
    }

    /**
     * handle inherit & _data
     */
    function _process(Class, data) {
        var superClass = data.inherit || _widget,
            proto = superClass.prototype,
            obj;
        obj = Class.prototype = _createObject(proto, {
            $factory: Class,
            $super: function(key) {
                var fn = proto[key];
                return $.isFunction(fn) ? fn.apply(this, $.slice(arguments, 1)) : fn;
            }
        });
        obj._data = $.extend({}, proto._data, data._data);
        delete data._data;
        return Class;
    }

   
    function _zeptoLize( name ) {
        $.fn[ name ] = function(opts) {
            var ret,
                obj,
                args = $.slice(arguments, 1);

            $.each( this, function( i, el ){

                obj = record( el, name ) || $.ui[name]( el, $.extend( $.isPlainObject(opts) ? opts : {}, {
                    setup: true
                } ) );
                if ($.isString( opts )) {
                    if (!$.isFunction( obj[ opts ] ) && opts !== 'this') {
                        throw new Error(name + '组件没有此方法');    //当不是取方法是，抛出错误信息
                    }
                    ret = $.isFunction( obj[ opts ] ) ? obj[opts].apply(obj, args) : undefined;
                }
                if( ret !== undefined && ret !== obj || opts === "this" && ( ret = obj ) ) {
                    return false;
                }
                ret = undefined;
            });
            //ret 为真就是要返回ui实例之外的内容
            //obj 'this'时返回
            //其他都是返回zepto实例
            //修改返回值为空的时，返回值不对的问题
            return ret !== undefined ? ret : this;
        };
    }
    /**
     * @name widget
     * @desc GMU所有的组件都是此类的子类，即以下此类里面的方法都可在其他组建中调用。
     */
    var _widget = function() {};
    $.extend(_widget.prototype, {
        _data: {
            status: true
        },

        data: function(key, val) {
            var _data = this._data;
            if ($.isObject(key)) return $.extend(_data, key);
            else return !$.isUndefined(val) ? _data[key] = val : _data[key];
        },

        /**
         * common constructor
         */
        _createWidget: function(el, opts,plugins) {

            if ($.isObject(el)) {
                opts = el || {};
                el = undefined;
            }

            var data = $.extend({}, this._data, opts);
            $.extend(this, {
                _el: el ? $(el) : undefined,
                _data: data
            });

            //触发plugins
            var me = this;
            $.each( plugins, function( i, fn ){
                var result = fn.apply( me );
                if(result && $.isPlainObject(result) ){
                    var plugins = me._data.disablePlugin;
                    if( !plugins || $.isString(plugins) && !~plugins.indexOf(result.pluginName) ){
                        delete result.pluginName;
                        $.each(result,function( key, val ){
                            var orgFn;
                            if((orgFn = me[key]) && $.isFunction( val ) ){
                                me[key] = function(){
                                    me[key + 'Org'] = orgFn;
                                    return val.apply(me,arguments);
                                }
                            }else
                                me[key] = val;
                        });
                    }
                }
            });
            // use setup or render
            if(data.setup) this._setup(el && el.getAttribute('data-mode'));
            else this._create();
            this._init();

            var me = this,
                $el = this.trigger('init').root();
            $el.on('tap', function(e) {
                (e['bubblesList'] || (e['bubblesList'] = [])).push(me);
            });

            record( $el[0], me._id.split('-')[0], me );
        },

       
        _create: function() {},

       
        _setup: function(mode) {},

        root: function(el) {
            return this._el = el || this._el;
        },

        id: function(id) {
            return this._id = id || this._id;
        },

       
        destroy: function() {
            var me = this,
                $el;
            $el = this.trigger('destroy').off().root();
            $el.find('*').off();
            record( $el[0], me._id.split('-')[0], null);
            $el.off().remove();
            this.__proto__ = null;
            $.each(this, function(key) {
                delete me[key];
            });
        },

      
        on: function(ev, callback) {
            this.root().on(ev, $.proxy(callback, this));
            return this;
        },

       
        off: function(ev, callback) {
            this.root().off(ev, callback);
            return this;
        },

       
        trigger: function(event, data) {
            event = $.isString(event) ? $.Event(event) : event;
            var onEvent = this.data(event.type),result;
            if( onEvent && $.isFunction(onEvent) ){
                event.data = data;
                result = onEvent.apply(this, [event].concat(data));
                if(result === false || event.defaultPrevented){
                    return this;
                }
            }
            this.root().trigger(event, data);
            return this;
        }
    });
})(Zepto);