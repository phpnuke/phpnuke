<?php
/**
 *
 * @package       QuickReply Reloaded
 * @copyright (c) 2014 - 2017 Tatiana5 and LavIgor
 * @license       http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace boardtools\quickreply;

/**
 * Extension class for custom enable/disable/purge actions
 *
 * NOTE TO EXTENSION DEVELOPERS:
 * Normally it is not necessary to define any functions inside the ext class below.
 * The ext class may contain special (un)installation commands in the methods
 * enable_step(), disable_step() and purge_step(). As it is, these methods are defined
 * in phpbb_extension_base, which this class extends, but you can overwrite them to
 * give special instructions for those cases. This extension must do this because it uses
 * the notifications system, which requires the methods enable_notifications(),
 * disable_notifications() and purge_notifications() be run to properly manage the
 * notifications created by it when enabling, disabling or deleting this extension.
 */
class ext extends \phpbb\extension\base
{
	/**
	 * Helper function for common actions at all steps.
	 *
	 * @param mixed $old_state State returned by previous call of this method
	 * @param string $notify_method The method called for notifications
	 * @param string $func_name The name of the parent function
	 * @return mixed Returns false after last step, otherwise temporary state
	 */
	private function steps($old_state, $notify_method, $func_name)
	{
		if ($old_state == '')
		{
			// Enable/disable/purge notifications
			$phpbb_notifications = $this->container->get('notification_manager');
			$phpbb_notifications->$notify_method('boardtools.quickreply.notification.type.quicknick');
			return 'notifications';
		}
		else
		{
			// Run parent step method
			return parent::$func_name($old_state);
		}
	}

	/**
	 * Overwrite enable_step to enable notifications
	 * before any included migrations are installed.
	 *
	 * @param mixed $old_state State returned by previous call of this method
	 * @return mixed Returns false after last step, otherwise temporary state
	 */
	public function enable_step($old_state)
	{
		return $this->steps($old_state, 'enable_notifications', 'enable_step');
	}

	/**
	 * Overwrite disable_step to disable notifications
	 * before the extension is disabled.
	 *
	 * @param mixed $old_state State returned by previous call of this method
	 * @return mixed Returns false after last step, otherwise temporary state
	 */
	public function disable_step($old_state)
	{
		return $this->steps($old_state, 'disable_notifications', 'disable_step');
	}

	/**
	 * Overwrite purge_step to purge notifications before
	 * any included and installed migrations are reverted.
	 *
	 * @param mixed $old_state State returned by previous call of this method
	 * @return mixed Returns false after last step, otherwise temporary state
	 */
	public function purge_step($old_state)
	{
		return $this->steps($old_state, 'purge_notifications', 'purge_step');
	}
}
