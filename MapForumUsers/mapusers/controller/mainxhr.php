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
class mainxhr
{
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

	/**
	 * Constructor
	 *
	 * @param \phpbb\config\config		$config
	 * @param \phpbb\controller\helper	$helper
	 * @param \phpbb\request\request_interface    $request
	 * @param \phpbb\user				$user
	 * @param \phpbb\db\driver\factory  $db
	 */
	public function __construct(\phpbb\config\config $config, 
	    \phpbb\controller\helper $helper, \phpbb\request\request_interface $request,
	    \phpbb\user $user, \phpbb\db\driver\factory $db)
	{
		$this->config = $config;
		$this->helper = $helper;
		$this->request = $request;
		$this->user = $user;
		$this->db = $db;
	}

	/**
	 * Gcmap controller for route /mapusers/xhr/{name}
	 *
	 * @param string $name
	 *
	 * @return \Symfony\Component\HttpFoundation\JsonResponse A Symfony Response object
	 */
	public function handle($name)
	{
	    global $table_prefix;
	    global $phpbb_container;
	    global $auth;
	    if (!$auth->acl_get('u_mapusers_view'))
	    {
	        trigger_error('NOT_AUTHORISED');
	    }
	    $this->table_prefix = $table_prefix;
	    $this->user->get_profile_fields( $this->user->data['user_id'] );
	    $geo_table = $phpbb_container->getParameter('myersware.mapusers.tables.mapusers');
	    
	    $sql = 'SELECT * FROM ' . $geo_table .
	    ' geo, ' . $table_prefix . 'users u, ' .
	    $table_prefix . 'groups gr' .
	    ' WHERE geo.user_id = ' . (int) $this->user->data['user_id'] .
	    ' AND u.group_id=gr.group_id AND u.user_id=geo.user_id AND geo.is_valid=1';
	    $result = $this->db->sql_query($sql);
	    $geo_data = null;
	    if ($result) {
	        $row = $this->db->sql_fetchrow($result);
	        $geo_data = [$row['latitude'], $row['longitude']];
	    }
	    $this->db->sql_freeresult($result);
	    
	    if ($name == 'getUser') {
            return $this->selectUsers($geo_data, 100);
	    } elseif ($name == 'searchUsers') {
	        $q_lat = $this->request->variable('lat', '');  // verify got arguments
	        $q_lng = $this->request->variable('lng', '');
	        $q_radius = $this->request->variable('radius', 100);
	        if ($q_lat == '') {
    	        return $this->selectUsers($geo_data, 100);
	        }
	        return $this->selectUsers([$q_lat, $q_lng], $q_radius);
	        
	    } else {
	        throw new \phpbb\exception\http_exception(404, 'NOT_FOUND');
	    }
	}
	
	function selectUsers($geo_data, $q_radius) {
	    global $table_prefix;
	    global $phpbb_container;
	    $geo_table = $phpbb_container->getParameter('myersware.mapusers.tables.mapusers');
	    // $q_qs = $this->request->variable_names();
	    $locations = array();
	    if ($geo_data != null) {
	        $q_lat = $geo_data[0];
	        $q_lng = $geo_data[1];
	        $sql = 'SELECT u.user_id, u.username, p.pf_phpbb_location, gr.group_colour, l.latitude, l.longitude, ( 3959 * acos( cos( radians(' .
	   	        $q_lat . ') ) * cos( radians( l.latitude ) ) * cos( radians( l.longitude ) - radians(' . $q_lng .
	   	        ') ) + sin( radians(' . $q_lat . ') ) * sin( radians( l.latitude ) ) ) ) AS distance ' .
	   	        'FROM  ' . $geo_table . ' g, ' . $table_prefix . 'users u, ' .
	   	        $table_prefix . 'groups gr' .
	   	        ', ' . $table_prefix . 'profile_fields_data p, ' . $table_prefix . 'mapusers_geolocation l' .
	   	        ' WHERE g.user_id=u.user_id AND p.user_id=g.user_id AND l.user_id=u.user_id AND u.group_id=gr.group_id AND g.is_valid=1' .
	   	        ' HAVING distance < ' .
	   	        $q_radius . ' ORDER BY distance LIMIT 0 , 20';
    	    $result = $this->db->sql_query($sql);
	        while ($row = $this->db->sql_fetchrow($result))
	       {
	            array_push($locations, array('id'	    => $row['user_id'],
	                'forum' => $row['username'],
	               'location' => $row['pf_phpbb_location'],
	               'color' => $row['group_colour'],
	               'geo'       => ['latitude' => $row['latitude'], 'longitude' => $row['longitude']],
	               'distance'  => $row['distance'],
	               // 'qs' => $q_qs,
	           ));
	       }
	       $this->db->sql_freeresult($result);
	    }
	    $response = new JsonResponse($locations);
	    return $response;
	}
}
