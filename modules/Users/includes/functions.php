<?php
/**
*
* This file is part of the PHP-NUKE Software package.
*
* @copyright (c) PHP-NUKE <https://www.phpnuke.ir>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

if (!defined("MODULE_FILE"))
{
    header("Location: ../../../index.php");
    die ();
}

$ya_config = (isset($ya_config) && !empty($ya_config)) ? $ya_config:((isset($nuke_configs['users']) && $nuke_configs['users'] != '') ? phpnuke_unserialize(stripslashes($nuke_configs['users'])):array());

$ya_config['data_verification']['username'] = array(
	"data-validation" => "required length server",
	"data-validation-length" => "".$ya_config['nick_min']."-".$ya_config['nick_max']."",
	"data-validation-url" => LinkToGT("index.php?modname=$module_name&op=check_register_fields"),
	"data-validation-req-params" => array('json', json_encode(array('field'=>'username', 'mode'=>'{MODE}', 'default_value'=>'{DEFAULT}', 'csrf_token'=>get_form_token()))),
	"data-validation-param-name" => "value"
);

$ya_config['data_verification']['user_realname'] = array(
	"data-validation"=> "server",
	"data-validation-url" => LinkToGT("index.php?modname=$module_name&op=check_register_fields"),
	"data-validation-req-params" => array('json', json_encode(array('field'=>'user_realname', 'default_value'=>'{DEFAULT}', 'mode'=>'{MODE}', 'csrf_token'=>get_form_token()))),
	"data-validation-param-name" => "value"
);

$ya_config['data_verification']['user_email'] = array(
	"data-validation" => "required email server",
	"data-validation-url" => LinkToGT("index.php?modname=$module_name&op=check_register_fields"),
	"data-validation-req-params" => array('json', json_encode(array('field'=>'user_email', 'default_value'=>'{DEFAULT}', 'mode'=>'{MODE}', 'csrf_token'=>get_form_token()))),
	"data-validation-param-name" => "value"
);

if($ya_config['doublecheckemail'] == 1)
{
	$ya_config['data_verification']['user_email_cn'] = array(
		"data-validation" => "required email confirmation",
		"data-validation-confirm" => "users_fields[user_email]",
	);
}

$ya_config['data_verification']['user_password'] = array(
	"data-validation" => "required length",
	"data-validation-length" => "".$ya_config['pass_min']."-".$ya_config['pass_max']."",
);

$ya_config['data_verification']['user_password_cn'] = array(
	"data-validation" => "required confirmation",
	"data-validation-confirm" => "users_fields[user_password]",
);


function ya_fixtext($ya_fixtext, $param = NULL)
{
    if ($ya_fixtext == "") { return $ya_fixtext; }
    $ya_fixtext = stripslashes($ya_fixtext);
    $ya_fixtext = str_replace("\'","",$ya_fixtext);
    $ya_fixtext = str_replace("\'","",$ya_fixtext);
    $ya_fixtext = str_replace("\'","&acute;",$ya_fixtext);
    $ya_fixtext = strip_tags($ya_fixtext);
    //if (!get_magic_quotes_gpc())
		$ya_fixtext = addslashes($ya_fixtext);
		
	//$ya_fixtext = addslashes($ya_fixtext);
    return $ya_fixtext;
}

function checkinvite($code, $invited_email)
{
    global $ya_config, $db;
	$inviter = $db->table(USERS_INVITES_TABLE)
						->where('email', $invited_email)
						->where('code', $code)
						->first(['rid']);
						
	return (intval($inviter->count()) > 0) ? intval($inviter['rid']):0;
}

//
// Users avatar functions
//
/**
* Avatar upload using the upload class
*/
function avatar_upload($data, &$error)
{
	global $ya_config, $nuke_configs;

	// Init upload class
	define('ALLOWED_UPLOAD', true);
	include_once('modules/Users/includes/functions_upload.php');
	$upload = new fileupload('AVATAR_', array('jpg', 'jpeg', 'gif', 'png'), $ya_config['avatar_filesize'], $ya_config['avatar_min_width'], $ya_config['avatar_min_height'], $ya_config['avatar_max_width'], $ya_config['avatar_max_height'], (isset($ya_config['mime_triggers']) ? explode('|', $ya_config['mime_triggers']) : false));

	if (!empty($_FILES['uploadfile']['name']))
	{
		$file = $upload->form_upload('uploadfile');
	}
	else
	{
		$file = $upload->remote_upload($data['uploadurl']);
	}
	
	$prefix = $ya_config['avatar_salt'] . '_';
	$file->clean_filename('avatar', $prefix, $data['user_id']);

	$destination = $ya_config['avatar_path'];

	// Adjust destination path (no trailing slash)
	if (substr($destination, -1, 1) == '/' || substr($destination, -1, 1) == '\\')
	{
		$destination = substr($destination, 0, -1);
	}

	$destination = str_replace(array('../', '..\\', './', '.\\'), '', $destination);
	if ($destination && ($destination[0] == '/' || $destination[0] == "\\"))
	{
		$destination = '';
	}
	
	// Move file and overwrite any existing image
	$file->move_file($destination, true);

	if (sizeof($file->error))
	{
		$file->remove();
		$error = array_merge($error, $file->error);
	}
	return $data['user_id'] . '_' . time() . '.' . $file->get('extension');
}

/**
* Remote avatar linkage
*/
function avatar_remote($data, &$error)
{
	global $ya_config, $db, $user;

	if (!preg_match('#^(http|https|ftp)://#i', $data['remotelink']))
	{
		$data['remotelink'] = 'http://' . $data['remotelink'];
	}
	if (!preg_match('#^(http|https|ftp)://(?:(.*?\.)*?[a-z0-9\-]+?\.[a-z]{2,4}|(?:\d{1,3}\.){3,5}\d{1,3}):?([0-9]*?).*?\.(gif|jpg|jpeg|png)$#i', $data['remotelink']))
	{
		$error[] = _INVALID_AVATAR_LINK;
		return false;
	}

	// Make sure getimagesize works...
	if (($image_data = @getimagesize($data['remotelink'])) === false)
	{
		$error[] = _PROBLEM_IN_AVATAR_INFO;
		return false;
	}

	if (!empty($image_data) && ($image_data[0] < 2 || $image_data[1] < 2))
	{
		$error[] = _AVATAR_HAS_LOW_DIM;
		return false;
	}

	// Check image type
	define('ALLOWED_UPLOAD', true);
	include_once('modules/Users/includes/functions_upload.php');
	$fileupload = new fileupload();
	$filespec = new filespec('','');
	$types = $fileupload->image_types();
	$extension = strtolower($filespec->get_extension($data['remotelink']));

	if (!empty($image_data) && (!isset($types[$image_data[2]]) || !in_array($extension, $types[$image_data[2]])))
	{
		if (!isset($types[$image_data[2]]))
		{
			$error[] = _PROBLEM_IN_AVATAR_INFO;
		}
		else
		{
			$error[] = _AVATAR_NOT_SUPPORTED;
		}
		return false;
	}

	if ($ya_config['avatar_max_width'] || $ya_config['avatar_max_height'])
	{
		if ($image_data[0] > $ya_config['avatar_max_width'] || $image_data[1] > $ya_config['avatar_max_height'])
		{
			$error[] = _AVATAR_HAS_HIGH_DIM;
			return false;
		}
	}

	if ($ya_config['avatar_min_width'] || $ya_config['avatar_min_height'])
	{
		if ($image_data[0] < $ya_config['avatar_min_width'] || $image_data[1] < $ya_config['avatar_min_height'])
		{
			$error[] = _AVATAR_HAS_LOW_DIM;
			return false;
		}
	}

	return $data['remotelink'];
}

/**
* gravatar avatar linkage
*/
function avatar_gravatar($data, &$error)
{
	global $ya_config;

	$user_gravatar = '';
	
	if(isset($data['gravatar']) && $data['gravatar'] != '')
	{
		$gravatar =  adv_filter($data['gravatar'], array('sanitize_email'));
		
		if($gravatar[0] != 'error')
			$user_gravatar = $gravatar[1];
		else
		{
			$error[] = "خطا : ".$gravatar[1]."";
			return false;
		}
	}

	if(!validate_mail($user_gravatar))
	{
		$error[] = "ایمیل نامعتبر : $user_gravatar";
		return false;
	}

	$avatar_url = _GRAVATAR_URL;
	$avatar_url .=  md5($user_gravatar);	

	// Make sure getimagesize works...
	if (($image_data = getimagesize($avatar_url)) === false)
	{
		$error[] = _PROBLEM_IN_AVATAR_INFO;
		//return false;
	}

	if (!empty($image_data) && ($image_data[0] < 2 || $image_data[1] < 2))
	{
		$error[] = _AVATAR_HAS_LOW_DIM;
		//return false;
	}
	
	return $user_gravatar;
}

/**
* Remove avatar
*/
function avatar_delete($mode, $row)
{
	global $ya_config, $db, $user;
	
	$filename = get_avatar_filename($row['user_avatar']);
	if (file_exists($ya_config['avatar_path'] . '/' . $filename))
	{
		@unlink($ya_config['avatar_path'] . '/' . $filename);
		return true;
	}

	return false;
}

/**
* Generates avatar filename from the database entry
*/
function get_avatar_filename($avatar_entry)
{
	global $ya_config;

	$ext 			= substr(strrchr($avatar_entry, '.'), 1);
	$avatar_entry	= intval($avatar_entry);
	return $ya_config['avatar_salt'] . '_' . $avatar_entry . '.' . $ext;
}

/**
* Tries to (re-)establish avatar dimensions
*/
function avatar_get_dimensions($avatar, $avatar_type, &$error, $current_x = 0, $current_y = 0)
{
	global $ya_config;

	switch ($avatar_type)
	{
		case AVATAR_REMOTE :
			break;

		case AVATAR_UPLOAD :
			$avatar = $ya_config['avatar_path'] . '/' . get_avatar_filename($avatar);
			break;
	}

	// Make sure getimagesize works...
	if (($image_data = @getimagesize($avatar)) === false)
	{
		$error[] = _PROBLEM_IN_AVATAR_INFO;
		return false;
	}

	if ($image_data[0] < 2 || $image_data[1] < 2)
	{
		$error[] = _AVATAR_HAS_LOW_DIM;
		return false;
	}

	// try to maintain ratio
	if (!(empty($current_x) && empty($current_y)))
	{
		if ($current_x != 0)
		{
			$image_data[1] = (int) floor(($current_x / $image_data[0]) * $image_data[1]);
			$image_data[1] = min($ya_config['avatar_max_height'], $image_data[1]);
			$image_data[1] = max($ya_config['avatar_min_height'], $image_data[1]);
		}
		if ($current_y != 0)
		{
			$image_data[0] = (int) floor(($current_y / $image_data[1]) * $image_data[0]);
			$image_data[0] = min($ya_config['avatar_max_width'], $image_data[1]);
			$image_data[0] = max($ya_config['avatar_min_width'], $image_data[1]);
		}
	}
	return array($image_data[0], $image_data[1]);
}

/**
* Uploading/Changing user avatar
*/
function avatar_process_user($avatar_type, $avatar, $can_upload = false)
{
	global $ya_config, $userinfo;
	
	$error = array();
	$data = array(
		'uploadurl'		=> $avatar['uploadurl'],
		'remotelink'	=> $avatar['remotelink'],
		'gravatar'		=> $avatar['gravatar_email'],
		'width'			=> 0,
		'height'		=> 0,
	);
	
	$user_avatar = '';

	$data['user_id'] = $userinfo['user_id'];
	$data['user_avatar'] = $userinfo['user_avatar'];

	// Can we upload?
	if (!$can_upload)
	{
		$can_upload = ($ya_config['allow_avatar_upload'] && file_exists($ya_config['avatar_path']) && (@ini_get('file_uploads') || strtolower(@ini_get('file_uploads')) == 'on')) ? true : false;
	}
	if ((!empty($_FILES['uploadfile']['name']) || $data['uploadurl']) && $can_upload && $avatar_type == 'upload')
	{
		$user_avatar = avatar_upload($data, $error);
	}
	else if ($data['remotelink'] && $avatar_type == 'remote' && $ya_config['allow_avatar_remote'])
	{
		$user_avatar = avatar_remote($data, $error);
	}
	else if ($data['gravatar'] && $avatar_type == 'gravatar' && $ya_config['allow_gravatar'])
	{
		$user_avatar = avatar_gravatar($data, $error);
	}

	if (!sizeof($error))
	{
		$ext_new = $ext_old = '';
		if ($user_avatar != '')
		{
			$ext_new = (empty($user_avatar)) ? '' : substr(strrchr($user_avatar, '.'), 1);
			$ext_old = (empty($userinfo['user_avatar'])) ? '' : substr(strrchr($userinfo['user_avatar'], '.'), 1);

			if ($userinfo['user_avatar_type'] == AVATAR_UPLOAD)
			{
				// Delete old avatar if present
				if ((!empty($userinfo['user_avatar']) && empty($user_avatar))
				   || ( !empty($userinfo['user_avatar']) && !empty($user_avatar) && $ext_new !== $ext_old))
				{
					avatar_delete('user', $userinfo);
				}
			}
		}
	}
	else
		die_error(implode("<br />", $error));	
	
	return $user_avatar;
}

function is_greater_ie_version($user_agent, $version)
{
	if (preg_match('/msie (\d+)/', strtolower($user_agent), $matches))
	{
		$ie_version = (int) $matches[1];
		return ($ie_version > $version);
	}
	else
	{
		return false;
	}
}

function header_filename($user_agent, $file)
{
	// There be dragons here.
	// Not many follows the RFC...
	if (strpos($user_agent, 'MSIE') !== false || strpos($user_agent, 'Safari') !== false || strpos($user_agent, 'Konqueror') !== false)
	{
		return "filename=" . rawurlencode($file);
	}

	// follow the RFC for extended filename for the rest
	return "filename*=UTF-8''" . rawurlencode($file);
}

function _get_user_avatar($filename)
{
	global $db, $prefix, $ya_config, $user, $users_system;

	$browser = $_SERVER['HTTP_USER_AGENT'];
	
	$ext		= substr(strrchr($filename, '.'), 1);
	$stamp		= (int) substr(stristr($filename, '_'), 1);
	$filename	= (int) $filename;
	
	$file = $filename . '.' . $ext;

	$prefix = $ya_config['avatar_salt'] . '_';
	$image_dir = $ya_config['avatar_path'];

	// Adjust image_dir path (no trailing slash)
	if (substr($image_dir, -1, 1) == '/' || substr($image_dir, -1, 1) == '\\')
	{
		$image_dir = substr($image_dir, 0, -1) . '/';
	}
	$image_dir = str_replace(array('../', '..\\', './', '.\\'), '', $image_dir);

	if ($image_dir && ($image_dir[0] == '/' || $image_dir[0] == '\\'))
	{
		$image_dir = '';
	}
	$file_path = $image_dir . '/' . $prefix . $file;

	if ((@file_exists($file_path) && @is_readable($file_path)) && !headers_sent())
	{
		header('Cache-Control: public');

		$image_data = @getimagesize($file_path);
		header('Content-Type: ' . image_type_to_mime_type($image_data[2]));

		if ((strpos(strtolower($browser), 'msie') !== false) && !is_greater_ie_version($browser, 7))
		{
			header('Content-Disposition: attachment; ' . header_filename($browser, $file));

			if (strpos(strtolower($browser), 'msie 6.0') !== false)
			{
				header('Expires: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
			}
			else
			{
				header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
			}
		}
		else
		{
			header('Content-Disposition: inline; ' . header_filename($browser, $file));
			header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
		}

		$size = @filesize($file_path);
		if ($size)
		{
			header("Content-Length: $size");
		}

		if (@readfile($file_path) == false)
		{
			$fp = @fopen($file_path, 'rb');

			if ($fp !== false)
			{
				while (!feof($fp))
				{
					echo fread($fp, 8192);
				}
				fclose($fp);
			}
		}

		flush();
	}
	else
	{
		header('HTTP/1.0 404 Not Found');
	}
}

/**
* Remove avatar also for users not having the group as default
*/
function avatar_remove_db($avatar_name)
{
	global  $db, $prefix;
	$db->sql_query("UPDATE ".$prefix."_users SET user_avatar = '', user_avatar_type = '' WHERE user_avatar = '" . $db->sql_escape($avatar_name) . "\''");
}

?>