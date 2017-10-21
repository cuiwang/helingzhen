// var $=function(id){return document.getElementById(id);};

//解决上面的问题
// function $(id) {
//      if (typeof jQuery == 'undefined' || (typeof id == 'string' && document.getElementById(id))) {
//           return document.getElementById(id);
//      } else if (typeof id == 'object' || !/^\w*$/.exec(id) ||
//           /^(body|div|span|a|input|textarea|button|img|ul|li|ol|table|tr|th|td)$/.exec(id)){
//         return jQuery(id);
//      }
//      return null;
// }

$(function(){
   $("#bgList").show();
   $("#zfyList").hide();
   $(".ui-btn-write").hide();
   $("cardTo").value=localStorage.cardTo || "";
   $("cardBody").value=localStorage.cardBody || "";
   $("cardFrom").value=localStorage.cardFrom || "";

   // if($("input[name=bg]").val()=="")
   // {
   //    $("#bgList").show();
   //    $("#zfyList").hide();
   //    $(".ui-btn-write").hide();
   // }
   // else
   // {
   //    $("#bgList").hide();
   //    $("#zfyList").show();
   //    $(".ui-btn-write").show();
   // }
   $("#bgList li").click(function(){
      //$("input[name=bg]").val($(this).find("img").attr("src"));
      $("input[name=style]").val($(this).attr("data"));
      $("#bgList").hide();
      var zfkey = $(this).attr("key");
      if(zfkey==""){
         $("#zfyList ul").html($("#zfyTemp ul").html());
      }
      else{
         var zfids = zfkey.split(",");
         for(i=0;i<zfids.length;i++){
            $("#zfyList ul").append('<li class="media mediaFullText">'+ $("#zfy"+zfids[i]).html() +'</li>');
         }
         $("#zfyTemp li").each(function(){
            var zfid = $(this).attr("id").replace("zfy","");
            for(i=0;i<zfids.length;i++){
               if(zfids[i]==zfid)
                  $(this).remove();
            }
         });
         $("#zfyList").append("<a class='more'>所有祝福语</a>");
         $("#zfyList .more").click(function(){
            $("#zfyList ul").append($("#zfyTemp ul").html());
            $(this).hide();
            $("#zfyList li").unbind("click");
            $("#zfyList li").click(function(){
               if($(this).find("p").text()!=""){
               $("textarea").val($(this).find("p").text());
               $("textarea").text($(this).find("p").text());
               //$("textarea").focus();
               $('html,body').animate({ scrollTop:'0px'}, 400); 
               }
            });
         });
      }
      $("#zfyList li").unbind("click");
      $("#zfyList li").click(function(){
         if($(this).find("p").text()!=""){
         $("textarea").val($(this).find("p").text());
         $("textarea").text($(this).find("p").text());
         //$("textarea").focus();
         $('html,body').animate({ scrollTop:'0px'}, 400); 
         }
      });
      $("#zfyList").show();
   });
   $(".ui-btn-write").click(function(){
      //$("textarea").focus();
      $('html,body').animate({ scrollTop:'0px'}, 400); 
   });


  // validate signup form on keyup and submit

  $("#signupForm").validate({
    errorLabelContainer: '#notice',
    rules: {
      cardTo: {
        maxlength: 20
      },
      cardBody: {
        required: true,
        minlength: 10,
        maxlength: 200
      },
      cardFrom: {
        required: true,
        maxlength: 20,
      }
    },
    messages: {
      cardTo: {
        maxlength: "对方的名字最多20个字符！"
      },
      cardBody: {
        required: "请输入祝福语！",
        minlength: jQuery.format("祝福语至少要输入 {0} 个字符！"),
        maxlength: jQuery.format("祝福语最多只能输入 {0} 个字符！")
      },
      cardFrom: {
        required: "请输入你自己的名字！",
        maxlength: "你的名字最多20个字符！"
      }
    }
  });
});
