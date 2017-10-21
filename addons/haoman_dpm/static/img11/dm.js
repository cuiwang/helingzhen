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

    var face = {
        faceUrl: '../addons/haoman_base/dpm/face',
        arr: ["[笑哈哈]", "[得瑟]", "[得意地笑]", "[转圈]", "[挤地铁]", "[我忍了]", "[粉爱你]", "[粉红兔火车]", "[转圈圈]", "[鼓掌]", "[压力]", "[抢镜]", "[草泥马]", "[神马]", "[多云]", "[给力]", "[围观]", "[v5]", "[小熊猫]", "[粉红兔微笑]", "[动感光波]", "[囧]", "[互粉]", "[礼物]", "[微笑]", "[呲牙笑]", "[大笑]", "[羞羞]", "[小可怜]", "[抠鼻孔]", "[惊讶]", "[大眼睛羞涩]", "[吐舌头]", "[闭嘴]", "[鄙视]", "[爱你哦]", "[泪牛满面]", "[偷笑]", "[嘴一个]", "[生病]", "[装可爱]", "[切~]", "[右不屑]", "[左不屑]", "[嘘]", "[雷人]", "[呕吐]", "[委屈]", "[装可爱]", "[再见]", "[疑问]", "[困]", "[money]", "[装酷]", "[色眯眯]", "[ok]", "[good]", "[nonono]", "[赞一个]", "[弱]"],
        init: function(list) {
            var arr = this.arr;
            for (var i = 0; i < list.length; i++) {
                var val = list[i].innerHTML;
                for (var j = 0; j < arr.length; j++) {
                    while (val.indexOf(arr[j]) != -1) {
                        val = val.replace(arr[j], '<img style="width:30px;height:30px;" src="' + this.faceUrl + '/' + (j + 1) + '.png" />');

                    }
                }
                list[i].innerHTML = val;
            }
        },
        replaceText: function(val) {
            if (val != null && val != '') {
                var arr = this.arr;
                for (var i = 0; i < arr.length; i++) {
                    while (val.indexOf(arr[i]) != -1) {
                        val = val.replace(arr[i], '<img style="width:30px;height:30px;" src="' + this.faceUrl + '/' + (i + 1) + '.png" />');
                    }
                }
            }
            return val;
        }
    }

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
            //console.log(z)

        if (r.length > 0) {
            if (z == 0) {
                B = r.pop()
            } else {
                B = r.shift()
            }
            v.push(B)
        } else {
            //console.log(333)
            if (v.length > 0 && j > 3) {
                B = u()
            }
        }

        if (B) {
            if (b == "0") {
                l(B.avatar, B.nickname, face.replaceText(cutstr(B.word,400)), B.id)
            } else {
                o(B.avatar, B.nickname, face.replaceText(cutstr(B.word,400)), B.id)
            }

        }
    }

    function cutstr(str,len){
       var str_length = 0;
       var str_len = 0;
          str_cut = new String();
          str_len = str.length;
          for(var i = 0;i<str_len;i++)
         {
            a = str.charAt(i);
            str_length++;
            if(escape(a).length > 4)
            {
             //中文字符的长度经编码之后大于4
             str_length++;
             }
             str_cut = str_cut.concat(a);
             if(str_length>=len)
             {
             str_cut = str_cut.concat("...");
             return str_cut;
             }
        }
        //如果给定字符串小于指定长度，则返回源字符串；
        if(str_length<len){
         return  str;
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
    var f = 0;
    function n(B) {
        e.getJSON(dpm_dm_getmessages, {
            last_id: f
        },
        function(C) {
            
            if (C && C.ret == 1 && e.isArray(C.data)) {
                if (C.data.length > 0) {
                    r = C.data.concat(r);
                    f = C.data[0].id;
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

            e(".Panel.Bottom").css({
                bottom: 0
            });
            e(".Panel.MsgList").css({
                display: "block",
                opacity: 1
            });

            A();
            h.hide();
            t();
            t();
            t();

            k.setInterval(function() {
                 t();
            },m);

            k.setInterval(function() {
                n()
            },3000)
        })
    }
})(window, jQuery);