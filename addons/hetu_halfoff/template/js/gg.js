require(["jquery", "district"], function($, dis){
	$(".tpl-district-container").each(function(){
	var elms = {};
	elms.province = $(this).find(".tpl-province")[0];
	elms.city = $(this).find(".tpl-city")[0];
	elms.district = $(this).find(".tpl-district")[0];
	var vals = {};
	vals.province = $(elms.province).attr("data-value");
	vals.city = $(elms.city).attr("data-value");
	vals.district = $(elms.district).attr("data-value");
	dis.render(elms, vals, {withTitle: true});
	});
	});