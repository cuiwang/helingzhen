/**
 * Created by ben on 2016-7-29.
 */
function placeholderPic(){
    var w = document.documentElement.offsetWidth||document.body.offsetWidth;
    document.documentElement.style.fontSize=(w/750)*100+'px';
}
placeholderPic();
window.onresize=function(){
    placeholderPic();
}