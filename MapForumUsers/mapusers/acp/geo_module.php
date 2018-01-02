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
		
		$this->table_prefix = $table_prefix;
		$this->db = $db;
		$api_key = $config ['mapusers_gapi_key'];
		$user->add_lang_ext ( 'myersware/mapusers', 'common' );
		$this->tpl_name = 'acp_geocode_body';
		$this->page_title = $user->lang ( 'Map Forum Geocode Users' );
		
		add_form_key ( 'myersware_mapusers_geocode' );
		
		if ($request->is_set_post ( 'submit' )) {
			if (! check_form_key ( 'myersware_mapusers_geocode' )) {
				trigger_error ( 'FORM_INVALID', E_USER_WARNING );
			}
			// do geocoding for users needing it
			$sql = 'SELECT p.user_id, p.pf_phpbb_location FROM ' . $table_prefix . 'profile_fields_data p ' . 'LEFT JOIN ' . $table_prefix . 'mapusers_geolocation g ' . 'ON p.user_id=g.user_id' . ' WHERE g.user_id IS NULL';
			$result = $this->db->sql_query ( $sql );
			while ( $row = $this->db->sql_fetchrow ( $result ) ) {
				$address = urlencode ( $row ['pf_phpbb_location'] );
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
				$insert = 'INSERT INTO ' . $table_prefix . 'mapusers_geolocation ' . '(user_id, latitude, longitude, location, is_valid) VALUES(' . $row ['user_id'] . ', ' . $geocode ['geometry'] ['location'] ['lat'] . ', ' . $geocode ['geometry'] ['location'] ['lng'] . ', "' . $db->sql_escape ( $address ) . '", ' . '1)';
				$insert_result = $this->db->sql_query ( $insert );
				$this->db->sql_freeresult ( $insert_result );
			}
			$this->db->sql_freeresult ( $result );
		}
		// display count of users without locations, with location and no geocode and with both.
		
		$sql = 'SELECT count(*) as c FROM ' . $table_prefix . 'profile_fields_data p ' . ' WHERE p.pf_phpbb_location is null';
		$result = $this->db->sql_query ( $sql );
		$row = $this->db->sql_fetchrow ( $result );
		$no_location = $row ['c'];
		$this->db->sql_freeresult ( $result );
		
		$sql = 'SELECT COUNT(*) as c FROM ' . $table_prefix . 'profile_fields_data p ' . 'LEFT JOIN ' . $table_prefix . 'mapusers_geolocation g ' . 'ON p.user_id=g.user_id' . ' WHERE g.user_id IS NULL';
		$result = $this->db->sql_query ( $sql );
		$row = $this->db->sql_fetchrow ( $result );
		$loc_no_geo = $row ['c'];
		$this->db->sql_freeresult ( $result );
		
		$sql = 'SELECT count(*) as c FROM ' . $table_prefix . 'profile_fields_data p, ' . $table_prefix . 'mapusers_geolocation g' . ' WHERE g.user_id=p.user_id AND p.pf_phpbb_location is not null';
		$result = $this->db->sql_query ( $sql );
		$row = $this->db->sql_fetchrow ( $result );
		$loc_geo = $row ['c'];
		$this->db->sql_freeresult ( $result );
		
		$template->assign_vars ( array (
				'U_ACTION' => $this->u_action,
				'U_GEO_STATUS' => 'Geocoding status',
				'U_NO_LOC' => $no_location,
				'U_LOC_NO_GEO' => $loc_no_geo,
				'U_LOC_GEO' => $loc_geo 
		) );
	}
}
