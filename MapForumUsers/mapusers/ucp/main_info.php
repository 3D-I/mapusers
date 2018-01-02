<?php

/**
 *
 * Map Forum Users. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2017, James Myers, myersware.com
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */
namespace myersware\mapusers\ucp;

/**
 * Map Forum Users UCP module info.
 */
class main_info {
	function module() {
		return array (
				'filename' => '\myersware\mapusers\ucp\main_module',
				'title' => 'UCP_MAPUSERS_TITLE',
				'modes' => array (
						'settings' => array (
								'title' => 'UCP_MAPUSERS',
								'auth' => 'ext_myersware/mapusers',
								'cat' => array (
										'UCP_MAPUSERS_TITLE' 
								) 
						) 
				) 
		);
	}
}
