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
	 * Prefill dynamic mapping fields based of fetched data from base_url
	 *
	 * 1. GET request to fetch content from url
	 * 2. If it is json data get only the first level keys
	 * 3. create a json string from thoses keys to prepopulate the remote fields names
	 *
	 * @param   string       $base_url
	 * @param   string|null  $id
	 *
	 * @return bool true on success
	 * @throws \AE\Library\CustomField\ErrorHandling\NoContentException|\AE\Library\CustomField\ErrorHandling\NotFoundException
	 * @since version
	 */
	public static function prefillRemoteFields(string $base_url, ?string $id = null): bool
	{
		// cache api response
		$path = Constant::getDataDirectory();
		
		// extension
		$extension = '.json';
		
		$filename = $path . 'api' . $extension;
		
		
		$json_response = '';
		
		if (file_exists($filename) && (filesize($filename) > 1))
		{
			$json_response = file_get_contents($filename);
		}
		elseif (!file_exists($filename) || (filesize($filename) < 1))
		{
			try
			{
				$json_response = Util::fetchApiData($base_url, $id);
				
				File::write($filename, $json_response, true);
			}
			catch (UnprocessableEntityException $noContentException)
			{
				$json_response = '';
			}
			catch (NotFoundException $notFoundException)
			{
				$json_response = '';
			}
			
		}
		
		
		if (empty($json_response))
		{
			throw new UnprocessableEntityException();
		}
		
		$json_array = Util::getJsonArray($json_response);
		
		if (empty($json_array))
		{
			throw new UnprocessableEntityException();
		}
		
		$remote_data_structure = Util::flattenAssocArray($json_array);
		
		return (self::generateInitialFields() || self::generateCustomFields($remote_data_structure));
	}
	
	
	/**
	 * @param   array  $flatten_json_array
	 *
	 * @return bool
	 *
	 * @since version
	 */
	public static function generateCustomFields(array $flatten_json_array = []): bool
	{
		$plugin_params = Util::getMainPluginParams();
		
		$model = Util::createFieldModel();
		
		$context = trim($plugin_params->get('field_context', 'com_content.article'));
		
		$output = false;
		$infer  = (new DynamicCustomFieldInference());
		
		foreach ($flatten_json_array as $key => $value)
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
	
	/**
	 *
	 * @return bool
	 *
	 * @since version
	 */
	private static function generateInitialFields()
	{
		$plugin_params = Util::getMainPluginParams();
		
		$model = Util::createFieldModel();
		
		$context = trim($plugin_params->get('field_context', 'com_content.article'));
		
		// id of api url to fetch from
		$title = 'Id external source';
		$name  = 'id-external-source';
		
		$data = self::createFieldData(
			$context,
			$title,
			$name,
			'text',
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
			['filter' => 'integer'],
			null
		);
		
		$output = (Util::isUniqueFieldName($name) && $model->save($data));
		
		// radio yes or no update api
		
		// id of api url to fetch from
		$title_yes_no = Text::_('PLG_SYSTEM_UPDATECF_UPDATE_FIELD_LABEL', true);
		$name_yes_no  = 'cf-update';
		
		$data_yes_no = self::createFieldData(
			$context,
			$title_yes_no,
			$name_yes_no,
			'radio',
			[
				"hint"               => "",
				"class"              => "btn-group btn-group-yesno",
				"label_class"        => "",
				"show_on"            => "1",
				"render_class"       => "",
				"showlabel"          => "1",
				"label_render_class" => "",
				"display"            => "2",
				"layout"             => "",
				"display_readonly"   => "1",
			],
			[
				'options' => [
					'options0' => [
						'name'  => Text::_('JNO', true),
						'value' => 0,
					],
					'options1' => [
						'name'  => Text::_('JYES', true),
						'value' => 1,
					],
				],
				'filter'  => 'integer',
			], null
		);
		
		$output_yes_no = (Util::isUniqueFieldName($name_yes_no) && $model->save($data_yes_no));
		
		return ($output && $output_yes_no);
	}
	
}
