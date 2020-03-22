<?php
/**
*
* @package addonforthanksforposts
* @copyright (c) 2014 alg
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
 */

namespace alg\addonforthanksforposts\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
{
	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\controller\helper */
	protected $controller_helper;
	/**
	* Constructor
	* @param \phpbb\template\template		$template			Template object
	* @param \phpbb\controller\helper		$controller_helper	Controller helper object

	* @access public
	*/

	public function __construct(\phpbb\template\template $template, \phpbb\controller\helper $controller_helper)
	{
		$this->template = $template;
		$this->controller_helper = $controller_helper;
	}

	static public function getSubscribedEvents()
	{
		return array(
			'core.viewtopic_modify_page_title'  => 'viewtopic_modify_page_title',
		);
	}

	public function viewtopic_modify_page_title($event)
	{
		$this->template->assign_vars(array(
			'U_ADDONFORTHANKSFORPOSTS_PATH'	=> $this->controller_helper->route('alg_addonforthanksforposts_controller_main'),
		));
	}
}
