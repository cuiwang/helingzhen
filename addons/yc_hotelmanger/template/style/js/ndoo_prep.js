
/*
" --------------------------------------------------
"   FileName: ndoo_prep.coffee
"       Desc: ndoo.js前置文件 for mini
"     Author: chenglifu
"    Version: ndoo.js(v0.1b2)
" LastChange: 11/04/2014 15:48
" --------------------------------------------------
 */

/* Notice: 不要修改本文件，本文件由ndoo_prep.coffee自动生成 */
var turbolinks_content_filter, turbolinks_init_filter, turbolinks_popstate_filter, turbolinks_redirect_filter, turbolinks_url_filter;

(function() {
  var _n;
  _n = this;

  /*变量名称空间 */
  _n.vars || (_n.vars = {});

  /*函数名称空间 */
  _n.func || (_n.func = {});

  /*页面脚本空间 */
  _n.app || (_n.app = {});

  /*调试开关 */
  _n.isDebug = 0;
  _n.DELAY_FAST = 0;
  _n.DELAY_DOM = 1;
  _n.DELAY_DOMORLOAD = 2;
  _n.DELAY_LOAD = 3;
  _n._delayArr = [[], [], [], []];
  _n.delayRun = function(level, req, fn) {
    fn || (fn = [req, req = []][0]);
    if (typeof req === 'string') {
      req = req.split(',');
    }
    this._delayArr[level].push([req, fn]);
    return void 0;
  };
  _n._hookData = {};
  _n.hook = function(name, call, isOverwrite) {
    var args;
    if (call && call.apply) {
      if (this._hookData[name] && !isOverwrite) {
        return false;
      }
      this._hookData[name] = call;
      return true;
    } else {
      if (call = [this._hookData[name], args = [].concat(call) || []][0]) {
        return call.apply(null, args);
      }
    }
  };
  return _n;
}).call(this.N = this.ndoo = this.ndoo || {});


/* ios 过滤 */

turbolinks_init_filter = function() {
  var ret;
  ret = true;
  if (navigator.userAgent.match(/HUAZHU\/ios/)) {
    ret = false;
  }
  return ret;
};


/* 内容过滤 */

turbolinks_content_filter = function(content) {
  var i, item, len, skips;
  skips = content.querySelectorAll('[data-turbolinks-skip]');
  if (skips.length) {
    for (i = 0, len = skips.length; i < len; i++) {
      item = skips[i];
      item.parentNode.removeChild(item);
      item = null;
    }
  }
  return content;
};


/* 状态过滤 */

turbolinks_popstate_filter = function(state) {
  var inject;
  inject = ndoo.storage('pushStateInject');
  if ((state && state.inject) || inject >= 1) {
    ndoo.func.pushStateCallback(state);
    return true;
  }
};


/* 重定向过滤 */

turbolinks_redirect_filter = function(xhr) {
  if (xhr != null ? typeof xhr.getResponseHeader === "function" ? xhr.getResponseHeader('X-XHR-Force-Redirect') : void 0 : void 0) {
    return true;
  }
};


/* url 同源策略过滤 */

turbolinks_url_filter = function(url) {
  var protocalCheck, protocalMatch, protocol, urlCheck;
  protocol = document.location.protocol;
  protocalMatch = url.match(/^(http(?:s|):)\/\//);
  protocalCheck = protocalMatch && protocol !== protocalMatch[1] ? false : true;
  urlCheck = true;
  if (ndoo && ndoo.vars.newyearStyle) {
    if (ndoo.pageId && ndoo.pageId === 'home/index') {
      urlCheck = false;
    }
  }
  return urlCheck && protocalCheck;
};
