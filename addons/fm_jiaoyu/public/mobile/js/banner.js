      $(function() {
        new Swipe($('#banner_box')[0], {
        speed:500,
            auto:3000,
            callback: function(){
            var lis = $(this.element).next("ol").children();
                lis.removeClass("on").eq(this.index).addClass("on");
            }
        });
      });