<?php

/**
*
* @package Banlist
* @copyright (c) 2019 Evil
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
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
   /*! Banlist Page */
	'BANU'					         => 'Banlist',
	'BU'					            => 'Banned users:',
	'BAN_TITLE'					      => 'List with all the banned users',
   
   /*! View Permissions */
   'LOGIN_EXPLAIN_VIEWBAN'	      => 'You must be logged in to view this page.',
   
   /*! User Page */
	'USBU'		                  => 'This account is permanently banned! ',
   'USBD'		                  => 'This account is blocked. It will be automatically unbanned in ',
   
   /*! ACP */
	'BL_CONFIG'		               => 'Configuration',
	'BL_ACP' 	                  => 'Banlist',
   'BL_P'		                  => 'Links position in banlist',
   'BL_P_EXP'		               => 'Choose position of the links in banlist',
   'BL_PU' 	                     => 'Top navigation bar',
   'BL_PD'		                  => 'Bottom navigation bar',
   'BL_U'		                  => 'Users on page',    
   'BL_U_EXP'		               => 'Enter the number of users displayed on one page of the Banlist',
	'BL_VI'		                  => 'Display ban information',    
   'BL_VI_EXP'		               => 'Enable display of ban information in the profile and mini profile of the banned user',
   
   /*! Helpers */
	'BAN_USERNAME'	               => 'Username',
	'BAN_START_DATE'					=> 'Ban date',
	'BAN_END_DATE'               	=> 'Unban date',
	'PERM'			               => 'Permanent ban',
   'BAN_REASON'	               => 'Reason',
	'USBUV'		                  => 'User banned',
	'WARN'		                  => 'Warning',
	'WARN_NO'		               => '<strong> --- </strong>',
	'LAST'		                  => '<strong>Last:</strong> ',
	'REAS'		                  => 'Reason:',
	'WARN_POST'		               => 'on post ',
   'WARN_PROF'		               => ' on profile',
   
   /*! Timestamps */
	'DAYLEFT'                     => array(
      0   => '<strong>0</strong> days ',
      1   => '<strong>%d</strong> day ',
      2   => '<strong>%d</strong> days ',
      3   => '<strong>%d</strong> days ',
   ),
	'HOURLEFT'                    => array(
      0   => '<strong>0</strong> hours ',
      1   => '<strong>%d</strong> hour ',
      2   => '<strong>%d</strong> hours ',
      3   => '<strong>%d</strong> hours ',
   ),
	'MINLEFT'                     => array(
      0   => '<strong>0</strong> minutes',
      1   => '<strong>%d</strong> minutes',
      2   => '<strong>%d</strong> minutes',
      3   => '<strong>%d</strong> minute',
   ),   
   	'WARN_COUNT'               => array(
      0   => 'The user currently has <strong>0</strong> warnings...',
      1   => 'The user currently has <strong>%d</strong> warnings:',
      2   => 'The user currently has <strong>%d</strong> warnings:',
      3   => 'The user currently has <strong>%d</strong> warnings:',
   ),
));
