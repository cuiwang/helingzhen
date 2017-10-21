// JavaScript Document
/*幻灯片*/
slider = function(time) {
  var spans = "";
  var T = 0;
  var timeout = time;
  for (var i = 0; i < $(".swipe-wrap div").length; i++) {
    spans += "<span></span>";
  }
  var setIntervals = function() {
    if (T < $(".swipe-wrap div").length) {
      T++;
    } else {
      T = 0;
    }
    ;
    return $("#sbsd span").eq(T).trigger("click");
  };

  var setauto = setInterval(setIntervals, timeout);
  $(".swipe-wrap div").hover(function() {
    clearInterval(setauto);
  }, function() {
    setauto = setInterval(setIntervals, timeout);
  });
  $("#sbsd").html(spans);
  $("#sbsd span").eq(0).addClass("on");
  $("#sbsd span").bind('click', function() {
    var numb = $("#sbsd span").index(this);
    slider.slide(numb);
  });
  var slider = Swipe(document.getElementById('slider'), {
    continuous: true,
    callback: function(pos) {
      var i = $("#sbsd span").length;
      while (i--) {
        $("#sbsd span").removeClass("selects");
      }
      $("#sbsd span").eq(pos).addClass("on").siblings("span").removeClass("on");
    }
  });

  if (time === undefined) {
    clearInterval(setauto);
  }
};
/*幻灯结束*/
/*select传值*/
function selectcont(select01, selectBx, orn) {
  $(select01).change(function() {
    var oVal = $(this).val();
    var oText = $(this).find("option:selected").text();
    var oParent = $(this).parents('.selectcont');
    $(oParent).find(selectBx).html(oText);
    $(oParent).find(selectBx).addClass(orn);
  });
}
/*select传值结束*/
//展开与收缩
function navList(id) {
  var $obj = $(".navlist");
  $obj.find(".list-titel").click(function () {
    var $div = $(this).siblings(".list-item");
    if ($(this).parent().hasClass("selected")) {
      $div.slideUp(600);
      $(this).parent().removeClass("selected");
    }
    if ($div.is(":hidden")) {
      $("#J_navlist").find(".list-item").slideUp(600);
      $("#J_navlist").removeClass("selected");
      $(this).parent().addClass("selected");
      $div.slideDown(400);
    } else {
      $div.slideUp(400);
    }
  });      
}
//加减数字
$(function () {
    //减数量1    
    $('.decrement').click(function () {
      var A_top = $(this).position().top + $(this).outerHeight(true);
      var object = $(this).parent("div").find(".quantity-text");
      if (parseInt($(object).val()) == 1) {
        return;  
      }
      else {
        $(object).val(parseInt($(object).val()) - 1);
      }
      var marketprice = parseFloat($("#marketprice").html());
      var delivery_fee =  parseFloat($("#delivery_fee").val());
      var total_price = $("#quantity2").val() * marketprice + delivery_fee ;
      if(total_price < 0){
        total_price = 0;
      }
      $("#totalmoney").html(total_price);
    });
    //加数量1
    $('.increment').click(function () {
      var object = $(this).parent("div").find(".quantity-text");
      $(object).val(parseInt($(object).val()) + 1);
      var marketprice = parseFloat($("#marketprice").html());
      var delivery_fee =  parseFloat($("#delivery_fee").val());
      var total_price = $("#quantity2").val() * marketprice + delivery_fee;
      if(total_price < 0){
        total_price = 0;
      }
      $("#totalmoney").html(total_price);
    });    
  }); 

