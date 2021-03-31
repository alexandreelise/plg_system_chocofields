<?php
declare(strict_types=1);
/**
 * Dynamic Mapping Helper
 * Handle mapping between local fields and remote api endpoint fields
 *
 * @package       DynamicMapping
 * @author        Alexandre ELISÉ <contact@alexandre-elise.fr>
 * @link          https://alexandre-elise.fr
 * @copyright (c) 2020 . Alexandre ELISÉ . Tous droits réservés.
 * @license       GPL-2.0-and-later GNU General Public License v2.0 or later
 * Created Date : 27/08/2020
 * Created Time : 11:09
 */

namespace AE\Library\CustomField\Helper;

use AE\Library\CustomField\Core\Constant;
use AE\Library\CustomField\ErrorHandling\NotFoundException;
use AE\Library\CustomField\ErrorHandling\UnprocessableEntityException;
use AE\Library\CustomField\Util\Util;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Language\Text;
use function defined;
use function file_exists;
use function file_get_contents;
use function filesize;
use function trim;

defined('_JEXEC') or die;

/**
 * Dynamic Mapping Helper
 * Handle mapping between local fields and remote api endpoint fields
 *
 * @package     AE\Library\CustomField\Helper
 *
 * @since       version
 */
abstract class DynamicMapping
{
	
	/**
	 * Prefill dynamic mapping fields based of fetched data from baseUrl
	 *
	 * 1. GET request to fetch content from url
	 * 2. If it is json data get only the first level keys
	 * 3. create a json string from thoses keys to prepopulate the remote fields names
	 *
	 * @param   int       $categoryId
	 * @param   int       $articleId
	 * @param   string    $baseUrl
	 * @param   int|null  $resourceId
	 *
	 * @return bool true on success
	 * @throws \AE\Library\CustomField\ErrorHandling\UnprocessableEntityException
	 * @since version
	 */
	public static function prefillRemoteFields(int $categoryId, int $articleId, string $baseUrl, ?int $resourceId = null): bool
	{
		$jsonArray = DataManager::createIfNotExistsArticleJsonApiFile($categoryId, $articleId, $baseUrl, $resourceId);
		
		$remoteDataStructure = Util::flattenAssocArray($jsonArray);
		$resultGenerated = self::generateCustomFields($remoteDataStructure);
		return $resultGenerated;
	}
	
	
	/**
	 * @param   array  $flattenJsonArray
	 *
	 * @return bool
	 *
	 * @since version
	 */
	public static function generateCustomFields(array $flattenJsonArray = []): bool
	{
		$plugin_params = Util::getMainPluginParams();
		
		$model = Util::createFieldModel();
		
		$context = trim($plugin_params->get('field_context', 'com_content.article'));
		
		$output = false;
		$infer  = (new DynamicCustomFieldInference());
		
		foreach ($flattenJsonArray as $key => $value)
		{
			$title = Util::realTitle($key);
			$name  = Util::realKey($key);
			
			// process next field if already created
			if (false === Util::isUniqueFieldName($name))
			{
				continue;
			}
			
			$data = static::createFieldData(
				$context,
				$title,
				$name,
				$infer($key, $value)->inferredFieldType,
				[
					"hint"               => "",
					"class"              => "",
					"label_class"        => "",
					"show_on"            => "1",
					"render_class"       => "",
					"showlabel"          => "1",
					"label_render_class" => "",
					"display"            => "2",
					"layout"             => "",
					"display_readonly"   => "1",
				],
				['filter' => $infer($key, $value)->inferredFieldFilter],
				null);
			$output = $model->save($data);
		}
		
		return $output;
	}
	
	/**
	 * Data representing a custom field
	 *
	 * @param   string  $context
	 * @param   string  $title
	 * @param   string  $name
	 * @param   string  $type
	 * @param   array   $params
	 *
	 * @param   array   $fieldparams
	 * @param   null    $default_value
	 *
	 * @return array
	 *
	 * @since version
	 */
	private static function createFieldData(
		string $context,
		string $title,
		string $name,
		string $type,
		array $params = [],
		array $fieldparams = [],
		$default_value = null
	): array
	{
		$data                  = [];
		$data['title']         = $title;
		$data['name']          = $name;
		$data['label']         = $title;
		$data['description']   = $title;
		$data['type']          = $type;
		$data['default_value'] = $default_value;
		$data['context']       = $context;
		$data['params']        = $params;
		$data['fieldparams']   = $fieldparams;
		$data['language']      = '*';
		$data['access']        = 1;
		$data['state']         = 1;
		
		return $data;
	}
	
}
