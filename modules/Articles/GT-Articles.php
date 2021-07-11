<?php

$rewrite_rule["Articles"] = array(
	"(.+)$" => array("parse_post_links"),
);

$friendly_links = array(
	"index.php\?([^\"]+)$" => array("parse_post_gt_links"),
);

?>