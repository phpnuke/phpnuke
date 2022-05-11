<?php

namespace rin\editor\acp;

class main_info
{
	public function module()
	{
		return array(
			'filename'	=> '\rin\editor\acp\main_module',
			'title'		=> 'ACP_RCE_TITLE',
			'modes'	   => array(
				'settings'	=> array(
					'title' => 'ACP_RCE_SETTING',
					'auth'	=> 'ext_rin/editor && acl_a_board',
					'cat'	=> array('ACP_RCE_TITLE'),
				),
			),
		);
	}
}
