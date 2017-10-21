!function(a, b) {
    a.SubStr = b(a.$)
}(this, function() {
    return {
        substr2: function(a, b, c) {
            var d = 0
              , e = 0
              , f = 0;
            for (f = 0; f < a.length; f++)
                if (e += a.charCodeAt(f) > 255 ? 3 : 1,
                b >= e)
                    d++;
                else if (e > b + c)
                    break;
            return a.substr(d, f)
        },
        size: function(a) {
            return a.replace(/[^\u0000-\u007F]/gim, "...").length
        }
    }
}),
function(a, b) {
    a.Face = b()
}(window, function() {
    function a(a) {
        return a.replace(/\u0014([\s\S])/gi, function(a, c) {
            var e = i[parseInt(b(c))];
            return e ? h.code2Img(d(e)) : a
        })
    }
    function b(a) {
        return a ? a.charCodeAt(0) : a
    }
    function c(a) {
        for (var b = 0; b < q.length; b++)
            a = a.replace(q[b], '<img alt="@" height="24" width="24" src="http://qplus3.idqqimg.com/qun/jslib/res/ios_emoji/' + p[b] + '.png" rel="emoji" name="' + p[b] + '" />');
        return a
    }
    function d(a) {
        return "[/" + a + "]"
    }
    function e(a) {
        var b = "p"
          , c = RegExp("<(/?\\s*" + b + ")[^>]*>", "ig");
        return a = a.replace(c, function(a, b) {
            return b.match(/^\//) ? "\n" : ""
        }),
        a.replace(/<\/?[^>]*>/gi, "").replace(new RegExp("&nbsp;","gi"), " ").replace(/\u200B/g, "").replace(/(\r\n|\n|\r)$/g, "")
    }
    function f(a) {
        return a.replace(/<img.+?\/?>/gi, function(a) {
            var b = a.match(/alt="(\[\/[\u4e00-\u9fa5OKN]{1,3}\])"/);
            return b ? b[1] : a
        })
    }
    function g(a) {
        return String.fromCharCode(l) + String.fromCharCode(a)
    }
    var h = {}
      , i = null 
      , j = null 
      , k = null 
      , l = 20
      , m = {
        "呲牙": {
            text: "呲牙",
            code: 0
        },
        "调皮": {
            text: "调皮",
            code: 1
        },
        "流汗": {
            text: "流汗",
            code: 2
        },
        "偷笑": {
            text: "偷笑",
            code: 3
        },
        "再见": {
            text: "再见",
            code: 4
        },
        "敲打": {
            text: "敲打",
            code: 5
        },
        "擦汗": {
            text: "擦汗",
            code: 6
        },
        "猪头": {
            text: "猪头",
            code: 7
        },
        "玫瑰": {
            text: "玫瑰",
            code: 8
        },
        "流泪": {
            text: "流泪",
            code: 9
        },
        "大哭": {
            text: "大哭",
            code: 1e3
        },
        "嘘": {
            text: "嘘",
            code: 11
        },
        "酷": {
            text: "酷",
            code: 12
        },
        "抓狂": {
            text: "抓狂",
            code: 1300
        },
        "委屈": {
            text: "委屈",
            code: 14
        },
        "便便": {
            text: "便便",
            code: 15
        },
        "炸弹": {
            text: "炸弹",
            code: 16
        },
        "菜刀": {
            text: "菜刀",
            code: 17
        },
        "可爱": {
            text: "可爱",
            code: 18
        },
        "色": {
            text: "色",
            code: 19
        },
        "害羞": {
            text: "害羞",
            code: 20
        },
        "得意": {
            text: "得意",
            code: 21
        },
        "吐": {
            text: "吐",
            code: 22
        },
        "微笑": {
            text: "微笑",
            code: 23
        },
        "发怒": {
            text: "发怒",
            code: 24
        },
        "尴尬": {
            text: "尴尬",
            code: 25
        },
        "惊恐": {
            text: "惊恐",
            code: 26
        },
        "冷汗": {
            text: "冷汗",
            code: 27
        },
        "爱心": {
            text: "爱心",
            code: 28
        },
        "示爱": {
            text: "示爱",
            code: 29
        },
        "白眼": {
            text: "白眼",
            code: 30
        },
        "傲慢": {
            text: "傲慢",
            code: 31
        },
        "难过": {
            text: "难过",
            code: 32
        },
        "惊讶": {
            text: "惊讶",
            code: 33
        },
        "疑问": {
            text: "疑问",
            code: 34
        },
        "睡": {
            text: "睡",
            code: 35
        },
        "亲亲": {
            text: "亲亲",
            code: 36
        },
        "憨笑": {
            text: "憨笑",
            code: 37
        },
        "爱情": {
            text: "爱情",
            code: 38
        },
        "衰": {
            text: "衰",
            code: 39
        },
        "撇嘴": {
            text: "撇嘴",
            code: 40
        },
        "阴险": {
            text: "阴险",
            code: 41
        },
        "奋斗": {
            text: "奋斗",
            code: 42
        },
        "发呆": {
            text: "发呆",
            code: 43
        },
        "右哼哼": {
            text: "右哼哼",
            code: 44
        },
        "拥抱": {
            text: "拥抱",
            code: 45
        },
        "坏笑": {
            text: "坏笑",
            code: 46
        },
        "飞吻": {
            text: "飞吻",
            code: 47
        },
        "鄙视": {
            text: "鄙视",
            code: 48
        },
        "晕": {
            text: "晕",
            code: 49
        },
        "大兵": {
            text: "大兵",
            code: 50
        },
        "可怜": {
            text: "可怜",
            code: 51
        },
        "强": {
            text: "强",
            code: 52
        },
        "弱": {
            text: "弱",
            code: 53
        },
        "握手": {
            text: "握手",
            code: 54
        },
        "胜利": {
            text: "胜利",
            code: 55
        },
        "抱拳": {
            text: "抱拳",
            code: 56
        },
        "凋谢": {
            text: "凋谢",
            code: 57
        },
        "饭": {
            text: "饭",
            code: 58
        },
        "蛋糕": {
            text: "蛋糕",
            code: 59
        },
        "西瓜": {
            text: "西瓜",
            code: 60
        },
        "啤酒": {
            text: "啤酒",
            code: 61
        },
        "瓢虫": {
            text: "瓢虫",
            code: 62
        },
        "勾引": {
            text: "勾引",
            code: 63
        },
        OK: {
            text: "OK",
            code: 64
        },
        "爱你": {
            text: "爱你",
            code: 65
        },
        "咖啡": {
            text: "咖啡",
            code: 66
        },
        "钱": {
            text: "钱",
            code: 67
        },
        "月亮": {
            text: "月亮",
            code: 68
        },
        "美女": {
            text: "美女",
            code: 69
        },
        "刀": {
            text: "刀",
            code: 70
        },
        "发抖": {
            text: "发抖",
            code: 71
        },
        "差劲": {
            text: "差劲",
            code: 72
        },
        "拳头": {
            text: "拳头",
            code: 73
        },
        "心碎": {
            text: "心碎",
            code: 74
        },
        "太阳": {
            text: "太阳",
            code: 75
        },
        "礼物": {
            text: "礼物",
            code: 76
        },
        "足球": {
            text: "足球",
            code: 77
        },
        "骷髅": {
            text: "骷髅",
            code: 78
        },
        "挥手": {
            text: "挥手",
            code: 79
        },
        "闪电": {
            text: "闪电",
            code: 80
        },
        "饥饿": {
            text: "饥饿",
            code: 81
        },
        "困": {
            text: "困",
            code: 82
        },
        "咒骂": {
            text: "咒骂",
            code: 83
        },
        "折磨": {
            text: "折磨",
            code: 84
        },
        "抠鼻": {
            text: "抠鼻",
            code: 85
        },
        "鼓掌": {
            text: "鼓掌",
            code: 86
        },
        "糗大了": {
            text: "糗大了",
            code: 87
        },
        "左哼哼": {
            text: "左哼哼",
            code: 88
        },
        "哈欠": {
            text: "哈欠",
            code: 89
        },
        "快哭了": {
            text: "快哭了",
            code: 90
        },
        "吓": {
            text: "吓",
            code: 91
        },
        "篮球": {
            text: "篮球",
            code: 92
        },
        "乒乓": {
            text: "乒乓",
            code: 93
        },
        NO: {
            text: "NO",
            code: 94
        },
        "跳跳": {
            text: "跳跳",
            code: 95
        },
        "怄火": {
            text: "怄火",
            code: 96
        },
        "转圈": {
            text: "转圈",
            code: 97
        },
        "磕头": {
            text: "磕头",
            code: 98
        },
        "回头": {
            text: "回头",
            code: 99
        },
        "跳绳": {
            text: "跳绳",
            code: 100
        },
        "激动": {
            text: "激动",
            code: 101
        },
        "街舞": {
            text: "街舞",
            code: 102
        },
        "献吻": {
            text: "献吻",
            code: 103
        },
        "左太极": {
            text: "左太极",
            code: 104
        },
        "右太极": {
            text: "右太极",
            code: 105
        },
        "闭嘴": {
            text: "闭嘴",
            code: 106
        },
        "招财进宝": {
            text: "招财进宝",
            code: 107
        },
        "双喜": {
            text: "双喜",
            code: 108
        },
        "鞭炮": {
            text: "鞭炮",
            code: 109
        },
        "灯笼": {
            text: "灯笼",
            code: 110
        },
        "发财": {
            text: "发财",
            code: 111
        },
        "K歌": {
            text: "K歌",
            code: 112
        },
        "购物": {
            text: "购物",
            code: 113
        },
        "邮件": {
            text: "邮件",
            code: 114
        },
        "帅": {
            text: "帅",
            code: 115
        },
        "嘴唇": {
            text: "嘴唇",
            code: 116
        },
        "祈祷": {
            text: "祈祷",
            code: 117
        },
        "爆筋": {
            text: "爆筋",
            code: 118
        },
        "棒棒糖": {
            text: "棒棒糖",
            code: 119
        },
        "喝奶": {
            text: "喝奶",
            code: 120
        },
        "下面": {
            text: "下面",
            code: 121
        },
        "香蕉": {
            text: "香蕉",
            code: 122
        },
        "飞机": {
            text: "飞机",
            code: 123
        },
        "开车": {
            text: "开车",
            code: 124
        },
        "高铁左车头": {
            text: "高铁左车头",
            code: 125
        },
        "车厢": {
            text: "车厢",
            code: 126
        },
        "高铁右车头": {
            text: "高铁右车头",
            code: 127
        },
        "多云": {
            text: "多云",
            code: 128
        },
        "下雨": {
            text: "下雨",
            code: 129
        },
        "钞票": {
            text: "钞票",
            code: 130
        },
        "熊猫": {
            text: "熊猫",
            code: 131
        },
        "灯泡": {
            text: "灯泡",
            code: 132
        },
        "风车": {
            text: "风车",
            code: 133
        },
        "闹钟": {
            text: "闹钟",
            code: 134
        },
        "打伞": {
            text: "打伞",
            code: 135
        },
        "彩球": {
            text: "彩球",
            code: 136
        },
        "钻戒": {
            text: "钻戒",
            code: 137
        },
        "沙发": {
            text: "沙发",
            code: 138
        },
        "纸巾": {
            text: "纸巾",
            code: 139
        },
        "药": {
            text: "药",
            code: 140
        },
        "手枪": {
            text: "手枪",
            code: 141
        },
        "青蛙": {
            text: "青蛙",
            code: 142
        }
    }
      , n = ["口红", "钻戒", "靴子", "灯笼", "灯泡", "点滴", "电视机", "照相机", "风车", "公文包", "购物袋", "海魂衫", "红领巾", "蝴蝶结", "皇冠", "火车头", "火箭", "酒杯", "蜡烛", "八爪鱼", "燕子", "小猫", "猫头鹰", "毛毛虫", "麋鹿", "鸭子", "小鸡", "青蛙", "乌龟", "熊猫", "棕熊", "牛奶", "苹果", "樱桃", "桃子", "橘子", "西瓜", "草莓", "巧克力冰棒", "鸡腿", "太阳", "彩虹", "浮云", "多云", "小雨", "中雨", "大雨", "雪花", "月圆", "汽车", "木马", "闹钟", "铅笔", "雨伞", "沙发", "手柄", "UFO", "醋坛", "孙悟空", "唐僧", "天使", "恶魔", "招财猫", "娃娃头", "卫生纸", "仙人球", "香烟", "烟斗", "板砖", "蝙蝠", "幽灵", "南瓜灯"]
      , o = h.wording = ["微笑", "撇嘴", "色", "发呆", "得意", "流泪", "害羞", "闭嘴", "睡", "大哭", "尴尬", "发怒", "调皮", "呲牙", "惊讶", "难过", "酷", "冷汗", "抓狂", "吐", "偷笑", "可爱", "白眼", "傲慢", "饥饿", "困", "惊恐", "流汗", "憨笑", "大兵", "奋斗", "咒骂", "疑问", "嘘", "晕", "折磨", "衰", "骷髅", "敲打", "再见", "擦汗", "抠鼻", "鼓掌", "糗大了", "坏笑", "左哼哼", "右哼哼", "哈欠", "鄙视", "委屈", "快哭了", "阴险", "亲亲", "吓", "可怜", "菜刀", "西瓜", "啤酒", "篮球", "乒乓", "咖啡", "饭", "猪头", "玫瑰", "凋谢", "", "爱心", "心碎", "蛋糕", "闪电", "炸弹", "刀", "足球", "瓢虫", "便便", "月亮", "太阳", "礼物", "拥抱", "强", "弱", "握手", "胜利", "抱拳", "勾引", "拳头", "差劲", "爱你", "NO", "OK", "爱情", "飞吻", "跳跳", "发抖", "怄火", "转圈", "磕头", "回头", "跳绳", "挥手", "激动", "街舞", "献吻", "左太极", "右太极"];
    h.wording[116] = "示爱";
    for (var p = ["0x00A9", "0x00AE", "0x203C", "0x2049", "0x2122", "0x2139", "0x2194", "0x2195", "0x2196", "0x2197", "0x2198", "0x2199", "0x21A9", "0x21AA", "0x231A", "0x231B", "0x23E9", "0x23EA", "0x23EB", "0x23EC", "0x23F0", "0x23F3", "0x24C2", "0x25AA", "0x25AB", "0x25B6", "0x25C0", "0x25FB", "0x25FC", "0x25FD", "0x25FE", "0x2600", "0x2601", "0x260E", "0x2611", "0x2614", "0x2615", "0x261D", "0x263A", "0x2648", "0x2649", "0x264A", "0x264B", "0x264C", "0x264D", "0x264E", "0x264F", "0x2650", "0x2651", "0x2652", "0x2653", "0x2660", "0x2663", "0x2665", "0x2666", "0x2668", "0x267B", "0x267F", "0x2693", "0x26A0", "0x26A1", "0x26AA", "0x26AB", "0x26BD", "0x26BE", "0x26C4", "0x26C5", "0x26CE", "0x26D4", "0x26EA", "0x26F2", "0x26F3", "0x26F5", "0x26FA", "0x26FD", "0x2702", "0x2705", "0x2708", "0x2709", "0x270A", "0x270B", "0x270C", "0x270F", "0x2712", "0x2714", "0x2716", "0x2728", "0x2733", "0x2734", "0x2744", "0x2747", "0x274C", "0x274E", "0x2753", "0x2754", "0x2755", "0x2757", "0x2764", "0x2795", "0x2796", "0x2797", "0x27A1", "0x27B0", "0x27BF", "0x2934", "0x2935", "0x2B05", "0x2B06", "0x2B07", "0x2B1B", "0x2B1C", "0x2B50", "0x2B55", "0x3030", "0x303D", "0x3297", "0x3299", "0xD83C0xDC04", "0xD83C0xDCCF", "0xD83C0xDD70", "0xD83C0xDD71", "0xD83C0xDD7E", "0xD83C0xDD7F", "0xD83C0xDD8E", "0xD83C0xDD91", "0xD83C0xDD92", "0xD83C0xDD93", "0xD83C0xDD94", "0xD83C0xDD95", "0xD83C0xDD96", "0xD83C0xDD97", "0xD83C0xDD98", "0xD83C0xDD99", "0xD83C0xDD9A", "0xD83C0xDE01", "0xD83C0xDE02", "0xD83C0xDE1A", "0xD83C0xDE2F", "0xD83C0xDE32", "0xD83C0xDE33", "0xD83C0xDE34", "0xD83C0xDE35", "0xD83C0xDE36", "0xD83C0xDE37", "0xD83C0xDE38", "0xD83C0xDE39", "0xD83C0xDE3A", "0xD83C0xDE50", "0xD83C0xDE51", "0xD83C0xDF00", "0xD83C0xDF01", "0xD83C0xDF02", "0xD83C0xDF03", "0xD83C0xDF04", "0xD83C0xDF05", "0xD83C0xDF06", "0xD83C0xDF07", "0xD83C0xDF08", "0xD83C0xDF09", "0xD83C0xDF0A", "0xD83C0xDF0B", "0xD83C0xDF0C", "0xD83C0xDF0D", "0xD83C0xDF0E", "0xD83C0xDF0F", "0xD83C0xDF10", "0xD83C0xDF11", "0xD83C0xDF12", "0xD83C0xDF13", "0xD83C0xDF14", "0xD83C0xDF15", "0xD83C0xDF16", "0xD83C0xDF17", "0xD83C0xDF18", "0xD83C0xDF19", "0xD83C0xDF1A", "0xD83C0xDF1B", "0xD83C0xDF1C", "0xD83C0xDF1D", "0xD83C0xDF1E", "0xD83C0xDF1F", "0xD83C0xDF30", "0xD83C0xDF31", "0xD83C0xDF32", "0xD83C0xDF33", "0xD83C0xDF34", "0xD83C0xDF35", "0xD83C0xDF37", "0xD83C0xDF38", "0xD83C0xDF39", "0xD83C0xDF3A", "0xD83C0xDF3B", "0xD83C0xDF3C", "0xD83C0xDF3D", "0xD83C0xDF3E", "0xD83C0xDF3F", "0xD83C0xDF40", "0xD83C0xDF41", "0xD83C0xDF42", "0xD83C0xDF43", "0xD83C0xDF44", "0xD83C0xDF45", "0xD83C0xDF46", "0xD83C0xDF47", "0xD83C0xDF48", "0xD83C0xDF49", "0xD83C0xDF4A", "0xD83C0xDF4B", "0xD83C0xDF4C", "0xD83C0xDF4D", "0xD83C0xDF4E", "0xD83C0xDF4F", "0xD83C0xDF50", "0xD83C0xDF51", "0xD83C0xDF52", "0xD83C0xDF53", "0xD83C0xDF54", "0xD83C0xDF55", "0xD83C0xDF56", "0xD83C0xDF57", "0xD83C0xDF58", "0xD83C0xDF59", "0xD83C0xDF5A", "0xD83C0xDF5B", "0xD83C0xDF5C", "0xD83C0xDF5D", "0xD83C0xDF5E", "0xD83C0xDF5F", "0xD83C0xDF60", "0xD83C0xDF61", "0xD83C0xDF62", "0xD83C0xDF63", "0xD83C0xDF64", "0xD83C0xDF65", "0xD83C0xDF66", "0xD83C0xDF67", "0xD83C0xDF68", "0xD83C0xDF69", "0xD83C0xDF6A", "0xD83C0xDF6B", "0xD83C0xDF6C", "0xD83C0xDF6D", "0xD83C0xDF6E", "0xD83C0xDF6F", "0xD83C0xDF70", "0xD83C0xDF71", "0xD83C0xDF72", "0xD83C0xDF73", "0xD83C0xDF74", "0xD83C0xDF75", "0xD83C0xDF76", "0xD83C0xDF77", "0xD83C0xDF78", "0xD83C0xDF79", "0xD83C0xDF7A", "0xD83C0xDF7B", "0xD83C0xDF7C", "0xD83C0xDF80", "0xD83C0xDF81", "0xD83C0xDF82", "0xD83C0xDF83", "0xD83C0xDF84", "0xD83C0xDF85", "0xD83C0xDF86", "0xD83C0xDF87", "0xD83C0xDF88", "0xD83C0xDF89", "0xD83C0xDF8A", "0xD83C0xDF8B", "0xD83C0xDF8C", "0xD83C0xDF8D", "0xD83C0xDF8E", "0xD83C0xDF8F", "0xD83C0xDF90", "0xD83C0xDF91", "0xD83C0xDF92", "0xD83C0xDF93", "0xD83C0xDFA0", "0xD83C0xDFA1", "0xD83C0xDFA2", "0xD83C0xDFA3", "0xD83C0xDFA4", "0xD83C0xDFA5", "0xD83C0xDFA6", "0xD83C0xDFA7", "0xD83C0xDFA8", "0xD83C0xDFA9", "0xD83C0xDFAA", "0xD83C0xDFAB", "0xD83C0xDFAC", "0xD83C0xDFAD", "0xD83C0xDFAE", "0xD83C0xDFAF", "0xD83C0xDFB0", "0xD83C0xDFB1", "0xD83C0xDFB2", "0xD83C0xDFB3", "0xD83C0xDFB4", "0xD83C0xDFB5", "0xD83C0xDFB6", "0xD83C0xDFB7", "0xD83C0xDFB8", "0xD83C0xDFB9", "0xD83C0xDFBA", "0xD83C0xDFBB", "0xD83C0xDFBC", "0xD83C0xDFBD", "0xD83C0xDFBE", "0xD83C0xDFBF", "0xD83C0xDFC0", "0xD83C0xDFC1", "0xD83C0xDFC2", "0xD83C0xDFC3", "0xD83C0xDFC4", "0xD83C0xDFC6", "0xD83C0xDFC7", "0xD83C0xDFC8", "0xD83C0xDFC9", "0xD83C0xDFCA", "0xD83C0xDFE0", "0xD83C0xDFE1", "0xD83C0xDFE2", "0xD83C0xDFE3", "0xD83C0xDFE4", "0xD83C0xDFE5", "0xD83C0xDFE6", "0xD83C0xDFE7", "0xD83C0xDFE8", "0xD83C0xDFE9", "0xD83C0xDFEA", "0xD83C0xDFEB", "0xD83C0xDFEC", "0xD83C0xDFED", "0xD83C0xDFEE", "0xD83C0xDFEF", "0xD83C0xDFF0", "0xD83D0xDC00", "0xD83D0xDC01", "0xD83D0xDC02", "0xD83D0xDC03", "0xD83D0xDC04", "0xD83D0xDC05", "0xD83D0xDC06", "0xD83D0xDC07", "0xD83D0xDC08", "0xD83D0xDC09", "0xD83D0xDC0A", "0xD83D0xDC0B", "0xD83D0xDC0C", "0xD83D0xDC0D", "0xD83D0xDC0E", "0xD83D0xDC0F", "0xD83D0xDC10", "0xD83D0xDC11", "0xD83D0xDC12", "0xD83D0xDC13", "0xD83D0xDC14", "0xD83D0xDC15", "0xD83D0xDC16", "0xD83D0xDC17", "0xD83D0xDC18", "0xD83D0xDC19", "0xD83D0xDC1A", "0xD83D0xDC1B", "0xD83D0xDC1C", "0xD83D0xDC1D", "0xD83D0xDC1E", "0xD83D0xDC1F", "0xD83D0xDC20", "0xD83D0xDC21", "0xD83D0xDC22", "0xD83D0xDC23", "0xD83D0xDC24", "0xD83D0xDC25", "0xD83D0xDC26", "0xD83D0xDC27", "0xD83D0xDC28", "0xD83D0xDC29", "0xD83D0xDC2A", "0xD83D0xDC2B", "0xD83D0xDC2C", "0xD83D0xDC2D", "0xD83D0xDC2E", "0xD83D0xDC2F", "0xD83D0xDC30", "0xD83D0xDC31", "0xD83D0xDC32", "0xD83D0xDC33", "0xD83D0xDC34", "0xD83D0xDC35", "0xD83D0xDC36", "0xD83D0xDC37", "0xD83D0xDC38", "0xD83D0xDC39", "0xD83D0xDC3A", "0xD83D0xDC3B", "0xD83D0xDC3C", "0xD83D0xDC3D", "0xD83D0xDC3E", "0xD83D0xDC40", "0xD83D0xDC42", "0xD83D0xDC43", "0xD83D0xDC44", "0xD83D0xDC45", "0xD83D0xDC46", "0xD83D0xDC47", "0xD83D0xDC48", "0xD83D0xDC49", "0xD83D0xDC4A", "0xD83D0xDC4B", "0xD83D0xDC4C", "0xD83D0xDC4D", "0xD83D0xDC4E", "0xD83D0xDC4F", "0xD83D0xDC50", "0xD83D0xDC51", "0xD83D0xDC52", "0xD83D0xDC53", "0xD83D0xDC54", "0xD83D0xDC55", "0xD83D0xDC56", "0xD83D0xDC57", "0xD83D0xDC58", "0xD83D0xDC59", "0xD83D0xDC5A", "0xD83D0xDC5B", "0xD83D0xDC5C", "0xD83D0xDC5D", "0xD83D0xDC5E", "0xD83D0xDC5F", "0xD83D0xDC60", "0xD83D0xDC61", "0xD83D0xDC62", "0xD83D0xDC63", "0xD83D0xDC64", "0xD83D0xDC65", "0xD83D0xDC66", "0xD83D0xDC67", "0xD83D0xDC68", "0xD83D0xDC69", "0xD83D0xDC6A", "0xD83D0xDC6B", "0xD83D0xDC6C", "0xD83D0xDC6D", "0xD83D0xDC6E", "0xD83D0xDC6F", "0xD83D0xDC70", "0xD83D0xDC71", "0xD83D0xDC72", "0xD83D0xDC73", "0xD83D0xDC74", "0xD83D0xDC75", "0xD83D0xDC76", "0xD83D0xDC77", "0xD83D0xDC78", "0xD83D0xDC79", "0xD83D0xDC7A", "0xD83D0xDC7B", "0xD83D0xDC7C", "0xD83D0xDC7D", "0xD83D0xDC7E", "0xD83D0xDC7F", "0xD83D0xDC80", "0xD83D0xDC81", "0xD83D0xDC82", "0xD83D0xDC83", "0xD83D0xDC84", "0xD83D0xDC85", "0xD83D0xDC86", "0xD83D0xDC87", "0xD83D0xDC88", "0xD83D0xDC89", "0xD83D0xDC8A", "0xD83D0xDC8B", "0xD83D0xDC8C", "0xD83D0xDC8D", "0xD83D0xDC8E", "0xD83D0xDC8F", "0xD83D0xDC90", "0xD83D0xDC91", "0xD83D0xDC92", "0xD83D0xDC93", "0xD83D0xDC94", "0xD83D0xDC95", "0xD83D0xDC96", "0xD83D0xDC97", "0xD83D0xDC98", "0xD83D0xDC99", "0xD83D0xDC9A", "0xD83D0xDC9B", "0xD83D0xDC9C", "0xD83D0xDC9D", "0xD83D0xDC9E", "0xD83D0xDC9F", "0xD83D0xDCA0", "0xD83D0xDCA1", "0xD83D0xDCA2", "0xD83D0xDCA3", "0xD83D0xDCA4", "0xD83D0xDCA5", "0xD83D0xDCA6", "0xD83D0xDCA7", "0xD83D0xDCA8", "0xD83D0xDCA9", "0xD83D0xDCAA", "0xD83D0xDCAB", "0xD83D0xDCAC", "0xD83D0xDCAD", "0xD83D0xDCAE", "0xD83D0xDCAF", "0xD83D0xDCB0", "0xD83D0xDCB1", "0xD83D0xDCB2", "0xD83D0xDCB3", "0xD83D0xDCB4", "0xD83D0xDCB5", "0xD83D0xDCB6", "0xD83D0xDCB7", "0xD83D0xDCB8", "0xD83D0xDCB9", "0xD83D0xDCBA", "0xD83D0xDCBB", "0xD83D0xDCBC", "0xD83D0xDCBD", "0xD83D0xDCBE", "0xD83D0xDCBF", "0xD83D0xDCC0", "0xD83D0xDCC1", "0xD83D0xDCC2", "0xD83D0xDCC3", "0xD83D0xDCC4", "0xD83D0xDCC5", "0xD83D0xDCC6", "0xD83D0xDCC7", "0xD83D0xDCC8", "0xD83D0xDCC9", "0xD83D0xDCCA", "0xD83D0xDCCB", "0xD83D0xDCCC", "0xD83D0xDCCD", "0xD83D0xDCCE", "0xD83D0xDCCF", "0xD83D0xDCD0", "0xD83D0xDCD1", "0xD83D0xDCD2", "0xD83D0xDCD3", "0xD83D0xDCD4", "0xD83D0xDCD5", "0xD83D0xDCD6", "0xD83D0xDCD7", "0xD83D0xDCD8", "0xD83D0xDCD9", "0xD83D0xDCDA", "0xD83D0xDCDB", "0xD83D0xDCDC", "0xD83D0xDCDD", "0xD83D0xDCDE", "0xD83D0xDCDF", "0xD83D0xDCE0", "0xD83D0xDCE1", "0xD83D0xDCE2", "0xD83D0xDCE3", "0xD83D0xDCE4", "0xD83D0xDCE5", "0xD83D0xDCE6", "0xD83D0xDCE7", "0xD83D0xDCE8", "0xD83D0xDCE9", "0xD83D0xDCEA", "0xD83D0xDCEB", "0xD83D0xDCEC", "0xD83D0xDCED", "0xD83D0xDCEE", "0xD83D0xDCEF", "0xD83D0xDCF0", "0xD83D0xDCF1", "0xD83D0xDCF2", "0xD83D0xDCF3", "0xD83D0xDCF4", "0xD83D0xDCF5", "0xD83D0xDCF6", "0xD83D0xDCF7", "0xD83D0xDCF9", "0xD83D0xDCFA", "0xD83D0xDCFB", "0xD83D0xDCFC", "0xD83D0xDD00", "0xD83D0xDD01", "0xD83D0xDD02", "0xD83D0xDD03", "0xD83D0xDD04", "0xD83D0xDD05", "0xD83D0xDD06", "0xD83D0xDD07", "0xD83D0xDD09", "0xD83D0xDD0A", "0xD83D0xDD0B", "0xD83D0xDD0C", "0xD83D0xDD0D", "0xD83D0xDD0E", "0xD83D0xDD0F", "0xD83D0xDD10", "0xD83D0xDD11", "0xD83D0xDD12", "0xD83D0xDD13", "0xD83D0xDD14", "0xD83D0xDD15", "0xD83D0xDD16", "0xD83D0xDD17", "0xD83D0xDD18", "0xD83D0xDD19", "0xD83D0xDD1A", "0xD83D0xDD1B", "0xD83D0xDD1C", "0xD83D0xDD1D", "0xD83D0xDD1E", "0xD83D0xDD1F", "0xD83D0xDD20", "0xD83D0xDD21", "0xD83D0xDD22", "0xD83D0xDD23", "0xD83D0xDD24", "0xD83D0xDD25", "0xD83D0xDD26", "0xD83D0xDD27", "0xD83D0xDD28", "0xD83D0xDD29", "0xD83D0xDD2A", "0xD83D0xDD2B", "0xD83D0xDD2C", "0xD83D0xDD2D", "0xD83D0xDD2E", "0xD83D0xDD2F", "0xD83D0xDD30", "0xD83D0xDD31", "0xD83D0xDD32", "0xD83D0xDD33", "0xD83D0xDD34", "0xD83D0xDD35", "0xD83D0xDD36", "0xD83D0xDD37", "0xD83D0xDD38", "0xD83D0xDD39", "0xD83D0xDD3A", "0xD83D0xDD3B", "0xD83D0xDD3C", "0xD83D0xDD3D", "0xD83D0xDD50", "0xD83D0xDD51", "0xD83D0xDD52", "0xD83D0xDD53", "0xD83D0xDD54", "0xD83D0xDD55", "0xD83D0xDD56", "0xD83D0xDD57", "0xD83D0xDD58", "0xD83D0xDD59", "0xD83D0xDD5A", "0xD83D0xDD5B", "0xD83D0xDD5C", "0xD83D0xDD5D", "0xD83D0xDD5E", "0xD83D0xDD5F", "0xD83D0xDD60", "0xD83D0xDD61", "0xD83D0xDD62", "0xD83D0xDD63", "0xD83D0xDD64", "0xD83D0xDD65", "0xD83D0xDD66", "0xD83D0xDD67", "0xD83D0xDDFB", "0xD83D0xDDFC", "0xD83D0xDDFD", "0xD83D0xDDFE", "0xD83D0xDDFF", "0xD83D0xDE00", "0xD83D0xDE01", "0xD83D0xDE02", "0xD83D0xDE03", "0xD83D0xDE04", "0xD83D0xDE05", "0xD83D0xDE06", "0xD83D0xDE07", "0xD83D0xDE08", "0xD83D0xDE09", "0xD83D0xDE0A", "0xD83D0xDE0B", "0xD83D0xDE0C", "0xD83D0xDE0D", "0xD83D0xDE0E", "0xD83D0xDE0F", "0xD83D0xDE10", "0xD83D0xDE11", "0xD83D0xDE12", "0xD83D0xDE13", "0xD83D0xDE14", "0xD83D0xDE15", "0xD83D0xDE16", "0xD83D0xDE17", "0xD83D0xDE18", "0xD83D0xDE19", "0xD83D0xDE1A", "0xD83D0xDE1B", "0xD83D0xDE1C", "0xD83D0xDE1D", "0xD83D0xDE1E", "0xD83D0xDE1F", "0xD83D0xDE20", "0xD83D0xDE21", "0xD83D0xDE22", "0xD83D0xDE23", "0xD83D0xDE24", "0xD83D0xDE25", "0xD83D0xDE26", "0xD83D0xDE27", "0xD83D0xDE28", "0xD83D0xDE29", "0xD83D0xDE2A", "0xD83D0xDE2B", "0xD83D0xDE2C", "0xD83D0xDE2D", "0xD83D0xDE2E", "0xD83D0xDE2F", "0xD83D0xDE30", "0xD83D0xDE31", "0xD83D0xDE32", "0xD83D0xDE33", "0xD83D0xDE34", "0xD83D0xDE35", "0xD83D0xDE36", "0xD83D0xDE37", "0xD83D0xDE38", "0xD83D0xDE39", "0xD83D0xDE3A", "0xD83D0xDE3B", "0xD83D0xDE3C", "0xD83D0xDE3D", "0xD83D0xDE3E", "0xD83D0xDE3F", "0xD83D0xDE40", "0xD83D0xDE45", "0xD83D0xDE46", "0xD83D0xDE47", "0xD83D0xDE48", "0xD83D0xDE49", "0xD83D0xDE4A", "0xD83D0xDE4B", "0xD83D0xDE4C", "0xD83D0xDE4D", "0xD83D0xDE4E", "0xD83D0xDE4F", "0xD83D0xDE80", "0xD83D0xDE81", "0xD83D0xDE82", "0xD83D0xDE83", "0xD83D0xDE84", "0xD83D0xDE85", "0xD83D0xDE86", "0xD83D0xDE87", "0xD83D0xDE88", "0xD83D0xDE89", "0xD83D0xDE8A", "0xD83D0xDE8C", "0xD83D0xDE8D", "0xD83D0xDE8E", "0xD83D0xDE8F", "0xD83D0xDE90", "0xD83D0xDE91", "0xD83D0xDE92", "0xD83D0xDE93", "0xD83D0xDE94", "0xD83D0xDE95", "0xD83D0xDE96", "0xD83D0xDE97", "0xD83D0xDE98", "0xD83D0xDE99", "0xD83D0xDE9A", "0xD83D0xDE9B", "0xD83D0xDE9C", "0xD83D0xDE9D", "0xD83D0xDE9E", "0xD83D0xDE9F", "0xD83D0xDEA0", "0xD83D0xDEA1", "0xD83D0xDEA2", "0xD83D0xDEA3", "0xD83D0xDEA4", "0xD83D0xDEA5", "0xD83D0xDEA6", "0xD83D0xDEA7", "0xD83D0xDEA8", "0xD83D0xDEA9", "0xD83D0xDEAA", "0xD83D0xDEAB", "0xD83D0xDEAC", "0xD83D0xDEAD", "0xD83D0xDEAE", "0xD83D0xDEAF", "0xD83D0xDEB0", "0xD83D0xDEB1", "0xD83D0xDEB2", "0xD83D0xDEB3", "0xD83D0xDEB4", "0xD83D0xDEB5", "0xD83D0xDEB6", "0xD83D0xDEB7", "0xD83D0xDEB8", "0xD83D0xDEB9", "0xD83D0xDEBA", "0xD83D0xDEBB", "0xD83D0xDEBC", "0xD83D0xDEBD", "0xD83D0xDEBE", "0xD83D0xDEBF", "0xD83D0xDEC0", "0xD83D0xDEC1", "0xD83D0xDEC2", "0xD83D0xDEC3", "0xD83D0xDEC4", "0xD83D0xDEC5", "0x00230x20E3", "0x00300x20E3", "0x00310x20E3", "0x00320x20E3", "0x00330x20E3", "0x00340x20E3", "0x00350x20E3", "0x00360x20E3", "0x00370x20E3", "0x00380x20E3", "0x00390x20E3", "0xD83C0xDDE80xD83C0xDDF3", "0xD83C0xDDE90xD83C0xDDEA", "0xD83C0xDDEA0xD83C0xDDF8", "0xD83C0xDDEB0xD83C0xDDF7", "0xD83C0xDDEC0xD83C0xDDE7", "0xD83C0xDDEE0xD83C0xDDF9", "0xD83C0xDDEF0xD83C0xDDF5", "0xD83C0xDDF00xD83C0xDDF7", "0xD83C0xDDF70xD83C0xDDFA", "0xD83C0xDDFA0xD83C0xDDF8"], q = [], r = /<img.+?rel="emoji".+?name="([\w]+?)".+?\/?>/gi, s = 0; s < p.length; s++)
        q.push(new RegExp(p[s].replace(/0x/g, "\\u"),"g"));
    !function() {
        if (j || (j = {},
        o.forEach(function(a, b) {
            j[a] = b
        })),
        k || (k = {},
        n.forEach(function(a, b) {
            k[a] = b + 1
        })),
        !i) {
            i = [];
            for (var a in m)
                if (m.hasOwnProperty(a)) {
                    var b = m[a].code - 0;
                    i[b] = a
                }
        }
        window.MOBLIE_FACE_ARRAY = i,
        window.faceWordingMap = j,
        window.lnWordingMap = k
    }();
    var t = /\[\/([\u4e00-\u9fa5OKN]{1,3})\]/g;
    h.code2Img = function(a) {
        return a ? a.replace(t, function(a, b) {
            var c = o.indexOf(b);
            return c > -1 ? '<img class="face" title="' + b + '" src="http://0.web.qstatic.com/webqqpic/style/face/' + c + '.gif" alt="' + d(b) + '" rel="face">' : a
        }) : a
    }
    ,
    h.decodeRichText = function(b) {
        return c(a(h.code2Img(u(b))))
    }
    ;
    var u = h.decode = function(a) {
        function b(a, b, c) {
            return b || c ? String.fromCharCode(b || c) : f[a] || a
        }
        var c = /&quot;|&lt;|&gt;|&amp;|&nbsp;|&apos;|&#(\d+);|&#(\d+)/g
          , d = /\u00a0/g
          , e = /<br\s*\/?>/gi
          , f = {
            "&quot;": '"',
            "&lt;": "<",
            "&gt;": ">",
            "&amp;": "&",
            "&nbsp;": " "
        };
        return a ? ("" + a).replace(e, "\n").replace(c, b).replace(d, " ") : ""
    }
    ;
    return h.getText = function(a) {
        return e(f(a).replace(/(\r\n|\n|\r)/gm, " "))
    }
    ,
    h.getTextLength = function(a) {
        var b = 0;
        if (a) {
            a = a.replace(/\[\/([\u4e00-\u9fa5OKN]{1,3})\]/g, "吃");
            var c = a.match(/[^\x00-\xff]/g) || []
              , d = a.replace(/[^\x00-\xff]/g, "");
            return c.length + d.length / 3 + .9
        }
        return b
    }
    ,
    h.faceCode2MoblieCode = function(a) {
        return a.replace(/\[\/([\u4e00-\u9fa5OKN]{1,3})\]/gi, function(a, b) {
            var c = m[b];
            return c ? g(c.code) : a
        })
    }
    ,
    h.moblieCode2Text = function(a) {
        return a.replace(/\u0014([\s\S])/gi, function(a, c) {
            var d = i[parseInt(b(c))];
            return d ? "/" + d : a
        })
    }
    ,
    h.imgs2MoblieCode = function(a) {
        return a.replace(/<img.+?\/?>/gi, function(a) {
            var b = a.match(/alt="(\[\/[\u4e00-\u9fa5OKN]{1,3}\])"/);
            return b ? h.faceCode2MoblieCode(b[1]) : a
        })
    }
    ,
    h.imgs2Code = function(a) {
        return a.replace(r, function(a, b) {
            for (var c = b.split("0x"), d = [], e = 1; e < c.length; e++)
                d.push(parseInt(c[e], 16));
            return String.fromCharCode.apply(void 0, d)
        })
    }
    ,
    h
}),
function(a, b) {
    "function" == typeof define && define.amd ? define(b) : "undefined" == typeof document ? module.exports = b() : a.TmplInline_relativegroup = b()
}(this, function() {
    var a = {}
      , b = function(a, b) {
        function c(b) {
            return d("undefined" == typeof a[b] ? this[b] : a[b])
        }
        function d(a) {
            return "undefined" == typeof a ? "" : a
        }
        a = a || {};
        var e = c("group_list")
          , f = "";
        if (f += " ",
        "undefined" == typeof e && (e = []),
        e.length)
            for (var g = 0; g < e.length; g++) {
                var h = e[g];
                f += ' <li class="ui-item ui-ignore-space" data-code="',
                f += d(h.code),
                f += '" data-url="',
                f += d(h.ticket),
                f += '"> <div class="avatar"> <img src="',
                f += d(h.glogo),
                f += '" class="group-icon" /> </div> <div class="message"> <p class="ui-no-wrap grouptitle">',
                f += d(h.name),
                f += '</p> <div class="tags"> ';
                for (var i = 0; i < h.labelList.length; i++) {
                    var j = h.labelList[i];
                    f += ' <div class="tips tips',
                    f += d(j.type),
                    f += '" style="background-color:#',
                    f += d(j.edging_color),
                    f += ";color:#",
                    f += d(j.text_color),
                    f += ';">',
                    f += d(j.name),
                    f += "</div> "
                }
                f += ' </div> <p class="desc">',
                f += d(h.memo),
                f += "</p> </div> </li> "
            }
        else
            f += ' <li class="no-content">暂无同城群</li> ';
        return f += " "
    }
    ;
    a.tab1 = "TmplInline_relativegroup.tab1",
    Tmpl.addTmpl(a.tab1, b);
    var c = function(a, b) {
        function c(b) {
            return d("undefined" == typeof a[b] ? this[b] : a[b])
        }
        function d(a) {
            return "undefined" == typeof a ? "" : a
        }
        a = a || {};
        var e = c("group_list")
          , f = "";
        if (f += " ",
        "undefined" == typeof e && (e = []),
        e.length) {
            window.hotCityPostRecord = {};
            for (var g = 0; g < e.length; g++) {
                var h = e[g];
                if (!window.hotCityPostRecord[h.code]) {
                    window.hotCityPostRecord[h.code] = 1,
                    f += ' <li class="ui-item ui-ignore-space" data-code="',
                    f += d(h.code),
                    f += '" data-url="',
                    f += d(h.ticket),
                    f += '"> <div class="avatar"> <img src="',
                    f += d(h.glogo),
                    f += '" class="group-icon" /> </div> <div class="message"> <p class="ui-no-wrap grouptitle">',
                    f += d(h.name),
                    f += '</p> <div class="tags"> ';
                    for (var i = 0; i < h.labelList.length; i++) {
                        var j = h.labelList[i];
                        f += ' <div class="tips tips',
                        f += d(j.type),
                        f += '" style="background-color:#',
                        f += d(j.edging_color),
                        f += ";color:#",
                        f += d(j.text_color),
                        f += ';">',
                        f += d(j.name),
                        f += "</div> "
                    }
                    f += ' </div> <p class="desc">',
                    f += d(h.memo),
                    f += "</p> </div> </li> "
                }
            }
        } else
            f += ' <li class="no-content">暂无热门群</li> ';
        return f += " "
    }
    ;
    a.tab2 = "TmplInline_relativegroup.tab2",
    Tmpl.addTmpl(a.tab2, c);
    var d = function(a, b) {
        function c(b) {
            return d("undefined" == typeof a[b] ? this[b] : a[b])
        }
        function d(a) {
            return "undefined" == typeof a ? "" : a
        }
        a = a || {};
        var e = c("relation")
          , f = c("isNoContent")
          , g = c("RegExp")
          , h = c("g")
          , i = c("isSub")
          , j = c("is_chief")
          , k = "";
        if (k += " ",
        "undefined" == typeof e && (e = []),
        f)
            k += ' <li class="no-content">暂无关联群</li> ';
        else
            for (var l = 0; l < e.length; l++) {
                var m = e[l]
                  , n = new g("\\n",h);
                k += ' <li class="ui-item ui-ignore-space" data-code="',
                k += d(m.code),
                k += '" data-url="',
                k += d(m.ticket),
                k += '"> <div class="avatar"> <img src="',
                k += d(m.glogo || m.face_url),
                k += '" class="group-icon" /> </div> <div class="message"> <p class="ui-no-wrap grouptitle"> ',
                k += d(m.name.replace(n, "&lt;br>")),
                k += " ",
                i || (k += ' <span class="relative honour border-1px">官方</span> '),
                k += ' </p> <p class="ui-gray">',
                k += d(m.member_num),
                k += "人 ",
                j && (k += '<span class="icon-del" data-action="del" data-name="',
                k += d(m.name.replace(n, "&lt;br>")),
                k += '" ></span>'),
                k += '</p> <p class="ui-gray ui-no-wrap">',
                k += d(m.fingermemo || m.memo),
                k += "</p> </div> </li> "
            }
        return k += " "
    }
    ;
    return a.tab3 = "TmplInline_relativegroup.tab3",
    Tmpl.addTmpl(a.tab3, d),
    a
}),
function(a, b) {
    a.RelativeGroup = b(a.Face)
}(this, function(a) {
    function b(b) {
        var d, e, f, g, h, i, k, l, m, n, o = c - 87, p = 60, q = o / p | 0;
        for (d = 0; d < b.result.group_list.length; d++) {
            if (l = b.result.group_list[d],
            l.memo = a.decodeRichText(l.memo),
            l.memo = l.memo.replace(/&nbsp;/g, " ").replace(/<br>/g, " ").replace(/<p>/g, " ").replace(/<\/p>/g, " ").replace(/<div>/g, " ").replace(/<\/div>/g, " "),
            h = [],
            "undefined" != typeof l.label)
                if (i = l.label,
                i.length > q) {
                    for (m = i.length - q,
                    n = j === !1 ? 1 : 2; m--; )
                        i.length >= 2 && i.splice(i.length - n, 1);
                    h = i
                } else
                    h = i;
            for (e = 0,
            f = h.length; f > e; e++)
                g = h[e],
                k = function(a) {
                    var b = {
                        1: "-number",
                        2: "-server",
                        3: "-business",
                        4: "-distance",
                        1000: "-operat-new",
                        1001: "-operat-hot"
                    };
                    return b[a] ? b[a] : a >= 1e3 && 2e3 > a ? "-operat" : void 0
                }
                ,
                g.type = k(g.attr),
                "-distance" === g.type && (g.edging_color = null );
            l.labelList = h
        }
    }
    Q.setMonitorMap({
        error: 392502,
        pv: 428050,
        oneClick: 392504,
        twoClick: 392505,
        threeClick: 392506,
        cgiCount: 392507,
        mqqapiError: 394204,
        localLocationFetch: 486620,
        newLocationFetch: 486621,
        locationError: 2018271
    });
    var c = document.documentElement.clientWidth || document.body.clientWidth
      , d = Login.getUin()
      , e = Util.queryString("bid") || Util.getHash("bid")
      , f = {
        report: function(a, b, c) {
            var d = {
                opername: "Grp_tribe",
                module: c || "rela_grp",
                obj1: b,
                ver1: e,
                action: a
            };
            b || delete d.obj1,
            Q.tdw(d)
        }
    }
      , g = Util.getHash("target")
      , h = "0" === mqq.QQVersion
      , i = 20
      , j = localStorage.getItem(d + "-lat")
      , k = localStorage.getItem(d + "-lon")
      , l = localStorage.getItem(d + "-location-time")
      , m = 18e5
      , n = !1
      , o = function() {}
    ;
    j && j && l ? Date.now() - l > m || j >= -180 && 180 >= j && 0 !== parseInt(j) || k >= -180 && 180 >= k && 0 !== parseInt(k) ? n = !0 : Q.monitorMap("localLocationFetch") : n = !0,
    n && mqq.sensor.getLocation(function(a, b, c) {
        if (Q.monitorMap("newLocationFetch"),
        0 === a) {
            var e = parseInt(1e6 * (b || 0), 10)
              , f = parseInt(1e6 * (c || 0), 10);
            localStorage.setItem(d + "-lat", e),
            localStorage.setItem(d + "-lon", f),
            localStorage.setItem(d + "-location-time", Date.now()),
            j = e,
            k = f
        }
        o()
    });
    var p = navigator.userAgent.match(/\/qqdownloader\/(\d+)?/);
    p && (mqq.sensor.getLocation = function(a) {
        var b = 0
          , c = 0
          , d = 0;
        a && a(b, c, d)
    }
    );
    var q = {
        renderTmpl: window.TmplInline_relativegroup.tab2,
        renderContainer: "#hot_list",
        comment: "relativegroupHot",
        cgiName: "/cgi-bin/bar/qunlist_search",
        param: function() {
            var a = 0
              , b = {
                retype: 1,
                page: a,
                wantnum: i,
                city_flag: 2,
                ver: 1,
                from: 1,
                bid: e
            };
            return h && (b.link = 1),
            function() {
                return b.keyword = s,
                b.page = a++,
                b
            }
        }(),
        beforeRequest: function() {
            $("#hot_list_loading").css({
                opacity: 1
            })
        },
        processData: function(a) {
            b(a),
            $("#hot_list_loading").css({
                opacity: 1
            }),
            $("#hot_list_loading").html("<span>载入中，请稍候</span>"),
            a.result.endflag > 0 && (a.result.group_list || (a.result.group_list = []),
            this.freeze(),
            $("#hot_list_loading").html("已全部加载"))
        },
        error: function(a) {
            $(".ui-top-bar").addClass("hide"),
            $(".ui-list").addClass("hide"),
            $("#hot_list_loading").html("<span>网络错误,请重试</span>"),
            1e5 !== a.retcode && Tip.show("获取相关群失败[" + a.retcode + "]", {
                type: "warning"
            })
        },
        complete: function(a, b) {
            b > 0 && 0 === a.retcode && a.result.group_list.length < 1 && $("#hot_list_loading").hide(),
            $("#hot_list_loading").css("marginTop", "-20px")
        }
    }
      , r = {
        renderTmpl: window.TmplInline_relativegroup.tab1,
        comment: "relativegroupCity",
        renderContainer: "#city_list",
        cgiName: "/cgi-bin/bar/qunlist_search",
        param: function() {
            var a = 0
              , b = {
                retype: 1,
                page: a,
                wantnum: 20,
                city_flag: 1,
                ver: 1,
                from: 1,
                bid: e
            };
            return h && (b.link = 1),
            function() {
                return b.lat = j || 0,
                b.lon = k || 0,
                b.keyword = s,
                b.page = a++,
                b
            }
        }(),
        beforeRequest: function() {
            $("#city_list_loading").css({
                opacity: 1
            })
        },
        processData: function(a) {
            b(a),
            $("#city_list_loading").css({
                opacity: 1
            }),
            $("#city_list_loading").html("<span>载入中，请稍候</span>"),
            a.result.endflag > 0 && (a.result.group_list || (a.result.group_list = []),
            $("#city_list_loading").html("<span>已全部加载</span>"),
            this.freeze())
        },
        error: function(a) {
            $(".ui-top-bar").addClass("hide"),
            $(".ui-list").addClass("hide"),
            $("#city_list_loading").html("<span>网络错误,请重试</span>"),
            Tip.show("获取相关群失败[" + a.retcode + "]", {
                type: "warning"
            })
        },
        complete: function(a, b) {
            b > 0 && 0 === a.retcode && a.result.group_list.length < 1 && $("#city_list_loading").hide(),
            $("#city_list_loading").css("marginTop", "-20px")
        }
    };
    $.os.android && $("html,body").addClass("android"),
    $.os.ios && (q.scrollEl = $("#page1"),
    r.scrollEl = $("#page1"));
    var s = decodeURIComponent(Util.queryString("keyword"))
      , t = new mutitabModel
      , u = new scrollModel(q)
      , v = new scrollModel(r)
      , w = ""
      , x = {
        init: function() {
            $(".act_tab").removeClass("js-active"),
            $(".ui_page").removeClass("js-active"),
            t.add("#hot", u),
            t.add("#city", v),
            "city" === g ? t.init("#city") : t.init("#hot"),
            t.ontabswitch(function(a) {
                $(".ui-top-bar").removeClass("hide"),
                $(".ui-list").removeClass("hide"),
                $(".act_tab").removeClass("js-active"),
                $(".ui_page").removeClass("js-active"),
                w = a,
                "#hot" === a && f.report("Clk_hot_page"),
                "#city" === a && f.report("Clk_nearby_page"),
                $(a).addClass("js-active"),
                $(a + "_page").addClass("js-active")
            }),
            "city" !== g ? t.rock() : k && j ? t.rock() : o = function() {
                t.rock()
            }
        }
    }
      , y = {
        bind: function() {
            function a() {
                c && (c.removeClass("hovered"),
                c = null ),
                d && (clearTimeout(d),
                d = null )
            }
            function b(a) {
                var b = a;
                b.addClass("hovered");
                var c = b.data("code");
                setTimeout(function() {
                    b.removeClass("hovered");
                    try {
                        var a = b.data("url")
                          , d = "#hot" === w ? 1 : 0;
                        "0" === mqq.QQVersion && -1 === navigator.userAgent.indexOf("QQHD") ? Util.openUrl(a, !0, "", !0) : mqq.invokeSchema("mqqapi", "card", "show_pslcard", {
                            uin: c,
                            card_type: "group",
                            tab_index: d,
                            wSourceSubID: 23
                        }, function() {}),
                        f.report("Clk_grp", {
                            ver3: c
                        }),
                        1 === d && f.report("Clk_hot", c),
                        0 === d && f.report("Clk_nearby", c)
                    } catch (e) {
                        Q.monitorMap("mqqapiError")
                    }
                }, 250)
            }
            var c, d, e = $("body");
            e.on("touchstart", ".ui-item", function() {
                c = $(this),
                d = setTimeout(function() {
                    c.addClass("hovered")
                }, 100)
            }).on("touchmove touchcancel", ".ui-item", function() {
                a()
            }).on("touchend", ".ui-item", function() {
                a()
            }).on("press", ".ui-item", function() {
                b($(this))
            }).on("scroll", a),
            e.on("tap", ".ui-item", function() {
                b($(this))
            })
        }
    }
      , z = {
        init: function() {
            s = Util.queryString("keyword") || "",
            $(window).on("hashchange", function() {
                var a = Util.getHash("poi");
                a || !Publish || Publish.isNative || Publish.hidePoiList()
            }),
            x.init(),
            y.bind(),
            Q.monitorMap("pv")
        }
    };
    return z
});
