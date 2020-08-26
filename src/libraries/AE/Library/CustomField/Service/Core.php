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

use AE\Library\CustomField\Core\Constant;
use AE\Library\CustomField\Helper\Content;
use AE\Library\CustomField\Util\Util;
use JLoader;
use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\Log\Log;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Version;
use Joomla\Registry\Registry;
use function substr;
use const DIRECTORY_SEPARATOR;

\defined('_JEXEC') or die;

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
	 * @param   \Joomla\Registry\Registry  $plugin_params
	 *
	 * @return void
	 * @author    Marc Dechèvre <marc@woluweb.be>
	 * @author    Pascal Leconte <pascal.leconte@conseilgouz.com>
	 * @author    Christophe Avonture <christophe@avonture.be>
	 * @author    Alexandre ELISÉ <contact@alexandre-elise.fr>
	 */
	public static function goUpdate(Registry $plugin_params): void
	{
		$categories = $plugin_params->get('categories');

		if (null === $categories) {
			$res        = Content::getAllCategories();
			$categories = [];
			foreach ($res as $catid) {
				if ((int)$catid->count > 0) {
					$categories[] = (int)$catid->id;
				}
			}
		}

		$joomlaVersion = new Version();
		$majorVersion  = (int) substr($joomlaVersion->getShortVersion(), 0, 1);

		if ($majorVersion >= Constant::JOOMLA_4) {
			$articles     = new Joomla\Components\Content\Site\Model\ArticlesModel(['ignore_request' => true]);
		} else {
			JLoader::register('ContentModelArticles',
				JPATH_SITE
				. DIRECTORY_SEPARATOR
				.'components'
				. DIRECTORY_SEPARATOR
				. 'com_content'
				. DIRECTORY_SEPARATOR
				. 'models'
				. DIRECTORY_SEPARATOR
				. 'articles.php'
			);
			$articles = BaseDatabaseModel::getInstance('Articles', 'ContentModel', ['ignore_request' => true]);
		}

		if ($articles) {
			$params = new Registry();

			$articles->setState('params', $params);
			$articles->setState('list.limit', 0);
			$articles->setState('list.start', 0);
			$articles->setState('filter.tag', 0);
			$articles->setState('list.ordering', 'a.ordering');
			$articles->setState('list.direction', 'ASC');
			$articles->setState('filter.published', 1);

			$articles->setState('filter.category_id', $categories);

			$articles->setState('filter.featured', 'show');
			$articles->setState('filter.author_id', '');
			$articles->setState('filter.author_id.include', 1);
			$articles->setState('filter.access', false);

			$app = CMSApplication::getInstance(Util::getClient());

			Factory::$application = $app;

			$items = $articles->getItems();

			// Process all articles
			foreach ($items as $item) {
				$result = Content::updateArticleCustomFields($item, $plugin_params);

				if (1 === (int) $plugin_params->get('log'))
				{
					if ($result === true) {
						Log::add('The article with id: ' . $item->id . ' has been successfully updated', Log::INFO, 'plg_system_updatecf');
					} else{
						Log::add('The article with id: ' . $item->id . ' has not been updated', Log::ERROR, 'plg_system_updatecf');
					}
				}

			}
		}
	}

	/**
	 * The present plugin will trigger automatically at the frequency configured
	 * in the Plugin Options.
	 *
	 * To do so it creates a file with the timestamp of the last execution
	 * Note: the manual way to trigger the Plugin is simply to (Open and) Save it
	 *
	 * @param Registry $plugin_params
	 *
	 * @return void
	 */
	public static function manualPluginSaving(Registry $plugin_params): void
	{
		static::goUpdate($plugin_params);

		Log::add('Plugin has been edited and saved', Log::INFO, 'plg_system_updatecf');
	}
}
