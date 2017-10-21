var windowwidth=$(window).width()-10;
if(windowwidth>350){
    var appw=(windowwidth/5)-5+'px';
}else{
    var appw=(windowwidth/4)-5+'px';
}
$('.guidi_app_area ul li').css('width', appw);


var loadstatus = true;
function autopage(obj,type){
    var autopbn = $('.body_main #autopbn');
    var nextpageurl = autopbn.attr('rel');
    var tab = autopbn.attr('tab');
    var history = autopbn.attr('history');
    var curpage = parseInt(autopbn.attr('curpage'));
    var totalpage = parseInt(autopbn.attr('totalpage'));
    var autopagenum = 100;
    var maxpage = (curpage + autopagenum) > totalpage ? totalpage : (curpage + autopagenum);

    if(type=='click'){
        getnextpagecontent();
    }else{
        if(autopagenum > 0) {
            var curtop = $('#'+obj.id).scrollTop();

            if(curtop + $('#'+obj.id).height() + 300 >= $('#'+obj.id)[0].scrollHeight && loadstatus) {
                loadstatus = false;
                autopbn.text('点击加载更多');

                getnextpagecontent();
            }
        }
    }
    function getnextpagecontent(){
        if(curpage + 1 > totalpage) {
            $('#'+obj.id).unbind('scroll');
            autopbn.css('display', 'none');
            return;
        }
        curpage++;
        var url = nextpageurl + '&t=' + parseInt((+new Date()/1000)/(Math.random()*1000));
        $.ajax({
            type : 'GET',
            url : url,
            dataType : 'html'
        }).success(function(s) {
            s = s.replace(/\n|\r/g, '');
            var tableobj = $('.body_main #listtableid');
            tableobj.append(s);
            autopbn.attr('rel',nextpageurl.replace(/&page=\d+/, '&page=' + (curpage + 1)))
            autopbn.attr('curpage',curpage)
            nextpageurl = nextpageurl.replace(/&page=\d+/, '&page=' + (curpage + 1));
            if(!s) {
                autopbn.css('display', 'none');
            } else {
                autopbn.text('点击加载更多');
                if($('#'+obj.id+' #autolist_ad').length){
                    getad();
                }
            }
            loadstatus = true;
            if(history=='1'){
                var state = {
                    url: url,
                    tabid: tab,
                    html: s
                };
                history.replaceState(state,null,url);
            }
        });
    }
}

function getad(){
    $.ajax({
        type : 'GET',
        url : "{php echo $this->createMobileUrl('getadvs')}",
        dataType : 'html'
    }).success(function(s) {
        $('.body_main #listtableid').append(s);
    }).error(function() {
        return false;
    });
}

var topnavnavleft = $('.body_main .topnv li.a').position().left + 150 - document.body.clientWidth;
if(topnavnavleft){
    $('.body_main .imui_scrollx_area').scrollLeft(topnavnavleft);
}

var bodyheight=document.documentElement.clientHeight;
$('body,.mainarea').css('height',bodyheight + 'px');

$(function(){
	$('.click_rec').click(function(){
        var _that = $(this);
        var id = _that.data('id');
        $.post("{php echo $this->createMobileUrl('detail_new')}",{tid:id,act:'recommend'},function(data){
            if(data.status == 0){
                _that.addClass('cc');
                _that.find('.rec').html(data.total);
                $.toast('点赞成功',2000,function(){});
            }else{
                _that.removeClass('cc');
                _that.find('.rec').html(data.total);
                $.toast('取消点赞成功',2000,function(){});
            }
        },'json');
    });

    $('.body_main .sliders').ready(function() {
        var slider = new Swipe($(".body_main #slider")[0], {
                    callback: function(e, pos) {
                        var i = bullets.length;
                        while (i--) {
                            bullets[i].className = ' ';
                        }
                        bullets[pos].className = 'on';
                    }
                }),
                bullets = document.getElementById('position').getElementsByTagName('em');
    });
})