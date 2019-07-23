<?php
/**
*
* @package Banlist
* @copyright (c) 2019 Evil
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace evilsystem\banlist\migrations;

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
    exit;
}

class release_1_0_5 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return (isset($this->config['bl_version']) && version_compare($this->config['bl_version'], '1.0.5', '>='));
	}
static public function depends_on()
	{
		return array('\evilsystem\banlist\migrations\release_1_0_4');
	}

	
	public function update_data()
	{
		return array(
			array('config.add', array('bl_version', '1.0.5')),
			array('if', array(
				(isset($this->config['bl_version']) && version_compare($this->config['bl_version'], '1.0.5', '<')),
				array('config.update', array('bl_version', '1.0.5')),
			)),
			
		);
	}

}