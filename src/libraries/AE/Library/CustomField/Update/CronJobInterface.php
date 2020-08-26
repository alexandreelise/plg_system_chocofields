<?php
declare(strict_types=1);
/**
 * Simple interface representing cronjobs
 *
 * @package       CronJobInterface
 * @author        Alexandre ELISÉ <contact@alexandre-elise.fr>
 * @link          https://alexandre-elise.fr
 * @copyright (c) 2020 . Alexandre ELISÉ . Tous droits réservés.
 * @license       GPL-2.0-and-later GNU General Public License v2.0 or later
 * Created Date : 21/08/2020
 * Created Time : 20:30
 */

namespace AE\Library\CustomField\Update;

\defined('_JEXEC') or die;
/**
 * Simple interface representing cronjobs
 *
 * @package AE\Library\CustomField\Update
 */
interface CronJobInterface
{
	public function run();
}
