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
        var coupon_money =  parseFloat($("#coupon_money").val());
        var total_price = $("#quantity2").val() * marketprice + delivery_fee - coupon_money;
        //total_price = total_price.toFixed(2);
        $("#totalmoney").html(total_price);
    });
    //加数量1
    $('.increment').click(function () {
        var object = $(this).parent("div").find(".quantity-text");
        $(object).val(parseInt($(object).val()) + 1);
        var marketprice = parseFloat($("#marketprice").html());
        var delivery_fee =  parseFloat($("#delivery_fee").val());
        var coupon_money =  parseFloat($("#coupon_money").val());
        var total_price = $("#quantity2").val() * marketprice + delivery_fee - coupon_money;
        //total_price = total_price.toFixed(2);
        $("#totalmoney").html(total_price);
    });    
}); 