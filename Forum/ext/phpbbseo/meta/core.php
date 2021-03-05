<?php
/**
*
* @package Dynamic Meta Tags phpBB SEO
* @version $$
* @copyright (c) 2017 www.phpbb-seo.org
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace phpbbseo\meta;

use phpbb\config\config;
use phpbb\template\template;
use phpbb\user;
use phpbb\symfony_request;

/**
* core Class
* www.phpBB-SEO.org
* @package Dynamic Meta Tags phpBB SEO
*/
class core
{
	/**
	* Some config :
	*	=> keywordlimit : number of keywords (max) in the keyword tag,
	*	=> wordlimit : number of words (max) in the desc tag,
	*	=> wordminlen : only words with more than wordminlen letters will be used, default is 2,
	*	=> bbcodestrip : | separated list of bbcode to fully delete, tag + content, default is 'img|url|flash',
	*	=> ellipsis : ellipsis to use if clipping,
	*	=> topic_sql : Do a SQL to build topic meta keywords or just use the meta desc tag,
	*	=> check_ignore : Check the search_ignore_words.php list.
	*		Please note :
	*			This will require some more work for the server.
	*			And this is mostly useless if you have re-enabled the search_ignore_words.php list
	*			filtering in includes/search/fulltest_native.php (and of course use fulltest_native index).
	*	=> bypass_common : Bypass common words in viewtopic.php.
	*		Set to true by default because the most interesting keywords are as well among the most common.
	*		This of course provides with even better results when fulltest_native is used
	*		and search_ignore_words.php list was re-enabled.
	*	=> get_filter : Disallow tag based on GET var used : coma separated list, will through a disallow meta tag.
	*	=> file_filter : Disallow tag based on the physical script file name : coma separated list of file names
	* Some default values are set bellow in the seo_meta_tags() method,
	**/
	public $config = array(
		'keywordlimit'		=> 15,
		'wordlimit'			=> 25,
		'wordminlen'		=> 2,
		'bbcodestrip'		=> 'img|url|flash|code',
		'ellipsis'			=> ' ...',
		'topic_sql'			=> true,
		'check_ignore'		=> false,
		'bypass_common'		=> true,
		// Consider adding ", 'p' => 1" if your forum is no indexed yet or if no post urls are to be redirected
		// to add a noindex tag on post urls
		'get_filter'		=> 'style,hilit,sid',
		// noindex based on physical script file name
		'file_filter'		=> 'ucp',
		// open graph (fb)
		'og'				=> 1,
		'fb_app_id'			=> '',
	);
	/* Limit in chars for the last post link text. */
	public $char_limit = 25;


	public $tags = array(
		'meta'	=> array(
			// here you can comment a tag line to deactivate it
			'fields' => array(
				// local name		=> global alias
				'content-language'	=> 'lang',
				'title'				=> 'title',
				'description'		=> 'description',
				'keywords'			=> 'keywords',
				'category'			=> 'category',
				'robots'			=> 'robots',
				'distribution'		=> 'distribution',
				'resource-type'		=> 'resource-type',
				'copyright'			=> 'copyright',
			),
			'mask' => '<meta name="%1$s" content="%2$s" />',
		),
		'og'	=> array(
			// here you can comment a tag line to deactivate it
			'fields'	=> array(
				// local name		=> global alias
				'og:title'			=> 'title',
				'og:site_name'		=> 'sitename',
				'og:url'			=> 'canonical',
				'og:description'	=> 'description',
				'og:locale'			=> 'lang',
				'og:image'			=> 'image',
				'fb:app_id'			=> 'fb:app_id',
			),
			'mask'		=> '<meta property="%1$s" content="%2$s" />',
			'filters'	=> array(
				'description'		=> 'meta_filter_og',
			),
		),
	);

	public $meta = array(
		'title'			=> '',
		'description'	=> '',
		'keywords'		=> '',
		'lang'			=> '',
		'category'		=> '',
		'robots'		=> '',
		'distribution'	=> '',
		'resource-type'	=> '',
		'copyright'		=> '',
	);

	public $meta_def = array();

	/* @var \phpbb\user */
	protected $user;

	/* @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\symfony_request */
	protected $symfony_request;

	/**
	* Current $phpbb_root_path
	* @var string
	*/
	protected $phpbb_root_path;

	/**
	* Current $php_ext
	* @var string
	*/
	protected $php_ext;

	protected $filters = array(
		'description'	=> 'meta_filter_txt',
		'keywords'		=> 'make_keywords'
	);

	/**
	* Constructor
	*
	* @param \phpbb\config\config			$config				Config object
	* @param \phpbb\template\template		$template			Template object
	* @param \phpbb\user					$user				User object
	* @param \phpbb\symfony_request			$symfony_request
	* @param string							$phpbb_root_path	Path to the phpBB root
	* @param string							$php_ext			PHP file extension
	*/
	public function __construct(config $config, template $template, user $user, symfony_request $symfony_request, $phpbb_root_path, $php_ext)
	{
		$this->user = $user;
		$this->template = $template;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
		$this->symfony_request = $symfony_request;

		// default values, leave empty to only output the corresponding tag if filled
		$this->meta_def['robots'] = 'index,follow';

		// global values, if these are empty, the corresponding meta will not show up
		$this->meta['category'] = 'general';
		$this->meta['distribution'] = 'global';
		$this->meta['resource-type'] = 'document';

		$this->config['sitename'] = $config['sitename'];
		$this->config['site_desc'] = $config['site_desc'];
		// other settings that may be set through acp in case the mod is not used standalone
		if (isset($config['seo_meta_desc_limit']))
		{
			// defaults
			$this->meta_def['title'] = $config['seo_meta_title'];
			$this->meta_def['description'] = $config['seo_meta_desc'];
			$this->meta_def['keywords'] = $config['seo_meta_keywords'];
			$this->meta_def['robots'] = $config['seo_meta_robots'];

			// global
			$this->meta['lang'] = $config['seo_meta_lang'];
			$this->meta['copyright'] = $config['seo_meta_copy'];

			// settings
			$this->config['wordlimit'] = (int) $config['seo_meta_desc_limit'];
			$this->config['keywordlimit'] = (int) $config['seo_meta_keywords_limit'];
			$this->config['wordminlen'] = (int) $config['seo_meta_min_len'];
			$this->config['check_ignore'] = (int) $config['seo_meta_check_ignore'];
			$this->config['file_filter'] = preg_replace('`[\s]+`', '', trim($config['seo_meta_file_filter'], ', '));
			$this->config['get_filter'] = preg_replace('`[\s]+`', '', trim($config['seo_meta_get_filter'], ', '));
			$this->config['bbcodestrip'] = str_replace(',', '|', preg_replace('`[\s]+`', '', trim($config['seo_meta_bbcode_filter'], ', ')));

			// open graph (fb)
			$this->config['og'] = isset($config['seo_meta_og']) ? max(0, (int) $config['seo_meta_og']) : $this->config['og'];
			$this->config['fb_app_id'] = isset($config['seo_fb_app_id']) ? $config['seo_fb_app_id'] : $this->config['fb_app_id'];
		}
		else
		{
			// default values, leave empty to only output the corresponding tag if filled
			$this->meta_def['title'] = $config['sitename'];
			$this->meta_def['description'] = $config['site_desc'];
			$this->meta_def['keywords'] = $config['site_desc'];

			// global values, if these are empty, the corresponding meta will not show up
			$this->meta['lang'] = $config['default_lang'];
			$this->meta['copyright'] = $config['sitename'];
		}

		// open graph (fb)
		$this->meta_def['fb:app_id'] = $this->config['fb_app_id'];

		$this->config['get_filter'] = !empty($this->config['get_filter']) ? @explode(',', $this->config['get_filter']) : array();
		$this->config['topic_sql'] = $config['search_type'] == 'fulltext_native' ? $this->config['topic_sql'] : false;

		// set up tags
		foreach ($this->tags as $type => $setup)
		{
			if ($type !== 'meta' && empty($this->config[$type]))
			{
				unset($this->tags[$type]);
				continue;
			}

			$tpl = array();
			foreach ($setup['fields'] as $field => $alias)
			{
				$tpl[$alias] = sprintf($setup['mask'], $field, '%1$s');
			}
			$this->tags[$type]['tpl'] = $tpl;
		}
	}

	/**
	* add meta tag
	* $content : if empty, the called tag will show up
	* do not call to fall back to default
	*/
	public function collect($type, $content = '', $combine = false)
	{
		$content = $this->soft_escape((string) $content);

		if ($combine)
		{
			$this->meta[$type] = (isset($this->meta[$type]) ? $this->meta[$type] . ' ' : '') . $content;
		}
		else
		{
			$this->meta[$type] = $content;
		}
	}

	/**
	* assign / return meta tag code
	*/
	public function build_meta($page_title = '', $return = false)
	{
		// If meta robots was not manually set
		if (empty($this->meta['robots']))
		{
			// Full request URI (e.g. phpBB/app.php/foo/bar)
			$request_uri = $this->symfony_request->getRequestUri();

			// Deny indexing for any url ending with htm(l) or / and with a qs (?)
			if (preg_match('`(\.html?|/)\?[^\?]*$`i', $request_uri))
			{
				$this->meta['robots'] = 'noindex,follow';
			}
			else
			{
				// lets still add some more specific ones
				$this->config['get_filter'] = array_merge($this->config['get_filter'], array('st','sk','sd','ch'));
			}

			// Do we allow indexing based on physical script file name
			if (empty($this->meta['robots']))
			{
				if (!empty($this->user->page['page_name']) && strpos($this->config['file_filter'], str_replace(".$this->php_ext", '', $this->user->page['page_name'])) !== false)
				{
					$this->meta['robots'] = 'noindex,follow';
				}
			}

			// Do we allow indexing based on get variable
			if (empty($this->meta['robots']))
			{
				foreach ($this->config['get_filter'] as $get)
				{
					if (isset($_GET[$get]))
					{
						$this->meta['robots'] = 'noindex,follow';
						break;
					}
				}
			}

			// fallback to default if necessary
			if (empty($this->meta['robots']))
			{
				$this->meta['robots'] = $this->meta_def['robots'];
			}
		}

		if (!empty($this->config['seo_meta_noarchive']))
		{
			$forum_id = isset($_GET['f']) ? max(0, request_var('f', 0)) : 0;

			if ($forum_id)
			{
				$forum_ids = @explode(',', preg_replace('`[\s]+`', '', trim($this->config['seo_meta_noarchive'], ', ')));

				if (in_array($forum_id, $forum_ids))
				{
					$this->meta['robots'] .= (!empty($this->meta['robots']) ? ',' : '') . 'noarchive';
				}
			}
		}

		// deal with titles, assign the tag if a default is set
		if (empty($this->meta['title']) && !empty($this->meta_def['title']))
		{
			$this->meta['title'] = $page_title;
		}

		$meta_code = '';

		foreach ($this->tags as $type => $setup)
		{

			foreach ($setup['fields'] as $original => $alias)
			{
				$is_set = false;
				$value = '';

				if (isset($this->meta[$original]))
				{
					$is_set = true;
					$value = $this->meta[$original];
				}
				else if ($type !== 'meta')
				{
					// core->collect('og:locale', $content)
					// core->collect('og:lang', $content)
					// core->collect('lang', $content)
					if (isset($this->meta["$type:$original"]))
					{
						$is_set = true;
						$value = $this->meta["$type:$original"];
					}
					else if (isset($this->meta["$type:$alias"]))
					{
						$is_set = true;
						$value = $this->meta["$type:$alias"];
					}
				}

				if (!$is_set)
				{
					if (isset($this->meta[$alias]))
					{
						$is_set = true;
						$value = $this->meta[$alias];
					}
					else if (isset($this->config[$alias]))
					{
						$is_set = true;
						$value = $this->config[$alias];
					}
				}

				// do like this so we can deactivate one particular tag on a given page,
				// by just setting the meta to an empty string
				if (!$is_set && !empty($this->meta_def[$alias]))
				{
					$value = isset($this->filters[$alias]) ? $this->{$this->filters[$alias]}($this->meta_def[$alias]) : $this->meta_def[$alias];
				}

				if (trim($value))
				{
					$filter_method = isset($setup['filters'][$alias]) ? $setup['filters'][$alias] : (isset($this->filters[$alias]) ? $this->filters[$alias] : false);
					if ($filter_method)
					{
						$value = $this->$filter_method($value);
					}
					$meta_code .= sprintf($setup['tpl'][$alias], utf8_htmlspecialchars($value)) . "\n";
				}
			}
			$meta_code .= "\n";
		}

		if (!$return)
		{
			$this->template->assign_var('SEO_META_TAGS', $meta_code);
		}
		else
		{
			return $meta_code;
		}
	}

	/**
	* Returns a coma separated keyword list
	*/
	public function make_keywords($text)
	{
		// we add ’ to the num filter because it does not seems to always be caught by punct
		// and it is widely used in languages files
		static $filter = array('`<[^>]*>(.*<[^>]*>)?`Usi', '`[[:punct:]]+`', '`[0-9’]+`',  '`[\s]{2,}`');

		$keywords = '';
		$num = 0;

		$text = utf8_strtolower(trim(preg_replace($filter, ' ', $text)));

		if (!$text)
		{
			return '';
		}

		$text = explode(' ', trim($text));
		if ($this->config['check_ignore'])
		{
			// add stop words to $user to allow reuse
			if (empty($this->user->stop_words))
			{
				$words = array();

				if (file_exists("{$this->user->lang_path}{$this->user->lang_name}/search_ignore_words.$this->php_ext"))
				{
					// include the file containing ignore words
					include("{$this->user->lang_path}{$this->user->lang_name}/search_ignore_words.$this->php_ext");
				}

				$this->user->stop_words = & $words;
			}

			$text = array_diff($text, $this->user->stop_words);
		}

		if (empty($text))
		{
			return '';
		}

		// We take the most used words first
		$text = array_count_values($text);
		arsort($text);

		foreach ($text as $word => $count)
		{
			if ( utf8_strlen($word) > $this->config['wordminlen'] )
			{
				$keywords .= ', ' . $word;
				$num++;
				if ( $num >= $this->config['keywordlimit'] )
				{
					break;
				}
			}
		}

		return trim($keywords, ', ');
	}

	/**
	* Same as meta_filter_txt but longer
	*/
	public function meta_filter_og($text)
	{
		return $this->meta_filter_txt($text, floor($this->config['wordlimit'] * 2));
	}

	/**
	* Filter php/html tags and white spaces and string with limit in words
	*/
	public function meta_filter_txt($text, $wordlimit = 0)
	{
		static $RegEx = array();

		$wordlimit = max((int) $wordlimit, $this->config['wordlimit']);

		if (empty($RegEx))
		{
			$RegEx = array(
				'`<[^>]*>(.*<[^>]*>)?`Usi', // HTML code
			);

			if (!empty($this->config['bbcodestrip']))
			{
				$RegEx[] = '`\[(' . $this->config['bbcodestrip'] . ')[^\[\]]*\].*\[/\1[^\[\]]*\]`Usi'; // bbcode to strip
			}

			$RegEx[] = '`\[\/?[a-z0-9\*\+\-]+(?:=(?:&quot;.*&quot;|[^\]]*))?(?::[a-z])?(\:[0-9a-z]{5,})\]`'; // Strip all bbcode tags

			$RegEx[] = "`[\n]{2,}`"; // Empty lines

			$RegEx[] = '`[\s]{2,}`'; // Multiple ws left

		}

		return $this->word_limit(trim(preg_replace($RegEx, ' ', $text)), $wordlimit);
	}

	/**
	* Cut the text according to the number of words.
	* Borrowed from www.php.net http://www.php.net/preg_replace
	*/
	public function word_limit($string, $wordlimit = 0)
	{
		$wordlimit = max((int) $wordlimit, $this->config['wordlimit']);
		return count($words = preg_split('/\s+/', ltrim($string), $wordlimit + 1)) > $wordlimit ? rtrim(utf8_substr($string, 0, utf8_strlen($string) - utf8_strlen(end($words)))) . $this->config['ellipsis'] : $string;
	}

	/**
	* same as htmlspecialchars but without "&" double encoding
	*/
	public static function soft_escape($string)
    {
    	$text = $string;
    	$text = censor_text($text);
	    strip_bbcode($text);
    	$text = str_replace(array("&quot;", "/", "\n", "\t", "\r"), ' ', $text);
     	$text = preg_replace(array("|http(.*)jpg|isU", "@(http(s)?://)?(([a-z0-9.-]+)?[a-z0-9-]+(!?\.[a-z]{2,4}))@"), ' ', $text);
    	$text=preg_replace('/\b(https?|ftp|file):/i', '', $text);
    	$text = trim(preg_replace("/[^A-ZА-ЯЁ.,-–?]+/ui", " ", $text));
    	return $text;
    }
}
