<?php
/**
 * MEEPO 米波现场
 *
 * 官网 http://meepo.com.cn 作者QQ 284099857
 */
global $_W,$_GPC;
$weid = $_W['uniacid'];
$rid = $_GPC['rid'];
$cateid = $_GPC['cateid'];
$filenames = $this->get_filenamesbydir(MODULE_ROOT.'/template/mobile/app/style/icon'); 
$styles = array();
if(!empty($filenames)){  
	foreach ($filenames as $value) {
		$jian = '';
		$jian = str_replace(MODULE_ROOT.'/template/mobile/app/style/icon/','',$value);
		//020d.jpg
		$jian = substr($jian,1,2);
		if(substr($jian,0,1)=='0'){
			$jian = str_replace('0','',$jian);
		}
		$styles[$jian] = str_replace(IA_ROOT,'..',$value);
		
	}
	sort($styles);
}

$the_style = pdo_fetchcolumn("SELECT `basic_style` FROM ".tablename($this->basic_config_table)." WHERE weid = :weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid));
$html = '';
$control = $_GPC['control'];
if($cateid!='meepo'){
	 if(!empty($styles)){
		 foreach($styles as $key=>$row){
			 $change_style = $this->createMobileUrl($control,array('rid'=>$rid,'basic_style'=>($key+1)));
			 if($the_style-$key==1){
				$html .= '<li><a href="'.$change_style.'" class="style-imgwrap active"><span class="style-select"></span><img class="style-img" src="'.$styles[$key].'">
					<p>风格'.($key+1).'</p>
				</a></li>';
			 }else{
				$html .= '<li><a href="'.$change_style.'" class="style-imgwrap"><span class="style-select"></span><img class="style-img" src="'.$styles[$key].'">
					<p>风格'.($key+1).'</p>
				</a></li>';
			 }
		 }
	 }
	 echo $html;
}else{
	echo '<li id="li0" class="curr"  ><a href="javascript:getmbhtml(0,this);">所有风格</a></li>';
}
                  
        /*<li id="li1" ><a href="javascript:getmbhtml(1,this);">节日风格</a></li>
      			  
        <li id="li8" ><a href="javascript:getmbhtml(8,this);">婚庆风格</a></li>
      			  
        <li id="li2" ><a href="javascript:getmbhtml(2,this);">红色风格</a></li>
      			  
        <li id="li3" ><a href="javascript:getmbhtml(3,this);">橙色风格</a></li>
      			  
        <li id="li4" ><a href="javascript:getmbhtml(4,this);">黄色风格</a></li>
      			  
        <li id="li5" ><a href="javascript:getmbhtml(5,this);">绿色风格</a></li>
      			  
        <li id="li6" ><a href="javascript:getmbhtml(6,this);">蓝色风格</a></li>
      			  
        <li id="li7" ><a href="javascript:getmbhtml(7,this);">紫色风格</a></li>
      			  
        <li id="li9" ><a href="javascript:getmbhtml(9,this);">其他风格</a></li>*/
      			             