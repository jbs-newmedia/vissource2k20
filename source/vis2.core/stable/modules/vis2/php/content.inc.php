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

/*
 * Hook Configure.
 */
$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('frame_current_module').DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'content_header_config.inc.php';
if (file_exists($file)) {
	require_once $file;
}

$VIS2_Main=new \VIS2\Core\Main();
$VIS2_Main->setEnvironment($osW_Template);
$osW_Template->setVar('VIS2_Main', $VIS2_Main);

// osW_Language::getInstance()->addLanguageVar(vOut('frame_current_module'), vOut('vis2_title'), 'navigation');

$VIS2_User=new \VIS2\Core\User();
$osW_Template->setVar('VIS2_User', $VIS2_User);

if ($VIS2_User->isLoginSessionToken()===true) {
	$VIS2_User->doLoginByToken();
}

/*
 * Hook Header.
 */
$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('frame_current_module').DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'content_header_pre.inc.php';
if (file_exists($file)) {
	require_once $file;
}

if ($VIS2_User->isLoggedIn()!==true) {
	$request_uri=$_SERVER['REQUEST_URI'];
	if (defined('SID')) {
		$request_uri=preg_replace('/oswsid=([a-z0-9]+)/', SID, $request_uri);
	} else {
		$request_uri=preg_replace('/oswsid=([a-z0-9]+)(&?)/', '', $request_uri);
	}

	if ((!stristr($request_uri, \osWFrame\Core\Settings::getStringVar('vis2_path').'/'.\osWFrame\Core\Settings::getStringVar('vis2_login_module').'/'))&&(strlen($request_uri)>strlen(\osWFrame\Core\Settings::getStringVar('vis2_path').'/'.\osWFrame\Core\Settings::getStringVar('vis2_login_module').'/'))) {
		$vis2_login_link=\osWFrame\Core\Settings::catchStringSessionValue(\osWFrame\Core\Settings::getStringVar('vis2_path').'_login_link', '');
		if (strlen($vis2_login_link)==0) {
			\osWFrame\Core\Session::setStringVar(\osWFrame\Core\Settings::getStringVar('vis2_path').'_login_link', $request_uri);
		}
	}

	$VIS2_Main->setTool(\osWFrame\Core\Settings::getStringVar('vis2_login_module'));
} else {
	if ($VIS2_Main->setTool(\osWFrame\Core\Settings::catchStringGetValue('vistool', \osWFrame\Core\Settings::catchStringSessionValue(\osWFrame\Core\Settings::getStringVar('vis2_path').'_tool')))!==true) {
		$VIS2_User->doLogout();
		$VIS2_Main->setTool(\osWFrame\Core\Settings::getStringVar('vis2_login_module'));
		\osWFrame\Core\Network::directHeader(\osWFrame\Core\Navigation::buildUrl('current', 'vistool='.$VIS2_Main->getTool()));
	} elseif ($VIS2_Main->getTool()==\osWFrame\Core\Settings::getStringVar('vis2_login_module')) {
		$VIS2_User->doLogout();
		$VIS2_Main->setTool(\osWFrame\Core\Settings::getStringVar('vis2_login_module'));
		\osWFrame\Core\SessionMessageStack::addMessage('session', 'success', ['msg'=>'Sie wurden erfolgreich abgemeldet.']);
		\osWFrame\Core\Network::directHeader(\osWFrame\Core\Navigation::buildUrl('current', 'vistool='.$VIS2_Main->getTool()));
	} elseif ($VIS2_User->checkToolAccess($VIS2_Main->getTool())!==true) {
		$VIS2_User->doLogout();
		$VIS2_Main->setTool(\osWFrame\Core\Settings::getStringVar('vis2_login_module'));
		\osWFrame\Core\Network::directHeader(\osWFrame\Core\Navigation::buildUrl('current', 'vistool='.$VIS2_Main->getTool()));
	}
}

$VIS2_User->setToolId($VIS2_Main->getToolId());

$VIS2_Navigation=new \VIS2\Core\Navigation($VIS2_Main->getToolId());
$osW_Template->setVar('VIS2_Navigation', $VIS2_Navigation);

/**
 * BreadCrumb erstellen
 */
$VIS2_BreadCrumb=new \osWFrame\Core\BreadCrumb();

$VIS2_BreadCrumb->add('VIS', \osWFrame\Core\Settings::getStringVar('frame_current_module'));
$VIS2_BreadCrumb->add($VIS2_Main->getToolName(), \osWFrame\Core\Settings::getStringVar('frame_current_module'), 'vistool='.$VIS2_Main->getTool());

/**
 * Inhalt VIS-Tool verarbeiten
 */
$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('frame_current_module').DIRECTORY_SEPARATOR.'vistools'.DIRECTORY_SEPARATOR.$VIS2_Main->getTool().DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'content.inc.php';
if (file_exists($file)) {
	require_once $file;
}

$osW_Template->setVarFromFile('vis2content', 'content', \osWFrame\Core\Settings::getStringVar('frame_current_module').DIRECTORY_SEPARATOR.'vistools'.DIRECTORY_SEPARATOR.$VIS2_Main->getTool(), 'modules');

$osW_Template->setVarFromFile('content', 'content', \osWFrame\Core\Settings::getStringVar('frame_current_module'), 'modules');

/*
 * Hook Footer.
 */
$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('frame_current_module').DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'content_footer_pre.inc.php';
if (file_exists($file)) {
	require_once $file;
}

/*
 * Titel für Browser aus BreadCrumb bauen
 */
$titles=[];
foreach ($VIS2_BreadCrumb->getReverse() as $item) {
	$titles[]=$item['name'];
}
$osW_Template->addStringTag('title', implode(\osWFrame\Core\Settings::getStringVar('vis2_navigation_char'), $titles));

/*
 * Session-Nachrichten aufbauen
 */
$_messages=[];
if (\osWFrame\Core\SessionMessageStack::getMessages('session')!=[]) {
	foreach (\osWFrame\Core\SessionMessageStack::getMessages('session') as $type=>$messages) {
		foreach ($messages as $message) {
			$_messages[]='window.parent.vis2_notify("'.$message['msg'].'", "'.$type.'");';
		}
	}
	\osWFrame\Core\SessionMessageStack::clearMessages('session');
}

/*
 * Session-Nachrichten ausgeben
 */
$VIS2_Main->addJSCodeHead('$(function() {'.implode("\n", $_messages).'});');

$osW_Template->addVoidTag('base', ['href'=>\osWFrame\Core\Settings::getBaseUrl()]);

?>