$(document).ready(function(){
	//列表下拉
	$('img[nc_type="flex"]').click(function(){
		var status = $(this).attr('status');
		if(status == 'open'){
			var pr = $(this).parent('td').parent('tr');
			var id = $(this).attr('fieldid');
			var obj = $(this);
			var deep = parseInt($(this).attr('deep'))+1;
			$(this).attr('status','none');
			//ajax
			$.ajax({
				url: 'index.php?act=area&op=arealist&ajax=1&area_parent_id='+id,
				dataType: 'json',
				success: function(data){
					var src='';
					for(var i = 0; i < data.length; i++){
						var tmp_vertline = "<img class='preimg' src='"+ADMIN_TEMPLATES_URL+"/images/vertline.gif'/>";
						src += "<tr class='"+pr.attr('class')+" row"+id+"'>";
						src += "<td class='w36'><input type='checkbox' name='check_area_id[]' value='"+data[i].area_id+"' class='checkitem'>";
						//图片
						if(data[i].have_child == 1){
							src += " <img fieldid='"+data[i].area_id+"' status='open' nc_type='flex' src='"+ADMIN_TEMPLATES_URL+"/images/tv-expandable.gif' deep='"+deep+"'/>";
						}else{
							src += " <img fieldid='"+data[i].area_id+"' status='none' nc_type='flex' src='"+ADMIN_TEMPLATES_URL+"/images/tv-item.gif' deep='"+deep+"'/>";
						}
						src += "</td>";
						//名称
						src += "<td>";
						if(deep == 2){
							src += " <img fieldid='"+data[i].area_id+"' status='open' nc_type='flex' src='"+ADMIN_TEMPLATES_URL+"/images/tv-item1.gif' />";
						}else{
							src += " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img fieldid='"+data[i].area_id+"' status='none' nc_type='flex' src='"+ADMIN_TEMPLATES_URL+"/images/tv-expandable1.gif' />";
						}
						src += data[i].area_name;
						//新增下级
						if(deep == 2){
							src += "<a class='btn-add-nofloat marginleft' href='index.php?act=area&op=area_add&area_id="+data[i].area_id+"'><span>新增下级</span></a>";
						}
						src += "</td>";
						//首字母
						var fl = data[i].first_letter==null?'':data[i].first_letter;
						src += "<td>"+fl+"</td>";
						//区号
						var an = data[i].area_number==null?'':data[i].area_number;
						src += "<td>"+an+"</td>";
						//邮编
						var post = data[i].post==null?'':data[i].post;
						src += "<td>"+post+"</td>";
						//热门城市
						var hot_state = data[i].hot_city==1?'是':'否';
						src += "<td>"+hot_state+"</td>";
						//操作
						src += "<td class='align-center'>";
						src += "<a href='index.php?act=area&op=area_edit&area_id="+data[i].area_id+"'>编辑</a>";
						src += " | <a href=\"javascript:submit_delete("+data[i].area_id+");\">删除</a>";
						src += "</td>";
						src += "</tr>";
					}
					//插入
					pr.after(src);
					obj.attr('status','close');
					obj.attr('src',obj.attr('src').replace("tv-expandable","tv-collapsable"));
					$('img[nc_type="flex"]').unbind('click');
					$('span[nc_type="inline_edit"]').unbind('click');
					//重现初始化页面
                    $.getScript(RESOURCE_SITE_URL+"/js/jquery.edit.js");
					$.getScript(RESOURCE_SITE_URL+"/js/jquery.area.js");
					$.getScript(RESOURCE_SITE_URL+"/js/admincp.js");
				},
				error: function(){
					alert('获取信息失败');
				}
			});
		}
		if(status == 'close'){
			$(".row"+$(this).attr('fieldid')).remove();
			$(this).attr('src',$(this).attr('src').replace("tv-collapsable","tv-expandable"));
			$(this).attr('status','open');
		}
	})
});