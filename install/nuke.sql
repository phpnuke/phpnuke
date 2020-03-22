-- PHPNUKE MT-Edition 8.4.2 Sql file
-- phpMyAdmin SQL Dump
-- https://www.phpmyadmin.net/


SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

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
  `aid` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `realname` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rule` text COLLATE utf8mb4_unicode_ci,
  `pwd` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `counter` int(11) NOT NULL DEFAULT '0',
  `radminsuper` tinyint(1) NOT NULL DEFAULT '1',
  `admlanguage` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `aadminsuper` int(1) NOT NULL DEFAULT '0',
  `password_reset` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`aid`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nuke_banned_ip`
--

CREATE TABLE `{NUKEPREFIX}banned_ip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ip_address` (`ip_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nuke_blocks`
--

CREATE TABLE `{NUKEPREFIX}blocks` (
  `bid` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `url` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `refresh` int(11) NOT NULL DEFAULT '0',
  `last_refresh` int(11) NOT NULL DEFAULT '0',
  `blockfile` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`bid`),
  KEY `title` (`title`),
  KEY `blockfile` (`blockfile`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nuke_blocks_boxes`
--

CREATE TABLE `{NUKEPREFIX}blocks_boxes` (
  `box_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `box_blocks` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `box_blocks_data` text COLLATE utf8mb4_unicode_ci,
  `box_status` int(11) NOT NULL DEFAULT '0',
  `box_theme_location` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `sidename` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `module` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `catname` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `catimage` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cattext` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `catdesc` text COLLATE utf8mb4_unicode_ci,
  `parent_id` int(10) NOT NULL DEFAULT '0',
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
  `module` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_id` int(11) NOT NULL DEFAULT '0',
  `post_title` varchar(600) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `ratings` int(11) NOT NULL DEFAULT '0',
  `score` tinyint(4) NOT NULL DEFAULT '0',
  `reason` text COLLATE utf8mb4_unicode_ci,
  `last_moderation` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `reported` tinyint(1) NOT NULL DEFAULT '0',
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
  `config_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `config_value` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`config_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Table structure for table `nuke_feedbacks`
--

CREATE TABLE `{NUKEPREFIX}feedbacks` (
  `fid` int(10) NOT NULL AUTO_INCREMENT,
  `sender_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sender_email` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `custom_fields` text COLLATE utf8mb4_unicode_ci,
  `responsibility` int(10) NOT NULL DEFAULT '0',
  `replys` text COLLATE utf8mb4_unicode_ci,
  `added_time` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `group_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_lang_titles` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_options` int(11) UNSIGNED NOT NULL DEFAULT '7',
  `group_colour` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
   PRIMARY KEY (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------

--
-- Table structure for table `nuke_headlines`
--

CREATE TABLE `{NUKEPREFIX}headlines` (
  `hid` int(11) NOT NULL AUTO_INCREMENT,
  `sitename` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `headlinesurl` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`hid`),
  KEY `hid` (`hid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nuke_languages`
--

CREATE TABLE `{NUKEPREFIX}languages` (
  `lid` int(11) NOT NULL AUTO_INCREMENT,
  `main_word` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `log_by` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `log_time` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `log_ip` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lang_titles` text COLLATE utf8mb4_unicode_ci,
  `active` int(1) NOT NULL DEFAULT '0',
  `mod_permissions` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `admins` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `server` char(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` char(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` int(12) NOT NULL DEFAULT '0',
  `method` char(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nuke_mtsn_ipban`
--

CREATE TABLE `{NUKEPREFIX}mtsn_ipban` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `blocker` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ipaddress` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `time` int(12) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `expire` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `nav_title` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lang_nav_title` text COLLATE utf8mb4_unicode_ci,
  `nav_location` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `date` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `title` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` text COLLATE utf8mb4_unicode_ci,
  `attributes` text COLLATE utf8mb4_unicode_ci,
  `type` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `module` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `title` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `status` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'article',
  `aid` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_lead` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_color` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hometext` text COLLATE utf8mb4_unicode_ci,
  `bodytext` text COLLATE utf8mb4_unicode_ci,
  `post_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `comments` int(11) DEFAULT '0',
  `counter` mediumint(8) UNSIGNED DEFAULT '0',
  `cat` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `informant` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tags` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ihome` int(1) NOT NULL DEFAULT '0',
  `alanguage` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `allow_comment` int(1) NOT NULL DEFAULT '0',
  `position` int(2) NOT NULL DEFAULT '1',
  `post_pass` text COLLATE utf8mb4_unicode_ci,
  `post_image` text COLLATE utf8mb4_unicode_ci,
  `cat_link` int(5) NOT NULL DEFAULT '1',
  `permissions` text COLLATE utf8mb4_unicode_ci,
  `score` int(11) NOT NULL DEFAULT '0',
  `ratings` int(11) NOT NULL DEFAULT '0',
  `micro_data` text COLLATE utf8mb4_unicode_ci,
  `download` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `meta_part` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `ip` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `post_title` varchar(750) COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_link` text COLLATE utf8mb4_unicode_ci,
  `subject` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `module` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `db_table` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating_ip` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vote_time` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `session_id` varchar(100) COLLATE utf8_bin NOT NULL,
  `session_user_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `session_time` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `session_ip` varchar(40) COLLATE utf8_bin NOT NULL,
  `session_browser` varchar(150) COLLATE utf8_bin NOT NULL,
  `session_page` varchar(255) COLLATE utf8_bin NOT NULL,
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
  `hourly_info` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `visitor_ips` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `var` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `aid` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `canVote` tinyint(1) NOT NULL DEFAULT '0',
  `main_survey` tinyint(1) NOT NULL DEFAULT '0',
  `module` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_id` int(10) NOT NULL DEFAULT '0',
  `pollTitle` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pollUrl` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `planguage` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `voters` int(11) NOT NULL DEFAULT '0',
  `start_time` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `end_time` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `ip` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pollID` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nuke_tags`
--

CREATE TABLE `{NUKEPREFIX}tags` (
  `tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `aid` varchar(200) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `rel_user_id` int(11) NOT NULL DEFAULT '0',
  `factor_number` int(11) NOT NULL DEFAULT '0',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0',
  `gateway` varchar(20) NOT NULL,
  `data` text NOT NULL,
  `fish_image` longblob NOT NULL,
  `amount` bigint(20) NOT NULL DEFAULT '0',
  `title` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `order_part` varchar(20) NOT NULL,
  `order_id` mediumint(9) NOT NULL DEFAULT '0',
  `order_link` varchar(500) NOT NULL,
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
  `user_groups` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_status` int(10) NOT NULL DEFAULT '1',
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_password` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_reset` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_ip` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_regdate` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_birthday` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_realname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_lastvisit` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `user_lastpage` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_login_attempts` tinyint(4) NOT NULL DEFAULT '0',
  `user_login_block_expire` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_inactive_reason` tinyint(2) NOT NULL DEFAULT '0',
  `user_inactive_time` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `user_lang` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_allow_viewonline` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `user_avatar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_avatar_type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `user_sig` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_address` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_phone` VARCHAR(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_website` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_interests` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_femail` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_newsletter` int(1) NOT NULL DEFAULT '0',
  `user_points` int(10) DEFAULT '0',
  `check_num` int(11) NOT NULL DEFAULT '0',
  `user_gender` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_about` text COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Dumping data for table `nuke_users`
--

INSERT INTO `{NUKEPREFIX}users` (`user_id`, `group_id`, `user_groups`, `user_status`, `username`, `user_realname`, `user_lastvisit`, `user_lastpage`, `user_regdate`) VALUES
(1, 1, 1, 1, 'anonymous', 'مهمان', UNIX_TIMESTAMP(NOW()), 'index.html', UNIX_TIMESTAMP(NOW()));

-- --------------------------------------------------------

--
-- Table structure for table `nuke_users_fields`
--

CREATE TABLE `{NUKEPREFIX}users_fields` (
  `fid` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`vid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nuke_users_invites`
--

CREATE TABLE `{NUKEPREFIX}users_invites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL DEFAULT '0',
  `code` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;