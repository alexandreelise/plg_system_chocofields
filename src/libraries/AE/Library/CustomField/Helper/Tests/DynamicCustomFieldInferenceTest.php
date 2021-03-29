<?php
/**
 * @package     AE\Tests\Helper
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace AE\Library\CustomField\Helper\Tests;

use \AE\Library\CustomField\Helper\DynamicCustomFieldInference;
use PHPUnit\Framework\TestCase;
use stdClass;
use function ob_get_clean;
use function ob_start;
use function str_repeat;

\defined('_JEXEC') or die;

/**
 * @package     AE\Tests\Helper
 *
 * @since       version
 */
class DynamicCustomFieldInferenceTest extends TestCase
{
	
	/**
	 * @covers \AE\Library\CustomField\Helper\DynamicCustomFieldInference
	 *
	 * @since version
	 */
	public function test__invoke_with_flatten_string_key_with_numeric_part()
	{
		$result = new stdClass();
		$result->inferredFieldType = 'list';
		$result->inferredFieldFilter = 'JComponentHelper::filterText';
		
        $expected = $result;
		$dynamicCustomFieldInference = new DynamicCustomFieldInference();
		$actual = $dynamicCustomFieldInference('ingredient.42', 42);
        self::assertEqualsWithDelta($expected, $actual,0.1);
	}
	
	/**
	 * @covers \AE\Library\CustomField\Helper\DynamicCustomFieldInference
	 *
	 * @since version
	 */
	public function test__invoke_with_float_value()
	{
		$result = new stdClass();
		$result->inferredFieldType = 'text';
		$result->inferredFieldFilter = 'float';
		
		$expected = $result;
		$dynamicCustomFieldInference = new DynamicCustomFieldInference();
		$actual = $dynamicCustomFieldInference('price', 19.99);
		self::assertEqualsWithDelta($expected, $actual,0.1);
	}
	
	/**
	 * @covers \AE\Library\CustomField\Helper\DynamicCustomFieldInference
	 *
	 * @since version
	 */
	public function test__invoke_with_integer_value()
	{
		$result = new stdClass();
		$result->inferredFieldType = 'text';
		$result->inferredFieldFilter = 'integer';
		
		$expected = $result;
		$dynamicCustomFieldInference = new DynamicCustomFieldInference();
		$actual = $dynamicCustomFieldInference('quantity', 5);
		self::assertEqualsWithDelta($expected, $actual,0.1);
	}
	
	/**
	 * @covers \AE\Library\CustomField\Helper\DynamicCustomFieldInference
	 *
	 * @since version
	 */
	public function test__invoke_with_text_value_with_less_than_255_chars()
	{
		$result = new stdClass();
		$result->inferredFieldType = 'text';
		$result->inferredFieldFilter = 'JComponentHelper::filterText';
		
		$expected = $result;
		$dynamicCustomFieldInference = new DynamicCustomFieldInference();
		$actual = $dynamicCustomFieldInference('name', str_repeat('a', 254));
		self::assertEqualsWithDelta($expected, $actual,0.1);
	}
	
	/**
	 * @covers \AE\Library\CustomField\Helper\DynamicCustomFieldInference
	 *
	 * @since version
	 */
	public function test__invoke_with_text_value_with_more_than_255_chars_and_less_than_400_chars()
	{
		$result = new stdClass();
		$result->inferredFieldType = 'textarea';
		$result->inferredFieldFilter = 'JComponentHelper::filterText';
		
		$expected = $result;
		$dynamicCustomFieldInference = new DynamicCustomFieldInference();
		$actual = $dynamicCustomFieldInference('name', str_repeat('a', 399));
		self::assertEqualsWithDelta($expected, $actual,0.1);
	}
	
	/**
	 * @covers \AE\Library\CustomField\Helper\DynamicCustomFieldInference
	 *
	 * @since version
	 */
	public function test__invoke_with_text_value_with_more_than_400_chars()
	{
		$result = new stdClass();
		$result->inferredFieldType = 'editor';
		$result->inferredFieldFilter = 'safehtml';
		
		$expected = $result;
		$dynamicCustomFieldInference = new DynamicCustomFieldInference();
		$actual = $dynamicCustomFieldInference('name', str_repeat('a', 2000));
		self::assertEqualsWithDelta($expected, $actual,0.1);
	}

}
