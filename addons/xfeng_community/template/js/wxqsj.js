function deldd_1(id) {
        var formdata = {id:$("#delid").val()} ; //var formdata = {id:id,pages:'qwert'} ;
        $.ajax({
            type: "POST",
            url: "/index/deldd",
            data: formdata,
            success: function(msg) {
                if (msg.status==1 ) {
					$("#loading"+id).hide();
					$("#upcancel"+id).click();
                    window.location = "/index/index";

                } else {
					 alert(msg.status);
                    //alert("删除失败，请稍后再试或联系客服");
                    window.location = "/index/dpdetail.html?id="+id;
                }
				 
            }
        });
}


function deldd(id) {
		$("#loading"+id).show();
        var formdata = {id:id} ; //var formdata = {id:id,pages:'qwert'} ;
        $.ajax({
            type: "POST",
            url: "/index/deldd",
            data: formdata,
            success: function(msg) {
                if (msg.status==1 ) {
					$("#loading"+id).hide();
					$("#upcancel"+id).click();
                    //window.location = "/index/index";
					window.location.reload();

                } else {
					 alert(msg.status);
                    //alert("删除失败，请稍后再试或联系客服");
                    window.location = "/index/dpdetail.html?id="+id;
                }
				 
            }
        });
}

function delyh(id) {
		$("#loading").show();
        var formdata = {id:id} ;  
        $.ajax({
            type: "POST",
            url: "/index/delyh",
            data: formdata,
            success: function(msg) {
                if (msg.status==1 ) {
                    $("#loading").hide();
					$("#upcancel").click();
                    window.location.reload();

                } else {
					alert(msg.info);
                    $("#loading").hide();
					$("#upcancel").click();
                    window.location.reload();
                }
				 
            }
        });
		 
		 
}

function delyh_detail(id) {
		$("#loading").show();
        var formdata = {id:id} ;  
        $.ajax({
            type: "POST",
            url: "/index/delyh",
            data: formdata,
            success: function(msg) {
                if (msg.status==1 ) {
                    $("#loading").hide();
					$("#upcancel").click();
                    window.location.href="/index/youhui";

                } else {
					alert(msg.info);
                    $("#loading").hide();
					$("#upcancel").click();
                    window.location.reload();
                }
				 
            }
        });
		 
		 
}


function delyouhui(id) {
		$("#loading").show();
        var formdata = {id:$("#delid").val()} ;  
        $.ajax({
            type: "POST",
            url: "/index/delyh",
            data: formdata,
            success: function(msg) {
                if (msg.status==1 ) {
                    $("#loading").hide();
					$("#upcancel").click();
                    window.location.reload();

                } else {
					alert(msg.info);
                    $("#loading").hide();
					$("#upcancel").click();
                    window.location.reload();
                }
				 
            }
        });
		 
		 
}



function shopEnter()
{
		$("#loading").show();
		var years = $("#ztc").text();
		var zprice = $("#zprice").text();
		var formdata = {'price':zprice,'shopid':$("#shopid").val(),'years':years,'id':$("#xiaoquid").val()} ;  
        $.ajax({
            type: "POST",
            url: "/index/inenter",
            data: formdata,
            success: function(msg) {
                if (msg.status==1 ) {
                    $("#loading").hide();
					window.location.href ="/index/index"; 
                } else {
					alert(msg.info);
                    $("#loading").hide();
                    window.location.reload();
                }
				 
            }
        });		
		 
}	


function shopTop()
{
	$("#tip").hide();
	$("#loading").hide();
	var zprice = $("#zprice").text();
	var years = $("#ztc").text();
	 
		$("#loading").show();
		var formdata = {'price':zprice,'shopid':$("#shopid").val(),'years':years,'id':$("#xiaoquid").val()} ;  
        $.ajax({
            type: "POST",
            url: "/index/intop",
            data: formdata,
            success: function(msg) {
                if (msg.status==1 ) {
                    $("#loading").hide();
					window.location.href ="/index/index"; 
                } else {
					alert(msg.info);
                    $("#loading").hide();
                    window.location.reload();
                }
				 
            }
        });		
		 
	 
}

function add_yhcode(optype) {
		codenum = parseInt($("#codenum").val());
		if(codenum>0 && codenum <1000)
		{
			
		}
		else
		{
			alert("请输入正确的数量");
			return false;
		}
		$("#loading").show();
		//alert(optype);
        var formdata = {'id':$("#delid").val(),'num':codenum} ;  
        $.ajax({
            type: "POST",
            url: "/index/code_add",
            data: formdata,
            success: function(msg) {
                if (msg.status==1 ) {
                    $("#loading").hide();
					$("#upcancel").click();
                    window.location.reload();

                } else {
					alert(msg.info);
                    $("#loading").hide();
					$("#upcancel").click();
                    window.location.reload();
                }
				 
            }
        });	 
}

function yhEnter()
{
	$("#tip").hide();
	$("#loading").hide();
	var zprice = $("#zprice").text();
	var money = $("#money").val();
	 
		$("#loading").show();
		var formdata = {'price':zprice,'yhid':$("#youhuiid").val(),'id':$("#xiaoquid").val()} ;  
        $.ajax({
            type: "POST",
            url: "/index/yhpay",
            data: formdata,
            success: function(msg) {
                if (msg.status==1 ) {
                    $("#loading").hide();
					window.location.href ="/index/youhui"; 
                } else {
					alert(msg.info);
                    $("#loading").hide();
                    window.location.reload();
                }
				 
            }
        });		
	 
}

function yhTop()
{
	$("#tip").hide();
	$("#loading").hide();
	var zprice = $("#zprice").text();
	var money = $("#money").val();
	var years = $("#ztc").text();
	 
		$("#loading").show();
		var formdata = {'price':zprice,'yhid':$("#youhuiid").val(),'years':years,'id':$("#xiaoquid").val()} ;  
        $.ajax({
            type: "POST",
            url: "/index/yhtop",
            data: formdata,
            success: function(msg) {
                if (msg.status==1 ) {
                    $("#loading").hide();
					window.location.href ="/index/youhui"; 
                } else {
					alert(msg.info);
                    $("#loading").hide();
                    window.location.reload();
                }
				 
            }
        });		
		 
	 	
}