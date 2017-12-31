<?php
/**
*
* Mapusers extension for the phpBB Forum Software package.
*
* @copyright (c) 2018 myersware <https://www.myersware.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace myersware\mapusers\migrations;

/**
* Migration stage 3: Initial permission
*/
class install_permission extends \phpbb\db\migration\migration
{
    static public function depends_on()
    {
        return array('\myersware\mapusers\migrations\install_user_schema');
    }

	/**
	* Add or update data in the database
	*
	* @return array Array of table data
	* @access public
	*/
	public function update_data()
	{
		return array(
			// Add permission
			array('permission.add', array('u_mapusers_view'), true),

			// Set permissions
			// array('permission.permission_set', array('ROLE_ADMIN_FULL', 'u_mapusers_view')),
			// array('permission.permission_set', array('ROLE_ADMIN_STANDARD', 'u_mapusers_view')),
		);
	}
}
