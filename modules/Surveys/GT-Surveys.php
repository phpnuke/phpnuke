<?php

$rewrite_rule["surveys"] = array(
	"surveys/([^/]+)/(result)?/?$" => 'index.php?modname=Surveys&op=poll_show&pollUrl=$1&mode=$2',
	"surveys/$" => 'index.php?modname=Surveys',
);

$friendly_links = array(
	"index.php\?modname=Surveys$" => "surveys/",
);

?>