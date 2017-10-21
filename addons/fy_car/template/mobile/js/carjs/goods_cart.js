function drop_cart_item(store_id, rec_id){
    var tr = $('#cart_item_' + rec_id);
    var amount_span = $('#cart'+store_id+'_amount');
    $.getJSON('index.php?act=cart&op=drop&specid=' + rec_id + '&storeid=' + store_id, function(result){
        if(result.done){
            //删除成功
            if(result.quantity == 0){//判断购物车是否为空
                window.location.reload();    //刷新
            }
            else{
            	//判断店铺商品是否为空
            	if(result.store_quantity == 0){
            		$(".storemodule_"+store_id).remove();
            	}else{
            		tr.remove();        //移除
            		amount_span.html(price_format(result.store_amount));  //刷新总费用
            	}
            }
        }else{
        	alert(result.msg);
        }
    });
}
function change_quantity(store_id, rec_id, spec_id, input, orig){
    var subtotal_span = $('#item' + rec_id + '_subtotal');
    var amount_span = $('#cart' + store_id + '_amount');
    //暂存为局部变量，否则如果用户输入过快有可能造成前后值不一致的问题
    var _v = input.value;
    $.getJSON('index.php?act=cart&op=update&spec_id=' + spec_id + '&quantity=' + _v, function(result){
        if(result.done){
            //更新成功
            $(input).attr('changed', _v);
            subtotal_span.html(number_format(result.subtotal,2));
            amount_span.html(number_format(result.amount,2));
        }
        else{
            //更新失败
            alert(result.msg);
            $(input).val($(input).attr('changed'));
        }
    });
}
function decrease_quantity(rec_id){
    var item = $('#input_item_' + rec_id);
    var orig = Number(item.val());
    if(orig > 1){
        item.val(orig - 1);
        item.keyup();
    }
}
function add_quantity(rec_id){
    var item = $('#input_item_' + rec_id);
    var orig = Number(item.val());
    item.val(orig + 1);
    item.keyup();
}
