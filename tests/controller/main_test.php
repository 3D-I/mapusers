<?php

/**
 *
 * Map Forum Users. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2018, James Myers, myersware.com
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */
namespace myersware\mapusers\tests\controller;

class main_test extends \phpbb_test_case {
	
	/** @var \PHPUnit_Framework_MockObject_MockObject|\phpbb\auth\auth */
	protected $auth;
	/** @var \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\DependencyInjection\ContainerInterface */
	protected $container;
	/** @var \PHPUnit_Framework_MockObject_MockObject|\phpbb\controller\helper */
	protected $controller_helper;
	/** @var \phpbb\language\language */
	protected $lang;
	/** @var \PHPUnit_Framework_MockObject_MockObject|\phpbb\template\template */
	protected $template;
	/** @var \phpbb\user */
	protected $user;
	
	static protected function setup_extensions() {
		return array (
				'myersware/mapusers'
		);
	}
	
	public function setUp()
	{
		parent::setUp();
		
		global $cache, $config, $phpbb_extension_manager, $phpbb_dispatcher, $user, $phpbb_root_path, $phpEx;
		
		// Load/Mock classes required by the controller class
		$this->config = new \phpbb\config\config(array());
		$phpbb_dispatcher = new \phpbb_mock_event_dispatcher();
		
		$this->auth = $this->getMockBuilder('\phpbb\auth')
		->disableOriginalConstructor()
		->getMock();
		$acl_get_map = array(
				array('u_mapusers_view', 23, true),
				array('u_mapusers_view', '23', true),// Called without int cast
		);
		$this->auth->expects($this->any())
		->method('acl_get')
		->with($this->stringContains('_'),
				$this->anything())
				->will($this->returnValueMap($acl_get_map));
		
		$this->template = $this->getMockBuilder('\phpbb\template\template')
		->getMock();
		$lang_loader = new \phpbb\language\language_file_loader($phpbb_root_path, $phpEx);
		$this->lang = new \phpbb\language\language($lang_loader);
		$this->user = new \phpbb\user($this->lang, '\phpbb\datetime');
		$this->controller_helper = $this->getMockBuilder('\phpbb\controller\helper')
		->disableOriginalConstructor()
		->getMock();
		$this->controller_helper->expects($this->any())
		->method('render')
		->willReturnCallback(function ($template_file, $page_title = '', $status_code = 200, $display_online_list = false) {
			return new \Symfony\Component\HttpFoundation\Response($template_file, $status_code);
		})
		;
		// Global vars called upon during execution
		$cache = new \phpbb_mock_cache();
		$user = $this->getMock('\phpbb\user', array(), array(
				new \phpbb\language\language(new \phpbb\language\language_file_loader($phpbb_root_path, $phpEx)),
				'\phpbb\datetime'
		));
		$phpbb_extension_manager = new \phpbb_mock_extension_manager($phpbb_root_path);
	}
	
	public function get_controller()
	{
		return  new \myersware\mapusers\controller\main(
				$this->config,
				$this->controller_helper,
				$this->template,
				$this->user,
				$this->auth
				);
	}
	
	public function display_data() {
		return array (
				array('mapusers/showmap', 200, 'mapusers_body.html', 2)
		);
	}
	
	/**
	 * Test controller display
	 *
	 * @dataProvider display_data
	 */
	public function test_handle($route, $status_code, $page_content, $user_id)
	{
		$this->user->data['user_id'] = $user_id;
		$controller = $this->get_controller();
		$response = $controller->handle($route);
		$this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $response);
		$this->assertEquals($status_code, $response->getStatusCode());
		$this->assertEquals($page_content, $response->getContent());
	}
	
	public function xxhandle_data() {
		return array (
				array (
						200,
						'mapusers_body.html'
				)
		);
	}
	/**
	 * @dataProvider handle_data
	 */
	public function xxtestxx_handle($status_code, $page_content) {
		// Mocks are dummy implementations that provide the API of components we depend on //
		/** @var \phpbb\template\template $template Mock the template class */
		$template = $this->getMockBuilder('\phpbb\template\template')
		->getMock();
		/** @var \phpbb\user $user Mock the user class */
		$user = $this->getMockBuilder ( '\phpbb\user' )->disableOriginalConstructor ()->getMock ();
		
		// Set user->lang() to return any arguments sent to it
		$user->expects ( $this->any () )->method ( 'lang' )->will ( $this->returnArgument ( 0 ) );
		
		/** @var \phpbb\controller\helper $controller_helper Mock the controller helper class */
		$controller_helper = $this->getMockBuilder ( '\phpbb\controller\helper' )->disableOriginalConstructor ()->getMock ();
		
		/** @var \phpbb\auth $auth Mock the auth class */
		$auth = $this->getMock('\phpbb\auth\auth');
		$acl_get_map = array(
				array('u_mapusers_view', 23, true),
				array('u_mapusers_view', '23', true),// Called without int cast
		);
		$auth->expects($this->any())
		->method('acl_get')
		->with($this->stringContains('_'),
				$this->anything())
				->will($this->returnValueMap($acl_get_map));
		
		// Set the expected output of the controller_helper->render() method
		$controller_helper->expects ( $this->any () )->method ( 'render' )->willReturnCallback ( function ($template_file, $page_title = '', $status_code = 200, $display_online_list = false) {
			return new \Symfony\Component\HttpFoundation\Response ( $template_file, $status_code );
		} );
		
		// Instantiate the map users controller
		$controller = new \myersware\mapusers\controller\main ( new \phpbb\config\config ( array () ), $controller_helper, $template, $user, $auth );
		
		$response = $controller->handle ( 'test' );
		$this->assertInstanceOf ( '\Symfony\Component\HttpFoundation\Response', $response );
		$this->assertEquals ( $status_code, $response->getStatusCode () );
		$this->assertEquals ( $page_content, $response->getContent () );
	}
}
