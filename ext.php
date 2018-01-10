<?php

/**
 *
 * Map Forum Users. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2017, James Myers, myersware.com
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */
namespace myersware\mapusers;

/**
 * Map Forum Users Extension base
 *
 * It is recommended to remove this file from
 * an extension if it is not going to be used.
 */
class ext extends \phpbb\extension\base {
	/**
	 * Check whether or not the extension can be enabled.
	 * The current phpBB version should meet or exceed
	 * the minimum version required by this extension:
	 *
	 * Requires phpBB 3.2.0 due to new dynamic route loader
	 *
	 * @return bool
	 * @access public
	 */
	public function is_enableable()
	{
		return phpbb_version_compare(PHPBB_VERSION, '3.2.0', '>=');
	}
	
	/**
	 * Enable notifications for the extension
	 *
	 * @param mixed $old_state
	 *        	State returned by previous call of this method
	 *        	
	 * @return mixed Returns false after last step, otherwise temporary state
	 */
	public function enable_step($old_state) {
		switch ($old_state) {
			case '' : // Empty means nothing has run yet
				
				$phpbb_notifications = $this->container->get ( 'notification_manager' );
				$phpbb_notifications->enable_notifications ( 'myersware.mapusers.notification.type.mapusers' );
				return 'notification';
				
				break;
			
			default :
				
				return parent::enable_step ( $old_state );
				
				break;
		}
	}
	
	/**
	 * Disable notifications for the extension
	 *
	 * @param mixed $old_state
	 *        	State returned by previous call of this method
	 *        	
	 * @return mixed Returns false after last step, otherwise temporary state
	 */
	public function disable_step($old_state) {
		switch ($old_state) {
			case '' : // Empty means nothing has run yet
				
				$phpbb_notifications = $this->container->get ( 'notification_manager' );
				$phpbb_notifications->disable_notifications ( 'myersware.mapusers.notification.type.mapusers' );
				return 'notification';
				
				break;
			
			default :
				
				return parent::disable_step ( $old_state );
				
				break;
		}
	}
	
	/**
	 * Purge notifications for the extension
	 *
	 * @param mixed $old_state
	 *        	State returned by previous call of this method
	 *        	
	 * @return mixed Returns false after last step, otherwise temporary state
	 */
	public function purge_step($old_state) {
		switch ($old_state) {
			case '' : // Empty means nothing has run yet
				
				$phpbb_notifications = $this->container->get ( 'notification_manager' );
				$phpbb_notifications->purge_notifications ( 'myersware.mapusers.notification.type.mapusers' );
				return 'notification';
				
				break;
			
			default :
				
				return parent::purge_step ( $old_state );
				
				break;
		}
	}
}
