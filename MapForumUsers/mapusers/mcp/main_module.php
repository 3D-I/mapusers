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
 * Map Forum Users MCP module.
 */
class main_module {
	var $u_action;
	function main($id, $mode) {
		global $template, $user;
		
		$this->tpl_name = 'mcp_mapusers_body';
		$this->page_title = $user->lang ( 'MCP_MAPUSERS_TITLE' );
		add_form_key ( 'myersware_mapusers_settings' );
		
		$template->assign_var ( 'U_POST_ACTION', $this->u_action );
	}
}
