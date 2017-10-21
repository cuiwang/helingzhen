if(window.localStorage){
    console.log('This browser supports localStorage');
    var storage = window.localStorage;
    var is_strage = true;
    var is_storage = _gdata('is_stroage');
    // console.log(is_storage);
            // localStorage.clear();
    if(!is_storage){
        localStorage.clear();
         _sdata('is_stroage',"{'status_code':2}");
    }
    function _sdata(key,data){
      storage[key] = JSON.stringify(data);
    }
    function _gdata(key){
      if(storage[key]==''||storage[key]==null){
         return false;
      }else{
        return JSON.parse(storage[key]);
      }
    }

    function _clearItem(key){
      key = key.toString();
       storage.removeItem(key);
    }
     function jts(data){
       return JSON.stringify(data);
    }
}else{
    var is_strage = false;
    console.log('This browser does NOT support localStorage');
}
