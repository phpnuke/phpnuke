<?php
/**
*
* This file is part of the PHP-NUKE Software package.
*
* @copyright (c) PHP-NUKE <https://www.phpnuke.ir>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

$rewrite_rule["credits"] = array(
	"credits/delete-filters(/(in-admin)+)?/?$" => 'index.php?modname=Credits&op=delete_all_filters&in_admin=$2',
	"credits/view/([0-9]{1,}+)/?$" => 'index.php?modname=Credits&op=credit_view&tid=$1',
	"credits/list(/([^/]+)/([^/]+))?(/page/([0-9]{1,}+)/)?/?$" => 'index.php?modname=Credits&op=credits_list&order_by=$2&sort=$3&page=$5',
	"credits/response/([0-9]{1,}+)/([^/]+)/([^/]+)/?$" => 'index.php?modname=Credits&op=credit_response&tid=$1&credit_gateway=$2&csrf_token=$3&only_get=true',
	"credits/$" => 'index.php?modname=Credits',
);

$friendly_links = array(
    'index.php\?modname=Credits&op=delete_all_filters(&in_admin=true)?$' => 'credits/delete-filters/$1/',
    'index.php\?modname=Credits&op=credit_view&tid=([0-9]{1,}+)$' => 'credits/view/$1/',
    'index.php\?modname=Credits&op=credits_list&order_by=([^/]+)&sort=([^/]+)&page=([0-9]{1,}+)$' => 'credits/list/$1/$2/page/$3/',
    'index.php\?modname=Credits&op=credits_list&order_by=([^/]+)&sort=([^/]+)$' => 'credits/list/$1/$2/',
    'index.php\?modname=Credits&op=credits_list&page=([0-9]{1,}+)$' => 'credits/list/page/$1/',
    'index.php\?modname=Credits&op=credits_list$' => 'credits/list/',
    'index.php\?modname=Credits&op=credit_response&tid=([0-9]{1,}+)&credit_gateway=([^/]+)&csrf_token=([^/]+)$' => 'credits/response/$1/$2/$3/',
	"index.php\?modname=Credits$" => 'credits/',
);

?>