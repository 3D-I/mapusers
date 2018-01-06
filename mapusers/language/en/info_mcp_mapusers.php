<?php
/**
 *
 * Map Forum Users. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2017, James Myers, myersware.com
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */
if (! defined ( 'IN_PHPBB' )) {
	exit ();
}

if (empty ( $lang ) || ! is_array ( $lang )) {
	$lang = array ();
}

$lang = array_merge ( $lang, array (
		'MCP_MAPUSERS' => 'Front',
		'MCP_MAPUSERS_TITLE' => 'Mapusers Module' 
) );
