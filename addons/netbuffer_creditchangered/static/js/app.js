function count_nbf_exchangemoney(nbf_divide_money,nbf_exchange_num){
	return (nbf_divide_money*nbf_exchange_num)/100;
}
function credittomoney(obj){
	var exc_num=parseInt($("#exc_number").val());
	var max_num=parseInt($("input[name='nbf_exchang_max_money']").val())==0?200:parseInt($("input[name='nbf_exchang_max_money']").val());
	if(isNaN(exc_num)){
		alert("请输入要兑换的积分数哦");
		return ;
	}else if(exc_num<0){
		alert("请输入要兑换的积分数哦");
		return ;
	}else if(exc_num>parseInt($("#nbf_current_user_hasnum_total").text())){
		alert("您兑换的积分已经超出当前的啦");
		$("#exc_number").focus();
		return ;
	}else if(parseInt($("#nbf_count_exchange_money").text())<1){
		alert("必须大于1元才可兑换的哦");
		return ;
	}else if(parseInt($("#nbf_count_exchange_money").text())>200){
		alert("红包金额不可大于200元");
		return ;
	}else if(parseInt($("#nbf_count_exchange_money").text())>max_num){
		alert("兑换的红包金额不能大于"+max_num+"元哦");
		return ;
	}
	$("#nbf_ask_usr_confirm").show();
	$('#nbf_ask_usr_confirm').find('.nbf_exchange_cancel').on('click', function () {
        $('#nbf_ask_usr_confirm').hide();
    });
	$('#nbf_ask_usr_confirm').find('.nbf_exchange_ok').off('click');
	$('#nbf_ask_usr_confirm').find('.nbf_exchange_ok').on('click', function () {
		$('#nbf_ask_usr_confirm').hide();
		//遮罩效果
		$("#nbf_exchanging_loading").show();
		$.ajax({
		  type: 'POST',
		  url: $.trim($("input[name='nbf_exchang_url']").val()),
		  data: { changenum:exc_num},
		  dataType: 'json',
		  success: function(data){
			  $("#nbf_exchanging_loading").hide();
			  //发送成功
			  if(data.status==1){
				  window.location.href=$.trim($("input[name='nbf_exchangsuccess_url']").val());
			  }else{
				  alert("兑换出了点问题哦，错误状态码:"+data.status+",详情:"+data.info);
			  }
		  },
		  error: function(xhr, type){
			  $("#nbf_exchanging_loading").hide();
		      alert("出了点问题哦，请稍后再试~");
		  }
		});
    });
}
$(function(){
	var exec_number = document.getElementById('exc_number');
	if(null!=exec_number){
		exec_number.addEventListener('input', function (e) {
			var nbf_exchange_num=e.target.value.trim();
			if(nbf_exchange_num<0){
				alert("不能兑换负数个积分哦");
				return ;
			}else if(parseInt($("#nbf_current_user_hasnum_total").text())<nbf_exchange_num){
				alert("您兑换的积分已经超出当前的啦");
				$("#exc_number").focus();
				return ;
			}else{
				var nbftotal=count_nbf_exchangemoney($.trim($("#nbfchangemoney").text()),nbf_exchange_num);
				$("#nbf_count_exchange_money").text(nbftotal);
			}
//		    e.target.setCustomValidity(ensurePasswordPolicy(e.target.value.trim()))
		})
	}
	$("title").text($(".page_title").text());
//	f.elements['reenter-password'].addEventListener('input', function (e) {
//	    e.target.setCustomValidity(
//	    e.target.value.trim() === f.elements['new-password'].value.trim() ?
//	        '' : 'Reenter password does not match new password')
//	})
});