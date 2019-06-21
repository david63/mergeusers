<?php
/**
*
* @package Merge Users Extension
* @copyright (c) 2014 david63
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace david63\mergeusers\acp;

class mergeusers_info
{
	function module()
	{
		return array(
			'filename'	=> '\david63\mergeusers\acp\mergeusers_module',
			'title'		=> 'ACP_USER_MERGE',
			'modes'		=> array(
				'main'		=> array('title' => 'ACP_USER_MERGE', 'auth' => 'ext_david63/mergeusers && acl_a_user', 'cat' => array('ACP_CAT_USERS')),
			),
		);
	}
}
