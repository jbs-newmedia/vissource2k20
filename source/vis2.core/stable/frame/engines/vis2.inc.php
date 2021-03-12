<?php

/**
 * This file is part of the VIS2 package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package VIS2
 * @link https://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 */

$osW_Template=new \osWFrame\Core\Template();

$osW_jQuery3=new \osWFrame\Core\jQuery3($osW_Template);

$osW_Bootstrap4=new \osWFrame\Core\Bootstrap4($osW_Template);

$osW_FontAwesome5=new \osWFrame\Core\FontAwesome5($osW_Template);

$osW_Bootstrap4->loadPlugin('jbsadmin4', ['theme'=>\osWFrame\Core\Settings::getStringVar('vis2_theme')]);
$osW_Bootstrap4->loadPlugin('select');
$osW_Bootstrap4->loadPlugin('datatables');
$osW_Bootstrap4->loadPlugin('datatables_responsive');
$osW_Bootstrap4->loadPlugin('datepicker');
$osW_Bootstrap4->loadPlugin('notify');

\osWFrame\Core\Network::sendHeader('Content-Type: text/html; charset=utf-8');
$osW_Template->addVoidTag('meta', ['charset'=>'utf-8']);
$osW_Template->addVoidTag('meta', ['http-equiv'=>'X-UA-Compatible', 'content'=>'IE=edge']);
$osW_Template->addVoidTag('meta', ['name'=>'viewport', 'content'=>'width=device-width, initial-scale=1, shrink-to-fit=no']);

\osWFrame\Core\DB::addConnectionMYSQL(\osWFrame\Core\Settings::getStringVar('database_server'), \osWFrame\Core\Settings::getStringVar('database_username'), \osWFrame\Core\Settings::getStringVar('database_password'), \osWFrame\Core\Settings::getStringVar('database_db'));
\osWFrame\Core\DB::connect();

$osW_FavIcon=new \osWFrame\Core\FavIcon('modules'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('frame_current_module').DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('vis2_logo_favicon_name'), $osW_Template);
$osW_FavIcon->setIcons2Template();

\osWFrame\Core\Settings::setStringVar('frame_current_module', \osWFrame\Core\Settings::getStringVar('frame_default_module'));

$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('frame_current_module').DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'content.inc.php';
if (file_exists($file)) {
	include_once $file;

	\osWFrame\Core\Navigation::checkUrl();
} else {
	\osWFrame\Core\Settings::setStringVar('frame_current_module', \osWFrame\Core\Settings::getStringVar('errorlogger_module'));
	$_GET['error_status']=404;

	$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('frame_current_module').DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'content.inc.php';
	if (file_exists($file)) {
		include_once $file;
	}
}



?>