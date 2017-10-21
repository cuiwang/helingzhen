<?php
$GLOBALS['_transient']['static']['nusoap_base']['globalDebugLevel'] = 9;

if (!(extension_loaded('soap'))) {
	class soapclient extends nusoap_client
	{	}

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