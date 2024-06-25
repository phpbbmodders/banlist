<?php

/**
*
* @package Banlist
* @copyright (c) 2019 Evil
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace phpbbmodders\banlist\acp;

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
    exit;
}

class banlist_info
{
	function module()
	{
		return array(
			'filename'	=> '\phpbbmodders\banlist\banlist_module',
			'title'		=> 'BL_ACP',
			'modes'		=> array(
				'banlist_config' => array('title' => 'BL_CONFIG', 'auth' => 'ext_phpbbmodders/banlist && acl_a_board', 'cat' => array('BL_ACP')),
			),
		);
	}
}

?>