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
	
	/* @var \myersware\mapusers\geocoder\geocoder */
	protected $geocoder;
	
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
	 * @param \myersware\mapusers\geocoder\geocoder $geocoder
	 *          Geocoder object
	 */
	public function __construct(\phpbb\controller\helper $helper, \phpbb\template\template $template, 
			\phpbb\user $user, $php_ext, \myersware\mapusers\geocoder\geocoder $geocoder) {
		$this->helper = $helper;
		$this->template = $template;
		$this->user = $user;
		$this->php_ext = $php_ext;
		$this->geocoder = $geocoder;
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
					) ),
					'U_MAPUSERS_VIEW' => 1
			) );
		} else {
			$this->template->assign_vars ( array (
				'U_MAPUSERS_VIEW' => 0
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
		// determine if location field is modified. If not, return with no action
		$this->user->get_profile_fields ( $user_id );
		if ($this->user->profile_fields['pf_phpbb_location'] == $cp_data ['pf_phpbb_location']) {
			return;
		}
		$this->geocoder->update_user_geodata( $user_id, $cp_data ['pf_phpbb_location'] );
	}
	
	/**
	 * User profile UCP exit to lookup geo data if location known.
	 * $event contains: cp_data, data, sql_ary
	 *
	 * @param \phpbb\event\data $event
	 *        	Event object
	 */
	public function get_geo_data_ucp($event) {
		global $user;
		
		$cp_data = $event ['cp_data']; // custom profile data
		$data = $event ['data']; // user profile data
		$sql_ary = $event ['sql_ary']; // ignore
		// determine if location field is modified. If not, return with no action
		$this->user->get_profile_fields ( $user->data['user_id'] );
		if ($this->user->profile_fields['pf_phpbb_location'] == $cp_data ['pf_phpbb_location']) {
			return;
		}
		$this->geocoder->update_user_geodata( $user->data['user_id'], $cp_data ['pf_phpbb_location'] );
	}
}
