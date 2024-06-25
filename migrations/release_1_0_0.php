<?php

/**
 * @package Banlist
 * @copyright (c) 2024 phpBBModders.net
 * @license https://opensource.org/license/gpl-2-0 GPL v2
 */

namespace phpbbmodders\banlist\migrations;

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
    exit;
}

class release_1_0_0 extends \phpbb\db\migration\migration
{
	public function update_schema()
	{
		if (!$this->db_tools->sql_column_exists($this->table_prefix . 'banlist', 'ban_banner'))
		{
			return 	array(
				'add_columns' => array(
					$this->table_prefix . 'banlist' => array(
						'ban_banner' => array('UINT',0),

					),
				),
			);
		}
	}
	public function revert_schema()
	{
		return 	array(
			'drop_columns' => array(
				$this->table_prefix . 'banlist' => array('ban_banner'),
			),
		);
		}
		
		public function update_data()
	{
		return array(

			// Add new config vars
			array('config.add', array('bl_version', '1.0.0')),
			array('config.add', array('bl_p', 0)),
			array('config.add', array('bl_u', 5)),
			array('permission.add', array('u_viewban')),
			// Add new modules
			array('module.add', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'BL_ACP'
			)),

			array('module.add', array(
				'acp',
				'BL_ACP',
				array(
					'module_basename'	=> '\phpbbmodders\banlist\acp\banlist_module',
					'modes'	=> array('banlist_config'),
				),
			)),
		);
	}

	public function revert_data()
	{
		return array(
			array('config.remove', array('bl_version')),
			array('config.remove', array('bl_p')),
			array('config.remove', array('bl_u')),
		array('permission.remove', array('u_viewban')),
			array('module.remove', array(
				'acp',
				'BL_ACP',
				array(
					'module_basename'	=> '\phpbbmodders\banlist\acp\banlist_module',
					'modes'	=> array('banlist_config'),
				),
			)),
			array('module.remove', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'BL_ACP'
			)),
		);
	}
		
}