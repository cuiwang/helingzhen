<?php
class nusoap_wsdlcache
{
	/**
	 *	@var resource
	 *	@access private
	 */
	public $fplock;
	/**
	 *	@var integer
	 *	@access private
	 */
	public $cache_lifetime;
	/**
	 *	@var string
	 *	@access private
	 */
	public $cache_dir;
	/**
	 *	@var string
	 *	@access public
	 */
	public $debug_str = '';

	/**
	* constructor
	*
	* @param string $cache_dir directory for cache-files
	* @param integer $cache_lifetime lifetime for caching-files in seconds or 0 for unlimited
	* @access public
	*/
	public function nusoap_wsdlcache($cache_dir = '.', $cache_lifetime = 0)
	{
		$this->fplock = array();
		$this->cache_dir = (($cache_dir != '' ? $cache_dir : '.'));
		$this->cache_lifetime = $cache_lifetime;
	}

	/**
	* creates the filename used to cache a wsdl instance
	*
	* @param string $wsdl The URL of the wsdl instance
	* @return string The filename used to cache the instance
	* @access private
	*/
	public function createFilename($wsdl)
	{
		return $this->cache_dir . '/wsdlcache-' . md5($wsdl);
	}

	/**
	* adds debug data to the class level debug string
	*
	* @param    string $string debug data
	* @access   private
	*/
	public function debug($string)
	{
		$this->debug_str .= get_class($this) . ': ' . $string . "\n";
	}

	/**
	* gets a wsdl instance from the cache
	*
	* @param string $wsdl The URL of the wsdl instance
	* @return object wsdl The cached wsdl instance, null if the instance is not in the cache
	* @access public
	*/
	public function get($wsdl)
	{
		$filename = $this->createFilename($wsdl);

		if ($this->obtainMutex($filename, 'r')) {
			if (0 < $this->cache_lifetime) {
				if (file_exists($filename) && ($this->cache_lifetime < (time() - filemtime($filename)))) {
					unlink($filename);
					$this->debug('Expired ' . $wsdl . ' (' . $filename . ') from cache');
					$this->releaseMutex($filename);
					return;
				}

			}


			if (!(file_exists($filename))) {
				$this->debug($wsdl . ' (' . $filename . ') not in cache (1)');
				$this->releaseMutex($filename);
				return;
			}


			$fp = @fopen($filename, 'r');

			if ($fp) {
				$s = implode('', @file($filename));
				fclose($fp);
				$this->debug('Got ' . $wsdl . ' (' . $filename . ') from cache');
			}
			 else {
				$s = NULL;
				$this->debug($wsdl . ' (' . $filename . ') not in cache (2)');
			}

			$this->releaseMutex($filename);
			return (!(is_null($s)) ? unserialize($s) : NULL);
		}


		$this->debug('Unable to obtain mutex for ' . $filename . ' in get');
	}

	/**
	* obtains the local mutex
	*
	* @param string $filename The Filename of the Cache to lock
	* @param string $mode The open-mode ("r" or "w") or the file - affects lock-mode
	* @return boolean Lock successfully obtained ?!
	* @access private
	*/
	public function obtainMutex($filename, $mode)
	{
		if (isset($this->fplock[md5($filename)])) {
			$this->debug('Lock for ' . $filename . ' already exists');
			return false;
		}


		$this->fplock[md5($filename)] = fopen($filename . '.lock', 'w');

		if ($mode == 'r') {
			return flock($this->fplock[md5($filename)], LOCK_SH);
		}


		return flock($this->fplock[md5($filename)], LOCK_EX);
	}

	/**
	* adds a wsdl instance to the cache
	*
	* @param object wsdl $wsdl_instance The wsdl instance to add
	* @return boolean WSDL successfully cached
	* @access public
	*/
	public function put($wsdl_instance)
	{
		$filename = $this->createFilename($wsdl_instance->wsdl);
		$s = serialize($wsdl_instance);

		if ($this->obtainMutex($filename, 'w')) {
			$fp = fopen($filename, 'w');

			if (!($fp)) {
				$this->debug('Cannot write ' . $wsdl_instance->wsdl . ' (' . $filename . ') in cache');
				$this->releaseMutex($filename);
				return false;
			}


			fputs($fp, $s);
			fclose($fp);
			$this->debug('Put ' . $wsdl_instance->wsdl . ' (' . $filename . ') in cache');
			$this->releaseMutex($filename);
			return true;
		}


		$this->debug('Unable to obtain mutex for ' . $filename . ' in put');
		return false;
	}

	/**
	* releases the local mutex
	*
	* @param string $filename The Filename of the Cache to lock
	* @return boolean Lock successfully released
	* @access private
	*/
	public function releaseMutex($filename)
	{
		$ret = flock($this->fplock[md5($filename)], LOCK_UN);
		fclose($this->fplock[md5($filename)]);
		unset($this->fplock[md5($filename)]);

		if (!($ret)) {
			$this->debug('Not able to release lock for ' . $filename);
		}


		return $ret;
	}

	/**
	* removes a wsdl instance from the cache
	*
	* @param string $wsdl The URL of the wsdl instance
	* @return boolean Whether there was an instance to remove
	* @access public
	*/
	public function remove($wsdl)
	{
		$filename = $this->createFilename($wsdl);

		if (!(file_exists($filename))) {
			$this->debug($wsdl . ' (' . $filename . ') not in cache to be removed');
			return false;
		}


		$this->obtainMutex($filename, 'w');
		$ret = unlink($filename);
		$this->debug('Removed (' . $ret . ') ' . $wsdl . ' (' . $filename . ') from cache');
		$this->releaseMutex($filename);
		return $ret;
	}
}

class wsdlcache extends nusoap_wsdlcache
{
	/**
	 *	@var resource
	 *	@access private
	 */
	/**
	 *	@var integer
	 *	@access private
	 */
	/**
	 *	@var string
	 *	@access private
	 */
	/**
	 *	@var string
	 *	@access public
	 */

	public function nusoap_wsdlcache($cache_dir, $cache_lifetime)
	{

Notice: Undefined offset: -1 in dephp on line 1463

Notice: Undefined offset: -1 in dephp on line 1468

Notice: Undefined offset: -1 in dephp on line 1469

Notice: Undefined offset: -1 in dephp on line 1470
	}

	public function createFilename($wsdl)
	{

Notice: Undefined offset: -1 in dephp on line 1463

Notice: Undefined offset: -1 in dephp on line 1468

Notice: Undefined offset: -1 in dephp on line 1469

Notice: Undefined offset: -1 in dephp on line 1470
	}

	public function debug($string)
	{

Notice: Undefined offset: -1 in dephp on line 1463

Notice: Undefined offset: -1 in dephp on line 1468

Notice: Undefined offset: -1 in dephp on line 1469

Notice: Undefined offset: -1 in dephp on line 1470
	}

	public function get($wsdl)
	{

Notice: Undefined offset: -1 in dephp on line 1463

Notice: Undefined offset: -1 in dephp on line 1468

Notice: Undefined offset: -1 in dephp on line 1469

Notice: Undefined offset: -1 in dephp on line 1470
	}

	public function obtainMutex($filename, $mode)
	{

Notice: Undefined offset: -1 in dephp on line 1463

Notice: Undefined offset: -1 in dephp on line 1468

Notice: Undefined offset: -1 in dephp on line 1469

Notice: Undefined offset: -1 in dephp on line 1470
	}

	public function put($wsdl_instance)
	{

Notice: Undefined offset: -1 in dephp on line 1463

Notice: Undefined offset: -1 in dephp on line 1468

Notice: Undefined offset: -1 in dephp on line 1469

Notice: Undefined offset: -1 in dephp on line 1470
	}

	public function releaseMutex($filename)
	{

Notice: Undefined offset: -1 in dephp on line 1463

Notice: Undefined offset: -1 in dephp on line 1468

Notice: Undefined offset: -1 in dephp on line 1469

Notice: Undefined offset: -1 in dephp on line 1470
	}

	public function remove($wsdl)
	{

Notice: Undefined offset: -1 in dephp on line 1463

Notice: Undefined offset: -1 in dephp on line 1468

Notice: Undefined offset: -1 in dephp on line 1469

Notice: Undefined offset: -1 in dephp on line 1470
	}
}


?>