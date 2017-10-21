/**
 * 字符窜工具
 * mark
 */
 
//截取字符容，长度中文按照2，英文按照来1计算
//str.sub(8,'...') 长度,后缀
String.prototype.sub = function (n) {  
	var r = /[^\x00-\xff]/g;  
	if ( this.replace(r, "mm" ).length <= n){
	 	return this.substr(0, this .length);  
	}
	// n = n - 3;  
	var m = Math.floor(n/2);  
	for ( var i=m; i< this .length; i++) {  
		if ( this.substr(0, i).replace(r, "mm" ).length>=n) {  
		return this.substr(0, i) + '...'; }  
	} 
	return this;  
}; 

//返回字符窜的长度，文字按2个来算，拼音数字为1.
String.prototype.length = function() {
    var realLength = 0, len = this.length, charCode = -1;
    for (var i = 0; i < len; i++) {
        charCode = this.charCodeAt(i);
        if (charCode >= 0 && charCode <= 128) realLength += 1;
        else realLength += 2;
    }
    return realLength;
};


//替换字符窜1，防止js执行
//将"<" 和 ">" 符号 用 &lt &gt 表示
function changeURLCode(str){
	str = str.replaceAll("&","&amp;");
	str = str.replace("<","&lt;");
	str = str.replace(">","&gt;");
	str = str.replace("/","&frasl;");
	return str;
}

//字符窜替换2
//注：只处理字符窜，对带有/的字符不可用，也不可追加
function changeStrCode(str){
	return str.replaceAll("'","&apos;");
	return str.replaceAll('"',"&quot;");
}

//替换全部
String.prototype.replaceAll = function (AFindText,ARepText){
	raRegExp = new RegExp(AFindText,"g");
	return this.replace(raRegExp,ARepText)
}

//去除首尾空格
String.prototype.trim = function(){
    return this.replace(/^\s+|\s+$/g,'');
}

//去除字符窜中的所有空格
String.prototype.delSpace = function(){
	return this.replace(/\s/ig,'');
}
