<?php
declare(strict_types=1);

/**
 * Concrete class responsible of running business logic (think of a model)
 *
 * @package       CronJob
 * @author        Alexandre ELISÉ <contact@alexandre-elise.fr>
 * @link          https://alexandre-elise.fr
 * @copyright (c) 2020 . Alexandre ELISÉ . Tous droits réservés.
 * @license       GPL-2.0-and-later GNU General Public License v2.0 or later
 * Created Date : 21/08/2020
 * Created Time : 18:24
 */


namespace AE\Library\CustomField\Update;

use AE\Library\CustomField\Service\Core;
use AE\Library\CustomField\Util\Util;

\defined('_JEXEC') or die;

/**
 * Concrete class responsible of running business logic (think of a model)
 *
 * @package AE\Library\CustomField\Update
 */
final class CronJob implements CronJobInterface
{
	public function run(): void
	{
	   $plugin_params = Util::getMainPluginParams();
	   
	   Core::goUpdate($plugin_params);
	}
}
