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

$toolsdata=$VIS_User->getToolsSelectArray();
if (count($toolsdata)===1) {
	$vis_login_tool=array_key_first($toolsdata);
	$VIS_Main->setTool($vis_login_tool);

	osWFrame\Core\Cookie::setCookie('vis_login_tool', $vis_login_tool, osWFrame\Core\Settings::getIntVar('vis_login_cookie_lifetime'));
	osWFrame\Core\Network::directHeader(\osWFrame\Core\Navigation::buildUrl(\osWFrame\Core\Settings::getStringVar('frame_default_module'), 'vistool='.$VIS_Main->getTool()));
}

if (\osWFrame\Core\Settings::getAction()=='dochange') {
	$vis_login_tool=\osWFrame\Core\Settings::catchStringPostValue('vis_login_tool');
	if ($vis_login_tool=='') {
		$osW_Template->Form()->addErrorMessage('vis_login_tool', 'Bitte wählen Sie ein Programm aus.');
	} elseif (!isset($toolsdata[$vis_login_tool])) {
		$osW_Template->Form()->addErrorMessage('vis_login_tool', 'Sie haben keine Rechte für dieses Programm.');
	} elseif ($VIS_Main->setTool($vis_login_tool)!==true) {
		$osW_Template->Form()->addErrorMessage('vis_login_tool', 'Das Programm ist nicht verfügbar.');
	}

	if ($osW_Template->Form()->hasErrorMessages()===true) {
		osWFrame\Core\Settings::setAction('');
		$VIS_Main->setTool(\osWFrame\Core\Settings::getStringVar('vis_chtool_module'));
	} else {
		$VIS_Main->setTool($vis_login_tool);
		osWFrame\Core\Cookie::setCookie('vis_login_tool', $vis_login_tool, osWFrame\Core\Settings::getIntVar('vis_login_cookie_lifetime'));
		osWFrame\Core\Network::directHeader(\osWFrame\Core\Navigation::buildUrl(\osWFrame\Core\Settings::getStringVar('frame_default_module'), 'vistool='.$VIS_Main->getTool()));
	}
}

if (isset($_COOKIE['vis_login_tool'])) {
	$_POST['vis_login_tool']=$_COOKIE['vis_login_tool'];
}
?>