jQuery(document).ready(function($){
	
	$(".no-tel").click(function(){
		return false;
	});

	$(".wrap-box .box-item:nth-child(3n+1)").addClass("text-left");
	$(".wrap-box .box-item:nth-child(3n+2)").addClass("text-center");
	$(".wrap-box .box-item:nth-child(3n+3)").addClass("text-right");

	
	$(".sexBox input[name='sex']").parents('form').find(".button").click(function(){
		var sex = $(this).parents("form").find(".sexBox input:checked").val();
		var name = $(this).parents("form").find("input[name='name']").val();
		if(name!==''){
		name = name.replace("先生","").replace("女士","");
		$(this).parents("form").find("input[name='name']").val(name+sex);
		}
	});
	
	$(".picListWrapper .picListWrapperItem:first").css({"borderTop":'0'});
	
	$(".drop-downText").click(function(){
		$(this).siblings(".drop-downItemsWrapper").slideToggle(200);	
	});
	$(".drop-downItem").click(function(){
		$(this).parents(".drop-downWrapper").find(".drop-downText").text($(this).text());
		$(this).parent(".drop-downItemsWrapper").slideUp(200);	
		$(this).parent(".drop-downItemsWrapper").siblings(".drop-downField").val($(this).text());
	});
});