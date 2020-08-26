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

use AE\Library\CustomField\Core\Constant;
use AE\Library\CustomField\Util\Util;
use JLoader;
use Joomla\CMS\Factory;
use Joomla\CMS\Log\Log;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\String\PunycodeHelper;
use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;
use stdClass;
use function defined;
use function in_array;
use function trim;
use function urlencode;
use const DIRECTORY_SEPARATOR;

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
	 * @param   stdClass                   $article  Joomla article
	 *
	 * @param   \Joomla\Registry\Registry  $plugin_params
	 *
	 * @return bool
	 */
	public static function updateArticleCustomFields(\stdClass $article, Registry $plugin_params): bool
	{
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

		$fields = \FieldsHelper::getFields('com_content.article', $article);

		$custom_fields_by_name = ArrayHelper::pivot($fields, 'name');

		$update           = ((int) ($custom_fields_by_name['cf-update']->rawvalue) === 1);
		$idExternalSource = trim((string) ($custom_fields_by_name['id-external-source']->rawvalue ?? ''));


		// We update a Article only if its Custom Field is set on Yes and if the
		// ID of the External Source is filled in
		if ($update && ('' !== $idExternalSource))
		{
			// Query f.i. https://social.brussels/rest/organisation/13219
			$url = PunycodeHelper::urlToUTF8($plugin_params->get('domain', 'https://social.brussels/rest/organisation/') ?? '') . urlencode($idExternalSource);

			$contentResponse = Transport::getCurlContent($url);

			if (in_array($contentResponse->getCode(), [Constant::HTTP_OK, Constant::HTTP_FOUND], true))
			{
				// Updating custom fields in the article
				return static::updateCustomFields((int) $article->id, $custom_fields_by_name, $contentResponse->getBody());
			}
            return false;
		}

		return true;
	}

	/**
	 * Update of the Custom Fields values based on the external source (webservices).
	 *
	 * @param   int     $articleId              The ID of the article
	 * @param   array   $custom_fields_by_name  The list of custom fields
	 * @param   string  $http_response_body     The response body of http request
	 *
	 * @return bool True on success
	 */
	private static function updateCustomFields(int $articleId, array $custom_fields_by_name, string $http_response_body): bool
	{
		$jsonArray = Util::getJsonArray($http_response_body);

		// define component path constants manually for cli to work
		//Util::pathDefined();

		/**
		 * @var \FieldsModelField $model
		 */
		$model = BaseDatabaseModel::getInstance('Field', 'FieldsModel', ['ignore_request' => true]);

		$result = false;
		foreach ($custom_fields_by_name as $name => $field)
		{
			// we mention here all the Custom Fields which should be ignored by the plugin
			if (('id-external-source' === $name) || ('cf-update' === $name))
			{
				continue;
			}

			// then, for every Custom Field where we want to fill in the value we
			// simply specify the value in the json provided by the external source
			switch ($name)
			{
				case 'labelfr':
					$value = $jsonArray['legalStatus']['labelFr'] ?? '';

					break;
				case 'labelnl':
					$value = $jsonArray['legalStatus']['labelNl'] ?? '';

					break;
				case 'nameofficialfr':
					$value = $jsonArray['nameOfficialFr'] ?? '';

					break;
				case 'nameofficialnl':
					$value = $jsonArray['nameOfficialNl'] ?? '';

					break;
				case 'descriptionfr':
					$value = $jsonArray['descriptionFr'] ?? '';

					break;
				case 'descriptionnl':
					$value = $jsonArray['descriptionNl'] ?? '';

					break;
				case 'permanencyfr':
					$value = $jsonArray['permanencyFr'] ?? '';

					break;
				case 'permanencynl':
					$value = $jsonArray['permanencyNl'] ?? '';

					break;
				case 'legalfr':
					$value = $jsonArray['legalStatus']['labelFr'] ?? '';

					break;
				case 'legalnl':
					$value = $jsonArray['legalStatus']['labelNl'] ?? '';

					break;
				case 'streetfr':
					$value = $jsonArray['address']['streetFr'] ?? '';

					break;
				case 'streetnl':
					$value = $jsonArray['address']['streetNl'] ?? '';

					break;
				case 'emailfr':
					$value = Util::getRepeat($jsonArray['emailFr'] ?? '', 'emailfr', 'email');

					break;
				case 'emailnl':
					$value = Util::getRepeat($jsonArray['emailNl'] ?? '', 'emailnl', 'email');

					break;
				default:
					// Default value in case some Custom Field would not be found
					// (also for example because its Name is misspelled in the backend)
					$value = 'That Custom Field ' . $field->title . ' was not found';
			}

			$result = $model->setFieldValue((string) $field->id, (string) $articleId, (string) $value);
		}

		return $result;
	}


}
