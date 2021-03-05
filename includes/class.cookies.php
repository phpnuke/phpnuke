<?php

if(!defined('NUKE_FILE'))
{
	die ("You can't access this file directly...");
}

/**
* Description of CookiesManager
*
* @author g4llic4
*/
class pn_Cookies {

	// ------------ CONSTANTS ------------ //

	const ONE_DAY = 86400;
	const SEVEN_DAYS = 604800;
	const THIRTY_DAYS = 2592000;
	const SIX_MONTHS = 15811200;
	const ONE_YEAR = 31536000;
	const LIFE8_TIME = 1893456000; // 2030-01-01 00:00:00

	const DEFAULT_PATH = '/';
	
	public $cookie_prefix_set = true;
	
	private $cookie_prefix = 'fs3d54rw5e_';
	
	public function __construct()
	{
		return true;
	}

	// ------------ METHODS ------------ //

	/**
	* Return true name with its prefix.
	* 
	* @param string $name
	* @return $name
	*/
	private function prefix_name ( &$name )
	{
		global $nuke_configs;
		if($this->cookie_prefix_set)
			$name = (isset($nuke_configs['sessions_prefix']) && $nuke_configs['sessions_prefix'] != '') ? $nuke_configs['sessions_prefix'].$name:$this->cookie_prefix.$name;
	}
	
	/**
	* Return true whetehr the paramater passed as an argument exist (as a cookie).
	* 
	* @param string $name
	* @return boolean
	*/
	public function exists ( $name, $name_prefixed=false )
	{
		if(!$name_prefixed)
			$this->prefix_name ( $name );

		return isset ( $_COOKIE[$name] );
	}

	/**
	* Return true whether the cookie does not exist or is empty for this name.
	* 
	* @param string $name
	* @return boolean
	*/
	public function isEmpty ( $name, $name_prefixed=false )
	{
		if(!$name_prefixed)
			$this->prefix_name ( $name );
			
		return empty ( $_COOKIE[$name] );
	}

	/**
	* Return the value of the given cookie. Return the default value if the
	* cookie does not exist.
	* 
	* @param string $name
	* @param string $defaultValue
	* @return mixed
	*/
	public function get ( $name, $defaultValue = '' )
	{
		$this->prefix_name ( $name );
		
		if($this->exists ( $name, true ))
			return $_COOKIE[$name];
				
		return $defaultValue;
	}
	
    /**
     * A better alternative (RFC 2109 compatible) to the php setcookie() function
     *
     * @param string Name of the cookie
     * @param string Value of the cookie
     * @param int Lifetime of the cookie
     * @param string Path where the cookie can be used
     * @param string Domain which can read the cookie
     * @param bool Secure mode?
     * @param bool Only allow HTTP usage?
     * @return bool True or false whether the method has successfully run
     */
	public function set ($name, $value='', $expires = self :: THIRTY_DAYS, $path='', $domain='', $secure=false, $HTTPOnly=false)
	{
		global $nuke_configs;

		$this->prefix_name ( $name );

		$secure = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $secure) ? 1 : 0;
		
		$ob = ini_get('output_buffering');

		// Abort the method if headers have already been sent, except when output buffering has been enabled
		if ( headers_sent() && (bool) $ob === false || strtolower($ob) == 'off' )
			return false; 

		if ( !empty($domain) )
		{
			// Fix the domain to accept domains with and without 'www.'.
			if ( strtolower( substr($domain, 0, 4) ) == 'www.' ) $domain = substr($domain, 4);
			// Add the dot prefix to ensure compatibility with subdomains
			if ( substr($domain, 0, 1) != '.' ) $domain = '.'.$domain;

			// Remove port information.
			$port = strpos($domain, ':');

			if ( $port !== false ) $domain = substr($domain, 0, $port);
		} 
		
		// Prevent "headers already sent" error with utf8 support (BOM)
        //if ( utf8_support ) header('Content-Type: text/html; charset=utf-8');

		$cookie_path = ($path == '') ? ((isset($nuke_configs['sitecookies']) && $nuke_configs['sitecookies'] != '') ? $nuke_configs['sitecookies']:self::DEFAULT_PATH):$path;
			
		if($value === false)
			$expires = _NOWTIME-86500;
		elseif ( is_numeric ( $expires ) )
			$expires = ($expires > 0) ? _NOWTIME + $expires : 0;
		else
			$expires = strtotime ( $expires );
			
		$expires = gmdate('D, d-M-Y H:i:s \\G\\M\\T', $expires);
		
        header('Set-Cookie: '.rawurlencode($name).'='.rawurlencode($value)
                                    .(empty($domain) ? '' : '; Domain='.$domain)
                                    .(empty($expires) ? '' : '; expires='.$expires)
                                    .(empty($cookie_path) ? '' : '; Path='.$cookie_path)
                                    .(!$secure ? '' : '; Secure')
                                    .(!$HTTPOnly ? '' : '; HttpOnly'), false);
        return true;
	}

	public function delete ( $name )
	{
		$cookieIsSet = false;
		if ( ! headers_sent ( ) )
		{
			$cookieIsSet = $this->set( $name, false, -1 );
		}

		return $cookieIsSet;
	}
}
?>