<?php
// ext/myersware/mapusers/tests/migrations/database/add_database_changes.php
/**
 *
 * This file is part of the phpBB Forum Software package.
 *
 * @copyright (c) phpBB Limited <https://www.phpbb.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * For full copyright and license information, please see
 * the docs/CREDITS.txt file.
 *
 */

namespace myersware\mapusers\tests\migrations;

class add_database_changes_test extends \phpbb_database_test_case
{
	static protected function setup_extensions() {
		return array (
				'myersware/mapusers'
		);
	}
	
    /** @var \phpbb\db\tools */
    protected $db_tools;

    /** @var string */
    protected $table_prefix;

    public function getDataSet()
    {
        return $this->createXMLDataSet(dirname(__FILE__) . '/fixtures/add_database_changes.xml');
    }

    public function setUp()
    {
        parent::setUp();

        global $table_prefix;

        $this->table_prefix = $table_prefix;
        $db = $this->new_dbal();
        $this->db_tools = new \phpbb\db\tools($db);
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
        foreach ( $float_type as $sql_layer => $type ) {
        	$this->db_tools->dbms_type_map [$sql_layer] ['FLOAT'] = $type;
        }
    }

    public function test_mapusers_geolocation_column()
    {
        $this->assertTrue($this->db_tools->sql_column_exists($this->table_prefix . 'mapusers_geolocation', 'latitude'), 'Asserting that column "latitude" exists');
    }

    public function test_mapusers_geolocation_demo_table()
    {
        $this->assertTrue($this->db_tools->sql_table_exists($this->table_prefix . 'mapusers_geolocation_demo'), 'Asserting that column "' . $this->table_prefix . 'latitude_demo" does not exist');
    }
}