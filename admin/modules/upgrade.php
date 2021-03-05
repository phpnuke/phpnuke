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

global $db, $admin_file;

$filename =  basename(__FILE__);
$filename = substr($filename,0,-4);

if (check_admin_permission($filename))
{
	function upgrade($start_upgrade)
	{
		global $db, $admin_file, $nuke_configs, $hooks;
		
		$hooks->add_filter("set_page_title", function(){return array("upgrade" => _CMS_UPGRADE);});
		$contents = '';
		$contents .= GraphicAdmin();
		
		$latest_version = "";
		$latest_version = get_latest_vershion_from_nuke();
			
		$Version_Num = $nuke_configs['Version_Num'];
		
		if($latest_version != '' && version_compare($Version_Num, "$latest_version", "<"))
		{
			$version_msg = _BAD_VERSION;
			$version_check = false;
			$version_bgcolor = "#BC2A4D";
		}
		else
		{
			$version_msg = _GOOD_VERSION;
			$version_check = true;
			$version_bgcolor = " #228822";
		}
		$contents .= OpenAdminTable();
		if(!$start_upgrade)
		{
			$contents .= "
			<div align=\"center\">
				<table class=\"product-table\" border=\"1\" width=\"600\">
					<tr>
						<td colspan=\"2\" align=\"center\" style=\"background-color:$version_bgcolor;color:#FFFFFF;\"><b>$version_msg<b></td>
					</tr>
					<tr>
						<td style=\"width:50%;line-height:25px;\">"._YOUR_NUKE_VERSION."</td>
						<td style=\"width:50%;line-height:25px;\">$Version_Num</td>
					</tr>
					<tr>
						<td style=\"width:50%;line-height:25px;\">"._LATEST_NUKE_VERSION_PUBLISHED."</td>
						<td style=\"width:50%;line-height:25px;\">".$latest_version."</td>
					</tr>";
					if(!$version_check)
					{
						// Request URL Redirect To Nuke Url
						//$Req_Protocol 	= strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === FALSE ? 'http' : 'https';
						$Req_Host     	= $_SERVER['HTTP_HOST'];
						$Req_Uri		= $_SERVER['REQUEST_URI'];
						//$Req_URIs		= $Req_Protocol . '://' . $Req_Host . $Req_Uri;
						$Req_URIs		= _SITE_PROROCOL . '://' . $Req_Host . $Req_Uri;
						
						$contents .= "<tr>
							<td colspan=\"2\" align=\"center\">"._UPGRADE_YOUR_NUKE_NOW."<br /><br />
							<form action=\"http://www.phpnuke.ir/upgrader.php\" method=\"post\" id=\"infoForm\">
								<table width=\"400\" align=\"center\" class=\"product-table id-form no-border\">
									<tr>
										<th align=\"right\">FTP Server</th>
										<td align=\"right\"><input type=\"text\" name=\"ftphost\" dir=\"ltr\" value=\"ftp.yoursite.com\" class=\"inp-form-ltr\" /></td>
									</tr>
									<tr>
										<th align=\"right\">FTP Username</th>
										<td align=\"right\"><input type=\"text\" name=\"ftpusername\" dir=\"ltr\" value=\"ftp user\" class=\"inp-form-ltr\" /></td>
									</tr>
									<tr>
										<th align=\"right\">FTP Password</th>
										<td align=\"right\"><input type=\"password\" dir=\"ltr\" name=\"ftppass\" class=\"inp-form-ltr\" /></td>
									</tr>
									<tr>
										<th align=\"right\">"._INSTALLED_NUKE_PATH."</th>
										<td align=\"right\"><input type=\"text\" dir=\"ltr\" name=\"nukedir\" class=\"inp-form-ltr\" value=\"public_hrml\" /></td>
									</tr>
									<tr>
										<td align=\"right\" colspan=\"2\"><input type=\"checkbox\" name=\"full_package\" class=\"styled\" value=\"1\" data-label=\"full package\" /></td>
									</tr>					  
									<tr>
										<td align=\"center\" colspan=\"2\"><input class=\"form-submit\" type=\"submit\" /></td>
									</tr>
								</table>
								<input type=\"hidden\" name=\"current_version\" value=\"$Version_Num\" />
								<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
								<input type=\"hidden\" name=\"redirect_url\" value=\"".$nuke_configs['nukeurl']."".$admin_file.".php?op=upgrade&start_upgrade=true&csrf_token="._PN_CSRF_TOKEN."\" />
							</form>
							</td>
						</tr>";
					}
				$contents .="</table>
			</div>
			";
		}
		else
		{
			csrfProtector::authorisePost(true);
			include("admin/modules/modules/pclzip.lib.php");
			$zip_dir = @basename("PHPNukeMTEdition-".$Version_Num."_to_".$latest_version.".zip");
			$archive = new PclZip("PHPNukeMTEdition-".$Version_Num."_to_".$latest_version.".zip");
			$archive->extract("./");
			@unlink("PHPNukeMTEdition-".$Version_Num."_to_".$latest_version.".zip");
			$contents .= "<META HTTP-EQUIV=\"refresh\" content=\"2;URL=install.php\">";
			$contents .="
			<div align=\"center\">
				<table id=\"product-table\" border=\"1\" width=\"600\">
					<tr>
						<td colspan=\"2\" align=\"center\">"._SUCCESSED_TRANSFER."</td>
					</tr>
				</table>
			</div>
			";
		}
		$contents .= CloseAdminTable();
		
		include("header.php");
		$html_output .= $contents;
		include("footer.php");
	}
	
	$start_upgrade = (isset($start_upgrade)) ? filter($start_upgrade, "nohtml"):'';

	switch ($op)
	{

		default:
		upgrade($start_upgrade);
		break;
	}

}
else
{
	header("location: ".$admin_file.".php");
}

?>