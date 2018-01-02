<?php

/**
 *
 * Map Forum Users. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2017, James Myers, myersware.com
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */
namespace myersware\mapusers\ucp;

/**
 * Map Forum Users UCP module.
 */
class main_module {
	var $u_action;
	function main($id, $mode) {
		global $db, $request, $template, $user;
		
		$this->tpl_name = 'ucp_mapusers';
		$this->page_title = $user->lang ( 'UCP_MAPUSERS_TITLE' );
		add_form_key ( 'myersware/mapusers' );
		
		$data = array (
				'user_mapusers' => $request->variable ( 'user_mapusers', $user->data ['user_mapusers'] ) 
		);
		
		if ($request->is_set_post ( 'submit' )) {
			if (! check_form_key ( 'myersware/mapusers' )) {
				trigger_error ( $user->lang ( 'FORM_INVALID' ) );
			}
			
			$sql = 'UPDATE ' . USERS_TABLE . '
				SET ' . $db->sql_build_array ( 'UPDATE', $data ) . '
				WHERE user_id = ' . $user->data ['user_id'];
			$db->sql_query ( $sql );
			
			meta_refresh ( 3, $this->u_action );
			$message = $user->lang ( 'UCP_MAPUSERS_SAVED' ) . '<br /><br />' . $user->lang ( 'RETURN_UCP', '<a href="' . $this->u_action . '">', '</a>' );
			trigger_error ( $message );
		}
		
		$template->assign_vars ( array (
				'S_USER_MAPUSERS' => $data ['user_mapusers'],
				'S_UCP_ACTION' => $this->u_action 
		) );
	}
}
