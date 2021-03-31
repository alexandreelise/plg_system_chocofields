<?php
declare(strict_types=1);
/**
 * Woluweb - Update Custom Field project (a.k.a Chocofields)
 * A plugin allowing to populate Joomla Custom Fields from Webservices
 * php version 7.2
 *
 * @package   Chocofields
 * @author    Pascal Leconte <pascal.leconte@conseilgouz.com>
 * @author    Christophe Avonture <christophe@avonture.be>
 * @author    Marc Dechèvre <marc@woluweb.be>
 * @author    Alexandre ELISÉ <contact@alexandre-elise.fr>
 * @license   GNU GPL-2.0-or-later
 *
 * @link      https://github.com/woluweb/updatecf
 * @wiki https://github.com/woluweb/updatecf/-/wikis/home
 *
 * @link      https://github.com/alexandreelise/plg_system_chocofields
 */

// phpcs:disable PSR1.Files.SideEffects

defined('_JEXEC') or die;

use AE\Library\CustomField\Service\Core;
use Joomla\CMS\Log\Log;
use Joomla\CMS\Plugin\CMSPlugin;


/**
 * The Update Custom Fields system plugin.
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class PlgSystemChocofields extends CMSPlugin
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
	 * @var bool $autoloadLanguage
	 */
	protected $autoloadLanguage = true;
	
	/**
	 * @var object $lastSavedArticle
	 */
	private static $lastSavedArticle;
	
	/**
	 * PlgSystemChocofields constructor.
	 *
	 * @param          $subject
	 * @param   array  $config
	 */
	public function __construct(&$subject, $config = [])
	{
		parent::__construct($subject, $config);
		
		
		// If you want to enable this plugin logger
		if (1 === ((int) $this->params->get('log')))
		{
			Log::addLogger(
				[
					'text_file' => 'chocofields.trace.log.php',
				],
				Log::INFO | Log::ERROR,
				['plg_system_chocofields']
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
	
	
	public function onContentPrepareForm($form, $data)
	{
		if (!($form instanceof Joomla\CMS\Form\Form))
		{
			return false;
		}
		
		if ($form->getName() === 'com_content.article')
		{
			$form->loadFile(__DIR__ . '/forms/external_api.xml', false);
			
			return true;
		}
		
		return true;
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
			self::$lastSavedArticle = $item;
			
			//execute manual synchronisation when saving an article
			Core::manualPluginSaving($item, $this->params);
			
			return true;
		}
		
		return true;
	}
	
	/**
	 * Execute manual synchronisation when accessing this url
	 *
	 * @return bool
	 */
	public function onAjaxCronUpdate()
	{
		Core::manualPluginSaving(self::$lastSavedArticle, $this->params);
		
		return true;
	}
	
}
