<?php
class nusoap_parser extends nusoap_base
{
	public $xml = '';
	public $xml_encoding = '';
	public $method = '';
	public $root_struct = '';
	public $root_struct_name = '';
	public $root_struct_namespace = '';
	public $root_header = '';
	public $document = '';
	public $status = '';
	public $position = 0;
	public $depth = 0;
	public $default_namespace = '';
	public $namespaces = array();
	public $message = array();
	public $parent = '';
	public $fault = false;
	public $fault_code = '';
	public $fault_str = '';
	public $fault_detail = '';
	public $depth_array = array();
	public $debug_flag = true;
	public $soapresponse;
	public $soapheader;
	public $responseHeaders = '';
	public $body_position = 0;
	public $ids = array();
	public $multirefs = array();
	public $decode_utf8 = true;

	/**
	* constructor that actually does the parsing
	*
	* @param    string $xml SOAP message
	* @param    string $encoding character encoding scheme of message
	* @param    string $method method for which XML is parsed (unused?)
	* @param    string $decode_utf8 whether to decode UTF-8 to ISO-8859-1
	* @access   public
	*/
	public function nusoap_parser($xml, $encoding = 'UTF-8', $method = '', $decode_utf8 = true)
	{
		parent::nusoap_base();
		$this->xml = $xml;
		$this->xml_encoding = $encoding;
		$this->method = $method;
		$this->decode_utf8 = $decode_utf8;

		if (!(empty($xml))) {
			$pos_xml = strpos($xml, '<?xml');

			if ($pos_xml !== false) {
				$xml_decl = substr($xml, $pos_xml, (strpos($xml, '?>', $pos_xml + 2) - $pos_xml) + 1);

				if (preg_match('/encoding=["\']([^"\']*)["\']/', $xml_decl, $res)) {
					$xml_encoding = $res[1];

					if (strtoupper($xml_encoding) != $encoding) {
						$err = 'Charset from HTTP Content-Type \'' . $encoding . '\' does not match encoding from XML declaration \'' . $xml_encoding . '\'';
						$this->debug($err);
						if (($encoding != 'ISO-8859-1') || (strtoupper($xml_encoding) != 'UTF-8')) {
							$this->setError($err);
							return;
							$this->debug('Charset from HTTP Content-Type matches encoding from XML declaration');
						}

					}
					 else {
						$this->debug('Charset from HTTP Content-Type matches encoding from XML declaration');
					}
				}
				 else {
					$this->debug('No encoding specified in XML declaration');
				}
			}
			 else {
				$this->debug('No XML declaration');
			}

			$this->debug('Entering nusoap_parser(), length=' . strlen($xml) . ', encoding=' . $encoding);
			$this->parser = xml_parser_create($this->xml_encoding);
			xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, 0);
			xml_parser_set_option($this->parser, XML_OPTION_TARGET_ENCODING, $this->xml_encoding);
			xml_set_object($this->parser, $this);
			xml_set_element_handler($this->parser, 'start_element', 'end_element');
			xml_set_character_data_handler($this->parser, 'character_data');

			if (!(xml_parse($this->parser, $xml, true))) {
				$err = sprintf('XML error parsing SOAP payload on line %d: %s', xml_get_current_line_number($this->parser), xml_error_string(xml_get_error_code($this->parser)));
				$this->debug($err);
				$this->debug('XML payload:' . "\n" . $xml);
				$this->setError($err);
			}
			 else {
				$this->debug('in nusoap_parser ctor, message:');
				$this->appendDebug($this->varDump($this->message));
				$this->debug('parsed successfully, found root struct: ' . $this->root_struct . ' of name ' . $this->root_struct_name);
				$this->soapresponse = $this->message[$this->root_struct]['result'];

				if (($this->root_header != '') && isset($this->message[$this->root_header]['result'])) {
					$this->soapheader = $this->message[$this->root_header]['result'];
				}


				if (0 < sizeof($this->multirefs)) {
					foreach ($this->multirefs as $id => $hrefs ) {
						$this->debug('resolving multirefs for id: ' . $id);
						$idVal = $this->buildVal($this->ids[$id]);
						if (is_array($idVal) && isset($idVal['!id'])) {
							unset($idVal['!id']);
						}


						foreach ($hrefs as $refPos => $ref ) {
							$this->debug('resolving href at pos ' . $refPos);
							$this->multirefs[$id][$refPos] = $idVal;
						}
					}
				}

			}

			xml_parser_free($this->parser);
			return;
		}


		$this->debug('xml was empty, didn\'t parse!');
		$this->setError('xml was empty, didn\'t parse!');
	}

	/**
	* start-element handler
	*
	* @param    resource $parser XML parser object
	* @param    string $name element name
	* @param    array $attrs associative array of attributes
	* @access   private
	*/
	public function start_element($parser, $name, $attrs)
	{
		$pos = $this->position++;
		$this->message[$pos] = array('pos' => $pos, 'children' => '', 'cdata' => '');
		$this->message[$pos]['depth'] = $this->depth++;

		if ($pos != 0) {
			$this->message[$this->parent]['children'] .= '|' . $pos;
		}


		$this->message[$pos]['parent'] = $this->parent;
		$this->parent = $pos;
		$this->depth_array[$this->depth] = $pos;

		if (strpos($name, ':')) {
			$prefix = substr($name, 0, strpos($name, ':'));
			$name = substr(strstr($name, ':'), 1);
		}


		if (($name == 'Envelope') && ($this->status == '')) {
			$this->status = 'envelope';
		}
		 else if (($name == 'Header') && ($this->status == 'envelope')) {
			$this->root_header = $pos;
			$this->status = 'header';
		}
		 else if (($name == 'Body') && ($this->status == 'envelope')) {
			$this->status = 'body';
			$this->body_position = $pos;
		}
		 else if (($this->status == 'body') && ($pos == $this->body_position + 1)) {
			$this->status = 'method';
			$this->root_struct_name = $name;
			$this->root_struct = $pos;
			$this->message[$pos]['type'] = 'struct';
			$this->debug('found root struct ' . $this->root_struct_name . ', pos ' . $this->root_struct);
		}


		$this->message[$pos]['status'] = $this->status;
		$this->message[$pos]['name'] = htmlspecialchars($name);
		$this->message[$pos]['attrs'] = $attrs;
		$attstr = '';

		foreach ($attrs as $key => $value ) {
			$key_prefix = $this->getPrefix($key);
			$key_localpart = $this->getLocalPart($key);

			if ($key_prefix == 'xmlns') {
				if (preg_match('/^http:\\/\\/www.w3.org\\/[0-9]{4}\\/XMLSchema$/', $value)) {
					$this->XMLSchemaVersion = $value;
					$this->namespaces['xsd'] = $this->XMLSchemaVersion;
					$this->namespaces['xsi'] = $this->XMLSchemaVersion . '-instance';
				}


				$this->namespaces[$key_localpart] = $value;

				if ($name == $this->root_struct_name) {
					$this->methodNamespace = $value;
				}

			}
			 else if ($key_localpart == 'type') {
				if (isset($this->message[$pos]['type']) && ($this->message[$pos]['type'] == 'array')) {
				}
				 else {
					$value_prefix = $this->getPrefix($value);
					$value_localpart = $this->getLocalPart($value);
					$this->message[$pos]['type'] = $value_localpart;
					$this->message[$pos]['typePrefix'] = $value_prefix;

					if (isset($this->namespaces[$value_prefix])) {
						$this->message[$pos]['type_namespace'] = $this->namespaces[$value_prefix];
					}
					 else if (isset($attrs['xmlns:' . $value_prefix])) {
						$this->message[$pos]['type_namespace'] = $attrs['xmlns:' . $value_prefix];
					}

				}
			}
			 else if ($key_localpart == 'arrayType') {
				$this->message[$pos]['type'] = 'array';
				$expr = '/([A-Za-z0-9_]+):([A-Za-z]+[A-Za-z0-9_]+)\\[([0-9]+),?([0-9]*)\\]/';

				if (preg_match($expr, $value, $regs)) {
					$this->message[$pos]['typePrefix'] = $regs[1];
					$this->message[$pos]['arrayTypePrefix'] = $regs[1];

					if (isset($this->namespaces[$regs[1]])) {
						$this->message[$pos]['arrayTypeNamespace'] = $this->namespaces[$regs[1]];
					}
					 else if (isset($attrs['xmlns:' . $regs[1]])) {
						$this->message[$pos]['arrayTypeNamespace'] = $attrs['xmlns:' . $regs[1]];
					}


					$this->message[$pos]['arrayType'] = $regs[2];
					$this->message[$pos]['arraySize'] = $regs[3];
					$this->message[$pos]['arrayCols'] = $regs[4];
				}

			}
			 else if ($key_localpart == 'nil') {
				$this->message[$pos]['nil'] = ($value == 'true') || ($value == '1');
			}
			 else if (($key != 'href') && ($key != 'xmlns') && ($key_localpart != 'encodingStyle') && ($key_localpart != 'root')) {
				$this->message[$pos]['xattrs']['!' . $key] = $value;
			}


			if ($key == 'xmlns') {
				$this->default_namespace = $value;
			}


			if ($key == 'id') {
				$this->ids[$value] = $pos;
			}


			if (($key_localpart == 'root') && ($value == 1)) {
				$this->status = 'method';
				$this->root_struct_name = $name;
				$this->root_struct = $pos;
				$this->debug('found root struct ' . $this->root_struct_name . ', pos ' . $pos);
			}


			$attstr .= ' ' . $key . '="' . $value . '"';
		}

		if (isset($prefix)) {
			$this->message[$pos]['namespace'] = $this->namespaces[$prefix];
			$this->default_namespace = $this->namespaces[$prefix];
		}
		 else {
			$this->message[$pos]['namespace'] = $this->default_namespace;
		}

		if ($this->status == 'header') {
			if ($this->root_header != $pos) {
				$this->responseHeaders .= '<' . ((isset($prefix) ? $prefix . ':' : '')) . $name . $attstr . '>';
				return;
			}

		}
		 else if ($this->root_struct_name != '') {
			$this->document .= '<' . ((isset($prefix) ? $prefix . ':' : '')) . $name . $attstr . '>';
		}

	}

	/**
	* end-element handler
	*
	* @param    resource $parser XML parser object
	* @param    string $name element name
	* @access   private
	*/
	public function end_element($parser, $name)
	{
		$pos = $this->depth_array[$this->depth--];

		if (strpos($name, ':')) {
			$prefix = substr($name, 0, strpos($name, ':'));
			$name = substr(strstr($name, ':'), 1);
		}


		if (isset($this->body_position) && ($this->body_position < $pos)) {
			if (isset($this->message[$pos]['attrs']['href'])) {
				$id = substr($this->message[$pos]['attrs']['href'], 1);
				$this->multirefs[$id][$pos] = 'placeholder';
				$this->message[$pos]['result'] = &$this->multirefs[$id][$pos];
			}
			 else if ($this->message[$pos]['children'] != '') {
				if (!(isset($this->message[$pos]['result']))) {
					$this->message[$pos]['result'] = $this->buildVal($pos);
				}

			}
			 else if (isset($this->message[$pos]['xattrs'])) {
				if (isset($this->message[$pos]['nil']) && $this->message[$pos]['nil']) {
					$this->message[$pos]['xattrs']['!'] = NULL;
				}
				 else if (isset($this->message[$pos]['cdata']) && (trim($this->message[$pos]['cdata']) != '')) {
					if (isset($this->message[$pos]['type'])) {
						$this->message[$pos]['xattrs']['!'] = $this->decodeSimple($this->message[$pos]['cdata'], $this->message[$pos]['type'], (isset($this->message[$pos]['type_namespace']) ? $this->message[$pos]['type_namespace'] : ''));
					}
					 else {
						$parent = $this->message[$pos]['parent'];

						if (isset($this->message[$parent]['type']) && ($this->message[$parent]['type'] == 'array') && isset($this->message[$parent]['arrayType'])) {
							$this->message[$pos]['xattrs']['!'] = $this->decodeSimple($this->message[$pos]['cdata'], $this->message[$parent]['arrayType'], (isset($this->message[$parent]['arrayTypeNamespace']) ? $this->message[$parent]['arrayTypeNamespace'] : ''));
						}
						 else {
							$this->message[$pos]['xattrs']['!'] = $this->message[$pos]['cdata'];
						}
					}
				}


				$this->message[$pos]['result'] = $this->message[$pos]['xattrs'];
			}
			 else if (isset($this->message[$pos]['nil']) && $this->message[$pos]['nil']) {
				$this->message[$pos]['xattrs']['!'] = NULL;
			}
			 else if (isset($this->message[$pos]['type'])) {
				$this->message[$pos]['result'] = $this->decodeSimple($this->message[$pos]['cdata'], $this->message[$pos]['type'], (isset($this->message[$pos]['type_namespace']) ? $this->message[$pos]['type_namespace'] : ''));
			}
			 else {
				$parent = $this->message[$pos]['parent'];

				if (isset($this->message[$parent]['type']) && ($this->message[$parent]['type'] == 'array') && isset($this->message[$parent]['arrayType'])) {
					$this->message[$pos]['result'] = $this->decodeSimple($this->message[$pos]['cdata'], $this->message[$parent]['arrayType'], (isset($this->message[$parent]['arrayTypeNamespace']) ? $this->message[$parent]['arrayTypeNamespace'] : ''));
				}
				 else {
					$this->message[$pos]['result'] = $this->message[$pos]['cdata'];
				}
			}
		}


		if ($this->status == 'header') {
			if ($this->root_header != $pos) {
				$this->responseHeaders .= '</' . ((isset($prefix) ? $prefix . ':' : '')) . $name . '>';
			}

		}
		 else if ($this->root_struct <= $pos) {
			$this->document .= '</' . ((isset($prefix) ? $prefix . ':' : '')) . $name . '>';
		}


		if ($pos == $this->root_struct) {
			$this->status = 'body';
			$this->root_struct_namespace = $this->message[$pos]['namespace'];
		}
		 else if ($pos == $this->root_header) {
			$this->status = 'envelope';
		}
		 else if (($name == 'Body') && ($this->status == 'body')) {
			$this->status = 'envelope';
		}
		 else if (($name == 'Header') && ($this->status == 'header')) {
			$this->status = 'envelope';
		}
		 else if (($name == 'Envelope') && ($this->status == 'envelope')) {
			$this->status = '';
		}


		$this->parent = $this->message[$pos]['parent'];
	}

	/**
	* element content handler
	*
	* @param    resource $parser XML parser object
	* @param    string $data element content
	* @access   private
	*/
	public function character_data($parser, $data)
	{
		$pos = $this->depth_array[$this->depth];

		if ($this->xml_encoding == 'UTF-8') {
			if ($this->decode_utf8) {
				$data = utf8_decode($data);
			}

		}


		$this->message[$pos]['cdata'] .= $data;

		if ($this->status == 'header') {
			$this->responseHeaders .= $data;
			return;
		}


		$this->document .= $data;
	}

	/**
	* get the parsed message (SOAP Body)
	*
	* @return	mixed
	* @access   public
	* @deprecated	use get_soapbody instead
	*/
	public function get_response()
	{
		return $this->soapresponse;
	}

	/**
	* get the parsed SOAP Body (NULL if there was none)
	*
	* @return	mixed
	* @access   public
	*/
	public function get_soapbody()
	{
		return $this->soapresponse;
	}

	/**
	* get the parsed SOAP Header (NULL if there was none)
	*
	* @return	mixed
	* @access   public
	*/
	public function get_soapheader()
	{
		return $this->soapheader;
	}

	/**
	* get the unparsed SOAP Header
	*
	* @return	string XML or empty if no Header
	* @access   public
	*/
	public function getHeaders()
	{
		return $this->responseHeaders;
	}

	/**
	* decodes simple types into PHP variables
	*
	* @param    string $value value to decode
	* @param    string $type XML type to decode
	* @param    string $typens XML type namespace to decode
	* @return	mixed PHP value
	* @access   private
	*/
	public function decodeSimple($value, $type, $typens)
	{
		if (!(isset($type)) || ($type == 'string') || ($type == 'long') || ($type == 'unsignedLong')) {
			return (string) $value;
		}


		if (($type == 'int') || ($type == 'integer') || ($type == 'short') || ($type == 'byte')) {
			return (int) $value;
		}


		if (($type == 'float') || ($type == 'double') || ($type == 'decimal')) {
			return (double) $value;
		}


		if ($type == 'boolean') {
			if ((strtolower($value) == 'false') || (strtolower($value) == 'f')) {
				return false;
			}


			return (bool) $value;
		}


		if (($type == 'base64') || ($type == 'base64Binary')) {
			$this->debug('Decode base64 value');
			return base64_decode($value);
		}


		if (($type == 'nonPositiveInteger') || ($type == 'negativeInteger') || ($type == 'nonNegativeInteger') || ($type == 'positiveInteger') || ($type == 'unsignedInt') || ($type == 'unsignedShort') || ($type == 'unsignedByte')) {
			return (int) $value;
		}


		if ($type == 'array') {
			return array();
		}


		return (string) $value;
	}

	/**
	* builds response structures for compound values (arrays/structs)
	* and scalars
	*
	* @param    integer $pos position in node tree
	* @return	mixed	PHP value
	* @access   private
	*/
	public function buildVal($pos)
	{
		if (!(isset($this->message[$pos]['type']))) {
			$this->message[$pos]['type'] = '';
		}


		$this->debug('in buildVal() for ' . $this->message[$pos]['name'] . '(pos ' . $pos . ') of type ' . $this->message[$pos]['type']);

		if ($this->message[$pos]['children'] != '') {
			$this->debug('in buildVal, there are children');
			$children = explode('|', $this->message[$pos]['children']);
			array_shift($children);

			if (isset($this->message[$pos]['arrayCols']) && ($this->message[$pos]['arrayCols'] != '')) {
				$r = 0;
				$c = 0;

				foreach ($children as $child_pos ) {
					$this->debug('in buildVal, got an MD array element: ' . $r . ', ' . $c);
					$params[$r][] = $this->message[$child_pos]['result'];
					++$c;

					if ($c == $this->message[$pos]['arrayCols']) {
						$c = 0;
						++$r;
					}

				}
			}
			 else {
				if (($this->message[$pos]['type'] == 'array') || ($this->message[$pos]['type'] == 'Array')) {
					$this->debug('in buildVal, adding array ' . $this->message[$pos]['name']);

					foreach ($children as $child_pos ) {
						$params[] = &$this->message[$child_pos]['result'];
					}
				}
				 else if (($this->message[$pos]['type'] == 'Map') && ($this->message[$pos]['type_namespace'] == 'http://xml.apache.org/xml-soap')) {
					$this->debug('in buildVal, Java Map ' . $this->message[$pos]['name']);

					foreach ($children as $child_pos ) {
						$kv = explode('|', $this->message[$child_pos]['children']);
						$params[$this->message[$kv[1]]['result']] = &$this->message[$kv[2]]['result'];
					}
				}
				 else {
					$this->debug('in buildVal, adding Java Vector or generic compound type ' . $this->message[$pos]['name']);

					if (($this->message[$pos]['type'] == 'Vector') && ($this->message[$pos]['type_namespace'] == 'http://xml.apache.org/xml-soap')) {
						$notstruct = 1;
					}
					 else {
						$notstruct = 0;
					}

					foreach ($children as $child_pos ) {
						if ($notstruct) {
							$params[] = &$this->message[$child_pos]['result'];
						}
						 else if (isset($params[$this->message[$child_pos]['name']])) {
							if (!(is_array($params[$this->message[$child_pos]['name']])) || !(isset($params[$this->message[$child_pos]['name']][0]))) {
								$params[$this->message[$child_pos]['name']] = array($params[$this->message[$child_pos]['name']]);
							}


							$params[$this->message[$child_pos]['name']][] = &$this->message[$child_pos]['result'];
						}
						 else {
							$params[$this->message[$child_pos]['name']] = &$this->message[$child_pos]['result'];
						}
					}
				}
			}

			if (isset($this->message[$pos]['xattrs'])) {
				$this->debug('in buildVal, handling attributes');

				foreach ($this->message[$pos]['xattrs'] as $n => $v ) {
					$params[$n] = $v;
				}
			}


			if (isset($this->message[$pos]['cdata']) && (trim($this->message[$pos]['cdata']) != '')) {
				$this->debug('in buildVal, handling simpleContent');

				if (isset($this->message[$pos]['type'])) {
					$params['!'] = $this->decodeSimple($this->message[$pos]['cdata'], $this->message[$pos]['type'], (isset($this->message[$pos]['type_namespace']) ? $this->message[$pos]['type_namespace'] : ''));
				}
				 else {
					$parent = $this->message[$pos]['parent'];

					if (isset($this->message[$parent]['type']) && ($this->message[$parent]['type'] == 'array') && isset($this->message[$parent]['arrayType'])) {
						$params['!'] = $this->decodeSimple($this->message[$pos]['cdata'], $this->message[$parent]['arrayType'], (isset($this->message[$parent]['arrayTypeNamespace']) ? $this->message[$parent]['arrayTypeNamespace'] : ''));
					}
					 else {
						$params['!'] = $this->message[$pos]['cdata'];
					}
				}
			}


			$ret = ((is_array($params) ? $params : array()));
			$this->debug('in buildVal, return:');
			$this->appendDebug($this->varDump($ret));
			return $ret;
		}


		$this->debug('in buildVal, no children, building scalar');
		$cdata = ((isset($this->message[$pos]['cdata']) ? $this->message[$pos]['cdata'] : ''));

		if (isset($this->message[$pos]['type'])) {
			$ret = $this->decodeSimple($cdata, $this->message[$pos]['type'], (isset($this->message[$pos]['type_namespace']) ? $this->message[$pos]['type_namespace'] : ''));
			$this->debug('in buildVal, return: ' . $ret);
			return $ret;
		}


		$parent = $this->message[$pos]['parent'];

		if (isset($this->message[$parent]['type']) && ($this->message[$parent]['type'] == 'array') && isset($this->message[$parent]['arrayType'])) {
			$ret = $this->decodeSimple($cdata, $this->message[$parent]['arrayType'], (isset($this->message[$parent]['arrayTypeNamespace']) ? $this->message[$parent]['arrayTypeNamespace'] : ''));
			$this->debug('in buildVal, return: ' . $ret);
			return $ret;
		}


		$ret = $this->message[$pos]['cdata'];
		$this->debug('in buildVal, return: ' . $ret);
		return $ret;
	}
}

class soap_parser extends nusoap_parser
{}


?>