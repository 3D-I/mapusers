<?php

/**
 *
 * Map Forum Users. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2017, James Myers, myersware.com
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */
namespace myersware\mapusers\acp;

/**
 * Map Forum Users ACP module.
 */
class geo_module {
	public $page_title;
	public $tpl_name;
	public $u_action;
	
	public function main($id, $mode) {
		global $config, $request, $template, $user, $db;
		global $table_prefix;
		global $phpbb_container;
		
		$this->geocoder = $phpbb_container-> get ('myersware.mapusers.geocoder');
		$this->table_prefix = $table_prefix;
		$this->db = $db;
		$this->limit = $config ['mapusers_geocode_limit'];
		$this->api_key = $config ['mapusers_gapi_key'];
		$user->add_lang_ext ( 'myersware/mapusers', 'common' );
		$this->tpl_name = 'acp_geocode_body';
		$this->page_title = $user->lang ( 'Map Forum Geocode Users' );
		$this->updateCount = 0;
		
		// test call to geocoder class
		
		
		add_form_key ( 'myersware_mapusers_geocode' );
		
		if ($request->is_set_post ( 'submit' )) {
			if (! check_form_key ( 'myersware_mapusers_geocode' )) {
				trigger_error ( 'FORM_INVALID', E_USER_WARNING );
			}
			
			if (!$this->api_key || !$this->limit) {
				trigger_error ( 'GAPI_KEY_INVALID', E_USER_WARNING );
			}
			// do geocoding for users needing it
			$this->updateCount = $this->geocoder->update();
		}
		// display count of users without locations, with location and no geocode and with both.
		
		$sql = 'SELECT count(*) as c FROM ' . $table_prefix . 'profile_fields_data p ' . ' WHERE p.pf_phpbb_location is null';
		$result = $this->db->sql_query ( $sql );
		$row = $this->db->sql_fetchrow ( $result );
		$no_location = $row ['c'];
		$this->db->sql_freeresult ( $result );
		
		$sql = 'SELECT COUNT(*) as c FROM ' . $table_prefix . 'profile_fields_data p ' . 
			'LEFT JOIN ' . $table_prefix . 'mapusers_geolocation g ' . 'ON p.user_id=g.user_id' . 
			' WHERE g.is_valid=0 OR g.user_id IS NULL';
		$result = $this->db->sql_query ( $sql );
		$row = $this->db->sql_fetchrow ( $result );
		$loc_no_geo = $row ['c'];
		$this->db->sql_freeresult ( $result );
		
		$sql = 'SELECT count(*) as c FROM ' . $table_prefix . 'profile_fields_data p, ' . 
				$table_prefix . 'mapusers_geolocation g' . 
			' WHERE g.is_valid=1 AND g.user_id=p.user_id AND p.pf_phpbb_location is not null';
		$result = $this->db->sql_query ( $sql );
		$row = $this->db->sql_fetchrow ( $result );
		$loc_geo = $row ['c'];
		$this->db->sql_freeresult ( $result );
		
		if (!$this->api_key || !$this->limit || $loc_no_geo == 0) {
			$submit = false;
		} else {
			$submit = true;
		}
		$template->assign_vars ( array (
				'U_ACTION' => $this->u_action,
				'U_GEO_STATUS' => 'Geocoding status',
				'U_NO_LOC' => $no_location,
				'U_LOC_NO_GEO' => $loc_no_geo,
				'U_LOC_GEO' => $loc_geo,
				'U_LOC_LIMIT' => $this->limit,
				'U_LOC_UPDATES' => $this->updateCount,
				'U_LOC_SUBMIT' => ($submit ? '1': '0')
		) );
	}
}
