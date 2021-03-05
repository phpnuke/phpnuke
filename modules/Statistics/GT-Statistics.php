<?php

$rewrite_rule["statistics"] = array(
	"statistics/all/?$" => 'index.php?modname=Statistics&op=advanced_statistics',
	"statistics/(.*)/?$" => array("parse_adv_statistics_link"),
	"statistics/$" => 'index.php?modname=Statistics',
);

$friendly_links = array(
	"index.php\?modname=Statistics([^/]+)?$" => array("parse_statistics_link"),
);


?>