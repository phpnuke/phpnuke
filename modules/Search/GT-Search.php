<?php

$rewrite_rule["search"] = array(
	"search/([^/]+)/([^/]+)/(cat-([^/]+)/)?(author-([^/]+)/)?(date-([0-9]{1,})/)?(page/([0-9]{1,}+))?/?$" => 'index.php?modname=Search&search_query=$1&search_module=$2&search_category=$4&search_author=$6&search_time=$8&page=$10',
	"search/$" => 'index.php?modname=Search',
);

$friendly_links = array(
	"index.php\?modname=Search([^/]+)?$" => array("parse_search_link"),
);

?>