<?php

/**
 * @package       updatecf-cli
 * @author        Alexandre ELISÉ <contact@alexandre-elise.fr>
 * @link          https://alexandre-elise.fr
 * @copyright (c) 2020 . Alexandre ELISÉ . Tous droits réservés.
 * @license       GPL-2.0-and-later GNU General Public License v2.0 or later
 * Created Date : 21/08/2020
 * Created Time : 18:26
 */

// Make sure we're being called from the command line, not a web interface

use AE\Library\CustomField\Update\CronJob;
use AE\Library\CustomField\Util\Util;
use Joomla\CMS\Application\CliApplication;
use Joomla\CMS\Factory;

// Configure error reporting to maximum for CLI output.
error_reporting(E_ALL | E_NOTICE);
ini_set('display_errors', 1);

if (PHP_SAPI !== 'cli')
{
	die('This is a command line only application.');
}

// We are a valid entry point.
if (!defined('_JEXEC'))
{
	define('_JEXEC', 1);
}
// Load system defines
if (file_exists(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'defines.php'))
{
	require_once dirname(__DIR__)  . DIRECTORY_SEPARATOR .'defines.php';
}

if (!defined('_JDEFINES'))
{
	if (!defined('JPATH_BASE'))
	{
		define('JPATH_BASE', dirname(__DIR__));
	}
	require_once JPATH_BASE . DIRECTORY_SEPARATOR .'includes'. DIRECTORY_SEPARATOR .'defines.php';
}


// Get the framework.
require_once JPATH_LIBRARIES . DIRECTORY_SEPARATOR .'import.legacy.php';

// Bootstrap the CMS libraries.
require_once JPATH_LIBRARIES . DIRECTORY_SEPARATOR .'cms.php';

// Pre-Load configuration. Don't remove the Output Buffering due to BOM issues, see JCode 26026
ob_start();
require_once JPATH_CONFIGURATION . DIRECTORY_SEPARATOR .'configuration.php';
ob_end_clean();

// System configuration.
$config = new JConfig;

// Set the error_reporting
switch ($config->error_reporting)
{
	case 'default':
	case '-1':
		break;

	case 'none':
	case '0':
		error_reporting(0);

		break;

	case 'simple':
		error_reporting(E_ERROR | E_WARNING | E_PARSE);
		ini_set('display_errors', 1);

		break;

	case 'maximum':
		error_reporting(E_ALL);
		ini_set('display_errors', 1);

		break;

	case 'development':
		error_reporting(-1);
		ini_set('display_errors', 1);

		break;

	default:
		error_reporting($config->error_reporting);
		ini_set('display_errors', 1);

		break;
}

if (!defined('JDEBUG')) {
	define('JDEBUG', $config->debug);
}

unset($config);

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

if (!class_exists('UpdateCfCli'))
{

	/**
	 * @package     ${NAMESPACE}
	 *
	 * @since       version
	 */
	class UpdateCfCli extends CliApplication
	{
		/**
		 * Cronjob instance
		 * @var
		 * @since version
		 */
		private static $cronjob;

		/**
		 * UpdateCfCli constructor.
		 */
		public function __construct()
		{
			parent::__construct();

			if (!isset(static::$cronjob)) {
				static::$cronjob = new CronJob();
			}
		}


		/**
		 * @throws Throwable
		 */
		protected function doExecute()
		{
			$this->out('Update - Custom Fields - Cli');
			$this->out('================================');

			// Remove the script time limit.
			set_time_limit(0);

			try
			{
				static::$cronjob->run();
			}
			catch (Exception $exception)
			{
				$this->out($exception->getMessage());
			}
		}

		/**
		 *
		 * @return string|void
		 *
		 * @since version
		 */
		public function getName()
		{
			return 'UpdateCfCli';
		}
	}
}
// Instantiate the application object, passing the class name to JCli::getInstance
// and use chaining to execute the application.
Factory::$application = CliApplication::getInstance('UpdateCfCli');

Factory::getApplication('cli', [])->execute();

