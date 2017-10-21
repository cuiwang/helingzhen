<?php
class nusoap_xmlschema extends nusoap_base
{
	public $schema = '';
	public $xml = '';
	public $enclosingNamespaces;
	public $schemaInfo = array();
	public $schemaTargetNamespace = '';
	public $attributes = array();
	public $complexTypes = array();
	public $complexTypeStack = array();
	public $currentComplexType;
	public $elements = array();
	public $elementStack = array();
	public $currentElement;
	public $simpleTypes = array();
	public $simpleTypeStack = array();
	public $currentSimpleType;
	public $imports = array();
	public $parser;
	public $position = 0;
	public $depth = 0;
	public $depth_array = array();
	public $message = array();
	public $defaultNamespace = array();

	/**
	* constructor
	*
	* @param    string $schema schema document URI
	* @param    string $xml xml document URI
	* @param	string $namespaces namespaces defined in enclosing XML
	* @access   public
	*/
	public function nusoap_xmlschema($schema = '', $xml = '', $namespaces = array())
	{
		parent::nusoap_base();
		$this->debug('nusoap_xmlschema class instantiated, inside constructor');
		$this->schema = $schema;
		$this->xml = $xml;
		$this->enclosingNamespaces = $namespaces;
		$this->namespaces = array_merge($this->namespaces, $namespaces);

		if ($schema != '') {
			$this->debug('initial schema file: ' . $schema);
			$this->parseFile($schema, 'schema');
		}


		if ($xml != '') {
			$this->debug('initial xml file: ' . $xml);
			$this->parseFile($xml, 'xml');
		}

	}

	/**
    * parse an XML file
    *
    * @param string $xml path/URL to XML file
    * @param string $type (schema | xml)
	* @return boolean
    * @access public
    */
	public function parseFile($xml, $type)
	{
		if ($xml != '') {
			$xmlStr = @join('', @file($xml));

			if ($xmlStr == '') {
				$msg = 'Error reading XML from ' . $xml;
				$this->setError($msg);
				$this->debug($msg);
				return false;
			}


			$this->debug('parsing ' . $xml);
			$this->parseString($xmlStr, $type);
			$this->debug('done parsing ' . $xml);
			return true;
		}


		return false;
	}

	/**
	* parse an XML string
	*
	* @param    string $xml path or URL
    * @param	string $type (schema|xml)
	* @access   private
	*/
	public function parseString($xml, $type)
	{
		if ($xml != '') {
			$this->parser = xml_parser_create();
			xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, 0);
			xml_set_object($this->parser, $this);

			if ($type == 'schema') {
				xml_set_element_handler($this->parser, 'schemaStartElement', 'schemaEndElement');
				xml_set_character_data_handler($this->parser, 'schemaCharacterData');
			}
			 else if ($type == 'xml') {
				xml_set_element_handler($this->parser, 'xmlStartElement', 'xmlEndElement');
				xml_set_character_data_handler($this->parser, 'xmlCharacterData');
			}


			if (!(xml_parse($this->parser, $xml, true))) {
				$errstr = sprintf('XML error parsing XML schema on line %d: %s', xml_get_current_line_number($this->parser), xml_error_string(xml_get_error_code($this->parser)));
				$this->debug($errstr);
				$this->debug('XML payload:' . "\n" . $xml);
				$this->setError($errstr);
			}


			xml_parser_free($this->parser);
			return;
		}


		$this->debug('no xml passed to parseString()!!');
		$this->setError('no xml passed to parseString()!!');
	}

	/**
	 * gets a type name for an unnamed type
	 *
	 * @param	string	Element name
	 * @return	string	A type name for an unnamed type
	 * @access	private
	 */
	public function CreateTypeName($ename)
	{
		$scope = '';
		$i = 0;

		while ($i < count($this->complexTypeStack)) {
			$scope .= $this->complexTypeStack[$i] . '_';
			++$i;
		}

		return $scope . $ename . '_ContainedType';
	}

	/**
	* start-element handler
	*
	* @param    string $parser XML parser object
	* @param    string $name element name
	* @param    string $attrs associative array of attributes
	* @access   private
	*/
	public function schemaStartElement($parser, $name, $attrs)
	{
		$pos = $this->position++;
		$depth = $this->depth++;
		$this->depth_array[$depth] = $pos;
		$this->message[$pos] = array('cdata' => '');

		if (0 < $depth) {
			$this->defaultNamespace[$pos] = $this->defaultNamespace[$this->depth_array[$depth - 1]];
		}
		 else {
			$this->defaultNamespace[$pos] = false;
		}

		if ($prefix = $this->getPrefix($name)) {
			$name = $this->getLocalPart($name);
		}
		 else {
			$prefix = '';
		}

		if (0 < count($attrs)) {
			foreach ($attrs as $k => $v ) {
				if (preg_match('/^xmlns/', $k)) {
					if ($ns_prefix = substr(strrchr($k, ':'), 1)) {
						$this->namespaces[$ns_prefix] = $v;
					}
					 else {
						$this->defaultNamespace[$pos] = $v;

						if (!($this->getPrefixFromNamespace($v))) {
							$this->namespaces['ns' . (count($this->namespaces) + 1)] = $v;
						}

					}

					if (($v == 'http://www.w3.org/2001/XMLSchema') || ($v == 'http://www.w3.org/1999/XMLSchema') || ($v == 'http://www.w3.org/2000/10/XMLSchema')) {
						$this->XMLSchemaVersion = $v;
						$this->namespaces['xsi'] = $v . '-instance';
					}

				}

			}

			foreach ($attrs as $k => $v ) {
				$k = ((strpos($k, ':') ? $this->expandQname($k) : $k));
				$v = ((strpos($v, ':') ? $this->expandQname($v) : $v));
				$eAttrs[$k] = $v;
			}

			$attrs = $eAttrs;
		}
		 else {
			$attrs = array();
		}

		switch ($name) {
		case 'all':

		case 'choice':

		case 'group':

		case 'sequence':
			$this->complexTypes[$this->currentComplexType]['compositor'] = $name;
			break;

		case 'attribute':
			$this->xdebug('parsing attribute:');
			$this->appendDebug($this->varDump($attrs));

			if (!(isset($attrs['form']))) {
				$attrs['form'] = $this->schemaInfo['attributeFormDefault'];
			}


			if (isset($attrs['http://schemas.xmlsoap.org/wsdl/:arrayType'])) {
				$v = $attrs['http://schemas.xmlsoap.org/wsdl/:arrayType'];

				if (!(strpos($v, ':'))) {
					if ($this->defaultNamespace[$pos]) {
						$attrs['http://schemas.xmlsoap.org/wsdl/:arrayType'] = $this->defaultNamespace[$pos] . ':' . $attrs['http://schemas.xmlsoap.org/wsdl/:arrayType'];
					}

				}

			}


			if (isset($attrs['name'])) {
				$this->attributes[$attrs['name']] = $attrs;
				$aname = $attrs['name'];
			}
			 else if (isset($attrs['ref']) && ($attrs['ref'] == 'http://schemas.xmlsoap.org/soap/encoding/:arrayType')) {
				if (isset($attrs['http://schemas.xmlsoap.org/wsdl/:arrayType'])) {
					$aname = $attrs['http://schemas.xmlsoap.org/wsdl/:arrayType'];
				}
				 else {
					$aname = '';
				}
			}
			 else if (isset($attrs['ref'])) {
				$aname = $attrs['ref'];
				$this->attributes[$attrs['ref']] = $attrs;
			}


			if ($this->currentComplexType) {
				$this->complexTypes[$this->currentComplexType]['attrs'][$aname] = $attrs;
			}


			if (isset($attrs['http://schemas.xmlsoap.org/wsdl/:arrayType']) || ($this->getLocalPart($aname) == 'arrayType')) {
				$this->complexTypes[$this->currentComplexType]['phpType'] = 'array';
				$prefix = $this->getPrefix($aname);

				if (isset($attrs['http://schemas.xmlsoap.org/wsdl/:arrayType'])) {
					$v = $attrs['http://schemas.xmlsoap.org/wsdl/:arrayType'];
				}
				 else {
					$v = '';
				}

				if (strpos($v, '[,]')) {
					$this->complexTypes[$this->currentComplexType]['multidimensional'] = true;
				}


				$v = substr($v, 0, strpos($v, '['));

				if (!(strpos($v, ':')) && isset($this->typemap[$this->XMLSchemaVersion][$v])) {
					$v = $this->XMLSchemaVersion . ':' . $v;
				}


				$this->complexTypes[$this->currentComplexType]['arrayType'] = $v;
			}


			break;

		case 'complexContent':
			$this->xdebug('do nothing for element ' . $name);
			break;

		case 'complexType':
			array_push($this->complexTypeStack, $this->currentComplexType);

			if (isset($attrs['name'])) {
				$this->xdebug('processing named complexType ' . $attrs['name']);
				$this->currentComplexType = $attrs['name'];
				$this->complexTypes[$this->currentComplexType] = $attrs;
				$this->complexTypes[$this->currentComplexType]['typeClass'] = 'complexType';

				if (isset($attrs['base']) && preg_match('/:Array$/', $attrs['base'])) {
					$this->xdebug('complexType is unusual array');
					$this->complexTypes[$this->currentComplexType]['phpType'] = 'array';
				}
				 else {
					$this->complexTypes[$this->currentComplexType]['phpType'] = 'struct';
				}
			}
			 else {
				$name = $this->CreateTypeName($this->currentElement);
				$this->xdebug('processing unnamed complexType for element ' . $this->currentElement . ' named ' . $name);
				$this->currentComplexType = $name;
				$this->complexTypes[$this->currentComplexType] = $attrs;
				$this->complexTypes[$this->currentComplexType]['typeClass'] = 'complexType';

				if (isset($attrs['base']) && preg_match('/:Array$/', $attrs['base'])) {
					$this->xdebug('complexType is unusual array');
					$this->complexTypes[$this->currentComplexType]['phpType'] = 'array';
				}
				 else {
					$this->complexTypes[$this->currentComplexType]['phpType'] = 'struct';
				}
			}

			$this->complexTypes[$this->currentComplexType]['simpleContent'] = 'false';
			break;

		case 'element':
			array_push($this->elementStack, $this->currentElement);

			if (!(isset($attrs['form']))) {
				if ($this->currentComplexType) {
					$attrs['form'] = $this->schemaInfo['elementFormDefault'];
				}
				 else {
					$attrs['form'] = 'qualified';
				}
			}


			if (isset($attrs['type'])) {
				$this->xdebug('processing typed element ' . $attrs['name'] . ' of type ' . $attrs['type']);

				if (!($this->getPrefix($attrs['type']))) {
					if ($this->defaultNamespace[$pos]) {
						$attrs['type'] = $this->defaultNamespace[$pos] . ':' . $attrs['type'];
						$this->xdebug('used default namespace to make type ' . $attrs['type']);
					}

				}


				if ($this->currentComplexType && ($this->complexTypes[$this->currentComplexType]['phpType'] == 'array')) {
					$this->xdebug('arrayType for unusual array is ' . $attrs['type']);
					$this->complexTypes[$this->currentComplexType]['arrayType'] = $attrs['type'];
				}


				$this->currentElement = $attrs['name'];
				$ename = $attrs['name'];
			}
			 else if (isset($attrs['ref'])) {
				$this->xdebug('processing element as ref to ' . $attrs['ref']);
				$this->currentElement = 'ref to ' . $attrs['ref'];
				$ename = $this->getLocalPart($attrs['ref']);
			}
			 else {
				$type = $this->CreateTypeName($this->currentComplexType . '_' . $attrs['name']);
				$this->xdebug('processing untyped element ' . $attrs['name'] . ' type ' . $type);
				$this->currentElement = $attrs['name'];
				$attrs['type'] = $this->schemaTargetNamespace . ':' . $type;
				$ename = $attrs['name'];
			}

			if (isset($ename) && $this->currentComplexType) {
				$this->xdebug('add element ' . $ename . ' to complexType ' . $this->currentComplexType);
				$this->complexTypes[$this->currentComplexType]['elements'][$ename] = $attrs;
			}
			 else if (!(isset($attrs['ref']))) {
				$this->xdebug('add element ' . $ename . ' to elements array');
				$this->elements[$attrs['name']] = $attrs;
				$this->elements[$attrs['name']]['typeClass'] = 'element';
			}


			break;

		case 'enumeration':
			$this->xdebug('enumeration ' . $attrs['value']);

			if ($this->currentSimpleType) {
				$this->simpleTypes[$this->currentSimpleType]['enumeration'][] = $attrs['value'];
			}
			 else if ($this->currentComplexType) {
				$this->complexTypes[$this->currentComplexType]['enumeration'][] = $attrs['value'];
			}


			break;

		case 'extension':
			$this->xdebug('extension ' . $attrs['base']);

			if ($this->currentComplexType) {
				$ns = $this->getPrefix($attrs['base']);

				if ($ns == '') {
					$this->complexTypes[$this->currentComplexType]['extensionBase'] = $this->schemaTargetNamespace . ':' . $attrs['base'];
				}
				 else {
					$this->complexTypes[$this->currentComplexType]['extensionBase'] = $attrs['base'];
				}
			}
			 else {
				$this->xdebug('no current complexType to set extensionBase');
			}

			break;

		case 'import':
			if (isset($attrs['schemaLocation'])) {
				$this->xdebug('import namespace ' . $attrs['namespace'] . ' from ' . $attrs['schemaLocation']);
				$this->imports[$attrs['namespace']][] = array('location' => $attrs['schemaLocation'], 'loaded' => false);
			}
			 else {
				$this->xdebug('import namespace ' . $attrs['namespace']);
				$this->imports[$attrs['namespace']][] = array('location' => '', 'loaded' => true);

				if (!($this->getPrefixFromNamespace($attrs['namespace']))) {
					$this->namespaces['ns' . (count($this->namespaces) + 1)] = $attrs['namespace'];
				}

			}

			break;

		case 'include':
			if (isset($attrs['schemaLocation'])) {
				$this->xdebug('include into namespace ' . $this->schemaTargetNamespace . ' from ' . $attrs['schemaLocation']);
				$this->imports[$this->schemaTargetNamespace][] = array('location' => $attrs['schemaLocation'], 'loaded' => false);
			}
			 else {
				$this->xdebug('ignoring invalid XML Schema construct: include without schemaLocation attribute');
			}

			break;

		case 'list':
			$this->xdebug('do nothing for element ' . $name);
			break;

		case 'restriction':
			$this->xdebug('restriction ' . $attrs['base']);

			if ($this->currentSimpleType) {
				$this->simpleTypes[$this->currentSimpleType]['type'] = $attrs['base'];
			}
			 else if ($this->currentComplexType) {
				$this->complexTypes[$this->currentComplexType]['restrictionBase'] = $attrs['base'];

				if (strstr($attrs['base'], ':') == ':Array') {
					$this->complexTypes[$this->currentComplexType]['phpType'] = 'array';
				}

			}


			break;

		case 'schema':
			$this->schemaInfo = $attrs;
			$this->schemaInfo['schemaVersion'] = $this->getNamespaceFromPrefix($prefix);

			if (isset($attrs['targetNamespace'])) {
				$this->schemaTargetNamespace = $attrs['targetNamespace'];
			}


			if (!(isset($attrs['elementFormDefault']))) {
				$this->schemaInfo['elementFormDefault'] = 'unqualified';
			}


			if (!(isset($attrs['attributeFormDefault']))) {
				$this->schemaInfo['attributeFormDefault'] = 'unqualified';
			}


			break;

		case 'simpleContent':
			if ($this->currentComplexType) {
				$this->complexTypes[$this->currentComplexType]['simpleContent'] = 'true';
			}
			 else {
				$this->xdebug('do nothing for element ' . $name . ' because there is no current complexType');
			}

			break;

		case 'simpleType':
			array_push($this->simpleTypeStack, $this->currentSimpleType);

			if (isset($attrs['name'])) {
				$this->xdebug('processing simpleType for name ' . $attrs['name']);
				$this->currentSimpleType = $attrs['name'];
				$this->simpleTypes[$attrs['name']] = $attrs;
				$this->simpleTypes[$attrs['name']]['typeClass'] = 'simpleType';
				$this->simpleTypes[$attrs['name']]['phpType'] = 'scalar';
			}
			 else {
				$name = $this->CreateTypeName($this->currentComplexType . '_' . $this->currentElement);
				$this->xdebug('processing unnamed simpleType for element ' . $this->currentElement . ' named ' . $name);
				$this->currentSimpleType = $name;
				$this->simpleTypes[$this->currentSimpleType] = $attrs;
				$this->simpleTypes[$this->currentSimpleType]['phpType'] = 'scalar';
			}

			break;

		case 'union':
			$this->xdebug('do nothing for element ' . $name);
			break;

			$this->xdebug('do not have any logic to process element ' . $name);
		}
	}

	/**
	* end-element handler
	*
	* @param    string $parser XML parser object
	* @param    string $name element name
	* @access   private
	*/
	public function schemaEndElement($parser, $name)
	{
		$this->depth--;

		if (isset($this->depth_array[$this->depth])) {
			$pos = $this->depth_array[$this->depth];
		}


		if ($prefix = $this->getPrefix($name)) {
			$name = $this->getLocalPart($name);
		}
		 else {
			$prefix = '';
		}

		if ($name == 'complexType') {
			$this->xdebug('done processing complexType ' . (($this->currentComplexType ? $this->currentComplexType : '(unknown)')));
			$this->xdebug($this->varDump($this->complexTypes[$this->currentComplexType]));
			$this->currentComplexType = array_pop($this->complexTypeStack);
		}


		if ($name == 'element') {
			$this->xdebug('done processing element ' . (($this->currentElement ? $this->currentElement : '(unknown)')));
			$this->currentElement = array_pop($this->elementStack);
		}


		if ($name == 'simpleType') {
			$this->xdebug('done processing simpleType ' . (($this->currentSimpleType ? $this->currentSimpleType : '(unknown)')));
			$this->xdebug($this->varDump($this->simpleTypes[$this->currentSimpleType]));
			$this->currentSimpleType = array_pop($this->simpleTypeStack);
		}

	}

	/**
	* element content handler
	*
	* @param    string $parser XML parser object
	* @param    string $data element content
	* @access   private
	*/
	public function schemaCharacterData($parser, $data)
	{
		$pos = $this->depth_array[$this->depth - 1];
		$this->message[$pos]['cdata'] .= $data;
	}

	/**
	* serialize the schema
	*
	* @access   public
	*/
	public function serializeSchema()
	{
		$schemaPrefix = $this->getPrefixFromNamespace($this->XMLSchemaVersion);
		$xml = '';

		if (0 < sizeof($this->imports)) {
			foreach ($this->imports as $ns => $list ) {
				foreach ($list as $ii ) {
					if ($ii['location'] != '') {
						$xml .= ' <' . $schemaPrefix . ':import location="' . $ii['location'] . '" namespace="' . $ns . '" />' . "\n";
					}
					 else {
						$xml .= ' <' . $schemaPrefix . ':import namespace="' . $ns . '" />' . "\n";
					}
				}
			}
		}


		foreach ($this->complexTypes as $typeName => $attrs ) {
			$contentStr = '';

			if (isset($attrs['elements']) && (0 < count($attrs['elements']))) {
				foreach ($attrs['elements'] as $element => $eParts ) {
					if (isset($eParts['ref'])) {
						$contentStr .= '   <' . $schemaPrefix . ':element ref="' . $element . '"/>' . "\n";
					}
					 else {
						$contentStr .= '   <' . $schemaPrefix . ':element name="' . $element . '" type="' . $this->contractQName($eParts['type']) . '"';

						foreach ($eParts as $aName => $aValue ) {
							if (($aName != 'name') && ($aName != 'type')) {
								$contentStr .= ' ' . $aName . '="' . $aValue . '"';
							}

						}

						$contentStr .= '/>' . "\n";
					}
				}

				if (isset($attrs['compositor']) && ($attrs['compositor'] != '')) {
					$contentStr = '  <' . $schemaPrefix . ':' . $attrs['compositor'] . '>' . "\n" . $contentStr . '  </' . $schemaPrefix . ':' . $attrs['compositor'] . '>' . "\n";
				}

			}


			if (isset($attrs['attrs']) && (1 <= count($attrs['attrs']))) {
				foreach ($attrs['attrs'] as $attr => $aParts ) {
					$contentStr .= '    <' . $schemaPrefix . ':attribute';

					foreach ($aParts as $a => $v ) {
						if (($a == 'ref') || ($a == 'type')) {
							$contentStr .= ' ' . $a . '="' . $this->contractQName($v) . '"';
						}
						 else if ($a == 'http://schemas.xmlsoap.org/wsdl/:arrayType') {
							$this->usedNamespaces['wsdl'] = $this->namespaces['wsdl'];
							$contentStr .= ' wsdl:arrayType="' . $this->contractQName($v) . '"';
						}
						 else {
							$contentStr .= ' ' . $a . '="' . $v . '"';
						}
					}

					$contentStr .= '/>' . "\n";
				}
			}


			if (isset($attrs['restrictionBase']) && ($attrs['restrictionBase'] != '')) {
				$contentStr = '   <' . $schemaPrefix . ':restriction base="' . $this->contractQName($attrs['restrictionBase']) . '">' . "\n" . $contentStr . '   </' . $schemaPrefix . ':restriction>' . "\n";
				if ((isset($attrs['elements']) && (0 < count($attrs['elements']))) || (isset($attrs['attrs']) && (0 < count($attrs['attrs'])))) {
					$contentStr = '  <' . $schemaPrefix . ':complexContent>' . "\n" . $contentStr . '  </' . $schemaPrefix . ':complexContent>' . "\n";
				}

			}


			if ($contentStr != '') {
				$contentStr = ' <' . $schemaPrefix . ':complexType name="' . $typeName . '">' . "\n" . $contentStr . ' </' . $schemaPrefix . ':complexType>' . "\n";
			}
			 else {
				$contentStr = ' <' . $schemaPrefix . ':complexType name="' . $typeName . '"/>' . "\n";
			}

			$xml .= $contentStr;
		}

		if (isset($this->simpleTypes) && (0 < count($this->simpleTypes))) {
			foreach ($this->simpleTypes as $typeName => $eParts ) {
				$xml .= ' <' . $schemaPrefix . ':simpleType name="' . $typeName . '">' . "\n" . '  <' . $schemaPrefix . ':restriction base="' . $this->contractQName($eParts['type']) . '">' . "\n";

				if (isset($eParts['enumeration'])) {
					foreach ($eParts['enumeration'] as $e ) {
						$xml .= '  <' . $schemaPrefix . ':enumeration value="' . $e . '"/>' . "\n";
					}
				}


				$xml .= '  </' . $schemaPrefix . ':restriction>' . "\n" . ' </' . $schemaPrefix . ':simpleType>';
			}
		}


		if (isset($this->elements) && (0 < count($this->elements))) {
			foreach ($this->elements as $element => $eParts ) {
				$xml .= ' <' . $schemaPrefix . ':element name="' . $element . '" type="' . $this->contractQName($eParts['type']) . '"/>' . "\n";
			}
		}


		if (isset($this->attributes) && (0 < count($this->attributes))) {
			foreach ($this->attributes as $attr => $aParts ) {
				$xml .= ' <' . $schemaPrefix . ':attribute name="' . $attr . '" type="' . $this->contractQName($aParts['type']) . '"' . "\n" . '/>';
			}
		}


		$attr = '';

		foreach ($this->schemaInfo as $k => $v ) {
			if (($k == 'elementFormDefault') || ($k == 'attributeFormDefault')) {
				$attr .= ' ' . $k . '="' . $v . '"';
			}

		}

		$el = '<' . $schemaPrefix . ':schema' . $attr . ' targetNamespace="' . $this->schemaTargetNamespace . '"' . "\n";

		foreach (array_diff($this->usedNamespaces, $this->enclosingNamespaces) as $nsp => $ns ) {
			$el .= ' xmlns:' . $nsp . '="' . $ns . '"';
		}

		$xml = $el . '>' . "\n" . $xml . '</' . $schemaPrefix . ':schema>' . "\n";
		return $xml;
	}

	/**
	* adds debug data to the clas level debug string
	*
	* @param    string $string debug data
	* @access   private
	*/
	public function xdebug($string)
	{
		$this->debug('<' . $this->schemaTargetNamespace . '> ' . $string);
	}

	/**
    * get the PHP type of a user defined type in the schema
    * PHP type is kind of a misnomer since it actually returns 'struct' for assoc. arrays
    * returns false if no type exists, or not w/ the given namespace
    * else returns a string that is either a native php type, or 'struct'
    *
    * @param string $type name of defined type
    * @param string $ns namespace of type
    * @return mixed
    * @access public
    * @deprecated
    */
	public function getPHPType($type, $ns)
	{
		if (isset($this->typemap[$ns][$type])) {
			return $this->typemap[$ns][$type];
		}


		if (isset($this->complexTypes[$type])) {
			return $this->complexTypes[$type]['phpType'];
		}


		return false;
	}

	/**
    * returns an associative array of information about a given type
    * returns false if no type exists by the given name
    *
	*	For a complexType typeDef = array(
	*	'restrictionBase' => '',
	*	'phpType' => '',
	*	'compositor' => '(sequence|all)',
	*	'elements' => array(), // refs to elements array
	*	'attrs' => array() // refs to attributes array
	*	... and so on (see addComplexType)
	*	)
	*
	*   For simpleType or element, the array has different keys.
    *
    * @param string $type
    * @return mixed
    * @access public
    * @see addComplexType
    * @see addSimpleType
    * @see addElement
    */
	public function getTypeDef($type)
	{
		if (substr($type, -1) == '^') {
			$is_element = 1;
			$type = substr($type, 0, -1);
		}
		 else {
			$is_element = 0;
		}

		if (!($is_element) && isset($this->complexTypes[$type])) {
			$this->xdebug('in getTypeDef, found complexType ' . $type);
			return $this->complexTypes[$type];
		}


		if (!($is_element) && isset($this->simpleTypes[$type])) {
			$this->xdebug('in getTypeDef, found simpleType ' . $type);

			if (!(isset($this->simpleTypes[$type]['phpType']))) {
				$uqType = substr($this->simpleTypes[$type]['type'], strrpos($this->simpleTypes[$type]['type'], ':') + 1);
				$ns = substr($this->simpleTypes[$type]['type'], 0, strrpos($this->simpleTypes[$type]['type'], ':'));
				$etype = $this->getTypeDef($uqType);

				if ($etype) {
					$this->xdebug('in getTypeDef, found type for simpleType ' . $type . ':');
					$this->xdebug($this->varDump($etype));

					if (isset($etype['phpType'])) {
						$this->simpleTypes[$type]['phpType'] = $etype['phpType'];
					}


					if (isset($etype['elements'])) {
						$this->simpleTypes[$type]['elements'] = $etype['elements'];
					}

				}

			}


			return $this->simpleTypes[$type];
		}


		if (isset($this->elements[$type])) {
			$this->xdebug('in getTypeDef, found element ' . $type);

			if (!(isset($this->elements[$type]['phpType']))) {
				$uqType = substr($this->elements[$type]['type'], strrpos($this->elements[$type]['type'], ':') + 1);
				$ns = substr($this->elements[$type]['type'], 0, strrpos($this->elements[$type]['type'], ':'));
				$etype = $this->getTypeDef($uqType);

				if ($etype) {
					$this->xdebug('in getTypeDef, found type for element ' . $type . ':');
					$this->xdebug($this->varDump($etype));

					if (isset($etype['phpType'])) {
						$this->elements[$type]['phpType'] = $etype['phpType'];
					}


					if (isset($etype['elements'])) {
						$this->elements[$type]['elements'] = $etype['elements'];
					}


					if (isset($etype['extensionBase'])) {
						$this->elements[$type]['extensionBase'] = $etype['extensionBase'];
					}

				}
				 else if ($ns == 'http://www.w3.org/2001/XMLSchema') {
					$this->xdebug('in getTypeDef, element ' . $type . ' is an XSD type');
					$this->elements[$type]['phpType'] = 'scalar';
				}

			}


			return $this->elements[$type];
		}


		if (isset($this->attributes[$type])) {
			$this->xdebug('in getTypeDef, found attribute ' . $type);
			return $this->attributes[$type];
		}


		if (preg_match('/_ContainedType$/', $type)) {
			$this->xdebug('in getTypeDef, have an untyped element ' . $type);
			$typeDef['typeClass'] = 'simpleType';
			$typeDef['phpType'] = 'scalar';
			$typeDef['type'] = 'http://www.w3.org/2001/XMLSchema:string';
			return $typeDef;
		}


		$this->xdebug('in getTypeDef, did not find ' . $type);
		return false;
	}

	/**
    * returns a sample serialization of a given type, or false if no type by the given name
    *
    * @param string $type name of type
    * @return mixed
    * @access public
    * @deprecated
    */
	public function serializeTypeDef($type)
	{
		if ($typeDef = $this->getTypeDef($type)) {
			$str .= '<' . $type;

			if (is_array($typeDef['attrs'])) {
				foreach ($typeDef['attrs'] as $attName => $data ) {
					$str .= ' ' . $attName . '="{type = ' . $data['type'] . '}"';
				}
			}


			$str .= ' xmlns="' . $this->schema['targetNamespace'] . '"';

			if (0 < count($typeDef['elements'])) {
				$str .= '>';

				foreach ($typeDef['elements'] as $element => $eData ) {
					$str .= $this->serializeTypeDef($element);
				}

				$str .= '</' . $type . '>';
			}
			 else if ($typeDef['typeClass'] == 'element') {
				$str .= '></' . $type . '>';
			}
			 else {
				$str .= '/>';
			}

			return $str;
		}


		return false;
	}

	/**
    * returns HTML form elements that allow a user
    * to enter values for creating an instance of the given type.
    *
    * @param string $name name for type instance
    * @param string $type name of type
    * @return string
    * @access public
    * @deprecated
	*/
	public function typeToForm($name, $type)
	{
		if ($typeDef = $this->getTypeDef($type)) {
			if ($typeDef['phpType'] == 'struct') {
				$buffer .= '<table>';

				foreach ($typeDef['elements'] as $child => $childDef ) {
					$buffer .= "\r\n\t\t\t\t\t" . '<tr><td align=\'right\'>' . $childDef['name'] . ' (type: ' . $this->getLocalPart($childDef['type']) . '):</td>' . "\r\n\t\t\t\t\t" . '<td><input type=\'text\' name=\'parameters[' . $name . '][' . $childDef['name'] . ']\'></td></tr>';
				}

				$buffer .= '</table>';
			}
			 else if ($typeDef['phpType'] == 'array') {
				$buffer .= '<table>';
				$i = 0;

				while ($i < 3) {
					$buffer .= "\r\n\t\t\t\t\t" . '<tr><td align=\'right\'>array item (type: ' . $typeDef['arrayType'] . '):</td>' . "\r\n\t\t\t\t\t" . '<td><input type=\'text\' name=\'parameters[' . $name . '][]\'></td></tr>';
					++$i;
				}

				$buffer .= '</table>';
			}
			 else {
				$buffer .= '<input type=\'text\' name=\'parameters[' . $name . ']\'>';
			}
		}
		 else {
			$buffer .= '<input type=\'text\' name=\'parameters[' . $name . ']\'>';
		}

		return $buffer;
	}

	/**
	* adds a complex type to the schema
	* 
	* example: array
	* 
	* addType(
	* 	'ArrayOfstring',
	* 	'complexType',
	* 	'array',
	* 	'',
	* 	'SOAP-ENC:Array',
	* 	array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'string[]'),
	* 	'xsd:string'
	* );
	* 
	* example: PHP associative array ( SOAP Struct )
	* 
	* addType(
	* 	'SOAPStruct',
	* 	'complexType',
	* 	'struct',
	* 	'all',
	* 	array('myVar'=> array('name'=>'myVar','type'=>'string')
	* );
	* 
	* @param name
	* @param typeClass (complexType|simpleType|attribute)
	* @param phpType: currently supported are array and struct (php assoc array)
	* @param compositor (all|sequence|choice)
	* @param restrictionBase namespace:name (http://schemas.xmlsoap.org/soap/encoding/:Array)
	* @param elements = array ( name = array(name=>'',type=>'') )
	* @param attrs = array(
	* 	array(
	*		'ref' => "http://schemas.xmlsoap.org/soap/encoding/:arrayType",
	*		"http://schemas.xmlsoap.org/wsdl/:arrayType" => "string[]"
	* 	)
	* )
	* @param arrayType: namespace:name (http://www.w3.org/2001/XMLSchema:string)
	* @access public
	* @see getTypeDef
	*/
	public function addComplexType($name, $typeClass = 'complexType', $phpType = 'array', $compositor = '', $restrictionBase = '', $elements = array(), $attrs = array(), $arrayType = '')
	{
		$this->complexTypes[$name] = array('name' => $name, 'typeClass' => $typeClass, 'phpType' => $phpType, 'compositor' => $compositor, 'restrictionBase' => $restrictionBase, 'elements' => $elements, 'attrs' => $attrs, 'arrayType' => $arrayType);
		$this->xdebug('addComplexType ' . $name . ':');
		$this->appendDebug($this->varDump($this->complexTypes[$name]));
	}

	/**
	* adds a simple type to the schema
	*
	* @param string $name
	* @param string $restrictionBase namespace:name (http://schemas.xmlsoap.org/soap/encoding/:Array)
	* @param string $typeClass (should always be simpleType)
	* @param string $phpType (should always be scalar)
	* @param array $enumeration array of values
	* @access public
	* @see nusoap_xmlschema
	* @see getTypeDef
	*/
	public function addSimpleType($name, $restrictionBase = '', $typeClass = 'simpleType', $phpType = 'scalar', $enumeration = array())
	{
		$this->simpleTypes[$name] = array('name' => $name, 'typeClass' => $typeClass, 'phpType' => $phpType, 'type' => $restrictionBase, 'enumeration' => $enumeration);
		$this->xdebug('addSimpleType ' . $name . ':');
		$this->appendDebug($this->varDump($this->simpleTypes[$name]));
	}

	/**
	* adds an element to the schema
	*
	* @param array $attrs attributes that must include name and type
	* @see nusoap_xmlschema
	* @access public
	*/
	public function addElement($attrs)
	{
		if (!($this->getPrefix($attrs['type']))) {
			$attrs['type'] = $this->schemaTargetNamespace . ':' . $attrs['type'];
		}


		$this->elements[$attrs['name']] = $attrs;
		$this->elements[$attrs['name']]['typeClass'] = 'element';
		$this->xdebug('addElement ' . $attrs['name']);
		$this->appendDebug($this->varDump($this->elements[$attrs['name']]));
	}
}

class XMLSchema extends nusoap_xmlschema
{}


?>