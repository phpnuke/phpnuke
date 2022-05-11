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

global $nuke_configs, $db, $block_global_contents,  $theme_setup, $cache;

$toptemp_default_configs = $theme_setup['toptemp_default_configs'];

$default_data = cache_toptemp_default_data();
$articles = $default_data['articles'];


$content .= "<div class='row mjr-p-5'>";
if(isset($articles[4]))
{
	$INume = 1;
	foreach($articles[4] as $cat_articles)
	{
		if($INume ==1)
		{
			$content .= "
			<div class='col-lg-12 animate-in' data-anim-type='fade-in-up' data-anim-delay='400'>
				<div class='mjr-card mjr-card--small shadow shadow-hover fly-sm overflow-hidden w-100 shadow-hover-sm2 shadow-sm2'>
					<div class='mjr-card__big-img'>
						<img src='".$cat_articles['post_image']."' class='mjr-card__img-thumbnail' alt='".$cat_articles['title']."'>
					</div>
					<div class='mjr-card__content'>
						<h4 class='mjr-card__content_header'>
							<a href='".$cat_articles['link']."' class='text-dark mjr-title font-weight-bold'>".$cat_articles['title']."</a>
						</h4>
						<p><i class='fa fa-calendar'></i> <span class='text-xs'>".$cat_articles['time']."</span></p>
						<p class='text-muted mb-0 d-none d-sm-block text-justify line-height-2'>".limit_words($cat_articles['hometext'],30)."</p>
					</div>
				</div>
			</div>";
		}else{
			$content .= "
			<div class='col-md-6 col-lg-12 col-xl-6 animate-in' data-anim-type='fade-in-up' data-anim-delay='400'>
				<div class='mjr-card mjr-card--small shadow shadow-hover fly-sm overflow-hidden w-100 shadow-hover-sm2 shadow-sm2'>
					<div class='mjr-card__img'>
						<img src='".$cat_articles['post_image']."' class='mjr-card__img-thumbnail' alt='".$cat_articles['title']."'>
					</div>
					<div class='mjr-card__content'>
						<h5 class='mjr-card__content_header'>
							<a href='".$cat_articles['link']."' class='text-dark mjr-title font-weight-bold'>".$cat_articles['title']."</a>
						</h5>
						<p class='mb-0'><i class='fa fa-calendar'></i> <span class='text-xs'>".$cat_articles['time']."</span></p>
					</div>
				</div>
			</div> ";
		}
		$INume++;
	}
}

$content .= "</div>";

?>