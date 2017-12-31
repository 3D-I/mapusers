<?php
/**
 *
 * Map Forum Users. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2017, James Myers, myersware.com
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace myersware\mapusers\mcp;

/**
 * Map Forum Users MCP module info.
 */
class main_info
{
	function module()
	{
		return array(
			'filename'	=> '\myersware\mapusers\mcp\main_module',
			'title'		=> 'MCP_MAPUSERS_TITLE',
			'modes'		=> array(
				'front'	=> array(
					'title'	=> 'MCP_MAPUSERS',
					'auth'	=> 'ext_myersware/mapusers',
					'cat'	=> array('MCP_MAPUSERS_TITLE')
				),
			),
		);
	}
}
