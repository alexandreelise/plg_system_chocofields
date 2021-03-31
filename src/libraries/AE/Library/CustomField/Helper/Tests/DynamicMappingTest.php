<?php
declare(strict_types=1);
/**
 * DynamicMappingTest
 *
 * @version       0.1.0
 * @package       DynamicMappingTest
 * @author        Alexandre ELISÉ <contact@alexandre-elise.fr>
 * @copyright (c) 2009-2021 . Alexandre ELISÉ . Tous droits réservés.
 * @license       GPL-2.0-and-later GNU General Public License v2.0 or later
 * @link          https://coderparlerpartager.fr
 */


namespace AE\Library\CustomField\Helper\Tests;

use AE\Library\CustomField\Helper\DynamicMapping;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

\defined('_JEXEC') or die;


/**
 * @package     AE\Library\CustomField\Helper\Tests
 *
 * @since       version
 */
class DynamicMappingTest extends TestCase
{
	
	/**
	 *
	 * @covers \AE\Library\CustomField\Helper\DynamicMapping::prefillRemoteFields
	 * @covers \AE\Library\CustomField\Core\Constant::getDataDirectory
	 * @covers \AE\Library\CustomField\Helper\DynamicCustomFieldInference::__invoke
	 * @covers \AE\Library\CustomField\Helper\DynamicMapping::createFieldData
	 * @covers \AE\Library\CustomField\Helper\DynamicMapping::generateCustomFields
	 * @covers \AE\Library\CustomField\Util\Util::createFieldModel
	 * @covers \AE\Library\CustomField\Util\Util::flattenAssocArray
	 * @covers \AE\Library\CustomField\Util\Util::getJsonArray
	 * @covers \AE\Library\CustomField\Util\Util::getMainPluginParams
	 * @covers \AE\Library\CustomField\Util\Util::isUniqueFieldName
	 * @covers \AE\Library\CustomField\Util\Util::realKey
	 * @covers \AE\Library\CustomField\Util\Util::realTitle
	 * @covers \AE\Library\CustomField\Helper\DataManager::createIfNotExistsArticleJsonApiFile
	 * @covers \AE\Library\CustomField\Util\Util::computeDataFilename
	 * @covers \AE\Library\CustomField\Util\Util::hashedDataFilename
	 * @since  \version
	 */
	public function testPrefillRemoteFieldsWithValidDataReturnsNonEmptyArray()
	{
		$expected = true;
		
		$categoryId = 14;
		
		$articleId = 74;
		
		$baseUrl = 'https://social.brussels/rest/organisation/';
		
		$default_resource_id = 13219;
		$actual              = DynamicMapping::prefillRemoteFields($categoryId, $articleId, $baseUrl, $default_resource_id);
		Assert::assertSame($expected, $actual);
	}
}
