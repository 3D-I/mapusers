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
		'MAPUSERS_PAGE' => 'View user map',
		'MAPUSERS_HELLO' => 'Hello %s!',
		'MAPUSERS_GOODBYE' => 'Goodbye %s!',
		
		'MYERSWARE_MAPUSERS' => 'Settings',
		'MYERSWARE_MAPUSERS_GOODBYE' => 'Should say goodbye?',
		'MYERSWARE_MAPUSERS_SETTING_SAVED' => 'Settings have been saved successfully!',
		
		'MYERSWARE_MAPUSERS_NOTIFICATION' => 'Forum users map notification',
		
		'VIEWING_MYERSWARE_MAPUSERS' => 'Viewing forum users map' 
) );
