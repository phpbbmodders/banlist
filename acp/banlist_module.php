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

/**
* @package acp
*/
class banlist_module
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	public $u_action;

	function main($id, $mode)
	{
		global $config, $request, $template, $user;

		$this->config 		= $config;
		$this->request 		= $request;
		$this->template 	= $template;
		$this->user 		= $user;

		$this->user->add_lang('acp/common');
		$this->tpl_name 	= 'acp_banlist';
		$this->page_title 	= $this->user->lang('BL_ACP');

		$form_key = 'acp_banlist';

		add_form_key($form_key);

		if ($this->request->is_set_post('submit')) {
			if (!check_form_key($form_key))
				trigger_error($user->lang('FORM_INVALID') . adm_back_link($this->u_action), E_USER_WARNING);
			
			$bl_u = $this->request->variable('bl_u', '');
			$this->config->set('bl_u', $bl_u);

			$bl_p = $this->request->variable('bl_p', 0);
			$this->config->set('bl_p', $bl_p);
			
			$bl_vi = $this->request->variable('bl_vi', 0);
			$this->config->set('bl_vi', $bl_vi);
			
					
			trigger_error($user->lang['CONFIG_UPDATED'] . adm_back_link($this->u_action));
		}

		/*! Assign Template Vars */
		$template->assign_vars(array(
		    'BL_VERSION'		=> isset($this->config['bl_version']) ? $this->config['bl_version'] : '',
			'BL_P'				=> isset($this->config['bl_p']) ? $this->config['bl_p'] : '',
			'BL_U'				=> isset($this->config['bl_u']) ? $this->config['bl_u'] : '',	
			'BL_VI'				=> isset($this->config['bl_vi']) ? $this->config['bl_vi'] : '',			
			'U_ACTION'			=> $this->u_action,
		));
	}
}

?>