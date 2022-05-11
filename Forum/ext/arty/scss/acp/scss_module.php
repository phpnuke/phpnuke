<?php
/**
 *
 * @package SCSS Compiler
 * @author Arty (Vjacheslav Trushkin) cyberalien@gmail.com
 * @copyright (c) 2015 artodia.com
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace arty\scss\acp;

class scss_module
{
	/** @var string */
	public $u_action;
	protected $s_hidden_fields;
	protected $mode;
	protected $styles_path;
	protected $styles_path_absolute = 'styles';

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\request\request_interface */
	protected $request;

	/** @var \phpbb\cache\driver\driver_interface */
	protected $cache;

	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var string */
	protected $phpbb_root_path;

	/** @var string */
	protected $php_ext;

	public function main($id, $mode)
	{
		global $db, $user, $phpbb_admin_path, $phpbb_root_path, $phpEx, $template, $request, $cache, $auth, $config;

		$this->db = $db;
		$this->user = $user;
		$this->template = $template;
		$this->request = $request;
		$this->cache = $cache;
		$this->auth = $auth;
		$this->config = $config;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $phpEx;

		$user->add_lang(array('acp/styles'));

		$this->styles_path = $this->phpbb_root_path . $this->styles_path_absolute . '/';

		$this->u_base_action = append_sid("{$phpbb_admin_path}index.{$this->php_ext}", "i=-arty-scss-acp-scss_module");
		$this->s_hidden_fields = array(
			'mode'		=> $mode,
		);

		$this->tpl_name = 'acp_scss';
		$this->page_title = $user->lang['ACP_SCSS_COMPILER'];
		$this->mode = $mode;

		$this->assert_required_modules();
		$this->frontend();
	}

	/**
	 * Main action
	 */
	protected function frontend()
	{
		$styles = $this->find_all_eligible_styles();
		if (!sizeof($styles))
		{
			trigger_error($this->user->lang['ACP_SCSS_NO_STYLES'] . adm_back_link($this->u_action), E_USER_WARNING);
		}

		// Compile styles
		$compile = $this->request->variable('style', '');
		if ($compile == '')
		{
			$compile = $this->request->variable('styles', array(''));
		}
		else
		{
			$compile = array($compile);
		}
		foreach ($compile as $style)
		{
			if (isset($styles[$style]))
			{
				$this->compile($style, $styles[$style]);
			}
		}

		// Display styles list
		foreach ($styles as $style => $path)
		{
			// Get last stylesheet.css modification time
			$filename = $path . 'stylesheet.css';
			$css_time = @file_exists($filename) ? @filemtime($filename) : 0;

			// Find all .scss files
			$files = $this->find_scss_files($path);
			$scss_time = 0;
			foreach ($files as $file)
			{
				$filename = $path . $file;
				$scss_time = max(@file_exists($filename) ? @filemtime($filename) : 0, $scss_time);
			}

			// Status
			$status = 'OK';
			if (!$css_time)
			{
				$status = 'MISSING_CSS';
			}
			elseif ($css_time < $scss_time)
			{
				$status = 'RECOMPILE';
			}

			// Add template variables
			$this->template->assign_block_vars('styles_list', array(
				'DIR'		=> $style,
				'STYLE_NAME'	=> $this->find_style_name($style),
				'CSS_TIME'	=> $css_time,
				'SCSS_TIME'	=> $scss_time,
				'STATUS'	=> $status,
				'L_STATUS'	=> $this->user->lang['SCSS_STATUS_' . $status],
				'U_COMPILE'	=> $this->u_base_action . '&amp;mode=main&style=' . urlencode($style),
			));
		}
	}

	/**
	 * Compile style
	 */
	protected function compile($style, $path)
	{
		// Test if stylesheet.css is writable
		$test = @file_exists($path . 'stylesheet.css') ? $path . 'stylesheet.css' : $path;
		if (!@is_writable($test))
		{
			$this->compilation_error($style, $this->user->lang['STYLESHEET_ISNOT_WRITABLE']);
			return false;
		}

		// Find all scss files
		$scss = $this->find_scss_files($path);

		if (!in_array('stylesheet.scss', $scss))
		{
			$this->compilation_error($style, $this->user->lang['NOTHING_TO_COMPILE']);
			return false;
		}

		// Generate data
		$data = @file_get_contents($path . 'stylesheet.scss');
		if (!strlen($data))
		{
			$this->compilation_error($style, $this->user->lang['MISSING_STYLESHEET_SCSS']);
			return false;
		}

		$post = array(
			'scss'	=> $data,
			'style'	=> 'compact',
			'files'	=> array(),
		);

		foreach ($scss as $file)
		{
			if ($file == 'stylesheet.scss')
			{
				continue;
			}
			$post['files'][$file] = @file_get_contents($path . $file);
		}

		// Send data to server
		$url = 'http://phpbb31.artodia.com/scss.php';

		$encoded = json_encode($post);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $encoded);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($encoded))
		);
		$result = curl_exec($ch);

		if (!strlen($result))
		{
			$this->compilation_error($style, $this->user->lang['SERVER_RETURNED_EMPTY_RESULT']);
			return false;
		}

		// Decode result
		$result = json_decode($result, true);
		if (!is_array($result))
		{
			$this->compilation_error($style, $this->user->lang['SERVER_RETURNED_INVALID_RESULT']);
			return false;
		}

		if (isset($result['error']))
		{
			$this->compilation_error($style, nl2br($result['error']));
			return false;
		}

		if (!isset($result['css']))
		{
			$this->compilation_error($style, $this->user->lang['SERVER_RETURNED_INVALID_RESULT']);
			return false;
		}

		$data = $result['css'];
		if (!strlen($data))
		{
			$this->compilation_error($style, $this->user->lang['SERVER_RETURNED_EMPTY_RESULT']);
			return false;
		}

		@file_put_contents($path . 'stylesheet.css', $data);
		@chmod($path . 'stylesheet.css', 0644);

		$this->template->assign_block_vars('results', array(
			'DIR'		=> $style,
			'STYLE_NAME'	=> $this->find_style_name($style),
			'SUCCESS'	=> true,
		));

		return true;
	}

	/**
	 * Compilation error
	 */
	protected function compilation_error($style, $error)
	{
		$this->template->assign_block_vars('results', array(
			'DIR'		=> $style,
			'STYLE_NAME'	=> $this->find_style_name($style),
			'ERROR'	=> htmlspecialchars($error),
		));
	}

	/**
	 * Find all styles with sass files
	 * 
	 * @return array [dir] = path to theme with trailing /
	 */
	protected function find_all_eligible_styles()
	{
		$styles = array();

		foreach (new \DirectoryIterator($this->styles_path) as $fileInfo)
		{
			if ($fileInfo->isDot() || !$fileInfo->isDir())
			{
				continue;
			}
			$test = $fileInfo->getPathname() . '/theme/stylesheet.scss';
			if (@file_exists($test))
			{
				$styles[$fileInfo->getFilename()] = $fileInfo->getPathname() . '/theme/';
			}
		}

		return $styles;
	}

	/**
	 * Find all .scss files in directory
	 * 
	 * @param string $path Path to directory
	 * @param string $prefix Prefix for files in resulting array
	 * 
	 * @return array
	 */
	protected function find_scss_files($path, $prefix = '')
	{
		$files = array();

		foreach (new \DirectoryIterator($path) as $fileInfo)
		{
			if ($fileInfo->isDot())
			{
				continue;
			}

			if ($fileInfo->isDir())
			{
				$files = array_merge($files, $this->find_scss_files($fileInfo->getPathname(), $prefix . $fileInfo->getFilename() . '/'));
			}
			elseif ($fileInfo->isFile() && $fileInfo->getExtension() == 'scss')
			{
				$files[] = $prefix . $fileInfo->getFilename();
			}
		}

		return $files;
	}


	/**
	 * Check if all required functions are supported
	 */
	protected function assert_required_modules()
	{
		if (!function_exists('curl_init'))
		{
			trigger_error($this->user->lang['ACP_SCSS_MISSING_CURL'] . adm_back_link($this->u_action), E_USER_WARNING);
		}
		if (!function_exists('json_encode'))
		{
			trigger_error($this->user->lang['ACP_SCSS_MISSING_JSON'] . adm_back_link($this->u_action), E_USER_WARNING);
		}
	}

	/**
	 * Find style name
	 * 
	 * @param string $style Style directory
	 * 
	 * @return string Style name. Style directory is returned if function fails to read style name
	 */
	protected function find_style_name($style)
	{
		$filename = $this->styles_path . $style . '/style.cfg';
		if (!@file_exists($filename))
		{
			return $style;
		}

		$cfg = parse_cfg_file($filename);
		if ($cfg && isset($cfg['name']))
		{
			return $cfg['name'];
		}

		return $style;
	}
}
