<?php
declare(strict_types=1);
/**
 * Utilities used in this plugin
 *
 * @package       Util
 * @author        Alexandre ELISÉ <contact@alexandre-elise.fr>
 * @link          https://alexandre-elise.fr
 * @copyright (c) 2020 . Alexandre ELISÉ . Tous droits réservés.
 * @license       GPL-2.0-and-later GNU General Public License v2.0 or later
 * Created Date : 22/08/2020
 * Created Time : 12:08
 */

namespace AE\Library\CustomField\Util;

use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\Registry\Registry;
use function define;
use function defined;
use function explode;
use function json_decode;
use function json_encode;
use function strpos;
use const DIRECTORY_SEPARATOR;

\defined('_JEXEC') or die;

/**
 * Utilities used in this plugin
 *
 * @package     AE\Library\CustomField\Util
 *
 * @since       version
 */
abstract class Util
{

	/**
	 * Get plugin system plugin params easily from anywhere in the code
	 *
	 * @return \Joomla\Registry\Registry
	 *
	 * @since version
	 */
	public static function getMainPluginParams(): Registry
	{
		/**
		 * @var \PlgSystemUpdatecf $plugin
		 */
		$plugin = PluginHelper::getPlugin('system', 'updatecf');

		return (new Registry($plugin->params));
	}

	/**
	 * Extract hours part (hh) from time based on format (hh:mm)
	 *
	 * @param   string  $time
	 *
	 * @return string
	 *
	 * @since version
	 */
	public static function extractHourFromTime(string $time): string
	{
		return explode(':', $time)[0] ?? '00';
	}

	/**
	 * Extract minutes part (mm) from time based on format (hh:mm)
	 *
	 * @param   string  $time
	 *
	 * @return string
	 *
	 * @since version
	 */
	public static function extractMinuteFromTime(string $time): string
	{
		return explode(':', $time)[1] ?? '00';
	}


	/**
	 * Formatting the received message in JSon mode.
	 *
	 * @param   string  $json  The JSON message
	 *
	 * @return array
	 * @author    Marc Dechèvre <marc@woluweb.be>
	 * @author    Pascal Leconte <pascal.leconte@conseilgouz.com>
	 * @author    Christophe Avonture <christophe@avonture.be>
	 */
	public static function getJsonArray(string $json): array
	{
		return (array) json_decode($json, true);
	}

	/**
	 * Return a field from an array in a text zone.
	 *
	 * @param   array   $array  An array
	 * @param   string  $field  A field name
	 *
	 * @return string The value of the field
	 */
	public static function getOneField(array $array, string $field): string
	{
		return $array[$field] ?? '';
	}

	/**
	 * Returns the elements of an array in a repeatable field.
	 *
	 * @param   array   $array  An array
	 * @param   string  $field  A field
	 * @param   string  $name   A name
	 *
	 * @return string
	 */
	public static function getRepeat(array $array, string $field, string $name): string
	{
		$ix      = 0;
		$results = [];

		foreach ($array as $elem)
		{
			$item                                  = [];
			$item[$name]                           = $elem;
			$results[$field . '-repeatable' . $ix] = $item;
			++$ix;
		}

		return (string) json_encode($results);
	}

	/**
	 * Copy cli script from plugin folder to real joomla cli folder
	 *
	 * @return bool True on success
	 * @since version
	 */
	public static function copyCliScript(): bool
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
	 * Get "site" or "administrator" based current path
	 * @return string
	 *
	 * @since version
	 */
	public static function getClient()
	{
		return (strpos(JPATH_BASE, 'administrator') === false) ? 'site' : 'administrator';
	}

	/**
	 *
	 * @since version
	 */
	public static function pathDefined()
	{
		if (!defined('JPATH_COMPONENT')) {
			define('JPATH_COMPONENT', JPATH_BASE);
		}
		if (!defined('JPATH_COMPONENT_SITE')) {
			define('JPATH_COMPONENT_SITE', JPATH_SITE);
		}
		if (!defined('JPATH_COMPONENT_ADMINISTRATOR')) {
			define('JPATH_COMPONENT_ADMINISTRATOR', JPATH_ADMINISTRATOR);
		}
	}

}
