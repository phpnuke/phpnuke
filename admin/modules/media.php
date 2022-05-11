<?PHP

/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2017 by MashhadTeam                                    */
/* http://www.phpnuke.ir                                                */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if (!defined('ADMIN_FILE'))
{
  die ("Access Denied");
}

global $db, $admin_file, $nuke_configs;

$filename =  basename(__FILE__);
$filename = substr($filename,0,-4);

if (check_admin_permission($filename))
{

	$upload_allowed_info = phpnuke_unserialize(stripslashes($nuke_configs['upload_allowed_info']));
	$default_folder = is_God() ? 'files':((isset($upload_allowed_info[$aid]['path']) && $upload_allowed_info[$aid]['path'] != '' && is_dir($upload_allowed_info[$aid]['path']) && file_exists($upload_allowed_info[$aid]['path'])) ? $upload_allowed_info[$aid]['path']:((is_dir("files/uploads/$aid") && file_exists("files/uploads/$aid")) ? "files/uploads/$aid":"files/uploads/"));

	function common()
	{
		global $db, $admin_file;
		$contents = '';
		$contents .= OpenAdminTable();
		$contents .="
		<br /><br /><p align=\"center\">
		[ <a href=\"".$admin_file.".php?op=media_browser\">"._FILES_MANAGER."</a> | <a href=\"".$admin_file.".php?op=media_upload&upload_dir=files/uploads&csrf_token="._PN_CSRF_TOKEN."\">"._UPLOAD_FILE."</a> ]
		</p>
		";
		$contents .= CloseAdminTable();
		return $contents;
	}

	function media_browser($ckeditor='')
	{
		global $db, $hooks, $admin_file, $aid, $nuke_configs, $default_folder, $CKEditorFuncNum;

		$hooks->add_filter("set_page_title", function(){return array("media_browser" => _MULTIMEDIA);});
		$contents = '';
		if($ckeditor != "")
			define('IS_POPUP', true);
		
		$CKEditorFuncNum = isset($CKEditorFuncNum) ? $CKEditorFuncNum:0;
		
		if($ckeditor == ""){
			$contents .= GraphicAdmin();
			$contents .= common();
			$contents .= OpenAdminTable();
			$contents .= "<br/><br />";
		}

		$contents .= "
		<div id=\"media_messages\"></div>
		<link rel=\"stylesheet\" href=\"admin/template/css/filetree.css\" type=\"text/css\" >
		<script>
			var media_languages = {
				view_more_info : '"._VIEW_MORE_INFO."',
				are_you_sure : '"._AREYOUSIRE."'
			}
		</script>
		<script src=\"admin/template/js/jquery/jquery.media.js\" type=\"text/javascript\"></script>
		<table width=\"100%\" class=\"product-table no-hover\">
			<tr>
				<th class=\"table-header-repeat line-left\" style=\"width:80%\">"._FILES_LIST."</th>
				<th class=\"table-header-repeat line-left\" style=\"width:20%\">"._SELECT_PATH."</th>
			</tr>
			<tr>
				<td style=\"vertical-align:top;\">
					<div id=\"medias\">
						<table width=\"100%\" class=\"product-table\">";
							if($ckeditor == ""){
							$contents .= "<thead>
							<tr>
								<th class=\"table-header-repeat line-left\" style=\"width:20px\"></th>
								<th class=\"table-header-repeat line-left\" style=\"width:80px\">"._PICTURE."</th>
								<th class=\"table-header-repeat line-left\" style=\"\">"._TITLE."</th>
								<th class=\"table-header-repeat line-left\" style=\"width:110px\">"._FILESIZE."</th>
								<th class=\"table-header-repeat line-left\" style=\"width:180px\">"._CREATIONDATE."</th>
								<th class=\"table-header-repeat no-padding\" style=\"width:110px\"><input data-label=\""._OPERATION."\" type=\"checkbox\" class=\"styled select-all\" data-element=\"#medias\"></th>
							</tr>
							</thead>";
							}
							$contents .= "
							<tbody>
							</tbody>
							<tfoot>
							</tfoot>
						</table>
					</div>
				</td>
				<td style=\"vertical-align:top;\"><a href=\"".$admin_file.".php?op=media_upload&upload_dir=$default_folder&ckeditor=$ckeditor".(($ckeditor != '') ? "&CKEditorFuncNum=$CKEditorFuncNum":"")."&csrf_token="._PN_CSRF_TOKEN."\" id=\"uploadto\">"._UPLOAD_IN."<br /><span id=\"upload_path\" dir=ltr>$default_folder</span></a><div id=\"media_menu\"></div></td>
			</tr>
		</table>
		<script>
		
			$(document).ready( function() {
				var medias_load = $(\"#media_menu\").medias({ 
					medias_id: 'medias',
					default_folder: '$default_folder',
					admin_file: '$admin_file',
					ckeditor: '$ckeditor',
				});
			});
		</script>";
		if($ckeditor != ''){
			$contents .= CloseAdminTable();
		}
		
		include("header.php");
		$html_output .= $contents;
		include("footer.php");
	}

	function media_upload($upload_dir, $ckeditor)
	{
		global $aid, $nuke_configs, $hooks;
		$contents = '';
		
		$hooks->add_filter("set_page_title", function(){return array("media_browser" => _MULTIMEDIA." - "._UPLOAD_FILE);});
		
		$upload_allowed_info = phpnuke_unserialize(stripslashes($nuke_configs['upload_allowed_info']));
		
		if(isset($_FILES['file_upload']))
		{
			csrfProtector::authorisePost(true);
			$upload_dir = (isset($upload_dir) && $upload_dir != "") ? $upload_dir:"files/";
			$upload_dir = trim($upload_dir,"/");
			$upload_dir = $upload_dir."/";
			
			if(!is_God() && !check_default_folder($upload_dir))
			{
				die('{"status":"error","message":"'.sprintf(_NOTALLOWED_UPLOADPATH, $upload_dir).'"}');
				exit;
			}
			
			if($_FILES['file_upload']['error'] == 0)
			{
				$extension = pathinfo($_FILES['file_upload']['name'], PATHINFO_EXTENSION);
				
				$metadatas = read_media_metadata($_FILES['file_upload']['tmp_name']);
				
				if(!in_array($metadatas['mime_type'], array("video/avi", "image/gif", "image/jpeg", "image/pjpeg", "video/mpeg", "audio/x-mpequrl", "video/quicktime", "video/x-mpeg", "video/x-mpeq2a", "audio/mpeg", "audio/mpeg3", "audio/x-mpeg-3", "image/png", "image/x-png", "audio/wav", "video/x-flv", "video/ogg", "video/mp4", "image/bmp", "audio/x-wav", "audio/x-realaudio", "audio/x-pn-realaudio-plugin", "audio/x-pn-realaudio", "audio/x-ogg", "audio/x-mpegurl", "audio/x-matroska", "audio/webm", "audio/ogg")))
				{
					die('{"status":"error","message":"'.sprintf(_NOTALLOWED_EXT, $extension).'"}');
					exit;
				}
				
				$i = "";
				
				$filename = (validateLatin($_FILES['file_upload']['name'])) ? $_FILES['file_upload']['name']:rawurlencode($_FILES['file_upload']['name']);
				$filename = substr($filename, 0, strrpos($filename, "."));
				
				$filename = sanitize($filename, array("_"));
				$file_name = $filename.".".$extension;
				
				while(file_exists($upload_dir.$filename.$i.".".$extension))
				{
					$i = intval($i)+1;
					$file_name = $filename.$i.".".$extension;
				}
				
				$hooks->do_action("media_upload_before", $upload_dir, $file_name, $ckeditor);
				
				if(move_uploaded_file($_FILES['file_upload']['tmp_name'], $upload_dir.$file_name))
				{
					add_log(sprintf(_UPLOAD_LOG, $file_name), 1);
					die('{"status":"success","message":"'._UPLOAD_SUCCESS.'","file_name":"'.$file_name.'","file_url":"'.$upload_dir.$file_name.'"}');
					exit;
				}
				else
				{
					die('{"status":"error","message":"'._UPLOAD_ERROR.'"}');
					exit;
				}
			}
			else
			{
				die('{"status":"error","message":"'.$_FILES['file_upload']['error'].'"}');
				exit;
			}
		}
	
		global $db, $admin_file;
		
		if($ckeditor != "")
			define('IS_POPUP', true);
		
		if($ckeditor == ""){
			$contents .= OpenAdminTable();
			$contents .= GraphicAdmin();
			$contents .= common();
			$contents .= "<br /><br />";
		}
		
		if(!is_God() && !check_default_folder($upload_dir))
		{
			$contents .= "<p align=\"center\">"._NOTALLOWED_PATH."</p>";
			if($ckeditor == ""){
				$contents .= CloseAdminTable();
			}
		}
		
		$contents .= "
		<link rel=\"stylesheet\" href=\"admin/template/css/filetree.css\" type=\"text/css\" >
		<link rel=\"stylesheet\" href=\"admin/template/css/uploader.css\" type=\"text/css\" >
		<script src=\"admin/template/js/jquery/jquery.ui.widget.js\" type=\"text/javascript\"></script>
		<script src=\"admin/template/js/jquery/jquery.fileupload.js\" type=\"text/javascript\"></script>
		<script src=\"admin/template/js/jquery/upload.js\" type=\"text/javascript\"></script>
		<form id=\"upload\" method=\"post\" action=\"".$admin_file.".php?op=media_upload&upload_dir=$upload_dir\" enctype=\"multipart/form-data\" accept-charset=\"utf-8\">
			<div id=\"drop\">
				<h1 dir=\"rtl\">"._DROG_FILES_HERE."<br />".sprintf(_UPLOAD_DIR_WILL_BE, $upload_dir)."</h1>
				<br />
				"._GOBACK." <a class=\"selectfile\">"._SELECT."</a>
				<input type=\"file\" name=\"file_upload\" multiple />
			</div>

			<ul>
				<!-- The file uploads will be shown here -->
			</ul>
			<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
		</form>";
		if($ckeditor == ""){
			$contents .= CloseAdminTable();
		}
		
		include("header.php");
		$html_output .= $contents;
		include("footer.php");
	}

	function delete_media($file_name)
	{
		global $nuke_configs, $hooks;
		
		$hooks->do_action("media_delete_before", $file_name);
		
		if(is_array($file_name))
		{
			$file_name = array_filter($file_name);
		}
		else
		{
			$file_name = array($file_name);
		}
		
		$i = 0;
		foreach($file_name as $filename)
		{
			$i++;
			$filename_arr = explode("/", $filename);
			array_pop($filename_arr);
			$directory = implode("/", $filename_arr);
			
			$response = array();
			
			if(!is_God() && !check_default_folder($directory))
			{
				$message[] = sprintf(_DELETE_NOTALLOWED, $directory);
				$status[] = 'error';
			}
			else
			{
				if(unlink($filename))
				{
					$message[] = sprintf(_DELETE_SUCCESS, $filename);
					$status[] = 'success';
					add_log(sprintf(_DELETE_LOG, $filename), 1);
				}
				else
				{
					$error = error_get_last();
					$message[] = ""._DELETE_ERROR." : ".$error['message'];
					$status[] = 'error';		
				}
			}
		}
		if($i == $nuke_configs['upload_pagesitems']){
			$response['emptypege'] = 1;
		}
		$response['this_dir'] = $directory;
		$response['message'] = $message;
		$response['status'] = $status;
		
		$hooks->do_action("media_delete_after", $file_name);
		
		die(json_encode($response));
	}
	
	function get_media_metadata($file_name)
	{
		global $db, $admin_file, $aid, $nuke_configs;
		$metadatas = read_media_metadata($file_name);
		$contents = '';
		$extension = pathinfo($file_name, PATHINFO_EXTENSION);
		
		$file_mime_type = (preg_match('!^image/!', $metadatas['mime_type'])) ? "image":((preg_match('!^audio/!', $metadatas['mime_type'])) ? "audio":((preg_match('!^video/!', $metadatas['mime_type'])) ? "video":"application"));
		$contents .= "
		<div class=\"media-preview\">
			<script src=\"includes/Ajax/jquery/player/mediaelement-and-player.min.js\"></script>
			<script src=\"includes/Ajax/jquery/player/mep-feature-playlist.js\"></script>
			<link rel=\"stylesheet\" href=\"includes/Ajax/jquery/player/mediaelementplayer.min.css\" />
			<link rel=\"stylesheet\" href=\"includes/Ajax/jquery/player/mep-feature-playlist.css\" />";
		$file_name_array = explode("/", $file_name);
		$file_name_clean = end($file_name_array);
		
		$type = check_filetype($file_name, get_mime_types());
		// m4v sometimes shows up as video/mpeg which collides with mp4
		if ('m4v' === $type['ext'])
			$type['type'] = 'video/m4v';
		$contents .= "
		<div class=\"media-preview-metadata\">
			<table width=\"100%\">
				<tr>
					<td style=\"width:100px;\">"._FILENAME."</td>
					<td dir=\"ltr\"><b>$file_name_clean</b></td>
				</tr>
				<tr>
					<td>"._ADDRESS."</td>
					<td><input readonly=\"readonly\" value=\"".$nuke_configs['nukeurl']."$file_name\" type=\"text\"></td>
				</tr>
				<tr>
					<td>"._FILEFORMAT."</td>
					<td><b>".((isset($metadatas['fileformat']) && $metadatas['fileformat'] != '') ? $metadatas['fileformat']:$extension)."</b></td>
				</tr>";
				if(!empty($metadatas['width']) && !empty($metadatas['height'])){
				$contents .= "<tr>
					<td>"._DIMENSIONS."</td>
					<td><b>".$metadatas['width']."px&nbsp;×&nbsp;".$metadatas['height']."px</b></td>
				</tr>";
				}
				if(!empty($metadatas['mime_type'])){
				$contents .= "<tr>
					<td>"._FILETYPE."</td>
					<td><b>".$metadatas['mime_type']."</b></td>
				</tr>";
				}
				if(!empty($metadatas['length_formatted'])){
				$contents .= "<tr>
					<td>"._LENGTH."</td>
					<td><b>".$metadatas['length_formatted']."</b></td>
				</tr>";
				}				
				if(!empty($metadatas['audio']['dataformat'])){
				$contents .= "<tr>
					<td>"._AUDIO_FORMAT."</td>
					<td><b>".$metadatas['audio']['dataformat']."</b></td>
				</tr>";
				}
				if(!empty($metadatas['audio']['codec'])){
				$contents .= "<tr>
					<td>"._CODEC."</td>
					<td><b>".$metadatas['audio']['codec']."</b></td>
				</tr>";
				}
				if(!empty($metadatas['audio']['sample_rate'])){
				$contents .= "<tr>
					<td>Sample Rate</td>
					<td><b>".$metadatas['audio']['sample_rate']."</b></td>
				</tr>";
				}
				if(!empty($metadatas['audio']['bitrate'])){
				$contents .= "<tr>
					<td>"._BEATRATE."</td>
					<td><b>".round($metadatas['audio']['bitrate']/1000 )."kb/s ".((!empty($metadatas['audio']['bitrate_mode']))? " ".strtoupper($metadatas['audio']['bitrate_mode']):"")."</b></td>
				</tr>";
				}
				if(!empty($metadatas['audio']['channels'])){
				$contents .= "<tr>
					<td>"._CHANNELS."</td>
					<td><b>".$metadatas['audio']['channels']."</b></td>
				</tr>";
				}
				if(!empty($metadatas['make'])){
				$contents .= "<tr>
					<td>"._DEAVICE_MAKER."</td>
					<td><b>".$metadatas['make']."</b></td>
				</tr>";
				}
				if(!empty($metadatas['model'])){
				$contents .= "<tr>
					<td>"._DEVICE_MODEL."</td>
					<td><b>".$metadatas['model']."</b></td>
				</tr>";
				}
				$contents .= "<tr>
					<td>"._UPLOADED_DATE."</td>
					<td><b>".nuketimes(filectime($file_name))." "._HOUR." ".date("H:i", filectime($file_name))."</b></td>
				</tr>
			</table>
		</div>
		<div class=\"media-preview-player\">";
		
		if($file_mime_type == "image")
		{
			$contents .= "<img src=\"".LinkToGT("index.php?timthumb=true&src=$file_name&w=390")."\" width=\"390\" style=\"border:1px solid #ccc;padding:2px;\" />";
		}
		elseif($file_mime_type == "audio" && $type['type'] != '')
		{
			$contents .= "
			<audio controls=\"controls\" type=\"".$type['type']."\">
				<source src=\"$file_name\" data-path=\"$file_name\" title=\"$file_name_clean\" />
			</audio>";
		}
		elseif($file_mime_type == "video" && $type['type'] != '')
		{
			$contents .= "<video width=\"390\" poster=\"".LinkToGT("index.php?timthumb=true&data_image=true&src=$file_name")."\" height=\"250\" controls=\"controls\" preload=\"none\">
				<source type=\"".$type['type']."\" src=\"$file_name\" title=\"$file_name_clean\" />
			</video>";
		}
		if($type['type'] == '' && ($file_mime_type == "video" || $file_mime_type == "audio"))
		{
			$contents .= "<p align=\"center\">"._CANNOTBEEXCUTED."</p>";
		}
		$contents .= "
			<script>
				$('audio,video').mediaelementplayer(";
				if($file_mime_type == "audio"){
				$contents .= "{
					default_poster: '$file_name',
					loop: true,
					shuffle: false,
					playlist: true,
					audioHeight: 30,
					playlistposition: 'bottom',
					features: ['playlistfeature', 'prevtrack', 'playpause', 'nexttrack', 'loop', 'shuffle', 'playlist', 'current', 'progress', 'duration', 'volume'],
				}";
				}
				$contents .= ");
			</script>
		</div>
		</div>";
		die($contents);
	}

	function check_default_folder($folder)
	{
		global $aid, $nuke_configs, $default_folder;
		
		$default_folder_len = strlen($default_folder);
		$folder = trim($folder, "/");
		
		if(substr($folder, 0, $default_folder_len) == $default_folder)
			return true;
		else
			return false;
	}
	
	function media_get_menu_files($dir)
	{
		global $aid, $nuke_configs, $default_folder;

		$files = array();	
		$list = "";
		
		if(!is_God() && !check_default_folder($dir))
			return false;

		if( file_exists( $dir)) {
			if( $dir[ strlen( $dir ) - 1 ] ==  '/' )
				$folder = $dir;
			else
				$folder = $dir . '/';
			
			$dirhandle = opendir( $dir );
			while(( $file = readdir( $dirhandle ) ) != false )
				$files[] = $file;
			closedir( $dirhandle );
		}
		if( count( $files ) > 2 ) { /* First 2 entries are . and ..  -skip them */
			natcasesort( $files );
			$list = '<ul class="filetree" style="display: none;">';
			// Group folders first
			foreach( $files as $file ) {
				if( file_exists( $folder . $file ) && $file != '.' && $file != '..' && is_dir( $folder . $file )) {
					$list .= '<li class="folder collapsed"><a href="#" rel="' . htmlentities( $folder . $file ) . '">' . htmlentities( $file ) . '</a></li>';
				}
			}
			$list .= '</ul>';
		}
		die($list);
	}
	
	function media_get_files($directory, $page, $ckeditor)
	{
		global $nuke_configs, $admin_file, $aid, $default_folder;
		
		if(!is_God() && !check_default_folder($directory))
			return false;

		$maindir = (isset($directory)) ? $directory: "$default_folder";
	
		$mydir = opendir($maindir) ;
		$limit = ($nuke_configs['upload_pagesitems'] <= 100) ? $nuke_configs['upload_pagesitems']:100;
		$limit = ($ckeditor == 'true') ? 50:$limit;
		$page = ((int)$page) ? $page : 1;
		$offset = ($page == 1) ? 0:(($page-1)*$limit);

		$files = array();
		$files_array = array();
		$all_files = array();
		$files_info = array();
		
		$exclude = array( ".", "..", "index.html",".htaccess");
		while($fn = readdir($mydir))
		{
			if (!in_array($fn, $exclude) && !is_dir($maindir."/".$fn)) 
			{
				$files[] = $fn;
			}
		}
		closedir($mydir);
		ksort($files);
		
		foreach ($files as $filename)
		{			
			$filemtime = filemtime($maindir."/".$filename);
			
			if ($filemtime !== false) {
				$files_array[$filemtime][] = $filename;
			}
		}
		krsort($files_array);
		
		foreach ($files_array as $key => $value)
		{
			$all_files = array_merge($all_files, $value);
		}
		
		$newICounter = (($offset + $limit) <= sizeof($all_files)) ? ($offset + $limit) : sizeof($all_files);

		for($i=$offset;$i<$newICounter;$i++)
		{
			$ext_array = explode(".", $all_files[$i]);
			$ext = end($ext_array);
			$metadatas = read_media_metadata($maindir."/".$all_files[$i]);
			$mime_type = explode("/", $metadatas['mime_type']);
			$mime_type = $mime_type[0];
			
			$files_info[] = array(
				'name' => $all_files[$i],
				'urlname' => $all_files[$i],
				'size' => formatBytes(filesize($maindir."/".$all_files[$i]),2 ,true),
				'time' => nuketimes(filemtime($maindir."/".$all_files[$i])),
				'type' => $ext,
				'mime_type' => $mime_type
			);
		}
		
		$pagination = admin_pagination(sizeof($all_files), $limit, $page, '');

		$pagination = str_replace('href=""', 'href="?page=1"', $pagination);
		
		preg_match_all('#href="\?page\=(.*)"#isU', $pagination, $matchs);

		foreach($matchs[0] as $key => $val)
		{
			$pagination = str_replace($val, 'class="media_browser" data-dir="'.$maindir.'" data-page="'.$matchs[1][$key].'"', $pagination);
		}
		
		$pagination = str_replace(
		array(
			'class="page-right" class="media_browser"',
			'class="page-far-right" class="media_browser"',
			'class="page-left" class="media_browser"',
			'class="page-far-left" class="media_browser"'
		),
		array(
			'class="page-right media_browser"',
			'class="page-far-right media_browser"',
			'class="page-left media_browser"',
			'class="page-far-left media_browser"'
		), $pagination);
		
		preg_match_all('#id="actions-box-slider">(.*)</div>#isU', $pagination, $matchs);
		$action_pagenum = str_replace("media_browser", "action-pagenum media_browser", $matchs[0][0]);
		$pagination = str_replace($matchs[0][0], $action_pagenum, $pagination);

		$plugin = "$(\".pagination\").medias_pagination();\n";
		$responsedata = array('files' => $files_info, 'pagination' => jquery_codes_load($plugin, true).$pagination, 'adminname' => (is_God() ? '':$aid));
		if(empty($files_info))
		{
			$responsedata = null;
		}
		
		die(json_encode($responsedata));
	}
	
	$page = (isset($page)) ? intval($page):0;
	$ckeditor = (isset($ckeditor)) ? filter($ckeditor, "nohtml"):'';
	$dir = (isset($dir)) ? filter($dir, "nohtml"):'';
	$directory = (isset($directory)) ? filter($directory, "nohtml"):'';
	$upload_dir = (isset($upload_dir)) ? filter($upload_dir, "nohtml"):'';
	$file_name = (isset($file_name)) ? $file_name:array();
	
	switch($op)
	{
		default:
		case "media_browser":
			media_browser($ckeditor);
		break;
		
		case "media_get_menu_files":
			media_get_menu_files($dir);
		break;
		
		case "media_get_files":
			media_get_files($directory, $page, $ckeditor);
		break;
		
		case "media_upload":
			media_upload($upload_dir, $ckeditor);
		break;
		
		case "delete_media":
			delete_media($file_name);
		break;
		
		case "get_media_metadata":
			get_media_metadata($file_name);
		break;
	}
}
else
{
	header("location: ".$admin_file.".php");
}
?>