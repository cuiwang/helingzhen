
$(function(){
    // TODO banner轮播效果
    try{
        //TODO 运动函数
        function runToFn(tarIndex){
            bannerSlider.stop(true,true).animate({
                'left':-tarIndex*100+'%'
            },900,'easeInOutExpo',function(){
                curIndex=tarIndex;
                //TODO banner导航状态值的改变
                var PageitemS=$('#indexBControls').children();
                var navIndex=(tarIndex+initLen-initIndex)%initLen;//计算当前是第几张
                PageitemS.removeClass('cur');
                PageitemS.filter(':eq('+navIndex+')').addClass('cur');
            });
        }

        var bannerSlider=$('#slider');
        var liS = bannerSlider.children();
        var initLen = liS.length;
        var _this = $(this);
        var gtValue=initLen - 3;
        if(gtValue<0){
            bannerSlider.prepend(liS.clone());
        }else{
            bannerSlider.prepend(liS.filter(':gt(' + gtValue + ')').clone());
        }
        bannerSlider.append(liS.filter(':lt(2)').clone());
        liS = bannerSlider.children();
        var newLen = liS.length;
        var initIndex,curIndex,tarIndex;//initIndex，curIndex是从1开始的，tarIndex是从0开始的，tarIndex取值是由动画执行前curIndex临时算出的
        initIndex=curIndex=(newLen-initLen)/2;
        //TODO 样式初始化
        bannerSlider.css({
            'width': newLen * 100+'%',
            'left': -initIndex * 100+'%'
        });
        liS.css({
            'width':1/newLen*100+'%'
        });
        for(var i=0;i<initLen;i++){
            if(i==0){
                $('#indexBControls').append($('<div class="indexBPageitem cur"></div>'));
            }else{
                $('#indexBControls').append($('<div class="indexBPageitem"></div>'));
            }
        }
        if(initLen!=1){
            $('#bannerNext').click(function(){
                if(!bannerSlider.is(':animated')){
                    if(curIndex>=initLen+initIndex){
                        bannerSlider.css({
                            'left': -100 * initIndex+'%'
                        });
                        tarIndex=initIndex+1;
                    }else{
                        tarIndex= curIndex+1;
                    }
                    runToFn(tarIndex);
                }
            });
            $('#bannerPre').click(function(){
                if(!bannerSlider.is(':animated')){
                    if(curIndex<=initIndex-1){
                        bannerSlider.css({
                            'left': -100 * (initIndex+initLen-1)+'%'
                        });
                        tarIndex=initIndex+initLen-2
                    }else{
                        tarIndex= curIndex-1;
                    }
                    runToFn(tarIndex);
                }
            });
            //TODO banner开启定时器
            var bannerTimer=setInterval(function(){
                $('#bannerNext').trigger('click');
            },5000);
            $('#indexBanner').hover(function(){
                clearInterval(bannerTimer);
            },function(){
                bannerTimer=setInterval(function(){
                    $('#bannerNext').trigger('click');
                },5000);
            });
            $('#indexBControls').on('mouseenter','.indexBPageitem',function(){
                var tepIndex=$(this).index()+initIndex;//tepIndex表示临时目标位置
                if(curIndex==tepIndex||curIndex+initLen==tepIndex||curIndex-initLen==tepIndex){
                    //如果滑向当前导航，不做任何动画处理，立即返回。
                    return;
                }
                if(!bannerSlider.is(':animated')){
                    //TODO 将bannerSlider从边缘位置规整到 中间位置
                    if(curIndex>=initIndex+initLen){
                        bannerSlider.css({
                            'left': -initIndex * 100+'%'
                        });
                    }else if(curIndex<=initIndex-1){
                        bannerSlider.css({
                            'left': -100 * (initIndex+initLen-1)+'%'
                        });
                    }
                    tarIndex=$(this).index()+initIndex;
                    runToFn(tarIndex);
                }
            });
        }else{
            $('#indexBPage').hide();
            $('#indexBControls').hide();
        }
    }catch(ex){

    }
    //TODO 创业只需一步的问题
    var indexCYItemWrap=$('#indexCYItemWrap');
    var indexCYBox=$('#indexCYBox');
    var indexCYItemS=indexCYItemWrap.find('.indexCYItem');
    if(indexCYItemS.size()>1){
        indexCYItemWrap.append(indexCYItemS.eq(0).clone());
    }
    indexCYItemS=indexCYItemWrap.find('.indexCYItem');
    var indexCYItemLen=indexCYItemS.size();
    var indexCYInitCount=1;
    function  indexCYAniFn(tarCount){
        indexCYItemWrap.animate({
            'top':-tarCount*indexCYBox.height()
        },400,function(){
            if(tarCount>=indexCYItemLen-1){
                indexCYInitCount=1;
                indexCYItemWrap.css({'top':'0px'});
            }else{
                indexCYInitCount=tarCount+1;
            }
        });
    }
    var indexCYTimer=setInterval(function(){
        indexCYAniFn(indexCYInitCount);
    },3000);
    indexCYBox.mouseenter(function(){
        clearInterval(indexCYTimer);
    }).mouseleave(function(){
        indexCYTimer=setInterval(function(){
            indexCYAniFn(indexCYInitCount);
        },3000);
    });
});
/*
 * jQuery Easing v1.3 - http://gsgd.co.uk/sandbox/jquery/easing/
 *
 * Uses the built in easing capabilities added In jQuery 1.1
 * to offer multiple easing options
 *
 * TERMS OF USE - jQuery Easing
 *
 * Open source under the BSD License.
 *
 * Copyright © 2008 George McGinley Smith
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 * Redistributions of source code must retain the above copyright notice, this list of
 * conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright notice, this list
 * of conditions and the following disclaimer in the documentation and/or other materials
 * provided with the distribution.
 *
 * Neither the name of the author nor the names of contributors may be used to endorse
 * or promote products derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 *  COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 *  EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
 *  GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 *  NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED
 * OF THE POSSIBILITY OF SUCH DAMAGE.
 *
*/

// t: current time, b: begInnIng value, c: change In value, d: duration
jQuery.easing['jswing'] = jQuery.easing['swing'];

jQuery.extend( jQuery.easing,
{
	def: 'easeOutQuad',
	swing: function (x, t, b, c, d) {
		//alert(jQuery.easing.default);
		return jQuery.easing[jQuery.easing.def](x, t, b, c, d);
	},
	easeInQuad: function (x, t, b, c, d) {
		return c*(t/=d)*t + b;
	},
	easeOutQuad: function (x, t, b, c, d) {
		return -c *(t/=d)*(t-2) + b;
	},
	easeInOutQuad: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t + b;
		return -c/2 * ((--t)*(t-2) - 1) + b;
	},
	easeInCubic: function (x, t, b, c, d) {
		return c*(t/=d)*t*t + b;
	},
	easeOutCubic: function (x, t, b, c, d) {
		return c*((t=t/d-1)*t*t + 1) + b;
	},
	easeInOutCubic: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t*t + b;
		return c/2*((t-=2)*t*t + 2) + b;
	},
	easeInQuart: function (x, t, b, c, d) {
		return c*(t/=d)*t*t*t + b;
	},
	easeOutQuart: function (x, t, b, c, d) {
		return -c * ((t=t/d-1)*t*t*t - 1) + b;
	},
	easeInOutQuart: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t*t*t + b;
		return -c/2 * ((t-=2)*t*t*t - 2) + b;
	},
	easeInQuint: function (x, t, b, c, d) {
		return c*(t/=d)*t*t*t*t + b;
	},
	easeOutQuint: function (x, t, b, c, d) {
		return c*((t=t/d-1)*t*t*t*t + 1) + b;
	},
	easeInOutQuint: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t*t*t*t + b;
		return c/2*((t-=2)*t*t*t*t + 2) + b;
	},
	easeInSine: function (x, t, b, c, d) {
		return -c * Math.cos(t/d * (Math.PI/2)) + c + b;
	},
	easeOutSine: function (x, t, b, c, d) {
		return c * Math.sin(t/d * (Math.PI/2)) + b;
	},
	easeInOutSine: function (x, t, b, c, d) {
		return -c/2 * (Math.cos(Math.PI*t/d) - 1) + b;
	},
	easeInExpo: function (x, t, b, c, d) {
		return (t==0) ? b : c * Math.pow(2, 10 * (t/d - 1)) + b;
	},
	easeOutExpo: function (x, t, b, c, d) {
		return (t==d) ? b+c : c * (-Math.pow(2, -10 * t/d) + 1) + b;
	},
	easeInOutExpo: function (x, t, b, c, d) {
		if (t==0) return b;
		if (t==d) return b+c;
		if ((t/=d/2) < 1) return c/2 * Math.pow(2, 10 * (t - 1)) + b;
		return c/2 * (-Math.pow(2, -10 * --t) + 2) + b;
	},
	easeInCirc: function (x, t, b, c, d) {
		return -c * (Math.sqrt(1 - (t/=d)*t) - 1) + b;
	},
	easeOutCirc: function (x, t, b, c, d) {
		return c * Math.sqrt(1 - (t=t/d-1)*t) + b;
	},
	easeInOutCirc: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return -c/2 * (Math.sqrt(1 - t*t) - 1) + b;
		return c/2 * (Math.sqrt(1 - (t-=2)*t) + 1) + b;
	},
	easeInElastic: function (x, t, b, c, d) {
		var s=1.70158;var p=0;var a=c;
		if (t==0) return b;  if ((t/=d)==1) return b+c;  if (!p) p=d*.3;
		if (a < Math.abs(c)) { a=c; var s=p/4; }
		else var s = p/(2*Math.PI) * Math.asin (c/a);
		return -(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
	},
	easeOutElastic: function (x, t, b, c, d) {
		var s=1.70158;var p=0;var a=c;
		if (t==0) return b;  if ((t/=d)==1) return b+c;  if (!p) p=d*.3;
		if (a < Math.abs(c)) { a=c; var s=p/4; }
		else var s = p/(2*Math.PI) * Math.asin (c/a);
		return a*Math.pow(2,-10*t) * Math.sin( (t*d-s)*(2*Math.PI)/p ) + c + b;
	},
	easeInOutElastic: function (x, t, b, c, d) {
		var s=1.70158;var p=0;var a=c;
		if (t==0) return b;  if ((t/=d/2)==2) return b+c;  if (!p) p=d*(.3*1.5);
		if (a < Math.abs(c)) { a=c; var s=p/4; }
		else var s = p/(2*Math.PI) * Math.asin (c/a);
		if (t < 1) return -.5*(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
		return a*Math.pow(2,-10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )*.5 + c + b;
	},
	easeInBack: function (x, t, b, c, d, s) {
		if (s == undefined) s = 1.70158;
		return c*(t/=d)*t*((s+1)*t - s) + b;
	},
	easeOutBack: function (x, t, b, c, d, s) {
		if (s == undefined) s = 1.70158;
		return c*((t=t/d-1)*t*((s+1)*t + s) + 1) + b;
	},
	easeInOutBack: function (x, t, b, c, d, s) {
		if (s == undefined) s = 1.70158;
		if ((t/=d/2) < 1) return c/2*(t*t*(((s*=(1.525))+1)*t - s)) + b;
		return c/2*((t-=2)*t*(((s*=(1.525))+1)*t + s) + 2) + b;
	},
	easeInBounce: function (x, t, b, c, d) {
		return c - jQuery.easing.easeOutBounce (x, d-t, 0, c, d) + b;
	},
	easeOutBounce: function (x, t, b, c, d) {
		if ((t/=d) < (1/2.75)) {
			return c*(7.5625*t*t) + b;
		} else if (t < (2/2.75)) {
			return c*(7.5625*(t-=(1.5/2.75))*t + .75) + b;
		} else if (t < (2.5/2.75)) {
			return c*(7.5625*(t-=(2.25/2.75))*t + .9375) + b;
		} else {
			return c*(7.5625*(t-=(2.625/2.75))*t + .984375) + b;
		}
	},
	easeInOutBounce: function (x, t, b, c, d) {
		if (t < d/2) return jQuery.easing.easeInBounce (x, t*2, 0, c, d) * .5 + b;
		return jQuery.easing.easeOutBounce (x, t*2-d, 0, c, d) * .5 + c*.5 + b;
	}
});

/*
 *
 * TERMS OF USE - EASING EQUATIONS
 *
 * Open source under the BSD License.
 *
 * Copyright © 2001 Robert Penner
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 * Redistributions of source code must retain the above copyright notice, this list of
 * conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright notice, this list
 * of conditions and the following disclaimer in the documentation and/or other materials
 * provided with the distribution.
 *
 * Neither the name of the author nor the names of contributors may be used to endorse
 * or promote products derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 *  COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 *  EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
 *  GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 *  NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED
 * OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 */
