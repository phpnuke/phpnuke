<?php
/**
*
* @package Ultimate phpBB SEO Friendly URL
* @version $$
* @copyright (c) 2017 www.phpBB-SEO.ir
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace phpbbseo\usu\core;

/**
* @ignore
*/
use phpbb\config\config;
use phpbb\request\request;
use phpbb\user;
use phpbb\auth\auth;
use phpbbseo\usu\core\traits\customise;
use phpbbseo\usu\core\traits\rewriter;
use phpbbseo\usu\core\traits\url;
use phpbbseo\usu\core\traits\seo;
use phpbbseo\usu\core\traits\get_set;

/**
* core Class
* www.phpBB-SEO.ir
* @package Ultimate phpBB SEO Friendly URL
*/
class core
{
	/**
	 * Import All Trait
	 */
	use customise, rewriter, url, seo, get_set;

	/** @var config */
	private $config;

	/** @var request */
	private $request;

	/** @var user */
	private $user;

	/** @var auth */
	private $auth;

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
	public $seo_path = [];

	/**
	* uri cache
	*/
	public $seo_url = [
		'forum'		=> [],
		'topic'		=> [],
		'user'		=> [],
		'username'	=> [],
		'group'		=> [],
		'file'		=> [],
	];

	/**
	* GET filters
	*/
	public $get_filter = [
		'forum'		=> ['st' => 0, 'sk' => 't', 'sd' => 'd'],
		'topic'		=> ['st' => 0, 'sk' => 't', 'sd' => 'a', 'hilit' => ''],
		'search'	=> ['st' => 0, 'sk' => 't', 'sd' => 'd', 'ch' => ''],
	];

	/**
	* file filters
	*/
	private $stop_files = [
		'posting'	=> 1,
		'faq'		=> 1,
		'ucp'		=> 1,
		'mcp'		=> 1,
		'style'		=> 1,
		'cron'		=> 1,
		'report'	=> 1,
	];

	/**
	* dir filters
	*/
	public $stop_dirs = [];

	/**
	* qs filters
	*/
	public $stop_vars = ['view=', 'mark=', 'watch=', 'hash='];

	/**
	* seo delimiters
	*/
	public $seo_delim = [
		'forum'	=> '-f',
		'topic'	=> '-t',
		'user'	=> '-u',
		'group'	=> '-g',
		'start'	=> '-',
		'sr'	=> '-',
		'file'	=> '/',
	];

	/**
	* seo suffixes
	*/
	public $seo_ext = [
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
	];

	/**
	* seo static
	*/
	public $seo_static = [
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
	];

	/**
	* hbase
	*/
	public $file_hbase = [];

	/**
	* current page url
	*/
	public $page_url = '';

	/**
	* options with default values
	*/
	public $seo_opt = [
		'url_rewrite'			=> false,
		'modrtype'				=> 2,
		'sql_rewrite'			=> false,
		'profile_inj'			=> false,
		'profile_vfolder'		=> false,
		'profile_noids'			=> false,
		'rewrite_usermsg'		=> false,

		// disable attachment rewriting
		// https://github.com/phpBBSEO/usu/issues/31
		// 'rewrite_files'		=> false,

		'rem_sid'				=> false,
		'rem_hilit'				=> true,
		'rem_small_words'		=> false,
		'virtual_folder'		=> false,
		'virtual_root'			=> false,
		'cache_layer'			=> true,
		'rem_ids'				=> false,
		'redirect_404_forum'	=> false,
		'redirect_404_topic'	=> false,
	];

	/**
	* runtime variables
	*/
	public $rewrite_method = [];
	public $paginate_method = [];
	public $seo_cache = [];
	public $cache_config = [];
	public $RegEx = [];
	public $sftpl = [];
	public $url_replace = [];
	public $ssl = ['requested' => false, 'forced' => false];
	public $forum_redirect = [];

	/**
	* rewriting private variable
	* per url values
	*/
	public $get_vars = [];
	public $path = '';
	public $start = '';
	public $filename = '';
	public $file = '';
	public $url_in = '';
	public $url = '';

	/**
	* Constructor
	*
	* @param	config		$config				Config object
	* @param	request		$request			Request object
	* @param	user		$user				User object
	* @param	auth		$auth				Auth object
	* @param	string		$phpbb_root_path	Path to the phpBB root
	* @param	string		$php_ext			PHP file extension
	*
	*/
	public function __construct(config $config, request $request, user $user, auth $auth, $phpbb_root_path, $php_ext)
	{
		$this->config = $config;
		$this->request = $request;
		$this->user = $user;
		$this->auth = $auth;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;

		$this->core_init();
	}

	/**
	* Initialize Core
	*/
	private function core_init()
	{
		// fix for an interesting bug with parse_str http://bugs.php.net/bug.php?id=48697
		// and apparently, the bug is still here in php5.3
		@ini_set("mbstring.internal_encoding", 'UTF-8');

		// reset the rewrite_method for $phpbb_root_path
		$this->rewrite_method[$this->phpbb_root_path] = [];

		if (!empty($this->seo_opt['rewrite_files']))
		{
			// phpBB files must be treated a bit differently
			$this->seo_static['file'] = [
				ATTACHMENT_CATEGORY_NONE		=> 'file',
				ATTACHMENT_CATEGORY_IMAGE		=> 'image',
				ATTACHMENT_CATEGORY_WM			=> 'wm',
				ATTACHMENT_CATEGORY_RM			=> 'rm',
				ATTACHMENT_CATEGORY_THUMB		=> 'image',
				ATTACHMENT_CATEGORY_FLASH		=> 'flash',
				ATTACHMENT_CATEGORY_QUICKTIME		=> 'qt',
			];
		}

		// Options that may be bypassed by the cached settings.
		$this->cache_config['dynamic_options'] = array_keys($this->seo_opt); // Do not change

		// copyright notice, do not change
		$this->cache_config['dynamic_options']['copyrights'] = $this->seo_opt['copyrights'] = ['img' => true, 'txt' => '', 'title' => ''];

		// Caching config
		define('PHPBB_SEO_USU_ROOT_DIR', rtrim($this->phpbb_root_path . 'ext/phpbbseo/usu/', '\\/') . '/');
		$this->seo_opt['cache_folder'] = PHPBB_SEO_USU_ROOT_DIR . 'cache/'; // where the cache file is stored

		$this->seo_opt['topic_type'] = []; // do not change
		$this->cache_config['cache_enable'] = true; // do not change
		$this->cache_config['rem_ids'] = $this->seo_opt['rem_ids']; // do not change, set up above
		$this->cache_config['file'] = $this->seo_opt['cache_folder'] . 'config.runtime.' . $this->php_ext;
		$this->cache_config['cached'] = false; // do not change
		$this->cache_config['forum_urls'] = []; // do not change
		$this->cache_config['forum'] = []; // do not change
		// $this->cache_config['topic'] = array(); // do not change
		$this->cache_config['settings'] = []; // do not change

		// --> Zero Dupe
		$this->seo_opt['zero_dupe'] = [
			'on'			=> false, // Activate or not the redirections : true / false
			'strict'		=> false, // strict compare, == VS strpos() : true / false
			'post_redir'	=> 'guest', // Redirect post urls if not valid ? : guest / all / post / off
		];
		$this->cache_config['dynamic_options']['zero_dupe'] = $this->seo_opt['zero_dupe']; // Do not change
		$this->seo_opt['zero_dupe']['do_redir'] = false; // do not change
		$this->seo_opt['zero_dupe']['go_redir'] = true; // do not change
		$this->seo_opt['zero_dupe']['do_redir_post'] = false; // do not change
		$this->seo_opt['zero_dupe']['start'] = 0; // do not change
		$this->seo_opt['zero_dupe']['redir_def'] = []; // do not change
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
			$this->seo_opt['req_self'] = str_replace(['\\', '//'], '/', $script_name);

			// basenamed page name (for example: index)
			$this->seo_opt['req_file'] = urlencode(htmlspecialchars(str_replace('.' . $this->php_ext, '', basename($this->seo_opt['req_self']))));
		}

		// Let's load config and forum urls, mods adding options in the cache file must do it in customise::init
		$this->read_config();

		// Load settings from customise.php
		$this->inject();

		// Let's make sure that settings are consistent
		$this->check_config();

		// see if we have some custom replacement
		if (!empty($this->url_replace))
		{
			$this->url_replace = [
				'find'		=> array_keys($this->url_replace),
				'replace'	=> array_values($this->url_replace)
			];
		}

		// Array of the filenames that require the use of a base href tag.
		$this->file_hbase = array_merge(
			[
				'viewtopic'		=> $this->seo_path['phpbb_url'],
				'viewforum'		=> $this->seo_path['phpbb_url'],
				'memberlist'		=> $this->seo_path['phpbb_url'],
				'search'		=> $this->seo_path['phpbb_url'],
			],
			$this->file_hbase
		);

		// Stop dirs
		$this->stop_dirs = array_merge(
			[
				$this->phpbb_root_path . 'adm/'	=> false
			],
			$this->stop_dirs
		);

		// Rewrite functions array : array('path' => array('file_name' => 'function_name'));
		// Warning, this way of doing things is path aware, this implies path to be properly sent to append_sid()
		// Allow to add options without slowing down the URL rewriting process
		$this->rewrite_method[$this->phpbb_root_path] = array_merge(
			[
				'viewtopic'		=> 'viewtopic',
				'viewforum'		=> 'viewforum',
				'index'			=> 'index',
				'memberlist'		=> 'memberlist',
				'search'		=> $this->seo_opt['rewrite_usermsg'] ? 'search' : '',
			],
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
		$pag_mtds = [];

		foreach ($this->seo_ext as $key => $ext)
		{
			$pag_mtds[$key] = trim($ext, '/') ? 'rewrite_pagination' : 'rewrite_pagination_page';
		}

		$this->paginate_method = array_merge(
			$pag_mtds,
			$this->paginate_method
		);

		$this->RegEx = array_merge(
			[
				'topic'	=> [
					'check'		=> '`^' . ($this->seo_opt['virtual_folder'] ? '%1$s/' : '') . '(' . $this->seo_static['topic'] . '|[a-z0-9_-]+' . $this->seo_delim['topic'] . ')$`i',
					'match'		=> '`^((([a-z0-9_-]+)(' . $this->seo_delim['forum'] . '([0-9]+))?/)?(' . $this->seo_static['topic'] . '(?!=' . $this->seo_delim['topic'] . ')|.+(?=' . $this->seo_delim['topic'] . '))(' . $this->seo_delim['topic'] . ')?)([0-9]+)$`i',
					'parent'	=> 2,
					'parent_id'	=> 5,
					'title'		=> 6,
					'id'		=> 8,
					'url'		=> 1,
				],
				'forum'	=> [
					'check'		=> $this->modrtype >= 2 ? '`^[a-z0-9_-]+(' . $this->seo_delim['forum'] . '[0-9]+)?$`i' : '`^' . $this->seo_static['forum'] . '[0-9]+$`i',
					'match'		=> '`^((' . $this->seo_static['forum'] . '|.+)(' . $this->seo_delim['forum'] . '([0-9]+))?)$`i',
					'title'		=> '\2',
					'id'		=> '\4',
				],
			],
			$this->RegEx
		);

		// preg_replace() patterns for format_url()
		// One could want to add |th|horn after |slash, but I'm not sure that Þ should be replaced with t and Ð with e
		$this->RegEx['url_find'] = ['`&([a-z]+)(acute|grave|circ|cedil|tilde|uml|lig|ring|caron|slash);`i', '`&(amp;)?[^;]+;`i', '`[^a-z0-9]`i']; // Do not remove : deaccentuation, html/xml entities & non a-z chars
		$this->RegEx['url_replace'] = ['\1', '-', '-'];

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
			[
				'topic'			=> ($this->seo_opt['virtual_folder'] ? '%1$s/' : '') . '%2$s' . $this->seo_delim['topic'] . '%3$s',
				'topic_smpl'		=> ($this->seo_opt['virtual_folder'] ? '%1$s/' : '') . $this->seo_static['topic'] . '%3$s',
				'forum'			=> $this->modrtype >= 2 ? '%1$s' : $this->seo_static['forum'] . '%2$s',
				'group'			=> $this->seo_opt['profile_inj'] ? '%2$s' . $this->seo_delim['group'] . '%3$s' : $this->seo_static['group'] . '%3$s',
			],
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

	/**
	* Appends the GET vars in the query string
	* @access public
	*/
	public function query_string($get_vars = [], $amp_delim = '&amp;', $url_delim = '?')
	{
		if (empty($get_vars))
		{
			return '';
		}

		$params = [];

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
}
