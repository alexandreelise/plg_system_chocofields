<?php
declare(strict_types=1);
/**
 * Core Service layer (kinda like a controller)
 *
 *
 * @package       Core
 * @author        Alexandre ELISÉ <contact@alexandre-elise.fr>
 * @link          https://alexandre-elise.fr
 * @copyright (c) 2020 . Alexandre ELISÉ . Tous droits réservés.
 * @license       GPL-2.0-and-later GNU General Public License v2.0 or later
 * Created Date : 21/08/2020
 * Created Time : 20:46
 */

namespace AE\Library\CustomField\Service;

use AE\Library\CustomField\Helper\Content;
use AE\Library\CustomField\Helper\DynamicMapping;
use AE\Library\CustomField\Util\Util;
use Joomla\CMS\Log\Log;
use Joomla\Registry\Registry;
use function defined;

defined('_JEXEC') or die;

/**
 * Core Service layer (kinda like a controller)
 *
 * @package     AE\Library\CustomField\Service
 *
 * @since       version
 */
abstract class Core
{
	/**
	 * Make the update, process articles.
	 *
	 * @param                              $article
	 * @param   \Joomla\Registry\Registry  $plugin_params
	 *
	 * @return void
	 * @author    Marc Dechèvre <marc@woluweb.be>
	 * @author    Pascal Leconte <pascal.leconte@conseilgouz.com>
	 * @author    Christophe Avonture <christophe@avonture.be>
	 * @author    Alexandre ELISÉ <contact@alexandre-elise.fr>
	 */
	public static function goUpdate($article, Registry $plugin_params): void
	{
		$isLogActive = Util::isLogActive();
		$result      = Content::updateArticleCustomFields($article, $plugin_params);
		if (!$isLogActive)
		{
			return;
		}
		$result ? Log::add('The article with id: ' . $article->id . ' has been successfully updated', Log::INFO, 'plg_system_chocofields')
			: Log::add('The article with id: ' . $article->id . ' has not been updated', Log::ERROR, 'plg_system_chocofields');
		
	}
	
	/**
	 * The present plugin will trigger automatically at the frequency configured
	 * in the Plugin Options.
	 *
	 * To do so it creates a file with the timestamp of the last execution
	 * Note: the manual way to trigger the Plugin is simply to (Open and) Save it
	 *
	 * @param             $article
	 * @param   Registry  $plugin_params
	 *
	 * @return void
	 */
	public static function manualPluginSaving($article, Registry $plugin_params): void
	{
		static::goUpdate($article, $plugin_params);
		
		Log::add('Plugin has been edited and saved', Log::INFO, 'plg_system_chocofields');
	}
}
