//url处理
var url = location.href;
host='http://'+window.location.host;
root=window.location.pathname;
h=$(window).height();
/*alert(root);*/
function getParameter(paraStr, url)
{
    var result = "";
    //获取URL中全部参数列表数据
    var str = "&" + url.split("?")[1];
    /*var stri=url.split("?")[1];
    alert(stri);*/
    var paraName = paraStr + "=";
    //判断要获取的参数是否存在
    if(str.indexOf("&"+paraName)!=-1)
    {
        //如果要获取的参数到结尾是否还包含“&”
        if(str.substring(str.indexOf(paraName),str.length).indexOf("&")!=-1)
        {
            //得到要获取的参数到结尾的字符串
            var TmpStr=str.substring(str.indexOf(paraName),str.length);
            //截取从参数开始到最近的“&”出现位置间的字符
            result=TmpStr.substr(TmpStr.indexOf(paraName),TmpStr.indexOf("&")-TmpStr.indexOf(paraName));
        }
        else
        {
            result=str.substring(str.indexOf(paraName),str.length);
        }
    }
    else
    {
        result="无此参数";
    }
    return (result.replace("&",""));
}
var r = getParameter("id",url);
idd =r.substring(r.lastIndexOf('=')+1, r.length);
var r1 = getParameter("imgUrl",url);
imgUrl =r1.substring(r1.lastIndexOf('=')+1, r1.length);
var r2 = getParameter("title",url);
title =r2.substring(r2.lastIndexOf('=')+1, r2.length);
var r3 = getParameter("desc",url);
desc =r3.substring(r3.lastIndexOf('=')+1, r3.length);
var r4= getParameter("shareLink",url);
shareLink =r4.substring(r4.lastIndexOf('=')+1, r4.length);
var r5 = getParameter("detailLink",url);
detailLink =r5.substring(r5.lastIndexOf('=')+1, r5.length);
stri=url.split("?")[1]; 
var id=idd.replace(/#rd/, "") 
/*$(document).ready(function() {
    document.title="几何试睡－杭州法云安缦"
});*/



