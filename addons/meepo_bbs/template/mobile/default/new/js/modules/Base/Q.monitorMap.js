!function() {
    function a(a) {
        if (d[0]) {
            var b = "[" + d + "]"
              , e = c + "monitors=" + b + "&t=" + Q.getTimestamp();
            d = [],
            Q.send(e, a)
        } else
            a && a()
    }
    function b(b, e, f) {
        if (e) {
            var g = c + "monitors=[" + b + "]&t=" + Q.getTimestamp();
            Q.send(g, f, !0)
        } else
            d.push(b),
            Q.tick(a)
    }
    var c = "http://cgi.connect.qq.com/report/report_vm?"
      , d = [];
    Q.mix(Q, {
        monitor: b
    });
    var e = {};
    Q.setMonitorMap = function(a) {
        e = a
    }
    ,
    Q.monitorMap = function(a) {
        e[a] && Q.monitor(e[a])
    }
}()