
//留言版判空表单#form
function checkForm()
{
	var flag = true;
	$('#guestbook').find('input[required="required"]').each(function() {
		var obj = $(this);
		if($.trim(obj.val()) == '')
		{
			var label = obj.parent().parent().find('.titles').text().replace(/：/, '');
			var str = '请填写' + label + '！';
			alert(str);
			obj.focus();
			flag = false;
			return false;
		}
	});
	if(flag == true)
	{
		$('#guestbook').submit();
	}
}

function checkThisForm()
{
  var flag = true;
  $('#guestbook').find('input[required="required"]').each(function() {
    var obj = $(this);
    if($.trim(obj.val()) == '')
    {
      var label = obj.parent().find('p').text().replace(/：/, '');
      var str = '请填写' + label + '！';
      alert(str);
      obj.focus();
      flag = false;
      return false;
    }
  });
  if(flag == true)
  {
    $('#form').submit();
  }
}



/*
 *搜索输入验证，判空
 */
 function checkFormS()
 {
 	var gosubmit = true;
 	var seastr = document.getElementById("SeaStr");

 	if(seastr.value == '')
 	{
 		gosubmit = false;
 		alert('请输入搜索内容!');
 		seastr.focus();
 	}
 	return gosubmit;
 }