<?php

/**
 * @title            Session Class
 * @desc             Handler Session
 *
 * @author           Pierre-Henry Soria <ph7software@gmail.com>
 * @url              https://www.phpclasses.org/package/9644-PHP-Store-and-retrieve-data-in-cookies-or-PHP-sessions.html#usage
 * @copyright        (c) 2012-2016, Pierre-Henry Soria. All Rights Reserved.
 * @license          GNU General Public License 3 or later <http://www.gnu.org/licenses/gpl.html>
 * @package          phpnuke 8.4
 */

if(!defined('NUKE_FILE'))
{
	die ("You can't access this file directly...");
}

class pn_Sessions {

	protected $sessionID;
    private $iExpiration = 31536000; // 1 Year
    public $sPrefix = 'pnSession_';
    private $sPath = '/';
    private $sDomain;
    private $bIsSsl;
    public $session_started = false;
    private $sCookieName = 'PH84SESS'; // Default Cookie Name
	
    public function __construct()
    {
        session_name($this->getCookieName());

        /**
         * In localhost mode, security session_set_cookie_params causing problems in the sessions, so we disable this if we are in localhost mode.
         * Otherwise if we are in production mode, we activate this.
         */
        if (!$this->isLocalHost())
		{
            $iTime = (int) $this->getExpiration();
            session_set_cookie_params($iTime, $this->getPath(), $this->getDomain(), $this->getIsSsl(), true);
        }

        // Session initialization
        if ('' === session_id()) {
            session_start();
			$this->session_started = true;
        }
    }

    public function getCookieName()
    {
        return $this->sCookieName;
    }

    /**
     * @param string $sCookieName Cookie name (e.g., PHS7SESS).
     */
    public function setCookieName(string $sCookieName)
    {
        $this->sCookieName = $sCookieName;
    }
	
    /**
     * Check if the server is in local.
     *
     * @return bool TRUE if it is in local mode, FALSE if not.
     */
    public static function isLocalHost()
    {
        $sServerName = $_SERVER['SERVER_NAME'];
        $sHttpHost = $_SERVER['HTTP_HOST'];

        return ($sServerName === 'localhost' || $sServerName === '127.0.0.1' || $sHttpHost === 'localhost' || $sHttpHost === '127.0.0.1');
    }

    /**
     * Escape function, uses the native htmlspecialchars()/strip_tags() PHP functions.
     *
     * @param string $sValue
     * @param bool $bStrip Default: FALSE
     * @return string The escaped string.
     */
    public static function escape(string $sValue, $bStrip = false)
    {
        return ($bStrip) ? strip_tags($sValue) : htmlspecialchars($sValue, ENT_QUOTES, 'utf-8');
    }

    public function getExpiration()
    {
        return $this->iExpiration;
    }

    public function getPrefix()
    {
        return $this->sPrefix;
    }

    public function getPath()
    {
        return $this->sPath;
    }

    public function getDomain()
    {
        if (empty($this->sDomain)) {
            $sDomain = (($_SERVER['SERVER_PORT'] != '80') && ($_SERVER['SERVER_PORT'] != '443')) ?  $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] : $_SERVER['SERVER_NAME'];
			
            $this->sDomain = (filter_var($sDomain, FILTER_VALIDATE_IP)) ? $sDomain:'.' . str_replace('www.', '', $sDomain);
        }
        return $this->sDomain;
    }

    public function getIsSsl()
    {
        if (empty($this->bIsSsl)) {
            $this->bIsSsl = (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS'] == 'on'));
        }
        return $this->bIsSsl;
    }

    /**
     * @param int $iExpiration In seconds.
     */
    public function setExpiration(int $iExpiration)
    {
        $this->iExpiration = $iExpiration;
    }

    /**
     * @param string $sPrefix Prefix for the Cookie name.
     */
    public function setPrefix(string $sPrefix)
    {
        $this->sPrefix = $sPrefix;
    }

    /**
     * @param string $sPath Full Path (e.g., /path/to/app/).
     */
    public function setPath(string $sPath)
    {
        return $this->sPath = $sPath;
    }

    /**
     * @param string $sPath Domain name (e.g., mysite.com).
     */
    public function setDomain(string $sDomain)
    {
        $this->sDomain = $sDomain;
    }

    /**
     * @param bool $bIsSsl
     */
    public function setIsSsl(bool $bIsSsl)
    {
        $this->bIsSsl = $bIsSsl;
    }
	
    /**
     * Returns the current session status
     *
     * @return bool
     */
	protected function is_session_started()
	{
		if ( php_sapi_name() !== 'cli' ) {
			if ( version_compare(phpversion(), '5.4.0', '>=') )
			{
				return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
			}
			else
			{
				return (session_id() === '' || !$this->session_started) ? FALSE : TRUE;
			}
		}
		return FALSE;
	}

    /**
     * Set a PHP Session.
     *
     * @param mixed (array | string) $mName Name of the session.
     * @param string $sValue Value of the session, Optional if the session data is in a array.
     * @return void
     */
    public function set($mName, $sValue = null)
    {
		$this->reopen();
		
        if (is_array($mName)) {
            foreach ($mName as $sName => $sVal) {
                $this->set($sName, $sVal); // Recursive method
            }
        } else {
            $_SESSION[$this->getPrefix() . $mName] = $sValue;
        }
		$this->close();
    }

    /**
     * Get Session.
     *
     * @param string $sName Name of the session.
     * @param boolean $bEscape Default TRUE
     * @return string If the session exists, returns the session with function escape() (htmlspecialchars) if escape is enabled. Empty string value if the session does not exist.
     */
    public function get($sName, $bEscape = true, $expire = 0)
    {
		if (!is_string($sName)) {
			trigger_error('No, you fool!');
			return;
		}
		
		if($expire != 0 && $expire < _NOWTIME)
			return false;
		
        $sSessionName = $this->getPrefix() . $sName;
        return ($this->exists($sName) ? ($bEscape ? $this->escape($_SESSION[$sSessionName]) : $_SESSION[$sSessionName]) : null);
    }

    /**
     * Returns a boolean informing if the session exists or not.
     *
     * @param mixed (array | string) $mName Name of the session.
     * @return boolean
     */
    public function exists($mName)
    {
        $bExists = false; // Default value

        if (is_array($mName)) {
            foreach ($mName as $sName) {
                if (!$bExists = $this->exists($sName)) break; // Recursive method
            }
        } else {
			$sSessionName = $this->getPrefix() . $mName;
            $bExists = (isset($_SESSION[$sSessionName])) ? true : false;
        }

        return $bExists;
    }

    /**
     * Delete the session(s) key if the session exists.
     *
     * @param mixed (array | string) $mName Name of the session to delete.
     * @return void
     */
    public function remove($mName)
    {
		$this->reopen();
		if (is_array($mName)) {
			foreach ($mName as $sName) {
				$this->remove($sName); // Recursive method
			}
		} else {
			$sSessionName = $this->getPrefix() . $mName;

			// We put the session in a table so if the session is in the form of multi-dimensional array, it is clear how much is destroyed
			$_SESSION[$sSessionName] = array();
			unset($_SESSION[$sSessionName]);
		}
		$this->close();
    }

    /**
     * Session regenerate ID.
     *
     * @return void
     */
    public function regenerateId()
    {
        session_regenerate_id(true);
    }

	public function uagent_no_version()
	{
		$uagent = $_SERVER['HTTP_USER_AGENT'];
		$regx = '/\/[a-zA-Z0-9.]+/';
		$newString = preg_replace($regx,'',$uagent);
		return $newString;
	}

    /**
     * Destroy all PHP's sessions.
     */
    public function destroy()
    {
		$this->reopen();
        if (!empty($_SESSION)) {
            $_SESSION = array();
            session_unset();
			session_destroy();
        }
		$this->close();
    }

    protected function reopen()
    {
		if ($this->is_session_started() === FALSE)
		{
			ini_set('session.use_strict_mode', true);
			ini_set('session.use_only_cookies', false);
			ini_set('session.use_cookies', false);
			//ini_set('session.use_trans_sid', false); //May be necessary in some situations
			ini_set('session.cache_limiter', null);
			session_start(); //Reopen the (previously closed) session for writing.
			$this->session_started = true;
		}
    }
	
    protected function close()
    {
        session_write_close();
		$this->session_started = false;
    }

    public function __destruct()
    {
        // $this->close();
    }

    private function __clone() {}
	
}

?>