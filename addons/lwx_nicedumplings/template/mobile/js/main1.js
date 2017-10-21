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
    //g('heka').className = 'heka';
    s2.start();
})

//粽子包起的动作
s2.add(1,function(){
    g('c_zongzi_box').className = 'c_zongzi_box';
})

//贺卡消失，粽子出现 按钮更换
s2.add(500,function(){
    g('main').className = 'main1 main1_out';
    g("c_diye").className = 'c_diye1 c_diye1_out';
    g("c_zongzirou").className = 'c_zongzirou1 c_zongzirou1_out';
})

//粽叶变换
s2.add(1500,function(){
    g("c_diye").className = 'c_diye1 c_diye1_in';
    g("c_youye").className = "c_youye1 c_youye1_out";
    g("c_zuoye").className = "c_zuoye1 c_zuoye1_out";
})
s2.add(2400,function(){
    g("c_zongzirou").className = 'c_zongzirou1';
    g("c_zuoye").className = 'c_zuoye1  c_zuoye1_in';
    g("c_youye").className = 'c_youye1  c_youye1_in';
    g("c_zongzirou").className = 'c_zongzirou1 c_zongzirou1_in';
    g("c_zongzi").className = 'c_zongzi1 c_zongzi1_out';
})
//绳子变换
s2.add(2500,function(){
    g("c_shengzi").className = 'c_shengzi1_4'
})
s2.add(3000,function(){
    g("c_shengzi").className = 'c_shengzi1_3'
})
s2.add(3500,function(){
    g("c_shengzi").className = 'c_shengzi1_2'
})
s2.add(4000,function(){
    g("c_shengzi").className = 'c_shengzi1_1';
    g('bozongzi_song').className = 'bozongzi_song bozongzi_song_in';
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




