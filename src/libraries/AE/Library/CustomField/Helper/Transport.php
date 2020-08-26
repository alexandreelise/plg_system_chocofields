<?php
declare(strict_types=1);
/**
 * Transport related helper
 *
 * @package       Transport
 * @author        Alexandre ELISÉ <contact@alexandre-elise.fr>
 * @link          https://alexandre-elise.fr
 * @copyright (c) 2020 . Alexandre ELISÉ . Tous droits réservés.
 * @license       GPL-2.0-and-later GNU General Public License v2.0 or later
 * Created Date : 21/08/2020
 * Created Time : 20:18
 */

namespace AE\Library\CustomField\Helper;


use AE\Library\CustomField\Data\HttpResponse;
use function curl_close;
use function curl_exec;
use function curl_getinfo;
use function curl_init;
use function curl_setopt;
use function extension_loaded;
use const CURLOPT_AUTOREFERER;
use const CURLOPT_CONNECTTIMEOUT;
use const CURLOPT_REFERER;
use const CURLOPT_RETURNTRANSFER;
use const CURLOPT_TIMEOUT;
use const CURLOPT_URL;
use const CURLOPT_USERAGENT;

\defined('_JEXEC') or die;

/**
 * Transport related helper
 *
 * @package     AE\Library\CustomField\Helper
 *
 * @since       version
 */
abstract class Transport
{
	/**
	 * Retrieving information thanks to curl.
	 *
	 * @param   string  $url  URL to query
	 *
	 * @return HttpResponse
	 * @author    Marc Dechèvre <marc@woluweb.be>
	 * @author    Pascal Leconte <pascal.leconte@conseilgouz.com>
	 * @author    Christophe Avonture <christophe@avonture.be>
	 * @author    Alexandre ELISÉ <contact@alexandre-elise.fr>
	 */
	public static function getCurlContent(string $url): HttpResponse
	{
		if (!extension_loaded('curl'))
		{
			return (new HttpResponse('', 0));
		}
		//TODO: replace this with Joomla! core Http api
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_USERAGENT, 'Googlebot/2.1 (+http://www.google.com/bot.html)');
		curl_setopt($curl, CURLOPT_REFERER, 'http://www.google.com');
		curl_setopt($curl, CURLOPT_AUTOREFERER, true);
		curl_setopt($curl, CURLOPT_URL, $url);
		
		$result = curl_exec($curl);
		
		if (\is_bool($result))
		{
			$response = '';
		}
		else
		{
			$response = $result;
		}
		
		$infos = curl_getinfo($curl);
		
		curl_close($curl);
		
		return (new HttpResponse($response, (int) $infos['http_code']));
	}
}
