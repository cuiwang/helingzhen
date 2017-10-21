

  $(function(){

    var xqTabOBox=$('#xqTabOBox');
    var xqTabHeader=$('#xqTabHeader');
    var xqNavUl=$('#xqTabNav_ul');
    var xqMainRightBox=$('.xqMainRightBox');
    var xqNavLis=xqNavUl.children();
    var xqTabHeight=xqTabOBox.height();
    var leftheight=$(window).height()-83;
    var oleftheight=$(window).height()-83-xqTabOBox.offset().top;
    if(oleftheight<0){
        oleftheight=0;
    }

    var projectInfo = {
        'fqrInforBox': 'projectDetails', //项目详情
        'zxjzBox': 'projectStatus', //项目更新
        'plOuterBox': 'comment', //评论
        'zczOuterBox': 'record' //支持记录
    };

    xqTabScrollFn=function(){
          var aleftheight=$(window).height()-83-(xqTabOBox.offset().top-$(document).scrollTop());
          var curObj = null;
          var classFlag = false;

          // console.log($(document).scrollTop());
        if ($(document).scrollTop()>xqTabOBox.offset().top) {
            xqTabHeader.addClass('fixLayout');

            //当左侧内容滚完的时候

            //  xqMainRightBox.attr('style','left: 1156.5px;position: fixed; bottom: 0px;');
             $('.switch').show();
             $('.rightfix-ch').css('height',leftheight+'px');
        } else {
            xqTabHeader.removeClass('fixLayout');
            xqMainRightBox.attr('style','left: 1156.5px');
            $('.rightfix-ch').css('height',aleftheight+'px');
            $('.switch').hide();
        }

        var oheight=$('.jlxqBox').height();

        var otop=$('.jlxqBox').offset().top;
        var obottom=oheight+otop-$(window).height();
        var btom=$(window).height()-(otop+oheight-$(document).scrollTop());
        function change () {
            if($(document).scrollTop()>=obottom){
                xqMainRightBox.removeClass('rightfix');
             $('.rightabs-ch').addClass('rightabs-ch1');
            }if($(document).scrollTop()<=obottom){
                $('.rightabs-ch').removeClass('rightabs-ch1');
            }
            if($(document).scrollTop()>xqTabOBox.offset().top){
                 $('.rightfix-ch').css('height',leftheight+'px')
             }
             if($(document).scrollTop()<xqTabOBox.offset().top&&$(document).scrollTop()<obottom){
                 $('.rightfix-ch').css('height',aleftheight+'px');
             }
        }
        change();
          $(window).resize(function(){
           change();
        });

    }
    xqTabScrollFn();
    // __right();
    $(window).scroll(function(){
        try{
          // __right();
            xqTabScrollFn();
        }catch (ex){
          console.log(ex);
        }
    });


    function __right(){
        var oTage = document.getElementById('tage');
        var s = oTage.offsetTop;  //自己距离顶部的高度offsetH
        var oRight = document.getElementById('right');
        var oLeft = document.getElementById('left');
        var oLeftH = oLeft.scrollHeight;
        var oLeftOffsetTop  = oLeft.offsetTop;

      var winH = document.documentElement.clientHeight || document.body.clientHeight;//可视区域高度
      var winW = document.documentElement.clientWidth || document.body.clientWidth ;//可视区域宽度
      // 自己距离顶部的高度offsetH
      // 自己的高度ownH
      // 屏幕高度winH
      // 滚动条scrollH
      // 当winH  scrollH >ofsetH+ownH  这个时候就是底部对齐的
      // 因为这一阵子 右边的内容已经看到底了
      // 当 offetH < scrollH 回到原来的状态


      //右侧内容低于左侧内容时
      //左侧内容过短时

      var oRightH = oRight.scrollHeight;    //自己的高度

      var oRightOffsetTop = oRight.offsetTop;  //自己距离顶部的高度offsetH
      var oRightOffsetL = oRight.offsetLeft;  //自己距离左侧的宽度
      var distance = (winW -1024)/2+717; //右侧距离
      var scrollH = document.body.scrollTop || document.documentElement.scrollTop; //滚动条
      // 当右侧高度大于可视屏幕高度时 右侧走完时

    var xqMainRightBox=$('.xqMainRightBox');
      if(oRightH > winH) {
          if ((winH + scrollH) > (oRightOffsetTop + oRightH) || ( scrollH - winH) < (oRightH + oRightOffsetTop)) {
              // oRight.style.position = 'fixed';
              // oRight.style.bottom = 0;
              // // oRight.style.marginTop = 0;
              // oRight.style.left = distance + 'px';
          }
          //当左侧内容滚完的时候
          if ((winH + scrollH) > (oLeftH + s)) {
              // oRight.style.position = 'absolute';
              // oRight.style.bottom = 20 + 'px';
              // // oRight.style.marginTop = 30 + 'px';
              // oRight.style.left = 690 + 'px';
          }
          if (scrollH < winH || (scrollH -winH) < (oRightH-winH))
          {
              // oRight.style.position = '';
              // oRight.style.bottom = '';
              // oRight.style.marginTop = 30+'px';
              // oRight.style.right = '';
          }
      }
      else{
          var space = winH - oRightH;

          // console.log(winH);
          if ((winH + scrollH) > (oRightOffsetTop + oRightH) )
          {
            //当左侧内容滚完的时候
            if ((winH + scrollH-space) > (oLeftH + s))
            {
                $("#right").attr('style','left: 690px;position: absolute; bottom:20px;');
                // oRight.style.position = "absolute";
                // oRight.style.bottom = 20+'px';
                // oRight.style.marginTop = 30 + 'px';
                // oRight.style.left = 690 +'px';
            }else{
                console.log(distance);
               xqMainRightBox.css({"position":"absolute"});
                // oRight.style.position = 'fixed';
                // oRight.style.marginTop = 0;
                // oRight.style.left = distance +'px';
                // oRight.style.bottom = space +'px' ;
            }
          }

          if(scrollH < winH)
          {
              oRight.style.position = '';
              oRight.style.bottom = '';
          }
      }
    }
  })
