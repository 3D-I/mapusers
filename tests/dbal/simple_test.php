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
				'mysql_41' => 'decimal(11,8)',
				'mysql_40' => 'decimal(11,8)',
				'oracle' => 'number(11, 8)',
				'sqlite3' => 'decimal(11, 8)'
		);
		foreach ( $float_type as $sql_layer => $type ) {
			$db_tools->dbms_type_map [$sql_layer] ['FLOAT'] = $type;
		}
		// var_dump($db_tools->dbms_type_map);
		dump($db_tools->dbms_type_map [$sql_layer]);
		print $db_tools->dbms_type_map [$sql_layer];
		echo $db_tools->dbms_type_map [$sql_layer];
		flush();
		$this->assertArrayHasKey ( 'FLOAT', [$db_tools->dbms_type_map[$db_tools->sql_layer] => 'no FLOAT type'] );
		print "test table exists";
		echo "test table exists";
		flush();
		$this->assertTrue($this->db_tools->sql_table_exists($this->table_prefix . 'mapusers_geolocation'), 'Asserting that table "' . $this->table_prefix . 'geolocation" exists');
		print "test column exists";
		echo "test column exists";
		flush();
		$this->assertTrue ( $db_tools->sql_column_exists ( $this->table_prefix . 'mapusers_geolocation', 'location' ), 'Asserting that column "location" exists' );
		$this->assertFalse ( $db_tools->sql_column_exists ( $this->table_prefix . 'mapusers_geolocation', 'latitude_demo' ), 'Asserting that column "latitude_demo" does not exist' );
	}
}
