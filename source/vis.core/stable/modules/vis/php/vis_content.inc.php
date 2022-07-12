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

$VIS_Mandant=new \VIS\Core\Mandant($VIS_Main->getToolId());
$osW_Template->setVar('VIS_Mandant', $VIS_Mandant);
if ($VIS_Main->getBoolVar('tool_use_mandant')===true) {
	if ($VIS_Mandant->getId()==0) {
		if (count($VIS_User->getMandantenSelectArray())==1) {
			$vis_mandant_id=array_key_first($VIS_User->getMandantenSelectArray());
		} else {
			$vis_mandant_id=intval(\osWFrame\Core\Settings::catchValue('vis_mandant_id', 0, 'gp'));
		}
	} else {
		if ($VIS_User->checkMandantAccess($VIS_Mandant->getId())===true) {
			$vis_mandant_id=intval(\osWFrame\Core\Settings::catchValue('vis_mandant_id', 0, 'gp'));
		} else {
			$VIS_Mandant->setId(0);
		}
	}
	if ($vis_mandant_id>0) {
		$VIS_Mandant->setId($vis_mandant_id);
	}
}

$VIS_Permission=new \VIS\Core\Permission($VIS_Main->getToolId(), $VIS_User->getId());
$VIS_Navigation->setPermission($VIS_Permission);

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
$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('frame_current_module').DIRECTORY_SEPARATOR.'vistools'.DIRECTORY_SEPARATOR.$VIS_Main->getTool().DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'content_header.inc.php';
if (file_exists($file)) {
	require_once $file;
}

$VIS_Navigation->setPage(\osWFrame\Core\Settings::catchStringGetValue('vispage'));

$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('frame_current_module').DIRECTORY_SEPARATOR.'vistools'.DIRECTORY_SEPARATOR.$VIS_Main->getTool().DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.$VIS_Navigation->getPage().'.inc.php';
if ((file_exists($file))&&($VIS_Permission->checkPermission($VIS_Navigation->getPage(), 'view')===true)) {
	include $file;
	$VIS_BreadCrumb->add($VIS_Navigation->getNavigationTitle(), \osWFrame\Core\Settings::getStringVar('frame_current_module'), 'vistool='.$VIS_Main->getTool().'&vispage='.$VIS_Navigation->getPage());
	$osW_Template->setVarFromFile('viscontent', $VIS_Navigation->getPage(), \osWFrame\Core\Settings::getStringVar('frame_current_module').DIRECTORY_SEPARATOR.'vistools'.DIRECTORY_SEPARATOR.$VIS_Main->getTool());
} else {
	$VIS_Navigation->setPage($VIS_Navigation->getDefaultPage());
	$VIS_BreadCrumb->add($VIS_Navigation->getNavigationTitle(), \osWFrame\Core\Settings::getStringVar('frame_current_module'), 'vistool='.$VIS_Main->getTool().'&vispage='.$VIS_Navigation->getPage());
	\osWFrame\Core\Network::directHeader(\osWFrame\Core\Navigation::buildUrl('current', 'vistool='.$VIS_Main->getTool().'&vispage='.$VIS_Navigation->getPage()));
}

$VIS_Main->addTemplateJSCode('head', 'var session_timeout=\''.\osWFrame\Core\Session::getSessionLifetime().'\';');
$VIS_Main->addTemplateJSCode('head', 'var session_logout=\''.\osWFrame\Core\Navigation::buildUrl('current', 'vistool='.\osWFrame\Core\Settings::getStringVar('vis_login_module')).'\';');

?>