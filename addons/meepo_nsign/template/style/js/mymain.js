//页面转跳
function redirect(url,time)
{
	if (undefined == url || url == '') {
		url = location;
	}
	
	if (undefined == time ) {
		time = 20;
	}
	setTimeout("location.href='"+url+"'",20);
}