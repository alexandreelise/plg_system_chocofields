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

defined('_JEXEC') or die;

use AE\Library\CustomField\ErrorHandling\NoContentException;
use AE\Library\CustomField\ErrorHandling\NotFoundException;
use AE\Library\CustomField\Helper\DynamicMapping;
use AE\Library\CustomField\Service\Core;
use Joomla\CMS\Form\Form;
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
	 * @var \JDatabaseDriver $db
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
		if (1 === ((int) $this->params->get('log')))
		{
			Log::addLogger(
				[
					'text_file' => 'updatecf.trace.log.php',
				],
				Log::INFO | Log::ERROR,
				['plg_system_updatecf']
			);
		}
		
		JLoader::registerNamespace(
			'AE\\Library\\CustomField\\',
			__DIR__
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
	 * Do something before saving any kind of content in the database
	 *
	 * @param   string  $context
	 * @param           $item
	 * @param   bool    $isNew
	 *
	 * @return bool
	 *
	 * @since version
	 */
	public function onContentBeforeSave($context, $item, $isNew, $data = [])
	{
		// if not an article do nothing
		if (in_array($context, ['com_content.article', 'com_content.form'], true))
		{
			//execute manual synchronisation when saving an article
			Core::manualPluginSaving($this->params);
			
			return true; // pass to next content plugin
		}
		
		return true; // pass to next plugin
	}
}
