<?php

class Express { 
     
    function __construct(){ 
    } 
     
    /* 
     * 采集网页内容的方法 
     */ 
    private function getcontent($url){ 
        if(function_exists("file_get_contents")){ 
            $file_contents = file_get_contents($url); 
        }else{ 
            $ch = curl_init(); 
            $timeout = 5; 
            curl_setopt($ch, CURLOPT_URL, $url); 
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
            curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout); 
            $file_contents = curl_exec($ch); 
            curl_close($ch); 
        } 
        return $file_contents; 
    }
     
    /* 
     * 解析object成数组的方法 
     * @param $json 输入的object数组 
     * return $data 数组 
     */ 
    private function json_array($json){ 
        if($json){ 
            foreach ((array)$json as $k=>$v){ 
                $data[$k] = !is_string($v)?$this->json_array($v):$v; 
            } 
            return $data; 
        } 
    } 
     
    /* 
     * 返回$data array      快递数组 
     * @param $name         快递名称 
     */ 
    public  function getorder($type,$order){ 
        $result = $this->getcontent("https://www.kuaidi100.com/query?type={$type}&postid={$order}"); 
        $result = json_decode($result); 
        $data = $this->json_array($result); 
        return $data; 
    } 
} 

?>