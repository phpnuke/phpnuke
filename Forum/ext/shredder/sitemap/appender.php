<?php

/**
*
* @package phpBB3 SEO Sitemap
* @copyright (c) 2014 www.phpbb-work.ru
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace shredder\sitemap;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class appender
{
	/**
	* Constructor
	* NOTE: The parameters of this method must match in order and type with
	* the dependencies defined in the services.yml file for this service.
	*

	/** @var \phpbb\config\config */
	private $config;

	/** @var \phpbb\user */
	private $user;

	/** @var string */
	private $phpbb_root_path;
	private $php_ext;

	private $fp;
	private $index;
	private $path;
	private $path_noslash;
	private $dir;
	private $urls;

	public function __construct(\phpbb\config\config $config, \phpbb\user $user, $root_path, $php_ext)
	{
		$this->config = $config;
		$this->user = $user;

		$this->root_path = $root_path;
		$this->php_ext = $php_ext;
		$this->fp = null;
		$this->index = 1;
		$this->path_noslash = generate_board_url();
		$this->path = $this->path_noslash . '/';
		$this->dir = 'store/shredder/';
		$this->urls = 0;
		$this->limit = $this->config['sitemap_seo_url_limit'];
	}

	public function getUrlCount()
	{
		return $this->urls + ($this->index - 1 - ($this->index > 1)) * $this->limit;
	}

	/**
	* Add URL record to sitemap
	*/
	public function append($loc, $time = false, $freq = false, $prior = false)
	{
		if ($this->urls == $this->limit)
		{
			$this->flush_content();
			$this->urls = 0;
			$this->index ++;
		}

		if (null == $this->fp)
		{
			if (!($this->fp = @fopen($this->root_path . $this->dir . $this->index . '.xml', 'w')))
			{
				throw new HttpException(503, sprintf($this->user->lang['SEOMAP_CANT_WRITE'], $this->path . $this->dir));
			}

			$f_beg = '<?xml version="1.0" encoding="UTF-8"?>'."\r\n";
			$f_beg .= '<?xml-stylesheet type="text/xsl" href="' . $this->path . 'ext/shredder/sitemap/styles/sitemap.xsl"?>'."\r\n";
			$f_beg .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\r\n";

			if (!(@fwrite($this->fp, $f_beg)))
			{
				throw new HttpException(503, sprintf($this->user->lang['SEOMAP_CANT_WRITE'], $this->path . $this->dir));
			}
		}

		if ($time && $freq && $prior)
		{
			$time = gmdate('Y-m-d\TH:i:s+00:00', (int) $time);

			if (!(@fprintf($this->fp ,"<url>\r\n<loc>%s%s</loc>\r\n<lastmod>%s</lastmod>\r\n<changefreq>%s</changefreq>\r\n<priority>%s</priority>\r\n</url>\r\n", (''), $loc, $time, $freq, $prior)))
			{
				throw new HttpException(503, sprintf($this->user->lang['SEOMAP_CANT_WRITE'], $this->path . $this->dir));
			}
		}
		else
		{
			$f_xml = '<url>'."\r\n";

			$f_xml .= '<loc>'.$loc.'</loc>'."\r\n";

			if ($time)
			{
				$time = gmdate('Y-m-d\TH:i:s+00:00', (int) $time);
				$f_xml .= '<lastmod>'.$time.'</lastmod>'."\r\n";
			}

			if ($freq)
			{
				$f_xml .= '<changefreq>'.$freq.'</changefreq>'."\r\n";
			}

			if ($prior)
			{
				$f_xml .= '<priority>'.$prior.'</priority>'."\r\n";
			}

			$f_xml .= '</url>'."\r\n";

			if (!(@fwrite($this->fp, $f_xml)))
			{
				throw new HttpException(503, sprintf($this->user->lang['SEOMAP_CANT_WRITE'], $this->path . $this->dir));
			}
		}

		++$this->urls;
	}

	public function flush_content()
	{
		if (null != $this->fp)
		{
			$f_end = '</urlset>';
			
			if (!(@fwrite($this->fp, $f_end)))
			{
				throw new HttpException(503, sprintf($this->user->lang['SEOMAP_CANT_WRITE'], $this->path . $this->dir));
			}

			@fclose($this->fp);
			$this->fp = null;
		}
	}

	public function get_content()
	{
		$this->flush_content();

		if ($this->index > 1)
		{
			$f_xml = '<?xml version="1.0" encoding="UTF-8"?>'."\r\n";
			$f_xml .= '<?xml-stylesheet type="text/xsl" href="' . $this->path . 'ext/shredder/sitemap/styles/sitemap.xsl"?>'."\r\n";
			$f_xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\r\n";

			$this->index += ($this->urls > 0);

			for ($i = 1; $i < $this->index; $i++)
			{
				$f_xml .= '<sitemap>'."\r\n";
				$f_xml .= '<loc>'. $this->path . 'sitemap-' . $i . '.xml</loc>'."\r\n";
				$f_xml .= '<lastmod>' . gmdate('Y-m-d\TH:i:s+00:00', time()) . '</lastmod>'."\r\n";
				$f_xml .= '</sitemap>'."\r\n";
			}

			$f_xml .= '</sitemapindex>';
		}
		else
		{
			$f_xml = @file_get_contents($this->root_path . $this->dir . $this->index . '.xml');
		}

		return $f_xml;
	}
}
