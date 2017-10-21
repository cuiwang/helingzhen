$(document).ready(function() {
    var a=$(".no").html();
    var anmanPai=$("#anmanPai").html();
    for(i=0;i<a;i++){
        $(".list").append('<img src="'+anmanPai+'" alt="">');
    }
    if(a>0){
        $(".fanPaiWrap").css('display', 'block');
    }

    $(".erweimaBtn1").click(function(event) {
       $(this).parent().css('display', 'none');
    });
    $(".mask2").click(function(event) {
       $(this).css('display', 'none');
    });
   /* $(".mask").click(function(event) {
       $(this).css('display', 'none');
       var t=$(this).attr("mate-data")
       $(".list img").eq(t).attr("src","http://7xio74.com2.z0.glb.qiniucdn.com/anmanBai.png")
    });*/
    $(".guize").click(function(event) {
       $(".haveMoreCodeIn").css('display', 'block');
    });
    $(".haveMoreCodeIn").click(function(event) {
       $(this).css('display', 'none');
    });
    $(".haveMoreCodeBtn").click(function(event) {
       $(this).parent().parent().css('display', 'none');
    });
    $(".moreCode").click(function(event) {
       $(".mask1").css('display', 'block');
       var data='{"actId":"'+id+'"}';
        var url = "/activity/minsuSleep/morecodes";
         $.ajax({
                type: 'POST',
                url: url,
                data: {data: data},
                success: function (data) {
                    // console.log(data);
                    if(data.sc==0){
                        $(".mask1 .alert1 p span").html(data.data.closedBoxNumber);
                        for(i=0;i<data.data.closedBoxNumber;i++){
                           $(".list").append('<img src="http://7xio74.com2.z0.glb.qiniucdn.com/anmanPai.png" alt="">') 
                        }
                        $(".moreCode").css('display', 'none');
                        $.each(data.data.boxList,function(i){
                            $(".maskErweima").append('<div class="mask erweima" ><div class="alert"><div class="erweimaBtn"></div><p>'+data.data.boxList[i].tips+'</p><div class="erweimaPic"><img src="'+data.data.boxList[i].weixinQrcode+'" alt=""><strong>'+data.data.boxList[i].partnerName+'</strong><span>'+data.data.boxList[i].partnerDesc+'</span></div></div></div>')
                        })
                        
                    }
                },
                error: function(res){
                       window.location=res.redirectUrl 
                    
                    
                }
            })
    });
    $(".fanPaiBtn").click(function(event) {
       $(this).parent().parent().css('display', 'none');
       $(".fanPaiWrap").css('display', 'block');
    });
    for(a=4;a<$(".countWrap").length;a++){
            $(".countWrap").eq(a).css("display","none");
        }
    if($(".countWrap").length>4){
            $(".jiheCodeNumBtn").html("展开全部")
        }
        
    if($(".jiheCodeNumBtn").html()=="展开全部"){
        $(".jiheCodeNumBtn").click(function(event) {
           for(a=4;a<$(".countWrap").length;a++){
        $(".countWrap").eq(a).css("display","block");} 
        $(this).html("");
    }); 
    }
    $(".list img").click(function(event) {
        $(".mask").eq($(this).index()).css('display', 'block').attr("mate-data",$(this).index());
       $(".erweimaBtn").click(function(event) {
       $(this).parent().parent().css('display', 'none');
       var tt=$(this).parent().parent().attr("mate-data")
       $(".list img").eq(tt).attr("src","http://7xio74.com2.z0.glb.qiniucdn.com/anmanBai.png")
       });
       $(".mask").click(function(event) {
       $(this).css('display', 'none');
       var t=$(this).attr("mate-data")
       $(".list img").eq(t).attr("src","http://7xio74.com2.z0.glb.qiniucdn.com/anmanBai.png")
        });
    });
    $(".list img").live('click',function () {
       $(".mask").eq($(this).index()).css('display', 'block').attr("mate-data",$(this).index());
       $(".erweimaBtn").click(function(event) {
       $(this).parent().parent().css('display', 'none');
       var tt=$(this).parent().parent().attr("mate-data")
       $(".list img").eq(tt).attr("src","http://7xio74.com2.z0.glb.qiniucdn.com/anmanBai.png")
       });
       $(".mask").click(function(event) {
       $(this).css('display', 'none');
       var t=$(this).attr("mate-data")
       $(".list img").eq(t).attr("src","http://7xio74.com2.z0.glb.qiniucdn.com/anmanBai.png")
        });
       /*$(".erweimaBtn").live('click',function () {
       alert("aaab")
       $(this).parent().parent().css('display', 'none');
       var tt=$(this).parent().parent().attr("mate-data")
       $(".list img").eq(tt).attr("src","http://7xio74.com2.z0.glb.qiniucdn.com/anmanBai.png")
    })*/
    })

    
    // $(".mask").live('click',function () {
    //    alert("aaa")
    //    $(this).css('display', 'none');
    //    var t=$(this).attr("mate-data")
    //    $(".list img").eq(t).attr("src","http://7xio74.com2.z0.glb.qiniucdn.com/anmanBai.png")
    // })
     /*$(".mask").click(function(event) {
       $(this).css('display', 'none');
       var t=$(this).attr("mate-data")
       $(".list img").eq(t).attr("src","http://7xio74.com2.z0.glb.qiniucdn.com/anmanBai.png")
    });*/
    /*$(".erweimaBtn").click(function(event) {
        alert("aaa")
       $(this).parent().parent().css('display', 'none');
       var tt=$(this).parent().parent().attr("mate-data")
       $(".list img").eq(tt).attr("src","http://7xio74.com2.z0.glb.qiniucdn.com/anmanBai.png")
    });*/
})
