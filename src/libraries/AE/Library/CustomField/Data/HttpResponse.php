<?php
declare(strict_types=1);
/**
 * HttpResponse DTO (Data Transfert Object)
 * Read only convienent object
 *
 * @package       HttpResponse
 * @author        Alexandre ELISÉ <contact@alexandre-elise.fr>
 * @link          https://alexandre-elise.fr
 * @copyright (c) 2020 . Alexandre ELISÉ . Tous droits réservés.
 * @license       GPL-2.0-and-later GNU General Public License v2.0 or later
 * Created Date : 21/08/2020
 * Created Time : 22:32
 */

namespace AE\Library\CustomField\Data;

use function defined;

defined('_JEXEC') or die;

/**
 * HttpResponse DTO (Data Transfert Object)
 * Read only convienent object
 *
 * @package     AE\Library\CustomField\Data
 *
 * @since       version
 */
final class HttpResponse
{
	/**
	 * The HTTP code or 0 when curl isn't loaded
	 *
	 * @var int $code
	 * @since version
	 */
	private $code;
	
	/**
	 * @var string $body
	 * @since version
	 */
	private $body;
	
	/**
	 * HttpResponse constructor.
	 *
	 * @param   string  $body
	 * @param   int     $code
	 */
	public function __construct(string $body, int $code)
	{
		$this->body = $body;
		$this->code = $code;
	}
	
	/**
	 * @return int
	 */
	public function getCode(): int
	{
		return $this->code;
	}
	
	/**
	 * @return string
	 */
	public function getBody(): string
	{
		return $this->body;
	}
	
}
