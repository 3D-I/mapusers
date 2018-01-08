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

class install_user_schema extends \phpbb\db\migration\container_aware_migration {
	public function effectively_installed() {
		return $this->db_tools->sql_column_exists ( $this->table_prefix . 'users', 'user_myersware' );
	}
	static public function depends_on() {
		return array (
				'\phpbb\db\migration\data\v31x\v314' 
		);
	}
	public function update_schema() {
		$float_type = array (
				'mysql_41' => 'float(10, 6)',
				'mysql_40' => 'float(10, 6)',
				'mssql' => '[float]',
				'mssqlnative' => '[float]',
				'oracle' => 'number(10, 6)',
				'sqlite' => 'decimal(10, 6)',
				'sqlite3' => 'decimal(10, 6)',
				'postgres' => 'float(10, 6)' 
		);
		
		//$tools = $this->container->get ( 'dbal.tools' );
		$tools = $this->db_tools;
		
		foreach ( $float_type as $sql_layer => $type ) {
			$tools->dbms_type_map [$sql_layer] ['FLOAT'] = $type;
		}
		
		return array (
				'add_tables' => array (
						$this->table_prefix . 'mapusers_geolocation' => array (
								'COLUMNS' => array (
										'user_id' => array (
												'UINT',
												'0'
										),
										'latitude' => array (
												'FLOAT',
												0.0
										),
										'longitude' => array (
												'FLOAT',
												0.0
										),
										'location' => array (
												'VCHAR:255',
												null 
										),
										'is_valid' => array (
												'BOOL',
												0 
										) 
								),
								'PRIMARY_KEY' => 'user_id' 
						) 
				) 
		);
	}
	public function revert_schema() {
		return array (
				'drop_tables' => array (
						$this->table_prefix . 'mapusers_geolocation' 
				) 
		);
	}
}
