/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};

/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {

/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId])
/******/ 			return installedModules[moduleId].exports;

/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			exports: {},
/******/ 			id: moduleId,
/******/ 			loaded: false
/******/ 		};

/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);

/******/ 		// Flag the module as loaded
/******/ 		module.loaded = true;

/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}


/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;

/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;

/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";

/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ function(module, exports) {

	'use strict';

	/**
	 * @fileOverview 收藏线路
	 *
	 * ```js
	 *   import Favorite from './favorite';
	 *   new Favorite($('.favorite'));
	 * ```
	 */

	// 默认配置项
	var defaultSettings = {
	  url: {
	    // 初始化
	    // 返回与id一一对应的收藏状态 [{favorited:1}, {favorited:0}] 1为已收藏
	    init: '../data/common/favorite/init.json',
	    // 收藏、取消收藏
	    // 操作是否成功。 {status : 1}  1为成功
	    toggle: '../data/common/favorite/toggle.json'
	  },
	  // 异步类型
	  dataType: 'json'
	};

	/**
	 * 收藏构造函数
	 * @param {Object} actions
	 *                 是每个按钮的jquery对象 $('<div class="favorite" data-id="1000"><div class="text"></div></div>')
	 *                 - data-id为收藏的id
	 *                 - .num用于填充已收藏的数量
	 * @param {Object} settings 设置url配置
	 * @param {Function} getTextByStatus 根据收藏状态返回对应的文本。
	 *
	 */
	function Favorite(actions, settings) {
	  var getTextByStatus = arguments.length <= 2 || arguments[2] === undefined ? function (favorited) {
	    return favorited ? '已收藏' : '未收藏';
	  } : arguments[2];

	  // 没有相关按钮
	  if (!actions || !actions.length) return null;

	  this.actions = actions;
	  this.settings = $.extend(defaultSettings, settings);
	  this.getTextByStatus = getTextByStatus;
	  return this.init();
	}

	Favorite.prototype = {
	  /**
	   * 初始化每个按钮的收藏状态，并且绑定按钮点击事件。
	   */

	  init: function init() {
	    // 如果已登录，初始化每个按钮的状态
	    if (this.isLogin()) this._init();

	    var that = this;
	    this.actions.on('click', function (e) {
	      that.trigger($(this));
	    });

	    return this;
	  },

	  /**
	   * 初始化每个按钮的状态
	   */
	  _init: function _init() {
	    var ids = [];
	    this.actions.each(function (i, action) {
	      var id = $(action).data('id');
	      if (typeof id == 'undefined') throw new Error('Favorite need define data-id.');
	      ids.push($(action).data('id'));
	    });

	    // 开始初始化
	    this.actions.addClass('favorite-loading');
	    var that = this;
	    $.ajax({
	      url: this.settings.url.init,
	      dataType: that.settings.dataType,
	      data: {
	        hotelid: ids.join(',')
	      },
	      cache: false,
	      success: function success(data) {
	        that.actions.removeClass('favorite-loading');
	        if (!data || !data.length) return;

	        // 设置每个收藏按钮的已收藏状态。
	        for (var i = 0; i < data.length && i < that.actions.length; i++) {
	          // 当前按钮
	          var action = $(that.actions[i]);
	          // 是否收藏
	          var favorited = data[i].favorited == 1;

	          // 设置状态外观
	          that.setAction(action, favorited);
	        }
	      },
	      error: function error() {
	        that.actions.removeClass('favorite-loading');
	      }
	    });
	  },

	  /**
	   * 是否登陆
	   * @return {Boolean} 登陆则为true
	   */
	  isLogin: function isLogin() {
	    return true;
	    var memberid = void 0;
	    if (fish) {
	      memberid = fish.cookie.get('us', 'userid');
	    } else {
	      memberid = $.cookie('us');
	      memberid = getQuery('userid', '', '', memberid);
	    }

	    // 获取 userid=1000&a=1&b=2 这种字符串格式中的userid中对应的值。
	    function getQuery(name, def, symbol, url) {
	      symbol = symbol || '?';
	      url = url || location.href;
	      var reg = new RegExp("(^|\\" + symbol + "|&)" + name + "=([^&]*)(\\s|&|$)", "i");
	      if (reg.test(url)) return unescape(RegExp.$2.replace(/\+/g, " "));

	      return def;
	    }
	    return !!memberid;
	  },

	  /**
	   * 根据状态设置按钮外观
	   * @param {Object} action    按钮
	   * @param {Boolean} favorited 是否收藏
	   */
	  setAction: function setAction(action, favorited) {
	    // 设置状态外观
	    action.toggleClass('favorited', favorited);
	    $('.text', action).html(this.getTextByStatus(favorited));
	  },
	  trigger: function trigger(action) {
	    // 未登录跳转链接登录
	    var that = this;
	    if (!that.isLogin()) {
	      location.href = '//passport.ly.com/?pageurl=' + encodeURIComponent(location.href);
	      return;
	    }

	    var id = action.data('id'),
	        favorited = action.hasClass('favorited');

	    // 进行按钮的收藏、取消收藏
	    action.addClass('favorite-loading');
	    $.ajax({
	      url: that.settings.url.toggle,
	      data: {
	        favouriteId: favorited ? 0 : 1,
	        hotelId: id
	      },
	      dataType: that.settings.dataType,
	      cache: false,
	      success: function success(data) {
	        action.removeClass('favorite-loading');

	        // 设置成功则设置外观
	        if (data && data[0].state == 1) {
	          that.setAction(action, !favorited);
	        } else {
	          //收藏失败
	          var dd = $.dialog({
	            autoOpen: false, //是否自动打开
	            closeBtn: true, //是否显示标题上面的关闭按钮
	            buttons: {
	              '取消': function _() {
	                this.close();
	              },
	              '确定': function _() {
	                this.close();
	              } //r如果只需要一个按钮就配一个。
	            },
	            title: '收藏提示', //头部内容，不配不显示
	            content: "收藏没有成功~" //'<p>请使用同程登录后, 获得更多个性化特色功能</p>'
	          });
	          dd.open();
	        }
	      },
	      error: function error() {
	        action.removeClass('favorite-loading');
	      }
	    });
	  }
	};

	/*export {Favorite};*/

	/*if (typeof module !== "undefined" && module.exports) {
	  module.exports = Favorite;
	} else if (typeof define === "function" && define.amd) {
	  define(function(){return Favorite;});
	} else {
	  
	}*/
	window.Favorite = Favorite;

/***/ }
/******/ ]);