/*$(document).ready(function(){
    var $category = $('ul li:gt(5):not(:last)');
	$category.hide();
	var $toggleBtn = $('#showMore a');
	$toggleBtn.click(function(){
		if($category.is(":hidden")){
			$category.show();
			$(this).find('span').removeClass('hide').addClass('show').text('精简显示品牌');
			$('ul li a').filter(":contains('佳能')").css("color","red");
		}else{
			$category.hide();
			$(this).find('span').removeClass('show').addClass('hide').text('显示全部品牌');
		}
	});
});*/

$(document).ready(function(){
    var $category = $('ul li:gt(1):not(:last)');
	$category.hide();
	var $toggleBtn = $('#showMore a');
	$toggleBtn.toggle(function(){
		$category.show();
		$(this).find('span').removeClass('mei').addClass('show').text('精简显示品牌');
	},function(){
		$category.hide();
		$(this).find('span').removeClass('show').addClass('mei').text('显示全部品牌');
	});
});


