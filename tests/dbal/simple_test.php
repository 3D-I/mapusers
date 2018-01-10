<?php

/**
 *
 * Map Forum Users. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2018, James Myers, myersware.com
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */
namespace myersware\mapusers\tests\dbal;

class simple_test extends \phpbb_database_test_case {
	static protected function setup_extensions() {
		return array (
				'myersware/mapusers' 
		);
	}
	
	/** @var \phpbb\db\driver\driver_interface */
	protected $db;
	public function getDataSet() {
		return $this->createXMLDataSet ( __DIR__ . '/fixtures/config.xml' );
	}
	
	public function test_column() {
		$this->db = $this->new_dbal ();
		// This is how to instantiate db_tools in phpBB 3.2
		$factory = new \phpbb\db\tools\factory ();
		$db_tools = $factory->get ( $this->db );
		
		$float_type = array (
				'mysql_41' => 'float(10, 6)',
				'mysql_40' => 'float(10, 6)',
				'oracle' => 'number(10, 6)',
				'sqlite3' => 'decimal(10, 6)'
		);
		foreach ( $float_type as $sql_layer => $type ) {
			$db_tools->dbms_type_map [$sql_layer] ['FLOAT'] = $type;
		}
		
		$this->assertTrue ( $db_tools->sql_column_exists ( $this->table_prefix . 'mapusers_geolocation', 'latitude' ), 'Asserting that column "latitude" exists' );
		$this->assertFalse ( $db_tools->sql_column_exists ( $this->table_prefix . 'mapusers_geolocation', 'latitude_demo' ), 'Asserting that column "latitude_demo" does not exist' );
	}
}
