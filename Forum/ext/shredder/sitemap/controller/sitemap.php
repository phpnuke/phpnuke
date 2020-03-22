<?php

/**
*
* @package phpBB3 SEO Sitemap
* @copyright (c) 2014 www.phpbb-work.ru
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace shredder\sitemap\controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class sitemap
{
	/**
	* Constructor
	* NOTE: The parameters of this method must match in order and type with
	* the dependencies defined in the services.yml file for this service.
	*

	/** @var \shredder\sitemap\core */
	protected $core;

	/** @var \shredder\sitemap\appender */
	protected $appender;

	/** @var \phpbb\cache\service */
	protected $cache;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\user */
	private $user;

	public function __construct(\shredder\sitemap\appender $appender, \shredder\sitemap\core $core, \phpbb\cache\service $cache, \phpbb\config\config $config, \phpbb\user $user)
	{
		$this->core = $core;
		$this->cache = $cache;
		$this->config = $config;
		$this->user = $user;
		$this->appender = $appender;
	}

	/**
	* Controller for route /sitemap/
	*
	* @param string		$name
	* @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	*/
	public function display_sitemap()
	{
		$driver = $this->cache->get_driver();

		if (($f_xml = $driver->get("_sitemap_seo_file")) === false)
		{
			$f_xml = $this->core->generate_sitemap();

			if (false == $f_xml)
			{
				throw new NotFoundHttpException($this->user->lang['SEOMAP_NO_DATA']);
			}

			$this->config->set('sitemap_seo_url_count', $this->appender->getUrlCount());

			if ($this->config['sitemap_seo_cache'])
			{
				$driver->put("_sitemap_seo_file", $f_xml, 3600*$this->config['sitemap_seo_cache']);
			}
		}

		$response = new Response($f_xml);

		$response->headers->set('Content-Type', 'application/xml');

		return $response;
	}

	public function display_sitemap_seqno_file($seqno)
	{
		$f_xml = $this->core->sitemap_file_get_contents($seqno);

		if (false == $f_xml)
		{
			throw new NotFoundHttpException(sprintf($this->user->lang['SEOMAP_NO_FILE'], generate_board_url() . '/store/shredder/' . $seqno . '.xml'));
		}

		$response = new Response($f_xml);

		$response->headers->set('Content-Type', 'application/xml');

		return $response;
	}
}
