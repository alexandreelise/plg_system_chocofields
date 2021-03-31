<?php
/**
 * @package       script
 * @author        Alexandre ELISÉ <contact@alexandre-elise.fr>
 * @link          https://alexandre-elise.fr
 * @copyright (c) 2020 . Alexandre ELISÉ . Tous droits réservés.
 * @license       GPL-2.0-and-later GNU General Public License v2.0 or later
 * Created Date : 26/08/2020
 * Created Time : 09:17
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Installer\InstallerScript;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Version;

defined('_JEXEC') or die;


/**
 * Class PlgSystemChocofieldsInstallerScript
 */
class PlgSystemChocofieldsInstallerScript extends InstallerScript
{
	/**
	 * PlgSystemChocofieldsInstallerScript constructor.
	 *
	 * @param   InstallerAdapter  $adapter
	 *
	 * @throws \Exception
	 */
	public function __construct($adapter)
	{
		$this->minimumJoomla = '3.9';
		$this->minimumPhp    = 7.2;
	}

	/**
	 * Called before any type of action
	 *
	 * @param   string            $type     Which action is happening (install|uninstall|discover_install|update)
	 * @param   InstallerAdapter  $adapter  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 * @throws \Exception
	 */
	public function preflight($type, $adapter)
	{
		$app = Factory::getApplication();

		if (strtolower($type) === 'uninstall')
		{
			return true;
		}

		$jversion = new Version();

		// Running Joomla! 2.5
		if (!$jversion->isCompatible('3.0.0'))
		{
			$app->enqueueMessage(Text::_('PLG_SYSTEM_CHOCOFIELDS_MESSAGE_IS_COMPATIBLE', true), 'error');

			return false;
		}

		// Running 3.x
		if (!$jversion->isCompatible('3.9.0'))
		{
			$app->enqueueMessage(Text::_('PLG_SYSTEM_CHOCOFIELDS_MESSAGE_PLEASE_UPGRADE', true), 'error');

			return false;
		}


		return true;
	}

	/**
	 * @param $adapter
	 *
	 *
	 * @since version
	 */
	public function install($adapter)
	{
		// during install
	}

	/**
	 * @param $adapter
	 *
	 *
	 * @since version
	 */
	public function uninstall($adapter)
	{
		// during uninstall
	}

	/**
	 * @param $adapter
	 *
	 *
	 * @since version
	 */
	public function update($adapter)
	{
		// during update
	}

	/**
	 * Called after any type of action
	 *
	 * @param   string            $type     Which action is happening (install|uninstall|discover_install|update)
	 * @param   InstallerAdapter  $adapter  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 * @throws \Exception
	 */
	public function postflight($type, $adapter)
	{
		$app = Factory::getApplication();

		$app->enqueueMessage(Text::_('PLG_SYSTEM_CHOCOFIELDS_POSTFLIGHT_' . strtoupper($type) . '_MESSAGE', true));

		if (in_array(strtolower($type), ['install', 'discover_install', 'update']))
		{
			return true;
		}

		return true;
	}
}
