<?php

/**
 * This file is part of the VIS2 package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package VIS2
 * @link https://oswframe.com
 * @license MIT License
 */

$VIS2_Mandant=new \VIS2\Core\Mandant($VIS2_Main->getToolId());
$osW_Template->setVar('VIS2_Mandant', $VIS2_Mandant);
if ($VIS2_Main->getBoolVar('tool_use_mandant')===true) {
	if ($VIS2_Mandant->getId()==0) {
		if (count($VIS2_User->getMandantenSelectArray())==1) {
			$vis2_mandant_id=array_key_first($VIS2_User->getMandantenSelectArray());
		} else {
			$vis2_mandant_id=intval(\osWFrame\Core\Settings::catchValue('vis2_mandant_id', 0, 'gp'));
		}
	} else {
		if ($VIS2_User->checkMandantAccess($VIS2_Mandant->getId())===true) {
			$vis2_mandant_id=intval(\osWFrame\Core\Settings::catchValue('vis2_mandant_id', 0, 'gp'));
		} else {
			$VIS2_Mandant->setId(0);
		}
	}
	if ($vis2_mandant_id>0) {
		$VIS2_Mandant->setId($vis2_mandant_id);
	}
}

$VIS2_Permission=new \VIS2\Core\Permission($VIS2_Main->getToolId(), $VIS2_User->getId());
$VIS2_Navigation->setPermission($VIS2_Permission);

/*
 * Hook Header.
 */
$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('frame_current_module').DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'content_header.inc.php';
if (file_exists($file)) {
	require_once $file;
}

/*
 * Hook Header für Tools.
 */
$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('frame_current_module').DIRECTORY_SEPARATOR.'vistools'.DIRECTORY_SEPARATOR.$VIS2_Main->getTool().DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'content_header.inc.php';
if (file_exists($file)) {
	require_once $file;
}

if ($VIS2_Navigation->getPage()=='') {
	$VIS2_Navigation->setPage(\osWFrame\Core\Settings::catchStringGetValue('vispage'));
}

if ($VIS2_Navigation->getFile()=='') {
	$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('frame_current_module').DIRECTORY_SEPARATOR.'vistools'.DIRECTORY_SEPARATOR.$VIS2_Main->getTool().DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.$VIS2_Navigation->getPage().'.inc.php';
} else {
	$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('frame_current_module').DIRECTORY_SEPARATOR.'vistools'.DIRECTORY_SEPARATOR.$VIS2_Main->getTool().DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.$VIS2_Navigation->getFile().'.inc.php';
}
if ((file_exists($file))&&($VIS2_Permission->checkPermission($VIS2_Navigation->getPage(), 'view')===true)) {
	include $file;
	$VIS2_BreadCrumb->add($VIS2_Navigation->getNavigationTitle(), \osWFrame\Core\Settings::getStringVar('frame_current_module'), 'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage());
	if ($VIS2_Navigation->getFile()=='') {
		$osW_Template->setVarFromFile('vis2content', $VIS2_Navigation->getPage(), \osWFrame\Core\Settings::getStringVar('frame_current_module').DIRECTORY_SEPARATOR.'vistools'.DIRECTORY_SEPARATOR.$VIS2_Main->getTool());
	} else {
		$osW_Template->setVarFromFile('vis2content', $VIS2_Navigation->getFile(), \osWFrame\Core\Settings::getStringVar('frame_current_module').DIRECTORY_SEPARATOR.'vistools'.DIRECTORY_SEPARATOR.$VIS2_Main->getTool());
	}
} else {
	$VIS2_Navigation->setPage($VIS2_Navigation->getDefaultPage());
	$VIS2_BreadCrumb->add($VIS2_Navigation->getNavigationTitle(), \osWFrame\Core\Settings::getStringVar('frame_current_module'), 'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage());
	\osWFrame\Core\Network::directHeader(\osWFrame\Core\Navigation::buildUrl('current', 'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage()));
}

$VIS2_Main->addTemplateJSCode('head', 'var session_timeout=\''.\osWFrame\Core\Session::getSessionLifetime().'\';');
$VIS2_Main->addTemplateJSCode('head', 'var session_logout=\''.\osWFrame\Core\Navigation::buildUrl('current', 'vistool='.\osWFrame\Core\Settings::getStringVar('vis2_logout_module')).'\';');

?>