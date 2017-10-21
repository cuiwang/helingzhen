
//首页 点击 查看 奖品 **********两个接口 1、查看奖品 2、游戏成功结束
var level = 0 ;//获奖等级 接口！ 注：level=0-->已经领过奖品     || 前端填写 不用管！level=6时，表示超时逃跑（前端填写 不用管！）
var btn = 0;//  判断是否点击升级按钮 0未点击 1点击
//获奖所得code---后台抽奖传入
var code  = "";
//首页 查奖

var remark ='';

function onAward(){
	
	window.location.href=myawardurl;

}

//游戏结束 所用函数接口
function goOver(){
	$.post(selectPrizeServleturl,{"xgtid":saveInfoArray[0],"uid":saveInfoArray[1],"from_user":saveInfoArray[2]},function(data){		
		remark = data.remark;
		level = parseInt(data.level);		
		var award_level = data.award_level;
		var award_name = data.award_name;
		cc.director.runScene(new GameOver());
	},"json");
}


//领取奖励
function saveinfo() {
		var realname = document.getElementById("realname").value;
		var mobile = document.getElementById("mobile").value;
		var address = document.getElementById("address").value;
		$.post(saveInfoServleturl,{"xgtid":saveInfoArray[0],"uid":saveInfoArray[1],"from_user":saveInfoArray[2],"realname":realname,"mobile":mobile,"address":address,"remark":remark},function(data){
			//alert(data);
			if(data.ret==1){
				var tip ="<br/><br/>保存成功！<br/><br/>";			
				document.getElementById("tipcontent").innerHTML = tip;
				setTimeout('gotosub()', 2000);				
			}else{
				alert("下手慢了，已被人领走");
			}
		},"json");
    }
function gotosub(){
	window.location.href=gotosuburl;
	
}

//游戏main结束