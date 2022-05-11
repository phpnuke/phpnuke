<?php

/*********************************************************/
/*    Theme for PHPNUKE 8.4	                             */
/*    http://www.phpnuke.ir                              */
/*    Theme Name: Mashhadteam-Default                    */
/*    Author: MJr-{TOPTEMP}                              */
/*    Author URI:  http://toptemp.ir/                    */
/*    Version: 1.0                                       */
/*********************************************************/

if (!defined('NUKE_FILE')) {
	die ("You can't access this file directly...");
}

function OpenTable($title='', $panel_class="default")
{
	$contents = "
	<section class='widget bg-white'>";
		if($title != '')
		{
		$contents .= "
		<h5 class='font-weight-bold text-heading-default mjr-mb-20 widget-title'>
			<span class='heading-font animated'>$title</span>
		</h5>";
		}
	return $contents;
}

function CloseTable()
{
	$contents = '
	</section> ';
	return $contents;
}

function _theme_header()
{
	global $db, $nuke_configs, $theme_setup, $category, $modname, $op, $hooks;
	$local = (isset($nuke_configs['local']) && $nuke_configs['local'] != '') ? explode("-", $nuke_configs['local']):array("fa","ir");

	$contents = '';
	$contents .= "<!DOCTYPE html>\n";
	$contents .= "<html lang=\"".$locale[0]."\" dir=\""._DIRECTION."\">\n";
	$contents .= "	<head>";

	include(INCLUDE_PATH."/meta.php");

	$contents .= "\n\t</head>\n\t";

	return $contents;
}

function themeheader()
{
	global $userinfo, $nuke_configs, $search_query, $theme_setup, $users_system, $hooks;

	$toptemp_default_configs = $theme_setup['toptemp_default_configs'];
	$contents = _theme_header();
    $default_data = cache_toptemp_default_data();
	$articles = $default_data['articles'];
    $real_name = (isset($userinfo['name'])) ? mres($userinfo['name']):"";
	$dateTime = nuketimes();
	$now = date("H:i");
	$time = "<clock".((_DIRECTION == 'ltr') ? "dir=\"ltr\"":"")." class=\"nukeclock\"></clock>";
	$search_query = (isset($search_query) && $search_query != '') ? $search_query:"";

    $tp_logo = (!empty($toptemp_default_configs['tp_logo'])) ? $toptemp_default_configs['tp_logo']:"".LinkToGT("themes/".$nuke_configs['ThemeSel']."/assets/img/logo-n2.png")."";
    $nav_sticky = (isset($toptemp_default_configs['nav_sticky']) && $toptemp_default_configs['nav_sticky'] == 1) ? "sticky-top":"no-sticky";
	$header_box = (isset($toptemp_default_configs['header_box']) && $toptemp_default_configs['header_box'] == 1) ? "header-wide":"container";

	$instagram_link = (!empty($toptemp_default_configs['instagram'])) ? $toptemp_default_configs['instagram']:"";
	$whatsapp_link = (!empty($toptemp_default_configs['whatsapp'])) ? $toptemp_default_configs['whatsapp']:"";
	$telegram_link = (!empty($toptemp_default_configs['telegram'])) ? $toptemp_default_configs['telegram']:"";
	$facebook_link = (!empty($toptemp_default_configs['facebook'])) ? $toptemp_default_configs['facebook']:"";
				
	$body_class = array();
	$body_class[] = (_DIRECTION == 'ltr') ? "body-ltr":"rtl";
	
	$body_class = $hooks->apply_filters("body_classes", $body_class);
	
	if(file_exists("themes/".$nuke_configs['ThemeSel']."/theme_header.php"))
		include("themes/".$nuke_configs['ThemeSel']."/theme_header.php");
	else
	{
	$contents .= "
	<body class='$body_class mjr-p-30 mjr-padding-style bg-gradient-primary-1 mjr-is-sticky-footer custom_responsive'>
		<div class='mjr-page-loading-bg'></div>
		<div class='mjr-loading-circ-path'></div>
		<div id='page' class='site bg-gray-1 shadow-lg'>";
			if(isset($toptemp_default_configs['top_info']) && $toptemp_default_configs['top_info'] == 1)
			{
			$contents .= "
			<!--TOPBAR_DESKTOP-START-->
			<div class='mjr-topbar position-relative mjr-header-desktop mjr-topbar-normal bg-white  text-white sticky-top2 p-sticky' style='z-index:999;'>
				<div class='$header_box ".$toptemp_default_configs['header_box']."'>
					<div class='row d-flex align-items-center align-items-stretch'>
						<div class='col-12 col-lg-6 column mjr-header-min-height text-left py-md-0 d-flex align-items-center'>
							<div class='d-inline-block text-sm mjr-py-5 mjr-hover-item mb-0'>
								<div class='text-heading-default font-weight-bold btn btn-link p-0 mjr-header-text d-flex2 align-items-center2' href=''>
									<span><i class='font-weight-bold flaticon-calendar2 mjr-hover-right mr-2'></i> <span>$dateTime</span>
								</div>
							</div>
						</div>
						<div class='col-12 col-lg-6 column text-right mjr-header-min-height py-md-0 d-flex align-items-center justify-content-end'>
							<div class='mjr-px-5 d-inline-block2 d-inline-flex align-items-between mjr-social text-18'>";
							if($instagram_link)
								$contents .="
								<a class='d-flex align-items-center mjr-header-text text-heading-default' href='$instagram_link' title='instagram'><i class='fa fa-instagram px-2'></i></a>";
							if($whatsapp_link)
								$contents .="
								<a class='d-flex align-items-center mjr-header-text text-heading-default' href='$whatsapp_link' title='whatsapp'><i class='fa fa-whatsapp px-2'></i></a>";
							if($telegram_link)
								$contents .="
								<a class='d-flex align-items-center mjr-header-text text-heading-default' href='$telegram_link' title='telegram'><i class='fa fa-telegram px-2'></i></a> ";
							if($telegram_link)
								$contents .="
								<a class='d-flex align-items-center mjr-header-text text-heading-default' href='$facebook_link' title='facebook'><i class='fa fa-facebook px-2'></i></a> ";
							$contents .="
							</div>
						</div>
					</div>
				</div>
				<div class='bg-gray-2' style='width:100%;height:1px;'></div>
			</div>
			<!--TOPBAR_DESKTOP-END-->";
			}
			$contents .= "
			<!--HEADER_DESKTOP-START-->
			<header id='masthead' class='$nav_sticky mjr-header mjr-header-desktop d-inline-block mjr-header-normal mjr-scroll-shadow header-scroll mjr-header-container-area bg-white'>
				<div class='$header_box'>
					<nav class='navbar mjr-main-menu navbar-hover-drop navbar-expand-lg navbar-light d-inline-block2'>
						<div class='slide-in-container'>
							<div class='slide-in-container d-inline-block animate-in' data-anim-type='slide-in-up'>
								<a class='navbar-brand' href='".$nuke_configs['nukeurl']."' rel='home'>
									<img src='".$tp_logo."' alt='".$nuke_configs['sitename']."' style='height:35px;width:auto;'> 
								</a>
							</div>
						</div>
						<div class='d-inline-block mjr-px-5 mx-2'>
							<div class='bg-gray-2 mjr-header-divider mjr-sm' data-color='gray-2'></div>
						</div>
						<button class='navbar-toggler hamburger--spin hamburger d-flex d-lg-none' type='button' data-toggle='collapse' data-target='#navbarNav-206' aria-controls='navbarNav-206' aria-expanded='false' aria-label='Toggle navigation'> 
							<span class='hamburger-box'>
								<span class='hamburger-inner bg-heading-default'>
									<span class='hamburger-inner-before bg-heading-default'></span> 
									<span class='hamburger-inner-after bg-heading-default'></span> 
								</span>
							</span>
						</button>";
						$contents .= pn_nav_menu(array(
							'walker'			=> 'toptemp_default_nav_menus',
							'theme_location'	=> 'primary',
							'container_class'	=> 'collapse navbar-collapse justify-content-center',
							'container_id'		=> 'navbarNav',
							'menu_class'		=> 'navbar-nav nav-style-megamenu',
							'items_wrap'		=> '<ul id="%1$s" class="%2$s">%3$s</ul>' ,
							'container'			=> 'div',
							'list_class'		=> 'sub-menu',
							'link_before'		=> '<a href="%1$s" target="%2$s" class="%3$s font-weight-bold mjr-nav-link dropdown-toggle nav-link mjr-waiting fade-in animated" data-toggle="%4$s" data-anim-type="fade-in"><span>',
							'link_after'		=> '</span></a>',
						));
						$contents .= "
						<div class='d-inline-block mjr-px-5 mx-2'>
							<div class='bg-gray-2 mjr-header-divider is-main-divider  mjr-sm' data-color='gray-2' data-scroll-color='dark-opacity-1'>
						</div>
						<div class='bg-dark-opacity-1 mjr-header-divider is-scroll-divider mjr-sm'></div>
						</div>
						<a data-anim-type='fade-in-left' data-anim-delay='200' href='#' class='btn mjr-header-btn btn-link p-0 mjr-px-15 mjr-search-btn mjr-toggle-overlay m-0 scale2 animate-in d-inline-flex align-items-center text-heading-default'>
							<i class='fa fa-search text-18 mjr-header-text font-weight-bold'></i>
						</a>
					</nav>
				</div>
			</header>
			<!--HEADER_DESKTOP-END-->

			<!--TOPBAR_MOBILE-END-->
			<div class='mjr-topbar mjr-header-mobile mjr-topbar-normal bg-white text-white p-sticky py-22'>
				<div class='container'>
					<div class='row'>
						<div class='col-12 column d-flex justify-content-between py-md-02 mjr-py-10'>
							<div class='mjr-px-5 d-inline-block2 d-inline-flex align-items-between mjr-social text-18'> ";
							if($instagram_link)
								$contents .="
								<a class='d-flex align-items-center mjr-header-text text-body-default' href='$instagram_link' title='instagram'><i class='fa fa-instagram px-2'></i></a>";
							if($whatsapp_link)
								$contents .="
								<a class='d-flex align-items-center mjr-header-text text-body-default' href='$whatsapp_link' title='whatsapp'><i class='fa fa-whatsapp px-2'></i></a>";
							if($telegram_link)
								$contents .="
								<a class='d-flex align-items-center mjr-header-text text-body-default' href='$telegram_link' title='telegram'><i class='fa fa-telegram px-2'></i></a> ";
							$contents .=" 
							</div>
							<div class='d-inline-flex align-items-center d-inline-block2 text-sm mb-0'>
								<a data-anim-type='fade-in-left' data-anim-delay='200' href='#' class='btn mjr-header-btn btn-link p-0 mjr-px-15 mjr-search-btn mjr-toggle-overlay m-0 scale2 animate-in d-inline-flex align-items-center text-heading-default'>
									<i class='fa fa-search text-18 mjr-header-text font-weight-bold'></i>
								</a>
							</div>
						</div>
					</div>
				</div>
				<div class='bg-gray-2' style='width:100%;height:1px;'></div>
			</div>
			<!--TOPBAR_MOBILE-END-->

			<!--HEADER_MOBILE-START-->
			<header id='mobile_head' class='mjr-header mjr-header-mobile d-inline-block mjr-header-normal mjr-scroll-shadow sticky-top header-scroll2 bg-white'>
				<div class='container'>
					<nav class='navbar navbar-hover-drop navbar-expand-lg navbar-light d-inline-block2'>
						<div class='slide-in-container'>
							<div class='slide-in-container d-inline-block animate-in' data-anim-type='slide-in-up' style=''>
								<a class='navbar-brand' href='".$nuke_configs['nukeurl']."' rel='home'>
									<img src='".$tp_logo."' alt='".$nuke_configs['sitename']."' style='height:35px;width:auto;'> 
								</a>
							</div>
						</div>
						<button class='navbar-toggler hamburger--spin hamburger d-flex d-lg-none' type='button' data-toggle='collapse' data-target='#navbarNav-761' aria-controls='navbarNav-761' aria-expanded='false' aria-label='Toggle navigation'> 
							<span class='hamburger-box'>
								<span class='hamburger-inner bg-body-default'>
									<span class='hamburger-inner-before bg-body-default'></span> 
									<span class='hamburger-inner-after bg-body-default'></span> 
								</span>
							</span>
						</button> ";
						$contents .= pn_nav_menu(array(
							'walker'	=> 'toptemp_default_nav_menus',
							'theme_location'	=> 'primary',
							'container_class' => 'collapse navbar-collapse ',
							'container_id' => 'navbarNav-761',
							'menu_id' => 'menu-default-essentials-menu-1',
							'menu_class' => 'navbar-nav nav-style-megamenu',
							'list_class' => 'dropdown-menu',
							'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>' ,
							'container'       => 'div',
							'link_before'		=> '<a href="%1$s" target="%2$s" class="%3$s" data-toggle="%4$s"><span>',
							'link_after'		=> '</span></a>',
						));
						$contents .= "
					</nav>
				</div>
			</header>
			<!--HEADER_MOBILE-END-->

			<!--MAIN_CONTENT-END-->
			<div id='content' class='site-content bg-white mjr-br' >
				<div class='container'>
					<div class='row'>
						<div class='col-12'>
							<div id='primary' class='content-area'>
								<main id='main' class='site-main'>";
								if(defined("HOME_FILE") && $toptemp_default_configs['active_spost'] == 1)
								{
									$contents .="
									<!-- Slider-Start -->
									<div data-vc-full-width='true' data-vc-full-width-init='true' data-vc-stretch-content='true' class='section-featured section mt-2 pt-1 mjr-mr-5 mjr-ml-5 custom_row custom_row-fluid custom_row_visible custom_1592356541849 custom_row-has-fil'>
										<div class='container-fluid '>
											<div class='row'> ";
											if(isset($articles[5]))
											{
												$TOPNume = 1;
												foreach($articles[5] as $top_articles)
												{
													$user_avatars = $nuke_configs['nukecdnurl']."themes/".$nuke_configs['ThemeSel']."/assets/img/avatar.png";
													if($TOPNume == 1)
													{
														$contents .="
														<div class='col-lg-8 mjr-mb-15'>
															<div class='itemInner w-100 h-100 mjr-hover-item rounded-lg position-relative overflow-hidden'>
																<div class='bg bg-55 pb-1 ' style='display: block;'>
																	<img src='".$top_articles['post_image']."' class='card-img mjr-bg-image mjr-img-scale h-100' alt='".$top_articles['title']."'>
																</div>
																<div class='by-badge topLeft d-none d-lg-inline-block small d-flex align-items-center '>
																	<img width='40' height='40' src='$user_avatars' class='rounded-circle mr-1' alt=''>
																	<span class='author'>&nbsp;
																		<a class='text-white' href='/' rel='author'> ".$top_articles['aid']."</a>
																	</span>
																</div>
																<a href='".$top_articles['link']."' title='".$top_articles['title']."' class='coverLink'></a>
																<div class='title l-white'>
																	<h2 class='h3 text-white'>".$top_articles['title']."</h2>
																	<div class='d-none d-md-inline-block'>
																		<div class='small'>
																			<i class='fa fa-calendar'></i>  ".$top_articles['time']." &nbsp;-&nbsp;
																			<i class='fa fa-eye'></i>   ".$top_articles['counter']."  "._VISITS." &nbsp;-&nbsp;
																			<i class='fa fa-comment'></i>   ".$top_articles['comments']."  "._COMMENT."
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class='col-lg-4'> ";
														$TOPNume++;
													}
													else
													{
														$mjr_mb = ($TOPNume == 2) ? "mjr-mb-15":"";
														$bg_55 = ($TOPNume == 2) ? "bg-55":"bg-54";
														$contents .="
														<div class='$mjr_mb mjr-hover-item rounded-lg position-relative overflow-hidden'>
															<div class='itemInner zoom'>
																<div class='bg $bg_55 pb-1 ' style='display: block;'>
																	<img src='".$top_articles['post_image']."' class='card-img mjr-bg-image mjr-img-scale h-100' alt='".$top_articles['title']."'>
																</div>
																<div class='by-badge topLeft d-none d-lg-inline-block small d-flex align-items-center'>
																	<img width='40' height='40' src='$user_avatars' class='rounded-circle mr-1' alt=''>
																	<span class='author'>&nbsp;
																		<a class='text-white' href='/' rel='author'> ".$top_articles['aid']."</a>
																	</span>
																</div>
																<a href='".$top_articles['link']."' title='".$top_articles['title']."' class='coverLink'></a>
																<div class='title'>
																	<h2 class='h4 bold text-white mjr-my-10'>".$top_articles['title']."</h2>
																	<div class='d-none d-md-inline-block'>
																		<div class='small'>
																			<i class='fa fa-calendar'></i>  ".$top_articles['time']." &nbsp;-&nbsp;
																			<i class='fa fa-eye'></i>   ".$top_articles['counter']."  "._VISITS." &nbsp;-&nbsp;
																			<i class='fa fa-comment'></i>   ".$top_articles['comments']."  "._COMMENT."
																		</div>
																	</div>
																</div>
															</div>
														</div> ";
														$TOPNume++;
													}
												}
												$contents .="
												</div>
												<!-- .col-lg-3 -->
											</div>
											<!-- .row -->";
											}
									$contents .="
										</div>
									<!-- .container-fluid -->
									</div>
									<!-- Slider End -->";
								}
								$contents .="
								<div class='custom_row-full-width custom_clearfix'></div>
								<!-- Main Content Start-->";
								if(!empty($toptemp_default_configs['active_tpost']) && $toptemp_default_configs['active_tpost'] == 1)
								include("themes/".$nuke_configs['ThemeSel']."/template-parts/widget/feuture-posts.php");
	}
	return $contents;
}

function themefooter($custom_theme_setup = array(), $replace = false)
{
	global $db, $nuke_configs, $theme_setup, $cache, $hooks;

	$toptemp_default_configs = $theme_setup['toptemp_default_configs'];

	$default_data = cache_toptemp_default_data();
	$articles = $default_data['articles'];
	$tp_logo = (!empty($toptemp_default_configs['tp_logo'])) ? $toptemp_default_configs['tp_logo']:"".LinkToGT("themes/".$nuke_configs['ThemeSel']."/assets/img/logo-green.png")."";
										$contents = "
										<div class='custom_row-full-width custom_clearfix'></div>
									</div><!-- .entry-content -->
									<div class='clearfix'></div>
								</main><!-- #main -->
							</div><!-- #primary -->";
						include("themes/".$nuke_configs['ThemeSel']."/template-parts/footer/footer.php");
						$contents .= "
						</div>
						<a href='#' class='shadow shadow-hover rounded-circle fly bg-gray-2 back_to_top default' title='برو به بالا'>
							<i class='fa fa-angle-up' aria-hidden='true'></i>
						</a>
					</div>
				</div>
			</div>
			<!--MAIN_CONTENT-END-->
			<!-- Search -->
			<div class='mjr-overlay'>
				<div class=''>
					<div class='mjr-search '>
						<div class='container'>
							<div class='row d-flex justify-content-center'>
								<div class='col-12 col-md-12'>
									<div class='mjr-overlay-item mjr-overlay-item--style-6'>
										<a href='#' class='mjr-search-close'><i class='fa fa-times-circle-o'></i></a>
										<div class='pb-0'>
										<h1 class='search-title display-2 text-gradient-primary2 text-white font-weight-bold'></h1></div>
									</div>
									<div class='slide-in-container pb-2 mjr-overlay-item mjr-overlay-item--style-6'>
									<p class='text-gray-3s text-20 mb-2 secondary-font search-note'>جستجو در سایت</p>
										</div>
									<div class='search-bar mjr-overlay-item mjr-overlay-item--style-6'>
										<div class='search-content'>
											<form class='mjr-search-form' method='post' role='search' action='".LinkToGT("index.php?modname=Search")."'>
												<div class='media mjr-ajax-search-container'>
													<div class='media-body'>
														<input type='text' value='$search_query' name='search_query' class='mjr-search-input' placeholder='"._SEARCH." ...'>
													</div>
													<button class='mjr-search-submit align-self-center' aria-label='search' type='submit'>
													<i class=' mjricon-search'></i></button>
												</div>
													<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" />
											</form>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<svg class='shape-overlays' viewBox='0 0 100 100' preserveAspectRatio='none'>
				<defs>
					<linearGradient id='search-overlay-color-1' x1='0%' y1='0%' x2='100%' y2='0%'>
						<stop offset='0%'   stop-color='#222222'><stop offset='100%'   stop-color='#000000'>
					</linearGradient>
				</defs>
				<path class='shape-overlays__path' fill='url(#search-overlay-color-1)'></path>

			</svg>
			<!-- /.Search -->
		</div>
	</div>";

	$contents .= _theme_footer($custom_theme_setup, $replace);

	return $contents;
}

function _theme_footer($custom_theme_setup = array(), $replace = false)
{
	global $db, $nuke_configs, $theme_setup,$nuke_categories_cacheData,$users_system,$search_query ;
    $statistics_contents = $users_system->user_statistics();
    $search_query = (isset($search_query) && $search_query != '') ? $search_query:"";
	$toptemp_default_configs = $theme_setup['toptemp_default_configs'];

	$defer_js_contents = '';
	
	foreach($theme_setup as $skey => $sval)
	{
		if(!in_array($skey, array('defer_js')))
			continue;
		$theme_setup[$skey] = array_unique($sval);
	}
	
	if(isset($theme_setup['defer_js']) && !empty($theme_setup['defer_js']))		
		foreach($theme_setup['defer_js'] as $defer_js)
			if($defer_js != '')
				$defer_js_contents .= "\n\t\t".$defer_js;
				$contents = "
		$defer_js_contents
	</body>
</html>";
	return $contents;
}

/*function website_index($meta_tags)
{
	global $db, $nuke_configs;

	if(file_exists("themes/".$nuke_configs['ThemeSel']."/website_index.php"))
		include("themes/".$nuke_configs['ThemeSel']."/website_index.php");
	else
	{
		$contents = _theme_header();
		$contents .="<body class=\"has-navbar-fixed-top page-index\">
		<div id=\"navigation\" class=\"wrapper\">
			<div class=\"navbar navbar-fixed-top\" id=\"top\">
				<div class=\"navbar-inner\">
					<div class=\"inner\">
						<div class=\"container\">
							<div class=\"navbar-header\">
								<button type=\"button\" class=\"navbar-toggle btn btn-navbar\" data-toggle=\"collapse\" data-target=\".navbar-collapse\"> <span class=\"sr-only\">
									Toggle navigation
								</span>
								<span class=\"icon-bar\"></span>
								<span class=\"icon-bar\"></span>
								<span class=\"icon-bar\"></span>
								</button>
								<a class=\"navbar-brand text-left\" href=\"".LinkToGT("index.php")."\" title=\"صفحه اصلی\">
									<h1 class=\"text-enleft\">
										PHPNuke
									</h1>
									<span class=\"text-enleft\">Bootstrap theme by GreenSkin.ir</span>
								</a>
							</div>
							</a>
							<div class=\"collapse navbar-collapse\">
								<ul class=\"nav navbar-right\" id=\"main-menu\">
									<li>
										<a href=\"".LinkToGT("index.php")."\">صفحه نخست</a>
									</li>
									<li>
										<a href=\"".LinkToGT("index.php?modname=Search")."\">جستجو</a>
									</li>
									<li>
										<a href=\"".LinkToGT("index.php?modname=Feedback")."\">تماس با ما</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>";
		$contents .= _theme_footer();
	}

	return $contents;
}*/

function blocks_box_theme($title, $content, $themeblock)
{
	global $db, $prefix, $align, $nuke_configs;
	$contents = '';
	if($themeblock == "" || ($themeblock != '' && !file_exists("themes/".$nuke_configs['ThemeSel']."/blocks/themes/$themeblock")))
	{
		$contents = "
		<section class='widget bg-white'>
			<h5 class='font-weight-bold text-heading-default mjr-mb-20 widget-title'>
				<span class='heading-font animated'>$title</span>
			</h5>
			$content
		</section>";
	}
	else
		include("themes/".$nuke_configs['ThemeSel']."/blocks/themes/$themeblock");

	return $contents;
}

function article_index($article_info)
{
	global $nuke_configs, $admin_file, $theme_setup;

	$toptemp_default_configs = $theme_setup['toptemp_default_configs'];

	$article_info['comments'] = ($article_info['comments']==0) ? "0":$article_info['comments'];

	$cats_name = array();
	if(isset($article_info['cats_data']) && !empty($article_info['cats_data']))
	{
		foreach($article_info['cats_data'] as $cat => $cat_data)
		{
			if($cat_data['catname'] == 'uncategorized') continue;
			$cats_name[] = "<a href=\"".$cat_data['catlink']."\" target=\"_self\"><span class=\"d-inline-block mr-1 \"><span class=\"badge font-weight-bold bg-primary-light  \" style=\"font-size:12px; padding:5px 10px;margin-right:3px;line-height:12px;\"><span class=\"text-primary\" style=\"\">".$cat_data['catname']."</span></span></span></a>";
		}
	}

	$thumb_post_imge = (isset($article_info['post_image']) && $article_info['post_image'] != '') ? "".LinkToGT("index.php?timthumb=true&src=".LinkToGT($article_info['post_image'])."&h=155&w=230&q=90&a=c")."":"";
    $full_post_imges = (isset($article_info['post_image']) && $article_info['post_image'] != '') ? "".LinkToGT($article_info['post_image'])."":"";


    $post_imge = ($toptemp_default_configs['post_photo_style'] == 1) ? $thumb_post_imge:$full_post_imges;
    $category_template = (!empty($toptemp_default_configs['category_template'])) ? $toptemp_default_configs['category_template']:"1";
	$user_avatar = $nuke_configs['nukecdnurl']."themes/".$nuke_configs['ThemeSel']."/assets/img/avatar.png";
    $content ="";

	include("themes/".$nuke_configs['ThemeSel']."/template-parts/blog/category/content-style-$category_template.php");
	return $content;
}

function article_more($article_info)
{
	global $nuke_configs, $admin_file, $theme_setup;

	$toptemp_default_configs = $theme_setup['toptemp_default_configs'];

	$module = (isset($article_info['post_type']) && $article_info['post_type'] != '') ? $article_info['post_type']:"Articles";
	$tags = str_replace(" ","-",$article_info['tags']);
	$tags = explode(",",$tags);
	$tags = array_filter($tags);
	$htmltags = '';
	foreach($tags as $tag)
		$htmltags .= "<a href=\"".LinkToGT("index.php?modname=$module&tags=$tag")."\" class=\"btn btn-sm btn-white shadow-sm shadow-hover-sm text-xs2 text-dark-opacity-4 fly-sm mjr-mr-10 mjr-mb-10 font-weight-bold\">".str_replace("_"," ",$tag)."</a> ";

	$article_info['comments'] = ($article_info['comments']==0) ? "0":$article_info['comments'];

	$cats_name = array();
	if(isset($article_info['cats_data']) && !empty($article_info['cats_data']))
	{
		foreach($article_info['cats_data'] as $cat => $cat_data)
		{
			if($cat_data['catname'] == 'uncategorized') continue;
			$cats_name[] = "<a href=\"".$cat_data['catlink']."\" target=\"_self\"><span class=\"d-inline-block mr-1 \"><span class=\"badge font-weight-bold bg-primary-light  \" style=\"font-size:12px; padding:5px 10px;margin-right:3px;line-height:12px;\"><span class=\"text-primary\" style=\"\">".$cat_data['catname']."</span></span></span></a>";
		}
	}

	$post_imge = (isset($article_info['post_image']) && $article_info['post_image'] != '') ? "<img class=\"img-thumbnail\" style=\"float:right;margin:0 7px 7px 7px;\" src=\"".LinkToGT("index.php?timthumb=true&src=".LinkToGT($article_info['post_image'])."&h=155&w=230&q=90&a=c")."\" width=\"230\" height=\"155\" />":"";

	$post_files = '';
	if(!empty($article_info['download']))
	{
		$post_files .=""._ARTICLE_FILES."<br /><ul class=\"post-files\">";
		foreach($article_info['download'] as $file)
		{
			$post_files .="<li class=\"post-file-item\"><i class=\"fa fa-file\"></i> <a href=\"".LinkToGT($file[1])."\" target=\"_blank\">".$file[0]."</a>";
			if($file[2] != '' && $file[2] != 0)
				$post_files .=" <span class=\"post-file-size\">".formatBytes($file[2], 2 ,true)."</span>";

			if($file[3] != '')
				$post_files .=" <span class=\"post-file-desc\">(".$file[3].")</span></li>";
		}
		$post_files .="</ul>";
	}
    $post_imge = (isset($article_info['post_image']) && $article_info['post_image'] != '') ? "".LinkToGT($article_info['post_image'])."":"";
    $user_avatar = $nuke_configs['nukecdnurl']."themes/".$nuke_configs['ThemeSel']."/assets/img/User_Avatar.png";
	$content = "

	<article id='post-".$article_info['sid']."' class=' post-sidebar-right pdrow post type-post card shadow-sm d-inline-block bg-white mjr-p-10 text-justify mjr-mb-40'>
		<div class='post-thumbnail'>
			<div class='card rounded-xl mjr-hover-item shadow-lg  overflow-hidden text-white2 h-100'>
				<img src='$post_imge' class='img-fluid mjr-img-scale ' alt='".$article_info['title']."'>
			</div>
		</div>
		<!-- .post-thumbnail -->
		<header class='entry-header'>
			<h1 class='mjr-post-title mjr-mt-10 mjr-sliding-headline font-weight-bold mjr-ready text-center'>
				".$article_info['title']."
			</h1>
			<div class='entry-meta mjr-post-meta-inner d-flex align-items-center mjr-my-10'>
				<div class='mjr-post-meta-author text-heading-default font-weight-bold'>
					<a href='".$article_info['aid_url']."' class='text-heading-default font-weight-bold'>
						<img class='mjr_blog_md_avatar mjr-mr-10 shadow' src='$user_avatar' alt='".$article_info['aid']."'>
						<span class='text-sm'> ".$article_info['aid']."</span>
					</a>
				</div>
				<div class='flex-fill text-right mr-2 text-center'>
					<div class='mjr-post-meta-badges'>";
						if(!empty($cats_name))
						$content .="".implode(" ", $cats_name)."";
						$content .="
					</div>
				</div>
				<div class='mjr-post-meta-date flex-fill2 text-right text-body-default'>
					 ".$article_info['rating_box']."
				</div>
			</div>
			<!-- .entry-meta -->
		</header>
		<!-- .entry-header -->
		<div class='entry-content'>
			".$article_info['hometext']."
			".$article_info['bodytext']."
			".$post_files."

			<div class='mjr-p-20'>
				$htmltags
			</div>
			<div class='entry-meta mjr-post-meta-inner d-flex align-items-center'>
				<div class='flex-fill text-right mr-2'>
					<div class='mjr-post-meta-badges'>
						<span class='pr-1'>
							<i class='fa fa-eye'></i>
							<span class='text-xs font-weight-bold mjr-pl-20'>&nbsp;".$article_info['counter']." بازدید </span>
						</span>
						<span class='pr-1'>
							<i class='fa fa-comment'></i>
							<span class='text-xs font-weight-bold'>&nbsp;".$article_info['comments']." دیدگاه</span>
						</span>
					</div>
				</div>
				<div class='mjr-post-meta-date flex-fill2 text-right text-body-default text-sm'>
					<span class='pr-1'>
						<i class='fa fa-calendar'></i>
						<span class='text-xs font-weight-bold mjr-pl-20'>
							&nbsp; ".$article_info['datetime']."
						</span>
					</span>
				</div>
			</div>
		</div>
		<!-- .entry-content -->
	</article>";
	
	$prev_article_img	= LinkToGT(get_article_image($article_info['psid'])) ;
	$next_article_img	=  LinkToGT(get_article_image($article_info['nsid']));
	$content .="
	<div class='col-12 mjr-mb-40'>
		<section class='post_navigation'>
			<div class='row'>
				<h3 class='screen-reader-text'></h3>";
				if($article_info['prev_article_link'])
				{
				$content .="
				<div class='post_navigation_item post_navigation_prev pull-left col-md-6'>
					<a class='post_navigation_arrow' href='".$article_info['prev_article_link']."' title='".$article_info['prev_article_title']."' rel='prev'>
						<i class='fa fa-angle-double-right'></i>
					</a>
					<div class='post_thumbnail_wrapper'>
						<a href='".$article_info['prev_article_link']."' title='".$article_info['prev_article_title']."' rel='prev'>
							<img width='60' height='60' src='$prev_article_img' class='img-responsive' alt='".$article_info['prev_article_title']."'>
						</a>
					</div>
					<div class='post_info_wrapper'>
						<span class='post_navigation_title title'>"._PRV_ARTICLES."</span>
						<h4 class='title post_title font-weight-bold line-height-1 truncate-250'>
							<a href='".$article_info['prev_article_link']."'>".$article_info['prev_article_title']."</a>
						</h4>
						<p></p>
					</div>
				</div> ";
				}
				if($article_info['next_article_link'])
				{
				$content .="
				<div class='post_navigation_item post_navigation_next col-md-6'>
					<a class='post_navigation_arrow' href='".$article_info['next_article_link']."' title='".$article_info['next_article_title']."' rel='next'>
						<i class='fa fa-angle-double-left'></i>
					</a>
					<div class='post_thumbnail_wrapper'>
						<a href='".$article_info['next_article_link']."' title='".$article_info['next_article_title']."' rel='next'>
							<img width='60' height='60' src='$next_article_img' class='img-responsive' alt='".$article_info['next_article_title']."'>
						</a>
					</div>
					<div class='post_info_wrapper'>
						<span class='post_navigation_title title'>"._NEXT_ARTICLES."</span>
						<h4 class='title post_title font-weight-bold line-height-1 truncate-250'>
							<a href='".$article_info['next_article_link']."'>".$article_info['next_article_title']."</a>
						</h4>
						<p></p>
					</div>
				</div> ";
				}
				$content .="
			</div>
		</section>
	</div>";
	return $content;
}

function comments_theme($el, $post_comment, $depth, $main_parent)
{
	global $admin_file, $nuke_configs;

	$options = array();
	if(is_admin())
	{
		$options[] = "<span class=\"plus-comment-ip\">IP : ".$post_comment['ip']."</span> ";
		$options[] = "<a href=\"".$nuke_configs['nukeurl'].$admin_file.".php?op=comments_delete&cid=".$post_comment['cid']."&csrf_token="._PN_CSRF_TOKEN."\" onclick=\"return confirm('"._SURETODELCOMMENTS."')\"><i class=\"glyphicon glyphicon-remove\" title=\""._DELETE."\"></i></a> ";
		$options[] = "<a href=\"".$nuke_configs['nukeurl'].$admin_file.".php?op=comments_edit&cid=".$post_comment['cid']."&show_header=1\"><i class=\"glyphicon glyphicon-edit\" title=\""._EDIT."\"></i></a> ";
	}
	if (($el->comments_configs['anonymous'] == 1 OR is_admin() OR is_user()) && $depth <= $el->comments_configs['depth'])
	{
		$depth++;
		$options[] = "<a href=\"".$el->Req_URIs_2."\" class=\"reply-comment\" data-cid=\"".$post_comment['cid']."\" data-main-parent=\"$main_parent\" data-name=\"".$post_comment['username']."\" data-message=\"".mb_word_wrap(strip_tags(stripslashes($post_comment['comment'])), 100)."\" data-replylang=\""._IN_REPLY."\" title=\""._REPLY."\"><i class=\"glyphicon glyphicon-share-alt\"></i></a> ";
	}
	if ($el->comments_configs['allow_reporting'] == 1)
	{
		$options[] = "<a href=\"".LinkToGT("index.php?sop=report&module_name=comments&post_id=".$post_comment['cid']."&post_title="._COMMENTS."")."\" data-mode=\"inline\" data-toggle=\"modal\" data-target=\"#sitemodal\" title=\""._POST_REPORT."\"><i class=\"glyphicon glyphicon-warning-sign\"></i></a> ";
	}
	if($el->comments_configs['allow_rating'] == 1)
		$options[] = "<a>".$post_comment['rating_box']."</a> ";

	$contents = "";
	$contents .= "
	<div class=\"clear\"></div>
	<!-- Comment -->
	<a name=\"comment-".$post_comment['cid']."\"></a>
	<div class=\"col-sm-12".(($depth > 1) ? " comments-reply":" well")."".(($post_comment['reported']) ? " reported":"")."\">
		<div class=\"panel panel-default text-right GSComment1\">
			<div class=\"panel-body\">
				<div class=\"row\">
				<div class=\"col-sm-4\">
				<span class=\"user\"><i class=\"glyphicon glyphicon-user\"></i> <a href=\"".$post_comment['url']."\" target=\"_blank\">".$post_comment['name']."</a></span></div>";
				if(is_admin())
					$contents .= "<div class=\"col-sm-5\"><span class=\"email\"><i class=\"glyphicon glyphicon-send\"></i> ".$post_comment['email']."</span> </div>";
				$contents .= "<div class=\"col-sm-3\"><span class=\"date\"><i class=\"glyphicon glyphicon-calendar\"></i> ".$post_comment['date']."</span></div>
				</div>
				<hr>
				<p class=\"comment-body\">
					".(($post_comment['deact'] != '') ? $post_comment['deact']."<br />":"")."
					".$post_comment['comment']."
					<div class=\"comment-tools\">";
					if(!empty($options))
						$contents .= "".implode("\n", $options)."";
					$contents .= "
					</div>
					<div class=\"clear\"></div>
				</p>";
				if($post_comment['replies'] > 0)
					$contents .= "<hr />".$el->display_comments_childs($post_comment['cid'], $depth, $main_parent);
				$contents .= "
			</div>
		</div>
	</div>";
	return $contents;
}

function mail_theme($subject, $logoimage, $message)
{
	global $nuke_configs;
	$message = str_replace("\r\n","<br />",$message);
	if(file_exists("themes/".$nuke_configs['ThemeSel']."/mail_theme.php"))
		$message = $contents;
	else
	{
		$message = "
		<html dir='"._DIRECTION."'>\n
			<head>\n
				<meta http-equiv='content-type' content='text/html; charset=utf-8'>\n
				<base target=_blank>\n
			</head>\n
			<body>\n
			<div dir='"._DIRECTION."'>\n
					$message
			</div>\n
		</body>\n
		</html>\n";
	}
	return $message;
}

function print_theme($pagetitle, $print_data)
{
	global $nuke_configs;
	$css	= array('includes/Ajax/jquery/bootstrap/css/bootstrap.min.css','includes/Ajax/jquery/bootstrap/css/bootstrap-rtl.css','themes/'.$nuke_configs['ThemeSel'].'/style/print.css');
	$js		= array('includes/Ajax/jquery/jquery.min.js', 'includes/Ajax/jquery/bootstrap/js/bootstrap.min.js');

	$pagetitle	= $nuke_configs['sitename']." - ".((isset($pagetitle)) ? $pagetitle:'');
	$favicon	= ((file_exists("themes/".$nuke_configs['ThemeSel']."/images/favicon.ico")) ? "<link rel=\"shortcut icon\" href=\"".$nuke_configs['nukeurl']."themes/".$nuke_configs['ThemeSel']."/images/favicon.ico\" type=\"image/x-icon\">":"");

	foreach($css as $css_link)
		$html_css[] = "<link rel=\"stylesheet\" href=\"".LinkToGT($css_link)."\">";
	$html_css	= implode("\n\t\t", $html_css);

	foreach($js as $js_link)
		$html_js[] = "<script type=\"text/javascript\" src=\"".LinkToGT($js_link)."\"></script>";
	$html_js	= implode("\n\t\t", $html_js);

	header("X-Robots-Tag: noindex, nofollow", true);

	$html_content = "<style>.article-header{width:100%;float:right;}.article-header span {width: calc(100% - 270px);float: right;}.article-header span:nth-child(2), .article-header span:nth-child(4) {color: #a7a9ac;}.article-header span:nth-child(3) {font-size: 20px;font-weight: bold;line-height: 35px;color: #333;padding: 9px 0 17px;}.article-header img{float:right;width:250px;margin-left:20px;}.p-nt {margin: 17px 0;float:right;width:100%;padding-top: 17px;border-top:1px dotted #ccc;}.p-nt p {margin-bottom: 19px;} img{max-width:99%;}</style>
	<div class=\"article-header\">
		".(($print_data['post_image'] != "" && $print_data['article_image_width'] != 0 && $print_data['article_image_height'] != 0) ? "<img src=\"".$print_data['post_image']."\" width=\"".$print_data['article_image_width']."\" height=\"".$print_data['article_image_height']."\" alt=\"".$print_data['title']."\" title=\"".$print_data['title']."\" />":"<span></span>")."
		".(($print_data['title_lead'] != '') ? "<span style=\"color:#ccc;\">".$print_data['title_lead']."</span>":"")."
		<span>".$print_data['title']."</span>
		<span>".$print_data['hometext']."</span>
	</div>
	<div class=\"p-nt\"><p class=\"rtejustify\">".$print_data['hometext']."<br />".$print_data['bodytext']."</div>";
	echo "<!DOCTYPE html>
	<html>
	<head>
		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
		<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
		<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
		<title>$pagetitle</title>
		$favicon
		$html_css
		$html_js
	</head>
	<body onload=\"window.print();\">
		<div class=\"container\">
			<div class=\"row\" id=\"header\">
			<div class=\"col-sm-7\" id=\"hedaer-logo\"><img src=\"".$nuke_configs['nukecdnurl']."images/logo-240x60.gif\" border=\"0\" width=\"240\" height=\"60\" alt=\"".$nuke_configs['sitename']."\" title=\"".$nuke_configs['sitename']."\"></div>
			<div class=\"col-sm-5 text-"._TEXTALIGN1."\" id=\"hedaer-desc\">تاریخ : <b>$datetime</b><br />"._CATEGORY." : <b>$category</b></div>
			</div>
			<div class=\"row\" dir=\""._DIRECTION."\">
				$html_content
			</div>
			<div class=\"row\" id=\"footer\">
				<div>"._COMESFROM." :<a href=\"".$nuke_configs['nukeurl']."\">".$nuke_configs['sitename']."</a>
				<br />"._POSTURL." <a dir=ltr href=\"$page_link\"><span style=\"direction:ltr;text-align:left;\">".LinkToGT($page_link)."</span></a></div>
			</div>
		</div>
	</body>
	</html>";
	die();
}

function die_error($error_message)
{
	global $nuke_configs, $hooks;	
	
	$hooks->do_action('die_error_action', $error_message);

	$error = false;

	switch($error_message)
	{
		case"404":
			parse_old_links();
			header('HTTP/1.0 404 Not Found', true, 404);
			$error = true;
			$error_message = _PAGE_NOT_FOUND;
			if(file_exists("themes/".$nuke_configs['ThemeSel']."/404.php"))
			{
				include("themes/".$nuke_configs['ThemeSel']."/404.php");
				die();
			}
		break;

		case"403":
			header('HTTP/1.0 403 Forbidden', true, 403);
			$error = true;
			$error_message = '403 Forbidden';
			if(file_exists("themes/".$nuke_configs['ThemeSel']."/403.php"))
			{
				include("themes/".$nuke_configs['ThemeSel']."/403.php");
				die();
			}
		break;

		case"csrf":
			header('HTTP/1.0 403 Forbidden', true, 403);
			$error = true;
			$error_message = _CSRF_TOKEN_ERROR;
			if(file_exists("themes/".$nuke_configs['ThemeSel']."/csrf.php"))
			{
				include("themes/".$nuke_configs['ThemeSel']."/csrf.php");
				die();
			}
		break;
	}

	define("_ERROR_PAGE", true);
	include("header.php");
	$html_output .= '
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="error-template">
					<h1>
						'._SORRY.'</h1>
					<h2>
						'._ERROR.'</h2>
					<div class="error-details">
						'.$error_message.'
					</div>
					<div class="error-actions">
						<a href="'._GOBACK_CLEAN.'" class="btn btn-info btn-lg"><span class="glyphicon glyphicon-share-alt"></span> '._GOBACK_TEXT.' </a>
						<a href="'.$nuke_configs['nukeurl'].'" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-home"></span> '._GO_TO_MAIN_PAGE.' </a>
						<a href="'.LinkToGT("index.php?modname=Feedback").'" class="btn btn-default btn-lg"><span class="glyphicon glyphicon-envelope"></span> '._CONTACT_US.' </a>
					</div>
				</div>
			</div>
		</div>
	</div>';
	include("footer.php");
}

function suspend_template()
{
$contents .="<!DOCTYPE html>
<html xmlns=\"http://www.w3.org/1999/xhtml\">
	<head>
		<title>{SITENAME}</title>
	</head>
	<body>
		<h1>Not Found</h1>
		The requested URL /404.shtml was not found on this server.
		<hr>
		<i>{NUKEURL}</i>
	</body>
</html>";

return $contents;
}
?>