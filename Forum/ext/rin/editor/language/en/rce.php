<?php

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	// Front
	'RCE_RESTORE'					=> 'Restore',
	'RCE_MORE'						=> 'More',
	'RCE_INSERT_A_VIDEO'			=> 'Insert a video',
	'RCE_ENTER_URL'					=> 'Enter URL:',
	'RCE_ENTER_THE_IMAGE_URL'		=> 'Enter the image URL:',
	'RCE_DESCRIPTION_OPTIONAL'		=> 'Description (optional):',
	'RCE_INSERT'					=> 'Insert',
	'RCE_VIDEO_URL'					=> 'Video URL or ID:',
	'RCE_QUICK_QUOTE'				=> 'Quote Text',

	// ACP
	'ACP_RCE_TITLE'			=>	'Rin Editor (Powerd by CKEditor)',
	'ACP_RCE_SETTING'		=>	'Settings related to the Rin Editor',
	'RCE_CONFIG_UPDATE'		=>	'Updated Rin Editor settings',
	'RCE_SETTING_SAVED'		=>	'Settings have been saved successfully!',
	'RCE_LANGUAGE_TITLE'	=>	'Language of Rin Editor',
	'RCE_LANGUAGE_DESC'		=>	'Set here language of Rin Editor. Ps. Use default if you want editor detect browser language.',
	'RCE_BBCODE_TITLE'		=>	'Choice BBCode',
	'RCE_BBCODE_DESC'		=>	'Choice BBCode that you want change group permission.',
	'RCE_PBBCODE_TITLE'		=>	'Group permission for ',
	'RCE_PBBCODE_DESC'		=>	'Select groups that you want give permission to use custom BBCode.<br /><strong>Ps.</strong> If you do not select any group, all groups have permission to use this BBCode.',
	'RCE_MOBMS_TITLE'		=>	'Source Mode in Mobile',
	'RCE_MOBMS_DESC'		=>	'Set to yes if you want load Rin Editor in Source Mode when using mobile device.',
	'RCE_ENBQUICK_TITLE'	=>	'Show Rin Editor in quick reply and quick edit',
	'RCE_ENBQUICK_DESC'		=>	'Set to yes if you want to show the Rin Editor in quick reply and quick edit.',
	'RCE_SCSMILEY_TITLE'	=>	'SCEditor style like Smile Box',
	'RCE_SCSMILEY_DESC'		=>	'Set to yes if you want to use Smile Box like SCEditor instead CKEditor style.',
	'RCE_AUTOSAVE_TITLE'	=>	'Autosave Feature',
	'RCE_AUTOSAVE_DESC'		=>	'Set to no if you want disable autosave feature.',
	'RCE_AUTOSAVEMSG_TITLE' =>	'Autosaved Notification',
	'RCE_AUTOSAVEMSG_DESC'	=>	'Set to yes if you want show autosaved notification.',
	'RCE_QUICKQUOTE_TITLE'	=>	'Quick Quote Feature',
	'RCE_QUICKQUOTE_DESC'	=>	'Set to no if you do not want enable quick quote feature.',
	'RCE_SUPSMENT_TITLE'	=>	'Active support for Simple mentions extension',
	'RCE_SUPSMENT_DESC'		=>	'Set to no if you do not want enable support for Simple mentions extension feature.<br /><strong>Ps.</strong> Before active this feature, you need to install Simple mentions extension. https://www.phpbb.com/customise/db/extension/simple_mentions/',
	'RCE_HEIGHT_TITLE'		=>	'Height of the editor',
	'RCE_HEIGHT_DESC'		=>	'Set the height of the editor (value in px).',
	'RCE_MAX_HEIGHT_TITLE'	=>	'Max height of the editor',
	'RCE_MAX_HEIGHT_DESC'	=>	'Set the max height of the editor (value in px).',
	'RCE_SUPEXT_TITLE'		=>	'Active support for external extensions buttons',
	'RCE_SUPEXT_DESC'		=>	'Set to yes if you want enable support for external extensions buttons.<br /><strong>Ps.</strong> This feature does not work in ACP, Quick Reply and Quick Edit.',
	'RCE_DESNOPOP_TITLE'	=>	'No popup of description to custom buttons',
	'RCE_DESNOPOP_DESC'		=>	'Set to yes if you do not want popup of description',
	'RCE_PARTIAL_TITLE'		=>	'Partial Mode',
	'RCE_PARTIAL_DESC'		=>	'Set to yes if you want enable Partial Mode feature.<br /><strong>Ps.</strong> This feature does not convert quote tag and code tag in WYSIWYG style like in Xenforo.',
	'RCE_SELTXT_TITLE'		=>	'Do not replace selected text',
	'RCE_SELTXT_DESC'		=>	'Set to yes if you do not want that selected text is replaced when the custom button is triggered.<br /><strong>Ps.</strong> Enabling this function may result in some bugs.',
	'RCE_RMV_ACP_COLOR_TITLE'	=>	'Remove ACP Color Picker',
	'RCE_RMV_ACP_COLOR_DESC'	=>	'Set to yes if you want remove acp color picker.',
	'RCE_CACHE_TITLE'		=>	'Cache',
	'RCE_CACHE_DESC'		=>	'Set the cache time in seconds. Set 0 to disable this function. Max value allowed is 86400.<br /><strong>Ps.</strong> After fully configured it is strongly recommended to use the cache to not inhibit performance.',
	'RCE_IMGUR_TITLE'		=>	'Imgur',
	'RCE_IMGUR_DESC'		=>	'Set here API of imgur (Client ID).<br /><strong>Ps.</strong> You can get client id in https://imgur.com/register/api_anon (oauth2 without callback)',
	'RCE_STYLE_TITLE'		=>	'Choice Style',
	'RCE_STYLE_DESC'		=>	'Choice Style that you want change skin of editor.',
	'RCE_SKIN_TITLE'		=>	'Skin for ',
	'RCE_SKIN_DESC'			=>	'Enter the Skin name. <br /><strong>Location to put new skin:</strong> root/ext/rin/editor/styles/all/template/js/skins/',
	'RCE_TXTA_TITLE'		=>	'Text area color for ',
	'RCE_TXTA_DESC'			=>	'Set to yes if you want change text area color to black.',
));
