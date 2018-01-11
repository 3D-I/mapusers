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
		$this->auth = $this->getMockBuilder('\phpbb\auth\auth')->getMock();
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
		/*
		$user = $this->getMock('\phpbb\user', array(), array(
				new \phpbb\language\language(new \phpbb\language\language_file_loader($phpbb_root_path, $phpEx)),
				'\phpbb\datetime'
		)); */
		$user = new \phpbb\user($lang, '\phpbb\datetime');
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
				array('mapusers/showmap', 403, 'NOT_AUTHORISED', 2)
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
		try
		{
			$response = $controller->handle($route);
			$this->fail('The expected \phpbb\exception\http_exception was not thrown');
		}
		catch (\phpbb\exception\http_exception $exception)
		{
			$this->assertEquals($status_code, $exception->getStatusCode());
			$this->assertEquals($page_content, $exception->getMessage());
		}
		
	}
}
