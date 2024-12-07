<?php
/**
 *
 * Ban List extension for the phpBB Forum Software package
 *
 * @copyright (c) 2024, phpBB Modders, https://www.phpbbmodders.com/
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace phpbbmodders\banlist\migrations\v10x;

class m1_initial_data extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return $this->config->offsetExists('users_per_page');
	}

	public static function depends_on()
	{
		return ['\phpbb\db\migration\data\v330\v330'];
	}

	/**
	 * Add, update or delete data
	 */
	public function update_data()
	{
		return [
			// Add config table settings
			['config.add', ['users_per_page', 25]],

			// Add permissions
			['permission.add', ['u_viewban']],

			// Set permissions
			['permission.permission_set', ['ROLE_USER_FULL', 'u_viewban']],
			['permission.permission_set', ['ROLE_USER_STANDARD', 'u_viewban']],
		];
	}
}
