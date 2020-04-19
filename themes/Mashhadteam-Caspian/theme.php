<?php

/************************************************************/
/* Theme for PHPNUKE 8.4	                                */
/* http://www.phpnuke.ir                                    */
/************************************************************/

if (!defined('NUKE_FILE')) {
	die ("You can't access this file directly...");
}

function OpenTable($title='', $panel_class="default")
{
	$contents = "
	<div class=\"OpenTable\">";
		if($title != '') $contents .= "<h2>$title</h2>";
		$contents .= "<div class=\"GScontent\">";
	return $contents;
}

function CloseTable()
{
	$contents = '
		</div>
		<div class="clear"></div>
	</div>';
	return $contents;
}

function _theme_header($meta_tags = array(), $custom_theme_setup = array(), $replace = false)
{
	global $nuke_configs, $theme_setup;
	$local = (isset($nuke_configs['local']) && $nuke_configs['local'] != '') ? explode("-", $nuke_configs['local']):array("fa","ir");
	
	$contents = '';
	$contents .= "<!DOCTYPE html>\n";
	$contents .= "<html lang=\"".$local[0]."\" dir=\""._DIRECTION."\">\n";
	$contents .= "	<head>";
	
	include("themes/".$nuke_configs['ThemeSel']."/meta.php");
	
	if(!empty($custom_theme_setup))
	{
		custom_theme_setup($theme_setup, $custom_theme_setup, array('default_meta', 'default_link_rel', 'default_css', 'default_js'), $replace);
	}
	foreach($theme_setup as $skey => $sval)
	{
		if(!in_array($skey, array('default_meta','default_link_rel','default_css','default_js')))
			continue;
		$theme_setup[$skey] = array_unique($sval);
	}
	
	if(isset($theme_setup['default_meta']) && !empty($theme_setup['default_meta']))
		foreach($theme_setup['default_meta'] as $default_meta)
			if($default_meta != '')
				$contents .= "\n\t\t".$default_meta;

	if(isset($theme_setup['default_link_rel']) && !empty($theme_setup['default_link_rel']))
		foreach($theme_setup['default_link_rel'] as $default_link_rel)
			if($default_link_rel != '')
				$contents .= "\n\t\t".$default_link_rel;

	if(isset($theme_setup['default_css']) && !empty($theme_setup['default_css']))
		foreach($theme_setup['default_css'] as $default_css)
			if($default_css != '')
				$contents .= "\n\t\t".$default_css;
		
	if(isset($theme_setup['default_js']) && !empty($theme_setup['default_js']))		
		foreach($theme_setup['default_js'] as $default_js)
			if($default_js != '')
				$contents .= "\n\t\t".$default_js;
				
	if(!defined("_ERROR_PAGE"))
		$contents .= show_microdata_as_json($meta_tags);
	
	$contents .= "\n\t</head>\n\t";
	
	return $contents;
}

function themeheader($meta_tags, $custom_theme_setup = array(), $replace = false)
{
	global $userinfo, $nuke_configs, $search_query, $theme_setup, $users_system;
	
	$caspian_configs = $theme_setup['caspian_configs'];
	
	$contents = _theme_header($meta_tags, $custom_theme_setup, $replace);
	
    $real_name = (isset($userinfo['name'])) ? mres($userinfo['name']):"";
	$dateTime = nuketimes();
	$now = date("H:i");
	$time = "<clock".((_DIRECTION == 'ltr') ? "dir=\"ltr\"":"")." class=\"nukeclock\"></clock>";
	
	$search_query = (isset($search_query) && $search_query != '') ? $search_query:"";
	
	$body_class = (_DIRECTION == 'ltr') ? " class=\"body-ltr\"":"";
	
	$contents .= "
<body".$body_class.">
	<header>
		<div class=\"GSTop\">
			<div class=\"container GSTopHeader\">
				<div class=\"GSStatus\">
					<span><i class=\"fa fa-bullhorn\"></i> "._WELLCOME."</span>
					<time><i class=\"fa fa-clock-o\"></i> $time <i class=\"fa fa-calendar\"></i> $dateTime</time>
				</div>
				<div class=\"clear\"></div>
				<nav class=\"navbar navbar-inverse\">
					<div class=\"container-fluid\">
						<div class=\"navbar-header\">
							<button type=\"button\" class=\"navbar-toggle\" data-toggle=\"collapse\" data-target=\"#main-menu\">
								<span class=\"icon-bar\"></span>
								<span class=\"icon-bar\"></span>
								<span class=\"icon-bar\"></span> 
							</button>
						</div>
						<div class=\"collapse navbar-collapse\" id=\"main-menu\">";
								$contents .= pn_nav_menu(array(
									'walker'	=> 'caspian_nav_menus',
									'theme_location'	=> 'primary',
									'container_class'	=> '',
									'menu_id'			=> 'main-menu',
									'menu_class'		=> 'nav navbar-nav',
									'list_class'		=> 'dropdown-menu',
									'link_before'		=> '<a tabindex="-1" href="%1$s" target="%2$s" class="%3$s" data-toggle="%4$s">',
									'link_after'		=> '</a>',
								));
							$contents .= "
							<form class=\"navbar-form navbar-left\" role=\"search\" action=\"".LinkToGT("index.php?modname=Search")."\" method=\"post\">
								<div class=\"form-group input-group\">
									<input type=\"text\" class=\"form-control\" placeholder=\""._SEARCH." ...\" value=\"$search_query\" name=\"search_query\">	<span class=\"input-group-btn\">
										<button class=\"btn btn-default\" type=\"button\">
											<span class=\"glyphicon glyphicon-search\"></span>
										</button>
									</span>
								</div>
								<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
							</form>
						</div>
					</div>
				</nav>
			</div>
		</div>
		<div class=\"container GSTopHeader\">
			<div class=\"GSHeader\">
				<div class=\"GSlogo\">
					<h1><a href=\"".LinkToGT("index.php")."\" title=\"".$nuke_configs['sitename']."\">".$nuke_configs['sitename']."</a></h1>
				</div>
			</div>
		</div>
	</header>";
	
	if(defined("HOME_FILE") && $caspian_configs['active_slider'] == 1)
	{
	$contents .="<div class=\"container GSSlider\">
		<section>
			<div id=\"myCarousel\" class=\"carousel slide\" data-ride=\"carousel\">
				<ol class=\"carousel-indicators\">";
				foreach($caspian_configs['slider_image'] as $key => $val)
				{
					if(empty($val)) continue;
					$class = ($key == 0) ? "class=\"active\"":"";
					$contents .="<li data-target=\"#myCarousel\" data-slide-to=\"$key\" $class></li>";
				}
				$contents .="</ol>
				<div class=\"carousel-inner\" role=\"listbox\">";
				foreach($caspian_configs['slider_image'] as $key => $val)
				{
					if(empty($val)) continue;
					$class = ($key == 0) ? "active":"";
					$contents .="<div class=\"item $class\">
						<img src=\"".$val."\" alt=\"Image\">
						<div class=\"carousel-caption\">
							<h5><a href=\"".$caspian_configs['slider_link'][$key]."\">".$caspian_configs['slider_title'][$key]."</a></h5>
							<h6>".$caspian_configs['slider_desc'][$key]."</h6>
						</div>      
					</div>";
				}
				$contents .="</div>
				<a class=\"left carousel-control\" href=\"#myCarousel\" role=\"button\" data-slide=\"prev\">
					<span class=\"glyphicon glyphicon-chevron-left\" aria-hidden=\"true\"></span>
					<span class=\"sr-only\">Previous</span>
				</a>
				<a class=\"right carousel-control\" href=\"#myCarousel\" role=\"button\" data-slide=\"next\">
					<span class=\"glyphicon glyphicon-chevron-right\" aria-hidden=\"true\"></span>
					<span class=\"sr-only\">Next</span>
				</a>
			</div>
		</section>
	</div>";
	}
	$contents .="<div class=\"container GSBody\">";
	return $contents;
}

function themefooter($custom_theme_setup = array(), $replace = false)
{
	global $db, $nuke_configs, $theme_setup, $cache;
	
	$caspian_configs = $theme_setup['caspian_configs'];

	$caspian_data = cache_caspian_data();
	$articles = $caspian_data['articles'];
	
	$contents = "
	</div>
	<footer>
		<div class=\"container GSFooter\">
			<div class=\"col-xs-12 col-sm-12 col-md-3 col-lg-3 GSAbout\">
				<h4><i class=\"fa fa-coffee\"></i> "._ABOUT_US."</h4>
				<div class=\"line-fooot-box-head\" style=\"border-right-width: 100px;\"></div>
				<p>".$caspian_configs['about_us']."</p>
			</div>
			<div class=\"col-xs-12 col-sm-12 col-md-3 col-lg-3\">
				<h4><i class=\"fa fa-newspaper-o\"></i> "._TOP_ARTICLES."</h4>
				<div class=\"line-fooot-box-head\" style=\"border-right-width: 100px;\"></div>
				<ul>";
					if(isset($articles[1]))
					{
						foreach($articles[1] as $latest_articles)
						{
							$contents .= "<li><a href=\"".$latest_articles['link']."\" title=\"".$latest_articles['title']."\">".$latest_articles['title']."</a></li>";
						}
					}
				$contents .= "</ul>
			</div>
			<div class=\"col-xs-12 col-sm-12 col-md-3 col-lg-3\">
				<h4><i class=\"fa fa-random\"></i> "._HOT_ARTICLES."</h4>
				<div class=\"line-fooot-box-head\" style=\"border-right-width: 100px;\"></div>
				<ul>";
					if(isset($articles[2]))
					{
						foreach($articles[2] as $random_articles)
						{
							$contents .= "<li><a href=\"".$random_articles['link']."\" title=\"".$random_articles['title']."\">".$random_articles['title']."</a></li>";
						}
					}
				$contents .= "</ul>
			</div>
			<div class=\"col-xs-12 col-sm-12 col-md-3 col-lg-3\">
				<h4><i class=\"fa fa-phone\"></i> "._CONTACT_US."</h4>
				<div class=\"line-fooot-box-head\" style=\"border-right-width: 100px;\"></div>
				<p>
					<span><i class=\"fa fa-map-marker\"></i> "._POSTAL_ADDRESS." : ".$caspian_configs['address']."</span><br>
					<span><i class=\"fa fa-phone\"></i> "._LANDLINE_PHONE." : ".$caspian_configs['phone']."</span><br>
					<span><i class=\"fa fa-mobile\"></i> "._MOBILE_PHONE." : ".$caspian_configs['mobile']."</span><br>
					<span><i class=\"fa fa-fax\"></i> "._FAX." : ".$caspian_configs['fax']."</span><br>
				</p>
				<p class=\"GSsocial\">
					<a href=\"".$caspian_configs['twitter']."\" class=\"btn btn-social-icon btn-twitter btn-lg\"><i class=\"fa fa-twitter\"></i></a>
					<a href=\"".$caspian_configs['instagram']."\" class=\"btn btn-social-icon btn-instagram btn-lg\"><i class=\"fa fa-instagram\"></i></a>
					<a href=\"".$caspian_configs['facebook']."\" class=\"btn btn-social-icon btn-facebook btn-lg\"><i class=\"fa fa-facebook\"></i></a>
					<a href=\"".$caspian_configs['telegram']."\" class=\"btn btn-primary btn-rw mt10\">"._TELEGRAM_CHANNEL."</a>
					<a href=\"".$caspian_configs['contact_us']."\" class=\"btn btn-primary btn-rw mt10\">"._ADMIN_CONTACT."</a>
				</p>
			</div>
		</div>
		<div class=\"GSCopyright\">
			<div class=\"container\">
				<span>©</span> "._ALLRIGHT_RESERVED." <i class=\"fa fa-code\"></i> "._DESIGN_AND_DEV." : <a href=\"http://www.GreenSkin.ir\" title=\""._GREENSKIN_DESIGN."\">"._GREEENSKIN."</a> | "._HONESTLY_POWERED_BY." <a href=\"http://www.phpnuke.ir/\">"._PHPNUKE_MT_EDITION."</a> <span>".number_format((microtime(true)-_START_TIME), 2)." "._SECOND."</span>
			</div>
		</div>
	</footer>";
	
	$contents .= _theme_footer($custom_theme_setup, $replace);	
		
	return $contents;
}

function _theme_footer($custom_theme_setup = array(), $replace = false)
{
	global $db, $nuke_configs, $theme_setup;
	
	$caspian_configs = $theme_setup['caspian_configs'];
	
	$defer_js_contents = '';
	
	if(!empty($custom_theme_setup))
	{
		custom_theme_setup($theme_setup, $custom_theme_setup, array('defer_js'), $replace);
	}
	
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
	<!-- Modal -->
	<div class=\"modal fade\" id=\"sitemodal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">
		<div class=\"modal-dialog\">
			<div class=\"modal-content\">
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->
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
		<aside>
			<h3><i class=\"fa fa-star\"></i> $title</h3>
			<div class=\"GSContent\">
				$content
			</div>
		</aside>";
	}
	else
		include("themes/".$nuke_configs['ThemeSel']."/blocks/themes/$themeblock");
	
	return $contents;
}

function article_index($article_info)
{
	global $nuke_configs, $admin_file;
	
	$article_info['comments'] = ($article_info['comments']==0) ? "0":$article_info['comments'];
	
	$cats_name = array();
	if(isset($article_info['cats_data']) && !empty($article_info['cats_data']))
	{
		foreach($article_info['cats_data'] as $cat => $cat_data)
		{
			if($cat_data['catname'] == 'uncategorized') continue;
			$cats_name[] = "<a href=\"".$cat_data['catlink']."\">".$cat_data['catname']."</a>";
		}
	}
	
	$post_imge = (isset($article_info['post_image']) && $article_info['post_image'] != '') ? "<img class=\"img-thumbnail\" style=\"float:right;margin:0 7px 7px 7px;\" src=\"".LinkToGT("index.php?timthumb=true&src=".LinkToGT($article_info['post_image'])."&h=155&w=230&q=90&a=c")."\" width=\"230\" height=\"155\" />":"";
	
	$content = "
	<article>
		<h2><i class=\"fa fa-th\"></i><a href=\"".$article_info['article_link']."\" title=\"".$article_info['title']."\">".$article_info['title']."</a>".(($article_info['status'] == 'future') ? " ("._PUBLISH_IN_FUTURE.")":"")."</h2>";
		if(!empty($cats_name)){
		$content .="<div class=\"GScat\">
			<i class=\"fa fa-comment\"></i> "._CATEGORIES." : ".implode(", ", $cats_name)."
		</div>";
		}
		$content .="<div class=\"GSContent\">
			$post_imge
			<p class=\"GSJustify\">".$article_info['hometext']."</p>
		</div>
		<div class=\"meta\">
			<ul>
				<li class=\"hidden-xs\"><i class=\"fa fa-user\"></i> <a href=\"".$article_info['aid_url']."\"> ".$article_info['aid']."</a></li>
				<li class=\"hidden-xs\"><i class=\"fa fa-calendar\"></i> ".$article_info['datetime']."</li>
				<li><i class=\"fa fa-eye\"></i> ".$article_info['counter']."</li>
				<li><i class=\"fa fa-comment\"></i> <a href=\"".$article_info['article_link']."#comments\">".$article_info['comments']."</a></li>
				".((is_admin()) ? "<li><a href=\"".LinkToGT($admin_file.".php?op=article_admin&mode=edit&sid=".$article_info['sid']."")."\"><i class=\"fa fa-edit\"></i></a></li><li><a href=\"".LinkToGT($admin_file.".php?op=article_admin&mode=delete&sid=".$article_info['sid']."&csrf_token="._PN_CSRF_TOKEN."")."\" onclick=\"return confirm('"._DELETE_THIS_SURE."');\"><i class=\"fa fa-remove\"></i></a></li>":"")."
			</ul>
			<span class=\"more\"><a href=\"".$article_info['article_link']."\">"._ARTICLE_MORE."</a></span>
		</div>
	</article>";
	return $content;
}

function article_more($article_info)
{
	global $nuke_configs, $admin_file;
	
	$module = (isset($article_info['post_type']) && $article_info['post_type'] != '') ? $article_info['post_type']:"Articles";
	$tags = str_replace(" ","-",$article_info['tags']);
	$tags = explode(",",$tags);
	$tags = array_filter($tags);
	$htmltags = '';
	foreach($tags as $tag)
		$htmltags .= "<i><a href=\"".LinkToGT("index.php?modname=$module&tags=$tag")."\">".str_replace("_"," ",$tag)."</a></i> ";
	
	$article_info['comments'] = ($article_info['comments']==0) ? "0":$article_info['comments'];
	
	$cats_name = array();
	if(isset($article_info['cats_data']) && !empty($article_info['cats_data']))
	{
		foreach($article_info['cats_data'] as $cat => $cat_data)
		{
			if($cat_data['catname'] == 'uncategorized') continue;
			$cats_name[] = "<a href=\"".$cat_data['catlink']."\">".$cat_data['catname']."</a>";
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
	
	$content = "
	<article>
		<h2><i class=\"fa fa-th\"></i><a href=\"".$article_info['article_link']."\" title=\"".$article_info['title']."\">".$article_info['title']."</a></h2>";
		if(!empty($cats_name)){
		$content .="<div class=\"GScat\">
			<i class=\"fa fa-comment\"></i> "._CATEGORIES." : ".implode(", ", $cats_name)."
		</div>";
		}
		$content .="<div class=\"GSContent\">
			$post_imge
			<p class=\"GSJustify\">
			".$article_info['hometext']."<br />
			".$article_info['bodytext']."<br />
			".$post_files."<br />
			<div id=\"article-more-share\">
				<div class=\"form-inline\">
					<div class=\"input-group\">
						<span class=\"input-group-addon btn copytoClipboard\" data-clipboard-target=\"#copytoClipboard\"><i class=\"fa fa-copy\"></i> "._COPY."</span>
							<input class=\"form-control\" id=\"copytoClipboard\" value=\"".$article_info['article_short_link']."\" type=\"text\" readonly />
						<div class=\"input-group-addon copytoClipboard\" data-clipboard-target=\"#copytoClipboard\">لینک اشتراک گذاری</div>
					</div>
				</div>
			</div>
			<div class=\"clear\"></div>
			<div class=\"article-tags\">$htmltags</div></p>
		</div>
		<div class=\"meta\">
			<ul>
				<li class=\"hidden-xs\"><i class=\"fa fa-user\"></i> <a href=\"".$article_info['aid_url']."\"> ".$article_info['aid']."</a></li>
				<li class=\"hidden-xs\"><i class=\"fa fa-calendar\"></i> ".$article_info['datetime']."</li>
				<li><i class=\"fa fa-eye\"></i> ".$article_info['counter']."</li>
				<li><i class=\"fa fa-comment\"></i> <a href=\"".$article_info['article_link']."#comments\">".$article_info['comments']."</a></li>
				".((is_admin()) ? "<li><a href=\"".LinkToGT($admin_file.".php?op=article_admin&mode=edit&sid=".$article_info['sid']."")."\"><i class=\"fa fa-edit\"></i></a></li><li><a href=\"".LinkToGT($admin_file.".php?op=article_admin&mode=delete&sid=".$article_info['sid']."&csrf_token="._PN_CSRF_TOKEN."")."\" onclick=\"return confirm('"._DELETE_THIS_SURE."');\"><i class=\"fa fa-remove\"></i></a></li>":"")."
			</ul>
		</div>
	</article>";
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
	global $nuke_configs;
	
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

?>