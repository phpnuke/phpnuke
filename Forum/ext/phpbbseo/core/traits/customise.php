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
* customize trait
* www.phpBB-SEO.ir
* @package Ultimate phpBB SEO Friendly URL
*/
trait customise
{
	/**
	* inject()
	*/
	public function inject()
	{
		// ===> Custom url replacements <===
		// Here you can set up custom replacements to be used in title injection.
		// Example : array('find' => 'replace')
		//	$this->url_replace = array(
		//		// Purely cosmetic replace
		//		'$' => 'dollar', '€' => 'euro',
		//		'\'s' => 's', // it's => its / mary's => marys ...
		//		// Language specific replace (German example)
		//		'ß' => 'ss',
		//		'Ä' => 'Ae', 'ä' => 'ae',
		//		'Ö' => 'Oe', 'ö' => 'oe',
		//		'Ü' => 'Ue', 'ü' => 'ue',
		//	);

		// ===> Custom values Delimiters, Static parts and Suffixes <===
		// ==> Delimiters <==
		// Can be overridden, requires .htaccess update <=
		// Example :
		//	$this->seo_delim['forum'] = '-mydelim'; // instead of the default "-f"

		// ==> Static parts <==
		// Can be overridden, requires .htaccess update.
		// Example :
		//	$this->seo_static['post'] = 'message'; // instead of the default "post"
		// !! phpBB files must be treated a bit differently !!
		// Example :
		//	$this->seo_static['file'][ATTACHMENT_CATEGORY_QUICKTIME] = 'quicktime'; // instead of the default "qt"
		//	$this->seo_static['file_index'] = 'my_files_virtual_dir'; // instead of the default "resources"

		// ==> Suffixes <==
		// Can be overridden, requires .htaccess update <=
		// Example :
		// 	$this->seo_ext['topic'] = '/'; // instead of the default ".html"

		// ==> Forum redirect <==
		// In case you are using forum id removing and need to edit some forum urls
		// that where already indexed, you can keep track of them ritgh here
		//
		// Example :
		//
		// $this->forum_redirect = array(
		// 	// 'old-url-without-id-nor-suffix' => forum_id,
		// 	'old-forum-url' => 23,
		// 	'another-one' => 32,
		// 	'another-version-of-the-same' => 32,
		// );
		//

		// ==> Special for lazy French, others may delete this part
		if (strpos($this->config['default_lang'], 'fr') !== false)
		{
			$this->seo_static['user'] = 'membre';
			$this->seo_static['group'] = 'groupe';
			$this->seo_static['global_announce'] = 'annonces';
			$this->seo_static['leaders'] = 'equipe';
			$this->seo_static['atopic'] = 'sujets-actifs';
			$this->seo_static['utopic'] = 'sans-reponses';
			$this->seo_static['npost'] = 'nouveaux-messages';
			$this->seo_static['urpost'] = 'non-lu';
			$this->seo_static['file_index'] = 'ressources';
		}
		// <== Special for lazy French, others may delete this part
	}

	/**
	* drop_sid($url)
	* drop the sid's in url
	*/
	public function drop_sid($url)
	{
		return (strpos($url, 'sid=') !== false) ? trim(preg_replace(['`&(amp;)?sid=[a-z0-9]+(&amp;|&)?`i', '`(\?)sid=[a-z0-9]+(&amp;|&)?`i'], ['\2', '\1'], $url), '?') : $url;
	}

	/**
	* sslify($url, $ssl = true)
	* properly set http protocol (eg http or https)
	*/
	public function sslify($url, $ssl = null)
	{
		$mask = '`^https?://`i';

		$replace = $ssl !== null ? ($ssl ? 'https://' : 'http://') : '//';

		return preg_replace($mask, $replace, trim($url));
	}

	/**
	* is_utf8($string)
	* Borrowed from php.net : http://www.php.net/mb_detect_encoding (detectUTF8)
	*/
	public function is_utf8($string)
	{
		// non-overlong 2-byte|excluding overlongs|straight 3-byte|excluding surrogates|planes 1-3|planes 4-15|plane 16
		return preg_match('%(?:[\xC2-\xDF][\x80-\xBF]|\xE0[\xA0-\xBF][\x80-\xBF]|[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}|\xED[\x80-\x9F][\x80-\xBF] |\xF0[\x90-\xBF][\x80-\xBF]{2}|[\xF1-\xF3][\x80-\xBF]{3}|\xF4[\x80-\x8F][\x80-\xBF]{2})+%xs', $string);
	}

	/**
	* stripslashes($value)
	* Borrowed from php.net : http://www.php.net/stripslashes
	*/
	public function stripslashes($value)
	{
		return is_array($value) ? array_map([&$this, 'stripslashes'], $value) : stripslashes($value);
	}
}
