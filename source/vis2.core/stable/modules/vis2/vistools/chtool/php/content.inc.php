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

$toolsdata=$VIS2_User->getToolsSelectArray();
if (count($toolsdata)===1) {
	$vis2_login_tool=array_key_first($toolsdata);
	$VIS2_Main->setTool($vis2_login_tool);

	osWFrame\Core\Cookie::setCookie('vis2_login_tool', $vis2_login_tool, osWFrame\Core\Settings::getIntVar('vis2_login_cookie_lifetime'));
	osWFrame\Core\Network::directHeader(\osWFrame\Core\Navigation::buildUrl(\osWFrame\Core\Settings::getStringVar('frame_default_module'), 'vistool='.$VIS2_Main->getTool()));
}

if (osWFrame\Core\Settings::getStringVar('vis2_login_tool')!='') {
	$vis2_login_tool=osWFrame\Core\Settings::getStringVar('vis2_login_tool');
	$VIS2_Main->setTool($vis2_login_tool);
	osWFrame\Core\Cookie::setCookie('vis2_login_tool', $vis2_login_tool, osWFrame\Core\Settings::getIntVar('vis2_login_cookie_lifetime'));
	osWFrame\Core\Network::directHeader(\osWFrame\Core\Navigation::buildUrl(\osWFrame\Core\Settings::getStringVar('frame_default_module'), 'vistool='.$VIS2_Main->getTool()));
}

if (\osWFrame\Core\Settings::getAction()=='dochange') {
	$vis2_login_tool=\osWFrame\Core\Settings::catchStringPostValue('vis2_login_tool');
	if ($vis2_login_tool=='') {
		$osW_Template->Form()->addErrorMessage('vis2_login_tool', 'Bitte wählen Sie ein Programm aus.');
	} elseif (!isset($toolsdata[$vis2_login_tool])) {
		$osW_Template->Form()->addErrorMessage('vis2_login_tool', 'Sie haben keine Rechte für dieses Programm.');
	} elseif ($VIS2_Main->setTool($vis2_login_tool)!==true) {
		$osW_Template->Form()->addErrorMessage('vis2_login_tool', 'Das Programm ist nicht verfügbar.');
	}

	if ($osW_Template->Form()->hasErrorMessages()===true) {
		osWFrame\Core\Settings::setAction('');
		$VIS2_Main->setTool(\osWFrame\Core\Settings::getStringVar('vis2_chtool_module'));
	} else {
		$VIS2_Main->setTool($vis2_login_tool);
		osWFrame\Core\Cookie::setCookie('vis2_login_tool', $vis2_login_tool, osWFrame\Core\Settings::getIntVar('vis2_login_cookie_lifetime'));
		osWFrame\Core\Network::directHeader(\osWFrame\Core\Navigation::buildUrl(\osWFrame\Core\Settings::getStringVar('frame_default_module'), 'vistool='.$VIS2_Main->getTool()));
	}
}

if (isset($_COOKIE['vis2_login_tool'])) {
	$_POST['vis2_login_tool']=$_COOKIE['vis2_login_tool'];
}
?>