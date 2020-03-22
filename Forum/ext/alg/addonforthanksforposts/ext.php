<?php
/**
*
* @package full_access_only_for_team
* @copyright (c) 2014 alg
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace alg\addonforthanksforposts;

/**
* Main extension class for this extension.
*/
class ext extends \phpbb\extension\base
{
	public function is_enableable()
	{
		global $phpbb_extension_manager;
		return $phpbb_extension_manager->is_enabled('gfksx/ThanksForPosts');
	}
}
