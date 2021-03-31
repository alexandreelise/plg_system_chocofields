<?php
declare(strict_types=1);
/**
 * Bootstrap
 *
 * @version       0.1.0
 * @package       bootstrap
 * @author        Alexandre ELISÉ <contact@alexandre-elise.fr>
 * @copyright (c) 2009-2021 . Alexandre ELISÉ . Tous droits réservés.
 * @license       GPL-2.0-and-later GNU General Public License v2.0 or later
 * @link          https://coderparlerpartager.fr
 */

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use Joomla\Event\Dispatcher;

/**
 * Mock for the global application exit.
 *
 * @param   mixed  $message  Exit code or string. Defaults to zero.
 *
 * @return  void
 */
function jexit($message = 0)
{
}

$_SERVER['HTTP_HOST']   = 'example.com';
$_SERVER['REQUEST_URI'] = '';
$joomla                 = 'joomla3x';

// Fix magic quotes.
ini_set('magic_quotes_runtime', '0');

// Maximise error reporting.
ini_set('zend.ze1_compatibility_mode', '0');
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Set fixed precision value to avoid round related issues
ini_set('precision', '14');

/**
 * Constant that is checked in included files to prevent direct access.
 * define() is used in the installation folder rather than "const" to not error for PHP 5.2 and lower
 */
define('_JEXEC', 1);

try
{
	if (file_exists(__DIR__ . '/' . $joomla . '/defines.php'))
	{
		include_once __DIR__ . '/' . $joomla . '/defines.php';
	}
	
	if (!defined('_JDEFINES'))
	{
		define('JPATH_BASE', __DIR__ . '/' . $joomla);
		require_once JPATH_BASE . '/includes/defines.php';
	}
	
	require_once JPATH_BASE . '/includes/framework.php';
	
	
	require_once __DIR__ . '/vendor/autoload.php';
	
	Factory::$application = CMSApplication::getInstance('site');
	
	JLoader::setup();
	
	JLoader::register('FieldsHelper',
		JPATH_ADMINISTRATOR
		. DIRECTORY_SEPARATOR
		. 'components'
		. DIRECTORY_SEPARATOR
		. 'com_fields'
		. DIRECTORY_SEPARATOR
		. 'helpers'
		. DIRECTORY_SEPARATOR
		. 'fields.php'
	);
	
	require_once __DIR__ . '/src/chocofields.php';
}
catch (Throwable $e)
{
	echo $e->getTraceAsString() . PHP_EOL;
}
