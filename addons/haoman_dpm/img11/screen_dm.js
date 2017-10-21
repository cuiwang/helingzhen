(function(k, e) {
    var d = ["#微笑#", "#喜欢#", "#晕#", "#尴尬#", "#汗#", "#惊讶#", "#郁闷#", "#疑问#", "#书呆子#", "#悲伤#", "#口罩#", "#再见#", "#冷#", "#奸诈#", "#困#", "#被打#", "#财迷#", "#大哭#", "#无聊#", "#中毒#", "#可爱#", "#呲牙#", "#大笑#", "#馋#", "#吵闹#", "#愤怒#", "#怀疑#", "#闭嘴#", "#鄙视#", "#不屑#", "#色#", "#无聊#", "#斜眼#", "#酸#", "#亲#", "#恐吓#", "#左鄙夷#", "#右鄙夷#", "#嘘#", "#委屈#", "#可怜#", "#感动#", "#酷#", "#逗趣#", "#黑#"];
    var i = function(C, B) {
        e.each(d, 
        function(D, E) {
            if (C.indexOf(E) > -1) {
                flg = true;
                C = C.replace(new RegExp(E, "g"), p(D, B))
            }
        });
        return C
    };
    var w;
    var s = 64;
    var p = function(C, B) {
        if (!w) {
            w = e(".faceicon")
        }
       return '<span class="faceicon" style="width:' + B + "px;height: " + B + 'px;display: inline-block"><img style="width:' + (2880 * B/s) + "px;height:" + B + "px;left:-" + B * C + 'px" src="' + w.find(".icon-seed").attr("src") + '" ></span>'
    };
    var A = k.WBActivity.resize = function() {
        if (!g) {
            return
        }
        var C = 20;
        var B = g.height();
        h.each(function(G, I) {
		 
		})
    };
    function y(C, E, B, F, D) {

    var pageW=parseInt($("#boxDom").width());
        var pageH=parseInt($("#boxDom").height());
        var Top,Right;
        var width;
        width=pageW;

            var creSpan=$("<ul class='string d_show'></ul>");

        // creSpan.html('<li><div class="tmtx"><img src="' + E +'"></div><div class="tmwz"><p class="tmnc">'+B+'</p><p class="tmnr">'+i(F,60)+'</p></div></li>');
           creSpan.html('<li><div class="tmtx"><img src="' + E +'"></div><div class="tmwz"><p class="tmnc">'+B+'</p><p class="tmnr">'+F+'</p></div></li>');
            Top=parseInt(pageH*(Math.random()));


            creSpan.css({"top":Top,"right":-300,"color":"#FFF"});
            $("#boxDom").append(creSpan);
            var spanDom=$("#boxDom>ul:last-child");
			 var num=parseInt(30000*(Math.random()))+5000;
            spanDom.stop().animate({"right":pageW+300},num,"linear",function(){
                        $(this).remove();
                    });
        
		
	 
    }
    var a = 0;
    var h;
    function o(E, C, F, D) {
        var B = e(h[a]);
 
		 y(B, E, C, F, D);
    }
    var q = 0;
    function l(E, C, F, D) {
        h = g.children();

        if (q <= x) {
            var B = e(h[q]);
            B.fadeIn();
            y(B, E, C, F, D)
        } else {
            var B = e(h[0]);
 
			 y(B, E, C, F, D);
        }
        q++
    }
    function t() {
        var B;
        if (r.length > 0) {
            if (z == 0) {
                B = r.pop()
            } else {
                B = r.shift()
            }
            v.push(B)
        } else {
            if (v.length > 0 && j > 3) {
                B = u()
            }
        }

        if (B) {
            if (b == "0") {
                l(B.avatar, B.nickname, B.word, B.id)
            } else {
                o(B.avatar, B.nickname, B.word, B.id)
            }
        }
    }
    function u() {
        var C = v.length,
        D = Math.floor(Math.random() * C),
        E = v[D],
        B = e(".MsgItem[msg_id=" + E.id + "]", ".Panel.MsgList");
        if (B.length <= 0) {
            return E
        } else {
            return u()
        }
    }
    var f = 1;
    function n(B) {
        e.getJSON(dpm_dm_getmessages, {
            last_id: f
        },
        function(C) {
            if (C && C.ret == 1 && e.isArray(C.data)) {
                if (C.data.length > 0) {
                    r = C.data.concat(r);
                    f = C.data[0].id
                }
            }

        }).complete(function() {
            if (B && typeof B == "function") {
                B.call()
            }
        })
    }
    var g,
    x;
    var r = [],
    v = [];
    var m = 3000,
    b = 0,
    z = 1;
    var j = 50;
    var c = k.WBActivity.start = function() {
        g = e(".Panel.MsgList"),
        x = g.children().length - 1,
        h = g.children();

        n(function() {

            k.WBActivity.hideLoading();
            e(".Panel.Top").css({
                top: 0
            });
            e(".Panel.Bottom").css({
                bottom: 0
            });
            e(".Panel.MsgList").css({
                display: "block",
                opacity: 1
            });

            A();
            //h.hide();
            t();
            // t();
            // t();

            k.setInterval(function() {
                t()
            },m);

            k.setInterval(function() {
                n()
            },3000)
        })
    }
})(window, jQuery);