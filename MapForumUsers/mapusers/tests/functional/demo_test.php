<?php

/**
 *
 * Map Forum Users. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2017, James Myers, myersware.com
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */
namespace myersware\myersware\tests\functional;

/**
 * @group functional
 */
class demo_test extends \phpbb_functional_test_case {
	static protected function setup_extensions() {
		return array (
				'myersware/myersware' 
		);
	}
	public function test_myersware_mapusers() {
		$crawler = self::request ( 'GET', 'app.php/myersware/mapusers' );
		$this->assertContains ( 'myersware', $crawler->filter ( 'h2' )->text () );
		
		$this->add_lang_ext ( 'myersware/mapusers', 'common' );
		$this->assertContains ( $this->lang ( 'MAPUSERS_HELLO', 'myersware' ), $crawler->filter ( 'h2' )->text () );
		$this->assertNotContains ( $this->lang ( 'MAPUSERS_GOODBYE', 'myersware' ), $crawler->filter ( 'h2' )->text () );
		
		$this->assertNotContainsLang ( 'ACP_MAPUSERS', $crawler->filter ( 'h2' )->text () );
	}
	public function test_map_users() {
		$crawler = self::request ( 'GET', 'app.php/myersware/mapusers' );
		$this->assertNotContains ( 'myersware', $crawler->filter ( 'h2' )->text () );
		$this->assertContains ( 'map', $crawler->filter ( 'h2' )->text () );
	}
}
