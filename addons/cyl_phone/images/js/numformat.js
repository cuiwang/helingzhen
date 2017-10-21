(function($){
	var methods={
		init:function(){
			$.error('What are you doing?')
		},
		account:function() {
			$(this).keydown(function(event){
					var code=event.keyCode
					if (event.shiftKey){
						return false
					}
					if((code>=96&&code<=105)||((!event.shiftKey)&&code>=48&&code<=57)||code<57){
						return true
					}
					return false
			})
			$(this).keyup(function(){
				$(this).val($(this).val().replace(/\s(?=\d)/g,'').replace(/(\d{4})(?=\d)/g,"$1 "))
			})
		},
		amount:function(){
			$(this).keydown(function(event){
					var code=event.keyCode
					var value=$(this).val()
					if (event.shiftKey||code==32){
						return false
					}
					if((code>=96&&code<=105)||((!event.shiftKey)&&code>=48&&code<=57)||code<57||code==110||code==190){
						return true
					}
					return false
			})
			$(this).keyup(function(event){
				var value=$(this).val().replace(/\,|\s/g,'')
				if(value!=''){				
					$(this).val(value.replace(/(\d{1,3})(?=(\d{3})+(?:$|\.))/g, "$1,"))
				}
			})
		}

	}
	$.fn.inputFormat=function(method){
						if (methods[method]) {
				            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
				        }else if (typeof method === 'object' || !method) {
				                return methods.init.apply(this, arguments);
				        }else {
				                $.error('Method ' + method + ' does not exist on jQuery.inputFormat');
				        }
	}
})(jQuery);