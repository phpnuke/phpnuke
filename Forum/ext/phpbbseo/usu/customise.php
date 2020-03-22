<?php
/**
*
* @package Ultimate phpBB SEO Friendly URL
* @version $$
* @copyright (c) 2017 www.phpbb-seo.org
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/
namespace phpbbseo\usu;

/**
* customize Class
* www.phpBB-SEO.org
* @package Ultimate phpBB SEO Friendly URL
*/
class customise
{
	/** @var \phpbbseo\usu\core */
	private $core;

	/** @var \phpbb\config\config */
	private $config;

	/**
	* Constructor
	*
	* @param	\phpbbseo\usu\core		$core
	* @param	\phpbb\config\config	$config				Config object
	*
	*/
	public function __construct(\phpbbseo\usu\core $core, \phpbb\config\config $config)
	{
		$this->core = $core;
		$this->config = $config;
	}

	/**
	* inject()
	*/
	public function inject()
	{
		// ===> Custom url replacements <===
		// Here you can set up custom replacements to be used in title injection.
		// Example : array('find' => 'replace')
		//	$this->core->url_replace = array(
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
			$this->core->seo_static['user'] = 'membre';
			$this->core->seo_static['group'] = 'groupe';
			$this->core->seo_static['global_announce'] = 'annonces';
			$this->core->seo_static['leaders'] = 'equipe';
			$this->core->seo_static['atopic'] = 'sujets-actifs';
			$this->core->seo_static['utopic'] = 'sans-reponses';
			$this->core->seo_static['npost'] = 'nouveaux-messages';
			$this->core->seo_static['urpost'] = 'non-lu';
			$this->core->seo_static['file_index'] = 'ressources';
		}
		// <== Special for lazy French, others may delete this part
	}
}
