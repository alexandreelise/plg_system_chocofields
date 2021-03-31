<?php
declare(strict_types=1);

/**
 * Class that behaves like a mini cache
 * but files do no expire or get removed
 * user have to remove them manually for now
 *
 * @version       0.1.0
 * @package       DataManager
 * @author        Alexandre ELISÉ <contact@alexandre-elise.fr>
 * @copyright (c) 2009-2021 . Alexandre ELISÉ . Tous droits réservés.
 * @license       GPL-2.0-and-later GNU General Public License v2.0 or later
 * @link          https://coderparlerpartager.fr
 */

namespace AE\Library\CustomField\Helper;

use AE\Library\CustomField\Core\Constant;
use AE\Library\CustomField\ErrorHandling\UnprocessableEntityException;
use AE\Library\CustomField\Util\Util;
use Joomla\CMS\Filesystem\File;
use function file_exists;
use function file_get_contents;
use function filesize;

/**
 * Class DataManager
 * @package AE\Library\CustomField\Helper
 */
abstract class DataManager
{
	/**
	 *
	 * @param   int       $categoryId
	 * @param   int       $articleId
	 * @param   string    $baseUrl
	 * @param   int|null  $resourceId
	 *
	 * @return array
	 * @throws \AE\Library\CustomField\ErrorHandling\UnprocessableEntityException
	 */
	public static function createIfNotExistsArticleJsonApiFile(int $categoryId, int $articleId, string $baseUrl, ?int $resourceId = null): array
	{
		
		if (empty($categoryId) || empty($articleId) || empty($resourceId))
		{
			throw new UnprocessableEntityException("Invalid input data. Cannot continue.");
		}
		
		$filename = Util::computeDataFilename($categoryId, $articleId, $baseUrl, $resourceId);
		
		$jsonResponse = '';
		
		if (file_exists($filename) && (filesize($filename) > 1))
		{
			$jsonResponse = file_get_contents($filename);
		}
		elseif (!file_exists($filename) || (filesize($filename) < 1))
		{
			try
			{
				$jsonResponse = Util::fetchApiData($baseUrl, $resourceId);
				
				File::write($filename, $jsonResponse, true);
			}
			catch (UnprocessableEntityException $noContentException)
			{
				$jsonResponse = '';
			}
			catch (NotFoundException $notFoundException)
			{
				$jsonResponse = '';
			}
			
		}
		
		
		if (empty($jsonResponse))
		{
			throw new UnprocessableEntityException();
		}
		
		$jsonArray = Util::getJsonArray($jsonResponse);
		
		if (empty($jsonArray))
		{
			throw new UnprocessableEntityException();
		}
		
		return $jsonArray;
	}
}
