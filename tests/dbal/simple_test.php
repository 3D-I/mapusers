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
	
	public function setUp()
	{
		global $table_prefix;
		
		$this->table_prefix = $table_prefix;
		$this->db = $this->new_dbal();
		$factory = new \phpbb\db\tools\factory ();
		$this->db_tools = $factory->get ( $this->db );
		$float_type = array (
				'mysql_41' => 'decimal(11,8)',
				'mysql_40' => 'decimal(11,8)',
				'oracle' => 'number(11, 8)',
				'sqlite3' => 'decimal(11, 8)'
		);
		foreach ( $float_type as $sql_layer => $type ) {
			$this->db_tools->dbms_type_map [$sql_layer] ['FLOAT'] = $type;
		}
		var_dump($this->db_tools->dbms_type_map [$this->db->sql_layer]);
		var_dump($this->db_tools->dbms_type_map);
		print "test FLOAT available " . $this->db->sql_layer . "-" . $this->sql_layer . " ";
		flush();
		
		parent::setUp();
	}
	
	/** @var \phpbb\db\driver\driver_interface */
	protected $db;
	public function getDataSet() {
		print "getDataSet";
		echo "getDataSet";
		flush();
		return $this->createXMLDataSet ( __DIR__ . '/fixtures/config.xml' );
	}
	
	public function test_column() {
		print "test_column";
		echo "test_column";
		flush();
		$this->assertArrayHasKey ( 'FLOAT', [$this->db_tools->dbms_type_map[$this->db->sql_layer] => 'no FLOAT type'] );
		print "test table exists";
		echo "test table exists";
		flush();
		$this->assertTrue($this->db_tools->sql_table_exists($this->table_prefix . 'mapusers_geolocation'), 'Asserting that table "' . $this->table_prefix . 'geolocation" exists');
		print "test column exists";
		echo "test column exists";
		flush();
		$this->assertTrue ( $this->db_tools->sql_column_exists ( $this->table_prefix . 'mapusers_geolocation', 'location' ), 'Asserting that column "location" exists' );
		$this->assertFalse ( $this->db_tools->sql_column_exists ( $this->table_prefix . 'mapusers_geolocation', 'latitude_demo' ), 'Asserting that column "latitude_demo" does not exist' );
	}
}
