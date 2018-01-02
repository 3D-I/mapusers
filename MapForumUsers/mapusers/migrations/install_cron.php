<?php

/**
 *
 * Map Forum Users. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2017, James Myers, myersware.com
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */
namespace myersware\mapusers\migrations;

class install_cron extends \phpbb\db\migration\migration {
	public function effectively_installed() {
		return isset ( $this->config ['myersware_mapusers_last_run'] );
	}
	static public function depends_on() {
		return array (
				'\myersware\mapusers\migrations\install_user_schema' 
		);
	}
	public function update_data() {
		return array (
				array (
						'config.add',
						array (
								'myersware_mapusers_last_run',
								0 
						) 
				) 
		);
	}
}
