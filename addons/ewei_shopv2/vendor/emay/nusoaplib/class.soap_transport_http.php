<?php
class soap_transport_http extends nusoap_base
{
	public $url = '';
	public $uri = '';
	public $digest_uri = '';
	public $scheme = '';
	public $host = '';
	public $port = '';
	public $path = '';
	public $request_method = 'POST';
	public $protocol_version = '1.0';
	public $encoding = '';
	public $outgoing_headers = array();
	public $incoming_headers = array();
	public $incoming_cookies = array();
	public $outgoing_payload = '';
	public $incoming_payload = '';
	public $response_status_line;
	public $useSOAPAction = true;
	public $persistentConnection = false;
	public $ch = false;
	public $ch_options = array();
	public $use_curl = false;
	public $proxy;
	public $username = '';
	public $password = '';
	public $authtype = '';
	public $digestRequest = array();
	public $certRequest = array();

	/**
	* constructor
	*
	* @param string $url The URL to which to connect
	* @param array $curl_options User-specified cURL options
	* @param boolean $use_curl Whether to try to force cURL use
	* @access public
	*/
	public function soap_transport_http($url, $curl_options = NULL, $use_curl = false)
	{
		parent::nusoap_base();
		$this->debug('ctor url=' . $url . ' use_curl=' . $use_curl . ' curl_options:');
		$this->appendDebug($this->varDump($curl_options));
		$this->setURL($url);

		if (is_array($curl_options)) {
			$this->ch_options = $curl_options;
		}


		$this->use_curl = $use_curl;
		preg_match('/\\$Revisio' . 'n: ([^ ]+)/', $this->revision, $rev);
		$this->setHeader('User-Agent', $this->title . '/' . $this->version . ' (' . $rev[1] . ')');
	}

	/**
	* sets a cURL option
	*
	* @param	mixed $option The cURL option (always integer?)
	* @param	mixed $value The cURL option value
	* @access   private
	*/
	public function setCurlOption($option, $value)
	{
		$this->debug('setCurlOption option=' . $option . ', value=');
		$this->appendDebug($this->varDump($value));
		curl_setopt($this->ch, $option, $value);
	}

	/**
	* sets an HTTP header
	*
	* @param string $name The name of the header
	* @param string $value The value of the header
	* @access private
	*/
	public function setHeader($name, $value)
	{
		$this->outgoing_headers[$name] = $value;
		$this->debug('set header ' . $name . ': ' . $value);
	}

	/**
	* unsets an HTTP header
	*
	* @param string $name The name of the header
	* @access private
	*/
	public function unsetHeader($name)
	{
		if (isset($this->outgoing_headers[$name])) {
			$this->debug('unset header ' . $name);
			unset($this->outgoing_headers[$name]);
		}

	}

	/**
	* sets the URL to which to connect
	*
	* @param string $url The URL to which to connect
	* @access private
	*/
	public function setURL($url)
	{
		$this->url = $url;
		$u = parse_url($url);

		foreach ($u as $k => $v ) {
			$this->debug('parsed URL ' . $k . ' = ' . $v);
			$this->$k = $v;
		}

		if (isset($u['query']) && ($u['query'] != '')) {
			$this->path .= '?' . $u['query'];
		}


		if (!(isset($u['port']))) {
			if ($u['scheme'] == 'https') {
				$this->port = 443;
			}
			 else {
				$this->port = 80;
			}
		}


		$this->uri = $this->path;
		$this->digest_uri = $this->uri;

		if (!(isset($u['port']))) {
			$this->setHeader('Host', $this->host);
		}
		 else {
			$this->setHeader('Host', $this->host . ':' . $this->port);
		}

		if (isset($u['user']) && ($u['user'] != '')) {
			$this->setCredentials(urldecode($u['user']), (isset($u['pass']) ? urldecode($u['pass']) : ''));
		}

	}

	/**
	* gets the I/O method to use
	*
	* @return	string	I/O method to use (socket|curl|unknown)
	* @access	private
	*/
	public function io_method()
	{
		if ($this->use_curl || ($this->scheme == 'https') || (($this->scheme == 'http') && ($this->authtype == 'ntlm')) || (($this->scheme == 'http') && is_array($this->proxy) && ($this->proxy['authtype'] == 'ntlm'))) {
			return 'curl';
		}


		if ((($this->scheme == 'http') || ($this->scheme == 'ssl')) && ($this->authtype != 'ntlm') && (!(is_array($this->proxy)) || ($this->proxy['authtype'] != 'ntlm'))) {
			return 'socket';
		}


		return 'unknown';
	}

	/**
	* establish an HTTP connection
	*
	* @param    integer $timeout set connection timeout in seconds
	* @param	integer $response_timeout set response timeout in seconds
	* @return	boolean true if connected, false if not
	* @access   private
	*/
	public function connect($connection_timeout = 0, $response_timeout = 30)
	{
		$this->debug('connect connection_timeout ' . $connection_timeout . ', response_timeout ' . $response_timeout . ', scheme ' . $this->scheme . ', host ' . $this->host . ', port ' . $this->port);

		if ($this->io_method() == 'socket') {
			if (!(is_array($this->proxy))) {
				$host = $this->host;
				$port = $this->port;
			}
			 else {
				$host = $this->proxy['host'];
				$port = $this->proxy['port'];
			}

			if ($this->persistentConnection && isset($this->fp) && is_resource($this->fp)) {
				if (!(feof($this->fp))) {
					$this->debug('Re-use persistent connection');
					return true;
				}


				fclose($this->fp);
				$this->debug('Closed persistent connection at EOF');
			}


			if ($this->scheme == 'ssl') {
				$host = 'ssl://' . $host;
			}


			$this->debug('calling fsockopen with host ' . $host . ' connection_timeout ' . $connection_timeout);

			if (0 < $connection_timeout) {
				$this->fp = @fsockopen($host, $this->port, $this->errno, $this->error_str, $connection_timeout);
			}
			 else {
				$this->fp = @fsockopen($host, $this->port, $this->errno, $this->error_str);
			}

			if (!($this->fp)) {
				$msg = 'Couldn\'t open socket connection to server ' . $this->url;

				if ($this->errno) {
					$msg .= ', Error (' . $this->errno . '): ' . $this->error_str;
				}
				 else {
					$msg .= ' prior to connect().  This is often a problem looking up the host name.';
				}

				$this->debug($msg);
				$this->setError($msg);
				return false;
			}


			$this->debug('set response timeout to ' . $response_timeout);
			socket_set_timeout($this->fp, $response_timeout);
			$this->debug('socket connected');
			return true;
		}


		if ($this->io_method() == 'curl') {
			if (!(extension_loaded('curl'))) {
				$this->setError('The PHP cURL Extension is required for HTTPS or NLTM.  You will need to re-build or update your PHP to include cURL or change php.ini to load the PHP cURL extension.');
				return false;
			}


			if (defined('CURLOPT_CONNECTIONTIMEOUT')) {
				$CURLOPT_CONNECTIONTIMEOUT = CURLOPT_CONNECTIONTIMEOUT;
			}
			 else {
				$CURLOPT_CONNECTIONTIMEOUT = 78;
			}

			if (defined('CURLOPT_HTTPAUTH')) {
				$CURLOPT_HTTPAUTH = CURLOPT_HTTPAUTH;
			}
			 else {
				$CURLOPT_HTTPAUTH = 107;
			}

			if (defined('CURLOPT_PROXYAUTH')) {
				$CURLOPT_PROXYAUTH = CURLOPT_PROXYAUTH;
			}
			 else {
				$CURLOPT_PROXYAUTH = 111;
			}

			if (defined('CURLAUTH_BASIC')) {
				$CURLAUTH_BASIC = CURLAUTH_BASIC;
			}
			 else {
				$CURLAUTH_BASIC = 1;
			}

			if (defined('CURLAUTH_DIGEST')) {
				$CURLAUTH_DIGEST = CURLAUTH_DIGEST;
			}
			 else {
				$CURLAUTH_DIGEST = 2;
			}

			if (defined('CURLAUTH_NTLM')) {
				$CURLAUTH_NTLM = CURLAUTH_NTLM;
			}
			 else {
				$CURLAUTH_NTLM = 8;
			}

			$this->debug('connect using cURL');
			$this->ch = curl_init();
			$hostURL = (($this->port != '' ? $this->scheme . '://' . $this->host . ':' . $this->port : $this->scheme . '://' . $this->host));
			$hostURL .= $this->path;
			$this->setCurlOption(CURLOPT_URL, $hostURL);
			if (ini_get('safe_mode') || ini_get('open_basedir')) {
				$this->debug('safe_mode or open_basedir set, so do not set CURLOPT_FOLLOWLOCATION');
				$this->debug('safe_mode = ');
				$this->appendDebug($this->varDump(ini_get('safe_mode')));
				$this->debug('open_basedir = ');
				$this->appendDebug($this->varDump(ini_get('open_basedir')));
			}
			 else {
				$this->setCurlOption(CURLOPT_FOLLOWLOCATION, 1);
			}

			$this->setCurlOption(CURLOPT_HEADER, 1);
			$this->setCurlOption(CURLOPT_RETURNTRANSFER, 1);

			if ($this->persistentConnection) {
				$this->persistentConnection = false;
				$this->setHeader('Connection', 'close');
			}


			if ($connection_timeout != 0) {
				$this->setCurlOption($CURLOPT_CONNECTIONTIMEOUT, $connection_timeout);
			}


			if ($response_timeout != 0) {
				$this->setCurlOption(CURLOPT_TIMEOUT, $response_timeout);
			}


			if ($this->scheme == 'https') {
				$this->debug('set cURL SSL verify options');
				$this->setCurlOption(CURLOPT_SSL_VERIFYPEER, 0);
				$this->setCurlOption(CURLOPT_SSL_VERIFYHOST, 0);

				if ($this->authtype == 'certificate') {
					$this->debug('set cURL certificate options');

					if (isset($this->certRequest['cainfofile'])) {
						$this->setCurlOption(CURLOPT_CAINFO, $this->certRequest['cainfofile']);
					}


					if (isset($this->certRequest['verifypeer'])) {
						$this->setCurlOption(CURLOPT_SSL_VERIFYPEER, $this->certRequest['verifypeer']);
					}
					 else {
						$this->setCurlOption(CURLOPT_SSL_VERIFYPEER, 1);
					}

					if (isset($this->certRequest['verifyhost'])) {
						$this->setCurlOption(CURLOPT_SSL_VERIFYHOST, $this->certRequest['verifyhost']);
					}
					 else {
						$this->setCurlOption(CURLOPT_SSL_VERIFYHOST, 1);
					}

					if (isset($this->certRequest['sslcertfile'])) {
						$this->setCurlOption(CURLOPT_SSLCERT, $this->certRequest['sslcertfile']);
					}


					if (isset($this->certRequest['sslkeyfile'])) {
						$this->setCurlOption(CURLOPT_SSLKEY, $this->certRequest['sslkeyfile']);
					}


					if (isset($this->certRequest['passphrase'])) {
						$this->setCurlOption(CURLOPT_SSLKEYPASSWD, $this->certRequest['passphrase']);
					}


					if (isset($this->certRequest['certpassword'])) {
						$this->setCurlOption(CURLOPT_SSLCERTPASSWD, $this->certRequest['certpassword']);
					}

				}

			}


			if ($this->authtype && ($this->authtype != 'certificate')) {
				if ($this->username) {
					$this->debug('set cURL username/password');
					$this->setCurlOption(CURLOPT_USERPWD, $this->username . ':' . $this->password);
				}


				if ($this->authtype == 'basic') {
					$this->debug('set cURL for Basic authentication');
					$this->setCurlOption($CURLOPT_HTTPAUTH, $CURLAUTH_BASIC);
				}


				if ($this->authtype == 'digest') {
					$this->debug('set cURL for digest authentication');
					$this->setCurlOption($CURLOPT_HTTPAUTH, $CURLAUTH_DIGEST);
				}


				if ($this->authtype == 'ntlm') {
					$this->debug('set cURL for NTLM authentication');
					$this->setCurlOption($CURLOPT_HTTPAUTH, $CURLAUTH_NTLM);
				}

			}


			if (is_array($this->proxy)) {
				$this->debug('set cURL proxy options');

				if ($this->proxy['port'] != '') {
					$this->setCurlOption(CURLOPT_PROXY, $this->proxy['host'] . ':' . $this->proxy['port']);
				}
				 else {
					$this->setCurlOption(CURLOPT_PROXY, $this->proxy['host']);
				}

				if ($this->proxy['username'] || $this->proxy['password']) {
					$this->debug('set cURL proxy authentication options');
					$this->setCurlOption(CURLOPT_PROXYUSERPWD, $this->proxy['username'] . ':' . $this->proxy['password']);

					if ($this->proxy['authtype'] == 'basic') {
						$this->setCurlOption($CURLOPT_PROXYAUTH, $CURLAUTH_BASIC);
					}


					if ($this->proxy['authtype'] == 'ntlm') {
						$this->setCurlOption($CURLOPT_PROXYAUTH, $CURLAUTH_NTLM);
					}

				}

			}


			$this->debug('cURL connection set up');
			return true;
		}


		$this->setError('Unknown scheme ' . $this->scheme);
		$this->debug('Unknown scheme ' . $this->scheme);
		return false;
	}

	/**
	* sends the SOAP request and gets the SOAP response via HTTP[S]
	*
	* @param    string $data message data
	* @param    integer $timeout set connection timeout in seconds
	* @param	integer $response_timeout set response timeout in seconds
	* @param	array $cookies cookies to send
	* @return	string data
	* @access   public
	*/
	public function send($data, $timeout = 0, $response_timeout = 30, $cookies = NULL)
	{
		$this->debug('entered send() with data of length: ' . strlen($data));
		$this->tryagain = true;
		$tries = 0;

		while ($this->tryagain) {
			$this->tryagain = false;

			if ($tries++ < 2) {
				if (!($this->connect($timeout, $response_timeout))) {
					return false;
				}


				if (!($this->sendRequest($data, $cookies))) {
					return false;
				}


				$respdata = $this->getResponse();
			}
			 else {
				$this->setError('Too many tries to get an OK response (' . $this->response_status_line . ')');
			}
		}

		$this->debug('end of send()');
		return $respdata;
	}

	/**
	* sends the SOAP request and gets the SOAP response via HTTPS using CURL
	*
	* @param    string $data message data
	* @param    integer $timeout set connection timeout in seconds
	* @param	integer $response_timeout set response timeout in seconds
	* @param	array $cookies cookies to send
	* @return	string data
	* @access   public
	* @deprecated
	*/
	public function sendHTTPS($data, $timeout = 0, $response_timeout = 30, $cookies)
	{
		return $this->send($data, $timeout, $response_timeout, $cookies);
	}

	/**
	* if authenticating, set user credentials here
	*
	* @param    string $username
	* @param    string $password
	* @param	string $authtype (basic|digest|certificate|ntlm)
	* @param	array $digestRequest (keys must be nonce, nc, realm, qop)
	* @param	array $certRequest (keys must be cainfofile (optional), sslcertfile, sslkeyfile, passphrase, certpassword (optional), verifypeer (optional), verifyhost (optional): see corresponding options in cURL docs)
	* @access   public
	*/
	public function setCredentials($username, $password, $authtype = 'basic', $digestRequest = array(), $certRequest = array())
	{
		$this->debug('setCredentials username=' . $username . ' authtype=' . $authtype . ' digestRequest=');
		$this->appendDebug($this->varDump($digestRequest));
		$this->debug('certRequest=');
		$this->appendDebug($this->varDump($certRequest));

		if ($authtype == 'basic') {
			$this->setHeader('Authorization', 'Basic ' . base64_encode(str_replace(':', '', $username) . ':' . $password));
		}
		 else if ($authtype == 'digest') {
			if (isset($digestRequest['nonce'])) {
				$digestRequest['nc'] = ((isset($digestRequest['nc']) ? $digestRequest['nc']++ : 1));
				$A1 = $username . ':' . ((isset($digestRequest['realm']) ? $digestRequest['realm'] : '')) . ':' . $password;
				$HA1 = md5($A1);
				$A2 = $this->request_method . ':' . $this->digest_uri;
				$HA2 = md5($A2);
				$unhashedDigest = '';
				$nonce = ((isset($digestRequest['nonce']) ? $digestRequest['nonce'] : ''));
				$cnonce = $nonce;

				if ($digestRequest['qop'] != '') {
					$unhashedDigest = $HA1 . ':' . $nonce . ':' . sprintf('%08d', $digestRequest['nc']) . ':' . $cnonce . ':' . $digestRequest['qop'] . ':' . $HA2;
				}
				 else {
					$unhashedDigest = $HA1 . ':' . $nonce . ':' . $HA2;
				}

				$hashedDigest = md5($unhashedDigest);
				$opaque = '';

				if (isset($digestRequest['opaque'])) {
					$opaque = ', opaque="' . $digestRequest['opaque'] . '"';
				}


				$this->setHeader('Authorization', 'Digest username="' . $username . '", realm="' . $digestRequest['realm'] . '", nonce="' . $nonce . '", uri="' . $this->digest_uri . $opaque . '", cnonce="' . $cnonce . '", nc=' . sprintf('%08x', $digestRequest['nc']) . ', qop="' . $digestRequest['qop'] . '", response="' . $hashedDigest . '"');
			}

		}
		 else if ($authtype == 'certificate') {
			$this->certRequest = $certRequest;
			$this->debug('Authorization header not set for certificate');
		}
		 else if ($authtype == 'ntlm') {
			$this->debug('Authorization header not set for ntlm');
		}


		$this->username = $username;
		$this->password = $password;
		$this->authtype = $authtype;
		$this->digestRequest = $digestRequest;
	}

	/**
	* set the soapaction value
	*
	* @param    string $soapaction
	* @access   public
	*/
	public function setSOAPAction($soapaction)
	{
		$this->setHeader('SOAPAction', '"' . $soapaction . '"');
	}

	/**
	* use http encoding
	*
	* @param    string $enc encoding style. supported values: gzip, deflate, or both
	* @access   public
	*/
	public function setEncoding($enc = 'gzip, deflate')
	{
		if (function_exists('gzdeflate')) {
			$this->protocol_version = '1.1';
			$this->setHeader('Accept-Encoding', $enc);

			if (!(isset($this->outgoing_headers['Connection']))) {
				$this->setHeader('Connection', 'close');
				$this->persistentConnection = false;
			}


			$this->encoding = $enc;
		}

	}

	/**
	* set proxy info here
	*
	* @param    string $proxyhost use an empty string to remove proxy
	* @param    string $proxyport
	* @param	string $proxyusername
	* @param	string $proxypassword
	* @param	string $proxyauthtype (basic|ntlm)
	* @access   public
	*/
	public function setProxy($proxyhost, $proxyport, $proxyusername = '', $proxypassword = '', $proxyauthtype = 'basic')
	{
		if ($proxyhost) {
			$this->proxy = array('host' => $proxyhost, 'port' => $proxyport, 'username' => $proxyusername, 'password' => $proxypassword, 'authtype' => $proxyauthtype);

			if ($proxyauthtype = 'basic') {
				$this->setHeader('Proxy-Authorization', ' Basic ' . base64_encode($proxyusername . ':' . $proxypassword));
			}


		}
		 else {
			$this->debug('remove proxy');
			$proxy = NULL;
			unsetHeader('Proxy-Authorization');
		}
	}

	/**
	 * Test if the given string starts with a header that is to be skipped.
	 * Skippable headers result from chunked transfer and proxy requests.
	 *
	 * @param	string $data The string to check.
	 * @returns	boolean	Whether a skippable header was found.
	 * @access	private
	 */
	public function isSkippableCurlHeader(&$data)
	{
		$skipHeaders = array('HTTP/1.1 100', 'HTTP/1.0 301', 'HTTP/1.1 301', 'HTTP/1.0 302', 'HTTP/1.1 302', 'HTTP/1.0 401', 'HTTP/1.1 401', 'HTTP/1.0 200 Connection established');

		foreach ($skipHeaders as $hd ) {
			if ($prefix == $hd) {
				$prefix = substr($data, 0, strlen($hd));

				return true;
			}
		}

		return false;
	}

	/**
	* decode a string that is encoded w/ "chunked' transfer encoding
 	* as defined in RFC2068 19.4.6
	*
	* @param    string $buffer
	* @param    string $lb
	* @returns	string
	* @access   public
	* @deprecated
	*/
	public function decodeChunked($buffer, $lb)
	{
		$length = 0;
		$new = '';
		$chunkend = strpos($buffer, $lb);

		if ($chunkend == false) {
			$this->debug('no linebreak found in decodeChunked');
			return $new;
		}


		$temp = substr($buffer, 0, $chunkend);
		$chunk_size = hexdec(trim($temp));
		$chunkstart = $chunkend + strlen($lb);

		while (0 < $chunk_size) {
			$this->debug('chunkstart: ' . $chunkstart . ' chunk_size: ' . $chunk_size);
			$chunkend = strpos($buffer, $lb, $chunkstart + $chunk_size);

			if ($chunkend == false) {
				$chunk = substr($buffer, $chunkstart);
				$new .= $chunk;
				$length += strlen($chunk);
				break;
			}


			$chunk = substr($buffer, $chunkstart, $chunkend - $chunkstart);
			$new .= $chunk;
			$length += strlen($chunk);
			$chunkstart = $chunkend + strlen($lb);
			$chunkend = strpos($buffer, $lb, $chunkstart) + strlen($lb);

			if ($chunkend == false) {
				break;
			}


			$temp = substr($buffer, $chunkstart, $chunkend - $chunkstart);
			$chunk_size = hexdec(trim($temp));
			$chunkstart = $chunkend;
		}

		return $new;
	}

	/**
	 * Writes the payload, including HTTP headers, to $this->outgoing_payload.
	 *
	 * @param	string $data HTTP body
	 * @param	string $cookie_str data for HTTP Cookie header
	 * @return	void
	 * @access	private
	 */
	public function buildPayload($data, $cookie_str = '')
	{
		if ($this->request_method != 'GET') {
			$this->setHeader('Content-Length', strlen($data));
		}


		if ($this->proxy) {
			$uri = $this->url;
		}
		 else {
			$uri = $this->uri;
		}

		$req = $this->request_method . ' ' . $uri . ' HTTP/' . $this->protocol_version;
		$this->debug('HTTP request: ' . $req);
		$this->outgoing_payload = $req . "\r\n";

		foreach ($this->outgoing_headers as $k => $v ) {
			$hdr = $k . ': ' . $v;
			$this->debug('HTTP header: ' . $hdr);
			$this->outgoing_payload .= $hdr . "\r\n";
		}

		if ($cookie_str != '') {
			$hdr = 'Cookie: ' . $cookie_str;
			$this->debug('HTTP header: ' . $hdr);
			$this->outgoing_payload .= $hdr . "\r\n";
		}


		$this->outgoing_payload .= "\r\n";
		$this->outgoing_payload .= $data;
	}

	/**
	* sends the SOAP request via HTTP[S]
	*
	* @param    string $data message data
	* @param	array $cookies cookies to send
	* @return	boolean	true if OK, false if problem
	* @access   private
	*/
	public function sendRequest($data, $cookies = NULL)
	{
		$cookie_str = $this->getCookiesForRequest($cookies, ($this->scheme == 'ssl') || ($this->scheme == 'https'));
		$this->buildPayload($data, $cookie_str);

		if ($this->io_method() == 'socket') {
			if (!(fputs($this->fp, $this->outgoing_payload, strlen($this->outgoing_payload)))) {
				$this->setError('couldn\'t write message data to socket');
				$this->debug('couldn\'t write message data to socket');
				return false;
			}


			$this->debug('wrote data to socket, length = ' . strlen($this->outgoing_payload));
			return true;
		}


		if ($this->io_method() == 'curl') {
			$curl_headers = array();

			foreach ($this->outgoing_headers as $k => $v ) {
				if (($k == 'Connection') || ($k == 'Content-Length') || ($k == 'Host') || ($k == 'Authorization') || ($k == 'Proxy-Authorization')) {
					$this->debug('Skip cURL header ' . $k . ': ' . $v);
				}
				 else {
					$curl_headers[] = $k . ': ' . $v;
				}
			}

			if ($cookie_str != '') {
				$curl_headers[] = 'Cookie: ' . $cookie_str;
			}


			$this->setCurlOption(CURLOPT_HTTPHEADER, $curl_headers);
			$this->debug('set cURL HTTP headers');

			if ($this->request_method == 'POST') {
				$this->setCurlOption(CURLOPT_POST, 1);
				$this->setCurlOption(CURLOPT_POSTFIELDS, $data);
				$this->debug('set cURL POST data');
			}


			foreach ($this->ch_options as $key => $val ) {
				$this->setCurlOption($key, $val);
			}

			$this->debug('set cURL payload');
			return true;
		}

	}

	/**
	* gets the SOAP response via HTTP[S]
	*
	* @return	string the response (also sets member variables like incoming_payload)
	* @access   private
	*/
	public function getResponse()
	{
		$this->incoming_payload = '';

		if ($this->io_method() == 'socket') {
			$data = '';

			while (!(isset($lb))) {
				if (feof($this->fp)) {
					$this->incoming_payload = $data;
					$this->debug('found no headers before EOF after length ' . strlen($data));
					$this->debug('received before EOF:' . "\n" . $data);
					$this->setError('server failed to send headers');
					return false;
				}


				$tmp = fgets($this->fp, 256);
				$tmplen = strlen($tmp);
				$this->debug('read line of ' . $tmplen . ' bytes: ' . trim($tmp));

				if ($tmplen == 0) {
					$this->incoming_payload = $data;
					$this->debug('socket read of headers timed out after length ' . strlen($data));
					$this->debug('read before timeout: ' . $data);
					$this->setError('socket read of headers timed out');
					return false;
				}


				$data .= $tmp;
				$pos = strpos($data, "\r\n\r\n");

				if (1 < $pos) {
					$lb = "\r\n";
				}
				 else {
					$pos = strpos($data, "\n\n");

					if (1 < $pos) {
						$lb = "\n";
					}

				}

				if (isset($lb) && preg_match('/^HTTP\\/1.1 100/', $data)) {
					unset($lb);
					$data = '';
				}

			}

			$this->incoming_payload .= $data;
			$this->debug('found end of headers after length ' . strlen($data));
			$header_data = trim(substr($data, 0, $pos));
			$header_array = explode($lb, $header_data);
			$this->incoming_headers = array();
			$this->incoming_cookies = array();

			foreach ($header_array as $header_line ) {
				$arr = explode(':', $header_line, 2);

				if (1 < count($arr)) {
					$header_name = strtolower(trim($arr[0]));
					$this->incoming_headers[$header_name] = trim($arr[1]);

					if ($header_name == 'set-cookie') {
						$cookie = $this->parseCookie(trim($arr[1]));

						if ($cookie) {
							$this->incoming_cookies[] = $cookie;
							$this->debug('found cookie: ' . $cookie['name'] . ' = ' . $cookie['value']);
						}
						 else {
							$this->debug('did not find cookie in ' . trim($arr[1]));
						}
					}

				}
				 else if (isset($header_name)) {
					$this->incoming_headers[$header_name] .= $lb . ' ' . $header_line;
				}

			}

			if (isset($this->incoming_headers['transfer-encoding']) && (strtolower($this->incoming_headers['transfer-encoding']) == 'chunked')) {
				$content_length = 2147483647;
				$chunked = true;
				$this->debug('want to read chunked content');
			}
			 else if (isset($this->incoming_headers['content-length'])) {
				$content_length = $this->incoming_headers['content-length'];
				$chunked = false;
				$this->debug('want to read content of length ' . $content_length);
			}
			 else {
				$content_length = 2147483647;
				$chunked = false;
				$this->debug('want to read content to EOF');
			}

			$data = '';

			if ($chunked) {
				$tmp = fgets($this->fp, 256);
				$tmplen = strlen($tmp);
				$this->debug('read chunk line of ' . $tmplen . ' bytes');

				if ($tmplen == 0) {
					$this->incoming_payload = $data;
					$this->debug('socket read of chunk length timed out after length ' . strlen($data));
					$this->debug('read before timeout:' . "\n" . $data);
					$this->setError('socket read of chunk length timed out');
					return false;
				}


				$content_length = hexdec(trim($tmp));
				$this->debug('chunk length ' . $content_length);
			}
			 else {
				$strlen = 0;

				while (('chunk length ' . $content_length) && ('chunk length ' . $content_length) && !(feof($this->fp))) {
					$readlen = min(8192, $content_length - $strlen);
					$tmp = fread($this->fp, $readlen);
					$tmplen = strlen($tmp);
					$this->debug('read buffer of ' . $tmplen . ' bytes');

					if (($tmplen == 0) && !(feof($this->fp))) {
						$this->incoming_payload = $data;
						$this->debug('socket read of body timed out after length ' . strlen($data));
						$this->debug('read before timeout:' . "\n" . $data);
						$this->setError('socket read of body timed out');
						return false;
					}


					$strlen += $tmplen;
					$data .= $tmp;
				}
				if ($chunked && (0 < $content_length)) {
					$tmp = fgets($this->fp, 256);
					$tmplen = strlen($tmp);
					$this->debug('read chunk terminator of ' . $tmplen . ' bytes');

					if ($tmplen == 0) {
						$this->incoming_payload = $data;
						$this->debug('socket read of chunk terminator timed out after length ' . strlen($data));
						$this->debug('read before timeout:' . "\n" . $data);
						$this->setError('socket read of chunk terminator timed out');
						return false;
					}

				}


			}

			if (feof($this->fp)) {
				$this->debug('read to EOF');
			}


			$this->debug('read body of length ' . strlen($data));
			$this->incoming_payload .= $data;
			$this->debug('received a total of ' . strlen($this->incoming_payload) . ' bytes of data from server');
			if ((isset($this->incoming_headers['connection']) && (strtolower($this->incoming_headers['connection']) == 'close')) || !($this->persistentConnection) || feof($this->fp)) {
				fclose($this->fp);
				$this->fp = false;
				$this->debug('closed socket');
			}


			if ($this->incoming_payload == '') {
				$this->setError('no response from server');
				return false;

				if ($this->io_method() == 'curl') {
					$this->debug('send and receive with cURL');
					$this->incoming_payload = curl_exec($this->ch);
					$data = $this->incoming_payload;
					$cErr = curl_error($this->ch);

					if ($cErr != '') {
						$err = 'cURL ERROR: ' . curl_errno($this->ch) . ': ' . $cErr . '<br>';

						foreach (curl_getinfo($this->ch) as $k => $v ) {
							$err .= $k . ': ' . $v . '<br>';
						}

						$this->debug($err);
						$this->setError($err);
						curl_close($this->ch);
						return false;
					}


					$this->debug('No cURL error, closing cURL');
					curl_close($this->ch);
					$savedata = $data;

					while ($this->isSkippableCurlHeader($data)) {
						$this->debug('Found HTTP header to skip');

						if ($pos = strpos($data, "\r\n\r\n")) {
							$data = ltrim(substr($data, $pos));
						}
						 else if ($pos = strpos($data, "\n\n")) {
							$data = ltrim(substr($data, $pos));
						}

					}

					if ($data == '') {
						$data = $savedata;

						while (preg_match('/^HTTP\\/1.1 100/', $data)) {
							if ($pos = strpos($data, "\r\n\r\n")) {
								$data = ltrim(substr($data, $pos));
							}
							 else if ($pos = strpos($data, "\n\n")) {
								$data = ltrim(substr($data, $pos));
							}

						}
					}


					if ($pos = strpos($data, "\r\n\r\n")) {
						$lb = "\r\n";
					}
					 else if ($pos = strpos($data, "\n\n")) {
						$lb = "\n";
					}
					 else {
						$this->debug('no proper separation of headers and document');
						$this->setError('no proper separation of headers and document');
						return false;
					}

					$header_data = trim(substr($data, 0, $pos));
					$header_array = explode($lb, $header_data);
					$data = ltrim(substr($data, $pos));
					$this->debug('found proper separation of headers and document');
					$this->debug('cleaned data, stringlen: ' . strlen($data));

					foreach ($header_array as $header_line ) {
						$arr = explode(':', $header_line, 2);

						if (1 < count($arr)) {
							$header_name = strtolower(trim($arr[0]));
							$this->incoming_headers[$header_name] = trim($arr[1]);

							if ($header_name == 'set-cookie') {
								$cookie = $this->parseCookie(trim($arr[1]));

								if ($cookie) {
									$this->incoming_cookies[] = $cookie;
									$this->debug('found cookie: ' . $cookie['name'] . ' = ' . $cookie['value']);
								}
								 else {
									$this->debug('did not find cookie in ' . trim($arr[1]));
								}
							}

						}
						 else if (isset($header_name)) {
							$this->incoming_headers[$header_name] .= $lb . ' ' . $header_line;
						}

					}
				}

			}

		}
		 else {
			$this->debug('send and receive with cURL');
			$this->incoming_payload = curl_exec($this->ch);
			$data = $this->incoming_payload;
			$cErr = curl_error($this->ch);
			$err = 'cURL ERROR: ' . curl_errno($this->ch) . ': ' . $cErr . '<br>';
			$err .= $k . ': ' . $v . '<br>';
			$this->debug($err);
			$this->setError($err);
			curl_close($this->ch);
			return false;
			$this->debug('No cURL error, closing cURL');
			curl_close($this->ch);
			$savedata = $data;
			$this->debug('Found HTTP header to skip');
			$data = ltrim(substr($data, $pos));
			$data = ltrim(substr($data, $pos));
			$data = $savedata;
			$data = ltrim(substr($data, $pos));
			$data = ltrim(substr($data, $pos));
			$lb = "\r\n";
			$lb = "\n";
			$this->debug('no proper separation of headers and document');
			$this->setError('no proper separation of headers and document');
			return false;
			$header_data = trim(substr($data, 0, $pos));
			$header_array = explode($lb, $header_data);
			$data = ltrim(substr($data, $pos));
			$this->debug('found proper separation of headers and document');
			$this->debug('cleaned data, stringlen: ' . strlen($data));
			$arr = explode(':', $header_line, 2);
			$header_name = strtolower(trim($arr[0]));
			$this->incoming_headers[$header_name] = trim($arr[1]);
			$cookie = $this->parseCookie(trim($arr[1]));
			$this->incoming_cookies[] = $cookie;
			$this->debug('found cookie: ' . $cookie['name'] . ' = ' . $cookie['value']);
			$this->debug('did not find cookie in ' . trim($arr[1]));
			$this->incoming_headers[$header_name] .= $lb . ' ' . $header_line;
		}

		$this->response_status_line = $header_array[0];
		$arr = explode(' ', $this->response_status_line, 3);
		$http_version = $arr[0];
		$http_status = intval($arr[1]);
		$http_reason = ((2 < count($arr) ? $arr[2] : ''));

		if (isset($this->incoming_headers['location']) && (($http_status == 301) || ($http_status == 302))) {
			$this->debug('Got ' . $http_status . ' ' . $http_reason . ' with Location: ' . $this->incoming_headers['location']);
			$this->setURL($this->incoming_headers['location']);
			$this->tryagain = true;
			return false;
		}


		if (isset($this->incoming_headers['www-authenticate']) && ($http_status == 401)) {
			$this->debug('Got 401 ' . $http_reason . ' with WWW-Authenticate: ' . $this->incoming_headers['www-authenticate']);

			if (strstr($this->incoming_headers['www-authenticate'], 'Digest ')) {
				$this->debug('Server wants digest authentication');
				$digestString = str_replace('Digest ', '', $this->incoming_headers['www-authenticate']);
				$digestElements = explode(',', $digestString);

				foreach ($digestElements as $val ) {
					$tempElement = explode('=', trim($val), 2);
					$digestRequest[$tempElement[0]] = str_replace('"', '', $tempElement[1]);
				}

				if (isset($digestRequest['nonce'])) {
					$this->setCredentials($this->username, $this->password, 'digest', $digestRequest);
					$this->tryagain = true;
					return false;
				}

			}


			$this->debug('HTTP authentication failed');
			$this->setError('HTTP authentication failed');
			return false;
		}


		if (((300 <= $http_status) && ($http_status <= 307)) || ((400 <= $http_status) && ($http_status <= 417)) || ((501 <= $http_status) && ($http_status <= 505))) {
			$this->setError('Unsupported HTTP response status ' . $http_status . ' ' . $http_reason . ' (soapclient->response has contents of the response)');
			return false;
		}


		if (isset($this->incoming_headers['content-encoding']) && ($this->incoming_headers['content-encoding'] != '')) {
			if ((strtolower($this->incoming_headers['content-encoding']) == 'deflate') || (strtolower($this->incoming_headers['content-encoding']) == 'gzip')) {
				if (function_exists('gzinflate')) {
					$this->debug('The gzinflate function exists');
					$datalen = strlen($data);

					if ($this->incoming_headers['content-encoding'] == 'deflate') {
						if ($degzdata = @gzinflate($data)) {
							$data = $degzdata;
							$this->debug('The payload has been inflated to ' . strlen($data) . ' bytes');

							if (strlen($data) < $datalen) {
								$this->debug('The inflated payload is smaller than the gzipped one; try again');

								if ($degzdata = @gzinflate($data)) {
									$data = $degzdata;
									$this->debug('The payload has been inflated again to ' . strlen($data) . ' bytes');
								}

							}

						}
						 else {
							$this->debug('Error using gzinflate to inflate the payload');
							$this->setError('Error using gzinflate to inflate the payload');
						}
					}
					 else if ($this->incoming_headers['content-encoding'] == 'gzip') {
						if ($degzdata = @gzinflate(substr($data, 10))) {
							$data = $degzdata;
							$this->debug('The payload has been un-gzipped to ' . strlen($data) . ' bytes');

							if (strlen($data) < $datalen) {
								$this->debug('The un-gzipped payload is smaller than the gzipped one; try again');

								if ($degzdata = @gzinflate(substr($data, 10))) {
									$data = $degzdata;
									$this->debug('The payload has been un-gzipped again to ' . strlen($data) . ' bytes');
								}

							}

						}
						 else {
							$this->debug('Error using gzinflate to un-gzip the payload');
							$this->setError('Error using gzinflate to un-gzip the payload');
						}
					}


					$this->incoming_payload = $header_data . $lb . $lb . $data;
				}
				 else {
					$this->debug('The server sent compressed data. Your php install must have the Zlib extension compiled in to support this.');
					$this->setError('The server sent compressed data. Your php install must have the Zlib extension compiled in to support this.');
				}
			}
			 else {
				$this->debug('Unsupported Content-Encoding ' . $this->incoming_headers['content-encoding']);
				$this->setError('Unsupported Content-Encoding ' . $this->incoming_headers['content-encoding']);
			}
		}
		 else {
			$this->debug('No Content-Encoding header');
		}

		if (strlen($data) == 0) {
			$this->debug('no data after headers!');
			$this->setError('no data present after HTTP headers');
			return false;
		}


		return $data;
	}

	/**
	 * sets the content-type for the SOAP message to be sent
	 *
	 * @param	string $type the content type, MIME style
	 * @param	mixed $charset character set used for encoding (or false)
	 * @access	public
	 */
	public function setContentType($type, $charset = false)
	{
		$this->setHeader('Content-Type', $type . (($charset ? '; charset=' . $charset : '')));
	}

	/**
	 * specifies that an HTTP persistent connection should be used
	 *
	 * @return	boolean whether the request was honored by this method.
	 * @access	public
	 */
	public function usePersistentConnection()
	{
		if (isset($this->outgoing_headers['Accept-Encoding'])) {
			return false;
		}


		$this->protocol_version = '1.1';
		$this->persistentConnection = true;
		$this->setHeader('Connection', 'Keep-Alive');
		return true;
	}

	/**
	 * parse an incoming Cookie into it's parts
	 *
	 * @param	string $cookie_str content of cookie
	 * @return	array with data of that cookie
	 * @access	private
	 */
	public function parseCookie($cookie_str)
	{
		$cookie_str = str_replace('; ', ';', $cookie_str) . ';';
		$data = preg_split('/;/', $cookie_str);
		$value_str = $data[0];
		$cookie_param = 'domain=';
		$start = strpos($cookie_str, $cookie_param);

		if (0 < $start) {
			$domain = substr($cookie_str, $start + strlen($cookie_param));
			$domain = substr($domain, 0, strpos($domain, ';'));
		}
		 else {
			$domain = '';
		}

		$cookie_param = 'expires=';
		$start = strpos($cookie_str, $cookie_param);

		if (0 < $start) {
			$expires = substr($cookie_str, $start + strlen($cookie_param));
			$expires = substr($expires, 0, strpos($expires, ';'));
		}
		 else {
			$expires = '';
		}

		$cookie_param = 'path=';
		$start = strpos($cookie_str, $cookie_param);

		if (0 < $start) {
			$path = substr($cookie_str, $start + strlen($cookie_param));
			$path = substr($path, 0, strpos($path, ';'));
		}
		 else {
			$path = '/';
		}

		$cookie_param = ';secure;';

		if (strpos($cookie_str, $cookie_param) !== false) {
			$secure = true;
		}
		 else {
			$secure = false;
		}

		$sep_pos = strpos($value_str, '=');

		if ($sep_pos) {
			$name = substr($value_str, 0, $sep_pos);
			$value = substr($value_str, $sep_pos + 1);
			$cookie = array('name' => $name, 'value' => $value, 'domain' => $domain, 'path' => $path, 'expires' => $expires, 'secure' => $secure);
			return $cookie;
		}


		return false;
	}

	/**
	 * sort out cookies for the current request
	 *
	 * @param	array $cookies array with all cookies
	 * @param	boolean $secure is the send-content secure or not?
	 * @return	string for Cookie-HTTP-Header
	 * @access	private
	 */
	public function getCookiesForRequest($cookies, $secure = false)
	{
		$cookie_str = '';

		if (!(is_null($cookies)) && is_array($cookies)) {
			foreach ($cookies as $cookie ) {
				if (!(is_array($cookie))) {
					continue;
				}


				$this->debug('check cookie for validity: ' . $cookie['name'] . '=' . $cookie['value']);

				if (isset($cookie['expires']) && !(empty($cookie['expires']))) {
					if (strtotime($cookie['expires']) <= time()) {
						$this->debug('cookie has expired');
						continue;
					}

				}


				if (isset($cookie['domain']) && !(empty($cookie['domain']))) {
					$domain = preg_quote($cookie['domain']);

					if (!(preg_match('\'.*' . $domain . '$\'i', $this->host))) {
						$this->debug('cookie has different domain');
						continue;
					}

				}


				if (isset($cookie['path']) && !(empty($cookie['path']))) {
					$path = preg_quote($cookie['path']);

					if (!(preg_match('\'^' . $path . '.*\'i', $this->path))) {
						$this->debug('cookie is for a different path');
						continue;
					}

				}


				if (!($secure) && isset($cookie['secure']) && $cookie['secure']) {
					$this->debug('cookie is secure, transport is not');
					continue;
				}


				$cookie_str .= $cookie['name'] . '=' . $cookie['value'] . '; ';
				$this->debug('add cookie to Cookie-String: ' . $cookie['name'] . '=' . $cookie['value']);
			}
		}


		return $cookie_str;
	}
}


?>