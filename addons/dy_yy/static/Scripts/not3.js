//window.onerror = function(){return true;} 
/*///////////////////////////////////////// ORDERJSFGX /////////////////////////////////////////*/
function goingtaalert(str){
        layer.alert(str, {
        title:'温馨提示',
        icon:7,
        time: 1500,
        });
}

function postcheck(){

try{
     var product = document.getElementsByName("product");
     if (product.length != 0){
     var numa=0;
     for (var i=0; i<product.length; i++){
      if(product[i].checked) {
      numa++;
      }
     }
     if(numa==0) {
      goingtaalert("请选择要购买产品!");
      document.getElementById('product0').focus();
      return false;
      }
      }
 }
 catch(ex){
 }

try{
     var yanse = document.getElementsByName("yanse");
     if (yanse.length != 0){
     var numa=0;
     for (var i=0; i<yanse.length; i++){
      if(yanse[i].checked) {
      numa++;
      }
     }
     if(numa==0) {
      goingtaalert("请选择要购买颜色");
     document.getElementById('ys0').focus();
      return false;
      }
      }
 }
 catch(ex){
 }

 try{
     var chicun = document.getElementsByName("chicun");
     if (chicun.length != 0){
     var numb=0;
     for (var i=0; i<chicun.length; i++){
      if(chicun[i].checked) {
      numb++;
      }
     }
     if(numb==0) {
      goingtaalert("请选择要购买尺码");
      document.getElementById('cc0').focus();
      return false;
      }
      }
 }
 catch(ex){
 }

try{
		if (document.form.name.value==""){
			goingtaalert('请填写姓名！');
			document.form.name.focus();
			return false;
		}
		var name = /^[\u4e00-\u9fa5]{2,6}$/;
		if (!name.test(document.form.name.value)){
			goingtaalert('姓名格式不正确，请重新填写！');
			document.form.name.focus();
			return false;
		}
    }catch(ex){}
    
    try{
		if (document.form.mob.value==""){
			goingtaalert('请填写手机号码！');
			document.form.mob.focus();
			return false;
		}
		var form = /^1[3,4,5,6,7,8]\d{9}$/;
		if (!form.test(document.form.mob.value)){
			goingtaalert('手机号码格式不正确，请重新填写！已输入'+document.form.mob.value.length+'位');
			document.form.mob.focus();
			return false;
		}
    }catch(ex){} 	
    
    try{
		if (document.form.province.value=="" || document.form.province.value=="所属省份"){
			goingtaalert('请选择所在省份！');
			document.form.province.focus();
			return false;
		}
    }catch(ex){} 
    
    try{
		if (document.form.city.value=="" || document.form.city.value=="所属地级市"){
			goingtaalert('请选择所在城市！');
			document.form.city.focus();
			return false;
		}
    }catch(ex){} 
    
    try{
		if (document.form.area.value=="" || document.form.area.value=="所属市、县级市"){
			goingtaalert('请选择所在区县！');
			document.form.area.focus();
			return false;
		}
    }catch(ex){} 
    
    try{
		if (document.form.address.value==""){
			goingtaalert('请填写详细地址！');
			document.form.address.focus();
			return false;
		}
    }
    catch(ex){
    } 	
    document.form.submit();
   // document.form.submit.disabled = true;
   // document.form.submit.value="正在提交，请稍等 >>";
    return true;
}	
try{	
	var thissrc = document.getElementById("yzm").src;
	function refreshCode(){
		document.getElementById("yzm").src=thissrc+"?"+Math.random(); 
	}
}
catch(ex){
} 	
new PCAS("province","city","area");
/*///////////////////////////////////////// ORDERJSFGX /////////////////////////////////////////*/
function pricea(){
	var product = document.form.product.alt;
	for(var i=0;i<document.form.product.length;i++){
		if(document.form.product[i].checked==true){
			var product = document.form.product[i].alt;
			document.form.danjia.value=product;
			break;
		}
	}
    if(document.form.mun.value=="" || document.form.mun.value==0){	
		var mun=1;
	}
	else{
		var mun=document.form.mun.value;
	}	
	var price=product*mun;
    //document.getElementById("b1").checked='checked';
	document.form.price.value=price;
	document.getElementById("total1").innerHTML=price;
	//document.getElementById("showprice").innerHTML=price;
	//document.getElementById("zfbyh").innerHTML='';
}
function priceb(){
    var cpxljg = document.getElementById("product");
    var product = cpxljg.options[document.getElementById("product").options.selectedIndex].title; 
    if(document.form.mun.value=="" || document.form.mun.value==0){	
		var mun=1;
	}
	else{
		var mun=document.form.mun.value;
	}	
	var price=product*mun;
	document.getElementById("b1").checked='checked';
	document.form.price.value=price;
	document.form.zfbprice.value=price;
	//document.getElementById("showprice").innerHTML=price;
	document.getElementById("zfbyh").innerHTML='';
}

//***************************  支付宝价格  ***************************
function zfbprize(){
         sprice=document.form.zfbprice.value;
		// alert(sprice);
         document.form.price.value=(sprice*notzfbzk*0.1).toFixed(0)
}
/*///////////////////////////////////////// ORDERJSFGX /////////////////////////////////////////*/

function changeprice(){
	var danjia=$("#danjia").val();
	var mun=$("#mun").val();
	$("#total1").html((parseInt(mun) * parseInt(danjia)).toFixed(0));
	$("#price").val((parseInt(mun) * parseInt(danjia)).toFixed(0));
}

function addnumber(){
	$('#mun').val(parseInt($('#mun').val())+1);
	changeprice();
}

function minnumber(){
	if($('#mun').val()>1){
	$('#mun').val(parseInt($('#mun').val())-1);
	changeprice();
	}
}
function inputnumber(){
	var number=parseInt($('#mun').val());
	if(isNaN(number)||number<1){
		$('#mun').val('1');
	changeprice();
	}else{
		$('#mun').val(number);
	}
        changeprice();
}

function quehuo(stra){
	$("input[value="+stra+"]").attr("disabled","disabled");
	$("input[value="+stra+"]").parent().addClass('mouoff');
	//alert(stra+"无货");
}

function f(obj){
$(obj).addClass('mouon').siblings().removeClass('mouon');
//$(objradio).attr("checked","checked");
}

function not3tuifs(){
 $("#putbank").css("display",$('#lx option:selected').attr("fs"));
}

function not3change(obj,str){
$("#qh li").removeClass("on");
if(str=="not3order"){
document.getElementById("not3order").style.display = "";
document.getElementById("not3check").style.display = "none";
document.getElementById("not3tui").style.display = "none";
document.getElementById(obj).setAttribute("class", "on");
}else if(str=="not3check"){
document.getElementById("not3order").style.display = "none";
document.getElementById("not3check").style.display = "";
document.getElementById("not3tui").style.display = "none";
document.getElementById(obj).setAttribute("class", "m on");
}else if(str=="not3tui"){
document.getElementById("not3order").style.display = "none";
document.getElementById("not3check").style.display = "none";
document.getElementById("not3tui").style.display = "";
document.getElementById(obj).setAttribute("class", "on");
  }
}

$(function(){

	function scollDown(id,time){
          var liHeight=$("#"+id+" ul li").height();
          var time=time||2500;
          setInterval(function(){
          $("#"+id+" ul").prepend($("#"+id+" ul li:last").css("height","0px").animate({
          	height:liHeight+"px"
          },"slow"));
          },time);
        

	}
	scollDown("fahuo",3000);
});


