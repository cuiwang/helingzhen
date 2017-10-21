<?php
	class get_head_imgs{
		public function get_head_img($url,$num){
			$imgs_array = array();
			$random_array = array();
			$files=array(); 
			if ($handle=opendir($url)) { 
				while(false !== ($file = readdir($handle))) { 
				    if ($file != "." && $file != "..") { 
					    if(substr($file,-3)=='gif' || substr($file,-3)=='jpg') $files[count($files)] = $file; 
					    } 
				} 
			} 
			closedir($handle); 
			for($i=0;$i<$num;$i++){
				$random=rand(0,count($files)-1);
				while(in_array($random, $random_array)){
					$random=rand(0,count($files)-1);
				} 
				$random_array[$i] = $random;
				$imgs_url = $url."/".$files[$random];
				$imgs_array[$i] = $imgs_url;
				
			}
			return $imgs_array;
		}
	}
?>