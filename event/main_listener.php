<?php
/**
 *
 * Ban List extension for the phpBB Forum Software package
 *
 * @copyright (c) 2024, phpBB Modders, https://www.phpbbmodders.com/
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace phpbbmodders\banlist\event;

/**
 * @ignore
 */
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Ban List event listener
 */
class main_listener implements EventSubscriberInterface
{
	/* @var \phpbb\controller\helper */
	protected $helper;

	/* @var \phpbb\language\language */
	protected $language;

	/* @var \phpbb\template\template */
	protected $template;

	/**
	 * Constructor
	 *
	 * @param \phpbb\controller\helper  $helper
	 * @param \phpbb\language\language  $language
	 * @param \phpbb\template\template  $template
	 */
	public function __construct(\phpbb\controller\helper $helper, \phpbb\language\language $language, \phpbb\template\template $template)
	{
		$this->helper = $helper;
		$this->language = $language;
		$this->template = $template;
	}

	public static function getSubscribedEvents()
	{
		return [
			'core.user_setup'	=> 'user_setup',
			'core.page_header'	=> 'page_header',
			'core.permissions'	=> 'add_permissions',
		];
	}

	/**
	 * Load common language files
	 */
	public function user_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = [
			'ext_name' => 'phpbbmodders/banlist',
			'lang_set' => 'common',
		];
		$event['lang_set_ext'] = $lang_set_ext;
	}

	/**
	 * Add a link to the controller in the forum navbar
	 */
	public function page_header()
	{
		$this->template->assign_vars([
			'U_BANLIST_PAGE'	=> $this->helper->route('phpbbmodders_banlist_controller'),
		]);
	}

	/**
	 * Add permissions to the ACP -> Permissions settings page
	 * This is where permissions are assigned language keys and
	 * categories (where they will appear in the Permissions table):
	 * actions|content|forums|misc|permissions|pm|polls|post
	 * post_actions|posting|profile|settings|topic_actions|user_group
	 */
	public function add_permissions($event)
	{
		$event->update_subarray('permissions', 'u_viewban', ['lang' => 'ACL_U_VIEWBAN', 'cat' => 'misc']);
	}
}
