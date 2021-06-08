<?php
/**
 * Attempt to "infer" the type of custom field to dynamically create based on cached api
 *
 * @version       0.1.0
 * @package       DynamicCustomFieldInference
 * @author        Alexandre ELISÉ <contact@alexandre-elise.fr>
 * @copyright (c) 2009-2021 . Alexandre ELISÉ . Tous droits réservés.
 * @license       GPL-2.0-and-later GNU General Public License v2.0 or later
 * @link          https://alexandre-elise.fr
 */

namespace AE\Library\CustomField\Helper;

use RuntimeException;
use stdClass;
use function array_map;
use function is_bool;
use function is_float;
use function is_int;
use function is_string;
use function mb_strlen;
use function preg_match;

/**
 * @package     AE\Library\CustomField\Helper
 *
 * @since       version
 */
final class DynamicCustomFieldInference
{
	// constants used only in this class
	private const FIELD_TYPE_0 = 'text';
	private const FIELD_TYPE_1 = 'textarea';
	private const FIELD_TYPE_2 = 'editor';
	private const FIELD_TYPE_3 = 'radio';
	private const FIELD_TYPE_4 = 'list';
	
	private const FIELD_FILTER_0 = 'JComponentHelper::filterText';
	private const FIELD_FILTER_1 = 'integer';
	private const FIELD_FILTER_2 = 'float';
	private const FIELD_FILTER_3 = 'boolean';
	private const FIELD_FILTER_4 = 'safehtml';
	
	/**
	 * Try to condensed the whole script in this invoke method
	 * NOTE: it's bad for separation of concerns but it was to be in one place
	 * easy for beginners to read this code
	 *
	 * @param $key
	 * @param $value
	 *
	 * @return $this
	 *
	 * @since version
	 */
	public function __invoke($key, $value)
	{
		$output = new stdClass();
		
		if ((preg_match('/\.([0-9]{1,10})$/', $key) > 0))
		{
			$output->inferredFieldType   = self::FIELD_TYPE_4;
			$output->inferredFieldFilter = self::FIELD_FILTER_0;
			return $output;
		}
		if (is_string($value))
		{
			if (mb_strlen($value) >= 0 && mb_strlen($value) <= 255)
			{
				$output->inferredFieldType   = self::FIELD_TYPE_0;
				$output->inferredFieldFilter = self::FIELD_FILTER_0;
				
				return $output;
			}
			
			if (mb_strlen($value) > 255 && mb_strlen($value) <= 400)
			{
				$output->inferredFieldType   = self::FIELD_TYPE_1;
				$output->inferredFieldFilter = self::FIELD_FILTER_0;
				
				return $output;
			}
			if (mb_strlen($value) > 400)
			{
				$output->inferredFieldType   = self::FIELD_TYPE_2;
				$output->inferredFieldFilter = self::FIELD_FILTER_4;
				
				return $output;
			}
		}
		if (is_int($value))
		{
			$output->inferredFieldType   = self::FIELD_TYPE_0;
			$output->inferredFieldFilter = self::FIELD_FILTER_1;
			
			return $output;
		}
		if (is_float($value))
		{
			$output->inferredFieldType   = self::FIELD_TYPE_0;
			$output->inferredFieldFilter = self::FIELD_FILTER_2;
			
			return $output;
		}
		if (is_bool($value))
		{
			$output->inferredFieldType   = self::FIELD_TYPE_0;
			$output->inferredFieldFilter = self::FIELD_FILTER_3;
			
			return $output;
		}
		if ($value === null)
		{
			$output->inferredFieldType   = self::FIELD_TYPE_0;
			$output->inferredFieldFilter = self::FIELD_FILTER_0;
			
			return $output;
		}
		
		
		throw new RuntimeException('No custom field type could be inferred from given data', 500);
		
	}
}
