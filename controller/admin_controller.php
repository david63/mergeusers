<?php
/**
*
* @package Merge Users Extension
* @copyright (c) 2014 david63
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace david63\mergeusers\controller;

use phpbb\db\driver\driver_interface;
use phpbb\request\request;
use phpbb\template\template;
use phpbb\user;
use phpbb\log\log;
use phpbb\language\language;
use david63\mergeusers\core\functions;

/**
* Admin controller
*/
class admin_controller implements admin_interface
{
	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\log */
	protected $log;

	/** @var string phpBB root path */
	protected $root_path;

	/** @var string PHP extension */
	protected $phpEx;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var \david63\mergeusers\core\functions */
	protected $functions;

	/** @var string phpBB tables */
	protected $tables;

	/** @var string Custom form action */
	protected $u_action;

	/**
	* Constructor for admin controller
	*
	* @param \phpbb\db\driver\driver_interface	$db				The db connection
	* @param \phpbb\request\request				$request		Request object
	* @param \phpbb\template\template			$template		Template object
	* @param \phpbb\language\language			$language		Language object
	* @param \david63\mergeusers\core\functions	$functions		Functions for the extension
	* @param array	                            $constants		Constants
	* @param array								$tables			phpBB db tables
	*
	* @return \david63\mergeusers\controller\admin_controller
	*
	* @access public
	*/
	public function __construct(driver_interface $db, request $request, template $template, user $user, log $log, $root_path, $php_ext, language $language, functions $functions, $tables)
	{
		$this->db  			= $db;
		$this->request		= $request;
		$this->template		= $template;
		$this->user			= $user;
		$this->log			= $log;
		$this->root_path	= $root_path;
		$this->phpEx		= $php_ext;
		$this->language		= $language;
		$this->functions	= $functions;
		$this->tables		= $tables;
	}

	/**
	* Display the output for this extension
	*
	* @return null
	* @access public
	*/
	public function display_output()
	{
		// Add the language files
		$this->language->add_lang('acp_mergeusers', $this->functions->get_ext_namespace());
		$this->language->add_lang('acp_common', $this->functions->get_ext_namespace());

		$form_key = 'mergeusers';
		add_form_key($form_key);

		$back = false;

		$action			= $this->request->variable('action', '');
		$submit 		= ($this->request->is_set_post('submit')) ? true : false;
		$merge			= ($action == 'merge') ? true : false;
		$old_username	= utf8_normalize_nfc($this->request->variable('old_username', '', true));
		$new_username	= utf8_normalize_nfc($this->request->variable('new_username', '', true));
		$delete_old		= $this->request->variable('delete_old_user', '');

		$errors = array();

		if ($submit)
		{
			if (!check_form_key($form_key))
			{
				trigger_error($this->language->lang('FORM_INVALID'));
			}
		}

		if ($submit || $merge)
		{
			$old_user_id = $this->check_user($old_username, $errors, true);
			$new_user_id = $this->check_user($new_username, $errors, false);

			if ($old_user_id == $new_user_id)
			{
				$errors[] = $this->language->lang('CANNOT_MERGE_SAME');
			}

			if (!$old_user_id && !$new_user_id)
			{
				$errors[] = $this->language->lang('NO_DATA');
			}

			if (empty($errors))
			{
				if (confirm_box(true))
				{
					$this->user_merge($old_user_id, $old_username, $new_user_id, $delete_old);
					$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_USERS_MERGED',  time(), array($old_username, $new_username));
					if ($delete_old)
					{
						trigger_error($this->language->lang('USERS_MERGED_DELETED', $old_username, $new_username) . adm_back_link($this->u_action));
					}
					else
					{
						trigger_error($this->language->lang('USERS_MERGED', $old_username, $new_username) . adm_back_link($this->u_action));
					}
				}
				else
				{
					$hidden_fields = array(
						'old_username'		=> $old_username,
						'new_username'		=> $new_username,
						'action'			=> 'merge',
						'delete_old_user'	=> $delete_old,
					);

					confirm_box(false, sprintf($this->language->lang('MERGE_USERS_CONFIRM'), $old_username, $new_username), build_hidden_fields($hidden_fields));
				}
			}
		}

		// Template vars for header panel
		$version_data	= $this->functions->version_check();

		$this->template->assign_vars(array(
			'DOWNLOAD'			=> (array_key_exists('download', $version_data)) ? '<a class="download" href =' . $version_data['download'] . '>' . $this->language->lang('NEW_VERSION_LINK') . '</a>' : '',

			'ERROR_TITLE'		=> $this->language->lang('WARNING'),
			'ERROR_DESCRIPTION'	=> implode('<br>', $errors),

			'HEAD_TITLE'		=> $this->language->lang('MERGE_USERS'),
			'HEAD_DESCRIPTION'	=> $this->language->lang('MERGE_USERS_EXPLAIN'),

			'NAMESPACE'			=> $this->functions->get_ext_namespace('twig'),

			'S_BACK'			=> $back,
			'S_ERROR'			=> (!empty($errors)) ? true : false,
			'S_VERSION_CHECK'	=> (array_key_exists('current', $version_data)) ? $version_data['current'] : false,

			'VERSION_NUMBER'	=> $this->functions->get_meta('version'),
		));

		$this->template->assign_vars(array(
			'NEW_USERNAME'			=> (!empty($new_user_id)) ? $new_username : '',

			'OLD_USERNAME'			=> (!empty($old_user_id)) ? $old_username : '',

			'U_ACTION'				=> $this->u_action,
			'U_FIND_NEW_USERNAME'	=> append_sid("{$this->root_path}memberlist.$this->phpEx", 'mode=searchuser&amp;form=user_merge&amp;field=new_username&amp;select_single=true'),
			'U_FIND_OLD_USERNAME'	=> append_sid("{$this->root_path}memberlist.$this->phpEx", 'mode=searchuser&amp;form=user_merge&amp;field=old_username&amp;select_single=true'),
		));
	}

	/**
	* Checks to see if we can use this username for a merge, based on a few factors.
	*
	* @param 	string	$username	The username to check
	* @param 	array	&$errors	Errors array to work with
	* @param 	int		$old_user	Old user check
	*
	* @return	mixed	Return the user's ID (integer) if valid, return void if there was an error
	*/
	protected function check_user($username, &$errors, $old_user)
	{
		if (!empty($username))
		{
			$sql = 'SELECT user_id, user_type
				FROM ' . USERS_TABLE . "
				WHERE username_clean = '" . $this->db->sql_escape(utf8_clean_string($username)) . "'";
			$result = $this->db->sql_query($sql);

			$user_id	= (int) $this->db->sql_fetchfield('user_id');
			$user_type	= (int) $this->db->sql_fetchfield('user_type');

			$this->db->sql_freeresult($result);

			if (!$user_id)
			{
				$errors[] = ($old_user) ? $this->language->lang('NO_OLD_USER') : $this->language->lang('NO_NEW_USER');
				return;
			}
		}
		else
		{
			$errors[] = ($old_user) ? $this->language->lang('NO_OLD_USER_SPECIFIED') : $this->language->lang('NO_NEW_USER_SPECIFIED');
			return;
		}

		// Check to see if it is ourselves here
		if ($user_id === (int) $this->user->data['user_id'] && $old_user)
		{
			$errors[] = $this->language->lang('CANNOT_MERGE_SELF');
			return;
		}

		// Make sure this isn't a founder
		if ($user_type === USER_FOUNDER && $old_user && $this->user->data['user_type'] !== USER_FOUNDER)
		{
			$errors[] = $this->language->lang('CANNOT_MERGE_FOUNDER');
			return;
		}
		return $user_id;
	}

	/**
	* Merge two user accounts into one
	*
	* @author eviL3
	* @param	int $old_user		User id of the old user
	* @param	int $new_user		User id of the new user
	* @param	int $delete_old		Delete the old user
	*
	* @return	void
	*/
	protected function user_merge($old_user, $old_username, $new_user, $delete_old)
	{
		if (!function_exists('user_add'))
		{
			include($this->root_path . 'includes/functions_user.' . $this->phpEx);
		}

		$old_user = (int) $old_user;
		$new_user = (int) $new_user;

		$total_posts = 0;

		// Add up the total number of posts for both...
		$sql = 'SELECT user_posts
			FROM ' . $this->tables['users'] . '
			WHERE ' . $this->db->sql_in_set('user_id', array($old_user, $new_user));
		$result = $this->db->sql_query($sql);

		while ($return = $this->db->sql_fetchrow($result))
		{
			$total_posts = $total_posts + (int) $return['user_posts'];
		}

		$this->db->sql_freeresult($result);

		// Now set the new user to have the total amount of posts.  ;)
		$this->db->sql_query('UPDATE ' . $this->tables['users'] . ' SET ' . $this->db->sql_build_array('UPDATE', array(
			'user_posts' => $total_posts,
		)) . ' WHERE user_id = ' . (int) $new_user);

		// Get both users userdata
		$data = array();

		foreach (array($old_user, $new_user) as $key)
		{
			$sql = 'SELECT user_id, username, user_colour
				FROM ' . $this->tables['users'] . '
					WHERE user_id = ' . (int) $key;
			$result = $this->db->sql_query($sql);

			$data[$key] = $this->db->sql_fetchrow($result);

			$this->db->sql_freeresult($result);
		}

		$update_ary = array(
			$this->tables['attachments']		=> array('poster_id'),
			$this->tables['forums']				=> array(array('forum_last_poster_id', 'forum_last_poster_name', 'forum_last_poster_colour')),
			$this->tables['log']				=> array('user_id', 'reportee_id'),
			$this->tables['moderator_cache']	=> array(array('user_id', 'username')),
			$this->tables['posts']				=> array(array('poster_id', 'post_username'), 'post_edit_user'),
			$this->tables['poll_votes']			=> array('vote_user_id'),
			$this->tables['privmsgs']			=> array('author_id', 'message_edit_user'),
			$this->tables['privmsgs_to']		=> array('user_id', 'author_id'),
			$this->tables['reports']			=> array('user_id'),
			$this->tables['topics']				=> array(array('topic_poster', 'topic_first_poster_name', 'topic_first_poster_colour'), array('topic_last_poster_id', 'topic_last_poster_name', 'topic_last_poster_colour')),
		);

		foreach ($update_ary as $table => $field_ary)
		{
			foreach ($field_ary as $field)
			{
				$sql_ary = array();

				if (!is_array($field))
				{
					$field = array($field);
				}

				$sql_ary[$field[0]] = $new_user;

				if (!empty($field[1]))
				{
					$sql_ary[$field[1]] = $data[$new_user]['username'];
				}

				if (!empty($field[2]))
				{
					$sql_ary[$field[2]] = $data[$new_user]['user_colour'];
				}

				$primary_field = $field[0];

				$sql = "UPDATE $table SET " . $this->db->sql_build_array('UPDATE', $sql_ary) . "
					WHERE $primary_field = $old_user";
				$this->db->sql_query($sql);
			}
		}

		if ($delete_old)
		{
			user_delete('remove', $old_user);
			$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_MERGED_DELETED',  time(), array($old_username));
		}
		else // Reset post count to zero
		{
			$sql = 'UPDATE ' . $this->tables['users'] .
				" SET user_posts = 0
				WHERE user_id = $old_user";
			$this->db->sql_query($sql);
		}
	}

	/**
	* Set page url
	*
	* @param string $u_action Custom form action
	* @return null
	* @access public
	*/
	public function set_page_url($u_action)
	{
		$this->u_action = $u_action;
	}
}
