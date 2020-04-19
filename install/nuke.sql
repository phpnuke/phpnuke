-- PHPNUKE MT-Edition 8.4.2 Sql file
-- phpMyAdmin SQL Dump
-- https://www.phpmyadmin.net/

-- --------------------------------------------------------

--
-- Table structure for table `nuke_admins_menu`
--

CREATE TABLE `{NUKEPREFIX}admins_menu` (
  `amid` int(10) NOT NULL AUTO_INCREMENT,
  `atitle` text COLLATE utf8mb4_unicode_ci,
  `admins` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`amid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nuke_admins_menu`
--

INSERT INTO `{NUKEPREFIX}admins_menu` (`amid`, `atitle`, `admins`) VALUES
(1, 'authors', ''),
(2, 'backup', ''),
(3, 'blocks', ''),
(4, 'bookmarks', ''),
(5, 'cache', ''),
(7, 'groups', ''),
(8, 'meta_tags', ''),
(9, 'modules', ''),
(10, 'mtsn', ''),
(12, 'referrers', ''),
(13, 'settings', ''),
(14, 'upgrade', ''),
(15, 'media', ''),
(16, 'language', ''),
(17, 'nav_menus', '');

-- --------------------------------------------------------

--
-- Table structure for table `nuke_authors`
--

CREATE TABLE `{NUKEPREFIX}authors` (
  `aid` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `realname` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `phone` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `rule` text COLLATE utf8mb4_unicode_ci,
  `pwd` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `counter` int(11) NOT NULL DEFAULT '0',
  `radminsuper` tinyint(1) NOT NULL DEFAULT '1',
  `admlanguage` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `aadminsuper` int(1) NOT NULL DEFAULT '0',
  `password_reset` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`aid`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nuke_banned_ip`
--

CREATE TABLE `{NUKEPREFIX}banned_ip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `date` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `ip_address` (`ip_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nuke_blocks`
--

CREATE TABLE `{NUKEPREFIX}blocks` (
  `bid` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `content` text COLLATE utf8mb4_unicode_ci,
  `url` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `refresh` int(11) NOT NULL DEFAULT '0',
  `last_refresh` int(11) NOT NULL DEFAULT '0',
  `blockfile` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`bid`),
  KEY `title` (`title`),
  KEY `blockfile` (`blockfile`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nuke_blocks_boxes`
--

CREATE TABLE `{NUKEPREFIX}blocks_boxes` (
  `box_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `box_blocks` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `box_blocks_data` text COLLATE utf8mb4_unicode_ci,
  `box_status` int(11) NOT NULL DEFAULT '0',
  `box_theme_location` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `box_theme_priority` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`box_id`),
  KEY `box_theme_location` (`box_theme_location`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nuke_blocks_boxes`
--

INSERT INTO `{NUKEPREFIX}blocks_boxes` (`box_id`, `box_blocks`, `box_blocks_data`, `box_status`, `box_theme_location`, `box_theme_priority`) VALUES
('bottomcenter', '', '', 1, '', 0),
('comments', '', '', 1, '', 0),
('left', '', '', 1, '', 0),
('right', '', '', 1, '', 0),
('topcenter', '', '', 1, '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `nuke_blocks_themes`
--

CREATE TABLE `{NUKEPREFIX}blocks_themes` (
  `sideid` int(10) NOT NULL AUTO_INCREMENT,
  `sidename` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`sideid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nuke_blocks_themes`
--

INSERT INTO `{NUKEPREFIX}blocks_themes` (`sideid`, `sidename`) VALUES
(1, 'nuke');

-- --------------------------------------------------------

--
-- Table structure for table `nuke_bookmarksite`
--

CREATE TABLE `{NUKEPREFIX}bookmarksite` (
  `bid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` text COLLATE utf8mb4_unicode_ci,
  `iconpath` text COLLATE utf8mb4_unicode_ci,
  `active` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `url` text COLLATE utf8mb4_unicode_ci,
  `weight` int(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`bid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nuke_bookmarksite`
--

INSERT INTO `{NUKEPREFIX}bookmarksite` (`bid`, `title`, `iconpath`, `active`, `url`, `weight`) VALUES
(1, 'گوگل', 'images/share/gimages.jpg', 1, 'http://www.google.com/bookmarks/mark?op=edit&output=popup&bkmk={URL}&title={TITLE}', 1),
(2, 'بالاترین', 'images/share/bal.png', 1, 'http://www.balatarin.com/links/submit?phase=2&url={URL}&title={TITLE}', 2),
(3, 'کلوب', 'images/share/cloob.gif', 1, 'http://www.cloob.com/share/link/add?url={URL}&title={TITLE}', 3),
(4, 'viwio', 'images/share/viwio.png', 1, 'http://www.viwio.com/home/?status={URL}&subject={TITLE}', 4),
(5, 'دنباله', 'images/share/donbaleh.gif', 1, 'https://donbaleh.com/submit.php?url={URL}&subject={TITLE}', 5),
(6, 'تویتر', 'images/share/twitter.png', 1, 'http://twitter.com/home?status={URL} - {TITLE}', 6),
(7, 'فیس بوک', 'images/share/facebook.png', 1, 'http://facebook.com/sharer.php?u={URL}&amp;t={TITLE}', 7),
(8, 'Google Buzz', 'images/share/google-buzz.png', 1, 'http://www.google.com/reader/link?url={URL}&title={TITLE}&srcURL={URL}', 8),
(9, 'Google Bookmarks', 'images/share/google.png', 1, 'http://google.com/bookmarks/mark?op=add&amp;bkmk={URL}&amp;title={TITLE}', 9),
(10, 'Digg', 'images/share/digg.png', 1, 'http://digg.com/submit?phase=2&amp;url={URL}&amp;title={TITLE}', 10),
(11, 'یاهو مسنجر', 'images/share/yahoo.gif', 1, 'ymsgr:im?msg=ino bebin - {URL} - {TITLE}', 12),
(12, 'Technorati', 'images/share/Technorati.png', 1, 'http://technorati.com/faves?add={URL}&title={TITLE}', 13),
(13, 'delicious', 'images/share/delicious.png', 1, 'http://delicious.com/post?url={URL}&title={TITLE}', 11);

-- --------------------------------------------------------

--
-- Table structure for table `nuke_categories`
--

CREATE TABLE `{NUKEPREFIX}categories` (
  `catid` int(10) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `module` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `catname` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `catimage` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `cattext` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `catdesc` text COLLATE utf8mb4_unicode_ci,
  `parent_id` int(10) NOT NULL DEFAULT '0',
  `imported_id` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`catid`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nuke_comments`
--

CREATE TABLE `{NUKEPREFIX}comments` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT '0',
  `main_parent` int(11) NOT NULL DEFAULT '0',
  `module` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `post_id` int(11) NOT NULL DEFAULT '0',
  `post_title` varchar(600) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `date` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `username` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `email` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `url` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `ip` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comment` text COLLATE utf8mb4_unicode_ci,
  `ratings` int(11) NOT NULL DEFAULT '0',
  `score` tinyint(4) NOT NULL DEFAULT '0',
  `reason` text COLLATE utf8mb4_unicode_ci,
  `last_moderation` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `status` int(1) NOT NULL DEFAULT '0',
  `reported` tinyint(1) NOT NULL DEFAULT '0',
  `imported_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cid`),
  KEY `pid` (`pid`),
  KEY `post_id` (`post_id`),
  KEY `main_parent` (`main_parent`),
  KEY `module` (`module`),
  KEY `status` (`status`),
  KEY `reported` (`reported`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nuke_config`
--

CREATE TABLE `{NUKEPREFIX}config` (
  `config_id` int(11) NOT NULL AUTO_INCREMENT,
  `config_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `config_value` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`config_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nuke_config`
--

INSERT INTO `{NUKEPREFIX}config` (`config_name`, `config_value`) VALUES
('sitename', ''),
('nukeurl', ''),
('site_logo', ''),
('site_description', 'شرح مختصری در مورد سایت'),
('slogan', ''),
('startdate', ''),
('adminmail', ''),
('adminmail_name', 'مدیریت سایت'),
('anonpost', '1'),
('Default_Theme', 'Mashhadteam-Caspian'),
('overwrite_theme', '0'),
('footer_message', ''),
('commentlimit', '40960'),
('anonymous', 'ميهمان'),
('minpass', '5'),
('broadcast_msg', '1'),
('my_headlines', '1'),
('top', '0'),
('home_pagination', '20'),
('user_pagination', '0'),
('oldnum', '20'),
('banners', '1'),
('backend_title', ''),
('backend_language', 'en-us'),
('language', 'farsi'),
('locale', 'fa_IR'),
('multilingual', '1'),
('useflags', '0'),
('notify', '0'),
('notify_subject', 'خبر جدید ارسال شده است'),
('notify_message', 'خبر جدیدی در سایت به منظور تائید مدیر ارسال شده است.'),
('notify_from', 'webmaster'),
('moderate', '0'),
('admingraphic', '1'),
('httpref', '1'),
('httprefmax', '1000'),
('httprefmode', '1'),
('copyright', 'VUVkU2NHUnBRbkJhUkRCcFZGWlJkRkV5T1hkbFdFcHdXakpvTUVscU5WRlRSa0YwVkc1V2NscFRRbEZqYlRseFdsZE9NRWxGU2pWSlJIaG9TVWRvZVZwWFdUbEpiV2d3WkVoQk5reDVPVE5rTTJOMVkwZG9kMkp1Vm5KYVV6VndZMmxKWjJSSFJubGFNbFl3VUZOS1psbHRlR2hpYlhOcFNVaEtiR0pFTUdsWk1qbDNaVmhLY0ZveWFEQkphalZSVTBaQ1QyUlhkR3hNYld4NVVFTTVhRkJxZDNaYVIyd3lVR2M5UFE9PQ=='),
('Version_Num', '8.4.2'),
('nuke_editor', '1'),
('display_errors', '0'),
('gtset', '1'),
('userurl', '1'),
('align', 'rtl'),
('show_links', '1'),
('datetype', '1'),
('show_effect', '1'),
('votetype', '3'),
('mobile_mode', '0'),
('filemaneger_pass', ''),
('sitecookies', '/'),
('site_meta_tags', ''),
('site_keywords', 'کلمات,کلیدی,سایت'),
('suspend_site', '0'),
('suspend_start', ''),
('suspend_expire', ''),
('suspend_template', '<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>{SITENAME}</title>
	</head>
	<body>
		<h1>Not Found</h1>
		The requested URL /404.shtml was not found on this server.
		<hr>
		<i>{NUKEURL}</i>
	</body>
</html>'),
('upload_allowed_info', ''),
('upload_pagesitems', '5'),
('pagination_number', '1'),
('comments', 'a:12:{s:5:"allow";s:1:"1";s:9:"anonymous";s:1:"1";s:12:"confirm_need";s:1:"1";s:5:"limit";s:1:"0";s:6:"editor";s:1:"2";s:6:"inputs";a:4:{s:8:"name_act";s:1:"1";s:9:"email_act";s:1:"1";s:9:"email_req";s:1:"1";s:7:"url_act";s:1:"1";}s:6:"notify";a:2:{s:5:"email";s:1:"1";s:3:"sms";s:1:"1";}s:8:"order_by";s:1:"1";s:12:"allow_rating";s:1:"1";s:15:"allow_reporting";s:1:"1";s:13:"item_per_page";s:2:"20";s:5:"depth";s:1:"2";}'),
('max_log_numbers', '500'),
('smtp_email_server', ''),
('smtp_email_user', ''),
('smtp_email_pass', ''),
('smtp_secure', ''),
('smtp_port', '0'),
('smtp_debug', '0'),
('is_html_mail', '1'),
('allow_attachement_mail', '1'),
('mtsn_text_file', '1'),
('mtsn_status', '1'),
('mtsn_show_alarm', '1'),
('mtsn_send_mail', '1'),
('mtsn_admin_mail', 'attack@sitename.com'),
('mtsn_string_filter', '1'),
('mtsn_html_filter', '1'),
('mtsn_injection_filter', '1'),
('mtsn_block_ip', '0'),
('mtsn_version', '4.3.0'),
('mtsn_ddos_filter', '0'),
('mtsn_CensorMode', '0'),
('mtsn_CensorWords', 'سکس'),
('mtsn_CensorReplace', '*****'),
('mtsn_login_attempts', '1'),
('mtsn_login_attempts_time', '3600'),
('mtsn_requests_mintime', '5'),
('mtsn_requests_pages', '7'),
('seccode_type', '2'),
('google_recaptcha_sitekey', ''),
('google_recaptcha_secretkey', ''),
('mtsn_gfx_chk', 'admin_login,user_login,comments,send_post,feedback,user_sign_up'),
('gverify', ''),
('alexverify', ''),
('yverify', ''),
('gcse', ''),
('ganalytic', ''),
('ping_sites', 'http://rpc.pingomatic.com
http://rpc.twingly.com
http://rpc.weblogs.com/RPC2
http://ping.blo.gs/
http://ping.feedburner.com'),
('meta_Tags', ''),
('active_pings', '1'),
('last_ping_time', ''),
('ping_options', 'a:3:{s:12:"limit_number";s:1:"1";s:10:"limit_time";s:1:"3";s:10:"limit_ping";i:1;}'),
('future_pings', ''),
('future_ping_time', ''),
('ping_num', '0'),
('have_forum', 0),
('forum_path', ''),
('forum_system', ''),
('forum_prefix', "phpbb_"),
('forum_db', ''),
('mtsn_search_skipwords', 'است,این'),
('feedbacks', 'a:13:{s:10:"letreceive";s:1:"1";s:5:"delay";s:3:"600";s:6:"notify";a:1:{s:3:"sms";s:1:"1";}s:11:"description";s:77:"<p>به سیستم مدیریت محتوای نیوک خوش آمدید</p>
";s:5:"phone";s:0:"";s:6:"mobile";s:0:"";s:3:"fax";s:0:"";s:7:"address";s:0:"";s:16:"meta_description";s:86:"بخش ارتباط با ما سیستم مدیریت محتوای نیوک فارسی";s:13:"meta_keywords";a:3:{i:0;s:22:"ارتباط با ما";i:1;s:18:"تماس با ما";i:2;s:11:"فید بک";}s:10:"map_active";s:1:"1";s:10:"google_api";s:0:"";s:12:"map_position";s:35:"36.28795445718431,59.61575198173523";}'),
('forum_GTlink_active', '1'),
('forum_collation', 'latin1'),
('website_index_theme', '0'),
('session_last_gc', '1498217142'),
('mtsn_captcha_charset', ''),
('sessions_prefix', 'pnSession_'),
('mtsn_block_ip_expire', '3600'),
('session_timeout', '3600'),
('forum_seo_post_link', 'post{P}.html#p{P}'),
('forum_seo_topic_link', 'forum-f{F}/topic-t{T}.html'),
('forum_seo_forum_link', 'forum-f{F}/'),
('forum_seo_profile_link', 'member/{UN}/'),
('forum_seo_pm_link', ''),
('forum_seo_login_link', ''),
('forum_seo_logout_link', ''),
('forum_seo_ucp_link', ''),
('forum_seo_register_link', ''),
('forum_seo_passlost_link', ''),
('timthumb_allowed', 'phpnuke.ir'),
('lock_siteurl', 1),
('smilies', 'a:21:{i:0;a:4:{s:4:"name";s:10:"icon_arrow";s:4:"code";s:2:";)";s:3:"url";s:28:"images/smiles/icon_arrow.gif";s:10:"dimentions";s:5:"19*19";}i:1;a:4:{s:4:"name";s:13:"icon_confused";s:4:"code";s:2:"|)";s:3:"url";s:31:"images/smiles/icon_confused.gif";s:10:"dimentions";s:5:"19*19";}i:2;a:4:{s:4:"name";s:9:"icon_cool";s:4:"code";s:2:":-";s:3:"url";s:27:"images/smiles/icon_cool.gif";s:10:"dimentions";s:5:"19*19";}i:3;a:4:{s:4:"name";s:8:"icon_cry";s:4:"code";s:2:":(";s:3:"url";s:26:"images/smiles/icon_cry.gif";s:10:"dimentions";s:5:"19*19";}i:4;a:4:{s:4:"name";s:8:"icon_eek";s:4:"code";s:2:":0";s:3:"url";s:26:"images/smiles/icon_eek.gif";s:10:"dimentions";s:5:"19*19";}i:5;a:4:{s:4:"name";s:9:"icon_evil";s:4:"code";s:2:":#";s:3:"url";s:27:"images/smiles/icon_evil.gif";s:10:"dimentions";s:5:"19*19";}i:6;a:4:{s:4:"name";s:12:"icon_exclaim";s:4:"code";s:2:"*)";s:3:"url";s:30:"images/smiles/icon_exclaim.gif";s:10:"dimentions";s:5:"19*19";}i:7;a:4:{s:4:"name";s:9:"icon_razz";s:4:"code";s:2:"^)";s:3:"url";s:27:"images/smiles/icon_razz.gif";s:10:"dimentions";s:5:"19*19";}i:8;a:4:{s:4:"name";s:14:"icon_surprised";s:4:"code";s:3:"+))";s:3:"url";s:32:"images/smiles/icon_surprised.gif";s:10:"dimentions";s:5:"19*19";}i:9;a:4:{s:4:"name";s:10:"icon_smile";s:4:"code";s:2:":}";s:3:"url";s:28:"images/smiles/icon_smile.gif";s:10:"dimentions";s:5:"19*19";}i:10;a:4:{s:4:"name";s:8:"icon_sad";s:4:"code";s:3:"|((";s:3:"url";s:26:"images/smiles/icon_sad.gif";s:10:"dimentions";s:5:"19*19";}i:11;a:4:{s:4:"name";s:13:"icon_rolleyes";s:4:"code";s:2:"@:";s:3:"url";s:31:"images/smiles/icon_rolleyes.gif";s:10:"dimentions";s:5:"19*19";}i:12;a:4:{s:4:"name";s:12:"icon_redface";s:4:"code";s:3:"(:)";s:3:"url";s:30:"images/smiles/icon_redface.gif";s:10:"dimentions";s:5:"19*19";}i:13;a:4:{s:4:"name";s:13:"icon_question";s:4:"code";s:2:":?";s:3:"url";s:31:"images/smiles/icon_question.gif";s:10:"dimentions";s:5:"19*19";}i:14;a:4:{s:4:"name";s:5:"heart";s:4:"code";s:3:")*(";s:3:"url";s:23:"images/smiles/heart.gif";s:10:"dimentions";s:5:"19*19";}i:15;a:4:{s:4:"name";s:4:"kiss";s:4:"code";s:3:"#%^";s:3:"url";s:22:"images/smiles/kiss.gif";s:10:"dimentions";s:5:"19*19";}i:16;a:4:{s:4:"name";s:9:"thumbs_up";s:4:"code";s:3:"@@#";s:3:"url";s:27:"images/smiles/thumbs_up.gif";s:10:"dimentions";s:5:"19*19";}i:17;a:4:{s:4:"name";s:11:"thumbs_down";s:4:"code";s:4:")))&";s:3:"url";s:29:"images/smiles/thumbs_down.gif";s:10:"dimentions";s:5:"19*19";}i:18;a:4:{s:4:"name";s:16:"embaressed_smile";s:4:"code";s:3:"^^*";s:3:"url";s:34:"images/smiles/embaressed_smile.gif";s:10:"dimentions";s:5:"19*19";}i:19;a:4:{s:4:"name";s:13:"regular_smile";s:4:"code";s:2:"!^";s:3:"url";s:31:"images/smiles/regular_smile.gif";s:10:"dimentions";s:5:"19*19";}i:20;a:4:{s:4:"name";s:10:"wink_smile";s:4:"code";s:3:"%&^";s:3:"url";s:28:"images/smiles/wink_smile.gif";s:10:"dimentions";s:5:"19*19";}}'),
('forum_last_number', '15'),
('nukecdnurl', ''),
('users', 'a:32:{s:19:"login_sign_up_theme";s:1:"1";s:12:"allowuserreg";s:1:"1";s:5:"coppa";s:1:"1";s:3:"tos";s:1:"1";s:10:"invitation";s:1:"0";s:14:"max_invitation";s:1:"5";s:8:"nick_max";s:2:"25";s:8:"nick_min";s:1:"3";s:8:"pass_max";s:2:"25";s:8:"pass_min";s:1:"3";s:16:"doublecheckemail";s:1:"1";s:8:"bad_mail";s:0:"";s:12:"bad_username";s:0:"";s:8:"bad_nick";s:0:"";s:12:"requireadmin";s:1:"1";s:18:"email_activatation";s:1:"1";s:17:"send_email_af_reg";s:1:"1";s:11:"sendaddmail";s:1:"1";s:11:"avatar_salt";s:8:"sdfsdwfs";s:11:"avatar_path";s:30:"modules/Users/includes/avatar/";s:12:"allow_avatar";s:1:"1";s:19:"allow_avatar_upload";s:1:"1";s:19:"allow_avatar_remote";s:1:"1";s:14:"allow_gravatar";s:1:"1";s:15:"allowmailchange";s:1:"1";s:16:"avatar_max_width";s:3:"180";s:17:"avatar_max_height";s:3:"180";s:16:"avatar_min_width";s:2:"40";s:17:"avatar_min_height";s:2:"40";s:15:"avatar_filesize";s:6:"102400";s:5:"mttos";s:28:"<p>قوانين سايت</p>";s:6:"notify";a:2:{s:3:"sms";s:1:"1";s:5:"email";s:1:"1";}}'),
('minify_src', '1'),
('pn_credits', 'a:7:{s:10:"min_amount";s:5:"10000";s:10:"max_amount";s:9:"500000000";s:6:"notify";a:1:{s:3:"sms";s:1:"1";}s:18:"credits_direct_msg";s:0:"";s:16:"credits_list_msg";s:0:"";s:10:"currencies";a:5:{i:0;a:3:{s:4:"code";s:3:"USD";s:4:"name";s:21:"دلار آمريکا";s:12:"rial_ex_rate";s:0:"";}i:1;a:3:{s:4:"code";s:3:"EUR";s:4:"name";s:8:"يورو";s:12:"rial_ex_rate";s:0:"";}i:2;a:3:{s:4:"code";s:3:"AED";s:4:"name";s:21:"درهم امارات";s:12:"rial_ex_rate";s:0:"";}i:3;a:3:{s:4:"code";s:3:"GBP";s:4:"name";s:21:"پوند انگليس";s:12:"rial_ex_rate";s:0:"";}i:4;a:3:{s:4:"code";s:3:"KWD";s:4:"name";s:19:"دينار کويت";s:12:"rial_ex_rate";s:0:"";}}s:8:"gateways";a:0:{}}'),
('sms', '0'),
('pn_sms', 'a:5:{s:8:"operator";s:5:"opsms";s:8:"username";s:0:"";s:8:"password";s:0:"";s:14:"default_number";s:0:"";s:10:"recipients";s:0:"";}'),
('csrf_token_time', '1800'),
('statistics_refresh', '1800');

-- --------------------------------------------------------


--
-- Table structure for table `nuke_feedbacks`
--

CREATE TABLE `{NUKEPREFIX}feedbacks` (
  `fid` int(10) NOT NULL AUTO_INCREMENT,
  `sender_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `sender_email` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `subject` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `message` text COLLATE utf8mb4_unicode_ci,
  `custom_fields` text COLLATE utf8mb4_unicode_ci,
  `responsibility` int(10) NOT NULL DEFAULT '0',
  `replys` text COLLATE utf8mb4_unicode_ci,
  `added_time` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `ip` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`fid`),
  KEY `ip` (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nuke_groups`
--

CREATE TABLE `{NUKEPREFIX}groups` (
  `group_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `group_type` tinyint(4) NOT NULL DEFAULT '1',
  `group_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `group_lang_titles` text COLLATE utf8mb4_unicode_ci,
  `group_options` int(11) UNSIGNED NOT NULL DEFAULT '7',
  `group_colour` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
   PRIMARY KEY (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------

--
-- Table structure for table `nuke_headlines`
--

CREATE TABLE `{NUKEPREFIX}headlines` (
  `hid` int(11) NOT NULL AUTO_INCREMENT,
  `sitename` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `headlinesurl` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`hid`),
  KEY `hid` (`hid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nuke_languages`
--

CREATE TABLE `{NUKEPREFIX}languages` (
  `lid` int(11) NOT NULL AUTO_INCREMENT,
  `main_word` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `equals` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`lid`),
  KEY `main_word` (`main_word`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nuke_log`
--

CREATE TABLE `{NUKEPREFIX}log` (
  `lid` int(11) NOT NULL AUTO_INCREMENT,
  `log_type` tinyint(1) NOT NULL DEFAULT '1',
  `log_by` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `log_time` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `log_ip` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `log_message` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`lid`),
  KEY `log_type` (`log_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nuke_modules`
--

CREATE TABLE `{NUKEPREFIX}modules` (
  `mid` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `lang_titles` text COLLATE utf8mb4_unicode_ci,
  `active` int(1) NOT NULL DEFAULT '0',
  `mod_permissions` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `admins` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `all_blocks` int(1) NOT NULL DEFAULT '0',
  `main_module` int(1) NOT NULL DEFAULT '0',
  `in_menu` int(1) NOT NULL DEFAULT '0',
  `module_boxes` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`mid`),
  KEY `title` (`title`(191)),
  KEY `active` (`active`),
  KEY `main_module` (`main_module`),
  KEY `in_menu` (`in_menu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nuke_mtsn`
--

CREATE TABLE `{NUKEPREFIX}mtsn` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server` char(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `ip` char(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `time` int(12) NOT NULL DEFAULT '0',
  `method` char(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nuke_mtsn_ipban`
--

CREATE TABLE `{NUKEPREFIX}mtsn_ipban` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `blocker` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `ipaddress` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `reason` text COLLATE utf8mb4_unicode_ci,
  `time` int(12) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `expire` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ipaddress` (`ipaddress`),
  KEY `blocker` (`blocker`(191)),
  KEY `status` (`status`),
  KEY `expire` (`expire`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nuke_nav_menus`
--

CREATE TABLE `{NUKEPREFIX}nav_menus` (
  `nav_id` int(11) NOT NULL AUTO_INCREMENT,
  `nav_title` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `lang_nav_title` text COLLATE utf8mb4_unicode_ci,
  `nav_location` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `status` int(11) NOT NULL DEFAULT '0',
  `date` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`nav_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------

--
-- Table structure for table `nuke_nav_menus_data`
--

CREATE TABLE `{NUKEPREFIX}nav_menus_data` (
  `nid` int(11) NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `nav_id` int(11) NOT NULL DEFAULT '0',
  `pid` int(11) NOT NULL DEFAULT '0',
  `weight` int(11) NOT NULL DEFAULT '1',
  `title` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `url` text COLLATE utf8mb4_unicode_ci,
  `attributes` text COLLATE utf8mb4_unicode_ci,
  `type` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `module` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `part_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`nid`),
  KEY `status` (`status`),
  KEY `nav_id` (`nav_id`),
  KEY `pid` (`pid`),
  KEY `weight` (`weight`),
  KEY `type` (`type`(191)),
  KEY `module` (`module`),
  KEY `part_id` (`part_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nuke_points_groups`
--

CREATE TABLE `{NUKEPREFIX}points_groups` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` int(1) NOT NULL DEFAULT '0',
  `title` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `description` text COLLATE utf8mb4_unicode_ci,
  `points` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nuke_points_groups`
--

INSERT INTO `{NUKEPREFIX}points_groups` (`id`, `type`, `title`, `description`, `points`) VALUES
(1, 1, 'نوشتن وبلاگ', 'نوشتن مطلب در وبلاگ ', 0),
(2, 1, 'ارائه نظر در مورد وبلاگها', 'ارسال نظرات براي مطالب وبلاگهاي کاربران', 0),
(3, 1, 'معرفي سايت به دوستان', 'ارسال لينک براي سايت و پيشنهاد اين سايت به دوستان', 0),
(4, 1, 'ارسال مطلب به سايت', 'اخبار ارسالي توسط کاربر که مدير تائيد نموده است', 0),
(5, 1, 'نظر در مورد مطالب سايت', 'ارسال نظر براي اخبار', 0),
(6, 1, 'مطالب ارسالي به دوستان', 'ارسال اخبار سايت توسط کاربر براي ديگران', 0),
(7, 1, 'امتياز دهي به مطالب سايت', 'راي به اخبار', 0),
(8, 1, 'دفعات مشاركت كاربر در نظرسنجي ها', 'راي در نظرسنجي هاي سايت', 0),
(9, 1, 'ارائه نظر در مورد نظرسنجي ها', 'نظرات ارسالي براي تمامي نظرسنجي هاي فعلي و قديمي سايت', 0),
(10, 1, 'ارسال مطلب به انجمنها', 'ارسال مطلب جديد در انجمن ها', 0),
(11, 1, 'ارسال جوابيه در انجمنها', 'پاسخ به مطالب انجمن ها', 0),
(12, 1, 'ارائه نظر در مورد نقدنامه ها', 'ارسال نظرات براي بخش نقدنامه', 0),
(13, 1, 'بازديد صفحات', 'رفتن به صفحات مختلف اين سايت ', 0),
(14, 1, 'بازديد يك لينك', 'پيشهاد و رفتن به سايت هاي معرفي شده در بخش آدرس سايت ها', 0),
(15, 1, 'راي به لينكها', 'راي به سايت هاي بخش آدرس سايت ها', 0),
(16, 1, 'نظر در مورد لينك ها', 'ارسال نظر براي لينک هاي موجود در آدرس سايت ها', 0),
(17, 1, 'دريافت يك فايل', 'دريافت و دانلود فايل از سايت', 0),
(18, 1, 'راي به فايلهاي موجود سايت', 'راي به فايل هاي بخش دريافت فايل', 0),
(19, 1, 'نظر در مورد فايلهاي موجود سايت', 'ارسال نظرات در بخش دريافت فايل', 0),
(20, 1, 'پيغام همگاني', 'ارسال پيغام همگاني در سايت', 0),
(21, 1, 'كليك روي بنرهاي تبليغاتي', 'کليک بر روي هر يک از تبليغات موجود در سايت', 0),
(22, 1, ' معرفی کاربر به سایت ', 'معرفی سایت به دیگران برای ثبت نام در سایت', 100);

-- --------------------------------------------------------

--
-- Table structure for table `nuke_posts`
--

CREATE TABLE `{NUKEPREFIX}posts` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `post_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'articles',
  `aid` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `title` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `title_lead` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `title_color` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `time` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `ip` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `hometext` text COLLATE utf8mb4_unicode_ci,
  `bodytext` text COLLATE utf8mb4_unicode_ci,
  `post_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comments` int(11) DEFAULT '0',
  `counter` mediumint(8) UNSIGNED DEFAULT '0',
  `cat` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `informant` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `tags` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `ihome` int(1) NOT NULL DEFAULT '0',
  `alanguage` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `allow_comment` int(1) NOT NULL DEFAULT '0',
  `position` int(2) NOT NULL DEFAULT '1',
  `post_pass` text COLLATE utf8mb4_unicode_ci,
  `post_image` text COLLATE utf8mb4_unicode_ci,
  `cat_link` int(5) NOT NULL DEFAULT '1',
  `permissions` text COLLATE utf8mb4_unicode_ci,
  `score` int(11) NOT NULL DEFAULT '0',
  `ratings` int(11) NOT NULL DEFAULT '0',
  `micro_data` text COLLATE utf8mb4_unicode_ci,
  `download` longtext COLLATE utf8mb4_unicode_ci,
  `imported_id` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sid`),
  KEY `title` (`title`(191)),
  KEY `status` (`status`),
  KEY `post_type` (`post_type`),
  KEY `time` (`time`),
  KEY `cat` (`cat`(191)),
  KEY `tags` (`tags`(191)),
  KEY `ihome` (`ihome`),
  KEY `alanguage` (`alanguage`),
  KEY `position` (`position`),
  KEY `cat_link` (`cat_link`),
  KEY `post_url` (`post_url`(191)) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nuke_postsmeta`
--

CREATE TABLE `{NUKEPREFIX}postsmeta` (
  `mid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `meta_part` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `meta_value` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`mid`),
  KEY `post_id` (`post_id`),
  KEY `meta_key` (`meta_key`(191)),
  KEY `meta_part` (`meta_part`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nuke_referrer`
--

CREATE TABLE `{NUKEPREFIX}referrer` (
  `rid` int(11) NOT NULL AUTO_INCREMENT,
  `url` text COLLATE utf8mb4_unicode_ci,
  `path` text COLLATE utf8mb4_unicode_ci,
  `ip` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `time` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`rid`),
  KEY `ip` (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nuke_reports`
--

CREATE TABLE `{NUKEPREFIX}reports` (
  `rid` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `post_title` varchar(750) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `post_link` text COLLATE utf8mb4_unicode_ci,
  `subject` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `message` text COLLATE utf8mb4_unicode_ci,
  `module` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `time` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `ip` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`rid`),
  KEY `post_id` (`post_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nuke_scores`
--

CREATE TABLE `{NUKEPREFIX}scores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `votetype` int(11) NOT NULL DEFAULT '1',
  `post_id` int(11) NOT NULL DEFAULT '0',
  `db_table` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `rating_ip` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `vote_time` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `score` tinyint(1) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `gust` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`),
  KEY `db_table` (`db_table`(191)),
  KEY `rating_ip` (`rating_ip`),
  KEY `score` (`score`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nuke_sessions`
--

CREATE TABLE `{NUKEPREFIX}sessions` (
  `session_id` varchar(100) COLLATE utf8_bin NOT NULL DEFAULT '',
  `session_user_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `session_time` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `session_ip` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '',
  `session_browser` varchar(150) COLLATE utf8_bin NOT NULL DEFAULT '',
  `session_page` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`session_id`),
  KEY `session_time` (`session_time`),
  KEY `session_user_id` (`session_user_id`),
  KEY `session_ip` (`session_ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `nuke_statistics`
--

CREATE TABLE `{NUKEPREFIX}statistics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `year` int(11) NOT NULL DEFAULT '0',
  `month` int(11) NOT NULL DEFAULT '0',
  `day` int(11) NOT NULL DEFAULT '0',
  `hourly_info` text COLLATE utf8mb4_unicode_ci,
  `visitor_ips` longtext COLLATE utf8mb4_unicode_ci,
  `visitors` int(11) NOT NULL DEFAULT '0',
  `hits` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `year` (`year`),
  KEY `month` (`month`),
  KEY `day` (`day`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nuke_statistics_counter`
--

CREATE TABLE `{NUKEPREFIX}statistics_counter` (
  `type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `var` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `count` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nuke_statistics_counter`
--

INSERT INTO `{NUKEPREFIX}statistics_counter` (`type`, `var`, `count`) VALUES
('os', 'win 10', 0),
('os', 'win 8.1', 0),
('os', 'win 8', 0),
('os', 'win 7', 0),
('os', 'win Vista', 0),
('os', 'win Server 2003/XP x64', 0),
('os', 'win XP', 0),
('os', 'win 2000', 0),
('os', 'win ME', 0),
('os', 'win 98', 0),
('os', 'win 95', 0),
('os', 'win 3.11', 0),
('os', 'Mac OS X', 0),
('os', 'Mac OS 9', 0),
('os', 'Linux', 0),
('os', 'Ubuntu', 0),
('os', 'iPhone', 0),
('os', 'iPod', 0),
('os', 'iPad', 0),
('os', 'Android', 0),
('os', 'BlackBerry', 0),
('os', 'Mobile', 0),
('browser', 'Msie', 0),
('browser', 'Firefox', 0),
('browser', 'Safari', 0),
('browser', 'Chrome', 0),
('browser', 'Opera', 0),
('browser', 'Netscape', 0),
('browser', 'Maxthon', 0),
('browser', 'Konqueror', 0),
('browser', 'Handheld Browser', 0),
('total', 'hits', 0),
('browser', 'Others', 0),
('os', 'Others OS', 0),
('mosts', 'total', 0),
('mosts', 'members', 0),
('mosts', 'guests', 0),
('mosts', 'date', 0);

-- --------------------------------------------------------

--
-- Table structure for table `nuke_surveys`
--

CREATE TABLE `{NUKEPREFIX}surveys` (
  `pollID` int(11) NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `aid` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `canVote` tinyint(1) NOT NULL DEFAULT '0',
  `main_survey` tinyint(1) NOT NULL DEFAULT '0',
  `module` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `post_id` int(10) NOT NULL DEFAULT '0',
  `pollTitle` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `pollUrl` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `description` text COLLATE utf8mb4_unicode_ci,
  `planguage` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `voters` int(11) NOT NULL DEFAULT '0',
  `start_time` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `end_time` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `to_main` tinyint(1) NOT NULL DEFAULT '0',
  `allow_comment` tinyint(1) NOT NULL DEFAULT '0',
  `comments` int(11) DEFAULT '0',
  `options` text COLLATE utf8mb4_unicode_ci,
  `multi_vote` tinyint(1) NOT NULL DEFAULT '0',
  `show_voters_num` tinyint(1) NOT NULL DEFAULT '0',
  `permissions` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`pollID`),
  KEY `pollID` (`pollID`),
  KEY `status` (`status`),
  KEY `canVote` (`canVote`),
  KEY `main_survey` (`main_survey`),
  KEY `module` (`module`),
  KEY `post_id` (`post_id`),
  KEY `pollUrl` (`pollUrl`(191)),
  KEY `pollTitle` (`pollTitle`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nuke_surveys_check`
--

CREATE TABLE `{NUKEPREFIX}surveys_check` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `time` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `pollID` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nuke_tags`
--

CREATE TABLE `{NUKEPREFIX}tags` (
  `tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `counter` int(20) NOT NULL DEFAULT '0',
  `visits` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tag_id`),
  KEY `tag` (`tag`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nuke_transactions`
--

CREATE TABLE `{NUKEPREFIX}transactions` (
  `tid` int(11) NOT NULL AUTO_INCREMENT,
  `aid` varchar(200) NOT NULL DEFAULT '',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `rel_user_id` int(11) NOT NULL DEFAULT '0',
  `factor_number` int(11) NOT NULL DEFAULT '0',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0',
  `gateway` varchar(20) NOT NULL DEFAULT '',
  `data` text NOT NULL,
  `fish_image` longblob NOT NULL,
  `amount` bigint(20) NOT NULL DEFAULT '0',
  `title` varchar(250) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `order_part` varchar(20) NOT NULL DEFAULT '',
  `order_id` mediumint(9) NOT NULL DEFAULT '0',
  `order_link` varchar(500) NOT NULL DEFAULT '',
  `order_data` text NOT NULL,
  PRIMARY KEY (`tid`),
  KEY `user_id` (`user_id`),
  KEY `type` (`type`),
  KEY `order_id` (`order_id`),
  KEY `order_part` (`order_part`),
  KEY `status` (`status`),
  KEY `factor_number` (`factor_number`),
  KEY `aid` (`aid`(191)),
  KEY `rel_user_id` (`rel_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `nuke_users`
--

CREATE TABLE `{NUKEPREFIX}users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL DEFAULT '2',
  `user_groups` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_status` int(10) NOT NULL DEFAULT '1',
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_password` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `password_reset` text COLLATE utf8mb4_unicode_ci,
  `user_ip` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_regdate` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_birthday` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_realname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_lastvisit` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `user_lastpage` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_login_attempts` tinyint(4) NOT NULL DEFAULT '0',
  `user_login_block_expire` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_inactive_reason` tinyint(2) NOT NULL DEFAULT '0',
  `user_inactive_time` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `user_lang` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_allow_viewonline` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `user_avatar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_avatar_type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'upload',
  `user_sig` mediumtext COLLATE utf8mb4_unicode_ci,
  `user_address` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_phone` VARCHAR(25) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_website` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_interests` text COLLATE utf8mb4_unicode_ci,
  `user_femail` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_newsletter` int(1) NOT NULL DEFAULT '0',
  `user_points` int(10) DEFAULT '0',
  `check_num` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_gender` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_about` text COLLATE utf8mb4_unicode_ci,
  `user_credit` bigint(20) NOT NULL DEFAULT '0',
  `user_referrer` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  KEY `user_id` (`user_id`),
  KEY `username` (`username`(191)),
  KEY `user_status` (`user_status`),
  KEY `user_email` (`user_email`),
  KEY `user_regdate` (`user_regdate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `nuke_users_fields`
--

CREATE TABLE `{NUKEPREFIX}users_fields` (
  `fid` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `display` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `size` int(3) NOT NULL DEFAULT '0',
  `need` int(1) NOT NULL DEFAULT '1',
  `pos` int(3) NOT NULL DEFAULT '0',
  `act` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nuke_users_fields_values`
--

CREATE TABLE `{NUKEPREFIX}users_fields_values` (
  `vid` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL DEFAULT '0',
  `fid` int(10) NOT NULL DEFAULT '0',
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`vid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nuke_users_invites`
--

CREATE TABLE `{NUKEPREFIX}users_invites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL DEFAULT '0',
  `code` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `email` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `time` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;