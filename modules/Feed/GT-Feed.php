<?php

$rewrite_rule["feed"] = array(
	"((.*)/)?feed/((rss1|rss2|atom))?/?$" => 'index.php?modname=Feed&module_link=$2&mode=$3',
	"sitemap.xml$" => 'index.php?modname=Feed&op=sitemap&is_index=1',
	"sitemap-misc.xml$" => 'index.php?modname=Feed&op=sitemap&mode=misc',
	"sitemap-([a-zA-Z0-9+_-]*)-([0-9]*)-([0-9]*).xml$" => 'index.php?modname=Feed&op=sitemap&mode=modules&module=$1&year=$2&month=$3',
	"sitemap-([a-zA-Z0-9+_-]*).xml$" => 'index.php?modname=Feed&op=sitemap&module=$1',
);

$friendly_links = array(
	"index.php\?modname=Feed(.+?)?$" => array("parse_rss_link"),
);

?>