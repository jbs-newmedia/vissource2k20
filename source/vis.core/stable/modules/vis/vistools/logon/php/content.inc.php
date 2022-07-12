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

if (\osWFrame\Core\Settings::getAction()=='dologin') {
	$vis_login_email=\osWFrame\Core\Settings::catchStringPostValue('vis_login_email');
	if (\VIS\Core\User::validateEmail($vis_login_email)===true) {
		if ($VIS_User->loadUserDetailsByEMail($vis_login_email)===true) {
			$vis_login_password=\osWFrame\Core\Settings::catchStringPostValue('vis_login_password');
			if (strlen($vis_login_password)==0) {
				$osW_Template->Form()->addErrorMessage('vis_login_password', 'Bitte geben Sie Ihr Passwort ein.');
			} else {
				if ($VIS_User->getIntVar('user_status')==1) {
					if (\VIS\Core\Protect::isBlocked($VIS_User->getId())!==true) {
						if (\VIS\Core\User::validatePassword($vis_login_password, $VIS_User->getStringVar('user_password'))!==true) {
							$osW_Template->Form()->addErrorMessage('vis_login_password', 'Ihr Passwort ist falsch.');
							\VIS\Core\Protect::addEntry($VIS_User->getId());
						}
					} else {
						$time=\VIS\Core\Protect::getTime($VIS_User->getId());
						if ($time==0) {
							$osW_Template->Form()->addErrorMessage('vis_login_email', 'Sie sind gesperrt.');
						} else {
							$osW_Template->Form()->addErrorMessage('vis_login_email', 'Sie sind bis zum '.date('d.m.Y H:i:s', $time).' Uhr gesperrt.');
						}
					}
				} elseif ($VIS_User->getIntVar('user_status')==0) {
					$osW_Template->Form()->addErrorMessage('vis_login_email', 'Sie sind nicht aktiviert.');
				} elseif ($VIS_User->getIntVar('user_status')==2) {
					$osW_Template->Form()->addErrorMessage('vis_login_email', 'Sie sind blockiert.');
				}
			}
		} else {
			$osW_Template->Form()->addErrorMessage('vis_login_email', 'Ihre E-Mail-Adresse existiert nicht.');
		}
	} else {
		$osW_Template->Form()->addErrorMessage('vis_login_email', 'Ihre E-Mail-Adresse ist ungültig.');
	}

	if ($osW_Template->Form()->hasErrorMessages()===true) {
		osWFrame\Core\Settings::setAction('');
	} else {
		if ($VIS_User->createLogin()===true) {
			osWFrame\Core\SessionMessageStack::addMessage('session', 'success', ['msg'=>'Sie wurden erfolgreich eingeloggt.']);

			\VIS\Core\Protect::clearEntries($VIS_User->getId());

			$vis_login_link=\osWFrame\Core\Settings::catchValue(\osWFrame\Core\Settings::getStringVar('vis_path').'_login_link', '', 's');
			if ((strlen($vis_login_link)>0)&&($vis_login_link!='/'.\osWFrame\Core\Settings::getStringVar('vis_path'))&&($vis_login_link!='/'.\osWFrame\Core\Settings::getStringVar('vis_path').'/')&&($vis_login_link!='/'.\osWFrame\Core\Settings::getStringVar('vis_path').'/'.\osWFrame\Core\Settings::getStringVar('vis_login_tool').'/')) {
				osWFrame\Core\Session::removeVar(\osWFrame\Core\Settings::getStringVar('vis_path').'_login_link');
				osWFrame\Core\Network::directHeader($vis_login_link);
			} else {
				if (\osWFrame\Core\Settings::getStringVar('vis_default_tool')!='') {
					$VIS_Main->setTool(\osWFrame\Core\Settings::getStringVar('vis_login_tool'));
				} else {
					$toolsdata=$VIS_User->getTools();
					if (count($toolsdata)===1) {
						$VIS_Main->setTool(array_key_first($toolsdata));
					} else {
						$VIS_Main->setTool(\osWFrame\Core\Settings::getStringVar('vis_chtool_module'));
					}
				}
				osWFrame\Core\Network::directHeader(\osWFrame\Core\Navigation::buildUrl(\osWFrame\Core\Settings::getStringVar('frame_default_module'), 'vistool='.$VIS_Main->getTool()));
			}
		} else {
			$osW_Template->Form()->addErrorMessage('vis_login_email', 'Sie konnten nicht eingeloggt werden.');
		}
	}
}

?>