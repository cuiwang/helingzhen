var tabber = {
    selector : "",
    options : [],
    params : {

    },
    infiniteConfig : {

    },

    loadingStatus : {},
    page : {},

    init : function (options,selector,infinite) {
        //加载器...
        if(infinite){
            tabber.infiniteConfig.infiniteScroll = infinite.infiniteScroll;
            tabber.infiniteConfig.loader = infinite.loader;
        }
        tabber.selector = selector;
        if(options){
            //tabber.options = options;

            var len = options.length;
            var cla = "col-33";
            if(len == 2) cla = "col-50";
            if(len == 4) cla = "col-25";
            if(len == 5) cla = "col-20";

            var html = '<div class=" common-tab"><div class="row">';
            var initCallback = null;
            $.each(options, function (index, option) {
                tabber.params[option.id] = option.param;
                tabber.options[option.id] = option;
                tabber.loadingStatus[option.id] = false;
                tabber.page[option.id] = 0;
                if(option.init == true){//默认回调函数
                    initCallback = option.callback;
                }

                var clazz = cla;
                if(option.active == true)clazz = cla + " active";
                var t = '<div class="tab '+clazz+'"';
                $.each(option, function (index, o) {
                    if(index != "callback") t = t + ' data-' + index + '="'+o+'"';
                });

                t = t + '> <span class="">'+option.name+'</span> </div>';
                html = html + t;
            });
            console.log(tabber.options);
            $(selector).prepend(html);

            var initBtn = $(selector+" .tab[data-init=true]");
            //初始化数据
            if(initBtn.length > 0){
                var optionId = initBtn.data("id");
                tabber.loadPage(optionId, function () {
                    if(tabber.options[optionId].callback){
                        var callback = tabber.options[optionId].callback;

                        callback();
                    }
                });
            }

        }

        $(selector).on("click",".tab", function (e) {
            e.stopPropagation();

            var optionId = $(this).data("id");
            var option = tabber.options[optionId];
            if(tabber.options[optionId].empty){
                $('.empty').hide();
            }

            //移除其他的active
            $(selector+" .tab").removeClass("active");
            $(selector+" .tab").attr("data-active","false");
            $(this).addClass("active");
            $(this).attr("data-active","true");
            var btn = $(this);
            var name = $(this).data("name");
            if(btn.data("auto") == true){
                tabber.page[optionId] = 0;
                tabber.loadPage(optionId, function () {
                    if(tabber.options[optionId].callback){
                        var callback = tabber.options[optionId].callback;
                        callback();
                    }
                });

            }
        });


        //下拉
        if(infinite){
            var infiniteSelector = tabber.infiniteConfig.infiniteScroll;
            $$(infiniteSelector).on('infinite', function () {
                var currentTab = $(selector).find(".tab.active");
                var id = currentTab.data("id");
                tabber.loadPage(id, function () {
                    if(tabber.options[id].callback){
                        var callback = tabber.options[id].callback;
                        callback();
                    }
                });

            });
        }
    },

    //加载一页数据
    loadPage : function (optionId,callback) {
        console.log("下拉...");
        var currentTab = $(tabber.selector).find(".tab.active");
        var options = tabber.options[optionId];
        var infinite = options.infinite;
        var infiniteScroll = tabber.infiniteConfig.infiniteScroll;
        var requestApi = options.request;
        var templateID = options.template;
        var appendTo = options.append;
        if(tabber.page[optionId] == 0){
            $(appendTo).empty();
        }
        $(tabber.infiniteConfig.loader).show();
        if (tabber.loadingStatus[optionId] == true) {
            if(tabber.options[optionId].empty){
                $(tabber.options[optionId].empty).show();
            }
            $(tabber.infiniteConfig.loader).hide();
            return false;
        };
        //var requestParam =
        if(options.infinite == true){
            tabber.loadingStatus[optionId] = true;
            var data = tabber.params[optionId];
            data.page = (parseInt(tabber.page[optionId]) + 1);
            if(options.wmap){
                data.lng = 121.446235;
                data.lat = 31.169152;
            }
            var activeFilter = $("body").find(".common-tab .tab[data-active='true']");

            $.get(requestApi, data, function (res) {
                var json = JSON.parse(res);
                //var json = res;
                if(json.status == 200){
                    //if(json.data == null || json.data.length < 20){
                    //    // 删除加载提示符
                    //    $(tabber.infiniteConfig.loader).hide();
                    //}
                    if(json.data.length > 0){
                        setTimeout(function () {
                            var html = template(templateID,json);
                            $(appendTo).append(html);
                            $(tabber.infiniteConfig.loader).hide();
                            tabber.page[optionId] = parseInt(tabber.page[optionId]) + 1;
                            tabber.loadingStatus[optionId] = false;
                            if(callback) callback(json);
                        },1000);
                    }else{
                        if(tabber.page[optionId] == 0){
                            if(tabber.options[optionId].empty){
                                $(tabber.options[optionId].empty).show();
                            }
                        }
                        $(tabber.infiniteConfig.loader).hide();
                        if(callback) callback(json);
                    }

                }else{
                    layer.msg(json.message);
                }
            });
        }else{
            tabber.loadingStatus[optionId] = false;
            $(tabber.infiniteConfig.loader).hide();
            console.log(" none event ...");
        }
    },


};