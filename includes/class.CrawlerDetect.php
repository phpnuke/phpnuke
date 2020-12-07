<?php
/**
*
* This file is part of the PHP-NUKE Software package.
*
* @copyright (c) PHP-NUKE <https://www.phpnuke.ir>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

if (!defined('NUKE_FILE')) {
	die ("You can't access this file directly...");
}

/*
 * This file is part of Crawler Detect - the web crawler detection library.
 *
 * (c) Mark Beech <m@rkbee.ch>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

abstract class AbstractProvider
{
    /**
     * The data set.
     * 
     * @var array
     */
    protected $data;
    /**
     * Return the data set.
     * 
     * @return array
     */
    public function getAll()
    {
        return $this->data;
    }
}

class Crawlers extends AbstractProvider
{
    /**
     * Array of regular expressions to match against the user agent.
     *
     * @var array
     */
    protected $data = array(
        '.*Java.*outbrain',
        '008\/',
        '192\.comAgent',
        '2ip\.ru',
        '404checker',
        '^bluefish ',
        '^Calypso v\/',
        '^COMODO DCV',
        '^DangDang',
        '^DavClnt',
        '^FDM ',
        '^git\/',
        '^Goose\/',
        '^HTTPClient\/',
        '^Java\/',
        '^Jeode\/',
        '^Jetty\/',
        '^Mget',
        '^Microsoft URL Control',
        '^NG\/[0-9\.]',
        '^NING\/',
        '^PHP\/[0-9]',
        '^RMA\/',
        '^Ruby|Ruby\/[0-9]',
        '^scrutiny\/',
        '^VSE\/[0-9]',
        '^WordPress\.com',
        '^XRL\/[0-9]',
        '^ZmEu',
        'a3logics\.in',
        'A6-Indexer',
        'a\.pr-cy\.ru',
        'Aboundex',
        'aboutthedomain',
        'Accoona-AI-Agent',
        'acoon',
        'acrylicapps\.com\/pulp',
        'adbeat',
        'AddThis',
        'ADmantX',
        'adressendeutschland',
        'Advanced Email Extractor v',
        'agentslug',
        'AHC',
        'aihit',
        'aiohttp\/',
        'Airmail',
        'akula\/',
        'alertra',
        'alexa site audit',
        'Alibaba\.Security\.Heimdall',
        'alyze\.info',
        'amagit',
        'AndroidDownloadManager',
        'Anemone',
        'Ant\.com',
        'Anturis Agent',
        'AnyEvent-HTTP\/',
        'Apache-HttpClient\/',
        'AportWorm\/[0-9]',
        'AppEngine-Google',
        'Arachmo',
        'arachnode',
        'Arachnophilia',
        'aria2',
        'asafaweb.com',
        'AskQuickly',
        'Astute',
        'asynchttp',
        'autocite',
        'Autonomy',
        'B-l-i-t-z-B-O-T',
        'Backlink-Ceck\.de',
        'Bad-Neighborhood',
        'baidu\.com',
        'baypup\/[0-9]',
        'baypup\/colbert',
        'BazQux',
        'BCKLINKS',
        'BDFetch',
        'BegunAdvertising\/',
        'BigBozz',
        'biglotron',
        'BingLocalSearch',
        'BingPreview',
        'binlar',
        'biNu image cacher',
        'biz_Directory',
        'Blackboard Safeassign',
        'Bloglovin',
        'BlogPulseLive',
        'BlogSearch',
        'Blogtrottr',
        'boitho\.com-dc',
        'BPImageWalker',
        'Braintree-Webhooks',
        'Branch Metrics API',
        'Branch-Passthrough',
        'Browsershots',
        'BUbiNG',
        'Butterfly\/',
        'BuzzSumo',
        'CAAM\/[0-9]',
        'CakePHP',
        'CapsuleChecker',
        'CaretNail',
        'catexplorador',
        'cb crawl',
        'CC Metadata Scaper',
        'Cerberian Drtrs',
        'CERT\.at-Statistics-Survey',
        'cg-eye',
        'changedetection',
        'Charlotte',
        'CheckHost',
        'checkprivacy',
        'chkme\.com',
        'CirrusExplorer\/',
        'CISPA Vulnerability Notification',
        'CJNetworkQuality',
        'clips\.ua\.ac\.be',
        'Cloud mapping experiment',
        'CloudFlare-AlwaysOnline',
        'Cloudinary\/[0-9]',
        'cmcm\.com',
        'coccoc',
        'CommaFeed',
        'Commons-HttpClient',
        'Comodo SSL Checker',
        'contactbigdatafr',
        'convera',
        'copyright sheriff',
        'Covario-IDS',
        'CrawlForMe\/[0-9]',
        'cron-job\.org',
        'Crowsnest',
        'curb',
        'Curious George',
        'curl',
        'cuwhois\/[0-9]',
        'cybo\.com',
        'DareBoost',
        'DataparkSearch',
        'dataprovider',
        'Daum(oa)?[ \/][0-9]',
        'DeuSu',
        'developers\.google\.com\/\+\/web\/snippet\/',
        'Digg',
        'Dispatch\/',
        'dlvr',
        'DMBrowser-UV',
        'DNS-Tools Header-Analyzer',
        'DNSPod-reporting',
        'docoloc',
        'Dolphin http client\/',
        'DomainAppender',
        'dotSemantic',
        'downforeveryoneorjustme',
        'downnotifier\.com',
        'DowntimeDetector',
        'Dragonfly File Reader',
        'drupact',
        'Drupal \(\+http:\/\/drupal\.org\/\)',
        'dubaiindex',
        'EARTHCOM',
        'Easy-Thumb',
        'ec2linkfinder',
        'eCairn-Grabber',
        'ECCP',
        'ElectricMonk',
        'elefent',
        'EMail Exractor',
        'EmailWolf',
        'Embed PHP Library',
        'Embedly',
        'europarchive\.org',
        'evc-batch\/[0-9]',
        'EventMachine HttpClient',
        'Evidon',
        'Evrinid',
        'ExactSearch',
        'ExaleadCloudview',
        'Excel\/',
        'Exif Viewer',
        'Exploratodo',
        'ezooms',
        'facebookexternalhit',
        'facebookplatform',
        'fairshare',
        'Faraday v',
        'Faveeo',
        'Favicon downloader',
        'FavOrg',
        'Feed Wrangler',
        'Feedbin',
        'FeedBooster',
        'FeedBucket',
        'FeedBunch\/[0-9]',
        'FeedBurner',
        'FeedChecker',
        'Feedly',
        'Feedspot',
        'Feedwind\/[0-9]',
        'feeltiptop',
        'Fetch API',
        'Fetch\/[0-9]',
        'Fever\/[0-9]',
        'findlink',
        'findthatfile',
        'FlipboardBrowserProxy',
        'FlipboardProxy',
        'FlipboardRSS',
        'fluffy',
        'flynxapp',
        'forensiq',
        'FoundSeoTool\/[0-9]',
        'free thumbnails',
        'FreeWebMonitoring SiteChecker',
        'Funnelback',
        'g00g1e\.net',
        'GAChecker',
        'ganarvisitas\/[0-9]',
        'geek-tools',
        'Genderanalyzer',
        'Genieo',
        'GentleSource',
        'GetLinkInfo',
        'getprismatic\.com',
        'GetURLInfo\/[0-9]',
        'GigablastOpenSource',
        'github\.com\/',
        'Go [\d\.]* package http',
        'Go-http-client',
        'gofetch',
        'GomezAgent',
        'gooblog',
        'Goodzer\/[0-9]',
        'Google favicon',
        'Google Keyword Suggestion',
        'Google Keyword Tool',
        'Google Page Speed Insights',
        'Google PP Default',
        'Google Search Console',
        'Google Web Preview',
        'Google-Adwords',
        'Google-Apps-Script',
        'Google-Calendar-Importer',
        'Google-HTTP-Java-Client',
        'Google-Publisher-Plugin',
        'Google-SearchByImage',
        'Google-Site-Verification',
        'Google-Structured-Data-Testing-Tool',
        'Google-Youtube-Links',
        'google_partner_monitoring',
        'GoogleDocs',
        'GoogleHC\/',
        'GoogleProducer',
        'GoScraper',
        'GoSpotCheck',
        'GoSquared-Status-Checker',
        'gosquared-thumbnailer',
        'GotSiteMonitor',
        'grabify',
        'Grammarly',
        'grouphigh',
        'grub-client',
        'GTmetrix',
        'gvfs\/',
        'HAA(A)?RTLAND http client',
        'Hatena',
        'hawkReader',
        'HEADMasterSEO',
        'HeartRails_Capture',
        'heritrix',
        'hledejLevne\.cz\/[0-9]',
        'Holmes',
        'HootSuite Image proxy',
        'Hootsuite-WebFeed\/[0-9]',
        'HostTracker',
        'ht:\/\/check',
        'htdig',
        'HTMLParser\/',
        'HTTP-Header-Abfrage',
        'http-kit',
        'HTTP-Tiny',
        'HTTP_Compression_Test',
        'http_request2',
        'http_requester',
        'HttpComponents',
        'httphr',
        'HTTPMon',
        'PEAR HTTPRequest',
        'httpscheck',
        'httpssites_power',
        'httpunit',
        'HttpUrlConnection',
        'httrack',
        'hosterstats',
        'huaweisymantec',
        'HubPages.*crawlingpolicy',
        'HubSpot Connect',
        'HubSpot Marketing Grader',
        'HyperZbozi.cz Feeder',
        'i2kconnect\/',
        'ichiro',
        'IdeelaborPlagiaat',
        'IDG Twitter Links Resolver',
        'IDwhois\/[0-9]',
        'Iframely',
        'igdeSpyder',
        'IlTrovatore',
        'ImageEngine\/',
        'Imagga',
        'InAGist',
        'inbound\.li parser',
        'InDesign%20CC',
        'infegy',
        'infohelfer',
        'InfoWizards Reciprocal Link System PRO',
        'Instapaper',
        'inpwrd\.com',
        'Integrity',
        'integromedb',
        'internet_archive',
        'InternetSeer',
        'internetVista monitor',
        'IODC',
        'IOI',
        'iplabel',
        'IPS\/[0-9]',
        'ips-agent',
        'IPWorks HTTP\/S Component',
        'iqdb\/',
        'Irokez',
        'isitup\.org',
        'iskanie',
        'iZSearch',
        'janforman',
        'Jigsaw',
        'Jobboerse',
        'jobo',
        'Jobrapido',
        'JS-Kit',
        'KeepRight OpenStreetMap Checker',
        'KeyCDN Perf Test',
        'Keywords Research',
        'KickFire',
        'KimonoLabs\/',
        'Kml-Google',
        'knows\.is',
        'kouio',
        'KrOWLer',
        'kulturarw3',
        'KumKie',
        'L\.webis',
        'Larbin',
        'LayeredExtractor',
        'LibVLC',
        'libwww',
        'Licorne Image Snapshot',
        'Liferea\/',
        'link checker',
        'Link Valet',
        'link_thumbnailer',
        'LinkAlarm\/',
        'linkCheck',
        'linkdex',
        'LinkExaminer',
        'linkfluence',
        'linkpeek',
        'LinkTiger',
        'LinkWalker',
        'Lipperhey',
        'livedoor ScreenShot',
        'LoadImpactPageAnalyzer',
        'LoadImpactRload',
        'LongURL API',
        'looksystems\.net',
        'ltx71',
        'lwp-trivial',
        'lycos',
        'LYT\.SR',
        'mabontland',
        'MagpieRSS',
        'Mail.Ru',
        'MailChimp\.com',
        'Mandrill',
        'MapperCmd',
        'marketinggrader',
        'Mediapartners-Google',
        'MegaIndex\.ru',
        'Melvil Rawi\/',
        'MergeFlow-PageReader',
        'Metaspinner',
        'MetaURI',
        'Microsearch',
        'Microsoft-WebDAV-MiniRedir',
        'Microsoft Data Access Internet Publishing Provider Protocol',
        'Microsoft Office ',
        'Microsoft Windows Network Diagnostics',
        'Mindjet',
        'Miniflux',
        'mixdata dot com',
        'mixed-content-scan',
        'Mnogosearch',
        'mogimogi',
        'Mojolicious \(Perl\)',
        'monitis',
        'Monitority\/[0-9]',
        'montastic',
        'MonTools',
        'Moreover',
        'Morning Paper',
        'mowser',
        'Mrcgiguy',
        'mShots',
        'MVAClient',
        'nagios',
        'Najdi\.si\/',
        'Needle\/',
        'NETCRAFT',
        'NetLyzer FastProbe',
        'netresearch',
        'NetShelter ContentScan',
        'NetTrack',
        'Netvibes',
        'Neustar WPM',
        'NeutrinoAPI',
        'NewsBlur .*Finder',
        'NewsGator',
        'newsme',
        'newspaper\/',
        'NG-Search',
        'nineconnections\.com',
        'NLNZ_IAHarvester',
        'Nmap Scripting Engine',
        'node-superagent',
        'node\.io',
        'nominet\.org\.uk',
        'Norton-Safeweb',
        'Notifixious',
        'notifyninja',
        'nuhk',
        'nutch',
        'Nuzzel',
        'nWormFeedFinder',
        'Nymesis',
        'Ocelli\/[0-9]',
        'oegp',
        'okhttp',
        'Omea Reader',
        'omgili',
        'Online Domain Tools',
        'OpenCalaisSemanticProxy',
        'Openstat\/',
        'OpenVAS',
        'Optimizer',
        'Orbiter',
        'OrgProbe\/[0-9]',
        'ow\.ly',
        'ownCloud News',
        'OxfordCloudService\/[0-9]',
        'Page Analyzer',
        'Page Valet',
        'page2rss',
        'page_verifier',
        'PagePeeker',
        'Pagespeed\/[0-9]',
        'Panopta',
        'panscient',
        'parsijoo',
        'PayPal IPN',
        'Pcore-HTTP',
        'Pearltrees',
        'peerindex',
        'Peew',
        'PhantomJS\/',
        'Photon\/',
        'phpcrawl',
        'phpservermon',
        'Pi-Monster',
        'ping\.blo\.gs\/',
        'Pingdom',
        'Pingoscope',
        'PingSpot',
        'pinterest\.com',
        'Pizilla',
        'Ploetz \+ Zeller',
        'Plukkie',
        'PocketParser',
        'Pompos',
        'Porkbun',
        'Port Monitor',
        'postano',
        'PostPost',
        'postrank',
        'PowerPoint\/',
        'Priceonomics Analysis Engine',
        'PritTorrent\/[0-9]',
        'Prlog',
        'probethenet',
        'Project 25499',
        'Promotion_Tools_www.searchenginepromotionhelp.com',
        'prospectb2b',
        'Protopage',
        'proximic',
        'pshtt, https scanning',
        'PTST ',
        'PTST\/[0-9]+',
        'Pulsepoint XT3 web scraper',
        'Python-httplib2',
        'python-requests',
        'Python-urllib',
        'Qirina Hurdler',
        'QQDownload',
        'Qseero',
        'Qualidator.com SiteAnalyzer',
        'Quora Link Preview',
        'Qwantify',
        'Radian6',
        'RankSonicSiteAuditor',
        'Readability',
        'RealPlayer%20Downloader',
        'RebelMouse',
        'redback\/',
        'Redirect Checker Tool',
        'ReederForMac',
        'request\.js',
        'ResponseCodeTest\/[0-9]',
        'RestSharp',
        'RetrevoPageAnalyzer',
        'Riddler',
        'Rival IQ',
        'Robosourcer',
        'Robozilla\/[0-9]',
        'ROI Hunter',
        'RPT-HTTPClient',
        'RSSOwl',
        'safe-agent-scanner',
        'SalesIntelligent',
        'SauceNAO',
        'SBIder',
        'Scoop',
        'scooter',
        'ScoutJet',
        'ScoutURLMonitor',
        'Scrapy',
        'ScreenShotService\/[0-9]',
        'Scrubby',
        'search\.thunderstone',
        'SearchSight',
        'Seeker',
        'semanticdiscovery',
        'semanticjuice',
        'Semiocast HTTP client',
        'SEO Browser',
        'Seo Servis',
        'seo-nastroj.cz',
        'Seobility',
        'SEOCentro',
        'SeoCheck',
        'SeopultContentAnalyzer',
        'Server Density Service Monitoring',
        'servernfo\.com',
        'Seznam screenshot-generator',
        'Shelob',
        'Shoppimon Analyzer',
        'ShoppimonAgent\/[0-9]',
        'ShopWiki',
        'ShortLinkTranslate',
        'shrinktheweb',
        'SilverReader',
        'SimplePie',
        'SimplyFast',
        'Site-Shot\/',
        'Site24x7',
        'SiteBar',
        'SiteCondor',
        'siteexplorer\.info',
        'SiteGuardian',
        'Siteimprove\.com',
        'Sitemap(s)? Generator',
        'Siteshooter B0t',
        'SiteTruth',
        'sitexy\.com',
        'SkypeUriPreview',
        'slider\.com',
        'slurp',
        'SMRF URL Expander',
        'SMUrlExpander',
        'Snappy',
        'SniffRSS',
        'sniptracker',
        'Snoopy',
        'sogou web',
        'SortSite',
        'spaziodati',
        'Specificfeeds',
        'speedy',
        'SPEng',
        'Spinn3r',
        'spray-can',
        'Sprinklr ',
        'spyonweb',
        'Sqworm',
        'SSL Labs',
        'StackRambler',
        'Statastico\/',
        'StatusCake',
        'Stratagems Kumo',
        'Stroke.cz',
        'StudioFACA',
        'suchen',
        'summify',
        'Super Monitoring',
        'Surphace Scout',
        'SwiteScraper',
        'Symfony2 BrowserKit',
        'SynHttpClient-Built',
        'Sysomos',
        'T0PHackTeam',
        'Tarantula\/',
        'Taringa UGC',
        'teoma',
        'terrainformatica\.com',
        'Test Certificate Info',
        'Tetrahedron\/[0-9]',
        'The Drop Reaper',
        'The Expert HTML Source Viewer',
        'theinternetrules',
        'theoldreader\.com',
        'Thumbshots',
        'ThumbSniper',
        'TinEye',
        'Tiny Tiny RSS',
        'topster',
        'touche.com',
        'Traackr.com',
        'truwoGPS',
        'tweetedtimes\.com',
        'Tweetminster',
        'Tweezler\/',
        'Twikle',
        'Twingly',
        'ubermetrics-technologies',
        'uclassify',
        'UdmSearch',
        'Untiny',
        'UnwindFetchor',
        'updated',
        'Upflow',
        'URLChecker',
        'URLitor.com',
        'urlresolver',
        'Urlstat',
        'UrlTrends Ranking Updater',
        'Vagabondo',
        'vBSEO',
        'via ggpht\.com GoogleImageProxy',
        'VidibleScraper\/',
        'visionutils',
        'vkShare',
        'voltron',
        'voyager\/',
        'VSAgent\/[0-9]',
        'VSB-TUO\/[0-9]',
        'VYU2',
        'w3af\.org',
        'W3C-checklink',
        'W3C-mobileOK',
        'W3C_I18n-Checker',
        'W3C_Unicorn',
        'wangling',
        'WatchMouse',
        'WbSrch\/',
        'web-capture\.net',
        'Web-Monitoring',
        'Web-sniffer',
        'Webauskunft',
        'WebCapture',
        'WebClient\/',
        'webcollage',
        'WebCookies',
        'WebCorp',
        'WebDoc',
        'WebFetch',
        'WebImages',
        'WebIndex',
        'webkit2png',
        'webmastercoffee',
        'webmon ',
        'webscreenie',
        'Webshot',
        'Website Analyzer\/',
        'websitepulse[+ ]checker',
        'Websnapr\/',
        'Webthumb\/[0-9]',
        'WebThumbnail',
        'WeCrawlForThePeace',
        'WeLikeLinks',
        'WEPA',
        'WeSEE',
        'wf84',
        'wget',
        'WhatsApp',
        'WhatsMyIP',
        'WhatWeb',
        'WhereGoes\?',
        'Whibse',
        'Whynder Magnet',
        'Windows-RSS-Platform',
        'WinHttpRequest',
        'wkhtmlto',
        'wmtips',
        'Woko',
        'Word\/',
        'WordPress\/',
        'wotbox',
        'WP Engine Install Performance API',
        'wprecon\.com survey',
        'WPScan',
        'wscheck',
        'WWW-Mechanize',
        'www\.monitor\.us',
        'XaxisSemanticsClassifier',
        'Xenu Link Sleuth',
        'XING-contenttabreceiver\/[0-9]',
        'XmlSitemapGenerator',
        'xpymep([0-9]?)\.exe',
        'Y!J-(ASR|BSC)',
        'Yaanb',
        'yacy',
        'Yahoo Ad monitoring',
        'Yahoo Link Preview',
        'YahooCacheSystem',
        'YahooYSMcm',
        'YandeG',
        'yandex',
        'yanga',
        'yeti',
        ' YLT',
        'Yo-yo',
        'Yoleo Consumer',
        'yoogliFetchAgent',
        'YottaaMonitor',
        'yourls\.org',
        'Zao',
        'Zemanta Aggregator',
        'Zend\\\\Http\\\\Client',
        'Zend_Http_Client',
        'zgrab',
        'ZnajdzFoto',
        'ZyBorg',
        '[a-z0-9\-_]*((?<!cu)bot|crawler|archiver|transcoder|spider|uptime|validator|fetcher)',
    );
}

class Exclusions extends AbstractProvider
{
    /**
     * List of strings to remove from the user agent before running the crawler regex
     * Over a large list of user agents, this gives us about a 55% speed increase!
     *
     * @var array
     */
    protected $data = array(
        'Safari.[\d\.]*',
        'Firefox.[\d\.]*',
        'Chrome.[\d\.]*',
        'Chromium.[\d\.]*',
        'MSIE.[\d\.]',
        'Opera\/[\d\.]*',
        'Mozilla.[\d\.]*',
        'AppleWebKit.[\d\.]*',
        'Trident.[\d\.]*',
        'Windows NT.[\d\.]*',
        'Android [\d\.]*',
        'Macintosh.',
        'Ubuntu',
        'Linux',
        '[ ]Intel',
        'Mac OS X [\d_]*',
        '(like )?Gecko(.[\d\.]*)?',
        'KHTML,',
        'CriOS.[\d\.]*',
        'CPU iPhone OS ([0-9_])* like Mac OS X',
        'CPU OS ([0-9_])* like Mac OS X',
        'iPod',
        'compatible',
        'x86_..',
        'i686',
        'x64',
        'X11',
        'rv:[\d\.]*',
        'Version.[\d\.]*',
        'WOW64',
        'Win64',
        'Dalvik.[\d\.]*',
        ' \.NET CLR [\d\.]*',
        'Presto.[\d\.]*',
        'Media Center PC',
        'BlackBerry',
        'Build',
        'Opera Mini\/\d{1,2}\.\d{1,2}\.[\d\.]*\/\d{1,2}\.',
        'Opera',
        ' \.NET[\d\.]*',
        ';', // Remove the following characters ;
    );
}

class Headers extends AbstractProvider
{
    /**
     * All possible HTTP headers that represent the user agent string.
     *
     * @var array
     */
    protected $data = array(
        // The default User-Agent string.
        'HTTP_USER_AGENT',
        // Header can occur on devices using Opera Mini.
        'HTTP_X_OPERAMINI_PHONE_UA',
        // Vodafone specific header: http://www.seoprinciple.com/mobile-web-community-still-angry-at-vodafone/24/
        'HTTP_X_DEVICE_USER_AGENT',
        'HTTP_X_ORIGINAL_USER_AGENT',
        'HTTP_X_SKYFIRE_PHONE',
        'HTTP_X_BOLT_PHONE_UA',
        'HTTP_DEVICE_STOCK_UA',
        'HTTP_X_UCBROWSER_DEVICE_UA',
        // Sometimes, bots (especially Google) use a genuine user agent, but fill this header in with their email address
        'HTTP_FROM',
    );
}

class CrawlerDetect
{
    /**
     * The user agent.
     *
     * @var null
     */
    protected $userAgent = null;

    /**
     * Headers that contain a user agent.
     *
     * @var array
     */
    protected $httpHeaders = array();

    /**
     * Store regex matches.
     *
     * @var array
     */
    protected $matches = array();

    /**
     * Crawlers object.
     *
     * @var \Jaybizzle\CrawlerDetect\Fixtures\Crawlers
     */
    protected $crawlers;

    /**
     * Exclusions object.
     *
     * @var \Jaybizzle\CrawlerDetect\Fixtures\Exclusions
     */
    protected $exclusions;

    /**
     * Headers object.
     *
     * @var \Jaybizzle\CrawlerDetect\Fixtures\Headers
     */
    protected $uaHttpHeaders;

    /**
     * The compiled regex string.
     *
     * @var string
     */
    protected $compiledRegex;

    /**
     * The compiled exclusions regex string.
     *
     * @var string
     */
    protected $compiledExclusions;

    /**
     * Class constructor.
     */
    public function __construct(array $headers = null, $userAgent = null)
    {
        $this->crawlers = new Crawlers();
        $this->exclusions = new Exclusions();
        $this->uaHttpHeaders = new Headers();

        $this->compiledRegex = $this->compileRegex($this->crawlers->getAll());
        $this->compiledExclusions = $this->compileRegex($this->exclusions->getAll());

        $this->setHttpHeaders($headers);
        $this->setUserAgent($userAgent);
    }

    /**
     * Compile the regex patterns into one regex string.
     *
     * @param array
     * 
     * @return string
     */
    public function compileRegex($patterns)
    {
        return '('.implode('|', $patterns).')';
    }

    /**
     * Set HTTP headers.
     *
     * @param array|null $httpHeaders
     */
    public function setHttpHeaders($httpHeaders = null)
    {
        // Use global _SERVER if $httpHeaders aren't defined.
        if (! is_array($httpHeaders) || ! count($httpHeaders)) {
            $httpHeaders = $_SERVER;
        }

        // Clear existing headers.
        $this->httpHeaders = array();

        // Only save HTTP headers. In PHP land, that means
        // only _SERVER vars that start with HTTP_.
        foreach ($httpHeaders as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $this->httpHeaders[$key] = $value;
            }
        }
    }

    /**
     * Return user agent headers.
     *
     * @return array
     */
    public function getUaHttpHeaders()
    {
        return $this->uaHttpHeaders->getAll();
    }

    /**
     * Set the user agent.
     *
     * @param string|null $userAgent
     */
    public function setUserAgent($userAgent = null)
    {
        if (false === empty($userAgent)) {
            $this->userAgent = $userAgent;
        } else {
            $this->userAgent = null;
            foreach ($this->getUaHttpHeaders() as $altHeader) {
                if (false === empty($this->httpHeaders[$altHeader])) { // @todo: should use getHttpHeader(), but it would be slow.
                    $this->userAgent .= $this->httpHeaders[$altHeader].' ';
                }
            }

            $this->userAgent = (! empty($this->userAgent) ? trim($this->userAgent) : null);
        }
    }

    /**
     * Check user agent string against the regex.
     *
     * @param string|null $userAgent
     *
     * @return bool
     */
    public function isCrawler($userAgent = null)
    {
        $agent = $userAgent ?: $this->userAgent;

        $agent = preg_replace('/'.$this->compiledExclusions.'/i', '', $agent);

        if (strlen(trim($agent)) == 0) {
            return false;
        }

        $result = preg_match('/'.$this->compiledRegex.'/i', trim($agent), $matches);

        if ($matches) {
            $this->matches = $matches;
        }

        return (bool) $result;
    }

    /**
     * Return the matches.
     *
     * @return string|null
     */
    public function getMatches()
    {
        return isset($this->matches[0]) ? $this->matches[0] : null;
    }
}
