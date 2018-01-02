<?php

/**
 *
 * Map Forum Users. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2017, James Myers, myersware.com
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */
namespace myersware\mapusers\acp;

/**
 * Map Forum Users ACP module info.
 */
class geo_info {
	public function module() {
		return array (
				'filename' => '\myersware\mapusers\acp\geo_module',
				'title' => 'Geocode forum users',
				'modes' => array (
						'geocode' => array (
								'title' => 'MAPUSERS Geocode',
								'auth' => 'ext_myersware/mapusers && acl_a_board',
								'cat' => array (
										'ACP_MAPUSERS_TITLE' 
								) 
						) 
				) 
		);
	}
}
