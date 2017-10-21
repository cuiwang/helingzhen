/**
 * 自定义提示框
 * Date:2014-12-25
 */
function PromptBox(){
	this.id = "PromptBox",
	this.class = "mainColor PromptBox",
	this.text = "自定义提示框",
	this.defaultTime= 2000,
	this.outTime = this.defaultTime,
	this.hideShade = function(id){
		var PromptBoxShade = document.getElementById(id+"Shade");
		if(PromptBoxShade){
			PromptBoxShade.style.display = "none";
		}
		document.body.style.cssText = '';
		document.body.parentNode.style.overflow="scroll";
	},
	this.showShade = function(){
		var PromptBoxShade = document.getElementById(this.id+"Shade");
		if(PromptBoxShade){
			PromptBoxShade.style.display = "block";
		}else{
			PromptBoxShade = document.createElement("div");
			PromptBoxShade.setAttribute("id",this.id+"Shade");
			PromptBoxShade.setAttribute("class","PromptBoxShade");
			PromptBoxShade.style.cssText = 'background-color:gray;left:0;opacity:0.5;position:absolute;top:0;z-index:1990;filter:alpha(opacity=50);-moz-opacity:0.5;-khtml-opacity:0.5;';
			var body = document.getElementsByTagName("body")[0];
			body.appendChild(PromptBoxShade);
		}
		PromptBoxShade.style.width=document.body.scrollWidth+'px';
		PromptBoxShade.style.height=document.body.scrollHeight+'px';
		document.body.style.cssText = 'overflow:hidden;+overflow:none;_overflow:hidden;';
		document.body.parentNode.style.overflow="hidden";
	},
	this.createMsg = function (){
		var PromptBox = document.createElement("div");
		PromptBox.setAttribute("id", this.id);
		PromptBox.setAttribute("class",this.class);
		var body = document.getElementsByTagName("body")[0];
		body.appendChild(PromptBox);
	},
	this.setArguments = function(a){
		if(a.length>0){
			this.text= a[0];
		}
		if(a.length>1 && typeof(a[1])=="number"){
			this.outTime= a[1];
		}else if(a[1]==="forever"){
			this.outTime = "forever";
		}else{
			this.outTime = this.defaultTime;
		}
	},
	this.promptHide = function(){
		if(document.getElementById(this.id)&&document.getElementById(this.id+"Shade")){
			document.getElementById(this.id).style.display = "none";
			document.getElementById(this.id+"Shade").style.display = "none";
			document.body.style.cssText = '';
			document.body.parentNode.style.overflow="scroll";
		}
	},
	this.showMsg = function(){
		var PBObj = document.getElementById(this.id);
		var Shade = document.getElementById(this.id+"Shade");
		PBObj.style.display = "block";
		PBObj.innerHTML=this.text;
		var doc = document.documentElement;
		var PBX = (doc.clientWidth - PBObj.offsetWidth)/2;
		var PBY = (doc.clientHeight - PBObj.offsetHeight-50)/2;
		PBObj.style.left = PBX+'px';
		if(typeof(this.outTime)=="number"){
			setTimeout(function(){
				document.getElementById("PromptBox").style.display = "none";
				var PromptBoxShade = document.getElementById("PromptBoxShade");
				if(PromptBoxShade){
					PromptBoxShade.style.display = "none";
				}
				document.body.style.cssText = '';
				document.body.parentNode.style.overflow="auto";
			},this.outTime);
		}
	},
	this.prompt = function(){
		this.setArguments(arguments);
		this.showShade();
		if(document.getElementById(this.id)){
			this.showMsg();
		}else{
			this.createMsg();
			this.showMsg();
		}
	},
	this.alert = function(){
		//TODO
	},
	this.confirm = function(){
		//TODO
	},
	this.loadMsg = function(){
		this.prompt(arguments[0]);
	};
}
