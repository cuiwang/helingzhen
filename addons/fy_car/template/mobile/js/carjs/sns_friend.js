$(function(){
	//加关注
	$("[nc_type='followbtn']").live('click',function(){
		var data_str = $(this).attr('data-param');
        eval( "data_str = "+data_str);
        ajax_get_confirm('','index.php?act=member_snsfriend&op=addfollow&mid='+data_str.mid);
        return false;
	});
	//取消关注
	$("[nc_type='cancelbtn']").live('click',function(){
		var data_str = $(this).attr('data-param');
        eval( "data_str = "+data_str);
        ajax_get_confirm('','index.php?act=member_snsfriend&op=delfollow&mid='+data_str.mid);
        return false;
	});
	// 批量关注
	$('*[nctype="batchFollow"]').live('click', function(){
		eval("data_str = "+$(this).attr('data-param'));
		ajax_get_confirm('','index.php?act=member_snsfriend&op=batch_addfollow&ids='+data_str.ids);
	});
});