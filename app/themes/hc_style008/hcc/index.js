$(function(){
	var swipeObj = $('#imgSwipe'),
		totalSize = swipeObj.find('li').length;

	$('#totalNum').html(totalSize);

	new Swipe(swipeObj[0], {
		speed: 500, 
		auto: 5000,
		callback: function(index){
			$('#curNum').html(index + 1);
		}
	});

	//去除列表最后一排的线
	var listLi = $('#indexList li'),
		liLen = listLi.length;

	listLi.slice(-(liLen % 3 || 3)).addClass('no_bd');
});