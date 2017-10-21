gps = {
        error: 0,
        /* -1 鏈凡寮€鍚紶鎰熷櫒 -2 鏈巿鏉� -3 鏃犳硶鑾峰彇褰撳墠浣嶇疆 -4 瓒呮椂 -5 鏈煡閿欒 */
        mqqSetError: function(status) {
            console.log(status);
            if (status) {
                if (!status.enabled) {
                    gps.error = -1;
                } else if (!status.authroized) {
                    gps.error = -2;
                }
            }
        },
        h5SetError: function(error) {
            console.log(error);
            if (error.code) {
                switch (error.code) {
                case error.PERMISSION_DENIED:
                    gps.error = -2;
                    break;
                case error.POSITION_UNAVAILABLE:
                    gps.error = -3;
                    break;
                case error.TIMEOUT:
                    gps.error = -4;
                    break;
                case error.UNKNOWN_ERROR:
                    gps.error = -5;
                    break;
                }
            }
        },
        getError: function() {
            return gps.error;
        },
        getLocation: function(callback) {
            if (typeof callback != 'function') {
                callback = function(latitude, longitude) {
                    console.log([latitude, longitude]);
                }
            }
            var retCode = -1;
            mqq = window.mqq || '';
            if (mqq) {
                mqq.sensor.getLocation(function(retCode, latitude, longitude, status) {
                    if (retCode == 0) {
                        callback(latitude, longitude);
                    }
                });
            }
            if (!mqq || retCode != 0) {
                var gps = navigator.geolocation;
                if (!gps) {
                    console.log('not support gps');
                    gps.mqqSetError(status);
                    callback(undefined, undefined);
                    return false;
                }
                /* gps 鎰熷簲鍣�*/
                gps.getCurrentPosition(function(position) {
                    console.log(position);
                    if (position) {
                        callback(position.coords.latitude, position.coords.longitude);
                    } else {
                        console.log('position is null');
                        callback(undefined, undefined);
                    }
                },
                function(error) {
                    console.log('get gps info failed', error);
                    gps.h5SetError(error);
                    callback(undefined, undefined);
                },
                {
                    maximumAge: 10000
                });
            }
        }
    }