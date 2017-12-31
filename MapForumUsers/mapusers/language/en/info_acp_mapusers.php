<?php
/**
 *
 * Map Forum Users. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2017, James Myers, myersware.com
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'ACP_MAPUSERS_TITLE'			=> 'Map Forum Users',
    'ACP_MAPUSERS_SETTINGS_TITLE'			=> 'Map Forum Settings',
    'ACP_MAPUSERS_GEOCODE_TITLE'			=> 'Map Forum Geocode Users',
    'GAPI_KEY' => 'Google API Key',
));
