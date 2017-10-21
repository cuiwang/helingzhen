<?php
class areaxml{
	public function createxml($array,$url){
		$citysall = explode(";", $array['citys']);
		if(is_array($citysall)){
			$dom2=new DOMDocument('1.0','utf-8');
			$path2=$url.'/limitarea.xml';
			@$dom2->load($path2);
			//清空
			$ads = $dom2->getElementsByTagName ('address');
			for($a=0;$a<$ads->length;$a++){
				$ad = $ads->item($a);
				$ad->parentNode->removeChild($ad);
			}
			//清空
			$address=$dom2->createElement('address');
			$dom2->appendChild($address);
			
			$path=IA_ROOT . '/addons/wz_tuan/template/amyarea.xml';
			$xml  =  simplexml_load_file ( $path );
			$provinces = $xml->province;
			
			for($i=0;$i<count($provinces);$i++){
				$province = $provinces[$i];
				foreach($citysall as $key=>$value){
					if($province['name']==$value){
						$shen=$dom2->createElement('province');
						$name1=$dom2->createAttribute('name');
						$name1->nodeValue=$province['name'];
						$shen->setAttributeNode($name1);
						$address->appendChild($shen);
						
						$citys = $province->city;
							for($j=0;$j<count($citys);$j++){
								$city = $citys[$j];
								
								$chen=$dom2->createElement('city'); 
								$name2=$dom2->createAttribute('name');
								$name2->nodeValue=$city['name'];
								$chen->setAttributeNode($name2);
								$shen->appendChild($chen);
								
									$countys = $city->county;
									for($k=0;$k<count($countys);$k++){
										$county = $countys[$k];
										$qu=$dom2->createElement('county');         
										$name3=$dom2->createAttribute('name');
										$name3->nodeValue=$county['name'];
										$qu->setAttributeNode($name3);
										$chen->appendChild($qu);
									}
							}
					}
				}
				
			}
			$dom2->save($path2);
		}
	}
}
?>