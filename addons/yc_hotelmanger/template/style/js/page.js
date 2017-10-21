/**
 * 1. 不支持打开传递数据，通过闭包的方式可以达到传递数据的目的
 * 2. 执行fn，只传递了当前页面的名字，前一个页面名称没有传递
 * 3. 连续关闭只能通过setTimeout方法，或者add方法传递的函数中处理（推荐使用这种方式，有一个关闭标记符就好）
 * 4. 没有标志页面正在打开状态
 **/
(function () {
    var buildUrl = function () {
        return "";
    };
    function buildUrl2(name) {
        var url = location.href,
            match = url.match(/(\?|&)pageName=[^&#]*/),
            index;
        if (match) {
            index = match.index;
            url = url.substring(0, index) + match[1] + "pageName=" + name + url.substring(index + match[0].length);
        } else {
            index = url.indexOf("#");
            if (index === -1) {
                index = url.length;
            }
            url = location.href.substring(0, index) + (url.indexOf("?") === -1 ? "?" : "&") + "pageName=" + name + url.substring(index);
        }
        return url;
    }

    var getPageName = function (e) {
        if (e.state) {
            return e.state.page;
        }
    };
    function getPageName2() {
        var match = location.href.match(/(\?|&)pageName=([^&#]*)/);
        if (match) {
            return match[2];
        }
    }

    var pages = ["main"];
    function openPage(name) {
        var current = getter(name);
        if (current.length === 0) { // 没有page组件进行页面切换的时会出现元素找不到的情况
            return;
        }
        var previous = getter(pages[pages.length - 1]).data("scrollTop", $(window).scrollTop());
        current.css({
            display: "block"
        });
        setTimeout(function () {
            current.addClass("current");
        }, 20);
        $(window).scrollTop(0); // 可以通过动画实现可流畅的效果
        setTimeout(function () {
            previous.css({
                display: "none"
            });
        }, 220);
        pages.push(name);
        setTimeout(function () {
            doPageFn(name);
        }, 220);
    }
    function closePage(name) {
        var current = getter(name);
        if (current.length === 0) { // 没有page组件进行页面切换的时会出现元素找不到的情况
            return;
        }
        current.css({
            display: "block"
        });
        var previous = getter(pages.pop()).removeClass("current");
        setTimeout(function () {
            previous.css({
                display: "none"
            });
            $(window).scrollTop(current.data("scrollTop"));
        }, 200);
        setTimeout(function () {
            doPageFn(name);
        }, 200);
    }

    function doPageFn(name) {
        var fn = fns[name];
        if (fn) {
            for (var i = 0, len = fn.length; i < len; i++) {
                fn[i](name);
            }
        }
    }

    $(window).on("popstate", function (e) {
        var name = getPageName(e);
        if (!name) {
            name = pages[0];
        }
        if (pages[pages.length - 1] === name) {
            // 什么都不做
        } else if (pages[pages.length - 2] === name) {
            closePage.call(page, name);
        } else {
            openPage.call(page, name);
        }
    });
    var getter = function (name) {
        // 会存在给出name不是正确的元素选择器，直接使用$("#" + name + "Page")，会报错
        return $(document.getElementById(name + "Page"));
    },
        fns = {};
    var page = {
        init: function (param) {
            param = param || {};
            getter = param.getter || getter;
            pages[0] = param.main || pages[0];

            doPageFn(pages[0]);

            if (!param.open) {
                return;
            }
            buildUrl = buildUrl2;
            getPageName = getPageName2;
            var name = getPageName();
            if (name && name !== pages[0]) { // 没有pageName参数或者值为空的情况，是主页面
                var pageMap = param.pageMap;
                if (pageMap) {
                    var i,
                        len,
                        index;
                    if ($.isArray(pageMap[0])) {
                        for (i = 0, len = pageMap.length; i < len; i++) {
                            index = pageMap[i].indexOf(name);
                            if (index !== -1) {
                                pageMap = pageMap[i];
                                break;
                            }
                        }
                    } else {
                        index = pageMap.indexOf(name);
                    }
                    for (i = 0; i <= index; i++) {
                        openPage.call(this, pageMap[i]);
                    }
                    if (index === -1) {
                        openPage.call(this, name);
                    }
                }
            }
        },
        open: function (name, url) {
            if (name === pages[pages.length - 1]) {
                doPageFn(name);
            } else {
                history.pushState({
                    page: name
                }, "", url || buildUrl(name));
                openPage.call(this, name);
            }
        },
        close: function () {
            history.go(-1);
        },
        refresh: function () {
            // 只执行函数
            doPageFn(pages[pages.length - 1]);
        },
        add: function (name, fn) {
            if (typeof fn !== "function") {
                return;
            }
            if (!fns[name]) {
                fns[name] = [fn];
            } else {
                fns[name].push(fn);
            }
        },
        remove: function (name, fn) {
            if (fns[name]) {
                for (var i = fns[name].length - 1; i >= 0; i++) {
                    if (fns[name][i] === fn) {
                        fns[name].splice(i, 1);
                        break;
                    }
                }
            }
        }
    };
    window.page = page;
})();