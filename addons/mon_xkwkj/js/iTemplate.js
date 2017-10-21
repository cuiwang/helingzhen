
define(function(require, exports, module){
	var iTemplate = (function(){
		var template = function(){};
		template.prototype = {
			makeList: function(tpl, json, fn){
				var res = [], $10 = [], reg = /{(.+?)}/g, json2 = {}, index = 0;
				for(var el in json){
					if(typeof fn === "function"){
						json2 = fn.call(this, el, json[el], index++)||{};
					}
					res.push(
						 tpl.replace(reg, function($1, $2){
						 	return ($2 in json2)? json2[$2]: (undefined === json[el][$2]? json[el]:json[el][$2]);
						})
					);
				}
				return res.join('');
			}
		}
		return new template();
	})();

	module.exports = iTemplate;
});