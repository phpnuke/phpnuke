<?php

if (!defined('NUKE_FILE')) {
	die ("You can't access this file directly...");
}

$contents .="<html xmlns=\"http://www.w3.org/1999/xhtml\" dir=\""._DIRECTION."\">
<head>
	<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\">
  	<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0;\">
 	<meta name=\"format-detection\" content=\"telephone=no\"/>
	<style>
		body { margin: 0; padding: 0; min-width: 100%; width: 100% !important; height: 100% !important;}
		body, table, td, div, p, a { -webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%; }
		table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse !important; border-spacing: 0; }
		img { border: 0; line-height: 100%; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }
		#outlook a { padding: 0; }
		.ReadMsgBody { width: 100%; } .ExternalClass { width: 100%; }
		.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div { line-height: 100%; }
		@media all and (max-width: 800px) {.floater { width: 320px; }}
		a, a:hover {color: #33b5e5;}
		.footer a, .footer a:hover {color: #999999;}
 	</style>
	<title>$subject</title>

</head>

<body topmargin=\"0\" rightmargin=\"0\" bottommargin=\"0\" leftmargin=\"0\" marginwidth=\"0\" marginheight=\"0\" width=\"100%\" style=\"border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; width: 100%; height: 100%; -webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%;background-color: #FFFFFF; color: #000000;direction:"._DIRECTION.";text-align:right;font-family: tahoma;\" bgcolor=\"#FFFFFF\" text=\"#000000\">

	<table width=\"100%\" align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; width: 100%;\" class=\"background\">
		<tr>
			<td align=\"center\" valign=\"top\" style=\"border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0;\"	bgcolor=\"#2081a5\">

				<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\" width=\"100%\" style=\"border-collapse: collapse; border-spacing: 0; padding: 0; width: inherit; max-width: 800px;\" class=\"wrapper\">
					<tr>
						<td align=\"center\" valign=\"top\" style=\"border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0;padding-top: 20px;\" class=\"hero\">
							<a target=\"_blank\" style=\"text-decoration: none;\" href=\"".$nuke_configs['nukeurl']."\">
								<img border=\"0\" vspace=\"0\" hspace=\"0\" src=\"".$nuke_configs['nukecdnurl']."images/logo.png\" alt=\"".$nuke_configs['sitename']."\" title=\"".$nuke_configs['sitename']."\" width=\"530\" style=\"width: 88.33%;max-width: 530px;color: #FFFFFF; font-size: 13px; margin: 0px 0px 15px; padding: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; border: none; display: block;\"/>
							</a>
						</td>
					</tr>
				</table>

			</td>
		</tr>
		<tr>
			<td align=\"center\" valign=\"top\" style=\"border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-top: 5px;\" bgcolor=\"#FFFFFF\">
				<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\" width=\"100%\" style=\"border-collapse: collapse; border-spacing: 0; padding: 0; width: inherit;
					max-width: 800px;\">
					<tr>
						<td valign=\"top\" style=\"border-collapse: collapse; border-spacing: 0; margin: 0; padding: 25px 5px 0px; padding-left: 10px; padding-right: 10px;\" class=\"floaters\">
							<p align=\"center\"><b>$subject</b></p>
							<p align=\"justify\" style=\"line-height: 25px;\">$message</p>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td align=\"center\" valign=\"top\" style=\"border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0;\" bgcolor=\"#F0F0F0\">
				<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\" width=\"100%\" style=\"border-collapse: collapse; border-spacing: 0; padding: 0; width: inherit;max-width: 800px;\" class=\"wrapper\">
					<tr>
						<td align=\"center\" valign=\"top\" style=\"border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;font-size: 13px; font-weight: 400; line-height: 150%;padding-top: 20px;padding-bottom: 20px;color: #999999;\" class=\"footer\">".$nuke_configs['footer_message']."<br/> "._HONESTLY_POWERED_BY."  <a href=\"http://phpnuke.ir/\" target=\"_blank\" style=\"text-decoration: none; color: #0F567A;font-size: 13px; font-weight: 400; line-height: 150%;\">"._PHPNUKE_MT_EDITION."</a>.<img width=\"1\" height=\"1\" border=\"0\" vspace=\"0\" hspace=\"0\" style=\"margin: 0; padding: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; border: none; display: block;\"src=\"".$nuke_configs['nukecdnurl']."images/tracker.png\" />
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

</body>
</html>";
?>