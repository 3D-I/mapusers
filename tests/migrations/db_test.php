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
    }

    public function test_user_acme_column()
    {
        $this->assertTrue($this->db_tools->sql_column_exists(USERS_TABLE, 'user_acme'), 'Asserting that column "user_acme" exists');
    }

    public function test_acme_demo_table()
    {
        $this->assertTrue($this->db_tools->sql_table_exists($this->table_prefix . 'acme_demo'), 'Asserting that column "' . $this->table_prefix . 'acme_demo" does not exist');
    }
}