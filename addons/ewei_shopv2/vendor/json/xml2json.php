<?php
require_once 'JSON.php';
define('DEBUG', false);
define('MAX_RECURSION_DEPTH_ALLOWED', 25);
define('EMPTY_STR', '');
define('SIMPLE_XML_ELEMENT_OBJECT_PROPERTY_FOR_ATTRIBUTES', '@attributes');
define('SIMPLE_XML_ELEMENT_PHP_CLASS', 'SimpleXMLElement');
class xml2json
{
	static public function transformXmlStringToJson($xmlStringContents)
	{
		$simpleXmlElementObject = simplexml_load_string($xmlStringContents);

		if ($simpleXmlElementObject == NULL) {
			return EMPTY_STR;
		}


		$simpleXmlRootElementName = $simpleXmlElementObject->getName();

		if (DEBUG) {
		}


		$jsonOutput = EMPTY_STR;
		$array1 = xml2json::convertSimpleXmlElementObjectIntoArray($simpleXmlElementObject);

		if (($array1 != NULL) && (0 < sizeof($array1))) {
			$json = new Services_JSON();
			$jsonOutput = $json->encode($array1);

			if (DEBUG) {
			}

		}


		return $jsonOutput;
	}

	static public function convertSimpleXmlElementObjectIntoArray($simpleXmlElementObject, &$recursionDepth = 0)
	{
		if (MAX_RECURSION_DEPTH_ALLOWED < $recursionDepth) {
			return;
		}


		if ($recursionDepth == 0) {
			if (get_class($simpleXmlElementObject) != SIMPLE_XML_ELEMENT_PHP_CLASS) {
				return;
			}


			$callerProvidedSimpleXmlElementObject = $simpleXmlElementObject;
		}


		if (@get_class($simpleXmlElementObject) == SIMPLE_XML_ELEMENT_PHP_CLASS) {
			$copyOfsimpleXmlElementObject = $simpleXmlElementObject;
			$simpleXmlElementObject = get_object_vars($simpleXmlElementObject);
		}


		if (is_array($simpleXmlElementObject)) {
			$resultArray = array();

			if (count($simpleXmlElementObject) <= 0) {
				return trim(strval($copyOfsimpleXmlElementObject));
			}


			foreach ($simpleXmlElementObject as $key => $value ) {
				++$recursionDepth;
				$resultArray[$key] = xml2json::convertSimpleXmlElementObjectIntoArray($value, $recursionDepth);
				--$recursionDepth;
			}

			if ($recursionDepth == 0) {
				$tempArray = $resultArray;
				$resultArray = array();
				$resultArray[$callerProvidedSimpleXmlElementObject->getName()] = $tempArray;
			}


			return $resultArray;
		}


		return trim(strval($simpleXmlElementObject));
	}
}


?>