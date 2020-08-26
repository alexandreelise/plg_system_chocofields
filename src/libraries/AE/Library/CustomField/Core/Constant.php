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

\defined('_JEXEC') or die;

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
	 * Used to detect if the Joomla major version is 4 or more.
	 *
	 * @var int
	 */
	public const JOOMLA_4 = 4;
	
}
