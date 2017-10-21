<?php
	class get_nicknames{
		public function get_nickname($filename,$num){
			$nickname_array = array();
			$random_array = array();
			$file_new=fopen($filename,"r");
			$file_read = fread($file_new, filesize($filename)); 
			fclose($file_new);
			$arr_new = unserialize($file_read);
			for($i=0;$i<$num;$i++){
				$random=rand(0,count($arr_new)-1);
				while(in_array($random, $random_array)){
					$random=rand(0,count($arr_new)-1);
				} 
				$random_array[$i] = $random;
				$nickname = $arr_new[$random];
				$nickname_array[$i] = $nickname;
				
			}
			return $nickname_array;
		}
		public function get_randtime($inittime=0,$now=0,$num){
			$randtime_array = array();
			$random_array = array();
			$max = $now - $inittime;
			for($i=0;$i<$num;$i++){
				$random=intval($max/$num);
				$max =$max - $random;
				$randtime_array[$i] =$inittime + $random;
				
			}
			return $randtime_array;
		}
	}
?>