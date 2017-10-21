<?php
/**
 * 钻石投票-批量上传
 *
 */

defined('IN_IA') or exit('Access Denied');
//require TYZM_MODEL_FUNC.'/uploads.php';
global $_W,$_GPC;
$op=$_GPC['op'];



 if ($op == 'uploads') {
    
     if (checksubmit('submit')){

        $tmp_file = $_FILES['file_name']['name'];  //上传压缩包名称
        if (empty($tmp_file)) {
			message('请选择文件上传');
		}
	    $file_type = $file_types [count ( $file_types ) - 1];
	    //设置上传路径
	    $savedir = '/addons/tyzm_diamondvote/file/uploads/';
	    $savePath = IA_ROOT.$savefile;
	    
		 
	     if (file_exists($savePath) == true) {
	          //清空解压文件
	          $this->deldir($savePath);
	     }
	     mkdirs(dirname($savePath));   //创建解压目录
     
     		$type_wj = pathinfo($tmp_file, PATHINFO_EXTENSION); //获取文件类型
	     //判断文件类型
	     //
	     if(strtolower($type_wj) == "zip" || strtolower($type_wj) == "rar"){
	                // if(strtolower($type_wj) == "zip"){
	                //     //解压zip文件
	                //     unzip_file($tmp_file,$savePath); 
	                // }else{
	                //     //解压rar文件
	                //     unrar($tmp_file,$savePath);
	                // }
	                // //获取解压后的文件
	                // $array_file = loopFun($savePath);
	                // $wj_count = count($array_file);
	                // //判断上传文件个数，上传文件不能多于10个
	                // if ($wj_count > 100) {
	                //     //清空解压文件
	                //     del_dir($savePath);
	                //     message('上传文件多于100个！','', 'error');
	                // }
	                // //文件上传提交
	                // if (!empty($array_file)) {
	                //     // foreach ($array_file as $k => $v) {
	                //     //    //此处就使用tp的上传或者自己的上传方法……
	                //     // }
	                //     print_r($array_file);
	                    
	                // }else{
	                //     message('压缩包为空','', 'error');
	                // }
	       }else{
	              //其他格式的文件根据自己实际情况上传
	       }



	}
    include $this->template('uploadsvote');
 }


if ($op == 'list') {


 }



    function loopFun($dir)  
    {  
        $handle = opendir($dir.".");
        //定义用于存储文件名的数组
        $array_file = array();
        while (false !== ($file = readdir($handle)))
        {
            if ($file != "." && $file != "..") {
                $array_file[] = $dir.'/'.$file; //输出文件名
            }
        }
        closedir($handle);
        return $array_file;
    }
       
  function unzip_file($file, $dir){ 
        // 实例化对象 
        $zip = new ZipArchive() ; 
        //打开zip文档，如果打开失败返回提示信息 
        if ($zip->open($file) !== TRUE) { 
          die ("Could not open archive"); 
        } 
        //将压缩文件解压到指定的目录下 
        $zip->extractTo($dir); 
        //关闭zip文档 
        $zip->close();  
    } 

    function deldir($dir){
        exec('rd /s /q '.$dir);
    }