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
		global $table_prefix;
		global $db;
		
		$this->user = $user;
		$this->config = $config;
		$this->table_prefix = $table_prefix;
		$this->db = $db;
		$this->api_key = $config ['mapusers_gapi_key'];
		$this->limit = $config['mapusers_geocode_limit'];
	}
	
	public function update() {

		$this->updateCount = 0;

		// do geocoding for users needing it
		$sql = 'SELECT p.user_id, p.pf_phpbb_location FROM ' . $this->table_prefix . 'profile_fields_data p ' . 
				'LEFT JOIN ' . $this->table_prefix . 'mapusers_geolocation g ' . 'ON p.user_id=g.user_id' . 
			' WHERE g.is_valid=0 OR g.user_id IS NULL LIMIT ' . $this->limit;
		$result = $this->db->sql_query ( $sql );
		$rowCount = 0;
		while ( $row = $this->db->sql_fetchrow ( $result ) ) {
			$rowCount++;
			$location = $row ['pf_phpbb_location'];
			$this->update_user_geodata($row['user_id'], $location);
		}
		$this->updateCount = $rowCount;
		return $this->updateCount;
	}
	
	public function geocode_user($location) {
		$address = urlencode ( $location );
		$geocode_url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . $address . "&key=" . $this->api_key;
		$ch = curl_init ();
		$options = array (
				CURLOPT_URL => $geocode_url,
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_TIMEOUT => 100,
				CURLOPT_SSL_VERIFYHOST => 0,
				CURLOPT_SSL_VERIFYPEER => false
		);
		curl_setopt_array ( $ch, $options );
		$response = curl_exec ( $ch );
		$httpcode = curl_getinfo ( $ch, CURLINFO_HTTP_CODE );
		if ($ce = curl_errno ( $ch ) || $httpcode != 200) {
			throw new \phpbb\exception\http_exception ( 500, "Location " . $address . " curl failed=" . curl_strerror ( $ce ) . " " . $httpcode . " url=" . $geocode_url );
		}
		curl_close ( $ch );
		// var_dump('geocode curl=' . $response);
		$data = json_decode ( $response, true );
		// from geometry.location.lat/lng
		$geocode = $data ['results'] [0];
		$geo_data = array (
				'lat' => $geocode ['geometry'] ['location'] ['lat'],
				'lng' => $geocode ['geometry'] ['location'] ['lng']
		);
		// var_dump($geo_data);
		return $geo_data;
	}
	
	public function update_user_geodata($user_id, $location) {
		
		if ($location == null) {
			// delete any geo data for this user
			$delete = 'DELETE FROM ' . $this->table_prefix . 'mapusers_geolocation ' .
					'WHERE user_id=' . $user_id;
			$this->db->sql_query ( $delete );
		} else {
			// insert/update geo data for this user
			$geo_data = $this->geocode_user($location);
			// record may be in database, so try UPDATE first. If that fails, do INSERT
			$update = 'UPDATE ' . $this->table_prefix . 'mapusers_geolocation ' .
					' SET latitude=' . $geo_data['lat'] .
					', longitude=' . $geo_data['lng'] .
					', location="' . $this->db->sql_escape ( $location ) . '"' .
					', is_valid=1 WHERE user_id=' . $user_id;
			$this->db->sql_query ( $update );
			if (!$this->db->sql_affectedrows())
			{
				$insert = 'INSERT INTO ' . $this->table_prefix . 'mapusers_geolocation ' .
						'(user_id, latitude, longitude, is_valid, location) VALUES(' . $user_id . ', ' .
						$geo_data['lat'] . ', ' .
						$geo_data['lng'] . ',' . '1, "' .
						$this->db->sql_escape ( $location ) . '")';
						$this->db->sql_query ( $insert );
			}
		}
	}
}
