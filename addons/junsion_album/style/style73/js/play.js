/* jsTunnel **********************************/
var jsTunnel = {
    callExtend: function(){
        switch(arguments[0]) {
            case 'getPics':
                return mvCfg.pics;
        }
    },
    egretCallBack : undefined
}