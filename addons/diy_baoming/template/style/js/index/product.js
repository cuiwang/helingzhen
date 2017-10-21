$(document).ready(function(){

	var options = {
		dataType : 'json',
		beforeSubmit : validate,
		success : processJson
	};
	$('#orderForm').ajaxForm(options);

	function validate(formData, jqForm, options) {

		var msgError = false

		var form = jqForm[0];
		
		if (msgError == true){
			return false;
		}

	}

	function processJson(data) {

		if (data.success == "true") {

			var product_id = $('#product_id').attr("value");
			window.location.href="product_shoppingcart.php?product_id="+product_id;

		}
		else{
			if (data.follow){
				showPopupBackground("popupBackground");
				showPopupWindow("popupFollow");
			}
			else{
				alert(data.message);
			}
		}

		return true;
	}
	
	/**/
	$('#popupBackground').click(function(){
		$('.popup-window:visible').hide();
		hidePopupBackground("popupBackground");
	});
	/**/

});
