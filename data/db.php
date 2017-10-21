<?php
$schemas = 'a:68:{i:0;a:6:{s:9:"tablename";s:11:"ims_account";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"2";s:6:"fields";a:5:{s:4:"acid";a:6:{s:4:"name";s:4:"acid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:7:"uniacid";a:6:{s:4:"name";s:7:"uniacid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:4:"hash";a:6:{s:4:"name";s:4:"hash";s:4:"type";s:7:"varchar";s:6:"length";s:1:"8";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:4:"type";a:7:{s:4:"name";s:4:"type";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"3";s:4:"null";b:0;s:7:"default";s:1:"1";s:6:"signed";b:0;s:9:"increment";b:0;}s:9:"isconnect";a:7:{s:4:"name";s:9:"isconnect";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"4";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:1;s:9:"increment";b:0;}}s:7:"indexes";a:2:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:4:"acid";}}s:11:"idx_uniacid";a:3:{s:4:"name";s:11:"idx_uniacid";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:7:"uniacid";}}}}i:1;a:6:{s:9:"tablename";s:19:"ims_account_wechats";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";N;s:6:"fields";a:19:{s:4:"acid";a:6:{s:4:"name";s:4:"acid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:7:"uniacid";a:6:{s:4:"name";s:7:"uniacid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:5:"token";a:6:{s:4:"name";s:5:"token";s:4:"type";s:7:"varchar";s:6:"length";s:2:"32";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:14:"encodingaeskey";a:6:{s:4:"name";s:14:"encodingaeskey";s:4:"type";s:7:"varchar";s:6:"length";s:3:"255";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:12:"access_token";a:7:{s:4:"name";s:12:"access_token";s:4:"type";s:7:"varchar";s:6:"length";s:4:"1000";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:5:"level";a:7:{s:4:"name";s:5:"level";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"4";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:4:"name";a:6:{s:4:"name";s:4:"name";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"account";a:6:{s:4:"name";s:7:"account";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:8:"original";a:6:{s:4:"name";s:8:"original";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:9:"signature";a:6:{s:4:"name";s:9:"signature";s:4:"type";s:7:"varchar";s:6:"length";s:3:"100";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"country";a:6:{s:4:"name";s:7:"country";s:4:"type";s:7:"varchar";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:8:"province";a:6:{s:4:"name";s:8:"province";s:4:"type";s:7:"varchar";s:6:"length";s:1:"3";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:4:"city";a:6:{s:4:"name";s:4:"city";s:4:"type";s:7:"varchar";s:6:"length";s:2:"15";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:8:"username";a:6:{s:4:"name";s:8:"username";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:8:"password";a:6:{s:4:"name";s:8:"password";s:4:"type";s:7:"varchar";s:6:"length";s:2:"32";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:10:"lastupdate";a:7:{s:4:"name";s:10:"lastupdate";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:3:"key";a:6:{s:4:"name";s:3:"key";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"secret";a:6:{s:4:"name";s:6:"secret";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"styleid";a:7:{s:4:"name";s:7:"styleid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:1:"1";s:6:"signed";b:0;s:9:"increment";b:0;}}s:7:"indexes";a:2:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:4:"acid";}}s:7:"idx_key";a:3:{s:4:"name";s:7:"idx_key";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:3:"key";}}}}i:2;a:6:{s:9:"tablename";s:17:"ims_account_yixin";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"1";s:6:"fields";a:13:{s:4:"acid";a:6:{s:4:"name";s:4:"acid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:7:"uniacid";a:6:{s:4:"name";s:7:"uniacid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:5:"token";a:6:{s:4:"name";s:5:"token";s:4:"type";s:7:"varchar";s:6:"length";s:2:"32";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:12:"access_token";a:7:{s:4:"name";s:12:"access_token";s:4:"type";s:7:"varchar";s:6:"length";s:4:"1000";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:5:"level";a:7:{s:4:"name";s:5:"level";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"4";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:4:"name";a:6:{s:4:"name";s:4:"name";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"account";a:6:{s:4:"name";s:7:"account";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:9:"signature";a:6:{s:4:"name";s:9:"signature";s:4:"type";s:7:"varchar";s:6:"length";s:3:"100";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:8:"username";a:6:{s:4:"name";s:8:"username";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:8:"password";a:6:{s:4:"name";s:8:"password";s:4:"type";s:7:"varchar";s:6:"length";s:2:"32";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:3:"key";a:6:{s:4:"name";s:3:"key";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"secret";a:6:{s:4:"name";s:6:"secret";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"styleid";a:7:{s:4:"name";s:7:"styleid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:1:"1";s:6:"signed";b:0;s:9:"increment";b:0;}}s:7:"indexes";a:2:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:4:"acid";}}s:7:"idx_key";a:3:{s:4:"name";s:7:"idx_key";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:3:"key";}}}}i:3;a:6:{s:9:"tablename";s:19:"ims_activity_coupon";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"1";s:6:"fields";a:16:{s:8:"couponid";a:6:{s:4:"name";s:8:"couponid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:7:"uniacid";a:6:{s:4:"name";s:7:"uniacid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:4:"type";a:6:{s:4:"name";s:4:"type";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"4";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:5:"title";a:7:{s:4:"name";s:5:"title";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:8:"couponsn";a:6:{s:4:"name";s:8:"couponsn";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:11:"description";a:6:{s:4:"name";s:11:"description";s:4:"type";s:4:"text";s:6:"length";s:0:"";s:4:"null";b:1;s:6:"signed";b:1;s:9:"increment";b:0;}s:8:"discount";a:6:{s:4:"name";s:8:"discount";s:4:"type";s:7:"decimal";s:6:"length";s:4:"10,2";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:9:"condition";a:6:{s:4:"name";s:9:"condition";s:4:"type";s:7:"decimal";s:6:"length";s:4:"10,2";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:9:"starttime";a:6:{s:4:"name";s:9:"starttime";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:7:"endtime";a:6:{s:4:"name";s:7:"endtime";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:5:"limit";a:7:{s:4:"name";s:5:"limit";s:4:"type";s:3:"int";s:6:"length";s:2:"11";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"dosage";a:7:{s:4:"name";s:6:"dosage";s:4:"type";s:3:"int";s:6:"length";s:2:"11";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:6:"amount";a:6:{s:4:"name";s:6:"amount";s:4:"type";s:3:"int";s:6:"length";s:2:"11";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:5:"thumb";a:6:{s:4:"name";s:5:"thumb";s:4:"type";s:7:"varchar";s:6:"length";s:3:"500";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"credit";a:6:{s:4:"name";s:6:"credit";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:10:"credittype";a:6:{s:4:"name";s:10:"credittype";s:4:"type";s:7:"varchar";s:6:"length";s:2:"20";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}}s:7:"indexes";a:2:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:8:"couponid";}}s:7:"uniacid";a:3:{s:4:"name";s:7:"uniacid";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:7:"uniacid";}}}}i:4;a:6:{s:9:"tablename";s:30:"ims_activity_coupon_allocation";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"1";s:6:"fields";a:4:{s:2:"id";a:6:{s:4:"name";s:2:"id";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:7:"uniacid";a:6:{s:4:"name";s:7:"uniacid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:8:"couponid";a:6:{s:4:"name";s:8:"couponid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:7:"groupid";a:6:{s:4:"name";s:7:"groupid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}}s:7:"indexes";a:2:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:2:"id";}}s:7:"uniacid";a:3:{s:4:"name";s:7:"uniacid";s:4:"type";s:5:"index";s:6:"fields";a:3:{i:0;s:7:"uniacid";i:1;s:8:"couponid";i:2;s:7:"groupid";}}}}i:5;a:6:{s:9:"tablename";s:28:"ims_activity_coupon_password";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"1";s:6:"fields";a:4:{s:2:"id";a:6:{s:4:"name";s:2:"id";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:7:"uniacid";a:6:{s:4:"name";s:7:"uniacid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:4:"name";a:7:{s:4:"name";s:4:"name";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:8:"password";a:7:{s:4:"name";s:8:"password";s:4:"type";s:7:"varchar";s:6:"length";s:3:"200";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}}s:7:"indexes";a:1:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:2:"id";}}}}i:6;a:6:{s:9:"tablename";s:26:"ims_activity_coupon_record";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"1";s:6:"fields";a:10:{s:5:"recid";a:6:{s:4:"name";s:5:"recid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:7:"uniacid";a:6:{s:4:"name";s:7:"uniacid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:3:"uid";a:6:{s:4:"name";s:3:"uid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:11:"grantmodule";a:7:{s:4:"name";s:11:"grantmodule";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:9:"granttime";a:7:{s:4:"name";s:9:"granttime";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:9:"usemodule";a:7:{s:4:"name";s:9:"usemodule";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"usetime";a:7:{s:4:"name";s:7:"usetime";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:6:"status";a:7:{s:4:"name";s:6:"status";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"4";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"remark";a:7:{s:4:"name";s:6:"remark";s:4:"type";s:7:"varchar";s:6:"length";s:3:"300";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:8:"couponid";a:6:{s:4:"name";s:8:"couponid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}}s:7:"indexes";a:3:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:5:"recid";}}s:8:"couponid";a:3:{s:4:"name";s:8:"couponid";s:4:"type";s:5:"index";s:6:"fields";a:4:{i:0;s:3:"uid";i:1;s:11:"grantmodule";i:2;s:9:"usemodule";i:3;s:6:"status";}}s:7:"uniacid";a:3:{s:4:"name";s:7:"uniacid";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:7:"uniacid";}}}}i:7;a:6:{s:9:"tablename";s:21:"ims_activity_exchange";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"1";s:6:"fields";a:16:{s:2:"id";a:6:{s:4:"name";s:2:"id";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:8:"couponid";a:7:{s:4:"name";s:8:"couponid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"uniacid";a:6:{s:4:"name";s:7:"uniacid";s:4:"type";s:3:"int";s:6:"length";s:2:"11";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:5:"title";a:6:{s:4:"name";s:5:"title";s:4:"type";s:7:"varchar";s:6:"length";s:3:"100";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:11:"description";a:6:{s:4:"name";s:11:"description";s:4:"type";s:7:"varchar";s:6:"length";s:4:"1000";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:5:"thumb";a:6:{s:4:"name";s:5:"thumb";s:4:"type";s:7:"varchar";s:6:"length";s:3:"500";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:4:"type";a:6:{s:4:"name";s:4:"type";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"1";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:5:"extra";a:7:{s:4:"name";s:5:"extra";s:4:"type";s:7:"varchar";s:6:"length";s:4:"3000";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"credit";a:6:{s:4:"name";s:6:"credit";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:10:"credittype";a:6:{s:4:"name";s:10:"credittype";s:4:"type";s:7:"varchar";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:8:"pretotal";a:6:{s:4:"name";s:8:"pretotal";s:4:"type";s:3:"int";s:6:"length";s:2:"11";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:3:"num";a:6:{s:4:"name";s:3:"num";s:4:"type";s:3:"int";s:6:"length";s:2:"11";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:5:"total";a:7:{s:4:"name";s:5:"total";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:6:"status";a:7:{s:4:"name";s:6:"status";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"3";s:4:"null";b:0;s:7:"default";s:1:"1";s:6:"signed";b:0;s:9:"increment";b:0;}s:9:"starttime";a:6:{s:4:"name";s:9:"starttime";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:7:"endtime";a:6:{s:4:"name";s:7:"endtime";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}}s:7:"indexes";a:1:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:2:"id";}}}}i:8;a:6:{s:9:"tablename";s:28:"ims_activity_exchange_trades";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"1";s:6:"fields";a:6:{s:3:"tid";a:6:{s:4:"name";s:3:"tid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:7:"uniacid";a:6:{s:4:"name";s:7:"uniacid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:3:"uid";a:6:{s:4:"name";s:3:"uid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:4:"exid";a:6:{s:4:"name";s:4:"exid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:4:"type";a:6:{s:4:"name";s:4:"type";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:10:"createtime";a:7:{s:4:"name";s:10:"createtime";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}}s:7:"indexes";a:2:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:3:"tid";}}s:7:"uniacid";a:3:{s:4:"name";s:7:"uniacid";s:4:"type";s:5:"index";s:6:"fields";a:3:{i:0;s:7:"uniacid";i:1;s:3:"uid";i:2;s:4:"exid";}}}}i:9;a:6:{s:9:"tablename";s:37:"ims_activity_exchange_trades_shipping";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"1";s:6:"fields";a:13:{s:3:"tid";a:6:{s:4:"name";s:3:"tid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:7:"uniacid";a:6:{s:4:"name";s:7:"uniacid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:4:"exid";a:6:{s:4:"name";s:4:"exid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:3:"uid";a:6:{s:4:"name";s:3:"uid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:6:"status";a:7:{s:4:"name";s:6:"status";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"4";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:1;s:9:"increment";b:0;}s:10:"createtime";a:6:{s:4:"name";s:10:"createtime";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:8:"province";a:6:{s:4:"name";s:8:"province";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:4:"city";a:6:{s:4:"name";s:4:"city";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:8:"district";a:6:{s:4:"name";s:8:"district";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"address";a:6:{s:4:"name";s:7:"address";s:4:"type";s:7:"varchar";s:6:"length";s:3:"255";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"zipcode";a:6:{s:4:"name";s:7:"zipcode";s:4:"type";s:7:"varchar";s:6:"length";s:1:"6";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"mobile";a:6:{s:4:"name";s:6:"mobile";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:4:"name";a:6:{s:4:"name";s:4:"name";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}}s:7:"indexes";a:3:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:3:"tid";}}s:7:"uniacid";a:3:{s:4:"name";s:7:"uniacid";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:7:"uniacid";}}s:3:"uid";a:3:{s:4:"name";s:3:"uid";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:3:"uid";}}}}i:10;a:6:{s:9:"tablename";s:20:"ims_activity_modules";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"1";s:6:"fields";a:6:{s:3:"mid";a:6:{s:4:"name";s:3:"mid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:7:"uniacid";a:6:{s:4:"name";s:7:"uniacid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:4:"exid";a:6:{s:4:"name";s:4:"exid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:6:"module";a:6:{s:4:"name";s:6:"module";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:3:"uid";a:6:{s:4:"name";s:3:"uid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:9:"available";a:7:{s:4:"name";s:9:"available";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}}s:7:"indexes";a:4:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:3:"mid";}}s:7:"uniacid";a:3:{s:4:"name";s:7:"uniacid";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:7:"uniacid";}}s:6:"module";a:3:{s:4:"name";s:6:"module";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:6:"module";}}s:3:"uid";a:3:{s:4:"name";s:3:"uid";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:3:"uid";}}}}i:11;a:6:{s:9:"tablename";s:27:"ims_activity_modules_record";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"1";s:6:"fields";a:4:{s:2:"id";a:6:{s:4:"name";s:2:"id";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:3:"mid";a:6:{s:4:"name";s:3:"mid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:3:"num";a:7:{s:4:"name";s:3:"num";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"3";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:1;s:9:"increment";b:0;}s:10:"createtime";a:6:{s:4:"name";s:10:"createtime";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}}s:7:"indexes";a:2:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:2:"id";}}s:3:"mid";a:3:{s:4:"name";s:3:"mid";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:3:"mid";}}}}i:12;a:6:{s:9:"tablename";s:17:"ims_article_reply";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"1";s:6:"fields";a:4:{s:2:"id";a:6:{s:4:"name";s:2:"id";s:4:"type";s:3:"int";s:6:"length";s:2:"11";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:1;}s:3:"rid";a:6:{s:4:"name";s:3:"rid";s:4:"type";s:3:"int";s:6:"length";s:2:"11";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:9:"articleid";a:6:{s:4:"name";s:9:"articleid";s:4:"type";s:3:"int";s:6:"length";s:2:"11";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"isfill";a:7:{s:4:"name";s:6:"isfill";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"1";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:1;s:9:"increment";b:0;}}s:7:"indexes";a:1:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:2:"id";}}}}i:13;a:6:{s:9:"tablename";s:15:"ims_basic_reply";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"1";s:6:"fields";a:3:{s:2:"id";a:6:{s:4:"name";s:2:"id";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:3:"rid";a:7:{s:4:"name";s:3:"rid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:7:"content";a:6:{s:4:"name";s:7:"content";s:4:"type";s:7:"varchar";s:6:"length";s:4:"1000";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}}s:7:"indexes";a:1:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:2:"id";}}}}i:14;a:6:{s:9:"tablename";s:12:"ims_business";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"1";s:6:"fields";a:16:{s:2:"id";a:6:{s:4:"name";s:2:"id";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:4:"weid";a:6:{s:4:"name";s:4:"weid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:5:"title";a:7:{s:4:"name";s:5:"title";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:5:"thumb";a:7:{s:4:"name";s:5:"thumb";s:4:"type";s:7:"varchar";s:6:"length";s:3:"100";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"content";a:7:{s:4:"name";s:7:"content";s:4:"type";s:7:"varchar";s:6:"length";s:4:"1000";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:5:"phone";a:7:{s:4:"name";s:5:"phone";s:4:"type";s:7:"varchar";s:6:"length";s:2:"15";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:2:"qq";a:7:{s:4:"name";s:2:"qq";s:4:"type";s:7:"varchar";s:6:"length";s:2:"15";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:8:"province";a:7:{s:4:"name";s:8:"province";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:4:"city";a:7:{s:4:"name";s:4:"city";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:4:"dist";a:7:{s:4:"name";s:4:"dist";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"address";a:7:{s:4:"name";s:7:"address";s:4:"type";s:7:"varchar";s:6:"length";s:3:"500";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:3:"lng";a:7:{s:4:"name";s:3:"lng";s:4:"type";s:7:"varchar";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:3:"lat";a:7:{s:4:"name";s:3:"lat";s:4:"type";s:7:"varchar";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:9:"industry1";a:7:{s:4:"name";s:9:"industry1";s:4:"type";s:7:"varchar";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:9:"industry2";a:7:{s:4:"name";s:9:"industry2";s:4:"type";s:7:"varchar";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:10:"createtime";a:7:{s:4:"name";s:10:"createtime";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:1;s:9:"increment";b:0;}}s:7:"indexes";a:2:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:2:"id";}}s:11:"idx_lat_lng";a:3:{s:4:"name";s:11:"idx_lat_lng";s:4:"type";s:5:"index";s:6:"fields";a:2:{i:0;s:3:"lng";i:1;s:3:"lat";}}}}i:15;a:6:{s:9:"tablename";s:19:"ims_core_attachment";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"1";s:6:"fields";a:7:{s:2:"id";a:6:{s:4:"name";s:2:"id";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:7:"uniacid";a:6:{s:4:"name";s:7:"uniacid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:3:"uid";a:6:{s:4:"name";s:3:"uid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:8:"filename";a:6:{s:4:"name";s:8:"filename";s:4:"type";s:7:"varchar";s:6:"length";s:3:"255";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:10:"attachment";a:6:{s:4:"name";s:10:"attachment";s:4:"type";s:7:"varchar";s:6:"length";s:3:"255";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:4:"type";a:6:{s:4:"name";s:4:"type";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"3";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:10:"createtime";a:6:{s:4:"name";s:10:"createtime";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}}s:7:"indexes";a:1:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:2:"id";}}}}i:16;a:6:{s:9:"tablename";s:14:"ims_core_cache";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";N;s:6:"fields";a:2:{s:3:"key";a:6:{s:4:"name";s:3:"key";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:5:"value";a:6:{s:4:"name";s:5:"value";s:4:"type";s:10:"mediumtext";s:6:"length";s:0:"";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}}s:7:"indexes";a:1:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:3:"key";}}}}i:17;a:6:{s:9:"tablename";s:15:"ims_core_paylog";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"1";s:6:"fields";a:9:{s:4:"plid";a:6:{s:4:"name";s:4:"plid";s:4:"type";s:3:"int";s:6:"length";s:2:"11";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:1;}s:4:"type";a:7:{s:4:"name";s:4:"type";s:4:"type";s:7:"varchar";s:6:"length";s:2:"20";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"uniacid";a:6:{s:4:"name";s:7:"uniacid";s:4:"type";s:3:"int";s:6:"length";s:2:"11";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"openid";a:7:{s:4:"name";s:6:"openid";s:4:"type";s:7:"varchar";s:6:"length";s:2:"40";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:3:"tid";a:6:{s:4:"name";s:3:"tid";s:4:"type";s:7:"varchar";s:6:"length";s:2:"64";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:3:"fee";a:6:{s:4:"name";s:3:"fee";s:4:"type";s:7:"decimal";s:6:"length";s:4:"10,2";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"status";a:7:{s:4:"name";s:6:"status";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"4";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"module";a:7:{s:4:"name";s:6:"module";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:3:"tag";a:7:{s:4:"name";s:3:"tag";s:4:"type";s:7:"varchar";s:6:"length";s:4:"2000";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}}s:7:"indexes";a:4:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:4:"plid";}}s:10:"idx_openid";a:3:{s:4:"name";s:10:"idx_openid";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:6:"openid";}}s:7:"idx_tid";a:3:{s:4:"name";s:7:"idx_tid";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:3:"tid";}}s:11:"idx_uniacid";a:3:{s:4:"name";s:11:"idx_uniacid";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:7:"uniacid";}}}}i:18;a:6:{s:9:"tablename";s:20:"ims_core_performance";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"1";s:6:"fields";a:6:{s:2:"id";a:6:{s:4:"name";s:2:"id";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:4:"type";a:6:{s:4:"name";s:4:"type";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"1";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"runtime";a:6:{s:4:"name";s:7:"runtime";s:4:"type";s:7:"varchar";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"runurl";a:6:{s:4:"name";s:6:"runurl";s:4:"type";s:7:"varchar";s:6:"length";s:3:"512";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"runsql";a:6:{s:4:"name";s:6:"runsql";s:4:"type";s:7:"varchar";s:6:"length";s:3:"512";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:10:"createtime";a:6:{s:4:"name";s:10:"createtime";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}}s:7:"indexes";a:1:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:2:"id";}}}}i:19;a:6:{s:9:"tablename";s:14:"ims_core_queue";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"1";s:6:"fields";a:9:{s:3:"qid";a:6:{s:4:"name";s:3:"qid";s:4:"type";s:6:"bigint";s:6:"length";s:2:"20";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:7:"uniacid";a:6:{s:4:"name";s:7:"uniacid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:4:"acid";a:6:{s:4:"name";s:4:"acid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:7:"message";a:7:{s:4:"name";s:7:"message";s:4:"type";s:7:"varchar";s:6:"length";s:4:"2000";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"params";a:7:{s:4:"name";s:6:"params";s:4:"type";s:7:"varchar";s:6:"length";s:4:"1000";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"keyword";a:7:{s:4:"name";s:7:"keyword";s:4:"type";s:7:"varchar";s:6:"length";s:4:"1000";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:8:"response";a:7:{s:4:"name";s:8:"response";s:4:"type";s:7:"varchar";s:6:"length";s:4:"2000";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"module";a:7:{s:4:"name";s:6:"module";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:8:"dateline";a:6:{s:4:"name";s:8:"dateline";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}}s:7:"indexes";a:2:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:3:"qid";}}s:7:"uniacid";a:3:{s:4:"name";s:7:"uniacid";s:4:"type";s:5:"index";s:6:"fields";a:2:{i:0;s:7:"uniacid";i:1;s:4:"acid";}}}}i:20;a:6:{s:9:"tablename";s:17:"ims_core_resource";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"1";s:6:"fields";a:6:{s:3:"mid";a:6:{s:4:"name";s:3:"mid";s:4:"type";s:3:"int";s:6:"length";s:2:"11";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:1;}s:7:"uniacid";a:6:{s:4:"name";s:7:"uniacid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:8:"media_id";a:6:{s:4:"name";s:8:"media_id";s:4:"type";s:7:"varchar";s:6:"length";s:3:"100";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:5:"trunk";a:7:{s:4:"name";s:5:"trunk";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:4:"type";a:6:{s:4:"name";s:4:"type";s:4:"type";s:7:"varchar";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:8:"dateline";a:6:{s:4:"name";s:8:"dateline";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}}s:7:"indexes";a:3:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:3:"mid";}}s:4:"acid";a:3:{s:4:"name";s:4:"acid";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:7:"uniacid";}}s:4:"type";a:3:{s:4:"name";s:4:"type";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:4:"type";}}}}i:21;a:6:{s:9:"tablename";s:17:"ims_core_sessions";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";N;s:6:"fields";a:5:{s:3:"sid";a:7:{s:4:"name";s:3:"sid";s:4:"type";s:4:"char";s:6:"length";s:2:"32";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"uniacid";a:6:{s:4:"name";s:7:"uniacid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:6:"openid";a:6:{s:4:"name";s:6:"openid";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:4:"data";a:6:{s:4:"name";s:4:"data";s:4:"type";s:7:"varchar";s:6:"length";s:3:"500";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:10:"expiretime";a:7:{s:4:"name";s:10:"expiretime";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}}s:7:"indexes";a:1:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:3:"sid";}}}}i:22;a:6:{s:9:"tablename";s:17:"ims_core_settings";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";N;s:6:"fields";a:2:{s:3:"key";a:6:{s:4:"name";s:3:"key";s:4:"type";s:7:"varchar";s:6:"length";s:3:"200";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:5:"value";a:6:{s:4:"name";s:5:"value";s:4:"type";s:4:"text";s:6:"length";s:0:"";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}}s:7:"indexes";a:1:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:3:"key";}}}}i:23;a:6:{s:9:"tablename";s:15:"ims_cover_reply";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"3";s:6:"fields";a:10:{s:2:"id";a:6:{s:4:"name";s:2:"id";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:7:"uniacid";a:6:{s:4:"name";s:7:"uniacid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:7:"multiid";a:7:{s:4:"name";s:7:"multiid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:3:"rid";a:6:{s:4:"name";s:3:"rid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:6:"module";a:7:{s:4:"name";s:6:"module";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:2:"do";a:7:{s:4:"name";s:2:"do";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:5:"title";a:7:{s:4:"name";s:5:"title";s:4:"type";s:7:"varchar";s:6:"length";s:3:"255";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:11:"description";a:7:{s:4:"name";s:11:"description";s:4:"type";s:7:"varchar";s:6:"length";s:3:"255";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:5:"thumb";a:7:{s:4:"name";s:5:"thumb";s:4:"type";s:7:"varchar";s:6:"length";s:3:"255";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:3:"url";a:7:{s:4:"name";s:3:"url";s:4:"type";s:7:"varchar";s:6:"length";s:3:"255";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}}s:7:"indexes";a:1:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:2:"id";}}}}i:24;a:6:{s:9:"tablename";s:11:"ims_mc_card";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"2";s:6:"fields";a:11:{s:2:"id";a:6:{s:4:"name";s:2:"id";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:7:"uniacid";a:6:{s:4:"name";s:7:"uniacid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:5:"title";a:7:{s:4:"name";s:5:"title";s:4:"type";s:7:"varchar";s:6:"length";s:3:"100";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:5:"color";a:7:{s:4:"name";s:5:"color";s:4:"type";s:7:"varchar";s:6:"length";s:3:"255";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:10:"background";a:7:{s:4:"name";s:10:"background";s:4:"type";s:7:"varchar";s:6:"length";s:3:"255";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:4:"logo";a:7:{s:4:"name";s:4:"logo";s:4:"type";s:7:"varchar";s:6:"length";s:3:"255";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"format";a:7:{s:4:"name";s:6:"format";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"fields";a:7:{s:4:"name";s:6:"fields";s:4:"type";s:7:"varchar";s:6:"length";s:4:"1000";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:5:"snpos";a:7:{s:4:"name";s:5:"snpos";s:4:"type";s:3:"int";s:6:"length";s:2:"11";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"status";a:7:{s:4:"name";s:6:"status";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"1";s:4:"null";b:0;s:7:"default";s:1:"1";s:6:"signed";b:1;s:9:"increment";b:0;}s:8:"business";a:6:{s:4:"name";s:8:"business";s:4:"type";s:4:"text";s:6:"length";s:0:"";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}}s:7:"indexes";a:2:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:2:"id";}}s:7:"uniacid";a:3:{s:4:"name";s:7:"uniacid";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:7:"uniacid";}}}}i:25;a:6:{s:9:"tablename";s:19:"ims_mc_card_members";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"1";s:6:"fields";a:7:{s:2:"id";a:6:{s:4:"name";s:2:"id";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:7:"uniacid";a:6:{s:4:"name";s:7:"uniacid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:3:"uid";a:6:{s:4:"name";s:3:"uid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:1;s:6:"signed";b:1;s:9:"increment";b:0;}s:3:"cid";a:7:{s:4:"name";s:3:"cid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"cardsn";a:7:{s:4:"name";s:6:"cardsn";s:4:"type";s:7:"varchar";s:6:"length";s:2:"20";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"status";a:6:{s:4:"name";s:6:"status";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"1";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:10:"createtime";a:6:{s:4:"name";s:10:"createtime";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}}s:7:"indexes";a:1:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:2:"id";}}}}i:26;a:6:{s:9:"tablename";s:23:"ims_mc_credits_recharge";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"1";s:6:"fields";a:8:{s:2:"id";a:6:{s:4:"name";s:2:"id";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:7:"uniacid";a:6:{s:4:"name";s:7:"uniacid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:3:"uid";a:6:{s:4:"name";s:3:"uid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:3:"tid";a:6:{s:4:"name";s:3:"tid";s:4:"type";s:7:"varchar";s:6:"length";s:2:"20";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"transid";a:6:{s:4:"name";s:7:"transid";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:3:"fee";a:6:{s:4:"name";s:3:"fee";s:4:"type";s:7:"varchar";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"status";a:7:{s:4:"name";s:6:"status";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"1";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:1;s:9:"increment";b:0;}s:10:"createtime";a:6:{s:4:"name";s:10:"createtime";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}}s:7:"indexes";a:3:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:2:"id";}}s:15:"idx_uniacid_uid";a:3:{s:4:"name";s:15:"idx_uniacid_uid";s:4:"type";s:5:"index";s:6:"fields";a:2:{i:0;s:7:"uniacid";i:1;s:3:"uid";}}s:7:"idx_tid";a:3:{s:4:"name";s:7:"idx_tid";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:3:"tid";}}}}i:27;a:6:{s:9:"tablename";s:21:"ims_mc_credits_record";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"1";s:6:"fields";a:8:{s:2:"id";a:6:{s:4:"name";s:2:"id";s:4:"type";s:3:"int";s:6:"length";s:2:"11";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:1;}s:3:"uid";a:6:{s:4:"name";s:3:"uid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:7:"uniacid";a:6:{s:4:"name";s:7:"uniacid";s:4:"type";s:3:"int";s:6:"length";s:2:"11";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:10:"credittype";a:7:{s:4:"name";s:10:"credittype";s:4:"type";s:7:"varchar";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:3:"num";a:7:{s:4:"name";s:3:"num";s:4:"type";s:3:"int";s:6:"length";s:2:"11";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:1;s:9:"increment";b:0;}s:8:"operator";a:6:{s:4:"name";s:8:"operator";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:10:"createtime";a:6:{s:4:"name";s:10:"createtime";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:6:"remark";a:7:{s:4:"name";s:6:"remark";s:4:"type";s:7:"varchar";s:6:"length";s:3:"200";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}}s:7:"indexes";a:3:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:2:"id";}}s:7:"uniacid";a:3:{s:4:"name";s:7:"uniacid";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:7:"uniacid";}}s:3:"uid";a:3:{s:4:"name";s:3:"uid";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:3:"uid";}}}}i:28;a:6:{s:9:"tablename";s:13:"ims_mc_groups";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"2";s:6:"fields";a:5:{s:7:"groupid";a:6:{s:4:"name";s:7:"groupid";s:4:"type";s:3:"int";s:6:"length";s:2:"11";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:1;}s:7:"uniacid";a:7:{s:4:"name";s:7:"uniacid";s:4:"type";s:3:"int";s:6:"length";s:2:"11";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:1;s:9:"increment";b:0;}s:5:"title";a:7:{s:4:"name";s:5:"title";s:4:"type";s:7:"varchar";s:6:"length";s:2:"20";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:9:"orderlist";a:7:{s:4:"name";s:9:"orderlist";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"4";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:9:"isdefault";a:7:{s:4:"name";s:9:"isdefault";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"4";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:1;s:9:"increment";b:0;}}s:7:"indexes";a:2:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:7:"groupid";}}s:7:"uniacid";a:3:{s:4:"name";s:7:"uniacid";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:7:"uniacid";}}}}i:29;a:6:{s:9:"tablename";s:14:"ims_mc_handsel";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"1";s:6:"fields";a:9:{s:2:"id";a:6:{s:4:"name";s:2:"id";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:7:"uniacid";a:6:{s:4:"name";s:7:"uniacid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:5:"touid";a:6:{s:4:"name";s:5:"touid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:7:"fromuid";a:6:{s:4:"name";s:7:"fromuid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"module";a:6:{s:4:"name";s:6:"module";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:4:"sign";a:6:{s:4:"name";s:4:"sign";s:4:"type";s:7:"varchar";s:6:"length";s:3:"100";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"action";a:6:{s:4:"name";s:6:"action";s:4:"type";s:7:"varchar";s:6:"length";s:2:"20";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:12:"credit_value";a:6:{s:4:"name";s:12:"credit_value";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:10:"createtime";a:6:{s:4:"name";s:10:"createtime";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}}s:7:"indexes";a:3:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:2:"id";}}s:3:"uid";a:3:{s:4:"name";s:3:"uid";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:5:"touid";}}s:7:"uniacid";a:3:{s:4:"name";s:7:"uniacid";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:7:"uniacid";}}}}i:30;a:6:{s:9:"tablename";s:19:"ims_mc_mapping_fans";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"1";s:6:"fields";a:10:{s:5:"fanid";a:6:{s:4:"name";s:5:"fanid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:4:"acid";a:6:{s:4:"name";s:4:"acid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:7:"uniacid";a:7:{s:4:"name";s:7:"uniacid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:3:"uid";a:7:{s:4:"name";s:3:"uid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:6:"openid";a:6:{s:4:"name";s:6:"openid";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:4:"salt";a:7:{s:4:"name";s:4:"salt";s:4:"type";s:4:"char";s:6:"length";s:1:"8";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"follow";a:7:{s:4:"name";s:6:"follow";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"1";s:4:"null";b:0;s:7:"default";s:1:"1";s:6:"signed";b:0;s:9:"increment";b:0;}s:10:"followtime";a:6:{s:4:"name";s:10:"followtime";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:12:"unfollowtime";a:6:{s:4:"name";s:12:"unfollowtime";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:3:"tag";a:7:{s:4:"name";s:3:"tag";s:4:"type";s:7:"varchar";s:6:"length";s:3:"500";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}}s:7:"indexes";a:4:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:5:"fanid";}}s:4:"acid";a:3:{s:4:"name";s:4:"acid";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:4:"acid";}}s:7:"uniacid";a:3:{s:4:"name";s:7:"uniacid";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:7:"uniacid";}}s:6:"openid";a:3:{s:4:"name";s:6:"openid";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:6:"openid";}}}}i:31;a:6:{s:9:"tablename";s:22:"ims_mc_mapping_ucenter";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"1";s:6:"fields";a:4:{s:2:"id";a:6:{s:4:"name";s:2:"id";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:7:"uniacid";a:6:{s:4:"name";s:7:"uniacid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:3:"uid";a:6:{s:4:"name";s:3:"uid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:9:"centeruid";a:6:{s:4:"name";s:9:"centeruid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}}s:7:"indexes";a:1:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:2:"id";}}}}i:32;a:6:{s:9:"tablename";s:14:"ims_mc_members";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"1";s:6:"fields";a:51:{s:3:"uid";a:6:{s:4:"name";s:3:"uid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:7:"uniacid";a:6:{s:4:"name";s:7:"uniacid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:6:"mobile";a:7:{s:4:"name";s:6:"mobile";s:4:"type";s:7:"varchar";s:6:"length";s:2:"11";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:5:"email";a:7:{s:4:"name";s:5:"email";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:8:"password";a:7:{s:4:"name";s:8:"password";s:4:"type";s:7:"varchar";s:6:"length";s:2:"32";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:4:"salt";a:7:{s:4:"name";s:4:"salt";s:4:"type";s:7:"varchar";s:6:"length";s:1:"8";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"groupid";a:7:{s:4:"name";s:7:"groupid";s:4:"type";s:3:"int";s:6:"length";s:2:"11";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"credit1";a:7:{s:4:"name";s:7:"credit1";s:4:"type";s:7:"decimal";s:6:"length";s:4:"10,2";s:4:"null";b:0;s:7:"default";s:4:"0.00";s:6:"signed";b:0;s:9:"increment";b:0;}s:7:"credit2";a:7:{s:4:"name";s:7:"credit2";s:4:"type";s:7:"decimal";s:6:"length";s:4:"10,2";s:4:"null";b:0;s:7:"default";s:4:"0.00";s:6:"signed";b:0;s:9:"increment";b:0;}s:7:"credit3";a:7:{s:4:"name";s:7:"credit3";s:4:"type";s:7:"decimal";s:6:"length";s:4:"10,2";s:4:"null";b:0;s:7:"default";s:4:"0.00";s:6:"signed";b:0;s:9:"increment";b:0;}s:7:"credit4";a:7:{s:4:"name";s:7:"credit4";s:4:"type";s:7:"decimal";s:6:"length";s:4:"10,2";s:4:"null";b:0;s:7:"default";s:4:"0.00";s:6:"signed";b:0;s:9:"increment";b:0;}s:7:"credit5";a:7:{s:4:"name";s:7:"credit5";s:4:"type";s:7:"decimal";s:6:"length";s:4:"10,2";s:4:"null";b:0;s:7:"default";s:4:"0.00";s:6:"signed";b:0;s:9:"increment";b:0;}s:10:"createtime";a:6:{s:4:"name";s:10:"createtime";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:8:"realname";a:7:{s:4:"name";s:8:"realname";s:4:"type";s:7:"varchar";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:8:"nickname";a:7:{s:4:"name";s:8:"nickname";s:4:"type";s:7:"varchar";s:6:"length";s:2:"20";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"avatar";a:7:{s:4:"name";s:6:"avatar";s:4:"type";s:7:"varchar";s:6:"length";s:3:"255";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:2:"qq";a:7:{s:4:"name";s:2:"qq";s:4:"type";s:7:"varchar";s:6:"length";s:2:"15";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:3:"vip";a:7:{s:4:"name";s:3:"vip";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"3";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:6:"gender";a:7:{s:4:"name";s:6:"gender";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"1";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:1;s:9:"increment";b:0;}s:9:"birthyear";a:7:{s:4:"name";s:9:"birthyear";s:4:"type";s:8:"smallint";s:6:"length";s:1:"6";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:10:"birthmonth";a:7:{s:4:"name";s:10:"birthmonth";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"3";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:8:"birthday";a:7:{s:4:"name";s:8:"birthday";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"3";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:13:"constellation";a:7:{s:4:"name";s:13:"constellation";s:4:"type";s:7:"varchar";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"zodiac";a:7:{s:4:"name";s:6:"zodiac";s:4:"type";s:7:"varchar";s:6:"length";s:1:"5";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:9:"telephone";a:7:{s:4:"name";s:9:"telephone";s:4:"type";s:7:"varchar";s:6:"length";s:2:"15";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"idcard";a:7:{s:4:"name";s:6:"idcard";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:9:"studentid";a:7:{s:4:"name";s:9:"studentid";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:5:"grade";a:7:{s:4:"name";s:5:"grade";s:4:"type";s:7:"varchar";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"address";a:7:{s:4:"name";s:7:"address";s:4:"type";s:7:"varchar";s:6:"length";s:3:"255";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"zipcode";a:7:{s:4:"name";s:7:"zipcode";s:4:"type";s:7:"varchar";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:11:"nationality";a:7:{s:4:"name";s:11:"nationality";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:14:"resideprovince";a:7:{s:4:"name";s:14:"resideprovince";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:10:"residecity";a:7:{s:4:"name";s:10:"residecity";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:10:"residedist";a:7:{s:4:"name";s:10:"residedist";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:14:"graduateschool";a:7:{s:4:"name";s:14:"graduateschool";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"company";a:7:{s:4:"name";s:7:"company";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:9:"education";a:7:{s:4:"name";s:9:"education";s:4:"type";s:7:"varchar";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:10:"occupation";a:7:{s:4:"name";s:10:"occupation";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:8:"position";a:7:{s:4:"name";s:8:"position";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"revenue";a:7:{s:4:"name";s:7:"revenue";s:4:"type";s:7:"varchar";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:15:"affectivestatus";a:7:{s:4:"name";s:15:"affectivestatus";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:10:"lookingfor";a:7:{s:4:"name";s:10:"lookingfor";s:4:"type";s:7:"varchar";s:6:"length";s:3:"255";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:9:"bloodtype";a:7:{s:4:"name";s:9:"bloodtype";s:4:"type";s:7:"varchar";s:6:"length";s:1:"5";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"height";a:7:{s:4:"name";s:6:"height";s:4:"type";s:7:"varchar";s:6:"length";s:1:"5";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"weight";a:7:{s:4:"name";s:6:"weight";s:4:"type";s:7:"varchar";s:6:"length";s:1:"5";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"alipay";a:7:{s:4:"name";s:6:"alipay";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:3:"msn";a:7:{s:4:"name";s:3:"msn";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"taobao";a:7:{s:4:"name";s:6:"taobao";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:4:"site";a:7:{s:4:"name";s:4:"site";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:3:"bio";a:6:{s:4:"name";s:3:"bio";s:4:"type";s:4:"text";s:6:"length";s:0:"";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:8:"interest";a:6:{s:4:"name";s:8:"interest";s:4:"type";s:4:"text";s:6:"length";s:0:"";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}}s:7:"indexes";a:3:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:3:"uid";}}s:7:"groupid";a:3:{s:4:"name";s:7:"groupid";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:7:"groupid";}}s:7:"uniacid";a:3:{s:4:"name";s:7:"uniacid";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:7:"uniacid";}}}}i:33;a:6:{s:9:"tablename";s:14:"ims_menu_event";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"1";s:6:"fields";a:5:{s:2:"id";a:6:{s:4:"name";s:2:"id";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:7:"uniacid";a:6:{s:4:"name";s:7:"uniacid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:7:"keyword";a:6:{s:4:"name";s:7:"keyword";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:4:"type";a:6:{s:4:"name";s:4:"type";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"picmd5";a:6:{s:4:"name";s:6:"picmd5";s:4:"type";s:7:"varchar";s:6:"length";s:2:"32";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}}s:7:"indexes";a:3:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:2:"id";}}s:7:"uniacid";a:3:{s:4:"name";s:7:"uniacid";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:7:"uniacid";}}s:6:"picmd5";a:3:{s:4:"name";s:6:"picmd5";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:6:"picmd5";}}}}i:34;a:6:{s:9:"tablename";s:16:"ims_mobilenumber";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"1";s:6:"fields";a:4:{s:2:"id";a:6:{s:4:"name";s:2:"id";s:4:"type";s:3:"int";s:6:"length";s:2:"11";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:1;}s:3:"rid";a:6:{s:4:"name";s:3:"rid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"enabled";a:7:{s:4:"name";s:7:"enabled";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"1";s:4:"null";b:0;s:7:"default";s:1:"1";s:6:"signed";b:0;s:9:"increment";b:0;}s:8:"dateline";a:7:{s:4:"name";s:8:"dateline";s:4:"type";s:3:"int";s:6:"length";s:2:"11";s:4:"null";b:1;s:7:"default";s:1:"0";s:6:"signed";b:1;s:9:"increment";b:0;}}s:7:"indexes";a:1:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:2:"id";}}}}i:35;a:6:{s:9:"tablename";s:11:"ims_modules";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"6";s:6:"fields";a:16:{s:3:"mid";a:6:{s:4:"name";s:3:"mid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:4:"name";a:6:{s:4:"name";s:4:"name";s:4:"type";s:7:"varchar";s:6:"length";s:3:"100";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:4:"type";a:7:{s:4:"name";s:4:"type";s:4:"type";s:7:"varchar";s:6:"length";s:2:"20";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:5:"title";a:6:{s:4:"name";s:5:"title";s:4:"type";s:7:"varchar";s:6:"length";s:3:"100";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"version";a:7:{s:4:"name";s:7:"version";s:4:"type";s:7:"varchar";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"ability";a:6:{s:4:"name";s:7:"ability";s:4:"type";s:7:"varchar";s:6:"length";s:3:"500";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:11:"description";a:6:{s:4:"name";s:11:"description";s:4:"type";s:7:"varchar";s:6:"length";s:4:"1000";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"author";a:6:{s:4:"name";s:6:"author";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:3:"url";a:6:{s:4:"name";s:3:"url";s:4:"type";s:7:"varchar";s:6:"length";s:3:"255";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:8:"settings";a:7:{s:4:"name";s:8:"settings";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"1";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:1;s:9:"increment";b:0;}s:10:"subscribes";a:7:{s:4:"name";s:10:"subscribes";s:4:"type";s:7:"varchar";s:6:"length";s:3:"500";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"handles";a:7:{s:4:"name";s:7:"handles";s:4:"type";s:7:"varchar";s:6:"length";s:3:"500";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:12:"isrulefields";a:7:{s:4:"name";s:12:"isrulefields";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"1";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:1;s:9:"increment";b:0;}s:8:"issystem";a:7:{s:4:"name";s:8:"issystem";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"1";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:10:"issolution";a:7:{s:4:"name";s:10:"issolution";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"1";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:6:"target";a:7:{s:4:"name";s:6:"target";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}}s:7:"indexes";a:2:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:3:"mid";}}s:8:"idx_name";a:3:{s:4:"name";s:8:"idx_name";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:4:"name";}}}}i:36;a:6:{s:9:"tablename";s:20:"ims_modules_bindings";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"1";s:6:"fields";a:8:{s:3:"eid";a:6:{s:4:"name";s:3:"eid";s:4:"type";s:3:"int";s:6:"length";s:2:"11";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:1;}s:6:"module";a:7:{s:4:"name";s:6:"module";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:5:"entry";a:7:{s:4:"name";s:5:"entry";s:4:"type";s:7:"varchar";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:4:"call";a:7:{s:4:"name";s:4:"call";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:5:"title";a:6:{s:4:"name";s:5:"title";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:2:"do";a:6:{s:4:"name";s:2:"do";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:5:"state";a:6:{s:4:"name";s:5:"state";s:4:"type";s:7:"varchar";s:6:"length";s:3:"200";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"direct";a:7:{s:4:"name";s:6:"direct";s:4:"type";s:3:"int";s:6:"length";s:2:"11";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:1;s:9:"increment";b:0;}}s:7:"indexes";a:2:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:3:"eid";}}s:10:"idx_module";a:3:{s:4:"name";s:10:"idx_module";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:6:"module";}}}}i:37;a:6:{s:9:"tablename";s:15:"ims_music_reply";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"1";s:6:"fields";a:6:{s:2:"id";a:6:{s:4:"name";s:2:"id";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:3:"rid";a:6:{s:4:"name";s:3:"rid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:5:"title";a:7:{s:4:"name";s:5:"title";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:11:"description";a:7:{s:4:"name";s:11:"description";s:4:"type";s:7:"varchar";s:6:"length";s:3:"255";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:3:"url";a:7:{s:4:"name";s:3:"url";s:4:"type";s:7:"varchar";s:6:"length";s:3:"300";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:5:"hqurl";a:7:{s:4:"name";s:5:"hqurl";s:4:"type";s:7:"varchar";s:6:"length";s:3:"300";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}}s:7:"indexes";a:1:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:2:"id";}}}}i:38;a:6:{s:9:"tablename";s:14:"ims_news_reply";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"1";s:6:"fields";a:10:{s:2:"id";a:6:{s:4:"name";s:2:"id";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:3:"rid";a:6:{s:4:"name";s:3:"rid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:8:"parentid";a:7:{s:4:"name";s:8:"parentid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:5:"title";a:6:{s:4:"name";s:5:"title";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:11:"description";a:6:{s:4:"name";s:11:"description";s:4:"type";s:7:"varchar";s:6:"length";s:3:"255";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:5:"thumb";a:6:{s:4:"name";s:5:"thumb";s:4:"type";s:7:"varchar";s:6:"length";s:2:"60";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"content";a:6:{s:4:"name";s:7:"content";s:4:"type";s:4:"text";s:6:"length";s:0:"";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:3:"url";a:6:{s:4:"name";s:3:"url";s:4:"type";s:7:"varchar";s:6:"length";s:3:"255";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:12:"displayorder";a:6:{s:4:"name";s:12:"displayorder";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:9:"incontent";a:7:{s:4:"name";s:9:"incontent";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"1";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:1;s:9:"increment";b:0;}}s:7:"indexes";a:1:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:2:"id";}}}}i:39;a:6:{s:9:"tablename";s:18:"ims_profile_fields";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:2:"37";s:6:"fields";a:9:{s:2:"id";a:6:{s:4:"name";s:2:"id";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:5:"field";a:6:{s:4:"name";s:5:"field";s:4:"type";s:7:"varchar";s:6:"length";s:3:"255";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:9:"available";a:7:{s:4:"name";s:9:"available";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"1";s:4:"null";b:0;s:7:"default";s:1:"1";s:6:"signed";b:1;s:9:"increment";b:0;}s:5:"title";a:6:{s:4:"name";s:5:"title";s:4:"type";s:7:"varchar";s:6:"length";s:3:"255";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:11:"description";a:6:{s:4:"name";s:11:"description";s:4:"type";s:7:"varchar";s:6:"length";s:3:"255";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:12:"displayorder";a:7:{s:4:"name";s:12:"displayorder";s:4:"type";s:8:"smallint";s:6:"length";s:1:"6";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:1;s:9:"increment";b:0;}s:8:"required";a:7:{s:4:"name";s:8:"required";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"1";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:1;s:9:"increment";b:0;}s:12:"unchangeable";a:7:{s:4:"name";s:12:"unchangeable";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"1";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:1;s:9:"increment";b:0;}s:14:"showinregister";a:7:{s:4:"name";s:14:"showinregister";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"1";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:1;s:9:"increment";b:0;}}s:7:"indexes";a:1:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:2:"id";}}}}i:40;a:6:{s:9:"tablename";s:10:"ims_qrcode";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"1";s:6:"fields";a:12:{s:2:"id";a:6:{s:4:"name";s:2:"id";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:7:"uniacid";a:6:{s:4:"name";s:7:"uniacid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:4:"acid";a:7:{s:4:"name";s:4:"acid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:5:"qrcid";a:7:{s:4:"name";s:5:"qrcid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:4:"name";a:7:{s:4:"name";s:4:"name";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"keyword";a:6:{s:4:"name";s:7:"keyword";s:4:"type";s:7:"varchar";s:6:"length";s:3:"100";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:5:"model";a:7:{s:4:"name";s:5:"model";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"1";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:6:"ticket";a:7:{s:4:"name";s:6:"ticket";s:4:"type";s:7:"varchar";s:6:"length";s:3:"250";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"expire";a:7:{s:4:"name";s:6:"expire";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:6:"subnum";a:7:{s:4:"name";s:6:"subnum";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:10:"createtime";a:7:{s:4:"name";s:10:"createtime";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:6:"status";a:7:{s:4:"name";s:6:"status";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"1";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}}s:7:"indexes";a:3:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:2:"id";}}s:9:"idx_qrcid";a:3:{s:4:"name";s:9:"idx_qrcid";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:5:"qrcid";}}s:7:"uniacid";a:3:{s:4:"name";s:7:"uniacid";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:7:"uniacid";}}}}i:41;a:6:{s:9:"tablename";s:15:"ims_qrcode_stat";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"1";s:6:"fields";a:9:{s:2:"id";a:6:{s:4:"name";s:2:"id";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:7:"uniacid";a:7:{s:4:"name";s:7:"uniacid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:4:"acid";a:6:{s:4:"name";s:4:"acid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:3:"qid";a:6:{s:4:"name";s:3:"qid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:6:"openid";a:7:{s:4:"name";s:6:"openid";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:4:"type";a:7:{s:4:"name";s:4:"type";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"1";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:5:"qrcid";a:7:{s:4:"name";s:5:"qrcid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:4:"name";a:7:{s:4:"name";s:4:"name";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:10:"createtime";a:7:{s:4:"name";s:10:"createtime";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}}s:7:"indexes";a:1:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:2:"id";}}}}i:42;a:6:{s:9:"tablename";s:8:"ims_rule";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"9";s:6:"fields";a:6:{s:2:"id";a:6:{s:4:"name";s:2:"id";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:7:"uniacid";a:7:{s:4:"name";s:7:"uniacid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:4:"name";a:7:{s:4:"name";s:4:"name";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"module";a:6:{s:4:"name";s:6:"module";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:12:"displayorder";a:7:{s:4:"name";s:12:"displayorder";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:6:"status";a:7:{s:4:"name";s:6:"status";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"1";s:4:"null";b:0;s:7:"default";s:1:"1";s:6:"signed";b:0;s:9:"increment";b:0;}}s:7:"indexes";a:1:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:2:"id";}}}}i:43;a:6:{s:9:"tablename";s:16:"ims_rule_keyword";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:2:"13";s:6:"fields";a:8:{s:2:"id";a:6:{s:4:"name";s:2:"id";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:3:"rid";a:7:{s:4:"name";s:3:"rid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:7:"uniacid";a:7:{s:4:"name";s:7:"uniacid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:6:"module";a:6:{s:4:"name";s:6:"module";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"content";a:6:{s:4:"name";s:7:"content";s:4:"type";s:7:"varchar";s:6:"length";s:3:"255";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:4:"type";a:7:{s:4:"name";s:4:"type";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"1";s:4:"null";b:0;s:7:"default";s:1:"1";s:6:"signed";b:0;s:9:"increment";b:0;}s:12:"displayorder";a:7:{s:4:"name";s:12:"displayorder";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"3";s:4:"null";b:0;s:7:"default";s:1:"1";s:6:"signed";b:0;s:9:"increment";b:0;}s:6:"status";a:7:{s:4:"name";s:6:"status";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"1";s:4:"null";b:0;s:7:"default";s:1:"1";s:6:"signed";b:0;s:9:"increment";b:0;}}s:7:"indexes";a:2:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:2:"id";}}s:11:"idx_content";a:3:{s:4:"name";s:11:"idx_content";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:7:"content";}}}}i:44;a:6:{s:9:"tablename";s:16:"ims_site_article";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"1";s:6:"fields";a:20:{s:2:"id";a:6:{s:4:"name";s:2:"id";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:7:"uniacid";a:6:{s:4:"name";s:7:"uniacid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:3:"rid";a:6:{s:4:"name";s:3:"rid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:3:"kid";a:6:{s:4:"name";s:3:"kid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:9:"iscommend";a:7:{s:4:"name";s:9:"iscommend";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"1";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:1;s:9:"increment";b:0;}s:5:"ishot";a:7:{s:4:"name";s:5:"ishot";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"1";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:5:"pcate";a:7:{s:4:"name";s:5:"pcate";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:5:"ccate";a:7:{s:4:"name";s:5:"ccate";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:8:"template";a:7:{s:4:"name";s:8:"template";s:4:"type";s:7:"varchar";s:6:"length";s:3:"300";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:5:"title";a:7:{s:4:"name";s:5:"title";s:4:"type";s:7:"varchar";s:6:"length";s:3:"100";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:11:"description";a:7:{s:4:"name";s:11:"description";s:4:"type";s:7:"varchar";s:6:"length";s:3:"100";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"content";a:6:{s:4:"name";s:7:"content";s:4:"type";s:10:"mediumtext";s:6:"length";s:0:"";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:5:"thumb";a:7:{s:4:"name";s:5:"thumb";s:4:"type";s:7:"varchar";s:6:"length";s:3:"100";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"source";a:7:{s:4:"name";s:6:"source";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"author";a:6:{s:4:"name";s:6:"author";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:12:"displayorder";a:7:{s:4:"name";s:12:"displayorder";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:7:"linkurl";a:7:{s:4:"name";s:7:"linkurl";s:4:"type";s:7:"varchar";s:6:"length";s:3:"500";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:10:"createtime";a:7:{s:4:"name";s:10:"createtime";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:4:"type";a:7:{s:4:"name";s:4:"type";s:4:"type";s:7:"varchar";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"credit";a:7:{s:4:"name";s:6:"credit";s:4:"type";s:7:"varchar";s:6:"length";s:3:"255";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}}s:7:"indexes";a:3:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:2:"id";}}s:13:"idx_iscommend";a:3:{s:4:"name";s:13:"idx_iscommend";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:9:"iscommend";}}s:9:"idx_ishot";a:3:{s:4:"name";s:9:"idx_ishot";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:5:"ishot";}}}}i:45;a:6:{s:9:"tablename";s:17:"ims_site_category";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"1";s:6:"fields";a:16:{s:2:"id";a:6:{s:4:"name";s:2:"id";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:7:"uniacid";a:7:{s:4:"name";s:7:"uniacid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:3:"nid";a:7:{s:4:"name";s:3:"nid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:4:"name";a:6:{s:4:"name";s:4:"name";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:8:"parentid";a:7:{s:4:"name";s:8:"parentid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:12:"displayorder";a:7:{s:4:"name";s:12:"displayorder";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"3";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:7:"enabled";a:7:{s:4:"name";s:7:"enabled";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"1";s:4:"null";b:0;s:7:"default";s:1:"1";s:6:"signed";b:0;s:9:"increment";b:0;}s:4:"icon";a:7:{s:4:"name";s:4:"icon";s:4:"type";s:7:"varchar";s:6:"length";s:3:"100";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:11:"description";a:7:{s:4:"name";s:11:"description";s:4:"type";s:7:"varchar";s:6:"length";s:3:"100";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:8:"template";a:7:{s:4:"name";s:8:"template";s:4:"type";s:7:"varchar";s:6:"length";s:3:"300";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"styleid";a:6:{s:4:"name";s:7:"styleid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:12:"templatefile";a:7:{s:4:"name";s:12:"templatefile";s:4:"type";s:7:"varchar";s:6:"length";s:3:"100";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"linkurl";a:7:{s:4:"name";s:7:"linkurl";s:4:"type";s:7:"varchar";s:6:"length";s:3:"500";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:10:"ishomepage";a:7:{s:4:"name";s:10:"ishomepage";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"1";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:1;s:9:"increment";b:0;}s:8:"icontype";a:6:{s:4:"name";s:8:"icontype";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"1";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:3:"css";a:6:{s:4:"name";s:3:"css";s:4:"type";s:7:"varchar";s:6:"length";s:3:"500";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}}s:7:"indexes";a:1:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:2:"id";}}}}i:46;a:6:{s:9:"tablename";s:14:"ims_site_multi";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"2";s:6:"fields";a:7:{s:2:"id";a:6:{s:4:"name";s:2:"id";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:7:"uniacid";a:6:{s:4:"name";s:7:"uniacid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:5:"title";a:6:{s:4:"name";s:5:"title";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"styleid";a:6:{s:4:"name";s:7:"styleid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:9:"site_info";a:6:{s:4:"name";s:9:"site_info";s:4:"type";s:7:"varchar";s:6:"length";s:4:"1000";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:9:"quickmenu";a:6:{s:4:"name";s:9:"quickmenu";s:4:"type";s:7:"varchar";s:6:"length";s:4:"2000";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"status";a:7:{s:4:"name";s:6:"status";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"3";s:4:"null";b:0;s:7:"default";s:1:"1";s:6:"signed";b:0;s:9:"increment";b:0;}}s:7:"indexes";a:2:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:2:"id";}}s:7:"uniacid";a:3:{s:4:"name";s:7:"uniacid";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:7:"uniacid";}}}}i:47;a:6:{s:9:"tablename";s:12:"ims_site_nav";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"1";s:6:"fields";a:13:{s:2:"id";a:6:{s:4:"name";s:2:"id";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:7:"uniacid";a:6:{s:4:"name";s:7:"uniacid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:7:"multiid";a:6:{s:4:"name";s:7:"multiid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:7:"section";a:7:{s:4:"name";s:7:"section";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"4";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"module";a:7:{s:4:"name";s:6:"module";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:12:"displayorder";a:6:{s:4:"name";s:12:"displayorder";s:4:"type";s:8:"smallint";s:6:"length";s:1:"5";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:4:"name";a:6:{s:4:"name";s:4:"name";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:11:"description";a:7:{s:4:"name";s:11:"description";s:4:"type";s:7:"varchar";s:6:"length";s:4:"1000";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:8:"position";a:7:{s:4:"name";s:8:"position";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"4";s:4:"null";b:0;s:7:"default";s:1:"1";s:6:"signed";b:1;s:9:"increment";b:0;}s:3:"url";a:7:{s:4:"name";s:3:"url";s:4:"type";s:7:"varchar";s:6:"length";s:3:"255";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:4:"icon";a:7:{s:4:"name";s:4:"icon";s:4:"type";s:7:"varchar";s:6:"length";s:3:"500";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:3:"css";a:7:{s:4:"name";s:3:"css";s:4:"type";s:7:"varchar";s:6:"length";s:4:"1000";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"status";a:7:{s:4:"name";s:6:"status";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"1";s:4:"null";b:0;s:7:"default";s:1:"1";s:6:"signed";b:0;s:9:"increment";b:0;}}s:7:"indexes";a:3:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:2:"id";}}s:7:"uniacid";a:3:{s:4:"name";s:7:"uniacid";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:7:"uniacid";}}s:7:"multiid";a:3:{s:4:"name";s:7:"multiid";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:7:"multiid";}}}}i:48;a:6:{s:9:"tablename";s:14:"ims_site_slide";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"1";s:6:"fields";a:6:{s:2:"id";a:6:{s:4:"name";s:2:"id";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:7:"uniacid";a:6:{s:4:"name";s:7:"uniacid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:5:"title";a:6:{s:4:"name";s:5:"title";s:4:"type";s:7:"varchar";s:6:"length";s:3:"255";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:3:"url";a:7:{s:4:"name";s:3:"url";s:4:"type";s:7:"varchar";s:6:"length";s:3:"255";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:5:"thumb";a:7:{s:4:"name";s:5:"thumb";s:4:"type";s:7:"varchar";s:6:"length";s:3:"255";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:12:"displayorder";a:7:{s:4:"name";s:12:"displayorder";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"4";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:1;s:9:"increment";b:0;}}s:7:"indexes";a:2:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:2:"id";}}s:7:"uniacid";a:3:{s:4:"name";s:7:"uniacid";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:7:"uniacid";}}}}i:49;a:6:{s:9:"tablename";s:15:"ims_site_styles";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"2";s:6:"fields";a:4:{s:2:"id";a:6:{s:4:"name";s:2:"id";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:7:"uniacid";a:6:{s:4:"name";s:7:"uniacid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:10:"templateid";a:6:{s:4:"name";s:10:"templateid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:4:"name";a:6:{s:4:"name";s:4:"name";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}}s:7:"indexes";a:1:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:2:"id";}}}}i:50;a:6:{s:9:"tablename";s:20:"ims_site_styles_vars";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"1";s:6:"fields";a:6:{s:2:"id";a:6:{s:4:"name";s:2:"id";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:7:"uniacid";a:7:{s:4:"name";s:7:"uniacid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:10:"templateid";a:6:{s:4:"name";s:10:"templateid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:7:"styleid";a:6:{s:4:"name";s:7:"styleid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:8:"variable";a:6:{s:4:"name";s:8:"variable";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"content";a:6:{s:4:"name";s:7:"content";s:4:"type";s:4:"text";s:6:"length";s:0:"";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}}s:7:"indexes";a:1:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:2:"id";}}}}i:51;a:6:{s:9:"tablename";s:18:"ims_site_templates";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"2";s:6:"fields";a:8:{s:2:"id";a:6:{s:4:"name";s:2:"id";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:4:"name";a:7:{s:4:"name";s:4:"name";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:5:"title";a:6:{s:4:"name";s:5:"title";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:11:"description";a:7:{s:4:"name";s:11:"description";s:4:"type";s:7:"varchar";s:6:"length";s:3:"500";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"author";a:6:{s:4:"name";s:6:"author";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:3:"url";a:7:{s:4:"name";s:3:"url";s:4:"type";s:7:"varchar";s:6:"length";s:3:"255";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:4:"type";a:7:{s:4:"name";s:4:"type";s:4:"type";s:7:"varchar";s:6:"length";s:2:"20";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:8:"sections";a:6:{s:4:"name";s:8:"sections";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}}s:7:"indexes";a:1:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:2:"id";}}}}i:52;a:6:{s:9:"tablename";s:16:"ims_solution_acl";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"1";s:6:"fields";a:8:{s:2:"id";a:6:{s:4:"name";s:2:"id";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:3:"uid";a:6:{s:4:"name";s:3:"uid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:6:"module";a:6:{s:4:"name";s:6:"module";s:4:"type";s:7:"varchar";s:6:"length";s:3:"255";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:5:"title";a:7:{s:4:"name";s:5:"title";s:4:"type";s:7:"varchar";s:6:"length";s:3:"255";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:3:"eid";a:7:{s:4:"name";s:3:"eid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:2:"do";a:6:{s:4:"name";s:2:"do";s:4:"type";s:7:"varchar";s:6:"length";s:3:"255";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:5:"state";a:6:{s:4:"name";s:5:"state";s:4:"type";s:7:"varchar";s:6:"length";s:4:"1000";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"enable";a:7:{s:4:"name";s:6:"enable";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"1";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:1;s:9:"increment";b:0;}}s:7:"indexes";a:3:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:2:"id";}}s:10:"idx_module";a:3:{s:4:"name";s:10:"idx_module";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:6:"module";}}s:7:"idx_eid";a:3:{s:4:"name";s:7:"idx_eid";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:3:"eid";}}}}i:53;a:6:{s:9:"tablename";s:16:"ims_stat_keyword";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"1";s:6:"fields";a:7:{s:2:"id";a:6:{s:4:"name";s:2:"id";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:7:"uniacid";a:6:{s:4:"name";s:7:"uniacid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:3:"rid";a:6:{s:4:"name";s:3:"rid";s:4:"type";s:7:"varchar";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:3:"kid";a:6:{s:4:"name";s:3:"kid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:3:"hit";a:6:{s:4:"name";s:3:"hit";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:10:"lastupdate";a:6:{s:4:"name";s:10:"lastupdate";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:10:"createtime";a:6:{s:4:"name";s:10:"createtime";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}}s:7:"indexes";a:2:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:2:"id";}}s:14:"idx_createtime";a:3:{s:4:"name";s:14:"idx_createtime";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:10:"createtime";}}}}i:54;a:6:{s:9:"tablename";s:20:"ims_stat_msg_history";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"1";s:6:"fields";a:9:{s:2:"id";a:6:{s:4:"name";s:2:"id";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:7:"uniacid";a:6:{s:4:"name";s:7:"uniacid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:3:"rid";a:6:{s:4:"name";s:3:"rid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:3:"kid";a:6:{s:4:"name";s:3:"kid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:9:"from_user";a:6:{s:4:"name";s:9:"from_user";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"module";a:6:{s:4:"name";s:6:"module";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"message";a:6:{s:4:"name";s:7:"message";s:4:"type";s:7:"varchar";s:6:"length";s:4:"1000";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:4:"type";a:7:{s:4:"name";s:4:"type";s:4:"type";s:7:"varchar";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:10:"createtime";a:6:{s:4:"name";s:10:"createtime";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}}s:7:"indexes";a:2:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:2:"id";}}s:14:"idx_createtime";a:3:{s:4:"name";s:14:"idx_createtime";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:10:"createtime";}}}}i:55;a:6:{s:9:"tablename";s:13:"ims_stat_rule";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"1";s:6:"fields";a:6:{s:2:"id";a:6:{s:4:"name";s:2:"id";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:7:"uniacid";a:6:{s:4:"name";s:7:"uniacid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:3:"rid";a:6:{s:4:"name";s:3:"rid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:3:"hit";a:6:{s:4:"name";s:3:"hit";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:10:"lastupdate";a:6:{s:4:"name";s:10:"lastupdate";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:10:"createtime";a:6:{s:4:"name";s:10:"createtime";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}}s:7:"indexes";a:2:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:2:"id";}}s:14:"idx_createtime";a:3:{s:4:"name";s:14:"idx_createtime";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:10:"createtime";}}}}i:56;a:6:{s:9:"tablename";s:15:"ims_uni_account";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"2";s:6:"fields";a:4:{s:7:"uniacid";a:6:{s:4:"name";s:7:"uniacid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:7:"groupid";a:7:{s:4:"name";s:7:"groupid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:1;s:9:"increment";b:0;}s:4:"name";a:6:{s:4:"name";s:4:"name";s:4:"type";s:7:"varchar";s:6:"length";s:3:"100";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:11:"description";a:7:{s:4:"name";s:11:"description";s:4:"type";s:7:"varchar";s:6:"length";s:3:"255";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}}s:7:"indexes";a:1:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:7:"uniacid";}}}}i:57;a:6:{s:9:"tablename";s:23:"ims_uni_account_modules";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"6";s:6:"fields";a:5:{s:2:"id";a:6:{s:4:"name";s:2:"id";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:7:"uniacid";a:6:{s:4:"name";s:7:"uniacid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:6:"module";a:7:{s:4:"name";s:6:"module";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"enabled";a:6:{s:4:"name";s:7:"enabled";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"1";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:8:"settings";a:6:{s:4:"name";s:8:"settings";s:4:"type";s:4:"text";s:6:"length";s:0:"";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}}s:7:"indexes";a:2:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:2:"id";}}s:10:"idx_module";a:3:{s:4:"name";s:10:"idx_module";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:6:"module";}}}}i:58;a:6:{s:9:"tablename";s:21:"ims_uni_account_users";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"2";s:6:"fields";a:4:{s:2:"id";a:6:{s:4:"name";s:2:"id";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:7:"uniacid";a:6:{s:4:"name";s:7:"uniacid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:3:"uid";a:6:{s:4:"name";s:3:"uid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:4:"role";a:6:{s:4:"name";s:4:"role";s:4:"type";s:7:"varchar";s:6:"length";s:3:"255";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}}s:7:"indexes";a:2:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:2:"id";}}s:12:"idx_memberid";a:3:{s:4:"name";s:12:"idx_memberid";s:4:"type";s:5:"index";s:6:"fields";a:1:{i:0;s:3:"uid";}}}}i:59;a:6:{s:9:"tablename";s:13:"ims_uni_group";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"2";s:6:"fields";a:4:{s:2:"id";a:6:{s:4:"name";s:2:"id";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:4:"name";a:6:{s:4:"name";s:4:"name";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"modules";a:7:{s:4:"name";s:7:"modules";s:4:"type";s:7:"varchar";s:6:"length";s:4:"5000";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:9:"templates";a:7:{s:4:"name";s:9:"templates";s:4:"type";s:7:"varchar";s:6:"length";s:4:"5000";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}}s:7:"indexes";a:1:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:2:"id";}}}}i:60;a:6:{s:9:"tablename";s:16:"ims_uni_settings";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";N;s:6:"fields";a:18:{s:7:"uniacid";a:6:{s:4:"name";s:7:"uniacid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:8:"passport";a:7:{s:4:"name";s:8:"passport";s:4:"type";s:7:"varchar";s:6:"length";s:3:"200";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:5:"oauth";a:7:{s:4:"name";s:5:"oauth";s:4:"type";s:7:"varchar";s:6:"length";s:3:"100";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:2:"uc";a:6:{s:4:"name";s:2:"uc";s:4:"type";s:7:"varchar";s:6:"length";s:3:"500";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"notify";a:7:{s:4:"name";s:6:"notify";s:4:"type";s:7:"varchar";s:6:"length";s:4:"2000";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:11:"creditnames";a:7:{s:4:"name";s:11:"creditnames";s:4:"type";s:7:"varchar";s:6:"length";s:3:"500";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:15:"creditbehaviors";a:7:{s:4:"name";s:15:"creditbehaviors";s:4:"type";s:7:"varchar";s:6:"length";s:3:"500";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"welcome";a:7:{s:4:"name";s:7:"welcome";s:4:"type";s:7:"varchar";s:6:"length";s:2:"60";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"default";a:7:{s:4:"name";s:7:"default";s:4:"type";s:7:"varchar";s:6:"length";s:2:"60";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:15:"default_message";a:7:{s:4:"name";s:15:"default_message";s:4:"type";s:7:"varchar";s:6:"length";s:4:"1000";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:9:"shortcuts";a:7:{s:4:"name";s:9:"shortcuts";s:4:"type";s:7:"varchar";s:6:"length";s:4:"5000";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"payment";a:7:{s:4:"name";s:7:"payment";s:4:"type";s:7:"varchar";s:6:"length";s:4:"2000";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:9:"groupdata";a:6:{s:4:"name";s:9:"groupdata";s:4:"type";s:7:"varchar";s:6:"length";s:3:"100";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:4:"stat";a:6:{s:4:"name";s:4:"stat";s:4:"type";s:7:"varchar";s:6:"length";s:3:"300";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:9:"bootstrap";a:6:{s:4:"name";s:9:"bootstrap";s:4:"type";s:7:"varchar";s:6:"length";s:3:"120";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"menuset";a:6:{s:4:"name";s:7:"menuset";s:4:"type";s:4:"text";s:6:"length";s:0:"";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:12:"default_site";a:7:{s:4:"name";s:12:"default_site";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:1;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:4:"sync";a:6:{s:4:"name";s:4:"sync";s:4:"type";s:7:"varchar";s:6:"length";s:3:"100";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}}s:7:"indexes";a:1:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:7:"uniacid";}}}}i:61;a:6:{s:9:"tablename";s:18:"ims_uni_verifycode";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"1";s:6:"fields";a:6:{s:2:"id";a:6:{s:4:"name";s:2:"id";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:7:"uniacid";a:6:{s:4:"name";s:7:"uniacid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:8:"receiver";a:6:{s:4:"name";s:8:"receiver";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:10:"verifycode";a:6:{s:4:"name";s:10:"verifycode";s:4:"type";s:7:"varchar";s:6:"length";s:1:"6";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:5:"total";a:6:{s:4:"name";s:5:"total";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"3";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:10:"createtime";a:6:{s:4:"name";s:10:"createtime";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}}s:7:"indexes";a:1:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:2:"id";}}}}i:62;a:6:{s:9:"tablename";s:17:"ims_userapi_cache";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"1";s:6:"fields";a:4:{s:2:"id";a:6:{s:4:"name";s:2:"id";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:3:"key";a:6:{s:4:"name";s:3:"key";s:4:"type";s:7:"varchar";s:6:"length";s:2:"32";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"content";a:6:{s:4:"name";s:7:"content";s:4:"type";s:4:"text";s:6:"length";s:0:"";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:10:"lastupdate";a:6:{s:4:"name";s:10:"lastupdate";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}}s:7:"indexes";a:1:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:2:"id";}}}}i:63;a:6:{s:9:"tablename";s:17:"ims_userapi_reply";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"7";s:6:"fields";a:7:{s:2:"id";a:6:{s:4:"name";s:2:"id";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:3:"rid";a:6:{s:4:"name";s:3:"rid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:11:"description";a:7:{s:4:"name";s:11:"description";s:4:"type";s:7:"varchar";s:6:"length";s:3:"300";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"apiurl";a:7:{s:4:"name";s:6:"apiurl";s:4:"type";s:7:"varchar";s:6:"length";s:3:"300";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:5:"token";a:7:{s:4:"name";s:5:"token";s:4:"type";s:7:"varchar";s:6:"length";s:2:"32";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:12:"default_text";a:7:{s:4:"name";s:12:"default_text";s:4:"type";s:7:"varchar";s:6:"length";s:3:"100";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:9:"cachetime";a:7:{s:4:"name";s:9:"cachetime";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}}s:7:"indexes";a:1:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:2:"id";}}}}i:64;a:6:{s:9:"tablename";s:9:"ims_users";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"2";s:6:"fields";a:11:{s:3:"uid";a:6:{s:4:"name";s:3:"uid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:7:"groupid";a:7:{s:4:"name";s:7:"groupid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:8:"username";a:6:{s:4:"name";s:8:"username";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:8:"password";a:6:{s:4:"name";s:8:"password";s:4:"type";s:7:"varchar";s:6:"length";s:3:"200";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:4:"salt";a:6:{s:4:"name";s:4:"salt";s:4:"type";s:7:"varchar";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"status";a:7:{s:4:"name";s:6:"status";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"4";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:1;s:9:"increment";b:0;}s:8:"joindate";a:7:{s:4:"name";s:8:"joindate";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:6:"joinip";a:7:{s:4:"name";s:6:"joinip";s:4:"type";s:7:"varchar";s:6:"length";s:2:"15";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:9:"lastvisit";a:7:{s:4:"name";s:9:"lastvisit";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:6:"lastip";a:7:{s:4:"name";s:6:"lastip";s:4:"type";s:7:"varchar";s:6:"length";s:2:"15";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"remark";a:7:{s:4:"name";s:6:"remark";s:4:"type";s:7:"varchar";s:6:"length";s:3:"500";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}}s:7:"indexes";a:2:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:3:"uid";}}s:8:"username";a:3:{s:4:"name";s:8:"username";s:4:"type";s:6:"unique";s:6:"fields";a:1:{i:0;s:8:"username";}}}}i:65;a:6:{s:9:"tablename";s:15:"ims_users_group";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"4";s:6:"fields";a:5:{s:2:"id";a:6:{s:4:"name";s:2:"id";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:4:"name";a:6:{s:4:"name";s:4:"name";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"package";a:7:{s:4:"name";s:7:"package";s:4:"type";s:7:"varchar";s:6:"length";s:4:"5000";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:10:"maxaccount";a:7:{s:4:"name";s:10:"maxaccount";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:13:"maxsubaccount";a:6:{s:4:"name";s:13:"maxsubaccount";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}}s:7:"indexes";a:1:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:2:"id";}}}}i:66;a:6:{s:9:"tablename";s:20:"ims_users_invitation";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"1";s:6:"fields";a:5:{s:2:"id";a:6:{s:4:"name";s:2:"id";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:4:"code";a:6:{s:4:"name";s:4:"code";s:4:"type";s:7:"varchar";s:6:"length";s:2:"64";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"fromuid";a:6:{s:4:"name";s:7:"fromuid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:9:"inviteuid";a:6:{s:4:"name";s:9:"inviteuid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:10:"createtime";a:6:{s:4:"name";s:10:"createtime";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}}s:7:"indexes";a:2:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:2:"id";}}s:8:"idx_code";a:3:{s:4:"name";s:8:"idx_code";s:4:"type";s:6:"unique";s:6:"fields";a:1:{i:0;s:4:"code";}}}}i:67;a:6:{s:9:"tablename";s:17:"ims_users_profile";s:7:"charset";s:15:"utf8_general_ci";s:6:"engine";s:6:"MyISAM";s:9:"increment";s:1:"1";s:6:"fields";a:44:{s:2:"id";a:6:{s:4:"name";s:2:"id";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:1;}s:3:"uid";a:6:{s:4:"name";s:3:"uid";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:10:"createtime";a:6:{s:4:"name";s:10:"createtime";s:4:"type";s:3:"int";s:6:"length";s:2:"10";s:4:"null";b:0;s:6:"signed";b:0;s:9:"increment";b:0;}s:8:"realname";a:7:{s:4:"name";s:8:"realname";s:4:"type";s:7:"varchar";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:8:"nickname";a:7:{s:4:"name";s:8:"nickname";s:4:"type";s:7:"varchar";s:6:"length";s:2:"20";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"avatar";a:7:{s:4:"name";s:6:"avatar";s:4:"type";s:7:"varchar";s:6:"length";s:3:"100";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:2:"qq";a:7:{s:4:"name";s:2:"qq";s:4:"type";s:7:"varchar";s:6:"length";s:2:"15";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"mobile";a:7:{s:4:"name";s:6:"mobile";s:4:"type";s:7:"varchar";s:6:"length";s:2:"11";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"fakeid";a:6:{s:4:"name";s:6:"fakeid";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:3:"vip";a:7:{s:4:"name";s:3:"vip";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"3";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:6:"gender";a:7:{s:4:"name";s:6:"gender";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"1";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:1;s:9:"increment";b:0;}s:9:"birthyear";a:7:{s:4:"name";s:9:"birthyear";s:4:"type";s:8:"smallint";s:6:"length";s:1:"6";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:10:"birthmonth";a:7:{s:4:"name";s:10:"birthmonth";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"3";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:8:"birthday";a:7:{s:4:"name";s:8:"birthday";s:4:"type";s:7:"tinyint";s:6:"length";s:1:"3";s:4:"null";b:0;s:7:"default";s:1:"0";s:6:"signed";b:0;s:9:"increment";b:0;}s:13:"constellation";a:7:{s:4:"name";s:13:"constellation";s:4:"type";s:7:"varchar";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"zodiac";a:7:{s:4:"name";s:6:"zodiac";s:4:"type";s:7:"varchar";s:6:"length";s:1:"5";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:9:"telephone";a:7:{s:4:"name";s:9:"telephone";s:4:"type";s:7:"varchar";s:6:"length";s:2:"15";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"idcard";a:7:{s:4:"name";s:6:"idcard";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:9:"studentid";a:7:{s:4:"name";s:9:"studentid";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:5:"grade";a:7:{s:4:"name";s:5:"grade";s:4:"type";s:7:"varchar";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"address";a:7:{s:4:"name";s:7:"address";s:4:"type";s:7:"varchar";s:6:"length";s:3:"255";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"zipcode";a:7:{s:4:"name";s:7:"zipcode";s:4:"type";s:7:"varchar";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:11:"nationality";a:7:{s:4:"name";s:11:"nationality";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:14:"resideprovince";a:7:{s:4:"name";s:14:"resideprovince";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:10:"residecity";a:7:{s:4:"name";s:10:"residecity";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:10:"residedist";a:7:{s:4:"name";s:10:"residedist";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:14:"graduateschool";a:7:{s:4:"name";s:14:"graduateschool";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"company";a:7:{s:4:"name";s:7:"company";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:9:"education";a:7:{s:4:"name";s:9:"education";s:4:"type";s:7:"varchar";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:10:"occupation";a:7:{s:4:"name";s:10:"occupation";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:8:"position";a:7:{s:4:"name";s:8:"position";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:7:"revenue";a:7:{s:4:"name";s:7:"revenue";s:4:"type";s:7:"varchar";s:6:"length";s:2:"10";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:15:"affectivestatus";a:7:{s:4:"name";s:15:"affectivestatus";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:10:"lookingfor";a:7:{s:4:"name";s:10:"lookingfor";s:4:"type";s:7:"varchar";s:6:"length";s:3:"255";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:9:"bloodtype";a:7:{s:4:"name";s:9:"bloodtype";s:4:"type";s:7:"varchar";s:6:"length";s:1:"5";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"height";a:7:{s:4:"name";s:6:"height";s:4:"type";s:7:"varchar";s:6:"length";s:1:"5";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"weight";a:7:{s:4:"name";s:6:"weight";s:4:"type";s:7:"varchar";s:6:"length";s:1:"5";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"alipay";a:7:{s:4:"name";s:6:"alipay";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:3:"msn";a:7:{s:4:"name";s:3:"msn";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:5:"email";a:7:{s:4:"name";s:5:"email";s:4:"type";s:7:"varchar";s:6:"length";s:2:"50";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:6:"taobao";a:7:{s:4:"name";s:6:"taobao";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:4:"site";a:7:{s:4:"name";s:4:"site";s:4:"type";s:7:"varchar";s:6:"length";s:2:"30";s:4:"null";b:0;s:7:"default";s:0:"";s:6:"signed";b:1;s:9:"increment";b:0;}s:3:"bio";a:6:{s:4:"name";s:3:"bio";s:4:"type";s:4:"text";s:6:"length";s:0:"";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}s:8:"interest";a:6:{s:4:"name";s:8:"interest";s:4:"type";s:4:"text";s:6:"length";s:0:"";s:4:"null";b:0;s:6:"signed";b:1;s:9:"increment";b:0;}}s:7:"indexes";a:1:{s:7:"PRIMARY";a:3:{s:4:"name";s:7:"PRIMARY";s:4:"type";s:7:"primary";s:6:"fields";a:1:{i:0;s:2:"id";}}}}}';

$datas = array();
//公众号相关
$datas[] = "INSERT INTO `ims_uni_account_modules` (`id`, `uniacid`, `module`, `enabled`, `settings`) VALUES
(1, 1, 'basic', 1, ''),
(2, 1, 'news', 1, ''),
(3, 1, 'music', 1, ''),
(4, 1, 'userapi', 1, ''),
(5, 1, 'recharge', 1, '');";
$datas[] = "INSERT INTO `ims_uni_account_users` (`id`, `uniacid`, `uid`, `role`) VALUES(1, 1, 1, 'manager');";
$datas[] = <<<EOF
INSERT INTO `ims_uni_settings` (`uniacid`, `passport`, `oauth`, `uc`, `notify`, `creditnames`, `creditbehaviors`, `welcome`, `default`, `default_message`, `shortcuts`, `payment`, `groupdata`, `stat`, `bootstrap`, `menuset`, `default_site`) VALUES
(1, 'a:3:{s:8:"focusreg";i:0;s:4:"item";s:5:"email";s:4:"type";s:8:"password";}', 'a:2:{s:6:"status";s:1:"0";s:7:"account";s:1:"0";}', 'a:1:{s:6:"status";i:0;}', 'a:1:{s:3:"sms";a:2:{s:7:"balance";i:0;s:9:"signature";s:0:"";}}', 'a:5:{s:7:"credit1";a:2:{s:5:"title";s:6:"积分";s:7:"enabled";i:1;}s:7:"credit2";a:2:{s:5:"title";s:6:"余额";s:7:"enabled";i:1;}s:7:"credit3";a:2:{s:5:"title";s:0:"";s:7:"enabled";i:0;}s:7:"credit4";a:2:{s:5:"title";s:0:"";s:7:"enabled";i:0;}s:7:"credit5";a:2:{s:5:"title";s:0:"";s:7:"enabled";i:0;}}', 'a:2:{s:8:"activity";s:7:"credit1";s:8:"currency";s:7:"credit2";}', '', '', '', '', 'a:4:{s:6:"credit";a:1:{s:6:"switch";b:0;}s:6:"alipay";a:4:{s:6:"switch";b:0;s:7:"account";s:0:"";s:7:"partner";s:0:"";s:6:"secret";s:0:"";}s:6:"wechat";a:5:{s:6:"switch";b:0;s:7:"account";b:0;s:7:"signkey";s:0:"";s:7:"partner";s:0:"";s:3:"key";s:0:"";}s:8:"delivery";a:1:{s:6:"switch";b:0;}}', 'a:3:{s:8:"isexpire";i:0;s:10:"oldgroupid";s:0:"";s:7:"endtime";i:1410364919;}', '', '', '', 1);
EOF;

//系统初始化数据
$datas[] = <<<EOF
INSERT INTO `ims_core_settings` (`key`, `value`) VALUES('authmode', 'i:1;'),('close', 'a:2:{s:6:"status";s:1:"0";s:6:"reason";s:0:"";}');
EOF;
$datas[] = <<<EOF
INSERT INTO `ims_core_settings` (`key`, `value`) VALUES ('copyright', 'a:17:{s:8:"sitename";s:24:"微信管理系统";s:3:"url";s:17:"http://www.weizancms.com";s:8:"statcode";s:0:"";s:10:"footerleft";s:17:"powered by weizancms.com";s:11:"footerright";s:0:"";s:5:"flogo";s:0:"";s:5:"blogo";s:0:"";s:8:"baidumap";a:2:{s:3:"lng";s:10:"114.060055";s:3:"lat";s:9:"22.529554";}s:7:"company";s:39:"XX科技有限公司";s:7:"address";s:68:"深圳XX区XX街";s:6:"person";s:9:"微信";s:5:"phone";s:11:"15800008888";s:2:"qq";s:9:"XXXXXXX";s:5:"email";s:0:"";s:8:"keywords";s:82:"微信,微信,微信公众平台,公众平台二次开发,公众平台开源软件";s:11:"description";s:82:"微信,微信,微信公众平台,公众平台二次开发,公众平台开源软件";s:12:"showhomepage";i:1;}');
EOF;
$datas[] = <<<EOF
INSERT INTO `ims_core_settings` (`key`, `value`) VALUES ('register', 'a:4:{s:4:"open";i:1;s:6:"verify";i:0;s:4:"code";i:1;s:7:"groupid";i:1;}');
EOF;
$datas[] = <<<EOF
INSERT INTO `ims_core_settings` (`key`, `value`) VALUES ('addons','a:2:{s:10:"addons_url";s:23:"http://addons.weizancms.com";s:5:"c_url";s:20:"http://www.012wz.com";}');
EOF;

$datas[] = "INSERT INTO `ims_users_group` (`id`, `name`, `package`, `maxaccount`, `maxsubaccount`) VALUES
(1, '体验用户组', 'a:1:{i:0;i:1;}', 1, 1),
(2, '白金用户组', 'a:1:{i:0;i:1;}', 2, 2),
(3, '黄金用户组', 'a:1:{i:0;i:1;}', 3, 3);";

//系统服务回复
$datas[] = <<<EOF
INSERT INTO `ims_userapi_reply` (`id`, `rid`, `description`, `apiurl`, `token`, `default_text`, `cachetime`) VALUES
(1, 1, '"城市名+天气", 如: "北京天气"', 'weather.php', '', '', 0),
(2, 2, '"百科+查询内容" 或 "定义+查询内容", 如: "百科姚明", "定义自行车"', 'baike.php', '', '', 0),
(3, 3, '"@查询内容(中文或英文)"', 'translate.php', '', '', 0),
(4, 4, '"日历", "万年历", "黄历"或"几号"', 'calendar.php', '', '', 0),
(5, 5, '"新闻"', 'news.php', '', '', 0),
(6, 6, '"快递+单号", 如: "申通1200041125"', 'express.php', '', '', 0);
EOF;

$datas[] = <<<EOF
INSERT INTO `ims_core_cache` (`key`, `value`) VALUES
('system_frame','a:7:{s:6:"system";a:5:{s:5:"title";s:12:"系统管理";s:3:"url";s:45:"./index.php?c=account&a=manage&account_type=1";s:7:"section";a:6:{s:10:"wxplatform";a:2:{s:5:"title";s:9:"公众号";s:4:"menu";a:1:{s:14:"system_account";a:9:{s:9:"is_system";i:1;s:10:"is_display";i:1;s:5:"title";s:16:" 微信公众号";s:3:"url";s:45:"./index.php?c=account&a=manage&account_type=1";s:15:"permission_name";s:14:"system_account";s:4:"icon";s:12:"wi wi-wechat";s:12:"displayorder";i:1;s:2:"id";N;s:14:"sub_permission";a:6:{i:0;a:2:{s:5:"title";s:21:"公众号管理设置";s:15:"permission_name";s:21:"system_account_manage";}i:1;a:2:{s:5:"title";s:15:"添加公众号";s:15:"permission_name";s:19:"system_account_post";}i:2;a:2:{s:5:"title";s:15:"公众号停用";s:15:"permission_name";s:19:"system_account_stop";}i:3;a:2:{s:5:"title";s:18:"公众号回收站";s:15:"permission_name";s:22:"system_account_recycle";}i:4;a:2:{s:5:"title";s:15:"公众号删除";s:15:"permission_name";s:21:"system_account_delete";}i:5;a:2:{s:5:"title";s:15:"公众号恢复";s:15:"permission_name";s:22:"system_account_recover";}}}}}s:6:"module";a:2:{s:5:"title";s:9:"小程序";s:4:"menu";a:1:{s:12:"system_wxapp";a:9:{s:9:"is_system";i:1;s:10:"is_display";i:1;s:5:"title";s:15:"微信小程序";s:3:"url";s:45:"./index.php?c=account&a=manage&account_type=4";s:15:"permission_name";s:12:"system_wxapp";s:4:"icon";s:11:"wi wi-wxapp";s:12:"displayorder";i:1;s:2:"id";N;s:14:"sub_permission";N;}}}s:4:"user";a:2:{s:5:"title";s:13:"帐户/用户";s:4:"menu";a:4:{s:9:"system_my";a:9:{s:9:"is_system";i:1;s:10:"is_display";i:1;s:5:"title";s:12:"我的帐户";s:3:"url";s:29:"./index.php?c=user&a=profile&";s:15:"permission_name";s:9:"system_my";s:4:"icon";s:10:"wi wi-user";s:12:"displayorder";i:4;s:2:"id";N;s:14:"sub_permission";N;}s:18:"system_shop_member";a:9:{s:9:"is_system";i:1;s:10:"is_display";i:1;s:5:"title";s:12:"余额充值";s:3:"url";s:38:"./index.php?c=shop&a=member&do=member&";s:15:"permission_name";s:25:"system_shop_member_member";s:4:"icon";s:10:"wi wi-user";s:12:"displayorder";i:3;s:2:"id";N;s:14:"sub_permission";N;}s:25:"system_shop_member_record";a:9:{s:9:"is_system";i:1;s:10:"is_display";i:1;s:5:"title";s:12:"消费记录";s:3:"url";s:38:"./index.php?c=shop&a=member&do=record&";s:15:"permission_name";s:25:"system_shop_member_record";s:4:"icon";s:17:"wi wi-wxapp-apply";s:12:"displayorder";i:2;s:2:"id";N;s:14:"sub_permission";N;}s:27:"system_shop_member_chongzhi";a:9:{s:9:"is_system";i:1;s:10:"is_display";i:1;s:5:"title";s:12:"充值记录";s:3:"url";s:40:"./index.php?c=shop&a=member&do=chongzhi&";s:15:"permission_name";s:27:"system_shop_member_chongzhi";s:4:"icon";s:17:"wi wi-wxapp-apply";s:12:"displayorder";i:1;s:2:"id";N;s:14:"sub_permission";N;}}}s:10:"permission";a:2:{s:5:"title";s:12:"权限管理";s:4:"menu";a:1:{s:19:"system_module_group";a:9:{s:9:"is_system";i:1;s:10:"is_display";i:1;s:5:"title";s:15:"应用权限组";s:3:"url";s:36:"./index.php?c=system&a=module-group&";s:15:"permission_name";s:19:"system_module_group";s:4:"icon";s:21:"wi wi-appjurisdiction";s:12:"displayorder";i:1;s:2:"id";N;s:14:"sub_permission";N;}}}s:7:"userset";a:2:{s:5:"title";s:12:"高级工具";s:4:"menu";a:3:{s:15:"user_set_yuming";a:9:{s:9:"is_system";i:1;s:10:"is_display";i:1;s:5:"title";s:12:"域名绑定";s:3:"url";s:35:"./index.php?c=user&a=set&do=yuming&";s:15:"permission_name";s:15:"user_set_yuming";s:4:"icon";s:21:"wi wi-appjurisdiction";s:12:"displayorder";i:3;s:2:"id";N;s:14:"sub_permission";N;}s:18:"user_set_copyright";a:9:{s:9:"is_system";i:1;s:10:"is_display";i:1;s:5:"title";s:12:"版权设置";s:3:"url";s:38:"./index.php?c=user&a=set&do=copyright&";s:15:"permission_name";s:18:"user_set_copyright";s:4:"icon";s:21:"wi wi-appjurisdiction";s:12:"displayorder";i:2;s:2:"id";N;s:14:"sub_permission";N;}s:13:"user_set_pifu";a:9:{s:9:"is_system";i:1;s:10:"is_display";i:1;s:5:"title";s:15:"自定义皮肤";s:3:"url";s:33:"./index.php?c=user&a=set&do=pifu&";s:15:"permission_name";s:13:"user_set_pifu";s:4:"icon";s:21:"wi wi-appjurisdiction";s:12:"displayorder";i:1;s:2:"id";N;s:14:"sub_permission";N;}}}s:5:"cache";a:2:{s:5:"title";s:6:"缓存";s:4:"menu";a:1:{s:26:"system_setting_updatecache";a:9:{s:9:"is_system";i:1;s:10:"is_display";i:1;s:5:"title";s:12:"更新缓存";s:3:"url";s:35:"./index.php?c=system&a=updatecache&";s:15:"permission_name";s:26:"system_setting_updatecache";s:4:"icon";s:12:"wi wi-update";s:12:"displayorder";i:1;s:2:"id";N;s:14:"sub_permission";N;}}}}s:9:"is_system";b:1;s:10:"is_display";b:1;}s:4:"site";a:6:{s:5:"title";s:12:"站点管理";s:3:"url";s:28:"./index.php?c=system&a=site&";s:7:"section";a:3:{s:5:"cloud";a:2:{s:5:"title";s:9:"云服务";s:4:"menu";a:4:{s:14:"system_profile";a:9:{s:9:"is_system";i:1;s:10:"is_display";i:1;s:5:"title";s:12:"系统升级";s:3:"url";s:30:"./index.php?c=cloud&a=upgrade&";s:15:"permission_name";s:20:"system_cloud_upgrade";s:4:"icon";s:11:"wi wi-cache";s:12:"displayorder";i:4;s:2:"id";N;s:14:"sub_permission";N;}s:21:"system_cloud_register";a:9:{s:9:"is_system";i:1;s:10:"is_display";i:1;s:5:"title";s:12:"注册站点";s:3:"url";s:30:"./index.php?c=cloud&a=profile&";s:15:"permission_name";s:21:"system_cloud_register";s:4:"icon";s:18:"wi wi-registersite";s:12:"displayorder";i:3;s:2:"id";N;s:14:"sub_permission";N;}s:21:"system_cloud_diagnose";a:9:{s:9:"is_system";i:1;s:10:"is_display";i:1;s:5:"title";s:15:"云服务诊断";s:3:"url";s:31:"./index.php?c=cloud&a=diagnose&";s:15:"permission_name";s:21:"system_cloud_diagnose";s:4:"icon";s:14:"wi wi-diagnose";s:12:"displayorder";i:2;s:2:"id";N;s:14:"sub_permission";N;}s:19:"system_cloud_addons";a:9:{s:9:"is_system";i:1;s:10:"is_display";i:1;s:5:"title";s:12:"切换商城";s:3:"url";s:30:"./index.php?c=system&a=addons&";s:15:"permission_name";s:19:"system_cloud_addons";s:4:"icon";s:14:"wi wi-wx-apply";s:12:"displayorder";i:1;s:2:"id";N;s:14:"sub_permission";N;}}}s:7:"setting";a:2:{s:5:"title";s:6:"设置";s:4:"menu";a:5:{s:19:"system_setting_site";a:9:{s:9:"is_system";i:1;s:10:"is_display";i:1;s:5:"title";s:12:"站点设置";s:3:"url";s:28:"./index.php?c=system&a=site&";s:15:"permission_name";s:19:"system_setting_site";s:4:"icon";s:18:"wi wi-site-setting";s:12:"displayorder";i:5;s:2:"id";N;s:14:"sub_permission";N;}s:19:"system_setting_menu";a:9:{s:9:"is_system";i:1;s:10:"is_display";i:1;s:5:"title";s:12:"菜单设置";s:3:"url";s:28:"./index.php?c=system&a=menu&";s:15:"permission_name";s:19:"system_setting_menu";s:4:"icon";s:18:"wi wi-menu-setting";s:12:"displayorder";i:4;s:2:"id";N;s:14:"sub_permission";N;}s:25:"system_setting_attachment";a:9:{s:9:"is_system";i:1;s:10:"is_display";i:1;s:5:"title";s:12:"附件设置";s:3:"url";s:34:"./index.php?c=system&a=attachment&";s:15:"permission_name";s:25:"system_setting_attachment";s:4:"icon";s:16:"wi wi-attachment";s:12:"displayorder";i:3;s:2:"id";N;s:14:"sub_permission";N;}s:25:"system_setting_systeminfo";a:9:{s:9:"is_system";i:1;s:10:"is_display";i:1;s:5:"title";s:12:"系统信息";s:3:"url";s:34:"./index.php?c=system&a=systeminfo&";s:15:"permission_name";s:25:"system_setting_systeminfo";s:4:"icon";s:17:"wi wi-system-info";s:12:"displayorder";i:2;s:2:"id";N;s:14:"sub_permission";N;}s:19:"system_setting_logs";a:9:{s:9:"is_system";i:1;s:10:"is_display";i:1;s:5:"title";s:12:"查看日志";s:3:"url";s:28:"./index.php?c=system&a=logs&";s:15:"permission_name";s:19:"system_setting_logs";s:4:"icon";s:9:"wi wi-log";s:12:"displayorder";i:1;s:2:"id";N;s:14:"sub_permission";N;}}}s:7:"utility";a:2:{s:5:"title";s:12:"常用工具";s:4:"menu";a:5:{s:24:"system_utility_filecheck";a:9:{s:9:"is_system";i:1;s:10:"is_display";i:1;s:5:"title";s:18:"系统文件校验";s:3:"url";s:33:"./index.php?c=system&a=filecheck&";s:15:"permission_name";s:24:"system_utility_filecheck";s:4:"icon";s:10:"wi wi-file";s:12:"displayorder";i:5;s:2:"id";N;s:14:"sub_permission";N;}s:23:"system_utility_optimize";a:9:{s:9:"is_system";i:1;s:10:"is_display";i:1;s:5:"title";s:12:"性能优化";s:3:"url";s:32:"./index.php?c=system&a=optimize&";s:15:"permission_name";s:23:"system_utility_optimize";s:4:"icon";s:14:"wi wi-optimize";s:12:"displayorder";i:4;s:2:"id";N;s:14:"sub_permission";N;}s:23:"system_utility_database";a:9:{s:9:"is_system";i:1;s:10:"is_display";i:1;s:5:"title";s:9:"数据库";s:3:"url";s:32:"./index.php?c=system&a=database&";s:15:"permission_name";s:23:"system_utility_database";s:4:"icon";s:9:"wi wi-sql";s:12:"displayorder";i:3;s:2:"id";N;s:14:"sub_permission";N;}s:19:"system_utility_scan";a:9:{s:9:"is_system";i:1;s:10:"is_display";i:1;s:5:"title";s:12:"木马查杀";s:3:"url";s:28:"./index.php?c=system&a=scan&";s:15:"permission_name";s:19:"system_utility_scan";s:4:"icon";s:12:"wi wi-safety";s:12:"displayorder";i:2;s:2:"id";N;s:14:"sub_permission";N;}s:18:"system_utility_bom";a:9:{s:9:"is_system";i:1;s:10:"is_display";i:1;s:5:"title";s:15:"检测文件BOM";s:3:"url";s:27:"./index.php?c=system&a=bom&";s:15:"permission_name";s:18:"system_utility_bom";s:4:"icon";s:9:"wi wi-bom";s:12:"displayorder";i:1;s:2:"id";N;s:14:"sub_permission";N;}}}}s:7:"founder";b:1;s:9:"is_system";b:1;s:10:"is_display";b:1;}s:7:"account";a:5:{s:5:"title";s:15:"公众号管理";s:3:"url";s:29:"./index.php?c=home&a=welcome&";s:7:"section";a:6:{s:13:"platform_plus";a:2:{s:5:"title";s:12:"增强功能";s:4:"menu";a:6:{s:14:"platform_reply";a:9:{s:9:"is_system";i:1;s:10:"is_display";i:1;s:5:"title";s:12:"自动回复";s:3:"url";s:31:"./index.php?c=platform&a=reply&";s:15:"permission_name";s:14:"platform_reply";s:4:"icon";s:11:"wi wi-reply";s:12:"displayorder";i:6;s:2:"id";N;s:14:"sub_permission";a:3:{i:0;a:2:{s:5:"title";s:22:"关键字自动回复 ";s:15:"permission_name";s:14:"platform_reply";}i:1;a:2:{s:5:"title";s:25:"非关键字自动回复 ";s:15:"permission_name";s:22:"platform_reply_special";}i:2;a:2:{s:5:"title";s:19:"欢迎/默认回复";s:15:"permission_name";s:21:"platform_reply_system";}}}s:13:"platform_menu";a:9:{s:9:"is_system";i:1;s:10:"is_display";s:1:"1";s:5:"title";s:15:"自定义菜单";s:3:"url";s:30:"./index.php?c=platform&a=menu&";s:15:"permission_name";s:13:"platform_menu";s:4:"icon";s:16:"wi wi-custommenu";s:12:"displayorder";i:5;s:2:"id";N;s:14:"sub_permission";N;}s:11:"platform_qr";a:9:{s:9:"is_system";i:1;s:10:"is_display";s:1:"1";s:5:"title";s:15:"二维码管理";s:3:"url";s:28:"./index.php?c=platform&a=qr&";s:15:"permission_name";s:11:"platform_qr";s:4:"icon";s:12:"wi wi-qrcode";s:12:"displayorder";i:4;s:2:"id";N;s:14:"sub_permission";a:2:{i:0;a:2:{s:5:"title";s:9:"二维码";s:15:"permission_name";s:11:"platform_qr";}i:1;a:2:{s:5:"title";s:12:"转化链接";s:15:"permission_name";s:15:"platform_url2qr";}}}s:18:"platform_mass_task";a:9:{s:9:"is_system";i:1;s:10:"is_display";i:1;s:5:"title";s:12:"定时群发";s:3:"url";s:30:"./index.php?c=platform&a=mass&";s:15:"permission_name";s:18:"platform_mass_task";s:4:"icon";s:13:"wi wi-crontab";s:12:"displayorder";i:3;s:2:"id";N;s:14:"sub_permission";N;}s:17:"platform_material";a:9:{s:9:"is_system";i:1;s:10:"is_display";i:1;s:5:"title";s:16:"素材/编辑器";s:3:"url";s:34:"./index.php?c=platform&a=material&";s:15:"permission_name";s:17:"platform_material";s:4:"icon";s:12:"wi wi-redact";s:12:"displayorder";i:2;s:2:"id";N;s:14:"sub_permission";N;}s:13:"platform_site";a:9:{s:9:"is_system";i:1;s:10:"is_display";i:1;s:5:"title";s:16:"微官网-文章";s:3:"url";s:38:"./index.php?c=site&a=multi&do=display&";s:15:"permission_name";s:13:"platform_site";s:4:"icon";s:10:"wi wi-home";s:12:"displayorder";i:1;s:2:"id";N;s:14:"sub_permission";a:2:{i:0;a:2:{s:5:"title";s:13:"添加/编辑";s:15:"permission_name";s:18:"platform_site_post";}i:1;a:2:{s:5:"title";s:6:"删除";s:15:"permission_name";s:20:"platform_site_delete";}}}}}s:15:"platform_module";a:3:{s:5:"title";s:12:"应用模块";s:4:"menu";a:0:{}s:10:"is_display";b:1;}s:2:"mc";a:2:{s:5:"title";s:6:"粉丝";s:4:"menu";a:2:{s:7:"mc_fans";a:9:{s:9:"is_system";i:1;s:10:"is_display";s:1:"1";s:5:"title";s:6:"粉丝";s:3:"url";s:24:"./index.php?c=mc&a=fans&";s:15:"permission_name";s:7:"mc_fans";s:4:"icon";s:16:"wi wi-fansmanage";s:12:"displayorder";i:2;s:2:"id";N;s:14:"sub_permission";N;}s:9:"mc_member";a:9:{s:9:"is_system";i:1;s:10:"is_display";s:1:"1";s:5:"title";s:6:"会员";s:3:"url";s:26:"./index.php?c=mc&a=member&";s:15:"permission_name";s:9:"mc_member";s:4:"icon";s:10:"wi wi-fans";s:12:"displayorder";i:1;s:2:"id";N;s:14:"sub_permission";N;}}}s:7:"profile";a:2:{s:5:"title";s:6:"配置";s:4:"menu";a:1:{s:7:"profile";a:9:{s:9:"is_system";i:1;s:10:"is_display";i:1;s:5:"title";s:12:"参数配置";s:3:"url";s:33:"./index.php?c=profile&a=passport&";s:15:"permission_name";s:15:"profile_setting";s:4:"icon";s:11:"wi wi-wxapp";s:12:"displayorder";i:1;s:2:"id";N;s:14:"sub_permission";N;}}}s:4:"shop";a:2:{s:5:"title";s:12:"应用商店";s:4:"menu";a:2:{s:13:"shop_mymodule";a:9:{s:9:"is_system";i:1;s:10:"is_display";i:1;s:5:"title";s:12:"应用商店";s:3:"url";s:30:"./index.php?c=shop&a=mymodule&";s:15:"permission_name";s:13:"shop_mymodule";s:4:"icon";s:11:"wi wi-wxapp";s:12:"displayorder";i:2;s:2:"id";N;s:14:"sub_permission";a:1:{i:0;a:2:{s:5:"title";s:12:"模块购买";s:15:"permission_name";s:16:"shop_morder_post";}}}s:12:"shop_mrecord";a:9:{s:9:"is_system";i:1;s:10:"is_display";i:1;s:5:"title";s:12:"消费记录";s:3:"url";s:29:"./index.php?c=shop&a=mrecord&";s:15:"permission_name";s:12:"shop_mrecord";s:4:"icon";s:11:"wi wi-wxapp";s:12:"displayorder";i:1;s:2:"id";N;s:14:"sub_permission";N;}}}s:7:"fournet";a:2:{s:5:"title";s:12:"四网融合";s:4:"menu";a:5:{s:20:"fournet_wxauth_mplis";a:9:{s:9:"is_system";i:1;s:10:"is_display";i:1;s:5:"title";s:15:"多平台绑定";s:3:"url";s:41:"./index.php?c=fournet&a=wxauth&do=mplist&";s:15:"permission_name";s:20:"fournet_wxauth_mplis";s:4:"icon";s:11:"wi wi-wxapp";s:12:"displayorder";i:5;s:2:"id";N;s:14:"sub_permission";N;}s:21:"fournet_domain_manage";a:9:{s:9:"is_system";i:1;s:10:"is_display";s:1:"1";s:5:"title";s:12:"域名管理";s:3:"url";s:41:"./index.php?c=fournet&a=domain&do=manage&";s:15:"permission_name";s:21:"fournet_domain_manage";s:4:"icon";s:11:"wi wi-wxapp";s:12:"displayorder";i:4;s:2:"id";N;s:14:"sub_permission";N;}s:11:"fournet_msg";a:9:{s:9:"is_system";i:1;s:10:"is_display";s:1:"1";s:5:"title";s:18:"全局短信设置";s:3:"url";s:28:"./index.php?c=fournet&a=msg&";s:15:"permission_name";s:11:"fournet_msg";s:4:"icon";s:11:"wi wi-wxapp";s:12:"displayorder";i:3;s:2:"id";N;s:14:"sub_permission";N;}s:13:"fournet_print";a:9:{s:9:"is_system";i:1;s:10:"is_display";s:1:"1";s:5:"title";s:21:"全局打印机设置";s:3:"url";s:30:"./index.php?c=fournet&a=print&";s:15:"permission_name";s:13:"fournet_print";s:4:"icon";s:11:"wi wi-wxapp";s:12:"displayorder";i:2;s:2:"id";N;s:14:"sub_permission";N;}s:12:"fournet_cron";a:9:{s:9:"is_system";i:1;s:10:"is_display";i:1;s:5:"title";s:18:"全局计划任务";s:3:"url";s:29:"./index.php?c=cron&a=display&";s:15:"permission_name";s:12:"fournet_cron";s:4:"icon";s:11:"wi wi-wxapp";s:12:"displayorder";i:1;s:2:"id";N;s:14:"sub_permission";N;}}}}s:9:"is_system";b:1;s:10:"is_display";b:1;}s:5:"wxapp";a:5:{s:5:"title";s:15:"小程序管理";s:3:"url";s:38:"./index.php?c=wxapp&a=display&do=home&";s:7:"section";a:2:{s:12:"wxapp_module";a:3:{s:5:"title";s:6:"应用";s:4:"menu";a:0:{}s:10:"is_display";b:1;}s:20:"platform_manage_menu";a:2:{s:5:"title";s:6:"管理";s:4:"menu";a:2:{s:11:"module_link";a:9:{s:9:"is_system";i:1;s:10:"is_display";i:1;s:5:"title";s:21:"模块关联公众号";s:3:"url";s:53:"./index.php?c=wxapp&a=version&do=module_link_uniacid&";s:15:"permission_name";s:25:"wxapp_module_link_uniacid";s:4:"icon";s:16:"wi wi-appsetting";s:12:"displayorder";i:2;s:2:"id";N;s:14:"sub_permission";N;}s:13:"wxapp_profile";a:9:{s:9:"is_system";i:1;s:10:"is_display";i:1;s:5:"title";s:12:"支付参数";s:3:"url";s:30:"./index.php?c=wxapp&a=payment&";s:15:"permission_name";s:13:"wxapp_payment";s:4:"icon";s:16:"wi wi-appsetting";s:12:"displayorder";i:1;s:2:"id";N;s:14:"sub_permission";N;}}}}s:9:"is_system";b:1;s:10:"is_display";b:1;}s:5:"pcweb";a:5:{s:5:"title";s:14:"PC网站管理";s:3:"url";s:30:"./index.php?c=pcweb&a=pcmulti&";s:7:"section";a:0:{}s:9:"is_system";b:1;s:10:"is_display";b:1;}s:3:"app";a:5:{s:5:"title";s:9:"APP管理";s:3:"url";s:25:"./index.php?c=app&a=list&";s:7:"section";a:0:{}s:9:"is_system";b:1;s:10:"is_display";b:1;}s:9:"appmarket";a:7:{s:5:"title";s:12:"应用市场";s:3:"url";s:31:"./index.php?c=cloud&a=appstore&";s:7:"section";a:0:{}s:5:"blank";b:1;s:7:"founder";b:1;s:9:"is_system";b:1;s:10:"is_display";b:1;}}');
EOF;

$datas[] = "
ALTER TABLE `ims_account_wechats` ADD   `jsapi_ticket` varchar(1000) NOT NULL;
ALTER TABLE `ims_account_wechats` ADD   `subscribeurl` varchar(120) NOT NULL;

ALTER TABLE `ims_activity_coupon_record`  ADD   `operator` varchar(30) NOT NULL;
ALTER TABLE `ims_business` change `thumb`  `thumb` varchar(255) NOT NULL;

ALTER TABLE `ims_core_paylog` change   `plid` `plid` bigint(11) unsigned NOT NULL AUTO_INCREMENT;

ALTER TABLE `ims_core_sessions` change   `data` `data` varchar(5000) NOT NULL;

ALTER TABLE `ims_mc_credits_record` change `num`  `num` decimal(10,2) NOT NULL;

ALTER TABLE `ims_mc_handsel` change  `fromuid` `fromuid` varchar(32) NOT NULL;

ALTER TABLE `ims_mc_mapping_fans` change  `tag` `tag` varchar(1000) NOT NULL;
ALTER TABLE `ims_mc_mapping_fans` ADD `nickname` varchar(50) NOT NULL;
ALTER TABLE `ims_mc_mapping_fans` ADD `groupid` int(10) unsigned NOT NULL;
ALTER TABLE `ims_mc_mapping_fans` ADD  `updatetime` int(10) unsigned DEFAULT NULL;

ALTER TABLE `ims_news_reply` ADD  `author` varchar(64) NOT NULL;
ALTER TABLE `ims_news_reply` change   `thumb` `thumb` varchar(255) NOT NULL;
ALTER TABLE `ims_news_reply` add  `createtime` int(10) NOT NULL;

ALTER TABLE `ims_site_article` change    `thumb` `thumb` varchar(255) NOT NULL;
ALTER TABLE `ims_site_article` change  `source` `source` varchar(255) NOT NULL;
ALTER TABLE `ims_site_article` add  `incontent` tinyint(1) NOT NULL;

ALTER TABLE `ims_site_multi` change `site_info` `site_info` text NOT NULL;
ALTER TABLE `ims_site_multi` add  `bindhost` varchar(255) NOT NULL;

ALTER TABLE `ims_site_nav` add `categoryid` int(10) unsigned NOT NULL;
ALTER TABLE `ims_site_slide` add    `multiid` int(10) unsigned NOT NULL;

ALTER TABLE `ims_site_styles_vars` add  `description` varchar(50) NOT NULL;

ALTER TABLE `ims_site_templates` add `version` varchar(64) NOT NULL;

ALTER TABLE `ims_uni_settings` add  `jsauth_acid` int(10) unsigned NOT NULL;

DROP TABLE IF EXISTS  `ims_core_wechats_attachment`;
CREATE TABLE `ims_core_wechats_attachment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `filename` varchar(255) NOT NULL,
  `attachment` varchar(255) NOT NULL,
  `media_id` varchar(255) NOT NULL,
  `type` varchar(15) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `media_id` (`media_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS  `ims_custom_reply`;
CREATE TABLE `ims_custom_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `start1` int(10) NOT NULL DEFAULT '-1',
  `end1` int(10) NOT NULL DEFAULT '-1',
  `start2` int(10) NOT NULL DEFAULT '-1',
  `end2` int(10) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS  `ims_images_reply`;
CREATE TABLE `ims_images_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `mediaid` varchar(255) NOT NULL,
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS  `ims_mc_chats_record`;
CREATE TABLE `ims_mc_chats_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `acid` int(10) unsigned NOT NULL,
  `flag` tinyint(3) unsigned NOT NULL,
  `openid` varchar(32) NOT NULL,
  `msgtype` varchar(15) NOT NULL,
  `content` varchar(10000) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`acid`),
  KEY `openid` (`openid`),
  KEY `createtime` (`createtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS  `ims_mc_fans_groups`;
CREATE TABLE `ims_mc_fans_groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `acid` int(10) unsigned NOT NULL,
  `groups` varchar(10000) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS  `ims_mc_mass_record`;
CREATE TABLE `ims_mc_mass_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `acid` int(10) unsigned NOT NULL,
  `groupname` varchar(50) NOT NULL,
  `fansnum` int(10) unsigned NOT NULL,
  `msgtype` varchar(10) NOT NULL,
  `content` varchar(10000) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`acid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS  `ims_mc_member_fields`;
CREATE TABLE `ims_mc_member_fields` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `fieldid` int(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  `available` tinyint(1) NOT NULL,
  `displayorder` smallint(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_fieldid` (`fieldid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS  `ims_users_permission`;
CREATE TABLE `ims_users_permission` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS  `ims_video_reply`;
CREATE TABLE `ims_video_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `mediaid` varchar(255) NOT NULL,
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS  `ims_voice_reply`;
CREATE TABLE `ims_voice_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `mediaid` varchar(255) NOT NULL,
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ims_uni_account_modules
ALTER TABLE `ims_uni_account_modules` ADD INDEX `idx_uniacid` (`uniacid`);
ALTER TABLE `ims_uni_account_modules` ADD  `shortcut` tinyint(1) unsigned NOT NULL;
ALTER TABLE `ims_uni_account_modules` ADD  `displayorder` int(10) unsigned NOT NULL;
-- ims_modules
ALTER TABLE `ims_modules` ADD INDEX `idx_issystem` (`issystem`);

-- ims_rule_keyword
ALTER TABLE `ims_rule_keyword` ADD INDEX `rid` (`rid`);
ALTER TABLE `ims_rule_keyword` ADD INDEX `idx_rid` (`rid`);
ALTER TABLE `ims_rule_keyword` ADD INDEX `idx_uniacid_type_content` (`uniacid`,`type`,`content`);

ALTER TABLE `ims_basic_reply` ADD INDEX(`rid`);
ALTER TABLE `ims_news_reply` ADD INDEX(`rid`);
ALTER TABLE `ims_music_reply` ADD INDEX(`rid`);
ALTER TABLE `ims_article_reply` ADD INDEX(`rid`);
ALTER TABLE `ims_cover_reply` ADD INDEX(`rid`);

ALTER TABLE `ims_userapi_reply` ADD INDEX(`rid`);

alter table `ims_qrcode` add `type` tinyint(1) unsigned NOT NULL DEFAULT '0';

-- 20150412

ALTER TABLE `ims_mc_mapping_fans` ADD INDEX(`updatetime`);
ALTER TABLE `ims_mc_mapping_fans` ADD INDEX(`nickname`);
ALTER TABLE `ims_site_multi` ADD INDEX(`bindhost`);

CREATE TABLE IF NOT EXISTS `ims_mc_oauth_fans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `oauth_openid` varchar(50) NOT NULL,
  `acid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_oauthopenid_acid` (`oauth_openid`,`acid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 20150423

ALTER TABLE `ims_account_wechats` ADD `card_ticket` varchar(1000) NOT NULL;

CREATE TABLE IF NOT EXISTS `ims_activity_coupon_modules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `couponid` int(10) unsigned NOT NULL,
  `module` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `couponid` (`couponid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `ims_core_paylog` ADD `acid` int(10) unsigned NOT NULL;
ALTER TABLE `ims_core_paylog` ADD  `is_usecard` tinyint(3) unsigned NOT NULL;
ALTER TABLE `ims_core_paylog` ADD  `card_type` tinyint(3) unsigned NOT NULL;
ALTER TABLE `ims_core_paylog` ADD  `card_id` varchar(50) NOT NULL;
ALTER TABLE `ims_core_paylog` ADD  `card_fee` decimal(10,2) unsigned NOT NULL;
ALTER TABLE `ims_core_paylog` ADD  `encrypt_code` varchar(100) NOT NULL;
ALTER TABLE `ims_core_queue` ADD  `type` tinyint(3) unsigned NOT NULL;

ALTER TABLE `ims_qrcode` CHANGE  `type` `type` varchar(10) NOT NULL;
ALTER TABLE `ims_qrcode` ADD  `extra` int(10) unsigned NOT NULL;

ALTER TABLE `ims_site_article`  ADD  `click` int(10) unsigned NOT NULL;

CREATE TABLE IF NOT EXISTS `ims_coupon` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `acid` int(10) unsigned NOT NULL,
  `card_id` varchar(50) NOT NULL,
  `type` varchar(15) NOT NULL,
  `logo_url` varchar(150) NOT NULL,
  `code_type` tinyint(3) unsigned NOT NULL,
  `brand_name` varchar(15) NOT NULL,
  `title` varchar(15) NOT NULL,
  `sub_title` varchar(20) NOT NULL,
  `color` varchar(15) NOT NULL,
  `notice` varchar(15) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `date_info` varchar(200) NOT NULL,
  `quantity` int(10) unsigned NOT NULL,
  `location_id_list` varchar(1000) NOT NULL,
  `use_custom_code` tinyint(3) NOT NULL,
  `bind_openid` tinyint(3) unsigned NOT NULL,
  `can_share` tinyint(3) unsigned NOT NULL,
  `can_give_friend` tinyint(3) unsigned NOT NULL,
  `get_limit` tinyint(3) unsigned NOT NULL,
  `service_phone` varchar(20) NOT NULL,
  `extra` varchar(1000) NOT NULL,
  `source` varchar(20) NOT NULL,
  `url_name_type` varchar(20) NOT NULL,
  `custom_url` varchar(100) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `is_display` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`acid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_coupon_activity` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `msg_id` int(10) NOT NULL,
  `status` int(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  `type` int(3) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `coupons` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `members` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `ims_coupon_groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `couponid` varchar(255) NOT NULL,
  `groupid` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_coupon_store` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `couponid` varchar(255) NOT NULL,
  `storeid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `couponid` (`couponid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;
DROP TABLE IF EXISTS  `ims_coupon_location`;
CREATE TABLE IF NOT EXISTS `ims_coupon_location` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `acid` int(10) unsigned NOT NULL,
  `location_id` int(10) unsigned NOT NULL,
  `business_name` varchar(50) NOT NULL,
  `branch_name` varchar(50) NOT NULL,
  `category` varchar(255) NOT NULL,
  `province` varchar(15) NOT NULL,
  `city` varchar(15) NOT NULL,
  `district` varchar(15) NOT NULL,
  `address` varchar(50) NOT NULL,
  `longitude` varchar(15) NOT NULL,
  `latitude` varchar(15) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `photo_list` varchar(10000) NOT NULL,
  `avg_price` int(10) unsigned NOT NULL,
  `open_time` varchar(50) NOT NULL,
  `recommend` varchar(255) NOT NULL,
  `special` varchar(255) NOT NULL,
  `introduction` varchar(255) NOT NULL,
  `offset_type` tinyint(3) unsigned NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `message` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`acid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_coupon_modules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `acid` int(10) unsigned NOT NULL,
  `cid` int(10) unsigned NOT NULL,
  `couponid` int(10) unsigned NOT NULL DEFAULT '0',
  `card_id` varchar(50) NOT NULL,
  `module` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`),
  KEY `card_id` (`card_id`),
  KEY `uniacid` (`uniacid`,`acid`),
  KEY `couponid` (`couponid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_coupon_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `acid` int(10) unsigned NOT NULL,
  `card_id` varchar(50) NOT NULL,
  `outer_id` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `friend_openid` varchar(50) NOT NULL,
  `givebyfriend` tinyint(3) unsigned NOT NULL,
  `code` varchar(50) NOT NULL,
  `hash` varchar(32) NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `usetime` int(10) unsigned NOT NULL,
  `status` tinyint(3) NOT NULL,
  `clerk_name` varchar(15) NOT NULL,
  `clerk_id` int(10) unsigned NOT NULL,
  `store_id` int(10) unsigned NOT NULL,
  `clerk_type` tinyint(3) unsigned NOT NULL,
  `couponid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `grantmodule` varchar(255) NOT NULL,
  `remark` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`acid`),
  KEY `outer_id` (`outer_id`),
  KEY `card_id` (`card_id`),
  KEY `hash` (`hash`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_coupon_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `acid` int(10) NOT NULL,
  `logourl` varchar(150) NOT NULL,
  `whitelist` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`acid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 0506

ALTER TABLE `ims_qrcode` ADD `url` varchar(80) NOT NULL;

-- 0520

ALTER TABLE `ims_site_nav` 
change  `section`  `section` tinyint(4) NOT NULL DEFAULT '1';

-- 0529
ALTER TABLE `ims_activity_coupon` 
add  `use_module` tinyint(3) unsigned NOT NULL;

ALTER TABLE `ims_core_wechats_attachment` 
 add `acid` int(10) unsigned NOT NULL,
 add `width` int(10) unsigned NOT NULL,
 add `height` int(10) unsigned NOT NULL,
 add `model` varchar(25) NOT NULL,
 add `tag` varchar(1000) NOT NULL;
 
ALTER TABLE `ims_modules` 
add  `iscard` tinyint(3) unsigned NOT NULL;

-- 0612
ALTER TABLE `ims_core_paylog` add `createtime` varchar(64) NOT NULL;
ALTER TABLE `ims_core_paylog` add `eso_tag` varchar(2000) NOT NULL DEFAULT '';

CREATE TABLE IF NOT EXISTS `ims_mc_member_address` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(50) unsigned NOT NULL,
  `username` varchar(20) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `zipcode` varchar(6) NOT NULL,
  `province` varchar(32) NOT NULL,
  `city` varchar(32) NOT NULL,
  `district` varchar(32) NOT NULL,
  `address` varchar(512) NOT NULL,
  `isdefault` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_uinacid` (`uniacid`),
  KEY `idx_uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 0805
ALTER TABLE `ims_core_paylog` add `uniontid` varchar(64) NOT NULL;
ALTER TABLE `ims_qrcode` add `scene_str` varchar(64) NOT NULL;
ALTER TABLE `ims_qrcode_stat` add  `scene_str` varchar(64) NOT NULL;

-- 0831
ALTER TABLE `ims_account_wechats` ADD   `topad` varchar(225) NOT NULL;
ALTER TABLE `ims_account_wechats` ADD   `footad` varchar(225) NOT NULL;
ALTER TABLE  `ims_uni_account` ADD  `default_acid` int(10) unsigned NOT NULL;
ALTER TABLE `ims_account_wechats` ADD   `auth_refresh_token` varchar(255) NOT NULL;
ALTER TABLE  `ims_users` add `ucuserid` int(10) unsigned NOT NULL;

-- 0919
ALTER TABLE `ims_users` ADD `viptime` varchar(13) NOT NULL;

ALTER TABLE `ims_users_group` ADD `maxsize` numeric(8,2) NOT NULL;

ALTER TABLE `ims_users` ADD   `starttime` int(10) unsigned NOT NULL;
ALTER TABLE `ims_users` ADD  `endtime` int(10) unsigned NOT NULL;
ALTER TABLE  `ims_uni_group` ADD  `uniacid` int(10) unsigned NOT NULL;

ALTER TABLE  `ims_core_paylog` ADD  INDEX `uniontid` (`uniontid`);
ALTER TABLE  `ims_core_queue`  ADD  INDEX `module` (`module`);
ALTER TABLE   `ims_core_wechats_attachment` ADD  INDEX `acid` (`acid`);
ALTER TABLE  `ims_modules`  ADD  `permissions` varchar(5000) NOT NULL;
ALTER TABLE  `ims_modules_bindings` ADD    `url` varchar(100) NOT NULL;
ALTER TABLE  `ims_modules_bindings` ADD   `icon` varchar(50) NOT NULL;
ALTER TABLE  `ims_modules_bindings` ADD   `displayorder` tinyint(255) unsigned NOT NULL;
ALTER TABLE `ims_modules_bindings` MODIFY `module` varchar(100) NOT NULL;
ALTER TABLE  `ims_site_slide`  ADD  INDEX `multiid` (`multiid`);
ALTER TABLE  `ims_uni_account_users` ADD  INDEX `uniacid` (`uniacid`);
ALTER TABLE  `ims_uni_group` ADD  INDEX `uniacid` (`uniacid`);
ALTER TABLE  `ims_users_group` ADD `timelimit` int(10) unsigned NOT NULL;
ALTER TABLE  `ims_users_permission` ADD   `type` varchar(30) NOT NULL;
ALTER TABLE  `ims_users_permission` ADD  `permission` varchar(10000) NOT NULL;
ALTER TABLE  `ims_users_profile` ADD   `workerid` varchar(64) NOT NULL;

CREATE TABLE IF NOT EXISTS `ims_article_news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cateid` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` mediumtext NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `source` varchar(255) NOT NULL,
  `author` varchar(50) NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL,
  `is_display` tinyint(3) unsigned NOT NULL,
  `is_show_home` tinyint(3) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `click` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `cateid` (`cateid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `ims_article_notice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cateid` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` mediumtext NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL,
  `is_display` tinyint(3) unsigned NOT NULL,
  `is_show_home` tinyint(3) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `click` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `cateid` (`cateid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `ims_article_unread_notice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `notice_id` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `is_new` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `notice_id` (`notice_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `ims_article_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(30) NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL,
  `type` varchar(15) NOT NULL,
  `num` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `ims_core_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(20) NOT NULL,
  `name` varchar(20) NOT NULL,
  `url` varchar(60) NOT NULL,
  `append_title` varchar(30) NOT NULL,
  `append_url` varchar(255) NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL,
  `type` varchar(15) NOT NULL,
  `is_display` tinyint(3) unsigned NOT NULL,
  `is_system` tinyint(3) unsigned NOT NULL,
  `permission_name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_site_page` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `multiid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `params` longtext NOT NULL,
  `html` longtext NOT NULL,
  `type` tinyint(1) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `multiid` (`multiid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

-- 1020
ALTER TABLE  `ims_activity_coupon_password` ADD   `mobile` varchar(20) NOT NULL;
ALTER TABLE  `ims_activity_coupon_password` ADD   `openid` varchar(50) NOT NULL;
ALTER TABLE  `ims_activity_coupon_password` ADD   `nickname` varchar(30) NOT NULL;
ALTER TABLE  `ims_activity_exchange` CHANGE  `description`   `description` text NOT NULL;

CREATE TABLE IF NOT EXISTS `ims_activity_stores` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `business_name` varchar(50) NOT NULL,
  `branch_name` varchar(50) NOT NULL,
  `category` varchar(255) NOT NULL,
  `province` varchar(15) NOT NULL,
  `city` varchar(15) NOT NULL,
  `district` varchar(15) NOT NULL,
  `address` varchar(50) NOT NULL,
  `longitude` varchar(15) NOT NULL,
  `latitude` varchar(15) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `photo_list` varchar(10000) NOT NULL,
  `avg_price` int(10) unsigned NOT NULL,
  `recommend` varchar(255) NOT NULL,
  `special` varchar(255) NOT NULL,
  `introduction` varchar(255) NOT NULL,
  `open_time` varchar(50) NOT NULL,
  `location_id` int(10) unsigned NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `source` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1为系统门店，2为微信门店',
  `message` varchar(500) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `location_id` (`location_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;

ALTER TABLE  `ims_coupon` ADD    `promotion_url_name` varchar(10) NOT NULL;
ALTER TABLE  `ims_coupon` ADD    `promotion_url` varchar(100) NOT NULL;
ALTER TABLE  `ims_coupon` ADD  	 `promotion_url_sub_title` varchar(10) NOT NULL;
ALTER TABLE  `ims_coupon_location` ADD    `sid` int(10) unsigned NOT NULL;
ALTER TABLE  `ims_coupon_location` CHANGE   `introduction`   `introduction` varchar(255) NOT NULL;

ALTER TABLE  `ims_mc_card_members` ADD    `openid` varchar(50) NOT NULL;
ALTER TABLE  `ims_mc_card_members` ADD   `nums` int(10) unsigned NOT NULL;
ALTER TABLE  `ims_mc_card_members` ADD   `endtime` int(10) unsigned NOT NULL;

CREATE TABLE IF NOT EXISTS `ims_mc_card_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `type` varchar(15) NOT NULL,
  `model` tinyint(3) unsigned NOT NULL,
  `fee` decimal(10,2) unsigned NOT NULL,
  `tag` varchar(10) NOT NULL,
  `note` varchar(255) NOT NULL,
  `remark` varchar(200) NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `uid` (`uid`),
  KEY `addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE  `ims_mc_credits_recharge` ADD   `openid` varchar(50) NOT NULL;
ALTER TABLE  `ims_mc_credits_recharge` ADD     `type` varchar(15) NOT NULL;
ALTER TABLE  `ims_mc_credits_recharge` ADD   `tag` int(10) unsigned NOT NULL;
ALTER TABLE  `ims_mc_credits_record` ADD    `module` varchar(30) NOT NULL;
ALTER TABLE  `ims_mc_credits_record` ADD    `clerk_id` int(10) unsigned NOT NULL;
ALTER TABLE  `ims_mc_groups` ADD    `credit` int(10) unsigned NOT NULL;
ALTER TABLE  `ims_news_reply` CHANGE   `content`  `content` mediumtext NOT NULL;
ALTER TABLE  `ims_uni_settings` ADD     `recharge` varchar(500) NOT NULL;
ALTER TABLE  `ims_uni_settings` ADD    `tplnotice` varchar(1000) NOT NULL;
ALTER TABLE  `ims_uni_settings` ADD    `grouplevel` tinyint(3) unsigned NOT NULL;
ALTER TABLE `ims_uni_settings` MODIFY `payment` text NOT NULL;
-- 1027

ALTER TABLE  `ims_activity_coupon_password`  ADD  `storeid` int(10) unsigned NOT NULL;
ALTER TABLE  `ims_activity_coupon_password`  ADD  `uid` int(11) unsigned NOT NULL DEFAULT '0';
ALTER TABLE  `ims_mc_credits_record`  ADD  `store_id` int(10) unsigned NOT NULL;

CREATE TABLE IF NOT EXISTS `ims_mc_card_care` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(30) NOT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  `groupid` int(10) unsigned NOT NULL,
  `credit1` int(10) unsigned NOT NULL,
  `credit2` int(10) unsigned NOT NULL,
  `couponid` int(10) unsigned NOT NULL,
  `granttime` int(10) unsigned NOT NULL,
  `days` int(10) unsigned NOT NULL,
  `time` tinyint(3) unsigned NOT NULL,
  `show_in_card` tinyint(3) unsigned NOT NULL,
  `content` varchar(1000) NOT NULL,
  `sms_notice` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_mc_card_credit_set` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `sign` varchar(1000) NOT NULL,
  `share` varchar(500) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;


CREATE TABLE IF NOT EXISTS `ims_mc_card_notices` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  `title` varchar(30) NOT NULL,
  `thumb` varchar(100) NOT NULL,
  `groupid` int(10) unsigned NOT NULL,
  `content` text NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;


CREATE TABLE IF NOT EXISTS `ims_mc_card_notices_unread` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `notice_id` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `is_new` tinyint(3) unsigned NOT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `uid` (`uid`),
  KEY `notice_id` (`notice_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `ims_mc_card_recommend` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(30) NOT NULL,
  `thumb` varchar(100) NOT NULL,
  `url` varchar(100) NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_mc_card_sign_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `credit` int(10) unsigned NOT NULL,
  `is_grant` tinyint(3) unsigned NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;
CREATE TABLE IF NOT EXISTS `ims_mc_cash_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `clerk_id` int(10) unsigned NOT NULL,
  `store_id` int(10) unsigned NOT NULL,
  `fee` decimal(10,2) unsigned NOT NULL,
  `final_fee` decimal(10,2) unsigned NOT NULL,
  `credit1` int(10) unsigned NOT NULL,
  `credit1_fee` decimal(10,2) unsigned NOT NULL,
  `credit2` decimal(10,2) unsigned NOT NULL,
  `cash` decimal(10,2) unsigned NOT NULL,
  `return_cash` decimal(10,2) unsigned NOT NULL,
  `final_cash` decimal(10,2) unsigned NOT NULL,
  `remark` varchar(255) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_uni_account_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `groupid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE  `ims_news_reply`  ADD    `parent_id` int(10) NOT NULL;
ALTER TABLE  `ims_uni_account_group`  CHANGE   `groupid`  `groupid` int(10) NOT NULL;

ALTER TABLE  `ims_mc_mapping_fans`  ADD  `unionid` varchar(64) NOT NULL;

CREATE TABLE IF NOT EXISTS `ims_article_case` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cateid` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` mediumtext NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `source` varchar(255) NOT NULL,
  `author` varchar(50) NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL,
  `is_display` tinyint(3) unsigned NOT NULL,
  `is_show_home` tinyint(3) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `click` int(10) unsigned NOT NULL,
  `weixinh` varchar(50) NOT NULL,
  `weixintag` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `cateid` (`cateid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `ims_article_catecase` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(30) NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL,
  `type` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

ALTER TABLE  `ims_mc_mass_record`  ADD    `finalsendtime` int(10) unsigned NOT NULL;
ALTER TABLE  `ims_uni_group`  CHANGE `modules` `modules` varchar(15000) NOT NULL;

CREATE TABLE IF NOT EXISTS `ims_users_failed_login` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(15) NOT NULL,
  `username` varchar(32) NOT NULL,
  `count` tinyint(1) unsigned NOT NULL,
  `lastupdate` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ip_username` (`ip`,`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wxcard_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `title` varchar(30) NOT NULL,
  `card_id` varchar(50) NOT NULL,
  `cid` int(10) unsigned NOT NULL,
  `brand_name` varchar(30) NOT NULL,
  `logo_url` varchar(255) NOT NULL,
  `success` varchar(255) NOT NULL,
  `error` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE  `ims_menu_event` ADD `openid` varchar(128) NOT NULL;
ALTER TABLE  `ims_menu_event` ADD `createtime` int(10) unsigned NOT NULL;

CREATE TABLE IF NOT EXISTS `ims_article_link` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cateid` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  `siteurl` varchar(100) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL,
  `is_display` tinyint(3) unsigned NOT NULL,
  `is_show_home` tinyint(3) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `click` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `cateid` (`cateid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_users_credits_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `uniacid` int(11) NOT NULL,
  `credittype` varchar(10) NOT NULL DEFAULT '',
  `num` decimal(10,2) NOT NULL,
  `operator` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `remark` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_users_packages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid`  int NULL COMMENT '公众号ID',
  `record_id` int(11) DEFAULT NULL COMMENT '消费记录ID',
  `uid` int(11) DEFAULT NULL COMMENT '用户ID',
  `package` int(11) DEFAULT NULL COMMENT '套餐ID',
  `buy_time` int(11) DEFAULT 0 NULL COMMENT '购买时间',
  `expiration_time` int(11) DEFAULT 0 NULL COMMENT '到期时间',
  `status`  int(1) NULL DEFAULT 0 COMMENT '状态 0 - 待付款 1-已付款',
  PRIMARY KEY (`id`),
  KEY `uid_package` (`uid`,`package`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_uni_payorder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderid` varchar(200) DEFAULT NULL,
  `status` int(1) DEFAULT '0' COMMENT '0-未付款 1-已付款',
  `money` decimal(10,2) DEFAULT NULL,
  `pay_time` int(10) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL COMMENT '用户ID',
  `order_time` int(10) DEFAULT NULL,
  `credittype` varchar(20) DEFAULT NULL,
  `pay_type` int(1) DEFAULT 0 NULL,
  `order_no` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orderid` (`orderid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE  `ims_users` ADD  `credit1` decimal(11,2) NULL DEFAULT 0 COMMENT '用户积分';
ALTER TABLE  `ims_users` ADD  `credit2` decimal(11,2) NULL DEFAULT 0 COMMENT '交易币';
ALTER TABLE  `ims_uni_group` ADD  `price` decimal(11,2) NULL DEFAULT 0 COMMENT '套餐价格';
ALTER TABLE  `ims_uni_group` ADD  `hide` int(1) NULL DEFAULT 0 COMMENT '是否隐藏 0-否 1-是';
ALTER TABLE  `ims_users_group` ADD  `discount` decimal(11,2) DEFAULT NULL COMMENT '打折';
ALTER TABLE `ims_users_group` ADD `owner_uid` int(10) NOT NULL;

CREATE TABLE IF NOT EXISTS  `ims_core_sendsms_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `content` varchar(255) NOT NULL,
  `result` varchar(255) NOT NULL,
  `createtime` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_stat_fans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `new` int(10) unsigned NOT NULL,
  `cancel` int(10) unsigned NOT NULL,
  `cumulate` int(10) NOT NULL,
  `date` varchar(8) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_uni_account_menus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `menuid` int(10) unsigned NOT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  `title` varchar(30) NOT NULL,
  `sex` tinyint(3) unsigned NOT NULL,
  `group_id` int(10) NOT NULL,
  `client_platform_type` tinyint(3) unsigned NOT NULL,
  `area` varchar(50) NOT NULL,
  `data` text NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `menuid` (`menuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wechat_news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned DEFAULT NULL,
  `attach_id` int(10) unsigned NOT NULL,
  `thumb_media_id` varchar(60) NOT NULL,
  `title` varchar(50) NOT NULL,
  `author` varchar(30) NOT NULL,
  `digest` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `content_source_url` varchar(200) NOT NULL,
  `show_cover_pic` tinyint(3) unsigned NOT NULL,
  `url` varchar(200) NOT NULL,
  `thumb_url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `attach_id` (`attach_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- 0218
CREATE TABLE IF NOT EXISTS `ims_activity_clerks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `storeid` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `openid` varchar(50) NOT NULL,
  `nickname` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `password` (`password`),
  KEY `openid` (`openid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
ALTER TABLE `ims_activity_coupon_record`  ADD    `store_id` int(10) unsigned NOT NULL;
ALTER TABLE `ims_activity_coupon_record`  ADD    `clerk_type` tinyint(3) unsigned NOT NULL;
ALTER TABLE `ims_core_menu`  CHANGE   `pid` `pid` int(10) unsigned NOT NULL DEFAULT '0';

ALTER TABLE `ims_mc_cash_record`  ADD `clerk_type` tinyint(3) unsigned NOT NULL;
ALTER TABLE `ims_mc_credits_record`  ADD   `clerk_type` tinyint(3) unsigned NOT NULL;
ALTER TABLE `ims_uni_account`  ADD    `rank` tinyint(1) DEFAULT NULL;
ALTER TABLE `ims_users`  ADD   `type` tinyint(3) unsigned NOT NULL;
ALTER TABLE `ims_users` ADD `owner_uid` int(10) NOT NULL;
ALTER TABLE `ims_users` ADD `founder_groupid` tinyint(4) NOT NULL;
CREATE TABLE IF NOT EXISTS `ims_article_about` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cateid` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` mediumtext NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `source` varchar(255) NOT NULL,
  `author` varchar(50) NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL,
  `is_display` tinyint(3) unsigned NOT NULL,
  `is_show_home` tinyint(3) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `click` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `cateid` (`cateid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `ims_article_agent` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cateid` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` mediumtext NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `source` varchar(255) NOT NULL,
  `author` varchar(50) NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL,
  `is_display` tinyint(3) unsigned NOT NULL,
  `is_show_home` tinyint(3) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `click` int(10) unsigned NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `mobilephone` varchar(20) NOT NULL,
  `address` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `cateid` (`cateid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;
CREATE TABLE IF NOT EXISTS `ims_article_wenda` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cateid` int(10) unsigned NOT NULL,
  `modid` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` mediumtext NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `source` varchar(255) NOT NULL,
  `author` varchar(50) NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL,
  `is_display` tinyint(3) unsigned NOT NULL,
  `is_show_home` tinyint(3) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `click` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `cateid` (`cateid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `ims_agent` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `intro` varchar(800) NOT NULL DEFAULT '',
  `mp` varchar(11) NOT NULL DEFAULT '',
  `usercount` int(10) NOT NULL DEFAULT '0',
  `wxusercount` int(10) NOT NULL DEFAULT '0',
  `sitename` varchar(50) NOT NULL DEFAULT '',
  `sitelogo` varchar(200) NOT NULL DEFAULT '',
  `qrcode` varchar(100) NOT NULL DEFAULT '',
  `sitetitle` varchar(60) NOT NULL DEFAULT '',
  `siteurl` varchar(100) NOT NULL DEFAULT '',
  `robotname` varchar(40) NOT NULL DEFAULT '',
  `connectouttip` varchar(50) NOT NULL DEFAULT '',
  `needcheckuser` tinyint(1) NOT NULL DEFAULT '0',
  `regneedmp` tinyint(1) NOT NULL DEFAULT '1',
  `reggid` int(10) NOT NULL DEFAULT '0',
  `regvaliddays` mediumint(4) NOT NULL DEFAULT '30',
  `qq` varchar(12) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `metades` varchar(300) NOT NULL DEFAULT '',
  `metakeywords` varchar(200) NOT NULL DEFAULT '',
  `statisticcode` varchar(300) NOT NULL DEFAULT '',
  `copyright` varchar(100) NOT NULL DEFAULT '',
  `alipayaccount` varchar(50) NOT NULL DEFAULT '',
  `alipaypid` varchar(100) NOT NULL DEFAULT '',
  `alipaykey` varchar(100) NOT NULL DEFAULT '',
  `password` varchar(40) NOT NULL DEFAULT '',
  `salt` varchar(6) NOT NULL DEFAULT '',
  `money` int(10) NOT NULL DEFAULT '0',
  `moneybalance` int(10) NOT NULL DEFAULT '0',
  `time` int(10) NOT NULL DEFAULT '0',
  `endtime` varchar(15) NOT NULL,
  `lastloginip` varchar(26) NOT NULL DEFAULT '',
  `lastlogintime` int(11) NOT NULL DEFAULT '0',
  `wxacountprice` mediumint(4) NOT NULL DEFAULT '0',
  `monthprice` mediumint(4) NOT NULL DEFAULT '0',
  `appid` varchar(50) NOT NULL DEFAULT '',
  `appsecret` varchar(100) NOT NULL DEFAULT '',
  `title` varchar(40) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `level` int(11) NOT NULL DEFAULT '0',
  `isnav` int(11) NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_agent_expenserecords` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `agentid` int(10) NOT NULL DEFAULT '0',
  `amount` int(10) NOT NULL DEFAULT '0',
  `orderid` varchar(60) NOT NULL DEFAULT '',
  `des` varchar(200) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `times` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `num` int(10) NOT NULL,
  `group` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `agentid` (`agentid`,`times`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
alter table `ims_users` add `agentid` int(10);
CREATE TABLE IF NOT EXISTS `ims_activity_clerk_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `displayorder` int(4) NOT NULL,
  `pid` int(6) NOT NULL,
  `group_name` varchar(20) NOT NULL,
  `title` varchar(20) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `url` varchar(60) NOT NULL,
  `type` varchar(20) NOT NULL,
  `permission` varchar(50) NOT NULL,
  `system` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
ALTER TABLE  `ims_coupon` ADD    `is_selfconsume` tinyint(3) unsigned NOT NULL;

CREATE TABLE IF NOT EXISTS  `ims_fournet_pcmulti` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(30) NOT NULL,
  `styleid` int(10) unsigned NOT NULL,
  `site_info` text NOT NULL,
  `quickmenu` varchar(2000) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `bindhost` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `bindhost` (`bindhost`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS  `ims_fournet_pcnav` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `multiid` int(10) unsigned NOT NULL,
  `section` tinyint(4) NOT NULL,
  `module` varchar(50) NOT NULL,
  `displayorder` smallint(5) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `position` tinyint(4) NOT NULL,
  `url` varchar(255) NOT NULL,
  `icon` varchar(500) NOT NULL,
  `css` varchar(1000) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `categoryid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `multiid` (`multiid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS  `ims_fournet_pcpage` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `multiid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `params` longtext NOT NULL,
  `html` longtext NOT NULL,
  `type` tinyint(1) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `multiid` (`multiid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS  `ims_fournet_pcslide` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `displayorder` tinyint(4) NOT NULL,
  `multiid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_fournet_pcstyles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `templateid` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS  `ims_fournet_pcstyles_vars` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `templateid` int(10) unsigned NOT NULL,
  `styleid` int(10) unsigned NOT NULL,
  `variable` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `description` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_fournet_pctemplates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `title` varchar(30) NOT NULL,
  `version` varchar(64) NOT NULL,
  `description` varchar(500) NOT NULL,
  `author` varchar(50) NOT NULL,
  `url` varchar(255) NOT NULL,
  `type` varchar(20) NOT NULL,
  `sections` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS  `ims_fournet_pcmulti` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(30) NOT NULL,
  `styleid` int(10) unsigned NOT NULL,
  `site_info` text NOT NULL,
  `quickmenu` varchar(2000) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `bindhost` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `bindhost` (`bindhost`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS  `ims_fournet_pcnav` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `multiid` int(10) unsigned NOT NULL,
  `section` tinyint(4) NOT NULL,
  `module` varchar(50) NOT NULL,
  `displayorder` smallint(5) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `position` tinyint(4) NOT NULL,
  `url` varchar(255) NOT NULL,
  `icon` varchar(500) NOT NULL,
  `css` varchar(1000) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `categoryid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `multiid` (`multiid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS  `ims_fournet_pcpage` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `multiid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `params` longtext NOT NULL,
  `html` longtext NOT NULL,
  `type` tinyint(1) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `multiid` (`multiid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS  `ims_fournet_pcslide` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `displayorder` tinyint(4) NOT NULL,
  `multiid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_fournet_pcstyles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `templateid` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS  `ims_fournet_pcstyles_vars` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `templateid` int(10) unsigned NOT NULL,
  `styleid` int(10) unsigned NOT NULL,
  `variable` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `description` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_fournet_pctemplates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `title` varchar(30) NOT NULL,
  `version` varchar(64) NOT NULL,
  `description` varchar(500) NOT NULL,
  `author` varchar(50) NOT NULL,
  `url` varchar(255) NOT NULL,
  `type` varchar(20) NOT NULL,
  `sections` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS  `ims_appdabao` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` varchar(200) NOT NULL,
  `domain` varchar(200) NOT NULL,
  `app_id` int(10) unsigned NOT NULL,
  `app_key` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_appdabao_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` varchar(200) NOT NULL,
  `web_app_id` int(10) unsigned NOT NULL DEFAULT '0',
  `status` int(10) unsigned NOT NULL DEFAULT '0',
  `oktime` int(10) unsigned DEFAULT NULL,
  `err_result` varchar(200) DEFAULT NULL,
  `addtime` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `intro` text,
  `weburl` varchar(500) NOT NULL,
  `apptype` int(11) NOT NULL DEFAULT '0',
  `icopic` varchar(200) NOT NULL,
  `hellopic` varchar(200) NOT NULL,
  `hidetop` int(11) NOT NULL DEFAULT '0',
  `screen` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `weid` (`weid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `tp_yundabao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` varchar(100) NOT NULL,
  `AppId` int(11) DEFAULT NULL,
  `endtime` int(11) DEFAULT NULL,
  `AppName` varchar(100) DEFAULT NULL,
  `AppNote` text,
  `HideTop` int(11) DEFAULT NULL,
  `IconType` int(11) DEFAULT NULL,
  `IconName` varchar(200) DEFAULT NULL,
  `IconName_url` varchar(200) DEFAULT NULL,
  `LogoName` varchar(100) DEFAULT NULL,
  `LogoName_url` varchar(200) DEFAULT NULL,
  `BgColor` int(11) DEFAULT NULL,
  `SplashType` int(11) DEFAULT NULL,
  `SplashName` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `tp_yundabao_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` varchar(100) NOT NULL,
  `username` varchar(255) NOT NULL,
  `endtime` int(11) NOT NULL,
  `AccessToken` varchar(200) DEFAULT NULL,
  `UserId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_article_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cateid` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` mediumtext NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `price` decimal(10,2) DEFAULT '0.00',
  `author` varchar(50) NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL,
  `is_display` tinyint(3) unsigned NOT NULL,
  `is_show_home` tinyint(3) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `click` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `cateid` (`cateid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;
CREATE TABLE IF NOT EXISTS `ims_paycenter_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `pid` int(10) unsigned NOT NULL,
  `clerk_id` int(10) unsigned NOT NULL,
  `store_id` int(10) unsigned NOT NULL,
  `clerk_type` tinyint(3) unsigned NOT NULL,
  `uniontid` varchar(40) NOT NULL,
  `transaction_id` varchar(40) NOT NULL,
  `type` varchar(10) NOT NULL,
  `trade_type` varchar(10) NOT NULL,
  `body` varchar(255) NOT NULL,
  `fee` varchar(15) NOT NULL,
  `final_fee` decimal(10,2) unsigned NOT NULL,
  `credit1` int(10) unsigned NOT NULL,
  `credit1_fee` decimal(10,2) unsigned NOT NULL,
  `credit2` decimal(10,2) unsigned NOT NULL,
  `cash` decimal(10,2) unsigned NOT NULL,
  `remark` varchar(255) NOT NULL,
  `auth_code` varchar(30) NOT NULL,
  `openid` varchar(50) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `follow` tinyint(3) unsigned NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `credit_status` tinyint(3) unsigned NOT NULL,
  `paytime` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;

ALTER TABLE `ims_uni_account_users`  ADD `rank` tinyint(3) unsigned NOT NULL;
ALTER TABLE `ims_mc_mapping_fans` ADD INDEX `uid` (`uid`);
ALTER TABLE `ims_qrcode` ADD INDEX `ticket` (`ticket`);
INSERT INTO `ims_activity_clerk_menu` (`id`, `uniacid`, `displayorder`, `pid`, `group_name`, `title`, `icon`, `url`, `type`, `permission`, `system`) VALUES
(1, 0, 0, 0, 'mc', '快捷交易', '', '', '', 'mc_manage', 1),
(2, 0, 0, 1, '', '会员积分修改', 'fa fa-money', 'credit1', 'modal', 'mc_credit1', 1),
(3, 0, 0, 1, '', '会员余额修改', 'fa fa-cny', 'credit2', 'modal', 'mc_credit2', 1),
(4, 0, 0, 1, '', '消费', 'fa fa-usd', 'consume', 'modal', 'mc_consume', 1),
(5, 0, 0, 1, '', '发放会员卡', 'fa fa-credit-card', 'card', 'modal', 'mc_card', 1),
(6, 0, 0, 0, 'stat', '数据统计', '', '', '', 'stat_manage', 1),
(7, 0, 0, 6, '', '会员积分统计', 'fa fa-bar-chart', './index.php?c=stat&a=credit1', 'url', 'stat_credit1', 1),
(8, 0, 0, 6, '', '会员余额统计', 'fa fa-bar-chart', './index.php?c=stat&a=credit2', 'url', 'stat_credit2', 1),
(9, 0, 0, 6, '', '现金消费统计', 'fa fa-bar-chart', './index.php?c=stat&a=cash', 'url', 'stat_cash', 1),
(10, 0, 0, 6, '', '会员卡领卡统计', 'fa fa-bar-chart', './index.php?c=stat&a=card', 'url', 'stat_card', 1),
(11, 0, 0, 0, 'activity', '卡券核销', '', '', '', 'activity_card_manage', 1),
(19, 0, 0, 18, '', '微信刷卡收款', 'fa fa-money', './index.php?c=paycenter&a=wxmicro&do=pay', 'url', 'paycenter_wxmicro_pay', 1),
(18, 0, 0, 0, 'paycenter', '收银台', '', '', '', 'paycenter_manage', 1),
(16, 0, 0, 6, '', '收银台收款统计', 'fa fa-bar-chart', './index.php?c=stat&a=paycenter', 'url', 'stat_paycenter', 1),
(17, 0, 0, 11, '', '卡券核销', 'fa fa-money', 'cardconsume', 'modal', 'coupon_consume', 1);

ALTER TABLE `ims_profile_fields`  ADD  `field_length` int(10) NOT NULL;
ALTER TABLE `ims_uni_settings`  ADD  `mcplugin` varchar(500) NOT NULL;
ALTER TABLE `ims_uni_settings`  CHANGE  `default_message` `default_message` varchar(2000) NOT NULL;
CREATE TABLE IF NOT EXISTS `ims_buymod_mbuy` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `weid` int(10) unsigned NOT NULL,
  `module` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `status` int(2) NOT NULL DEFAULT '0',
  `price` decimal(10,2) unsigned NOT NULL,
  `starttime` int(10) unsigned NOT NULL,
  `endtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_buymod_members` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `weid` int(10) unsigned NOT NULL,
  `credit` decimal(10,2) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_buymod_modules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `mid` int(10) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `module` varchar(50) NOT NULL,
  `price` decimal(10,2) unsigned NOT NULL,
  `outLink` varchar(500) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_buymod_payrecords` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `weid` int(10) unsigned NOT NULL,
  `orderid` varchar(200) NOT NULL,
  `description` varchar(100) NOT NULL DEFAULT '',
  `credit` decimal(10,2) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `pay_type` int(2) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `order_no` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_buymod_payset` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `weid` int(10) unsigned NOT NULL,
  `alipayaccount` varchar(100) NOT NULL DEFAULT '',
  `alipaypartner` varchar(100) NOT NULL DEFAULT '',
  `alipaykey` varchar(100) NOT NULL DEFAULT '',
  `yunaccount` varchar(100) NOT NULL,
  `yunpid` varchar(100) NOT NULL,
  `yunkey` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_buymod_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `weid` int(10) unsigned NOT NULL,
  `module` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `price` decimal(10,2) unsigned NOT NULL,
  `starttime` int(10) unsigned NOT NULL,
  `endtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `ims_account`  ADD  `isdeleted` tinyint(3) unsigned NOT NULL;
ALTER TABLE `ims_activity_coupon_record`  ADD `code` varchar(50) NOT NULL;
ALTER TABLE `ims_uni_account_menus`  ADD `isdeleted` tinyint(3) unsigned NOT NULL;
ALTER TABLE `ims_mc_credits_recharge`  ADD  `backtype` tinyint(3) unsigned NOT NULL;

CREATE TABLE IF NOT EXISTS `ims_wxauth_app` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `m_id` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `is_delete` tinyint(1) unsigned NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  `token` char(32) NOT NULL,
  `encodingaeskey` char(43) NOT NULL,
  `url` varchar(200) NOT NULL,
  `sort` int(11) unsigned NOT NULL DEFAULT '50',
  `desc` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wxauth_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `w_id` int(10) unsigned NOT NULL,
  `name` varchar(20) NOT NULL,
  `appid` char(18) NOT NULL,
  `appsecret` char(32) NOT NULL,
  `token` char(32) NOT NULL,
  `encodingaeskey` char(43) NOT NULL,
  `is_yz` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1',
  `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL,
  `desc` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wxauth_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `m_id` int(10) unsigned NOT NULL,
  `a_id` int(10) unsigned NOT NULL,
  `from_data` varchar(10000) NOT NULL,
  `send_data` varchar(10000) NOT NULL,
  `time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_domain` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `domain` varchar(40) NOT NULL DEFAULT '',
  `title` varchar(50) NOT NULL DEFAULT '',
  `entry` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `module` varchar(20) NOT NULL DEFAULT '',
  `eid` int(11) NOT NULL,
  `ext` text,
  `type` tinyint(3) NOT NULL DEFAULT '0',
  `all` tinyint(3) NOT NULL DEFAULT '0',
  `redirect` tinyint(3) NOT NULL DEFAULT '0',
  `status` tinyint(3) NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_domain` (`domain`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_domain_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(40) NOT NULL DEFAULT '',
  `host` varchar(80) NOT NULL DEFAULT '',
  `groupid` int(11) NOT NULL,
  `isaccount` tinyint(3) NOT NULL DEFAULT '0',
  `limit` int(10) unsigned NOT NULL DEFAULT '0',
  `ext` text,
  `type` tinyint(3) NOT NULL DEFAULT '0',
  `domain` tinyint(3) NOT NULL DEFAULT '0',
  `right` tinyint(3) NOT NULL DEFAULT '0',
  `status` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_groupid` (`groupid`),
  KEY `idx_isaccount` (`isaccount`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

ALTER TABLE `ims_core_paylog`  CHANGE `tid` `tid` varchar(128) NOT NULL;
ALTER TABLE `ims_uni_settings` ADD `msg` varchar(2000) NOT NULL COMMENT '短信配置参数' ;
ALTER TABLE `ims_uni_settings` ADD `print` varchar(2000) NOT NULL COMMENT '打印机配置参数' ;
ALTER TABLE `ims_mc_credits_recharge` CHANGE `tag` `tag` varchar(10) NOT NULL;
ALTER TABLE `ims_modules` ADD `price` decimal(10,2) unsigned DEFAULT '0';
ALTER TABLE `ims_modules` ADD `isbuy` int(10) unsigned NOT NULL;
DROP TABLE IF EXISTS `ims_core_cron`;
CREATE TABLE `ims_core_cron` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cloudid` int(10) unsigned NOT NULL,
  `module` varchar(50) NOT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `filename` varchar(50) NOT NULL,
  `lastruntime` int(10) unsigned NOT NULL,
  `nextruntime` int(10) unsigned NOT NULL,
  `weekday` tinyint(3) NOT NULL,
  `day` tinyint(3) NOT NULL,
  `hour` tinyint(3) NOT NULL,
  `minute` varchar(255) NOT NULL,
  `extra` varchar(500) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `createtime` (`createtime`),
  KEY `nextruntime` (`nextruntime`),
  KEY `uniacid` (`uniacid`),
  KEY `cloudid` (`cloudid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `ims_core_cron_record`;
CREATE TABLE `ims_core_cron_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `module` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `tid` int(10) unsigned NOT NULL,
  `note` varchar(500) NOT NULL,
  `tag` varchar(5000) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `tid` (`tid`),
  KEY `module` (`module`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `ims_core_paylog`;
CREATE TABLE `ims_core_paylog` (
  `plid` bigint(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(20) NOT NULL,
  `uniacid` int(11) NOT NULL,
  `acid` int(10) NOT NULL,
  `openid` varchar(40) NOT NULL,
  `uniontid` varchar(64) NOT NULL,
  `tid` varchar(128) NOT NULL,
  `fee` decimal(10,2) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `module` varchar(50) NOT NULL,
  `tag` varchar(2000) NOT NULL,
  `is_usecard` tinyint(3) unsigned NOT NULL,
  `card_type` tinyint(3) unsigned NOT NULL,
  `card_id` varchar(50) NOT NULL,
  `card_fee` decimal(10,2) unsigned NOT NULL,
  `encrypt_code` varchar(100) NOT NULL,
  PRIMARY KEY (`plid`),
  KEY `idx_openid` (`openid`),
  KEY `idx_tid` (`tid`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `uniontid` (`uniontid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `ims_core_queue`;
CREATE TABLE `ims_core_queue` (
  `qid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `acid` int(10) unsigned NOT NULL,
  `message` varchar(2000) NOT NULL,
  `params` varchar(1000) NOT NULL,
  `keyword` varchar(1000) NOT NULL,
  `response` varchar(2000) NOT NULL,
  `module` varchar(50) NOT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY (`qid`),
  KEY `uniacid` (`uniacid`,`acid`),
  KEY `module` (`module`),
  KEY `dateline` (`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_core_refundlog`;
CREATE TABLE `ims_core_refundlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `refund_uniontid` varchar(64) NOT NULL,
  `reason` varchar(80) NOT NULL,
  `uniontid` varchar(64) NOT NULL,
  `fee` decimal(10,2) NOT NULL,
  `status` int(2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `refund_uniontid` (`refund_uniontid`),
  KEY `uniontid` (`uniontid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_mc_credits_recharge`;
CREATE TABLE `ims_mc_credits_recharge` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `tid` varchar(64) NOT NULL,
  `transid` varchar(30) NOT NULL,
  `fee` varchar(10) NOT NULL,
  `type` varchar(15) NOT NULL,
  `tag` varchar(10) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `backtype` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_uniacid_uid` (`uniacid`,`uid`),
  KEY `idx_tid` (`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_mc_mass_record`;
CREATE TABLE `ims_mc_mass_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `acid` int(10) unsigned NOT NULL,
  `groupname` varchar(50) NOT NULL,
  `fansnum` int(10) unsigned NOT NULL,
  `msgtype` varchar(10) NOT NULL,
  `content` varchar(10000) NOT NULL,
  `group` int(10) NOT NULL,
  `attach_id` int(10) unsigned NOT NULL,
  `media_id` varchar(100) NOT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `cron_id` int(10) unsigned NOT NULL,
  `sendtime` int(10) unsigned NOT NULL,
  `finalsendtime` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`acid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `ims_mc_members`;
CREATE TABLE `ims_mc_members` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(32) NOT NULL,
  `salt` varchar(8) NOT NULL,
  `groupid` int(11) NOT NULL,
  `credit1` decimal(10,2) unsigned NOT NULL,
  `credit2` decimal(10,2) unsigned NOT NULL,
  `credit3` decimal(10,2) unsigned NOT NULL,
  `credit4` decimal(10,2) unsigned NOT NULL,
  `credit5` decimal(10,2) unsigned NOT NULL,
  `credit6` decimal(10,2) NOT NULL,
  `credit20` decimal(10,2) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `realname` varchar(10) NOT NULL,
  `nickname` varchar(20) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `qq` varchar(15) NOT NULL,
  `vip` tinyint(3) unsigned NOT NULL,
  `gender` tinyint(1) NOT NULL,
  `birthyear` smallint(6) unsigned NOT NULL,
  `birthmonth` tinyint(3) unsigned NOT NULL,
  `birthday` tinyint(3) unsigned NOT NULL,
  `constellation` varchar(10) NOT NULL,
  `zodiac` varchar(5) NOT NULL,
  `telephone` varchar(15) NOT NULL,
  `idcard` varchar(30) NOT NULL,
  `studentid` varchar(50) NOT NULL,
  `grade` varchar(10) NOT NULL,
  `address` varchar(255) NOT NULL,
  `zipcode` varchar(10) NOT NULL,
  `nationality` varchar(30) NOT NULL,
  `resideprovince` varchar(30) NOT NULL,
  `residecity` varchar(30) NOT NULL,
  `residedist` varchar(30) NOT NULL,
  `graduateschool` varchar(50) NOT NULL,
  `company` varchar(50) NOT NULL,
  `education` varchar(10) NOT NULL,
  `occupation` varchar(30) NOT NULL,
  `position` varchar(30) NOT NULL,
  `revenue` varchar(10) NOT NULL,
  `affectivestatus` varchar(30) NOT NULL,
  `lookingfor` varchar(255) NOT NULL,
  `bloodtype` varchar(5) NOT NULL,
  `height` varchar(5) NOT NULL,
  `weight` varchar(5) NOT NULL,
  `alipay` varchar(30) NOT NULL,
  `msn` varchar(30) NOT NULL,
  `taobao` varchar(30) NOT NULL,
  `site` varchar(30) NOT NULL,
  `bio` text NOT NULL,
  `interest` text NOT NULL,
  PRIMARY KEY (`uid`),
  KEY `groupid` (`groupid`),
  KEY `uniacid` (`uniacid`),
  KEY `email` (`email`),
  KEY `mobile` (`mobile`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_wechat_attachment`;
CREATE TABLE `ims_wechat_attachment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `acid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `filename` varchar(255) NOT NULL,
  `attachment` varchar(255) NOT NULL,
  `media_id` varchar(255) NOT NULL,
  `width` int(10) unsigned NOT NULL,
  `height` int(10) unsigned NOT NULL,
  `type` varchar(15) NOT NULL,
  `model` varchar(25) NOT NULL,
  `tag` varchar(5000) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `media_id` (`media_id`),
  KEY `acid` (`acid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `ims_site_page` ADD `goodnum` int(10) unsigned NOT NULL;
CREATE TABLE IF NOT EXISTS `ims_modules_recycle` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `modulename` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `modulename` (`modulename`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `ims_uni_group` ADD `moduletime` text NOT NULL;
ALTER TABLE `ims_uni_group` ADD `owner_uid` int(10) NOT NULL;
ALTER TABLE `ims_users_group` ADD `price` decimal(10,2) unsigned DEFAULT '0';
CREATE TABLE IF NOT EXISTS `ims_agent_copyright` (
  `id` int(10) NOT NULL,
  `uid` int(5) unsigned NOT NULL ,
  `yuming` varchar(30) NOT NULL,
  `copyright` varchar(5000) NOT NULL DEFAULT '',
  `pifu` varchar(30) NOT NULL,
  PRIMARY KEY (`uid`)
 ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `ims_mc_card`;
CREATE TABLE IF NOT EXISTS `ims_mc_card` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  `color` varchar(255) NOT NULL,
  `background` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `format` varchar(50) NOT NULL,
  `fields` varchar(1000) NOT NULL,
  `snpos` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `business` text NOT NULL,
  `description` varchar(512) NOT NULL,
  `discount_type` tinyint(3) unsigned NOT NULL,
  `discount` varchar(3000) NOT NULL,
  `grant` varchar(3000) NOT NULL,
  `grant_rate` varchar(20) NOT NULL,
  `offset_rate` int(10) unsigned NOT NULL,
  `offset_max` int(10) NOT NULL,
  `nums_status` tinyint(3) unsigned NOT NULL,
  `nums_text` varchar(15) NOT NULL,
  `nums` varchar(1000) NOT NULL,
  `times_status` tinyint(3) unsigned NOT NULL,
  `times_text` varchar(15) NOT NULL,
  `times` varchar(1000) NOT NULL,
  `recharge` varchar(500) NOT NULL,
  `format_type` tinyint(3) unsigned NOT NULL,
  `params` longtext NOT NULL,
  `html` longtext NOT NULL,
  `recommend_status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `sign_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '签到功能是否开启，0为关闭，1为开启',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_mc_fans_tag_mapping` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fanid` int(11) unsigned NOT NULL,
  `tagid` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mapping` (`fanid`,`tagid`),
  KEY `fanid_index` (`fanid`),
  KEY `tagid_index` (`tagid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;
ALTER TABLE `ims_users_group` ADD `domain` int(10) NOT NULL DEFAULT '0';
ALTER TABLE `ims_mc_mapping_fans` CHANGE `groupid` `groupid` varchar(30) NOT NULL;
ALTER TABLE `ims_activity_exchange` ADD  INDEX `extra` (`extra`(333));
ALTER TABLE `ims_qrcode` CHANGE `qrcid` `qrcid` bigint(20) NOT NULL;
ALTER TABLE `ims_qrcode` CHANGE  `url` `url` varchar(256) NOT NULL;
ALTER TABLE `ims_qrcode_stat` CHANGE `qrcid` `qrcid` bigint(20) unsigned NOT NULL;
ALTER TABLE `ims_site_page` ADD  `multipage` longtext NOT NULL;

CREATE TABLE IF NOT EXISTS `ims_printer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `module` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `type` tinyint(2) NOT NULL DEFAULT '0',
  `isdefault` tinyint(2) NOT NULL DEFAULT '0',
  `token` varchar(20) NOT NULL,
  `apikey` varchar(50) NOT NULL,
  `dtuid` varchar(50) NOT NULL,
  `imei` varchar(100) NOT NULL,
  `top` varchar(500) NOT NULL,
  `bottom` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_printep` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `tepid` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `module` varchar(20) NOT NULL,
  `content` text NOT NULL,
  `defaul` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
ALTER TABLE `ims_uni_settings` ADD `shenhe` VARCHAR(10) NOT NULL AFTER `default`;
ALTER TABLE `ims_uni_settings` CHANGE `shortcuts` `shortcuts` text NOT NULL;
DROP TABLE IF EXISTS  `ims_core_cron`;
CREATE TABLE IF NOT EXISTS `ims_core_cron` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `type` varchar(5) NOT NULL,
  `filename` varchar(50) NOT NULL,
  `lastruntime` int(10) unsigned NOT NULL,
  `nextruntime` int(10) unsigned NOT NULL,
  `weekday` tinyint(3) NOT NULL,
  `day` tinyint(3) NOT NULL,
  `hour` tinyint(3) NOT NULL,
  `minute` varchar(255) NOT NULL,
  `extra` varchar(5000) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `value` varchar(15) NOT NULL,
  `url` varchar(200) NOT NULL,
  `open` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `cloudid` int(10) NOT NULL,
  `module` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `createtime` (`createtime`),
  KEY `nextruntime` (`nextruntime`),
  KEY `uniacid` (`uniacid`),
  KEY `cloudid` (`cloudid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
ALTER TABLE  `ims_uni_settings` ADD `exchange_enable` tinyint(3) unsigned NOT NULL;
ALTER TABLE  `ims_uni_settings` ADD `coupon_type` tinyint(3) unsigned NOT NULL;
CREATE TABLE IF NOT EXISTS `ims_account_wxapp` (
  `acid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `token` varchar(32) NOT NULL,
  `encodingaeskey` varchar(43) NOT NULL,
  `level` tinyint(4) NOT NULL,
  `account` varchar(30) NOT NULL,
  `original` varchar(50) NOT NULL,
  `key` varchar(50) NOT NULL,
  `secret` varchar(50) NOT NULL,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`acid`),
  KEY `uniacid` (`uniacid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_wxapp_versions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `multiid` int(10) unsigned NOT NULL,
  `version` varchar(10) NOT NULL,
  `modules` varchar(1000) NOT NULL,
  `design_method` tinyint(1) NOT NULL,
  `template` int(10) NOT NULL,
  `redirect` varchar(300) NOT NULL,
  `quickmenu` varchar(2500) NOT NULL,
  `createtime` int(10) NOT NULL,
  `connection` varchar(1000) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `version` (`version`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;
DROP TABLE IF EXISTS `ims_wxapp_general_analysis`;
CREATE TABLE `ims_wxapp_general_analysis` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `session_cnt` int(10) NOT NULL,
  `visit_pv` int(10) NOT NULL,
  `visit_uv` int(10) NOT NULL,
  `visit_uv_new` int(10) NOT NULL,
  `type` tinyint(2) NOT NULL,
  `stay_time_uv` varchar(10) NOT NULL,
  `stay_time_session` varchar(10) NOT NULL,
  `visit_depth` varchar(10) NOT NULL,
  `ref_date` varchar(8) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `ref_date` (`ref_date`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_modules_plugin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `main_module` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `main_module` (`main_module`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;
CREATE TABLE IF NOT EXISTS `ims_mc_member_property` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `property` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;
ALTER TABLE `ims_site_article` add  `edittime` int(10) NOT NULL;
ALTER TABLE `ims_site_category` add  `multiid` int(11) NOT NULL;
ALTER TABLE `ims_stat_rule` ADD INDEX `rid` (`rid`);
ALTER TABLE `ims_uni_account` add  `title_initial` varchar(1) NOT NULL;
ALTER TABLE `ims_uni_account` CHANGE `rank` `rank` int(10) DEFAULT NULL;
ALTER TABLE `ims_uni_settings` CHANGE `sync` `sync` tinyint(3) unsigned NOT NULL;
ALTER TABLE `ims_users_group` ADD  `maxwxapp` int(10) unsigned NOT NULL;
ALTER TABLE `ims_users_profile` ADD  `edittime` int(10) NOT NULL;
ALTER TABLE `ims_users_profile` CHANGE  `avatar` `avatar` varchar(255) NOT NULL;
ALTER TABLE `ims_wechat_news` ADD  `displayorder` int(2) NOT NULL;
ALTER TABLE ims_account_wxapp ADD `version` varchar(10) NOT NULL;
ALTER TABLE ims_account_wxapp ADD `access_token` varchar(200) NOT NULL;
ALTER TABLE ims_account_wxapp ADD `expirein` int(10) unsigned NOT NULL;
ALTER TABLE ims_account_wxapp ADD `refresh_token` varchar(100) NOT NULL;
ALTER TABLE ims_account_wxapp ADD `plugin` varchar(30) NOT NULL;
ALTER TABLE ims_account_wxapp ADD `description` text NOT NULL;
ALTER TABLE ims_account_wxapp ADD `status` tinyint(1) unsigned NOT NULL;
ALTER TABLE ims_account_wxapp ADD `fail_reason` text NOT NULL;
ALTER TABLE ims_account_wxapp ADD `a_uniacid` int(10) NOT NULL;
ALTER TABLE ims_account_wxapp ADD `a_acid` int(10) NOT NULL;
ALTER TABLE `ims_core_cache` CHANGE  `value` `value` longtext NOT NULL;
ALTER TABLE `ims_core_cron` CHANGE `type` `type` tinyint(3) unsigned NOT NULL;
ALTER TABLE `ims_core_cron` CHANGE  `extra` `extra` varchar(5000) NOT NULL;
ALTER TABLE `ims_core_cron` CHANGE `status` `status` tinyint(3) unsigned NOT NULL;
ALTER TABLE `ims_core_menu` CHANGE `url` `url` varchar(255) NOT NULL;
ALTER TABLE `ims_core_menu` add `group_name` varchar(30) NOT NULL;
ALTER TABLE `ims_core_menu`  ADD `icon` varchar(20) NOT NULL;
ALTER TABLE `ims_mc_cash_record`  ADD `trade_type` varchar(20) NOT NULL;
ALTER TABLE `ims_modules`  CHANGE  `version` `version` varchar(15) NOT NULL;
ALTER TABLE `ims_modules`  ADD  `title_initial` varchar(1) NOT NULL;
ALTER TABLE `ims_modules`  ADD   `wxapp_support` tinyint(1) NOT NULL DEFAULT '1';
ALTER TABLE `ims_modules`  ADD   `app_support` tinyint(1) NOT NULL DEFAULT '1';
ALTER TABLE ims_modules ADD `template_id` int(10) unsigned NOT NULL;
ALTER TABLE ims_modules ADD `item_list` text NOT NULL;
ALTER TABLE `ims_rule`  ADD  `containtype` varchar(100) NOT NULL;
ALTER TABLE `ims_rule`  ADD  `reply_type` tinyint(1) NOT NULL;
ALTER TABLE `ims_news_reply`  CHANGE `thumb` `thumb` varchar(500) NOT NULL;
ALTER TABLE `ims_news_reply`  ADD `media_id` varchar(50) NOT NULL;
INSERT INTO `ims_account_wechats` (`acid`, `uniacid`, `token`, `encodingaeskey`, `level`, `name`, `account`, `original`, `signature`, `country`, `province`, `city`, `username`, `password`, `lastupdate`, `key`, `secret`, `styleid`, `subscribeurl`, `auth_refresh_token`) VALUES
(1, 1, 'omJNpZEhZeHj1ZxFECKkP48B5VFbk1HP', '', 1, 'Wzteam', '', '', '', '', '', '', '', '', 0, '', '', 1, '', '');
INSERT INTO `ims_account` (`acid`, `uniacid`, `hash`, `type`, `isconnect`, `isdeleted`) VALUES
(1, 1, 'uRr8qvQV', 1, 0, 0);
INSERT INTO `ims_mc_groups` (`groupid`, `uniacid`, `title`, `credit`, `isdefault`) VALUES
(1, 1, '默认会员组', 0, 1);
INSERT INTO `ims_site_multi` (`id`, `uniacid`, `title`, `styleid`, `site_info`, `status`, `bindhost`) VALUES
(1, 1, '微赞团队', 1, '', 1, '');
INSERT INTO `ims_modules_bindings` (`eid`, `module`, `entry`, `call`, `title`, `do`, `state`, `direct`, `url`, `icon`, `displayorder`) VALUES
(13, 'we7_coupon', 'cover', '', '会员卡入口设置', 'card', '', 0, '', '', 0),
(14, 'we7_coupon', 'cover', '', '收银台入口设置', 'clerk', '', 0, '', '', 0),
(15, 'we7_coupon', 'menu', '', '会员卡设置', 'membercard', '', 0, '', '', 0),
(16, 'we7_coupon', 'menu', '', '会员卡管理', 'cardmanage', '', 0, '', '', 0),
(17, 'we7_coupon', 'menu', '', '会员属性', 'memberproperty', '', 0, '', '', 0),
(18, 'we7_coupon', 'menu', '', '优惠券管理', 'couponmanage', '', 0, '', '', 0),
(19, 'we7_coupon', 'menu', '', '优惠券核销', 'couponconsume', '', 0, '', '', 0),
(20, 'we7_coupon', 'menu', '', '优惠券派发', 'couponmarket', '', 0, '', '', 0),
(21, 'we7_coupon', 'menu', '', '兑换优惠券', 'couponexchange', '', 0, '', '', 0),
(22, 'we7_coupon', 'menu', '', '兑换真实物品', 'goodsexchange', '', 0, '', '', 0),
(23, 'we7_coupon', 'menu', '', '门店管理', 'storelist', '', 0, '', '', 0),
(24, 'we7_coupon', 'menu', '', '店员管理', 'clerklist', '', 0, '', '', 0),
(25, 'we7_coupon', 'menu', '', '门店收银台', 'paycenter', '', 0, '', '', 0),
(26, 'we7_coupon', 'menu', '', '工作台菜单设置', 'clerkdeskmenu', '', 0, '', '', 0),
(27, 'we7_coupon', 'menu', '', '签到管理', 'signmanage', '', 0, '', '', 0),
(28, 'we7_coupon', 'menu', '', '通知管理', 'noticemanage', '', 0, '', '', 0),
(29, 'we7_coupon', 'menu', '', '会员积分统计', 'statcredit1', '', 0, '', '', 0),
(30, 'we7_coupon', 'menu', '', '会员余额统计', 'statcredit2', '', 0, '', '', 0),
(31, 'we7_coupon', 'menu', '', '会员现金消费统计', 'statcash', '', 0, '', '', 0),
(32, 'we7_coupon', 'menu', '', '会员卡统计', 'statcard', '', 0, '', '', 0),
(33, 'we7_coupon', 'menu', '', '收银台收款统计', 'statpaycenter', '', 0, '', '', 0),
(34, 'we7_coupon', 'menu', '', '微信卡券回复', 'wxcardreply', '', 0, '', '', 0),
(35, 'we7_coupon', 'profile', '', '会员卡', 'card', '', 0, '', '', 0),
(36, 'we7_coupon', 'profile', '', '兑换商城', 'activity', '', 0, '', '', 0);

INSERT INTO `ims_profile_fields` (`id`, `field`, `available`, `title`, `description`, `displayorder`, `required`, `unchangeable`, `showinregister`, `field_length`) VALUES
(1, 'realname', 1, '真实姓名', '', 0, 1, 1, 1, 0),
(2, 'nickname', 1, '昵称', '', 1, 1, 0, 1, 0),
(3, 'avatar', 1, '头像', '', 1, 0, 0, 0, 0),
(4, 'qq', 1, 'QQ号', '', 0, 0, 0, 1, 0),
(5, 'mobile', 1, '手机号码', '', 0, 0, 0, 0, 0),
(6, 'vip', 1, 'VIP级别', '', 0, 0, 0, 0, 0),
(7, 'gender', 1, '性别', '', 0, 0, 0, 0, 0),
(8, 'birthyear', 1, '出生生日', '', 0, 0, 0, 0, 0),
(9, 'constellation', 1, '星座', '', 0, 0, 0, 0, 0),
(10, 'zodiac', 1, '生肖', '', 0, 0, 0, 0, 0),
(11, 'telephone', 1, '固定电话', '', 0, 0, 0, 0, 0),
(12, 'idcard', 1, '证件号码', '', 0, 0, 0, 0, 0),
(13, 'studentid', 1, '学号', '', 0, 0, 0, 0, 0),
(14, 'grade', 1, '班级', '', 0, 0, 0, 0, 0),
(15, 'address', 1, '邮寄地址', '', 0, 0, 0, 0, 0),
(16, 'zipcode', 1, '邮编', '', 0, 0, 0, 0, 0),
(17, 'nationality', 1, '国籍', '', 0, 0, 0, 0, 0),
(18, 'resideprovince', 1, '居住地址', '', 0, 0, 0, 0, 0),
(19, 'graduateschool', 1, '毕业学校', '', 0, 0, 0, 0, 0),
(20, 'company', 1, '公司', '', 0, 0, 0, 0, 0),
(21, 'education', 1, '学历', '', 0, 0, 0, 0, 0),
(22, 'occupation', 1, '职业', '', 0, 0, 0, 0, 0),
(23, 'position', 1, '职位', '', 0, 0, 0, 0, 0),
(24, 'revenue', 1, '年收入', '', 0, 0, 0, 0, 0),
(25, 'affectivestatus', 1, '情感状态', '', 0, 0, 0, 0, 0),
(26, 'lookingfor', 1, ' 交友目的', '', 0, 0, 0, 0, 0),
(27, 'bloodtype', 1, '血型', '', 0, 0, 0, 0, 0),
(28, 'height', 1, '身高', '', 0, 0, 0, 0, 0),
(29, 'weight', 1, '体重', '', 0, 0, 0, 0, 0),
(30, 'alipay', 1, '支付宝帐号', '', 0, 0, 0, 0, 0),
(31, 'msn', 1, 'MSN', '', 0, 0, 0, 0, 0),
(32, 'email', 1, '电子邮箱', '', 0, 0, 0, 0, 0),
(33, 'taobao', 1, '阿里旺旺', '', 0, 0, 0, 0, 0),
(34, 'site', 1, '主页', '', 0, 0, 0, 0, 0),
(35, 'bio', 1, '自我介绍', '', 0, 0, 0, 0, 0),
(36, 'interest', 1, '兴趣爱好', '', 0, 0, 0, 0, 0);
INSERT INTO `ims_rule` (`id`, `uniacid`, `name`, `module`, `displayorder`, `status`, `containtype`, `reply_type`) VALUES
(1, 0, '城市天气', 'userapi', 255, 1, '', 0),
(2, 0, '百度百科', 'userapi', 255, 1, '', 0),
(3, 0, '即时翻译', 'userapi', 255, 1, '', 0),
(4, 0, '今日老黄历', 'userapi', 255, 1, '', 0),
(5, 0, '看新闻', 'userapi', 255, 1, '', 0),
(6, 0, '快递查询', 'userapi', 255, 1, '', 0),
(7, 1, '个人中心入口设置', 'cover', 0, 1, '', 0),
(8, 1, '微赞团队入口设置', 'cover', 0, 1, '', 0);
INSERT INTO `ims_rule_keyword` (`id`, `rid`, `uniacid`, `module`, `content`, `type`, `displayorder`, `status`) VALUES
(1, 1, 0, 'userapi', '^.+天气$', 3, 255, 1),
(2, 2, 0, 'userapi', '^百科.+$', 3, 255, 1),
(3, 2, 0, 'userapi', '^定义.+$', 3, 255, 1),
(4, 3, 0, 'userapi', '^@.+$', 3, 255, 1),
(5, 4, 0, 'userapi', '日历', 1, 255, 1),
(6, 4, 0, 'userapi', '万年历', 1, 255, 1),
(7, 4, 0, 'userapi', '黄历', 1, 255, 1),
(8, 4, 0, 'userapi', '几号', 1, 255, 1),
(9, 5, 0, 'userapi', '新闻', 1, 255, 1),
(10, 6, 0, 'userapi', '^(申通|圆通|中通|汇通|韵达|顺丰|EMS) *[a-z0-9]{1,}$', 3, 255, 1),
(11, 7, 1, 'cover', '个人中心', 1, 0, 1),
(12, 8, 1, 'cover', '首页', 1, 0, 1);
INSERT INTO `ims_site_styles` (`id`, `uniacid`, `templateid`, `name`) VALUES
(1, 1, 1, '微站默认模板_gC5C');
INSERT INTO `ims_site_templates` (`id`, `name`, `title`, `version`, `description`, `author`, `url`, `type`, `sections`) VALUES
(1, 'default', '微站默认模板', '', '由微赞提供默认微站模板套系', '微赞团队', 'http://weizancms.com', '1', 0);
INSERT INTO `ims_site_templates` VALUES (2, 'style31', '微赞style31', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'drink', 0, '6.0');
INSERT INTO `ims_site_templates` VALUES (3, 'style_car', '微站微汽车', '由微赞提供微站微汽车', '012微赞', 'http://bbs.b2ctui.com', 'other', 0, '');
INSERT INTO `ims_site_templates` VALUES (4, 'style99', '微赞微站模板99', '由微赞微站模板套系', 'weidongli Team', 'http://bbs.b2ctui.com', 'other', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (5, 'style98', '微赞微站模板98', '由微赞微站模板套系', 'weidongli Team', 'http://bbs.b2ctui.com', 'other', 0, '');
INSERT INTO `ims_site_templates` VALUES (6, 'style96', '微赞微站模板96', '由微赞微站模板套系', 'weidongli Team', 'http://bbs.b2ctui.com', 'other', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (7, 'style95', '微赞微站模板95', '由微赞微站模板套系', 'weidongli Team', 'http://bbs.b2ctui.com', 'other', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (8, 'style94', '微赞微站模板94', '由微赞微站模板套系', 'weidongli Team', 'http://bbs.b2ctui.com', 'other', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (9, 'style93', '微赞微站模板93', '由微赞微站模板套系', 'weidongli Team', 'http://bbs.b2ctui.com', 'other', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (10, 'style92', '微赞微站模板92', '由微赞微站模板套系', 'weidongli Team', 'http://bbs.b2ctui.com', 'other', 0, '');
INSERT INTO `ims_site_templates` VALUES (11, 'style91', '微赞模板91', '由微赞提供默认微站模板套系', 'WDL', 'http://bbs.b2ctui.com', 'other', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (12, 'style90', '微赞模板90', '由微赞提供默认微站模板套系', 'WDL', 'http://bbs.b2ctui.com', 'other', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (13, 'style9', '微赞style9', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'car', 0, '6.0');
INSERT INTO `ims_site_templates` VALUES (14, 'style89', '微赞模板89', '由微赞提供默认微站模板套系', 'WDL', 'http://bbs.b2ctui.com', 'other', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (15, 'style88', '微赞模板88', '由微赞提供默认微站模板套系', 'WDL', 'http://bbs.b2ctui.com', 'other', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (16, 'style87', '微赞模板87', '由微赞提供默认微站模板套系', 'WDL', 'http://bbs.b2ctui.com', 'other', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (17, 'style86', '微赞模板86', '由微赞提供默认微站模板套系', 'WDL', 'http://bbs.b2ctui.com', 'other', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (18, 'style85', '微赞模板85', '由易福源码网提供默认微站模板套系', 'WDL', 'http://bbs.b2ctui.com', 'other', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (19, 'style84', '微赞模板84', '由微赞提供默认微站模板套系', 'WDL', 'http://bbs.b2ctui.com', 'other', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (20, 'style83', '微赞模板83', '由微赞提供默认微站模板套系', 'WDL', 'http://bbs.b2ctui.com', 'other', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (21, 'style82', '微赞模板82', '由微赞提供默认微站模板套系', 'WDL', 'http://bbs.b2ctui.com', 'other', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (22, 'style81', '微赞模板81', '由微赞提供默认微站模板套系，刷新看事例，请自行上传30*30px大小的png格式透明分类图片', 'WDL', 'http://bbs.b2ctui.com', 'other', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (23, 'style80', '微赞模板80', '由微赞提供默认微站模板套系', 'WDL', 'http://bbs.b2ctui.com', 'other', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (118, 'style8', '微赞style8', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'shoot', 0, '6.0');
INSERT INTO `ims_site_templates` VALUES (25, 'style79', '微赞模板79', '由微赞提供默认微站模板套系', 'WDL', 'http://bbs.b2ctui.com', 'other', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (26, 'style78', '微赞模板78', '由微赞提供默认微站模板套系', 'WDL', 'http://bbs.b2ctui.com', 'other', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (27, 'style77', '微赞模板77', '由微赞提供默认微站模板套系', 'WDL', 'http://bbs.b2ctui.com', 'other', 0, '');
INSERT INTO `ims_site_templates` VALUES (28, 'style76', '微赞模板76', '由微赞提供默认微站模板套系', 'WDL', 'http://bbs.b2ctui.com', 'other', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (29, 'style75', '微赞模板75', '由易福源码网提供默认微站模板套系', 'WDL', 'http://bbs.b2ctui.com', 'other', 0, '');
INSERT INTO `ims_site_templates` VALUES (30, 'style74', '微赞模板74', '由微赞提供默认微站模板套系', 'WDL', 'http://bbs.b2ctui.com', 'other', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (31, 'style73', '微赞模板73', '由微赞提供默认微站模板套系', 'WDL', 'http://bbs.b2ctui.com', 'other', 0, '');
INSERT INTO `ims_site_templates` VALUES (32, 'style72', '微赞模板72', '由微赞提供默认微站模板套系', 'WDL', 'http://bbs.b2ctui.com', 'other', 0, '');
INSERT INTO `ims_site_templates` VALUES (33, 'style71', '微赞模71', '由微赞提供默认微站模板套系', 'WDL', 'http://bbs.b2ctui.com', 'other', 0, '');
INSERT INTO `ims_site_templates` VALUES (34, 'style70', '微赞模板70', '由微赞提供默认微站模板套系', 'WDL', 'http://bbs.b2ctui.com', 'other', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (35, 'style7', '微赞style7', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '6.0');
INSERT INTO `ims_site_templates` VALUES (36, 'style69', '微赞模板69', '由微赞提供默认微站模板套系', 'WDL', 'http://bbs.b2ctui.com', 'other', 0, '');
INSERT INTO `ims_site_templates` VALUES (37, 'style68', '微赞模板68', '由微赞提供默认微站模板套系', 'WDL', 'http://bbs.b2ctui.com', 'other', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (38, 'style67', '微赞模板67', '由易福源码网提供默认微站模板套系', 'WDL', 'http://bbs.b2ctui.com', 'other', 0, '');
INSERT INTO `ims_site_templates` VALUES (39, 'style66', '微赞模板66', '由微赞提供默认微站模板套系', 'WDL', 'http://bbs.b2ctui.com', 'other', 0, '');
INSERT INTO `ims_site_templates` VALUES (40, 'style65', '微赞模板65', '由微赞提供默认微站模板套系', 'WDL', 'http://bbs.b2ctui.com', 'other', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (41, 'style64', '微赞模板64', '由微赞提供默认微站模板套系', 'WDL', 'http://bbs.b2ctui.com', 'other', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (42, 'style63', '微赞模板63', '由微赞提供默认微站模板套系', 'WDL', 'http://bbs.b2ctui.com', 'other', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (43, 'style62', '微赞模板62', '由微赞提供默认微站模板套系', 'WDL', 'http://bbs.b2ctui.com', 'other', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (44, 'style61', '微赞模板61', '由微赞提供默认微站模板套系', 'WDL', 'http://bbs.b2ctui.com', 'other', 0, '');
INSERT INTO `ims_site_templates` VALUES (45, 'style60', '微赞模板60', '由微赞提供默认微站模板套系', 'WDL', 'http://bbs.b2ctui.com', 'other', 0, '');
INSERT INTO `ims_site_templates` VALUES (46, 'style6', '微赞style6', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '6.0');
INSERT INTO `ims_site_templates` VALUES (47, 'style59', '微赞模板59', '由易福源码网提供默认微站模板套系', 'WDL', 'http://bbs.b2ctui.com', 'other', 0, '');
INSERT INTO `ims_site_templates` VALUES (48, 'style58', '微赞模板58', '由微赞提供默认微站模板套系', 'WDL', 'http://bbs.b2ctui.com', 'other', 0, '');
INSERT INTO `ims_site_templates` VALUES (49, 'style57', '微赞style57', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '6.0');
INSERT INTO `ims_site_templates` VALUES (50, 'style56', '微赞style56', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '6.0');
INSERT INTO `ims_site_templates` VALUES (51, 'style55', '微赞style55', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '6.0');
INSERT INTO `ims_site_templates` VALUES (52, 'style54', '微赞style54', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '6.0');
INSERT INTO `ims_site_templates` VALUES (53, 'style53', '微赞style53', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '7.0');
INSERT INTO `ims_site_templates` VALUES (54, 'style52', '微赞style52', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '5.0');
INSERT INTO `ims_site_templates` VALUES (55, 'style51', '微赞style51', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '6.0');
INSERT INTO `ims_site_templates` VALUES (56, 'style50', '微赞style50', '由易福源码网提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '6.0');
INSERT INTO `ims_site_templates` VALUES (57, 'style5', '微赞style5', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'car', 0, '5.0');
INSERT INTO `ims_site_templates` VALUES (58, 'style49', '微赞style49', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '6.0');
INSERT INTO `ims_site_templates` VALUES (59, 'style48', '微赞style48', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '6.0');
INSERT INTO `ims_site_templates` VALUES (60, 'style47', '微赞style47', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '6.0');
INSERT INTO `ims_site_templates` VALUES (61, 'style46', '微赞style46', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '6.0');
INSERT INTO `ims_site_templates` VALUES (62, 'style45', '微赞style45', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '6.0');
INSERT INTO `ims_site_templates` VALUES (63, 'style44', '微赞style44', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '6.0');
INSERT INTO `ims_site_templates` VALUES (64, 'style43', '微赞style43', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '6.0');
INSERT INTO `ims_site_templates` VALUES (65, 'style42', '微赞style42', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '6.0');
INSERT INTO `ims_site_templates` VALUES (66, 'style41', '微赞style41', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '6.0');
INSERT INTO `ims_site_templates` VALUES (67, 'style40', '微赞style40', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '6.0');
INSERT INTO `ims_site_templates` VALUES (68, 'style4', '微赞style4', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'car', 0, '5.0');
INSERT INTO `ims_site_templates` VALUES (69, 'style39', '微赞style39', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '6.0');
INSERT INTO `ims_site_templates` VALUES (70, 'style38', '微赞style38', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '6.0');
INSERT INTO `ims_site_templates` VALUES (71, 'style37', '微赞style37', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '6.0');
INSERT INTO `ims_site_templates` VALUES (72, 'style36', '微赞style36', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'medical', 0, '6.0');
INSERT INTO `ims_site_templates` VALUES (73, 'style35', '微赞style35', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '6.0');
INSERT INTO `ims_site_templates` VALUES (74, 'style34', '微赞style34', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '6.0');
INSERT INTO `ims_site_templates` VALUES (75, 'style33', '微赞style33', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '6.0');
INSERT INTO `ims_site_templates` VALUES (76, 'style32', '微赞style32', '由易福源码网提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '6.0');
INSERT INTO `ims_site_templates` VALUES (77, 'style30', '微赞style30', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '6.0');
INSERT INTO `ims_site_templates` VALUES (78, 'style3', '微赞style3', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '4.0');
INSERT INTO `ims_site_templates` VALUES (79, 'style29', '微赞style29', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '6.0');
INSERT INTO `ims_site_templates` VALUES (80, 'style28', '微赞style28', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '6.0');
INSERT INTO `ims_site_templates` VALUES (81, 'style27', '微赞style27', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '6.0');
INSERT INTO `ims_site_templates` VALUES (82, 'style26', '微赞style26', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '6.0');
INSERT INTO `ims_site_templates` VALUES (83, 'style25', '微赞style25', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '6.0');
INSERT INTO `ims_site_templates` VALUES (84, 'style24', '微赞style24', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '6.0');
INSERT INTO `ims_site_templates` VALUES (85, 'style23', '微赞style23', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '5.0');
INSERT INTO `ims_site_templates` VALUES (86, 'style22', '微赞style22', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '5.0');
INSERT INTO `ims_site_templates` VALUES (87, 'style21', '微赞style21', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '6.0');
INSERT INTO `ims_site_templates` VALUES (88, 'style20', '微赞style20', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '6.0');
INSERT INTO `ims_site_templates` VALUES (89, 'style2', '微赞style2', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'cosmetology', 0, '5.0');
INSERT INTO `ims_site_templates` VALUES (90, 'style19', '微赞style19', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'drink', 0, '7.0');
INSERT INTO `ims_site_templates` VALUES (91, 'style18', '微赞style18', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '4.0');
INSERT INTO `ims_site_templates` VALUES (92, 'style17', '微赞style17', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '6.0');
INSERT INTO `ims_site_templates` VALUES (93, 'style16', '微赞style16', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '6.0');
INSERT INTO `ims_site_templates` VALUES (94, 'style15', '微赞style15', '由易福源码网提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'tourism', 0, '6.0');
INSERT INTO `ims_site_templates` VALUES (95, 'style14', '微赞style14', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '6.0');
INSERT INTO `ims_site_templates` VALUES (96, 'style13', '微赞style13', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'realty', 0, '6.0');
INSERT INTO `ims_site_templates` VALUES (97, 'style12', '微赞style12', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '5.0');
INSERT INTO `ims_site_templates` VALUES (98, 'style118', '微赞微站模板118', '由微赞微站模板套系', 'weidongli Team', 'http://bbs.b2ctui.com', 'other', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (99, 'style117', '微赞微站模板117', '由微赞微站模板套系', 'weidongli Team', 'http://bbs.b2ctui.com', 'other', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (100, 'style116', '微赞微站模板116', '由微赞微站模板套系', 'weidongli Team', 'http://bbs.b2ctui.com', 'other', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (101, 'style113', '微赞微站模板113', '由微赞微站模板套系', 'weidongli Team', 'http://bbs.b2ctui.com', 'other', 0, '');
INSERT INTO `ims_site_templates` VALUES (102, 'style112', '微赞微站模板112', '由微赞微站模板套系', 'weidongli Team', 'http://bbs.b2ctui.com', 'other', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (103, 'style111', '微赞微站模板111', '由微赞微站模板套系', 'weidongli Team', 'http://bbs.b2ctui.com', 'other', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (104, 'style110', '微赞微站模板110', '由微赞微站模板套系', 'weidongli Team', 'http://bbs.b2ctui.com', 'other', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (105, 'style11', '微赞style11', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'shoot', 0, '6.0');
INSERT INTO `ims_site_templates` VALUES (106, 'style108', '微赞微站模板108', '由微赞微站模板套系', 'weidongli Team', 'http://bbs.b2ctui.com', 'other', 0, '');
INSERT INTO `ims_site_templates` VALUES (107, 'style107', '微赞微站模板107', '由微赞微站模板套系', 'weidongli Team', 'http://bbs.b2ctui.com', 'other', 0, '');
INSERT INTO `ims_site_templates` VALUES (108, 'style106', '微赞微站模板106', '由微赞微站模板套系', 'weidongli Team', 'http://bbs.b2ctui.com', 'other', 0, '');
INSERT INTO `ims_site_templates` VALUES (109, 'style105', '微赞微站模板105', '由微赞微站模板套系', 'weidongli Team', 'http://bbs.b2ctui.com', 'other', 0, '');
INSERT INTO `ims_site_templates` VALUES (110, 'style104', '微赞微站模板104', '由微赞微站模板套系', 'weidongli Team', 'http://bbs.b2ctui.com', 'other', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (111, 'style103', '微赞微站模板103', '由微赞微站模板套系', 'weidongli Team', 'http://bbs.b2ctui.com', 'other', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (112, 'style102', '微赞微站模板102', '由微赞微站模板套系', 'weidongli Team', 'http://bbs.b2ctui.com', 'other', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (113, 'style101', '微赞微站模板101', '由易福源码网提供默认微站模板套系', 'weidongli Team', 'http://bbs.b2ctui.com', 'other', 0, '');
INSERT INTO `ims_site_templates` VALUES (114, 'style100', '微赞微站模板100', '由微赞微站模板套系', 'weidongli Team', 'http://bbs.b2ctui.com', 'other', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (115, 'style10', '微赞style10', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'shoot', 0, '6.0');
INSERT INTO `ims_site_templates` VALUES (116, 'style1', '微赞style1', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'drink', 0, '4.0');
INSERT INTO `ims_site_templates` VALUES (117, 'fanxing_daqi1', '微赞fanxing_daqi1', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (119, 'superman_wxtmpl', '微赞superman_wxtmpl', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '2.0');
INSERT INTO `ims_site_templates` VALUES (120, 'hccc05', '微赞hccc05', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (121, 'hccc04', '微赞hccc04', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (122, 'hccc02', '微赞hccc02', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (123, 'hccc01', '微赞hccc01', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (124, 'style109', '微赞微站模板109', '由微赞微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (125, 'style114', '微赞微站模板114', '由微赞微站模板套系', 'WDL', 'http://bbs.012wz.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (126, 'style119', '微赞微站模板119', '由微赞微站模板套系', 'WDL', 'http://bbs.012wz.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (127, 'style120', '微赞微站模板120', '由微赞微站模板套系', 'WDL', 'http://bbs.012wz.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (128, 'style121', '微赞微站模板121', '由微赞微站模板套系', 'weidongli Team', 'http://bbs.b2ctui.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (129, 'style125', '微赞微站模板125', '由微赞微站模板套系', 'weidongli Team', 'http://bbs.b2ctui.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (130, 'style127', '微赞微站默认模板127', '霓虹灯下的旋转特效', '微赞', 'http://bbs.b2ctui.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (131, 'style128', '微赞模版128', '由微赞提供微站模板套系128', '微赞', 'http://www.b2ctui.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (132, 'style129', '微赞模版系列129', '由微赞提供微站模板套系129', '微赞', 'http://www.b2ctui.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (133, 'style130', '微赞130', '由微赞提供微站模板套系130', '微赞', 'http://www.b2ctui.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (134, 'style133', '微赞默认模板133', '由易福源码网提供默认微站模板套系', 'WEIDONGLI', 'http://www.b2ctui.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (135, 'style134', '微赞134', '由微赞提供默认套系', 'WEIDONGLI', 'http://www.b2ctui.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (136, 'style137', '微赞137', '圆圆的小角，圆圆的卡片，刷新看事例，请自行上传30*30px大小的png格式分类图片', 'Hooyo', 'http://bbs.b2ctui.com/', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (137, 'style139', '微赞139', '由微赞提供默认套系', '012微赞', 'http://bbs.b2ctui.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (138, 'style141', '微赞141', '由微赞提供默认套系', '012微赞', 'http://bbs.b2ctui.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (139, 'style143', '微赞143', '由微赞提供默认套系', '012微赞', 'http://bbs.b2ctui.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (140, 'style144', '微赞144', '由微赞提供默认套系', '012微赞', 'http://bbs.b2ctui.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (141, 'style146', '微赞146', '由微赞提供默认套系', '012微赞', 'http://bbs.b2ctui.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (142, 'style147', '微赞147', '由微赞提供默认套系', '012微赞', 'http://bbs.b2ctui.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (143, 'style148', '微赞148', '微赞系列', '微赞', '微', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (144, 'style149', '微赞149', '由微赞提供默认套系', 'WeiYang Team', 'http://bbs.b2ctui.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (145, 'style150', '微赞150', '微赞模板', '微', 'bbs.012wz.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (146, 'style153', '微赞153', '由微赞提供套系', '微赞', 'http://www.b2ctui.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (147, 'style156', '微赞156', '由微赞提供小清新套系', 'b2ctui.com', 'http://b2ctui.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (148, 'style157', '微赞157', '由微赞提供小清新套系', 'b2ctui.com', 'http://b2ctui.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (149, 'style158', '微赞158', '仿微盟的电商微官网模板', '微赞 大圣', 'http://www.b2ctui.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (150, 'style159', '微赞159', '微赞微营销', 'weidongli', 'http://www.b2ctui.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (151, 'style160', '微赞160', '微赞电商分类微官网模板', '微赞 大圣', 'http://www.b2ctui.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (152, 'style163', '微赞163', '由微赞提供套系', '微赞', 'bbs.b2ctui.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (153, 'style166', '微赞166', '由易福源码网提供默认微站模板套系', 'Fox', 'http://www.b2ctui.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (154, 'style167', '微赞167', '微赞', 'wdl', 'http://www.b2ctui.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (155, 'style169', '微赞169', '由微赞提供默认套系', '012微赞', 'http://bbs.b2ctui.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (156, 'style170', '微赞170', '微赞提供', 'wdl Team', 'http://bbs.b2ctui.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (157, 'style172', '微赞172', '微赞', 'weidongli', 'http://www.b2ctui.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (158, 'style174', '微赞174', '古朴风“木板风格”', '微赞', 'weidongli', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (159, 'style175', '微赞175', 'weidongli', 'weidongli Team', 'http://bbs.b2ctui.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (160, 'style179', '微站179', '微', 'bjue', '', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (161, 'style180', '微赞180', '微赞提供', 'bjue', '微', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (162, 'style184', '微赞184', '由易福源码网提供默认微站模板套系', '微赞', 'http://www.b2ctui.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (163, 'style185', '微赞185', '由微赞提供套系', '微赞', 'http://bbs.b2ctui.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (164, 'style186', '微赞186', '由微赞提供套系', '微赞', 'http://bbs.b2ctui.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (165, 'style187', '微赞187', '由微赞提供套系', '微赞', 'http://bbs.b2ctui.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (166, 'style188', '微赞188', '由微赞提供套系', '微赞网络科技', 'www.b2ctui.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (167, 'style190', '微赞190', '由微赞提供微站模板', '微赞', 'http://www.b2ctui.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (168, 'style193', '超爱小清新|微赞193', '由微赞提供小清新微站模板套系', 'b2ctui.com', 'http://b2ctui.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (169, 'style195', '超爱小清新|微赞195', '由微赞提供小清新微站模板套系', 'b2ctui.com', 'http://b2ctui.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (170, 'style198', '微赞默认模板系列198', '由微赞提供默认微站模板套系之一', '微赞', 'http://微赞.中国', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (171, 'style199', '微赞默认模板系列199', '由微赞提供默认微站模板套系', '微赞', 'http://微赞.中国', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (172, 'style200', '微赞默认模板系列200', '由微赞提供默认微站模板套系之三', 'Dear Hu', 'http://微赞.中国', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (173, 'style211', 'style211', '由微赞提供微站模板', '微赞', 'http://www.b2ctui.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (174, 'style206', '微赞206', '由微赞提供默认微站模板套系', '微赞', 'http://www.b2ctui.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (175, 'style209', '紫色高贵209', '微赞作品', 'wdl', 'http://bbs.b2ctui.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (176, 'style210', '微赞210', '由易福源码网提供默认微站模板套系', 'wdl', 'http://bbs.b2ctui.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (177, 'jlsh_wb', '微赞-仿微盟手机端jlsh_wb', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (178, 'hc_style012', '微赞hc_style012', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (179, 'hc_style011', '微赞hc_style011', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (180, 'hc_style010', '微赞hc_style010', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (181, 'hc_style009', '微赞hc_style009', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (182, 'hc_style008', '微赞hc_style008', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (183, 'hc_style007', '微赞hc_style007', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (184, 'hc_style006', '微赞hc_style006', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (185, 'hc_style005', '微赞hc_style005', '由易福源码网提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (186, 'hc_style004', '微赞hc_style004', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (187, 'hc_style003', '微赞hc_style003', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (188, 'hc_style002', '微赞hc_style002', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (189, 'hc_style001', '微赞hc_style001', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (190, 'hc_style_03', '微赞hc_style_03', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (191, 'mihuacar', '微赞-mihuacar', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (192, 'niling_themes01', '微赞-niling_themes01', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (193, 'sdren', '微赞-sdren', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (194, 'shijihongda', '微赞-shijihongda', '由易福源码网提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (195, 'sql_tpl_company', '微赞sql_tpl_company', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (196, 'on_vfuling', '微赞on_vfuling', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (197, 'wl_tm01', '微赞-wl_tm01', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (198, 'xingwei08', '微站【首页08】模板', '首页音乐切换多菜单模版', 'QQ:79172293', 'http://bbs.we7.cc/', 'often', 0, '1.0');
INSERT INTO `ims_site_templates` VALUES (200, 'dp_moban01', 'DP整站模板01', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'other', 0, '');
INSERT INTO `ims_site_templates` VALUES (201, 'dp_list03', 'DP列表模板03', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'other', 0, '');
INSERT INTO `ims_site_templates` VALUES (202, 'dp_list01', 'DP列表模板01', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'other', 0, '');
INSERT INTO `ims_site_templates` VALUES (204, 'vcard02', '微信样式WeUI', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'other', 0, '');
INSERT INTO `ims_site_templates` VALUES (205, 'vcard01', '微名片模板1', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'other', 0, '');
INSERT INTO `ims_site_templates` VALUES (206, 'mihuakid', '幼儿园微官网模板', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'other', 0, '');
INSERT INTO `ims_site_templates` VALUES (207, 'bsht_WeUI', '微赞样式WeUI', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'other', 0, '');
INSERT INTO `ims_site_templates` VALUES (208, 'niling_themes_06', 'niling_themes_06', 'niling_themes_06', '微赞', 'http://bbs.012wz.com', 'other', 0, '');
INSERT INTO `ims_site_templates` VALUES (209, 'niling_themes_05', 'niling_themes_05', 'niling_themes_05', '微赞', 'http://bbs.012wz.com', 'other', 0, '');
INSERT INTO `ims_site_templates` VALUES (210, 'niling_themes_01', 'niling_themes_01', 'niling_themes_01', '微赞', 'http://bbs.012wz.com', 'other', 0, '');
INSERT INTO `ims_site_templates` VALUES (211, 'm001_themes01', 'm001_themes01', 'm001_themes01', '微赞', 'http://bbs.012wz.com', 'other', 0, '');
INSERT INTO `ims_site_templates` VALUES (212, 'lyz_dcstyle', 'lyz_dcstyle', 'lyz_dcstyle', '微赞', 'http://bbs.012wz.com', 'other', 0, '');
INSERT INTO `ims_site_templates` VALUES (213, 'bilin_version2', 'bilin_version2模版', '由微赞提供默认微站模板套系', '微赞', 'http://bbs.012wz.com', 'other', 0, '');

INSERT INTO `ims_uni_account` (`uniacid`, `groupid`, `name`, `description`, `default_acid`, `rank`, `title_initial`) VALUES
(1, -1, '微赞团队', '一个朝气蓬勃的团队', 1, NULL, 'W');
INSERT INTO `ims_uni_group` (`id`, `name`, `modules`, `templates`, `uniacid`) VALUES
(1, '体验套餐服务', 'N;', 'N;', 0);
INSERT INTO `ims_cover_reply` (`id`, `uniacid`, `multiid`, `rid`, `module`, `do`, `title`, `description`, `thumb`, `url`) VALUES
(1, 1, 0, 7, 'mc', '', '进入个人中心', '', '', './index.php?c=mc&a=home&i=1'),
(2, 1, 1, 8, 'site', '', '进入首页', '', '', './index.php?c=home&i=1&t=1');
";
$datas[] = <<<EOF
INSERT INTO `ims_modules` (`mid`, `name`, `type`, `title`, `version`, `ability`, `description`, `author`, `url`, `settings`, `subscribes`, `handles`, `isrulefields`, `issystem`, `target`, `iscard`, `permissions`, `title_initial`, `wxapp_support`, `app_support`) VALUES
(1, 'basic', 'system', '基本文字回复', '1.0', '和您进行简单对话', '一问一答得简单对话. 当访客的对话语句中包含指定关键字, 或对话语句完全等于特定关键字, 或符合某些特定的格式时. 系统自动应答设定好的回复内容.', 'Weizancms Team', 'http://www.weizancms.com/', 0, '', '', 1, 1, 0, 0, '', '', 1, 2),
(2, 'news', 'system', '基本混合图文回复', '1.0', '为你提供生动的图文资讯', '一问一答得简单对话, 但是回复内容包括图片文字等更生动的媒体内容. 当访客的对话语句中包含指定关键字, 或对话语句完全等于特定关键字, 或符合某些特定的格式时. 系统自动应答设定好的图文回复内容.', 'Weizancms Team', 'http://www.weizancms.com/', 0, '', '', 1, 1, 0, 0, '', '', 1, 2),
(3, 'music', 'system', '基本音乐回复', '1.0', '提供语音、音乐等音频类回复', '在回复规则中可选择具有语音、音乐等音频类的回复内容，并根据用户所设置的特定关键字精准的返回给粉丝，实现一问一答得简单对话。', 'Weizancms Team', 'http://www.weizancms.com/', 0, '', '', 1, 1, 0, 0, '', '', 1, 2),
(4, 'userapi', 'system', '自定义接口回复', '1.1', '更方便的第三方接口设置', '自定义接口又称第三方接口，可以让开发者更方便的接入微赞系统，高效的与微信公众平台进行对接整合。', 'Weizancms Team', 'http://www.weizancms.com/', 0, '', '', 1, 1, 0, 0, '', '', 1, 2),
(5, 'recharge', 'system', '会员中心充值模块', '1.0', '提供会员充值功能', '', 'Weizancms Team', 'http://www.weizancms.com/', 0, '', '', 0, 1, 0, 0, '', '', 1, 2),
(6, 'custom', 'system', '多客服转接', '1.0.0', '用来接入腾讯的多客服系统', '', 'Weizancms Team', 'http://bbs.weizancms.com', 0, 'a:0:{}', 'a:6:{i:0;s:5:"image";i:1;s:5:"voice";i:2;s:5:"video";i:3;s:8:"location";i:4;s:4:"link";i:5;s:4:"text";}', 1, 1, 0, 0, '', '', 1, 2),
(7, 'images', 'system', '基本图片回复', '1.0', '提供图片回复', '在回复规则中可选择具有图片的回复内容，并根据用户所设置的特定关键字精准的返回给粉丝图片。', 'Weizancms Team', 'http://www.weizancms.com/', 0, '', '', 1, 1, 0, 0, '', '', 1, 2),
(8, 'video', 'system', '基本视频回复', '1.0', '提供图片回复', '在回复规则中可选择具有视频的回复内容，并根据用户所设置的特定关键字精准的返回给粉丝视频。', 'Weizancms Team', 'http://www.weizancms.com/', 0, '', '', 1, 1, 0, 0, '', '', 1, 2),
(9, 'voice', 'system', '基本语音回复', '1.0', '提供语音回复', '在回复规则中可选择具有语音的回复内容，并根据用户所设置的特定关键字精准的返回给粉丝语音。', 'Weizancms Team', 'http://www.weizancms.com/', 0, '', '', 1, 1, 0, 0, '', '', 1, 2),
(10, 'chats', 'system', '发送客服消息', '1.0', '公众号可以在粉丝最后发送消息的48小时内无限制发送消息', '', 'Weizancms Team', 'http://www.weizancms.com/', 0, '', '', 0, 1, 0, 0, '', '', 1, 2),
(11, 'wxcard', 'system', '微信卡券回复', '1.0', '微信卡券回复', '微信卡券回复', 'Weizancms Team', 'http://www.weizancms.com/', 0, '', '', 1, 1, 0, 0, '', '', 1, 2),
(14, 'we7_coupon', 'business', '系统卡券', '3.8', '微赞卡券', '微赞卡券', '微赞团队', '', 2, 'a:0:{}', 'a:0:{}', 0, 1, 0, 0, 'a:0:{}', 'X', 1, 2);
EOF;
$dat = array();
$dat['schemas'] = unserialize($schemas);
$dat['datas'] = $datas;
return $dat;