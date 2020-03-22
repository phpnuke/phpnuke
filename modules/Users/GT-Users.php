<?php

$rewrite_rule["users"] = array(
	"users/(register|login|logout|reset\-password|check\-register\-fields|edit\-user|get\-user_avatar|userinfo|delete\-cookies)/$" => array("function" => 'return "index.php?modname=Users&op=".str_replace("-","_", $match[1])."";'),
	"users/(.+)/$" => 'index.php?modname=Users&op=userinfo&username=$1',
	"users/$" => 'index.php?modname=Users',
);

$friendly_links = array(
    'index.php\?modname=Users&op=(register|login|logout|reset_password|check_register_fields|edit_user|get_user_avatar|userinfo|delete_cookies)$' => array("function" => 'return "users/".str_replace("_","-", $match[1])."/";'),
    'index.php\?modname=Users&op=userinfo&username=([^/]+)$' => 'users/$1/',
	"index.php\?modname=Users$" => 'users/',
);

?>