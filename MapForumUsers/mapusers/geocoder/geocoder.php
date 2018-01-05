<?php

/**
 *
 * Map Forum Users. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2018, James Myers, myersware.com
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */
namespace myersware\mapusers\geocoder;

/**
 * Map Forum Users Geocoding module.
 */
class geocoder {
	
	/**
	 * User object
	 * @var \phpbb\user
	 */
	protected $user;
	
	/**
	 * config object
	 * @var \phpbb\config\config
	 */
	protected $config;
	
	
	/**
	 * Constructor
	 *
	 * @param \phpbb\template\template $template Template object
	 * @param \phpbb\user $user User object
	 * @param \phpbb\config\config $config Config object
	 */
	public function __construct(\phpbb\user $user, 
			\phpbb\config\config $config)
	{
		$this->user = $user;
		$this->config = $config;
	}
	
	public function update($id, $mode) {
		global $config, $request, $template, $user, $db;
		global $table_prefix;
		
		$this->table_prefix = $table_prefix;
		$this->db = $db;
		$this->limit = 25;
		$api_key = $config ['mapusers_gapi_key'];
		$this->updateCount = 0;

		// do geocoding for users needing it
		$sql = 'SELECT p.user_id, p.pf_phpbb_location FROM ' . $table_prefix . 'profile_fields_data p ' . 
			'LEFT JOIN ' . $table_prefix . 'mapusers_geolocation g ' . 'ON p.user_id=g.user_id' . 
			' WHERE g.is_valid=0 OR g.user_id IS NULL LIMIT ' . $this->limit;
		$result = $this->db->sql_query ( $sql );
		$rowCount = 0;
		while ( $row = $this->db->sql_fetchrow ( $result ) ) {
			$rowCount++;
			$addressRaw = $row ['pf_phpbb_location'];
			$address = urlencode ( $addressRaw );
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
			// record may be in database, so try UPDATE first. If that fails, do INSERT
			$update = 'UPDATE ' . $table_prefix . 'mapusers_geolocation ' .
					' SET latitude=' . $geocode ['geometry'] ['location'] ['lat'] .
					', longitude=' . $geocode ['geometry'] ['location'] ['lng'] .
					', location="' . $db->sql_escape ( $addressRaw ) . '"' .
					', is_valid=1 WHERE user_id=' . $row ['user_id'];
			$this->db->sql_query ( $update );
			if (!$this->db->sql_affectedrows())
			{
				$insert = 'INSERT INTO ' . $table_prefix . 'mapusers_geolocation ' . 
					'(user_id, latitude, longitude, location, is_valid) VALUES(' . 
					$row ['user_id'] . ', ' . $geocode ['geometry'] ['location'] ['lat'] . ', ' . 
					$geocode ['geometry'] ['location'] ['lng'] . ', "' . 
					$db->sql_escape ( $addressRaw ) . '", ' . '1)';
					$this->db->sql_query ( $insert );
			}
		}
		$this->updateCount = $rowCount;
	}
	
	public function update_geo_data($user_id, $user_row, $cp_data) {
		global $config;
		global $table_prefix;
		global $db;
		
		$this->table_prefix = $table_prefix;
		$this->db = $db;
		
		// print_r ( 'update_geo_data' );
		// determine if location field is modified. If not, return with no action
		$this->user->get_profile_fields ( $user_id );
		if ($this->user->profile_fields['pf_phpbb_location'] == $cp_data ['pf_phpbb_location']) {
			return;
		}
		if ($cp_data ['pf_phpbb_location'] == null) {
			// delete any geo data for this user
			$delete = 'DELETE FROM ' . $table_prefix . 'mapusers_geolocation ' .
					'WHERE user_id=' . $user_id;
			$this->db->sql_query ( $delete );
		} else {
			// insert/update geo data for this user
			$api_key = $config ['mapusers_gapi_key'];
			$addressRaw = $cp_data ['pf_phpbb_location'];
			$address = urlencode ( $addressRaw );
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
			// record may be in database, so try UPDATE first. If that fails, do INSERT
			$update = 'UPDATE ' . $table_prefix . 'mapusers_geolocation ' .
					' SET latitude=' . $geocode ['geometry'] ['location'] ['lat'] .
					', longitude=' . $geocode ['geometry'] ['location'] ['lng'] .
					', location="' . $db->sql_escape ( $addressRaw ) . '"' .
					', is_valid=1 WHERE user_id=' . $user_id;
			$this->db->sql_query ( $update );
			if (!$this->db->sql_affectedrows())
			{
				$insert = 'INSERT INTO ' . $table_prefix . 'mapusers_geolocation ' .
						'(user_id, latitude, longitude, is_valid, location) VALUES(' . $user_id . ', ' .
						$geocode ['geometry'] ['location'] ['lat'] . ', ' .
						$geocode ['geometry'] ['location'] ['lng'] . ',' . '1, "' .
						$db->sql_escape ( $addressRaw ) . '")';
						$this->db->sql_query ( $insert );
			}
		}
	}
}
