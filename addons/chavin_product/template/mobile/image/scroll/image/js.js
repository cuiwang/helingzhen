var ClockTimer = null;
var start = Math.floor(Math.random()*(10-5+1)+5);
function clock()
{
	//alert(start);
	ClockTimer = setInterval(function(){
		if(start == 0){
			clearInterval( ClockTimer );
			AutoScroll("#scrollP");
		}
		else{
			start--;
		}
		
	},1000);
}
function AutoScroll(obj){ 

	var seconds,mobile_star,mobile_end;
	var mobile = new Array(130,131,132,185,186,134,135,136,137,138,139,158,159,133,153,189,189);
	seconds = Math.floor(Math.random()*(16-10+1)+10);
	mobile_star = mobile[Math.floor(Math.random()*(16-0+1)+0)];
	mobile_end = Math.floor(Math.random()*(9999-1000+1)+1000);
	$("#scrollP").html('<p class="index01-banner-notes">祝贺:<span class="color-one">'+mobile_star+'****'+mobile_end+'</span>的用户于'+seconds+'秒前购买了商品</p>').animate({
	   opacity: 1
	 }, 500);

	//
	 setTimeout(function(){
		$("#scrollP").animate({
			opacity: 0
		}, 500);
	 },2000);

	start = Math.floor(Math.random()*(10-5+1)+5);
	clock();
}
clock();