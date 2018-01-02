<?php

/**
 *
 * Map Forum Users. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2018, James Myers, myersware.com
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */
namespace myersware\mapusers\event;

/**
 *
 * @ignore
 */
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Map Forum Users Event listener.
 */
class main_listener implements EventSubscriberInterface {
	static public function getSubscribedEvents() {
		return array (
				'core.permissions' => 'set_permissions',
				'core.user_setup' => 'load_language_on_setup',
				'core.page_header' => 'add_page_header_link',
				'core.ucp_profile_info_modify_sql_ary' => 'get_geo_data_ucp',
				'core.acp_users_profile_modify_sql_ary' => 'get_geo_data_acp' 
		);
	}
	
	/* @var \phpbb\controller\helper */
	protected $helper;
	
	/* @var \phpbb\template\template */
	protected $template;
	
	/* @var \phpbb\user */
	protected $user;
	
	/** @var string phpEx */
	protected $php_ext;
	
	/**
	 * Constructor
	 *
	 * @param \phpbb\controller\helper $helper
	 *        	Controller helper object
	 * @param \phpbb\template\template $template
	 *        	Template object
	 * @param \phpbb\user $user
	 *        	User object
	 * @param string $php_ext
	 *        	phpEx
	 */
	public function __construct(\phpbb\controller\helper $helper, \phpbb\template\template $template, \phpbb\user $user, $php_ext) {
		$this->helper = $helper;
		$this->template = $template;
		$this->user = $user;
		$this->php_ext = $php_ext;
	}
	
	/**
	 * Load common language files during user setup
	 *
	 * @param \phpbb\event\data $event
	 *        	Event object
	 */
	public function load_language_on_setup($event) {
		$lang_set_ext = $event ['lang_set_ext'];
		$lang_set_ext [] = array (
				'ext_name' => 'myersware/mapusers',
				'lang_set' => 'common' 
		);
		$event ['lang_set_ext'] = $lang_set_ext;
	}
	
	/**
	 * Load permissions during user setup
	 *
	 * @param \phpbb\event\data $event
	 *        	Event object
	 */
	public function set_permissions($event) {
		$permissions = $event ['permissions'];
		$permissions ['u_mapusers_view'] = array (
				'lang' => 'ACL_U_MAPUSERS_VIEW',
				'cat' => 'profile' 
		);
		$event ['permissions'] = $permissions;
	}
	
	/**
	 * Add a link to the controller in the forum navbar
	 */
	public function add_page_header_link() {
		global $auth;
		
		if ($auth->acl_get ( 'u_mapusers_view' )) {
			$this->template->assign_vars ( array (
					'U_MAPUSERS_PAGE' => $this->helper->route ( 'myersware_mapusers_controller_main', array (
							'name' => 'showmap' 
					) ) 
			) );
		}
	}
	
	/**
	 * User profile ACP exit to lookup geo data if location known.
	 * $event contains: cp_data, data, sql_ary, user_id, user_row
	 *
	 * @param \phpbb\event\data $event
	 *        	Event object
	 */
	public function get_geo_data_acp($event) {
		$cp_data = $event ['cp_data']; // custom profile data
		$data = $event ['data']; // full user data
		$sql_ary = $event ['sql_ary']; // ignore
		$user_id = $event ['user_id'];
		$user_row = $event ['user_row']; // regular user profile data
		update_geo_data ( $user_id, $user_row, $cp_data );
	}
	
	/**
	 * User profile UCP exit to lookup geo data if location known.
	 * $event contains: cp_data, data, sql_ary
	 *
	 * @param \phpbb\event\data $event
	 *        	Event object
	 */
	public function get_geo_data_ucp($event) {
		$cp_data = $event ['cp_data']; // custom profile data
		$data = $event ['data']; // user profile data
		$sql_ary = $event ['sql_ary']; // ignore
		update_geo_data ( $data ['user_id'], $data, $cp_data );
	}
	function update_geo_data($user_id, $user_row, $cp_data) {
		print_r ( 'update_geo_data' );
		if ($cp_data ['pf_phpbb_location'] == null) {
			// delete any geo data for this user
		} else {
			// insert/update geo data for this user
			$api_key = $config ['mapusers_gapi_key'];
			$address = urlencode ( $cp_data ['pf_phpbb_location'] );
			// $key = urlencode("************");
			$ch = curl_init ();
			$options = array (
					CURLOPT_URL => "https://maps.googleapis.com/maps/api/geocode/json?address=" . $address . "&key=" . $api_key,
					CURLOPT_RETURNTRANSFER => 1,
					CURLOPT_TIMEOUT => 100,
					CURLOPT_SSL_VERIFYHOST => 0,
					CURLOPT_SSL_VERIFYPEER => false 
			);
			curl_setopt_array ( $ch, $options );
			$response = curl_exec ( $ch );
			if (curl_error ( $ch )) {
				echo 'error:' . curl_error ( $ch );
			}
			curl_close ( $ch );
			// print_r($response);
			$data = json_decode ( $response, true ); // insert in the database
			                                      // from geometry.location.lat/lng
			$geocode = $data ['results'] [0];
			$insert = 'INSERT INTO ' . $table_prefix . 'mapusers_geolocation ' . '(user_id, latitude, longitude, is_valid, location) VALUES(' . $row ['user_id'] . ', ' . $geocode ['geometry'] ['location'] ['lat'] . ', ' . $geocode ['geometry'] ['location'] ['lng'] . ',' . '1, "' . $db->sql_escape ( $address ) . '")';
			$insert_result = $this->db->sql_query ( $insert );
			$this->db->sql_freeresult ( $insert_result );
		}
	}
}
