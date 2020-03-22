<?php

namespace rin\editor\migrations;

class add_module extends \phpbb\db\migration\migration
{
	/**
	 * If our config variable already exists in the db
	 * skip this migration.
	 */
	public function effectively_installed()
	{
		return isset($this->config['RCE_enb_quick']);
	}

	/**
	 * This migration depends on phpBB's v314 migration
	 * already being installed.
	 */
	static public function depends_on()
	{
		return array('\phpbb\db\migration\data\v31x\v314');
	}

	public function update_data()
	{
		return array(

			// Add the config variable we want to be able to set
			array('config.add', array('RCE_enb_quick', 0)),
			array('config.add', array('RCE_language', 'fa')),
			array('config.add', array('RCE_mobm_source', 1)),
			array('config.add', array('RCE_smiley_sc', 0)),
			array('config.add', array('RCE_autosave', 0)),
			array('config.add', array('RCE_autosave_message', 0)),
			array('config.add', array('RCE_quickquote', 1)),
			array('config.add', array('RCE_supsment', 0)),
			array('config.add', array('RCE_height', 250)),
			array('config.add', array('RCE_max_height', 500)),
			array('config.add', array('RCE_supext', 1)),
			array('config.add', array('RCE_desnopop', 0)),
			array('config.add', array('RCE_partial', 0)),
			array('config.add', array('RCE_cache', 0)),
			array('config.add', array('RCE_seltxt', 1)),
			array('config.add', array('RCE_imgurapi', '')),
			array('config.add', array('RCE_skin', 'moonocolor')),
			array('config.add', array('RCE_rmv_acp_color', 0)),
			array('config_text.add', array('RCE_bbcode_permission', '')),
			array('config_text.add', array('RCE_style_preference', '')),
			array('config_text.add', array('RCE_skin_preference', '')),

			// Add a parent module (ACP_DEMO_TITLE) to the Extensions tab (ACP_CAT_DOT_MODS)
			array('module.add', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'ACP_RCE_TITLE'
			)),

			// Add our main_module to the parent module (ACP_DEMO_TITLE)
			array('module.add', array(
				'acp',
				'ACP_RCE_TITLE',
				array(
					'module_basename'			=> '\rin\editor\acp\main_module',
					'modes'						=> array('settings'),
				),
			)),
		);
	}
}
