<?php
$GLOBALS['_transient']['static']['nusoap_base']['globalDebugLevel'] = 9;
class nusoap_base
{
	/**
	 * Identification for HTTP headers.
	 *
	 * @var string
	 * @access private
	 */
	public $title = 'NuSOAP';
	/**
	 * Version for HTTP headers.
	 *
	 * @var string
	 * @access private
	 */
	public $version = '0.9.5';
	/**
	 * CVS revision for HTTP headers.
	 *
	 * @var string
	 * @access private
	 */
	public $revision = '$Revision: 1.56 $';
	/**
     * Current error string (manipulated by getError/setError)
	 *
	 * @var string
	 * @access private
	 */
	public $error_str = '';
	/**
     * Current debug string (manipulated by debug/appendDebug/clearDebug/getDebug/getDebugAsXMLComment)
	 *
	 * @var string
	 * @access private
	 */
	public $debug_str = '';
	/**
	 * toggles automatic encoding of special characters as entities
	 * (should always be true, I think)
	 *
	 * @var boolean
	 * @access private
	 */
	public $charencoding = true;
	/**
	 * the debug level for this instance
	 *
	 * @var	integer
	 * @access private
	 */
	public $debugLevel;
	/**
	* set schema version
	*
	* @var      string
	* @access   public
	*/
	public $XMLSchemaVersion = 'http://www.w3.org/2001/XMLSchema';
	/**
	* charset encoding for outgoing messages
	*
	* @var      string
	* @access   public
	*/
	public $soap_defencoding = 'ISO-8859-1';
	/**
	* namespaces in an array of prefix => uri
	*
	* this is "seeded" by a set of constants, but it may be altered by code
	*
	* @var      array
	* @access   public
	*/
	public $namespaces = array('SOAP-ENV' => 'http://schemas.xmlsoap.org/soap/envelope/', 'xsd' => 'http://www.w3.org/2001/XMLSchema', 'xsi' => 'http://www.w3.org/2001/XMLSchema-instance', 'SOAP-ENC' => 'http://schemas.xmlsoap.org/soap/encoding/');
	/**
	* namespaces used in the current context, e.g. during serialization
	*
	* @var      array
	* @access   private
	*/
	public $usedNamespaces = array();
	/**
	* XML Schema types in an array of uri => (array of xml type => php type)
	* is this legacy yet?
	* no, this is used by the nusoap_xmlschema class to verify type => namespace mappings.
	* @var      array
	* @access   public
	*/
	public $typemap = array(
		'http://www.w3.org/2001/XMLSchema'          => array('string' => 'string', 'boolean' => 'boolean', 'float' => 'double', 'double' => 'double', 'decimal' => 'double', 'duration' => '', 'dateTime' => 'string', 'time' => 'string', 'date' => 'string', 'gYearMonth' => '', 'gYear' => '', 'gMonthDay' => '', 'gDay' => '', 'gMonth' => '', 'hexBinary' => 'string', 'base64Binary' => 'string', 'anyType' => 'string', 'anySimpleType' => 'string', 'normalizedString' => 'string', 'token' => 'string', 'language' => '', 'NMTOKEN' => '', 'NMTOKENS' => '', 'Name' => '', 'NCName' => '', 'ID' => '', 'IDREF' => '', 'IDREFS' => '', 'ENTITY' => '', 'ENTITIES' => '', 'integer' => 'integer', 'nonPositiveInteger' => 'integer', 'negativeInteger' => 'integer', 'long' => 'integer', 'int' => 'integer', 'short' => 'integer', 'byte' => 'integer', 'nonNegativeInteger' => 'integer', 'unsignedLong' => '', 'unsignedInt' => '', 'unsignedShort' => '', 'unsignedByte' => '', 'positiveInteger' => ''),
		'http://www.w3.org/2000/10/XMLSchema'       => array('i4' => '', 'int' => 'integer', 'boolean' => 'boolean', 'string' => 'string', 'double' => 'double', 'float' => 'double', 'dateTime' => 'string', 'timeInstant' => 'string', 'base64Binary' => 'string', 'base64' => 'string', 'ur-type' => 'array'),
		'http://www.w3.org/1999/XMLSchema'          => array('i4' => '', 'int' => 'integer', 'boolean' => 'boolean', 'string' => 'string', 'double' => 'double', 'float' => 'double', 'dateTime' => 'string', 'timeInstant' => 'string', 'base64Binary' => 'string', 'base64' => 'string', 'ur-type' => 'array'),
		'http://soapinterop.org/xsd'                => array('SOAPStruct' => 'struct'),
		'http://schemas.xmlsoap.org/soap/encoding/' => array('base64' => 'string', 'array' => 'array', 'Array' => 'array'),
		'http://xml.apache.org/xml-soap'            => array('Map')
		);
	/**
	* XML entities to convert
	*
	* @var      array
	* @access   public
	* @deprecated
	* @see	expandEntities
	*/
	public $xmlEntities = array('quot' => '"', 'amp' => '&', 'lt' => '<', 'gt' => '>', 'apos' => '\'');

	/**
	* constructor
	*
	* @access	public
	*/
	public function nusoap_base()
	{
		$this->debugLevel = $GLOBALS['_transient']['static']['nusoap_base']['globalDebugLevel'];
	}

	/**
	* gets the global debug level, which applies to future instances
	*
	* @return	integer	Debug level 0-9, where 0 turns off
	* @access	public
	*/
	public function getGlobalDebugLevel()
	{
		return $GLOBALS['_transient']['static']['nusoap_base']['globalDebugLevel'];
	}

	/**
	* sets the global debug level, which applies to future instances
	*
	* @param	int	$level	Debug level 0-9, where 0 turns off
	* @access	public
	*/
	public function setGlobalDebugLevel($level)
	{
		$GLOBALS['_transient']['static']['nusoap_base']['globalDebugLevel'] = $level;
	}

	/**
	* gets the debug level for this instance
	*
	* @return	int	Debug level 0-9, where 0 turns off
	* @access	public
	*/
	public function getDebugLevel()
	{
		return $this->debugLevel;
	}

	/**
	* sets the debug level for this instance
	*
	* @param	int	$level	Debug level 0-9, where 0 turns off
	* @access	public
	*/
	public function setDebugLevel($level)
	{
		$this->debugLevel = $level;
	}

	/**
	* adds debug data to the instance debug string with formatting
	*
	* @param    string $string debug data
	* @access   private
	*/
	public function debug($string)
	{
		if (0 < $this->debugLevel) {
			$this->appendDebug($this->getmicrotime() . ' ' . get_class($this) . ': ' . $string . "\n");
		}

	}

	/**
	* adds debug data to the instance debug string without formatting
	*
	* @param    string $string debug data
	* @access   public
	*/
	public function appendDebug($string)
	{
		if (0 < $this->debugLevel) {
			$this->debug_str .= $string;
		}

	}

	/**
	* clears the current debug data for this instance
	*
	* @access   public
	*/
	public function clearDebug()
	{
		$this->debug_str = '';
	}

	/**
	* gets the current debug data for this instance
	*
	* @return   debug data
	* @access   public
	*/
	public function &getDebug()
	{
		return $this->debug_str;
	}

	/**
	* gets the current debug data for this instance as an XML comment
	* this may change the contents of the debug data
	*
	* @return   debug data as an XML comment
	* @access   public
	*/
	public function &getDebugAsXMLComment()
	{
		while (strpos($this->debug_str, '--')) {
			$this->debug_str = str_replace('--', '- -', $this->debug_str);
		}

		$ret = '<!--' . "\n" . $this->debug_str . "\n" . '-->';
		return $ret;
	}

	/**
	* expands entities, e.g. changes '<' to '&lt;'.
	*
	* @param	string	$val	The string in which to expand entities.
	* @access	private
	*/
	public function expandEntities($val)
	{
		if ($this->charencoding) {
			$val = str_replace('&', '&amp;', $val);
			$val = str_replace('\'', '&apos;', $val);
			$val = str_replace('"', '&quot;', $val);
			$val = str_replace('<', '&lt;', $val);
			$val = str_replace('>', '&gt;', $val);
		}


		return $val;
	}

	/**
	* returns error string if present
	*
	* @return   mixed error string or false
	* @access   public
	*/
	public function getError()
	{
		if ($this->error_str != '') {
			return $this->error_str;
		}


		return false;
	}

	/**
	* sets error string
	*
	* @return   boolean $string error string
	* @access   private
	*/
	public function setError($str)
	{
		$this->error_str = $str;
	}

	/**
	* detect if array is a simple array or a struct (associative array)
	*
	* @param	mixed	$val	The PHP array
	* @return	string	(arraySimple|arrayStruct)
	* @access	private
	*/
	public function isArraySimpleOrStruct($val)
	{
		$keyList = array_keys($val);

		foreach ($keyList as $keyListValue ) {
			if (!(is_int($keyListValue))) {
				return 'arrayStruct';
			}
		}

		return 'arraySimple';
	}

	/**
	* serializes PHP values in accordance w/ section 5. Type information is
	* not serialized if $use == 'literal'.
	*
	* @param	mixed	$val	The value to serialize
	* @param	string	$name	The name (local part) of the XML element
	* @param	string	$type	The XML schema type (local part) for the element
	* @param	string	$name_ns	The namespace for the name of the XML element
	* @param	string	$type_ns	The namespace for the type of the element
	* @param	array	$attributes	The attributes to serialize as name=>value pairs
	* @param	string	$use	The WSDL "use" (encoded|literal)
	* @param	boolean	$soapval	Whether this is called from soapval.
	* @return	string	The serialized element, possibly with child elements
    * @access	public
	*/
	public function serialize_val($val, $name = false, $type = false, $name_ns = false, $type_ns = false, $attributes = false, $use = 'encoded', $soapval = false)
	{
		$this->debug('in serialize_val: name=' . $name . ', type=' . $type . ', name_ns=' . $name_ns . ', type_ns=' . $type_ns . ', use=' . $use . ', soapval=' . $soapval);
		$this->appendDebug('value=' . $this->varDump($val));
		$this->appendDebug('attributes=' . $this->varDump($attributes));
		if (is_object($val) && (get_class($val) == 'soapval') && !($soapval)) {
			$this->debug('serialize_val: serialize soapval');
			$xml = $val->serialize($use);
			$this->appendDebug($val->getDebug());
			$val->clearDebug();
			$this->debug('serialize_val of soapval returning ' . $xml);
			return $xml;
		}


		if (is_numeric($name)) {
			$name = '__numeric_' . $name;
		}
		 else if (!($name)) {
			$name = 'noname';
		}


		$xmlns = '';

		if ($name_ns) {
			$prefix = 'nu' . rand(1000, 9999);
			$name = $prefix . ':' . $name;
			$xmlns .= ' xmlns:' . $prefix . '="' . $name_ns . '"';
		}


		if (($type_ns != '') && ($type_ns == $this->namespaces['xsd'])) {
			$type_prefix = 'xsd';
		}
		 else if ($type_ns) {
			$type_prefix = 'ns' . rand(1000, 9999);
			$xmlns .= ' xmlns:' . $type_prefix . '="' . $type_ns . '"';
		}


		$atts = '';

		if ($attributes) {
			foreach ($attributes as $k => $v ) {
				$atts .= ' ' . $k . '="' . $this->expandEntities($v) . '"';
			}
		}


		if (is_null($val)) {
			$this->debug('serialize_val: serialize null');

			if ($use == 'literal') {
				$xml = '<' . $name . $xmlns . $atts . '/>';
				$this->debug('serialize_val returning ' . $xml);
				return $xml;
			}


			if (isset($type) && isset($type_prefix)) {
				$type_str = ' xsi:type="' . $type_prefix . ':' . $type . '"';
			}
			 else {
				$type_str = '';
			}

			$xml = '<' . $name . $xmlns . $type_str . $atts . ' xsi:nil="true"/>';
			$this->debug('serialize_val returning ' . $xml);
			return $xml;
		}


		if (($type != '') && isset($this->typemap[$this->XMLSchemaVersion][$type])) {
			$this->debug('serialize_val: serialize xsd built-in primitive type');

			if (is_bool($val)) {
				if ($type == 'boolean') {
					$val = (($val ? 'true' : 'false'));
				}
				 else if (!($val)) {
					$val = 0;
				}

			}
			 else if (is_string($val)) {
				$val = $this->expandEntities($val);
			}


			if ($use == 'literal') {
				$xml = '<' . $name . $xmlns . $atts . '>' . $val . '</' . $name . '>';
				$this->debug('serialize_val returning ' . $xml);
				return $xml;
			}


			$xml = '<' . $name . $xmlns . ' xsi:type="xsd:' . $type . '"' . $atts . '>' . $val . '</' . $name . '>';
			$this->debug('serialize_val returning ' . $xml);
			return $xml;
		}


		$xml = '';
		switch (true) {
		case is_bool($val) || ($type == 'boolean'):
			$this->debug('serialize_val: serialize boolean');

			if ($type == 'boolean') {
				$val = (($val ? 'true' : 'false'));
			}
			 else if (!($val)) {
				$val = 0;
			}


			if ($use == 'literal') {
				$xml .= '<' . $name . $xmlns . $atts . '>' . $val . '</' . $name . '>';
			}
			 else {
				$xml .= '<' . $name . $xmlns . ' xsi:type="xsd:boolean"' . $atts . '>' . $val . '</' . $name . '>';
			}

			break;

		default:
			switch (true) {
			case is_int($val) || is_long($val) || ($type == 'int'):
				$this->debug('serialize_val: serialize int');

				if ($use == 'literal') {
					$xml .= '<' . $name . $xmlns . $atts . '>' . $val . '</' . $name . '>';
				}
				 else {
					$xml .= '<' . $name . $xmlns . ' xsi:type="xsd:int"' . $atts . '>' . $val . '</' . $name . '>';
				}

				break;

			default:
				switch (true) {
				case is_float($val) || is_double($val) || ($type == 'float'):
					$this->debug('serialize_val: serialize float');

					if ($use == 'literal') {
						$xml .= '<' . $name . $xmlns . $atts . '>' . $val . '</' . $name . '>';
					}
					 else {
						$xml .= '<' . $name . $xmlns . ' xsi:type="xsd:float"' . $atts . '>' . $val . '</' . $name . '>';
					}

					break;

				default:
					switch (true) {
					case is_string($val) || ($type == 'string'):
						$this->debug('serialize_val: serialize string');
						$val = $this->expandEntities($val);

						if ($use == 'literal') {
							$xml .= '<' . $name . $xmlns . $atts . '>' . $val . '</' . $name . '>';
						}
						 else {
							$xml .= '<' . $name . $xmlns . ' xsi:type="xsd:string"' . $atts . '>' . $val . '</' . $name . '>';
						}

						break;

					default:
						switch (true) {
						case is_object($val):
							$this->debug('serialize_val: serialize object');

							if (get_class($val) == 'soapval') {
								$this->debug('serialize_val: serialize soapval object');
								$pXml = $val->serialize($use);
								$this->appendDebug($val->getDebug());
								$val->clearDebug();
							}
							 else {
								if (!($name)) {
									$name = get_class($val);
									$this->debug('In serialize_val, used class name ' . $name . ' as element name');
								}
								 else {
									$this->debug('In serialize_val, do not override name ' . $name . ' for element name for class ' . get_class($val));
								}

								foreach (get_object_vars($val) as $k => $v ) {
									$pXml = ((isset($pXml) ? $pXml . $this->serialize_val($v, $k, false, false, false, false, $use) : $this->serialize_val($v, $k, false, false, false, false, $use)));
								}
							}

							if (isset($type) && isset($type_prefix)) {
								$type_str = ' xsi:type="' . $type_prefix . ':' . $type . '"';
							}
							 else {
								$type_str = '';
							}

							if ($use == 'literal') {
								$xml .= '<' . $name . $xmlns . $atts . '>' . $pXml . '</' . $name . '>';
							}
							 else {
								$xml .= '<' . $name . $xmlns . $type_str . $atts . '>' . $pXml . '</' . $name . '>';
							}

							break;

						default:
							switch (true) {
							case is_array($val) || $type:
								$valueType = $this->isArraySimpleOrStruct($val);
								if (($valueType == 'arraySimple') || preg_match('/^ArrayOf/', $type)) {
									$this->debug('serialize_val: serialize array');
									$i = 0;
									if (is_array($val) && (0 < count($val))) {
										foreach ($val as $v ) {
											if (is_object($v) && (get_class($v) == 'soapval')) {
												$tt_ns = $v->type_ns;
												$tt = $v->type;
											}
											 else if (is_array($v)) {
												$tt = $this->isArraySimpleOrStruct($v);
											}
											 else {
												$tt = gettype($v);
											}

											$array_types[$tt] = 1;
											$xml .= $this->serialize_val($v, 'item', false, false, false, false, $use);
											++$i;
										}

										if (1 < count($array_types)) {
											$array_typename = 'xsd:anyType';
										}
										 else if (isset($tt) && isset($this->typemap[$this->XMLSchemaVersion][$tt])) {
											if ($tt == 'integer') {
												$tt = 'int';
											}


											$array_typename = 'xsd:' . $tt;
										}
										 else if (isset($tt) && ($tt == 'arraySimple')) {
											$array_typename = 'SOAP-ENC:Array';
										}
										 else if (isset($tt) && ($tt == 'arrayStruct')) {
											$array_typename = 'unnamed_struct_use_soapval';
										}
										 else if (($tt_ns != '') && ($tt_ns == $this->namespaces['xsd'])) {
											$array_typename = 'xsd:' . $tt;
										}
										 else if ($tt_ns) {
											$tt_prefix = 'ns' . rand(1000, 9999);
											$array_typename = $tt_prefix . ':' . $tt;
											$xmlns .= ' xmlns:' . $tt_prefix . '="' . $tt_ns . '"';
										}
										 else {
											$array_typename = $tt;
										}

										$array_type = $i;

										if ($use == 'literal') {
											$type_str = '';
										}
										 else if (isset($type) && isset($type_prefix)) {
											$type_str = ' xsi:type="' . $type_prefix . ':' . $type . '"';
										}
										 else {
											$type_str = ' xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="' . $array_typename . '[' . $array_type . ']"';
										}
									}
									 else if ($use == 'literal') {
										$type_str = '';
									}
									 else if (isset($type) && isset($type_prefix)) {
										$type_str = ' xsi:type="' . $type_prefix . ':' . $type . '"';
									}
									 else {
										$type_str = ' xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="xsd:anyType[0]"';
									}

									$xml = '<' . $name . $xmlns . $type_str . $atts . '>' . $xml . '</' . $name . '>';
								}
								 else {
									$this->debug('serialize_val: serialize struct');

									if (isset($type) && isset($type_prefix)) {
										$type_str = ' xsi:type="' . $type_prefix . ':' . $type . '"';
									}
									 else {
										$type_str = '';
									}

									if ($use == 'literal') {
										$xml .= '<' . $name . $xmlns . $atts . '>';
									}
									 else {
										$xml .= '<' . $name . $xmlns . $type_str . $atts . '>';
									}

									foreach ($val as $k => $v ) {
										if (($type == 'Map') && ($type_ns == 'http://xml.apache.org/xml-soap')) {
											$xml .= '<item>';
											$xml .= $this->serialize_val($k, 'key', false, false, false, false, $use);
											$xml .= $this->serialize_val($v, 'value', false, false, false, false, $use);
											$xml .= '</item>';
										}
										 else {
											$xml .= $this->serialize_val($v, $k, false, false, false, false, $use);
										}
									}

									$xml .= '</' . $name . '>';
								}

								break;

								$this->debug('serialize_val: serialize unknown');
								$xml .= 'not detected, got ' . gettype($val) . ' for ' . $val;
							}
						}
					}
				}
			}
		}
	}

	/**
    * serializes a message
    *
    * @param string $body the XML of the SOAP body
    * @param mixed $headers optional string of XML with SOAP header content, or array of soapval objects for SOAP headers, or associative array
    * @param array $namespaces optional the namespaces used in generating the body and headers
    * @param string $style optional (rpc|document)
    * @param string $use optional (encoded|literal)
    * @param string $encodingStyle optional (usually 'http://schemas.xmlsoap.org/soap/encoding/' for encoded)
    * @return string the message
    * @access public
    */
	public function serializeEnvelope($body, $headers = false, $namespaces = array(), $style = 'rpc', $use = 'encoded', $encodingStyle = 'http://schemas.xmlsoap.org/soap/encoding/')
	{
		$this->debug('In serializeEnvelope length=' . strlen($body) . ' body (max 1000 characters)=' . substr($body, 0, 1000) . ' style=' . $style . ' use=' . $use . ' encodingStyle=' . $encodingStyle);
		$this->debug('headers:');
		$this->appendDebug($this->varDump($headers));
		$this->debug('namespaces:');
		$this->appendDebug($this->varDump($namespaces));
		$ns_string = '';

		foreach (array_merge($this->namespaces, $namespaces) as $k => $v ) {
			$ns_string .= ' xmlns:' . $k . '="' . $v . '"';
		}

		if ($encodingStyle) {
			$ns_string = ' SOAP-ENV:encodingStyle="' . $encodingStyle . '"' . $ns_string;
		}
		if ($headers) {
			if (is_array($headers)) {
				$xml = '';

				foreach ($headers as $k => $v ) {
					if (is_object($v) && (get_class($v) == 'soapval')) {
						$xml .= $this->serialize_val($v, false, false, false, false, false, $use);
					}
					 else {
						$xml .= $this->serialize_val($v, $k, false, false, false, false, $use);
					}
				}

				$headers = $xml;
				$this->debug('In serializeEnvelope, serialized array of headers to ' . $headers);
			}


			$headers = '<SOAP-ENV:Header>' . $headers . '</SOAP-ENV:Header>';
		}


		return '<?xml version="1.0" encoding="' . $this->soap_defencoding . '"?' . '>' . '<SOAP-ENV:Envelope' . $ns_string . '>' . $headers . '<SOAP-ENV:Body>' . $body . '</SOAP-ENV:Body>' . '</SOAP-ENV:Envelope>';
	}

	/**
	 * formats a string to be inserted into an HTML stream
	 *
	 * @param string $str The string to format
	 * @return string The formatted string
	 * @access public
	 * @deprecated
	 */
	public function formatDump($str)
	{
		$str = htmlspecialchars($str);
		return nl2br($str);
	}

	/**
	* contracts (changes namespace to prefix) a qualified name
	*
	* @param    string $qname qname
	* @return	string contracted qname
	* @access   private
	*/
	public function contractQname($qname)
	{
		if (strrpos($qname, ':')) {
			$name = substr($qname, strrpos($qname, ':') + 1);
			$ns = substr($qname, 0, strrpos($qname, ':'));
			$p = $this->getPrefixFromNamespace($ns);

			if ($p) {
				return $p . ':' . $name;
			}


			return $qname;
		}


		return $qname;
	}

	/**
	* expands (changes prefix to namespace) a qualified name
	*
	* @param    string $qname qname
	* @return	string expanded qname
	* @access   private
	*/
	public function expandQname($qname)
	{
		if (strpos($qname, ':') && !(preg_match('/^http:\\/\\//', $qname))) {
			$name = substr(strstr($qname, ':'), 1);
			$prefix = substr($qname, 0, strpos($qname, ':'));

			if (isset($this->namespaces[$prefix])) {
				return $this->namespaces[$prefix] . ':' . $name;
			}


			return $qname;
		}


		return $qname;
	}

	/**
    * returns the local part of a prefixed string
    * returns the original string, if not prefixed
    *
    * @param string $str The prefixed string
    * @return string The local part
    * @access public
    */
	public function getLocalPart($str)
	{
		if ($sstr = strrchr($str, ':')) {
			return substr($sstr, 1);
		}


		return $str;
	}

	/**
    * returns the prefix part of a prefixed string
    * returns false, if not prefixed
    *
    * @param string $str The prefixed string
    * @return mixed The prefix or false if there is no prefix
    * @access public
    */
	public function getPrefix($str)
	{
		if ($pos = strrpos($str, ':')) {
			return substr($str, 0, $pos);
		}


		return false;
	}

	/**
    * pass it a prefix, it returns a namespace
    *
    * @param string $prefix The prefix
    * @return mixed The namespace, false if no namespace has the specified prefix
    * @access public
    */
	public function getNamespaceFromPrefix($prefix)
	{
		if (isset($this->namespaces[$prefix])) {
			return $this->namespaces[$prefix];
		}


		return false;
	}

	/**
    * returns the prefix for a given namespace (or prefix)
    * or false if no prefixes registered for the given namespace
    *
    * @param string $ns The namespace
    * @return mixed The prefix, false if the namespace has no prefixes
    * @access public
    */
	public function getPrefixFromNamespace($ns)
	{
		foreach ($this->namespaces as $p => $n ) {
			if (($ns == $n) || ($ns == $p)) {
				$this->usedNamespaces[$p] = $n;

				return $p;
			}

		}

		return false;
	}

	/**
    * returns the time in ODBC canonical form with microseconds
    *
    * @return string The time in ODBC canonical form with microseconds
    * @access public
    */
	public function getmicrotime()
	{
		if (function_exists('gettimeofday')) {
			$tod = gettimeofday();
			$sec = $tod['sec'];
			$usec = $tod['usec'];
		}
		 else {
			$sec = time();
			$usec = 0;
		}

		return strftime('%Y-%m-%d %H:%M:%S', $sec) . '.' . sprintf('%06d', $usec);
	}

	/**
	 * Returns a string with the output of var_dump
	 *
	 * @param mixed $data The variable to var_dump
	 * @return string The output of var_dump
	 * @access public
	 */
	public function varDump($data)
	{
		ob_start();
		var_dump($data);
		$ret_val = ob_get_contents();
		ob_end_clean();
		return $ret_val;
	}

	/**
	* represents the object as a string
	*
	* @return	string
	* @access   public
	*/
	public function __toString()
	{
		return $this->varDump($this);
	}
}

/**
* convert unix timestamp to ISO 8601 compliant date string
*
* @param    int $timestamp Unix time stamp
* @param	boolean $utc Whether the time stamp is UTC or local
* @return	mixed ISO 8601 date string or false
* @access   public
*/
function timestamp_to_iso8601($timestamp, $utc = true)
{
	$datestr = date('Y-m-d\\TH:i:sO', $timestamp);
	$pos = strrpos($datestr, '+');

	if ($pos === false) {
		$pos = strrpos($datestr, '-');
	}


	if ($pos !== false) {
		if (strlen($datestr) == $pos + 5) {
			$datestr = substr($datestr, 0, $pos + 3) . ':' . substr($datestr, -2);
		}
	}
	if ($utc) {
		$pattern = '/' . '([0-9]{4})-' . '([0-9]{2})-' . '([0-9]{2})' . 'T' . '([0-9]{2}):' . '([0-9]{2}):' . '([0-9]{2})(\\.[0-9]*)?' . '(Z|[+\\-][0-9]{2}:?[0-9]{2})?' . '/';

		if (preg_match($pattern, $datestr, $regs)) {
			return sprintf('%04d-%02d-%02dT%02d:%02d:%02dZ', $regs[1], $regs[2], $regs[3], $regs[4], $regs[5], $regs[6]);
		}


		return false;
	}


	return $datestr;
}

/**
* convert ISO 8601 compliant date string to unix timestamp
*
* @param    string $datestr ISO 8601 compliant date string
* @return	mixed Unix timestamp (int) or false
* @access   public
*/
function iso8601_to_timestamp($datestr)
{
	$pattern = '/' . '([0-9]{4})-' . '([0-9]{2})-' . '([0-9]{2})' . 'T' . '([0-9]{2}):' . '([0-9]{2}):' . '([0-9]{2})(\\.[0-9]+)?' . '(Z|[+\\-][0-9]{2}:?[0-9]{2})?' . '/';

	if (preg_match($pattern, $datestr, $regs)) {
		if ($regs[8] != 'Z') {
			$op = substr($regs[8], 0, 1);
			$h = substr($regs[8], 1, 2);
			$m = substr($regs[8], strlen($regs[8]) - 2, 2);

			if ($op == '-') {
				$regs[4] = $regs[4] + $h;
				$regs[5] = $regs[5] + $m;
			}
			 else if ($op == '+') {
				$regs[4] = $regs[4] - $h;
				$regs[5] = $regs[5] - $m;
			}

		}


		return gmmktime($regs[4], $regs[5], $regs[6], $regs[2], $regs[3], $regs[1]);
	}


	return false;
}

/**
* sleeps some number of microseconds
*
* @param    string $usec the number of microseconds to sleep
* @access   public
* @deprecated
*/
function usleepWindows($usec)
{
	$start = gettimeofday();

	do {
		$stop = gettimeofday();
		$timePassed = ((1000000 * ($stop['sec'] - $start['sec'])) + $stop['usec']) - $start['usec'];
	} while ($timePassed < $usec);
}


?>