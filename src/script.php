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
 * @package     ${NAMESPACE}
 *
 * @since       version
 */
class PlgSystemUpdatecfInstallerScript extends InstallerScript
{
	/**
	 * Destination path of
	 *
	 * @var string $dst
	 */
	private $dst;


	/**
	 * PlgSystemUpdatecfInstallerScript constructor.
	 *
	 * @param   InstallerAdapter  $adapter
	 *
	 * @throws \Exception
	 */
	public function __construct($adapter)
	{
		$this->dst           = JPATH_ROOT . DIRECTORY_SEPARATOR . 'cli' . DIRECTORY_SEPARATOR;
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
			$app->enqueueMessage(Text::_('PLG_SYSTEM_UPDATECF_MESSAGE_REMOVING_CLI_SCRIPT', true));
			if ($this->removeCliScript() === false)
			{
				$app->enqueueMessage(Text::sprintf('PLG_SYSTEM_UPDATECF_MESSAGE_CANNOT_REMOVE_CLI_SCRIPT', implode('<br>', [$this->dst . 'updatecf-cli.php'])), 'error');

				return false;
			}
			$app->enqueueMessage(Text::_('PLG_SYSTEM_UPDATECF_MESSAGE_REMOVED_SUCCESSFULLY', true));

			return true;
		}

		$jversion = new Version();

		// Running Joomla! 2.5
		if (!$jversion->isCompatible('3.0.0'))
		{
			$app->enqueueMessage(Text::_('PLG_SYSTEM_UPDATECF_MESSAGE_IS_COMPATIBLE', true), 'error');

			return false;
		}

		// Running 3.x
		if (!$jversion->isCompatible('3.9.0'))
		{
			$app->enqueueMessage(Text::_('PLG_SYSTEM_UPDATECF_MESSAGE_PLEASE_UPGRADE', true), 'error');

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

		$app->enqueueMessage(Text::_('PLG_SYSTEM_UPDATECF_POSTFLIGHT_' . strtoupper($type) . '_MESSAGE', true));

		if (in_array(strtolower($type), ['install', 'discover_install', 'update']))
		{
			$result = $this->copyCliScript();

			if ($result === false) {
				$app->enqueueMessage(Text::_('PLG_SYSTEM_UPDATECF_MESSAGE_CANNOT_COPY_CLI_SCRIPT', true), 'error');
				return false;
			}
			$app->enqueueMessage(Text::_('PLG_SYSTEM_UPDATECF_MESSAGE_CLI_SCRIPT_COPIED_SUCCESSFULLY', true));
			return true;
		}

		return true;
	}


	/**
	 * Copy cli script from plugin folder to real joomla cli folder
	 *
	 * @return bool True on success
	 * @since version
	 */
	private function copyCliScript()
	{
		// can be used by a real cronjob scheduler
		$sourceCliScriptFileName =
			JPATH_PLUGINS
			. DIRECTORY_SEPARATOR
			. 'system'
			. DIRECTORY_SEPARATOR
			. 'updatecf'
			. DIRECTORY_SEPARATOR
			. 'cli'
			. DIRECTORY_SEPARATOR
			. 'updatecf-cli.php';

		// destination folder
		$destinationCliScriptFileName =
			JPATH_ROOT
			. DIRECTORY_SEPARATOR
			. 'cli'
			. DIRECTORY_SEPARATOR
			. 'updatecf-cli.php';

		// copy joomla cli application script from this plugin cli folder
		// to the real joomla default cli folder
		return File::copy(
			$sourceCliScriptFileName,
			$destinationCliScriptFileName
		);
	}


	/**
	 * Remove cli script installed to handle cron tasks
	 *
	 * @return bool True when successfully deleted cli script
	 */
	private function removeCliScript()
	{
		$filename = $this->dst . 'updatecf-cli.php';

		if (file_exists($filename))
		{
			return unlink($filename);
		}

		return true;
	}
}
