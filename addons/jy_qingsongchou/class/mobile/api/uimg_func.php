<?php



   if($action=='up'){
        $rchar = '';
        if(empty($_FILES['file'])){
          _fail(array('msg'=>'请上传文件'));
          return ;
        }
        $file = $_FILES['file'];
        $fileName = $file['name'];
        $filePath = $file['tmp_name'];
        $type = strtolower(substr($fileName,strrpos($fileName,'.')+1)); //得到文件类型，并且都转化成小写
        $allow_type = array('jpg','jpeg','gif','png'); //定义允许上传的类型
        if(!in_array($type, $allow_type)){
            _fail(array('msg'=>'上传格式有误'));
            return ;
        }

        $data= array(
           'name'=>$fileName,
           'tmp_name'=>$filePath,
           'type'=>$_FILES["file"]['type'],
           'size'=>$_FILES["file"]['size'],
           'error'=>$_FILES["file"]['error'],
           'status'=>2,
           'msg'=>$_FILES["file"]['name']
        );

        //
          $_conf = $this->__uploadImg($data);
        if($_conf['status']==1){
            unset($_conf['status']);
            _success(array('res'=>$_conf));
        }else {
             _fail(array('msg'=>'上传失败'));
        }
   }
   else if($action=='text'){
     $file = $_FILES['wangEditorH5File'];
     $fileName = $file['name'];
     $filePath = $file['tmp_name'];
     $type = strtolower(substr($fileName,strrpos($fileName,'.')+1)); //得到文件类型，并且都转化成小写
     $allow_type = array('jpg','jpeg','gif','png'); //定义允许上传的类型
     if(!in_array($type, $allow_type)){
         _fail(array('msg'=>$fileName));
         return ;
     }
     $data= array(
        'name'=>$fileName,
        'tmp_name'=>$filePath,
        'type'=>$_FILES['type'],
        'size'=>$_FILES['size'],
        'error'=>$_FILES['error'],
        'status'=>2,
        'msg'=>$_FILES['name']
     );
     $_conf = $this->__uploadImg($data);
     if($_conf['status']==1){
         unset($_conf['status']);
        //  _success(array('res'=>$_conf));
        echo $_conf['imgurl'];
        exit;
     }else {
          _fail(array('msg'=>'上传失败'));
     }


   }
   else{
       _fail(array('msg'=>'not found function'));
   }


 ?>
