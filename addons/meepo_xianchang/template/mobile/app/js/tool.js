function Path_url(d){
		var i = querystring('i');
        var j = querystring('j');
        
        var url = './index.php?i=' + i + '&j=' + j + '&c=entry&do=' + d  + '&m=meepo_xianchang';
		return url;
}
function querystring(name) {
        var result = location.search.match(new RegExp("[\?\&]" + name + "=([^\&]+)","i"));
        if (result == null  || result.length < 1) {
            return "";
        }
        return result[1];
 }