/**
 * Created by cxt on 2016/5/28 0028.
 */

//剥粽子

/*获取id*/
var g = function(id){
    return document.getElementById(id);
}
/*时间轴*/
var TimeLine = function(){
    this.order = [];
    this.add = function(timeout, func, log){
        this.order.push({
            timeout:timeout,
            func:func,
            log:log
        })
    }
    this.start = function(ff){  //执行到指定的时间位置，相当于快进
        for(s in this.order){
            (function(me){
                var fn = me.func;
                var timeout = me.timeout;
                var log = me.log;
                timeout = Math.max(timeout-(ff||0),0);
                setTimeout(fn, timeout);
                setTimeout(function(){
                    console.log('time==',timeout,'log==',log)
                }, timeout);
            })(this.order[s])
        }
    }
}
//初始动作，场景
var s1 = new TimeLine();    //构造函数
//粽子展开的场景
var s2 = new TimeLine();
/*1.粽子不停的在抖动
 * 2.剥粽子按钮点击触发绳子事件
 */
s1.add(1,function(){
    g('angel').className = 'angel angel1';
    //g('c_zongzi_box').className = 'c_zongzi_box c_zongzi_box_rock';
    g('c_bozongzi').onclick = function(){
        s2.start();
    }
})
//粽子展开的动作
s2.add(1,function(){
    g('c_zongzi_box').className = 'c_zongzi_box';
})

//飞机等消失 粽子出现
s2.add(100,function(){
    g('angel').className = 'angel angel1_in';
    g('main').className = 'main main_out';
    g('bozongzi_bo').className = 'bozongzi bozongzi_bao_in bozongzi_bao1';

})

//绳子变换
s2.add(500,function(){
    g("c_shengzi").className = 'c_shengzi_2'
})
s2.add(1000,function(){
    g("c_shengzi").className = 'c_shengzi_3'
})
s2.add(1500,function(){
    g("c_shengzi").className = 'c_shengzi_4'
})
s2.add(2000,function(){
    g("c_shengzi").className = 'c_shengzi_0'
})
//粽叶变换
s2.add(2500,function(){
    g("c_zongzi").className = 'c_zongzi_out';
    g("c_zongzirou").className = 'c_zongzirou c_zongzirou_in';
    g("c_zuoye").className = 'c_zuoye c_zuoye_in';
    g("c_youye").className = 'c_youye c_youye_in';
})
s2.add(3500,function(){
    g("c_zuoye").className = "c_zuoye c_zuoye_in c_zuoye_out";
    g("c_youye").className = "c_youye c_zuoye_in c_youye_out";
    g("c_diye").className = 'c_diye c_diye_in';
})

s2.add(4000,function(){
    g('main').className = 'main main_in main2';
    g('angel').className = 'angel2';
    g('zhufu_Warp').className = 'zhufu_Warp zhufu_Warp_in';
    g('bozongzi_bao').className = 'bozongzi_song bozongzi_song_in';
})


//图片预加载
var imgs = ['../addons/lwx_nicedumplings/template/mobile/img/zzr_2.png','../addons/lwx_nicedumplings/template/mobile/img/zzr_3.png','../addons/lwx_nicedumplings/template/mobile/img/zzr_4.png'];
var imgs_onload = function(){
    imgs.pop();
    if (imgs.length == 0) {
        s1.start()
    };
}
for(s in imgs){
    var img = new Image;
    img.onload = imgs_onload;
    img.src = imgs[s];
}

