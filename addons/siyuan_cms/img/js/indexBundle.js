var SimpleTemplate = function(e) {
  this.init(e)
};
SimpleTemplate.prototype = {
  data: {},
  tags: [],
  regexp: {
    logic: /\s*\[#(if|foreach):(.*?)#\]([\s\S]*?)\[#\/\1#\]\s*/g,
    common: /\[#(.+?)#\]/g
  },
  validActions: {
    include: 1,
    js: 2,
    string: 3,
    "int": 3,
    "boolean": 3,
    "float": 3,
    money: 3,
    foreach: 4,
    "if": 5
  },
  init: function(e) {
    this.template = e || ""
  },
  replaceCommonTag: function(e, t) {
    var a = [],
    i = 0,
    n = this;
    $.each(t.split(":"),
    function(e, t) {
      if (t in n.validActions) {
        $.inArray(t, a) === -1 && a.push(t);
        i += t.length + 1
      } else {
        return false
      }
    });
    t.indexOf("||") > -1 && $.inArray("js", a) === -1 && a.push("js");
    if (a.length > 0) {
      t = t.substr(i);
      for (var r = a.length - 1,
      s = t; r >= 0; r--) {
        switch (this.validActions[a[r]]) {
        case 1:
          s = this.includeTag(s);
          break;
        case 2:
          s = this.jsTag(s);
          break;
        case 3:
          s = this.dataTypeTag(s, a[r]);
          break;
        default:
          s = ""
        }
      }
      return s
    } else if (t.indexOf(":") === -1 && (this.tags.length === 0 || $.inArray(t.split(".")[0], this.tags) === -1)) {
      return this.getTagValue(t)
    } else {
      return e
    }
  },
  replaceCommonTags: function(e) {
    var t = this;
    return e.replace(this.regexp.common,
    function(e, a) {
      return t.replaceCommonTag(e, a)
    })
  },
};
jQuery.renderContent = function() {
  var e = new SimpleTemplate;
  return $.proxy(e.renderContent, e)
} ();
jQuery.slideLoader = function(e) {
  e = e || {};
  $.each(["before", "success", "error"],
  function(t, a) {
    e[a] = $.isFunction(e[a]) ? e[a] : function() {}
  });
  e.page = e.page !== undefined ? convertType(e.page, "int") : 1;
  e.totalPage = e.totalPage !== undefined ? convertType(e.totalPage, "int") : -1;
  e.mode = e.mode || "page";
  e.type = String(e.type).toUpperCase(),
  e.type = $.inArray(e.type, ["GET", "POST"]) !== -1 ? e.type: "GET";
  e.container = e.container || $(window);
  e.event = e.event || "scroll";
  if (!e.url) {
    return false
  } else if (e.mode === "page") {
    if (e.totalPage === 0 || e.totalPage > 0 && e.page >= e.totalPage) {
      return false
    }
  }
  e.container.bind(e.event, e.callback);
  e.event === "scroll" && e.container.trigger("scroll");
  return e
};
var Lists = {
  showLeftTime: function(e) {
    return $.showLeftTime({
      sec: e.sec,
      step: function(e) {
        e.tag = this.params.tag;
        var t = $("#seckill_time-tpl").renderTpl(e);
        $(".seckill-title .date").html(t)
      },
      end: function() {
        location.reload()
      }
    },
    e)
  },
};
var ListsEvent = {
  bindIndexEvent: function() {
    $.slideLoader({
      before: function(e) {
        if (e.type !== "scroll" || $('#list_data .col-title a[data-type="new"]').is(".cur")) {
          this.wrap.data("status", "load").is(":visible") && $(".loading-text").show()
        } else {
          return false
        }
      },
      success: Lists.loadIndexList,
      error: function() {
        this.wrap.data("status", "hide").is(":visible") && $(".loading-text").hide()
      }
    });
    var e = $(".list-ul[data-type]").data("status", "hide").filter('[data-type="recommend"]');
    window.totalPage <= 1 && e.data("status", "end") && $(".load-more").show();
    $("#list_data .col-title a[data-type]").click(this.changeShowList);
    $(".sort-nav a").click(this.jumpCat);
    $(".channel-nav a").click(function() {
      _gaq.push(["_trackEvent", "Index-品类", "click", "cat-" + getUrlParam("cat", $(this).attr("href"))])
    });
    $(".list-ul a").live("click",
    function() {
      _gaq.push(["_trackEvent", "Index-推荐", "click", "goodsId-" + getUrlParam("id", $(this).attr("href"))])
    });
    getLocation();
    Lists.showLeftTime(window.miaosha)
  },
  changeShowList: function() {
    var e = $(this);
    var t = e.attr("data-type");
    var a = e.parent().siblings('.list-ul[data-type="' + t + '"]');
    if (!e.is(".cur")) {
      e.siblings(".cur").removeClass("cur").end().addClass("cur");
      a.siblings("ul:visible").hide().end().show();
      var i = a.data("status");
      var n = $(".loading-text").hide();
      var r = $(".load-more").hide();
      i === "load" && n.show();
      i === "end" && r.show()
    }
    if (t === "new" && a.children().length === 0) {
      $(window).trigger("load_newList")
    }
    return false
  },
  jumpCat: function() {
    var e = $(this).attr("href");
    if (getCookie("cur_lng") && getCookie("cur_lat")) {
      e = UrlInfo.keepParam(e, ["sort"], {
        sort: "distance"
      })
    }
    _gaq.push(["_trackEvent", "Index-分类", "click", "cat-" + getUrlParam("cat", e)]);
    location.href = e;
    return false
  }
};
$(function() {
  switch (window.exec_page) {
  case "list":
    ListsEvent.bindListEvent();
    break;
  case "index":
    ListsEvent.bindIndexEvent();
    break
  }
  $(".pic img").lazyload({
    effect:
    "fadeIn",
    threshold: 200
  })
});