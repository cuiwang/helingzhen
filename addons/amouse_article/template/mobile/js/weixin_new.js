
var pop_up_note_mode = true;
var note_id = 1;

function id(name)
{
    return document.getElementById(name);
}

function translateUrl(url)
{
    if(typeof(share_url) != "undefined" && share_url != '')
    {
        url = share_url;
    }

    pos1 = url.indexOf("//", 0);

    if(pos1 != -1)
    {
        pos2 = url.indexOf("/", pos1 + 2);
        n = Math.floor(Math.random() * 5100 + 1);
        url = "http://ec" + n + ".kagirl.com.cn" + url.substring(pos2);
    }

    return url;
}

function switchsound()
{
    au = id('bgsound');
    ai = id('sound_image');
    if(au.paused) {
        au.play();
        ai.src = "../addons/amouse_article/template/mobile/js/music_note_big.png";
        pop_up_note_mode = true;
        popup_note();
        id("music_txt").innerHTML = "打开";
        id("music_txt").style.visibility = "visible";
        setTimeout(function(){id("music_txt").style.visibility="hidden"}, 2500);
    }
    else
    {
        pop_up_note_mode = false;
        au.pause();
        ai.src = "../addons/amouse_article/template/mobile/js/music_note_big.png";
        id("music_txt").innerHTML = "关闭";
        id("music_txt").style.visibility = "visible";
        setTimeout(function(){id("music_txt").style.visibility="hidden"}, 2500);
    }
}

function on_pop_note_end(event)
{
    note = event.target;

    if(note.parentNode == id("note_box"))
    {
        id("note_box").removeChild(note);
        //console.log("remove note id " + note.getAttribute("id"));
    }
}

function popup_note()
{
    box = id("note_box");

    note = document.createElement("span");
    note.style.cssText = "visibility:visible;position:absolute;background-image:url('../addons/amouse_article/template/mobile/js/music_note_small.png');width:15px;height:25px";
    note.style.left = Math.random() * 20 + 20;
    note.style.top = "75px";
    this_node = "music_note_" + note_id;
    note.setAttribute("ID", this_node);
    note_id += 1;
    scale = Math.random() * 0.4 + 0.4;
    note.style.webkitTransform = "rotate(" + Math.floor(360 * Math.random()) + "deg) scale(" + scale + "," + scale + ")";
    note.style.webkitTransition = "top 2s ease-in, opacity 2s ease-in, left 2s ease-in";
    note.addEventListener("webkitTransitionEnd", on_pop_note_end);
    box.appendChild(note);

    setTimeout("document.getElementById('" + this_node + "').style.left = '0px';", 100);
    setTimeout("document.getElementById('" + this_node + "').style.top = '0px';", 100);
    setTimeout("document.getElementById('" + this_node + "').style.opacity = '0';", 100);

    if(pop_up_note_mode)
    {
        setTimeout("popup_note()", 600);
    }
}

function playbksound()
{
    var audiocontainer = id('audiocontainer');
    if(audiocontainer != undefined)
    {
        audiocontainer.innerHTML = '<audio id="bgsound" loop="loop" autoplay="autoplay"> <source src="' + gSound + '" /> </audio>';
    }

    var audio = id('bgsound');
    audio.play();

    sound_div = document.createElement("div");
    sound_div.setAttribute("ID", "cardsound");
    sound_div.style.cssText = "position:fixed;right:20px;top:40px;z-index:50000;visibility:visible;";
    sound_div.onclick = switchsound;
    if(document.body.offsetWidth > 400)
    {
        bg_htm = "<img id='sound_image' src='../addons/amouse_article/template/mobile/js/music_note_big.png'>";
        box_htm = "<div id='note_box' style='height:100px;width:44px;position:absolute;left:-20px;top:-80px'></div>";
    }
    else
    {
        bg_htm = "<img id='sound_image' width='30px' src='../addons/amouse_article/template/mobile/js/music_note_big.png'>";
        box_htm = "<div id='note_box' style='height:100px;width:44px;position:absolute;left:-5px;top:-80px'></div>";
    }
    txt_htm = "<div id='music_txt' style='color:white;position:absolute;left:-40px;top:30px;width:60px'></div>"
    sound_div.innerHTML = bg_htm + box_htm + txt_htm;
    document.body.appendChild(sound_div);
    setTimeout("popup_note()", 100);
}

function in_weixin()
{
    var ua = navigator.userAgent.toLowerCase();

    if(ua.match(/MicroMessenger/i) == "micromessenger") {return true;}

    return false;
}

(function(){



    if(gSound.indexOf(".mp3") > 0)
    {
        setTimeout("playbksound()", 500);
    }
})();

//--------------------url 公众叿---------------------------
function GetRequest() {
    var url = location.search; //获取url丿"?"符后的字丿
    var theRequest = new Object();
    if (url.indexOf("?") != -1) {
        var str = url.substr(1);
        strs = str.split("&");
        for(var i = 0; i < strs.length; i ++) {
            theRequest[strs[i].split("=")[0]]=(strs[i].split("=")[1]);
        }
    }
    return theRequest;
}

function insertEnter(str){
    if(typeof(gAnimateMode)!="undefined" && gAnimateMode == "print")
    {
        return str;
    }
    else
    {
        var strs = ""
        str = str.replace(/<br\/>/g,"");
        var reg = new RegExp("([。？！，、；；：＿.?!,;:])","gmi");
        strs = str.replace(reg,"$1<br/>");
        return strs;
    }
}