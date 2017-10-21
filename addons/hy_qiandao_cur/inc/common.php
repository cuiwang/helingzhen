<?php
if (!defined("IN_IA")) {
    exit("Access Denied");
}  

class cls_common{

	public function __construct()
    {
        global $_W;	 
        
    }
	public function continue_day($cur_date,$day_list){   
		$continue_day = 1 ;//连续天数   
		//print_r($day_list);
		if(count($day_list) >= 1)    {      
			 for ($i=1; $i<=count($day_list); $i++)        {          
				if( ( abs(( strtotime($cur_date) - strtotime($day_list[$i-1]) ) / 86400)) == $i )            {           
					$continue_day = $i+1;         
				} else {        
					   break;        
				}      
			 }  
		}      
	   return $continue_day;    //输出连续几天
	}
	
	
	
	}




 