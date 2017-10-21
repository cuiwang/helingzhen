function ShowInfo(content, base){
	html = '';
	html += '<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">';
	html += '<div class="modal-dialog" role="document">';
	html += '<div class="modal-content">';
	html += '<div class="modal-header">';
	html += '<h4 class="modal-title text-center" id="myModalLabel">温馨提示</h4>';
	html += '</div>';
	html += '<div class="modal-body">';
	html += '<h2 class="text-center" id="myModalContent">'+content+'</h2>';
	html += '<p class="btn-jx" data-dismiss="modal" id="myModalBase">'+base+'</p>';
	html += '</div>';
	html += '</div>';
	html += '</div>';
	html += '</div>';

	var divObj = document.createElement("div");
	divObj.innerHTML = html;
	var first = document.body.firstChild; //得到第一个元素
	document.body.insertBefore(divObj, first); //在第原来的第一个元素之前插入
	
	$('#myModal').modal('show'); //显示
}
$(function (){ 
	$("#identifier").modal();  //引用
});

function ShowConfirm(content, base1,base2){
	html = '';
	html += '<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">';
	html += '<div class="modal-dialog" role="document">';
	html += '<div class="modal-content">';
	html += '<div class="modal-header">';
	html += '<h4 class="modal-title text-center" id="myModalLabel">温馨提示</h4>';
	html += '</div>';
	html += '<div class="modal-body">';
	html += '<h2 class="text-center" id="myModalContent">'+content+'</h2>';
	html += '<p class="modal-body-btn" >';
    html += '<span class="btn-true" data-dismiss="modal"  onclick="" >'+base1+'</span> ';
    html += '<span class="btn-false" data-dismiss="modal">'+base2+'</span>';
    html += '</p>';
	html += '</div>';
	html += '</div>';
	html += '</div>';
	html += '</div>';

	var divObj = document.createElement("div");
	divObj.innerHTML = html;
	var first = document.body.firstChild; //得到第一个元素
    
	document.body.insertBefore(divObj, first); //在第原来的第一个元素之前插入
	
	$('#myModal').modal('show'); //显示
}

