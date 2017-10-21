$(document).ready(function(){
	bind_cate_select();
	bind_project_form();
	
});


function bind_cate_select()
{
	$("#cate_id").bind("change",function(){
		$("#cate_id_last").val($(this).find("option:selected").attr("rel"));
		//alert($(this).attr("rel"));
	});
	/*
	$(".cate_list").find("span").bind("click",function(){
		$(".cate_list").find("span").removeClass("current");
		$(this).addClass("current");
		$("input[name='cate_id']").val($(this).attr("rel"));
	});*/
}

function bind_project_form()
{
	$("input[name='name']").bind("keyup blur",function(){
		if($(this).val().length>30)
		{
			$(this).val($(this).val().substr(0,30));
			return false;
		}
		else
		$("#project_title").html($(this).val());
	});
	
	$("textarea[name='brief']").bind("keyup blur",function(){
		if($(this).val().length>75)
		{
			$(this).val($(this).val().substr(0,75));
			return false;
		}
		else
		$("#deal_brief").html($(this).val());
	});
	
	$("input[name='limit_price']").bind("keyup blur",function(){
		if($.trim($(this).val())==''||isNaN($(this).val())||parseFloat($(this).val())<0)
		{
			$(this).val("");
		}
		else
		$(".limit_price").html($(this).val());
	});
	$("input[name='deal_days']").bind("keyup blur",function(){
		if($.trim($(this).val())==''||isNaN($(this).val())||parseInt($(this).val())<=0)
		{
			$(this).val("");
		}
		else if($(this).val().length>2)
		{
			$(this).val($(this).val().substr(0,2));
			$("#deal_days").html($(this).val().substr(0,2));
		}
		else
		$(".deal_days").html($(this).val());
	});

	$("#project_form").bind("submit",function(){
		if($.trim($(this).find("input[name='limit_price']").val())=='')
		{
			$.show_tip("请输入筹款金额");
			return false;
		}
		if(isNaN($(this).find("input[name='limit_price']").val())||parseFloat($(this).find("input[name='limit_price']").val())<=0)
		{
			$.show_tip("请输入正确的筹款金额");
			return false;
		}
		if($.trim($(this).find("input[name='deal_days']").val())=='')
		{
			$.show_tip("请输入筹集天数");
			return false;
		}
		if(isNaN($(this).find("input[name='deal_days']").val())||parseInt($(this).find("input[name='deal_days']").val())<=0)
		{
			$.show_tip("请输入正确的筹集天数");
			return false;
		}
		if($.trim($(this).find("input[name='lianxiren']").val())=='')
		{
			$.show_tip("请填写您的姓名");
			return false;
		}
		if($.trim($(this).find("input[name='qq']").val())=='')
		{
			$.show_tip("请填写您的QQ");
			return false;
		}
		if($.trim($(this).find("input[name='name']").val())=='')
		{
			$.show_tip("请填写项目标题");
			return false;
		}
		if($(this).find("input[name='name']").val().length>30)
		{
			$.show_tip("项目标题不超过30个字");
			return false;
		}
		if($(this).find("#cate_id").val()==''||$(this).find("#cate_id").val()==0)
		{
			$.show_tip("请选择项目分类");
			return false;
		}
		if($.trim($(this).find("input[name='image']").val())=='')
		{
			$.show_tip("上传封面图片");
			return false;
		}
		
		var ajaxurl = $(this).attr("action");
		var query = $(this).serialize();
		query+="&description="+ encodeURIComponent($("textarea[name='descript']").val());
 		$.ajax({ 
				url: ajaxurl,
				dataType: "json",
				data:query,
				type: "POST",
				success: function(ajaxobj){
					if(ajaxobj.status==1)
					{
						if(ajaxobj.info!="")
						{
							$("input[name='id']").val(ajaxobj.info);
							$.showSuccess("保存成功",function(){
								if(ajaxobj.jump!="")
								{
									location.href = ajaxobj.jump;
								}
							});	
						}
						else
						{
							if(ajaxobj.jump!="")
							{
								location.href = ajaxobj.jump;
							}
						}
					}
					else
					{
						if(ajaxobj.info!="")
						{
							$.showErr(ajaxobj.info,function(){
								if(ajaxobj.jump!="")
								{
									location.href = ajaxobj.jump;
								}
							});	
						}
						else
						{
							if(ajaxobj.jump!="")
							{
								location.href = ajaxobj.jump;
							}
						}							
					}
				},
				error:function(ajaxobj)
				{
					if(ajaxobj.responseText!='')
					alert(ajaxobj.responseText);
				}
			});
			return false;
		});
		
	
	
	$("#savenow").bind("click",function(){
		$("input[name='savenext']").val("0");
		$("#project_form").submit();
	});
	$("#savenext").bind("click",function(){
		$("input[name='savenext']").val("1");
		$("#project_form").submit();
	});
}
