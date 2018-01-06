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

class install_ucp_module extends \phpbb\db\migration\migration {
	public function effectively_installed() {
		$sql = 'SELECT module_id
			FROM ' . $this->table_prefix . "modules
			WHERE module_class = 'ucp'
				AND module_langname = 'UCP_MAPUSERS_TITLE'";
		$result = $this->db->sql_query ( $sql );
		$module_id = $this->db->sql_fetchfield ( 'module_id' );
		$this->db->sql_freeresult ( $result );
		
		return $module_id !== false;
	}
	static public function depends_on() {
		return array (
				'\myersware\mapusers\migrations\install_user_schema' 
		);
	}
	public function update_data() {
		return array (
				array (
						'module.add',
						array (
								'ucp',
								0,
								'UCP_MAPUSERS_TITLE' 
						) 
				),
				array (
						'module.add',
						array (
								'ucp',
								'UCP_MAPUSERS_TITLE',
								array (
										'module_basename' => '\myersware\mapusers\ucp\main_module',
										'modes' => array (
												'settings' 
										) 
								) 
						) 
				) 
		);
	}
}
