// ----------------------------------------------//
//                                               //
//                唐明明☀20150724                //
//                                               //
// ----------------------------------------------//


//Regional开始
$(document).ready(function(){
    $(".Regional").click(function(){
        
        
    	
        if ($('.grade-eject').hasClass('grade-w-roll')) {
            $('.grade-eject').removeClass('grade-w-roll');
        } else {
            $('.grade-eject').addClass('grade-w-roll');
        }
    });
});
$(function(){
	$(".screening>ul>li").each(function(){
		$(this).click(function(){
			$(this).children("div").toggleClass("transformthis");
		});
	});
});
$(document).ready(function(){
    $(".grade-w>li").click(function(){
        $(".grade-t")
            .css("left","33.48%")
    });
});

$(document).ready(function(){
    $(".grade-t>li").click(function(){
        $(".grade-s")
            .css("left","66.96%")
    });
});

//Brand开始

$(document).ready(function(){
    $(".Brand").click(function(){
        if ($('.Category-eject').hasClass('grade-w-roll')) {
            $('.Category-eject').removeClass('grade-w-roll');
        } else {
            $('.Category-eject').addClass('grade-w-roll');
        }
    });
});

$(document).ready(function(){
    $(".Category-w>li").click(function(){
        $(".Category-t")
            .css("left","33.48%")
    });
});

$(document).ready(function(){
    $(".Category-t>li").click(function(){
        $(".Category-s")
            .css("left","66.96%")
    });
});

//Sort开始

$(document).ready(function(){
    $(".Sort").click(function(){
        if ($('.Sort-eject').hasClass('grade-w-roll')) {
            $('.Sort-eject').removeClass('grade-w-roll');
        } else {
            $('.Sort-eject').addClass('grade-w-roll');
        }
    });
});


//判断页面是否有弹出

$(document).ready(function(){
    $(".Regional").click(function(){
        if ($('.Category-eject').hasClass('grade-w-roll')){
            $('.Category-eject').removeClass('grade-w-roll');
        };
    });
});
$(document).ready(function(){
    $(".Regional").click(function(){
        if ($('.Sort-eject').hasClass('grade-w-roll')){
            $('.Sort-eject').removeClass('grade-w-roll');
        };
    });
});
$(document).ready(function(){
    $(".Brand").click(function(){
        if ($('.Sort-eject').hasClass('grade-w-roll')){
            $('.Sort-eject').removeClass('grade-w-roll');
        };
    });
});
$(document).ready(function(){
    $(".Brand").click(function(){
        if ($('.grade-eject').hasClass('grade-w-roll')){
            $('.grade-eject').removeClass('grade-w-roll');
        };
    });
});
$(document).ready(function(){
    $(".Sort").click(function(){
        if ($('.Category-eject').hasClass('grade-w-roll')){
            $('.Category-eject').removeClass('grade-w-roll');
        };
    });
});
$(document).ready(function(){
    $(".Sort").click(function(){
        if ($('.grade-eject').hasClass('grade-w-roll')){
            $('.grade-eject').removeClass('grade-w-roll');
        };

    });
});


//js点击事件监听开始
function grade1(wbj){
    var arr = document.getElementById("gradew").getElementsByTagName("li");
    for (var i = 0; i < arr.length; i++){
        var a = arr[i];
        a.style.background = "";
        a.style.color="#808080";
    };
    wbj.style.background = "#f5f5f5"
    wbj.style.color = "#e34f63"
}

function gradet(tbj){
    var arr = document.getElementById("gradet").getElementsByTagName("li");
    for (var i = 0; i < arr.length; i++){
        var a = arr[i];
        a.style.background = "";
        a.style.color="#808080";
    };
    tbj.style.background = "#f5f5f5"
    tbj.style.color = "#e34f63"
}

function grades(sbj){
    var arr = document.getElementById("grades").getElementsByTagName("li");
    for (var i = 0; i < arr.length; i++){
        var a = arr[i];
        a.style.borderBottom = "";
    };
    sbj.style.borderBottom = "solid 1px #ff7c08"
}

function Categorytw(wbj){
    var arr = document.getElementById("Categorytw").getElementsByTagName("li");
    for (var i = 0; i < arr.length; i++){
        var a = arr[i];
        a.style.background = "";
        a.style.color="#808080";
    };
    wbj.style.background = "#eee"
    wbj.style.color = "#e34f63"
}

function Categoryt(tbj){
    var arr = document.getElementById("Categoryt").getElementsByTagName("li");
    for (var i = 0; i < arr.length; i++){
        var a = arr[i];
        a.style.background = "";
        a.style.color="#808080";
    };
    tbj.style.background = "#EEEEEE"
    tbj.style.color = "#e34f63"
}

function Categorys(sbj){
    var arr = document.getElementById("Categorys").getElementsByTagName("li");
    for (var i = 0; i < arr.length; i++){
        var a = arr[i];
        a.style.borderBottom = "";
    };
    sbj.style.borderBottom = "solid 1px #ff7c08"
}

function Sorts(sbj){
    var arr = document.getElementById("Sort-Sort").getElementsByTagName("li");
    for (var i = 0; i < arr.length; i++){
        var a = arr[i];
        a.style.borderBottom = "";
    };
    sbj.style.borderBottom = "solid 1px #e34f63"
}
