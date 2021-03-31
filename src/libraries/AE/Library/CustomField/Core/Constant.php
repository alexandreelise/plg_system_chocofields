<?php
declare(strict_types=1);
/**
 * All constants used in this plugin in one place
 *
 * @package       Constant
 * @author        Alexandre ELISÉ <contact@alexandre-elise.fr>
 * @link          https://alexandre-elise.fr
 * @copyright (c) 2020 . Alexandre ELISÉ . Tous droits réservés.
 * @license       GPL-2.0-and-later GNU General Public License v2.0 or later
 * Created Date : 21/08/2020
 * Created Time : 21:02
 */

namespace AE\Library\CustomField\Core;

use function defined;
use function dirname;
use const DIRECTORY_SEPARATOR;

defined('_JEXEC') or die;

/**
 * All constants used in this plugin in one place
 *
 * @package     AE\Library\CustomField\Core
 *
 * @since       version
 */
abstract class Constant
{
	/**
	 * The HTTP ressource has been successfuly retrieved.
	 *
	 * @var int
	 */
	public const HTTP_OK = 200;
	
	/**
	 * HTTP found.
	 *
	 * @var int
	 */
	public const HTTP_FOUND = 302;
	
	/**
	 * Where the cached api file is stored
	 * @return string
	 *
	 * @since version
	 */
	public static function getDataDirectory(): string
	{
		return dirname(dirname(dirname(dirname(dirname(__DIR__)))))
			. DIRECTORY_SEPARATOR
			. 'media'
			. DIRECTORY_SEPARATOR
			. 'data'
			. DIRECTORY_SEPARATOR;
	}
}
