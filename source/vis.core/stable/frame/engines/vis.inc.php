<?php

/**
 * This file is part of the VIS package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package VIS
 * @link https://oswframe.com
 * @license MIT License
 */

\osWFrame\Core\Settings::setStringVar('frame_current_module', \osWFrame\Core\Settings::getStringVar('frame_default_module'));

$osW_Template=new \osWFrame\Core\Template();

$osW_jQuery3=new \osWFrame\Core\jQuery3($osW_Template);
$osW_jQuery3->load();

$osW_Bootstrap5=new \osWFrame\Core\Bootstrap5($osW_Template);
if ((\osWFrame\Core\Settings::getStringVar('vis_theme')!==null)&&(\osWFrame\Core\Settings::getStringVar('vis_theme')!='')) {
	$osW_Bootstrap5->setTheme(\osWFrame\Core\Settings::getStringVar('vis_theme'));
}
if ((\osWFrame\Core\Settings::getStringVar('vis_theme_color')!==null)&&(\osWFrame\Core\Settings::getStringVar('vis_theme_color')!='')) {
	$osW_Bootstrap5->setCustom('blue', \osWFrame\Core\Settings::getStringVar('vis_theme_color'));
}
if ((\osWFrame\Core\Settings::getStringVar('vis_theme_font')!==null)&&(\osWFrame\Core\Settings::getStringVar('vis_theme_font')!='')) {
	$osW_Bootstrap5->setCustom('font-family-sans-serif', \osWFrame\Core\Settings::getStringVar('vis_theme_font'));
}
$osW_Bootstrap5->load();

$osW_FontAwesome5=new \osWFrame\Core\FontAwesome5($osW_Template);
$osW_FontAwesome5->load();

$osW_jQuery3->loadPlugin('easing');

$osW_Bootstrap5->loadPlugin('jbsadmin');
$osW_Bootstrap5->loadPlugin('select');
$osW_Bootstrap5->loadPlugin('datatables');
$osW_Bootstrap5->loadPlugin('datatables_responsive');
$osW_Bootstrap5->loadPlugin('datepicker');
$osW_Bootstrap5->loadPlugin('notify');

\osWFrame\Core\Network::sendHeader('Content-Type: text/html; charset=utf-8');
$osW_Template->addVoidTag('base', ['href'=>\osWFrame\Core\Settings::getStringVar('project_domain_full')]);
$osW_Template->addVoidTag('meta', ['charset'=>'utf-8']);
$osW_Template->addVoidTag('meta', ['http-equiv'=>'X-UA-Compatible', 'content'=>'IE=edge']);
$osW_Template->addVoidTag('meta', ['name'=>'viewport', 'content'=>'width=device-width, initial-scale=1, shrink-to-fit=no']);

if ((\osWFrame\Core\Settings::getStringVar('vis_database_alias')!==null)&&(\osWFrame\Core\Settings::getStringVar('vis_database_alias')!='')) {
	\osWFrame\Core\DB::addConnectionMYSQL(\osWFrame\Core\Settings::getStringVar('vis_database_server'), \osWFrame\Core\Settings::getStringVar('vis_database_username'), \osWFrame\Core\Settings::getStringVar('vis_database_password'), \osWFrame\Core\Settings::getStringVar('vis_database_db'), \osWFrame\Core\Settings::getStringVar('vis_database_character'), 'vis', \osWFrame\Core\Settings::getIntVar('vis_database_port'));
	\osWFrame\Core\DB::connect('vis');
} else {
	\osWFrame\Core\DB::addConnectionMYSQL(\osWFrame\Core\Settings::getStringVar('database_server'), \osWFrame\Core\Settings::getStringVar('database_username'), \osWFrame\Core\Settings::getStringVar('database_password'), \osWFrame\Core\Settings::getStringVar('database_db'), \osWFrame\Core\Settings::getStringVar('database_character'), 'default', \osWFrame\Core\Settings::getIntVar('database_port'));
	\osWFrame\Core\DB::connect('default');
	\osWFrame\Core\Settings::setStringVar('vis_database_alias', 'default');
}

$osW_FavIcon=new \osWFrame\Core\FavIcon('modules'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('frame_current_module').DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('vis_logo_favicon_name'), $osW_Template);
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