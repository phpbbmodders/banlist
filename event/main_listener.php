<?php

/**
*
* @package Banlist
* @copyright (c) 2019 Evil
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace phpbbmodders\banlist\event;

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
    exit;
}

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class main_listener implements EventSubscriberInterface
{
	protected $banlist_controller;
	protected $config;
	protected $request;
	protected $db;
	protected $auth;
	protected $template;
	protected $user;
	protected $phpbb_root_path;
	
	public function __construct(
		\phpbbmodders\banlist\controller\main_controller $controller,
		\phpbb\config\config $config, 
		\phpbb\request\request $request,
		\phpbb\db\driver\driver_interface $db, 
		\phpbb\auth\auth $auth, 
		\phpbb\template\template $template, 
		\phpbb\user $user, 
		$phpbb_root_path
	) {
		$this->banlist_controller 	= $controller;
		$this->config 				= $config;
		$this->request 				= $request;
		$this->db 					= $db;
		$this->auth 				= $auth;
		$this->template 			= $template;
		$this->user 				= $user;
		$this->phpbb_root_path 		= $phpbb_root_path;
	}
	
	static public function getSubscribedEvents() {
		return array(
		'core.index_modify_page_title'			=> 'ban',	
		'core.user_setup'						=> 'load_language_on_setup',
		'core.page_header'						=> 'add_page_header_link',
		'core.memberlist_view_profile'			=> 'banMember',
		'core.viewtopic_modify_post_data'		=> 'viewtopic_modify_post_data',
		'core.viewtopic_modify_post_row'		=> 'banMemberUpd',
		'core.permissions'						=> 'add_permissions',
		);
	}
	
	public function load_language_on_setup($event) {
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => 'phpbbmodders/banlist',
			'lang_set' => 'banlist',
		);

		$event['lang_set_ext'] = $lang_set_ext;
	}	
	
	public function add_permissions($event) {
		$permissions = $event['permissions'];
		$permissions['u_viewban'] = array('lang' => 'ACL_U_VIEWBAN', 'cat' => 'misc');
		$event['permissions'] = $permissions;
	}

	public function ban($event) {
		$sql = 'SELECT COUNT(ban_userid) as total_banned_users
			FROM ' . BANLIST_TABLE . '
			WHERE ban_exclude = 0 AND ban_userid > 0 AND (ban_end >= ' . time() . ' OR ban_end = 0)';

		$total_banned_users = (int) ($this->db->sql_fetchrow($this->db->sql_query($sql)))['total_banned_users'];

		/*! Assign Vars */
		$this->template->assign_vars(array(
			'TOTAL_BANNED_USERS'	=> $total_banned_users,
		));
	}
	
	public function add_page_header_link($event) {
		$this->template->assign_vars(array(
			'BL_P'				=> $this->config['bl_p'],
			'U_BAN' 			=> append_sid("{$this->phpbb_root_path}banlist"),
			'S_DISPLAY_BAN'		=> $this->auth->acl_get('u_viewban'),
		));
	}
	
	public function banMember($event) {
		$member = $event['member'];
		$user_id = (int) $member['user_id'];
		$sql = 'SELECT ban_userid, ban_reason, ban_end
			FROM ' . BANLIST_TABLE . '
			WHERE ban_userid = ' . $user_id . ' AND ban_exclude = 0 AND (ban_end >= ' . time() . ' OR ban_end = 0)';	

		$result = $this->db->sql_query($sql);

		while ($row = $this->db->sql_fetchrow($result)) {
			$ban_end = $row['ban_end'];
			
			if ($ban_end == 0)
				$ban_end = (string)$this->user->lang['USBU'];
			
			else {
				$time_left = (int) $row['ban_end'] - time();
				$days_left = $minutes_left = $seconds_left = 0;
				$remaining_time = '';

				if ($time_left) {

					/*! Days Left */
					$days_left = floor($time_left / 86400);

					if ($days_left) {
						$days = $this->user->lang('DAYLEFT', $days_left);
						$time_left = $time_left - ($days_left * 86400);
						$remaining_time .= $days;
					}
					
					/*! Hours Left */
					$hours_left = floor($time_left / 3600);

					if ($hours_left) {
						$hours = $this->user->lang('HOURLEFT', $hours_left);
						$time_left = $time_left - ($hours_left * 3600);
						$remaining_time .= $hours;
					}
					
					/*! Minutes Left */
					$minutes_left = ceil($time_left / 60);

					if ($minutes_left) {
						$minutes = $this->user->lang('MINLEFT', $minutes_left);
						$remaining_time .= $minutes;
					}
				}

				/*! Ban end append */
				$ban_end =' '. (string)($this->user->lang['USBD']) . '' . $remaining_time . '! ';
			}

			/*! Assign template vars */
			$this->template->assign_vars(array(
				'BL_VI'		=> $this->config['bl_vi'],
				'USBU' => $ban_end,
				'USERB_ID' => $row['ban_userid'],
				'BRS' => $row['ban_reason'],
			));
		}

		$sql2 = 'SELECT COUNT(warning_id) as total_warn
			FROM ' . WARNINGS_TABLE . '
			WHERE user_id = ' . $user_id . '';				

		$total_warn = (int) ($this->db->sql_fetchrow($this->db->sql_query($sql2)))['total_warn'];
		$total_warning = $this->user->lang('WARN_COUNT', $total_warn);

		$total_w = ($total_warn != 0 ? true : false);

		$this->template->assign_vars(array(
			'TOTAL_WARN'		=> $total_warning,
			'S_TOTAL_WARN'		=> $total_w,
		));
		
		/*! Get warning times and warning post */

		$sql3 = 'SELECT warning_time, post_id
			FROM ' . WARNINGS_TABLE . '
			WHERE user_id = ' . $user_id . '';	

		$result3 = $this->db->sql_query($sql3);

		while ($row3 = $this->db->sql_fetchrow($result3)) {
			$warn_t = $this->user->format_date($row3['warning_time']);
			$post_id = $row3['post_id'];
			
			/*! Get warning post */
			$post_link = (!empty($row3['post_id']) ? ', ' . $this->user->lang['WARN_POST'] . ' <a href="./viewtopic.php?p=' . $row3['post_id'] . '#p' . $row3['post_id'] . '">#' . $row3['post_id'] . '</a>;' : $this->user->lang['WARN_PROF']);

			/*! Assign Vars */
			$this->template->assign_block_vars('warn', array(
				'WARN_T'			=> $warn_t,
				'POST_LINK'			=> $post_link,
			));
		}
	}	
	
	public function viewtopic_modify_post_data($event) {
		$user_ids = array();
		$rowset = $event['rowset'];
		$post_list = $event['post_list'];

		for ($i = 0, $end = sizeof($post_list); $i < $end; ++$i)
		{
			if (!isset($rowset[$post_list[$i]]))
				continue;
			
			$row = $rowset[$post_list[$i]];
			$poster_id = $row['user_id'];

			if ($poster_id != ANONYMOUS && !$row['foe'] && !$row['hide_post'])
				$user_ids[] = $poster_id;
			
			unset($rowset[$post_list[$i]]);
		}
		
		if (sizeof($user_ids)) {
			$sql = 'SELECT ban_userid
				FROM ' . BANLIST_TABLE . '
				WHERE ' . $this->db->sql_in_set('ban_userid', $user_ids) . '
					AND ban_exclude = 0 AND (ban_end >= ' . time() . ' OR ban_end = 0)';

			$result = $this->db->sql_query($sql);

			while ($row = $this->db->sql_fetchrow($result))
				$this->ban_userid[$row['ban_userid']] = $row['ban_userid'];
			
			$this->db->sql_freeresult($result);
		}
	}
	
	public function banMemberUpd($event) {
		$this->template->assign_vars(array(
			'BL_VI'		=> $this->config['bl_vi'],
		));

		if (!empty($this->ban_userid[$event['poster_id']])) {
			$post_row = array(
				'USERB_ID' => $this->ban_userid[$event['poster_id']],
			);

			$event['post_row'] += $post_row;
		}
	}
}