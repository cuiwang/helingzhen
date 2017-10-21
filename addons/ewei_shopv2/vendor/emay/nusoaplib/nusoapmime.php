<?php
require_once 'Mail/mimeDecode.php';
require_once 'Mail/mimePart.php';

if (!(extension_loaded('soap'))) {
	class soapclientmime extends nusoap_client_mime
	{	}

}


?>