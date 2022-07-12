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

/*
 * Hook Configure.
 */
$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('frame_current_module').DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'content_header_config.inc.php';
if (file_exists($file)) {
	require_once $file;
}

$VIS_Main=new \VIS\Core\Main();
$VIS_Main->setEnvironment($osW_Template);
$osW_Template->setVar('VIS_Main', $VIS_Main);

// osW_Language::getInstance()->addLanguageVar(vOut('frame_current_module'), vOut('vis_title'), 'navigation');

$VIS_User=new \VIS\Core\User();
$osW_Template->setVar('VIS_User', $VIS_User);

if ($VIS_User->isLoginSessionToken()===true) {
	$VIS_User->doLoginByToken();
}

/*
 * Hook Header.
 */
$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('frame_current_module').DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'content_header_pre.inc.php';
if (file_exists($file)) {
	require_once $file;
}

if ($VIS_User->isLoggedIn()!==true) {
	$request_uri=$_SERVER['REQUEST_URI'];
	if (defined('SID')) {
		$request_uri=preg_replace('/oswsid=([a-z0-9]+)/', SID, $request_uri);
	} else {
		$request_uri=preg_replace('/oswsid=([a-z0-9]+)(&?)/', '', $request_uri);
	}

	if ((!stristr($request_uri, \osWFrame\Core\Settings::getStringVar('vis_path').'/'.\osWFrame\Core\Settings::getStringVar('vis_login_module').'/'))&&(strlen($request_uri)>strlen(\osWFrame\Core\Settings::getStringVar('vis_path').'/'.\osWFrame\Core\Settings::getStringVar('vis_login_module').'/'))) {
		$vis_login_link=\osWFrame\Core\Settings::catchStringSessionValue(\osWFrame\Core\Settings::getStringVar('vis_path').'_login_link', '');
		if (strlen($vis_login_link)==0) {
			\osWFrame\Core\Session::setStringVar(\osWFrame\Core\Settings::getStringVar('vis_path').'_login_link', $request_uri);
		}
	}

	$VIS_Main->setTool(\osWFrame\Core\Settings::getStringVar('vis_login_module'));
} else {
	if ($VIS_Main->setTool(\osWFrame\Core\Settings::catchStringGetValue('vistool', \osWFrame\Core\Settings::catchStringSessionValue(\osWFrame\Core\Settings::getStringVar('vis_path').'_tool')))!==true) {
		$VIS_User->doLogout();
		$VIS_Main->setTool(\osWFrame\Core\Settings::getStringVar('vis_login_module'));
		\osWFrame\Core\Network::directHeader(\osWFrame\Core\Navigation::buildUrl('current', 'vistool='.$VIS_Main->getTool()));
	} elseif ($VIS_Main->getTool()==\osWFrame\Core\Settings::getStringVar('vis_login_module')) {
		$VIS_User->doLogout();
		$VIS_Main->setTool(\osWFrame\Core\Settings::getStringVar('vis_login_module'));
		\osWFrame\Core\SessionMessageStack::addMessage('session', 'success', ['msg'=>'Sie wurden erfolgreich abgemeldet.']);
		\osWFrame\Core\Network::directHeader(\osWFrame\Core\Navigation::buildUrl('current', 'vistool='.$VIS_Main->getTool()));
	} elseif ($VIS_User->checkToolAccess($VIS_Main->getTool())!==true) {
		$VIS_User->doLogout();
		$VIS_Main->setTool(\osWFrame\Core\Settings::getStringVar('vis_login_module'));
		\osWFrame\Core\Network::directHeader(\osWFrame\Core\Navigation::buildUrl('current', 'vistool='.$VIS_Main->getTool()));
	}
}

$VIS_User->setToolId($VIS_Main->getToolId());

$VIS_Navigation=new \VIS\Core\Navigation($VIS_Main->getToolId());
$osW_Template->setVar('VIS_Navigation', $VIS_Navigation);

/**
 * BreadCrumb erstellen
 */
$VIS_BreadCrumb=new \osWFrame\Core\BreadCrumb();

$VIS_BreadCrumb->add('VIS', \osWFrame\Core\Settings::getStringVar('frame_current_module'));
$VIS_BreadCrumb->add($VIS_Main->getToolName(), \osWFrame\Core\Settings::getStringVar('frame_current_module'), 'vistool='.$VIS_Main->getTool());

/**
 * Inhalt VIS-Tool verarbeiten
 */
$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('frame_current_module').DIRECTORY_SEPARATOR.'vistools'.DIRECTORY_SEPARATOR.$VIS_Main->getTool().DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'content.inc.php';
if (file_exists($file)) {
	require_once $file;
}

$osW_Template->setVarFromFile('viscontent', 'content', \osWFrame\Core\Settings::getStringVar('frame_current_module').DIRECTORY_SEPARATOR.'vistools'.DIRECTORY_SEPARATOR.$VIS_Main->getTool(), 'modules');

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
foreach ($VIS_BreadCrumb->getReverse() as $item) {
	$titles[]=$item['name'];
}
$osW_Template->addStringTag('title', implode(\osWFrame\Core\Settings::getStringVar('vis_navigation_char'), $titles));

/*
 * Session-Nachrichten aufbauen
 */
$_messages=[];
if (\osWFrame\Core\SessionMessageStack::getMessages('session')!=[]) {
	foreach (\osWFrame\Core\SessionMessageStack::getMessages('session') as $type=>$messages) {
		foreach ($messages as $message) {
			$_messages[]='window.parent.vis_notify("'.$message['msg'].'", "'.$type.'");';
		}
	}
	\osWFrame\Core\SessionMessageStack::clearMessages('session');
}

/*
 * Session-Nachrichten ausgeben
 */
$VIS_Main->addJSCodeHead('$(function() {'.implode("\n", $_messages).'});');

$osW_Template->addVoidTag('base', ['href'=>\osWFrame\Core\Settings::getBaseUrl()]);

?>