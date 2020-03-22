<?php
/**
 *
 * @package       QuickReply Reloaded
 * @copyright (c) 2014 - 2017 Tatiana5 and LavIgor
 * @license       http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace boardtools\quickreply\functions;

class captcha_helper
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\captcha\factory */
	protected $captcha;

	/**
	 * Constructor
	 *
	 * @param \phpbb\config\config     $config
	 * @param \phpbb\template\template $template
	 * @param \phpbb\request\request   $request
	 * @param \phpbb\captcha\factory   $captcha
	 */
	public function __construct(\phpbb\config\config $config, \phpbb\template\template $template, \phpbb\request\request $request, \phpbb\captcha\factory $captcha)
	{
		$this->config = $config;
		$this->template = $template;
		$this->request = $request;
		$this->captcha = $captcha;
	}

	/**
	 * Initialize captcha instance
	 *
	 * @param bool $set_hidden_fields Whether we need to assign hidden fields to the template
	 */
	public function set_captcha($set_hidden_fields = true)
	{
		$captcha = $this->captcha->get_instance($this->config['captcha_plugin']);
		$captcha->init(CONFIRM_POST);

		if ($captcha->is_solved() === false)
		{
			$this->template->assign_vars(array(
				'S_CONFIRM_CODE'   => true,
				'CAPTCHA_TEMPLATE' => $captcha->get_template(),
			));
		}

		// Add the confirm id/code pair to the hidden fields, else an error is displayed on next submit/preview
		if ($set_hidden_fields && $captcha->is_solved() !== false)
		{
			$this->template->append_var('QR_HIDDEN_FIELDS', build_hidden_fields($captcha->get_hidden_fields()));
		}
	}

	/**
	 * Sends refreshed CAPTCHA if needed
	 */
	public function check_captcha_refresh()
	{
		if ($this->request->is_ajax() && $this->request->is_set('qr_captcha_refresh'))
		{
			if ($this->config['enable_post_confirm'])
			{
				$this->set_captcha(false);
			}
			$json_response = new \phpbb\json_response;
			$json_response->send(array(
				'captcha_refreshed' => true,
				'captcha_result'    => $this->template->assign_display('@boardtools_quickreply/quickreply_captcha_template.html', '', true),
			));
		}
	}
}
