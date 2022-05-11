<?php

/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2006 by Francisco Burzi                                */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

/* Block to fit perfectly in the center of the site, remember that not all
blocks looks good on Center, just try and see yourself what fits your needs */

if ( !defined('BLOCK_FILE') ) {
	Header("Location: ../index.php");
	die();
}

global $nuke_configs, $db, $block_global_contents, $cache, $theme_setup;
$default_data = cache_toptemp_default_data();
$articles = $default_data['articles'];

$content .= "
<div class='rand_widget'>
	<ul class='side-newsfeed'>";
	if(isset($articles[2]))
	{
		foreach($articles[2] as $rand_articles)
		{
			$randpost_imge = (isset($rand_articles['post_image']) && $rand_articles['post_image'] != '') ? "".LinkToGT("index.php?timthumb=true&src=".LinkToGT($rand_articles['post_image'])."&h=100&w=100&q=100&a=c")."":"";
			$content .= "
			<li>
				<div class='media side-item fly-sm overflow-hidden w-100'>
					<div class='side-image'>
						<div class='widget-prop-image-wrap'>
							<a href='".$rand_articles['link']."' rel='bookmark'>
								<img width='80' height='80' src='".$rand_articles['post_image']."' alt='".$rand_articles['title']."'>
							</a>
						</div>
					</div>
					<div class='media-body side-item-text'>
						<div class='media-content font-weight-small'>
							<a href='".$rand_articles['link']."' rel='bookmark'> ".$rand_articles['title']." </a>
						</div>
						<div class='datetime text-xs text-body-default'>
							<i class='fa fa-calendar'></i> &nbsp; ".$rand_articles['time']."
						</div>
						<!-- .property-price-inner -->
					</div>
				</div>
			</li>";
		}
	}
	$content .= "
	</ul>
</div>";

?>