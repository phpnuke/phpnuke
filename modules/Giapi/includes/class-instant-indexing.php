<?php
/**
 * Main plugin class.
 *
 * @package Instant Indexing
 * @since 1.0.0
 * @author Rank Math
 * @link https://rankmath.com
 * @license GNU General Public License 3.0+
 */
class RM_GIAPI {

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	public $version = '1.1.13';

	/**
	 * Holds the admin menu hook suffix for the "dummy" dashboard.
	 *
	 * @var string
	 */
	public $dashboard_menu_hook_suffix = '';

	/**
	 * Holds the admin menu hook suffix for Rank Math > Instant Indexing
	 *
	 * @var string
	 */
	public $menu_hook_suffix = '';

	/**
	 * The default tab when visiting the admin page.
	 *
	 * @var string
	 */
	public $default_nav_tab = '';

	/**
	 * Holds the current admin tab.
	 *
	 * @var string
	 */
	public $current_nav_tab = '';

	/**
	 * Holds the admin tabs.
	 *
	 * @var array
	 */
	public $nav_tabs = [];

	/**
	 * Holds the admin notice messages.
	 *
	 * @var array
	 */
	public $notices = [];

	/**
	 * Holds the default settings.
	 *
	 * @var array
	 */
	public $settings_defaults = [];

	/**
	 * Debug mode. Enable with define( 'GIAPI_DEBUG', true );
	 *
	 * @var bool
	 */
	public $debug = false;

	/**
	 * Check if Rank Math SEO is installed.
	 *
	 * @var bool
	 */
	public $is_rm_active = false;

	/**
	 * Rank Math Instant Indexing API.
	 *
	 * @var object
	 */
	public $rmapi = null;

	/**
	 * URL of the Google plugin setup guide on rankmath.com.
	 *
	 * @var string
	 */
	public $google_guide_url = 'https://developers.google.com/search/apis/indexing-api/v3/prereqs';

	/**
	 * Constructor method.
	 */
	 
	public function __construct() {
		global $hooks, $nuke_configs, $action;
		$this->debug             = ( defined( 'GIAPI_DEBUG' ) && GIAPI_DEBUG );

		$this->settings_defaults = [
			'json_key'		=> '',
			'post_types'	=> [],
		];
		
		if(isset($action) && $action != '')
		{
			switch($action){
				case"rm_giapi":
					$hooks->add_action( 'run_admin_plugins', [ $this, 'ajax_rm_giapi' ], 10 );
				break;
				case"rm_giapi_limits":
					$hooks->add_action( 'run_admin_plugins', [ $this, 'ajax_get_limits' ], 10 );
				break;
			}
		}
		
		$hooks->add_action( 'post_save_finish', [ $this, 'publish_post' ], 10 );
		$hooks->add_action( 'publish_post', [ $this, 'publish_post' ], 10 );
		$hooks->add_action( 'delete_post', [ $this, 'delete_post' ], 10);

		$hooks->add_action( 'admin_init', [ $this, 'handle_clear_history' ] );
	}

	/**
	 * Submit one or more URLs to Google's API using their API library.
	 *
	 * @param  array  $url_input URLs.
	 * @param  string $action    API action.
	 * @return array  $data      Result of the API call.
	 */
	private function send_to_api( $url_input, $action, $is_manual = true ) {
		$url_input  = (array) $url_input;
		$urls_count = count( $url_input );

		include_once 'modules/Giapi/includes/vendor/autoload.php';
		$this->client = new Google_Client();
		$this->client->setAuthConfig( json_decode( $this->get_setting( 'json_key' ), true ) );
		$this->client->setConfig( 'base_path', 'https://indexing.googleapis.com' );
		$this->client->addScope( 'https://www.googleapis.com/auth/indexing' );

		// Batch request.
		$this->client->setUseBatch( true );
		// init google batch and set root URL.
		$service = new Google_Service_Indexing( $this->client );
		$batch   = new Google_Http_Batch( $this->client, false, 'https://indexing.googleapis.com' );

		foreach ( $url_input as $i => $url ) {
			$post_body = new Google_Service_Indexing_UrlNotification();
			if ( $action === 'getstatus' ) {
				$request_part = $service->urlNotifications->getMetadata( [ 'url' => $url ] ); // phpcs:ignore
			} else {
				$post_body->setType( $action === 'update' ? 'URL_UPDATED' : 'URL_DELETED' );
				$post_body->setUrl( $url );
				$request_part = $service->urlNotifications->publish( $post_body ); // phpcs:ignore
			}
			$batch->add( $request_part, 'url-' . $i );
		}

		$results   = $batch->execute();
		
		$data      = [];
		$res_count = count( $results );
		foreach ( $results as $id => $response ) {
			// Change "response-url-1" to "url-1".
			$local_id = substr( $id, 9 );
			if ( is_a( $response, 'Google_Service_Exception' ) ) {
				$data[ $local_id ] = json_decode( $response->getMessage() );
			} else {
				$data[ $local_id ] = (array) $response->toSimpleObject();
			}
			if ( $res_count === 1 ) {
				$data = $data[ $local_id ];
			}
		}

		$this->log_request( $action, $urls_count );

		if ( $this->debug ) {
			$data = objectToArray($data);
			$message = (isset($data['error']) && isset($data['error']['message'])) ? $data['error']['message']:"";
			add_log(
				sprintf(
					(($message == "") ? _GIAPI_SEND_TO_API_LOG_SUCCESS:_GIAPI_SEND_TO_API_LOG_ERROR), 
					$action, 
					$url_input[0] . ( count( $url_input ) > 1 ? ' (+)' : '' ), 
					$message
				),
				1
			);
		}

		return $data;
	}

	/**
	 * Log request type & timestamp to keep track of remaining quota.
	 *
	 * @param  string $type API action.
	 * @param  int    $number Number of URLs.
	 * @return void
	 */
	private function log_request( $type, $number = 1 ) {

		$requests_log = $this->get_setting(
			'giapi_requests',
			[
				'update'      => [],
				'delete'      => [],
				'getstatus'   => [],
			]
		);

		if ( ! isset( $requests_log[ $type ] ) ) {
			$requests_log[ $type ] = [];
		}

		$add = array_fill( 0, $number, time() );
		$requests_log[ $type ] = array_merge( $requests_log[ $type ], $add );
		if ( count( $requests_log[ $type ] ) > 600 ) {
			$requests_log[ $type ] = array_slice( $requests_log[ $type ], -600, 600, true );
		}
		
		$this->update_setting( 'giapi_requests', $requests_log );
	}

	/**
	 * Get current quota (limits minus usage).
	 *
	 * @return array Current quota.
	 */
	private function get_limits() {
		global $nuke_configs;
		$current_limits = [
			'publishperday' => 0,
			'permin'        => 0,
			'metapermin'    => 0,
		];

		$limit_publishperday = 200;
		$limit_permin        = 600;
		$limit_metapermin    = 180;

		$limit_bingsubmitperday = 10;

		$requests_log = $this->get_setting(
			'giapi_requests',
			[
				'update'      => [],
				'delete'      => [],
				'getstatus'   => [],
			]
		);
		
		$timestamp_1day_ago = strtotime( '-1 day' );
		$timestamp_1min_ago = strtotime( '-1 minute' );

		$publish_1day = 0;
		$all_1min     = 0;
		$meta_1min    = 0;

		foreach ( $requests_log['update'] as $time ) {
			if ( $time > $timestamp_1day_ago ) {
				$publish_1day++;
			}
			if ( $time > $timestamp_1min_ago ) {
				$all_1min++;
			}
		}
		foreach ( $requests_log['delete'] as $time ) {
			if ( $time > $timestamp_1min_ago ) {
				$all_1min++;
			}
		}
		foreach ( $requests_log['getstatus'] as $time ) {
			if ( $time > $timestamp_1min_ago ) {
				$all_1min++;
				$meta_1min++;
			}
		}

		$current_limits['publishperday'] = $limit_publishperday - $publish_1day;
		$current_limits['permin']        = $limit_permin - $all_1min;
		$current_limits['metapermin']    = $limit_metapermin - $meta_1min;

		$current_limits['publishperday_max'] = $limit_publishperday;
		$current_limits['permin_max']        = $limit_permin;
		$current_limits['metapermin_max']    = $limit_metapermin;

		return $current_limits;
	}

	/**
	 * AJAX handler to get current quota in JSON format.
	 *
	 * @return void
	 */
	public function ajax_get_limits() {
		header( 'Content-type: application/json' );
		$limits = json_encode( $this->get_limits() );
		die($limits);
	}

	/**
	 * AJAX handler for the console.
	 *
	 * @return void
	 */
	public function ajax_rm_giapi() {
		global $api_action;
		$url_input = $this->get_input_urls();
		$action    = sanitize( filter( $api_action, "nohtml" ) );
		header( 'Content-type: application/json' );

		$result = $this->send_to_api( $url_input, $action, true );
		$result = json_encode( $result );
		die($result);
	}
	
	/**
	 * Normalize input URLs.
	 *
	 * @return array Input URLs.
	 */
	public function get_input_urls() {
		global $url;
		return array_values( array_filter( array_map( 'trim', explode( "\n", str_replace("\r","", $url ) ) ) ) );
	}
	
	/**
	 * Output Indexing API Console page contents.
	 *
	 * @return void
	 */
	public function show_console() {
		global $nuke_configs, $admin_file;
		$limits = $this->get_limits();
		$urls   = $nuke_configs['nukeurl'];
		
		$contents = '';//reload jquery function is need in jquery yi tabs
		$contents .= jquery_codes_load('', true);//reload jquery function is need in jquery yi tabs
		$contents .= "
		<form method=\"post\" action=\"".$admin_file.".php\">
			<table width=\"100%\" class=\"id-form product-table no-border\">
				<tr>
					<th style=\"width:200px;\">Google JSON Key</th>
					<td>
						<textarea name=\"config_fields[giapi_settings][json_key]\" class=\"form-textarea\" style=\"height: 100px; width: 400px;direction:ltr;padding:10px;\">".$this->get_setting( 'json_key' )."</textarea>
						".bubble_show("<a href=\"".$this->google_guide_url."\" target=\"_blank\">"._GIAPI_READ_OUR_GUIDE."</a>")."
					</td>
				</tr>
				<tr>
					<th>"._GIAPI_AUTO_SUBMIT_POSTS."</th>
					<td>
						".$this->post_types_checkboxes()."
					</td>
				</tr>
				<tr>
					<td colspan=\"2\">
						<input type=\"hidden\" name=\"op\" value=\"save_configs\">
						<input type=\"hidden\" name=\"return_op\" value=\"settings#giapi\">
						<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" />
						<input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\" class=\"form-submit\" />
					</td>
				</tr>
			</table>
		</form>";
		if ( $this->get_setting( 'json_key' ) ) {
			$contents .= "
			<form id=\"instant-indexing\" method=\"post\" action=\"".$admin_file.".php\">
				<table width=\"100%\" class=\"id-form product-table no-border\">
				<tr>
					<th>"._GIAPI_MANUAL_SUBMIT."</th>						
					<td>
						<textarea name=\"url\" id=\"giapi-url\" class=\"form-textarea\" style=\"height: 100px; width: 400px;direction:ltr;padding:10px;\">".$urls."</textarea>
						".bubble_show(sprintf(_GIAPI_MAX_URLS, 100))."
					</td>
				</tr>
				<tr>
					<th></th>						
					<td style=\"line-height: 1.8\">
						<div>PublishRequestsPerDayPerProject = <strong id=\"giapi-limit-publishperday\">".intval($limits['publishperday'])."</strong> / ".intval($limits['publishperday_max'])."<br>
						RequestsPerMinutePerProject = <strong id=\"giapi-limit-permin\">".intval($limits['permin'])."</strong> / ".intval($limits['permin_max'])."<br>
						MetadataRequestsPerMinutePerProject = <strong id=\"giapi-limit-metapermin\">".intval($limits['metapermin'])."</strong> / ".intval($limits['metapermin_max'])."</div>
						".bubble_show("<a href=\"https://developers.google.com/search/apis/indexing-api/v3/quota-pricing\" target=\"_blank\">"._GIAPI_GOOGLEAPI_REM_QUOTA."</a>")."
					</td>
				</tr>
				<tr>
					<th>"._GIAPI_SUBMIT_ACTION."</th>						
					<td>
						<input type=\"radio\" name=\"api_action\" value=\"update\" class=\"giapi-action styled\" data-label=\""._GIAPI_SUBMIT_ACTION_PUBLISH_UPDATE."\">
						<input type=\"radio\" name=\"api_action\" value=\"remove\" class=\"giapi-action styled\" data-label=\""._GIAPI_SUBMIT_ACTION_REMOVE."\">
						<input type=\"radio\" name=\"api_action\" value=\"getstatus\" class=\"giapi-action styled\" data-label=\""._GIAPI_SUBMIT_ACTION_STATUS."\">
					</td>
				</tr>
				<tr>
					<td colspan=\"2\">
						<div id=\"giapi-response-userfriendly\" class=\"not-ready\">
							<div class=\"response-box\">
								<code class=\"response-id\"></code>
								<h4 class=\"response-status\"></h4>
								<p class=\"response-message\"></p>
							</div>							
							<a href=\"#\" id=\"giapi-response-trigger\"> "._GIAPI_SEE_RESPONSE."</a>
						</div>	
						<div id=\"giapi-response-wrapper\">
							<textarea id=\"giapi-response\" class=\"form-textarea\" style=\"height: 100px; width: 98%;direction:ltr;padding:10px;\" placeholder=\"Response...\"></textarea>
						</div>
					</td>
				</tr>
				<tr>				
					<td colspan=\"2\">
						<input type=\"hidden\" name=\"op\" value=\"others_config\">
						<input type=\"hidden\" name=\"other_admin_config\" value=\"giapi\">
						<input type=\"hidden\" name=\"return_op\" value=\"settings#giapi\">
						<input type=\"hidden\" name=\"csrf_token\" id=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" />
						<input type=\"submit\" id=\"giapi-submit\" class=\"form-submit\" value=\"Send to API\" disabled=\"disabled\">
					</td>
				</tr>
			</table>
			</form>";
		}
		
		return $contents;
	}

	/**
	 * Get settings.
	 */
	private function get_settings() {
		global $nuke_configs;
		$settings = (isset($nuke_configs['giapi_settings']) && is_serialized($nuke_configs['giapi_settings'])) ? phpnuke_unserialize($nuke_configs['giapi_settings']):[];
		$settings = array_merge( $this->settings_defaults, $settings );

		return $settings;
	}

	/**
	 * Output checkbox inputs for the registered post types.
	 *
	 * @param string $api API provider: "google" or "bing".
	 * @return void
	 */
	private function post_types_checkboxes() {
		global $all_post_types;
		$contents = '';
		$settings   = $this->get_setting( 'post_types', [] );
		foreach ( $all_post_types as $post_type => $post_type_label) {
			$sel = (isset($settings[$post_type]) && $settings[$post_type] == 1) ? "checked":"";
			$contents .= "<input type=\"checkbox\" class=\"styled\" name=\"config_fields[giapi_settings][post_types][".$post_type."]\" data-label=\"$post_type_label\" value=\"1\" $sel />";
		}
		return $contents;
	}

	/**
	 * Get a specific plugin setting.
	 *
	 * @param  string $setting Setting name.
	 * @param  string $default Default value if setting is not found.
	 * @return mixed  Setting value or default.
	 */
	private function get_setting( $setting, $default = null ) {
		$settings = $this->get_settings();
		return ( isset( $settings[ $setting ] ) ? $settings[ $setting ] : $default );
	}
	
	/**
	 * Get a specific post data.
	 *
	 * @param  int $sid post id.
	 */
	private function get_post( $sid ) {
		global $db;
		$result = $db->table(POSTS_TABLE)
				->where('sid', $sid)
				->select(['title', 'post_url', 'time', 'cat_link', 'post_type', 'status']);
		$row = [];
		if($result->count() == 1)
		{
			$row = $result->results()[0];
			$row['post_url_full'] = LinkToGT(articleslink($sid, $row['title'], $row['post_url'], $row['time'], $row['cat_link'], $row['post_type']));
		}
		return $row;
	}	
	
	/**
	 * Set a specific plugin setting.
	 *
	 * @param  string $setting Setting name.
	 * @param  string $default Default value if setting is not found.
	 * @return mixed  Setting value or default.
	 */
	private function update_setting( $setting, $default = null ) {
		$settings = $this->get_settings();
		$settings[$setting] = $default;
		update_configs('giapi_settings', $settings);
	}

	/**
	 * When a post from a watched post type is published, submit its URL
	 * to the API and add notice about it.
	 *
	 * @param  int $post_id Post ID.
	 * @return void
	 */
	public function publish_post( $sid ) {
		$row = $this->get_post($sid);
		
		$post_types = $this->get_setting( 'post_types', [] );
		
		if ( ! isset( $post_types[$row['post_type']]) ) {
			return;
		}
		
		if ( $row['status'] !== 'publish' ) {
			return;
		}

		// Early exit if filter is set to false.
		if ( ! $row['post_url_full'] ) {
			return;
		}
		
		$this->send_to_api( $row['post_url_full'], 'update', false );
	}

	/**
	 * When a post is deleted, check its post type and submit its URL
	 * to the API if appropriate, then add notice about it.
	 *
	 * @param  int $post_id Post ID.
	 * @return void
	 */
	public function delete_post( $sid ) {
		$row = $this->get_post($sid);
		
		$post_types = $this->get_setting( 'post_types', [] );

		if ( ! isset( $post_types[$row['post_type']]) ) {
			return;
		}
		
		// Early exit if filter is set to false.
		if ( ! $row['post_url_full'] ) {
			return;
		}

		$this->send_to_api( $row['post_url_full'], 'delete', false );
	}
}
