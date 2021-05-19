<?php
/**
*
* @package Ultimate phpBB SEO Friendly URL
* @version $$
* @copyright (c) 2017 www.phpBB-SEO.ir
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace phpbbseo\usu\core\traits;

/**
* seo trait
* www.phpBB-SEO.ir
* @package Ultimate phpBB SEO Friendly URL
*/
trait seo
{
	/**
	* Returns usable start param
	* -xx
	*/
	public function seo_start($start)
	{
		return ($start >= 1) ? $this->seo_delim['start'] . (int) $start : '';
	}

	/**
	* seo_url_encode($url)
	* custom urlencoding
	*/
	public function seo_url_encode($url)
	{
		// can be faster to return $url directly if you do not allow more chars than
		// [a-zA-Z0-9_\.-] in your usernames
		// return $url;
		// Here we hanlde the "&", "/", "+" and "#" case proper (http://www.php.net/urlencode => http://issues.apache.org/bugzilla/show_bug.cgi?id=34602)
		$find = ['&', '/', '#', '+'];
		$replace = ['%26', '%2F', '%23', '%2b'];

		return rawurlencode(str_replace($find, $replace, \utf8_normalize_nfc(htmlspecialchars_decode(str_replace('&amp;amp;', '%26', rawurldecode($url))))));
	}

	/**
	* Returns usable start param
	* pagexx.html
	* Only used in virtual folder mode
	*/
	public function seo_start_page($start, $suffix = '/')
	{
		return ($start >= 1) ? '/' . $this->seo_static['pagination'] . (int) $start . $this->seo_ext['pagination'] : $suffix;
	}

	/**
	* Returns the full REQUEST_URI
	*/
	public function seo_req_uri()
	{
		$this->seo_path['uri'] = $this->request->server('HTTP_X_REWRITE_URL'); // IIS  isapi_rewrite

		if (empty($this->seo_path['uri']))
		{
			// Apache mod_rewrite
			$this->seo_path['uri'] = $this->request->server('REQUEST_URI');
		}

		if (empty($this->seo_path['uri']))
		{
			$this->seo_path['uri'] = $this->request->server('SCRIPT_NAME') . (($qs = $this->request->server('QUERY_STRING')) != '' ? "?$qs" : '');
		}

		$this->seo_path['uri'] = str_replace('%26', '&', rawurldecode(ltrim($this->seo_path['uri'], '/')));

		// workaround for FF default iso encoding
		if (!$this->is_utf8($this->seo_path['uri']))
		{
			$this->seo_path['uri'] = \utf8_normalize_nfc(\utf8_recode($this->seo_path['uri'], 'iso-8859-1'));
		}

		$this->seo_path['uri'] = $this->seo_path['root_url'] . $this->seo_path['uri'];

		return $this->seo_path['uri'];
	}

	/**
	* Custom HTTP 301 redirections.
	* To kill duplicates
	*/
	public function seo_redirect($url, $code = 301, $replace = true)
	{
		$supported_headers = [
			301	=> 'Moved Permanently',
			302	=> 'Found',
			307	=> 'Temporary Redirect',
		];

		if (
			!isset($supported_headers[$code]) ||
			@headers_sent()
		)
		{
			return false;
		}

		garbage_collection();

		$url = str_replace('&amp;', '&', $url);

		// Behave as redirect() for checks to provide with the same level of protection
		// Make sure no linebreaks are there... to prevent http response splitting for PHP < 4.4.2
		if (strpos(urldecode($url), "\n") !== false || strpos(urldecode($url), "\r") !== false || strpos($url, ';') !== false)
		{
			send_status_line(400, 'Bad Request');

			trigger_error('INSECURE_REDIRECT', E_USER_ERROR);
		}

		// Now, also check the protocol and for a valid url the last time...
		$allowed_protocols = ['http', 'https'/*, 'ftp', 'ftps'*/];
		$url_parts = parse_url($url);

		if ($url_parts === false || empty($url_parts['scheme']) || !in_array($url_parts['scheme'], $allowed_protocols))
		{
			send_status_line(400, 'Bad Request');

			trigger_error('INSECURE_REDIRECT', E_USER_ERROR);
		}

		send_status_line($code, $supported_headers[$code]);
		/*
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Pragma: no-cache');
		header('Expires: -1');
		*/
		header('Location: ' . $url);

		exit_handler();
	}

	/**
	* check start var consistency
	* Returns our best guess for $start, eg the first valid page
	*/
	public function seo_chk_start($start = 0, $limit = 0)
	{
		$this->start = 0;

		if ($limit > 0)
		{
			$start = is_int($start / $limit) ? $start : intval($start / $limit) * $limit;
		}

		if ($start >= 1)
		{
			$this->start = $this->seo_delim['start'] . (int) $start;

			return (int) $start;
		}

		$this->start = '';

		return 0;
	}
}
