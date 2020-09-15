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

use AE\Library\CustomField\Core\Constant;
use AE\Library\CustomField\Helper\Transport;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\String\PunycodeHelper;
use Joomla\CMS\Table\Table;
use Joomla\Registry\Registry;
use function define;
use function defined;
use function explode;
use function in_array;
use function json_decode;
use function json_encode;
use function str_replace;
use function strpos;
use function strtolower;
use function ucwords;
use function urlencode;
use const DIRECTORY_SEPARATOR;
use const JPATH_ADMINISTRATOR;

defined('_JEXEC') or die;

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
	public static function getClient(): string
	{
		return (strpos(JPATH_BASE, 'administrator') === false) ? 'site' : 'administrator';
	}
	
	/**
	 *
	 * @since version
	 */
	public static function pathDefined(): void
	{
		if (!defined('JPATH_COMPONENT'))
		{
			define('JPATH_COMPONENT', JPATH_BASE);
		}
		if (!defined('JPATH_COMPONENT_SITE'))
		{
			define('JPATH_COMPONENT_SITE', JPATH_SITE);
		}
		if (!defined('JPATH_COMPONENT_ADMINISTRATOR'))
		{
			define('JPATH_COMPONENT_ADMINISTRATOR', JPATH_ADMINISTRATOR);
		}
	}
	
	/**
	 * Sanitize the result of base_url and id
	 *
	 * @param   string       $base_url
	 * @param   string|null  $id
	 *
	 * @return string
	 *
	 * @since version
	 */
	public static function cleanUrl(string $base_url, ?string $id = null): string
	{
		if (empty($base_url))
		{
			return '';
		}
		
		return PunycodeHelper::urlToUTF8($base_url) . (isset($id) ? urlencode($id) : '');
	}
	
	/**
	 * Encode array to json string
	 *
	 * @param   array  $data
	 *
	 * @return string
	 *
	 * @since version
	 */
	public static function toJsonString(array $data): string
	{
		return json_encode($data);
	}
	
	/**
	 * Walk recursively in associative array data structure
	 *
	 * @param   array  $data
	 *
	 *
	 * @return array
	 * @since version
	 */
	public static function flattenAssocArray(array $data): array
	{
		return (new Registry($data))->flatten();
	}
	
	/**
	 * From flatten key to alias representation of the key
	 *
	 * @param   string  $key
	 *
	 * @return string
	 *
	 * @since version
	 */
	public static function realKey(string $key): string
	{
		return strtolower(str_replace('.', '-', $key));
	}
	
	/**
	 * Generate title from flatten key
	 *
	 * @param   string  $key
	 *
	 * @return string
	 *
	 * @since version
	 */
	public static function realTitle(string $key): string
	{
		return ucwords(str_replace('.', ' ', $key));
	}
	
	/**
	 * Did the user chose to activate logging in updatecf plugin params?
	 *
	 * @return bool true active false otherwise
	 *
	 * @since version
	 */
	public static function isLogActive(): bool
	{
		return (1 === (int) self::getMainPluginParams()->get('log'));
	}
	
	/**
	 * Is given field name unique?
	 *
	 * @param   string  $name
	 *
	 * @return bool
	 */
	public static function isUniqueFieldName(string $name): bool
	{
		Table::addIncludePath(JPATH_ADMINISTRATOR
			. DIRECTORY_SEPARATOR
			. 'components'
			. DIRECTORY_SEPARATOR
			. 'com_fields'
			. DIRECTORY_SEPARATOR
			. 'tables'
		);
		
		$table = Table::getInstance('Field', 'FieldsTable');
		
		return !$table->load(['name' => $name]);
	}
	
	
	/**
	 * fetch data from remote api returned as json
	 *
	 * @param   string       $base_url
	 * @param   string|null  $id
	 *
	 * @return string|boolean json response on success false on error
	 *
	 * @since version
	 */
	public static function fetchApiData(string $base_url, ?string $id = null)
	{
		// 1. GET request to fetch url content
		$url = Util::cleanUrl($base_url, $id);
		
		$content_response = Transport::getCurlContent($url);
		
		// 2. Try to decode json response to assoc array
		if (!in_array($content_response->getCode(), [Constant::HTTP_OK, Constant::HTTP_FOUND], true))
		{
			return false;
		}
		
		$json_response = $content_response->getBody() ?? '';
		
		if (empty($json_response))
		{
			return false;
		}
		
		return $json_response;
	}
}
