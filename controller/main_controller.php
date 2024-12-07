<?php
/**
 *
 * Ban List extension for the phpBB Forum Software package
 *
 * @copyright (c) 2024, phpBB Modders, https://www.phpbbmodders.com/
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace phpbbmodders\banlist\controller;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Ban List main controller
 */
class main_controller
{
	/** @var ContainerInterface */
	protected $container;

	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var string */
	protected $table_prefix;

	/**
	 * Constructor
	 *
	 * @param ContainerInterface                 $container
	 * @param \phpbb\auth\auth                   $auth
	 * @param \phpbb\config\config               $config
	 * @param \phpbb\db\driver\driver_interface  $db
	 * @param \phpbb\controller\helper           $helper
	 * @param \phpbb\language\language           $language
	 * @param \phpbb\request\request             $request
	 * @param \phpbb\template\template           $template
	 * @param \phpbb\user                        $user
	 * @param string                             $table_prefix
	 */
	public function __construct(ContainerInterface $container, \phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\db\driver\driver_interface $db, \phpbb\controller\helper $helper, \phpbb\language\language $language, \phpbb\request\request $request, \phpbb\template\template $template, \phpbb\user $user, $table_prefix)
	{
		$this->container = $container;
		$this->auth = $auth;
		$this->config = $config;
		$this->db = $db;
		$this->helper = $helper;
		$this->language = $language;
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
		$this->table_prefix = $table_prefix;
	}

	/**
	 * Controller handler for route /banlist
	 */
	public function display()
	{
		// Check for permission to view the ban list
		if (!$this->auth->acl_get('u_viewban'))
		{
			throw new \phpbb\exception\http_exception(403, $this->language->lang('NOT_AUTHORISED'));
		}

		$pagination = $this->container->get('pagination');

		$start = $this->request->variable('start', 0);

		$sql = 'SELECT COUNT(ban_userid) as total_banned_users
			FROM ' . $this->table_prefix . 'banlist
			WHERE ban_exclude = 0
				AND ban_userid > 0
				AND (ban_end >= ' . time() . '
				OR ban_end = 0)';
		$result = $this->db->sql_query($sql);
		$total_banned_users = (int) $this->db->sql_fetchfield('total_banned_users');
		$this->db->sql_freeresult($result);

		// Handle pagination
		$start = $pagination->validate_start($start, $this->config['users_per_page'], $total_banned_users);
		$base_url = $this->helper->route('phpbbmodders_banlist_controller');
		$pagination->generate_template_pagination($base_url, 'pagination', 'start', $total_banned_users, $this->config['users_per_page'], $start);

		$sql_where = ' AND b.ban_userid = u.user_id AND u.user_type <> ' . USER_IGNORE;
		//$order_by = ($sort_dir == 'a') ? 'ASC' : 'DESC';

		$sql_ary = [
			'SELECT'	=> 'b.*, u.user_id, u.username, u.username_clean, u.user_colour, u.user_warnings, u.user_last_warning',
			'FROM'		=> [$this->table_prefix . 'banlist' => 'b', $this->table_prefix . 'users' => 'u'],
			'WHERE'		=> '(b.ban_end >= ' . time() . '
				OR b.ban_end = 0) AND ban_exclude <> 1' . $sql_where,
			'ORDER_BY'	=> 'ban_id ASC',
		];

		$result = $this->db->sql_query_limit($this->db->sql_build_query('SELECT', $sql_ary), $this->config['users_per_page'], $start);
		$row_number = $start;
		while ($row = $this->db->sql_fetchrow($result))
		{
			$ban_end = $row['ban_end'];
			$banstart = $row['ban_start'];

			$ban_end = ($ban_end == 0 ? (string) $this->language->lang('PERMANENT') : $this->user->format_date($row['ban_end']));

			$last_warn = $row['user_last_warning'];
			$last_warn = ($last_warn == 0 ? false : $this->user->format_date($row['user_last_warning']));

			$warn = $row['user_warnings'];
			$warn = ($warn == 0 ? (string) $this->language->lang('WARN_NO') : $row['user_warnings']);

			$row_number++;

			$this->template->assign_block_vars('banlist_row', [
				'ROW_NUMBER'	=> $row_number,
				'BAN_START'		=> $this->user->format_date($row['ban_start']),
				'BAN_END'		=> $ban_end,
				'WARN'			=> $warn,
				'LAST_WARN'		=> $last_warn,
				'BAN_REASON'	=> $row['ban_give_reason'],
				'USERNAME_FULL'	=> get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']),
			]);
		}
		$this->db->sql_freeresult($result);

		$this->template->assign_vars([
			//'U_SORT_USERNAME'	=> $sort_url . '?mode=&amp;sk=a&amp;sd=' . (($sort_key == 'a' && $sort_dir == 'a') ? 'd' : 'a'),
			//'U_SORT_BAN_START'	=> $sort_url . '?mode=&amp;sk=b&amp;sd=' . (($sort_key == 'b' && $sort_dir == 'a') ? 'd' : 'a'),

			'TOTAL_USERS'		=> $total_banned_users,

			//'S_MODE_SELECT'		=> $s_sort_key,
			//'S_ORDER_SELECT'	=> $s_sort_dir,
		]);

		$navlinks = [
			[
				'FORUM_NAME'	=> $this->language->lang('BANLIST'),
				'U_VIEW_FORUM'	=> $this->helper->route('phpbbmodders_banlist_controller'),
			],
		];

		foreach ($navlinks as $navlink)
		{
			$this->template->assign_block_vars('navlinks', [
				'FORUM_NAME'	=> $navlink['FORUM_NAME'],
				'U_VIEW_FORUM'	=> $navlink['U_VIEW_FORUM'],
			]);
		}

		return $this->helper->render('banlist_body.html');
	}
}
