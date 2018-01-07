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

/**
 * Map Forum Users main controller.
 */
class main {
	/* @var \phpbb\config\config */
	protected $config;
	
	/* @var \phpbb\controller\helper */
	protected $helper;
	
	/* @var \phpbb\template\template */
	protected $template;
	
	/* @var \phpbb\user */
	protected $user;
	
	/* @var \phpbb\auth */
	protected $auth;
	
	/**
	 * Constructor
	 *
	 * @param \phpbb\config\config $config
	 * @param \phpbb\auth $auth
	 * @param \phpbb\controller\helper $helper
	 * @param \phpbb\template\template $template
	 * @param \phpbb\user $user
	 */
	public function __construct(\phpbb\config\config $config, \phpbb\controller\helper $helper, \phpbb\template\template $template, \phpbb\user $user,
			\phpbb\auth $auth) {
		$this->config = $config;
		$this->helper = $helper;
		$this->template = $template;
		$this->user = $user;
		$this->auth = $auth;
	}
	
	/**
	 * Gcmap controller for route /mapusers/{name}
	 *
	 * @param string $name
	 *
	 * @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	 */
	public function handle($name) {
		if (! $this->auth->acl_get ( 'u_mapusers_view' )) {
			trigger_error ( 'NOT_AUTHORISED' );
		}
		if ($name == 'showmap') {
			$l_message = ! $this->config ['myersware_mapusers_goodbye'] ? 'MAPUSERS_HELLO' : 'MAPUSERS_GOODBYE';
			$this->template->assign_vars ( array (
					'MAPUSERS_MESSAGE' => $this->user->lang ( $l_message, $this->user->data ['username'] ),
					'GAPI_KEY' => $this->config ['mapusers_gapi_key'] 
			) );
			return $this->helper->render ( 'mapusers_body.html', $name );
		} else {
			throw new \phpbb\exception\http_exception ( 404, "NOT_FOUND" );
		}
	}
}
