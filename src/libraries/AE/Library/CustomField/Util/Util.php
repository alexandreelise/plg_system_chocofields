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
use AE\Library\CustomField\ErrorHandling\NotFoundException;
use AE\Library\CustomField\ErrorHandling\UnprocessableEntityException;
use FieldsModelField;
use Joomla\CMS\Http\Http;
use Joomla\CMS\Http\Transport\StreamTransport;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\String\PunycodeHelper;
use Joomla\CMS\Table\Table;
use Joomla\Registry\Registry;
use function define;
use function defined;
use function hash;
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
		 * @var \PlgSystemChocofields $plugin
		 */
		$plugin = PluginHelper::getPlugin('system', 'chocofields');
		
		return (new Registry($plugin->params));
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
	 * Sanitize the result of baseUrl and id
	 *
	 * @param   string       $baseUrl
	 * @param   int|null  $id
	 *
	 * @return string
	 *
	 * @since version
	 */
	public static function cleanUrl(string $baseUrl, ?int $id = null): string
	{
		if (empty($baseUrl))
		{
			return '';
		}
		
		return PunycodeHelper::urlToUTF8($baseUrl) . (isset($id) ? $id : '');
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
	 * Did the user chose to activate logging in chocofields plugin params?
	 *
	 * @return bool true active false otherwise
	 *
	 * @since version
	 */
	public static function isLogActive(): bool
	{
		return (((int) self::getMainPluginParams()->get('log')) === 1);
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
	 * @param   string    $baseUrl
	 * @param   int|null  $id
	 *
	 * @return string json response on success false on error
	 * @throws \AE\Library\CustomField\ErrorHandling\NotFoundException
	 * @throws \AE\Library\CustomField\ErrorHandling\UnprocessableEntityException
	 * @since version
	 */
	public static function fetchApiData(string $baseUrl, ?int $id = null)
	{
		// 1. GET request to fetch url content
		$url = Util::cleanUrl($baseUrl, $id);
		
		$http = self::createHttpClient();
		
		$getRequestHeaders = [
			'Content-Type' => 'application/json',
		];
		
		$contentResponse = $http->get($url, $getRequestHeaders);
		
		// 2. Try to decode json response to assoc array
		if (!in_array((int) $contentResponse->code, [Constant::HTTP_OK, Constant::HTTP_FOUND], true))
		{
			throw new NotFoundException();
		}
		
		$jsonResponse = $contentResponse->body ?? '';
		
		if (empty($jsonResponse))
		{
			throw new UnprocessableEntityException();
		}
		
		return $jsonResponse;
	}
	
	/**
	 * Create Http Client instance and return it
	 *
	 * @return \Joomla\CMS\Http\Http
	 *
	 * @since version
	 */
	private static function createHttpClient()
	{
		$http_options = new Registry([]);
		
		// DO NOT DO THIS IN PRODUCTION
		// ALWAYS SET verify_peer, verify_peer_name to true
		// AND allow_self_signed to false
		$transport_options = new Registry([
			'ssl' => [
				'verify_peer'       => false,
				'verify_peer_name'  => false,
				'allow_self_signed' => true,
			],
		]);
		
		$transport = new StreamTransport(
			$transport_options);
		
		return (new Http($http_options, $transport));
	}
	
	/**
	 * Create Field Model
	 * @return \FieldsModelField
	 *
	 * @since version
	 */
	public static function createFieldModel(): FieldsModelField
	{
		//quick fix due to warning
		defined('JPATH_COMPONENT') || define('JPATH_COMPONENT', JPATH_BASE . '/components/com_fields');
		
		BaseDatabaseModel::addIncludePath(JPATH_ADMINISTRATOR
			. DIRECTORY_SEPARATOR
			. 'components'
			. DIRECTORY_SEPARATOR
			. 'com_fields'
			. DIRECTORY_SEPARATOR
			. 'models'
		);
		
		/**
		 * @var \FieldsModelField $model
		 */
		return BaseDatabaseModel::getInstance('Field', 'FieldsModel', ['ignore_request' => true]);
		
	}
	
	/**
	 * Hashed data filename after each api fetch
	 * to mitigate duplicate fetch of the same content
	 *
	 * @param   int       $categoryId
	 * @param   int       $articleId
	 * @param   string    $baseUrl
	 * @param   int|null  $resourceId
	 *
	 * @return string
	 */
	private static function hashedDataFilename(int $categoryId, int $articleId, string $baseUrl, ?int $resourceId)
	{
		return hash('sha256', (string) ($categoryId . $articleId . $baseUrl . $resourceId));
	}
	
	
	/**
	 * Computed filename based on unique enough values
	 *
	 * @param   int          $categoryId
	 * @param   int          $articleId
	 * @param   string       $baseUrl
	 * @param   int|null     $resourceId
	 * @param   string|null  $givenPath
	 * @param   string|null  $givenPrefix
	 * @param   string|null  $givenExtension
	 *
	 * @return string
	 */
	public static function computeDataFilename(int $categoryId, int $articleId, string $baseUrl, ?int $resourceId, ?string $givenPath = null, ?string $givenPrefix = null, ?string $givenExtension = null)
	{
		// cache api path
		$path = $givenPath ?? Constant::getDataDirectory();
		
		// file prefix
		$prefix = $givenPrefix ?? 'api-';
		
		// file extension
		$extension = $givenExtension ?? '.json';
		
		// unique enough hash to prevent duplicates
		$hash = self::hashedDataFilename($categoryId, $articleId, $baseUrl, $resourceId);
		
		$filename = $path . $prefix . $hash . $extension;
		
		return $filename;
	}
	
}
