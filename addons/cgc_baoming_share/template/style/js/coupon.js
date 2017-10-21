$(document).ready(function(){

	$('#use').click(function(){
		var apply_id = $(this).attr('val');
		//alert(apply_id);
		window.location.href="use.php?apply_id="+apply_id;
	});

});
