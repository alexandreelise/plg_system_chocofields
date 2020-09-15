<?php

/**
 * @package       bootstrap-joomla3x
 * @author        Alexandre ELISÉ <contact@alexandre-elise.fr>
 * @link          https://alexandre-elise.fr
 * @copyright (c) 2020 . Alexandre ELISÉ . Tous droits réservés.
 * @license       GPL-2.0-and-later GNU General Public License v2.0 or later
 * Created Date : 22/08/2020
 * Created Time : 17:06
 */

define('_JEXEC', 1);

$_SERVER['HTTP_HOST']   = 'example.com';
$_SERVER['REQUEST_URI'] = '';

$joomla_directory = 'joomla3x'; // or joomla4x

if (file_exists(
	__DIR__
	. DIRECTORY_SEPARATOR
	. $joomla_directory
	. DIRECTORY_SEPARATOR
	. 'defines.php'
))
{
	include_once __DIR__ . DIRECTORY_SEPARATOR . $joomla_directory . DIRECTORY_SEPARATOR .'defines.php';
}

if (!defined('_JDEFINES'))
{
	define('JPATH_BASE', __DIR__ . DIRECTORY_SEPARATOR . $joomla_directory);
	require_once JPATH_BASE . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'defines.php';
}

require_once JPATH_BASE . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'framework.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

// plugin code
require_once __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'updatecf.php';

