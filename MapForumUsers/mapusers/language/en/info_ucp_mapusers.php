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
		'UCP_MAPUSERS' => 'Settings',
		'UCP_MAPUSERS_TITLE' => 'Mapusers Module',
		'UCP_MAPUSERS_USER' => 'Mapusers user',
		'UCP_MAPUSERS_USER_EXPLAIN' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
		'UCP_MAPUSERS_SAVED' => 'Settings have been saved successfully!',
		
		'NOTIFICATION_TYPE_MAPUSERS' => 'Use myersware mapusers notifications' 
) );
