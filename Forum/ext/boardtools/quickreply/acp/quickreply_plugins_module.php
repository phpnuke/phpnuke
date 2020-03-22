<?php
/**
 *
 * @package       QuickReply Reloaded
 * @copyright (c) 2014 - 2017 Tatiana5 and LavIgor
 * @license       http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 */

namespace boardtools\quickreply\acp;

use boardtools\quickreply\functions\acp_module_helper;

class quickreply_plugins_module extends acp_module_helper
{
	public function main($id, $mode)
	{
		$this->tpl_name = 'acp_quickreply';
		$this->form_key = 'config_quickreply_plugins';
		add_form_key($this->form_key);

		parent::main($id, $mode);
	}

	/**
	 * Generates the array of display_vars
	 */
	protected function generate_display_vars()
	{
		$this->display_vars = array(
			'title' => 'ACP_QUICKREPLY_PLUGINS_TITLE',
			'vars'  => array(
				'legend1'                    => 'ACP_QR_LEGEND_SPECIAL',
				'qr_enable_re'               => array('lang' => 'ACP_QR_ENABLE_RE', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'qr_show_subjects'           => array('lang' => 'ACP_QR_SHOW_SUBJECTS', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => false),
				'qr_show_subjects_in_search' => array('lang' => 'ACP_QR_SHOW_SUBJECTS_IN_SEARCH', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => false),
				'qr_hide_subject_box'        => array('lang' => 'ACP_QR_HIDE_SUBJECT_BOX', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				//
				'legend2'                    => 'ACP_SUBMIT_CHANGES',
			),
		);
	}
}
