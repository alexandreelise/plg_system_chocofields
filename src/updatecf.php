<?php
declare(strict_types=1);
/**
 * Woluweb - Update Custom Field project
 * A plugin allowing to populate Joomla Custom Fields from Webservices
 * php version 7.2
 *
 * @package   Updatecf
 * @author    Pascal Leconte <pascal.leconte@conseilgouz.com>
 * @author    Christophe Avonture <christophe@avonture.be>
 * @author    Marc Dechèvre <marc@woluweb.be>
 * @author    Alexandre ELISÉ <contact@alexandre-elise.fr>
 * @license   GNU GPL-2.0-or-later
 *
 * @link      https://github.com/woluweb/updatecf
 * @wiki https://github.com/woluweb/updatecf/-/wikis/home
 */

// phpcs:disable PSR1.Files.SideEffects

defined('_JEXEC') or die('Restricted access');

use AE\Library\CustomField\Service\Core;
use AE\Library\CustomField\Util\Util;
use Joomla\CMS\Factory;
use Joomla\CMS\Log\Log;
use Joomla\CMS\Plugin\CMSPlugin;


/**
 * The Update Custom Fields system plugin.
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class PlgSystemUpdatecf extends CMSPlugin
{
	/**
	 * CMSApplication
	 *
	 * @var \Joomla\CMS\Application\CMSApplication $app
	 * @since version
	 */
	protected $app;
	
	/**
	 * DatabaseDriver
	 *
	 * @var \Joomla\Database\DatabaseDriver $db
	 * @since version
	 */
	protected $db;
	
	
	/**
	 * PlgSystemUpdatecf constructor.
	 *
	 * @param          $subject
	 * @param   array  $config
	 */
	public function __construct(&$subject, $config = [])
	{
		parent::__construct($subject, $config);
		
		$this->autoloadLanguage = true;
		
		
		// If you want to enable this plugin logger
		if (1 === (int) $this->params->get('log'))
		{
			Log::addLogger(
				[
					'text_file' => 'updatecf.trace.log.php',
				],
				Log::INFO|Log::ERROR,
				['plg_system_updatecf']
			);
		}
		
		JLoader::registerNamespace(
			'AE\\Library\\CustomField\\',
			JPATH_PLUGINS
			. DIRECTORY_SEPARATOR
			. 'system'
			. DIRECTORY_SEPARATOR
			. 'updatecf'
			. DIRECTORY_SEPARATOR
			. 'libraries'
			. DIRECTORY_SEPARATOR
			. 'AE'
			. DIRECTORY_SEPARATOR
			. 'Library'
			. DIRECTORY_SEPARATOR
			. 'CustomField'
			, false
			, false
			, 'psr4'
		);
	}
	
	/**
	 * Joomla! onAfterRoute
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function onAfterRoute(): void
	{
		$input = $this->app->input;
		$data  = $input->post->getArray();
		
		if ($this->app->isClient('administrator')
			&& ($input->get('option') === 'com_plugins')
			&& ($data['jform']['enabled'] ?? false)
			&& ((int) $data['jform']['enabled'] === 1)
			&& ($data['jform']['folder'] ?? false)
			&& ($data['jform']['folder'] === 'system')
			&& ($data['jform']['element'] ?? false)
			&& ($data['jform']['element'] === 'updatecf')
			&& in_array($input->get('task'), ['plugin.apply', 'plugin.save'], true)
		)
		{
			Core::manualPluginSaving(Util::getMainPluginParams());
		}
		
		
		// force overwrite of cli script by appending "&cpy=1"
		// in admin area when being super user
		if (Factory::getUser()->authorise('core.admin')
			&& ($input->getUint('cpy') === 1)
		)
		{
			Util::copyCliScript();
		}
	}
	
	
	/**
	 * Do something before saving any kind of content in the database
	 *
	 * @param   string                    $context
	 * @param   Joomla\CMS\Table\Content  $item
	 * @param   bool                      $isNew
	 * @param   array                     $data
	 *
	 * @return bool
	 *
	 * @since version
	 */
	public function onContentBeforeSave(string $context, Joomla\CMS\Table\Content $item, bool $isNew, $data = []): bool
	{
		// if not an article do nothing
		if (!in_array($context, ['com_content.article', 'com_content.form'], true))
		{
			return true; // pass to next content plugin
		}
		
		//execute manual synchronisation when saving an article
		Core::manualPluginSaving(Util::getMainPluginParams());
		
		return true; // pass to next plugin
	}
	
}
