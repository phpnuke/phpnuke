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

class quickreply_qn_module extends acp_module_helper
{
	public function main($id, $mode)
	{
		$this->tpl_name = 'acp_quickreply';
		$this->form_key = 'config_quickreply_qn';
		add_form_key($this->form_key);

		parent::main($id, $mode);
	}

	/**
	 * Generates the array of display_vars
	 */
	protected function generate_display_vars()
	{
		$this->display_vars = array(
			'title' => 'ACP_QUICKREPLY_QN_TITLE',
			'vars'  => array(
				'legend1'              => 'ACP_QR_LEGEND_QUICKQUOTE',
				'qr_quickquote'        => array('lang' => 'ACP_QR_QUICKQUOTE', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'qr_quickquote_button' => array('lang' => 'ACP_QR_QUICKQUOTE_BUTTON', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'qr_full_quote'        => array('lang' => 'ACP_QR_FULL_QUOTE', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'qr_last_quote'        => array('lang' => 'ACP_QR_LAST_QUOTE', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				//
				'legend2'              => 'ACP_QR_LEGEND_QUICKNICK',
				'qr_quicknick'         => array('lang' => 'ACP_QR_QUICKNICK', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'qr_quicknick_string'  => array('lang' => 'ACP_QR_QUICKNICK_STRING', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'qr_quicknick_ref'     => array('lang' => 'ACP_QR_QUICKNICK_REF', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'qr_comma'             => array('lang' => 'ACP_QR_COMMA', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'qr_color_nickname'    => array('lang' => 'ACP_QR_COLOUR_NICKNAME', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => false),
				'qr_quicknick_pm'      => array('lang' => 'ACP_QR_QUICKNICK_PM', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => false),
				//
				'legend3'              => 'ACP_SUBMIT_CHANGES',
			),
		);
	}
}
