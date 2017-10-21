// JavaScript Document

    var size = document.getElementsByClassName("d").length;
    for(i = 0 ;i<size; i++)
    {
        document.getElementsByClassName("f").item(i).onload = function()
        {
            div_w = this.parentNode.offsetWidth;
            div_h = this.parentNode.offsetHeight;
            img_w = this.width;
            img_h = this.height;
            w = parseInt((div_w - img_w)/2);
            h = parseInt((div_h - img_h)/2);
            this.style.left = w + "px";
            this.style.top = h + "px";
        }
    }