<?php
/**
*
* @package Merge Users Extension
* @copyright (c) 2014 david63
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, array(
	'CANNOT_MERGE_FOUNDER'		=> 'Founders can only be deleted by other founders.',
	'CANNOT_MERGE_SAME'			=> 'You cannot merge a user with the same user.',
	'CANNOT_MERGE_SELF'			=> 'You are trying to delete yourself.',

	'DELETE_OLD_USER'			=> 'Delete old user after the merge',

	'MERGE_USERS'				=> 'Merge users',
	'MERGE_USERS_CONFIRM'		=> 'Are you sure you wish to merge <strong>%1$s</strong> with <strong>%2$s</strong>?',
	'MERGE_USERS_EXPLAIN'		=> 'Here you can merge two users together.<br>Note: the content made by the old user will be transferred to the new user and the old user can be deleted.',

	'NEW_USER'					=> 'New username',
	'NEW_USER_EXPLAIN'			=> 'The new user that the old user should be merged into.<br><strong>Note: This user must already exist.</strong>',
	'NO_DATA'					=> 'No users have been entered',
	'NO_NEW_USER'				=> 'The <strong>new</strong> user for merging could not be located within the database.',
	'NO_NEW_USER_SPECIFIED'		=> 'The <strong>new</strong> user for merging was not specified.',
	'NO_OLD_USER'				=> 'The <strong>old</strong> user for merging could not be located within the database.',
	'NO_OLD_USER_SPECIFIED'		=> 'The <strong>old</strong> user for merging was not specified.',

	'OLD_USER'					=> 'Old username',
	'OLD_USER_EXPLAIN'			=> 'The old user that is to be merged.<br><strong>Note: This is the user that will be deleted after the merge.</strong>',

	'USERS_MERGED'				=> 'User %1$s was successfully merged with user %2$s and no users were deleted.',
	'USERS_MERGED_DELETED'		=> 'User %1$s was successfully merged with user %2$s and user %1$s was deleted.',
));
