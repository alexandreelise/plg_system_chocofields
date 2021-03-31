<?php
declare(strict_types=1);
/**
 * Content related helper
 *
 * @package       Content
 * @author        Alexandre ELISÉ <contact@alexandre-elise.fr>
 * @link          https://alexandre-elise.fr
 * @copyright (c) 2020 . Alexandre ELISÉ . Tous droits réservés.
 * @license       GPL-2.0-and-later GNU General Public License v2.0 or later
 * Created Date : 21/08/2020
 * Created Time : 20:17
 */

namespace AE\Library\CustomField\Helper;

use AE\Library\CustomField\Util\Util;
use FieldsHelper;
use JLoader;
use Joomla\CMS\Cache\Exception\CacheConnectingException;
use Joomla\CMS\Cache\Exception\UnsupportedCacheException;
use Joomla\CMS\Factory;
use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;
use function defined;
use function sha1;
use function trim;
use const DIRECTORY_SEPARATOR;
use const JPATH_ADMINISTRATOR;

defined('_JEXEC') or die;

/**
 * Content related helper
 *
 * @package     AE\Library\CustomField\Helper
 *
 * @since       version
 */
abstract class Content
{
	/**
	 * Get all categories.
	 * @return array
	 * @author    Christophe Avonture <christophe@avonture.be>
	 * @author    Marc Dechèvre <marc@woluweb.be>
	 * @author    Pascal Leconte <pascal.leconte@conseilgouz.com>
	 */
	public static function getAllCategories(): array
	{
		$db = Factory::getDbo();
		
		$query = $db->getQuery(true);
		
		$query->select('distinct `cat`.`id`,count(`cont`.`id`) AS `count`,`cat`.note')
			->from('#__categories AS `cat`')
			->join('LEFT', '#__content cont on cat.id = cont.catid')
			->where('(extension LIKE "com_content") AND (cat.published = 1) AND (cat.access = 1) AND (cont.state = 1)')
			->group('cont.catid');
		
		$db->setQuery($query);
		
		return $db->loadObjectList();
	}
	
	
	/**
	 * For each Article, decides whether to trigger or not the update of the Custom Field values.
	 *
	 * @param                              $article
	 *
	 * @param   \Joomla\Registry\Registry  $plugin_params
	 *
	 * @return bool
	 */
	public static function updateArticleCustomFields($article, Registry $plugin_params): bool
	{
		
		$attribs = Util::getJsonArray($article->attribs ?? '');
		
		$update           = (int)$attribs['cf_update'];
		
		$baseUrl = trim($attribs['base_url'] ?? $plugin_params->get('base_url','https://social.brussels/rest/organisation/'));
		
		
		$resourceId = (int) ($attribs['resource_id'] ?? $plugin_params->get('default_resource_id',13219));
		
		
		// We update a Article only if its Custom Field is set on Yes
		// and if the URL and ID of the External Source is filled in
		if ($update && !empty($baseUrl) && !empty($resourceId))
		{
			$categoryId = (int)$article->catid;
			$articleId = (int)$article->id;
			
			// if custom fields created successfully from api results continue otherwise stop here
			$isCustomFieldsCreated = DynamicMapping::prefillRemoteFields($categoryId, $articleId, $baseUrl,$resourceId);
		
			if (!$isCustomFieldsCreated) {
				
				return false;
			}
			
			
			JLoader::setup();
			
			JLoader::register('FieldsHelper',
				JPATH_ADMINISTRATOR
				. DIRECTORY_SEPARATOR
				. 'components'
				. DIRECTORY_SEPARATOR
				. 'com_fields'
				. DIRECTORY_SEPARATOR
				. 'helpers'
				. DIRECTORY_SEPARATOR
				. 'fields.php'
			);
			
			$fields = FieldsHelper::getFields('com_content.article', $article, false, []);
			
			$customFieldsByName = ArrayHelper::pivot($fields ?? [], 'name');
			
			return static::updateCustomFields($categoryId, $articleId, $customFieldsByName,$baseUrl, $resourceId);
		}
		
		return true;
	}
	
	/**
	 * Update of the Custom Fields values based on the external source (webservices).
	 *
	 * @param   int       $categoryId
	 * @param   int       $articleId  The ID of the article
	 * @param   array     $customFieldsByName
	 * @param   string    $baseUrl
	 * @param   int|null  $resourceId
	 *
	 * @return bool True on success
	 * @throws \AE\Library\CustomField\ErrorHandling\UnprocessableEntityException
	 */
	private static function updateCustomFields(int  $categoryId, int $articleId, array $customFieldsByName, string $baseUrl, ?int $resourceId = null): bool
	{
		$jsonArray = DataManager::createIfNotExistsArticleJsonApiFile($categoryId, $articleId, $baseUrl, $resourceId);
		
		$flattenJsonArray = Util::flattenAssocArray($jsonArray);
		
		$model = Util::createFieldModel();
		
		foreach ($flattenJsonArray as $key => $value)
		{
			$custom_field = $customFieldsByName[Util::realKey($key)];
			$model->setFieldValue((string) $custom_field->id, (string) $articleId, (string) $value);
			
		}
		
		
		return true;
	}
	
	
}
