<?php
declare(strict_types=1);
/**
 * @package       DynamicMappingTest
 * @author        Alexandre ELISÉ <contact@alexandre-elise.fr>
 * @link          https://alexandre-elise.fr
 * @copyright (c) 2020 . Alexandre ELISÉ . Tous droits réservés.
 * @license       GPL-2.0-and-later GNU General Public License v2.0 or later
 * Created Date : 14/09/2020
 * Created Time : 22:32
 */


namespace AE\Tests;

use AE\Library\CustomField\Helper\DynamicMapping;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use function ob_end_clean;
use function ob_start;

defined('_JEXEC') or die;


/**
 * @package     AE\Tests
 *
 * @since       version
 */
class DynamicMappingTest extends TestCase
{
	
	/**
	 *
	 * @covers \AE\Library\CustomField\Helper\DynamicMapping::prefillRemoteFields
	 * @since version
	 */
	public function testPrefillRemoteFieldsWithValidDataReturnsNonEmptyArray()
	{
		/*$expected = [];
		
		$base_url = 'https://social.brussels/rest/organisation/';
		
		$default_resource_id = '13219';
		$actual = DynamicMapping::prefillRemoteFields($base_url, $default_resource_id);
		Assert::assertSame($expected, $actual); */
		self::markTestSkipped();
	}
}
