<?php

/**
 *
 * Map Forum Users. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2017, James Myers, myersware.com
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */
namespace myersware\mapusers\acp;

/**
 * Map Forum Users ACP module.
 */
class main_module {
	public $page_title;
	public $tpl_name;
	public $u_action;
	public function main($id, $mode) {
		global $config, $request, $template, $user;
		
		$user->add_lang_ext ( 'myersware/mapusers', 'common' );
		$this->tpl_name = 'acp_mapusers_body';
		$this->page_title = $user->lang ( 'Map Forum Users Settings' );
		
		add_form_key ( 'myersware_mapusers_settings' );
		
		if ($request->is_set_post ( 'submit' )) {
			if (! check_form_key ( 'myersware_mapusers_settings' )) {
				trigger_error ( 'FORM_INVALID', E_USER_WARNING );
			}
			
			$config->set ( 'mapusers_gapi_key', $request->variable ( 'gapi_key', 'invalid' ) );
			trigger_error ( $user->lang ( 'MAPUSERS_SETTING_SAVED' ) . adm_back_link ( $this->u_action ) );
		}
		
		$template->assign_vars ( array (
				'U_ACTION' => $this->u_action,
				'GAPI_KEY' => $config ['mapusers_gapi_key'] 
		) );
	}
}
