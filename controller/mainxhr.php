<?php

/**
 *
 * Map Forum Users. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2017, James Myers, myersware.com
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */
namespace myersware\mapusers\controller;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Map Forum Users xhr controller.
 */
class mainxhr {
	/* @var \phpbb\config\config */
	protected $config;
	
	/* @var \phpbb\controller\helper */
	protected $helper;
	
	/* @var \phpbb\request\request_interface */
	protected $request;
	
	/* @var \phpbb\user */
	protected $user;
	
	/* @var \phpbb\driver_interface */
	protected $db;
	
	/* @var \phpbb\auth\auth */
	protected $auth;
	
	/* @var \myersware\mapusers\geocoder\geocoder */
	protected $geocoder;
	
	/**
	 * Constructor
	 *
	 * @param \phpbb\config\config $config
	 * @param \phpbb\controller\helper $helper
	 * @param \phpbb\request\request_interface $request
	 * @param \phpbb\user $user
	 * @param \phpbb\db\driver\factory $db
	 * @param \phpbb\auth\auth $auth
	 * @param \myersware\mapusers\geocoder\geocoder $geocoder
	 */
	public function __construct(\phpbb\config\config $config, \phpbb\controller\helper $helper, 
			\phpbb\request\request_interface $request, \phpbb\user $user, \phpbb\db\driver\factory $db,
			\phpbb\auth\auth $auth,
			\myersware\mapusers\geocoder\geocoder $geocoder) {
		$this->config = $config;
		$this->helper = $helper;
		$this->request = $request;
		$this->user = $user;
		$this->auth = $auth;
		$this->db = $db;
		$this->geocoder = $geocoder;
	}
	
	/**
	 * Gcmap controller for route /mapusers/xhr/{name}
	 *
	 * @param string $name
	 *
	 * @return \Symfony\Component\HttpFoundation\JsonResponse A Symfony Response object
	 *        
	 *         Expects $name='searchUser' with no query string or qs='name=xxx&radius=nnnn'
	 *         or $name='searchLocation' with query string = 'address=xxxx&radius=nnnn'
	 */
	public function handle($name) {
		global $table_prefix;
		global $phpbb_container;
		
		if (! $this->auth->acl_get ( 'u_mapusers_view' )) {
			throw new \phpbb\exception\http_exception ( 403, "NOT_AUTHORISED" );
		}
		$this->table_prefix = $table_prefix;
		$api_key = $this->config ['mapusers_gapi_key'];
		$geo_table = $phpbb_container->getParameter ( 'myersware.mapusers.tables.mapusers' );
		$username = $this->request->variable ( 'name', $this->user->data ['username'] );
		$q_radius = (int)$this->request->variable ( 'radius', 100 );
		$q_limit = (int)$this->request->variable ( 'limit', 20 );
		$location = $this->request->variable ( 'address', '' );
		$geo_data = null;
		if ($name == 'searchUser' || $location == '') {
			$sql = 'SELECT * FROM ' . $geo_table . ' geo, ' . $table_prefix . 'users u, ' . $table_prefix . 'groups gr' . ' WHERE u.username="' . $this->db->sql_escape($username) . '" AND u.user_id=geo.user_id' . ' AND u.group_id=gr.group_id AND u.user_id=geo.user_id AND geo.is_valid=1';
			$result = $this->db->sql_query ( $sql );
			if ($result) {
				$row = $this->db->sql_fetchrow ( $result );
				if ($row) {
					// var_dump($row);
					$location = $row['location'];
					$geo_data = array (
						'lat' => $row ['latitude'],
						'lng' => $row ['longitude'] 
					);
					// var_dump($geo_data);
					$this->user->get_profile_fields ( $row ['user_id'] );
				} else {
					throw new \phpbb\exception\http_exception ( 404, "NOT_FOUND: no location found for user " . $username );
				}
				$this->db->sql_freeresult ( $result );
			} else {
				throw new \phpbb\exception\http_exception ( 500, "User " . $username . " NOT_FOUND: no SQL result" );
			}
		}
		
		if ($name == 'searchUser') {
			return $this->selectUsers ( $location, $geo_data, $q_radius, $q_limit );
		} elseif ($name == 'searchLocation') {
			if ($location == '') {
				return $this->selectUsers ( $location, $geo_data, $q_radius, $q_limit );
			}
			// geocode input address for search
			$geo_data = $this->geocoder->geocode_user($location);
			return $this->selectUsers ( $location, $geo_data, $q_radius, $q_limit );
		} else {
			throw new \phpbb\exception\http_exception ( 404, 'NOT_FOUND' );
		}
	}
	function selectUsers($address, $p_geo_data, $q_radius, $q_limit) {
		global $table_prefix;
		global $phpbb_container;
		$geo_table = $phpbb_container->getParameter ( 'myersware.mapusers.tables.mapusers' );
		$locations = array ();
		if ($p_geo_data != null) {
			$q_lat = $p_geo_data ['lat'];
			$q_lng = $p_geo_data ['lng'];
			if (! $q_lat) {
				throw new \phpbb\exception\http_exception ( 500, "Location " . $address . " NOT_FOUND: invalid geo_data=" . $p_geo_data );
			}
			$sql = 'SELECT u.user_id, u.username, p.pf_phpbb_location, gr.group_colour, l.latitude, l.longitude, ( 3959 * acos( cos( radians(' . $q_lat . ') ) * cos( radians( l.latitude ) ) * cos( radians( l.longitude ) - radians(' . $q_lng . ') ) + sin( radians(' . $q_lat . ') ) * sin( radians( l.latitude ) ) ) ) AS distance ' . 'FROM  ' . $geo_table . ' g, ' . $table_prefix . 'users u, ' . $table_prefix . 'groups gr' . ', ' . $table_prefix . 'profile_fields_data p, ' . $table_prefix . 'mapusers_geolocation l' . ' WHERE g.user_id=u.user_id AND p.user_id=g.user_id AND l.user_id=u.user_id AND u.group_id=gr.group_id AND g.is_valid=1' . ' HAVING distance < ' . $q_radius . ' ORDER BY distance';
			$result = $this->db->sql_query_limit( $sql, $q_limit );
			while ( $row = $this->db->sql_fetchrow ( $result ) ) {
				array_push ( $locations, array (
						'id' => $row ['user_id'],
						'forum' => $row ['username'],
						'location' => $row ['pf_phpbb_location'],
						'color' => $row ['group_colour'],
						'geo' => [ 
								'latitude' => $row ['latitude'],
								'longitude' => $row ['longitude'] 
						],
						'distance' => $row ['distance'] 
					// 'qs' => $q_qs,
				) );
			}
			$this->db->sql_freeresult ( $result );
		}
		$response = new JsonResponse ( $locations );
		return $response;
	}
}
