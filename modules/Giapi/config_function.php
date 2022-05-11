<?php

/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2006 by Francisco Burzi                                */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if (!defined('CONFIG_FUNCTIONS_FILE')) {
	die("You can't access this file directly...");
}

$this_module_name = basename(dirname(__FILE__));

define("GIAPI_DEBUG", true);

/**
 * Require plugin class.
 */
require_once "modules/$this_module_name/includes/class-instant-indexing.php";
/**
 * Init plugin.
 *
 * @return void
 */
$rm_giapi = new RM_GIAPI();

function rankmath_config()
{
	global $nuke_configs, $admin_file, $rm_giapi, $apiaction, $apiurl, $apipostid;
	$contents = '';
	
	$submit_onload = false;
	if ( isset($apiaction) && ! empty( $apiaction ) && ((isset($apiurl) && ! empty( $apiurl )) || (isset($apiurl) && ! empty( $apipostid ) ) ) ) {
		$submit_onload = true;
	}

	$contents .= "<script id='instant-indexing-console-js-extra'>
	var rm_giapi = {
		'admin_file': '".$admin_file.".php',
		'submit_onload': '".$submit_onload."',
		'l10n_success':'"._GIAPI_SUCCESS."',
		'l10n_error':'"._GIAPI_ERROR."',
		'l10n_last_updated':'"._GIAPI_LAST_UPDATE."',
		'l10n_see_response':'"._GIAPI_SEE_RESPONSE."',
	};
	</script>";
	$contents .= "<script src=\"".LinkToGT("modules/Giapi/includes/assets/js/console.js")."\"></script>
	<link rel=\"stylesheet\" id=\"instant-indexing-admin-css\"  href=\"".LinkToGT("modules/Giapi/includes/assets/css/admin.css")."\" />";
	//asd(phpnuke_unserialize($nuke_configs['giapi_settings']));
	$contents .= $rm_giapi->show_console();
	die($contents);
}

function giapi_settings($other_admin_configs){
	$other_admin_configs['giapi'] = array("title" => '_GIAPI', "function" => "rankmath_config", "God" => false);
	return $other_admin_configs;
}

$hooks->add_filter("other_admin_configs", "giapi_settings", 10);



?>