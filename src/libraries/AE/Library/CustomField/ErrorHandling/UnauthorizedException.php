<?php
declare(strict_types=1);
/**
 * Http code 401 Unauthorized
 *
 * @package       UnauthorizedException
 * @author        Alexandre ELISÉ <contact@alexandre-elise.fr>
 * @link          https://alexandre-elise.fr
 * @copyright (c) 2020 . Alexandre ELISÉ . Tous droits réservés.
 * @license       GPL-2.0-and-later GNU General Public License v2.0 or later
 * Created Date : 28/09/2020
 * Created Time : 20:47
 */

namespace AE\Library\CustomField\ErrorHandling;


use Exception;
use Throwable;

defined('_JEXEC') or die;

/**
 * Http code 401 Unauthorized
 *
 * @package     AE\Library\CustomField\ErrorHandling
 *
 * @since       version
 */
final class UnauthorizedException extends Exception
{
	
	/**
	 *
	 * Construct the exception. Note: The message is NOT binary safe.
	 * @link https://php.net/manual/en/exception.construct.php
	 * @param string $message [optional] The Exception message to throw.
	 * @param int $code [optional] The Exception code.
	 * @param Throwable $previous [optional] The previous throwable used for the exception chaining.
	 */
	public function __construct($message = "", $code = 0, Throwable $previous = null)
	{
		parent::__construct('Unauthorized', 401, $previous);
	}
}
