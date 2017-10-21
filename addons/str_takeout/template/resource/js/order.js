$.fn.amount = function(num, callback){
	var num = typeof num === 'undefined' ? 0 : num,
		callback = callback || $.noop,
		isShow = num > 0 ? '' : ' style="display:none;"',
		activeClass = 'active';

	function add(){
		var obj = $(this).prev(),
			_num = obj.find('.num'),
			curNum = parseInt(_num.text(), 10);

		var data_obj = obj.parent();
		var max = data_obj.attr("max");
		var is_first_order = data_obj.data("first-order");
		var buy_limit = data_obj.data("buy-limit");
		var first_order_limit = data_obj.data("first-order-limit");
		if(first_order_limit == 1 && !is_first_order) {
			alert('仅限新用户购买');
			return false;
		}
		if(buy_limit > 0 && curNum >= buy_limit) {
			alert('每人仅限购' + buy_limit + '份');
			return false;
		}		
		if(null != max && max != "" && max != "-1" && curNum >= max)
		{
			return false;
		}
		_num.text(++curNum);
		data_obj.next(".number").val(curNum);
		if(curNum > 0){
			obj.show();
			$(this).addClass(activeClass);
		}
		return callback.call(this, '+');
	}

	function del(){
		var obj = $(this).parent(),
			_num = obj.find('.num'),
			_add = obj.next(),
			curNum = parseInt(_num.text(), 10);

		_num.text(--curNum);
		obj.parent().next(".number").val(curNum);
		if(curNum < 1){
			obj.hide();
			_add.removeClass(activeClass);
		}else{
			_add.addClass(activeClass);
		}
		return callback.call(this, '-');
	}

	return this.each(function(){
		$(this).before('<span'+ isShow +'><a href="javascript:void(0);" class="btn_n del '+ activeClass +'"></a><span class="num">'+ num +'</span></span>').bind('click', add);
		
		$(this).prev().find('.del').bind('click', del);

		if(num > 0){
			$(this).addClass(activeClass);
		}
	});
}

$.amountCb = function(){
	var _condition = $('#sendCondition'),
		_total = $('#totalPrice'),
		_cartNum = $('#cartNum'),
		sendCondition = parseFloat(_condition.text()).toFixed(3);

	return function(sign){
		var totalPrice = parseFloat(_total.text()) || 0,
			disPrice = parseFloat(sign + 1) * parseFloat($(this).parents('li').find('.unit_price').text()),
			price = totalPrice + disPrice,
			number = _cartNum.text() == '' ? 0 : parseInt(_cartNum.text()),
			disNumber = number + parseInt(sign + 1);
			price = parseFloat((price).toFixed(3));
		_total.text(price);
		_condition.text(parseFloat((sendCondition - price).toFixed(3)));
		_cartNum.text(disNumber);
		if(sendCondition - price <= 0){
			_condition.parent().hide().next().show();
		}else{
			_condition.parent().show().next().hide();
		}

		if(disNumber > 0){
			_cartNum.addClass('has_num');
		}else{
			_cartNum.removeClass('has_num').text('');
		}
		return false;
	}
}

$(function(){
	if($('#swipeNum').length){
		new Swipe($('#imgSwipe')[0], {
			speed: 500, 
			auto: 5000, 
			callback: function(index){
				$('#swipeNum li').eq(index).addClass("on").siblings().removeClass("on");
			}
		});
	}

	$('#storeList li').click(function(e){
		if(e.target.tagName != 'A'){
			location.href = $(this).attr('href');
		}
	});
});