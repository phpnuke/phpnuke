<?php
/**
 *
 * @package       QuickReply Reloaded
 * @copyright (c) 2014 - 2017 Tatiana5 and LavIgor
 * @license       http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 */

namespace boardtools\quickreply\functions;

abstract class acp_module_helper
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var string */
	protected $form_key;

	/** @var bool */
	protected $submit = false;

	/** @var array */
	protected $error;

	/** @var array */
	protected $display_vars;

	/** @var string */
	public $u_action;

	/** @var string */
	public $page_title;

	/** @var string */
	public $tpl_name;

	/** @var array */
	public $new_config = array();

	public function main($id, $mode)
	{
		global $config, $template, $user, $request;

		$this->config = $config;
		$this->new_config = $config;
		$this->template = $template;
		$this->user = $user;
		$this->request = $request;

		$this->set_submit();
		$this->generate_display_vars();
		$this->submit_form();

		// Output relevant page
		$this->output_page();
	}

	/**
	 * Generates the array of display_vars
	 */
	abstract protected function generate_display_vars();

	/**
	 * Sets the requested submission state
	 */
	protected function set_submit()
	{
		$this->submit = $this->request->is_set_post('submit');
	}

	/**
	 * When form is submitting
	 */
	protected function submit_form()
	{
		$cfg_array = ($this->request->is_set('config')) ? $this->request->variable('config', array('' => ''), true) : $this->new_config;
		$this->error = array();

		// We validate the complete config if wished
		validate_config_vars($this->display_vars['vars'], $cfg_array, $this->error);
		$this->check_form_valid();
		$this->set_config($cfg_array);

		if ($this->submit)
		{
			trigger_error($this->user->lang['CONFIG_UPDATED'] . adm_back_link($this->u_action));
		}
	}

	/**
	 * Check submitting errors
	 */
	protected function check_form_valid()
	{
		if ($this->submit && (!isset($this->form_key) || !check_form_key($this->form_key)))
		{
			$this->error[] = $this->user->lang['FORM_INVALID'];
		}

		// Do not write values if there is an error
		if (sizeof($this->error))
		{
			$this->submit = false;
		}
	}

	/**
	 * Sets the new configuration array
	 *
	 * @param array $cfg_array Array with new values
	 */
	protected function set_config($cfg_array)
	{
		// We go through the display_vars to make sure no one is trying to set variables he/she is not allowed to...
		foreach ($this->display_vars['vars'] as $config_name => $null)
		{
			if ($this->invalid_var($config_name, $cfg_array))
			{
				continue;
			}

			$this->new_config[$config_name] = $config_value = $cfg_array[$config_name];

			if ($this->submit)
			{
				$this->config->set($config_name, $config_value);
			}
		}
	}

	/**
	 * Check var for valid
	 *
	 * @param string $config_name
	 * @param array  $cfg_array
	 * @return bool
	 */
	protected function invalid_var($config_name, $cfg_array)
	{
		return (!isset($cfg_array[$config_name]) || strpos($config_name, 'legend') !== false);
	}

	/**
	 * Add language
	 *
	 * @param string $display_vars_lang
	 */
	protected function add_langs(&$display_vars_lang)
	{
		$this->user->add_lang_ext('boardtools/quickreply', 'quickreply');
		if (isset($display_vars_lang))
		{
			$this->user->add_lang($display_vars_lang);
		}
	}

	/**
	 * Gets text for title (if exists)
	 *
	 * @param array  $vars Array of vars
	 * @param string $key
	 * @param string $key2
	 * @return string
	 */
	protected function get_title($vars, $key, $key2 = '')
	{
		if (isset($this->user->lang[$vars[$key] . $key2]))
		{
			return $this->user->lang[$vars[$key] . $key2];
		}
		else
		{
			return ($key2 === '') ? $vars[$key] : '';
		}
	}

	/**
	 * Gets text for title explanation (if exists)
	 *
	 * @param array $vars Array of vars
	 * @return string
	 */
	protected function get_title_explain($vars)
	{
		if (!$vars['explain'])
		{
			return '';
		}

		$explain2 = (isset($vars['lang_explain2'])) ? '<br />' . $this->get_title($vars, 'lang_explain2') : '';
		if (isset($vars['lang_explain']))
		{
			return $this->get_title($vars, 'lang_explain') . $explain2;
		}
		else
		{
			return $this->get_title($vars, 'lang', '_EXPLAIN') . $explain2;
		}
	}

	/**
	 * Outputs page title and errors
	 */
	protected function output_basic_vars()
	{
		$this->template->assign_vars(array(
			'L_TITLE'         => $this->user->lang[$this->display_vars['title']],
			'L_TITLE_EXPLAIN' => $this->user->lang[$this->display_vars['title'] . '_EXPLAIN'],

			'S_ERROR'   => (sizeof($this->error)) ? true : false,
			'ERROR_MSG' => implode('<br />', $this->error),
		));
	}

	/**
	 * Outputs generated array for an option to the template
	 *
	 * @param string $config_key The name of the option
	 * @param array  $vars       Array of parameters
	 * @param string $content    Generated template option string
	 */
	protected function output_options($config_key, $vars, $content)
	{
		$this->template->assign_block_vars('options', array(
			'KEY'           => $config_key,
			'TITLE'         => $this->get_title($vars, 'lang'),
			'S_EXPLAIN'     => $vars['explain'],
			'TITLE_EXPLAIN' => $this->get_title_explain($vars),
			'CONTENT'       => $content,
		));
	}

	/**
	 * Gets array with language strings for a legend
	 *
	 * @param array|string $vars String with legend language key or
	 *                           array with legend and legend explain language keys
	 * @return array
	 */
	protected function get_legend_array($vars)
	{
		if (is_array($vars))
		{
			$legend = $this->user->lang(array_shift($vars));
			$legend_explain = $this->user->lang(array_shift($vars));
		}
		else
		{
			$legend = $this->user->lang($vars);
			$legend_explain = '';
		}
		return array(
			'S_LEGEND'       => true,
			'LEGEND'         => $legend,
			'LEGEND_EXPLAIN' => $legend_explain,
		);
	}

	/**
	 * Output options
	 */
	protected function output_vars($config_key, $vars)
	{
		if (strpos($config_key, 'legend') !== false)
		{
			$this->template->assign_block_vars('options', $this->get_legend_array($vars));
		}
		else
		{
			$type = explode(':', $vars['type']);

			$content = build_cfg_template($type, $config_key, $this->new_config, $config_key, $vars);

			if (!empty($content))
			{
				$this->output_options($config_key, $vars, $content);
				unset($this->display_vars['vars'][$config_key]);
			}
		}
	}

	/**
	 * Output the page
	 */
	protected function output_page()
	{
		$this->page_title = $this->display_vars['title'];
		$this->add_langs($this->display_vars['lang']);
		$this->output_basic_vars();

		foreach ($this->display_vars['vars'] as $config_key => $vars)
		{
			if (!is_array($vars) && strpos($config_key, 'legend') === false)
			{
				continue;
			}

			$this->output_vars($config_key, $vars);
		}
	}
}
