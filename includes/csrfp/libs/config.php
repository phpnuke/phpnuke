<?php
/**
 * Configuration file for CSRF Protector z
 */
return array(
	"CSRFP_TOKEN" => "",
	"noJs" => true,
	"logDirectory" => INCLUDE_PATH."/csrfp/log/",
	"failedAuthAction" => array(
		"GET" => 2,
		"POST" => 2),
	"errorRedirectionPage" => LinkToGT("index.php?error=csrf"),
	"customErrorMessage" => "",
	"jsPath" => INCLUDE_PATH."/csrfp/js/csrfprotector.js",
	"jsUrl" => "",
	"tokenLength" => '',
	"disabledJavascriptMessage" => "This site attempts to protect users against <a href=\"https://www.owasp.org/index.php/Cross-Site_Request_Forgery_%28CSRF%29\">
	Cross-Site Request Forgeries </a> attacks. In order to do so, you must have JavaScript enabled in your web browser otherwise this site will fail to work correctly for you.
	 See details of your web browser for how to enable JavaScript.",
	 "verifyGetFor" => array()
);

?>