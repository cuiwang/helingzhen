var picfont = ["0", "e601", "e602", "e603", "e604", "e605", "e606", "e607", "e608", "e609", "e60a", "e60b", "e60c", "e60d", "e60e", "e60f", "e610", "e611", "e612", "e613", "e614", "e615", "e616", "e617", "e618", "e619", "e61a", "e61b", "e61c", "e61d", "e61e", "e61f", "e620", "e621", "e622", "e623", "e624", "e625", "e62a", "e62b", "e62d", "e62e", "e62f", "e630", "e631", "e632", "e633", "e634", "e635", "e636", "e637", "e638", "e639", "e63a", "e63b", "e63c", "e63d", "e63e", "e63f", "e640", "e641", "e642", "e643", "e644", "e645", "e646", "e647", "e648", "e649", "e64a", "e64b", "e64c", "e64d", "e64e", "e64f", "e650", "e651", "e652", "e653", "e654", "e655", "e656", "e657", "e658", "e659", "e65a", "e65b", "e65c", "e65d", "e65e", "e65f", "e660", "e661", "e662", "e663", "e664", "e665", "e666", "e667", "e668", "e669", "e66a", "e66b", "e66c", "e66d", "e66e", "e66f", "e670", "e671", "e672", "e673", "e674", "e675", "e676", "e677", "e678", "e679", "e67a", "e67b", "e67c", "e682", "e683", "e684", "e685", "e686", "e687", "e688", "e689", "e68a", "e68b", "e68c", "e68d", "e68e", "e68f", "e690", "e691", "e692", "e693", "e627", "e600", "e626", "e628", "e629", "e62c"];


function writeSelect(index) {
    if (index == "") {
        index = "0";
    }
    //console.log(index);
    document.write('<select name="image" class="iconfont"  style="margin-left:20px;width: 80px;">');
    for (var i = 1; i < picfont.length; i++) {
        document.write('<option ' + (index == picfont[i] ? 'selected=selected' : '') + ' value="' + picfont[i] + '">' + "&#X" + picfont[i] + ";" + '</option>');
    }
    document.write('</select> ');
}

function createSelect(index) {
    if (index == "") {
        index = "0";
    }
    var html = '';
    for (var i = 1; i < picfont.length; i++) {
        html += '<option ' + (index == picfont[i] ? 'selected=selected' : '') + ' value="' + picfont[i] + '">' + "&#X" + picfont[i] + ";" + '</option>';
    }
    //html += '</select> ';
    return html;

}
function getpic(str) {
    if (str == "" || str == "0") 
        str = "e68b";
    return "&#X" + str + ";";
}
//
var lm23show = 1; //是否显示
var listType = ""; //number显示数字，为空显示点。

