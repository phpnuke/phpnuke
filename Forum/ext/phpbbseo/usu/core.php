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

use phpbbseo\usu\customise;
use phpbbseo\usu\rewriter;

/**
* core Class
* www.phpBB-SEO.org
* @package Ultimate phpBB SEO Friendly URL
*/
class core
{
	/* @var \phpbb\config\config */
	private $config;

	/* @var \phpbb\request\request */
	private $request;

	/* @var \phpbb\user */
	private $user;

	/** @var \phpbb\auth\auth */
	private $auth;

	/* @var \phpbbseo\usu\customise */
	private $customise;

	/* @var \phpbbseo\usu\rewriter */
	private $rewriter;

	/**
	* Current $phpbb_root_path
	* @var string
	*/
	private $phpbb_root_path;

	/**
	* Current $php_ext
	* @var string
	*/
	private $php_ext;

	/**
	* mod rewrite type
	* 	1 : simple
	* 	2 : mixed
	* 	3 : advanced
	*/
	public $modrtype = 2; // We set it to mixed as a default value

	/**
	* paths
	*/
	public $seo_path = array();

	/**
	* uri cache
	*/
	public $seo_url = array(
		'forum'		=>  array(),
		'topic'		=>  array(),
		'user'		=> array(),
		'username'	=> array(),
		'group'		=> array(),
		'file'		=> array(),
	);

	/**
	* GET filters
	*/
	public $get_filter = array(
		'forum'		=> array('st' => 0, 'sk' => 't', 'sd' => 'd'),
		'topic'		=> array('st' => 0, 'sk' => 't', 'sd' => 'a', 'hilit' => ''),
		'search'	=> array('st' => 0, 'sk' => 't', 'sd' => 'd', 'ch' => ''),
	);

	/**
	* file filters
	*/
	private $stop_files = array(
		'posting'	=> 1,
		'faq'		=> 1,
		'ucp'		=> 1,
		'mcp'		=> 1,
		'style'		=> 1,
		'cron'		=> 1,
		'report'	=> 1,
	);

	/**
	* dir filters
	*/
	public $stop_dirs = array();

	/**
	* qs filters
	*/
	public $stop_vars = array('view=', 'mark=', 'watch=', 'hash=');

	/**
	* seo delimiters
	*/
	public $seo_delim = array(
		'forum'	=> '-f',
		'topic'	=> '-t',
		'user'	=> '-u',
		'group'	=> '-g',
		'start'	=> '-',
		'sr'	=> '-',
		'file'	=> '/',
	);

	/**
	* seo suffixes
	*/
	public $seo_ext = array(
		'forum'			=> '.html',
		'topic'			=> '.html',
		'post'			=> '.html',
		'user'			=> '.html',
		'group'			=> '.html',
		'index'			=> '',
		'global_announce'	=> '/',
		'leaders'		=> '.html',
		'atopic'		=> '.html',
		'utopic'		=> '.html',
		'npost'			=> '.html',
		'urpost'		=> '.html',
		'pagination'		=> '.html',
		'gz_ext'		=> '',
	);

	/**
	* seo static
	*/
	public $seo_static = array(
		'forum'			=> 'forum',
		'topic'			=> 'topic',
		'post'			=> 'post',
		'user'			=> 'member',
		'group'			=> 'group',
		'index'			=> '',
		'global_announce'	=> 'announces',
		'leaders'		=> 'the-team',
		'atopic'		=> 'active-topics',
		'utopic'		=> 'unanswered',
		'npost'			=> 'newposts',
		'urpost'		=> 'unreadposts',
		'pagination'		=> 'page',
		'gz_ext'		=> '.gz',
		'file_index'		=> 'resources',
		'thumb'			=> 'thumb',
	);

	/**
	* hbase
	*/
	public $file_hbase = array();

	/**
	* current page url
	*/
	public $page_url = '';

	/**
	* options with default values
	*/
	public $seo_opt = array(
		'url_rewrite'			=> false,
		'modrtype'			=> 2,
		'sql_rewrite'			=> false,
		'profile_inj'			=> false,
		'profile_vfolder'		=> false,
		'profile_noids'			=> false,
		'rewrite_usermsg'		=> false,

		// disable attachment rewriting
		// https://github.com/phpBBSEO/usu/issues/31
		// 'rewrite_files'			=> false,

		'rem_sid'			=> false,
		'rem_hilit'			=> true,
		'rem_small_words'		=> false,
		'virtual_folder'		=> false,
		'virtual_root'			=> false,
		'cache_layer'			=> true,
		'rem_ids'			=> false,
		'redirect_404_forum'		=> false,
		'redirect_404_topic'		=> false,
	);

	/**
	* runtime variables
	*/
	public $rewrite_method = array();
	public $paginate_method = array();
	public $seo_cache = array();
	public $cache_config = array();
	public $RegEx = array();
	public $sftpl = array();
	public $url_replace = array();
	public $ssl = array('requested' => false, 'forced' => false);
	public $forum_redirect = array();

	/**
	* rewriting private variable
	* per url values
	*/
	public $get_vars = array();
	public $path = '';
	public $start = '';
	public $filename = '';
	public $file = '';
	public $url_in = '';
	public $url = '';

	/**
	* Constructor
	*
	* @param	\phpbb\config\config		$config				Config object
	* @param	\phpbb\request\request		$request			Request object
	* @param	\phpbb\user			$user				User object
	* @param	\phpbb\auth\auth		$auth				Auth object
	* @param	string				$phpbb_root_path		Path to the phpBB root
	* @param	string				$php_ext			PHP file extension
	*
	*/
	public function __construct(\phpbb\config\config $config, \phpbb\request\request $request, \phpbb\user $user, \phpbb\auth\auth $auth, $phpbb_root_path, $php_ext)
	{
		$this->config = $config;
		$this->request = $request;
		$this->user = $user;
		$this->auth = $auth;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;

		$this->customise = new customise($this, $this->config);
		$this->rewriter = new rewriter($this, $this->user, $this->phpbb_root_path);

		// fix for an interesting bug with parse_str http://bugs.php.net/bug.php?id=48697
		// and apparently, the bug is still here in php5.3
		@ini_set("mbstring.internal_encoding", 'UTF-8');

		// reset the rewrite_method for $phpbb_root_path
		$this->rewrite_method[$this->phpbb_root_path] = array();

		if (!empty($this->seo_opt['rewrite_files']))
		{
			// phpBB files must be treated a bit differently
			$this->seo_static['file'] = array(
				ATTACHMENT_CATEGORY_NONE		=> 'file',
				ATTACHMENT_CATEGORY_IMAGE		=> 'image',
				ATTACHMENT_CATEGORY_WM			=> 'wm',
				ATTACHMENT_CATEGORY_RM			=> 'rm',
				ATTACHMENT_CATEGORY_THUMB		=> 'image',
				ATTACHMENT_CATEGORY_FLASH		=> 'flash',
				ATTACHMENT_CATEGORY_QUICKTIME		=> 'qt',
			);
		}

		// Options that may be bypassed by the cached settings.
		$this->cache_config['dynamic_options'] = array_keys($this->seo_opt); // Do not change

		// copyright notice, do not change
		$this->cache_config['dynamic_options']['copyrights'] = $this->seo_opt['copyrights'] = array('img' => true, 'txt' => '', 'title' => '');

		// Caching config
		define('PHPBB_SEO_USU_ROOT_DIR', rtrim(dirname(realpath(__FILE__)), '\\/') . '/');
		$this->seo_opt['cache_folder'] = PHPBB_SEO_USU_ROOT_DIR . 'cache/'; // where the cache file is stored

		$this->seo_opt['topic_type'] = array(); // do not change
		$this->cache_config['cache_enable'] = true; // do not change
		$this->cache_config['rem_ids'] = $this->seo_opt['rem_ids']; // do not change, set up above
		$this->cache_config['file'] = $this->seo_opt['cache_folder'] . 'config.runtime.' . $this->php_ext;
		$this->cache_config['cached'] = false; // do not change
		$this->cache_config['forum_urls'] = array(); // do not change
		$this->cache_config['forum'] = array(); // do not change
		// $this->cache_config['topic'] = array(); // do not change
		$this->cache_config['settings'] = array(); // do not change

		// --> Zero Dupe
		$this->seo_opt['zero_dupe'] = array(
			'on'			=> false, // Activate or not the redirections : true / false
			'strict'		=> false, // strict compare, == VS strpos() : true / false
			'post_redir'	=> 'guest', // Redirect post urls if not valid ? : guest / all / post / off
		);
		$this->cache_config['dynamic_options']['zero_dupe'] = $this->seo_opt['zero_dupe']; // Do not change
		$this->seo_opt['zero_dupe']['do_redir'] = false; // do not change
		$this->seo_opt['zero_dupe']['go_redir'] = true; // do not change
		$this->seo_opt['zero_dupe']['do_redir_post'] = false; // do not change
		$this->seo_opt['zero_dupe']['start'] = 0; // do not change
		$this->seo_opt['zero_dupe']['redir_def'] = array(); // do not change
		// <-- Zero Dupe

		// --> DOMAIN SETTING <-- //
		// SSL, beware with cookie secure, it won't force ssl here,
		// so you will need to switch to ssl for your user to use cookie based session (no sid)
		// could be done by using an https link to login form (or within the redirect after login)
		$this->ssl['requested'] = (bool) ($this->request->server('HTTPS') || ($this->request->server('SERVER_PORT') === 443));
		$this->ssl['forced'] = (bool) (($this->config['server_protocol'] === 'https://'));
		$this->ssl['use'] = (bool) ($this->ssl['requested'] || $this->ssl['forced']);

		// Server Settings, rely on DB
		$server_protocol = $this->ssl['use'] ? 'https://' : 'http://';
		$server_name = trim($this->config['server_name'], '/ ');
		$server_port = max(0, (int) $this->config['server_port']);
		$default_port = $this->ssl['use'] ? 443 : 80;

		$server_port = $server_port && ($server_port != $default_port) ? ':' . $server_port : '';
		$script_path = trim($this->config['script_path'], './ ');
		$script_path = (empty($script_path)) ? '' : $script_path . '/';

		$this->seo_path['root_url'] = strtolower($server_protocol . $server_name . $server_port . '/');
		$this->seo_path['phpbb_urlR'] = $this->seo_path['phpbb_url'] = $this->seo_path['root_url'] . $script_path;
		$this->seo_path['phpbb_script'] = $script_path;
		$this->seo_path['phpbb_files'] = $this->seo_path['phpbb_url'] . 'download/';
		$this->seo_path['canonical'] = '';

		// magic quotes, do it like this in case phpbbseo class is not started in common.php
		if (!defined('STRIP'))
		{
			if (version_compare(PHP_VERSION, '6.0.0-dev', '<'))
			{
				if (get_magic_quotes_gpc())
				{
					define('SEO_STRIP', true);
				}
			}
		}
		else if (STRIP)
		{
			define('SEO_STRIP', true);
		}

		// File setting
		$this->seo_req_uri();
		$this->seo_opt['seo_base_href'] = $this->seo_opt['req_file'] = $this->seo_opt['req_self'] = '';

		if ($script_name = $this->request->server('PHP_SELF'))
		{
			// From session.php
			// Replace backslashes and doubled slashes (could happen on some proxy setups)
			$this->seo_opt['req_self'] = str_replace(array('\\', '//'), '/', $script_name);

			// basenamed page name (for example: index)
			$this->seo_opt['req_file'] = urlencode(htmlspecialchars(str_replace('.' . $this->php_ext, '', basename($this->seo_opt['req_self']))));
		}

		// Let's load config and forum urls, mods adding options in the cache file must do it in customise::init
		$this->read_config();

		// Load settings from customise.php
		$this->customise->inject();

		// Let's make sure that settings are consistent
		$this->check_config();

		// see if we have some custom replacement
		if (!empty($this->url_replace))
		{
			$this->url_replace = array(
				'find'		=> array_keys($this->url_replace),
				'replace'	=> array_values($this->url_replace)
			);
		}

		// Array of the filenames that require the use of a base href tag.
		$this->file_hbase = array_merge(
			array(
				'viewtopic'		=> $this->seo_path['phpbb_url'],
				'viewforum'		=> $this->seo_path['phpbb_url'],
				'memberlist'		=> $this->seo_path['phpbb_url'],
				'search'		=> $this->seo_path['phpbb_url'],
			),
			$this->file_hbase
		);

		// Stop dirs
		$this->stop_dirs = array_merge(
			array(
				$this->phpbb_root_path . 'adm/'	=> false
			),
			$this->stop_dirs
		);

		// Rewrite functions array : array('path' => array('file_name' => 'function_name'));
		// Warning, this way of doing things is path aware, this implies path to be properly sent to append_sid()
		// Allow to add options without slowing down the URL rewriting process
		$this->rewrite_method[$this->phpbb_root_path] = array_merge(
			array(
				'viewtopic'		=> 'viewtopic',
				'viewforum'		=> 'viewforum',
				'index'			=> 'index',
				'memberlist'		=> 'memberlist',
				'search'		=> $this->seo_opt['rewrite_usermsg'] ? 'search' : '',
			),
			$this->rewrite_method[$this->phpbb_root_path]
		);

		if (!empty($this->seo_opt['rewrite_files']))
		{
			$this->seo_path['phpbb_filesR'] = $this->seo_path['phpbb_urlR'] . $this->seo_static['file_index'] . $this->seo_delim['file'];
			$this->rewrite_method[$this->phpbb_root_path . 'download/']['file'] = 'phpbb_files';
		}

		if (
			$this->seo_opt['virtual_folder'] ||
			$this->seo_opt['profile_noids'] ||
			$this->seo_opt['profile_vfolder']
		)
		{
			// This hax is required because phpBB Path helper is tricked
			// into thinking our virtual dirs are real
			$this->helper_trick();
		}

		// allow empty ext
		$pag_mtds = array();

		foreach ($this->seo_ext as $key => $ext)
		{
			$pag_mtds[$key] = trim($ext, '/') ? 'rewrite_pagination' : 'rewrite_pagination_page';
		}

		$this->paginate_method = array_merge(
			$pag_mtds,
			$this->paginate_method
		);

		$this->RegEx = array_merge(
			array(
				'topic'	=> array(
					'check'		=> '`^' . ($this->seo_opt['virtual_folder'] ? '%1$s/' : '') . '(' . $this->seo_static['topic'] . '|[a-z0-9_-]+' . $this->seo_delim['topic'] . ')$`i',
					'match'		=> '`^((([a-z0-9_-]+)(' . $this->seo_delim['forum'] . '([0-9]+))?/)?(' . $this->seo_static['topic'] . '(?!=' . $this->seo_delim['topic'] . ')|.+(?=' . $this->seo_delim['topic'] . '))(' . $this->seo_delim['topic'] . ')?)([0-9]+)$`i',
					'parent'	=> 2,
					'parent_id'	=> 5,
					'title'		=> 6,
					'id'		=> 8,
					'url'		=> 1,
				),
				'forum'	=> array(
					'check'		=> $this->modrtype >= 2 ? '`^[a-z0-9_-]+(' . $this->seo_delim['forum'] . '[0-9]+)?$`i' : '`^' . $this->seo_static['forum'] . '[0-9]+$`i',
					'match'		=> '`^((' . $this->seo_static['forum'] . '|.+)(' . $this->seo_delim['forum'] . '([0-9]+))?)$`i',
					'title'		=> '\2',
					'id'		=> '\4',
				),
			),
			$this->RegEx
		);

		// preg_replace() patterns for format_url()
		// One could want to add |th|horn after |slash, but I'm not sure that Þ should be replaced with t and Ð with e
		$this->RegEx['url_find'] = array('`&([a-z]+)(acute|grave|circ|cedil|tilde|uml|lig|ring|caron|slash);`i', '`&(amp;)?[^;]+;`i', '`[^a-z0-9]`i'); // Do not remove : deaccentuation, html/xml entities & non a-z chars
		$this->RegEx['url_replace'] = array('\1', '-', '-');

		if ($this->seo_opt['rem_small_words'])
		{
			$this->RegEx['url_find'][] = '`(^|-)[a-z0-9]{1,2}(?=-|$)`i';
			$this->RegEx['url_replace'][] = '-';
		}

		$this->RegEx['url_find'][] ='`[-]+`'; // Do not remove : multi hyphen reduction
		$this->RegEx['url_replace'][] = '-';

		// $1 parent : string/
		// $2 title / url : topic-title / forum-url-fxx
		// $3 id
		$this->sftpl = array_replace(
			array(
				'topic'			=> ($this->seo_opt['virtual_folder'] ? '%1$s/' : '') . '%2$s' . $this->seo_delim['topic'] . '%3$s',
				'topic_smpl'		=> ($this->seo_opt['virtual_folder'] ? '%1$s/' : '') . $this->seo_static['topic'] . '%3$s',
				'forum'			=> $this->modrtype >= 2 ? '%1$s' : $this->seo_static['forum'] . '%2$s',
				'group'			=> $this->seo_opt['profile_inj'] ? '%2$s' . $this->seo_delim['group'] . '%3$s' : $this->seo_static['group'] . '%3$s',
			),
			$this->sftpl
		);

		if ($this->seo_opt['url_rewrite'] && !defined('ADMIN_START') && isset($this->file_hbase[$this->seo_opt['req_file']]))
		{
			$this->seo_opt['seo_base_href'] = '<base href="' . $this->file_hbase[$this->seo_opt['req_file']] . '"/>';
		}

		return;
	}

	/**
	* will make sure that configured options are consistent
	*/
	public function check_config()
	{
		$this->modrtype = max(0, (int) $this->modrtype);

		// For profiles and user messages pages, if we do not inject, we do not get rid of ids
		$this->seo_opt['profile_noids'] = $this->seo_opt['profile_inj'] ? $this->seo_opt['profile_noids'] : false;

		// If profile noids ... or user messages virtual folder
		if ($this->seo_opt['profile_noids'] || $this->seo_opt['profile_vfolder'])
		{
			$this->seo_ext['user'] = trim($this->seo_ext['user'], '/') ? '/' : $this->seo_ext['user'];
		}

		$this->seo_delim['sr'] = trim($this->seo_ext['user'], '/') ? $this->seo_delim['sr'] : $this->seo_ext['user'];

		// If we use virtual folder ...
		if ($this->seo_opt['virtual_folder'])
		{
			$this->seo_ext['forum'] = $this->seo_ext['global_announce'] = trim($this->seo_ext['forum'], '/') ? '/' : $this->seo_ext['forum'];
		}

		// If the forum cache is not activated
		if (!$this->seo_opt['cache_layer'])
		{
			$this->seo_opt['rem_ids'] = false;
		}

		// virtual root option
		if ($this->seo_opt['virtual_root'] && $this->seo_path['phpbb_script'])
		{
			// virtual root is available and activated
			$this->seo_path['phpbb_urlR'] = $this->seo_path['root_url'];
			$this->file_hbase['index'] = $this->seo_path['phpbb_url'];
			$this->seo_static['index'] = empty($this->seo_static['index']) ? 'forum' : $this->seo_static['index'];
		}
		else
		{
			// virtual root is not used or usable
			$this->seo_opt['virtual_root'] = false;
		}

		$this->seo_ext['index'] = empty($this->seo_static['index']) ? '' : (empty($this->seo_ext['index']) ? '.html' : $this->seo_ext['index']);

		// In case url rewriting is deactivated
		if (!$this->seo_opt['url_rewrite'] || $this->modrtype == 0)
		{
			$this->seo_opt['sql_rewrite'] = false;
			$this->seo_opt['zero_dupe']['on'] = false;
		}
	}

	/**
	* Of course, there should ba a better way to do that
	* @TODO investigate if extending helper service is feasible
	*/
	public function helper_trick()
	{

		static $been_here;
		if (!empty($been_here))
		{
			return;
		}

		foreach ($this->rewrite_method as $path => $method_list)
		{

			foreach ($method_list as $index => $method)
			{

				if (is_array($method) || empty($method))
				{
					continue;
				}

				$this->rewrite_method[$this->phpbb_root_path . '../'][$index] = $method;
				$this->rewrite_method[$this->phpbb_root_path . '../../'][$index] = $method;
			}
		}

		$been_here = true;
	}

	// --> URL rewriting functions <--
	/**
	* format_url($url, $type = 'topic')
	* Prepare Titles for URL injection
	*/
	public function format_url($url, $type = 'topic')
	{
		$url = preg_replace('`\[.*\]`U', '', $url);

		if (isset($this->url_replace['find']))
		{
			$url = str_replace($this->url_replace['find'], $this->url_replace['replace'], $url);
		}

		$url = htmlentities($url, ENT_COMPAT, 'UTF-8');
		$url = preg_replace($this->RegEx['url_find'], $this->RegEx['url_replace'], $url);
		$url = strtolower(trim($url, '-'));

		return empty($url) ? $type : $url;
	}

	/**
	* set_url($url, $id = 0, $type = 'forum', $parent = '')
	* Prepare url first part and checks cache
	*/
	public function set_url($url, $id = 0, $type = 'forum', $parent = '')
	{
		if (empty($this->seo_url[$type][$id]))
		{
			return ($this->seo_url[$type][$id] = !empty($this->cache_config[$type . '_urls'][$id]) ? $this->cache_config[$type . '_urls'][$id] : sprintf($this->sftpl[$type], $this->format_url($url, $this->seo_static[$type]) . $this->seo_delim[$type] . $id, $id));
		}

		return $this->seo_url[$type][$id];
	}

	/**
	* set_parent_urls(array & $forum_data)
	* set/check urls of current forum's parent(s)
	*/
	public function set_parent_urls(&$forum_data)
	{
		if (!empty($forum_data['forum_parents']))
		{
			$forum_parents = @unserialize($forum_data['forum_parents']);
			if (!empty($forum_parents))
			{
				foreach ($forum_parents as $fid => $data)
				{
					$this->set_url($data[0], $fid, 'forum');
				}
			}
		}
	}

	/**
	* prepare_url($type, $title, $id, $parent = '', $smpl = false)
	* Prepare url first part
	*/
	public function prepare_url($type, $title, $id, $parent = '', $smpl = false)
	{
		return empty($this->seo_url[$type][$id]) ? ($this->seo_url[$type][$id] = sprintf($this->sftpl[$type . ($smpl ? '_smpl' : '')], $parent, !$smpl ? $this->format_url($title, $this->seo_static[$type]) : '', $id)) : $this->seo_url[$type][$id];
	}

	/**
	* set_title($type, $title, $id, $parent = '')
	* Set title for url injection
	*/
	public function set_title($type, $title, $id, $parent = '')
	{
		return empty($this->seo_url[$type][$id]) ? ($this->seo_url[$type][$id] = ($parent ? $parent . '/' : '') . $this->format_url($title, $this->seo_static[$type])) : $this->seo_url[$type][$id];
	}

	/**
	* get_url_info($type, $url, $info = 'title')
	* Get info from url (title, id, parent etc ...)
	*/
	public function get_url_info($type, $url, $info = 'title')
	{
		$url = trim($url, '/ ');

		if (preg_match($this->RegEx[$type]['match'], $url, $matches))
		{
			return !empty($matches[$this->RegEx[$type][$info]]) ? $matches[$this->RegEx[$type][$info]] : '';
		}

		return '';
	}

	/**
	* check_url($type, $url, $parent = '')
	* Validate a prepared url
	*/
	public function check_url($type, $url, $parent = '')
	{
		if (empty($url))
		{
			return false;
		}

		$parent = !empty($parent) ? (string) $parent : '[a-z0-9/_-]+';

		return !empty($this->RegEx[$type]['check']) ? preg_match(sprintf($this->RegEx[$type]['check'], $parent), $url) : false;
	}

	/**
	* prepare_topic_url(&$topic_data, $topic_forum_id)
	* Prepare topic url with SQL based URL rewriting
	*/
	public function prepare_topic_url(&$topic_data, $topic_forum_id = 0)
	{
		$id = max(0, (int) $topic_data['topic_id']);

		if (empty($this->seo_url['topic'][$id]))
		{
			if (!empty($topic_data['topic_url']))
			{
				return ($this->seo_url['topic'][$id] = $topic_data['topic_url'] . $id);
			}
			else
			{
				if ($this->modrtype > 2)
				{
					$topic_data['topic_title'] = censor_text($topic_data['topic_title']);
				}

				$topic_forum_id = $topic_forum_id ? $topic_forum_id : $topic_data['forum_id'];
				$parent_forum = $topic_data['topic_type'] == POST_GLOBAL ? $this->seo_static['global_announce'] : (!empty($this->seo_url['forum'][$topic_forum_id]) ? $this->seo_url['forum'][$topic_forum_id] : (!empty($topic_data['forum_name']) ? $this->set_url($topic_data['forum_name'], $topic_forum_id, 'forum') : ''));

				return ($this->seo_url['topic'][$id] = sprintf($this->sftpl['topic' . ($this->modrtype > 2 ? '' : '_smpl')], $parent_forum, $this->modrtype > 2 ? $this->format_url($topic_data['topic_title'], $this->seo_static['topic']) : '', $id));
			}
		}

		return $this->seo_url['topic'][$id];
	}

	/**
	* prepare_forum_url(&$forum_data, $parent = '')
	* Prepare url first part and checks cache
	*/
	public function prepare_forum_url(&$forum_data)
	{
		$id = max(0, (int) $forum_data['forum_id']);

		if (empty($this->seo_url['forum'][$id]))
		{
			$this->seo_url['forum'][$id] = sprintf($this->sftpl['forum'], $this->format_url($forum_data['forum_name'], $this->seo_static['forum']) . $this->seo_delim['forum'] . $id, $id);
		}

		return $this->seo_url['forum'][$id];
	}

	/**
	* prepare_iurl($data, $type, $parent = '')
	* Prepare url first part (not for forums) with SQL based URL rewriting
	*/
	public function prepare_iurl(&$data, $type, $parent = '')
	{
		$id = max(0, (int) $data[$type . '_id']);

		if (empty($this->seo_url[$type][$id]))
		{
			if (!empty($data[$type . '_url']))
			{
				return ($this->seo_url[$type][$id] = $data[$type . '_url'] . $id);
			}
			else
			{
				return ($this->seo_url[$type][$id] = sprintf($this->sftpl[$type . ($this->modrtype > 2 ? '' : '_smpl')], $parent, $this->modrtype > 2 ? $this->format_url($data[$type . '_title'], $this->seo_static[$type]) : '', $id));
			}
		}

		return $this->seo_url[$type][$id];
	}

	/**
	* drop_sid($url)
	* drop the sid's in url
	*/
	public function drop_sid($url)
	{
		return (strpos($url, 'sid=') !== false) ? trim(preg_replace(array('`&(amp;)?sid=[a-z0-9]+(&amp;|&)?`i', '`(\?)sid=[a-z0-9]+(&amp;|&)?`i'), array('\2', '\1'), $url), '?') : $url;
	}

	/**
	* set_user_url($username, $user_id = 0)
	* Prepare profile url
	*/
	public function set_user_url($username, $user_id = 0)
	{
		if (empty($this->seo_url['user'][$user_id]))
		{
			$username = strip_tags($username);

			$this->seo_url['username'][$username] = $user_id;

			if ($this->seo_opt['profile_inj'])
			{
				if ($this->seo_opt['profile_noids'])
				{
					$this->seo_url['user'][$user_id] = $this->seo_static['user'] . '/' . $this->seo_url_encode($username);
				}
				else
				{
					$this->seo_url['user'][$user_id] = $this->format_url($username,  $this->seo_delim['user']) . $this->seo_delim['user'] . $user_id;
				}
			}
			else
			{
				$this->seo_url['user'][$user_id] = $this->seo_static['user'] . $user_id;
			}
		}
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
		$find = array('&', '/', '#', '+');
		$replace = array('%26', '%2F', '%23', '%2b');

		return rawurlencode(str_replace($find, $replace, \utf8_normalize_nfc(htmlspecialchars_decode(str_replace('&amp;amp;', '%26', rawurldecode($url))))));
	}

	/**
	* url_rewrite($url, $params = false, $is_amp = true, $session_id = false)
	* builds and Rewrite URLs.
	* Allow adding of many more cases than just the
	* regular phpBB URL rewritting without slowing down the process.
	* Mimics append_sid with some shortcuts related to how url are rewritten
	*/
	public function url_rewrite($url, $params = false, $is_amp = true, $session_id = false, $is_route = false, $recache = false)
	{
		global $_SID, $_EXTRA_URL;

		if ($is_route)
		{
			return false;
		}

		$qs = $anchor = '';
		$amp_delim = ($is_amp) ? '&amp;' : '&';

		$this->get_vars = array();

		if (strpos($url, '#') !== false)
		{
			list($url, $anchor) = explode('#', $url, 2);

			$anchor = '#' . $anchor;
		}

		@list($this->path, $qs) = explode('?', $url, 2);

		if (is_array($params))
		{
			if (!empty($params['#']))
			{
				$anchor = '#' . $params['#'];

				unset($params['#']);
			}

			$qs .= ($qs ? $amp_delim : '') . $this->query_string($params, $amp_delim, '');
		}
		else if ($params)
		{
			if (strpos($params, '#') !== false)
			{
				list($params, $anchor) = explode('#', $params, 2);

				$anchor = '#' . $anchor;
			}

			$qs .= ($qs ? $amp_delim : '') . $params;
		}

		// Appending custom url parameter?
		if (!empty($_EXTRA_URL))
		{
			$qs .= ($qs ? $amp_delim : '') . implode($amp_delim, $_EXTRA_URL);
		}

		// Sid ?
		if ($session_id === false && !empty($_SID))
		{
			$qs .= ($qs ? $amp_delim : '') . "sid=$_SID";
		}
		else if ($session_id)
		{
			$qs .= ($qs ? $amp_delim : '') . "sid=$session_id";
		}

		// Build vanilla URL
		if (preg_match("`\.[a-z0-9]+$`i", $this->path))
		{
			$this->file = basename($this->path);
			$this->path = ltrim(str_replace($this->file, '', $this->path), '/');
		}
		else
		{
			$this->file = '';
			$this->path = ltrim($this->path, '/');
		}

		$this->url_in = $this->file . ($qs ? '?' . $qs : '');
		$url = $this->path . $this->url_in;

		if (!$recache && isset($this->seo_cache[$url]))
		{
			return $this->seo_cache[$url] . $anchor;
		}

		if (!$this->seo_opt['url_rewrite'] || defined('ADMIN_START'))
		{
			return ($this->seo_cache[$url] = $url) . $anchor;
		}

		if (isset($this->stop_dirs[$this->path]))
		{
			// add full url no matter what assuming concerned
			// scripts will always be phpBB ones
			$url = $this->seo_path['phpbb_url'] . preg_replace('`^.*?(' . trim($this->path, '/.') . '.*?)$`', '\1', $url);
			return ($this->seo_cache[$url] = $url) . $anchor;
		}

		$this->filename = trim(str_replace('.' . $this->php_ext, '', $this->file));

		if (isset($this->stop_files[$this->filename]))
		{
			// add full url no matter what assuming concerned
			// scripts will always be phpBB ones
			$url = $this->seo_path['phpbb_url'] . preg_replace('`^.*?(' . $this->filename . '\.' . $this->php_ext . '.*?)$`', '\1', $url);
			return ($this->seo_cache[$url] = $url) . $anchor;
		}

		parse_str(str_replace('&amp;', '&', $qs), $this->get_vars);

		// strip slashes if necessary
		if (defined('SEO_STRIP'))
		{
			$this->get_vars = array_map(array($this, 'stripslashes'), $this->get_vars);
		}

		if (empty($this->user->data['is_registered']))
		{
			if ($this->seo_opt['rem_sid'])
			{
				unset($this->get_vars['sid']);
			}

			if ($this->seo_opt['rem_hilit'])
			{
				unset($this->get_vars['hilit']);
			}
		}

		$this->url = $this->file;

		if (!empty($this->rewrite_method[$this->path][$this->filename]))
		{
			$rewrite_method_name = $this->rewrite_method[$this->path][$this->filename];

			$this->rewriter->$rewrite_method_name();

			return ($this->seo_cache[$url] = $this->path . $this->url . $this->query_string($this->get_vars, $amp_delim, '?')) . $anchor;
		}
		else
		{
			return ($this->seo_cache[$url] = $url) . $anchor;
		}
	}

	/**
	* Returns true if the user can edit urls
	* @access public
	*/
	public function url_can_edit($forum_id = 0)
	{
		if (empty($this->seo_opt['sql_rewrite']) || empty($this->user->data['is_registered']))
		{
			return false;
		}

		if ($this->auth->acl_get('a_'))
		{
			return true;
		}

		// un comment to grant url edit perm to moderators in at least a forums
		/*
		if ($this->auth->acl_getf_global('m_'))
		{
			return true;
		}
		*/

		$forum_id = max(0, (int) $forum_id);

		if ($forum_id && $this->auth->acl_get('m_', $forum_id))
		{
			return true;
		}

		return false;
	}

	/**
	* Will break if a $filter pattern is foundin $url.
	* Example $filter = array("view=", "mark=");
	*/
	public function filter_url($filter = array())
	{
		foreach ($filter as $patern)
		{
			if (strpos($this->url_in, $patern) !== false)
			{
				$this->get_vars = array();
				$this->url = $this->url_in;

				return false;
			}
		}

		return true;
	}

	/**
	* Will unset all default var stored in $filter array.
	* Example $filter = array('st' => 0, 'sk' => 't', 'sd' => 'a', 'hilit' => '');
	*/
	public function filter_get_var($filter = array())
	{
		if (!empty($this->get_vars))
		{
			foreach ($this->get_vars as $paramkey => $paramval)
			{
				if (isset($filter[$paramkey]))
				{
					if ($filter[$paramkey] == $this->get_vars[$paramkey] || !isset($this->get_vars[$paramkey]))
					{
						unset($this->get_vars[$paramkey]);
					}
				}
			}
		}

		return;
	}

	/**
	* Appends the GET vars in the query string
	* @access public
	*/
	public function query_string($get_vars = array(), $amp_delim = '&amp;', $url_delim = '?')
	{
		if (empty($get_vars))
		{
			return '';
		}

		$params = array();

		foreach ($get_vars as $key => $value)
		{
			if (is_array($value))
			{
				foreach ($value as $k => $v)
				{
					$params[] = $key . '[' . $k . ']=' . $v;
				}
			}
			else
			{
				// until https://tracker.phpbb.com/browse/PHPBB3-12852 is fixed
				// $params[] = $key . (!trim($value) ? '' : '=' . $value);
				$params[] = $key . '=' . $value;
			}
		}

		return $url_delim . implode($amp_delim , $params);
	}

	/**
	* rewrite pagination, simple
	* -xx.html
	*/
	public function rewrite_pagination($suffix)
	{
		$this->start = $this->seo_start(@$this->get_vars['start']) . $suffix;

		unset($this->get_vars['start']);
	}

	/**
	* rewrite pagination, virtual folder
	* /pagexx.html
	*/
	public function rewrite_pagination_page($suffix = '/')
	{
		$this->start = $this->seo_start_page(@$this->get_vars['start'], $suffix);

		unset($this->get_vars['start']);

		return $this->start;
	}

	/**
	* Returns usable start param
	* -xx
	*/
	public function seo_start($start)
	{
		return ($start >= 1) ? $this->seo_delim['start'] . (int) $start : '';
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

	// -> Cache functions
	/**
	* forum_id(&$forum_id, $forum_uri = '')
	* will tell the forum id from the uri or the forum_uri GET var by checking the cache.
	*/
	public function get_forum_id(&$forum_id, $forum_uri = '')
	{
		if (empty($forum_uri))
		{
			$forum_uri = $this->request->variable('forum_uri', '');

			if (!empty($this->request))
			{
				$this->request->overwrite('forum_uri', null, \phpbb\request\request_interface::REQUEST);
				$this->request->overwrite('forum_uri', null, \phpbb\request\request_interface::GET);
			}
			else
			{
				unset($_GET['forum_uri'], $_REQUEST['forum_uri']);
			}
		}

		if (empty($forum_uri) || $forum_uri == $this->seo_static['global_announce'])
		{
			return 0;
		}

		if ($id = @array_search($forum_uri, $this->cache_config['forum_urls']))
		{
			$forum_id = max(0, (int) $id);
		}
		else if ($id = $this->get_url_info('forum', $forum_uri, 'id'))
		{
			$forum_id = max(0, (int) $id);
		}
		else if (!empty($this->forum_redirect))
		{
			if (isset($this->forum_redirect[$forum_uri]))
			{
				$forum_id = max(0, (int) $this->forum_redirect[$forum_uri]);
			}
		}

		return $forum_id;
	}

	/**
	* read_config()
	*/
	public function read_config($from_bkp = false)
	{
		if (
			!$this->cache_config['cache_enable'] ||
			!file_exists($this->cache_config['file'])
		)
		{
			$this->cache_config['cached'] = false;

			return false;
		}

		include($this->cache_config['file']);

		if (!empty($settings))
		{
			$this->cache_config['settings'] = & $settings;
			$this->cache_config['forum_urls'] = & $forum_urls;
			$this->cache_config['cached'] = true;
			$this->seo_opt = array_replace_recursive($this->seo_opt, $settings);
			$this->modrtype = @isset($this->seo_opt['modrtype']) ? $this->seo_opt['modrtype'] : $this->modrtype;

			if ($this->modrtype > 1)
			{
				// bind cached URLs
				$this->seo_url['forum'] = & $this->cache_config['forum_urls'];
			}
		}
		else
		{
			if (!$from_bkp)
			{
				// Try the current backup
				@copy($file . '.current', $file);

				return $this->read_config(true);
			}

			$this->cache_config['cached'] = false;

			return false;
		}
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
		return is_array($value) ? array_map('\\phpbbseo\\core::stripslashes', $value) : stripslashes($value);
	}

	/**
	* Custom HTTP 301 redirections.
	* To kill duplicates
	*/
	public function seo_redirect($url, $code = 301, $replace = true)
	{
		$supported_headers = array(
			301	=> 'Moved Permanently',
			302	=> 'Found',
			307	=> 'Temporary Redirect',
		);

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
		$allowed_protocols = array('http', 'https'/*, 'ftp', 'ftps'*/);
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
	* Set the do_redir_post option right
	*/
	public function set_do_redir_post()
	{
		switch ($this->seo_opt['zero_dupe']['post_redir'])
		{
			case 'guest':
				if (empty($this->user->data['is_registered']))
				{
					$this->seo_opt['zero_dupe']['do_redir_post'] = true;
				}

				break;
			case 'all':
				$this->seo_opt['zero_dupe']['do_redir_post'] = true;

				break;
			case 'off': // Do not redirect
				$this->seo_opt['zero_dupe']['do_redir'] = false;
				$this->seo_opt['zero_dupe']['go_redir'] = false;
				$this->seo_opt['zero_dupe']['do_redir_post'] = false;

				break;
			default:
				$this->seo_opt['zero_dupe']['do_redir_post'] = false;

				break;
		}

		return $this->seo_opt['zero_dupe']['do_redir_post'];
	}

	/**
	* Redirects if the uri sent does not match (fully) the
	* attended url
	*/
	public function zero_dupe($url = '', $uri = '', $path = '')
	{
		global $_SID;

		if (!$this->seo_opt['zero_dupe']['on'] || empty($this->seo_opt['req_file']) || (!$this->seo_opt['rewrite_usermsg'] && $this->seo_opt['req_file'] == 'search'))
		{
			return false;
		}

		if ($this->request->is_set('explain') && (boolean) ($this->auth->acl_get('a_') && defined('DEBUG_CONTAINER')))
		{
			if ($this->request->variable('explain', 0) == 1)
			{
				return true;
			}
		}

		$path = empty($path) ? $this->phpbb_root_path : $path;
		$uri = !empty($uri) ? $uri : $this->seo_path['uri'];
		$reg = !empty($this->user->data['is_registered']) ? true : false;
		$url = empty($url) ? $this->expected_url($path) : str_replace('&amp;', '&', append_sid($url, false, true, 0));
		$url = $this->drop_sid($url);

		// Only add sid if user is registered and needs it to keep session
		if ($this->request->is_set('sid', \phpbb\request\request_interface::GET) && !empty($_SID) && ($reg || !$this->seo_opt['rem_sid']))
		{
			if ($this->request->variable('sid', '') == $this->user->session_id)
			{
				$url .=  (\utf8_strpos($url, '?') !== false ? '&' : '?') . 'sid=' . $this->user->session_id;
			}
		}

		$url = str_replace('%26', '&', urldecode($url));

		if ($this->seo_opt['zero_dupe']['do_redir'])
		{
			$this->seo_redirect($url);
		}
		else
		{
			$url_check = $url;

			// we remove url hash for comparison, but keep it for redirect
			if (strpos($url, '#') !== false)
			{
				list($url_check, $hash) = explode('#', $url, 2);
			}

			if ($this->seo_opt['zero_dupe']['strict'])
			{
				return $this->seo_opt['zero_dupe']['go_redir'] && (($uri != $url_check) ? $this->seo_redirect($url) : false);
			}
			else
			{
				return $this->seo_opt['zero_dupe']['go_redir'] && ((\utf8_strpos($uri, $url_check) === false) ? $this->seo_redirect($url) : false);
			}
		}
	}

	/**
	* expected_url($path = '')
	* build expected url
	*/
	public function expected_url($path = '')
	{
		$path = empty($path) ? $this->phpbb_root_path : $path;
		$params = array();

		foreach ($this->seo_opt['zero_dupe']['redir_def'] as $get => $def)
		{
			if (($this->request->is_set($get, \phpbb\request\request_interface::GET) && $def['keep']) || !empty($def['force']))
			{
				$params[$get] = $def['val'];

				if (!empty($def['hash']))
				{
					$params['#'] = $def['hash'];
				}
			}
		}

		$this->page_url = append_sid($path . $this->seo_opt['req_file'] . '.' . $this->php_ext, $params, true, 0);

		return $this->page_url;
	}

	/**
	* set_cond($bool, $type = 'bool_redir', $or = true)
	* Helps out grabbing boolean vars
	*/
	public function set_cond($bool, $type = 'do_redir', $or = true)
	{
		if ($or)
		{
			$this->seo_opt['zero_dupe'][$type] = (boolean) ($bool || $this->seo_opt['zero_dupe'][$type]);
		}
		else
		{
			$this->seo_opt['zero_dupe'][$type] = (boolean) ($bool && $this->seo_opt['zero_dupe'][$type]);
		}

		return;
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

	/**
	* get_canonical
	* Returns the canonical url if ever built
	* Beware with ssl :
	* 	Since we want zero duplicate, the canonical element will only use https when ssl is forced
	* 	(eg set as THE server protocol in config) and will use http in other cases.
	*/
	public function get_canonical()
	{
		return !empty($this->seo_path['canonical']) ? $this->sslify($this->seo_path['canonical'], $this->ssl['forced']) : '';
	}
}
