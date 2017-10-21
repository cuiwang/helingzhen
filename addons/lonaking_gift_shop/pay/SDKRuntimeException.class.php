<?php
/**
 * @author 木兰东 http://mulandong.duapp.com/
 * 本代码出自一位前辈之手，由于作者被封，无法与其取得联系，如有侵权，请联系本人 QQ 1214512299
 */
class  SDKRuntimeException extends Exception {
	public function errorMessage()
	{
		return $this->getMessage();
	}

}

?>